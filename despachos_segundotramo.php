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

		<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Gestión Despachos - 2do Tramo</title>

		<script type="text/javascript">
			var is_mobile = 0;
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
	            <div  class="modal-body" style=" padding: 0px !important;">
	             
	            	<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Emp. de Transporte</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_transportista" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
													
												</select>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Placa</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_placa" type="text" class="form-control" style="font-size: 14px;">
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Lote</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_lote" type="text" class="form-control" style="font-size: 14px;">
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

				<div class="row">
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

							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="row" style="padding: 20px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<div class="d-flex">
											<h5>Resumen de Unidades</h5>

											<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Cargando datos...</label>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
									<table class="table table-bordered table-hover">
					        	<thead>
					        		<tr style="font-size: 12px;">
					        			<th rowspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px; border-top-left-radius: 15px;">
					        				N°
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Fecha Estimada Despacho
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
					        				Destino
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				N° Placa 1
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				N° Placa 2 (Remolque)
					        			</th>

					        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Emp. de Transporte
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
					        				Tipo Vehículo
					        			</th>

					        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Información de Lotes
					        			</th>

					        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Información de Pesos de Despacho
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px; border-top-right-radius: 15px;">
					        				Observación
					        			</th>

					        			<!-- <th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px; border-top-right-radius: 15px;">
					        				Cierre
					        			</th> -->
					        		</tr>

					        		<tr style="font-size: 12px;">
					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
					        				DNI / RUC
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
					        				Razón Social
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 115px;">
					        				Código GEL
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
					        				N° Parte
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
					        				Presentación
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
					        				N° Bigbags
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
					        				TMH de Programación
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Tara
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Bruto
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Neto
					        			</th>

					        			<!-- <th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Acción
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Fecha Hora
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Usuario
					        			</th> -->
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

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_addrecepcion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addrecepcionLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addrecepcionLabel">Nueva Recepción de Unidad</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div id="div_recepcion1">
			        <div class="row" style="padding: 5px; background-color: #f0efe8; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Condición:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<select id="registro_condicion" class="form-select" style="text-align: left;" onchange="f_LoadListaTipoCarga();">
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
							</div>

							<div class="row" style="padding: 5px;">
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
											<input id="registro_placa2" type="text" class="form-control" style="text-align: center; margin-left: 2px;" placeholder="111" onkeyup="f_KeyUpPlaca();">
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px; margin-top: -10px;">
									Emp. Transporte:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 90%">
											<select id="registro_transportista" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2">
											<button type="button" class="btn" onclick="f_AddTransportista();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Tipo Vehículo:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<select id="registro_tipovehiculo" class="form-select" data-placeholder="Elija una opción..." onchange="f_TieneCarreta();">
										<option selected value="">Elija una opción...</option>
										<option value="x" style="font-size: 6px;" disabled></option>

										<?php

										$t = 1;

										$q_tipovehiculo = "SELECT Id,
			                        								UPPER(descripcion) AS descripcion,
			                        								tiene_carreta
							                           FROM tbconfig_tipovehiculo
							                          WHERE estado = 'A'
							                         ORDER BY descripcion";

						        if ($res_tipovehiculo = mysqli_query($enlace, $q_tipovehiculo)){
						          if (mysqli_num_rows($res_tipovehiculo) > 0) {
						            while($row_tipovehiculo = mysqli_fetch_array($res_tipovehiculo)){
						              ?>

						              <option value="<?php echo $row_tipovehiculo["Id"].'|'.$row_tipovehiculo["tiene_carreta"]; ?>"><?php echo $row_tipovehiculo["descripcion"]; ?></option>

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

							<div id="div_placa2" class="row" style="padding: 5px; display: none;">
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
									</div>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Conductor:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 90%">
											<select id="registro_conductor" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2">
											<button type="button" class="btn" onclick="f_AddConductor();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Tipo Carga:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<select id="registro_tipocarga" class="form-select" data-placeholder="Elija una opción...">

									</select>
								</div>
							</div>

							<div id="div_zonaorigen" class="row" style="padding: 5px; display: none;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Zona Origen:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<div class="d-flex">
										<div class="flex-fill" style="max-width: 90%">
											<select id="registro_zonaorigen" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-2">
											<button type="button" class="btn" onclick="f_AddZonaOrigen();" style="padding: 0px; margin-left: 10px;">
												<img src="<?php echo $btn_add; ?>" style="width: 35px;">
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Observación:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<textarea id="registro_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
								</div>
							</div>
						</div>

						<div id="div_recepcion2" style="display: none;">
							<div class="row" style="padding: 5px; background-color: #f0efe8; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<label style="font-weight: bold; text-align: center;">
									Registro de Acompañantes
								</label>
							</div>

							<div class="row" style="padding: 5px;">
								<table class="table table-bordered table-hover">
				        	<thead>
				        		<tr style="font-size: 12px;">
				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
				        				N°
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				DNI
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Nombres
				        			</th>

				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
				        				Foto Documento
				        			</th>
				        		</tr>
				        	</thead>

				        	<tbody id="tbl_acompanantes">

				        	</tbody>
				        </table>
							</div>
						</div>

						<div id="div_recepcion3" style="display: none;">
							<div class="row" style="padding: 5px; background-color: #f0efe8; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="form-check">
								  <input id="chk_vehiculoparticular" class="form-check-input" type="checkbox">
								  <label class="form-check-label" for="chk_vehiculoparticular">
								    Ingresa con Vehículo Particular
								  </label>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<table class="table table-bordered table-hover">
				        	<thead>
				        		<tr style="font-size: 12px;">
				        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
				        				Imágenes Adicionales
				        			</th>
				        		</tr>

				        		<tr style="font-size: 12px;">
				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				N°
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Imagen
				        			</th>
				        		</tr>
				        	</thead>

				        	<tbody id="tbl_imagenes">

				        	</tbody>
				        </table>
							</div>
						</div>
		      </div>

		      <input id="hd_idregistro" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarregistro" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarregistro_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
		        <button id="btn_Regresar_2" type="button" class="btn btn-dark wt_grabarregistro_button" style="display: none; font-size: 14px;" onclick="f_RegresarRecepcion(2);">Regresar</button>
		        <button id="btn_Regresar_3" type="button" class="btn btn-dark wt_grabarregistro_button" style="display: none; font-size: 14px;" onclick="f_RegresarRecepcion(3);">Regresar</button>
		        <button id="btn_Next_1" type="button" class="btn btn-warning wt_grabarregistro_button" style="font-size: 14px;" onclick="f_GrabarRecepcion_Next(1);">Continuar</button>
		        <button id="btn_Next_2" type="button" class="btn btn-warning wt_grabarregistro_button" style="display: none; font-size: 14px;" onclick="f_GrabarRecepcion_Next(2);">Continuar</button>
		        <button id="btn_ConfirmarAcompanantes" type="button" class="btn btn-danger wt_grabarregistro_button" style="display: none; font-size: 14px;" onclick="f_GrabarRecepcion_Confirmar();">Finalizar y Confirmar</button>
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

		<div class="modal fade" id="modal_addacompanante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addacompananteLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_addacompanante_content" class="modal-content" style="margin-top: 225px;">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_addacompananteLabel">Nuevo Acompañante</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								DNI:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="acompanante_dni" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente(3);">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Nombres: <img id="wt_acompanante" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="acompanante_nombres" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
							</div>
						</div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarAcompanante();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_showinfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showinfoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5">Información de: </h1>
		        <h1 class="modal-title fs-5" id="modal_showinfoLabel" style="margin-left: 10px;"></h1>

						<div id="wt_info" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Cargando imagen...</label>
						</div>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div>
		      		<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Ingreso Planta:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="info_ingreso" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

			        <div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Condición:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="info_condicion" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Placa 1:
								</div>

								<div class="col-md-4 col-sm-4 col-xs-12">
									<input id="info_placa1" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Emp. Transporte:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="info_transportista_documento" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled> <br>

									<textarea id="info_transportista" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="font-size: 14px; text-transform: uppercase; font-weight: bold; margin-top: -20px;" disabled></textarea>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Tipo Vehículo:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="info_tipovehiculo" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

							<div id="div_placa2_info" class="row" style="padding: 5px; display: none;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Placa 2:
								</div>

								<div class="col-md-4 col-sm-4 col-xs-12">
									<input id="info_placa2" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Conductor:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="info_conductor" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Tipo Carga:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="info_tipocarga" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

							<div id="div_zonaorigen_info" class="row" style="padding: 5px; display: none;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Zona Origen:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="info_zonaorigen" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
									Observación:
								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<textarea id="info_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled></textarea>
								</div>
							</div>

							<div class="row" style="padding: 5px; display: none;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">

								</div>

								<div class="col-md-9 col-sm-9 col-xs-12">
									<input id="chk_tienevehiculoparticular" class="form-check-input obj_cab" type="checkbox" disabled>
								  <label class="form-check-label" for="chk_tienevehiculoparticular">
								    Ingresó con Vehículo Particular
								  </label>
								</div>
							</div>

							<div class="row" style="padding: 5px; margin-top: 10px;">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<table class="table table-bordered table-hover">
					        	<thead>
					        		<tr style="font-size: 12px;">
					        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
					        				Información de Acompañantes
					        			</th>
					        		</tr>

					        		<tr style="font-size: 12px;">
					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				DNI
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Nombres
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Imagen
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Fecha Hora Salida
					        			</th>
					        		</tr>
					        	</thead>

					        	<tbody id="tbl_infoacompanantes">

					        	</tbody>
					        </table>
					      </div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<table class="table table-bordered table-hover">
					        	<thead>
					        		<tr style="font-size: 12px;">
					        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
					        				Información de Salida de Unidad
					        			</th>
					        		</tr>

					        		<tr style="font-size: 12px;">
					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Fecha Hora
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Estado Unidad
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Observación
					        			</th>
					        		</tr>
					        	</thead>

					        	<tbody id="tbl_infosalidas">

					        	</tbody>
					        </table>
					      </div>
							</div>

							<div class="row" style="padding: 5px; display: none;">
								<table class="table table-bordered table-hover">
				        	<thead>
				        		<tr style="font-size: 12px;">
				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
				        				Imágenes Adicionales
				        			</th>
				        		</tr>

				        		<tr style="font-size: 12px;">
				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 60px;">
				        				N°
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Imagen
				        			</th>
				        		</tr>
				        	</thead>

				        	<tbody id="tbl_infoimagenes">

				        	</tbody>
				        </table>
							</div>
						</div>
		      </div>

		      <input id="hd_idregistro" type="hidden">
		      <input id="hd_modograbar" type="hidden">

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

		<div class="modal fade" id="modal_registrosalida" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_registrosalidaLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_registrosalida_content" class="modal-content" style="margin-top: 100px;">
		      <div class="modal-header" style="background-color: #dc3545;">
		        <h1 class="modal-title fs-5" style="color: #ffffff;">Registro de Salida: </h1>
		        <h1 class="modal-title fs-5" id="modal_registrosalidaLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>

		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Estado Unidad:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="salida_estado" class="form-select" style="text-align: left;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_estadossalida = "SELECT Id,
	                          								 descripcion
						                            FROM tbconfig_estadosalidaunidades
						                           WHERE estado = 'A'";

					        if ($res_estadossalida = mysqli_query($enlace, $q_estadossalida)){
					          if (mysqli_num_rows($res_estadossalida) > 0) {
					            while($row_estadossalida = mysqli_fetch_array($res_estadossalida)){
					              ?>

					              <option value="<?php echo $row_estadossalida["Id"]; ?>"><?php echo $row_estadossalida["descripcion"]; ?></option>

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
								Observación:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="salida_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
							</div>
						</div>

						<div class="row" style="padding: 5px; margin-top: 5px;">
							<hr/>
						</div>

						<div class="row" style="padding: 5px; margin-top: -10px;">
							<div id="wt_loadingacompanantes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px; margin-top: -10px;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Cargando datos...</label>
							</div>

							<table class="table table-bordered table-hover">
			        	<thead>
			        		<tr style="font-size: 12px;">
			        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
			        				Acompañantes
			        			</th>
			        		</tr>

			        		<tr style="font-size: 12px;">
			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				N°
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				DNI
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Nombres
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Imágenes
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Salida
			        			</th>
			        		</tr>
			        	</thead>

			        	<tbody id="tbl_acompanantes_salida">

			        	</tbody>
			        </table>
						</div>
		      </div>

		      <input id="hd_idregistrosalida" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarsalida" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarsalida_button" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_grabarsalida_button" onclick="f_RegistroSalida_Confirmar();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_showdocumentoacompanante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showdocumentoacompananteLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div id="modal_showdocumentoacompanante_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_showdocumentoacompananteLabel"></h1>

		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<img id="img_documentoacompanante" alt="">

							<div id="wt_documentoacompanante" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Cargando imagen...</label>
							</div>
						</div>
					</div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_showimagenes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showimagenesLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div id="modal_showimagenes_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_showimagenesLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<img id="img_imagenes" alt="">

							<div id="wt_imagenes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Cargando imagen...</label>
							</div>
						</div>
					</div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>
		
		<div class="modal fade" id="modal_showimagenes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showimagenesLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div id="modal_showimagenes_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_showimagenesLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<img id="img_imagenes" alt="">

							<div id="wt_imagenes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Cargando imagen...</label>
							</div>
						</div>
					</div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_getpeso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_getpesoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content" style="width: 120%;">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_getpesoLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div id="div_pesoinicial" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #f0efe8; padding: 5px; margin-bottom: 5px;">
							<div class="d-flex" style="padding: 5px;">
								<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; font-weight: bold; font-size: 14px; text-align: center;">
									Peso:
								</div>

								<div class="col-md-5 col-sm-5 col-xs-5">
									<input id="lote_peso" type="number" class="form-control obj_cab col-md-12 col-xs-12 show_peso" style="text-align: center; font-weight: bold; font-size: 15px;">
								</div>

								<div id="div_InterfazAuto" class="col-md-4 col-sm-4 col-xs-4">
									<div class="d-flex justify-content-center" style="margin-left: 10px; margin-top: 7px;">
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
		      </div>

		      <input id="hd_idlote" type="hidden">
		      <input id="hd_codlote" type="hidden">
		      <input id="hd_ispesotara" type="hidden">
		      <input id="hd_itemobjeto" type="hidden">
		      <input id="hd_isloteaum" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarpeso" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarpeso_button" data-bs-dismiss="modal" style="font-size: 14px;" onclick="f_GetPesoCerrar();">Cerrar</button>

		         <button type="button" class="btn btn-warning wt_grabarpeso_button" style="font-size: 14px;" onclick="f_ConfirmarPeso();">Confirmar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_editinfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_editinfoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_editinfo_content" class="modal-content" style="margin-top: 250px;">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_editinfoLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px; margin-left: 40px; margin-right: 40px;">
							<div class="flex-fill justify-content-center">
								<div id="div_lista" style="padding: 0px;">
									<select id="edit_Lista" class="form-select select_datos" data-placeholder="Elija una opción...">
										
									</select>
								</div>

								<input id="edit_InputText" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">

								<input id="edit_InputNumber" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>
		      </div>

		      <input id="hd_idbalanza" type="hidden">
		      <input id="hd_edititem" type="hidden">
		      <input id="hd_tipoobject" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarEdit();">Grabar</button>
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
					$("#nv_titulo").html('| Gestión Despachos - 2do Tramo');

				// Carga Filtros
					// f_LoadFiltroClientes();

				// Cargando listas generales
					f_LoadListaTransportistas();
					// f_LoadListaConductores();
					// f_LoadListaTipoCarga();
					// f_LoadListaZonaOrigen();

				// Setea el campo de Placa 2 (Carreta)
					// f_TieneCarreta();

				// Carga el detalle de información
					f_LoadResultados();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			// Listas para edición
			  $('.select_datos').select2({
			    theme: "bootstrap-5",
			    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			    placeholder: $( this ).data( 'placeholder' ),
			    allowClear: true,
			    dropdownParent: $('#modal_editinfo')
				}).on('select2:open', function() {
					$('body').css('zoom', '100%'); 
				}).on('select2:close', function() {
				    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
				});
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadListaTransportistas(_id_cliente){
				var _html = '<option></option>';
        _html += '<option value="x" style="font-size: 6px;" disabled></option>';

        $("#filtro_transportista").html('');

        $.post( "apis/backend.php", { accion: "get_BalanzaDespachos_FiltroTransportistas" }, 
          function( data ) {
            if(data.estado == 1){
              $("#filtro_transportista").html(data.html);
            }

          }, "json");
    	};

			function f_LoadResultados(){
				var _html = '';

				// Obteniendo filtros
	        var filtro_transportista = $("#filtro_transportista").val();
	        var filtro_placa = $("#filtro_placa").val();
	        var filtro_lote = $("#filtro_lote").val();

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_SegundoTramo_ListaPesosBalanza", filtro_transportista: filtro_transportista, filtro_placa: filtro_placa, filtro_lote: filtro_lote }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);
            }

            f_LoadingResumen(0);

          }, "json");
    	};

    	function f_AdminRecepcion(){
        f_OpenModal('modal_addrecepcion');

        $("#hd_idregistro").val(0);
				$("#hd_modograbar").val('N');

	    	$("#registro_placa1").val('');
        $("#registro_placa2").val('');

        $("#registro_transportista").val('');
        $("#registro_transportista").trigger('change');

        $("#registro_tipovehiculo").val('');
        $("#registro_tipovehiculo").trigger('change');

        $("#registro_conductor").val('');
        $("#registro_conductor").trigger('change');

        $("#registro_tipocarga").val('');
        $("#registro_tipocarga").trigger('change');

        $("#registro_zonaorigen").val('');
        $("#registro_zonaorigen").trigger('change');

        $("#registro_observacion").val('');
        $("#chk_vehiculoparticular").prop('checked', false);

        $("#tbl_acompanantes").html('');
        $("#tbl_imagenes").html('');

        $("#div_recepcion1").css('display', 'block');
        $("#div_recepcion2").css('display', 'none');
				$("#div_recepcion3").css('display', 'none');

				$("#btn_Regresar_2").hide();
  			$("#btn_Regresar_3").hide();

  			$("#btn_Next_1").show();
  			$("#btn_Next_2").hide();
      	$("#btn_ConfirmarAcompanantes").hide();

        f_LoadingGrabarIngreso(0);
    	}

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
					documento = $("#acompanante_dni").val();
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
						$("#acompanante_nombres").val('');
						$("#wt_acompanante").hide();
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
							$("#wt_acompanante").show();
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
			              	$("#acompanante_nombres").val(arr_response[0].split(':')[1].trim());
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
		              	$("#acompanante_nombres").val('NO ENCONTRADO');
		              }
	            	}

	            	if (_id_modulo == 1){
	            		$("#wt_razonsocial2").hide();
	            	}

	            	if (_id_modulo == 2){
	            		$("#wt_conductor").hide();
	            	}

	            	if (_id_modulo == 3){
	            		$("#wt_acompanante").hide();
	            	}

	            }, "json");
					}
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

    	function f_GrabarRecepcion_Next(_id_div){
    		if (_id_div == 1){
					// Recupera variables
						var registro_condicion = $("#registro_condicion").val();
						var registro_placa = f_CleanInjection($("#registro_placa1").val()) + '-' + f_CleanInjection($("#registro_placa2").val());
						var registro_transportista = $("#registro_transportista").val();
						var registro_tipovehiculo = $("#registro_tipovehiculo").val().split('|')[0];
						var tiene_carreta = $("#registro_tipovehiculo").val().split('|')[1];
						var registro_placa2 = f_CleanInjection($("#registro_placa1_2").val()) + '-' + f_CleanInjection($("#registro_placa2_2").val());
						var registro_conductor = $("#registro_conductor").val();
						var registro_tipocarga = $("#registro_tipocarga").val();
						var registro_zonaorigen = $("#registro_zonaorigen").val();
						var registro_observacion = f_CleanInjection($("#registro_observacion").val());

					// Validando datos
	          if (registro_condicion == null){
	            alert("Debe seleccionar la Condición de Ingreso.");

	            return;
	          }
	          if (registro_condicion.length == 0){
	            alert("Debe seleccionar la Condición de Ingreso.");

	            return;
	          }

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

	          if (registro_transportista == null){
	            alert("Debe seleccionar el Transportista.");

	            return;
	          }
	          if (registro_transportista.length == 0){
	            alert("Debe seleccionar el Transportista.");

	            return;
	          }

	          if (registro_tipovehiculo == null){
	            alert("Debe seleccionar el Tipo de Vehículo.");

	            return;
	          }
	          if (registro_tipovehiculo.length == 0){
	            alert("Debe seleccionar el Tipo de Vehículo.");

	            return;
	          }

	          if (tiene_carreta == 1){
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
	          else{
	          	registro_placa2 = '';
	          }

	          if (registro_conductor == null){
	            alert("Debe seleccionar el Conductor.");

	            return;
	          }
	          if (registro_conductor.length == 0){
	            alert("Debe seleccionar el Conductor.");

	            return;
	          }

	          if (registro_tipocarga == null){
	            alert("Debe seleccionar el Tipo de Carga.");

	            return;
	          }
	          if (registro_tipocarga.length == 0){
	            alert("Debe seleccionar el Tipo de Carga.");

	            return;
	          }

	        // Obtiene total de acompañantes
	          var table = document.getElementById('tbl_acompanantes');
  					var a = table.rows.length

	        // Setea tabla de Acompañantes
	          if (a == 0){
	          	var _html = $("#tbl_acompanantes").html();

		          _html += '<td colspan="6" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
		          _html += '	<button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddAcompanante();">';
		          _html += '		<b>+ Agregar Acompañante</b>';
		          _html += '	</button>';
		          _html += '</td>';

		          document.getElementById('tbl_acompanantes').insertRow(-1).innerHTML = _html;
	          }

	        // Continuar al siguiente grupo de datos
	          $("#div_recepcion1").hide(500);
	        	$("#div_recepcion2").show(500);
	        	
	        	$("#btn_Regresar_2").show();
	        	$("#btn_Regresar_3").hide();

	        	$("#btn_Next_1").hide();
	        	// $("#btn_Next_2").show();
	        	// $("#btn_ConfirmarAcompanantes").hide();
	        	$("#btn_Next_2").hide();
	        	$("#btn_ConfirmarAcompanantes").show();
        }

        if (_id_div == 2){
        	// Continuar al siguiente grupo de datos
	          $("#div_recepcion2").hide(500);
	        	$("#div_recepcion3").show(500);
	        	
	        	$("#btn_Regresar_2").hide();
	        	$("#btn_Regresar_3").show();

	        	$("#btn_Next_2").hide();
	        	$("#btn_ConfirmarAcompanantes").show();

        	// Obtiene total de Imágenes
	          var table = document.getElementById('tbl_imagenes');
  					var a = table.rows.length

	        // Setea tabla de Acompañantes
	          if (a == 0){
	          	var _html = $("#tbl_imagenes").html();

		          _html += '<td colspan="3" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
		          _html += '	<button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddImagenes();">';
		          _html += '		<b>+ Agregar Imagen</b>';
		          _html += '	</button>';
		          _html += '</td>';

		          document.getElementById('tbl_imagenes').insertRow(-1).innerHTML = _html;
	          }
        }
      }

    	function f_RegresarRecepcion(_id_div){
    		if (_id_div == 2){
          $("#div_recepcion1").show(500);
        	$("#div_recepcion2").hide(500);
    			
    			$("#btn_Regresar_2").hide();
    			$("#btn_Regresar_3").hide();

    			$("#btn_Next_1").show();
    			$("#btn_Next_2").hide();
        	$("#btn_ConfirmarAcompanantes").hide();
    		}

    		if (_id_div == 3){
          $("#div_recepcion2").show(500);
        	$("#div_recepcion3").hide(500);
    			
    			$("#btn_Regresar_2").show();
    			$("#btn_Regresar_3").hide();

    			$("#btn_Next_2").show();
        	$("#btn_ConfirmarAcompanantes").hide();
    		}
    	}

    	function f_AddAcompanante(){
		    // Cargando datos
	        f_OpenModal('modal_addacompanante');

		    	$("#acompanante_dni").val('');
          $("#acompanante_nombres").val('');
    	}

    	function f_GrabarAcompanante(){
    		// Recupera variables
          var acompanante_dni = f_CleanInjection($("#acompanante_dni").val().trim());
          var acompanante_nombres = f_CleanInjection($("#acompanante_nombres").val());

        // Validando datos
          if (acompanante_dni == null){
            alert("Debe ingresar el N° de DNI del Acompañante.");

            return;
          }
          if (acompanante_dni.length == 0){
            alert("Debe ingresar el N° de DNI del Acompañante.");

            return;
          }

          if (acompanante_nombres == null){
            alert("Debe ingresar los Nombres y Apellidos del Acompañante.");

            return;
          }
          if (acompanante_nombres.length == 0){
            alert("Debe ingresar los Nombres y Apellidos del Acompañante.");

            return;
          }

        // Eliminando la ultima fila (Botón de agregar acompañantes)
          var table = document.getElementById('tbl_acompanantes');
  				var a = table.rows.length

  				table.deleteRow(a - 1);

  			// Obteniendo Id temporal autogenerado
  				var _time = new Date();
	        _time = _time.getHours().toString().padStart(2, '0') + ":" + _time.getMinutes().toString().padStart(2, '0');

	        var tmp_Id = 'tmp-<?php echo $g_date ?>-' + _time;

  			// Agregar nuevo acompañante
	        var _html = '';

  				_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  				_html += '	' + a;
  				_html += '	<input id="tmp_id_' + a + '" type="hidden" value="' + tmp_Id + '_' + a + '">';
  				_html += '</td>';

  				_html += '<td class="del_tr" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  				_html += '	<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;">X</label>';
  				_html += '</td>';

  				_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  				_html += '	' + acompanante_dni;
  				_html += '</td>';

  				_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  				_html += '	' + acompanante_nombres;
  				_html += '</td>';

  				_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  				_html += '	<img class="imagen" src="" alt="" style="width: 80px; display: none;" id="img_acompanante_' + a + '" onclick="f_ShowDocumentoAcompanante(this.src, ' + "'" + acompanante_nombres + "', 1" + ');">';
  				_html += '</td>';

          _html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
          _html += '	<img src="<?php echo $img_camara ?>" style="width: 30px; cursor: pointer;" onclick="f_AddAcompanante_Imagen(' + a + ');">';
          _html += '</td>';

          document.getElementById('tbl_acompanantes').insertRow(-1).innerHTML = _html;

        // Agregar fila para Nuevo Acompañante
          _html = '<td colspan="6" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
          _html += '	<button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddAcompanante();">';
          _html += '		<b>+ Agregar Acompañante</b>';
          _html += '	</button>';
          _html += '</td>';

          document.getElementById('tbl_acompanantes').insertRow(-1).innerHTML = _html;

        // Cerrando Modal
          f_cerrarModal('modal_addacompanante');
      }

      $(document).on('click', '.del_tr', function (event) {
	      event.preventDefault();

	      $(this).closest('tr').remove();

	      // Obtiene total de Acompañantes
		      var table = document.getElementById('tbl_acompanantes');
	  			var _rows = table.rows.length - 1;

	      // Reinicia los contadores
	      	var x = 1;

	      	$("#tbl_acompanantes tr").each(function () {
	      		if (x <= _rows){
	          	$(this).find("td").eq(0).html(x);
	      		}

	          x ++;
	        });
	    });

      $(document).on('click', '.del_tr2', function (event) {
	      event.preventDefault();

	      $(this).closest('tr').remove();

	      // Obtiene total de Imágenes
		      var table = document.getElementById('tbl_imagenes');
	  			var _rows = table.rows.length - 1;

	      // Reinicia los contadores
	      	var x = 1;

	      	$("#tbl_imagenes tr").each(function () {
	      		if (x <= _rows){
	          	$(this).find("td").eq(0).html(x);
	          }

	          x ++;
	        });
	    });

	    function f_AddAcompanante_Imagen(_id_row){
			  var input = document.createElement('input');
			  input.type = 'file';
			  input.accept = 'image/*';
			  input.onchange = function(event) {
			    var file = event.target.files[0];
			    var reader = new FileReader();
			    reader.onload = function(e) {
			      var imagen = document.getElementById('img_acompanante_' + _id_row);
			      imagen.src = e.target.result;
			    };
			    reader.readAsDataURL(file);
			  };
			  input.click();

			  $("#img_acompanante_" + _id_row).show();
	    }

	    function f_ShowDocumentoAcompanante(_id_img, _nombres, _is_local){
		    // Colocando el título a la pantalla
	        $("#modal_showdocumentoacompananteLabel").html(_nombres);

	      // Limpiando objeto img
	        $("#img_documentoacompanante").attr('src', '');

	      // Obtiene el SRC si lo tuviera
	        if (_is_local == 1){
	        	// Cargando Imagen
			        var modalImg = document.getElementById('img_documentoacompanante');
			        modalImg.src = _id_img;
	        }
	        else{
	        	var _src = '';

		        f_LoadingDocumentoAcompanante(1);

		        $.post( "apis/backend.php", { accion: "get_ControlIngreso_AcompanantesSRC", id_img: _id_img }, 
		          function( data ) {
		            if(data.estado == 1){
		            	_src = data.src;
		            }

		            // Cargando Imagen
					        var modalImg = document.getElementById('img_documentoacompanante');
					        modalImg.src = _src;

					      f_LoadingDocumentoAcompanante(0);
		          });
	        }

	      // Abre modal
	      	f_OpenModal('modal_showdocumentoacompanante');
	    }

	    function f_AddImagenes(){
	    	// Eliminando la ultima fila (Botón de agregar imágenes)
          var table = document.getElementById('tbl_imagenes');
  				var a = table.rows.length

  				table.deleteRow(a - 1);

  			// Obteniendo Id temporal autogenerado
  				var _time = new Date();
	        _time = _time.getHours().toString().padStart(2, '0') + ":" + _time.getMinutes().toString().padStart(2, '0');

	        var tmp_Id = 'tmp_imagenes-<?php echo $g_date ?>-' + _time;

	    	// Agrega fila de imágenes
	        var _html = '';

					_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
					_html += '	' + a;
					_html += '	<input id="tmp_imagenes_id_' + a + '" type="hidden" value="' + tmp_Id + '_' + a + '">';
					_html += '</td>';

					_html += '<td class="del_tr2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
					_html += '	<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;">X</label>';
					_html += '</td>';

					_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
					_html += '	<img class="imagen" src="" alt="" style="width: 80px; display: none;" id="img_imagenes_' + a + '" onclick="f_ShowImagenes(this.src, 1);">';
					_html += '</td>';

	        document.getElementById('tbl_imagenes').insertRow(-1).innerHTML = _html;

	      // Agregar fila para Nuevo Acompañante
	        _html = '<td colspan="3" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
	        _html += '	<button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddImagenes();">';
          _html += '		<b>+ Agregar Imagen</b>';
	        _html += '	</button>';
	        _html += '</td>';

	        document.getElementById('tbl_imagenes').insertRow(-1).innerHTML = _html;

        // Agrega la imagen a la tabla
				  var input = document.createElement('input');
				  input.type = 'file';
				  input.accept = 'image/*';
				  input.onchange = function(event) {
				    var file = event.target.files[0];
				    var reader = new FileReader();
				    reader.onload = function(e) {
				      var imagen = document.getElementById('img_imagenes_' + a);
				      imagen.src = e.target.result;
				    };
				    reader.readAsDataURL(file);
				  };
				  input.click();

				  $("#img_imagenes_" + a).show();
	    }

	    function f_ShowImagenes(_id_img, _is_local, _item){
	    	// Colocando el título a la pantalla
	        $("#modal_showimagenesLabel").html('Imagen: ' + _item);

	      // Limpiando objeto img
	        $("#img_imagenes").attr('src', '');

		    // Cargando datos
		    	if (_is_local == 1){
		        var modalImg = document.getElementById('img_imagenes');
		        modalImg.src = _id_img;
	        }
	        else{
	        	var _src = '';

	        	f_LoadingImagenes(1);

	        	$.post( "apis/backend.php", { accion: "get_ControlIngreso_ImagenesSRC", id_img: _id_img }, 
		          function( data ) {
		            if(data.estado == 1){
		            	_src = data.src;
		            }

		            // Cargando Imagen
					        var modalImg = document.getElementById('img_imagenes');
		        			modalImg.src = _src;

					      f_LoadingImagenes(0);
		          });
	        }

	      // Abre modal
	      	f_OpenModal('modal_showimagenes');
	    }

	    function f_RegistroSalida(_id_registro){
	    	$("#hd_idregistrosalida").val(_id_registro);

	    	$("#salida_estado").val('');
	    	$("#salida_observacion").val('');

	    	// Cargando datos de acompañantes
	    		f_LoadingSalidaAcompanantes(1);

	    		$("#tbl_acompanantes_salida").html('');

	    		$.post( "apis/backend.php", { accion: "get_ListaAcompanantes", id_registro: _id_registro }, 
	          function( data ) {
	            if(data.estado == 1){
	              $("#tbl_acompanantes_salida").html(data.html);
	            }

	            f_LoadingSalidaAcompanantes(0);

	          }, "json");

      	f_OpenModal('modal_registrosalida');
	    }

	    function f_TieneCarreta(){
	    	$("#registro_placa1_2").val('');
	    	$("#registro_placa2_2").val('');

	    	if ($("#registro_tipovehiculo").val().trim().length == 0){
	    		$("#div_placa2").hide();
	    	}
	    	else{
	    		var tiene_carreta = $("#registro_tipovehiculo").val().split('|')[1];

		    	if (tiene_carreta == 0){
		    		$("#div_placa2").hide();
		    	}
		    	else{
		    		$("#div_placa2").show();
		    	}
	    	}
	    }

    	function f_ShowInformacion(_id_registro){
        f_OpenModal('modal_showinfo');

        f_LoadingShowInfo(1);

        // Limpiando objetos
        	$("#info_ingreso").val('');
					$("#info_condicion").val('');
					$("#info_placa1").val('');
					$("#info_transportista_documento").val('');
					$("#info_transportista").val('');
					$("#info_tipovehiculo").val('');
					$("#info_placa2").val('');
					$("#info_conductor").val('');
					$("#info_tipocarga").val('');
					$("#info_zonaorigen").val('');
					$("#info_zonaorigen").val('');
					$("#info_observacion").val('');
					$("#chk_tienevehiculoparticular").prop('checked', false);

					$("#tbl_infosalidas").html('');
					$("#tbl_infoacompanantes").html('');
					$("#tbl_infoimagenes").html('');

        // Cargando datos
        	$.post( "apis/backend.php", { accion: "get_ListaIngresoUnidades_Info", id_registro: _id_registro }, 
	          function( data ) {
	            if(data.estado == 1){
	              $.each( data.res, function( key, val ) {
	              	// Título de Ventana
	              		$("#modal_showinfoLabel").html(val.placa);
	              	// Llenando los datos principales
	              		$("#info_ingreso").val(val.dFechaIngreso + ' ' + val.dhoraingresoPlanta);
	              		$("#info_condicion").val(val.CLIENTE_CONDICION);
	              		$("#info_placa1").val(val.placa);
	              		$("#info_transportista_documento").val(val.documento);
	              		$("#info_transportista").val(val.TRANSPORTISTA);
	              		$("#info_tipovehiculo").val(val.TIPO_VEHICULO);

	              		if (val.tiene_carreta == 1){
	              			$("#div_placa2_info").show();

	              			$("#info_placa2").val(val.placa2);
	              		}
	              		else{
	              			$("#div_placa2_info").hide();
	              		}

	              		$("#info_conductor").val(val.CONDUCTOR);
	              		$("#info_tipocarga").val(val.TIPO_CARGA);

	              		// if (val.id_tipoingresounidad == 1){
	              		// 	$("#div_zonaorigen_info").show();

	              		// 	$("#info_zonaorigen").val(val.ZONA_ORIGEN);
	              		// }
	              		// else{
	              		// 	$("#div_zonaorigen_info").hide();
	              		// }

	              		$("#info_observacion").val(val.cNotas);
	              		$("#chk_tienevehiculoparticular").prop('checked', ((val.tiene_vehiculoparticular == 1) ? true : false));
	              });

	              // Llenando la Salida
              		$("#tbl_infosalidas").html(data.html_salida);

              	// Llenando Acompañantes
              		$("#tbl_infoacompanantes").html(data.html_acompanantes);

              	// Llenando Imágenes
              		$("#tbl_infoimagenes").html(data.html_imagenes);
	            }

	            f_LoadingShowInfo(0);

	          }, "json");
    	}

    	function f_ExportToExcel(){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_transportista = $("#filtro_transportista").val();
	        var filtro_placa = $("#filtro_placa").val();

        window.location.href = "export_to_excel/resumen_balanza_prev.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_transportista="+filtro_transportista+"&filtro_placa="+filtro_placa;
    	}

    	function f_PrintTicketBalanza(_id_md5){
    		url = 'print_ticketdespacho.php?x=' + _id_md5;
				
				window.open(url, '_blank');
    	}

    	function f_PrintCodigosBarra(_id_md5){
    		url = 'print_etiquetashumedad.php?x=' + _id_md5;
				
				window.open(url, '_blank');
    	}

    	function f_LoadFiltroClientes(){
    		// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();

        // Cargando clientes
        	$("#filtro_transportista").html('');

        	$.post( "apis/backend.php", { accion: "get_ClientesIngresoUnidadesxFechas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
          function( data ) {
            if(data.estado == 1){
            	$("#filtro_transportista").html(data.html);
            }

          }, "json");
    	}

    	// function f_Edit(_id_registro, _item, _valor){
    	// 	var show_select = 0;
    	// 	var show_inputtext = 0;
    	// 	var show_inputnumber = 0;
    	// 	var tipo_objeto = 0;
    	// 	var _cliente_condicion = $("#id_clientecondicion_" + _id_registro).val();

    	// 	var _etiqueta = '';

    	// 	// setea Objetos
	    // 		if (_item == 1){
	    // 			_etiqueta = 'Condición';

	    // 			f_LoadLista_Condicion(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 2){
	    // 			_etiqueta = 'N° Placa 1';

	    // 			show_inputtext = 1;
	    // 			tipo_objeto = 2;
	    // 		}

	    // 		if (_item == 3){
	    // 			_etiqueta = 'N° Placa 2 (Remolque)';

	    // 			show_inputtext = 1;
	    // 			tipo_objeto = 2;
	    // 		}

	    // 		if (_item == 4){
	    // 			_etiqueta = 'Emp. de Transporte';

	    // 			f_LoadLista_EmpresaTransporte(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 5){
	    // 			_etiqueta = 'Tipo de Vehículo';

	    // 			f_LoadLista_TipoVehiculo(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 6){
	    // 			_etiqueta = 'Licencia Conductor';

	    // 			f_LoadLista_ListaConductores(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 7){
	    // 			_etiqueta = 'Tipo Carga';

	    // 			f_LoadLista_ListaTipoCarga(_valor, _cliente_condicion);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 8){
	    // 			_etiqueta = 'Zona Origen';

	    // 			f_LoadLista_ListaZonaOrigen(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 9){
	    // 			_etiqueta = 'Proveedor Minero';

	    // 			f_LoadLista_ListaProveedorMinero(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 10){
	    // 			_etiqueta = 'Encargado Muestra';

	    // 			f_LoadLista_ListaEncargadoMuestra(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 11){
	    // 			_etiqueta = 'Producto';

	    // 			f_LoadLista_ListaProducto(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 12){
	    // 			_etiqueta = 'Tipo Mineral';

	    // 			f_LoadLista_ListaTipoMineral(_valor);

	    // 			show_select = 1;
	    // 			tipo_objeto = 1;
	    // 		}

	    // 		if (_item == 13){
	    // 			_etiqueta = 'Peso Bruto';

	    // 			show_inputnumber = 1;
	    // 			tipo_objeto = 3;
	    // 		}

	    // 		if (_item == 14){
	    // 			_etiqueta = 'Tara';

	    // 			show_inputnumber = 1;
	    // 			tipo_objeto = 3;
	    // 		}

    	// 	// Setea variables ocultas
    	// 		$("#hd_idbalanza").val(_id_registro);
    	// 		$("#hd_edititem").val(_item);
    	// 		$("#hd_tipoobject").val(tipo_objeto);

    	// 	// Setea pantalla
			// 		$("#div_lista").hide();
			// 		$("#edit_InputText").hide();
			// 		$("#edit_InputNumber").hide();

	    // 		if (show_select == 1){
	    // 			$("#div_lista").show();
	    // 		}

	    // 		if (show_inputtext == 1){
	    // 			$("#edit_InputText").show();

	    // 			$("#edit_InputText").val(_valor);
	    // 		}

	    // 		if (show_inputnumber == 1){
	    // 			$("#edit_InputNumber").show();

	    // 			$("#edit_InputNumber").val(parseFloat(_valor).toFixed(0));
	    // 		}

	    // 		$("#modal_editinfoLabel").html('Editar: <b>' + _etiqueta + '</b>');

    	// 	// Abrir pantalla
	    // 		f_OpenModal('modal_editinfo');
    	// }

    	function f_LoadLista_Condicion(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaCondicion", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_EmpresaTransporte(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_EmpresaTransporte", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_TipoVehiculo(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_TipoVehiculo", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_ListaConductores(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaConductores", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_ListaTipoCarga(_id_registro, _cliente_condicion){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaTipoCarga", id_registro: _id_registro, cliente_condicion: _cliente_condicion }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_ListaZonaOrigen(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaZonaOrigen", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_ListaProveedorMinero(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaProveedorMinero", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_ListaEncargadoMuestra(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaEncargadoMuestra", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_ListaProducto(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaProducto", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_LoadLista_ListaTipoMineral(_id_registro){
    		$("#edit_Lista").html('');

        $.post( "apis/backend.php", { accion: "get_ResumenBalanza_ListaTipoMineral", id_registro: _id_registro }, 
          function( data ) {
            if(data.estado == 1){
              $("#edit_Lista").html(data.html);
            }

          }, "json");
    	}

    	function f_PrintInformeCliente(_id_md5){
    		var url = 'print_ticket_humedad.php?x=' + _id_md5;

    		window.open(url,'_blank',"");
    	}

	    function f_ShowEvidencia(_id_cabecera){
	    	var _src = '';

	    	$("#img_imagenes").attr('src', '');

      	f_LoadingImagenes(1);

      	$.post( "apis/backend.php", { accion: "get_AnalisisHumedad_ReciboImagenSRC", id_cabecera: _id_cabecera }, 
          function( data ) {
            if (data.estado == 1){
            	_src = data.src;
            }

            // Cargando Imagen
			        var modalImg = document.getElementById('img_imagenes');
        			modalImg.src = _src;

			      f_LoadingImagenes(0);
          });

	      // Abre modal
	      	f_OpenModal('modal_showimagenes');
	    }

			function f_ShowGetPeso(_is_tara, _id_lote, _cod_lote_x, _id_itemobjeto, _is_loteaum){
				// Obtiene datos
					var cod_lote = $("#td_cod_lote_" + _id_lote).html().trim();
					var num_parte = $("#td_num_parte_" + _id_lote).html().trim();

				// Setea título
					if (_is_tara == 1){
						$("#modal_getpesoLabel").html('Registro de Tara para Lote: <label style="margin-left: 5px; color: #9e6d14;">' + cod_lote + ((num_parte.length == 0) ? '' : ' | ' + num_parte));
					}
					else{
						$("#modal_getpesoLabel").html('Registro de Peso Bruto para Lote: <label style="margin-left: 5px; color: #9e6d14;">' + cod_lote + ((num_parte.length == 0) ? '' : ' | ' + num_parte));
					}

				// Setea objetos hidden
					$("#hd_idlote").val(_id_lote);
					$("#hd_codlote").val(_cod_lote_x);
					$("#hd_ispesotara").val(_is_tara);
					$("#hd_itemobjeto").val(_id_itemobjeto);
					$("#hd_isloteaum").val(_is_loteaum);

				// Limpiando campos
					$("#lote_peso").val('');

				// Setea objetos
					$(".show_peso").prop('disabled', false);
					$("#lote_peso").val('');
					$("#chk_InterfazAuto").prop('checked', true);

					f_getPeso(1);

				f_OpenModal('modal_getpeso');
			}

			function f_getPeso(_on){
				// Validando el check de envío automático
					if (!$("#chk_InterfazAuto").prop('checked')){
						return;
					}

				if (_on == 1){
					find_peso = 1;
				}

				if (find_peso == 1){
					$.get( "apis/interfaces.php", { accion: "get_Peso", cod_balanza: 1 }, 
	          function( data ) {
	            if(data.estado == 1){
	              if (data.peso == -1){
	                $("#div_SinConexion").show();

	                return;
	              }
	              else{
                	$("#div_SinConexion").hide();

                	$("#lote_peso").val(data.peso);
	              }
	            }
	            else{
	            	$("#div_SinConexion").show();
	            }

	            setTimeout('f_getPeso()', 500);

	          }, "json");
				}
			}

			function f_ConfirmarPeso(){
				// Obteniendo datos
					var _id_lote = $("#hd_idlote").val();
					var _cod_lote = $("#hd_codlote").val();
					var _is_pesoinicial = $("#hd_ispesotara").val();
					var _id_itemobjeto = $("#hd_itemobjeto").val();
					var _is_loteaum = $("#hd_isloteaum").val();
					var _peso = $("#lote_peso").val();

				// Validando datos
					if (_peso <= 0){
						alert("El Peso ingresado no es válido.");

						return;
					}

				// Seteando Mesaje
					var _msg = ((_is_pesoinicial == 1) ? 'la Tara' : 'el Peso Bruto');

					if (!confirm("¿Está seguro de registrar " + _msg + ": " + _peso + " Kg?")){
						return;
					}

				// Guardando datos
					f_Edit(_id_itemobjeto, _id_lote, _cod_lote, _is_loteaum);
			}

    	function f_EditPeso(_id_registro, _item, _valor){
    		var show_select = 0;
    		var show_inputtext = 0;
    		var show_inputnumber = 0;
    		var tipo_objeto = 0;
    		var _cliente_condicion = $("#id_clientecondicion_" + _id_registro).val();

    		var _etiqueta = '';

    		// setea Objetos
	    		if (_item == 1){
	    			_etiqueta = 'Tara';

	    			show_inputnumber = 1;
	    			tipo_objeto = 3;
	    		}

    		// Setea variables ocultas
    			$("#hd_idbalanza").val(_id_registro);
    			$("#hd_edititem").val(_item);
    			$("#hd_tipoobject").val(tipo_objeto);

    		// Setea pantalla
					$("#div_lista").hide();
					$("#edit_InputText").hide();
					$("#edit_InputNumber").hide();

	    		if (show_select == 1){
	    			$("#div_lista").show();
	    		}

	    		if (show_inputtext == 1){
	    			$("#edit_InputText").show();

	    			$("#edit_InputText").val(_valor);
	    		}

	    		if (show_inputnumber == 1){
	    			$("#edit_InputNumber").show();

	    			$("#edit_InputNumber").val(parseFloat(_valor).toFixed(0));
	    		}

	    		$("#modal_editinfoLabel").html('Editar: <b>' + _etiqueta + '</b>');

    		// Abrir pantalla
	    		f_OpenModal('modal_editinfo');
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_KeyUpPlaca(){
				var placa1 = $("#registro_placa1").val().trim();
				var placa2 = $("#registro_placa2").val().trim();

				if (placa1.length == 3){
					document.getElementById("registro_placa2").focus();
				}

				// Obtiene los datos de Placa
					if (placa1.length == 3 && placa2.length == 3){
						$.post( "apis/backend.php", { accion: "get_InfoUnidad", placa: placa1 + '-' + placa2 }, 
		          function( data ) {
		            if(data.estado == 1){
		              $("#registro_transportista").val(data.id_transportista);
		              $("#registro_transportista").trigger('change');

		              $("#registro_tipovehiculo").val(data.id_tipovehiculo);
		              $("#registro_tipovehiculo").trigger('change');

		              $("#registro_conductor").val(data.id_conductor);
		              $("#registro_conductor").trigger('change');
		            }
		          }, "json");
					}
			}

			function f_KeyUpPlaca2(){
				var placa2 = $("#registro_placa1_2").val();

				if (placa2.trim().length == 3){
					document.getElementById("registro_placa2_2").focus();
				}
			}

			$("#modal_addrecepcion").on('shown.bs.modal', function(){
      	$("#registro_placa1").focus();
    	});

			$("#modal_addcliente").on('shown.bs.modal', function(){
      	$("#cliente_tipocliente").focus();
    	});

			$("#modal_addconductor").on('shown.bs.modal', function(){
      	$("#conductor_dni").focus();
    	});

			$("#modal_addzonaorigen").on('shown.bs.modal', function(){
      	$("#zona_origen").focus();
    	});

			function f_LoadingGrabarIngreso(_is_show){
				if (_is_show == 1){
					$("#wt_grabarregistro").show();

					$(".wt_grabarregistro_button").prop('disabled', true);
				}
				else{
					$("#wt_grabarregistro").hide();

					$(".wt_grabarregistro_button").prop('disabled', false);
				}
			}

			function f_LoadingRegistroSalida(_is_show){
				if (_is_show == 1){
					$("#wt_grabarsalida").show();

					$(".wt_grabarsalida_button").prop('disabled', true);
				}
				else{
					$("#wt_grabarsalida").hide();

					$(".wt_grabarsalida_button").prop('disabled', false);
				}
			}

			function f_LoadingSalidaAcompanantes(_is_show){
				if (_is_show == 1){
					$("#wt_loadingacompanantes").show();
				}
				else{
					$("#wt_loadingacompanantes").hide();
				}
			}

			function f_LoadingResumen(_is_show){
				if (_is_show == 1){
					$("#wt_resumen").show();
				}
				else{
					$("#wt_resumen").hide();
				}
			}

			function f_LoadingDocumentoAcompanante(_is_show){
				if (_is_show == 1){
					$("#wt_documentoacompanante").show();
				}
				else{
					$("#wt_documentoacompanante").hide();
				}
			}

			function f_LoadingImagenes(_is_show){
				if (_is_show == 1){
					$("#wt_imagenes").show();
				}
				else{
					$("#wt_imagenes").hide();
				}
			}

			function f_LoadingShowInfo(_is_show){
				if (_is_show == 1){
					$("#wt_info").show();
				}
				else{
					$("#wt_info").hide();
				}
			}

			$("#modal_getpeso").on('shown.bs.modal', function(){
      	$("#lote_peso").focus();
    	});

		  function f_BreakAutomatico(){
		  	$("#chk_InterfazAuto").prop('checked', false);

	  		$("#div_SinConexion").hide();

	  		f_getPeso(0);
		  }

		  function f_GetPesoCerrar(){
		  	f_BreakAutomatico();
		  }
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_GrabarRecepcion_Confirmar(){
				// Recupera variables
					var registro_condicion = $("#registro_condicion").val();

					var registro_placa = f_CleanInjection($("#registro_placa1").val().trim()) + '-' + f_CleanInjection($("#registro_placa2").val().trim());
					registro_placa = registro_placa.toUpperCase();

					var registro_transportista = $("#registro_transportista").val();
					var registro_tipovehiculo = $("#registro_tipovehiculo").val().split('|')[0];
					var tiene_carreta = $("#registro_tipovehiculo").val().split('|')[1];

					var registro_placa2 = f_CleanInjection($("#registro_placa1_2").val().trim()) + '-' + f_CleanInjection($("#registro_placa2_2").val().trim());
					registro_placa2 = registro_placa2.toUpperCase();

					var registro_conductor = $("#registro_conductor").val();
					var registro_tipocarga = $("#registro_tipocarga").val();
					var registro_zonaorigen = $("#registro_zonaorigen").val();
					var registro_observacion = f_CleanInjection($("#registro_observacion").val().trim().toUpperCase());
					var vehiculo_particular = (($("#chk_vehiculoparticular").prop('checked')) ? 1 : 0);

				f_LoadingGrabarIngreso(1);

				// Obtiene total de acompañantes
          var table = document.getElementById('tbl_acompanantes');
					var _rows_acompanantes = table.rows.length - 1;

        // Recorre la tabla de Acompañanates y obtiene los datos
          var a = 1;
          var arr_acompanantes = [];
          var arr_acompanantes_datos = [];

          $('#tbl_acompanantes tr').each(function () {
          	if (a <= _rows_acompanantes){
	            var _acompanante = {
	            	cod_auto: a,
					      dni: $(this).find("td").eq(2).html(),
					      nombres: $(this).find("td").eq(3).html(),
					      imagen: $(this).find('.imagen').attr('src')
					    };

					    var _acompanante_datos = {
	            	cod_auto: a,
					      dni: $(this).find("td").eq(2).html(),
					      nombres: $(this).find("td").eq(3).html(),
					      tiene_imagen: (($(this).find('.imagen').attr('src').length > 0) ? 1 : 0)
					    };

					    arr_acompanantes.push(_acompanante);
					    arr_acompanantes_datos.push(_acompanante_datos);
				    }

				    a ++;
          });

				// Obtiene total de Imágenes adicionales
          var table = document.getElementById('tbl_imagenes');
					var _rows_imagenes = table.rows.length - 1;

        // Recorre la tabla de Acompañanates y obtiene los datos
          var a = 1;
          var arr_imagenes = [];
          var arr_imagenes_datos = [];

          $('#tbl_imagenes tr').each(function () {
          	if (a <= _rows_imagenes){
	            var _imagen = {
	            	cod_auto: a,
					      imagen: $(this).find('.imagen').attr('src')
					    };

					    var _imagen_datos = {
	            	cod_auto: a
					    };

					    arr_imagenes.push(_imagen);
					    arr_imagenes_datos.push(_imagen_datos);
				    }

				    a ++;
          });

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_recepcionunidades", registro_condicion: registro_condicion, registro_placa: registro_placa, registro_transportista: registro_transportista, registro_tipovehiculo: registro_tipovehiculo, tiene_carreta: tiene_carreta, registro_placa2: registro_placa2, registro_conductor: registro_conductor, registro_tipocarga: registro_tipocarga, registro_zonaorigen: registro_zonaorigen, registro_observacion: registro_observacion, tiene_vehiculoparticular: vehiculo_particular, arr_acompanantes_datos: JSON.stringify(arr_acompanantes_datos), arr_imagenes_datos: JSON.stringify(arr_imagenes_datos) },
            function( data ) {
            	if(data.estado == 1){
              	f_LoadResultados();

              	var id_registro = data.id_registro;

              	// Grabando Acompañantes
	              	if (arr_acompanantes.length > 0){
	              		$.post( "apis/backend.php", { accion: "grabar_recepcionunidades_acompanantes", id_registro: id_registro, arr_acompanantes: JSON.stringify(arr_acompanantes) },
					            function( data ) {
					            	if(data.estado == 0){
					            		alert("Ocurrió un error al momento de grabar los Acompañantes en Segundo Plano.");

					                return;
					            	}

				            	}, "json");
	              	}

              	// Grabando Imágenes
	              	if (arr_imagenes.length > 0){
	              		$.post( "apis/backend.php", { accion: "grabar_recepcionunidades_imagenes", id_registro: id_registro, arr_imagenes: JSON.stringify(arr_imagenes) },
					            function( data ) {
					            	if(data.estado == 0){
					            		alert("Ocurrió un error al momento de grabar las Imágenes en Segundo Plano.");

					                return;
					            	}

				            	}, "json");
	              	}
              }
              else{
                alert("Ocurrió un error al momento de grabar los datos de ingreso.");

                f_LoadingGrabarIngreso(0);

                return;
              }

              f_LoadingGrabarIngreso(0);

              f_cerrarModal('modal_addrecepcion');

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
                alert("El documento ingresado ya fue registrado anteriormente.\n\nPor favor verificar.");

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

	    function f_RegistroSalida_Confirmar(){
	    	// Recupera variables
	    		var id_registro = $("#hd_idregistrosalida").val();
					var salida_estado = $("#salida_estado").val();
					var des_salidaestado = $("#salida_estado option:selected").text();
					var salida_observacion = f_CleanInjection($("#salida_observacion").val().trim());

				// Validando datos
          if (salida_estado == null){
            alert("Debe seleccionar el Estado de la Unidad.");

            return;
          }
          if (salida_estado.length == 0){
            alert("Debe seleccionar el Estado de la Unidad.");

            return;
          }

        // Obtiene la lista de Acompañantes seleccionados
          var a = 1;
          var arr_acompanantes = '';

          $("#tbl_acompanantes_salida tr").each(function () {
	      		if ($("#chk_acompanante_" + a).prop('checked')){
	      			arr_acompanantes += $("#id_acompanante_" + a).val() + '|';
	      		}

	          a ++;
	        });

	        if (arr_acompanantes.length > 0){
	        	arr_acompanantes = arr_acompanantes.substring(0, arr_acompanantes.length - 1);
	        }

				// Grabando Datos
          f_LoadingRegistroSalida(1);

          $.post( "apis/backend.php", { accion: "grabar_salidaunidades", id_registro: id_registro, salida_estado: salida_estado, salida_observacion: salida_observacion, arr_acompanantes: arr_acompanantes },
            function( data ) {
              if(data.estado == 1){
              	$("#td_salida_1_" + id_registro).html(data.fechahora_registro + '</br><i>' + data.usuario_registro + '</i>');
              	$("#td_salida_2_" + id_registro).html(des_salidaestado);
              	$("#td_salida_3_" + id_registro).html(salida_observacion.toUpperCase());

              	f_LoadResultados();
              }

              f_LoadingRegistroSalida(0);

              f_cerrarModal('modal_registrosalida');

            }, "json");
	    }

	    function f_RegistroSalida_Acompanantes(_id_acompanante, _nombres){
	    	if (!confirm("¿Está seguro de registrar la salida de:\n\n" + _nombres)){
	    		return;
	    	}

	    	// Grabando salida
	    		$.post( "apis/backend.php", { accion: "grabar_salidaacompanante", id_acompanante: _id_acompanante }, 
	          function( data ) {
	            if(data.estado == 1){
	              $("#td_salidaacompanante_" + _id_acompanante).html(data.fechahora_salida + '<br><i>' + data.usuario_registro + '</i>');
	            }

	          }, "json");
	    }

	    function f_GrabarEdit(){
	    	// Obteniendo variables
	    		var id_registro = $("#hd_idbalanza").val();
	    		var item = $("#hd_edititem").val();
	    		var tipo_objecto = $("#hd_tipoobject").val();
	    		var condicion_ingreso = $("#id_clientecondicion_" + id_registro).val();

	    		var _text = '';
	    		var _valor = '';

    		// Obteniendo el valor
	    		if (item == 1){
	    			_valor = $("#edit_InputNumber").val().trim();

	    			_text = _valor;
	    		}


    		// Grabando datos
	    		$.post( "apis/backend.php", { accion: "grabar_EditBalanza", id_registro: id_registro, item: 17, valor: _valor, condicion_ingreso: condicion_ingreso },
            function( data ) {
              if(data.estado == 1){
              	// Actualizando el valor del td
              		var html = f_RedondearDecimales(_valor / 1000, 3);

              		html += '		<i id="event_click_tara_' + id_registro + '" class="bi bi-pencil-square" style="cursor: pointer;" onclick="f_EditPeso(' + id_registro + ', 1, ' + _valor + ', 0)"></i>';

            			$("#td_pesotara_" + id_registro).html(html);
              }

              f_cerrarModal("modal_editinfo");

            }, "json");
	    }

    	function f_Edit(_id_object, _id_registro, _cod_lote, _is_loteaum){
    		var _valor = '';
    		var _html = '';

    		// Obtiene datos
    			if (_id_object == 1){
    				_valor = $("#unidad_placa2_" + _id_registro).val().trim();
    			}

    			if (_id_object == 2){
    				_valor = $("#lote_numbigbags_" + _id_registro).val();
    			}

  				if (_id_object == 3 || _id_object == 4){
    				_valor = $("#lote_peso").val();
    			}

    			if (_id_object == 5){
    				_valor = $("#lote_observacion_" + _id_registro).val();
    			}

    		$.post( "apis/backend.php", { accion: "grabar_SegundoTramo_EditBalanza", id_object: _id_object, id_registro: _id_registro, valor: _valor, cod_lote: _cod_lote, is_loteaum: _is_loteaum },
          function( data ) {
            if(data.estado == 1){
            	if (_id_object == 3){
            		f_BreakAutomatico();

            		// Setea TD de Tara
            			_html = f_RedondearDecimales(_valor / 1000, 3);

            			$("#td_pesotara_" + _id_registro).html(_html);

            		// Setea TD de Peso Bruto
            			_html = '<button class="btn btn-warning" type="button" onclick="f_ShowGetPeso(0, ' + _id_registro + ", '" + _cod_lote + "', 4, " + _is_loteaum + ');" style="font-size: 14px;">';
									_html += '	Pesar';
									_html += '</button>';

            			$("#td_pesobruto_" + _id_registro).html(_html);
            	}

            	if (_id_object == 4){
            		// Setea TD de Peso Bruto
            			_html = f_RedondearDecimales(_valor / 1000, 3);

            			$("#td_pesobruto_" + _id_registro).html(_html);

            		// Setea TD de Peso Neto
            			var _peso_neto = f_RedondearDecimales(parseFloat((_valor / 1000)) - parseFloat($("#td_pesotara_" + _id_registro).html().trim()), 3);

            			$("#td_pesoneto_" + _id_registro).html(_peso_neto);
            	}

            	// Imprime Ticket
            		if (_id_object == 3 || _id_object == 4){
            			window.open('https://gelerp.intelli-apps.com/print_ticketdespacho.php?x=' + data.id_md5);
            		}

            	// Cerrar Modal
            		f_cerrarModal('modal_getpeso');
            }
            else{
              alert("Ocurrió un error al momento de grabar los datos.");
            }

          }, "json");
    	}

    	function f_Cierre(_id_unidad){
    		// Validando datos
    			// Setea Clases
					  var class_1 = 'is_cerrado_1_' + _id_unidad;
					  var class_2 = 'is_cerrado_2_' + _id_unidad;
					  var class_3 = 'is_cerrado_3_' + _id_unidad;
					  var class_4 = 'is_cerrado_4_' + _id_unidad;
					  var class_5 = 'is_cerrado_5_' + _id_unidad;
					  var class_6 = 'is_cerrado_6_' + _id_unidad;

				  // Setea variables de TMH
					  var arr_lotes = '';
					  var arr_lotes_des = '';
					  var arr_num_partes = '';
					  var arr_tipo_carga = '';
					  var arr_num_bigbags = '';
					  var arr_peso_neto = '';

					// 1. Obtiene Array de Códigos de Lotes
    				$('.' + class_1).each(function() {
					    arr_lotes += $(this).val() + '|';
					  });

					// 2. Obtiene Array de Id de Lotes
    				$('.' + class_2).each(function () {
					    arr_lotes_des += $(this).html().trim() + '|';
					  });

					// 3. Obtiene Array de N° Partes
    				$('.' + class_3).each(function () {
					    arr_num_partes += $(this).html().trim() + '|';
					  });

					// 4. Obtiene Array de Tipo de Carga (Presentación)
    				$('.' + class_4).each(function () {
					    arr_tipo_carga += $(this).val() + '|';
					  });

					// 5. Obtiene Array de N° Bigbags
    				$('.' + class_5).each(function () {
					    arr_num_bigbags += $(this).val() + '|';
					  });

					// 6. Obtiene Peso Neto
    				$('.' + class_6).each(function () {
					    arr_peso_neto += $(this).html().trim() + '|';
					  });

				  // Setea Arrays
						arr_lotes = arr_lotes.substring(0, arr_lotes.length - 1);
						arr_lotes_des = arr_lotes_des.substring(0, arr_lotes_des.length - 1);
						arr_num_partes = arr_num_partes.substring(0, arr_num_partes.length - 1);
						arr_tipo_carga = arr_tipo_carga.substring(0, arr_tipo_carga.length - 1);
						arr_num_bigbags = arr_num_bigbags.substring(0, arr_num_bigbags.length - 1);
						arr_peso_neto = arr_peso_neto.substring(0, arr_peso_neto.length - 1);

						arr_lotes = arr_lotes.split('|');
						arr_lotes_des = arr_lotes_des.split('|');
						arr_num_partes = arr_num_partes.split('|');
						arr_tipo_carga = arr_tipo_carga.split('|');
						arr_num_bigbags = arr_num_bigbags.split('|');
						arr_peso_neto = arr_peso_neto.split('|');

					// Valida el N° Bigbags y Registro de Peso Bruto
						var d = 0;
						var info_lote = '';
						var continuar = 1;

						while (d < arr_lotes.length){
							info_lote = arr_lotes_des[d] + ((arr_num_partes[d].length == 0) ? '' : ' | ' + arr_num_partes[d]);

							if (arr_tipo_carga[d] == 5 && arr_num_bigbags[d].length == 0){
								continuar = 0;

								alert("Debe ingresar el N° de Bigbags para el Lote: " + info_lote);

								d = 99999;
							}

							if (continuar == 1){
								if (arr_peso_neto[d].length == 0){
									continuar = 0;

									alert("Debe ingresar el Peso Bruto del Lote: " + info_lote);

									d = 99999;
								}
							}

							d ++;
						}

				// Realiza el Cierre
					if (continuar == 1){
						var placa = $("#td_placa_" + _id_unidad).html().trim();

						if (!confirm("¿Está seguro de realizar el Cierre de la Unidad: " + placa + "?")){
							return;
						}

						$.post( "apis/backend.php", { accion: "cierre_SegundoTramo_PesosBalanza", id_unidad: _id_unidad },
		          function( data ) {
		            if(data.estado == 1){
		            	// Seteando variables html
		            		var _html1 = '<label style="font-style: italic; color: #F23030; cursor: pointer; font-size: 13px;" onclick="f_Reabrir(' + _id_unidad + ')">';
										_html1 += '			<u> Reabrir </u>';
										_html1 += '		</label>';

		            	// Setea objetos
		            		$("#td_cierre_1_" + _id_unidad).html(_html1);
		            		$("#td_cierre_2_" + _id_unidad).html(data.fechahora_registro);
		            		$("#td_cierre_3_" + _id_unidad).html(data.usuario_registro);

									// Setea objetos de Cierre
										$(".is_cerrado_" + _id_unidad).prop('disabled', true);
		            }
		            else{
		              alert("Ocurrió un error al momento de realizar el Cierre.");
		            }

		          }, "json");
					}
    	}

		  function f_Reabrir(_id_unidad){
		  	// Validando cierre
		  		if (!confirm("¿Está seguro de Reabrir el Cierre seleccionado?")){
		  			return;
		  		}

		  	// Reabrir Cierre
		  		$.post( "apis/backend.php", { accion: "reabrir_SegundoTramo_PesosBalanza", id_unidad: _id_unidad },
	          function( data ) {
	            if(data.estado == 1){
								// Habilita los objetos de Cierre
									var _html = '<button class="btn btn-primary" type="button" onclick="f_Cierre(' + _id_unidad + ');" style="font-size: 14px;">';
									_html += '			<b>Cerrar</b>';
									_html += '    </button>';

									$("#td_cierre_1_" + _id_unidad).html(_html);
									$("#td_cierre_2_" + _id_unidad).html('');
									$("#td_cierre_3_" + _id_unidad).html('');

									// Setea objetos de Cierre
										$(".is_cerrado_" + _id_unidad).prop('disabled', false);
	            }
	            else{
	              alert("Ocurrió un error al momento de Reabrir el Cierre.");
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