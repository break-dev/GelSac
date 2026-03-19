<?php
session_start();


include('cnx/cnx.php');
include('global/variables.php');
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION["Id"])) {
    echo "Acceso denegado.";
    exit;
}

// Suprimir warnings de deprecación de PHP 8.2+ en Dompdf
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startuo_errors', 0);


// Parametros de Filtro
$f_planta = $_GET['planta'] ?? '';
$f_proveedor = $_GET['proveedor'] ?? '';
$f_desde = $_GET['desde'] ?? '';
$f_hasta = $_GET['hasta'] ?? '';

// Ruta imágenes (Adaptado de print_valorizacion_compramineral.php)
$ruta_images_x = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
// Ajustamos el nombre del archivo actual para el recorte ruta
$script_name = basename(__FILE__);
$ruta_base = substr($ruta_images_x, 0, strpos($ruta_images_x, $script_name));
$ruta_images = $ruta_base . 'images/';

// Construccion de Query Principal (Despachos)
// Copiado y adaptado de export_to_excel/export_despachos_distribuciones.php
$sql_where = " WHERE 1=1 ";

// Filtros
if (!empty($f_planta)) {
    $sql_where .= " AND dsp.id_planta = '" . mysqli_real_escape_string($enlace, $f_planta) . "' ";
}
if (!empty($f_proveedor)) {
    $sql_where .= " AND dsp.id_proveedor = '" . mysqli_real_escape_string($enlace, $f_proveedor) . "' ";
}
if (!empty($f_desde)) {
    // Asumiendo formato YYYY-MM-DD
    $sql_where .= " AND DATE(dsp.created_at) >= '" . mysqli_real_escape_string($enlace, $f_desde) . "' ";
}
if (!empty($f_hasta)) {
    $sql_where .= " AND DATE(dsp.created_at) <= '" . mysqli_real_escape_string($enlace, $f_hasta) . "' ";
}

// Query Principal
$q_despachos = "
SELECT
    dsp.id AS id_despacho,
    dsp.id_planta,
    dsp.correlativo,
    prov.documento AS documento_proveedor,
    prov.razon_social,
    pln.descripcion AS descripcion_planta,
    pln.ruc AS ruc_planta,
    pln.direccionguia_segundotramo AS direccion_planta,
    dsp.created_at AS fecha_registro,
    (
        SELECT COUNT(dsd.id)
        FROM despacho_detalle dsd
        WHERE dsp.id = dsd.id_despacho AND dsd.is_blending = 1
    ) AS blending_usados,
    (
        SELECT COUNT(dsd.id)
        FROM despacho_detalle dsd
        WHERE dsp.id = dsd.id_despacho AND dsd.is_blending = 0
    ) AS lotes_usados,
    dsp.estado
FROM
    despacho dsp
INNER JOIN tb_clientes prov ON
    dsp.id_proveedor = prov.Id
INNER JOIN tbconfig_plantas pln ON
    pln.Id = dsp.id_planta
$sql_where
ORDER BY
    dsp.correlativo DESC
";

$res_despachos = mysqli_query($enlace, $q_despachos);
$data_reporte = [];

