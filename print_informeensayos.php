<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');

	require_once 'dompdf/autoload.inc.php';

	use Dompdf\Dompdf;
	use Dompdf\Options;

	$id_md5 = $_GET["x"];

	// Ruta logo
    $ruta_images = 'https://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $ruta_images = substr($ruta_images, 0, strpos($ruta_images, 'print_informeensayos.php')).'images/';

	// Obteniendo datos de cabecera de la recepción
		$num_informeensayos = '';
		$nom_cliente = '';
		$informacion_contacto = '';
		$telefonos = '';
		$tipo_muestra = '';
		$tipo_envase = '';
		$fecha_recepcion = '';
		$fechaensayo_inicio = '';
		$fechaensayo_fin = '';
		$fecha_cierre = '';
		$nom_muestra = '';
		$cod_interno = '';

		$q_datos = "SELECT RED.Id,
											 MD5(RED.Id) AS ID_MD5,
											 REC.cod_sucursal,
											 S.des_sucursal,
											 IFNULL(REC.clienteventa_documento, REC.cliente_documento) AS cliente_documento,
											 IFNULL(CLC.razon_social, CL.razon_social) AS cliente_razonsocial,
											 CONCAT(CL.telefono1, CASE WHEN LENGTH(TRIM(IFNULL(CL.telefono2, ''))) > 0 THEN CONCAT(' - ', CL.telefono2) ELSE '' END) AS TELEFONOS_CLIENTE,
											 TM.descripcion AS TIPO_MUESTRA,
											 EM.descripcion AS ENVASE_MUESTRA,
											 DATE(REC.instruccion_fechahoraregistro) AS FECHA_RECEPCION,
											 D.fechaensayo_inicio,
											 D.fechaensayo_fin,
											 REC.entregado_por,
											 D.num_informeensayos,
											 D.cod_interno,
								       D.nombre_muestra,
								       D.porc_h2o,
								       D.porc_cu,
								       D.porc_cuox,
								       D.ley_au_gt,
								       D.ley_au_ot,
								       D.ley_ag_gt,
								       D.ley_ag_ot,
								       D.porc_as,
								       D.porc_pb,
								       D.porc_pbox,
								       D.porc_zn,
								       D.porc_znox,
								       D.porc_sb,
								       D.porc_bi,
								       D.porc_cd,
								       D.porc_s,
								       D.porc_fe,
								       D.observacion,
								       D.is_cerrado,
								       DATE(D.fechahora_cerrado) AS fechahora_cerrado,
								       D.usuario_cerrado
								  FROM import_resultadoslq_detalle D
											 INNER JOIN recepcion_ensayos_detalle RED ON D.cod_interno = RED.cod_interno
									     INNER JOIN recepcion_ensayos_cabecera REC ON RED.id_cabecera = REC.Id
									     INNER JOIN tb_sucursal S ON REC.cod_sucursal = S.Id
											 LEFT JOIN tb_clientes CL ON REC.cliente_documento = CL.documento
											 LEFT JOIN tb_clientes CLC ON REC.clienteventa_documento = CLC.documento
											 INNER JOIN tbconfig_tiposmuestra TM ON RED.cod_tipomuestra = TM.Id
											 INNER JOIN tbconfig_envasesmuestra EM ON RED.cod_envasemuestra = EM.Id
								 WHERE MD5(RED.Id) = '".$id_md5."'";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
      if (mysqli_num_rows($res_datos) > 0) {
        while($row_datos = mysqli_fetch_array($res_datos)){
					// Datos generales
						$num_informeensayos = $row_datos["cod_interno"];
						$nom_cliente = $row_datos["cliente_razonsocial"];
						$informacion_contacto = $row_datos["entregado_por"];
						$telefonos = $row_datos["TELEFONOS_CLIENTE"];
						$tipo_muestra = $row_datos["TIPO_MUESTRA"];
						$tipo_envase = $row_datos["ENVASE_MUESTRA"];
						$fecha_recepcion = $row_datos["FECHA_RECEPCION"];
						$fechaensayo_inicio = $row_datos["fechaensayo_inicio"];
						$fechaensayo_fin = $row_datos["fechaensayo_fin"];
						$fecha_cierre = $row_datos["fechahora_cerrado"];
						$nom_muestra = $row_datos["nombre_muestra"];
						$cod_interno = $row_datos["cod_interno"];

					// Leyes
						$porc_h2o = $row_datos["porc_h2o"];
						$porc_cu =$row_datos["porc_cu"];
						$porc_cuox =$row_datos["porc_cuox"];
						$ley_au_gt =$row_datos["ley_au_gt"];
						$ley_au_ot =$row_datos["ley_au_ot"];
						$ley_ag_gt =$row_datos["ley_ag_gt"];
						$ley_ag_ot = $row_datos["ley_ag_ot"];
						$porc_as =$row_datos["porc_as"];
						$porc_pb =$row_datos["porc_pb"];
						$porc_pbox =$row_datos["porc_pbox"];
						$porc_zn =$row_datos["porc_zn"];
						$porc_znox = $row_datos["porc_znox"];
						$porc_sb = $row_datos["porc_sb"];
						$porc_bi = $row_datos["porc_bi"];
						$porc_cd = $row_datos["porc_cd"];
						$porc_s = $row_datos["porc_s"];
						$porc_fe = $row_datos["porc_fe"];

					// Obtiene los análisis de la muestra
						$analisis = '';

						$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

						// 1. Obtiene análisis de Precios Generales
							$q_analisis = "SELECT CONCAT('|', EA.abv, CASE WHEN LENGTH(IFNULL(C.abv, '')) > 0 THEN CONCAT('', C.abv) ELSE '' END, '|') AS abv,
																		REA.total
															 FROM recepcion_ensayos_analisis REA
																		INNER JOIN tb_ensayos_analisisclasificaciones_detalle ACD ON REA.cod_analisis = ACD.Id
																		INNER JOIN tb_ensayos_analisisclasificaciones C ON ACD.cod_clasificacion = C.Id
																		INNER JOIN tb_ensayos_analisis EA ON ACD.cod_analisis = EA.Id
															WHERE REA.id_detalle = ".$row_datos["Id"]."
																AND is_paquete = 0
																AND is_paquetecliente = 0
														 ORDER BY EA.orden";

							if ($res_analisis = mysqli_query($enlace, $q_analisis)){
								if (mysqli_num_rows($res_analisis) > 0) {
									while($row_analisis = mysqli_fetch_array($res_analisis)){
										$analisis .= '            '.$row_analisis["abv"];
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
															WHERE REA.id_detalle = ".$row_datos["Id"]."
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
														$abv .= '|'.$row_analisispaquete["abv"].'|';
														$descripcion .= $row_analisispaquete["descripcion"].' - ';
													}
												}
											}

											if (strlen($abv) > 0){
												// $abv = substr($abv, 0, -3);
												$descripcion = substr($descripcion, 0, -3);
											}

										// Creando el query final
											$q_analisis = "SELECT '".$abv."' AS abv,
																						".$row_paquetes["total"]." AS total";

											if ($res_analisis = mysqli_query($enlace, $q_analisis)){
												if (mysqli_num_rows($res_analisis) > 0) {
													while($row_analisis = mysqli_fetch_array($res_analisis)){
														$analisis .= '            '.$row_analisis["abv"];
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
															WHERE REA.id_detalle = ".$row_datos["Id"]."
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
														$abv .= '|'.$row_analisispaquete["abv"].'|';
														$descripcion .= $row_analisispaquete["descripcion"].' - ';
													}
												}
											}

											if (strlen($abv) > 0){
												// $abv = substr($abv, 0, -3);
												$descripcion = substr($descripcion, 0, -3);
											}

										// Creando el query final
											$q_analisis = "SELECT '".$abv."' AS abv,
																						".$row_paquetes["total"]." AS total";

											if ($res_analisis = mysqli_query($enlace, $q_analisis)){
												if (mysqli_num_rows($res_analisis) > 0) {
													while($row_analisis = mysqli_fetch_array($res_analisis)){
														$analisis .= '            '.$row_analisis["abv"];
													}
												}
											}
									}
								}
							}

							if (strlen($analisis) > 0){
								// $analisis = substr($analisis, 0, -2);
								$analisis = str_replace(' ', '', $analisis);
							}
				}
			}
		}

	// Inicia html
    $html = '	<!DOCTYPE html>
						 	<html lang="es">
								<head>
									<title>Informe de Ensayo - '.$num_informeensayos.'</title>

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

								<body style="margin-left: 20px; margin-right: 20px;">
									<div style="margin-left: -20px; position: absolute; z-index: -1;">
										<img src="'.$ruta_images.'fondo_informeensayos.png" style="margin-left: 0px;"/>
									</div>

									<div style="margin-top: 50px;">
										<img src="'.$ruta_images.'logo_fit.png" width="180px"/>
									</div>

									<div class="row">
										<div style="margin-top: -10px; text-align: center; font-weight: bold; font-size: 14px">
											INFORME DE ENSAYO N° <label style="font-size: 22px;">'.$num_informeensayos.'</label>
										</div>
									</div>

									<div class="row" style="margin-top: 20px;">
										<table style="width: 100%; margin-left: 60px;">
											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Cliente
												</td>

												<td style="width: 42%; font-weight: bold;">
													: '.strtoupper($nom_cliente).'
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Información del contacto
												</td>

												<td style="width: 42%; font-weight: bold;">
													: '.((strlen($informacion_contacto) == 0) ? '-' : strtoupper($informacion_contacto)).'
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Correo o teléfono
												</td>

												<td style="width: 42%; font-weight: bold;">
													: '.((strlen($telefonos) == 0) ? '-' : strtoupper($telefonos)).'
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Producto descrito como
												</td>

												<td style="width: 42%; font-weight: bold;">
													: '.((strlen($tipo_muestra) == 0) ? '-' : strtoupper($tipo_muestra)).'
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Tipo de análisis
												</td>

												<td style="width: 42%; font-weight: bold;">
													: LOTE
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Presentación de la muestra
												</td>

												<td style="width: 42%; font-weight: bold;">
													: '.((strlen($tipo_envase) == 0) ? '-' : strtoupper($tipo_envase)).'
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Fecha de recepción
												</td>

												<td style="width: 42%; font-weight: bold;">
													: '.explode('-', $fecha_recepcion)[2].'/'.explode('-', $fecha_recepcion)[1].'/'.explode('-', $fecha_recepcion)[0].'
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Fecha de ensayo
												</td>

												<td style="width: 42%; font-weight: bold;">
													: '.$fechaensayo_inicio.'  al  '.$fechaensayo_fin.'
												</td>
											</tr>

											<tr style="font-size: 12px;">
												<td style="width: 12.7%; font-size: 13px;">
													Lugar de ensayos
												</td>

												<td style="width: 42%; font-weight: bold;">
													: CORPORACION COPPER CAVE SAC
												</td>
											</tr>
										</table>
									</div>

									<div style="width: 100%;">
										<hr style="color: #00a2aa; border: solid; border-width: 2px; width: 100%;"/>
									</div>

									<div style="width: 100%;">
										<label style="font-weight: bold; font-size: 12px;">RESULTADOS:</label>
									</div>';

	// Obtiene la información de Métodos para ser comparados contra los resultados
		$analisis_x = str_replace('||', "', '", $analisis);
		$analisis_x = strtolower(str_replace('|', "'", $analisis_x));

		$analisis_q = str_replace('||', "|", $analisis);
		$analisis_q = substr($analisis_q, 1);
		$analisis_q = strtolower(substr($analisis_q, 0, -1));
		$arr_elementos = explode('|', $analisis_q);

		$arr_metodos = '';

		$q_metodos = "SELECT ME.cod_metodo,
												 LOWER(EA.abv) AS ABV,
												 MED.num_decimales,
									       MED.unidad_medida,
									       MED.limite_minimo,
									       MED.limite_maximo,
									       MED.vigencia_desde,
									       MED.vigencia_hasta,
									       ME.nom_metodo
									  FROM metodos_ensayo_detalle MED
												 INNER JOIN tbconfig_metodosensayo ME ON MED.id_metodo = ME.Id
										     INNER JOIN tb_ensayos_analisis EA ON MED.id_elemento = EA.Id
									 WHERE MED.id_elemento IN (SELECT Id
									 														 FROM tb_ensayos_analisis
									 														WHERE LOWER(abv) IN (".$analisis_x."))
										 AND ME.is_concentradogeoquimico = 1
										 AND MED.estado = 'A'
										 AND '".$fecha_recepcion."' BETWEEN vigencia_desde AND IFNULL(vigencia_hasta, '".$g_fecha."')
									ORDER BY ME.cod_metodo";

		if ($res_metodos = mysqli_query($enlace, $q_metodos)){
			if (mysqli_num_rows($res_metodos) > 0) {
				while($row_metodos = mysqli_fetch_array($res_metodos)){
					// Comprueba por cada elemento que el resultado se encuentre dentro de los rangos
						$e = 0;
						$resultado = '';

						$is_h2o = 0;
						$is_cu = 0;
						$is_cuox = 0;
						$is_au = 0;
						$is_ag = 0;
						$is_as = 0;
						$is_pb = 0;
						$is_pbox = 0;
						$is_zn = 0;
						$is_znox = 0;
						$is_sb = 0;
						$is_bi = 0;
						$is_cd = 0;
						$is_s = 0;
						$is_fe = 0;

					// Obtiene el resultado
						while ($e < count($arr_elementos)){
							if ($arr_elementos[$e] == $row_metodos["ABV"]){
								// Identifica el elemento
									if ($arr_elementos[$e] == 'h2o'){
										$resultado = $porc_h2o;

										$is_h2o = 1;
									}

									if ($arr_elementos[$e] == 'cu'){
										$resultado = $porc_cu;

										$is_cu = 1;
									}

									if ($arr_elementos[$e] == 'cuox'){
										$resultado = $porc_cuox;

										$is_cuox = 1;
									}

									if ($arr_elementos[$e] == 'au'){
										$resultado = $ley_au_gt;

										$is_au = 1;
									}

									if ($arr_elementos[$e] == 'ag'){
										$resultado = $ley_ag_gt;

										$is_ag = 1;
									}

									if ($arr_elementos[$e] == 'as'){
										$resultado = $porc_as;

										$is_as = 1;
									}

									if ($arr_elementos[$e] == 'pb'){
										$resultado = $porc_pb;

										$is_pb = 1;
									}

									if ($arr_elementos[$e] == 'pbox'){
										$resultado = $porc_pbox;

										$is_pbox = 1;
									}

									if ($arr_elementos[$e] == 'zn'){
										$resultado = $porc_zn;

										$is_zn = 1;
									}

									if ($arr_elementos[$e] == 'znox'){
										$resultado = $porc_znox;

										$is_znox = 1;
									}

									if ($arr_elementos[$e] == 'sb'){
										$resultado = $porc_sb;

										$is_sb = 1;
									}

									if ($arr_elementos[$e] == 'bi'){
										$resultado = $porc_bi;

										$is_bi = 1;
									}

									if ($arr_elementos[$e] == 'cd'){
										$resultado = $porc_cd;

										$is_cd = 1;
									}

									if ($arr_elementos[$e] == 's'){
										$resultado = $porc_s;

										$is_s = 1;
									}

									if ($arr_elementos[$e] == 'fe'){
										$resultado = $porc_fe;

										$is_fe = 1;
									}

								// Verificando el método correcto según el intervalo
									if ($resultado >= $row_metodos["limite_minimo"] && $resultado <= $row_metodos["limite_maximo"]){
										$arr_metodos .= $row_metodos["cod_metodo"].'|'.
																		$arr_elementos[$e].'|'.
																		$row_metodos["unidad_medida"].'|'.
																		$resultado.'|'.
																		$row_metodos["nom_metodo"].'|'.
																		$row_metodos["num_decimales"].';';
									}
							}

							$e ++;
						}
				}
			}
		}

	// Setea el html de la tabla de Resultados
		$factor = 34.285;
		$arr_metodos = substr($arr_metodos, 0, -1);
		$arr_metodos = explode(';', $arr_metodos);
		$arr_detalle = '';

		$html .= '<div style="width: 100%; margin-top: 10px;">';
		$html .= '	<table class="table" style="border-spacing: 0px;">';

		// Primera cabecera (Métodos)
			$html .= '		<tr style="font-size: 10px;">';
			$html .= '			<td colspan="2" style="border: solid; border-width: 0px; width: 35%">';

			$html .= '			</td>';

			$m = 0;

			while ($m < count($arr_metodos)){
				$arr_detalle = $arr_metodos[$m];
				$arr_detalle = explode('|', $arr_detalle);

				$html .= '			<td '.(($arr_detalle[1] == 'au' || $arr_detalle[1] == 'ag') ? 'colspan="2"' : '').' style="border: solid; border-width: 0.5px; border-color: #000000; color: #000000; text-align: center;  vertical-align: middle;">';
				$html .= '				'.$arr_detalle[0];
				$html .= '			</td>';

				$m ++;
			}

			$html .= '		</tr>';

		// Segunda cabecera (Elementos)
			$html .= '		<tr style="font-size: 10px;">';
			$html .= '			<td rowspan="2" style="border: solid; border-width: 0.5px; border-color: #000000; background-color: #00a2aa; color: #000000; text-align: center;  vertical-align: middle; width: 180px; margin-top: 5px;">';
			$html .= '				CÓDIGO<br>MUESTRA';
			$html .= '			</td>';

			$html .= '			<td rowspan="2" style="border: solid; border-width: 0.5px; border-color: #000000; background-color: #00a2aa; color: #000000; text-align: center; vertical-align: middle; width: 80px; margin-top: 5px;">';
			$html .= '				CÓDIGO<br>INTERNO';
			$html .= '			</td>';

			$m = 0;

			while ($m < count($arr_metodos)){
				$arr_detalle = $arr_metodos[$m];
				$arr_detalle = explode('|', $arr_detalle);

				$html .= '			<td '.(($arr_detalle[1] == 'au' || $arr_detalle[1] == 'ag') ? 'colspan="2"' : '').' style="border: solid; border-width: 0.5px; border-color: #000000; background-color: #00a2aa; color: #000000; text-align: center; vertical-align: middle;">';
				$html .= '				<label style="font-size: 12px;">'.strtoupper($arr_detalle[1]).'</label>';
				$html .= '			</td>';

				$m ++;
			}

			$html .= '		</tr>';

		// Tercera cabecera (Unidades)
			$html .= '		<tr style="font-size: 10px;">';

			$m = 0;

			while ($m < count($arr_metodos)){
				$arr_detalle = $arr_metodos[$m];
				$arr_detalle = explode('|', $arr_detalle);

				$html .= '			<td style="border: solid; border-width: 0.5px; border-color: #000000; background-color: #00a2aa; color: #000000; text-align: center; vertical-align: middle; width: 50px;">';
				$html .= '				<label style="font-size: 14px;">'.$arr_detalle[2].'</label>';
				$html .= '			</td>';

				if ($arr_detalle[1] == 'au' || $arr_detalle[1] == 'ag'){
					$html .= '			<td style="border: solid; border-width: 0.5px; border-color: #000000; background-color: #00a2aa; color: #000000; text-align: center; vertical-align: middle; width: 50px;">';
					$html .= '				<label style="font-size: 14px;">oz/tc</label>';
					$html .= '			</td>';
				}

				$m ++;
			}

			$html .= '		</tr>';

		// Resultados
			$html .= '		<tr style="font-size: 12px;">';
			$html .= '			<td style="border: solid; border-width: 0.5px; border-color: #000000; text-align: center; vertical-align: middle; width: 200px; margin-top: 5px;">';
			$html .= '				'.strtoupper($nom_muestra);
			$html .= '			</td>';

			$html .= '			<td style="border: solid; border-width: 0.5px; border-color: #000000; text-align: center; vertical-align: middle; width: 100px; margin-top: 5px;">';
			$html .= '				'.strtoupper($cod_interno);
			$html .= '			</td>';

			$m = 0;

			while ($m < count($arr_metodos)){
				$arr_detalle = $arr_metodos[$m];
				$arr_detalle = explode('|', $arr_detalle);

				$html .= '			<td style="border: solid; border-width: 0.5px; border-color: #000000; text-align: center; vertical-align: middle; width: 50px;">';
				$html .= '				'.number_format($arr_detalle[3], $arr_detalle[5], '.', ',').'</label>';
				$html .= '			</td>';

				if ($arr_detalle[1] == 'au' || $arr_detalle[1] == 'ag'){
					$html .= '			<td style="border: solid; border-width: 0.5px; border-color: #000000; text-align: center; vertical-align: middle; width: 50px;">';
					$html .= '				'.number_format($arr_detalle[3] / $factor, $arr_detalle[5], '.', ',').'</label>';
					$html .= '			</td>';
				}

				$m ++;
			}

			$html .= '		</tr>';
			$html .= '	</table>';
			$html .= '</div>';

	// Setea el html para la tabla de Métodos
		$html .= '<div style="width: 125%; margin-top: 30px; height: 190px; max-height: 240px;">';
		$html .= '	<table class="table" style="border-spacing: 0px;">';
		$html .= '		<tr style="font-size: 12px;">';
		$html .= '			<td style="vertical-align: middle; width: 120px; font-weight: bold; text-align: center;">';
		$html .= '				Métodos';
		$html .= '			</td>';
		$html .= '		</tr>';

		$m = 0;
		$metodo_in = '';

		while ($m < count($arr_metodos)){
			$arr_detalle = $arr_metodos[$m];
			$arr_detalle = explode('|', $arr_detalle);

			if (strpos($metodo_in, $arr_detalle[0]) === false){
				$html .= '		<tr style="font-size: 10px;">';
				$html .= '			<td style="text-align: center; vertical-align: middle; height: 15px;">';
				$html .= '				'.$arr_detalle[0].':';
				$html .= '			</td>';

				$html .= '			<td style="vertical-align: middle; height: 15px; font-size: 11px;">';
				$html .= '				'.$arr_detalle[4];
				$html .= '			</td>';
				$html .= '		</tr>';

				$metodo_in .= $arr_detalle[0];
			}

			$m ++;
		}

		$html .= '	</table>';
		$html .= '</div>';

	// Setea las firmas
		$html .= '<div style="width: 125%; height: 60px; max-height: 60px;">';
		$html .= '	<table class="table" style="border-spacing: 0px; width: 100%">';
		$html .= '		<tr style="font-size: 14px;">';
		$html .= '			<td style="vertical-align: middle; width: 35%; text-align: center;">';
		$html .= '				Emitido en Trujillo, Perú';
		$html .= '			</td>';

		$html .= '			<td style="vertical-align: middle; width: 35%; text-align: center;">';

		$fecha = explode('-', $fecha_cierre);

		$html .= '				'.get_nombre_dia($fecha_cierre).', '.$fecha[2].' de '.strtolower(get_nombre_mes($fecha[1])). ' del '.$fecha[0];
		$html .= '			</td>';

		$html .= '			<td style="vertical-align: middle; width: 60%;">';
		$html .= '				<img src="'.$ruta_images.'firmas/rvillegas.png" style="width: 120px; margin-left: 50px;"/>';
		$html .= '			</td>';
		$html .= '		</tr>';
		$html .= '	</table>';
		$html .= '</div>';

	// Setea Pie de Página
		$html .= '<div style="width: 100%; margin-top: 50px; height: 220px; max-height: 220px; border: solid; border-width: 2px; border-color: #00a2aa; padding-top: 10px; padding-left: 15px; padding-right: 15px;">';
		$html .= '	<div class="row">';
		$html .= '		<label style="font-weight: bold;">OBSERVACIONES</label>';
		$html .= '	</div>';

		$html .= '	<div class="row" style="margin-top: 5px;">';
		$html .= '		<label style="font-size: 12px;">No se debe reproducir el informe de ensayo, excepto en su totalidad, sin la aprobación escrita de CORPORACION CAVE S.A.C </label>';
		$html .= '	</div>';

		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 12px;">El informe de ensayo es un documento oficial de interés público, su adulteración o uso indebido constituye delito contra la fe publica y se regula por las disposiciones penales y civiles en la materia.</label>';
		$html .= '	</div>';

		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 12px;">El informe de ensayo solo es validado para las muestras referidas en el presente informe, no pudiendo extenderse los resultados del informe a ninguna otra unidad o lote que no haya sido analizado.</label>';
		$html .= '	</div>';

		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 12px;">Estos resultados no deben ser utilizados como una certificación de conformidad con normas del producto o como un certificado del sistema de calidad de la entidad que lo produce.</label>';
		$html .= '	</div>';

		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 12px;">El laboratorio no es responsable cuando la información proporcionada por el cliente pueda afectar la validez de los resultados.</label>';
		$html .= '	</div>';

		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 12px;">Los resultados de ensayo del presente informe se aplican a la muestra que se recibió.</label>';
		$html .= '	</div>';

		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 12px;">Los servicios ofrecidos son conforme a nuestros términos y condiciones.</label>';
		$html .= '	</div>';

		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 12px;">Las muestras podrán ser retiradas por los interesados transcurridos los 20 días calendarios, a partir de la fecha de recepción, caso contrario de procederá a desecharlas.</label>';
		$html .= '	</div>';
		$html .= '</div>';

		$html .= '<div style="width: 100%; padding-left: 15px; padding-right: 15px; margin-top: 20px; text-align: center;">';
		$html .= '	<div class="row">';
		$html .= '		<label style="font-size: 11px;">Laboratorio: Trujillo lote 3C MZ C 12 Reactivación 2007 primera fase etapa Vigésimo Octava Parque Industrial la Esperanza</label>';
		$html .= '	</div>';

		$html .= '	<div class="row" style="margin-top: 2px;">';
		$html .= '		<label style="font-size: 11px;">Teléfonos: 991855179</label>';
		$html .= '	</div>';

		$html .= '	<div class="row" style="margin-top: 2px;">';
		$html .= '		<label style="font-size: 11px;">Correo: aadministracion@cclaboratorio.com/recepcion1@corporacioncoppercave.com</label>';
		$html .= '	</div>';

		$html .= '	<div class="row" style="margin-top: 5px; text-align: left;">';
		$html .= '		<label style="font-size: 11px;">F-016-Ver 00</label>';
		$html .= '	</div>';
		$html .= '<div>';
// echo '</br>';
// echo '$arr_metodos: '.$arr_metodos;
// return;
	// Cierra html
    $html .= '		</body>
							</html>';
// echo '$html: '.$html;
// return;
	$options = new Options();
  $options->set('isRemoteEnabled', TRUE);
  $document = new Dompdf($options);

	$document -> loadHtml($html, 'UTF-8');
	$document -> setPaper('A4', 'portrait');
	$document -> render();
	$document -> stream("Informe de Ensayos - ".$num_informeensayos, array('Attachment' => 0));

?>
