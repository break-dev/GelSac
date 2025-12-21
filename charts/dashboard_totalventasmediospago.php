<?php

	session_start();

	include('../cnx/cnx.php');

	// Recibiendo parámetros
		$fecha_inicio = $_GET["fecha_inicio"];
		$fecha_fin = $_GET["fecha_fin"];

	// Obtiene datos
		$html = '';

		$q_datos = "SELECT SUBSTRING(MEDIO_PAGO, 1, 5) AS MEDIO_PAGO,
											 ROUND(SUM(TOTAL), 2) AS TOTAL_VENTAS
								  FROM (SELECT Id,
															 MEDIO_PAGO,

															 CASE WHEN cliente_tienedscto = 1
															   THEN ROUND(TOTAL * ((100 - dscto_porcentaje) / 100), 2)
															 ELSE TOTAL END AS TOTAL
													FROM (SELECT DISTINCT
																			 C.Id,
																       C.cod_sucursal,
																       UPPER(IFNULL(MP.descripcion, 'POR PAGAR')) AS MEDIO_PAGO,

																			 (SELECT IFNULL(SUM(D_x.exceso), 0)
																					FROM recepcion_ensayos_detalle D_x
																		     WHERE D_x.id_cabecera = C.Id
																					 AND estado = 'A')
																		            
																				+

																				CASE WHEN C.tiene_recojo = 1 THEN 10 ELSE 0 END
																		        
																		    +

																				(SELECT SUM(EA.total)
																					 FROM recepcion_ensayos_analisis EA
																					WHERE EA.id_detalle IN (SELECT D_x.Id
																																		FROM recepcion_ensayos_detalle D_x
																																	 WHERE D_x.id_cabecera = C.Id)) AS TOTAL,
																		        
																		    C.cliente_tienedscto,
																				C.dscto_porcentaje
																	 FROM recepcion_ensayos_cabecera C
																				INNER JOIN recepcion_ensayos_detalle D ON C.Id = D.id_cabecera
																				LEFT JOIN recepcion_instruccion I ON C.Id = I.id_cabecera
																				LEFT JOIN tbconfig_mediospago MP ON I.cod_mediopago = MP.Id
																			WHERE C.is_temporal = 0
																				AND C.is_recepcioncanceled = 0";

		if (strlen($filtro_sucursal) > 0){
			$q_datos .= "   AND C.cod_sucursal = ".$filtro_sucursal;
		}

		if (strlen(trim($filtro_mediospago)) > 0){
			$q_datos .= "   AND I.cod_mediopago LIKE '%".trim($filtro_mediospago)."%'";
		}

		$q_datos .= "   AND DATE(C.fechahora_recepcion) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."') AS DATOS) AS DATOS_x
								 GROUP BY MEDIO_PAGO
								 ORDER BY 2 DESC";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
			if (mysqli_num_rows($res_datos) > 0) {
				$estado = 1;

				while($row_datos = mysqli_fetch_array($res_datos)){
					$html .= "{ value: ".$row_datos["TOTAL_VENTAS"].", name: '".$row_datos["MEDIO_PAGO"]."' },";
				}

				if (strlen($html) > 0){
					$html = substr($html, 0, -1);
				}
			}
		}

?>

<!DOCTYPE html>
<html lang="es">
	<head>

	</head>

	<body>
		<div id="chart_2" class="d-flex" style="width: 100%; height: 330px;">

		</div>

		<script type="text/javascript">
      // Initialize the echarts instance based on the prepared dom
      	var myChart = echarts.init(document.getElementById('chart_2'));

    	// Specify the configuration items and data for the chart
	      var option = {grid:{top: '0%',
														left: '10%',
														right: '3%'},

							        series: [
														    {
														      type: 'pie',
														      radius: ['35%', '60%'],
														      labelLine: {
														        length: 5
														      },
														      label: {
														        formatter: '  {b|{b}：} {per|S/ {c}}  ',
														        backgroundColor: '#F6F8FC',
														        borderColor: '#8C8D8E',
														        borderWidth: 1,
														        borderRadius: 4,
														        rich: {
														          b: {
														            color: '#4C5058',
														            fontSize: 12,
														            fontWeight: 'bold',
														            lineHeight: 33
														          },
														          per: {
														            color: '#fff',
														            backgroundColor: '#4C5058',
														            padding: [4, 4],
														            borderRadius: 4
														          }
														        }
														      },
														      data: [<?php echo $html; ?>]
														    }
														  ]
      							 };

      // Display the chart using the configuration items and data just specified.
				myChart.setOption(option);
		</script>
	</body>
</html>