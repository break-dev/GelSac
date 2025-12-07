<?php

	include('../cnx/cnx.php');
  include('../global/variables.php');

  header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Reporte de Pesos.xls');
	header('Pragma: no-cache');
	header('Expires: 0');

	// Obtener Peso Seco en función a la Humedad
		function f_GetPesoSeco($tmh, $h2o){
			if (strlen(trim($tmh)) > 0 && strlen(trim($h2o)) > 0){
				// Obtiene factor de humedad
					$factor = (100 - $h2o) / 100;

				// Obtiene Peso Seco
					$peso_seco = $tmh * $factor;
			}
			else{
				$peso_seco = '';
			}

			return $peso_seco;
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
						$id_destino = $_GET["id_destino"];
        		
        		$filtro_lote = $_GET["filtro_lote"];
						$filtro_lote = explode(',', $filtro_lote);

						// Setea Filtro de Lotes
							$l = 0;
							$arr_lotes = '';

							if(isset($filtro_lote) && is_array($filtro_lote)) {
								while ($l < count($filtro_lote)){
									if (strlen($filtro_lote[$l]) > 0){
										$arr_lotes .= "'".$filtro_lote[$l]."', ";
									}

									$l ++;
								}

								if (strlen($arr_lotes) > 0){
									$arr_lotes = substr($arr_lotes, 0, -2);
								}
							}

        		$filtro_codigodespacho = $_GET["filtro_codigodespacho"];
						$filtro_codigodespacho = explode(',', $filtro_codigodespacho);

						// Setea Filtro de Códigos de Despacho
							$l = 0;
							$arr_codigosdespacho = '';

							if(isset($filtro_codigodespacho) && is_array($filtro_codigodespacho)) {
								while ($l < count($filtro_codigodespacho)){
									if (strlen($filtro_codigodespacho[$l]) > 0){
										$arr_codigosdespacho .= "'".$filtro_codigodespacho[$l]."', ";
									}

									$l ++;
								}

								if (strlen($arr_codigosdespacho) > 0){
									$arr_codigosdespacho = substr($arr_codigosdespacho, 0, -2);
								}
							}

				?>

				<font size = "3"><b>
					REPORTE DE PESOS
				</b></font>

				<br/>

				<font size = "2">
					Fecha de inicio: <?php echo $fecha_inicio; ?>
				</font>
				<br/>
				<font size = "2">
					Fecha de fin: <?php echo $fecha_fin; ?>
				</font>

				<br/>
				<br/>

				<table>
        	<thead>
        		<tr style="font-size: 12px;">
        			<th class="sticky" rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 40px;">
        				N°
        			</th>

        			<th rowspan="3" class="sticky-2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 70px;">
        				Lote
        			</th>

        			<th rowspan="3" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Código Planta
        			</th>

        			<th rowspan="3" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
        				Destino
        			</th>

        			<th rowspan="3" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
        				Código Despacho
        			</th>

        			<th colspan="5" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Información Recepción
        			</th>

        			<th colspan="4" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Información Salida
        			</th>

        			<th colspan="5" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Información Planta
        			</th>

        			<th colspan="8" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
        				Anaálisis de Diferenciass
        			</th>
        		</tr>

        		<tr style="font-size: 12px;">
        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Fecha Ingreso a Balanza<br>(Real)
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				Peso Bruto
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				Peso Tara
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				TMH<br>Recepción
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				TMS<br>Recepción
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Fecha<br>Salida
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				Peso Bruto
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				Peso Tara
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				TMH<br>Salida
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Fecha llegada<br>a Planta
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				Peso Bruto
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				Peso Tara
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
        				TMH<br>Planta
        			</th>

        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				TMS<br>Planta
        			</th>

        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Recepción vs Salida
        			</th>

        			<th colspan="4" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Recepción vs Planta
        			</th>

        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Salida vs Planta
        			</th>
        		</tr>

        		<tr style="font-size: 12px;">
        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				TMH
        			</th>

        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				%
        			</th>

        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				TMH
        			</th>

        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				%
        			</th>

        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				TMS
        			</th>

        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				%
        			</th>

        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				TMH
        			</th>

        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				%
        			</th>
        		</tr>
        	</thead>

        	<tbody id="tbl_detalle">

						<?php
							$d = 1;
							$html = '';

							$q_datos = "SELECT PD.cod_lote,
																 PL.nombre_comercial AS DESTINO,
													       PD.codigo_despacho,
													       L.tFechaInicialBalanza AS FECHA_INGRESOBALANZA,
													       AHC.cierre_prom AS HUMEDAD,
													       SUBSTRING(CI.fechahora_salida, 1, 10) AS FECHAHORA_SALIDA,
													       SUM(DL.peso_tara) AS SALIDA_PESOBRUTO,
													       SUM(DL.peso_bruto) AS SALIDA_PESOTARA,
													       SUM(DL.peso_neto) AS SALIDA_PESONETO,
													       DL.llegadaplanta_fechaingreso,
													       DL.llegadaplanta_pesobrutoplanta,
													       DL.llegadaplanta_pesotaraplanta,
													       DL.llegadaplanta_pesonetoplanta,
													       DL.llegadaplanta_humedadplanta,
													       DL.llegadaplanta_pesoseco										        
														FROM despachos_segundotramo_programacion_detalle PD
																 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																 INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
													       INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
													         AND PD.cod_lote = DL.cod_lote
																 INNER JOIN transporte UN ON U.id_unidad = UN.id_transporte
													       LEFT JOIN catalogolotes L ON L.ccod_Lote = PD.cod_lote
													       LEFT JOIN analisislq_humedad_cabecera AHC ON cod_interno = PD.cod_lote
													         AND AHC.is_reanalisis = 0
													       LEFT JOIN controlingresovehiculo CI ON CI.placa = UN.cplaca
																   AND CI.dFechaIngreso BETWEEN U.fecha_ingresoplanta AND P.fechaestimada_despacho
																 LEFT JOIN tbconfig_plantas PL ON P.id_planta = PL.Id
													 WHERE DL.is_complemento = 0";

							if (strlen($arr_lotes) > 0 || strlen($arr_codigosdespacho) > 0){
								if (strlen($arr_lotes) > 0){
									$q_datos .= "   AND DL.cod_lote IN (".$arr_lotes.")";
								}

								if (strlen($arr_codigosdespacho) > 0){
									$q_datos .= "   AND PD.codigo_despacho IN (".$arr_codigosdespacho.")";
								}
							}
							else{
								$q_datos .= "   AND DATE(P.fechaestimada_despacho) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

								if (strlen($id_destino) > 0){
									$q_datos .= "   AND P.id_planta = ".$id_destino;
								}
							}

							$q_datos .= " GROUP BY PD.cod_lote, PL.nombre_comercial, PD.codigo_despacho, L.tFechaInicialBalanza, AHC.cierre_prom, SUBSTRING(CI.fechahora_salida, 1, 10), DL.llegadaplanta_fechaingreso, DL.llegadaplanta_pesobrutoplanta, DL.llegadaplanta_pesotaraplanta, DL.llegadaplanta_pesonetoplanta, DL.llegadaplanta_humedadplanta, DL.llegadaplanta_pesoseco
														ORDER BY DL.cod_lote";

							if ($res_datos = mysqli_query($enlace, $q_datos)){
								if (mysqli_num_rows($res_datos) > 0) {
									$estado = 1;

									while($row_datos = mysqli_fetch_array($res_datos)){
										$html .= '<tr style="font-size: 14px; cursor: pointer;">';

										$html .= '  <td class="row-sticky" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; background-color: #ffffff;">';
										$html .= '    '.$d;
										$html .= '  </td>';

										$html .= '  <td class="row-sticky-2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer; background-color: #ffffff; font-weight: bold;">';
										$html .= '    '.$row_datos["cod_lote"];
										$html .= '  </td>';

										// Obtiene el/los Códigos de Planta
											$arr_codigoplanta = '';

											$q_codigosplanta = "SELECT DISTINCT
																								 IFNULL(PD.codigo_planta, DL.llegadaplanta_codigoplanta) AS CODIGO_PLANTA
																						FROM despachos_segundotramo_programacion_detalle PD
																								 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																								 INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
																					       INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
																					         AND PD.cod_lote = DL.cod_lote
																					 WHERE PD.cod_lote = '".$row_datos["cod_lote"]."'";

											if ($res_codigosplanta = mysqli_query($enlace, $q_codigosplanta)){
												if (mysqli_num_rows($res_codigosplanta) > 0) {
													while($row_codigosplanta = mysqli_fetch_array($res_codigosplanta)){
														if (strlen($row_codigosplanta["CODIGO_PLANTA"]) > 0){
															$arr_codigoplanta .= $row_codigosplanta["CODIGO_PLANTA"].'/';
														}
													}
												}
											}

											if (strlen($arr_codigoplanta) > 0){
												$arr_codigoplanta = substr($arr_codigoplanta, 0, -1);
											}

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '    '.$arr_codigoplanta;
											$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.$row_datos["DESTINO"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.$row_datos["codigo_despacho"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.$row_datos["FECHA_INGRESOBALANZA"];
										$html .= '  </td>';

										// Obteniendo datos de Recepción
											$recepcion_pesobruto = 0;
											$recepcion_pesotara = 0;
											$recepcion_pesoneto = 0;

											$q_recepcion = "SELECT SUM(lote_peso_inicial) AS RECEPCION_PESOBRUTO,
																			       SUM(lote_peso_final) AS RECEPCION_PESOTARA,
																			       SUM(lote_peso_neto) AS RECEPCION_PESONETO
																			  FROM despachos_primertramo_validaciondatos
																			 WHERE lote_cod_lote = '".$row_datos["cod_lote"]."'";

											if ($res_recepcion = mysqli_query($enlace, $q_recepcion)){
												if (mysqli_num_rows($res_recepcion) > 0) {
													while($row_recepcion = mysqli_fetch_array($res_recepcion)){
														$recepcion_pesobruto = $row_recepcion["RECEPCION_PESOBRUTO"];
														$recepcion_pesotara = $row_recepcion["RECEPCION_PESOTARA"];
														$recepcion_pesoneto = $row_recepcion["RECEPCION_PESONETO"];
													}
												}
											}

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.number_format($recepcion_pesobruto / 1000, 3, '.', '');
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.number_format($recepcion_pesotara / 1000, 3, '.', '');
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer; font-weight: bold; background-color: #D9D2D0;">';

										$tmh_recepcion = $recepcion_pesoneto;
										$tmh_recepcion = ((strlen($tmh_recepcion) == 0) ? 'NULL' : $recepcion_pesoneto / 1000);

										$html .= '    '.(($tmh_recepcion == 'NULL') ? '' : number_format($tmh_recepcion, 3, '.', ''));
										$html .= '  </td>';

										// Calcula Peso Seco
											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';

											$tms_recepcion = f_GetPesoSeco($recepcion_pesoneto / 1000, $row_datos["HUMEDAD"]);

											if (strlen($tms_recepcion) > 0){
												$html .= '    '.number_format(($tms_recepcion), 3, '.', ',');
											}

											$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.$row_datos["FECHAHORA_SALIDA"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.number_format($row_datos["SALIDA_PESOBRUTO"], 3, '.', '');
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.number_format($row_datos["SALIDA_PESOTARA"], 3, '.', '');
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer; font-weight: bold; background-color: #D9D2D0;">';

										$tmh_salida = $row_datos["SALIDA_PESONETO"];
										$tmh_salida = ((strlen($tmh_salida) == 0) ? 'NULL' : number_format($row_datos["SALIDA_PESONETO"], 3, '.', ''));

										$html .= '    '.$tmh_salida;
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.$row_datos["llegadaplanta_fechaingreso"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.((strlen($row_datos["llegadaplanta_pesobrutoplanta"]) == 0) ? '' : number_format($row_datos["llegadaplanta_pesobrutoplanta"], 3, '.', ''));
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
										$html .= '    '.((strlen($row_datos["llegadaplanta_pesotaraplanta"]) == 0) ? '' : number_format($row_datos["llegadaplanta_pesotaraplanta"], 3, '.', ''));
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer; font-weight: bold; background-color: #D9D2D0;">';

										$tmh_planta = $row_datos["llegadaplanta_pesonetoplanta"];
										$tmh_planta = ((strlen($tmh_planta) == 0) ? 'NULL' : number_format($row_datos["llegadaplanta_pesonetoplanta"], 3, '.', ''));

										$html .= '    '.(($tmh_planta == 'NULL') ? '' : $tmh_planta);
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';

										$tms_planta = $row_datos["llegadaplanta_pesoseco"];
										$tms_planta = ((strlen($tms_planta) == 0) ? 'NULL' : number_format($row_datos["llegadaplanta_pesoseco"], 3, '.', ''));

										$html .= '    '.(($tms_planta == 'NULL') ? '' : $tms_planta);
										$html .= '  </td>';

										// Calculando Indicadores: Recepción vs Salida
											if ($tmh_salida == 'NULL' || $tmh_recepcion == 'NULL'){
												$ind_1a = '';
												$ind_1b = '';
											}
											else{
												$ind_1a = number_format($tmh_salida - $tmh_recepcion, 3, '.', '');
												$ind_1b = number_format(($ind_1a / $tmh_recepcion) * 100, 2, '.', '');
											}

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_1a;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_1b;
											$html .= '  </td>';

										// Calculando Indicadores: Recepción vs Planta (TMH)
											if ($tmh_planta == 'NULL' || $tmh_recepcion == 'NULL'){
												$ind_2a = '';
												$ind_2b = '';
											}
											else{
												$ind_2a = number_format($tmh_planta - $tmh_recepcion, 3, '.', '');
												$ind_2b = number_format(($ind_2a / $tmh_recepcion) * 100, 2, '.', '');
											}

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_2a;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_2b;
											$html .= '  </td>';

										// Calculando Indicadores: Recepción vs Planta (TMS)
											if ($tms_planta == 'NULL' || $tms_recepcion == 'NULL'){
												$ind_3a = '';
												$ind_3b = '';
											}
											else{
												$ind_3a = number_format($tms_planta - $tms_recepcion, 3, '.', '');
												$ind_3b = number_format(($ind_3a / $tms_recepcion) * 100, 2, '.', '');
											}

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_3a;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_3b;
											$html .= '  </td>';

										// Calculando Indicadores: Salida vs Planta
											if ($tmh_planta == 'NULL' || $tmh_salida == 'NULL'){
												$ind_4a = '';
												$ind_4b = '';
											}
											else{
												$ind_4a = number_format($tmh_planta - $tmh_salida, 3, '.', '');
												$ind_4b = number_format(($ind_4a / $tmh_salida) * 100, 2, '.', '');
											}

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_4a;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 60px; cursor: pointer;">';
											$html .= '		'.$ind_4b;
											$html .= '  </td>';

										$html .= '</tr>';

										$d ++;
									}
								}
							}

			        echo $html;

						?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</html>