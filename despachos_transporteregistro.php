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

		<!-- JSColor -->
		<script src="libs/jscolor/jscolor.js"></script>

		<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Gestión de Transporte</title>

		<script type="text/javascript">
			let itemplanta_Selected = 0;
      let idplanta_Selected = 0;

      let itemunidad_Selected = 0;
      let coddespacho_Selected = 0;
      let idunidad_Selected = 0;
      let idmodalidadenvio_Selected = 0;
		</script>

		<style>
			#colorSeleccionado + .jscolor {
        position: absolute;
        z-index: 2; /* Ajusta este valor según sea necesario */
        top: 0; /* Ajusta estos valores según sea necesario */
        left: 0; /* Ajusta estos valores según sea necesario */
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
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Fecha de Despacho</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="row" >
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" >
												</div>
												<br><br>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Estado de Cierre</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
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
								</div>
		           
 								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
 									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Empresa de Transporte</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -7px; padding-left: 10px; padding-right: 10px;">
												<div class="flex-fill">
													<select id="filtro_empresatransporte" class="form-select" data-placeholder="Elija una opción..." style="text-align: left; font-size: 14px;">
														<option selected value="">Elija una opción...</option>

														<?php

														$q_empresatransporte = "SELECT Id,
																													 documento,
																													 razon_social
																											FROM tb_clientes
																										 WHERE cod_clientecondicion = 2
																											 AND estado = 'A'";

										        if ($res_empresatransporte = mysqli_query($enlace, $q_empresatransporte)){
										          if (mysqli_num_rows($res_empresatransporte) > 0) {
										            while($row_empresatransporte = mysqli_fetch_array($res_empresatransporte)){
										              ?>

										              <option value="<?php echo $row_empresatransporte["Id"]; ?>"><?php echo $row_empresatransporte["documento"].' - '.$row_empresatransporte["razon_social"]; ?></option>

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
								</div>

 								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Coordinador de Transporte</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -7px; padding-left: 10px; padding-right: 10px;">
												<div class="flex-fill">
													<select id="filtro_coordinadortransporte" class="form-select" data-placeholder="Elija una opción..." style="text-align: left; font-size: 14px;">
														<option selected value="">Elija una opción...</option>

														<?php

														$q_coordinadortransporte = "SELECT coordinador_nombres
																													FROM transporte
																												 WHERE cEstado_Registro = 'A'";

										        if ($res_coordinadortransporte = mysqli_query($enlace, $q_coordinadortransporte)){
										          if (mysqli_num_rows($res_coordinadortransporte) > 0) {
										            while($row_coordinadortransporte = mysqli_fetch_array($res_coordinadortransporte)){
										              ?>

										              <option value="<?php echo $row_coordinadortransporte["coordinador_nombres"]; ?>"><?php echo $row_coordinadortransporte["coordinador_nombres"]; ?></option>

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
 								</div>

 								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">

											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Códigos Despacho:</h6>
											</div>
											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;" />
											</div>
											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_codigodespacho" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
														<?php

														$q_lotes = "SELECT DISTINCT codigo_despacho
																					FROM despachos_segundotramo_programacion_detalle
																				ORDER BY codigo_despacho DESC";

										        if ($res_lotes = mysqli_query($enlace, $q_lotes)){
										          if (mysqli_num_rows($res_lotes) > 0) {
										            while($row_lotes = mysqli_fetch_array($res_lotes)){
										              ?>

										              <option value="<?php echo $row_lotes["codigo_despacho"]; ?>"><?php echo $row_lotes["codigo_despacho"]; ?></option>

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

										        if ($res_lotes = mysqli_query($enlace, $q_lotes)){
										          if (mysqli_num_rows($res_lotes) > 0) {
										            while($row_lotes = mysqli_fetch_array($res_lotes)){
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

								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadPlantas();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
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
							<div id="div_plantas" class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<h5>Lista de Plantas</h5>

												<div id="wt_plantas" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
													<img src="<?php echo $img_waiting ?>" style="width: 20px;">
													<label style="font-style: italic;"> Cargando datos...</label>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 12px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 10%; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 90%; border-top-right-radius: 15px;">
						        				Planta
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_plantas">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_detalle" class="col-md-10 col-sm-10 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="d-flex">
													<div id="div_ShowListaProgramaciones" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px;" onclick="f_HideListaProgramaciones(1);">
	                          <i class="bi bi-arrow-left" style="font-size: 18px;"></i>
	                        </div>

	                        <div id="div_HideListaProgramaciones" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px; display: none;" onclick="f_HideListaProgramaciones(0);">
	                          <i class="bi bi-arrow-right" style="font-size: 18px;"></i>
	                        </div>

													<h5>Unidades Despachadas: </h5>
													<h5 id="lbl_tituloplanta" style="margin-left: 5px; color: #337ab7;"></h5>

													<div id="wt_programacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 12px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Información Despacho
						        			</th>

						        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Información Unidad
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Empresa Transporte
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Costo Servicio
						        			</th>

						        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Liquidación Flete
						        			</th>
						        		</tr>

						        		<tr style="font-size: 12px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Código
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Modalidad Envío
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Real<br>Despacho
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Llegada<br>A Planta
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Tipo Vehículo
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Placa 1
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Placa 2
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Coordinador
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				RUC
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Razón Social
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				TMH<br>Total
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
						        				Precio<br>x Tonelada<br>(Sin IGV)
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Total<br>Base<br>Imponible
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Acción
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Formato
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_listaunidades">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>

								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-8 col-sm-8 col-xs-12">
												<div class="d-flex">
													<h5>Información de Lotes por Unidad</h5>
													<div id="wt_informacionlotes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 12px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Remitente Despacho
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Código Lote
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				N° Parte
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Código<br>Planta
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				TMH<br>Planta
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
		<div class="modal fade modal-dialog-scrollable" id="modal_CierreLiquidacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_CierreLiquidacionLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="modal_CierreLiquidacionLabel">Confirmación de Liquidación</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-10 col-sm-10 col-xs-12">
								<div class="d-flex" style="padding: 5px; margin-top: -5px;">
									<h6 style="font-size: 14px; margin-top: 8px; min-width: 80px;">
										Material:
									</h6>

									<div class="flex-fill" style="width: 100%; max-width: 100%; min-width: 100%;">
										<select id="lista_materiales" class="form-select" data-placeholder="Elija una opción...">
											<option selected value="">Elija una opción...</option>

											<?php

												$q_datos = "SELECT Id,
																					 descripcion
																			FROM tbconfig_materialesflete
																		ORDER BY descripcion";

												if ($res_datos = mysqli_query($enlace, $q_datos)) {
													if (mysqli_num_rows($res_datos) > 0) {
														while ($row_datos = mysqli_fetch_array($res_datos)) {
															?>

															<option value="<?php echo $row_datos["Id"]; ?>"><?php echo $row_datos["descripcion"]; ?></option>

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
					</div>

					<input id="hd_cierreliquidacion_item" type="hidden">
					<input id="hd_cierreliquidacion_codigodespacho" type="hidden">
					<input id="hd_cierreliquidacion_remitenteruc" type="hidden">
					<input id="hd_cierreliquidacion_placa" type="hidden">
					<input id="hd_cierreliquidacion_idplanta" type="hidden">
					<input id="hd_cierreliquidacion_idmodalidadenvio" type="hidden">
					<input id="hd_cierreliquidacion_isflete" type="hidden">

					<div class="modal-footer" style="margin-top: -10px;">
						<div id="wt_configuracionvehicular" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

						<button type="button" class="btn btn-secondary wt_configuracionvehicular_button" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
						<button type="button" class="btn btn-primary wt_configuracionvehicular_button" style="font-size: 14px;" onclick="f_CierreLiquidacion(0, 0, 0, 0, 0, 0, 0, 1);">Confirmar</button>
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
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| Gestión de Transporte');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadPlantas();
			}
		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			$('#filtro_empresatransporte, #filtro_empresatransporte, #filtro_coordinadortransporte').select2({
		    theme: "bootstrap-5",
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		    placeholder: $( this ).data( 'placeholder' ),
		    allowClear: true
			}).on('select2:open', function() {
				$('body').css('zoom', '100%'); 
			}).on('select2:close', function() {
	    	$('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
			});

			$('#filtro_lote, #filtro_codigodespacho').select2({
		    theme: "bootstrap-5",
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : '100%',
		    placeholder: $( this ).data( 'placeholder' ),
		    allowClear: true,
		    minimumResultsForSearch: -1
			}).on('select2:open', function() {
				$('body').css('zoom', '100%'); 
			}).on('select2:close', function() {
		    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
			});

			$('#lista_materiales').select2({
				theme: "bootstrap-5",
				width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
				placeholder: $(this).data('placeholder'),
				allowClear: true,
				dropdownParent: $('#modal_CierreLiquidacion')
			}).on('select2:open', function() {
				$('body').css('zoom', '100%'); 
			}).on('select2:close', function() {
		    $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
			});

			$('.select2-search__field').css('font-size', '14px');

			$('.select2-selection__rendered').css('font-size', '14px');
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadPlantas(){
        var _html = '';
        var d = 1;

        // Obtiene filtros
          var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_estadocierre = $("#filtro_estadocierre").val();
	        var filtro_codigodespacho = $("#filtro_codigodespacho").val();
	        var filtro_lote = $("#filtro_lote").val();

        // Validando datos

				// Cargando Lista de Racks
	        $("#tbl_plantas").html('');

	        f_LoadingPlantas(1);

	        $.post( "apis/backend.php", { accion: "get_DespachosProgramacion_ListaPlantas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, estado_cierre: filtro_estadocierre, filtro_codigodespacho: filtro_codigodespacho, filtro_lote: filtro_lote }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_plantas").html(data.html);

	            	itemplanta_Selected = 1;
								idplanta_Selected = data.id_planta;

								f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);
	            }

	            f_LoadingPlantas(0);

	          }, "json");
    	}

      function f_LoadItemPlanta(_item, _id_planta, _load_programacion, _item_programacion, _id_programacion){
        var _html = '';

        // Pinta selección
          f_ColorSelected_Planta(_item);

        // Obtiene filtros
          var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_estadocierre = $("#filtro_estadocierre").val();
	        var filtro_codigodespacho = $("#filtro_codigodespacho").val();
	        var filtro_lote = $("#filtro_lote").val();

        // Cargando datos
          f_LoadingProgramacion(1);

          $("#tbl_listaunidades").html(_html);
          $("#tbl_listalotes").html(_html);

          $.post( "apis/backend.php", { accion: "get_GestionTransporte_ListaUnidades", id_planta: _id_planta, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, estado_cierre: filtro_estadocierre, filtro_codigodespacho: filtro_codigodespacho, filtro_lote: filtro_lote }, 
            function( data ) {
              if(data.estado == 1){
                $("#tbl_listaunidades").html(data.html);

	            	itemunidad_Selected = 1;
	            	coddespacho_Selected = data.cod_despacho;
								idunidad_Selected = data.id_unidad;
								idmodalidadenvio_Selected = data.id_modalidadenvio;

								f_LoadItemInformacionLotes(itemunidad_Selected, coddespacho_Selected, idunidad_Selected, idmodalidadenvio_Selected);
              }

              f_LoadingProgramacion(0);

            }, "json");

        itemplanta_Selected = _item;
        idplanta_Selected = _id_planta;
      }

      function f_ColorSelected_Planta(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_plantas tr").each(function () {
          $("#tr_planta_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_planta_" + _item).css('background-color', '#FFF587');

          $("#lbl_tituloplanta").html($("#td_planta_" + _item).html().trim());
      }

    	function f_LoadItemInformacionLotes(_item, _cod_despacho, _id_unidad, _id_modalidadenvio){
    		// Pinta selección
          f_UnidadSelected(_item);

        // Obteniendo filtros
          var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_estadocierre = $("#filtro_estadocierre").val();
	        var filtro_codigodespacho = $("#filtro_codigodespacho").val();
	        var filtro_lote = $("#filtro_lote").val();

        // Setea las variables globales
	    		itemunidad_Selected = _item;
	    		coddespacho_Selected = _cod_despacho;
					idunidad_Selected = _id_unidad;

        // Carga las distribuciones
          f_LoadingInformacionlotes(1);

        	$("#tbl_listalotes").html('');

        	$.post( "apis/backend.php", { accion: "get_GestionTransporte_ListaUnidades_InformacionLotes", cod_despacho: _cod_despacho, id_unidad: _id_unidad, id_modalidadenvio: _id_modalidadenvio, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, estado_cierre: filtro_estadocierre, filtro_codigodespacho: filtro_codigodespacho, filtro_lote: filtro_lote }, 
	          function( data ) {
	            if(data.estado == 1){
	              $("#tbl_listalotes").html(data.html);
	            }

	            f_LoadingInformacionlotes(0);

	          }, "json");
    	}

      function f_UnidadSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
          $(".bg_selected").css('background-color', '#ffffff');

        // Seteando item seleccionado
          $(".bg_selected_" + _item).css('background-color', '#FFF587');

          $("#img_select_" + _item).show();

        // Seteando títulos

      }

      function f_EditTarifa(_item){
      	var tmh_total = $("#td_tmh_" + _item).html();
      	var tarifa = $("#edit_tarifa_" + _item).val();

      	$("#td_totalbase_" + _item).html(f_RedondearDecimales(tmh_total * tarifa, 2));
      }
// -----------------------------------------------------------------
      function f_ColorSelected_Programacion(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_listaunidades tr").each(function () {
          $("#td_prog_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#td_prog_" + _item).css('background-color', '#FFF587');
      }

      function f_AddProgramacion(_modo, _item, _id_programacion){
        // Registrando el modo
          $("#modo_grabarprogramacion").val(_modo);
          $("#id_programacion").val(_id_programacion);
          $("#item_programacion").val(_item);

        // Colocando Títulos
          if (_modo == 'N'){
            $("#modal_adminprogramacionesLabel").html('Nueva Programación');
          }
          else{
            $("#modal_adminprogramacionesLabel").html('Agregar Lote');
          }

        // Seteando filtros
          $("#filtrolotes_lote").val('');
					$("#filtro_proveedorminero").val('');
					$("#filtro_modalidadenvio").val('');
					$("#th_Chk").prop('checked', false);

        // Cargando datos
					f_LoadFiltroModalidadEnvio();
					f_LoadFiltroLotes();

        // Abre modal
        	f_OpenModal('modal_adminprogramaciones');
      };

      function f_LoadFiltroModalidadEnvio(){
      	$("#filtro_modalidadenvio").html('');

      	$.post( "apis/backend.php", { accion: "get_DespachosProgramacion_ListaModalidadEnvio", id_planta: idplanta_Selected },
          function( data ) {
            if(data.estado == 1){
            	$("#filtro_modalidadenvio").html(data.html);
            }

          }, "json");
      }

      function f_LoadFiltroLotes(){
      	var _modo = $("#modo_grabarprogramacion").val();

				var cod_lote = $("#filtrolotes_lote").val();
				var cod_proveedorminero = $("#filtro_proveedorminero").val();
				var cod_modalidadenvio = $("#filtro_modalidadenvio").val();

				// Cargando datos
					f_LoadingLotes(1);

	      	$("#tbl_FiltroLotes").html('');

        	$.post( "apis/backend.php", { accion: "get_LotesProgramadosParaDespacho", id_planta: idplanta_Selected, cod_lote: cod_lote, cod_proveedorminero: cod_proveedorminero, cod_modalidadenvio: cod_modalidadenvio },
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_FiltroLotes").html(data.html);
	            }

	            f_LoadingLotes(0);

	          }, "json");
      }

      function f_AddDistribucion(_modo, _item, _id_distribucion, _id_unidad, _placa, _id_tipounidad){
        // Registrando el modo
          $("#modo_grabardistribucion").val(_modo);
          $("#id_distribucion").val(_id_distribucion);
          $("#item_distribucion").val(_item);

        // Colocando Títulos
          if (_modo == 'N'){
            $("#modal_admindistribucionesLabel").html('Nueva Distribución');
          }
          else{
            $("#modal_admindistribucionesLabel").html('<div class="d-flex">Agregar Lote para: <h5 style="margin-top: 3px; margin-left: 5px; color: #337ab7;">' + _placa + '</h5></div>');
          }

        // Seteando Objetos
          $("#tipo_unidad").prop('disabled', false);
          $("#distribucion_unidad").prop('disabled', false);

          if (_modo == 'N'){
          	$("#th_Chk_Distribucion").prop('checked', false);

          	$("#tipo_unidad").val('');
          	$("#tipo_unidad").trigger('change');

          	$("#distribucion_unidad").val('');
          	$("#distribucion_unidad").trigger('change');

          	$("#unidad_capacidad").val('');
          	$("#lbl_countlotes_Distribucion").html('0');
          	$("#lbl_totaltmh_Distribuido").html('0');
          }
          else{
          	$("#tipo_unidad").val(_id_tipounidad);
          	$("#tipo_unidad").trigger('change');

          	$("#tipo_unidad").prop('disabled', true);
          	$("#distribucion_unidad").prop('disabled', true);

          	// Validando la Unidad
          		_id_unidad_x = _id_unidad.split('|')[0];

          		if (_id_unidad_x.trim().length > 0){
          			$("#distribucion_unidad").val(_id_unidad);
          		}
          		else{
          			$("#distribucion_unidad").val('');
          		}

          		$("#distribucion_unidad").trigger('change');

          	f_SetCapacidad();
          }

        // Cargando datos
					f_LoadFiltroLotes_Distribucion();

        // Abre modal
        	f_OpenModal('modal_admindistribuciones');
      }

      function f_LoadFiltroLotes_Distribucion(){
      	// Cargando datos
					f_LoadingLotes_Distribucion(1);

	      	$("#tbl_FiltroLotes_Distribucion").html('');

        	$.post( "apis/backend.php", { accion: "get_LotesProgramadosParaDespacho_Distribucion", id_programacion: idprog_Selected },
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_FiltroLotes_Distribucion").html(data.html);
	            }

	            f_LoadingLotes_Distribucion(0);

	          }, "json");
      }

    	function f_SetColor(_item, _id_registro, _lote, _color){
    		$("#hd_distribucionlote_id").val(_id_registro);
    		$("#hd_distribucionlote_item").val(_item);

    		// Setea título
    			$("#modal_SetColorLabel").html(_lote);

  			// Setea Color
    			$("#colorSeleccionado").val('');

    		f_OpenModal('modal_SetColor');
    	}

      function f_VerifyDistrbucion(){
      	// Recorre la Grilla de Programaciones y busca lote por lote en la grilla de Distrbuciones
      		var d = 1;
      		var _object = '';
      		var cod_lote = '';

      		while (d < 10000){
      			_object = $("#td_programacionlote_" + d);

      			if (_object.html() == undefined){
      				d = 99999;
      			}
      			else{
      				cod_lote = _object.html().trim();

      				// // Limpiando el Total Distribuído de cada lote
      				// 	$("#td_pesodistribuido_" + d).css('background-color', '');

  						// 	$("#td_pesodistribuido_" + d).html('');

      				// Recorre la grilla de Distribuciones
      					var is_inicio = 1;
      					var l = 1;
      					var _object2 = '';
      					var cod_lote2 = '';
      					var total_distribuido = 0;

      					while (l < 10000){
      						_object2 = $("#td_distribucionlote_" + l);

      						if (_object2.html() == undefined){
			      				l = 99999;
			      			}
			      			else{
			      				cod_lote2 = _object2.html().trim();

			      				if (cod_lote == cod_lote2){
			      					if (is_inicio == 1){
			      						// Limpiando el Total Distribuído de cada lote
					      					$("#td_pesodistribuido_" + d).css('background-color', '');

					  							$("#td_pesodistribuido_" + d).html('');

				  							is_inicio = 0;
			      					}

			      					total_distribuido += (($("#peso_distribuido_" + l).val().length == 0) ? 0 : parseFloat($("#peso_distribuido_" + l).val()));
			      				}
			      			}

      						l ++;
      					}

    					// Valida la distribución
      					if (total_distribuido > 0){
      						var bg_color = '';

      						if (parseFloat($("#td_lotetmh_" + d).html()) == parseFloat(total_distribuido)){
      							bg_color = '#B6F279';
      						}

      						if (parseFloat($("#td_lotetmh_" + d).html()) < parseFloat(total_distribuido)){
      							bg_color = '#FF5F5D';
      						}

      						// Verificando si es Lote AUM
      							if ($("#td_programacionloteaum_" + d).val() == 1){
      								$("#td_lotetmh_" + d).html(f_RedondearDecimales(total_distribuido, 3));

      								bg_color = '#B6F279';
      							}

      						$("#td_pesodistribuido_" + d).css('background-color', bg_color);

    							$("#td_pesodistribuido_" + d).html(f_RedondearDecimales(total_distribuido, 3));
      					}
      			}

      			d ++;
      		}
      }

      function f_GetTotalDistrbucion(_item_unidad){
      	// Obtener la suma de los valores de los inputs con la clase 'td_tmhdistribuido'
          var suma = 0;

          $(".td_tmhdistribuido_" + _item_unidad).each(function() {
              // Convertir el valor del input a un número y sumarlo
              suma += parseFloat($(this).val()) || 0;
          });

      	// Valida si la unidad tiene Capacidad
      		if ($("#td_unidad_capacidad_" + _item_unidad).val().trim().length == 0){
      			$("#td_tmhdistribuido_pendiente_" + _item_unidad).html(f_RedondearDecimales(0 - suma, 3));

      			$("#td_tmhdistribuido_pendiente_" + _item_unidad).css('background-color', '#FF5F5D');
      		}
      		else{
	          // Seteando el TMH Distribuído acumulado
	          	$(".td_distribucionlote_totaltmh_" + _item_unidad).html(f_RedondearDecimales(suma, 3));

	          // Seteando el Saldo de TMH por Distribuir
	          	var bg_color = '';

							if (parseFloat($("#td_unidad_capacidad_" + _item_unidad).val()).toFixed(3) - parseFloat(suma).toFixed(3) == 0){
								bg_color = '#B6F279';
							}

							if (parseFloat($("#td_unidad_capacidad_" + _item_unidad).val()) < parseFloat(suma)){
								bg_color = '#FF5F5D';
							}

							$("#td_tmhdistribuido_pendiente_" + _item_unidad).css('background-color', bg_color);

	          	$("#td_tmhdistribuido_pendiente_" + _item_unidad).html(f_RedondearDecimales(parseFloat($("#td_unidad_capacidad_" + _item_unidad).val()) - suma, 3));
      		}
      }

			function f_CierrePrograma(_id_programacion, _item){
				// Validando datos
					// 1. Validando que tenga al menos una distribución
						if ($("#lbl_SinDistribuciones").html() != undefined){
							alert("Debe registrar al menos una Distribución.");

							return;
						}

					// 2. Verificando si hay unidades sin: Placa, Capacidad, Responsable de Despacho
						var d = 1;
						var _object1 = ''; // Para Placa
						var _object2 = ''; // Para Capacidad
						var _object3 = ''; // Para Responsable de Despacho

						var tiene_placa = 1;
						var arr_unidades = '';
						var arr_capacidad = '';
						var arr_responsable = '';

						$("#tbl_distribucion tr").each(function () {
		      		_object1 = $("#idunidad_" + d);
		      		_object2 = $("#td_unidad_capacidad_" + d);
		      		// _object3 = $("#responsableunidad_" + d);

		      		if (_object1.html() != undefined){
		      			// Arma el Array de Placas
			      			if (_object1.val() == 0){
			      				arr_unidades += 'Unidad ' + d + '|';
			      			}

		      			// Arma el Array de Capacidad
			      			if (_object2.val() <= 0){
			      				arr_capacidad += 'Capacidad de Unidad ' + d + '|';
			      			}

		      			// // Arma el Array de Responsable de Despacho
			      		// 	if (_object3.val() <= 0){
			      		// 		arr_responsable += 'Responsable de Unidad ' + d + '|';
			      		// 	}
		      		}

		          d ++;
		        });

		        // Armando mensaje para Validación de Placas
			        if (arr_unidades.length > 0){
			        	var m = 0;
			        	var _msg = "Se han encontrado Unidades con Placas sin asignar:\n\n";
			        	var arr_unidades = arr_unidades.substring(0, arr_unidades.length - 1).split('|');

			        	while (m < arr_unidades.length){
			        		_msg += '   - ' + arr_unidades[m] + "\n";

			        		m ++;
			        	}

			        	_msg += "\n¿Está seguro de continuar?";

			        	if (!confirm(_msg)){
			        		return;
			        	}
			        }

		        // Armando mensaje para Validación de Capacidad
			        if (arr_capacidad.length > 0){
			        	var m = 0;
			        	var _msg = "Se han encontrado Unidades Sin Capacidad o Capacidad Incorrecta:\n\n";
			        	var arr_capacidad = arr_capacidad.substring(0, arr_capacidad.length - 1).split('|');

			        	while (m < arr_capacidad.length){
			        		_msg += '   - ' + arr_capacidad[m] + "\n";

			        		m ++;
			        	}

			        	_msg += "\nNo podrá continuar.";

			        	alert(_msg);

			        	return;
			        }

		        // // Armando mensaje para Validación de Responsable de Despacho
			      //   if (arr_responsable.length > 0){
			      //   	var m = 0;
			      //   	var _msg = "Se han encontrado Unidades sin Responsable de Despacho asignado:\n\n";
			      //   	var arr_responsable = arr_responsable.substring(0, arr_responsable.length - 1).split('|');

			      //   	while (m < arr_responsable.length){
			      //   		_msg += '   - ' + arr_responsable[m] + "\n";

			      //   		m ++;
			      //   	}

			      //   	_msg += "\nNo podrá continuar.";

			      //   	alert(_msg);

			      //   	return;
			      //   }

					// 3. Verificando si hay Lotes distribuídos sin: TMH, Presentación, Hora de Ingreso a Planta, Responsable Despacho
		        var d = 1;
		        var info_lote = '';
	      		var _object1 = ''; // Para TMH Distribuído
	      		var _object2 = ''; // Para Presentación
	      		var _object3 = ''; // Para Hora de Ingreso a Planta
	      		var _object4 = ''; // Responsable Despacho

	      		var arr_tmhdistribuido = '';
	      		var arr_presentacion = '';
	      		var arr_ingresoplanta = '';
	      		var arr_responsabledespacho = '';

	      		while (d < 10000){
	      			_object1 = $("#peso_distribuido_" + d);
	      			_object2 = $("#tipo_carga_" + d);
	      			_object3 = $("#ingreso_planta_" + d);
	      			_object4 = $("#responsabledespacho_" + d);

	      			if (_object1.html() == undefined){
	      				d = 99999;
	      			}
	      			else{
	      				// Obtiene información del Lote
		      				info_lote = $("#td_distribucionlote_" + d).html().trim();
		      				info_lote += (($("#td_distribucionlote_parte_" + d).html().trim().length == 0) ? '' : ' - ' + $("#td_distribucionlote_parte_" + d).html().trim());

	      				// Arma el Array de TMH Distribuído
			      			if (_object1.val() <= 0){
			      				arr_tmhdistribuido += info_lote + '|';
			      			}

	      				// Arma el Array de Presentación
			      			if (_object2.val() == 0){
			      				arr_presentacion += info_lote + '|';
			      			}

	      				// Arma el Array de Hora de Ingreso a Planta
			      			if (_object3.val() == 0){
			      				arr_ingresoplanta += info_lote + '|';
			      			}

	      				// Arma el Array de Hora de Ingreso a Planta
			      			if (_object4.val() == 0){
			      				arr_responsabledespacho += info_lote + '|';
			      			}
	      			}

			    		d ++;
			    	}

		        // Armando mensaje para Validación de TMH Distribuído
			        if (arr_tmhdistribuido.length > 0){
			        	var m = 0;
			        	var _msg = "Se han encontrado Lotes con TMH sin distribuir o TMH incorrecto:\n\n";
			        	var arr_tmhdistribuido = arr_tmhdistribuido.substring(0, arr_tmhdistribuido.length - 1).split('|');

			        	while (m < arr_tmhdistribuido.length){
			        		_msg += '   - ' + arr_tmhdistribuido[m] + "\n";

			        		m ++;
			        	}

			        	_msg += "\nNo podrá continuar.";

			        	alert(_msg);

			        	return;
			        }

		        // Armando mensaje para Validación de TMH Distribuído
			        if (arr_presentacion.length > 0){
			        	var m = 0;
			        	var _msg = "Se han encontrado Lotes con Presentación sin asignar:\n\n";
			        	var arr_presentacion = arr_presentacion.substring(0, arr_presentacion.length - 1).split('|');

			        	while (m < arr_presentacion.length){
			        		_msg += '   - ' + arr_presentacion[m] + "\n";

			        		m ++;
			        	}

			        	_msg += "\nNo podrá continuar.";

			        	alert(_msg);

			        	return;
			        }

		        // Armando mensaje para Validación de Hora de Ingreso a Planta
			        if (arr_ingresoplanta.length > 0){
			        	var m = 0;
			        	var _msg = "Se han encontrado Lotes con Hora de Ingreso a Planta sin asignar:\n\n";
			        	var arr_ingresoplanta = arr_ingresoplanta.substring(0, arr_ingresoplanta.length - 1).split('|');

			        	while (m < arr_ingresoplanta.length){
			        		_msg += '   - ' + arr_ingresoplanta[m] + "\n";

			        		m ++;
			        	}

			        	_msg += "\nNo podrá continuar.";

			        	alert(_msg);

			        	return;
			        }

		        // Armando mensaje para Validación de Hora de Ingreso a Planta
			        if (arr_responsabledespacho.length > 0){
			        	var m = 0;
			        	var _msg = "Se han encontrado Lotes con Responsable de Despacho sin asignar:\n\n";
			        	var arr_responsabledespacho = arr_responsabledespacho.substring(0, arr_responsabledespacho.length - 1).split('|');

			        	while (m < arr_responsabledespacho.length){
			        		_msg += '   - ' + arr_responsabledespacho[m] + "\n";

			        		m ++;
			        	}

			        	_msg += "\nNo podrá continuar.";

			        	alert(_msg);

			        	return;
			        }

	        // 4. Verificando que los TMH Distribuídos correspondan exactamente al TMH del Primer Tramo
	        	// Setea Clases
						  var class_1 = 'tmh_primertramo_' + _item;
						  var class_2 = 'tmh_distribuidoprograma_' + _item;
						  var class_3 = 'lote_programa_' + _item;

					  // Setea variables de TMH
						  var arr_primertramo = '';
						  var arr_distribuido = '';
						  var arr_lotes = '';

					  // Recorre los td con la Clase 1
						  $('td[class^="' + class_1 + '"]').each(function () {
					    	arr_primertramo += $(this).html().trim() + '|';
						  });

					  // Recorre los td con la Clase 2
						  $('td[class^="' + class_2 + '"]').each(function () {
					    	arr_distribuido += $(this).html().trim() + '|';
						  });

					  // Recorre los td con la Clase 3
						  var x = 1;

						  $('td[class^="' + class_3 + '"]').each(function () {
					    	// arr_lotes += $(this).html().trim() + '|';

					    	arr_lotes += $("#td_programacionlote_" + x).html().trim() + '|';

					    	x ++;
						  });

					  // Recorre los Array y compara las Distribuciones
						  var d = 0;
						  var tmh_primertramo = 0;
						  var tmh_distribuido = 0;
						  var lote_programa = '';

						  arr_primertramo = arr_primertramo.substring(0, arr_primertramo.length - 1);
							arr_distribuido = arr_distribuido.substring(0, arr_distribuido.length - 1);
							arr_lotes = arr_lotes.substring(0, arr_lotes.length - 1);

						  arr_primertramo = arr_primertramo.split('|');
							arr_distribuido = arr_distribuido.split('|');
							arr_lotes = arr_lotes.split('|');

						  while (d < arr_primertramo.length){
						  	tmh_primertramo = arr_primertramo[d];
						  	tmh_distribuido = arr_distribuido[d];
						  	lote_programa = arr_lotes[d];

						  	if (parseFloat(tmh_primertramo) > parseFloat(tmh_distribuido)){
						  		alert("El TMH Distribuído Total del Lote: " + lote_programa + ", no puede ser menor al TMH Primer Tramo.");

						  		d = 99999;

						  		return;
						  	}

						  	if (parseFloat(tmh_primertramo) < parseFloat(tmh_distribuido)){
						  		alert("El TMH Distribuído Total del Lote: " + lote_programa + ", no puede ser mayor al TMH Primer Tramo.");

						  		d = 99999;

						  		return;
						  	}

						  	d ++;
						  }

			  // Abrir pantalla de confirmación
			  	$("#cierre_fechadespacho").val('<?php echo $g_date; ?>');

		  		f_OpenModal('modal_CierrePrograma');
			}

			function f_LoadUnidades(){
				var tipo_vehiculo = $("#tipo_unidad").val();

				if (tipo_vehiculo == ''){
					$("#distribucion_unidad").html('');
					$("#distribucion_unidad2").val('');

					$("#distribucion_unidad2").trigger('change');
					$("#div_placa2").hide();
				}
				else{
					$("#distribucion_unidad").html('');

					$.post( "apis/backend.php", { accion: "get_ListaUnidadesxTipo", tipo_vehiculo: tipo_vehiculo },
	          function( data ) {
	            if(data.estado == 1){
	            	$("#distribucion_unidad").html(data.html);
	            }

	            f_SetCapacidad();

          }, "json");
				}
			}

      function f_PrintCargos(_iddistribucionunidad_md5){
      	var url = 'print_cargosguias.php?x=' + _iddistribucionunidad_md5;

      	window.open(url, '_blank');
      }

      function f_PrintRCI(_iddistribucionunidad_md5){
      	var url = 'print_rci.php?x=' + _iddistribucionunidad_md5;

      	window.open(url, '_blank');
      }

      function f_PrintDistribucionDespachos(_idprogramacion_md5){
      	// Obtener la lista de Códigos de Despacho, solo para Colibrí se abrirá un solo formato
      		if (idplanta_Selected == 3){
      			var url = 'print_programaciondistribucion.php?x=' + _idprogramacion_md5 + '&p=' + idplanta_Selected;

      			window.open(url, '_blank');
      		}
      		else{
      			$.post( "apis/backend.php", { accion: "get_ListaCodigosDespachoxProgramacion", id_programacion: _idprogramacion_md5 },
		          function( data ) {
		            if(data.estado == 1){
		            	$.each( data.res, function( key, val ) {
		                var url = 'print_programaciondistribucion2.php?x=' + val.CODIGO_DESPACHO;

		                window.open(url, '_blank');
		              });
		            }

		            f_SetCapacidad();

	          }, "json");
      		}
      }

      function f_PrintPesosMedidas(_item_unidad, _id_distribucionunidad, _id_distribucionunidad_md5){
      	// Setea variables hidden
      		$("#hd_idprogramacionunidad").val(_id_distribucionunidad);
      		$("#hd_idprogramacionunidadmd5").val(_id_distribucionunidad_md5);

      	// Setea título
      		$("#modal_configuracionvehicularLabel").html(_item_unidad);

      	// Obtiene Configuración Vehicular
      		$.post( "apis/backend.php", { accion: "get_ConfiguracionVehicular", id_distribucionunidad: _id_distribucionunidad },
	          function( data ) {
	            if(data.estado == 1){
	            	$.each( data.res, function( key, val ) {
	                $("#configuracion_vehicular").val(val.ID_CONFIGURACIONVEHICULAR);
	                $("#pesobruto_maximo").val(val.configuracionvehicular_pesobrutomaximo);
	                $("#pesobruto_maximo2").val(val.configuracionvehicular_pesobrutomaximo2);
	              });
	            }

          }, "json");

      	// Abre modal
        	f_OpenModal('modal_configuracionvehicular');
      }

      function f_ConfiguracionVehicular_Selected(){
      	var id_configuracionvehicular = $("#configuracion_vehicular").val().split('|')[0];
      	var pesobruto_maximo = $("#configuracion_vehicular").val().split('|')[1];

      	// Setea Peso Bruto
      		$("#pesobruto_maximo").val(pesobruto_maximo);
      }

      function f_PrintLiquidacionTransporte(_id_cierre_md5, _id_planta, _id_modalidadenvio){
      	if (_id_planta == 3 && (_id_modalidadenvio == 1 || _id_modalidadenvio == 2)){ // Para Colibrí - Venta Directa
      		var url = 'print_liquidaciontransporte_colibri.php?x=' + _id_cierre_md5;
      	}
      	else{ // Para las demás plantas
      		if (_id_modalidadenvio == 3 || _id_modalidadenvio == 4 || _id_modalidadenvio == 5){
      			// Si es Colibrí o Solandra agrega el bloque de Valor Referencial
      				var add_VR = 0;

      				if (_id_planta == 3 || _id_planta == 5 || _id_planta == 15){
      					add_VR = 1;
      				}

      			var url = 'print_liquidaciontransporte_comercializacion.php?x=' + _id_cierre_md5 + '&vr=' + add_VR;
      		}
      	}

    		window.open(url,'_blank',"");
      }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingPlantas(_is_show){
				if (_is_show == 1){
					$("#wt_plantas").show();
				}
				else{
					$("#wt_plantas").hide();
				}
			}

			function f_LoadingProgramacion(_is_show){
				if (_is_show == 1){
					$("#wt_programacion").show();
				}
				else{
					$("#wt_programacion").hide();
				}
			}

			function f_LoadingInformacionlotes(_is_show){
				if (_is_show == 1){
					$("#wt_informacionlotes").show();
				}
				else{
					$("#wt_informacionlotes").hide();
				}
			}
// -------------------------------------------------
			function f_LoadingLotes(_is_show){
				if (_is_show == 1){
					$("#wt_loadinglotes").show();
				}
				else{
					$("#wt_loadinglotes").hide();
				}
			}

			function f_LoadingLotes_Distribucion(_is_show){
				if (_is_show == 1){
					$("#wt_loadinglotesdistribuciones").show();
				}
				else{
					$("#wt_loadinglotesdistribuciones").hide();
				}
			}

			

			function f_LoadingDistribucion(_is_show){
				if (_is_show == 1){
					$("#wt_distribucion").show();
				}
				else{
					$("#wt_distribucion").hide();
				}
			}

      function f_HideListaProgramaciones(_x){
        if (_x == 1){
          $("#div_plantas").hide();
          $("#div_detalle").width('100%');

          f_CerrarDiv('C', 'div_ShowListaProgramaciones');
          f_CerrarDiv('A', 'div_HideListaProgramaciones');
          }
        else{
          $("#div_plantas").show();
          $("#div_detalle").width('');

          f_CerrarDiv('A', 'div_ShowListaProgramaciones');
          f_CerrarDiv('C', 'div_HideListaProgramaciones');
        }
      }

	    function f_SelectChkLotes(){
	    	var is_checked = false;

	    	// Obteniendo valor del checkbox
		    	if ($("#th_Chk").prop('checked')){
		    		is_checked = true;
		    	}

		    // Recorre solo las filas visibles
		    	var d = 1;

		    	$("#tbl_FiltroLotes tr").filter(function() {
		    		$("#chk_lote_" + d).prop('checked', is_checked);

		    		d ++;
		    	});

		    // Cuenta los seleccionados
		    	f_CountSelected();
	    }

	    function f_CountSelected(){
	    	var d = 1;
	    	var _count = 0;
	    	var _total_tmh = 0;
	    	var _total_tms = 0;

	    	$("#tbl_FiltroLotes tr").filter(function() {
	    		if ($("#chk_lote_" + d).prop('checked')){
	    			_total_tmh += parseFloat($(this).find("td:eq(5)").text());
	    			_total_tms += ((isNaN($(this).find("td:eq(6)").text().trim())) ? 0 : parseFloat($(this).find("td:eq(6)").text()));

	    			_count ++;
	    		}

	    		d ++;
	    	});

	    	// Setea el conteo de seleccionados
	    		$("#lbl_countlotes").html(_count);

	    	// Setea el total de Netos
	    		$("#lbl_totaltmh").html(f_RedondearDecimales(_total_tmh, 3));
	    		$("#lbl_totaltms").html(f_RedondearDecimales(_total_tms, 3));
	    }

	    function f_SelectChkLotes_Distribucion(){
	    	var is_checked = false;

	    	// Obteniendo valor del checkbox
		    	if ($("#th_Chk_Distribucion").prop('checked')){
		    		is_checked = true;
		    	}

		    // Recorre solo las filas visibles
		    	var d = 1;

		    	$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
		    		$("#chk_lotedistribucion_" + d).prop('checked', is_checked);

		    		d ++;
		    	});

		    // Cuenta los seleccionados
		    	f_CountSelected_Distribucion();
	    }

	    function f_CountSelected_Distribucion(){
	    	var d = 1;
	    	var _count = 0;
	    	var _total_distribuido = 0;

	    	$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
	    		if ($("#chk_lotedistribucion_" + d).prop('checked')){
	    			_total_distribuido += (($("#tmh_distribuido_" + d).val().length == 0) ? 0 : parseFloat($("#tmh_distribuido_" + d).val()));

	    			_count ++;
	    		}

	    		d ++;
	    	});

	    	// Setea el conteo de seleccionados
	    		$("#lbl_countlotes_Distribucion").html(_count);

	    	// Setea el total de Netos
	    		$("#lbl_totaltmh_Distribuido").html(f_RedondearDecimales(_total_distribuido, 3));
	    }
	    
	    function f_LoadingGrabarProgramacion(_is_show){
				if (_is_show == 1){
					$("#wt_grabarprogramacion").show();

					$(".wt_grabarprogramacion_button").prop('disabled', true);
				}
				else{
					$("#wt_grabarprogramacion").hide();

					$(".wt_grabarprogramacion_button").prop('disabled', false);
				}
			}
	    
	    function f_LoadingGrabarProgramacion_Distribucion(_is_show){
				if (_is_show == 1){
					$("#wt_grabardistribucion").show();

					$(".wt_grabardistribucion_button").prop('disabled', true);
				}
				else{
					$("#wt_grabardistribucion").hide();

					$(".wt_grabardistribucion_button").prop('disabled', false);
				}
			}

			function f_SetCapacidad(){
				var tipo_vehiculo = $("#tipo_unidad").val().split('|')[0];
				var tiene_carreta = (($("#tipo_unidad").val().length == 0) ? 0 : $("#tipo_unidad").val().split('|')[1]);
				var capacidad_unidad = '';

				// Oculta Placa 2
					$("#div_placa2").hide();

				// Determinando si el tipo de unidad tiene carreta
					if (tiene_carreta == 1){
						$("#div_placa2").show();
					}

				// Obteniendo la Capacidad
					if (tiene_carreta == 0){
						if ($("#distribucion_unidad").val() != null){
							if ($("#distribucion_unidad").val().length > 0){
								var capacidad_unidad = $("#distribucion_unidad").val().split('|')[1];

								if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0){
									capacidad_unidad = 'Sin Asignar...';
								}
								else{
									capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
								}
							}
						}
					}
					else{
						if ($("#distribucion_unidad2").val() != null){
							if ($("#distribucion_unidad2").val().length > 0){
								var capacidad_unidad = $("#distribucion_unidad2").val().split('|')[1];

								if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0){
									capacidad_unidad = 'Sin Asignar...';
								}
								else{
									capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
								}
							}
						}
					}

				$("#unidad_capacidad").val(capacidad_unidad);
			}
	    
	    function f_LoadingGrabar_CierrePrograma(_is_show){
				if (_is_show == 1){
					$("#wt_configuracionvehicular").show();

					$(".wt_configuracionvehicular_button").prop('disabled', true);
				}
				else{
					$("#wt_configuracionvehicular").hide();

					$(".wt_configuracionvehicular_button").prop('disabled', false);
				}
			}
	    
	    function f_LoadingGrabar_ConfiguracionVehicular(_is_show){
				if (_is_show == 1){
					$("#wt_configuracionvehicular").show();

					$(".wt_configuracionvehicular_button").prop('disabled', true);
				}
				else{
					$("#wt_configuracionvehicular").hide();

					$(".wt_configuracionvehicular_button").prop('disabled', false);
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_ConfirmarProgramacion(){
				// Recuperando datos hidden
					var modo = $("#modo_grabarprogramacion").val();
					var id_programacion = $("#id_programacion").val();

				// Validando datos
          if ($("#lbl_countlotes").html().trim() == 0){
            alert("Debe seleccionar al menos un Lote.");

            return;
          }

        // Arma Array de Lotes seleccionados
          var l = 1;
          var arr_lotes = '';

          $("#tbl_FiltroLotes tr").each(function () {
	          if ($("#chk_lote_" + l).prop('checked')){
	          	arr_lotes += $(this).find("td:eq(1)").text().trim() + '|';
	          }

	          l ++;
	        });

	        arr_lotes = arr_lotes.substring(0, arr_lotes.length - 1);

        // Grabando Datos
	        f_LoadingGrabarProgramacion(1);

	        if (modo == 'N'){
	        	$.post( "apis/backend.php", { accion: "confirmar_ProgramacionLote", id_planta: idplanta_Selected, arr_lotes: arr_lotes },
	            function( data ) {
	              if(data.estado == 1){
	              	f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);

	              	f_cerrarModal('modal_adminprogramaciones');
	              }
	              else{
	                alert("Ocurrió un error al momento de agregar el Lote.\nCódigo de Error N° " + data.estado + ".");
	              }

	              f_LoadingGrabarProgramacion(0);

	            }, "json");
	        }
	        else{
	        	$.post( "apis/backend.php", { accion: "confirmar_ProgramacionLote_AddLote", id_planta: idplanta_Selected, id_programacion: id_programacion, arr_lotes: arr_lotes, is_loteaum: 0 },
	            function( data ) {
	              if(data.estado == 1){
	              	f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected, 1, $("#item_programacion").val(), $("#id_programacion").val());

	              	f_cerrarModal('modal_adminprogramaciones');
	              }
	              else{
	                alert("Ocurrió un error al momento de grabar la Programación.");
	              }

	              f_LoadingGrabarProgramacion(0);

	            }, "json");
	        }
			}

      function f_EliminarProgramacion(_item, _id_programacion, _fechahora, _usuario){
      	if (!confirm("¿Está seguro de Eliminar la Programación seleccionada?\n\n   - Fecha Hora Creación: " + _fechahora + "\n   - Usuario Creación: " + _usuario + "\n\nSi continua se eliminarán los datos permanentemente incluyendo todos los Lote y sus Distribuciones si es que las tuviera.\n\n¿Está seguro de continuar?")){
      		return;
      	}

      	// Eliminando Datos
          $.post( "apis/backend.php", { accion: "eliminar_Programacion", id_programacion: _id_programacion },
            function( data ) {
              if(data.estado == 1){
              	f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);
              }
              else{
              	if(data.estado == 0){
              		alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
              	}

              	if(data.estado == 2){
              		alert("Ocurrió un error al momento de eliminar el Lote programado.");
              	}

              	if(data.estado == 3){
              		alert("Ocurrió un error al momento de grabar el Log del Código de Despacho.");
              	}

              	if(data.estado == 4){
              		alert("Ocurrió un error al momento de actualizar los correlativos de los Códigos de Despacho.");
              	}
              }

            }, "json");
			}

      function f_EliminarProgramacion_Lote(_item, _id_programacionlote, _cod_lote){
      	if (!confirm("¿Está seguro de Eliminar el Lote seleccionado: " + _cod_lote + "?\n\nSi continua se eliminarán los datos permanentemente incluyendo las Distribuciones si es que las tuviera.\n\n¿Está seguro de continuar?")){
      		return;
      	}

      	// Eliminando Datos
          $.post( "apis/backend.php", { accion: "eliminar_ProgramacionLote", id_programacionlote: _id_programacionlote },
            function( data ) {
              if(data.estado == 1){
              	f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected);
              }
              else{
              	if(data.estado == 0){
              		alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
              	}

              	if(data.estado == 2){
              		alert("Ocurrió un error al momento de eliminar el Lote programado.");
              	}

              	if(data.estado == 3){
              		alert("Ocurrió un error al momento de grabar el Log del Código de Despacho.");
              	}

              	if(data.estado == 4){
              		alert("Ocurrió un error al momento de actualizar los correlativos de los Códigos de Despacho.");
              	}

              	if(data.estado == 5){
              		alert("Ocurrió un error al momento de eliminar el Lote de la tabla maestra.");
              	}

              	if(data.estado == 6){
              		alert("Ocurrió un error al momento de eliminar el Lote de la tabla de Validación de Datos del Primer Tramo.");
              	}

              	if(data.estado == 7){
              		alert("Ocurrió un error al momento de eliminar la Distribución del Lote seleccionado.");
              	}
              }

            }, "json");
			}

			function f_ConfirmarDistribucion(){
				// Recuperando datos
					modo = $("#modo_grabardistribucion").val();

					var tipo_unidad = $("#tipo_unidad").val();

					var id_unidad = $("#distribucion_unidad").val();

					if (id_unidad == null){
						id_unidad = '';
					}
					else{
						if (id_unidad.length > 0){
							id_unidad = id_unidad.split('|')[0];
						}
					}

					var id_unidad2 = $("#distribucion_unidad2").val();

					if (id_unidad2 == null){
						id_unidad2 = '';
					}
					else{
						if (id_unidad2.length > 0){
							id_unidad2 = id_unidad2.split('|')[0];
						}
					}

					var capacidad = $("#unidad_capacidad").val();

					var total_distribuido = parseFloat($("#lbl_totaltmh_Distribuido").html().trim());
					total_distribuido = f_RedondearDecimales(total_distribuido, 3);

				// Validando datos
					if (tipo_unidad == null){
            alert("Debe la seleccionar el Tipo de Vehículo.");

            return;
          }
          if (tipo_unidad.length == 0){
            alert("Debe la seleccionar el Tipo de Vehículo.");

            return;
          }

					// if (id_unidad == null){
          //   alert("Debe la seleccionar la Unidad.");

          //   return;
          // }
          // if (id_unidad.length == 0){
          //   alert("Debe la seleccionar la Unidad.");

          //   return;
          // }

					// if (isNaN(capacidad)){
					// 	alert("La Unidad seleccionada no tiene una Capacidad asignada.");

					// 	return;
					// }
					// else{
					// 	capacidad = f_RedondearDecimales(capacidad, 3);
					// }

					if (capacidad == null){
          	capacidad = 0;
          }
          if (capacidad.length == 0){
          	capacidad = 0;
          }

					if (isNaN(capacidad)){
						capacidad = 0;
					}
					else{
						capacidad = f_RedondearDecimales(capacidad, 3);
					}

          if ($("#lbl_countlotes_Distribucion").html().trim() == 0){
            alert("Debe seleccionar al menos un Lote.");

            return;
          }

          // if (parseFloat(total_distribuido) > parseFloat(capacidad)){
          // 	alert("El Total de Toneladas Distribuídas de los lotes seleccionados no puede ser mayor a la Capacidad de la Unidad.\n\n   - Capacidad Unidad: " + capacidad + "\n   - Total Distribuídio: " + total_distribuido);

          // 	return;
          // }

          // if (parseFloat(total_distribuido) > parseFloat(capacidad)){
          // 	if (!confirm("El Total de Toneladas por Distribuir es mayor a la Capacidad de la Unidad.\n\n   - Capacidad Unidad: " + capacidad + "\n   - Total por Distribuir: " + total_distribuido + "\n\n¿Está seguro de continuar?")){
          // 		return;
          // 	}
          // }

        // Recorriendo la grilla y verificando que las distribuciones sean correctas
          var d = 1;
          var _object = '';
          var cod_lote = '';
          var td_pordistribuir = 0;
          var td_distribuido = 0;
          var td_loteaum = 0;
          var is_completo = 0;
          var is_complemento = 0;
          var _script = '';
          var continuar = 1;

        	$("#tbl_FiltroLotes_Distribucion tr").each(function () {
        		_object = $("#chk_lotedistribucion_" + d);

        		if (_object.html() != undefined){
        			if (_object.prop('checked')){
        				cod_lote = $("#lote_distribucion_" + d).html().trim();
			        	td_pordistribuir = parseFloat($("#por_distribuir_" + d).html().trim());
			        	td_distribuido = (($("#tmh_distribuido_" + d).val().length == 0) ? 0 : parseFloat($("#tmh_distribuido_" + d).val()));
			        	td_loteaum = $("#td_loteaum_" + d).val();
			        	is_complemento = (($("#chk_lotecomplemento_" + d).prop('checked')) ? 1 : 0);
			        	is_complemento_de = 'NULL';

			        	// Si es Complemento
			        		if (is_complemento == 1){
			        			is_complemento_de = $("#sel_complemento_" + d).val();
			        		}

			          // Validando Distribución
			          	if (td_distribuido <= 0){
			          		alert("La Distribución ingresada para el Lote: " + cod_lote + " no es correcta.");

			          		d = 1000;

			          		continuar = 0;

			          		return;
			          	}

			          	if ((td_loteaum == 0) && (td_pordistribuir < td_distribuido)){
			          		alert("La Distribución ingresada para el Lote: " + cod_lote + " no puede ser mayor a las Toneladas por Distribuir.");

			          		d = 1000;

			          		continuar = 0;

			          		return;
			          	}

			          // Comprobando si es una distribución completa
			        		is_completo = ((td_pordistribuir == td_distribuido) ? 1 : 0);

		          	// Armando la cadena de datos a enviar para cada lote
			        		_script += cod_lote + ';' + td_distribuido + ';' + is_completo + ';' + td_loteaum + ';' + is_complemento + ';' + is_complemento_de + '|';
							}

		          d ++;
        		}
	        });

        // Seteando script de datos
        	if (continuar == 1){
						_script = _script.substring(0, _script.length - 1);

						// Grabando Datos
			        // f_LoadingGrabarProgramacion_Distribucion(1);

			        if (modo == 'N'){
			        	$.post( "apis/backend.php", { accion: "confirmar_DistribucionLotes", id_programacion: idprog_Selected, tipo_unidad: tipo_unidad, id_unidad: id_unidad, id_unidad2: id_unidad2, capacidad: capacidad, script_x: _script },
			            function( data ) {
			              if(data.estado == 1){
			              	f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);

			              	f_cerrarModal('modal_admindistribuciones');
			              }
			              else{
			                alert("Ocurrió un error al momento de registrar la Distribución.\nCódigo de Error N° " + data.estado + ".");
			              }

			              f_LoadingGrabarProgramacion_Distribucion(0);

			            }, "json");
			        }
			        else{
			        	var id_distribucionunidad = $("#id_distribucion").val();

			        	$.post( "apis/backend.php", { accion: "confirmar_DistribucionLotes_AddLote", id_distribucionunidad: id_distribucionunidad, script_x: _script },
			            function( data ) {
			              if(data.estado == 1){
			              	f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);

			              	f_cerrarModal('modal_admindistribuciones');
			              }
			              else{
			                alert("Ocurrió un error al momento de registrar la Distribución.\nCódigo de Error N° " + data.estado + ".");
			              }

			              f_LoadingGrabarProgramacion_Distribucion(0);

			            }, "json");
			        }
        	}
			}

    	function f_Edit(_id_object, _item, _id_registro, _item_unidad){
    		var _valor = '';

    		// Obtiene datos
    			if (_id_object == 1){
    				_valor = $("#responsabledespacho_" + _item).val();
    			}

    			if (_id_object == 2){
    				_valor = $("#peso_distribuido_" + _item).val();
    			}

    			if (_id_object == 3){
    				_valor = $("#tipo_carga_" + _item).val();
    			}

    			if (_id_object == 4){
    				_valor = $("#idunidad_" + _item).val();
    			}

    			if (_id_object == 5){
    				_valor = $("#td_unidad_capacidad_" + _item).val();
    			}

    			if (_id_object == 6){
    				_valor = $("#ingreso_planta_" + _item).val();
    			}

    			if (_id_object == 7){
    				_valor = $("#fecha_ingresoplanta_" + _item).val();
    			}

    			if (_id_object == 8){
    				_valor = $("#id_tipounidad_" + _item).val();

    				// Muestra u oculta la Placa 2
    					if (_valor.split('|')[1] == 1){
    						$("#td_idunidad2_" + _item).show();
    					}
    					else{
    						$("#td_idunidad2_" + _item).hide();

    						$("#idunidad2_" + _item).val('');
    						$("#idunidad2_" + _item).trigger('change');
    					}
    			}

    			if (_id_object == 9){
    				_valor = $("#idunidad2_" + _item).val();
    			}

    			if (_id_object == 10){
    				_valor = $("#td_proveedorminero_" + _item).val();
    			}

    		$.post( "apis/backend.php", { accion: "grabar_EditDistribucionLote", id_object: _id_object, id_registro: _id_registro, valor: _valor },
          function( data ) {
            if(data.estado == 1){
            	if (_id_object == 2){
            		f_VerifyDistrbucion();
            		f_GetTotalDistrbucion(_item_unidad);

            		f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);
            	}

            	if (_id_object == 4){
            		// Verifica si tiene Carreta
            			var tiene_carreta = $("#id_tipounidad_" + _item).val().split('|')[1];

            			if (tiene_carreta == 0){
            				var _capacidad = _valor.split('|')[1];

            				$("#td_unidad_capacidad_" + _item).val(f_RedondearDecimales(_capacidad / 1000, 3));
            			}

            		f_GetTotalDistrbucion(_item_unidad);
            	}

            	if (_id_object == 5){
            		f_GetTotalDistrbucion(_item_unidad);
            	}

            	if (_id_object == 9){
        				var _capacidad = _valor.split('|')[1];

          			$("#td_unidad_capacidad_" + _item).val(f_RedondearDecimales(_capacidad / 1000, 3));

            		f_GetTotalDistrbucion(_item_unidad);
            	}
            }
            else{
              alert("Ocurrió un error al momento de grabar los datos.");
            }

          }, "json");
    	}

    	function f_SetColor_Grabar(){
    		// Obteniendo valores
    			var _id_registro = $("#hd_distribucionlote_id").val();
    			var _item = $("#hd_distribucionlote_item").val();
    			var _cod_lote = $("#td_distribucionlote_" + _item).html().trim();
    			var _color = $("#colorSeleccionado").val();

    		$.post( "apis/backend.php", { accion: "grabar_DistribucionLote_SetColor", id_registro: _id_registro, color: _color, cod_lote: _cod_lote },
          function( data ) {
            if(data.estado == 1){
            	f_cerrarModal('modal_SetColor');

            	// Setea objeto
            		$(".td_lote_" + _cod_lote).css('background-color', _color);
            }
            else{
              alert("Ocurrió un error al momento de grabar los datos.");
            }

          }, "json");
    	}

      function f_EliminarDistribucion(_item, _id_distribucion, _placa){
      	if (!confirm("¿Está seguro de Eliminar la Unidad seleccionada?\n\n   - Unidad: " + _item + "\n   - Placa: " + _placa + "\n\nSi continua se eliminarán los datos permanentemente incluyendo sus Distribuciones.\n\n¿Está seguro de continuar?")){
      		return;
      	}

      	// Eliminando Datos
          $.post( "apis/backend.php", { accion: "eliminar_Distribucion", id_distribucion: _id_distribucion },
            function( data ) {
              if(data.estado == 1){
              	f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);
              }
              else{
              	if(data.estado == 0){
              		alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
              	}

              	if(data.estado == 2){
              		alert("Ocurrió un error al momento de eliminar el Lote programado.");
              	}

              	if(data.estado == 3){
              		alert("Ocurrió un error al momento de grabar el Log del Código de Despacho.");
              	}

              	if(data.estado == 4){
              		alert("Ocurrió un error al momento de actualizar los correlativos de los Códigos de Despacho.");
              	}
              }

            }, "json");
			}

      function f_EliminarDistribucion_Lote(_id_distribucionlote, _cod_lote, _num_parte, _placa, _id_distribucionunidad){
      	// Setea el N° de Parte
      		var num_parte = '';

      		if (_num_parte.length > 0){
      			num_parte = "\n   - N° Parte: PARTE " + _num_parte;
      		}
      	if (!confirm("¿Está seguro de Eliminar el Lote seleccionado?\n\n   - Lote: " + _cod_lote + num_parte + "\n   - Unidad: " + _placa + "\n\nSi continua se eliminarán los datos permanentemente.\n¿Está seguro de continuar?")){
      		return;
      	}

      	// Eliminando Datos
          $.post( "apis/backend.php", { accion: "eliminar_DistribucionLote", id_distribucionlote: _id_distribucionlote, id_distribucionunidad: _id_distribucionunidad },
            function( data ) {
              if(data.estado == 1){
              	f_LoadItemProgramacion(itemprog_Selected, idprog_Selected);
              }
              else{
              	if(data.estado == 0){
              		alert("Ocurrió un error al momento de grabar el Log del Lote eliminado.");
              	}

              	if(data.estado == 2){
              		alert("Ocurrió un error al momento de eliminar el Lote distribuído.");
              	}

              	if(data.estado == 3){
              		alert("Ocurrió un error al momento de actualizar los N° de Partes siguientes.");
              	}

              	if(data.estado == 4){
              		alert("Ocurrió un error al momento de actualizar el N° de Parte que quedó solo.");
              	}
              }

            }, "json");
			}

			function f_GrabarCierrePrograma(){
				var fecha_estimadadespacho = $("#cierre_fechadespacho").val();

				// Validando datos
					if (fecha_estimadadespacho == null){
            alert("Debe ingresar la Fecha Estimada de Despacho.");

            return;
          }
          if (fecha_estimadadespacho.length == 0){
            alert("Debe ingresar la Fecha Estimada de Despacho.");

            return;
          }

        // Grabar Cierre
          f_LoadingGrabar_CierrePrograma(1);

          $.post( "apis/backend.php", { accion: "cierre_SegundoTramo_ProgramaDistribucion", id_programacion: idprog_Selected, fecha_estimadadespacho: fecha_estimadadespacho },
	          function( data ) {
	            if(data.estado == 1){
	            	// Seteando variables html
	            		var _html1 = '<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_Reabrir(' + idprog_Selected + ', ' + itemprog_Selected + ')">';
									_html1 += '			<u> Reabrir </u>';
									_html1 += '		</label>';

	            	// Setea objetos
	            		$("#td_cierreprograma_1_" + itemprog_Selected).html(_html1);
	            		$("#td_cierreprograma_2_" + itemprog_Selected).html(fecha_estimadadespacho);
	            		$("#td_cierreprograma_3_" + itemprog_Selected).html(data.fechahora_registro);
	            		$("#td_cierreprograma_4_" + itemprog_Selected).html(data.usuario_registro);

								// Setea objetos de Cierre
									$(".is_programacerrado_" + itemprog_Selected).hide();
									$(".is_programacerrado_object_" + itemprog_Selected).prop('disabled', true);

            		f_cerrarModal('modal_CierrePrograma');
	            }
	            else{
	              alert("Ocurrió un error al momento de grabar el Cierre del Programa.");
	            }

	            f_LoadingGrabar_CierrePrograma(0);

	          }, "json");
			}

			function f_AddLoteAUM(_id_programacion, _item_programacion){
				// Validando creación
					if (!confirm("¿Está seguro de crear un Lote AUM para la Programación seleccionada?")){
						return
					}

				// Creando Lote AUM
					$.post( "apis/backend.php", { accion: "crear_NuevoLoteAUM", id_programacion: _id_programacion, id_destino: idplanta_Selected },
	          function( data ) {
	            if(data.estado == 1){
	            	// Agregar el Lote AUM a la programación
		            	$.post( "apis/backend.php", { accion: "confirmar_ProgramacionLote_AddLote", id_planta: idplanta_Selected, id_programacion: _id_programacion, arr_lotes: data.cod_lote, is_loteaum: 1 },
				            function( data2 ) {
				              if(data2.estado == 1){
				              	f_LoadItemPlanta(itemplanta_Selected, idplanta_Selected, 1, _item_programacion, _id_programacion);
				              }
				              else{
				                alert("Ocurrió un error al momento de agregar el Lote AUM a la programación.");
				              }

				            }, "json");
	            }
	            else{
	              alert("Ocurrió un error al momento de crear el Lote AUM.");
	            }

	          }, "json");
			}

			function f_PrintPesosMedidas_Informe(){
				var id_distribucionunidad = $("#hd_idprogramacionunidad").val();
				var id_distribucionunidadmd5 = $("#hd_idprogramacionunidadmd5").val();
				var id_configuracionvehicular = $("#configuracion_vehicular").val();
				var pesobruto_maximo = $("#pesobruto_maximo").val();
				var pesobruto_maximo2 = $("#pesobruto_maximo2").val();

				// Validando datos
					if (id_configuracionvehicular == null){
            alert("Debe seleccionar la Configuración Vechiular.");

            return;
          }
          if (id_configuracionvehicular.length == 0){
            alert("Debe seleccionar la Configuración Vechiular.");

            return;
          }

          if (pesobruto_maximo == null){
            alert("No ha asignado el Peso Bruto Máximo a la Configuración Vehicular seleccionada.");

            return;
          }
          if (pesobruto_maximo.length == 0){
            alert("No ha asignado el Peso Bruto Máximo a la Configuración Vehicular seleccionada.");

            return;
          }

          if (pesobruto_maximo2 == null){
            alert("Debe ingresar el Peso Bruto Máximo (Con Bonificación).");

            return;
          }
          if (pesobruto_maximo2.length == 0){
            alert("Debe ingresar el Peso Bruto Máximo (Con Bonificación).");

            return;
          }

        // Grabar Cierre
          f_LoadingGrabar_ConfiguracionVehicular(1);

          $.post( "apis/backend.php", { accion: "grabar_ConfiguracionVehicular_PesosyMedidas", id_distribucionunidad: id_distribucionunidad, id_configuracionvehicular: id_configuracionvehicular, pesobruto_maximo: pesobruto_maximo, pesobruto_maximo2: pesobruto_maximo2 },
	          function( data ) {
	            if(data.estado == 1){
	            	$.each( data.res, function( key, val ) {
	                // Imprime el Informe por Remitente
	            			var url = 'print_pesosmedidas.php?x=' + id_distribucionunidadmd5 + '&r=' + val.ruc + '&z=' + val.razon_social;

	      						window.open(url, '_blank');
	              });

	      				// Cerrar Modal
            			f_cerrarModal('modal_configuracionvehicular');
	            }
	            else{
	              alert("Ocurrió un error al momento de grabar la Configuración Vehicular.");
	            }

	            f_LoadingGrabar_ConfiguracionVehicular(0);

	          }, "json");

			}

      function f_CierreLiquidacion(_item, _codigo_despacho, _guias_remitenteruc, _placa, _id_planta, _id_modalidadenvio, _is_flete, _is_confirmacionmodal){
      	var id_material = 0;

      	// Solo para Colibrí Venta Directa
      		if ((_id_planta == 3 && (_id_modalidadenvio == 1 || _id_modalidadenvio == 2)) || _is_confirmacionmodal == 1){
						if (_is_confirmacionmodal != 1){
							// Seteando variables hidden
	    					$("#hd_cierreliquidacion_item").val(_item);
								$("#hd_cierreliquidacion_codigodespacho").val(_codigo_despacho);
								$("#hd_cierreliquidacion_remitenteruc").val(_guias_remitenteruc);
								$("#hd_cierreliquidacion_placa").val(_placa);
								$("#hd_cierreliquidacion_idplanta").val(_id_planta);
								$("#hd_cierreliquidacion_idmodalidadenvio").val(_id_modalidadenvio);
								$("#hd_cierreliquidacion_isflete").val(_is_flete);

							$("#lista_materiales").val('');
							$("#lista_materiales").trigger('change');

							f_OpenModal('modal_CierreLiquidacion');

							return;
						}
						else{
							id_material = $("#lista_materiales").val();

							if (id_material == null){
		            alert("Debe seleccionar el Material.");

		            return;
		          }
		          if (id_material.length == 0){
		            alert("Debe seleccionar el Material.");

		            return;
		          }

							_item = $("#hd_cierreliquidacion_item").val();
							_codigo_despacho = $("#hd_cierreliquidacion_codigodespacho").val();
							_guias_remitenteruc = $("#hd_cierreliquidacion_remitenteruc").val();
							_placa = $("#hd_cierreliquidacion_placa").val();
							_id_planta = $("#hd_cierreliquidacion_idplanta").val();
							_id_modalidadenvio = $("#hd_cierreliquidacion_idmodalidadenvio").val();
							_is_flete = $("#hd_cierreliquidacion_isflete").val();
						}
      		}

      	if (_is_confirmacionmodal != 1){
	      	if (_is_flete == 0){
	      		if (!confirm("¿Está seguro de Generar la Liquidación?")){
	      			return;
	      		}
	      	}
	      	else{
	      		if (!confirm("¿Está seguro de Confirmar el Flete?")){
	      			return;
	      		}
	      	}
	      }

      	// Obtiene la tarifa para Lomas
      		var tarifa = '';

      		if (_id_planta == 4){
      			tarifa = $("#edit_tarifa_" + _item).val();

      			if (tarifa == null){
              alert("Debe ingresar la Tarifa.");

              return;
            }
            if (tarifa.length == 0){
              alert("Debe ingresar la Tarifa.");

              return;
            }
            if (tarifa <= 0){
              alert("La Tarifa ingresada no puede ser CERO.");

              return;
            }
      		}

     		// Creando cierre
	  			$.post( "apis/backend.php", { accion: "cierre_LiquidacionTransporte", codigo_despacho: _codigo_despacho, ruc_remitente: _guias_remitenteruc, placa: _placa, tarifa: tarifa, id_material: id_material },
	          function( data ) {
	            if(data.estado == 1){
	            	// Seteando campos
	            		$("#td_cierre_1_" + _item).html('<label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_Reabrir(' + _item + ', ' + data.id_cierre + ", '" + _codigo_despacho + "', '" + _guias_remitenteruc + "', '" + _placa + "', " + _id_planta + ', ' + _id_modalidadenvio + ', ' + _is_flete + ')"><u> Revertir </u></label>');

	            		$("#td_cierre_2_" + _item).html(data.fechahora_registro + '<br>' + data.usuario_registro);

	            		if (_is_flete == 0){
	            			$("#td_cierre_3_" + _item).html('<img src="images/informe_ensayos.png" class="rounded" style="width: 30px; cursor: pointer;" onclick="f_PrintLiquidacionTransporte(' + "'" + data.id_cierre_md5 + "', " + _id_planta + ', ' + _id_modalidadenvio + ')">');
	            		}
	            		else{
	            			$("#td_cierre_3_" + _item).html('');
	            		}

	            	// Revierte el campo de Tarifa para Lomas
	            		if (_id_planta == 4){
	            			$("#edit_tarifa_" + _item).prop('disabled', true);
	            		}

	            	f_cerrarModal('modal_CierreLiquidacion');
	            }
	            else{
	              alert("Ocurrió un error al momento de realizar el Cierre de Análisis.");
	            }

	            f_LoadingCierre(0);

	          }, "json");
  		}

  		function f_Reabrir(_item, _id_cierre, _codigo_despacho, _guias_remitenteruc, _placa, _id_planta, _id_modalidadenvio, _is_flete){
  			// Validando cierre
		  		if (!confirm("¿Está seguro de Revertir la Liquidación seleccionada?")){
		  			return;
		  		}

		  	// Reabrir Cierre
		  		$.post( "apis/backend.php", { accion: "reabrir_LiquidacionTransporte", id_cierre: _id_cierre },
	          function( data ) {
	            if(data.estado == 1){
	            	if (_is_flete == 0){
	            		$("#td_cierre_1_" + _item).html('<button class="btn btn-warning" type="button" onclick="f_CierreLiquidacion(' + _item +", '" + _codigo_despacho + "', '" + _guias_remitenteruc + "', '" + _placa + "', " + _id_planta + ', ' + _id_modalidadenvio + ', ' + _is_flete + ');" style="width: 100%; color: #ffffff; font-size: 12px; background-color: #cfaa41; padding: 5px;"><b>Generar Liquidación</b></button>');
	            	}
	            	else{
	            		$("#td_cierre_1_" + _item).html('<button class="btn btn-warning" type="button" onclick="f_CierreLiquidacion(' + _item +", '" + _codigo_despacho + "', '" + _guias_remitenteruc + "', '" + _placa + "', " + _id_planta + ', ' + _id_modalidadenvio + ', ' + _is_flete + ');" style="width: 100%; color: #ffffff; font-size: 12px; background-color: #cfaa41; padding: 5px;"><b>Confirmar Flete</b></button>');
	            	}

            		$("#td_cierre_2_" + _item).html('');

            		$("#td_cierre_3_" + _item).html('');

	            	// Bloquea el campo de Tarifa para Lomas
	            		if (_id_planta == 4){
	            			$("#edit_tarifa_" + _item).prop('disabled', false);
	            		}
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
		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>