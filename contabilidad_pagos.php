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

	<link rel="stylesheet" href="<?php echo $url_lims ?>/global/styles.css">

	<title><?php echo $nom_app; ?> | Contabilidad Pagos</title>

	<script type="text/javascript">
		let is_mobile = 0;
		let idmodalidadenvio_selected = 3; // Comercializadora
		let iddestino_selected = 21; // 21: GRUPO EMPRESARIAL LUBRA S.A.C. tbconfig_destinatarios
		let puntodestino_selected = 'OTR.VALLE MOCHE LOTE. VD SEC. VALDIVIA ALTA (190-III)'; // Dirección de GELSAC
		let motivotraslado_selected = "VENTA SUJETA A CONFIRMACIÓN"

		let gb_tipocambio_idregistro = '';
		let gb_tipocambio_compra = '';
		let gb_tipocambio_venta = '';
		let gb_tipocambio_modograbar = '';
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

		.resaltado-movido {
			background-color: #fff3cd !important;
			/* Amarillo suave */
			transition: background-color 1s ease;
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
						<div class="modal-body" style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0px !important;">
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
						<div class="modal-body" style="padding: 0px !important;">

							<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
								<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">
											Fecha de Emisión
										</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_inicio_emision" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
										</div>
										<br><br>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_fin_emision" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
										</div>

									</div>
								</div>
							</div>

							<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;" hidden>
								<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">
											Fecha de Pago (Sin Detracción)
										</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_inicio_sindetraccion" type="date" class="form-control" style="text-align: center; font-size: 14px;">
										</div>
										<br><br>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_fin_sindetraccion" type="date" class="form-control" style="text-align: center; font-size: 14px;">
										</div>

									</div>
								</div>
							</div>

							<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;" hidden>
								<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
									<div class="row" style="padding-left: 10px; padding-right: 10px;">
										<h6 style="font-size: 14px;">
											Fecha de Pago (Detracción)
										</h6>
									</div>

									<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
										<hr style="border-color: #D9D9D9;" />
									</div>

									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_inicio_detraccion" type="date" class="form-control" style="text-align: center; font-size: 14px;">
										</div>
										<br><br>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<input id="fecha_fin_detraccion" type="date" class="form-control" style="text-align: center; font-size: 14px;">
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

												$q_lotes = "SELECT cod_lote
																					FROM valorizacion_compramineral_detalle
																				 WHERE estado = 'A'
																				GROUP BY cod_lote
																				ORDER BY cod_lote DESC";

												if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
													if (mysqli_num_rows($res_lotes) > 0) {
														while ($row_lotes = mysqli_fetch_array($res_lotes)) {
												?>

															<option value="<?php echo $row_lotes["cod_lote"]; ?>"><?php echo $row_lotes["cod_lote"]; ?></option>

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
											<h6 style="font-size: 14px;">Por Cod GEL:</h6>
										</div>
										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
										</div>
										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_lote_gel" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
												<?php

												$q_lotes = "SELECT cod_gel
																					FROM valorizacion_compramineral_detalle
																				 WHERE estado = 'A'
																					 AND cod_gel <> 'pendiente'
																				GROUP BY cod_gel
																				ORDER BY cod_gel DESC";

												if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
													if (mysqli_num_rows($res_lotes) > 0) {
														while ($row_lotes = mysqli_fetch_array($res_lotes)) {
												?>

															<option value="<?php echo $row_lotes["cod_gel"]; ?>"><?php echo $row_lotes["cod_gel"]; ?></option>

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
								<br><br>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<!-- <button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
				              <b>Exportar a Excel</b>
				            </button> -->
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
										<div class="col-md-9 col-sm-9 col-xs-12">
											<div class="d-flex">
												<div class="d-flex flex-fill">
													<h5>Resumen </h5>

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

										<div class="col-md-3 col-sm-3 col-xs-12">
											<div class="d-flex justify-content-end">
												<button id="btn_AddComprobante" type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AddComprobantePago();">+ Generar Comprobante</button>
											</div>
										</div>
									</div>
								</div>



								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;" />
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
									<div class="table-container" style="margin-top: 5px; overflow-x: scroll; width: 100%; height: 500px; margin-bottom: 20px;">
										<table class="table table-bordered table-hover">
											<thead>
												<tr style="font-size: 12px;">
													<th colspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
														N°
													</th>

													<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
														Información Comprobante
													</th>

													<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Información Proveedor
													</th>

													<th colspan="9" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Información Valorización
													</th>

													<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Información Pago Neto
													</th>

													<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Información Pago Detracción
													</th>

													<th colspan="10" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
														Aprobaciones
													</th>

													<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px; border-top-right-radius: 15px; min-width: 250px;">
														Observaciones
													</th>
												</tr>

												<tr style="font-size: 12px;">
													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
														Serie
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Número
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Fecha Emisión
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
														RUC
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
														Razón Social
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Aprobación
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 70px;">
														N°
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Lote
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Cod. GEL
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Elemento
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Sub Total
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Valor Neto Mineral
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														I.G.V.
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Valor Total
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Total por Pagar<br>($USD)
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Total Pagado<br>($USD)
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Saldo<br>($USD)
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Estado
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
														%
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Total por Pagar<br>($USD)
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 60px;">
														Tipo<br>Cambio
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Total por Pagar<br>(S/)
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Total Pagado<br>(S/)
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
														Saldo<br>(S/)
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Estado
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Contabilidad
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Registro
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Comercial
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Registro
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Documentaria
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Registro
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														DJVM
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Registro
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Firma Proveedor
													</th>

													<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
														Registro
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

	</div>

	<input id="hd_id_moneda" type="hidden">

	<!-- Ventanas modales -->
	<div class="modal fade modal-dialog-scrollable" id="modal_admincomprobantes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_admincomprobantesLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_admincomprobantesLabel">Generar Comprobante</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">

					<div class="row" style="padding: 5px;" hidden>
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;"></div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Moneda:
						</div>

						<div class="col-md-4 col-sm-4 col-xs-12" style="margin-left: -20px;">
							<select id="comprobante_moneda" class="form-select" style="font-size: 14px;">
								<option value="">Cargando monedas...</option>
							</select>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Fecha Emisión
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="comprobante_fecha" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_GetTipoCambio();">
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Serie y Número:
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
							<input id="comprobante_serie" type="text" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Serie">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
							<input id="comprobante_numero" type="text" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Comprobante">
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Proveedor:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<select id="comprobante_proveedor" class="form-select" data-placeholder="Elija una opción..." style="font-size: 14px;">
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

											<option value="<?php echo $row_lista["Id"] ?>"><?php echo $row_lista["documento"] . ' - ' . $row_lista["razon_social"] ?></option>

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
							Valorización:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
							<select id="comprobante_valorizacion" class="form-select" data-placeholder="Elija opciones..." style="font-size: 14px;">
							</select>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							% Detracción:
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
							<input id="comprobante_porc_detraccion" type="number" class="form-control" style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="% Detracción">
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
							Tipo Cambio:
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
							<input id="comprobante_tipocambio" type="number" class="form-control" style="text-align: center; font-size: 14px;" placeholder="Tipo cambio (Venta)">

							<a href="https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias" target="_blank" rel="noopener noreferrer" style="font-size: 12px;">
								Consulta Sunat
								<i class="bi bi-search ms-1" aria-hidden="true"></i>
							</a>
						</div>

						<div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px; text-align: center;">
							<img src="<?php echo $img_tipocambio ?>" style="margin-top: -7px; width: 40px; cursor: pointer;" title="Configurar Tipo de Cambio" onclick="f_AdminTipoCambio();">
						</div>
					</div>
				</div>

				<div class="modal-footer" style="margin-top: -10px;">
					<div id="wt_grabarcomprobante" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<input id="modograbar_comprobante" type="hidden">

					<button type="button" class="btn btn-secondary wt_grabarcomprobante_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarcomprobante_button" style="font-size: 14px;" onclick="f_GrabarComprobante();">Grabar Comprobante</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_registraradelanto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_registraradelantoLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<div>
						<div class="d-flex">
							<h6>Registro de Pagos para: </h6>
							<h6 id="lbl_titulopagos" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h6>
						</div>
					</div>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="d-flex" style="padding: 5px;">
						<div class="col-md-2 col-sm-2 col-xs-2" style="padding: 5px; font-size: 14px;">
							Información de Lote(s):
						</div>

						<div class="col-md-5 col-sm-5 col-xs-5">
							<input id="ins_cod_lote" type="text" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center; font-weight: bold;" disabled>
						</div>

						<div class="col-md-5 col-sm-5 col-xs-5">
							<input id="ins_cod_gel" type="text" class="form-control col-md-12 col-xs-12" style="margin-left: 5px; font-size: 14px; text-align: center; font-weight: bold;" disabled>
						</div>
					</div>

					<div class="d-flex" style="padding: 5px;">
						<div class="col-md-2 col-sm-2 col-xs-2" style="padding: 5px; font-size: 14px;">
							Proveedor:
						</div>
						<div class="col-md-10 col-sm-10 col-xs-10">
							<input id="ins_proveedor" type="text" class="form-control col-md-12 col-xs-12" style="font-size: 14px; font-weight: bold;" disabled>
							<input hidden id="ins_id_proveedor" type="text" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center; font-weight: bold;" disabled>
						</div>
					</div>

					<hr style="margin-bottom: 10px;">

					<!-- Resumen financiero -->
					<div id="resumen_financiero" class="row g-3 text-start">

						<!-- VALOR TOTAL (USD) -->
						<div class="col-lg-4">
							<div class="card shadow-sm h-100">
								<div class="card-body p-3" style="background-color: #D9D9D9">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="mb-0">Valor Total <small class="text-muted">($USD)</small></h6>
										<span id="badge_estado_venta" class="badge bg-warning">Pendiente</span>
									</div>

									<hr style="margin-bottom: -5px;">

									<div id="lbl_totalventa" class="display-6 fw-bold my-2">$ 0.00</div>

									<div class="small text-muted">Por pagar</div>
									<div class="d-flex justify-content-between align-items-end">
										<div id="lbl_por_pagar_venta" class="fw-semibold">$ 0.00</div>
										<div id="lbl_pct_venta" class="text-muted small">0%</div>
									</div>
									<div class="progress mt-1" style="height: 8px;">
										<div id="pb_venta" class="progress-bar" role="progressbar" style="width:0%"></div>
									</div>

									<!-- Mantener TUS inputs (compatibilidad con lógica existente) -->
									<input id="ins_totalventa" type="text" class="visually-hidden" disabled>
									<input id="ins_por_pagar_venta" type="text" class="visually-hidden" disabled>
								</div>
							</div>
						</div>

						<!-- NETO (USD) = Valor Total - Detracción (10%) -->
						<div class="col-lg-4">
							<div class="card shadow-sm h-100">
								<div class="card-body p-3" style="background-color: #C9DFF2">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="mb-0">Neto <small class="text-muted">($USD)</small></h6>
										<span id="badge_estado_neto" class="badge bg-warning">Pendiente</span>
									</div>

									<hr style="margin-bottom: -5px;">

									<div id="lbl_sin_detraccion" class="display-6 fw-bold my-2">$ 0.00</div>

									<div class="small text-muted">Por pagar</div>
									<div class="d-flex justify-content-between align-items-end">
										<div id="lbl_por_pagar_sin_detraccion" class="fw-semibold">$ 0.00</div>
										<div id="lbl_pct_neto" class="text-muted small">0%</div>
									</div>
									<div class="progress mt-1" style="height: 8px;">
										<div id="pb_neto" class="progress-bar" role="progressbar" style="width:0%"></div>
									</div>

									<!-- Mantener TUS inputs -->
									<input id="ins_sin_detraccion" type="text" class="visually-hidden" disabled>
									<input id="ins_por_pagar_sin_detraccion" type="text" class="visually-hidden" disabled>
								</div>
							</div>
						</div>

						<!-- DETRACCIÓN (S/) = (10% de USD) x T.C. -->
						<div class="col-lg-4">
							<div class="card shadow-sm h-100">
								<div class="card-body p-3" style="background-color: #DEEFE7">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="mb-0">Detracción <small class="text-muted">(S/)</small></h6>
										<span id="badge_estado_detrac" class="badge bg-warning">Pendiente</span>
									</div>

									<hr style="margin-bottom: -5px;">

									<!-- Cabecera con desglose -->
									<div class="row">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="small text-muted mt-2">(<label id="lbl_porcdetraccion"></label>% de Valor Total):</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="small text-muted mt-2">Tipo de cambio:</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div id="lbl_detraccion_usd" class="fw-semibold">$ 0.00</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-6">
											<div id="lbl_tc" class="fw-semibold">0.0000</div>
										</div>
									</div>

									<div class="small text-muted mt-2">Total S/):</div>
									<div id="lbl_detraccion_soles" class="h4 fw-bold text-success mb-2">S/ 0.00</div>

									<div class="small text-muted">Por pagar</div>
									<div class="d-flex justify-content-between align-items-end">
										<div id="lbl_por_pagar_detraccion" class="fw-semibold">S/ 0.00</div>
										<div id="lbl_pct_detrac" class="text-muted small">0%</div>
									</div>
									<div class="progress mt-1" style="height: 8px;">
										<div id="pb_detrac" class="progress-bar bg-success" role="progressbar" style="width:0%"></div>
									</div>

									<div id="lbl_det_formula" class="small text-muted mt-2" hidden></div>

									<!-- Mantener TUS inputs -->
									<input id="ins_detraccion" type="text" class="visually-hidden" disabled>
									<input id="ins_por_pagar_detraccion" type="text" class="visually-hidden" disabled>
								</div>
							</div>
						</div>
					</div>

					<hr>

					<div class="d-flex justify-content-center" style="padding: 5px; margin-top: 10px;">
						<button type="button" class="btn btn-primary" style="font-size: 14px; width: 100%;" onclick="f_AddPago('N');">+ Nuevo Pago</button>
					</div>

					<div class="d-flex justify-content-center" style="padding: 5px;">
						<div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
							<table class="table table-bordered table-hover mb-0" style="white-space: nowrap;">
								<thead>
									<tr style="font-size: 12px;">
										<th colspan="14" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
											Detalle de Pagos
										</th>
									</tr>

									<tr style="font-size: 12px;">
										<th colspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											Item
										</th>

										<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											Registro
										</th>

										<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											Información Pago
										</th>

										<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											Información Bancaria
										</th>

										<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 120px;">
											Monto
										</th>

										<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 80px;">
											T.C
										</th>

										<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											N°<br>Operación
										</th>

										<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
											Observación
										</th>

										<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
											Adjunto(s)
										</th>
									</tr>

									<tr style="font-size: 12px;">
										<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
											Fecha
										</th>

										<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											Medio pago
										</th>

										<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
											Cuenta Origen<br>(GEL)
										</th>

										<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
											Cuenta Destino<br>(Proveedor)
										</th>
									</tr>
								</thead>

								<tbody id="tbl_RegistrosPago">
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<input id="hd_idregistro" type="hidden">

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_addpago" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addpagoLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" style="margin-top: 5%;">
			<div class="modal-content" style="border: solid; border-width: 1px; border-color: #E6E9ED;">
				<div class="modal-header" style="background-color: #198754; color: #ffffff;">
					<div>
						<div class="row">
							<h6 id="modal_addpagoLabel" class="modal-title fs-5">Registrar Pago </h6>
						</div>
					</div>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">

					<div class="d-flex align-items-stretch" style="gap: 5px;">
						<!-- Banco GEL -->
						<div class="flex-fill" style="max-width: 49%;">
							<div class="card shadow-sm mb-2 h-100">
								<div class="card-body">
									<label class="form-label" style="font-size: 14px;">Banco GEL:</label>
									<select id="pago_entidadbancaria_1" class="form-select mb-2" style="font-size: 14px;">
										<option value="">Elija una opción...</option>
										<?php
										$q_bancos = "SELECT id, descripcion FROM tb_bancos WHERE estado = 'A' ORDER BY descripcion";
										$res_bancos = mysqli_query($enlace, $q_bancos);
										while ($row = mysqli_fetch_array($res_bancos)) {
											echo '<option value="' . $row["id"] . '">' . $row["descripcion"] . '</option>';
										}
										?>
									</select>

									<label class="form-label" style="font-size: 14px;">Cuenta GEL:</label>
									<select id="pago_entidadbancaria_cuentas_1" class="form-select" style="font-size: 14px;">
										<option value="">Seleccione cuenta</option>
									</select>
								</div>
							</div>
						</div>

						<!-- Flecha centrada verticalmente -->
						<div class="d-flex justify-content-center align-items-center" style="width: 40px;">
							<div style="background-color: #198754; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
								<i class="bi bi-arrow-right" style="font-size: 14px;"></i>
							</div>
						</div>

						<!-- Banco Proveedor -->
						<div class="flex-fill" style="max-width: 49%;">
							<div class="card shadow-sm mb-2 h-100">
								<div class="card-body">
									<label class="form-label" style="font-size: 14px;">Banco Proveedor:</label>
									<select id="pago_entidadbancaria_2" class="form-select mb-2" style="font-size: 14px;">
										<option value="">Elija una opción...</option>
									</select>

									<label class="form-label" style="font-size: 14px;">Cuenta Proveedor:</label>
									<select id="pago_entidadbancaria_cuentas_2" class="form-select" style="font-size: 14px;">
										<option value="">Seleccione cuenta</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<hr style="margin-bottom: 10px;">

					<div class="d-flex" style="padding: 5px;" style="display: none;">
						<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 5px; font-size: 14px;">
							Fecha Pago:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12">
							<input id="pago_fecha" type="date" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center; font-weight: bold">
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12">
						</div>
					</div>

					<div class="d-flex" style="padding: 5px;">
						<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 5px; font-size: 14px;">
							Por pagar:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12">
							<input id="pago_saldo" type="text" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center; font-weight: bold; color: green" disabled>
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12">
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 5px; font-size: 14px;">
							A pagar <label id="lbl_pago_simbolo_moneda" style="font-weight: bold"></label>:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12">
							<input id="pago_monto" type="number" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center; font-weight: bold">
						</div>
					</div>

					<div id="div_tipocambio" style="padding: 5px" class="d-none">
						<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; font-size: 14px;">
							Tipo de Cambio:
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4">
							<input id="pago_tipocambio" type="number" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center; font-weight: bold; box-shadow: 0px 0px 8px #198754;" disabled>
						</div>
					</div>

					<div class="d-flex" style="padding: 5px;">
						<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 5px; font-size: 14px;">
							Medio Pago:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12">
							<select id="pago_medio_pago" class="form-select" style="text-align: left; font-size: 14px;">
								<option selected value="">Elija una opción...</option>

								<?php

								$q_datos = "SELECT Id,
																		 descripcion,
																		 is_efectivo
																FROM tbconfig_mediospago
															 WHERE estado = 'A'
                               AND is_comprobante_pago = 1
															ORDER BY orden_reportecontable";

								if ($res_datos = mysqli_query($enlace, $q_datos)) {
									if (mysqli_num_rows($res_datos) > 0) {
										while ($row_datos = mysqli_fetch_array($res_datos)) {
								?>
											<option data-isefectivo="<?php echo $row_datos["is_efectivo"]; ?>" value="<?php echo $row_datos["Id"] ?>"><?php echo $row_datos["descripcion"] ?></option>
								<?php
										}
									}
								}

								?>

							</select>
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12">
						</div>

						<div class="col-md-2 col-sm-2 col-xs-2" style="padding: 5px; font-size: 14px;">
							N° Operación:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-3">
							<input id="pago_numoperacion" type="text" class="form-control col-md-12 col-xs-12" style="font-size: 14px; text-align: center; text-transform: uppercase;">
						</div>
					</div>

					<div class="d-flex" style="padding: 5px;">
						<div class="col-md-2 col-sm-2 col-xs-2" style="padding: 5px; font-size: 14px;">
							Observación:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-8">
							<textarea id="pago_observacion" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase;" rows="2"></textarea>
						</div>
					</div>

				</div>

				<input id="modo_grabarregistropago" type="hidden">
				<input id="id_registropago" type="hidden">

				<div class="modal-footer">
					<div id="wt_grabarpago" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabarpago_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarpago_button" style="font-size: 14px;" onclick="f_GrabarPago();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_admintipocambio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_admintipocambioLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content" style="border: solid; border-width: 1px; border-color: #E6E9ED;">
				<div class="modal-header py-2">
					<h5 class="modal-title" id="modal_admintipocambioLabel">Nuevo Tipo de Cambio</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
				<div class="modal-body">
					<div class="row mb-2">
						<div class="col-md-1">
						</div>
						<div class="col-md-4">Fecha:</div>
						<div class="col-md-6">
							<input id="tipocambio_fecha" type="date" class="form-control form-control-sm text-center" disabled>
						</div>
					</div>
					<div class="row mb-2">
						<div class="col-md-1">
						</div>
						<div class="col-md-4">Moneda Base:</div>
						<div class="col-md-6">
							<select id="tipocambio_moneda" class="form-select form-select-sm" disabled>
								<?php

								$q_monedas = "SELECT Id,
	                                       CONCAT('(', abv, ') ', descripcion) AS DESCRIPCION
	                                  FROM tbconfig_monedas
	                                 WHERE estado = 'A'
	                                ORDER BY is_default_valorizaciones DESC";

								if ($res_monedas = mysqli_query($enlace, $q_monedas)) {
									if (mysqli_num_rows($res_monedas) > 0) {
										while ($row_monedas = mysqli_fetch_array($res_monedas)) {
								?>

											<option value="<?php echo $row_monedas["Id"] ?>" style="font-size: 14px;"><?php echo $row_monedas["DESCRIPCION"] ?></option>

								<?php
										}
									}
								}

								?>
							</select>
						</div>
					</div>

					<div class="row mb-2">
						<div class="col-md-1">
						</div>
						<div class="col-md-4">TC Compra:</div>
						<div class="col-md-3"><input id="tipocambio_compra" type="number" step="0.0001" class="form-control form-control-sm text-center"></div>
					</div>
					<div class="row mb-2">
						<div class="col-md-1">
						</div>
						<div class="col-md-4">TC Venta:</div>
						<div class="col-md-3"><input id="tipocambio_venta" type="number" step="0.0001" class="form-control form-control-sm text-center"></div>
					</div>

					<input id="hd_idtipocambio" type="hidden">
					<input id="hd_tipocambio_modograbar" type="hidden">
				</div>
				<div class="modal-footer">
					<div id="wt_admintipocambio" class="text-center mt-2" style="font-size: 12px; display: none;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>
					<button type="button" class="btn btn-secondary wt_admintipocambio_button" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary wt_admintipocambio_button" onclick="f_GrabarTipoCambio();">Grabar</button>
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
		function f_Init() {
			// Genera menús
			f_GetMenuPrincipal();

			// Titulo de Pantalla
			$("#nv_titulo").html('| Contabilidad Pagos');

			// Carga el detalle de información
			f_LoadResultados();
		}
	</script>

	<script type="text/javascript">
		const dropdownParentComprobante = $('#modal_admincomprobantes');

		$(document).ready(function() {
			$('#comprobante_valorizacion, #comprobante_proveedor').select2({
				theme: 'bootstrap-5',
				dropdownParent: dropdownParentComprobante,
				width: '100%',
				placeholder: "Elija opciones...",
				allowClear: true

			});

			$('#filtro_lote, #filtro_lote_gel').select2({
				theme: 'bootstrap-5',
				width: '100%',
				placeholder: "Elija opciones...",
				allowClear: true

			});

			// Al cambiar de banco
			$("#pago_entidadbancaria_1").on("change", function() {
				const id_banco = $(this).val();
				const id_moneda = $("#hd_id_moneda").val();
				const $combo_cuentas = $("#pago_entidadbancaria_cuentas_1");

				if (id_banco) {
					// Consultar y cargar cuentas
					$.post("apis/backend.php", {
						accion: "get_CuentasBancariasPorBanco",
						id_banco: id_banco,
						id_moneda: id_moneda
					}, function(data) {
						$combo_cuentas.html('<option value="">Elija una opción...</option>');

						if (data.estado == 1) {
							data.cuentas.forEach(function(item) {
								$combo_cuentas.append(`<option value="${item.id}" data-isdetraccion="${item.is_detraccion}" data-idmoneda="${item.id_moneda}" data-simbolomoneda="${item.simbolo_moneda}">(${item.moneda_abv}) ${item.num_cuenta}</option>`);
							});

						} else {
							$combo_cuentas.html('<option value="">Sin cuentas disponibles</option>');
						}
					}, "json");
				} else {
					// Si no hay banco seleccionado, ocultar cuentas
					$combo_cuentas.html('<option value="">Elija una opción...</option>');
				}

			});

			// Al cambiar de banco
			$("#pago_entidadbancaria_2").on("change", function() {
				const id_banco = $(this).val();
				const id_proveedor = $('#ins_id_proveedor').val();
				const $combo_cuentas = $("#pago_entidadbancaria_cuentas_2");

				$("#pago_saldo").val('');

				if (id_banco) {
					// Consultar y cargar cuentas
					$.post("apis/backend.php", {
						accion: "get_CuentasBancariasPorProveedor",
						id_banco: id_banco,
						id_proveedor: id_proveedor
					}, function(data) {
						$combo_cuentas.html('<option value="">Elija una opción...</option>');

						if (data.estado == 1) {
							data.cuentas.forEach(function(item) {
								$combo_cuentas.append(`<option value="${item.id}" data-isdetraccion="${item.is_detraccion}" data-idmoneda="${item.id_moneda}" data-simbolomoneda="${item.simbolo_moneda}">(${item.moneda_abv}) ${item.nro_cuenta} | CCI: ${((item.CCI.trim().length == 0) ? '---' : item.CCI)}</option>`);
							});

						} else {
							$combo_cuentas.html('<option value="">Sin cuentas disponibles</option>');
						}
					}, "json");
				} else {
					// Si no hay banco seleccionado, ocultar cuentas
					$combo_cuentas.html('<option value="">Elija una opción...</option>');
				}

			});

			$('#modal_registraradelanto').on('hidden.bs.modal', function() {
				f_LoadResultados();
			});

			$('#comprobante_moneda').on('change', function() {
				const id_moneda = $(this).val();
				const id_proveedor = $('#comprobante_proveedor').val();

				if (id_proveedor) {
					f_CargarValorizaciones(id_proveedor, id_moneda);
				}
			});

			$('#comprobante_proveedor').on('change', function() {
				const id_proveedor = $(this).val();
				const id_moneda = $('#comprobante_moneda').val();
				$('#comprobante_valorizacion').val(null).trigger('change');
				f_CargarValorizaciones(id_proveedor, id_moneda);
			});

			$("#pago_entidadbancaria_cuentas_1").on("change", function() {
				const $opt = $("option:selected", this);
				const simbolo_moneda = $opt.data("simbolomoneda");

				if (simbolo_moneda) {
					$("#lbl_pago_simbolo_moneda").text("(" + simbolo_moneda + ")");
				} else {
					$("#lbl_pago_simbolo_moneda").text("");
				}

				// f_ValidarTipoCambioPorMoneda();

			});

			$("#pago_entidadbancaria_cuentas_2").on("change", function() {
				// f_ValidarTipoCambioPorMoneda();
				$("#pago_saldo").val('');

				const ins_detraccion = $('#ins_por_pagar_detraccion').val();
				const ins_sin_detraccion = $('#ins_por_pagar_sin_detraccion').val();

				const $opt = $("option:selected", this);
				const is_detraccion = $opt.data("isdetraccion");
				const id_moneda_cuenta_prov = $opt.data("idmoneda"); // 1 = Soles, 2 = Dólares

				// Si es SOLES o DETRACCIÓN
				if (id_moneda_cuenta_prov == 1 || is_detraccion == 1) {
					// Fija filtro a DÓLARES
					$("#hd_id_moneda").val(1);
				} else {
					// Restablece filtro a la misma moneda de la cuenta proveedor
					$("#hd_id_moneda").val(id_moneda_cuenta_prov);
				}

				// Validar que la opción tenga el atributo definido
				if (typeof is_detraccion !== "undefined") {
					if (is_detraccion == 1) {
						$("#pago_saldo").val(ins_detraccion);
					} else {
						$("#pago_saldo").val(ins_sin_detraccion);
					}

					$("#pago_monto").val($("#pago_saldo").val().replace(/,/g, ''));
				} else {
					// No hacer nada si la opción es inválida (ej. "Elija una opción...")
					$("#pago_saldo").val('');
					$("#pago_monto").val('');
				}
			});


		});
	</script>

	<!-- Funciones Principales -->
	<script type="text/javascript">
		let data_cuenta_valorizacion = ''

		function f_LoadResultados() {
			var _html = '';

			var fecha_inicio_emision = $("#fecha_inicio_emision").val();
			var fecha_fin_emision = $("#fecha_fin_emision").val();
			var fecha_inicio_sindetraccion = $("#fecha_inicio_sindetraccion").val();
			var fecha_fin_sindetraccion = $("#fecha_fin_sindetraccion").val();
			var fecha_inicio_detraccion = $("#fecha_inicio_detraccion").val();
			var fecha_fin_detraccion = $("#fecha_fin_detraccion").val();
			var filtro_lote = $("#filtro_lote").val();
			var filtro_lote_gel = $("#filtro_lote_gel").val();

			f_LoadingResumen(1);

			$("#tbl_detalle").html('');

			$.post("apis/backend.php", {
					accion: "get_ComprobantePago_ListaValorizacion",
					fecha_inicio_emision: fecha_inicio_emision,
					fecha_fin_emision: fecha_fin_emision,
					fecha_inicio_sindetraccion: fecha_inicio_sindetraccion,
					fecha_fin_sindetraccion: fecha_fin_sindetraccion,
					fecha_inicio_detraccion: fecha_inicio_detraccion,
					fecha_fin_detraccion: fecha_fin_detraccion,
					filtro_lote: filtro_lote,
					filtro_lote_gel: filtro_lote_gel,
					is_contabilidad: 1
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_detalle").html(data.html);
					}

					f_LoadingResumen(0);

				}, "json");
		};

		function f_AddComprobantePago(_is_edit, _comprobante_fecha, _comprobante_serie, _comprobante_numero, _comprobante_valorizacion) {
			// Seteando variables hidden
			$("#modograbar_comprobante").val(((_is_edit == 1) ? 'E' : 'N'));

			// Seteando objetos
			f_LoadMonedas();

			$("#comprobante_fecha").val(((_is_edit == 1) ? _comprobante_fecha : '<?php echo $g_date ?>'));
			$("#comprobante_serie").val(((_is_edit == 1) ? _comprobante_serie : ''));
			$("#comprobante_numero").val(((_is_edit == 1) ? _comprobante_numero : ''));
			$("#comprobante_proveedor").val('').trigger('change');
			$("#comprobante_valorizacion").val(((_is_edit == 1) ? _comprobante_valorizacion : '')).trigger('change');
			$("#comprobante_porc_detraccion").val(10);

			// Obtener Tipo de Cambio
			f_GetTipoCambio();

			f_OpenModal('modal_admincomprobantes');

		};

		function f_CargarValorizaciones(id_proveedor = '', id_moneda = '') {
			const $combo = $('#comprobante_valorizacion');

			$combo.html('<option value="">Cargando valorizaciones...</option>');

			$.post('apis/backend.php', {
				accion: 'get_ValorizacionCompra_Lista',
				id_proveedor: id_proveedor,
				id_moneda: id_moneda
			}, function(data) {
				if (data.estado == 1) {
					let html = '';

					// Si hay registros
					if (data.registros && data.registros.length > 0) {
						data.registros.forEach(item => {
							// Usa los campos REALES que vienen del backend
							const correlativo = item.correlativo || '';
							const procedencia = item.procedencia || '';
							const concesion = item.concesion || '';
							const info = item.info_valorizacion || `${correlativo} | ${procedencia} | ${concesion}`;

							// Si quieres mostrar el ID de la respuesta
							const valorizacionId = item.id_valorizacion || '';

							html += `<option value="${valorizacionId}">${info}</option>`;
						});
					} else {
						html = '<option value="">No hay valorizaciones disponibles</option>';
					}

					$combo.html(html).val(null).trigger('change');
				} else {
					$combo.html('<option value="">No hay valorizaciones disponibles</option>');
				}
			}, 'json');
		}

		function f_ConfirmarPago_ComprobantePago(_id_registro, _lote, _cod_lote, _proveedor, _id_proveedor, _serie, _numero, _total, _total_detraccion, _total_detraccion_soles, _total_sin_detraccion, _total_ingresado_detraccion, _total_ingresado_sin_detraccion, _tipo_cambio, _id_moneda, _porc_detraccion) {
			// Seteando 
			data_cuenta_valorizacion = '';
			$("#hd_idregistro").val(_id_registro);
			$("#hd_id_moneda").val(_id_moneda || ''); // guarda la moneda del comprobante


			var total_pagado_venta = _total - ((_total_ingresado_detraccion / _tipo_cambio) + _total_ingresado_sin_detraccion);
			// var total_pagado_detraccion = (parseFloat(_total_detraccion) - parseFloat(_total_ingresado_detraccion)) * parseFloat(_tipo_cambio) ;
			var total_pagado_detraccion = parseFloat(_total_detraccion_soles) - parseFloat(_total_ingresado_detraccion);
			var total_pagado_sin_detraccion = _total_sin_detraccion - _total_ingresado_sin_detraccion;

			// Validando si ya se pagó toda la detracción
			if (total_pagado_detraccion == 0) {
				total_pagado_venta = total_pagado_sin_detraccion;
			}

			// Setea título de ventana
			$("#lbl_titulopagos").html(_serie + "-" + _numero);
			$("#ins_cod_lote").val(_lote);
			$("#ins_cod_gel").val(_cod_lote);
			$("#ins_proveedor").val(_proveedor);
			$("#ins_id_proveedor").val(_id_proveedor);
			f_CargarBancosProveedor(_id_proveedor);

			// Completa datos
			$("#ins_totalventa").val(f_RedondearDecimales(_total, 2));
			$("#ins_detraccion").val(f_RedondearDecimales(f_FormatEntero(_total_detraccion_soles, 2), 2));
			$("#ins_sin_detraccion").val(f_RedondearDecimales(_total_sin_detraccion, 2));
			$("#ins_por_pagar_venta").val(f_RedondearDecimales(total_pagado_venta, 2));
			$("#ins_por_pagar_detraccion").val(f_RedondearDecimales(f_FormatEntero(total_pagado_detraccion, 2), 2));
			$("#ins_por_pagar_sin_detraccion").val(f_RedondearDecimales(total_pagado_sin_detraccion, 2));
			$("#lbl_porcdetraccion").html(_porc_detraccion);

			f_RenderResumenPagos();

			// $("#hd_tipo_cambio").remove(); // limpia si existe
			// $("body").append('<input id="hd_tipo_cambio" type="hidden" value="'+ _tipo_cambio +'">');

			$("#pago_tipocambio").val(_tipo_cambio);

			// Cargar pagos
			f_LoadDetallePagos();

			// Abrir modal
			f_OpenModal('modal_registraradelanto');
		}

		function f_AddPago(_modo, _id_registropago, _fecha_pago, _id_mediopago, _id_entidadbancaria, _id_entidadbancaria_cuenta, _monto, _tipocambio, _numero_operacion, _observacion) {
			$("#modo_grabarregistropago").val(_modo);

			// Seteando Títulos
			if (_modo == 'N') {
				$("#modal_addpagoLabel").html('Nuevo Pago');
			} else {
				$("#modal_addpagoLabel").html('Editar Pago');
			}

			// Validando saldo disponible
			if (_modo != 'N') {
				// Completa los datos
				$("#id_registropago").val(_id_registropago);

				$("#pago_fecha").val(_fecha_pago);
				$("#pago_medio_pago").val(_id_mediopago);
				$("#pago_entidadbancaria").val(_id_entidadbancaria);
				$("#pago_entidadbancaria_cuentas").val(_id_entidadbancaria_cuenta);
				$("#pago_saldo").val('aasd');
				$("#pago_monto").val(f_RedondearDecimales(parseFloat(_pago_monto), 2));
				$("#pago_numoperacion").val(_numero_operacion);
				// $("#pago_tipocambio").val(_tipocambio);
				$("#pago_observacion").val(_observacion);

			} else {
				$("#id_registropago").val(0);

				$("#pago_fecha").val('<?php echo $g_date ?>');
				$("#pago_medio_pago").val('');
				$("#pago_entidadbancaria_1").val('');
				$("#pago_entidadbancaria_cuentas_1")
					.empty()
					.append('<option value="">Seleccione cuenta</option>')
					.val('');
				$("#pago_entidadbancaria_2").val('');
				$("#pago_entidadbancaria_cuentas_2")
					.empty()
					.append('<option value="">Seleccione cuenta</option>')
					.val('');
				$("#pago_saldo").val('');
				$("#pago_monto").val('');
				$("#pago_numoperacion").val('');
				// $("#pago_tipocambio").val('');
				$("#pago_observacion").val('');
			}

			if (data_cuenta_valorizacion) {
				// 1. Definir valores clave
				const id_banco_a_setear = data_cuenta_valorizacion.id_banco;
				const id_proveedor = data_cuenta_valorizacion.id_proveedor;
				const id_cuenta_a_setear = data_cuenta_valorizacion.id_cuentabancaria;

				// 2. Definir el SELECT de Cuentas (EL PROBLEMA)
				const $combo_cuentas = $("#pago_entidadbancaria_cuentas_2");

				// 3. SETEAR EL SELECT DEL BANCO
				$("#pago_entidadbancaria_2").val(id_banco_a_setear);

				// 4. Consultar y cargar cuentas mediante AJAX
				$.post("apis/backend.php", {
					accion: "get_CuentasBancariasPorProveedor",
					id_banco: id_banco_a_setear,
					id_proveedor: id_proveedor
				}, function(data) {

					// Limpiar y añadir opción inicial al combo de Cuentas (pago_entidadbancaria_cuentas_2)
					$combo_cuentas.html('<option value="">Elija una opción...</option>');

					if (data.estado == 1) {
						console.log("Cuentas bancarias cargadas. Intentando setear:", id_cuenta_a_setear);

						// POBLAR EL SELECT: Añadir todas las opciones
						data.cuentas.forEach(function(item) {
							const cci_info = (item.CCI && item.CCI.trim().length > 0) ? ` | CCI: ${item.CCI}` : '';
							$combo_cuentas.append(
								`<option value="${item.id}" data-isdetraccion="${item.is_detraccion}" data-idmoneda="${item.id_moneda}" data-simbolomoneda="${item.simbolo_moneda}">(${item.moneda_abv}) ${item.nro_cuenta}${cci_info}</option>`
							);
						});

						// SETEAR EL VALOR: Se ejecuta solo DESPUÉS de que todas las opciones están cargadas
						$combo_cuentas.val(id_cuenta_a_setear);

						// (Opcional) Agrega este console.log para verificar si el valor se seleccionó correctamente
						console.log("Valor final seleccionado:", $combo_cuentas.val());
						const porPagarNeto = toNum($('#ins_por_pagar_sin_detraccion').val()); // USD
						console.log("Por pagar neto", porPagarNeto);
						$('#pago_saldo').val(fmtUSD(porPagarNeto));

					} else {
						// Si no hay cuentas, limpiar el combo
						$combo_cuentas.html('<option value="">Sin cuentas disponibles</option>');
						$combo_cuentas.val(''); // Asegurar que no quede ningún valor residual
					}

					f_OpenModal('modal_addpago');
					return;
				}, "json");
			}


			// Seteando Bancos
			if (<?php echo $_SESSION['is_compramineral_pagodetracciones'] ?> == 1) {
				// $("#pago_entidadbancaria_1").val(3).trigger('change');
				$("#pago_entidadbancaria_2").val(3).trigger('change');

				// $("#pago_entidadbancaria_1").prop('disabled', true);
				$("#pago_entidadbancaria_2").prop('disabled', true);
			}

			// Abriendo modal
			f_OpenModal('modal_addpago');
		}

		function f_LoadDetallePagos() {
			var id_recepcion = $("#hd_idregistro").val();
			var total_venta = parseFloat($("#ins_totalventa").val()) || 0;

			$("#tbl_RegistrosPago").html('Cargando...');

			$.post("apis/backend.php", {
					accion: "get_ComprobantePago_RegistroPagos",
					id_comprobante_pago: id_recepcion
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_RegistrosPago").html(data.html);
					} else {
						$("#tbl_RegistrosPago").html('<tr><td colspan="11" style="text-align: center; font-size: 12px;">Aún no se han registrado pagos</td></tr>');
					}

					$.post("apis/backend.php", {
							accion: "get_info_cuenta_banco_valorizacion",
							id_comprobante_pago: id_recepcion
						},
						function(r) {
							console.log("response:", r);
							if (r.estado == 1) {
								data_cuenta_valorizacion = r.data;
							}
						}, "json");

				}, "json");
		}

		function f_GetTipoCambio() {
			var fecha = $("#comprobante_fecha").val();

			// Seteando objetos
			gb_tipocambio_idregistro = 0;
			gb_tipocambio_compra = '';
			gb_tipocambio_venta = '';
			gb_tipocambio_modograbar = 'N';

			$("#comprobante_tipocambio").val('');
			$("#comprobante_tipocambio").prop('placeholder', 'Cargando...');

			// Cargando datos

			$.post("apis/backend.php", {
					accion: "get_TipoCambio",
					fecha
				},
				function(data) {
					if (data.estado == 1) {
						data.data.forEach(d => {
							gb_tipocambio_idregistro = d.id_registro;
							gb_tipocambio_compra = d.tc_compra;
							gb_tipocambio_venta = d.tc_venta;
							gb_tipocambio_modograbar = 'E';

							$("#comprobante_tipocambio").val(d.tc_venta);
						});
					} else {
						$("#comprobante_tipocambio").prop('placeholder', 'No registrado');
					}

				}, "json");
		}

		function f_AdminTipoCambio() {
			// Título modal
			$('#modal_admintipocambioLabel').html('Registrar Tipo de Cambio');

			// Seteando objetos hidden
			$('#hd_idtipocambio').val(gb_tipocambio_idregistro);
			$('#hd_tipocambio_modograbar').val(gb_tipocambio_modograbar);

			// Seteando objetos
			$('#tipocambio_fecha').val($("#comprobante_fecha").val());
			$('#tipocambio_compra').val(gb_tipocambio_compra);
			$('#tipocambio_venta').val($("#comprobante_tipocambio").val());
			$('#tipocambio_moneda').val(2);

			f_OpenModal('modal_admintipocambio');
		}
	</script>

	<script>
		// === Helpers seguros ===
		const DETRACCION_PCT = 0.10;

		function toNum(v) {
			if (v === undefined || v === null) return 0;
			// Quita separadores, símbolos y convierte
			const s = ('' + v).replace(/[^\d.\-]/g, '');
			const n = parseFloat(s);
			return isNaN(n) ? 0 : n;
		}

		function fmtUSD(n) {
			return '$ ' + (new Intl.NumberFormat('en-US', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			})).format(n || 0);
		}

		function fmtPEN(n) {
			return 'S/ ' + (new Intl.NumberFormat('es-PE', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			})).format(n || 0);
		}

		function pct(paid, total) {
			return (total > 0) ? Math.max(0, Math.min(100, (paid / total) * 100)) : 0;
		}

		function setBadge($el, porPagar) {
			if (porPagar <= 0.005) { // tolerancia centavos
				$el.removeClass().addClass('badge bg-success').text('Pagado');
			} else {
				$el.removeClass().addClass('badge bg-warning').text('Pendiente');
			}
		}

		// === Render principal ===
		function f_RenderResumenPagos() {
			// 1) Leer entradas OCULTAS (ya las seteas en tu flujo)
			const totalVentaUSD = toNum($('#ins_totalventa').val()); // USD
			const porPagarVenta = toNum($('#ins_por_pagar_venta').val()); // USD

			const netoUSD = toNum($('#ins_sin_detraccion').val()); // USD
			const porPagarNeto = toNum($('#ins_por_pagar_sin_detraccion').val()); // USD

			const detracSoles = toNum($('#ins_detraccion').val()); // S/
			const porPagarDetrac = toNum($('#ins_por_pagar_detraccion').val()); // S/

			// 2) Tipo de cambio mostrado (usa el que ya setean al abrir)
			const tc = toNum($('#pago_tipocambio').val()) || toNum($('#hd_tipo_cambio').val());

			// 3) Detracción en USD (base para mostrar desglose): 10% de Valor Total
			const detracUSD = totalVentaUSD * DETRACCION_PCT;

			// 4) Pintar VALOR TOTAL
			$('#lbl_totalventa').text(fmtUSD(totalVentaUSD));
			$('#lbl_por_pagar_venta').text(fmtUSD(porPagarVenta));
			const ventaPaid = Math.max(0, totalVentaUSD - porPagarVenta);
			const pctVenta = pct(ventaPaid, totalVentaUSD);
			$('#pb_venta').css('width', pctVenta.toFixed(0) + '%');
			$('#lbl_pct_venta').text(pctVenta.toFixed(0) + '%');
			setBadge($('#badge_estado_venta'), porPagarVenta);

			// 5) Pintar NETO
			$('#lbl_sin_detraccion').text(fmtUSD(netoUSD));
			$('#lbl_por_pagar_sin_detraccion').text(fmtUSD(porPagarNeto));
			const netoPaid = Math.max(0, netoUSD - porPagarNeto);
			const pctNeto = pct(netoPaid, netoUSD);
			$('#pb_neto').css('width', pctNeto.toFixed(0) + '%');
			$('#lbl_pct_neto').text(pctNeto.toFixed(0) + '%');
			setBadge($('#badge_estado_neto'), porPagarNeto);

			// 6) Pintar DETRACCIÓN (USD -> TC -> S/)
			$('#lbl_detraccion_usd').text(fmtUSD(detracUSD));
			$('#lbl_tc').text((tc || 0).toFixed(4));
			$('#lbl_detraccion_soles').text(fmtPEN(detracSoles));
			$('#lbl_por_pagar_detraccion').text(fmtPEN(porPagarDetrac));

			const detracPaidS = Math.max(0, detracSoles - porPagarDetrac);
			const pctDetrac = pct(detracPaidS, detracSoles);
			$('#pb_detrac').css('width', pctDetrac.toFixed(0) + '%');
			$('#lbl_pct_detrac').text(pctDetrac.toFixed(0) + '%');
			setBadge($('#badge_estado_detrac'), porPagarDetrac);

			// 7) Fórmula de transparencia
			$('#lbl_det_formula').text(
				`Cálculo: 10% de ${fmtUSD(totalVentaUSD)} × ${tc.toFixed(4)} = ${fmtPEN(detracUSD * tc)}`
			);
		}

		// === Hooks recomendados ===
		// a) Cada vez que abras/recargues el modal y setees los #ins_*:
		$('#modal_registraradelanto').on('shown.bs.modal', function() {
			f_RenderResumenPagos();
		});

		// b) Después de grabar un pago (ya actualizas #ins_por_pagar_*):
		//    Agrega al final del success de f_GrabarPago():
		//    f_RenderResumenPagos();

		// c) Después de eliminar un pago (ya actualizas #ins_por_pagar_*):
		//    Agrega al final del success de f_Eliminar_ComprobantePagoDetalle():
		//    f_RenderResumenPagos();

		// d) Si permites editar el tipo de cambio en el modal:
		$(document).on('input', '#pago_tipocambio', function() {
			f_RenderResumenPagos();
		});
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

		function f_SavingDatos(_is_show) {
			if (_is_show == 1) {
				$("#wt_grabarcomprobante").show();
			} else {
				$("#wt_grabarcomprobante").hide();
			}
		}


		function f_LoadingRegistroPago(_is_show) {
			if (_is_show == 1) {
				$("#wt_grabarpago").show();

				$(".wt_grabarpago_button").prop('disabled', true);
				$(".wt_grabarpago_button").css('background-color', '#C2C0A6');
			} else {
				$("#wt_grabarpago").hide();

				$(".wt_grabarpago_button").prop('disabled', false);
				$(".wt_grabarpago_button").css('background-color', '');
			}
		}

		function f_LoadMonedas() {
			const $combo = $("#comprobante_moneda");
			$combo.html('<option value="">Cargando monedas...</option>');

			$.post("apis/backend.php", {
				accion: "get_monedas_listado"
			}, function(data) {
				if (data.estado == 1) {
					$combo.empty().append('<option value="">Elija una moneda...</option>');
					data.data.forEach(m => {
						const selected = m.id == 2 ? 'selected' : '';
						$combo.append(`<option value="${m.id}" ${selected}>${m.abv} - ${m.descripcion}</option>`);
					});
				} else {
					$combo.html('<option value="">No hay monedas disponibles</option>');
				}
			}, 'json');
		}
	</script>

	<!-- Funciones de Grabación -->
	<script type="text/javascript">
		function f_GrabarComprobante() {
			// Variables
			const modo = $("#modograbar_comprobante").val() || 'N';
			const fecha = $("#comprobante_fecha").val();
			const serie = ($.trim($("#comprobante_serie").val()) || '').toUpperCase();
			const numero = ($.trim($("#comprobante_numero").val()) || '').toUpperCase();
			const id_proveedor = $("#comprobante_proveedor").val() || ''; // opcional (por si tu backend lo usa)
			let id_valorizacion = $("#comprobante_valorizacion").val();
			console.log("vals: ", id_valorizacion);
			const porc_detraccion = parseFloat($("#comprobante_porc_detraccion").val());
			const tipo_cambio = $("#comprobante_tipocambio").val();

			// Validando datos
			if (fecha == null) {
				alert("Debe registrar la Fecha del comprobante.");

				return;
			}
			if (fecha.length == 0) {
				alert("Debe registrar la Fecha del comprobante.");

				return;
			}

			if (serie == null) {
				alert("Debe ingresar la Serie del comprobante.");

				return;
			}
			if (serie.length == 0) {
				alert("Debe ingresar la Serie del comprobante.");

				return;
			}

			if (numero == null) {
				alert("Debe ingresar el Número del comprobante.");

				return;
			}
			if (numero.length == 0) {
				alert("Debe ingresar el Número del comprobante.");

				return;
			}

			if (id_proveedor == null) {
				alert("Debe seleccionar el Proveedor.");

				return;
			}
			if (id_proveedor.length == 0) {
				alert("Debe seleccionar el Proveedor.");

				return;
			}

			if (!vals || vals.length === 0) {
				alert("Debe ingresar al menos una Valorización.");
				return;
			}

			if (isNaN(porc_detraccion) || porc_detraccion < 0) {
				alert("Debe registrar un % de detracción válido");
				return;
			}

			if (tipo_cambio == null) {
				alert("Debe ingresar el Tipo de Cambio.");

				return;
			}
			if (tipo_cambio.length == 0) {
				alert("Debe ingresar el Tipo de Cambio.");

				return;
			}

			alert("Valorizacion", vals);
			return;
			// Total = suma de data-total de cada opción seleccionada
			let total = 0;
			$("#comprobante_valorizacion option:selected").each(function() {
				const v = parseFloat($(this).data("total"));
				if (!isNaN(v)) total += v;
			});
			total = Number(total.toFixed(2));

			// (opcional) monto de detracción calculado en Front
			const monto_detraccion = Number((total * (porc_detraccion / 100)).toFixed(2));

			// Cadena de IDs de valorizaciones
			const vals_str = vals.join(',');

			// Loading ON
			f_SavingDatos(1);
			$(".wt_grabarcomprobante_button").prop("disabled", true).css("background-color", "#C2C0A6");

			// Grabando datos
			const id_moneda = $("#comprobante_moneda").val();

			$.post("apis/backend.php", {
					accion: "grabar_ComprobantePago_Valorizacion",
					modograbar_comprobante: modo,
					id_proveedor,
					id_valorizacion_detalle: vals_str,
					id_moneda: id_moneda,
					comprobante_serie: serie,
					comprobante_numero: numero,
					comprobante_fecha: fecha,
					sub_total: total,
					comprobante_porc_detraccion: porc_detraccion,
					tipo_cambio
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadResultados();
						f_cerrarModal('modal_admincomprobantes');
					} else {
						if (data.estado == 2) {
							alert("No se ha configurado el Tipo de Cambio para el día: " + f_FormatFecha(fecha, 0));
						} else {
							alert("Ocurrió un error al momento de grabar el Comprobante.");
						}
					}

					f_SavingDatos(0);
					$(".wt_grabarcomprobante_button").prop("disabled", false).css("background-color", "");

				}, "json");
		}

		function f_Eliminar_ComprobantePago(id_comprobante_pago) {
			if (!confirm('¿Está seguro que desea eliminar este comprobante de pago?')) return;

			$.post("apis/backend.php", {
					accion: "eliminar_ComprobantePago",
					id: id_comprobante_pago
				},
				function(data) {
					if (data.estado == 1) {
						f_LoadResultados();
					} else {
						alert("Ocurrió un error al momento de eliminar el comprobante de pago.");
					}
				});

		}

		function f_UpdateDatos(_item, _valor, id_comprobante_pago) {
			$.post("apis/backend.php", {
				accion: 'update_ContabilidadPagos_Datos',
				item: _item,
				id_registro: id_comprobante_pago,
				valor: _valor
			}, function(data) {
				if (data.estado != 1) {
					alert("Ocurrió un error al momento de actualizar el comprobante de pago.");
				}
			});
		}

		function f_ActualizarCampoComprobante(el) {

			const is_checkbox = $(el).is(":checkbox");
			const id_comprobante = $(el).data("id");
			const campo = $(el).data("tipo");
			const valor = is_checkbox ? (el.checked ? 1 : 0) : $(el).val();

			$.post("apis/backend.php", {
				accion: 'actualizar_CampoTexto_ComprobantePago',
				id: id_comprobante,
				campo: campo,
				valor: valor
			}, function(data) {
				if (data.estado != 1) {
					alert("Ocurrió un error al momento de actualizar el comprobante de pago.");
				} else {
					$('#td_' + campo + '_' + data.id_comprobante).html(data.fechahora_registro + '<br>' + data.usuario_registro);
				}
			});

			// Validación en vivo: si los 3 checks están activos, habilita el botón
			const chk_conta = $('input[data-id="' + id_comprobante + '"][data-tipo="aprobo_contabilidad"]').prop("checked");
			const chk_comer = $('input[data-id="' + id_comprobante + '"][data-tipo="aprobo_comercial"]').prop("checked");
			const chk_docu = $('input[data-id="' + id_comprobante + '"][data-tipo="aprobo_documentaria"]').prop("checked");

			const btn = $('#btn_pagar_' + id_comprobante);

			if (chk_conta && chk_comer && chk_docu) {
				btn.css("display", '');
			} else {
				btn.css("display", 'none');
			}

		}

		function f_CargarBancosProveedor(_id_proveedor) {
			if (!_id_proveedor) return;

			const $combo = $("#pago_entidadbancaria_2");
			$combo.html('<option value="">Cargando bancos...</option>');

			$.post("apis/backend.php", {
				accion: "get_BancosPorProveedor",
				id_proveedor: _id_proveedor
			}, function(data) {
				if (data.estado == 1) {
					let html = '<option value="">Elija una opción...</option>';
					data.bancos.forEach(item => {
						html += `<option value="${item.id}">${item.descripcion}</option>`;
					});
					$combo.html(html);
				} else {
					$combo.html('<option value="">Sin bancos disponibles</option>');
				}
			}, "json");
		}

		function f_AddArchivo(_id_registro) {
			// Abre el prompt para seleccionar el archivo
			var inputFile = $('<input type="file">');
			inputFile.on('change', function() {
				if (this.files.length > 0) {
					var selectedFile = this.files[0];

					// Envía el archivo al servidor con los parámetros mediante $.post
					var formData = new FormData();
					formData.append('accion', 'grabar_ComprobantePago_RegistroPago_Documento');
					formData.append('file', selectedFile);
					formData.append('id_registro', _id_registro);

					$.ajax({
						url: 'apis/backend.php',
						type: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						success: function(response) {
							f_LoadDetallePagos();
						}
					});
				}
			});

			inputFile.click(); // Simula el clic en el input file
		}

		function f_GrabarPago() {
			// Recupera variables
			var _id_comprobante_pago = $("#hd_idregistro").val();
			var _modo = $("#modo_grabarregistropago").val();
			var _id_registropago = $("#id_registropago").val();

			var total_venta = $("#ins_totalventa").val();
			var pago_saldo = f_ToNumber($("#pago_saldo").val()) || 0;
			var pago_monto = parseFloat($("#pago_monto").val()) || 0;

			var id_mediopago = $("#pago_medio_pago").val();
			var fecha = $("#pago_fecha").val();

			var id_entidadbancaria_1 = $("#pago_entidadbancaria_1").val();
			var id_cuenta_1 = $("#pago_entidadbancaria_cuentas_1").val();
			var id_entidadbancaria_2 = $("#pago_entidadbancaria_2").val();
			var id_cuenta_2 = $("#pago_entidadbancaria_cuentas_2").val();

			var pago_tipocambio = parseFloat($("#pago_tipocambio").val()) || 0;
			var num_operacion = $("#pago_numoperacion").val();
			var pago_observacion = $("#pago_observacion").val();

			// Capturando data-isdetraccion
			var is_detraccion = $("#pago_entidadbancaria_cuentas_2").find('option:selected').data('isdetraccion');

			// Capturando data-idmoneda
			var id_cuenta_moneda_1 = $("#pago_entidadbancaria_cuentas_1").find('option:selected').data('idmoneda');
			var id_cuenta_moneda_2 = $("#pago_entidadbancaria_cuentas_2").find('option:selected').data('idmoneda');

			// Validaciones generales
			if (!_id_comprobante_pago) return alert('No se encontró ID del comprobante de pago.');

			if (!pago_monto || pago_monto <= 0) return alert('El Monto a pagar no es válido.');
			if (!id_mediopago) return alert('Debe ingresar el Medio de Pago.');
			if (!id_entidadbancaria_1) return alert('Debe seleccionar la Entidad Bancaria de Origen (GEL).');
			if (!id_cuenta_1) return alert('Debe seleccionar la Cuenta Bancaria de Origen (GEL).');
			if (!id_entidadbancaria_2) return alert('Debe seleccionar la Entidad Bancaria de Destino (Proveedor).');
			if (!id_cuenta_2) return alert('Debe seleccionar la Cuenta Bancaria de Destino (Proveedor).');

			if (!num_operacion) return alert('Debe registrar el Número de Operación.');

			// Validación especial: pago_tipocambio requerido si id_cuenta = 2
			// if (id_cuenta_2 == '2' && (!pago_tipocambio || isNaN(pago_tipocambio))) {
			//   return alert('Debe registrar el Tipo de Cambio.');
			// }

			/*if(is_detraccion == 1){
			  if (!pago_tipocambio || isNaN(pago_tipocambio) || pago_tipocambio <= 0){
			    return alert("Tipo de cambio inválido para calcular detracción.");
			  }

			  var pago_registro = (pago_monto / pago_tipocambio);

			  if(parseFloat(pago_registro.toFixed(2)) > parseFloat(pago_saldo.toFixed(2)) ){
			    return alert('El monto ingresado supera el Monto a Pagar');
			  }
			}else{*/
			if (parseFloat(pago_monto.toFixed(2)) > parseFloat(pago_saldo.toFixed(2))) {
				return alert('El monto a pagar ingresado supera el monto por pagar');
			}
			/*}*/

			// Grabando datos
			f_LoadingRegistroPago(1);

			$.post("apis/backend.php", {
				accion: "grabar_ComprobantePago_RegistroPago",
				modo: _modo,
				id: _id_registropago,
				fecha: fecha,
				id_comprobante_pago: _id_comprobante_pago,
				pago_monto: pago_monto,
				pago_saldo: pago_saldo,
				id_mediopago: id_mediopago,
				id_entidadbancaria_1: id_entidadbancaria_1,
				id_cuenta_1: id_cuenta_1,
				id_cuenta_moneda_1: id_cuenta_moneda_1,
				id_entidadbancaria_2: id_entidadbancaria_2,
				id_cuenta_2: id_cuenta_2,
				id_cuenta_moneda_2: id_cuenta_moneda_2,
				tipocambio: pago_tipocambio,
				num_operacion: num_operacion,
				pago_observacion: pago_observacion,
				total_venta: total_venta,
				is_detraccion: is_detraccion
			}, function(data) {
				if (data.estado == 1) {
					f_cerrarModal('modal_addpago');

					var total_pagado_venta = parseFloat(data.total_comprobante) - ((parseFloat(data.pago_detraccion) / parseFloat(pago_tipocambio)) + parseFloat(data.pago_sin_detraccion));

					// var total_pagado_detraccion = f_FormatEntero((parseFloat(data.total_detraccion)*parseFloat(data.tipo_cambio)) - (parseFloat(data.pago_detraccion)*parseFloat(data.tipo_cambio)));

					var total_pagado_detraccion = f_FormatEntero((parseFloat(data.total_detraccion_soles)) - (parseFloat(data.pago_detraccion)));
					var total_pagado_sin_detraccion = parseFloat(data.total_sin_detraccion) - parseFloat(data.pago_sin_detraccion);

					// Validando si ya se pagó toda la detracción
					if (total_pagado_detraccion == 0) {
						total_pagado_venta = total_pagado_sin_detraccion;
					}

					$("#ins_por_pagar_venta").val(f_RedondearDecimales(total_pagado_venta, 2));
					$("#ins_por_pagar_detraccion").val(f_RedondearDecimales(f_FormatEntero(total_pagado_detraccion, 2), 2));
					$("#ins_por_pagar_sin_detraccion").val(f_RedondearDecimales(total_pagado_sin_detraccion, 2));

					f_RenderResumenPagos();
					f_LoadDetallePagos();
				} else {
					alert("Ocurrió un error al momento de grabar el Pago.");
				}
				f_LoadingRegistroPago(0);
			}, "json");
		}

		function f_Eliminar_ComprobantePagoDetalle(id_pago) {
			if (!confirm('¿Está seguro de eliminar el registro de Pago seleccionado?')) return;

			const id_comprobante = $("#hd_idregistro").val();
			if (!id_comprobante) {
				alert('No se encontró el comprobante.');
				return;
			}

			f_LoadingRegistroPago(1);

			$.post("apis/backend.php", {
				accion: "eliminar_ComprobantePago_RegistroPago",
				id: id_pago,
				id_comprobante_pago: id_comprobante
			}, function(r) {
				f_LoadingRegistroPago(0);

				if (r && r.estado == 1) {
					// 1) Normaliza números (con tolerancia a nulos)
					const totalComprobante = parseFloat(r.total_comprobante) || 0;
					const total_detraccion_soles = parseFloat(r.total_detraccion_soles, 2) || 0;
					const totalSinDetraccion = parseFloat(r.total_sin_detraccion) || 0;
					const pagoDetraccion = parseFloat(r.pago_detraccion, 2) || 0;
					const pagoSinDetraccion = parseFloat(r.pago_sin_detraccion) || 0;

					// 2) Recalcula POR PAGAR
					const porPagarVenta = totalComprobante - ((pagoDetraccion / r.tipo_cambio) + pagoSinDetraccion); // USD
					const porPagarDetraccion = total_detraccion_soles - pagoDetraccion; // S/
					const porPagarSinDetraccion = totalSinDetraccion - pagoSinDetraccion; // USD

					// Validando si ya se pagó toda la detracción
					if (porPagarDetraccion == 0) {
						porPagarVenta = porPagarSinDetraccion;
					}

					// 3) Pinta inputs ocultos que usa el resumen
					$("#ins_por_pagar_venta").val(f_RedondearDecimales(porPagarVenta, 2));
					$("#ins_por_pagar_detraccion").val(f_RedondearDecimales(f_FormatEntero(porPagarDetraccion, 2), 2));
					$("#ins_por_pagar_sin_detraccion").val(f_RedondearDecimales(porPagarSinDetraccion, 2));

					// 4) Redibuja tarjetas y barras + recarga tabla
					f_RenderResumenPagos();
					f_LoadDetallePagos();
				} else {
					alert(r?.msg || "No se pudo eliminar el pago.");
				}
			}, "json");
		}

		function f_GrabarTipoCambio() {
			var id = $('#hd_idtipocambio').val();
			var modo = $('#hd_tipocambio_modograbar').val();

			var fecha = $('#tipocambio_fecha').val();
			var id_moneda_base = $('#tipocambio_moneda').val();
			var compra = parseFloat($('#tipocambio_compra').val());
			var venta = parseFloat($('#tipocambio_venta').val());

			// Validando datos
			if (!fecha) {
				alert("Debe seleccionar la Fecha.");

				return;
			}

			if (!id_moneda_base) {
				alert("Debe seleccionar la Moneda base.");

				return;
			}

			if (!compra) {
				alert("Debe ingresar el Tipo de Cambio para Compra.");

				return;
			}

			if (!venta) {
				alert("Debe ingresar el Tipo de Cambio para Venta.");

				return;
			}

			f_LoadingRegistro('admintipocambio', true);

			$.post('apis/backend.php', {
					accion: 'grabar_config_tipocambio',
					modo: modo,
					id: id,
					fecha: fecha,
					compra: compra,
					venta: venta,
					id_moneda_base: id_moneda_base,
				},
				function(response) {
					f_LoadingRegistro('admintipocambio', false);

					if (response.estado == 1) {
						$('#modal_admintipocambio').modal('hide');

						f_GetTipoCambio();
					} else if (response.estado == 2) {
						alert('El Tipo de Cambio para esta fecha ya se encuentra ingresado.');
					} else {
						alert('Error al grabar la información.');
					}
				}, 'json');
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

		function f_ToNumber(str) {
			if (!str) return 0;
			str = String(str).replace(/,/g, '').replace(/[^\d.-]/g, '');
			const num = parseFloat(str);
			return isNaN(num) ? 0 : num;
		}
	</script>

	<!-- Funcion Default -->
	<script type="text/javascript">

	</script>
</body>

</html>