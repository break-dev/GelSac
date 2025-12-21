<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('libs/barcode.php');

	require_once 'dompdf/autoload.inc.php';
	require_once 'dompdf/vendor/phenx/php-font-lib/src/FontLib/Autoloader.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$cod_interno = $_GET["CI"];
	$fecha_recepcion = $_GET["FR"];
	$elemento_analisis = $_GET["EA"];
	$tipo_analisis = $_GET["TA"];
	$id = $_GET["Id"];
	$peso_muestra = $_GET["PM"];

	// Ruta imágenes
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_etiquetas_ensayos.php')).'images/';

	// Gnera ls códigos de barra
	// barcode( 'images/bc/'.$id.'.png', $id, 20, 'horizontal', 'code128', false );
	barcode( 'images/bc/1000.png', '1000', 20, 'horizontal', 'code128', false );

// echo substr($cod_interno, 6);

	$html = '<!DOCTYPE html>
						<html lang="es">
							<head>
				        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				        <meta charset="utf-8">
				        <meta http-equiv="X-UA-Compatible" content="IE=edge">
				        <meta name="viewport" content="width=device-width, initial-scale=1">

				        <title>Impresión de Etiquetas</title>

								<style type="text/css">
						        html {
										margin: 0;
										pading: 0;
									}
									body {
										font-family: "Times New Roman", Times, serif;
									}
								</style>
					    </head>

					    <body style="width: 100%; padding: 0px;">
					    	<div style="height: 28mm; margin-top: 2px; margin-bottom: -10px;">
									<table style="width: 100%; border-spacing: 0; margin-top: -5px;">
										<tr>
											<td style="border-spacing: 0; width: 50%; height: 100px;">
												<div style="margin-top: -10px; margin-left: 20px;">
													<table style="width: 100%; border-spacing: 0">
														<tr style="font-size: 9px; font-weight: bold;">
															<td style="width: 30%; text-align: center;">
																<div class="row">
																	> > > >
																</div>

																<div class="row">
																	CLIENTE
																</div>

																<div class="row">
																	> > > >
																</div>
															</td>

															<td style="width: 40%; text-align: center;">
																<label style="font-size: 37px; font-weight: bold; text-align: left; margin-left: 5px; margin-top: -10px;">
																	9820
																</label>
															</td>

															<td style="width: 10%; text-align: right;">
																<div style="margin-right: 20px; font-size: 9px;">
																	03/02/2023
																</div>

																<div style="margin-right: 25px; font-size: 9px;">
																	15:30
																</div>
															</td>
														</tr>

														<tr style="font-size: 11px; font-weight: bold;">
															<td colspan="3">
																<img src="'.$ruta_images.'bc/1000.png" style="height: 30px; width: 90%;"/>
															</td>
														</tr>

														<tr style="font-size: 11px; font-weight: bold;">
															<td colspan="3 style="text-align: center;">
																<div style="text-align: center;">
																	CC LABORATORIO
																</div>
															</td>
														</tr>
													</table>
												</div>
											</td>

											<td style="border-spacing: 0; width: 50%; height: 100px;">
												<div style="margin-top: -15px; margin-left: 15px; margin-right: 2px;">
													<table style="width: 100%;">
														<tr style="font-size: 11px; font-weight: bold;">
															<td style="width: 75%">
																<label>
																	CC LABORATORIO
																</label>
															</td>

															<td style="width: 25%; text-align: center;">
																<label style="font-size: 27px; font-weight: bold; text-align: left; margin-left: -20px; margin-top: -10px;">
																	9820
																</label>
															</td>
														</tr>

														<tr style="font-size: 11px; font-weight: bold;">
															<td style="font-size: 10px;">
																<div style="margin-top: -13px;">
																	03/02/2023 15:30
																</div>
															</td>

															<td style="font-size: 10px;">
																<div style="margin-top: -10px; text-align: center; margin-left: -25px;">
																	SOBRE
																</div>
															</td>
														</tr>

														<tr style="font-size: 11px; font-weight: bold;">
															<td colspan="2" style="font-size: 10px;">
																<div style="margin-top: -5px;">
																	NOMBRE DE LA MUESTRA 123
																</div>
															</td>
														</tr>

														<tr style="font-size: 11px; font-weight: bold;">
															<td colspan="2" style="font-size: 10px;">
																<div style="margin-top: -4px;">
																	MIXTO ROCA
																</div>
															</td>
														</tr>

														<tr style="font-size: 11px; font-weight: bold;">
															<td colspan="2" style="font-size: 10px;">
																<div style="margin-top: -4px;">
																	BOLSA DE PLASTICO
																</div>
															</td>
														</tr>

														<tr style="font-weight: bold;">
															<td colspan="2" style="font-size: 10px;">
																<div style="font-size: 9px; margin-top: -4px;">
																	Au, Ag, Cu, Au, Ag, CuAu, Ag, CuAu, Ag, CuAu, Ag, Cu
																</div>
															</td>
														</tr>
													</table>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</body>

			  		</html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper(array(0, 0, 305, 78));
	$document -> render();
	$document -> stream("Recibo - ".$recibo_codigo, array('Attachment' => 0));

?>
