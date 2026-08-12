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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

	<!-- Íconos -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

	<!-- Select2 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

	<link rel="stylesheet" href="<?php echo $url_lims ?>/global/styles.css">

	<title><?php echo $nom_app; ?> | Cierre de Lotes</title>

	<script type="text/javascript">
		var is_mobile = 0;
		var color_selected = '';
	</script>

	<style>
		.table-container {
			max-width: 100%;
			overflow-x: scroll;
		}

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
			left: 35;
			z-index: 1000;
		}

		.sticky-4 {
			position: sticky;
			left: 140;
			z-index: 1000;
		}

		.sticky-5 {
			position: sticky;
			left: 270;
			z-index: 1000;
		}

		.sticky-2h {
			position: sticky;
			left: 35;
			z-index: 1000;
		}

		.sticky-3h {
			position: sticky;
			left: 140;
			z-index: 1000;
		}

		.sticky-4h {
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
			<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true"
				data-bs-backdrop="static" data-bs-keyboard="false">
				<div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body"
							style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0px !important;">
							<ul class="list-unstyled">
								<div id="div_menu1"></div>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal (Menú Lateral) -->
			<div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true"
				data-bs-backdrop="static" data-bs-keyboard="false">
				<div class="modal-dialog modal-right" style="margin-top: 0px !important; margin-left: 0px !important;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="menuModalLabel">Filtro de Opciones</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body" style="padding: 0px !important;">

							<div class="row"
								style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
								<div
									style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">
											Fecha Ingreso a Balanza
										</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_inicio" type="date" class="form-control"
												style="text-align: center; font-size: 14px;"
												value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">
										</div>
										<br><br>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_fin" type="date" class="form-control"
												style="text-align: center; font-size: 14px;"
												value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">
										</div>

									</div>
								</div>
							</div>

							<div class="row"
								style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div
										style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Lotes:</h6>
										</div>
										<div class="row"
											style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
										</div>
										<div class="d-flex"
											style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_lote" class="form-control" multiple
												data-placeholder="Elija una o más opciones..."
												style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
												<?php

												$q_lotes = "SELECT ccod_Lote
																						FROM catalogolotes
																					WHERE YEAR(dFechaIngreso) >= 2024
																					ORDER BY ccod_Lote DESC";

												if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
													if (mysqli_num_rows($res_lotes) > 0) {
														while ($row_lotes = mysqli_fetch_array($res_lotes)) {
															?>

															<option value="<?php echo $row_lotes["ccod_Lote"]; ?>">
																<?php echo $row_lotes["ccod_Lote"]; ?>
															</option>

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

							<div class="row"
								style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();"
										style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
										<i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
									</button>
								</div>
								<br><br>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<button class="btn btn-success" type="button" onclick="f_ExportToExcel();"
										style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
										<b>Exportar a Excel</b>
									</button>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12"
				style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
				<div class="d-flex row">

					<div class="row"
						style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
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
							<div class="row"
								style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="row"
										style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="col-md-9 col-sm-9 col-xs-12">
											<div class="d-flex">
												<div class="d-flex flex-fill">
													<h5>Resumen </h5>

													<div id="wt_resumen" class=""
														style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>

													<div id="wt_saving" class=""
														style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Grabando datos...</label>
													</div>
												</div>
											</div>
										</div>

										<!-- <div class="col-md-3 col-sm-3 col-xs-12" style="margin-top: -5px;">
											<div class="d-flex">
												<button class="btn btn-danger" type="button"
													onclick="f_ConfirmarCierre();"
													style="width: 100%; color: #ffffff; height: 40px; font-size: 14px; margin-top: -5px;">
													<b> Confirmar Cierre</b>
												</button>

												<button class="btn btn-success" type="button"
													onclick="f_GenerarCodigoGel();"
													style="width: 100%; color: #ffffff; height: 40px; font-size: 14px; margin-top: -5px; margin-left: 5px;">
													<b> Generar Código GEL</b>
												</button>
											</div>
										</div> -->
									</div>
								</div>



								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12"
									style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
									<div class="table-container"
										style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 500px; margin-bottom: 20px;">
										<table class="table table-bordered table-hover">
											<thead>
												<tr style="font-size: 12px;">
													<th colspan="2" rowspan="3"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 35px;">
														N°
													</th>

													<th colspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
														Información Lote
													</th>

													<th colspan="3"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
														Información Código GEL
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 105px;">
														Ticket Balanza
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
														Fecha Llegada<br>(Contable)
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Placa 1
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Placa 2
													</th>

													<th colspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Información Guías
													</th>

													<th colspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Emp. de Transporte
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Tipo Vehículo
													</th>

													<th colspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Info. Conductor
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Tipo Carga
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Zona Origen
													</th>

													<th colspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Proveedor Minero
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
														Encargado Muestra
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
														Producto
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
														Tipo Mineral
													</th>

													<th rowspan="2"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
														Observación
													</th>

													<th colspan="5"
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Información de Pesos (Kg)
													</th>

													<th colspan="3"
														style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
														Cierre
													</th>
												</tr>

												<tr style="font-size: 12px;">
													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Código
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 50px;">
														Parte
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 40px;" hidden>
														Sel.<br>
														<input id="th_Chk" class="form-check-input" type="checkbox"
															style="margin-top: 5px; transform: scale(1.5);"
															onchange="f_SelectChkCodigoGel();">
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Código GEL
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Fecha Hora<br>Registro
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
														Usuario<br>Registro
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Remitente
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Transportista
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														DNI/RUC
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
														Razón Social
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Licencia
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
														Nombres
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														DNI/RUC
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
														Razón Social
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
														Fecha Peso Inicial
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
														Fecha Peso Final
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Bruto
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Tara
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Neto
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
														Valorizado
													</th>

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 50px;">
														Facturado
													</th>

													<!-- Columna Sel. eliminada -->

													<th
														style="text-align: center; border: solid; border-width: 1px; background-color: #F23030; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Fecha Hora
													</th>

													<!-- Columna Usuario eliminada -->
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

	</div>

	<!-- Ventanas modales -->
	<div class="modal fade" id="modal_SetColor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
		aria-labelledby="modal_SetColorLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div id="modal_SetColor_content" class="modal-content" style="margin-top: 15%;">
				<div class="modal-header" style="background-color: #f8da62;">
					<h1 class="modal-title fs-5">Asigne un color: </h1>
					<h1 class="modal-title fs-5" id="modal_SetColorLabel" style="margin-left: 5px; font-weight: bold;">
					</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="d-flex justify-content-center" style="padding: 5px;">
						<div class="color-box" data-color="#99BFF2"
							style="background-color: #99BFF2; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer; margin-right: 5px;">
						</div>
						<div class="color-box" data-color="#6BFA7E"
							style="background-color: #6BFA7E; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer; margin-right: 5px;">
						</div>
						<div class="color-box" data-color="#FADD5F"
							style="background-color: #FADD5F; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer; margin-right: 5px;">
						</div>
						<div class="color-box" data-color="#FF7F82"
							style="background-color: #FF7F82; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer;">
						</div>
					</div>
				</div>

				<input id="hd_setcolor_item" type="hidden">

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="f_GrabarColor();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Referenciando a JQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"
		integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
		crossorigin="anonymous"></script>

	<!-- Select2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<!-- ECharts -->
	<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

	<!-- Referenciando auxiliares -->
	<?php include('global/auxiliares_js.php'); ?>

	<!-- Funciones de Inicio -->
	<script type="text/javascript">
		function f_Init() {
			// Genera menús
			f_GetMenuPrincipal();

			// Titulo de Pantalla
			$("#nv_titulo").html('| Cierre de Lotes');

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
			}).on('select2:open', function () {
				$('body').css('zoom', '100%');
			}).on('select2:close', function () {
				$('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
			});

			$('.select2-container').css('z-index', 1);

			$('#filtro_lote').select2({
				theme: "bootstrap-5",
				width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : '100%',
				placeholder: $(this).data('placeholder'),
				allowClear: true,
				minimumResultsForSearch: -1
			}).on('select2:open', function () {
				$('body').css('zoom', '100%');
			}).on('select2:close', function () {
				$('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
			});

			$('.select2-search__field').css('font-size', '14px');
		}
	</script>

	<!-- Funciones Principales -->
	<script type="text/javascript">
		function f_LoadResultados() {
			var _html = '';

			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_transportista = $("#filtro_transportista").val();
			var filtro_placa = $("#filtro_placa").val();
			var filtro_lote = $("#filtro_lote").val();

			f_LoadingResumen(1);

			$("#tbl_detalle").html('');

			$.post("apis/backend.php", { accion: "get_ListaResumenBalanza_Cierre", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_transportista: filtro_transportista, filtro_placa: filtro_placa, filtro_lote: filtro_lote },
				function (data) {
					if (data.estado == 1) {
						$("#tbl_detalle").html(data.html);

						// f_SetInputDisabled();
					}

					f_LoadingResumen(0);

					// Seteando Select2
					f_SetSelect2();

				}, "json");
		};

		function f_SetColor(_item, _lote) {
			$("#hd_setcolor_item").val(_item);

			// Setea título
			$("#modal_SetColorLabel").html(_lote);

			// Limpia la variable global
			color_selected = '';

			f_OpenModal('modal_SetColor');
		}

		// function f_SetInputDisabled() {
		// 	var cierre = '';

		// 	// Recorre las todas filas
		// 	$("#tbl_detalle tr").filter(function () {
		// 		tr_id = $(this).attr('id').substring(11);

		// 		// Identifica el valor del cierre
		// 		cierre = $("#td_cierre_1_" + tr_id).html();

		// 		if (cierre.toLowerCase().includes('reabrir')) {
		// 			$(".input_datos_" + tr_id).prop('disabled', true);
		// 		}
		// 		else {
		// 			$(".input_datos_" + tr_id).prop('disabled', false);
		// 		}
		// 	});
		// }

		function f_PrintTicketBakanza(_id_md5) {
			url = 'print_ticketbalanza_prev.php?x=' + _id_md5;

			window.open(url, '_blank');
		}

		function f_ExportToExcel() {
			// Obteniendo filtros
			var fecha_inicio = $("#fecha_inicio").val();
			var fecha_fin = $("#fecha_fin").val();
			var filtro_transportista = $("#filtro_transportista").val();
			var filtro_placa = $("#filtro_placa").val();

			window.location.href = "export_to_excel/cierre_lotes.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin + "&filtro_transportista=" + filtro_transportista + "&filtro_placa=" + filtro_placa;
		}
	</script>

	<!-- Funciones Secundarias -->
	<script type="text/javascript">
		function f_ValidarFechas(_item, _orden_campo) {
			var fechahora_ingreso = $("#td_ingreso_fecha_" + _item).val() + ' ' + $("#td_ingreso_hora_" + _item).val();
			var fechahora_inicial = $("#td_pesoinicio_fecha_" + _item).val() + ' ' + $("#td_pesoinicio_hora_" + _item).val();
			var fechahora_final = $("#td_pesofin_fecha_" + _item).val() + ' ' + $("#td_pesofin_hora_" + _item).val();

			if (_orden_campo == 5 || _orden_campo == 6) {
				if (fechahora_ingreso > fechahora_inicial) {
					alert("La Fecha y Hora de Ingreso no puede ser mayor a la Fecha y Hora de Inicio de Pesado.");
				}
				else {
					if (fechahora_ingreso > fechahora_final) {
						alert("La Fecha y Hora de Ingreso no puede ser mayor a la Fecha y Hora de Fin de Pesado.");
					}
				}

				f_UpdateDatos(_item, _orden_campo, 0);

				return false;
			}

			if (_orden_campo == 1 || _orden_campo == 2) {
				if (fechahora_ingreso > fechahora_inicial) {
					alert("La Fecha y Hora de Inicio de Pesado no puede ser menor a la Fecha y Hora de Ingreso.");
				}
				else {
					if (fechahora_inicial > fechahora_final) {
						alert("La Fecha y Hora de Inicio de Pesado no puede ser mayor a la Fecha y Hora de Fin de Pesado.");
					}
				}

				f_UpdateDatos(_item, _orden_campo, 0);

				return false;
			}

			if (_orden_campo == 3 || _orden_campo == 4) {
				if (fechahora_ingreso > fechahora_final) {
					alert("La Fecha y Hora de Fin de Pesado no puede ser menor a la Fecha y Hora de Ingreso.");
				}
				else {
					if (fechahora_inicial > fechahora_final) {
						alert("La Fecha y Hora de Fin de Pesado no puede ser menor a la Fecha y Hora de Inicio de Pesado.");
					}
				}

				f_UpdateDatos(_item, _orden_campo, 0);

				return false;
			}
		}

		function f_ValidarFechas2(_item, _orden_campo) {
			var _ok = 1;
			var fechahora_ingreso = $("#td_ingreso_fecha_" + _item).val() + ' ' + $("#td_ingreso_hora_" + _item).val();
			var fechahora_inicial = $("#td_pesoinicio_fecha_" + _item).val() + ' ' + $("#td_pesoinicio_hora_" + _item).val();
			var fechahora_final = $("#td_pesofin_fecha_" + _item).val() + ' ' + $("#td_pesofin_hora_" + _item).val();
			var _arr_msg = '';

			if (_orden_campo == 5 || _orden_campo == 6) {
				if (fechahora_ingreso > fechahora_inicial) {
					_arr_msg = "La Fecha y Hora de Ingreso no puede ser mayor a la Fecha y Hora de Inicio de Pesado.";

					_ok = 0;
				}
				else {
					if (fechahora_ingreso > fechahora_final) {
						_arr_msg = "La Fecha y Hora de Ingreso no puede ser mayor a la Fecha y Hora de Fin de Pesado.";

						_ok = 0;
					}
				}
			}

			if (_orden_campo == 1 || _orden_campo == 2) {
				if (fechahora_ingreso > fechahora_inicial) {
					_arr_msg = "La Fecha y Hora de Inicio de Pesado no puede ser menor a la Fecha y Hora de Ingreso.";

					_ok = 0;
				}
				else {
					if (fechahora_inicial > fechahora_final) {
						_arr_msg = "La Fecha y Hora de Inicio de Pesado no puede ser mayor a la Fecha y Hora de Fin de Pesado.";

						_ok = 0;
					}
				}
			}

			if (_orden_campo == 3 || _orden_campo == 4) {
				if (fechahora_ingreso > fechahora_final) {
					_arr_msg = "La Fecha y Hora de Fin de Pesado no puede ser menor a la Fecha y Hora de Ingreso.";

					_ok = 0;
				}
				else {
					if (fechahora_inicial > fechahora_final) {
						_arr_msg = "La Fecha y Hora de Fin de Pesado no puede ser menor a la Fecha y Hora de Inicio de Pesado.";

						_ok = 0;
					}
				}
			}

			return _ok + '|' + _arr_msg;
		}

		function f_LoadingResumen(_is_show) {
			if (_is_show == 1) {
				$("#wt_resumen").show();
			}
			else {
				$("#wt_resumen").hide();
			}
		}

		function f_SavingDatos(_is_show) {
			if (_is_show == 1) {
				$("#wt_saving").show();
			}
			else {
				$("#wt_saving").hide();
			}
		}

		$('.color-box').click(function () {
			$('.color-box').css('border-color', '#ffffff'); // Resetear todos los bordes a blanco
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

			$("#tbl_detalle tr:visible").filter(function () {
				tr_id = $(this).attr('id').substring(11);

				$("#chk_cierre_" + tr_id).prop('checked', is_checked);
			});
		}

		function f_SelectChkCodigoGel() {
			var is_checked = false;

			// Obteniendo valor del checkbox
			if ($("#th_Chk").prop('checked')) {
				is_checked = true;
			}

			// Recorre solo las filas visibles
			$(".chk_codigogel").prop('checked', false);

			if (is_checked) {
				$(".chk_codigogel").prop('checked', true);
			}
		}
	</script>

	<!-- Funciones de Grabación -->
	<script type="text/javascript">
		function f_UpdateDatos(_item, _orden_campo, _validafechas) {
			// Obtiene Id
			var _Id = $("#id_" + _item).val();

			// Obtiene Valor
			var _valor = $("#val_" + _orden_campo + '_' + _item).val();

			// Complementa la fecha y hora de definición de Destino
			if (_orden_campo == 1 || _orden_campo == 2) {
				_valor = $("#td_pesoinicio_fecha_" + _item).val() + ' ' + $("#td_pesoinicio_hora_" + _item).val();
			}

			if (_orden_campo == 3 || _orden_campo == 4) {
				_valor = $("#td_pesofin_fecha_" + _item).val() + ' ' + $("#td_pesofin_hora_" + _item).val();
			}

			if (_orden_campo == 5 || _orden_campo == 6) {
				_valor = $("#td_ingreso_fecha_" + _item).val() + ' ' + $("#td_ingreso_hora_" + _item).val();
			}

			// // Validando Fechas
			// 	if (_validafechas != 0){
			// 		if (!f_ValidarFechas(_item, _orden_campo)){
			// 			return;
			// 		}
			// 	}

			// Seteando Check de Código GEL
			var is_valorizado = (($("#chk_codigogel_1_" + _item).prop('checked')) ? 1 : 0);
			var is_facturado = (($("#chk_codigogel_2_" + _item).prop('checked')) ? 1 : 0);

			if (_orden_campo == 7 || _orden_campo == 8) {
				if (is_valorizado == 1 && is_facturado == 1) {
					$("#chk_cierre_" + _item).show();
				}
				else {
					$("#chk_cierre_" + _item).hide();
				}

				if (_orden_campo == 7) {
					_valor = is_valorizado;
				}
				else {
					_valor = is_facturado;
				}
			}

			f_SavingDatos(1);

			// Grabando Datos
			$.post("apis/backend.php", { accion: "update_ResumenBalanza_Datos", Id: _Id, orden_campo: _orden_campo, valor: _valor },
				function (data) {
					if (data.estado == 1) {
						// Actualizando Fecha de Inicio y Fin de Peso Balanza
						if (_orden_campo == 5) {
							$("#td_pesoinicio_fecha_" + _item).html(_valor.substring(0, 10));
							$("#td_pesofin_fecha_" + _item).html(_valor.substring(0, 10));
						}

						// Actualizando Peso Neto
						if (_orden_campo == 7 || _orden_campo == 8) {
							$("#td_pesoneto_" + _item).html(data.peso_neto);
						}
					}
					else {
						alert("Ocurrió un error al momento de grabar los datos.");
					}

					f_SavingDatos(0);

				}, "json");
		}

		function f_GrabarColor() {
			var item = $("#hd_setcolor_item").val();
			var id_detalle = $("#id_" + item).val();
			var color_x = color_selected;

			// Validando datos
			if (color_x.length == 0) {
				alert("Debe seleccionar un color");

				return;
			}

			// Grabando datos
			$.post("apis/backend.php", { accion: "grabar_ValidacionDatos_SetColor", id_detalle: id_detalle, color: color_x },
				function (data) {
					if (data.estado == 1) {
						// Setea el color seleccionado
						$("#tr_detalle_" + item).css('background-color', color_x);
					}
					else {
						alert("Ourrió un error al momento de grabar el color");
					}

					f_cerrarModal("modal_SetColor");

				}, "json");
		}

		function f_ConfirmarCierre() {
			var arr_pos = '';
			var arr_ids = '';
			var arr_idvalidacion = '';
			var validar_fechas = '';
			var invalidfechas_msg = '';
			var continuar = 0;

			// Recorre las filas visibles
			$("#tbl_detalle tr:visible").filter(function () {
				tr_id = $(this).attr('id').substring(11);

				// // Validando fechas
				// 	validar_fechas = f_ValidarFechas2(tr_id, 6).split('|');

				// 	if (validar_fechas[0] == 1){
				// 		continuar = 1;
				// 	}
				// 	else{
				// 		continuar = 0;
				// 		invalidfechas_msg += '- ' + $(this).find("td").eq(2).text().trim() + ': ' + validar_fechas[1] + '\n';
				// 	}

				// 	validar_fechas = f_ValidarFechas2(tr_id, 4).split('|');

				// 	if (validar_fechas[0] == 1){
				// 		continuar = 1;
				// 	}
				// 	else{
				// 		continuar = 0;
				// 		invalidfechas_msg += '- ' + $(this).find("td").eq(2).text().trim() + ': ' + validar_fechas[1] + '\n';
				// 	}

				// Continua con el bucle
				// if (continuar == 1){
				if ($("#chk_cierre_" + tr_id).prop('checked')) {
					arr_idvalidacion += $("#id_" + tr_id).val() + ', ';

					arr_pos += tr_id + '|';
					arr_ids += $("#id_" + tr_id).val() + '|';
				}
				// }
			});

			// Valida la selección de checkbox
			if (arr_idvalidacion.length == 0) {
				// Validando fechas
				if (invalidfechas_msg.trim().length > 0) {
					alert("Se encontraron registros con las siguientes inconsistencias:\n\n" + invalidfechas_msg);
				}
				else {
					alert("Debe seleccionar al menos un Lote");

					return;
				}
			}
			else {
				arr_idvalidacion = arr_idvalidacion.substring(0, arr_idvalidacion.length - 2);
				arr_pos = arr_pos.substring(0, arr_pos.length - 1);
				arr_ids = arr_ids.substring(0, arr_ids.length - 1);

				$.post("apis/backend.php", { accion: "cierre_PrimerTramo_CierreLotes", arr_idvalidacion: arr_idvalidacion },
					function (data) {
						if (data.estado == 1) {
							// Setea tr cerrados
							var t = 0;

							arr_pos = arr_pos.split('|');
							arr_ids = arr_ids.split('|');

							while (t < arr_pos.length) {
								$("#td_cierre_1_" + arr_pos[t]).html('<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_Reabrir(' + arr_pos[t] + ', ' + arr_ids[t] + ')"><u> Reabrir </u></label>');
								$("#td_cierre_2_" + arr_pos[t]).html(data.cerrado_fechahoraregistro);
								$("#td_cierre_3_" + arr_pos[t]).html(data.cerrado_usuarioregistro);

								// Setea columna de Revertir Código GEL
								$("#td_codigogel_1_" + arr_pos[t]).html('');

								// Setea columnas adicionales
								$("#val_9_" + arr_pos[t]).prop('disabled', true);
								$("#chk_codigogel_1_" + arr_pos[t]).prop('disabled', true);
								$("#chk_codigogel_2_" + arr_pos[t]).prop('disabled', true);

								t++;
							}

							// Actualiza Tickets
							var id_registro = 0;
							var num_ticket = '';

							$.each(data.arr_tickets, function (key, val) {
								id_registro = val.id_registro;
								num_ticket = val.num_ticketbalanza;

								// Actualiza Número de Ticket
								$("#td_numticket_" + id_registro).html(num_ticket);
							});
						}
						else {
							alert("Ocurrió un error al momento de realizar el cierre.");
						}

						// Validando fechas
						if (invalidfechas_msg.trim().length > 0) {
							alert("Se encontraron registros con las siguientes inconsistencias:\n\n" + invalidfechas_msg);
						}

						// f_SetInputDisabled();

					}, "json");
			}
		}

		function f_Reabrir(_item, _id_validacion) {
			$.post("apis/backend.php", { accion: "reabrir_PrimerTramo_CierreLotes", id_validacion: _id_validacion },
				function (data) {
					if (data.estado == 0) {
						alert("Ocurrió un error al momento de reabrir el registro.");

						return;
					}

					if (data.estado == 1) {
						$("#td_cierre_1_" + _item).html('<input id="chk_cierre_' + _item + '" class="form-check-input chk_cierre" type="checkbox" style="transform: scale(1.5);">');
						$("#td_cierre_2_" + _item).html('');
						$("#td_cierre_3_" + _item).html('');

						// Setea columna de Revertir Código GEL
						$("#td_codigogel_1_" + _item).html('<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_RevertirCodigoGel(' + _item + ', ' + _id_validacion + ')"><u> Revertir </u></label>');

						// f_SetInputDisabled();

						// Limpia Número de Ticket
						$("#td_numticket_" + _id_validacion).html('');
					}

				}, "json");
		}

		function f_GenerarCodigoGel() {
			let checkboxes = document.querySelectorAll('.chk_codigogel'); // Selecciona todos los checkboxes con la clase
			let arr_lotes = [];
			let is_Checked = false;
			let is_Checked_x = false;

			checkboxes.forEach(checkbox => {
				is_Checked = false;

				if (checkbox.offsetParent !== null) { // Verifica si el elemento es visible
					let idParts = checkbox.id.split('_'); // Divide el ID por "_"
					let ultimoNumero = idParts.length > 3 ? idParts[3] : null; // Obtiene el último número

					if (ultimoNumero !== null) {
						let hiddenInput = document.getElementById(`id_${ultimoNumero}`); // Busca el input hidden con id="id_X"
						let valorHidden = hiddenInput ? hiddenInput.value : null; // Obtiene su valor si existe

						let marcado = checkbox.checked; // Verifica si el checkbox está marcado
						if (marcado) is_Checked = true; // Si al menos uno está marcado, actualiza la variable

						if (is_Checked) {
							arr_lotes.push({
								id: valorHidden, // Valor del input hidden
								item: ultimoNumero, // Valor del input hidden
								marcado: marcado // Estado del checkbox
							});

							is_Checked_x = true;
						}
					}
				}
			});

			// Validando la selección de Checks
			if (!is_Checked_x) {
				alert("Debe seleccionar al menos un Lote para generar su Código GEL.");

				return;
			}

			// Generando el Código GEL
			$.post("apis/backend.php", { accion: "cierre_CierreLotes_GenerarCodigosGEL", arr_lotes: arr_lotes },
				function (data) {
					if (data.estado == 1) {
						f_LoadResultados();
					}

				}, "json");
		}

		function f_RevertirCodigoGel(_item, _id_validacion) {
			if (!confirm("¿Está seguro de Revertir el Código: " + $("#td_codigogel_2_" + _item).html().trim() + "?")) {
				return;
			}

			$.post("apis/backend.php", { accion: "reabrir_CierreLotes_CodigosGEL", id_validacion: _id_validacion },
				function (data) {
					if (data.estado == 0) {
						alert("Ocurrió un error al momento de reabrir el registro.");

						return;
					}

					if (data.estado == 1) {
						$("#td_codigogel_1_" + _item).html('<input id="chk_codigogel_3_' + _item + '" class="form-check-input chk_codigogel" type="checkbox" style="transform: scale(1.5);">');
						$("#td_codigogel_2_" + _item).html('');
						$("#td_codigogel_3_" + _item).html('');
						$("#td_codigogel_4_" + _item).html('');

						// Setea la columna de Fecha de Llegada
						var _html = '';
						var fecha_llegada = $("#td_fechallegada_" + _item).html().trim();

						_html += '		<div class="d-flex">';
						_html += '			<input id="td_ingreso_fecha_' + _item + '" type="date" class="form-control input_datos_' + _item + '" style="text-align: center; font-size: 14px; max-width: 220px; margin-right: 5px;" value="' + fecha_llegada + '" onchange="f_UpdateDatos(' + _item + ', 5)">';

						_html += '			<input id="td_ingreso_hora_' + _item + '" type="time" class="form-control input_datos_' + _item + '" style="text-align: center; font-size: 14px; max-width: 100px;" value="00:00" onchange="f_UpdateDatos(' + _item + ', 6)" hidden>';
						_html += '  	</div>';

						$("#td_fechallegada_" + _item).html(_html);

						// f_SetInputDisabled();

						// Limpia Número de Ticket
						$("#td_numticket_" + _id_validacion).html('');
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