<?php
session_start();

header("Content-Type: application/json");

include "../cnx/cnx.php";
include "../global/variables.php";


ini_set("memory_limit", "1024M");

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Seteando librería para importar Excel
require "vendor/autoload.php";

// Para obtener el valor de las columnas de Excel
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

switch ($_POST["accion"]) {
    case "getAnticiposAndProviders":
        $response = [
            "cantidad_anticipos" => 0,
            "anticipos_con_saldo" => 0,
            "anticipos_sin_saldo" => 0,
            "proveedores" => [],
            "proveedores_con_anticipos" => [],
        ];

        // 1. Obtener la lista de proveedores APTOs para Select2 (cod_clientecondicion = 1)
        $sql_aptos = "
            SELECT
                pr.Id as id_proveedor,
                pr.documento,
                pr.razon_social
            FROM
                tb_clientes pr
            WHERE pr.cod_clientecondicion = 1
			ORDER BY pr.razon_social ASC;
        ";
        $res_aptos = mysqli_query($enlace, $sql_aptos);
        while ($p = mysqli_fetch_assoc($res_aptos)) {
            $response["proveedores"][] = $p;
        }

        // 2. Obtener TODAS las transacciones para mapearlas fácilmente (Query 3)
        $sql_transacciones = "
            SELECT
                tr.id AS id_transaccion,
                tr.id_proveedor_anticipo AS id_anticipo,
                tr.saldo_actual,
                tr.monto_retirado,
                tr.saldo_restante,
                val.codigo_unico,
                val.procedencia,
                val.concesion,
                tr.created_at AS fecha_registro
            FROM
                proveedor_anticipo_transaccion AS tr
            INNER JOIN valorizacion_compramineral AS val
            ON
                tr.id_valorizacion_compramineral = val.Id
            WHERE tr.estado = 'A'
            ORDER BY fecha_registro DESC;
        ";
        $res_transacciones = mysqli_query($enlace, $sql_transacciones);
        $transacciones_map = [];
        while ($t = mysqli_fetch_assoc($res_transacciones)) {
            $transacciones_map[$t["id_anticipo"]][] = $t;
        }
        // Liberar el resultado
        mysqli_free_result($res_transacciones);

        // 3. Obtener TODOS los anticipos (Query 2)
        $sql_anticipos = "
            SELECT
                ant.id as id_anticipo,
                ant.id_proveedor,
                ant.serie_factura,
                ant.numero_factura,
                ant.saldo_inicial,
                ant.saldo_actual,
                ant.cantidad_transacciones,
                DATE_FORMAT(ant.created_at, '%d/%m/%Y') as fecha_registro_formateada,
                ant.estado
            FROM
                proveedor_anticipo ant
            WHERE ant.estado != 'X';
        ";
        $res_anticipos = mysqli_query($enlace, $sql_anticipos);
        $anticipos_raw = [];
        while ($a = mysqli_fetch_assoc($res_anticipos)) {
            $anticipos_raw[] = $a;
        }
        // Liberar el resultado
        mysqli_free_result($res_anticipos);

        // 4. Obtener proveedores con anticipos (Query 1)
        $sql_proveedores_con_anticipos = "
            SELECT DISTINCT
                pr.Id AS id_proveedor,
                pr.documento,
                pr.razon_social
            FROM
                tb_clientes pr
            INNER JOIN proveedor_anticipo ant ON ant.id_proveedor = pr.Id;
        ";
        $res_proveedores_con_anticipos = mysqli_query(
            $enlace,
            $sql_proveedores_con_anticipos
        );
        $proveedores_con_anticipos_map = [];

        // Inicializar el mapa de proveedores con anticipos
        while ($p = mysqli_fetch_assoc($res_proveedores_con_anticipos)) {
            $proveedores_con_anticipos_map[$p["id_proveedor"]] = [
                "id_proveedor" => $p["id_proveedor"],
                "documento" => $p["documento"],
                "razon_social" => $p["razon_social"],
                "cantidad_anticipos" => 0,
                "anticipos_con_saldo" => 0,
                "anticipos_sin_saldo" => 0,
                "anticipos" => [],
            ];
        }
        // Liberar el resultado
        mysqli_free_result($res_proveedores_con_anticipos);

        // 5. Mapear anticipos a proveedores y sumar totales
        foreach ($anticipos_raw as $ant) {
            $id_proveedor = $ant["id_proveedor"];
            $id_anticipo = $ant["id_anticipo"];

            // Asignar transacciones al anticipo
            $ant["transacciones"] = $transacciones_map[$id_anticipo] ?? [];
            $ant["fecha_registro"] = $ant["fecha_registro_formateada"]; // Usar el campo con formato DD/MM/YYYY
            unset($ant["fecha_registro_formateada"]); // Limpieza

            if (isset($proveedores_con_anticipos_map[$id_proveedor])) {
                $proveedores_con_anticipos_map[$id_proveedor]["anticipos"][] = $ant;
                $proveedores_con_anticipos_map[$id_proveedor]["cantidad_anticipos"]++;
                $response["cantidad_anticipos"]++;

                if ($ant["estado"] === "A") {
                    // Asumo 'A' para CON SALDO
                    $proveedores_con_anticipos_map[$id_proveedor]["anticipos_con_saldo"]++;
                    $response["anticipos_con_saldo"]++;
                } else {
                    // Asumo 'B' para SALDO AGOTADO / SIN SALDO
                    $proveedores_con_anticipos_map[$id_proveedor]["anticipos_sin_saldo"]++;
                    $response["anticipos_sin_saldo"]++;
                }
            }
        }

        // Convertir el mapa de proveedores a una lista para el JSON final
        $response["proveedores_con_anticipos"] = array_values(
            $proveedores_con_anticipos_map
        );

        // Ordenar la lista de proveedores por Razon Social
        usort($response["proveedores_con_anticipos"], function ($a, $b) {
            return strcmp($a["razon_social"], $b["razon_social"]);
        });

        // Retornar la respuesta
        header("Content-Type: application/json");
        echo json_encode(["estado" => 1, "data" => $response]);
        break;

    case "get_info_cuenta_banco_valorizacion":
        $estado = 0;
        $data = []; // Usaremos 'data' en lugar de 'msg' para los resultados

        // Obtener y validar el ID del Comprobante de Pago
        $id_comprobante = isset($_POST["id_comprobante_pago"]) ? intval($_POST["id_comprobante_pago"]) : 0;

        if ($id_comprobante == 0) {
            // Devolver error si el ID no es válido
            echo json_encode(["estado" => 0, "msg" => "ID de Comprobante de Pago inválido."]);
            exit();
        }

        // La consulta SQL solicitada
        $q_data_pago = "
            SELECT
                ban.id AS id_banco,
                cli.Id as id_proveedor,
                ban.descripcion AS nombre_banco,
                vc.id_cuentabancaria,
                clb.nro_cuenta,
                clb.cci,
                mon.Id AS id_moneda,
                clb.is_detraccion,
                mon.abv AS simbolo_moneda
            FROM
                comprobante_pago cp
            INNER JOIN valorizacion_compramineral_detalle vcd ON
                vcd.Id = cp.id_valorizacion
            INNER JOIN valorizacion_compramineral vc ON
                vc.Id = vcd.id_valorizacion
            INNER JOIN tb_clientes cli ON
                cli.Id = vc.id_proveedor
            INNER JOIN tb_clientes_bancos clb ON
                clb.id_cliente = cli.Id AND clb.cci = vc.infopago_cci
            INNER JOIN tbconfig_monedas mon ON
                clb.id_moneda = mon.Id
            INNER JOIN tb_bancos ban ON
                ban.id = clb.id_banco
            WHERE
                cp.Id = $id_comprobante;
        "; // Se agrega LIMIT 1 ya que se espera una cuenta bancaria única por comprobante

        $res_data_pago = mysqli_query($enlace, $q_data_pago);

        if ($res_data_pago === false) {
            // Manejo de error de consulta SQL
            $msg = "Error al ejecutar la consulta: " . mysqli_error($enlace);
            echo json_encode(["estado" => 0, "msg" => $msg]);
            exit();
        }

        if (mysqli_num_rows($res_data_pago) > 0) {
            // Si se encuentra información, se lee la primera fila
            $row = mysqli_fetch_assoc($res_data_pago);
            $data = $row; // La información se almacena en el array $data
            $estado = 1;
            $msg = "Datos de pago obtenidos correctamente.";
        } else {
            // Si no se encuentra información
            $msg = "No se encontraron datos bancarios asociados al Comprobante de Pago ID $id_comprobante.";
        }
        echo json_encode([
            "estado" => $estado,
            "msg" => $msg ?? "", // Asegura que 'msg' exista
            "data" => $data
        ]);
        break;

    case "get_monto_total_valorizacion":
        $estado = 0;
        $data = [];

        // Obtener y validar el ID de la Valorización
        $id_valorizacion = isset($_POST["id_valorizacion"]) ? intval($_POST["id_valorizacion"]) : 0;

        if ($id_valorizacion == 0) {
            echo json_encode(["estado" => 0, "msg" => "ID de Valorizacion inválida."]);
            exit();
        }

        // 1. Consulta para obtener el monto total de la valorización
        $q_monto_total_valorizacion = "
            SELECT
                COALESCE(SUM(vcd.total), 0) AS monto_total_valorizacion
            FROM
                valorizacion_compramineral vc
            INNER JOIN valorizacion_compramineral_detalle vcd ON
                vcd.id_valorizacion = vc.Id
            WHERE
                vc.Id = $id_valorizacion AND vc.estado = 'A';
        ";

        $res_monto_total = mysqli_query($enlace, $q_monto_total_valorizacion);

        if ($res_monto_total === false) {
            $msg = "Error al ejecutar la consulta del monto total: " . mysqli_error($enlace);
            echo json_encode(["estado" => 0, "msg" => $msg]);
            exit();
        }

        // 2. Consulta para obtener el monto usado de anticipos
        $q_monto_anticipos = "
            SELECT
                COALESCE(SUM(pat.monto_retirado), 0) AS monto_total_anticipos
            FROM
                valorizacion_compramineral vc
            INNER JOIN proveedor_anticipo_transaccion pat ON
                pat.id_valorizacion_compramineral = vc.Id
            WHERE
                vc.Id = $id_valorizacion 
                AND vc.estado = 'A' 
                AND pat.estado = 'A';
        ";

        $res_monto_anticipos = mysqli_query($enlace, $q_monto_anticipos);

        if ($res_monto_anticipos === false) {
            $msg = "Error al ejecutar la consulta de anticipos: " . mysqli_error($enlace);
            echo json_encode(["estado" => 0, "msg" => $msg]);
            exit();
        }

        // Obtener resultados
        $row_monto_total = mysqli_fetch_assoc($res_monto_total);
        $row_monto_anticipos = mysqli_fetch_assoc($res_monto_anticipos);

        if ($row_monto_total) {
            $monto_total_valorizacion = floatval($row_monto_total['monto_total_valorizacion']);
            $monto_total_anticipos = floatval($row_monto_anticipos['monto_total_anticipos']);

            // Calcular saldo por transferencia
            $saldo_transferencia = $monto_total_valorizacion - $monto_total_anticipos;

            // Asegurarse de que no sea negativo (por si acaso)
            if ($saldo_transferencia < 0) {
                $saldo_transferencia = 0;
            }

            // Preparar los datos para la respuesta
            $data = [
                "monto_total_valorizacion" => $monto_total_valorizacion,
                "monto_total_anticipos" => $monto_total_anticipos,
                "saldo_transferencia" => $saldo_transferencia
            ];

            $estado = 1;
            $msg = "Montos obtenidos correctamente.";
        } else {
            $msg = "No se encontraron datos para la valorización ID $id_valorizacion.";
        }

        echo json_encode([
            "estado" => $estado,
            "msg" => $msg ?? "",
            "data" => $data
        ]);
        break;

    case "getReporteAnticiposTransacciones":
        $id_proveedor = intval($_POST["id_proveedor"] ?? 0);
        $fecha_desde = $_POST["fecha_desde"] ?? null;
        $fecha_hasta = $_POST["fecha_hasta"] ?? null;

        if ($id_proveedor == 0) {
            header("Content-Type: application/json");
            echo json_encode(["estado" => 0, "msg" => "ID de proveedor inválido."]);
            exit();
        }

        // 1. Obtener Anticipos del Proveedor
        // Regla: Estado 'B' o 'A'. Si es 'A', debe tener transacciones confirmadas.
        $q_anticipos = "
            SELECT 
                ant.id,
                ant.serie_factura,
                ant.numero_factura,
                ant.saldo_inicial,
                ant.saldo_actual,
                ant.created_at as fecha_registro,
                ant.estado
            FROM proveedor_anticipo ant
            WHERE ant.id_proveedor = $id_proveedor
              AND ant.estado != 'X'
              AND (
                  ant.estado = 'B' 
                  OR (ant.estado = 'A' AND ant.cantidad_transacciones > 0)
              )
            ORDER BY ant.created_at DESC
        ";

        $res_anticipos = mysqli_query($enlace, $q_anticipos);
        $data_final = [];

        while ($ant = mysqli_fetch_assoc($res_anticipos)) {
            $anticipo_id = $ant['id'];

            // 2. Obtener Transacciones para este Anticipo
            // Solo estado 'A' (Confirmado)
            $q_trans = "
                SELECT
                    tr.id,
                    tr.monto_retirado,
                    tr.saldo_actual AS saldo_antes_transaccion,
                    tr.saldo_restante,
                    tr.created_at AS fecha_transaccion,
                    val.Id AS id_valorizacion,
                    val.codigo_unico,
                    val.correlativo AS nro_valorizacion,
                    val.estado AS estado_val,
                    cp.serie_comprobante,
                    cp.numero_comprobante,
                    cp.fecha_emision_comprobante,
                    (
                    SELECT
                        SUM(vcd.total)
                    FROM
                        valorizacion_compramineral_detalle vcd
                    WHERE
                        vcd.id_valorizacion = val.Id AND vcd.estado = 'A'
                ) AS importe_factura_usd,
                cp.estado AS estado_comprobante,
                (
                    SELECT
                        GROUP_CONCAT(
                            DISTINCT IFNULL(vd.cod_gel, vd.cod_lote) SEPARATOR ', '
                        )
                    FROM
                        valorizacion_compramineral_detalle vd
                    WHERE
                        vd.id_valorizacion = val.Id
                ) AS lotes
                FROM
                    proveedor_anticipo_transaccion tr
                INNER JOIN valorizacion_compramineral val ON
                    tr.id_valorizacion_compramineral = val.Id
                LEFT JOIN comprobante_pago cp ON
                    cp.id_valorizacion = val.Id AND cp.estado = 'A'
                WHERE
                    tr.id_proveedor_anticipo = $anticipo_id AND tr.estado = 'A'
                ORDER BY
                    tr.created_at ASC;
            ";

            $res_trans = mysqli_query($enlace, $q_trans);
            $transacciones = [];
            $anticipoMatchesDate = false;

            while ($tr = mysqli_fetch_assoc($res_trans)) {

                // Verificación de fecha para determinar si se incluye el anticipo
                $fecha_comprobante = $tr['fecha_emision_comprobante'];
                $matches = true;
                if ($fecha_desde && (!$fecha_comprobante || $fecha_comprobante < $fecha_desde)) $matches = false;
                if ($fecha_hasta && (!$fecha_comprobante || $fecha_comprobante > $fecha_hasta)) $matches = false;

                if ($matches) {
                    $anticipoMatchesDate = true;
                }

                // Calculo de porcentajes y montos
                $monto_retirado = floatval($tr['monto_retirado']);
                $saldo_inicial_anticipo = floatval($ant['saldo_inicial']);

                $porcentaje_aplicado = 0;
                if ($saldo_inicial_anticipo > 0) {
                    $porcentaje_aplicado = ($monto_retirado / $saldo_inicial_anticipo) * 100;
                }

                $importe_factura_usd = $tr['importe_factura_usd'] ? floatval($tr['importe_factura_usd']) : 0;
                $saldo_factura_amortiza = $importe_factura_usd - $monto_retirado;
                $saldo_neto_factura_amortiza = $saldo_factura_amortiza >= 0 ? $saldo_factura_amortiza - ($saldo_factura_amortiza * 0.1) : 0;

                $transacciones[] = [
                    "id_transaccion" => $tr['id'],
                    "lotes" => $tr['lotes'],
                    "porcentaje_aplicado" => number_format($porcentaje_aplicado, 2) . '%',
                    "monto_aplicado" => round($monto_retirado, 2),

                    // Factura Venta Info
                    "factura_amortiza_serie" => $tr['serie_comprobante'] ? $tr['serie_comprobante'] . '-' . $tr['numero_comprobante'] : 'S/N',
                    "fecha_factura" => $tr['fecha_emision_comprobante'] ? $tr['fecha_emision_comprobante'] : '-',
                    "importe_factura_usd" => round($importe_factura_usd, 2),

                    "importe_amortiza_adelanto_usd" => round($monto_retirado, 2),

                    "saldo_factura_amortiza" => round($saldo_factura_amortiza, 2),
                    "saldo_neto_factura_amortiza" => round($saldo_neto_factura_amortiza, 2),

                    "saldo_deuda_usd" => round(floatval($tr['saldo_restante']), 2),

                    "estado_comprobante" => $tr['serie_comprobante'] ? ($tr['estado_comprobante'] == 'A' ? 'Pagado' : 'Pendiente') : 'Pendiente',
                    "nro_valorizacion" => $tr['nro_valorizacion']
                ];
            }

            // Lógica de inclusión:
            // 1. Si NO hay filtros de fecha, se incluye si tiene transacciones o es estado B.
            // 2. Si HAY filtros de fecha, se incluye si $anticipoMatchesDate es true.

            $include = false;
            if (!$fecha_desde && !$fecha_hasta) {
                if (count($transacciones) > 0 || $ant['estado'] == 'B') {
                    $include = true;
                }
            } else {
                if ($anticipoMatchesDate) {
                    $include = true;
                }
            }

            if ($include) {
                $data_final[] = [
                    "anticipo_info" => [
                        "factura" => $ant['serie_factura'] . '-' . $ant['numero_factura'],
                        "fecha" => $ant['fecha_registro'],
                        "importe_inicial" => round(floatval($ant['saldo_inicial']), 2),
                        "id" => $ant['id']
                    ],
                    "transacciones" => $transacciones
                ];
            }
        }

        header("Content-Type: application/json");
        echo json_encode(["estado" => 1, "data" => $data_final]);
        break;

    default:
        # code...

        break;
}
