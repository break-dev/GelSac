<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Resumen de Balanza.xls');
	header('Pragma: no-cache');
	header('Expires: 0');

	include('../cnx/cnx.php');
  include('../global/variables.php');

	// Obtener Peso Seco en función a la Humedad
		function f_GetPesoSeco($tmh, $h2o){
			if (strlen(trim($tmh)) > 0 && strlen(trim($h2o)) > 0){
				// Obtiene factor de humedad
					$factor = (100 - $h2o) / 100;

				// Obtiene Peso Seco
					$peso_seco = $tmh * $factor;
			}
			else{
				$peso_seco = '';
			}

			return $peso_seco;
		}

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
	</head>

	<body class="nav-md footer_fixed">
		<div class="container body">
			<div class="main_container">
				<?php

					// Recuperando las variables enviadas
						$fecha_inicio = $_GET["fecha_inicio"];
						$fecha_fin = $_GET["fecha_fin"];
						$filtro_condicioningreso = $_GET["filtro_condicioningreso"];
						$filtro_transportista = $_GET["filtro_transportista"];
						$filtro_transportista = (($filtro_transportista == 'null') ? '' : $filtro_transportista);
						$filtro_placa = $_GET["filtro_placa"];
						$filtro_placa = (($filtro_placa == 'null') ? '' : $filtro_placa);
						$filtro_lote = $_GET["filtro_lote"];
						$filtro_lote = (($filtro_lote == 'null') ? '' : $filtro_lote);

				?>

					<font size = "3"><b>
						RESUMEN DE BALANZA
					</b></font>

					<br/>

					<font size = "2">
						Fecha de inicio: <?php echo $fecha_inicio; ?>
					</font>
					<br/>
					<font size = "2">
						Fecha de fin: <?php echo $fecha_fin; ?>
					</font>

					<br/>
					<br/>

					<table class="table table-bordered table-hover">
	        	<thead>
	        		<tr style="font-size: 12px;">
	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				N°
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
	        				Fecha Hora Creación Lote
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
	        				Fecha Hora Ingreso
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
	        				Condición
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				N° Placa 1
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				N° Placa 2 (Remolque)
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Emp. de Transporte
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
	        				Tipo Vehículo
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Info. Conductor
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				Tipo Carga
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Zona Origen
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Proveedor Minero
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Encargado Muestra
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Producto
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
	        				Tipo Mineral
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
	        				Observación
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 135px;">
	        				Lote
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Ticket
	        			</th>

	        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Información de Pesos (Kg)
	        			</th>

	        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
	        				Información Humedad
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px; border-top-right-radius: 15px;">
	        				Peso Seco<br>TMS
	        			</th>
	        		</tr>

	        		<tr style="font-size: 12px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
	        				DNI / RUC
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
	        				Razón Social
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Licencia
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
	        				Nombres
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
	        				Inicial
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
	        				Final
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Bruto
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Tara
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
	        				Neto
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
	        				%<br>Humedad
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
	        				Informe
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
	        				Evidencia
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody id="tbl_detalle">

							<?php
								$d = 1;
				        $id_lote = 0;
				        $html = '';

				        // Query para obtener el tipo: "Recepción de Mineral"
				          if ($filtro_condicioningreso == 99 || $filtro_condicioningreso == 1){
				            $q_balanza = "SELECT DISTINCT I.id_controlIngresoVehiculo,
				                                 MD5(L.id_CatalogoLotes) AS ID_MD5,
				                                 CONCAT(I.dFechaIngreso, ' ', I.dhoraingresoPlanta) AS FECHAHORA_REGISTRO,
				                                 I.id_tipoingresounidad,
				                                 IU.descripcion AS CLIENTE_CONDICION,
				                                 I.placa,
				                                 I.placa2,
				                                 I.id_transportista,
				                                 T.documento AS documento,
				                                 T.razon_social AS TRANSPORTISTA,
				                                 I.id_tipovehiculo,
				                                 TV.descripcion AS TIPO_VEHICULO,
				                                 I.id_choferes,
				                                 CD.dni_licencia,
				                                 CD.nombres AS CONDUCTOR,
				                                 L.balanza_id_tipocarga,
				                                 TC.descripcion AS TIPO_CARGA,
				                                 L.balanza_id_zonaorigen,
				                                 ZO.descripcion AS ZONA_ORIGEN,
				                                 I.cNotas,
				                                 L.id_CatalogoLotes, 
				                                 L.ccod_Lote,
				                                 MD5(L.ccod_Lote) AS MD5_LOTE,
				                                 L.dFechaCreacion AS FECHAHORA_CREACIONLOTE,
				                                 L.nNro_ticketsBalanza,
				                                 L.item_ticketbalanza,
				                                 L.nPeso_InicialBalanza,
				                                 L.tFechaInicialBalanza,
				                                 L.tHoraInicialBalanza,
				                                 L.pesoinicial_observacion,
				                                 U_I.usu_usuario AS USUARIO_INICIO,
				                                 L.nPeso_FinalBalanza,
				                                 L.dFechaFinalBalanza,
				                                 L.tHoraFinalBalanza,
				                                 L.pesofinal_observacion,
				                                 L.id_UsuarioModificacion,
				                                 U_F.usu_usuario AS USUARIO_FIN,
				                                 L.balanza_id_proveedorminero,
				                                 CONCAT(PM.documento, ' - ', UPPER(PM.razon_social)) AS PROVEEDOR_MINERO,
				                                 L.balanza_id_encargadomuestra,
				                                 /*UPPER(EM.nombres) AS ENCARGADO_MUESTRA,*/
				                                 EM.codigo AS ENCARGADO_MUESTRA,
				                                 UPPER(EM.nombres) AS ENCARGADO_MUESTRA_NOMBRES,
				                                 L.balanza_id_producto,
				                                 UPPER(P.descripcion) AS PRODUCTO,
				                                 L.balanza_id_tipomineral,
				                                 UPPER(TM.descripcion) AS TIPO_MINERAL,
				                                 UPPER(L.balanza_observacion) AS LOTE_OBSERVACION,
				                                 HC.cierre_prom,
				                                 HC.Id AS ID_CABECERAHUMEDAD,
				                                 1 AS TIPO_CONDICION,
				                                 0 AS ID_PLANTA,
				                                 IFNULL(L.balanza_id_planta, 0) AS balanza_id_planta,
				                                 IFNULL(PL.nombre_comercial, PL.descripcion) AS PLANTA_INGRESO
				                            FROM controlingresovehiculo I
				                                 INNER JOIN tbconfig_tipoingresounidades IU ON I.id_tipoingresounidad = IU.Id
				                                 LEFT JOIN tb_clientes T ON I.id_transportista = T.Id
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
				                                 LEFT JOIN analisislq_humedad_cabecera HC ON L.ccod_Lote = HC.cod_interno
				                                   AND HC.is_reanalisis = 0
				                                 LEFT JOIN despachos_primertramo_validaciondatos VD ON L.ccod_Lote = VD.lote_cod_lote
				                                 LEFT JOIN tbconfig_plantas PL ON L.balanza_id_planta = PL.Id
				                           WHERE I.id_tipoingresounidad = 1";

				            if (strlen($arr_lotes) > 0){
				              $q_balanza .= "   AND L.ccod_Lote IN (".$arr_lotes.")";
				            }
				            else{
				              $q_balanza .= "   AND DATE(L.dFechaCreacion) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

				              if (strlen($filtro_transportista) > 0){
				                $q_balanza .= "   AND I.id_transportista = ".$filtro_transportista;
				              }

				              if (strlen($filtro_placa) > 0){
				                $q_balanza .= "   AND I.placa LIKE '%".$filtro_placa."%'";
				              }

				              // if (strlen($filtro_planta) > 0){
				              //  $q_balanza .= "   AND VD.despacho_id_destinoplanta = ".$filtro_planta;
				              // }
				            }

				            $q_balanza .= " UNION ";
				          }

				        // Query para obtener el tipo: "Despacho de Mineral"
				          if ($filtro_condicioningreso == 99 || $filtro_condicioningreso == 2 || $filtro_condicioningreso == 999){
				            $q_balanza .= "SELECT I.id_controlIngresoVehiculo,
				                                  MD5(L.Id) AS ID_MD5,
				                                  CONCAT(I.dFechaIngreso, ' ', I.dhoraingresoPlanta) AS FECHAHORA_REGISTRO,
				                                  I.id_tipoingresounidad,
				                                  IU.descripcion AS CLIENTE_CONDICION,
				                                  I.placa,
				                                  I.placa2,
				                                  I.id_transportista,
				                                  ET.documento AS documento,
				                                  ET.razon_social AS TRANSPORTISTA,
				                                  I.id_tipovehiculo,
				                                  TV.descripcion AS TIPO_VEHICULO,
				                                  I.id_choferes,
				                                  CD.dni_licencia,
				                                  CD.nombres AS CONDUCTOR,
				                                  L.id_tipocarga AS balanza_id_tipocarga,
				                                  TC.descripcion AS TIPO_CARGA,
				                                  I.id_zonaorigen AS balanza_id_zonaorigen,
				                                  ZO.descripcion AS ZONA_ORIGEN,
				                                  I.cNotas,
				                                  L.Id AS id_CatalogoLotes,
				                                  CONCAT(L.cod_lote, '|', IFNULL(L.num_parte, '')) AS ccod_Lote,
				                                  '' AS MD5_LOTE,
				                                  '' AS FECHAHORA_CREACIONLOTE,
				                                  L.num_ticketbalanza AS nNro_ticketsBalanza,
				                                  '' AS item_ticketbalanza,
				                                  L.peso_tara AS nPeso_InicialBalanza,
				                                  SUBSTRING(L.peso_tara_fechahoraregistro, 1, 10) AS tFechaInicialBalanza,
				                                  SUBSTRING(L.peso_tara_fechahoraregistro, 12, 5) AS tHoraInicialBalanza,
				                                  '' AS pesoinicial_observacion,
				                                  L.peso_tara_usuarioregistro AS USUARIO_INICIO,
				                                  L.peso_bruto AS nPeso_FinalBalanza,
				                                  SUBSTRING(L.peso_bruto_fechahoraregistro, 1, 10) AS dFechaFinalBalanza,
				                                  SUBSTRING(L.peso_bruto_fechahoraregistro, 12, 5) AS tHoraFinalBalanza,
				                                  '' AS pesofinal_observacion,
				                                  '' AS id_UsuarioModificacion,
				                                  L.peso_bruto_usuarioregistro AS USUARIO_FIN,
				                                  LT.balanza_id_proveedorminero,
				                                  CONCAT(PM.documento, ' - ', UPPER(PM.razon_social)) AS PROVEEDOR_MINERO,
				                                  LT.balanza_id_encargadomuestra,
				                                  /*UPPER(EM.nombres) AS ENCARGADO_MUESTRA,*/
				                                  EM.codigo AS ENCARGADO_MUESTRA,
				                                  UPPER(EM.nombres) AS ENCARGADO_MUESTRA_NOMBRES,
				                                  LT.balanza_id_producto,
				                                  UPPER(PR.descripcion) AS PRODUCTO,
				                                  LT.balanza_id_tipomineral,
				                                  UPPER(TM.descripcion) AS TIPO_MINERAL,
				                                  L.observacion AS LOTE_OBSERVACION,
				                                  HC.cierre_prom,
				                                  HC.Id AS ID_CABECERAHUMEDAD,
				                                  2 AS TIPO_CONDICION,
				                                  P.id_planta AS ID_PLANTA,
				                                  0 AS balanza_id_planta,
				                                  '' AS PLANTA_INGRESO
				                             FROM controlingresovehiculo I
				                                  INNER JOIN tbconfig_tipoingresounidades IU ON I.id_tipoingresounidad = IU.Id
				                                  INNER JOIN despachos_segundotramo_distribucion_unidades U ON DATE(I.dFechaIngreso) >= DATE(U.fecha_ingresoplanta)
				                                  INNER JOIN despachos_segundotramo_programacion P ON U.id_programacion = P.Id
				                                    AND DATE(I.dFechaIngreso) <= DATE(P.fechaestimada_despacho)
				                                  INNER JOIN transporte T ON U.id_unidad = T.id_transporte
				                                    AND I.placa = T.cplaca
				                                  INNER JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
				                                  INNER JOIN despachos_segundotramo_distribucion_lotes L ON U.Id = L.id_distribucionunidad
				                                  INNER JOIN tbconfig_tipocarga TC ON L.id_tipocarga = TC.Id
				                                  LEFT JOIN tbconfig_zonaorigen ZO ON I.id_zonaorigen = ZO.Id
				                                  LEFT JOIN tb_clientes ET ON T.id_Transportista = ET.Id
				                                  LEFT JOIN tbconfig_conductores CD ON I.id_choferes = CD.Id
				                                  LEFT JOIN catalogolotes LT ON L.cod_lote = LT.ccod_Lote
				                                  LEFT JOIN tb_clientes PM ON LT.balanza_id_proveedorminero = PM.Id
				                                  LEFT JOIN tbconfig_encargadosmuestra EM ON LT.balanza_id_encargadomuestra = EM.Id
				                                  LEFT JOIN tbconfig_producto PR ON LT.balanza_id_producto = PR.Id
				                                  LEFT JOIN tbconfig_tipomineral TM ON LT.balanza_id_tipomineral = TM.Id
				                                  LEFT JOIN analisislq_humedad_cabecera HC ON LT.ccod_Lote = HC.cod_interno
				                                    AND HC.is_reanalisis = 0
				                            WHERE 1 = 1";

				            if ($filtro_condicioningreso == 999){
				              $q_balanza .= "   AND P.id_planta = 16";
				            }
				            else{
				              $q_balanza .= "   AND I.id_tipoingresounidad = 2";
				            }

				            if (strlen($arr_lotes) > 0){
				              $q_balanza .= "   AND L.cod_lote IN (".$arr_lotes.")";
				            }
				            else{
				              $q_balanza .= "   AND DATE(L.peso_tara_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";
				              $q_balanza .= "   AND DATE(I.dFechaIngreso) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

				              if (strlen($filtro_transportista) > 0){
				                $q_balanza .= "   AND I.id_transportista = ".$filtro_transportista;
				              }

				              if (strlen($filtro_placa) > 0){
				                $q_balanza .= "   AND I.placa LIKE '%".$filtro_placa."%'";
				              }

				              if (strlen($filtro_planta) > 0){
				                $q_balanza .= "   AND P.id_planta = ".$filtro_planta;
				              }
				            }

				            $q_balanza .= " UNION ";
				          }

				        // Query para obtener el tipo: "Otros"
				          if ($filtro_condicioningreso == 99 || $filtro_condicioningreso == 3){
				            $q_balanza .= "SELECT I.id_controlIngresoVehiculo,
				                                 MD5(L.id_CatalogoLotes) AS ID_MD5,
				                                 CONCAT(I.dFechaIngreso, ' ', I.dhoraingresoPlanta) AS FECHAHORA_REGISTRO,
				                                 I.id_tipoingresounidad,
				                                 IU.descripcion AS CLIENTE_CONDICION,
				                                 I.placa,
				                                 I.placa2,
				                                 I.id_transportista,
				                                 T.documento,
				                                 T.razon_social AS TRANSPORTISTA,
				                                 I.id_tipovehiculo,
				                                 TV.descripcion AS TIPO_VEHICULO,
				                                 I.id_choferes,
				                                 CD.dni_licencia,
				                                 CD.nombres AS CONDUCTOR,
				                                 I.id_tipocarga,
				                                 TC.descripcion AS TIPO_CARGA,
				                                 I.id_zonaorigen,
				                                 ZO.descripcion AS ZONA_ORIGEN,
				                                 I.cNotas AS OBSERVACION,
				                                 L.id_CatalogoLotes, 
				                                 L.ccod_Lote,
				                                 '' AS MD5_LOTE,
				                                 '' AS dFechaCreacion,
				                                 L.nNro_ticketsBalanza,
				                                 L.item_ticketbalanza,
				                                 L.nPeso_InicialBalanza,
				                                 L.tFechaInicialBalanza,
				                                 L.tHoraInicialBalanza,
				                                 L.pesoinicial_observacion,
				                                 U_I.usu_usuario AS USUARIO_INICIO,
				                                 L.nPeso_FinalBalanza,
				                                 L.dFechaFinalBalanza,
				                                 L.tHoraFinalBalanza,
				                                 L.pesofinal_observacion,
				                                 L.id_UsuarioModificacion,
				                                 U_F.usu_usuario AS USUARIO_FIN,
				                                 L.balanza_id_proveedorminero,
				                                 UPPER(PM.razon_social) AS PROVEEDOR_MINERO,
				                                 I.id_encargadomuestra,
				                                 /*UPPER(EM.nombres) AS ENCARGADO_MUESTRA,*/
				                                 EM.codigo AS ENCARGADO_MUESTRA,
				                                 UPPER(EM.nombres) AS ENCARGADO_MUESTRA_NOMBRES,
				                                 I.id_producto,
				                                 UPPER(P.descripcion) AS PRODUCTO,
				                                 I.id_tipomineral,
				                                 UPPER(TM.descripcion) AS TIPO_MINERAL,
				                                 UPPER(L.balanza_observacion) AS LOTE_OBSERVACION,
				                                 NULL AS cierre_prom,
				                                 0 AS ID_CABECERAHUMEDAD,
				                                 3 AS TIPO_CONDICION,
				                                 0 AS ID_PLANTA,
				                                 0 AS balanza_id_planta,
				                                 '' AS PLANTA_INGRESO
				                            FROM controlingresovehiculo I
				                                 INNER JOIN tbconfig_tipoingresounidades IU ON I.id_tipoingresounidad = IU.Id
				                                 LEFT JOIN tb_clientes T ON I.id_transportista = T.Id
				                                 INNER JOIN tbconfig_tipovehiculo TV ON I.id_tipovehiculo = TV.Id
				                                 INNER JOIN tbconfig_conductores CD ON I.id_choferes = CD.Id
				                                 LEFT JOIN catalogolotes L ON I.id_controlIngresoVehiculo = L.id_controlIngresoVehiculo
				                                 LEFT JOIN tbconfig_tipocarga TC ON I.id_tipocarga = TC.Id
				                                 LEFT JOIN tbconfig_zonaorigen ZO ON I.id_zonaorigen = ZO.Id
				                                 LEFT JOIN tb_usuario U_I ON L.id_UsuarioCreacion = U_I.Id
				                                 LEFT JOIN tb_usuario U_F ON L.id_UsuarioModificacion = U_F.Id
				                                 LEFT JOIN tb_clientes PM ON I.id_proveedorminero = PM.Id
				                                 LEFT JOIN tbconfig_encargadosmuestra EM ON I.id_encargadomuestra = EM.Id
				                                 LEFT JOIN tbconfig_producto P ON I.id_producto = P.Id
				                                 LEFT JOIN tbconfig_tipomineral TM ON I.id_tipomineral = TM.Id
				                           WHERE I.id_tipoingresounidad = 3";

				            if (strlen($arr_lotes) > 0){
				              $q_balanza .= "   AND L.ccod_Lote IN (".$arr_lotes.")";
				            }
				            else{
				              $q_balanza .= "   AND DATE(I.dFechaIngreso) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

				              if (strlen($filtro_transportista) > 0){
				                $q_balanza .= "   AND I.id_transportista = ".$filtro_transportista;
				              }

				              if (strlen($filtro_placa) > 0){
				                $q_balanza .= "   AND I.placa LIKE '%".$filtro_placa."%'";
				              }
				            }

				            $q_balanza .= " ORDER BY ccod_Lote";

				            $q_balanza .= " UNION ";
				          }

				        $q_balanza = substr($q_balanza, 0, -7);

								if ($res_balanza = mysqli_query($enlace, $q_balanza)){
									if (mysqli_num_rows($res_balanza) > 0) {
										$estado = 1;

										while($row_balanza = mysqli_fetch_array($res_balanza)){
											$html .= '<tr style="font-size: 14px;">';

				              // Inicia con la carga de datos
				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    '.$d;
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    '.$row_balanza["FECHAHORA_CREACIONLOTE"];
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    '.$row_balanza["FECHAHORA_REGISTRO"].'<br><i>'.$row_balanza["usuario_registro"].'</i>';
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_1_'.$row_balanza["id_controlIngresoVehiculo"].'">'.(($row_balanza["ID_PLANTA"] == 16) ? 'Mineral Retirado' : $row_balanza["CLIENTE_CONDICION"]).'</label>';
				                // $html .= '    <i id="event_click_1_'.$row_balanza["id_controlIngresoVehiculo"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_controlIngresoVehiculo"].', 1, '.$row_balanza["id_tipoingresounidad"].')"></i>';
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #f8da62;">';
				                $html .= '    <label id="lbl_text_2_'.$row_balanza["id_controlIngresoVehiculo"].'">'.$row_balanza["placa"].'</label>';
				                $html .= '    <i id="event_click_2_'.$row_balanza["id_controlIngresoVehiculo"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_controlIngresoVehiculo"].', 2, '."'".$row_balanza["placa"]."'".')"></i>';
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_3_'.$row_balanza["id_controlIngresoVehiculo"].'">'.$row_balanza["placa2"].'</label>';
				                $html .= '    <i id="event_click_3_'.$row_balanza["id_controlIngresoVehiculo"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_controlIngresoVehiculo"].', 3, '."'".$row_balanza["placa2"]."'".')"></i>';
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_4_'.$row_balanza["id_controlIngresoVehiculo"].'">'.$row_balanza["documento"].'</label>';
				                $html .= '    <i id="event_click_4_'.$row_balanza["id_controlIngresoVehiculo"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_controlIngresoVehiculo"].', 4, '."'".$row_balanza["id_transportista"]."'".')"></i>';
				                $html .= '  </td>';

				                $html .= '  <td id="td_text_4_'.$row_balanza["id_controlIngresoVehiculo"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    '.$row_balanza["TRANSPORTISTA"];
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_5_'.$row_balanza["id_controlIngresoVehiculo"].'">'.$row_balanza["TIPO_VEHICULO"].'</label>';
				                $html .= '    <i id="event_click_5_'.$row_balanza["id_controlIngresoVehiculo"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_controlIngresoVehiculo"].', 5, '."'".$row_balanza["id_tipovehiculo"]."'".')"></i>';
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_6_'.$row_balanza["id_controlIngresoVehiculo"].'">'.$row_balanza["dni_licencia"].'</label>';
				                $html .= '    <i id="event_click_6_'.$row_balanza["id_controlIngresoVehiculo"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_controlIngresoVehiculo"].', 6, '."'".$row_balanza["id_choferes"]."'".')"></i>';
				                $html .= '  </td>';

				                $html .= '  <td id="td_text_6_'.$row_balanza["id_controlIngresoVehiculo"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    '.$row_balanza["CONDUCTOR"];
				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_7_'.$row_balanza["id_CatalogoLotes"].'">'.((strlen($row_balanza["TIPO_CARGA"]) == 0) ? '---' : $row_balanza["TIPO_CARGA"]).'</label>';

				                if ($row_balanza["id_tipoingresounidad"] == 1){
				                  $html .= '    <i id="event_click_7_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 7, '."'".$row_balanza["balanza_id_tipocarga"]."'".')"></i>';
				                  $html .= '    <input id="id_clientecondicion_'.$row_balanza["id_CatalogoLotes"].'" type="hidden" value="'.$row_balanza["id_tipoingresounidad"].'">';
				                }

				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_8_'.$row_balanza["id_CatalogoLotes"].'">'.((strlen($row_balanza["ZONA_ORIGEN"]) == 0) ? '---' : $row_balanza["ZONA_ORIGEN"]).'</label>';

				                if ($row_balanza["id_tipoingresounidad"] == 1){
				                  $html .= '    <i id="event_click_8_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 8, '."'".$row_balanza["balanza_id_zonaorigen"]."'".')"></i>';
				                }

				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_9_'.$row_balanza["id_CatalogoLotes"].'">'.((strlen($row_balanza["PROVEEDOR_MINERO"]) == 0) ? '---' : $row_balanza["PROVEEDOR_MINERO"]).'</label>';

				                if ($row_balanza["id_tipoingresounidad"] == 1){
				                  $html .= '    <i id="event_click_9_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 9, '."'".$row_balanza["balanza_id_proveedorminero"]."'".')"></i>';
				                }

				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_10_'.$row_balanza["id_CatalogoLotes"].'">'.((strlen($row_balanza["ENCARGADO_MUESTRA"]) == 0) ? '---' : $row_balanza["ENCARGADO_MUESTRA"].(($row_balanza["balanza_id_proveedorminero"] == 73) ? ' - '.$row_balanza["ENCARGADO_MUESTRA_NOMBRES"] : '')).'</label>';

				                if ($row_balanza["id_tipoingresounidad"] == 1){
				                  $html .= '    <i id="event_click_10_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 10, '."'".$row_balanza["balanza_id_encargadomuestra"]."'".')"></i>';
				                }

				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_11_'.$row_balanza["id_CatalogoLotes"].'">'.((strlen($row_balanza["PRODUCTO"]) == 0) ? '---' : $row_balanza["PRODUCTO"]).'</label>';

				                if ($row_balanza["id_tipoingresounidad"] == 1){
				                  $html .= '    <i id="event_click_11_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 11, '."'".$row_balanza["balanza_id_producto"]."'".')"></i>';
				                }

				                $html .= '  </td>';

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
				                $html .= '    <label id="lbl_text_12_'.$row_balanza["id_CatalogoLotes"].'">'.((strlen($row_balanza["TIPO_MINERAL"]) == 0) ? '---' : $row_balanza["TIPO_MINERAL"]).'</label>';

				                if ($row_balanza["id_tipoingresounidad"] == 1){
				                  $html .= '    <i id="event_click_12_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 12, '."'".$row_balanza["balanza_id_tipomineral"]."'".')"></i>';
				                }

				                $html .= '  </td>';

				                $html .= '  <td id="td_text_15_'.$row_balanza["id_CatalogoLotes"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';

				                if ($row_balanza["ID_PLANTA"] == 15){
				                  $q_datos = "SELECT IFNULL(PD.cmh_codigoguias, '') AS CMH_CODIGOGUIAS,

				                                     (SELECT COUNT(DL_x.Id) AS _COUNT
				                                        FROM despachos_segundotramo_distribucion_lotes DL_x
				                                       WHERE DL_x.cod_lote = PD.cod_lote) AS TOTAL_PARTES

				                                FROM despachos_segundotramo_programacion_detalle PD
				                               WHERE PD.cod_lote = '".explode('|', $row_balanza["ccod_Lote"])[0]."'";

				                  if ($res_datos = mysqli_query($enlace, $q_datos)) {
				                    if (mysqli_num_rows($res_datos) > 0) {
				                      while ($row_datos = mysqli_fetch_array($res_datos)) {
				                        $cmh_codigoguias = $row_datos["CMH_CODIGOGUIAS"];
				                        $total_partes = $row_datos["TOTAL_PARTES"];

				                        $cmh_codigoguias = $cmh_codigoguias.(($total_partes > 1) ? ' ('.explode('|', $row_balanza["ccod_Lote"])[1].'/'.$total_partes.')' : '');

				                        $html .= '    '.$cmh_codigoguias;
				                      }
				                    }
				                  }
				                }
				                else{
				                  $html .= '    '.$row_balanza["LOTE_OBSERVACION"];
				                }

				                $html .= '    <i id="event_click_15_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 15, '."'".$row_balanza["LOTE_OBSERVACION"]."', ".$row_balanza["TIPO_CONDICION"].')"></i>';
				                $html .= '  </td>';

				                if (strlen($row_balanza["ccod_Lote"]) == 0){
				                  $html .= '  <td colspan="7" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; font-size: 12px; color: #E1523D; '.(($row_balanza["id_tipoingresounidad"] != 1) ? 'background-color: #D2D9D8; border-color: #ffffff;' : '').'">';

				                  if ($row_balanza["id_tipoingresounidad"] == 1){
				                    $html .= '    <i>Generación de Lotes Pendiente</i>';
				                  }

				                  $html .= '  </td>';
				                }
				                else{
				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #5FCDD9;">';

				                  if ($row_balanza["id_tipoingresounidad"] == 2){
				                    $cod_lote_x = explode('|', $row_balanza["ccod_Lote"]);

				                    $html .= '    '.$cod_lote_x[0].((strlen($cod_lote_x[1]) > 0) ? ' - ('.$cod_lote_x[1].')' : '');
				                  }
				                  else{
				                    $html .= '    '.$row_balanza["ccod_Lote"];
				                  }

				                  $html .= '  </td>';

				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
				                  $html .= '    '.$row_balanza["nNro_ticketsBalanza"].((strlen($row_balanza["item_ticketbalanza"]) == 0) ? '' : ' - '.$row_balanza["item_ticketbalanza"]);
				                  $html .= '  </td>';

				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

				                  if (strlen($row_balanza["tFechaInicialBalanza"]) > 0){
				                    if ($row_balanza["id_tipoingresounidad"] == 2){
				                      $html .= '    <label id="lbl_pesoinicial_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_InicialBalanza"] * 1000, 0, '.', ',').' | <label style="font-size: 12px; font-weight: 400;">'.$row_balanza["tFechaInicialBalanza"].' '.$row_balanza["tHoraInicialBalanza"].'</label></label>';
				                    }
				                    else{
				                      $html .= '    <label id="lbl_pesoinicial_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_InicialBalanza"], 0, '.', ',').' | <label style="font-size: 12px; font-weight: 400;">'.$row_balanza["tFechaInicialBalanza"].' '.$row_balanza["tHoraInicialBalanza"].'</label></label>';
				                    }
				                  }
				                  else{
				                    $html .= '    <label style="font-size: 12px; font-weight: 400; color: #dc3545;">';
				                    $html .= '      <i>Pendiente</i>';
				                    $html .= '    </label>';
				                  }
				                  
				                  $html .= '  </td>';

				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

				                  if (strlen($row_balanza["dFechaFinalBalanza"]) > 0){
				                    if ($row_balanza["id_tipoingresounidad"] == 2){
				                      $html .= '    <label id="lbl_pesofinal_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_FinalBalanza"] * 1000, 0, '.', ',').' | <label style="font-size: 12px; font-weight: 400;">'.$row_balanza["dFechaFinalBalanza"].' '.$row_balanza["tHoraFinalBalanza"].'</label></label>';
				                    }
				                    else{
				                      $html .= '    <label id="lbl_pesofinal_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_FinalBalanza"], 0, '.', ',').' | <label style="font-size: 12px; font-weight: 400;">'.$row_balanza["dFechaFinalBalanza"].' '.$row_balanza["tHoraFinalBalanza"].'</label></label>';
				                    }
				                  }
				                  else{
				                    $html .= '    <label style="font-size: 12px; font-weight: 400; color: #dc3545;">';
				                    $html .= '      <i>Pendiente</i>';
				                    $html .= '    </label>';
				                  }

				                  $html .= '  </td>';

				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #f0efe8">';

				                    if (strlen($row_balanza["tFechaInicialBalanza"]) > 0){
				                      if ($row_balanza["id_tipoingresounidad"] == 2){
				                        $html .= '    <label id="lbl_text_13_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_FinalBalanza"] * 1000, 0, '.', ',').'</label>';
				                      }
				                      else{
				                        $html .= '    <label id="lbl_text_13_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_InicialBalanza"], 0, '.', ',').'</label>';
				                      }

				                      if ($row_balanza["id_tipoingresounidad"] == 1){
				                        $html .= '    <i id="event_click_13_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 13, '."'".$row_balanza["nPeso_InicialBalanza"]."'".')"></i>';
				                      }

				                      if ($row_balanza["id_tipoingresounidad"] == 2){
				                        $html .= '    <i id="event_click_16_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 16, '.number_format($row_balanza["nPeso_FinalBalanza"] * 1000, 0, '.', '').')"></i>';
				                      }
				                    }

				                    $html .= '  </td>';

				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #f0efe8">';

				                    if (strlen($row_balanza["dFechaFinalBalanza"]) > 0){
				                      if ($row_balanza["id_tipoingresounidad"] == 2){
				                        $html .= '    <label id="lbl_text_14_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_InicialBalanza"] * 1000, 0, '.', ',').'</label>';
				                      }
				                      else{
				                        $html .= '    <label id="lbl_text_14_'.$row_balanza["id_CatalogoLotes"].'">'.number_format($row_balanza["nPeso_FinalBalanza"], 0, '.', ',').'</label>';
				                      }

				                      if ($row_balanza["id_tipoingresounidad"] == 1){
				                        $html .= '    <i id="event_click_14_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 14, '."'".$row_balanza["nPeso_FinalBalanza"]."'".')"></i>';
				                      }

				                      if ($row_balanza["id_tipoingresounidad"] == 2){
				                        $html .= '    <i id="event_click_17_'.$row_balanza["id_CatalogoLotes"].'" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_Edit('.$row_balanza["id_CatalogoLotes"].', 17, '.number_format($row_balanza["nPeso_InicialBalanza"] * 1000, 0, '.', '').')"></i>';
				                      }
				                    }

				                    $html .= '  </td>';

				                  $html .= '  <td id="td_neto_'.$row_balanza["id_CatalogoLotes"].'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; background-color: #f0efe8">';

				                  if ($row_balanza["id_tipoingresounidad"] == 2){
				                    $html .= '    '.number_format(abs($row_balanza["nPeso_FinalBalanza"] - $row_balanza["nPeso_InicialBalanza"]) * 1000, 0, '.', ',');
				                  }
				                  else{
				                    $html .= '    '.number_format($row_balanza["nPeso_InicialBalanza"] - $row_balanza["nPeso_FinalBalanza"], 0, '.', ',');
				                  }

				                  $html .= '  </td>';
				                }

				                // Información de Humedad
				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

				                  if ($row_balanza["id_tipoingresounidad"] == 1){
				                    if (strlen($row_balanza["cierre_prom"]) > 0){
				                      $html .= '    '.number_format($row_balanza["cierre_prom"], 2, '.', ',');
				                    }
				                    else{
				                      $html .= '    <label style="font-size: 13px;">';
				                      $html .= '      <i style="color: #FF5F5D;">Humedad Pendiente</i>';
				                      $html .= '    </label>';
				                    }
				                  }

				                  $html .= '  </td>';

				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

				                  if ($row_balanza["id_tipoingresounidad"] == 1){
				                    if (strlen($row_balanza["cierre_prom"]) > 0){
				                      $html .= '    <img src="'.$img_IE.'" class="rounded" style="width: 30px; cursor: pointer;" onclick="f_PrintInformeCliente('."'".$row_balanza["MD5_LOTE"]."'".')">';
				                    }
				                  }

				                  $html .= '  </td>';

				                  // Verifica si tiene Evidencia
				                    $tiene_imagen = 0;

				                    if (strlen($row_balanza["ID_CABECERAHUMEDAD"]) > 0){
				                      $q_imagen = "SELECT Id
				                                     FROM analisislq_humedad_uploadrecibos
				                                    WHERE id_cabecera = ".$row_balanza["ID_CABECERAHUMEDAD"];

				                      if ($res_imagen = mysqli_query($enlace, $q_imagen)) {
				                        if (mysqli_num_rows($res_imagen) > 0) {
				                          $tiene_imagen = 1;
				                        }
				                      }
				                    }

				                  $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

				                  if ($row_balanza["id_tipoingresounidad"] == 1){
				                    if (strlen($row_balanza["cierre_prom"]) > 0){
				                      if ($tiene_imagen == 1){
				                        $html .= '    <img src="'.$img_view.'" class="rounded" style="width: 30px; cursor: pointer;" onclick="f_ShowEvidencia('."'".$row_balanza["ID_CABECERAHUMEDAD"]."'".')">';
				                      }
				                    }
				                  }

				                  $html .= '  </td>';

				                // Obtiene Peso Seco
				                  $peso_seco = f_GetPesoSeco(($row_balanza["nPeso_InicialBalanza"] - $row_balanza["nPeso_FinalBalanza"]), $row_balanza["cierre_prom"]);

				                $html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';

				                if ($row_balanza["id_tipoingresounidad"] == 1){
				                  if (strlen($peso_seco) > 0){
				                    $html .= '      '.number_format($peso_seco, 0, '.', ',');;
				                  }
				                }

				                $html .= '  </td>';

				              $html .= '</tr>';

				              $d ++;
										}
									}
								}

				        echo $html;

							?>

						</tbody>
					</table>
			</div>
		</div>
	</div>
</html>