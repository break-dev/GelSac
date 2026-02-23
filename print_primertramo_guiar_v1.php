<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');

require('libs/phpqrcode/qrlib.php');
require_once 'dompdf/autoload.inc.php';
error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startuo_errors', 0);

use Dompdf\Dompdf;
use Dompdf\Options;

$serie_guia = $_GET["a"];
$numero_guia = $_GET["b"];
$id_remitente = $_GET["c"];
$id_transportista = $_GET["d"];
$guia_fecha = $_GET["e"];

// Cambiar el formato de Fecha PE (backend.php)
function f_FormatFecha($fechahora, $show_hora = 1)
{
  if (strtolower($fechahora) === "hoy") {
    return $show_hora ? date("d/m/Y H:i:s") : date("d/m/Y");
  }

  // Intentar crear la fecha desde diferentes formatos
  $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $fechahora);

  // Si no tiene hora, agregar "00:00:00" por defecto
  if (!$fecha) {
    $fecha = DateTime::createFromFormat('Y-m-d', $fechahora);

    if ($fecha) {
      $fecha->setTime(0, 0, 0);
    }
  }

  if (!$fecha) {
    error_log("Formato de fecha no válido: " . $fechahora);

    return null;
  }

  return $show_hora ? $fecha->format('d/m/Y H:i:s') : $fecha->format('d/m/Y');
}

// Ruta imágenes
$ruta_images_x = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_primertramo_guiar_v1.php')) . 'images/';
$ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_primertramo_guiar_v1.php')) . '/';

// 1. Obteniendo datos de la guía
$nom_archivo = 'Guía de Remisión Electrónica';
$tipo_guia = mb_strtoupper($nom_archivo);

$q_datos = "SELECT DISTINCT
                       V.guias_fechahoraemision,
                       V.guiaremitente_serie,
                       V.guiaremitente_numero,
                       V.guiatransportista_serie,
                       V.guiatransportista_numero,
                       V.guias_fecha,
                       V.guias_fechahoraregistro,
                       V.guias_puntopartida,
                       V.guias_puntodestino,
                       V.balanza_placa,
                       V.balanza_placa2,
                       V.unidad_idmarca,
                       UPPER(U.descripcion) AS MARCA,
                       V.unidad_constanciamtc,
                       V.guias_idchofer,
                       C.dni_licencia,
                       C.licencia_conducir,
                       UPPER(C.nombres) AS CONDUCTOR,
                       V.guias_destinatario,
                       UPPER(PM.documento) AS PROVEEDORMINERO_RUC,
                       UPPER(PM.razon_social) AS PROVEEDORMINERO_RAZONSOCIAL,
                       ET.documento AS TRANSPORTISTA_RUC,
                       UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
                       ET.cod_MTC AS TRANSPORTISTA_CODIGO_MTC,
                       /*
                       UPPER(PM.proveedorminero_concesion) AS CONCESION,
                       UPPER(PM.proveedorminero_codigounico) AS CODIGO_UNICO,
                       UPPER(PM.proveedorminero_codigounico) AS CODIGO_UNICO,
                       */
                       UPPER(CS.descripcion) AS CONCESION,
                       UPPER(CS.codigo_unico) AS CODIGO_UNICO,
                       UPPER(V.guias_motivotraslado) AS MOTIVO_TRASLADO
                  FROM despachos_primertramo_validaciondatos V
                       LEFT JOIN tbconfig_unidadesmarca U ON V.unidad_idmarca = U.Id
                       LEFT JOIN tbconfig_conductores C ON V.guias_idchofer = C.Id
                       LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
                       INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
                       INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
                       INNER JOIN tbconfig_proveedoresmineros_concesion CS ON V.lote_id_proveedorminero_concesion = CS.Id
                 WHERE MD5(V.guiaremitente_serie) = '" . $serie_guia . "'
                   AND MD5(V.guiaremitente_numero) = '" . $numero_guia . "'
                   AND V.lote_id_proveedorminero = " . $id_remitente . "
                   AND T.id_Transportista = " . $id_transportista . "
                   AND V.guias_fecha = '" . $guia_fecha . "'";

