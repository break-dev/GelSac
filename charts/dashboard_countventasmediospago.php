<?php

	session_start();

	include('../cnx/cnx.php');

	// Recibiendo parámetros
		$fecha_inicio = $_GET["fecha_inicio"];
		$fecha_fin = $_GET["fecha_fin"];

	// Obtiene datos
		$html = '';

		$q_datos = "SELECT SUBSTRING(MEDIO_PAGO, 1, 10) AS MEDIO_PAGO,
											 COUNT(COD_MEDIOPAGO) AS _COUNT
								  FROM (SELECT UPPER(IFNULL(MP.descripcion, 'POR PAGAR')) AS MEDIO_PAGO,
															 IFNULL(I.cod_mediopago, 0) AS COD_MEDIOPAGO
													FROM recepcion_ensayos_cabecera C
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

		$q_datos .= "   AND DATE(C.fechahora_recepcion) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."') AS DATOS
								 GROUP BY MEDIO_PAGO
								 ORDER BY 2 DESC";

		if ($res_datos = mysqli_query($enlace, $q_datos)){
			if (mysqli_num_rows($res_datos) > 0) {
				$estado = 1;

				while($row_datos = mysqli_fetch_array($res_datos)){
					$html .= "{ value: ".$row_datos["_COUNT"].", name: '".$row_datos["MEDIO_PAGO"]."' },";
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
		<div id="chart_1" class="d-flex" style="width: 100%; height: 350px;">

		</div>

		<script type="text/javascript">
      // Initialize the echarts instance based on the prepared dom
      	var myChart = echarts.init(document.getElementById('chart_1'));

    	// Specify the configuration items and data for the chart
	      var option = {grid:{top: '0%',
														left: '10%',
														right: '3%'},

							        series: [
														    {
														      type: 'pie',
														      radius: ['35%', '60%'],
														      labelLine: {
														        length: 10
														      },
														      label: {
														        formatter: '  {b|{b}：} {per|{c}}  ',
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