if ($res_despachos) {
    while ($row = mysqli_fetch_assoc($res_despachos)) {
        $id_despacho = $row['id_despacho'];

        // --- Obtener Detalle del Despacho ---
        $detalle_items = [];
        $q_detalle_despacho = "
		SELECT
			dsd.id as id_despacho_detalle,
			CASE
				WHEN dsd.is_blending = 1 THEN (
					SELECT 
						bln.correlativo 
					FROM blending bln 
					WHERE bln.id = dsd.id_mineral)
				WHEN dsd.is_blending = 0 THEN (
					SELECT 
						vcd.cod_gel
					FROM catalogolotes lot 
					INNER JOIN valorizacion_compramineral_detalle vcd ON vcd.cod_lote = lot.ccod_Lote
					INNER JOIN valorizacion_compramineral vc on vc.Id = vcd.id_valorizacion
					INNER JOIN comprobante_pago cp on cp.id_valorizacion = vc.Id
					WHERE cp.estado IN('A', 'B', 'C') AND lot.id_CatalogoLotes = dsd.id_mineral
					
				)
			END AS codigo,
			dsd.is_blending,
			dsd.peso_tomado,
			dsd.peso_actual,
			dsd.peso_actual_log,
			(dsd.peso_tomado - dsd.peso_actual) as peso_distribuido,
			dsd.estado
		FROM
			despacho_detalle dsd
		WHERE
			dsd.id_despacho = $id_despacho
		ORDER BY codigo;
        ";

        $res_det = mysqli_query($enlace, $q_detalle_despacho);
        if ($res_det) {
            while ($d = mysqli_fetch_assoc($res_det)) {
                $detalle_items[] = $d;
            }
        }

        // --- Obtener Distribuciones ---
        $distribuciones = [];
        $q_distribuciones_despacho = "
        SELECT
            dist.id AS id_distribucion,
            dist.id_unidad,
            dist.id_despacho,
            trn.documento AS documento_transportista,
            trn.razon_social AS nombre_transportista,
            tpv.descripcion AS tipo_vehiculo,
            uni.cplaca AS placa,
            CONCAT(dist.serie_segunda_placa, '-', dist.numero_segunda_placa) AS segunda_placa,
            uni.nCapacidad AS capacidad,
            (
                SELECT SUM(dstd.peso_tomado)
                FROM distribucion_detalle dstd
                WHERE dstd.id_distribucion = dist.id
            ) AS peso_acumulado,
            dist.fecha_estimada,
            dist.created_at AS fecha_registro,
            dist.estado
        FROM
            distribucion dist
        INNER JOIN transporte uni ON
            uni.id_transporte = dist.id_unidad
        INNER JOIN tb_clientes trn ON
            trn.Id = uni.id_Transportista
        INNER JOIN tbconfig_tipovehiculo tpv ON
            tpv.Id = uni.id_tipovehiculo
        WHERE
            dist.estado = 'A' AND dist.id_despacho = $id_despacho
        ORDER BY
            dist.fecha_estimada ASC,
            dist.id ASC
        ";

        $res_dist = mysqli_query($enlace, $q_distribuciones_despacho);
        if ($res_dist) {
            while ($dst = mysqli_fetch_assoc($res_dist)) {
                $q_items_dist = "
		SELECT
			dst.id_distribucion,
			dst.id_despacho_detalle,
			CASE
				WHEN dsd.is_blending = 1 THEN (
					SELECT
						bl.correlativo
					FROM blending bl
					WHERE bl.id = dsd.id_mineral
				)
				WHEN dsd.is_blending = 0 THEN (
					SELECT
						vcd.cod_gel
					FROM catalogolotes lot
					INNER JOIN valorizacion_compramineral_detalle vcd on vcd.cod_lote = lot.ccod_Lote
					WHERE lot.id_CatalogoLotes = dsd.id_mineral
					LIMIT 1
				)
			END AS codigo,
			dsd.is_blending,
			dst.peso_actual_log,
			dst.peso_tomado,
			(dst.peso_actual_log - dst.peso_tomado) as peso_restante,
			dst.numero_parte,
			dst.created_at as fecha_registro
		FROM
			distribucion_detalle dst
		INNER JOIN despacho_detalle dsd on dsd.id = dst.id_despacho_detalle
		WHERE dst.id_distribucion = " . $dst['id_distribucion'];
                $res_dd = mysqli_query($enlace, $q_items_dist);
                $items_d = [];
                while ($idr = mysqli_fetch_assoc($res_dd)) {
                    $items_d[] = $idr;
                }
                $dst['items'] = $items_d;

                $distribuciones[] = $dst;
            }
        }

        $row['items'] = $detalle_items;
        $row['distribuciones'] = $distribuciones;
        $data_reporte[] = $row;
    }
}

