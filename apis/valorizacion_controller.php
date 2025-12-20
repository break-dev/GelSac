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

function f_SetValorizacionCorrelativo(
    $enlace,
    $id_valorizacion,
    $g_fecha,
    $usuario_registro
) {
    $estado = 0;

    // 1. Obtiene el último Correlativo
    $correlativo = "0";

    $q_newcodigo = "SELECT IFNULL(MAX(correlativo), 0) AS CORRELATIVO
												FROM correlativo_valorizacion_compramineral
											 /*WHERE anho = 0
												 AND mes = 0
												 AND dia = 0*/";

    if ($res_newcodigo = mysqli_query($enlace, $q_newcodigo)) {
        if (mysqli_num_rows($res_newcodigo) > 0) {
            while ($row_newcodigo = mysqli_fetch_array($res_newcodigo)) {
                $correlativo = $row_newcodigo["CORRELATIVO"] + 1;
            }
        }
    }

    // 2. Asignando correlativo
    $q_update = "UPDATE valorizacion_compramineral SET ";
    $q_update .= "  correlativo = '$correlativo'";
    $q_update .= " WHERE Id = $id_valorizacion";

    if ($res_update = mysqli_query($enlace, $q_update)) {
    }

    // 3. Guardando correlativo
    $q_insert =
        "INSERT INTO correlativo_valorizacion_compramineral (correlativo, id_valorizacion, fechahora_registro, usuario_registro) VALUES (";
    $q_insert .= "'$correlativo', ";
    $q_insert .= "$id_valorizacion, ";
    $q_insert .= "'$g_fecha', ";
    $q_insert .= "'$usuario_registro')";

    if ($res_insert = mysqli_query($enlace, $q_insert)) {
        $estado = 1;
    }

    return $estado;
}

