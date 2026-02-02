<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	require('libs/phpqrcode/qrlib.php');

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_md5 = $_GET["x"];

	// Ruta logo
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_ticketbalanza.php')).'images/';

	function nombre_numeros($_numero){
		// Determinando la cantidad de dígitos
			$digitos = strlen($_numero);

		// Descomponiendo números
			$entero = substr($_numero, 0, strpos($_numero, '.'));
			$decimal = substr($_numero, strpos($_numero, '.') + 1);

			$digitos = strlen($entero);

			// Unidad
				if ($digitos >= 1){
					$unidad = substr($entero, -1);

					$unidad = (($unidad == 0) ? '' : $unidad);

					if ($unidad == 1){
						$unidad = 'uno';
					}
					if ($unidad == 2){
						$unidad = 'dos';
					}
					if ($unidad == 3){
						$unidad = 'tres';
					}
					if ($unidad == 4){
						$unidad = 'cuatro';
					}
					if ($unidad == 5){
						$unidad = 'cinco';
					}
					if ($unidad == 6){
						$unidad = 'seis';
					}
					if ($unidad == 7){
						$unidad = 'siete';
					}
					if ($unidad == 8){
						$unidad = 'ocho';
					}
					if ($unidad == 9){
						$unidad = 'nueve';
					}
				}

			// Decena entre 11 y 19
				if ($digitos >= 2){
					$decena = substr($entero, -2);

					if ($decena >= 10 && $decena <= 19){
						$unidad = '';

						if ($decena == 10){
							$decena = 'Diez';
						}
						if ($decena == 11){
							$decena = 'Once';
						}
						if ($decena == 12){
							$decena = 'Doce';
						}
						if ($decena == 13){
							$decena = 'Trece';
						}
						if ($decena == 14){
							$decena = 'Catorce';
						}
						if ($decena == 15){
							$decena = 'Quince';
						}
						if ($decena == 16){
							$decena = 'Dieciseis';
						}
						if ($decena == 17){
							$decena = 'Diecisiete';
						}
						if ($decena == 18){
							$decena = 'Dieciocho';
						}
						if ($decena == 19){
							$decena = 'Diecinueve';
						}
					}
					else{ // Decenas
						$decena = substr(substr($entero, -2), 0, 1);

						$decena = (($decena == 0) ? '' : $decena);

						if ($decena == 2){
							if (strlen($unidad) == 0){
								$decena = 'Veinte';
							}
							else{
								$decena = 'Veinti';
							}
						}
						if ($decena == 3){
							if (strlen($unidad) == 0){
								$decena = 'Treinta';
							}
							else{
								$decena = 'Treinta y ';
							}
						}
						if ($decena == 4){
							if (strlen($unidad) == 0){
								$decena = 'Cuarenta';
							}
							else{
								$decena = 'Cuarenta y ';
							}
						}
						if ($decena == 5){
							if (strlen($unidad) == 0){
								$decena = 'Cincuenta';
							}
							else{
								$decena = 'Cincuenta y ';
							}
						}
						if ($decena == 6){
							if (strlen($unidad) == 0){
								$decena = 'Sesenta';
							}
							else{
								$decena = 'Sesenta y ';
							}
						}
						if ($decena == 7){
							if (strlen($unidad) == 0){
								$decena = 'Setenta';
							}
							else{
								$decena = 'Setenta y ';
							}
						}
						if ($decena == 8){
							if (strlen($unidad) == 0){
								$decena = 'Ochenta';
							}
							else{
								$decena = 'Ochenta y ';
							}
						}
						if ($decena == 9){
							if (strlen($unidad) == 0){
								$decena = 'Noventa';
							}
							else{
								$decena = 'Noventa y ';
							}
						}
					}
				}

			// Centenas
				if ($digitos >= 3){
					$centena = substr(substr($entero, -3), 0, 1);

					$centena = (($centena == 0) ? '' : $centena);

					if ($centena == 1){
						if ($entero == 100){
							$centena = 'Cien ';
						}
						else{
							$centena = 'Ciento ';
						}
					}
					if ($centena == 2){
						$centena = 'Doscientos ';
					}
					if ($centena == 3){
						$centena = 'Trescientos ';
					}
					if ($centena == 4){
						$centena = 'Cuatrocientos ';
					}
					if ($centena == 5){
						$centena = 'Quinientos ';
					}
					if ($centena == 6){
						$centena = 'Seiscientos ';
					}
					if ($centena == 7){
						$centena = 'Setecientos ';
					}
					if ($centena == 8){
						$centena = 'Ochocientos ';
					}
					if ($centena == 9){
						$centena = 'Novecientos ';
					}
				}

			// Millares
				if ($digitos >= 5){
					$millar = substr(substr($entero, -5), 0, 1);

					$millar = (($millar == 0) ? '' : $millar);

					if ($millar == 1){
						$millar = 'Mil ';
					}
					if ($millar == 2){
						$millar = 'Dos mil ';
					}
					if ($millar == 3){
						$millar = 'Tres mil ';
					}
					if ($millar == 4){
						$millar = 'Cuatro mil ';
					}
					if ($millar == 5){
						$millar = 'Cinco mil ';
					}
					if ($millar == 6){
						$millar = 'Seis mil ';
					}
					if ($millar == 7){
						$millar = 'Siete mil ';
					}
					if ($millar == 8){
						$millar = 'Ocho mil ';
					}
					if ($millar == 9){
						$millar = 'Nueve mil ';
					}
				}

		$entero = $millar.$centena.$decena.$unidad.' y '.$decimal.'/100 Soles';

		return $entero;
	}

	// Obteniendo datos de cabecera de la recepción
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
		$id_proveedor_minero = '';
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
		$guia_remitente = '';
		$guia_transportista = '';

		$m = 1;

		$q_balanza = "SELECT V.Id,
												 MD5(V.Id) AS ID_MD5,
												 V.lote_id_lote,
												 V.lote_cod_lote,
												 V.lote_num_ticket,
												 V.lote_ticket_orden,
												 /*V.guias_ticketbalanza,*/
												 CRL.num_ticketbalanza AS guias_ticketbalanza,
												 DATE(CRL.fecha_ingresobalanza) AS FECHA_TICKET,
												 V.balanza_placa,
												 CL_T.documento AS TRANSPORTISTA_RUC,
												 UPPER(CL_T.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,

												 TV.descripcion AS TIPO_VEHICULO,

												 CD.dni_licencia AS CONDUCTOR_DNI,
												 CD.nombres AS CONDUCTOR_NOMBRES,

												 V.lote_id_tipocarga,
												 TC.descripcion AS TIPO_CARGA,

												 V.lote_id_zonaorigen,
												 ZO.descripcion AS ZONA_ORIGEN,

												 V.lote_id_proveedorminero,
												 CL.documento AS PROVEEDORMINERO_RUC,
												 UPPER(CL.razon_social) AS PROVEEDORMINERO_RAZONSOCIAL,
												 CL.proveedorminero_concesion,
												 CL.proveedorminero_codigounico,
												 CL.proveedorminero_ubicacion,
												 CL.proveedorminero_ubicacion_departamento,
												 CL.proveedorminero_ubicacion_provincia,
												 /*CL.proveedorminero_ubicacion_distrito,*/

												 CASE WHEN V.lote_id_zonaorigen = 25
												   THEN ZO.descripcion
												 ELSE CCS.procedencia_distrito END AS procedencia_distrito,

												 V.lote_id_encargadomuestra,
												 UPPER(EM.nombres) AS ENCARGADO_MUESTRA,

												 V.lote_id_producto,
												 UPPER(P.descripcion) AS PRODUCTO,

												 V.lote_id_tipomineral,
												 TM.descripcion AS TIPO_MATERIAL,

												 V.despacho_observacion,

												 
												 V.lote_pesoinicial_fechahoraregistro,
												 V.lote_pesofinal_fechahoraregistro,
												 V.lote_peso_inicial AS lote_peso_bruto,
												 V.lote_peso_final AS lote_peso_tara,
												 V.lote_peso_neto,
												 V.operaciones_humedad,
												 V.lote_peso_seco,
												 V.unidad_capacidad,
												 V.unidad_tara,
												 V.unidad_idmarca,
												 V.despacho_color,

												 V.is_cerrado,
												 V.cerrado_fechahoraregistro,
												 V.cerrado_usuarioregistro,

												 V.guiaremitente_serie,
												 V.guiaremitente_numero,
												 V.guiatransportista_serie,
												 V.guiatransportista_numero

									  FROM despachos_primertramo_validaciondatos V
									  		 LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
									  		 INNER JOIN tb_clientes CL_T ON T.id_Transportista = CL_T.Id
									  		 INNER JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
									  		 INNER JOIN tbconfig_conductores CD ON V.guias_idchofer = CD.Id
									  		 LEFT JOIN tbconfig_zonaorigen ZO ON V.lote_id_zonaorigen = ZO.Id
									  		 LEFT JOIN tb_clientes CL ON V.lote_id_proveedorminero = CL.Id
									  		 LEFT JOIN tbconfig_proveedoresmineros_concesion CCS ON V.lote_id_proveedorminero_concesion = CCS.Id
									  		 LEFT JOIN tbconfig_encargadosmuestra EM ON V.lote_id_encargadomuestra = EM.Id
									  		 LEFT JOIN tbconfig_tipomineral TM ON V.lote_id_tipomineral = TM.Id
												 LEFT JOIN tbconfig_tipocarga TC ON V.lote_id_tipocarga = TC.Id
										 		 /*INNER JOIN guia_remision_detalle GRD ON V.Id = GRD.id_despachos_primertramo_validaciondatos
										 		 INNER JOIN guia_remision GR ON GRD.id_Guia_remision = GR.id_Guia_remision*/
										 		 LEFT JOIN tbconfig_producto P ON V.lote_id_producto = P.Id
									 			 LEFT JOIN consolidado_lotes_cierrecontable CRL ON V.Id = CRL.id_registro
									 			   AND CRL.id_tipoingreso = 1
									 WHERE MD5(V.Id) = '".$id_md5."'";

		if ($res_balanza = mysqli_query($enlace, $q_balanza)){
      if (mysqli_num_rows($res_balanza) > 0) {
        while($row_balanza = mysqli_fetch_array($res_balanza)){
        	if ($row_balanza["lote_cod_lote"] == 'AUM-2370'){
        		$ticket_balanza = $row_balanza["lote_num_ticket"].((strlen($row_balanza["lote_ticket_orden"]) > 0) ? '-'.$row_balanza["lote_ticket_orden"] : '');
        	}
        	else{
        		$ticket_balanza = $row_balanza["guias_ticketbalanza"];
        	}

					$lote_ticket_orden = $row_balanza["lote_ticket_orden"];
					$cod_lote = $row_balanza["lote_cod_lote"];
					$placa_1 = $row_balanza["balanza_placa"];
					// $placa_2 = $row_balanza["placa2"];
					$transportista_documento = $row_balanza["TRANSPORTISTA_RUC"];
					$transportista_razonsocial = $row_balanza["TRANSPORTISTA_RAZONSOCIAL"];
					$tipo_vehiculo = $row_balanza["TIPO_VEHICULO"];
					$conductor_documento = $row_balanza["CONDUCTOR_DNI"];
					$conductor_nombres = $row_balanza["CONDUCTOR_NOMBRES"];
					$tipo_carga = $row_balanza["TIPO_CARGA"];
					// $zona_origen = $row_balanza["proveedorminero_ubicacion_distrito"];
					$zona_origen = $row_balanza["procedencia_distrito"];
					$id_proveedor_minero = $row_balanza["lote_id_proveedorminero"];
					$proveedor_minero = $row_balanza["PROVEEDORMINERO_RAZONSOCIAL"];
					$encargado_muestra = $row_balanza["ENCARGADO_MUESTRA"];
					$producto = $row_balanza["PRODUCTO"];
					$tipo_mineral = $row_balanza["TIPO_MATERIAL"];
					$observacion = $row_balanza["despacho_observacion"];
					$guia_remitente = $row_balanza["guiaremitente_serie"].'-'.$row_balanza["guiaremitente_numero"];
					$guia_transportista = $row_balanza["guiatransportista_serie"].'-'.$row_balanza["guiatransportista_numero"];

					$peso_inicial = $row_balanza["lote_peso_bruto"];
					$pesoinicial_fechahora = $row_balanza["lote_pesoinicial_fechahoraregistro"];
					// $pesoinicial_observacion = $row_balanza["pesoinicial_observacion"];
					$peso_final = $row_balanza["lote_peso_tara"];
					$pesofinal_fechahora = $row_balanza["lote_pesofinal_fechahoraregistro"];
					// $pesofinal_observacion = $row_balanza["pesofinal_observacion"];

					// Genera el Código QR
						$url = $url_lims.'print_ticketbalanza.php?x='.$id_md5;

		        $dir = 'images/qr/';
		        $file_name = $dir.'ticket_qr_'.$id_md5.'.png';

		        if (!file_exists($dir)){
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
					        <title>Ticket: '.$ticket_balanza.'</title>
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
						    	<div style="width: 100%; margin-left: 0px; margin-top: '.(($_SESSION['cod_rol'] != 4) ? '50px' : '0px').' margin-right: 0px;">
										<div class="row" style="text-align: center; margin-bottom: 8px !important;">
											<img src="'.$ruta_images.'empresa/logo.png" width="100mm"/>
										</div>
 
										<div class="row" style="text-align: center;">
											<label>RUC: 20612353183</label>
										</div>

										<div class="row" style="margin-top: 5px; text-align: center; font-size: 20px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #BFBFBF;">
											TICKET DE BALANZA
										</div>

										<div class="row" style="margin-top: -5px; text-align: center;">
											<label style="font-family: AgencyFBb;">'.$ticket_balanza.'</label>
										</div>

										<div class="row" style="text-align: center; margin-top: -10px;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 7px; text-align: left; margin-bottom: -10px;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%; ">
														<label style="font-family: AgencyFBb;">Lote: </label>
														<label>'.$cod_lote.'</label>
													</td>

													<td style="width: 50%; text-align: right;">
														<div style="margin-right: 7px;">
															<label style="font-family: AgencyFBb;">T-1</label>
														</div>
													</td>
												</tr>
											</table>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 7px; text-align: left; margin-bottom: -10px;">
											<table style="width: 100%;">
												<tr>
													<td style="width: 50%; ">
														<label style="font-family: AgencyFBb;">Placa 1: </label>
														<label>'.$placa_1.'</label>
													</td>
												</tr>
											</table>
										</div>';

		if (strlen($placa_2) > 0){
			$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
												<label style="font-family: AgencyFBb;">Placa 2: </label>
												<label>'.$placa_2.'</label>
											</div>';
		}

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Emp. de Transporte: </label>
											<label>'.$transportista_documento.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.$transportista_razonsocial.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Vehículo: </label>
											<label>'.$tipo_vehiculo.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Conductor: </label>
											<label>'.$conductor_documento.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label>'.$conductor_nombres.'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Zona Origen: </label>
											<label>'.((strlen(trim($zona_origen)) == 0) ? '---' : $zona_origen).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Proveedor Minero: </label>
											<label>'.((strlen(trim($proveedor_minero)) == 0) ? '---' : $proveedor_minero).'</label>
										</div>';

		if ($id_proveedor_minero == 73){ // Para Llacuabamba
			$html .= '		<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Encargado Muestra: </label>
											<label>'.((strlen(trim($encargado_muestra)) == 0) ? '---' : $encargado_muestra).'</label>
										</div>';
		}

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Guía Remitente: </label>
											<label>'.((strlen(trim($guia_remitente)) == 0) ? '---' : $guia_remitente).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Guía Transportista: </label>
											<label>'.((strlen(trim($guia_transportista)) == 0) ? '---' : $guia_transportista).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Producto: </label>
											<label>'.((strlen(trim($producto)) == 0) ? '---' : $producto).'</label>
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Tipo Carga: </label>
											<label>'.$tipo_carga.'</label>
										</div>';

		if (strlen($observacion) == 0 && strlen($lote_ticket_orden) > 0){
			$observacion = 'PARTE '.$lote_ticket_orden;
		}

		$html .= '			<div class="row" style="margin-top: -5px; margin-left: 10px; text-align: left;">
											<label style="font-family: AgencyFBb;">Observación: </label>
											<label>'.((strlen(trim($observacion)) == 0) ? '---' : $observacion).'</label>
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
														<label>'.number_format($peso_inicial, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: '.substr($pesoinicial_fechahora, 0, 10).'
														</div>
													</td>
												</tr>

												<tr>
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO FINAL: </label>
													</td>

													<td style="width: 50%; text-align: right;">
														<label>'.number_format($peso_final, 0, '.', ',').' Kg</label>
													</td>
												</tr>

												<tr>
													<td colspan="2">
														<div style="margin-top: -10px; font-size: 13px;">
															Fecha: '.substr($pesofinal_fechahora, 0, 10).'
														</div>
													</td>
												</tr>
											</table>
										</div>

										<div class="row" style="margin-top: -5px; text-align: center;">
											---------------------------------------------------------
										</div>

										<div class="row" style="margin-top: -5px; margin-left: 10px; margin-right: 10px; text-align: left;">
											<table style="width: 100%;">
												<tr style="font-size: 16px;">
													<td style="width: 50%;">
														<label style="font-family: AgencyFBb;">PESO NETO: </label>
													</td>

													<td style="width: 50%; text-align: right; font-family: AgencyFBb;">
														<label>'.number_format((abs($peso_inicial - $peso_final)), 0, '.', ',').' Kg</label>
													</td>
												</tr>
											</table>
										</div>

										';

	$html .= '			
								</div>
							</body>
					  </html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');

	// Solo si es balanza aumenta el Height
		if ($_SESSION['cod_rol'] == 4){
			$document -> setPaper(array(0, 0, 190, 6000));
		}
		else{
			$document -> setPaper(array(0, 0, 190, 700));
		}

	$document -> render();
	$document -> stream("Recibo - Ticket ".$ticket_balanza, array('Attachment' => 0));

?>