// ---------------- HTML GENERATION ----------------
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Reporte de Despachos y Distribuciones</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 20px; }
        @page { margin: 20px 30px; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .title { font-size: 16px; font-weight: bold; text-decoration: underline; text-align: center; }
        
        .dispatch-container { margin-bottom: 25px; page-break-inside: avoid; border: 1px solid #ccc; padding: 10px; }
        .dispatch-header { background-color: #f0f0f0; padding: 5px; border-bottom: 1px solid #ccc; margin-bottom: 5px;}
        .dispatch-title { font-size: 12px; font-weight: bold; color: #333; }
        
        .section-title { font-weight: bold; font-size: 10px; margin-top: 5px; margin-bottom: 2px; color: #555; text-decoration: underline;}
        
        .data-table { width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 10px; }
        .data-table th { border: 1px solid #888; padding: 3px; background-color: #eee; font-weight: bold; text-align: center; }
        .data-table td { border: 1px solid #888; padding: 3px; }
        
        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            width: 500px; 
            transform: translate(-50%, -50%);
            opacity: 0.10;
            z-index: -1000;
        }

        .lbl-val { font-weight: bold; margin-right: 15px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    
    <img src="' . $ruta_images . 'empresa/logo.jpeg" class="watermark">

    <table class="header-table">
        <tr>
            <td style="width: 25%;">
                <img src="' . $ruta_images . 'empresa/logo_horizontal.jpeg" style="width: 150px;">
            </td>
            <td style="width: 50%; text-align: center; vertical-align: middle;">
                 <div class="title">REPORTE DE DESPACHOS Y DISTRIBUCIONES</div>
                 <div style="margin-top:8px !important;">Fecha Impresión: ' . date('d/m/Y H:i:s') . '</div>
            </td>
            <td style="width: 25%;"></td>
        </tr>
    </table>';

if (empty($data_reporte)) {
    $html .= '<div style="text-align:center; padding: 20px; font-size:12px;">No se encontraron registros para los filtros seleccionados.</div>';
} else {
    foreach ($data_reporte as $dsp) {
        $fecha_dsp = date('d/m/Y', strtotime($dsp['fecha_registro']));

        $html .= '<div class="dispatch-container">';

        // Cabecera Despacho
        $html .= '<div class="dispatch-header">
                    <table style="width: 100%;">
                        <tr>
                            <td><span class="lbl-val">DESPACHO:</span> ' . $dsp['correlativo'] . '</td>
                            <td><span class="lbl-val">FECHA:</span> ' . $fecha_dsp . '</td>
                            <td><span class="lbl-val">PLANTA:</span> ' . $dsp['descripcion_planta'] . '</td>
                        </tr>
                        <tr>
                            <td colspan="3"><span class="lbl-val">PROVEEDOR:</span> ' . $dsp['razon_social'] . ' (' . $dsp['documento_proveedor'] . ')</td>
                        </tr>
                    </table>
                  </div>';

        // Items del Despacho
        $html .= '<div class="section-title">Lotes/Blending</div>';
        $html .= '<table class="data-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Peso Inicial</th>
                            <th>Peso Distribuido</th>
                            <th>Peso Stock</th>
                        </tr>
                    </thead>
                    <tbody>';

        $total_peso_inicial = 0;
        $total_peso_distribuido = 0;
        $total_peso_stock = 0;
        if (count($dsp['items']) > 0) {
            foreach ($dsp['items'] as $item) {
                $tipo = ($item['is_blending'] == 1) ? 'Blending' : 'Lote';
                $html .= '<tr>
                            <td class="text-center">' . $item['codigo'] . '</td>
                            <td class="text-center">' . $tipo . '</td>
                            <td class="text-right">' . number_format($item['peso_tomado'], 2) . '</td>
                            <td class="text-right">' . number_format($item['peso_distribuido'], 2) . '</td>
                            <td class="text-right">' . number_format($item['peso_actual'], 2) . '</td>
                          </tr>';
                $total_peso_inicial += $item['peso_tomado'];
                $total_peso_distribuido += $item['peso_distribuido'];
                $total_peso_stock += $item['peso_actual'];
            }
        } else {
            $html .= '<tr><td colspan="5" class="text-center">Sin items</td></tr>';
        }
        $html .= '<tr>
                    <td colspan="2" class="text-right" style="font-weight:bold;">TOTALES:</td>
                    <td class="text-right" style="font-weight:bold;">' . number_format($total_peso_inicial, 2) . '</td>
                    <td class="text-right" style="font-weight:bold;">' . number_format($total_peso_distribuido, 2) . '</td>
                    <td class="text-right" style="font-weight:bold;">' . number_format($total_peso_stock, 2) . '</td>
                  </tr>';
        $html .= '</tbody></table>';

        // Distribuciones
        if (count($dsp['distribuciones']) > 0) {
            $html .= '<div class="section-title">DISTRIBUCIONES</div>';
            foreach ($dsp['distribuciones'] as $dist) {
                $fecha_dist = date('d/m/Y', strtotime($dist['fecha_estimada']));
                $html .= '<table class="data-table" style="margin-top:2px;">
                            <thead>
                                <tr style="background-color: #d1efff;">
                                    <th style="width: 25%;">Transportista</th>
                                    <th style="width: 20%;">Vehículo / Placa</th>
                                    <th style="width: 15%;">Fecha Est. Llegada</th>
                                    <th style="width: 15%;">Peso</th>
                                    <th style="width: 25%;">Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>' . $dist['nombre_transportista'] . '</td>
                                    <td class="text-center">' . $dist['tipo_vehiculo'] . ' (' . $dist['placa'] . ')</td>
                                    <td class="text-center">' . $fecha_dist . '</td>
                                    <td class="text-right" style="font-weight:bold;">' . number_format($dist['peso_acumulado'], 2) . '</td>
                                    <td style="font-size:8px;">';

                // Listar items dentro de la celda
                if (count($dist['items']) > 0) {
                    foreach ($dist['items'] as $it) {
                        $parte_info = ($it['numero_parte'] === null) ? 'Dist. Total' : 'Parte ' . $it['numero_parte'];
                        $html .= '<div>- ' . $it['codigo'] . ' (' . $parte_info . '): ' . number_format($it['peso_tomado'], 2) . '</div>';
                    }
                } else {
                    $html .= '-';
                }

                $html .= '     </td>
                                </tr>
                            </tbody>
                           </table>';
            }
        } else {
            $html .= '<div style="font-size:9px; font-style:italic; margin-left:5px;">No hay distribuciones registradas.</div>';
        }

        $html .= '</div>'; // End container
    }
}

$html .= '
</body>
</html>
';

// Render PDF
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Reporte_Despachos_" . date('Ymd_His') . ".pdf", array("Attachment" => 0));

?>