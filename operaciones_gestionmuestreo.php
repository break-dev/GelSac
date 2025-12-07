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

		<title><?php echo $nom_app; ?> | Gestión Muestreo</title>

		<script type="text/javascript">
			var is_mobile = 0;
			var color_selected = '';

			var itemlote_Selected = 0;
			var codlote_Selected = 0;
		</script>

		<style>
			/*.table-container{
				max-width: 100%;
				height: 800px;
				overflow-x: scroll;
				overflow-y: scroll;
			}*/

			/* Estilo para columnas estáticas*/
				.sticky{
					position: sticky;
					left: 0;
					z-index: 970;
				}

				.sticky-2{
					position: sticky;
					left: 35;
					z-index: 970;
				}

				.sticky-3{
					position: sticky;
					left: 58;
					z-index: 970;
				}

				.sticky-4{
					position: sticky;
					left: 58;
					z-index: 970;
				}

				.sticky-5{
					position: sticky;
					left: 188;
					z-index: 970;
				}

				.sticky-2h{
					position: sticky;
					left: 58;
					z-index: 970;
				}

				.sticky-3h{
					position: sticky;
					left: 58;
					z-index: 970;
				}

				.sticky-4h{
					position: sticky;
					left: 188;
					z-index: 970;
				}

			/* Estilo para Cabeceras estáticas */
				.sticky-1Cx{
					position: sticky;
					top: 0;
					z-index: 980;
				}

				.sticky-2Cxa{
					position: sticky;
					top: 0;
					z-index: 980;
				}

				.sticky-2Cxc{
					position: sticky;
					top: 95;
					z-index: 980;
				}

				.sticky-1C{
					position: sticky;
					top: 0;
				}

				.sticky-2Ca{
					position: sticky;
					top: 0;
					z-index: 970;
				}

				.sticky-2Cb{
					position: sticky;
					top: 33;
					z-index: 970;
				}

				.sticky-2Cc{
					position: sticky;
					top: 65;
					z-index: 970;
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
		            <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
		            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		          </div>
		          <div  class="modal-body" style=" padding: 0px !important;">
		            
	          		<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Fecha de Ingreso a Balanza</h6>
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

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Estado de Lote</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_estadolote" class="form-select" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_estadolote = "SELECT Id,
	                              								descripcion
								                           FROM tbconfig_estadoslote
								                          WHERE estado = 'A'";

								        if ($res_estadolote = mysqli_query($enlace, $q_estadolote)){
								          if (mysqli_num_rows($res_estadolote) > 0) {
								            while($row_estadolote = mysqli_fetch_array($res_estadolote)){
								              ?>

								              <option value="<?php echo $row_estadolote["Id"]; ?>"><?php echo $row_estadolote["descripcion"]; ?></option>

								              <?php
								            }
								          }
								        }

												?>

											</select>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Planta</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_planta" class="form-select" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_planta = "SELECT Id,
	                          								descripcion
						                           FROM tbconfig_plantas
						                          WHERE estado = 'A'";

								        if ($res_planta = mysqli_query($enlace, $q_planta)){
								          if (mysqli_num_rows($res_planta) > 0) {
								            while($row_planta = mysqli_fetch_array($res_planta)){
								              ?>

								              <option value="<?php echo $row_planta["Id"]; ?>"><?php echo $row_planta["descripcion"]; ?></option>

								              <?php
								            }
								          }
								        }

												?>

											</select>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Estado de Cierre</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_estadocierre" class="form-select" style="text-align: left; font-size: 14px;">
													<option value="">Elija una opción...</option>
													<option selected value="0">Pendiente</option>
													<option value="1">Cerrado</option>
												</select>
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
																				 WHERE (YEAR(dFechaIngreso) >= 2024
										 	 	 										OR ccod_Lote IN ('AUM-2587', 'AUM-3000', 'AUM-2337', 'AUM-2585', 'AUM-2907', 'AUM-2980'))
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


								<div class="row" style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
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

						<div class="row" style="padding: 0px;">
							<div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="d-flex">
													<h5>Información de Lotes </h5>

													<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>

													<div id="wt_saving" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Grabando datos...</label>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
										<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 600px; margin-bottom: 20px;">
											<table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 12px;">
							        			<th class="sticky sticky-1Cx" rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 58px;">
							        				N°
							        			</th>

							        			<th class="sticky-3h sticky-2Cxa" rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Lote
							        			</th>

							        			<th class="sticky-2Ca" rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
							        				Fecha Ingreso a Balanza<br>(Real)
							        			</th>

							        			<!-- <th colspan="2" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Encargado Muestra
							        			</th> -->

							        			<th colspan="1" class="sticky-2Ca" rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Encargado Muestra
							        			</th>

							        			<th class="sticky-2Cxa" colspan="9" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Información Muetreo
							        			</th>
							        		</tr>

							        		<tr style="font-size: 12px;">
														<!-- <th class="sticky-2Cb" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
							        				Documento
							        			</th>

							        			<th class="sticky-2Cb" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
							        				Nombres
							        			</th> -->

							        			<th class="sticky-2Cb" colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Las Lomas
							        			</th>

							        			<th class="sticky-2Cb" colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Solandra
							        			</th>

							        			<th class="sticky-2Cb" colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Paltarumi
							        			</th>
							        		</tr>

							        		<tr style="font-size: 12px;">
							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Solicitado
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
							        				Información Operaciones
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Solicitado
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
							        				Información Operaciones
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Solicitado
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #026E81; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
							        				Información Operaciones
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
		<div class="modal fade" id="modal_AddMuestreo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_AddMuestreoLabel" aria-hidden="true" style="z-index: 10000;">
		  <div class="modal-dialog">
		    <div id="modal_AddMuestreo_content" class="modal-content" style="margin-top: 15%;">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5">Muestreo para: </h1>
		        <h1 class="modal-title fs-5" id="modal_AddMuestreoLabel" style="margin-left: 5px;"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="d-flex">
							<label style="font-size: 14px; margin-top: 8px; min-width: 130px;">
								Lote:
							</label>

							<input id="muestreo_lote" type="text" class="form-control" style="text-align: center; font-size: 14px; width: 40%; font-weight: bold;" disabled>
			      </div>

		      	<div class="d-flex" style="margin-top: 5px;">
							<label style="font-size: 14px; margin-top: 8px; min-width: 130px;">
								Fecha Muestreo:
							</label>

							<input id="muestreo_fecha" type="date" class="form-control" style="text-align: center; font-size: 14px; width: 40%;" value="<?php echo $g_date; ?>">
			      </div>

			      <div class="d-flex" style="margin-top: 5px;">
							<label style="font-size: 14px; margin-top: 8px; min-width: 130px;">
								N° Muestreo:
							</label>

							<input id="muestreo_numero" type="number" class="form-control" style="text-align: center; font-size: 14px; width: 40%;">
			      </div>

			      <div class="d-flex justify-content-center" style="margin-top: 5px;">
							<label style="font-size: 14px; margin-top: 8px; min-width: 130px;">
								Observación:
							</label>

							<textarea id="muestreo_observacion" type="text" class="form-control" rows="2" style="text-transform: uppercase;"></textarea>
			      </div>
		      </div>

		      <input id="hd_idregistromuestreo" type="hidden">
		      <input id="hd_itemplanta" type="hidden">
		      <input id="hd_row" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarmuestreo" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarmuestreo_button" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_grabarmuestreo_button" onclick="f_GrabarMuestreo();">Grabar</button>
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
					$("#nv_titulo").html('| Gestión Muestreo');

				// Carga el detalle de información
					f_LoadResultados();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			function f_SetSelect2(){
			  $('.select_datos').select2({
			    theme: "bootstrap-5",
			    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			    placeholder: $( this ).data( 'placeholder' ),
			    allowClear: true
				}).on('select2:open', function() {
					$('body').css('zoom', '100%'); 
				}).on('select2:close', function() {
		    	$('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
				});

				$('#filtro_lote').select2({
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
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
				var _html = '';

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var filtro_lote = $("#filtro_lote").val();
        var filtro_estadolote = $("#filtro_estadolote").val();
        var filtro_planta = $("#filtro_planta").val();
        var filtro_estadocierre = $("#filtro_estadocierre").val();

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_Operaciones_ListaLotesMuestreo", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_lote: filtro_lote, filtro_estadolote: filtro_estadolote, filtro_planta: filtro_planta, filtro_estadocierre: filtro_estadocierre }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);
            }

            // Seteando Select2
          		f_SetSelect2();

					   f_LoadingResumen(0);

          }, "json");
    	}

    	function f_RowIsSolicitadoSelected(_item){
    		var is_solicitado = (($("#row_chk_" + _item).prop('checked')) ? 1 : 0);

    		$(".chk_issolicitado_" + _item).hide();

    		if (is_solicitado == 1){
    			$(".chk_issolicitado_" + _item).show();
    		}
    	}

    	function f_AddMuestreo(_item_planta, _item, _is_edit, _id_registromuestreo, fecha_muestreo, numero_muestreo, observacion){
    		// Título de ventana
    			var titulo = '';

    			if (_item_planta == 1){
    				titulo = 'LAS LOMAS';
    			}

    			if (_item_planta == 2){
    				titulo = 'SOLANDRA';
    			}

    			if (_item_planta == 3){
    				titulo = 'PALTARUMI';
    			}

    			$("#modal_AddMuestreoLabel").html(titulo);

  			// Limpiando datos

    		// Setea datos
    			if (_is_edit == 1){
    				$("#hd_idregistromuestreo").val(_id_registromuestreo);
    				$("#hd_itemplanta").val(_item_planta);
	    			$("#hd_row").val(_item);
	    			$("#hd_modograbar").val('E');
	    			$("#muestreo_lote").val($("#td_lote_" + _item).html().trim());
	    			$("#muestreo_fecha").val(fecha_muestreo);
	    			$("#muestreo_numero").val(numero_muestreo);
	    			$("#muestreo_observacion").val(observacion);
    			}
    			else{
    				$("#hd_idregistromuestreo").val(0);
    				$("#hd_itemplanta").val(_item_planta);
	    			$("#hd_row").val(_item);
	    			$("#hd_modograbar").val('N');
	    			$("#muestreo_lote").val($("#td_lote_" + _item).html().trim());
	    			$("#muestreo_fecha").val('<?php echo $g_date ?>');
	    			$("#muestreo_numero").val('');
	    			$("#muestreo_observacion").val('');
    			}
    			

    		// Abre modal
    			f_OpenModal('modal_AddMuestreo');
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

			function f_LoadingGrabarMuestreo(_is_show){
				if (_is_show == 1){
					$("#wt_grabarmuestreo").show();

					$(".wt_grabarmuestreo_button").prop('disabled', true);
				}
				else{
					$("#wt_grabarmuestreo").hide();

					$(".wt_grabarmuestreo_button").prop('disabled', false);
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
    	function f_GrabarMuestreo(){
    		// Recupera datos
    			var id_registromuestreo = $("#hd_idregistromuestreo").val();
    			var item_planta = $("#hd_itemplanta").val();
    			var pos_row = $("#hd_row").val();
    			var modo_grabar = $("#hd_modograbar").val();
    			var cod_lote = f_CleanInjection($("#muestreo_lote").val());
          var muestreo_fecha = $("#muestreo_fecha").val();
          var muestreo_numero = $("#muestreo_numero").val();
          var muestreo_observacion = f_CleanInjection($("#muestreo_observacion").val());

        // Validando datos
          if (muestreo_fecha == null){
            alert("Debe ingresar la Fecha de Muestreo.");

            return;
          }
          if (muestreo_fecha.length == 0){
            alert("Debe ingresar la Fecha de Muestreo.");

            return;
          }

          if (muestreo_numero == null){
            alert("Debe ingresar el Número de Muestreo.");

            return;
          }
          if (muestreo_numero.length == 0){
            alert("Debe ingresar el Número de Muestreo.");

            return;
          }

        // Grabando datos
          f_LoadingGrabarMuestreo(1);

          $.post( "apis/backend.php", { accion: "grabar_Operaciones_Muestreo", id_registro: id_registromuestreo, item_planta: item_planta, pos_row: pos_row, modo_grabar: modo_grabar, cod_lote: cod_lote, muestreo_fecha: muestreo_fecha, muestreo_numero: muestreo_numero, muestreo_observacion: muestreo_observacion }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tddiv_planta" + item_planta + "_" + pos_row).html(data.html_muestreo);
	            }

				      f_LoadingGrabarMuestreo(0);

				      // Cerrar modal
				      	f_cerrarModal('modal_AddMuestreo');
	          });
      }

      function f_EliminarMuestreo(_id_registromuestreo, _item_planta, _pos_row){
      	if (!confirm("¿Está seguro de eliminar el Muestreo seleccionado?")){
					return;
				}

				// Eliminando registro
					var cod_lote = f_CleanInjection($("#td_lote_" + _pos_row).html().trim());

					$.post( "apis/backend.php", { accion: "eliminar_Operaciones_Muestreo", id_registro: _id_registromuestreo, item_planta: _item_planta, pos_row: _pos_row, cod_lote: cod_lote },
	          function( data ) {
	          	if (data.estado == 1){
	          		$("#tddiv_planta" + _item_planta + "_" + _pos_row).html(data.html_muestreo);
	            }
	            else{
	              alert("Ocurrió un error al momento de Eliminar el Muestreo seleccionado.");

	          		return;
	            }

	          }, "json");
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