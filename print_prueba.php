<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_md5 = $_GET["x"];
	$is_preliminar = $_GET["p"];

	// Ruta logo
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_prueba.php')).'images/';

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
		$recibo_codigo = '';
		$fechahora_recepcion = '';
		$fechahora_entrega = '';
		$cliente_documento = '';
		$cliente_razonsocial = '';
		$entregado_por = '';
		$celular_areportar = '';
		$cod_sucursal = '';
		$des_sucursal = '';
		$cod_moneda = '';
		$des_moneda = '';
		$tiene_recojo = 0;
		$cliente_tienedscto = 0;
		$dscto_porcentaje = 0;
		$usuario_registro = '';
		$total_muestras = 0;
		$is_ampliacion = 0;
		$m = 1;

		$q_datos = "SELECT C.recibo_codigo,
											 C.fechahora_recepcion,
											 C.fechahora_entrega,
											 C.cliente_documento,
											 C.cliente_razonsocial,
											 C.entregado_por,
											 C.celular_areportar,
											 C.cod_sucursal,
											 S.cod_sucursal AS COD_SUCURSAL,
		                   S.des_sucursal AS DES_SUCURSAL,
		                   C.cod_moneda,
		                   M.abv AS ABV_MONEDA,
		                   M.descripcion AS DES_MONEDA,
		                   C.tiene_recojo,
		                   C.cliente_tienedscto,
		                   C.dscto_porcentaje,
		                   C.usuario_registro,

		                   (SELECT COUNT(D.Id)
		                   		FROM recepcion_ensayos_detalle D
		                   	 WHERE D.id_cabecera = C.Id) AS TOTAL_MUESTRAS,

		                   IFNULL(C.is_ampliacion_de, 0) AS IS_AMPLIACION

									FROM recepcion_ensayos_cabecera C
					          	 INNER JOIN tb_sucursal S ON C.cod_moneda = S.Id
					          	 INNER JOIN tbconfig_monedas M ON C.cod_moneda = M.Id
								 WHERE md5(C.Id) = '".$id_md5."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
					$recibo_codigo = $row_datos["recibo_codigo"];
					$fechahora_recepcion = $row_datos["fechahora_recepcion"];
					$fechahora_entrega = $row_datos["fechahora_entrega"];
					$cliente_documento = $row_datos["cliente_documento"];
					$cliente_razonsocial = $row_datos["cliente_razonsocial"];
					$entregado_por = $row_datos["entregado_por"];
					$celular_areportar = $row_datos["celular_areportar"];
					$cod_sucursal = $row_datos["cod_sucursal"];
					$des_sucursal = $row_datos["DES_SUCURSAL"];
					$cod_moneda = $row_datos["cod_moneda"];
					$abv_moneda = $row_datos["ABV_MONEDA"];
					$des_moneda = $row_datos["DES_MONEDA"];
					$tiene_recojo = $row_datos["tiene_recojo"];
					$cliente_tienedscto = $row_datos["cliente_tienedscto"];
					$dscto_porcentaje = $row_datos["dscto_porcentaje"];
					$usuario_registro = $row_datos["usuario_registro"];
					$total_muestras = $row_datos["TOTAL_MUESTRAS"];
					$is_ampliacion = $row_datos["IS_AMPLIACION"];
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
					        <title>Recibo - '.$recibo_codigo.'</title>
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
						    	<div style="width: 100%; margin-left: 0px; margin-top: 0px; margin-right: 0px;">
										<div class="row" style="text-align: center;">
											<img src="'.$ruta_images.'logo_fit.png" width="100mm"/>
										</div>
										<div class="row" style="margin-top: -5px; text-align: center;">
											'.strtoupper($des_sucursal).'
										</div>
										<div class="row" style="margin-top: 3px; text-align: center;">
											REQUERIMIENTO DE ANALISIS
										</div>
										<div class="row" style="text-align: center; font-family: AgencyFBb; font-size: 18px; margin-top: -7px;">
											'.$recibo_codigo.'
										</div>
										<div class="row" style="text-align: center; font-family: AgencyFBb; font-size: 10px; margin-top: -5px;">
											FORMATO CCL-RA.v1
										</div>
										<div class="row" style="width: 100%; margin-top: 3px; text-align: center;">
											<table style="width: 100%; border-spacing: 0px;">
												<tr>
													<td style="width: 12mm;">
														EMISION:
													</td>

													<td style="width: 30mm;">
														<div style="margin: 0px; margin-left: 10px; font-family: AgencyFBb;">
														'.explode('-', explode(' ', $fechahora_recepcion)[0])[2].'/'.explode('-', explode(' ', $fechahora_recepcion)[0])[1].'/'.explode('-', explode(' ', $fechahora_recepcion)[0])[0].'
														</div>
													</td>

													<td style="text-align: right; width: 20mm">
														<div style="margin: 0px; margin-left: 10px; font-family: AgencyFBb;">
															HORA: '.explode(':', explode(' ', $fechahora_recepcion)[1])[0].':'.explode(':', explode(' ', $fechahora_recepcion)[1])[1].'
														</div>
													<td>
												</tr>
											</table>
										</div>

										<div class="row" style="text-align: center; margin-top: -7px;">
											<table style="width: 100%; border-spacing: 0px;">
												<tr>
													<td style="width: 12mm;">
														ENTREGA:
													</td>

													<td style="width: 30mm;">
														<div style="margin: 0px; margin-left: 10px; font-family: AgencyFBb;">';

	if ($is_preliminar == 1){
	$html .= '								POR CONFIRMAR
													</div>
												</td>
												<td style="text-align: right; width: 20mm">
												</td>';
	}
	else{
		$html .= '								'.explode('-', explode(' ', $fechahora_entrega)[0])[2].'/'.explode('-', explode(' ', $fechahora_entrega)[0])[1].'/'.explode('-', explode(' ', $fechahora_entrega)[0])[0];

		$html .= '					</td>

												<td style="text-align: right; width: 20mm">
													<div style="margin: 0px; margin-left: 10px; font-family: AgencyFBb;">
														HORA: '.explode(':', explode(' ', $fechahora_entrega)[1])[0].':'.explode(':', explode(' ', $fechahora_entrega)[1])[1].'
													</div>
												<td>';
	}

	$html .= '					</tr>
										</table>
									</div>
									
									<div class="row" style="text-align: center; margin-top: -7px;">
										<table style="width: 100%; border-spacing: 0px; margin-top: -1px;">
											<tr>
												<td style="width: 15mm;">
													COD.TRA.:
												</td>

												<td style="width: 30mm;">
													'.strtoupper($usuario_registro).'
												</td>

												<td style="text-align: right; width: 20mm">
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="text-align: center; margin-top: -1.5mm; margin-left: -5px;">
										-----------------------------------------------------------
									</div>

									<div style="text-align: left; margin-left: 1px;">
										<div class="row" style="padding-right: 2px; font-family: AgencyFBb;">
											CLIENTE A FACTURAR:
										</div>

										<div class="row" style="padding-right: 2px; margin-top: -7px;">
											RUC / DNI: '.$cliente_documento.'
										</div>

										<div class="row" style="padding-right: 2px; margin-top: -7px;">
											'.$cliente_razonsocial.'
										</div>
									</div>

									<div class="row" style="text-align: center; margin-top: -1.5mm; margin-left: -5px;">
										-----------------------------------------------------------
									</div>

									<div style="text-align: left; margin-left: 1px;">
										<div class="row" style="padding-right: 2px; font-family: AgencyFBb;">
											CLIENTE A REPORTAR:
										</div>

										<div class="row" style="padding-right: 2px; margin-top: -7px;">
											'.$cliente_razonsocial.'
										</div>
									</div>

									<div class="row" style="text-align: center; margin-top: -1.5mm; margin-left: -5px;">
										-----------------------------------------------------------
									</div>

									<div style="text-align: left; margin-left: 1px;">
										<div class="row" style="padding-right: 2px; font-family: AgencyFBb;">
											ENTREGADO POR: '.$entregado_por.'
										</div>

										<div class="row" style="padding-right: 2px; margin-top: -7px;">
											CANTIDAD: '.$total_muestras.' Muestra(s)
										</div>

										<div class="row" style="padding-right: 2px; margin-top: -7px;">
											LABORATORIO: METALES Y MINERALES
										</div>
									</div>

									<div class="row" style="text-align: center; margin-top: -1.5mm; margin-left: -5px;">
										========================================
									</div>

									<div class="row" style="text-align: center; width: 100%;">
										<label>A N A L I S I S</label><label style="margin-left: 10px;">S O L I C I T A D O S</label>
									</div>';

	if ($is_ampliacion > 0){
		$html .= '			<div class="row" style="font-weight: bold; padding-right: 2px; text-align: center; font-size: 7px; margin-top: 5px; margin-bottom: 5px;">
											** A M P L I A C I O N **
										</div>';
	}

	$html .= '			<div class="row" style="text-align: center; margin-top: -1.5mm; margin-left: -5px;">
										========================================
									</div>';

	// Recuperando el detalle de la recepción
		$total_venta = 0;
		$total_exceso = 0;

		$q_detalle = "SELECT D.Id,

												 (SELECT COUNT(D_x.Id) + 1
										        FROM recepcion_ensayos_detalle D_x
										       WHERE D_x.Id < D.Id
										         AND DATE(D_x.fechahora_registro) = DATE(D.fechahora_registro)) AS COD_1,

												 (SELECT COUNT(D_x.Id) + 1
										        FROM recepcion_ensayos_detalle D_x
										       WHERE D_x.Id < D.Id
										         AND MONTH(D_x.fechahora_registro) = MONTH(D.fechahora_registro)) AS COD_2,

												 D.nombre_muestra,
												 T.descripcion AS TIPO_MUESTRA,
        								 E.descripcion AS ESTADO_MUESTRA,
       									 V.descripcion AS ENVASE_MUESTRA,
       									 D.exceso
									  FROM recepcion_ensayos_detalle D
												 INNER JOIN tbconfig_tiposmuestra T ON D.cod_tipomuestra = T.Id
        								 INNER JOIN tbconfig_estadosmuestra E ON D.cod_estadomuestra = E.Id
        								 INNER JOIN tbconfig_envasesmuestra V ON D.cod_envasemuestra = V.Id
									 WHERE md5(D.id_cabecera) = '".$id_md5."'";

		if ($res_detalle = mysqli_query($enlace, $q_detalle)){
      if (mysqli_num_rows($res_detalle) > 0) {
        while($row_detalle = mysqli_fetch_array($res_detalle)){
        	// $html .= '		      <div class="row" style="font-weight: bold; padding-right: 2px; font-size: 3.5px; margin-top: 1px;">
					// 										'.str_pad($row_detalle["COD_1"], 3, "0", STR_PAD_LEFT).' / '.str_pad($row_detalle["COD_2"], 4, "0", STR_PAD_LEFT).' '.strtoupper($row_detalle["nombre_muestra"]).'
					// 										</div>';

					$html .= '		      <div style="text-align: left;">
																<div class="row" style="margin-top: -7px; font-family: AgencyFBb;">
																'.strtoupper($row_detalle["nombre_muestra"]).'
																</div>';

					$html .= '		      	<div class="row" style="margin-top: -7px;">
																'.strtoupper($row_detalle["TIPO_MUESTRA"]).' '.strtoupper($row_detalle["ESTADO_MUESTRA"]).' - ('.strtoupper($row_detalle["ENVASE_MUESTRA"]).')
																</div>';

					// Obtiene los análisis de cada muestra
						$analisis = '';
						$total_muestra = 0;
						$count_muestras = 0;
						$total_exceso += ((strlen($row_detalle["exceso"]) > 0) ? $row_detalle["exceso"] : 0);

						$html .= '		      <div class="row" style="margin-top: -7px;">';

						// 1. Obtiene análisis de Precios Generales
			        $q_analisis = "SELECT CONCAT('(', EA.abv, CASE WHEN LENGTH(IFNULL(C.abv, '')) > 0 THEN CONCAT(' ', C.abv) ELSE '' END, ')') AS abv,
			                              REA.total
			                         FROM recepcion_ensayos_analisis REA
			                              INNER JOIN tb_ensayos_analisisclasificaciones_detalle ACD ON REA.cod_analisis = ACD.Id
			                              INNER JOIN tb_ensayos_analisisclasificaciones C ON ACD.cod_clasificacion = C.Id
			                              INNER JOIN tb_ensayos_analisis EA ON ACD.cod_analisis = EA.Id
			                        WHERE REA.id_detalle = ".$row_detalle["Id"]."
			                          AND is_paquete = 0
			                          AND is_paquetecliente = 0
			                       ORDER BY EA.orden";

							if ($res_analisis = mysqli_query($enlace, $q_analisis)){
					      if (mysqli_num_rows($res_analisis) > 0) {
					        while($row_analisis = mysqli_fetch_array($res_analisis)){
					        	$analisis .= '		        '.$row_analisis["abv"].', ';

					        	$total_muestra += $row_analisis["total"];

					        	$total_venta +=  $row_analisis["total"];

					        	$count_muestras ++;
					        }
					      }
					    }

					  // 2. Obtiene análisis de Paquetes Generales
			        $q_paquetes = "SELECT C.abv,
			                              C.descripcion AS CLASIFICACION,
			                              REA.cod_analisis,
			                              REA.total,
			                              REA.is_paquete,
			                              REA.is_paquetecliente
			                         FROM recepcion_ensayos_analisis REA
			                              INNER JOIN tb_ensayos_analisisclasificaciones_paquetes P ON REA.cod_analisis = P.Id
			                              INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
			                        WHERE REA.id_detalle = ".$row_detalle["Id"]."
			                          AND REA.is_paquete = 1";

			        if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
			          if (mysqli_num_rows($res_paquetes) > 0) {
			            while($row_paquetes = mysqli_fetch_array($res_paquetes)){
			              // Armando la descripción de análisis por paquete
			                $abv = '';
			                $descripcion = '';

			                $q_analisispaquete = "SELECT A.abv,
			                                            A.descripcion
			                                       FROM tb_ensayos_analisisclasificaciones_paquetes_detalle D
			                                            INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
			                                      WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

			                if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
			                  if (mysqli_num_rows($res_analisispaquete) > 0) {
			                    while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
			                      $abv .= $row_analisispaquete["abv"].' - ';
			                      $descripcion .= $row_analisispaquete["descripcion"].' - ';
			                    }
			                  }
			                }

			                if (strlen($abv) > 0){
			                  $abv = substr($abv, 0, -3);
			                  $descripcion = substr($descripcion, 0, -3);
			                }

			              // Creando el query final
			                $q_analisis = "SELECT '(".$abv.")' AS abv,
			                                        ".$row_paquetes["total"]." AS total";

			                if ($res_analisis = mysqli_query($enlace, $q_analisis)){
									      if (mysqli_num_rows($res_analisis) > 0) {
									        while($row_analisis = mysqli_fetch_array($res_analisis)){
									        	$analisis .= '		        '.$row_analisis["abv"].', ';

									        	$total_muestra += $row_analisis["total"];

									        	$total_venta +=  $row_analisis["total"];

									        	$count_muestras ++;
									        }
									      }
									    }
								  }
								}
							}

						// 3. Obtiene análisis de Paquetes de Clientes
			        $q_paquetes = "SELECT C.abv,
			                              C.descripcion AS CLASIFICACION,
			                              REA.cod_analisis,
			                              REA.total,
			                              REA.is_paquete,
			                              REA.is_paquetecliente
			                         FROM recepcion_ensayos_analisis REA
			                              INNER JOIN tb_ensayos_analisisclasificaciones_paquetesclientes P ON REA.cod_analisis = P.Id
			                              INNER JOIN tb_ensayos_analisisclasificaciones C ON P.cod_clasificacion = C.Id
			                        WHERE REA.id_detalle = ".$row_detalle["Id"]."
			                          AND REA.is_paquetecliente = 1";

			        if ($res_paquetes = mysqli_query($enlace, $q_paquetes)){
			          if (mysqli_num_rows($res_paquetes) > 0) {
			            while($row_paquetes = mysqli_fetch_array($res_paquetes)){
			              // Armando la descripción de análisis por paquete
			                $abv = '';
			                $descripcion = '';

			                $q_analisispaquete = "SELECT A.abv,
			                                            A.descripcion
			                                       FROM tb_ensayos_analisisclasificaciones_paquetesclientes_detalle D
			                                            INNER JOIN tb_ensayos_analisis A ON D.cod_analisis = A.Id
			                                      WHERE D.cod_paquete = ".$row_paquetes["cod_analisis"];

			                if ($res_analisispaquete = mysqli_query($enlace, $q_analisispaquete)){
			                  if (mysqli_num_rows($res_analisispaquete) > 0) {
			                    while($row_analisispaquete = mysqli_fetch_array($res_analisispaquete)){
			                      $abv .= $row_analisispaquete["abv"].' - ';
			                      $descripcion .= $row_analisispaquete["descripcion"].' - ';
			                    }
			                  }
			                }

			                if (strlen($abv) > 0){
			                  $abv = substr($abv, 0, -3);
			                  $descripcion = substr($descripcion, 0, -3);
			                }

			              // Creando el query final
			                $q_analisis = "SELECT '(".$abv.")' AS abv,
			                                        ".$row_paquetes["total"]." AS total";

			                if ($res_analisis = mysqli_query($enlace, $q_analisis)){
			                  if (mysqli_num_rows($res_analisis) > 0) {
			                    while($row_analisis = mysqli_fetch_array($res_analisis)){
									        	$analisis .= '		        '.$row_analisis["abv"].', ';

									        	$total_muestra += $row_analisis["total"];

									        	$total_venta +=  $row_analisis["total"];

									        	$count_muestras ++;
			                    }
			                  }
			                }
			            }
			          }
			        }

			        if (strlen($analisis) > 0){
					    	$analisis = substr($analisis, 0, -2);
					    }

				    $html .= '		      	'.$analisis;

				    $html .= '		      </div>

																<div class="row" style="4px; margin-top: 1px; text-align: right; font-family: AgencyFBb; margin-right: 5px;">
																	'.$abv_moneda.' '.number_format($total_muestra, 2, '.', '').'
																</div>';

						// 4. Verifica si tiene exceso
							if (strlen($row_detalle["exceso"]) > 0){
								$html .= '		  <div class="row" style="margin-top: 1px; text-align: right; margin-right: 5px;">
																	Exceso ('.$abv_moneda.'): '.number_format($row_detalle["exceso"], 2, '.', '').'
																</div>';
							}

					$html .= '		</div>';

					if ($m < $total_muestras){
						$html .= '		      <div class="row" style="text-align: center; margin-left: 0px; margin-top: -7px;">
																	--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
																</div>';
					}
					else{
						if ($tiene_recojo == 1){
							$html .= '		      <div class="row" style="text-align: center; margin-left: 0px; margin-top: -7px;">
																		--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
																	</div>

																	<div class="row" style="text-align: right; padding-right: 2px;">
																		<label style="text-align: right;">
																			Recojo:
																		</label>

																		<label style="text-align: right;">
																			S/ 10.00
																		</label>
																	</div>';
						}

						$html .= '		      <div class="row" style="text-align: center; margin-top: -1.5mm; margin-left: -5px;">
																	========================================
																</div>';
					}

					$m ++;
        }
      }
    }

  // Agrega Exceso
    $total_venta += $total_exceso;

  // Agregando el total de la venta
    if ($tiene_recojo == 1){
    	$total_venta += 10;
    }

    $html .= '		      <div class="row" style="padding: 0px; text-align: center; margin-top: -7px;">
    											<table style="width: 100%;">
    												<tr>
    													<td>';

		if ($cliente_tienedscto == 1){
			$html .= '		      				<table style="margin-top: -2px;">
																		<tr>
																			<td style="text-align: center; width: 25mm;">
																				S U B  T O T A L
																			</td>

																			<td style="text-align: center; width: 15mm;">
																				DSCTO.
																			</td>
																		</tr>

																		<tr>
																			<td style="text-align: center;">
																				<div style="margin-top: -7px;">
																					'.$abv_moneda.' '.number_format($total_venta, 2, '.', '').'
																				</div>
																			</td>

																			<td style="text-align: center;">
																				<div style="margin-top: -7px;">
																					'.$dscto_porcentaje.' %
																				</div>
																			</td>
																		</tr>
																	</table>
																</td>';

			$total_venta = $total_venta * ((100 - $dscto_porcentaje) / 100);
		}

		$html .= '		      			<td>
																<table style="margin-top: -2px; '.(($cliente_tienedscto == 0) ? 'margin-left: -5px; width: 100%' : 'margin-left: -10px').';">
																	<tr>
																		<td style="text-align: center; width: 25mm;">
																			T O T A L
																		</td>
																	</tr>

																	<tr>
																		<td style="text-align: center;">
																			<div style="margin-top: -7px; font-family: AgencyFBb;">
																				'.$abv_moneda.' '.number_format($total_venta, 2, '.', '').'
																			</div>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</div>';

	if ($is_ampliacion > 0){
		$html .= '						<div class="row" style="padding-right: 2px; text-align: center; margin-top: -2px; margin-bottom: 6px;">
														** A M P L I A C I O N **
													</div>';
	}

	$html .= '						<div class="row" style="text-align: center; margin-top: -7px;">
													********************************************
												</div>

												<div class="row" style="margin-top: -7px; text-align: left; font-size: 12px;">
													CELULAR A REPORTAR: '.$celular_areportar.'
												</div>

												<div class="row" style="text-align: center; margin-top: 50px;">
													--------------------------------------------------
												</div>

												<div class="row" style="text-align: center; margin-top: -7px;">
													CONFORMIDAD DEL CLIENTE
												</div>

												<div class="row" style="text-align: center; margin-top: -3px;">
													********************************************
												</div>

												<div style="text-align: left; font-size: 12px; margin-top: -7px;">
													<div class="row" style="padding-right: 2px;">
														NOTA:
													</div>

													<div class="row">
														TODO SERVICIO ACEPTADO, NO DA LUGAR A REEMBOLSOS.
													</div>

													<div class="row" style="margin-top: 1mm; text-align: center; font-family: AgencyFBb;">
														SE SUTODIARAN LAS MUESTRAS POR UN PERIODO DE 20 DIAS.
													</div>

													<div class="row" style="margin-top: 1mm; text-align: center; font-family: AgencyFBb;">
														ESTE NO ES UN COMPROBANTE DE PAGO
													</div>

													<div class="row" style="margin-top: 1mm; text-align: center;">
														GRACIAS POR SU PREFERENCIA
													</div>

													<div class="row" style="text-align: center; margin-bottom: 10px; font-family: AgencyFBb;">
														The Nebula Lims
													</div>

													<div class="row" style="text-align: right; margin-right: 10px;">
														.
													</div>
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

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper(array(0, 0, 190, 6000));
	$document -> render();
	$document -> stream("Recibo - ".$recibo_codigo, array('Attachment' => 0));

?>
