<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares_new.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

  $id_rol = $_SESSION["cod_rol"];

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
	 
	  <!--Bootstrap Stylesheet [ REQUIRED ]-->
	  <link href="template/css/bootstrap.min.css" rel="stylesheet">

	  <!--Nifty Stylesheet [ REQUIRED ]-->
	  <link href="template/css/nifty.min.css" rel="stylesheet">

	  <!--Nifty Premium Icon [ DEMONSTRATION ]-->
	  <link href="template/css/demo/nifty-demo-icons.min.css" rel="stylesheet">

	  <!--Bootstrap Stylesheet [ REQUIRED ]-->
	  <link href="template/css/template_style.css" rel="stylesheet">

	  <!-- Íconos -->
	  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

		<!-- Select2 -->
		<link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

		<title><?php echo $nom_app; ?> | Preparación Mecánica - Cierre de Humedad</title>

		<!-- Jq Toast -->
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css" integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

		<script type="text/javascript">
			let itemrack_Selected = 0;
      let idrack_Selected = 0;
      let rack_tieneiniciosecado_selected = 0;
			let rack_tienefinsecado_selected = 0;
      let rack_horassecado_selected = 0;
      let total_chkcierre = 0;
		</script>
	</head>

	<body onload="f_Init();" style="zoom: 85%;">
		<div id="container" class="effect aside-float aside-bright mainnav-lg">

			<!-- Llamando a Navbar -->
	  	<?php echo $navbar_maintop_new; ?>

	  	<!-- Llamando a Menu de opciones -->
	  	<?php echo $navbar_mainleft_new; ?>

	  	<div class="boxed">
	  		<div id="content-container" style="background-color: #816951;">

	  			<div id="page-head" style="border-top: solid #EFB810 4px;">
	                    
	          <!--Page Title-->
	          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	          <div id="page-title">
	              <h1 class="page-header text-overflow"> Preparación Mecánica - Cierre de Humedad</h1>
	          </div>
	          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	          <!--End page title-->


	          <!--Breadcrumb-->
	          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	          <ol class="breadcrumb">
	            <li><a>Operaciones</a></li>
              <li><a>Determinación Humedad</a></li>
	            <li class="active">Cierre Resultado</li>
	          </ol> 
	          <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	          <!--End breadcrumb-->

	      	</div>

	      	<div id="page-content" style="padding: 10px;">
	          <div class="panel">
	            <div class="panel-body">

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
													<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadResultados();">

													<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadResultados();">
												</div>
											</div>
										</div>

										<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
											<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
												<div class="row" style="padding-left: 10px; padding-right: 10px;">
													<h6 style="font-size: 14px;">Estado</h6>
												</div>

												<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
													<hr style="border-color: #D9D9D9;"/>
												</div>

												<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
													<select id="filtro_estado" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
														<option selected value="">Elija una opción...</option>

														<?php
														$e = 0;

								            while($e <= 1){
								              ?>

								              <option value="<?php echo $e; ?>"><?php echo (($e == 0) ? 'Pendientes' : 'Cerrados'); ?></option>

								              <?php

								              $e ++;
								            }

														?>

													</select>
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
													<input id="barcode_lectura" type="text" class="form-control" style="font-size: 14px; font-weight: bold; text-align: center;">

													<img src="<?php echo $barcode_laser ?>" style="width: 45px; margin-left: -60px; margin-right: 10px;">
												</div>
											</div>
										</div>

										<img id="img_evidencia" style="width: 250px; display: none;">
									</div>
								</div>

								<div class="row" style="padding: 0px;">
									<div class="col-md-9 col-sm-9 col-xs-12" style="padding: 0px; padding-left: 10px;padding-right:25px; padding-bottom: 5px;">
										<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px; ">
											<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
												<div class="d-flex" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
													<div class="col-md-8 col-sm-8 col-xs-12" style="padding: 0px;">
														<div class="d-flex">
															<h5>Lista de Muestras</h5>

															<div id="wt_listamuestras" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 7px;">
																<label style="font-style: italic; margin-left: 5px;"> Cargando datos...</label>
																<img src="<?php echo $img_waiting ?>" style="width: 20px; margin-top: -5px;">
															</div>
														</div>
													</div>

													<div class="col-md-4 col-sm-4 col-xs-12" style="text-align: right;">
														<div class="d-flex">
															<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 50%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
									              <b>Exportar a Excel</b>
									            </button>

															<button class="btn btn-danger" type="button" onclick="f_CierreAnalisis();" style="width: 50%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px; margin-left: 10px;">
									              <b>Cierre de Análisis</b>
									            </button>

									            <div id="wt_cierre" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 7px; width: 200px;">
																<label style="font-style: italic; margin-left: 5px;"> Cargando datos...</label>
																<img src="<?php echo $img_waiting ?>" style="width: 20px; margin-top: -5px;">
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
								        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
								        				N°
								        			</th>

								        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Muestra
								        			</th>

								        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Tara (Peso Bandeja)
								        			</th>

								        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Peso Húmedo <br> + Tara
								        			</th>

								        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Peso Seco <br> + Tara
								        			</th>

								        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Dif. Pesos
								        			</th>

								        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				% Humedad
								        			</th>

								        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				% Promedio
								        			</th>

								        			<th colspan="6" style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
								        				Cierre
								        			</th>
								        		</tr>

								        		<tr style="font-size: 14px;">
								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
								        				Fecha Hora Ingreso Planta
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;">
								        				Cód. Interno
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 70px;">
								        				Item
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Sel.
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Sel.
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
								        				Fecha Hora
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
								        				Usuario
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
								        				Informe
								        			</th>

								        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
								        				Evidencia
								        			</th>
								        		</tr>
								        	</thead>

								        	<tbody id="tbl_muestras">
								        		
								        	</tbody>
								        </table>
											</div>
										</div>
									</div>

									<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 0px; padding-left: 5px;padding-right: 10px;">
										<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
											<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
												<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
													<div class="d-flex">
														<h5>Pendientes de Recepción Laboratorio</h5>

														<div id="wt_listapendientes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 7px;">
															<label style="font-style: italic; margin-left: 5px;"> Cargando datos...</label>
															<img src="<?php echo $img_waiting ?>" style="width: 20px; margin-top: -5px;">
														</div>
													</div>
												</div>
											</div>

											<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; width: 100%; overflow-x: scroll;">
												<div class="d-flex" style="font-size: 14px; margin-top: -15px;">
													<label>Total Muestras: </label>
													<label id="lbl_totalpendientes" style="color: #FF5F5D; margin-left: 5px; font-weight: bold;">0</label>
												</div>

												<table class="table table-bordered table-hover">
								        	<thead>
								        		<tr style="font-size: 14px;">
								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
								        				N°
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
								        				Muestra
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
								        				Fecha Hora Ingreso Planta
								        			</th>
								        		</tr>
								        	</thead>

								        	<tbody id="tbl_pendientes">
								        		
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
	  	</div>

		</div>

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_showimagenes" data-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showimagenesLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div id="modal_showimagenes_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title" id="modal_showimagenesLabel"></h1>
		        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<!--jQuery [ REQUIRED ]-->
	  <script src="template/js/jquery.min.js"></script>

	  <!--BootstrapJS [ RECOMMENDED ]-->
	  <script src="template/js/bootstrap.min.js"></script>

	  <!--NiftyJS [ RECOMMENDED ]-->
	  <script src="template/js/nifty.min.js"></script>

		<!-- Select2 -->
		<script src="libs/select2/dist/js/select2.full.min.js"></script>

		<!-- ECharts -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

		<!-- Jq Toast -->
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js" integrity="sha512-Y+cHVeYzi7pamIOGBwYHrynWWTKImI9G78i53+azDb1uPmU1Dz9/r2BLxGXWgOC7FhwAGsy3/9YpNYaoBy7Kzg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipalNew();

				// Titulo de Pantalla
					$("#nv_titulo").html('| LQ - Cierre de Análisis de Humedad');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadResultados();

				// Agrega el Focus
					document.getElementById("barcode_lectura").focus();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
        var _html = '';
        var d = 1;

        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_estado = $("#filtro_estado").val();
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

				// Cargando datos de Análisis
	        $("#tbl_muestras").html('');

	        f_LoadingListaMuestras(1);

	        $.post( "apis/backend.php", { accion: "get_AnalisisHumedad_Cierre", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_estado: filtro_estado, cod_interno: cod_interno, id_rol: <?php echo $id_rol ?> }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_muestras").html(data.html);

	            	total_chkcierre = data.total_chkcierre;
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingListaMuestras(0);

	          }, "json");

	      // Cargando Muestras Pendiente de Recepción que piden análisis de Humedad
	        $("#tbl_pendientes").html('');
	        $("#lbl_totalpendientes").html(0);

	        f_LoadingListaPendientes(1);

	        $.post( "apis/backend.php", { accion: "get_AnalisisHumedad_PendientesRecepcionLQ" }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_pendientes").html(data.html);
	            	$("#lbl_totalpendientes").html(data.total_pendientes);
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingListaPendientes(0);

	          }, "json");
    	}

    	$("#barcode_lectura").keyup(function (e) {
		    if (e.keyCode === 13) {
					f_LoadResultados();
		    }
		  })

		  function f_RowSelected(_id_cabecera, _id_chkselected){
		  	var h = 1;
		  	var p = 0;

		  	var _html = '';
		  	var _html_chk = '';
		  	var _porc_humedad = 0;
		  	var _is_checked = 0;
		  	var _promedio = 0;
		  	var _arr_promedio = '';

		  	while (h <= 100){
		  		_html = $("#row_promedio_" + _id_cabecera + "_" + h).html();
		  		_html_chk = $("#row_chk_" + _id_cabecera + "_" + h);

		  		if (_html == undefined){
		  			break;
		  		}
		  		else{
		  			if (_html_chk.prop('checked')){
		  				_porc_humedad += parseFloat(_html);

		  				_arr_promedio += parseFloat(_html) + '|';

		  				_is_checked = 1;

		  				p ++;
		  			}
		  		}

		  		h ++;
		  	}

		  	if (_is_checked == 1){
		  		_promedio = _porc_humedad / p;

		  		// Seteando Campo de Cierre
		  			var _html = '<input id="rowchk_' + _id_chkselected + '" class="form-check-input" style="transform: scale(1.5);" type="checkbox" checked>';
		  			_html += '<input id="rowchk_cierre_' + _id_chkselected + '" type="hidden" value="' + _id_cabecera + '">';

		  			$("#tdcierre_1_" + _id_chkselected).html(_html);
		  	}
		  	else{
		  		_promedio = '';

		  		// Seteando Campo de Cierre
		  			$("#tdcierre_1_" + _id_chkselected).html('');
		  	}

		  	// Setea el promedio
			  	_promedio = ((_promedio.toString().length > 0) ? parseFloat(_promedio).toFixed(2) : '');

			  	$("#td_prom_" + _id_cabecera).html(_promedio);

		  	// Determina el color del según criterio de aceptación
			  	var bgcolor = '#D9D9D9';

			  	_arr_promedio = _arr_promedio.substring(0, _arr_promedio.length - 1);

			  	if (h == 3){
			  		// Obtiene los valores
				  		var c = 0;
				  		var porc_item1 = 999.999;
							var porc_item2 = 999.999;

				  		_arr_promedio = _arr_promedio.split('|');

				  		while (c < h){
				  			if (c == 0){
				  				porc_item1 = _arr_promedio[c];
				  			}

				  			if (c == 1){
				  				porc_item2 = _arr_promedio[c];
				  			}

				  			c ++;
				  		}

				  	// Valida la existencia del Porcentaje
							var e = 0;

							if (porc_item1 != 999.999){
								e ++;
							}

							if (porc_item2 != 999.999){
								e ++;
							}

						// Determina el criterio de aceptación
							if (e < 2){
								bgcolor = '#93D94E';
							}
							else{
								var is_reanalisis = 0;
								bgcolor = '#93D94E';

								var _prom = parseFloat((parseFloat(porc_item1) + parseFloat(porc_item2)) / 2).toFixed(2);
								var _diff = Math.abs(parseFloat(porc_item1) - parseFloat(porc_item2));

								if (_prom >= 3 && _diff >= 0.25){
									is_reanalisis = 1;
								}

								if (_prom < 3 && _diff >= 0.22){
									is_reanalisis = 1;
								}

								if (is_reanalisis == 1){
									bgcolor = '#FF5F5D';
								}
							}

						// Seteando el color del promedio
							$("#td_prom_" + _id_cabecera).css('background-color', bgcolor);
			  	}
		  }

    	function f_ExportToExcel(){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_estado = $("#filtro_estado").val();
        	var cod_interno = $("#barcode_lectura").val();

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

        window.location.href = "export_to_excel/lq_analisishumedad_cierre.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_estado="+filtro_estado+"&cod_interno="+cod_interno;
    	}

    	function f_ExportToExcel_Macro(){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_estado = $("#filtro_estado").val();
        	var cod_interno = $("#barcode_lectura").val();

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

        window.location.href = "export_to_excel/lq_analisishumedad_cierre_macro.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_estado="+filtro_estado+"&cod_interno="+cod_interno;
    	}

    	function f_PrintInformeCliente(_id_md5){
    		var url = 'print_ticket_humedad.php?x=' + _id_md5;

    		window.open(url,'_blank',"");
    	}

	    function f_AddEvidencia(_id_cabecera){
			  var input = document.createElement('input');
			  input.type = 'file';
			  input.accept = 'image/*';
			  input.onchange = function(event) {
			    var file = event.target.files[0];
			    var reader = new FileReader();
			    reader.onload = function(e) {
			      var imagen = document.getElementById('img_evidencia');
			      imagen.src = e.target.result;

			      f_SaveImagen(_id_cabecera);
			    };
			    reader.readAsDataURL(file);
			  };
			  input.click();

			  // $("#img_evidencia").show();
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
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
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

			function f_LoadingListaMuestras(_is_show){
				if (_is_show == 1){
					$("#wt_listamuestras").show();
				}
				else{
					$("#wt_listamuestras").hide();
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

			function f_LoadingCierre(_is_show){
				if (_is_show == 1){
					$("#wt_cierre").show();
				}
				else{
					$("#wt_cierre").hide();
				}
			}

			$("#modal_addmuestras").on('shown.bs.modal', function(){
      	$("#addmuestra_barcode").focus();
    	});

    	$("#modal_getpeso").on('shown.bs.modal', function(){
      	$("#txt_getpeso").focus();
    	});

			function f_LoadingImagenes(_is_show){
				if (_is_show == 1){
					$("#wt_imagenes").show();
				}
				else{
					$("#wt_imagenes").hide();
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
		  function f_CierreAnalisis(){
		  	var c = 0;
		  	var html = '';
		  	var id_cabecera = 0;
		  	var tiene_checks = 0;
		  	var arr_selected = [];
		  	var a = 0;
		  	var _prom = 0;

		  	// Recorriendo los checks
			  	while (c <= total_chkcierre){
			  		// Determina su el objeto existe
			  			html = $("#rowchk_" + c);

			  			if (html.html() != undefined){
			  				// Validando los promedios que tienen Check
			  					if (html.prop('checked')){
			  						id_cabecera = $("#rowchk_cierre_" + c).val();

			  						_prom = $("#td_prom_" + id_cabecera).html().trim();

			  						// Guarda el Id del check
			  							arr_selected.push(id_cabecera + '|' + _prom);

			  						tiene_checks = 1;
			  					}
			  			}

			  		c ++;
			  	}

			  // Validando si no se ha seleccionado ningún check
			  	if (tiene_checks == 0){
			  		alert("No ha seleccionado ningún check de Cierre.");
			  	}
			  	else{
			  		// Recorriendo el array de Id's
			  			var i = 0;
			  			var m = 1;
			  			var _id_cabecera = 0;
			  			var _id_detalle = 0;
			  			var arr_chkmuestras = [];

			  			while (i < arr_selected.length){
			  				// Buscando checks de muestras
			  					m = 1;
			  					_id_cabecera = arr_selected[i].split('|')[0];
			  					// arr_chkmuestras = [];

			  					while (m < 999){
			  						html = $("#row_chk_" + _id_cabecera + "_" + m);

			  						// Validando si existe el check de muestra
			  							if (html.html() == undefined){
			  								break;
			  							}
			  							else{
			  								_id_detalle = $("#chk_iddetalle_" + _id_cabecera + "_" + m).val().trim();

			  								if (html.prop('checked')){
			  									arr_chkmuestras.push(_id_detalle);
			  								}
			  							}

			  						m ++;
			  					}

			  				i ++;
			  			}

		  			// Guardando cierres
			  			f_LoadingCierre(1);

			  			$.post( "apis/backend.php", { accion: "cierre_AnalisisHumedad", arr_cabecera: arr_selected, arr_muestras: arr_chkmuestras },
			          function( data ) {
			            if(data.estado == 1){
			            	f_LoadResultados();
			            }
			            else{
			              alert("Ocurrió un error al momento de realizar el Cierre de Análisis.");
			            }

			            f_LoadingCierre(0);

			          }, "json");
			  	}
		  }

		  function f_Reabrir(_item_cierre, _id_cabecera){
		  	// Validando cierre
		  		if (!confirm("¿Está seguro de Reabrir el cierre seleccionado?")){
		  			return;
		  		}

		  	// Reabrir Cierre
		  		$.post( "apis/backend.php", { accion: "reabrir_AnalisisHumedad", id_cabecera: _id_cabecera },
	          function( data ) {
	            if(data.estado == 1){
	            	// Habilita los checks de muestras
						  		var h = 1;

						  		while (h < 999){
										html = $("#row_chk_" + _id_cabecera + "_" + h);

										// Validando si existe el check de muestra
											if (html.html() == undefined){
												break;
											}
											else{
												html.prop('disabled', false);
											}

										h ++;
									}

								// Habilita los objetos de Cierre
									var html = '<input id="rowchk_' + _item_cierre + '" class="form-check-input" style="transform: scale(1.5);" type="checkbox" checked="">';
									html += '<input id="rowchk_cierre_' + _item_cierre + '" type="hidden" value="' + _id_cabecera + '">';

									$("#tdcierre_1_" + _item_cierre).html(html);
									$("#tdcierre_2_" + _item_cierre).html('');
									$("#tdcierre_3_" + _item_cierre).html('');
	            }
	            else{
	              alert("Ocurrió un error al momento de realizar el Cierre de Análisis.");
	            }

	          }, "json");
		  }

      function f_GuardarPeso(){
      	var _is_buscarmuestra = $("#getpeso_isbuscarmuestra").val();
				var _orden_peso = $("#getpeso_ordenpeso").val();
				var _orden_item = $("#getpeso_ordenitem").val();
				var _item = $("#getpeso_item").val();
				var _id_detalle = $("#getpeso_iddetalle").val();
				var _peso = $("#txt_getpeso").val();
				var _cod_interno = $("#addmuestra_barcode").html().trim().substring(0, $("#addmuestra_barcode").html().trim().length - 1) ;

				// Validando datos
					if (_peso == null){
            alert("Debe ingresar el Peso.");

            return;
          }
          if (_peso.length == 0){
            alert("Debe ingresar el Peso.");

            return;
          }
          if (_peso <= 0){
            alert("El Peso ingresado no es válido.");

            return;
          }

        // Grabando datos
        	$.post( "apis/backend.php", { accion: "guardar_PesoHumedad", id_detalle: _id_detalle, orden_peso: _orden_peso, peso: _peso },
	          function( data ) {
	            if(data.estado == 1){
	            	if (_orden_peso == 1){
	            		$("#modal_getpesoLabel").html('Peso Húmedo: ');

				        // Setea objetos
				          $("#txt_getpeso").val('');

				          if (_is_buscarmuestra == 1){
				          	$("#td_buscarmuestra_getpeso_1_" + _orden_item).html(_peso);
				          	$("#td_buscarmuestra_getpeso_2_" + _orden_item).html('<button class="btn btn-info" type="button" onclick="f_GetPeso_Show(' + _is_buscarmuestra + ', 2, ' + _orden_item + ", '" + _item + "'" + ', ' + _id_detalle + ", '" + _cod_interno + "'" + ');" style="width: 100%; color: #ffffff; font-size: 14px;">Pesar</button>');
				          }
				          else{
				          	$("#td_analisismuestra_getpeso_1_" + _orden_item).html(_peso);
				          	$("#td_analisismuestra_getpeso_2_" + _orden_item).html('<button class="btn btn-info" type="button" onclick="f_GetPeso_Show(' + _is_buscarmuestra + ', 2, ' + _orden_item + ", '" + _item + "'" + ', ' + _id_detalle + ", '" + _cod_interno + "'" + ');" style="width: 100%; color: #ffffff; font-size: 14px;">Pesar</button>');

				          	f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
				          }

				        // Asignando valores a objetos hidden
				          $("#getpeso_isbuscarmuestra").val(_is_buscarmuestra);
									$("#getpeso_ordenpeso").val(2);
									$("#getpeso_ordenitem").val(_orden_item);
									$("#getpeso_item").val(_item);
									$("#getpeso_iddetalle").val(_id_detalle);
	            	}

	            	if (_orden_peso == 2){
	            		if (_is_buscarmuestra == 1){
		            		// Setea objetos
						          $("#td_buscarmuestra_getpeso_2_" + _orden_item).html(_peso);
						      }
						      else{
						      	f_LoadItemHumedad(itemrack_Selected, idrack_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
						      }

						      f_cerrarModal("modal_getpeso");

					        f_BreakAutomatico();
	            	}

	            	if (_orden_peso == 3){
	            		// Setea objetos
						        $("#td_buscarmuestra_getpeso_3_" + _orden_item).html(_peso);

						      // Actualizar el Click del Rack seleccionado
				        		$("#tr_item_" + itemrack_Selected).attr("onclick", 'f_LoadItemHumedad(' + itemrack_Selected + ", " + idrack_Selected + ", 1, 1, " + rack_horassecado_selected + ');');

						      f_LoadItemHumedad(itemrack_Selected, idrack_Selected, 1, 1, rack_horassecado_selected);

						      f_cerrarModal("modal_getpeso");

					        f_BreakAutomatico();
	            	}
	            }
	            else{
	              alert("Ocurrió un error al momento de eliminar el Modelo.");
	            }

	          }, "json");
      }

      function f_SaveImagen(_id_cabecera){
	    	if ($("#img_evidencia").attr('src').length == 0){
	    		setTimeout('f_SaveImagen(' + _id_cabecera + ')', 1000);
	    	}
	    	else{
	    		// Guardando archivo
						var arr_imagenes = [];

						var _imagen = {
				      imagen: $("#img_evidencia").attr('src')
				    };

				    arr_imagenes.push(_imagen);

				    $.post( "apis/backend.php", { accion: "grabar_AnalisisHumedad_UploadRecibo", id_cabecera: _id_cabecera, arr_imagenes: JSON.stringify(arr_imagenes) },
	            function( data ) {
	            	if(data.estado == 1){
	            		$("#img_recibo_" + _id_cabecera).css('display', 'block');
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