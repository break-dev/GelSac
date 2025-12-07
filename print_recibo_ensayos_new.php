<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';
	require_once 'dompdf/vendor/phenx/php-font-lib/src/FontLib/Autoloader.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_md5 = $_GET["x"];
	$is_preliminar = $_GET["p"];

	// Ruta logo
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_recibo_ensayos_new.php')).'images/';

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

	$html = '	<!DOCTYPE html>
							<html lang="es">
								<head>
									<meta charset="UTF-8">
					        <meta name="viewport"
					              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
					        <meta http-equiv="X-UA-Compatible" content="ie=edge">
					        <title>Recibo - '.$recibo_codigo.'</title>

									<style type="text/css">
										@font-face {
									    font-family: "Agency FB";
									    font-style: normal;
									    font-weight: normal;
									    src: url("fonts/agencyfb.ttf");
										}

										body {
											font-family: "Agency FB";
											margin: 0;
											padding: 0;
										}

										@page  {
				              margin: 0;
				              padding: 0;
				            }
									</style>
						    </head>

								<body style="width: 100%; font-size: 4px;">
									<div class="row" style="text-align: center;">
										<img src="'.$ruta_images.'logo_fit.png" width="45mm"/>
									</div>
									<div class="row" style="margin-top: 1px; text-align: center;">
										'.strtoupper($des_sucursal).'
									</div>
									<div class="row" style="margin-top: 3px; text-align: center;">
										REQUERIMIENTO DE ANALISIS
									</div>
									<div class="row" style="text-align: center; font-weight: bold; font-size: 5px;">
										'.$recibo_codigo.'
									</div>
									<div class="row" style="text-align: center; font-size: 3.5px; font-weight: bold;">
										FORMATO CCL-RA.v1
									</div>
									<div class="row" style="margin-top: 3px; text-align: center; padding-right: 2px;">
										<table style="width: 100%; border-spacing: 0px; margin-left: -1px;">
											<tr>
												<td style="width: 21px;">
													EMISION:
												</td>

												<td style="width: 45px;">
													'.explode('-', explode(' ', $fechahora_recepcion)[0])[2].'/'.explode('-', explode(' ', $fechahora_recepcion)[0])[1].'/'.explode('-', explode(' ', $fechahora_recepcion)[0])[0].'
												</td>

												<td style="width: 8px;">
													HORA:
												</td>

												<td>
													'.explode(':', explode(' ', $fechahora_recepcion)[1])[0].':'.explode(':', explode(' ', $fechahora_recepcion)[1])[1].'
												<td>
											</tr>
										</table>
									</div>

									<div class="row" style="text-align: center; padding-right: 2px;">
										<table style="width: 100%; border-spacing: 0px; margin-left: -1px;">
											<tr>
												<td style="width: 21px;">
													ENTREGA:
												</td>

												<td style="width: 45px;">';

	if ($is_preliminar == 1){
		$html .= '								POR CONFIRMAR';
		$html .= '					</td>';
		$html .= '					<td style="width: 8px;">';
		$html .= '					</td>';
		$html .= '					<td style="width: 12px;">';
		$html .= '					</td>';
	}
	else{
		$html .= '								'.explode('-', explode(' ', $fechahora_entrega)[0])[2].'/'.explode('-', explode(' ', $fechahora_entrega)[0])[1].'/'.explode('-', explode(' ', $fechahora_entrega)[0])[0];

		$html .= '					</td>

												<td style="width: 8px;">
													HORA:
												</td>

												<td>
													'.explode(':', explode(' ', $fechahora_entrega)[1])[0].':'.explode(':', explode(' ', $fechahora_entrega)[1])[1].'
												<td>';
	}

	$html .= '					</tr>
										</table>
									</div>
									
									<div class="row" style="text-align: center; padding-right: 2px;">
										<table style="width: 100%; border-spacing: 0px; margin-top: -1px; margin-left: -1px;">
											<tr>
												<td style="width: 21px;">
													COD. TRA.:
												</td>

												<td>
													'.strtoupper($usuario_registro).'
												</td>
											</tr>
										</table>
									</div>

									<div class="row" style="text-align: center; margin-top: -0.5mm; margin-left: -5px;">
										----------------------------------------------------------------------------
									</div>

									<div class="row" style="font-weight: bold; padding-right: 2px;">
										CLIENTE A FACTURAR:
									</div>

									<div class="row" style="padding-right: 2px; margin-top: 1px;">
										RUC / DNI: '.$cliente_documento.'
									</div>

									<div class="row" style="padding-right: 2px; margin-top: 1px;">
										'.$cliente_razonsocial.'
									</div>

									<div class="row" style="text-align: center; margin-top: -0.5mm; margin-left: -5px;">
										----------------------------------------------------------------------------
									</div>

									<div class="row" style="font-weight: bold; padding-right: 2px;">
										CLIENTE A REPORTAR:
									</div>

									<div class="row" style="padding-right: 2px; margin-top: 1px;">
										'.$cliente_razonsocial.'
									</div>

									<div class="row" style="text-align: center; margin-top: -0.5mm; margin-left: -5px;">
										----------------------------------------------------------------------------
									</div>

									<div class="row" style="font-weight: bold; padding-right: 2px;">
										ENTREGADO POR:
									</div>

									<div class="row" style="padding-right: 2px; margin-top: 1px;">
										'.$entregado_por.'
									</div>

									<div class="row" style="padding-right: 2px; margin-top: 1px;">
										CANTIDAD: '.$total_muestras.' Muestra(s)
									</div>

									<div class="row" style="padding-right: 2px; margin-top: 1px;">
										LABORATORIO: METALES Y MINERALES
									</div>

									<div class="row" style="text-align: center; margin-top: -0.3mm; margin-left: -5px;">
										=============================================
									</div>

									<div class="row" style="font-weight: bold; padding-right: 2px; text-align: center;">
										A N A L I S I S &nbsp;&nbsp;&nbsp; S O L I C I T A D O S
									</div>';

	if ($is_ampliacion > 0){
		$html .= '			<div class="row" style="font-weight: bold; padding-right: 2px; text-align: center; font-size: 7px; margin-top: 5px; margin-bottom: 5px;">
											** A M P L I A C I O N **
										</div>';
	}

	$html .= '			<div class="row" style="text-align: center; margin-left: -5px;">
										=============================================
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

					$html .= '		      <div class="row" style="font-weight: bold; padding-right: 2px; font-size: 3.5px; margin-top: 1px;">
															'.strtoupper($row_detalle["nombre_muestra"]).'
															</div>';

					$html .= '		      <div class="row" style="padding-right: 2px; font-size: 3.5px; padding-left: 3px; margin-top: 1px;">
															'.strtoupper($row_detalle["TIPO_MUESTRA"]).' '.strtoupper($row_detalle["ESTADO_MUESTRA"]).' - ('.strtoupper($row_detalle["ENVASE_MUESTRA"]).')
															</div>';

					// Obtiene los análisis de cada muestra
						$analisis = '';
						$total_muestra = 0;
						$count_muestras = 0;
						$total_exceso += ((strlen($row_detalle["exceso"]) > 0) ? $row_detalle["exceso"] : 0);

						$html .= '		      <div class="row" style="padding-right: 2px; font-size: 4px; padding-left: 3px; margin-top: 1px;">';

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

																<div class="row" style="padding-right: 3px; font-size: 4px; margin-top: 1px; text-align: right;">
																	'.$abv_moneda.' '.number_format($total_muestra, 2, '.', '').'
																</div>';

						// 4. Verifica si tiene exceso
							if (strlen($row_detalle["exceso"]) > 0){
								$html .= '		  <div class="row" style="padding-right: 3px; font-size: 4px; margin-top: 1px; text-align: right;">
																	Exceso ('.$abv_moneda.'): '.number_format($row_detalle["exceso"], 2, '.', '').'
																</div>';
							}

					if ($m < $total_muestras){
						$html .= '		      <div class="row" style="text-align: center; margin-left: 0px; margin-top: 1px; font-size: 3.5px;">
																	--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
																</div>';
					}
					else{
						if ($tiene_recojo == 1){
							$html .= '		      <div class="row" style="text-align: center; margin-left: 0px; margin-top: 1px; font-size: 3.5px;">
																		--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
																	</div>

																	<div class="row" style="text-align: right; padding-right: 2px;">
																		<label style="text-align: right; font-weight: bold;">
																			Recojo:
																		</label>

																		<label style="text-align: right;">
																			S/ 10.00
																		</label>
																	</div>';
						}

						$html .= '		      <div class="row" style="text-align: center; margin-top: -0.3mm; margin-left: -5px;">
																	=============================================
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

    $html .= '		      <div class="row" style="padding: 0px; text-align: center;">
    											<table style="width: 100%;">
    												<tr>
    													<td>';

		if ($cliente_tienedscto == 1){
			$html .= '		      				<table style="margin-top: -2px;">
																		<tr>
																			<td style="text-align: center; width: 30px;">
																				S U B  T O T A L
																			</td>

																			<td style="text-align: center; width: 10px;">
																				DSCTO.
																			</td>
																		</tr>

																		<tr>
																			<td style="text-align: center; font-weight: bold; font-size: 6px;">
																				<div style="margin-top: -2px;">
																					'.$abv_moneda.' '.number_format($total_venta, 2, '.', '').'
																				</div>
																			</td>

																			<td style="text-align: center; font-weight: bold; font-size: 6px;">
																				<div style="margin-top: -2px;">
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
																		<td style="text-align: center; width: 30px;">
																			T O T A L
																		</td>
																	</tr>

																	<tr>
																		<td style="text-align: center; font-weight: bold; font-size: 6px;">
																			<div style="margin-top: -2px;">
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
		$html .= '						<div class="row" style="font-weight: bold; padding-right: 2px; text-align: center; font-size: 7px; margin-top: -2px; margin-bottom: 6px;">
														** A M P L I A C I O N **
													</div>';
	}

	$html .= '						<div class="row" style="font-weight: bold; text-align: center; margin-top: -0.3mm; font-size: 6px;">
													***********************************************
												</div>

												<div class="row" style="padding-right: 2px;">
													CELULAR A REPORTAR: '.$celular_areportar.'
												</div>

												<div class="row" style="text-align: center; margin-top: 5mm; margin-left: -2px;">
													--------------------------------------------
												</div>

												<div class="row" style="padding-right: 2px; text-align: center;">
													CONFORMIDAD DEL CLIENTE
												</div>

												<div class="row" style="font-weight: bold; text-align: center; margin-top: -0.3mm; font-size: 6px;">
													***********************************************
												</div>

												<div class="row" style="padding-right: 2px; font-size: 3.2px;">
													NOTA:
												</div>

												<div class="row" style="font-size: 3.2px;">
													TODO SERVICIO ACEPTADO, NO DA LUGAR A REEMBOLSOS.
												</div>

												<div class="row" style="padding-right: 2px; font-size: 3.2px; margin-top: 1mm; text-align: center; font-weight: bold;">
													LAS MUESTRAS SE SUTODIARAN POR UN PERIODO DE 20 DIAS.
												</div>

												<div class="row" style="padding-right: 2px; font-size: 3.2px; margin-top: 1mm; text-align: center; font-weight: bold;">
													ESTE NO ES UN COMPROBANTE DE PAGO
												</div>

												<div class="row" style="padding-right: 2px; font-size: 3.2px; margin-top: 1mm; text-align: center;">
													GRACIAS POR SU PREFERENCIA
												</div>

												<div class="row" style="padding-right: 2px; font-size: 3.2px; text-align: center; font-weight: bold; margin-bottom: 3px;">
													Nebula Lims
												</div>';

	// // Agregando el total de la venta
	// 	$q_total = "SELECT ISNULL(SUM(Precio_Final), 0) AS SUB_TOTAL,
	// 					   ISNULL(SUM(Precio_Exceso), 0) AS EXCESO,
	// 					   ROUND(ISNULL(SUM(Precio_Final), 0) + ISNULL(SUM(Precio_Exceso), 0), 2) AS TOTAL
  //                     FROM _max_labperu_DetallePedido D
  //                    WHERE Id_Pedido = ".$_GET["Id"];

	// 	if ($res_total = sqlsrv_query($enlacehost, $q_total)) {
	// 		while($row_total = sqlsrv_fetch_array($res_total)) {
	// 			$sub_total = $row_total["SUB_TOTAL"];
	// 			$exceso = $row_total["EXCESO"];
				
	// 			$_total = $row_total["TOTAL"];
	// 			$total_igv = $row_total["TOTAL"];

	// 			if ($incluir_igv == 1){
	// 				$total = number_format($_total, 2, '.', ',');
	// 				$igv = number_format($_total * 0.18, 2, '.', ',');
	// 				$total_igv = number_format($_total * 1.18, 2, '.', ',');
	// 			}
	// 			else{
	// 				$total = number_format($_total, 2, '.', ',');
	// 				$igv = number_format(0, 2, '.', ',');
	// 				$total_igv = number_format($_total, 2, '.', ',');
	// 			}

	// 			// $son = nombre_numeros(number_format($total_igv, 2, '.', ','));
	// 			$son = nombre_numeros($total_igv);
	// 		}
	// 	}

	// 	if ($mostrar_precio == 1){
	// 		$html .= '		<div style="margin-left: 1mm; margin-top: 3px; padding-right: 0.5mm">';
	// 		$html .= '			<table style="width: 98%; border-spacing: 0px 0px;">
	// 								<tr>
	// 									<th style="width: 90%">
	// 										Valor Venta (S/):
	// 									</th>
	// 									<th style="text-align: right">'.number_format($sub_total, 2, '.', ',').'
	// 									</th>
	// 								</tr>
	// 								<tr>
	// 									<th style="width: 90%">
	// 										Exceso (S/):
	// 									</th>
	// 									<th style="text-align: right">'.number_format($exceso, 2, '.', ',').'
	// 									</th>
	// 								</tr>
	// 								<tr>
	// 									<th style="width: 90%">
	// 										Total Valor Venta (S/):
	// 									</th>
	// 									<th style="text-align: right">'.$total.'
	// 									</th>
	// 								</tr>
	// 								<tr>
	// 									<th style="width: 90%">
	// 										I.G.V. (S/):
	// 									</th>
	// 									<th style="text-align: right">'.$igv.'
	// 									</th>
	// 								</tr>
	// 								<tr>
	// 									<th style="width: 90%">
	// 										Importe Total (S/):
	// 									</th>
	// 									<th style="text-align: right">'.$total_igv.'
	// 									</th>
	// 								</tr>
	// 								<tr colspan = "2">
	// 									<th style="vertical-align: top;">
	// 										Son: '.$son.'

	// 									</th>
	// 								</tr>
	// 							</table>
	// 						</div>';
	// 	}

	// // Finalizando html
	// 	// $html .= '			<div style="margin-left: 0.5mm"><center>
	// 	// 						..............................................................................................
	// 	// 					</center></div>
	// 	// 					<div style="margin-top: 0.5mm; margin-left: 0.6mm"><center>
	// 	// 						"LA CUSTODIA DE LAS CONTRAMUESTRAS ES DE 20 DÍAS CALENDARIO"
	// 	// 					</center></div>';

	// // Footer
	// 	if (substr($fecha_recepcion, 0, 10) >= '2023-01-03'){
	// 		$html .= '			<div style="margin-left: 0.5mm"><center>
	// 								.......................................................................................................
	// 							</center></div>
	// 							<div style="margin-top: 0.5mm; margin-left: 0.6mm"><center>
	// 								Cantidad mínima de ingreso para solicitud de contramuestra: Muestras por análisis newmont 1500 g | Otros análisis 800 g | Soluciones 300 ml
	// 							</center></div>
	// 							<div style="margin-left: 0.5mm"><center>
	// 								.......................................................................................................
	// 							</center></div>
	// 							<div style="margin: 1mm; text-align: justify; font-size: 3.5px;">
	// 								Tiempo de respuesta (TR) en Trujillo - Perú es de 1 (uno) día útil sujeto a volumen de muestras y carga de trabajo en el Laboratorio. <br><br>
	// 								Las muestras serán conservadas por un máximo de 14 días calendario o por un período menor de acuerdo a la naturaleza de la muestra, (tiempo en la cual el cliente puede solicitar su contramuestra), después de dicho período (14 días calendario) Lab Perú Minerals dejará de asumir responsabilidad por dichas muestras y serán dispuestas de cualquier otra forma a discreción. <br><br>
	// 								Lab Perú Minerals S.R.L no asume responsabilidad alguna por los daños indirectos, especiales, emergentes y/o consecuenciales incluyendo sin limitación lucro cesante, pérdida de negocio, ingresos, utilidades o beneficios, perdida de oportunidad y daño al prestigio o la reputación del Cliente ni de los gastos que pudieran derivarse de la retirada del servicio. <br><br>
	// 								La responsabilidad de Lab Perú Minerals S.R.L respecto de cualquier reclamación que surja debido a pérdida, daños y perjuicios o gastos de cualquier naturaleza, bajo ninguna circunstancia podrá exceder un total agregado igual a 8 veces la cantidad de los honorarios pagados con relación al servicio específico que haya dado lugar a dicha reclamación.
	// 							</div>';
	// 	}
	// 	else{
	// 		$html .= '			<div style="margin-left: 0.5mm"><center>
	// 								.......................................................................................................
	// 							</center></div>
	// 							<div style="margin-top: 0.5mm; margin-left: 0.6mm"><center>
	// 								'.(($is_newmont == 1) ? 'Muestras Newmont menores a 1.500 kg no se otorgará contra muestra en caso requiera' : 'Muestras menores a 800 gr no se otorgará contra muestra en caso requiera').'
	// 							</center></div>
	// 							<div style="margin-left: 0.5mm"><center>
	// 								.......................................................................................................
	// 							</center></div>
	// 							<div style="margin: 1mm; text-align: justify; font-size: 3.5px;">
	// 								Tiempo de respuesta (TR) en Trujillo - Perú es de 1 (uno) día útil sujeto a volumen de muestras y carga de trabajo en el Laboratorio. <br><br>
	// 								Las muestras serán conservadas por un máximo de 10 días calendario o por un período menor de acuerdo a la naturaleza de la muestra, (tiempo en la cual el cliente puede solicitar su contramuestra), después de dicho período (20 días calendario) Lab Perú Minerals dejará de asumir responsabilidad por dichas muestras y serán dispuestas de cualquier otra forma a discreción. <br><br>
	// 								Lab Perú Minerals S.R.L no asume responsabilidad alguna por los daños indirectos, especiales, emergentes y/o consecuenciales incluyendo sin limitación lucro cesante, pérdida de negocio, ingresos, utilidades o beneficios, perdida de oportunidad y daño al prestigio o la reputación del Cliente ni de los gastos que pudieran derivarse de la retirada del servicio. <br><br>
	// 								La responsabilidad de Lab Perú Minerals S.R.L respecto de cualquier reclamación que surja debido a pérdida, daños y perjuicios o gastos de cualquier naturaleza, bajo ninguna circunstancia podrá exceder un total agregado igual a 8 veces la cantidad de los honorarios pagados con relación al servicio específico que haya dado lugar a dicha reclamación.
	// 							</div>';
	// 	}

	// 	$html .= '			
	// 					</div>
	// 				</body>
	// 			  </html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper(array(0, 0, 72, 350));
	$document -> render();
	$document -> stream("Recibo - ".$recibo_codigo, array('Attachment' => 0));

?>
