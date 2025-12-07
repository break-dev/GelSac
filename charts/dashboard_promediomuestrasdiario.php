<?php

	session_start();

	include('../cnx/cnx.php');

	// Permite obtener el nombre de cada mes
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

	// Recibiendo parámetros

	// Obtiene el periodo de Meses (hasta 13 meses)
		$xAxis = '';
		$arr_periodo = '';

		$q_periodos = "SELECT DISTINCT YEAR(C.fechahora_recepcion) AS ANHO,
													MONTH(C.fechahora_recepcion) AS MES,
													CONCAT(YEAR(C.fechahora_recepcion), '|', MONTH(C.fechahora_recepcion)) AS PERIODO
										 FROM recepcion_ensayos_detalle D
													INNER JOIN recepcion_ensayos_cabecera C ON D.id_cabecera = C.Id
										WHERE C.is_temporal = 0
										  AND C.is_recepcioncanceled = 0
									 ORDER BY 1 DESC, 2
									 LIMIT 13";

		if ($res_periodos = mysqli_query($enlace, $q_periodos)){
			if (mysqli_num_rows($res_periodos) > 0) {
				while($row_periodos = mysqli_fetch_array($res_periodos)){
					$xAxis .= "'".substr(nombre_meses($row_periodos["MES"]), 0, 3).'-'.substr($row_periodos["ANHO"], 2, 2)."', ";

					$arr_periodo .= $row_periodos["PERIODO"].';';
				}

				if (strlen($xAxis) > 0){
					$xAxis = substr($xAxis, 0, -2);

					$arr_periodo = substr($arr_periodo, 0, -1);
				}
			}
		}

	// Obtiene los DataSeries por cada periodo
		$p = 0;
		$anho = 0;
		$mes = 0;
		$data_series1 = '';
		$data_series2 = '';
		$data_series3 = '';
		$arr_periodos = explode(';', $arr_periodo);

		while($p < count($arr_periodos)){
			$anho = explode('|', $arr_periodos[$p])[0];
			$mes = explode('|', $arr_periodos[$p])[1];

			// Obtiene la data por sucursal
				if ($anho == 2023 && $mes == 1){
					$q_datos = "SELECT R.cod_sucursal,
														 COUNT(R.Id) AS _COUNT,

														 (SELECT COUNT(fecha_recepcion)
															  FROM (SELECT DISTINCT fecha_recepcion
																			  FROM recepcion_ensayos_muestras_old
																			 WHERE YEAR(fecha_recepcion) = ".$anho."
																			   AND MONTH(fecha_recepcion) = ".$mes.") AS DATOS) AS TOTAL_DIAS

												FROM recepcion_ensayos_muestras_old R
											 WHERE YEAR(R.fecha_recepcion) = ".$anho."
												 AND MONTH(R.fecha_recepcion) = ".$mes."
											GROUP BY R.cod_sucursal";
				}
				else{
					$q_datos = "SELECT C.cod_sucursal,
														 COUNT(D.Id) AS _COUNT,

														 (SELECT COUNT(RECEPCION) AS _COUNT
															  FROM (SELECT DISTINCT DATE(C.fechahora_recepcion) AS RECEPCION
															         FROM recepcion_ensayos_detalle D
															              INNER JOIN recepcion_ensayos_cabecera C ON D.id_cabecera = C.Id
															         WHERE C.is_temporal = 0
															           AND C.is_recepcioncanceled = 0
															           AND C.cod_sucursal IS NOT NULL
															           AND YEAR(C.fechahora_recepcion) = ".$anho."
										 										 AND MONTH(C.fechahora_recepcion) = ".$mes.") AS DATOS) AS TOTAL_DIAS

										  	FROM recepcion_ensayos_detalle D
														 INNER JOIN recepcion_ensayos_cabecera C ON D.id_cabecera = C.Id
												WHERE C.is_temporal = 0
													AND C.is_recepcioncanceled = 0
	                        AND C.cod_sucursal IS NOT NULL
										 			AND YEAR(C.fechahora_recepcion) = ".$anho."
										 			AND MONTH(C.fechahora_recepcion) = ".$mes."
										 	GROUP BY C.cod_sucursal";
				}

				if ($res_datos = mysqli_query($enlace, $q_datos)){
					if (mysqli_num_rows($res_datos) > 0) {
						while($row_datos = mysqli_fetch_array($res_datos)){
							if ($row_datos["cod_sucursal"] == 1){
								$data_series1 .= "{ value: ".round($row_datos["_COUNT"] / $row_datos["TOTAL_DIAS"]).",
																		itemStyle: {
													            color: '#0d6efd'
													          }
												          }, ";
							}

							if ($row_datos["cod_sucursal"] == 2){
								$data_series2 .= "{ value: ".round($row_datos["_COUNT"] / $row_datos["TOTAL_DIAS"]).",
																		itemStyle: {
													            color: '#ffc107'
													          }
												          }, ";
							}
						}
					}
				}

			// Obtiene la data de ambas sucursales
				$total = 0;

				if ($anho == 2023 && $mes == 1){
					$q_datos = "SELECT R.cod_sucursal,
														 COUNT(R.Id) AS _COUNT,

														 (SELECT COUNT(fecha_recepcion)
															  FROM (SELECT DISTINCT fecha_recepcion
																			  FROM recepcion_ensayos_muestras_old
																			 WHERE YEAR(fecha_recepcion) = ".$anho."
																			   AND MONTH(fecha_recepcion) = ".$mes.") AS DATOS) AS TOTAL_DIAS

												FROM recepcion_ensayos_muestras_old R
											 WHERE YEAR(R.fecha_recepcion) = ".$anho."
												 AND MONTH(R.fecha_recepcion) = ".$mes."
											GROUP BY R.cod_sucursal";
				}
				else{
					$q_datos = "SELECT C.cod_sucursal,
														 COUNT(D.Id) AS _COUNT,

														 (SELECT COUNT(RECEPCION) AS _COUNT
															  FROM (SELECT DISTINCT DATE(C.fechahora_recepcion) AS RECEPCION
															         FROM recepcion_ensayos_detalle D
															              INNER JOIN recepcion_ensayos_cabecera C ON D.id_cabecera = C.Id
															         WHERE C.is_temporal = 0
															           AND C.is_recepcioncanceled = 0
															           AND C.cod_sucursal IS NOT NULL
															           AND YEAR(C.fechahora_recepcion) = ".$anho."
										 										 AND MONTH(C.fechahora_recepcion) = ".$mes.") AS DATOS) AS TOTAL_DIAS

										  	FROM recepcion_ensayos_detalle D
														 INNER JOIN recepcion_ensayos_cabecera C ON D.id_cabecera = C.Id
												WHERE C.is_temporal = 0
													AND C.is_recepcioncanceled = 0
	                        AND C.cod_sucursal IS NOT NULL
										 			AND YEAR(C.fechahora_recepcion) = ".$anho."
										 			AND MONTH(C.fechahora_recepcion) = ".$mes."
										 	GROUP BY C.cod_sucursal";
		 		}

				if ($res_datos = mysqli_query($enlace, $q_datos)){
					if (mysqli_num_rows($res_datos) > 0) {
						while($row_datos = mysqli_fetch_array($res_datos)){
							$total += round($row_datos["_COUNT"] / $row_datos["TOTAL_DIAS"]).', ';
						}
					}
				}

				$data_series3 .= $total.', ';

			$p ++;
		}

		if (strlen($data_series1) > 0){
			$data_series1 = substr($data_series1, 0, -2);
			$data_series2 = substr($data_series2, 0, -2);
			$data_series3 = substr($data_series3, 0, -2);
		}

	// Setea las Series
		$series = "{type: 'bar',
								label: {
												show: true,
												position: 'top',
												color: '#ffffff',
												distance: -2
											 },
								data: [".$data_series1."],
					     },

					     {type: 'bar',
					      label: {
					      				show: true,
					      				position: 'top',
												color: '#ffffff',
												distance: -2
					      			 },
								data: [".$data_series2."],
					     },

					     {type: 'line',
					     	lineStyle: {
			            					color: '#A276DB'
			          },
								label: {
												show: true,
												position: 'top',
												color: '#ffffff',
												distance: -2
											 },
								data: [".$data_series3."],
					     }";
?>

<!DOCTYPE html>
<html lang="es">
	<head>

	</head>

	<body>
		<div id="chart_3" class="d-flex" style="width: 100%; height: 310px;">

		</div>

		<script type="text/javascript">
      // Initialize the echarts instance based on the prepared dom
      	var myChart = echarts.init(document.getElementById('chart_3'));

    	// Specify the configuration items and data for the chart
	      var option = {grid:{top: '8%',
														left: '8%',
														right: '3%',
														bottom: '25%'},

							        xAxis: {	
													   	 type: 'category',
													   	 axisLabel: { interval: 0, rotate: 30 },
													     data: [<?php echo $xAxis; ?>]
													   },
													   yAxis: {
													     type: 'value'
													   },
													   series: [<?php echo $series; ?>]
      							 };

      // Display the chart using the configuration items and data just specified.
				myChart.setOption(option);
		</script>
	</body>
</html>