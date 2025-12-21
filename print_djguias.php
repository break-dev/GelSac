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
		$q_datos = "SELECT VD.guias_idchofer,
											 CD.nombres AS CHOFER_NOMBRES,
									     CD.dni_licencia AS CHOFER_DNI,
									     CD.licencia_conducir AS CHOFER_LICENCIA,
									     CD.domicilio AS CHOFER_DOMICILIO,
									     ET.documento AS TRANSPORTISTA_RUC,
									     ET.razon_social AS TRANSPORTISTA_RAZONSOCIAL,
									     VD.lote_cod_lote,
									     SUM(VD.lote_peso_neto) AS TOTAL_LOTES,
									     VD.guiatransportista_serie,
											 VD.guiatransportista_numero,
									     VD.guias_puntopartida,
									     VD.guias_destinatario,
									     VD.guias_puntodestino,
									     VD.balanza_placa,
									     IFNULL(VD.balanza_placa2, '') AS PLACA2,
									     UPPER(PM.documento) AS REMITENTE_RUC,
											 UPPER(PM.razon_social) AS REMITENTE_RAZONSOCIAL,
									     DATE(VD.lote_pesoinicial_fechahoraregistro) AS FECHA_BALANZA,
									     VD.despacho_id_destinoplanta,
									     VD.despacho_id_modalidadenvio
									FROM despachos_primertramo_validaciondatos VD
											 LEFT JOIN tbconfig_conductores CD ON VD.guias_idchofer = CD.Id
									     LEFT JOIN transporte T ON VD.balanza_placa = T.cplaca
											 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
											 INNER JOIN tb_clientes PM ON VD.lote_id_proveedorminero = PM.Id
								 WHERE MD5(VD.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(VD.guiaremitente_numero) = '".$numero_guia."'
									 AND VD.lote_id_proveedorminero = ".$id_remitente."
									 AND T.id_Transportista = ".$id_transportista."
									 AND VD.guias_fecha = '".$guia_fecha."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$chofer_nombres = $row_datos["CHOFER_NOMBRES"];
					$chofer_dni = $row_datos["CHOFER_DNI"];
					$chofer_licencia = $row_datos["CHOFER_LICENCIA"];
					$chofer_domicilio = $row_datos["CHOFER_DOMICILIO"];
					$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
					$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
					$total_lotes = $row_datos["TOTAL_LOTES"];
					$guiaT_serie = $row_datos["guiatransportista_serie"];
					$guiaT_numero = $row_datos["guiatransportista_numero"];
					$guiaT = $guiaT_serie.'-'.$guiaT_numero;
					$guias_puntopartida = $row_datos["guias_puntopartida"];
					$guias_destinatario = $row_datos["guias_destinatario"];
					$guias_puntodestino = $row_datos["guias_puntodestino"];
					$placas = $row_datos["balanza_placa"].((strlen($row_datos["PLACA2"]) == 0) ? '' : ' / '.$row_datos["PLACA2"]);
					$remitente_ruc = $row_datos["REMITENTE_RUC"];
					$remitente_razonsocial = $row_datos["REMITENTE_RAZONSOCIAL"];
					$fecha_balanza_x = $row_datos["FECHA_BALANZA"];
					$id_destino = $row_datos["despacho_id_destinoplanta"];
					$id_modalidadenvio = $row_datos["despacho_id_modalidadenvio"];

					// Seteando fecha de documento
						$fecha_balanza = explode('-', $fecha_balanza_x)[2].'.'.explode('-', $fecha_balanza_x)[1].'.'.explode('-', $fecha_balanza_x)[0];

					// Obteniendo lista de lotes
						$arr_lotes = '';

						$q_lotes = "SELECT lote_cod_lote,
															 lote_ticket_orden
													FROM despachos_primertramo_validaciondatos
												 WHERE MD5(guiaremitente_serie) = '".$serie_guia."'
													 AND MD5(guiaremitente_numero) = '".$numero_guia."'";

						if ($res_lotes = mysqli_query($enlace, $q_lotes)){
				      if (mysqli_num_rows($res_lotes) > 0) {
				        while($row_lotes = mysqli_fetch_array($res_lotes)){
				        	$arr_lotes .= $row_lotes["lote_cod_lote"].((strlen($row_lotes["lote_ticket_orden"]) == 0) ? '' : '['.$row_lotes["lote_ticket_orden"].']').'-';
				        }
				      }
				    }

				    $arr_lotes = substr($arr_lotes, 0, -1);

				  // Seteando nombre del archivo
						$nom_archivo = 'Declaración Jurada de Traslado de Mineral - '.$fecha_balanza.'-'.$arr_lotes;
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

								<body style="margin-left: 80px; margin-right: 80px;">';

								if ($id_destino == 5 && $id_modalidadenvio == 1){
									$html .= '		<div class="row" style="margin-top: 20px; text-align: center;">
																	<table style="width: 100%;">
																		<tr style="font-family: AgencyFBb; color: #D9D9D9; font-size: 16px;">
																			<td style="text-align: right;">
																				<div style="margin-top: -10px;">
																					SOL-CO-FO-08
																				</div>
																			</td>
																		</tr>

																		<tr style="font-family: AgencyFBb; color: #D9D9D9; font-size: 16px;">
																			<td style="text-align: right;">
																				<div style="margin-top: -10px;">
																					Versión: 01
																				</div>
																			</td>
																		</tr>

																		<tr style="font-family: AgencyFBb; color: #D9D9D9; font-size: 16px;">
																			<td style="text-align: right;">
																				<div style="margin-top: -10px;">
																					Aprobación: 03/08/2022
																				</div>
																			</td>
																		</tr>
																	</table>
																</div>';
								}

		$html .= '		<div class="row" style="margin-top: 60px; text-align: center;">
										<label style="font-family: AgencyFBb; font-size: 20px;">
											<u>DECLARACION JURADA DE TRASLADO DE MINERAL</u>
										</label>
									</div>

									<div class="row" style="margin-top: 40px; text-align: justify; font-size: 16px; font-family: AgencyFB;">
										Yo, <label style="font-family: AgencyFBb;">'.mb_strtoupper($chofer_nombres).'</label>, identificado con DNI N° <label style="font-family: AgencyFBb;">'.$chofer_dni.'</label>, con licencia de conducir N° <label style="font-family: AgencyFBb;">'.((strlen($chofer_licencia) == 0) ? '___'.$chofer_dni : $chofer_licencia).'</label>, con domicilio en, <label style="font-family: AgencyFBb;">'.((strlen($chofer_domicilio) == 0) ? '___________________________________________________________' : mb_strtoupper($chofer_domicilio)).'</label>, en representación de la empresa de transportes <label style="font-family: AgencyFBb;">'.mb_strtoupper($transportista_razonsocial).'</label> declaro bajo juramento haber trasladado <label style="font-family: AgencyFBb;">'.number_format(($total_lotes / 1000), 2, '.', '').'</label> toneladas métricas húmedas de mineral aurífero sin procesar, según la Guía de Transporte N° <label style="font-family: AgencyFBb;">'.$guiaT.'</label>, desde <label style="font-family: AgencyFBb;">'.mb_strtoupper($guias_puntopartida).'</label> hasta la empresa <label style="font-family: AgencyFBb;">'.mb_strtoupper(explode('-', $guias_destinatario)[1]).'</label> ubicada en <label style="font-family: AgencyFBb;">'.mb_strtoupper($guias_puntodestino).'</label>, con el vehículo de placa <label style="font-family: AgencyFBb;">'.mb_strtoupper($placas).'</label>, por encargo del propietario del mineral <label style="font-family: AgencyFBb;">'.mb_strtoupper($remitente_razonsocial).'</label>, identificado con el RUC N° <label style="font-family: AgencyFBb;">'.mb_strtoupper($remitente_ruc).'</label>.
									</div>

									<div class="row" style="margin-top: 20px; text-align: justify; font-size: 16px; font-family: AgencyFB;">
										Formulo la presente declaración para los fines pertinentes respecto al sustento del traslado de mineral, teniendo el presente, carácter de Declaración Jurada, asumiendo en caso de comprobarse falsedad, las consecuencias penales y civiles, liberando a <label style="font-family: AgencyFBb;">'.mb_strtoupper(explode('-', $guias_destinatario)[1]).'</label> de toda responsabilidad administrativa, civil o penal.
									</div>

									<div class="row" style="margin-top: 20px; text-align: justify; font-size: 16px; font-family: AgencyFB;">
										Para mayor constancia y validez en cumplimiento firmo y pongo mi huella digital al pie del presente documento para fines legales correspondientes.
									</div>';

	// 2. Setea la fecha
		$html .= '<div class="row" style="margin-top: 40px; text-align: right; font-size: 16px; font-family: AgencyFB;">
								Trujillo, '.formatearFecha($fecha_balanza_x).'
							</div>';

	// 3. Setea firma y huella
		$html .= '<div class="row" style="margin-top: 60px; text-align: center;">
								<table style="width: 100%;">
									<tr style="font-size: 16px;">
										<td style="vertical-align: bottom; font-family: AgencyFB; text-align: center; width: 50%;">
											----------------------------------------------------
										</td>

										<td style="vertical-align: top; font-family: AgencyFB; text-align: center; width: 15%;">
											<div style="border: solid; border-width: 1px; border-color: #000000; border-radius: 7px; height: 120px; min-width: 50px; margin-bottom: 10px;">
											</div>
										</td>

										<td style="vertical-align: top; font-family: AgencyFB; text-align: center; width: 15%;">

										</td>
									</tr>

									<tr style="font-size: 16px; width: 50%;">
										<td style="vertical-align: center; font-family: AgencyFB; height: 30px; text-align: center; margin-top: -20px;">
											<div style="margin-top: -10px; font-family: AgencyFBb">Firma</div>
										</td>

										<td style="vertical-align: center; font-family: AgencyFB; height: 30px; text-align: center; margin-top: -20px;">
											<div style="margin-top: -10px; font-family: AgencyFBb">Huella Digital</div>
										</td>
									</tr>
								</table>
							</div>

							<div class="row" style="margin-top: 40px; font-family: AgencyFB;">
								Nombre: <label style="font-family: AgencyFBb; font-size: 16px;">'.mb_strtoupper($chofer_nombres).'
							</div>

							<div class="row" style="font-family: AgencyFB;">
								DNI: <label style="font-family: AgencyFBb; font-size: 16px;">'.$chofer_dni.'
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
	$document -> stream($nom_archivo, array('Attachment' => 0));

?>
