<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_distribucionunidad = $_GET["x"];
	$id_modalidadenvio= $_GET["m"];

	// Funciones
		function formatearFecha($fecha) {
	    // Separar fecha
				$dia = str_pad(explode('-', $fecha)[2], 2, '0', STR_PAD_LEFT);
				$mes = nombre_meses(explode('-', $fecha)[1]);
				$anho = explode('-', $fecha)[0];

	    return $dia.' de '.$mes.' del '.$anho;
		}

		function nombre_meses($num_mes){
			if ($num_mes == 1){
				return "ENERO";
			}
			if ($num_mes == 2){
				return "FEBRERO";
			}
			if ($num_mes == 3){
				return "MARZO";
			}
			if ($num_mes == 4){
				return "ABRIL";
			}
			if ($num_mes == 5){
				return "MAYO";
			}
			if ($num_mes == 6){
				return "JUNIO";
			}
			if ($num_mes == 7){
				return "JULIO";
			}
			if ($num_mes == 8){
				return "AGOSTO";
			}
			if ($num_mes == 9){
				return "SEPTIEMBRE";
			}
			if ($num_mes == 10){
				return "OCTUBRE";
			}
			if ($num_mes == 11){
				return "NOVIEMBRE";
			}
			if ($num_mes == 12){
				return "DICIEMBRE";
			}
		}

	// Ruta imágenes
    $ruta_images_x = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_cargosguias.php')).'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_cargosguias.php')).'/';

	// 1. Obteniendo datos de la guía
		$nom_archivo = 'Guía de Remisión Electrónica';
		$tipo_guia = mb_strtoupper($nom_archivo);

		$q_datos = "SELECT DISTINCT
											 DL.Id,
											 DL.guiaremitente_serie,
											 DL.guiaremitente_numero,
											 DL.guiatransportista_serie,
											 DL.guiatransportista_numero,
											 DL.guias_fecha,
											 DL.guias_puntopartida,
											 DL.guias_puntodestino,
											 TR.cplaca,
											 TR.id_marca,
											 UPPER(M.descripcion) AS MARCA,
											 UPPER(TR.codigo_mtc) AS codigo_mtc,
											 TR2.cplaca AS PLACA2,
											 TR2.id_marca AS ID_MARCA2,
											 UPPER(M2.descripcion) AS MARCA2,
											 UPPER(TR2.codigo_mtc) AS CODIGO_MTC2,
											 DL.guias_idchofer,
											 C.dni_licencia,
											 UPPER(C.nombres) AS CONDUCTOR,
											 DL.guias_destinatario,
											 ET.documento AS TRANSPORTISTA_RUC,
											 UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
											 UPPER(DL.guias_motivotraslado) AS MOTIVO_TRASLADO,
								       DL.guias_fechahoraregistro,
								       P.id_planta,
								       P.fechaestimada_despacho,

							         (SELECT COUNT(DL_x.Id)
							        		FROM despachos_segundotramo_distribucion_lotes DL_x
							           WHERE DL_x.id_distribucionunidad = U.Id) AS TOTAL_LOTES,

							         (SELECT COUNT(DL_x.Id)
							        		FROM despachos_segundotramo_distribucion_lotes DL_x
							           WHERE DL_x.id_distribucionunidad = U.Id
							           	 AND DL_x.guiaremitente_serie IS NOT NULL) AS TOTAL_LOTES_GUIA,

							      	 (SELECT ruc
													FROM tbconfig_remitentessegundotramo
												 WHERE id_destino = DL.guias_iddestino
													 AND id_modalidadenvio = DL.guias_idmodalidadenvio) AS REMITENTE_RUC,

							      	 (SELECT razon_social
													FROM tbconfig_remitentessegundotramo
												 WHERE id_destino = DL.guias_iddestino
													 AND id_modalidadenvio = DL.guias_idmodalidadenvio) AS REMITENTE_RAZONSOCIAL

								  FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
										   INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
							         INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
							           AND PD.cod_lote = DL.cod_lote
								 			 INNER JOIN transporte TR ON U.id_unidad = TR.id_transporte
								 			 LEFT JOIN transporte TR2 ON U.id_unidad2 = TR2.id_transporte
								  		 LEFT JOIN tbconfig_unidadesmarca M ON TR.id_marca = M.Id
								  		 LEFT JOIN tbconfig_unidadesmarca M2 ON TR2.id_marca = M2.Id
								  		 LEFT JOIN tbconfig_conductores C ON DL.guias_idchofer = C.Id
								  		 INNER JOIN tb_clientes ET ON TR.id_Transportista = ET.Id
									  	 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
								 WHERE MD5(U.Id) = '".$id_distribucionunidad."'
								 	 AND DL.guias_idmodalidadenvio = ".$id_modalidadenvio."
								 	 /*AND MD5(DL.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(DL.guiaremitente_numero) = '".$numero_guia."'*/";

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

					$fecha_guia = $row_datos["guias_fecha"];
					$guias_puntopartida = $row_datos["guias_puntopartida"];
					$guias_puntodestino = $row_datos["guias_puntodestino"];
					$placa_1 = $row_datos["cplaca"].((strlen($row_datos["PLACA2"]) == 0) ? '' : ' / '.$row_datos["PLACA2"]);
					$marca_1 = $row_datos["MARCA"].((strlen($row_datos["MARCA2"]) == 0) ? '' : ' / '.$row_datos["MARCA2"]);
					$constancia_mtc_1 = $row_datos["codigo_mtc"].((strlen($row_datos["CODIGO_MTC2"]) == 0) ? '' : ' / '.$row_datos["CODIGO_MTC2"]);
					$conductor_licencia = $row_datos["dni_licencia"];
					$conductor_nombres = $row_datos["CONDUCTOR"];
					$destinatario = $row_datos["guias_destinatario"];
					$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
					$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
					$motivo_traslado = $row_datos["MOTIVO_TRASLADO"];
					$remitente_ruc = $row_datos["REMITENTE_RUC"];
					$remitente_razonsocial = $row_datos["REMITENTE_RAZONSOCIAL"];
					$fechahora_registro = $row_datos["guias_fechahoraregistro"];
					$id_destino = $row_datos["id_planta"];
					$fechaestimada_despacho = $row_datos["fechaestimada_despacho"];

					// Genera en línea el código QR
				    $url = 'https://gelerp.intelli-apps.com/print_cargosguias.php?a='.$serie_guia.'&b='.$numero_guia;

				    $dir = 'tmp_files/';
				    $file_name = $dir.'tmp_qr_'.$row_datos["guiatransportista_serie"].$row_datos["guiatransportista_numero"].'.png';

				    if (!file_exists($dir)){
				      mkdir($dir);
				    }

					  // Genera QR
					    QRcode::png($url, $file_name, 'H', 3, 3);
        }
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Cargo 2do tramo - Guia N° '.$guiaR_serie.' '.$guiaR_numero.'</title>

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
										<table style="width: 100%;">
											<tr style="font-size: 14px;">
												<td style="text-align: left; vertical-align: top; max-width: 10%;">
													<div style="font-family: AgencyFB; font-size: 16px;">
														'.$remitente_razonsocial.'
													</div>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%;">
											<tr style="font-size: 16px;">
												<td style="vertical-align: middle; text-align: center; height: 60px;">
													<div style="font-family: AgencyFBb;">
														<label style="font-family: AgencyFBb;">
															CONSTANCIA DE ENTREGA DE DOCUMENTOS
														</label>
													</div>
												</td>
											</tr>

											<tr style="font-size: 16px;">
												<td style="vertical-align: middle; text-align: justify;">
													<label style="font-family: AgencyFB;">
														Hoy, '.formatearFecha($fecha_guia).' se hace constar la entrega al Sr(a): _______________________________________________________<br>con DNI: ____________________ ; la documentación que se especifica a continuación:
													</label>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 15px; font-family: AgencyFBb;">
												<td colspan="'.(($id_destino == 3) ? '5' : '4').'" style="text-align: center; border: solid; border-width: 1px; border-color: #D9D9D9; background-color: #D9D9D9; vertical-align: middle;">
													TRAZABILIDAD DE LOTES
												</td>
											</tr>

											<tr style="font-size: 15px; font-family: AgencyFBb;">
												<td colspan= "'.(($id_destino == 3) ? '3' : '2').'">
												</td>

												<td colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													SEGUNDO TRAMO
												</td>
											</tr>

											<tr style="font-size: 15px; font-family: AgencyFBb;">
												<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													LOTE AUM
												</td>';

												if ($id_destino == 3){
													$html .= '<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
																			LOTE COLIBRI
																		</td>';
												}

		$html .= '					<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 350px;">
													PROVEEDOR
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													GRR (destinatario, SUNAT)
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													GRT (destinatario, SUNAT)
												</td>
											</tr>
										</thead>

										<tbody>';

	// 2. Arma la estructura de Detalle
		$d = 1;
		$total_TNE = 0;

		$cod_planta = '';
		$cod_lote = '';
		$num_parte = '';

		$q_datos = "SELECT DL.cod_lote,
											 DB.descripcion AS DESCRIPCION_BIEN,
											 DL.guias_pesonetoajustado,
											 PD.codigo_planta,
											 DL.cod_lote,
											 DL.num_parte,
											 DL.id_tipocarga,
								       TC.descripcion AS TIPO_CARGA,
								       DL.num_bigbag,
								       DL.guiaremitente_serie,
								       DL.guiaremitente_numero,
								       DL.guiatransportista_serie,
								       DL.guiatransportista_numero,
								       DL.guias_idmodalidadenvio,

								       (SELECT DISTINCT CONCAT(PM.documento, ' - ', PM.razon_social)
													FROM despachos_primertramo_validaciondatos V
															 INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
												 WHERE V.lote_cod_lote = DL.cod_lote) AS PROVEEDOR_MINERO,

							      	 (SELECT CONCAT (ruc, ' - ', razon_social)
													FROM tbconfig_remitentessegundotramo
												 WHERE id_destino = DL.guias_iddestino
													 AND id_modalidadenvio = DL.guias_idmodalidadenvio) AS REMITENTE_RAZONSOCIAL,

											 P.id_planta,
											 IFNULL(PD.cmh_codigodocumentos, '') AS CMH_CODIGODOCUMENTOS,
											 IFNULL(PD.cmh_codigoguias, '') AS CMH_CODIGOGUIAS,

											 (SELECT COUNT(DL_x.Id) AS _COUNT
											 		FROM despachos_segundotramo_distribucion_lotes DL_x
											 	 WHERE DL_x.cod_lote = PD.cod_lote) AS TOTAL_PARTES

								  FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
										   INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
							         INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
							           AND PD.cod_lote = DL.cod_lote
								  		 INNER JOIN tbconfig_segundotramo_guiasdescripcionbien DB ON DL.guias_iddescripcionbien = DB.Id
									  	 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
								 WHERE MD5(U.Id) = '".$id_distribucionunidad."'
								 	 AND DL.guias_idmodalidadenvio = ".$id_modalidadenvio."
								 	 /*AND MD5(DL.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(DL.guiaremitente_numero) = '".$numero_guia."'*/
								ORDER BY DL.cod_lote";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$cod_planta = $row_datos["codigo_planta"];
					$cod_lote = $row_datos["cod_lote"];
					$num_parte = $row_datos["num_parte"];
					$id_tipocarga = $row_datos["id_tipocarga"];
					$tipo_carga = $row_datos["TIPO_CARGA"];
					$num_bigbag = $row_datos["num_bigbag"];
					$guia_remitente = $row_datos["guiaremitente_serie"].'-'.$row_datos["guiaremitente_numero"];
					$guia_transportista = $row_datos["guiatransportista_serie"].'-'.$row_datos["guiatransportista_numero"];
					// $id_modalidadenvio = $row_datos["guias_idmodalidadenvio"];
					$proveedor_minero = ((($id_destino == 3 || $id_destino == 15) && ($id_modalidadenvio == 3 || $id_modalidadenvio == 4 || $id_modalidadenvio == 5)) ? $row_datos["REMITENTE_RAZONSOCIAL"] : $row_datos["PROVEEDOR_MINERO"]);
					$id_planta = $row_datos["id_planta"];
					$cmh_codigodocumentos = $row_datos["CMH_CODIGODOCUMENTOS"];
					$cmh_codigoguias = $row_datos["CMH_CODIGOGUIAS"];
					$total_partes = $row_datos["TOTAL_PARTES"];

					if ($id_planta == 15){
						$cmh_codigodocumentos = $cmh_codigodocumentos.(($total_partes > 1) ? ' ('.$num_parte.'/'.$total_partes.')' : '');
					}

        	$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';
        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

        	if ($id_planta == 15){
        		$html .= '							'.$cmh_codigodocumentos;
        	}
        	else{
        		$html .= '							'.$cod_lote.((strlen($num_parte) > 0) ? '<br>PARTE '.$num_parte : '');
        	}

        	$html .= '						</td>';

        	if ($id_destino == 3){
        		$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        	$html .= '							'.((strlen($cod_planta) > 0) ? $cod_planta : '').((strlen($num_parte) > 0) ? '<br>PARTE '.$num_parte : '');
	        	$html .= '						</td>';
        	}

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$proveedor_minero;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$guia_remitente;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$guia_transportista;
        	$html .= '						</td>';
					$html .= '					</tr>';

					$d ++;
        }
      }
    }

		$html .= '					</tbody>
										</table>
									</div>';

	// 3. Completnado pie de página
		$html .= '<div class="row" style="margin-top: 50px; margin-left: 150px;">
								<table style="width: 100%;">
									<tr style="font-size: 14px; font-family: AgencyFB;">
										<td style="vertical-align: top;">
											Para mayor constancia de lo recepcionado firmo la presente en señal de conformidad.
										</td>
									</tr>
								</table>
							</div>';

		$html .= '<div class="row" style="margin-top: 30px; margin-left: 150px;">
								<table style="width: 100%;">
									<tr style="font-size: 14px;">
										<td style="vertical-align: top; font-family: AgencyFB; height: 30px;">
											FIRMA: ___________________________________
										</td>
									</tr>

									<tr style="font-size: 14px;">
										<td style="vertical-align: top; font-family: AgencyFB;">
											HORA: ____________________________________
										</td>
									</tr>
								</table>
							</div>';

	// Cierra html
    $html .= '	</body>
							</html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper('A4', 'portrait');
	$document -> render();
	$document -> stream('Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia, array('Attachment' => 0));

?>
