<?php

	include('../cnx/cnx.php');
  include('../global/variables.php');

  header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Cierre de Lotes - Sgundo Tramo.xls');
	header('Pragma: no-cache');
	header('Expires: 0');

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
        		
        		$filtro_lote = $_GET["filtro_lote"];
						$filtro_lote = explode(',', $filtro_lote);

						// Setea Filtro de Lotes
							$l = 0;
							$arr_lotes = '';

							if(isset($filtro_lote) && is_array($filtro_lote)) {
								while ($l < count($filtro_lote)){
									if (strlen($filtro_lote[$l]) > 0){
										$arr_lotes .= "'".$filtro_lote[$l]."', ";
									}

									$l ++;
								}

								if (strlen($arr_lotes) > 0){
									$arr_lotes = substr($arr_lotes, 0, -2);
								}
							}

				?>

				<font size = "3"><b>
					CIERRE DE LOTES - SEGUNDO TRAMO
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

				<table>
        	<thead>
        		<tr style="font-size: 12px;">
        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 35px;">
        				N°
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
        				Lote
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 105px;">
        				Ticket Balanza
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
        				Fecha Ingreso a Balanza<br>(Contable)
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Placa 1
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Placa 2
        			</th>

        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Información Guías
        			</th>

        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Emp. de Transporte
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
        				Tipo Vehículo
        			</th>

        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Info. Conductor
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Tipo Carga
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Cant. Big Bag
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Zona Origen
        			</th>

        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Remitente
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
        				Producto
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
        				Tipo Mineral
        			</th>

        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
        				Observación
        			</th>

        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Información de Pesos (Kg)
        			</th>

        			<th rowspan="2" colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
        				Cierre
        			</th>
        		</tr>

        		<tr style="font-size: 12px;">
        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Remitente
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Transportista
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
        				DNI/RUC
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
        				Razón Social
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
        				Licencia
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
        				Nombres
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
        				DNI/RUC
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
        				Razón Social
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
        				Fecha Peso Inicial
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
        				Fecha Peso Final
        			</th>

							<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Bruto
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Tara
        			</th>

        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
        				Neto
        			</th>
        		</tr>
        	</thead>

        	<tbody id="tbl_detalle">

						<?php
							$d = 1;
							$html = '';

							// Query para obtener el tipo: "Recepción de Mineral"
								$q_datos = "SELECT DISTINCT
																	 DL.Id,
																	 MD5(DL.Id) AS ID_MD5,
																	 PD.cod_lote,
																	 CL.num_ticketbalanza,
																	 /* DATE(V.lote_pesoinicial_fechahoraregistro) AS FECHA_INGRESOBALANZA,*/
																	 /* DATE(DL.peso_bruto_fechahoraregistro) AS FECHA_INGRESOBALANZA,*/
																	 /*DL.guias_fecha AS FECHA_INGRESOBALANZA,*/
																	 DATE(IFNULL(CL.fecha_ingresobalanza, DL.guias_fecha)) AS FECHA_INGRESOBALANZA,
																	 IFNULL(DL.guias_placa1, UN.cplaca) AS PLACA1,
																	 IFNULL(DL.guias_placa2, UN2.cplaca) AS PLACA2,
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
																	 DL.num_bigbag,
																	 'PLANTA HUANCHACO' AS ZONA_ORIGEN,
																	 DL.guias_remitenteruc,
																	 DL.guias_remitenterazonsocial,
																	 PR.descripcion AS PRODUCTO,
																	 TM.descripcion AS TIPO_MINERAL,
																	 DL.observacion,
																	 DL.peso_bruto_fechahoraregistro,
																	 DL.peso_tara_fechahoraregistro,
																	 DL.peso_bruto,
																	 DL.peso_tara,
																	 DL.peso_neto,
																	 DL.is_cerradolote,
																	 DL.cerradolote_fechahoraregistro,
																	 DL.cerradolote_usuarioregistro,
																	 DL.is_complemento,
																	 IFNULL(DL.cierrelote_complementotara, 0) AS COMPLEMENTO_TARA,
																	 /*
													         (SELECT CASE WHEN COUNT(DL_x.Id) > 0
													         				 	 THEN 1
													         				 ELSE 0 END
													         		FROM despachos_segundotramo_distribucion_lotes DL_x
													         	 WHERE DL_x.is_complemento_de = DL.Id) AS TIENE_COMPLEMENTO,
																	 */
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
														 WHERE DL.guiaremitente_serie IS NOT NULL";

								if (strlen($arr_lotes) > 0){
									$q_datos .= "   AND PD.cod_lote IN (".$arr_lotes.")";
								}
								else{
									// $q_datos .= "   AND DATE(DL.peso_bruto_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
									$q_datos .= "   AND DL.guias_fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
								}

								$q_datos .= " ORDER BY PD.cod_lote";

							if ($res_datos = mysqli_query($enlace, $q_datos)){
								if (mysqli_num_rows($res_datos) > 0) {
									$estado = 1;

									while($row_datos = mysqli_fetch_array($res_datos)){
										$html .= '<tr id="tr_detalle_'.$d.'" style="font-size: 14px;">';

										// Inicia con la carga de datos
											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$d;
											$html .= '    <input id="id_'.$d.'" type="hidden" value="'.$row_datos["Id"].'">';
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
											$html .= '    '.$row_datos["cod_lote"];
											$html .= '  </td>';

											$html .= '  <td id="td_numticket_'.$row_datos["Id"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
											$html .= '    '.$row_datos["num_ticketbalanza"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
											$html .= '		<div class="d-flex">';
											$html .= '			<input id="td_ingreso_fecha_'.$d.'" type="date" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 220px; margin-right: 5px;" value="'.$row_datos["FECHA_INGRESOBALANZA"].'" onchange="f_UpdateDatos('.$d.', 1)">';

											$html .= '			<input id="td_ingreso_hora_'.$d.'" type="time" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 100px;" value="00:00" onchange="f_UpdateDatos('.$d.', 6)" hidden>';
											$html .= '  	</div>';
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
											$html .= '		'.$row_datos["PLACA1"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
											$html .= '		'.$row_datos["PLACA2"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
											$html .= '		'.$row_datos["guiaremitente_serie"].'-'.$row_datos["guiaremitente_numero"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #ffffff;">';
											$html .= '		'.$row_datos["guiatransportista_serie"].'-'.$row_datos["guiatransportista_numero"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["TRANSPORTISTA_RUC"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["TRANSPORTISTA_RAZONSOCIAL"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["TIPO_VEHICULO"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["CONDUCTOR_DNI"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["CONDUCTOR_NOMBRES"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["TIPO_CARGA"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["num_bigbag"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["ZONA_ORIGEN"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["guias_remitenteruc"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["guias_remitenterazonsocial"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["PRODUCTO"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '    '.$row_datos["TIPO_MINERAL"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											$html .= '		<textarea id="val_2_'.$d.'" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase; text-align: center;" rows="1" onblur="f_UpdateDatos('.$d.', 2)">';

											if (strlen($row_datos["observacion"]) == 0 && strlen($row_datos["lote_ticket_orden"]) > 0){
												$html .= 'PARTE '.$row_datos["lote_ticket_orden"];
											}
											else{
												$html .= $row_datos["observacion"];
											}

											$html .= '</textarea>';
											$html .= '	</td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											// $html .= '		<div class="d-flex">';
											// $html .= '			<input id="td_pesoinicio_fecha_'.$d.'" type="date" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 220px; margin-right: 5px;" value="'.substr($row_datos["peso_bruto_fechahoraregistro"], 0, 10).'" onchange="f_UpdateDatos('.$d.', 1)">';

											// $html .= '			<input id="td_pesoinicio_hora_'.$d.'" type="time" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 100px;" value="00:00" onchange="f_UpdateDatos('.$d.', 2)" hidden>';
											// $html .= '  	</div>';
											$html .= '  	'.substr($row_datos["FECHA_INGRESOBALANZA"], 0, 10);
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color: #ffffff;">';
											// $html .= '		<div class="d-flex">';
											// $html .= '			<input id="td_pesofin_fecha_'.$d.'" type="date" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 220px; margin-right: 5px;" value="'.substr($row_datos["peso_tara_fechahoraregistro"], 0, 10).'" onchange="f_UpdateDatos('.$d.', 3)">';

											// $html .= '			<input id="td_pesofin_hora_'.$d.'" type="time" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 100px;" value="00:00" onchange="f_UpdateDatos('.$d.', 4)" hidden>';
											// $html .= '  	</div>';
											$html .= '  	'.substr($row_datos["FECHA_INGRESOBALANZA"], 0, 10);
											$html .= '  </td>';

											$html .= '  <td id="td_pesobruto_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; min-width: 110px;">';
											// $html .= '			<input id="val_8_'.$d.'" type="number" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 220px; font-weight: bold;" value="'.$row_datos["peso_bruto"].'" onchange="f_UpdateDatos('.$d.', 8, 0)">';
											$html .= '  	'.number_format($row_datos["peso_bruto"] * 1000, 0, '.', ',');
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; min-width: 110px;">';
											// $html .= '			<input id="val_7_'.$d.'" type="number" class="form-control input_datos_'.$d.'" style="text-align: center; font-size: 14px; max-width: 220px; font-weight: bold;" value="'.$row_datos["peso_tara"].'" onchange="f_UpdateDatos('.$d.', 7, 0)">';

											// Obteniendo Peso Neto
												$peso_neto = $row_datos["peso_neto"];

												// if ($row_datos["TIENE_COMPLEMENTO"] == 1){
												if (strlen($row_datos["COMPLEMENTO_PESODISTRIBUIDO"] > 0)){
													$peso_neto = $peso_neto - $row_datos["COMPLEMENTO_PESODISTRIBUIDO"];
												}

												if ($row_datos["is_complemento"] == 1){
													$peso_neto = abs($peso_neto - $row_datos["COMPLEMENTO_PESODISTRIBUIDO2"]);
												}

											// Obteniendo Peso Tara
												$peso_tara = $row_datos["peso_tara"];

												// if ($row_datos["TIENE_COMPLEMENTO"] == 1){
												if (strlen($row_datos["COMPLEMENTO_PESODISTRIBUIDO"] > 0)){
													$peso_tara = $row_datos["peso_bruto"] - $peso_neto;
												}

												if ($row_datos["is_complemento"] == 1){
													$html .= '  	<input id="val_3_'.$d.'" type="number" class="form-control col-md-12 col-xs-12 input_datos_'.$d.'" style="text-align: center; font-size: 14px;" onblur="f_UpdateDatos('.$d.', 3, '.$row_datos["COMPLEMENTO_PESODISTRIBUIDO2"].')" value="'.$row_datos["COMPLEMENTO_TARA"].'">';
												}
												else{
													$html .= '  	'.number_format($peso_tara * 1000, 0, '.', ',');
												}

											$html .= '  </td>';

											$html .= '  <td id="td_pesoneto_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

											$html .= '    '.number_format($peso_neto * 1000, 0, '.', ',');

											$html .= '  </td>';

											// Seteo de columnas de Cierre
												$html .= '  <td id="td_cierre_1_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; 	vertical-align: middle; text-align: center;">';

												if ($row_datos["is_cerradolote"] == 0){
													$html .= '		<input id="chk_cierre_'.$d.'" class="form-check-input chk_cierre" type="checkbox" style="transform: scale(1.5);">';
												}
												else{
													$html .= '		<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_Reabrir('.$d.', '.$row_datos["Id"].')"><u> Reabrir </u></label>';
												}

												$html .= '  </td>';

												$html .= '  <td id="td_cierre_2_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

												if ($row_datos["is_cerradolote"] == 1){
													$html .= '		'.$row_datos["cerradolote_fechahoraregistro"];
												}

												$html .= '  </td>';

												$html .= '  <td id="td_cierre_3_'.$d.'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

												if ($row_datos["is_cerradolote"] == 1){
													$html .= '		'.$row_datos["cerradolote_usuarioregistro"];
												}

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