<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

  // Obtiene variables de usuario
  	$gestionmuestras_operaciones = $_SESSION["gestionmuestras_operaciones"];
		$gestionmuestras_vigilancia = $_SESSION["gestionmuestras_vigilancia"];
		$gestionmuestras_apoyointerno = $_SESSION["gestionmuestras_apoyointerno"];
		$gestionmuestras_acopiador = $_SESSION["gestionmuestras_acopiador"]

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

		<title><?php echo $nom_app; ?> | Gestión de Muestras</title>

		<script type="text/javascript">
			var is_mobile = 0;
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
					z-index: 980;
				}

				.sticky-2{
					position: sticky;
					left: 33;
					z-index: 980;
				}

				.sticky-3{
					position: sticky;
					left: 58;
					z-index: 980;
				}

				.sticky-4{
					position: sticky;
					left: 58;
					z-index: 980;
				}

				.sticky-5{
					position: sticky;
					left: 188;
					z-index: 980;
				}

				.sticky-2h{
					position: sticky;
					left: 58;
					z-index: 980;
				}

				.sticky-3h{
					position: sticky;
					left: 58;
					z-index: 980;
				}

				.sticky-4h{
					position: sticky;
					left: 188;
					z-index: 980;
				}

			/* Estilo para Cabeceras estáticas */
				.sticky-1Cx{
					position: sticky;
					top: 0;
					z-index: 990;
				}

				.sticky-2Cxa{
					position: sticky;
					top: 0;
					z-index: 990;
				}

				.sticky-2Cxc{
					position: sticky;
					top: 95;
					z-index: 990;
				}

				.sticky-1C{
					position: sticky;
					top: 0;
				}

				.sticky-2Ca{
					position: sticky;
					top: 0;
					z-index: 980;
				}

				.sticky-2Cb{
					position: sticky;
					top: 33;
					z-index: 980;
				}

				.sticky-2Cc{
					position: sticky;
					top: 95;
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
		          <div  class="modal-body" style=" padding: 0px !important;">
			           
		            <div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="form-check">
											  <input id="chk_filtrofechas" class="form-check-input obj_waiting" type="checkbox" onchange="f_ShowPlaca();">

											  <label class="form-check-label" for="chk_filtrofechas" style="font-size: 14px; font-weight: bold;">
											    Por fechas
											  </label>
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
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Lote</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_lote" type="text" class="form-control" style="font-size: 14px;">
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

							<img id="img_evidencia" style="width: 250px; display: none;">

							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="row" style="padding: 20px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<div class="d-flex">
											<h5>Información de Muestras por Lote</h5>

											<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Cargando datos...</label>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
									<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 450px; margin-bottom: 20px;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 12px;">
						        			<th rowspan="2" class="sticky sticky-1Cx" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="2" class="sticky-2 sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Lote
						        			</th>

						        			<th rowspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1) ? 'hidden' : '') ?>>
						        				Fecha Hora<br>Ingreso Planta
						        			</th>

						        			<th rowspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora<br>Creación Lote
						        			</th>

						        			<th rowspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1) ? 'hidden' : '') ?>>
						        				Neto<br>(TMH)
						        			</th>

						        			<th rowspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
						        				Encargado Muestra
						        			</th>

						        			<th rowspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;" hidden>
						        				Tipo Mineral
						        			</th>

						        			<th rowspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Fecha Hora<br>Peso Final
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?> hidden>
						        				Gestión Chancado
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1) ? 'hidden' : '') ?>>
						        				Entrega de Muestra a Proveedor
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Entrega Interna de Muestra<br>G&S
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Entrega Interna de Muestra<br>Lab Perú Minerals
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
						        				Entrega de Muestra a Garita
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
						        				Recepción de Muestra en Garita
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
						        				Recepción de Muestra para Laboratorio
						        			</th>

						        			<th colspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
						        				Ingreso de Muestra a Laboratorio
						        			</th>

						        			<th colspan="2" rowspan="2" class="sticky-2Cxa" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; color: #816951; vertical-align: middle; border-top-right-radius: 15px;" hidden>
						        				Evidencia
						        			</th>
						        		</tr>

						        		<tr style="font-size: 12px;">
						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1) ? 'hidden' : '') ?> hidden>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1) ? 'hidden' : '') ?> hidden>
						        				Observación
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Observación
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" hidden>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" hidden>
						        				Observación
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" hidden>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" hidden>
						        				Observación
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" hidden>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" hidden>
						        				Observación
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" hidden>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" hidden>
						        				Observación
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Observación
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Registro
						        			</th>

						        			<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;" <?php echo (($gestionmuestras_vigilancia == 1 || $gestionmuestras_apoyointerno == 1 || $gestionmuestras_acopiador == 1) ? 'hidden' : '') ?>>
						        				Observación
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
		<div class="modal fade" id="modal_setregistro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_setregistroLabel" aria-hidden="true" style="z-index: 10000;">
		  <div class="modal-dialog">
		    <div class="modal-content" style="width: 120%;">
		      <div class="modal-header">
		      	<h1 class="modal-title fs-5">Lote: </h1>
		        <h1 class="modal-title fs-5" id="modal_setregistroLabel" style="color: #337ab7; margin-left: 5px;"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="padding: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; margin-left: 5px;">
								Motivo retraso:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-12">
								<select id="registro_motivoretraso" class="form-select" style="text-align: left;" onchange="f_ShowObservacionOtros();">
									<option selected value="">Elija una opción...</option>
									<option value="x" style="font-size: 6px;" disabled></option>

									<?php

									$t = 1;

									$q_datos = "SELECT Id,
                    								 descripcion,
                    								 is_otros
		                            FROM tbconfig_gestionmuestras_observaciones
		                           WHERE estado = 'A'
		                          ORDER BY is_otros, descripcion";

					        if ($res_datos = mysqli_query($enlace, $q_datos)){
					          if (mysqli_num_rows($res_datos) > 0) {
					            while($row_datos = mysqli_fetch_array($res_datos)){
					              ?>

					              <option value="<?php echo $row_datos["Id"].'|'.$row_datos["is_otros"]; ?>"><?php echo $row_datos["descripcion"]; ?></option>

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

						<div id="div_observacionotros" class="row" style="padding: 5px; display: none;">
							<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px; margin-left: 5px;">
								Comentario:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-12">
								<textarea id="registro_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
							</div>
						</div>
		      </div>

		      <input id="hd_item" type="hidden">
		      <input id="hd_idproceso" type="hidden">
		      <input id="hd_codlote" type="hidden">
		      <input id="hd_isfuerahorario" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarpeso" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarpeso_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>

		         <button type="button" class="btn btn-warning wt_grabarpeso_button" style="font-size: 14px;" onclick="f_GrabarRegistro();">Confirmar</button>
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

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| Gestión de Muestras');

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

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_ListaResumenBalanza_GestionMuestras", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_condicioningreso: filtro_condicioningreso, filtro_transportista: filtro_transportista, filtro_placa: filtro_placa, filtro_lote: filtro_lote, gestionmuestras_operaciones: <?php echo $gestionmuestras_operaciones; ?>, gestionmuestras_vigilancia: <?php echo $gestionmuestras_vigilancia; ?>, gestionmuestras_apoyointerno: <?php echo $gestionmuestras_apoyointerno; ?>, gestionmuestras_acopiador: <?php echo $gestionmuestras_acopiador; ?> }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);
            }

            f_LoadingResumen(0);

          }, "json");
    	};

    	function f_ShowRegistro(_item, _id_proceso, _cod_lote, _is_fuerahorario){
				// Setea título
					$("#modal_setregistroLabel").html(_cod_lote);

				// Setea objetos hidden
					$("#hd_item").val(_item);
					$("#hd_idproceso").val(_id_proceso);
					$("#hd_codlote").val(_cod_lote);
					$("#hd_isfuerahorario").val(_is_fuerahorario);

				// Limpiando campos
					$("#registro_motivoretraso").val('');
					$("#registro_observacion").val('');
					$("#div_observacionotros").hide();

        // Setea Motivos de Retraso por Proceso
          $("#registro_motivoretraso").prop('disabled', false);
          $("#div_observacionotros").hide();

				if (_is_fuerahorario == 0){
					// if (_id_proceso == 4 || _id_proceso == 5 || _id_proceso == 6){
         	// 	$("#registro_motivoretraso").val('4|1');

         	// 	$("#registro_motivoretraso").prop('disabled', true);
         	// 	$("#div_observacionotros").show();

         	// 	f_OpenModal('modal_setregistro');
         	// }
         	// else{
         		// f_GrabarRegistro();
         	// }

         	f_OpenModal('modal_setregistro');
				}
				else{
					if (_id_proceso == 4 || _id_proceso == 5 || _id_proceso == 6){
         		$("#registro_motivoretraso").val('4|1');

         		$("#registro_motivoretraso").prop('disabled', true);
         		$("#div_observacionotros").show();
         	}
         	// else{
         	// 	f_GrabarRegistro();
         	// }

					f_OpenModal('modal_setregistro');
				}
			}

	    function f_AddEvidencia(_cod_lote){
			  var input = document.createElement('input');
			  input.type = 'file';
			  input.accept = 'image/*';
			  input.onchange = function(event) {
			    var file = event.target.files[0];
			    var reader = new FileReader();
			    reader.onload = function(e) {
			      var imagen = document.getElementById('img_evidencia');
			      imagen.src = e.target.result;

			      f_SaveImagen(_cod_lote);
			    };
			    reader.readAsDataURL(file);
			  };
			  input.click();

			  // $("#img_evidencia").show();
	    }

	    function f_ShowEvidencia(_cod_lote){
	    	var _src = '';

	    	$("#img_imagenes").attr('src', '');

      	f_LoadingImagenes(1);

      	$.post( "apis/backend.php", { accion: "get_GestionMuestras_EvidenciasImagenSRC", cod_lote: _cod_lote }, 
          function( data ) {
            if (data.estado == 1){
            	_src = data.src;
            }

            // Cargando Imagen
			        var modalImg = document.getElementById('img_imagenes');
        			modalImg.src = _src;

			      f_LoadingImagenes(0);
          });

	      // Abre modal
	      	f_OpenModal('modal_showimagenes');
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

			function f_ShowObservacionOtros(){
				var id_motivoretraso = $("#registro_motivoretraso").val().split('|')[0];
				var is_otros = $("#registro_motivoretraso").val().split('|')[1];

				// Oculta campo de observación
					$("#div_observacionotros").hide();
					$("#registro_observacion").val('');

				if (is_otros == 1){
					$("#div_observacionotros").show();
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
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_GrabarRegistro(){
				// Recupera variables ocultas
					var item = $("#hd_item").val();
					var id_proceso = $("#hd_idproceso").val();
					var cod_lote = $("#hd_codlote").val();
					var is_fuerahorario = $("#hd_isfuerahorario").val();

				// Recupera datos
          var id_motivoretraso = f_CleanInjection($("#registro_motivoretraso").val().split('|')[0]);
          var des_motivoretraso = ((is_fuerahorario == 1) ? $("#registro_motivoretraso option:selected").text() : '');
					var is_otros = $("#registro_motivoretraso").val().split('|')[1];
          var observacion = f_CleanInjection($("#registro_observacion").val().trim());

        // Validando datos
          if (is_fuerahorario == 1){
          	if (id_motivoretraso == null){
	            alert("Debe seleccionar el Motivo de Retraso.");

	            return;
	          }
	          if (id_motivoretraso.length == 0){
	            alert("Debe seleccionar el Motivo de Retraso.");

	            return;
	          }

	          if (is_otros == 1){
	          	if (observacion == null){
		            alert("Debe registrar la observación.");

		            return;
		          }
		          if (observacion.length == 0){
		            alert("Debe registrar la observación.");

		            return;
		          }
	          }
          }

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_GestionMuestras_RegistroProcesos", id_proceso: id_proceso, cod_lote: cod_lote, is_fuerahorario: is_fuerahorario, id_motivoretraso: id_motivoretraso, observacion: observacion },
            function( data ) {
              if(data.estado == 1){
              	// Actualizando registro
              		if (id_proceso == 1){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso1_" + item).html(_html);
	              		$("#td_proceso2_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));

	              		// Obteniendo la diferencia en horas para según eso pintar el botón
	              			var is_fuerahorario = 0;
	              			var _class = '';
	              			var num_horas = f_CalcularDiferenciaHoras(data.fechahora_registro, '<?php echo $g_fecha ?>');

	              			if (num_horas > 4){
												_class = 'danger';

												is_fuerahorario = 1;
											}
											else{
												_class = 'warning';
											}

	              		// Preparando el siguiente botón
	              			_html = '<button class="btn btn-' + _class + '" type="button" onclick="f_ShowRegistro(' + item + ", 2, '" + cod_lote + "', " + is_fuerahorario + ');" style="font-size: 14px;">';
											_html += '	Registrar';
											_html += '</button>';

											$("#td_proceso3_" + item).html(_html);
              		}

              		if (id_proceso == 2){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso3_" + item).html(_html);
	              		$("#td_proceso4_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));

	              		// Seteando los siguientes procesos
	              			// 1. "Entrega Interna de Muestra G&S"
			              		// Obteniendo la diferencia en horas para según eso pintar el botón
			              			var is_fuerahorario = 0;
			              			var _class = '';
			              			var num_horas = f_CalcularDiferenciaHoras(data.fechahora_registro, '<?php echo $g_fecha ?>');

			              			if (num_horas > 1){
														_class = 'danger';

														is_fuerahorario = 1;
													}
													else{
														_class = 'warning';
													}

			              		// Preparando el botón
			              			_html = '<button class="btn btn-' + _class + '" type="button" onclick="f_ShowRegistro(' + item + ", 7, '" + cod_lote + "', " + is_fuerahorario + ');" style="font-size: 14px;">';
													_html += '	Registrar';
													_html += '</button>';

													$("#td_proceso13_" + item).html(_html);

											// 2. "Entrega Interna de Muestra Lab Perú Minerals"
			              		// Obteniendo la diferencia en horas para según eso pintar el botón
			              			var is_fuerahorario = 0;
			              			var _class = '';
			              			var num_horas = f_CalcularDiferenciaHoras(data.fechahora_registro, '<?php echo $g_fecha ?>');

			              			if (num_horas > 2){
														_class = 'danger';

														is_fuerahorario = 1;
													}
													else{
														_class = 'warning';
													}

			              		// Preparando el botón
			              			_html = '<button class="btn btn-' + _class + '" type="button" onclick="f_ShowRegistro(' + item + ", 8, '" + cod_lote + "', " + is_fuerahorario + ');" style="font-size: 14px;">';
													_html += '	Registrar';
													_html += '</button>';

													$("#td_proceso15_" + item).html(_html);
              		}

              		if (id_proceso == 3){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso5_" + item).html(_html);
	              		$("#td_proceso6_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));

	              		if (<?php echo $gestionmuestras_operaciones ?> != 1){
	              			// Obteniendo la diferencia en horas para según eso pintar el botón
		              			var is_fuerahorario = 0;
		              			var _class = '';
		              			var num_horas = f_CalcularDiferenciaHoras(data.fechahora_registro, '<?php echo $g_fecha ?>');

		              			if (num_horas > 1){
													_class = 'danger';

													is_fuerahorario = 1;
												}
												else{
													_class = 'warning';
												}

		              		// Preparando el siguiente botón
		              			_html = '<button class="btn btn-' + _class + '" type="button" onclick="f_ShowRegistro(' + item + ", 4, '" + cod_lote + "', " + is_fuerahorario + ');" style="font-size: 14px;">';
												_html += '	Registrar';
												_html += '</button>';

												$("#td_proceso7_" + item).html(_html);
	              		}
              		}

              		if (id_proceso == 4){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso7_" + item).html(_html);
	              		$("#td_proceso8_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));

	              		// Obteniendo la diferencia en horas para según eso pintar el botón
	              			var is_fuerahorario = 0;
	              			var _class = '';
	              			var num_horas = f_CalcularDiferenciaHoras(data.fechahora_registro, '<?php echo $g_fecha ?>');

	              			if (num_horas > 1){
												_class = 'danger';

												is_fuerahorario = 1;
											}
											else{
												_class = 'warning';
											}

	              		// Preparando el siguiente botón
	              			_html = '<button class="btn btn-' + _class + '" type="button" onclick="f_ShowRegistro(' + item + ", 5, '" + cod_lote + "', " + is_fuerahorario + ');" style="font-size: 14px;">';
											_html += '	Registrar';
											_html += '</button>';

											$("#td_proceso9_" + item).html(_html);
              		}

              		if (id_proceso == 5){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso9_" + item).html(_html);
	              		$("#td_proceso10_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));

	              		// Obteniendo la diferencia en horas para según eso pintar el botón
	              			var is_fuerahorario = 0;
	              			var _class = '';
	              			var num_horas = f_CalcularDiferenciaHoras(data.fechahora_registro, '<?php echo $g_fecha ?>');

	              			if (num_horas > 1){
												_class = 'danger';

												is_fuerahorario = 1;
											}
											else{
												_class = 'warning';
											}

	              		// Preparando el siguiente botón
	              			_html = '<button class="btn btn-' + _class + '" type="button" onclick="f_ShowRegistro(' + item + ", 6, '" + cod_lote + "', " + is_fuerahorario + ');" style="font-size: 14px;">';
											_html += '	Registrar';
											_html += '</button>';

											$("#td_proceso11_" + item).html(_html);
              		}

              		if (id_proceso == 6){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso11_" + item).html(_html);
	              		$("#td_proceso12_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));
              		}

              		if (id_proceso == 7){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso13_" + item).html(_html);
	              		$("#td_proceso14_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));
              		}

              		if (id_proceso == 8){
              			var _html = data.fechahora_registro + '<br><i style="font-weight: 400">' + data.usuario_registro + '</i>';

	              		$("#td_proceso15_" + item).html(_html);
	              		$("#td_proceso16_" + item).html(((is_otros == 1) ? observacion.toUpperCase() : des_motivoretraso.toUpperCase()));
              		}

              	f_cerrarModal('modal_setregistro');
              }
              else{
                alert("Ocurrió un error al momento de grabar los datos.");
              }

            }, "json");
			}

      function f_SaveImagen(_cod_lote){
	    	if ($("#img_evidencia").attr('src').length == 0){
	    		setTimeout('f_SaveImagen(' + "'" + _cod_lote + "'" + ')', 1000);
	    	}
	    	else{
	    		// Guardando archivo
						var arr_imagenes = [];

						var _imagen = {
				      imagen: $("#img_evidencia").attr('src')
				    };

				    arr_imagenes.push(_imagen);

				    $.post( "apis/backend.php", { accion: "grabar_GestionMuestras_UploadEvidencias", cod_lote: _cod_lote, arr_imagenes: JSON.stringify(arr_imagenes) },
	            function( data ) {
	            	if(data.estado == 1){
	            		$("#img_view_" + _cod_lote).css('display', 'block');
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