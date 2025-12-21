<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=LQ - Cierre de Análisis de Humedad.xls');
	header('Pragma: no-cache');
	header('Expires: 0');

	include('../cnx/cnx.php');
  include('../global/variables.php');

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
						$filtro_estado = $_GET["filtro_estado"];
						$cod_interno = $_GET["cod_interno"];

				?>

					<font size = "3"><b>
						LQ - Cierre de Análisis de Humedad
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

					<table class="table table-bordered table-hover">
	        	<thead>
	        		<tr style="font-size: 14px;">
	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				N°
	        			</th>

	        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Muestra
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Tara (Peso Bandeja)
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Peso Húmedo
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Peso Seco + Tara
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Peso Seco
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				% Humedad
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Promedio
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Cierre
	        			</th>
	        		</tr>

	        		<tr style="font-size: 14px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Fecha Hora Recepción (A.T.C.)
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Cód. Interno
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Material
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Item
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Sel.
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Fecha Hora
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Usuario
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody id="tbl_muestras">

							<?php
								// Obtiene datos
									$d = 1;
									$id_cabecera = 0;
									$item_id = 0;
									$c = 0; // Utilizado para contabilizar los checks de Cierre

									$q_detalle = "SELECT REC.fechahora_recepcion,
																       A.cod_interno,
																       TM.descripcion AS TIPO_MUESTRA,
																       EM.descripcion AS ESTADO_MUESTRA,
																       A.item,
																       C.is_cierre,
																       C.cierre_prom,
																       C.fechahora_cierre,
																       C.usuario_cierre,
																       A.cierre_pesos,
																       A.peso_bandeja,
																			 A.fechahora_pesobandeja,
																			 A.usuario_pesobandeja,
																			 A.peso_muestrahumeda,
																			 A.fechahora_muestrahumeda,
																			 A.usuario_muestrahumeda,
																			 A.peso_muestraseca,
																			 A.fechahora_muestraseca,
																			 A.usuario_muestraseca,
																			 (A.peso_muestraseca - A.peso_bandeja) AS PESO_SECO,
																			 A.porc_humedad,

																			 (SELECT IFNULL(AVG(A_x.porc_humedad), '')
																				  FROM analisislq_humedad_analisis A_x
																				 WHERE A_x.id_cabecera = C.Id) AS PROMEDIO,

																       C.Id AS ID_CABECERA,
																       A.Id AS ID_DETALLE
																  FROM analisislq_humedad_analisis A
																			 INNER JOIN analisislq_humedad_cabecera C ON A.id_cabecera = C.Id
																       INNER JOIN analisislq_humedad_rack R ON C.id_rack = R.Id
																       INNER JOIN recepcion_ensayos_detalle D ON C.id_ensayosdetalle = D.Id
																       INNER JOIN tbconfig_tiposmuestra TM ON D.cod_tipomuestra = TM.Id
																       INNER JOIN tbconfig_estadosmuestra EM ON D.cod_estadomuestra = EM.Id
																       INNER JOIN recepcion_ensayos_cabecera REC ON D.id_cabecera = REC.Id
																 WHERE DATE(REC.fechahora_recepcion) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

									if (strlen($filtro_estado) > 0){
										$q_detalle .= "   AND C.is_cierre = ".$filtro_estado;
									}

									$q_detalle .= " ORDER BY REC.fechahora_recepcion, A.cod_interno, C.Id, A.item";

									if ($res_detalle = mysqli_query($enlace, $q_detalle)) {
										if (mysqli_num_rows($res_detalle) > 0) {
											$estado = 1;            

											while($row_detalle = mysqli_fetch_assoc($res_detalle)) {
												$html .= '<tr style="font-size: 14px;">';
												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
												$html .= '    '.$d;
												$html .= '  </td>';

												if ($id_cabecera != $row_detalle["ID_CABECERA"]){
													$item_id = 1;

													if (strlen($row_detalle["PROMEDIO"]) > 0){
														$c ++;
													}

													$html .= '  <td rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
													$html .= '    '.substr($row_detalle["fechahora_recepcion"], 0, 16);
													$html .= '  </td>';

													$html .= '  <td rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
													$html .= '    '.$row_detalle["cod_interno"];
													$html .= '  </td>';

													$html .= '  <td rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
													$html .= '    '.$row_detalle["TIPO_MUESTRA"].'<br>'.$row_detalle["ESTADO_MUESTRA"];
													$html .= '  </td>';
												}
												else{
													$item_id ++;
												}

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
												$html .= '    '.$row_detalle["item"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';

												if (strlen($row_detalle["porc_humedad"]) > 0){
													if ($row_detalle["cierre_pesos"] == 1){
														$html .= '    x';
													}
												}

												$html .= '  </td>';

												// Peso de Bandeja
													$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; font-size: 14px;">';

													if (strlen($row_detalle["peso_bandeja"]) > 0){
														$html .= '  	'.$row_detalle["peso_bandeja"];
													}
													else{
														$html .= '  	<label style="color: #FF5F5D;">Pendiente</label>';
													}

													$html .= '  </td>';

												// Peso Húmedo
													$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; font-size: 14px;">';

													if (strlen($row_detalle["peso_muestrahumeda"]) > 0){
														$html .= '  	'.$row_detalle["peso_muestrahumeda"];
													}
													else{
														$html .= '  	<label style="color: #FF5F5D;">Pendiente</label>';
													}

													$html .= '  </td>';

												// Peso Seco + Tara
													$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; font-size: 14px;">';

													if (strlen($row_detalle["peso_muestraseca"]) > 0){
														$html .= '  	'.$row_detalle["peso_muestraseca"];
													}
													else{
														$html .= '  	<label style="color: #FF5F5D;">Pendiente</label>';
													}

													$html .= '  </td>';

												// Peso Seco
													$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; font-size: 14px;">';

													if (strlen($row_detalle["peso_muestraseca"]) > 0){
														$html .= '  	'.round($row_detalle["peso_muestraseca"] - $row_detalle["peso_bandeja"], 2);
													}

													$html .= '  </td>';

												// % Humedad
													$html .= '  <td id="row_promedio_'.$row_detalle["ID_CABECERA"].'_'.$item_id.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; font-size: 14px; '.((strlen($row_detalle["porc_humedad"]) > 0) ? 'background-color: #BB8CDD;' : '').'">';
													$html .= '  	'.$row_detalle["porc_humedad"];
													$html .= '  </td>';

												// Promedio
													if ($id_cabecera != $row_detalle["ID_CABECERA"]){
														$html .= '  <td id="td_prom_'.$row_detalle["ID_CABECERA"].'" rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; font-size: 14px; '.((strlen($row_detalle["PROMEDIO"]) > 0) ? 'background-color: #93D94E;' : '').'">';

														if ($row_detalle["is_cierre"] == 1){
															$html .= '  	'.$row_detalle["cierre_prom"];
														}
														else{
															$html .= '  	'.((strlen($row_detalle["PROMEDIO"]) > 0) ? round($row_detalle["PROMEDIO"], 2) : '');
														}

														$html .= '  </td>';
													}

												// Seteando objetos para Cierre
													if ($id_cabecera != $row_detalle["ID_CABECERA"]){
														// $html .= '  <td id="tdcierre_1_'.$c.'" rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';

														// if (strlen($row_detalle["PROMEDIO"]) > 0){
														// 	if ($row_detalle["is_cierre"] == 1){
														// 		$html .= '<u style="color: #FF5F5D; cursor: pointer;" onclick="f_Reabrir('.$c.', '.$row_detalle["ID_CABECERA"].')">Reabrir</u>';
														// 	}
														// 	else{
														// 		$html .= '  	<input id="rowchk_'.$c.'" class="form-check-input" style="transform: scale(1.5);" type="checkbox" checked '.(($row_detalle["is_cierre"] == 1) ? 'disabled' : '').'>';
														// 	}
															
														// 	$html .= '    <input id="rowchk_cierre_'.$c.'" type="hidden" value="'.$row_detalle["ID_CABECERA"].'">';
														// }

														// $html .= '  </td>';

														$html .= '  <td id="tdcierre_2_'.$c.'" rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

														if (strlen($row_detalle["PROMEDIO"]) > 0){
															if ($row_detalle["is_cierre"] == 1){
																$html .= '  	'.$row_detalle["fechahora_cierre"];
															}
														}

														$html .= '  </td>';

														$html .= '  <td id="tdcierre_3_'.$c.'" rowspan="2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

														if (strlen($row_detalle["PROMEDIO"]) > 0){
															if ($row_detalle["is_cierre"] == 1){
																$html .= '  	'.$row_detalle["usuario_cierre"];
															}
														}

														$html .= '  </td>';
													}

												$id_cabecera = $row_detalle["ID_CABECERA"];

												$html .= '</tr>';

												$d += 1;
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