<?php

  session_start();

  include('cnx/cnx.php');
  include('global/variables.php');

  require('libs/phpqrcode/qrlib.php');
  require_once 'dompdf/autoload.inc.php';

  use Dompdf\Dompdf;
  use Dompdf\Options;

  $id_unidad = $_GET["x"];
  $serie_guia = $_GET["a"];
  $numero_guia = $_GET["b"];

  // Cambiar el formato de Fecha PE (backend.php)
    function f_FormatFecha($fechahora, $show_hora = 1) {
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
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_segundotramo_guiat_v1.php')) . 'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_segundotramo_guiat_v1.php')) . '/';

  // 1. Obteniendo datos de la guía
    $nom_archivo = 'Guía de Remisión Electrónica';
    $tipo_guia = mb_strtoupper($nom_archivo);

    $q_datos = "SELECT DISTINCT
                       DL.guiaremitente_serie,
                       DL.guiaremitente_numero,
                       DL.guiatransportista_serie,
                       DL.guiatransportista_numero,
                       DL.guias_fecha,
                       DL.fechahora_emision,
                       DL.guias_puntopartida,
                       DL.guias_puntodestino,
                       DL.guias_placa1 AS cplaca,
                       TR.id_marca,
                       UPPER(M.descripcion) AS MARCA,
                       UPPER(TR.codigo_mtc) AS codigo_mtc,
                       DL.guias_placa2 AS PLACA2,
                       TR2.id_marca AS ID_MARCA2,
                       UPPER(M2.descripcion) AS MARCA2,
                       UPPER(TR2.codigo_mtc) AS CODIGO_MTC2,
                       DL.guias_idchofer,
                       C.dni_licencia,
                       C.licencia_conducir,
                       UPPER(C.nombres) AS CONDUCTOR,
                       DL.guias_destinatario,
                       UPPER(PM.documento) AS PROVEEDORMINERO_RUC,
                       UPPER(PM.razon_social) AS PROVEEDORMINERO_RAZONSOCIAL,
                       ET.documento AS TRANSPORTISTA_RUC,
                       UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
                       ET.cod_MTC AS TRANSPORTISTA_CODIGO_MTC,
                       UPPER(DL.guias_motivotraslado) AS MOTIVO_TRASLADO,
                       DL.guias_remitenteruc,
                       DL.guias_remitenterazonsocial
                  FROM despachos_segundotramo_distribucion_lotes DL
                       INNER JOIN despachos_segundotramo_distribucion_unidades U ON DL.id_distribucionunidad = U.Id
                       INNER JOIN transporte TR ON DL.guias_placa1 = TR.cplaca
                       LEFT JOIN transporte TR2 ON DL.guias_placa2 = TR2.cplaca
                       LEFT JOIN tbconfig_unidadesmarca M ON TR.id_marca = M.Id
                       LEFT JOIN tbconfig_unidadesmarca M2 ON TR2.id_marca = M2.Id
                       LEFT JOIN tbconfig_conductores C ON DL.guias_idchofer = C.Id
                       INNER JOIN tb_clientes ET ON TR.id_Transportista = ET.Id
                       INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
                       LEFT JOIN despachos_primertramo_validaciondatos V ON DL.id_validaciondatos = V.Id
                       LEFT JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
                 WHERE U.Id = " . $id_unidad . "
                   AND MD5(DL.guiatransportista_serie) = '" . $serie_guia . "'
                   AND MD5(DL.guiatransportista_numero) = '" . $numero_guia . "'";

    if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
          $guiaR_serie = $row_datos["guiaremitente_serie"];
          $guiaR_numero = $row_datos["guiaremitente_numero"];
          $guiaR = $guiaR_serie.'-'.$guiaR_numero;

          $guiaT_serie = $row_datos["guiatransportista_serie"];
          $guiaT_numero = $row_datos["guiatransportista_numero"];
          $guiaT = $guiaT_serie.'-'.$guiaT_numero;

          $nom_archivo_guia = $guiaT;

          $fecha_guia = f_FormatFecha($row_datos["guias_fecha"], 0);
          $guiaR_fechahoraemision = f_FormatFecha($row_datos["fechahora_emision"]);
          // $fechahora_registro = $row_datos["guias_fechahoraregistro"];
          $guias_puntopartida = $row_datos["guias_puntopartida"];
          $guias_puntodestino = $row_datos["guias_puntodestino"];
          // $placa_1 = $row_datos["balanza_placa"];
          $placa_1 = $row_datos["cplaca"] . ((strlen($row_datos["PLACA2"]) == 0) ? '' : ' / ' . $row_datos["PLACA2"]);
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
          $concesion = '';
          $codigo_unico = '';
          $motivo_traslado = $row_datos["MOTIVO_TRASLADO"];

          // Genera en línea el código QR
            $url = 'https://gelerp.intelli-apps.com/print_segundotramo_guiat_v1.php?x='.$id_unidad.'&a='.$serie_guia.'&b='.$numero_guia;
            $dir = 'images/qr/';
            $file_name = $dir . 'tmp_qr_'.$guiaT_serie.$id_unidad.'.png';

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
                                ' . $transportista_razonsocial . '
                              </div>

                              <div style="margin-top: 20px; font-size: 14px;">
                                <label style="font-family: AgencyFBb;">Número de registro del MTC: </label><label style="font-family: AgencyFB;">' . ((strlen($transportista_codigo_mtc) == 0) ? '---' : $transportista_codigo_mtc) . '</label>
                              </div>

                              <div style="margin-top: 5px; font-size: 14px;">
                                <label style="font-family: AgencyFBb;">Fecha y hora de emisión: </label><label style="font-family: AgencyFB;">' . $guiaR_fechahoraemision . '</label>
                              </div>
                            </td>

                            <td style="text-align: center; vertical-align: middle; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; width: 40%; padding: 0px; height: 60px;">
                              <div style="font-size: 18px; font-family: AgencyFBb;">
                                RUC N° ' . $transportista_ruc . '
                              </div>

                              <div style="font-size: 18px; font-family: AgencyFBb; margin-top: -5px;">
                                ' . $tipo_guia . '
                              </div>

                              <div style="font-size: 18px; font-family: AgencyFBb; margin-top: -5px;">
                                <label style="font-size: 20px; font-family: AgencyFBb;">
                                  TRANSPORTISTA
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
                                <label style="font-family: AgencyFBb;">Datos del Remitente: </label><label style="font-family: AgencyFB;">' . $proveedor_razonsocial . ' - REGISTRO ÚNICO DE CONTRIBUYENTES N° ' . $proveedor_ruc . '</label>
                              </div>
                            </td>
                          </tr>

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
                              <label style="font-family: AgencyFBb;">Documentos Relacionados: </label>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row">
                        <table style="width: 100%;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top;">
                              <label style="font-family: AgencyFB;">Guía de Remisión Remitente N° '.$guiaR.' - RUC N° '.$proveedor_ruc.'</label>
                            </td>
                          </tr>
                        </table>
                      </div>

                      <div class="row" style="margin-top: 10px;">
                        <table style="width: 100%;">
                          <tr style="font-size: 14px;">
                            <td style="vertical-align: top; width: 50%;">
                              <label style="font-family: AgencyFBb;">Bienes por Transportar: </label>
                            </td>
                          </tr>
                        </table>
                      </div>';

  // 3. Arma la estructura de Detalle
    $d = 1;

   $q_datos = "SELECT /*DL.cod_lote,*/

                      (SELECT DISTINCT V.codigo_gel
                                      FROM despachos_primertramo_validaciondatos V
                                     WHERE V.Id = PD.id_validaciondatos) AS cod_lote,

                      DB.descripcion AS DESCRIPCION_BIEN,
                      DL.guias_pesonetoajustado,
                      PD.codigo_planta,
                      DL.cod_lote,
                      DL.num_parte,
                      DL.id_tipocarga,
                      TC.descripcion AS TIPO_CARGA,
                      DL.num_bigbag
                 FROM despachos_segundotramo_programacion_detalle PD
                      INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
                      INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
                      INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
                        AND PD.id_validaciondatos = DL.id_validaciondatos
                      INNER JOIN tbconfig_segundotramo_guiasdescripcionbien DB ON DL.guias_iddescripcionbien = DB.Id
                      INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
                WHERE U.Id = " . $id_unidad . "
                  AND MD5(DL.guiatransportista_serie) = '" . $serie_guia . "'
                  AND MD5(DL.guiatransportista_numero) = '" . $numero_guia . "'
               ORDER BY 1";

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

          $total_TNE += $row_datos["guias_pesonetoajustado"];
        }
      }
    }

  // 4. Completando pie de página
    $html .= '<div class="row">
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

    $html .= '<div class="row" style="margin-top: 10px;">
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
                      <label style="font-family: AgencyFB;">Indicador de transbordo programado: NO</label>
                    </td>

                    <td style="vertical-align: top; width: 50%;">
                      
                    </td>
                  </tr>
                </table>
              </div>';

    $html .= '<div class="row">
                <table style="width: 100%;">
                  <tr style="font-size: 14px;">
                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFB;">Indicador de retorno de vehículo vacío: NO</label>
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
                      <label style="font-family: AgencyFB;">Indicador de Transporte subcontratado: NO</label>
                    </td>

                    <td style="vertical-align: top; width: 50%;">
                      <label style="font-family: AgencyFB;">Indicador del pagador del flete: Sin pagador de flete</label>
                    </td>
                  </tr>
                </table>
              </div>';

      $html .= '<div class="row" style="margin-top: 10px;">
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
                        <label style="font-family: AgencyFB;">CONCESIÓN: '.$concesion.' / C.U.: '.$codigo_unico.'</label>
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
