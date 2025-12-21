<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Registro de Visitas.xls');
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

				?>

					<font size = "3"><b>
						RECEPCIÓN DE UNIDADES
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
	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				N°
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
	        				Fecha Hora Ingreso
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
	        				Motivo Visita
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Usuario Contacto
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
	        				Ingresó con Vehículo Particular
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Observaciones
	        			</th>

	        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Autorización
	        			</th>

	        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Información de Visitantes
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px; border-top-right-radius: 15px;" hidden>
	        				Información de Salida
	        			</th>
	        		</tr>

	        		<tr style="font-size: 12px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Estado
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Responsable
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Fecha y Hora
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				DNI
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
	        				Nombres
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
	        				Fecha Hora Salida
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
	        				Fecha Hora
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
	        				Observación
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody id="tbl_detalle">

							<?php
								$d = 1;
								$id_registro = 0;
								$total_visitas = 0;
								$html = '';

								$q_ingreso = "SELECT V.Id,
																		 V.id_motivovisita,
														         MV.descripcion AS MOTIVO_VISITA,
														         V.id_usuariocontacto,
														         CONCAT(E.nombres, ' ', E.apellido_paterno, ' ', E.apellido_materno) AS CONTACTO,
														         V.tiene_vehiculoparticular,
														         V.vehiculo_placa,
														         V.observaciones,
														         V.fechahora_registro,
														         V.usuario_registro,
														         V.fechahora_salida,
														         V.observacion_salida,
																		 V.usuario_salida,
																		 V.is_autorizado,
																		 V.autorizado_usuarioregistro,
																		 V.autorizado_fechahoraregistro,

														         (SELECT COUNT(D.Id)
																				FROM controlingreso_visitas_detalle D
																			 WHERE D.id_controlingreso = V.Id) AS TOTAL_VISITAS

																FROM controlingreso_visitas V
																		 INNER JOIN tbconfig_motivovisita MV ON V.id_motivovisita = MV.Id
														         INNER JOIN tb_empleados E ON V.id_usuariocontacto = E.Id
											         WHERE DATE(V.fechahora_registro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

								// if (strlen($filtro_transportista) > 0){
								// 	$q_ingreso .= "   AND I.id_transportista = ".$filtro_transportista;
								// }

								// if (strlen($filtro_placa) > 0){
								// 	$q_ingreso .= "   AND I.placa LIKE '%".$filtro_placa."%'";
								// }

								$q_ingreso .= " ORDER BY V.fechahora_registro DESC";

								if ($res_ingreso = mysqli_query($enlace, $q_ingreso)){
									if (mysqli_num_rows($res_ingreso) > 0) {
										$estado = 1;

										while($row_ingreso = mysqli_fetch_array($res_ingreso)){
											$total_visitas = $row_ingreso["TOTAL_VISITAS"];
											$total_visitas = (($total_visitas == 0) ? 1 : $total_visitas);

											$html .= '<tr style="cursor: pointer; font-size: 14px;">';

											if ($id_registro != $row_ingreso["Id"]){
												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.$d;
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.$row_ingreso["fechahora_registro"].' <i>'.$row_ingreso["usuario_registro"].'</i>';
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.$row_ingreso["MOTIVO_VISITA"];
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
												$html .= '    '.$row_ingreso["CONTACTO"];
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.$row_ingreso["vehiculo_placa"];
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
												$html .= '    '.$row_ingreso["observaciones"];
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

												if (strlen($row_ingreso["autorizado_fechahoraregistro"]) > 0){
													$html .= '    <label style="padding: 5px; border-radius: 7px; background-color: '.(($row_ingreso["is_autorizado"] == 1) ? '#CCEA8D' : '#FF5F5D; color: #ffffff;').'">'.(($row_ingreso["is_autorizado"] == 1) ? 'Autorizado' : 'Rechazado');
												}
												else{
													$html .= '		Pendiente';
												}

												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.$row_ingreso["autorizado_usuarioregistro"];
												$html .= '  </td>';

												$html .= '  <td rowspan="'.$total_visitas.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '    '.$row_ingreso["autorizado_fechahoraregistro"];
												$html .= '  </td>';
											}

											// Información de Visitas
												$a = 1;

												// Obteniendo detalle de Visitas
													$q_visitas = "SELECT Id,
																						   dni,
																						   nombres,
																						   tiene_imagen,
																						   imagen,
																						   fechahora_salida,
																						   usuario_salida
																				  FROM controlingreso_visitas_detalle
																			   WHERE id_controlingreso = ".$row_ingreso["Id"]."
																			  ORDER BY Id";

													if ($res_visitas = mysqli_query($enlace, $q_visitas)){
														if (mysqli_num_rows($res_visitas) > 0) {
															while($row_visitas = mysqli_fetch_array($res_visitas)){
																if ($a > 1){
																	$html .= '<tr style="cursor: pointer; font-size: 14px;">';
																}

																$html .= '				<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																$html .= '					'.$row_visitas["dni"];
																$html .= '  			</td>';

																$html .= '				<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
																$html .= '					'.mb_strtoupper($row_visitas["nombres"], 'UTF-8');
																$html .= '  			</td>';

																$html .= '				<td id="td_salidaacompanante_'.$row_visitas["Id"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

																if (strlen($row_visitas["fechahora_salida"]) > 0){
																// 	// $html .= '	<button class="btn btn-danger" type="button" onclick="f_RegistroSalida_Visitas('.$row_visitas["Id"].", '".$row_visitas["nombres"]."'".');" style="color: #ffffff; font-size: 11px; margin-top: -5px;">';
																// 	$html .= '	<button class="btn btn-danger" type="button" onclick="f_RegistroSalida('.$row_ingreso["Id"].');" style="color: #ffffff; font-size: 11px; margin-top: -5px;">';
																// 	$html .= '		<b>Registrar Salida</b>';
																// 	$html .= '	</button>';
																// }
																// else{
																	$html .= '		'.$row_visitas["fechahora_salida"].'<br>';
																	$html .= '		<i>'.$row_visitas["usuario_salida"].'</i>';
																}

																$html .= '				</td>';

																// Información de Salida
																	if ($a == 1){
																		$html .= '  <td rowspan="'.$total_visitas.'" id="td_salida_1_'.$row_ingreso["Id"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

																		if (strlen($row_ingreso["fechahora_salida"]) > 0){
																		// 	$html .= '		<button class="btn btn-danger" type="button" onclick="f_RegistroSalida('.$row_ingreso["Id"].');" style="color: #ffffff; font-size: 12px; margin-top: -5px;">';
																		// 	$html .= '			<b>Registrar Salida</b>';
																		// 	$html .= '		</button>';
																		// }
																		// else{
																			$html .= '			'.$row_ingreso["fechahora_salida"].'<br>';
																			$html .= '			<i>'.$row_ingreso["usuario_salida"].'</i>';
																		}

																		$html .= '  </td>';

																		$html .= '  <td rowspan="'.$total_visitas.'" id="td_salida_2_'.$row_ingreso["Id"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';

																		if (strlen($row_ingreso["fechahora_salida"]) > 0){
																			$html .= '		'.$row_ingreso["observacion_salida"];
																		}

																		$html .= '  </td>';
																	}

																if ($a > 1){
																	$html .= '</tr>';
																}

																$a ++;
															}

															$a - 1;
														}
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