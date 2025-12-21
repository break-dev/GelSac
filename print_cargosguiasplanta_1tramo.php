<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$cod_lote = $_GET["l"];

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
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_cargosguias.php')).'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_cargosguias.php')).'/';

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Cargo Planta 1er tramo</title>

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
									<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 14px;">
												<td style="text-align: left; vertical-align: top; max-width: 10%;">
													<div style="font-family: AgencyFB; font-size: 16px;">
														GRUPO EMPRESARIAL LUBRA S.A.C.
													</div>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px; margin-left: 50px; margin-right: 50px;">
										<table style="width: 100%;">
											<tr style="font-size: 16px;">
												<td style="vertical-align: middle; text-align: center; height: 60px;">
													<div style="font-family: AgencyFBb;">
														<label style="font-family: AgencyFBb;">
															CONSTANCIA DE ENTREGA DE DOCUMENTOS
														</label>
													</div>
												</td>
											</tr>

											<tr style="font-size: 16px;">
												<td style="vertical-align: middle; text-align: justify;">
													<label style="font-family: AgencyFB;">
														Hoy, ____ de _______________ del 20____ se hace constar la entrega al Sr(a): ______________________________________________ con DNI: ________________ ; la documentación que se especifica a continuación:
													</label>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 20px;">
										<table style="width: 100%; border-spacing: -1px;">
											<tr style="font-size: 15px; font-family: AgencyFBb;">
												<td colspan="'.(($id_destino == 3) ? '5' : '4').'" style="text-align: center; border: solid; border-width: 1px; border-color: #D9D9D9; background-color: #D9D9D9; vertical-align: middle;">
													TRAZABILIDAD DE LOTES
												</td>
											</tr>

											<tr style="font-size: 15px; font-family: AgencyFBb;">
												<td colspan= "'.(($id_destino == 3) ? '3' : '2').'">
												</td>

												<td colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													PRIMER TRAMO
												</td>
											</tr>

											<tr style="font-size: 15px; font-family: AgencyFBb;">
												<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													LOTE AUM
												</td>';

												// if ($id_destino == 3){
												// 	$html .= '<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
												// 							LOTE COLIBRI
												// 						</td>';
												// }

		$html .= '					<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle; width: 350px;">
													PROVEEDOR
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													GRR (destinatario, SUNAT)
												</td>

												<td style="text-align: center; border: solid; border-width: 1px; background-color: #D9D9D9; vertical-align: middle;">
													GRT (destinatario, SUNAT)
												</td>
											</tr>
										</thead>

										<tbody>';

	// 2. Obtiene los datos de las guías asociadas del Lote seleccionado
		$guiaremitente_serie = '';
		$guiaremitente_numero = '';
		$lote_id_proveedorminero = '';
		$id_Transportista = '';
		$guias_fecha = '';

		$arr_lotes = array();
		$cadena_lotes = "";

		$q_datos = "SELECT V.guiaremitente_serie,
											 V.guiaremitente_numero,
								       V.lote_id_proveedorminero,
								       T.id_Transportista,
								       V.guias_fecha
									FROM despachos_primertramo_validaciondatos V
											 LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
											 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
											 INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
								 WHERE V.lote_cod_lote = '".$cod_lote."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$guiaremitente_serie = $row_datos["guiaremitente_serie"];
					$guiaremitente_numero = $row_datos["guiaremitente_numero"];
					$id_proveedorminero = $row_datos["lote_id_proveedorminero"];
					$id_transportista = $row_datos["id_Transportista"];
					$guias_fecha = $row_datos["guias_fecha"];

					// 3. Obteniendo información de guías
						$q_info = "SELECT V.lote_cod_lote,
															PM.razon_social AS PROVEEDOR_MINERO,
											        V.guiaremitente_serie,
											        V.guiaremitente_numero,
											        V.guiatransportista_serie,
											        V.guiatransportista_numero
												 FROM despachos_primertramo_validaciondatos V
															LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
															INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
															INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
												WHERE V.guiaremitente_serie = '".$guiaremitente_serie."'
												  AND V.guiaremitente_numero = '".$guiaremitente_numero."'
												  AND V.lote_id_proveedorminero = ".$id_proveedorminero."
												  AND T.id_Transportista = ".$id_transportista."
												  AND V.guias_fecha = '".$guias_fecha."'
											 ORDER BY V.lote_cod_lote";

						if ($res_info = mysqli_query($enlace, $q_info)){
				      if (mysqli_num_rows($res_info) > 0) {
				        while($row_info = mysqli_fetch_array($res_info)){
									$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';
				        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				        	$html .= '							'.$row_info["lote_cod_lote"];
				        	$html .= '						</td>';

				        	// if ($id_destino == 3){
				        	// 	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
					        // 	$html .= '							'.((strlen($cod_planta) > 0) ? $cod_planta : '').((strlen($num_parte) > 0) ? ' PARTE '.$num_parte : '');
					        // 	$html .= '						</td>';
				        	// }

				        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				        	$html .= '							'.$row_info["PROVEEDOR_MINERO"];
				        	$html .= '						</td>';

				        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				        	$html .= '							'.$row_info["guiaremitente_serie"].' - '.$row_info["guiaremitente_numero"];
				        	$html .= '						</td>';

				        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				        	$html .= '							'.$row_info["guiatransportista_serie"].' - '.$row_info["guiatransportista_numero"];
				        	$html .= '						</td>';
									$html .= '					</tr>';

									// Guardando lotes
            				$arr_lotes[] = $row_info['lote_cod_lote'];
								}

								// Eliminar duplicados del array
					        $arr_lotes = array_unique($arr_lotes);

				        // Construir la cadena concatenando los valores de lote_cod_lote
				        	$cadena_lotes = implode(" - ", $arr_lotes);
							}
						}
        }
      }
    }

		$html .= '					</tbody>
										</table>
									</div>';

	// 3. Completnado pie de página
		$html .= '<div class="row" style="margin-top: 50px; margin-left: 150px;">
								<table style="width: 100%;">
									<tr style="font-size: 14px; font-family: AgencyFB;">
										<td style="vertical-align: top;">
											Para mayor constancia de lo recepcionado firmo la presente en señal de conformidad.
										</td>
									</tr>
								</table>
							</div>';

		$html .= '<div class="row" style="margin-top: 30px; margin-left: 150px;">
								<table style="width: 100%;">
									<tr style="font-size: 14px;">
										<td style="vertical-align: top; font-family: AgencyFB; height: 30px;">
											FIRMA: ___________________________________
										</td>
									</tr>

									<tr style="font-size: 14px;">
										<td style="vertical-align: top; font-family: AgencyFB;">
											HORA: ____________________________________
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
	$document -> stream('Cargo Planta 1er tramo - '.$cadena_lotes, array('Attachment' => 0));

?>
