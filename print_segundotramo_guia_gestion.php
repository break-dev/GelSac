<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

require('libs/phpqrcode/qrlib.php');
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_guia = intval($_GET["id"] ?? 0);

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Ruta imagenes
$ruta_images_x = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_segundotramo_guia_gestion.php')) . 'images/';
$ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_segundotramo_guia_gestion.php')) . '/';

// 1. Obteniendo datos de la guía
$nom_archivo = 'Guía de Remisión Electrónica';
$tipo_guia = mb_strtoupper($nom_archivo);

$q_datos = "
SELECT
	g.id,
	g.id_distribucion,
	g.id_marca_tolva,
	g.id_empresa_transporte_tolva,
	DATE_FORMAT(g.fecha_inicio_traslado,'%d/%m/%Y') AS fecha_inicio_traslado,
	DATE_FORMAT(g.fecha_hora_emision,'%d/%m/%Y %H:%i') AS fecha_hora_emision,
	DATE_FORMAT(g.fecha_hora_planta,'%d/%m/%Y %H:%i') AS fecha_hora_planta,
	g.planta_origen,
	CASE 
		WHEN g.planta_origen = 1 THEN 'Huanchaco' 
		WHEN g.planta_origen = 2 THEN 'Laredo' ELSE '-'
	END AS planta_origen_nombre,
	g.guia_remitente_serie,
	g.guia_remitente_numero,
	g.guia_transportista_serie,
	g.guia_transportista_numero,
	g.sin_guia_transportista,
	g.motivo_traslado,
	g.serie_tolva,
	g.numero_tolva,
	g.numero_mtc_tolva,
	g.estado,
	marca.descripcion AS marca_tolva_nombre,
	emp_tolva.razon_social AS empresa_tolva_nombre,
	(
		SELECT GROUP_CONCAT(DISTINCT t2.cplaca SEPARATOR ' / ')
		FROM distribucion d2
		INNER JOIN transporte t2 ON d2.id_unidad = t2.id_transporte
		WHERE d2.id_guia_segundo_tramo = g.id
	) AS placas,
	(
		SELECT GROUP_CONCAT(DISTINCT t2.codigo_mtc SEPARATOR ' / ')
		FROM distribucion d2
		INNER JOIN transporte t2 ON d2.id_unidad = t2.id_transporte
		WHERE d2.id_guia_segundo_tramo = g.id
	) AS mtc_tractos,
	(
		SELECT GROUP_CONCAT(DISTINCT c2.nombres SEPARATOR ' / ')
		FROM distribucion d2
		LEFT JOIN tbconfig_conductores c2 ON d2.id_conductor = c2.Id
		WHERE d2.id_guia_segundo_tramo = g.id
	) AS conductor_nombres,
	(
		SELECT GROUP_CONCAT(DISTINCT c2.dni_licencia SEPARATOR ' / ')
		FROM distribucion d2
		LEFT JOIN tbconfig_conductores c2 ON d2.id_conductor = c2.Id
		WHERE d2.id_guia_segundo_tramo = g.id
	) AS conductor_documento,
	(
		SELECT GROUP_CONCAT(DISTINCT c2.licencia_conducir SEPARATOR ' / ')
		FROM distribucion d2
		LEFT JOIN tbconfig_conductores c2 ON d2.id_conductor = c2.Id
		WHERE d2.id_guia_segundo_tramo = g.id
	) AS conductor_licencias,
	(
		SELECT GROUP_CONCAT(DISTINCT cli2.razon_social SEPARATOR ', ')
		FROM distribucion d2
		LEFT JOIN tb_clientes cli2 ON d2.id_empresa_transporte = cli2.id
		WHERE d2.id_guia_segundo_tramo = g.id
	) AS empresa_transporte,
	(
		SELECT GROUP_CONCAT(DISTINCT cli2.documento SEPARATOR ', ')
		FROM distribucion d2
		LEFT JOIN tb_clientes cli2 ON d2.id_empresa_transporte = cli2.id
		WHERE d2.id_guia_segundo_tramo = g.id
	) AS transporte_ruc
FROM guia_segundo_tramo g
LEFT JOIN tbconfig_unidadesmarca marca ON g.id_marca_tolva = marca.Id
LEFT JOIN tb_clientes emp_tolva ON g.id_empresa_transporte_tolva = emp_tolva.id
WHERE g.id = $id_guia
LIMIT 1
";

$res_datos = mysqli_query($enlace, $q_datos);
if (!$res_datos || mysqli_num_rows($res_datos) == 0) {
	die("No se encontraron datos para la guía proporcionada.");
}
$row_datos = mysqli_fetch_array($res_datos);

$guiaR_serie = $row_datos["guia_remitente_serie"];
$guiaR_numero = $row_datos["guia_remitente_numero"];
$nom_archivo_guia = $guiaR_serie . '-' . $guiaR_numero;

$fecha_guia = $row_datos["fecha_inicio_traslado"];
$fechahora_registro = $row_datos["fecha_hora_emision"];
$guias_puntopartida = 'PLANTA GEL (' . $row_datos["planta_origen_nombre"] . ')';
$guias_puntodestino = 'ALMACÉN / PUERTO SALAVERRY';
$placa_1 = $row_datos["placas"];
$constancia_mtc_1 = $row_datos["mtc_tractos"];
$conductor_documento = $row_datos["conductor_documento"];
$conductor_licencia = $row_datos["conductor_licencias"];
$conductor_nombres = $row_datos["conductor_nombres"];
$transportista_ruc = $row_datos["transporte_ruc"];
$transportista_razonsocial = $row_datos["empresa_transporte"];
$motivo_traslado = $row_datos["motivo_traslado"];
$remitente_ruc = '20601334057';
$remitente_razonsocial = 'GEL S.A.C.';
$destinatario = '20601334057 - GEL S.A.C.';

