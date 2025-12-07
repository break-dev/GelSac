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

		<title><?php echo $nom_app; ?> | Captura de Pesos del Primer Tramo</title>

		<script type="text/javascript">
			var is_mobile = 0;
		</script>

		<style>
			.hidden-part {
				color: white; /* Cambia el color a blanco para ocultarlo */
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

					<div class="col-md-11 col-sm-11 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px;">
						<div class="d-flex">
							<div class="col-md-2 col-sm-2 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px;">
								<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; color: #ffffff; text-align: center;">
									<div class="d-flex justify-content-center" style="text-align: center;">
										<h5 style="font-size: 14px; font-weight: bold; padding-top: 5px;">Unidades en Planta</h5>

										<div id="wt_loadingingresounidades" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
											<img src="<?php echo $img_waiting ?>" style="width: 20px;">
											<label style="font-style: italic;"> Cargando datos...</label>
										</div>
									</div>
								</div>

								<div id="div_ingresounidades" style="padding: 5px; font-size: 13px;">
									
								</div>
							</div>

							<div class="col-md-7 col-sm-7 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px;">
								<div class="d-flex justify-content-center" style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #cfaa41; text-align: center;">
									<table style="width: 100%;">
										<tr>
											<td>
												<div class="d-flex justify-content-center" style="padding-left: 40px;">
													<h5 style="font-size: 14px; font-weight: bold; padding-top: 5px;">Validación de Unidades, Generación de Lotes y Peso Inicial de Lotes</h5>

													<div id="wt_loadingingresounidades_validadcion" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</td>
										</tr>
									</table>
								</div>

								<div id="div_ingresounidades_validacion" style="padding: 5px; font-size: 13px;">
									
								</div>
							</div>

							<div class="col-md-3 col-sm-3 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; color: #ffffff; text-align: center;">
									<h5 style="font-size: 14px; font-weight: bold; padding-top: 5px;">Peso Final de Lotes</h5>

									<div id="wt_loadinglotespesofinal" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
										<img src="<?php echo $img_waiting ?>" style="width: 20px;">
										<label style="font-style: italic;"> Cargando datos...</label>
									</div>
								</div>

								<div id="div_lotespesofinal" style="padding: 5px; font-size: 13px;">
									
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
		<div class="modal fade" id="modal_unidadvalidacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_unidadvalidacionLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" style="width: 120%;">
					<div class="modal-header">
						<h1 class="modal-title fs-5">Validando Unidad: </h1>
						<h1 class="modal-title fs-5" id="modal_unidadvalidacionLabel" style="margin-left: 10px;"></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div id="div_recepcion1">
							<div id="div_row1" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Condición:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 91%">
											<select id="registro_condicion" class="form-select" data-placeholder="Elija una opción..." style="text-align: left;">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_tipocarga = "SELECT Id,
																							 descripcion
																					FROM tbconfig_tipoingresounidades
																				 WHERE estado = 'A'
																				ORDER BY is_predeterminado DESC";

												if ($res_tipocarga = mysqli_query($enlace, $q_tipocarga)){
													if (mysqli_num_rows($res_tipocarga) > 0) {
														while($row_tipocarga = mysqli_fetch_array($res_tipocarga)){
															?>

															<option value="<?php echo $row_tipocarga["Id"]; ?>"><?php echo $row_tipocarga["descripcion"]; ?></option>

															<option value="x" style="font-size: 6px;" disabled></option>

															<?php

															$t ++;
														}
													}
												}

												?>

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_divrow1" type="button" class="btn" onclick="f_ValidacionCheck(1);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow1" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row2" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Placa 1:
								</div>

								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="d-flex">
										<div class="col-md-5 col-sm-5 col-xs-5">
											<input id="registro_placa1" type="text" class="form-control" style="text-align: center; text-transform: uppercase;" placeholder="ABC" onkeyup="f_KeyUpPlaca();">
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<label style="font-weight: bold; margin-left: 5px; margin-top: 5px;">-</label>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-6">
											<input id="registro_placa2" type="text" class="form-control" style="text-align: center; margin-left: 2px;" placeholder="111">
										</div>

										<div class="col-md-7 col-sm-7 col-xs-7" style="text-align: right; margin-left: -8px;">
											<button id="btn_divrow2" type="button" class="btn" onclick="f_ValidacionCheck(2);" style="padding: 0px; padding-top: 9px;">
												<img id="img_divrow2" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row3" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Emp. Transporte:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 80%">
											<select id="registro_transportista" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddTransportista" type="button" class="btn" onclick="f_AddTransportista();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 2px;">
											<button id="btn_divrow3" type="button" class="btn" onclick="f_ValidacionCheck(3);" style="padding: 0px; margin-left: 26px; padding-top: 9px;">
												<img id="img_divrow3" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row4" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Tipo Vehículo:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 91%">
											<select id="registro_tipovehiculo" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_tipovehiculo = "SELECT Id,
																									UPPER(descripcion) AS descripcion
																						 FROM tbconfig_tipovehiculo
																						WHERE estado = 'A'
																					 ORDER BY descripcion";

												if ($res_tipovehiculo = mysqli_query($enlace, $q_tipovehiculo)){
													if (mysqli_num_rows($res_tipovehiculo) > 0) {
														while($row_tipovehiculo = mysqli_fetch_array($res_tipovehiculo)){
															?>

															<option value="<?php echo $row_tipovehiculo["Id"]; ?>"><?php echo $row_tipovehiculo["descripcion"]; ?></option>

															<option value="x" style="font-size: 6px;" disabled></option>

															<?php

															$t ++;
														}
													}
												}

												?>

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 3px;">
											<button id="btn_divrow4" type="button" class="btn" onclick="f_ValidacionCheck(4);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow4" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row5" class="row" style="padding: 5px; display: none;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Placa 2:
								</div>

								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="d-flex">
										<div class="col-md-5 col-sm-5 col-xs-5">
											<input id="registro_placa1_2" type="text" class="form-control" style="text-align: center; text-transform: uppercase;" placeholder="ABC" onkeyup="f_KeyUpPlaca2();">
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<label style="font-weight: bold; margin-left: 5px; margin-top: 5px;">-</label>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-6">
											<input id="registro_placa2_2" type="text" class="form-control" style="text-align: center; margin-left: 2px;" placeholder="111">
										</div>

										<div class="col-md-5 col-sm-5 col-xs-5">
											<label style="margin-left: 5px; margin-top: 10px; font-size: 14px;"><i>(Remolque)</i></label>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2" style="text-align: right; margin-left: -7px;">
											<button id="btn_divrow5" type="button" class="btn" onclick="f_ValidacionCheck(5);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow5" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row6" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Conductor:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 80%">
											<select id="registro_conductor" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddConductor" type="button" class="btn" onclick="f_AddConductor();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 2px;">
											<button id="btn_divrow6" type="button" class="btn" onclick="f_ValidacionCheck(6);" style="padding: 0px; margin-left: 26px; padding-top: 9px;">
												<img id="img_divrow6" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row7" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Tipo Carga:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 91%">
											<select id="registro_tipocarga" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_tipocarga = "SELECT Id,
																							 UPPER(descripcion) AS descripcion
																					FROM tbconfig_tipocarga
																				 WHERE estado = 'A'
																					 AND id_clientecondicion = 1
																				ORDER BY orden";

												if ($res_tipocarga = mysqli_query($enlace, $q_tipocarga)){
													if (mysqli_num_rows($res_tipocarga) > 0) {
														while($row_tipocarga = mysqli_fetch_array($res_tipocarga)){
															?>

															<option value="<?php echo $row_tipocarga["Id"]; ?>"><?php echo $row_tipocarga["descripcion"]; ?></option>

															<option value="x" style="font-size: 6px;" disabled></option>

															<?php

															$t ++;
														}
													}
												}

												?>

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 3px;">
											<button id="btn_divrow7" type="button" class="btn" onclick="f_ValidacionCheck(7);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow7" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row8" id="div_zonaorigen" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Zona Origen:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 80%">
											<select id="registro_zonaorigen" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddZonaOrigen" type="button" class="btn" onclick="f_AddZonaOrigen();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 2px;">
											<button id="btn_divrow8" type="button" class="btn" onclick="f_ValidacionCheck(8);" style="padding: 0px; margin-left: 26px; padding-top: 9px;">
												<img id="img_divrow8" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row10" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Proveedor Minero:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 91%">
											<select id="registro_proveedorminero" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_proveedorminero = "SELECT Id,
																										 documento,
																										 UPPER(razon_social) AS razon_social
																								FROM tb_clientes
																							 WHERE estado = 'A'
																								 AND cod_clientecondicion = 1
																							ORDER BY razon_social";

												if ($res_proveedorminero = mysqli_query($enlace, $q_proveedorminero)){
													if (mysqli_num_rows($res_proveedorminero) > 0) {
														while($row_proveedorminero = mysqli_fetch_array($res_proveedorminero)){
															?>

															<option value="<?php echo $row_proveedorminero["Id"]; ?>"><?php echo $row_proveedorminero["documento"].' - '.$row_proveedorminero["razon_social"]; ?></option>

															<option value="x" style="font-size: 6px;" disabled></option>

															<?php

															$t ++;
														}
													}
												}

												?>

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 3px;">
											<button id="btn_divrow10" type="button" class="btn" onclick="f_ValidacionCheck(10);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow10" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row11" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Encar. Muestra:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 80%">
											<select id="registro_encargado" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddEncargado" type="button" class="btn" onclick="f_AddEncargadoMuestra();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 2px;">
											<button id="btn_divrow11" type="button" class="btn" onclick="f_ValidacionCheck(11);" style="padding: 0px; margin-left: 26px; padding-top: 9px;">
												<img id="img_divrow11" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row12" id="div_producto" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Producto:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 91%">
											<select id="registro_producto" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_producto = "SELECT Id,
																							UPPER(descripcion) AS descripcion
																				 FROM tbconfig_producto
																				WHERE estado = 'A'
																			 ORDER BY descripcion";

												if ($res_producto = mysqli_query($enlace, $q_producto)){
													if (mysqli_num_rows($res_producto) > 0) {
														while($row_producto = mysqli_fetch_array($res_producto)){
															?>

															<option value="<?php echo $row_producto["Id"]; ?>"><?php echo $row_producto["descripcion"]; ?></option>

															<option value="x" style="font-size: 6px;" disabled></option>

															<?php

															$t ++;
														}
													}
												}

												?>

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 2px;">
											<button id="btn_divrow12" type="button" class="btn" onclick="f_ValidacionCheck(12);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow12" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row13" id="div_tipomaterial" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Tipo Material:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 91%">
											<select id="registro_tipomaterial" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_tipomaterial = "SELECT Id,
																									UPPER(descripcion) AS descripcion
																						 FROM tbconfig_tipomineral
																						WHERE estado = 'A'
																					 ORDER BY descripcion";

												if ($res_tipomaterial = mysqli_query($enlace, $q_tipomaterial)){
													if (mysqli_num_rows($res_tipomaterial) > 0) {
														while($row_tipomaterial = mysqli_fetch_array($res_tipomaterial)){
															?>

															<option value="<?php echo $row_tipomaterial["Id"]; ?>"><?php echo $row_tipomaterial["descripcion"]; ?></option>

															<option value="x" style="font-size: 6px;" disabled></option>

															<?php

															$t ++;
														}
													}
												}

												?>

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 2px;">
											<button id="btn_divrow13" type="button" class="btn" onclick="f_ValidacionCheck(13);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow13" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div id="div_row9" class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Observación:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 91%">
											<textarea id="registro_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1" style="margin-left: 3px;">
											<button id="btn_divrow9" type="button" class="btn" onclick="f_ValidacionCheck(9);" style="padding: 0px; margin-left: 15px; padding-top: 9px;">
												<img id="img_divrow9" src="<?php echo $img_check_red; ?>" style="width: 20px;">
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<input id="hd_idregistro" type="hidden">

					<div class="modal-footer">
						<div id="wt_grabarregistro" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

						<button type="button" class="btn btn-secondary wt_grabarregistro_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal_addcliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addclienteLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div id="modal_addcliente_content" class="modal-content" style="margin-top: 250px;">
					<div class="modal-header" style="background-color: #f8da62;">
						<h1 class="modal-title fs-5" id="modal_addclienteLabel">Nuevo Cliente</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Tipo Cliente:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="cliente_tipocliente" class="form-select" style="text-align: left;" onchange="f_GetListaTipoDocumento(0)">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_tipocliente = "SELECT Id,
																					 descripcion
																			FROM tbconfig_tipocliente
																		 WHERE estado = 'A'";

									if ($res_tipocliente = mysqli_query($enlace, $q_tipocliente)){
										if (mysqli_num_rows($res_tipocliente) > 0) {
											while($row_tipocliente = mysqli_fetch_array($res_tipocliente)){
												?>

												<option value="<?php echo $row_tipocliente["Id"]; ?>"><?php echo $row_tipocliente["descripcion"]; ?></option>

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
								Tipo Documento:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="cliente_tipodocumento" class="form-select" style="text-align: left;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_tipodocumento = "SELECT Id,
																						 descripcion
																				FROM tbconfig_tipodocumento
																			 WHERE estado = 'A'";

									if ($res_tipodocumento = mysqli_query($enlace, $q_tipodocumento)){
										if (mysqli_num_rows($res_tipodocumento) > 0) {
											while($row_tipodocumento = mysqli_fetch_array($res_tipodocumento)){
												?>

												<option value="<?php echo $row_tipodocumento["Id"]; ?>"><?php echo $row_tipodocumento["descripcion"]; ?></option>

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
								<input id="cliente_documento" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente(1);">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Razón Social: <img id="wt_razonsocial2" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="cliente_razonsocial" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Teléfonos:
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="cliente_telefono1" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="cliente_telefono2" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Correo:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="cliente_correo" type="email" class="form-control col-md-12 col-xs-12">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Dirección:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="cliente_direccion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
							</div>
						</div>
					</div>

					<input id="hd_idcliente" type="hidden">
					<input id="hd_modograbar" type="hidden">

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" onclick="f_GrabarCliente();">Grabar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal_gestionlotes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_gestionlotesLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content" style="width: 120%;">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="modal_gestionlotesLabel_a"></h1>
						<h1 class="modal-title fs-5" id="modal_gestionlotesLabel" style="margin-left: 10px; color: #9e6d14;"></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; margin-bottom: 5px;">
							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Tipo Carga:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lote_tipocarga" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_tipocarga = "SELECT Id,
																							 UPPER(descripcion) AS descripcion
																					FROM tbconfig_tipocarga
																				 WHERE estado = 'A'
																					 AND id_clientecondicion = 1
																				ORDER BY orden";

												if ($res_tipocarga = mysqli_query($enlace, $q_tipocarga)){
													if (mysqli_num_rows($res_tipocarga) > 0) {
														while($row_tipocarga = mysqli_fetch_array($res_tipocarga)){
															?>

															<option value="<?php echo $row_tipocarga["Id"]; ?>"><?php echo $row_tipocarga["descripcion"]; ?></option>

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
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Zona Origen:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 87%">
											<select id="lote_zonaorigen" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddZonaOrigen" type="button" class="btn" onclick="f_AddZonaOrigen();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Proveedor Minero:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lote_proveedorminero" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_proveedorminero = "SELECT Id,
																										 documento,
																										 UPPER(razon_social) AS razon_social
																								FROM tb_clientes
																							 WHERE estado = 'A'
																								 AND cod_clientecondicion = 1
																							ORDER BY razon_social";

												if ($res_proveedorminero = mysqli_query($enlace, $q_proveedorminero)){
													if (mysqli_num_rows($res_proveedorminero) > 0) {
														while($row_proveedorminero = mysqli_fetch_array($res_proveedorminero)){
															?>

															<option value="<?php echo $row_proveedorminero["Id"]; ?>"><?php echo $row_proveedorminero["documento"].' - '.$row_proveedorminero["razon_social"]; ?></option>

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
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Encargado Muestra:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 87%; position: relative;">
											<div hidden>
												<select id="lote_encargado" class="form-select" data-placeholder="Elija una opción...">

												</select>
											</div>

											<input id="txt_CodigoEncargadoMuestra" type="text" class="form-control" style="text-align: center; text-transform: uppercase;" onkeyup="f_ShowListaEncargadosMuestra();">

											<div id="div_ListaEncargadosMuestra" style="position: absolute; z-index: 1000; background-color: #D9D9D9; width: 100%; height: 250px; overflow-y: scroll; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; display: none;">
												<table id="tbl_EncargadosMuestra" class="table table-bordered table-hover" style="width: 100%;">

												</table>
											</div>
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<button id="btn_AddEncargado" type="button" class="btn" onclick="f_AddEncargadoMuestra();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Producto:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lote_producto" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_producto = "SELECT Id,
																							UPPER(descripcion) AS descripcion
																				 FROM tbconfig_producto
																				WHERE estado = 'A'
																			 ORDER BY descripcion";

												if ($res_producto = mysqli_query($enlace, $q_producto)){
													if (mysqli_num_rows($res_producto) > 0) {
														while($row_producto = mysqli_fetch_array($res_producto)){
															?>

															<option value="<?php echo $row_producto["Id"]; ?>"><?php echo $row_producto["descripcion"]; ?></option>

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
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Tipo Material:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<select id="lote_tipomaterial" class="form-select" data-placeholder="Elija una opción...">
												<option selected value="">Elija una opción...</option>
												<option value="x" style="font-size: 6px;" disabled></option>

												<?php

												$t = 1;

												$q_tipomaterial = "SELECT Id,
																									UPPER(descripcion) AS descripcion
																						 FROM tbconfig_tipomineral
																						WHERE estado = 'A'
																					 ORDER BY descripcion";

												if ($res_tipomaterial = mysqli_query($enlace, $q_tipomaterial)){
													if (mysqli_num_rows($res_tipomaterial) > 0) {
														while($row_tipomaterial = mysqli_fetch_array($res_tipomaterial)){
															?>

															<option value="<?php echo $row_tipomaterial["Id"]; ?>"><?php echo $row_tipomaterial["descripcion"]; ?></option>

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
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Observación:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 100%">
											<textarea id="lote_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="div_pesoinicial" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #f0efe8; padding: 5px; margin-bottom: 5px;">
							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; font-weight: bold; font-size: 14px; text-align: center;">
									Peso Inicial:
								</div>

								<div class="col-md-6 col-sm-6 col-xs-6">
									<input id="lote_pesoinicial" type="number" class="form-control obj_cab col-md-12 col-xs-12 show_pesoinicial" style="text-align: center; font-weight: bold; font-size: 15px;" onclick="f_getPeso(1);" onblur="f_getPesoOff();">
								</div>

								<div id="div_InterfazAuto" class="col-md-2 col-sm-2 col-xs-2">
									<div class="col-md-2 col-sm-2 col-xs-2" style="margin-left: 10px; margin-top: 7px;">
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" id="chk_InterfazAuto" onchange="f_getPeso(1);" checked>
											<label id="lbl_getpeso_check" class="form-check-label" for="chk_InterfazAuto">Auto.</label>
										</div>
									</div>
								</div>
							</div>

							<div id="div_SinConexion" class="col-md-10 col-sm-10 col-xs-10" style="text-align: right; display: none;">
								<label class="control-label" style="color: #d9534f; font-size: 12px;">
									Se perdió la conexión con la balanza
								</label>
							</div>
						</div>

						<div id="div_pesofinal" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #f0efe8; padding: 5px; margin-bottom: 5px;">
							<div id="div_InterfazAuto_pesofinal" class="d-flex">
								<div class="col-md-9 col-sm-9 col-xs-9">
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2">
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" role="switch" id="chk_InterfazAuto_pesofinal" onchange="f_getPeso(1);" checked>
										<label id="lbl_getpeso_check" class="form-check-label" for="chk_InterfazAuto_pesofinal">Auto.</label>
									</div>
								</div>
							</div>

							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; font-size: 14px;">
									Peso Final:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<input id="lote_pesofinal" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold; font-size: 15px;" onclick="f_getPeso(1);" onblur="f_getPesoOff();" onkeyup="f_CalculaPesoNeto();">
								</div>
							</div>

							<div id="div_SinConexion_pesofinal" class="col-md-10 col-sm-10 col-xs-10" style="text-align: right; display: none;">
								<label class="control-label" style="color: #d9534f; font-size: 12px;">
									Se perdió la conexión con la balanza
								</label>
							</div>

							<div class="d-flex" style="padding: 5px; margin-top: -7px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-size: 14px;">
									Observación:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<textarea id="lote_pesofinal_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
								</div>
							</div>
						</div>

						<div id="div_pesobruto" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; padding: 5px;">
							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; color: #ffffff;">
									Peso Bruto:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<input id="lote_pesobruto" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold;" disabled>
								</div>
							</div>
						</div>

						<div id="div_tara" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; padding: 5px;">
							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; color: #ffffff;">
									Tara:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<input id="lote_tara" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold;" disabled>
								</div>
							</div>
						</div>

						<div id="div_pesoneto" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #816951; padding: 5px;">
							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; font-weight: bold; color: #ffffff;">
									Peso Neto:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<input id="lote_pesoneto" type="number" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; font-weight: bold;" disabled>
								</div>
							</div>
						</div>
					</div>

					<input id="hd_ingreso" type="hidden">
					<input id="hd_idlote" type="hidden">
					<input id="hd_ispesoinicial" type="hidden">

					<div class="modal-footer">
						<div id="wt_grabarlote" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

						<button type="button" class="btn btn-secondary wt_grabarlote_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>

						 <button type="button" class="btn btn-warning wt_grabarlote_button" style="font-size: 14px;" onclick="f_ConfirmarLote();">Confirmar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal_addconductor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addconductorLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div id="modal_addconductor_content" class="modal-content" style="margin-top: 346px;">
					<div class="modal-header" style="background-color: #f8da62;">
						<h1 class="modal-title fs-5" id="modal_addconductorLabel">Nuevo Conductor</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								DNI / Licencia:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="conductor_dni" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente(2);">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Nombres: <img id="wt_conductor" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="conductor_nombres" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" onclick="f_GrabarConductor();">Grabar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal_addzonaorigen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addzonaorigenLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div id="modal_addzonaorigen_content" class="modal-content" style="margin-top: 394px;">
					<div class="modal-header" style="background-color: #f8da62;">
						<h1 class="modal-title fs-5" id="modal_addzonaorigenLabel">Nueva Zona de Origen</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Zona Origen:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="zona_origen" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" onclick="f_GrabarZonaOrigen();">Grabar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal_addencargadomuestra" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addencargadomuestraLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div id="modal_addencargadomuestra_content" class="modal-content" style="margin-top: 346px;">
					<div class="modal-header" style="background-color: #f8da62;">
						<h1 class="modal-title fs-5" id="modal_addencargadomuestraLabel">Nuevo Encargado de Muestra</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								DNI:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="encargado_dni" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente(3);">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Nombres: <img id="wt_encargado" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="encargado_nombres" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" onclick="f_GrabarEncargadoMuestra();">Grabar</button>
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
					$("#nv_titulo").html('| Captura de Pesos del Primer Tramo');

				// Carga Tarjetas
					f_LoadCards_All();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			$('#registro_condicion, #registro_transportista, #registro_tipovehiculo, #registro_conductor, #registro_tipocarga, #registro_zonaorigen, #registro_producto, #registro_tipomaterial, #registro_proveedorminero, #registro_encargado').select2({
				theme: "bootstrap-5",
				width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
				placeholder: $( this ).data( 'placeholder' ),
				allowClear: true,
				dropdownParent: $('#modal_unidadvalidacion')
			});

			$('#lote_tipocarga, #lote_zonaorigen, #lote_proveedorminero, #lote_producto, #lote_tipomaterial').select2({
				theme: "bootstrap-5",
				width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
				placeholder: $( this ).data( 'placeholder' ),
				allowClear: true,
				dropdownParent: $('#modal_gestionlotes')
			});

			$('#lote_encargado').select2({
				theme: "bootstrap-5",
				width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
				placeholder: $( this ).data( 'placeholder' ),
				allowClear: true,
				dropdownParent: $('#modal_gestionlotes')
			});
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadCards_All(){
				f_LoadCards_IngresoUnidades();
				f_LoadCards_UnidadesValidacion();
				f_LoadCards_LotesPesoFinal();
			}

			function f_LoadCards_IngresoUnidades(){
				f_LoadingIngresoUnidades(1);

				$.post( "apis/backend.php", { accion: "get_ListaIngresoUnidades_Cards" }, 
					function( data ) {
						if(data.estado == 1){
							$("#div_ingresounidades").html(data.html);
						}
						else{
							$("#div_ingresounidades").html('');
						}

						f_LoadingIngresoUnidades(0);

						setTimeout('f_LoadCards_IngresoUnidades()', 60000);

					}, "json");
			}

			function f_LoadCards_UnidadesValidacion(){
				f_LoadingIngresoUnidades_Validacion(1);

				$.post( "apis/backend.php", { accion: "get_ListaIngresoUnidadesValidacion_Cards" }, 
					function( data ) {
						if(data.estado == 1){
							$("#div_ingresounidades_validacion").html(data.html);
						}
						else{
							$("#div_ingresounidades_validacion").html('');
						}

						f_LoadingIngresoUnidades_Validacion(0);

					}, "json");
			}

			function f_LoadCards_LotesPesoFinal(){
				f_LoadingLotesPesoFinal(1);

				// Buscando checks seleccionados de Unidades
					var c = 1;
					var _html = '';
					var _arr_unidades = '';

					while (c < 1000){
						_html = $("#chk_Unidad_" + c);

						if (_html.html() == undefined){
							break
						}
						else{
							if ($("#chk_Unidad_" + c).prop('checked')){
								_arr_unidades += $("#id_unidadingreso_" + c).val() + ', ';
							}
						}

						c ++;
					}

					if (_arr_unidades.length > 0){
						_arr_unidades = _arr_unidades.substring(0, _arr_unidades.length - 2);
					}

				$.post( "apis/backend.php", { accion: "get_ListaLotesPesoFinal_Cards", arr_unidades: _arr_unidades }, 
					function( data ) {
						if(data.estado == 1){
							$("#div_lotespesofinal").html(data.html);
						}
						else{
							$("#div_lotespesofinal").html('');
						}

						f_LoadingLotesPesoFinal(0);

					}, "json");
			}

			function f_ValidarUnidad(_arr_validacion, _orden_objeto){
				// Recupera los datos de la unidad
					var _id_registro = _arr_validacion.split('|')[0];
					var _id_tipoingresounidad = _arr_validacion.split('|')[1].split('%')[0];
					var _id_tipoingresounidad_checked = _arr_validacion.split('|')[1].split('%')[1];
					var _placa = _arr_validacion.split('|')[2].split('%')[0];
					var _placa_checked = _arr_validacion.split('|')[2].split('%')[1];
					var _id_transportista = _arr_validacion.split('|')[3].split('%')[0];
					var _id_transportista_checked = _arr_validacion.split('|')[3].split('%')[1];
					var _id_tipovehiculo = _arr_validacion.split('|')[4].split('%')[0];
					var _id_tipovehiculo_checked = _arr_validacion.split('|')[4].split('%')[1];
					var _placa2 = _arr_validacion.split('|')[5].split('%')[0];
					var _placa2_checked = _arr_validacion.split('|')[5].split('%')[1];
					var _id_choferes = _arr_validacion.split('|')[6].split('%')[0];
					var _id_choferes_checked = _arr_validacion.split('|')[6].split('%')[1];
					var _id_tipocarga = _arr_validacion.split('|')[7].split('%')[0];
					var _id_tipocarga_checked = _arr_validacion.split('|')[7].split('%')[1];
					var _id_zonaorigen = _arr_validacion.split('|')[8].split('%')[0];
					var _id_zonaorigen_checked = _arr_validacion.split('|')[8].split('%')[1];
					var _id_proveedorminero = _arr_validacion.split('|')[9].split('%')[0];
					var _id_proveedorminero_checked = _arr_validacion.split('|')[9].split('%')[1];
					var _id_encargado = _arr_validacion.split('|')[10].split('%')[0];
					var _id_encargado_checked = _arr_validacion.split('|')[10].split('%')[1];
					var _id_producto = _arr_validacion.split('|')[11].split('%')[0];
					var _id_producto_checked = _arr_validacion.split('|')[11].split('%')[1];
					var _id_tipomineral = _arr_validacion.split('|')[12].split('%')[0];
					var _id_tipomineral_checked = _arr_validacion.split('|')[12].split('%')[1];
					var _cNotas = _arr_validacion.split('|')[13].split('%')[0];
					var _cNotas_checked = _arr_validacion.split('|')[13].split('%')[1];

				// Oculta objetos
					$("#div_row1").hide();
					$("#div_row2").hide();
					$("#div_row3").hide();
					$("#div_row4").hide();
					$("#div_row5").hide();
					$("#div_row6").hide();
					$("#div_row7").hide();
					$("#div_row8").hide();
					$("#div_row10").hide();
					$("#div_row11").hide();
					$("#div_row12").hide();
					$("#div_row13").hide();
					$("#div_row9").hide();

				// Habilita objetos
					$("#registro_condicion").prop('disabled', false);
					$("#registro_placa1").prop('disabled', false);
					$("#registro_placa2").prop('disabled', false);
					$("#registro_transportista").prop('disabled', false);
					$("#btn_AddTransportista").prop('disabled', false);
					$("#registro_tipovehiculo").prop('disabled', false);
					$("#registro_placa1_2").prop('disabled', false);
					$("#registro_placa2_2").prop('disabled', false);
					$("#registro_conductor").prop('disabled', false);
					$("#btn_AddConductor").prop('disabled', false);
					$("#registro_tipocarga").prop('disabled', false);
					$("#registro_zonaorigen").prop('disabled', false);
					$("#btn_AddZonaOrigen").prop('disabled', false);
					$("#registro_proveedorminero").prop('disabled', false);
					$("#registro_encargado").prop('disabled', false);
					$("#btn_AddEncargado").prop('disabled', false);
					$("#registro_producto").prop('disabled', false);
					$("#registro_tipomaterial").prop('disabled', false);
					$("#registro_observacion").prop('disabled', false);

				// Setea título
					$("#modal_unidadvalidacionLabel").html(_placa);

				// Mostrando modal
					f_OpenModal('modal_unidadvalidacion');

				// Setea los datos de la unidad
					var show_next = 0;
					var show_placa2 = 0;

					$("#hd_idregistro").val(_id_registro);
					$("#registro_condicion").val(_id_tipoingresounidad);
					$("#registro_condicion").trigger('change');
					$("#registro_placa1").val(_placa.split('-')[0]);
					$("#registro_placa2").val(_placa.split('-')[1]);
					$("#registro_transportista").val(_id_transportista);
					$("#registro_transportista").trigger('change');
					$("#registro_tipovehiculo").val(_id_tipovehiculo);
					$("#registro_tipovehiculo").trigger('change');
					$("#registro_placa1_2").val(_placa2.split('-')[0]);
					$("#registro_placa2_2").val(_placa2.split('-')[1]);
					$("#registro_conductor").val(_id_choferes);
					$("#registro_tipocarga").val(_id_tipocarga);
					$("#registro_tipocarga").trigger('change');
					$("#registro_zonaorigen").val(_id_zonaorigen);
					$("#registro_zonaorigen").trigger('change');
					$("#registro_proveedorminero").val(_id_proveedorminero);
					$("#registro_proveedorminero").trigger('change');
					$("#registro_encargado").val(_id_encargado);
					$("#registro_encargado").trigger('change');
					$("#registro_producto").val(_id_producto);
					$("#registro_producto").trigger('change');
					$("#registro_tipomaterial").val(_id_tipomineral);
					$("#registro_tipomaterial").trigger('change');
					$("#registro_observacion").val(_cNotas);

					// Tipo Ingreso
						if (_id_tipoingresounidad_checked == 1){
							$("#registro_condicion").prop('disabled', true);
							
							$("#btn_divrow1").attr('onclick', '');
							$("#img_divrow1").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#btn_divrow1").attr('onclick', 'f_ValidacionCheck(1)');
							$("#img_divrow1").attr('src', 'images/check_red.png');
						}

						$("#div_row1").show(1000);

					// Placa
						if (show_next == 1){
							$("#div_row2").show(1000);
						}
						else{
							// return;
						}

						if (_placa_checked == 1){
							$("#registro_placa1").prop('disabled', true);
							$("#registro_placa2").prop('disabled', true);
							
							$("#btn_divrow2").attr('onclick', '');
							$("#img_divrow2").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#btn_divrow2").attr('onclick', 'f_ValidacionCheck(2)');
							$("#img_divrow2").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Transportista
						// Carga Lista de Transportistas
							f_LoadListaTransportistas(_id_transportista);

						if (show_next == 1){
							$("#div_row3").show(1000);
						}
						else{
							// return;
						}

						if (_id_transportista_checked == 1){
							$("#registro_transportista").prop('disabled', true);
							$("#btn_AddTransportista").prop('disabled', true);
							
							$("#btn_divrow3").attr('onclick', '');
							$("#img_divrow3").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#btn_divrow3").attr('onclick', 'f_ValidacionCheck(3)');
							$("#img_divrow3").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Tipo Vehículo
						if (show_next == 1){
							$("#div_row4").show(1000);
						}
						else{
							// return;
						}

						if (_id_tipovehiculo_checked == 1){
							$("#registro_tipovehiculo").prop('disabled', true);
							
							$("#btn_divrow4").attr('onclick', '');
							$("#img_divrow4").attr('src', 'images/check.png');

							show_next = 1;

							if (_id_tipovehiculo == 3 || _id_tipovehiculo == 4 || _id_tipovehiculo == 6){
								show_placa2 = 1;
							}
						}
						else{
							$("#btn_divrow4").attr('onclick', 'f_ValidacionCheck(4)');
							$("#img_divrow4").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Placa 2
						if (show_placa2 == 1){
							$("#div_row5").show(1000);

							if (_placa2_checked == 1){
								$("#registro_placa1_2").prop('disabled', true);
								$("#registro_placa2_2").prop('disabled', true);
								
								$("#btn_divrow5").attr('onclick', '');
								$("#img_divrow5").attr('src', 'images/check.png');

								show_next = 1;
							}
							else{
								$("#btn_divrow5").attr('onclick', 'f_ValidacionCheck(5)');
								$("#img_divrow5").attr('src', 'images/check_red.png');

								show_next = 0;

								// return;
							}
						}
						else{
							// return;
						}

					// Conductor
						// Carga Lista de Conductores
							f_LoadListaConductores(_id_choferes);

						if (show_next == 1){
							$("#div_row6").show(1000);
						}
						else{
							// return;
						}

						if (_id_choferes_checked == 1){
							$("#registro_conductor").prop('disabled', true);
							$("#btn_AddConductor").prop('disabled', true);
							
							$("#btn_divrow6").attr('onclick', '');
							$("#img_divrow6").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#btn_divrow6").attr('onclick', 'f_ValidacionCheck(6)');
							$("#img_divrow6").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Tipo Carga
						if (show_next == 1){
							$("#div_row7").show(1000);
						}
						else{
							// return;
						}

						if (_id_tipocarga_checked == 1){
							$("#registro_tipocarga").prop('disabled', true);
							
							$("#btn_divrow7").attr('onclick', '');
							$("#img_divrow7").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#img_divrow7").attr('onclick', 'f_ValidacionCheck(7)');
							$("#img_divrow7").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Zona Origen
						// Carga Lista de Zonas de Origen
							f_LoadListaZonaOrigen(_id_zonaorigen);

						if (show_next == 1){
							$("#div_row8").show(1000);
						}
						else{
							// return;
						}

						if (_id_zonaorigen_checked == 1){
							$("#registro_zonaorigen").prop('disabled', true);
							$("#btn_AddZonaOrigen").prop('disabled', true);
							
							$("#btn_divrow8").attr('onclick', '');
							$("#img_divrow8").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#btn_divrow8").attr('onclick', 'f_ValidacionCheck(8)');
							$("#img_divrow8").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Proveedor minero
						if (show_next == 1){
							$("#div_row10").show(1000);
						}
						else{
							// return;
						}

						if (_id_proveedorminero_checked == 1){
							$("#registro_proveedorminero").prop('disabled', true);
							
							$("#btn_divrow10").attr('onclick', '');
							$("#img_divrow10").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#img_divrow10").attr('onclick', 'f_ValidacionCheck(10)');
							$("#img_divrow10").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Encargado de Muestra
						// Carga Lista de Encargados de Muestra
							f_LoadListaEncargadosMuestra(_id_encargado);

						if (show_next == 1){
							$("#div_row11").show(1000);
						}
						else{
							// return;
						}

						if (_id_encargado_checked == 1){
							$("#registro_encargado").prop('disabled', true);
							$("#btn_AddEncargado").prop('disabled', true);
							
							$("#btn_divrow11").attr('onclick', '');
							$("#img_divrow11").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#btn_divrow11").attr('onclick', 'f_ValidacionCheck(11)');
							$("#img_divrow11").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Producto
						if (show_next == 1){
							$("#div_row12").show(1000);
						}
						else{
							// return;
						}

						if (_id_producto_checked == 1){
							$("#registro_producto").prop('disabled', true);
							
							$("#btn_divrow12").attr('onclick', '');
							$("#img_divrow12").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#img_divrow12").attr('onclick', 'f_ValidacionCheck(12)');
							$("#img_divrow12").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Tipo Material
						if (show_next == 1){
							$("#div_row13").show(1000);
						}
						else{
							// return;
						}

						if (_id_tipomineral_checked == 1){
							$("#registro_tipomaterial").prop('disabled', true);
							
							$("#btn_divrow13").attr('onclick', '');
							$("#img_divrow13").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#img_divrow13").attr('onclick', 'f_ValidacionCheck(13)');
							$("#img_divrow13").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}

					// Observación
						if (show_next == 1){
							$("#div_row9").show(1000);
						}
						else{
							// return;
						}

						if (_cNotas_checked == 1){
							$("#registro_observacion").prop('disabled', true);
							
							$("#btn_divrow9").attr('onclick', '');
							$("#img_divrow9").attr('src', 'images/check.png');

							show_next = 1;
						}
						else{
							$("#btn_divrow9").attr('onclick', 'f_ValidacionCheck(9)');
							$("#img_divrow9").attr('src', 'images/check_red.png');

							show_next = 0;

							// return;
						}
			}

			function f_NuevoLote(_id_ingreso){
				if (!confirm("¿Está seguro de crear un nuevo lote?")){
					return;
				}

				// Creando Lote
					$.post( "apis/backend.php", { accion: "crear_NuevoLote", id_ingreso: _id_ingreso }, 
						function( data ) {
							if(data.estado == 1){
								f_LoadCards_UnidadesValidacion();
								f_LoadCards_LotesPesoFinal();
							}

						}, "json");
			}

			function f_GestionLotes(_id_ingreso, _id_lote, _placa, _is_pesoinicial, _peso_inicial, _pesoinicial_observacion, _cod_aum, _num_ticket, balanza_id_tipocarga, balanza_id_zonaorigen, balanza_id_proveedorminero, balanza_id_encargadomuestra, balanza_id_producto, balanza_id_tipomineral, balanza_observacion){
				// Setea título
					if (_is_pesoinicial == 1){
						$("#modal_gestionlotesLabel_a").html('Peso Inicial para Lote: ');
						$("#modal_gestionlotesLabel").html(_placa);
					}
					else{
						$("#modal_gestionlotesLabel_a").html('Peso Final para Lote: ');
						$("#modal_gestionlotesLabel").html(_cod_aum + '<label style="color: #212529; margin-left: 5px; margin-right: 5px;"> | </label>' + _placa);
					}

				// Setea Id de registro
					$("#hd_ingreso").val(_id_ingreso);
					$("#hd_idlote").val(_id_lote);
					$("#hd_ispesoinicial").val(_is_pesoinicial);

				// Cargando Listas
					if (_is_pesoinicial == 1){
						f_LoadListaZonaOrigen(0);
						f_LoadListaEncargadosMuestra(0);
					}
					else{
						f_LoadListaZonaOrigen(balanza_id_zonaorigen);
						f_LoadListaEncargadosMuestra(balanza_id_encargadomuestra);
					}

				// Limpiando campos
					if (_is_pesoinicial == 1){
						$("#lote_tipocarga").val('');
						$("#lote_zonaorigen").val('');
						$("#lote_proveedorminero").val('');
						$("#lote_encargado").val('');
						$("#lote_producto").val('');
						$("#lote_tipomaterial").val('');
						$("#lote_observacion").val('');
					}
					else{
						$("#lote_tipocarga").val(balanza_id_tipocarga);
						$("#lote_tipocarga").trigger('change');
						$("#lote_zonaorigen").val(balanza_id_zonaorigen);
						$("#lote_zonaorigen").trigger('change');
						$("#lote_proveedorminero").val(balanza_id_proveedorminero);
						$("#lote_proveedorminero").trigger('change');
						$("#lote_encargado").val(balanza_id_encargadomuestra);
						$("#lote_encargado").trigger('change');
						$("#lote_producto").val(balanza_id_producto);
						$("#lote_producto").trigger('change');
						$("#lote_tipomaterial").val(balanza_id_tipomineral);
						$("#lote_tipomaterial").trigger('change');
						$("#lote_observacion").val(balanza_observacion);
					}

				// Setea objetos
					if (_is_pesoinicial == 1){
						$(".show_pesoinicial").prop('disabled', false);
						$("#lote_pesoinicial").val('');
						$("#lote_pesoinicial_observacion").val('');
						$("#chk_InterfazAuto").prop('checked', true);

						f_ReplaceClass("div_InterfazAuto", '', 'd-flex');
						$("#div_InterfazAuto").show();
						$("#div_pesofinal").hide();
						$("#div_pesobruto").hide();
						$("#div_tara").hide();
						$("#div_pesoneto").hide();
					}
					else{
						$(".show_pesoinicial").prop('disabled', true);
						$("#lote_pesobruto").val(_peso_inicial);
						$("#lote_pesoinicial").val(_peso_inicial);
						$("#lote_pesoinicial_observacion").val(_pesoinicial_observacion);
						$("#lote_pesofinal").val('');
						$("#lote_pesofinal_observacion").val('');
						$("#lote_tara").val('');
						$("#lote_pesoneto").val('');
						$("#chk_InterfazAuto_pesofinal").prop('checked', true);

						f_ReplaceClass("div_InterfazAuto", 'd-flex', '');
						$("#div_InterfazAuto").hide();

						$("#div_pesofinal").show();
						$("#div_pesobruto").show();
						$("#div_tara").show();
						$("#div_pesoneto").show();
					}

					f_getPeso(1);

				f_OpenModal('modal_gestionlotes');
			}

			function f_getPeso(_on){
				var is_pesoinicial = $("#hd_ispesoinicial").val();

				// Validando el check de envío automático
					if (is_pesoinicial == 1){
						if (!$("#chk_InterfazAuto").prop('checked')){
							return;
						}
					}
					else{
						if (!$("#chk_InterfazAuto_pesofinal").prop('checked')){
							return;
						}
					}

				if (_on == 1){
					find_peso = 1;
				}

				if (find_peso == 1){
					$.get( "apis/interfaces.php", { accion: "get_Peso", cod_balanza: 1 }, 
						function( data ) {
							if(data.estado == 1){
								if (data.peso == -1){
									if (is_pesoinicial == 1){
										$("#div_SinConexion").show();
									}
									else{
										$("#div_SinConexion_pesofinal").show();
									}

									return;
								}
								else{
									if (is_pesoinicial == 1){
										$("#div_SinConexion").hide();

										$("#lote_pesoinicial").val(data.peso);
									}
									else{
										$("#div_SinConexion_pesofinal").hide();

										$("#lote_pesofinal").val(data.peso);

										f_CalculaPesoNeto();
									}
								}
							}
							else{
								if (is_pesoinicial == 1){
									$("#div_SinConexion").show();
								}
								else{
									$("#div_SinConexion_pesofinal").show();
								}
							}

							setTimeout('f_getPeso()', 500);

						}, "json");
				}
			}

			function f_getPesoOff(){
				// f_getPeso(0);
				find_peso = 0;
			}

			function f_CalculaPesoNeto(){
				var peso_inicio = $("#lote_pesoinicial").val().trim();
				var peso_fin = $("#lote_pesofinal").val().trim();

				// Validando datos
					var peso_neto = '0';

					if (peso_inicio.length == 0){
						peso_neto = '';
					}

					if (peso_inicio < 0){
						peso_neto = '';
					}

					if (peso_fin.length == 0){
						peso_neto = '';
					}

					if (peso_fin < 0){
						peso_neto = '';
					}

				// Calculando Peso Neto
					if (peso_neto.length > 0){
						peso_neto = Math.abs(parseFloat(peso_inicio) - parseFloat(peso_fin));
					}

					$("#lote_tara").val(peso_fin);
					$("#lote_pesoneto").val(peso_neto);
			}

			function f_PrintCodigosBarra(_id_md5){
				url = 'print_etiquetashumedad.php?x=' + _id_md5;
				
				window.open(url, '_blank');
			}

			function f_SelectListaEncargadosMuestra(_id_encargadomuestra){
				$("#lote_encargado").val(_id_encargadomuestra);
				$("#lote_encargado").trigger('change');

				$("#txt_CodigoEncargadoMuestra").val($("#td_CodigoEncargadoMuestra_" + _id_encargadomuestra).html().trim());

				$("#div_ListaEncargadosMuestra").hide();
			}

			function f_ShowListaEncargadosMuestra(){
				var input = $("#txt_CodigoEncargadoMuestra").val().toLowerCase().trim();

				$("#div_ListaEncargadosMuestra").show();

				var rows = $('#tbl_EncargadosMuestra tr'); // Selecciona todas las filas de la tabla

        if (input.length >= 3) { // Solo buscar si hay 3 o más caracteres
          rows.each(function(index) {
            var secondColumnText = $(this).find('td:eq(1)').text().toLowerCase();

            if (secondColumnText.indexOf(input) > -1) {
                $(this).show(); // Muestra la fila si hay coincidencia
            } else {
                $(this).hide(); // Oculta la fila si no hay coincidencia
            }
          });
        }
        else{
          rows.show(); // Muestra todas las filas si hay menos de 3 caracteres
        }
			}
		</script>

		<!-- Funciones varias para complementar -->
		<script type="text/javascript">
			function f_LoadListaTransportistas(_id_cliente){
				var _html = '<option></option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				$("#registro_transportista").html('');

				$.post( "apis/backend.php", { accion: "get_listaclientes", cod_condicion: 2 }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.res, function( key, val ) {
								_html += '<option value="' + val.Id + '" ' + ((_id_cliente > 0) ? ((_id_cliente == val.Id) ? 'selected' : '') : '') + '>' + val.razon_social.toUpperCase() + '</option>';
							});
						}
						else{
							// alert("No se encontraron resultados.");
						}

						$("#registro_transportista").html(_html);

					}, "json");
			}

			function f_LoadListaConductores(_id_conductor){
				var _html = '<option></option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				$("#registro_conductor").html('');

				$.post( "apis/backend.php", { accion: "get_ListaConductores" }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.registros, function( key, val ) {
								_html += '<option value="' + val.Id + '" ' + ((_id_conductor > 0) ? ((_id_conductor == val.Id) ? 'selected' : '') : '') + '>' + val.nombres.toUpperCase() + '</option>';

								_html += '<option value="x" style="font-size: 6px;" disabled></option>';
							});
						}
						else{
							// alert("No se encontraron resultados.");
						}

						$("#registro_conductor").html(_html);

					}, "json");
			};

			function f_LoadListaTipoCarga(){
				var _html = '<option></option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				var id_condicion = $("#registro_condicion").val();

				$("#registro_tipocarga").html('');

				$.post( "apis/backend.php", { accion: "get_ListaTipoCarga", id_condicion: id_condicion }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.registros, function( key, val ) {
								_html += '<option value="' + val.Id + '">' + val.descripcion + '</option>';

								_html += '<option value="x" style="font-size: 6px;" disabled></option>';
							});
						}
						else{
							// alert("No se encontraron resultados.");
						}

						$("#registro_tipocarga").html(_html);

					}, "json");

				// Seteando la Zona de Origen
					$("#registro_zonaorigen").val('');
					$("#registro_zonaorigen").trigger('change');

					if (id_condicion == 1){
						$("#div_zonaorigen").show();
					}
					else{
						$("#div_zonaorigen").hide();
					}
			}

			function f_LoadListaZonaOrigen(_id_zonaorigen){
				var _html = '<option></option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				// $("#registro_zonaorigen").html('');
				$("#lote_zonaorigen").html('');

				$.post( "apis/backend.php", { accion: "get_ListaZonaOrigen" }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.registros, function( key, val ) {
								_html += '<option value="' + val.Id + '" ' + ((_id_zonaorigen > 0) ? ((_id_zonaorigen == val.Id) ? 'selected' : '') : '') + '>' + val.descripcion.toUpperCase() + '</option>';

								_html += '<option value="x" style="font-size: 6px;" disabled></option>';
							});
						}
						else{
							// alert("No se encontraron resultados.");
						}

						// $("#registro_zonaorigen").html(_html);
						$("#lote_zonaorigen").html(_html);

					}, "json");
			};

			function f_LoadListaEncargadosMuestra(_id_encarado){
				var _html_tbl = '';
				var _html = '<option></option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				// $("#registro_encargado").html('');
				$("#lote_encargado").html('');

				$.post( "apis/backend.php", { accion: "get_ListaEncargadosMuestra" }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.registros, function( key, val ) {
								// _html += '<option value="' + val.Id + '" ' + ((_id_encarado > 0) ? ((_id_encarado == val.Id) ? 'selected' : '') : '') + '>' + val.nombres.toUpperCase() + '</option>';
								_html += '<option value="' + val.Id + '" ' + ((_id_encarado > 0) ? ((_id_encarado == val.Id) ? 'selected' : '') : '') + '>' + val.codigo + '</font></option>';

								_html += '<option value="x" style="font-size: 6px;" disabled></option>';

								// Armando la tabla de Lista de Encargados de Muestra
									_html_tbl += '<tr style="font-size: 14px; cursor: pointer;" onclick="f_SelectListaEncargadosMuestra(' + val.Id + ');">';
									_html_tbl += '  <td id="td_CodigoEncargadoMuestra_' + val.Id + '" style="text-align: center;">';
									_html_tbl += '    ' + val.codigo;
									_html_tbl += '  </td>';
									_html_tbl += '  <td hidden>';
									_html_tbl += '    ' + val.nombres;
									_html_tbl += '  </td>';
									_html_tbl += '</tr>';
							});
						}

						// $("#registro_encargado").html(_html);
						$("#lote_encargado").html(_html);
						$("#tbl_EncargadosMuestra").html(_html_tbl);

					}, "json");
			};

			function f_AddTransportista(){
				// Definiendo título de ventana e Inicilizando controles de tipo texto
					var tipo = "N";
					var titulo = "Nuevo Transportista";

				// Colocando el título a la pantalla
					$("#modal_addclienteLabel").html(titulo);

				// Identificando el tipo de grabación
					$("#hd_modograbar").val(tipo);

				// Cargando datos
					f_OpenModal('modal_addcliente');

					$("#hd_idcliente").val(0);
					$("#cliente_condicion").val(2);
					$("#cliente_tipocliente").val('');
					$("#cliente_tipodocumento").val('');
					$("#cliente_documento").val('');
					$("#cliente_razonsocial").val('');
					$("#cliente_telefono1").val('');
					$("#cliente_telefono2").val('');
					$("#cliente_correo").val('');
					$("#cliente_direccion").val('');
			}

			function f_AddConductor(){
				// Definiendo título de ventana e Inicilizando controles de tipo texto
					var tipo = "N";
					var titulo = "Nuevo Conductor";

				// Colocando el título a la pantalla
					$("#modal_addconductorLabel").html(titulo);

				// Identificando el tipo de grabación

				// Cargando datos
					f_OpenModal('modal_addconductor');

					$("#conductor_dni").val('');
					$("#conductor_nombres").val('');
			}

			function f_AddZonaOrigen(){
				// Definiendo título de ventana e Inicilizando controles de tipo texto
					var tipo = "N";
					var titulo = "Nueva Zona de Origen";

				// Colocando el título a la pantalla
					$("#modal_addzonaorigenLabel").html(titulo);

				// Identificando el tipo de grabación

				// Cargando datos
					f_OpenModal('modal_addzonaorigen');

					$("#zona_origen").val('');
			}

			function f_AddEncargadoMuestra(){
				// Definiendo título de ventana e Inicilizando controles de tipo texto
					var tipo = "N";
					var titulo = "Nuevo Encargado de Muestra";

				// Colocando el título a la pantalla
					$("#modal_addencargadomuestraLabel").html(titulo);

				// Identificando el tipo de grabación

				// Cargando datos
					f_OpenModal('modal_addencargadomuestra');

					$("#encargado_dni").val('');
					$("#encargado_nombres").val('');
			}

			function f_GetInfoCliente(_id_modulo){
				var is_ruc = 0;
				var documento = '';

				if (_id_modulo == 1){
					is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
					documento = $("#cliente_documento").val();
				}
				
				if (_id_modulo == 2){
					is_ruc = 0;
					documento = $("#conductor_dni").val();
				}
				
				if (_id_modulo == 3){
					is_ruc = 0;
					documento = $("#encargado_dni").val();
				}

				var arr_response = '';

				// Limpiando objetos
					if (_id_modulo == 1){
						$("#cliente_razonsocial").val('');
						$("#cliente_direccion").val('');
						$("#wt_razonsocial2").hide();
					}

					if (_id_modulo == 2){
						$("#conductor_nombres").val('');
						$("#wt_conductor").hide();
					}

					if (_id_modulo == 3){
						$("#encargado_nombres").val('');
						$("#wt_encargado").hide();
					}

				// Obteniendo información
					if (documento.length == 8 || documento.length == 11){
						if (_id_modulo == 1){
							$("#wt_razonsocial2").show();
						}

						if (_id_modulo == 2){
							$("#wt_conductor").show();
						}

						if (_id_modulo == 3){
							$("#wt_encargado").show();
						}

						$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
							function( data ) {
								if (data.estado == 1){
									arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

									if (is_ruc == 1){
										$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
										$("#cliente_direccion").val(arr_response[4].split(':')[1].trim());
									}
									else{
										if (_id_modulo == 1){
											$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
											$("#cliente_direccion").val('');
										}

										if (_id_modulo == 2){
											$("#conductor_nombres").val(arr_response[0].split(':')[1].trim());
										}

										if (_id_modulo == 3){
											$("#encargado_nombres").val(arr_response[0].split(':')[1].trim());
										}
									}
								}
								else{
									if (_id_modulo == 1){
										$("#cliente_razonsocial").val('NO ENCONTRADO');
										$("#cliente_direccion").val('');
									}

									if (_id_modulo == 2){
										$("#conductor_nombres").val('NO ENCONTRADO');
									}

									if (_id_modulo == 3){
										$("#encargado_nombres").val('NO ENCONTRADO');
									}
								}

								if (_id_modulo == 1){
									$("#wt_razonsocial2").hide();
								}

								if (_id_modulo == 2){
									$("#wt_conductor").hide();
								}

								if (_id_modulo == 3){
									$("#wt_encargado").hide();
								}

							}, "json");
					}
			}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingIngresoUnidades(_is_show){
				if (_is_show == 1){
					$("#wt_loadingingresounidades").show();
				}
				else{
					$("#wt_loadingingresounidades").hide();
				}
			}

			function f_LoadingIngresoUnidades_Validacion(_is_show){
				if (_is_show == 1){
					$("#wt_loadingingresounidades_validadcion").show();
				}
				else{
					$("#wt_loadingingresounidades_validadcion").hide();
				}
			}

			function f_LoadingLotesPesoFinal(_is_show){
				if (_is_show == 1){
					$("#wt_loadinglotespesofinal").show();
				}
				else{
					$("#wt_loadinglotespesofinal").hide();
				}
			}

			function f_GetListaTipoDocumento(_is_juridico){
				var _html = '<option selected value="">Elija una opción...</option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				if (_is_juridico == 0){
					if ($("#cliente_tipocliente").val() == 2){
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

			$("#modal_unidadvalidacion").on("hidden.bs.modal", function () {
				f_LoadCards_UnidadesValidacion();
			});

			$("#modal_gestionlotes").on("shown.bs.modal", function () {
				var is_pesoinicial = $("#hd_ispesoinicial").val();

				if (is_pesoinicial == 1){
					$(this).find('#lote_pesoinicial').focus();
				}
				else{
					$(this).find('#lote_pesofinal').focus();
				}
			});

			$("#modal_gestionlotes").on("hidden.bs.modal", function () {
				f_getPesoOff();
			});

			function formatState(state) {
				if (!state.id) {
						return state.text; // Muestra el placeholder
				}
				var parts = state.text.split(' - ');
				var $result = $('<span>' + parts[0] + ' <span style="color: white;">- ' + (parts[1] || '') + '</span></span>');
				return $result;
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_InitValidacion(_id_registro, _placa){
				if (confirm("¿Está seguro de Iniciar la Validación de la unidad: " + _placa + '?')){
					$.post( "apis/backend.php", { accion: "inicio_BalanzaValidacionUnidades", id_registro: _id_registro }, 
						function( data ) {
							if(data.estado == 1){
								f_LoadCards_IngresoUnidades();
								f_LoadCards_UnidadesValidacion();
							}
							else{
								alert("Ocurrió un error al momento de cambiar el estado");
							}

						}, "json");
				}
			}

			function f_ValidacionCheck(_orden_objeto){
				var _valor = '';
				var _message = '';

				// Recuperando datos
					if (_orden_objeto == 1){
						_valor = $("#registro_condicion").val();

						_message = 'Debe seleccionar la Condición de Ingreso.';
					}

					if (_orden_objeto == 2){
						_valor = f_CleanInjection($("#registro_placa1").val()) + '-' + f_CleanInjection($("#registro_placa2").val());

						// Validando ingreso de Placa 1
							if ($("#registro_placa1").val() == null){
								alert("La Placa 1 ingresada no es válida.");

								return;
							}
							if ($("#registro_placa1").val().length == 0){
								alert("La Placa 1 ingresada no es válida.");

								return;
							}
							if ($("#registro_placa2").val() == null){
								alert("La Placa 1 ingresada no es válida.");

								return;
							}
							if ($("#registro_placa2").val().length == 0){
								alert("La Placa 1 ingresada no es válida.");

								return;
							}
					}

					if (_orden_objeto == 3){
						_valor = $("#registro_transportista").val();

						_message = 'Debe seleccionar el Transportista.';
					}

					if (_orden_objeto == 4){
						_valor = $("#registro_tipovehiculo").val();

						_message = 'Debe seleccionar el Tipo de Vehículo.';
					}

					if (_orden_objeto == 5){
						_valor = f_CleanInjection($("#registro_placa1_2").val()) + '-' + f_CleanInjection($("#registro_placa2_2").val());

						// Validando ingreso de Placa 1
							if ($("#registro_placa1_2").val() == null){
								alert("La Placa 2 ingresada no es válida.");

								return;
							}
							if ($("#registro_placa1_2").val().length == 0){
								alert("La Placa 2 ingresada no es válida.");

								return;
							}
							if ($("#registro_placa2_2").val() == null){
								alert("La Placa 2 ingresada no es válida.");

								return;
							}
							if ($("#registro_placa2_2").val().length == 0){
								alert("La Placa 2 ingresada no es válida.");

								return;
							}
					}

					if (_orden_objeto == 6){
						_valor = $("#registro_conductor").val();

						_message = 'Debe seleccionar el Conductor.';
					}

					if (_orden_objeto == 7){
						_valor = $("#registro_tipocarga").val();

						_message = 'Debe seleccionar el Tipo de Carga.';
					}

					if (_orden_objeto == 8){
						_valor = $("#registro_zonaorigen").val();
					}

					if (_orden_objeto == 10){
						_valor = $("#registro_proveedorminero").val();
					}

					if (_orden_objeto == 11){
						_valor = $("#registro_encargado").val();
					}

					if (_orden_objeto == 12){
						_valor = $("#registro_producto").val();
					}

					if (_orden_objeto == 13){
						_valor = $("#registro_tipomaterial").val();
					}

					if (_orden_objeto == 9){
						_valor = f_CleanInjection($("#registro_observacion").val().trim());
					}

				// Validando datos
					if (_orden_objeto != 2 && _orden_objeto != 3 && _orden_objeto != 8 && _orden_objeto != 9 && _orden_objeto != 10 && _orden_objeto != 11 && _orden_objeto != 12 && _orden_objeto != 13){
						if (_valor == null){
							alert(_message);

							return;
						}
						if (_valor.length == 0){
							alert(_message);

							return;
						}
					}

				// Validando la condición de ingreso
					if (_orden_objeto == 1){
						if (_valor != 1){
							if (!confirm("¿Está seguro que la unidad no ingresa para RECEPCIÓN DE MINERAL?\nSi continúa, la unidad será eliminada de la lista actual.\n\n¿Está seguro de continuar?")){
								return;
							}
						}
					}

				// Grabando actualización
					var _id_registro = $("#hd_idregistro").val();

					$.post( "apis/backend.php", { accion: "update_BalanzaValidacionUnidades_Validar", id_registro: _id_registro, orden_objeto: _orden_objeto, valor: _valor }, 
						function( data ) {
							if(data.estado == 1){
								// Validando la condición de ingreso
									if (_orden_objeto == 1){
										if (_valor != 1){
											f_cerrarModal("modal_unidadvalidacion");

											f_LoadCards_UnidadesValidacion();

											return;
										}
									}

								// Cambiando el ícono del grupo actual
									$("#img_divrow" + _orden_objeto).attr('src', 'images/check.png');

								// Cambiando el evento click
									// $("#btn_divrow" + _orden_objeto).attr('onclick', 'f_RevertirValidacion(' + _id_registro + ', ' + _orden_objeto + ')');

									$("#btn_divrow" + _orden_objeto).attr('onclick', '');

								// Cambiando el estado de cada TD de la tabla
									$("#td_validacion_" + _orden_objeto + "_" + _id_registro).html('<img src="<?php echo $img_check; ?>" style="width: 20px;">');

								// Inhabilita objetos
									if (_orden_objeto == 1){
										$("#registro_condicion").prop('disabled', true);
									}

									if (_orden_objeto == 2){
										$("#registro_placa1").prop('disabled', true);
										$("#registro_placa2").prop('disabled', true);
									}

									if (_orden_objeto == 3){
										$("#registro_transportista").prop('disabled', true);
										$("#btn_AddTransportista").prop('disabled', true);
									}

									if (_orden_objeto == 4){
										$("#registro_tipovehiculo").prop('disabled', true);
									}

									if (_orden_objeto == 5){
										$("#registro_placa1_2").prop('disabled', true);
										$("#registro_placa2_2").prop('disabled', true);
									}

									if (_orden_objeto == 6){
										// $("#registro_conductor").prop('disabled', true);
										// $("#btn_AddConductor").prop('disabled', true);

										// MAX (17/07/2023 13:42): Se agregó estas líneas ya que se adelantó el cierre de la Validación. Ahora solo se debe cerrar con el Conductor
										var placa = $("#registro_placa1").val().trim() + '-' + $("#registro_placa2").val().trim();

										alert("Ha finalizado con la validación de la unidad: " + placa + "\n\nYa puede iniciar con la gestión de Lotes.");

										f_LoadCards_UnidadesValidacion();

										f_cerrarModal("modal_unidadvalidacion");

										return;
									}

									if (_orden_objeto == 7){
										$("#registro_tipocarga").prop('disabled', true);
									}

									if (_orden_objeto == 8){
										$("#registro_zonaorigen").prop('disabled', true);
										$("#btn_AddZonaOrigen").prop('disabled', true);
									}

									if (_orden_objeto == 10){
										$("#registro_proveedorminero").prop('disabled', true);
									}

									if (_orden_objeto == 11){
										$("#registro_encargado").prop('disabled', true);
										$("#btn_AddEncargado").prop('disabled', true);
									}

									if (_orden_objeto == 12){
										$("#registro_producto").prop('disabled', true);
									}

									if (_orden_objeto == 13){
										$("#registro_tipomaterial").prop('disabled', true);
									}

									if (_orden_objeto == 9){
										var placa = $("#registro_placa1").val().trim() + '-' + $("#registro_placa2").val().trim();

										alert("Ha finalizado con la validación de la unidad: " + placa + "\n\nYa puede iniciar con la gestión de Lotes.");

										f_LoadCards_UnidadesValidacion();

										f_cerrarModal("modal_unidadvalidacion");

										return;
									}

								// Mostrando siguiente grupo de datos de la unidad
									if (_orden_objeto == 4){
										if (_valor == 3 || _valor == 4 || _valor == 6){
											$("#div_row" + (_orden_objeto + 1)).show(1000);
										}
										else{
											$("#div_row" + (_orden_objeto + 2)).show(1000);
										}
									}
									else{
										if (_orden_objeto == 8){
											$("#div_row" + (_orden_objeto + 2)).show(1000);
										}
										else{
											if (_orden_objeto == 10){
												$("#div_row" + (_orden_objeto + 1)).show(1000);
											}
											else{
												if (_orden_objeto == 11){
													$("#div_row" + (_orden_objeto + 1)).show(1000);
												}
												else{
													if (_orden_objeto == 12){
														$("#div_row" + (_orden_objeto + 1)).show(1000);
													}
													else{
														if (_orden_objeto == 13){
															$("#div_row" + (_orden_objeto - 4)).show(1000);
														}
														else{
															$("#div_row" + (_orden_objeto + 1)).show(1000);
														}
													}
												}
											}
										}
									}
							}
							else{
								alert("Ocurrió un error al momento de cambiar el estado");
							}

						}, "json");
			}

			function f_ConfirmarLote(){
				// Obteniendo datos
					var _id_ingreso = $("#hd_ingreso").val();
					var _id_lote = $("#hd_idlote").val();
					var _is_pesoinicial = $("#hd_ispesoinicial").val();
					var _peso_inicial = $("#lote_pesoinicial").val();
					var _peso_final = $("#lote_pesofinal").val();

					var _tipocarga = $("#lote_tipocarga").val();
					var _zonaorigen = $("#lote_zonaorigen").val();
					var _proveedorminero = $("#lote_proveedorminero").val();
					var _encargado = $("#lote_encargado").val();
					var _producto = $("#lote_producto").val();
					var _tipomaterial = $("#lote_tipomaterial").val();
					var _observacion = $("#lote_observacion").val();

				// Validando datos
					if (_is_pesoinicial == 1){
						if (_peso_inicial <= 0){
							alert("El Peso Inicial no es válido.");

							return;
						}
					}
					else{
						if (_peso_final <= 0){
							alert("El Peso Final no es válido.");

							return;
						}
					}


				// Mesaje de seguridad
					var _msg = ((_is_pesoinicial == 1) ? 'Peso Inicial' : 'Peso Final');
					var _peso = ((_is_pesoinicial == 1) ? _peso_inicial : _peso_final);

					if (!confirm("¿Está seguro de registrar el " + _msg + ": " + _peso + " Kg?")){
						return;
					}

				// Guardando datos
					$.post( "apis/backend.php", { accion: "grabar_GestionLotes", id_ingreso: _id_ingreso, id_lote: _id_lote, is_pesoinicial: _is_pesoinicial, peso_inicial: _peso_inicial, peso_final: _peso_final, tipocarga: _tipocarga, zonaorigen: _zonaorigen, proveedorminero: _proveedorminero, encargado: _encargado, producto: _producto, tipomaterial: _tipomaterial, observacion: _observacion }, 
						function( data ) {
							if(data.estado == 1){
								// Si es Peso Final debe imprimir el Ticket
								// MAX (15/04/2023 14:12): Debe imprimir tanto en el primer como en el segundo peso
									// if (_is_pesoinicial == 0){
										window.open('https://auminversiones.intelli-apps.com/print_ticketbalanza_prev.php?x=' + data.id_md5);
									// }

								f_cerrarModal('modal_gestionlotes');

								f_LoadCards_UnidadesValidacion();
								f_LoadCards_LotesPesoFinal();
							}

						}, "json");
			}

			function f_CerrarLote(_id_ingreso){
				if (!confirm("¿Está seguro de Cerrar la Unidad seleccionada?")){
					return;
				}

				// Grabar cierre
					$.post( "apis/backend.php", { accion: "cerrar_GestionLotes", id_ingreso: _id_ingreso }, 
						function( data ) {
							if(data.estado == 1){
								f_LoadCards_UnidadesValidacion();
							}

						}, "json");
			}

			function f_GrabarCliente(){
				// Recupera variables
					var id_cliente = $("#hd_idcliente").val();
					var modo_grabar = $("#hd_modograbar").val();

					var cod_condicion = 2;
					var cod_tipocliente = f_CleanInjection($("#cliente_tipocliente").val());
					var cod_tipodocumento = f_CleanInjection($("#cliente_tipodocumento").val());
					var documento = f_CleanInjection($("#cliente_documento").val().trim());
					var razon_social = f_CleanInjection($("#cliente_razonsocial").val().trim());
					var telefono1 = f_CleanInjection($("#cliente_telefono1").val().trim());
					var telefono2 = f_CleanInjection($("#cliente_telefono2").val().trim());
					var correo = f_CleanInjection($("#cliente_correo").val().trim());
					var direccion = f_CleanInjection($("#cliente_direccion").val().trim());

				// Validando datos
					if (cod_tipocliente == null){
						alert("Debe seleccionar el Tipo de Cliente.");

						return;
					}
					if (cod_tipocliente.length == 0){
						alert("Debe seleccionar el Tipo de Cliente.");

						return;
					}

					if (cod_tipodocumento == null){
						alert("Debe seleccionar el Tipo de Documento.");

						return;
					}
					if (cod_tipodocumento.length == 0){
						alert("Debe seleccionar el Tipo de Documento.");

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

					if (razon_social == null){
						alert("Debe ingresar la Razón Social.");

						return;
					}
					if (razon_social.length == 0){
						alert("Debe ingresar la Razón Social.");

						return;
					}

					if (correo.trim().length > 0){
						if (!f_CheckEMail('cliente_correo')){
							alert("El correo ingresado no tiene el formato correcto.");

							return;
						}
					}

					if (direccion == null){
							alert("Debe ingresar la Dirección.");

							return;
					}
					if (direccion.length == 0){
							alert("Debe ingresar la Dirección.");

							return;
					}

				// Grabando Datos
					$.post( "apis/backend.php", { accion: "grabar_cliente", modo_grabar: modo_grabar, id_cliente: id_cliente, cod_condicion: cod_condicion, cod_tipocliente: cod_tipocliente, cod_tipodocumento: cod_tipodocumento, documento: documento, razon_social: razon_social, telefono1: telefono1, telefono2: telefono2, correo: correo, direccion: direccion },
						function( data ) {
							if (data.estado == 2){
								alert("El documento ingresado ya fue registrado anteriormente.\n\nPor favor verificar");

								return;
							}
							else{
								if(data.estado == 1){
									f_LoadListaTransportistas(data.id_cliente);

									f_cerrarModal('modal_addcliente');
								}
								else{
									alert("Ocurrió un error al momento de grabar el Cliente.");
								}
							}

						}, "json");
			}

			function f_GrabarConductor(){
				// Recupera variables
					var dni_licencia = f_CleanInjection($("#conductor_dni").val().trim());
					var conductor_nombres = f_CleanInjection($("#conductor_nombres").val());

				// Validando datos
					if (dni_licencia == null){
						alert("Debe ingresar el DNI o N° de Licencia.");

						return;
					}
					if (dni_licencia.length == 0){
						alert("Debe ingresar el DNI o N° de Licencia.");

						return;
					}

					if (conductor_nombres == null){
						alert("Debe ingresar los Nombres y Apellidos del Conductor.");

						return;
					}
					if (conductor_nombres.length == 0){
						alert("Debe ingresar los Nombres y Apellidos del Conductor.");

						return;
					}

				// Grabando Datos
					$.post( "apis/backend.php", { accion: "grabar_conductor", modo_grabar: 'N', id_conductor: 0, dni_licencia: dni_licencia, conductor_nombres: conductor_nombres },
						function( data ) {
							if (data.estado == 2){
								alert("El DNI o N° de Licencia ya fue registrado anteriormente.\n\nPor favor verificar");

								return;
							}
							else{
								if(data.estado == 1){
									f_LoadListaConductores(data.id_conductor);

									f_cerrarModal('modal_addconductor');
								}
								else{
									alert("Ocurrió un error al momento de grabar el Conductor.");
								}
							}

						}, "json");
			}

			function f_GrabarZonaOrigen(){
				// Recupera variables
					var zona_origen = f_CleanInjection($("#zona_origen").val().trim());

				// Validando datos
					if (zona_origen == null){
						alert("Debe ingresar la Zona de Origen.");

						return;
					}
					if (zona_origen.length == 0){
						alert("Debe ingresar la Zona de Origen.");

						return;
					}

				// Grabando Datos
					$.post( "apis/backend.php", { accion: "grabar_zonaorigen", modo_grabar: 'N', id_zonaorigen: 0, zona_origen: zona_origen },
						function( data ) {
							if (data.estado == 2){
								alert("La Zona de Origen ingresada ya fue registrada anteriormente.\n\nPor favor verificar.");

								return;
							}
							else{
								if(data.estado == 1){
									f_LoadListaZonaOrigen(data.id_zonaorigen);

									f_cerrarModal('modal_addzonaorigen');
								}
								else{
									alert("Ocurrió un error al momento de grabar la Zona de Origen.");
								}
							}

						}, "json");
			}

			function f_GrabarEncargadoMuestra(){
				// Recupera variables
					var encargado_dni = f_CleanInjection($("#encargado_dni").val().trim());
					var encargado_nombres = f_CleanInjection($("#encargado_nombres").val());

				// Validando datos
					if (encargado_dni == null){
						alert("Debe ingresar el DNI.");

						return;
					}
					if (encargado_dni.length == 0){
						alert("Debe ingresar el DNI.");

						return;
					}

					if (encargado_nombres == null){
						alert("Debe ingresar los Nombres y Apellidos del Encargado de Muestra.");

						return;
					}
					if (encargado_nombres.length == 0){
						alert("Debe ingresar los Nombres y Apellidos del Encargado de Muestra.");

						return;
					}

				// Grabando Datos
					$.post( "apis/backend.php", { accion: "grabar_encargadomuestra", modo_grabar: 'N', id_encargado: 0, encargado_dni: encargado_dni, encargado_nombres: encargado_nombres },
						function( data ) {
							if (data.estado == 2){
								alert("El DNI ya fue registrado anteriormente.\n\nPor favor verificar");

								return;
							}
							else{
								if(data.estado == 1){
									f_LoadListaEncargadosMuestra(data.id_encargado);

									f_cerrarModal('modal_addencargadomuestra');
								}
								else{
									alert("Ocurrió un error al momento de grabar el Conductor.");
								}
							}

						}, "json");
			}

			function f_DeleteLote(_id_lote){
				if (!confirm("¿Está seguro de eliminar el Lote selecionado?")){
					return;
				}

				// Eliminar registro
					$.post( "apis/backend.php", { accion: "eliminar_Lote", id_lote: _id_lote },
						function( data ) {
							if(data.estado == 1){
								f_LoadCards_UnidadesValidacion();
							}
							else{
								alert("Ocurrió un error al momento de grabar la Zona de Origen.");
							}

						}, "json");
			}

			function f_AddLoteAUM(){
				if (!confirm("¿Está seguro de crear un Lote AUM?")){
					return;
				}

				// Creando lote
					$.post( "apis/backend.php", { accion: "crear_LoteAUM" }, 
						function( data ) {
							if(data.estado == 1){
								f_LoadCards_UnidadesValidacion();
								f_LoadCards_LotesPesoFinal();
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