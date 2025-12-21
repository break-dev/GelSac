<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="<?php echo $favicon; ?>" type="image/png"/>

		<!-- Bootstrap -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

		<!-- Íconos -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

		<!-- Select2 -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

		<!-- JSColor -->
		<script src="libs/jscolor/jscolor.js"></script>

		<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Reporte de Pesos</title>

		<script type="text/javascript">
			var is_mobile = 0;
			var color_selected = '';

			var itemagrupacion_Selected = 0;
			var iddestino_selected = 0;
			var idmodalidadenvio_selected = 0;
			var codigodespacho_selected = 0;
			var fechaestimadadespacho_selected = 0;
			var placa_selected = '';
			var idproveedorminero_selected = 0;

			let img_selected = '0';
		</script>

		<style>
			/*.table-container{
				max-width: 100%;
				height: 800px;
				overflow-x: scroll;
				overflow-y: scroll;
			}*/


			/* Estilo para columnas estáticas */
				.sticky{
					position: sticky;
					top: 0;
					left: 0;
					z-index: 990;
				}

				.sticky-2{
					position: sticky;
					top: 0;
					left: 40;
					z-index: 990;
				}

				.sticky-3{
					position: sticky;
					top: 0;
					left: 98;
					z-index: 990;
				}

			/* Estilo para Rows de columnas estáticas */
				.row-sticky{
					position: sticky;
					left: 0;
					z-index: 0;
				}

				.row-sticky-2{
					position: sticky;
					left: 40;
					z-index: 0;
				}

				.row-sticky-3{
					position: sticky;
					left: 98;
					z-index: 0;
				}

			/* Estilo para Cabeceras estáticas */
				.sticky-1Cx{
					position: sticky;
					top: 0;
					height: 33;
					z-index: 980;
				}

				.sticky-2Cxa{
					position: sticky;
					top: 33;
					z-index: 980;
				}

				.sticky-3Cxa{
					position: sticky;
					top: 66;
					z-index: 980;
				}
		</style>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<!-- Modal (Menú Lateral) -->
	    	<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		      <div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important;">
		        <div class="modal-content">
		          <div class="modal-header">
		            <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
		            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		          </div>
		          <div  class="modal-body" style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0px !important;">
		            <ul class="list-unstyled">
		              <div id="div_menu1"></div>
		            </ul>
		          </div>
		        </div>
		      </div>
		    </div>

    		<!-- Modal (Menú Lateral) -->
	    	<div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		      <div class="modal-dialog modal-right" style="margin-top: 0px !important; margin-left: 0px !important;">
		        <div class="modal-content">
		          <div class="modal-header">
		            <h5 class="modal-title" id="menuModalLabel">Filtro de Opciones</h5>
		            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		          </div>
		          <div  class="modal-body" style="padding: 0px !important;">
		           
		          	<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
		          		<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Fecha Despacho:</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="row" >
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" >
												</div>
												<br><br>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" >
												</div>
											</div>
										</div>
									</div>
		          	</div>

	          		<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
		          		<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Destino:</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="row" >
												<div class="col-md-12 col-sm-12 col-xs-12">
													<select id="filtro_destino" class="form-select" style="text-align: left; font-size: 14px; ">
														<option selected value="">Elija una opción...</option>

														<?php

														$html = '';

														$q_datos = "SELECT Id,
																							 nombre_comercial
																					FROM tbconfig_plantas
																				 WHERE estado = 'A'
																				 	 AND para_despacho = 1
																				ORDER BY nombre_comercial";

															if ($res_datos = mysqli_query($enlace, $q_datos)){
																if (mysqli_num_rows($res_datos) > 0) {
																	while($row_datos = mysqli_fetch_array($res_datos)){
																		?>
																		
																		<option value="<?php echo $row_datos["Id"] ?>"><?php echo $row_datos["nombre_comercial"] ?></option>

																		<?php
																	}
																}
															}

														?>
														
													</select>
												</div>
											</div>
										</div>
									</div>
		          	</div>

	          		<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">

											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Lotes:</h6>
											</div>
											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;" />
											</div>
											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_lote" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
														<?php

														$q_lotes = "SELECT ccod_Lote
																					FROM catalogolotes
																				 WHERE YEAR(dFechaIngreso) >= 2024
																				ORDER BY ccod_Lote DESC";

										        if ($res_lotes = mysqli_query($enlace, $q_lotes)){
										          if (mysqli_num_rows($res_lotes) > 0) {
										            while($row_lotes = mysqli_fetch_array($res_lotes)){
										              ?>

										              <option value="<?php echo $row_lotes["ccod_Lote"]; ?>"><?php echo $row_lotes["ccod_Lote"]; ?></option>

										              <?php
										            }
										          }
										        }

										        ?>
													</select>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Código Despacho:</h6>
											</div>
											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;" />
											</div>
											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_codigodespacho" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
													<?php

													$q_despachos = "SELECT DISTINCT PD.codigo_despacho
																			  FROM despachos_segundotramo_programacion_detalle PD
																						 INNER JOIN despachos_segundotramo_programacion P ON PD.id_programacion = P.Id
																			 WHERE P.is_cerrado = 1
																			ORDER BY PD.codigo_despacho";

									        if ($res_despachos = mysqli_query($enlace, $q_despachos)){
									          if (mysqli_num_rows($res_despachos) > 0) {
									            while($row_despachos = mysqli_fetch_array($res_despachos)){
									              ?>

									              <option value="<?php echo $row_despachos["codigo_despacho"]; ?>"><?php echo $row_despachos["codigo_despacho"]; ?></option>

									              <?php
									            }
									          }
									        }

									        ?>
												</select>
											</div>
									
										</div>
									</div>
								</div>

								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
				              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
			            	</button>
			            </div>
								</div>

		          </div>
		        </div>
		      </div>
		    </div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
							<div class="row text-end" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
								<h5>
									Filtros
									<a role="button" data-bs-toggle="modal" data-bs-target="#filtroModal">
										<i class="bi bi-funnel" style="color: #000; font-size: 30px"></i>
									</a>
								</h5>
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>
							
						</div>

						<img id="img_evidencia" style="width: 250px; display: none;">

						<div class="row" style="padding: 0px;">
							<div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="d-flex">
													<div class="d-flex flex-fill">
														<h5>Resumen </h5>

														<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>

														<div id="wt_saving" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Grabando datos...</label>
														</div>
													</div>

													<div class="d-flex justify-content-end">
														<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px; margin-left: 10px;">
								              <b>Exportar a Excel</b>
								            </button>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
										<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 500px; margin-bottom: 20px;">
											<table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 12px;">
							        			<th class="sticky" rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 40px;">
							        				N°
							        			</th>

							        			<th rowspan="3" class="sticky-2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 70px;">
							        				Lote
							        			</th>

							        			<th rowspan="3" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Código Planta
							        			</th>

							        			<th rowspan="3" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Destino
							        			</th>

							        			<th rowspan="3" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
							        				Código Despacho
							        			</th>

							        			<th colspan="5" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Información Recepción
							        			</th>

							        			<th colspan="4" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Información Salida
							        			</th>

							        			<th colspan="5" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Información Planta
							        			</th>

							        			<th colspan="8" class="sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Anaálisis de Diferenciass
							        			</th>
							        		</tr>

							        		<tr style="font-size: 12px;">
							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Fecha Ingreso a Balanza<br>(Real)
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				Peso Bruto
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				Peso Tara
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				TMH<br>Recepción
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				TMS<br>Recepción
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Fecha<br>Salida
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				Peso Bruto
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				Peso Tara
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				TMH<br>Salida
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Fecha llegada<br>a Planta
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				Peso Bruto
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				Peso Tara
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				TMH<br>Planta
							        			</th>

							        			<th class="sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				TMS<br>Planta
							        			</th>

							        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Recepción vs Salida
							        			</th>

							        			<th colspan="4" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Recepción vs Planta
							        			</th>

							        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Salida vs Planta
							        			</th>
							        		</tr>

							        		<tr style="font-size: 12px;">
							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				TMH
							        			</th>

							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				%
							        			</th>

							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				TMH
							        			</th>

							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				%
							        			</th>

							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				TMS
							        			</th>

							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				%
							        			</th>

							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				TMH
							        			</th>

							        			<th class="sticky-3Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #cfaa41; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				%
							        			</th>
							        		</tr>
							        	</thead>

							        	<tbody id="tbl_detalle">

							        	</tbody>
							        </table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_editinfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_editinfoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_editinfo_content" class="modal-content" style="margin-top: 250px;">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_editinfoLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px; margin-left: 40px; margin-right: 40px;">
							<div class="flex-fill justify-content-center">
								<div id="div_edit_1" style="padding: 0px; margin-left: 20%; margin-right: 20%">
									<input id="edit_1" type="time" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center;">
								</div>
							</div>
						</div>
		      </div>

		      <input id="hd_editcoddespacho" type="hidden">
		      <input id="hd_edititem" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarEdit();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_showimagenes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showimagenesLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div id="modal_showimagenes_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_showimagenesLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<img id="img_imagenes" alt="">

							<div id="wt_imagenes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Cargando imagen...</label>
							</div>
						</div>
					</div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Select2 -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<!-- ECharts -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

		<!-- JSColor -->
		<script>
			// Here we can adjust defaults for all color pickers on page:
			jscolor.presets.default = {
			    position: 'bottom',
			    palette: [
			        '#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26',
			        '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
			        '#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d',
			        '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
			    ],
			    //paletteCols: 12,
			    hideOnPaletteClick: true,
			};
		</script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| Reporte de Pesos');

				// Carga el detalle de información
					f_LoadResultados();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			function f_SetSelect2(){
			//   $('.select_datos').select2({
			//     theme: "bootstrap-5",
			//     width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			//     placeholder: $( this ).data( 'placeholder' ),
			//     allowClear: true
			// 	}).on('select2:open', function() {
			// 	  $(this).data('select2').$dropdown.find(':input.select2-search__field').focus();
			// 	});

				$('#filtro_lote, #filtro_codigodespacho').select2({
			    theme: "bootstrap-5",
			    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : '100%',
			    placeholder: $( this ).data( 'placeholder' ),
			    allowClear: true,
			    minimumResultsForSearch: -1
				}).on('select2:open', function() {
					$('body').css('zoom', '100%'); 
				}).on('select2:close', function() {
		    	$('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
				});

				$('.select2-search__field').css('font-size', '14px');
			}

			$('#guia_marcaunidad, #guia_marcaunidad2, #guia_conductor').select2({
		    theme: "bootstrap-5",
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		    placeholder: $( this ).data( 'placeholder' ),
		    allowClear: true,
		    dropdownParent: $('#modal_adminguias')
			}).on('select2:open', function() {
				$('body').css('zoom', '100%'); 
			}).on('select2:close', function() {
	    	$('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
			});

			$('.select2-selection__rendered').css('font-size', '14px');
		</script>

		<!-- Seteando lógica de filtrado -->
		<script type="text/javascript">
			$(document).on('input', '.filter', function() {
				// Oculta todas las filas
					$("#tbl_detalle tr").hide();

				// Recorre cada filtro y lo ejecuta
			  	var f = 1;
			  	var tiene_masfiltros = 0;

			  	$(".filter").each(function() {
			  		var id_filter = $(this).attr('id');
					  var columnIndex = id_filter.substring(4) - 1; // Obtiene el índice de la columna
					  var filterValue = $(this).val().trim().toLowerCase(); // Valor del filtro en minúsculas

						if (f == 1){
							$("#tbl_detalle tr").filter(function() {
						    return $(this).find("td").eq(columnIndex).text().trim().toLowerCase().indexOf(filterValue) > -1;
						  }).show();
						}
						else{
							$("#tbl_detalle tr:visible").filter(function() {
								if (columnIndex == 3 || columnIndex == 6 || columnIndex == 10 || columnIndex == 11 || columnIndex == 15 || columnIndex == 17 || columnIndex == 18 || columnIndex == 22 || columnIndex == 23 || columnIndex == 24 || columnIndex == 25 || columnIndex == 28){
									return $(this).find('td:eq(' + columnIndex + ') select option:selected').text().trim().toLowerCase().indexOf(filterValue) < 0;
								}
								else{
									if (columnIndex == 7 || columnIndex == 8){
										if (columnIndex == 7){
											return $(this).find('td:eq(' + columnIndex + ') input[type="date"]').val().trim().toLowerCase().indexOf(filterValue) < 0;
										}
										else{
											return $(this).find('td:eq(' + columnIndex + ') input[type="time"]').val().trim().toLowerCase().indexOf(filterValue) < 0;
										}
									}
									else{
										if (columnIndex == 12 || columnIndex == 13 || columnIndex == 14){
											return ($(this).find('td:eq(' + columnIndex + ') input[type="date"]').val().trim().toLowerCase() + ' ' +
															$(this).find('td:eq(' + columnIndex + ') input[type="time"]').val().trim().toLowerCase()).indexOf(filterValue) < 0;
										}
										else{
											if (columnIndex == 16){
												return $(this).find('td:eq(' + columnIndex + ') textarea').val().trim().toLowerCase().indexOf(filterValue) < 0;
											}
											else{
												if (columnIndex == 26 || columnIndex == 27 || columnIndex == 29 || columnIndex == 30 || columnIndex == 31 || columnIndex == 32){
													return $(this).find('td:eq(' + columnIndex + ') input[type="number"]').val().trim().toLowerCase().indexOf(filterValue) < 0;
												}
												else{
													return $(this).find("td").eq(columnIndex).text().trim().toLowerCase().indexOf(filterValue) < 0;
												}
											}
										}
									}
								}

						  }).hide();
						}

					  f ++;
					});
			});
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
				var _html = '';

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var filtro_destino = $("#filtro_destino").val();
        var filtro_lote = $("#filtro_lote").val();
        var filtro_codigodespacho = $("#filtro_codigodespacho").val();

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_Despachos_ReportePesos", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_destino: filtro_destino, filtro_lote: filtro_lote, filtro_codigodespacho: filtro_codigodespacho }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);
            }

            f_LoadingResumen(0);

            // Seteando Select2
          		f_SetSelect2();

            // Se posisiona en el div de Detalle
            	var divDetalle = document.getElementById("div_detalle");

					    if (divDetalle) {
					      divDetalle.scrollIntoView({ behavior: "smooth" });
					    }

          }, "json");
    	}

    	function f_ExportToExcel(){
				// Obteniendo filtros
					var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_destino = $("#filtro_destino").val();
	        var filtro_lote = $("#filtro_lote").val();
	        var filtro_codigodespacho = $("#filtro_codigodespacho").val();

        // Generar Excel
        	window.location.href = "export_to_excel/despachos_reportepesos.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&id_destino=" + filtro_destino + "&filtro_lote=" + filtro_lote + "&filtro_codigodespacho=" + filtro_codigodespacho;
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingResumen(_is_show){
				if (_is_show == 1){
					$("#wt_resumen").show();
				}
				else{
					$("#wt_resumen").hide();
				}
			}

			function f_LoadingListaDestinos(_is_show){
				if (_is_show == 1){
					$("#wt_listalotes").show();
				}
				else{
					$("#wt_listalotes").hide();
				}
			}

			function f_SavingDatos(_is_show){
				if (_is_show == 1){
					$("#wt_saving").show();
				}
				else{
					$("#wt_saving").hide();
				}
			}

			function f_SavingDistribucion(_is_show){
				if (_is_show == 1){
					$("#wt_savingDistribucion").show();
				}
				else{
					$("#wt_savingDistribucion").hide();
				}
			}

			$('.color-box').click(function(){
        $('.color-box').css('border-color', '#D9D9D9'); // Resetear todos los bordes a blanco
        $(this).css('border-color', '#8D8D84'); // Establecer el borde del color seleccionado
        $(this).css('border-width', '3px');

        color_selected = $(this).data('color');
	    });

	    function f_SelectChk(){
	    	var is_checked = false;

	    	// Obteniendo valor del checkbox
		    	if ($("#th_Chk").prop('checked')){
		    		is_checked = true;
		    	}

		    // Recorre solo las filas visibles
		    	var d = 1;

		    	$("#tbl_detalle tr").each(function () {
		    		$("#chk_lote_" + d).prop('checked', is_checked);

		    		d ++;
		    	});
	    }

      function f_AgrupacionSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
          $(".bg_selected").css('background-color', '#ffffff');
          $(".cs_imgselect").hide();

        // Seteando item seleccionado
          $(".bg_selected_" + _item).css('background-color', '#FFF587');

          $("#img_select_" + _item).show();

        // Seteando títulos
          $("#lbl_titulodestino").html($("#td_detalle_1_" + _item).html().trim());
          $("#lbl_titulomodalidadenvio").html($("#td_detalle_2_" + _item).html().trim().substring(0, 25) + '...');
          $("#lbl_titulocodigodespacho").html($("#td_detalle_3_" + _item).html().trim());
          $("#lbl_titulofechaestimadadespacho").html($("#td_detalle_4_" + _item).html().trim());
          $("#lbl_tituloproveedorminero").html($("#td_detalle_5_" + _item).html().trim().substring($("#td_detalle_5_" + _item).html().trim().indexOf(" - ") + 2, 35) + '...');
          $("#lbl_tituloplaca").html($("#td_detalle_6_" + _item).html().trim());
      }

			function f_LoadingImagenes(_is_show){
				if (_is_show == 1){
					$("#wt_imagenes").show();
				}
				else{
					$("#wt_imagenes").hide();
				}
			}

	    function f_SelectChkCierre(){
	    	var is_checked = false;

	    	// Obteniendo valor del checkbox
		    	if ($("#th_Chk").prop('checked')){
		    		is_checked = true;
		    	}

		    // Recorre solo las filas visibles
		    	var tr_id = 1;

		    	$("#tbl_detalle tr:visible").filter(function() {
		    		$("#chk_cierre_" + tr_id).prop('checked', is_checked);

		    		tr_id ++;
		    	});
	    }
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_UpdateDatos(_item, _orden_campo){
				// Obtiene Id
					var _cod_lote = $("#id_" + _item).val();

				// Obtiene Valor
					var _valor = $("#val_" + _orden_campo + '_' + _item).val();

				// Complementa la fecha y hora de definición de Destino
					if (_orden_campo == 3 || _orden_campo == 4){
						_valor = $("#val_3_" + _item).val() + ' ' + $("#val_4_" + _item).val();
					}

				// Complementa la fecha y hora de muestreo para Las Lomas
					if (_orden_campo == 17 || _orden_campo == 18){
						_valor = $("#val_17_" + _item).val() + ' ' + $("#val_18_" + _item).val();
					}

				// Complementa la fecha y hora de muestreo para Solandra
					if (_orden_campo == 19 || _orden_campo == 20){
						_valor = $("#val_19_" + _item).val() + ' ' + $("#val_20_" + _item).val();
					}

				// Complementa la fecha y hora de muestreo para Paltarumi
					if (_orden_campo == 21 || _orden_campo == 22){
						_valor = $("#val_21_" + _item).val() + ' ' + $("#val_22_" + _item).val();
					}

				f_SavingDatos(1);

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "update_PrimerTramo_ValidacionDatos_new", cod_lote: _cod_lote, orden_campo: _orden_campo, valor: _valor },
            function( data ) {
            	if(data.estado == 1){
            		if (_orden_campo == 2){
            			var fechahora_registro = data.destino_fechahoraregistro;

            			// Seteando Fecha y Hora
            				if (_valor.length == 0){
            					$("#val_3_" + _item).val('');
	            				$("#val_4_" + _item).val('');
	            				$("#val_5_" + _item).html('');
            				}
            				else{
            					$("#val_3_" + _item).val(fechahora_registro.substring(0, 10));
	            				$("#val_4_" + _item).val(fechahora_registro.substring(11).substring(0, 5));
	            				$("#val_5_" + _item).html('0.0');
            				}
            		}

            		if (_orden_campo == 3 || _orden_campo == 4){
            			$("#val_5_" + _item).html(data.destino_totaldiasdefiniciondestino);
            		}

            		if (_orden_campo == 10){
            			$("#td_pv_1_" + _item).html(data.proveedorminero_concesion);
            			$("#td_pv_2_" + _item).html(data.proveedorminero_codigounico);
            			$("#td_pv_3_" + _item).html(data.proveedorminero_ubicacion);
            		}

            		// if (_orden_campo == 13){
            		// 	$("#val_14_" + _item).val(data.unidad_capacidad);
            		// 	$("#val_15_" + _item).val(data.unidad_tara);
            		// 	$("#val_16_" + _item).val(data.id_marca);
            		// }

            		// // Recorriendo tabla y actualizando Capacidad, Tara y Marca
            		// 	if (_orden_campo == 14 || _orden_campo == 15 || _orden_campo == 16 || _orden_campo == 29){
	            	// 		var d = 1;
	            	// 		var placa = $("#val_13_" + _item).val();
	            	// 		var placa_x = '';

	            	// 		$("#tbl_detalle tr").each(function () {
						    //   		if (d != _item){
						    //   			placa_x = $("#val_13_" + d).val();

						    //   			if (placa == placa_x){
						    //   				// Desactivando temporalmente el evento Change de los Select2
					      //       			if (_orden_campo == 16 || _orden_campo == 29){
					      //       				$("#val_" + _orden_campo + '_' + d).attr('onchange', '');
					      //       			}

						    //   				$("#val_" + _orden_campo + '_' + d).val(_valor);

						    //   				if (_orden_campo == 16 || _orden_campo == 29){
					      // 						// Actualizando el valor del Select
					      // 							$("#val_" + _orden_campo + '_' + d).trigger('change');

					      // 						// Volviendo a Setear el onchange a los Select2
						    //         			if (_orden_campo == 16 || _orden_campo == 29){
						    //         				$("#val_" + _orden_campo + '_' + d).attr('onchange', 'f_UpdateDatos(' + d + ', ' + _orden_campo + ')');
						    //         			}
						    //   				}
						    //   			}
						    //   		}

						    //       d ++;
						    //     });
	            	// 	}

            		// if (_orden_campo == 27 || _orden_campo == 28){
            		// 	$("#td_pesobruto_" + _item).html(data.peso_bruto);
            		// }

            		// Verificando si el registro está listo para el Cierre
            			f_VerifyCierreListo();
              }
              else{
                alert("Ocurrió un error al momento de grabar los datos de ingreso.");

                f_SavingDatos(0);

                return;
              }

              f_SavingDatos(0);

            }, "json");
			}

			function f_EditDistribucion(_orden_campo, _item){
				// Obtiene Id
					var _id_registro = $("#id_distribucion_" + _item).val();

				// Obtiene Valor
					var _valor = $("#id_distribucion_" + _orden_campo + "_" + _item).val();

				// Validando datos
					if (_orden_campo == 1){
    				// Muestra u oculta la Placa 2
    					if (_valor.split('|')[1] == 1){
    						$("#td_distribucion_3_" + _item).show();
    					}
    					else{
    						$("#td_distribucion_3_" + _item).hide();

    						$("#id_distribucion_3_" + _item).val('');
    						$("#id_distribucion_3_" + _item).trigger('change');
    					}
					}

					if (_orden_campo == 2){
						// Determina si tiene Carreta
							var tiene_carreta = $("#id_distribucion_1_" + _item).val().split('|')[1];

						// Establece la Capacidad
							if (tiene_carreta == 0){
								var capacidad = _valor.split('|')[1];

								if (capacidad != undefined){
									capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
								}
								else{
									capacidad = 0;
								}

								$("#id_distribucion_4_" + _item).val(capacidad);
							}
					}

					if (_orden_campo == 3){
						var capacidad = _valor.split('|')[1];

						if (capacidad != undefined){
							capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
						}
						else{
							capacidad = 0;
						}

						$("#id_distribucion_4_" + _item).val(capacidad);
					}

				f_SavingDistribucion(1);

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "update_PrimerTramo_DistribucionDatos", id_registro: _id_registro, orden_campo: _orden_campo, valor: _valor },
            function( data ) {
            	if(data.estado == 1){
								if (_orden_campo == 7){
									$("#td_fechainicial_" + itemlote_Selected).html(_valor);
								}

								if (_orden_campo == 10 || _orden_campo == 11){
									var peso_tara = $("#id_distribucion_10_" + _item).val();
									var peso_neto = $("#id_distribucion_11_" + _item).val();

									$("#id_distribucion_9_" + _item).val(f_RedondearDecimales(parseFloat(peso_tara) + parseFloat(peso_neto), 2));

									if (_orden_campo == 11){
										f_GetTotalDistribuido();
									}
								}

								// Verificando si el registro está listo para el Cierre
            			f_VerifyCierreListo();
              }
              else{
                alert("Ocurrió un error al momento de actualizar el dato.");
              }

              f_SavingDistribucion(0);

            }, "json");
			}

			function f_SetColor_Grabar(){
				var _item = $("#hd_SetColorItem").val();
				var _cod_lote = $("#hd_SetColorLote").val();
				var _color = $("#colorSeleccionado").val();

				// Grabando datos
					$.post( "apis/backend.php", { accion: "grabar_ValidacionDatos_SetColor", cod_lote: _cod_lote, color: _color },
            function( data ) {
            if(data.estado == 1){
            	$("#tr_detalle_" + _item).css('background-color', ((_color == 'NULL') ? '' : _color));
            }
            else{
              alert("Ocurrió un error al momento de grabar los datos.");
            }

            // Cierra modal
            	f_cerrarModal('modal_SetColor');

            }, "json");
			}

			function f_GrabarCierre(){
				var arr_pos = '';
				var arr_ids = '';
				var arr_idvalidacion = '';

				// Recorre las filas visibles
					$("#tbl_detalle tr:visible").filter(function() {
		    		tr_id = $(this).attr('id').substring(11);

		    		if ($("#chk_cierre_" + tr_id).prop('checked')){
		    			arr_idvalidacion += $("#id_" + tr_id).val() + ', ';

		    			arr_pos += tr_id + '|';
		    			arr_ids += $("#id_" + tr_id).val() + '|';
		    		}
		    	});

		    // Valida la selección de checkbox
					if (arr_idvalidacion.length == 0){
						alert("Debe seleccionar al menos un Lote");

						return;
					}
					else{
						arr_idvalidacion = arr_idvalidacion.substring(0, arr_idvalidacion.length - 2);
						arr_pos = arr_pos.substring(0, arr_pos.length - 1);
						arr_ids = arr_ids.substring(0, arr_ids.length - 1);

						$.post( "apis/backend.php", { accion: "cierre_PrimerTramo_Validacion", arr_idvalidacion: arr_idvalidacion },
	            function( data ) {
	            	if(data.estado == 1){
	            		// Setea tr cerrados
	            			var t = 0;

	            			arr_pos = arr_pos.split('|');
	            			arr_ids = arr_ids.split('|');

	            			while (t < arr_pos.length){
	            				$("#td_cierre_1_" + arr_pos[t]).html('<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_Reabrir(' + arr_pos[t] + ', ' + arr_ids[t] + ')"><u> Reabrir </u></label>');
	            				$("#td_cierre_2_" + arr_pos[t]).html(data.cerrado_fechahoraregistro);
          						$("#td_cierre_3_" + arr_pos[t]).html(data.cerrado_usuarioregistro);

          						// Actualiza el combo de Estados de Lote solo para Estados que no sean: "RETIRADO, NO COMERCIAL"
          							if ($("#val_6_" + arr_pos[t]).val() != 5 && $("#val_6_" + arr_pos[t]).val() != 6){
          								$("#val_6_" + arr_pos[t]).val(4);
													$("#val_6_" + arr_pos[t]).trigger('change');
          							}

	            				t ++;
	            			}
	              }
	              else{
	                alert("Ocurrió un error al momento de realizar el cierre.");
	              }

	            }, "json");
					}
			}

			function f_EliminarDistribucion(_id_distribucion, _num_ticket){
				if (!confirm("¿Está seguro de eliminar el Ticket seleccionado?")){
					return;
				}

				// Eliminando registro
					$.post( "apis/backend.php", { accion: "eliminar_PrimerTramo_Distribucion", id_distribucion: _id_distribucion, cod_lote: codlote_Selected },
	          function( data ) {
	          	if (data.estado == 1){
	          		f_LoadItemAgrupacion(itemlote_Selected, codlote_Selected);
	            }
	            else{
	              alert("Ocurrió un error al momento de Crear el Nuevo registro.");

	          		return;
	            }

	          }, "json");
			}

			function f_ConfirmarCierre(){
				// Verificar las filas de la grilla
					if ($("#tbl_detalle").find('tr').length == 0){
						alert("Debe seleccionar al menos un Lote.");

						return;
					}

				// Recorre la grilla buscando seleccionados
					var d = 1;
					var is_selected = 0;
					var arr_lotes = '';

					$("#tbl_detalle tr").each(function () {
    				if ($("#chk_cierre_" + d).prop('checked')){
    					arr_lotes += "'" + $("#id_" + d).val() + "', ";

    					is_selected = 1;
    				}

    				d ++;
    			});

    			if (is_selected == 0){
    				alert("Debe seleccionar al menos un Lote.");

						return;
    			}
    			else{
    				arr_lotes = arr_lotes.substring(0, arr_lotes.length - 2);
    			}

  			// Cerrando Lotes
    			$.post( "apis/backend.php", { accion: "cierre_PrimerTramo_ValidacionDistribucion", arr_lotes: arr_lotes },
	          function( data ) {
	          	if (data.estado == 1){
	          		f_LoadResultados();
	            }
	            else{
	              alert("Ocurrió un error al momento de Crear el Nuevo registro.");

	          		return;
	            }

	          }, "json");
			}

			function f_ConfirmarGuia(){
				var modograbar_guia = $("#modograbar_guia").val();
				var guia_fechas = $("#guia_fechas").val();
				var guia_remitenteserie = $("#guia_remitenteserie").val();
				var guia_remitentenumero = $("#guia_remitentenumero").val();
				var guia_transportistaserie = $("#guia_transportistaserie").val();
				var guia_transportistanumero = $("#guia_transportistanumero").val();
				var sin_GRT = (($("#chk_SinGRT").prop('checked')) ? 1 : 0);
				var guia_puntopartida = $("#guia_puntopartida").val();
				var guia_puntodestino = $("#guia_puntodestino").val();
				var guia_remitente = $("#guia_remitente").val();
				var guia_destinatario = $("#guia_destinatario").val();
				var guia_transportista = $("#guia_transportista").val();
				var guia_placa = $("#guia_placa").val();
				var guia_constanciamtc = $("#guia_constanciamtc").val();
				var guia_marcaunidad = $("#guia_marcaunidad").val();
				var guia_placa2 = $("#guia_placa2").val();
				var guia_constanciamtc2 = $("#guia_constanciamtc2").val();
				var guia_marcaunidad2 = $("#guia_marcaunidad2").val();
				var guia_conductor = $("#guia_conductor").val();
				var guia_motivotraslado = $("#guia_motivotraslado").val();
				var guia_capacidadunidad = $("#guia_capacidadunidad").val();
				var guia_ajustecapacidad = $("#guia_ajustecapacidad").val();

				// Validando datos
					if (guia_fechas == null){
            alert("Debe registrar la Fecha de las Guías.");

            return;
          }
          if (guia_fechas.length == 0){
            alert("Debe registrar la Fecha de las Guías.");

            return;
          }

          if (guia_remitenteserie == null){
            alert("Debe registrar la Serie de la Guía del Remitente.");

            return;
          }
          if (guia_remitenteserie.length == 0){
            alert("Debe registrar la Serie de la Guía del Remitente.");

            return;
          }

          if (guia_remitentenumero == null){
            alert("Debe registrar el Número de Guía del Remitente.");

            return;
          }
          if (guia_remitentenumero.length == 0){
            alert("Debe registrar el Número de Guía del Remitente.");

            return;
          }

          if (guia_puntopartida == null){
            alert("El Punto de Partida no ha sido configurado correctamente, por favor verificar.");

            return;
          }
          if (guia_puntopartida.length == 0){
            alert("El Punto de Partida no ha sido configurado correctamente, por favor verificar.");

            return;
          }

          if (guia_puntodestino == null){
            alert("El Punto de Destino no ha sido configurado correctamente, por favor verificar.");

            return;
          }
          if (guia_puntodestino.length == 0){
            alert("El Punto de Destino no ha sido configurado correctamente, por favor verificar.");

            return;
          }

          if (guia_destinatario == null){
            alert("El Destinatario no ha sido configurado correctamente, por favor verificar.");

            return;
          }
          if (guia_destinatario.length == 0){
            alert("El Destinatario no ha sido configurado correctamente, por favor verificar.");

            return;
          }

          if (guia_transportista == null){
            alert("La Empresa de Transporte no ha sido configurada correctamente, por favor verificar.");

            return;
          }
          if (guia_transportista.length == 0){
            alert("La Empresa de Transporte no ha sido configurada correctamente, por favor verificar.");

            return;
          }

          if (guia_constanciamtc == null){
            alert("Debe registrar el N° de Constancia MTC de la Placa 1.");

            return;
          }
          if (guia_constanciamtc.length == 0){
            alert("Debe registrar el N° de Constancia MTC de la Placa 1.");

            return;
          }

          if (guia_marcaunidad == null){
            alert("Debe registrar la Marca de la Placa 1.");

            return;
          }
          if (guia_marcaunidad.length == 0){
            alert("Debe registrar la Marca de la Placa 1.");

            return;
          }

          // Determinando si la segunda placa es visible
          	if ($(".info_placa2:first").is(":visible")) {
						  if (guia_constanciamtc2 == null){
		            alert("Debe registrar el N° de Constancia MTC de la Placa 2.");

		            return;
		          }
		          if (guia_constanciamtc2.length == 0){
		            alert("Debe registrar el N° de Constancia MTC de la Placa 2.");

		            return;
		          }

		          if (guia_marcaunidad2 == null){
		            alert("Debe registrar la Marca de la Placa 2.");

		            return;
		          }
		          if (guia_marcaunidad2.length == 0){
		            alert("Debe registrar la Marca de la Placa 2.");

		            return;
		          }
						}
						else{
							guia_placa2 = '';
							guia_constanciamtc2 = '';
							guia_marcaunidad2 = '';
						}

          if (guia_conductor == null){
            alert("Debe seleccionar el Conductor.");

            return;
          }
          if (guia_conductor.length == 0){
            alert("Debe seleccionar el Conductor.");

            return;
          }

          if (guia_motivotraslado == null){
            alert("El Motivo de Traslado no ha sido configurado correctamente, por favor verificar.");

            return;
          }
          if (guia_motivotraslado.length == 0){
            alert("El Motivo de Traslado no ha sido configurado correctamente, por favor verificar.");

            return;
          }

          // Validando la Capacidad de la Placa 1 / PLaca 2
          	if ($(".info_placa2:first").is(":visible")) {
		          if (guia_capacidadunidad == null){
		            alert("Debe registrar la Capacidad de la Unidad 2.");

		            return;
		          }
		          if (guia_capacidadunidad.length == 0){
		            alert("Debe registrar la Capacidad de la Unidad 2.");

		            return;
		          }
		          if (guia_capacidadunidad <= 0){
		          	alert("La Capacidad ingresada es incorrecta.");

		          	return;
		          }
		        }
		        else{
		        	if (guia_capacidadunidad == null){
		            alert("Debe registrar la Capacidad de la Unidad 1.");

		            return;
		          }
		          if (guia_capacidadunidad.length == 0){
		            alert("Debe registrar la Capacidad de la Unidad 1.");

		            return;
		          }
		          if (guia_capacidadunidad <= 0){
		          	alert("La Capacidad ingresada es incorrecta.");

		          	return;
		          }
		        }

		      // Validando el Ajuste de Capacidad
		        if ($("#chk_AjusteCapacidad").prop('checked')){
		        	if (guia_ajustecapacidad == null){
		            alert("No ha ingresado el Ajuste de Capacidad.");

		            return;
		          }
		          if (guia_ajustecapacidad.length == 0){
		            alert("No ha ingresado el Ajuste de Capacidad.");

		            return;
		          }
		          if (guia_ajustecapacidad <= 0){
		          	alert("El Ajuste de Capacidad ingresado es incorrecto.");

		          	return;
		          }
		        }
		        else{
		        	guia_ajustecapacidad = '';
		        }

					// Valida el registro de Pesos Ajustados
						var d = 1;
						var total_rows = $("#tbl_guialistalotes tr").length;

		    		while (d < total_rows){
		    			if ($("#guialote_pesoajustado_" + d).val().trim() == 0){
		    				alert("Debe ingresar el Peso Ajustado para:\n   - Lote: " + $("#guialote_lote_" + d).html().trim() + "\n   - Ticket: " + $("#guialote_ticket_" + d).html().trim().replace('<b>', '').replace('</b>', ''));

		    				return;
		    			}

		    			d ++;
		    		}

		    	// Valida si no se generará Guía de Transportista
		    		if (sin_GRT == 1){
		    			if (!confirm("¿Está seguro de Emitir la Guía del Remitente sin Guía del Transportista?")){
		    				return;
		    			}

		    			guia_transportistaserie = '';
							guia_transportistanumero = '';
		    		}
		    		else{
		    			if (guia_transportistaserie == null){
		            alert("Debe registrar la Serie de la Guía del Transportista.");

		            return;
		          }
		          if (guia_transportistaserie.length == 0){
		            alert("Debe registrar la Serie de la Guía del Transportista.");

		            return;
		          }

		          if (guia_transportistanumero == null){
		            alert("Debe registrar el Número de Guía del Transportista.");

		            return;
		          }
		          if (guia_transportistanumero.length == 0){
		            alert("Debe registrar el Número de Guía del Transportista.");

		            return;
		          }
		    		}

		    	// Valida que el Peso Distribuido no exceda la Capacidad del Vehículo
		    		var total_distribuido = parseFloat($("#guialote_totalpesoajustado").html().trim());
		    		var capacidad_unidad = parseFloat((($("#chk_AjusteCapacidad").prop('checked')) ? $("#guia_ajustecapacidad_total").val() : $("#guia_capacidadunidad").val()));

		    		if (total_distribuido > capacidad_unidad){
		    			alert("El Peso Total es mayor a la capacidad de la unidad.\nPor favor, verificar.");

		    			return;
		    		}

				// Obtiene el detalle de Lotes
					var d = 1;
					var arr_infolotes = '';

	    		while (d < total_rows){
	    			arr_infolotes += $("#id_guialote_" + d).val() + ';' + $("#guialote_descripcionbien_" + d).val() + ';' + $("#guialote_descripcionbien_" + d + ' option:selected').text() + ';' + parseFloat($("#guialote_pesoajustado_" + d).val()) + '|';

	    			d ++;
	    		}

	    		arr_infolotes = arr_infolotes.substring(0, arr_infolotes.length - 1);

	    	// Grabando datos
					$.post( "apis/backend.php", { accion: "grabar_SegundoTramo_GestionGuias", modograbar_guia: modograbar_guia, guia_fechas: guia_fechas, guia_remitenteserie: guia_remitenteserie, guia_remitentenumero: guia_remitentenumero, guia_transportistaserie: guia_transportistaserie, guia_transportistanumero: guia_transportistanumero, guia_puntopartida: guia_puntopartida, guia_puntodestino: guia_puntodestino, guia_destinatario: guia_destinatario, guia_placa: guia_placa, guia_constanciamtc: guia_constanciamtc, guia_marcaunidad: guia_marcaunidad, guia_placa2: guia_placa2, guia_constanciamtc2: guia_constanciamtc2, guia_marcaunidad2: guia_marcaunidad2, guia_conductor: guia_conductor, guia_motivotraslado: guia_motivotraslado, guia_capacidadunidad: guia_capacidadunidad, guia_ajustecapacidad: guia_ajustecapacidad, arr_infolotes: arr_infolotes, id_destino: iddestino_selected, id_modalidadenvio: idmodalidadenvio_selected },
            function( data ) {
            if(data.estado == 1){
            	// Imprimir Guías
            		url = 'print_segundotramo_guiar.php?a=' + data.gr_serie + '&b=' + data.gr_numero;
            		window.open(url, '_blank');

            		if (sin_GRT == 0){
            			url = 'print_segundotramo_guiat.php?a=' + data.gt_serie + '&b=' + data.gt_numero;
            			window.open(url, '_blank');
            		}

            	f_LoadItemAgrupacion(itemagrupacion_Selected, iddestino_selected, idmodalidadenvio_selected, codigodespacho_selected, fechaestimadadespacho_selected, placa_selected, idproveedorminero_selected);
            }
            else{
              alert("Ocurrió un error al momento de confirmar las guías.");
            }

            // Cierra modal
            	f_cerrarModal('modal_adminguias');

            }, "json");
			}

      function f_EliminarGuia(_numguia_serie, _numguia_numero){
      	if (!confirm("¿Está seguro de eliminar la guía seleccionada?\n\n   - Guía Remitente: " + _numguia_serie + '-' + _numguia_numero + "\n\nSi continua se eliminará también la guía del transportista.")){
      		return;
      	}

      	// Grabando datos
					$.post( "apis/backend.php", { accion: "eliminar_SegundoTramo_Guias", numguia_serie: _numguia_serie, numguia_numero: _numguia_numero },
            function( data ) {
	            if(data.estado == 1){
            		f_LoadItemAgrupacion(itemagrupacion_Selected, iddestino_selected, idmodalidadenvio_selected, codigodespacho_selected, fechaestimadadespacho_selected, placa_selected, idproveedorminero_selected);
	            }
	            else{
	              alert("Ocurrió un error al momento de eliminar la guía.");
	            }
	          });
      }

	    function f_Edit(_object, _item, _cod_despacho){
	    	// Obteniendo valor
	    		var valor = $("#val_" + _object + "_" + _item + '_' + _cod_despacho).val();

    		$.post( "apis/backend.php", { accion: "grabar_SegundoTramo_Resumen_Edit", item: _object, cod_despacho: _cod_despacho, valor: valor },
          function( data ) {
            if(data.estado == 1){
            	// Actualizando el valor
          			$(".edit_" + _cod_despacho.replace(/ /g, '')).val(valor);
            }
            else{
              alert("Ocurrió un error al momento de grabar los datos.");
            }

            f_cerrarModal("modal_editinfo");

          }, "json");
	    }

	    function f_EditLlegadaPlanta(_object, _item){
	    	// Obteniendo valores
	    		var id_registro = $("#tr_iddetalle_" + _item).val();
	    		var valor = f_CleanInjection($("#edit_d_" + _object + "_" + _item).val()).toUpperCase();

    		$.post( "apis/backend.php", { accion: "grabar_SegundoTramo_Resumen_EditLlegadaPlanta", item: _object, id_registro: id_registro, valor: valor },
          function( data ) {
            if(data.estado == 1){
            	// Actualizando el valor
          			$("#edit_d_" + _object + "_" + _item).val(valor);

          			// Recalcula el Peso Neto
	          			if (_object == 7 || _object == 8){
	          				var peso_bruto = $("#edit_d_7_" + _item).val();
	          				var peso_tara = $("#edit_d_8_" + _item).val();
	          				var peso_neto = f_RedondearDecimales(peso_bruto - peso_tara, 3);

	          				$("#edit_d_4_" + _item).val(((peso_neto == 0) ? '' : peso_neto));
	          				$("#edit_d_6_" + _item).val(f_RedondearDecimales(data.peso_seco, 3));
	          			}

          			// Recalcula el Peso Seco
	          			if (_object == 5){
	          				$("#edit_d_6_" + _item).val(f_RedondearDecimales(data.peso_seco, 3));
	          			}
            }
            else{
              alert("Ocurrió un error al momento de grabar los datos.");
            }

            f_cerrarModal("modal_editinfo");

          }, "json");
	    }

      function f_SaveImagen(_item, _id_registro){
	    	if ($("#img_evidencia").attr('src').length == 0){
	    		setTimeout('f_SaveImagen(' + _item + ', ' + _id_registro + ')', 1000);
	    	}
	    	else{
	    		// Guardando archivo
						var arr_imagenes = [];

						var _imagen = {
				      imagen: $("#img_evidencia").attr('src')
				    };

				    arr_imagenes.push(_imagen);

				    $.post( "apis/backend.php", { accion: "grabar_SegundoTramo_Resumen_LlegadaPlanta_UploadEvidencias", item: _item, id_registro: _id_registro, arr_imagenes: JSON.stringify(arr_imagenes) },
	            function( data ) {
	            	if(data.estado == 1){
	            		$("#img_view_" + _item + "_" + _id_registro).css('display', 'block');
	              }
		            else{
		            	if (data.estado == 0){
		            		alert("Ocurrió un error al momento de grabar la imagen.");

		            		return;
		            	}

		            	if (data.estado == 99){
		            		alert("El formato de imagen no es compatible.");

		            		return;
		            	}
		            }

	            }, "json");
	    	}
	    }
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');

					$("#modal_addcliente_content, #modal_addconductor_content, #modal_addzonaorigen_content, #modal_addacompanante_content").css('margin-top', '10px');
				}
			}

		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>