switch ($_POST["accion"]) {
    case "getAnticiposByProveedor":
        $id_proveedor = intval($_POST["id_proveedor"] ?? 0);

        if ($id_proveedor == 0) {
            header("Content-Type: application/json");
            echo json_encode([
                "estado" => 0,
                "msg" => "ID de proveedor inválido.",
                "data" => [],
            ]);
            exit();
        }

        // Obtener la lista de anticipos por proveedor
        $sql_query = "
                    SELECT
                        ant.id AS id_anticipo,
                        CONCAT(
                            ant.serie_factura,
                            '-',
                            ant.numero_factura
                        ) AS factura,
                        ant.saldo_actual,
                        ant.saldo_inicial,
                        ant.created_at AS fecha_registro
                    FROM
                        proveedor_anticipo ant
                    WHERE
                        ant.id_proveedor = '$id_proveedor' AND ant.estado = 'A' AND ant.saldo_actual > 0
                    ORDER BY
                        ant.created_at ASC;
                ";
        $response_query = mysqli_query($enlace, $sql_query);

        $anticipos = [];
        while ($row = mysqli_fetch_assoc($response_query)) {
            $row["saldo_actual"] = floatval($row["saldo_actual"]);
            $anticipos[] = $row;
        }

        // Liberar el resultado
        mysqli_free_result($response_query);

        // Retornar la respuesta
        header("Content-Type: application/json");
        echo json_encode(["estado" => 1, "data" => $anticipos]);
        break;
    case "get_ValorizacionCompra_ListaValorizaciones":
        $q_datos = "
            SELECT
                V.Id,
                MD5(V.Id) AS ID_MD5,
                V.correlativo,
                V.version,
                V.num_oficio,
                V.id_proveedor,
                V.id_concesion,
                P.documento AS ruc,
                P.razon_social AS proveedor,
                V.usuario_registro,
                V.is_aprobado,
                V.is_aprobado_fechahoraregistro,
                V.is_aprobado_usuarioregistro,
                V.id_cuentabancaria,
                V.id_cuentadetraccion,
                V.fechahora_registro,
                V.usuario_registro,
                V.estado,
                V.usa_anticipo,
                IFNULL(
                    (
                    SELECT
                        V_x.is_aprobado
                    FROM
                        valorizacion_compramineral V_x
                    WHERE
                        V_x.is_aprobado = 1 AND V_x.correlativo = V.correlativo
                ),
                0
                ) AS IS_VALORIZACIONAPROBADA,
                IF(
                    V.usa_anticipo = TRUE,
                    (
                    SELECT
                        GROUP_CONCAT(
                            CONCAT(
                                ant.serie_factura,
                                '-',
                                ant.numero_factura
                            ) SEPARATOR ', '
                        )
                    FROM
                        proveedor_anticipo_transaccion trans
                    INNER JOIN proveedor_anticipo ant ON
                        ant.id = trans.id_proveedor_anticipo
                    WHERE
                        trans.id_valorizacion_compramineral = V.Id
                ),
                NULL
                ) AS anticipos_usados,
                EXISTS(
                SELECT
                    1
                FROM
                    comprobante_pago cp
                WHERE
                    cp.id_valorizacion = V.Id AND cp.estado = 'A'
            ) AS tiene_comprobante
            FROM
                valorizacion_compramineral V
            LEFT JOIN tb_clientes P ON
                P.Id = V.id_proveedor
            WHERE
                V.estado <> 'X'
            ORDER BY
                V.correlativo
            DESC
                ,
                V.version
            DESC
                ,
                V.Id
            DESC
        ";

        $res = mysqli_query($enlace, $q_datos);
        $data = [];

        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }

        echo json_encode(["estado" => 1, "registros" => $data]);
        break;

    case "reabrir_Valorizacion":
        $estado = 0;
        $msg = "";

        $id_valorizacion = intval($_POST["id_valorizacion"]);
        $usuario_registro = $_SESSION["usu_usuario"];

        if ($id_valorizacion == 0) {
            echo json_encode(["estado" => 0, "msg" => "ID de valorización inválido."]);
            exit();
        }

        mysqli_begin_transaction($enlace);

        try {
            // Verificar si la valorización existe y está aprobada
            $q_check = "SELECT 
                V.Id,
                V.is_aprobado,
                V.usa_anticipo
            FROM valorizacion_compramineral V
            WHERE V.Id = $id_valorizacion
            AND V.estado = 'A'
            AND V.is_aprobado = 1";

            $res_check = mysqli_query($enlace, $q_check);

            if (mysqli_num_rows($res_check) == 0) {
                throw new Exception("La valorización no existe, no está activa o no está aprobada.");
            }

            $row_val = mysqli_fetch_assoc($res_check);
            $usa_anticipo = $row_val['usa_anticipo'] == 1;

            // Revertir aprobación
            $q_update = "UPDATE valorizacion_compramineral 
                        SET is_aprobado = 0,
                            is_aprobado_fechahoraregistro = NULL,
                            is_aprobado_usuarioregistro = NULL
                        WHERE Id = $id_valorizacion";

            if (!mysqli_query($enlace, $q_update)) {
                throw new Exception("Error al actualizar la valorización.");
            }

            // Revertir transacciones de anticipos si corresponde
            if ($usa_anticipo) {
                // Obtener transacciones confirmadas para revertir
                $q_transacciones = "
                    SELECT
                        trans.id,
                        trans.id_proveedor_anticipo,
                        trans.monto_retirado,
                        pa.saldo_actual
                    FROM
                        proveedor_anticipo_transaccion trans
                    INNER JOIN proveedor_anticipo pa on pa.id = trans.id_proveedor_anticipo
                    WHERE
                        trans.id_valorizacion_compramineral = $id_valorizacion AND trans.estado = 'A';
                    ";

                $res_trans = mysqli_query($enlace, $q_transacciones);

                while ($row = mysqli_fetch_assoc($res_trans)) {
                    $id_anticipo = $row['id_proveedor_anticipo'];
                    $monto_retirado = floatval($row['monto_retirado']);
                    $saldo_actual = floatval($row['saldo_actual']);

                    // Restaurar saldo del anticipo
                    $saldo_nuevo = $saldo_actual + $monto_retirado;

                    // Actualizar el anticipo
                    $q_actualizar_anticipo = "UPDATE proveedor_anticipo 
                                    SET saldo_actual = $saldo_nuevo,
                                        cantidad_transacciones = GREATEST(0, cantidad_transacciones - 1),
                                        estado = 'A', -- Volver a estado con saldo
                                        updated_at = '$g_fecha'
                                    WHERE id = $id_anticipo";

                    mysqli_query($enlace, $q_actualizar_anticipo);

                    // Cambiar estado de transacción a 'B' (Por confirmar)
                    $q_update_trans = "UPDATE proveedor_anticipo_transaccion 
                                    SET estado = 'B',
                                        updated_at = '$g_fecha'
                                    WHERE id = {$row['id']}";

                    mysqli_query($enlace, $q_update_trans);
                }
            }

            // Actualizar tablas relacionadas
            $q_update_leyes = "UPDATE import_resultadosleyes_detalle 
                            SET is_valorizado = 0,
                                is_valorizado_fechahoraregistro = NULL,
                                is_valorizado_usuarioregistro = NULL
                            WHERE cod_interno IN (
                                SELECT cod_lote 
                                FROM valorizacion_compramineral_detalle 
                                WHERE id_valorizacion = $id_valorizacion
                            )";

            mysqli_query($enlace, $q_update_leyes);

            $q_update_validacion = "UPDATE despachos_primertramo_validaciondatos 
                                SET codigogel_valorizado = 0,
                                    codigogel_valorizado_fechahoraregistro = NULL,
                                    codigogel_valorizado_usuarioregistro = NULL
                                WHERE lote_cod_lote IN (
                                    SELECT cod_lote 
                                    FROM valorizacion_compramineral_detalle 
                                    WHERE id_valorizacion = $id_valorizacion
                                )";

            mysqli_query($enlace, $q_update_validacion);

            mysqli_commit($enlace);
            $estado = 1;
            $msg = "Valorización reabierta correctamente. Las transacciones han sido revertidas a estado 'Por confirmar'.";
        } catch (Exception $e) {
            mysqli_rollback($enlace);
            $msg = $e->getMessage();
        }

        echo json_encode(["estado" => $estado, "msg" => $msg]);
        break;
    case "get_ValorizacionCompra_Detalle":
        $id_valorizacion = $_POST["id_valorizacion"];

        $sql = "SELECT VD.Id,
		  							 VD.id_elemento,
		  							 VD.cod_lote,
										 VD.cod_gel,
										 VD.guiaremision_remitente,
										 VD.guiaremision_transportista,
										 VD.fecha_ingreso,
										 VD.pesto_tmh,
										 VD.porc_h20,
										 VD.porc_h20,
										 VD.peso_tms,
										 VD.ley_oztc,
										 VD.porc_rec,
										 IFNULL(VD.precio_inter, '0.00') AS precio_inter,
										 IFNULL(VD.precio_inter_desc, '0.00') AS precio_inter_desc,
										 VD.maquila,
										 VD.precio_reac,
										 VD.factor,
										 VD.subtotal,
										 VD.incentivo,
										 VD.subtotal_final,
										 VD.total,
		  							 el.abv_valorizacion AS elemento,
		  							 el.abv AS elemento_original,
		  							 P.documento AS PROVEEDOR_RUC,

		            		 (SELECT L.id_CatalogoLotes FROM catalogolotes L WHERE L.ccod_Lote = VD.cod_lote) AS ID_LOTE

								FROM valorizacion_compramineral_detalle VD
										 LEFT JOIN tb_ensayos_analisis el ON el.Id = VD.id_elemento
										 LEFT JOIN valorizacion_compramineral V ON VD.id_valorizacion = V.Id
										 LEFT JOIN tb_clientes P ON V.id_proveedor = P.Id
		           WHERE VD.id_valorizacion = '$id_valorizacion' AND VD.estado <> 'X'
		          ORDER BY VD.Id ASC";

        $res = mysqli_query($enlace, $sql);
        $registros = [];

        while ($row = mysqli_fetch_assoc($res)) {
            $registros[] = $row;
        }

        echo json_encode([
            "estado" => 1,
            "registros" => $registros,
        ]);

        break;
    case "getAnticiposByValorizacion":
        $id_valorizacion = intval($_POST["id_valorizacion"] ?? 0);

        if ($id_valorizacion == 0) {
            header("Content-Type: application/json");
            echo json_encode(["estado" => 0, "msg" => "ID de valorización inválido.", "data" => []]);
            exit();
        }

        $sql_query = "
            SELECT 
                trans.id,
                trans.id_proveedor_anticipo,
                trans.monto_retirado,
                trans.saldo_actual,
                trans.saldo_restante,
                trans.estado,
                trans.created_at,
                ant.serie_factura,
                ant.numero_factura,
                CONCAT(ant.serie_factura, '-', ant.numero_factura) AS factura
            FROM proveedor_anticipo_transaccion trans
            INNER JOIN proveedor_anticipo ant ON ant.id = trans.id_proveedor_anticipo
            WHERE trans.id_valorizacion_compramineral = '$id_valorizacion'
            AND(
                trans.estado = 'A' OR trans.estado = 'B'
            )
            ORDER BY trans.created_at ASC;
        ";

        $response_query = mysqli_query($enlace, $sql_query);

        $transacciones = [];
        while ($row = mysqli_fetch_assoc($response_query)) {
            $row['monto_retirado'] = floatval($row['monto_retirado']);
            $row['saldo_actual'] = floatval($row['saldo_actual']);
            $row['saldo_restante'] = floatval($row['saldo_restante']);
            $transacciones[] = $row;
        }

        mysqli_free_result($response_query);

        header("Content-Type: application/json");
        echo json_encode(["estado" => 1, "data" => $transacciones]);
        break;
    case "get_ValorizacionCompra_ListaProveedores":
        $res = [];
        $estado = 0;

        $q = "SELECT DISTINCT C.Id, C.documento, C.razon_social
		          FROM despachos_primertramo_validaciondatos V
	    						 INNER JOIN tb_clientes C ON V.lote_id_proveedorminero = C.Id
		         /*WHERE V.guiaremitente_serie IS NOT NULL*/
		      ORDER BY C.razon_social";

        $r = mysqli_query($enlace, $q);
        while ($f = mysqli_fetch_assoc($r)) {
            $res[] = [
                "Id" => $f["Id"],
                "documento" => $f["documento"],
                "razon_social" => $f["razon_social"],
            ];
        }

        echo json_encode([
            "estado" => 1,
            "registros" => $res,
        ]);

        break;
    case "get_ValorizacionCompra_ListaConcesiones":
        $id_proveedor = $_POST["id_proveedor"];
        $res = [];

        $q = "SELECT DISTINCT PC.Id, PC.descripcion, PC.codigo_unico, PC.procedencia
		          FROM despachos_primertramo_validaciondatos V
		    					 INNER JOIN tbconfig_proveedoresmineros_concesion PC ON V.lote_id_proveedorminero_concesion = PC.Id
		         WHERE V.lote_id_proveedorminero = '$id_proveedor'
		           /*AND V.guiaremitente_serie IS NOT NULL*/";

        $r = mysqli_query($enlace, $q);
        while ($f = mysqli_fetch_assoc($r)) {
            $res[] = [
                "Id" => $f["Id"],
                "descripcion" => $f["descripcion"],
                "codigo_unico" => $f["codigo_unico"],
                "procedencia" => $f["procedencia"],
            ];
        }

        echo json_encode([
            "estado" => 1,
            "registros" => $res,
        ]);

        break;
    case "get_ValorizacionCompra_LotesDisponibles":
        $res = ["estado" => 0, "registros" => []];
        $id_proveedor = $_POST["id_proveedor"];
        $id_concesion = $_POST["id_concesion"];

        $q = "
        SELECT
            V.Id,
            V.lote_cod_lote,
            V.lote_id_lote AS ID_CODLOTE,
            IFNULL(CG.codigo_gel, 'Pendiente') AS CODIGO_GEL,
            CONCAT(
                V.guiaremitente_serie,
                '-',
                V.guiaremitente_numero
            ) AS GUIA_REMITENTE,
            CONCAT(
                V.guiatransportista_serie,
                '-',
                V.guiatransportista_numero
            ) AS GUIA_TRANSPORTISTA,
            V.lote_pesoinicial_fechahoraregistro,
            LC_NewAu.promedio AS ley_au_oz,
            LC_NewAg.promedio AS ley_ag_oz,
            LC_H2O.promedio AS h2o,
            LC_RECUP.promedio AS recup,
            CASE WHEN RD.gestionleyes_cerrado_isvalorizar = 0 THEN 1 ELSE 0
        END AS IS_SINVALORCOMERCIAL,
        (V.lote_peso_neto / 1000) AS TMH,
        ROUND(
            ((100 - LC_H2O.promedio) / 100) * 100,
            3
        ) AS tms,
        CASE WHEN(V.lote_peso_neto / 1000) <= 1 THEN 1 ELSE 1.1023
        END AS factor
        FROM
            despachos_primertramo_validaciondatos V
        LEFT JOIN consolidado_lotes_cierrecontable CC ON
            V.Id = CC.id_registro
        LEFT JOIN tb_leyes_analisis_cierre LC_NewAu ON
            V.lote_cod_lote = LC_NewAu.cod_lote AND LC_NewAu.id_grupo = 3 AND LC_NewAu.abv_elemento = 'newau'
        LEFT JOIN tb_leyes_analisis_cierre LC_NewAg ON
            V.lote_cod_lote = LC_NewAg.cod_lote AND LC_NewAg.id_grupo = 3 AND LC_NewAg.abv_elemento = 'newag'
        LEFT JOIN tb_leyes_analisis_cierre LC_H2O ON
            V.lote_cod_lote = LC_H2O.cod_lote AND LC_H2O.id_grupo = 4 AND LC_H2O.abv_elemento = 'h2o'
        LEFT JOIN tb_leyes_analisis_cierre LC_RECUP ON
            V.lote_cod_lote = LC_RECUP.cod_lote AND LC_RECUP.id_grupo = 5 AND LC_RECUP.abv_elemento = 'recup'
        LEFT JOIN correlativo_codigosgel CG ON
            V.Id = CG.id_validaciondatos
        INNER JOIN import_resultadosleyes_detalle RD ON
            V.lote_cod_lote = RD.cod_interno
        WHERE
            RD.gestionleyes_cerrado = 1 AND V.lote_id_proveedorminero = '$id_proveedor' AND V.lote_id_proveedorminero_concesion = '$id_concesion' AND NOT EXISTS(
            SELECT
                1
            FROM
                valorizacion_compramineral_detalle CMD
            INNER JOIN valorizacion_compramineral CM ON
                CMD.id_valorizacion = CM.Id
            LEFT JOIN comprobante_pago comp ON
                comp.id_valorizacion = CM.Id
            WHERE
                CMD.cod_lote = V.lote_cod_lote AND CMD.id_elemento IN(33, 34) AND CM.estado <> 'X' AND CM.is_aprobado = 1
        )
        ORDER BY
            V.Id
        DESC;
        ";
        $r = mysqli_query($enlace, $q);
        while ($f = mysqli_fetch_assoc($r)) {
            $res["registros"][] = $f;
        }

        $res["estado"] = 1;
        echo json_encode($res);

        break;
    case "get_ValorizacionCompra_CondicionesComerciales":
        $res = [];
        $estado = 0;

        $documento = mysqli_real_escape_string($enlace, $_POST["documento"]);
        $ley = floatval($_POST["ley"]);

        $q = "SELECT IFNULL(recuperacion, '') AS recuperacion,
		  						 IFNULL(maquila, '') AS maquila,
		  						 IFNULL(consumo, '') AS consumo
		          FROM condiciones_comerciales
		         WHERE documento_proveedorminero = '$documento'
		           AND $ley BETWEEN ley_auoz_inicio AND ley_auoz_fin
		           AND estado = 'A'";

        $r = mysqli_query($enlace, $q);
        if ($f = mysqli_fetch_assoc($r)) {
            $res["recuperacion"] = $f["recuperacion"];
            $res["maquila"] = $f["maquila"];
            $res["consumo"] = $f["consumo"];
            $estado = 1;
        }

        echo json_encode(["estado" => $estado] + $res);

        break;
    case "get_ValorizacionCompra_Elementos":
        $id_valorizacion = $_POST["id_valorizacion"];
        $id_valorizacion = strlen($id_valorizacion) == 0 ? 0 : $id_valorizacion;
        $cod_lote = trim($_POST["cod_lote"]);

        // Obtiene elementos ya registrados
        $in_elementos = "";

        $q_datos = "SELECT CMD.id_elemento
	  									FROM valorizacion_compramineral_detalle CMD
		           						 INNER JOIN valorizacion_compramineral CM ON CMD.id_valorizacion = CM.Id
		           			 WHERE CMD.cod_lote = '$cod_lote'
		           				 AND CM.estado <> 'X' AND CM.estado <> 'R'";

        if ($res_datos = mysqli_query($enlace, $q_datos)) {
            if (mysqli_num_rows($res_datos) > 0) {
                while ($row_datos = mysqli_fetch_array($res_datos)) {
                    $in_elementos .= $row_datos["id_elemento"] . ", ";
                }
            }
        }

        if (strlen($in_elementos) == 0) {
            $in_elementos = "''";
        } else {
            $in_elementos = substr($in_elementos, 0, -2);
        }

        $res = ["estado" => 0, "registros" => []];
        $q = "SELECT Id, abv 
		          FROM tb_ensayos_analisis 
		         WHERE estado = 'A' 
		           AND is_valorizacion = 1
		           AND Id NOT IN ($in_elementos)
		      ORDER BY orden";

        $r = mysqli_query($enlace, $q);

        while ($f = mysqli_fetch_assoc($r)) {
            $res["registros"][] = $f;
        }

        $res["estado"] = 1;
        echo json_encode($res);

        break;
    case "get_ValorizacionCompra_ListaCuentasBancariasProveedor":
        $res = [];
        $estado = 0;

        // Recupera parámetros
        $id_moneda = $_POST["id_moneda"];
        $id_proveedor = $_POST["id_proveedor"];
        $is_detraccion = $_POST["is_detraccion"];

        $q_datos = "SELECT CB.Id,
												 B.descripcion AS BANCO,
												 M.descripcion AS MONEDA,
												 CB.nro_cuenta,
											   CB.cci
										FROM tb_clientes_bancos CB
												 INNER JOIN tb_bancos B ON CB.id_banco = B.id
										     INNER JOIN tbconfig_monedas M ON CB.id_moneda = M.Id
									 WHERE CB.estado = 'A'
									   AND CB.is_detraccion = $is_detraccion
									   AND CB.id_moneda = $id_moneda
									   AND CB.id_cliente = $id_proveedor";

        if ($res_datos = mysqli_query($enlace, $q_datos)) {
            if (mysqli_num_rows($res_datos) > 0) {
                $estado = 1;

                while ($row_datos = mysqli_fetch_array($res_datos)) {
                    $res[] = [
                        "Id" => $row_datos["Id"],
                        "BANCO" => $row_datos["BANCO"],
                        "MONEDA" => $row_datos["MONEDA"],
                        "nro_cuenta" => $row_datos["nro_cuenta"],
                        "cci" => $row_datos["cci"],
                    ];
                }
            }
        }

        echo json_encode([
            "estado" => $estado,
            "registros" => $res,
        ]);

        break;
    case "grabar_ValorizacionCompra":
        $estado = 0;

        // Campos existentes
        $id_valorizacion = $_POST["id_valorizacion"];
        $id_proveedor = $_POST["id_proveedor"];
        $id_concesion = $_POST["id_concesion"];
        $concesion = $_POST["concesion"];
        $codigo_unico = $_POST["codigo_unico"];
        $procedencia = $_POST["procedencia"];
        $id_cuentabancaria = $_POST["id_cuentabancaria"];
        $info_cuentabancaria = $_POST["info_cuentabancaria"];
        $id_cuentadetraccion = $_POST["id_cuentadetraccion"];
        $info_cuentadetraccion = $_POST["info_cuentadetraccion"];
        $modo_grabar = $_POST["modo_grabar"];
        $arr_detalle = json_decode($_POST["arr_detalle"], true);
        $usuario_registro = $_SESSION["usu_usuario"];

        // Nuevos campos
        $usa_anticipo = isset($_POST["usa_anticipo"]) ? ($_POST["usa_anticipo"] === "true" || $_POST["usa_anticipo"] === true) : false;
        $es_pago_mixto = isset($_POST["es_pago_mixto"]) ? ($_POST["es_pago_mixto"] === "true" || $_POST["es_pago_mixto"] === true) : false;
        $anticipos_seleccionados = isset($_POST["anticipos_seleccionados"]) ? json_decode($_POST["anticipos_seleccionados"], true) : [];
        $monto_total_valorizacion = isset($_POST["monto_total_valorizacion"]) ? floatval($_POST["monto_total_valorizacion"]) : 0;
        $monto_transferencia = isset($_POST["monto_transferencia"]) ? floatval($_POST["monto_transferencia"]) : 0;

        mysqli_begin_transaction($enlace);

        try {
            // ===== DETECCIÓN AUTOMÁTICA DEL TIPO DE PAGO =====
            // Procesar basándose en los datos recibidos, no en flags
            $total_anticipos = 0;
            if (!empty($anticipos_seleccionados)) {
                foreach ($anticipos_seleccionados as $anticipo) {
                    $total_anticipos += floatval($anticipo['monto_a_usar'] ?? 0);
                }
            }

            $tiene_anticipos = $total_anticipos > 0;
            $tiene_cuenta_bancaria = !empty($id_cuentabancaria);

            // Detectar tipo de pago según datos reales
            if ($tiene_anticipos && $total_anticipos >= $monto_total_valorizacion) {
                // Solo anticipos
                $usa_anticipo = true;
                $es_pago_mixto = false;
                $id_mediopago = 10;
                error_log("Tipo de pago detectado: SOLO ANTICIPOS");
            } elseif ($tiene_anticipos && $total_anticipos > 0 && $tiene_cuenta_bancaria) {
                // Pago mixto
                $usa_anticipo = true;
                $es_pago_mixto = true;
                $id_mediopago = 11;
                error_log("Tipo de pago detectado: MIXTO (Anticipos + Transferencia)");
            } elseif ($tiene_cuenta_bancaria) {
                // Solo transferencia
                $usa_anticipo = false;
                $es_pago_mixto = false;
                $id_mediopago = 2;
                error_log("Tipo de pago detectado: SOLO TRANSFERENCIA");
            } else {
                throw new Exception("No se especificó ningún método de pago válido.");
            }

            // ===== VALIDACIONES SEGÚN TIPO DE PAGO =====
            if ($usa_anticipo && !$es_pago_mixto) {
                // Solo anticipos - validar que cubran el total
                if (empty($anticipos_seleccionados)) {
                    throw new Exception("Debe seleccionar al menos un anticipo.");
                }

                if (abs($total_anticipos - $monto_total_valorizacion) > 0.01) {
                    throw new Exception("Los anticipos seleccionados ($$total_anticipos) no cubren el monto total ($$monto_total_valorizacion).");
                }
            } elseif ($es_pago_mixto) {
                // Pago mixto - validar anticipos + cuenta bancaria
                if (empty($anticipos_seleccionados)) {
                    throw new Exception("Debe seleccionar al menos un anticipo.");
                }

                if (empty($id_cuentabancaria)) {
                    throw new Exception("Debe seleccionar una cuenta bancaria.");
                }

                if (empty($id_cuentadetraccion)) {
                    throw new Exception("Debe seleccionar una cuenta de detracción.");
                }

                // Validar que la suma sea correcta
                $suma_esperada = $total_anticipos + $monto_transferencia;
                if (abs($suma_esperada - $monto_total_valorizacion) > 0.01) {
                    throw new Exception("La suma de anticipos ($$total_anticipos) y transferencia ($$monto_transferencia) debe ser igual al total ($$monto_total_valorizacion).");
                }
            } else {
                // Solo transferencia - validar cuenta bancaria
                if (empty($id_cuentabancaria)) {
                    throw new Exception("Debe seleccionar una cuenta bancaria.");
                }

                if (empty($id_cuentadetraccion)) {
                    throw new Exception("Debe seleccionar una cuenta de detracción.");
                }

                // Asegurar que monto de transferencia sea el total
                if ($monto_transferencia == 0) {
                    $monto_transferencia = $monto_total_valorizacion;
                }
            }

            // Validar cuentas bancarias para transferencia o mixto
            if (!$usa_anticipo || $es_pago_mixto) {
                if (empty($id_cuentabancaria) || empty($info_cuentabancaria)) {
                    throw new Exception("Debe seleccionar una cuenta bancaria.");
                }

                // Procesar información de cuenta bancaria
                $info_cuentabancaria_parts = explode("|", $info_cuentabancaria);
                $info_bancomoneda = isset($info_cuentabancaria_parts[0]) ? trim($info_cuentabancaria_parts[0]) : '';

                $cuentabancaria_banco = "";
                $cuentabancaria_moneda = "";
                $cuentabancaria_cuenta = "";
                $cuentabancaria_cci = "";

                if (preg_match('/^(.*?)\s*\((.*?)\)$/', $info_bancomoneda, $matches)) {
                    $cuentabancaria_banco = mb_strtoupper(trim($matches[1]));
                    $cuentabancaria_moneda = mb_strtoupper(trim($matches[2]));
                }

                if (isset($info_cuentabancaria_parts[1])) {
                    $cuenta_part = explode(":", $info_cuentabancaria_parts[1]);
                    if (isset($cuenta_part[1])) {
                        $cuentabancaria_cuenta = trim($cuenta_part[1]);
                    }
                }

                if (isset($info_cuentabancaria_parts[2])) {
                    $cci_part = explode(":", $info_cuentabancaria_parts[2]);
                    if (isset($cci_part[1])) {
                        $cuentabancaria_cci = trim($cci_part[1]);
                    }
                }
            }

            // Guardando Cabecera
            $correlativo_valorizacion = "";

            if ($modo_grabar == "N") {
                $q_save = "INSERT INTO valorizacion_compramineral (
                    id_proveedor, id_concesion, concesion, codigo_unico, procedencia, 
                    correlativo, version, id_cuentabancaria, infopago_banco, infopago_moneda, 
                    infopago_cuenta, infopago_cci, id_cuentadetraccion, infopago_cuentadetraccion, 
                    id_mediopago, fechahora_registro, usuario_registro, usa_anticipo
                ) VALUES (
                    '$id_proveedor',
                    '$id_concesion',
                    '$concesion',
                    '$codigo_unico',
                    '$procedencia',
                    '$correlativo_valorizacion',
                    1,
                    " . ((!$usa_anticipo || $es_pago_mixto) ? "'$id_cuentabancaria'" : "NULL") . ",
                    " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_banco'" : "NULL") . ",
                    " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_moneda'" : "NULL") . ",
                    " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_cuenta'" : "NULL") . ",
                    " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_cci'" : "NULL") . ",
                    " . ((!$usa_anticipo || $es_pago_mixto) ? "'$id_cuentadetraccion'" : "NULL") . ",
                    " . ((!$usa_anticipo || $es_pago_mixto) ? "'$info_cuentadetraccion'" : "NULL") . ",
                    '$id_mediopago',
                    '$g_fecha',
                    '$usuario_registro',
                    " . ($usa_anticipo ? "TRUE" : "FALSE") . "
                )";

                if ($res_save = mysqli_query($enlace, $q_save)) {
                    $id_valorizacion = mysqli_insert_id($enlace);
                }
            } elseif ($modo_grabar == "E") {
                // Para edición, actualizar usa_anticipo y es_pago_mixto
                $q_update = "UPDATE valorizacion_compramineral 
                            SET usa_anticipo = " . ($usa_anticipo ? "TRUE" : "FALSE") . ",
                                id_cuentabancaria = " . ((!$usa_anticipo || $es_pago_mixto) ? "'$id_cuentabancaria'" : "NULL") . ",
                                infopago_banco = " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_banco'" : "NULL") . ",
                                infopago_moneda = " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_moneda'" : "NULL") . ",
                                infopago_cuenta = " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_cuenta'" : "NULL") . ",
                                infopago_cci = " . ((!$usa_anticipo || $es_pago_mixto) ? "'$cuentabancaria_cci'" : "NULL") . ",
                                id_cuentadetraccion = " . ((!$usa_anticipo || $es_pago_mixto) ? "'$id_cuentadetraccion'" : "NULL") . ",
                                infopago_cuentadetraccion = " . ((!$usa_anticipo || $es_pago_mixto) ? "'$info_cuentadetraccion'" : "NULL") . ",
                                id_mediopago = '$id_mediopago'
                            WHERE Id = $id_valorizacion";
                mysqli_query($enlace, $q_update);

                // Eliminar detalle existente
                $q_delete = "DELETE FROM valorizacion_compramineral_detalle
                            WHERE id_valorizacion = $id_valorizacion";
                mysqli_query($enlace, $q_delete);

                // También eliminar transacciones de anticipos pendientes si existían
                $q_delete_transacciones = "DELETE FROM proveedor_anticipo_transaccion
                                        WHERE id_valorizacion_compramineral = $id_valorizacion 
                                        AND estado = 'B'";
                mysqli_query($enlace, $q_delete_transacciones);
            }

            // Guardando Detalle
            foreach ($arr_detalle as $row) {
                $q_insert_det = "INSERT INTO valorizacion_compramineral_detalle (
                    id_valorizacion, id_elemento, cod_lote, cod_gel, guiaremision_remitente, 
                    guiaremision_transportista, fecha_ingreso, pesto_tmh, porc_h20, peso_tms, 
                    ley_oztc, porc_rec, precio_inter, precio_inter_desc, maquila, precio_reac, 
                    factor, subtotal, incentivo, subtotal_final, total, estado, fechahora_registro, 
                    usuario_registro
                ) VALUES (
                    $id_valorizacion,
                    '{$row["id_elemento"]}',
                    '" . mysqli_real_escape_string($enlace, $row["cod_lote"]) . "',
                    '" . mysqli_real_escape_string($enlace, $row["cod_gel"]) . "',
                    '" . mysqli_real_escape_string($enlace, $row["grr"]) . "',
                    '" . mysqli_real_escape_string($enlace, $row["grt"]) . "',
                    " . (empty($row["fecha_ingreso"]) ? "NULL" : "'" . mysqli_real_escape_string($enlace, $row["fecha_ingreso"]) . "'") . ",
                    " . (is_numeric($row["tmh"]) ? floatval($row["tmh"]) : "NULL") . ",
                    " . (is_numeric($row["h2o"]) ? floatval($row["h2o"]) : "NULL") . ",
                    " . (is_numeric($row["tms"]) ? floatval($row["tms"]) : "NULL") . ",
                    " . (is_numeric($row["ley"]) ? floatval($row["ley"]) : "NULL") . ",
                    " . (is_numeric($row["rec"]) ? floatval($row["rec"]) : "NULL") . ",
                    " . (is_numeric($row["inter"]) ? floatval($row["inter"]) : "NULL") . ",
                    " . (is_numeric($row["descint"]) ? floatval($row["descint"]) : "NULL") . ",
                    " . (is_numeric($row["maquila"]) ? floatval($row["maquila"]) : "NULL") . ",
                    " . (is_numeric($row["react"]) ? floatval($row["react"]) : "NULL") . ",
                    " . (is_numeric($row["factor"]) ? floatval($row["factor"]) : "NULL") . ",
                    " . (is_numeric($row["ptn"]) ? floatval($row["ptn"]) : "NULL") . ",
                    " . (is_numeric($row["incent"]) ? floatval($row["incent"]) : "NULL") . ",
                    " . (is_numeric($row["ptnf"]) ? floatval($row["ptnf"]) : "NULL") . ",
                    " . (is_numeric($row["total"]) ? floatval($row["total"]) : "NULL") . ",
                    'A',
                    '$g_fecha',
                    '$usuario_registro'
                )";

                mysqli_query($enlace, $q_insert_det);
            }

            // Registrar transacciones de anticipos si se usan
            if ($usa_anticipo && !empty($anticipos_seleccionados)) {
                error_log("Procesando " . count($anticipos_seleccionados) . " anticipos...");

                foreach ($anticipos_seleccionados as $idx => $anticipo) {
                    // Validar que existan los campos necesarios
                    if (!isset($anticipo['id_anticipo']) || empty($anticipo['id_anticipo'])) {
                        error_log("ERROR: Anticipo[$idx] no tiene id_anticipo. Datos: " . print_r($anticipo, true));
                        throw new Exception("Error en anticipo #" . ($idx + 1) . ": Falta el ID del anticipo.");
                    }

                    if (!isset($anticipo['monto_a_usar'])) {
                        error_log("ERROR: Anticipo[$idx] no tiene monto_a_usar. Datos: " . print_r($anticipo, true));
                        throw new Exception("Error en anticipo #" . ($idx + 1) . ": Falta el monto a usar.");
                    }

                    $id_anticipo = intval($anticipo['id_anticipo']);
                    $monto_a_usar = floatval($anticipo['monto_a_usar']);

                    // Validar que los valores sean válidos
                    if ($id_anticipo <= 0) {
                        error_log("ERROR: Anticipo[$idx] tiene id_anticipo inválido: $id_anticipo");
                        throw new Exception("Error en anticipo #" . ($idx + 1) . ": ID de anticipo inválido.");
                    }

                    if ($monto_a_usar <= 0) {
                        error_log("ERROR: Anticipo[$idx] tiene monto_a_usar inválido: $monto_a_usar");
                        throw new Exception("Error en anticipo #" . ($idx + 1) . ": Monto a usar debe ser mayor a cero.");
                    }

                    error_log("Insertando transacción: id_anticipo=$id_anticipo, monto=$monto_a_usar");

                    $q_insert_transaccion = "INSERT INTO proveedor_anticipo_transaccion (
                        id_proveedor_anticipo, id_valorizacion_compramineral, monto_retirado, 
                        estado, created_at
                    ) VALUES (
                        $id_anticipo,
                        $id_valorizacion,
                        $monto_a_usar,
                        'B', -- Por confirmar
                        '$g_fecha'
                    )";

                    if (!mysqli_query($enlace, $q_insert_transaccion)) {
                        $mysql_error = mysqli_error($enlace);
                        error_log("ERROR SQL al insertar transacción de anticipo: $mysql_error");
                        throw new Exception("Error al registrar transacción de anticipo #" . ($idx + 1) . ": $mysql_error");
                    }

                    error_log("Transacción de anticipo insertada exitosamente (ID: $id_anticipo)");
                }

                error_log("Todas las transacciones de anticipos se procesaron correctamente.");
            } elseif ($usa_anticipo && empty($anticipos_seleccionados)) {
                error_log("WARNING: usa_anticipo=true pero anticipos_seleccionados está vacío");
            }

            // Generando Correlativo de Valorización
            if ($modo_grabar == "N") {
                f_SetValorizacionCorrelativo(
                    $enlace,
                    $id_valorizacion,
                    $g_fecha,
                    $usuario_registro
                );
            }

            mysqli_commit($enlace);
            $estado = 1;
        } catch (Exception $e) {
            mysqli_rollback($enlace);
            echo json_encode(["estado" => 0, "msg" => $e->getMessage()]);
            exit();
        }

        echo json_encode(["estado" => $estado, "id_valorizacion" => $id_valorizacion]);
        break;
    case "eliminar_ValorizacionDetalle":
        $estado = 0;

        $id_registro = mysqli_real_escape_string(
            $enlace,
            $_POST["id_registro"]
        );

        $q_query = "DELETE FROM valorizacion_compramineral_detalle
									 WHERE Id = $id_registro";

        if ($res = mysqli_query($enlace, $q_query)) {
            $estado = 1;
        }

        echo json_encode(["estado" => $estado]);

        break;
    case "eliminar_Valorizacion":
        $res = [];
        $estado = 0;
        $msg = "";

        $modo = $_POST["modo"]; // 'I' = Inactivar, 'X' = Eliminar
        $id = intval($_POST["id_registro"]);
        $usuario_registro = $_SESSION["usu_usuario"];

        mysqli_begin_transaction($enlace);

        try {
            // Primero, verificar el estado actual de la valorización
            $q_check = "SELECT 
                estado, 
                is_aprobado, 
                usa_anticipo 
            FROM valorizacion_compramineral 
            WHERE Id = $id";

            $res_check = mysqli_query($enlace, $q_check);

            if (mysqli_num_rows($res_check) == 0) {
                throw new Exception("La valorización no existe.");
            }

            $row_check = mysqli_fetch_assoc($res_check);
            $estado_actual = $row_check['estado'];
            $is_aprobado = $row_check['is_aprobado'];
            $usa_anticipo = $row_check['usa_anticipo'] == 1;

            // Verificar si ya está inactiva o eliminada
            if ($estado_actual == 'X' && $modo == 'X') {
                throw new Exception("La valorización ya se encuentra eliminada.");
            }

            // Verificar si ya está eliminada
            if ($estado_actual == 'I' && $modo == 'I') {
                throw new Exception("La valorización ya se encuentra eliminada");
            }

            // Si está aprobada, no se puede eliminar, solo inactivar
            if ($is_aprobado == 1 && $modo == 'X') {
                throw new Exception("No se puede eliminar una valorización aprobada. Solo se puede inactivar.");
            }

            // Si usa anticipos y está aprobada, hay que revertir las transacciones
            if ($usa_anticipo && $is_aprobado == 1) {
                // Obtener transacciones confirmadas para revertir
                $q_transacciones = "SELECT 
                    trans.id,
                    trans.id_proveedor_anticipo,
                    trans.monto_retirado
                FROM proveedor_anticipo_transaccion trans
                WHERE trans.id_valorizacion_compramineral = $id 
                AND trans.estado = 'A'";

                $res_trans = mysqli_query($enlace, $q_transacciones);

                while ($row = mysqli_fetch_assoc($res_trans)) {
                    $id_anticipo = $row['id_proveedor_anticipo'];
                    $monto_retirado = floatval($row['monto_retirado']);

                    // Obtener el saldo actual del ANTICIPO (no de la transacción)
                    $q_saldo_actual = "SELECT saldo_actual, cantidad_transacciones 
                                    FROM proveedor_anticipo 
                                    WHERE id = $id_anticipo 
                                    AND estado IN ('A', 'B')";

                    $res_saldo = mysqli_query($enlace, $q_saldo_actual);

                    if ($res_saldo && mysqli_num_rows($res_saldo) > 0) {
                        $row_saldo = mysqli_fetch_assoc($res_saldo);
                        $saldo_actual_anticipo = floatval($row_saldo['saldo_actual']);
                        $cantidad_transacciones = intval($row_saldo['cantidad_transacciones']);

                        // Calcular nuevo saldo
                        $saldo_nuevo = $saldo_actual_anticipo + $monto_retirado;

                        // Actualizar el anticipo
                        $q_actualizar_anticipo = "UPDATE proveedor_anticipo 
                                        SET saldo_actual = $saldo_nuevo,
                                            cantidad_transacciones = GREATEST(0, $cantidad_transacciones - 1),
                                            estado = CASE 
                                                WHEN $saldo_nuevo > 0 THEN 'A' 
                                                ELSE 'B' 
                                            END,
                                            updated_at = '$g_fecha'
                                        WHERE id = $id_anticipo";

                        mysqli_query($enlace, $q_actualizar_anticipo);
                    }
                    // Cambiar estado de transacción a 'C' (Cancelada)
                    $q_update_trans = "UPDATE proveedor_anticipo_transaccion 
                                    SET estado = 'C',
                                        updated_at = '$g_fecha'
                                    WHERE id = {$row['id']}";

                    mysqli_query($enlace, $q_update_trans);
                }
            }
            // Si usa anticipos pero NO está aprobada, eliminar transacciones pendientes
            elseif ($usa_anticipo && $is_aprobado == 0) {
                $q_delete_trans = "DELETE FROM proveedor_anticipo_transaccion 
                                WHERE id_valorizacion_compramineral = $id 
                                AND estado = 'B'";

                mysqli_query($enlace, $q_delete_trans);
            }

            // Actualizar el estado de la valorización
            $q_update = "UPDATE valorizacion_compramineral 
                        SET estado = '$modo'
                        WHERE Id = $id";

            if (mysqli_query($enlace, $q_update)) {
                $estado = 1;
                $msg = "Valorización " .
                    ($modo == 'X' ? "eliminada" : "inactivada") .
                    " correctamente" .
                    ($usa_anticipo && $is_aprobado == 1 ? " (transacciones de anticipos revertidas)" : "") . ".";

                // Si se está inactivando una valorización aprobada, también actualizar las tablas relacionadas
                if ($modo == 'I' && $is_aprobado == 1) {
                    // Actualizar tablas de leyes
                    $q_update_leyes = "UPDATE import_resultadosleyes_detalle 
                                    SET is_valorizado = 0,
                                        is_valorizado_fechahoraregistro = NULL,
                                        is_valorizado_usuarioregistro = NULL
                                    WHERE cod_interno IN (
                                        SELECT cod_lote 
                                        FROM valorizacion_compramineral_detalle 
                                        WHERE id_valorizacion = $id
                                    )";

                    mysqli_query($enlace, $q_update_leyes);

                    // Actualizar tabla de validación de lotes
                    $q_update_validacion = "UPDATE despachos_primertramo_validaciondatos 
                                        SET codigogel_valorizado = 0,
                                            codigogel_valorizado_fechahoraregistro = NULL,
                                            codigogel_valorizado_usuarioregistro = NULL
                                        WHERE lote_cod_lote IN (
                                            SELECT cod_lote 
                                            FROM valorizacion_compramineral_detalle 
                                            WHERE id_valorizacion = $id
                                        )";

                    mysqli_query($enlace, $q_update_validacion);
                }
            } else {
                throw new Exception("Error al actualizar la valorización.");
            }

            mysqli_commit($enlace);
        } catch (Exception $e) {
            mysqli_rollback($enlace);
            $msg = $e->getMessage();
        }

        $res["estado"] = $estado;
        $res["msg"] = $msg;
        echo json_encode($res);
        break;
    case "grabar_ClienteBanco":
        $res = ["estado" => 0];

        $modo_grabar = $_POST["modo_grabar"];
        $id_cliente_banco = (isset($_POST["id_cliente_banco"])) ? intval($_POST["id_cliente_banco"]) : 0;
        $id_cliente = mysqli_real_escape_string($enlace, $_POST["id_cliente"]);
        $id_banco = intval($_POST["id_banco"]);
        $nro_cuenta = mysqli_real_escape_string($enlace, $_POST["nro_cuenta"]);
        $cci = mysqli_real_escape_string($enlace, $_POST["cci"]);
        $id_moneda = intval($_POST["id_moneda"]);
        $is_detraccion = intval($_POST["is_detraccion"]);
        $fechahora_actual = date("Y-m-d H:i:s");
        $usuario_registro = $_SESSION["usu_usuario"];

        // Validación datos
        $q_exists = "SELECT COUNT(Id) AS _EXISTS
											 FROM tb_clientes_bancos
											WHERE estado <> 'X'
												AND id_banco = $id_banco
												AND nro_cuenta = '$nro_cuenta'";

        // Grabando datos
        if ($modo_grabar == "N") {
            // Valida que el DNI / RUC ingresado no haya sido ingresado antes
            if ($res_exists = mysqli_query($enlace, $q_exists)) {
                if (mysqli_num_rows($res_exists) > 0) {
                    while ($row_exists = mysqli_fetch_array($res_exists)) {
                        if ($row_exists["_EXISTS"] > 0) {
                            $estado = 2;

                            echo json_encode(["estado" => $estado]);
                            return;
                        }
                    }
                }
            }

            $q = "INSERT INTO tb_clientes_bancos (
	                id_cliente, id_banco, nro_cuenta, cci,
	                id_moneda, is_detraccion, estado,
	                fechahora_registro, usuario_registro
	              ) VALUES (
	                '$id_cliente', $id_banco, '$nro_cuenta', '$cci',
	                $id_moneda, $is_detraccion, 'A',
	                '$fechahora_actual', '$usuario_registro'
	              )";
        } else {
            // Valida que el DNI / RUC ingresado no haya sido ingresado antes
            $q_exists .= "   AND Id <> " . $id_cliente_banco;

            if ($res_exists = mysqli_query($enlace, $q_exists)) {
                if (mysqli_num_rows($res_exists) > 0) {
                    while ($row_exists = mysqli_fetch_array($res_exists)) {
                        if ($row_exists["_EXISTS"] > 0) {
                            $estado = 2;

                            echo json_encode(["estado" => $estado]);
                            return;
                        }
                    }
                }
            }

            $q = "UPDATE tb_clientes_bancos SET
	                id_banco = $id_banco,
	                nro_cuenta = '$nro_cuenta',
	                cci = '$cci',
	                id_moneda = $id_moneda,
	                is_detraccion = $is_detraccion
	              WHERE Id = $id_cliente_banco";
        }

        if (mysqli_query($enlace, $q)) {
            $res["estado"] = 1;

            if ($modo_grabar == "N") {
                $res["id_registro"] = mysqli_insert_id($enlace);
            }
        }

        echo json_encode($res);
        break;
    case "grabar_ValorizacionCompra_NuevaVersion":
        $estado = 0;
        $mensaje = "";

        $id_registro = mysqli_real_escape_string($enlace, $_POST["id_registro"]);
        $num_valorizacion = mysqli_real_escape_string($enlace, $_POST["num_valorizacion"]);
        $usuario_registro = $_SESSION["usu_usuario"];

        // Primero, verificar si la valorización original usa anticipos
        $q_check_anticipo = "SELECT usa_anticipo FROM valorizacion_compramineral WHERE Id = $id_registro";
        $res_check = mysqli_query($enlace, $q_check_anticipo);
        $usa_anticipo = 0;

        if ($res_check && mysqli_num_rows($res_check) > 0) {
            $row_check = mysqli_fetch_assoc($res_check);
            $usa_anticipo = $row_check['usa_anticipo'];
        }

        // Copiando datos de cabecera
        $q_insert = "
                    INSERT INTO valorizacion_compramineral(
                        id_proveedor,
                        id_concesion,
                        concesion,
                        codigo_unico,
                        procedencia,
                        correlativo,
                        num_oficio,
                        version,
                        is_copia,
                        is_copia_de,
                        id_cuentabancaria,
                        infopago_banco,
                        infopago_moneda,
                        infopago_cuenta,
                        infopago_cci,
                        id_cuentadetraccion,
                        infopago_cuentadetraccion,
                        id_mediopago,
                        fechahora_registro,
                        usuario_registro,
                        usa_anticipo
                    )
                    ";
        $q_insert .= "
                    SELECT
                        id_proveedor,
                        id_concesion,
                        concesion,
                        codigo_unico,
                        procedencia,
                        correlativo,
                        num_oficio,
                        (
                        SELECT
                            MAX(CM.version) + 1
                        FROM
                            valorizacion_compramineral CM
                        WHERE
                            CM.correlativo = valorizacion_compramineral.correlativo AND CM.estado <> 'X'
                    ),
                    1,
                    $id_registro,
                    id_cuentabancaria,
                    infopago_banco,
                    infopago_moneda,
                    infopago_cuenta,
                    infopago_cci,
                    id_cuentadetraccion,
                    infopago_cuentadetraccion,
                    id_mediopago,
                    '$g_fecha',
                    '$usuario_registro',
                    usa_anticipo
                    FROM
                        valorizacion_compramineral
                    WHERE
                        Id = $id_registro
                    ";

        if ($res_insert = mysqli_query($enlace, $q_insert)) {
            $id_valorizacion = mysqli_insert_id($enlace);

            // Copiando datos del detalle
            $q_insert2 = "
                        INSERT INTO valorizacion_compramineral_detalle(
                            id_valorizacion,
                            id_elemento,
                            cod_lote,
                            cod_gel,
                            guiaremision_remitente,
                            guiaremision_transportista,
                            fecha_ingreso,
                            pesto_tmh,
                            porc_h20,
                            peso_tms,
                            ley_oztc,
                            porc_rec,
                            precio_inter,
                            precio_inter_desc,
                            maquila,
                            precio_reac,
                            factor,
                            subtotal,
                            incentivo,
                            subtotal_final,
                            total,
                            fechahora_registro,
                            usuario_registro
                        )
                        ";
            $q_insert2 .= "
                        SELECT
                            $id_valorizacion,
                            id_elemento,
                            cod_lote,
                            cod_gel,
                            guiaremision_remitente,
                            guiaremision_transportista,
                            fecha_ingreso,
                            pesto_tmh,
                            porc_h20,
                            peso_tms,
                            ley_oztc,
                            porc_rec,
                            precio_inter,
                            precio_inter_desc,
                            maquila,
                            precio_reac,
                            factor,
                            subtotal,
                            incentivo,
                            subtotal_final,
                            total,
                            '$g_fecha',
                            '$usuario_registro'
                        FROM
                            valorizacion_compramineral_detalle
                        WHERE
                            id_valorizacion = $id_registro
                        ";

            if ($res_insert2 = mysqli_query($enlace, $q_insert2)) {
                // Si la valorización original usa anticipos, copiar también los registros de anticipos
                if ($usa_anticipo == 1) {
                    // Obtener los anticipos de la valorización original
                    $q_get_anticipos = "
                        SELECT 
                            id_proveedor_anticipo,
                            monto_retirado,
                            estado
                        FROM proveedor_anticipo_transaccion
                        WHERE id_valorizacion_compramineral = $id_registro
                    ";

                    $res_anticipos = mysqli_query($enlace, $q_get_anticipos);

                    if ($res_anticipos && mysqli_num_rows($res_anticipos) > 0) {
                        $anticipos_copiados = 0;

                        while ($row_anticipo = mysqli_fetch_assoc($res_anticipos)) {
                            // Para cada anticipo, crear un nuevo registro en proveedor_anticipo_transaccion
                            // No copiamos saldo_actual ni saldo_restante como especificaste
                            $id_proveedor_anticipo = $row_anticipo['id_proveedor_anticipo'];
                            $monto_retirado = $row_anticipo['monto_retirado'];
                            $estado_original = $row_anticipo['estado'];

                            $q_insert_anticipo = "
                                INSERT INTO proveedor_anticipo_transaccion(
                                    id_proveedor_anticipo,
                                    id_valorizacion_compramineral,
                                    monto_retirado,
                                    created_at,
                                    estado
                                ) VALUES (
                                    $id_proveedor_anticipo,
                                    $id_valorizacion,
                                    $monto_retirado,
                                    '$g_fecha',
                                    '$estado_original'
                                )
                            ";

                            if (mysqli_query($enlace, $q_insert_anticipo)) {
                                $anticipos_copiados++;
                            }
                        }

                        $mensaje = "Valorización copiada correctamente. Se copiaron $anticipos_copiados anticipos.";
                    } else {
                        $mensaje = "Valorización copiada correctamente. La valorización original usa anticipos pero no se encontraron registros para copiar.";
                    }
                } else {
                    $mensaje = "Valorización copiada correctamente.";
                }

                $estado = 1;
            } else {
                // Si falla la copia del detalle, eliminar la cabecera creada
                mysqli_query($enlace, "DELETE FROM valorizacion_compramineral WHERE Id = $id_valorizacion");
                $mensaje = "Error al copiar el detalle de la valorización.";
            }
        } else {
            $mensaje = "Error al copiar la cabecera de la valorización.";
        }

        echo json_encode(["estado" => $estado, "msg" => $mensaje]);
        break;
    case "grabar_ValorizacionCompra_Aprobacion":
        $estado = 0;
        $msg = "";

        $id_registro = mysqli_real_escape_string($enlace, $_POST["id_registro"]);
        $num_valorizacion = mysqli_real_escape_string($enlace, $_POST["num_valorizacion"]);
        $version = mysqli_real_escape_string($enlace, $_POST["version"]);
        $is_aprobado = mysqli_real_escape_string($enlace, $_POST["is_aprobado"]);
        $usuario_registro = $_SESSION["usu_usuario"];

        mysqli_begin_transaction($enlace);

        try {
            // Primero, verificar si la valorización usa anticipos
            $q_check_anticipo = "SELECT usa_anticipo FROM valorizacion_compramineral WHERE Id = $id_registro";
            $res_check = mysqli_query($enlace, $q_check_anticipo);
            $row_check = mysqli_fetch_assoc($res_check);
            $usa_anticipo = $row_check['usa_anticipo'] == 1;

            if ($is_aprobado == 1 && $usa_anticipo) {
                // Verificar transacciones pendientes
                $q_transacciones = "SELECT 
                    trans.id,
                    trans.id_proveedor_anticipo,
                    trans.monto_retirado,
                    ant.saldo_actual as saldo_disponible
                FROM proveedor_anticipo_transaccion trans
                INNER JOIN proveedor_anticipo ant ON ant.id = trans.id_proveedor_anticipo
                WHERE trans.id_valorizacion_compramineral = $id_registro 
                AND trans.estado = 'B'";

                $res_trans = mysqli_query($enlace, $q_transacciones);
                $transacciones = [];

                while ($row = mysqli_fetch_assoc($res_trans)) {
                    $transacciones[] = $row;
                }

                // Verificar que cada anticipo tenga saldo suficiente
                foreach ($transacciones as $trans) {
                    if ($trans['monto_retirado'] > $trans['saldo_disponible']) {
                        throw new Exception("El anticipo {$trans['id_proveedor_anticipo']} no tiene saldo suficiente. Saldo disponible: {$trans['saldo_disponible']}, Monto a retirar: {$trans['monto_retirado']}");
                    }
                }

                // Actualizar saldos de anticipos y confirmar transacciones
                foreach ($transacciones as $trans) {
                    $id_anticipo = $trans['id_proveedor_anticipo'];
                    $monto_retirado = $trans['monto_retirado'];
                    $saldo_actual = $trans['saldo_disponible'];
                    $saldo_restante = $saldo_actual - $monto_retirado;

                    // Actualizar transacción
                    $q_update_trans = "UPDATE proveedor_anticipo_transaccion 
                                    SET saldo_actual = $saldo_actual,
                                        saldo_restante = $saldo_restante,
                                        estado = 'A',
                                        updated_at = '$g_fecha'
                                    WHERE id = {$trans['id']}";
                    mysqli_query($enlace, $q_update_trans);

                    // Actualizar anticipo
                    $q_update_anticipo = "UPDATE proveedor_anticipo 
                                        SET saldo_actual = $saldo_restante,
                                            cantidad_transacciones = cantidad_transacciones + 1,
                                            updated_at = '$g_fecha'
                                        WHERE id = $id_anticipo";
                    mysqli_query($enlace, $q_update_anticipo);

                    // Si el saldo llega a 0, cambiar estado a 'B' (Sin saldo)
                    if ($saldo_restante <= 0) {
                        $q_update_estado = "UPDATE proveedor_anticipo 
                                        SET estado = 'B', updated_at = '$g_fecha'
                                        WHERE id = $id_anticipo AND saldo_actual <= 0";
                        mysqli_query($enlace, $q_update_estado);
                    }
                }
            } elseif ($is_aprobado == 0 && $usa_anticipo) {
                // Si se desaprueba, revertir transacciones confirmadas
                $q_revertir = "SELECT 
                    trans.id,
                    trans.id_proveedor_anticipo,
                    trans.monto_retirado,
                    trans.saldo_actual,
                    trans.saldo_restante
                FROM proveedor_anticipo_transaccion trans
                WHERE trans.id_valorizacion_compramineral = $id_registro 
                AND trans.estado = 'A'";

                $res_revertir = mysqli_query($enlace, $q_revertir);

                while ($row = mysqli_fetch_assoc($res_revertir)) {
                    $id_anticipo = $row['id_proveedor_anticipo'];
                    $monto_retirado = $row['monto_retirado'];
                    $saldo_actual = $row['saldo_actual'];

                    // Restaurar saldo del anticipo
                    $saldo_nuevo = $saldo_actual + $monto_retirado;
                    $q_restaurar = "UPDATE proveedor_anticipo 
                                SET saldo_actual = $saldo_nuevo,
                                    cantidad_transacciones = cantidad_transacciones - 1,
                                    estado = 'A', -- Volver a estado con saldo
                                    updated_at = '$g_fecha'
                                WHERE id = $id_anticipo";
                    mysqli_query($enlace, $q_restaurar);

                    // Cambiar estado de transacción a 'B' (Por confirmar)
                    $q_update_trans = "UPDATE proveedor_anticipo_transaccion 
                                    SET estado = 'B',
                                        updated_at = '$g_fecha'
                                    WHERE id = {$row['id']}";
                    mysqli_query($enlace, $q_update_trans);
                }
            }

            // Actualizar la valorización
            $q_update = "UPDATE valorizacion_compramineral SET 
                is_aprobado = $is_aprobado,
                is_aprobado_fechahoraregistro = " . ($is_aprobado == 1 ? "'$g_fecha'" : "NULL") . ",
                is_aprobado_usuarioregistro = " . ($is_aprobado == 1 ? "'$usuario_registro'" : "NULL") . "
            WHERE Id = $id_registro";

            if (mysqli_query($enlace, $q_update)) {
                $estado = 1;

                // Actualiza tablas adicionales (código existente)
                $q_update2 = "UPDATE import_resultadosleyes_detalle SET 
                    is_valorizado = $is_aprobado,
                    is_valorizado_fechahoraregistro = " . ($is_aprobado == 1 ? "'$g_fecha'" : "NULL") . ",
                    is_valorizado_usuarioregistro = " . ($is_aprobado == 1 ? "'$usuario_registro'" : "NULL") . "
                    WHERE cod_interno IN (SELECT cod_lote FROM valorizacion_compramineral_detalle WHERE id_valorizacion = $id_registro)";

                mysqli_query($enlace, $q_update2);

                $q_update3 = "UPDATE despachos_primertramo_validaciondatos SET 
                    codigogel_valorizado = $is_aprobado,
                    codigogel_valorizado_fechahoraregistro = " . ($is_aprobado == 1 ? "'$g_fecha'" : "NULL") . ",
                    codigogel_valorizado_usuarioregistro = " . ($is_aprobado == 1 ? "'$usuario_registro'" : "NULL") . "
                    WHERE lote_cod_lote IN (SELECT cod_lote FROM valorizacion_compramineral_detalle WHERE id_valorizacion = $id_registro)";

                mysqli_query($enlace, $q_update3);
            }

            mysqli_commit($enlace);
        } catch (Exception $e) {
            mysqli_rollback($enlace);
            $msg = $e->getMessage();
            $estado = 0;
        }

        echo json_encode(["estado" => $estado, "msg" => $msg]);
        break;

    case "getTipoPagoValorizacion":
        $estado = 0;
        $tipo_pago = "";
        $mensaje = "Error desconocido.";

        // Sanitizar el parámetro 'id'
        if (!isset($_POST["id_valorizacion"])) {
            $mensaje = "Parámetro 'id_valorizacion' no recibido.";
        } else {
            $id_valorizacion = mysqli_real_escape_string($enlace, $_POST["id_valorizacion"]);

            $q_tipo_pago = "
                SELECT
                    vc.usa_anticipo,
                    vc.id_cuentabancaria
                FROM
                    valorizacion_compramineral vc
                WHERE
                    vc.id = $id_valorizacion;
            ";

            $res_tipo_pago = mysqli_query($enlace, $q_tipo_pago);

            if ($res_tipo_pago && mysqli_num_rows($res_tipo_pago) > 0) {
                $row = mysqli_fetch_assoc($res_tipo_pago);

                $usa_anticipo = (int)$row['usa_anticipo'];
                $id_cuentabancaria = $row['id_cuentabancaria'];
                $usa_cuenta_bancaria = !empty($id_cuentabancaria);

                if ($usa_anticipo === 1 && $usa_cuenta_bancaria) {
                    $tipo_pago = "mixto";
                } elseif ($usa_anticipo === 1 && !$usa_cuenta_bancaria) {
                    $tipo_pago = "anticipo";
                } elseif ($usa_anticipo === 0 && $usa_cuenta_bancaria) {
                    $tipo_pago = "banco";
                } else {
                    // no habra posibilidad de que usa_anticipo y id_cuentabancaria sean nulos, pero por si acaso xd
                    $tipo_pago = "pago no definido/invalido";
                    $mensaje = "Combinación de pago inválida (No anticipo y No cuenta bancaria).";
                    goto end_response;
                }

                $estado = 1;
                $mensaje = "Tipo de pago obtenido correctamente.";
            } else if ($res_tipo_pago && mysqli_num_rows($res_tipo_pago) === 0) {
                $mensaje = "No se encontró registro para el ID proporcionado.";
            } else {
                $mensaje = "Error en la consulta a la base de datos: " . mysqli_error($enlace);
            }
        }

        end_response:

        echo json_encode([
            "estado" => $estado,
            "msg" => $mensaje,
            "tipo_pago" => $tipo_pago,
            "id_valorizacion" => $id_valorizacion ?? null
        ]);
        break;

    case "get_montos_valorizacion":
        $estado = 0;
        $mensaje = "Error desconocido.";
        $monto_total = 0.00; // Usar float para montos
        $monto_anticipo = 0.00;
        $monto_banco = 0.00;

        // 1. Validar y Sanitizar la Entrada
        if (!isset($_POST["id_valorizacion"])) {
            $mensaje = "Parámetro 'id_valorizacion' no recibido.";
            break;
        }

        $id_valorizacion = $_POST["id_valorizacion"];

        if (!is_numeric($id_valorizacion) || $id_valorizacion <= 0) {
            $mensaje = "ID de valorización inválido.";
            break;
        }

        $id_valorizacion = (int)$id_valorizacion;

        // Consulta del monto total de la valorización
        $q_monto_total = "
            SELECT
                IFNULL(SUM(vcd.total), 0.00) AS monto_total
            FROM
                valorizacion_compramineral_detalle vcd
            WHERE
                vcd.id_valorizacion = {$id_valorizacion};
        ";

        // Consulta del monto de anticipo usado
        $q_uso_anticipo = "
            SELECT
                IFNULL(SUM(pat.monto_retirado), 0.00) AS monto_anticipo
            FROM
                proveedor_anticipo_transaccion pat
            WHERE
                pat.id_valorizacion_compramineral = {$id_valorizacion}
                AND pat.estado <> 'C';
        ";

        $res_monto_total = mysqli_query($enlace, $q_monto_total);
        $res_uso_anticipo = mysqli_query($enlace, $q_uso_anticipo);

        if (!$res_monto_total || !$res_uso_anticipo) {
            $mensaje = "Error en la consulta a la base de datos: " . mysqli_error($enlace);
        } else {
            if ($row_monto = mysqli_fetch_assoc($res_monto_total)) {
                $monto_total = (float)$row_monto['monto_total'];
            }

            if ($row_anticipo = mysqli_fetch_assoc($res_uso_anticipo)) {
                $monto_anticipo = (float)$row_anticipo['monto_anticipo'];
            }

            $monto_banco = $monto_total - $monto_anticipo;
            $monto_banco = max(0.00, $monto_banco);

            $estado = 1;
            $mensaje = "Montos de valorización obtenidos correctamente.";
        }

        echo json_encode([
            "estado" => $estado,
            "msg" => $mensaje,
            "monto_total" => $monto_total,
            "monto_anticipo" => $monto_anticipo,
            "monto_banco" => $monto_banco,
        ]);
        break;

    default:
        # code...

        break;
}
