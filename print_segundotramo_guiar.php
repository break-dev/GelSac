<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');

require('libs/phpqrcode/qrlib.php');
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_unidad_x = $_GET["x"];
$serie_guia = $_GET["a"];
$numero_guia = $_GET["b"];
$id_modalidadenvio = $_GET["c"];

// Ruta im►0genes
$ruta_images_x = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_segundotramo_guiar.php')) . 'images/';
$ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_segundotramo_guiar.php')) . '/';

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
											 UPPER(C.nombres) AS CONDUCTOR,
											 DL.guias_destinatario,
											 ET.documento AS TRANSPORTISTA_RUC,
											 UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
											 UPPER(DL.guias_motivotraslado) AS MOTIVO_TRASLADO,
								       DL.guias_fechahoraregistro,
								       P.id_planta,
								       DL.guias_remitenteruc,
								       DL.guias_remitenterazonsocial

								  FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
										   INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
							         INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
							           AND PD.id_validaciondatos = DL.id_validaciondatos
								 			 INNER JOIN transporte TR ON DL.guias_placa1 = TR.cplaca
								 			 LEFT JOIN transporte TR2 ON DL.guias_placa2 = TR2.cplaca
								  		 LEFT JOIN tbconfig_unidadesmarca M ON TR.id_marca = M.Id
								  		 LEFT JOIN tbconfig_unidadesmarca M2 ON TR2.id_marca = M2.Id
								  		 LEFT JOIN tbconfig_conductores C ON DL.guias_idchofer = C.Id
								  		 INNER JOIN tb_clientes ET ON TR.id_Transportista = ET.Id
									  	 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
								 WHERE U.Id = " . $id_unidad_x . "
								 	 AND MD5(DL.guiaremitente_serie) = '" . $serie_guia . "'
									 AND MD5(DL.guiaremitente_numero) = '" . $numero_guia . "'
									 AND DL.guias_idmodalidadenvio = " . $id_modalidadenvio;

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

			$fecha_guia = $row_datos["guias_fecha"];
			$fechahora_registro = $row_datos["fechahora_emision"];
			$guias_puntopartida = $row_datos["guias_puntopartida"];
			$guias_puntodestino = $row_datos["guias_puntodestino"];
			$placa_1 = $row_datos["cplaca"] . ((strlen($row_datos["PLACA2"]) == 0) ? '' : ' / ' . $row_datos["PLACA2"]);
			$marca_1 = $row_datos["MARCA"] . ((strlen($row_datos["MARCA2"]) == 0) ? '' : ' / ' . $row_datos["MARCA2"]);
			$constancia_mtc_1 = $row_datos["codigo_mtc"] . ((strlen($row_datos["CODIGO_MTC2"]) == 0) ? '' : ' / ' . $row_datos["CODIGO_MTC2"]);
			$conductor_licencia = $row_datos["dni_licencia"];
			$conductor_nombres = $row_datos["CONDUCTOR"];
			$destinatario = $row_datos["guias_destinatario"];
			$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
			$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
			$motivo_traslado = $row_datos["MOTIVO_TRASLADO"];
			$remitente_ruc = $row_datos["guias_remitenteruc"];
			$remitente_razonsocial = $row_datos["guias_remitenterazonsocial"];
			$id_destino = $row_datos["id_planta"];

			// Genera en línea el código QR
			$url = 'https://gelerp.intelli-apps.com/print_segundotramo_guiar.php?x=' . $id_unidad_x . '&a=' . $serie_guia . '&b=' . $numero_guia;

			$dir = 'tmp_files/';
			$file_name = $dir . 'tmp_qr_' . $id_unidad_x . $row_datos["guiatransportista_serie"] . $row_datos["guiatransportista_numero"] . '.png';

			if (!file_exists($dir)) {
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
														' . $remitente_razonsocial . '
													</div>

													<div style="margin-top: 50px; font-size: 14px;">
														<label style="font-family: AgencyFBb;">Fecha y hora de emisión: </label><label style="font-family: AgencyFB;">' . $fechahora_registro . '</label>
													</div>
												</td>

												<td style="text-align: center; vertical-align: middle; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; width: 40%; padding: 0px; height: 60px;">
													<div style="font-size: 18px; font-family: AgencyFBb;">
														RUC N° ' . $remitente_ruc . '
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
														<label style="font-family: AgencyFBb;">Motivo de Traslado: </label><label style="font-family: AgencyFB;">' . $motivo_traslado . '</label>
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

// 2. Arma la estructura de Detalle
$d = 1;
$total_TNE = 0;

$cod_planta = '';
$cod_lote = '';
$num_parte = '';

$q_datos = "SELECT DL.cod_lote,
											 PD.id_planta,
											 DB.descripcion AS DESCRIPCION_BIEN,
											 DL.guias_pesonetoajustado,
											 PD.codigo_planta,
											 /*DL.cod_lote,*/

											 (SELECT DISTINCT V.codigo_gel
																			FROM despachos_primertramo_validaciondatos V
																		 WHERE V.Id = PD.id_validaciondatos) AS cod_lote,

											 DL.num_parte,
											 DL.id_tipocarga,
								       TC.descripcion AS TIPO_CARGA,
								       DL.num_bigbag,

											 P.id_planta,

											 (SELECT COUNT(DL_x.Id) AS _COUNT
											 		FROM despachos_segundotramo_distribucion_lotes DL_x
											 	 WHERE DL_x.id_validaciondatos = PD.id_validaciondatos) AS TOTAL_PARTES

								  FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
										   INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
							         INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
							           AND PD.id_validaciondatos = DL.id_validaciondatos
								  		 INNER JOIN tbconfig_segundotramo_guiasdescripcionbien DB ON DL.guias_iddescripcionbien = DB.Id
									  	 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
								 WHERE U.Id = " . $id_unidad_x . "
								 	 AND MD5(DL.guiaremitente_serie) = '" . $serie_guia . "'
									 AND MD5(DL.guiaremitente_numero) = '" . $numero_guia . "'
								ORDER BY DL.cod_lote";

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
			$total_partes = $row_datos["TOTAL_PARTES"];

			$html .= '					<tr style="font-size: 12px; font-family: AgencyFB;">';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '							' . $d;
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '							NO';
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

			$html .= '							' . ((strlen($cod_planta) > 0) ? $cod_planta : '') . (($id_planta == 3) ? ((strlen($num_parte) > 0) ? ' PARTE ' . $num_parte : '') : '');

			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '							' . mb_strtoupper($row_datos["DESCRIPCION_BIEN"]);

			if ($id_planta == 15){
				$html .= '							- '.$cmh_codigoguias;
			}
			else{
				if ($id_tipocarga == 1) {
					$html .= ' - A ' . mb_strtoupper($tipo_carga);
				} else {
					$html .= ' - ' . $num_bigbag . ' ' . mb_strtoupper($tipo_carga);
				}
			}

			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
			$html .= '							TONELADAS';
			$html .= '						</td>';

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">';

			$total_TNE += $row_datos["guias_pesonetoajustado"];

			$html .= '							' . number_format($row_datos["guias_pesonetoajustado"], 2, '.', '');
			$html .= '						</td>';

			$html .= '					</tr>';

			$d++;
		}
	}
}

