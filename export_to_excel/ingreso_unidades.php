<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Ingreso de Unidades.xls');
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
						$condicion_ingreso = $_GET["filtro_condicioningreso"];
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

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Fecha Ingreso
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

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;" hidden>
	        				Zona Origen
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
	        				Observación
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;" hidden>
	        				Ingresó con Vehículo Particular
	        			</th>

	        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Información de Salida de Unidad
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

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Observación
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody id="tbl_detalle">

							<?php
								$d = 1;
								$id_lote = 0;
								$html = '';

								// Query para obtener el tipo: "Recepción de Mineral"
									if ($condicion_ingreso == 99 || $condicion_ingreso == 1){
										$q_validacion = "	SELECT DISTINCT
																						 1 AS ID_CONDICION,
																						 'Recepción de Mineral' AS CONDICION,
																						 V.Id,
																						 MD5(V.Id) AS ID_MD5,
																						 V.lote_id_lote,
																						 V.lote_cod_lote,
																						 V.lote_ticket_orden,
																			 			 CRL.num_ticketbalanza,
																			 			 DATE(CRL.fecha_ingresobalanza) AS FECHA_TICKET,
																						 V.guias_ticketbalanza,
															       				 DATE(V.lote_pesoinicial_fechahoraregistro) AS FECHA_INGRESOBALANZA,
																						 V.balanza_placa,
																						 V.balanza_placa2,
																						 V.guiaremitente_serie,
																						 V.guiaremitente_numero,
																						 V.guiatransportista_serie,
																						 V.guiatransportista_numero,
																						 CL_T.documento AS TRANSPORTISTA_RUC,
																						 UPPER(CL_T.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
																						 TV.descripcion AS TIPO_VEHICULO,
																						 CD.dni_licencia AS CONDUCTOR_DNI,
																						 CD.nombres AS CONDUCTOR_NOMBRES,
																						 TC.descripcion AS TIPO_CARGA,
																						 '' AS CANT_BIGBAG,
																						 '' AS REMITENTE_RUC,
																						 '' AS REMITENTE_RAZONSOCIAL,
																						 UPPER(P.descripcion) AS PRODUCTO,
																						 TM.descripcion AS TIPO_MATERIAL,
																						 V.despacho_observacion,
																						 CL.documento AS PROVEEDORMINERO_RUC,
																						 UPPER(CL.razon_social) AS PROVEEDORMINERO_RAZONSOCIAL,
																						 CL.proveedorminero_concesion,
																						 CL.proveedorminero_codigounico,
																						 CL.proveedorminero_ubicacion,
																						 CL.proveedorminero_ubicacion_departamento,
																						 CL.proveedorminero_ubicacion_provincia,
																						 /*CL.proveedorminero_ubicacion_distrito,*/

																						 CASE WHEN V.lote_id_zonaorigen = 25
																						   THEN ZO.descripcion
																						 ELSE CCS.procedencia_distrito END AS procedencia_distrito,

																						 UPPER(EM.nombres) AS ENCARGADO_MUESTRA,
																						 V.lote_pesoinicial_fechahoraregistro,
																						 V.lote_pesofinal_fechahoraregistro,
																						 V.lote_peso_inicial AS lote_peso_bruto,
																	 					 V.lote_peso_final AS lote_peso_tara,
																						 V.lote_peso_neto,
																						 0 AS is_complemento,
																						 0 AS COMPLEMENTO_TARA,
																						 0 AS COMPLEMENTO_PESODISTRIBUIDO,
																						 0 AS COMPLEMENTO_PESODISTRIBUIDO2

																			  FROM despachos_primertramo_validaciondatos V
																			  		 LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
																			  		 INNER JOIN tb_clientes CL_T ON T.id_Transportista = CL_T.Id
																			  		 INNER JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
																			  		 INNER JOIN tbconfig_conductores CD ON V.guias_idchofer = CD.Id
																			  		 LEFT JOIN tbconfig_zonaorigen ZO ON V.lote_id_zonaorigen = ZO.Id
																			  		 LEFT JOIN tb_clientes CL ON V.lote_id_proveedorminero = CL.Id
																			  		 LEFT JOIN tbconfig_proveedoresmineros_concesion CCS ON V.lote_id_proveedorminero_concesion = CCS.Id
																			  		 LEFT JOIN tbconfig_encargadosmuestra EM ON V.lote_id_encargadomuestra = EM.Id
																			  		 LEFT JOIN tbconfig_tipomineral TM ON V.lote_id_tipomineral = TM.Id
																						 LEFT JOIN tbconfig_tipocarga TC ON V.lote_id_tipocarga = TC.Id
																				 		 LEFT JOIN tbconfig_producto P ON V.lote_id_producto = P.Id
																				 		 /*LEFT JOIN guia_remision_detalle GRD ON V.Id = GRD.id_despachos_primertramo_validaciondatos
																				 		 LEFT JOIN guia_remision GR ON GRD.id_Guia_remision = GR.id_Guia_remision*/
																			 			 LEFT JOIN consolidado_lotes_cierrecontable CRL ON V.Id = CRL.id_registro
																			 			   AND CRL.id_tipoingreso = 1
																			 WHERE V.guiaremitente_serie IS NOT NULL
																				 AND V.is_cerradolote = 1";

										$q_validacion .= "   AND DATE(V.lote_pesoinicial_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

										if (strlen($filtro_transportista) > 0){
											$q_validacion .= "   AND T.id_Transportista = ".$filtro_transportista;
										}

										if (strlen($filtro_placa) > 0){
											$q_validacion .= "   AND V.balanza_placa LIKE '%".$filtro_placa."%'";
										}

										$q_validacion .= " UNION ALL ";
									}

									if ($condicion_ingreso == 99 || $condicion_ingreso == 2){
										$q_validacion .= "SELECT DISTINCT
																						 2 AS ID_CONDICION,
																						 'Despacho de Mineral' AS CONDICION,
																						 DL.Id,
																						 MD5(DL.Id) AS ID_MD5,
																						 0 AS lote_id_lote,
																						 PD.cod_lote AS lote_cod_lote,
																						 0 AS lote_ticket_orden,
																						 CL.num_ticketbalanza,
																			 			 DATE(CL.fecha_ingresobalanza) AS FECHA_TICKET,
																						 0 AS guias_ticketbalanza,
																						 DL.guias_fecha AS FECHA_INGRESOBALANZA,
																						 DL.guias_placa1 AS balanza_placa,
																						 DL.guias_placa2 AS balanza_placa2,
																						 DL.guiaremitente_serie,
																						 DL.guiaremitente_numero,
																						 DL.guiatransportista_serie,
																						 DL.guiatransportista_numero,
																						 TR.documento AS TRANSPORTISTA_RUC,
																						 TR.razon_social AS TRANSPORTISTA_RAZONSOCIAL,
																						 TV.descripcion AS TIPO_VEHICULO,
																						 CH.licencia_conducir AS CONDUCTOR_DNI,
																						 CH.nombres AS CONDUCTOR_NOMBRES,
																						 TC.descripcion AS TIPO_CARGA,
																						 DL.num_bigbag AS CANT_BIGBAG,
																						 DL.guias_remitenteruc AS REMITENTE_RUC,
																						 DL.guias_remitenterazonsocial AS REMITENTE_RAZONSOCIAL,
																						 PR.descripcion AS PRODUCTO,
																						 TM.descripcion AS TIPO_MATERIAL,
																						 DL.observacion AS despacho_observacion,
																						 '' AS PROVEEDORMINERO_RUC,
																					 	 '' AS PROVEEDORMINERO_RAZONSOCIAL,
																					 	 '' AS proveedorminero_concesion,
																						 '' AS proveedorminero_codigounico,
																						 '' AS proveedorminero_ubicacion,
																						 '' AS proveedorminero_ubicacion_departamento,
																						 '' AS proveedorminero_ubicacion_provincia,
																						 'PLANTA HUANCHACO' AS procedencia_distrito,
																						 '' AS ENCARGADO_MUESTRA,
																						 DL.guias_fecha AS lote_pesoinicial_fechahoraregistro,
																						 DL.guias_fecha AS lote_pesofinal_fechahoraregistro,
																						 DL.peso_bruto AS lote_peso_bruto,
																						 DL.peso_tara AS lote_peso_tara,
																						 DL.peso_neto AS lote_peso_neto,
																						 DL.is_complemento,
																						 IFNULL(DL.cierrelote_complementotara, 0) AS COMPLEMENTO_TARA,
					 
																		         (SELECT SUM(DL_x.peso_distribuido)
																							  FROM despachos_segundotramo_distribucion_lotes DL_x
																							 WHERE DL_x.Id IN (SELECT DL_x2.Id
																							 									 FROM despachos_segundotramo_distribucion_lotes DL_x2
																							 									 WHERE DL_x2.is_complemento_de = DL.Id)) AS COMPLEMENTO_PESODISTRIBUIDO,
					 
																		         (SELECT SUM(DL_x.peso_distribuido)
																							  FROM despachos_segundotramo_distribucion_lotes DL_x
																							 WHERE DL_x.Id = DL.Id) AS COMPLEMENTO_PESODISTRIBUIDO2
					 
																			 FROM despachos_segundotramo_programacion_detalle PD
																						 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																						 INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
																						 INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
																						 AND PD.cod_lote = DL.cod_lote
																						 INNER JOIN transporte UN ON U.id_unidad = UN.id_transporte
																						 LEFT JOIN transporte UN2 ON U.id_unidad2 = UN2.id_transporte
																						 INNER JOIN tb_clientes TR ON UN.id_Transportista = TR.Id
																						 INNER JOIN tbconfig_plantas PL ON PD.id_planta = PL.Id
																						 LEFT JOIN tbconfig_modalidadenvio ME ON PD.id_modalidadenvio = ME.Id
																						 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
																						 INNER JOIN tbconfig_tipovehiculo TV ON UN.id_tipovehiculo = TV.Id
																						 INNER JOIN despachos_primertramo_validaciondatos V ON PD.cod_lote = V.lote_cod_lote
																						 INNER JOIN tbconfig_conductores CH ON DL.guias_idchofer = CH.Id
																						 INNER JOIN tbconfig_remitentessegundotramo RE ON DL.guias_iddestino = RE.id_destino
																						 AND DL.guias_idmodalidadenvio = RE.id_modalidadenvio
																						 INNER JOIN tbconfig_producto PR ON V.lote_id_producto = PR.Id
																						 LEFT JOIN tbconfig_tipomineral TM ON V.lote_id_tipomineral = TM.Id
																						 LEFT JOIN consolidado_lotes_cierrecontable CL ON DL.Id = CL.id_registro
																						  AND CL.id_tipoingreso = 2
																			 WHERE DL.guiaremitente_serie IS NOT NULL
																				 AND DL.is_cerradolote = 1";

										$q_validacion .= "   AND DL.guias_fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

										if (strlen($filtro_transportista) > 0){
											$q_validacion .= "   AND UN.id_Transportista = ".$filtro_transportista;
										}

										if (strlen($filtro_placa) > 0){
											$q_validacion .= "   AND DL.guias_placa1 LIKE '%".$filtro_placa."%'";
										}

										$q_validacion .= " UNION ALL ";
									}

									$q_validacion = substr($q_validacion, 0, -7);

									// Agregando los registros ficticios
										$q_validacion .= " UNION ALL ";

										$q_validacion .= "SELECT DISTINCT
																						 AU.id_tipoingresounidad AS ID_CONDICION,
																						 TI.descripcion AS CONDICION,
																						 AU.id_controlIngresoVehiculo AS Id,
																						 MD5(AU.id_controlIngresoVehiculo) AS ID_MD5,
																						 0 AS lote_id_lote,
																						 '' AS lote_cod_lote,
																						 0 AS lote_ticket_orden,
																			 			 '' AS num_ticketbalanza,
																			 			 AU.dFechaIngreso AS FECHA_TICKET,
																						 '' AS guias_ticketbalanza,
															       				 AU.dFechaIngreso AS FECHA_INGRESOBALANZA,
																						 AU.placa AS balanza_placa,
																						 AU.placa2 AS balanza_placa2,
																						 '' AS guiaremitente_serie,
																						 '' AS guiaremitente_numero,
																						 '' AS guiatransportista_serie,
																						 '' AS guiatransportista_numero,
																						 TR.documento AS TRANSPORTISTA_RUC,
																						 UPPER(TR.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
																						 TV.descripcion AS TIPO_VEHICULO,
																						 CO.licencia_conducir AS CONDUCTOR_DNI,
																						 CO.nombres AS CONDUCTOR_NOMBRES,
																						 TC.descripcion AS TIPO_CARGA,
																						 '' AS CANT_BIGBAG,
																						 '' AS REMITENTE_RUC,
																						 '' AS REMITENTE_RAZONSOCIAL,
																						 '' AS PRODUCTO,
																						 '' AS TIPO_MATERIAL,
																						 AU.cNotas AS despacho_observacion,
																						 '' AS PROVEEDORMINERO_RUC,
																						 '' AS PROVEEDORMINERO_RAZONSOCIAL,
																						 '' AS proveedorminero_concesion,
																						 '' AS proveedorminero_codigounico,
																						 '' AS proveedorminero_ubicacion,
																						 '' AS proveedorminero_ubicacion_departamento,
																						 '' AS proveedorminero_ubicacion_provincia,
																						 /*CL.proveedorminero_ubicacion_distrito,*/

																						 '' AS procedencia_distrito,

																						 '' AS ENCARGADO_MUESTRA,
																						 '' AS lote_pesoinicial_fechahoraregistro,
																						 '' AS lote_pesofinal_fechahoraregistro,
																						 '' AS lote_peso_bruto,
																	 					 '' AS lote_peso_tara,
																						 '' AS lote_peso_neto,
																						 0 AS is_complemento,
																						 0 AS COMPLEMENTO_TARA,
																						 0 AS COMPLEMENTO_PESODISTRIBUIDO,
																						 0 AS COMPLEMENTO_PESODISTRIBUIDO2

																			  FROM controlingresovehiculo_auditoria AU
																						 INNER JOIN tbconfig_tipoingresounidades TI ON AU.id_tipoingresounidad = TI.Id
																			       INNER JOIN tb_clientes TR ON AU.id_transportista = TR.Id
																			       INNER JOIN tbconfig_tipovehiculo TV ON AU.id_tipovehiculo = TV.Id
																			       INNER JOIN tbconfig_conductores CO ON AU.id_choferes = CO.Id
																			       LEFT JOIN tbconfig_tipocarga TC ON AU.id_tipocarga = TC.Id
																			 WHERE AU.dFechaIngreso BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

										if (strlen($filtro_transportista) > 0){
											$q_validacion .= "   AND AU.id_Transportista = ".$filtro_transportista;
										}

										if (strlen($filtro_placa) > 0){
											$q_validacion .= "   AND AU.placa LIKE '%".$filtro_placa."%'";
										}

									$q_validacion .= " ORDER BY 8";

								if ($res_validacion = mysqli_query($enlace, $q_validacion)){
									if (mysqli_num_rows($res_validacion) > 0) {
										$estado = 1;

										while($row_validacion = mysqli_fetch_array($res_validacion)){
											$html .= '<tr id="tr_detalle_'.$d.'" style="font-size: 14px;">';

											// Inicia con la carga de datos
												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$d;
												$html .= '    <input id="id_'.$d.'" type="hidden" value="'.$row_validacion["Id"].'">';
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$row_validacion["FECHA_INGRESOBALANZA"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$row_validacion["CONDICION"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
												$html .= '		'.$row_validacion["balanza_placa"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
												$html .= '		'.$row_validacion["balanza_placa2"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$row_validacion["TRANSPORTISTA_RUC"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$row_validacion["TRANSPORTISTA_RAZONSOCIAL"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$row_validacion["TIPO_VEHICULO"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$row_validacion["CONDUCTOR_DNI"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.$row_validacion["CONDUCTOR_NOMBRES"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
												$html .= '    '.((strlen($row_validacion["CANT_BIGBAG"]) > 0) ? $row_validacion["CANT_BIGBAG"].' ' : '').$row_validacion["TIPO_CARGA"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';

												if ($row_validacion["ID_CONDICION"] == 1){
													// if (strlen($row_validacion["despacho_observacion"]) == 0 && strlen($row_validacion["lote_ticket_orden"]) > 0){
													// 	$html .= 'PARTE '.$row_validacion["lote_ticket_orden"];
													// }
													// else{
														$html .= $row_validacion["despacho_observacion"];
													// }
												}
												else{
													$html .= $row_validacion["despacho_observacion"];
												}

												$html .= '	</td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';

												if ($row_validacion["ID_CONDICION"] == 1){
													$html .= '    '.$row_validacion["FECHA_INGRESOBALANZA"];
												}
												else{
													$html .= '    '.$row_validacion["FECHA_TICKET"];
												}

												$html .= '	</td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';

												$html .= '	</td>';

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