<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');

if (!isset($_SESSION["Id"])) {
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
	<link rel="icon" href="<?php echo $favicon; ?>" type="image/png" />

	<!-- Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

	<!-- Íconos -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

	<!-- Select2 -->
	<!-- Select2 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

	<!-- JSColor -->
	<script src="libs/jscolor/jscolor.js"></script>

	<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

	<title><?php echo $nom_app; ?> | Programación de Despachos</title>

	<script type="text/javascript">
		let itemplanta_Selected = 0;
		let idplanta_Selected = 0;

		let itemprog_Selected = 0;
		let idprog_Selected = 0;

		var itemagrupacion_Selected = 0;
		var iddistribucionunidad_selected = 0;
		var iddestino_selected = 0;
		var idmodalidadenvio_selected = 0;
		var codigodespacho_selected = 0;
		var fechaestimadadespacho_selected = 0;
		var placa_selected = '';
		var idproveedorminero_selected = 0;
	</script>

	<style>
		#colorSeleccionado+.jscolor {
			position: absolute;
			z-index: 2;
			/* Ajusta este valor según sea necesario */
			top: 0;
			/* Ajusta estos valores según sea necesario */
			left: 0;
			/* Ajusta estos valores según sea necesario */
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
	           		<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">Por Fecha de Programación</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="row" >
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">
										</div>
										<br><br>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">
										</div>
									</div>
								</div>
	           	</div>

        			<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Estado de Cierre</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
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

							<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Códigos Despacho:</h6>
										</div>
										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
										</div>
										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_codigodespacho" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
												<?php

												$q_lotes = "SELECT DISTINCT codigo_despacho
																				FROM despachos_segundotramo_programacion_detalle
																			ORDER BY codigo_despacho DESC";

												if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
													if (mysqli_num_rows($res_lotes) > 0) {
														while ($row_lotes = mysqli_fetch_array($res_lotes)) {
												?>

															<option value="<?php echo $row_lotes["codigo_despacho"]; ?>"><?php echo $row_lotes["codigo_despacho"]; ?></option>

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

												if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
													if (mysqli_num_rows($res_lotes) > 0) {
														while ($row_lotes = mysqli_fetch_array($res_lotes)) {
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
							<hr style="border-color: #D9D9D9;" />
						</div>
					</div>

					<div class="row" style="padding: 0px;">
						<div id="div_plantas" class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
							<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="d-flex">
											<h5>Lista de Plantas</h5>

											<div id="wt_plantas" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Cargando datos...</label>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
									<table class="table table-bordered table-hover">
										<thead>
											<tr style="font-size: 12px;">
												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 10%; border-top-left-radius: 15px;">
													N°
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 90%; border-top-right-radius: 15px;">
													Planta
												</th>
											</tr>
										</thead>

										<tbody id="tbl_plantas">

										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div id="div_detalle" class="col-md-10 col-sm-10 col-xs-12" style="padding: 0px; padding-left: 5px;">
							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="col-md-8 col-sm-8 col-xs-12">
											<div class="d-flex">
												<div id="div_ShowListaProgramaciones" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px;" onclick="f_HideListaProgramaciones(1);">
													<i class="bi bi-arrow-left" style="font-size: 18px;"></i>
												</div>

												<div id="div_HideListaProgramaciones" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px; display: none;" onclick="f_HideListaProgramaciones(0);">
													<i class="bi bi-arrow-right" style="font-size: 18px;"></i>
												</div>

												<h5>Historial de Programaciones de: </h5>
												<h5 id="lbl_tituloplanta" style="margin-left: 5px; color: #337ab7;"></h5>

												<div id="wt_programacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
													<img src="<?php echo $img_waiting ?>" style="width: 20px;">
													<label style="font-style: italic;"> Cargando datos...</label>
												</div>
											</div>
										</div>

										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="d-flex justify-content-end">
												<button type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AddProgramacion('N');">+ Nueva Programación</button>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
									<table class="table table-bordered table-hover">
										<thead>
											<tr style="font-size: 12px;">
												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
													N°
												</th>

												<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Programación
												</th>

												<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
													Información Lote
												</th>

												<!-- <th colspan="2" class="th_codigoscmh" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px; display: none;">
													Códigos
												</th> -->

												<!-- <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
													Código Despacho
												</th> -->

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
													Código Planta
												</th>

												<th id="th_codigocomercializacion" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; display: none;">
													Código Despacho<br>Comercialización
												</th>

												<th id="th_codigoplanta" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px; display: none;">
													Código Planta
												</th>

												<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
													Información Pesos
												</th>

												<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
													Cierre
												</th>
											</tr>

											<tr style="font-size: 12px;">
												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													Acción
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
													Fecha Hora<br>Registro
												</th>

												<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
													Lote
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
													Código GEL
												</th>

												<!-- <th class="th_codigoscmh" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; display: none;">
													Documentos
												</th>

												<th class="th_codigoscmh" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; display: none;">
													Guías
												</th> -->

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
													TMH<br><label style="font-size: 12px;">(Primer Tramo)</label>
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													TMS
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													TMH<br>Distribuído
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													Acción
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													Fecha Estimada Despacho
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													Fecha Hora
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Usuario
												</th>
											</tr>
										</thead>

										<tbody id="tbl_programacion">

										</tbody>
									</table>
								</div>
							</div>

							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="col-md-8 col-sm-8 col-xs-12">
											<div class="d-flex">
												<h5>Distribución de Lotes</h5>
												<div id="wt_distribucion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
													<img src="<?php echo $img_waiting ?>" style="width: 20px;">
													<label style="font-style: italic;"> Cargando datos...</label>
												</div>
											</div>
										</div>

										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="d-flex justify-content-end">
												<button type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px; z-index: 1;" onclick="f_AddDistribucion('N');">+ Nueva Distribución</button>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
									<table class="table table-bordered table-hover">
										<thead>
											<tr style="font-size: 12px;">
												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px; border-top-left-radius: 15px;">
													N°<br>Unidad
												</th>

												<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Información Unidad
												</th>

												<th id="th_distribucion_infolote" colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Información Lote
												</th>

												<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Proveedor
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													TMH<br>Distribuído
												</th>

												<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Información Pesos<br>Por Unidad
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
													Presentación
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													Fecha Ingreso<br>a Planta
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
													Hora Ingreso<br>a Planta
												</th>

												<!-- <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
													Guías a<br>Generar
												</th> -->
											</tr>

											<tr style="font-size: 12px;">
												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													Acción
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 270px;">
													Tipo Vehículo
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
													Placa 1
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
													Placa 2
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Capacidad<br>(Tn)
												</th>

												<!-- <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
													Coordinador
												</th> -->

												<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Lote
												</th>

												<th id="th_distribucion_codplanta" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px; display: none;">
													Cód. Planta
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 40px;">
													N° Parte
												</th>

												<!-- <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
													Responsable Despacho
												</th> -->

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
													RUC
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
													Razón Social
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													TMH Distribuído<br>Total
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
													TMH<br>(Para completar Capacidad del vehículo)
												</th>
											</tr>
										</thead>

										<tbody id="tbl_distribucion">

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
	<div class="modal fade modal-dialog-scrollable" id="modal_adminprogramaciones" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminprogramacionesLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_adminprogramacionesLabel"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="d-flex" style="padding: 5px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 90px;">
									Por Lote:
								</h6>

								<input id="filtrolotes_lote" type="text" class="form-control" style="text-align: center; font-size: 14px;">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="d-flex" style="padding: 3px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 92px;">
									Prov. Minero:
								</h6>

								<div class="flex-fill" style="width: 80%; max-width: 80%; min-width: 80%;">
									<select id="filtro_proveedorminero" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;">
										<option selected value="">Elija una opción...</option>

										<?php

										$q_lista = "SELECT Id,
																				 documento,
																				 UPPER(razon_social) AS razon_social
																		FROM tb_clientes
																	 WHERE estado = 'A'
																	 	 AND cod_clientecondicion = 1
																	ORDER BY razon_social";

										if ($res_lista = mysqli_query($enlace, $q_lista)) {
											if (mysqli_num_rows($res_lista) > 0) {
												while ($row_lista = mysqli_fetch_array($res_lista)) {
										?>

													<option value="<?php echo $row_lista["Id"] ?>"><?php echo $row_lista["documento"].' - '.$row_lista["razon_social"] ?>"</option>

										<?php
												}
											}
										}

										?>
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="d-flex" style="padding: 3px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 120px;">
									Modalidad Envío:
								</h6>

								<div class="flex-fill" style="max-width: 65%;">
									<select id="filtro_modalidadenvio" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;">

									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="d-flex justify-content-center" style="padding: 5px; margin-top: 5px;">
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
									<th colspan="10" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
										Información Lotes
									</th>
								</tr>

								<tr style="font-size: 12px;">
									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 55px;">
										Sel.<br>
										<input id="th_Chk" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.2);" onchange="f_SelectChkLotes();">
									</th>

									<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Información Lote
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Encargado Muestra
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Proveedor Minero
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Modalidad Envío
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Neto<br>(TMH)
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Neto<br>(TMS)
									</th>
								</tr>

								<tr style="font-size: 12px;">
									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 110px;">
										Lote
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 40px;">
										Parte
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 110px;">
										Código GEL
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
								<div class="d-flex">
									<label>
										Total TMH:
									</label>

									<label id="lbl_totaltmh" style="font-weight: bold; margin-left: 5px;">0</label>
								</div>

								<div class="d-flex" style="margin-left: 10px; margin-right: 10px;">
									|
								</div>

								<div class="d-flex">
									<label>
										Total TMS:
									</label>

									<label id="lbl_totaltms" style="font-weight: bold; margin-left: 5px;">0</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<input id="modo_grabarprogramacion" type="hidden">
				<input id="id_programacion" type="hidden">
				<input id="item_programacion" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_grabarprogramacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabarprogramacion_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarprogramacion_button" style="font-size: 14px;" onclick="f_ConfirmarProgramacion();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-dialog-scrollable" id="modal_admindistribuciones" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_admindistribucionesLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_admindistribucionesLabel"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row" style="padding: 5px;">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="d-flex" style="padding: 3px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; margin-left: 5px; text-align: center;">
									Tipo Vehículo:
								</h6>

								<div class="flex-fill" style="margin-left: 10px; width: 355px; max-width: 355px; min-width: 355px;">
									<select id="tipo_unidad" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;" onchange="f_LoadUnidades();">
										<option selected value="">Elija una opción...</option>

										<?php

										$q_lista = "SELECT Id,
																				 descripcion,
																				 tiene_carreta
																		FROM tbconfig_tipovehiculo
																	 WHERE estado = 'A'
																	 	 AND is_carreta = 0
																	ORDER BY descripcion";

										if ($res_lista = mysqli_query($enlace, $q_lista)) {
											if (mysqli_num_rows($res_lista) > 0) {
												while ($row_lista = mysqli_fetch_array($res_lista)) {
										?>

													<option value="<?php echo $row_lista["Id"] ?>|<?php echo $row_lista["tiene_carreta"] ?>"><?php echo $row_lista["descripcion"] ?></option>

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

					<div class="row" style="padding: 5px;">
						<div class="col-md-12 col-sm-12 col-xs-12" hidden>
							<div class="d-flex" style="padding: 3px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; margin-left: 5px; text-align: right; width: 90px;">
									Placa 1:
								</h6>

								<div class="flex-fill" style="margin-left: 10px; width: 130px; max-width: 130px; min-width: 130px;">
									<select id="distribucion_unidad" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;" onchange="f_SetCapacidad();">

									</select>
								</div>

								<div id="div_placa2" style="display: none;">
									<div class="d-flex">
										<h6 style="font-size: 14px; margin-top: 8px; margin-left: 5px; text-align: right; width: 80px;">
											Placa 2:
										</h6>

										<div class="flex-fill" style="margin-left: 10px; width: 130px; max-width: 130px; min-width: 130px;">
											<select id="distribucion_unidad2" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;" onchange="f_SetCapacidad();">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_lista = "SELECT T.id_transporte,
																						 T.cplaca,
																						 T.nCapacidad
																				FROM transporte T
																						 INNER JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
																			 WHERE T.cEstado_Registro = 'A'
																			 	 AND TV.is_carreta = 1
																			ORDER BY T.cplaca";

												if ($res_lista = mysqli_query($enlace, $q_lista)) {
													if (mysqli_num_rows($res_lista) > 0) {
														while ($row_lista = mysqli_fetch_array($res_lista)) {
												?>

															<option value="<?php echo $row_lista["id_transporte"] ?>|<?php echo $row_lista["nCapacidad"] ?>"><?php echo $row_lista["cplaca"] ?></option>

												<?php
														}
													}
												}

												?>
											</select>
										</div>
									</div>
								</div>

								<div class="flex-fill">
									<div class="d-flex justify-content-end" style="margin-right: 20px;">
										<h6 style="font-size: 14px; margin-top: 8px; text-align: right; margin-left: 10px; width: 120px;">
											Capacidad (Tn):
										</h6>

										<input id="unidad_capacidad" type="text" class="form-control" style="margin-left: 10px; text-align: center; width: 100px; max-width: 100px; font-size: 14px; font-weight: bold;" disabled>
									</div>
								</div>
							</div>
						</div>

						<div class="d-flex justify-content-center" style="padding: 5px; margin-top: -15px; height: 30px;">
							<div id="wt_loadinglotesdistribuciones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Cargando datos...</label>
							</div>
						</div>

						<div class="col-md-12 col-sm-12 col-xs-12" style="height: 350px; margin-top: 10px;">
							<div class="d-flex" style="padding: 3px; margin-top: -5px;">
								<table class="table table-bordered table-hover">
									<thead>
										<!-- <tr style="font-size: 12px;">
											<th colspan="6" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
												Información Lotes
											</th>

											<th colspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; color: #816951; vertical-align: middle; width: 110px; min-width: 110px; border-top-right-radius: 15px;">
												Complemento
											</th>
										</tr> -->

										<tr style="font-size: 12px;">
											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 60px; min-width: 60px; max-width: 60px; border-top-left-radius: 15px;">
												Sel.<br>
												<input id="th_Chk_Distribucion" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.2);" onchange="f_SelectChkLotes_Distribucion();">
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 110px;">
												Lote
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
												Proveedor Minero
											</th>

											<!-- <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
												Modalidad Envío
											</th> -->

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
												TMH<br>(Por distribuir)
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 110px; min-width: 110px; max-width: 110px; border-top-right-radius: 15px;">
												TMH<br>Ditribuído
											</th>
										</tr>
									</thead>

									<tbody id="tbl_FiltroLotes_Distribucion">

									</tbody>
								</table>
							</div>
						</div>
					</div>

					<hr style="color: #6c757d;">

					<div class="d-flex" style="padding: 5px; margin-top: -10px; font-size: 14px;">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<label>
								Lotes seleccionados:
							</label>

							<label id="lbl_countlotes_Distribucion" style="font-weight: bold; margin-left: 5px;">0</label>
						</div>

						<div class="col-md-6 col-sm-6 col-xs-6" style="margin-left: -10px;">
							<div class="d-flex justify-content-end">
								<div class="d-flex">
									<label>
										Total TMH Distribuído:
									</label>

									<label id="lbl_totaltmh_Distribuido" style="font-weight: bold; margin-left: 5px;">0</label>
								</div>
							</div>
						</div>
					</div>
				</div>

				<input id="modo_grabardistribucion" type="hidden">
				<input id="id_distribucion" type="hidden">
				<input id="item_distribucion" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_grabardistribucion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabardistribucion_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabardistribucion_button" style="font-size: 14px;" onclick="f_ConfirmarDistribucion();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_SetColor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_SetColorLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div id="modal_SetColor_content" class="modal-content" style="margin-top: 15%;">
				<div class="modal-header" style="background-color: #f8da62;">
					<h1 class="modal-title fs-5">Asigne un color para: </h1>
					<h1 class="modal-title fs-5" id="modal_SetColorLabel" style="margin-left: 5px; font-weight: bold;"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="d-flex justify-content-center">
						<input id="colorSeleccionado" data-jscolor="{height: 150, width: 200}" value="#ffffff">
					</div>
				</div>

				<input id="hd_distribucionlote_id" type="hidden">
				<input id="hd_distribucionlote_item" type="hidden">

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="f_SetColor_Grabar();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_CierrePrograma" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_CierreProgramaLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div id="modal_CierrePrograma_content" class="modal-content" style="margin-top: 15%;">
				<div class="modal-header" style="background-color: #f8da62;">
					<h1 class="modal-title fs-5" id="modal_CierreProgramaLabel">Cierre de Programa </h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="d-flex justify-content-center">
						<label style="font-size: 14px; margin-top: 8px; width: 350px;">
							Fecha Estimada de Despacho:
						</label>

						<input id="cierre_fechadespacho" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
					</div>
				</div>

				<div class="modal-footer">
					<div id="wt_grabarcierre" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabarcierre_button" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarcierre_button" onclick="f_GrabarCierrePrograma();">Confirmar Cierre</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-dialog-scrollable" id="modal_configuracionvehicular" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_configuracionvehicularLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5">Pesos y Medidas para Unidad N°: </h1>
					<h1 class="modal-title fs-5" id="modal_configuracionvehicularLabel" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-10 col-sm-10 col-xs-12">
							<div class="d-flex" style="padding: 5px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 208px;">
									Configuración Vehicular:
								</h6>

								<div class="flex-fill" style="width: 67%; max-width: 67%; min-width: 67%;">
									<select id="configuracion_vehicular" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;" onchange="f_ConfiguracionVehicular_Selected();">
										
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="d-flex" style="padding: 3px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 210px;">
									Peso Bruto Máximo (Kg):
								</h6>

								<div class="flex-fill" style="width: 45%; max-width: 45%; min-width: 45%;">
									<input id="pesobruto_maximo" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="d-flex" style="padding: 3px; margin-top: -2px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 210px;">
									Peso Bruto Máximo (Kg):
								</h6>

								<div class="flex-fill">
									<div class="d-flex">
										<input id="pesobruto_maximo2" type="number" class="form-control" style="text-align: center; font-size: 14px; width: 39%;">

										<label style="font-size: 12px; margin-left: 5px; margin-top: 8px;">
											<i>Con Bonificación</i>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="d-flex" style="padding: 3px; margin-top: -2px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 210px;">
									Peso Bruto Total Transportado:
								</h6>

								<div class="flex-fill">
									<div class="d-flex">
										<input id="pesobruto_total" type="number" class="form-control" style="text-align: center; font-size: 14px; width: 39%;">


									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<input id="hd_idprogramacionunidad" type="hidden">
				<input id="hd_idprogramacionunidadmd5" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_configuracionvehicular" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_configuracionvehicular_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_configuracionvehicular_button" style="font-size: 14px;" onclick="f_PrintPesosMedidas_Informe();">Ver Informe</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-dialog-scrollable" id="modal_loteaum" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_loteaumLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_loteaumLabel"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-10 col-sm-10 col-xs-10">
							<div class="d-flex" style="padding: 5px; margin-top: -5px;">
								<h6 style="font-size: 14px; margin-top: 8px; min-width: 140px; text-align: center;">
									Peso Estimado (Tn):
								</h6>

								<input id="loteaum_peso" type="text" class="form-control" style="text-align: center; font-size: 14px;">
							</div>
						</div>
					</div>
				</div>

				<input id="loteaum_modograbar" type="hidden">
				<input id="loteaum_idprogramacion" type="hidden">
				<input id="loteaum_itemprogramacion" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_grabarprogramacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabarprogramacion_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarprogramacion_button" style="font-size: 14px;" onclick="f_ConfirmarLoteAum();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-dialog-scrollable" id="modal_configuracionrci" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_configuracionrciLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5">Datos RCI para Unidad N°: </h1>
					<h1 class="modal-title fs-5" id="modal_configuracionrciLabel" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row" style="padding: 3px;">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<h6 style="font-size: 14px; margin-top: 8px; min-width: 120px;">
								Fecha Salida:
							</h6>
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="flex-fill" style="width: 65%; max-width: 65%; min-width: 65%;">
								<input id="rci_fechasalida" type="date" class="form-control" style="text-align: center; font-size: 14px;">
							</div>
						</div>
					</div>

					<div class="row" style="padding: 3px;">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<h6 style="font-size: 14px; margin-top: 8px; min-width: 120px;">
								Hora Salida:
							</h6>
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="flex-fill" style="width: 65%; max-width: 65%; min-width: 65%;">
								<input id="rci_horasalida" type="time" class="form-control" style="text-align: center; font-size: 14px;">
							</div>
						</div>
					</div>

					<div class="row" style="padding: 3px;">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<h6 style="font-size: 14px; margin-top: 8px; min-width: 120px;">
								Info. Precintos:
							</h6>
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="flex-fill" style="width: 65%; max-width: 65%; min-width: 65%;">
								<input id="rci_precintos" type="text" class="form-control" style="text-align: center; font-size: 14px;">
							</div>
						</div>
					</div>

					<div class="row" style="padding: 3px;">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<h6 style="font-size: 14px; margin-top: 8px; min-width: 120px;">
								Teléfono:
							</h6>
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="flex-fill" style="width: 65%; max-width: 65%; min-width: 65%;">
								<input id="rci_telefono" type="text" class="form-control" style="text-align: center; font-size: 14px;">
							</div>
						</div>
					</div>
				</div>

				<input id="hdrci_idprogramacionunidad" type="hidden">
				<input id="hdrci_idprogramacionunidadmd5" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_rci" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_rci_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_rci_button" style="font-size: 14px;" onclick="f_PrintRCI_Informe();">Ver Informe</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Generar Guia -->
	<div class="modal fade modal-dialog-scrollable" id="modal_generarguia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_generarguiaLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" style="width: 90vw; margin: auto;">
			<div class="modal-content" style="width: 90vw; margin: auto;">
				<div class="modal-header">
					<h1 class="modal-title fs-5"></h1>
					<h1 class="modal-title fs-5" id="modal_generarguiaLabel" style="margin-left: 5px; color: #337ab7; font-weight: bold;">Generar Guía</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<!-- <iframe src="" frameborder="0"></iframe> -->
					<div class="guia-container">
						<div class="row" style="padding: 0px;">
							<div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="d-flex">
													<div class="d-flex flex-fill">
														<h5>Agrupaciones generadas automáticamente </h5>

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
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
										<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 200px; margin-bottom: 20px;">
											<table class="table table-bordered table-hover">
												<thead>
													<tr style="font-size: 12px;">
														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 58px;">
															N°
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
															Destino
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
															Modalidad Envío
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
															Código Despacho
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
															Fecha Estimada<br>Despacho
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
															Proveedor Minero<br>(1er Tramo)
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
															Placa
														</th>
													</tr>
												</thead>

												<tbody id="tbl_detalle">

												</tbody>
											</table>
										</div>
									</div>
								</div>

								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-8 col-sm-8 col-xs-12">
												<div class="d-flex">
													<h5 style="margin-top: 5px;">Lista de Lotes </h5>

													<div id="wt_listalotes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>

													<div id="wt_savingDistribucion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Grabando datos...</label>
													</div>
												</div>
											</div>

											<div class="col-md-4 col-sm-4 col-xs-12">
												<div class="d-flex justify-content-end">
													<button id="btn_AddDistribucion" type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AddGuia();">+ Generar Guía</button>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px;">
										<div class="d-flex" style="overflow-x: scroll; width: 100%;">
											<table class="table table-bordered table-hover">
												<thead>
													<tr style="font-size: 12px;">
														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 40px; border-top-left-radius: 15px;">
															Sel.<br>
															<input id="th_Chk_Guias" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.5);" onchange="f_SelectChk_Guias();">
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															Lote
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															N° Parte
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															Cód. Planta
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 50px;">
															Verif.
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															Placa
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															Placa 2
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
															Neto<br>(Tn)
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															Presentación
														</th>

														<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
															Fecha Guías
														</th>

														<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 170px;">
															Información Remitente
														</th>

														<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
															Información Transportista
														</th>
													</tr>

													<tr style="font-size: 12px;">
														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
															N° Guía
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															RUC
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
															Razón Social
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
															N° Guía
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
															RUC
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
															Razón Social
														</th>

														<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
															Coordinador
														</th>
													</tr>
												</thead>

												<tbody id="tbl_listalotes">

												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<input id="hdrci_idprogramacionunidad" type="hidden">
				<input id="hdrci_idprogramacionunidadmd5" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">


					<button type="button" class="btn btn-secondary wt_rci_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>

				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-dialog-scrollable" id="modal_adminguias" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminguiasLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="margin-left: -5%; width: 130%;">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_adminguiasLabel">Generar Guía</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Fecha Hora de Emisión:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="fecha_emision" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="">
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="hora_emision" type="time" class="form-control" style="text-align: center; font-size: 14px;" value="">
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Fecha Inicio Traslado:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="guia_fechas" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Guía Remitente:
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
							<input id="guia_remitenteserie" type="text" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Serie">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="guia_remitentenumero" type="text" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Guía">
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Guía Transportista:
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
							<input id="guia_transportistaserie" type="text" class="form-control guia_GRT" style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Serie">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="guia_transportistanumero" type="text" class="form-control guia_GRT" style="text-align: center; font-size: 14px; text-transform: uppercase;;" placeholder="N° Guía">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px; margin-top: 7px;">
							<div class="form-check">
								<input id="chk_SinGRT" class="form-check-input" type="checkbox" onchange="f_DisabledGRT();">
								<label class="form-check-label" for="chk_SinGRT">
									Sin GRT
								</label>
							</div>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Punto Partida:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<textarea id="guia_puntopartida" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase; font-size: 14px;" disabled></textarea>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Punto Destino:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<textarea id="guia_puntodestino" type="text" class="form-control col-md-12 col-xs-12" rows="3" style="text-transform: uppercase; font-size: 14px;" disabled></textarea>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Remitente:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<input id="guia_remitente" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Destinatario:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<input id="guia_destinatario" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Emp. Transporte:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<!-- <input id="guia_transportista" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled> -->

							<select id="guia_transportista" class="form-select" data-placeholder="Elija una opción...">
								<option selected value="">Elija una opción...</option>
								<option value="x" style="font-size: 6px;" disabled></option>

								<?php

								// Obtiene lista
								$q_transportistas = "SELECT Id,
																								CONCAT(documento, ' - ', razon_social) AS TRANSPORTISTA
																				   FROM tb_clientes
																				  WHERE cod_clientecondicion = 2
																						AND estado = 'A'
																				 ORDER BY razon_social";

								if ($res_transportistas = mysqli_query($enlace, $q_transportistas)) {
									if (mysqli_num_rows($res_transportistas) > 0) {
										while ($row_transportistas = mysqli_fetch_array($res_transportistas)) {
								?>

											<option value="<?php echo $row_transportistas["Id"]; ?>"><?php echo $row_transportistas["TRANSPORTISTA"]; ?></option>

											<option value="x" style="font-size: 6px;" disabled></option>

								<?php
										}
									}
								}

								?>

							</select>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Placa:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<!-- <input id="guia_placa" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled> -->

							<select id="guia_placa" class="form-select" data-placeholder="Elija una opción..." onchange="f_GetPlacaInfo(1);">
								<option selected value="">Elija una opción...</option>
								<option value="x" style="font-size: 6px;" disabled></option>

								<?php

								// Obtiene lista
								$q_unidades = "SELECT T.cplaca,
																					TV.descripcion
																	   FROM transporte T
																	   			INNER JOIN tbconfig_tipovehiculo TV ON T.id_tipovehiculo = TV.Id
																	  WHERE T.cEstado_Registro = 'A'
																	  	AND is_carreta = 0
																	 ORDER BY T.cplaca";

								if ($res_unidades = mysqli_query($enlace, $q_unidades)) {
									if (mysqli_num_rows($res_unidades) > 0) {
										while ($row_unidades = mysqli_fetch_array($res_unidades)) {
								?>

											<option value="<?php echo $row_unidades["cplaca"]; ?>"><?php echo $row_unidades["cplaca"].' ('.$row_unidades["descripcion"].')'; ?></option>

											<option value="x" style="font-size: 6px;" disabled></option>

								<?php
										}
									}
								}

								?>

							</select>
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12 info_placa2" style="padding: 5px; text-align: right; display: none;">
							Placa 2:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12 info_placa2" style="display: none;">
							<!-- <input id="guia_placa2" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled> -->

							<select id="guia_placa2" class="form-select" data-placeholder="Elija una opción..." onchange="f_GetPlacaInfo(2);">
								<option selected value="">Elija una opción...</option>
								<option value="x" style="font-size: 6px;" disabled></option>

								<?php

								// Obtiene lista
								$q_unidades = "SELECT cplaca
																	   FROM transporte T
																	  WHERE cEstado_Registro = 'A'
																	  	AND id_tipovehiculo = 7
																	 ORDER BY cplaca";

								if ($res_unidades = mysqli_query($enlace, $q_unidades)) {
									if (mysqli_num_rows($res_unidades) > 0) {
										while ($row_unidades = mysqli_fetch_array($res_unidades)) {
								?>

											<option value="<?php echo $row_unidades["cplaca"]; ?>"><?php echo $row_unidades["cplaca"] ?></option>

											<option value="x" style="font-size: 6px;" disabled></option>

								<?php
										}
									}
								}

								?>

							</select>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							N° Constancia MTC:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="guia_constanciamtc" type="text" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase;">
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12 info_placa2" style="padding: 5px; text-align: right; display: none;">
							N° Cons. MTC 2:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12 info_placa2" style="display: none;">
							<input id="guia_constanciamtc2" type="text" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase;">
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Marca Unidad:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<select id="guia_marcaunidad" class="form-select" data-placeholder="Elija una opción...">
								<option selected value="">Elija una opción...</option>
								<option value="x" style="font-size: 6px;" disabled></option>

								<?php

								// Obtiene lista
								$q_marcas = "SELECT Id,
																				descripcion
																	 FROM tbconfig_unidadesmarca
																  WHERE estado = 'A'
																 ORDER BY descripcion";

								if ($res_marcas = mysqli_query($enlace, $q_marcas)) {
									if (mysqli_num_rows($res_marcas) > 0) {
										while ($row_marcas = mysqli_fetch_array($res_marcas)) {
								?>

											<option value="<?php echo $row_marcas["Id"]; ?>"><?php echo $row_marcas["descripcion"]; ?></option>

											<option value="x" style="font-size: 6px;" disabled></option>

								<?php
										}
									}
								}

								?>

							</select>
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12 info_placa2" style="padding: 5px; text-align: right; display: none;">
							Marca Unidad 2:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12 info_placa2" style="display: none;">
							<select id="guia_marcaunidad2" class="form-select" data-placeholder="Elija una opción...">
								<option selected value="">Elija una opción...</option>
								<option value="x" style="font-size: 6px;" disabled></option>

								<?php

								// Obtiene lista
								$q_marcas = "SELECT Id,
																				descripcion
																	 FROM tbconfig_unidadesmarca
																  WHERE estado = 'A'
																 ORDER BY descripcion";

								if ($res_marcas = mysqli_query($enlace, $q_marcas)) {
									if (mysqli_num_rows($res_marcas) > 0) {
										while ($row_marcas = mysqli_fetch_array($res_marcas)) {
								?>

											<option value="<?php echo $row_marcas["Id"]; ?>"><?php echo $row_marcas["descripcion"]; ?></option>

											<option value="x" style="font-size: 6px;" disabled></option>

								<?php
										}
									}
								}

								?>

							</select>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Conductor:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<select id="guia_conductor" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;">
								<option selected value="">Elija una opción...</option>

								<?php

								$q_lista = "SELECT Id,
																		 dni_licencia,
																		 UPPER(nombres) AS nombres
																FROM tbconfig_conductores
															 WHERE estado = 'A'
															ORDER BY nombres";

								if ($res_lista = mysqli_query($enlace, $q_lista)) {
									if (mysqli_num_rows($res_lista) > 0) {
										while ($row_lista = mysqli_fetch_array($res_lista)) {
								?>

											<option value="<?php echo $row_lista["Id"] ?>"><?php echo $row_lista["dni_licencia"].' - '.$row_lista["nombres"] ?></option>

								<?php
										}
									}
								}

								?>
							</select>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Motivo Traslado:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<input id="guia_motivotraslado" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled>
						</div>
					</div>

					<div class="d-flex justify-content-center" style="padding: 5px; height: 200px; overflow-y: scroll;">
						<table class="table table-bordered table-hover">
							<thead>
								<tr style="font-size: 12px;">
									<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
										Información Lotes
									</th>
								</tr>

								<tr style="font-size: 12px;">
									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 40px;">
										N°
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 90px;">
										Lote
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
										N° Parte
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
										Descripción del Bien
									</th>

									<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
										Presentación
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 120px;">
										Peso Distrbuído<br>2do Tramo
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 120px;">
										Peso Ajustado<br>Guía
									</th>
								</tr>
							</thead>

							<tbody id="tbl_guialistalotes">

							</tbody>
						</table>
					</div>

					<hr style="color: #6c757d;">

					<div class="d-flex" style="padding: 5px; margin-top: -10px; font-size: 14px;">
						<label style="width: 150px; margin-top: 7px;">
							Capacidad Unidad (Tn):
						</label>

						<input id="guia_capacidadunidad" type="number" class="form-control" style="text-align: center; font-size: 14px; width: 80px;" onkeyup="f_SetAjusteCapacidad();" onchange="f_SetAjusteCapacidad();">

						<div style="border-left: solid; border-left-width: 1px; border-left-color: #BFBFBF; margin-left: 10px; margin-right: 10px;"></div>

						<div class="form-check" style="padding-top: 7px; width: 160px;">
							<input id="chk_AjusteCapacidad" class="form-check-input" type="checkbox" onchange="f_ShowAjusteCapacidad();">
							<label class="form-check-label" for="chk_AjusteCapacidad">
								Ajuste Capacidad (Tn):
							</label>
						</div>

						<input id="guia_ajustecapacidad" type="number" class="form-control" style="margin-left: 5px; text-align: center; font-size: 14px; width: 80px; display: none;" onkeyup="f_SetAjusteCapacidad();" onchange="f_SetAjusteCapacidad();">

						<div id="div_ajustecapacidad" style="margin-left: 10px; display: none;">
							<div class="d-flex">
								<label style="width: 140px; margin-top: 7px; font-weight: bold;">
									Capacidad Final (Tn):
								</label>

								<input id="guia_ajustecapacidad_total" type="text" class="form-control" style="margin-left: 5px; text-align: center; font-size: 14px; width: 80px;" disabled>
							</div>
						</div>
					</div>
				</div>

				<input id="id_programacion" type="hidden">
				<input id="item_programacion" type="hidden">
				<input id="modograbar_guia" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_confirmarguia" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_confirmarguia_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_confirmarguia_button" style="font-size: 14px;" onclick="f_ConfirmarGuia();">Emitir Guías</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-dialog-scrollable" id="modal_verificacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_verificacionLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_verificacionLabel">Verificación Documentaria</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="d-flex" style="padding: 5px; margin-top: -10px;">
						<label style="padding: 5px; min-width: 130px;">
							Guía Remitente:
						</label>

						<input id="verif_numguiar" type="text" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase; min-width: 130px; font-weight: bold;" disabled>

						<label style="padding: 5px; min-width: 60px; margin-left: 5px;">
							Lote:
						</label>

						<input id="verif_codlote" type="text" class="form-control guia_GRT" style="text-align: center; font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>

						<label style="padding: 5px; min-width: 90px; margin-left: 5px;">
							N° Ticket:
						</label>

						<input id="verif_numticket" type="text" class="form-control guia_GRT" style="text-align: center; font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>

						<label style="padding: 5px; min-width: 120px; margin-left: 5px;">
							Fecha Balanza:
						</label>

						<input id="verif_fechabalanza" type="text" class="form-control guia_GRT" style="text-align: center; font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
					</div>

					<div class="d-flex justify-content-center" style="padding: 5px;">
						<table class="table table-bordered table-hover">
							<thead>
								<tr style="font-size: 12px;">
									<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
										Información Lotes
									</th>
								</tr>

								<tr style="font-size: 12px;">
									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 40px;">
										N°
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 300px;">
										Documento
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 80px;">
										Link Referencia
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 350px;">
										Acción
									</th>
								</tr>
							</thead>

							<tbody id="tbl_verificaciondocumentos">

							</tbody>
						</table>
					</div>
				</div>

				<input id="modo_grabarprogramacion" type="hidden">
				<input id="id_programacion" type="hidden">
				<input id="item_programacion" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_grabarprogramacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabarprogramacion_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarprogramacion_button" style="font-size: 14px;" onclick="f_DownloadVerificacion();">Descargar Archivos</button>
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
		function f_Init() {
			// Genera menús
			f_GetMenuPrincipal();

			// Titulo de Pantalla
			$("#nv_titulo").html('| Programación de Despachos');

			// Cargando listas generales
				f_GetConfiguracionVehicular();

			// Carga el detalle de información
			f_LoadPlantas();
		}
	</script>

	<!-- Seteando objetos Select2 -->
	<script type="text/javascript">
		$('#filtro_proveedorminero, #filtro_modalidadenvio').select2({
			theme: "bootstrap-5",
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: true,
			dropdownParent: $('#modal_adminprogramaciones')
		}).on('select2:open', function() {
			$('body').css('zoom', '100%'); 
		}).on('select2:close', function() {
	    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
		});

		$('#tipo_unidad, #distribucion_unidad, #distribucion_unidad2').select2({
			theme: "bootstrap-5",
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: true,
			dropdownParent: $('#modal_admindistribuciones')
		}).on('select2:open', function() {
			$('body').css('zoom', '100%'); 
		}).on('select2:close', function() {
	    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
		});

		$('#filtro_lote, #filtro_codigodespacho').select2({
			theme: "bootstrap-5",
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : '100%',
			placeholder: $(this).data('placeholder'),
			allowClear: true,
			minimumResultsForSearch: -1
		}).on('select2:open', function() {
			$('body').css('zoom', '100%'); 
		}).on('select2:close', function() {
	    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
		});

		$('#guia_marcaunidad, #guia_marcaunidad2, #guia_conductor, #guia_transportista, #guia_placa, #guia_placa2').select2({
			theme: "bootstrap-5",
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: true,
			dropdownParent: $('#modal_adminguias')
		}).on('select2:open', function() {
			$('body').css('zoom', '100%'); 
		}).on('select2:close', function() {
	    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
		});

		$('.select2-selection__rendered').css('font-size', '14px');

		$('.select2-search__field').css('font-size', '14px');

		$('#filtro_proveedorminero, #filtro_modalidadenvio, #tipo_unidad, #distribucion_unidad, #distribucion_unidad2').next('.select2-container').find('.select2-selection__rendered').css('font-size', '14px');
	</script>

	<!-- Funciones Principales -->
	<script type="text/javascript">
		function f_LoadPlantas() {
			var _html = '';
			var d = 1;

			// Obtiene filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_estadocierre = $("#filtro_estadocierre").val();
			var filtro_codigodespacho = $("#filtro_codigodespacho").val();
			var filtro_lote = $("#filtro_lote").val();

			// Validando datos

			// Cargando Lista de Racks
			$("#tbl_plantas").html('');

			f_LoadingPlantas(1);

			$.post("apis/backend.php", {
					accion: "get_DespachosProgramacion_ListaPlantas",
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
					estado_cierre: filtro_estadocierre,
					filtro_codigodespacho: filtro_codigodespacho,
					filtro_lote: filtro_lote
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_plantas").html(data.html);

						itemplanta_Selected = 1;
						idplanta_Selected = data.id_planta;

						f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);
					}

					f_LoadingPlantas(0);

				}, "json");
		}

		function f_LoadItemPlanta(_item, _id_planta, _load_programacion, _item_programacion, _id_programacion) {
			var _html = '';

			// Pinta selección
			f_ColorSelected_Planta(_item);

			// Seteando Th de Código de Planta y Comercialización en la Distribución, solo para Colibrí
			$("#th_distribucion_codplanta").hide();
			$("#th_codigocomercializacion").hide();
			$("#th_codigoplanta").hide();
			// $(".th_codigoscmh").hide();

			$("#th_distribucion_infolote").attr('colspan', 4);

			// if (_id_planta == 3) {
			// 	$("#th_distribucion_codplanta").show();
			// 	$("#th_codigocomercializacion").show();
			// 	$("#th_codigoplanta").show();

			// 	$("#th_distribucion_infolote").attr('colspan', 6);
			// }

			// if (_id_planta == 1) {
			// 	$(".th_codigoscmh").show();
			// }

			// Obtiene filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_estadocierre = $("#filtro_estadocierre").val();
			var filtro_codigodespacho = $("#filtro_codigodespacho").val();
			var filtro_lote = $("#filtro_lote").val();

			// Cargando datos
			f_LoadingProgramacion(1);

			$("#tbl_programacion").html(_html);
			$("#tbl_distribucion").html(_html);

			$.post("apis/backend.php", {
					accion: "get_DespachosProgramacion_ListaProgramaciones",
					id_planta: _id_planta,
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
					estado_cierre: filtro_estadocierre,
					filtro_codigodespacho: filtro_codigodespacho,
					filtro_lote: filtro_lote
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_programacion").html(data.html);

						itemprog_Selected = 1;
						idprog_Selected = data.id_programacion;

						if (_load_programacion == 1) {
							f_LoadItemProgramacion(_item_programacion, _id_programacion);
						} else {
							f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);
						}
					}

					f_LoadingProgramacion(0);

				}, "json");

			itemplanta_Selected = _item;
			idplanta_Selected = _id_planta;
		}

		function f_generarguia(_is_distribucionunidad, _cod_despacho, _cod_destino, _placa) {
			f_OpenModal('modal_generarguia');
			f_LoadResultados(_is_distribucionunidad, _cod_despacho, _cod_destino, _placa);
		}

		function f_LoadItemProgramacion(_item, _id_prog, _is_cierre, _item_cierre) {
			var _html = '';

			// Pinta selección
			f_ColorSelected_Programacion(_item);

			// Cargando datos
			f_LoadingDistribucion(1);

			var _html = '<tr>';
			_html += '	<td colspan="20" style="font-size: 13px; background-color: #FF5F5D; color: #ffffff; text-align: center;">';
			_html += '		<label id="lbl_SinDistribuciones">';
			_html += '			<i>Sin Distribuciones</i>';
			_html += '		</label>';
			_html += '	</td>';
			_html += '</tr>';

			$("#tbl_distribucion").html(_html);

			$.post("apis/backend.php", {
					accion: "get_DespachosProgramacion_ListaDistribuciones",
					id_planta: idplanta_Selected,
					id_programacion: _id_prog,
					item_programacion: _item
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_distribucion").html(data.html);
					}

					// Setea los Selects
					$('.select_edit').select2({
						theme: "bootstrap-5",
						width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
						placeholder: $(this).data('placeholder'),
						allowClear: true
					}).on('select2:open', function() {
						$('body').css('zoom', '100%'); 
					}).on('select2:close', function() {
				    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
					});

					$('.select_edit').next('.select2-container').find('.select2-selection__rendered').css('font-size', '14px');

					f_LoadingDistribucion(0);
					f_VerifyDistrbucion();

					// Valida si fue llamado desde el botón de cierre
					if (_is_cierre == 1) {
						f_CierrePrograma(_id_prog, _item_cierre);
					}

				}, "json");

			itemprog_Selected = _item;
			idprog_Selected = _id_prog;
		}

		function f_ColorSelected_Planta(_item) {
			var i = 1;

			// Recorre los Tr de la tabla y los limpia
			$("#tbl_plantas tr").each(function() {
				$("#tr_planta_" + i).css('background-color', '');

				i += 1;
			});

			// Seteando item seleccionado
			$("#tr_planta_" + _item).css('background-color', '#FFF587');

			$("#lbl_tituloplanta").html($("#td_planta_" + _item).html().trim());
		}

		function f_ColorSelected_Programacion(_item) {
			var i = 1;

			// Recorre los Tr de la tabla y los limpia
			$("#tbl_programacion tr").each(function() {
				$("#td_prog_" + i).css('background-color', '');

				i += 1;
			});

			// Seteando item seleccionado
			$("#td_prog_" + _item).css('background-color', '#FFF587');
		}

		function f_AddProgramacion(_modo, _item, _id_programacion) {
			// Registrando el modo
			$("#modo_grabarprogramacion").val(_modo);
			$("#id_programacion").val(_id_programacion);
			$("#item_programacion").val(_item);

			// Colocando Títulos
			if (_modo == 'N') {
				$("#modal_adminprogramacionesLabel").html('Nueva Programación');
			} else {
				$("#modal_adminprogramacionesLabel").html('Agregar Lote');
			}

			// Seteando filtros
			$("#filtrolotes_lote").val('');
			$("#filtro_proveedorminero").val('');
			$("#filtro_proveedorminero").trigger('change');
			$("#filtro_modalidadenvio").val('');
			$("#filtro_modalidadenvio").trigger('change');

			$("#th_Chk").prop('checked', false);

			// Cargando datos
			f_LoadFiltroModalidadEnvio();
			f_LoadFiltroLotes();

			// Abre modal
			f_OpenModal('modal_adminprogramaciones');
		};

		function f_LoadFiltroModalidadEnvio() {
			$("#filtro_modalidadenvio").html('');

			$.post("apis/backend.php", {
					accion: "get_DespachosProgramacion_ListaModalidadEnvio",
					id_planta: idplanta_Selected
				},
				function(data) {
					if (data.estado == 1) {
						$("#filtro_modalidadenvio").html(data.html);
					}

				}, "json");
		}

		function f_LoadFiltroLotes() {
			var _modo = $("#modo_grabarprogramacion").val();

			var cod_lote = $("#filtrolotes_lote").val();
			var cod_proveedorminero = $("#filtro_proveedorminero").val();
			var cod_modalidadenvio = $("#filtro_modalidadenvio").val();

			// Cargando datos
			f_LoadingLotes(1);

			$("#tbl_FiltroLotes").html('');

			$.post("apis/backend.php", {
					accion: "get_LotesProgramadosParaDespacho",
					id_planta: idplanta_Selected,
					cod_lote: cod_lote,
					cod_proveedorminero: cod_proveedorminero,
					cod_modalidadenvio: cod_modalidadenvio
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_FiltroLotes").html(data.html);
					}

					f_LoadingLotes(0);

				}, "json");
		}

		function f_AddDistribucion(_modo, _item, _id_distribucion, _id_unidad, _placa, _id_tipounidad) {
			// Registrando el modo
			$("#modo_grabardistribucion").val(_modo);
			$("#id_distribucion").val(_id_distribucion);
			$("#item_distribucion").val(_item);

			// Colocando Títulos
			if (_modo == 'N') {
				$("#modal_admindistribucionesLabel").html('Nueva Distribución');
			} else {
				$("#modal_admindistribucionesLabel").html('<div class="d-flex">Agregar Lote para: <h5 style="margin-top: 3px; margin-left: 5px; color: #337ab7;">' + _placa + '</h5></div>');
			}

			// Seteando Objetos
			$("#tipo_unidad").prop('disabled', false);
			$("#distribucion_unidad").prop('disabled', false);

			if (_modo == 'N') {
				$("#th_Chk_Distribucion").prop('checked', false);

				$("#tipo_unidad").val('');
				$("#tipo_unidad").trigger('change');

				$("#distribucion_unidad").val('');
				$("#distribucion_unidad").trigger('change');

				$("#unidad_capacidad").val('');
				$("#lbl_countlotes_Distribucion").html('0');
				$("#lbl_totaltmh_Distribuido").html('0');
			} else {
				$("#tipo_unidad").val(_id_tipounidad);
				$("#tipo_unidad").trigger('change');

				$("#tipo_unidad").prop('disabled', true);
				$("#distribucion_unidad").prop('disabled', true);

				// Validando la Unidad
				_id_unidad_x = _id_unidad.split('|')[0];

				if (_id_unidad_x.trim().length > 0) {
					$("#distribucion_unidad").val(_id_unidad);
				} else {
					$("#distribucion_unidad").val('');
				}

				$("#distribucion_unidad").trigger('change');

				f_SetCapacidad();
			}

			// Cargando datos
			f_LoadFiltroLotes_Distribucion();

			// Abre modal
			f_OpenModal('modal_admindistribuciones');
		}

		function f_LoadFiltroLotes_Distribucion() {
			// Cargando datos
			f_LoadingLotes_Distribucion(1);

			$("#tbl_FiltroLotes_Distribucion").html('');

			$.post("apis/backend.php", {
					accion: "get_LotesProgramadosParaDespacho_Distribucion",
					id_programacion: idprog_Selected,
					id_planta: idplanta_Selected
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_FiltroLotes_Distribucion").html(data.html);
					}

					f_LoadingLotes_Distribucion(0);

				}, "json");
		}

		function f_SetColor(_item, _id_registro, _lote, _color) {
			$("#hd_distribucionlote_id").val(_id_registro);
			$("#hd_distribucionlote_item").val(_item);

			// Setea título
			$("#modal_SetColorLabel").html(_lote);

			// Setea Color
			$("#colorSeleccionado").val('');

			f_OpenModal('modal_SetColor');
		}

		function f_VerifyDistrbucion() {
			// Recorre la Grilla de Programaciones y busca lote por lote en la grilla de Distrbuciones
			var d = 1;
			var _object = '';
			var cod_lote = '';

			while (d < 10000) {
				// _object = $("#td_programacionlote_" + d);
				_object = $("#td_programacioncodigogel_" + d);

				if (_object.html() == undefined) {
					d = 99999;
				} else {
					cod_lote = _object.html().trim();

					// // Limpiando el Total Distribuído de cada lote
					// 	$("#td_pesodistribuido_" + d).css('background-color', '');

					// 	$("#td_pesodistribuido_" + d).html('');

					// Recorre la grilla de Distribuciones
					var is_inicio = 1;
					var l = 1;
					var _object2 = '';
					var cod_lote2 = '';
					var total_distribuido = 0;

					while (l < 10000) {
						_object2 = $("#td_distribucionlote_" + l);

						if (_object2.html() == undefined) {
							l = 99999;
						} else {
							cod_lote2 = _object2.html().trim();

							if (cod_lote == cod_lote2) {
								if (is_inicio == 1) {
									// Limpiando el Total Distribuído de cada lote
									$("#td_pesodistribuido_" + d).css('background-color', '');

									$("#td_pesodistribuido_" + d).html('');

									is_inicio = 0;
								}

								total_distribuido += (($("#peso_distribuido_" + l).val().length == 0) ? 0 : parseFloat($("#peso_distribuido_" + l).val()));
							}
						}

						l++;
					}

					// Valida la distribución
					if (total_distribuido > 0) {
						var bg_color = '';

						if (parseFloat($("#td_lotetmh_" + d).html()).toFixed(2) == parseFloat(total_distribuido).toFixed(2)) {
							bg_color = '#B6F279';
						}

						if (parseFloat($("#td_lotetmh_" + d).html()).toFixed(2) < parseFloat(total_distribuido).toFixed(2)) {
							bg_color = '#FF5F5D';
						}

						// Verificando si es Lote AUM
						// if ($("#td_programacionloteaum_" + d).val() == 1){
						// 	$("#td_lotetmh_" + d).html(f_RedondearDecimales(total_distribuido, 3));

						// 	bg_color = '#B6F279';
						// }

						$("#td_pesodistribuido_" + d).css('background-color', bg_color);

						$("#td_pesodistribuido_" + d).html(f_RedondearDecimales(total_distribuido, 3));
					}
				}

				d++;
			}
		}

		function f_GetTotalDistrbucion(_item_unidad) {
			// Obtener la suma de los valores de los inputs con la clase 'td_tmhdistribuido'
			var suma = 0;

			$(".td_tmhdistribuido_" + _item_unidad).each(function() {
				// Convertir el valor del input a un número y sumarlo
				suma += parseFloat($(this).val()) || 0;
			});

			// Valida si la unidad tiene Capacidad
			if ($("#td_unidad_capacidad_" + _item_unidad).val().trim().length == 0) {
				$("#td_tmhdistribuido_pendiente_" + _item_unidad).html(f_RedondearDecimales(0 - suma, 3));

				$("#td_tmhdistribuido_pendiente_" + _item_unidad).css('background-color', '#FF5F5D');
			} else {
				// Seteando el TMH Distribuído acumulado
				$(".td_distribucionlote_totaltmh_" + _item_unidad).html(f_RedondearDecimales(suma, 3));

				// Seteando el Saldo de TMH por Distribuir
				var bg_color = '';

				if (parseFloat($("#td_unidad_capacidad_" + _item_unidad).val()).toFixed(3) - parseFloat(suma).toFixed(3) == 0) {
					bg_color = '#B6F279';
				}

				if (parseFloat($("#td_unidad_capacidad_" + _item_unidad).val()) < parseFloat(suma)) {
					bg_color = '#FF5F5D';
				}

				$("#td_tmhdistribuido_pendiente_" + _item_unidad).css('background-color', bg_color);

				$("#td_tmhdistribuido_pendiente_" + _item_unidad).html(f_RedondearDecimales(parseFloat($("#td_unidad_capacidad_" + _item_unidad).val()) - suma, 3));
			}
		}

		function f_CierrePrograma(_id_programacion, _item) {
			// Validando datos
			// 1. Validando que tenga al menos una distribución
			if ($("#lbl_SinDistribuciones").html() != undefined) {
				alert("Debe registrar al menos una Distribución.");

				return;
			}

			// 2. Verificando si hay unidades sin: Placa, Capacidad, Responsable de Despacho
			var d = 1;
			var _object1 = ''; // Para Placa
			var _object2 = ''; // Para Capacidad
			var _object3 = ''; // Para Responsable de Despacho

			var tiene_placa = 1;
			var arr_unidades = '';
			var arr_capacidad = '';
			var arr_responsable = '';

			$("#tbl_distribucion tr").each(function() {
				_object1 = $("#idunidad_" + d);
				_object2 = $("#td_unidad_capacidad_" + d);
				// _object3 = $("#responsableunidad_" + d);

				if (_object1.html() != undefined) {
					// Arma el Array de Placas
					if (_object1.val() == 0) {
						arr_unidades += 'Unidad ' + d + '|';
					}

					// Arma el Array de Capacidad
					if (_object2.val() <= 0) {
						arr_capacidad += 'Capacidad de Unidad ' + d + '|';
					}

					// // Arma el Array de Responsable de Despacho
					// 	if (_object3.val() <= 0){
					// 		arr_responsable += 'Responsable de Unidad ' + d + '|';
					// 	}
				}

				d++;
			});

			// Armando mensaje para Validación de Placas
			if (arr_unidades.length > 0) {
				var m = 0;
				var _msg = "Se han encontrado Unidades con Placas sin asignar:\n\n";
				var arr_unidades = arr_unidades.substring(0, arr_unidades.length - 1).split('|');

				while (m < arr_unidades.length) {
					_msg += '   - ' + arr_unidades[m] + "\n";

					m++;
				}

				_msg += "\n¿Está seguro de continuar?";

				if (!confirm(_msg)) {
					return;
				}
			}

			// Armando mensaje para Validación de Capacidad
			if (arr_capacidad.length > 0) {
				var m = 0;
				var _msg = "Se han encontrado Unidades Sin Capacidad o Capacidad Incorrecta:\n\n";
				var arr_capacidad = arr_capacidad.substring(0, arr_capacidad.length - 1).split('|');

				while (m < arr_capacidad.length) {
					_msg += '   - ' + arr_capacidad[m] + "\n";

					m++;
				}

				_msg += "\nNo podrá continuar.";

				alert(_msg);

				return;
			}

			// // Armando mensaje para Validación de Responsable de Despacho
			//   if (arr_responsable.length > 0){
			//   	var m = 0;
			//   	var _msg = "Se han encontrado Unidades sin Responsable de Despacho asignado:\n\n";
			//   	var arr_responsable = arr_responsable.substring(0, arr_responsable.length - 1).split('|');

			//   	while (m < arr_responsable.length){
			//   		_msg += '   - ' + arr_responsable[m] + "\n";

			//   		m ++;
			//   	}

			//   	_msg += "\nNo podrá continuar.";

			//   	alert(_msg);

			//   	return;
			//   }

			// 3. Verificando si hay Lotes distribuídos sin: TMH, Presentación, Hora de Ingreso a Planta, Responsable Despacho
			var d = 1;
			var info_lote = '';
			var _object1 = ''; // Para TMH Distribuído
			var _object2 = ''; // Para Presentación
			var _object3 = ''; // Para Hora de Ingreso a Planta
			var _object4 = ''; // Responsable Despacho

			var arr_tmhdistribuido = '';
			var arr_presentacion = '';
			var arr_ingresoplanta = '';
			var arr_responsabledespacho = '';

			while (d < 10000) {
				_object1 = $("#peso_distribuido_" + d);
				_object2 = $("#tipo_carga_" + d);
				_object3 = $("#ingreso_planta_" + d);
				_object4 = $("#responsabledespacho_" + d);

				if (_object1.html() == undefined) {
					d = 99999;
				} else {
					// Obtiene información del Lote
					info_lote = $("#td_distribucionlote_" + d).html().trim();
					info_lote += (($("#td_distribucionlote_parte_" + d).html().trim().length == 0) ? '' : ' - ' + $("#td_distribucionlote_parte_" + d).html().trim());

					// Arma el Array de TMH Distribuído
					if (_object1.val() <= 0) {
						arr_tmhdistribuido += info_lote + '|';
					}

					// Arma el Array de Presentación
					if (_object2.val() == 0) {
						arr_presentacion += info_lote + '|';
					}

					// Arma el Array de Hora de Ingreso a Planta
					if (_object3.val() == 0) {
						arr_ingresoplanta += info_lote + '|';
					}

					// Arma el Array de Hora de Ingreso a Planta
					if (_object4.val() == 0) {
						arr_responsabledespacho += info_lote + '|';
					}
				}

				d++;
			}

			// Armando mensaje para Validación de TMH Distribuído
			if (arr_tmhdistribuido.length > 0) {
				var m = 0;
				var _msg = "Se han encontrado Lotes con TMH sin distribuir o TMH incorrecto:\n\n";
				var arr_tmhdistribuido = arr_tmhdistribuido.substring(0, arr_tmhdistribuido.length - 1).split('|');

				while (m < arr_tmhdistribuido.length) {
					_msg += '   - ' + arr_tmhdistribuido[m] + "\n";

					m++;
				}

				_msg += "\nNo podrá continuar.";

				alert(_msg);

				return;
			}

			// Armando mensaje para Validación de TMH Distribuído
			if (arr_presentacion.length > 0) {
				var m = 0;
				var _msg = "Se han encontrado Lotes con Presentación sin asignar:\n\n";
				var arr_presentacion = arr_presentacion.substring(0, arr_presentacion.length - 1).split('|');

				while (m < arr_presentacion.length) {
					_msg += '   - ' + arr_presentacion[m] + "\n";

					m++;
				}

				_msg += "\nNo podrá continuar.";

				alert(_msg);

				return;
			}

			// Armando mensaje para Validación de Hora de Ingreso a Planta
			if (arr_ingresoplanta.length > 0) {
				var m = 0;
				var _msg = "Se han encontrado Lotes con Hora de Ingreso a Planta sin asignar:\n\n";
				var arr_ingresoplanta = arr_ingresoplanta.substring(0, arr_ingresoplanta.length - 1).split('|');

				while (m < arr_ingresoplanta.length) {
					_msg += '   - ' + arr_ingresoplanta[m] + "\n";

					m++;
				}

				_msg += "\nNo podrá continuar.";

				alert(_msg);

				return;
			}

			// Armando mensaje para Validación de Hora de Ingreso a Planta
			if (arr_responsabledespacho.length > 0) {
				var m = 0;
				var _msg = "Se han encontrado Lotes con Responsable de Despacho sin asignar:\n\n";
				var arr_responsabledespacho = arr_responsabledespacho.substring(0, arr_responsabledespacho.length - 1).split('|');

				while (m < arr_responsabledespacho.length) {
					_msg += '   - ' + arr_responsabledespacho[m] + "\n";

					m++;
				}

				_msg += "\nNo podrá continuar.";

				alert(_msg);

				return;
			}

			// 4. Verificando que los TMH Distribuídos correspondan exactamente al TMH del Primer Tramo
			// Setea Clases
			var class_1 = 'tmh_primertramo_' + _item;
			var class_2 = 'tmh_distribuidoprograma_' + _item;
			var class_3 = 'lote_programa_' + _item;

			// Setea variables de TMH
			var arr_primertramo = '';
			var arr_distribuido = '';
			var arr_lotes = '';

			// Recorre los td con la Clase 1
			$('td[class^="' + class_1 + '"]').each(function() {
				arr_primertramo += $(this).html().trim() + '|';
			});

			// Recorre los td con la Clase 2
			$('td[class^="' + class_2 + '"]').each(function() {
				arr_distribuido += $(this).html().trim() + '|';
			});

			// Recorre los td con la Clase 3
			var x = 1;

			$('td[class^="' + class_3 + '"]').each(function() {
				// arr_lotes += $(this).html().trim() + '|';

				arr_lotes += $("#td_programacionlote_" + x).html().trim() + '|';

				x++;
			});

			// Recorre los Array y compara las Distribuciones
			var d = 0;
			var tmh_primertramo = 0;
			var tmh_distribuido = 0;
			var lote_programa = '';

			arr_primertramo = arr_primertramo.substring(0, arr_primertramo.length - 1);
			arr_distribuido = arr_distribuido.substring(0, arr_distribuido.length - 1);
			arr_lotes = arr_lotes.substring(0, arr_lotes.length - 1);

			arr_primertramo = arr_primertramo.split('|');
			arr_distribuido = arr_distribuido.split('|');
			arr_lotes = arr_lotes.split('|');

			while (d < arr_primertramo.length) {
				tmh_primertramo = arr_primertramo[d];
				tmh_distribuido = arr_distribuido[d];
				lote_programa = arr_lotes[d];

				if (parseFloat(tmh_primertramo) > parseFloat(tmh_distribuido)) {
					alert("El TMH Distribuído Total del Lote: " + lote_programa + ", no puede ser menor al TMH Primer Tramo.");

					d = 99999;

					return;
				}

				if (parseFloat(tmh_primertramo) < parseFloat(tmh_distribuido)) {
					alert("El TMH Distribuído Total del Lote: " + lote_programa + ", no puede ser mayor al TMH Primer Tramo.");

					d = 99999;

					return;
				}

				d++;
			}

			// Abrir pantalla de confirmación
			$("#cierre_fechadespacho").val('<?php echo $g_date; ?>');

			f_OpenModal('modal_CierrePrograma');
		}

		function f_LoadUnidades() {
			var tipo_vehiculo = $("#tipo_unidad").val();

			if (tipo_vehiculo == '') {
				$("#distribucion_unidad").html('');
				$("#distribucion_unidad2").val('');

				$("#distribucion_unidad2").trigger('change');
				$("#div_placa2").hide();
			} else {
				$("#distribucion_unidad").html('');

				$.post("apis/backend.php", {
						accion: "get_ListaUnidadesxTipo",
						tipo_vehiculo: tipo_vehiculo
					},
					function(data) {
						if (data.estado == 1) {
							$("#distribucion_unidad").html(data.html);
						}

						f_SetCapacidad();

					}, "json");
			}
		}

		function f_PrintCargos(_iddistribucionunidad_md5, _arr_modalidadenvio = null) {
			var url = '';

			if (_arr_modalidadenvio != null){
				var m = 0;

				_arr_modalidadenvio = _arr_modalidadenvio.split('|');

				while (m < _arr_modalidadenvio.length){
					url = 'print_cargosguias.php?x=' + _iddistribucionunidad_md5 + "&m=" + _arr_modalidadenvio[m];

					window.open(url, '_blank');

					m ++;
				}
			}
			else{
				url = 'print_cargosguias.php?x=' + _iddistribucionunidad_md5;

				window.open(url, '_blank');
			}		
		}

		function f_PrintRCI_PDF(_iddistribucionunidad_md5) {
			// MAX (28/0/2024 17:13): Kharoll volvió a solicitar que se unifique el formato
			// // Obteniendo la información de Modalidades de Envío
			// 	$.post( "apis/backend.php", { accion: "get_segundotramoprogramacion_rcimodalidadenvio", id_unidad: _iddistribucionunidad_md5 },
			//     function( data ) {
			//       if(data.estado == 1){
			//       	$.each( data.res, function( key, val ) {
			//           var url = 'print_rci.php?x=' + _iddistribucionunidad_md5 + '&m=' + val.guias_idmodalidadenvio;

			//           window.open(url, '_blank');
			//         });
			//       }
			//       else{
			//       	alert("Esta Unidad aún no tiene guías generadas.");
			//       }

			//   }, "json");

			var url = 'print_rci.php?x=' + _iddistribucionunidad_md5;

			window.open(url, '_blank');
		}

		function f_PrintDistribucionDespachos(_idprogramacion_md5) {
			// Obtener la lista de Códigos de Despacho, solo para Colibrí se abrirá un solo formato
			if (idplanta_Selected == 3) {
				var url = 'print_programaciondistribucion.php?x=' + _idprogramacion_md5 + '&p=' + idplanta_Selected;

				window.open(url, '_blank');
			} else {
				$.post("apis/backend.php", {
						accion: "get_ListaCodigosDespachoxProgramacion",
						id_programacion: _idprogramacion_md5
					},
					function(data) {
						if (data.estado == 1) {
							$.each(data.res, function(key, val) {
								var url = 'print_programaciondistribucion2.php?x=' + val.CODIGO_DESPACHO;

								window.open(url, '_blank');
							});
						}

						f_SetCapacidad();

					}, "json");
			}
		}

		function f_PrintPesosMedidas(_item_unidad, _id_distribucionunidad, _id_distribucionunidad_md5, _remitente_ruc, _configuracionvehicular_pesobrutomaximo, _configuracionvehicular_pesobrutomaximo2, _configuracionvehicular_pesobrutototaltransportado, _tiene_ingresomanual) {
			// Setea variables hidden
				$("#hd_idprogramacionunidad").val(_id_distribucionunidad);
				$("#hd_idprogramacionunidadmd5").val(_id_distribucionunidad_md5);

			// Limpia objetos
				$("#configuracion_vehicular").val('');
				$("#configuracion_vehicular").trigger('change');

			// Setea título
				$("#modal_configuracionvehicularLabel").html(_item_unidad);

			// Obtiene Configuración Vehicular
				$.post("apis/backend.php", { accion: "get_ConfiguracionVehicular", id_distribucionunidad: _id_distribucionunidad, remitente_ruc: _remitente_ruc },
					function(data) {
						if (data.estado == 1) {
							$.each(data.res, function(key, val) {
								//$("#configuracion_vehicular").val(val.ID_CONFIGURACIONVEHICULAR);

								f_GetConfiguracionVehicular(val.Id)

								// $("#pesobruto_maximo").val(val.configuracionvehicular_pesobrutomaximo);
								// $("#pesobruto_maximo2").val(val.configuracionvehicular_pesobrutomaximo2);

								// // Setea el Peso Bruto Total Transportado
								// 	if (val.configuracionvehicular_pesobrutototaltransportado == null){
								// 		$("#pesobruto_total").val(data.peso_distribuido);
								// 	}
								// 	else{
								// 		$("#pesobruto_total").val(val.configuracionvehicular_pesobrutototaltransportado);
								// 	}
							});

							$("#pesobruto_maximo").val(_configuracionvehicular_pesobrutomaximo);
							$("#pesobruto_maximo2").val(_configuracionvehicular_pesobrutomaximo2);
							$("#pesobruto_total").val(_configuracionvehicular_pesobrutototaltransportado);

							// Valida si tiene Ingreso Manual
								$("#pesobruto_maximo").prop('disabled', true);

								if (_tiene_ingresomanual == 1 && idplanta_Selected == 15){
									$("#pesobruto_maximo").prop('disabled', false);
								}
						}

					}, "json");

			// Abre modal
				f_OpenModal('modal_configuracionvehicular');
		}

		function f_ConfiguracionVehicular_Selected() {
			if ($("#configuracion_vehicular").val() == undefined){
				return;
			}

			var id_configuracionvehicular = $("#configuracion_vehicular").val().split('|')[0];
			var pesobruto_maximo = $("#configuracion_vehicular").val().split('|')[1];
			var tiene_ingresomanual = $("#configuracion_vehicular").val().split('|')[2];

			// Valida si tiene Ingreso Manual
				$("#pesobruto_maximo").prop('disabled', true);

				// if (tiene_ingresomanual == 1 && idplanta_Selected == 15){

				if (tiene_ingresomanual == 1){
					$("#pesobruto_maximo").prop('disabled', false);
				}

			// Setea Peso Bruto
				$("#pesobruto_maximo").val(pesobruto_maximo);
		}

		function f_AddLoteAUM(_modo, _id_programacion, _item_programacion, _cod_lote, _peso_estimado) {
			// Seteando Título
			if (_modo == 'N') {
				$("#modal_loteaumLabel").html('Nuevo Lote AUM');
			} else {
				$("#modal_loteaumLabel").html('Editar Lote AUM: <b>' + _cod_lote + '</b>');
			}

			// Seteo de variables hidden
			$("#loteaum_modograbar").val(_modo);
			$("#loteaum_idprogramacion").val(_id_programacion);
			$("#loteaum_itemprogramacion").val(_item_programacion);

			// Seteando datos
			if (_modo == 'N') {
				$("#loteaum_peso").val('');
			} else {
				$("#loteaum_peso").val(_peso_estimado);
			}

			// Abre modal
			f_OpenModal('modal_loteaum');
		};

		function f_PrintRCI(_item_unidad, _id_distribucionunidad, _id_distribucionunidad_md5) {
			// Setea variables hidden
			$("#hdrci_idprogramacionunidad").val(_id_distribucionunidad);
			$("#hdrci_idprogramacionunidadmd5").val(_id_distribucionunidad_md5);

			// Setea título
			$("#modal_configuracionrciLabel").html(_item_unidad);

			// Limpia objetos
			$("#rci_fechasalida").val('<?php echo $g_date ?>');
			$("#rci_horasalida").val('');
			$("#rci_precintos").val('');
			$("#rci_telefono").val('');

			// Obtiene Configuración Vehicular
			$.post("apis/backend.php", {
					accion: "get_InfoRCI",
					id_distribucionunidad: _id_distribucionunidad
				},
				function(data) {
					if (data.estado == 1) {
						$.each(data.res, function(key, val) {
							$("#rci_fechasalida").val(val.inforci_fechasalida);
							$("#rci_horasalida").val(val.inforci_horasalida);
							$("#rci_precintos").val(val.inforci_infoprecintos);
							$("#rci_telefono").val(val.inforci_telefono);
						});
					}

				}, "json");

			// Abre modal
			f_OpenModal('modal_configuracionrci');
		}

		function f_GetConfiguracionVehicular(_id_registro){
			$("#configuracion_vehicular").html('');

			$.post("apis/backend.php", { accion: "get_ListaConfiguracionVehicular", id_registro: _id_registro },
				function(data) {
					if (data.estado == 1) {
						$("#configuracion_vehicular").html(data.html);
					}

				}, "json");
		}
	</script>

	<!-- Funciones Secundarias -->
	<script type="text/javascript">
		function f_LoadingPlantas(_is_show) {
			if (_is_show == 1) {
				$("#wt_plantas").show();
			} else {
				$("#wt_plantas").hide();
			}
		}

		function f_LoadingLotes(_is_show) {
			if (_is_show == 1) {
				$("#wt_loadinglotes").show();
			} else {
				$("#wt_loadinglotes").hide();
			}
		}

		function f_LoadingLotes_Distribucion(_is_show) {
			if (_is_show == 1) {
				$("#wt_loadinglotesdistribuciones").show();
			} else {
				$("#wt_loadinglotesdistribuciones").hide();
			}
		}

		function f_LoadingProgramacion(_is_show) {
			if (_is_show == 1) {
				$("#wt_programacion").show();
			} else {
				$("#wt_programacion").hide();
			}
		}

		function f_LoadingDistribucion(_is_show) {
			if (_is_show == 1) {
				$("#wt_distribucion").show();
			} else {
				$("#wt_distribucion").hide();
			}
		}

		function f_HideListaProgramaciones(_x) {
			if (_x == 1) {
				$("#div_plantas").hide();
				$("#div_detalle").width('100%');

				f_CerrarDiv('C', 'div_ShowListaProgramaciones');
				f_CerrarDiv('A', 'div_HideListaProgramaciones');
			} else {
				$("#div_plantas").show();
				$("#div_detalle").width('');

				f_CerrarDiv('A', 'div_ShowListaProgramaciones');
				f_CerrarDiv('C', 'div_HideListaProgramaciones');
			}
		}

		function f_SelectChkLotes() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var d = 1;

			$("#tbl_FiltroLotes tr").filter(function() {
				$("#chk_lote_" + d).prop('checked', is_checked);

				d++;
			});

			// Cuenta los seleccionados
			f_CountSelected();
		}

		function f_CountSelected() {
			var d = 1;
			var _count = 0;
			var _total_tmh = 0;
			var _total_tms = 0;

			$("#tbl_FiltroLotes tr").filter(function() {
				if ($("#chk_lote_" + d).prop('checked')) {
					_total_tmh += parseFloat($(this).find("td:eq(7)").text());
					_total_tms += ((isNaN($(this).find("td:eq(8)").text().trim())) ? 0 : parseFloat($(this).find("td:eq(8)").text()));

					_count++;
				}

				d++;
			});

			// Setea el conteo de seleccionados
			$("#lbl_countlotes").html(_count);

			// Setea el total de Netos
			$("#lbl_totaltmh").html(f_RedondearDecimales(_total_tmh, 3));
			$("#lbl_totaltms").html(f_RedondearDecimales(_total_tms, 3));
		}

		function f_SelectChkLotes_Distribucion() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk_Distribucion").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var d = 1;

			$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
				$("#chk_lotedistribucion_" + d).prop('checked', is_checked);

				d++;
			});

			// Cuenta los seleccionados
			f_CountSelected_Distribucion();
		}

		function f_CountSelected_Distribucion() {
			var d = 1;
			var _count = 0;
			var _total_distribuido = 0;

			$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
				if ($("#chk_lotedistribucion_" + d).prop('checked')) {
					_total_distribuido += (($("#tmh_distribuido_" + d).val().length == 0) ? 0 : parseFloat($("#tmh_distribuido_" + d).val()));

					_count++;
				}

				d++;
			});

			// Setea el conteo de seleccionados
			$("#lbl_countlotes_Distribucion").html(_count);

			// Setea el total de Netos
			$("#lbl_totaltmh_Distribuido").html(f_RedondearDecimales(_total_distribuido, 3));
		}

		function f_LoadingGrabarProgramacion(_is_show) {
			if (_is_show == 1) {
				$("#wt_grabarprogramacion").show();

				$(".wt_grabarprogramacion_button").prop('disabled', true);
			} else {
				$("#wt_grabarprogramacion").hide();

				$(".wt_grabarprogramacion_button").prop('disabled', false);
			}
		}

		function f_LoadingGrabarProgramacion_Distribucion(_is_show) {
			if (_is_show == 1) {
				$("#wt_grabardistribucion").show();

				$(".wt_grabardistribucion_button").prop('disabled', true);
			} else {
				$("#wt_grabardistribucion").hide();

				$(".wt_grabardistribucion_button").prop('disabled', false);
			}
		}

		function f_SetCapacidad() {
			var tipo_vehiculo = $("#tipo_unidad").val().split('|')[0];
			var tiene_carreta = (($("#tipo_unidad").val().length == 0) ? 0 : $("#tipo_unidad").val().split('|')[1]);
			var capacidad_unidad = '';

			// Oculta Placa 2
			$("#div_placa2").hide();

			// Determinando si el tipo de unidad tiene carreta
			if (tiene_carreta == 1) {
				$("#div_placa2").show();
			}

			// Obteniendo la Capacidad
			if (tiene_carreta == 0) {
				if ($("#distribucion_unidad").val() != null) {
					if ($("#distribucion_unidad").val().length > 0) {
						var capacidad_unidad = $("#distribucion_unidad").val().split('|')[1];

						if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0) {
							capacidad_unidad = 'Sin Asignar...';
						} else {
							capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
						}
					}
				}
			} else {
				if ($("#distribucion_unidad2").val() != null) {
					if ($("#distribucion_unidad2").val().length > 0) {
						var capacidad_unidad = $("#distribucion_unidad2").val().split('|')[1];

						if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0) {
							capacidad_unidad = 'Sin Asignar...';
						} else {
							capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
						}
					}
				}
			}

			$("#unidad_capacidad").val(capacidad_unidad);
		}

		function f_LoadingGrabar_CierrePrograma(_is_show) {
			if (_is_show == 1) {
				$("#wt_configuracionvehicular").show();

				$(".wt_configuracionvehicular_button").prop('disabled', true);
			} else {
				$("#wt_configuracionvehicular").hide();

				$(".wt_configuracionvehicular_button").prop('disabled', false);
			}
		}

		function f_LoadingGrabar_ConfiguracionVehicular(_is_show) {
			if (_is_show == 1) {
				$("#wt_configuracionvehicular").show();

				$(".wt_configuracionvehicular_button").prop('disabled', true);
			} else {
				$("#wt_configuracionvehicular").hide();

				$(".wt_configuracionvehicular_button").prop('disabled', false);
			}
		}

		function f_LoadingGrabar_RCI(_is_show) {
			if (_is_show == 1) {
				$("#wt_rci").show();

				$(".wt_rci_button").prop('disabled', true);
			} else {
				$("#wt_rci").hide();

				$(".wt_rci_button").prop('disabled', false);
			}
		}

		function f_LoadingConfirmarGuia(_is_show) {
			if (_is_show == 1) {
				$("#wt_confirmarguia").show();

				$(".wt_confirmarguia_button").prop('disabled', true);
			} else {
				$("#wt_confirmarguia").hide();

				$(".wt_confirmarguia_button").prop('disabled', false);
			}
		}
	</script>

	<!-- Funciones de Grabación -->
	<script type="text/javascript">
		function f_ConfirmarProgramacion() {
			// Recuperando datos hidden
				var modo = $("#modo_grabarprogramacion").val();
				var id_programacion = $("#id_programacion").val();

			// Validando datos
				if ($("#lbl_countlotes").html().trim() == 0) {
					alert("Debe seleccionar al menos un Lote.");

					return;
				}

			// Arma Array de Lotes seleccionados
				var l = 1;
				var arr_lotes = '';

				$("#tbl_FiltroLotes tr").each(function() {
					if ($("#chk_lote_" + l).prop('checked')) {
						// arr_lotes += $(this).find("td:eq(1)").text().trim() + '|';
						arr_lotes += $("#td_idlote_" + l).val() + '|';
					}

					l++;
				});

				arr_lotes = arr_lotes.substring(0, arr_lotes.length - 1);

			// Grabando Datos
				f_LoadingGrabarProgramacion(1);

				if (modo == 'N') {
					$.post("apis/backend.php", {
							accion: "confirmar_ProgramacionLote",
							id_planta: idplanta_Selected,
							arr_lotes: arr_lotes
						},
						function(data) {
							if (data.estado == 1) {
								f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);

								f_cerrarModal('modal_adminprogramaciones');
							} else {
								alert("Ocurrió un error al momento de agregar el Lote.\nCódigo de Error N° " + data.estado + ".");
							}

							f_LoadingGrabarProgramacion(0);

						}, "json");
				} else {
					$.post("apis/backend.php", {
							accion: "confirmar_ProgramacionLote_AddLote",
							id_planta: idplanta_Selected,
							id_programacion: id_programacion,
							arr_lotes: arr_lotes
						},
						function(data) {
							if (data.estado == 1) {
								f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected, 1, $("#item_programacion").val(), $("#id_programacion").val());

								f_cerrarModal('modal_adminprogramaciones');
							} else {
								alert("Ocurrió un error al momento de grabar la Programación.");
							}

							f_LoadingGrabarProgramacion(0);

						}, "json");
				}
		}

		function f_EliminarProgramacion(_item, _id_programacion, _fechahora, _usuario) {
			if (!confirm("¿Está seguro de Eliminar la Programación seleccionada?\n\n   - Fecha Hora Creación: " + _fechahora + "\n   - Usuario Creación: " + _usuario + "\n\nSi continua se eliminarán los datos permanentemente incluyendo todos los Lote y sus Distribuciones si es que las tuviera.\n\n¿Está seguro de continuar?")) {
				return;
			}

			// Eliminando Datos
			$.post("apis/backend.php", {
					accion: "eliminar_Programacion",
					id_programacion: _id_programacion
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);
					} else {
						if (data.estado == 0) {
							alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
						}

						if (data.estado == 2) {
							alert("Ocurrió un error al momento de eliminar el Lote programado.");
						}

						if (data.estado == 3) {
							alert("Ocurrió un error al momento de grabar el Log del Código de Despacho.");
						}

						if (data.estado == 4) {
							alert("Ocurrió un error al momento de actualizar los correlativos de los Códigos de Despacho.");
						}
					}

				}, "json");
		}

		function f_EliminarProgramacion_Lote(_item, _id_programacionlote, _cod_lote) {
			if (!confirm("¿Está seguro de Eliminar el Lote seleccionado: " + _cod_lote + "?\n\nSi continua se eliminarán los datos permanentemente incluyendo las Distribuciones si es que las tuviera.\n\n¿Está seguro de continuar?")) {
				return;
			}

			// Eliminando Datos
			$.post("apis/backend.php", {
					accion: "eliminar_ProgramacionLote",
					id_programacionlote: _id_programacionlote
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);
					} else {
						if (data.estado == 0) {
							alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
						}

						if (data.estado == 2) {
							alert("Ocurrió un error al momento de eliminar el Lote programado.");
						}

						if (data.estado == 3) {
							alert("Ocurrió un error al momento de grabar el Log del Código de Despacho.");
						}

						if (data.estado == 4) {
							alert("Ocurrió un error al momento de actualizar los correlativos de los Códigos de Despacho.");
						}

						if (data.estado == 5) {
							alert("Ocurrió un error al momento de eliminar el Lote de la tabla maestra.");
						}

						if (data.estado == 6) {
							alert("Ocurrió un error al momento de eliminar el Lote de la tabla de Validación de Datos del Primer Tramo.");
						}

						if (data.estado == 7) {
							alert("Ocurrió un error al momento de eliminar la Distribución del Lote seleccionado.");
						}
					}

				}, "json");
		}

		function f_ConfirmarDistribucion() {
			// Recuperando datos
			modo = $("#modo_grabardistribucion").val();

			var tipo_unidad = $("#tipo_unidad").val();

			var id_unidad = $("#distribucion_unidad").val();

			if (id_unidad == null) {
				id_unidad = '';
			} else {
				if (id_unidad.length > 0) {
					id_unidad = id_unidad.split('|')[0];
				}
			}

			var id_unidad2 = $("#distribucion_unidad2").val();

			if (id_unidad2 == null) {
				id_unidad2 = '';
			} else {
				if (id_unidad2.length > 0) {
					id_unidad2 = id_unidad2.split('|')[0];
				}
			}

			var capacidad = $("#unidad_capacidad").val();

			var total_distribuido = parseFloat($("#lbl_totaltmh_Distribuido").html().trim());
			total_distribuido = f_RedondearDecimales(total_distribuido, 3);

			// Validando datos
			if (tipo_unidad == null) {
				alert("Debe la seleccionar el Tipo de Vehículo.");

				return;
			}
			if (tipo_unidad.length == 0) {
				alert("Debe la seleccionar el Tipo de Vehículo.");

				return;
			}

			// if (id_unidad == null){
			//   alert("Debe la seleccionar la Unidad.");

			//   return;
			// }
			// if (id_unidad.length == 0){
			//   alert("Debe la seleccionar la Unidad.");

			//   return;
			// }

			// if (isNaN(capacidad)){
			// 	alert("La Unidad seleccionada no tiene una Capacidad asignada.");

			// 	return;
			// }
			// else{
			// 	capacidad = f_RedondearDecimales(capacidad, 3);
			// }

			if (capacidad == null) {
				capacidad = 0;
			}
			if (capacidad.length == 0) {
				capacidad = 0;
			}

			if (isNaN(capacidad)) {
				capacidad = 0;
			} else {
				capacidad = f_RedondearDecimales(capacidad, 3);
			}

			if ($("#lbl_countlotes_Distribucion").html().trim() == 0) {
				alert("Debe seleccionar al menos un Lote.");

				return;
			}

			// if (parseFloat(total_distribuido) > parseFloat(capacidad)){
			// 	alert("El Total de Toneladas Distribuídas de los lotes seleccionados no puede ser mayor a la Capacidad de la Unidad.\n\n   - Capacidad Unidad: " + capacidad + "\n   - Total Distribuídio: " + total_distribuido);

			// 	return;
			// }

			// if (parseFloat(total_distribuido) > parseFloat(capacidad)){
			// 	if (!confirm("El Total de Toneladas por Distribuir es mayor a la Capacidad de la Unidad.\n\n   - Capacidad Unidad: " + capacidad + "\n   - Total por Distribuir: " + total_distribuido + "\n\n¿Está seguro de continuar?")){
			// 		return;
			// 	}
			// }

			// Recorriendo la grilla y verificando que las distribuciones sean correctas
			var d = 1;
			var _object = '';
			var cod_lote = '';
			var td_pordistribuir = 0;
			var td_distribuido = 0;
			var td_loteaum = 0;
			var is_completo = 0;
			var is_complemento = 0;
			var _script = '';
			var continuar = 1;

			$("#tbl_FiltroLotes_Distribucion tr").each(function() {
				_object = $("#chk_lotedistribucion_" + d);

				if (_object.html() != undefined) {
					if (_object.prop('checked')) {
						cod_lote = $("#lote_distribucion_" + d).html().trim();
						id_validaciondatos = $("#lote_idvalidaciondatos_" + d).val().trim();
						td_pordistribuir = parseFloat($("#por_distribuir_" + d).html().trim());
						td_distribuido = (($("#tmh_distribuido_" + d).val().length == 0) ? 0 : parseFloat($("#tmh_distribuido_" + d).val()));
						td_loteaum = $("#td_loteaum_" + d).val();
						// is_complemento = (($("#chk_lotecomplemento_" + d).prop('checked')) ? 1 : 0);
						is_complemento = 0;
						is_complemento_de = 'NULL';

						// Si es Complemento
						if (is_complemento == 1) {
							is_complemento_de = $("#sel_complemento_" + d).val();
						}

						// Validando Distribución
						if (td_distribuido <= 0) {
							alert("La Distribución ingresada para el Lote: " + cod_lote + " no es correcta.");

							d = 1000;

							continuar = 0;

							return;
						}

						if ((td_loteaum == 0) && (td_pordistribuir < td_distribuido)) {
							alert("La Distribución ingresada para el Lote: " + cod_lote + " no puede ser mayor a las Toneladas por Distribuir.");

							d = 1000;

							continuar = 0;

							return;
						}

						// Comprobando si es una distribución completa
						is_completo = ((td_pordistribuir == td_distribuido) ? 1 : 0);

						// Armando la cadena de datos a enviar para cada lote
						_script += cod_lote + ';' + td_distribuido + ';' + is_completo + ';' + td_loteaum + ';' + is_complemento + ';' + is_complemento_de + ';' + id_validaciondatos + '|';
					}

					d++;
				}
			});

			// Seteando script de datos
			if (continuar == 1) {
				_script = _script.substring(0, _script.length - 1);

				// Grabando Datos
				// f_LoadingGrabarProgramacion_Distribucion(1);

				if (modo == 'N') {
					$.post("apis/backend.php", {
							accion: "confirmar_DistribucionLotes",
							id_programacion: idprog_Selected,
							tipo_unidad: tipo_unidad,
							id_unidad: id_unidad,
							id_unidad2: id_unidad2,
							capacidad: capacidad,
							script_x: _script
						},
						function(data) {
							if (data.estado == 1) {
								f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);

								f_cerrarModal('modal_admindistribuciones');
							} else {
								alert("Ocurrió un error al momento de registrar la Distribución.\nCódigo de Error N° " + data.estado + ".");
							}

							f_LoadingGrabarProgramacion_Distribucion(0);

						}, "json");
				} else {
					var id_distribucionunidad = $("#id_distribucion").val();

					$.post("apis/backend.php", {
							accion: "confirmar_DistribucionLotes_AddLote",
							id_distribucionunidad: id_distribucionunidad,
							script_x: _script
						},
						function(data) {
							if (data.estado == 1) {
								f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);

								f_cerrarModal('modal_admindistribuciones');
							} else {
								alert("Ocurrió un error al momento de registrar la Distribución.\nCódigo de Error N° " + data.estado + ".");
							}

							f_LoadingGrabarProgramacion_Distribucion(0);

						}, "json");
				}
			}
		}

		function f_Edit(_id_object, _item, _id_registro, _item_unidad) {
			var _valor = '';

			// Obtiene datos
			if (_id_object == 1) {
				_valor = $("#responsabledespacho_" + _item).val();
			}

			if (_id_object == 2) {
				_valor = $("#peso_distribuido_" + _item).val();
			}

			if (_id_object == 3) {
				_valor = $("#tipo_carga_" + _item).val();
			}

			if (_id_object == 4) {
				_valor = $("#idunidad_" + _item).val();
			}

			if (_id_object == 5) {
				_valor = $("#td_unidad_capacidad_" + _item).val();
			}

			if (_id_object == 6) {
				_valor = $("#ingreso_planta_" + _item).val();
			}

			if (_id_object == 7) {
				_valor = $("#fecha_ingresoplanta_" + _item).val();
			}

			if (_id_object == 8) {
				_valor = $("#id_tipounidad_" + _item).val();

				// Muestra u oculta la Placa 2
				if (_valor.split('|')[1] == 1) {
					$("#td_idunidad2_" + _item).show();
				} else {
					$("#td_idunidad2_" + _item).hide();

					$("#idunidad2_" + _item).val('');
					$("#idunidad2_" + _item).trigger('change');
				}
			}

			if (_id_object == 9) {
				_valor = $("#idunidad2_" + _item).val();
			}

			if (_id_object == 10) {
				_valor = $("#td_proveedorminero_" + _item).val();
			}

			if (_id_object == 11) {
				_valor = $("#idcoordinadortransporte_" + _item).val();
			}

			$.post("apis/backend.php", {
					accion: "grabar_EditDistribucionLote",
					id_object: _id_object,
					id_registro: _id_registro,
					valor: _valor
				},
				function(data) {
					if (data.estado == 1) {
						if (_id_object == 2) {
							f_VerifyDistrbucion();
							f_GetTotalDistrbucion(_item_unidad);

							f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);
						}

						if (_id_object == 4) {
							// Verifica si tiene Carreta
							var tiene_carreta = $("#id_tipounidad_" + _item).val().split('|')[1];

							if (tiene_carreta == 0) {
								var _capacidad = _valor.split('|')[1];

								$("#td_unidad_capacidad_" + _item).val(f_RedondearDecimales(_capacidad / 1000, 3));
							}

							f_GetTotalDistrbucion(_item_unidad);
						}

						if (_id_object == 5) {
							f_GetTotalDistrbucion(_item_unidad);
						}

						if (_id_object == 9) {
							var _capacidad = _valor.split('|')[1];

							$("#td_unidad_capacidad_" + _item).val(f_RedondearDecimales(_capacidad / 1000, 3));

							f_GetTotalDistrbucion(_item_unidad);
						}
					} else {
						alert("Ocurrió un error al momento de grabar los datos.");
					}

				}, "json");
		}

		function f_SetColor_Grabar() {
			// Obteniendo valores
			var _id_registro = $("#hd_distribucionlote_id").val();
			var _item = $("#hd_distribucionlote_item").val();
			var _cod_lote = $("#td_distribucionlote_" + _item).html().trim();
			var _color = $("#colorSeleccionado").val();

			$.post("apis/backend.php", {
					accion: "grabar_DistribucionLote_SetColor",
					id_registro: _id_registro,
					color: _color,
					cod_lote: _cod_lote
				},
				function(data) {
					if (data.estado == 1) {
						f_cerrarModal('modal_SetColor');

						// Setea objeto
						$(".td_lote_" + _cod_lote).css('background-color', _color);
					} else {
						alert("Ocurrió un error al momento de grabar los datos.");
					}

				}, "json");
		}

		function f_EliminarDistribucion(_item, _id_distribucion, _placa) {
			if (!confirm("¿Está seguro de Eliminar la Unidad seleccionada?\n\n   - Unidad: " + _item + "\n   - Placa: " + _placa + "\n\nSi continua se eliminarán los datos permanentemente incluyendo sus Distribuciones.\n\n¿Está seguro de continuar?")) {
				return;
			}

			// Eliminando Datos
			$.post("apis/backend.php", {
					accion: "eliminar_Distribucion",
					id_distribucion: _id_distribucion
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);
					} else {
						if (data.estado == 0) {
							alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
						}

						if (data.estado == 2) {
							alert("Ocurrió un error al momento de eliminar el Lote programado.");
						}

						if (data.estado == 3) {
							alert("Ocurrió un error al momento de grabar el Log del Código de Despacho.");
						}

						if (data.estado == 4) {
							alert("Ocurrió un error al momento de actualizar los correlativos de los Códigos de Despacho.");
						}
					}

				}, "json");
		}

		function f_EliminarDistribucion_Lote(_id_distribucionlote, _cod_lote, _num_parte, _placa, _id_distribucionunidad) {
			// Setea el N° de Parte
			var num_parte = '';

			if (_num_parte.length > 0) {
				num_parte = "\n   - N° Parte: PARTE " + _num_parte;
			}
			if (!confirm("¿Está seguro de Eliminar el Lote seleccionado?\n\n   - Lote: " + _cod_lote + num_parte + "\n   - Unidad: " + _placa + "\n\nSi continua se eliminarán los datos permanentemente.\n¿Está seguro de continuar?")) {
				return;
			}

			// Eliminando Datos
			$.post("apis/backend.php", {
					accion: "eliminar_DistribucionLote",
					id_distribucionlote: _id_distribucionlote,
					id_distribucionunidad: _id_distribucionunidad
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);
					} else {
						if (data.estado == 0) {
							alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
						}

						if (data.estado == 2) {
							alert("Ocurrió un error al momento de eliminar el Lote distribuído.");
						}

						if (data.estado == 3) {
							alert("Ocurrió un error al momento de actualizar los N° de Partes siguientes.");
						}

						if (data.estado == 4) {
							alert("Ocurrió un error al momento de actualizar el N° de Parte que quedó solo.");
						}
					}

				}, "json");
		}

		function f_GrabarCierrePrograma() {
			var fecha_estimadadespacho = $("#cierre_fechadespacho").val();

			// Validando datos
			if (fecha_estimadadespacho == null) {
				alert("Debe ingresar la Fecha Estimada de Despacho.");

				return;
			}
			if (fecha_estimadadespacho.length == 0) {
				alert("Debe ingresar la Fecha Estimada de Despacho.");

				return;
			}

			// Grabar Cierre
			f_LoadingGrabar_CierrePrograma(1);

			$.post("apis/backend.php", {
					accion: "cierre_SegundoTramo_ProgramaDistribucion",
					id_programacion: idprog_Selected,
					fecha_estimadadespacho: fecha_estimadadespacho
				},
				function(data) {
					if (data.estado == 1) {
						// Seteando variables html
						var _html1 = '<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_Reabrir(' + idprog_Selected + ', ' + itemprog_Selected + ')">';
						_html1 += '			<u> Reabrir </u>';
						_html1 += '		</label>';

						// Setea objetos
						$("#td_cierreprograma_1_" + itemprog_Selected).html(_html1);
						$("#td_cierreprograma_2_" + itemprog_Selected).html(fecha_estimadadespacho);
						$("#td_cierreprograma_3_" + itemprog_Selected).html(data.fechahora_registro);
						$("#td_cierreprograma_4_" + itemprog_Selected).html(data.usuario_registro);

						// Setea objetos de Cierre
						$(".is_programacerrado_" + itemprog_Selected).hide();
						$(".is_programacerrado_object_" + itemprog_Selected).prop('disabled', true);

						f_cerrarModal('modal_CierrePrograma');
					} else {
						alert("Ocurrió un error al momento de grabar el Cierre del Programa.");
					}

					f_LoadingGrabar_CierrePrograma(0);

				}, "json");
		}

		function f_Reabrir(_id_programacion, _item_cierre) {
			// Validando cierre
			if (!confirm("¿Está seguro de Reabrir el Programa seleccionado?")) {
				return;
			}

			// Reabrir Cierre
			$.post("apis/backend.php", {
					accion: "reabrir_SegundoTramo_ProgramaDistribucion",
					id_programacion: _id_programacion
				},
				function(data) {
					if (data.estado == 1) {
						// Habilita los objetos de Cierre
						var _html = '<button class="btn btn-primary" type="button" onclick="f_LoadItemProgramacion(' + _item_cierre + ', ' + _id_programacion + ', 1, ' + _item_cierre + ');" style="font-size: 13px;">';
						_html += '			<b>Cerrar</b>';
						_html += '    </button>';

						$("#td_cierreprograma_1_" + _item_cierre).html(_html);
						$("#td_cierreprograma_2_" + _item_cierre).html('');
						$("#td_cierreprograma_3_" + _item_cierre).html('');
						$("#td_cierreprograma_4_" + _item_cierre).html('');

						// Setea objetos de Cierre
						$(".is_programacerrado_" + _item_cierre).show();
						$(".is_programacerrado_object_" + _item_cierre).prop('disabled', false);
					} else {
						alert("Ocurrió un error al momento de Reabrir el Cierre.");
					}

				}, "json");
		}

		function f_ConfirmarLoteAum() {
			// Recuperando datos
			var modad_grabar = $("#loteaum_modograbar").val();
			var _id_programacion = $("#loteaum_idprogramacion").val();
			var _item_programacion = $("#loteaum_itemprogramacion").val();
			var peso_estimado = $("#loteaum_peso").val();

			// Validando datos
			if (peso_estimado == null) {
				alert("Debe ingresar el Peso Estimado para el Lote AUM.");

				return;
			}
			if (peso_estimado.length == 0) {
				alert("Debe ingresar el Peso Estimado para el Lote AUM.");

				return;
			}
			if (peso_estimado <= 0) {
				alert("El Peso Estimado no es correcto.");

				return;
			}

			if (!confirm("¿Está seguro de crear un Lote AUM para la Programación seleccionada?")) {
				return
			}

			// Creando Lote AUM
			$.post("apis/backend.php", {
					accion: "crear_NuevoLoteAUM",
					id_programacion: _id_programacion,
					id_destino: idplanta_Selected,
					peso_estimado: peso_estimado
				},
				function(data) {
					if (data.estado == 1) {
						// Agregar el Lote AUM a la programación
						$.post("apis/backend.php", {
								accion: "confirmar_ProgramacionLote_AddLote",
								id_planta: idplanta_Selected,
								id_programacion: _id_programacion,
								arr_lotes: data.cod_lote,
								is_loteaum: 1
							},
							function(data2) {
								if (data2.estado == 1) {
									f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected, 1, _item_programacion, _id_programacion);
								} else {
									alert("Ocurrió un error al momento de agregar el Lote AUM a la programación.");
								}

							}, "json");
					} else {
						alert("Ocurrió un error al momento de crear el Lote AUM.");
					}

					f_cerrarModal("modal_loteaum");

				}, "json");
		}

		function f_PrintPesosMedidas_Informe() {
			var id_distribucionunidad = $("#hd_idprogramacionunidad").val();
			var id_distribucionunidadmd5 = $("#hd_idprogramacionunidadmd5").val();
			var id_configuracionvehicular = $("#configuracion_vehicular").val();
			var pesobruto_maximo = $("#pesobruto_maximo").val();
			var pesobruto_maximo2 = $("#pesobruto_maximo2").val();
			var pesobruto_total = $("#pesobruto_total").val();

			// Validando datos
				if (id_configuracionvehicular == null) {
					alert("Debe seleccionar la Configuración Vechiular.");

					return;
				}
				if (id_configuracionvehicular.length == 0) {
					alert("Debe seleccionar la Configuración Vechiular.");

					return;
				}

				if (pesobruto_maximo == null) {
					alert("No ha asignado el Peso Bruto Máximo a la Configuración Vehicular seleccionada.");

					return;
				}
				if (pesobruto_maximo.length == 0) {
					alert("No ha asignado el Peso Bruto Máximo a la Configuración Vehicular seleccionada.");

					return;
				}

				if (!(id_configuracionvehicular.split('|')[0] == 2 || id_configuracionvehicular.split('|')[0] == 3)){ // Para C3 y C4 no debe obligar bonificación
					if (pesobruto_maximo2 == null) {
						alert("Debe ingresar el Peso Bruto Máximo (Con Bonificación).");

						return;
					}
					if (pesobruto_maximo2.length == 0) {
						alert("Debe ingresar el Peso Bruto Máximo (Con Bonificación).");

						return;
					}
				}

				if (pesobruto_total == null) {
					alert("Debe ingresar el Peso Bruto Total Transportado.");
					return;
				}
				if (pesobruto_total.length == 0) {
					alert("Debe ingresar el Peso Bruto Total Transportado.");
					return;
				}

			// Grabar Cierre
				f_LoadingGrabar_ConfiguracionVehicular(1);

			$.post("apis/backend.php", {
					accion: "grabar_ConfiguracionVehicular_PesosyMedidas",
					id_distribucionunidad: id_distribucionunidad,
					id_configuracionvehicular: id_configuracionvehicular,
					pesobruto_maximo: pesobruto_maximo,
					pesobruto_maximo2: pesobruto_maximo2,
					pesobruto_total: pesobruto_total,
				},
				function(data) {
					if (data.estado == 1) {
						$.each(data.res, function(key, val) {
							// Imprime el Informe por Remitente
							var url = 'print_pesosmedidas.php?x=' + id_distribucionunidadmd5 + '&r=' + val.ruc + '&z=' + val.razon_social;

							window.open(url, '_blank');
						});

						// Cerrar Modal
							f_cerrarModal('modal_configuracionvehicular');
					}
					else{
						alert("Ocurrió un error al momento de grabar la Configuración Vehicular.");
					}

					f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);

					f_LoadingGrabar_ConfiguracionVehicular(0);

				}, "json");

		}

		function f_PrintRCI_Informe() {
			var id_distribucionunidad = $("#hdrci_idprogramacionunidad").val();
			var id_distribucionunidadmd5 = $("#hdrci_idprogramacionunidadmd5").val();

			var fecha_salida = $("#rci_fechasalida").val();
			var hora_salida = $("#rci_horasalida").val();
			var info_precintos = $("#rci_precintos").val();
			var telefono = $("#rci_telefono").val();

			// Validando datos
			if (fecha_salida == null) {
				alert("Debe registrar la Fecha de Salida.");

				return;
			}
			if (fecha_salida.length == 0) {
				alert("Debe registrar la Fecha de Salida.");

				return;
			}

			if (hora_salida == null) {
				alert("Debe registrar la Hora de Salida.");

				return;
			}
			if (hora_salida.length == 0) {
				alert("Debe registrar la Hora de Salida.");

				return;
			}

			if (info_precintos == null) {
				if (!confirm("¿Está seguro de emitir el RCI sin Información de Precintos?")) {
					return;
				}
			}
			if (info_precintos.length == 0) {
				if (!confirm("¿Está seguro de emitir el RCI sin Información de Precintos?")) {
					return;
				}
			}

			// Grabar Cierre
			f_LoadingGrabar_RCI(1);

			$.post("apis/backend.php", {
					accion: "grabar_InfoRCI",
					id_distribucionunidad: id_distribucionunidad,
					fecha_salida: fecha_salida,
					hora_salida: hora_salida,
					info_precintos: info_precintos,
					telefono: telefono
				},
				function(data) {
					if (data.estado == 1) {
						f_PrintRCI_PDF(id_distribucionunidadmd5);

						// Cerrar Modal
						f_cerrarModal('modal_configuracionrci');
					} else {
						alert("Ocurrió un error al momento de grabar la Configuración Vehicular.");
					}

					f_LoadingGrabar_RCI(0);

				}, "json");

		}

		// function f_GrabarCodigosCMH(_id_programaciondetalle, _item){
		// 	var codigo = "";

		// 	if (_item == 1){
		// 		codigo = $("#cmh_codigodocumentos_" + _id_programaciondetalle).val().trim().toUpperCase();
		// 	}
		// 	else{
		// 		codigo = $("#cmh_codigoguias_" + _id_programaciondetalle).val().trim().toUpperCase();
		// 	}

		// 	$.post("apis/backend.php", { accion: "grabar_ProgramacionDespachos_CodigosCMH", id_programaciondetalle: _id_programaciondetalle, item: _item, codigo: codigo },
		// 		function(data) {
		// 			if (data.estado == 0) {
		// 				alert("Ocurrió un error al momento de grabar el Código.");
		// 			}

		// 		}, "json");
		// }

		function f_Despachos_GrabarCodigoPlanta(_id_programaciondetalle){
			var codigo = $("#lote_codigodoplanta_" + _id_programaciondetalle).val().trim().toUpperCase();

			$.post("apis/backend.php", { accion: "grabar_ProgramacionDespachos_CodigosCMH", id_programaciondetalle: _id_programaciondetalle, codigo: codigo },
				function(data) {
					if (data.estado == 0) {
						alert("Ocurrió un error al momento de grabar el Código.");
					}

				}, "json");
		}
	</script>

	<!-- Funciones de Menús -->
	<script type="text/javascript">
		function f_SetDimension() {
			if (screen.width < 500) {
				$("#offcanvasExample").css('width', '60%');

			}
		}

		const inputFechaEmision = document.getElementById("fecha_emision");
		const inputGuiasFecha = document.getElementById("guia_fechas");
		inputFechaEmision.addEventListener("change", () => {

			// Obtener la fecha seleccionada en el input de fecha de emisión
			const fechaEmision = new Date(inputFechaEmision.value);

			// Establecer la fecha mínima en el input de guías como la fecha de emisión
			inputGuiasFecha.min = fechaEmision.toISOString().slice(0, 10);

			// Obtener la fecha seleccionada en el input de guías
			const fechaGuia = new Date(inputGuiasFecha.value);

			// Verificar si la fecha de guías es menor que la fecha de emisión
			if (fechaGuia < fechaEmision) {
				// Establecer la fecha de guías como la fecha de emisión
				inputGuiasFecha.value = inputFechaEmision.value;
			}
		});

		// Agregar un evento de cambio al input de guías
		inputGuiasFecha.addEventListener("change", () => {
			// Obtener la fecha seleccionada en el input de fecha de emisión
			const fechaEmision = new Date(inputFechaEmision.value);

			// Obtener la fecha seleccionada en el input de guías
			const fechaGuia = new Date(inputGuiasFecha.value);

			// Verificar si la fecha de guías es menor que la fecha de emisión
			if (fechaGuia < fechaEmision) {
				// Establecer la fecha de guías como la fecha de emisión
				inputGuiasFecha.value = inputFechaEmision.value;
			}
		});
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

				if (f == 1) {
					$("#tbl_detalle tr").filter(function() {
						return $(this).find("td").eq(columnIndex).text().trim().toLowerCase().indexOf(filterValue) > -1;
					}).show();
				} else {
					$("#tbl_detalle tr:visible").filter(function() {
						if (columnIndex == 3 || columnIndex == 6 || columnIndex == 10 || columnIndex == 11 || columnIndex == 15 || columnIndex == 17 || columnIndex == 18 || columnIndex == 22 || columnIndex == 23 || columnIndex == 24 || columnIndex == 25 || columnIndex == 28) {
							return $(this).find('td:eq(' + columnIndex + ') select option:selected').text().trim().toLowerCase().indexOf(filterValue) < 0;
						} else {
							if (columnIndex == 7 || columnIndex == 8) {
								if (columnIndex == 7) {
									return $(this).find('td:eq(' + columnIndex + ') input[type="date"]').val().trim().toLowerCase().indexOf(filterValue) < 0;
								} else {
									return $(this).find('td:eq(' + columnIndex + ') input[type="time"]').val().trim().toLowerCase().indexOf(filterValue) < 0;
								}
							} else {
								if (columnIndex == 12 || columnIndex == 13 || columnIndex == 14) {
									return ($(this).find('td:eq(' + columnIndex + ') input[type="date"]').val().trim().toLowerCase() + ' ' +
										$(this).find('td:eq(' + columnIndex + ') input[type="time"]').val().trim().toLowerCase()).indexOf(filterValue) < 0;
								} else {
									if (columnIndex == 16) {
										return $(this).find('td:eq(' + columnIndex + ') textarea').val().trim().toLowerCase().indexOf(filterValue) < 0;
									} else {
										if (columnIndex == 26 || columnIndex == 27 || columnIndex == 29 || columnIndex == 30 || columnIndex == 31 || columnIndex == 32) {
											return $(this).find('td:eq(' + columnIndex + ') input[type="number"]').val().trim().toLowerCase().indexOf(filterValue) < 0;
										} else {
											return $(this).find("td").eq(columnIndex).text().trim().toLowerCase().indexOf(filterValue) < 0;
										}
									}
								}
							}
						}

					}).hide();
				}

				f++;
			});
		});
	</script>

	<!-- Funciones Principales -->
	<script type="text/javascript">
		function f_LoadResultados(_is_distribucionunidad, _cod_despacho, _cod_destino, _placa) {
			var _html = '';

			$.post("apis/backend.php", {
					accion: "get_SegundoTramo_GestionGuias_AgrupacionCriterios_Programacion",
					is_distribucionunidad: _is_distribucionunidad,
					codigo_despacho: _cod_despacho,
					cod_destino: _cod_destino,
					placa: _placa
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_detalle").html(data.html);

						// Carga lista de Fechas Iniciales
						f_LoadItemAgrupacion(1, data.iddistribucionunidad_inicio, data.iddestino_inicio, data.idmodalidadenvio_inicio, data.codigodespacho_inicio, data.fechaestimadadespacho_inicio, data.placa_inicio, data.idproveedorminero_inicio);
					}

					f_LoadingResumen(0);

				}, "json");
		};

		function f_ExportToExcel(_Ind) {
			// Obteniendo filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();

			// Obteniendo Ids visualizados
			var r = 1;
			var ids = '';

			$("#tbl_detalle tr:visible").filter(function() {
				ids += $("#id_" + r).val() + ',';

				r++;
			});

			ids = ids.substring(0, ids.length - 1);

			// Generar Excel
			if (_Ind == 1) {
				window.location.href = "export_to_excel/despachos_validaciondatos_1.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&ids=" + ids;
			}

			if (_Ind == 2) {
				window.location.href = "export_to_excel/despachos_validaciondatos_2.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&ids=" + ids;
			}
		}

		function f_LoadItemAgrupacion(_item, _id_distribucionunidad, _id_destino, _id_modalidadenvio, _codigo_despacho, _fechaestimada_despacho, _placa, _id_proveedorminero) {

			// Pinta selección
			f_AgrupacionSelected(_item);

			// Obteniendo filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_estadoguia = $("#filtro_estadoguia").val();
			var filtro_numguiaR = $("#filtro_numguiaR").val();
			var filtro_numguiaT = $("#filtro_numguiaT").val();
			var filtro_lote = $("#filtro_lote").val();
			var filtro_codigodespacho = $("#filtro_codigodespacho").val();

			// Setea las variables globales
			itemagrupacion_Selected = _item;
			iddistribucionunidad_selected = _id_distribucionunidad;
			iddestino_selected = _id_destino;
			idmodalidadenvio_selected = _id_modalidadenvio;
			codigodespacho_selected = _codigo_despacho;
			fechaestimadadespacho_selected = _fechaestimada_despacho;
			placa_selected = _placa;
			idproveedorminero_selected = _id_proveedorminero;

			// Carga las distribuciones
			f_LoadingListaDestinos(1);

			$("#tbl_listalotes").html('');
			$("#th_Chk").prop('checked', false);

			$.post("apis/backend.php", {
					accion: "get_SegundoTramo_GestionGuias_AgrupacionCriterios_ListaLotes",
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
					estado_guia: filtro_estadoguia,
					num_guiaR: filtro_numguiaR,
					num_guiaT: filtro_numguiaT,
					filtro_lote: filtro_lote,
					filtro_codigodespacho: filtro_codigodespacho,
					id_distribucionunidad: _id_distribucionunidad,
					id_destino: _id_destino,
					id_modalidadenvio: _id_modalidadenvio,
					codigo_despacho: _codigo_despacho,
					fechaestimada_despacho: _fechaestimada_despacho,
					placa: _placa,
					id_proveedorminero: _id_proveedorminero
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_listalotes").html(data.html);
					}

					f_LoadingListaDestinos(0);

				}, "json");
		}

		function f_VerifyCierreListo() {
			// verificando Tickets con Placa
			var d = 1;
			var is_placapendiente = 0;

			$("#tbl_detalle tr").each(function() {
				if ($("#id_distribucion_2_" + d).val() == '') {
					is_placapendiente = 1;
				}

				d++;
			});

			var id_modalidadenvio = $("#val_7_" + itemlote_Selected).val();
			var id_destino = $("#val_2_" + itemlote_Selected).val();
			var id_proveedorminero = $("#val_10_" + itemlote_Selected).val();

			if (is_placapendiente == 1 ||
				id_modalidadenvio.length == 0 ||
				id_destino.length == 0 ||
				id_proveedorminero.length == 0) {
				$("#td_cierre_1_" + itemlote_Selected).html('');
			} else {
				$("#td_cierre_1_" + itemlote_Selected).html('<input id="chk_cierre_' + itemlote_Selected + '" class="form-check-input chk_cierre" type="checkbox" style="transform: scale(1.5);">');
			}
		}

		function f_ShowAjusteCapacidad() {
			var is_visible = $("#chk_AjusteCapacidad").prop('checked');

			if (is_visible) {
				$("#guia_ajustecapacidad").show();
				$("#div_ajustecapacidad").show();

				$("#guia_ajustecapacidad").val(0);

				f_SetAjusteCapacidad();
			} else {
				$("#guia_ajustecapacidad").hide();
				$("#div_ajustecapacidad").hide();
			}
		}

		function f_SetAjusteCapacidad() {
			var capacidad_unidad = $("#guia_capacidadunidad").val();
			capacidad_unidad = ((capacidad_unidad.length > 0) ? parseFloat(capacidad_unidad) : 0);

			var ajuste_capacidad = $("#guia_ajustecapacidad").val();
			ajuste_capacidad = ((ajuste_capacidad.length > 0) ? parseFloat(ajuste_capacidad) : 0);

			$("#guia_ajustecapacidad_total").val(f_RedondearDecimales(capacidad_unidad + ajuste_capacidad, 2));
		}

		function f_AddGuia(_is_edit, fecha_inicio, _guiaremitente_serie, _guiaremitente_numero, _guiatransportista_serie, _guiatransportista_numero, _id_chofer, arr_distribuciones) {
			// Verificar las filas de la grilla
			if ($("#tbl_listalotes").find('tr').length == 0) {
				alert("Debe seleccionar al menos un registro.");

				return;
			}

			// Recorre la grilla buscando seleccionados
			if (_is_edit != 1) {
				var d = 1;
				var is_selected = 0;
				var arr_distribuciones = '';

				$("#tbl_listalotes tr").each(function() {
					if ($("#chk_lote_guias_" + d).prop('checked')) {
						arr_distribuciones += $("#id_lote_" + d).val() + ", ";

						is_selected = 1;
					}

					d++;
				});

				if (is_selected == 0) {
					alert("Debe seleccionar al menos un Lote.");

					return;
				} else {
					arr_distribuciones = arr_distribuciones.substring(0, arr_distribuciones.length - 2);
				}
			}

			// Seteando variables hidden
			$("#modograbar_guia").val(((_is_edit == 1) ? 'E' : 'N'));

			// Limpiando los datos
			$("#fecha_emision").val('<?php echo $g_date; ?>');
			$("#hora_emision").val('<?php echo substr($g_time, 0, 5); ?>');
			$("#guia_fechas").val('<?php echo $g_date; ?>');
			$("#guia_remitenteserie").val(((_is_edit == 1) ? _guiaremitente_serie : ''));
			$("#guia_remitentenumero").val(((_is_edit == 1) ? _guiaremitente_numero : ''));
			$("#guia_transportistaserie").val(((_is_edit == 1) ? _guiatransportista_serie : ''));
			$("#guia_transportistanumero").val(((_is_edit == 1) ? _guiatransportista_numero : ''));
			$("#chk_SinGRT").prop('checked', false);
			$("#guia_puntopartida").val('');
			$("#guia_puntodestino").val('');
			$("#guia_remitente").val('');
			$("#guia_destinatario").val('');
			$("#guia_transportista").val('');
			$("#guia_placa").val('');
			$("#guia_constanciamtc").val('');
			$("#guia_marcaunidad").val('');
			$("#guia_conductor").val('');
			$("#guia_motivotraslado").val('');
			$("#tbl_guialistalotes").val('');
			$("#guia_capacidadunidad").val('');
			$("#chk_AjusteCapacidad").prop('checked', false);
			$("#guia_ajustecapacidad").val('');
			$("#guia_capacidadunidad").val('');

			$(".info_placa2").hide();
			$("#guia_ajustecapacidad").hide();
			$("#div_ajustecapacidad").hide();

			$("#guia_conductor").trigger('change');
			$("#guia_transportista").trigger('change');
			$("#guia_placa").trigger('change');
			$("#guia_placa2").trigger('change');

			// Obtiene la información
			$.post("apis/backend.php", {
					accion: "get_SegundoTramo_GestionGuias_Datos",
					id_destino: iddestino_selected,
					id_modalidadenvio: idmodalidadenvio_selected,
					id_proveedorminero: idproveedorminero_selected,
					placa: placa_selected,
					arr_distribuciones: arr_distribuciones
				},
				function(data) {
					if (data.estado == 1) {
						$("#guia_puntopartida").val(data.punto_partida);
						$("#guia_puntodestino").val(data.punto_destino);
						$("#guia_remitente").val(data.remitente);
						$("#guia_destinatario").val(data.destinatario);
						$("#guia_transportista").val(data.transportista);
						$("#guia_placa").val($("#td_detalle_6_" + itemagrupacion_Selected).html().trim());
						$("#guia_constanciamtc").val(data.codigo_mtc_1);
						$("#guia_marcaunidad").val(data.marca_1);
						$("#guia_marcaunidad").trigger('change');
						$("#guia_capacidadunidad").val(data.capacidad);
						$("#tbl_guialistalotes").html(data.html);
						$("#guia_motivotraslado").val(data.motivo_traslado);

						// Obtener la fecha actual en formato yyyy-MM-dd
							if (_is_edit == 1) {
								const fechaActual = new Date().toISOString().slice(0, 10);

								$("#fecha_emision").val(data.fechahora_emision ? data.fechahora_emision.split(" ")[0] : fechaActual);
								$("#hora_emision").val(data.fechahora_emision ? data.fechahora_emision.split(" ")[1] : '<?php echo substr($g_time, 0, 5) ?>');
								$("#guia_fechas").val(data.fecha_guia || fechaActual);
							}

							if (_is_edit != 1) {
								$("#guia_conductor").val(data.chofer);
							} else {
								$("#guia_conductor").val(_id_chofer);
							}

						// Determina si tiene Placa 2
							if ($("#infolotes_placa2_1").html() != undefined) {
								if ($("#infolotes_placa2_1").html().trim().length > 0) {
									$(".info_placa2").show();
								}

								$("#guia_placa2").val($("#infolotes_placa2_1").html().trim());
								$("#guia_constanciamtc2").val(data.codigo_mtc_2);
								$("#guia_marcaunidad2").val(data.marca_2);
								$("#guia_marcaunidad2").trigger('change');
							}

						// Actualiza los Select2
							$("#guia_conductor").trigger('change');
							$("#guia_transportista").trigger('change');
							$("#guia_placa").trigger('change');
							$("#guia_placa2").trigger('change');
					}

				}, "json");

			// Abre modal
			f_OpenModal('modal_adminguias');
		};

		function f_ImpirmirGuias(_id_unuidad, _guiaserie_md5, _guianumero_md5, is_remitente, _id_modalidadenvio) {
			if (is_remitente == 1) {
				url = 'print_segundotramo_guiar.php?x=' + _id_unuidad + '&a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_modalidadenvio;
			} else {
				url = 'print_segundotramo_guiat.php?x=' + _id_unuidad + '&a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_modalidadenvio;
			}

			window.open(url, '_blank');
		}

		function f_DisabledGRT() {
			var is_selected = (($("#chk_SinGRT").prop('checked')) ? 1 : 0);

			$(".guia_GRT").val('');

			if (is_selected == 1) {
				$(".guia_GRT").prop('disabled', true);
			} else {
				$(".guia_GRT").prop('disabled', false);
			}
		}

		function f_Verificacion(_item, _guiaserie_md5, _guianumero_md5) {
			// Obteniendo los datos para el título
			var cod_lote = $("#td_codlote_" + _item).html().trim();
			var num_ticket = $("#td_numticket_" + _item).html().trim();
			var num_guiaR = $("#td_guiar_" + _item).html();
			var fecha_balanza = $("#filtro_destino").val();

			if (num_guiaR != undefined) {
				$("#verif_numguiar").val(num_guiaR.trim());
			} else {
				$("#verif_numguiar").val('--Pendiente--');
			}

			$("#verif_codlote").val(cod_lote);
			$("#verif_numticket").val(num_ticket.replace('<b>', '').replace('</b>', ''));
			$("#verif_fechabalanza").val(fecha_balanza);
			$("#tbl_verificaciondocumentos").html('');

			// Obtiene la información de documentos
			f_GetVerificacionDocumentos(_guiaserie_md5, _guianumero_md5, fecha_balanza);

			// Abre modal
			f_OpenModal('modal_verificacion');
		}

		function f_AddArchivo(_id_registro, _id_modalidadenvio, _id_destino, _id_proveedorminero, _placa, _fecha_balanza, _guiaserie_md5, _guianumero_md5) {
			// Abre el prompt para seleccionar el archivo
			var inputFile = $('<input type="file">');
			inputFile.on('change', function() {
				if (this.files.length > 0) {
					var selectedFile = this.files[0];

					// Envía el archivo al servidor con los parámetros mediante $.post
					var formData = new FormData();
					formData.append('accion', 'grabar_PrimerTramo_VerificacionDocumentos');
					formData.append('file', selectedFile);
					formData.append('id_registro', _id_registro);
					formData.append('id_modalidadenvio', _id_modalidadenvio);
					formData.append('id_destino', _id_destino);
					formData.append('id_proveedorminero', _id_proveedorminero);
					formData.append('placa', _placa.trim());
					formData.append('fecha_balanza', _fecha_balanza.trim());
					formData.append('guiaserie_md5', _guiaserie_md5);
					formData.append('guianumero_md5', _guianumero_md5);
					formData.append('num_guia', $("#verif_numguiar").val());

					$.ajax({
						url: 'apis/backend.php',
						type: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						success: function(response) {
							f_GetVerificacionDocumentos(_guiaserie_md5, _guianumero_md5, _fecha_balanza.trim());
						}
					});
				}
			});

			inputFile.click(); // Simula el clic en el input file
		}

		function f_GetVerificacionDocumentos(_guiaserie_md5, _guianumero_md5, _fecha_balanza) {
			$.post("apis/backend.php", {
					accion: "get_PrimerTramo_VerificacionDocumentos",
					guiaserie_md5: _guiaserie_md5,
					guianumero_md5: _guianumero_md5,
					fecha_balanza: _fecha_balanza,
					id_modalidadenvio: idmodalidadenvio_selected,
					id_destino: iddestino_selected,
					id_proveedorminero: idproveedorminero_selected,
					placa: placa_selected
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_verificaciondocumentos").html(data.html);
					}

				}, "json");
		}

		function f_LoadListaDestinos(_item, _id_destino, _id_modalidadenvio, _codigo_despacho, _fechaestimada_despacho, _placa) {
			// Pinta selección
			f_AgrupacionSelected(_item);

			// Obteniendo filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_estadoguia = $("#filtro_estadoguia").val();
			var filtro_numguiaR = $("#filtro_numguiaR").val();
			var filtro_numguiaT = $("#filtro_numguiaT").val();
			var filtro_lote = $("#filtro_lote").val();
			var filtro_codigodespacho = $("#filtro_codigodespacho").val();

			// Setea las variables globales
			itemagrupacion_Selected = _item;
			iddestino_selected = _id_destino;
			idmodalidadenvio_selected = _id_modalidadenvio;
			codigodespacho_selected = _codigo_despacho;
			fechaestimadadespacho_selected = _fechaestimada_despacho;
			placa_selected = _placa;

			// Carga Fechas
			f_LoadingListaDestinos(1);

			$("#filtro_destino").val('');

			$.post("apis/backend.php", {
					accion: "get_PrimerTramo_GestionGuias_AgrupacionCriterios_ListaFechasPesoInicial",
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
					estado_guia: filtro_estadoguia,
					num_guiaR: filtro_numguiaR,
					num_guiaT: filtro_numguiaT,
					filtro_lote: filtro_lote,
					id_modalidadenvio: _id_modalidadenvio,
					id_destino: _id_destino,
					id_proveedorminero: _id_proveedorminero,
					placa: _placa
				},
				function(data) {
					if (data.estado == 1) {
						$("#filtro_destino").html(data.html);

						// Carga por defecto el primer item
						// f_LoadItemAgrupacion(1, _id_modalidadenvio, _id_destino, _id_proveedorminero, _placa);
						f_LoadItemAgrupacion();
					}

					f_LoadingListaDestinos(0);

				}, "json");
		}

		function f_GetTotalPesoAjustado() {
			var d = 1;
			var total_ajustado = 0;
			var total_rows = $("#tbl_guialistalotes tr").length;

			while (d < total_rows) {
				if ($("#guialote_pesoajustado_" + d).val().length > 0) {
					total_ajustado += parseFloat($("#guialote_pesoajustado_" + d).val());
				}

				d++;
			}

			$("#guialote_totalpesoajustado").html(f_RedondearDecimales(total_ajustado, 2));
		}

		function f_GetPlacaInfo(_is_placa1) {
			var cod_placa = '';

			if (_is_placa1 == 1) {
				cod_placa = $("#guia_placa").val();
			} else {
				cod_placa = $("#guia_placa2").val();
			}

			$.post("apis/backend.php", {
					accion: "get_TransportistaxPlaca",
					cod_placa: cod_placa
				},
				function(data) {
					if (data.estado == 1) {
						if (_is_placa1 == 1) {
							$("#guia_transportista").val(data.id_transportista);
							$("#guia_transportista").trigger('change');

							$("#guia_constanciamtc").val(data.codigo_mtc);

							$("#guia_marcaunidad").val(data.id_marca);
							$("#guia_marcaunidad").trigger('change');

							// Seteando Segunda Placa
							$(".info_placa2").hide()

							if (data.tiene_carreta == 1) {
								$(".info_placa2").show();
							}
						} else {
							if (data.codigo_mtc_2) {
								$("#guia_constanciamtc2").val(data.codigo_mtc_2);
							}
							if (data.marca_2) {
								$("#guia_marcaunidad2").val(data.marca_2);
							}

							$("#guia_constanciamtc2").trigger('change')
							$("#guia_marcaunidad2").trigger('change');
						}
					}

				}, "json");
		}
	</script>

	<!-- Funciones Secundarias -->
	<script type="text/javascript">
		function f_LoadingResumen(_is_show) {
			if (_is_show == 1) {
				$("#wt_resumen").show();
			} else {
				$("#wt_resumen").hide();
			}
		}

		function f_LoadingListaDestinos(_is_show) {
			if (_is_show == 1) {
				$("#wt_listalotes").show();
			} else {
				$("#wt_listalotes").hide();
			}
		}

		function f_SavingDatos(_is_show) {
			if (_is_show == 1) {
				$("#wt_saving").show();
			} else {
				$("#wt_saving").hide();
			}
		}

		function f_SavingDistribucion(_is_show) {
			if (_is_show == 1) {
				$("#wt_savingDistribucion").show();
			} else {
				$("#wt_savingDistribucion").hide();
			}
		}

		$('.color-box').click(function() {
			$('.color-box').css('border-color', '#D9D9D9'); // Resetear todos los bordes a blanco
			$(this).css('border-color', '#8D8D84'); // Establecer el borde del color seleccionado
			$(this).css('border-width', '3px');

			color_selected = $(this).data('color');
		});

		function f_SelectChk() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var d = 1;

			$("#tbl_detalle tr").each(function() {
				$("#chk_lote_" + d).prop('checked', is_checked);

				d++;
			});
		}

		function f_SelectChk_Guias() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk_Guias").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var d = 1;

			$("#tbl_listalotes tr").each(function() {
				$("#chk_lote_guias_" + d).prop('checked', is_checked);

				d++;
			});
		}

		function f_AgrupacionSelected(_item) {
			var i = 1;

			// Recorre los Tr de la tabla y los limpia
			$(".bg_selected").css('background-color', '#ffffff');
			$(".cs_imgselect").hide();

			// Seteando item seleccionado
			$(".bg_selected_" + _item).css('background-color', '#FFF587');

			$("#img_select_" + _item).show();
		}
	</script>

	<!-- Funciones de Grabación -->
	<script type="text/javascript">
		function f_UpdateDatos(_item, _orden_campo) {
			// Obtiene Id
			var _cod_lote = $("#id_" + _item).val();

			// Obtiene Valor
			var _valor = $("#val_" + _orden_campo + '_' + _item).val();

			// Complementa la fecha y hora de definición de Destino
			if (_orden_campo == 3 || _orden_campo == 4) {
				_valor = $("#val_3_" + _item).val() + ' ' + $("#val_4_" + _item).val();
			}

			// Complementa la fecha y hora de muestreo para Las Lomas
			if (_orden_campo == 17 || _orden_campo == 18) {
				_valor = $("#val_17_" + _item).val() + ' ' + $("#val_18_" + _item).val();
			}

			// Complementa la fecha y hora de muestreo para Solandra
			if (_orden_campo == 19 || _orden_campo == 20) {
				_valor = $("#val_19_" + _item).val() + ' ' + $("#val_20_" + _item).val();
			}

			// Complementa la fecha y hora de muestreo para Paltarumi
			if (_orden_campo == 21 || _orden_campo == 22) {
				_valor = $("#val_21_" + _item).val() + ' ' + $("#val_22_" + _item).val();
			}

			f_SavingDatos(1);

			// Grabando Datos
			$.post("apis/backend.php", {
					accion: "update_PrimerTramo_ValidacionDatos_new",
					cod_lote: _cod_lote,
					orden_campo: _orden_campo,
					valor: _valor
				},
				function(data) {
					if (data.estado == 1) {
						if (_orden_campo == 2) {
							var fechahora_registro = data.destino_fechahoraregistro;

							// Seteando Fecha y Hora
							if (_valor.length == 0) {
								$("#val_3_" + _item).val('');
								$("#val_4_" + _item).val('');
								$("#val_5_" + _item).html('');
							} else {
								$("#val_3_" + _item).val(fechahora_registro.substring(0, 10));
								$("#val_4_" + _item).val(fechahora_registro.substring(11).substring(0, 5));
								$("#val_5_" + _item).html('0.0');
							}
						}

						if (_orden_campo == 3 || _orden_campo == 4) {
							$("#val_5_" + _item).html(data.destino_totaldiasdefiniciondestino);
						}

						if (_orden_campo == 10) {
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
					} else {
						alert("Ocurrió un error al momento de grabar los datos de ingreso.");

						f_SavingDatos(0);

						return;
					}

					f_SavingDatos(0);

				}, "json");
		}

		function f_EditDistribucion(_orden_campo, _item) {
			// Obtiene Id
			var _id_registro = $("#id_distribucion_" + _item).val();

			// Obtiene Valor
			var _valor = $("#id_distribucion_" + _orden_campo + "_" + _item).val();

			// Validando datos
			if (_orden_campo == 1) {
				// Muestra u oculta la Placa 2
				if (_valor.split('|')[1] == 1) {
					$("#td_distribucion_3_" + _item).show();
				} else {
					$("#td_distribucion_3_" + _item).hide();

					$("#id_distribucion_3_" + _item).val('');
					$("#id_distribucion_3_" + _item).trigger('change');
				}
			}

			if (_orden_campo == 2) {
				// Determina si tiene Carreta
				var tiene_carreta = $("#id_distribucion_1_" + _item).val().split('|')[1];

				// Establece la Capacidad
				if (tiene_carreta == 0) {
					var capacidad = _valor.split('|')[1];

					if (capacidad != undefined) {
						capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
					} else {
						capacidad = 0;
					}

					$("#id_distribucion_4_" + _item).val(capacidad);
				}
			}

			if (_orden_campo == 3) {
				var capacidad = _valor.split('|')[1];

				if (capacidad != undefined) {
					capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
				} else {
					capacidad = 0;
				}

				$("#id_distribucion_4_" + _item).val(capacidad);
			}

			f_SavingDistribucion(1);

			// Grabando Datos
			$.post("apis/backend.php", {
					accion: "update_PrimerTramo_DistribucionDatos",
					id_registro: _id_registro,
					orden_campo: _orden_campo,
					valor: _valor
				},
				function(data) {
					if (data.estado == 1) {
						if (_orden_campo == 7) {
							$("#td_fechainicial_" + itemlote_Selected).html(_valor);
						}

						if (_orden_campo == 10 || _orden_campo == 11) {
							var peso_tara = $("#id_distribucion_10_" + _item).val();
							var peso_neto = $("#id_distribucion_11_" + _item).val();

							$("#id_distribucion_9_" + _item).val(f_RedondearDecimales(parseFloat(peso_tara) + parseFloat(peso_neto), 2));

							if (_orden_campo == 11) {
								f_GetTotalDistribuido();
							}
						}

						// Verificando si el registro está listo para el Cierre
						f_VerifyCierreListo();
					} else {
						alert("Ocurrió un error al momento de actualizar el dato.");
					}

					f_SavingDistribucion(0);

				}, "json");
		}

		function f_GrabarCierre() {
			var arr_pos = '';
			var arr_ids = '';
			var arr_idvalidacion = '';

			// Recorre las filas visibles
			$("#tbl_detalle tr:visible").filter(function() {
				tr_id = $(this).attr('id').substring(11);

				if ($("#chk_cierre_" + tr_id).prop('checked')) {
					arr_idvalidacion += $("#id_" + tr_id).val() + ', ';

					arr_pos += tr_id + '|';
					arr_ids += $("#id_" + tr_id).val() + '|';
				}
			});

			// Valida la selección de checkbox
			if (arr_idvalidacion.length == 0) {
				alert("Debe seleccionar al menos un Lote");

				return;
			} else {
				arr_idvalidacion = arr_idvalidacion.substring(0, arr_idvalidacion.length - 2);
				arr_pos = arr_pos.substring(0, arr_pos.length - 1);
				arr_ids = arr_ids.substring(0, arr_ids.length - 1);

				$.post("apis/backend.php", {
						accion: "cierre_PrimerTramo_Validacion",
						arr_idvalidacion: arr_idvalidacion
					},
					function(data) {
						if (data.estado == 1) {
							// Setea tr cerrados
							var t = 0;

							arr_pos = arr_pos.split('|');
							arr_ids = arr_ids.split('|');

							while (t < arr_pos.length) {
								$("#td_cierre_1_" + arr_pos[t]).html('<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_Reabrir(' + arr_pos[t] + ', ' + arr_ids[t] + ')"><u> Reabrir </u></label>');
								$("#td_cierre_2_" + arr_pos[t]).html(data.cerrado_fechahoraregistro);
								$("#td_cierre_3_" + arr_pos[t]).html(data.cerrado_usuarioregistro);

								// Actualiza el combo de Estados de Lote solo para Estados que no sean: "RETIRADO, NO COMERCIAL"
								if ($("#val_6_" + arr_pos[t]).val() != 5 && $("#val_6_" + arr_pos[t]).val() != 6) {
									$("#val_6_" + arr_pos[t]).val(4);
									$("#val_6_" + arr_pos[t]).trigger('change');
								}

								t++;
							}
						} else {
							alert("Ocurrió un error al momento de realizar el cierre.");
						}

					}, "json");
			}
		}

		function f_ConfirmarCierre() {
			// Verificar las filas de la grilla
			if ($("#tbl_detalle").find('tr').length == 0) {
				alert("Debe seleccionar al menos un Lote.");

				return;
			}

			// Recorre la grilla buscando seleccionados
			var d = 1;
			var is_selected = 0;
			var arr_lotes = '';

			$("#tbl_detalle tr").each(function() {
				if ($("#chk_cierre_" + d).prop('checked')) {
					arr_lotes += "'" + $("#id_" + d).val() + "', ";

					is_selected = 1;
				}

				d++;
			});

			if (is_selected == 0) {
				alert("Debe seleccionar al menos un Lote.");

				return;
			} else {
				arr_lotes = arr_lotes.substring(0, arr_lotes.length - 2);
			}

			// Cerrando Lotes
			$.post("apis/backend.php", {
					accion: "cierre_PrimerTramo_ValidacionDistribucion",
					arr_lotes: arr_lotes
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadResultados();
					} else {
						alert("Ocurrió un error al momento de Crear el Nuevo registro.");

						return;
					}

				}, "json");
		}

		function f_ConfirmarGuia() {
			var modograbar_guia = $("#modograbar_guia").val();
			// var guia_fechas = $("#guia_fechas").val();
			var guia_remitenteserie = $("#guia_remitenteserie").val();
			var guia_remitentenumero = $("#guia_remitentenumero").val();
			var guia_transportistaserie = $("#guia_transportistaserie").val();
			var guia_transportistanumero = $("#guia_transportistanumero").val();
			var sin_GRT = (($("#chk_SinGRT").prop('checked')) ? 1 : 0);
			var guia_puntopartida = $("#guia_puntopartida").val();
			var guia_puntodestino = $("#guia_puntodestino").val();

			var guia_remitente = $("#guia_remitente").val();
			var guiaremitente_ruc = guia_remitente.split(' - ')[0];
			var guiaremitente_razonsocial = guia_remitente.split(' - ')[1];

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

			// obteniendo valor de las fechas
				var fecha_emision = $("#fecha_emision").val();
				var hora_emision = $("#hora_emision").val();
				var guia_fechas = $("#guia_fechas").val();

			// Validando datos
				if (fecha_emision == null) {
					alert("Debe registrar la Fecha de Emisión.");

					return;
				}
				if (fecha_emision.length == 0) {
					alert("Debe registrar la Fecha de Emisión.");

					return;
				}

				if (hora_emision == null) {
					alert("Debe registrar la Hora de Emisión.");

					return;
				}
				if (hora_emision.length == 0) {
					alert("Debe registrar la Hora de Emisión.");

					return;
				}

				if (guia_fechas == null) {
					alert("Debe registrar la Fecha de las Guías.");

					return;
				}
				if (guia_fechas.length == 0) {
					alert("Debe registrar la Fecha de las Guías.");

					return;
				}

				if (guia_remitenteserie == null) {
					alert("Debe registrar la Serie de la Guía del Remitente.");

					return;
				}
				if (guia_remitenteserie.length == 0) {
					alert("Debe registrar la Serie de la Guía del Remitente.");

					return;
				}

				if (guia_remitentenumero == null) {
					alert("Debe registrar el Número de Guía del Remitente.");

					return;
				}
				if (guia_remitentenumero.length == 0) {
					alert("Debe registrar el Número de Guía del Remitente.");

					return;
				}

				if (guia_puntopartida == null) {
					alert("El Punto de Partida no ha sido configurado correctamente, por favor verificar.");

					return;
				}
				if (guia_puntopartida.length == 0) {
					alert("El Punto de Partida no ha sido configurado correctamente, por favor verificar.");

					return;
				}

				if (guia_puntodestino == null) {
					alert("El Punto de Destino no ha sido configurado correctamente, por favor verificar.");

					return;
				}
				if (guia_puntodestino.length == 0) {
					alert("El Punto de Destino no ha sido configurado correctamente, por favor verificar.");

					return;
				}

				if (guia_destinatario == null) {
					alert("El Destinatario no ha sido configurado correctamente, por favor verificar.");

					return;
				}
				if (guia_destinatario.length == 0) {
					alert("El Destinatario no ha sido configurado correctamente, por favor verificar.");

					return;
				}

				if (guia_transportista == null) {
					alert("La Empresa de Transporte no ha sido configurada correctamente, por favor verificar.");

					return;
				}
				if (guia_transportista.length == 0) {
					alert("La Empresa de Transporte no ha sido configurada correctamente, por favor verificar.");

					return;
				}

				if (guia_constanciamtc == null) {
					alert("Debe registrar el N° de Constancia MTC de la Placa 1.");

					return;
				}
				if (guia_constanciamtc.length == 0) {
					alert("Debe registrar el N° de Constancia MTC de la Placa 1.");

					return;
				}

				if (guia_marcaunidad == null) {
					alert("Debe registrar la Marca de la Placa 1.");

					return;
				}
				if (guia_marcaunidad.length == 0) {
					alert("Debe registrar la Marca de la Placa 1.");

					return;
				}

			// Determinando si la segunda placa es visible
				if ($(".info_placa2:first").is(":visible")) {
					if (guia_constanciamtc2 == null) {
						alert("Debe registrar el N° de Constancia MTC de la Placa 2.");

						return;
					}
					if (guia_constanciamtc2.length == 0) {
						alert("Debe registrar el N° de Constancia MTC de la Placa 2.");

						return;
					}

					if (guia_marcaunidad2 == null) {
						alert("Debe registrar la Marca de la Placa 2.");

						return;
					}
					if (guia_marcaunidad2.length == 0) {
						alert("Debe registrar la Marca de la Placa 2.");

						return;
					}
				} else {
					guia_placa2 = '';
					guia_constanciamtc2 = '';
					guia_marcaunidad2 = '';
				}

				if (guia_conductor == null) {
					alert("Debe seleccionar el Conductor.");

					return;
				}
				if (guia_conductor.length == 0) {
					alert("Debe seleccionar el Conductor.");

					return;
				}

				if (guia_motivotraslado == null) {
					alert("El Motivo de Traslado no ha sido configurado correctamente, por favor verificar.");

					return;
				}
				if (guia_motivotraslado.length == 0) {
					alert("El Motivo de Traslado no ha sido configurado correctamente, por favor verificar.");

					return;
				}

			// Validando la Capacidad de la Placa 1 / PLaca 2
				if ($(".info_placa2:first").is(":visible")) {
					if (guia_capacidadunidad == null) {
						alert("Debe registrar la Capacidad de la Unidad 2.");

						return;
					}
					if (guia_capacidadunidad.length == 0) {
						alert("Debe registrar la Capacidad de la Unidad 2.");

						return;
					}
					if (guia_capacidadunidad <= 0) {
						alert("La Capacidad ingresada es incorrecta.");

						return;
					}
				} else {
					if (guia_capacidadunidad == null) {
						alert("Debe registrar la Capacidad de la Unidad 1.");

						return;
					}
					if (guia_capacidadunidad.length == 0) {
						alert("Debe registrar la Capacidad de la Unidad 1.");

						return;
					}
					if (guia_capacidadunidad <= 0) {
						alert("La Capacidad ingresada es incorrecta.");

						return;
					}
				}

			// Validando el Ajuste de Capacidad
				if ($("#chk_AjusteCapacidad").prop('checked')) {
					if (guia_ajustecapacidad == null) {
						alert("No ha ingresado el Ajuste de Capacidad.");

						return;
					}
					if (guia_ajustecapacidad.length == 0) {
						alert("No ha ingresado el Ajuste de Capacidad.");

						return;
					}
					if (guia_ajustecapacidad <= 0) {
						alert("El Ajuste de Capacidad ingresado es incorrecto.");

						return;
					}
				} else {
					guia_ajustecapacidad = '';
				}

			// Valida el registro de Pesos Ajustados
				var d = 1;
				var total_rows = $("#tbl_guialistalotes tr").length;

				while (d < total_rows) {
					if ($("#guialote_pesoajustado_" + d).val().trim() == 0) {
						alert("Debe ingresar el Peso Ajustado para:\n   - Lote: " + $("#guialote_lote_" + d).html().trim() + "\n   - Ticket: " + $("#guialote_ticket_" + d).html().trim().replace('<b>', '').replace('</b>', ''));

						return;
					}

					d++;
				}

			// Valida si no se generará Guía de Transportista
				if (sin_GRT == 1) {
					if (!confirm("¿Está seguro de Emitir la Guía del Remitente sin Guía del Transportista?")) {
						return;
					}

					guia_transportistaserie = '';
					guia_transportistanumero = '';
				} else {
					if (guia_transportistaserie == null) {
						alert("Debe registrar la Serie de la Guía del Transportista.");

						return;
					}
					if (guia_transportistaserie.length == 0) {
						alert("Debe registrar la Serie de la Guía del Transportista.");

						return;
					}

					if (guia_transportistanumero == null) {
						alert("Debe registrar el Número de Guía del Transportista.");

						return;
					}
					if (guia_transportistanumero.length == 0) {
						alert("Debe registrar el Número de Guía del Transportista.");

						return;
					}
				}

			// Valida que el Peso Distribuido no exceda la Capacidad del Vehículo
				var total_distribuido = parseFloat($("#guialote_totalpesoajustado").html().trim());
				var capacidad_unidad = parseFloat((($("#chk_AjusteCapacidad").prop('checked')) ? $("#guia_ajustecapacidad_total").val() : $("#guia_capacidadunidad").val()));

				if (total_distribuido > capacidad_unidad) {
					alert("El Peso Total es mayor a la capacidad de la unidad.\nPor favor, verificar.");

					return;
				}

			// Obtiene el detalle de Lotes
				var d = 1;
				var arr_infolotes = '';

				while (d < total_rows) {
					arr_infolotes += $("#id_guialote_" + d).val() + ';' + $("#guialote_descripcionbien_" + d).val() + ';' + $("#guialote_descripcionbien_" + d + ' option:selected').text() + ';' + parseFloat($("#guialote_pesoajustado_" + d).val()) + '|';

					d++;
				}

				arr_infolotes = arr_infolotes.substring(0, arr_infolotes.length - 1);

			// Grabando datos
				f_LoadingConfirmarGuia(1);

				$.post("apis/backend.php", {
						accion: "grabar_SegundoTramo_GestionGuias",
						modograbar_guia: modograbar_guia,
						guia_fechas: guia_fechas,
						fechahora_emision: `${fecha_emision } ${hora_emision}`,
						guia_remitenteserie: guia_remitenteserie,
						guia_remitentenumero: guia_remitentenumero,
						guia_transportistaserie: guia_transportistaserie,
						guia_transportistanumero: guia_transportistanumero,
						guias_remitenteruc: guiaremitente_ruc,
						guias_remitenterazonsocial: guiaremitente_razonsocial,
						guia_puntopartida: guia_puntopartida,
						guia_puntodestino: guia_puntodestino,
						guia_destinatario: guia_destinatario,
						guia_placa: guia_placa,
						guia_constanciamtc: guia_constanciamtc,
						guia_marcaunidad: guia_marcaunidad,
						guia_placa2: guia_placa2,
						guia_constanciamtc2: guia_constanciamtc2,
						guia_marcaunidad2: guia_marcaunidad2,
						guia_conductor: guia_conductor,
						guia_motivotraslado: guia_motivotraslado,
						guia_capacidadunidad: guia_capacidadunidad,
						guia_ajustecapacidad: guia_ajustecapacidad,
						arr_infolotes: arr_infolotes,
						id_destino: iddestino_selected,
						id_modalidadenvio: idmodalidadenvio_selected,
						guia_transportista: guia_transportista
					},
					function(data) {
						if (data.estado == 1) {
							// Imprimir Guías
							url = 'print_segundotramo_guiar.php?x=' + data.id_distribucionunidad + '&a=' + data.gr_serie + '&b=' + data.gr_numero + '&c=' + idmodalidadenvio_selected;
							window.open(url, '_blank');

							if (sin_GRT == 0) {
								url = 'print_segundotramo_guiat.php?x=' + data.id_distribucionunidad + '&a=' + data.gt_serie + '&b=' + data.gt_numero + '&c=' + idmodalidadenvio_selected;
								window.open(url, '_blank');
							}

							f_LoadItemAgrupacion(itemagrupacion_Selected, iddistribucionunidad_selected, iddestino_selected, idmodalidadenvio_selected, codigodespacho_selected, fechaestimadadespacho_selected, placa_selected, idproveedorminero_selected);
						} else {
							alert("Ocurrió un error al momento de confirmar las guías.");
						}

						// Cierra modal
						f_cerrarModal('modal_adminguias');

						f_LoadingConfirmarGuia(0);

					}, "json");
		}

		function f_EliminarGuia(_id_unidad, _numguia_serie, _numguia_numero) {
			if (!confirm("¿Está seguro de eliminar la guía seleccionada?\n\n   - Guía Remitente: " + _numguia_serie + '-' + _numguia_numero + "\n\nSi continua se eliminará también la guía del transportista.")) {
				return;
			}

			// Grabando datos
			$.post("apis/backend.php", {
					accion: "eliminar_SegundoTramo_Guias",
					id_unidad: _id_unidad,
					numguia_serie: _numguia_serie,
					numguia_numero: _numguia_numero
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemAgrupacion(itemagrupacion_Selected, iddistribucionunidad_selected, iddestino_selected, idmodalidadenvio_selected, codigodespacho_selected, fechaestimadadespacho_selected, placa_selected, idproveedorminero_selected);
					} else {
						alert("Ocurrió un error al momento de eliminar la guía.");
					}
				});
		}
	</script>
	<!-- Funcion Default -->
	<script type="text/javascript">

	</script>
</body>

</html>