if ($res_datos = mysqli_query($enlace, $q_datos)) {
  if (mysqli_num_rows($res_datos) > 0) {
    while ($row_datos = mysqli_fetch_array($res_datos)) {
      $guiaR_serie = $row_datos["guiaremitente_serie"];
      $guiaR_numero = $row_datos["guiaremitente_numero"];
      $guiaR = $guiaR_serie . '-' . $guiaR_numero;

      $guiaT_serie = $row_datos["guiatransportista_serie"];
      $guiaT_numero = $row_datos["guiatransportista_numero"];
      $guiaT = $guiaT_serie . '-' . $guiaT_numero;

      $nom_archivo_guia = $guiaR;

      $fecha_guia = f_FormatFecha($row_datos["guias_fecha"], 0);
      $guiaR_fechahoraemision = f_FormatFecha($row_datos["guias_fechahoraemision"]);
      // $fechahora_registro = $row_datos["guias_fechahoraregistro"];
      $guias_puntopartida = $row_datos["guias_puntopartida"];
      $guias_puntodestino = $row_datos["guias_puntodestino"];
      // $placa_1 = $row_datos["balanza_placa"];
      $placa_1 = $row_datos["balanza_placa"] . ((strlen($row_datos["balanza_placa2"]) == 0) ? '' : ' / ' . $row_datos["balanza_placa2"]);
      $marca_1 = $row_datos["MARCA"];
      $constancia_mtc_1 = mb_strtoupper($row_datos["unidad_constanciamtc"]);
      $conductor_dni = $row_datos["dni_licencia"];
      $licencia_conducir = $row_datos["licencia_conducir"];
      $conductor_nombres = $row_datos["CONDUCTOR"];
      $destinatario = $row_datos["guias_destinatario"];
      $proveedor_ruc = $row_datos["PROVEEDORMINERO_RUC"];
      $proveedor_razonsocial = $row_datos["PROVEEDORMINERO_RAZONSOCIAL"];
      $transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
      $transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
      $transportista_codigo_mtc = $row_datos["TRANSPORTISTA_CODIGO_MTC"];
      $concesion = $row_datos["CONCESION"];
      $codigo_unico = $row_datos["CODIGO_UNICO"];
      $motivo_traslado = $row_datos["MOTIVO_TRASLADO"];

      // Genera en línea el código QR
      $url = 'https://gelerp.intelli-apps.com/print_primertramo_guiar_v1.php?a=' . $serie_guia . '&b=' . $numero_guia . '&c=' . $id_remitente . '&d=' . $id_transportista . '&e=' . $guia_fecha;
      $dir = 'images/qr/';
      $file_name = $dir . 'tmp_qr_' . $guiaR_serie . $guiaR_numero . $id_remitente . $id_transportista . '.png';

      if (!file_exists($dir)) {
        mkdir($dir);
      }

      // Genera QR
      QRcode::png($url, $file_name, 'H', 3, 3);
    }
  }
}

