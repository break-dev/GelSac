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

		<title><?php echo $nom_app; ?> | Resumen de Transporte</title>

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
										<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
				              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
			            	</button>
			            </div>

									<img id="img_evidencia" style="width: 250px; display: none;">
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
							<div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="d-flex">
													<h5>Resumen de Transporte </h5>

													<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Planta
						        			</th>

						        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Información Despacho
						        			</th>

						        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Información Unidad
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Empresa Transporte
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Liquidación Flete
						        			</th>

						        			<th colspan="5" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Información Factura
						        			</th>

						        			<th colspan="7" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Información Pago
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Estado Pago
						        			</th>

						        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Información Detracción
						        			</th>
						        		</tr>

						        		<tr style="font-size: 12px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Código
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Modalidad Envío
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Real<br>Despacho
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Llegada<br>A Planta
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Tipo Vehículo
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Placa 1
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Placa 2
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Coordinador
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				RUC
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Razón Social
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Formato
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #37393c; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Liquidación<br>Firmada
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				N° Comprobante
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Documento
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Emisión
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Vencimiento
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Tipo Pago
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; min-width: 70px;">
						        				TMH<br>Total
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Precio<br>Unitario
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; min-width: 80px;">
						        				Sub<br>Total
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				IGV (18%)
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Monto<br>Total
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Monto<br>Referencial
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Monto<br>A Pagar
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Estado
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Días transcurridos<br>Sin Pago
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Detracción
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Estado
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #D9D2D0; border-color: #ffffff; vertical-align: middle;">
						        				Días transcurridos<br>Sin Pago
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_resumen">
						        		
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

		<!-- Ventanas modales -->
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

		<div class="modal fade" id="modal_registrarcomprobante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_registrarcomprobanteLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_registrarcomprobanteLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
								N° Comprobante:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-12">
								<input id="comprobante_numero" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Fecha Emisión:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-12">
								<input id="comprobante_fechaemision" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;" onchange="f_CopyDate();">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Fecha Vencimiento:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-12">
								<input id="comprobante_fechavencimiento" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

		      	<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Tipo Pago:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="comprobante_tipopago" class="form-select" style="text-align: left;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_datos = "SELECT Id,
                    								 descripcion
		                            FROM tbconfig_tipopagocomprobante
		                           WHERE estado = 'A'";

					        if ($res_datos = mysqli_query($enlace, $q_datos)){
					          if (mysqli_num_rows($res_datos) > 0) {
					            while($row_datos = mysqli_fetch_array($res_datos)){
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

		      <input id="hd_registrarcomprobante_idregistro" type="hidden">
		      <input id="hd_registrarcomprobante_item" type="hidden">
		      <input id="hd_registrarcomprobante_modograbar" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_registrarcomprobante" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_registrarcomprobante_button" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_registrarcomprobante_button" onclick="f_GrabarRegistroComprobante();">Registrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_comprobantefechapago" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_comprobantefechapagoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_comprobantefechapagoLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Fecha Pago:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-12">
								<input id="comprobantefechapago_fecha" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>
		      </div>

		      <input id="hd_comprobantefechapago_idregistro" type="hidden">
		      <input id="hd_comprobantefechapago_item" type="hidden">
		      <input id="hd_comprobantefechapago_modograbar" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_comprobantefechapago" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_comprobantefechapago_button" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_comprobantefechapago_button" onclick="f_GrabarComprobanteFechaPago();">Registrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_detraccionfechapago" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_detraccionfechapagoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_detraccionfechapagoLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Fecha Pago:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-12">
								<input id="detraccionfechapago_fecha" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>
		      </div>

		      <input id="hd_detraccionfechapago_idregistro" type="hidden">
		      <input id="hd_detraccionfechapago_item" type="hidden">
		      <input id="hd_detraccionfechapago_modograbar" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_detraccionfechapago" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_detraccionfechapago_button" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_detraccionfechapago_button" onclick="f_GrabarDetraccionFechaPago();">Registrar</button>
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
					$("#nv_titulo").html('| Resumen de Transporte');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadResultados();
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

			$('.select2-search__field').css('font-size', '14px');

			$('.select2-selection__rendered').css('font-size', '14px');
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
      function f_LoadResultados(){
        var _html = '';

        // Obtiene filtros
          var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_estadocierre = $("#filtro_estadocierre").val();
	        var filtro_codigodespacho = $("#filtro_codigodespacho").val();
	        var filtro_lote = $("#filtro_lote").val();

        // Cargando datos
          f_LoadingResumen(1);

          $("#tbl_resumen").html(_html);

          $.post( "apis/backend.php", { accion: "get_GestionTransporte_Resumen", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, estado_cierre: filtro_estadocierre, filtro_codigodespacho: filtro_codigodespacho, filtro_lote: filtro_lote }, 
            function( data ) {
              if(data.estado == 1){
                $("#tbl_resumen").html(data.html);
              }

              f_LoadingResumen(0);

            }, "json");
      }

      function f_PrintLiquidacionTransporte(_id_cierre_md5, _id_planta, _id_modalidadenvio){
      	if (_id_planta == 3 && (_id_modalidadenvio == 1 || _id_modalidadenvio == 2)){ // Para Colibrí - Venta Directa
      		var url = 'print_liquidaciontransporte_colibri.php?x=' + _id_cierre_md5;
      	}
      	else{ // Para las demás plantas
      		if (_id_modalidadenvio == 3 || _id_modalidadenvio == 4){
      			// Si es Colibrí o Solandra agrega el bloque de Valor Referencial
      				var add_VR = 0;

      				if (_id_planta == 3 || _id_planta == 5){
      					add_VR = 1;
      				}

      			var url = 'print_liquidaciontransporte_comercializacion.php?x=' + _id_cierre_md5 + '&vr=' + add_VR;
      		}
      	}

    		window.open(url,'_blank',"");
      }

	    function f_AddEvidencia(_id_registro){
			  var input = document.createElement('input');
			  input.type = 'file';
			  input.accept = 'image/*';
			  input.onchange = function(event) {
			    var file = event.target.files[0];
			    var reader = new FileReader();
			    reader.onload = function(e) {
			      var imagen = document.getElementById('img_evidencia');
			      imagen.src = e.target.result;

			      f_SaveImagen(_id_registro);
			    };
			    reader.readAsDataURL(file);
			  };
			  input.click();

			  // $("#img_evidencia").show();
	    }

	    function f_ShowEvidencia(_id_registro){
	    	var _src = '';

	    	$("#img_imagenes").attr('src', '');

      	f_LoadingImagenes(1);

      	$.post( "apis/backend.php", { accion: "get_LiquidacionFlete_LiquidacionFirmadaSRC", id_registro: _id_registro }, 
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

	    function f_RegistrarComprobante(_modograbar, _item, _id_registro, _num_comprobante, _fecha_emision, _fecha_vencimiento, _id_tipopagocomprobante){
	    	// Definiendo título de ventana
          if (_modograbar == 'E'){
            tipo = "E";
            titulo = 'Editar Comprobante:<br>"<b>' + _num_comprobante + '</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Registrar Comprobante";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_registrarcomprobanteLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_registrarcomprobante_modograbar").val(tipo);

	      // Seteando datos
	        $("#hd_registrarcomprobante_item").val(_item);
	        $("#hd_registrarcomprobante_idregistro").val(_id_registro);

	        if (tipo != 'N'){
	        	$("#comprobante_numero").val(_num_comprobante);
	        	$("#comprobante_fechaemision").val(_fecha_emision);
	        	$("#comprobante_fechavencimiento").val(_fecha_vencimiento);
	        	$("#comprobante_tipopago").val(_id_tipopagocomprobante);
	        }
	        else{
	        	$("#comprobante_numero").val('');
	        	$("#comprobante_fechaemision").val('<?php echo $g_date ?>');
	        	$("#comprobante_fechavencimiento").val('<?php echo $g_date ?>');
	        	$("#comprobante_tipopago").val('');
	        }

		    // Cargando datos
	        f_OpenModal('modal_registrarcomprobante');
	    }

	    function f_CopyDate(){
	    	var fecha_emision = $("#comprobante_fechaemision").val();

	    	$("#comprobante_fechavencimiento").val(fecha_emision);
	    }

	    function f_AddComprobante(_id_registro){
			  var input = document.createElement('input');
			  input.type = 'file';
			  input.accept = 'image/*';
			  input.onchange = function(event) {
			    var file = event.target.files[0];
			    var reader = new FileReader();
			    reader.onload = function(e) {
			      var imagen = document.getElementById('img_evidencia');
			      imagen.src = e.target.result;

			      f_SaveImagenComprobante(_id_registro);
			    };
			    reader.readAsDataURL(file);
			  };
			  input.click();

			  // $("#img_evidencia").show();
	    }

	    function f_ShowComprobante(_id_registro){
	    	var _src = '';

	    	$("#img_imagenes").attr('src', '');

      	f_LoadingImagenes(1);

      	$.post( "apis/backend.php", { accion: "get_LiquidacionFlete_ComprobanteSRC", id_registro: _id_registro }, 
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

	    function f_RegistrarComprobante_Pago(_modograbar, _item, _id_registro, _fecha_pago){
	    	// Definiendo título de ventana
          if (_modograbar == 'E'){
            tipo = "E";
            titulo = 'Comprobante - Editar Fecha Pago: "<b>' + _fecha_pago + '</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Comprobante - Registrar Fecha Pago";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_comprobantefechapagoLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_comprobantefechapago_modograbar").val(tipo);

	      // Seteando datos
	        $("#hd_comprobantefechapago_item").val(_item);
	        $("#hd_comprobantefechapago_idregistro").val(_id_registro);

	        if (tipo != 'N'){
	        	$("#comprobantefechapago_fecha").val(_fecha_pago);
	        }
	        else{
	        	$("#comprobantefechapago_fecha").val('<?php echo $g_date ?>');
	        }

		    // Cargando datos
	        f_OpenModal('modal_comprobantefechapago');
	    }

	    function f_RegistrarPago_Detraccion(_modograbar, _item, _id_registro, _fecha_pago){
	    	// Definiendo título de ventana
          if (_modograbar == 'E'){
            tipo = "E";
            titulo = 'Detracción - Editar Fecha Pago: "<b>' + _fecha_pago + '</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Detracción - Registrar Fecha Pago";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_detraccionfechapagoLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_detraccionfechapago_modograbar").val(tipo);

	      // Seteando datos
	        $("#hd_detraccionfechapago_item").val(_item);
	        $("#hd_detraccionfechapago_idregistro").val(_id_registro);

	        if (tipo != 'N'){
	        	$("#detraccionfechapago_fecha").val(_fecha_pago);
	        }
	        else{
	        	$("#detraccionfechapago_fecha").val('<?php echo $g_date ?>');
	        }

		    // Cargando datos
	        f_OpenModal('modal_detraccionfechapago');
	    }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingResumen(_is_show){
				if (_is_show == 1){
					$("#wt_resumen").show();
				}
				else{
					$("#wt_resumen").hide();
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

			function f_LoadingRegistroComprobante(_is_show){
				if (_is_show == 1){
					$("#wt_registrarcomprobante").show();

					$(".wt_registrarcomprobante_button").prop('disabled', true);
				}
				else{
					$("#wt_registrarcomprobante").hide();

					$(".wt_registrarcomprobante_button").prop('disabled', false);
				}
			}

			function f_LoadingComprobanteFechaPago(_is_show){
				if (_is_show == 1){
					$("#wt_comprobantefechapago").show();

					$(".wt_comprobantefechapago_button").prop('disabled', true);
				}
				else{
					$("#wt_comprobantefechapago").hide();

					$(".wt_comprobantefechapago_button").prop('disabled', false);
				}
			}

			function f_LoadingDetraccionFechaPago(_is_show){
				if (_is_show == 1){
					$("#wt_detraccionfechapago").show();

					$(".wt_detraccionfechapago_button").prop('disabled', true);
				}
				else{
					$("#wt_detraccionfechapago").hide();

					$(".wt_detraccionfechapago_button").prop('disabled', false);
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
      function f_SaveImagen(_id_registro){
	    	if ($("#img_evidencia").attr('src').length == 0){
	    		setTimeout('f_SaveImagen(' + _id_registro + ')', 1000);
	    	}
	    	else{
	    		// Guardando archivo
						var arr_imagenes = [];

						var _imagen = {
				      imagen: $("#img_evidencia").attr('src')
				    };

				    arr_imagenes.push(_imagen);

				    $.post( "apis/backend.php", { accion: "grabar_LiquidacionFlete_UploadLiquidacionFirmada", id_registro: _id_registro, arr_imagenes: JSON.stringify(arr_imagenes) },
	            function( data ) {
	            	if(data.estado == 1){
	            		$("#img_view_1_" + _id_registro).css('display', 'block');
	              }
		            else{
		            	if (data.estado == 0){
		            		alert("Ocurrió un error al momento de grabar la imagen.");

		            		return;
		            	}

		            	if (data.estado == 99){
		            		alert("El formato de imagen no es compatible.");

		            		return;
		            	}
		            }

	            }, "json");
	    	}
	    }

	    function f_GrabarRegistroComprobante(){
	    	// Recupera variables
	    		var item = $("#hd_registrarcomprobante_item").val();
					var id_registro = $("#hd_registrarcomprobante_idregistro").val();
					var modo_grabar = $("#hd_registrarcomprobante_modograbar").val();

		    	var comprobante_numero = $("#comprobante_numero").val().toUpperCase();
		    	var fecha_emision = $("#comprobante_fechaemision").val();
		    	var fecha_vencimiento = $("#comprobante_fechavencimiento").val();
		    	var tipo_pago = $("#comprobante_tipopago").val();
		    	var tipopago_des = $("#comprobante_tipopago option:selected").text();

	    	// Validando datos
	    		if (comprobante_numero == null){
            alert("Debe registrar el N° de Comprobante.");

            return;
          }
          if (comprobante_numero.length == 0){
            alert("Debe registrar el N° de Comprobante.");

            return;
          }

          if (fecha_emision == null){
            alert("Debe registrar la Fecha de Emisión.");

            return;
          }
          if (fecha_emision.length == 0){
            alert("Debe registrar la Fecha de Emisión.");

            return;
          }

          if (fecha_vencimiento == null){
            alert("Debe registrar la Fecha de Vencimiento.");

            return;
          }
          if (fecha_vencimiento.length == 0){
            alert("Debe registrar la Fecha de Vencimiento.");

            return;
          }

          if (fecha_vencimiento < fecha_emision){
          	alert("La Fecha de Vencimiento no puede ser menor a la Fecha de Emisión.");

          	return
          }

          if (tipo_pago == null){
            alert("Debe seleccionar el Tipo de Pago.");

            return;
          }
          if (tipo_pago.length == 0){
            alert("Debe seleccionar el Tipo de Pago.");

            return;
          }

        // Grabando Datos
          f_LoadingRegistroComprobante(1);

          $.post( "apis/backend.php", { accion: "grabar_LiquidacionFlete_RegistrarComprobante", modo_grabar: modo_grabar, id_registro: id_registro, comprobante_numero: comprobante_numero, fecha_emision: fecha_emision, fecha_vencimiento: fecha_vencimiento, tipo_pago: tipo_pago },
            function( data ) {
              if(data.estado == 1){
              	// Setea los campos del Registro del Comprobante
	              	var _html = '<a class="success" href="javascript: f_RegistrarComprobante(' + "'E', " + item + ', ' + id_registro + ", '" + comprobante_numero + "', '" + fecha_emision + "', '" + fecha_vencimiento + "', " + tipo_pago + ');"><font color="#4B94F2"><u style="font-weight: bold;"> ' + comprobante_numero + '</u></font></a>';

	              	$("#td_registrocomprobante_1_" + id_registro).html(_html);
	              	$("#td_registrocomprobante_2_" + id_registro).html(fecha_emision);
	              	$("#td_registrocomprobante_3_" + id_registro).html(fecha_vencimiento);
	              	$("#td_registrocomprobante_4_" + id_registro).html(tipopago_des);

	              // Setea el campo de carga del archivo del Comprobante
	              	var _html = '<div class="d-flex justify-content-center">';
	              	_html += '		<i class="bi bi-upload" style="margin-top: 3px; font-weight: bold; font-size: 18px; cursor: pointer; padding-right: 5px;" onclick="f_AddComprobante(' + id_registro + ')"></i>';
	              	_html += '		<img id="img_view_2_' + id_registro + '" src="' + '<?php echo $img_view ?>' + '" style="width: 35px; cursor: pointer; margin-left: 5px; border-left: solid; border-left-width: 1px; border-left-color: #595959; padding-left: 10px; display: none;" onclick="f_ShowComprobante(' + id_registro + ')">';
	              	_html += '</div>';

	              	$("#td_uploadcomprobante_" + id_registro).html(_html);

	              // Setea los campos de Pago de Comprobante
	              	var _html = '<button class="btn btn-primary" type="button" onclick="f_RegistrarComprobante_Pago(' + "'N', " + item + ', ' + id_registro + ", ''" + ');" style="color: #ffffff; font-size: 12px;">';
									_html += '			Registrar';
									_html += '		</button>';

									$("#td_comprobantefechapago_1_" + id_registro).html(_html);

									var _html = '<label style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #FF6A53; color: #ffffff; font-size: 12px; width: 70px;">';
									_html += '	Pendiente';
									_html += '</label>';

									$("#td_comprobantefechapago_2_" + id_registro).html(_html);

									var diferencia = f_RedondearDecimales(Math.abs(f_CalcularDiferenciaHoras('<?php echo $g_fecha ?>', $("#td_registrocomprobante_2_" + id_registro).html().trim())) / 24, 0);

									$("#td_comprobantefechapago_3_" + id_registro).html(diferencia);

	              // Setea los campos de Pago de Detracción
	              	var _html = '<button class="btn btn-primary" type="button" onclick="f_RegistrarPago_Detraccion(' + "'N', " + item + ', ' + id_registro + ", ''" + ');" style="color: #ffffff; font-size: 12px;">';
									_html += '			Registrar';
									_html += '		</button>';

									$("#td_pagodetraccion_1_" + id_registro).html(_html);

									var _html = '<label style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #FF6A53; color: #ffffff; font-size: 12px; width: 70px;">';
									_html += '	Pendiente';
									_html += '</label>';

									$("#td_pagodetraccion_2_" + id_registro).html(_html);

									var diferencia = f_RedondearDecimales(Math.abs(f_CalcularDiferenciaHoras('<?php echo $g_fecha ?>', $("#td_registrocomprobante_2_" + id_registro).html().trim())) / 24, 0);

									$("#td_pagodetraccion_3_" + id_registro).html(diferencia);
              }
              else{
                alert("Ocurrió un error al momento de Registrar el Comprobante");
              }

              f_cerrarModal('modal_registrarcomprobante');

              f_LoadingRegistroComprobante(0);

            }, "json");
	    }

	    function f_SaveImagenComprobante(_id_registro){
	    	if ($("#img_evidencia").attr('src').length == 0){
	    		setTimeout('f_SaveImagen(' + _id_registro + ')', 1000);
	    	}
	    	else{
	    		// Guardando archivo
						var arr_imagenes = [];

						var _imagen = {
				      imagen: $("#img_evidencia").attr('src')
				    };

				    arr_imagenes.push(_imagen);

				    $.post( "apis/backend.php", { accion: "grabar_LiquidacionFlete_UploadComprobante", id_registro: _id_registro, arr_imagenes: JSON.stringify(arr_imagenes) },
	            function( data ) {
	            	if(data.estado == 1){
	            		$("#img_view_2_" + _id_registro).css('display', 'block');
	              }
		            else{
		            	if (data.estado == 0){
		            		alert("Ocurrió un error al momento de grabar la imagen.");

		            		return;
		            	}

		            	if (data.estado == 99){
		            		alert("El formato de imagen no es compatible.");

		            		return;
		            	}
		            }

	            }, "json");
	    	}
	    }

	    function f_GrabarComprobanteFechaPago(){
	    	// Recupera variables
	    		var item = $("#hd_comprobantefechapago_item").val();
					var id_registro = $("#hd_comprobantefechapago_idregistro").val();
					var modo_grabar = $("#hd_comprobantefechapago_modograbar").val();

		    	var fecha_pago = $("#comprobantefechapago_fecha").val();

	    	// Validando datos
          if (fecha_pago == null){
            alert("Debe registrar la Fecha de Pago.");

            return;
          }
          if (fecha_pago.length == 0){
            alert("Debe registrar la Fecha de Pago.");

            return;
          }

        // Grabando Datos
          f_LoadingComprobanteFechaPago(1);

          $.post( "apis/backend.php", { accion: "grabar_LiquidacionFlete_ComprobanteFechaPago", modo_grabar: modo_grabar, id_registro: id_registro, fecha_pago: fecha_pago },
            function( data ) {
              if(data.estado == 1){
              	// Seteando la Fecha de Pago
              		var _html = '<a class="success" href="javascript: f_RegistrarComprobante_Pago(' + "'E', " + item + ', ' + id_registro + ", '" + fecha_pago + "'" + ');"><font color="#4B94F2"><u style="font-weight: bold;"> ' + fecha_pago + '</u></font></a>';

              		$("#td_comprobantefechapago_1_" + id_registro).html(_html);

              	// Seteando Estado de Pago
	              	var _html = '<label style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #7DBF3B; color: #ffffff; font-size: 12px; width: 70px;">';
	              	_html += '	Pagado';
	              	_html += '</label>';

	              	$("#td_comprobantefechapago_2_" + id_registro).html(_html);

              	// Obteniendo diferencia de horas
              		var diferencia = f_CalcularDiferenciaHoras(fecha_pago, $("#td_registrocomprobante_2_" + id_registro).html().trim());

              		$("#td_comprobantefechapago_3_" + id_registro).html(Math.abs(diferencia) / 24);
              }
              else{
                alert("Ocurrió un error al momento de Registrar la Fecha de Pago del Comprobante.");
              }

              f_cerrarModal('modal_comprobantefechapago');

              f_LoadingComprobanteFechaPago(0);

            }, "json");
	    }

	    function f_GrabarDetraccionFechaPago(){
	    	// Recupera variables
	    		var item = $("#hd_detraccionfechapago_item").val();
					var id_registro = $("#hd_detraccionfechapago_idregistro").val();
					var modo_grabar = $("#hd_detraccionfechapago_modograbar").val();

		    	var fecha_pago = $("#detraccionfechapago_fecha").val();

	    	// Validando datos
          if (fecha_pago == null){
            alert("Debe registrar la Fecha de Pago.");

            return;
          }
          if (fecha_pago.length == 0){
            alert("Debe registrar la Fecha de Pago.");

            return;
          }

        // Grabando Datos
          f_LoadingDetraccionFechaPago(1);

          $.post( "apis/backend.php", { accion: "grabar_LiquidacionFlete_DetraccionFechaPago", modo_grabar: modo_grabar, id_registro: id_registro, fecha_pago: fecha_pago },
            function( data ) {
              if(data.estado == 1){
              	// Seteando la Fecha de Pago
              		var _html = '<a class="success" href="javascript: f_RegistrarPago_Detraccion(' + "'E', " + item + ', ' + id_registro + ", '" + fecha_pago + "'" + ');"><font color="#4B94F2"><u style="font-weight: bold;"> ' + fecha_pago + '</u></font></a>';

              		$("#td_pagodetraccion_1_" + id_registro).html(_html);

              	// Seteando Estado de Pago
	              	var _html = '<label style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #7DBF3B; color: #ffffff; font-size: 12px; width: 70px;">';
	              	_html += '	Pagado';
	              	_html += '</label>';

	              	$("#td_pagodetraccion_2_" + id_registro).html(_html);

              	// Obteniendo diferencia de horas
              		var diferencia = f_CalcularDiferenciaHoras(fecha_pago, $("#td_registrocomprobante_2_" + id_registro).html().trim());

              		$("#td_pagodetraccion_3_" + id_registro).html(Math.abs(diferencia) / 24);
              }
              else{
                alert("Ocurrió un error al momento de Registrar la Fecha de Pago de la Detracción.");
              }

              f_cerrarModal('modal_detraccionfechapago');

              f_LoadingDetraccionFechaPago(0);

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