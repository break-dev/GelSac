<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require('libs/phpqrcode/qrlib.php');
	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$serie_guia = $_GET["a"];
	$numero_guia = $_GET["b"];
	$id_remitente = $_GET["c"];
	$id_transportista = $_GET["d"];
	$guia_fecha = $_GET["e"];

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
    $ruta_images = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_djguias.php')).'images/';
    $ruta_images_qr = substr($ruta_images_x, 0, strpos($ruta_images_x, 'print_djguias.php')).'/';

	// 1. Obteniendo datos de la guía
		$q_datos = "SELECT PM.documento AS REMITENTE_RUC,
											 UPPER(PM.razon_social) AS REMITENTE_RAZONSOCIAL,
											 ET.documento AS TRANSPORTISTA_RUC,
											 UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
											 VD.guiaremitente_serie,
											 VD.guiaremitente_numero,
											 VD.guiatransportista_serie,
											 VD.guiatransportista_numero,
											 VD.guias_fecha
									FROM despachos_primertramo_validaciondatos VD
											 INNER JOIN tb_clientes PM ON VD.lote_id_proveedorminero = PM.Id
											 LEFT JOIN transporte T ON VD.balanza_placa = T.cplaca
								  		 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
								 WHERE MD5(VD.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(VD.guiaremitente_numero) = '".$numero_guia."'
									 AND VD.lote_id_proveedorminero = ".$id_remitente."
									 AND T.id_Transportista = ".$id_transportista."
									 AND VD.guias_fecha = '".$guia_fecha."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$remitente_ruc = $row_datos["REMITENTE_RUC"];
					$remitente_razonsocial = $row_datos["REMITENTE_RAZONSOCIAL"];
					$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
					$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
					$guiaR_serie = $row_datos["guiaremitente_serie"];
					$guiaR_numero = $row_datos["guiaremitente_numero"];

					$guiaR = $guiaR_serie.'-'.$guiaR_numero;

					$guiaT_serie = $row_datos["guiatransportista_serie"];
					$guiaT_numero = $row_datos["guiatransportista_numero"];

					$guiaT = $guiaT_serie.'-'.$guiaT_numero;

					$guias_fecha_x = $row_datos["guias_fecha"];

					// Seteando fecha de documento
						$guias_fecha = explode('-', $guias_fecha_x)[2].'/'.explode('-', $guias_fecha_x)[1].'/'.explode('-', $guias_fecha_x)[0];

					// Obteniendo lista de lotes para el detalle
						$arr_lotes = '';

						$q_lotes = "SELECT DISTINCT VD.lote_cod_lote
													FROM despachos_primertramo_validaciondatos VD
															 LEFT JOIN transporte T ON VD.balanza_placa = T.cplaca
												  		 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
												 WHERE MD5(VD.guiaremitente_serie) = '".$serie_guia."'
													 AND MD5(VD.guiaremitente_numero) = '".$numero_guia."'
													 AND VD.lote_id_proveedorminero = ".$id_remitente."
													 AND T.id_Transportista = ".$id_transportista."
													 AND VD.guias_fecha = '".$guia_fecha."'";

						if ($res_lotes = mysqli_query($enlace, $q_lotes)){
				      if (mysqli_num_rows($res_lotes) > 0) {
				        while($row_lotes = mysqli_fetch_array($res_lotes)){
				        	$arr_lotes .= $row_lotes["lote_cod_lote"].' | ';
				        }
				      }
				    }

				    $arr_lotes = substr($arr_lotes, 0, -3);

					// Obteniendo lista de lotes para el nombre del documento
						$arr_lotes_x = '';

						$q_lotes = "SELECT VD.lote_cod_lote,
															 VD.lote_ticket_orden
													FROM despachos_primertramo_validaciondatos VD
															 LEFT JOIN transporte T ON VD.balanza_placa = T.cplaca
												  		 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
												 WHERE MD5(VD.guiaremitente_serie) = '".$serie_guia."'
													 AND MD5(VD.guiaremitente_numero) = '".$numero_guia."'
													 AND VD.lote_id_proveedorminero = ".$id_remitente."
													 AND T.id_Transportista = ".$id_transportista."
													 AND VD.guias_fecha = '".$guia_fecha."'";

						if ($res_lotes = mysqli_query($enlace, $q_lotes)){
				      if (mysqli_num_rows($res_lotes) > 0) {
				        while($row_lotes = mysqli_fetch_array($res_lotes)){
				        	$arr_lotes_x .= $row_lotes["lote_cod_lote"].((strlen($row_lotes["lote_ticket_orden"]) == 0) ? '' : '['.$row_lotes["lote_ticket_orden"].']').' | ';
				        }
				      }
				    }

				    $arr_lotes_x = substr($arr_lotes_x, 0, -3);

				  // Seteando nombre del archivo
						$nom_archivo = 'Cargo 1er Tramo - '.$arr_lotes_x;
        }
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>'.$nom_archivo.'</title>

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

								<body style="margin-left: 60px; margin-right: 60px;">';

	// 2. Seteando documento
		$d = 1;

		while ($d <= 2){
			$html .= '<div class="row" style="margin-top: 30px;">
									<label style="font-family: AgencyFB; font-size: 16px;">
										GRUPO EMPRESARIAL LUBRA S.A.C.
									</label>
								</div>

								<div class="row" style="margin-top: 30px; text-align: center; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px; background-color: #D9E1F2;">
									<label style="font-family: AgencyFB; font-size: 16px; ">
										<i><b>CONSTANCIA DE ENTREGA DE GUIAS DE REMISIÓN - '.(($d == 1) ? 'REMITENTE' : 'TRANSPORTISTA').'</b></i>
									</label>
								</div>

								<div class="row" style="margin-top: 20px; text-align: justify; font-size: 16px; font-family: AgencyFB;">
									<table>
										<tr style="font-size: 16px;">
											<td style=" width: 80px;">
												Lote(s):
											</td>

											<td style=" width: 80px; font-family: AgencyFBb;">
												'.$arr_lotes.'
											</td>
										</tr>

										<tr style="font-size: 16px;">
											<td style=" width: 80px;">
												Empresa:
											</td>';

											if ($d == 1){
												$html .= '		<td style=" width: 80px; font-family: AgencyFBb;">
																				'.$remitente_razonsocial.'
																			</td>';
											}
											else{
												$html .= '		<td style=" width: 80px; font-family: AgencyFBb;">
																				'.$transportista_razonsocial.'
																			</td>';
											}

			$html .= '		</tr>

										<tr style="font-size: 16px;">
											<td style=" width: 80px;">
												RUC:
											</td>';

											if ($d == 1){
												$html .= '		<td style=" width: 80px; font-family: AgencyFBb;">
																				'.$remitente_ruc.'
																			</td>';
											}
											else{
												$html .= '		<td style=" width: 80px; font-family: AgencyFBb;">
																				'.$transportista_ruc.'
																			</td>';
											}

			$html .= '		</tr>';

										if ($d == 1){
											$html .= '		<tr style="font-size: 16px;">
																			<td style=" width: 80px;">
																				GRR:
																			</td>

																			<td style=" width: 80px; font-family: AgencyFBb;">
																				'.$guiaR.'
																			</td>
																		</tr>';
										}

										if ($d == 1 || $d == 2){
											$html .= '		<tr style="font-size: 16px;">
																			<td style=" width: 80px;">
																				GRT:
																			</td>

																			<td style=" width: 80px; font-family: AgencyFBb;">
																				'.$guiaT.'
																			</td>
																		</tr>';
										}

			$html .= '		<tr style="font-size: 16px;">
											<td style=" width: 80px;">
												Fecha:
											</td>

											<td style=" width: 80px; font-family: AgencyFBb;">
												______________________
											</td>
										</tr>
									</table>
								</div>';

			// Pie de página
				$html .= '<div class="row" style="margin-top: 30px; text-align: center;">
										<table style="width: 100%;">
											<tr style="font-size: 16px;">
												<td style="vertical-align: top; font-family: AgencyFB; text-align: right; width: 20%;">
													Recibí conforme:
												</td>

												<td style="vertical-align: bottom; font-family: AgencyFB; text-align: center; width: 50%; height: 70px;">
													----------------------------------------------------
												</td>

												<td style="vertical-align: top; font-family: AgencyFB; text-align: center; width: 15%;">

												</td>
											</tr>

											<tr style="font-size: 16px; width: 50%;">
												<td style="vertical-align: top; font-family: AgencyFB; text-align: center; width: 20%;">
													
												</td>

												<td style="vertical-align: center; font-family: AgencyFB; height: 30px; text-align: center; margin-top: -20px;">
													<div style="margin-top: -10px; font-family: AgencyFBb">Firma</div>
												</td>

												<td style="vertical-align: center; font-family: AgencyFB; height: 30px; text-align: center; margin-top: -20px;">
													
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-top: 0px; font-family: AgencyFB; width: 80px; text-align: right;">
										<table style="width: 100%;">
											<tr style="font-family: AgencyFBb; font-size: 16px;">
												<td style="text-align: right; width: 230px;">
													Nombre:
												</td>

												<td style="font-family: AgencyFB;">
													________________________________________
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="font-family: AgencyFB; width: 80px; text-align: right;">
										<table>
											<tr style="font-family: AgencyFBb; font-size: 16px;">
												<td style="text-align: right; width: 230px;">
													DNI:
												</td>

												<td style="font-family: AgencyFB;">
													________________________________________
												</td>
											</tr>
										</table>
									</div>';

			if ($d == 1){
				$html .= '<hr style="margin-top: 40px; margin-bottom: 20px; border-color: #D9D9D9; border-width: 1px;">';
			}

			$d ++;
		}

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
	$document -> stream($nom_archivo, array('Attachment' => 0));

?>
