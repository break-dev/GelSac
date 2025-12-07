<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_distribucionunidad = $_GET["x"];
	$remitente_ruc = $_GET["r"];
	$remitente_razonsocial = $_GET["u"];

	// Funciones
		function formatearFecha($fecha) {
	    // Separar fecha
				$dia = str_pad(explode('-', $fecha)[2], 2, '0', STR_PAD_LEFT);
				$mes = nombre_meses(explode('-', $fecha)[1]);
				$anho = explode('-', $fecha)[0];

	    return $dia.' de '.$mes.' del '.$anho;
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
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_pesosmedidas.php')).'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_pesosmedidas.php')).'/';

	// 1. Obteniendo datos de Guías
    $g = 1;
    $arr_guias = '';
		$nom_archivo = 'Pesos y Medidas';

		$q_datos = "SELECT DISTINCT
											 DL.guias_fecha,
											 RE.ruc AS REMITENTE_RUC,
									     RE.razon_social AS REMITENTE_RAZONSOCIAL,
											 CONCAT(DL.guiaremitente_serie, '-', DL.guiaremitente_numero) AS GUIA_REMITENTE,
							         CONCAT(DL.guiatransportista_serie, '-', DL.guiatransportista_numero) AS GUIA_TRANSPORTISTA,
							         PP.direccion AS PUNTO_PARTIDA,
							         PP.departamento,
							         PP.provincia,
							         PP.distrito,
											 T1.cplaca AS PLACA1,
											 T1.largo AS PLACA1_LARGO,
											 T1.ancho AS PLACA1_ANCHO,
											 T1.alto AS PLACA1_ALTO,
											 T2.cplaca AS PLACA2,
											 T2.largo AS PLACA2_LARGO,
											 T2.ancho AS PLACA2_ANCHO,
											 T2.alto AS PLACA2_ALTO,
											 CV.codigo AS CONFIGURACION_VEHICULAR,
											 U.configuracionvehicular_pesobrutomaximo,
											 U.configuracionvehicular_pesobrutomaximo2,
											 DB.descripcion AS DESCRIPCION_BIEN
									FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
											 INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
											 INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
											   AND PD.cod_lote = DL.cod_lote
											 INNER JOIN tbconfig_remitentessegundotramo RE ON DL.guias_iddestino = RE.id_destino
											   AND DL.guias_idmodalidadenvio = RE.id_modalidadenvio
											 INNER JOIN despachos_primertramo_validaciondatos V ON PD.cod_lote = V.lote_cod_lote
											 LEFT JOIN tbconfig_puntospartidasegundotramo PP ON P.id_planta = PP.id_destino
									           AND v.despacho_id_modalidadenvio = PP.id_modalidadenvio
											 INNER JOIN transporte T1 ON U.id_unidad = T1.id_transporte
											 LEFT JOIN transporte T2 ON U.id_unidad2 = T2.id_transporte
											 INNER JOIN tbconfig_configuracionvehicular CV ON U.id_configuracionvehicular = CV.Id
											 INNER JOIN tbconfig_segundotramo_guiasdescripcionbien DB ON DL.guias_iddescripcionbien = DB.Id
								 WHERE MD5(U.Id) = '".$id_distribucionunidad."'
									 AND RE.ruc = '".$remitente_ruc."'
									 AND DL.guiaremitente_serie IS NOT NULL
								ORDER BY 1";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	// Obteniendo la primera guía para cabecera
        		$guia_remitente = $row_datos["GUIA_REMITENTE"];

        		if ($g == 1){
        			$fecha_guia = formatearFecha($row_datos["guias_fecha"]);
        			$guia_remitente_x = $row_datos["GUIA_REMITENTE"];
        			$remitente_ruc = $row_datos["REMITENTE_RUC"];
        			$remitente_razonsocial = $row_datos["REMITENTE_RAZONSOCIAL"];
        			$punto_partida = $row_datos["PUNTO_PARTIDA"];
        			$departamento = $row_datos["departamento"];
        			$provincia = $row_datos["provincia"];
        			$distrito = $row_datos["distrito"];
        			$placa1 = $row_datos["PLACA1"];
        			$placa1_largo = $row_datos["PLACA1_LARGO"];
        			$placa1_ancho = $row_datos["PLACA1_ANCHO"];
        			$placa1_alto = $row_datos["PLACA1_ALTO"];
        			$placa2 = $row_datos["PLACA2"];
        			$placa2_largo = $row_datos["PLACA2_LARGO"];
        			$placa2_ancho = $row_datos["PLACA2_ANCHO"];
        			$placa2_alto = $row_datos["PLACA2_ALTO"];
        			$configuracion_vehicular = $row_datos["CONFIGURACION_VEHICULAR"];
        			$pesobruto_maximo = $row_datos["configuracionvehicular_pesobrutomaximo"];
        			$pesobruto_maximo2 = $row_datos["configuracionvehicular_pesobrutomaximo2"];
        			$descripcion_bien = $row_datos["DESCRIPCION_BIEN"];

        			// Calcula la mayor de las Toneladas Distribuídas
        				$q_toneladas = "SELECT DL.peso_bruto
																	FROM despachos_segundotramo_programacion_detalle PD
																			 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																			 INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
																			 INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
																			   AND PD.cod_lote = DL.cod_lote
																			 INNER JOIN tbconfig_remitentessegundotramo RE ON DL.guias_iddestino = RE.id_destino
																			   AND DL.guias_idmodalidadenvio = RE.id_modalidadenvio
																 WHERE MD5(U.Id) = '".$id_distribucionunidad."'
																	 AND RE.ruc = '".$remitente_ruc."'
																	 AND DL.guiaremitente_serie IS NOT NULL
																ORDER BY DL.peso_bruto DESC LIMIT 1";

								if ($res_toneladas = mysqli_query($enlace, $q_toneladas)){
						      if (mysqli_num_rows($res_toneladas) > 0) {
						        while($row_toneladas = mysqli_fetch_array($res_toneladas)){
						        	$peso_distribuido = $row_toneladas["peso_bruto"];
						        }
						      }
						    }
        		}

        	// Armando array de guías
        		$arr_guias .= $guia_remitente.' / ';

        	$g ++;
        }

        $arr_guias = substr($arr_guias, 0, -3);
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Formato Pesos Medidas</title>

									<style>
										html, body{
											font-family: Arial, sans-serif;
											margin: 0;
											padding: -5;
											margin-bottom: -15px;
											font-size: 14px;
										}

										@page{
											margin: 0;
											pading: 0;
										}
									</style>
								</head>

								<body style="margin-left: 10px; margin-right: 10px;">
									<div class="row" style="margin-top: 30px; margin-left: 25px; margin-right: 25px;">
										<table style="width: 100%;">
											<tr style="font-size: 18px; font-weight: bold;">
												<td style="vertical-align: middle; text-align: center;">
													CONSTANCIA DE VERIFICACIÓN DE PESOS Y MEDIDAS
												</td>
											</tr>

											<tr style="font-size: 10.5px; font-weight: bold;">
												<td style="vertical-align: middle; text-align: center;">
													ALMACENES, TERMINALES DE ALMACENAMIENTO, TERMINALES PORTUARIOS O AEROPORTUARIOS, GENERADORES, DADORES O
												</td>
											</tr>

											<tr style="font-size: 10.5px; font-weight: bold;">
												<td style="vertical-align: middle; text-align: center;">
													REMITENTES DE LA MERCANCÍA
												</td>
											</tr>

											<tr style="font-size: 10.5px; font-weight: bold;">
												<td style="vertical-align: middle; text-align: center;">
													DECRETO SUPREMO Nº 058-2003-MTC REGLAMENTO NACIONAL DE VEHÍCULOS Y SUS NORMAS MODIFICATORIAS
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 60px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr>
												<td style="vertical-align: top; font-size: 11px; width: 385px;">
													Fecha: '.$fecha_guia.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 7px; font-weight: bold; font-size: 14px;">
													REGISTRO N°
												</td>

												<td style="vertical-align: middle; text-align: center; text-align: center; border: solid; border-width: 2px; vertical-align: middle; width: 100px;">
													'.$guia_remitente_x.'
												</td>
											</tr>

											<tr>
												<td style="font-weight: bold; vertical-align: top; font-size: 11px;">
													I) DATOS DEL GENERADOR DE CARGA
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 2px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -2px;">
											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 60px;">
													NOMBRE DE<br>LA EMPRESA
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 310px;">
													'.$remitente_razonsocial.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 40px;">
													N° RUC
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px;">
													'.$remitente_ruc.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 9px; width: 40px;">
													TELEF.
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 97px;">
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px;">
													DIRECCIÓN
												</td>

												<td colspan="5" style="vertical-align: middle; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px;">
													'.$punto_partida.'
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 2px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -2px;">
											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 54px;">
													DISTRITO
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 150px;">
													'.$distrito.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 40px;">
													PROVINCIA
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 150px;">
													'.$provincia.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 9px; width: 40px;">
													DEPARTAMENTO
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 130px;">
													'.$departamento.'
												</td>
											</tr>

											<tr>
												<td colspan="6" style="vertical-align: middle; font-weight: bold; font-size: 11px;">
													<div style="margin-top: 10px;">
														II) TIPO DE MERCANCÍA TRANSPORTADA: '.mb_strtoupper($descripcion_bien).'
													</div>
												</td>
											</tr>

											<tr>
												<td colspan="6" style="vertical-align: middle; font-size: 11px;">
													<div style="margin-top: 2px;">
														Según Guía de Remisión que se Adjunta: '.$arr_guias.'
													</div>
												</td>
											</tr>

											<tr>
												<td colspan="6" style="vertical-align: middle; font-weight: bold; font-size: 11px;">
													<div style="margin-top: 10px;">
														III) TIPO DE CONTROL EFECTUADO
													</div>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 5px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -2px;">
											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 60px;">
													BALANZA
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 60px;">
													X
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 60px;">
													SOFTWARE
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 60px;">
													
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 9px; width: 60px;">
													CUBICACIÓN
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 60px;">
													
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 9px; width: 60px;">
													OTROS
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 9px; width: 60px;">
													
												</td>
											</tr>

											<tr>
												<td colspan="8" style="vertical-align: middle; font-weight: bold; font-size: 11px;">
													<div style="margin-top: 10px;">
														IV) DATOS DEL VEHICULO
													</div>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 5px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -2px;">
											<tr>
												<td rowspan="2" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 100px;">
													PLACAS<br>
													(camión, tracto, remolque,<br>semiremolque, carretas)
												</td>

												<td colspan="3" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 90px;">
													DIMENSIÓN TOTAL DEL<br>VEHICULO<br>
													(incluida la mercancía)
												</td>

												<td rowspan="2" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 70px;">
													CONFIGURACIÓN<br>
													VEHICULAR
												</td>

												<td rowspan="2" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 80px;">
													PESO BRUTO<br>
													VEHICULAR MAX.<br>
													PERMITIDO (Kg.)<br>
													(1)
												</td>

												<td rowspan="2" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 90px;">
													PESO BRUTO TOTAL<br>
													TRANSPORTADO<br>
													(Kg.)
												</td>

												<td rowspan="2" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 100px;">
													PBMax. Para no control de<br>
													pesos por ejes (DS 006-<br>
													2008-MTC)(Kg)<br>
													(2)
												</td>

												<td rowspan="2" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px; width: 110px;">
													PBMax. Para no control de<br>
													pesos por ejes (DS 006-2008-<br>
													MTC)(Kg)<br>
													<label style="font-weight: bold; ">
														con Bonificaciones x Susp.<br>
														Neu. y Neumac Extraanch (3)<br>
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px;">
													LARGO<br>
													(mt)
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px;">
													ANCHO<br>
													(mt)
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 3px; font-size: 8px;">
													ALTO<br>
													(mt)
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px; height: 15px;">
													'.$placa1.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$placa1_largo.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$placa1_ancho.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$placa1_alto.'
												</td>

												<td rowspan="3" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$configuracion_vehicular.'
												</td>

												<td rowspan="3" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$pesobruto_maximo.'
												</td>

												<td rowspan="3" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.($peso_distribuido * 1000).'
												</td>

												<td rowspan="3" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
												</td>

												<td rowspan="3" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$pesobruto_maximo2.'
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px; height: 15px;">
													'.$placa2.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$placa2_largo.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$placa2_ancho.'
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
													'.$placa2_alto.'
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px; height: 15px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; vertical-align: middle; padding: 3px; font-size: 8px;">
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 10px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -2px;">
											<tr>
												<td style="vertical-align: middle; font-size: 7px; height: 15px;">
													(1) SE OBTIENE DEL ANEXO IV DEL RNV. DS 058-2003
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; font-size: 7px; height: 30px;">
													(2) EL GENERADOR DEBERÁ CONTROLAR QUE EL PESO BRUTO TRANSPORTADO NO SEA MAYOR QUE EL 95% DE LAS SUMATORIA DE LOS PESOS POR EJES O CONJUNTOS DE EJES INDICADOS EN EL ANEXO IV DEL RNV
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; font-size: 7px;">
													(3) PB MAX PARA NO CONTROL P x EJES A VEHÍCULOS CON BONIFICACIONES PERMITIDAS PARA SUSP. NEUMÁTICA Y NEUMAT EXTRA ANCHOS
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 15px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr>
												<td style="font-weight: bold; vertical-align: top; font-size: 11px;">
													V) CONTROL DE PESOS POR EJE O CONJUNTO DE EJES:
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 11px;">
													Para aquellos vehículos que exceden el 95% de la suma de los pesos por ejes
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 3px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr>
												<td colspan="7" style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; vertical-align: middle; padding: 10px; font-size: 11px;">
													DISTRIBUCIÓN DE PESOS POR CONJUNTO DE EJES EN KG
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 10px; font-size: 11px;">
													PESOS
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 10px; font-size: 11px;">
													1er cjto
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 10px; font-size: 11px;">
													2do cjto
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 10px; font-size: 11px;">
													3er cjto
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 10px; font-size: 11px;">
													4to cjto
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 10px; font-size: 11px;">
													5to cjto
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 10px; font-size: 11px;">
													6to cjto
												</td>
											</tr>

											<tr>
												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; background-color: #C7FFED; padding: 3px; font-size: 11px; height: 15px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; padding: 3px; font-size: 11px; height: 15px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; padding: 3px; font-size: 11px; height: 15px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; padding: 3px; font-size: 11px; height: 15px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; padding: 3px; font-size: 11px; height: 15px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; padding: 3px; font-size: 11px; height: 15px;">
												</td>

												<td style="vertical-align: middle; text-align: center; border: solid; border-width: 2px; padding: 3px; font-size: 11px; height: 15px;">
												</td>
										</table>
									</div>

									<div class="row" style="margin-top: 15px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr>
												<td style="font-weight: bold; vertical-align: top; font-size: 10px;">
													DECRETO SUPREMO N° 058-2003-MTC, modificado por D.S . N° 006-2008-MTC, ANEXO IV: PESOS Y MEDIDAS
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 10px; height: 50px;">
													<b>Artículo 37°.- Pesos máximos permitidos:</b> (…) están exonerados del control de peso por eje o conjunto de ejes, los vehículos o combinaciones vehiculares que transiten con un peso bruto vehicular que no exceda del 95% de la sumatoria de pesos por eje o conjunto de ejes, en tanto este valor no supere el Peso Bruto Vehicular máximo permitido por el presente reglamento o sus normas complementarias
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 11px;">
													<div>
														<b>OBSERVACIONES</b>: ......................................................................................................................................................................................................
													</div>
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 11px;">
													.........................................................................................................................................................................................................................................
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 11px;">
													.........................................................................................................................................................................................................................................
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 11px;">
													.........................................................................................................................................................................................................................................
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr>
												<td style="vertical-align: top; font-size: 10px;">
													....................................................................
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 10px;">
													<div style="margin-left: 6px;">
														Representante del Generador de Carga
													</div>
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 10px;">
													<div style="margin-left: 70px;">
														Firma y Sello
													</div>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr>
												<td style="font-weight: bold; vertical-align: top; font-size: 10px;">
													NOTA
												</td>
											</tr>

											<tr>
												<td style="vertical-align: top; font-size: 8.5px; text-align: justify">
													1,- LO CONSIGNADO EN EL PRESENTE FORMATO TIENE CARÁCTER DE DECLARACIÓN JURADA, POR LO QUE ESTARÁ SUJETO A LO ESTABLECIDO EN EL ART. 32 NUMERAL 32.3 DE LA LEY N° 27444; SIN PERJUICIO D E LA SANCIÓN ADMINISTRATIVA CORRESPONDIENTE. TENIENDO QUE CUMPLIR QUIEN GENERA LA CARGA EL LLENADO DE LA PRESENTE CONSTANCIA.<br>
													2,- Solo para Terminales Portuarios, Aeroportuarios, Almacenes Aduaneros y de carga de Hidrocarburos, LA GUÍA DE SALIDA , CONSTANCIA DE PESO O TICKET DE PESO DE SALIDA, reemplazará a la presente constancia, la cual deberá contener lo indicado en el punto N° I y adicionalmente las Placas, Tipo de Vehiculo y Peso Bruto Total del Vehículo. Cuando el destino de la mercancia es local no se requiere la emisión de esta constancia de control de pesos y medidas.<br>
													3,- Del punto IV - "Dimensión Total del Vehiculo y Carga", será llenado cuando excedan las dimensiones permitidas.<br>
													4.- Para el transporte de contenedores vaciosla presentación del EIR (Equipment Interchance Reception) reemplaza al presente formato; Asimismo, los contenedores no están sujetos al control de pesos por ejes.<br>
													5.- Para el control en las balanzas de las Estaciones de Pesaje; "Peso Bruto Total Transportado", se consideraran las tolerancias del 3% vigente en el pesaje dinamico.<br>
													6.- De no consignar los datos en el punto V, cuando corresponda, el generador declara que los pesos por eje estan dentro de lo permitido en el RNV.
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
