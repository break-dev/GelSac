<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	// Recuperando las variables enviadas
		$fecha_inicio = $_GET["fecha_inicio"];
		$fecha_fin = $_GET["fecha_fin"];
		$filtro_planta = $_GET["filtro_planta"];

		$filtro_estadolote = $_GET["filtro_estadolote"];
		$filtro_estadolote = (($filtro_estadolote == 'null') ? '' : $filtro_estadolote);
		
		$filtro_lote = $_GET["filtro_lote"];
		$filtro_lote = (($filtro_lote == 'null') ? '' : $filtro_lote);

	// Función para Obtener la diferencia de horas
		function f_GetDiferenciaHoras($fecha_inicio, $fecha_fin){
			// Crear objetos DateTime para las fechas de inicio y fin
				$inicio = new DateTime($fecha_inicio);
				$fin = new DateTime($fecha_fin);

			// Calcular la diferencia entre las fechas
				$diferencia = $inicio->diff($fin);

			// Obtener la diferencia en horas
				$horas = $diferencia->h;
				$horas += $diferencia->days * 24;

			// Si la diferencia también incluye minutos, sumar fracción de hora
				if ($diferencia->i > 0) {
					$horas += $diferencia->i / 60;
				}

			return number_format($horas, 2, '.', '');
		}

	// Inicia html
		$html = '<!DOCTYPE html>
							<html lang="es">
								<head>
					        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					        <meta charset="utf-8">
					        <meta http-equiv="X-UA-Compatible" content="IE=edge">
					        <meta name="viewport" content="width=device-width, initial-scale=1">
					        <title>Reporte Stock - Destino Definido ('.$g_fecha.')</title>
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
											padding: 0;
											margin-bottom: -15px;
											font-size: 14px;
										}

										@page{
											margin: 0;
											pading: 0;
										}
									</style>
						    </head>

						    <body style="width: 100%; padding: 0px; text-align: center; margin-left: 20px; margin-right: 20px;">';

		$html_cabecera = '<div style="width: 100%; margin-left: 0px; margin-right: 0px; height: 1100px;">
												<div class="row" style="margin-top: 20px; text-align: left; font-family: AgencyFBb; font-size: 16px;">
													REPORTE DE STOCK PENDIENTE DE DESTINO ACTUALIZADO AL: '.$g_fecha.'
												</div>

												<div class="row" style="text-align: center; margin-top: 10px;">
													<table style="width: 100%; border-spacing: -1px;">
									        	<thead>
							        				<tr style="font-size: 12px;">
										        		<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 58px;">
									        				N°
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
									        				Lote
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
									        				Fecha Ingreso<br>a Balanza<br>(Real)
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
									        				Encargado Muestra
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
									        				Proveedor Minero
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81; min-width: 80px;">
									        				Peso Neto<br>(TMH)
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
									        				Días Transcurridos
									        			</th>

									        			<th colspan="6" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Información Muestreo
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
									        				Modalidad Envío
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
									        				Estado de Lote
									        			</th>

									        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px; border-top-right-radius: 15px;">
									        				Observación
									        			</th>
									        		</tr>

									        		<tr style="font-size: 12px;">
									        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Las Lomas
									        			</th>

									        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Solandra
									        			</th>

									        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Paltarumi
									        			</th>
									        		</tr>

									        		<tr style="font-size: 12px;">
									        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Fecha Muestreo
									        			</th>

									        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Días Transcurridos
									        			</th>

									        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Fecha Muestreo
									        			</th>

									        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Días Transcurridos
									        			</th>

									        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Fecha Muestreo
									        			</th>

									        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
									        				Días Transcurridos
									        			</th>
									        		</tr>
									        	</thead>

									        	<tbody>';

  	$html .= $html_cabecera;

								        	// Setea el Detalle
														$d = 1;
														$x = 1;
														$cod_lote_inicio = 0;
														$total_tmh = 0;

														// Obtiene los lotes de Balanza (Que no sean Lote AUM)
															$q_validacion = "	SELECT *
																									FROM (SELECT DISTINCT
																											 V.lote_cod_lote,
																											 V.lote_id_encargadomuestra,
																											 EM.nombres AS ENCARGADO_MUESTRA,
																											 V.lote_id_proveedorminero,
																											 PM.razon_social AS PROVEEDOR_MINERO,
																											 V.despacho_id_destinoplanta,
																											 DP.nombre_comercial AS DESTINO_PLANTA,
																											 V.despacho_id_modalidadenvio,
																											 ME.descripcion AS MODALIDAD_ENVIO,
																											 V.despacho_id_estadolote,
																											 EL.descripcion AS ESTADO_LOTE,
																											 V.balanza_observacion,
																											 L.is_loteaum,
																											 V.lote_peso_neto_loteaum,
																											 V.despacho_destinoplanta_fechahoraregistro,
																											 P.fechaestimada_despacho,
																											 V.muestreo_laslomas_issolicitado,
																											 V.muestreo_solandra_issolicitado,
																											 V.muestreo_paltarumi_issolicitado,

																											 (SELECT IFNULL(GM.fecha_muestreo, '')
																													FROM operaciones_gestionmuestreo GM
																												 WHERE GM.item_planta = 1
																													 AND GM.cod_lote = V.lote_cod_lote
																												ORDER BY GM.fecha_muestreo DESC
																												LIMIT 1) AS FECHAMUESTREO_LASLOMAS,

																											 (SELECT IFNULL(GM.fecha_muestreo, '')
																													FROM operaciones_gestionmuestreo GM
																												 WHERE GM.item_planta = 2
																													 AND GM.cod_lote = V.lote_cod_lote
																												ORDER BY GM.fecha_muestreo DESC
																												LIMIT 1) AS FECHAMUESTREO_SOLANDRA,

																											 (SELECT IFNULL(GM.fecha_muestreo, '')
																													FROM operaciones_gestionmuestreo GM
																												 WHERE GM.item_planta = 3
																													 AND GM.cod_lote = V.lote_cod_lote
																												ORDER BY GM.fecha_muestreo DESC
																												LIMIT 1) AS FECHAMUESTREO_PALTARUMI

																								  FROM despachos_primertramo_validaciondatos V
																											 LEFT JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
																											 INNER JOIN catalogolotes L ON V.lote_cod_lote = L.ccod_Lote
																											 LEFT JOIN controlingresovehiculo CI ON L.id_controlIngresoVehiculo = CI.id_controlIngresoVehiculo
																											 LEFT JOIN tbconfig_encargadosmuestra EM ON V.lote_id_encargadomuestra = EM.Id
																											 LEFT JOIN tbconfig_plantas DP ON V.despacho_id_destinoplanta = DP.Id
																											 LEFT JOIN tbconfig_modalidadenvio ME ON V.despacho_id_modalidadenvio = ME.Id
																											 LEFT JOIN tbconfig_estadoslote EL ON V.despacho_id_estadolote = EL.Id
																											 LEFT JOIN despachos_segundotramo_programacion_detalle PD ON PD.cod_lote = V.lote_cod_lote
																											 LEFT JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																								 WHERE L.is_loteaum = 0
																								 	 AND V.despacho_id_destinoplanta IS NULL
																									 /*AND (YEAR(balanza_fechahoraregistro) >= 2024*/
																									 AND (YEAR(L.tFechaInicialBalanza) >= 2024
																									 	OR V.lote_cod_lote IN ('AUM-2587', 'AUM-3000', 'AUM-2337', 'AUM-2585', 'AUM-2907', 'AUM-2980'))";

															if (strlen($fecha_inicio) > 0){
																$q_validacion .= "   AND DATE(L.tFechaInicialBalanza) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
															}

															if (strlen($filtro_estadolote) > 0){
																$q_validacion .= "   AND V.despacho_id_estadolote IN (".$filtro_estadolote.")";
															}

															if (strlen($filtro_lote) > 0){
																$q_validacion .= "   AND V.lote_cod_lote IN (".$filtro_lote.")";
															}

														// Obtiene los lotes AUM creados en Despachos
															$q_validacion .= " UNION

																								SELECT DISTINCT
																											 V.lote_cod_lote,
																											 V.lote_id_encargadomuestra,
																											 EM.nombres AS ENCARGADO_MUESTRA,
																											 V.lote_id_proveedorminero,
																											 PM.razon_social AS PROVEEDOR_MINERO,
																											 V.despacho_id_destinoplanta,
																											 DP.nombre_comercial AS DESTINO_PLANTA,
																											 V.despacho_id_modalidadenvio,
																											 ME.descripcion AS MODALIDAD_ENVIO,
																											 V.despacho_id_estadolote,
																											 EL.descripcion AS ESTADO_LOTE,
																											 V.balanza_observacion,
																											 L.is_loteaum,
																											 V.lote_peso_neto_loteaum,
																											 V.despacho_destinoplanta_fechahoraregistro,
																											 P.fechaestimada_despacho,
																											 V.muestreo_laslomas_issolicitado,
																											 V.muestreo_solandra_issolicitado,
																											 V.muestreo_paltarumi_issolicitado,

																											 (SELECT IFNULL(GM.fecha_muestreo, '')
																													FROM operaciones_gestionmuestreo GM
																												 WHERE GM.item_planta = 1
																													 AND GM.cod_lote = V.lote_cod_lote
																												ORDER BY GM.fecha_muestreo DESC
																												LIMIT 1) AS FECHAMUESTREO_LASLOMAS,

																											 (SELECT IFNULL(GM.fecha_muestreo, '')
																													FROM operaciones_gestionmuestreo GM
																												 WHERE GM.item_planta = 2
																													 AND GM.cod_lote = V.lote_cod_lote
																												ORDER BY GM.fecha_muestreo DESC
																												LIMIT 1) AS FECHAMUESTREO_SOLANDRA,

																											 (SELECT IFNULL(GM.fecha_muestreo, '')
																													FROM operaciones_gestionmuestreo GM
																												 WHERE GM.item_planta = 3
																													 AND GM.cod_lote = V.lote_cod_lote
																												ORDER BY GM.fecha_muestreo DESC
																												LIMIT 1) AS FECHAMUESTREO_PALTARUMI

																								  FROM despachos_primertramo_validaciondatos V
																											 LEFT JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
																											 INNER JOIN catalogolotes L ON V.lote_cod_lote = L.ccod_Lote
																											 LEFT JOIN controlingresovehiculo CI ON L.id_controlIngresoVehiculo = CI.id_controlIngresoVehiculo
																											 LEFT JOIN tbconfig_encargadosmuestra EM ON V.lote_id_encargadomuestra = EM.Id
																											 LEFT JOIN tbconfig_plantas DP ON V.despacho_id_destinoplanta = DP.Id
																											 LEFT JOIN tbconfig_modalidadenvio ME ON V.despacho_id_modalidadenvio = ME.Id
																											 LEFT JOIN tbconfig_estadoslote EL ON V.despacho_id_estadolote = EL.Id
																											 LEFT JOIN despachos_segundotramo_programacion_detalle PD ON PD.cod_lote = V.lote_cod_lote
																											 LEFT JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																								 WHERE L.is_loteaum = 1
																								 	 AND V.despacho_id_destinoplanta IS NULL
																									 /*AND YEAR(balanza_fechahoraregistro) >= 2024*/
																									 AND YEAR(L.tFechaInicialBalanza) >= 2024";

															if (strlen($fecha_inicio) > 0){
																$q_validacion .= "   AND DATE(L.tFechaInicialBalanza) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
															}

															if (strlen($filtro_estadolote) > 0){
																$q_validacion .= "   AND V.despacho_id_estadolote IN (".$filtro_estadolote.")";
															}

															if (strlen($filtro_lote) > 0){
																$q_validacion .= "   AND V.lote_cod_lote IN (".$filtro_lote.")";
															}

															$q_validacion .= ") AS DATOS";
															$q_validacion .= " WHERE 1 = 1";

															if ($filtro_planta == 1){
																$q_validacion .= "   AND LENGTH(FECHAMUESTREO_LASLOMAS) > 0";
															}

															if ($filtro_planta == 2){
																$q_validacion .= "   AND LENGTH(FECHAMUESTREO_SOLANDRA) > 0";
															}

															if ($filtro_planta == 3){
																$q_validacion .= "   AND LENGTH(FECHAMUESTREO_PALTARUMI) > 0";
															}

															$q_validacion .= " ORDER BY 1";

														if ($res_validacion = mysqli_query($enlace, $q_validacion)){
															if (mysqli_num_rows($res_validacion) > 0) {
																while($row_validacion = mysqli_fetch_array($res_validacion)){
																	$html .= '<tr id="tr_detalle_'.$d.'" style="font-size: 14px; '.((strlen($row_validacion["despacho_color"]) > 0) ? 'background-color: '.$row_validacion["despacho_color"] : '').'" onclick="f_LoadItemLote('.$d.", '".$row_validacion["lote_cod_lote"]."'".')">';

																	$html .= '  <td id="td_select_1_'.$d.'" class="sticky td_bgselect" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; background-color: #ffffff;">';
																	$html .= '    '.$d;
																	$html .= '		<input id="id_'.$d.'" type="hidden" value="'.$row_validacion["lote_cod_lote"].'">';
																	$html .= '		<input id="td_iscerrado_'.$d.'" type="hidden" value="'.$is_cerrado.'">';
																	$html .= '  </td>';

																	$html .= '  <td id="td_select_3_'.$d.'" class="sticky-4 td_bgselect" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; cursor: pointer; background-color: #ffffff;">';
																	$html .= '    <label id="td_lote_'.$d.'" style="cursor: pointer;">';
																	$html .= '    	'.$row_validacion["lote_cod_lote"];
																	$html .= '    </label>';
																	$html .= '  </td>';

																	// Obtiene Fecha Real de Peso Inicial de Balanza
																		$fecha_pesoinicial = '';

																		$q_balanza = "SELECT tFechaInicialBalanza
																										FROM catalogolotes
																									 WHERE ccod_Lote = '".$row_validacion["lote_cod_lote"]."'";
																									 
																		if ($res_balanza = mysqli_query($enlace, $q_balanza)){
																			if (mysqli_num_rows($res_balanza) > 0) {
																				while($row_balanza = mysqli_fetch_array($res_balanza)){
																					$fecha_pesoinicial = $row_balanza["tFechaInicialBalanza"];
																				}
																			}
																		}

																		$html .= '  <td id="td_fechainicial_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																		$html .= '    '.substr($fecha_pesoinicial, 0, 10);
																		$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																	$html .= '    	'.mb_strtoupper($row_validacion["ENCARGADO_MUESTRA"]);
																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																	$html .= '    	'.mb_strtoupper($row_validacion["PROVEEDOR_MINERO"]);
																	$html .= '  </td>';

																	// Obtiene Total de Toneladas Distribuídas
																		$total_neto = 0;

																		$q_datos = "SELECT nPesoNetoBalanza
																									FROM catalogolotes
																								 WHERE ccod_Lote = '".$row_validacion["lote_cod_lote"]."'";

																		if ($res_datos = mysqli_query($enlace, $q_datos)){
																			if (mysqli_num_rows($res_datos) > 0) {
																				while($row_datos = mysqli_fetch_array($res_datos)){
																					$total_neto = $row_datos["nPesoNetoBalanza"] / 1000;
																				}
																			}
																		}

																	// Seteando toneladas solo para Sara Briceño
																		$tmh_distribuido = 0;

																		$q_datos = "SELECT SUM(lote_peso_neto) AS TOTAL_TMH
																									FROM despachos_primertramo_validaciondatos
																								 WHERE lote_cod_lote = '".$row_validacion["lote_cod_lote"]."'";

																		if ($res_datos = mysqli_query($enlace, $q_datos)){
																			if (mysqli_num_rows($res_datos) > 0) {
																				while($row_datos = mysqli_fetch_array($res_datos)){
																					$tmh_distribuido = $row_datos["TOTAL_TMH"] / 1000;

																					if ($row_validacion["lote_id_encargadomuestra"] == 3){
																						if (floatval($tmh_distribuido) != floatval($total_neto)){
																							$total_neto = $tmh_distribuido;
																						}
																					}
																				}
																			}
																		}

																	// Si es Lote AUM
																		if ($row_validacion["is_loteaum"] == 1){
																			$total_neto = $row_validacion["lote_peso_neto_loteaum"] / 1000;
																		}

																	// Obtiene el Peso Neto
																		$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #D2E8E3">';
																		$html .= '		<label id="td_totallote_'.$d.'" style="font-weight: bold; font-size: 14px;">';
																		$html .= '			'.number_format($total_neto, 2, '.', '');
																		$html .= '		</label>';
																		$html .= '  </td>';

																	// Seteando los días transcurridos
																		$dias_transcurridos = f_GetDiferenciaHoras($fecha_pesoinicial, $g_fecha);
																		$dias_transcurridos = number_format($dias_transcurridos / 24, 2, '.', '');
																		$dias_transcurridos = explode('.', $dias_transcurridos)[0];

																		$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																		$html .= '			'.$dias_transcurridos;
																		$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

																	if (strlen($row_validacion["FECHAMUESTREO_LASLOMAS"]) == 0){
																		if ($row_validacion["muestreo_laslomas_issolicitado"] == 1){
																			$html .= '			Solicitado';
																		}
																	}
																	else{
																		$html .= '			'.$row_validacion["FECHAMUESTREO_LASLOMAS"];
																	}
																	
																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

																	if (strlen($row_validacion["FECHAMUESTREO_LASLOMAS"]) > 0){
																		$dias_transcurridos = f_GetDiferenciaHoras($row_validacion["FECHAMUESTREO_LASLOMAS"], $g_fecha);
																		$dias_transcurridos = number_format($dias_transcurridos / 24, 2, '.', '');
																		$dias_transcurridos = explode('.', $dias_transcurridos)[0];

																		$html .= '			'.$dias_transcurridos;
																	}

																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

																	if (strlen($row_validacion["FECHAMUESTREO_SOLANDRA"]) == 0){
																		if ($row_validacion["muestreo_solandra_issolicitado"] == 1){
																			$html .= '			Solicitado';
																		}
																	}
																	else{
																		$html .= '			'.$row_validacion["FECHAMUESTREO_SOLANDRA"];
																	}
																	
																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

																	if (strlen($row_validacion["FECHAMUESTREO_SOLANDRA"]) > 0){
																		$dias_transcurridos = f_GetDiferenciaHoras($row_validacion["FECHAMUESTREO_SOLANDRA"], $g_fecha);
																		$dias_transcurridos = number_format($dias_transcurridos / 24, 2, '.', '');
																		$dias_transcurridos = explode('.', $dias_transcurridos)[0];

																		$html .= '			'.$dias_transcurridos;
																	}

																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

																	if (strlen($row_validacion["FECHAMUESTREO_PALTARUMI"]) == 0){
																		if ($row_validacion["muestreo_paltarumi_issolicitado"] == 1){
																			$html .= '			Solicitado';
																		}
																	}
																	else{
																		$html .= '			'.$row_validacion["FECHAMUESTREO_PALTARUMI"];
																	}
																	
																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

																	if (strlen($row_validacion["FECHAMUESTREO_PALTARUMI"]) > 0){
																		$dias_transcurridos = f_GetDiferenciaHoras($row_validacion["FECHAMUESTREO_PALTARUMI"], $g_fecha);
																		$dias_transcurridos = number_format($dias_transcurridos / 24, 2, '.', '');
																		$dias_transcurridos = explode('.', $dias_transcurridos)[0];

																		$html .= '			'.$dias_transcurridos;
																	}

																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																	$html .= '			'.mb_strtoupper($row_validacion["MODALIDAD_ENVIO"]);
																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																	$html .= '			'.mb_strtoupper($row_validacion["ESTADO_LOTE"]);
																		$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																	$html .= '			'.mb_strtoupper($row_validacion["balanza_observacion"]);
																	$html .= '  </td>';

																	$html .= '</tr>';

																	$d ++;
																	$x ++;

																	$total_tmh += $total_neto;

																	// Salta a la siguiente página
																		if ($x == 20){
																			$html .= '			</tbody>
																										</table>
																									</div>
																								</div>';

																			$html .= $html_cabecera;

																			$x = 1;
																		}
																}
															}
														}

	// Agregando fila de Total
		$html .= '<tr style="font-size: 14px; font-family: AgencyFBb;">';
		$html .= '	<td colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: right;">
        					Total Peso Neto (TMH)
        				</th>';

		$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #816951; color: #ffffff;">';
		$html .= '	'.number_format($total_tmh, 2, '.', ',');
		$html .= '  </td>';

		$html .= '	<td colspan="10" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: right;">
        				</th>';

    $html .= '</tr>';

	// Cerrando html
		$html .= '					</tbody>
											</table>
										</div>
									</div>
								</body>
					  	</html>';

	// Seteando PDF
		$options = new Options();
	  $options->set('isRemoteEnabled', TRUE);
	  $document = new Dompdf($options);

		$document -> loadHtml($html, 'UTF-8');
		$document -> setPaper('A3', 'landscape');
		$document -> render();
		$document -> stream("Reporte Stock - Destino Definido (".$g_fecha.")", array('Attachment' => 0));

?>