// 2. Arma la estructura de Cabeceera
$html = ' <!DOCTYPE html>
                  <html lang="es">
                    <head>
                      <title>Modelo de Guía de ' . $nom_archivo . ' - ' . $nom_archivo_guia . '</title>

                      <style>
                        @font-face {
                          font-family : "AgencyFB";
                          src: url("fonts/AgencyFB.ttf");
                        }

                        @font-face {
                          font-family : "AgencyFBb";
                          src: url("fonts/AgencyFB-Bold.ttf");
                        }

                        .fstyle{
                          font: AgencyFB;
                        }

                        .fstyleb{
                          font: AgencyFBb;
                        }

                        html, body{
                          font-family: Arial;
                          margin: 0;
                          padding: -5;
                          margin-bottom: -15px;
                          font-size: 14px;
                        }

                        @page{
                          margin: 0;
                          pading: 0;
                        }
                      </style>
                    </head>

                    <body style="margin-left: 10px; margin-right: 10px;">
                      <div class="row">
                        <table style="width: 100%; margin-top: 20px;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top; min-width: 15%; width: 15%; max-width: 15%;">
                              <div style="font-family: AgencyFBb;">
                                <img style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; width: 100px;" src="' . $ruta_images_qr . $file_name . '"/>
                              </div>
                            </td>

                            <td style="text-align: left; vertical-align: top; max-width: 10%;">
                              <div style="font-family: AgencyFB; font-size: 20px;">
                                ' . $proveedor_razonsocial . '
                              </div>

                              <div style="margin-top: 50px; font-size: 14px;">
                                <label style="font-family: AgencyFBb;">Fecha y hora de emisión: </label><label style="font-family: AgencyFB;">' . $guiaR_fechahoraemision . '</label>
                              </div>
                            </td>

                            <td style="text-align: center; vertical-align: middle; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; width: 40%; padding: 0px; height: 60px;">
                              <div style="font-size: 18px; font-family: AgencyFBb;">
                                RUC N° ' . $proveedor_ruc . '
                              </div>

                              <div style="font-size: 18px; font-family: AgencyFBb; margin-top: -5px;">
                                ' . $tipo_guia . '
                              </div>

                              <div style="font-size: 18px; font-family: AgencyFBb; margin-top: -5px;">
                                <label style="font-size: 20px; font-family: AgencyFBb;">
                                  REMITENTE
                                </label>
                              </div>

                              <div style="font-size: 18px; font-family: AgencyFBb; margin-top: -5px;">
                                <label style="font-size: 18px; font-family: AgencyFBb;">
                                  N° ' . $nom_archivo_guia . '
                                </label>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row" style="margin-top: 10px;">
                        <table style="width: 100%;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top; width: 50%;">
                              <div style="font-family: AgencyFBb;">
                                <label style="font-family: AgencyFBb;">Fecha de Inicio de Traslado: </label><label style="font-family: AgencyFB;">' . $fecha_guia . '</label>
                              </div>
                            </td>

                            <td style="text-align: center; vertical-align: top; width: 50%;">
                              <table style="width: 100%;">
                                <tr>
                                  <td style="vertical-align: top; padding: 0px; width: 25%;">
                                    <label style="font-family: AgencyFBb;">Punto de Partida: </label>
                                  </td>

                                  <td style="vertical-align: top; padding: 0px;">
                                    <label style="font-family: AgencyFB;">' . $guias_puntopartida . '</label>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row" style="margin-top: 10px;">
                        <table style="width: 100%;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top; width: 50%;">
                              <div style="font-family: AgencyFBb;">
                                <label style="font-family: AgencyFBb;">Motivo de Traslado: </label><label style="font-family: AgencyFB;">OTROS</label>
                              </div>
                            </td>

                            <td style="text-align: center; vertical-align: top; width: 50%;">
                              <table style="width: 100%;">
                                <tr>
                                  <td style="vertical-align: top; padding: 0px; width: 25%;">
                                    <label style="font-family: AgencyFBb;">Punto de Llegada: </label>
                                  </td>

                                  <td style="vertical-align: top; padding: 0px;">
                                    <label style="font-family: AgencyFB;">' . $guias_puntodestino . '</label>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row" style="margin-top: 10px;">
                        <table style="width: 100%;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top; width: 50%;">
                              <div style="font-family: AgencyFBb;">
                                <label style="font-family: AgencyFBb;">Descripción de Motivo: </label><label style="font-family: AgencyFB;">SERVICIO DE CHANCADO</label>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row" style="margin-top: 10px;">
                        <table style="width: 100%;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top; width: 50%;">
                              <div style="font-family: AgencyFBb;">
                                <label style="font-family: AgencyFBb;">Datos del Destinatario: </label><label style="font-family: AgencyFB;">' . explode(' - ', $destinatario)[1] . ' - REGISTRO ÚNICO DE CONTRIBUYENTES N° ' . explode(' - ', $destinatario)[0] . '</label>
                              </div>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row" style="margin-top: 10px;">
                        <table style="width: 100%;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top; width: 50%;">
                              <label style="font-family: AgencyFBb;">Bienes por Transportar </label>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row">
                        <table style="width: 100%; border-spacing: -1px;">
                          <thead>
                            <tr style="font-size: 12px; font-family: AgencyFBb;">
                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 30px;">
                                N°
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 70px;">
                                BIEN NORMALIZADO
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 90px;">
                                CÓDIGO DE<br>BIEN
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 80px;">
                                CÓD. PRODUCTO<br>SUNAT
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 60px;">
                                PARTIDA<br>ARANCELARIA
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 50px;">
                                CÓDIGO<br>GTIN
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
                                DESCRIPCIÓN DETALLADA
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 50px;">
                                UNIDAD DE<br>MEDIDA
                              </td>

                              <td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 60px;">
                                CANTIDAD
                              </td>
                            </tr>
                          </thead>

                          <tbody>';

// 3. Arma la estructura de Detalle
$d = 1;

