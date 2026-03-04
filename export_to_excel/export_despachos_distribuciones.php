<?php
// 0. Limpiar buffers y suprimir errores al inicio absoluto
error_reporting(0);
ini_set('display_errors', 0);
while (ob_get_level())
    ob_end_clean();

// Includes
include('../cnx/cnx.php');
include('../global/variables.php');
require '../apis/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 1. Parametros de Filtro
$f_planta = $_GET['planta'] ?? '';
$f_proveedor = $_GET['proveedor'] ?? '';
$f_desde = $_GET['desde'] ?? '';
$f_hasta = $_GET['hasta'] ?? '';

// 2. Construccion de Query Principal (Despachos) - Misma logica que PDF
$sql_where = " WHERE 1=1 ";

if (!empty($f_planta)) {
    $sql_where .= " AND dsp.id_planta = '" . mysqli_real_escape_string($enlace, $f_planta) . "' ";
}
if (!empty($f_proveedor)) {
    $sql_where .= " AND dsp.id_proveedor = '" . mysqli_real_escape_string($enlace, $f_proveedor) . "' ";
}
if (!empty($f_desde)) {
    $sql_where .= " AND DATE(dsp.created_at) >= '" . mysqli_real_escape_string($enlace, $f_desde) . "' ";
}
if (!empty($f_hasta)) {
    $sql_where .= " AND DATE(dsp.created_at) <= '" . mysqli_real_escape_string($enlace, $f_hasta) . "' ";
}

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

        // --- Detalle Items ---
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

        // --- Distribuciones ---
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
            CONCAT(d.serie_segunda_placa, '-', d.numero_segunda_placa) as segunda_placa,
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
                // Get distribution items for "Items Cargados" column
                $q_items_dist = "
                SELECT
                    dst.id_distribucion,
                    CASE
                        WHEN dsd.is_blending = 1 THEN (SELECT bl.correlativo FROM blending bl WHERE bl.id = dsd.id_mineral)
                        WHEN dsd.is_blending = 0 THEN (
                            SELECT vcd.cod_gel
                            FROM catalogolotes lot
                            INNER JOIN valorizacion_compramineral_detalle vcd on vcd.cod_lote = lot.ccod_Lote
                            WHERE lot.id_CatalogoLotes = dsd.id_mineral
                            LIMIT 1
                        )
                    END AS codigo,
                    dst.numero_parte,
                    dst.peso_tomado
                FROM distribucion_detalle dst
                INNER JOIN despacho_detalle dsd on dsd.id = dst.id_despacho_detalle
                WHERE dst.id_distribucion = " . $dst['id_distribucion'];
                $res_dd = mysqli_query($enlace, $q_items_dist);
                $dst['items'] = [];
                while ($idr = mysqli_fetch_assoc($res_dd)) {
                    $dst['items'][] = $idr;
                }

                $distribuciones[] = $dst;
            }
        }

        $row['items'] = $detalle_items;
        $row['distribuciones'] = $distribuciones;
        $data_reporte[] = $row;
    }
}

// 3. Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Despachos y Distribuciones");

// --- Estilos ---
$styleHeader = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2C3E50']] // Dark Blue
];

$styleHeaderItems = array_merge($styleHeader, ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF27AE60']]]); // Green
$styleHeaderDist = array_merge($styleHeader, ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE67E22']]]); // Orange

$styleBorder = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF888888']]],
    'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true]
];

$styleDispatchHeader = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FF333333']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF0F0F0']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
    'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF888888']]]
];

$styleSubTitle = [
    'font' => ['bold' => true, 'underline' => true, 'color' => ['argb' => 'FF555555']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
];

$styleTotal = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'wrapText' => true],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF888888']]]
];

// Anchos de Columna (A-E)
$sheet->getColumnDimension('A')->setWidth(28);
$sheet->getColumnDimension('B')->setWidth(24);
$sheet->getColumnDimension('C')->setWidth(24);
$sheet->getColumnDimension('D')->setWidth(24);
$sheet->getColumnDimension('E')->setWidth(24);

$row = 1;

