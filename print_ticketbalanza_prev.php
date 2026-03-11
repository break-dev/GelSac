<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');

require_once 'dompdf/autoload.inc.php';

require('libs/phpqrcode/qrlib.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$id_md5 = $_GET["x"];

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startuo_errors', 0);

// Ruta logo
$ruta_images = 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_ticketbalanza_prev.php')) . 'images/';

function nombre_numeros($_numero)
{
	// Determinando la cantidad de dígitos
	$digitos = strlen($_numero);

	// Descomponiendo números
	$entero = substr($_numero, 0, strpos($_numero, '.'));
	$decimal = substr($_numero, strpos($_numero, '.') + 1);

	$digitos = strlen($entero);

	// Unidad
	if ($digitos >= 1) {
		$unidad = substr($entero, -1);

		$unidad = (($unidad == 0) ? '' : $unidad);

		if ($unidad == 1) {
			$unidad = 'uno';
		}
		if ($unidad == 2) {
			$unidad = 'dos';
		}
		if ($unidad == 3) {
			$unidad = 'tres';
		}
		if ($unidad == 4) {
			$unidad = 'cuatro';
		}
		if ($unidad == 5) {
			$unidad = 'cinco';
		}
		if ($unidad == 6) {
			$unidad = 'seis';
		}
		if ($unidad == 7) {
			$unidad = 'siete';
		}
		if ($unidad == 8) {
			$unidad = 'ocho';
		}
		if ($unidad == 9) {
			$unidad = 'nueve';
		}
	}

	// Decena entre 11 y 19
	if ($digitos >= 2) {
		$decena = substr($entero, -2);

		if ($decena >= 10 && $decena <= 19) {
			$unidad = '';

			if ($decena == 10) {
				$decena = 'Diez';
			}
			if ($decena == 11) {
				$decena = 'Once';
			}
			if ($decena == 12) {
				$decena = 'Doce';
			}
			if ($decena == 13) {
				$decena = 'Trece';
			}
			if ($decena == 14) {
				$decena = 'Catorce';
			}
			if ($decena == 15) {
				$decena = 'Quince';
			}
			if ($decena == 16) {
				$decena = 'Dieciseis';
			}
			if ($decena == 17) {
				$decena = 'Diecisiete';
			}
			if ($decena == 18) {
				$decena = 'Dieciocho';
			}
			if ($decena == 19) {
				$decena = 'Diecinueve';
			}
		} else { // Decenas
			$decena = substr(substr($entero, -2), 0, 1);

			$decena = (($decena == 0) ? '' : $decena);

			if ($decena == 2) {
				if (strlen($unidad) == 0) {
					$decena = 'Veinte';
				} else {
					$decena = 'Veinti';
				}
			}
			if ($decena == 3) {
				if (strlen($unidad) == 0) {
					$decena = 'Treinta';
				} else {
					$decena = 'Treinta y ';
				}
			}
			if ($decena == 4) {
				if (strlen($unidad) == 0) {
					$decena = 'Cuarenta';
				} else {
					$decena = 'Cuarenta y ';
				}
			}
			if ($decena == 5) {
				if (strlen($unidad) == 0) {
					$decena = 'Cincuenta';
				} else {
					$decena = 'Cincuenta y ';
				}
			}
			if ($decena == 6) {
				if (strlen($unidad) == 0) {
					$decena = 'Sesenta';
				} else {
					$decena = 'Sesenta y ';
				}
			}
			if ($decena == 7) {
				if (strlen($unidad) == 0) {
					$decena = 'Setenta';
				} else {
					$decena = 'Setenta y ';
				}
			}
			if ($decena == 8) {
				if (strlen($unidad) == 0) {
					$decena = 'Ochenta';
				} else {
					$decena = 'Ochenta y ';
				}
			}
			if ($decena == 9) {
				if (strlen($unidad) == 0) {
					$decena = 'Noventa';
				} else {
					$decena = 'Noventa y ';
				}
			}
		}
	}

	// Centenas
	if ($digitos >= 3) {
		$centena = substr(substr($entero, -3), 0, 1);

		$centena = (($centena == 0) ? '' : $centena);

		if ($centena == 1) {
			if ($entero == 100) {
				$centena = 'Cien ';
			} else {
				$centena = 'Ciento ';
			}
		}
		if ($centena == 2) {
			$centena = 'Doscientos ';
		}
		if ($centena == 3) {
			$centena = 'Trescientos ';
		}
		if ($centena == 4) {
			$centena = 'Cuatrocientos ';
		}
		if ($centena == 5) {
			$centena = 'Quinientos ';
		}
		if ($centena == 6) {
			$centena = 'Seiscientos ';
		}
		if ($centena == 7) {
			$centena = 'Setecientos ';
		}
		if ($centena == 8) {
			$centena = 'Ochocientos ';
		}
		if ($centena == 9) {
			$centena = 'Novecientos ';
		}
	}

	// Millares
	if ($digitos >= 5) {
		$millar = substr(substr($entero, -5), 0, 1);

		$millar = (($millar == 0) ? '' : $millar);

		if ($millar == 1) {
			$millar = 'Mil ';
		}
		if ($millar == 2) {
			$millar = 'Dos mil ';
		}
		if ($millar == 3) {
			$millar = 'Tres mil ';
		}
		if ($millar == 4) {
			$millar = 'Cuatro mil ';
		}
		if ($millar == 5) {
			$millar = 'Cinco mil ';
		}
		if ($millar == 6) {
			$millar = 'Seis mil ';
		}
		if ($millar == 7) {
			$millar = 'Siete mil ';
		}
		if ($millar == 8) {
			$millar = 'Ocho mil ';
		}
		if ($millar == 9) {
			$millar = 'Nueve mil ';
		}
	}

	$entero = $millar . $centena . $decena . $unidad . ' y ' . $decimal . '/100 Soles';

	return $entero;
}

// Obteniendo datos de cabecera de la recepción
$fechahora_ingresoplanta = '';
$ticket_balanza = '';
$cod_lote = '';
$placa_1 = '';
$placa_2 = '';
$transportista_documento = '';
$transportista_razonsocial = '';
$tipo_vehiculo = '';
$conductor_documento = '';
$conductor_nombres = '';
$tipo_carga = '';
$zona_origen = '';
$proveedor_minero = '';
$encargado_muestra = '';
$producto = '';
$tipo_mineral = '';
$observacion = '';
$peso_inicial = '';
$pesoinicial_fechahora = '';
$pesoinicial_observacion = '';
$peso_final = '';
$pesofinal_fechahora = '';
$pesofinal_observacion = '';

$m = 1;

$q_balanza = "SELECT I.id_controlIngresoVehiculo,
												 CONCAT(I.dFechaIngreso, ' ', I.dhoraingresoPlanta) AS FECHAHORA_REGISTRO,
												 L.nNro_ticketsBalanza,
												 L.item_ticketbalanza,
												 L.ccod_Lote,
												 I.placa,
												 I.placa2,
												 T.documento AS TRANSPORTISTA_DOCUMENTO,
												 T.razon_social AS TRANSPORTISTA_RAZONSOCIAL,
												 TV.descripcion AS TIPO_VEHICULO,
												 IFNULL(CD.dni_licencia, '---') AS CONDUCTOR_DOCUMENTO,
									       CD.nombres AS CONDUCTOR_NOMBRES,
									       TC.descripcion AS TIPO_CARGA,
												 ZO.descripcion AS ZONA_ORIGEN,
												 L.balanza_id_proveedorminero,
												 UPPER(PM.razon_social) AS PROVEEDOR_MINERO,
												 UPPER(EM.nombres) AS ENCARGADO_MUESTRA,
									       UPPER(P.descripcion) AS PRODUCTO,
									       UPPER(TM.descripcion) AS TIPO_MINERAL,
									       UPPER(L.balanza_observacion) AS OBSERVACION,

											   L.nPeso_InicialBalanza,
											   L.tFechaInicialBalanza,
											   L.tHoraInicialBalanza,
											   L.pesoinicial_observacion,

											   L.nPeso_FinalBalanza,
											   L.dFechaFinalBalanza,
											   L.tHoraFinalBalanza,
											   L.pesofinal_observacion

									  FROM controlingresovehiculo I
												 INNER JOIN tbconfig_tipoingresounidades IU ON I.id_tipoingresounidad = IU.Id
												 INNER JOIN transporte TR ON I.placa = TR.cplaca
									       LEFT JOIN tb_clientes T ON TR.id_Transportista = T.Id
									       LEFT JOIN tbconfig_tipovehiculo TV ON I.id_tipovehiculo = TV.Id
									       LEFT JOIN tbconfig_conductores CD ON I.id_choferes = CD.Id
									       LEFT JOIN catalogolotes L ON I.id_controlIngresoVehiculo = L.id_controlIngresoVehiculo
									       LEFT JOIN tbconfig_tipocarga TC ON L.balanza_id_tipocarga = TC.Id
									       LEFT JOIN tbconfig_zonaorigen ZO ON L.balanza_id_zonaorigen = ZO.Id
												 LEFT JOIN tb_usuario U_I ON L.id_UsuarioCreacion = U_I.Id
										     LEFT JOIN tb_usuario U_F ON L.id_UsuarioModificacion = U_F.Id
									       LEFT JOIN tb_clientes PM ON L.balanza_id_proveedorminero = PM.Id
									       LEFT JOIN tbconfig_encargadosmuestra EM ON L.balanza_id_encargadomuestra = EM.Id
												 LEFT JOIN tbconfig_producto P ON L.balanza_id_producto = P.Id
												 LEFT JOIN tbconfig_tipomineral TM ON L.balanza_id_tipomineral = TM.Id
									 WHERE MD5(L.id_CatalogoLotes) = '" . $id_md5 . "'";

if ($res_balanza = mysqli_query($enlace, $q_balanza)) {
	if (mysqli_num_rows($res_balanza) > 0) {
		while ($row_balanza = mysqli_fetch_array($res_balanza)) {
			$fechahora_ingresoplanta = $row_balanza["FECHAHORA_REGISTRO"];
			$ticket_balanza = $row_balanza["nNro_ticketsBalanza"] . ((strlen($row_balanza["item_ticketbalanza"]) > 0) ? '-' . $row_balanza["item_ticketbalanza"] : '');
			$cod_lote = $row_balanza["ccod_Lote"];
			$placa_1 = $row_balanza["placa"];
			$placa_2 = $row_balanza["placa2"];
			$transportista_documento = $row_balanza["TRANSPORTISTA_DOCUMENTO"];
			$transportista_razonsocial = $row_balanza["TRANSPORTISTA_RAZONSOCIAL"];
			$tipo_vehiculo = $row_balanza["TIPO_VEHICULO"];
			$conductor_documento = $row_balanza["CONDUCTOR_DOCUMENTO"];
			$conductor_nombres = $row_balanza["CONDUCTOR_NOMBRES"];
			$tipo_carga = $row_balanza["TIPO_CARGA"];
			$zona_origen = $row_balanza["ZONA_ORIGEN"];
			$id_proveedorminero = $row_balanza["balanza_id_proveedorminero"];
			$proveedor_minero = $row_balanza["PROVEEDOR_MINERO"];
			$encargado_muestra = $row_balanza["ENCARGADO_MUESTRA"];
			$producto = $row_balanza["PRODUCTO"];
			$tipo_mineral = $row_balanza["TIPO_MINERAL"];
			$observacion = $row_balanza["OBSERVACION"];

			$peso_inicial = $row_balanza["nPeso_InicialBalanza"];
			$pesoinicial_fechahora = $row_balanza["tFechaInicialBalanza"] . ' ' . $row_balanza["tHoraInicialBalanza"];
			$pesoinicial_observacion = $row_balanza["pesoinicial_observacion"];
			$peso_final = $row_balanza["nPeso_FinalBalanza"];
			$pesofinal_fechahora = $row_balanza["dFechaFinalBalanza"] . ' ' . $row_balanza["tHoraFinalBalanza"];
			$pesofinal_observacion = $row_balanza["pesofinal_observacion"];

			// Genera el Código QR
			$url = $url_lims . 'print_ticketbalanza_prev.php?x=' . $id_md5;

			$dir = 'images/qr/';
			$file_name = $dir . 'ticket_qr_' . $id_md5 . '.png';

			if (!file_exists($dir)) {
				mkdir($dir);
			}

			// Genera QR
			QRcode::png($url, $file_name, 'H', 3, 3);
		}
	}
}

// Inicia html
$html = '<!DOCTYPE html>
							<html lang="es">
								<head>
					        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					        <meta charset="utf-8">
					        <meta http-equiv="X-UA-Compatible" content="IE=edge">
					        <meta name="viewport" content="width=device-width, initial-scale=1">
					        <title>Ticket: ' . $ticket_balanza . '</title>
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
											font-family: AgencyFB;
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

						    <body style="width: 100%; padding: 0px; text-align: center;">
						    	<div style="width: 100%; margin-left: 0px; margin-top: ' . (($_SESSION['cod_rol'] != 4) ? '50px' : '0px') . ' margin-right: 0px;">

										<div class="row" style="margin-top: -30px; text-align: center;">
											TICKET DE BALANZA: <label style="font-family: AgencyFBb;">' . $ticket_balanza . '</label>
										</div>

										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -8px; text-align: center; font-family: AgencyFBb; font-size: 20px;">
											LOTE: ' . (($g_anho >= 2024) ? $cod_lote : substr($cod_lote, 4)) . '
										</div>

										<div class="row" style="margin-top: -10px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 7px; text-align: left; margin-bottom: -10px;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%; ">
														<label style="font-family: AgencyFBb;">Placa 1: </label>
														<label>' . $placa_1 . '</label>
													</td>

													<td style="width: 50%; text-align: right;">
														<div style="margin-right: 7px;">
															<label style="font-family: AgencyFBb;">PRIMER TRAMO</label>
														</div>
													</td>
												</tr>
											</table>
										</div>';

if (strlen($placa_2) > 0) {
	$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
												<label style="font-family: AgencyFBb;">Placa 2: </label>
												<label>' . $placa_2 . '</label>
											</div>';
}

$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Emp. de Transporte: </label>
											<label>' . $transportista_documento . '</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>' . $transportista_razonsocial . '</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Vehículo: </label>
											<label>' . $tipo_vehiculo . '</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Conductor: </label>
											<label>' . $conductor_documento . '</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>' . $conductor_nombres . '</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Proveedor Minero: </label>
											<label>' . ((strlen(trim($proveedor_minero)) == 0) ? '---' : $proveedor_minero) . '</label>
										</div>';

if ($id_proveedorminero == 73) {
	$html .= '		<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Encargado Muestra: </label>
											<label>' . ((strlen(trim($encargado_muestra)) == 0) ? '---' : $encargado_muestra) . '</label>
										</div>';
}

$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Observación: </label>
											<label>' . ((strlen(trim($observacion)) == 0) ? '---' : $observacion) . '</label>
										</div>

										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; text-align: center; font-family: AgencyFBb;">
											PESAJE
										</div>

										<div class="row" style="margin-top: -7px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; margin-right: 10px; text-align: left;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO INICIAL: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>' . number_format($peso_inicial, 0, '.', ',') . ' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: ' . substr($pesoinicial_fechahora, 0, 10) . '
														</div>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO FINAL: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>' . number_format($peso_final, 0, '.', ',') . ' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: ' . substr($pesofinal_fechahora, 0, 10) . '
														</div>
													</td>
												</tr>
											</table>
										</div>

										<div class="row" style="text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; text-align: center; font-family: AgencyFBb;">
											RESUMEN
										</div>

										<div class="row" style="margin-top: -7px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; margin-right: 10px; text-align: left;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO BRUTO: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>' . number_format($peso_inicial, 0, '.', ',') . ' Kg</label>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">TARA: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>' . number_format($peso_final, 0, '.', ',') . ' Kg</label>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO NETO: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>' . number_format((abs($peso_inicial - $peso_final)), 0, '.', ',') . ' Kg</label>
													</td>
												</tr>
											</table>
										</div>';

$html .= '			
								</div>
							</body>
					  </html>';
// echo '$html: '.$html;
// return;
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$document = new Dompdf($options);

$document->loadHtml($html, 'UTF-8');

// Solo si es balanza aumenta el Height
if ($_SESSION['cod_rol'] == 4) {
	$document->setPaper(array(0, 0, 190, 6000));
} else {
	$document->setPaper(array(0, 0, 190, 700));
}

$document->render();
$document->stream("Recibo - Ticket " . $ticket_balanza, array('Attachment' => 0));
