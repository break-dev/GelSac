<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Resumen de Ventas.xls');
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
						$filtro_nommuestra = $_GET["filtro_nommuestra"];
						$filtro_listatipodocumento = $_GET["filtro_listatipodocumento"];
						$filtro_documento = $_GET["filtro_documento"];
						$filtro_opt1 = $_GET["filtro_opt1"];
						$filtro_opt2 = $_GET["filtro_opt2"];
						$filtro_opt3 = $_GET["filtro_opt3"];

				?>

					<font size = "3"><b>
						RESUMEN DE VENTAS
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

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
	        				Sucursal
	        			</th>

	        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
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
	        				Fecha Hora Reporte
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Entrega Informe
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Medio Pago
	        			</th>

	        			<th colspan="11" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Información de Muestras
	        			</th>

	        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				Informe de Ensayos
	        			</th>
	        		</tr>

	        		<tr style="font-size: 14px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Documento
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
	        				Razón Social
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Teléfonos
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				N° Recibo
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				N° Comprobante
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Fecha Emisión
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Fecha y Hora
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Usuario
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
	        				Peso (Kg)
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Estado
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Envase
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Tipo
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Ensayo
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Exceso
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Total
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
	        				Observación
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody>

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
																	 CONCAT(CL.telefono1, CASE WHEN LENGTH(TRIM(IFNULL(CL.telefono2, ''))) > 0 THEN CONCAT(' - ', CL.telefono2) ELSE '' END) AS TELEFONOS_CLIENTE,
																	 C.recibo_codigo,
																	 C.facturador_comprobante,
																	 C.is_instruccion,
																	 C.is_temporal,
																	 C.is_recepcioncanceled,
																	 SUBSTRING(C.fechahora_recepcion, 1, 16) AS fechahora_recepcion,
																	 SUBSTRING(C.fechahora_entrega, 1, 16) AS fechahora_entrega,
																	 SUBSTRING(C.instruccion_fechahoraregistro, 1, 16) AS instruccion_fechahoraregistro,
																	 I.is_factura,
																	 UPPER(IFNULL(MP.descripcion, 'POR PAGAR')) AS MEDIO_PAGO,
																	 CASE WHEN LENGTH(IFNULL(MP.descripcion, '')) = 0 THEN 1 ELSE 0 END AS IS_PORPAGAR,
																	 IFNULL(I.is_pagado, 0) AS IS_PAGADO,
																	 I.cod_mediopago,
																	 C.is_ventacredito,
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
																	 IFNULL(C.facturador_fechaemision, C.facturador_fechavencimiento) AS facturador_fechaemision,
																	 I.pagado_codmediopago,

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
																 WHERE IFNULL(DATE(C.instruccion_fechahoraregistro), DATE(C.fechahora_recepcion)) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

								if (strlen($filtro_cliente) > 0){
									$q_datos .= "   AND C.cliente_documento = '".$filtro_cliente."'";
								}

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

								$q_datos .= " ORDER BY C.is_instruccion DESC, D.cod_interno";

								if ($res_datos = mysqli_query($enlace, $q_datos)){
				          if (mysqli_num_rows($res_datos) > 0) {
				            while($row_datos = mysqli_fetch_array($res_datos)){
				              $html .= '<tr style="cursor: pointer; font-size: 14px;">';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
											$html .= '   '.$d;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["des_sucursal"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["cliente_documento"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["razon_social"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["TELEFONOS_CLIENTE"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '  	<a class="success" href="javascript: f_ImprimirDocumentoCliente(1, '."'".$row_datos["MD5_IDRECEPCION"]."', ".(($row_datos["is_instruccion"] == 1) ? 0 : 1).')"><font color="#4B94F2"><u> '.$row_datos["recibo_codigo"].'</u></font></a>';
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '  	<a class="success" href="javascript: f_ImprimirDocumentoCliente(2, '."'".$row_datos["MD5_IDRECEPCION"]."', ".(($row_datos["is_instruccion"] == 1) ? 0 : 1).', '.((strlen(trim($row_datos["is_factura"])) == 0) ? 1 : $row_datos["is_factura"]).", '".$row_datos["facturador_comprobante"]."'".')"><font color="#4B94F2"><u> '.$row_datos["facturador_comprobante"].'</u></font></a>';
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.substr($row_datos["facturador_fechaemision"], 0, 10);
											$html .= '  </td>';

											if ($row_datos["is_recepcioncanceled"] == 0){
												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: '.(($row_datos["is_instruccion"] == 1) ? '#0099DD' : (($row_datos["is_temporal"] == 1) ? '#737373' : '#FF5F5D')).'; color: #ffffff;">';
												$html .= '   '.(($row_datos["is_instruccion"] == 1) ? 'INSTRUCCION' : (($row_datos["is_temporal"] == 1) ? 'EN REGISTRO' : 'R.A.'));
											}
											else{
												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #737373; color: #ffffff;">';
												$html .= '  	VENTA CANCELADA';
											}

											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["fechahora_recepcion"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["instruccion_fechahoraregistro"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.((strlen($row_datos["fechahora_entrega"]) == 0) ? 'POR CONFIRMAR' : $row_datos["fechahora_entrega"]);
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    PENDIENTE';
											$html .= '  </td>';

											// Calculando la fecha y hora actual
												date_default_timezone_set("America/Lima"); 

												$_date = date('Y-m-d');
												$_time = date('H:i:s');

												$_fecha = $_date.' ' .$_time;

											// Obteniendo el cálculo de la diferencia entre la fecha de entrega y now()
												$diferencia = 0;

												if ($row_datos["is_informeentregado"] == 0){
													$inicio = strtotime($_fecha);
													$fin = strtotime($row_datos["fechahora_entrega"]);

													$diferencia = ($fin - $inicio) / 3600;
												}

											$html .= '  <td id="td_entregainforme_1_'.$row_datos["Id"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; '.(($row_datos["is_informeentregado"] == 0) ? (($diferencia < 0.5) ? 'background-color: #ECF229' : '') : 'background-color: '.(($row_datos["informeentregado_fechahoraregistro"] > $row_datos["fechahora_entrega"]) ? '#FFC9CF' : '#93D94E')).'">';

											if ($row_datos["is_informeentregado"] == 0){
												// $html .= '    <a style="margin-top: 10px;" href="javascript: f_ConfirmarEntregaInforme('.$row_datos["Id"].')">			<font color="#32B86C">';
												// $html .= '    	<label style="margin-top: 8px; cursor: pointer; font-size: 13px;"><u>Confirmar Entrega</u></label>			</font>		</a>';
											}
											else{
												$html .= '    '.$row_datos["informeentregado_fechahoraregistro"];
											}

											$html .= '  </td>';

											$html .= '  <td id="td_entregainforme_2_'.$row_datos["Id"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

											if ($row_datos["is_informeentregado"] == 1){
												$html .= '    '.$row_datos["informeentregado_usuarioregistro"];
											}

											$html .= '  </td>';

											if ($row_datos["is_recepcioncanceled"] == 0 && $row_datos["is_temporal"] == 0){
												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; '.(($row_datos["is_temporal"] == 1) ? '' :(($row_datos["IS_PORPAGAR"] == 1) ? 'background-color: #FF5F5D; color: #ffffff;' : (($row_datos["cod_mediopago"] == 3) ? 'background-color: #89D99D; color: #ffffff;' : (($row_datos["cod_mediopago"] == 1) ? 'background-color: #F2BE22; color: #ffffff;' : '')))).'">';
												$html .= '   '.(($row_datos["is_temporal"] == 1) ? '' : (($row_datos["IS_PORPAGAR"] == 1 && $row_datos["is_ventacredito"] == 1) ? 'CREDITO POR CONFIRMAR' : $row_datos["MEDIO_PAGO"]));

												if ($row_datos["IS_PORPAGAR"] == 1 && $row_datos["IS_PAGADO"] == 1){
													$html .= '   <label style="color: #212226;">* PAGADO *</label>';
												}
											}
											else{
												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											}

											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
											$html .= '   '.$row_datos["cod_interno"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["nombre_muestra"];
											$html .= '  </td>';

											// Obtiene los análisis de cada muestra
												$analisis = '';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

												// 1. Obtiene análisis de Precios Generales
													$q_analisis = "SELECT CONCAT('(', EA.abv, CASE WHEN LENGTH(IFNULL(C.abv, '')) > 0 THEN CONCAT(' ', C.abv) ELSE '' END, ')') AS abv,
																								REA.total
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
																$analisis .= '            '.$row_analisis["abv"].', ';
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

																	$q_analisispaquete = "SELECT A.abv,
																															A.descripcion
																												 FROM tb_ensayos_analisisclasificaciones_paquetes_detalle D
																															INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
																												WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

																	if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
																		if (mysqli_num_rows($res_analisispaquete) > 0) {
																			while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
																				$abv .= $row_analisispaquete["abv"].' - ';
																				$descripcion .= $row_analisispaquete["descripcion"].' - ';
																			}
																		}
																	}

																	if (strlen($abv) > 0){
																		$abv = substr($abv, 0, -3);
																		$descripcion = substr($descripcion, 0, -3);
																	}

																// Creando el query final
																	$q_analisis = "SELECT '(".$abv.")' AS abv,
																													".$row_paquetes["total"]." AS total";

																	if ($res_analisis = mysqli_query($enlace, $q_analisis)){
																		if (mysqli_num_rows($res_analisis) > 0) {
																			while($row_analisis = mysqli_fetch_array($res_analisis)){
																				$analisis .= '            '.$row_analisis["abv"].', ';
																			}
																		}
																	}
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

																	$q_analisispaquete = "SELECT A.abv,
																															A.descripcion
																												 FROM tb_ensayos_analisisclasificaciones_paquetesclientes_detalle D
																															INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
																												WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

																	if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
																		if (mysqli_num_rows($res_analisispaquete) > 0) {
																			while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
																				$abv .= $row_analisispaquete["abv"].' - ';
																				$descripcion .= $row_analisispaquete["descripcion"].' - ';
																			}
																		}
																	}

																	if (strlen($abv) > 0){
																		$abv = substr($abv, 0, -3);
																		$descripcion = substr($descripcion, 0, -3);
																	}

																// Creando el query final
																	$q_analisis = "SELECT '(".$abv.")' AS abv,
																													".$row_paquetes["total"]." AS total";

																	if ($res_analisis = mysqli_query($enlace, $q_analisis)){
																		if (mysqli_num_rows($res_analisis) > 0) {
																			while($row_analisis = mysqli_fetch_array($res_analisis)){
																				$analisis .= '            '.$row_analisis["abv"].', ';
																			}
																		}
																	}
															}
														}
													}

													if (strlen($analisis) > 0){
														$analisis = substr($analisis, 0, -2);
													}

												$html .= '            '.$analisis;

												$html .= '          </div>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["peso_muestra"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["ESTADO_MUESTRA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["ENVASE_MUESTRA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["TIPO_MUESTRA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["ENSAYO_MUESTRA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["exceso"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
											$html .= '   '.number_format($row_datos["TOTAL"], 2, '.', '');
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["observacion"];
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