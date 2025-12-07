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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

	<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

	<!-- JSColor -->
	<script src="libs/jscolor/jscolor.js"></script>

	<title><?php echo $nom_app; ?> | Validación y Distribución de Datos - 1er Tramo</title>

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
		.sticky {
			position: sticky;
			left: 0;
			z-index: 50;
		}

		.sticky-2 {
			position: sticky;
			left: 35;
			z-index: 50;
		}

		.sticky-3 {
			position: sticky;
			left: 58;
			z-index: 50;
		}

		.sticky-4 {
			position: sticky;
			left: 58;
			z-index: 50;
		}

		.sticky-5 {
			position: sticky;
			left: 188;
			z-index: 50;
		}

		.sticky-2h {
			position: sticky;
			left: 58;
			z-index: 1000;
		}

		.sticky-3h {
			position: sticky;
			left: 58;
			z-index: 1000;
		}

		.sticky-4h {
			position: sticky;
			left: 188;
			z-index: 1000;
		}

		/* Estilo para Cabeceras estáticas */
		.sticky-1Cx {
			position: sticky;
			top: 0;
			z-index: 500;
		}

		.sticky-2Cxa {
			position: sticky;
			top: 0;
			z-index: 500;
		}

		.sticky-2Cxc {
			position: sticky;
			top: 95;
			z-index: 500;
		}

		.sticky-1C {
			position: sticky;
			top: 0;
		}

		.sticky-2Ca {
			position: sticky;
			top: 0;
			z-index: 100;
		}

		.sticky-2Cb {
			position: sticky;
			top: 33;
			z-index: 100;
		}

		.sticky-2Cc {
			position: sticky;
			top: 95;
			z-index: 100;
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
        <div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important;   z-index: 1060 !important;">
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
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Fecha de Ingreso a Balanza</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
										</div>

										<div class="row" >
											<div class="col-md-12 col-sm-12 col-xs-12">
												<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
											</div>
											<br><br>
											<div class="col-md-12 col-sm-12 col-xs-12">
												<input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
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
											<hr style="border-color: #D9D9D9;" />
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_estadolote" class="form-select" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_estadolote = "SELECT Id,
	                              								descripcion
								                           FROM tbconfig_estadoslote
								                          WHERE estado = 'A'";

												if ($res_estadolote = mysqli_query($enlace, $q_estadolote)) {
													if (mysqli_num_rows($res_estadolote) > 0) {
														while ($row_estadolote = mysqli_fetch_array($res_estadolote)) {
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
											<hr style="border-color: #D9D9D9;" />
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_planta" class="form-select" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_planta = "SELECT Id,
	                          								descripcion
						                           FROM tbconfig_plantas
						                          WHERE estado = 'A'";

												if ($res_planta = mysqli_query($enlace, $q_planta)) {
													if (mysqli_num_rows($res_planta) > 0) {
														while ($row_planta = mysqli_fetch_array($res_planta)) {
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
						<div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="col-md-8 col-sm-8 col-xs-12">
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

										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="d-flex justify-content-end">
												<button class="btn btn-danger" type="button" onclick="f_ConfirmarCierre();" style="min-width: 150px; color: #ffffff; height: 40px; font-size: 14px; margin-top: -5px; margin-bottom: 5px;">
													<b> Confirmar Cierre</b>
												</button>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
									<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 450px; margin-bottom: 20px;">
										<table class="table table-bordered table-hover">
											<thead>
												<tr style="font-size: 12px;">
													<th class="sticky sticky-1Cx" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 58px;">
														N°
													</th>

													<th class="sticky-2h sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 105px;" hidden>
														Ticket Balanza
													</th>

													<th class="sticky-3h sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
														Lote
													</th>

													<!-- <th class="sticky-4h sticky-2Cxa" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
														Responsable Recepción Lote
													</th> -->

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
														Fecha Ingreso a Balanza<br>(Real)
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Días transcurridos Totales
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81;">
														Peso Neto<br>(Tn)
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81;">
														% Humedad
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81;">
														Peso Seco (TMS)
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #02A676;">
														TMH<br>Distribuído
													</th>

													<th class="sticky-2Ca" colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Definición Destino
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
														Estado de Lote
													</th>

													<th class="sticky-2Ca" colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
														Información Muestreo
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
														Encargado Muestra
													</th>

													<th class="sticky-2Ca" colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Proveedor Minero
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
														Tipo Material
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
														Presentación Lote
													</th>

													<th class="sticky-2Ca" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
														Observación
													</th>

													<th class="sticky-2Cxa" colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
														Cierre
													</th>
												</tr>

												<tr style="font-size: 12px;">
													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
														Destino
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
														Modalidad Envío
													</th>

													<th class="sticky-2Cb" colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Fecha Hora Definición
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Días transcurridos Definición
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 450px;">
														Razón Social / Nombres
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
														Concesión
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
														Código Único
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
														Procedencia
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
														Sel.<br>
														<input id="th_Chk" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.5);" onchange="f_SelectChkCierre();">
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Fecha Hora
													</th>

													<th class="sticky-2Cb" style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
														Usuario
													</th>
												</tr>

												<!-- <tr style="font-size: 12px;">
													<th class="sticky-2h sticky-2Cxc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
														<input id="fil_2" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-3h sticky-2Cxc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_3" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-4h sticky-2Cxc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_4" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_5" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_6" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81;">
														<input id="fil_7" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81;">
														<input id="fil_8" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #026E81;">
														<input id="fil_9" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #02A676;">
														<input id="fil_10" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_11" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_12" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
														<input id="fil_13" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
														<input id="fil_14" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
														<input id="fil_15" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
														<input id="fil_16" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_17" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_18" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_19" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_20" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_21" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_22" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_23" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_24" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_25" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_26" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_27" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th class="sticky-2Cc" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_28" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th> -->
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
												<h5>Distribución para Lote: </h5>
												<h5 id="lbl_titulolote" style="margin-left: 5px; color: #337ab7;"></h5>
												<h5 style="margin-left: 5px;" hidden> | Ticket: </h5>
												<h5 id="lbl_tituloticket" style="margin-left: 5px; color: #337ab7;" hidden></h5>

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
												<button id="btn_AddDistribucion" type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AddDistribucion();">+ Nueva Distribución</button>
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
											<tr style="font-size: 14px;">
												<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px; border-top-left-radius: 15px;">
													Item<br>Ticket
												</th>

												<th colspan="9" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Información Unidad Ingreso
												</th>

												<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px; border-top-right-radius: 15px;">
													Información de Pesos
												</th>
											</tr>

											<tr style="font-size: 14px;">
												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
													Tipo Vehículo
												</th>

												<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
													Información Placa 1
												</th>

												<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
													Información Placa 2
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
													Fecha<br>Peso Inicial
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
													Fecha<br>Peso Final
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Bruto<br>(Tn)
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Tara<br>(Tn)
												</th>

												<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
													Neto<br>(Tn)
												</th>
											</tr>

											<tr style="font-size: 14px;">
												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
													Placa 1
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Capacidad<br>(Tn)
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Tara<br>(Tn)
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
													Marca
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
													Placa 2
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Capacidad<br>(Tn)
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
													Tara<br>(Tn)
												</th>

												<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
													Marca
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
		<div class="modal-dialog modal-lg">
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

													<option value="<?php echo $row_lista["Id"] ?>"><?php echo $row_lista["documento"] . ' - ' . $row_lista["razon_social"] ?>"</option>

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
									<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
										Información Lotes
									</th>
								</tr>

								<tr style="font-size: 12px;">
									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px; max-width: 60px;">
										Sel.<br>
										<input id="th_Chk" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.2);" onchange="f_SelectChkLotes();">
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 90px;">
										Lote
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Encargado Muestra
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Proveedor Minero
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Modalidad Envío
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Neto<br>(TMH)
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
										Neto<br>(TMS)
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
						<div class="col-md-12 col-sm-12 col-xs-12">
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

						<div class="col-md-12 col-sm-12 col-xs-12" style="height: 350px; margin-top: 10px; overflow-y: scroll;">
							<div class="d-flex" style="padding: 3px; margin-top: -5px;">
								<table class="table table-bordered table-hover">
									<thead>
										<tr style="font-size: 12px;">
											<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
												Información Lotes
											</th>
										</tr>

										<tr style="font-size: 12px;">
											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 60px; min-width: 60px; max-width: 60px;">
												Sel.<br>
												<input id="th_Chk_Distribucion" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.2);" onchange="f_SelectChkLotes_Distribucion();">
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 90px;">
												Lote
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
												Proveedor Minero
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
												Modalidad Envío
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
												TMH<br>(Por distribuir)
											</th>

											<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 110px; min-width: 110px; max-width: 110px;">
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

	<div class="modal fade" id="modal_SetColor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_SetColorLabel" aria-hidden="true" style="z-index: 4000;">
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

				<input id="hd_SetColorLote" type="hidden">
				<input id="hd_SetColorItem" type="hidden">

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
			$("#nv_titulo").html('| Validación y Distribución de Datos - 1er Tramo');

			// Carga el detalle de información
			f_LoadResultados();
		}
	</script>

	<!-- Seteando objetos Select2 -->
	<script type="text/javascript">
		function f_SetSelect2() {
			$('.select_datos').select2({
				theme: "bootstrap-5",
				width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
				placeholder: $(this).data('placeholder'),
				allowClear: true
			}).on('select2:open', function() {
				$('body').css('zoom', '100%'); 
			}).on('select2:close', function() {
			    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
			});

			$('#filtro_lote').select2({
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
		function f_LoadResultados() {
			var _html = '';

			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_lote = $("#filtro_lote").val();
			var filtro_estadolote = $("#filtro_estadolote").val();
			var filtro_planta = $("#filtro_planta").val();
			var filtro_estadocierre = $("#filtro_estadocierre").val();

			f_LoadingResumen(1);

			$("#tbl_detalle").html('');
			$("#tbl_distribucion").html('');
			$("#lbl_titulolote").html('');
			$("#lbl_tituloticket").html('');

			$.post("apis/backend.php", {
					accion: "get_PrimerTramo_ValidacionDistribucionDatos",
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
					filtro_lote: filtro_lote,
					filtro_estadolote: filtro_estadolote,
					filtro_planta: filtro_planta,
					filtro_estadocierre: filtro_estadocierre
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_detalle").html(data.html);

						f_SetInputDisabled();

						// Carga por defecto el primer item
						f_LoadItemLote(1, data.cod_lote);
					}

					// Seteando Select2
					f_SetSelect2();

					// Se posisiona en el div de Detalle
					var divDetalle = document.getElementById("div_detalle");

					if (divDetalle) {
						divDetalle.scrollIntoView({
							behavior: "smooth"
						});
					}

					// Verificando si el registro está listo para el Cierre
					f_VerifyCierreListo();

					f_LoadingResumen(0);

				}, "json");
		};

		function f_SetColor(_item, _lote) {
			// Setea título
			$("#modal_SetColorLabel").html(_lote);
			$("#hd_SetColorLote").val(_lote);
			$("#hd_SetColorItem").val(_item);

			// Limpia la variable global
			color_selected = '';

			f_OpenModal('modal_SetColor');
		}

		function f_SetInputDisabled() {
			var cierre = '';

			// Recorre las todas filas
			$("#tbl_detalle tr").filter(function() {
				tr_id = $(this).attr('id').substring(11);

				// Identifica el valor del cierre
				cierre = $("#td_cierre_1_" + tr_id).html();

				if (cierre.toLowerCase().includes('reabrir')) {
					$(".input_datos_" + tr_id).prop('disabled', true);
				} else {
					$(".input_datos_" + tr_id).prop('disabled', false);
				}
			});
		}

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

		function f_LoadItemLote(_item, _cod_lote) {
			// Pinta selección
			f_LoteSelected(_item);

			// Si tiene Cierre
			$("#btn_AddDistribucion").show();

			if ($("#td_iscerrado_" + _item).val() == 1) {
				$("#btn_AddDistribucion").hide();
			}

			// Carga las distribuciones
			f_LoadingListaLotes(1);

			$("#tbl_distribucion").html('');

			$.post("apis/backend.php", {
					accion: "get_PrimerTramo_ValidacionDistribucionDatos_Distribucion",
					cod_lote: _cod_lote
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_distribucion").html(data.html);

						// Seteando el Total Distribuído
						f_GetTotalDistribuido();

						// Verificando si el registro está listo para el Cierre
						f_VerifyCierreListo();

						// Verificando los Lotes cerrados
						f_VerifyLoteCerrado();
					}

					f_LoadingListaLotes(0);

				}, "json");

			// Setea las variables globales
			itemlote_Selected = _item;
			codlote_Selected = _cod_lote;
		}

		function f_GetTotalDistribuido() {
			var d = 1;
			var total_distribuido = 0;

			// Recorre las Filas de lotes distribuídos
			$("#tbl_distribucion tr").each(function() {

				if ($("#id_distribucion_11_" + d).val().length > 0) {
					total_distribuido += parseFloat($("#id_distribucion_11_" + d).val());
				}

				d++;
			});

			$("#td_totaldistribuido_" + itemlote_Selected).html(f_RedondearDecimales(total_distribuido, 2));

			// Si es Sara Briceño
			if ($("#val_9_" + itemlote_Selected).val() == 3) {
				$("#td_totallote_" + itemlote_Selected).html(f_RedondearDecimales(total_distribuido, 2));
			}

			// Seteando el Color del total distribuído
			var bg_color = '#FF8598';
			var total_lote = $("#td_totallote_" + itemlote_Selected).html().trim();

			if (parseFloat(total_lote).toFixed(2) == parseFloat(total_distribuido).toFixed(2)) {
				bg_color = '#CEF09D';
			}

			$("#td_totaldistribuido_" + itemlote_Selected).css('background-color', bg_color);
		}

		function f_VerifyCierreListo() {
			// Verificando Cierre del Lote seleccionado
			if ($("#td_iscerrado_" + itemlote_Selected).val() == 1) {
				return;
			}

			// verificando Tickets con Placa
			var d = 1;
			var is_placapendiente = 0;

			$("#tbl_detalle tr").each(function() {
				// Verificando Placa 1
				if ($("#id_distribucion_2_" + d).val() == '') {
					is_placapendiente = 1;
				}

				// Verificando si tiene Placa 2
				if ($("#id_distribucion_1_" + d).val() != undefined) {
					var tiene_carreta = $("#id_distribucion_1_" + d).val().split('|')[1];

					if (tiene_carreta == 1) {
						if ($("#id_distribucion_3_" + d).val() == '') {
							is_placapendiente = 1;
						}
					}
				}

				d++;
			});

			// Verificando Lotes Distribuidos
			var is_distribucionpendiente = 0;

			if ($('#td_totaldistribuido_' + itemlote_Selected).css('background-color') == 'rgb(255, 133, 152)') {
				is_distribucionpendiente = 1;
			}

			var id_modalidadenvio = $("#val_7_" + itemlote_Selected).val();
			var id_destino = $("#val_2_" + itemlote_Selected).val();
			var id_proveedorminero = $("#val_10_" + itemlote_Selected).val();

			if (id_modalidadenvio != undefined) {
				if (id_modalidadenvio == 3 || id_modalidadenvio == 4 || id_modalidadenvio == 5) {
					if (is_placapendiente == 1 ||
						is_distribucionpendiente == 1 ||
						id_modalidadenvio.length == 0 ||
						id_proveedorminero.length == 0) {
						$("#td_cierre_1_" + itemlote_Selected).html('');
					} else {
						$("#td_cierre_1_" + itemlote_Selected).html('<input id="chk_cierre_' + itemlote_Selected + '" class="form-check-input chk_cierre" type="checkbox" style="transform: scale(1.5);" onclick="f_handleCheckboxClick(event);">');
					}
				} else {
					if (is_placapendiente == 1 ||
						is_distribucionpendiente == 1 ||
						id_modalidadenvio.length == 0 ||
						id_destino.length == 0 ||
						id_proveedorminero.length == 0) {
						$("#td_cierre_1_" + itemlote_Selected).html('');
					} else {
						$("#td_cierre_1_" + itemlote_Selected).html('<input id="chk_cierre_' + itemlote_Selected + '" class="form-check-input chk_cierre" type="checkbox" style="transform: scale(1.5);" onclick="f_handleCheckboxClick(event);">');
					}
				}
			}
		}

		function f_VerifyLoteCerrado() {
			// Verifica Lote Cerrado para Placa 1 y Fecha de Inicio de Peso
			var d = 1;
			var placa1 = '';
			var fecha_inicio = '';
			var is_disabled = false;

			$("#tbl_distribucion tr").each(function() {
				// Obteniendo Placa 1
				placa1 = $("#id_distribucion_2_" + d).val().split('|')[0];

				// Obteniendo Fecha de Inicio de Pesado
				fecha_inicio = $("#id_distribucion_7_" + d).val();

				// Verificando si está cerrado
				$.ajax({
					type: "POST",
					url: "apis/backend.php",
					data: {
						accion: "get_PrimerTramo_ValidacionDistribucion_VerifyLotesCerrados",
						placa1: placa1,
						fecha_inicio: fecha_inicio
					},
					dataType: "json",
					async: false, // Configurar la solicitud como síncrona
					success: function(data) {
						is_disabled = false;
						if (data.is_disabled == 1) {
							is_disabled = true;
						}

						$(".info_unidad_" + d).prop('disabled', is_disabled);
					}
				});

				d++;
			});
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

		function f_LoadingListaLotes(_is_show) {
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

		function f_SelectChkCierre() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var tr_id = 0;

			$("#tbl_detalle tr:visible").filter(function() {
				tr_id = $(this).attr('id').substring(11);

				$("#chk_cierre_" + tr_id).prop('checked', is_checked);
			});
		}

		function f_LoteSelected(_item) {
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

			// Seteando los valores del campo Procedencia del Proveedor Minero
			if (_orden_campo == 30){
				var codigo_unico = '';
				var procedencia = '';

				_valor_x = $("#val_" + _orden_campo + "_" + _item).val();
				_valor = _valor_x.split('|')[0];

				codigo_unico = _valor_x.split('|')[1];
				procedencia = _valor_x.split('|')[2];
			}

			// Solo para Mineral Retirado
				if (_orden_campo == 6 && _valor == 5){
					if (!confirm("¿Está seguro de asignar este Lote como MINERAL RETIRADO?")){
						// Devuelve el valor original
							$.post( "apis/backend.php", { accion: "get_PrimerTramo_ValidacionDatos_EstadoLote", cod_lote: _cod_lote }, 
			          function( data ) {
			            if(data.estado == 1){
			              $("#val_" + _orden_campo + "_" + _item).val(data.id_estadolote);
			              $("#val_" + _orden_campo + "_" + _item).trigger('change');
			            }

			          }, "json");

						return;
					}
				}

			f_SavingDatos(1);

			// Selecciona previamente el TR
			f_LoadItemLote(_item, _cod_lote);

			// Grabando Datos
			$.post("apis/backend.php", {
					accion: "update_PrimerTramo_ValidacionDatos_new",
					cod_lote: _cod_lote,
					orden_campo: _orden_campo,
					valor: _valor,
					item: _item
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
									$("#val_6_" + _item).val(1);
								} else {
									$("#val_3_" + _item).val(fechahora_registro.substring(0, 10));
									$("#val_4_" + _item).val(fechahora_registro.substring(11).substring(0, 5));
									$("#val_5_" + _item).html('0.0');
									$("#val_6_" + _item).val(4); // Asignando Para programación
								}

								$("#val_6_" + _item).trigger('change');
						}

						if (_orden_campo == 3 || _orden_campo == 4) {
							$("#val_5_" + _item).html(data.destino_totaldiasdefiniciondestino);
						}

						if (_orden_campo == 10) {
							$("#td_pv_1_" + _item).html(data.proveedorminero_concesion);
							$("#td_pv_2_" + _item).html(data.proveedorminero_codigounico);
							$("#td_pv_3_" + _item).html(data.proveedorminero_ubicacion);
						}

						if (_orden_campo == 9) {
							$("#td_totallote_" + itemlote_Selected).html(data.total_neto);
						}

						if (_orden_campo == 10) {
							// Refrescando el Select de Concesiones
								$("#td_pv_1_" + itemlote_Selected).html(data.html);

								f_SetSelect2();

							// Grabando la Concesión
								f_UpdateDatos(_item, 30);
						}

						if (_orden_campo == 30){
							$("#td_pv_2_" + _item).html(codigo_unico);
							$("#td_pv_3_" + _item).html(procedencia);
						}

						if (_orden_campo == 6 && _valor == 5){
							$("#val_2_" + _item).val(16);
              $("#val_2_" + _item).trigger('change');
						}

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

		function f_EditDistribucion(_orden_campo, _item, _valida_infounidad) {
			// Obtiene Id
			var _id_registro = $("#id_distribucion_" + _item).val();

			// Obtiene Valor
			var _valor = $("#id_distribucion_" + _orden_campo + "_" + _item).val();

			// Validando datos
			if (_orden_campo == 1) {
				// Muestra u oculta la Placa 2
				if (_valor.split('|')[1] == 1) {
					$("#td_distribucion_3_" + _item).show();
					$("#id_distribucion_12_" + _item).show();
					$("#id_distribucion_13_" + _item).show();
					$("#td_distribucion_14_" + _item).show();
				} else {
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

			if (_orden_campo == 2) {
				// Determina si tiene Carreta
				var tiene_carreta = $("#id_distribucion_1_" + _item).val().split('|')[1];

				// Establece la Capacidad
				var capacidad = _valor.split('|')[1];
				var tara = _valor.split('|')[2];
				var id_marca = _valor.split('|')[3];

				// Setea Capacidad
				if (capacidad != undefined) {
					capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
				} else {
					capacidad = 0;
				}

				$("#id_distribucion_4_" + _item).val(capacidad);

				// Setea Tara
				if (tara != undefined) {
					tara = ((tara.trim().length > 0) ? f_RedondearDecimales(tara.trim() / 1000, 2) : '');
				} else {
					tara = 0;
				}

				$("#id_distribucion_5_" + _item).val(tara);

				// Setea Marca
				if (id_marca == undefined) {
					id_marca = '';
				}

				$("#id_distribucion_6_" + _item).val(id_marca);
			}

			if (_orden_campo == 3) {
				var capacidad = _valor.split('|')[1];
				var tara = _valor.split('|')[2];
				var id_marca = _valor.split('|')[3];

				// Setea Capacidad
				if (capacidad != undefined) {
					capacidad = ((capacidad.trim().length > 0) ? f_RedondearDecimales(capacidad.trim() / 1000, 2) : '');
				} else {
					capacidad = 0;
				}

				$("#id_distribucion_12_" + _item).val(capacidad);

				// Setea Tara
				if (tara != undefined) {
					tara = ((tara.trim().length > 0) ? f_RedondearDecimales(tara.trim() / 1000, 2) : '');
				} else {
					tara = 0;
				}

				$("#id_distribucion_13_" + _item).val(tara);

				// Setea Marca
				if (id_marca == undefined) {
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
			$.post("apis/backend.php", {
					accion: "update_PrimerTramo_DistribucionDatos",
					id_registro: _id_registro,
					orden_campo: _orden_campo,
					valor: _valor,
					valida_infounidad: ((_valida_infounidad == 1) ? 1 : 0)
				},
				function(data) {
					if (data.estado == 1) {
						if (_orden_campo == 7) {
							$("#id_distribucion_8_" + _item).val(_valor);
						}

						if (_orden_campo == 8) {
							$("#id_distribucion_7_" + _item).val(_valor);
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

						// Por si se cambia algún datos referente a la unidad
						if (_valida_infounidad == 1) {
							f_LoadItemLote(itemlote_Selected, codlote_Selected);
						}

						// Si se cambia la Placa y Fecha de Inicio valida el lote cerrado para los datos de unidades
						if (_orden_campo == 2 || _orden_campo == 7) {
							f_VerifyLoteCerrado();
						}
					} else {
						alert("Ocurrió un error al momento de actualizar el dato.");
					}

					f_SavingDistribucion(0);

				}, "json");
		}

		function f_SetColor_Grabar() {
			var _item = $("#hd_SetColorItem").val();
			var _cod_lote = $("#hd_SetColorLote").val();
			var _color = $("#colorSeleccionado").val();

			// Grabando datos
			$.post("apis/backend.php", {
					accion: "grabar_ValidacionDatos_SetColor",
					cod_lote: _cod_lote,
					color: _color
				},
				function(data) {
					if (data.estado == 1) {
						$("#tr_detalle_" + _item).css('background-color', ((_color == 'NULL') ? '' : _color));
					} else {
						alert("Ocurrió un error al momento de grabar los datos.");
					}

					// Cierra modal
					f_cerrarModal('modal_SetColor');

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

						f_SetInputDisabled();

					}, "json");
			}
		}

		function f_AddDistribucion() {
			// Seteando el Total Distribuído
			f_GetTotalDistribuido();

			// Obteniendo totales y validando datos
			var total_lote = $("#td_totallote_" + itemlote_Selected).html().trim();
			var total_distribuido = $("#td_totaldistribuido_" + itemlote_Selected).html().trim();
			var id_encargado = $("#val_9_" + itemlote_Selected).val();

			if (id_encargado != 3) {
				if (parseFloat(total_lote) == parseFloat(total_distribuido)) {
					alert("Ya ha distribuído el Total del Lote, no puede continuar.");

					return;
				} else {
					if (parseFloat(total_distribuido) > parseFloat(total_lote)) {
						alert("Ha superado el Total del Lote, no puede continuar.");

						return;
					}
				}
			}

			// Agregando Nueva Distribución
			if (!confirm("¿Está seguro de Crear una nueva distribución?")) {
				return;
			}

			$.post("apis/backend.php", {
					accion: "crear_PrimerTramo_NuevaDistribucion",
					cod_lote: codlote_Selected
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemLote(itemlote_Selected, codlote_Selected);

						// Seteando el Total Distribuído
						f_GetTotalDistribuido();
					} else {
						alert("Ocurrió un error al momento de Crear el Nuevo registro.");

						return;
					}

				}, "json");
		}

		function f_EliminarDistribucion(_id_distribucion, _num_ticket) {
			if (!confirm("¿Está seguro de eliminar el Ticket seleccionado?")) {
				return;
			}

			// Eliminando registro
			$.post("apis/backend.php", {
					accion: "eliminar_PrimerTramo_Distribucion",
					id_distribucion: _id_distribucion,
					cod_lote: codlote_Selected
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemLote(itemlote_Selected, codlote_Selected);
					} else {
						alert("Ocurrió un error al momento de Crear el Nuevo registro.");

						return;
					}

				}, "json");
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

		function f_Reabrir(_item, _cod_lote) {
			$.post("apis/backend.php", {
					accion: "reabrir_PrimerTramo_ValidacionDistribucion",
					cod_lote: _cod_lote
				},
				function(data) {
					if (data.estado == 0) {
						alert("Ocurrió un error al momento de reabrir el registro.");

						return;
					}

					if (data.estado == 1) {
						$("#td_cierre_1_" + _item).html('<input id="chk_cierre_' + _item + '" class="form-check-input chk_cierre" type="checkbox" style="transform: scale(1.5);">');
						$("#td_cierre_2_" + _item).html('');
						$("#td_cierre_3_" + _item).html('');

						f_SetInputDisabled();

						if (data.tiene_guia == 1) {
							alert("================= IMPORTANTE =================\n\nTener en cuenta que el lote seleccionado tiene Guía(s) asignadas. Una vez finalizados los ajustes en este módulo, verifique los cambios en el módulo de Gestión Guías.");
						}
					}

				}, "json");
		}
	</script>

	<!-- Funciones de Menús -->
	<script type="text/javascript">
		function f_SetDimension() {
			if (screen.width < 500) {
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