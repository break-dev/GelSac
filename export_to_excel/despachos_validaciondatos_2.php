<?php
	header('Content-type: application/vnd.ms-excel; charset=UTF-8');
	header('Content-Disposition: attachment;filename=Mineral Ingresado.xls');
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
						$ids = $_GET["ids"];

						$ids = '';

				?>

					<font size = "3"><b>
						TOTAL INGRESADO
					</b></font>

					<br/>

					<font size = "2">
						Fecha de inicio: <b><?php echo $fecha_inicio; ?></b>
					</font>
					<br/>
					<font size = "2">
						Fecha de fin: <b><?php echo $fecha_fin; ?></b>
					</font>

					<br/>
					<br/>

					<table class="table table-bordered table-hover">
	        	<thead>
	        		<tr style="font-size: 14px;">
	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
	        				N°
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Código Minero
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				N° Ticket
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Fecha Hora Ingreso AUM
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Placa Ingreso
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Encargado Muestra
	        			</th>

	        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Proveedor Minero
	        			</th>

	        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Peso Neto AUM (TMH)
	        			</th>
	        		</tr>

	        		<tr style="font-size: 14px;">
	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				DNI
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Nombres
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				RUC / DNI
	        			</th>

	        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
	        				Razón Social / Nombre
	        			</th>
	        		</tr>
	        	</thead>

	        	<tbody id="tbl_detalle">

							<?php
								$d = 1;
								$html = '';
								$total_ingresado = 0;
								$total_ingresado_x = 0;

								$q_datos = "SELECT VD.Id,
																	 VD.lote_cod_lote,
																	 VD.lote_num_ticket,
															     VD.lote_ticket_orden,
															     VD.balanza_fechahoraregistro,
															     VD.balanza_placa,
															     EM.documento AS ENCARGADOMUESTRA_DNI,
															     EM.nombres AS ENCARGADOMUESTRA_NOMBRES,
															     PM.documento AS PROVEEDORMINERO_DOCUMENTO,
															     PM.razon_social AS PROVEEDORMINERO_RAZONSOCIAL,
															     VD.lote_peso_neto
														  FROM despachos_primertramo_validaciondatos VD
																	 LEFT JOIN tbconfig_encargadosmuestra EM ON VD.lote_id_encargadomuestra = EM.Id
														       LEFT JOIN tb_clientes PM ON VD.lote_id_proveedorminero = PM.Id
														 WHERE DATE(VD.balanza_fechahoraregistro) BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'";

								// if (strlen($ids) > 0){
								// 	$q_datos .= "   AND VD.Id IN (".$ids.")";
								// }

								$q_datos .= " ORDER BY VD.lote_cod_lote, lote_num_ticket, lote_ticket_orden";

								if ($res_datos = mysqli_query($enlace, $q_datos)){
				          if (mysqli_num_rows($res_datos) > 0) {
				            while($row_datos = mysqli_fetch_array($res_datos)){
				              $html .= '<tr style="font-size: 14px;">';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">';
											$html .= '   '.$d;
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["lote_cod_lote"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '    '.$row_datos["lote_num_ticket"].((strlen($row_datos["lote_ticket_orden"]) > 0) ? '-'.$row_datos["lote_ticket_orden"] : '');
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["balanza_fechahoraregistro"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
											$html .= '   '.$row_datos["balanza_placa"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["ENCARGADOMUESTRA_DNI"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["ENCARGADOMUESTRA_NOMBRES"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["PROVEEDORMINERO_DOCUMENTO"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
											$html .= '   '.$row_datos["PROVEEDORMINERO_RAZONSOCIAL"];
											$html .= '  </td>';

											$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; background-color: #B6D6F2; text-align: center;">';

											$total_ingresado_x = number_format(($row_datos["lote_peso_neto"] / 1000), 3, '.', ',');

											$html .= '   '.$total_ingresado_x;

											$total_ingresado += $total_ingresado_x;

											$html .= '  </td>';
											$html .= '</tr>';

				              $d ++;
				            }
				          }
				        }

				      // Agregando la fila de Total
				        $html .= '<tr style="font-size: 14px;">';
				        $html .= '  <td colspan="9" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; font-weight: bold;">';
								$html .= '  	TOTAL INGRESADO';
								$html .= '  </td>';

								$html .= '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; font-weight: bold; text-align: center;">';
								$html .= '  	'.$total_ingresado;
								$html .= '  </td>';
				        $html .= '</tr>';

				        echo $html;

							?>

						</tbody>
					</table>
			</div>
		</div>
	</div>
</html>