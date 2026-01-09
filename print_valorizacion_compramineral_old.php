<?php

session_start();

// Suprimir warnings de deprecación de PHP 8.2+ en Dompdf
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

include('cnx/cnx.php');
include('global/variables.php');

require('libs/phpqrcode/qrlib.php');
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_md5 = $_GET["x"];

// Funciones
function formatearFecha($fecha)
{
  // Separar fecha
  $dia = str_pad(explode('-', $fecha)[2], 2, '0', STR_PAD_LEFT);
  $mes = nombre_meses(explode('-', $fecha)[1]);
  $anho = explode('-', $fecha)[0];

  return $dia . ' de ' . $mes . ' del ' . $anho;
}

function nombre_meses($num_mes)
{
  if ($num_mes == 1) {
    return "ENERO";
  }
  if ($num_mes == 2) {
    return "FEBRERO";
  }
  if ($num_mes == 3) {
    return "MARZO";
  }
  if ($num_mes == 4) {
    return "ABRIL";
  }
  if ($num_mes == 5) {
    return "MAYO";
  }
  if ($num_mes == 6) {
    return "JUNIO";
  }
  if ($num_mes == 7) {
    return "JULIO";
  }
  if ($num_mes == 8) {
    return "AGOSTO";
  }
  if ($num_mes == 9) {
    return "SEPTIEMBRE";
  }
  if ($num_mes == 10) {
    return "OCTUBRE";
  }
  if ($num_mes == 11) {
    return "NOVIEMBRE";
  }
  if ($num_mes == 12) {
    return "DICIEMBRE";
  }
}

// Ruta imágenes
$ruta_images_x = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_valorizacion_compramineral.php')) . 'images/';
$ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_valorizacion_compramineral.php')) . '/';

// Declaración variables
$nom_archivo = 'Valorización - Compra de Mineral';

// 1. Obteniendo datos de Cabecera
$q_datos = "SELECT V.Id,
                       V.correlativo,
                       P.documento AS ruc,
                       P.razon_social AS proveedor,
                       V.concesion,
                       V.codigo_unico,
                       V.procedencia,
                       V.correlativo,
                       V.infopago_banco,
                       V.infopago_moneda,
                       V.infopago_cuenta,
                       V.infopago_cci,
                       UPPER(MP.descripcion) AS MEDIO_PAGO,
                       CONCAT(EM.apellido_paterno, ' ', EM.apellido_materno, ', ', EM.nombres) AS ELABORADO_POR,
                       EM.documento AS ELABORADO_POR_DNI,
                       EP_AP.descripcion AS ELABORADO_POR_CARGO,
                       CONCAT(U_AP.apellido_paterno, ' ', U_AP.apellido_materno, ', ', U_AP.nombres) AS APROBADO_POR,
                       CR_AP.descripcion AS APROBADO_POR_CARGO,
                       U_AP.documento AS APROBADO_POR_DNI
                  FROM valorizacion_compramineral V
                       LEFT JOIN tb_clientes P ON P.Id = V.id_proveedor
                       LEFT JOIN tbconfig_mediospago MP ON V.id_mediopago = MP.Id
                       LEFT JOIN tbconfig_monedas M ON P.id_moneda = M.Id
                       LEFT JOIN tb_usuario U ON V.usuario_registro = U.usu_usuario
                       LEFT JOIN tb_empleados EM ON U.id_empleado = EM.Id
                       LEFT JOIN tbconfig_cargos EP_AP ON EM.id_cargo = EP_AP.Id
                       LEFT JOIN tb_usuario AP ON V.is_aprobado_usuarioregistro = AP.usu_usuario
                       LEFT JOIN tb_empleados U_AP ON AP.id_empleado = U_AP.Id
                       LEFT JOIN tbconfig_cargos CR_AP ON U_AP.id_cargo = CR_AP.Id
                 WHERE MD5(V.Id) = '$id_md5'
                ORDER BY V.Id DESC";