$q_datos = "SELECT VD.lote_cod_lote,
                       VD.guias_pesonetoajustado
                  FROM despachos_primertramo_validaciondatos VD
                       LEFT JOIN transporte T ON VD.balanza_placa = T.cplaca
                       INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
                 WHERE MD5(VD.guiaremitente_serie) = '" . $serie_guia . "'
                   AND MD5(VD.guiaremitente_numero) = '" . $numero_guia . "'
                   AND VD.lote_id_proveedorminero = " . $id_remitente . "
                   AND T.id_Transportista = " . $id_transportista . "
                   AND VD.guias_fecha = '" . $guia_fecha . "'
                ORDER BY VD.lote_cod_lote, VD.lote_num_ticket, VD.lote_ticket_orden";

if ($res_datos = mysqli_query($enlace, $q_datos)) {
  if (mysqli_num_rows($res_datos) > 0) {
    while ($row_datos = mysqli_fetch_array($res_datos)) {
      $id_planta = $row_datos["id_planta"];
      $cod_planta = $row_datos["codigo_planta"];
      $cod_lote = $row_datos["cod_lote"];
      $num_parte = $row_datos["num_parte"];
      $id_tipocarga = $row_datos["id_tipocarga"];
      $tipo_carga = $row_datos["TIPO_CARGA"];
      $num_bigbag = $row_datos["num_bigbag"];
      $id_planta = $row_datos["id_planta"];
      $cmh_codigodocumentos = $row_datos["CMH_CODIGODOCUMENTOS"];
      $cmh_codigoguias = $row_datos["CMH_CODIGOGUIAS"];
      $total_partes = $row_datos["TOTAL_PARTES"];

      if ($id_planta == 15) {
        $cmh_codigoguias = $cmh_codigoguias . (($total_partes > 1) ? ' (' . $num_parte . '/' . $total_partes . ')' : '');
      }

      $html .= '          <tr style="font-size: 12px; font-family: AgencyFB;">';
      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '              ' . $d;
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '              NO';
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '              MINERAL AURÍFERO EN BRUTO SIN PROCESAR';
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      $html .= '              TONELADAS';
      $html .= '            </td>';

      $html .= '            <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">';

      $total_TNE += $row_datos["guias_pesonetoajustado"];

      $html .= '              ' . number_format($row_datos["guias_pesonetoajustado"], 2, '.', '');
      $html .= '            </td>';

      $html .= '          </tr>';

      $d++;
    }
  }
}

$html .= '          </tbody>
                      </table>
                    </div>';

// 4. Completando pie de página
$html .= '<div class="row" style="margin-top: 10px;">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top;">
                      <label style="font-family: AgencyFB;">Unidad de Medida del Peso Bruto: TNE</label>
                    </td>
                  </tr>
                </table>
              </div>';

$html .= '<div class="row">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top;">
                      <label style="font-family: AgencyFB;">Peso Bruto total de la carga: ' . number_format($total_TNE, 2, '.', '') . '</label>
                    </td>
                  </tr>
                </table>
              </div>';

$html .= '<div class="row">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFBb;">Datos del traslado: </label>
                    </td>
                  </tr>
                </table>
              </div>';

$html .= '<div class="row">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFB;">Modalidad de Traslado: Público</label>
                    </td>

                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFB;">Indicador de retorno de vehículo con envases o embalajes vacíos: NO</label>
                    </td>
                  </tr>
                </table>
              </div>';

$html .= '<div class="row">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFB;">Indicador de traslado en vehículos de categoría M1 o L: NO</label>
                    </td>

                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFB;">Indicador para registrar vehículos y conductores del transportista: SI</label>
                    </td>
                  </tr>
                </table>
              </div>';

$html .= '<div class="row">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFBb;">Datos del transportista: </label>
                    </td>
                  </tr>
                </table>
              </div>';

$html .= '<div class="row">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top;">
                      <label style="font-family: AgencyFB;">' . $transportista_razonsocial . ' - REGISTRO ÚNICO DE CONTRIBUYENTES N° ' . $transportista_ruc . '</label>
                    </td>
                  </tr>

                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top;">
                      <label style="font-family: AgencyFB;">Número de registro del MTC: ' . ((strlen($transportista_codigo_mtc) == 0) ? '---' : $transportista_codigo_mtc) . '</label>
                    </td>
                  </tr>
                </table>
              </div>';

$html .= '<div class="row">
                  <table style="width: 100%;">
                    <tr style="font-size: 14px;">
                      <td style="vertical-align: top; width: 50%;">
                        <label style="font-family: AgencyFBb;">Datos de los vehículos: </label>
                      </td>
                    </tr>
                  </table>
                </div>';

