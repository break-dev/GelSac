<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

  $is_vistatouch = $_SESSION["vista_touch"];

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

		<title><?php echo $nom_app; ?> | Reporte Contable</title>

		<script type="text/javascript">

		</script>
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
										<div class="d-flex" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px; width: 120px;">Por Fechas: </h6>

											<select id="filtro_fechas" class="form-select" style="font-size: 12px; margin-top: -8px; height: 32px;">
												<option value="1">Comprobante Emisión</option>
												<option value="2">Comprobante Vencimiento</option>
											</select>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadClientes();">

											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadClientes();">
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Cliente</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_clientes" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
												
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Sucursal</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_sucursal" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_sucursales = "SELECT Id,
                                								des_sucursal
								                           FROM tb_sucursal
								                          WHERE estado_sucursal = 'A'";

								        if ($res_sucursales = mysqli_query($enlace, $q_sucursales)){
								          if (mysqli_num_rows($res_sucursales) > 0) {
								            while($row_sucursales = mysqli_fetch_array($res_sucursales)){
								              ?>

								              <option value="<?php echo $row_sucursales["Id"]; ?>"><?php echo $row_sucursales["des_sucursal"]; ?></option>

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
											<h6 style="font-size: 14px;">Por Medios de Pago</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_mediospago" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_mediospago = "SELECT Id,
                                								descripcion
								                           FROM tbconfig_mediospago
								                          /*WHERE estado = 'A'*/";

								        if ($res_mediospago = mysqli_query($enlace, $q_mediospago)){
								          if (mysqli_num_rows($res_mediospago) > 0) {
								            while($row_mediospago = mysqli_fetch_array($res_mediospago)){
								              ?>

								              <option value="<?php echo $row_mediospago["Id"]; ?>"><?php echo $row_mediospago["descripcion"]; ?></option>

								              <?php
								            }
								          }
								        }

												?>

											</select>
										</div>
									</div>
								</div>

								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Documentos</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_listatipodocumento" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>
												<option value="1">N° Recibo</option>
												<option value="2">N° Comprobante</option>
											</select>

											<input id="filtro_documento" type="text" class="form-control" style="font-size: 14px;">
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="padding-left: 30px; margin-top: 5px; margin-bottom: 10px; font-size: 13px;">
								<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px;">
		              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
		            </button>
							</div>
						</div>

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-3 col-sm-3 col-xs-3">
									<div class="d-flex">
										<h5>Reporte Contable</h5>

										<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
											<img src="<?php echo $img_waiting ?>" style="width: 20px;">
											<label style="font-style: italic;"> Cargando datos...</label>
										</div>
									</div>
								</div>

								<div class="col-md-7 col-sm-7 col-xs-7" style="margin-top: -10px;">
									<div class="d-flex flex-row-reverse" style="padding: 10px;">
										<div class="form-check" style="margin-left: 10px;">
										  <input class="form-check-input" type="radio" name="rd_estado" id="rd_pendientes" onchange="f_LoadResultados();">
										  <label class="form-check-label" for="rd_pendientes">
										    Pendientes
										  </label>
										</div>

										<div class="form-check" style="margin-left: 10px;">
										  <input class="form-check-input" type="radio" name="rd_estado" id="rd_pagados" onchange="f_LoadResultados();">
										  <label class="form-check-label" for="rd_pagados">
										    Pagados
										  </label>
										</div>

										<div class="form-check" style="margin-left: 40px;">
										  <input class="form-check-input" type="radio" name="rd_estado" id="rd_todos" onchange="f_LoadResultados();" checked>
										  <label class="form-check-label" for="rd_todos">
										    Todos
										  </label>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px;">
			              <b>Exportar a Excel</b>
			            </button>
								</div>
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
								<!-- <div class="d-flex" style="margin-top: -10px; font-size: 14px;">
									<label>
										Total de Muestras: <spam id="total_muestras" style="color: #0897B4; font-weight: bold; margin-left: 5px;"></spam>
									</label>

									<label style="margin-left: 10px;">
										| &nbsp; Muestras Sin Recepción: <spam id="total_sinrecepcion" style="color: #dc3545; font-weight: bold; margin-left: 5px;"></spam>
									</label>

									<label style="margin-left: 10px;">
										| &nbsp; Muestras Con Recepción: <spam id="total_conrecepcion" style="color: #32B86C; font-weight: bold; margin-left: 5px;"></spam>
									</label>

									<label style="margin-left: 10px;">
										| &nbsp; Total Efectivo (S/): <spam id="total_efectivo" style="color: #404040; font-weight: bold; margin-left: 5px;"></spam>
									</label>

									<label style="margin-left: 10px;">
										| &nbsp; Parque Industrial (S/): <spam id="total_efectivo_1" style="color: #404040; font-weight: bold; margin-left: 5px;"></spam>
									</label>

									<label style="margin-left: 10px;">
										| &nbsp; Av. América (S/): <spam id="total_efectivo_2" style="color: #404040; font-weight: bold; margin-left: 5px;"></spam>
									</label>
								</div> -->

								<table id="tbl_detalle" class="table table-bordered table-hover">
				        	
				        </table>
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
		<div class="modal fade" id="modal_confirmarpago" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_confirmarpagoLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_confirmarpagoLabel">Confirmar Pago</h1>
		        <br>
		        <h1 id="titulo_confirmarpago" class="modal-title" style="font-size: 14px;"></h1>
		      </div>
		      <div class="modal-body">
						<div class="row" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px; background-color: #FFF587;">
							<div class="col-md-4 col-sm-4 col-xs-6" style="padding: 5px; text-align: right;">
								Medio de Pago:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-6">
								<select id="ins_mediospago" class="form-select" style="text-align: left;" onchange="f_ShowEfectivo();">

								</select>
							</div>
						</div>

						<div class="row" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px;">
							<div class="d-flex justify-content-center">
								<div style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px; width: 240px; font-weight: bold;">
									Total Venta <label id="ins_moneda_1"></label>
									<hr style="margin-top: 5px; margin-bottom: 10px;">

									<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #5b2a68;">
										<label id="ins_totalventa" style="color: #ffffff; font-size: 22px;">

										</label>
									</div>
								</div>

								<div class="ins_divefectivo" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px; width: 240px; font-weight: bold; display: none;">
									Efectivo Ingresado <label id="ins_moneda_2"></label>
									<hr style="margin-top: 5px; margin-bottom: 10px;">

									<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ba9842;">
										<label id="ins_efectivo" style="color: #ffffff; font-size: 22px;">

										</label>
									</div>

								</div>

								<div class="ins_divefectivo" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px; width: 240px; font-weight: bold; display: none;">
									Cambio <label id="ins_moneda_3"></label>
									<hr style="margin-top: 5px; margin-bottom: 10px;">

									<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #dc3545;">
										<label id="ins_cambio" style="color: #ffffff; font-size: 22px;">

										</label>
									</div>

								</div>
							</div>
						</div>

						<div class="ins_divefectivo" style="display: none;">
              <div class="row" style="padding-left: 30px; padding-right: 30px;">
                <div class="col-md-6 col-sm-6 col-xs-12" style="text-align: center;">
                	<div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(200)">
	                    <img src="images/dinero/billete200.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete200" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(100)">
	                    <img src="images/dinero/billete100.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete100" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(50)">
	                    <img src="images/dinero/billete50.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete50" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(20)">
	                    <img src="images/dinero/billete20.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px;">
	                    <input type="number" id="total_billete20" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(10)">
	                    <img src="images/dinero/billete10.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete10" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes(5)">
	                    <img src="images/dinero/billete5.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete5" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes(2)">
	                    <img src="images/dinero/billete2.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete2" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes(1)">
	                    <img src="images/dinero/billete1.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete1" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes('50cen')">
	                    <img src="images/dinero/billete50cen.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete50cen" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes('20cen')">
	                    <img src="images/dinero/billete20cen.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete20cen" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes('10cen')">
	                    <img src="images/dinero/billete10cen.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete10cen" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>
                </div>
              </div>
            </div>
		      </div>

		      <input id="hd_item" type="hidden">
		      <input id="hd_comprobante" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_confirmarpago" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="images/waiting.gif" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button id="btn_inscerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button id="btn_insgrabar" type="button" class="btn btn-primary" onclick="f_GrabarConfirmarPago();">Confirmar Pago</button>
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
					$("#nv_titulo").html('| Reporte Contable');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadResultados();

				// Cargando Medio de Pago para la Confirmación del Pago
					f_GetListaMediosDePago();

				// Carga la lista de Clientes
					f_LoadClientes();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
        // Obteniendo filtros
        	var filtro_fechas = $("#filtro_fechas").val();
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_cliente = $("#filtro_clientes").val();
        	var filtro_sucursal = $("#filtro_sucursal").val();
        	var filtro_mediospago = $("#filtro_mediospago").val();
        	var filtro_listatipodocumento = $("#filtro_listatipodocumento").val();
        	var filtro_documento = $("#filtro_documento").val();
        	var filtro_opt1 = (($("#rd_pendientes").prop('checked')) ? 1 : 0);
        	var filtro_opt2 = (($("#rd_pagados").prop('checked')) ? 1 : 0);
        	var filtro_opt3 = (($("#rd_todos").prop('checked')) ? 1 : 0);

        // Validando datos
      		// if (fecha_inicio == null){
          //   alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }
	        // if (fecha_inicio.length == 0){
          //   alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }

	        // if (fecha_fin == null){
          //   alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }
	        // if (fecha_fin.length == 0){
          //   alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }

	        // if (fecha_fin < fecha_inicio){
          //   alert('La fecha "Desde" no puede ser mayor a la fecha "Hasta".\nPor favor, verificar.');

          //   return;
        	// }

        $("#tbl_detalle").html('');

        f_LoadingDetalle(1);

        $.post( "apis/backend.php", { accion: "get_GestionContable_Reporte", filtro_fechas: filtro_fechas, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_cliente: filtro_cliente, filtro_sucursal: filtro_sucursal, filtro_mediospago: filtro_mediospago, filtro_listatipodocumento: filtro_listatipodocumento, filtro_documento: filtro_documento, filtro_opt1: filtro_opt1, filtro_opt2: filtro_opt2, filtro_opt3: filtro_opt3 }, 
          function( data ) {
            if(data.estado == 1){
            	$("#tbl_detalle").html(data.html);

	            // f_TotalMuestras();
            }
            else{
              // alert("No se encontraron resultados.");
            }

            f_LoadingDetalle(0);

          }, "json");
    	};

    	function f_LoadClientes(){
    		// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();

        // Cargando clientes
        	$("#filtro_clientes").val('');

        	$.post( "apis/backend.php", { accion: "get_ClientesxFechas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
          function( data ) {
            if(data.estado == 1){
            	$("#filtro_clientes").html(data.html);
            }
            else{
              // alert("No se encontraron resultados.");
            }

          }, "json");
    	}

    	// function f_TotalMuestras(){
    	// 	// Obteniendo filtros
      //   	var fecha_inicio = $("#fecha_inicio").val();
      //   	var fecha_fin = $("#fecha_fin").val();
      //   	var filtro_estadomuestra = '';
      //   	var filtro_envasemuestra = '';
      //   	var filtro_tiposmuestra = '';
      //   	var filtro_ensayosmuestra = '';
      //   	var filtro_CI = '';
      //   	var filtro_opt1 = '';
      //   	var filtro_opt2 = '';
      //   	var filtro_opt3 = '';

      //   // Inicializando los totales
      //   	$("#total_muestras").html('0');
			// 		$("#total_sinrecepcion").html('0');
			// 		$("#total_conrecepcion").html('0');

			// 	// Obteniendo totales
	    // 		$.post( "apis/backend.php", { accion: "get_LQRecepcionMuestras_TotalMuestras", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_estadomuestra: filtro_estadomuestra, filtro_envasemuestra: filtro_envasemuestra, filtro_tiposmuestra: filtro_tiposmuestra, filtro_ensayosmuestra: filtro_ensayosmuestra, filtro_CI: filtro_CI, filtro_opt1: filtro_opt1, filtro_opt2: filtro_opt2, filtro_opt3: filtro_opt3 }, 
	    //       function( data ) {
	    //         if(data.estado == 1){
	    //         	$("#total_muestras").html(parseFloat(data.total_sinrecepcion) + parseFloat(data.total_conrecepcion));
			// 					$("#total_sinrecepcion").html(data.total_sinrecepcion);
			// 					$("#total_conrecepcion").html(data.total_conrecepcion);
	    //         }
	    //         else{
	    //           alert("No se encontraron resultados.");
	    //         }

	    //       }, "json");
    	// }

    	function f_ExportToExcel(){
        // Obteniendo filtros
        	var filtro_fechas = $("#filtro_fechas").val();
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_cliente = $("#filtro_clientes").val();
        	var filtro_sucursal = $("#filtro_sucursal").val();
        	var filtro_mediospago = $("#filtro_mediospago").val();
        	var filtro_listatipodocumento = $("#filtro_listatipodocumento").val();
        	var filtro_documento = $("#filtro_documento").val();
        	var filtro_opt1 = (($("#rd_pendientes").prop('checked')) ? 1 : 0);
        	var filtro_opt2 = (($("#rd_pagados").prop('checked')) ? 1 : 0);
        	var filtro_opt3 = (($("#rd_todos").prop('checked')) ? 1 : 0);

        window.location.href = "export_to_excel/gestioncontable_reporte.php?filtro_fechas="+filtro_fechas+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_cliente="+filtro_cliente+"&filtro_sucursal="+filtro_sucursal+"&filtro_mediospago="+filtro_mediospago+"&filtro_listatipodocumento="+filtro_listatipodocumento+"&filtro_documento="+filtro_documento+"&filtro_opt1="+filtro_opt1+"&filtro_opt2="+filtro_opt2+"&filtro_opt3="+filtro_opt3;
    	};

    	function f_ImprimirDocumentoCliente(_tipo, _num_comprobante){
    		var tipo = '';

    		if (_tipo == 'FA'){
    			tipo = '01';
    		}

    		if (_tipo == 'BO'){
    			tipo = '03';
    		}

    		if (_tipo == 'NC'){
    			tipo = '';
    		}

    		var url = 'https://apicooper.wsystem.world/invoices/pdf/20602385371-' + tipo + '-' + _num_comprobante + '.pdf';

				window.open(url,'_blank',"");
			}

			function f_ConfirmarPago(_item, _num_comprobante, _cliente_documento, _cliente_razonsocial, _total_comprobante){
				// Setea los objetos hidden
					$("#hd_item").val(_item);
					$("#hd_comprobante").val(_num_comprobante);

				// Setea título de ventana
					$("#titulo_confirmarpago").html(_cliente_documento + ' - ' + _cliente_razonsocial);

				// Setea los datos
					$("#ins_totalventa").html(parseFloat(_total_comprobante).toFixed(2));
					$("#ins_mediospago").val('');
					$("#ins_efectivo").html('');
					$("#ins_cambio").html('');

					$("#total_billete200").val(0);
					$("#total_billete100").val(0);
					$("#total_billete50").val(0);
					$("#total_billete20").val(0);
					$("#total_billete10").val(0);
					$("#total_billete5").val(0);
					$("#total_billete2").val(0);
					$("#total_billete1").val(0);
					$("#total_billete50cen").val(0);
					$("#total_billete20cen").val(0);
					$("#total_billete10cen").val(0);

					f_ShowEfectivo();

				f_OpenModal('modal_confirmarpago');
    	}

			function f_ImprimirRecibo(_id_md5){
				var url = 'print_recibo_ensayos.php?x=' + _id_md5 + '&p=0';

				window.open(url,'_blank',"");
			}

			function f_ImprimirTickets(_id_md5){
				var url = 'print_etiquetas_ensayos_ok.php?x=' + _id_md5 + '&d=0';

				window.open(url,'_blank',"");
			}

			function f_LoadingDetalle(_is_show){
				if (_is_show == 1){
					$("#wt_detalle").show();
				}
				else{
					$("#wt_detalle").hide();
				}
			}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
	    // Lista de Medios de Pago
	    	function f_GetListaMediosDePago(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listamediospago" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '|' + val.is_efectivo + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#ins_mediospago").html(_html);
							}
							else{
								$("#ins_mediospago").html('');
							}

						}, "json");
				}

			// Loading de Confirmación de Instrucción
				function f_LoadingConfirmarPago(_is_show){
					if (_is_show == 1){
						$("#wt_confirmarpago").show();

						$("#chk_Comprobante").prop('disabled', true);
						$("#ins_mediospago").prop('disabled', true);
						$("#btn_insgrabar").prop('disabled', true);
						$("#btn_insgrabar").css('background-color', '#C2C0A6');

						$("#btn_inscerrar").prop('disabled', true);
						$("#btn_inscerrar").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_confirmarpago").hide();

						$("#chk_Comprobante").prop('disabled', false);
						$("#ins_mediospago").prop('disabled', false);
						$("#btn_insgrabar").prop('disabled', false);
						$("#btn_insgrabar").css('background-color', '');

						$("#btn_inscerrar").prop('disabled', false);
						$("#btn_inscerrar").css('background-color', '');
					}
				}

			// Si se selecciona el Tipo de Pago efectivo se mostrarán los billetes
				function f_ShowEfectivo(){
					var medio_pago = $("#ins_mediospago").val().substring(0, $("#ins_mediospago").val().indexOf('|'));
          var is_efectivo = $("#ins_mediospago").val().substring($("#ins_mediospago").val().indexOf('|') + 1);

          if (is_efectivo == 1){
            $(".ins_divefectivo").show();
	        }
	        else{
            $(".ins_divefectivo").hide();
        	}
				}

			// Suma la selección de billetes y monedas
				function f_SumaBilletes(_billete){
	        $("#total_billete" + _billete).val(parseFloat($("#total_billete" + _billete).val()) + 1);

	        f_CalcularEfectivo();
	      };

	    // Calcular el total de efectivo seleccionado
      	function f_CalcularEfectivo(){
          var total_billete200 = parseFloat($("#total_billete200").val()) * 200;
          var total_billete100 = parseFloat($("#total_billete100").val()) * 100;
          var total_billete50 = parseFloat($("#total_billete50").val()) * 50;
          var total_billete20 = parseFloat($("#total_billete20").val()) * 20;
          var total_billete10 = parseFloat($("#total_billete10").val()) * 10;

          var total_billete5 = parseFloat($("#total_billete5").val()) * 5;
          var total_billete2 = parseFloat($("#total_billete2").val()) * 2;
          var total_billete1 = parseFloat($("#total_billete1").val()) * 1;
          var total_billete50cen = parseFloat($("#total_billete50cen").val()) * 0.5;
          var total_billete20cen = parseFloat($("#total_billete20cen").val()) * 0.2;
          var total_billete10cen = parseFloat($("#total_billete10cen").val()) * 0.1;

          var total_efectivo = parseFloat(total_billete200 +
	                                        total_billete100 +
	                                        total_billete50 +
	                                        total_billete20 +
	                                        total_billete10 +
	                                        total_billete5 +
	                                        total_billete2 +
	                                        total_billete1 +
	                                        total_billete50cen +
	                                        total_billete20cen +
	                                        total_billete10cen).toFixed(2);

          $("#ins_efectivo").html(total_efectivo);
          $("#ins_cambio").html(parseFloat(total_efectivo - parseFloat($("#ins_totalventa").html())).toFixed(2));
      	};
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Confirmar Instrucción
				function f_GrabarConfirmarPago(){
					var item = 0;
					var num_comprobante = '';
					var medio_pago = '';
					var total_venta = '';

					// Obteniendo datos
						item = $("#hd_item").val();
						num_comprobante = $("#hd_comprobante").val();
            medio_pago = $("#ins_mediospago").val().substring(0, $("#ins_mediospago").val().indexOf('|'));
            total_venta = parseFloat($("#ins_totalventa").html()).toFixed(2);

          // Validando datos
          	if (medio_pago == null){
              alert("Debe seleccionar el Medio de Pago.");

              return;
            }
            if (medio_pago.length == 0){
              alert("Debe seleccionar el Medio de Pago.");

              return;
            }

            // Validaciones para cuando sea "Efectivo"
	            is_efectivo = $("#ins_mediospago").val().substring($("#ins_mediospago").val().indexOf('|') + 1);

	            if (is_efectivo == 1 && medio_pago != 5){
	              total_efectivo = parseFloat(document.getElementById("ins_totalventa").innerHTML).toFixed(2);
	              cambio = parseFloat(document.getElementById("ins_cambio").innerHTML).toFixed(2);

	              if (medio_pago == 3){
	                if (parseFloat(cambio) < 0 || isNaN(cambio)){
	                  alert('El efectivo ingresado aún no cubre el total de la venta.'+"\n\n"+'Por favor, verificar.');

	                  return;
	                }
	              }
	              else{
	                if (total_efectivo == 0){
	                  alert("No ha ingresado efectivo. \n\nPor favor, verificar.");

	                  return;
	                }
	              }
	            }

          // Parámetros de pago
            efectivo_ingresado = parseFloat(document.getElementById("ins_efectivo").innerHTML).toFixed(2);

            total_billete200 = parseFloat($("#total_billete200").val());
            total_billete100 = parseFloat($("#total_billete100").val());
            total_billete50 = parseFloat($("#total_billete50").val());
            total_billete20 = parseFloat($("#total_billete20").val());
            total_billete10 = parseFloat($("#total_billete10").val());

            total_billete5 = parseFloat($("#total_billete5").val());
            total_billete2 = parseFloat($("#total_billete2").val());
            total_billete1 = parseFloat($("#total_billete1").val());
            total_billete50cen = parseFloat($("#total_billete50cen").val());
            total_billete20cen = parseFloat($("#total_billete20cen").val());
            total_billete10cen = parseFloat($("#total_billete10cen").val());

          // Confirmar Instrucción
            if (!confirm("¿Está seguro de Confirmar el Pago?")){
            	return;
            }

            f_LoadingConfirmarPago(1);

            $.post( "apis/backend.php", { accion: "confirmarPago_GestionContable", num_comprobante: num_comprobante, medio_pago: medio_pago, total_venta: total_venta, efectivo_ingresado: efectivo_ingresado, total_billete200: total_billete200, total_billete100: total_billete100, total_billete50: total_billete50, total_billete20: total_billete20, total_billete10: total_billete10, total_billete5: total_billete5, total_billete2: total_billete2, total_billete1: total_billete1, total_billete50cen: total_billete50cen, total_billete20cen: total_billete20cen, total_billete10cen: total_billete10cen }, 
							function( data ) {
								if(data.estado == 1){
									// Actualizando camposs
										$("#td_accion_" + item).html('');
										$("#td_creditopagadoespecial_1_" + item).html($("#ins_mediospago option:selected").text());

										if ($("#ins_mediospago").val().split('|')[0] == 5){
											$("#td_creditopagadoespecial_2_" + item).html($("#ins_efectivo").html().trim());
											$("#td_creditopagadoespecial_3_" + item).html(parseFloat(parseFloat($("#ins_totalventa").html().trim()) - parseFloat($("#ins_efectivo").html().trim())).toFixed(2));
										}

										$("#td_creditopagadoespecial_4_" + item).html(data.pagado_fechahoraregistro);
										$("#td_creditopagadoespecial_5_" + item).html(data.pagado_usuarioregistro);

										f_LoadingConfirmarPago(0);

										f_cerrarModal('modal_confirmarpago');
								}
								else{
									alert("Ocurrió un error al momento de Confirmar la Instrucción.");
								}

							}, "json");
				}
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 400){
					
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