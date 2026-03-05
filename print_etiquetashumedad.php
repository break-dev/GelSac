<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('libs/barcode.php');

	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startuo_errors', 0);

	// Recuperando parámetros
		$id_md5 = $_GET["x"];

	// Ruta imágenes
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_etiquetashumedad.php')).'images/';

  // Inicia html
    $html = '<!DOCTYPE html>
							<html lang="es">
								<head>
					        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					        <meta charset="utf-8">
					        <meta http-equiv="X-UA-Compatible" content="IE=edge">
					        <meta name="viewport" content="width=device-width, initial-scale=1">
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
											font-family: AgencyFBb;
											margin: 0;
											padding: -5;
											margin-bottom: -15px;
										}

										@page{
											margin: 0;
											pading: 0;
										}
									</style>
						    </head>

						    <body style="width: 100%; padding: 0px;">';

  // Obtiene los datos de cada muestra
    $cod_lote = '';
    $fecha_registro = '';
    $hora_registro = '';

    $q_datos = "SELECT L.ccod_Lote,
    									 L.dFechaIngreso,
    									 L.tHora_Ingreso
									FROM catalogolotes L
								 WHERE md5(L.id_CatalogoLotes) = '".$id_md5."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
			if (mysqli_num_rows($res_datos) > 0) {
				while($row_datos = mysqli_fetch_array($res_datos)){
					// Recuperando valores
						$cod_lote = $row_datos["ccod_Lote"];
						$fecha_registro = $row_datos["dFechaIngreso"];
						$hora_registro = $row_datos["tHora_Ingreso"];

					// Genera los códigos de barra
						barcode( 'images/bc/'.$cod_lote.'A.png', $cod_lote.'A', 20, 'horizontal', 'code128', false );
						barcode( 'images/bc/'.$cod_lote.'B.png', $cod_lote.'B', 20, 'horizontal', 'code128', false );

					// Pintando Etiquetas
						$html .= '<div style="width: 102mm; height: 24mm; margin: 0px; pading: 0px;">
												<table style="width: 101.4mm; border-spacing: 0; margin: 0px;">
													<tr>
														<td style="border-spacing: 0; width: 55mm; margin: 0px;">
															<div style="margin-left: -5px; margin-right: 12px; width: 100%; height: 24mm;">
																<div style="width: 100%; height: 23mm; margin: -2px;">
																	<table style="min-width: 100%; border-spacing: 0">
																		<tr style="font-size: 10px; font-family: AgencyFBb;">
																			<td colspan="2" style="text-align: center;">
																				<div style="width: 50mm; height: 15mm; margin-top: 0px; margin-left: -25px; margin-right: 50px; font-size: 28px;">
																					'.$cod_lote.'.A
																				</div>
																			</td>

																			<td style="text-align: right;">
																				<div style="width: 17mm; height: 10mm; margin-right: -15px; margin-top: 5px; text-align: right;">
																					<div style="width: 100%; font-size: 11px;">
																						'.$fecha_registro.'
																					</div>

																					<div style="margin-top: -5px; font-size: 10px;">
																						'.$hora_registro.'
																					</div>
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 11px; font-weight: bold;">
																			<td colspan="3">
																				<div style="width: 100%; margin-top: -10px; text-align: center;">
																					<img src="'.$ruta_images.'bc/'.$cod_lote.'A.png" style="height: 25px; width: 90%;"/>
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 14px; font-family: AgencyFBb;">
																			<td colspan="3 style="text-align: center;">
																				<div style="text-align: center; margin-top: -5px; width: 100%">
																					ANALISIS HUMEDAD - GEL SAC
																				</div>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
														</td>

														<td style="border-spacing: 0; width: 52mm; margin: 0px;">
															<div style="margin-left: -5px; margin-right: 12px; width: 100%; height: 24mm;">
																<div style="width: 100%; height: 23mm; margin: -2px;">
																	<table style="min-width: 100%; border-spacing: 0">
																		<tr style="font-size: 10px; font-family: AgencyFBb;">
																			<td colspan="2" style="text-align: center;">
																				<div style="width: 50mm; height: 15mm; margin-top: 0px; margin-left: -25px; margin-right: 50px; font-size: 28px;">
																					'.$cod_lote.'.B
																				</div>
																			</td>

																			<td style="text-align: right;">
																				<div style="width: 17mm; height: 10mm; margin-right: -15px; margin-top: 5px; text-align: right;">
																					<div style="width: 100%; font-size: 11px;">
																						'.$fecha_registro.'
																					</div>

																					<div style="margin-top: -5px; font-size: 10px;">
																						'.$hora_registro.'
																					</div>
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 11px; font-weight: bold;">
																			<td colspan="3">
																				<div style="width: 100%; margin-top: -10px; text-align: center;">
																					<img src="'.$ruta_images.'bc/'.$cod_lote.'B.png" style="height: 25px; width: 90%;"/>
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 14px; font-family: AgencyFBb;">
																			<td colspan="3 style="text-align: center;">
																				<div style="text-align: center; margin-top: -5px; width: 100%">
																					ANALISIS HUMEDAD - GEL SAC
																				</div>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
														</td>
													</tr>
												</table>
											</div>';
				}
			}
		}

	$html .= '		</body>
			  		</html>';

// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper(array(0, 0, 291, 77));
	$document -> render();
	$document -> stream("Recibo - ".$recibo_codigo, array('Attachment' => 0));

?>
