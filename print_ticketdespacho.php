<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	require('libs/phpqrcode/qrlib.php');

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_md5 = $_GET["x"];

	// Ruta logo
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_ticketdespacho.php')).'images/';

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
		$num_parte = '';
		$num_ticket = '';
		$placa = '';
		$placa2 = '';
		$ruc_empresatransporte = '';
		$des_empresastransporte = '';
		$tipo_vehiculo = '';
		$dni_conductor = '';
		$des_conductor = '';
		$cod_tipocarga = '';
		$des_tipocarga = '';
		$num_bigbag = '';
		$ruc_remitente = '';
		$des_remitente = '';
		$tipo_mineral = '';
		$observacion = '';
		$destino = '';
		$peso_tara = '';
		$peso_tara_fechahoraregistro = '';
		$peso_tara_usuarioregistro = '';
		$peso_bruto = '';
		$peso_bruto_fechahoraregistro = '';
		$peso_bruto_usuarioregistro = '';
		$peso_neto = '';

		$m = 1;

		$q_balanza = "SELECT L.cod_lote,
												 L.num_parte,
												 L.num_ticketbalanza,
												 I.placa,
												 I.placa2,
												 ET.documento AS RUC_EMPRESATRANSPORTE,
												 ET.razon_social AS EMPRESA_TRANSPORTE,
												 TV.descripcion AS TIPO_VEHICULO,
												 CD.dni_licencia DNI_CONDUCTOR,
												 CD.nombres AS DES_CONDUCTOR,
												 L.id_tipocarga AS COD_TIPOCARGA,
												 TC.descripcion AS DES_TIPOCARGA,
												 L.num_bigbag,

												 CASE WHEN (SELECT DISTINCT VD.despacho_id_modalidadenvio
																			FROM despachos_primertramo_validaciondatos VD
																		 WHERE VD.lote_cod_lote = L.cod_lote
																		LIMIT 1) = 1
												 THEN PL.ruc
												 ELSE CASE WHEN (SELECT DISTINCT VD.despacho_id_modalidadenvio
																					 FROM despachos_primertramo_validaciondatos VD
																					WHERE VD.lote_cod_lote = L.cod_lote
																				 LIMIT 1) = 2
															THEN CASE WHEN PL.Id = 4
																	 THEN (SELECT DISTINCT PM.documento
																					 FROM despachos_primertramo_validaciondatos VD
																								INNER JOIN tb_clientes PM ON VD.lote_id_proveedorminero = PM.Id
																					WHERE VD.lote_cod_lote = L.cod_lote
																				 LIMIT 1)
																	 ELSE PL.ruc END
															ELSE (SELECT ruc
																			FROM tbconfig_plantas
																		 WHERE Id = 11)
															END
												 END AS RUC_REMITENTE,
 
												 CASE WHEN (SELECT DISTINCT VD.despacho_id_modalidadenvio
																			FROM despachos_primertramo_validaciondatos VD
																		 WHERE VD.lote_cod_lote = L.cod_lote
																		LIMIT 1) = 1
												 THEN PL.descripcion
												 ELSE CASE WHEN (SELECT DISTINCT VD.despacho_id_modalidadenvio
																					 FROM despachos_primertramo_validaciondatos VD
																					WHERE VD.lote_cod_lote = L.cod_lote
																				 LIMIT 1) = 2
														  THEN CASE WHEN PL.Id = 4
																	 THEN (SELECT DISTINCT PM.razon_social
																					 FROM despachos_primertramo_validaciondatos VD
																								INNER JOIN tb_clientes PM ON VD.lote_id_proveedorminero = PM.Id
																					WHERE VD.lote_cod_lote = L.cod_lote
																				 LIMIT 1)
																	 ELSE PL.descripcion END
															ELSE (SELECT descripcion
																			FROM tbconfig_plantas
																		 WHERE Id = 11)
															END
												 END AS DES_REMITENTE,
 
												 (SELECT DISTINCT TM.descripcion
														FROM despachos_primertramo_validaciondatos VD
																 INNER JOIN tbconfig_tipomineral TM ON VD.lote_id_tipomineral = TM.Id
													 WHERE VD.lote_cod_lote = L.cod_lote
													LIMIT 1) AS TIPO_MINERAL,
 
												 L.observacion,
												 (L.peso_tara * 1000) AS peso_tara,
												 L.peso_tara_fechahoraregistro,
												 L.peso_tara_usuarioregistro,
												 (L.peso_bruto * 1000) AS peso_bruto,
												 L.peso_bruto_fechahoraregistro,
												 L.peso_bruto_usuarioregistro,
												 L.peso_neto,
									       PL.nombre_comercial AS DESTINO,
									       P.id_planta

												 FROM controlingresovehiculo I
															 INNER JOIN despachos_segundotramo_distribucion_unidades U ON I.dFechaIngreso >= U.fecha_ingresoplanta
															 INNER JOIN despachos_segundotramo_programacion P ON U.id_programacion = P.Id
															INNER JOIN transporte T ON U.id_unidad = T.id_transporte
															  AND I.placa = T.cplaca
															INNER JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
															INNER JOIN despachos_segundotramo_distribucion_lotes L ON U.Id = L.id_distribucionunidad
															INNER JOIN tbconfig_tipocarga TC ON L.id_tipocarga = TC.Id
															LEFT JOIN tb_clientes ET ON T.id_Transportista = ET.Id
															LEFT JOIN tbconfig_conductores CD ON I.id_choferes = CD.Id
															LEFT JOIN tbconfig_plantas PL ON P.id_planta = PL.Id
															LEFT JOIN catalogolotes LT ON L.cod_lote = LT.ccod_Lote
									 WHERE MD5(L.Id) = '".$id_md5."'";

		if ($res_balanza = mysqli_query($enlace, $q_balanza)){
      if (mysqli_num_rows($res_balanza) > 0) {
        while($row_balanza = mysqli_fetch_array($res_balanza)){
        	$cod_lote = $row_balanza["cod_lote"];
					$num_parte = $row_balanza["num_parte"];
					$num_ticket = $row_balanza["num_ticketbalanza"];
					$placa = $row_balanza["placa"];
					$placa2 = $row_balanza["placa2"];
					$ruc_empresatransporte = $row_balanza["RUC_EMPRESATRANSPORTE"];
					$des_empresastransporte = $row_balanza["EMPRESA_TRANSPORTE"];
					$tipo_vehiculo = $row_balanza["TIPO_VEHICULO"];
					$dni_conductor = $row_balanza["DNI_CONDUCTOR"];
					$des_conductor = $row_balanza["DES_CONDUCTOR"];
					$cod_tipocarga = $row_balanza["COD_TIPOCARGA"];
					$des_tipocarga = $row_balanza["DES_TIPOCARGA"];
					$num_bigbag = $row_balanza["num_bigbag"];
					$ruc_remitente = $row_balanza["RUC_REMITENTE"];
					$des_remitente = $row_balanza["DES_REMITENTE"];
					$tipo_mineral = $row_balanza["TIPO_MINERAL"];
					$observacion = $row_balanza["observacion"];
					$destino = $row_balanza["DESTINO"];
					$peso_tara = $row_balanza["peso_tara"];
					$peso_tara_fechahoraregistro = $row_balanza["peso_tara_fechahoraregistro"];
					$peso_tara_usuarioregistro = $row_balanza["peso_tara_usuarioregistro"];
					$peso_bruto = $row_balanza["peso_bruto"];
					$peso_bruto_fechahoraregistro = $row_balanza["peso_bruto_fechahoraregistro"];
					$peso_bruto_usuarioregistro = $row_balanza["peso_bruto_usuarioregistro"];
					$peso_neto = $row_balanza["peso_neto"];
					$id_planta = $row_balanza["id_planta"];

					// Genera el Código QR
						$url = $url_lims.'print_ticketdespacho.php?x='.$id_md5;

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
					        <title>Ticket: '.$num_ticket.'</title>
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
						    	<div style="width: 100%; margin-left: 0px; margin-top: '.(($_SESSION['cod_rol'] != 4) ? '50px' : '0px').' margin-right: 0px;">
										<div class="row" style="margin-top: -30px; text-align: center;">
											TICKET DE BALANZA: <label style="font-family: AgencyFBb;">'.$num_ticket.'</label>
										</div>

										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -8px; text-align: center; font-family: AgencyFBb; font-size: 20px;">
											LOTE: '.$cod_lote.((strlen($num_parte) == 0) ? '' : ' - PARTE N° '.$num_parte).'
										</div>

										<div class="row" style="margin-top: -10px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 7px; text-align: left; margin-bottom: -10px;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%; ">
														<label style="font-family: AgencyFBb;">Placa 1: </label>
														<label>'.$placa.'</label>
													</td>

													<td style="width: 50%; text-align: right;">
														<div style="margin-right: 7px;">
															<label style="font-family: AgencyFBb;">SEGUNDO TRAMO</label>
														</div>
													</td>
												</tr>
											</table>
										</div>';

		if (strlen($placa2) > 0){
			$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
												<label style="font-family: AgencyFBb;">Placa 2: </label>
												<label>'.$placa2.'</label>
											</div>';
		}

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Emp. de Transporte: </label>
											<label>'.$ruc_empresatransporte.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.$des_empresastransporte.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Vehículo: </label>
											<label>'.$tipo_vehiculo.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Conductor: </label>
											<label>'.$dni_conductor.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.$des_conductor.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Remitente: </label>
											<label>'.$ruc_remitente.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.mb_strtoupper($des_remitente).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Mineral: </label>
											<label>'.((strlen(trim($tipo_mineral)) == 0) ? '---' : $tipo_mineral).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Presentación: </label>
											<label>'.((strlen(trim($des_tipocarga)) == 0) ? '---' : $des_tipocarga).'</label>';

											if ($cod_tipocarga == 5){
												$html .= '				<label style="font-family: AgencyFBb; margin-left: 5px;">Cant.: </label>
																					<label>'.((strlen(trim($num_bigbag)) == 0) ? '---' : $num_bigbag).'</label>';
											}

		// $html .= '			</div>

		// 								<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
		// 									<label style="font-family: AgencyFBb;">Destino: </label>
		// 									<label>'.((strlen(trim($destino)) == 0) ? '---' : $destino).'</label>
		// 								</div>

		$html .= '			</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Observación: </label>';

										// Setea Observación
											$observacion_x = trim($observacion);

											if ($id_planta == 15){
												$cmh_codigoguias = '';

												$q_datos = "SELECT IFNULL(PD.cmh_codigoguias, '') AS CMH_CODIGOGUIAS,

																					 (SELECT COUNT(DL_x.Id) AS _COUNT
																					 		FROM despachos_segundotramo_distribucion_lotes DL_x
																					 	 WHERE DL_x.cod_lote = PD.cod_lote) AS TOTAL_PARTES

																			FROM despachos_segundotramo_programacion_detalle PD
																		 WHERE PD.cod_lote = '".$cod_lote."'";

												if ($res_datos = mysqli_query($enlace, $q_datos)) {
													if (mysqli_num_rows($res_datos) > 0) {
														while ($row_datos = mysqli_fetch_array($res_datos)) {
															$cmh_codigoguias = $row_datos["CMH_CODIGOGUIAS"];
															$total_partes = $row_datos["TOTAL_PARTES"];

															$cmh_codigoguias = $cmh_codigoguias.(($total_partes > 1) ? ' ('.$num_parte.'/'.$total_partes.')' : '');
														}
													}
												}

												if (strlen($cmh_codigoguias) > 0){
													$observacion_x .= ((strlen($observacion_x) == 0) ? '' : ' / ').$cmh_codigoguias;
												}
											}
											else{
												// Agregando Código de Planta para Colibrí
													$codigo_planta_x = '';

													$q_codigoplanta = "SELECT codigo_planta
																							 FROM despachos_segundotramo_programacion_detalle
																						  WHERE cod_lote = '".$cod_lote."'";

													if ($res_codigoplanta = mysqli_query($enlace, $q_codigoplanta)){
											      if (mysqli_num_rows($res_codigoplanta) > 0) {
											        while($row_codigoplanta = mysqli_fetch_array($res_codigoplanta)){
										        		$codigo_planta_x = trim($row_codigoplanta["codigo_planta"]);
											        }
											      }
											    }

											    if (strlen($codigo_planta_x) > 0){
											    	$observacion_x .= ' / '.$codigo_planta_x;
											    }
											}

		$html .= '				<label>'.$observacion_x.'</label>

										</div>

										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; text-align: center; font-family: AgencyFBb;">
											PESAJE
										</div>

										<div class="row" style="margin-top: -7px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; margin-right: 10px; text-align: left;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO INICIAL: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>'.number_format($peso_tara, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: '.substr($peso_tara_fechahoraregistro, 0, 10).'
														</div>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO FINAL: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>'.number_format($peso_bruto, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: '.substr($peso_bruto_fechahoraregistro, 0, 10).'
														</div>
													</td>
												</tr>
											</table>
										</div>

										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; text-align: center; font-family: AgencyFBb;">
											RESUMEN
										</div>

										<div class="row" style="margin-top: -7px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; margin-right: 10px; text-align: left;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO BRUTO: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>'.number_format($peso_bruto, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">TARA: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>'.number_format($peso_tara, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO NETO: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>'.number_format((abs($peso_bruto - $peso_tara)), 0, '.', ',').' Kg</label>
													</td>
												</tr>
											</table>
										</div>';

	$html .= '			
								</div>
							</body>
					  </html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml(utf8_decode($html), 'UTF-8');

	// Solo si es balanza aumenta el Height
		if ($_SESSION['cod_rol'] == 4){
			$document -> setPaper(array(0, 0, 190, 6000));
		}
		else{
			$document -> setPaper(array(0, 0, 190, 700));
		}

	$document -> render();
	$document -> stream("Recibo - Ticket ".$num_ticket, array('Attachment' => 0));

?>
