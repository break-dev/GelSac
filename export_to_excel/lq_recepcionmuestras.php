<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=LQ - Recepción de Muestras.xls');
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
						$filtro_estadomuestra = $_GET["filtro_estadomuestra"];
						$filtro_envasemuestra = $_GET["filtro_envasemuestra"];
						$filtro_tiposmuestra = $_GET["filtro_tiposmuestra"];
						$filtro_ensayosmuestra = $_GET["filtro_ensayosmuestra"];
						$filtro_CI = $_GET["filtro_CI"];
						$filtro_opt1 = $_GET["filtro_opt1"];
						$filtro_opt2 = $_GET["filtro_opt2"];
						$filtro_opt3 = $_GET["filtro_opt3"];

				?>

					<font size = "3"><b>
						LQ - Recepción de Muestras
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
	        			<!-- MAX (22/02/2023 08:07): No olvidar agregar: "rowspan="2" a los siguientes 4 "th" -->
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				N°
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
	        				Código Interno
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
	        				Cliente
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
	        				Nombre Muestra
	        			</th>

	        			<!-- <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
	        				Fecha Hora Recepción
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
	        				Análisis
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Peso (Kg)
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Estado
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Envase
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Tipo
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
	        				Ensayo
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Información de Recepción
	        			</th> -->
	        		</tr>

	        		<!-- <tr style="font-size: 14px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Fecha y Hora
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Usuario
	        			</th>
	        		</tr> -->
	        	</thead>

	        	<tbody id="tbl_detalle">

							<?php
								$d = 1;
								$html = '';

								$q_datos = "SELECT C.Id AS ID_RECEPCION,
													 md5(C.Id) AS MD5_IDRECEPCION,
													 D.Id,
													 D.cod_interno,
													 S.des_sucursal,
													 C.cliente_documento,
													 CL.razon_social,
													 C.recibo_codigo,
													 C.facturador_comprobante,
													 C.is_instruccion,
													 C.is_temporal,
													 C.is_recepcioncanceled,
													 SUBSTRING(C.fechahora_recepcion, 1, 16) AS fechahora_recepcion,
													 SUBSTRING(C.fechahora_entrega, 1, 16) AS fechahora_entrega,
													 /*IFNULL(SUBSTRING(I.pagado_fechahoraregistro, 1, 16), SUBSTRING(C.fechahora_recepcion, 1, 16)) AS fechahora_instruccion,*/
													 SUBSTRING(C.instruccion_fechahoraregistro, 1, 16) AS fechahora_instruccion,
													 I.is_factura,
													 UPPER(IFNULL(MP.descripcion, 'POR PAGAR')) AS MEDIO_PAGO,
													 D.nombre_muestra,
													 D.peso_muestra,
													 EM.descripcion AS ESTADO_MUESTRA,
													 EVM.descripcion AS ENVASE_MUESTRA,
													 TM.descripcion AS TIPO_MUESTRA,
													 ESM.descripcion AS ENSAYO_MUESTRA,
													 D.fechahora_recepcionlq,
													 D.usuario_recepcionlq
											FROM recepcion_ensayos_cabecera C
													 INNER JOIN recepcion_ensayos_detalle D ON C.Id = D.id_cabecera
													 LEFT JOIN tb_sucursal S ON C.cod_sucursal = S.Id
													 LEFT JOIN tb_clientes CL ON C.cliente_documento = CL.documento
													 LEFT JOIN recepcion_instruccion I ON C.Id = I.id_cabecera
													 LEFT JOIN tbconfig_mediospago MP ON I.cod_mediopago = MP.Id
													 LEFT JOIN tbconfig_estadosmuestra EM ON D.cod_estadomuestra = EM.Id
													 LEFT JOIN tbconfig_envasesmuestra EVM ON D.cod_envasemuestra = EVM.Id
													 LEFT JOIN tbconfig_tiposmuestra TM ON D.cod_tipomuestra = TM.Id
													 LEFT JOIN tbconfig_ensayosmuestra ESM ON D.cod_ensayomuestra = ESM.Id
													 LEFT JOIN recepcion_codigosinternos_lq CI ON D.id_codinterno = CI.Id
										 WHERE C.is_instruccion = 1
										 	 AND C.is_recepcioncanceled = 0";

								if (strlen(trim($filtro_CI)) > 0){
									$q_datos .= "   AND D.cod_interno LIKE '%".trim($filtro_CI)."%'";
								}
								else{
									// $q_datos .= "   AND IFNULL(DATE(I.pagado_fechahoraregistro), DATE(C.fechahora_recepcion)) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

									$q_datos .= "   AND DATE(C.instruccion_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
								}

								if (strlen($filtro_estadomuestra) > 0){
									$q_datos .= "   AND D.cod_estadomuestra = ".$filtro_estadomuestra;
								}

								if (strlen($filtro_envasemuestra) > 0){
									$q_datos .= "   AND D.cod_envasemuestra = ".$filtro_envasemuestra;
								}

								if (strlen($filtro_tiposmuestra) > 0){
									$q_datos .= "   AND D.cod_tipomuestra = ".$filtro_tiposmuestra;
								}

								if (strlen($filtro_ensayosmuestra) > 0){
									$q_datos .= "   AND D.cod_ensayomuestra = ".$filtro_ensayosmuestra;
								}

								if ($filtro_opt3 == 0){
									if ($filtro_opt1 == 1){
										$q_datos .= "   AND D.fechahora_recepcionlq IS NULL";
									}
									else{
										$q_datos .= "   AND D.fechahora_recepcionlq IS NOT NULL";
									}
								}

								$q_datos .= " ORDER BY 4";

								if ($res_datos = mysqli_query($enlace, $q_datos)){
				          if (mysqli_num_rows($res_datos) > 0) {
				            while($row_datos = mysqli_fetch_array($res_datos)){
				              // Obteniendo la diferencia entre la Hora de Recepción y la Hora del Sistema
												$time_inicio = strtotime($row_datos["fechahora_recepcion"]);
												$time_fin = strtotime($g_fecha);
												$diff = ($time_fin - $time_inicio) / 60;

											$html .= '<tr id="tr_'.$row_datos["cod_interno"].'" style="cursor: pointer; font-size: 14px; '.((strlen($row_datos["fechahora_recepcionlq"]) > 0) ? 'background-color : #93D94E;' : (($diff > 60) ? 'background-color : #FFC9CF;' : '')).'">';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
											$html .= '   '.$d;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
											$html .= '   '.$row_datos["cod_interno"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.strtoupper($row_datos["razon_social"]);
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
											$html .= '   '.$row_datos["nombre_muestra"];
											$html .= '  </td>';

											// $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											// $html .= '   '.$row_datos["fechahora_recepcion"];
											// $html .= '  </td>';

											// // Obtiene los análisis de cada muestra
											// 	$analisis = '';

											// 	$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';

											// 	// 1. Obtiene análisis de Precios Generales
											// 		$q_analisis = "SELECT CONCAT('(', EA.abv, CASE WHEN LENGTH(IFNULL(C.abv, '')) > 0 THEN CONCAT(' ', C.abv) ELSE '' END, ')') AS abv,
											// 													REA.total
											// 										 FROM recepcion_ensayos_analisis REA
											// 													INNER JOIN tb_ensayos_analisisclasificaciones_detalle ACD ON REA.cod_analisis = ACD.Id
											// 													INNER JOIN tb_ensayos_analisisclasificaciones C ON ACD.cod_clasificacion = C.Id
											// 													INNER JOIN tb_ensayos_analisis EA ON ACD.cod_analisis = EA.Id
											// 										WHERE REA.id_detalle = ".$row_datos["Id"]."
											// 											AND is_paquete = 0
											// 											AND is_paquetecliente = 0
											// 									 ORDER BY EA.orden";

											// 		if ($res_analisis = mysqli_query($enlace, $q_analisis)){
											// 			if (mysqli_num_rows($res_analisis) > 0) {
											// 				while($row_analisis = mysqli_fetch_array($res_analisis)){
											// 					$analisis .= '            '.$row_analisis["abv"].', ';
											// 				}
											// 			}
											// 		}

											// 	// 2. Obtiene análisis de Paquetes Generales
											// 		$q_paquetes = "SELECT C.abv,
											// 													C.descripcion AS CLASIFICACION,
											// 													REA.cod_analisis,
											// 													REA.total,
											// 													REA.is_paquete,
											// 													REA.is_paquetecliente
											// 										 FROM recepcion_ensayos_analisis REA
											// 													INNER JOIN tb_ensayos_analisisclasificaciones_paquetes P ON REA.cod_analisis = P.Id
											// 													INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
											// 										WHERE REA.id_detalle = ".$row_datos["Id"]."
											// 											AND REA.is_paquete = 1";

											// 		if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
											// 			if (mysqli_num_rows($res_paquetes) > 0) {
											// 				while($row_paquetes = mysqli_fetch_array($res_paquetes)){
											// 					// Armando la descripción de análisis por paquete
											// 						$abv = '';
											// 						$descripcion = '';

											// 						$q_analisispaquete = "SELECT A.abv,
											// 																				A.descripcion
											// 																	 FROM tb_ensayos_analisisclasificaciones_paquetes_detalle D
											// 																				INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
											// 																	WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

											// 						if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
											// 							if (mysqli_num_rows($res_analisispaquete) > 0) {
											// 								while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
											// 									$abv .= $row_analisispaquete["abv"].' - ';
											// 									$descripcion .= $row_analisispaquete["descripcion"].' - ';
											// 								}
											// 							}
											// 						}

											// 						if (strlen($abv) > 0){
											// 							$abv = substr($abv, 0, -3);
											// 							$descripcion = substr($descripcion, 0, -3);
											// 						}

											// 					// Creando el query final
											// 						$q_analisis = "SELECT '(".$abv.")' AS abv,
											// 																		".$row_paquetes["total"]." AS total";

											// 						if ($res_analisis = mysqli_query($enlace, $q_analisis)){
											// 							if (mysqli_num_rows($res_analisis) > 0) {
											// 								while($row_analisis = mysqli_fetch_array($res_analisis)){
											// 									$analisis .= '            '.$row_analisis["abv"].', ';
											// 								}
											// 							}
											// 						}
											// 				}
											// 			}
											// 		}

											// 	// 3. Obtiene análisis de Paquetes de Clientes
											// 		$q_paquetes = "SELECT C.abv,
											// 													C.descripcion AS CLASIFICACION,
											// 													REA.cod_analisis,
											// 													REA.total,
											// 													REA.is_paquete,
											// 													REA.is_paquetecliente
											// 										 FROM recepcion_ensayos_analisis REA
											// 													INNER JOIN tb_ensayos_analisisclasificaciones_paquetesclientes P ON REA.cod_analisis = P.Id
											// 													INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
											// 										WHERE REA.id_detalle = ".$row_datos["Id"]."
											// 											AND REA.is_paquetecliente = 1";

											// 		if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
											// 			if (mysqli_num_rows($res_paquetes) > 0) {
											// 				while($row_paquetes = mysqli_fetch_array($res_paquetes)){
											// 					// Armando la descripción de análisis por paquete
											// 						$abv = '';
											// 						$descripcion = '';

											// 						$q_analisispaquete = "SELECT A.abv,
											// 																				A.descripcion
											// 																	 FROM tb_ensayos_analisisclasificaciones_paquetesclientes_detalle D
											// 																				INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
											// 																	WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

											// 						if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
											// 							if (mysqli_num_rows($res_analisispaquete) > 0) {
											// 								while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
											// 									$abv .= $row_analisispaquete["abv"].' - ';
											// 									$descripcion .= $row_analisispaquete["descripcion"].' - ';
											// 								}
											// 							}
											// 						}

											// 						if (strlen($abv) > 0){
											// 							$abv = substr($abv, 0, -3);
											// 							$descripcion = substr($descripcion, 0, -3);
											// 						}

											// 					// Creando el query final
											// 						$q_analisis = "SELECT '(".$abv.")' AS abv,
											// 																		".$row_paquetes["total"]." AS total";

											// 						if ($res_analisis = mysqli_query($enlace, $q_analisis)){
											// 							if (mysqli_num_rows($res_analisis) > 0) {
											// 								while($row_analisis = mysqli_fetch_array($res_analisis)){
											// 									$analisis .= '            '.$row_analisis["abv"].', ';
											// 								}
											// 							}
											// 						}
											// 				}
											// 			}
											// 		}

											// 		if (strlen($analisis) > 0){
											// 			$analisis = substr($analisis, 0, -2);
											// 		}

											// 	$html .= '            '.$analisis;

											// 	$html .= '          </div>';

											// $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											// $html .= '   '.$row_datos["peso_muestra"];
											// $html .= '  </td>';

											// $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											// $html .= '   '.$row_datos["ESTADO_MUESTRA"];
											// $html .= '  </td>';

											// $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											// $html .= '   '.$row_datos["ENVASE_MUESTRA"];
											// $html .= '  </td>';

											// $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											// $html .= '   '.$row_datos["TIPO_MUESTRA"];
											// $html .= '  </td>';

											// $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											// $html .= '   '.$row_datos["ENSAYO_MUESTRA"];
											// $html .= '  </td>';

											// $html .= '  <td id="tdrecepcion_1_'.$row_datos["cod_interno"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;';

											// 	if (strlen($row_datos["fechahora_recepcionlq"]) == 0){
											// 		$html .= ' color: #dc3545;"> Pendiente';
											// 	}
											// 	else{
											// 		$html .= '"> '.$row_datos["fechahora_recepcionlq"];
											// 	}

											// $html .= '  </td>';

											// $html .= '  <td id="tdrecepcion_2_'.$row_datos["cod_interno"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

											// 	if (strlen($row_datos["fechahora_recepcionlq"]) > 0){
											// 		$html .= '   '.$row_datos["usuario_recepcionlq"];
											// 	}

											// $html .= '  </td>';

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