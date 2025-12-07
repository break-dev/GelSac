<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_programacion = $_GET["x"];

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
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_programaciondistribucion.php')).'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_programaciondistribucion.php')).'/';

	// 1. Obteniendo datos
		$x = 1;
		$d = 1;
		$t = 0;
		$id_distribucionunidad = 0;
		$id_distribucionunidad_x = 0;
		$codigo_despacho_comercializacion = '';
		$html = '';

		$q_datos = "SELECT DISTINCT
											 PD.codigo_despacho,
											 PD.codigo_despacho_comercializacion,
											 CD.correlativo AS CORRELATIVO_DESPACHO
									FROM despachos_segundotramo_programacion_detalle PD
											 INNER JOIN correlativo_despacho CD ON PD.id_programacion = CD.id_programacion
								 WHERE MD5(PD.id_programacion) = '".$id_programacion."'";

			if ($res_datos = mysqli_query($enlace, $q_datos)){
				if (mysqli_num_rows($res_datos) > 0) {
					$estado = 1;

					while($row_datos = mysqli_fetch_array($res_datos)){
						$codigo_despacho = $row_datos["codigo_despacho"];

						if (strlen($row_datos["codigo_despacho_comercializacion"]) > 0){
							$codigo_despacho_comercializacion .= $row_datos["codigo_despacho_comercializacion"].'/';
						}
						else{
							$correlativo_despacho = $codigo_despacho;
						}

						// $correlativo_despacho = $row_datos["CORRELATIVO_DESPACHO"];
						
	        }

	        $codigo_despacho_comercializacion = substr($codigo_despacho_comercializacion, 0, -1);

	        $correlativo_despacho_x = ((strlen($codigo_despacho_comercializacion) > 0) ? $codigo_despacho.'/'.$codigo_despacho_comercializacion : $codigo_despacho);

	        if (strlen($correlativo_despacho_x) > 0){
	        	$correlativo_despacho = $correlativo_despacho_x;
	        }
	      }
	    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Distribución Final - '.$codigo_despacho.'</title>

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
									<div class="row" style="margin-top: 20px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%;">
											<tr style="font-size: 20px;">
												<td style="vertical-align: middle; text-align: center; height: 60px;">
													<div style="font-family: AgencyFBb;">
														<label style="font-family: AgencyFBb;">
															DISTRIBUCIÓN DE DESPACHO ('.$correlativo_despacho.')
														</label>
													</div>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 16px; font-family: AgencyFBb;">
												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													LOTE COLIBRI
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													LOTE AUM
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													PLACA
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													PESO
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													PRESENTACIÓN
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													PROVEEDOR
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													RUC
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													GRR
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													GRT
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													N° CARRO
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #FFC000; vertical-align: middle;">
													N° GUIAS
												</td>
											</tr>
										</thead>

										<tbody>';

	// 2. Arma la estructura de Detalle
		$x = 1;
		$id_distribucionunidad = 0;

		$q_datos = "SELECT DISTINCT DU.Id AS ID_DISTRIBUCIONUNIDAD,
											 PD.codigo_planta,
											 DL.num_parte,
											 DL.cod_lote,
											 T.cplaca,
											 IFNULL(DL.guias_pesonetoajustado, DL.peso_distribuido) AS PESO_NETO,
											 TC.descripcion AS TIPO_CARGA,
											 DL.num_bigbag,

											 CASE WHEN DL.guias_idmodalidadenvio = 3 OR DL.guias_idmodalidadenvio = 4 OR DL.guias_idmodalidadenvio = 5
											   THEN DL.guias_remitenteruc
											 ELSE PM.documento END AS PROVEEDORMINERO_RUC,

											 CASE WHEN DL.guias_idmodalidadenvio = 3 OR DL.guias_idmodalidadenvio = 4 OR DL.guias_idmodalidadenvio = 5
											   THEN DL.guias_remitenterazonsocial
											 ELSE PM.razon_social END AS PROVEEDORMINERO_RAZONSOCIAL,

							         DL.guiaremitente_serie,
							         DL.guiaremitente_numero,
							         DL.guiatransportista_serie,
							         DL.guiatransportista_numero,

											 MD5(DU.Id) AS ID_DISTRIBUCIONUNIDAD_MD5,
											 DL.Id AS ID_DISTRIBUCIONLOTE,
											 /*T.nCapacidad,*/
											 DU.id_tipovehiculo,
											 TV.tiene_carreta,
											 DU.id_unidad,
											 
											 DU.id_unidad2,
											 T_x.cplaca AS PLACA2,
											 (DU.capacidad_unidad * 1000) AS capacidad_unidad,
							         TV.descripcion AS TIPO_VEHICULO,
							         DU.id_responsableunidad,

							    		 IFNULL(UPPER(CONCAT(E.apellido_paterno, ' ', E.apellido_materno, ' ', E.nombres)), '') AS  RESPONSABLE_UNIDAD,

							         
							         IFNULL(DL.num_parte, '') AS NUM_PARTE,
							         VD.lote_id_proveedorminero,
							         
							         EM.nombres AS ENCARGADO_MUESTRA,
							         
							         DL.id_tipocarga,
							         DU.fecha_ingresoplanta,
							         DU.hora_ingresoplanta,
							         IFNULL(DL.color_lote, '') AS COLOR_LOTE,
							         P.is_cerrado,
							         PD.codigo_planta,

							         (SELECT L.is_loteaum
							         		FROM catalogolotes L
							         	 WHERE L.ccod_Lote = DL.cod_lote
							         	LIMIT 1) AS IS_LOTEAUM,

							         (SELECT COUNT(DL_x.Id)
							        	 FROM despachos_segundotramo_distribucion_lotes DL_x
							           WHERE DL_x.id_distribucionunidad = DU.Id) AS TOTAL_LOTES,

							         /*
							         (SELECT COUNT(DISTINCT VD_x.lote_id_proveedorminero, VD_x.despacho_id_modalidadenvio)
							        	 FROM despachos_segundotramo_distribucion_unidades DU_x
							        	 			INNER JOIN despachos_segundotramo_distribucion_lotes DL_x ON DU_x.Id = DL_x.id_distribucionunidad
							        	 			LEFT JOIN despachos_primertramo_validaciondatos VD_x ON DL_x.cod_lote = VD_x.lote_cod_lote
							           WHERE DU_x.Id = DU.Id) AS TOTAL_GUIAS,
						           */

							         (SELECT COUNT(DISTINCT DL_x.guias_remitenteruc)
							         		FROM despachos_segundotramo_distribucion_lotes DL_x
							         	 WHERE DL_x.id_distribucionunidad = DU.Id
							         		 AND DL_x.guiaremitente_serie = DL.guiaremitente_serie
							         		 AND DL_x.guiaremitente_numero = DL.guiaremitente_numero
							         		 AND DL_x.guias_remitenteruc = DL.guias_remitenteruc) AS TOTAL_GUIAS,
								        
							         (SELECT SUM(DL_x.peso_distribuido)
							        	 FROM despachos_segundotramo_distribucion_lotes DL_x
							           WHERE DL_x.id_distribucionunidad = DU.Id) AS TMH_DISTRIBUIDO_TOTAL

							   FROM despachos_segundotramo_distribucion_unidades DU
							   			INNER JOIN despachos_segundotramo_programacion P ON DU.id_programacion = P.Id
									 		INNER JOIN despachos_segundotramo_distribucion_lotes DL ON DU.Id = DL.id_distribucionunidad
									 		INNER JOIN despachos_segundotramo_programacion_detalle PD ON P.Id = PD.id_programacion
							   				AND PD.cod_lote = DL.cod_lote
							        LEFT JOIN transporte T ON DU.id_unidad = T.id_transporte
							        LEFT JOIN transporte T_x ON DU.id_unidad2 = T_x.id_transporte
							        LEFT JOIN tbconfig_tipovehiculo TV ON DU.id_tipovehiculo = TV.Id
							        LEFT JOIN tb_empleados E ON DU.id_responsableunidad = E.Id
							        LEFT JOIN despachos_primertramo_validaciondatos VD ON DL.cod_lote = VD.lote_cod_lote
							        LEFT JOIN tb_clientes PM ON VD.lote_id_proveedorminero = PM.Id
							        LEFT JOIN tbconfig_encargadosmuestra EM ON VD.lote_id_encargadomuestra = EM.Id
							        INNER JOIN correlativo_despacho CD ON P.Id = CD.id_programacion
							        INNER JOIN tbconfig_tipocarga TC ON DL.id_tipocarga = TC.Id
	          WHERE MD5(DU.id_programacion) = '".$id_programacion."'
					 ORDER BY DU.Id, DL.cod_lote";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$codigo_planta = $row_datos["codigo_planta"];
					$num_parte = $row_datos["num_parte"];
					$cod_lote = $row_datos["cod_lote"];
					$placa1 = $row_datos["cplaca"].((strlen($row_datos["PLACA2"]) == 0) ? '' : ' / '.$row_datos["PLACA2"]);
					$peso_neto = $row_datos["PESO_NETO"];
					$num_bigbag = $row_datos["num_bigbag"];
					$tipo_carga = ((strlen($num_bigbag) > 0) ? $num_bigbag.' ' : '').$row_datos["TIPO_CARGA"];
					$proveedorminero_ruc = $row_datos["PROVEEDORMINERO_RUC"];
					$proveedorminero_razonsocial = $row_datos["PROVEEDORMINERO_RAZONSOCIAL"];
					$guia_remitente = $row_datos["guiaremitente_serie"].' '.$row_datos["guiaremitente_numero"];
					$guia_transportista = $row_datos["guiatransportista_serie"].' '.$row_datos["guiatransportista_numero"];
					$total_guias = $row_datos["TOTAL_GUIAS"].' GRR / '.$row_datos["TOTAL_GUIAS"].' GRT';

        	$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';
        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$codigo_planta.((strlen($num_parte) == 0) ? '' : "<br>PARTE ".$num_parte);
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$cod_lote.((strlen($num_parte) == 0) ? '' : "<br>PARTE ".$num_parte);
        	$html .= '						</td>';

        	if ($id_distribucionunidad != $row_datos["ID_DISTRIBUCIONUNIDAD"]){
        		$html .= '						<td rowspan="'.$row_datos["TOTAL_LOTES"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        	$html .= '							'.$placa1;
	        	$html .= '						</td>';
        	}

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.number_format($peso_neto, 2, '.', '');
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$tipo_carga;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$proveedorminero_razonsocial;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$proveedorminero_ruc;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.((strlen(trim($guia_remitente)) > 0) ? $guia_remitente : '<i style="font-size: 12px; color: #F23030;">Pendiente</i>');
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.((strlen(trim($guia_transportista)) > 0) ? $guia_transportista : '<i style="font-size: 12px; color: #F23030;">Pendiente</i>');
        	$html .= '						</td>';

        	if ($id_distribucionunidad != $row_datos["ID_DISTRIBUCIONUNIDAD"]){
        		$html .= '						<td rowspan="'.$row_datos["TOTAL_LOTES"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        	$html .= '							UNIDAD '.$x;
	        	$html .= '						</td>';

        	$html .= '						<td rowspan="'.$row_datos["TOTAL_LOTES"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$total_guias;
        	$html .= '						</td>';

	        	$x ++;
        	}


        	$id_distribucionunidad = $row_datos["ID_DISTRIBUCIONUNIDAD"];

        	// if ($id_destino == 3){
        	// 	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        // 	$html .= '							'.((strlen($cod_planta) > 0) ? $cod_planta : '').((strlen($num_parte) > 0) ? ' PARTE '.$num_parte : '');
	        // 	$html .= '						</td>';
        	// }

        	// $html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	// $html .= '							'.$proveedor_minero;
        	// $html .= '						</td>';

        	// $html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	// $html .= '							'.$guia_remitente;
        	// $html .= '						</td>';

        	// $html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	// $html .= '							'.$guia_transportista;
        	// $html .= '						</td>';
					$html .= '					</tr>';

					$d ++;
        }
      }
    }

		$html .= '					</tbody>
										</table>
									</div>';

	// // 3. Completando pie de página
	// 	$html .= '<div class="row" style="margin-top: 50px; margin-left: 150px;">
	// 							<table style="width: 100%;">
	// 								<tr style="font-size: 14px; font-family: AgencyFB;">
	// 									<td style="vertical-align: top;">
	// 										Para mayor constancia de lo recepcionado firmo la presente en señal de conformidad.
	// 									</td>
	// 								</tr>
	// 							</table>
	// 						</div>';

	// 	$html .= '<div class="row" style="margin-top: 30px; margin-left: 150px;">
	// 							<table style="width: 100%;">
	// 								<tr style="font-size: 14px;">
	// 									<td style="vertical-align: top; font-family: AgencyFB; height: 30px;">
	// 										FIRMA: ___________________________________
	// 									</td>
	// 								</tr>

	// 								<tr style="font-size: 14px;">
	// 									<td style="vertical-align: top; font-family: AgencyFB;">
	// 										HORA: ____________________________________
	// 									</td>
	// 								</tr>
	// 							</table>
	// 						</div>';

	// Cierra html
    $html .= '	</body>
							</html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper('A3', 'landscape');
	$document -> render();
	$document -> stream('Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia, array('Attachment' => 0));

?>
