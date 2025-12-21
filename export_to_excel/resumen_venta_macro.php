<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Resumen de Ventas - Para Macro.xls');
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
						$filtro_origen = $_GET["filtro_origen"];
						$fecha_inicio = $_GET["fecha_inicio"];
						$fecha_fin = $_GET["fecha_fin"];
						$filtro_sucursal = $_GET["filtro_sucursal"];
						$filtro_nommuestra = $_GET["filtro_nommuestra"];
						$filtro_listatipodocumento = $_GET["filtro_listatipodocumento"];
						$filtro_documento = $_GET["filtro_documento"];
						$filtro_opt1 = $_GET["filtro_opt1"];
						$filtro_opt2 = $_GET["filtro_opt2"];
						$filtro_opt3 = $_GET["filtro_opt3"];

					// Obteniendo datos de Análisis por cada venta
						$id_analisis = '';

						$q_cabecera = "SELECT D.Id
														 FROM recepcion_ensayos_cabecera C
																  INNER JOIN recepcion_ensayos_detalle D ON C.Id = D.id_cabecera
																	LEFT JOIN recepcion_instruccion I ON C.Id = I.id_cabecera
																	LEFT JOIN tbconfig_mediospago MP ON I.cod_mediopago = MP.Id
														WHERE /*IFNULL(DATE(I.pagado_fechahoraregistro), DATE(C.fechahora_recepcion)) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'*/
																	DATE(C.instruccion_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
				                      AND C.is_recepcioncanceled = 0
				                      AND C.is_instruccion = 1";

						if (strlen($filtro_sucursal) > 0){
							$q_cabecera .= "   AND C.cod_sucursal = ".$filtro_sucursal;
						}

						if (strlen(trim($filtro_nommuestra)) > 0){
							$q_cabecera .= "   AND D.nombre_muestra LIKE '%".trim($filtro_nommuestra)."%'";
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

					// Pintando cabeceras
						$c = 0;
						$html_cabeceras = '';

						while ($c < count($arr_cabecera)){
							$html_cabeceras .= '<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
	        		$html_cabeceras .= '	'.$arr_cabecera[$c]["abv"];
	        		$html_cabeceras .= '</th>';

							$c ++;
						}

				?>

					<font size = "3"><b>
						RESUMEN DE VENTAS - PARA MACRO
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
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				Código Interno
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Item
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Fecha Hora Ingreso
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Fecha Hora Instrucción
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Cliente
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Código Muestra
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Contacto Muestra
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Responsable
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Presentación Muestra
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Tipo Muestra
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Característica Muestra
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Hora Ingreso
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Fecha Programada
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Hora Programada
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Orden de Trabajo (O/T)
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Teléfono
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Análisis
	        			</th>

	        			<?php

	        				// Agregando cabeceras
	        					echo $html_cabeceras;

	        			?>
	        		</tr>
	        	</thead>

	        	<tbody>

							<?php
								$d = 1;
								$html = '';

								$q_datos = "SELECT C.Id AS ID_RECEPCION,
				                           D.Id,
				                           D.cod_interno,
				                           S.des_sucursal,
				                           C.cliente_documento,
				                           CL.razon_social,
				                           C.entregado_por,
				                           C.recibo_codigo,
				                           C.facturador_comprobante,
				                           C.is_instruccion,
																	 C.is_temporal,
																	 C.is_recepcioncanceled,
				                           SUBSTRING(C.fechahora_recepcion, 1, 16) AS fechahora_recepcion,
				                           SUBSTRING(C.fechahora_entrega, 1, 16) AS fechahora_entrega,
													 				 SUBSTRING(C.instruccion_fechahoraregistro, 1, 16) AS instruccion_fechahoraregistro,
				                           MP.descripcion AS MEDIO_PAGO,
				                           D.nombre_muestra,
				                           D.peso_muestra,
				                           EM.descripcion AS ESTADO_MUESTRA,
				                           EVM.descripcion AS ENVASE_MUESTRA,
				                           TM.descripcion AS TIPO_MUESTRA,
				                           ESM.descripcion AS ENSAYO_MUESTRA,
				                           D.observacion,
				                           CONCAT(CL.telefono1, CASE WHEN LENGTH(TRIM(IFNULL(CL.telefono2, ''))) > 0 THEN CONCAT('/', CL.telefono2) ELSE '' END) AS TELEFONOS_CLIENTE
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
				                         WHERE DATE(C.instruccion_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
				                         	 AND C.is_recepcioncanceled = 0
						                       AND C.is_instruccion = 1
						                       AND MP.descripcion IS NOT NULL";

				        if (strlen($filtro_sucursal) > 0){
				          $q_datos .= "   AND C.cod_sucursal = ".$filtro_sucursal;
				        }

				        if (strlen(trim($filtro_nommuestra)) > 0){
				          $q_datos .= "   AND D.nombre_muestra LIKE '%".trim($filtro_nommuestra)."%'";
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

				        $q_datos .= " UNION

				        						SELECT C.Id AS ID_RECEPCION,
				                           D.Id,
				                           D.cod_interno,
				                           S.des_sucursal,
				                           C.cliente_documento,
				                           CL.razon_social,
				                           C.entregado_por,
				                           C.recibo_codigo,
				                           C.facturador_comprobante,
				                           C.is_instruccion,
																	 C.is_temporal,
																	 C.is_recepcioncanceled,
				                           SUBSTRING(C.fechahora_recepcion, 1, 16) AS fechahora_recepcion,
				                           SUBSTRING(C.fechahora_entrega, 1, 16) AS fechahora_entrega,
													 				 SUBSTRING(C.instruccion_fechahoraregistro, 1, 16) AS instruccion_fechahoraregistro,
				                           MP.descripcion AS MEDIO_PAGO,
				                           D.nombre_muestra,
				                           D.peso_muestra,
				                           EM.descripcion AS ESTADO_MUESTRA,
				                           EVM.descripcion AS ENVASE_MUESTRA,
				                           TM.descripcion AS TIPO_MUESTRA,
				                           ESM.descripcion AS ENSAYO_MUESTRA,
				                           D.observacion,
				                           CONCAT(CL.telefono1, CASE WHEN LENGTH(TRIM(IFNULL(CL.telefono2, ''))) > 0 THEN CONCAT('/', CL.telefono2) ELSE '' END) AS TELEFONOS_CLIENTE
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
				                         WHERE DATE(I.pagado_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
				                         	 AND C.is_recepcioncanceled = 0
						                       AND C.is_instruccion = 1
						                       AND MP.descripcion IS NULL";

				        if (strlen($filtro_sucursal) > 0){
				          $q_datos .= "   AND C.cod_sucursal = ".$filtro_sucursal;
				        }

				        if (strlen(trim($filtro_nommuestra)) > 0){
				          $q_datos .= "   AND D.nombre_muestra LIKE '%".trim($filtro_nommuestra)."%'";
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

				        $q_datos .= " ORDER BY 3";

								if ($res_datos = mysqli_query($enlace, $q_datos)){
				          if (mysqli_num_rows($res_datos) > 0) {
				            while($row_datos = mysqli_fetch_array($res_datos)){
				              $html .= '<tr style="cursor: pointer; font-size: 14px;">';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
				              $html .= '   '.$row_datos["cod_interno"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.substr($row_datos["cod_interno"], 7);
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.substr($row_datos["fechahora_recepcion"], 0, 10);
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.substr($row_datos["instruccion_fechahoraregistro"], 0, 10);
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.$row_datos["razon_social"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.$row_datos["nombre_muestra"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				              $html .= '   '.$row_datos["entregado_por"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				              // $html .= '   '.$row_datos["razon_social"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				              $html .= '   '.$row_datos["ENVASE_MUESTRA"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				              $html .= '   '.$row_datos["TIPO_MUESTRA"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				              $html .= '   '.$row_datos["ESTADO_MUESTRA"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.substr($row_datos["instruccion_fechahoraregistro"], 11, 5);
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.substr($row_datos["fechahora_entrega"], 0, 10);
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.substr($row_datos["fechahora_entrega"], 11, 5);
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.$row_datos["recibo_codigo"];
				              $html .= '  </td>';

				              $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
				              $html .= '   '.$row_datos["TELEFONOS_CLIENTE"];
				              $html .= '  </td>';

				              // Obteniendo análisis
				              	$analisis = '';

				              	// 1. Obtiene análisis de Precios Generales
													$q_analisis = "SELECT CONCAT('|', LOWER(EA.abv), '|') AS abv
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
																					WHERE REA.id_detalle = ".$row_datos["Id"]."
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
																					WHERE REA.id_detalle = ".$row_datos["Id"]."
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

												$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
												$html .= '            '.$analisis;
												$html .= '</td>';

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