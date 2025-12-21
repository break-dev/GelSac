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
		<link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

  	<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Administración de Empleados</title>

		<style>
		  
		</style>

	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<!-- Modal (Menú Lateral) -->
	      <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
	        <div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important; ">
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


				<!-- Modal (Filtros Lateral) -->
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
												<h6 style="font-size: 14px;">Por Planilla</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_planilla" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
													<option selected value="">Elija una opción...</option>

													<?php

													$q_planilla = "SELECT Id,
	                              								descripcion
								                           FROM tbconfig_planillas
								                          WHERE estado = 'A'";

									        if ($res_planilla = mysqli_query($enlace, $q_planilla)){
									          if (mysqli_num_rows($res_planilla) > 0) {
									            while($row_planilla = mysqli_fetch_array($res_planilla)){
									              ?>

									              <option value="<?php echo $row_planilla["Id"]; ?>"><?php echo $row_planilla["descripcion"]; ?></option>

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
												<h6 style="font-size: 14px;">Por Documento</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_documento" type="text" class="form-control" style="font-size: 14px;" onblur="f_LoadResultados();">
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-1 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Nombres</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_nombres" type="text" class="form-control" style="font-size: 14px;" onblur="f_LoadResultados();">
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Cargo</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_cargo" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
													<option selected value="">Elija una opción...</option>

													<?php

													$q_cargos = "SELECT Id,
	                            								descripcion
							                           FROM tbconfig_cargos
							                          WHERE estado = 'A'
							                         ORDER BY descripcion";

									        if ($res_cargos = mysqli_query($enlace, $q_cargos)){
									          if (mysqli_num_rows($res_cargos) > 0) {
									            while($row_cargos = mysqli_fetch_array($res_cargos)){
									              ?>

									              <option value="<?php echo $row_cargos["Id"]; ?>"><?php echo $row_cargos["descripcion"]; ?></option>

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
												<h6 style="font-size: 14px;">Por Área</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_area" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
													<option selected value="">Elija una opción...</option>

													<?php

													$q_areas = "SELECT Id,
	                            							 descripcion
							                          FROM tbconfig_areas
							                         WHERE estado = 'A'
							                         ORDER BY descripcion";

									        if ($res_areas = mysqli_query($enlace, $q_areas)){
									          if (mysqli_num_rows($res_areas) > 0) {
									            while($row_areas = mysqli_fetch_array($res_areas)){
									              ?>

									              <option value="<?php echo $row_areas["Id"]; ?>"><?php echo $row_areas["descripcion"]; ?></option>

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
	          </div>
	        </div>
	      </div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row " style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
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

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-10 col-sm-10 col-xs-10">
									<h5>Resumen de Empleados</h5>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-primary" type="button" onclick="f_AdminEmpleados('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
			              <b> + Nuevo Empleado</b>
			            </button>
								</div>

								
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
								<table class="table table-bordered table-striped table-hover">
				        	<thead>
				        		<tr style="font-size: 14px;">
				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
				        				N°
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Planilla
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Documento
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Nombres
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Cargo
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Área
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Fecha Ingreso
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Fecha Nacimiento
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Teléfonos
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Sexo
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Estado
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
				        				Acción
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

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_addempleado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addempleadoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addempleadoLabel">Nuevo Cliente</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Planilla:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="empleado_planilla" class="form-select" style="text-align: left;" onchange="f_GetListaTipoDocumento(0)">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_planilla = "SELECT Id,
                        								descripcion
				                           FROM tbconfig_planillas
				                          WHERE estado = 'A'";

					        if ($res_planilla = mysqli_query($enlace, $q_planilla)){
					          if (mysqli_num_rows($res_planilla) > 0) {
					            while($row_planilla = mysqli_fetch_array($res_planilla)){
					              ?>

					              <option value="<?php echo $row_planilla["Id"]; ?>"><?php echo $row_planilla["descripcion"]; ?></option>

					              <?php
					            }
					          }
					        }

									?>

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Documento:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="empleado_documento" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Apellido Paterno:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="empleado_apellidopaterno" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Apellido Materno:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="empleado_apellidomaterno" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Nombres:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="empleado_nombres" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Cargo:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="empleado_cargo" class="form-select" style="text-align: left;" onchange="f_GetListaTipoDocumento(0)">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_cargos = "SELECT Id,
                      								descripcion
			                           FROM tbconfig_cargos
			                          WHERE estado = 'A'
						                   ORDER BY descripcion";

					        if ($res_cargos = mysqli_query($enlace, $q_cargos)){
					          if (mysqli_num_rows($res_cargos) > 0) {
					            while($row_cargos = mysqli_fetch_array($res_cargos)){
					              ?>

					              <option value="<?php echo $row_cargos["Id"]; ?>"><?php echo $row_cargos["descripcion"]; ?></option>

					              <?php
					            }
					          }
					        }

									?>

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Área:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="empleado_area" class="form-select" style="text-align: left;" onchange="f_GetListaTipoDocumento(0)">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_areas = "SELECT Id,
                      							 descripcion
			                          FROM tbconfig_areas
			                         WHERE estado = 'A'
						                  ORDER BY descripcion";

					        if ($res_areas = mysqli_query($enlace, $q_areas)){
					          if (mysqli_num_rows($res_areas) > 0) {
					            while($row_areas = mysqli_fetch_array($res_areas)){
					              ?>

					              <option value="<?php echo $row_areas["Id"]; ?>"><?php echo $row_areas["descripcion"]; ?></option>

					              <?php
					            }
					          }
					        }

									?>

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Fecha Ingreso:
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="empleado_fechaingreso" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;" value="<?php echo $g_date; ?>">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Fecha Nacimiento:
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="empleado_fechanacimiento" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;" value="<?php echo $g_date; ?>">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Teléfonos:
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="empleado_telefono1" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="empleado_telefono2" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Sexo:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="empleado_sexo" class="form-select" style="text-align: left;" onchange="f_GetListaTipoDocumento(0)">
									<option selected value="">Elija una opción...</option>
									<option value="1">MASCULINO</option>
									<option value="0">FEMENINO</option>
								</select>
							</div>
						</div>
		      </div>

		      <input id="hd_idempleado" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarEmpleado();">Grabar</button>
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
					$("#nv_titulo").html('| Administración de Empleados');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadResultados();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
        var _html = '';
        var d = 1;

        // var cod_condicion = $("#filtro_condicion").val();
        var cod_planilla = $("#filtro_planilla").val();
        var documento = $("#filtro_documento").val();
        var nombres = $("#filtro_nombres").val().trim();
        var cod_cargo = $("#filtro_cargo").val();
        var cod_area = $("#filtro_area").val();

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        var arr_creditos = '';
        var arr_descuentos = '';
        var c = 0;

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_ListaEmpleados", cod_planilla: cod_planilla, documento: documento, nombres: nombres, cod_cargo: cod_cargo, cod_area: cod_area }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.DES_PLANILLA;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.documento;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.NOMBRES;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.CARGO;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.AREA;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.fecha_ingreso;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.fecha_nacimiento;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.TELEFONOS;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + ((val.is_masculino == 1) ? 'M' : 'F');
                _html += '  </td>';

                // Setea el Estado del registro
                  if (val.estado == 'I'){
                    bk_color = '#E6A50D';
                    estado = 'Inactivo';
                    href_estado = 'Activar';
                    href_color = '#44803F';
                    href_icon = 'bi bi-node-plus';
                  }
                  else{
                    bk_color = '#44803F';
                    estado = 'Activo';
                    href_estado = 'Inactivar';
                    href_color = '#E6A50D';
                    href_icon = 'bi bi-node-minus';
                  }

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color:' + ((val.estado == 'I') ? '#E6A50D' : '#44803F') + '; color: #ffffff;">';
                  _html += '      ' + ((val.estado == 'A') ? 'Activo' : 'Inactivo');
                  _html += '  </td>';

                // Agregando acciones
                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';

                  _html += '      <a class="success" href="javascript: f_AdminEmpleados(' + d + ', ' + val.Id + ', ' + val.id_planilla	+ ", '" + val.documento + "', '" + val.apellido_paterno + "', '" + val.apellido_materno + "', '" + val.nombres + "', " + val.id_cargo + ', ' + val.id_area + ", '" + val.fecha_ingreso + "', '" + val.fecha_nacimiento + "', '" + val.telefono1 + "', '" + val.telefono2 + "', " + val.is_masculino + ')"><i class="bi bi-pencil-square"></i>';
                  _html += '          <font style="color: #337ab7;"> Editar</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_CambiarEstado(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                  _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_EliminarRegistro(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                  _html += '          <font style="color: #F20505;"> Eliminar</font>';
                  _html += '      </a>';

                  _html += '  </td>';

                _html += '</tr>';

                d += 1;
              });
            }
            else{
              // alert("No se encontraron resultados.");
            }

            $("#tbl_detalle").html(_html);

          }, "json");
    	};

    	function f_AdminEmpleados(_item, _id_empleado, _id_planilla, _documento, _apellido_paterno, _apellido_materno, _nombres, _id_cargo, _id_area, _fecha_ingreso, _fecha_nacimiento, _telefono1, _telefono2, _sexo){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Empleado: "<b>' + (_apellido_paterno + ' ' + _apellido_materno + ', ' + _nombres).substring(0, 25) + '...</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Nuevo Empleado";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_addempleadoLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addempleado');

	        if (tipo != 'N'){
            $("#hd_idempleado").val(_id_empleado);
            $("#empleado_planilla").val(_id_planilla);
            $("#empleado_documento").val(f_CleanInjection(_documento));
            $("#empleado_apellidopaterno").val(f_CleanInjection(_apellido_paterno));
            $("#empleado_apellidomaterno").val(f_CleanInjection(_apellido_materno));
            $("#empleado_nombres").val(f_CleanInjection(_nombres));
            $("#empleado_cargo").val(f_CleanInjection(_id_cargo));
            $("#empleado_area").val(f_CleanInjection(_id_area));
            $("#empleado_fechaingreso").val(_fecha_ingreso);
            $("#empleado_fechanacimiento").val(_fecha_nacimiento);
            $("#empleado_telefono1").val(_telefono1);
		        $("#empleado_telefono2").val(_telefono2);
		        $("#empleado_sexo").val(_sexo);
			    }
			    else{
			    	$("#hd_idempleado").val(0);
		        $("#empleado_planilla").val('');
		        $("#empleado_documento").val('');
		        $("#empleado_apellidopaterno").val('');
		        $("#empleado_apellidomaterno").val('');
		        $("#empleado_nombres").val('');
            $("#empleado_cargo").val('');
            $("#empleado_area").val('');
            $("#empleado_fechaingreso").val('');
            $("#empleado_fechanacimiento").val('');
		        $("#empleado_telefono1").val('');
		        $("#empleado_telefono2").val('');
		        $("#empleado_sexo").val('');
		   		}
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_CleanTxtTipo(){
				var cod_tipo = $("#filtro_listatipo").val();

				if (cod_tipo == null){
          $("#filtro_tipo").val('');

          return;
        }

        if (cod_tipo.length == 0){
          $("#filtro_tipo").val('');

          return;
        }
			}

			function f_GetListaTipoDocumento(_is_juridico){
				var _html = '<option selected value="">Elija una opción...</option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				if (_is_juridico == 0){
					if ($("#empleado_planilla").val() == 2){
						_is_juridico = 1;
					}
				}

				$.post( "apis/backend.php", { accion: "get_listatipodocumento" }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.res, function( key, val ) {
								_html += '<option value="' + val.Id + '" ' + ((_is_juridico == 1) ? ((val.Id == 2) ? 'selected' : '') : ((val.Id == 1) ? 'selected' : '')) + '>' + val.descripcion + '</option>';
								_html += '<option value="x" style="font-size: 6px;" disabled></option>';
							});

							$("#cliente_tipodocumento").html(_html);
						}
						else{
							$("#cliente_tipodocumento").html('');
						}

					}, "json");
			}

			function f_GetInfoCliente(){
				var is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
					var documento = $("#cliente_documento").val();
					var arr_response = '';

					// Limpiando objetos
						$("#cliente_razonsocial").val('');
          	$("#cliente_direccion").val('');
						$("#wt_razonsocial2").hide();

					// Obteniendo información
						if (documento.length == 8 || documento.length == 11){
							$("#wt_razonsocial2").show();

							$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
		            function( data ) {
		            	if (data.estado == 1){
		            		arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

		            		if (is_ruc == 1){
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	$("#cliente_direccion").val(arr_response[4].split(':')[1].trim());
			            	}
			            	else{
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	$("#cliente_direccion").val('');
			            	}
		            	}
		            	else{
		            		$("#cliente_razonsocial").val('NO ENCONTRADO');
		              	$("#cliente_direccion").val('');
		            	}

		            	$("#wt_razonsocial2").hide();

		            }, "json");
						}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Graba información temporal (onblur).
				function f_GrabarEmpleado(){
					// Recupera variables
						var id_empleado = $("#hd_idempleado").val();
						var modo_grabar = $("#hd_modograbar").val();

            var cod_planilla = f_CleanInjection($("#empleado_planilla").val());
            var documento = f_CleanInjection($("#empleado_documento").val());
            var apellido_paterno = f_CleanInjection($("#empleado_apellidopaterno").val());
            var apellido_materno = f_CleanInjection($("#empleado_apellidomaterno").val());
            var nombres = f_CleanInjection($("#empleado_nombres").val());
            var cod_cargo = f_CleanInjection($("#empleado_cargo").val());
            var cod_area = f_CleanInjection($("#empleado_area").val());
            var fecha_ingreso = f_CleanInjection($("#empleado_fechaingreso").val());
            var fecha_nacimiento = f_CleanInjection($("#empleado_fechanacimiento").val());
            var telefono1 = f_CleanInjection($("#empleado_telefono1").val());
            var telefono2 = f_CleanInjection($("#empleado_telefono2").val());
            var sexo = f_CleanInjection($("#empleado_sexo").val());

          // Validando datos
            if (cod_planilla == null){
              alert("Debe seleccionar la Planilla.");

              return;
            }
            if (cod_planilla.length == 0){
              alert("Debe seleccionar la Planilla.");

              return;
            }

            if (documento == null){
              alert("Debe ingresar el Documento.");

              return;
            }
            if (documento.length == 0){
              alert("Debe ingresar el Documento.");

              return;
            }

            if (apellido_paterno == null){
              alert("Debe ingresar el Apellido Paterno.");

              return;
            }
            if (apellido_paterno.length == 0){
              alert("Debe ingresar el Apellido Paterno.");

              return;
            }

            if (apellido_materno == null){
              alert("Debe ingresar el Apellido Materno.");

              return;
            }
            if (apellido_materno.length == 0){
              alert("Debe ingresar el Apellido Materno.");

              return;
            }

            if (nombres == null){
              alert("Debe ingresar los Nombres.");

              return;
            }
            if (nombres.length == 0){
              alert("Debe ingresar los Nombres.");

              return;
            }

            if (cod_cargo == null){
              alert("Debe eleccionar el Cargo.");

              return;
            }
            if (cod_cargo.length == 0){
              alert("Debe eleccionar el Cargo.");

              return;
            }

            if (cod_area == null){
              alert("Debe eleccionar el Área.");

              return;
            }
            if (cod_area.length == 0){
              alert("Debe eleccionar el Área.");

              return;
            }

            if (sexo == null){
              alert("Debe eleccionar el Sexo.");

              return;
            }
            if (sexo.length == 0){
              alert("Debe eleccionar el Sexo.");

              return;
            }

          // Grabando Datos
            $.post( "apis/backend.php", { accion: "grabar_Empleado", modo_grabar: modo_grabar, id_empleado: id_empleado, cod_planilla: cod_planilla, documento: documento, apellido_paterno: apellido_paterno, apellido_materno: apellido_materno, nombres: nombres, cod_cargo: cod_cargo, cod_area: cod_area, fecha_ingreso: fecha_ingreso, fecha_nacimiento: fecha_nacimiento, telefono1: telefono1, telefono2: telefono2, sexo: sexo },
              function( data ) {
                if (data.estado == 2){
                  alert("El documento ingresado ya fue registrado anteriormente.\n\nPor favor verificar");

                  return;
                }
                else{
                  if(data.estado == 1){
                  	f_LoadResultados();

                  	f_cerrarModal('modal_addempleado');
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar el Empleado");
                  }
                }

              }, "json");
				}

			// Cambiar estado de registros
        function f_CambiarEstado(_Estado, _id_empleado){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " el Empleado seleccionado?")){
            $.post( "apis/backend.php", { accion: "update_EstadoEmpleado", id_empleado: _id_empleado, estado: _Estado }, 
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de cambiar el estado");
                }

              }, "json");
          }
        };

      // Eliminar registros
        function f_EliminarRegistro(_id_empleado){
          if(confirm("¿Está seguro de eliminar el Empleado seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_Empleado", id_empleado: _id_empleado },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar el Empleado.");
                }
              }, "json");
          }
        };
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');
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

		<script type="text/javascript">
			// Funciones Principales
				function f_LoadAnhos(){
					// Carga filtros de Periodo
						$.post( "apis/backend.php", { accion: "get_Anhos" }, 
							function( data ) {
								if(data.estado == 1){
									$("#filtro_anho").html(data.html);

									f_LoadMeses();
								}
								else{
									$("#filtro_anho").val('');
									$("#filtro_mes").val('');
								}

							}, "json");
				}

				function f_LoadMeses(){
					var _anho = $("#filtro_anho").val();

					// Carga filtros de Periodo
						$.post( "apis/backend.php", { accion: "get_Meses", anho: _anho}, 
							function( data ) {
								if(data.estado == 1){
									$("#filtro_mes").html(data.html);

									f_LoadDashboard();
								}
								else{
									$("#filtro_mes").val('');
								}

							}, "json");
				}

				function f_LoadDashboard(){
					$("#lbl_anho").html('Año: <b>' + $("#filtro_anho").val() + '</b>');
					$("#lbl_mes").html('Mes: <b>' + $("#filtro_mes option:selected").text() + '</b>');

					// Obteniendo filtros
						var filtro_anho = $("#filtro_anho").val();
						var filtro_mes = $("#filtro_mes").val();

					// Cargando el Chart Principal
						$("#chart_main").load("charts/chart_mainnps.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes);

					// Cargando Interacciones
						$.post( "apis/backend.php", { accion: "get_Interacciones", filtro_anho: filtro_anho, filtro_mes: filtro_mes }, 
							function( data ) {
								if(data.estado == 1){
									$("#int_1").html(data.totalitems_nps.split('|')[0]);
									$("#int_2").html(data.totalitems_nps.split('|')[1]);
									$("#int_3").html(data.totalitems_nps.split('|')[2]);
									$("#int_4").html(data.totalitems_nps.split('|')[3]);
									$("#int_5").html(data.totalitems_nps.split('|')[4]);
								}
								else{
									$("#int_1").val('');
									$("#int_2").val('');
									$("#int_3").val('');
									$("#int_4").val('');
									$("#int_5").val('');
								}

							}, "json");

						// Cargando Pies
							// Operaciones Ventanilla
								$("#chart_int1").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 1);

							// Asesores de Negocio
								$("#chart_int2").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 2);

							// Call Center
								$("#chart_int3").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 3);

							// Agentes Corresponsales
								$("#chart_int4").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 4);

							// App Móvil
								$("#chart_int5").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 5);
				}
		</script>
	</body>
</html>