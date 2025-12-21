<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_distribucionunidad = $_GET["x"];
	// $id_modalidadenvio = $_GET["m"];

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
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_rci.php')).'images/tipo_vehiculos/';

	// 1. Obteniendo Destino
    $id_destino = 0;
    $destino = '';

		$q_datos = "SELECT P.id_planta,
											 UPPER(D.descripcion) AS DESTINO
								  FROM despachos_segundotramo_distribucion_unidades U
											 INNER JOIN despachos_segundotramo_programacion P ON U.id_programacion = P.Id
									     INNER JOIN tbconfig_plantas D ON P.id_planta = D.Id
								 WHERE MD5(U.Id) = '".$id_distribucionunidad."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
			if (mysqli_num_rows($res_datos) > 0) {
				$estado = 1;

				while($row_datos = mysqli_fetch_array($res_datos)){
					$id_destino = $row_datos["id_planta"];
					$destino = $row_datos["DESTINO"];
        }
      }
    }

  // 2. Obteniendo Guías
    $g = 1;
    $guia_remitente = '';
    $guia_transportista = '';
    $arr_guias = '';

    $fecha_guias = '';

    $conductor_dni = '';
    $conductor_nombres = '';

    $placa_1 = '';
    $placa_2 = '';
    $tipo_vehiculo = '';
    $img_tipovehiculo = '';

		$q_datos = "SELECT DISTINCT DL.guiaremitente_serie,
																DL.guiaremitente_numero,
												        DL.guiatransportista_serie,
												        DL.guiatransportista_numero,
												        CO.licencia_conducir,
												        CO.nombres AS CONDUCTOR_NOMBRES,
												        T.cplaca,
																T_x.cplaca AS PLACA2,
																TV.descripcion AS TIPO_VEHICULO,
																TV.imagen AS IMG_TIPOVEHICULO,
																DL.guias_fecha,
																U.inforci_fechasalida,
																U.inforci_horasalida,
																U.inforci_infoprecintos,
																U.inforci_telefono
													 FROM despachos_segundotramo_programacion_detalle PD
																INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
													      INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
													      INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
													      	AND PD.cod_lote = DL.cod_lote
													      INNER JOIN tbconfig_conductores CO ON DL.guias_idchofer = CO.Id
												        LEFT JOIN transporte T ON U.id_unidad = T.id_transporte
												        LEFT JOIN transporte T_x ON U.id_unidad2 = T_x.id_transporte
												        LEFT JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
													WHERE MD5(U.Id) = '".$id_distribucionunidad."'";
														// AND DL.guias_idmodalidadenvio = ".$id_modalidadenvio;

		if ($res_datos = mysqli_query($enlace, $q_datos)){
			if (mysqli_num_rows($res_datos) > 0) {
				while($row_datos = mysqli_fetch_array($res_datos)){
					$guia_remitente = $row_datos["guiaremitente_serie"].' '.$row_datos["guiaremitente_numero"];
    			$guia_transportista = $row_datos["guiatransportista_serie"].' '.$row_datos["guiatransportista_numero"];

    			$arr_guias .= '('.$guia_remitente.((strlen($guia_transportista) == 0) ? '' : '/'.$guia_transportista).') ';

    			// Datos del conductor
	    			if ($g == 1){
	    				$conductor_dni = $row_datos["licencia_conducir"];
	    				$conductor_nombres = $row_datos["CONDUCTOR_NOMBRES"];
	    				$placa_1 = $row_datos["cplaca"];
    					$placa_2 = $row_datos["PLACA2"];
    					$tipo_vehiculo = $row_datos["TIPO_VEHICULO"];
    					$img_tipovehiculo = $row_datos["IMG_TIPOVEHICULO"];
	    				$fecha_guias = $row_datos["guias_fecha"];
	    				$guias_fecha = $row_datos["guias_fecha"];
							$inforci_fechasalida = $row_datos["inforci_fechasalida"];
							$inforci_horasalida = $row_datos["inforci_horasalida"];
							$inforci_infoprecintos = $row_datos["inforci_infoprecintos"];
							$inforci_telefono = $row_datos["inforci_telefono"];
	    			}

    			$g ++;
        }

        $arr_guias = substr($arr_guias, 0, -2).')';
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>RCI '.$destino.'</title>

									<style>
										@font-face {
									    font-family : "AgencyFB";
									    src: url("fonts/AgencyFB.ttf");
										}

										@font-face {
									    font-family : "AgencyFBb";
									    src: url("fonts/AgencyFB-Bold.ttf");
										}

										.fstyle{
											font: AgencyFB;
										}

										.fstyleb{
											font: AgencyFBb;
										}

										html, body{
											font-family: Arial;
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
									<div class="row" style="margin-top: 10px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr>
												<td style="vertical-align: middle; text-align: center; height: 40px;">
													<div style="font-family: AgencyFBb;">
														<label style="font-family: AgencyFBb; font-size: 25px;">
															RCI '.$destino.'
														</label>
													</div>
												</td>
											</tr>

											<tr style="font-size: 20px;">
												<td style="vertical-align: middle; text-align: center; background-color: #FFFF00;">
													<label style="font-family: AgencyFBb; font-size: 16px;">
														REPORTE CONTROL INTERNO SEGÚN GR '.$arr_guias.'
													</label>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 14px; font-family: AgencyFBb;">
												<td style="vertical-align: middle; text-align: center; background-color: #ED7D31; text-align: center; border: solid; border-width: 1px;">
													CONDUCTOR:
												</td>

												<td style="vertical-align: middle; text-align: center; text-align: center; border: solid; border-width: 1px;">
													'.mb_strtoupper($conductor_nombres).'
												</td>

												<td style="vertical-align: middle; text-align: center; background-color: #ED7D31; text-align: center; border: solid; border-width: 1px;">
													LICENCIA DE CONDUCIR:
												</td>

												<td style="vertical-align: middle; text-align: center; text-align: center; border: solid; border-width: 1px;">
													'.$conductor_dni.'
												</td>
											</tr>

											<tr style="font-size: 14px; font-family: AgencyFBb;">
												<td style="vertical-align: middle; text-align: center; background-color: #ED7D31; text-align: center; border: solid; border-width: 1px;">
													PLACA:
												</td>

												<td style="vertical-align: middle; text-align: center; text-align: center; border: solid; border-width: 1px;">
													'.$placa_1.((strlen($placa_2) == 0) ? '' : ' / '.$placa_2).'
												</td>

												<td style="vertical-align: middle; text-align: center; background-color: #ED7D31; text-align: center; border: solid; border-width: 1px;">
													TELÉFONO:
												</td>

												<td style="vertical-align: middle; text-align: center; text-align: center; border: solid; border-width: 1px;">
													'.$inforci_telefono.'
												</td>
											</tr>

											<tr style="font-size: 14px; font-family: AgencyFBb;">
												<td style="vertical-align: middle; text-align: center; background-color: #ED7D31; text-align: center; border: solid; border-width: 1px;">
													FECHA SALIDA:
												</td>

												<td style="vertical-align: middle; text-align: center; text-align: center; border: solid; border-width: 1px;">
													'.$inforci_fechasalida.'
												</td>

												<td style="vertical-align: middle; text-align: center; background-color: #ED7D31; text-align: center; border: solid; border-width: 1px;">
													HORA SALIDA:
												</td>

												<td style="vertical-align: middle; text-align: center; text-align: center; border: solid; border-width: 1px;">
													'.$inforci_horasalida.'
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 30px; margin-right: 30px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 14px; font-family: AgencyFBb;">
												<td colspan="'.(($id_destino == 3) ? '7' : '6').'" style="text-align: center; border: solid; border-width: 1px; background-color: #DBDBDB; vertical-align: middle;">
													INFORMACIÓN DE DESPACHO
												</td>

												<td style="width: 50px;">
													
												</td>

												<td colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #DBDBDB; vertical-align: middle;">
													INFORMACIÓN DE PLANTA
												</td>
											</tr>

											<tr style="font-size: 14px; font-family: AgencyFBb;">';

		if ($id_destino == 3){
			$html .= '<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle; width: 100px;">
									LOTE COLIBRI
								</td>';
		}

		$html .= '					<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle;">
													LOTE AUM
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle; width: 200px;">
													RAZÓN SOCIAL
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle; width: 80px;">
													RUC
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle; width: 70px;">
													N° BIG BAG
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle;">
													MAT.<br>GRANEL
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle; width: 120px;">
													PESO NETO DE LOTE<br>APROXIMADO (TM)
												</td>

												<td style="width: 50px;">
													
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle;">
													CODIFICACIÓN<br>LOTE
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle;">
													HUMEDAD (%)
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle;">
													PESO NETO<br>BALANZA
												</td>
											</tr>
										</thead>

										<tbody>';

	// 2. Arma la estructura de Detalle
		$d = 1;
		$total_TNE = 0;

		$cod_planta = '';
		$cod_lote = '';
		$num_parte = '';

		$info_lotes = '';

		$q_datos = "SELECT DL.cod_lote,
											 DB.descripcion AS DESCRIPCION_BIEN,
											 DL.guias_pesonetoajustado,
											 PD.codigo_planta,
											 DL.cod_lote,
											 DL.num_parte,
											 DL.id_tipocarga,
								       TC.descripcion AS TIPO_CARGA,
								       DL.num_bigbag,
								       DL.guiaremitente_serie,
								       DL.guiaremitente_numero,
								       DL.guiatransportista_serie,
								       DL.guiatransportista_numero,

								       CASE WHEN (DL.guias_idmodalidadenvio = 3 OR DL.guias_idmodalidadenvio = 4 OR DL.guias_idmodalidadenvio = 5) AND (PD.id_planta = 3 OR PD.id_planta = 15)
								       	 THEN CONCAT(DL.guias_remitenteruc, ' - ', DL.guias_remitenterazonsocial)
								       ELSE (SELECT DISTINCT CONCAT(PM.documento, ' - ', PM.razon_social)
															 FROM despachos_primertramo_validaciondatos V
																		INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
															WHERE V.lote_cod_lote = DL.cod_lote) END AS PROVEEDOR_MINERO,

											 P.id_planta,
											 IFNULL(PD.cmh_codigodocumentos, '') AS CMH_CODIGODOCUMENTOS,
											 IFNULL(PD.cmh_codigoguias, '') AS CMH_CODIGOGUIAS,

											 (SELECT COUNT(DL_x.Id) AS _COUNT
											 		FROM despachos_segundotramo_distribucion_lotes DL_x
											 	 WHERE DL_x.cod_lote = PD.cod_lote) AS TOTAL_PARTES

								  FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
										   INNER JOIN despachos_segundotramo_distribucion_unidades U ON P.Id = U.id_programacion
							         INNER JOIN despachos_segundotramo_distribucion_lotes DL ON U.Id = DL.id_distribucionunidad
							           AND PD.cod_lote = DL.cod_lote
								  		 LEFT JOIN tbconfig_segundotramo_guiasdescripcionbien DB ON DL.guias_iddescripcionbien = DB.Id
									  	 INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
								 WHERE MD5(U.Id) = '".$id_distribucionunidad."'
									 -- AND DL.guias_idmodalidadenvio = ".$id_modalidadenvio."
								 	 /*AND MD5(DL.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(DL.guiaremitente_numero) = '".$numero_guia."'*/
								ORDER BY DL.cod_lote";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$cod_planta = $row_datos["codigo_planta"];
					$cod_lote = $row_datos["cod_lote"];
					$num_parte = $row_datos["num_parte"];
					$id_tipocarga = $row_datos["id_tipocarga"];
					$tipo_carga = $row_datos["TIPO_CARGA"];
					$num_bigbag = $row_datos["num_bigbag"];
					$guia_remitente = $row_datos["guiaremitente_serie"].' '.$row_datos["guiaremitente_numero"];
					$guia_transportista = $row_datos["guiatransportista_serie"].' '.$row_datos["guiatransportista_numero"];
					$proveedor_minero = $row_datos["PROVEEDOR_MINERO"];
					$pesoneto_ajustado = $row_datos["guias_pesonetoajustado"];
					$id_planta = $row_datos["id_planta"];
					$cmh_codigodocumentos = $row_datos["CMH_CODIGODOCUMENTOS"];
					$cmh_codigoguias = $row_datos["CMH_CODIGOGUIAS"];
					$total_partes = $row_datos["TOTAL_PARTES"];

					if ($id_planta == 15){
						$cmh_codigodocumentos = $cmh_codigodocumentos.(($total_partes > 1) ? ' ('.$num_parte.'/'.$total_partes.')' : '');

						$info_lotes .= $cmh_codigodocumentos.' - '.((strlen($num_bigbag) == 0) ? 'A' : $num_bigbag).' '.$tipo_carga."<br>";
					}
					else{
						$info_lotes .= $cod_lote.((strlen($num_parte) == 0) ? '' : ' (PARTE '.$num_parte.')').' - '.((strlen($num_bigbag) == 0) ? 'A' : $num_bigbag).' '.$tipo_carga."<br>";
					}

        	$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';

        	if ($id_destino == 3){
        		$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
	        	$html .= '							'.((strlen($cod_planta) > 0) ? $cod_planta : '').((strlen($num_parte) > 0) ? '<br>PARTE '.$num_parte : '');
	        	$html .= '						</td>';
        	}

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';

        	if ($id_planta == 15){
        		$html .= '							'.$cmh_codigodocumentos;
        	}
        	else{
        		$html .= '							'.$cod_lote.((strlen($num_parte) > 0) ? '<br>PARTE '.$num_parte : '');
        	}

        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '							'.explode(' - ', $proveedor_minero)[1];
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '							'.explode(' - ', $proveedor_minero)[0];
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$num_bigbag;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '							'.((strlen($num_bigbag) == 0) ? 'X' : '');
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '							'.number_format($pesoneto_ajustado, 2, '.', '');
        	$html .= '						</td>';

        	$html .= '						<td>';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; vertical-align: middle; text-align: center;">';
        	$html .= '						</td>';
					$html .= '					</tr>';

					$d ++;
        }
      }
    }

  // Agrega filas adicionales
    $html .= '				<tr style="font-size: 14px; font-family: AgencyFB;">
    										<td style="height: 40px;">
												</td>
    									</tr>

    									<tr style="font-size: 14px; font-family: AgencyFB;">
    										<td colspan="'.(($id_destino == 3) ? '6' : '5').'" style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle;">
													PRECINTO DE SEGURIDAD
												</td>

												<td colspan="5" style="text-align: center; border: solid; border-width: 1px;">
													'.$inforci_infoprecintos.'
												</td>
											</tr>

											<tr style="font-size: 14px; font-family: AgencyFB;">
    										<td style="height: 20px;">
												</td>
    									</tr>

    									<tr style="font-size: 14px; font-family: AgencyFB;">
    										<td colspan="'.(($id_destino == 3) ? '6' : '5').'" style="text-align: center; border: solid; border-width: 1px; background-color: #00B0F0; vertical-align: middle;">
													CONFORMIDAD PLANTA
												</td>

												<td colspan="5" style="text-align: center; border: solid; border-width: 1px;">
													
												</td>
											</tr>
  									</tbody>
									</table>
								</div>';

	// Agrega imagen
		$html .= '			<div class="row" style="margin-top: 20px; margin-left: 30px; margin-right: 30px; text-align: center;">';
		$html .= '				<img src="'.$ruta_images.$img_tipovehiculo.'" style="width: 550px; height: 220"/>';
		$html .= '			</div>';

	// Agregando cuadro resumen para imagen
		$html .= '			<div class="row" style="border: solid; border-width: 1px; vertical-align: middle; text-align: center; margin-left: 420px; margin-right: 420px; background-color: #DEEBF7; margin-top: -250px; font-family: AgencyFBb; font-size: 16px;">';
		$html .= '				<div class="row">';
		$html .= '					'.$placa_1.((strlen($placa_2) == 0) ? '' : ' / '.$placa_2);
		$html .= '				</div>';

		$html .= '				<div class="row">';
		$html .= '					'.mb_strtoupper($info_lotes);
		$html .= '				</div>';
		$html .= '			</div>';

	// Cierra html
    $html .= '	</body>
							</html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper('A4', 'landscape');
	$document -> render();
	$document -> stream('Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia, array('Attachment' => 0));

?>
