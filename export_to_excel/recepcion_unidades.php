<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Recepción de Unidades.xls');
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
						$filtro_condicioningreso = $_GET["filtro_condicioningreso"];
						$filtro_transportista = $_GET["filtro_transportista"];
						$filtro_placa = $_GET["filtro_placa"];

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

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Condición
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				N° Placa 1
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
	        				N° Placa 2 (Remolque)
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Emp. de Transporte
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Tipo Vehículo
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Info. Conductor
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Tipo Carga
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
	        				Observación
	        			</th>

	        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Información de Salida de Unidad
	        			</th>

	        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Información de Acompañantes
	        			</th>
	        		</tr>

	        		<tr style="font-size: 12px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				DNI / RUC
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Razón Social
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Licencia
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
	        				Nombres
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
	        				Fecha Hora
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
	        				Estado Unidad
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Observación
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				DNI
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Nombres
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
	        				Fecha Hora Salida
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody id="tbl_detalle">

							<?php
								$d = 1;
								$id_registro = 0;
								$total_acompanantes = 0;
								$html = '';

								$q_ingreso = "SELECT I.id_controlIngresoVehiculo,
																		 CONCAT(I.dFechaIngreso, ' ', I.dhoraingresoPlanta) AS FECHAHORA_REGISTRO,
																		 I.id_tipoingresounidad,
															       IU.descripcion AS CLIENTE_CONDICION,
															       I.placa,
															       I.placa2,
															       I.id_transportista,
															       T.documento,
															       T.razon_social AS TRANSPORTISTA,
															       I.id_tipovehiculo,
															       TV.descripcion AS TIPO_VEHICULO,
															       I.id_choferes,
															       CD.dni_licencia,
															       CD.nombres AS CONDUCTOR,
															       I.id_tipocarga,
															       TC.descripcion AS TIPO_CARGA,
															       i.id_zonaorigen,
															       ZO.descripcion AS ZONA_ORIGEN,
															       i.cNotas,
															       i.tiene_vehiculoparticular,
															       I.fechahora_salida,
															       I.usuario_salida,
															       I.id_estadosalidaunidad,
															       ES.descripcion AS ESTADO_SALIDA,
															       I.observacion_salida,
															       I.usuario_registro,

															       A.dni AS acompanantes_dni,
																		 A.nombres AS acompanantes_nombres,
												             A.tiene_imagen AS acompanantes_tiene_imagen,
												             A.imagen AS acompanantes_imagen,
												             A.fechahora_salida AS acompanantes_fechahora_salida,
												             A.usuario_salida AS acompanantes_usuario_salida,

														         (SELECT COUNT(A.Id)
														       		 FROM controlingresovehiculo_acompanantes A
														       	  WHERE A.id_controlingreso = I.id_controlIngresoVehiculo) AS TOTAL_ACOMPANANTES

																FROM controlingresovehiculo I
																		 INNER JOIN tbconfig_tipoingresounidades IU ON I.id_tipoingresounidad = IU.Id
																		 INNER JOIN tb_clientes T ON I.id_transportista = T.Id
																		 INNER JOIN tbconfig_tipovehiculo TV ON I.id_tipovehiculo = TV.Id
																		 INNER JOIN tbconfig_conductores CD ON I.id_choferes = CD.Id
																		 LEFT JOIN tbconfig_tipocarga TC ON I.id_tipocarga = TC.Id
																		 LEFT JOIN tbconfig_zonaorigen ZO ON I.id_zonaorigen = ZO.Id
																		 LEFT JOIN tbconfig_estadosalidaunidades ES ON I.id_estadosalidaunidad = ES.Id
																		 LEFT JOIN controlingresovehiculo_acompanantes A ON I.id_controlIngresoVehiculo = A.id_controlingreso
															 WHERE I.dFechaIngreso BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

								if (strlen($filtro_condicioningreso) > 0){
									$q_ingreso .= "   AND I.id_tipoingresounidad = ".$filtro_condicioningreso;
								}

								if (strlen($filtro_transportista) > 0){
									$q_ingreso .= "   AND I.id_transportista = ".$filtro_transportista;
								}

								if (strlen($filtro_placa) > 0){
									$q_ingreso .= "   AND I.placa LIKE '%".$filtro_placa."%'";
								}

								$q_ingreso .= " ORDER BY I.dFechaIngreso, I.dhoraingresoPlanta DESC";

								if ($res_ingreso = mysqli_query($enlace, $q_ingreso)){
									if (mysqli_num_rows($res_ingreso) > 0) {
										while($row_ingreso = mysqli_fetch_array($res_ingreso)){
											$html .= '<tr style="font-size: 14px;">';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$d;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["FECHAHORA_REGISTRO"].' | <i>'.$row_ingreso["usuario_registro"].'</i>';
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["CLIENTE_CONDICION"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
											$html .= '		<label style="padding: 8px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #f8da62; cursor: pointer;" onclick="f_ShowInformacion('.$row_ingreso["id_controlIngresoVehiculo"].')">';
											$html .= '			'.$row_ingreso["placa"];
											$html .= '		</label>';
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["placa2"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["documento"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["TRANSPORTISTA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["TIPO_VEHICULO"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["dni_licencia"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["CONDUCTOR"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_ingreso["TIPO_CARGA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '    '.$row_ingreso["cNotas"];
											$html .= '  </td>';

											$html .= '  <td id="td_salida_1_'.$row_ingreso["id_controlIngresoVehiculo"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; '.((strlen($row_ingreso["fechahora_salida"]) == 0) ? 'color: #dc3545; font-size: 12px;' : '').'">';

											if (strlen($row_ingreso["fechahora_salida"]) > 0){
												$html .= '			'.$row_ingreso["fechahora_salida"].' | ';
												$html .= '			<i>'.$row_ingreso["usuario_salida"].'</i>';
											}
											else{
												$html .= '				<i>Salida Pendiente</i>';
											}

											$html .= '  </td>';

											$html .= '  <td id="td_salida_2_'.$row_ingreso["id_controlIngresoVehiculo"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

											if (strlen($row_ingreso["fechahora_salida"]) > 0){
												$html .= '		'.$row_ingreso["ESTADO_SALIDA"];
											}

											$html .= '  </td>';

											$html .= '  <td id="td_salida_3_'.$row_ingreso["id_controlIngresoVehiculo"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';

											if (strlen($row_ingreso["fechahora_salida"]) > 0){
												$html .= '		'.$row_ingreso["observacion_salida"];
											}

											$html .= '  </td>';

											if ($row_ingreso["TOTAL_ACOMPANANTES"] > 0){
												$html .= '				<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '					'.$row_ingreso["acompanantes_dni"];
												$html .= '  			</td>';

												$html .= '				<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
												$html .= '					'.$row_ingreso["acompanantes_nombres"];
												$html .= '  			</td>';

												$html .= '				<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; '.((strlen($row_ingreso["acompanantes_fechahora_salida"]) == 0) ? 'color: #dc3545; font-size: 12px;' : '').'">';

												if (strlen($row_ingreso["acompanantes_fechahora_salida"]) > 0){
													$html .= '					'.$row_ingreso["acompanantes_fechahora_salida"].' | ';
													$html .= '					<i>'.$row_ingreso["acompanantes_usuario_salida"].'</i>';
												}
												else{
													$html .= '				<i>Salida Pendiente</i>';
												}

												$html .= '				</td>';
											}
											else{
												$html .= '  <td colspan="3" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; padding: 0px; text-align: center; color: #dc3545; font-size: 12px;">';
												$html .= '		<i>Sin Acompañantes</i>';
												$html .= '  </td>';
											}

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