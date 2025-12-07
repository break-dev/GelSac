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

		<title><?php echo $nom_app; ?> | Stock - Destino Pendiente</title>

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

			.select2-container .select2-dropdown {
		    z-index: 3000;
		    font-size: 12px;
		  }

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
		            <h5 class="modal-title" id="menuModalLabel">Filtro de Opciones</h5>
		            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		          </div>
		          <div  class="modal-body" style="padding: 0px !important;">
		            
		            <div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="form-check">
											  <input id="chk_filtrofechas" class="form-check-input obj_waiting" type="checkbox" onchange="f_ShowPlaca();">

											  <label class="form-check-label" for="chk_filtrofechas" style="font-size: 14px; font-weight: bold;">
											    Por Fecha de Ingreso a Balanza
											  </label>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">

												<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>">
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="form-check">
											  <input id="chk_filtroplanta" class="form-check-input obj_waiting" type="checkbox" onchange="f_ShowPlaca();">

											  <label class="form-check-label" for="chk_filtroplanta" style="font-size: 14px; font-weight: bold;">
											    Por Planta Muestreo
											  </label>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_planta" class="form-select" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>
												<option value="1">Las Lomas</option>
												<option value="2">Solandra</option>
												<option value="3">Paltarumi</option>
											</select>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px; ">
											<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
												<div class="row" style="padding-left: 10px; padding-right: 10px;">
													<h6 style="font-size: 14px;">  <input id="chk_filtroestadoslotes" class="form-check-input obj_waiting" type="checkbox" onchange="f_ShowPlaca();" checked> Por Estado de Lote:</h6>
												</div>
												<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
													<hr style="border-color: #D9D9D9;" />
												</div>
												<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
													<select id="filtro_estadolote" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
														<?php

														$q_estadolote = "SELECT Id,
			                              								descripcion
										                           FROM tbconfig_estadoslote
										                          WHERE estado = 'A'
										                          	AND is_reportestock_destinopendiente_default = 1";

										        if ($res_estadolote = mysqli_query($enlace, $q_estadolote)){
										          if (mysqli_num_rows($res_estadolote) > 0) {
										            while($row_estadolote = mysqli_fetch_array($res_estadolote)){
										              ?>

										              <option value="<?php echo $row_estadolote["Id"]; ?>" selected><?php echo $row_estadolote["descripcion"]; ?></option>

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
												<h6 style="font-size: 14px;"><input id="chk_filtrolotes" class="form-check-input obj_waiting" type="checkbox" onchange="f_ShowPlaca();"> Por Lotes:</h6>
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

						<div class="row" style="padding: 0px;">
							<div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-9 col-sm-9 col-xs-12">
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

											<div class="col-md-3 col-sm-3 col-xs-12">
												<div class="d-flex justify-content-end">
													<button class="btn btn-info" type="button" onclick="f_PrintInforme();" style="width: 100%; color: #ffffff; font-size: 14px; height: 35px; margin-bottom: 10px;">
							              <b>PDF</b>
							            </button>

													<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; height: 35px; margin-bottom: 10px; margin-left: 5px;">
							              <b>Exportar a Excel</b>
							            </button>
							          </div>
							         </div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
										<div class="col-md-3 col-sm-3 col-xs-12" style="margin-top: -15px; font-size: 14px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; background-color: #D2E8E3; text-align: center;">
											Total Peso Neto (TMH): <label id="lbl_totaltmh" style="margin-left: 5px; font-weight: bold;"></label>
										</div>

										<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 450px; margin-bottom: 20px;">
											<table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 12px;">
							        			<th rowspan="3" class="sticky sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 58px;">
							        				N°
							        			</th>

							        			<th rowspan="3" class="sticky-3h sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Lote
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Fecha Ingreso<br>a Balanza<br>(Real)
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
							        				Encargado Muestra
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
							        				Proveedor Minero
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81; min-width: 80px;">
							        				Peso Neto<br>(TMH)
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
							        				Días Transcurridos
							        			</th>

							        			<th colspan="6" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Información Muestreo
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
							        				Modalidad Envío
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
							        				Estado de Lote
							        			</th>

							        			<th rowspan="3" class="sticky-2Ca" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px; border-top-right-radius: 15px;">
							        				Observación
							        			</th>
							        		</tr>

							        		<tr style="font-size: 12px;">
							        			<th colspan="2" class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Las Lomas
							        			</th>

							        			<th colspan="2" class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Solandra
							        			</th>

							        			<th colspan="2" class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Paltarumi
							        			</th>
							        		</tr>

							        		<tr style="font-size: 12px;">
							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Fecha Muestreo
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Días Transcurridos
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Fecha Muestreo
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Días Transcurridos
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Fecha Muestreo
							        			</th>

							        			<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
							        				Días Transcurridos
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
					$("#nv_titulo").html('| Stock - Destino Pendiente');

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

				$('#filtro_lote, #filtro_estadolote').select2({
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

				// Seteando el Filtro de Fechas
					var fecha_inicio = '';
        	var fecha_fin = '';

					if ($("#chk_filtrofechas").prop('checked')){
						fecha_inicio = $("#fecha_inicio").val();
        		fecha_fin = $("#fecha_fin").val();
					}
					else{
						fecha_inicio = '';
						fecha_fin = '';
					}

				// Seteando el Filtro de Plantas
					var filtro_planta = '';					

					if ($("#chk_filtroplanta").prop('checked')){
						filtro_planta = $("#filtro_planta").val();
					}
					else{
						filtro_planta = '';
					}

				// Seteando el Filtro de Estados de Lote
					var filtro_estadolote = '';					

					if ($("#chk_filtroestadoslotes").prop('checked')){
						filtro_estadolote = $("#filtro_estadolote").val();
					}
					else{
						filtro_estadolote = '';
					}

				// Seteando el Filtro de Lotes
					var filtro_lote = '';					

					if ($("#chk_filtrolotes").prop('checked')){
						filtro_lote = $("#filtro_lote").val();
					}
					else{
						filtro_lote = '';
					}

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_DespachosStok_DestinoPendiente", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_planta: filtro_planta, filtro_estadolote: filtro_estadolote, filtro_lote: filtro_lote }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);

              $("#lbl_totaltmh").html(data.total_tmh);
            }

            // Seteando Select2
          		f_SetSelect2();

					  f_LoadingResumen(0);

          }, "json");
    	};

    	function f_PrintInforme(){
				// Seteando el Filtro de Fechas
					var fecha_inicio = '';
        	var fecha_fin = '';

					if ($("#chk_filtrofechas").prop('checked')){
						fecha_inicio = $("#fecha_inicio").val();
        		fecha_fin = $("#fecha_fin").val();
					}
					else{
						fecha_inicio = '';
						fecha_fin = '';
					}

				// Seteando el Filtro de Plantas
					var filtro_planta = '';					

					if ($("#chk_filtroplanta").prop('checked')){
						filtro_planta = $("#filtro_planta").val();
					}
					else{
						filtro_planta = '';
					}

				// Seteando el Filtro de Estados de Lote
					var filtro_estadolote = '';					

					if ($("#chk_filtroestadoslotes").prop('checked')){
						filtro_estadolote = $("#filtro_estadolote").val();
					}
					else{
						filtro_estadolote = '';
					}

				// Seteando el Filtro de Lotes
					var filtro_lote = '';					

					if ($("#chk_filtrolotes").prop('checked')){
						filtro_lote = $("#filtro_lote").val();
					}
					else{
						filtro_lote = '';
					}

        // Setea URL
					var url = 'print_despachosstock_destinopendiente.php?fecha_inicio=' + fecha_inicio + "&fecha_fin=" + fecha_fin + "&filtro_planta=" + filtro_planta + "&filtro_estadolote=" + filtro_estadolote + "&filtro_lote=" + filtro_lote;

					window.open(url, '_blank');
    	}

    	function f_ExportToExcel(){
				// Seteando el Filtro de Fechas
					var fecha_inicio = '';
        	var fecha_fin = '';

					if ($("#chk_filtrofechas").prop('checked')){
						fecha_inicio = $("#fecha_inicio").val();
        		fecha_fin = $("#fecha_fin").val();
					}
					else{
						fecha_inicio = '';
						fecha_fin = '';
					}

				// Seteando el Filtro de Plantas
					var filtro_planta = '';					

					if ($("#chk_filtroplanta").prop('checked')){
						filtro_planta = $("#filtro_planta").val();
					}
					else{
						filtro_planta = '';
					}

				// Seteando el Filtro de Estados de Lote
					var filtro_estadolote = '';					

					if ($("#chk_filtroestadoslotes").prop('checked')){
						filtro_estadolote = $("#filtro_estadolote").val();
					}
					else{
						filtro_estadolote = '';
					}

				// Seteando el Filtro de Lotes
					var filtro_lote = '';					

					if ($("#chk_filtrolotes").prop('checked')){
						filtro_lote = $("#filtro_lote").val();
					}
					else{
						filtro_lote = '';
					}

        // Generar Excel
        	window.location.href = "export_to_excel/despachosstock_destinopendiente.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&filtro_planta=" + filtro_planta + "&filtro_estadolote=" + filtro_estadolote + "&filtro_lote=" + filtro_lote;
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

			function f_LoadingListaLotes(_is_show){
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

	    function f_SelectChkCierre(){
	    	var is_checked = false;

	    	// Obteniendo valor del checkbox
		    	if ($("#th_Chk").prop('checked')){
		    		is_checked = true;
		    	}

		    // Recorre solo las filas visibles
		    	var tr_id = 0;

		    	$("#tbl_detalle tr:visible").filter(function() {
		    		tr_id = $(this).attr('id').substring(11);

		    		$("#chk_cierre_" + tr_id).prop('checked', is_checked);
		    	});
	    }

      function f_LoteSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
          $(".td_bgselect").css('background-color', '#ffffff');
          $(".cs_imgselect").hide();

        // Seteando item seleccionado
          $("#td_select_1_" + _item).css('background-color', '#FFF587');
          $("#td_select_2_" + _item).css('background-color', '#FFF587');
          $("#td_select_3_" + _item).css('background-color', '#FFF587');

          $("#img_select_" + _item).show();

          $("#lbl_titulolote").html($("#td_lote_" + _item).html().trim());
          $("#lbl_tituloticket").html($("#td_select_2_" + _item).html().trim());
      }

      // Evita que el evento se propague hacia arriba
	      function f_handleCheckboxClick(event) {
				  event.stopPropagation();
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

				// Selecciona previamente el TR
					f_LoadItemLote(_item, _cod_lote);

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

            		if (_orden_campo == 9){
            			$("#td_totallote_" + itemlote_Selected).html(data.total_neto);
            		}

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

			function f_EditDistribucion(_orden_campo, _item, _valida_infounidad){
				// Obtiene Id
					var _id_registro = $("#id_distribucion_" + _item).val();

				// Obtiene Valor
					var _valor = $("#id_distribucion_" + _orden_campo + "_" + _item).val();

				// Validando datos
					if (_orden_campo == 1){
    				// Muestra u oculta la Placa 2
    					if (_valor.split('|')[1] == 1){
    						$("#td_distribucion_3_" + _item).show();
    						$("#id_distribucion_12_" + _item).show();
    						$("#id_distribucion_13_" + _item).show();
    						$("#td_distribucion_14_" + _item).show();
    					}
    					else{
    						$("#td_distribucion_3_" + _item).hide();
    						$("#id_distribucion_3_" + _item).val('');
    						$("#id_distribucion_3_" + _item).trigger('change');

    						$("#id_distribucion_12_" + _item).hide();
    						$("#id_distribucion_13_" + _item).hide();

    						$("#td_distribucion_14_" + _item).hide();
    						$("#id_distribucion_14_" + _item).val('');
    						$("#id_distribucion_14_" + _item).trigger('change');
    					}
					}

					if (_orden_campo == 2){
						// Determina si tiene Carreta
							var tiene_carreta = $("#id_distribucion_1_" + _item).val().split('|')[1];

						// Establece la Capacidad
							var capacidad = _valor.split('|')[1];
							var tara = _valor.split('|')[2];
							var id_marca = _valor.split('|')[3];

							// Setea Capacidad
								if (capacidad != undefined){
									capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
								}
								else{
									capacidad = 0;
								}

								$("#id_distribucion_4_" + _item).val(capacidad);

							// Setea Tara
								if (tara != undefined){
									tara = ((tara.trim().length > 0) ? f_RedondearDecimales(tara.trim() / 1000, 2) : '');
								}
								else{
									tara = 0;
								}

								$("#id_distribucion_5_" + _item).val(tara);

							// Setea Marca
								if (id_marca == undefined){
									id_marca = '';
								}

								$("#id_distribucion_6_" + _item).val(id_marca);
					}

					if (_orden_campo == 3){
						var capacidad = _valor.split('|')[1];
						var tara = _valor.split('|')[2];
						var id_marca = _valor.split('|')[3];

						// Setea Capacidad
							if (capacidad != undefined){
								capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
							}
							else{
								capacidad = 0;
							}

							$("#id_distribucion_12_" + _item).val(capacidad);

							// Setea Tara
								if (tara != undefined){
									tara = ((tara.trim().length > 0) ? f_RedondearDecimales(tara.trim() / 1000, 2) : '');
								}
								else{
									tara = 0;
								}

								$("#id_distribucion_13_" + _item).val(tara);

							// Setea Marca
								if (id_marca == undefined){
									id_marca = '';
								}

								$("#id_distribucion_14_" + _item).val(id_marca);
					}

					// if (_orden_campo == 7 || _orden_campo == 8){
					// 	var fecha_inicio_x = $("#id_distribucion_7_" + _item).val();
					// 	var fecha_fin_x = $("#id_distribucion_8_" + _item).val();

					// 	if (fecha_inicio_x > fecha_fin_x){
					// 		alert("La Fecha de Peso Inicial no puede ser mayor a la Fecha de Peso Final.");

					// 		if (_orden_campo == 7){
					// 			$("#id_distribucion_7_" + _item).val($("#id_distribucion_8_" + _item).val());
					// 		}
					// 		else{
					// 			$("#id_distribucion_8_" + _item).val($("#id_distribucion_7_" + _item).val());
					// 		}

					// 		return;
					// 	}
					// }

				f_SavingDistribucion(1);

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "update_PrimerTramo_DistribucionDatos", id_registro: _id_registro, orden_campo: _orden_campo, valor: _valor, valida_infounidad: ((_valida_infounidad == 1)  ? 1 : 0) },
            function( data ) {
            	if(data.estado == 1){
								if (_orden_campo == 7){
									$("#id_distribucion_8_" + _item).val(_valor);
								}

								if (_orden_campo == 8){
									$("#id_distribucion_7_" + _item).val(_valor);
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

            		// Por si se cambia algún datos referente a la unidad
	            		if (_valida_infounidad == 1){
	            			f_LoadItemLote(itemlote_Selected, codlote_Selected);
	            		}

	            	// Si se cambia la Placa y Fecha de Inicio valida el lote cerrado para los datos de unidades
	            		if (_orden_campo == 2 || _orden_campo == 7){
	            			f_VerifyLoteCerrado();
	            		}
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

	              f_SetInputDisabled();

	            }, "json");
					}
			}

			function f_AddDistribucion(){
				// Seteando el Total Distribuído
					f_GetTotalDistribuido();

				// Obteniendo totales y validando datos
					var total_lote = $("#td_totallote_" + itemlote_Selected).html().trim();
					var total_distribuido = $("#td_totaldistribuido_" + itemlote_Selected).html().trim();
					var id_encargado = $("#val_9_" + itemlote_Selected).val();

					if (id_encargado != 3){
						if (parseFloat(total_lote) == parseFloat(total_distribuido)){
							alert("Ya ha distribuído el Total del Lote, no puede continuar.");

							return;
						}
						else{
							if (parseFloat(total_distribuido) > parseFloat(total_lote)){
								alert("Ha superado el Total del Lote, no puede continuar.");

								return;
							}
						}
					}

				// Agregando Nueva Distribución
					if (!confirm("¿Está seguro de Crear una nueva distribución?")){
						return;
					}

					$.post( "apis/backend.php", { accion: "crear_PrimerTramo_NuevaDistribucion", cod_lote: codlote_Selected },
	          function( data ) {
	          	if (data.estado == 1){
	          		f_LoadItemLote(itemlote_Selected, codlote_Selected);

	          		// Seteando el Total Distribuído
									f_GetTotalDistribuido();
	            }
	            else{
	              alert("Ocurrió un error al momento de Crear el Nuevo registro.");

	          		return;
	            }

	          }, "json");
			}

			function f_EliminarDistribucion(_id_distribucion, _num_ticket){
				if (!confirm("¿Está seguro de eliminar el Ticket seleccionado?")){
					return;
				}

				// Eliminando registro
					$.post( "apis/backend.php", { accion: "eliminar_PrimerTramo_Distribucion", id_distribucion: _id_distribucion, cod_lote: codlote_Selected },
	          function( data ) {
	          	if (data.estado == 1){
	          		f_LoadItemLote(itemlote_Selected, codlote_Selected);
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

			function f_Reabrir(_item, _cod_lote){
				$.post( "apis/backend.php", { accion: "reabrir_PrimerTramo_ValidacionDistribucion", cod_lote: _cod_lote },
          function( data ) {
          	if (data.estado == 0){
          		alert("Ocurrió un error al momento de reabrir el registro.");

          		return;
          	}

          	if (data.estado == 1){
          		$("#td_cierre_1_" + _item).html('<input id="chk_cierre_' + _item + '" class="form-check-input chk_cierre" type="checkbox" style="transform: scale(1.5);">');
          		$("#td_cierre_2_" + _item).html('');
          		$("#td_cierre_3_" + _item).html('');

          		f_SetInputDisabled();

          		if (data.tiene_guia == 1){
          			alert("================= IMPORTANTE =================\n\nTener en cuenta que el lote seleccionado tiene Guía(s) asignadas. Una vez finalizados los ajustes en este módulo, verifique los cambios en el módulo de Gestión Guías.");
          		}
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