if ($res_datos = mysqli_query($enlace, $q_datos)) {
  if (mysqli_num_rows($res_datos) > 0) {
    while ($row_datos = mysqli_fetch_array($res_datos)) {
      $id_valorizacion = $row_datos["Id"];
      $correlativo = $row_datos["correlativo"];
      $ruc = $row_datos["ruc"];
      $proveedor = $row_datos["proveedor"];
      $concesion = $row_datos["concesion"];
      $codigo_unico = $row_datos["codigo_unico"];
      $procedencia = $row_datos["procedencia"];
      $correlativo = $row_datos["correlativo"];
      $nom_banco = $row_datos["infopago_banco"];
      $moneda = $row_datos["infopago_moneda"];
      $num_cuenta = $row_datos["infopago_cuenta"];
      $cci = $row_datos["infopago_cci"];
      $medio_pago = $row_datos["MEDIO_PAGO"];
      $elaborado_por = $row_datos["ELABORADO_POR"];
      $elaboradopor_dni = $row_datos["ELABORADO_POR_DNI"];
      $elaboradopor_cargo = $row_datos["ELABORADO_POR_CARGO"];
      $aprobado_por = $row_datos["APROBADO_POR"];
      $aprobadopor_cargo = $row_datos["APROBADO_POR_CARGO"];
      $aprobadopor_dni = $row_datos["APROBADO_POR_DNI"];
    }
  }
}

// 1.1 Verificar si usa anticipos y obtener el detalle
$usa_anticipo = 0;
$anticipos_data = [];
$total_anticipos = 0;

$q_usa_anticipo = "SELECT usa_anticipo FROM valorizacion_compramineral WHERE Id = $id_valorizacion";
if ($res_usa = mysqli_query($enlace, $q_usa_anticipo)) {
  if ($row_usa = mysqli_fetch_assoc($res_usa)) {
    $usa_anticipo = intval($row_usa['usa_anticipo']);
  }
} else {
  // Error en consulta - continuar sin anticipos
  $usa_anticipo = 0;
}

// Si usa anticipos, obtener el detalle
if ($usa_anticipo == 1) {
  $q_anticipos = "
        SELECT 
          pat.id,
          pat.monto_retirado,
          CONCAT(pa.serie_factura, '-', pa.numero_factura) as correlativo
        FROM proveedor_anticipo_transaccion pat
        INNER JOIN proveedor_anticipo pa ON pat.id_proveedor_anticipo = pa.id
        WHERE pat.id_valorizacion_compramineral = $id_valorizacion
          AND pat.estado = 'A'
        ORDER BY pat.id ASC
      ";

  if ($res_anticipos = mysqli_query($enlace, $q_anticipos)) {
    while ($row_ant = mysqli_fetch_assoc($res_anticipos)) {
      $anticipos_data[] = [
        'correlativo' => $row_ant['correlativo'],
        'monto' => floatval($row_ant['monto_retirado'])
      ];
      $total_anticipos += floatval($row_ant['monto_retirado']);
    }
  } else {
    // Error en consulta de anticipos - continuar sin anticipos
    $anticipos_data = [];
    $total_anticipos = 0;
  }
}

