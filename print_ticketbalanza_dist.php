<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');

require_once 'dompdf/autoload.inc.php';

require('libs/phpqrcode/qrlib.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$id_detalle = intval($_GET["id"]);

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startuo_errors', 0);

// Ruta logo
$ruta_images = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_ticketbalanza_dist.php')) . 'images/';

$ticket_balanza = '';
$cod_lote = '';
$placa_1 = '';
$placa_2 = '';
$transportista_razonsocial = '';
$transportista_documento = '';
$tipo_vehiculo = '';
$conductor_nombres = '';
$conductor_documento = '';
$proveedor_minero = '---';
$observacion = '---';

$peso_tara = 0;
$peso_bruto = 0;
$peso_neto = 0;
$fecha_registro = '';

$q_balanza = "SELECT 
                dd.ticket_balanza,
                CASE
                    WHEN dsd.is_blending = 1 THEN (
                        SELECT bl.correlativo FROM blending bl WHERE bl.id = dsd.id_mineral LIMIT 1
                    )
                    WHEN dsd.is_blending = 0 THEN (
                        SELECT vcd.cod_gel
                        FROM catalogolotes lot
                        INNER JOIN valorizacion_compramineral_detalle vcd ON vcd.cod_lote = lot.ccod_Lote
                        WHERE lot.id_CatalogoLotes = dsd.id_mineral
                        LIMIT 1
                    )
                END AS codigo_mineral,
                t.cplaca AS placa1,
                CONCAT(d.serie_segunda_placa, '-', d.numero_segunda_placa) AS placa2,
                cli.razon_social as transportista_rs,
                cli.documento as transportista_doc,
                tv.descripcion as tipo_vehiculo,
                con.nombres as conductor_nombre,
                con.dni_licencia as conductor_doc,
                dd.peso_tara,
                dd.peso_bruto,
                dd.peso_neto,
                d.fecha_hora_llegada,
                desp.correlativo as despacho_correlativo
              FROM distribucion_detalle dd
              INNER JOIN distribucion d ON dd.id_distribucion = d.id
              INNER JOIN despacho desp ON d.id_despacho = desp.id
              INNER JOIN despacho_detalle dsd ON dd.id_despacho_detalle = dsd.id
              LEFT JOIN transporte t ON d.id_unidad = t.id_transporte
              LEFT JOIN tb_clientes cli ON d.id_empresa_transporte = cli.Id
              LEFT JOIN tbconfig_tipovehiculo tv ON t.id_tipovehiculo = tv.Id
              LEFT JOIN tbconfig_conductores con ON d.id_conductor = con.Id
              WHERE dd.id = $id_detalle";

if ($res_balanza = mysqli_query($enlace, $q_balanza)) {
    if ($row = mysqli_fetch_assoc($res_balanza)) {
        $ticket_balanza = $row["ticket_balanza"] ?? '---';
        $cod_lote = $row["codigo_mineral"] ?? '---';
        $placa_1 = $row["placa1"] ?? '---';
        $placa_2 = $row["placa2"] ?? '';
        $transportista_razonsocial = $row["transportista_rs"] ?? '---';
        $transportista_documento = $row["transportista_doc"] ?? '---';
        $tipo_vehiculo = $row["tipo_vehiculo"] ?? '---';
        $conductor_nombres = $row["conductor_nombre"] ?? '---';
        $conductor_documento = $row["conductor_doc"] ?? '---';
        $peso_tara = $row["peso_tara"] ?? 0;
        $peso_bruto = $row["peso_bruto"] ?? 0;
        $peso_neto = $row["peso_neto"] ?? 0;
        $fecha_registro = $row["fecha_hora_llegada"] ?? date('Y-m-d H:i:s');

        // Código QR
        $url = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $dir = 'images/qr/';
        $file_name = $dir . 'ticket_dist_qr_' . $id_detalle . '.png';
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        QRcode::png($url, $file_name, 'H', 3, 3);

        // Convertir QR a Base64 para Dompdf
        $qr_base64 = base64_encode(file_get_contents($file_name));
        $qr_src = 'data:image/png;base64,' . $qr_base64;
    }
}

$html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket: ' . $ticket_balanza . '</title>
    <style>
        @font-face {
            font-family : "AgencyFB";
            src: url("fonts/AgencyFB.ttf");
        }
        @font-face {
            font-family : "AgencyFBb";
            src: url("fonts/AgencyFB-Bold.ttf");
        }
        html, body {
            font-family: AgencyFB;
            margin: 0;
            padding: 10px 5px;
            font-size: 15px;
            text-align: center;
            line-height: 1.3;
        }
        @page {
            margin: 0;
        }
        .bold { font-family: AgencyFBb; }
        .row { width: 100%; margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        .sep { border-top: 1px dashed #000; margin: 5px 0; }
    </style>
</head>
<body style="width: 100%; padding: 0px; text-align: center;">
    <div style="width: 100%; padding: 5px;">
        <div class="row" style="text-align: center;">
            TICKET DE BALANZA: <label class="bold">' . $ticket_balanza . '</label>
        </div>

        <div class="sep"></div>

        <div class="row" style="text-align: center; font-family: AgencyFBb; font-size: 22px;">
            LOTE: ' . $cod_lote . '
        </div>

        <div class="sep"></div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; ">
                        <label class="bold">Placa 1: </label>
                        <label>' . $placa_1 . '</label>
                    </td>

                    <td style="width: 50%; text-align: right;">
                        <div style="margin-right: 5px;">
                            <label class="bold">SEGUNDO TRAMO</label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label class="bold">Placa 2: </label>
            <label>' . ($placa_2 ?: '-') . '</label>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label class="bold">Emp. de Transporte: </label>
            <label>' . $transportista_documento . '</label>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label>' . $transportista_razonsocial . '</label>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label class="bold">Tipo Vehículo: </label>
            <label>' . $tipo_vehiculo . '</label>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label class="bold">Conductor: </label>
            <label>' . $conductor_documento . '</label>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label>' . $conductor_nombres . '</label>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label class="bold">Proveedor: </label>
            <label>---</label>
        </div>

        <div class="row" style="margin-left: 5px; text-align: left;">
            <label class="bold">Observación: </label>
            <label>---</label>
        </div>

        <div class="sep"></div>

        <div class="row" style="text-align: center; font-family: AgencyFBb; font-size: 18px;">
            PESAJE
        </div>

        <div class="sep"></div>

        <div class="row" style="margin-left: 5px; margin-right: 5px; text-align: left;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%;">
                        <label class="bold">FECHA REGISTRO: </label>
                    </td>

                    <td style="width: 40%; text-align: right;">
                        <label>' . substr($fecha_registro, 0, 10) . '</label>
                    </td>
                </tr>
            </table>
        </div>

        <div class="sep"></div>

        <div class="row" style="text-align: center; font-family: AgencyFBb; font-size: 18px;">
            RESUMEN
        </div>

        <div class="sep"></div>

        <div class="row" style="margin-left: 5px; margin-right: 5px; text-align: left;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <label class="bold">TARA: </label>
                    </td>

                    <td style="width: 50%; text-align: right;">
                        <label>' . number_format($peso_tara, 0, '.', ',') . ' Kg</label>
                    </td>
                </tr>

                <tr>
                    <td style="width: 50%;">
                        <label class="bold">PESO BRUTO: </label>
                    </td>

                    <td style="width: 50%; text-align: right;">
                        <label>' . number_format($peso_bruto, 0, '.', ',') . ' Kg</label>
                    </td>
                </tr>


                <tr>
                    <td style="width: 50%;">
                        <label class="bold">PESO NETO: </label>
                    </td>

                    <td style="width: 50%; text-align: right;">
                        <label class="bold">' . number_format($peso_neto, 0, '.', ',') . ' Kg</label>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <img src="' . $qr_src . '" width="120" />
        </div>
    </div>
</body>
</html>';

$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$document = new Dompdf($options);
$document->loadHtml($html, 'UTF-8');
$document->setPaper(array(0, 0, 190, 700));
$document->render();
$document->stream("TicketBalanza_" . $ticket_balanza, array('Attachment' => 0));
