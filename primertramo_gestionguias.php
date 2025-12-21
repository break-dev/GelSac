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

	<!-- JSColor -->
	<script src="libs/jscolor/jscolor.js"></script>

	<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

	<title><?php echo $nom_app; ?> | Gestión de Guías - 1er Tramo</title>

	<script type="text/javascript">
		var is_mobile = 0;
		var color_selected = '';

		var itemagrupacion_Selected = 0;
		var idmodalidadenvio_selected = 0;
		var iddestino_selected = 0;
		var idproveedorminero_selected = 0;
		var idproveedormineroconcesion_selected = 0;
		var placa_selected = '';

		var guiaserie_md5_VerificacionDocumentaria = '';
		var guianumero_md5_VerificacionDocumentaria = '';
		var fecha_balanza_VerificacionDocumentaria = '';

		let img_selected = '0';

		let datosZIP = {};
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
			z-index: 1000;
		}

		.sticky-2 {
			position: sticky;
			left: 35;
			z-index: 1000;
		}

		.sticky-3 {
			position: sticky;
			left: 58;
			z-index: 1000;
		}

		.sticky-4 {
			position: sticky;
			left: 163;
			z-index: 1000;
		}

		.sticky-5 {
			position: sticky;
			left: 293;
			z-index: 1000;
		}

		.sticky-2h {
			position: sticky;
			left: 58;
			z-index: 1000;
		}

		.sticky-3h {
			position: sticky;
			left: 163;
			z-index: 1000;
		}

		.sticky-4h {
			position: sticky;
			left: 293;
			z-index: 1000;
		}

		/* Estilo para Cabeceras estáticas */
		.sticky-1Cx {
			position: sticky;
			top: 0;
			z-index: 2000;
		}

		.sticky-2Cxa {
			position: sticky;
			top: 0;
			z-index: 2000;
		}

		.sticky-2Cxc {
			position: sticky;
			top: 95;
			z-index: 2000;
		}

		.sticky-1C {
			position: sticky;
			top: 0;
		}

		.sticky-2Ca {
			position: sticky;
			top: 0;
			z-index: 1000;
		}

		.sticky-2Cb {
			position: sticky;
			top: 33;
			z-index: 1000;
		}

		.sticky-2Cc {
			position: sticky;
			top: 95;
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
										<h6 style="font-size: 14px;">Fecha Ingreso a Balanza</h6>
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
	           		<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">Estado guía</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="row" >
										<div class="col-md-12 col-sm-12 col-xs-12">
											<select id="filtro_estadoguia" class="form-select" style="text-align: left; font-size: 14px">
												<option value="">Elija una opción...</option>
												<option selected value="0">Pendiente</option>
												<option value="1">Asignada</option>
											</select>
										</div>
									</div>
								</div>
	           	</div>

	           	<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
	           		<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">N° Guía Remitente</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="row" >
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="filtro_numguiaT" type="text" class="form-control" style="font-size: 14px">
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
							<hr style="border-color: #D9D9D9;"/>
						</div>
					</div>

					<div class="row" style="padding: 0px;">
						<div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="d-flex">
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

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
									<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 350px; margin-bottom: 20px;">
										<table class="table table-bordered table-hover">
											<thead>
												<tr style="font-size: 12px;">
													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 58px;">
														N°
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
														Modalidad Envío
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
														Destino
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
														Proveedor Minero
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px; border-top-right-radius: 15px;">
														Placa
													</th>
												</tr>

												<tr style="font-size: 12px;">
													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_2" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_3" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_4" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														<input id="fil_5" type="text" class="form-control filter col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 14px; text-transform: uppercase;">
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

												<div style="border-left: solid; border-left-width: 1px; border-left-color: #BFBFBF; margin-left: 10px; margin-right: 10px; height: 37px;">
												</div>

												<h6 style="font-size: 14px; width: 150px; font-weight: bold; margin-top: 7px;">Fecha de Peso Inicial: </h6>

												<select id="detalle_fechainicial" class="form-select" style="text-align: left; font-size: 14px; width: 130px; margin-bottom: 5px;" onchange="f_LoadItemAgrupacion();">

												</select>

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
									<div class="d-flex" style="font-size: 14px; margin-top: -10px;">
										<label style="font-weight: bold;"> Modalidad de Envío: </label>
										<label id="lbl_titulomodalidadenvio" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></label>
										<label style="margin-left: 5px; margin-right: 5px; font-weight: bold;"> | Destino: </label>
										<label id="lbl_titulodestino" style="color: #337ab7; font-weight: bold;"></label>
										<label style="margin-left: 5px; margin-right: 5px; font-weight: bold;"> | Proveedor Minero: </label>
										<label id="lbl_tituloproveedorminero" style="color: #337ab7; font-weight: bold;"></label>
										<label style="margin-left: 5px; margin-right: 5px; font-weight: bold;"> | Placa: </label>
										<label id="lbl_tituloplaca" style="color: #337ab7; font-weight: bold;"></label>
									</div>

									<div class="d-flex" style="overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
											<thead>
												<tr style="font-size: 12px;">
													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 40px; border-top-left-radius: 15px;">
														Sel.<br>
														<input id="th_Chk" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.5);" onchange="f_SelectChk();">
													</th>

													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
														Lote
													</th>

													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 50px;">
														Verif.
													</th>

													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 100px;">
														Fecha Ingreso a Balanza<br>(Real)
													</th>

													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Ticket
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

													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Fecha Guías
													</th>

													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 170px;">
														N° Guía<br>Remitente
													</th>

													<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
														Información Transportista
													</th>
												</tr>

												<tr style="font-size: 12px;">
													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
														N° Guía
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
														RUC
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
														Razón Social
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
		</div>
		
	</div>

	<!-- Ventanas modales -->
	<div class="modal fade modal-dialog-scrollable" id="modal_adminguias" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminguiasLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_adminguiasLabel">Generar Guía</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
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
							Fecha Hora Emisión:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="guia_fechaemision" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
							<input id="guia_horaemision" type="time" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo substr($g_time, 0, 5); ?>">
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
							<input id="guia_transportista" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Placa:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="guia_placa" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled>
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12 info_placa2" style="padding: 5px; text-align: right; display: none;">
							Placa 2:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12 info_placa2" style="display: none;">
							<input id="guia_placa2" type="text" class="form-control" style="text-align: center; font-size: 14px;" disabled>
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

											<option value="<?php echo $row_lista["Id"] ?>"><?php echo $row_lista["dni_licencia"] . ' - ' . $row_lista["nombres"] ?></option>

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

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 90px;">
										Ticket
									</th>

									<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 120px;">
										Peso Distrbuído<br>Real
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
				<input id="hd_idtransportista" type="hidden">

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_grabarprogramacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabarprogramacion_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarprogramacion_button" style="font-size: 14px;" onclick="f_ConfirmarGuia();">Emitir Guías</button>
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
			$("#nv_titulo").html('| Gestión de Guías - 1er Tramo');

			// Carga el detalle de información
			f_LoadResultados();
		}
	</script>

	<!-- Seteando objetos Select2 -->
	<script type="text/javascript">
		function f_SetSelect2() {
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

		$('#guia_marcaunidad, #guia_marcaunidad2, #guia_conductor').select2({
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>

	<script type="text/javascript">
		async function f_DownloadVerificacion() {
			var dominioBase = window.location.origin;

			try {
				// Validando que hayan archivos
					if (Object.keys(datosZIP).length == 0){
						alert("No se han encontrado archivos para descargar.");

						return;
					}

				// Crear una nueva instancia de JSZip
					var zip = new JSZip();

					for (var key in datosZIP) {
						if (datosZIP.hasOwnProperty(key)) {
							// Obtener el array de rutas de archivos
							var files = datosZIP[key];

							// Recorrer cada ruta de archivo y agregarlo al ZIP
							for (let index = 0; index < files.length; index++) {
								var file = files[index];
								var filename = file.substring(file.lastIndexOf('/') + 1);

								// Hacer una petición fetch para obtener el contenido de la imagen
								var response = await fetch(dominioBase + file.substring(2));
								var blob = await response.blob();

								// Agregar el archivo al ZIP, renombrándolo con el nombre de la clave y su índice
								zip.file(`${key}/${index + 1}_${filename}`, blob);
							}
						}
					}

				// Generar el ZIP
					var content = await zip.generateAsync({
						type: "blob"
					});

				// Crear un objeto URL para el archivo ZIP
					var zipUrl = URL.createObjectURL(content);

				// Crear un elemento <a> para descargar el archivo
					var link = document.createElement('a');
					link.href = zipUrl;
					const tramo = $("#verif_numticket").val().trim()

					// link.download = `${$('#verif_codlote').val()} PARTE ${tramo?.replace("TICKET - ","").trim() == '1' ? '1 - 1er Tramo': '2 - 2do Tramo' }.zip`;

					link.download = $("#verif_codlote").val() + (($("#verif_numticket").val().trim().length == 0) ? '' : ' ' + $("#verif_numticket").val().trim().replace('TICKET ', '')) + ' - 1er Tramo.zip';

				// Agregar el elemento <a> al cuerpo del documento
					document.body.appendChild(link);

				// Simular un clic en el enlace para iniciar la descarga
					link.click();

				// Eliminar el elemento <a> después de la descarga
					document.body.removeChild(link);
			} catch (e) {
				alert("NO SE PUDO DESCARGAR LOS ARCHIVOS")
				console.log(e)
			}
		}


		function f_LoadResultados() {
			var _html = '';

			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_estadoguia = $("#filtro_estadoguia").val();
			var filtro_numguiaR = $("#filtro_numguiaR").val();
			var filtro_numguiaT = $("#filtro_numguiaT").val();
			var filtro_lote = $("#filtro_lote").val();

			f_LoadingResumen(1);

			$("#detalle_fechainicial").html('');
			$("#tbl_detalle").html('');
			$("#tbl_listalotes").html('');
			$("#lbl_titulomodalidadenvio").html('');
			$("#lbl_titulodestino").html('');
			$("#lbl_tituloproveedorminero").html('');
			$("#lbl_tituloplaca").html('');

			$.post("apis/backend.php", {
					accion: "get_PrimerTramo_GestionGuias_AgrupacionCriterios",
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
					filtro_estadoguia: filtro_estadoguia,
					filtro_numguiaR: filtro_numguiaR,
					filtro_numguiaT: filtro_numguiaT,
					filtro_lote: filtro_lote
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_detalle").html(data.html);

						// Carga lista de Fechas Iniciales
						f_LoadFechasPesoInicial(1, data.idmodalidadenvio_inicio, data.iddestino_inicio, data.idproveedorminero_inicio, data.placa_inicio, data.idproveedormineroconcesion_inicio);

						// // Carga por defecto el primer item
						// 	f_LoadItemAgrupacion(1, data.idmodalidadenvio_inicio, data.iddestino_inicio, data.idproveedorminero_inicio, data.placa_inicio);
					}

					f_LoadingResumen(0);

					// Seteando Select2
					f_SetSelect2();

					// Se posisiona en el div de Detalle
					var divDetalle = document.getElementById("div_detalle");

					if (divDetalle) {
						divDetalle.scrollIntoView({
							behavior: "smooth"
						});
					}

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

		function f_LoadItemAgrupacion() {
			// Obteniendo filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_estadoguia = $("#filtro_estadoguia").val();
			var filtro_numguiaR = $("#filtro_numguiaR").val();
			var filtro_numguiaT = $("#filtro_numguiaT").val();
			var filtro_lote = $("#filtro_lote").val();
			var fecha_pesoinicial = $("#detalle_fechainicial").val();

			// Carga las distribuciones
			f_LoadingListaLotes(1);

			$("#tbl_listalotes").html('');
			$("#th_Chk").prop('checked', false);

			$.post("apis/backend.php", {
					accion: "get_PrimerTramo_GestionGuias_AgrupacionCriterios_ListaLotes",
					fecha_inicio: fecha_inicio,
					fecha_fin: fecha_fin,
					estado_guia: filtro_estadoguia,
					num_guiaR: filtro_numguiaR,
					num_guiaT: filtro_numguiaT,
					filtro_lote: filtro_lote,
					id_modalidadenvio: idmodalidadenvio_selected,
					id_destino: iddestino_selected,
					id_proveedorminero: idproveedorminero_selected,
					placa: placa_selected,
					fecha_pesoinicial: fecha_pesoinicial
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_listalotes").html(data.html);
					}

					f_LoadingListaLotes(0);

				}, "json");

			// // Setea las variables globales
			// 	itemagrupacion_Selected = _item;
			//  idmodalidadenvio_selected = _id_modalidadenvio;
			// 	iddestino_selected = _id_destino;
			// 	idproveedorminero_selected = _id_proveedorminero;
			// 	placa_selected = _placa;
		}

		function f_GetTotalDistribuido() {
			var d = 1;
			var total_distribuido = 0;

			// Recorre las Filas de lotes distribuídos
			$("#tbl_listalotes tr").each(function() {

				if ($("#id_distribucion_11_" + d).val().length > 0) {
					total_distribuido += parseFloat($("#id_distribucion_11_" + d).val());
				}

				d++;
			});

			$("#td_totaldistribuido_" + itemlote_Selected).html(total_distribuido);

			// Seteando el Color del total distribuído
			var bg_color = '#FF8598';
			var total_lote = $("#td_totallote_" + itemlote_Selected).html().trim();

			if (parseFloat(total_lote) == parseFloat(total_distribuido)) {
				bg_color = '#CEF09D';
			}

			$("#td_totaldistribuido_" + itemlote_Selected).css('background-color', bg_color);
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

		function f_AddGuia(_is_edit, fecha_inicio, _fechahora_iniciotraslado, _guiaremitente_serie, _guiaremitente_numero, _guiatransportista_serie, _guiatransportista_numero, _id_chofer, arr_distribuciones, _tiene_complemento, _is_complemento, _complemento_pesodistribuido) {
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
					if ($("#chk_lote_" + d).prop('checked')) {
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

			// Definiendo la fecha de guías a 1 día antes de la Fecha de Peso Inicial
			if (_is_edit != 1) {
				var fecha_inicio = $("#detalle_fechainicial").val();

				// Convierte la fecha a un objeto Date de JavaScript
				var fecha_inicio = new Date(fecha_inicio);

				// Resta un día a la fecha
				fecha_inicio.setDate(fecha_inicio.getDate() - 1);

				// Obtiene el resultado en el formato "YYYY-MM-DD"
				fecha_inicio = fecha_inicio.toISOString().split('T')[0];
			}

			// Limpiando los datos
			$("#guia_fechas").val(fecha_inicio);

			if (_fechahora_iniciotraslado == undefined){
				$("#guia_fechaemision").val('<?php echo $g_date ?>');
				$("#guia_horaemision").val('<?php echo substr($g_time, 0, 5) ?>');
			}
			else{
				$("#guia_fechaemision").val(_fechahora_iniciotraslado.substring(0, 10));
				$("#guia_horaemision").val(_fechahora_iniciotraslado.substring(16, 11));
			}

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

			// Obtiene la información
			$.post("apis/backend.php", {
					accion: "get_PrimerTramo_GestionGuias_Datos",
					id_modalidadenvio: idmodalidadenvio_selected,
					id_destino: iddestino_selected,
					id_proveedorminero: idproveedorminero_selected,
					placa: placa_selected,
					arr_distribuciones: arr_distribuciones,
					id_concesion: idproveedormineroconcesion_selected
				},
				function(data) {
					if (data.estado == 1) {
						$("#guia_puntopartida").val(data.punto_partida);
						$("#guia_puntodestino").val(data.punto_destino);
						$("#guia_remitente").val($("#td_detalle_3_" + itemagrupacion_Selected).html().trim());
						$("#guia_destinatario").val(data.destinatario);
						$("#guia_transportista").val(data.transportista);
						$("#guia_placa").val($("#td_detalle_4_" + itemagrupacion_Selected).html().trim());
						$("#guia_constanciamtc").val(data.codigo_mtc_1);
						$("#guia_marcaunidad").val(data.marca_1);
						$("#guia_marcaunidad").trigger('change');
						$("#guia_capacidadunidad").val(data.capacidad);
						$("#tbl_guialistalotes").html(data.html);
						$("#hd_idtransportista").val(data.id_transportista);

						if (_is_edit != 1) {
							$("#guia_conductor").val(data.chofer);
						} else {
							$("#guia_conductor").val(_id_chofer);
						}

						$("#guia_conductor").trigger('change');
						$("#guia_motivotraslado").val(data.motivo_traslado);

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
					}

				}, "json");

			// Abre modal
			f_OpenModal('modal_adminguias');
		};

		function f_ImpirmirGuias(_tipo, _guiaserie_md5, _guianumero_md5, is_remitente, _id_remitente, _id_transportista, _guia_fecha) {
			if (_tipo == 1){
				if (is_remitente == 1) {
					url = 'print_primertramo_guiar_v1.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
				} else {
					url = 'print_primertramo_guiat_v1.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
				}
			}
			else{
				if (is_remitente == 1) {
					url = 'print_primertramo_guiar_v2.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
				} else {
					url = 'print_primertramo_guiat_v2.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
				}
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
			var cod_lote = $("#td_codlote_" + _item).text().trim().replace(/\t/g, "");;
			var num_ticket = $("#td_numticket_" + _item).html().trim();
			var num_guiaR = $("#td_guiar_" + _item).html();
			var fecha_balanza = $("#detalle_fechainicial").val();

			if (cod_lote.split(" ").length) {
				cod_lote = cod_lote.split(" ")[0]
			}

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
			// Asignando variables públicas
			guiaserie_md5_VerificacionDocumentaria = _guiaserie_md5;
			guianumero_md5_VerificacionDocumentaria = _guianumero_md5;
			fecha_balanza_VerificacionDocumentaria = _fecha_balanza;

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
						datosZIP = data.datos;
						console.log({
							datosZIP
						})
					}

				}, "json");
		}

		function f_LoadFechasPesoInicial(_item, _id_modalidadenvio, _id_destino, _id_proveedorminero, _placa, _id_proveedormineroconcesion) {
			// Pinta selección
			f_AgrupacionSelected(_item);

			// Obteniendo filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_estadoguia = $("#filtro_estadoguia").val();
			var filtro_numguiaR = $("#filtro_numguiaR").val();
			var filtro_numguiaT = $("#filtro_numguiaT").val();
			var filtro_lote = $("#filtro_lote").val();

			// Setea las variables globales
			itemagrupacion_Selected = _item;
			idmodalidadenvio_selected = _id_modalidadenvio;
			iddestino_selected = _id_destino;
			idproveedorminero_selected = _id_proveedorminero;
			idproveedormineroconcesion_selected = _id_proveedormineroconcesion;
			placa_selected = _placa;

			// Carga Fechas
			f_LoadingListaLotes(1);

			$("#detalle_fechainicial").val('');

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
						$("#detalle_fechainicial").html(data.html);

						// Carga por defecto el primer item
						// f_LoadItemAgrupacion(1, _id_modalidadenvio, _id_destino, _id_proveedorminero, _placa);
						f_LoadItemAgrupacion();
					}

					f_LoadingListaLotes(0);

				}, "json");
		}

		function f_PrintInformes(_item, _guiaserie_md5, _guianumero_md5, _id_remitente, _id_transportista, _guia_fecha, _cod_lote) {
			var url = '';

			if (_item == 1) {
				url = 'print_djguias.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
			}

			if (_item == 2) {
				url = 'print_cargosguias_1tramo.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
			}

			if (_item == 3) {
				url = 'print_cargosguiasplanta_1tramo.php?l=' + _cod_lote;
			}

			window.open(url, '_blank');
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

		function f_SelectChk() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			var d = 1;

			$("#tbl_listalotes tr").each(function() {
				$("#chk_lote_" + d).prop('checked', is_checked);

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

			// Seteando títulos
			$("#lbl_titulomodalidadenvio").html($("#td_detalle_1_" + _item).html().trim());
			$("#lbl_titulodestino").html($("#td_detalle_2_" + _item).html().trim());
			$("#lbl_tituloproveedorminero").html($("#td_detalle_3_" + _item).html().trim());
			$("#lbl_tituloplaca").html($("#td_detalle_4_" + _item).html().trim());
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

					}, "json");
			}
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
						f_LoadItemAgrupacion(itemlote_Selected, codlote_Selected);
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

		function f_ConfirmarGuia() {
			var modograbar_guia = $("#modograbar_guia").val();
			var guia_fechas = $("#guia_fechas").val();
			var guia_fechaemision = $("#guia_fechaemision").val();
			var guia_horaemision = $("#guia_horaemision").val();
			var guia_remitenteserie = $("#guia_remitenteserie").val();
			var guia_remitentenumero = $("#guia_remitentenumero").val();
			var guia_transportistaserie = $("#guia_transportistaserie").val();
			var guia_transportistanumero = $("#guia_transportistanumero").val();
			var sin_GRT = (($("#chk_SinGRT").prop('checked')) ? 1 : 0);
			var guia_puntopartida = $("#guia_puntopartida").val();
			var guia_puntodestino = $("#guia_puntodestino").val();
			var guia_remitente = $("#guia_remitente").val();
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

			// Validando datos
				if (guia_fechas == null) {
					alert("Debe registrar la Fecha de Inicio de Traslado.");

					return;
				}
				if (guia_fechas.length == 0) {
					alert("Debe registrar la Fecha de Inicio de Traslado.");

					return;
				}

				if (guia_fechaemision == null){
          alert("Debe ingresar la Fecha de Emisión.");

          return;
        }
        if (guia_fechaemision.length == 0){
          alert("Debe ingresar la Fecha de Emisión.");

          return;
        }

        if (guia_horaemision == null){
          alert("Debe ingresar la Hora de Emisión.");

          return;
        }
        if (guia_horaemision.length == 0){
          alert("Debe ingresar la Hora de Emisión.");

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

			// Obtiene las toneladas ajustadas
				var d = 1;
				var arr_netoajutado = '';

				while (d < total_rows) {
					arr_netoajutado += $("#id_guialote_" + d).val() + ';' + parseFloat($("#guialote_pesoajustado_" + d).val()) + '|';

					d++;
				}

				arr_netoajutado = arr_netoajutado.substring(0, arr_netoajutado.length - 1);

			// Grabando datos
				$.post("apis/backend.php", {
						accion: "grabar_PrimerTramo_GestionGuias",
						modograbar_guia: modograbar_guia,
						guia_fechas: guia_fechas,
						guia_fechaemision: guia_fechaemision,
						guia_horaemision: guia_horaemision,
						guia_remitenteserie: guia_remitenteserie,
						guia_remitentenumero: guia_remitentenumero,
						guia_transportistaserie: guia_transportistaserie,
						guia_transportistanumero: guia_transportistanumero,
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
						arr_netoajutado: arr_netoajutado
					},
					function(data) {
						if (data.estado == 1) {
							// Imprimir Guías
							url = 'print_primertramo_guiar_v1.php?a=' + data.gr_serie + '&b=' + data.gr_numero + '&c=' + idproveedorminero_selected + '&d=' + $("#hd_idtransportista").val() + '&e=' + guia_fechas;
							window.open(url, '_blank');

							if (sin_GRT == 0) {
								url = 'print_primertramo_guiat_v1.php?a=' + data.gt_serie + '&b=' + data.gt_numero + '&c=' + idproveedorminero_selected + '&d=' + $("#hd_idtransportista").val() + '&e=' + guia_fechas;
								window.open(url, '_blank');
							}

							f_LoadItemAgrupacion();
						} else {
							alert("Ocurrió un error al momento de confirmar las guías.");
						}

						// Cierra modal
						f_cerrarModal('modal_adminguias');

					}, "json");
		}

		function f_EliminarGuia(_numguia_serie, _numguia_numero) {
			if (!confirm("¿Está seguro de eliminar la guía seleccionada?\n\n   - Guía Remitente: " + _numguia_serie + '-' + _numguia_numero + "\n\nSi continua se eliminará también la guía del transportista.")) {
				return;
			}

			// Grabando datos
			$.post("apis/backend.php", {
					accion: "eliminar_PrimerTramo_Guias",
					numguia_serie: _numguia_serie,
					numguia_numero: _numguia_numero
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadItemAgrupacion();
					} else {
						alert("Ocurrió un error al momento de eliminar la guía.");
					}
				});
		}

		function f_EliminarVerificacion(_id_registro) {
			if (!confirm("¿Está seguro de eliminar el documento seleccionado?")) {
				return;
			}

			// Grabando datos
			$.post("apis/backend.php", {
					accion: "eliminar_PrimerTramo_VerificacionDocumento",
					id_registro: _id_registro
				},
				function(data) {
					if (data.estado == 1) {
						f_GetVerificacionDocumentos(guiaserie_md5_VerificacionDocumentaria, guianumero_md5_VerificacionDocumentaria, fecha_balanza_VerificacionDocumentaria);
					} else {
						alert("Ocurrió un error al momento de eliminar la guía.");
					}
				});
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