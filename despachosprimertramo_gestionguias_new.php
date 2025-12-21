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

		<title><?php echo $nom_app; ?> | Despachos 1er Tramo - Generación Guías</title>

		<script type="text/javascript">
			var is_mobile = 0;
			var color_selected = '';
		</script>

		<style>
			.table-container{
				max-width: 100%;
				height: 800px;
				overflow-x: scroll;
				overflow-y: scroll;
			}

			/* Estilo para columnas estáticas*/
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

			/* Estilo para Cabeceras estáticas */
				.sticky-1Cx{
					position: sticky;
					top: 0;
					z-index: 2000;
				}

				.sticky-2Cxa{
					position: sticky;
					top: 0;
					z-index: 2000;
				}

				.sticky-2Cxc{
					position: sticky;
					top: 95;
					z-index: 2000;
				}

				.sticky-1C{
					position: sticky;
					top: 0;
				}

				.sticky-2Ca{
					position: sticky;
					top: 0;
				}

				.sticky-2Cb{
					position: sticky;
					top: 33;
				}

				.sticky-2Cc{
					position: sticky;
					top: 95;
				}
		</style>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<div class="row">
					<!-- Menús principales -->
					<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #DEDEDE;">
						
					</div>

					<div class="col-md-11 col-sm-11 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
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
												<h6 style="font-size: 14px;">Por Fecha de Creación</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">

												<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">
											</div>
										</div>
									</div>

									<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Lote</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_lote" type="text" class="form-control" style="font-size: 14px; margin-left: 5px;">
											</div>
										</div>
									</div>

									<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Estado de Cierre</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_estadocierre" class="form-select" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>
												<option value="0">Pendiente</option>
												<option value="1">Cerrado</option>
											</select>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding-left: 30px; margin-top: 5px; margin-bottom: 10px; font-size: 13px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
				              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
			            	</button>
			            </div>

			            <div class="col-md-2 col-sm-2 col-xs-12" style="margin-top: -8px; margin-left: -10px;">
			            	<div class="d-flex justify-content-end">
					            <button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; height: 36px; font-size: 14px; margin-right: 5px;">
					            	<div class="d-flex justify-content-center">
						              <img src="<?php echo $img_excel; ?>" style="width: 25px; height: 25px; margin-top: -2px;">
						              <b style="margin-top: 2px; margin-left: 5px;"> Exportar a Excel</b>
						            </div>
					            </button>
					          </div>
				          </div>
								</div>
							</div>

							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="row" style="padding: 20px;">
									<div class="col-md-4 col-sm-4 col-xs-12">
										<div class="d-flex">
											<h5>Resumen de Guías</h5>

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

									<div class="col-md-8 col-sm-8 col-xs-12">
										<div class="d-flex justify-content-end">
											<button class="btn btn-primary" type="button" onclick="f_ShowFiltroLotes();" style="min-width: 150px; color: #ffffff; height: 40px; font-size: 14px; margin-top: -10px;">
					              <b> + Nueva Guía</b>
					            </button>
					          </div>
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
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 35px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora Creación
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Lote
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 105px;">
						        				Ticket Balanza
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
						        				Trazabilidad
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Peso Lote (Tn)
						        			</th>

						        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Guía Remitente
						        			</th>

						        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Guía Transportista
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Punto Partida
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Punto Destino
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Cierre
						        			</th>
						        		</tr>

						        		<tr style="font-size: 12px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
						        				Documentos
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
						        				Verificado
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				N° Guía
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha
						        			</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				RUC / DNI
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
						        				Razón Social
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Acción
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				N° Guía
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha
						        			</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				RUC / DNI
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
						        				Razón Social
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Acción
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Sel.<br>
						        				<input id="th_Chk" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.5);" onchange="f_SelectChkCierre();">
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Usuario
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
			<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%; z-index: 10000;">
			  <div class="offcanvas-header" style="background-color: #ffffff;">
			    <h5 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h5>
			    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			  </div>

			  <div id="div_submenu1" class="offcanvas-body" style="color: #212529;">

			  </div>
			</div>
		</div>

		<!-- Ventanas modales -->
		<div class="modal fade modal-dialog-scrollable" id="modal_filtrolotes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_filtrolotesLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_filtrolotesLabel">Seleccionar Lotes</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="d-flex justify-content-center" style="padding: 5px; margin-top: -10px;">
							<h6 style="font-size: 14px; min-width: 100px; margin-top: 8px;">
								Ingreso a Planta:
							</h6>

							<input id="filtrolotes_fechainicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">

							<input id="filtrolotes_fechafin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>">
						</div>

						<div class="d-flex" style="padding: 5px; margin-top: -5px;">
							<h6 style="font-size: 14px; min-width: 100px; margin-top: 8px;">
								Proveedor Minero:
							</h6>

							<input id="filtrolotes_lote" type="text" class="form-control" style="text-align: center; font-size: 14px;">
						</div>

						<div class="d-flex" style="padding: 5px; margin-top: -5px;">
							<h6 style="font-size: 14px; min-width: 100px; margin-top: 8px;">
								Por Ticket:
							</h6>

							<input id="filtrolotes_ticket" type="text" class="form-control" style="text-align: center; font-size: 14px;">
						</div>

						<div class="d-flex justify-content-center" style="padding: 5px; margin-top: -5px;">
							<button class="btn btn-secondary" type="button" onclick="f_LoadFiltroLotes();" style="width: 100%; color: #ffffff; font-size: 14px; background-color: #cfaa41; margin-bottom: 10px;">
	              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
	          	</button>
	          </div>

	          <div class="d-flex justify-content-center" style="padding: 5px; margin-top: -15px; height: 30px;">
		          <div id="wt_loadinglotes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Cargando datos...</label>
							</div>
						</div>

						<div class="d-flex justify-content-center" style="padding: 5px; height: 350px; overflow-y: scroll;">
							<table class="table table-bordered table-hover">
			        	<thead>
			        		<tr style="font-size: 12px;">
			        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
			        				Información Lotes
			        			</th>
			        		</tr>

			        		<tr style="font-size: 12px;">
			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 60px;">
			        				Sel.<br>
			        				<input id="th_Chk" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.2);" onchange="f_SelectChkLotes();">
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Lote
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Ticket
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Neto (Tn)
			        			</th>
			        		</tr>
			        	</thead>

			        	<tbody id="tbl_FiltroLotes">
			        		
			        	</tbody>
			        </table>
						</div>

						<hr style="color: #6c757d;">

						<div class="d-flex" style="padding: 5px; margin-top: -10px; font-size: 14px;">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<label>
									Lotes seleccionados:
								</label>

								<label id="lbl_countlotes" style="font-weight: bold; margin-left: 5px;">0</label>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-6" style="margin-left: -10px;">
								<div class="d-flex justify-content-end">
									<label>
										Total Neto:
									</label>

									<label id="lbl_totalneto" style="font-weight: bold; margin-left: 5px;">0</label>
								</div>
							</div>
						</div>
		      </div>

		      <input id="modo_grabarprogramacion" type="hidden">
		      <input id="id_programacion" type="hidden">
		      <input id="item_programacion" type="hidden">

		      <div class="modal-footer" style="margin-top: -10px;">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_ConfirmarProgramacion();">Confirmar</button>
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
					$("#nv_titulo").html('| Despachos 1er Tramo - Generación Guías');

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
				  $(this).data('select2').$dropdown.find(':input.select2-search__field').focus();
				});

				$('.select2-container').css('z-index', 1);
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
								if (columnIndex == 3 || columnIndex == 6 || columnIndex == 10 || columnIndex == 11 || columnIndex == 15 || columnIndex == 17 || columnIndex == 18 || columnIndex == 22 || columnIndex == 23 || columnIndex == 24 || columnIndex == 27){
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
												if (columnIndex == 25 || columnIndex == 26 || columnIndex == 29 || columnIndex == 30){
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
        var filtro_lote = $("#filtro_lote").val();
        var filtro_estadolote = $("#filtro_estadolote").val();
        var filtro_planta = $("#filtro_planta").val();
        var filtro_estadocierre = $("#filtro_estadocierre").val();

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_ListaResumenBalanza_PrimerTramo_ValidacionDatos", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_lote: filtro_lote, filtro_estadolote: filtro_estadolote, filtro_planta: filtro_planta, filtro_estadocierre: filtro_estadocierre }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);

              f_SetInputDisabled();
            }

            // Seteando Select2
            	f_SetSelect2();

            f_LoadingResumen(0);

          }, "json");
    	};

      function f_ShowFiltroLotes(){
        // Seteando filtros
          $("#filtrolotes_fechainicio").val('<?php echo $g_date; ?>');
					$("#filtrolotes_fechafin").val('<?php echo $g_date; ?>');

        // Cargando datos
					f_LoadFiltroLotes();

        // // Cargando datos
        //   if (_modo != 'N'){
        //     $("#fecha_rack").val(_fecha_rack);
				// 		$("#hora_rack").val(_hora_rack);
				// 		$("#nombre_rack").val(_nombre_rack);
        //     $("#estufa_rack").val(_estufa_rack);
        //     $("#horassecado_rack").val(_horas_secado);
        //     $("#observacion_rack").val(_observacion);
        //   }
        //   else{
        //   	var _time = new Date();
        //   	_time = _time.getHours().toString().padStart(2, '0') + ":" + _time.getMinutes().toString().padStart(2, '0');

        //     $("#fecha_rack").val('<?php echo $g_date; ?>');
				// 		$("#hora_rack").val(_time);
        //     $("#nombre_rack").val('');
        //     $("#estufa_rack").val(1);
        //     $("#horassecado_rack").val(4);
        //     $("#observacion_rack").val('');
        //   }

        // Abre modal
        	f_OpenModal('modal_filtrolotes');
      };

    	function f_SetColor(_item, _lote){
    		$("#hd_setcolor_item").val(_item);

    		// Setea título
    			$("#modal_SetColorLabel").html(_lote);

    		// Limpia la variable global
    			color_selected = '';

    		f_OpenModal('modal_SetColor');
    	}

    	function f_SetInputDisabled(){
    		var cierre = '';

    		// Recorre las todas filas
					$("#tbl_detalle tr").filter(function() {
						tr_id = $(this).attr('id').substring(11);

						// Identifica el valor del cierre
							cierre = $("#td_cierre_1_" + tr_id).html();

							if (cierre.toLowerCase().includes('reabrir')){
								$(".input_datos_" + tr_id).prop('disabled', true);
							}
							else{
								$(".input_datos_" + tr_id).prop('disabled', false);
							}
		    	});
    	}

    	function f_ExportToExcel(_Ind){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();

        // Obteniendo Ids visualizados
        	var r = 1;
        	var ids = '';

        	$("#tbl_detalle tr:visible").filter(function() {
        		ids += $("#id_" + r).val() + ',';

        		r ++;
        	});

        	ids = ids.substring(0, ids.length - 1);

        // Generar Excel
        	if (_Ind == 1){
        		window.location.href = "export_to_excel/despachos_validaciondatos_1.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&ids=" + ids;
        	}

        	if (_Ind == 2){
        		window.location.href = "export_to_excel/despachos_validaciondatos_2.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&ids=" + ids;
        	}
    	};
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

			function f_SavingDatos(_is_show){
				if (_is_show == 1){
					$("#wt_saving").show();
				}
				else{
					$("#wt_saving").hide();
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
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_UpdateDatos(_item, _orden_campo){
				// Obtiene Id
					var _Id = $("#id_" + _item).val();

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
          $.post( "apis/backend.php", { accion: "update_PrimerTramo_ValidacionDatos", Id: _Id, orden_campo: _orden_campo, valor: _valor },
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

            		if (_orden_campo == 13){
            			$("#val_14_" + _item).val(data.unidad_capacidad);
            			$("#val_15_" + _item).val(data.unidad_tara);
            			$("#val_16_" + _item).val(data.id_marca);
            		}

            		// Recorriendo tabla y actualizando Capacidad, Tara y Marca
            			if (_orden_campo == 14 || _orden_campo == 15 || _orden_campo == 16){
	            			var d = 1;
	            			var placa = $("#val_13_" + _item).val();
	            			var placa_x = '';

	            			$("#tbl_detalle tr").each(function () {
						      		if (d != _item){
						      			placa_x = $("#val_13_" + d).val();

						      			if (placa == placa_x){
						      				$("#val_" + _orden_campo + '_' + d).val(_valor);
						      			}
						      		}

						          d ++;
						        });
	            		}

            		if (_orden_campo == 27 || _orden_campo == 28){
            			$("#td_pesobruto_" + _item).html(data.peso_bruto);
            		}

              }
              else{
                alert("Ocurrió un error al momento de grabar los datos de ingreso.");

                f_SavingDatos(0);

                return;
              }

              f_SavingDatos(0);

            }, "json");
			}

			function f_GrabarColor(){
				var item = $("#hd_setcolor_item").val();
				var id_detalle = $("#id_" + item).val();
				var color_x = color_selected;

				// Validando datos
					if (color_x.length == 0){
						alert("Debe seleccionar un color");

						return;
					}

				// Grabando datos
					$.post( "apis/backend.php", { accion: "grabar_ValidacionDatos_SetColor", id_detalle: id_detalle, color: color_x },
            function( data ) {
            	if(data.estado == 1){
            		// Setea el color seleccionado
            			$("#tr_detalle_" + item).css('background-color', ((color_x == 'NULL') ? '' : color_x));
            	}
            	else{
            		alert("Ourrió un error al momento de grabar el color");
            	}

            	f_cerrarModal("modal_SetColor");

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

			function f_Reabrir(_item, _id_validacion){
				$.post( "apis/backend.php", { accion: "reabrir_PrimerTramo_Validacion", id_validacion: _id_validacion },
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
            }
            else{
              if (data.estado == 2){
              	alert("El lote seleccionado se encuentra asignado a una pre guía.\n\nNo puede continuar.");
              }

              if (data.estado == 3){
              	alert("El lote seleccionado se encuentra asignado a una guía.\n\nNo puede continuar.");
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