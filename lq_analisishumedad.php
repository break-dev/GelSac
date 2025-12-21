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

		<title><?php echo $nom_app; ?> | LQ - Análisis de Humedad</title>

		<script type="text/javascript">
			let itemrack_Selected = 0;
      let idrack_Selected = 0;
      let rack_tieneiniciosecado_selected = 0;
			let rack_tienefinsecado_selected = 0;
      let rack_horassecado_selected = 0;
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
							<div id="div_racks" class="col-md-6 col-sm-6 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
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

											<div class="col-md-4 col-sm-4 col-xs-12">
												<button class="btn btn-info" type="button" onclick="f_AdminRack('N');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
						              <b>+ Nuevo Rack</b>
						            </button>
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
						        			<th colspan="3" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				Item
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Rack
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Estufa
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Horas Secado
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
						        				Observación
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Información de Secado
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
						        				Inicio
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
						        				Fin (Programado)
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
						        				Fin (Real)
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_racks">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>

								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<h5>Lista de Muestras Pendientes por Analizar</h5>

												<div id="wt_listapendientes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 7px;">
													<label style="font-style: italic; margin-left: 5px;"> Cargando datos...</label>
													<img src="<?php echo $img_waiting ?>" style="width: 20px; margin-top: -5px;">
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<div class="d-flex" style="font-size: 14px; margin-top: -15px;">
											<label>Total Muestras Pendientes: </label>
											<label id="lbl_totalpendientes" style="color: #FF5F5D; margin-left: 5px; font-weight: bold;">765</label>
											<label style="margin-left: 10px; margin-right: 10px;"> | </label>
											<label>Total Pendientes por Recepción L.Q.: </label>
											<label id="lbl_totalpendientesLQ" style="color: #FF5F5D; margin-left: 5px; font-weight: bold;">765</label>
										</div>

										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 14px;">
						        			<th rowspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				Item
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Muestra
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Fecha Hora Recepción (A.T.C.)
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Fecha Hora Recepción (L.Q.)
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_pendientes">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_muestras" class="col-md-6 col-sm-6 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<div id="div_ShowListaRacks" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px;" onclick="f_HideListaRacks(1);">
                          <i class="bi bi-arrow-left-square" style="font-size: 18px;"></i>
                        </div>

                        <div id="div_HideListaRacks" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px; display: none;" onclick="f_HideListaRacks(0);">
                          <i class="bi bi-arrow-right-square" style="font-size: 18px;"></i>
                        </div>

												<h5>Muestras de: </h5>
												<h5 id="lbl_titulomuestras" style="margin-left: 5px; color: #337ab7;"></h5>

												<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
													<img src="<?php echo $img_waiting ?>" style="width: 20px;">
													<label style="font-style: italic;"> Cargando datos...</label>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: -10px;">
										<div class="d-flex justify-content-center">
											<button id="btn_addmuestras" type="button" class="btn btn-primary" style="font-size: 14px;" onclick="f_AddMuestras();">+ Agregar Muestras</button>
											<button id="btn_iniciosecado" type="button" class="btn btn-primary" style="font-size: 14px; margin-left: 5px;" onclick="f_GestionSecado(1);">Inicio Secado</button>
											<button id="btn_finsecado" type="button" class="btn btn-primary" style="font-size: 14px; margin-left: 5px;" onclick="f_GestionSecado(2);">Fin Secado</button>
											<button id="btn_cierreanalisis" type="button" class="btn btn-primary" style="font-size: 14px; margin-left: 5px;" onclick="f_CerrarRack();">Cerrar Análisis</button>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 14px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Muestra
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Tara (Peso Bandeja)
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Peso Húmedo
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Peso Seco + Tara
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Peso Seco
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				% Humedad
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Promedio
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
						        				Cód. Interno
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
						        				Material
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
						        				Item
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
						        				<div class="d-flex" style="padding-left: 5px; padding-right: 5px;">
															<input id="th_buscarmuestra_1" type="text" class="form-control th_buscarmuestra" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">

															<img src="<?php echo $barcode_laser ?>" style="width: 30px; margin-left: -35px;">
														</div>
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
						        				<div class="d-flex" style="padding-left: 5px; padding-right: 5px;">
															<input id="th_buscarmuestra_2" type="text" class="form-control th_buscarmuestra" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">

															<img src="<?php echo $barcode_laser ?>" style="width: 30px; margin-left: -35px;">
														</div>
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
						        				<div class="d-flex" style="padding-left: 5px; padding-right: 5px;">
															<input id="th_buscarmuestra_3" type="text" class="form-control th_buscarmuestra" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">

															<img src="<?php echo $barcode_laser ?>" style="width: 30px; margin-left: -35px;">
														</div>
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
		<div class="modal fade modal-dialog-scrollable" id="modal_adminracks" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminracksLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_adminracksLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
								Creación:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<div class="d-flex">
									<div class="col-md-7 col-sm-7 col-xs-7">
										<input id="fecha_rack" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center; width: 95%;" disabled>
									</div>

									<div class="col-md-5 col-sm-5 col-xs-5">
										<input id="hora_rack" type="time" class="form-control col-md-12 col-xs-12" style="text-align: center;" disabled>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
								Nombre Rack:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="nombre_rack" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase;">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
								Estufa:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="estufa_rack" class="form-select">
									<option value="">Elija una opción...</option>

									<?php

									$q_estufas = "SELECT Id,
																			 descripcion
																	FROM tbconfig_estufas
																 WHERE estado = 'A'
																 ORDER BY descripcion";

								  if ($res_estufas = mysqli_query($enlace, $q_estufas)){
										if (mysqli_num_rows($res_estufas) > 0) {
											while($row_estufas = mysqli_fetch_array($res_estufas)){
												?>

												<option value="<?php echo $row_estufas["Id"] ?>"><?php echo $row_estufas["descripcion"] ?></option>

												<?php
											}
										}
									}

									?>
								</select>
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
								Horas Secado:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="horassecado_rack" type="number" class="form-control" style="text-align: center;" max="8" min="3">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
								Observacion:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="observacion_rack" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase;" rows="3"></textarea>
							</div>
						</div>
		      </div>

		      <input id="modo_grabarrack" type="hidden">
		      <input id="id_rack" type="hidden">
		      <input id="item_rack" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarRack();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_addmuestras" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addmuestrasLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addmuestrasLabel">Agregar Muestra</h1>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 25px;">
								<div id="wt_detallebarcode" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 7px;">
									<img src="<?php echo $img_waiting ?>" style="width: 20px;">
									<label style="font-style: italic;"> Buscando Muestra...</label>
								</div>

								<input id="addmuestra_barcode" type="text" class="form-control" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #F2AA52; height: 45px; text-transform: uppercase;">

								<img src="<?php echo $barcode_laser ?>" style="width: 45px; margin-left: -60px; margin-right: 10px;">
							</div>
						</div>

						<div class="row" style="margin-top: 10px; margin-bottom: 5px; padding-left: 15px; padding-right: 15px;">
							<table class="table table-bordered table-hover">
			        	<thead>
			        		<tr style="font-size: 14px;">
			        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
			        				Muestra
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Tara (Peso Bandeja)
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
			        				Peso Húmedo
			        			</th>
			        		</tr>

			        		<tr style="font-size: 14px;">
			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Cód. Interno
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Material
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Item
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
			        				<div class="d-flex" style="padding-left: 5px; padding-right: 5px;">
												<input id="th_addmuestra_1" type="text" class="form-control th_addmuestra" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">

												<img src="images/barcode_laser.png" style="width: 30px; margin-left: -35px;">
											</div>
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
			        				<div class="d-flex" style="padding-left: 5px; padding-right: 5px;">
												<input id="th_addmuestra_2" type="text" class="form-control th_addmuestra" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">

												<img src="images/barcode_laser.png" style="width: 30px; margin-left: -35px;">
											</div>
			        			</th>
			        		</tr>
			        	</thead>

			        	<tbody id="tbl_addmuestras">
			        	</tbody>
			        </table>
				    </div>
		      </div>

		      <input id="id_cabecera" type="hidden">

		      <div class="modal-footer">
		        <button id="btn_addmuestra_cerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <div id="div_addmuestra_buttons" class="d-flex" style="display: none !important">
			        <button class="btn btn-danger" type="button" style="font-size: 14px; margin-right: 5px;" onclick="f_AddMuestraCancelar();">Cancelar</button>
			        <button class="btn btn-warning" type="button" style="font-size: 14px; margin-right: 5px;" onclick="f_AddMuestraConfirmar(0);">Confirmar y Cerrar</button>
			        <button id="btn_addmuestra_confirmar" class="btn btn-warning" type="button" style="font-size: 14px;" onclick="f_AddMuestraConfirmar(1);">Confirmar y Agregar Nueva Muestra</button>
			      </div>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_getpeso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_getpesoLabel" aria-hidden="true">
		  <div class="modal-dialog" style="margin-top: 21%;">
		    <div class="modal-content">
		      <div class="modal-header">
		      	<div class="d-flex">
			        <h1 class="modal-title fs-5" id="modal_getpesoLabel"></h1>
			        <h5 class="modal-title fs-5" id="lbl_titulogetpeso" style="margin-left: 5px; color: #337ab7;"></h5>
			        <h5 class="modal-title fs-5" id="lbl_titulogetpeso_item" style="color: #FF5F5D;"></h5>
			      </div>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="padding: 5px; margin-left: 5px;">
							<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 25px;">
								<input id="txt_getpeso" type="number" class="form-control" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #F2AA52; height: 45px;">
							</div>

							<div id="div_SinConexion" class="row" style="text-align: right; display: none;">
                <label class="control-label" style="color: #d9534f; font-size: 12px;">
                  Esperando peso...
                </label>
              </div>
						</div>

						<div class="row" style="padding-left: 35px;">
							<div class="form-check form-switch">
							  <input class="form-check-input" type="checkbox" role="switch" id="chk_MuestrasReplicaSelectAll" onchange="f_GetPeso_Auto();" checked>
							  <label id="lbl_getpeso_check" class="form-check-label" for="chk_MuestrasReplicaSelectAll">Automático</label>
							</div>
						</div>
		      </div>

		      <input id="getpeso_isbuscarmuestra" type="hidden">
		      <input id="getpeso_ordenpeso" type="hidden">
		      <input id="getpeso_ordenitem" type="hidden">
		      <input id="getpeso_item" type="hidden">
		      <input id="getpeso_iddetalle" type="hidden">
		      <input id="getpeso_update" type="hidden">

		      <div class="modal-footer">
		        <button id="btn_addmuestra_cerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="f_GetPesoCerrar();" >Cerrar</button>
		        <button class="btn btn-warning" type="button" onclick="f_GuardarPeso();" style="font-size: 14px;">Confirmar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_racksecado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_racksecadoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		      	<div class="d-flex">
			        <h1 class="modal-title fs-5" id="modal_racksecadoLabel"></h1>
			        <h5 class="modal-title fs-5" id="lbl_tituloracksecado" style="margin-left: 5px; color: #337ab7;"></h5>
			      </div>
		      </div>
		      <div class="modal-body">
		      	<div class="d-flex" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div style="padding: 5px; text-align: left; width: 150px;">
								Inicio:
							</div>

							<div class="d-flex">
								<div class="col-md-7 col-sm-7 col-xs-7">
									<input id="secado_fechainicio" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center; width: 95%;" onchange="f_SecadoSetTime();">
								</div>

								<div class="col-md-5 col-sm-5 col-xs-5">
									<input id="secado_horainicio" type="time" class="form-control col-md-12 col-xs-12" style="text-align: center;" onchange="f_SecadoSetTime();">
								</div>
							</div>
						</div>

						<div class="d-flex" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div style="padding: 5px; text-align: left; width: 150px;">
								Fin Programado:
							</div>

							<div class="d-flex">
								<div class="col-md-7 col-sm-7 col-xs-7">
									<input id="secado_fechafin_programado" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center; width: 95%;" disabled>
								</div>

								<div class="col-md-5 col-sm-5 col-xs-5">
									<input id="secado_horafin_programado" type="time" class="form-control col-md-12 col-xs-12" style="text-align: center;" disabled>
								</div>
							</div>
						</div>

						<div id="div_racksecado_finreal" class="d-flex" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px; display: none !important;">
							<div style="padding: 5px; text-align: left; width: 150px;">
								Fin Real:
							</div>

							<div class="d-flex">
								<div class="col-md-7 col-sm-7 col-xs-7">
									<input id="secado_fechafin_real" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center; width: 95%;">
								</div>

								<div class="col-md-5 col-sm-5 col-xs-5">
									<input id="secado_horafin_real" type="time" class="form-control col-md-12 col-xs-12" style="text-align: center;">
								</div>
							</div>
						</div>
		      </div>

		      <input id="racksecado_isiniciosecado" type="hidden">

		      <div class="modal-footer">
		        <button id="btn_addmuestra_cerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="f_GetPesoCerrar();" >Cerrar</button>
		        <button class="btn btn-warning" type="button" onclick="f_ConfirmarSecado();" style="font-size: 14px;">Confirmar</button>
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
					$("#nv_titulo").html('| LQ - Análisis de Humedad');

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
      		if (fecha_inicio == null){
            alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

            return;
	        }
	        if (fecha_inicio.length == 0){
            alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

            return;
	        }

	        if (fecha_fin == null){
            alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

            return;
	        }
	        if (fecha_fin.length == 0){
            alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

            return;
	        }

	        if (fecha_fin < fecha_inicio){
            alert('La fecha "Desde" no puede ser mayor a la fecha "Hasta".\nPor favor, verificar.');

            return;
        	}

				// Cargando Lista de Racks
	        $("#tbl_racks").html('');
	        $("#tbl_detalle").html('');
	        $("#lbl_titulomuestras").html('');

	        f_SetButtons(0);

	        f_LoadingRacks(1);

	        $.post( "apis/backend.php", { accion: "get_AnalisisHumedad_Racks", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_estado: filtro_estado, cod_interno: cod_interno }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_racks").html(data.html);

	            	itemrack_Selected = 1;
								idrack_Selected = data.id_rack;
								rack_tieneiniciosecado_selected = data.tiene_iniciosecado;
								rack_tienefinsecado_selected= data.tiene_finsecado;
								rack_horassecado_selected = data.horas_secado;

								f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingRacks(0);

	          }, "json");

        // Cargando Muestras Pendientes de análisis de Humedad
	        $("#tbl_pendientes").html('');
	        $("#lbl_totalpendientes").html(0);
					$("#lbl_totalpendientesLQ").html(0);

	        f_LoadingListaPendientes(1);

	        $.post( "apis/backend.php", { accion: "get_AnalisisHumedad_PendientesAnalisis" }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_pendientes").html(data.html);

	            	$("#lbl_totalpendientes").html(data.total_pendientes);
								$("#lbl_totalpendientesLQ").html(data.total_pendientesLQ);
	            }

	            f_LoadingListaPendientes(0);

	          }, "json");

    	};

      function f_AdminRack(_modo, _item, _id_rack, _fecha_rack, _hora_rack, _nombre_rack, _estufa_rack, _horas_secado, _observacion){
        // Registrando el modo
          $("#modo_grabarrack").val(_modo);
          $("#id_rack").val(_id_rack);
          $("#item_rack").val(_item);

        // Colocando Títulos
          if (_modo == 'N'){
            $("#modal_adminracksLabel").html('Nuevo Rack');
          }
          else{
            $("#modal_adminracksLabel").html('Editar Rack');
          }

        // Cargando datos
          if (_modo != 'N'){
            $("#fecha_rack").val(_fecha_rack);
						$("#hora_rack").val(_hora_rack);
						$("#nombre_rack").val(_nombre_rack);
            $("#estufa_rack").val(_estufa_rack);
            $("#horassecado_rack").val(_horas_secado);
            $("#observacion_rack").val(_observacion);
          }
          else{
          	var _time = new Date();
          	_time = _time.getHours().toString().padStart(2, '0') + ":" + _time.getMinutes().toString().padStart(2, '0');

            $("#fecha_rack").val('<?php echo $g_date; ?>');
						$("#hora_rack").val(_time);
            $("#nombre_rack").val('');
            $("#estufa_rack").val(1);
            $("#horassecado_rack").val(4);
            $("#observacion_rack").val('');
          }

        // Abre modal
        	f_OpenModal('modal_adminracks');
      };

    	$("#barcode_lectura").keyup(function (e) {
		    if (e.keyCode === 13) {
					f_LoadRacks();
		    }
		  });

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

      function f_LoadItemHumedad(_item, _id_rack, _tiene_iniciosecado, _tiene_finsecado, _horas_secado){
        var _html = '';

        // Pinta selección
          f_ColorSelected(_item);

        // Inhabilita los botones
          f_SetButtons(0);

        // Limpia objetos de búsqueda
          $("#th_buscarmuestra_1").val('');
          $("#th_buscarmuestra_2").val('');
          $("#th_buscarmuestra_3").val('');

        // Cargando datos
          f_LoadingDetalle(1);

          $("#tbl_detalle").html(_html);

          $.post( "apis/backend.php", { accion: "Get_LQHumedad_AnalisisDatos", id_rack: _id_rack }, 
            function( data ) {
              if(data.estado == 1){
                // Actualiza la tabla de Muestras
                  $("#tbl_detalle").html(data.html);

                  // Seteando botones
                  	f_SetButtons(1);

                  	// Verifica si el registro de pesos está completo
          						f_ValidaRegistroPesosCompleto();

					          if (_tiene_iniciosecado == 1){
					          	f_SetButtons(3);
					          }

					          if (_tiene_finsecado == 1){
					          	f_SetButtons(0);

					          	// Verifica si el registro de pesos está completo
          							f_ValidaRegistroPesosCompleto();

          						// Posiciona el foco en el buscador de Peso Seco + Tara
          							document.getElementById("th_buscarmuestra_3").focus();
					          }

					        // Validando si el Rack está cerrado
				        		if ($("#td_israckcerrado_" + itemrack_Selected).val() == 1){
				        			f_SetButtons(0);
				        		}
              }
              else{
                f_SetButtons(1);
              }

              f_LoadingDetalle(0);

            }, "json");

        itemrack_Selected = _item;
        idrack_Selected = _id_rack;
        rack_tienefinsecado_selected = _tiene_iniciosecado;
				rack_tieneiniciosecado_selected = _tiene_finsecado;
				rack_horassecado_selected = _horas_secado;
      };

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

		  	$.post( "apis/backend.php", { accion: "get_AnalisisHumedad_AddMuestra", cod_interno: bar_code, id_rack: idrack_Selected },
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
								var _item = bar_code.substring(bar_code.length - 1).toUpperCase();
								var _num_item = ((_item == 'A') ? 1 : 2);
								var _cod_interno = bar_code.substring(0, bar_code.length - 1) ;

								f_GetPeso_Show(1, 1, _num_item, _item, $("#id_buscarmuestra_" + _num_item).val(), _cod_interno);
            }
            else{
            	if(data.estado == 0){
            		alert("Ocurrió un error al momento de ingresar la muestra.");
            	}

              if(data.estado == 2){
              	alert("La Muestra no se encuentra en la B.D.");
              }

              if(data.estado == 3){
              	alert("No se ha solicitado análisis de Humedad para La Muestra ingresada.");
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
				  			$.post( "apis/backend.php", { accion: "get_AnalisisHumedad_AddMuestra_Reanalisis", cod_interno: bar_code, id_rack: idrack_Selected, id_cabecera: id_cabecera_x },
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

											// Alista la lectura del Peso
												var _item = bar_code.substring(bar_code.length - 1).toUpperCase();
												var _num_item = ((_item == 'A') ? 1 : 2);
												var _cod_interno = bar_code.substring(0, bar_code.length - 1) ;

												f_GetPeso_Show(1, 1, _num_item, _item, $("#id_buscarmuestra_" + _num_item).val(), _cod_interno);
										}

									}, "json");
				  		}
				  		else{
				  			// $("#addmuestra_barcode").val('');
				  		}

				  	f_LoadingBuscarMuestra(0);

          }, "json");
		  }

		  function f_GetPeso_Show(_is_buscarmuestra, _orden_peso, _orden_item, _item, _id_detalle, _cod_interno, _update){
		  	// Colocando Títulos
		  		var _titulo = '';

		  		if (_orden_peso == 1){
		  			_titulo = 'Tara (Peso Bandeja)';
		  		}

		  		if (_orden_peso == 2){
		  			_titulo = 'Peso Húmedo';
		  		}

		  		if (_orden_peso == 3){
		  			_titulo = 'Peso Seco + Tara';
		  		}

		  		$("#modal_getpesoLabel").html(_titulo + ': ');
          $("#lbl_titulogetpeso").html(_cod_interno);
          $("#lbl_titulogetpeso_item").html(_item.toUpperCase());

        // Setea objetos
          $("#txt_getpeso").val('');
          $("#chk_MuestrasReplicaSelectAll").prop('checked', true);
          $("#lbl_getpeso_check").html('Automático');

        // Asignando valores a objetos hidden
          $("#getpeso_isbuscarmuestra").val(_is_buscarmuestra);
					$("#getpeso_ordenpeso").val(_orden_peso);
					$("#getpeso_ordenitem").val(_orden_item);
					$("#getpeso_item").val(_item);
					$("#getpeso_iddetalle").val(_id_detalle);
					$("#getpeso_update").val(((_update == 1) ? _update : 0));

        // Abre modal
        	f_OpenModal('modal_getpeso');

        	f_GetPeso(1);
		  }

		  function f_GetPeso(_on){
				if (_on == 1){
					$.get( "apis/interfaces.php", { accion: "get_Peso", cod_balanza: 2 }, 
	          function( data ) {
	            if(data.estado == 1){
	              if (data.peso == -1){
	                $("#div_SinConexion").show();

	                $("#txt_getpeso").val('');
	              }
	              else{
	                $("#div_SinConexion").hide();

	                $("#txt_getpeso").val(data.peso);
	              }
	            }
	            else{
	              $("#div_SinConexion").show();
	            }

	            // Verifica si es Automático o Manual
	            	if ($("#chk_MuestrasReplicaSelectAll").prop('checked')){
	            		setTimeout('f_GetPeso(1)', 1000);
	            	}
	            	else{
	            		$("#div_SinConexion").hide();
	            	}

	          }, "json");
				}
			}

		  function f_GetPeso_Auto(){
		  	var is_auto = (($("#chk_MuestrasReplicaSelectAll").prop('checked')) ? 1 : 0);

		  	if (is_auto == 1){
		  		$('#lbl_getpeso_check').html('Automático');

		  		f_GetPeso(1);
		  	}
		  	else{
		  		f_BreakAutomatico();
		  	}
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
						$("#th_addmuestra_2").val('');

						document.getElementById("addmuestra_barcode").focus();
		  	}
		  }

		  function f_AddMuestraConfirmar(_x){
		  	// Recorriendo tabla y validando si hay pesos pendientes
		  		var r = 1;
		  		var p = 1;
		  		var _ok = 1;

		  		$('#tbl_addmuestras tr').each(function () {
		  			p = 1;

						while (p <= 2){
							if ($("#td_buscarmuestra_getpeso_" + p + "_" + r).html().indexOf("button") > -1){
								alert("Aún tiene Pesos por registrar.\nNo puede continuar.");

								r = 99;

								_ok = 0;
							}

							p ++;
						}

						r ++;
	        });

        // Confirmando el registro de pesos
		  		if (_ok == 1){
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
							$("#th_addmuestra_2").val('');

							document.getElementById("addmuestra_barcode").focus();

			  		f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
		  		}
		  }

		  function f_GetPesoCerrar(){
		  	f_BreakAutomatico();
		  }

		  function f_BreakAutomatico(){
		  	$("#chk_MuestrasReplicaSelectAll").prop('checked', false);

	  		$("#div_SinConexion").hide();

	  		f_GetPeso(0);
		  }

		  function f_ValidaRegistroPesosCompleto(){
		  	var r = 1;
		  	var p = 1;
		  	var _ok = 1;

		  	// Recorre la tabla buscando botones habilitados, solo para Peso de Bandeja (Tara) y Peso Húmedo
			  	$('#tbl_detalle tr').each(function () {
		  			p = 1;

						while (p <= 3){
							if ($("#td_analisismuestra_getpeso_" + p + "_" + r).html() != undefined){
								if ($("#td_analisismuestra_getpeso_" + p + "_" + r).html().indexOf("button") > -1){
									r = 999;

									_ok = 0;
								}
							}

							p ++;
						}

						r ++;
	        });

		  	// Valida si el registro de pesos está completo
			  	if (_ok == 1){
			  		if (rack_tienefinsecado_selected != 1){
			  			f_SetButtons(2);
			  		}
			  		else{
			  			f_SetButtons(5);
			  		}
			  	}
		  }

		  function f_GestionSecado(_x){
		  	var _titulo = '';
		  	var _nombre_rack = $("#lbl_td_1_" + itemrack_Selected).html();

		  	// Seteando título
		  		if (_x == 1){
		  			_titulo = 'Inicio de Secado para: ';

		  			$("#racksecado_isiniciosecado").val(1);
		  		}
		  		if (_x == 2){
		  			_titulo = 'Fin de Secado para: ';

		  			$("#racksecado_isiniciosecado").val(0);
		  		}

		  		$("#modal_racksecadoLabel").html(_titulo);
		  		$("#lbl_tituloracksecado").html($("#lbl_td_1_" + itemrack_Selected).html().trim());

		  	// Seteando fecha y hora
					var _time = new Date();
	        _time = _time.getHours().toString().padStart(2, '0') + ":" + _time.getMinutes().toString().padStart(2, '0');

					if (_x == 1){
						$("#secado_fechainicio").val('<?php echo $g_date; ?>');
						$("#secado_horainicio").val(_time);

						$("#secado_fechafin_programado").val('<?php echo $g_date; ?>');
						$("#secado_horafin_programado").val(_time);

						f_SecadoSetTime();

						$("#div_racksecado_finreal").attr('style', 'display: none !important');
					}

					if (_x == 2){
						// Seteando datos de inicio de secado
							var _fecha_iniciosecado = $("#td_5_" + itemrack_Selected).html().trim();
							_fecha_iniciosecado = _fecha_iniciosecado.substring(0, 10);

							var _hora_iniciosecado = $("#td_5_" + itemrack_Selected).html().trim();
							_hora_iniciosecado = _hora_iniciosecado.substring(_hora_iniciosecado.length - 5);

							var _fecha_finprogramado = $("#td_6_" + itemrack_Selected).html().trim();
							_fecha_finprogramado = _fecha_finprogramado.substring(0, 10);

							var _hora_finprogramado = $("#td_6_" + itemrack_Selected).html().trim();
							_hora_finprogramado = _hora_finprogramado.substring(_hora_finprogramado.length - 5);

							$("#secado_fechainicio").val(_fecha_iniciosecado);
							$("#secado_horainicio").val(_hora_iniciosecado);

							$("#secado_fechafin_programado").val(_fecha_finprogramado);
							$("#secado_horafin_programado").val(_hora_finprogramado);

							f_SecadoSetTime();

						// Seteando daros de fin de secado real
							$("#secado_fechafin_real").val('<?php echo $g_date; ?>');
							$("#secado_horafin_real").val(_time);

						$("#div_racksecado_finreal").attr('style', 'display: block; margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;');
					}

		  	// Abre modal
        	f_OpenModal('modal_racksecado');
		  }

		  function f_SecadoSetTime(){
		  	// Sumando Horas de Secado
			  	var _inicio = $("#secado_fechainicio").val() + ' ' + $("#secado_horainicio").val().substring(0, 5);
			  	_inicio = new Date(_inicio);
			  	_inicio = _inicio.getTime() + ((rack_horassecado_selected * 60) * 60000);
			  	_inicio = new Date(_inicio);

		  	// Armando Fecha
			  	var _fecha = _inicio.getFullYear() + '-' +
			  							 (_inicio.getMonth() + 1).toString().padStart(2, '0') + '-' +
			  							 _inicio.getDate().toString().padStart(2, '0');

			  	var _hora = _inicio.getHours().toString().padStart(2, '0') + ':' +
			  							_inicio.getMinutes().toString().padStart(2, '0');

			  	$("#secado_fechafin_programado").val(_fecha);
			  	$("#secado_horafin_programado").val(_hora);

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
				var orden_peso = _id.substring(_id.length - 1);
				var item = cod_interno_x.substring(cod_interno_x.length - 1);
				var orden_item = $("#td_CI_" + cod_interno_x).val();

				cod_interno_x = cod_interno_x.substring(0, cod_interno_x.length - 1);

				// Limpia objetos de búsqueda
					$("#th_buscarmuestra_1").val('');
					$("#th_buscarmuestra_2").val('');
					$("#th_buscarmuestra_3").val('');

					$("#th_buscarmuestra_" + orden_peso).val(cod_interno_x + item);

				// Buscar Código Interno en Rack
					$.post( "apis/backend.php", { accion: "get_AnalisisHumedad_BuscarMuestraRack", id_rack: idrack_Selected, orden_peso: orden_peso, cod_interno: cod_interno_x, item: item }, 
	          function( data ) {
	            if(data.estado == 1){
	            	if (data.tiene_campopeso == 0){
	            		if (data.tiene_campopeso_prev == 99){
	            			f_GetPeso_Show(0, orden_peso, orden_item, item, data.id_analisis, cod_interno_x);
	            		}
	            		else{
	            			// Definiendo Campo Previo
		            			var campo = '';

		            			if (orden_peso == 2){
		            				campo = 'Tara (Peso Bandeja)';
		            			}

		            			if (orden_peso == 3){
		            				campo = 'Peso Húmedo';
		            			}

	            			if (data.tiene_campopeso_prev == 0){
	            				alert("Primero debe registrar el peso para: " + campo + ".");
	            			}
	            			else{
	            				if (orden_peso != 3){
	            					f_GetPeso_Show(0, orden_peso, orden_item, item, data.id_analisis, cod_interno_x);
	            				}
	            				else{
	            					if (data.tiene_finsecado == 0){
	            						alert("Aún no ha realizado el Fin de Secado.");

	            						return;
	            					}
	            					else{
	            						f_GetPeso_Show(0, orden_peso, orden_item, item, data.id_analisis, cod_interno_x);
	            					}
	            				}
	            			}
	            		}
	            	}
	            	else{
	            		// Definiendo Campo
	            			var campo = '';

	            			if (orden_peso == 1){
	            				campo = 'Tara (Peso Bandeja)';
	            			}

	            			if (orden_peso == 2){
	            				campo = 'Peso Húmedo';
	            			}

	            			if (orden_peso == 3){
	            				campo = 'Peso Seco + Tara';
	            			}

	            		// alert("La muestra ingresada ya tiene un peso registrado para: " + campo + ".");

	            			if (!confirm("La muestra ingresada ya tiene un peso registrado para: " + campo + ".\n\n¿Desea modificarlo?")){
	            				return;
	            			}
	            			else{
	            				f_GetPeso_Show(0, orden_peso, orden_item, item, data.id_analisis, cod_interno_x, 1);
	            			}
	            	}
	            }
	            else{
	              alert("La muestra no se encuentra en este Rack.");

	              $("#th_buscarmuestra_1").val('');
								$("#th_buscarmuestra_2").val('');
								$("#th_buscarmuestra_3").val('');
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
				var orden_peso = _id.substring(_id.length - 1);
				var item = cod_interno_x.substring(cod_interno_x.length - 1);
				var orden_item = ((item == 'A') ? 1 : 2);
				var id_analisis = $("#id_buscarmuestra_" + orden_item).val();

				cod_interno_x = cod_interno_x.substring(0, cod_interno_x.length - 1);

				// Limpia objetos de búsqueda
					$("#th_addmuestra_1").val('');
					$("#th_addmuestra_2").val('');

					$("#th_addmuestra_" + orden_peso).val(cod_interno_x + item);

				// Validando que el C.I. sea el mismo que se ha buscado
					if (cod_interno_x != $("#td_buscarmuestra_1_1").html().trim().substring(0, 11))
					{
						alert("La muestra buscada no corresponde a la muestra que tiene en pantalla.\nPor favor, verificar.");

						return;
					}

				// Validando si tiene peso previo
					var _html = '';
					var _html_prev = '';

					_html = $("#td_buscarmuestra_getpeso_" + orden_peso + "_" + orden_item);
					_html_prev = $("#td_buscarmuestra_getpeso_" + (orden_peso - 1) + "_" + orden_item);

					if (orden_peso == 1){
						if (_html.html().indexOf('<button') == -1){
							alert("La muestra leída ya tiene un peso registrado.");

							$("#th_addmuestra_1").val('');

							return;
						}
					}

					if (orden_peso == 2){
						if (_html_prev.html().indexOf('<button') > -1){
							alert("Primero debe registrar el peso para: Tara (Peso Bandeja).");

							return;
						}

						if (_html.html().indexOf('<button') == -1){
							alert("La muestra leída ya tiene un peso registrado.");

							$("#th_addmuestra_2").val('');

							return;
						}
					}

				// Abriendo pantalla de Registro de Pesos
					f_GetPeso_Show(1, orden_peso, orden_item, item, id_analisis, cod_interno_x);
			}

			function f_LoadingRacks(_is_show){
				if (_is_show == 1){
					$("#wt_rack").show();
				}
				else{
					$("#wt_rack").hide();
				}
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

				$("#btn_iniciosecado").prop('disabled', true);
				$("#btn_iniciosecado").css('background-color', '#BBBBBB');
				$("#btn_iniciosecado").css('color', '#ffffff');
				$("#btn_iniciosecado").removeClass('btn-primary');
				$("#btn_iniciosecado").addClass('btn-secondary');

				$("#btn_finsecado").prop('disabled', true);
				$("#btn_finsecado").css('background-color', '#BBBBBB');
				$("#btn_finsecado").css('color', '#ffffff');
				$("#btn_finsecado").removeClass('btn-primary');
				$("#btn_finsecado").addClass('btn-secondary');

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

				if (_x == 2){
					$("#btn_addmuestras").prop('disabled', false);
					$("#btn_addmuestras").css('background-color', '');
					$("#btn_addmuestras").css('color', '');
					$("#btn_addmuestras").removeClass('btn-secondary');
					$("#btn_addmuestras").addClass('btn-primary');

					$("#btn_iniciosecado").prop('disabled', false);
					$("#btn_iniciosecado").css('background-color', '');
					$("#btn_iniciosecado").css('color', '');
					$("#btn_iniciosecado").removeClass('btn-secondary');
					$("#btn_iniciosecado").addClass('btn-primary');
				}

				if (_x == 3){
					$("#btn_finsecado").prop('disabled', false);
					$("#btn_finsecado").css('background-color', '');
					$("#btn_finsecado").css('color', '');
					$("#btn_finsecado").removeClass('btn-secondary');
					$("#btn_finsecado").addClass('btn-primary');
				}

				if (_x == 4){

				}

				if (_x == 5){
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

			function f_LoadingDetalle(_is_show){
				if (_is_show == 1){
					$("#wt_detalle").show();
				}
				else{
					$("#wt_detalle").hide();
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

			$("#modal_addmuestras").on('shown.bs.modal', function(){
      	$("#addmuestra_barcode").focus();
    	});

    	$("#modal_getpeso").on('shown.bs.modal', function(){
      	$("#txt_getpeso").focus();
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
			function f_GrabarRack(){
        var _id_rack = $("#id_rack").val();
        var _item_rack = $("#item_rack").val();
        var _modo = $("#modo_grabarrack").val();

        var _fecha_rack = $("#fecha_rack").val();
        var _hora_rack = $("#hora_rack").val();
        var _registro_rack = _fecha_rack + ' ' + _hora_rack;
        var _nombre_rack = $("#nombre_rack").val().trim().toUpperCase();
				var _estufa_rack = $("#estufa_rack").val().trim().toUpperCase();
				var _estufa_rack_des = $("#estufa_rack option:selected").text().trim().toUpperCase();
        var _horas_secado = $("#horassecado_rack").val();
        var _observacion = $("#observacion_rack").val().trim().toUpperCase();
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

          if (_estufa_rack == null){
            alert("Debe seleccionar la Estufa.");

            return;
          }
          if (_estufa_rack.length == 0){
            alert("Debe seleccionar la Estufa.");

            return;
          }

          if (_horas_secado == null){
            alert("Debe ingresar las Horas de Secado.");

            return;
          }
          if (_horas_secado.length == 0){
            alert("Debe ingresar las Horas de Secado.");

            return;
          }
          if (_horas_secado < 3){
            alert("La Hora de Secado no puede ser menor a 3.");

            return;
          }
          if (_horas_secado > 8){
            alert("La Hora de Secado no puede ser mayor a 8.");

            return;
          }

        // Grabando datos
          $.post( "apis/backend.php", { accion: "grabar_AnalisisHumedad_Racks", modo: _modo, id_rack: _id_rack, registro_rack: _registro_rack, nombre_rack: _nombre_rack, estufa_rack: _estufa_rack, horas_secado: _horas_secado, observacion: _observacion },
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
                      _html += '<tr id="tr_item_' + item_rack + '" style="cursor: pointer; font-size: 13px;" onclick="f_LoadItemHumedad(' + item_rack + ', ' + _id_rack + ', 0, 0)">';

                      _html += '  <td id="td_item_1_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '    ' + item_rack;
                      _html += '  </td>';

                      _html += '  <td id="td_item_2_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 14px;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_AdminRack(' + "'M', " + item_rack + ', ' + _id_rack + ", '" + _fecha_rack + "', '" + _hora_rack + "', '" + _nombre_rack + "', " + _estufa_rack + ', ' + _horas_secado + ", '" + _observacion + "'" + ');">';
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
                      _html += '    ' + _estufa_rack_des;
                      _html += '  </td>';

                      _html += '  <td id="td_3_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '      ' + _horas_secado;
                      _html += '  </td>';

                      _html += '  <td id="td_4_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '      ' + _observacion;
                      _html += '  </td>';

                      _html += '  <td id="td_5_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; color: #FF5F5D;">';
                      _html += '      Pendiente';
                      _html += '  </td>';

                      _html += '  <td id="td_6_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; color: #FF5F5D;">';
                      _html += '      Pendiente';
                      _html += '  </td>';

                      _html += '  <td id="td_7_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; color: #FF5F5D;">';
                      _html += '      Pendiente';
                      _html += '  </td>';

                      _html += '</tr>';

                    $("#tbl_racks").html(_html);

                    f_ColorSelected(item_rack);

                  	// Actualiza variables
                  		itemrack_Selected = item_rack;
                  		idrack_Selected = _id_rack;
                  		rack_tieneiniciosecado_selected = 0;
                  		rack_tienefinsecado_selected = 0;
                  		rack_horassecado_selected = _horas_secado;
                  }

                // Actualiza el Rack seleccionado
                  if (_modo == 'M'){
                  	var _html_x = '';

                  	// td_item_2
                  		_html_x = '			<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_AdminRack(' + "'M', " + item_rack + ', ' + _id_rack + ", '" + _fecha_rack + "', '" + _hora_rack + "', '" + _nombre_rack + "', " + _estufa_rack + ', ' + _horas_secado + ", '" + _observacion + "'" + ');">';
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

                    $("#td_2_" + _item_rack).html(_estufa_rack_des);
                    $("#td_3_" + _item_rack).html(_horas_secado);
                    $("#td_4_" + _item_rack).html(_observacion);
                  }

                f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
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
      		var _muestra = $("#td_analisismuestra_3_" + _item).html().trim();

      		if(!confirm("¿Está seguro de eliminar la muestra seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
      			return;
      		}
      	}

        $.post( "apis/backend.php", { accion: "eliminar_AnalisisHumedad_MuestraRack", id_cabecera: _id_cabecera },
          function( data ) {
            if(data.estado == 1){
              f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
            }
            else{
              alert("Ocurrió un error al momento de eliminar el Modelo.");
            }

          }, "json");
      };

      function f_GuardarPeso(){
      	var _is_buscarmuestra = $("#getpeso_isbuscarmuestra").val();
				var _orden_peso = $("#getpeso_ordenpeso").val();
				var _orden_item = $("#getpeso_ordenitem").val();
				var _item = $("#getpeso_item").val();
				var _id_detalle = $("#getpeso_iddetalle").val();
				var _is_update = $("#getpeso_update").val();
				var _peso = $("#txt_getpeso").val();
				var _cod_interno = $("#addmuestra_barcode").val().trim().substring(0, $("#addmuestra_barcode").val().trim().length - 1);

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

        // Grabando datos
        	$.post( "apis/backend.php", { accion: "guardar_PesoHumedad", id_detalle: _id_detalle, orden_peso: _orden_peso, peso: _peso },
	          function( data ) {
	            if(data.estado == 1){
	            	if (_is_update == 1){
	            		f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);

	            		f_cerrarModal("modal_getpeso");

	            		return;
	            	}

	            	if (_orden_peso == 1){
	            		$("#modal_getpesoLabel").html('Peso Húmedo: ');

					        // Setea objetos
					          $("#txt_getpeso").val('');

				          if (_is_buscarmuestra == 1){
				          	$("#td_buscarmuestra_getpeso_1_" + _orden_item).html(_peso);
				          	$("#td_buscarmuestra_getpeso_2_" + _orden_item).html('<button class="btn btn-info" type="button" onclick="f_GetPeso_Show(' + _is_buscarmuestra + ', 2, ' + _orden_item + ", '" + _item + "'" + ', ' + _id_detalle + ", '" + _cod_interno + "'" + ');" style="width: 100%; color: #ffffff; font-size: 14px;">Pesar</button>');

				          	$("#th_addmuestra_1").val('');
										$("#th_addmuestra_2").val('');

				          	// Agrega el Focus
											document.getElementById("txt_getpeso").focus();
				          }
				          else{
				          	$("#td_analisismuestra_getpeso_1_" + _orden_item).html(_peso);
				          	$("#td_analisismuestra_getpeso_2_" + _orden_item).html('<button class="btn btn-info" type="button" onclick="f_GetPeso_Show(' + _is_buscarmuestra + ', 2, ' + _orden_item + ", '" + _item + "'" + ', ' + _id_detalle + ", '" + _cod_interno + "'" + ');" style="width: 100%; color: #ffffff; font-size: 14px;">Pesar</button>');

				          	f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
				          }

					        // Asignando valores a objetos hidden
					          $("#getpeso_isbuscarmuestra").val(_is_buscarmuestra);
										$("#getpeso_ordenpeso").val(2);
										$("#getpeso_ordenitem").val(_orden_item);
										$("#getpeso_item").val(_item);
										$("#getpeso_iddetalle").val(_id_detalle);
	            	}

	            	if (_orden_peso == 2){
	            		if (_is_buscarmuestra == 1){
		            		// Setea objetos
						          $("#td_buscarmuestra_getpeso_2_" + _orden_item).html(_peso);

						          $("#th_addmuestra_1").val('');
											$("#th_addmuestra_2").val('');
						      }
						      else{
						      	f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
						      }

						      f_cerrarModal("modal_getpeso");

					        f_BreakAutomatico();

					        // Agrega el Focus en el primer buscador
										document.getElementById("th_addmuestra_1").focus();
	            	}

	            	if (_orden_peso == 3){
	            		// Setea objetos
						        $("#td_buscarmuestra_getpeso_3_" + _orden_item).html(_peso);

						      // Actualizar el Click del Rack seleccionado
				        		$("#tr_item_" + itemrack_Selected).attr("onclick", 'f_LoadItemHumedad(' + itemrack_Selected + ", " + idrack_Selected + ", 1, 1, " + rack_horassecado_selected + ');');

						      f_LoadItemHumedad(itemrack_Selected, idrack_Selected, 1, 1, rack_horassecado_selected);

						      f_cerrarModal("modal_getpeso");

					        f_BreakAutomatico();

					        // Agrega el Focus en el tercer buscador
										document.getElementById("th_buscarmuestra_3").focus();
	            	}
	            }
	            else{
	              alert("Ocurrió un error al momento de eliminar el Modelo.");
	            }

	          }, "json");
      }

      function f_ConfirmarSecado(){
      	var _is_iniciosecado = $("#racksecado_isiniciosecado").val();
      	var _is_finsecado = 0;
      	var inicio_secado = '';
      	var fin_programado = '';
      	var fin_real = '';

      	inicio_secado = $("#secado_fechainicio").val() + ' ' + $("#secado_horainicio").val();
      	fin_programado = $("#secado_fechafin_programado").val() + ' ' + $("#secado_horafin_programado").val();

      	if (_is_iniciosecado == 0){
      		fin_real = $("#secado_fechafin_real").val() + ' ' + $("#secado_horafin_real").val();

      		// Validando fechas
      			if (fin_real <= inicio_secado){
      				alert("La Fecha y Hora Real no puede ser menor o igual al Inicio de Secado.");

      				return;
      			}

      			if (fin_real <= fin_programado){
      				if (!confirm("La Fecha y Hora Real es menor o igual al Fin Programado.\n\n¿Está seguro de continuar?")){
      					return;
      				}
      			}
      	}

        // Grabando datos
        	$.post( "apis/backend.php", { accion: "confirmar_AnalisisHumedad_Secado", is_iniciosecado: _is_iniciosecado, id_rack: idrack_Selected, inicio_secado: inicio_secado, fin_programado: fin_programado, fin_real: fin_real },
	          function( data ) {
	            if(data.estado == 1){
	            	// Actualizando los campos de Secado del Rack
	            		if (_is_iniciosecado == 1){
		            		$("#td_5_" + itemrack_Selected).html(inicio_secado);
		            		$("#td_6_" + itemrack_Selected).html(fin_programado);
		            	}
		            	else{
		            		$("#td_7_" + itemrack_Selected).html(fin_real);
		            	}

	            	// Define inicio o fin de secado
	            		if (_is_iniciosecado == 0){
	            			_is_finsecado = 1;
	            		}

	            	// Cargando nuevamente los datos de análisis
	            		f_LoadItemHumedad(itemrack_Selected, idrack_Selected, _is_iniciosecado, _is_finsecado, rack_horassecado_selected);

	            	// Cambiando el evento click del Rack seleccionado
	            		$("#tr_item_" + itemrack_Selected).attr("onclick", 'f_LoadItemHumedad(' + itemrack_Selected + ", " + idrack_Selected + ", " + _is_iniciosecado + ", " + _is_finsecado + ", " + rack_horassecado_selected + ');');

	            	f_cerrarModal('modal_racksecado');
	            }
	            else{
	              alert("Ocurrió un error al momento de confirmar el Secado.");
	            }

	          }, "json");
      }

      function f_CerrarRack(){
      	if (!confirm("¿Está seguro de cerrar el Rack seleccionado?")){
      		return;
      	}

      	// Guardando datos
      		$.post( "apis/backend.php", { accion: "cerrar_AnalisisHumedad", id_rack: idrack_Selected },
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