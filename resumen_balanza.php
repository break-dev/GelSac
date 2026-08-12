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

		<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Resumen de Balanza</title>

		<script type="text/javascript">
			var is_mobile = 0;
			var color_selected = '';
		</script>

		<style>
			.table-container{
				max-width: 100%;
				overflow-x: scroll;
			}

			.sticky{
				position: sticky;
				left: 0;
				z-index: 1000;
			}

			.sticky-2{
				position: sticky;
				left: 35;
				z-index: 1000;
			}

			.sticky-3{
				position: sticky;
				left: 35;
				z-index: 1000;
			}

			.sticky-4{
				position: sticky;
				left: 140;
				z-index: 1000;
			}

			.sticky-5{
				position: sticky;
				left: 270;
				z-index: 1000;
			}

			.sticky-2h{
				position: sticky;
				left: 35;
				z-index: 1000;
			}

			.sticky-3h{
				position: sticky;
				left: 140;
				z-index: 1000;
			}

			.sticky-4h{
				position: sticky;
				left: 270;
				z-index: 1000;
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

	      <!-- Modal (Filtro Lateral) -->
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
												<h6 style="font-size: 14px;">Por Fechas</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="row" >

												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltros();">
												</div>
												<br><br>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_fin" type="date" class="form-control" style="text-align: center;  font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltros();">
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Condición Ingreso:</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_condicioningreso" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
													<option selected value="99">Elija una opción...</option>

													<?php

													$html = '';

													$q_datos = "SELECT Id,
																						 descripcion
																				FROM tbconfig_tipoingresounidades
																			 WHERE consolidado_resumenbalanza = 1
																			ORDER BY is_predeterminado DESC, descripcion";

														if ($res_datos = mysqli_query($enlace, $q_datos)){
															if (mysqli_num_rows($res_datos) > 0) {
																while($row_datos = mysqli_fetch_array($res_datos)){
																	?>
																	
																	<option value="<?php echo $row_datos["Id"] ?>"><?php echo $row_datos["descripcion"] ?></option>

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
												<h6 style="font-size: 14px;">Por Emp. de Transporte</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_transportista" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
													
												</select>
											</div>
										</div>
									</div>

									<div id="div_filtroplanta" class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px; display: none;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Planta - 2do Tramo</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_plantas" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
													
												</select>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Placa</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_placa" type="text" class="form-control" style="font-size: 14px;">
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">

										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
											<h6 style="font-size: 14px; margin-top: 13px; margin-right: 15px;">Por Lotes: </h6>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">

											<div class="flex-fill" style="margin-top: 3px;">
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

								</div>




								<div class="row" style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
				              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
			            	</button>
			            </div>
			            <br><br>
			            <div class="col-md-12 col-sm-12 col-xs-12">
			            	<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
				              <b>Exportar a Excel</b>
				            </button>
				          </div>
								</div>

	            </div>
	          </div>
	        </div>
	      </div>

				<div class="row">

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

							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="row" style="padding: 20px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<div class="d-flex">
											<h5>Resumen de Balanza</h5>

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

									<div class="col-md-2 col-sm-2 col-xs-12" style="margin-top: -5px;">
										<button class="btn btn-primary" type="button" onclick="f_AdminUnidades('x');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px;">
				              <b>+ Nuevo Registro</b>
				            </button>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%; margin-bottom: 100px;">
									<div class="table-container">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 12px;">
						        			<th colspan="2" rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 35px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Lote
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 105px;">
						        				Ticket Balanza
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
						        				Condición
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Fecha Ingreso a Balanza
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Placa 1
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Placa 2
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Información Guías
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Emp. de Transporte
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Tipo Vehículo
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Info. Conductor
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Tipo Carga
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Cant. Big Bag
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Zona Origen
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Proveedor Minero / Remitente
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Producto
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Tipo Mineral
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
						        				Observación
						        			</th>

						        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Información de Pesos (Kg)
						        			</th>
						        		</tr>

						        		<tr style="font-size: 12px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Remitente
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Transportista
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				DNI/RUC
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
						        				Razón Social
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Licencia
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
						        				Nombres
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Documento
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
						        				Razón Social
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Fecha Peso Inicial
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Fecha Peso Final
						        			</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Bruto
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Tara
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Neto
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

		<!-- Ventanas modales -->
		

		<div class="modal fade" id="modal_gestionlotes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_gestionlotesLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content" style="width: 120%;">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_gestionlotesLabel_a"></h1>
		        <h1 class="modal-title fs-5" id="modal_gestionlotesLabel" style="margin-left: 10px; color: #9e6d14;"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; margin-bottom: 5px;">
		      		<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Lote:
								</div>

								<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<input id="registro_lote" type="text" class="form-control" style="font-size: 14px;">
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Condición:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lista_condicion" class="form-select" data-placeholder="Elija una opción..." onchange="f_ShowTipoCarga();">
												<option selected value="1">Recepción de Mineral</option>
												<option value="2">Despacho de Mineral</option>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Placa 1:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lista_placa1" class="form-select" data-placeholder="Elija una opción...">
												
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Placa 2:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lista_placa2" class="form-select" data-placeholder="Elija una opción...">
												
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Emp. Transporte:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lista_transportista" class="form-select" data-placeholder="Elija una opción...">
												
											</select>
										</div>
									</div>
								</div>
							</div>

		      		<div id="div_TipoCarga">
		      			<div class="d-flex" style="padding: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
										Tipo Carga:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-12">
										<div class="d-flex">
											<div class="flex-fill" style="max-width: 100%">
												<select id="lote_tipocarga" class="form-select" data-placeholder="Elija una opción...">
													<option selected value="">Elija una opción...</option>
													<option value="x" style="font-size: 6px;" disabled></option>

													<?php

													$t = 1;

													$q_tipocarga = "SELECT Id,
																								 UPPER(descripcion) AS descripcion
																						FROM tbconfig_tipocarga
																					 WHERE estado = 'A'
																					 	 AND id_clientecondicion = 1
																					ORDER BY orden";

													if ($res_tipocarga = mysqli_query($enlace, $q_tipocarga)){
									          if (mysqli_num_rows($res_tipocarga) > 0) {
									            while($row_tipocarga = mysqli_fetch_array($res_tipocarga)){
									              ?>

									              <option value="<?php echo $row_tipocarga["Id"]; ?>"><?php echo $row_tipocarga["descripcion"]; ?></option>

									              <option value="x" style="font-size: 6px;" disabled></option>

									              <?php

									              $t ++;
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

		      		<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Zona Origen:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 87%">
											<select id="lote_zonaorigen" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddZonaOrigen" type="button" class="btn" onclick="f_AddZonaOrigen();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Proveedor Minero:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lote_proveedorminero" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_proveedorminero = "SELECT Id,
																										 documento,
																										 UPPER(razon_social) AS razon_social
																								FROM tb_clientes
																							 WHERE estado = 'A'
																							 	 AND cod_clientecondicion = 1
																							ORDER BY razon_social";

												if ($res_proveedorminero = mysqli_query($enlace, $q_proveedorminero)){
								          if (mysqli_num_rows($res_proveedorminero) > 0) {
								            while($row_proveedorminero = mysqli_fetch_array($res_proveedorminero)){
								              ?>

								              <option value="<?php echo $row_proveedorminero["Id"]; ?>"><?php echo $row_proveedorminero["documento"].' - '.$row_proveedorminero["razon_social"]; ?></option>

								              <option value="x" style="font-size: 6px;" disabled></option>

								              <?php

								              $t ++;
								            }
								          }
								        }

												?>

											</select>
										</div>
									</div>
								</div>
							</div>

							<!-- <div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Encargado Muestra:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 87%">
											<select id="lote_encargado" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddEncargado" type="button" class="btn" onclick="f_AddEncargadoMuestra();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>
									</div>
								</div>
							</div> -->

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Producto:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lote_producto" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_producto = "SELECT Id,
			                        								UPPER(descripcion) AS descripcion
							                           FROM tbconfig_producto
							                          WHERE estado = 'A'
							                         ORDER BY descripcion";

								        if ($res_producto = mysqli_query($enlace, $q_producto)){
								          if (mysqli_num_rows($res_producto) > 0) {
								            while($row_producto = mysqli_fetch_array($res_producto)){
								              ?>

								              <option value="<?php echo $row_producto["Id"]; ?>"><?php echo $row_producto["descripcion"]; ?></option>

								              <option value="x" style="font-size: 6px;" disabled></option>

								              <?php

								              $t ++;
								            }
								          }
								        }

												?>

											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Tipo Material:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lote_tipomaterial" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_tipomaterial = "SELECT Id,
					                        								UPPER(descripcion) AS descripcion
									                           FROM tbconfig_tipomineral
									                          WHERE estado = 'A'
									                         ORDER BY descripcion";

								        if ($res_tipomaterial = mysqli_query($enlace, $q_tipomaterial)){
								          if (mysqli_num_rows($res_tipomaterial) > 0) {
								            while($row_tipomaterial = mysqli_fetch_array($res_tipomaterial)){
								              ?>

								              <option value="<?php echo $row_tipomaterial["Id"]; ?>"><?php echo $row_tipomaterial["descripcion"]; ?></option>

								              <option value="x" style="font-size: 6px;" disabled></option>

								              <?php

								              $t ++;
								            }
								          }
								        }

												?>

											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Observación:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<textarea id="lote_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
										</div>
									</div>
								</div>
							</div>
		      	</div>

		      	<div id="div_pesoinicial" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #f0efe8; padding: 5px; margin-bottom: 5px;">
							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; font-weight: bold; font-size: 14px;">
									Peso Inicial:
								</div>

								<div class="col-md-6 col-sm-6 col-xs-6">
									<input id="lote_pesoinicial" type="number" class="form-control obj_cab col-md-12 col-xs-12 show_pesoinicial" style="text-align: center; font-weight: bold; font-size: 15px;" onclick="f_getPeso(1);" onblur="f_getPesoOff();">
								</div>

								<!-- <div id="div_InterfazAuto" class="col-md-2 col-sm-2 col-xs-2">
									<div class="col-md-2 col-sm-2 col-xs-2" style="margin-left: 10px; margin-top: 7px;">
										<div class="form-check form-switch">
										  <input class="form-check-input" type="checkbox" role="switch" id="chk_InterfazAuto" onchange="f_getPeso(1);" checked>
										  <label id="lbl_getpeso_check" class="form-check-label" for="chk_InterfazAuto">Auto.</label>
										</div>
									</div>
								</div> -->
							</div>

							<!-- <div id="div_SinConexion" class="col-md-10 col-sm-10 col-xs-10" style="text-align: right; display: none;">
	              <label class="control-label" style="color: #d9534f; font-size: 12px;">
	                Se perdió la conexión con la balanza
	              </label>
	            </div> -->
						</div>

		      	<div id="div_pesofinal" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #f0efe8; padding: 5px; margin-bottom: 5px;">
							<!-- <div id="div_InterfazAuto_pesofinal" class="d-flex">
								<div class="col-md-9 col-sm-9 col-xs-9">
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2">
									<div class="form-check form-switch">
									  <input class="form-check-input" type="checkbox" role="switch" id="chk_InterfazAuto_pesofinal" onchange="f_getPeso(1);" checked>
									  <label id="lbl_getpeso_check" class="form-check-label" for="chk_InterfazAuto_pesofinal">Auto.</label>
									</div>
								</div>
							</div> -->

			        <div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; font-size: 14px;">
									Peso Final:
								</div>

								<div class="col-md-6 col-sm-6 col-xs-12">
									<input id="lote_pesofinal" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 15px;" onclick="f_getPeso(1);" onblur="f_getPesoOff();" onkeyup="f_CalculaPesoNeto();">
								</div>
							</div>

							<div id="div_SinConexion_pesofinal" class="col-md-10 col-sm-10 col-xs-10" style="text-align: right; display: none;">
	              <label class="control-label" style="color: #d9534f; font-size: 12px;">
	                Se perdió la conexión con la balanza
	              </label>
	            </div>

							<div class="d-flex" style="padding: 5px; margin-top: -7px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-size: 14px;">
									Observación:
								</div>

								<div class="col-md-6 col-sm-6 col-xs-12">
									<textarea id="lote_pesofinal_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
								</div>
							</div>
						</div>

		      	<div id="div_pesobruto" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; padding: 5px;">
			        <div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; color: #ffffff;">
									Peso Bruto:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<input id="lote_pesobruto" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold;" disabled>
								</div>
							</div>
						</div>

		      	<div id="div_tara" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; padding: 5px;">
			        <div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; color: #ffffff;">
									Tara:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<input id="lote_tara" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold;" disabled>
								</div>
							</div>
						</div>

		      	<div id="div_pesoneto" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; padding: 5px;">
			        <div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; color: #ffffff;">
									Peso Neto:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<input id="lote_pesoneto" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold;" disabled>
								</div>
							</div>
						</div>
		      </div>

		      <input id="hd_ingreso" type="hidden">
		      <input id="hd_idlote" type="hidden">
		      <input id="hd_ispesoinicial" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarlote" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarlote_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>

		         <button type="button" class="btn btn-warning wt_grabarlote_button" style="font-size: 14px;" onclick="f_ConfirmarLote();">Confirmar</button>
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

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| Resumen de Balanza');

				// Carga Filtros
					f_LoadFiltros();

				// Carga el detalle de información
					f_LoadResultados();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			// Listas para edición
			  $('.select_datos').select2({
			    theme: "bootstrap-5",
			    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			    placeholder: $( this ).data( 'placeholder' ),
			    allowClear: true,
			    dropdownParent: $('#modal_editinfo')
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

				$('#lote_zonaorigen, #lote_proveedorminero, #lote_producto, #lote_tipomaterial').select2({
			    theme: "bootstrap-5",
			    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			    placeholder: $( this ).data( 'placeholder' ),
			    allowClear: true,
			    dropdownParent: $('#modal_gestionlotes')
				}).on('select2:open', function() {
					$('body').css('zoom', '100%'); 
				}).on('select2:close', function() {
		    	$('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
				});

				$('.select2-search__field').css('font-size', '14px');
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
				var _html = '';

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var filtro_condicioningreso = $("#filtro_condicioningreso").val();
        var filtro_transportista = $("#filtro_transportista").val();
        var filtro_placa = $("#filtro_placa").val();
        var filtro_lote = $("#filtro_lote").val();
        var filtro_planta = $("#filtro_plantas").val();

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_ListaResumenBalanza_Consolidado", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_condicioningreso: filtro_condicioningreso, filtro_transportista: filtro_transportista, filtro_placa: filtro_placa, filtro_lote: filtro_lote, filtro_planta: filtro_planta }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);
            }

            f_LoadingResumen(0);

          }, "json");
    	}

    	function f_LoadFiltros(){
    		// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_condicioningreso = $("#filtro_condicioningreso").val();

        // Cargando clientes
        	$("#filtro_transportista").html('');

        	$.post( "apis/backend.php", { accion: "get_ConsolidadoTransportistas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, condicion_ingreso: filtro_condicioningreso }, 
          function( data ) {
            if(data.estado == 1){
            	$("#filtro_transportista").html(data.html);
            }

          }, "json");

        // Cargando Plantas del 2do Tramo
        	$("#filtro_plantas").html('');

        	$.post( "apis/backend.php", { accion: "get_ResumenBalanza_Plantas2doTramoxFechas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
          function( data ) {
            if(data.estado == 1){
            	$("#filtro_plantas").html(data.html);
            }

          }, "json");
    	}

    	function f_ExportToExcel(){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_condicioningreso = $("#filtro_condicioningreso").val();
	        var filtro_transportista = $("#filtro_transportista").val();
	        var filtro_placa = $("#filtro_placa").val();
	        var filtro_lote = $("#filtro_lote").val();

        window.location.href = "export_to_excel/resumen_balanza.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&condicion_ingreso="+filtro_condicioningreso+"&filtro_transportista="+filtro_transportista+"&filtro_placa="+filtro_placa+"&filtro_lote="+filtro_lote;
    	}

    	function f_PrintTicketBalanza(_tipo_ingreso, _id_md5){
    		if (_tipo_ingreso == 1){
    			url = 'print_ticketbalanza_prev.php?x=' + _id_md5;
    		}

    		if (_tipo_ingreso == 2){
    			url = 'print_ticketbalanza_segundotramo.php?x=' + _id_md5;
    		}
				
				window.open(url, '_blank');
    	}

			function f_AdminUnidades(_id_ingreso, _id_lote, _placa, _is_pesoinicial, _peso_inicial, _pesoinicial_observacion, _cod_aum, _num_ticket, balanza_id_tipocarga, balanza_id_zonaorigen, balanza_id_proveedorminero, balanza_id_encargadomuestra, balanza_id_producto, balanza_id_tipomineral, balanza_observacion){
				// Setea título
					// if (_is_pesoinicial == 1){
					// 	$("#modal_gestionlotesLabel_a").html('Peso Inicial para Lote: ');
					// 	$("#modal_gestionlotesLabel").html(_placa);
					// }
					// else{
					// 	$("#modal_gestionlotesLabel_a").html('Peso Final para Lote: ');
					// 	$("#modal_gestionlotesLabel").html(_cod_aum + '<label style="color: #212529; margin-left: 5px; margin-right: 5px;"> | </label>' + _placa);
					// }

					$("#modal_gestionlotesLabel_a").html('Nuevo Registro');

				// Setea Id de registro
					$("#hd_ingreso").val(_id_ingreso);
					$("#hd_idlote").val(_id_lote);
					$("#hd_ispesoinicial").val(_is_pesoinicial);

				// Cargando Listas
					if (_is_pesoinicial == 1){
						f_LoadListaZonaOrigen(0);
						f_LoadListaEncargadosMuestra(0);
					}
					else{
						f_LoadListaZonaOrigen(balanza_id_zonaorigen);
						f_LoadListaEncargadosMuestra(balanza_id_encargadomuestra);
					}

				// Limpiando campos
					if (_is_pesoinicial == 1){
						$("#lote_tipocarga").val('');
						$("#lote_zonaorigen").val('');
						$("#lote_proveedorminero").val('');
						$("#lote_encargado").val('');
						$("#lote_producto").val('');
						$("#lote_tipomaterial").val('');
						$("#lote_observacion").val('');
					}
					else{
						$("#lote_tipocarga").val(balanza_id_tipocarga);
						$("#lote_tipocarga").trigger('change');
						$("#lote_zonaorigen").val(balanza_id_zonaorigen);
						$("#lote_zonaorigen").trigger('change');
						$("#lote_proveedorminero").val(balanza_id_proveedorminero);
						$("#lote_proveedorminero").trigger('change');
						$("#lote_encargado").val(balanza_id_encargadomuestra);
						$("#lote_encargado").trigger('change');
						$("#lote_producto").val(balanza_id_producto);
						$("#lote_producto").trigger('change');
						$("#lote_tipomaterial").val(balanza_id_tipomineral);
						$("#lote_tipomaterial").trigger('change');
						$("#lote_observacion").val(balanza_observacion);
					}

				// Setea objetos
					if (_is_pesoinicial == 1){
						$(".show_pesoinicial").prop('disabled', false);
						$("#lote_pesoinicial").val('');
						$("#lote_pesoinicial_observacion").val('');
						$("#chk_InterfazAuto").prop('checked', true);

						f_ReplaceClass("div_InterfazAuto", '', 'd-flex');
						$("#div_InterfazAuto").show();
						$("#div_pesofinal").hide();
						$("#div_pesobruto").hide();
						$("#div_tara").hide();
						$("#div_pesoneto").hide();
					}
					else{
						// $(".show_pesoinicial").prop('disabled', true);
						$("#lote_pesobruto").val(_peso_inicial);
						$("#lote_pesoinicial").val(_peso_inicial);
						$("#lote_pesoinicial_observacion").val(_pesoinicial_observacion);
						$("#lote_pesofinal").val('');
						$("#lote_pesofinal_observacion").val('');
						$("#lote_tara").val('');
						$("#lote_pesoneto").val('');
						$("#chk_InterfazAuto_pesofinal").prop('checked', true);

						f_ReplaceClass("div_InterfazAuto", 'd-flex', '');
						$("#div_InterfazAuto").hide();

						$("#div_pesofinal").show();
						$("#div_pesobruto").show();
						$("#div_tara").show();
						$("#div_pesoneto").show();
					}

					// f_getPeso(1);

				f_OpenModal('modal_gestionlotes');
			}

    	function f_LoadListaZonaOrigen(_id_zonaorigen){
    		var _html = '<option></option>';
        _html += '<option value="x" style="font-size: 6px;" disabled></option>';

        // $("#registro_zonaorigen").html('');
        $("#lote_zonaorigen").html('');

        $.post( "apis/backend.php", { accion: "get_ListaZonaOrigen" }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.registros, function( key, val ) {
                _html += '<option value="' + val.Id + '" ' + ((_id_zonaorigen > 0) ? ((_id_zonaorigen == val.Id) ? 'selected' : '') : '') + '>' + val.descripcion.toUpperCase() + '</option>';

                _html += '<option value="x" style="font-size: 6px;" disabled></option>';
              });
            }
            else{
              // alert("No se encontraron resultados.");
            }

            // $("#registro_zonaorigen").html(_html);
            $("#lote_zonaorigen").html(_html);

          }, "json");
    	}

    	function f_LoadListaEncargadosMuestra(_id_encarado){
    		var _html = '<option></option>';
        _html += '<option value="x" style="font-size: 6px;" disabled></option>';

        // $("#registro_encargado").html('');
        $("#lote_encargado").html('');

        $.post( "apis/backend.php", { accion: "get_ListaEncargadosMuestra" }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.registros, function( key, val ) {
                _html += '<option value="' + val.Id + '" ' + ((_id_encarado > 0) ? ((_id_encarado == val.Id) ? 'selected' : '') : '') + '>' + val.nombres.toUpperCase() + '</option>';

                _html += '<option value="x" style="font-size: 6px;" disabled></option>';
              });
            }
            else{
              // alert("No se encontraron resultados.");
            }

            // $("#registro_encargado").html(_html);
            $("#lote_encargado").html(_html);

          }, "json");
    	}

    	function f_AddTransportista(){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          var tipo = "N";
          var titulo = "Nuevo Transportista";

		    // Colocando el título a la pantalla
	        $("#modal_addclienteLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addcliente');

		    	$("#hd_idcliente").val(0);
          $("#cliente_condicion").val(2);
	        $("#cliente_tipocliente").val('');
	        $("#cliente_tipodocumento").val('');
	        $("#cliente_documento").val('');
	        $("#cliente_razonsocial").val('');
	        $("#cliente_telefono1").val('');
	        $("#cliente_telefono2").val('');
	        $("#cliente_correo").val('');
	        $("#cliente_direccion").val('');
    	}

    	function f_AddConductor(){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          var tipo = "N";
          var titulo = "Nuevo Conductor";

		    // Colocando el título a la pantalla
	        $("#modal_addconductorLabel").html(titulo);

		    // Identificando el tipo de grabación

		    // Cargando datos
	        f_OpenModal('modal_addconductor');

		    	$("#conductor_dni").val('');
          $("#conductor_nombres").val('');
    	}

    	function f_AddZonaOrigen(){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          var tipo = "N";
          var titulo = "Nueva Zona de Origen";

		    // Colocando el título a la pantalla
	        $("#modal_addzonaorigenLabel").html(titulo);

		    // Identificando el tipo de grabación

		    // Cargando datos
	        f_OpenModal('modal_addzonaorigen');

		    	$("#zona_origen").val('');
    	}

    	function f_AddEncargadoMuestra(){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          var tipo = "N";
          var titulo = "Nuevo Encargado de Muestra";

		    // Colocando el título a la pantalla
	        $("#modal_addencargadomuestraLabel").html(titulo);

		    // Identificando el tipo de grabación

		    // Cargando datos
	        f_OpenModal('modal_addencargadomuestra');

		    	$("#encargado_dni").val('');
          $("#encargado_nombres").val('');
    	}

    	function f_GetInfoCliente(_id_modulo){
    		var is_ruc = 0;
				var documento = '';

    		if (_id_modulo == 1){
    			is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
					documento = $("#cliente_documento").val();
    		}
    		
    		if (_id_modulo == 2){
    			is_ruc = 0;
					documento = $("#conductor_dni").val();
    		}
    		
    		if (_id_modulo == 3){
    			is_ruc = 0;
					documento = $("#encargado_dni").val();
    		}

				var arr_response = '';

				// Limpiando objetos
					if (_id_modulo == 1){
						$("#cliente_razonsocial").val('');
	        	$("#cliente_direccion").val('');
						$("#wt_razonsocial2").hide();
					}

					if (_id_modulo == 2){
						$("#conductor_nombres").val('');
						$("#wt_conductor").hide();
					}

    			if (_id_modulo == 3){
						$("#encargado_nombres").val('');
						$("#wt_encargado").hide();
					}

				// Obteniendo información
					if (documento.length == 8 || documento.length == 11){
						if (_id_modulo == 1){
							$("#wt_razonsocial2").show();
						}

						if (_id_modulo == 2){
							$("#wt_conductor").show();
						}

						if (_id_modulo == 3){
							$("#wt_encargado").show();
						}

						$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
	            function( data ) {
	            	if (data.estado == 1){
	            		arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

	            		if (is_ruc == 1){
		            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
		              	$("#cliente_direccion").val(arr_response[4].split(':')[1].trim());
		            	}
		            	else{
		            		if (_id_modulo == 1){
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	$("#cliente_direccion").val('');
			              }

			              if (_id_modulo == 2){
			              	$("#conductor_nombres").val(arr_response[0].split(':')[1].trim());
			              }

			              if (_id_modulo == 3){
			              	$("#encargado_nombres").val(arr_response[0].split(':')[1].trim());
			              }
		            	}
	            	}
	            	else{
	            		if (_id_modulo == 1){
		            		$("#cliente_razonsocial").val('NO ENCONTRADO');
		              	$("#cliente_direccion").val('');
		              }

		              if (_id_modulo == 2){
		              	$("#conductor_nombres").val('NO ENCONTRADO');
		              }

		              if (_id_modulo == 3){
		              	$("#encargado_nombres").val('NO ENCONTRADO');
		              }
	            	}

	            	if (_id_modulo == 1){
	            		$("#wt_razonsocial2").hide();
	            	}

	            	if (_id_modulo == 2){
	            		$("#wt_conductor").hide();
	            	}

	            	if (_id_modulo == 3){
	            		$("#wt_encargado").hide();
	            	}

	            }, "json");
					}
			}

			function f_ShowTipoCarga(){
				var id_condicion = $("#lista_condicion").val();

				$("#div_TipoCarga").show();

				if (id_condicion == 2){
					$("#div_TipoCarga").hide();
				}
			}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_KeyUpPlaca(){
				var placa1 = $("#registro_placa1").val().trim();
				var placa2 = $("#registro_placa2").val().trim();

				if (placa1.length == 3){
					document.getElementById("registro_placa2").focus();
				}

				// Obtiene los datos de Placa
					if (placa1.length == 3 && placa2.length == 3){
						$.post( "apis/backend.php", { accion: "get_InfoUnidad", placa: placa1 + '-' + placa2 }, 
		          function( data ) {
		            if(data.estado == 1){
		              $("#registro_transportista").val(data.id_transportista);
		              $("#registro_transportista").trigger('change');

		              $("#registro_tipovehiculo").val(data.id_tipovehiculo);
		              $("#registro_tipovehiculo").trigger('change');

		              $("#registro_conductor").val(data.id_conductor);
		              $("#registro_conductor").trigger('change');
		            }
		          }, "json");
					}
			}

			function f_KeyUpPlaca2(){
				var placa2 = $("#registro_placa1_2").val();

				if (placa2.trim().length == 3){
					document.getElementById("registro_placa2_2").focus();
				}
			}

			$("#modal_addrecepcion").on('shown.bs.modal', function(){
      	$("#registro_placa1").focus();
    	});

			$("#modal_addcliente").on('shown.bs.modal', function(){
      	$("#cliente_tipocliente").focus();
    	});

			$("#modal_addconductor").on('shown.bs.modal', function(){
      	$("#conductor_dni").focus();
    	});

			$("#modal_addzonaorigen").on('shown.bs.modal', function(){
      	$("#zona_origen").focus();
    	});

			function f_LoadingGrabarIngreso(_is_show){
				if (_is_show == 1){
					$("#wt_grabarregistro").show();

					$(".wt_grabarregistro_button").prop('disabled', true);
				}
				else{
					$("#wt_grabarregistro").hide();

					$(".wt_grabarregistro_button").prop('disabled', false);
				}
			}

			function f_LoadingRegistroSalida(_is_show){
				if (_is_show == 1){
					$("#wt_grabarsalida").show();

					$(".wt_grabarsalida_button").prop('disabled', true);
				}
				else{
					$("#wt_grabarsalida").hide();

					$(".wt_grabarsalida_button").prop('disabled', false);
				}
			}

			function f_LoadingSalidaAcompanantes(_is_show){
				if (_is_show == 1){
					$("#wt_loadingacompanantes").show();
				}
				else{
					$("#wt_loadingacompanantes").hide();
				}
			}

			function f_LoadingResumen(_is_show){
				if (_is_show == 1){
					$("#wt_resumen").show();
				}
				else{
					$("#wt_resumen").hide();
				}
			}

			function f_LoadingDocumentoAcompanante(_is_show){
				if (_is_show == 1){
					$("#wt_documentoacompanante").show();
				}
				else{
					$("#wt_documentoacompanante").hide();
				}
			}

			function f_LoadingImagenes(_is_show){
				if (_is_show == 1){
					$("#wt_imagenes").show();
				}
				else{
					$("#wt_imagenes").hide();
				}
			}

			function f_LoadingShowInfo(_is_show){
				if (_is_show == 1){
					$("#wt_info").show();
				}
				else{
					$("#wt_info").hide();
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">

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