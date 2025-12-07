<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_liquidacionmd5 = $_GET["x"];
	$tiene_VR = $_GET["vr"];

	// Funciones
		function formatearFecha($fecha) {
	    // Separar fecha
				$dia = str_pad(explode('-', $fecha)[2], 2, '0', STR_PAD_LEFT);
				// $mes = nombre_meses(explode('-', $fecha)[1]);
				$mes = str_pad(explode('-', $fecha)[1], 2, '0', STR_PAD_LEFT);
				$anho = explode('-', $fecha)[0];

	    return $dia.'/'.$mes.'/'.$anho;
		}

		function nombre_meses($num_mes){
			if ($num_mes == 1){
				return "ENERO";
			}
			if ($num_mes == 2){
				return "FEBRERO";
			}
			if ($num_mes == 3){
				return "MARZO";
			}
			if ($num_mes == 4){
				return "ABRIL";
			}
			if ($num_mes == 5){
				return "MAYO";
			}
			if ($num_mes == 6){
				return "JUNIO";
			}
			if ($num_mes == 7){
				return "JULIO";
			}
			if ($num_mes == 8){
				return "AGOSTO";
			}
			if ($num_mes == 9){
				return "SEPTIEMBRE";
			}
			if ($num_mes == 10){
				return "OCTUBRE";
			}
			if ($num_mes == 11){
				return "NOVIEMBRE";
			}
			if ($num_mes == 12){
				return "DICIEMBRE";
			}
		}

	// Ruta imágenes
    $ruta_images_x = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_liquidaciontransporte_comercializacion.php')).'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_liquidaciontransporte_comercializacion.php')).'/';

	// 1. Obteniendo datos
    $d = 1;
    $arr_datos = array();
		$nom_archivo = 'Liquidación de Flete - Comercialización';

		$q_datos = "SELECT DATOS.ID_UNIDAD,
											 DATOS.codigo_despacho,
											 DATOS.codigo_planta,
											 DATOS.codigo_despacho_comercializacion,
											 DATOS.ID_MODALIDAD_ENVIO,
											 DATOS.MODALIDAD_ENVIO,
											 DATOS.FECHAHORA_SALIDA,
											 DATOS.FECHA_LLEGADAPLANTA,
											 DATOS.TIPO_VEHICULO,
											 DATOS.PLACA1,
											 DATOS.PLACA2,
											 DATOS.TRANSPORTISTA_RUC,
											 DATOS.TRANSPORTISTA_RAZONSOCIAL,
											 MO.abv AS MONEDA_ABV,
											 TT.tarifa_sin_igv,
											 DATOS.TARIFA_CIERRE,
											 DATOS.COORDINADOR_TRANSPORTE,
											 DATOS.guias_remitenteruc,
											 DATOS.guias_puntopartida,
											 DATOS.guias_puntodestino,
											 DATOS.guias_glosa,
											 DATOS.FECHAHORA_CERRE,
											 DATOS.USUARIO_CIERRE,
											 DATOS.llegadaplanta_pesonetoplanta AS TMH_TOTAL,
											 DATOS.cod_lote,
											 DATOS.num_parte,
											 DATOS.llegadaplanta_codigoplanta,
											 DATOS.nombre_comercial AS DESTINO_PLANTA,
											 DATOS.ID_TIPOCARGA,
											 DATOS.TIPO_CARGA,
											 DATOS.num_bigbag,
							 				 DATOS.subruta1,
							 				 DATOS.subruta1_valorreferencial,
							 				 DATOS.subruta2,
							 				 DATOS.subruta2_valorreferencial,
							 				 DATOS.id_planta,
											 MO.abv AS MONEDA
									FROM (SELECT DISTINCT
															 U.Id AS ID_UNIDAD,
															 PD.codigo_despacho,
															 PD.codigo_planta,
															 PD.codigo_despacho_comercializacion,

															 CASE WHEN PD.id_planta = 3
															   THEN (SELECT DISTINCT V.despacho_id_modalidadenvio
																		 		 FROM despachos_primertramo_validaciondatos V
																		 	 	WHERE V.lote_cod_lote = DL.cod_lote)
															 ELSE ME.Id END AS ID_MODALIDAD_ENVIO,
		 
															 CASE WHEN PD.id_planta = 3
															   THEN (SELECT DISTINCT ME_x.descripcion
																		 		 FROM despachos_primertramo_validaciondatos V
																		 		 			INNER JOIN tbconfig_modalidadenvio ME_x ON V.despacho_id_modalidadenvio = ME_x.Id
																		 	 	WHERE V.lote_cod_lote = DL.cod_lote)
															 ELSE ME.descripcion END AS MODALIDAD_ENVIO,
		 
														   (SELECT CI.fechahora_salida
																	FROM controlingresovehiculo CI
														  	 WHERE CI.placa = UN.cplaca
														  		 AND CI.dFechaIngreso BETWEEN U.fecha_ingresoplanta AND P.fechaestimada_despacho
														  	ORDER BY CI.fechahora_salida DESC
														  	LIMIT 1) AS FECHAHORA_SALIDA,
															  
														   DATE(DL.llegadaplanta_fechaingreso) AS FECHA_LLEGADAPLANTA,
														   TV.descripcion AS TIPO_VEHICULO,
															 UN.cplaca AS PLACA1,
															 UN2.cplaca AS PLACA2,
															 TR.documento AS TRANSPORTISTA_RUC,
															 TR.razon_social AS TRANSPORTISTA_RAZONSOCIAL,
															 DL.llegadaplanta_pesonetoplanta,
															 PD.id_planta,
															 U.id_coordinadortransporte,
															 CT.nombres AS COORDINADOR_TRANSPORTE,
															 DL.guias_remitenteruc,
															 DL.guias_puntopartida,
															 DL.guias_puntodestino,
															 DL.guias_glosa,
															 LC.tarifa AS TARIFA_CIERRE,
															 LC.fechahora_registro AS FECHAHORA_CERRE,
											 				 LC.usuario_registro AS USUARIO_CIERRE,
											 				 DL.cod_lote,
											 				 DL.num_parte,
											 				 DL.llegadaplanta_codigoplanta,
											 				 DP.nombre_comercial,
											 				 DL.id_tipocarga AS ID_TIPOCARGA,
											 				 TC.descripcion AS TIPO_CARGA,
											 				 DL.num_bigbag,
											 				 VR.subruta1,
											 				 VR.subruta1_valorreferencial,
											 				 VR.subruta2,
											 				 VR.subruta2_valorreferencial
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
												       INNER JOIN tbconfig_tipovehiculo TV ON UN.id_tipovehiculo = TV.Id
												       LEFT JOIN tbconfig_coordinadorestransporte CT ON U.id_coordinadortransporte = CT.Id
												       INNER JOIN liquidaciones_transporte LC ON PD.codigo_despacho = LC.codigo_despacho
												         AND DL.guias_remitenteruc = LC.remitente_ruc
												         AND UN.cplaca = LC.placa
												       INNER JOIN tbconfig_plantas DP ON P.id_planta = DP.Id
												       INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
												       LEFT JOIN tbconfig_transportevalorreferencial VR ON P.id_planta = VR.id_planta
												         AND VR.estado = 'A'
												 WHERE MD5(LC.Id) = '".$id_liquidacionmd5."') AS DATOS
										 	 LEFT JOIN tbconfig_tarifatransporte TT ON DATOS.id_coordinadortransporte = TT.id_coordinadortransporte
										     AND DATOS.id_planta = TT.id_planta
											 LEFT JOIN tbconfig_monedas MO ON TT.id_moneda = MO.Id";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	// Obteniendo datos
        		if ($d == 1){
        			$fecha_salida = formatearFecha(substr($row_datos["FECHAHORA_SALIDA"], 0, 10));
	      			$codigo_despacho = $row_datos["codigo_despacho"];
	      			$codigo_planta = $row_datos["codigo_planta"];
							$codigo_despacho_comercializacion = $row_datos["codigo_despacho_comercializacion"];
	      			$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
	      			$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
							$placa1 = $row_datos["PLACA1"];
							$placa2 = $row_datos["PLACA2"];
							$punto_partida = $row_datos["guias_puntopartida"];
							$punto_llegada = $row_datos["guias_puntodestino"];
							$guias_glosa = $row_datos["guias_glosa"];
							$subruta1 = $row_datos["subruta1"];
							$subruta1_valorreferencial = $row_datos["subruta1_valorreferencial"];
							$subruta2 = $row_datos["subruta2"];
							$subruta2_valorreferencial = $row_datos["subruta2_valorreferencial"];
							$id_planta = $row_datos["id_planta"];
							$moneda = $row_datos["MONEDA"];
							$id_modalidadenvio = $row_datos["ID_MODALIDAD_ENVIO"];

							// Obteniendo el Logo del Informe según Modalidad de Envío
								$informes_logo = '';

								$q_datos_md = "SELECT informes_logo
																 FROM tbconfig_modalidadenvio
																WHERE Id = ".$id_modalidadenvio;

								if ($res_datos_md = mysqli_query($enlace, $q_datos_md)){
								  if (mysqli_num_rows($res_datos_md) > 0) {
								    while($row_datos_md = mysqli_fetch_array($res_datos_md)){
								    	$informes_logo = $url_images.$row_datos_md["informes_logo"];
								    }
								  }
								}
        		}

        	array_push($arr_datos, $row_datos);
        }
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Liquidación de Flete</title>

									<style>
										html, body{
											font-family: Arial, sans-serif;
											margin: 0;
											padding: -5;
											margin-bottom: -15px;
											font-size: 14px;
											color: #545759;
										}

										@page{
											margin: 0;
											pading: 0;
										}
									</style>
								</head>

								<body style="margin-left: 10px; margin-right: 10px;">
									<div class="row" style="margin-top: 60px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 11px;">
												<td rowspan="2" style="text-align: center; border: solid; border-width: 1px; border-color: #E6E9ED; width: 150px; height: 30px; vertical-align: middle;">
													<img src="'.$ruta_images_qr.$informes_logo.'" style="width: 110px;"/>
												</td>

												<td rowspan="2" style="text-align: center; border: solid; border-width: 1px; border-color: #E6E9ED; width: 300px; height: 30px; font-weight: bold; font-size: 16px; vertical-align: middle;">
													LIQUIDACIÓN FLETE
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; border-color: #E6E9ED; height: 30px; vertical-align: middle;">
													Fecha de Envío
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; border-color: #E6E9ED; height: 30px; vertical-align: middle;">
													'.$fecha_salida.'
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="text-align: center; border: solid; border-width: 1px; border-color: #E6E9ED; height: 30px; vertical-align: middle;">
													Código de despacho
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; border-color: #E6E9ED; height: 30px; vertical-align: middle; font-weight: bold;">
													'.(($id_planta == 3) ? $codigo_despacho_comercializacion : $codigo_despacho).'
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 11px;">
												<td colspan="4" style="border: solid; border-width: 1px; border-color: #E6E9ED; font-size: 11px; font-weight: bold;">
													Datos de la Empresa de Transportes
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 90px;">
													Razón Social
												</td>

												<td colspan="3" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle;">
													'.$transportista_razonsocial.'
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 90px;">
													RUC
												</td>

												<td colspan="3" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle;">
													'.$transportista_ruc.'
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 90px;">
													Placa 1
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 250px;">
													'.$placa1.'
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 50px;">
													Placa 2
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 200px;">
													'.((strlen($placa2) == 0) ? '' : $placa2).'
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 11px;">
												<td colspan="4" style="border: solid; border-width: 1px; border-color: #E6E9ED; font-size: 11px; font-weight: bold;">
													Datos de la Ruta
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 90px; height: 70px;">
													Punto de partida
												</td>

												<td colspan="3" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle;">
													'.$punto_partida.'
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 90px; height: 70px;">
													Punto de llegada
												</td>

												<td colspan="3" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle;">
													'.$punto_llegada.'
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 11px;">
												<td colspan="8" style="border: solid; border-width: 1px; border-color: #E6E9ED; font-size: 11px; font-weight: bold;">
													Detalle de flete
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 90px;">
													Bien trasladado
												</td>

												<td colspan="7" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle;">
													'.$guias_glosa.'
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; width: 90px;">
													Código AUM
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; width: 80px;">
													Ref. Cod. Planta
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; width: 80px;">
													Procedencia
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; width: 90px;">
													Llegada
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; width: 80px;">
													Presentación
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; width: 50px;">
													TMH
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; width: 70px;">
													Precio Unitario ('.$moneda.'xTNE)
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center; font-weight: bold; width: 80px;">
													Sub Total
												</td>
											</tr>';

											// Agregando detalle
												$d = 0;
												$total_tmh = 0;
												$total_monto = 0;

												while ($d < count($arr_datos)){
													$html .= '	<tr style="font-size: 11px;">';
													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
													$html .= '			'.$arr_datos[$d]["cod_lote"].((strlen($arr_datos[$d]["num_parte"]) == 0) ? '' : '<br>PARTE '.$arr_datos[$d]["num_parte"]);
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
													$html .= '			'.(($id_planta == 3) ? $arr_datos[$d]["codigo_planta"] : $arr_datos[$d]["llegadaplanta_codigoplanta"]).((strlen($arr_datos[$d]["num_parte"]) == 0) ? '' : '<br>PARTE '.$arr_datos[$d]["num_parte"]);
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
													$html .= '			AUM';
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
													$html .= '			'.$arr_datos[$d]["DESTINO_PLANTA"];
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
													$html .= '			'.(($arr_datos[$d]["ID_TIPOCARGA"] == 5) ? $arr_datos[$d]["num_bigbag"] : '').' '.$arr_datos[$d]["TIPO_CARGA"];
													$html .= '		</td>';

													// Obteniendo montos
														$tmh = $arr_datos[$d]["TMH_TOTAL"];
														$tarifa = ((strlen($arr_datos[$d]["TARIFA_CIERRE"]) == 0) ? $arr_datos[$d]["tarifa_sin_igv"] : $arr_datos[$d]["TARIFA_CIERRE"]);
														$sub_total = $tmh * $tarifa;

														$total_tmh += $tmh;
														$total_monto += $sub_total;

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right;">';
													$html .= '			'.number_format($tmh, 2, '.', ',');
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right;">';
													$html .= '			'.number_format($tarifa, 2, '.', ',');
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right;">';
													$html .= '			<b>'.$moneda.' '.number_format($sub_total, 2, '.', ',').'</b>';
													$html .= '		</td>';
													$html .= '	</tr>';

													$d ++;
												}

											// Agregando resumen
												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="4">';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; font-size: 10px;">';
												$html .= '			Sub Total';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '			'.number_format($total_tmh, 2, '.', ',');
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '			'.$moneda.' '.number_format($tarifa, 2, '.', ',');
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '			'.$moneda.' '.number_format($total_monto, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="6">';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; background-color: #F2F2F2; font-size: 10px;">';
												$html .= '			Valor de Flete';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right; font-weight: bold; background-color: #F2F2F2;">';
												$html .= '			'.$moneda.' '.number_format($total_monto, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="6">';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; font-size: 10px;">';
												$html .= '			IGV (18%)';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '			'.$moneda.' '.number_format($total_monto * (18/100), 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="6">';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; font-size: 10px; background-color: #F2F2F2;">';
												$html .= '			Valor Total';
												$html .= '		</td>';

												// Obtiene el Valor Total
													$valor_total = $total_monto + ($total_monto * (18/100));

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right; font-weight: bold; background-color: #F2F2F2;">';
												$html .= '			'.$moneda.' '.number_format($valor_total, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

		$html .= '			</table>
									</div>';

		// Valida si tiene Valor Referencial
			if ($tiene_VR == 1){
				$html .= '<div class="row" style="margin-top: 20px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 11px;">
												<td colspan="5" style="border: solid; border-width: 1px; border-color: #E6E9ED; font-size: 11px; font-weight: bold;">
													Cálculo de monto a pagar y detracción
												</td>
											</tr>

											<tr style="font-size: 11px;">
												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 350px; text-align: center;">
													Ruta para determinación de valor referencial
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">
													Valor referencial  ('.$moneda.'xTNE)
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">
													Valor referencial  ('.$moneda.'xTNE)
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">
													TNE TOTALES
												</td>

												<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; text-align: center;">
													Valor referencial total
												</td>
											</tr>';

											// Obtiene los datos del Valor Referencial configurado
												if (strlen($subruta1) > 0){
													$num_rows = 1;

													// Determina el Número de rows
														if (strlen($subruta2) > 0){
															$num_rows = 2;
														}

													// Obtiene el Total del Valor Referencial
														$total_VR = $subruta1_valorreferencial + $subruta2_valorreferencial;

													// Registra detalle
														$v = 1;

														while ($v <= $num_rows){
															$html .= '<tr style="font-size: 11px;">';
															$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; width: 350px;">';
															$html .= '		'.${'subruta'.$v};
															$html .= '	</td>';

															$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
															$html .= '		'.${'subruta'.$v.'_valorreferencial'};
															$html .= '	</td>';

															if ($v == 1){
																$html .= '	<td rowspan="'.$num_rows.'" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
																$html .= '		'.$moneda.' '.$total_VR;
																$html .= '	</td>';

																$html .= '	<td rowspan="'.$num_rows.'" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: center;">';
																$html .= '		'.number_format($total_tmh, 2, '.', ',');
																$html .= '	</td>';

																// Obtiene el Total del Valor Referencial
																	$total_VR_x = ($total_VR * $total_tmh);

																$html .= '	<td rowspan="'.$num_rows.'" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; text-align: right;">';
																$html .= '		'.$moneda.' '.number_format($total_VR_x, 2, '.', ',');
																$html .= '	</td>';
															}

															$html .= '</tr>';

															$v ++;
														}
												}

											// Determina el Monto Mayor
												$monto_mayor = 0;
												$texto_resumen = '';

												if ($valor_total > $total_VR_x){
													$monto_mayor = $valor_total;
													$texto_resumen = 'VALOR TOTAL FT';
												}
												else{
													$monto_mayor = $total_VR_x;
													$texto_resumen = 'VALOR REFERENCIAL';
												}

											// Setea el Final del Valor Referencial
												$html .= '<tr style="font-size: 11px;">';
												$html .= '	<td colspan="3">';
												$html .= '	</td>';

												$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; font-size: 10px;">';
												$html .= '		Monto Mayor';
												$html .= '	</td>';

												$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; text-align: right;">';
												$html .= '		'.$moneda.' '.number_format($monto_mayor, 2, '.', ',');
												$html .= '	</td>';
												$html .= '</tr>';

												$html .= '<tr style="font-size: 11px;">';
												$html .= '	<td colspan="3">';
												$html .= '	</td>';

												$html .= '	<td colspan="2" style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; text-align: center; font-size: 10px; background-color: #F2F2F2;">';
												$html .= '		'.$texto_resumen;
												$html .= '	</td>';
												$html .= '</tr>';

												$html .= '<tr style="font-size: 11px;">';
												$html .= '	<td colspan="3">';
												$html .= '	</td>';

												$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-size: 10px;">';
												$html .= '		Detracción (4%)';
												$html .= '	</td>';

												$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; text-align: right;">';
												$html .= '		'.$moneda.' '.number_format($monto_mayor * (4/100), 0, '.', ',');
												$html .= '	</td>';
												$html .= '</tr>';

												$html .= '<tr style="font-size: 11px;">';
												$html .= '	<td colspan="3">';
												$html .= '	</td>';

												$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-size: 10px; font-weight: bold; background-color: #F2F2F2;">';
												$html .= '		Monto a pagar';
												$html .= '	</td>';

												$html .= '	<td style="border: solid; border-width: 1px; border-color: #E6E9ED; vertical-align: middle; font-weight: bold; text-align: right; background-color: #F2F2F2;">';
												$html .= '		'.$moneda.' '.number_format($valor_total - round($monto_mayor * (4/100), 0), 2, '.', ',');
												$html .= '	</td>';
												$html .= '</tr>';

				$html .= '	</table>
									</div>';
			}

		// Setea firma de la Liquidación
			$html .= '<div class="row" style="margin-top: 100px; margin-left: 50px; margin-right: 50px;">
									<table style="width: 100%; border-spacing: -1px;">
										<tr style="font-size: 11px;">
											<td style="width: 25%;">
												
											</td>

											<td style="width: 50%; border-top: solid; border-top-width: 2px; border-top-color: #E6E9ED; text-align: center;">
												'.$transportista_razonsocial.'
											</td>

											<td style="width: 25%;">
												
											</td>
										</tr>

										<tr style="font-size: 11px;">
											<td style="width: 25%;">
												
											</td>

											<td style="width: 40%; text-align: center;">
												RUC: '.$transportista_ruc.'
											</td>

											<td style="width: 25%;">
												
											</td>
										</tr>
									</table>
								</div>';
 
	// Cierra html
    $html .= '	</body>
							</html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper('A4', 'portrait');
	$document -> render();
	$document -> stream('Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia, array('Attachment' => 0));

?>
