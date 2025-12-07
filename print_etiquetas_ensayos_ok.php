<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('libs/barcode.php');

	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	// Recuperando parámetros
		$id_md5 = $_GET["x"];
		$id_detalle = $_GET["d"];

	// Ruta imágenes
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_etiquetas_ensayos_ok.php')).'images/';

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
    $fecha_recepcion = '';
    $hora_recepcion = '';
    $cod_interno = '';
    $CI = '';
    $nombre_muestra = '';
    $estado_muestra = '';
    $envase_muestra = '';

    $q_datos = "SELECT D.Id,
    									 /*IFNULL(RI.pagado_fechahoraregistro, C.fechahora_recepcion) AS fechahora_recepcion,*/
    									 C.instruccion_fechahoraregistro,
											 D.cod_interno,
											 CONCAT(CI.dia, CI.mes, CI.anho, '-', D.cod_interno) AS CI,
									     D.nombre_muestra,
									     CONCAT(TM.descripcion, ' ', EM.descripcion) AS ESTADO_MUESTRA,
									     VM.descripcion AS ENVASE_MUESTRA
									FROM recepcion_ensayos_detalle D
											 INNER JOIN recepcion_codigosinternos_lq CI ON D.id_codinterno = CI.Id
										   INNER JOIN recepcion_ensayos_cabecera C ON D.id_cabecera = C.Id
										   INNER JOIN tbconfig_tiposmuestra TM ON D.cod_tipomuestra = TM.Id
										   INNER JOIN tbconfig_estadosmuestra EM ON D.cod_estadomuestra = EM.Id
										   INNER JOIN tbconfig_envasesmuestra VM ON D.cod_envasemuestra = VM.Id
										   LEFT JOIN recepcion_instruccion RI ON C.Id = RI.id_cabecera
								 WHERE md5(D.id_cabecera) = '".$id_md5."'";

		if ($id_detalle != 0){
			$q_datos .= "   AND D.Id = ".$id_detalle;
		}

		$q_datos .= " ORDER BY D.Id";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
			if (mysqli_num_rows($res_datos) > 0) {
				while($row_datos = mysqli_fetch_array($res_datos)){
					// Recuperando valores
						$fecha_recepcion = explode('-', explode(' ', $row_datos["instruccion_fechahoraregistro"])[0])[2].'/'.
															 explode('-', explode(' ', $row_datos["instruccion_fechahoraregistro"])[0])[1].'/'.
															 explode('-', explode(' ', $row_datos["instruccion_fechahoraregistro"])[0])[0];
						$hora_recepcion = substr(explode(' ', $row_datos["instruccion_fechahoraregistro"])[1], 0, 5);
						$cod_interno = $row_datos["cod_interno"];
						$cod_internoA = $row_datos["cod_interno"].'A';
						$cod_internoB = $row_datos["cod_interno"].'B';
						$cod_internox = substr($row_datos["cod_interno"], 7, 4);
						$CI = $row_datos["CI"];
				    $nombre_muestra = strtoupper(trim($row_datos["nombre_muestra"]));
				    $estado_muestra = strtoupper(trim($row_datos["ESTADO_MUESTRA"]));
				    $envase_muestra = strtoupper(trim($row_datos["ENVASE_MUESTRA"]));
				    $tiene_h2o = 0;

				  // Obteniendo análisis
						$analisis = '';

						// 1. Obtiene análisis de Precios Generales
			        $q_analisis = "SELECT CONCAT(EA.abv, CASE WHEN LENGTH(IFNULL(C.abv, '')) > 0 THEN CONCAT(' ', C.abv) ELSE '' END) AS abv,
			        											EA.is_humedad
			                         FROM recepcion_ensayos_analisis REA
			                              INNER JOIN tb_ensayos_analisisclasificaciones_detalle ACD ON REA.cod_analisis = ACD.Id
			                              INNER JOIN tb_ensayos_analisisclasificaciones C ON ACD.cod_clasificacion = C.Id
			                              INNER JOIN tb_ensayos_analisis EA ON ACD.cod_analisis = EA.Id
			                        WHERE REA.id_detalle = ".$row_datos["Id"]."
			                          AND is_paquete = 0
			                          AND is_paquetecliente = 0
			                       ORDER BY EA.orden";

							if ($res_analisis = mysqli_query($enlace, $q_analisis)){
					      if (mysqli_num_rows($res_analisis) > 0) {
					        while($row_analisis = mysqli_fetch_array($res_analisis)){
					        	$analisis .= '		        '.$row_analisis["abv"].', ';

					        	if ($row_analisis["is_humedad"] == 1){
					        		$tiene_h2o = 1;
					        	}
					        }
					      }
					    }

					  // 2. Obtiene análisis de Paquetes Generales
			        $q_paquetes = "SELECT REA.cod_analisis
			                         FROM recepcion_ensayos_analisis REA
			                              INNER JOIN tb_ensayos_analisisclasificaciones_paquetes P ON REA.cod_analisis = P.Id
			                              INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
			                        WHERE REA.id_detalle = ".$row_datos["Id"]."
			                          AND REA.is_paquete = 1";

			        if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
			          if (mysqli_num_rows($res_paquetes) > 0) {
			            while($row_paquetes = mysqli_fetch_array($res_paquetes)){
			              // Armando la descripción de análisis por paquete
			                $abv = '';

			                $q_analisispaquete = "SELECT A.abv,
			        																		 A.is_humedad
			                                       FROM tb_ensayos_analisisclasificaciones_paquetes_detalle D
			                                            INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
			                                      WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

			                if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
			                  if (mysqli_num_rows($res_analisispaquete) > 0) {
			                    while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
			                      $abv .= $row_analisispaquete["abv"].', ';

									        	if ($row_analisispaquete["is_humedad"] == 1){
									        		$tiene_h2o = 1;
									        	}
			                    }
			                  }
			                }

			                if (strlen($abv) > 0){
			                  $abv = substr($abv, 0, -2);
			                }

			              // Creando el query final
			                $q_analisis = "SELECT '".$abv."' AS abv";

			                if ($res_analisis = mysqli_query($enlace, $q_analisis)){
									      if (mysqli_num_rows($res_analisis) > 0) {
									        while($row_analisis = mysqli_fetch_array($res_analisis)){
									        	$analisis .= $row_analisis["abv"].', ';
									        }
									      }
									    }
								  }
								}
							}

						// 3. Obtiene análisis de Paquetes de Clientes
			        $q_paquetes = "SELECT REA.cod_analisis
			                         FROM recepcion_ensayos_analisis REA
			                              INNER JOIN tb_ensayos_analisisclasificaciones_paquetesclientes P ON REA.cod_analisis = P.Id
			                              INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
			                        WHERE REA.id_detalle = ".$row_datos["Id"]."
			                          AND REA.is_paquetecliente = 1";

			        if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
			          if (mysqli_num_rows($res_paquetes) > 0) {
			            while($row_paquetes = mysqli_fetch_array($res_paquetes)){
			              // Armando la descripción de análisis por paquete
			                $abv = '';

			                $q_analisispaquete = "SELECT A.abv,
			        																		 A.is_humedad
			                                       FROM tb_ensayos_analisisclasificaciones_paquetesclientes_detalle D
			                                            INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
			                                      WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

			                if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
			                  if (mysqli_num_rows($res_analisispaquete) > 0) {
			                    while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
			                      $abv .= $row_analisispaquete["abv"].', ';

									        	if ($row_analisispaquete["is_humedad"] == 1){
									        		$tiene_h2o = 1;
									        	}
			                    }
			                  }
			                }

			                if (strlen($abv) > 0){
			                  $abv = substr($abv, 0, -2);
			                }

			              // Creando el query final
			                $q_analisis = "SELECT '".$abv."' AS abv";

			                if ($res_analisis = mysqli_query($enlace, $q_analisis)){
			                  if (mysqli_num_rows($res_analisis) > 0) {
			                    while($row_analisis = mysqli_fetch_array($res_analisis)){
									        	$analisis .= $row_analisis["abv"].', ';
			                    }
			                  }
			                }
			            }
			          }
			        }

			        if (strlen($analisis) > 0){
					    	$analisis = substr($analisis, 0, -2);
					    }

					// Genera el códigos de barra
						barcode( 'images/bc/'.$cod_interno.'.png', $cod_interno, 20, 'horizontal', 'code128', false );

						if ($tiene_h2o == 1){
							barcode( 'images/bc/'.$cod_internoA.'.png', $cod_internoA, 20, 'horizontal', 'code128', false );
							barcode( 'images/bc/'.$cod_internoB.'.png', $cod_internoB, 20, 'horizontal', 'code128', false );
						}

					// Primera línea
						$html .= '<div style="width: 102mm; height: 24mm; margin: 0px; pading: 0px;">
												<table style="width: 101.4mm; border-spacing: 0; margin: 0px;">
													<tr>
														<td style="border-spacing: 0; width: 50.7mm; margin: 0px;">
															<div style="margin-left: -5px; margin-right: 12px; width: 100%; height: 24mm;">
																<div style="width: 100%; height: 23mm; margin: -2px;">
																	<table style="min-width: 100%; border-spacing: 0">
																		<tr style="font-size: 10px; font-family: AgencyFBb;">
																			<td style="text-align: center;">
																				<div style="width: 15mm; height: 10mm; margin-left: -5px;">
																					<div class="row">
																						> > > >
																					</div>

																					<div class="row" style="margin-top: -7px;">
																						CLIENTE
																					</div>

																					<div class="row" style="margin-top: -8px;">
																						> > > >
																					</div>
																				</div>
																			</td>

																			<td style="text-align: center;">
																				<div style="width: 20mm; height: 15mm; margin-top: -10px; font-size: 40px; text-align: center;">
																					'.$cod_internox.'
																				</div>
																			</td>

																			<td style="text-align: right;">
																				<div style="width: 12mm; height: 10mm; margin-right: 7px; margin-top: 5px;">
																					<div style="width: 100%; font-size: 9px;">
																						'.$fecha_recepcion.'
																					</div>

																					<div style="margin-top: -5px; font-size: 9px;">
																						'.$hora_recepcion.'
																					</div>
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 11px; font-weight: bold;">
																			<td colspan="3">
																				<div style="width: 100%; margin-top: 0px; text-align: center;">
																					<img src="'.$ruta_images.'bc/'.$cod_interno.'.png" style="height: 25px; width: 90%;"/>
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 14px; font-family: AgencyFBb;">
																			<td colspan="3 style="text-align: center;">
																				<div style="text-align: center; margin-top: -5px; width: 100%">
																					Labmin Perú S.A.C.
																				</div>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
														</td>

														<td style="border-spacing: 0; width: 49mm; margin: 0px; ">
															<div style="margin-left: 0px; width: 100%; height: 24mm;">
																<div style="width: 49mm; height: 23mm; margin: -2px;">
																	<table style="width: 100%; border-spacing: 0">
																		<tr style="font-size: 14px; font-family: AgencyFBb;">
																			<td style="width: 20mm">
																				<div style="margin-top: -5px; margin-left: 5px;">
																					Labmin Perú S.A.C.
																				</div>
																			</td>

																			<td style="width: 10mm; text-align: center;">
																				<div style="font-size: 30px; text-align: center; margin-top: -10px;">
																					'.$cod_internox.'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 11px; font-family: AgencyFBb;">
																			<td style="width: 20mm">
																				<div style="width: 100%; margin-left: 5px; margin-top: -20px;">
																						'.$fecha_recepcion.' '.$hora_recepcion.'
																				</div>
																			</td>

																			<td style="width: 10mm">
																				<div style="text-align: center; margin-top: -13px;">
																					SOBRE
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 12px; font-family: AgencyFBb;">
																			<td colspan="2">
																				<div style="margin-top: -12px; margin-left: 5px;">
																					'.substr(trim($nombre_muestra), 0, 34).'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 12px; font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 10px;">
																				<div style="margin-top: -8px; margin-left: 5px;">
																					'.substr(trim($estado_muestra), 0, 34).'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 12px; font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 10px;">
																				<div style="margin-top: -8px; margin-left: 5px;">
																					'.substr(trim($envase_muestra), 0, 34).'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 9px;">
																				<div style="margin-top: -6px; margin-left: 5px;">
																					'.substr(trim($analisis), 0, 100).'
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

					// Segunda línea
						$html .= '<div style="width: 102mm; height: 24mm; margin: 0px; pading: 0px;">
												<table style="width: 101.4mm; border-spacing: 0; margin: 0px;">
													<tr>
														<td style="border-spacing: 0; width: 49mm; margin: 0px; ">
															<div style="margin-left: 0px; width: 100%; height: 24mm;">
																<div style="width: 49mm; height: 23mm; margin: -2px;">
																	<table style="width: 100%; border-spacing: 0">
																		<tr style="font-size: 14px; font-family: AgencyFBb;">
																			<td style="width: 20mm">
																				<div style="margin-top: -5px; margin-left: 0px;">
																					Labmin Perú S.A.C.
																				</div>
																			</td>

																			<td style="width: 10mm; text-align: center;">
																				<div style="font-size: 30px; text-align: center; margin-top: -10px;">
																					'.$cod_internox.'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 11px; font-family: AgencyFBb;">
																			<td style="width: 20mm">
																				<div style="width: 100%; margin-left: 0px; margin-top: -20px;">
																						'.$fecha_recepcion.' '.$hora_recepcion.'
																				</div>
																			</td>

																			<td style="width: 10mm">
																				<div style="text-align: center; margin-top: -13px;">
																					RECHAZO
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 12px; font-family: AgencyFBb;">
																			<td colspan="2">
																				<div style="margin-top: -12px; margin-left: 0px;">
																					'.substr(trim($nombre_muestra), 0, 34).'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 12px; font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 10px;">
																				<div style="margin-top: -8px; margin-left: 0px;">
																					'.substr(trim($estado_muestra), 0, 34).'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 12px; font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 10px;">
																				<div style="margin-top: -8px; margin-left: 0px;">
																					'.substr(trim($envase_muestra), 0, 34).'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 9px;">
																				<div style="margin-top: -6px; margin-left: 0px;">
																					'.substr(trim($analisis), 0, 100).'
																				</div>
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
														</td>

														<td style="border-spacing: 0; width: 49mm; margin: 0px; ">
															<div style="margin-left: 0px; width: 100%; height: 24mm;">
																<div style="width: 49mm; height: 23mm; margin: -2px;">
																	<table style="width: 100%; border-spacing: 0">
																		<tr style="font-size: 14px; font-family: AgencyFBb;">
																			<td style="width: 30mm">
																				<div style="margin-top: -5px; margin-left: 10px;">
																					Labmin Perú S.A.C.
																				</div>
																			</td>

																			<td style="width: 10mm; text-align: center;">
																				<div style="font-size: 30px; text-align: center; margin-top: -10px;">
																					'.$cod_internox.'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 11px; font-family: AgencyFBb;">
																			<td style="width: 30mm">
																				<div style="width: 100%; margin-left: 10px; margin-top: -20px;">
																						'.$fecha_recepcion.' '.$hora_recepcion.'
																				</div>
																			</td>

																			<td style="width: 10mm">
																				
																			</td>
																		</tr>

																		<tr style="font-size: 11px; font-weight: bold;">
																			<td colspan="3">
																				<div style="width: 100%; margin-top: -2px; text-align: center;">
																					<img src="'.$ruta_images.'bc/'.$cod_interno.'.png" style="height: 25px; width: 90%;"/>
																				</div>
																			</td>
																		</tr>

																		<tr style="font-size: 12px; font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 10px; text-align: center">
																				<div style="margin-top: -5px; margin-left: 10px;">
																					'.substr(trim($estado_muestra), 0, 34).'
																				</div>
																			</td>
																		</tr>

																		<tr style="font-family: AgencyFBb;">
																			<td colspan="2" style="font-size: 9px;">
																				<div style="margin-top: -8px; margin-left: 10px;">
																					LAB: '.substr(trim($analisis), 0, 100).'
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

					// Tercera línea
						if ($tiene_h2o == 1){
							$html .= '<div style="width: 102mm; height: 24mm; margin: 0px; pading: 0px;">
													<table style="width: 101.4mm; border-spacing: 0; margin: 0px;">
														<tr>
															<td style="border-spacing: 0; width: 50.7mm; margin: 0px;">
																<div style="margin-left: -5px; margin-right: 12px; width: 100%; height: 24mm;">
																	<div style="width: 100%; height: 23mm; margin: -2px;">
																		<table style="min-width: 100%; border-spacing: 0">
																			<tr style="font-size: 10px; font-family: AgencyFBb;">
																				<td style="text-align: center;">
																					<div style="width: 17mm; height: 10mm; margin-left: -5px;">
																						<div class="row">
																							ETIQUETA
																						</div>

																						<div class="row" style="margin-top: -7px;">
																							HUMEDAD
																						</div>
																					</div>
																				</td>

																				<td style="text-align: center;">
																					<div style="width: 25mm; height: 15mm; margin-top: -10px; font-size: 40px; text-align: center;">
																						'.$cod_internox.'A
																					</div>
																				</td>

																				<td style="text-align: right;">
																					<div style="width: 13mm; height: 10mm; margin-right: 7px; margin-top: 5px;">
																						<div style="width: 100%; font-size: 10px;">
																							'.$fecha_recepcion.'
																						</div>

																						<div style="margin-top: -5px; font-size: 10px;">
																							'.$hora_recepcion.'
																						</div>
																					</div>
																				</td>
																			</tr>

																			<tr style="font-size: 11px; font-weight: bold;">
																				<td colspan="3">
																					<div style="width: 100%; margin-top: 0px; text-align: center;">
																						<img src="'.$ruta_images.'bc/'.$cod_interno.'A.png" style="height: 25px; width: 90%;"/>
																					</div>
																				</td>
																			</tr>

																			<tr style="font-size: 14px; font-family: AgencyFBb;">
																				<td colspan="3 style="text-align: center;">
																					<div style="text-align: center; margin-top: -5px; width: 100%">
																						Labmin Perú S.A.C.
																					</div>
																				</td>
																			</tr>
																		</table>
																	</div>
																</div>
															</td>

															<td style="border-spacing: 0; width: 50.7mm; margin: 0px;">
																<div style="margin-left: -5px; margin-right: 12px; width: 100%; height: 24mm;">
																	<div style="width: 100%; height: 23mm; margin: -2px;">
																		<table style="min-width: 100%; border-spacing: 0">
																			<tr style="font-size: 10px; font-family: AgencyFBb;">
																				<td style="text-align: center;">
																					<div style="width: 17mm; height: 10mm; margin-left: 10px;">
																						<div class="row">
																							ETIQUETA
																						</div>

																						<div class="row" style="margin-top: -7px;">
																							HUMEDAD
																						</div>
																					</div>
																				</td>

																				<td style="text-align: center;">
																					<div style="width: 27mm; height: 15mm; margin-top: -10px; font-size: 40px; text-align: center;">
																						'.$cod_internox.'B
																					</div>
																				</td>

																				<td style="text-align: right;">
																					<div style="width: 13mm; height: 10mm; margin-left: 10px; margin-top: 5px;">
																						<div style="width: 100%; font-size: 10px;">
																							'.$fecha_recepcion.'
																						</div>

																						<div style="margin-top: -5px; font-size: 10px;">
																							'.$hora_recepcion.'
																						</div>
																					</div>
																				</td>
																			</tr>

																			<tr style="font-size: 11px; font-weight: bold;">
																				<td colspan="3">
																					<div style="width: 100%; margin-top: 0px; text-align: center; margin-left: 25px;">
																						<img src="'.$ruta_images.'bc/'.$cod_interno.'B.png" style="height: 25px; width: 100%;"/>
																					</div>
																				</td>
																			</tr>

																			<tr style="font-size: 14px; font-family: AgencyFBb;">
																				<td colspan="3" style="text-align: center;">
																					<div style="text-align: center; margin-top: -5px; width: 100%; margin-left: 25px;">
																						Labmin Perú S.A.C.
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

					// // Segunda línea
					// 	$html .= '<div style="width: 100%; height: 23mm; margin: 0px; pading: 0px; margin-top: 1px; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px;">
					// 							<table style="width: 100%; border-spacing: 0; margin: 0px;">
					// 								<tr>
					// 									<td style="border-spacing: 0; width: 50%; margin: 0px;">
					// 										<div style="margin-right: 8px;">
					// 											<div style="width: 100%; height: 23mm; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; margin: -2px;">
					// 											</div>
					// 										</div>
					// 									</td>

					// 									<td style="border-spacing: 0; width: 50%; margin: 0px;">
					// 										<div style="margin-left: 8px;">
					// 											<div style="width: 100%; height: 23mm; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; margin: -2px;">
					// 											</div>
					// 										</div>
					// 									</td>
					// 								</tr>
					// 							</table>
					// 						</div>';

					// // Tercera línea
					// 	$html .= '<div style="width: 100%; height: 23mm; margin: 0px; pading: 0px; margin-top: 1px; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px;">
					// 							<table style="width: 100%; border-spacing: 0; margin: 0px;">
					// 								<tr>
					// 									<td style="border-spacing: 0; width: 50%; margin: 0px;">
					// 										<div style="margin-right: 8px;">
					// 											<div style="width: 100%; height: 23mm; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; margin: -2px;">
					// 											</div>
					// 										</div>
					// 									</td>

					// 									<td style="border-spacing: 0; width: 50%; margin: 0px;">
					// 										<div style="margin-left: 8px;">
					// 											<div style="width: 100%; height: 23mm; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; margin: -2px;">
					// 											</div>
					// 										</div>
					// 									</td>
					// 								</tr>
					// 							</table>
					// 						</div>';

					// 	// $html .= '			<div style="height: 26mm !important; margin-top: 0px; margin-bottom: -10px; border: solid; border-width: 1px; border-color: #000000; border-radius: 7px;">
					// 	// 									<table style="width: 100%; border-spacing: 0; margin-top: -5px;">
					// 	// 										<tr>
					// 	// 											<td style="border-spacing: 0; width: 50%;">
					// 	// 												<div style="margin-top: 0px; height: 100px;">
					// 	// 													<table style="width: 100%; border-spacing: 0">
					// 	// 														<tr style="font-size: 9px; font-weight: bold;">
					// 	// 															<td style="width: 20%; text-align: center;">
					// 	// 																<div class="row">
					// 	// 																	> > > >
					// 	// 																</div>

					// 	// 																<div class="row">
					// 	// 																	CLIENTE
					// 	// 																</div>

					// 	// 																<div class="row">
					// 	// 																	> > > >
					// 	// 																</div>
					// 	// 															</td>

					// 	// 															<td style="width: 40%; text-align: center;">
					// 	// 																<label style="font-size: 37px; font-weight: bold; text-align: left; margin-left: -2px; margin-top: -10px;">
					// 	// 																	'.$cod_interno.'
					// 	// 																</label>
					// 	// 															</td>

					// 	// 															<td style="width: 10%; text-align: right;">
					// 	// 																<div style="margin-right: 25px; font-size: 9px;">
					// 	// 																	'.$fecha_recepcion.'
					// 	// 																</div>

					// 	// 																<div style="margin-right: 25px; font-size: 9px;">
					// 	// 																	'.$hora_recepcion.'
					// 	// 																</div>
					// 	// 															</td>
					// 	// 														</tr>

					// 	// 														<tr style="font-size: 11px; font-weight: bold;">
					// 	// 															<td colspan="3">
					// 	// 																<img src="'.$ruta_images.'bc/'.$CI.'.png" style="height: 30px; width: 90%;"/>
					// 	// 															</td>
					// 	// 														</tr>

					// 	// 														<tr style="font-size: 11px; font-weight: bold;">
					// 	// 															<td colspan="3 style="text-align: center;">
					// 	// 																<div style="text-align: center;">
					// 	// 																	Labmin Perú S.A.C.
					// 	// 																</div>
					// 	// 															</td>
					// 	// 														</tr>
					// 	// 													</table>
					// 	// 												</div>
					// 	// 											</td>

					// 	// 											<td style="border-spacing: 0; width: 50%;">
					// 	// 												<div style="margin-top: 0px; height: 100px;">
					// 	// 													<table style="width: 100%;">
					// 	// 														<tr style="font-size: 11px; font-weight: bold;">
					// 	// 															<td style="width: 60%">
					// 	// 																<label>
					// 	// 																	Labmin Perú S.A.C.
					// 	// 																</label>
					// 	// 															</td>

					// 	// 															<td style="width: 40%; text-align: center;">
					// 	// 																<label style="font-size: 27px; font-weight: bold; text-align: left; margin-left: -20px; margin-top: -10px;">
					// 	// 																	'.$cod_interno.'
					// 	// 																</label>
					// 	// 															</td>
					// 	// 														</tr>

					// 	// 														<tr style="font-size: 11px; font-weight: bold;">
					// 	// 															<td style="font-size: 10px;">
					// 	// 																<div style="margin-top: -13px;">
					// 	// 																	'.$fecha_recepcion.' '.$hora_recepcion.'
					// 	// 																</div>
					// 	// 															</td>

					// 	// 															<td style="font-size: 10px;">
					// 	// 																<div style="margin-top: -10px; text-align: center; margin-left: -25px;">
					// 	// 																	SOBRE
					// 	// 																</div>
					// 	// 															</td>
					// 	// 														</tr>

					// 	// 														<tr style="font-size: 11px; font-weight: bold;">
					// 	// 															<td colspan="2" style="font-size: 10px;">
					// 	// 																<div style="margin-top: -3px;">
					// 	// 																	'.trim($nombre_muestra).'
					// 	// 																</div>
					// 	// 															</td>
					// 	// 														</tr>

					// 	// 														<tr style="font-size: 11px; font-weight: bold;">
					// 	// 															<td colspan="2" style="font-size: 10px;">
					// 	// 																<div style="margin-top: -3px;">
					// 	// 																	'.$estado_muestra.'
					// 	// 																</div>
					// 	// 															</td>
					// 	// 														</tr>

					// 	// 														<tr style="font-size: 11px; font-weight: bold;">
					// 	// 															<td colspan="2" style="font-size: 10px;">
					// 	// 																<div style="margin-top: -3px;">
					// 	// 																	'.$envase_muestra.'
					// 	// 																</div>
					// 	// 															</td>
					// 	// 														</tr>

					// 	// 														<tr style="font-weight: bold;">
					// 	// 															<td colspan="2" style="font-size: 10px;">
					// 	// 																<div style="font-size: 9px; margin-top: -3px;">
					// 	// 																	'.$analisis.'
					// 	// 																</div>
					// 	// 															</td>
					// 	// 														</tr>
					// 	// 													</table>
					// 	// 												</div>
					// 	// 											</td>
					// 	// 										</tr>
					// 	// 									</table>
					// 	// 								</div>';

					// // Segunda línea
					// 	$html .= '			<div style="height: 105px; margin-top: 0px; margin-bottom: -10px;">
					// 										<table style="width: 100%; border-spacing: 0; margin-top: -5px;">
					// 											<tr>
					// 												<td style="border-spacing: 0; width: 50%;">
					// 													<div style="margin-top: -5px; height: 105px;">
					// 														<table style="width: 100%;">
					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td style="width: 60%">
					// 																	<label>
					// 																		Labmin Perú S.A.C.
					// 																	</label>
					// 																</td>

					// 																<td style="width: 40%; text-align: center;">
					// 																	<label style="font-size: 27px; font-weight: bold; text-align: left; margin-left: -20px; margin-top: -10px;">
					// 																		'.$cod_interno.'
					// 																	</label>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td style="font-size: 10px;">
					// 																	<div style="margin-top: -13px;">
					// 																		'.$fecha_recepcion.' '.$hora_recepcion.'
					// 																	</div>
					// 																</td>

					// 																<td style="font-size: 10px;">
					// 																	<div style="margin-top: -10px; text-align: center; margin-left: -25px;">
					// 																		RECHAZO
					// 																	</div>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td colspan="2" style="font-size: 10px;">
					// 																	<div style="margin-top: -3px;">
					// 																		'.trim($nombre_muestra).'
					// 																	</div>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td colspan="2" style="font-size: 10px;">
					// 																	<div style="margin-top: -3px;">
					// 																		'.$estado_muestra.'
					// 																	</div>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td colspan="2" style="font-size: 10px;">
					// 																	<div style="margin-top: -3px;">
					// 																		'.$envase_muestra.'
					// 																	</div>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-weight: bold;">
					// 																<td colspan="2" style="font-size: 10px;">
					// 																	<div style="font-size: 9px; margin-top: -3px;">
					// 																		'.$analisis.'
					// 																	</div>
					// 																</td>
					// 															</tr>
					// 														</table>
					// 													</div>
					// 												</td>

					// 												<td style="border-spacing: 0; width: 50%;">
					// 													<div style="pading-top: 5px; height: 105px;">
					// 														<table style="width: 100%;">
					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td style="width: 60%">
					// 																	<label>
					// 																		Labmin Perú S.A.C.
					// 																	</label>

					// 																	<div style="margin-top: 0px;">
					// 																		'.$fecha_recepcion.' '.$hora_recepcion.'
					// 																	</div>
					// 																</td>

					// 																<td style="width: 40%; text-align: center;">
					// 																	<label style="font-size: 27px; font-weight: bold; text-align: left; margin-left: -20px; margin-top: -10px;">
					// 																		'.$cod_interno.'
					// 																	</label>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td colspan="2" style="font-size: 10px;">
					// 																	<div style="margin-top: -8px;">
					// 																		'.$estado_muestra.'
					// 																	</div>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-size: 11px; font-weight: bold;">
					// 																<td colspan="2">
					// 																	<img src="'.$ruta_images.'bc/'.$CI.'.png" style="height: 30px; width: 90%;"/>
					// 																</td>
					// 															</tr>

					// 															<tr style="font-weight: bold;">
					// 																<td colspan="2 style="font-size: 10px;">
					// 																	<div style="font-size: 9px; margin-top: -5px;">
					// 																		LAB: '.$analisis.'
					// 																	</div>
					// 																</td>
					// 															</tr>
					// 														</table>
					// 													</div>
					// 												</td>
					// 											</tr>
					// 										</table>
					// 									</div>';

					// // Tercera línea (Solo para Humedad)
					// 	if ($tiene_h2o == 1){
					// 		$html .= '			<div style="height: 105px; margin-top: 0px; margin-bottom: -10px;">
					// 											<table style="width: 100%; border-spacing: 0; margin-top: -5px;">
					// 												<tr>
					// 													<td style="border-spacing: 0; width: 50%;">
					// 														<div style="margin-top: 0px; height: 105px;">
					// 															<table style="width: 100%; border-spacing: 0">
					// 																<tr style="font-size: 9px; font-weight: bold;">
					// 																	<td style="width: 20%; text-align: center;">
					// 																		<div class="row">
					// 																			ETIQUETA
					// 																		</div>

					// 																		<div class="row">
					// 																			HUMEDAD
					// 																		</div>
					// 																	</td>

					// 																	<td style="width: 40%; text-align: center;">
					// 																		<label style="font-size: 27px; font-weight: bold; text-align: left; margin-left: -2px; margin-top: -10px;">
					// 																			'.$cod_interno.'A
					// 																		</label>
					// 																	</td>

					// 																	<td style="width: 10%; text-align: right;">
					// 																		<div style="margin-right: 25px; font-size: 9px;">
					// 																			'.$fecha_recepcion.'
					// 																		</div>

					// 																		<div style="margin-right: 25px; font-size: 9px;">
					// 																			'.$hora_recepcion.'
					// 																		</div>
					// 																	</td>
					// 																</tr>

					// 																<tr style="font-size: 11px; font-weight: bold;">
					// 																	<td colspan="3">
					// 																		<img src="'.$ruta_images.'bc/'.$CI.'.png" style="height: 30px; width: 90%;"/>
					// 																	</td>
					// 																</tr>

					// 																<tr style="font-size: 11px; font-weight: bold;">
					// 																	<td colspan="3" style="text-align: center;">
					// 																		<div style="text-align: left; margin-top: 5px; margin-left: 5px;">
					// 																			SULFURO CHANCADO
					// 																		</div>
					// 																	</td>
					// 																</tr>
					// 															</table>
					// 														</div>
					// 													</td>

					// 													<td style="border-spacing: 0; width: 50%;">
					// 														<div style="margin-top: 0px; height: 105px;">
					// 															<table style="width: 100%; border-spacing: 0">
					// 																<tr style="font-size: 9px; font-weight: bold;">
					// 																	<td style="width: 20%; text-align: center;">
					// 																		<div class="row">
					// 																			ETIQUETA
					// 																		</div>

					// 																		<div class="row">
					// 																			HUMEDAD
					// 																		</div>
					// 																	</td>

					// 																	<td style="width: 40%; text-align: center;">
					// 																		<label style="font-size: 27px; font-weight: bold; text-align: left; margin-left: -2px; margin-top: -10px;">
					// 																			'.$cod_interno.'B
					// 																		</label>
					// 																	</td>

					// 																	<td style="width: 10%; text-align: right;">
					// 																		<div style="margin-right: 25px; font-size: 9px;">
					// 																			'.$fecha_recepcion.'
					// 																		</div>

					// 																		<div style="margin-right: 25px; font-size: 9px;">
					// 																			'.$hora_recepcion.'
					// 																		</div>
					// 																	</td>
					// 																</tr>

					// 																<tr style="font-size: 11px; font-weight: bold;">
					// 																	<td colspan="3">
					// 																		<img src="'.$ruta_images.'bc/'.$CI.'.png" style="height: 30px; width: 90%;"/>
					// 																	</td>
					// 																</tr>

					// 																<tr style="font-size: 11px; font-weight: bold;">
					// 																	<td colspan="3" style="text-align: center;">
					// 																		<div style="text-align: left; margin-top: 5px; margin-left: 5px;">
					// 																			SULFURO CHANCADO
					// 																		</div>
					// 																	</td>
					// 																</tr>
					// 															</table>
					// 														</div>
					// 													</td>
					// 												</tr>
					// 											</table>
					// 										</div>';
					// 	}
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