if (empty($data_reporte)) {
    $sheet->setCellValue('A1', 'No se encontraron registros.');
} else {
    foreach ($data_reporte as $dsp) {
        // --- 1. Cabecera Despacho ---
        $fecha_dsp = date('d/m/Y', strtotime($dsp['fecha_registro']));

        // Fila 1 Despacho
        $sheet->setCellValue("A$row", "DESPACHO: " . $dsp['correlativo']);
        $sheet->mergeCells("A$row:B$row");

        $sheet->setCellValue("C$row", "FECHA: " . $fecha_dsp);

        $sheet->setCellValue("D$row", "PLANTA: " . $dsp['descripcion_planta']);
        $sheet->mergeCells("D$row:E$row");

        $sheet->getStyle("A$row:E$row")->applyFromArray($styleDispatchHeader);
        $row++;

        // Fila 2 Proveedor
        $sheet->setCellValue("A$row", "PROVEEDOR: " . $dsp['razon_social'] . ' (' . $dsp['documento_proveedor'] . ')');
        $sheet->mergeCells("A$row:E$row");
        $sheet->getStyle("A$row:E$row")->applyFromArray($styleDispatchHeader);
        $row++;

        $row++; // Espacio

        // --- 2. Tabla Items (Lotes/Blending) ---
        $sheet->setCellValue("A$row", "Lotes/Blending");
        $sheet->getStyle("A$row")->applyFromArray($styleSubTitle);
        $row++;

        // Headers Items
        $sheet->setCellValue("A$row", "Código");
        $sheet->setCellValue("B$row", "Tipo");
        $sheet->setCellValue("C$row", "Peso Inicial");
        $sheet->setCellValue("D$row", "Peso Distribuido");
        $sheet->setCellValue("E$row", "Peso Stock");
        $sheet->getStyle("A$row:E$row")->applyFromArray($styleHeaderItems);
        $row++;

        // Data Items
        $total_peso_inicial = 0;
        $total_peso_distribuido = 0;
        $total_peso_stock = 0;

        if (count($dsp['items']) > 0) {
            foreach ($dsp['items'] as $item) {
                $tipo = ($item['is_blending'] == 1) ? 'Blending' : 'Lote';

                $sheet->setCellValue("A$row", $item['codigo']);
                $sheet->setCellValue("B$row", $tipo);
                $sheet->setCellValue("C$row", $item['peso_tomado']);
                $sheet->setCellValue("D$row", $item['peso_distribuido']);
                $sheet->setCellValue("E$row", $item['peso_actual']);

                $sheet->getStyle("A$row:B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C$row:E$row")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("A$row:E$row")->applyFromArray($styleBorder);

                $total_peso_inicial += $item['peso_tomado'];
                $total_peso_distribuido += $item['peso_distribuido'];
                $total_peso_stock += $item['peso_actual'];

                $row++;
            }
        } else {
            $sheet->setCellValue("A$row", "Sin items");
            $sheet->mergeCells("A$row:E$row");
            $sheet->getStyle("A$row:E$row")->applyFromArray($styleBorder);
            $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        // Totales Items
        $sheet->setCellValue("A$row", "TOTALES:");
        $sheet->mergeCells("A$row:B$row");
        $sheet->setCellValue("C$row", $total_peso_inicial);
        $sheet->setCellValue("D$row", $total_peso_distribuido);
        $sheet->setCellValue("E$row", $total_peso_stock);

        $sheet->getStyle("C$row:E$row")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("A$row:E$row")->applyFromArray($styleTotal); // Apply to whole range for borders
        $row++;

        $row++; // Espacio

        // --- 3. Distribuciones ---
        if (count($dsp['distribuciones']) > 0) {
            $sheet->setCellValue("A$row", "DISTRIBUCIONES");
            $sheet->getStyle("A$row")->applyFromArray($styleSubTitle);
            $row++;

            // Headers Distribucion
            $sheet->setCellValue("A$row", "Transportista");
            $sheet->setCellValue("B$row", "Vehículo / Placa");
            $sheet->setCellValue("C$row", "Fecha Est. Llegada");
            $sheet->setCellValue("D$row", "Peso");
            $sheet->setCellValue("E$row", "Items");
            $sheet->getStyle("A$row:E$row")->applyFromArray($styleHeaderDist);
            $row++;

            foreach ($dsp['distribuciones'] as $dist) {
                $fecha_dist = date('d/m/Y', strtotime($dist['fecha_estimada']));

                // Construir string de items
                $items_str = "";
                if (count($dist['items']) > 0) {
                    foreach ($dist['items'] as $it) {
                        $parte = ($it['numero_parte'] === null) ? 'Dist. Total' : 'Parte ' . $it['numero_parte'];
                        $items_str .= "- " . $it['codigo'] . " (" . $parte . "): " . number_format($it['peso_tomado'], 2) . "\n";
                    }
                } else {
                    $items_str = "-";
                }

                $sheet->setCellValue("A$row", $dist['nombre_transportista']);
                $sheet->setCellValue("B$row", $dist['tipo_vehiculo'] . ' (' . $dist['placa'] . ')');
                $sheet->setCellValue("C$row", $fecha_dist);
                $sheet->setCellValue("D$row", $dist['peso_acumulado']);
                $sheet->setCellValue("E$row", $items_str);

                $sheet->getStyle("B$row:C$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D$row")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("E$row")->getAlignment()->setWrapText(true);
                $sheet->getStyle("A$row:E$row")->applyFromArray($styleBorder);
                $sheet->getStyle("A$row:E$row")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

                $row++;
            }
        }

        $row++; // Separador entre despachos
        $row++;
    }
}

// Finalizar y Descargar
$filename = 'Despachos_Distribuciones_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1'); // IE 9
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>