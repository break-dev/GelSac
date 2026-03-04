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

// 2. Construccion de Query Principal (Despachos)
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

// 3. Query Principal (Propuesta Directa)
$q_despachos = "
SELECT
    -- 1. DATOS DEL DESPACHO (CABECERA)
    dsp.correlativo AS despacho_correlativo,
    dsp.created_at AS despacho_fecha_registro,
    CASE 
        WHEN dsp.estado = 'A' THEN 'Activo'
        WHEN dsp.estado = 'I' THEN 'Anulado'
        ELSE dsp.estado 
    END AS despacho_estado,
    prov.documento AS proveedor_documento,
    prov.razon_social AS proveedor_nombre,
    pln.descripcion AS planta_descripcion,
    pln.ruc AS planta_ruc,

    -- 2. DATOS DEL DETALLE DEL DESPACHO (MINERAL)
    CASE 
        WHEN dsd.is_blending = 1 THEN 'Blending'
        ELSE 'Lote'
    END AS mineral_tipo,
    dsd.peso_tomado AS mineral_peso_solicitado,
    dsd.peso_actual AS mineral_peso_stock,
    (dsd.peso_tomado - dsd.peso_actual) AS mineral_peso_distribuido,
    CASE 
        WHEN dsd.is_blending = 1 THEN(
            SELECT bln.correlativo
            FROM blending bln
            WHERE bln.id = dsd.id_mineral
        ) 
        WHEN dsd.is_blending = 0 THEN(
            SELECT vcd.cod_gel
            FROM catalogolotes lot
            INNER JOIN valorizacion_compramineral_detalle vcd ON vcd.cod_lote = lot.ccod_Lote
            WHERE lot.id_CatalogoLotes = dsd.id_mineral
            LIMIT 1
        )
	END AS mineral_codigo,
    
    -- 3. DATOS DE LA DISTRIBUCIÓN (VEHÍCULO)
    dist.fecha_estimada AS distribucion_fecha_completada, 
    CASE 
        WHEN dist.estado = 'A' THEN 'Activo'
        WHEN dist.estado = 'I' THEN 'Anulado'
        ELSE dist.estado
    END AS distribucion_estado, 
    trn.documento AS transportista_documento, 
    trn.razon_social AS transportista_nombre, 
    tpv.descripcion AS vehiculo_tipo, 
    uni.cplaca AS vehiculo_placa, 
    CONCAT(dist.serie_segunda_placa, '-', dist.numero_segunda_placa) AS vehiculo_placa_carreta, 
    uni.nCapacidad AS vehiculo_capacidad,
    
    -- 4. DATOS DEL DETALLE DE DISTRIBUCIÓN (LA CARGA ESPECÍFICA)
    dstd.numero_parte AS carga_numero_parte, 
    dstd.peso_tomado AS carga_peso_asignado

FROM
    despacho dsp
LEFT JOIN tb_clientes prov ON dsp.id_proveedor = prov.Id
LEFT JOIN tbconfig_plantas pln ON dsp.id_planta = pln.Id
LEFT JOIN despacho_detalle dsd ON dsp.id = dsd.id_despacho
LEFT JOIN distribucion_detalle dstd ON dsd.id = dstd.id_despacho_detalle
LEFT JOIN distribucion dist ON dstd.id_distribucion = dist.id
LEFT JOIN transporte uni ON dist.id_unidad = uni.id_transporte
LEFT JOIN tb_clientes trn ON uni.id_Transportista = trn.Id
LEFT JOIN tbconfig_tipovehiculo tpv ON uni.id_tipovehiculo = tpv.Id
" . $sql_where . "
ORDER BY
    dsp.correlativo DESC,
    dsd.id ASC,
    dist.id ASC;
";

$res_despachos = mysqli_query($enlace, $q_despachos);

// 4. Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Data Cruda Despachos");

// --- Estilos ---
$baseStyleHeader = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// 1. Despacho (Azul Oscuro)
$styleHeaderDispatch = array_merge($baseStyleHeader, [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2C3E50']]
]);

// 2. Mineral (Verde)
$styleHeaderMineral = array_merge($baseStyleHeader, [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF27AE60']]
]);

// 3. Distribucion (Naranja)
$styleHeaderDistrib = array_merge($baseStyleHeader, [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE67E22']]
]);

// 4. Detalle Carga (Rojo/Burdeos)
$styleHeaderLoad = array_merge($baseStyleHeader, [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFC0392B']]
]);

$styleRow = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF888888']]],
    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => false]
];

