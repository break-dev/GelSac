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
    <link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

    <title><?php echo $nom_app; ?> | LQ - Configuración de Métodos de Ensayo</title>

    <script type="text/javascript">

    </script>
  </head>

  <body class="bg-light" onload="f_Init();" style="zoom: 80%;">
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

        <!-- Modal (Filtros Lateral) -->
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
                        <h6 style="font-size: 14px;">Por Tipo Cliente</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                        <select id="filtro_tipocliente" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
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
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                    <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                      <div class="row" style="padding-left: 10px; padding-right: 10px;">
                        <h6 style="font-size: 14px;">Por Documento / Razón Social</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                        <select id="filtro_listatipo" class="form-select" style="text-align: left; font-size: 14px; width: 50%; margin-right: 5px;" onchange="f_CleanTxtTipo();">
                          <option selected value="">Elija una opción...</option>
                          <option value="1">Documento</option>
                          <option value="2">Razón Social</option>
                        </select>

                        <input id="filtro_tipo" type="text" class="form-control" style="font-size: 14px;" onblur="f_LoadResultados();">
                      </div>
                    </div>
                  </div>

                  <!-- <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                    <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                      <div class="row" style="padding-left: 10px; padding-right: 10px;">
                        <h6 style="font-size: 14px;">Por Cód. Cliente</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                        <input id="filtro_codcliente" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase;" onblur="f_LoadResultados();">
                      </div>
                    </div>
                  </div> -->
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

            <div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px; padding: 0px;">
							<div id="div_elementos" class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
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

							<div id="div_metodos" class="col-md-9 col-sm-9 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-8 col-sm-8 col-xs-12">
												<div class="d-flex">
													<h6>Métodos Configurados para: </h6>
													<h6 id="lbl_titulometodos" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h6>

													<div id="wt_metodos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>

											<div class="col-md-4 col-sm-4 col-xs-12">
												<button class="btn btn-info" type="button" onclick="f_AdminMetodos('x');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
						              <b>+ Nuevo Método de Ensayo</b>
						            </button>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -20px; overflow-x: scroll; width: 100%;">
										<table class="table table-bordered table-hover">
						        	<thead>
                        <tr style="font-size: 14px;">
                          <th rowspan="2" style="border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; width: 40px; text-align: center;">
                            N°
                          </th>

                          <th colspan="8" style="background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                            Método Ensayo
                          </th>

                          <th rowspan="2" style="border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                            Unidad Medida
                          </th>

                          <th rowspan="2" style="border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                            Cant. Decimales
                          </th>

                          <th colspan="2" style="background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                            Límites
                          </th>
                          
                          <th colspan="2" style="background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                            Vigencia
                          </th>

                          <th rowspan="2" style="border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                          	Estado
                          </th>

                          <th rowspan="2" style="border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; border-top-right-radius: 15px; min-width: 100px;">
                          	Acción
                          </th>
                        </tr>

                        <tr style="font-size: 14px;">
                        	<th style="background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 100px;">
                            Código
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 80px;">
                            Condición
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 80px; background-color: #343840; font-size: 12px;">
                            Concentrado Geoquímico
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 80px; background-color: #343840; font-size: 12px;">
                            Newmont
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 80px; background-color: #343840; font-size: 12px;">
                            Volumetría
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 80px; background-color: #343840; font-size: 12px;">
                            ICP
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 80px; background-color: #343840; font-size: 12px;">
                            Carbón Activado
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 80px; background-color: #343840; font-size: 12px;">
                            Soluble En Cianuro
                          </th>

                          <th style="background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                            Mínimo
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center;">
                            Máximo
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 90px;">
                            Desde
                          </th>

                          <th style=" background-color: #404040; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; text-align: center; min-width: 90px;">
                            Hasta
                          </th>
                        </tr>
                      </thead>

                      <tbody id="tbl_detalle" style="overflow: scroll">

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
		<div class="modal fade modal-dialog-scrollable" id="modal_adminmetodos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminmetodosLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_adminmetodosLabel"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Método:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cbo_codmetodo" class="form-select">
                	<option selected = "true" value="">Elija una opción...</option>

                	<?php

                	$q_metodos = "SELECT Id,
						                           cod_metodo
						                      FROM tbconfig_metodosensayo
						                    ORDER BY orden";

						      if ($res_metodos = mysqli_query($enlace, $q_metodos)) {
						        if (mysqli_num_rows($res_metodos) > 0) {
						          while($row_metodos = mysqli_fetch_assoc($res_metodos)) {
						          	?>

						            <option value="<?php echo $row_metodos["Id"] ?>"><?php echo $row_metodos["cod_metodo"] ?></option>

						            <?php
						          }
						        }
						      }

                	?>
                </select>
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Condición:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cbo_codtipometodo" class="form-select">
                	<option selected value="0">No Acreditado</option>
                   <option value="1">Acreditado</option>
                </select>
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Unidad Medida:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cbo_unidadmedida" class="form-select">
                	<option selected = "true" value="">Elija una opción...</option>

                	<?php
                    $q_unidadmedida = "SELECT Id,
									                          	abv
                                        FROM tbconfig_unidadmedida
                                       WHERE estado = 'A'
                                      ORDER BY 2";

	                    if ($res_unidadmedida = mysqli_query($enlace, $q_unidadmedida)) {
								        if (mysqli_num_rows($res_unidadmedida) > 0) {
								          while($row_unidadmedida = mysqli_fetch_assoc($res_unidadmedida)) {
								          	?>

								            <option value="<?php echo $row_unidadmedida["Id"] ?>"><?php echo $row_unidadmedida["abv"] ?></option>

								            <?php
								          }
								        }
								      }
                  ?>

                </select>
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Cant. Decimales:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
                <input id="num_decimales" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" value="0" min="0">
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Límite Mínimo:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
                <input id="limite_minimo" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" value="0" min="0">
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Límite Máximo:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
                <input id="limite_maximo" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" value="0" min="0">
							</div>
						</div>

						<div class="row" style="padding: 5px; background-color: #404040; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px;">
							<label style="color: #ffffff; padding-bottom: 5px;">
                Vigencia
              </label>
						</div>

						<div class="d-flex">
							<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
								<div class="d-flex">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
		                Desde:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
		                <input id="vigencia_desde" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;" value="<?php echo $g_date; ?>">
									</div>
								</div>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
								<div class="d-flex">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
		                <div class="form-check">
										  <input type="checkbox" id="chk_hasta" value="chk_hasta" class="chk_hasta" onchange="f_CheckHasta();">
		            			<label for="chk_hasta">Hasta</label>
										</div>
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
		                <input id="vigencia_hasta" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center;" value="<?php echo $g_date; ?>" disabled>
									</div>
								</div>
							</div>
						</div>

            <!-- Campos usados al momento de grabar los datos -->
            <input id="id_metodo" type="hidden">
            <input id="modo_grabar" type="hidden">

			      <div class="modal-footer">
			      	<div id="wt_adminmetodos" class="" style="font-size: 12px; text-align: center; display: none;">
								<img src="<?php echo $img_waiting ?>" style="width: 20px;">
								<label style="font-style: italic;"> Grabando datos...</label>
							</div>

			        <button id="btn_cerrarmetodo" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			        <button id="btn_grabarmetodo" type="button" class="btn btn-primary" onclick="f_GrabarMetodoEnsayo();">Grabar</button>
			      </div>
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
        $("#nv_titulo").html('| LQ - Configuración de Métodos de Ensayo');

          // Cargando listas generales

          // Carga el detalle de información
        f_LoadElementos();
      }
    </script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadElementos(){
        var _html = '';
        var d = 1;
        var id_elemento = 0;
        var cod_elemento = '';

        // Obteniendo filtros

        // Validando datos

				// Cargando Lista de Racks
	        $("#tbl_elementos").html('');

	        f_LoadingElementos(1);

	        $.post( "apis/backend.php", { accion: "get_ListaElementosQuimicos", modulo: 0 }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$.each( data.registros, function( key, val ) {
	            		if (d == 1){
	            			id_elemento = val.Id;
	            			cod_elemento = val.abv;
	            		}

	            		_html += '<tr id="tr_item_E_' + d + '" style="cursor: pointer; font-size: 14px;" onclick="f_LoadItemElemento(' + d + ', ' + val.Id + ')">';

									_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
									_html += '   ' + d;
									_html += '    <input id="td_idelemento_' + d + '" type="hidden" value="' + val.Id + '">';
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

								f_LoadItemElemento(1, id_elemento);
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingElementos(0);

	          }, "json");

    	};

    	function f_LoadItemElemento(_item, _id_elemento){
    		// Pinta selección
          f_ColorSelected(_item);

        // Datos elementos
          itemelemento_Selected = _item;
          idelemento_Selected = _id_elemento;
         	codelemento_Selected = $("#td_idelemento_" + _item).html().trim();

        // Titulo
          $("#titulo_elemento").html(codelemento_Selected);

        // Cargando detalles
    			$("#tbl_detalle").html('');

          $.post( "apis/backend.php", { accion: "get_ListaMetodosEnsayoxElemento", cod_elemento: idelemento_Selected }, 
            function( data ) {
              if(data.estado == 1){
                $("#tbl_detalle").html(data.html);
              }
              else{
                alert("No se encontraron resultados para la muestra seleccionada");
              }

            }, "json");
    	}

      function f_ColorSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_elementos tr").each(function () {
          $("#tr_item_E_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_item_E_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulometodos").html($("#td_item_E_" + _item).html().trim());
      };

    	function f_AdminMetodos(_x, _cod_metodo, _is_acreditado, _cod_unidadmedida, _num_decimales, _limite_minimo, _limite_maximo, _vigencia_desde, _vigencia_hasta){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_x != 'x'){
            tipo = "E";
            titulo = 'Editar Método de Ensayo para: <b style="color: #FA7F08; font-size: 18px;">' + codelemento_Selected + '</b>';
          }
          else{
            tipo = "N";
            titulo = 'Nuevo Método de Ensayo para: <b style="color: #FA7F08; font-size: 18px;">' + codelemento_Selected + '</b>';
          }

          $("#modal_adminmetodosLabel").html(titulo);

        // Identificando el tipo de grabación
          $("#modo_grabar").val(tipo);

        // Cargando datos
          $("#id_metodo").val(_x);

          if (_x != 'x'){
          	$("#cbo_codmetodo").val(_cod_metodo);
            $("#cbo_codtipometodo").val(_is_acreditado);
            $("#cbo_unidadmedida").val(_cod_unidadmedida);
            $("#num_decimales").val(_num_decimales);
            $("#limite_minimo").val(_limite_minimo);
            $("#limite_maximo").val(_limite_maximo);
            $("#vigencia_desde").val(_vigencia_desde);
            $("#vigencia_hasta").val(((_vigencia_hasta.length == 0) ? '<?php echo $g_date; ?>' : _vigencia_hasta));
            $("#chk_hasta").prop('checked', ((_vigencia_hasta.length == 0) ? false : true));
            $("#vigencia_hasta").prop('disabled', ((_vigencia_hasta.length == 0) ? true : false));
          }
          else{
            $("#cbo_codmetodo").val('');
            $("#cbo_codtipometodo").val(0);
            $("#cbo_unidadmedida").val('');
            $("#num_decimales").val(0);
            $("#limite_minimo").val(0);
            $("#limite_maximo").val(0);
            $("#vigencia_desde").val('<?php echo $g_date; ?>');
            $("#vigencia_hasta").val('<?php echo $g_date; ?>');
            $("#chk_hasta").prop('checked', false);
            $("#vigencia_hasta").prop('disabled', true);
          }

        // Abre modal
        	f_OpenModal('modal_adminmetodos');
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingElementos(_is_show){
				if (_is_show == 1){
					$("#wt_elementos").show();
				}
				else{
					$("#wt_elementos").hide();
				}
			}

			function f_LoadingAdminMetodos(_is_show){
				if (_is_show == 1){
					$("#wt_adminmetodos").show();

					$("#btn_cerrarmetodo").prop('disabled', true);
					$("#btn_cerrarmetodo").css('background-color', '#C2C0A6');
					$("#btn_grabarmetodo").prop('disabled', true);
					$("#btn_grabarmetodo").css('background-color', '#C2C0A6');
				}
				else{
					$("#wt_adminmetodos").hide();

					$("#btn_cerrarmetodo").prop('disabled', false);
					$("#btn_cerrarmetodo").css('background-color', '');
					$("#btn_grabarmetodo").prop('disabled', false);
					$("#btn_grabarmetodo").css('background-color', '');
				}
			}

			function f_CheckHasta(){
        if ($("#chk_hasta").prop('checked')){
          $("#vigencia_hasta").prop('disabled', false);
        }
        else{
          $("#vigencia_hasta").prop('disabled', true);
        }
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
    	function f_GrabarMetodoEnsayo(){
    		var _modo_grabar = $("#modo_grabar").val();
        var _id_detalle = ((_modo_grabar == 'N') ? 0 : $("#id_metodo").val());

        var _cod_metodo = $("#cbo_codmetodo").val();
        var _des_metodo = $("#cbo_codmetodo option:selected").text();
        var _cod_tipometodo = $("#cbo_codtipometodo").val();
        var _des_tipometodo = $("#cbo_codtipometodo option:selected").text();
        var _cod_unidadmedida = $("#cbo_unidadmedida").val();
        var _des_unidadmedida = $("#cbo_unidadmedida option:selected").text();
        var _num_decimales = $("#num_decimales").val();
        var _limite_minimo = $("#limite_minimo").val();
        var _limite_maximo = $("#limite_maximo").val();
        var _vigencia_desde = $("#vigencia_desde").val();
        var _chk_hasta = (($("#chk_hasta").prop('checked')) ? 1 : 0);
        var _vigencia_hasta = ((_chk_hasta == 0) ? 'NULL' : $("#vigencia_hasta").val());

        // Validando datos
          if (_cod_tipometodo == null){
            alert("Debe seleccionar el Tipo de Método");

            return;
          }
          if (_cod_tipometodo.length == 0){
            alert("Debe seleccionar el Tipo de Método");

            return;
          }

          if (_cod_metodo == null){
            alert("Debe seleccionar el Método");

            return;
          }
          if (_cod_metodo.length == 0){
            alert("Debe seleccionar el Método");

            return;
          }

          if (_cod_unidadmedida == null){
            alert("Debe seleccionar la Unidad de Medida");

            return;
          }
          if (_cod_unidadmedida.length == 0){
            alert("Debe seleccionar la Unidad de Medida");

            return;
          }

          if (_num_decimales == null){
            alert("Debe indicar la cantidad de decimales.");

            return;
          }
          if (_num_decimales.length == 0){
            alert("Debe indicar la cantidad de decimales.");

            return;
          }
          if (_num_decimales < 0){
            alert("La cantidad de decimales ingresada no es correcta.");

            return;
          }

          if (_limite_minimo == null){
            alert("Debe indicar el Límite Mínimo.");

            return;
          }
          if (_limite_minimo.length == 0){
            alert("Debe indicar el Límite Mínimo.");

            return;
          }
          if (_limite_minimo < 0){
            alert("El Límite Mínimo ingresado no es correcto.");

            return;
          }

          if (_limite_maximo == null){
            alert("Debe indicar el Límite Máximo.");

            return;
          }
          if (_limite_maximo.length == 0){
            alert("Debe indicar el Límite Máximo.");

            return;
          }
          if (_limite_maximo < 0){
            alert("El Límite Máximo ingresado no es correcto.");

            return
          }

          if (parseFloat(_limite_maximo) <= parseFloat(_limite_minimo)){
            alert('El Límite Mínimo no puede ser mayor o igual al Límite Máximo".\nPor favor, verificar.');

            return;
          }

          if (_vigencia_desde == null){
            alert('La fecha de vigencia "Desde" no es correcta.\nPor favor, verificar.');

            return;
          }
          if (_vigencia_desde.length == 0){
            alert('La fecha de vigencia "Desde" no es correcta.\nPor favor, verificar.');

            return;
          }

          if (_chk_hasta == 1){
            if (_vigencia_hasta == null){
              alert('La fecha de vigencia "Hasta" no es correcta.\nPor favor, verificar.');

              return;
            }
            if (_vigencia_hasta.length == 0){
              alert('La fecha de vigencia "Hasta" no es correcta.\nPor favor, verificar.');

              return;
            }

            if (_vigencia_hasta < _vigencia_desde){
              alert('La fecha de vigencia "Desde" no puede ser mayor a la fecha "Hasta".\nPor favor, verificar.');

              return;
            }
          }

        // Grabando datos
        	f_LoadingAdminMetodos(1);

          $.post( "apis/backend.php", { accion: "grabar_MetodosEnsayo", modo_grabar: _modo_grabar, cod_elemento: idelemento_Selected, id_detalle: _id_detalle, cod_metodo: _cod_metodo, cod_tipometodo: _cod_tipometodo, cod_unidadmedida: _cod_unidadmedida, des_unidadmedida: _des_unidadmedida, num_decimales: _num_decimales, limite_minimo: _limite_minimo, limite_maximo: _limite_maximo, vigencia_desde: _vigencia_desde, tiene_hasta: _chk_hasta, vigencia_hasta: _vigencia_hasta },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemElemento(itemelemento_Selected, idelemento_Selected);
              }
              else{
            		if (data.estado == 2){
            			alert("El método eleccionado: " + _des_metodo + " ya fue registrado anteriormente.\n\nPor favor, revisar.");

              		f_LoadingAdminMetodos(0);

              		return;
            		}
              	else{
              		alert("Ocurrió un error al momento de grabar el nuevo Método de Ensayo");

              		f_LoadingAdminMetodos(0);

              		return;
              	}
              }

              f_LoadingAdminMetodos(0);

              f_cerrarModal("modal_adminmetodos");

            }, "json");
    	}

      function f_CambiarEstadoMetodo(_id_metodo, _modo){
      	var _estado = '';

      	if (_modo == 'A'){
      		_estado = 'Inactivar';
      	}
      	else{
      		_estado = 'Activar';
      	}

        if(confirm("¿Está seguro de " + _estado + " el Método seleccionado?")){
          $.post( "apis/backend.php", { accion: "eliminar_MetodosEnsayo", modo: _modo, id_metodo: _id_metodo },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemElemento(itemelemento_Selected, idelemento_Selected);
              }
              else{
                alert("Ocurrió un error al momento de inactivar el Método.");
              }

            }, "json");
        }
      };

      function f_EliminarMetodo(_id_metodo, _modo){
        if(confirm("¿Está seguro de Eliminar la Dilución seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_MetodosEnsayo", modo: 'X', id_metodo: _id_metodo },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemElemento(itemelemento_Selected, idelemento_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Método.");
              }

            }, "json");
        }
      };
		</script>
  </body>
</html>