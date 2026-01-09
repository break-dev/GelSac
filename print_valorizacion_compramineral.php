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
  if ($num_mes == 1)
    return "ENERO";
  if ($num_mes == 2)
    return "FEBRERO";
  if ($num_mes == 3)
    return "MARZO";
  if ($num_mes == 4)
    return "ABRIL";
  if ($num_mes == 5)
    return "MAYO";
  if ($num_mes == 6)
    return "JUNIO";
  if ($num_mes == 7)
    return "JULIO";
  if ($num_mes == 8)
    return "AGOSTO";
  if ($num_mes == 9)
    return "SEPTIEMBRE";
  if ($num_mes == 10)
    return "OCTUBRE";
  if ($num_mes == 11)
    return "NOVIEMBRE";
  if ($num_mes == 12)
    return "DICIEMBRE";
}

// Ruta imágenes
$ruta_images_x = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
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
      $nom_banco = $row_datos["infopago_banco"];
      $moneda = $row_datos["infopago_moneda"];
      $num_cuenta = $row_datos["infopago_cuenta"];
      $cci = $row_datos["infopago_cci"];
      $medio_pago = $row_datos["MEDIO_PAGO"];
      // ... (otros campos si se requieren)
    }
  }
}

// 1.1 Verificar si usa anticipos
$usa_anticipo = 0;
$total_anticipos = 0;
// (Lógica de anticipos simplificada a lo existente)
$q_usa_anticipo = "SELECT usa_anticipo FROM valorizacion_compramineral WHERE Id = $id_valorizacion";
if ($res_usa = mysqli_query($enlace, $q_usa_anticipo)) {
  if ($row_usa = mysqli_fetch_assoc($res_usa)) {
    $usa_anticipo = intval($row_usa['usa_anticipo']);
  }
}

if ($usa_anticipo == 1) {
  $q_anticipos = "SELECT monto_retirado FROM proveedor_anticipo_transaccion WHERE id_valorizacion_compramineral = $id_valorizacion AND estado = 'A'";
  if ($res_anticipos = mysqli_query($enlace, $q_anticipos)) {
    while ($row_ant = mysqli_fetch_assoc($res_anticipos)) {
      $total_anticipos += floatval($row_ant['monto_retirado']);
    }
  }
}

