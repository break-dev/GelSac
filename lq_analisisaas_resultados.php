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

		<title><?php echo $nom_app; ?> | LQ - Análisis de AAS | Importación de Resultados</title>

		<script type="text/javascript">
			let itemrack_Selected = 0;
      let idrack_Selected = 0;
      let item_patronadded = 0;
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
											<h6 style="font-size: 14px;">Por Fechas</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadRacks();">

											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadRacks();">
										</div>
									</div>
								</div>

								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Cód. Interno</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="barcode_lectura" type="text" class="form-control" style="font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase;">

											<img src="<?php echo $barcode_laser ?>" style="width: 45px; margin-left: -60px; margin-right: 10px;">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="padding: 0px;">
							<div id="div_racks" class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-8 col-sm-8 col-xs-12">
												<div class="d-flex">
													<h5>Lista de Racks</h5>

													<div id="wt_rack" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px; border-top-right-radius: 15px;">
						        				Rack
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;" hidden>
						        				Observación
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_racks">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_muestras" class="col-md-5 col-sm-5 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<div class="col-md-8 col-sm-8 col-xs-12">
													<div class="d-flex">
	                        	<h5>Muestras de: </h5>
														<h5 id="lbl_titulomuestras" style="margin-left: 5px; color: #337ab7;"></h5>

														<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>
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
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N° Vaso
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Muestra
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Peso (g)
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Es Reanálisis
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;" hidden>
						        				Tipo Réplica
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;" hidden>
						        				Cód. Interno
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Cód. Interno
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Material
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Análisis
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_detalle">
						        		
						        	</tbody>
						        </table>

						        <div class="d-flex" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px; background-color: #737373; color: #ffffff; margin-top: -10px;">
						        	<label style="width: 35%; padding-top: 7px; font-size: 14px;">
						        		Orden Elementos:
						        	</label>

						        	<input id="orden_elementos" type="text" class="form-control" style="font-size: 14px; text-align: center; font-weight: bold;" disabled>
						        </div>
									</div>
								</div>

								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px; margin-top: 5px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
                      	<h5>Lista de Patrones</h5>

												<div id="wt_patrones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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
						        		<tr style="font-size: 14px;">
						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Elemento
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Patrón
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Dilución
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Peso (g)
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_patrones">

						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_lecturas" class="col-md-5 col-sm-5 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<div class="col-md-7 col-sm-7 col-xs-12">
													<div class="d-flex">
	                        	<h5>Lecturas en Equipo</h5>

														<div id="wt_lecturas" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
															<img src="<?php echo $img_waiting ?>" style="width: 20px;">
															<label style="font-style: italic;"> Cargando datos...</label>
														</div>
													</div>
                        </div>

												<div class="col-md-5 col-sm-5 col-xs-12">
													<button id="btn_ImportarLecturas" class="btn btn-info load_esquema" type="button" onclick="f_ImportarLecturas();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
							              <b><i class="bi bi-cloud-arrow-down" style="font-size: 16px;"></i> Importar Lecturas</b>
							            </button>

							            <div id="div_ImportarLecturas" style="text-align: center; display: none;">
							            	<img src="<?php echo $downloading ?>" style="width: 250px; height: 40px;">
							            	<label style="margin-top: -10px; font-size: 12px;">
							            		<i>Importando Lecturas...</i>
							            	</label>
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
						        				N°
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Muestra
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;">
						        				Elemento
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 170px; border-top-right-radius: 15px;">
						        				Resultado
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_esquema">
						        		
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
		<div class="modal fade modal-dialog-scrollable" id="modal_ordenelementos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_ordenelementosLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_ordenelementosLabel">Ordenar Elementos</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 10px;">
							<table class="table table-bordered table-hover">
			        	<thead>
			        		<tr style="font-size: 14px;">
			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
			        				Orden
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 85%; border-top-right-radius: 15px">
			        				Elementos
			        			</th>
			        		</tr>
			        	</thead>

			        	<tbody id="tbl_ordenelementos">
			        		
			        	</tbody>
			        </table>
						</div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-warning" onclick="f_GrabarOrdenElementos();">Confirmar</button>
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
					$("#nv_titulo").html('| LQ - Análisis de AAS | Importación de Resultados');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadRacks();

				// Agrega el Focus
					document.getElementById("barcode_lectura").focus();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadRacks(){
        var _html = '';
        var d = 1;

        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var cod_interno = $("#barcode_lectura").val();

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

				// Cargando Lista de Racks
	        $("#tbl_racks").html('');
	        $("#tbl_detalle").html('');
	        $("#lbl_titulomuestras").html('');

	        f_LoadingRacks(1);

	        $.post( "apis/backend.php", { accion: "get_AnalisisAAS_RacksResultados", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, cod_interno: cod_interno }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_racks").html(data.html);

	            	itemrack_Selected = 1;
								idrack_Selected = data.id_rack;

								f_LoadItemDetalle(itemrack_Selected, idrack_Selected);
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingRacks(0);

	          }, "json");
    	};

      function f_ColorSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_racks tr").each(function () {
          $("#tr_item_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_item_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulomuestras").html($("#lbl_td_1_" + _item).html().trim());
      };

      function f_LoadItemDetalle(_item, _id_rack){
      	item_patronadded = 0;
      	itemrack_Selected = _item;
        idrack_Selected = _id_rack;

        // Pinta selección
          f_ColorSelected(_item);

        // Limpia objetos de búsqueda
          $("#th_buscarmuestra_1").val('');

        // Cargando Resultados Importados
          // f_LoadEsquema();

        // Cargando datos de Muestras
          f_LoadingDetalle(1);
          f_LoadingPatrones(1);

          $("#tbl_detalle").html('');
          $("#tbl_patrones").html('');

          $.post( "apis/backend.php", { accion: "get_AnalisisAAS_RackListaMuestrasEsquemasResultados", id_rack: _id_rack }, 
            function( data ) {
              if(data.estado == 1){
                // Actualiza la tabla de Muestras
                  $("#tbl_detalle").html(data.html_m);
                  $("#tbl_patrones").html(data.html_p);
                  $("#orden_elementos").val(data.orden_elementos);
              }

              f_LoadingDetalle(0);
              f_LoadingPatrones(0);

            }, "json");
      };

      function f_EsquemaSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
	        $("#tbl_esquema tr").each(function () {

	        	// Determina si es Patrón o Muestra
		        	if ($("#td_itemesquema_5_" + i).html().trim() == 1){
		        		$("#tr_itemesquema_" + i).css('background-color', '#737373');
		        		$("#tr_itemesquema_" + i).css('color', '#ffffff');
		        	}
		        	else{
		        		$("#tr_itemesquema_" + i).css('background-color', '#E4EAF1');
		        		$("#tr_itemesquema_" + i).css('color', '#212529');
		        	}

	          i += 1;
	        });

	        // Seteando item seleccionado
	          $("#tr_itemesquema_" + _item).css('background-color', '#FFF587');
	          $("#tr_itemesquema_" + _item).css('color', '#212529');
      }

      function f_ImportarLecturas(){
      	if (!confirm("¿Desea iniciar el proceso de importación de lecturas?")){
      		return;
      	}

      	// Seteando botones
      		$("#btn_ImportarLecturas").hide();
      		$("#div_ImportarLecturas").show();

      	// Seteando Rack para importación
      		$.post( "apis/backend.php", { accion: "set_AnalisisAAS_RackImportarResultados", cod_equipo: 1, id_rack: idrack_Selected },
	          function( data ) {
	            if(data.estado == 1){
	            	// Inicio del Proceso de Confirmación de Importación
// MAX - CREAR EL PROCESO DE CONFIRMACION AUTOMATICO
	            }
	            else{
	              alert("Ocurrió un error al momento de Importar las Lecturas.");
	            }

          }, "json");
      }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
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

			function f_LoadingRacks(_is_show){
				if (_is_show == 1){
					$("#wt_rack").show();
				}
				else{
					$("#wt_rack").hide();
				}
			}

			function f_LoadingDetalle(_is_show){
				if (_is_show == 1){
					$("#wt_detalle").show();
				}
				else{
					$("#wt_detalle").hide();
				}
			}

			function f_LoadingEsquema(_is_show){
				if (_is_show == 1){
					$("#wt_lecturas").show();

					$(".load_esquema").prop('disabled', true);
				}
				else{
					$("#wt_lecturas").hide();

					$(".load_esquema").prop('disabled', false);
				}
			}

			function f_LoadingGenerateArchivo(_is_show){
				if (_is_show == 1){
					$("#wt_generatefile").show();
				}
				else{
					$("#wt_generatefile").hide();
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

			$("#modal_adminracks").on('shown.bs.modal', function(){
      	$("#nombre_rack").focus();
    	});

			$("#modal_addmuestras").on('shown.bs.modal', function(){
      	$("#addmuestra_barcode").focus();
    	});

    	$("#modal_getpeso").on('shown.bs.modal', function(){
      	$("#txt_getpeso").focus();
    	});

    	$("#modal_getpesopatron").on('shown.bs.modal', function(){
      	$("#txt_getpesopatron").focus();
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
			function f_GrabarOrdenElementos(){
				// Recorre la lista y crea el nuevl orden
					var t = 1;
					var orden_elementos = '';

					$("#tbl_ordenelementos tr").each(function () {
	          orden_elementos += $("#td_orden_" + t).html().trim() + ' - ';

	          t += 1;
	        });

	        if (orden_elementos.length > 0){
	        	orden_elementos = orden_elementos.substring(0, orden_elementos.length - 3);
	        }

	      // Guarda el nuevo orden
	      	$.post( "apis/backend.php", { accion: "grabar_AnalisisAAS_RacksOrdenElementos", id_rack: idrack_Selected, orden_elementos: orden_elementos },
            function( data ) {
              if(data.estado == 1){
              	$("#orden_elementos").val(orden_elementos);

                f_cerrarModal('modal_ordenelementos');
              }
              else{
                alert("Ocurrió un error al momento de Ordenar los Elementos.");
              }

            }, "json");
			}

			function f_GuardaEsquema(_reload){
				var e = 1;
				var _html = '';

				// Obteniendo la fecha y hora del registro
					var fechahora_registro = '';
					var usuario_registro = '<?php echo $_SESSION["usu_usuario"]; ?>';

					var _time = new Date();
        	_time = _time.getHours().toString().padStart(2, '0') + ":" + _time.getMinutes().toString().padStart(2, '0');

          fechahora_registro = '<?php echo $g_date; ?>' + ' ' + _time;

				// Arma html
					$("#tbl_esquema tr").each(function () {
						_html += "(" + idrack_Selected + ', ';
						_html += e + ', ';
						_html += "'" + $(this).find("td").eq(2).html().trim() + "', ";
						_html += (($(this).find("td").eq(3).html().trim().length == 0) ? 'NULL' : "'" + $(this).find("td").eq(3).html().trim() + "'") + ", ";
						_html += (($(this).find("td").eq(4).html().trim().length == 0) ? 'NULL' : "'" + $(this).find("td").eq(4).html().trim() + "'") + ", ";
						_html += $(this).find("td").eq(5).html().trim() + ', ';
						_html += "'" + $(this).find("td").eq(6).html().trim() + "', ";
						_html += $(this).find("td").eq(7).html().trim() + ", ";
						_html += $(this).find("td").eq(8).html().trim() + ", ";
						_html += "'" + fechahora_registro + "', ";
						_html += "'" + usuario_registro + "'), ";

	          e ++;
	        });

        // Validando datos
					if (_html.length == 0){
						return;
					}
					else{
						_html = _html.substring(0, _html.length - 2);
					}

				// Guarda esquema
					f_LoadingEsquema(1);

					$.post( "apis/backend.php", { accion: "grabar_AnalisisAAS_RacksEsquemas", id_rack: idrack_Selected, esquema: _html },
            function( data ) {
              if(data.estado == 1){
              	if (_reload == 1){
              		f_LoadEsquema(idrack_Selected);
              	}
              }
              else{
                alert("Ocurrió un error al momento de Guardar el Esquema.");
              }

              f_LoadingEsquema(0);

            }, "json");
			}

			function f_CerrarEsquema(_is_cierre){
				if (_is_cierre == 1){
					if (!confirm("¿Está seguro de cerrar el Esquema del Rack seleccionado?")){
	      		return;
	      	}
				}

      	// Guardando datos
      		$.post( "apis/backend.php", { accion: "cerrar_AnalisisAAS_Esquema", id_rack: idrack_Selected, is_cierre: _is_cierre },
	          function( data ) {
	            if(data.estado == 1){
	            	// Actualizar estado del Rack
		            	var html = $("#td_1_" + itemrack_Selected).html();

		            	if (_is_cierre == 0){
		            		html = html.replace('CERRADO', '');

		            		$("#td_1_" + itemrack_Selected).html(html);
		            	}
		            	else{
		            		html += '<br><label style="color: #FF5F5D; font-weight: bold;">CERRADO</label>';

		            		$("#td_1_" + itemrack_Selected).html(html);
		            	}

		            	$("#td_israckcerrado_" + itemrack_Selected).val(_is_cierre);

		            // Actualiza botones de Cierre
		            	if (_is_cierre == 1){
				        		$("#btn_GenerateFile").show();
				        		$("#btn_CerrarEsquema").hide();
				        		$("#btn_ReabrirCierre").show();
				        	}
				        	else{
				        		$("#btn_GenerateFile").hide();
				        		$("#btn_CerrarEsquema").show();
				        		$("#btn_ReabrirCierre").hide();
				        	}

				        	rack_IsCerrado = _is_cierre;
	            }
	            else{
	              alert("Ocurrió un error al momento de eliminar el Modelo.");
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
	</body>
</html>