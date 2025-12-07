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

	<title><?php echo $nom_app; ?> | Administración de Unidades</title>

	<script type="text/javascript">
		let loaded_img_TC_1 = '';
		let loaded_img_TC_2 = '';
		let img_selected_TC_1 = '0';
		let img_selected_TC_2 = '0';

		let loaded_img_TP_1 = '';
		let loaded_img_TP_2 = '';
		let img_selected_TP_1 = '0';
		let img_selected_TP_2 = '0';
	</script>
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
											<h6 style="font-size: 14px;">Por Empresa de Transporte</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<div class="flex-fill">
												<select id="filtro_transportista" class="form-select" data-placeholder="Elija una opción..." style="text-align: left; font-size: 14px;">
													<option selected value="">Elija una opción...</option>
													<option value="x" style="font-size: 6px;" disabled></option>

													<?php

													$q_transportistas = "SELECT Id,
																												documento,
																												razon_social
																									 FROM tb_clientes
																									WHERE cod_clientecondicion = 2
																								 ORDER BY razon_social";

													if ($res_transportistas = mysqli_query($enlace, $q_transportistas)) {
														if (mysqli_num_rows($res_transportistas) > 0) {
															while ($row_transportistas = mysqli_fetch_array($res_transportistas)) {
													?>

																<option value="<?php echo $row_transportistas["Id"]; ?>"><?php echo $row_transportistas["documento"] . ' - ' . $row_transportistas["razon_social"]; ?></option>

																<option value="x" style="font-size: 6px;" disabled></option>

													<?php

																$t++;
															}
														}
													}

													?>

												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Tipo de Vehículo</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<div class="flex-fill">
												<select id="filtro_tipovehiculo" class="form-select" data-placeholder="Elija una opción..." style="text-align: left; font-size: 14px;">
													<option selected value="">Elija una opción...</option>
													<option value="x" style="font-size: 6px;" disabled></option>

													<?php

													$q_tipovehiculo = "SELECT Id,
																											descripcion
																								 FROM tbconfig_tipovehiculo
																							 ORDER BY descripcion";

													if ($res_tipovehiculo = mysqli_query($enlace, $q_tipovehiculo)) {
														if (mysqli_num_rows($res_tipovehiculo) > 0) {
															while ($row_tipovehiculo = mysqli_fetch_array($res_tipovehiculo)) {
													?>

																<option value="<?php echo $row_tipovehiculo["Id"]; ?>"><?php echo $row_tipovehiculo["descripcion"]; ?></option>

																<option value="x" style="font-size: 6px;" disabled></option>

													<?php

																$t++;
															}
														}
													}

													?>

												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Placa</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;" />
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<div class="flex-fill">
												<input id="filtro_placa" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase;" placeholder="ABC-123">
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
							</div>

            </div>
          </div>
        </div>
      </div>

			<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: px;">
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

					<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
						<div class="row" style="padding: 20px;">
							<div class="col-md-10 col-sm-10 col-xs-10">
								<h5>Resumen de Unidades</h5>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
								<button class="btn btn-primary" type="button" onclick="f_AdminUnidades('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
									<b> + Nueva Unidad</b>
								</button>
							</div>


						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
							<hr style="border-color: #D9D9D9;" />
						</div>

						<div id="div_resumen" class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll;">
							<table id="tbl_detalle" class="table table-bordered table-striped table-hover">

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- Ventanas modales -->
	<div class="modal fade" id="modal_addunidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addunidadLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="width: 120%;">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="modal_addunidadLabel"></h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row" style="padding: 5px;">
						<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">

						</div>

						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="d-flex">
								<div class="col-md-5 col-sm-5 col-xs-5">
									<input id="unidad_placa1" type="text" class="form-control" style="text-align: center; text-transform: uppercase;" placeholder="ABC" onkeyup="f_KeyUpPlaca();">
								</div>

								<div class="col-md-1 col-sm-1 col-xs-1">
									<label style="font-weight: bold; margin-left: 8px; margin-top: 5px;">-</label>
								</div>

								<div class="col-md-5 col-sm-5 col-xs-5">
									<input id="unidad_placa2" type="text" class="form-control" style="text-align: center; margin-left: 2px;" placeholder="111">
								</div>
							</div>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px;">
							Emp. Transporte:
						</div>

						<div class="col-md-9 col-sm-9 col-xs-9">
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<select id="unidad_transportista" class="form-select" data-placeholder="Elija una opción...">
										<option selected value="">Elija una opción...</option>
										<option value="x" style="font-size: 6px;" disabled></option>

										<?php

										// Obtiene lista
										$q_transportistas = "SELECT Id,
																										documento,
																										razon_social
																							FROM tb_clientes
																						 WHERE estado = 'A'
																							 AND cod_clientecondicion = 2
																						ORDER BY razon_social";

										if ($res_transportistas = mysqli_query($enlace, $q_transportistas)) {
											if (mysqli_num_rows($res_transportistas) > 0) {
												while ($row_transportistas = mysqli_fetch_array($res_transportistas)) {
										?>

													<option value="<?php echo $row_transportistas["Id"]; ?>"><?php echo $row_transportistas["documento"] . ' - ' . $row_transportistas["razon_social"]; ?></option>

													<option value="x" style="font-size: 6px;" disabled></option>

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
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px;">
							Tipo Vehículo:
						</div>

						<div class="col-md-9 col-sm-9 col-xs-9">
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<select id="unidad_tipovehiculo" class="form-select" data-placeholder="Elija una opción...">
										<option selected value="">Elija una opción...</option>
										<option value="x" style="font-size: 6px;" disabled></option>

										<?php

										// Obtiene lista
										$q_tipovehiculo = "SELECT Id,
																									UPPER(descripcion) AS descripcion
																						 FROM tbconfig_tipovehiculo
																					  WHERE estado = 'A'
																					 ORDER BY descripcion";

										if ($res_tipovehiculo = mysqli_query($enlace, $q_tipovehiculo)) {
											if (mysqli_num_rows($res_tipovehiculo) > 0) {
												while ($row_tipovehiculo = mysqli_fetch_array($res_tipovehiculo)) {
										?>

													<option value="<?php echo $row_tipovehiculo["Id"]; ?>"><?php echo $row_tipovehiculo["descripcion"]; ?></option>

													<option value="x" style="font-size: 6px;" disabled></option>

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

					<!-- <div class="row" style="padding: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px;">
								Conductor:
							</div>

							<div class="col-md-9 col-sm-9 col-xs-9">
								<div class="d-flex">
									<div class="flex-fill" style="max-width: 100%">
										<select id="unidad_conductor" class="form-select" data-placeholder="Elija una opción...">
											<option selected value="">Elija una opción...</option>
											<option value="x" style="font-size: 6px;" disabled></option>

											<?php

											// Obtiene lista
											$q_tipovehiculo = "SELECT Id,
																									dni_licencia,
																									UPPER(nombres) AS nombres
																						 FROM tbconfig_conductores
																					  WHERE estado = 'A'
																					 ORDER BY nombres";

											if ($res_tipovehiculo = mysqli_query($enlace, $q_tipovehiculo)) {
												if (mysqli_num_rows($res_tipovehiculo) > 0) {
													while ($row_tipovehiculo = mysqli_fetch_array($res_tipovehiculo)) {
											?>

															<option value="<?php echo $row_tipovehiculo["Id"]; ?>"><?php echo $row_tipovehiculo["dni_licencia"] . ' - ' . $row_tipovehiculo["nombres"]; ?></option>

															<option value="x" style="font-size: 6px;" disabled></option>

															<?php
														}
													}
												}

															?>

										</select>
									</div>
								</div>
							</div>
						</div> -->

					<div class="row" style="padding: 5px;">
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px;">
							Marca:
						</div>

						<div class="col-md-9 col-sm-9 col-xs-9">
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<select id="unidad_marca" class="form-select" data-placeholder="Elija una opción...">
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
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px;">
							Constancia MTC:
						</div>

						<div class="col-md-5 col-sm-5 col-xs-5">
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<input id="unidad_codigomtc" type="text" class="form-control" style="text-align: center; text-transform: uppercase;">
								</div>
							</div>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px;">
							Capacidad (Kg):
						</div>

						<div class="col-md-3 col-sm-3 col-xs-3">
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<input id="unidad_capacidad" type="number" class="form-control" style="text-align: center; text-transform: uppercase;">
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: center;">
							Tara (Kg):
						</div>

						<div class="col-md-3 col-sm-3 col-xs-3">
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<input id="unidad_tara" type="number" class="form-control" style="text-align: center; text-transform: uppercase;">
								</div>
							</div>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px;">
							Dimensiones:
						</div>

						<div class="col-md-3 col-sm-3 col-xs-3 d-flex" style="justify-content: space-between; gap: 6px; align-items: center;">
							(L):
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<input id="largo" type="number" class="form-control" style="text-align: center; text-transform: uppercase;">
								</div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 d-flex" style="justify-content: space-between; gap: 6px; align-items: center;">
							(A):
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<input id="ancho" type="number" class="form-control" style="text-align: center; text-transform: uppercase;">
								</div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 d-flex" style="justify-content: space-between; gap: 6px; align-items: center;">
							(H):
							<div class="d-flex">
								<div class="flex-fill" style="max-width: 100%">
									<input id="alto" type="number" class="form-control" style="text-align: center; text-transform: uppercase;">
								</div>
							</div>
						</div>

					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #816951; color: #ffffff;">
							Tarjeta de Circulación
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
							Anverso:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-8">
							<table style="width: 100%;">
								<tr>
									<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
										<img id="img_TC_1" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
									</td>

									<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
										<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(1);">
									</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
							Reverso:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-8">
							<table style="width: 100%;">
								<tr>
									<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
										<img id="img_TC_2" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
									</td>

									<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
										<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(2);">
									</td>
								</tr>
							</table>
						</div>
					</div>


					<div class="row" style="padding: 5px;">
						<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #816951; color: #ffffff;">
							Tarjeta de Propiedad
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
							Anverso:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-8">
							<table style="width: 100%;">
								<tr>
									<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
										<img id="img_TP_1" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
									</td>

									<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
										<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(3);">
									</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="row" style="padding: 5px;">
						<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
							Reverso:
						</div>

						<div class="col-md-8 col-sm-8 col-xs-8">
							<table style="width: 100%;">
								<tr>
									<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
										<img id="img_TP_2" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
									</td>

									<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
										<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(4);">
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<input id="hd_idunidad" type="hidden">
				<input id="hd_modograbar" type="hidden">

				<div class="modal-footer">
					<div id="wt_grabarunidad" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
						<img src="<?php echo $img_waiting ?>" style="width: 20px;">
						<label style="font-style: italic;"> Grabando datos...</label>
					</div>

					<button type="button" class="btn btn-secondary wt_grabarunidad_button" data-bs-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary wt_grabarunidad_button" onclick="f_GrabarUnidad();">Grabar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_showimg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showimgLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div id="modal_showimg_content" class="modal-content">
				<div class="modal-header" style="background-color: #f8da62;">
					<h1 class="modal-title fs-5" id="modal_showimgLabel">Título</h1>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row" style="padding: 5px;">
						<img id="img_modal" alt="">
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
		function f_Init() {
			// Genera menús
			f_GetMenuPrincipal();

			// Titulo de Pantalla
			$("#nv_titulo").html('| Administración de Unidades');

			// Cargando listas generales

			// Carga el detalle de información
			f_LoadResultados();
		}
	</script>

	<!-- Seteando objetos Select2 -->
	<script type="text/javascript">
		// Lista de Unidades
		$('#unidad_transportista, #unidad_tipovehiculo, #unidad_conductor, #unidad_marca').select2({
			theme: "bootstrap-5",
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: true,
			dropdownParent: $('#modal_addunidad')
		}).on('select2:open', function() {
			$('body').css('zoom', '100%'); 
		}).on('select2:close', function() {
		    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
		});

		$('#filtro_transportista, #filtro_tipovehiculo').select2({
			theme: "bootstrap-5",
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: true,
		}).on('select2:open', function() {
					$('body').css('zoom', '100%'); 
		}).on('select2:close', function() {
		    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
		});
		
	</script>

	<!-- Funciones Principales -->
	<script type="text/javascript">
		function f_LoadResultados() {
			var _html = '';
			var d = 1;

			var filtro_transportista = $("#filtro_transportista").val();
			var filtro_tipovehiculo = $("#filtro_tipovehiculo").val();
			var filtro_placa = $("#filtro_placa").val().trim();

			var bk_color = '';
			var estado = '';
			var href_estado = '';
			var href_color = '';
			var href_icon = '';

			$("#tbl_detalle").html('');

			$.post("apis/backend.php", {
					accion: "get_listaunidades_All",
					filtro_transportista: filtro_transportista,
					filtro_tipovehiculo: filtro_tipovehiculo,
					filtro_placa: filtro_placa
				},
				function(data) {
					if (data.estado == 1) {
						$("#tbl_detalle").html(data.html);
					}

				}, "json");
		};

		function f_AdminUnidades(_id_transporte, _cplaca, _id_Transportista, _id_tipovehiculo, _id_choferes, _id_marca, _Capacidad, _Tara, _codigo_mtc, img_TC_1, img_TC_2, img_TP_1, img_TP_2, largo, ancho, alto) {
			// Definiendo título de ventana e Inicilizando controles de tipo texto
			if (_id_transporte != 'x') {
				tipo = "E";
				titulo = 'Editar Unidad: "<b>' + _cplaca + '</b>"';
			} else {
				tipo = "N";
				titulo = "Nueva Unidad";
				$("#largo").val("");
				$("#ancho").val("");
				$("#alto").val("");
				$("#unidad_transportista").val("");
				$("#unidad_transportista").trigger('change');
				$("#unidad_tipovehiculo").val("");
				$("#unidad_tipovehiculo").trigger('change');
				$("#unidad_marca").val("");
				$("#unidad_marca").trigger('change');
			}

			// Colocando el título a la pantalla
			$("#modal_addunidadLabel").html(titulo);

			// Identificando el tipo de grabación
			$("#hd_modograbar").val(tipo);

			// Seteando objetos
			if (tipo != 'N') {
				$("#hd_idunidad").val(_id_transporte);
				$("#unidad_placa1").val(_cplaca.split('-')[0]);
				$("#unidad_placa2").val(_cplaca.split('-')[1]);

				$("#unidad_transportista").val(_id_Transportista);
				$("#unidad_transportista").trigger('change');

				$("#unidad_tipovehiculo").val(_id_tipovehiculo);
				$("#unidad_tipovehiculo").trigger('change');

				$("#unidad_conductor").val(_id_choferes);
				$("#unidad_conductor").trigger('change');

				$("#unidad_marca").val(_id_marca);
				$("#unidad_marca").trigger('change');

				$("#unidad_codigomtc").val(_codigo_mtc);
				$("#unidad_capacidad").val(_Capacidad);
				$("#unidad_tara").val(_Tara);

				// seteando los valores de Dimensiones
				$("#largo").val(largo);
				$("#ancho").val(ancho);
				$("#alto").val(alto);
				if (img_TC_1.trim().length > 0) {
					$("#img_TC_1").attr('src', 'images/unidades/' + img_TC_1);

					$("#img_TC_1").show();

					loaded_img = 'images/unidades/' + img_TC_1;
				} else {
					$("#img_TC_1").attr('src', '');

					$("#img_TC_1").hide();
				}

				if (img_TC_2.trim().length > 0) {
					$("#img_TC_2").attr('src', 'images/unidades/' + img_TC_2);

					$("#img_TC_2").show();

					loaded_img = 'images/unidades/' + img_TC_2;
				} else {
					$("#img_TC_2").attr('src', '');

					$("#img_TC_2").hide();
				}

				if (img_TP_1.trim().length > 0) {
					$("#img_TP_1").attr('src', 'images/unidades/' + img_TP_1);

					$("#img_TP_1").show();

					loaded_img = 'images/unidades/' + img_TP_1;
				} else {
					$("#img_TP_1").attr('src', '');

					$("#img_TP_1").hide();
				}

				if (img_TP_2.trim().length > 0) {
					$("#img_TP_2").attr('src', 'images/unidades/' + img_TP_2);

					$("#img_TP_2").show();

					loaded_img = 'images/unidades/' + img_TP_2;
				} else {
					$("#img_TP_2").attr('src', '');

					$("#img_TP_2").hide();
				}
			} else {
				$("#hd_idunidad").val(0);
				$("#unidad_placa1").val('');
				$("#unidad_placa2").val('');
				$("#unidad_transportista").val('');
				$("#unidad_tipovehiculo").val('');
				$("#unidad_conductor").val('');
				$("#unidad_marca").val('');
				$("#unidad_codigomtc").val('');
				$("#unidad_capacidad").val('');
				$("#unidad_tara").val('');
				$("#img_TC_1").attr('src', '');
				$("#img_TC_1").hide();
				$("#img_TC_2").attr('src', '');
				$("#img_TC_2").hide();
				$("#img_TP_1").attr('src', '');
				$("#img_TP_1").hide();
				$("#img_TP_2").attr('src', '');
				$("#img_TP_2").hide();

				loaded_img_TC_1 = '';
				loaded_img_TC_2 = '';
				img_selected_TC_1 = '0';
				img_selected_TC_2 = '0';

				loaded_img_TP_1 = '';
				loaded_img_TP_2 = '';
				img_selected_TP_1 = '0';
				img_selected_TP_2 = '0';
			}

			// Cargando datos
			f_OpenModal('modal_addunidad');
		}

		function f_AddImg(_item) {
			var input = document.createElement('input');
			input.type = 'file';
			input.accept = 'image/*';
			input.onchange = function(event) {
				var file = event.target.files[0];
				var timer;

				var checkFileLoaded = function() {
					if (file) {
						var reader = new FileReader();
						reader.onload = function(e) {
							if (_item == 1) {
								var imagen = document.getElementById('img_TC_1');
							}

							if (_item == 2) {
								var imagen = document.getElementById('img_TC_2');
							}

							if (_item == 3) {
								var imagen = document.getElementById('img_TP_1');
							}

							if (_item == 4) {
								var imagen = document.getElementById('img_TP_2');
							}

							imagen.src = e.target.result;
						};

						reader.readAsDataURL(file);

						if (_item == 1) {
							loaded_img_TC_1 = file;

							$("#img_TC_1").show();

							img_selected_TC_1 = 1;
						}

						if (_item == 2) {
							loaded_img_TC_2 = file;

							$("#img_TC_2").show();

							img_selected_TC_2 = 1;
						}

						if (_item == 3) {
							loaded_img_TP_1 = file;

							$("#img_TP_1").show();

							img_selected_TP_1 = 1;
						}

						if (_item == 4) {
							loaded_img_TP_2 = file;

							$("#img_TP_2").show();

							img_selected_TP_2 = 1;
						}
					} else {
						alert('No se seleccionó ningún archivo.');
					}
				};

				timer = setTimeout(checkFileLoaded, 1000); // Espera 1 segundo antes de verificar

				input.addEventListener('click', function() {
					clearTimeout(timer);
				});
			};
			input.click();
		}

		function f_ShowImg(_id_img) {
			// Limpiando objeto img
			$("#img_modal").attr('src', '');

			// Obtiene el SRC si lo tuviera
			var modalImg = document.getElementById('img_modal');
			modalImg.src = _id_img;

			// Abre modal
			f_OpenModal('modal_showimg');
		}
	</script>

	<!-- Funciones Secundarias -->
	<script type="text/javascript">
		function f_CleanTxtTipo() {
			var cod_tipo = $("#filtro_listatipo").val();

			if (cod_tipo == null) {
				$("#filtro_tipo").val('');

				return;
			}

			if (cod_tipo.length == 0) {
				$("#filtro_tipo").val('');

				return;
			}
		}

		function f_GetListaTipoDocumento(_is_juridico) {
			var _html = '<option selected value="">Elija una opción...</option>';
			_html += '<option value="x" style="font-size: 6px;" disabled></option>';

			if (_is_juridico == 0) {
				if ($("#cliente_tipocliente").val() == 2) {
					_is_juridico = 1;
				}
			}

			$.post("apis/backend.php", {
					accion: "get_listatipodocumento"
				},
				function(data) {
					if (data.estado == 1) {
						$.each(data.res, function(key, val) {
							_html += '<option value="' + val.Id + '" ' + ((_is_juridico == 1) ? ((val.Id == 2) ? 'selected' : '') : ((val.Id == 1) ? 'selected' : '')) + '>' + val.descripcion + '</option>';
							_html += '<option value="x" style="font-size: 6px;" disabled></option>';
						});

						$("#cliente_tipodocumento").html(_html);
					} else {
						$("#cliente_tipodocumento").html('');
					}

				}, "json");
		}

		function f_GetInfoCliente() {
			var is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
			var documento = $("#cliente_documento").val();
			var arr_response = '';

			// Limpiando objetos
			$("#cliente_razonsocial").val('');
			$("#cliente_direccion").val('');
			$("#wt_razonsocial2").hide();

			// Obteniendo información
			if (documento.length == 8 || documento.length == 11) {
				$("#wt_razonsocial2").show();

				$.post("apis/backend.php", {
						accion: "get_infocliente",
						is_ruc: is_ruc,
						documento: documento
					},
					function(data) {
						if (data.estado == 1) {
							arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

							if (is_ruc == 1) {
								$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
								$("#cliente_direccion").val(arr_response[4].split(':')[1].trim());
							} else {
								$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
								$("#cliente_direccion").val('');
							}
						} else {
							$("#cliente_razonsocial").val('NO ENCONTRADO');
							$("#cliente_direccion").val('');
						}

						$("#wt_razonsocial2").hide();

					}, "json");
			}
		}

		function f_LoadingGrabarUnidad(_is_show) {
			if (_is_show == 1) {
				$("#wt_grabarunidad").show();

				$(".wt_grabarunidad_button").prop('disabled', true);
			} else {
				$("#wt_grabarunidad").hide();

				$(".wt_grabarunidad_button").prop('disabled', false);
			}
		}
	</script>

	<!-- Funciones de Grabación -->
	<script type="text/javascript">
		// Graba información temporal (onblur).
		function f_GrabarUnidad() {
			// Recupera variables
			var id_unidad = $("#hd_idunidad").val();
			var modo_grabar = $("#hd_modograbar").val();

			var unidad_placa = f_CleanInjection($("#unidad_placa1").val()) + '-' + f_CleanInjection($("#unidad_placa2").val());
			var unidad_transportista = $("#unidad_transportista").val();
			var unidad_tipovehiculo = $("#unidad_tipovehiculo").val();
			var unidad_marca = $("#unidad_marca").val();
			var unidad_conductor = $("#unidad_conductor").val();
			var unidad_codigomtc = $("#unidad_codigomtc").val();
			var unidad_capacidad = $("#unidad_capacidad").val();
			var unidad_tara = $("#unidad_tara").val();
			var largo = $("#largo").val();
			var ancho = $("#ancho").val();
			var alto = $("#alto").val();

			// Validando datos
			if ($("#unidad_placa1").val() == null) {
				alert("La Placa ingresada no es válida.");

				return;
			}
			if ($("#unidad_placa1").val().length == 0) {
				alert("La Placa ingresada no es válida.");

				return;
			}
			if ($("#unidad_placa2").val() == null) {
				alert("La Placa ingresada no es válida.");

				return;
			}
			if ($("#unidad_placa2").val().length == 0) {
				alert("La Placa ingresada no es válida.");

				return;
			}

			if (unidad_transportista == null) {
				alert("Debe seleccionar la Empresa de Transporte.");

				return;
			}
			if (unidad_transportista.length == 0) {
				alert("Debe seleccionar la Empresa de Transporte.");

				return;
			}

			if (unidad_tipovehiculo == null) {
				alert("Debe seleccionar el Tipo de Vehículo.");

				return;
			}
			if (unidad_tipovehiculo.length == 0) {
				alert("Debe seleccionar el Tipo de Vehículo.");

				return;
			}

			// if (unidad_conductor == null){
			//   alert("Debe seleccionar el Conductor.");

			//   return;
			// }
			// if (unidad_conductor.length == 0){
			//   alert("Debe seleccionar el Conductor.");

			//   return;
			// }

			// if (unidad_marca == null){
			//   alert("Debe seleccionar la Marca.");

			//   return;
			// }
			// if (unidad_marca.length == 0){
			//   alert("Debe seleccionar la Marca.");

			//   return;
			// }

			if (unidad_capacidad == null) {
				alert("Debe ingresar la Capacidad.");

				return;
			}
			if (unidad_capacidad.length == 0) {
				alert("Debe ingresar la Capacidad.");

				return;
			}
			if (unidad_capacidad <= 0) {
				alert("La Capacidad ingresada no es correcta.");

				return;
			}

			if (unidad_tara == null) {
				alert("Debe ingresar la Tara.");

				return;
			}
			if (unidad_tara.length == 0) {
				alert("Debe ingresar la Tara.");

				return;
			}
			if (unidad_tara <= 0) {
				alert("La Tara ingresada no es correcta.");

				return;
			}

			// Seteando parámetros de Imagen
			var formData = new FormData();
			formData.append('img_TC_1', loaded_img_TC_1);
			formData.append('img_TC_2', loaded_img_TC_2);
			formData.append('img_TP_1', loaded_img_TP_1);
			formData.append('img_TP_2', loaded_img_TP_2);

			// Seteando los demás parámetros
			formData.append('accion', 'grabar_unidad');
			formData.append('modo_grabar', modo_grabar);
			formData.append('id_unidad', id_unidad);
			formData.append('unidad_placa', unidad_placa);
			formData.append('unidad_transportista', unidad_transportista);
			formData.append('unidad_tipovehiculo', unidad_tipovehiculo);
			formData.append('unidad_conductor', unidad_conductor);
			formData.append('unidad_marca', unidad_marca);
			formData.append('unidad_capacidad', unidad_capacidad);
			formData.append('unidad_tara', unidad_tara);
			formData.append('unidad_codigomtc', unidad_codigomtc);
			formData.append('img_selected_TC_1', img_selected_TC_1);
			formData.append('img_selected_TC_2', img_selected_TC_2);
			formData.append('img_selected_TP_1', img_selected_TP_1);
			formData.append('img_selected_TP_2', img_selected_TP_2);

			formData.append('ancho', ancho);
			formData.append('alto', alto);
			formData.append('largo', largo);
			// Grabando Datos
			f_LoadingGrabarUnidad(1);

			$.ajax({
				url: 'apis/backend.php',
				type: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: function(data) {
					if (data.estado == 2) {
						alert("La Unidad ingresada ya fue registrada anteriormente.\n\nPor favor verificar");
					} else {
						if (data.estado == 1) {
							f_LoadResultados();

							f_cerrarModal('modal_addunidad');
						} else {
							alert("Ocurrió un error al momento de grabar la Unidad");
						}
					}

					f_LoadingGrabarUnidad(0);

				}
			});
		}

		function f_CambiarEstado(_Estado, _id_unidad) {
			var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

			// Validando datos
			if (_Estado != 'A' && _Estado != 'I') {
				alert("Ocurrió un error al momento de cambiar el estado");

				return;
			}

			if (confirm("¿Está seguro de " + estado + " la Unidad seleccionada?")) {
				$.post("apis/backend.php", {
						accion: "update_estadounidad",
						id_unidad: _id_unidad,
						estado: _Estado
					},
					function(data) {
						if (data.estado == 1) {
							f_LoadResultados();
						} else {
							alert("Ocurrió un error al momento de cambiar el estado");
						}

					}, "json");
			}
		};

		function f_EliminarUnidad(_id_unidad) {
			if (confirm("¿Está seguro de eliminar la Unidad seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")) {
				$.post("apis/backend.php", {
						accion: "eliminar_unidad",
						id_unidad: _id_unidad
					},
					function(data) {
						if (data.estado == 1) {
							f_LoadResultados();
						} else {
							alert("Ocurrió un error al momento de eliminar el Cliente.");
						}
					}, "json");
			}
		};
	</script>

	<!-- Funciones de Menús -->
	<script type="text/javascript">
		function f_SetDimension() {
			if (screen.width < 500) {
				$("#offcanvasExample").css('width', '60%');
				$("#div_resumen").css('width', '100%');
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
</body>

</html>