// Tolva Info
$placa_tolva = $row_datos["serie_tolva"] . '-' . $row_datos["numero_tolva"];
$mtc_tolva = $row_datos["numero_mtc_tolva"];
$empresa_tolva = $row_datos["empresa_tolva_nombre"];

// Genera en línea el código QR
$url = 'https://gelerp.intelli-apps.com/print_segundotramo_guia_gestion.php?id=' . $id_guia;
$dir = 'tmp_files/';
$file_name = $dir . 'tmp_qr_g2t_' . $id_guia . '_' . $guiaR_serie . $guiaR_numero . '.png';

if (!file_exists($dir)) {
	mkdir($dir);
}
// Genera QR
QRcode::png($url, $file_name, 'H', 3, 3);

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

$q_detalles = "
	SELECT
		dd.id AS id_detalle,
		dd.tipo_carga,
		dd.cantidad_bigbags,
		dd.numero_parte,
		dd.peso_neto,
		dsd.is_blending,
		CASE WHEN dsd.is_blending = 1 THEN (
			SELECT b.correlativo FROM blending b WHERE b.id = dsd.id_mineral LIMIT 1
		) ELSE (
			SELECT l.ccod_Lote FROM catalogolotes l WHERE l.id_CatalogoLotes = dsd.id_mineral LIMIT 1
		) END AS codigo_mineral,
		desp.correlativo AS correlativo_despacho
	FROM distribucion dist
	INNER JOIN distribucion_detalle dd ON dd.id_distribucion = dist.id
	INNER JOIN despacho_detalle dsd ON dsd.id = dd.id_despacho_detalle
	INNER JOIN despacho desp ON desp.id = dist.id_despacho
	WHERE dist.id_guia_segundo_tramo = $id_guia
	ORDER BY dist.id, dd.id
";

if ($res_detalles = mysqli_query($enlace, $q_detalles)) {
	if (mysqli_num_rows($res_detalles) > 0) {
		while ($row_lote = mysqli_fetch_array($res_detalles)) {
			// Presentación
			$presentacion = "GRANEL";
			if ($row_lote["tipo_carga"] == 1) {
				$presentacion = "SACOS";
			} elseif ($row_lote["tipo_carga"] == 2) {
				$presentacion = $row_lote["cantidad_bigbags"] . " BIG BAGS";
			}

			$descripcion_bien = 'MINERAL AURÍFERO EN BRUTO SIN PROCESAR - ' . $presentacion;

			$html .= '					<tr style="font-size: 12px; font-family: AgencyFB;">';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">' . $d . '</td>';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">NO</td>';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;"></td>';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;"></td>';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;"></td>';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;"></td>';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">' . $descripcion_bien . '</td>';
			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">TONELADAS</td>';

			$peso_neto_tne = floatval($row_lote["peso_neto"]) / 1000;
			$total_TNE += $peso_neto_tne;

			$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">' . number_format($peso_neto_tne, 2, '.', '') . '</td>';
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
											<label style="font-family: AgencyFB;">' . $placa_1 . '</label>
										</td>
									</tr>

									<tr style="font-size: 14px;">
										<td style="vertical-align: top; width: 10%;">
											<label style="font-family: AgencyFB;"></label>
										</td>

										<td colspan="2" style="vertical-align: top;">
											<label style="font-family: AgencyFB;">Número de TUCE o Certificado de Habiltación Vehicular: ' . $constancia_mtc_1 . '</label>
										</td>
									</tr>';

if (strlen($placa_tolva) > 0 && $placa_tolva !== '-') {
	$html .= '		<tr style="font-size: 14px;">
											<td style="vertical-align: top; width: 10%;">
												<label style="font-family: AgencyFB;">Secundario 1: </label>
											</td>

											<td style="vertical-align: top; width: 11%;">
												<label style="font-family: AgencyFB;">Número de placa: </label>
											</td>

											<td style="vertical-align: top;">
												<label style="font-family: AgencyFB;">' . $placa_tolva . '</label>
											</td>
										</tr>

										<tr style="font-size: 14px;">
											<td style="vertical-align: top; width: 10%;">
												<label style="font-family: AgencyFB;"></label>
											</td>

											<td colspan="2" style="vertical-align: top;">
												<label style="font-family: AgencyFB;">Número de TUCE o Certificado de Habiltación Vehicular: ' . $mtc_tolva . '</label>
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
											<label style="font-family: AgencyFB;">' . $conductor_nombres . ' - DOCUMENTO NACIONAL DE IDENTIDAD N° ' . $conductor_documento . '</label>
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

// Cierra html
$html .= '	</body>
							</html>';

$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$document = new Dompdf($options);

$document->loadHtml($html, 'UTF-8');
$document->setPaper('A4', 'portrait');
$document->render();
$document->stream('Modelo de Guía de ' . $nom_archivo . ' - ' . $nom_archivo_guia, array('Attachment' => 0));
