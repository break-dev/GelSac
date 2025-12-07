<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

  $is_vistatouch = $_SESSION["vista_touch"];

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
		<link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

		<title><?php echo $nom_app; ?> | LQ - Análisis de AAS | Generación de Esquemas</title>

		<script type="text/javascript">
			let itemrack_Selected = 0;
      let idrack_Selected = 0;
      let rack_IsCerrado = 0;
      let item_patronadded = 0;
		</script>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<!-- Menús principales -->
				<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-1" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; height: 114vh; background-color: #DEDEDE;">
					
				</div>

				<div class="col-md-11 col-sm-11 col-xs-11" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
							<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
								<h5>Filtros</h5>
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="row" style="padding-left: 30px; margin-top: -5px; margin-bottom: 10px; font-size: 13px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Fechas</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadRacks();">

											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadRacks();">
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Estado</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_estado" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadRacks();">
												<option selected value="">Elija una opción...</option>

												<?php
												$e = 0;

						            while($e <= 1){
						              ?>

						              <option value="<?php echo $e; ?>"><?php echo (($e == 0) ? 'Pendientes' : 'Cerrados'); ?></option>

						              <?php

						              $e ++;
						            }

												?>

											</select>
										</div>
									</div>
								</div>

								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Cód. Interno</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="barcode_lectura" type="text" class="form-control" style="font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase;">

											<img src="<?php echo $barcode_laser ?>" style="width: 45px; margin-left: -60px; margin-right: 10px;">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="padding: 0px;">
							<div id="div_racks" class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-8 col-sm-8 col-xs-12">
												<div class="d-flex">
													<h5>Lista de Racks</h5>

													<div id="wt_rack" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 14px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				Item
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px; border-top-right-radius: 15px;">
						        				Rack
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;" hidden>
						        				Observación
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_racks">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_muestras" class="col-md-5 col-sm-5 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<div class="d-flex">
	                        	<h5>Muestras de: </h5>
														<h5 id="lbl_titulomuestras" style="margin-left: 5px; color: #337ab7;"></h5>

														<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>
													</div>
                        </div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 14px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N° Vaso
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Muestra
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Peso (g)
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Es Reanálisis
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;" hidden>
						        				Tipo Réplica
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;" hidden>
						        				Cód. Interno
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Cód. Interno
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Material
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Análisis
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_detalle">
						        		
						        	</tbody>
						        </table>

						        <div class="d-flex" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px; background-color: #737373; color: #ffffff; margin-top: -10px;">
						        	<label style="width: 35%; padding-top: 7px; font-size: 14px;">
						        		Orden Elementos:
						        	</label>

						        	<input id="orden_elementos" type="text" class="form-control" style="font-size: 14px; text-align: center; font-weight: bold;" disabled>

						        	<button type="button" class="btn btn-warning load_esquema" style="margin-left: 10px; font-size: 14px;" onclick="f_GestionarOrdenElementos();">Gestionar</button>
						        </div>
									</div>
								</div>

								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px; margin-top: 5px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
                      	<h5>Lista de Patrones</h5>

												<div id="wt_patrones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
													<img src="<?php echo $img_waiting ?>" style="width: 20px;">
													<label style="font-style: italic;"> Cargando datos...</label>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 14px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Elemento
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Patrón
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Dilución
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Peso (g)
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_patrones">

						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_esquema" class="col-md-5 col-sm-5 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<div class="d-flex">
	                        	<h5>Esquema de Lectura</h5>

														<div id="wt_esquema" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>
													</div>
                        </div>

												<div class="col-md-4 col-sm-4 col-xs-12">
													<button class="btn btn-info load_esquema" type="button" onclick="f_GenerarEsquema();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
							              <b><i class="bi bi-arrow-repeat"></i> Generar Esquema</b>
							            </button>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 14px;">
						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Muestra
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;">
						        				Dilución
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;" hidden>
						        				Cód. Dilución
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;" hidden>
						        				Es Patrón
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;" hidden>
						        				Cód. Elemento
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_esquema">
						        		
						        	</tbody>
						        </table>

						        <div class="d-flex justify-content-center" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px; background-color: #737373; color: #ffffff; margin-top: -10px;">
						        	<button id="btn_GenerateFile" type="button" class="btn btn-warning load_esquema" style="margin-left: 10px; font-size: 14px; display: none;" onclick="f_GenerarArchivo();">
						        		Generar Archivo
						        	</button>

						        	<button id="btn_CerrarEsquema" type="button" class="btn btn-danger load_esquema" style="margin-left: 10px; font-size: 14px;" onclick="f_CerrarEsquema(1);">
						        		Cerrar Esquema
						        	</button>

						        	<button id="btn_ReabrirCierre" type="button" class="btn btn-success load_esquema" style="margin-left: 10px; font-size: 14px;" onclick="f_CerrarEsquema(0);">
						        		Reabrir Cierre
						        	</button>
						        </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Menú flotante -->
			<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%;">
			  <div class="offcanvas-header" style="background-color: #ffffff;">
			    <h5 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h5>
			    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			  </div>

			  <div id="div_submenu1" class="offcanvas-body" style="color: #212529;">

			  </div>
			</div>
		</div>

		<!-- Ventanas modales -->
		<div class="modal fade modal-dialog-scrollable" id="modal_ordenelementos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_ordenelementosLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_ordenelementosLabel">Ordenar Elementos</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 10px;">
							<table class="table table-bordered table-hover">
			        	<thead>
			        		<tr style="font-size: 14px;">
			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
			        				Orden
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 85%; border-top-right-radius: 15px">
			        				Elementos
			        			</th>
			        		</tr>
			        	</thead>

			        	<tbody id="tbl_ordenelementos">
			        		
			        	</tbody>
			        </table>
						</div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-warning" onclick="f_GrabarOrdenElementos();">Confirmar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Select2 -->
		<script src="libs/select2/dist/js/select2.full.min.js"></script>

		<!-- ECharts -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| LQ - Análisis de AAS | Generación de Esquemas');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadRacks();

				// Agrega el Focus
					document.getElementById("barcode_lectura").focus();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadRacks(){
        var _html = '';
        var d = 1;

        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_estado = $("#filtro_estado").val();
        	var cod_interno = $("#barcode_lectura").val();

        // Validando datos
      		// if (fecha_inicio == null){
          //   alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }
	        // if (fecha_inicio.length == 0){
          //   alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }

	        // if (fecha_fin == null){
          //   alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }
	        // if (fecha_fin.length == 0){
          //   alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }

	        // if (fecha_fin < fecha_inicio){
          //   alert('La fecha "Desde" no puede ser mayor a la fecha "Hasta".\nPor favor, verificar.');

          //   return;
        	// }

				// Cargando Lista de Racks
	        $("#tbl_racks").html('');
	        $("#tbl_detalle").html('');
	        $("#lbl_titulomuestras").html('');

	        f_SetButtons(0);

	        f_LoadingRacks(1);

	        $.post( "apis/backend.php", { accion: "get_AnalisisAAS_RacksEsquemas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_estado: filtro_estado, cod_interno: cod_interno }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_racks").html(data.html);

	            	itemrack_Selected = 1;
								idrack_Selected = data.id_rack;
								rack_IsCerrado = data.is_cerrado;

								f_LoadItemDetalle(itemrack_Selected, idrack_Selected, rack_IsCerrado);
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingRacks(0);

	          }, "json");
    	};

      function f_ColorSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_racks tr").each(function () {
          $("#tr_item_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_item_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulomuestras").html($("#lbl_td_1_" + _item).html().trim());
      };

      function f_LoadItemDetalle(_item, _id_rack, _is_cerrado){
      	item_patronadded = 0;
      	itemrack_Selected = _item;
        idrack_Selected = _id_rack;
        rack_IsCerrado = _is_cerrado;

        // Si el Rack está cerrado
        	if (rack_IsCerrado == 1){
        		$("#btn_GenerateFile").show();
        		$("#btn_CerrarEsquema").hide();
        		$("#btn_ReabrirCierre").show();

        		$(".load_esquema").prop('disabled', true);
        	}
        	else{
        		$("#btn_GenerateFile").hide();
        		$("#btn_CerrarEsquema").show();
        		$("#btn_ReabrirCierre").hide();

        		$(".load_esquema").prop('disabled', false);
        	}

        // Pinta selección
          f_ColorSelected(_item);

        // Inhabilita los botones
          f_SetButtons(0);

        // Limpia objetos de búsqueda
          $("#th_buscarmuestra_1").val('');

        // Cargando Esquema
          f_LoadEsquema();

        // Cargando datos de Muestras
          f_LoadingDetalle(1);
          f_LoadingPatrones(1);

          $("#tbl_detalle").html('');
          $("#tbl_patrones").html('');

          $.post( "apis/backend.php", { accion: "get_AnalisisAAS_RackListaMuestrasEsquemas", id_rack: _id_rack }, 
            function( data ) {
              if(data.estado == 1){
                // Actualiza la tabla de Muestras
                  $("#tbl_detalle").html(data.html_m);
                  $("#tbl_patrones").html(data.html_p);
                  $("#orden_elementos").val(data.orden_elementos);

				        // Validando si el Rack está cerrado
			        		if ($("#td_israckcerrado_" + _item).val() == 1){
			        			f_SetButtons(0);
			        		}
              }
              else{
                f_SetButtons(1);
              }

              f_LoadingDetalle(0);
              f_LoadingPatrones(0);

            }, "json");
      };

      function f_GestionarOrdenElementos(){
      	// Validando el cierre
      		if (rack_IsCerrado == 1){
      			return;
      		}

        // Obteniendo Elementos
          var _elementos = $("#orden_elementos").val();

        // Setea los elementos
          var e = 0;
          var _arr_elementos = _elementos.split(' - ');
          var _html = '';

          $("#tbl_ordenelementos").html(_html);

          while (e < _arr_elementos.length){
          	_html += '<tr id="tr_orden_' + (e + 1) + '" style="font-size: 14px; cursor: pointer;">';

          	if (e == 0){
          		_html += '  <td rowspan="' + _arr_elementos.length + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
          		_html += '  	<div class="col-md-12 col-sm-12 col-xs-12">';
          		_html += '  		<button class="btn btn-info" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;" onclick="f_OrdenElementos(1);">';
          		_html += '  			<i class="bi bi-arrow-bar-up"></i>';
          		_html += '  		</button>';
          		_html += '  	</div>';

          		_html += '  	<div class="col-md-12 col-sm-12 col-xs-12">';
          		_html += '  		<button class="btn btn-danger" type="button" style="color: #ffffff; font-size: 14px;" onclick="f_OrdenElementos(0);">';
          		_html += '  			<i class="bi bi-arrow-bar-down"></i>';
          		_html += '  		</button>';
          		_html += '  	</div>';
          		_html += '  </td>';
          	}

						_html += '  <td id="td_orden_' + (e + 1) + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px; font-weight: bold; ' + ((e == 0) ? 'background-color: #FFF587;' : '') + '" onclick="f_OrdenSelected(' + (e + 1) + '); ">';
						_html += '    ' + _arr_elementos[e];
						_html += '  </td>';

						_html += '</tr>';

          	e ++;
          }

          $("#tbl_ordenelementos").html(_html);

        // Abre modal
        	f_OpenModal('modal_ordenelementos');
      };

      function f_OrdenSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
	        $("#tbl_ordenelementos tr").each(function () {
	          $("#td_orden_" + i).css('background-color', '');

	          i += 1;
	        });

	        // Seteando item seleccionado
	          $("#td_orden_" + _item).css('background-color', '#FFF587');
      }

      function f_OrdenElementos(_is_up){
      	// Busca el <td> seleccionado
      		var t = 1;
      		var _selected = 0;

      		$("#tbl_ordenelementos tr").each(function () {
	          if ($("#td_orden_" + t).css('background-color') == 'rgb(255, 245, 135)'){
	          	_selected = t;
	          }

	          t += 1;
	        });

	      // Valida extremos
      		if (_is_up == 1 && _selected == 1){
      			return;
      		}

      		if (_is_up == 0 && _selected == (t - 1)){
      			return;
      		}

      	// Reordena Itemas
      		var new_selected = ((_is_up == 1) ? _selected - 1 : _selected + 1);

      		var _td_1 = $("#td_orden_" + _selected).html().trim();
      		var _td_2 = $("#td_orden_" + new_selected).html().trim();

					$("#td_orden_" + _selected).html(_td_2);
					$("#td_orden_" + new_selected).html(_td_1);

				// Seleccionar el Item movido
					var i = 1;

	        $("#tbl_ordenelementos tr").each(function () {
	          $("#td_orden_" + i).css('background-color', '');

	          i += 1;
	        });

	        // Seteando item seleccionado
	          $("#td_orden_" + new_selected).css('background-color', '#FFF587');
      }

      function f_LoadEsquema(){
      	// Cargando Esquema
	      	f_LoadingEsquema(1);

	      	$("#tbl_esquema").html('');

	        $.post( "apis/backend.php", { accion: "get_AnalisisAAS_Esquemas", id_rack: idrack_Selected }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_esquema").html(data.html);

	            	if (item_patronadded > 0){
	            		f_EsquemaSelected(item_patronadded + 1);
	            	}
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingEsquema(0);

		        }, "json");
      }

      function f_GenerarEsquema(){
      	// Validando el cierre
      		if (rack_IsCerrado == 1){
      			return;
      		}

      	// 1. Arma un array de Elementos
      		var e = 0;
      		var p = 1;
      		var m = 1;
      		var q = 1;
      		var _analisis = '';
      		var _html = '';

      		var arr_elementos = $("#orden_elementos").val().split(' - ');

      		f_LoadingEsquema(1);

      		while (e < arr_elementos.length){
      			// 2. Agrega el/los Patrones del elemento al Inicio del grupo de muestras
      				p = 1;

      				$("#tbl_patrones tr").each(function () {
			          if (arr_elementos[e].toLowerCase() == $("#td_patron_2_" + p).html().trim().toLowerCase()){
			          	_html += '<tr id="tr_itemesquema_' + q + '" style="cursor: pointer; font-size: 13px; background-color: ' + ((q == 1) ? '#FFF587' : ' #737373') + '; color: ' + ((q == 1) ? '#212529' : ' #ffffff') + ';" onclick="f_EsquemaSelected(' + q + ')">';

                  _html += '  <td id="td_itemesquema_1_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '    ' + q;
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_9_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; width: 30px;">';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_2_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                  _html += '    ' + $("#td_patron_3_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_3_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '    ' + $("#td_patron_4_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_4_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + $("#td_patron_6_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_5_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    1';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_6_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + arr_elementos[e];
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_7_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + $("#td_patron_5_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_8_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    0';
                  _html += '  </td>';

                  q ++;
			          }

			          p ++;
			        });

			      // 3. Agrega las Muestras del elemento
      				m = 1;

      				$("#tbl_detalle tr").each(function () {
      					_analisis = '|' + $("#td_detalle_4_" + m).html().trim().toLowerCase().replace(/ /g, '') + '|';

			          if (_analisis.includes('|' + arr_elementos[e].toLowerCase() + '|')){
			          	_html += '<tr id="tr_itemesquema_' + q + '" style="cursor: pointer; font-size: 13px; background-color: #E4EAF1;" onclick="f_EsquemaSelected(' + q + ')">';

                  _html += '  <td id="td_itemesquema_1_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '    ' + q;
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_9_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; width: 30px;">';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_2_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                  _html += '    ' + $("#td_detalle_8_" + m).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_3_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_4_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_5_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    0';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_6_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + arr_elementos[e];
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_7_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + $("#td_detalle_5_" + m).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_8_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    0';
                  _html += '  </td>';

                  q ++;
			          }

			          m ++;
			        });

		        // 4. Agrega el/los Patrones del elemento al Final del grupo de muestras
      				p = 1;

      				$("#tbl_patrones tr").each(function () {
			          if (arr_elementos[e].toLowerCase() == $("#td_patron_2_" + p).html().trim().toLowerCase()){
			          	_html += '<tr id="tr_itemesquema_' + q + '" style="cursor: pointer; font-size: 13px; background-color: #737373; color: #ffffff;" onclick="f_EsquemaSelected(' + q + ')">';

                  _html += '  <td id="td_itemesquema_1_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '    ' + q;
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_9_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; width: 30px;">';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_2_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                  _html += '    ' + $("#td_patron_3_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_3_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '    ' + $("#td_patron_4_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_4_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + $("#td_patron_6_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_5_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    1';
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_6_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + arr_elementos[e];
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_7_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    ' + $("#td_patron_5_" + p).html().trim();
                  _html += '  </td>';

                  _html += '  <td id="td_itemesquema_8_' + q + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
                  _html += '    0';
                  _html += '  </td>';

                  q ++;
			          }

			          p ++;
			        });

      			e ++;
      		}

      	// Setea html de Esquemas
      		$("#tbl_esquema").html(_html);

      		f_LoadingEsquema(0);

      	// Guarda Esquema
      		f_GuardaEsquema();
      }

      function f_AddPatron(_item){
      	// Validando el cierre
      		if (rack_IsCerrado == 1){
      			return;
      		}

      	// Busca el <td> seleccionado
      		var t = 1;
      		var _selected = 0;

      		$("#tbl_esquema tr").each(function () {
	          if ($("#tr_itemesquema_" + t).css('background-color') == 'rgb(255, 245, 135)'){
	          	_selected = t;

	          	return;
	          }

	          t += 1;
	        });

	      // Verifica que pertenezcan al mismo grupo
      		if ($("#td_itemesquema_6_" + _selected).html().trim().toLowerCase() != $("#td_patron_2_" + _item).html().trim().toLowerCase()){
      			alert("Está intentando agregar un Patrón de: " + $("#td_patron_2_" + _item).html().trim() + " en un grupo de: " + $("#td_itemesquema_6_" + _selected).html().trim() + ".\n\nPor favor, verificar.");

      			return;
      		}

    		// Inserta Item
      		var _html = '';
      		var _table = document.getElementById("tbl_esquema");
					var _row = _table.insertRow(_selected);

					_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; font-size: 13px;">';
					_html += '  0';
					_html += '</td>';

					_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; font-size: 13px;">';
					_html += '</td>';

					_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; font-size: 13px;">';
					_html += '  ' + $("#td_patron_3_" + _item).html().trim();
					_html += '</td>';

          _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 13px;">';
          _html += '    ' + $("#td_patron_4_" + _item).html().trim();
          _html += '  </td>';

          _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
          _html += '    ' + $("#td_patron_6_" + _item).html().trim();
          _html += '  </td>';

          _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
          _html += '    1';
          _html += '  </td>';

          _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
          _html += '    ' + $("#td_patron_2_" + _item).html().trim();
          _html += '  </td>';

          _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
          _html += '    ' + $("#td_patron_5_" + _item).html().trim();
          _html += '  </td>';

          _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" hidden>';
          _html += '    1';
          _html += '  </td>';

					_row.innerHTML = _html;

					item_patronadded = _selected;

				// Guarda el esquema
					f_GuardaEsquema(1);
      }

      function f_EsquemaSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
	        $("#tbl_esquema tr").each(function () {

	        	// Determina si es Patrón o Muestra
		        	if ($("#td_itemesquema_5_" + i).html().trim() == 1){
		        		$("#tr_itemesquema_" + i).css('background-color', '#737373');
		        		$("#tr_itemesquema_" + i).css('color', '#ffffff');
		        	}
		        	else{
		        		$("#tr_itemesquema_" + i).css('background-color', '#E4EAF1');
		        		$("#tr_itemesquema_" + i).css('color', '#212529');
		        	}

	          i += 1;
	        });

	        // Seteando item seleccionado
	          $("#tr_itemesquema_" + _item).css('background-color', '#FFF587');
	          $("#tr_itemesquema_" + _item).css('color', '#212529');
      }

      function f_GenerarArchivo(){
      	var nom_rack = 'EsquemaAAS - ' + $("#lbl_td_1_" + itemrack_Selected).html().trim();

      	$.post( "apis/backend.php", { accion: "generate_AnalisisAAS_ArchivoSifx", id_rack: idrack_Selected, nom_rack: nom_rack }, 
          function( data ) {
            if(data.estado == 1){
            	// Descargar archivo automáticamente
            		DownloadFileFromUrl('files/' + nom_rack + '.sifx', nom_rack + '.sifx');
            }
            else{
              // alert("No se encontraron resultados.");
            }

            f_LoadingRacks(0);

          }, "json");
      }

      // ---------------------------------

      function f_AddMuestras(){
      	// Setea los objetos
      		$("#addmuestra_barcode").val('');
      		$("#tbl_addmuestras").html('');

      		f_AddMuestras_SetButtons(0);

      	// Abre modal
        	f_OpenModal('modal_addmuestras');
      }

      $("#addmuestra_barcode").keyup(function (e) {
		    if (e.keyCode === 13) {
					f_AddMuestras_BuscarMuestra();
		    }
		  });

		  function f_AddMuestras_BuscarMuestra(){
		  	var _html = '';
		  	var bar_code = $("#addmuestra_barcode").val().trim();
		  	var tiene_reanalisis = 0;
		  	var id_cabecera_x = 0;

		  	$("#tbl_addmuestras").html(_html);

		  	f_LoadingBuscarMuestra(1);

		  	$.post( "apis/backend.php", { accion: "get_AnalisisAAS_AddMuestra", cod_interno: bar_code, id_rack: idrack_Selected },
          function( data ) {
            if(data.estado == 1){
            	$("#tbl_addmuestras").html(data.html);

            	f_AddMuestras_SetButtons(1);

            	// Setea objetos
            		id_cabecera_x = data.id_cabecera;

            		$("#id_cabecera").val(data.id_cabecera);

	            	$("#addmuestra_barcode").prop('disabled', true);
								$("#addmuestra_barcode").css('background-color', '#BBBBBB');
								$("#addmuestra_barcode").css('color', '#ffffff');

								$("#btn_addmuestra_cerrar").hide();
								$("#div_addmuestra_buttons").show();

							// Alista la lectura del Peso
								f_GetPeso_Show(1, id_cabecera_x, bar_code);
            }
            else{
            	if(data.estado == 0){
            		alert("Ocurrió un error al momento de ingresar la muestra.");
            	}

              if(data.estado == 2){
              	alert("La Muestra no se encuentra en la B.D.");
              }

              if(data.estado == 3){
              	alert("No se ha solicitado análisis de Absorción Atómica para La Muestra ingresada.");
              }

              if(data.estado == 4){
              	alert("La Muestra no ha sido recepcionada por L.Q.");
              }

              if(data.estado == 5){
              	if (confirm("La Muestra ya se encuentra registrada en este Rack.\n\n¿Desea agregarla como Reanálisis?")){
              		tiene_reanalisis = 1;

              		id_cabecera_x = data.id_cabecera;
              	}
              }

              if(data.estado == 6 || data.estado == 7){
              	if (confirm("La Muestra ya se encuentra registrada en otro Rack.\n\n¿Desea agregarla como Reanálisis?")){
              		tiene_reanalisis = 1;

              		id_cabecera_x = data.id_cabecera;
              	}
              }              
            }

            // Validando si es un reanálisis
				  		if (tiene_reanalisis == 1){
				  			$.post( "apis/backend.php", { accion: "get_AnalisisAAS_AddMuestra_Reanalisis", cod_interno: bar_code, id_rack: idrack_Selected, id_cabecera: id_cabecera_x },
				          function( data ) {
				            if(data.estado == 1){
				            	$("#tbl_addmuestras").html(data.html);

				            	// Setea objetos
				            		$("#id_cabecera").val(data.id_cabecera);

					            	$("#addmuestra_barcode").prop('disabled', true);
												$("#addmuestra_barcode").css('background-color', '#BBBBBB');
												$("#addmuestra_barcode").css('color', '#ffffff');

												$("#btn_addmuestra_cerrar").hide();
												$("#div_addmuestra_buttons").show();

											f_GetPeso_Show(1, data.id_cabecera, bar_code);
										}

									}, "json");
				  		}
				  		else{
				  			// $("#addmuestra_barcode").val('');
				  		}

				  	f_LoadingBuscarMuestra(0);

          }, "json");
		  }

		  function f_GetPeso_Show(_is_buscarmuestra, _id_detalle, _cod_interno, _update, _item){
		  	// Colocando título
		  		$("#lbl_titulogetpeso").html(_cod_interno);

		  	// Setea objetos
          $("#txt_getpeso").val('');
          $("#chk_GetPeso").prop('checked', true);
          $("#lbl_getpeso_check").html('Automático');

        // Asignando valores a objetos hidden
          $("#getpeso_isbuscarmuestra").val(_is_buscarmuestra);
					$("#getpeso_iddetalle").val(_id_detalle);
					$("#getpeso_update").val(((_update == 1) ? _update : 0));
					$("#getpeso_item").val(_item);

				// Obtiene los límites
					f_GetLimites();

        // Abre modal
        	f_OpenModal('modal_getpeso');

        	f_GetPeso(1);
		  }

		  function f_GetPeso(_on){
				if (_on == 1){
					$.get( "apis/interfaces.php", { accion: "get_Peso", cod_balanza: 3 }, 
	          function( data ) {
	            if(data.estado == 1){
	              if (data.peso == -1){
	                $("#div_SinConexion").show();

	                $("#txt_getpeso").val('');
	              }
	              else{
	                $("#div_SinConexion").hide();

	                $("#txt_getpeso").val(data.peso);

									// Guardar el peso automáticamente
	                	f_GuardarPeso();
	              }
	            }
	            else{
	              $("#div_SinConexion").show();
	            }

	            // Verifica si es Automático o Manual
	            	if ($("#chk_GetPeso").prop('checked')){
	            		setTimeout('f_GetPeso(1)', 1000);
	            	}
	            	else{
	            		$("#div_SinConexion").hide();
	            	}

	          }, "json");
				}
			}

		  function f_GetPesoPatron(_on){
				if (_on == 1){
					$.get( "apis/interfaces.php", { accion: "get_Peso", cod_balanza: 3 }, 
	          function( data ) {
	            if(data.estado == 1){
	              if (data.peso == -1){
	                $("#div_SinConexionPatron").show();

	                $("#txt_getpesopatron").val('');
	              }
	              else{
	                $("#div_SinConexionPatron").hide();

	                $("#txt_getpesopatron").val(data.peso);

									// Guardar el peso automáticamente
	                	f_GuardarPesoPatron();
	              }
	            }
	            else{
	              $("#div_SinConexionPatron").show();
	            }

	            // Verifica si es Automático o Manual
	            	if ($("#chk_GetPesoPatron").prop('checked')){
	            		setTimeout('f_GetPesoPatron(1)', 1000);
	            	}
	            	else{
	            		$("#div_SinConexionPatron").hide();
	            	}

	          }, "json");
				}
			}

		  function f_GetPeso_Auto(){
		  	var is_auto = (($("#chk_GetPeso").prop('checked')) ? 1 : 0);

		  	if (is_auto == 1){
		  		$('#lbl_getpeso_check').html('Automático');

		  		f_GetPeso(1);
		  	}
		  	else{
		  		$('#lbl_getpeso_check').html('Manual');

		  		f_BreakAutomatico();
		  	}
		  }

		  function f_GetPeso_AutoPatron(){
		  	var is_auto = (($("#chk_GetPesoPatron").prop('checked')) ? 1 : 0);

		  	if (is_auto == 1){
		  		$('#lbl_getpesopatron_check').html('Automático');

		  		f_GetPesoPatron(1);
		  	}
		  	else{
		  		$('#lbl_getpesopatron_check').html('Manual');

		  		f_BreakAutomatico();
		  	}
		  }

		  function f_GetLimites(){
		  	var _limites = '';

		  	$.post( "apis/backend.php", { accion: "get_LimitePesos", id_limitepesos: 1 }, 
          function( data ) {
            if(data.estado == 1){
            	_limites = data.limite_pesos.split('|');

            	$("#lim_minimo").html(_limites[1]);
							$("#lim_promedio").html(_limites[0]);
							$("#lim_maximo").html(_limites[2]);
            }
            else{
              // alert("No se encontraron resultados.");
            }

          }, "json");
		  }

		  function f_GetLimitesPatron(){
		  	var _limites = '';

		  	$.post( "apis/backend.php", { accion: "get_LimitePesos", id_limitepesos: 1 }, 
          function( data ) {
            if(data.estado == 1){
            	_limites = data.limite_pesos.split('|');

            	$("#lim_minimopatron").html(_limites[1]);
							$("#lim_promediopatron").html(_limites[0]);
							$("#lim_maximopatron").html(_limites[2]);
            }
            else{
              // alert("No se encontraron resultados.");
            }

          }, "json");
		  }

		  function f_AddMuestraCancelar(){
				var id_cabecera = $("#id_cabecera").val();

		  	if(confirm("¿Está seguro de Cancelar?\n\nSi continua perderá toda la información. ¿Desea continuar?")){
		  		f_EliminarMuestra(0, id_cabecera);

		  		// Setea objeetos
		  			$("#addmuestra_barcode").val('');
		  			$("#tbl_addmuestras").html('');

	        	$("#addmuestra_barcode").prop('disabled', false);
						$("#addmuestra_barcode").css('background-color', '#F2AA52');
						$("#addmuestra_barcode").css('color', '');

						$("#btn_addmuestra_cerrar").show();
						$("#div_addmuestra_buttons").attr('style', 'display: none !important');

						$("#th_addmuestra_1").val('');

						document.getElementById("addmuestra_barcode").focus();
		  	}
		  }

		  function f_AddMuestraConfirmar(_x){
		  	// Validando el registro del peso
		  		if ($("#td_buscarmuestra_getpeso_1_1").html().indexOf("button") > -1){
		  			alert("Aún no ha registrado el Peso.\nNo puede continuar.");

		  			return;
		  		}

        // Confirmando el registro de pesos
	  			if (_x == 0){
		  			f_cerrarModal('modal_addmuestras');
		  		}

		  		// Setea objetos
		  			$("#addmuestra_barcode").val('');
		  			$("#tbl_addmuestras").html('');

	        	$("#addmuestra_barcode").prop('disabled', false);
						$("#addmuestra_barcode").css('background-color', '#F2AA52');
						$("#addmuestra_barcode").css('color', '');

						$("#btn_addmuestra_cerrar").show();
						$("#div_addmuestra_buttons").attr('style', 'display: none !important');

						$("#th_addmuestra_1").val('');

						document.getElementById("addmuestra_barcode").focus();

		  		f_LoadItemDetalle(itemrack_Selected, idrack_Selected, rack_IsCerrado);
		  }

		  function f_GetPesoCerrar(){
		  	f_BreakAutomatico();
		  }

		  function f_GetPesoCerrarPatron(){
		  	f_BreakAutomaticoPatron();
		  }

		  function f_BreakAutomatico(){
		  	$("#chk_GetPeso").prop('checked', false);

	  		$("#div_SinConexion").hide();

	  		f_GetPeso(0);
		  }

		  function f_BreakAutomaticoPatron(){
		  	$("#chk_GetPesoPatron").prop('checked', false);

	  		$("#div_SinConexionPatron").hide();

	  		f_GetPesoPatron(0);
		  }

		  function f_ValidaRegistroPesosCompleto(){
		  	var r = 1;
		  	var p = 1;
		  	var _ok = 1;

		  	// Recorre la tabla de Muestras buscando botones habilitados de Pesos
			  	$('#tbl_detalle tr').each(function () {
						if ($("#td_detalle_6_" + r).html() != undefined){
							if ($("#td_detalle_6_" + r).html().indexOf("button") > -1){
								r = 999;

								_ok = 0;
							}
						}

						r ++;
	        });

	      // Recorre la tabla de Patronea buscando botones habilitados de Asignaciones y Pesos
			  	r = 1;

			  	$('#tbl_patrones tr').each(function () {
						if ($("#td_patron_3_" + r).html() != undefined && $("#td_patron_5_" + r).html() != undefined){
							if ($("#td_patron_3_" + r).html().indexOf("button") > -1 ||
									$("#td_patron_5_" + r).html().indexOf("button") > -1){
								r = 999;

								_ok = 0;
							}
						}

						r ++;
	        });

		  	// Valida si el registro de pesos está completo
			  	if (_ok == 1){
			  		f_SetButtons(5);
		  		}
		  		else{
		  			f_SetButtons(1);
		  		}
		  }

			$('.th_buscarmuestra').click(function(){
				// Limpia objetos de búsqueda
					$("#th_buscarmuestra_1").val('');
					$("#th_buscarmuestra_2").val('');
					$("#th_buscarmuestra_3").val('');
			});

		  document.querySelectorAll(".th_buscarmuestra").forEach(el => {
			  el.addEventListener("keyup", e => {
			  	if (e.keyCode === 13) {
			  		var _id = '';

			  		_id = e.target.getAttribute("id");

			  		f_BuscarMuestraOrdenPeso(_id);
			  	}
			  });
			});

			function f_BuscarMuestraOrdenPeso(_id){
				var cod_interno_x = $("#" + _id).val().trim().toUpperCase();
				var orden_item = $("#td_CI_" + cod_interno_x).val();

				// Limpia objetos de búsqueda
					$("#th_buscarmuestra_1").val('');

				// Buscar Código Interno en Rack
					$.post( "apis/backend.php", { accion: "get_AnalisisAAS_BuscarMuestraRack", id_rack: idrack_Selected, cod_interno: cod_interno_x }, 
	          function( data ) {
	            if(data.estado == 1){
	            	if (data.tiene_peso == 0){
	            		f_GetPeso_Show(0, data.id_analisis, cod_interno_x, 0, orden_item);
	            	}
	            	else{
            			if (!confirm("La muestra ingresada ya tiene un peso registrado.\n\n¿Desea modificarlo?")){
            				return;
            			}
            			else{
            				f_GetPeso_Show(0, data.id_analisis, cod_interno_x, 1, orden_item);
            			}
	            	}
	            }
	            else{
	              alert("La muestra no se encuentra en este Rack.");

	              $("#th_buscarmuestra_1").val('');
	            }

	          }, "json");
			}

			$('.th_addmuestra').click(function(){
				// Limpia objetos de búsqueda
					$("#th_addmuestra_1").val('');
					$("#th_addmuestra_2").val('');
			});

		  document.querySelectorAll(".th_addmuestra").forEach(el => {
			  el.addEventListener("keyup", e => {
			  	if (e.keyCode === 13) {
			  		var _id = '';

			  		_id = e.target.getAttribute("id");

			  		f_BuscarMuestraOrdenPeso_Add(_id);
			  	}
			  });
			});

			function f_BuscarMuestraOrdenPeso_Add(_id){
				var cod_interno_x = $("#" + _id).val().trim().toUpperCase();
				var id_cabecera = $("#id_buscarmuestra_1").val();

				// Validando que el C.I. sea el mismo que se ha buscado
					if (cod_interno_x != $("#td_buscarmuestra_2_1").html().trim().substring(0, 11))
					{
						alert("La muestra buscada no corresponde a la muestra que tiene en pantalla.\nPor favor, verificar.");

						return;
					}

				// Validando si tiene peso previo
					var _html = '';
					var _html_prev = '';

					// Validando si tiene una lectura previa
						_html = $("#td_buscarmuestra_getpeso_1_1");

						if (_html.html().indexOf('<button') == -1){
							alert("La muestra leída ya tiene un peso registrado.");

							$("#th_addmuestra_1").val('');

							return;
						}

				// Abriendo pantalla de Registro de Pesos
					f_GetPeso_Show(1, id_cabecera, cod_interno_x);
			}

		  function f_GetPeso_ShowPatron(_item, _id_patron, _des_elemento, _update){
		  	// Colocando título
		  		$("#lbl_titulogetpesopatron").html(_des_elemento);

		  	// Setea objetos
          $("#txt_getpesopatron").val('');
          $("#chk_GetPesoPatron").prop('checked', true);
          $("#lbl_getpesopatron_check").html('Automático');

        // Asignando valores a objetos hidden
					$("#getpeso_idpatron").val(_id_patron);
					$("#getpeso_itempatron").val(_item);
					$("#getpeso_updatepatron").val(((_update == 1) ? _update : 0));

				// Obtiene los límites
					f_GetLimitesPatron();

        // Abre modal
        	f_OpenModal('modal_getpesopatron');

        	f_GetPesoPatron(1);
		  }

		  function f_ShowAsignarPatron(_item, _id_patron, _id_elemento, _des_elemento, _update){
		  	var _html = '';

		  	// Colocando título
		  		$("#lbl_tituloasignarpatron").html(_des_elemento);

        // Asignando valores a objetos hidden
					$("#id_asignarpatron").val(_id_patron);
					$("#id_elementopatron").val(_id_elemento);
					$("#item_elementopatron").val(_item);
					$("#asignarpatron_update").val(((_update == 1) ? _update : 0));

				// Carga lista de Patrones por elementos
					$("#asignar_patron").html(_html);

					$.post( "apis/backend.php", { accion: "get_AnalisisAAS_ListaElementoPatrones", id_elemento: _id_elemento, solo_activos: 1 }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.registros, function( key, val ) {
									_html += '<option ' + ((val.is_predeterminado == 1) ? 'selected' : '') + ' value="' + val.Id + '|' + val.MATRIZ + '|' + val.dilucion + '|' + val.aliquota + '|' + val.fiola + '">' + val.patron + '</option>';
								});

								$("#asignar_patron").html(_html);
							}

						}, "json");

        // Abre modal
        	f_OpenModal('modal_asignarpatron');
		  }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_SetButtons(_x){
				$("#btn_addmuestras").prop('disabled', true);
				$("#btn_addmuestras").css('background-color', '#BBBBBB');
				$("#btn_addmuestras").css('color', '#ffffff');
				$("#btn_addmuestras").removeClass('btn-primary');
				$("#btn_addmuestras").addClass('btn-secondary');

				$("#btn_cierreanalisis").prop('disabled', true);
				$("#btn_cierreanalisis").css('background-color', '#BBBBBB');
				$("#btn_cierreanalisis").css('color', '#ffffff');
				$("#btn_cierreanalisis").removeClass('btn-primary');
				$("#btn_cierreanalisis").removeClass('btn-danger');
				$("#btn_cierreanalisis").addClass('btn-secondary');

				if (_x == 1){
					$("#btn_addmuestras").prop('disabled', false);
					$("#btn_addmuestras").css('background-color', '');
					$("#btn_addmuestras").css('color', '');
					$("#btn_addmuestras").removeClass('btn-secondary');
					$("#btn_addmuestras").addClass('btn-primary');
				}

				if (_x == 5){
					$("#btn_addmuestras").prop('disabled', false);
					$("#btn_addmuestras").css('background-color', '');
					$("#btn_addmuestras").css('color', '');
					$("#btn_addmuestras").removeClass('btn-secondary');
					$("#btn_addmuestras").addClass('btn-primary');

					$("#btn_cierreanalisis").prop('disabled', false);
					$("#btn_cierreanalisis").css('background-color', '');
					$("#btn_cierreanalisis").css('color', '');
					$("#btn_cierreanalisis").removeClass('btn-secondary');
					$("#btn_cierreanalisis").addClass('btn-danger');
				}
			}

			function f_AddMuestras_SetButtons(_x){
				$("#btn_addmuestra_pesobandeja").prop('disabled', true);
				$("#btn_addmuestra_pesobandeja").css('background-color', '#BBBBBB');
				$("#btn_addmuestra_pesobandeja").css('color', '#ffffff');
				$("#btn_addmuestra_pesobandeja").removeClass('btn-primary');
				$("#btn_addmuestra_pesobandeja").addClass('btn-secondary');

				$("#btn_addmuestra_pesohumedo").prop('disabled', true);
				$("#btn_addmuestra_pesohumedo").css('background-color', '#BBBBBB');
				$("#btn_addmuestra_pesohumedo").css('color', '#ffffff');
				$("#btn_addmuestra_pesohumedo").removeClass('btn-primary');
				$("#btn_addmuestra_pesohumedo").addClass('btn-secondary');

				if (_x == 1){
					$("#btn_addmuestra_pesobandeja").prop('disabled', false);
					$("#btn_addmuestra_pesobandeja").css('background-color', '');
					$("#btn_addmuestra_pesobandeja").css('color', '');
					$("#btn_addmuestra_pesobandeja").removeClass('btn-secondary');
					$("#btn_addmuestra_pesobandeja").addClass('btn-primary');
				}

				if (_x == 2){
					$("#btn_addmuestra_pesohumedo").prop('disabled', false);
					$("#btn_addmuestra_pesohumedo").css('background-color', '');
					$("#btn_addmuestra_pesohumedo").css('color', '');
					$("#btn_addmuestra_pesohumedo").removeClass('btn-secondary');
					$("#btn_addmuestra_pesohumedo").addClass('btn-primary');
				}
			}

			function f_LoadingRacks(_is_show){
				if (_is_show == 1){
					$("#wt_rack").show();
				}
				else{
					$("#wt_rack").hide();
				}
			}

			function f_LoadingDetalle(_is_show){
				if (_is_show == 1){
					$("#wt_detalle").show();
				}
				else{
					$("#wt_detalle").hide();
				}
			}

			function f_LoadingEsquema(_is_show){
				if (_is_show == 1){
					$("#wt_esquema").show();

					$(".load_esquema").prop('disabled', true);
				}
				else{
					$("#wt_esquema").hide();

					$(".load_esquema").prop('disabled', false);
				}
			}

			function f_LoadingGenerateArchivo(_is_show){
				if (_is_show == 1){
					$("#wt_generatefile").show();
				}
				else{
					$("#wt_generatefile").hide();
				}
			}

			function f_LoadingPatrones(_is_show){
				if (_is_show == 1){
					$("#wt_patrones").show();
				}
				else{
					$("#wt_patrones").hide();
				}
			}

			function f_LoadingBuscarMuestra(_is_show){
				if (_is_show == 1){
					$("#wt_detallebarcode").show();
				}
				else{
					$("#wt_detallebarcode").hide();
				}
			}

			function f_LoadingListaPendientes(_is_show){
				if (_is_show == 1){
					$("#wt_listapendientes").show();
				}
				else{
					$("#wt_listapendientes").hide();
				}
			}

			$("#modal_adminracks").on('shown.bs.modal', function(){
      	$("#nombre_rack").focus();
    	});

			$("#modal_addmuestras").on('shown.bs.modal', function(){
      	$("#addmuestra_barcode").focus();
    	});

    	$("#modal_getpeso").on('shown.bs.modal', function(){
      	$("#txt_getpeso").focus();
    	});

    	$("#modal_getpesopatron").on('shown.bs.modal', function(){
      	$("#txt_getpesopatron").focus();
    	});

			function f_SelectBarcode(_ind){
				$("#th_buscarmuestra_1").val('');
        $("#th_buscarmuestra_2").val('');
        $("#th_buscarmuestra_3").val('');

				document.getElementById("th_buscarmuestra_" + _ind).focus();
			}

      function f_HideListaRacks(_x){
        if (_x == 1){
          $("#div_racks").hide();
          $("#div_muestras").width('100%');

          f_CerrarDiv('C', 'div_ShowListaRacks');
          f_CerrarDiv('A', 'div_HideListaRacks');
          }
        else{
          $("#div_racks").show();
          $("#div_muestras").width('');

          f_CerrarDiv('A', 'div_ShowListaRacks');
          f_CerrarDiv('C', 'div_HideListaRacks');
        }
      };
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_GrabarOrdenElementos(){
				// Recorre la lista y crea el nuevl orden
					var t = 1;
					var orden_elementos = '';

					$("#tbl_ordenelementos tr").each(function () {
	          orden_elementos += $("#td_orden_" + t).html().trim() + ' - ';

	          t += 1;
	        });

	        if (orden_elementos.length > 0){
	        	orden_elementos = orden_elementos.substring(0, orden_elementos.length - 3);
	        }

	      // Guarda el nuevo orden
	      	$.post( "apis/backend.php", { accion: "grabar_AnalisisAAS_RacksOrdenElementos", id_rack: idrack_Selected, orden_elementos: orden_elementos },
            function( data ) {
              if(data.estado == 1){
              	$("#orden_elementos").val(orden_elementos);

                f_cerrarModal('modal_ordenelementos');
              }
              else{
                alert("Ocurrió un error al momento de Ordenar los Elementos.");
              }

            }, "json");
			}

			function f_GuardaEsquema(_reload){
				var e = 1;
				var _html = '';

				// Obteniendo la fecha y hora del registro
					var fechahora_registro = '';
					var usuario_registro = '<?php echo $_SESSION["usu_usuario"]; ?>';

					var _time = new Date();
        	_time = _time.getHours().toString().padStart(2, '0') + ":" + _time.getMinutes().toString().padStart(2, '0');

          fechahora_registro = '<?php echo $g_date; ?>' + ' ' + _time;

				// Arma html
					$("#tbl_esquema tr").each(function () {
						_html += "(" + idrack_Selected + ', ';
						_html += e + ', ';
						_html += "'" + $(this).find("td").eq(2).html().trim() + "', ";
						_html += (($(this).find("td").eq(3).html().trim().length == 0) ? 'NULL' : "'" + $(this).find("td").eq(3).html().trim() + "'") + ", ";
						_html += (($(this).find("td").eq(4).html().trim().length == 0) ? 'NULL' : "'" + $(this).find("td").eq(4).html().trim() + "'") + ", ";
						_html += $(this).find("td").eq(5).html().trim() + ', ';
						_html += "'" + $(this).find("td").eq(6).html().trim() + "', ";
						_html += $(this).find("td").eq(7).html().trim() + ", ";
						_html += $(this).find("td").eq(8).html().trim() + ", ";
						_html += "'" + fechahora_registro + "', ";
						_html += "'" + usuario_registro + "'), ";

	          e ++;
	        });

        // Validando datos
					if (_html.length == 0){
						return;
					}
					else{
						_html = _html.substring(0, _html.length - 2);
					}

				// Guarda esquema
					f_LoadingEsquema(1);

					$.post( "apis/backend.php", { accion: "grabar_AnalisisAAS_RacksEsquemas", id_rack: idrack_Selected, esquema: _html },
            function( data ) {
              if(data.estado == 1){
              	if (_reload == 1){
              		f_LoadEsquema(idrack_Selected);
              	}
              }
              else{
                alert("Ocurrió un error al momento de Guardar el Esquema.");
              }

              f_LoadingEsquema(0);

            }, "json");
			}

			function f_EliminarItem(_item){
      	// Validando el cierre
      		if (rack_IsCerrado == 1){
      			return;
      		}

				f_LoadingEsquema(1);

				$.post( "apis/backend.php", { accion: "eliminar_AnalisisAAS_ItemEsquemas", id_rack: idrack_Selected, item: _item },
          function( data ) {
            if(data.estado == 1){
            	f_LoadEsquema(idrack_Selected);
            }
            else{
              alert("Ocurrió un error al momento de Eliminar el Item.");
            }

            f_LoadingEsquema(0);

          }, "json");
			}

			function f_CerrarEsquema(_is_cierre){
				if (_is_cierre == 1){
					if (!confirm("¿Está seguro de cerrar el Esquema del Rack seleccionado?")){
	      		return;
	      	}
				}

      	// Guardando datos
      		$.post( "apis/backend.php", { accion: "cerrar_AnalisisAAS_Esquema", id_rack: idrack_Selected, is_cierre: _is_cierre },
	          function( data ) {
	            if(data.estado == 1){
	            	// Actualizar estado del Rack
		            	var html = $("#td_1_" + itemrack_Selected).html();

		            	if (_is_cierre == 0){
		            		html = html.replace('CERRADO', '');

		            		$("#td_1_" + itemrack_Selected).html(html);
		            	}
		            	else{
		            		html += '<br><label style="color: #FF5F5D; font-weight: bold;">CERRADO</label>';

		            		$("#td_1_" + itemrack_Selected).html(html);
		            	}

		            	$("#td_israckcerrado_" + itemrack_Selected).val(_is_cierre);

		            // Actualiza botones de Cierre
		            	if (_is_cierre == 1){
				        		$("#btn_GenerateFile").show();
				        		$("#btn_CerrarEsquema").hide();
				        		$("#btn_ReabrirCierre").show();
				        	}
				        	else{
				        		$("#btn_GenerateFile").hide();
				        		$("#btn_CerrarEsquema").show();
				        		$("#btn_ReabrirCierre").hide();
				        	}

				        	rack_IsCerrado = _is_cierre;
	            }
	            else{
	              alert("Ocurrió un error al momento de eliminar el Modelo.");
	            }

          }, "json");
			}

			// ---------------------------------
			function f_GrabarRack(){
        var _id_rack = $("#id_rack").val();
        var _item_rack = $("#item_rack").val();
        var _modo = $("#modo_grabarrack").val();

        var _fecha_rack = $("#fecha_rack").val();
        var _hora_rack = $("#hora_rack").val();
        var _registro_rack = _fecha_rack + ' ' + _hora_rack;
        var _nombre_rack = f_CleanInjection($("#nombre_rack").val().trim().toUpperCase());
        var _observacion = f_CleanInjection($("#observacion_rack").val().trim().toUpperCase());
        var _html = '';

        // Validando datos
          if (_nombre_rack == null){
            alert("Debe ingresar el nombre del Rack.");

            return;
          }
          if (_nombre_rack.length == 0){
            alert("Debe ingresar el nombre del Rack.");

            return;
          }

        // Grabando datos
          $.post( "apis/backend.php", { accion: "grabar_AnalisisAAS_Racks", modo: _modo, id_rack: _id_rack, registro_rack: _registro_rack, nombre_rack: _nombre_rack, observacion: _observacion },
            function( data ) {
              if(data.estado == 1){
                // Registra el nuevo Rack
                  if (_modo == 'N'){
                    _id_rack = data.id_rack;

                    // Obtiene el total de Racks
                      var item_rack = 1;

                      $("#tbl_racks tr").each(function () {
                        item_rack += 1;
                      });

                    // Obtiene los registros actuales de Racks
                      _html = $("#tbl_racks").html();

                    // Agregando el nuevo Rack
                      _html += '<tr id="tr_item_' + item_rack + '" style="cursor: pointer; font-size: 13px;" onclick="f_LoadItemDetalle(' + item_rack + ', ' + _id_rack + ', 0)">';

                      _html += '  <td id="td_item_1_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '    ' + item_rack;
                      _html += '  </td>';

                      _html += '  <td id="td_item_2_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 14px;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_AdminRack(' + "'M', " + item_rack + ', ' + _id_rack + ", '" + _fecha_rack + "', '" + _hora_rack + "', '" + _nombre_rack + "', '" + _observacion + "'" + ');">';
				              _html += '      	<i class="bi bi-pencil-square"></i>';
				              _html += '      </label>';
				              _html += '  </td>';

				              _html += '  <td id="td_item_3_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarRack(' + _id_rack + ');">X</label>';
				              _html += '  </td>';

                      _html += '  <td id="td_1_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                      _html += '  		<label id="lbl_td_1_' + item_rack + '" style="font-weight: bold;">';
				              _html += '      	' + _nombre_rack;
				              _html += '  		</label><br>';
				              _html += '  		<label>';
				              _html += '  			<i>' + _registro_rack + '</i>';
				              _html += '  		</label><br>';
				              _html += '  		<label>';
				              _html += '  			<i>' + '<?php echo $_SESSION["usu_usuario"]; ?>' + '</i>';
				              _html += '  		</label>';
                      _html += '  </td>';

                      _html += '  <td id="td_2_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '    ' + _observacion;
                      _html += '  </td>';

                      _html += '</tr>';

                    $("#tbl_racks").html(_html);

                    f_ColorSelected(item_rack);

                  	// Actualiza variables
                  		itemrack_Selected = item_rack;
                  		idrack_Selected = _id_rack;
                  		rack_IsCerrado = 0;
                  }

                // Actualiza el Rack seleccionado
                  if (_modo == 'M'){
                  	var _html_x = '';

                  	// td_item_2
                  		_html_x = '			<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_AdminRack(' + "'M', " + item_rack + ', ' + _id_rack + ", '" + _fecha_rack + "', '" + _hora_rack + "', '" + _nombre_rack + "', '" + _observacion + "'" + ');">';
				              _html_x += '      	<i class="bi bi-pencil-square"></i>';
				              _html_x += '			</label>';

				              $("#td_item_2_" + _item_rack).html(_html_x);

			              // td_1
				              _html_x = '  		<label style="font-weight: bold;">';
				              _html_x += '      	' + _nombre_rack;
				              _html_x += '  		</label><br>';
				              _html_x += '  		<label>';
				              _html_x += '  			<i>' + _registro_rack + '</i>';
				              _html_x += '  		</label><br>';
				              _html_x += '  		<label>';
				              _html_x += '  			<i>' + '<?php echo $_SESSION["usu_usuario"]; ?>' + '</i>';
				              _html_x += '  		</label>';

                    	$("#td_1_" + _item_rack).html(_html_x);

                    $("#td_2_" + _item_rack).html(_observacion);
                  }

                f_LoadItemDetalle(itemrack_Selected, idrack_Selected, rack_IsCerrado);
              }
              else{
                alert("Ocurrió un error al momento gusrdar el Rack.");
              }

              f_cerrarModal("modal_adminracks");

            }, "json");
      };

      function f_EliminarRack(_id_rack){
        if(confirm("¿Está seguro de eliminar el Rack seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_AnalisisHumedad_Racks", modo: 'E', id_rack: _id_rack },
            function( data ) {
              if(data.estado == 1){
                f_LoadRacks();
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Modelo.");
              }

            }, "json");
        }
      };

      function f_EliminarMuestra(_item, _id_cabecera){
      	if (_item != 0){
      		if(!confirm("¿Está seguro de eliminar la muestra seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
      			return;
      		}
      	}

        $.post( "apis/backend.php", { accion: "eliminar_AnalisisAAS_RackMuestra", id_cabecera: _id_cabecera },
          function( data ) {
            if(data.estado == 1){
              f_LoadItemDetalle(itemrack_Selected, idrack_Selected, rack_IsCerrado);
            }
            else{
              alert("Ocurrió un error al momento de eliminar el Modelo.");
            }

          }, "json");
      };

      function f_GuardarPeso(){
      	var _is_buscarmuestra = $("#getpeso_isbuscarmuestra").val();
				var _id_detalle = $("#getpeso_iddetalle").val();
				var _is_update = $("#getpeso_update").val();
				var _item = $("#getpeso_item").val();
				var _peso = $("#txt_getpeso").val();
				var _cod_interno = $("#addmuestra_barcode").val().trim();

				// Validando datos
					if (_peso == null){
            alert("Debe ingresar el Peso.");

            return;
          }
          if (_peso.length == 0){
            alert("Debe ingresar el Peso.");

            return;
          }
          if (_peso <= 0){
            alert("El Peso ingresado no es válido.");

            return;
          }

        // Validando que el Peso esté dentro del límite
          var limite_min = $("#lim_minimo").html().trim();
          var limite_max = $("#lim_maximo").html().trim();

          if (!(_peso >= limite_min && _peso <= limite_max)){
          	alert("El Peso ingresado se encuentra fuera del rango indicado.\n\n   - Peso Mínimo: " + limite_min + " g\n   - Peso Máximo: " + limite_max + " g");

          	f_LimpiarLecturaInterfaz();

            return;
          }

        // Grabando datos
        	$.post( "apis/backend.php", { accion: "guardar_AnalisisAAS_Peso", id_detalle: _id_detalle, peso: _peso },
	          function( data ) {
	            if(data.estado == 1){
	            	if (_is_update == 1){
	            		f_LoadItemDetalle(itemrack_Selected, idrack_Selected, rack_IsCerrado);

	            		f_cerrarModal("modal_getpeso");

	            		return;
	            	}

            		// Seteando objetos
            			if (_is_buscarmuestra == 1){
				          	$("#td_buscarmuestra_getpeso_1_1").html(_peso);
            				$("#th_addmuestra_1").val('');
				          }
				          else{
				          	$("#td_detalle_6_" + _item).html(_peso);
            				$("#th_buscarmuestra_1").val('');
				          }

            		// Interrumpe la interfaz
            			f_BreakAutomatico();

            		// Limpia la lectura
            			$("#txt_getpeso").val('');

            		f_ValidaRegistroPesosCompleto();

            		f_cerrarModal("modal_getpeso");
	            }
	            else{
	              alert("Ocurrió un error al momento de grabar el Peso.");

	              return;
	            }

	          }, "json");
      }

      function f_GuardarPesoPatron(){
				var _id_patron = $("#getpeso_idpatron").val();
				var _item_patron = $("#getpeso_itempatron").val();
				var _is_update = $("#getpeso_updatepatron").val();
				var _peso = $("#txt_getpesopatron").val();

				// Validando datos
					if (_peso == null){
            alert("Debe ingresar el Peso.");

            return;
          }
          if (_peso.length == 0){
            alert("Debe ingresar el Peso.");

            return;
          }
          if (_peso <= 0){
            alert("El Peso ingresado no es válido.");

            return;
          }

        // Validando que el Peso esté dentro del límite
          var limite_min = $("#lim_minimopatron").html().trim();
          var limite_max = $("#lim_maximopatron").html().trim();

          if (!(_peso >= limite_min && _peso <= limite_max)){
          	alert("El Peso ingresado se encuentra fuera del rango indicado.\n\n   - Peso Mínimo: " + limite_min + " g\n   - Peso Máximo: " + limite_max + " g");

          	f_LimpiarLecturaInterfaz();

            return;
          }

        // Grabando datos
        	$.post( "apis/backend.php", { accion: "guardar_AnalisisAAS_PesoPatron", id_patron: _id_patron, peso: _peso },
	          function( data ) {
	            if(data.estado == 1){
            		// Seteando objetos
			          	$("#td_patron_5_" + _item_patron).html(_peso);

            		// Interrumpe la interfaz
            			f_BreakAutomaticoPatron();

            		// Limpia la lectura
            			$("#txt_getpesopatron").val('');

            		f_ValidaRegistroPesosCompleto();

            		f_cerrarModal("modal_getpesopatron");
	            }
	            else{
	              alert("Ocurrió un error al momento de grabar el Peso.");

	              return;
	            }

	          }, "json");
      }

      function f_GrabarAsignarPatron(){
      	var _id_elemento = $("#id_elementopatron").val();
      	var _item_patron = $("#item_elementopatron").val();
				var _id_asignarpatron = $("#id_asignarpatron").val();
				var _is_update = $("#asignarpatron_update").val();
				var _id_patron = $("#asignar_patron").val().split('|')[0];
				var _des_asignarpatron = $("#asignar_patron option:selected").text().trim();
				var _matriz = $("#asignar_patron").val().split('|')[1];
				var _dilucion = $("#asignar_patron").val().split('|')[2];
				var _aliquota = $("#asignar_patron").val().split('|')[3];
				var _fiola = $("#asignar_patron").val().split('|')[4];

				// Validando datos
					if (_id_patron == null){
            alert("Debe seleccionar el Patrón.");

            return;
          }
          if (_id_patron.length == 0){
            alert("Debe seleccionar el Patrón.");

            return;
          }

        // Grabando datos
        	$.post( "apis/backend.php", { accion: "guardar_AnalisisAAS_Patron", id_elemento: _id_elemento, id_asignarpatron: _id_asignarpatron, id_patron: _id_patron, matriz: _matriz, diucion: _dilucion, aliquota: _aliquota, fiola: _fiola },
	          function( data ) {
	            if(data.estado == 1){
            		// Seteando objetos
			          	$("#td_patron_3_" + _item_patron).html(_des_asignarpatron);

			          	if (_matriz == 'null'){
			          		$("#td_patron_4_" + _item_patron).html('');
			          	}
			          	else{
			          		$("#td_patron_4_" + _item_patron).html('Matriz: ' + _matriz + ' - Dilución: x' + _dilucion + ' (' + _aliquota + ' en ' + _fiola + ')');
			          	}

			          f_ValidaRegistroPesosCompleto();

            		f_cerrarModal("modal_asignarpatron");
	            }
	            else{
	              alert("Ocurrió un error al momento de grabar el Peso.");

	              return;
	            }

	          }, "json");
      }

      function f_LimpiarLecturaInterfaz(){
      	$.post( "apis/backend.php", { accion: "limpia_PesoInterfaz", id_limitepesos: 3 },
          function( data ) {
            if(data.estado == 0){
	              alert("Ocurrió un error al momento de Limpiar el Peso de la Interfaz.");
	            }

	          }, "json");
      }

      function f_CerrarRack(){
      	if (!confirm("¿Está seguro de cerrar el Rack seleccionado?")){
      		return;
      	}

      	// Guardando datos
      		$.post( "apis/backend.php", { accion: "cerrar_AnalisisAAS", id_rack: idrack_Selected },
          function( data ) {
            if(data.estado == 1){
            	f_SetButtons(0);

            	// Seteando el cierre en el Rack
            		var html = $("#td_1_" + itemrack_Selected).html();

            		html += '<br><label style="color: #FF5F5D; font-weight: bold;">CERRADO</label>';

            		$("#td_1_" + itemrack_Selected).html(html);

            		$("#td_israckcerrado_" + itemrack_Selected).val(1);
            }
            else{
              alert("Ocurrió un error al momento de eliminar el Modelo.");
            }

          }, "json");
      }
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 400){
					
				}
			}

			$(document).ready(function() {
	  		$("#filtro_anho, #filtro_mes").select2();

	  		$("#select2-filtro_anho-container").css('background-color', '#0d2b68');
	  		$("#select2-filtro_anho-container").css('color', '#ffffff');

	  		$("#select2-filtro_mes-container").css('background-color', '#0d2b68');
	  		$("#select2-filtro_mes-container").css('color', '#ffffff');
	  	});

		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>