$html .= '<div class="row">
                  <table style="width: 100%;">
                    <tr style="font-size: 14px;">
                      <td style="vertical-align: top; width: 10%;">
                        <label style="font-family: AgencyFB;">Principal: </label>
                      </td>

                      <td style="vertical-align: top; width: 11%;">
                        <label style="font-family: AgencyFB;">Número de placa: </label>
                      </td>

                      <td style="vertical-align: top;">
                        <label style="font-family: AgencyFB;">' . explode(' / ', $placa_1)[0] . '</label>
                      </td>
                    </tr>

                    <tr style="font-size: 14px;">
                      <td style="vertical-align: top; width: 10%;">
                        <label style="font-family: AgencyFB;"></label>
                      </td>

                      <td colspan="2" style="vertical-align: top;">
                        <label style="font-family: AgencyFB;">Número de TUCE o Certificado de Habiltación Vehicular: ' . explode(' / ', $constancia_mtc_1)[0] . '</label>
                      </td>
                    </tr>';

if (strlen(explode(' / ', $placa_1)[1]) > 0) {
  $html .= '    <tr style="font-size: 14px;">
                        <td style="vertical-align: top; width: 10%;">
                          <label style="font-family: AgencyFB;">Secundario 1: </label>
                        </td>

                        <td style="vertical-align: top; width: 11%;">
                          <label style="font-family: AgencyFB;">Número de placa: </label>
                        </td>

                        <td style="vertical-align: top;">
                          <label style="font-family: AgencyFB;">' . explode(' / ', $placa_1)[1] . '</label>
                        </td>
                      </tr>

                      <tr style="font-size: 14px;">
                        <td style="vertical-align: top; width: 10%;">
                          <label style="font-family: AgencyFB;"></label>
                        </td>

                        <td colspan="2" style="vertical-align: top;">
                          <label style="font-family: AgencyFB;">Número de TUCE o Certificado de Habiltación Vehicular: ' . explode(' / ', $constancia_mtc_1)[1] . '</label>
                        </td>
                      </tr>';
}

$html .= '  </table>
                    </div>';

$html .= '<div class="row">
                  <table style="width: 100%;">
                    <tr style="font-size: 14px;">
                      <td style="vertical-align: top; width: 50%;">
                        <label style="font-family: AgencyFBb;">Datos de los conductores: </label>
                      </td>
                    </tr>
                  </table>
                </div>';

$html .= '<div class="row">
                  <table style="width: 100%;">
                    <tr style="font-size: 14px;">
                      <td style="vertical-align: top; width: 10%;">
                        <label style="font-family: AgencyFB;">Principal: </label>
                      </td>

                      <td style="vertical-align: top;">
                        <label style="font-family: AgencyFB;">' . $conductor_nombres . ' - DOCUMENTO NACIONAL DE IDENTIDAD N° ' . $conductor_dni . '</label>
                      </td>
                    </tr>

                    <tr style="font-size: 14px;">
                      <td style="vertical-align: top; width: 10%;">
                        <label style="font-family: AgencyFB;"></label>
                      </td>

                      <td style="vertical-align: top;">
                        <label style="font-family: AgencyFB;">Número de lincencia de conducir: ' . $licencia_conducir . '</label>
                      </td>
                    </tr>
                  </table>
                </div>';

$html .= '<div class="row">
                  <table style="width: 100%;">
                    <tr style="font-size: 14px;">
                      <td style="vertical-align: top; width: 50%;">
                        <label style="font-family: AgencyFBb;">Observación: </label>
                        <label style="font-family: AgencyFB;">CONCESIÓN: ' . $concesion . ' / C.U.: ' . $codigo_unico . '</label>
                      </td>
                    </tr>
                  </table>
                </div>';

$html .= '<div class="row" style="margin-top: 50px;">
                  <label style="font-family: AgencyFBb; font-size: 11px;">Esta es una representación impresa sin valor tributario de la Guía de Remisión Electrónica generada en el sistema  de la SUNAT. Este es solo un borrador generado por el ERP de Operaciones de G.E.L.</label>
                </div>';

// Cierra html
$html .= '  </body>
              </html>';
// echo '$html: '.$html;
// return;
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$document = new Dompdf($options);

$document->loadHtml($html, 'UTF-8');
$document->setPaper('A4', 'portrait');
$document->render();
$document->stream('Modelo de Guía de ' . $nom_archivo . ' - ' . $nom_archivo_guia, array('Attachment' => 0));