// 2. HTML
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Reporte de Valorización N° ' . $correlativo . '</title>
    <style>
        /* Ajuste de márgenes globales para centrado vertical de watermark */
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 20px 30px; }
        @page { margin: 10px 10px; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .header-line { border-bottom: 2px solid #000; margin-bottom: 20px; }
        
        .info-table { width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 5px; }
        .info-table td { padding: 4px; vertical-align: top; }
        .label { font-weight: bold; width: 80px; }
        
        .data-table { width: 100%; border-collapse: collapse; font-size: 8px; margin-top: 5px; }
        .data-table th { 
            border: 1px solid #000; 
            padding: 5px; 
            background-color: #fff; 
            font-weight: bold;
            text-align: center;
        }
        .data-table td { 
            border: 1px solid #000; 
            padding: 4px; 
            text-align: center;
        }

        .totals-table { width: 100%; border-collapse: collapse; font-size: 9px; margin-top: 10px; }
        .totals-table td { padding: 3px; }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 40%; /* Movido un poco más arriba (antes 50%) */
            left: 50%;
            width: 600px; 
            transform: translate(-50%, -50%);
            opacity: 0.10;
            z-index: -1000;
        }
    </style>
</head>
<body>

    <!-- Watermark -->
    <img src="' . $ruta_images . 'empresa/logo.jpeg" class="watermark">

    <!-- Header -->
    <div style="margin-bottom: 0px;">
        <table class="header-table">
            <tr>
                <td style="width: 25%; vertical-align: bottom;">
                    <img src="' . $ruta_images . 'empresa/logo_horizontal.jpeg" style="width: 180px;">
                </td>
                <td style="width: 50%; text-align: center; vertical-align: bottom; padding-bottom: 5px;">
                     <span style="font-size: 16px; font-weight: bold; text-decoration: underline;">VALORIZACIÓN DE MINERAL</span>
                </td>
                <td style="width: 25%; text-align: right; vertical-align: bottom; padding-bottom: 5px;">
                     <span style="font-size: 10px; font-weight: bold; text-decoration: underline;">ZONA: HUANCHACO</span>
                </td>
            </tr>
        </table>
        <div class="header-line"></div>
    </div>

    <!-- Info Section: Tightened Columns -->
    <table class="info-table">
        <tr>
            <td class="label">RAZON SOCIAL</td>
            <!-- Se quita width: 50% fijo para que ocupe lo necesario, y se fuerza el colapso de la derecha -->
            <td style="font-weight: bold;">: ' . mb_strtoupper($proveedor) . '</td>
            
            <!-- Width 1% fuerza a la celda a ser del ancho del contenido + padding -->
            <td style="width: 1%; white-space: nowrap; padding-right: 5px;">
                <span style="font-weight: bold;">N° VALORIZACION</span>
            </td>
            <td style="width: 1%;">:</td>
            <td style="white-space: nowrap; width: 1%;"><b>' . $correlativo . '</b></td>
        </tr>
        <tr>
            <td class="label">RUC</td>
            <td>: ' . $ruc . '</td>
            
            <td style="width: 1%; white-space: nowrap; padding-right: 5px;"><span style="font-weight: bold;">UBICACIÓN</span></td>
            <td style="width: 1%;">:</td>
            <td style="white-space: nowrap; width: 1%;">PATAZ - PATAZ - LA LIBERTAD</td>
        </tr>
        <tr>
            <td class="label">CONCESION</td>
            <td>: PILAR DEL VALLE</td> 
            <td colspan="3"></td>
        </tr>
        <tr>
            <td class="label">COD. UNICO</td>
            <td>: ' . mb_strtoupper($codigo_unico) . '</td>
            <td colspan="3"></td>
        </tr>
    </table>
    
    <div style="border-bottom: 2px solid #000; margin-bottom: 10px;"></div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th>TIPO DE<br>MINERAL</th>
                <th>LOTE</th>
                <th>G.R.R.</th>
                <th>G.R.T.</th>
                <th>FECHA DE<br>INGRESO</th>
                <th>T.M.H.</th>
                <th>% H2O</th>
                <th>T.M.S</th>
                <th>LEY (Oz/Tc)</th>
                <th>REC.UP<br>(%)</th>
                <th>INTER<br>($/Oz)</th>
                <th>MAQUILA</th>
                <th>CONSUMO</th>
                <th>FLETE</th>
                <th>FACTOR</th>
                <th>PRECIO *<br>TONELADA</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>';

// Detalle
$sum_total = 0;
$q_detalle = "SELECT D.*, E.abv_valorizacion 
              FROM valorizacion_compramineral_detalle D
              INNER JOIN tb_ensayos_analisis E ON D.id_elemento = E.Id
              WHERE D.id_valorizacion = $id_valorizacion
              ORDER BY D.cod_lote, E.orden";

if ($res_detalle = mysqli_query($enlace, $q_detalle)) {
  while ($row_detalle = mysqli_fetch_array($res_detalle)) {
    $sum_total += $row_detalle["total"];
    $html .= '<tr>
                    <td>' . $row_detalle["abv_valorizacion"] . '</td>
                    <td>' . $row_detalle["cod_lote"] . '</td>
                    <td>' . $row_detalle["guiaremision_remitente"] . '</td>
                    <td>' . $row_detalle["guiaremision_transportista"] . '</td>
                    <td>' . $row_detalle["fecha_ingreso"] . '</td>
                    <td>' . number_format($row_detalle["pesto_tmh"], 4) . '</td>
                    <td>' . number_format($row_detalle["porc_h20"], 4) . '</td>
                    <td>' . number_format($row_detalle["peso_tms"], 4) . '</td>
                    <td>' . number_format($row_detalle["ley_oztc"], 3) . '</td>
                    <td>' . number_format($row_detalle["porc_rec"], 0) . '%</td>
                    <td>' . number_format($row_detalle["precio_inter"], 2) . '</td>
                    <td>' . number_format($row_detalle["maquila"], 2) . '</td>
                    <td>' . number_format($row_detalle["precio_reac"], 2) . '</td>
                    <td>-</td>
                    <td>' . number_format($row_detalle["factor"], 4) . '</td>
                    <td style="text-align: right;">$ ' . number_format($row_detalle["subtotal"], 2) . '</td>
                    <td style="text-align: right;">$ ' . number_format($row_detalle["total"], 2) . '</td>
                  </tr>';
  }
}

// Calculos finales
$valor_neto = $sum_total;
$anticipos = $total_anticipos;
$sub_total = $sum_total - $anticipos;
$igv = $sub_total * 0.18;
$valor_total = $sub_total + $igv;
$detraccion = $valor_total * 0.10;
$neto_pagar = $valor_total - $detraccion;

$html .= '</tbody>
    </table>

    <br>

    <!-- Provider Data & Final Calculations -->
    <div style="width: 100%;">
        <div style="display: inline-block; width: 60%; vertical-align: top;">
            <div style="font-weight: bold; text-decoration: underline; font-size: 10px; margin-bottom: 5px;">DATOS DEL PROVEEDOR</div>
            <table style="width: 100%; font-size: 9px;">
                <tr><td style="font-weight: bold; width: 80px;">Banco</td><td>: ' . $nom_banco . '</td></tr>
                <tr><td style="font-weight: bold;">N° Cuenta</td><td>: ' . $num_cuenta . '</td></tr>
                 <tr><td style="font-weight: bold;">N° Cta. CCI</td><td>: ' . ((strlen($cci) == 0) ? '---' : $cci) . '</td></tr>
                 <tr><td style="font-weight: bold;">N° Cta. Detraccion</td><td>: ---</td></tr>
                 <tr><td style="font-weight: bold;">Modo de pago</td><td>: ' . $medio_pago . '</td></tr>
                 <tr><td style="font-weight: bold;">Tipo de pago</td><td>: ' . $moneda . '</td></tr>
            </table>
        </div>
        <div style="display: inline-block; width: 38%; vertical-align: top;">
             <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                <!-- Valor Neto -->
                 <tr>
                        <td style="padding: 3px; font-weight: bold; text-align: right;">Valor Neto de Mineral</td>
                        <td style="padding: 3px; text-align: right; width: 80px;">$ ' . number_format($valor_neto, 2) . '</td>
                </tr>
                 <tr>
                        <td style="padding: 3px; font-weight: bold; text-align: right;">Anticipo A/C de Mineral</td>
                        <td style="padding: 3px; text-align: right;">$ ' . number_format($anticipos, 2) . '</td>
                 </tr>
                 
                 <!-- Espacio -->
                 <tr><td colspan="2" style="height: 10px;"></td></tr>

                 <!-- Subtotales -->
                <tr>
                    <td style="padding: 3px; font-weight: bold; text-align: right;">Sub Total</td>
                    <td style="padding: 3px; text-align: right;">$ ' . number_format($sub_total, 2) . '</td>
                </tr>
                <tr>
                    <td style="padding: 3px; font-weight: bold; text-align: right;">I.G.V. 18%</td>
                    <td style="padding: 3px; text-align: right;">$ ' . number_format($igv, 2) . '</td>
                </tr>
                <tr>
                    <td style="padding: 3px; font-weight: bold; text-align: right;">Valor Total</td>
                    <td style="padding: 3px; text-align: right;">$ ' . number_format($valor_total, 2) . '</td>
                </tr>
                <tr>
                    <td style="padding: 3px; font-weight: bold; text-align: right;">Detracción 10%</td>
                    <td style="padding: 3px; text-align: right;">$ ' . number_format($detraccion, 2) . '</td>
                </tr>
                <tr>
                    <td style="padding: 3px; font-weight: bold; text-align: right;">Neto a pagar</td>
                    <td style="border-top: 1px solid #000; padding: 3px; text-align: right; font-weight: bold;">$ ' . number_format($neto_pagar, 2) . '</td>
                </tr>
             </table>
        </div>
    </div>
    
    <div style="margin-top: 20px; font-weight: bold; font-size: 10px; text-align: left;">
        SON: CUARENTA Y OCHO MIL NOVECIENTOS NOVENTA Y UNO 09/100 DÓLARES AMERICANOS
    </div>

    <!-- Signatures -->
    <div style="margin-top: 40px;">
        <table style="width: 100%;">
            <tr>
                <!-- Firma Izquierda -->
                <td style="width: 45%; text-align: center; vertical-align: bottom;">
                     <!-- Altura fija para la parte SUPERIOR a la linea: 110px para dar espacio -->
                     <div style="height: 110px;">
                          <!-- Logo -->
                          <div style="margin-bottom: 5px;">
                             <img src="' . $ruta_images . 'empresa/logo_horizontal.jpeg" style="width: 120px;">
                          </div>
                          <!-- Firma superpuesta con margen negativo -->
                          <div style="margin-top: -40px;">
                                <img src="' . $url_lims . $img_firmas_fvillavicencio . '" style="width: 140px;">
                          </div>
                     </div>
                     
                     <!-- Linea y texto abajo -->
                     <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto; padding-top: 5px; font-weight: bold; font-size: 9px;">MARICELA CASTILLO A.</div>
                     <div style="font-size: 8px; font-weight: bold;">LIQUIDACIONES</div>
                     
                     <div style="margin-top: 10px; font-size: 8px; text-align: left; height: 35px;">
                        PLANTA DE BENEFICIO BEIJING S.A.C. - LIQUIDACIONES<br>
                        NOMBRE: MARICELA CASTILLO A.<br>
                        DNI: 18080165
                     </div>
                </td>
                
                <!-- Espacio central más pequeño para juntar firmas -->
                 <td style="width: 10%;"></td>
                
                <!-- Firma Derecha -->
                <td style="width: 45%; text-align: center; vertical-align: bottom;">
                     <!-- Altura fija IGUAL a la izquierda para emparejar linea -->
                     <div style="height: 110px;"></div>
                     
                     <!-- Linea y texto abajo -->
                     <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto; padding-top: 5px; font-weight: bold; font-size: 9px;">PROVEEDOR</div>
                     
                     <!-- Contenedor de misma altura -->
                      <div style="margin-top: 10px; font-size: 8px; text-align: left; min-height: 35px;">
                        RAZON SOCIAL: ' . mb_strtoupper($proveedor) . '<br>
                        RUC: ' . $ruc . '
                     </div>
                </td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 30px; font-size: 9px;">
        <strong>NOTA IMPORTANTE</strong>
        <ul style="list-style-type: none; padding-left: 0; margin-top: 2px;">
            <li>*Vigencia de Valorizacion sera de 7 dias calendario.</li>
            <li>*Si existe una falsificacion o adulteracion de los datos seran denunciados penalmente.</li>
            <li>*Pasado los 30 dias no hay lugar a reclamo alguno.</li>
            <li>*Si el proveedor minero desea retirar el mineral de nuestra planta , este debera realizar un pago por almacenaje, chancado y gastos operativos.</li>
        </ul>
    </div>

</body>
</html>
';

$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$document = new Dompdf($options);

$document->loadHtml($html, 'UTF-8');
$document->setPaper('A3', 'landscape');
$document->render();
$document->stream('Valorización de Mineral de Compra - N° ' . $correlativo, array('Attachment' => 0));

?>