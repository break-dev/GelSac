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

		<title><?php echo $nom_app; ?> | Preparación - Gestión Muestras</title>

		<script type="text/javascript">
			let itemlote_Selected = 0;
      let codlote_Selected = 0;

      let itemprog_Selected = 0;
      let idprog_Selected = 0;
		</script>

		<style>
			#colorSeleccionado + .jscolor {
        position: absolute;
        z-index: 2; /* Ajusta este valor según sea necesario */
        top: 0; /* Ajusta estos valores según sea necesario */
        left: 0; /* Ajusta estos valores según sea necesario */
      }

		</style>
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
											<h6 style="font-size: 14px;">Por Fecha de Fin de Proceso</h6>
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

								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;" hidden>
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Tipo Muestra:</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_tipomuestra" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_tipo = "SELECT Id,
                            							UPPER(descripcion) AS descripcion
					                           FROM tbconfig_tipomuestraspreparacion
					                          WHERE estado = 'A'";

								        if ($res_tipo = mysqli_query($enlace, $q_tipo)){
								          if (mysqli_num_rows($res_tipo) > 0) {
								            while($row_tipo = mysqli_fetch_array($res_tipo)){
								              ?>

								              <option value="<?php echo $row_tipo["Id"]; ?>"><?php echo $row_tipo["descripcion"]; ?></option>

								              <?php
								            }
								          }
								        }

												?>
											</select>
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
							</div>

							<div class="row" style="padding-left: 30px; margin-top: 5px; margin-bottom: 10px; font-size: 13px;">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<button class="btn btn-secondary" type="button" onclick="f_LoadLotes();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
			              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
		            	</button>
		            </div>
							</div>
						</div>

						<div class="row" style="padding: 0px;">
							<div id="div_plantas" class="col-md-3 col-sm-3 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<h5>Lista de Lotes</h5>

												<div id="wt_lotes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Fecha Hora Ingreso Planta
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Cód. Lote
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px; border-top-right-radius: 15px;">
						        				Peso Neto (Tn)
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_lotes">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_detalle" class="col-md-9 col-sm-9 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-9 col-sm-9 col-xs-12">
												<div class="d-flex">
													<div id="div_ShowListaProgramaciones" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px;" onclick="f_HideListaProgramaciones(1);">
	                          <i class="bi bi-arrow-left" style="font-size: 18px;"></i>
	                        </div>

	                        <div id="div_HideListaProgramaciones" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px; display: none;" onclick="f_HideListaProgramaciones(0);">
	                          <i class="bi bi-arrow-right" style="font-size: 18px;"></i>
	                        </div>

													<h5>Información de Muestra Primaria para: </h5>
													<h5 id="lbl_titulomuestraprimaria" style="margin-left: 5px; color: #337ab7;"></h5>

													<div id="wt_muestrasprimarias" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>

											<div class="col-md-3 col-sm-3 col-xs-12">
												<div class="d-flex justify-content-end">
													<button type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AdminRegistroMuestra('x', 1);">+ Nuevo Registro</button>
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
						        			<th colspan="3" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 40px; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px;">
						        				Destino
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 200px;">
						        				Fecha Hora Envío<br>a Laboratorio
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 200px;">
						        				Nombre
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 60px;">
						        				Peso (Kg)
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px;">
						        				Estado
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px;">
						        				Envase
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px; border-top-right-radius: 15px;">
						        				Registro
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 130px;">
						        				Fecha Hora
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 120px;">
						        				Usuario
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_MuestrasPrimaria">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>

								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-9 col-sm-9 col-xs-12">
												<div class="d-flex" style="margin-left: 40px;">
													<h5>Información de Muestra Proveedor Minero para: </h5>
													<h5 id="lbl_titulomuestraproveedorminero" style="margin-left: 5px; color: #337ab7;"></h5>

													<div id="wt_muestrasproveedorminero" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>

											<div class="col-md-3 col-sm-3 col-xs-12">
												<div class="d-flex justify-content-end">
													<button type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AdminRegistroMuestra('x', 0);">+ Nuevo Registro</button>
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
						        			<th colspan="3" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 40px; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px;">
						        				Destino
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 200px;">
						        				Fecha Hora Envío<br>a Laboratorio
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 200px;">
						        				Nombre
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 60px;">
						        				Peso (Kg)
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px;">
						        				Estado
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px;">
						        				Envase
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Entregado a
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 250px; border-top-right-radius: 15px;">
						        				Registro
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 130px;">
						        				Fecha Hora
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 120px;">
						        				Usuario
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_MuestrasProveedorMinero">
						        		
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
		<div class="modal fade modal-dialog-scrollable" id="modal_adminmuestrasprimaria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminmuestrasprimariaLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_adminmuestrasprimariaLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
								Destino:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<select id="add_MuestraPrimaria_Destino" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;" onchange="f_SetNombreMuestra();">

								</select>
							</div>
						</div>

						<div id="div_fechahoraentrega" class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px; display: none;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
								Entrega:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<div class="d-flex">
									<input id="fecha_entrega" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">

									<input id="hora_entrega" type="time" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px; width: 150px;" value="<?php echo substr($g_time, 0, 5); ?>">
								</div>
							</div>
						</div>

						<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
								Nombre Muestra:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<input id="add_MuestraPrimaria_NombreMuestra" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase; font-size: 14px; text-align: center; font-weight: bold;">
							</div>
						</div>

						<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
								Peso Muestra (Kg):
							</div>

							<div class="col-md-3 col-sm-3 col-xs-3">
								<input id="add_MuestraPrimaria_PesoMuestra" type="number" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center;">
							</div>

							<div class="col-md-3 col-sm-3 col-xs-3">
								<div class="form-check form-switch" style="margin-top: 5px;">
								  <input class="form-check-input" type="checkbox" role="switch" id="chk_InterfazAuto" onchange="f_GetPeso_Auto();" checked>
								  <label id="lbl_getpeso_check" class="form-check-label" for="chk_InterfazAuto">Automático</label>
								</div>
							</div>
						</div>

		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
								Estado Muestra:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<select id="add_MuestraPrimaria_EstadoMuestra" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_lista = "SELECT Id,
																		 descripcion
																FROM tbconfig_preparacionestadomuestra
															 WHERE estado = 'A'
															ORDER BY descripcion";

									if ($res_lista = mysqli_query($enlace, $q_lista)){
										if (mysqli_num_rows($res_lista) > 0) {
											while($row_lista = mysqli_fetch_array($res_lista)){
												?>

												<option value="<?php echo $row_lista["Id"] ?>"><?php echo $row_lista["descripcion"] ?></option>

												<?php
											}
										}
									}

									?>
								</select>
							</div>
						</div>

		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
								Envase Muestra:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<select id="add_MuestraPrimaria_EnvaseMuestra" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_lista = "SELECT Id,
																		 descripcion
																FROM tbconfig_preparacionenvasemuestra
															 WHERE estado = 'A'
															ORDER BY descripcion";

									if ($res_lista = mysqli_query($enlace, $q_lista)){
										if (mysqli_num_rows($res_lista) > 0) {
											while($row_lista = mysqli_fetch_array($res_lista)){
												?>

												<option value="<?php echo $row_lista["Id"] ?>"><?php echo $row_lista["descripcion"] ?></option>

												<?php
											}
										}
									}

									?>
								</select>
							</div>
						</div>

						<div id="div_encargadomuestra" class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px; display: none;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
								Entregado a:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<input id="add_MuestraPrimaria_EncargadoMuestra" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase;">
							</div>
						</div>
		      </div>

		      <input id="hd_modograbar" type="hidden">
		      <input id="hd_idregistromuestra" type="hidden">
		      <input id="hd_ismuestraprimaria" type="hidden">

		      <div class="modal-footer" style="margin-top: -10px;">
		      	<div id="wt_grabar" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabar_button" data-bs-dismiss="modal" style="font-size: 14px;" onclick="f_BreakAutomatico();">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_grabar_button" style="font-size: 14px;" onclick="f_ConfirmarRegistroMuestra();">Grabar</button>
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
					$("#nv_titulo").html('| Preparación - Gestión Muestras');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadLotes();
			}
		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			$('#filtro_MuestraPrimaria_Destino, #filtro_modalidadenvio').select2({
		    theme: "bootstrap-5",
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		    placeholder: $( this ).data( 'placeholder' ),
		    allowClear: true,
		    dropdownParent: $('#modal_adminmuestrasprimaria')
			});

			$('#tipo_unidad, #distribucion_unidad, #distribucion_unidad2').select2({
		    theme: "bootstrap-5",
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		    placeholder: $( this ).data( 'placeholder' ),
		    allowClear: true,
		    dropdownParent: $('#modal_admindistribuciones')
			});

			$('#filtro_MuestraPrimaria_Destino, #filtro_modalidadenvio, #tipo_unidad, #distribucion_unidad, #distribucion_unidad2').next('.select2-container').find('.select2-selection__rendered').css('font-size', '14px');
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadLotes(){
        var _html = '';

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var cod_tipomuestra = $("#filtro_tipomuestra").val();
        var filtro_lote = $("#filtro_lote").val();

        // Validando datos

				// Cargando Lista de Racks
	        $("#tbl_lotes").html('');

	        f_LoadingLotes(1);

	        $.post( "apis/backend.php", { accion: "get_PreparacionGestionMuestras_ListaLotes", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, cod_tipomuestra: cod_tipomuestra, cod_lote: filtro_lote }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_lotes").html(data.html);

	            	itemlote_Selected = 1;
								codlote_Selected = data.cod_lote;

								f_LoadItemLote(itemlote_Selected, codlote_Selected);
	            }

	            f_LoadingLotes(0);

	          }, "json");
    	}

      function f_LoadItemLote(_item, _cod_lote){
        var _html = '';

        // Pinta selección
          f_ColorSelected_Lote(_item);

        // Cargando datos de Muestra Primaria
          f_LoadingMuestrasPrimarias(1);

          $("#tbl_MuestrasPrimaria").html(_html);

          $.post( "apis/backend.php", { accion: "get_PreparacionGestionMuestras_Primaria", cod_lote: _cod_lote }, 
            function( data ) {
              if(data.estado == 1){
                $("#tbl_MuestrasPrimaria").html(data.html);
              }

              f_LoadingMuestrasPrimarias(0);

            }, "json");

        // Cargando datos de Muestra Proveedor Minero
          f_LoadingMuestrasProveedorMinero(1);

          $("#tbl_MuestrasProveedorMinero").html(_html);

          $.post( "apis/backend.php", { accion: "get_PreparacionGestionMuestras_ProveedorMinero", cod_lote: _cod_lote }, 
            function( data ) {
              if(data.estado == 1){
                $("#tbl_MuestrasProveedorMinero").html(data.html);
              }

              f_LoadingMuestrasProveedorMinero(0);

            }, "json");

        itemlote_Selected = _item;
        codlote_Selected = _cod_lote;
      }

      function f_AdminRegistroMuestra(_item, _is_muestraprimaria, _id_registromuestra, _id_destino, _fechahora_enviolaboratorio, _nom_muestra, _peso_muestra, _id_estadomuestra, _id_envasemuestra, _encargado_muestra){
      	// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";

            if (_is_muestraprimaria == 1){
            	titulo = 'Muestra Primaria - Editar Registro';
            }
            else{
            	titulo = 'Muestra Proveedor Minero - Editar Registro';
            }
	        }
	        else{
            tipo = "N";

            if (_is_muestraprimaria == 1){
            	titulo = 'Muestra Primaria - Nuevo Registro';
            }
            else{
            	titulo = 'Muestra Proveedor Minero - Nuevo Registro';
            }
	        }

		    // Colocando el título a la pantalla
	        $("#modal_adminmuestrasprimariaLabel").html(titulo);

		    // Seteando variables hidden
	        $("#hd_modograbar").val(tipo);
	        $("#hd_idregistromuestra").val(_id_registromuestra);
	        $("#hd_ismuestraprimaria").val(_is_muestraprimaria);

	      // Cargando destindos
	        f_LoadDestinos(_id_destino);

		    // Cargando datos
	        f_OpenModal('modal_adminmuestrasprimaria');

	        $("#div_fechahoraentrega").hide();
		      $("#div_encargadomuestra").hide();

	        if (tipo != 'N'){
            $("#hd_idregistromuestra").val(_id_registromuestra);

            $("#add_MuestraPrimaria_Destino").val(_id_destino);
		        $("#add_MuestraPrimaria_NombreMuestra").val(_nom_muestra);
		        $("#add_MuestraPrimaria_PesoMuestra").val(_peso_muestra);
		        $("#add_MuestraPrimaria_EstadoMuestra").val(_id_estadomuestra);
		        $("#add_MuestraPrimaria_EnvaseMuestra").val(_id_envasemuestra);

		        if (_id_destino == 3 || _id_destino == 4){
		        	$("#div_fechahoraentrega").show();

		        	$("#fecha_entrega").val(_fechahora_enviolaboratorio.substring(0, 10));
		        	$("#hora_entrega").val(_fechahora_enviolaboratorio.substring(11));
		        }

		        if (_id_destino == 5){
							$("#div_encargadomuestra").show();

							$("#add_MuestraPrimaria_EncargadoMuestra").val(_encargado_muestra);
		        }

		        $("#chk_InterfazAuto").prop('checked', false);
			    }
			    else{
			    	$("#hd_idregistromuestra").val(0);

		        $("#add_MuestraPrimaria_Destino").val('');
		        $("#fecha_entrega").val('<?php echo $g_date; ?>');
		        $("#hora_entrega").val('<?php echo substr($g_time, 0, 5); ?>');
		        $("#add_MuestraPrimaria_NombreMuestra").val('');
		        $("#add_MuestraPrimaria_PesoMuestra").val('');
		        $("#add_MuestraPrimaria_EstadoMuestra").val('');
		        $("#add_MuestraPrimaria_EnvaseMuestra").val('');
		        $("#add_MuestraPrimaria_EncargadoMuestra").val('');

		        $("#chk_InterfazAuto").prop('checked', true);
		   		}

		   		f_GetPeso_Auto();
      };

      function f_SetNombreMuestra(){
      	var is_muestraprimaria = $("#hd_ismuestraprimaria").val();
      	var id_destino = $("#add_MuestraPrimaria_Destino").val();
      	var nom_muestra = '';

      	// Determina el nombre de la muestra
      		if (id_destino.length == 0){
      			$("#add_MuestraPrimaria_NombreMuestra").val('');
      		}
      		else{
      			$("#div_fechahoraentrega").hide();
      			$("#div_encargadomuestra").hide();

      			if (id_destino == 3 || id_destino == 4){
      				$("#div_fechahoraentrega").show();
      			}

      			if (id_destino == 5){
      				$("#div_encargadomuestra").show();
      			}

      			$.post( "apis/backend.php", { accion: "set_PreparacionGestionMuestras_NombreMuestras", is_muestraprimaria: is_muestraprimaria, cod_lote: codlote_Selected, id_destino: id_destino }, 
	            function( data ) {
	              if(data.estado == 1){
	                $("#add_MuestraPrimaria_NombreMuestra").val(data.nom_muestra);
	              }

	            }, "json");
      		}
      }

      function f_LoadDestinos(_id_destino){
      	var id_tipomuestrapreparacion = (($("#hd_ismuestraprimaria").val() == 1) ? $("#hd_ismuestraprimaria").val() : 2);

      	// Cargando destinos
      		var _html = '';

      		$("#add_MuestraPrimaria_Destino").html(_html);

          $.post( "apis/backend.php", { accion: "get_PreparacionGestionMuestras_ListaDestinos", id_tipomuestrapreparacion: id_tipomuestrapreparacion, id_destino: ((_id_destino == undefined) ? '' : _id_destino) }, 
            function( data ) {
              if(data.estado == 1){
                $("#add_MuestraPrimaria_Destino").html(data.html);
              }

            }, "json");
      }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
      function f_ColorSelected_Lote(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_lotes tr").each(function () {
          $("#tr_lote_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_lote_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulomuestraprimaria").html($("#td_lote_" + _item).html().trim());
          $("#lbl_titulomuestraproveedorminero").html($("#td_lote_" + _item).html().trim());
      }

			function f_LoadingLotes(_is_show){
				if (_is_show == 1){
					$("#wt_lotes").show();
				}
				else{
					$("#wt_lotes").hide();
				}
			}

			function f_LoadingMuestrasPrimarias(_is_show){
				if (_is_show == 1){
					$("#wt_muestrasprimarias").show();
				}
				else{
					$("#wt_muestrasprimarias").hide();
				}
			}

			function f_LoadingMuestrasProveedorMinero(_is_show){
				if (_is_show == 1){
					$("#wt_muestrasproveedorminero").show();
				}
				else{
					$("#wt_muestrasproveedorminero").hide();
				}
			}

			function f_LoadingGrabarRegistroMuestra(_is_show){
				if (_is_show == 1){
					$("#wt_grabar").show();

					$(".wt_grabar_button").prop('disabled', true);
				}
				else{
					$("#wt_grabar").hide();

					$(".wt_grabar_button").prop('disabled', false);
				}
			}

      function f_HideListaProgramaciones(_x){
        if (_x == 1){
          $("#div_plantas").hide();
          $("#div_detalle").width('100%');

          f_CerrarDiv('C', 'div_ShowListaProgramaciones');
          f_CerrarDiv('A', 'div_HideListaProgramaciones');
          }
        else{
          $("#div_plantas").show();
          $("#div_detalle").width('');

          f_CerrarDiv('A', 'div_ShowListaProgramaciones');
          f_CerrarDiv('C', 'div_HideListaProgramaciones');
        }
      }

		  function f_GetPeso_Auto(){
		  	var is_auto = (($("#chk_InterfazAuto").prop('checked')) ? 1 : 0);

		  	if (is_auto == 1){
		  		$('#lbl_getpeso_check').html('Automático');

		  		f_GetPeso(1);
		  	}
		  	else{
		  		$('#lbl_getpeso_check').html('Manual');

		  		f_BreakAutomatico();
		  	}
		  }

		  function f_GetPeso(_on){
				if (_on == 1){
					$.get( "apis/interfaces.php", { accion: "get_Peso", cod_balanza: 2 }, 
	          function( data ) {
	            if(data.estado == 1){
	              if (data.peso == -1){
	                $("#div_SinConexion").show();

	                $("#add_MuestraPrimaria_PesoMuestra").val('');
	              }
	              else{
	                $("#div_SinConexion").hide();

	                $("#add_MuestraPrimaria_PesoMuestra").val(data.peso);
	              }
	            }
	            else{
	              $("#div_SinConexion").show();
	            }

	            // Verifica si es Automático o Manual
	            	if ($("#chk_InterfazAuto").prop('checked')){
	            		setTimeout('f_GetPeso(1)', 1000);
	            	}
	            	else{
	            		$("#div_SinConexion").hide();
	            	}

	          }, "json");
				}
			}

		  function f_BreakAutomatico(){
		  	$("#chk_InterfazAuto").prop('checked', false);

	  		$("#div_SinConexion").hide();

	  		f_GetPeso(0);
		  }
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_ConfirmarRegistroMuestra(){
				// Recuperando datos hidden
					var modo_grabar = $("#hd_modograbar").val();
					var id_registromuestra = $("#hd_idregistromuestra").val();
					var is_muestraprimaria = $("#hd_ismuestraprimaria").val();

				// Obteniendo datos
					var id_destino = $("#add_MuestraPrimaria_Destino").val();
					var fecha_entrega = $("#fecha_entrega").val();
					var hora_entrega = $("#hora_entrega").val();
					var fechahora_entrega = '';
					var nom_muestra = f_CleanInjection($("#add_MuestraPrimaria_NombreMuestra").val().trim());
					var peso_muestra = $("#add_MuestraPrimaria_PesoMuestra").val();
					var estado_muestra = $("#add_MuestraPrimaria_EstadoMuestra").val();
					var envase_muestra = $("#add_MuestraPrimaria_EnvaseMuestra").val();
					var encargado_muestra = $("#add_MuestraPrimaria_EncargadoMuestra").val();

					if (is_muestraprimaria == 1){
						encargado_muestra = '';
					}

				// Validando datos
          if (id_destino == null){
            alert("Debe seleccionar el Destino.");

            return;
          }
          if (id_destino.length == 0){
            alert("Debe seleccionar el Destino.");

            return;
          }

          if (id_destino == 3 || id_destino == 4){
          	if (fecha_entrega == null){
	            alert("Debe ingresar la Fecha de Entrega.");

	            return;
	          }
	          if (fecha_entrega.length == 0){
	            alert("Debe ingresar la Fecha de Entrega.");

	            return;
	          }

	          if (hora_entrega == null){
	            alert("Debe ingresar la Hora de Entrega.");

	            return;
	          }
	          if (hora_entrega.length == 0){
	            alert("Debe ingresar la Hora de Entrega.");

	            return;
	          }

	          fechahora_entrega = fecha_entrega + ' ' + hora_entrega;
          }
          else{
          	fechahora_entrega = '';
          }

          if (nom_muestra == null){
            alert("Debe ingresar el Nombre de la Muestra.");

            return;
          }
          if (nom_muestra.length == 0){
            alert("Debe ingresar el Nombre de la Muestra.");

            return;
          }

          if (peso_muestra == null){
            alert("Debe ingresar el Peso de la Muestra.");

            return;
          }
          if (peso_muestra.length == 0){
            alert("Debe ingresar el Peso de la Muestra.");

            return;
          }
          if (peso_muestra <= 0){
            alert("El Peso de la Muestra ingresado no es válido.");

            return;
          }

          if (estado_muestra == null){
            alert("Debe seleccionar el Estado de la Muestra.");

            return;
          }
          if (estado_muestra.length == 0){
            alert("Debe seleccionar el Estado de la Muestra.");

            return;
          }

          if (envase_muestra == null){
            alert("Debe ingresar el Envase de la Muestra.");

            return;
          }
          if (envase_muestra.length == 0){
            alert("Debe ingresar el Envase de la Muestra.");

            return;
          }

        // Grabando Datos
	        f_LoadingGrabarRegistroMuestra(1);

        	$.post( "apis/backend.php", { accion: "grabar_PreparacionGestionMuestras_Registro", modo_grabar: modo_grabar, id_tipomuestrapreparacion: ((is_muestraprimaria == 1) ? is_muestraprimaria : 2), cod_lote: codlote_Selected, id_registromuestra: id_registromuestra, id_destino: id_destino, fechahora_entrega: fechahora_entrega, nom_muestra: nom_muestra, peso_muestra: peso_muestra, id_estadomuestra: estado_muestra, id_envasemuestra: envase_muestra, encargado_muestra: encargado_muestra },
            function( data ) {
              if(data.estado == 1){
              	f_LoadItemLote(itemlote_Selected, codlote_Selected);

              	f_cerrarModal('modal_adminmuestrasprimaria');
              }
              else{
                alert("Ocurrió un error al momento de agregar el registro.");
              }

              f_LoadingGrabarRegistroMuestra(0);

            }, "json");
			}

      function f_EliminarRegistro(_id_registro){
        if(confirm("¿Está seguro de eliminar el Regsitro seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_PreparacionGestionMuestras_Registro", id_registro: _id_registro },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemLote(itemlote_Selected, codlote_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Modelo.");
              }

            }, "json");
        }
      };
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 400){
					
				}
			}
		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>