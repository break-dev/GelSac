<?php

	include('../cnx/cnx.php');
  include('../global/variables.php');

  header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Reporte Stock - Destino Definido ('.$g_fecha.').xls');
	header('Pragma: no-cache');
	header('Expires: 0');

	// Obtiene la Fecha y Hora de Despacho Real
		function f_GetFechaHoraDespachoReal($enlace, $lote){
			$fecha_despacho = '';

			$q_datos = "SELECT CI.fechahora_salida
									  FROM despachos_segundotramo_programacion_detalle PD
												 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
								         INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
								         INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
								           AND PD.cod_lote = DL.cod_lote
												 INNER JOIN transporte UN ON U.id_unidad = UN.id_transporte
								         INNER JOIN controlingresovehiculo CI ON CI.placa = UN.cplaca
								           AND CI.dFechaIngreso BETWEEN U.fecha_ingresoplanta AND P.fechaestimada_despacho
								   WHERE PD.cod_lote = '".$lote."'";

		  if ($res_datos = mysqli_query($enlace, $q_datos)){
				if (mysqli_num_rows($res_datos) > 0) {
					while($row_datos = mysqli_fetch_array($res_datos)){
						$fecha_despacho = $row_datos["fechahora_salida"];
					}
				}
			}

			return $fecha_despacho;
		}

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

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>

	<body class="nav-md footer_fixed">
		<div class="container body">
			<div class="main_container">
				<?php

					// Recuperando las variables enviadas
						$fecha_inicio = $_GET["fecha_inicio"];
						$fecha_fin = $_GET["fecha_fin"];
						$filtro_planta = $_GET["filtro_planta"];

						$filtro_estadolote = $_GET["filtro_estadolote"];
						$filtro_estadolote = (($filtro_estadolote == 'null') ? '' : $filtro_estadolote);
        		
        		$filtro_lote = $_GET["filtro_lote"];
						$filtro_lote = (($filtro_lote == 'null') ? '' : $filtro_lote);

				?>

				<font size = "3"><b>
					REPORTE DE STOCK CON DESTINO DEFINIDO
				</b></font>

				<br/>

				<font size = "2">
					Actualizado a: <?php echo $g_fecha; ?>
				</font>
				<br/>
				<br/>

				<table>
        	<thead>
        		<tr style="font-size: 12px;">
        			<th class="sticky sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 58px;">
        				N°
        			</th>

        			<th class="sticky-3h sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Lote
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Fecha Ingreso a Balanza (Real)
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
        				Encargado Muestra
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
        				Proveedor Minero
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81; min-width: 80px;">
        				Peso Neto (TMH)
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Fecha Hora Definición
        			</th>

        			<td class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Días Transcurridos<br>Definición
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
        				Destino
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
        				Modalidad Envío
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
        				Estado de Lote
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Fecha Estimada Despacho
        			</th>

        			<th class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px; border-top-right-radius: 15px;">
        				Observación
        			</th>
        		</tr>
        	</thead>

        	<tbody id="tbl_detalle">

						<?php
							$d = 1;
							$cod_lote_inicio = 0;
							$html = '';
							$total_tmh = 0;

							// Obtiene los lotes de Balanza (Que no sean Lote AUM)
								$q_validacion = "	SELECT DISTINCT
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
			  																 P.fechaestimada_despacho
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
																	 	 AND V.despacho_id_destinoplanta IS NOT NULL
																		 /*AND (YEAR(balanza_fechahoraregistro) >= 2024*/
																		 AND (YEAR(L.tFechaInicialBalanza) >= 2024
																		 	OR V.lote_cod_lote IN ('AUM-2587', 'AUM-3000', 'AUM-2337', 'AUM-2585', 'AUM-2907', 'AUM-2980'))";

								if (strlen($fecha_inicio) > 0){
									$q_validacion .= "   AND DATE(L.tFechaInicialBalanza) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
								}

								if (strlen($filtro_planta) > 0){
									$q_validacion .= "   AND V.despacho_id_destinoplanta = ".$filtro_planta;
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
			  																	P.fechaestimada_despacho
																	   FROM despachos_primertramo_validaciondatos V
																				  LEFT JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
																				  INNER JOIN catalogolotes L ON V.lote_cod_lote = L.ccod_Lote
																				 LEFT JOIN controlingresovehiculo CI ON L.id_controlIngresoVehiculo =  CI.id_controlIngresoVehiculo
																				  LEFT JOIN tbconfig_encargadosmuestra EM ON V.lote_id_encargadomuestra = EM.Id
																				  LEFT JOIN tbconfig_plantas DP ON V.despacho_id_destinoplanta = DP.Id
																				  LEFT JOIN tbconfig_modalidadenvio ME ON V.despacho_id_modalidadenvio = ME.Id
																				  LEFT JOIN tbconfig_estadoslote EL ON V.despacho_id_estadolote = EL.Id
																				  LEFT JOIN despachos_segundotramo_programacion_detalle PD ON PD.cod_lote = V.lote_cod_lote
																				  LEFT JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																	  WHERE L.is_loteaum = 1
																	 	  AND V.despacho_id_destinoplanta IS NOT NULL
																		  /*AND YEAR(balanza_fechahoraregistro) >= 2024*/
																		  AND YEAR(L.tFechaInicialBalanza) >= 2024";
 
								 if (strlen($fecha_inicio) > 0){
									 $q_validacion .= "   AND DATE(L.tFechaInicialBalanza) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
								 }
 
								 if (strlen($filtro_planta) > 0){
									 $q_validacion .= "   AND V.despacho_id_destinoplanta = ".$filtro_planta;
								 }
 
								 if (strlen($filtro_estadolote) > 0){
									 $q_validacion .= "   AND V.despacho_id_estadolote IN (".$filtro_estadolote.")";
								 }
 
								 if (strlen($filtro_lote) > 0){
									 $q_validacion .= "   AND V.lote_cod_lote IN (".$filtro_lote.")";
								 }
 
								 $q_validacion .= " ORDER BY 3, 1";

							if ($res_validacion = mysqli_query($enlace, $q_validacion)){
								if (mysqli_num_rows($res_validacion) > 0) {
									$estado = 1;

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

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '			'.$row_validacion["despacho_destinoplanta_fechahoraregistro"];
										$html .= '  </td>';

										// Seteando los días transcurridos
											$fecha_despachoreal = f_GetFechaHoraDespachoReal($enlace, $row_validacion["lote_cod_lote"]);

											if (strlen($fecha_despachoreal) > 0){
												$dias_transcurridos = f_GetDiferenciaHoras($row_validacion["despacho_destinoplanta_fechahoraregistro"], $fecha_despachoreal);
											}
											else{
												if (strlen($row_validacion["fechaestimada_despacho"]) > 0){
													$dias_transcurridos = f_GetDiferenciaHoras($row_validacion["despacho_destinoplanta_fechahoraregistro"], $row_validacion["fechaestimada_despacho"]);
												}
												else{
													$dias_transcurridos = f_GetDiferenciaHoras($row_validacion["despacho_destinoplanta_fechahoraregistro"], $g_fecha);
												}
											}

											$dias_transcurridos = number_format($dias_transcurridos / 24, 2, '.', '');
											$dias_transcurridos = explode('.', $dias_transcurridos)[0];

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '			'.$dias_transcurridos;
											$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
										$html .= '			'.mb_strtoupper($row_validacion["DESTINO_PLANTA"]);
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '			'.mb_strtoupper($row_validacion["MODALIDAD_ENVIO"]);
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '			'.mb_strtoupper($row_validacion["ESTADO_LOTE"]);
											$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

										if ($row_validacion["despacho_id_estadolote"] == 7 || $row_validacion["despacho_id_estadolote"] == 8){
											$html .= '			'.mb_strtoupper($row_validacion["fechaestimada_despacho"]);
										}
										
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '			'.mb_strtoupper($row_validacion["balanza_observacion"]);
										$html .= '  </td>';

										$html .= '</tr>';

										$d ++;

										$total_tmh += $total_neto;
									}
								}
							}

							// Agregando fila de Total
								$html .= '<tr style="font-size: 14px;">';
								$html .= '	<td colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: right;">
						        					Total Peso Neto (TMH)
						        				</th>';

        				$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #816951; color: #ffffff;">';
								$html .= '		<label id="td_totallote_'.$d.'" style="font-weight: bold; font-size: 14px;">';
								$html .= '			'.number_format($total_tmh, 2, '.', '');
								$html .= '		</label>';
								$html .= '  </td>';

								$html .= '	<td colspan="6" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: right;">
						        				</th>';

						    $html .= '</tr>';

			        echo $html;

						?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</html>