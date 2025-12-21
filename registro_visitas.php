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

		<title><?php echo $nom_app; ?> | Registro de Visitas</title>

		<script type="text/javascript">
			var is_mobile = 0;
			let waiting_autorizacion = 0; // Para obtener el Peso en línea
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
												<h6 style="font-size: 14px;">Por Fechas</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="row" >
												<div class="col-md-12 col-sm-12 col-xs-12" >
													<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadClientes();">
												</div>
												<br><br>
												<div class="col-md-12 col-sm-12 col-xs-12" >
													<input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadClientes();">
												</div>

											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;" hidden>
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Transportista</h6>
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

									<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;" hidden>
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
								</div>

								<div class="row" style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
				              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
			            	</button>
			            </div>
			            <br><br>
			            <div class="col-md-12 col-sm-12 col-xs-12">
			            	<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
				              <b>Exportar a Excel</b>
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
											<h5>Resumen de Visitas</h5>

											<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Cargando datos...</label>
											</div>
										</div>
									</div>

									<div class="col-md-2 col-sm-2 col-xs-12" style="margin-top: -5px;">
										<button class="btn btn-primary" type="button" onclick="f_AdminVisitas('x');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px;">
				              <b>+ Nueva Visita</b>
				            </button>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
									<table class="table table-bordered table-hover">
					        	<thead>
					        		<tr style="font-size: 12px;">
					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
					        				N°
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
					        				Fecha Hora Ingreso
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
					        				Motivo Visita
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
					        				Usuario Contacto
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
					        				Ingresó con Vehículo Particular
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
					        				Observaciones
					        			</th>

					        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
					        				Información de Visitantes
					        			</th>

					        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px; border-top-right-radius: 15px;" hidden>
					        				Información de Salida
					        			</th>
					        		</tr>

					        		<tr style="font-size: 12px;">
					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				DNI
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
					        				Nombres
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Imagen
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
					        				Fecha Hora Salida
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
					        				Fecha Hora
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
					        				Observación
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

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_addrecepcion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addrecepcionLabel" aria-hidden="true">
		  <div class="modal-dialog" style="margin-top: 0px;">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addrecepcionLabel">Nueva Registro de Visita</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div id="div_recepcion1">
			        <div class="row" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Motivo Visita:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<select id="registro_motivo" class="form-select obj_waiting" data-placeholder="Elija una opción..." data-titulo="Motivo Visita" onclick="f_ShowListaModal(this);">
										<option selected value="">Elija una opción...</option>

										<?php

										$t = 1;

										$q_motivo = "SELECT Id,
                        								descripcion,
                        								is_otros
				                           FROM tbconfig_motivovisita
				                          WHERE estado = 'A'
				                         ORDER BY is_otros, descripcion";

						        if ($res_motivo = mysqli_query($enlace, $q_motivo)){
						          if (mysqli_num_rows($res_motivo) > 0) {
						            while($row_motivo = mysqli_fetch_array($res_motivo)){
						              ?>

						              <option value="<?php echo $row_motivo["Id"].'|'.$row_motivo["is_otros"]; ?>"><?php echo $row_motivo["descripcion"]; ?></option>

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
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Personal Contacto:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<select id="registro_contacto" class="form-select obj_waiting" data-placeholder="Elija una opción..." data-titulo="Personal Contacto" onclick="f_ShowListaModal(this);">
										<option selected value="">Elija una opción...</option>

										<?php

										$t = 1;

										$q_contacto = "SELECT E.Id,
	                        								E.documento,
	                        								E.nombres,
	                        								E.apellido_paterno,
	                        								E.apellido_materno,
	                        								E.telefono1,
	                        								E.telefono2
					                           FROM tb_empleados E
																					INNER JOIN tb_usuario U ON E.Id = U.id_empleado
					                          WHERE E.estado = 'A'
					                          	/*AND U.is_centrocontrol = 1*/
					                         ORDER BY E.nombres, E.apellido_paterno";

						        if ($res_contacto = mysqli_query($enlace, $q_contacto)){
						          if (mysqli_num_rows($res_contacto) > 0) {
						            while($row_contacto = mysqli_fetch_array($res_contacto)){
						              ?>

						              <option value="<?php echo $row_contacto["Id"].'|'.$row_contacto["telefono1"].'-'.$row_contacto["telefono2"]; ?>"><?php echo $row_contacto["nombres"].' '.$row_contacto["apellido_paterno"].' '.$row_contacto["apellido_materno"]; ?></option>

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
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									Observación:
								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<textarea id="registro_observacion" type="text" class="form-control col-md-12 col-xs-12 obj_waiting" rows="2" style="text-transform: uppercase;"></textarea>
								</div>
							</div>

							<div class="row" style="padding: 5px;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">

								</div>

								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="form-check">
									  <input id="chk_vehiculoparticular" class="form-check-input obj_waiting" type="checkbox" onchange="f_ShowPlaca();">
									  <label class="form-check-label" for="chk_vehiculoparticular">
									    Ingresa con Vehículo Particular
									  </label>
									</div>
								</div>
							</div>

							<div id="div_placa" class="row" style="padding: 5px; display: none;">
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
									
								</div>

								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="d-flex">
										<label style="margin-top: 5px; margin-right: 10px;">
											Placa:
										</label>

										<div class="col-md-5 col-sm-5 col-xs-5">
											<input id="registro_placa1" type="text" class="form-control obj_waiting" style="text-align: center; text-transform: uppercase;" placeholder="ABC" onkeyup="f_KeyUpPlaca();">
										</div>

										<div class="col-md-1 col-sm-1 col-xs-1">
											<label style="font-weight: bold; margin-left: 5px; margin-top: 5px;">-</label>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-6">
											<input id="registro_placa2" type="text" class="form-control obj_waiting" style="text-align: center; margin-left: 2px;" placeholder="111">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div style="margin-top: 10px;">
							<div class="row" style="padding: 5px; overflow-y: scroll; max-height: 350px;">
								<table class="table table-bordered table-hover">
				        	<thead>
				        		<tr style="font-size: 12px;">
				        			<th colspan="6" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
				        				Registro de Visitantes
				        			</th>
				        		</tr>

				        		<tr style="font-size: 12px;">
				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				N°
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				DNI
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Nombres
				        			</th>

				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Foto Documento
				        			</th>
				        		</tr>
				        	</thead>

				        	<tbody id="tbl_visitas">

				        	</tbody>
				        </table>
							</div>
						</div>
		      </div>

		      <input id="hd_idregistro" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer" style="margin-top: 20px;">
		      	<div id="wt_grabarregistro" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button id="btn_Cerrar" type="button" class="btn btn-secondary wt_grabarregistro_button obj_waiting" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
		        <button id="btn_ConfirmarAcompanantes" type="button" class="btn btn-warning wt_grabarregistro_button" style="font-size: 14px;" onclick="f_GrabarVisita_Confirmar();">Confirmar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_addvisita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addvisitaLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_addvisita_content" class="modal-content" style="margin-top: 225px;">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_addvisitaLabel">Nueva Visita</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								DNI:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="visita_dni" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente(3);">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Nombres: <img id="wt_visita" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="visita_nombres" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
							</div>
						</div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarVisita();">Grabar</button>
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
						<div class="row" style="padding: 5px;" hidden>
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Observación:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="salida_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
							</div>
						</div>

						<div class="row" style="padding: 5px; margin-top: 5px;" hidden>
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

			        	<tbody id="tbl_visitas_salida">

			        	</tbody>
			        </table>
						</div>
		      </div>

		      <input id="hd_idregistrosalida" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarsalida" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarsalida_button" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_grabarsalida_button" onclick="f_RegistroSalida_Confirmar();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_showdocumentovisita" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showdocumentovisitaLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div id="modal_showdocumentovisita_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_showdocumentovisitaLabel"></h1>

		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<img id="img_documentovisita" alt="">

							<div id="wt_documentoimagen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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
					$("#nv_titulo").html('| Registro de Visitas');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadResultados();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			// Lista de Unidades
			  // $('#registro_motivo, #registro_contacto').select2({
			  //   theme: "bootstrap-5",
			  //   width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			  //   placeholder: $( this ).data( 'placeholder' ),
			  //   allowClear: true,
			  //   dropdownParent: $('#modal_addrecepcion')
				// }).on('select2:open', function() {
				// 	$('body').css('zoom', '100%'); 
				// }).on('select2:close', function() {
				//     $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
				// });
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
				var _html = '';

        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        // var filtro_transportista = $("#filtro_transportista").val();
        // var filtro_placa = $("#filtro_placa").val();

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_ListaIngresoVisitas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);
            }

            f_LoadingResumen(0);

          }, "json");
    	};

    	function f_AdminVisitas(){
        f_OpenModal('modal_addrecepcion');

        $("#hd_idregistro").val(0);
				$("#hd_modograbar").val('N');

	    	$("#registro_motivo").val('');
	    	$("#registro_motivo").trigger('change');
        // $("#registro_contacto").val('');
        // $("#registro_contacto").trigger('change');
        $("#registro_observacion").val('');
        $("#chk_vehiculoparticular").prop('checked', false);
        $("#registro_placa1").val('');
        $("#registro_placa2").val('');
        $("#div_placa").hide();
        $("#btn_llamada").hide();
	    	$("#obj_waiting").prop('disabled', false);

        $("#tbl_visitas").html('');

        // Setea Envío de Solicitud
        	f_ReplaceClass("div_waiting", 'd-flex', '');
					$("#div_waiting").hide();

					$("#btn_sendautorizacion").show();
	    		$("#btn_cancelautorizacion").hide();
	    		$("#registro_contacto").prop('disabled', false);
	    		$("#lbl_autorizacion").hide();

	    	// Activando Waiting
	    		$("#btn_sendautorizacion").show();
	    		$("#btn_cancelautorizacion").hide();
	    		$("#registro_contacto").prop('disabled', false);
	    		$("#lbl_autorizacion").hide();
	    		$(".obj_waiting").prop('disabled', false);

        // Setea la tabla de acompañantes
        	var _html = '';

        	_html += '<tr>';
        	_html += '	<td colspan="6" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
          _html += '		<button class="btn btn-primary obj_waiting" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddVisita();">';
          _html += '			<b>+ Agregar Visita</b>';
          _html += '		</button>';
          _html += '	</td>';
          _html += '</tr>';

          $("#tbl_visitas").html(_html);

        f_LoadingGrabarIngreso(0);
    	}

    	function f_AddVisita(){
		    // Cargando datos
	        f_OpenModal('modal_addvisita');

		    	$("#visita_dni").val('');
          $("#visita_nombres").val('');

          $(".obj_waiting").prop('disabled', false);
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
					documento = $("#visita_dni").val();
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
						$("#visita_nombres").val('');
						$("#wt_visita").hide();
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
							$("#wt_visita").show();
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
			              	$("#visita_nombres").val(arr_response[0].split(':')[1].trim());
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
		              	$("#visita_nombres").val('NO ENCONTRADO');
		              }
	            	}

	            	if (_id_modulo == 1){
	            		$("#wt_razonsocial2").hide();
	            	}

	            	if (_id_modulo == 2){
	            		$("#wt_conductor").hide();
	            	}

	            	if (_id_modulo == 3){
	            		$("#wt_visita").hide();
	            	}

	            }, "json");
					}
			}

    	function f_GrabarVisita(){
    		// Recupera variables
          var visita_dni = f_CleanInjection($("#visita_dni").val().trim());
          var visita_nombres = f_CleanInjection($("#visita_nombres").val());

        // Validando datos
          if (visita_dni == null){
            alert("Debe ingresar el N° de DNI de la Visita.");

            return;
          }
          if (visita_dni.length == 0){
            alert("Debe ingresar el N° de DNI de la Visita.");

            return;
          }

          if (visita_nombres == null){
            alert("Debe ingresar los Nombres y Apellidos de la Visita.");

            return;
          }
          if (visita_nombres.length == 0){
            alert("Debe ingresar los Nombres y Apellidos de la Visita.");

            return;
          }

        // Eliminando la ultima fila (Botón de agregar acompañantes)
          var table = document.getElementById('tbl_visitas');
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
  				_html += '	' + visita_dni;
  				_html += '</td>';

  				_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  				_html += '	' + visita_nombres;
  				_html += '</td>';

  				_html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  				_html += '	<img class="imagen" src="" alt="" style="width: 80px; display: none;" id="img_visita_' + a + '" onclick="f_ShowDocumentoVisita(this.src, ' + "'" + visita_nombres + "', 1" + ');">';
  				_html += '</td>';

          _html += '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
          _html += '	<img src="<?php echo $img_camara ?>" style="width: 30px; cursor: pointer;" onclick="f_AddAcompanante_Imagen(' + a + ');">';
          _html += '</td>';

          document.getElementById('tbl_visitas').insertRow(-1).innerHTML = _html;

        // Agregar fila para Nuevo Acompañante
          _html = '<td colspan="6" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
          _html += '	<button class="btn btn-primary obj_waiting" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddVisita();">';
          _html += '		<b>+ Agregar Visita</b>';
          _html += '	</button>';
          _html += '</td>';

          document.getElementById('tbl_visitas').insertRow(-1).innerHTML = _html;

        // Cerrando Modal
          f_cerrarModal('modal_addvisita');
      }

	    function f_AddAcompanante_Imagen(_id_row){
			  var input = document.createElement('input');
			  input.type = 'file';
			  input.accept = 'image/*';
			  input.onchange = function(event) {
			    var file = event.target.files[0];
			    var reader = new FileReader();
			    reader.onload = function(e) {
			      var imagen = document.getElementById('img_visita_' + _id_row);
			      imagen.src = e.target.result;
			    };
			    reader.readAsDataURL(file);
			  };
			  input.click();

			  $("#img_visita_" + _id_row).show();
	    }

	    function f_ShowDocumentoVisita(_id_img, _nombres, _is_local){
		    // Colocando el título a la pantalla
	        $("#modal_showdocumentovisitaLabel").html(_nombres);

	      // Limpiando objeto img
	        $("#img_documentovisita").attr('src', '');

	      // Obtiene el SRC si lo tuviera
	        if (_is_local == 1){
	        	// Cargando Imagen
			        var modalImg = document.getElementById('img_documentovisita');
			        modalImg.src = _id_img;
	        }
	        else{
	        	var _src = '';

		        f_LoadingDocumentoAcompanante(1);

		        $.post( "apis/backend.php", { accion: "get_ControlIngreso_VisitasSRC", id_img: _id_img }, 
		          function( data ) {
		            if(data.estado == 1){
		            	_src = data.src;
		            }

		            // Cargando Imagen
					        var modalImg = document.getElementById('img_documentovisita');
					        modalImg.src = _src;

					      f_LoadingDocumentoAcompanante(0);
		          });
	        }

	      // Abre modal
	      	f_OpenModal('modal_showdocumentovisita');
	    }

	    function f_RegistroSalida(_id_registro){
	    	$("#hd_idregistrosalida").val(_id_registro);

	    	$("#salida_estado").val('');
	    	$("#salida_observacion").val('');

	    	// Cargando datos de acompañantes
	    		f_LoadingSalidaAcompanantes(1);

	    		$("#tbl_visitas_salida").html('');

	    		$.post( "apis/backend.php", { accion: "get_ListaVisitas", id_registro: _id_registro }, 
	          function( data ) {
	            if(data.estado == 1){
	              $("#tbl_visitas_salida").html(data.html);
	            }

	            f_LoadingSalidaAcompanantes(0);

	          }, "json");

      	f_OpenModal('modal_registrosalida');
	    }

	    function f_ShowPlaca(){
	    	$("#div_placa").hide();

	    	$("#registro_placa1").val('');
	    	$("#registro_placa2").val('');

	    	if ($("#chk_vehiculoparticular").prop('checked')){
	    		$("#div_placa").show();
	    	}
	    }

	    function f_DatosContacto(){
	    	// Valida el contacto seleccionado
	    		var _datos_contacto = $("#registro_contacto").val();

		    	if (_datos_contacto.length == 0){
		    		return;
		    	}

	    	// Obtiene el Contacto
	    		var _contacto = _datos_contacto.split('|')[0];

	    	// Obtiene los teléfonos
	    		var _telefonos = _datos_contacto.split('|')[1];
		    	var _telefono1 = _telefonos.split('-')[0];
		    	var _telefono2 = _telefonos.split('-')[1];

		    // Si tiene al menos 1 teléfono habilita el botón de llamada
		    	$("#btn_llamada").hide();

		    	if (_telefono1.trim().length > 0 || _telefono2.trim().length > 0){
		    		$("#btn_llamada").show();
		    	}
	    }

	    function f_SolicitudAutorizacion(){
	    	// Recupera variables
					var visita_motivo = $("#registro_motivo").val();
					var registro_contacto = $("#registro_contacto").val();
					registro_contacto = registro_contacto.split('|')[0];
					var registro_observacion = f_CleanInjection($("#registro_observacion").val().trim().toUpperCase());
					var vehiculo_particular = (($("#chk_vehiculoparticular").prop('checked')) ? 1 : 0);
					var visita_placa = f_CleanInjection($("#registro_placa1").val()) + '-' + f_CleanInjection($("#registro_placa2").val());

				// Validando datos
          if (visita_motivo == null){
            alert("Debe seleccionar el Motivo de la Visita.");

            return;
          }
          if (visita_motivo.length == 0){
            alert("Debe seleccionar el Motivo de la Visita.");

            return;
          }

          if (vehiculo_particular == 1){
          	if ($("#registro_placa1").val() == null){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
	          if ($("#registro_placa1").val().length == 0){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
	          if ($("#registro_placa2").val() == null){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
	          if ($("#registro_placa2").val().length == 0){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
          }
          else{
          	visita_placa = '';
          }

				// Obtiene total de Visitas
          var table = document.getElementById('tbl_visitas');
					var _rows_visitas = table.rows.length - 1;
					
					if (table.rows.length - 1 == 0){
						alert("Debe ingresar al menos una visita.");

						return;
					}

				// Valida el Contacto
					if (registro_contacto == null){
            alert("Debe seleccionar un Contacto.");

            return;
          }
          if (registro_contacto.length == 0){
            alert("Debe seleccionar un Contacto.");

            return;
          }

	    	// Activando Waiting
	    		$("#btn_sendautorizacion").hide();
	    		$("#btn_cancelautorizacion").show();
	    		$("#registro_contacto").prop('disabled', true);
	    		$("#lbl_autorizacion").hide();
	    		$(".obj_waiting").prop('disabled', true);
					
					f_ReplaceClass("div_waiting", '', 'd-flex');
					$("#div_waiting").show();

	    	// Recorre la tabla de Visitas y obtiene los datos
          var a = 1;
          var arr_visitas = [];
          var arr_visitas_datos = [];

          $('#tbl_visitas tr').each(function () {
          	if (a <= _rows_visitas){
	            var _visita = {
	            	cod_auto: a,
					      dni: $(this).find("td").eq(2).html(),
					      nombres: $(this).find("td").eq(3).html(),
					      imagen: $(this).find('.imagen').attr('src')
					    };

					    var _visita_datos = {
	            	cod_auto: a,
					      dni: $(this).find("td").eq(2).html(),
					      nombres: $(this).find("td").eq(3).html(),
					      tiene_imagen: (($(this).find('.imagen').attr('src').length > 0) ? 1 : 0)
					    };

					    arr_visitas.push(_visita);
					    arr_visitas_datos.push(_visita_datos);
				    }

				    a ++;
          });

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_recepcionvisitas", visita_motivo: visita_motivo, registro_contacto: registro_contacto, registro_observacion: registro_observacion, tiene_vehiculoparticular: vehiculo_particular, visita_placa: visita_placa, arr_visitas_datos: JSON.stringify(arr_visitas_datos) },
            function( data ) {
            	if(data.estado == 1){
            		var id_registro = data.id_registro;

            		// Asignando Id del registro
            			$("#hd_idregistro").val(id_registro);

            		// Enviando Solicitud
            			f_getPeso(1, id_registro);

              	// Grabando Acompañantes
	              	if (arr_visitas.length > 0){
	              		$.post( "apis/backend.php", { accion: "grabar_recepcionvisitas_imagenes", id_registro: id_registro, arr_visitas: JSON.stringify(arr_visitas) },
					            function( data ) {
					            	if(data.estado == 0){
					            		alert("Ocurrió un error al momento de grabar las Imágenes en Segundo Plano.");

					                return;
					            	}

				            	}, "json");
	              	}
            	}
              else{
                alert("Ocurrió un error al momento de grabar los datos de Visita.");

                return;
              }
            }, "json");

	    }

	    function f_CancelAutorizacion(){
	    	// Desactivando Waiting
	    		$("#btn_sendautorizacion").show();
	    		$("#btn_cancelautorizacion").hide();
	    		$("#registro_contacto").prop('disabled', false);
	    		$("#lbl_autorizacion").hide();
	    		$(".obj_waiting").prop('disabled', false);
					
					f_ReplaceClass("div_waiting", 'd-flex', '');
					$("#div_waiting").hide();

				// Cancelando Solicitud
					var id_registro = $("#hd_idregistro").val();

					$.post( "apis/backend.php", { accion: "cancelar_SolicitudVisita", id_visita: id_registro },
            function( data ) {
            	if(data.estado == 1){
            		f_getPesoOff();
            	}
            	else{
            		alert("Ocurrió un error al momento de Cancelar la solicitud.");
            	}

            }, "json");
	    }

	    function f_LlamarContacto(){
	    	// Valida el contacto seleccionado
	    		var _datos_contacto = $("#registro_contacto").val();

		    	if (_datos_contacto.length == 0){
		    		return;
		    	}

	    	// Obtiene el Contacto
	    		var _contacto = _datos_contacto.split('|')[0];

	    	// Obtiene los teléfonos
	    		var _telefonos = _datos_contacto.split('|')[1];
		    	var _telefono1 = _telefonos.split('-')[0];
		    	var _telefono2 = _telefonos.split('-')[1];

		    // Setea la llamada
		    	var _num_llamada = '';

		    	if (_telefono1.trim().length > 0){
		    		_num_llamada = _telefono1.trim();
		    	}
		    	else{
		    		_num_llamada = _telefono2.trim();
		    	}

  			window.location.href = 'tel:' + _num_llamada;
	    }

	    $(document).on('click', '.del_tr', function (event) {
	      event.preventDefault();

	      $(this).closest('tr').remove();

	      // Obtiene total de Acompañantes
		      var table = document.getElementById('tbl_visitas');
	  			var _rows = table.rows.length - 1;

	      // Reinicia los contadores
	      	var x = 1;

	      	$("#tbl_visitas tr").each(function () {
	      		if (x <= _rows){
	          	$(this).find("td").eq(0).html(x);
	      		}

	          x ++;
	        });
	    });

			function f_getPeso(_on, _id_visita){
				if (_on == 1){
					waiting_autorizacion = 1;
				}

				if (waiting_autorizacion == 1){
					$.get( "apis/interfaces.php", { accion: "get_AutorizacionVisita", id_visita: _id_visita }, 
	          function( data ) {
	            if(data.estado == 1){
	            	var is_autorizado = data.is_autorizado;

	            	// Mostrando el resultado
					    		$("#btn_sendautorizacion").hide();
					    		$("#btn_cancelautorizacion").hide();
									
									f_ReplaceClass("div_waiting", 'd-flex', '');
									$("#div_waiting").hide();
									$("#lbl_autorizacion").show();

									$("#lbl_autorizacion").html(((is_autorizado == 1) ? 'Ingreso Autorizado' : 'Ingreso Rechazado'));
									$("#lbl_autorizacion").css('background-color', ((is_autorizado == 1) ? '#7AB42C' : '#FF4541'));

									$("#btn_Cerrar").prop('disabled', false);

	              f_getPesoOff();
	            }

	            setTimeout('f_getPeso(' + waiting_autorizacion + ', ' + _id_visita + ')', 3000);

	          }, "json");
				}
			}

			function f_getPesoOff(){
				waiting_autorizacion = 0;
			}

    	function f_ExportToExcel(){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();

        window.location.href = "export_to_excel/registro_visitas.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin;
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_KeyUpPlaca(){
				var placa1 = $("#registro_placa1").val();

				if (placa1.trim().length == 3){
					document.getElementById("registro_placa2").focus();
				}
			}

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
					$("#wt_documentoimagen").show();
				}
				else{
					$("#wt_documentoimagen").hide();
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

			$("#modal_addrecepcion").on("hidden.bs.modal", function () {
		    f_LoadResultados();
			});
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_GrabarVisita_Confirmar(){
				// Recupera variables
					var visita_motivo = $("#registro_motivo").val();
					visita_motivo = visita_motivo.split('|')[0];
					var registro_contacto = $("#registro_contacto").val();
					registro_contacto = registro_contacto.split('|')[0];
					var registro_observacion = f_CleanInjection($("#registro_observacion").val().trim().toUpperCase());
					var vehiculo_particular = (($("#chk_vehiculoparticular").prop('checked')) ? 1 : 0);
					var visita_placa = f_CleanInjection($("#registro_placa1").val()) + '-' + f_CleanInjection($("#registro_placa2").val());

				// Validando datos
          if (visita_motivo == null){
            alert("Debe seleccionar el Motivo de la Visita.");

            return;
          }
          if (visita_motivo.length == 0){
            alert("Debe seleccionar el Motivo de la Visita.");

            return;
          }

          if (vehiculo_particular == 1){
          	if ($("#registro_placa1").val() == null){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
	          if ($("#registro_placa1").val().length == 0){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
	          if ($("#registro_placa2").val() == null){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
	          if ($("#registro_placa2").val().length == 0){
	            alert("Debe ingresar la Placa del vehículo.");

	            return;
	          }
          }
          else{
          	visita_placa = '';
          }

				// Obtiene total de acompañantes
          var table = document.getElementById('tbl_visitas');
					var _rows_visitas = table.rows.length - 1;
					
					if (table.rows.length - 1 == 0){
						alert("Debe ingresar al menos una visita.");

						return;
					}

					f_LoadingGrabarIngreso(1);

        // Recorre la tabla de Visitas y obtiene los datos
          var a = 1;
          var arr_visitas = [];
          var arr_visitas_datos = [];

          $('#tbl_visitas tr').each(function () {
          	if (a <= _rows_visitas){
	            var _visita = {
	            	cod_auto: a,
					      dni: $(this).find("td").eq(2).html(),
					      nombres: $(this).find("td").eq(3).html(),
					      imagen: $(this).find('.imagen').attr('src')
					    };

					    var _visita_datos = {
	            	cod_auto: a,
					      dni: $(this).find("td").eq(2).html(),
					      nombres: $(this).find("td").eq(3).html(),
					      tiene_imagen: (($(this).find('.imagen').attr('src').length > 0) ? 1 : 0)
					    };

					    arr_visitas.push(_visita);
					    arr_visitas_datos.push(_visita_datos);
				    }

				    a ++;
          });

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_recepcionvisitas", visita_motivo: visita_motivo, registro_contacto: registro_contacto, registro_observacion: registro_observacion, tiene_vehiculoparticular: vehiculo_particular, visita_placa: visita_placa, arr_visitas_datos: JSON.stringify(arr_visitas_datos) },
            function( data ) {
            	if(data.estado == 1){
              	f_LoadResultados();

              	var id_registro = data.id_registro;

              	// Grabando Acompañantes
	              	if (arr_visitas.length > 0){
	              		$.post( "apis/backend.php", { accion: "grabar_recepcionvisitas_imagenes", id_registro: id_registro, arr_visitas: JSON.stringify(arr_visitas) },
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

	    function f_RegistroSalida_Confirmar(){
	    	// Recupera variables
	    		var id_registro = $("#hd_idregistrosalida").val();
					var salida_observacion = f_CleanInjection($("#salida_observacion").val().trim());

				// Validando datos

        // Obtiene la lista de Acompañantes seleccionados
          var a = 1;
          var arr_visitas = '';

          $("#tbl_visitas_salida tr").each(function () {
	      		if ($("#chk_visita_" + a).prop('checked')){
	      			arr_visitas += $("#id_visita_" + a).val() + '|';
	      		}

	          a ++;
	        });

	        if (arr_visitas.length > 0){
	        	arr_visitas = arr_visitas.substring(0, arr_visitas.length - 1);
	        }

				// Grabando Datos
          f_LoadingRegistroSalida(1);

          $.post( "apis/backend.php", { accion: "grabar_salidavisitas", id_registro: id_registro, salida_observacion: salida_observacion, arr_visitas: arr_visitas },
            function( data ) {
              if(data.estado == 1){
              	$("#td_salida_1_" + id_registro).html(data.fechahora_registro + '</br><i>' + data.usuario_registro + '</i>');
              	$("#td_salida_2_" + id_registro).html(salida_observacion.toUpperCase());

              	f_LoadResultados();
              }

              f_LoadingRegistroSalida(0);

              f_cerrarModal('modal_registrosalida');

            }, "json");
	    }

	    function f_RegistroSalida_Visitas(_id_acompanante, _nombres){
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