// 2. html de cabecera
$html = ' <!DOCTYPE html>
              <html lang="es">
                <head>
                  <title>Reporte de Valorización N° ' . $correlativo . '</title>

                  <style>
                    html, body{
                      font-family: Arial, sans-serif;
                      margin: 10px;
                      padding: 10px;
                      margin-bottom: 0px;
                      font-size: 14px;
                    }

                    @page{
                      margin: 0;
                      padding: 0;
                    }

                    /* ===== Marca de agua centrada ===== */
                    .has-watermark::before{
                      content: "";
                      position: fixed;
                      left: 50%;
                      top: 50%;
                      transform: translate(-50%, -50%);     /* centra exacto */
                      width: 850px;                          /* tamaño en pantalla */
                      height: 850px;                         /* usa el que prefieras */
                      background: url(' . "'" . $ruta_images_qr . $img_logo . "'" . ') no-repeat center center;
                      background-size: contain;              /* respeta proporción */
                      opacity: .15;
                      z-index: 0;
                      pointer-events: none;
                    }

                    @media print{
                      .has-watermark::before{
                        width: 6cm;                          /* tamaño al imprimir/PDF */
                        height: 6cm;
                      }
                    }
                    /* ===== Fin marca de agua ===== */
                  </style>
                </head>

                <!-- Quitamos background-image directo y usamos la clase -->
                <body class="has-watermark" style="margin-left: 10px; margin-right: 10px;">
                  <div class="page-content">
                    <div style="width: 100%; text-align: center; font-weight: bold;">
                      <table style="width: 100%;">
                        <tr style="font-size: 16px;">
                          <td style=" text-align: center; width: 20%; font-size: 20px;">
                            <div style="width: 100%; text-align: center;">
                              <img src="' . $ruta_images_qr . $img_logo . '" style="width: 150px;">
                            </div>
                          </td>

                          <td style="text-align: center; width: 60%; font-size: 20px;">
                            <u>Valorización de Mineral Aurífero</u>
                          </td>

                          <td style="width: 12%; text-align: right; font-size: 12px; font-weight: normal;">
                            N° VALORIZACIÓN:
                          </td>

                          <td style="width: 8%; text-align: center; font-size: 14px; font-weight: normal;">
                            <div style="border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; margin-left: 10px;">' . $correlativo . '</div>
                          </td>
                        </tr>
                      </table>
                    </div>

                    <div style="width: 100%; margin-top: 20px; margin-left: 80px; margin-right: 80px; text-align: center;">
                      <table>
                        <tr style="font-size: 12px;">
                          <td style="font-weight: bold; min-width: 130px; height: 20px;">
                            PROVEEDOR 
                          </td>

                          <td style="width: 500px;">
                            : ' . mb_strtoupper($proveedor) . '
                          </td>

                          <td style="font-weight: bold; height: 20px;">
                            CONCESIÓN
                          </td>

                          <td style="width: 300px;">
                            : ' . mb_strtoupper($concesion) . '
                          </td>

                          <td style="font-weight: bold; height: 20px;">
                            TIPO DE MINERAL
                          </td>

                          <td>
                            : MINERAL AURÍFERO EN BRUTO
                          </td>
                        </tr>

                        <tr style="font-size: 12px;">
                          <td style="font-weight: bold; height: 20px;">
                            RUC
                          </td>

                          <td style="width: 500px;">
                            : ' . $ruc . '
                          </td>

                          <td style="font-weight: bold; height: 20px;">
                            CÓDIGO ÚNICO
                          </td>

                          <td style="width: 300px;">
                            : ' . mb_strtoupper($codigo_unico) . '
                          </td>

                          <td style="font-weight: bold; height: 20px;">
                            RECEPCIÓN
                          </td>

                          <td>
                            : HUANCHACO - TRUJILLO - LA LIBERTAD
                          </td>
                        </tr>

                        <tr style="font-size: 12px;">
                          <td style="font-weight: bold; height: 20px;">
                          </td>

                          <td style="width: 500px;">
                          </td>

                          <td style="font-weight: bold; height: 20px;">
                            PROCEDENCIA
                          </td>

                          <td style="width: 300px;">
                            : ' . mb_strtoupper($procedencia) . '
                          </td>

                          <td style="font-weight: bold; height: 20px;">
                            CONDICIÓN
                          </td>

                          <td>
                            : CRÉDITO
                          </td>
                        </tr>
                      </table>
                    </div>

                    <div style="width: 100%; margin-top: 20px; text-align: center;">
                      <table style="width: 100%; border-spacing: 0px;">
                        <thead>
                          <tr style="font-size: 10px;">
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; height: 45px;">#</th>
                            <th colspan="3" style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">LOTE</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">GUIA R.<br>REMITENTE</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">GUIA R. TRANSPORTISTA</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">Fecha<br>Ingreso</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TMH</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">% H2O</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TMS</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Ley<br>(oz/tc)</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">REC<br>(%)</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">INTER<br>($/oz)</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">MAQUILA<br>($/oz)</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">REACT<br>($/oz)</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">FACTOR</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">PRECIO * TN</th>
                            <th style="text-align: center; background-color: #816951; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TOTAL</th>
                          </tr>
                        </thead>

                        <tbody id="tbl_valorizacion_detalle">';

// Obteniendo el detalle de Valorizaciones
$d = 1;
$cod_gel = '';
$sum_total_pxt = 0;
$sum_total = 0;
$arr_resumen = '';