$html .= '					</tbody>
										</table>
									</div>';

// 3. Completnado pie de página
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
											<label style="font-family: AgencyFB;">Indicador para registrar vehículos y conductores del transportista: ' . (($id_destino == 2) ? 'NO' : 'SI') . '</label>
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
								</table>
							</div>';

if ($id_destino == 2) {
	$html .= '<div class="row" style="margin-top: 20px;">
									<table style="width: 100%;">
										<tr style="font-size: 14px;">
											<td style="vertical-align: top;">
												<label style="font-family: AgencyFBb;">Observaciones: </label><label style="font-family: AgencyFB;">MARCA: ' . $marca_1 . ' PLACA: ' . $placa_1 . ' MTC: ' . $constancia_mtc_1 . ' LICENCIA: ' . $conductor_licencia . '</label>
											</td>
										</tr>
									</table>
								</div>';
} else {
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
		$html .= '		<tr style="font-size: 14px;">
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

	$html .= '	</table>
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
												<label style="font-family: AgencyFB;">' . $conductor_nombres . ' - DOCUMENTO NACIONAL DE IDENTIDAD N° ' . $conductor_licencia . '</label>
											</td>
										</tr>

										<tr style="font-size: 14px;">
											<td style="vertical-align: top; width: 10%;">
												<label style="font-family: AgencyFB;"></label>
											</td>

											<td style="vertical-align: top;">
												<label style="font-family: AgencyFB;">Número de lincencia de conducir: ' . $conductor_licencia . '</label>
											</td>
										</tr>
									</table>
								</div>';
}

// Cierra html
$html .= '	</body>
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
