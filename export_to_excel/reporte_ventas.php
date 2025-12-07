<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Reporte de Ventas.xls');
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
						$filtro_cliente = $_GET["filtro_cliente"];
						$filtro_sucursal = $_GET["filtro_sucursal"];
						$filtro_mediospago = $_GET["filtro_mediospago"];
						$filtro_listatipodocumento = $_GET["filtro_listatipodocumento"];
						$filtro_documento = $_GET["filtro_documento"];
						$filtro_opt1 = $_GET["filtro_opt1"];
						$filtro_opt2 = $_GET["filtro_opt2"];
						$filtro_opt3 = $_GET["filtro_opt3"];

				?>

					<font size = "3"><b>
						REPORTE DE VENTAS
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

						<?php

						// Setea la Pre Cabecera
							$html_cabecera1 = '<thead id="th_detalle">
											        		<tr style="font-size: 14px;">
											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
											        				N°
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
											        				Sucursal
											        			</th>

											        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Cliente
											        			</th>

											        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Documentos
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Estado
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Fecha Hora Recepción
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Fecha Hora Instrucción
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Fecha Hora Entrega
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Incluye Recojo
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Exceso
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Dscto. (%)
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Sub Total
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Total Venta
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
											        				Medio Pago
											        			</th>

											        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
											        				Efectivo Ingresado
											        			</th>

											        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Info. Pagos Pendientes
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Usuario A.T.C.
											        			</th>

											        			<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Información de Muestras
											        			</th> ';

							$html_cabecera2 .= '</tr>

											        		<tr style="font-size: 14px;">
											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Documento
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
											        				Razón Social
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
											        				N° Recibo
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
											        				N° Comprobante
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Fecha Emisión
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Total
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Depósito
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
											        				Medio Pago
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
											        				Fecha Hora Pago
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Usuario Pago
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; font-weight: bold; min-width: 120px;">
											        				C.I.
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
											        				Nombre
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
											        				Análisis
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Estado
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Tipo
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Exceso
											        			</th>

											        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Total
											        			</th> ';

			  			$html_cabecera3 .= '</tr>
											        	</thead>';

						// Obteniendo datos de Análisis por cada venta
							$id_analisis = '';

							$q_cabecera = "SELECT D.Id
															 FROM recepcion_ensayos_cabecera C
																	  INNER JOIN recepcion_ensayos_detalle D ON C.Id = D.id_cabecera
																		LEFT JOIN recepcion_instruccion I ON C.Id = I.id_cabecera
																		LEFT JOIN tbconfig_mediospago MP ON I.cod_mediopago = MP.Id
															WHERE IFNULL(DATE(C.instruccion_fechahoraregistro), DATE(C.fechahora_recepcion)) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
					                      AND C.is_recepcioncanceled = 0
					                      AND C.is_instruccion = 1";

							if (strlen($filtro_cliente) > 0){
								$q_cabecera .= "   AND C.cliente_documento = ".$filtro_cliente;
							}

							if (strlen($filtro_sucursal) > 0){
								$q_cabecera .= "   AND C.cod_sucursal = ".$filtro_sucursal;
							}

							if (strlen(trim($filtro_mediospago)) > 0){
								$q_cabecera .= "   AND I.cod_mediopago = ".$filtro_mediospago;
							}

							if (strlen($filtro_listatipodocumento) > 0){
								if ($filtro_listatipodocumento == 1){
									$q_cabecera .= "   AND C.recibo_codigo LIKE '%".trim($filtro_documento)."%'";
								}
								else{
									$q_cabecera .= "   AND C.facturador_comprobante LIKE '%".trim($filtro_documento)."%'";
								}
							}

							if ($filtro_opt3 == 0){
								if ($filtro_opt1 == 1){
									$q_cabecera .= "   AND C.is_instruccion = 0";
								}
								else{
									$q_cabecera .= "   AND C.is_instruccion = 1";
								}
							}

							if ($res_cabecera = mysqli_query($enlace, $q_cabecera)){
								if (mysqli_num_rows($res_cabecera) > 0) {
									while($row_cabecera = mysqli_fetch_array($res_cabecera)){
										// 1. Obtiene análisis de Precios Generales
											$q_analisis = "SELECT EA.Id,
																						CONCAT('(', EA.abv, CASE WHEN LENGTH(IFNULL(C.abv, '')) > 0 THEN CONCAT(' ', C.abv) ELSE '' END, ')') AS abv,
																						REA.total
																			 FROM recepcion_ensayos_analisis REA
																						INNER JOIN tb_ensayos_analisisclasificaciones_detalle ACD ON REA.cod_analisis = ACD.Id
																						INNER JOIN tb_ensayos_analisisclasificaciones C ON ACD.cod_clasificacion = C.Id
																						INNER JOIN tb_ensayos_analisis EA ON ACD.cod_analisis = EA.Id
																			WHERE REA.id_detalle = ".$row_cabecera["Id"]."
																				AND is_paquete = 0
																				AND is_paquetecliente = 0
																		 ORDER BY EA.orden";

											if ($res_analisis = mysqli_query($enlace, $q_analisis)){
												if (mysqli_num_rows($res_analisis) > 0) {
													while($row_analisis = mysqli_fetch_array($res_analisis)){
														$id_analisis .= $row_analisis["Id"].', ';
													}
												}
											}

										// 2. Obtiene análisis de Paquetes Generales
											$q_paquetes = "SELECT C.abv,
																						C.descripcion AS CLASIFICACION,
																						REA.cod_analisis,
																						REA.total,
																						REA.is_paquete,
																						REA.is_paquetecliente
																			 FROM recepcion_ensayos_analisis REA
																						INNER JOIN tb_ensayos_analisisclasificaciones_paquetes P ON REA.cod_analisis = P.Id
																						INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
																			WHERE REA.id_detalle = ".$row_cabecera["Id"]."
																				AND REA.is_paquete = 1";

											if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
												if (mysqli_num_rows($res_paquetes) > 0) {
													while($row_paquetes = mysqli_fetch_array($res_paquetes)){
														// Armando la descripción de análisis por paquete
															$abv = '';
															$descripcion = '';

															$q_analisispaquete = "SELECT A.Id,
																													 A.abv,
																													 A.descripcion
																										  FROM tb_ensayos_analisisclasificaciones_paquetes_detalle D
																													 INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
																										 WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

															if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
																if (mysqli_num_rows($res_analisispaquete) > 0) {
																	while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
																		$id_analisis .= $row_analisispaquete["Id"].', ';
																		$abv .= $row_analisispaquete["abv"].' - ';
																		$descripcion .= $row_analisispaquete["descripcion"].' - ';
																	}
																}
															}

															if (strlen($abv) > 0){
																$abv = substr($abv, 0, -3);
																$descripcion = substr($descripcion, 0, -3);
															}

														// // Creando el query final
														// 	$q_analisis = "SELECT '(".$abv.")' AS abv,
														// 													".$row_paquetes["total"]." AS total";

														// 	if ($res_analisis = mysqli_query($enlace, $q_analisis)){
														// 		if (mysqli_num_rows($res_analisis) > 0) {
														// 			while($row_analisis = mysqli_fetch_array($res_analisis)){
														// 				$id_analisis .= '            '.$row_analisis["abv"].', ';
														// 			}
														// 		}
														// 	}
													}
												}
											}

										// 3. Obtiene análisis de Paquetes de Clientes
											$q_paquetes = "SELECT C.abv,
																						C.descripcion AS CLASIFICACION,
																						REA.cod_analisis,
																						REA.total,
																						REA.is_paquete,
																						REA.is_paquetecliente
																			 FROM recepcion_ensayos_analisis REA
																						INNER JOIN tb_ensayos_analisisclasificaciones_paquetesclientes P ON REA.cod_analisis = P.Id
																						INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
																			WHERE REA.id_detalle = ".$row_cabecera["Id"]."
																				AND REA.is_paquetecliente = 1";

											if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
												if (mysqli_num_rows($res_paquetes) > 0) {
													while($row_paquetes = mysqli_fetch_array($res_paquetes)){
														// Armando la descripción de análisis por paquete
															$abv = '';
															$descripcion = '';

															$q_analisispaquete = "SELECT A.Id,
																													 A.abv,
																													 A.descripcion
																										  FROM tb_ensayos_analisisclasificaciones_paquetesclientes_detalle D
																													 INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
																										 WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

															if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
																if (mysqli_num_rows($res_analisispaquete) > 0) {
																	while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
																		$id_analisis .= $row_analisispaquete["Id"].', ';
																		$abv .= $row_analisispaquete["abv"].' - ';
																		$descripcion .= $row_analisispaquete["descripcion"].' - ';
																	}
																}
															}

															if (strlen($abv) > 0){
																$abv = substr($abv, 0, -3);
																$descripcion = substr($descripcion, 0, -3);
															}

														// // Creando el query final
														// 	$q_analisis = "SELECT '(".$abv.")' AS abv,
														// 													".$row_paquetes["total"]." AS total";

														// 	if ($res_analisis = mysqli_query($enlace, $q_analisis)){
														// 		if (mysqli_num_rows($res_analisis) > 0) {
														// 			while($row_analisis = mysqli_fetch_array($res_analisis)){
														// 				$id_analisis .= '            '.$row_analisis["abv"].', ';
														// 			}
														// 		}
														// 	}
													}
												}
											}
									}
								}
							}

							if (strlen($id_analisis) > 0){
								$id_analisis = substr($id_analisis, 0, -2);
							}

						// Armando las cabeceras de análisis
							$arr_cabecera = array();

							if (strlen($id_analisis) > 0){
								$q_analisis = "SELECT abv,
																			CONCAT('|', LOWER(abv), '|') AS CAMPO_ANALISIS
																 FROM tb_ensayos_analisis
																WHERE Id IN (".$id_analisis.")
															 ORDER BY orden";

								if ($res_analisis = mysqli_query($enlace, $q_analisis)){
									if (mysqli_num_rows($res_analisis) > 0) {
										while($row_analisis = mysqli_fetch_array($res_analisis)){
											array_push($arr_cabecera, $row_analisis);
										}
									}
								}
							}

						// Terminando de setear la cabecera 1
							$html_cabecera1 .= '<th colspan="'.count($arr_cabecera).'" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
										        				Información de Análisis
										        			</th>';

						// Pintando cabeceras
							$c = 0;

							while ($c < count($arr_cabecera)){
								$html_cabecera2 .= '<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '	'.$arr_cabecera[$c]["abv"];
			      		$html_cabecera2 .= '</th>';

								$c ++;
							}

							$html = $html_cabecera1.$html_cabecera2.$html_cabecera3;
							$html .= ' <tbody>';

						// Obtiene datos
							$d = 1;
							$total_efectivo = 0;
							$total_efectivo_1 = 0; // Parque Industrial
							$total_efectivo_2 = 0; // Av. América
							$id_cabecera = 0;

							$q_datos = "SELECT DISTINCT
																 C.Id AS ID_RECEPCION,
																 md5(C.Id) AS MD5_IDRECEPCION,
																 S.des_sucursal,
																 C.cliente_documento,
																 CL.razon_social,
																 CONCAT(CL.telefono1, CASE WHEN LENGTH(TRIM(IFNULL(CL.telefono2, ''))) > 0 THEN CONCAT(' - ', CL.telefono2) ELSE '' END) AS TELEFONOS_CLIENTE,
																 C.recibo_codigo,
																 C.facturador_estado,
																 C.facturador_comprobante,
																 C.is_instruccion,
																 SUBSTRING(C.fechahora_recepcion, 1, 16) AS fechahora_recepcion,
																 SUBSTRING(C.fechahora_entrega, 1, 16) AS fechahora_entrega,
																 IFNULL(SUBSTRING(C.instruccion_fechahoraregistro, 1, 16), SUBSTRING(C.fechahora_recepcion, 1, 16)) AS fechahora_instruccion,
																 I.cod_mediopago,
																 I.is_factura,
																 IFNULL(I.is_pagado, 0) AS IS_PAGADO,
																 UPPER(IFNULL(MP_x.descripcion, '')) AS PAGADO_CODMEDIOPAGO,
																 I.pagado_fechahoraregistro,
																 I.pagado_usuarioregistro,
																 I.pagado_observacion,
																 UPPER(IFNULL(MP.descripcion, 'POR PAGAR')) AS MEDIO_PAGO,
																 CASE WHEN LENGTH(IFNULL(MP.descripcion, '')) = 0 THEN 1 ELSE 0 END AS IS_PORPAGAR,
																 MP.is_efectivo,

																 (SELECT SUM(D_x.exceso)
																		FROM recepcion_ensayos_detalle D_x
																	 WHERE D_x.id_cabecera = C.Id
																		 AND estado = 'A') AS EXCESO,

																 (SELECT SUM(EA.total)
																		FROM recepcion_ensayos_analisis EA
																	 WHERE EA.id_detalle IN (SELECT D_x.Id
																														 FROM recepcion_ensayos_detalle D_x
																														WHERE D_x.id_cabecera = C.Id)) AS TOTAL,

																 C.fechahora_recepcion,
																 C.usuario_registro,
																 C.is_temporal,
																 C.is_recepcioncanceled,
																 TCL.Id AS COD_TIPOCLIENTE,
																 C.tiene_recojo,

																 CASE WHEN C.tiene_recojo = 1 THEN 'S/ 10.00' ELSE '' END AS RECOJO,

																 IFNULL(C.is_ampliacion_de, 0) AS IS_AMPLIACION,
																 C.cliente_tienedscto,
																 C.dscto_porcentaje,
																 I.efectivo_ingresado,
																 C.cod_sucursal,
																 C.is_ventacredito,
																 C.facturador_fechavencimiento,
																 IFNULL(C.facturador_fechaemision, C.facturador_fechavencimiento) AS facturador_fechaemision,
																 I.pagado_codmediopago,
			                                                     
			                           (SELECT CASE WHEN COUNT(RED_x.Id) = 0 THEN 1 ELSE COUNT(RED_x.Id) END AS _COUNT_MUESTRAS
			                            	FROM recepcion_ensayos_detalle RED_x
			                             WHERE RED_x.id_cabecera = C.Id) AS _COUNT_MUESTRAS

														FROM recepcion_ensayos_cabecera C
																 LEFT JOIN recepcion_ensayos_detalle D ON C.Id = D.id_cabecera
																 LEFT JOIN tb_sucursal S ON C.cod_sucursal = S.Id
																 LEFT JOIN tb_clientes CL ON C.cliente_documento = CL.documento
																 LEFT JOIN tbconfig_tipocliente TCL ON CL.cod_tipocliente = TCL.Id
																 LEFT JOIN recepcion_instruccion I ON C.Id = I.id_cabecera
																 LEFT JOIN tbconfig_mediospago MP ON I.cod_mediopago = MP.Id
																 LEFT JOIN tbconfig_mediospago MP_x ON I.pagado_codmediopago = MP_x.Id
																 LEFT JOIN tbconfig_estadosmuestra EM ON D.cod_estadomuestra = EM.Id
																 LEFT JOIN tbconfig_envasesmuestra EVM ON D.cod_envasemuestra = EVM.Id
																 LEFT JOIN tbconfig_tiposmuestra TM ON D.cod_tipomuestra = TM.Id
																 LEFT JOIN tbconfig_ensayosmuestra ESM ON D.cod_ensayomuestra = ESM.Id
															 WHERE IFNULL(DATE(C.instruccion_fechahoraregistro), DATE(C.fechahora_recepcion)) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

					    if (strlen($filtro_cliente) > 0){
								$q_datos .= "   AND C.cliente_documento = '".$filtro_cliente."'";
							}

							if (strlen($filtro_sucursal) > 0){
								$q_datos .= "   AND C.cod_sucursal = ".$filtro_sucursal;
							}

							if (strlen(trim($filtro_mediospago)) > 0){
								$q_datos .= "   AND I.cod_mediopago LIKE '%".trim($filtro_mediospago)."%'";
							}

							if (strlen($filtro_listatipodocumento) > 0){
								if ($filtro_listatipodocumento == 1){
									$q_datos .= "   AND C.recibo_codigo LIKE '%".trim($filtro_documento)."%'";
								}
								else{
									$q_datos .= "   AND C.facturador_comprobante LIKE '%".trim($filtro_documento)."%'";
								}
							}

							if ($filtro_opt3 == 0){
								if ($filtro_opt1 == 1){
									$q_datos .= "   AND C.is_instruccion = 0";
								}
								else{
									$q_datos .= "   AND C.is_instruccion = 1";
								}
							}

							$q_datos .= " ORDER BY 11 DESC";

							if ($res_datos = mysqli_query($enlace, $q_datos)){
								if (mysqli_num_rows($res_datos) > 0) {
									$estado = 1;

									while($row_datos = mysqli_fetch_array($res_datos)){
										$html .= '<tr style="cursor: pointer; font-size: 14px;">';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
										$html .= '   '.$d;
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
										$html .= '   '.$row_datos["des_sucursal"];
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
										$html .= '   '.$row_datos["cliente_documento"];
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
										$html .= '   '.$row_datos["razon_social"];
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

										// if ($row_datos["is_recepcioncanceled"] == 0 && $row_datos["is_temporal"] == 0){
										if ($row_datos["is_temporal"] == 0){
											$html .= '  	<a class="success" href=""><font color="#4B94F2"><u> '.$row_datos["recibo_codigo"].'</u></font></a>';

											if ($row_datos["IS_AMPLIACION"] > 0){
												$html .= '   <label style="color: #dc3545; font-weight: bold; font-size: 12px;">* AMPLIACION *</label>';
											}
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

										if ($row_datos["is_recepcioncanceled"] == 0 && $row_datos["is_temporal"] == 0){
											$html .= '  	<a class="success" href=""><font color="#4B94F2"><u> '.$row_datos["facturador_comprobante"].'</u></font></a>';
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.substr($row_datos["facturador_fechaemision"], 0, 10);
										$html .= '  </td>';

										if ($row_datos["is_recepcioncanceled"] == 0){
											$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: '.(($row_datos["is_instruccion"] == 1) ? '#0099DD' : (($row_datos["is_temporal"] == 1) ? '#737373' : '#FF5F5D')).'; color: #ffffff;">';
											$html .= '   '.(($row_datos["is_instruccion"] == 1) ? 'INSTRUCCION' : (($row_datos["is_temporal"] == 1) ? 'EN REGISTRO' : 'R.A.'));
										}
										else{
											$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #737373; color: #ffffff;">';
											$html .= '  	VENTA CANCELADA';
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.$row_datos["fechahora_recepcion"];
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.$row_datos["fechahora_instruccion"];
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

										if ($row_datos["is_recepcioncanceled"] == 0 && $row_datos["is_temporal"] == 0){
											$html .= '   '.((strlen($row_datos["fechahora_entrega"]) == 0) ? 'POR CONFIRMAR' : $row_datos["fechahora_entrega"]);
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.$row_datos["RECOJO"];
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.((strlen($row_datos["EXCESO"]) > 0) ? number_format($row_datos["EXCESO"], 2, '.', '') : '');
										$html .= '  </td>';

										// Si tiene Dscto
											$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

											if ($row_datos["cliente_tienedscto"] == 1){
												$html .= '   '.$row_datos["dscto_porcentaje"].' %';
											}

											$html .= '  </td>';

										// Sub Total
											$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.number_format($row_datos["TOTAL"], 2, '.', '');
											$html .= '  </td>';

										// Calcula el Total de la Venta
											$total_x = (($row_datos["tiene_recojo"] == 1) ? 10 : 0) +
																 ((strlen($row_datos["EXCESO"]) > 0) ? $row_datos["EXCESO"] : 0) +
																 $row_datos["TOTAL"];

											if ($row_datos["cliente_tienedscto"] == 1){
												$total_x = $total_x * ((100 - $row_datos["dscto_porcentaje"]) / 100);
											}

											$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.number_format($total_x, 2, '.', '');
											$html .= '  </td>';

										if ($row_datos["is_recepcioncanceled"] == 0 && $row_datos["is_temporal"] == 0){
											$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; '.(($row_datos["is_temporal"] == 1) ? '' :(($row_datos["IS_PORPAGAR"] == 1) ? 'background-color: #FF5F5D; color: #ffffff;' : (($row_datos["cod_mediopago"] == 3) ? 'background-color: #89D99D; color: #ffffff;' : (($row_datos["cod_mediopago"] == 1) ? 'background-color: #F2BE22; color: #ffffff;' : '')))).'">';
											$html .= '   '.(($row_datos["is_temporal"] == 1) ? '' : (($row_datos["IS_PORPAGAR"] == 1 && $row_datos["is_ventacredito"] == 1) ? 'CREDITO POR CONFIRMAR' : $row_datos["MEDIO_PAGO"]));

											if ($row_datos["IS_PORPAGAR"] == 1 && $row_datos["IS_PAGADO"] == 1){
												$html .= '   </br><label style="color: #212226;">* PAGADO *</label>';
											}
										}
										else{
											$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

										if ($row_datos["is_recepcioncanceled"] == 0){
											$total_efectivo_x = ((strlen($row_datos["efectivo_ingresado"]) == 0) ? '' : (($row_datos["efectivo_ingresado"] > $total_x) ? number_format($total_x, 2, '.', '') : number_format($row_datos["efectivo_ingresado"], 2, '.', '')));

											$html .= '   '.$total_efectivo_x;

											if ($row_datos["IS_PORPAGAR"] == 1 || $row_datos["cod_mediopago"] == 1){
												if ($row_datos["pagado_codmediopago"] == 3 && $row_datos["IS_PORPAGAR"] == 0){
													$html .= '   '.number_format($total_x, 2, '.', '');

													$total_efectivo += $total_x;

													if ($row_datos["cod_sucursal"] == 1){
														$total_efectivo_1 += $total_x;
													}
													else{
														$total_efectivo_2 += $total_x;
													}
												}
											}

											if (strlen(trim($total_efectivo_x)) > 0){
												$total_efectivo += $total_efectivo_x;

												if ($row_datos["cod_sucursal"] == 1){
													$total_efectivo_1 += $total_efectivo_x;
												}
												else{
													$total_efectivo_2 += $total_efectivo_x;
												}
											}
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.((strlen($row_datos["efectivo_ingresado"]) == 0) ? '' : ((round($row_datos["efectivo_ingresado"], 2) >= round($total_x, 2)) ? '' : number_format($total_x - $row_datos["efectivo_ingresado"], 2, '.', '')));
										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" id="td_pagado_'.$d.'_1" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

										if ($row_datos["IS_PORPAGAR"] == 1 || $row_datos["cod_mediopago"] == 1){
											$html .= '   '.$row_datos["PAGADO_CODMEDIOPAGO"];
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" id="td_pagado_'.$d.'_2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										
										if ($row_datos["IS_PORPAGAR"] == 1 || $row_datos["cod_mediopago"] == 1){
											$html .= '   '.$row_datos["pagado_fechahoraregistro"];
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" id="td_pagado_'.$d.'_3" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										
										if ($row_datos["IS_PORPAGAR"] == 1 || $row_datos["cod_mediopago"] == 1){
											$html .= '   '.strtolower($row_datos["pagado_usuarioregistro"]);
										}

										$html .= '  </td>';

										$html .= '  <td rowspan="'.$row_datos["_COUNT_MUESTRAS"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.strtolower($row_datos["usuario_registro"]);
										$html .= '  </td>';

										// Obtiene los datos de Muestras por ventas
											$m = 1;

											$q_muestras = "SELECT C.Id AS ID_RECEPCION,
																				 md5(C.Id) AS MD5_IDRECEPCION,
																				 D.Id,
																				 D.cod_interno,
																				 S.des_sucursal,
																				 C.cliente_documento,
																				 CL.razon_social,
																				 CONCAT(CL.telefono1, CASE WHEN LENGTH(TRIM(IFNULL(CL.telefono2, ''))) > 0 THEN CONCAT(' - ', CL.telefono2) ELSE '' END) AS TELEFONOS_CLIENTE,
																				 C.recibo_codigo,
																				 C.facturador_comprobante,
																				 C.is_instruccion,
																				 C.is_temporal,
																				 C.is_recepcioncanceled,
																				 SUBSTRING(C.fechahora_recepcion, 1, 16) AS fechahora_recepcion,
																				 SUBSTRING(C.fechahora_entrega, 1, 16) AS fechahora_entrega,
																				 IFNULL(SUBSTRING(I.pagado_fechahoraregistro, 1, 16), SUBSTRING(C.fechahora_recepcion, 1, 16)) AS fechahora_instruccion,
																				 I.is_factura,
																				 UPPER(IFNULL(MP.descripcion, 'POR PAGAR')) AS MEDIO_PAGO,
																				 D.nombre_muestra,
																				 D.peso_muestra,
																				 EM.descripcion AS ESTADO_MUESTRA,
																				 EVM.descripcion AS ENVASE_MUESTRA,
																				 TM.descripcion AS TIPO_MUESTRA,
																				 ESM.descripcion AS ENSAYO_MUESTRA,
																				 D.exceso,
																				 D.observacion,
																				 D.is_informeentregado,
																				 D.informeentregado_fechahoraregistro,
																				 D.informeentregado_usuarioregistro,
																				 C.facturador_fechavencimiento,

																				 (SELECT SUM(EA.total)
																						FROM recepcion_ensayos_analisis EA
																					 WHERE EA.id_detalle IN (SELECT D_x.Id
																																		 FROM recepcion_ensayos_detalle D_x
																																		WHERE D_x.id_cabecera = C.Id
																																			AND D_x.Id = D.Id)) AS TOTAL

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
																			 WHERE C.Id = ".$row_datos["ID_RECEPCION"]."
																	ORDER BY 14";

											if ($res_muestras = mysqli_query($enlace, $q_muestras)){
												if (mysqli_num_rows($res_muestras) > 0) {
													while($row_muestras = mysqli_fetch_array($res_muestras)){
														if ($m > 1){
															$html .= '<tr style="cursor: pointer; font-size: 14px;">';
														}

														$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
														$html .= '   '.$row_muestras["cod_interno"];
														$html .= '  </td>';

														$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
														$html .= '   '.$row_muestras["nombre_muestra"];
														$html .= '  </td>';

							              // Obteniendo análisis
							              	$analisis = '';

							              	// 1. Obtiene análisis de Precios Generales
																$q_analisis = "SELECT CONCAT('|', LOWER(EA.abv), '|') AS abv
																								 FROM recepcion_ensayos_analisis REA
																											INNER JOIN tb_ensayos_analisisclasificaciones_detalle ACD ON REA.cod_analisis = ACD.Id
																											INNER JOIN tb_ensayos_analisisclasificaciones C ON ACD.cod_clasificacion = C.Id
																											INNER JOIN tb_ensayos_analisis EA ON ACD.cod_analisis = EA.Id
																								WHERE REA.id_detalle = ".$row_muestras["Id"]."
																									AND is_paquete = 0
																									AND is_paquetecliente = 0
																							 ORDER BY EA.orden";

																if ($res_analisis = mysqli_query($enlace, $q_analisis)){
																	if (mysqli_num_rows($res_analisis) > 0) {
																		while($row_analisis = mysqli_fetch_array($res_analisis)){
																			$analisis .= $row_analisis["abv"];
																		}
																	}
																}

															// 2. Obtiene análisis de Paquetes Generales
																$q_paquetes = "SELECT C.abv,
																											C.descripcion AS CLASIFICACION,
																											REA.cod_analisis,
																											REA.total,
																											REA.is_paquete,
																											REA.is_paquetecliente
																								 FROM recepcion_ensayos_analisis REA
																											INNER JOIN tb_ensayos_analisisclasificaciones_paquetes P ON REA.cod_analisis = P.Id
																											INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
																								WHERE REA.id_detalle = ".$row_muestras["Id"]."
																									AND REA.is_paquete = 1";

																if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
																	if (mysqli_num_rows($res_paquetes) > 0) {
																		while($row_paquetes = mysqli_fetch_array($res_paquetes)){
																			// Armando la descripción de análisis por paquete
																				$abv = '';
																				$descripcion = '';

																				$q_analisispaquete = "SELECT CONCAT('|', LOWER(A.abv), '|') AS abv
																															 FROM tb_ensayos_analisisclasificaciones_paquetes_detalle D
																																		INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
																															WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

																				if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
																					if (mysqli_num_rows($res_analisispaquete) > 0) {
																						while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
																							$analisis .= $row_analisispaquete["abv"];
																							// $descripcion .= $row_analisispaquete["descripcion"].' - ';
																						}
																					}
																				}

																			// 	if (strlen($abv) > 0){
																			// 		$abv = substr($abv, 0, -3);
																			// 		$descripcion = substr($descripcion, 0, -3);
																			// 	}

																			// // Creando el query final
																			// 	$q_analisis = "SELECT '(".$abv.")' AS abv,
																			// 													".$row_paquetes["total"]." AS total";

																			// 	if ($res_analisis = mysqli_query($enlace, $q_analisis)){
																			// 		if (mysqli_num_rows($res_analisis) > 0) {
																			// 			while($row_analisis = mysqli_fetch_array($res_analisis)){
																			// 				$analisis .= '            '.$row_analisis["abv"].', ';
																			// 			}
																			// 		}
																			// 	}
																		}
																	}
																}

															// 3. Obtiene análisis de Paquetes de Clientes
																$q_paquetes = "SELECT C.abv,
																											C.descripcion AS CLASIFICACION,
																											REA.cod_analisis,
																											REA.total,
																											REA.is_paquete,
																											REA.is_paquetecliente
																								 FROM recepcion_ensayos_analisis REA
																											INNER JOIN tb_ensayos_analisisclasificaciones_paquetesclientes P ON REA.cod_analisis = P.Id
																											INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
																								WHERE REA.id_detalle = ".$row_muestras["Id"]."
																									AND REA.is_paquetecliente = 1";

																if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
																	if (mysqli_num_rows($res_paquetes) > 0) {
																		while($row_paquetes = mysqli_fetch_array($res_paquetes)){
																			// Armando la descripción de análisis por paquete
																				$abv = '';
																				$descripcion = '';

																				$q_analisispaquete = "SELECT CONCAT('|', LOWER(A.abv), '|') AS abv
																															 FROM tb_ensayos_analisisclasificaciones_paquetesclientes_detalle D
																																		INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
																															WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

																				if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
																					if (mysqli_num_rows($res_analisispaquete) > 0) {
																						while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
																							$analisis .= $row_analisispaquete["abv"];
																							// $descripcion .= $row_analisispaquete["descripcion"].' - ';
																						}
																					}
																				}

																			// 	if (strlen($abv) > 0){
																			// 		$abv = substr($abv, 0, -3);
																			// 		$descripcion = substr($descripcion, 0, -3);
																			// 	}

																			// // Creando el query final
																			// 	$q_analisis = "SELECT '(".$abv.")' AS abv,
																			// 													".$row_paquetes["total"]." AS total";

																			// 	if ($res_analisis = mysqli_query($enlace, $q_analisis)){
																			// 		if (mysqli_num_rows($res_analisis) > 0) {
																			// 			while($row_analisis = mysqli_fetch_array($res_analisis)){
																			// 				$analisis .= '            '.$row_analisis["abv"].', ';
																			// 			}
																			// 		}
																			// 	}
																		}
																	}
																}

																// if (strlen($analisis) > 0){
																// 	$analisis = substr($analisis, 0, -2);
																// }

															$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
															$html .= '            '.$analisis;
															$html .= '</td>';

							              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
							              $html .= '   '.$row_muestras["ESTADO_MUESTRA"];
							              $html .= '  </td>';

							              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
							              $html .= '   '.$row_muestras["TIPO_MUESTRA"];
							              $html .= '  </td>';

														$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
														$html .= '   '.$row_muestras["exceso"];
														$html .= '  </td>';

														$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
														$html .= '   '.number_format($row_muestras["TOTAL"], 2, '.', '');
														$html .= '  </td>';

														// Pintando cabeceras
															$c = 0;
															$campo = '';

															while ($c < count($arr_cabecera)){
										        		$campo = $arr_cabecera[$c]["CAMPO_ANALISIS"];

										        		$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; '.(($campo == '|new au/ag|') ? 'background-color: #F2E30C' : '').'">';

										        		if (strpos($analisis, $campo) !== false){
																	$html .= '	x';
										        		}

										        		$html .= '</td>';

																$c ++;
															}

														if ($m > 1){
															$html .= '</tr>';
														}

														$m ++;
													}
												}
											}

											if ($m == 1){
												$html .= '</tr>';
											}

										$d ++;
									}
								}
							}

						$html .= '</tbody>';

						echo $html;

						?>

					</table>
</html>