$q_detalle = "SELECT D.*,
                                                 E.abv_valorizacion
                                            FROM valorizacion_compramineral_detalle D
                                                 INNER JOIN tb_ensayos_analisis E ON D.id_elemento = E.Id
                                           WHERE D.id_valorizacion = $id_valorizacion
                                          ORDER BY D.cod_lote, E.orden";

if ($res_detalle = mysqli_query($enlace, $q_detalle)) {
  if (mysqli_num_rows($res_detalle) > 0) {
    while ($row_detalle = mysqli_fetch_array($res_detalle)) {
      if (strlen($cod_gel) > 0 && ($cod_gel != $row_detalle["cod_gel"])) {
        $arr_resumen .= $cod_gel . ' / ';
      }

      $html .= '<tr style="font-size: 11px;">
                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $d . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $row_detalle["abv_valorizacion"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff; width: 100px;">
                                                ' . $row_detalle["cod_lote"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff; width: 100px;">
                                                ' . $row_detalle["cod_gel"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $row_detalle["guiaremision_remitente"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $row_detalle["guiaremision_transportista"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $row_detalle["fecha_ingreso"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $row_detalle["pesto_tmh"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $row_detalle["porc_h20"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . $row_detalle["peso_tms"] . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . number_format($row_detalle["ley_oztc"], 3, '.', ',') . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . number_format($row_detalle["porc_rec"], 0, '.', ',') . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . number_format($row_detalle["precio_inter"], 2, '.', ',') . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . number_format($row_detalle["maquila"], 2, '.', ',') . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . number_format($row_detalle["precio_reac"], 2, '.', ',') . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">
                                                ' . number_format($row_detalle["factor"], 4, '.', ',') . '
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff;">
                                                <label style="margin-right: 5px;">' . number_format($row_detalle["subtotal"], 2, '.', ',') . '</label>
                                              </td>

                                              <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff;">
                                                <label style="margin-right: 5px;">' . number_format($row_detalle["total"], 2, '.', ',') . '</label>
                                              </td>
                                            </tr>';

      // Obtiene totales
      $sum_total_pxt += $row_detalle["subtotal"];
      $sum_total += $row_detalle["total"];

      $cod_gel = $row_detalle["cod_gel"];

      $d++;
    }

    $arr_resumen .= $cod_gel . '|';
  }
}

// Agrega el resumen de valorización
$r = 0;
$resumen_1 = '';

$arr_resumen = substr($arr_resumen, 0, -1);
$arr_resumen = explode('$', $arr_resumen);

while ($r < count($arr_resumen)) {
  $resumen_1 = $arr_resumen[$r] . ' / ';

  $r++;
}

$resumen_1 = substr($resumen_1, 0, -3);

$html .= '<tr style="font-size: 11px;">';
$html .= '  <td colspan="16" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; font-weight: bold;">';
$html .= '    <label style="margin-right: 5px;">' . $resumen_1 . '</label>';
$html .= '  </td>';

$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; font-weight: bold;">';
$html .= '    <label style="margin-right: 5px;">' . number_format($sum_total_pxt, 2, '.', ',') . '</label>';
$html .= '  </td>';

$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; font-weight: bold;">';
$html .= '    <label style="margin-right: 5px;">' . number_format($sum_total, 2, '.', ',') . '</label>';
$html .= '  </td>';
$html .= '</tr>';

$html .= '          </tbody>
                      </table>
                    </div>';

// Agregando Resumen
// Calcular los valores correctamente
// Valor Neto de Mineral = Total Valorización - Total Anticipos (monto a pagar por banco)
$valor_neto_mineral = $sum_total - $total_anticipos;     // Monto a pagar por banco
$igv = $valor_neto_mineral * 0.18;                       // IGV del 18% sobre el valor neto
$valor_total = $valor_neto_mineral + $igv;               // Total = Valor Neto + IGV
$detraccion = $valor_total * 0.10;                       // Detracción del 10% sobre el total
$neto_a_pagar = $valor_total - $detraccion;              // Neto a pagar = Total - Detracción

$html .= '    <div style="width: 100%; margin-top: 20px; text-align: center;">
                      <table style="width: 100%; border-spacing: 0px;">';

// Primera fila: Título + primer anticipo o Valor Neto
$html .= '
                        <tr style="font-size: 11px;">
                          <td colspan="2" style="font-weight: bold; height: 30px;">
                            <u>DATOS DEL PROVEEDOR</u>
                          </td>';

// Si hay anticipos, mostrar el primero en la primera fila
if ($usa_anticipo == 1 && count($anticipos_data) > 0) {
  $primer_anticipo = $anticipos_data[0];
  $html .= '
                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #ffffff; font-weight: bold; min-width: 250px;">
                            <i style="margin-left: 5px;">Anticipo ' . $primer_anticipo['correlativo'] . '</i>
                          </td>
                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; min-width: 300px;">
                            <label style="margin-right: 5px;">$ ' . number_format($primer_anticipo['monto'], 2, '.', ',') . '</label>
                          </td>';
} else {
  // Si no hay anticipos, dejar las columnas vacías en la primera fila
  $html .= '
                          <td colspan="2"></td>';
}

$html .= '
                        </tr>';

// Filas adicionales de anticipos (del segundo en adelante)
if ($usa_anticipo == 1 && count($anticipos_data) > 1) {
  for ($i = 1; $i < count($anticipos_data); $i++) {
    $anticipo = $anticipos_data[$i];
    $html .= '
                        <tr style="font-size: 11px;">
                          <td colspan="2"></td>
                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #ffffff; font-weight: bold; min-width: 250px;">
                            <i style="margin-left: 5px;">Anticipo ' . $anticipo['correlativo'] . '</i>
                          </td>
                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; min-width: 300px;">
                            <label style="margin-right: 5px;">$ ' . number_format($anticipo['monto'], 2, '.', ',') . '</label>
                          </td>
                        </tr>';
  }
}

// SIEMPRE agregar fila de Valor Neto de Mineral (después de todos los anticipos o en la primera fila si no hay anticipos)
$html .= '
                        <tr style="font-size: 11px;">
                          <td colspan="2"></td>
                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #ffffff; font-weight: bold; min-width: 250px;">
                            <i style="margin-left: 5px;">Valor Neto de Mineral</i>
                          </td>
                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; min-width: 300px;">
                            <label style="margin-right: 5px;">$ ' . number_format($valor_neto_mineral, 2, '.', ',') . '</label>
                          </td>
                        </tr>';


// Continuar con el resto de la tabla (Banco, IGV, etc.)
$html .= '                        <tr style="font-size: 11px;">
                          <td style="font-weight: bold; height: 20px; width: 10%;">
                            Banco
                          </td>

                          <td style="width: 75%;">
                            : ' . $nom_banco . '
                          </td>';

$html .= '
                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #ffffff; font-weight: bold; width: 15%;">
                            <i style="margin-left: 5px;">IGV (18%)</i>
                          </td>

                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; width: 10%;">
                            <label style="margin-right: 5px;">$ ' . number_format($igv, 2, '.', ',') . '</label>
                          </td>
                        </tr>';

$html .= '
                        <tr style="font-size: 11px;">
                          <td style="font-weight: bold; height: 20px;">
                            N° Cuenta
                          </td>

                          <td style="width: 80%;">
                            : ' . $num_cuenta . '
                          </td>

                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #ffffff; font-weight: bold; width: 15%;">
                            <i style="margin-left: 5px;">Valor Total</i>
                          </td>

                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; width: 10%;">
                            <label style="margin-right: 5px;">$ ' . number_format($valor_total, 2, '.', ',') . '</label>
                          </td>
                        </tr>';

$html .= '
                        <tr style="font-size: 11px;">
                          <td style="font-weight: bold; height: 20px;">
                            CCI
                          </td>

                          <td colspan="3" style="width: 80%;">
                            : ' . ((strlen($cci) == 0) ? '---' : $cci) . '
                          </td>
                        </tr>';

$html .= '
                        <tr style="font-size: 11px;">
                          <td style="font-weight: bold; height: 20px;">
                            Modo de Pago
                          </td>

                          <td style="width: 80%;">
                            : ' . $medio_pago . '
                          </td>

                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #ffffff; font-weight: bold; width: 15%;">
                            <i style="margin-left: 5px;">Detracción (10%)</i>
                          </td>

                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; background-color: #ffffff; width: 10%;">
                            <label style="margin-right: 5px;">$ ' . number_format($detraccion, 2, '.', ',') . '</label>
                          </td>
                        </tr>';

$html .= '
                        <tr style="font-size: 11px;">
                          <td style="font-weight: bold; height: 20px;">
                            Tipo de Moneda
                          </td>

                          <td style="width: 80%;">
                            : ' . $moneda . '
                          </td>

                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #ffffff; font-weight: bold; width: 15%; background-color: #FFF587;">
                            <i style="margin-left: 5px;">Neto a pagar</i>
                          </td>

                          <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; font-weight: bold; width: 10%; background-color: #FFF587;">
                            <label style="margin-right: 5px;">$ ' . number_format($neto_a_pagar, 2, '.', ',') . '</label>
                          </td>
                        </tr>
                      </table>
                    </div>';

// Agregando Firmas
$html .= '    <div style="width: 100%; margin-top: -30px; text-align: center;">
                      <table style="width: 100%; border-spacing: 0px;">
                        <tr style="font-size: 12px;">
                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%; text-align: center;">
                            <img src="' . $url_lims . $img_firmas_fvillavicencio . '" style="width: 250px; margin-top: 30px;">
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%; text-align: center;">
                            
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%; text-align: center;">
                            
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>
                        </tr>

                        <tr style="font-size: 12px;">
                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%; text-align: center;">
                            <hr style="color: #000000; margin-top: -10px;">

                            <div style="margin-top: 5px; height: 20px; font-weight: bold;">
                              GRUPO EMPRESARIAL LUBRA SAC - RUC: 20612353183
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%; text-align: center;">
                            <hr style="color: #000000; margin-top: -10px;">

                            <div style="margin-top: 5px; height: 20px; font-weight: bold;">
                              ELABORADO POR
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%; text-align: center;">
                            <hr style="color: #000000; margin-top: -10px;">

                            <div style="margin-top: 5px; height: 20px; font-weight: bold;">
                              PROVEEDOR
                            </div>
                          </td>
                        </tr>

                        <tr style="font-size: 12px;">
                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              VB: ' . $aprobado_por . '
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              NOMBRE: ' . $elaborado_por . '
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              RAZÓN SOCIAL:
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>
                        </tr>

                        <tr style="font-size: 12px;">
                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              CARGO: ' . $aprobadopor_cargo . '
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              CARGO: ' . $elaboradopor_cargo . '
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              RUC:
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>
                        </tr>

                        <tr style="font-size: 12px;">
                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              DNI: ' . $aprobadopor_dni . '
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                            <div style="margin-left: 10px;">
                              DNI: ' . $elaboradopor_dni . '
                            </div>
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>

                          <td style="width: 25%;">
                          </td>

                          <td style="width: 6%; text-align: center;">                            
                          </td>
                        </tr>
                      </table>
                    </div>';

// Agregando Pie de Página
$html .= '    <div style="width: 100%; margin-top: 40px; text-align: center;">
                      <table style="width: 100%; border-spacing: 0px;">
                        <tr style="font-size: 10px;">
                          <td style="font-weight: bold;">
                            NOTA IMPORTANTE
                          </td>
                        </tr>

                        <tr style="font-size: 11px;">
                          <td>
                            *Vigencia de Valorizacion sera de 7 dias calendario.
                          </td>
                        </tr>

                        <tr style="font-size: 11px;">
                          <td>
                            *Si existe una falsificacion o adulteracion de los datos seran denunciados penalmente.
                          </td>
                        </tr>

                        <tr style="font-size: 11px;">
                          <td>
                            *Pasado los 30 dias no hay lugar a reclamo alguno.
                          </td>
                        </tr>

                        <tr style="font-size: 11px;">
                          <td>
                            *Si el proveedor minero desea retirar el mineral de nuestra planta , este debera realizar un pago por almacenaje, chancado y gastos operativos.
                          </td>
                        </tr>
                      </table>
                    </div>';

// Cierra html
$html .= '        </div>
                    </body>
                  </html>';
// echo '$html: '.$html;
// return;
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$document = new Dompdf($options);

$document->loadHtml($html, 'UTF-8');
$document->setPaper('A3', 'landscape');
$document->render();
$document->stream('Valorización de Mineral de Compra - N° ' . $correlativo, array('Attachment' => 0));