// --- Headers ---
$headers = [
    'A' => 'Despacho Correlativo',
    'B' => 'Fecha Registro',
    'C' => 'Estado Despacho',
    'D' => 'Planta Destino',
    'E' => 'RUC Planta',
    'F' => 'Proveedor',
    'G' => 'RUC Proveedor',
    'H' => 'Cod. Mineral',
    'I' => 'Tipo Mineral',
    'J' => 'Peso Solicitado (TM)',
    'K' => 'Peso Stock (TM)',
    'L' => 'Peso Distribuido (TM)',
    'M' => 'Placa Vehículo',
    'N' => 'Carreta',
    'O' => 'Tipo Vehículo',
    'P' => 'Capacidad (TM)',
    'Q' => 'Transportista',
    'R' => 'RUC Transportista',
    'S' => 'Fecha Distribucion',
    'T' => 'Estado Distribucion',
    'U' => 'Nro. Parte',
    'V' => 'Peso Carga Asignado'
];

// 1. Super Headers
$sheet->setCellValue('A1', 'DESPACHO');
$sheet->mergeCells('A1:G1');

$sheet->setCellValue('H1', 'DETALLE DEL DESPACHO');
$sheet->mergeCells('H1:L1');

$sheet->setCellValue('M1', 'DISTRIBUCION');
$sheet->mergeCells('M1:T1');

$sheet->setCellValue('U1', 'DETALLE DE LA DISTRIBUCION');
$sheet->mergeCells('U1:V1');

// 2. Column Headers
foreach ($headers as $col => $text) {
    $sheet->setCellValue($col . '2', $text);
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Aplicar estilos por rangos (Super Headers)
$sheet->getStyle('A1:G1')->applyFromArray($styleHeaderDispatch);
$sheet->getStyle('H1:L1')->applyFromArray($styleHeaderMineral);
$sheet->getStyle('M1:T1')->applyFromArray($styleHeaderDistrib);
$sheet->getStyle('U1:V1')->applyFromArray($styleHeaderLoad);

// Aplicar estilos por rangos (Column Headers)
$sheet->getStyle('A2:G2')->applyFromArray($styleHeaderDispatch);
$sheet->getStyle('H2:L2')->applyFromArray($styleHeaderMineral);
$sheet->getStyle('M2:T2')->applyFromArray($styleHeaderDistrib);
$sheet->getStyle('U2:V2')->applyFromArray($styleHeaderLoad);

$rowNum = 3;

if ($res_despachos) {
    while ($row = mysqli_fetch_assoc($res_despachos)) {

        // Formatear Fechas
        $fechaReg = ($row['despacho_fecha_registro']) ? date('d/m/Y', strtotime($row['despacho_fecha_registro'])) : '';
        $fechaDist = ($row['distribucion_fecha_completada']) ? date('d/m/Y', strtotime($row['distribucion_fecha_completada'])) : '';

        $sheet->setCellValue('A' . $rowNum, $row['despacho_correlativo']);
        $sheet->setCellValue('B' . $rowNum, $fechaReg);
        $sheet->setCellValue('C' . $rowNum, $row['despacho_estado']);
        $sheet->setCellValue('D' . $rowNum, $row['planta_descripcion']);
        $sheet->setCellValue('E' . $rowNum, $row['planta_ruc']);
        $sheet->setCellValue('F' . $rowNum, $row['proveedor_nombre']);
        $sheet->setCellValue('G' . $rowNum, $row['proveedor_documento']);

        $sheet->setCellValue('H' . $rowNum, $row['mineral_codigo']);
        $sheet->setCellValue('I' . $rowNum, $row['mineral_tipo']);
        $sheet->setCellValue('J' . $rowNum, $row['mineral_peso_solicitado']);
        $sheet->setCellValue('K' . $rowNum, $row['mineral_peso_stock']);
        $sheet->setCellValue('L' . $rowNum, $row['mineral_peso_distribuido']);

        $sheet->setCellValue('M' . $rowNum, $row['vehiculo_placa']);
        $sheet->setCellValue('N' . $rowNum, $row['vehiculo_placa_carreta']);
        $sheet->setCellValue('O' . $rowNum, $row['vehiculo_tipo']);
        $sheet->setCellValue('P' . $rowNum, $row['vehiculo_capacidad']);
        $sheet->setCellValue('Q' . $rowNum, $row['transportista_nombre']);
        $sheet->setCellValue('R' . $rowNum, $row['transportista_documento']);
        $sheet->setCellValue('S' . $rowNum, $fechaDist);
        $sheet->setCellValue('T' . $rowNum, $row['distribucion_estado']);

        $parte = ($row['carga_numero_parte'] !== null) ? $row['carga_numero_parte'] : '';
        $sheet->setCellValue('U' . $rowNum, $parte);
        $sheet->setCellValue('V' . $rowNum, $row['carga_peso_asignado']);

        // Formato de numeros
        $sheet->getStyle('J' . $rowNum . ':L' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle('V' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->getStyle('A' . $rowNum . ':V' . $rowNum)->applyFromArray($styleRow);

        $rowNum++;
    }
} else {
    $sheet->setCellValue('A2', 'Error en la consulta: ' . mysqli_error($enlace));
}

// Finalizar y Descargar
$filename = 'Despachos_Data_Cruda_' . date('Ymd_His') . '.xlsx';

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