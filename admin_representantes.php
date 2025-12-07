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

		<title><?php echo $nom_app; ?> | Representantes de Empresas</title>

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
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por DNI / Nombres</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_listatipo" class="form-select" style="text-align: left; font-size: 14px; width: 70%;" onchange="f_CleanTxtTipo();">
												<option selected value="">Elija una opción...</option>
												<option value="1">DNI</option>
												<option value="2">Nombres</option>
											</select>

											<input id="filtro_tipo" type="text" class="form-control" style="font-size: 14px;" onblur="f_LoadResultados();">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="padding: 0px;">
							<div class="col-md-8 col-sm-8 col-xs-8" style="padding: 3px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
									<div class="row" style="padding: 20px;">
										<div class="col-md-9 col-sm-9 col-xs-9">
											<h5>Representantes</h5>
										</div>

										<div class="col-md-3 col-sm-3 col-xs-3" style="margin-top: -5px;">
											<button class="btn btn-primary" type="button" onclick="f_AdminClientes('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
					              <b> + Nuevo</b>
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
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Tipo Cliente
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Tipo Documento
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Documento
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
						        				Razón Social
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Teléfonos
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Correo
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Dirección
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Estado
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Acción
						        			</th>

						        			<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Crédito
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 35px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
						        				Vigencia
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Observación
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 65px;">
						        				Estado
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora Registro
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Usuario Registro
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
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

							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 3px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
									<div class="row" style="padding: 20px;">
										<div class="col-md-7 col-sm-7 col-xs-7">
											<h5>Empresas del Representante</h5>
										</div>

										<div class="col-md-5 col-sm-5 col-xs-5" style="margin-top: -5px;">
											<button class="btn btn-primary" type="button" onclick="f_AdminClientes('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
					              <b> + Asignar</b>
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
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Tipo Cliente
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Tipo Documento
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Documento
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
						        				Razón Social
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Teléfonos
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Correo
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Dirección
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Estado
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Acción
						        			</th>

						        			<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Crédito
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 35px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px;">
						        				Vigencia
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Observación
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 65px;">
						        				Estado
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora Registro
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Usuario Registro
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
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
		<div class="modal fade" id="modal_addcliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addclienteLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
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
								<input id="cliente_documento" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente()";>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Razón Social: <img id="wt_razonsocial2" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="cliente_razonsocial" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
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
								<textarea id="cliente_direccion" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
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

		<div class="modal fade" id="modal_addcredito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addcreditoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addcreditoLabel">Nuevo Cliente</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Vigencia:
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="cred_fechainicio" type="date" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; width: 95%; font-size: 14px;" value="<?php echo $g_date; ?>">
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="cred_fechafin" type="date" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; width: 95%; font-size: 14px;" value="<?php echo $g_date; ?>">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Observación:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="cred_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
							</div>
						</div>
		      </div>

		      <input id="hd_idclientecredito" type="hidden">
		      <input id="hd_idcredito" type="hidden">
		      <input id="hd_modograbarcredito" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarCredito();">Grabar</button>
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
					$("#nv_titulo").html('| Representantes de Empresas');

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

        var cod_tipocliente = $("#filtro_tipocliente").val();
        var cod_tipodocumento = $("#filtro_tipodocumento").val();
        var cod_tipo = $("#filtro_listatipo").val();
        var txt_tipo = $("#filtro_tipo").val().trim();

        if (txt_tipo.length > 0){
      		if (cod_tipo == null){
	          alert("Debe indicar si la búsqueda es por Documento o Razón Social.");

	          return;
	        }
	        if (cod_tipo.length == 0){
	          alert("Debe indicar si la búsqueda es por Documento o Razón Social.");

	          return;
	        }
        }

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        var arr_creditos = '';
        var c = 0;

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_listaclientes", cod_tipocliente: cod_tipocliente, cod_tipodocumento: cod_tipodocumento, cod_tipo: cod_tipo, txt_tipo: txt_tipo }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.TIPO_CLIENTE;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.TIPO_DOCUMENTO;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.documento;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.razon_social;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.TELEFONOS;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.correo;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.direccion;
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

                  _html += '      <a class="success" href="javascript: f_AdminClientes(' + d + ', ' + val.Id + ', ' + val.cod_tipocliente	+ ', ' + val.cod_tipodocumento + ", '" + val.documento + "', '" + val.razon_social + "', '" + val.telefono1 + "', '" + val.telefono2 + "', '" + val.correo + "', '" + val.direccion + "'" + ')"><i class="bi bi-pencil-square"></i>';
                  _html += '          <font style="color: #337ab7;"> Editar</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_CambiarEstado(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                  _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_EliminarCliente(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                  _html += '          <font style="color: #F20505;"> Eliminar</font>';
                  _html += '      </a>';

                  _html += '  </td>';

                // Agregando información de Créditos
                  _html += '  <td colspan="7" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; padding: 0px;">';
                  _html += '    <table style="width: 100%;">';

									if (val.INF_CREDITO.trim().length > 0){
                  	arr_creditos = val.INF_CREDITO.split('|');
                  
                  	c = 0;

		                while (c < arr_creditos.length){
		                	_html += '    	<tr>';
		                	_html += '  			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; width: 32px; text-align: center;">';
		                	_html += '  				' + (c + 1);
		                	_html += '  			</td>';
		                	_html += '  			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; width: 170px; text-align: center;">';
		                	_html += '  				' + arr_creditos[c].split('¿')[0] + ' - ' + arr_creditos[c].split('¿')[1];
		                	_html += '  			</td>';
		                	_html += '  			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; width: 200px;">';
		                	_html += '  				' + arr_creditos[c].split('¿')[2];
		                	_html += '  			</td>';

		                	// Setea el Estado del registro
			                  if (arr_creditos[c].split('¿')[3] == 'I'){
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

			                  _html += '  			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color:' + ((arr_creditos[c].split('¿')[3] == 'I') ? '#E6A50D' : '#44803F') + '; color: #ffffff; width: 65px;">';
			                  _html += '      		' + ((arr_creditos[c].split('¿')[3] == 'A') ? 'Activo' : 'Inactivo');
			                  _html += '  			</td>';
			                	_html += '  			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; width: 100px; text-align: center;">';
			                	_html += '  				' + arr_creditos[c].split('¿')[4];
			                	_html += '  			</td>';
			                	_html += '  			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; width: 100px; text-align: center;">';
			                	_html += '  				' + arr_creditos[c].split('¿')[5];
			                	_html += '  			</td>';
			                	_html += '  			<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; width: 100px;">';
			                	_html += '      		<a class="success" href="javascript: f_AdminCredito(' + val.Id + ', ' + arr_creditos[c].split('¿')[6]	+ ", '" + arr_creditos[c].split('¿')[0] + "', '" + arr_creditos[c].split('¿')[1] + "', '" + arr_creditos[c].split('¿')[2] + "'" + ')"><i class="bi bi-pencil-square"></i>';
			                  _html += '          		<font style="color: #337ab7;"> Editar</font>';
			                  _html += '      		</a>';

			                  _html += '<br>';

			                  _html += '      		<a class="success" href="javascript: f_CambiarEstadoCredito(' + "'" + href_estado.substring(0, 1) + "', " + arr_creditos[c].split('¿')[6] + ')"><i class="' + href_icon + '"></i>';
			                  _html += '          		<font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
			                  _html += '      		</a>';

			                  _html += '<br>';

			                  _html += '      		<a class="success" href="javascript: f_EliminarCredito(' + arr_creditos[c].split('¿')[6] + ')"><i class="bi bi-file-x"></i>';
			                  _html += '          		<font style="color: #F20505;"> Eliminar</font>';
			                  _html += '      		</a>';
			                	_html += '  			</td>';

		                	_html += '    	</tr>';

		                	c ++;
		                }
                  }

                  _html += '    	<tr>';
	                _html += '    		<td colspan="7">';
	                _html += '    			<button class="btn btn-primary" type="button" onclick="f_AdminCredito(' + val.Id + ", 'x'" + ');" style="color: #ffffff; width: 100%; font-size: 14px; margin-top: 1px;">';
	                _html += '    				<b> + Nuevo Crédito</b>';
	                _html += '    			</button>';
	                _html += '    		</td>';
	                _html += '    	</tr>';
                  _html += '    </table>';
                  _html += '  </td>';

                _html += '</tr>';

                d += 1;
              });
            }
            else{
              alert("No se encontraron resultados.");
            }

            $("#tbl_detalle").html(_html);

          }, "json");
    	};

    	function f_AdminClientes(_item, _id_cliente, _cod_tipocliente, _cod_tipodocumento, _documento, _razon_social, _telefono1, _telefono2, _correo, _direccion){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Cliente:<br>"<b>'+_documento + ' - ' + _razon_social + '</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Nuevo Cliente";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_addclienteLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addcliente');

	        if (tipo != 'N'){
            $("#hd_idcliente").val(_id_cliente);
            $("#cliente_tipocliente").val(_cod_tipocliente);
		        $("#cliente_tipodocumento").val(_cod_tipodocumento);
            $("#cliente_documento").val(f_CleanInjection(_documento));
            $("#cliente_razonsocial").val(f_CleanInjection(_razon_social));
            $("#cliente_telefono1").val(_telefono1);
		        $("#cliente_telefono2").val(_telefono2);
		        $("#cliente_correo").val(f_CleanInjection(_correo));
		        $("#cliente_direccion").val(f_CleanInjection(_direccion));
			    }
			    else{
			    	$("#hd_idcliente").val(0);
		        $("#cliente_tipocliente").val('');
		        $("#cliente_tipodocumento").val('');
		        $("#cliente_documento").val('');
		        $("#cliente_razonsocial").val('');
		        $("#cliente_telefono1").val('');
		        $("#cliente_telefono2").val('');
		        $("#cliente_correo").val('');
		        $("#cliente_direccion").val('');
		   		}
    	}

    	function f_AdminCredito(_id_cliente, _id_credito, _fecha_inicio, _fecha_fin, _observacion){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_id_credito != 'x'){
            tipo = "E";
            titulo = "Editar Crédito";
	        }
	        else{
            tipo = "N";
            titulo = "Nuevo Crédito";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_addcreditoLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbarcredito").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addcredito');

	        $("#hd_idclientecredito").val(_id_cliente);

		      if (tipo != 'N'){
						$("#hd_idcredito").val(_id_credito);
						$("#cred_fechainicio").val(_fecha_inicio);
						$("#cred_fechafin").val(_fecha_fin);
						$("#cred_observacion").val(_observacion);
					}
			    else{
			    	$("#hd_idcredito").val(0);
			    	$("#cred_fechainicio").val('<?php echo $g_date; ?>');
			    	$("#cred_fechafin").val('<?php echo $g_date; ?>');
			    	$("#cred_observacion").val('');
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

			function f_GetInfoCliente(){
				var is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
				var documento = $("#cliente_documento").val();

				// Limpiando objetos
					$("#cliente_razonsocial").val('');
        	$("#cliente_direccion").val('');
					$("#wt_razonsocial2").hide();

				// Obteniendo información
					if (documento.length == 8 || documento.length == 11){
						$("#wt_razonsocial2").show();

						$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
	            function( data ) {
	            	if (is_ruc == 1){
	            		$("#cliente_razonsocial").val(data.denominacion.trim());
	              	$("#cliente_direccion").val(data.direccion.trim());
	            	}
	            	else{
	            		$("#cliente_razonsocial").val(data.nombre.trim());
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
				function f_GrabarCliente(){
					// Recupera variables
						var id_cliente = $("#hd_idcliente").val();
						var modo_grabar = $("#hd_modograbar").val();

            var cod_tipocliente = f_CleanInjection($("#cliente_tipocliente").val());
            var cod_tipodocumento = f_CleanInjection($("#cliente_tipodocumento").val());
            var documento = f_CleanInjection($("#cliente_documento").val());
            var razon_social = f_CleanInjection($("#cliente_razonsocial").val());
            var telefono1 = f_CleanInjection($("#cliente_telefono1").val());
            var telefono2 = f_CleanInjection($("#cliente_telefono2").val());
            var correo = f_CleanInjection($("#cliente_correo").val());
            var direccion = f_CleanInjection($("#cliente_direccion").val());

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
            $.post( "apis/backend.php", { accion: "grabar_cliente", modo_grabar: modo_grabar, id_cliente: id_cliente, cod_tipocliente: cod_tipocliente, cod_tipodocumento: cod_tipodocumento, documento: documento, razon_social: razon_social, telefono1: telefono1, telefono2: telefono2, correo: correo, direccion: direccion },
              function( data ) {
                if (data.estado == 2){
                  alert("El documento ingresado ya fue registrado anteriormente, por favor verificar");

                  return;
                }
                else{
                  if(data.estado == 1){
                  	f_LoadResultados();

                  	f_cerrarModal('modal_addcliente');
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar el Cliente");
                  }
                }

              }, "json");
				}

			// Cambiar estado de registros
        function f_CambiarEstado(_Estado, _id_cliente){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " el Cliente seleccionado?")){
            $.post( "apis/backend.php", { accion: "update_EstadoCliente", id_cliente: _id_cliente, estado: _Estado }, 
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
        function f_EliminarCliente(_id_cliente){
          if(confirm("¿Está seguro de eliminar el Cliente seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_Cliente", id_cliente: _id_cliente },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar el Cliente.");
                }
              }, "json");
          }
        };

      // Grabar Crédito
      	function f_GrabarCredito(){
      		// Recupera variables
      			var id_cliente = $("#hd_idclientecredito").val();
						var id_credito = $("#hd_idcredito").val();
						var modo_grabar = $("#hd_modograbarcredito").val();

      			var fecha_inicio = f_CleanInjection($("#cred_fechainicio").val());
      			var fecha_fin = f_CleanInjection($("#cred_fechafin").val());
      			var observacion = f_CleanInjection($("#cred_observacion").val());

      		// Validando datos
	      		if (fecha_inicio == null){
	            alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

	            return;
		        }
		        if (fecha_inicio.length == 0){
	            alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

	            return;
		        }

		        if (fecha_fin == null){
	            alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

	            return;
		        }
		        if (fecha_fin.length == 0){
	            alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

	            return;
		        }

		        if (fecha_fin < fecha_inicio){
	            alert('La fecha "Desde" no puede ser mayor a la fecha "Hasta".\nPor favor, verificar.');

	            return;
	        	}

	        // Grabando Datos
            $.post( "apis/backend.php", { accion: "grabar_clientecredito", modo_grabar: modo_grabar, id_cliente: id_cliente, id_credito: id_credito, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, observacion: observacion },
              function( data ) {
                if (data.estado == 2){
                  alert("El documento ingresado ya fue registrado anteriormente, por favor verificar");

                  return;
                }
                else{
                  if(data.estado == 1){
                  	f_LoadResultados();

                  	f_cerrarModal('modal_addcredito');
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar el Cliente");
                  }
                }

              }, "json");
      	}

			// Cambiar estado de registros
        function f_CambiarEstadoCredito(_Estado, _id_credito){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " el registro seleccionado?")){
            $.post( "apis/backend.php", { accion: "update_EstadoCredito", id_credito: _id_credito, estado: _Estado }, 
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
        function f_EliminarCredito(_id_credito){
          if(confirm("¿Está seguro de eliminar el registro seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_Credito", id_credito: _id_credito },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar el Cliente.");
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