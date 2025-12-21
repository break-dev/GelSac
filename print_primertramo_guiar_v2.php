<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$serie_guia = $_GET["a"];
	$numero_guia = $_GET["b"];
	$id_remitente = $_GET["c"];
	$id_transportista = $_GET["d"];
	$guia_fecha = $_GET["e"];

	// 1. Obteniendo datos de la guía
		$nom_archivo = 'Remitente';
		$tipo_guia = mb_strtoupper($nom_archivo);

		$q_datos = "SELECT DISTINCT
											 V.guias_fecha,
                       V.guias_fechahoraemision,
											 V.guiaremitente_serie,
											 V.guiaremitente_numero,
											 V.guiatransportista_serie,
											 V.guiatransportista_numero,
											 V.guias_puntopartida,
											 V.guias_puntodestino,
											 V.balanza_placa,
											 V.balanza_placa2,
											 V.unidad_idmarca,
											 V.unidad_idmarca2,
											 UPPER(U.descripcion) AS MARCA,
											 UPPER(U2.descripcion) AS MARCA2,
											 V.unidad_constanciamtc,
											 V.unidad_constanciamtc2,
											 V.guias_idchofer,
											 C.dni_licencia,
											 C.licencia_conducir,
											 UPPER(C.nombres) AS CONDUCTOR,
											 V.guias_destinatario,
                       UPPER(PM.documento) AS PROVEEDORMINERO_RUC,
                       UPPER(PM.razon_social) AS PROVEEDORMINERO_RAZONSOCIAL,
                       UPPER(PM.direccion) AS PROVEEDORMINERO_DIRECCION,
											 ET.documento AS TRANSPORTISTA_RUC,
											 UPPER(ET.razon_social) AS TRANSPORTISTA_RAZONSOCIAL,
											 /*
											 UPPER(PM.proveedorminero_concesion) AS CONCESION,
											 UPPER(PM.proveedorminero_codigounico) AS CODIGO_UNICO,
											 UPPER(PM.proveedorminero_codigounico) AS CODIGO_UNICO,
											 */
											 UPPER(CS.descripcion) AS CONCESION,
											 UPPER(CS.codigo_unico) AS CODIGO_UNICO,
											 UPPER(V.guias_motivotraslado) AS MOTIVO_TRASLADO,
											 UPPER(CS.procedencia_distrito) AS CONCESION_DISTRITO,
											 UPPER(CS.procedencia_provincia) AS CONCESION_PROVINCIA,
											 UPPER(CS.procedencia_departamento) AS CONCESION_DEPARTAMENTO
								  FROM despachos_primertramo_validaciondatos V
								  		 LEFT JOIN tbconfig_unidadesmarca U ON V.unidad_idmarca = U.Id
								  		 LEFT JOIN tbconfig_unidadesmarca U2 ON V.unidad_idmarca2 = U2.Id
								  		 LEFT JOIN tbconfig_conductores C ON V.guias_idchofer = C.Id
								  		 LEFT JOIN transporte T ON V.balanza_placa = T.cplaca
								  		 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
								  		 INNER JOIN tb_clientes PM ON V.lote_id_proveedorminero = PM.Id
								  		 INNER JOIN tbconfig_proveedoresmineros_concesion CS ON V.lote_id_proveedorminero_concesion = CS.Id
								 WHERE MD5(V.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(V.guiaremitente_numero) = '".$numero_guia."'
									 AND V.lote_id_proveedorminero = ".$id_remitente."
									 AND T.id_Transportista = ".$id_transportista."
									 AND V.guias_fecha = '".$guia_fecha."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$guiaR_serie = $row_datos["guiaremitente_serie"];
					$guiaR_numero = $row_datos["guiaremitente_numero"];
					$guiaR = $guiaR_serie.'-'.$guiaR_numero;

					$guiaT_serie = $row_datos["guiatransportista_serie"];
					$guiaT_numero = $row_datos["guiatransportista_numero"];
					$guiaT = $guiaT_serie.'-'.$guiaT_numero;

					$nom_archivo_guia = $guiaR;

					$iniciotraslado_dia = explode('-', $row_datos["guias_fecha"])[2];
					$iniciotraslado_mes = explode('-', $row_datos["guias_fecha"])[1];
					$iniciotraslado_anho = explode('-', $row_datos["guias_fecha"])[0];

					$emision_dia = explode('-', explode(' ', $row_datos["guias_fechahoraemision"])[0])[2];
					$emision_mes = explode('-', explode(' ', $row_datos["guias_fechahoraemision"])[0])[1];
					$emision_anho = explode('-', explode(' ', $row_datos["guias_fechahoraemision"])[0])[0];

					$guias_puntopartida = $row_datos["guias_puntopartida"];
					$guias_puntodestino = $row_datos["guias_puntodestino"];
					$placa_1 = $row_datos["balanza_placa"];
					$marca_1 = $row_datos["MARCA"];
					$constancia_mtc_1 = mb_strtoupper($row_datos["unidad_constanciamtc"]);
					$placa_2 = $row_datos["balanza_placa2"];
					$marca_2 = $row_datos["MARCA2"];
					$constancia_mtc_2 = mb_strtoupper($row_datos["unidad_constanciamtc2"]);
					$conductor_dni = $row_datos["dni_licencia"];
					$licencia_conducir = $row_datos["licencia_conducir"];
					$conductor_nombres = $row_datos["CONDUCTOR"];
					$destinatario = $row_datos["guias_destinatario"];
					$proveedor_ruc = $row_datos["PROVEEDORMINERO_RUC"];
          $proveedor_razonsocial = $row_datos["PROVEEDORMINERO_RAZONSOCIAL"];
          $proveedor_direccion = $row_datos["PROVEEDORMINERO_DIRECCION"];
					$transportista_ruc = $row_datos["TRANSPORTISTA_RUC"];
					$transportista_razonsocial = $row_datos["TRANSPORTISTA_RAZONSOCIAL"];
					$concesion = $row_datos["CONCESION"];
					$codigo_unico = $row_datos["CODIGO_UNICO"];
					$motivo_traslado = $row_datos["MOTIVO_TRASLADO"];
					$concesion_distrito = $row_datos["CONCESION_DISTRITO"];
					$concesion_provincia = $row_datos["CONCESION_PROVINCIA"];
					$concesion_departamento = $row_datos["CONCESION_DEPARTAMENTO"];
        }
      }
    }

	// 1. Arma la estructura de Cabeceera
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia.'</title>

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
											margin-bottom: -15px;
											font-size: 14px;
											padding-left: 80px;
											padding-right: 80px;
										}

										@page{
											margin: 0;
											pading: 0;
										}
									</style>
								</head>

								<body style="margin-left: 10px; margin-right: 10px;">
									<div class="row">
										<table style="width: 100%; margin-top: 40px;">
											<tr style="font-size: 14px;">
												<td style="width: 70%; vertical-align: top; font-size: 25px;">
													<label style="font-size: 35px; font-family: AgencyFBb;">
														'.$proveedor_razonsocial.'
													</label>
												</td>

												<td rowspan="3" style="text-align: center; vertical-align: middle; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 40%; padding: 0px;">
													<div style="font-size: 30px; font-family: AgencyFBb;">
														R.U.C. '.$proveedor_ruc.'
													</div>

													<div style="background-color: #0099DD; color: #ffffff; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-bottom: 5px;">
														<label style="font-size: 30px; font-family: AgencyFBb;">
															GUIA DE REMISIÓN
														</label>

														<br>

														<div style="font-size: 30px; font-family: AgencyFBb; margin-top: -10px;">
															'.$tipo_guia.'
														</div>
													</div>

													<div style="margin-top: -5px;">
														<label style="font-size: 30px; font-family: AgencyFBb;">
															'.$nom_archivo_guia.'
														</label>
													</div>
												</td>
											</tr>

											<tr style="font-size: 14px;">
												<td style="width: 70%; vertical-align: middle; font-size: 15px; background-color: #C9E2F2; height: 25px;">
													<label style="font-size: 17px; font-family: AgencyFBb; padding: 2px;">
														'.$proveedor_direccion.'
													</label>
												</td>
											</tr>

											<tr style="font-size: 20px; padding: 0px;">
												<td style="text-align: center; vertical-align: top; width: 60%; height: 40px;">
													<table style="width: 100%; border-spacing: 0px;">
														<tr style="font-size: 18px; padding: 0px; font-family: AgencyFBb;">
															<td colspan="3" style="text-align: center; vertical-align: top; width: 45%;">
																<div style="text-align: center; vertical-align: middle; padding: 0px; background-color: #0099DD; color: #ffffff; border-top-left-radius: 7px; border-top-right-radius: 7px;">
																	FECHA DE EMISIÓN
																</div>
															</td>

															<td style="text-align: center; vertical-align: top; width: 10%;">
															</td>

															<td colspan="3" style="text-align: center; vertical-align: bottom; width: 45%;">
																<div style="text-align: center; vertical-align: middle; padding: 0px; background-color: #0099DD; color: #ffffff; border-top-left-radius: 7px; border-top-right-radius: 7px;">
																	FECHA DE INICIO DE TRASLADO
																</div>
															</td>
														</tr>

														<tr style="font-size: 20px; padding: 0px; font-family: AgencyFBb;">
															<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; border-bottom-left-radius: 7px;">
																'.$emision_dia.'
															</td>

															<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
																'.$emision_mes.'
															</td>

															<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; border-bottom-right-radius: 7px;">
																'.$emision_anho.'
															</td>

															<td style="text-align: center; vertical-align: top; width: 10%;">
															</td>

															<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; border-bottom-left-radius: 7px;">
																'.$iniciotraslado_dia.'
															</td>

															<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
																'.$iniciotraslado_mes.'
															</td>

															<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; border-bottom-right-radius: 7px;">
																'.$iniciotraslado_anho.'
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>

									<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 20px;">
												<td style="text-align: center; vertical-align: top; width: 50%;">
													<table style="width: 100%;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 20px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #0099DD; color: #ffffff; padding: 5px; text-align: center; font-size: 18px;">
																				DIRECCIÓN DE PUNTO DE PARTIDA
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 20px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; height: 45px; vertical-align: middle;">
																			<div style="padding-left: 10px;">
																				<label style="font-family: AgencyFB;">
																					Dirección:
																				</label>

																				<label style="font-family: AgencyFBb;">'
																					.$concesion.'
																				</label>
																			</div>

																			</br>

																			<div style="padding-left: 10px;">
																				<label style="font-family: AgencyFB;">
																					Distrito:
																				</label>

																				<label style="font-family: AgencyFBb;">'
																					.$concesion_distrito.'
																				</label>

																				<label style="margin-left: 10px; font-family: AgencyFB;">
																					Provincia:
																				</label>

																				<label style="font-family: AgencyFBb;">'
																					.$concesion_provincia.'
																				</label>

																				<label style="margin-left: 10px; font-family: AgencyFB;">
																					Departamento:
																				</label>

																				<label style="font-family: AgencyFBb;">'
																					.$concesion_departamento.'
																				</label>
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>

												<td style="text-align: center; vertical-align: top; width: 50%;">
													<table style="width: 100%;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 20px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #0099DD; color: #ffffff; padding: 5px; text-align: center; font-size: 18px;">
																				DIRECCIÓN DE PUNTO DE LLEGADA
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 20px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; vertical-align: middle;">
																			<div style="padding-left: 10px;">
																				<label style="font-family: AgencyFB;">
																					Dirección:
																				</label>

																				<label style="font-family: AgencyFBb;">
																					OTR.VALLE MOCHE LOTE. VD SEC. VALDIVIA ALTA (190-III)
																				</label>
																			</div>

																			</br>

																			<div style="padding-left: 10px;">
																				<label style="font-family: AgencyFB;">
																					Distrito:
																				</label>

																				<label style="font-family: AgencyFBb;">
																					HUANCHACO
																				</label>

																				<label style="margin-left: 10px; font-family: AgencyFB;">
																					Provincia:
																				</label>

																				<label style="font-family: AgencyFBb;">
																					TRUJILLO
																				</label>

																				<label style="margin-left: 10px; font-family: AgencyFB;">
																					Departamento:
																				</label>

																				<label style="font-family: AgencyFBb;">
																					LA LIBERTAD
																				</label>
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>

									<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 20px;">
												<td style="text-align: center; vertical-align: top; width: 50%;">
													<table style="width: 100%;">
														<tr>
															<td colspan="2" style="text-align: center; vertical-align: middle; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 20px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #0099DD; color: #ffffff; padding: 5px; text-align: center; font-size: 18px;">
																				UNIDAD DE TRANSPORTE Y CONDUCTOR(ES)
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 20px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; vertical-align: middle; width: 100%; height: 190px;">
																<div style="padding-left: 10px; width: 100%;">
																	<label style="font-family: AgencyFB;">
																		Número de Placa:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.$placa_1.((strlen($placa_2) == 0) ? '' : ' / '.$placa_2).'
																	</label>

																	<label style="margin-left: 10px; font-family: AgencyFB;">
																		Marca:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.$marca_1.((strlen($marca_2) == 0) ? '' : ' / '.$marca_2).'
																	</label>
																</div>

																</br>

																<div style="padding-left: 10px;">
																	<label style="font-family: AgencyFB;">
																		N° de Constancia de Inscripción:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.$constancia_mtc_1.((strlen($constancia_mtc_2) == 0) ? '' : ' / '.$constancia_mtc_2).'
																	</label>
																</div>

																</br>

																<div style="padding-left: 10px;">
																	<label style="font-family: AgencyFB;">
																		N° de Licencia de Conducir:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.$licencia_conducir.'
																	</label>
																</div>

																</br>

																<div style="padding-left: 10px;">
																	<label style="font-family: AgencyFB;">
																		Nombre del Chofer:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.$conductor_nombres.'
																	</label>
																</div>
															</td>
														</tr>
													</table>
												</td>

												<td style="text-align: center; vertical-align: top; width: 50%; margin-top: -10px;">
													<table style="width: 100%;">
														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 20px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #0099DD; color: #ffffff; padding: 5px; text-align: center; font-size: 18px;">
																				DESTINATARIO
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 20px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; vertical-align: middle; width: 100%;">
																<div style="padding-left: 10px; width: 100%;">
																	<label style="font-family: AgencyFB;">
																		Nombre o Razón Social:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.explode('-', $destinatario)[1].'
																	</label>
																</div>

																</br>

																<div style="padding-left: 10px;">
																	<label style="font-family: AgencyFB;">
																		N° de R.U.C.:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.explode('-', $destinatario)[0].'
																	</label>
																</div>
															</td>
														</tr>

														<tr>
															<td style="text-align: center; vertical-align: middle; width: 50%; padding: 0px;">
																<table style="width: 100%; border-spacing: 0px;">
																	<tr>
																		<td style="font-family: AgencyFBb; font-size: 20px; padding: 0px;">
																			<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #0099DD; color: #ffffff; padding: 5px; text-align: center; font-size: 18px;">
																				EMPRESA DE TRANSPORTES
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td style="vertical-align: middle; font-family: AgencyFBb; font-size: 20px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; vertical-align: middle; width: 100%;">
																<div style="padding-left: 10px; width: 100%;">
																	<label style="font-family: AgencyFB;">
																		Denominación o Razón Social:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.$transportista_ruc.'
																	</label>
																</div>

																</br>

																<div style="padding-left: 10px;">
																	<label style="font-family: AgencyFB;">
																		N° de R.U.C.:
																	</label>

																	<label style="font-family: AgencyFBb;">
																		'.$transportista_razonsocial.'
																	</label>
																</div>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="margin-left: 5px; margin-right: 5px;">
										<table style="width: 100%; border-spacing: 0px; background-color: #ffffff; border-color: #ffffff;">
											<thead>
												<tr style="font-size: 18px; font-family: AgencyFBb;">
													<td style="text-align: center; border: solid; border-width: 1px; background-color: #0099DD; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														N°
													</td>

													<td style="text-align: center; border: solid; border-width: 1px; background-color: #0099DD; border-color: #ffffff; color: #ffffff; vertical-align: middle; color: #ffffff;">
														DESCRIPCIÓN
													</td>

													<td style="text-align: center; border: solid; border-width: 1px; background-color: #0099DD; border-color: #ffffff; color: #ffffff; vertical-align: middle; color: #ffffff;">
														CANT.
													</td>

													<td style="text-align: center; border: solid; border-width: 1px; background-color: #0099DD; border-color: #ffffff; color: #ffffff; vertical-align: middle; color: #ffffff;">
														UNID. MEDIDA
													</td>

													<td style="text-align: center; border: solid; border-width: 1px; background-color: #0099DD; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														PESO<br>TOTAL
													</td>
												</tr>
											</thead>

											<tbody>';

	// 2. Arma la estructura de Detalle
		$d = 1;

		$q_datos = "SELECT VD.lote_cod_lote,
											 VD.guias_pesonetoajustado
								  FROM despachos_primertramo_validaciondatos VD
											 LEFT JOIN transporte T ON VD.balanza_placa = T.cplaca
								  		 INNER JOIN tb_clientes ET ON T.id_Transportista = ET.Id
								 WHERE MD5(VD.guiaremitente_serie) = '".$serie_guia."'
									 AND MD5(VD.guiaremitente_numero) = '".$numero_guia."'
									 AND VD.lote_id_proveedorminero = ".$id_remitente."
									 AND T.id_Transportista = ".$id_transportista."
									 AND VD.guias_fecha = '".$guia_fecha."'
								ORDER BY VD.lote_cod_lote, VD.lote_num_ticket, VD.lote_ticket_orden";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
        	$html .= '					<tr style="font-size: 20px; font-family: AgencyFB;">';
        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							'.$d;
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							MINERAL AURÍFERO EN BRUTO SIN PROCESAR';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
        	$html .= '							TN';
        	$html .= '						</td>';

        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">';
        	$html .= '							'.number_format($row_datos["guias_pesonetoajustado"], 2, '.', '');
        	$html .= '						</td>';

					$html .= '					</tr>';

					$d ++;
        }

        // Completa con líneas adicionales
        	$a = 1;
        	$adicional_descripcion = '';

        	while ($a < 8){
        		$html .= '					<tr style="font-size: 14px; font-family: AgencyFB;">';
	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; height: 25px;">';
	        	$html .= '						</td>';

	        	if ($a == 5){
	        		$adicional_descripcion = 'Concesión: <label style="font-family: AgencyFBb">'.$concesion.'</label>';
	        	}
	        	else{
	        		if ($a == 6){
	        			$adicional_descripcion = 'Código Único: <label style="font-family: AgencyFBb">'.$codigo_unico.'</label>';
	        		}
	        		else{
	        			$adicional_descripcion = '';
	        		}
	        	}

	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-size: 18px; padding-left: 10px;">';
	        	$html .= '							'.$adicional_descripcion;
	        	$html .= '						</td>';

	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        	$html .= '						</td>';

	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        	$html .= '						</td>';

	        	$html .= '						<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-family: AgencyFBb;">';
	        	$html .= '						</td>';

						$html .= '					</tr>';

        		$a ++;
        	}
      }
    }

		$html .= '					</tbody>
										</table>
									</div>';

	// 3. Cerrando guia
		$html .= '		<div class="row">
										<table style="width: 100%;">
											<tr style="font-size: 20px;">
												<td style="text-align: center; vertical-align: top; padding: 0px; width: 100%;">
													<table style="width: 100%; border-spacing: 0px;">
														<tr>
															<td style="font-family: AgencyFBb; font-size: 20px; padding: 0px; width: 24%;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #0099DD; color: #ffffff; padding: 5px; text-align: center;">
																	MOTIVO DEL TRASLADO
																</div>
															</td>

															<td style="font-family: AgencyFBb; font-size: 20px; padding: 0px;">
																<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; text-align: center;">
																	'.$motivo_traslado.'
																</div>
															</td>
														</tr>
													</table>
												<td>
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
	$document -> setPaper('A3', 'landscape');
	$document -> render();
	$document -> stream('Modelo de Guía de '.$nom_archivo.' - '.$nom_archivo_guia, array('Attachment' => 0));

?>
