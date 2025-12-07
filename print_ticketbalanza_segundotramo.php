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
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_ticketbalanza_segundotramo.php')).'images/';

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
		$ticket_balanza = '';
		$cod_lote = '';
		$placa_1 = '';
		$placa_2 = '';
		$transportista_documento = '';
		$transportista_razonsocial = '';
		$tipo_vehiculo = '';
		$conductor_documento = '';
		$conductor_nombres = '';
		$tipo_carga = '';
		$zona_origen = '';
		$id_proveedor_minero = '';
		$proveedor_minero = '';
		$encargado_muestra = '';
		$producto = '';
		$tipo_mineral = '';
		$observacion = '';
		$peso_inicial = '';
		$pesoinicial_fechahora = '';
		$pesoinicial_observacion = '';
		$peso_final = '';
		$pesofinal_fechahora = '';
		$pesofinal_observacion = '';
		$guia_remitente = '';
		$guia_transportista = '';

		$m = 1;

		$q_datos = "SELECT DL.Id,
											 MD5(DL.Id) AS ID_MD5,
											 PD.cod_lote,
											 /*DL.num_parte,*/

											 (CASE WHEN (SELECT COUNT(DL_x.Id)
																	   FROM despachos_segundotramo_distribucion_lotes DL_x
																	  WHERE DL_x.is_complemento_de = DL.Id) > 0
										  	  THEN 1
										    ELSE CASE WHEN DL.is_complemento = 1
										   				 THEN (SELECT CC_x.lote_item
																			 FROM consolidado_lotes_cierrecontable CC_x
																			WHERE CC_x.id_registro = DL.Id
														   					AND CC_x.id_tipoingreso = 2
														   					AND lote_item <> 1)
										   			 ELSE DL.num_parte END END) AS num_parte,

											 CL.num_ticketbalanza,
											 -- DATE(V.lote_pesoinicial_fechahoraregistro) AS FECHA_INGRESOBALANZA,
											 IFNULL(DATE(DL.peso_bruto_fechahoraregistro), DL.guias_fecha) AS FECHA_INGRESOBALANZA,
											 DL.guias_placa1 AS PLACA1,
											 DL.guias_placa2 AS PLACA2,
											 PD.codigo_planta,
											 DL.guiaremitente_serie,
											 DL.guiaremitente_numero,
											 DL.guiatransportista_serie,
											 DL.guiatransportista_numero,
											 TR.documento AS TRANSPORTISTA_RUC,
											 TR.razon_social AS TRANSPORTISTA_RAZONSOCIAL,
											 TV.descripcion AS TIPO_VEHICULO,
											 CH.licencia_conducir AS CONDUCTOR_DNI,
											 CH.nombres AS CONDUCTOR_NOMBRES,
											 DL.id_tipocarga,
											 TC.descripcion AS TIPO_CARGA,
											 DL.num_bigbag,
											 'PLANTA HUANCACO' AS ZONA_ORIGEN,
											 RE.ruc AS REMITENTE_RUC,
											 RE.razon_social AS REMITENTE_RAZONSOCIAL,
											 PR.descripcion AS PRODUCTO,
											 TM.descripcion AS TIPO_MINERAL,
											 DL.observacion,
											 DL.peso_bruto_fechahoraregistro,
											 DL.peso_tara_fechahoraregistro,
											 DL.peso_bruto,
											 DL.peso_tara,
											 DL.peso_neto,
											 DL.is_cerradolote,
											 DL.cerradolote_fechahoraregistro,
											 DL.cerradolote_usuarioregistro,
											 DL.is_complemento,
											 IFNULL(DL.cierrelote_complementotara, 0) AS COMPLEMENTO_TARA,
											 
							         (SELECT CASE WHEN COUNT(DL_x.Id) > 0
							         				 	 THEN 1
							         				 ELSE 0 END
							         		FROM despachos_segundotramo_distribucion_lotes DL_x
							         	 WHERE DL_x.is_complemento_de = DL.Id) AS TIENE_COMPLEMENTO,
											 
							         (SELECT SUM(DL_x.peso_distribuido)
												  FROM despachos_segundotramo_distribucion_lotes DL_x
												 WHERE DL_x.Id IN (SELECT DL_x2.Id
												 										 FROM despachos_segundotramo_distribucion_lotes DL_x2
												 									  WHERE DL_x2.is_complemento_de = DL.Id)) AS COMPLEMENTO_PESODISTRIBUIDO,

							         (SELECT SUM(DL_x.peso_distribuido)
												  FROM despachos_segundotramo_distribucion_lotes DL_x
												 WHERE DL_x.Id = DL.Id) AS COMPLEMENTO_PESODISTRIBUIDO2

									FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
											 INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
											 INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
											   AND PD.id_validaciondatos = DL.id_validaciondatos
											 INNER JOIN transporte UN ON U.id_unidad = UN.id_transporte
											 LEFT JOIN transporte UN2 ON U.id_unidad2 = UN2.id_transporte
											 INNER JOIN tb_clientes TR ON UN.id_Transportista = TR.Id
											 INNER JOIN tbconfig_plantas PL ON PD.id_planta = PL.Id
											 LEFT JOIN tbconfig_modalidadenvio ME ON PD.id_modalidadenvio = ME.Id
											 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
											 INNER JOIN tbconfig_tipovehiculo TV ON UN.id_tipovehiculo = TV.Id
											 INNER JOIN despachos_primertramo_validaciondatos V ON PD.id_validaciondatos = V.Id
											 INNER JOIN tbconfig_conductores CH ON DL.guias_idchofer = CH.Id
											 INNER JOIN tbconfig_remitentessegundotramo RE ON /*DL.guias_iddestino = RE.id_destino
											   AND*/ DL.guias_idmodalidadenvio = RE.id_modalidadenvio
											 INNER JOIN tbconfig_producto PR ON V.lote_id_producto = PR.Id
											 LEFT JOIN tbconfig_tipomineral TM ON V.lote_id_tipomineral = TM.Id
											 LEFT JOIN consolidado_lotes_cierrecontable CL ON DL.Id = CL.id_registro
											   AND CL.id_tipoingreso = 2
								 WHERE MD5(DL.Id) = '".$id_md5."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$ticket_balanza = $row_datos["num_ticketbalanza"];
					$cod_lote = $row_datos["cod_lote"];
					$num_parte = $row_datos["num_parte"];
					$placa_1 = $row_datos["PLACA1"];
					$codigo_planta = $row_datos["codigo_planta"];
					$placa_2 = $row_datos["PLACA2"];
					$transportista_documento = $row_datos["TRANSPORTISTA_RUC"];
					$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
					$tipo_vehiculo = $row_datos["TIPO_VEHICULO"];
					$conductor_documento = $row_datos["CONDUCTOR_DNI"];
					$conductor_nombres = $row_datos["CONDUCTOR_NOMBRES"];
					$id_tipocarga = $row_datos["id_tipocarga"];
					$tipo_carga = $row_datos["TIPO_CARGA"];
					$num_bigbag = $row_datos["num_bigbag"];
					$zona_origen = 'PLANTA HUANCHACO';
					$remitente_ruc = $row_datos["REMITENTE_RUC"];
					$remitente_razonsocial = $row_datos["REMITENTE_RAZONSOCIAL"];
					$producto = $row_datos["PRODUCTO"];
					$tipo_mineral = $row_datos["TIPO_MATERIAL"];
					$observacion = $row_datos["observacion"];
					$guia_remitente = $row_datos["guiaremitente_serie"].'-'.$row_datos["guiaremitente_numero"];
					$guia_transportista = $row_datos["guiatransportista_serie"].'-'.$row_datos["guiatransportista_numero"];

					$peso_inicial = $row_datos["peso_bruto"] * 1000;
					// $pesoinicial_fechahora = $row_datos["lote_pesoinicial_fechahoraregistro"];
					// // $pesoinicial_observacion = $row_datos["pesoinicial_observacion"];
					$pesoinicial_fechahora = $row_datos["FECHA_INGRESOBALANZA"];
					$peso_final = $row_datos["peso_tara"] * 1000;
					// $pesofinal_fechahora = $row_datos["peso_tara_fechahoraregistro"];
					// // $pesofinal_observacion = $row_datos["pesofinal_observacion"];
					$pesofinal_fechahora = $pesoinicial_fechahora;

					// Obteniendo Peso Neto
						$peso_neto = $row_datos["peso_neto"] * 1000;

						if ($row_datos["TIENE_COMPLEMENTO"] == 1){
							$peso_neto = $peso_neto - $row_datos["COMPLEMENTO_PESODISTRIBUIDO"] * 1000;
						}

						if ($row_datos["is_complemento"] == 1){
							$peso_neto = abs($peso_neto - ($row_datos["COMPLEMENTO_PESODISTRIBUIDO2"] * 1000));
						}

					// Obteniendo Peso Tara
						$peso_final = $row_datos["peso_tara"] * 1000;

						if ($row_datos["TIENE_COMPLEMENTO"] == 1){
							$peso_final = ($row_datos["peso_bruto"] * 1000) - $peso_neto;
						}

						if ($row_datos["is_complemento"] == 1){
							$peso_final = $row_datos["COMPLEMENTO_TARA"];
						}

					// Genera el Código QR
						$url = $url_lims.'print_ticketbalanza.php?x='.$id_md5;

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
					        <title>Ticket: '.$ticket_balanza.'</title>
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
										<div class="row" style="text-align: center;">
											<img src="'.$ruta_images.'logo_oppm.png" width="130mm"/>
										</div>
 
										<div class="row" style="text-align: center;">
											<label>RUC: 20603892446</label>
										</div>

										<div class="row" style="margin-top: 5px; text-align: center; font-size: 20px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #BFBFBF;">
											TICKET DE BALANZA
										</div>

										<div class="row" style="margin-top: -5px; text-align: center;">
											<label style="font-family: AgencyFBb;">'.$ticket_balanza.'</label>
										</div>

										<div class="row" style="text-align: center; margin-top: -10px;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 7px; text-align: left; margin-bottom: -10px;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%; ">
														<label style="font-family: AgencyFBb;">Lote: </label>
														<label>'.$cod_lote.'</label>
													</td>

													<td style="width: 50%; text-align: right;">
														<div style="margin-right: 7px;">
															<label style="font-family: AgencyFBb;">T-2</label>
														</div>
													</td>
												</tr>';

												if (strlen($num_parte) > 0){
													$html .= '<tr>
																			<td colspan="2" style="width: 50%; ">
																				<div style="margin-top: -10px;">
																					<label style="font-family: AgencyFBb;">Parte: </label>
																					<label>'.$num_parte.'</label>
																				</div>
																			</td>
																		</tr>';
												}

						$html .= '</table>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 7px; text-align: left; margin-bottom: -10px;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%; ">
														<label style="font-family: AgencyFBb;">Placa 1: </label>
														<label>'.$placa_1.'</label>
													</td>
												</tr>
											</table>
										</div>';

		if (strlen($placa_2) > 0){
			$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
												<label style="font-family: AgencyFBb;">Placa 2: </label>
												<label>'.$placa_2.'</label>
											</div>';
		}

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Emp. de Transporte: </label>
											<label>'.$transportista_documento.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.$transportista_razonsocial.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Vehículo: </label>
											<label>'.$tipo_vehiculo.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Conductor: </label>
											<label>'.$conductor_documento.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.$conductor_nombres.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Zona Origen: </label>
											<label>'.((strlen(trim($zona_origen)) == 0) ? '---' : $zona_origen).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Remitente: </label>
											<label>'.((strlen(trim($remitente_ruc)) == 0) ? '---' : $remitente_ruc).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.$remitente_razonsocial.'</label>
										</div>';

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Guía Remitente: </label>
											<label>'.((strlen(trim($guia_remitente)) == 0) ? '---' : $guia_remitente).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Guía Transportista: </label>
											<label>'.((strlen(trim($guia_transportista)) == 0) ? '---' : $guia_transportista).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Producto: </label>
											<label>'.((strlen(trim($producto)) == 0) ? '---' : $producto).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Carga: </label>
											<label>'.$tipo_carga.'</label>
										</div>';

										if ($id_tipocarga == 5){
											$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
																				<label style="font-family: AgencyFBb;">Cant. Bigbag: </label>
																				<label>'.$num_bigbag.'</label>
																			</div>';
										}

		// $html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
		// 									<label style="font-family: AgencyFBb;">Observación: </label>
		// 									<label>'.((strlen(trim($observacion)) == 0) ? '---' : $observacion);

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Observación: </label>
											<label>'.$observacion;

										if (strlen($codigo_planta) > 0){
											$html .= ((strlen($observacion) > 0) ? ' / ' : '').$codigo_planta.'</label>
																</div>';
										}

		$html .= '			<div class="row" style="text-align: center;">
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
														<label>'.number_format($peso_inicial, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: '.substr($pesoinicial_fechahora, 0, 10).'
														</div>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO FINAL: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>'.number_format($peso_final, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: '.substr($pesofinal_fechahora, 0, 10).'
														</div>
													</td>
												</tr>
											</table>
										</div>

										<div class="row" style="margin-top: -5px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; margin-right: 10px; text-align: left;">
											<table style="width: 100%;">
												<tr style="font-size: 16px;">
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO NETO: </label>
													</td>

													<td style="width: 50%; text-align: right; font-family: AgencyFBb;">
														<label>'.number_format((abs($peso_neto)), 0, '.', ',').' Kg</label>
													</td>
												</tr>
											</table>
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
		if ($_SESSION['cod_rol'] == 4){
			$document -> setPaper(array(0, 0, 190, 6000));
		}
		else{
			$document -> setPaper(array(0, 0, 190, 700));
		}

	$document -> render();
	$document -> stream("Recibo - Ticket ".$ticket_balanza, array('Attachment' => 0));

?>
