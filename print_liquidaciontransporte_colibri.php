<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_liquidacionmd5 = $_GET["x"];

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
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_liquidaciontransporte_colibri.php')).'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_liquidaciontransporte_colibri.php')).'/';

	// 1. Obteniendo datos
    $d = 1;
    $arr_datos = array();
		$nom_archivo = 'Liquidación Colibrí - Venta Directa';

		$q_datos = "SELECT DATOS.ID_UNIDAD,
											 DATOS.codigo_despacho,
											 DATOS.codigo_planta,
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
							 				 DATOS.llegadaplanta_fechaingreso,
							 				 DATOS.MATERIAL
									FROM (SELECT DISTINCT
															 U.Id AS ID_UNIDAD,
															 PD.codigo_despacho,
															 PD.codigo_planta,

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
											 				 VR.subruta2_valorreferencial,
											 				 DL.llegadaplanta_fechaingreso,
											 				 IFNULL(MF.descripcion, '') AS MATERIAL
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
												       LEFT JOIN tbconfig_materialesflete MF ON LC.id_material = MF.Id
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
							$fecha_cierre = substr($row_datos["FECHAHORA_CERRE"], 0, 10);
        		}

        	array_push($arr_datos, $row_datos);
        }
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Liquidación Colibrí</title>

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
									<div class="row" style="margin-top: 60px; margin-left: 20px; margin-right: 20px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 9px;">
												<td colspan="8" style="text-align: center; border: solid; border-width: 1px; border-color: #000000; width: 400px; font-weight: bold; vertical-align: middle; background-color: #C0C0C0; color: #000000;">
													LIQUIDACIÓN DE FLETE
												</td>

												<td rowspan="3" style="text-align: center; border: solid; border-width: 1px; border-color: #000000; width: 60px; vertical-align: middle;">
													<img src="'.$ruta_images.'logo_colibri.png" style="width: 60px; height: 60px;"/>
												</td>
											</tr>

											<tr style="font-weight: bold; font-size: 9px;">
												<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; width: 70px; color: #000000;">
													Fletero
												</td>

												<td colspan="4" style="text-align: center; border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; width: 400px;">
													'.$transportista_razonsocial.'
												</td>

												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; width: 50px; color: #000000; text-align: center;">
													Placa
												</td>

												<td colspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; width: 70px;">
													'.$placa1.'
												</td>
											</tr>

											<tr style="font-weight: bold; font-size: 9px;">
												<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; width: 70px; color: #000000;">
													RUC
												</td>

												<td colspan="4" style="text-align: center; border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; width: 400px;">
													'.$transportista_ruc.'
												</td>
											</tr>

											<tr style="font-weight: bold; font-size: 9px;">
												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center; width: 50px;">
													Lote
												</td>

												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center; width: 70px;">
													Fecha
												</td>

												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center;">
													Procedencia
												</td>

												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center; width: 200px;">
													Proveedor
												</td>

												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center; width: 60px;">
													Sacos
												</td>

												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center;">
													Material
												</td>

												<td rowspan="2" style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center; width: 60px;">
													TMH
												</td>

												<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center; width: 60px;">
													Flete
												</td>

												<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center;">
													Total
												</td>
											</tr>

											<tr style="font-weight: bold; font-size: 9px;">
												<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center;">
													TMH
												</td>

												<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; background-color: #C0C0C0; color: #000000; text-align: center;">
													S/./TMH
												</td>
											</tr>';

											// Agregando detalle
												$d = 0;
												$total_tmh = 0;
												$total_monto = 0;

												while ($d < count($arr_datos)){
													$html .= '	<tr style="font-size: 11px;">';
													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: center;">';
													$html .= '			'.$arr_datos[$d]["codigo_planta"].((strlen($arr_datos[$d]["num_parte"]) == 0) ? '' : '<br>PARTE '.$arr_datos[$d]["num_parte"]);
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: center;">';
													$html .= '			'.$arr_datos[$d]["llegadaplanta_fechaingreso"];
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: center;">';
													$html .= '			HUANCHACO';
													$html .= '		</td>';

													// Obteniendo el Proveedor Minero del Primer Tramo
														$proveedor_minero = '';

														$q_datos = "SELECT PM.razon_social
																					FROM despachos_primertramo_validaciondatos V
																							 INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
																				 WHERE lote_cod_lote = '".$arr_datos[$d]["cod_lote"]."'
																				GROUP BY PM.razon_social";

														if ($res_datos = mysqli_query($enlace, $q_datos)){
												      if (mysqli_num_rows($res_datos) > 0) {
												        while($row_datos = mysqli_fetch_array($res_datos)){
												        	$proveedor_minero = $row_datos["razon_social"];
												        }
												      }
												    }

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: center;">';
													$html .= '			'.$proveedor_minero;
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: center;">';
													$html .= '			'.(($arr_datos[$d]["ID_TIPOCARGA"] == 5) ? $arr_datos[$d]["num_bigbag"] : '').' '.$arr_datos[$d]["TIPO_CARGA"];
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: center;">';
													$html .= '			'.$arr_datos[$d]["MATERIAL"];
													$html .= '		</td>';

													// Obteniendo montos
														$tmh = $arr_datos[$d]["TMH_TOTAL"];
														$tarifa = $arr_datos[$d]["tarifa_sin_igv"];
														$sub_total = $tmh * $tarifa;

														$total_tmh += $tmh;
														$total_monto += $sub_total;

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: right;">';
													$html .= '			'.number_format($tmh, 2, '.', ',');
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: right;">';
													$html .= '			'.number_format($tarifa, 2, '.', ',');
													$html .= '		</td>';

													$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: right;">';
													$html .= '			<b>'.number_format($sub_total, 2, '.', ',').'</b>';
													$html .= '		</td>';
													$html .= '	</tr>';

													$d ++;
												}

											// Agregando resumen
												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="9" style="height: 20px;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="6">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; border-bottom: solid; border-bottom-width: 1px; border-bottom-color: #000000; vertical-align: middle; text-align: center; font-weight: bold; color: #44546A;">';
												$html .= '			'.number_format($total_tmh, 2, '.', ',');
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; border-bottom: solid; border-bottom-width: 1px; border-bottom-color: #000000; vertical-align: middle; text-align: center; font-weight: bold; color: #44546A;">';
												$html .= '			'.number_format($tarifa, 2, '.', ',');
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right; font-weight: bold; color: #44546A;">';
												$html .= '			'.number_format($total_monto, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="6">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; border-bottom: solid; border-bottom-width: 1px; border-bottom-color: #000000; vertical-align: middle; text-align: center; font-weight: bold; color: #44546A;">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; border-bottom: solid; border-bottom-width: 1px; border-bottom-color: #000000; vertical-align: middle; text-align: center; font-weight: bold; color: #44546A;">';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right; font-weight: bold; color: #44546A;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="9" style="height: 20px;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5">';
												$html .= '		</td>';

												$html .= '		<td colspan="3" style="vertical-align: middle; font-weight: bold;">';
												$html .= '			Valor Flete';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '			'.number_format($total_monto, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="8">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="8">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="9" style="height: 20px;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle; font-weight: bold;">';
												$html .= '			Descuentos';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: center; font-weight: bold;">';
												$html .= '			S/';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: center; font-weight: bold;">';
												$html .= '			$';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle;">';
												$html .= '			Comedor / Víveres';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle; text-align: right; vertical-align: middle; text-align: right;">';
												$html .= '			0.00';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5" style="height: 45px;">';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: bottom;">';
												$html .= '			Combustible';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: bottom; text-align: center;">';
												$html .= '			-38 Glns. D-2';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: bottom; text-align: right;">';
												$html .= '			0.00';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="2">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="border-bottom: solid; border-bottom-width: 1px; border-bottom-color: #000000;">';
												$html .= '		</td>';

												$html .= '		<td>';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle;">';
												$html .= '			Otros';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle;">';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right;">';
												$html .= '			0.00';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="2">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="text-align: center; font-weight: bold;">';
												$html .= '			'.$transportista_razonsocial;
												$html .= '		</td>';

												$html .= '		<td>';
												$html .= '		</td>';

												$html .= '		<td colspan="3" style="vertical-align: middle; font-weight: bold;">';
												$html .= '			Valor Flete';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; font-weight: bold; text-align: right">';
												$html .= '			'.number_format($total_monto, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="2">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="text-align: center; font-weight: bold;">';
												$html .= '			'.$transportista_ruc;
												$html .= '		</td>';

												$html .= '		<td>';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle;">';
												$html .= '			Adelantos Soles';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle">';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right;">';
												$html .= '			0.00';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="2">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="text-align: center; font-weight: bold;">';
												$html .= '			'.$fecha_cierre;
												$html .= '		</td>';

												$html .= '		<td>';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle;">';
												$html .= '			Adelantos Dólares';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle;">';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right;">';
												$html .= '			0.00';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5" style="height: 30px;">';
												$html .= '		</td>';

												$html .= '		<td colspan="3" style="vertical-align: bottom; font-weight: bold;">';
												$html .= '			Valor Flete';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: bottom; text-align: right; font-weight: bold;">';
												$html .= '			'.number_format($total_monto, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												// Obtiene el Valor Total
													$valor_total = $total_monto + ($total_monto * (18/100));

												// Obtiene el Total del Valor Referencial
													$total_VR = $subruta1_valorreferencial + $subruta2_valorreferencial;

												// Obtiene el Total del Valor Referencial
													$total_VR_x = ($total_VR * $total_tmh);

												// Determina el Monto Mayor
													$monto_mayor = 0;

													if ($valor_total > $total_VR_x){
														$monto_mayor = $valor_total;
													}
													else{
														$monto_mayor = $total_VR_x;
													}

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td style="font-weight: bold; text-align: right;">';
												$html .= '			<label style="margin-right: 5px;">';
												$html .= '				S/';
												$html .= '			</label>';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000; vertical-align: middle; text-align: right; font-weight: bold; background-color: #FFFF00;">';
												$html .= '			'.number_format($valor_total + ($monto_mayor * (4/100)), 2, '.', ',');
												$html .= '		</td>';

												$html .= '		<td colspan="3">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle;">';
												$html .= '			IGV 18%';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; font-weight: bold;">';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '			'.number_format($total_monto * (18/100), 2, '.', '');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td style="font-weight: bold; text-align: right;">';
												$html .= '			<label style="margin-right: 5px;">';
												$html .= '				$ ';
												$html .= '			</label>';
												$html .= '		</td>';

												$html .= '		<td style="border: solid; border-width: 1px; border-color: #000000;">';
												$html .= '		</td>';

												$html .= '		<td colspan="3">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle; font-weight: bold;">';
												$html .= '			T/C:';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5">';
												$html .= '		</td>';

												$html .= '		<td colspan="3" style="vertical-align: middle; font-weight: bold;">';
												$html .= '			Valor Total Flete:';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; vertical-align: middle; text-align: right; font-weight: bold; background-color: #FFFF00;">';
												$html .= '			'.number_format($valor_total, 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5">';
												$html .= '		</td>';

												$html .= '		<td colspan="3" style="vertical-align: middle; font-weight: bold;">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="8">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; vertical-align: middle; text-align: right;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5">';
												$html .= '		</td>';

												$html .= '		<td colspan="2" style="vertical-align: middle; font-weight: bold; text-align: right;">';
												$html .= '			Detracción 4%';
												$html .= '		</td>';

												$html .= '		<td>';
												$html .= '		</td>';

												$html .= '		<td style="border-bottom: solid; border-bottom-width: 1px; border-bottom-color: #000000; vertical-align: middle; text-align: right;">';
												$html .= '			'.number_format($monto_mayor * (4/100), 0, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="5"">';
												$html .= '		</td>';

												$html .= '		<td colspan="3" style="vertical-align: middle; font-weight: bold;">';
												$html .= '			Total a Pagar S/';
												$html .= '		</td>';

												$html .= '		<td style="vertical-align: middle; text-align: right; font-weight: bold;">';
												$html .= '			'.number_format($valor_total - round($monto_mayor * (4/100), 0), 2, '.', ',');
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="8">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; vertical-align: middle; text-align: right;">';
												$html .= '		</td>';
												$html .= '	</tr>';

												$html .= '	<tr style="font-size: 11px;">';
												$html .= '		<td colspan="8">';
												$html .= '		</td>';

												$html .= '		<td style="border-top: solid; border-top-width: 1px; border-top-color: #000000; vertical-align: middle; text-align: right;">';
												$html .= '		</td>';
												$html .= '	</tr>';

		$html .= '			</table>
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
