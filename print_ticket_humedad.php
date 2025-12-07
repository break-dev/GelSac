<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	require('libs/phpqrcode/qrlib.php');

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_md5 = $_GET["x"];
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
	// Ruta logo
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_ticket_humedad.php')).'images/';

	function nombre_numeros($_numero){
		// Determinando la cantidad de dígitos
			$digitos = strlen($_numero);

		// Descomponiendo números
			$entero = substr($_numero, 0, strpos($_numero, '.'));
			$decimal = substr($_numero, strpos($_numero, '.') + 1);

			$digitos = strlen($entero);

			// Unidad
				if ($digitos >= 1){
					$unidad = substr($entero, -1);

					$unidad = (($unidad == 0) ? '' : $unidad);

					if ($unidad == 1){
						$unidad = 'uno';
					}
					if ($unidad == 2){
						$unidad = 'dos';
					}
					if ($unidad == 3){
						$unidad = 'tres';
					}
					if ($unidad == 4){
						$unidad = 'cuatro';
					}
					if ($unidad == 5){
						$unidad = 'cinco';
					}
					if ($unidad == 6){
						$unidad = 'seis';
					}
					if ($unidad == 7){
						$unidad = 'siete';
					}
					if ($unidad == 8){
						$unidad = 'ocho';
					}
					if ($unidad == 9){
						$unidad = 'nueve';
					}
				}

			// Decena entre 11 y 19
				if ($digitos >= 2){
					$decena = substr($entero, -2);

					if ($decena >= 10 && $decena <= 19){
						$unidad = '';

						if ($decena == 10){
							$decena = 'Diez';
						}
						if ($decena == 11){
							$decena = 'Once';
						}
						if ($decena == 12){
							$decena = 'Doce';
						}
						if ($decena == 13){
							$decena = 'Trece';
						}
						if ($decena == 14){
							$decena = 'Catorce';
						}
						if ($decena == 15){
							$decena = 'Quince';
						}
						if ($decena == 16){
							$decena = 'Dieciseis';
						}
						if ($decena == 17){
							$decena = 'Diecisiete';
						}
						if ($decena == 18){
							$decena = 'Dieciocho';
						}
						if ($decena == 19){
							$decena = 'Diecinueve';
						}
					}
					else{ // Decenas
						$decena = substr(substr($entero, -2), 0, 1);

						$decena = (($decena == 0) ? '' : $decena);

						if ($decena == 2){
							if (strlen($unidad) == 0){
								$decena = 'Veinte';
							}
							else{
								$decena = 'Veinti';
							}
						}
						if ($decena == 3){
							if (strlen($unidad) == 0){
								$decena = 'Treinta';
							}
							else{
								$decena = 'Treinta y ';
							}
						}
						if ($decena == 4){
							if (strlen($unidad) == 0){
								$decena = 'Cuarenta';
							}
							else{
								$decena = 'Cuarenta y ';
							}
						}
						if ($decena == 5){
							if (strlen($unidad) == 0){
								$decena = 'Cincuenta';
							}
							else{
								$decena = 'Cincuenta y ';
							}
						}
						if ($decena == 6){
							if (strlen($unidad) == 0){
								$decena = 'Sesenta';
							}
							else{
								$decena = 'Sesenta y ';
							}
						}
						if ($decena == 7){
							if (strlen($unidad) == 0){
								$decena = 'Setenta';
							}
							else{
								$decena = 'Setenta y ';
							}
						}
						if ($decena == 8){
							if (strlen($unidad) == 0){
								$decena = 'Ochenta';
							}
							else{
								$decena = 'Ochenta y ';
							}
						}
						if ($decena == 9){
							if (strlen($unidad) == 0){
								$decena = 'Noventa';
							}
							else{
								$decena = 'Noventa y ';
							}
						}
					}
				}

			// Centenas
				if ($digitos >= 3){
					$centena = substr(substr($entero, -3), 0, 1);

					$centena = (($centena == 0) ? '' : $centena);

					if ($centena == 1){
						if ($entero == 100){
							$centena = 'Cien ';
						}
						else{
							$centena = 'Ciento ';
						}
					}
					if ($centena == 2){
						$centena = 'Doscientos ';
					}
					if ($centena == 3){
						$centena = 'Trescientos ';
					}
					if ($centena == 4){
						$centena = 'Cuatrocientos ';
					}
					if ($centena == 5){
						$centena = 'Quinientos ';
					}
					if ($centena == 6){
						$centena = 'Seiscientos ';
					}
					if ($centena == 7){
						$centena = 'Setecientos ';
					}
					if ($centena == 8){
						$centena = 'Ochocientos ';
					}
					if ($centena == 9){
						$centena = 'Novecientos ';
					}
				}

			// Millares
				if ($digitos >= 5){
					$millar = substr(substr($entero, -5), 0, 1);

					$millar = (($millar == 0) ? '' : $millar);

					if ($millar == 1){
						$millar = 'Mil ';
					}
					if ($millar == 2){
						$millar = 'Dos mil ';
					}
					if ($millar == 3){
						$millar = 'Tres mil ';
					}
					if ($millar == 4){
						$millar = 'Cuatro mil ';
					}
					if ($millar == 5){
						$millar = 'Cinco mil ';
					}
					if ($millar == 6){
						$millar = 'Seis mil ';
					}
					if ($millar == 7){
						$millar = 'Siete mil ';
					}
					if ($millar == 8){
						$millar = 'Ocho mil ';
					}
					if ($millar == 9){
						$millar = 'Nueve mil ';
					}
				}

		$entero = $millar.$centena.$decena.$unidad.' y '.$decimal.'/100 Soles';

		return $entero;
	}

	// Obteniendo datos de cabecera de la recepción
		$cod_lote = '';
		$placa = '';
		$proveedor_minero = '';
		$encargado_muestra = '';
		$producto = '';
		$tipo_mineral = '';
		$observacion = '';
		$id_humedad = 0;
		$prom_humedad = '';
		$fechahora_recepcion = '';
		$fecha_reporte = '';

		$m = 1;

		// $q_balanza = "SELECT V.lote_cod_lote,
		// 										 V.balanza_placa,
		// 							       CL.razon_social AS PROVEEDOR_MINERO,
		// 							       EM.nombres AS ENCARGADO_MUESTRA,
		// 							       PD.descripcion AS PRODUCTO,
		// 							       TM.descripcion AS TIPO_MINERAL,
		// 							       V.despacho_observacion,
		// 							       HC.Id,
		// 							       HC.cierre_prom,
		// 							       L.preparacion_fechahoraregistro,
		// 							       HC.fechahora_cierre
		// 								FROM despachos_primertramo_validaciondatos V
		// 										 LEFT JOIN tb_clientes CL ON V.lote_id_proveedorminero = CL.Id
		// 										 LEFT JOIN tbconfig_encargadosmuestra EM ON V.lote_id_encargadomuestra = EM.Id
		// 							       LEFT JOIN tbconfig_producto PD ON V.lote_id_producto = PD.Id
		// 							       LEFT JOIN tbconfig_tipomineral TM ON V.lote_id_tipomineral = TM.Id
		// 							       INNER JOIN analisislq_humedad_cabecera HC ON V.lote_cod_lote = HC.cod_interno
		// 							       INNER JOIN catalogolotes L ON V.lote_cod_lote = L.ccod_Lote
		// 							WHERE MD5(V.lote_cod_lote) = '".$id_md5."'";

		$q_balanza = "SELECT L.ccod_Lote,
												 L.placa,
									       CL.razon_social AS PROVEEDOR_MINERO,
									       EM.nombres AS ENCARGADO_MUESTRA,
									       PD.descripcion AS PRODUCTO,
									       TM.descripcion AS TIPO_MINERAL,
									       L.balanza_observacion,
									       HC.Id,
									       HC.cierre_prom,
									       L.preparacion_fechahoraregistro,
									       HC.fechahora_cierre
										FROM catalogolotes L
												 LEFT JOIN tb_clientes CL ON L.balanza_id_proveedorminero = CL.Id
									       LEFT JOIN tbconfig_encargadosmuestra EM ON L.balanza_id_encargadomuestra = EM.Id
									       LEFT JOIN tbconfig_producto PD ON L.balanza_id_producto = PD.Id
									       LEFT JOIN tbconfig_tipomineral TM ON L.balanza_id_tipomineral = TM.Id
									       INNER JOIN analisislq_humedad_cabecera HC ON L.ccod_Lote = HC.cod_interno
									 WHERE MD5(L.ccod_Lote) = '".$id_md5."'
									 	 AND HC.is_reanalisis = 0";

		if ($res_balanza = mysqli_query($enlace, $q_balanza)){
      if (mysqli_num_rows($res_balanza) > 0) {
        while($row_balanza = mysqli_fetch_array($res_balanza)){
        	$cod_lote = $row_balanza["ccod_Lote"];
					$placa = $row_balanza["placa"];
					$proveedor_minero = $row_balanza["PROVEEDOR_MINERO"];
					$encargado_muestra = $row_balanza["ENCARGADO_MUESTRA"];
					$producto = $row_balanza["PRODUCTO"];
					$tipo_mineral = $row_balanza["TIPO_MINERAL"];
					$observacion = $row_balanza["balanza_observacion"];
					$id_humedad =  $row_balanza["Id"];
					$prom_humedad = $row_balanza["cierre_prom"];
					$fechahora_recepcion = $row_balanza["preparacion_fechahoraregistro"];
					$fecha_reporte = $row_balanza["fechahora_cierre"];

					// Genera el Código QR
						$url = $url_lims.'print_ticket_humedad.php?x='.$id_md5;

		        $dir = 'images/qr/';
		        $file_name = $dir.'ticket_qr_'.$id_md5.'.png';

		        if (!file_exists($dir)){
							mkdir($dir);
		        }

						// Genera QR
							QRcode::png($url, $file_name, 'H', 3, 3);
				}
			}
		}

	// Inicia html
    $html = '<!DOCTYPE html>
							<html lang="es">
								<head>
					        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					        <meta charset="utf-8">
					        <meta http-equiv="X-UA-Compatible" content="IE=edge">
					        <meta name="viewport" content="width=device-width, initial-scale=1">
					        <title>Resultados Humead</title>
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
											font-family: AgencyFB;
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

						    <body style="width: 100%; padding: 0px; text-align: center;">
						    	<div style="width: 100%; margin-left: 0px; margin-top: 10px margin-right: 0px;">
										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -8px; text-align: center; font-family: AgencyFBb; font-size: 20px;">
											LOTE: '.$cod_lote.'
										</div>

										<div class="row" style="margin-top: -10px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 7px; text-align: left; margin-bottom: -10px;">
											<table style="width: 100%;">
												<tr>
													<td>
														<div style="margin-right: 7px;">
															<label style="font-family: AgencyFBb;">PRIMER TRAMO</label>
														</div>
													</td>
												</tr>
											</table>
										</div>';

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Cierre Análisis: </label>
											<label>'.$fecha_reporte.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Proveedor Minero: </label>
											<label>'.((strlen(trim($proveedor_minero)) == 0) ? '---' : mb_strtoupper($proveedor_minero)).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left; display: none;">
											<label style="font-family: AgencyFBb;">Encargado Muestra: </label>
											<label>'.((strlen(trim($encargado_muestra)) == 0) ? '---' : mb_strtoupper($encargado_muestra)).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Producto: </label>
											<label>'.((strlen(trim($producto)) == 0) ? '---' : mb_strtoupper($producto)).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Mineral: </label>
											<label>'.((strlen(trim($tipo_mineral)) == 0) ? '---' : mb_strtoupper($tipo_mineral)).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Observación: </label>
											<label>'.((strlen(trim($observacion)) == 0) ? '---' : mb_strtoupper($observacion)).'</label>
										</div>

										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; text-align: center; font-family: AgencyFBb;">
											HUMEDAD (H2O)
										</div>

										<div class="row" style="margin-top: -7px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; margin-right: 10px; text-align: left;">
											<table style="width: 100%;">
												<tr>
													<td colspan="2" style="width: 100%; text-align: center;">
														<label style="font-family: AgencyFBb; font-size: 20px;">'.number_format($prom_humedad, 2, '.', '').' %</label>
													</td>
												</tr>
											</table>
										</div>

										<div class="row" style="margin-top: -7px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left; font-family: AgencyFBb;">
											Conformidad de Humedad:
										</div>

										<div class="row" style="margin-top: 50px; font-family: AgencyFBb; border-bottom-width: 1px; border-bottom: solid; margin-left: 10px; margin-right: 10px;">
											
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: center; font-family: AgencyFBb;">
											Firma:
										</div>

										<div class="row" style="margin-top: 5px; margin-left: 10px; text-align: left; font-family: AgencyFBb;">
											Nombre:
										</div>

										<div class="row" style="margin-top: 0px; font-family: AgencyFBb; border-bottom-width: 1px; border-bottom: solid; margin-left: 55px; margin-right: 10px;">
											
										</div>
										';

	$html .= '			
								</div>
							</body>
					  </html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');

	// Solo si es balanza aumenta el Height
		$document -> setPaper(array(0, 0, 190, 700));

	$document -> render();
	$document -> stream("Recibo - Ticket ".$ticket_balanza, array('Attachment' => 0));

?>
