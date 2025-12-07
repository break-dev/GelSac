<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Preparación - Resumen de Procesos.xls');
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
						$cod_tipomuestra = $_GET["cod_tipomuestra"];
						$filtro_lote = $_GET["filtro_lote"];

				?>

					<font size = "3"><b>
						PREPARACIÓN - RESUMEN DE PROCESOS
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
	        		<tr style="font-size: 12px;">
	        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				N°
	        			</th>

	        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
	        				Fecha Hora Ingreso Planta
	        			</th>

	        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
	        				Cód. Lote
	        			</th>

	        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Peso Neto Lote (Tn)
	        			</th>

	        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
	        				Tipo Muestra
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Información Recepción
	        			</th>

	        			<?php

	        			// Obteniendo lista de procesos
	        				$count_procesos = 0;
	        				$arr_procesos = '';

	        				$q_procesos = "SELECT Id,
	        															abv,
	        															descripcion,
	        															depende_de
	        												 FROM tb_procesos
	        												WHERE id_procesosarea = 2
	        													AND estado = 'A'
	        											 ORDER BY orden";

									if ($res_procesos = mysqli_query($enlace, $q_procesos)){
										if (mysqli_num_rows($res_procesos) > 0) {
											$count_procesos = mysqli_num_rows($res_procesos);

											while($row_procesos = mysqli_fetch_array($res_procesos)){
												$arr_procesos .= $row_procesos["Id"].';'.$row_procesos["abv"].';'.$row_procesos["descripcion"].'|';
											}
										}
									}

									$arr_procesos = substr($arr_procesos, 0, -1);
									$arr_procesos_x = $arr_procesos;

	        			?>

	        			<th colspan="<?php echo ($count_procesos * 3) ?>" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Información Procesos
	        			</th>
	        		</tr>

	        		<tr style="font-size: 12px;">
	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Peso Muestra (Kg)
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
	        				Registro
	        			</th>

	        			<?php

	        			// Coloca las cabeceras de procesos
	        				$p = 0;
	        				$arr_procesos = explode('|', $arr_procesos);

	        				while ($p < count($arr_procesos)){
	        					?>

	        					<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				<?php

			        				echo explode(';', $arr_procesos[$p])[1];

			        				?>
			        			</th>

	        					<?php

	        					$p ++;
	        				}

	        			?>

	        		</tr>

	        		<tr style="font-size: 12px;">
	        			<?php

		        		// Coloca las cabeceras de Inicio / Fin
	        				$p = 0;

	        				while ($p < count($arr_procesos)){
	        					?>

	        					<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
	        						Equipo
			        			</th>

	        					<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
	        						Inicio
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
	        						Fin
			        			</th>

	        					<?php

	        					$p ++;
	        				}

	        			?>
	        		</tr>
	        	</thead>

	        	<tbody>

							<?php
								$d = 1;
								$html = '';

								$q_datos = "SELECT DISTINCT R.cod_lote,
																	 CONCAT(L.dFechaIngreso, ' ', L.tHora_Ingreso) AS FECHAHORA_INGRESO,
																	 R.id_tipomuestrapreparacion,
																	 TM.descripcion AS TIPO_MUESTRA,
																   L.nPesoNetoBalanza,
																	 R.peso_muestra,
																	 R.fechahora_registro,
																	 R.usuario_registro,

														       (SELECT COUNT(Id)
														      		FROM preparacion_recepcionmuestras R_x
														      	 WHERE R_x.cod_lote = R.cod_lote) AS TOTAL_ROWS
															FROM preparacion_recepcionmuestras R
																	 LEFT JOIN tb_procesos_preparacion_detalle D ON R.cod_lote = D.cod_lote
																   LEFT JOIN tbconfig_tipomuestraspreparacion TM ON R.id_tipomuestrapreparacion = TM.Id
																   LEFT JOIN catalogolotes L  ON R.cod_lote = L.ccod_Lote
														 WHERE 1 = 1";

								if (strlen($cod_tipomuestra) > 0){
									$q_datos .= "   AND R.id_tipomuestrapreparacion = ".$cod_tipomuestra;
								}

								if (strlen($cod_lote) > 0){
									$q_datos .= "   AND R.cod_lote = '".$cod_lote."'";
								}
								else{
									$q_datos .= "   AND L.dFechaIngreso BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
								}

								$q_datos .= " ORDER BY R.cod_lote, R.id_tipomuestrapreparacion";

								if ($res_datos = mysqli_query($enlace, $q_datos)){
									if (mysqli_num_rows($res_datos) > 0) {
										$estado = 1;

										// Valida si tiene el Filtro de Tipo de Muestra
											if (strlen($cod_tipomuestra) > 0){
												$total_rows = 1;
											}
											else{
												$total_rows = 2;
											}

										while($row_datos = mysqli_fetch_array($res_datos)){
											$html .= '<tr style="cursor: pointer; font-size: 14px;">';
											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
											$html .= '    '.$d;
											$html .= '  </td>';

											if ($cod_lote != $row_datos["cod_lote"]){
												$html .= '  <td rowspan="'.$total_rows.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.$row_datos["FECHAHORA_INGRESO"];
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_rows.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
												$html .= '    '.$row_datos["cod_lote"];
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_rows.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.number_format($row_datos["nPesoNetoBalanza"], 0, '.', ',');
												$html .= '  </td>';
											}

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
											$html .= '    '.$row_datos["TIPO_MUESTRA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
											$html .= '    '.$row_datos["peso_muestra"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

											if (strlen($row_datos["fechahora_registro"]) > 0){
												$html .= '    '.$row_datos["fechahora_registro"].' | <i>'.$row_datos["usuario_registro"].'</i>';
											}
											else{
												$html .= '  	<i style="color: #FF5F5D;">Pendiente</i>';
											}

											$html .= '  </td>';

											// Recorre Array de Procesos
												$p = 0;
												$arr_proceso = '';

												while ($p < count($arr_procesos)){
													$arr_proceso = explode(';', $arr_procesos[$p]);

													// Obtiene los datos de Procesos
														$q_avance = "SELECT UPPER(E.descripcion) AS EQUIPO,
																								D.fechahora_inicio,
																								D.usuario_inicio,
																								D.fechahora_fin,
																								D.usuario_fin
																					 FROM tb_procesos_preparacion_detalle D
																					 			INNER JOIN tb_procesos_preparacion_equipos E ON D.id_equipo = E.Id
																					WHERE D.id_tipomuestrapreparacion = ".$row_datos["id_tipomuestrapreparacion"]."
																						AND D.id_proceso = ".$arr_proceso[0]."
																						AND D.cod_lote = '".$row_datos["cod_lote"]."'";

														if ($res_avance = mysqli_query($enlace, $q_avance)){
															if (mysqli_num_rows($res_avance) > 0) {
																while($row_avance = mysqli_fetch_array($res_avance)){
																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
																	$html .= '		'.$row_avance["EQUIPO"];
																	$html .= '  </td>';

																	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; background-color: #C4FF6E;">';
																	$html .= '		'.$row_avance["fechahora_inicio"].' | <i>'.$row_avance["usuario_inicio"].'</i>';
																	$html .= '  </td>';

																	if (strlen($row_avance["fechahora_fin"]) > 0){
																		$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; background-color: #FFB8B2;">';
																		$html .= '		'.$row_avance["fechahora_fin"].' | <i>'.$row_avance["usuario_fin"].'</i>';
																	}
																	else{
																		$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center; background-color: #F28705;">';
																		$html .= '  	<i style="color: #ffffff;">EN PROCESO...</i>';
																	}

																	$html .= '  </td>';
																}
															}
															else{
																$html .= '  <td colspan="3" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
																$html .= '  	<i style="color: #FF5F5D;">Pendiente</i>';
																$html .= '  </td>';
															}
														}

													$p ++;

													$cod_lote = $row_datos["cod_lote"];
												}

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