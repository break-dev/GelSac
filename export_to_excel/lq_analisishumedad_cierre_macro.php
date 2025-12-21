<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=LQ - Cierre de Análisis de Humedad (Para Macro).xls');
	header('Pragma: no-cache');
	header('Expires: 0');

	include('../cnx/cnx.php');
  include('../global/variables.php');

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
						$filtro_estado = $_GET["filtro_estado"];
						$cod_interno = $_GET["cod_interno"];

				?>

					<font size = "3"><b>
						LQ - Cierre de Análisis de Humedad
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
	        		<tr style="font-size: 14px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				N°
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Muestra
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
	        				% Humedad
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody id="tbl_muestras">

							<?php
								// Obtiene datos
									$d = 1;
									$id_cabecera = 0;
									$item_id = 0;
									$c = 0; // Utilizado para contabilizar los checks de Cierre

									$q_detalle = "SELECT C.cod_interno,
																			 C.cierre_prom
																  FROM analisislq_humedad_cabecera C
																       INNER JOIN analisislq_humedad_rack R ON C.id_rack = R.Id
																 WHERE DATE(R.fechahora_registro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'
																	 AND C.is_cierre = 1
																ORDER BY C.cod_interno";

									if ($res_detalle = mysqli_query($enlace, $q_detalle)) {
										if (mysqli_num_rows($res_detalle) > 0) {
											$estado = 1;            

											while($row_detalle = mysqli_fetch_assoc($res_detalle)) {
												$html .= '<tr style="font-size: 14px;">';
												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
												$html .= '    '.$d;
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
												$html .= '    '.$row_detalle["cod_interno"];
												$html .= '  </td>';

												$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; font-weight: bold; text-align: center;">';
												$html .= '  	'.$row_detalle["cierre_prom"];
												$html .= '  </td>';

												$html .= '</tr>';

												$d += 1;
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