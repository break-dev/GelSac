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

		<title><?php echo $nom_app; ?> | LQ - Análisis de AAS</title>

		<script type="text/javascript">
			let itemmatriz_Selected = 0;
      let idmatriz_Selected = 0;
      let itemelemento_Selected = 0;
      let idelemento_Selected = 0;
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
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
								<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
									<div class="d-flex" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #fff3cd; padding: 5px;">
										<h5 style="padding-left: 5px;">Configuración General de Dilución</h5>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -5px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>
							</div>

							<div class="d-flex" style="padding: 20px; margin-top: -25px; overflow-x: scroll; width: 100%;">
								<div id="div_matrices" class="col-md-6 col-sm-6 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
									<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<div class="d-flex">
														<h6>Lista de Matrices</h6>

														<div id="wt_matriz" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>
													</div>
												</div>

												<div class="col-md-4 col-sm-4 col-xs-12">
													<button class="btn btn-info" type="button" onclick="f_AdminMatriz('N');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
							              <b>+ Nueva Matriz</b>
							            </button>
												</div>
											</div>
										</div>

										<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
											<table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 14px;">
							        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
							        				Item
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
							        				Capacidad
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Predeterminado
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Estado
							        			</th>
							        		</tr>
							        	</thead>

							        	<tbody id="tbl_matrices">
							        		
							        	</tbody>
							        </table>
										</div>
									</div>
								</div>

								<div id="div_dilucion" class="col-md-6 col-sm-6 col-xs-12" style="padding: 5px;">
									<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<div class="d-flex">
														<h6>Diluciones de Matriz de: </h6>
														<h6 id="lbl_titulodiluciones" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h6>

														<div id="wt_dilucion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>
													</div>
												</div>

												<div class="col-md-4 col-sm-4 col-xs-12">
													<button class="btn btn-info" type="button" onclick="f_AdminDilucion('N');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
							              <b>+ Nueva Dilución</b>
							            </button>
												</div>
											</div>
										</div>

										<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
											<table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 14px;">
							        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
							        				Item
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Dilución
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Alicuota
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Fiola
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Estado
							        			</th>
							        		</tr>
							        	</thead>

							        	<tbody id="tbl_diluciones">
							        		
							        	</tbody>
							        </table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="d-flex row">
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
								<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
									<div class="d-flex" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #fff3cd; padding: 5px;">
										<h5 style="padding-left: 5px;">Configuración de Patrones por Elementos</h5>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -5px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>
							</div>

							<div class="d-flex" style="padding: 20px; margin-top: -25px; overflow-x: scroll; width: 100%;">
								<div id="div_elementos" class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
									<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<div class="d-flex">
														<h6>Lista de Elementos</h6>

														<div id="wt_elementos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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
							        		<tr style="font-size: 14px;">
							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
							        				Item
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
							        				Elemento
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Símbolo
							        			</th>
							        		</tr>
							        	</thead>

							        	<tbody id="tbl_elementos">
							        		
							        	</tbody>
							        </table>
										</div>
									</div>
								</div>

								<div id="div_patrones" class="col-md-8 col-sm-8 col-xs-12" style="padding: 5px;">
									<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
											<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<div class="d-flex">
														<h6>Patrones de: </h6>
														<h6 id="lbl_titulopatrones" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h6>

														<div id="wt_patrones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>
													</div>
												</div>

												<div class="col-md-4 col-sm-4 col-xs-12">
													<button class="btn btn-info" type="button" onclick="f_AdminPatron('N');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
							              <b>+ Nuevo Patrón</b>
							            </button>
												</div>
											</div>
										</div>

										<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
											<table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 14px;">
							        			<th rowspan="2" colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
							        				Item
							        			</th>

							        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Patrón
							        			</th>

							        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Media
							        			</th>

							        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Desviación
							        			</th>

							        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Info. Dilución
							        			</th>

							        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Predeterminado
							        			</th>

							        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Observación
							        			</th>

							        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Estado
							        			</th>
							        		</tr>

							        		<tr style="font-size: 14px;">
							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Matriz
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Dilución
							        			</th>
							        		</tr>
							        	</thead>

							        	<tbody id="tbl_patrones">
							        		
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
		<div class="modal fade modal-dialog-scrollable" id="modal_adminmatrices" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminmatricesLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_adminmatricesLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: right;">
								Capacidad (ml):
							</div>

							<div class="col-md-5 col-sm-5 col-xs-5">
								<input id="matriz_capacidad" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">

							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<div class="form-check">
								  <input id="matriz_predeterminado" class="form-check-input" type="checkbox">
								  <label class="form-check-label" for="matriz_predeterminado">
								    Predeterminado
								  </label>
								</div>
							</div>
						</div>
		      </div>

		      <input id="modo_grabarmatriz" type="hidden">
		      <input id="id_matriz" type="hidden">
		      <input id="item_matriz" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_adminmatrices" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button id="btn_cerrarmatriz" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button id="btn_grabarmatriz" type="button" class="btn btn-primary" onclick="f_GrabarMatriz();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_admindiluciones" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_admindilucionesLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_admindilucionesLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: right;">
								Dilución:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-5">
								<input id="dilucion_dilucion" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: right;">
								Alicuota:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-5">
								<input id="dilucion_alicuota" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: right;">
								Fiola:
							</div>

							<div class="col-md-5 col-sm-5 col-xs-5">
								<input id="dilucion_fiola" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>
		      </div>

		      <input id="modo_grabardilucion" type="hidden">
		      <input id="id_dilucion" type="hidden">
		      <input id="item_dilucion" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_admindiluciones" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button id="btn_cerrardilucion" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button id="btn_grabardilucion" type="button" class="btn btn-primary" onclick="f_GrabarDilucion();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_adminpatrones" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminpatronesLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_adminpatronesLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px; text-align: left;">

							</div>

							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-check">
								  <input id="patron_predeterminado" class="form-check-input" type="checkbox">
								  <label class="form-check-label" for="patron_predeterminado">
								    Predeterminado
								  </label>
								</div>
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: right;">
								Patrón:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="patron_patron" type="text" class="form-control col-md-12 col-xs-12" style="text-transform: uppercase; text-align: center;">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: right;">
								Media:
							</div>

							<div class="col-md-3 col-sm-3 col-xs-3">
								<input id="patron_media" type="number" class="form-control col-md-12 col-xs-12" min="0" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: right;">
								Desviación:
							</div>

							<div class="col-md-3 col-sm-3 col-xs-3">
								<input id="patron_desviacion" type="number" class="form-control col-md-12 col-xs-12" min="0" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: right;">
								<div class="form-check">
								  <input id="chk_patrondilucion" class="form-check-input" type="checkbox" onchange="f_ShowPatronDilucion();">
								  <label class="form-check-label" for="chk_patrondilucion">
								    Dilución:
								  </label>
								</div>
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<div id="div_patrondilucion" class="row" style="margin-bottom: 5px; padding: 5px; margin-left: 0px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #6c757d; width: 100%;">
									<div class="d-flex" style="padding: 0px;">
										<div id="lbl_patronmatriz" class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: right;">
											Matriz:
										</div>

										<div class="col-md-8 col-sm-8 col-xs-8">
											<select id="patron_matriz" class="form-select" style="text-align: left; margin-left: 5px; width: 95%;" onchange="f_LoadMatrizDilucion();" disabled>
												<?php

												$q_matriz = "SELECT Id,
									                          capacidad
									                     FROM tbconfig_matrizdilucion
									                    WHERE estado = 'A'
									                   ORDER BY is_predeterminado DESC, capacidad";

									      if ($res_matriz = mysqli_query($enlace, $q_matriz)) {
									      	if (mysqli_num_rows($res_matriz) > 0) {
									          while($row_matriz = mysqli_fetch_assoc($res_matriz)) {
											      	?>

											      	<option value="<?php echo $row_matriz["Id"] ?>"><?php echo $row_matriz["capacidad"] ?></option>

											      	<?php
											      }
											    }
									      }

												?>
											</select>
										</div>
									</div>

									<div class="d-flex" style="padding: 0px; margin-top: 5px;">
										<div id="lbl_patrondilucion" class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: right;">
											Dilución:
										</div>

										<div class="col-md-8 col-sm-8 col-xs-8">
											<select id="patron_dilucion" class="form-select" style="text-align: left; margin-left: 5px; width: 95%;" disabled>

											</select>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: right;">
								Observación:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="patron_observacion" type="text" class="form-control obj_cab col-md-12 col-xs-12" style="text-transform: uppercase;" rows="3"></textarea>
							</div>
						</div>
		      </div>

		      <input id="modo_grabarpatron" type="hidden">
		      <input id="id_patron" type="hidden">
		      <input id="item_patron" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_adminpatrones" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button id="btn_cerrarpatron" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button id="btn_grabarpatron" type="button" class="btn btn-primary" onclick="f_GrabarPatron();">Grabar</button>
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
					$("#nv_titulo").html('| LQ - Análisis de AAS');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadMatrices();
					f_LoadElementos();
					f_LoadMatrizDilucion();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadMatrices(){
        var _html = '';
        var d = 1;

        // Obteniendo filtros

        // Validando datos

				// Cargando Lista de Racks
	        $("#tbl_matrices").html('');

	        f_LoadingMatrices(1);

	        $.post( "apis/backend.php", { accion: "get_AnalisisAAS_ListaMatrices" }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_matrices").html(data.html);

	            	itemmatriz_Selected = 1;
								idmatriz_Selected = data.id_matriz;

								f_LoadItemMatriz(itemmatriz_Selected, idmatriz_Selected);
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingMatrices(0);

	          }, "json");

    	};

      function f_AdminMatriz(_modo, _item, _id_matriz, _capacidad, _is_predeterminado){
        // Registrando el modo
          $("#modo_grabarmatriz").val(_modo);
          $("#id_matriz").val(_id_matriz);
          $("#item_matriz").val(_item);

        // Colocando Títulos
          if (_modo == 'N'){
            $("#modal_adminmatricesLabel").html('Nueva Matriz');
          }
          else{
            $("#modal_adminmatricesLabel").html('Editar Matriz');
          }

        // Cargando datos
          if (_modo != 'N'){
            $("#matriz_capacidad").val(_capacidad);
						$("#matriz_predeterminado").prop('checked', ((_is_predeterminado == 1) ? true : false));
          }
          else{
            $("#matriz_capacidad").val('');
						$("#matriz_predeterminado").prop('checked', false);

						document.getElementById("matriz_capacidad").focus();
          }

        // Abre modal
        	f_OpenModal('modal_adminmatrices');
      };

      function f_ColorSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_matrices tr").each(function () {
          $("#tr_item_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_item_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulodiluciones").html($("#td_item_5_" + _item).html().trim() + ' ml');
      };

      function f_LoadItemMatriz(_item, _id_matriz){
        var _html = '';

        // Pinta selección
          f_ColorSelected(_item);

        // Cargando datos
          f_LoadingDiluciones(1);

          $("#tbl_diluciones").html(_html);

          $.post( "apis/backend.php", { accion: "get_AnalisisAAS_DilucionesMatriz", id_matriz: _id_matriz }, 
            function( data ) {
              if(data.estado == 1){
                $("#tbl_diluciones").html(data.html);
              }

              f_LoadingDiluciones(0);

            }, "json");

        itemmatriz_Selected = _item;
        idmatriz_Selected = _id_matriz;
      };

      function f_AdminDilucion(_modo, _item, _id_dilucion, _dilucion, _alicuota, _fiola){
        // Registrando el modo
          $("#modo_grabardilucion").val(_modo);
          $("#id_dilucion").val(_id_dilucion);
          $("#item_dilucion").val(_item);

        // Colocando Títulos
          if (_modo == 'N'){
            $("#modal_admindilucionesLabel").html('Nueva Dilución');
          }
          else{
            $("#modal_admindilucionesLabel").html('Editar Dilución');
          }

        // Cargando datos
          if (_modo != 'N'){
            $("#dilucion_dilucion").val(_dilucion);
						$("#dilucion_alicuota").val(_alicuota);
						$("#dilucion_fiola").val(_fiola);
          }
          else{
            $("#dilucion_dilucion").val('');
						$("#dilucion_alicuota").val('');
						$("#dilucion_fiola").val('');

						document.getElementById("dilucion_dilucion").focus();
          }

        // Abre modal
        	f_OpenModal('modal_admindiluciones');
      };

			function f_LoadElementos(){
        var _html = '';
        var d = 1;
        var id_elemento = 0;

        // Obteniendo filtros

        // Validando datos

				// Cargando Lista de Racks
	        $("#tbl_elementos").html('');

	        f_LoadingElementos(1);

	        $.post( "apis/backend.php", { accion: "get_ListaElementosQuimicos", modulo: 1 }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$.each( data.registros, function( key, val ) {
	            		if (d == 1){
	            			id_elemento = val.Id;
	            		}

	            		_html += '<tr id="tr_item_E_' + d + '" style="cursor: pointer; font-size: 14px;" onclick="f_LoadItemElemento(' + d + ', ' + val.Id + ')">';

									_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
									_html += '   ' + d;
									_html += '  </td>';

									_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
									_html += '   ' + val.descripcion;
									_html += '  </td>';

									_html += '  <td id="td_item_E_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
									_html += '   ' + val.abv;
									_html += '  </td>';

									_html += '</tr>';

									d ++;
	            	});

	            	$("#tbl_elementos").html(_html);

	            	itemelemento_Selected = 1;
								idelemento_Selected = id_elemento;

								f_LoadItemElemento(itemelemento_Selected, idelemento_Selected);
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingElementos(0);

	          }, "json");

    	};

      function f_ColorSelectedElementos(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_elementos tr").each(function () {
          $("#tr_item_E_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_item_E_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulopatrones").html($("#td_item_E_" + _item).html().trim());
      };

      function f_LoadItemElemento(_item, _id_elemento){
        var _html = '';

        // Pinta selección
          f_ColorSelectedElementos(_item);

        // Cargando datos
          f_LoadingPatrones(1);

          $("#tbl_patrones").html(_html);

          $.post( "apis/backend.php", { accion: "get_AnalisisAAS_ListaElementoPatrones", id_elemento: _id_elemento }, 
            function( data ) {
              if(data.estado == 1){
                $("#tbl_patrones").html(data.html);
              }

              f_LoadingPatrones(0);

            }, "json");

        itemelemento_Selected = _item;
        idelemento_Selected = _id_elemento;
      };

      function f_AdminPatron(_modo, _item, _id_patron, _des_patron, _media, _desviacion, _id_matriz, _id_dilucion, _observacion, _is_predeterminado){
        // Registrando el modo
          $("#modo_grabarpatron").val(_modo);
          $("#id_patron").val(_id_patron);
          $("#item_patron").val(_item);

        // Carga listas
          f_LoadMatrizPatrones(_id_matriz, _id_dilucion);

        // Colocando Títulos
          if (_modo == 'N'){
            $("#modal_adminpatronesLabel").html('Nuevo Patrón');
          }
          else{
            $("#modal_adminpatronesLabel").html('Editar Patrón');
          }

        // Cargando datos
          if (_modo != 'N'){
          	$("#patron_predeterminado").prop('checked', ((_is_predeterminado == 1) ? true : false));
            $("#patron_patron").val(_des_patron);
						$("#patron_media").val(_media);
						$("#patron_desviacion").val(_desviacion);

						if (_id_matriz == 0){
							$("#chk_patrondilucion").prop('checked', false);
						}
						else{
							$("#chk_patrondilucion").prop('checked', true);

							$("#patron_matriz").val(_id_matriz);
							$("#patron_dilucion").val(_id_dilucion);
						}

						$("#patron_observacion").val(_observacion);
          }
          else{
          	$("#patron_predeterminado").prop('checked', false);
            $("#patron_patron").val('');
						$("#patron_media").val('');
						$("#patron_desviacion").val('');
						$("#chk_patrondilucion").prop('checked', false);
						$("#patron_observacion").val('');
          }

          f_ShowPatronDilucion();

          document.getElementById("dilucion_dilucion").focus();

        // Abre modal
        	f_OpenModal('modal_adminpatrones');
      };

      function f_ShowPatronDilucion(){
      	var tiene_dilucion = (($("#chk_patrondilucion").prop('checked')) ? 1 : 0);

				// Setea objetos
					if (tiene_dilucion == 1){
						$("#patron_matriz").prop('disabled', false);
						$("#patron_dilucion").prop('disabled', false);

						$("#div_patrondilucion").css('background-color', '#FFF587');
					}
					else{
						$("#patron_matriz").prop('disabled', true);
						$("#patron_dilucion").prop('disabled', true);

						$("#div_patrondilucion").css('background-color', '#6c757d');
					}
      }

      function f_LoadMatrizPatrones(_id_matriz, _id_dilucion){
      	var _html = '';
      	var m = 1;

      	$("#patron_matriz").html('');

      		$.post( "apis/backend.php", { accion: "get_AnalisisAAS_ListaMatrices", solo_activos: 1 }, 
            function( data ) {
              if(data.estado == 1){
								$.each( data.registros, function( key, val ) {
                	_html += '<option ' + ((_id_matriz != 0) ? ((_id_matriz == val.Id) ? 'selected' : '') : ((m == 1) ? 'selected' : '')) + ' value="' + val.Id + '">' + val.capacidad + '</option>';

                	m ++;
                });

                $("#patron_matriz").html(_html);

                f_LoadMatrizDilucion(_id_dilucion);
              }

            }, "json");
      }

      function f_LoadMatrizDilucion(_id_dilucion){
      	var _html = '';
      	var _id_matriz = $("#patron_matriz").val();

      	// Carga lista
      		$("#patron_dilucion").html('');

      		$.post( "apis/backend.php", { accion: "get_AnalisisAAS_DilucionesMatriz", id_matriz: _id_matriz, solo_activos: 1 }, 
            function( data ) {
              if(data.estado == 1){
								$.each( data.registros, function( key, val ) {
                	_html += '<option ' + ((_id_dilucion == val.Id) ? 'selected' : '') + ' value="' + val.Id + '">' + val.dilucion + ' (' + val.aliquota + ' en ' + val.fiola + ')</option>';
                });

                $("#patron_dilucion").html(_html);
              }

            }, "json");
      }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			$("#modal_adminmatrices").on('shown.bs.modal', function(){
      	$("#matriz_capacidad").focus();
    	});

    	$("#modal_admindiluciones").on('shown.bs.modal', function(){
      	$("#dilucion_dilucion").focus();
    	});

    	$("#modal_adminpatrones").on('shown.bs.modal', function(){
      	$("#patron_patron").focus();
    	});

    	function f_LoadingMatrices(_is_show){
				if (_is_show == 1){
					$("#wt_matriz").show();
				}
				else{
					$("#wt_matriz").hide();
				}
			}

    	function f_LoadingDiluciones(_is_show){
				if (_is_show == 1){
					$("#wt_dilucion").show();
				}
				else{
					$("#wt_dilucion").hide();
				}
			}

    	function f_LoadingAdminMatrices(_is_show){
				if (_is_show == 1){
					$("#wt_adminmatrices").show();

					$("#btn_cerrarmatriz").prop('disabled', true);
					$("#btn_cerrarmatriz").css('background-color', '#C2C0A6');
					$("#btn_grabarmatriz").prop('disabled', true);
					$("#btn_grabarmatriz").css('background-color', '#C2C0A6');
				}
				else{
					$("#wt_adminmatrices").hide();

					$("#btn_cerrarmatriz").prop('disabled', false);
					$("#btn_cerrarmatriz").css('background-color', '');
					$("#btn_grabarmatriz").prop('disabled', false);
					$("#btn_grabarmatriz").css('background-color', '');
				}
			}

    	function f_LoadingAdminDiluciones(_is_show){
				if (_is_show == 1){
					$("#wt_admindiluciones").show();

					$("#btn_cerrardilucion").prop('disabled', true);
					$("#btn_cerrardilucion").css('background-color', '#C2C0A6');
					$("#btn_grabardilucion").prop('disabled', true);
					$("#btn_grabardilucion").css('background-color', '#C2C0A6');
				}
				else{
					$("#wt_admindiluciones").hide();

					$("#btn_cerrardilucion").prop('disabled', false);
					$("#btn_cerrardilucion").css('background-color', '');
					$("#btn_grabardilucion").prop('disabled', false);
					$("#btn_grabardilucion").css('background-color', '');
				}
			}

			function f_LoadingElementos(_is_show){
				if (_is_show == 1){
					$("#wt_elementos").show();
				}
				else{
					$("#wt_elementos").hide();
				}
			}

			function f_LoadingPatrones(_is_show){
				if (_is_show == 1){
					$("#wt_patrones").show();
				}
				else{
					$("#wt_patrones").hide();
				}
			}

    	function f_LoadingAdminPatrones(_is_show){
				if (_is_show == 1){
					$("#wt_adminpatrones").show();

					$("#btn_cerrarpatron").prop('disabled', true);
					$("#btn_cerrarpatron").css('background-color', '#C2C0A6');
					$("#btn_grabarpatron").prop('disabled', true);
					$("#btn_grabarpatron").css('background-color', '#C2C0A6');
				}
				else{
					$("#wt_adminpatrones").hide();

					$("#btn_cerrarpatron").prop('disabled', false);
					$("#btn_cerrarpatron").css('background-color', '');
					$("#btn_grabarpatron").prop('disabled', false);
					$("#btn_grabarpatron").css('background-color', '');
				}
			}

			// --------------------------------------
			function f_SetButtons(_x){
				$("#btn_addmuestras").prop('disabled', true);
				$("#btn_addmuestras").css('background-color', '#BBBBBB');
				$("#btn_addmuestras").css('color', '#ffffff');
				$("#btn_addmuestras").removeClass('btn-primary');
				$("#btn_addmuestras").addClass('btn-secondary');

				$("#btn_iniciosecado").prop('disabled', true);
				$("#btn_iniciosecado").css('background-color', '#BBBBBB');
				$("#btn_iniciosecado").css('color', '#ffffff');
				$("#btn_iniciosecado").removeClass('btn-primary');
				$("#btn_iniciosecado").addClass('btn-secondary');

				$("#btn_finsecado").prop('disabled', true);
				$("#btn_finsecado").css('background-color', '#BBBBBB');
				$("#btn_finsecado").css('color', '#ffffff');
				$("#btn_finsecado").removeClass('btn-primary');
				$("#btn_finsecado").addClass('btn-secondary');

				$("#btn_cierreanalisis").prop('disabled', true);
				$("#btn_cierreanalisis").css('background-color', '#BBBBBB');
				$("#btn_cierreanalisis").css('color', '#ffffff');
				$("#btn_cierreanalisis").removeClass('btn-primary');
				$("#btn_cierreanalisis").removeClass('btn-danger');
				$("#btn_cierreanalisis").addClass('btn-secondary');

				if (_x == 1){
					$("#btn_addmuestras").prop('disabled', false);
					$("#btn_addmuestras").css('background-color', '');
					$("#btn_addmuestras").css('color', '');
					$("#btn_addmuestras").removeClass('btn-secondary');
					$("#btn_addmuestras").addClass('btn-primary');
				}

				if (_x == 2){
					$("#btn_addmuestras").prop('disabled', false);
					$("#btn_addmuestras").css('background-color', '');
					$("#btn_addmuestras").css('color', '');
					$("#btn_addmuestras").removeClass('btn-secondary');
					$("#btn_addmuestras").addClass('btn-primary');

					$("#btn_iniciosecado").prop('disabled', false);
					$("#btn_iniciosecado").css('background-color', '');
					$("#btn_iniciosecado").css('color', '');
					$("#btn_iniciosecado").removeClass('btn-secondary');
					$("#btn_iniciosecado").addClass('btn-primary');
				}

				if (_x == 3){
					$("#btn_finsecado").prop('disabled', false);
					$("#btn_finsecado").css('background-color', '');
					$("#btn_finsecado").css('color', '');
					$("#btn_finsecado").removeClass('btn-secondary');
					$("#btn_finsecado").addClass('btn-primary');
				}

				if (_x == 4){

				}

				if (_x == 5){
					$("#btn_cierreanalisis").prop('disabled', false);
					$("#btn_cierreanalisis").css('background-color', '');
					$("#btn_cierreanalisis").css('color', '');
					$("#btn_cierreanalisis").removeClass('btn-secondary');
					$("#btn_cierreanalisis").addClass('btn-danger');
				}
			}

			function f_AddMuestras_SetButtons(_x){
				$("#btn_addmuestra_pesobandeja").prop('disabled', true);
				$("#btn_addmuestra_pesobandeja").css('background-color', '#BBBBBB');
				$("#btn_addmuestra_pesobandeja").css('color', '#ffffff');
				$("#btn_addmuestra_pesobandeja").removeClass('btn-primary');
				$("#btn_addmuestra_pesobandeja").addClass('btn-secondary');

				$("#btn_addmuestra_pesohumedo").prop('disabled', true);
				$("#btn_addmuestra_pesohumedo").css('background-color', '#BBBBBB');
				$("#btn_addmuestra_pesohumedo").css('color', '#ffffff');
				$("#btn_addmuestra_pesohumedo").removeClass('btn-primary');
				$("#btn_addmuestra_pesohumedo").addClass('btn-secondary');

				if (_x == 1){
					$("#btn_addmuestra_pesobandeja").prop('disabled', false);
					$("#btn_addmuestra_pesobandeja").css('background-color', '');
					$("#btn_addmuestra_pesobandeja").css('color', '');
					$("#btn_addmuestra_pesobandeja").removeClass('btn-secondary');
					$("#btn_addmuestra_pesobandeja").addClass('btn-primary');
				}

				if (_x == 2){
					$("#btn_addmuestra_pesohumedo").prop('disabled', false);
					$("#btn_addmuestra_pesohumedo").css('background-color', '');
					$("#btn_addmuestra_pesohumedo").css('color', '');
					$("#btn_addmuestra_pesohumedo").removeClass('btn-secondary');
					$("#btn_addmuestra_pesohumedo").addClass('btn-primary');
				}
			}

			function f_LoadingBuscarMuestra(_is_show){
				if (_is_show == 1){
					$("#wt_detallebarcode").show();
				}
				else{
					$("#wt_detallebarcode").hide();
				}
			}

			function f_LoadingListaPendientes(_is_show){
				if (_is_show == 1){
					$("#wt_listapendientes").show();
				}
				else{
					$("#wt_listapendientes").hide();
				}
			}

			$("#modal_addmuestras").on('shown.bs.modal', function(){
      	$("#addmuestra_barcode").focus();
    	});

    	$("#modal_getpeso").on('shown.bs.modal', function(){
      	$("#txt_getpeso").focus();
    	});

			function f_SelectBarcode(_ind){
				$("#th_buscarmuestra_1").val('');
        $("#th_buscarmuestra_2").val('');
        $("#th_buscarmuestra_3").val('');

				document.getElementById("th_buscarmuestra_" + _ind).focus();
			}

      function f_HideListaRacks(_x){
        if (_x == 1){
          $("#div_racks").hide();
          $("#div_muestras").width('100%');

          f_CerrarDiv('C', 'div_ShowListaRacks');
          f_CerrarDiv('A', 'div_HideListaRacks');
          }
        else{
          $("#div_racks").show();
          $("#div_muestras").width('');

          f_CerrarDiv('A', 'div_ShowListaRacks');
          f_CerrarDiv('C', 'div_HideListaRacks');
        }
      };
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_GrabarMatriz(){
        var _id_matriz = $("#id_matriz").val();
        var _item_matriz = $("#item_matriz").val();
        var _modo = $("#modo_grabarmatriz").val();

        var _matriz_capacidad = $("#matriz_capacidad").val();
        var _matriz_predeterminado = (($("#matriz_predeterminado").prop('checked')) ? 1 : 0);
        var _html = '';

        // Validando datos
          if (_matriz_capacidad == null){
            alert("Debe ingresar la Capacidad.");

            return;
          }
          if (_matriz_capacidad.length == 0){
            alert("Debe ingresar la Capacidad.");

            return;
          }
          if (_matriz_capacidad <= 0){
            alert("La Capacidad ingresada no es correcta.");

            return;
          }

        // Grabando datos
          f_LoadingAdminMatrices(1);

          $.post( "apis/backend.php", { accion: "grabar_AbsorcionAtomica_MatrizDilucion", modo: _modo, id_matriz: _id_matriz, capacidad: _matriz_capacidad, predeterminado: _matriz_predeterminado },
            function( data ) {
              if(data.estado == 1){
                // Registra el nuevo Rack
                  if (_modo == 'N'){
                    _id_matriz = data.id_matriz;

                    // Obtiene el total de Racks
                      var item_matriz = 1;

                      $("#tbl_matrices tr").each(function () {
                        item_matriz += 1;
                      });

                    // Obtiene los registros actuales de Racks
                      _html = $("#tbl_matrices").html();

                    // Agregando el nuevo Rack
                      _html += '<tr id="tr_item_' + item_matriz + '" style="cursor: pointer; font-size: 13px;" onclick="f_LoadItemMatriz(' + item_matriz + ', ' + _id_matriz + ')">';

                      _html += '  <td id="td_item_1_' + item_matriz + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
                      _html += '    ' + item_matriz;
                      _html += '  </td>';

                      _html += '  <td id="td_item_2_' + item_matriz + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 14px; width: 30px;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #4C8B44; color: #ffffff; cursor: pointer;" onclick="f_AdminMatriz(' + "'M', " + item_matriz + ', ' + _id_matriz + ', ' + _matriz_capacidad + ', ' + _matriz_predeterminado + ');">';
				              _html += '      	<i class="bi bi-pencil-square"></i>';
				              _html += '      </label>';
				              _html += '  </td>';

				              _html += '  <td id="td_item_3_' + item_matriz + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; width: 30px;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_InactivarMatriz(' + _id_matriz +  ", 'A'" + ');">';
											_html += '      	<i class="bi bi-dash-circle"></i>';
				              _html += '      </label>';
				              _html += '  </td>';

				              _html += '  <td id="td_item_4_' + item_matriz + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer; width: 30px;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarMatriz(' + _id_matriz + ');">X</label>';
				              _html += '  </td>';

                      _html += '  <td id="td_item_5_' + item_matriz + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                      _html += '    ' + _matriz_capacidad;
                      _html += '  </td>';

                      _html += '  <td id="td_item_6_' + item_matriz + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '      ' + ((_matriz_predeterminado == 1) ? 'SÍ' : '');
                      _html += '  </td>';

                      _html += '  <td id="td_item_7_' + item_matriz + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '      Activo';
                      _html += '  </td>';

                      _html += '</tr>';

                    $("#tbl_matrices").html(_html);

                    f_ColorSelected(item_matriz);

                  	// Actualiza variables
                  		itemmatriz_Selected = item_matriz;
                  		idmatriz_Selected = _id_matriz;
                  }

                // Actualiza el Rack seleccionado
                  if (_modo == 'M'){
                  	var _html_x = '';

                  	// Si es Predeterminado limpia el Predeterminado anterior, si es que lo hubiera
	                  	if (_matriz_predeterminado == 1){
	                  		$("#tbl_matrices tr").each(function () {
								          $(this).find("td").eq(5).html('');
								        });
	                  	}

                  	// td_item_2
                  		_html_x = '			<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #4C8B44; color: #ffffff; cursor: pointer;" onclick="f_AdminMatriz(' + "'M', " + item_matriz + ', ' + _id_matriz + ', ' + _matriz_capacidad + ', ' + _matriz_predeterminado + ');">';
				              _html_x += '      	<i class="bi bi-pencil-square"></i>';
				              _html_x += '			</label>';

				              $("#td_item_2_" + _item_matriz).html(_html_x);

                    $("#td_item_5_" + _item_matriz).html(_matriz_capacidad);
                    $("#td_item_6_" + _item_matriz).html(((_matriz_predeterminado == 1) ? 'SÍ' : ''));
                  }
 
                f_LoadItemMatriz(itemmatriz_Selected, idmatriz_Selected);
              }
              else{
              	if(data.estado == 2){
              		alert("La Matriz ingresada ya fue registrada anteriormente.\nPor favor, verificar.");

              		f_LoadingAdminMatrices(0);

              		return;
              	}
              	else{
              		alert("Ocurrió un error al momento guardar la Matriz.");

              		f_LoadingAdminMatrices(0);

              		return;
              	}
              }

              f_LoadingAdminMatrices(0);

              f_cerrarModal("modal_adminmatrices");

            }, "json");
      };

      function f_InactivarMatriz(_id_matriz, _modo){
      	var _estado = '';

      	if (_modo == 'A'){
      		_estado = 'Inactivar';
      	}
      	else{
      		_estado = 'Activar';
      	}

        if(confirm("¿Está seguro de " + _estado + " la Matriz seleccionada?")){
          $.post( "apis/backend.php", { accion: "eliminar_AbsorcionAtomica_MatrizDilucion", modo: _modo, id_matriz: _id_matriz },
            function( data ) {
              if(data.estado == 1){
                f_LoadMatrices();
              }
              else{
                alert("Ocurrió un error al momento de inactivar la Matriz.");
              }

            }, "json");
        }
      };

      function f_EliminarMatriz(_id_matriz){
        if(confirm("¿Está seguro de Eliminar la Matriz seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_AbsorcionAtomica_MatrizDilucion", modo: 'X', id_matriz: _id_matriz },
            function( data ) {
              if(data.estado == 1){
                f_LoadMatrices();
              }
              else{
                alert("Ocurrió un error al momento de eliminar la Matriz.");
              }

            }, "json");
        }
      };

      function f_GrabarDilucion(){
        var _id_dilucion = $("#id_dilucion").val();
        var _item_dilucion = $("#item_dilucion").val();
        var _modo = $("#modo_grabardilucion").val();

        var _dilucion_dilucion = $("#dilucion_dilucion").val();
        var _dilucion_alicuota = $("#dilucion_alicuota").val();
        var _dilucion_fiola = $("#dilucion_fiola").val();
        var _html = '';

        // Validando datos
          if (_dilucion_dilucion == null){
            alert("Debe ingresar la Dilución");

            return;
          }
          if (_dilucion_dilucion.length == 0){
            alert("Debe ingresar la Dilución");

            return;
          }
          if (_dilucion_dilucion <= 0){
            alert("La Capacidad ingresada Dilución");

            return;
          }

          if (_dilucion_alicuota == null){
            alert("Debe ingresar la Alicuota");

            return;
          }
          if (_dilucion_alicuota.length == 0){
            alert("Debe ingresar la Alicuota");

            return;
          }
          if (_dilucion_alicuota <= 0){
            alert("La Capacidad ingresada Alicuota");

            return;
          }

          if (_dilucion_fiola == null){
            alert("Debe ingresar la Fiola");

            return;
          }
          if (_dilucion_fiola.length == 0){
            alert("Debe ingresar la Fiola");

            return;
          }
          if (_dilucion_fiola <= 0){
            alert("La Capacidad ingresada Fiola");

            return;
          }

        // Grabando datos
          f_LoadingAdminDiluciones(1);

          $.post( "apis/backend.php", { accion: "grabar_AbsorcionAtomica_DilucionMatriz", modo: _modo, id_matriz: idmatriz_Selected, id_detalle: _id_dilucion, dilucion: _dilucion_dilucion, aliquota: _dilucion_alicuota, fiola: _dilucion_fiola },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemMatriz(itemmatriz_Selected, idmatriz_Selected);
              }
              else{
              	if(data.estado == 2){
              		alert("La Dilución ingresada ya fue registrada anteriormente.\nPor favor, verificar.");

              		f_LoadingAdminDiluciones(0);

              		return;
              	}
              	else{
              		alert("Ocurrió un error al momento guardar la Matriz.");

              		f_LoadingAdminDiluciones(0);

              		return;
              	}
              }

              f_LoadingAdminDiluciones(0);

              f_cerrarModal("modal_admindiluciones");

            }, "json");
      };

      function f_InactivarDilucion(_id_dilucion, _modo){
      	var _estado = '';

      	if (_modo == 'A'){
      		_estado = 'Inactivar';
      	}
      	else{
      		_estado = 'Activar';
      	}

        if(confirm("¿Está seguro de " + _estado + " la Dilución seleccionada?")){
          $.post( "apis/backend.php", { accion: "eliminar_AbsorcionAtomica_DilucionMatriz", modo: _modo, id_dilucion: _id_dilucion },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemMatriz(itemmatriz_Selected, idmatriz_Selected);
              }
              else{
                alert("Ocurrió un error al momento de inactivar la Dilución.");
              }

            }, "json");
        }
      };

      function f_EliminarDilucion(_id_dilucion, _modo){
        if(confirm("¿Está seguro de Eliminar la Dilución seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_AbsorcionAtomica_DilucionMatriz", modo: 'X', id_dilucion: _id_dilucion },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemMatriz(itemmatriz_Selected, idmatriz_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar la Dilución.");
              }

            }, "json");
        }
      };

      function f_GrabarPatron(){
        var _id_patron = $("#id_patron").val();
        var _item_patron = $("#item_patron").val();
        var _modo = $("#modo_grabarpatron").val();

        var _is_predeterminado = (($("#patron_predeterminado").prop('checked')) ? 1 : 0);
        var _patron_patron = f_CleanInjection($("#patron_patron").val());
        var _patron_media = $("#patron_media").val();
        var _patron_desviacion = $("#patron_desviacion").val();
        var _tiene_dilucion = (($("#chk_patrondilucion").prop('checked')) ? 1 : 0);
        var _patron_matriz = $("#patron_matriz").val();
        var _patron_dilucion = $("#patron_dilucion").val();
        var _patron_observacion = f_CleanInjection($("#patron_observacion").val());

        // Validando datos
          if (_patron_patron == null){
            alert("Debe ingresar el Patrón.");

            return;
          }
          if (_patron_patron.length == 0){
            alert("Debe ingresar el Patrón.");

            return;
          }

          if (_patron_media == null){
            alert("Debe ingresar la Media.");

            return;
          }
          if (_patron_media.length == 0){
            alert("Debe ingresar la Media.");

            return;
          }
          if (_patron_media <= 0){
            alert("La Media ingresada no es correcta.");

            return;
          }

          if (_patron_desviacion == null){
            alert("Debe ingresar la Desviación.");

            return;
          }
          if (_patron_desviacion.length == 0){
            alert("Debe ingresar la Desviación.");

            return;
          }
          if (_patron_desviacion <= 0){
            alert("La Desviación ingresada no es correcta.");

            return;
          }

          if (_tiene_dilucion == 1){
          	if (_patron_matriz == null){
	            alert("Debe seleccionar la Matriz.");

	            return;
	          }
	          if (_patron_matriz.length == 0){
	            alert("Debe seleccionar la Matriz.");

	            return;
	          }

	          if (_patron_dilucion == null){
	            alert("Debe seleccionar la Dilución.");

	            return;
	          }
	          if (_patron_dilucion.length == 0){
	            alert("Debe seleccionar la Dilución.");

	            return;
	          }
          }

        // Grabando datos
          f_LoadingAdminPatrones(1);

          $.post( "apis/backend.php", { accion: "grabar_AbsorcionAtomica_ElementoPatron", modo: _modo, id_elemento: idelemento_Selected, id_patron: _id_patron, is_predeterminado: _is_predeterminado, des_patron: _patron_patron, media: _patron_media, desviacion: _patron_desviacion, tiene_dilucion: _tiene_dilucion, patron_matriz: _patron_matriz, patron_dilucion: _patron_dilucion, patron_observacion: _patron_observacion },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemElemento(itemelemento_Selected, idelemento_Selected);
              }
              else{
              	if(data.estado == 2){
              		alert("El Patrón ingresado ya fue registrado anteriormente.\nPor favor, verificar.");

              		f_LoadingAdminPatrones(0);

              		return;
              	}
              	else{
              		alert("Ocurrió un error al momento grabar el Patrón.");

              		f_LoadingAdminPatrones(0);

              		return;
              	}
              }

              f_LoadingAdminPatrones(0);

              f_cerrarModal("modal_adminpatrones");

            }, "json");
      };

      function f_InactivarPatron(_id_patron, _modo){
      	var _estado = '';

      	if (_modo == 'A'){
      		_estado = 'Inactivar';
      	}
      	else{
      		_estado = 'Activar';
      	}

        if(confirm("¿Está seguro de " + _estado + " el Patrón seleccionado?")){
          $.post( "apis/backend.php", { accion: "eliminar_AbsorcionAtomica_ElementoPatron", modo: _modo, id_patron: _id_patron },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemElemento(itemelemento_Selected, idelemento_Selected);
              }
              else{
                alert("Ocurrió un error al momento de inactivar la Patrón.");
              }

            }, "json");
        }
      };

      function f_EliminarPatron(_id_patron){
        if(confirm("¿Está seguro de Eliminar el Patrón seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_AbsorcionAtomica_ElementoPatron", modo: 'X', id_patron: _id_patron },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemElemento(itemelemento_Selected, idelemento_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar la Patrón.");
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
	</body>
</html>