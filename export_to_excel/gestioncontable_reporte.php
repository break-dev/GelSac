<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Reporte Contable.xls');
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
						$filtro_fechas = $_GET["filtro_fechas"];
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
					REPORTE CONTABLE
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
											        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
											        				N°
											        			</th>

											        			<th colspan="5" rowspan="1" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Información del Comprobante
											        			</th>

											        			<th colspan="8" rowspan="1" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Información de la Venta
											        			</th>';

							$html_cabecera2 .= '</tr>

											        		<tr style="font-size: 14px;">
											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Tipo
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
											        				N° Comprobante
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Fecha Emisión
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Fecha Vencimiento
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											        				Usuario
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Documento
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
											        				Razón Social
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 210px;">
											        				N° Requerimiento
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Rubro
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Moneda
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Sub Total
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				IGV
											        			</th>

											        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											        				Total
											        			</th>';

			  			$html_cabecera3 .= '</tr>
											        	</thead>';

						// Obteniendo los medio de pago según filtros
							$arr_cabecera = array();
							$tiene_especial = 0;

							$q_cabecera = "SELECT DATOS.COD_MEDIOPAGO,
																		MP.descripcion AS DES_MEDIOPAGO
														   FROM (SELECT DISTINCT
																						CASE WHEN RI.cod_mediopago = 0
																					    THEN RI.pagado_codmediopago
																					  ELSE RI.cod_mediopago
																					  END AS COD_MEDIOPAGO
																			 FROM recepcion_ensayos_cabecera C
																						LEFT JOIN facturacion_creditospago CP ON C.facturador_comprobante = CP.num_comprobante
																						LEFT JOIN recepcion_instruccion RI ON C.Id = RI.id_cabecera
																			      LEFT JOIN tbconfig_mediospago MP ON RI.cod_mediopago = MP.Id
																			      LEFT JOIN tbconfig_mediospago MP_I ON RI.pagado_codmediopago = MP_I.Id
																			WHERE LENGTH(TRIM(C.facturador_comprobante)) > 0";

							if ($filtro_opt3 == 0){
								if ($filtro_opt1 == 1){
									$q_cabecera .= "   AND CP.cod_mediopago IS NULL";
								}
								else{
									$q_cabecera .= "   AND LENGTH(TRIM(CP.cod_mediopago)) > 0";
								}
							}

							if ($filtro_fechas == 1){
								$q_cabecera .= "   AND DATE(C.facturador_fechaemision) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
							}
							else{
								$q_cabecera .= "   AND DATE(C.facturador_fechavencimiento) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
							}

							if (strlen($filtro_cliente) > 0){
								$q_cabecera .= "   AND C.cliente_documento = '".$filtro_cliente."'";
							}

							if (strlen($filtro_sucursal) > 0){
								$q_cabecera .= "   AND C.cod_sucursal = ".$filtro_sucursal;
							}

							if (strlen(trim($filtro_mediospago)) > 0){
								$q_cabecera .= "   AND RI.cod_mediopago LIKE '%".trim($filtro_mediospago)."%'";
							}

							if (strlen(trim($filtro_documento)) > 0){
								$q_cabecera .= "   AND C.facturador_comprobante LIKE '%".trim($filtro_documento)."%'";
							}

							$q_cabecera .= ") AS DATOS
													 		       INNER JOIN tbconfig_mediospago MP ON TRIM(DATOS.COD_MEDIOPAGO) = MP.Id
													 		ORDER BY MP.orden_reportecontable";

							if ($res_cabecera = mysqli_query($enlace, $q_cabecera)){
								if (mysqli_num_rows($res_cabecera) > 0) {
									while($row_cabecera = mysqli_fetch_array($res_cabecera)){
										array_push($arr_cabecera, $row_cabecera);

										if ($row_cabecera["COD_MEDIOPAGO"] == 5){
											$tiene_especial = 1;
										}
									}
								}
							}

						// Terminando de setear la cabecera 1
							$html_cabecera1 .= '<th colspan="'.count($arr_cabecera) + (($tiene_especial == 1) ? 1 : 0).'" rowspan="1" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										        				Importe del Servicio
										        			</th>

										        			<th colspan="5" rowspan="1" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
										        				Información de Pagos</br><label style="color: #FFDB17;">Para CRÉDITOS</label>
										        			</th>';

						// Pintando cabeceras
							$c = 0;
							$tiene_especial = 0;
							$tiene_especial_x = 0;

							while ($c < count($arr_cabecera)){
								if ($arr_cabecera[$c]["COD_MEDIOPAGO"] == 5){
									$tiene_especial = 1;
									$tiene_especial_x = 1;
								}
								else{
									$tiene_especial = 0;
								}

								$html_cabecera2 .= '	<th '.(($tiene_especial == 1) ? 'colspan="2" rowspan="1"' : 'rowspan="2"').' style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '		'.$arr_cabecera[$c]["DES_MEDIOPAGO"];
			      		$html_cabecera2 .= '	</th>';

								$c ++;
							}

							$html_cabecera2 .= '<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										        				Medio Pago
										        			</th>

										        			<th rowspan="1" colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
										        				Especial
										        			</th>

										        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
										        				Fecha Hora Pago
										        			</th>

										        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										        				Usuario Pago
										        			</th>';

							if ($tiene_especial_x == 1){
								$html_cabecera2 .= '<tr style="font-size: 14px;">';
								$html_cabecera2 .= '	<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '		Efectivo';
			      		$html_cabecera2 .= '	</th>';

			      		$html_cabecera2 .= '	<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '		Saldo';
			      		$html_cabecera2 .= '	</th>';

			      		$html_cabecera2 .= '	<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '		Efectivo';
			      		$html_cabecera2 .= '	</th>';

			      		$html_cabecera2 .= '	<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '		Saldo';
			      		$html_cabecera2 .= '	</th>';
								$html_cabecera2 .= '</tr>';
							}
							else{
								$html_cabecera2 .= '<tr style="font-size: 14px;">';
								$html_cabecera2 .= '	<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '		Efectivo';
			      		$html_cabecera2 .= '	</th>';

			      		$html_cabecera2 .= '	<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">';
			      		$html_cabecera2 .= '		Saldo';
			      		$html_cabecera2 .= '	</th>';
								$html_cabecera2 .= '</tr>';
							}

							$html = $html_cabecera1.$html_cabecera2.$html_cabecera3;
							$html .= ' <tbody>';

						// Obtiene datos
							$d = 1;
							$id_cabecera = 0;

							$q_datos = "SELECT DISTINCT
																 CASE WHEN SUBSTRING(C.facturador_comprobante, 1, 2) = 'FA'
																 	 THEN 'FACTURA'
															   ELSE 'BOLETA' END AS TIPO,

																 C.facturador_comprobante,
															   C.facturador_fechaemision,
															   C.facturador_fechavencimiento,
															        
															   CASE WHEN LENGTH(TRIM(C.facturador_usuarioemision)) = 0
															     THEN C.usuario_registro
															   ELSE C.facturador_usuarioemision END AS USUARIO_COMPROBANTE,

															   C.cliente_documento,
															   C.cliente_razonsocial,
															   IFNULL(C.facturador_moneda, 'S/') AS MONEDA,
															        
															   (SELECT ROUND(SUM(total), 2) AS _TOTAL
															      FROM recepcion_ensayos_detallefactura
															     WHERE id_cabecera IN (SELECT C_x.Id
																													 FROM recepcion_ensayos_cabecera C_x
															                            WHERE DATE(C_x.fechahora_recepcion) > '2023-03-28'
															                              AND C_x.facturador_comprobante = C.facturador_comprobante)) AS COMPROBANTE_TOTAL,

																 CASE WHEN RI.cod_mediopago = 0
																   THEN RI.pagado_codmediopago
																 ELSE RI.cod_mediopago
																 END AS COD_MEDIOPAGOCABECERA,
															                                 
																 CASE WHEN RI.cod_mediopago = 0
															     THEN RI.pagado_codmediopago
															   ELSE CASE WHEN RI.cod_mediopago = 1
			                             		  THEN CP.cod_mediopago
			                                  ELSE RI.cod_mediopago
			                           END END AS COD_MEDIOPAGO,
															        
															   CASE WHEN RI.cod_mediopago = 0
															     THEN MP_I.descripcion
															   ELSE CASE WHEN RI.cod_mediopago = 1
															          THEN MP_x.descripcion
															        ELSE MP.descripcion
															   END END AS MEDIO_PAGO,

															   RI.efectivo_ingresado AS EFECTIVO_RECEPCION,
															   RI.pagado_fechahoraregistro,
																 RI.pagado_usuarioregistro,

																 CASE WHEN LENGTH(IFNULL(MP_x.descripcion, '')) = 0 THEN 0 ELSE 1 END AS IS_CREDITOPAGADO,

																 CP.cod_mediopago AS CREDITOPAGO_CODMEDIOPAGO,
																 MP_x.descripcion AS CREDITOPAGO_MEDIOPAGO,
																 CP.efectivo_ingresado AS EFECTIVO_CREDITOSPAGO,
																 CP.fechahora_registro AS CREDITOPAGO_FECHAHORA,
															   CP.usuario_registro AS CREDITOPAGO_USUARIO,
															   C.is_recepcioncanceled,
															   C.cliente_tienedscto,
																 C.dscto_porcentaje,

															   CASE WHEN LENGTH(IFNULL(MP.descripcion, '')) = 0 THEN 1 ELSE 0 END AS IS_PORPAGAR

													  FROM recepcion_ensayos_cabecera C
																 LEFT JOIN facturacion_creditospago CP ON C.facturador_comprobante = CP.num_comprobante
																 LEFT JOIN recepcion_instruccion RI ON C.Id = RI.id_cabecera
													       LEFT JOIN tbconfig_mediospago MP ON RI.cod_mediopago = MP.Id
													       LEFT JOIN tbconfig_mediospago MP_I ON RI.pagado_codmediopago = MP_I.Id
													       LEFT JOIN tbconfig_mediospago MP_x ON CP.cod_mediopago = MP_x.Id
													 WHERE LENGTH(TRIM(C.facturador_comprobante)) > 0";

							if ($filtro_opt3 == 0){
								if ($filtro_opt1 == 1){
									$q_datos .= "   AND CP.cod_mediopago IS NULL";
								}
								else{
									$q_datos .= "   AND LENGTH(TRIM(CP.cod_mediopago)) > 0";
								}
							}

							if ($filtro_fechas == 1){
								$q_datos .= "   AND DATE(C.facturador_fechaemision) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
							}
							else{
								$q_datos .= "   AND DATE(C.facturador_fechavencimiento) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
							}

							if (strlen($filtro_cliente) > 0){
								$q_datos .= "   AND C.cliente_documento = '".$filtro_cliente."'";
							}

							if (strlen($filtro_sucursal) > 0){
								$q_datos .= "   AND C.cod_sucursal = ".$filtro_sucursal;
							}

							if (strlen(trim($filtro_mediospago)) > 0){
								$q_datos .= "   AND RI.cod_mediopago LIKE '%".trim($filtro_mediospago)."%'";
							}

							if (strlen(trim($filtro_documento)) > 0){
								$q_datos .= "   AND C.facturador_comprobante LIKE '%".trim($filtro_documento)."%'";
							}

							if ($filtro_fechas == 1){
								$q_datos .= " ORDER BY 1 DESC, facturador_fechaemision";
							}
							else{
								$q_datos .= " ORDER BY 1 DESC, facturador_fechavencimiento";
							}

							if ($res_datos = mysqli_query($enlace, $q_datos)){
								if (mysqli_num_rows($res_datos) > 0) {
									$estado = 1;

									while($row_datos = mysqli_fetch_array($res_datos)){
										$html .= '<tr style="cursor: pointer; font-size: 14px;">';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
										$html .= '   '.$d;
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.$row_datos["TIPO"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
										$html .= '  	'.$row_datos["facturador_comprobante"];

										if ($row_datos["is_recepcioncanceled"] == 1){
											$html .= '  		</br><label style="color: #dc3545; font-size: 12px;">';
											$html .= '  			Venta Anulada';
											$html .= '  		<label>';
										}

										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.substr($row_datos["facturador_fechaemision"], 0, 10);
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.substr($row_datos["facturador_fechavencimiento"], 0, 10);
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.$row_datos["USUARIO_COMPROBANTE"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.$row_datos["cliente_documento"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
										$html .= '   '.$row_datos["cliente_razonsocial"];
										$html .= '  </td>';

										// Obtiene él o los Requerimientos Asociados
											$num_requerimientos = '';
											$count_req = 0;

											$q_requerimiento = "SELECT cod_interno_preliminar
																					  FROM recepcion_ensayos_cabecera
																					 WHERE DATE(fechahora_recepcion) > '2023-03-28'
																					   AND facturador_comprobante = '".$row_datos["facturador_comprobante"]."'
																					ORDER BY 1";

											if ($res_requerimiento = mysqli_query($enlace, $q_requerimiento)){
												if (mysqli_num_rows($res_requerimiento) > 0) {
													while($row_requerimiento = mysqli_fetch_array($res_requerimiento)){
														$num_requerimientos .= $row_requerimiento["cod_interno_preliminar"].' | ';

														$count_req ++;
													}
												}
											}

											if (strlen($num_requerimientos) > 0){
												$num_requerimientos = substr($num_requerimientos, 0, -3);
											}

											$html .= '	<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '  	'.((strpos($num_requerimientos, '|') !== false) ? 'Req. Múltiple (<label style="font-size: 12px; font-weight: bold;">x '.$count_req.'</label>)' : $num_requerimientos);

											if (strpos($num_requerimientos, '|') !== false){
												$html .= '		</br><label style="color: #4b94f2; font-size: 12px;">';
												$html .= '			<i>'.$num_requerimientos."</i>";
												$html .= '		</label>';
											}

											$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
										$html .= '  	ANÁLISIS';
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.$row_datos["MONEDA"];
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

										$total_x = $row_datos["COMPROBANTE_TOTAL"];
										$dscto_x = ((100 - $row_datos["dscto_porcentaje"]) / 100);

										if ($row_datos["cliente_tienedscto"] == 1){
											$total_x = $total_x * $dscto_x;
										}

										$html .= '   '.number_format(($total_x / 1.18), 2, '.', ',');

										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
										$html .= '   '.number_format($total_x - ($total_x / 1.18), 2, '.', ',');
										$html .= '  </td>';

										$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
										$html .= '   '.number_format($total_x, 2, '.', ',');
										$html .= '  </td>';

										// Pintando Cabeceras de Medios de Pago
											$c = 0;
											$campo = '';

											while ($c < count($arr_cabecera)){
						        		$campo = $arr_cabecera[$c]["COD_MEDIOPAGO"];

						        		if ($row_datos["COD_MEDIOPAGOCABECERA"] == $campo){
						        			if ($campo == 5){
							        			$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

							        			$html .= ((strlen($row_datos["EFECTIVO_RECEPCION"]) == 0) ? '' : (($row_datos["EFECTIVO_RECEPCION"] > $row_datos["COMPROBANTE_TOTAL"]) ? number_format($row_datos["COMPROBANTE_TOTAL"], 2, '.', '') : number_format($row_datos["EFECTIVO_RECEPCION"], 2, '.', '')));

								        		$html .= '</td>';

								        		$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

							        			$html .= ((strlen($row_datos["EFECTIVO_RECEPCION"]) == 0) ? '' : ((round($row_datos["EFECTIVO_RECEPCION"], 2) >= round($row_datos["COMPROBANTE_TOTAL"], 2)) ? '' : number_format($row_datos["COMPROBANTE_TOTAL"] - $row_datos["EFECTIVO_RECEPCION"], 2, '.', '')));

								        		$html .= '</td>';
							        		}
							        		else{
							        			$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; '.(($campo == 1) ? 'background-color: #F2BE22;' : '').'">';
														$html .= '	x';
								        		$html .= '</td>';
							        		}
						        		}
							        		else{
							        			if ($campo == 5){
								        			$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
									        		$html .= '</td>';

									        		$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
									        		$html .= '</td>';
									        	}
									        	else{
									        		$html .= '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
									        		$html .= '</td>';
									        	}
							        		}

												$c ++;
											}

										// Seteando información de Pagos
											$html .= '  <td id="td_creditopagadoespecial_1_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["CREDITOPAGO_MEDIOPAGO"];
											$html .= '  </td>';

											$html .= '  <td id="td_creditopagadoespecial_2_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

											if ($row_datos["CREDITOPAGO_CODMEDIOPAGO"] == 5){
												$html .= '   '.number_format($row_datos["EFECTIVO_CREDITOSPAGO"], 2, '.', ',');
											}

											$html .= '  </td>';

											$html .= '  <td id="td_creditopagadoespecial_3_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

											if ($row_datos["CREDITOPAGO_CODMEDIOPAGO"] == 5){
												$html .= '   '.number_format($row_datos["COMPROBANTE_TOTAL"] - $row_datos["EFECTIVO_CREDITOSPAGO"], 2, '.', ',');
											}

											$html .= '  </td>';

											$html .= '  <td id="td_creditopagadoespecial_4_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["CREDITOPAGO_FECHAHORA"];
											$html .= '  </td>';

											$html .= '  <td id="td_creditopagadoespecial_5_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["CREDITOPAGO_USUARIO"];
											$html .= '  </td>';

										$d ++;
									}
								}
							}

						$html .= '</tbody>';

						echo $html;

					?>

				</table>
			</div>
		</div>
	</div>
</html>