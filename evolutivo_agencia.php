<?php

	session_start();

	if(isset($_SESSION["Id"])){
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
		<link rel="icon" href="../usuario/images/icons/cajatrujillo.png" type="image/png"/>

		<!-- Bootstrap -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

		<!-- Íconos -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

		<!-- Select2 -->
		<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">

		<title>MadBoard | Caja Trujillo</title>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_LoadAnhos();" style="zoom: 80%;">
		<nav class="navbar fixed-top" style="background-color: #ffffff; box-shadow: 0px 0px 10px #BDBFAE;">
		  <div class="container-fluid" style="background-color: #ffffff;">
		    <img src="images/cajatrujillo.png" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;">
		    <form class="d-flex" role="search" style="font-size: 14px;">
		    	<div>
		    		<select id="filtro_anho" class="form-select" style="width: 90px;">

	          </select>
		    	</div>

		    	<div style="margin-left: 5px;">
		    		<select id="filtro_mes" class="form-select" style="width: 140px;">
	            
	          </select>
		    	</div>

		    	<div style="margin-top: -7px;margin-left: 15px; font-size: 25px; color: #DEDEDE;">|</div>

		    	<div style="margin-left: 15px;">
		    		<select id="filtro_interacciones" class="form-select" style="width: 240px;">
		    			<option selected value="627966">OPERACIONES VENTANILLA</option>
		    			<option value="366894">ASESORES NEGOCIOS</option>
	          </select>
		    	</div>

		    	<div style="margin-left: 5px;">
		    		<select id="filtro_region" class="form-select" style="width: 180px;">
	            <option selected value="">---</option>
	          </select>
		    	</div>

		    	<div style="margin-left: 5px;">
		    		<select id="filtro_tipocliente" class="form-select" style="width: 180px;">
	            <option selected value="">---</option>
	          </select>
		    	</div>

		    	<div id="div_agencias" style="margin-left: 5px;">
		    		<select id="filtro_agencia" class="form-select" style="width: 180px;">
	            <option selected value="">---</option>
	          </select>
		    	</div>
		    </form>
		  </div>
		</nav>

		<div class="d-flex">
			<div class="col-md-1 col-sm-1 col-xs-1" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; height: 1000px; padding-top: 70px; background-color: #DEDEDE;">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<button id="men_1" class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" style="background-color: #DEDEDE; border-color: #BFBFBF; width: 100%; height: 90px; color: #0d2b68; font-size: 14px; border-left-width: 0; border-right-width: 0; border-top-width: 0; margin-top: 4px;">
						<div style="margin-bottom: 5px;">
							<i class="bi bi-bar-chart" style="font-size: 18px; border: solid; border-width: 1px; border-color: #737373; border-radius: 7px; margin-bottom: 3px; padding: 3px;"></i>
						</div>

						<span style="margin-top: 5px;">
							VoC
						</span>
	        </a>
	      </div>

	      <div class="col-md-12 col-sm-12 col-xs-12">
					<button id="men_2" class="btn btn-secondary" type="button" style="background-color: #DEDEDE; border-color: #BFBFBF; width: 100%; height: 90px; color: #0d2b68; font-size: 14px; border-left-width: 0; border-right-width: 0; border-top-width: 0; margin-top: 4px;">
						<div style="margin-bottom: 5px;">
							<i class="bi bi-people" style="font-size: 18px; border: solid; border-width: 1px; border-color: #737373; border-radius: 7px; margin-bottom: 3px; padding: 3px;"></i>
						</div>

						<span style="margin-top: 5px;">
							Mystery
						</span>
	        </button>
	      </div>
			</div>

			<div class="col-md-11 col-sm-11 col-xs-11" style="height: 1000px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 60px;">
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px;">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<label class="mb-1" style="font-size: 22px; font-weight: 500;">Evolutivo por Agencia</label>
					</div>

					<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #e9ecef; font-size: 12px; padding: 8px;">
						<label id="lbl_anho">

						</label> |

						<label id="lbl_mes">

						</label> |

						<label id="lbl_interaccion">

						</label>

						<label id="lbl_region">

						</label>

						<label id="lbl_tipocliente">

						</label>

						<label id="lbl_agencia">

						</label>
					</div>
				</div>

				<div class="d-flex" style="margin-top: -25px; padding: 20px;">
					<div class="table-responsive col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 15px; background-color: #ffffff;">
						<label style="font-size: 22px; margin-left: 10px;">
							NPS
						</label>

						<table id="tbl_NPS" class="table table-hover table-bordered" style="font-size: 14px;">
              
            </table>
					</div>
				</div>

				<div class="d-flex" style="margin-top: -25px; padding: 20px;">
					<div class="table-responsive col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 15px; background-color: #ffffff;" hidden>
						<label style="font-size: 22px; margin-left: 10px;">
							INS
						</label>

						<table id="tbl_INS" class="table table-hover table-bordered">
              
            </table>
					</div>
				</div>
			</div>
		</div>

		<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%;">
		  <div class="offcanvas-header" style="background-color: #ffffff;">
		    <h5 class="offcanvas-title" id="offcanvasExampleLabel">VoC</h5>
		    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		  </div>
		  <div class="offcanvas-body" style="color: #212529;">
		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-pie-chart-fill" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_1" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px;">
		        	Resultados NPS
		        </button>
		    	</div>
		    </div>

		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-people" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_2" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px;">
		        	Detalle NPS
		        </button>
		    	</div>
		    </div>

		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-graph-up-arrow" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_3" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px;">
		        	Resultados Satisfacción
		        </button>
		    	</div>
		    </div>

		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-broadcast" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_8" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px;">
		        	Resultados Esfuerzo
		        </button>
		    	</div>
		    </div>

		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-diagram-3" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_4" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px;">
		        	Resultados por Región
		        </button>
		    	</div>
		    </div>

		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-person-lines-fill" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_5" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px;">
		        	Resultados por Tipo de Cliente
		        </button>
		    	</div>
		    </div>

		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-ui-checks" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_6" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px;">
		        	Resultados por Agencia
		        </button>
		    	</div>
		    </div>

		    <div class="d-flex" style="text-align: center;">
		    	<div class="col-md-3 col-sm-3 col-xs-3">
		    		<i class="bi bi-ubuntu" style="font-size: 24px;"></i>
		    	</div>

		    	<div class="col-md-9 col-sm-9 col-xs-9">
		    		<button id="submen_7" class="btn" type="button" data-bs-dismiss="offcanvas" style="width: 100%; text-align: left; font-size: 14px; background-color: #0d2b68; color: #ffffff;">
		        	Evolutivo por Agencia
		        </button>
		    	</div>
		    </div>
		  </div>
		</div>

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Select2 -->
		<script src="../vendors/select2/dist/js/select2.full.min.js"></script>

		<!-- ECharts -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 400){
					
				}
			}

			$(document).ready(function() {
	  		$("#filtro_anho, #filtro_mes, #filtro_interacciones, #filtro_region, #filtro_tipocliente, #filtro_agencia").select2();

	  		$("#select2-filtro_anho-container").css('background-color', '#0d2b68');
	  		$("#select2-filtro_anho-container").css('color', '#ffffff');

	  		$("#select2-filtro_mes-container").css('background-color', '#0d2b68');
	  		$("#select2-filtro_mes-container").css('color', '#ffffff');

	  		$("#select2-filtro_interacciones-container").css('background-color', '#00619F');
	  		$("#select2-filtro_interacciones-container").css('color', '#ffffff');

	  		$("#select2-filtro_region-container").css('background-color', '#00619F');
	  		$("#select2-filtro_region-container").css('color', '#ffffff');

	  		$("#select2-filtro_tipocliente-container").css('background-color', '#00619F');
	  		$("#select2-filtro_tipocliente-container").css('color', '#ffffff');

	  		$("#select2-filtro_agencia-container").css('background-color', '#00619F');
	  		$("#select2-filtro_agencia-container").css('color', '#ffffff');
	  	});

			$("#men_1").on( 'click', function() {
				$("#men_1").css('box-shadow', '5px 3px 10px #0A3459');
				$("#men_2").css('box-shadow', '0px 0px 0px #ffffff');

				$(".offcanvas-backdrop").css('width', '100%');
				$(".offcanvas-backdrop").css('height', '100%');
			});

			$("#men_2").on( 'click', function() {
				$("#men_1").css('box-shadow', '0px 0px 0px #ffffff');
				$("#men_2").css('box-shadow', '5px 3px 10px #0A3459');
			});

			$("#submen_1").on( 'click', function() {
				window.open('resultados_nps.php', '_self');
			});

			$("#submen_2").on( 'click', function() {
				window.open('detalle_nps.php', '_self');
			});

			$("#submen_3").on( 'click', function() {
				window.open('satisfaccion.php', '_self');
			});

			$("#submen_5").on( 'click', function() {
				window.open('resultados_tipocliente.php', '_self');
			});

			$("#submen_6").on( 'click', function() {
				window.open('resultados_agencia.php', '_self');
			});

			$("#submen_7").on( 'click', function() {
				window.open('evolutivo_agencia.php', '_self');
			});

			$("#submen_8").on( 'click', function() {
				window.open('resultados_esfuerzo.php', '_self');
			});

			$("#filtro_anho").on('change', function() {
				f_LoadMeses();
			});

			$("#filtro_mes").on('change', function() {
				f_LoadRegiones();
				f_LoadTipoCliente();
				f_LoadAgencias();
				f_LoadEvolutivoAgencias();
			});

			$("#filtro_interacciones").on('change', function() {
				f_LoadRegiones();
				f_LoadTipoCliente();
				f_LoadAgencias();
				f_LoadEvolutivoAgencias();
			});

			$("#filtro_region").on('change', function() {
				f_LoadEvolutivoAgencias();
			});

			$("#filtro_tipocliente").on('change', function() {
				f_LoadEvolutivoAgencias();
			});

			$("#filtro_agencia").on('change', function() {
				f_LoadEvolutivoAgencias();
			});
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
						$("#filtro_mes").val('');

						$.post( "apis/backend.php", { accion: "get_Meses", anho: _anho },
							function( data ) {
								if(data.estado == 1){
									$("#filtro_mes").html(data.html);

									f_LoadRegiones();
									f_LoadTipoCliente();
									f_LoadAgencias();
									f_LoadEvolutivoAgencias();
								}

							}, "json");
				}

				function f_LoadRegiones(){
					var _anho = $("#filtro_anho").val();
					var _mes = $("#filtro_mes").val();
					var _interaccion = $("#filtro_interacciones").val();

					// Carga filtros de Regiones
						$("#filtro_region").html('');

						$.post( "apis/backend.php", { accion: "get_Regiones", anho: _anho, mes: _mes, interaccion: _interaccion }, 
							function( data ) {
								if(data.estado == 1){
									$("#filtro_region").html(data.html);

									// f_LoadEvolutivoAgencias();
								}

							}, "json");
				}

				function f_LoadTipoCliente(){
					var _anho = $("#filtro_anho").val();
					var _mes = $("#filtro_mes").val();
					var _interaccion = $("#filtro_interacciones").val();

					// Carga filtros de Regiones
						$("#filtro_tipocliente").html('');

						$.post( "apis/backend.php", { accion: "get_TipoCliente", anho: _anho, mes: _mes, interaccion: _interaccion }, 
							function( data ) {
								if(data.estado == 1){
									$("#filtro_tipocliente").html(data.html);

									// f_LoadEvolutivoAgencias();
								}

							}, "json");
				}

				function f_LoadAgencias(){
					var _anho = $("#filtro_anho").val();
					var _mes = $("#filtro_mes").val();
					var _interaccion = $("#filtro_interacciones").val();

					// Carga filtros de Regiones
						$("#filtro_agencia").html('');

						if (_interaccion.split('|')[0] != 863264 && _interaccion.split('|')[0] != 852952){
							$("#div_agencias").show();

							$.post( "apis/backend.php", { accion: "get_Agencias", anho: _anho, mes: _mes, interaccion: _interaccion },
								function( data ) {
									if(data.estado == 1){
										$("#filtro_agencia").html(data.html);

										// f_LoadEvolutivoAgencias();
									}

								}, "json");
						}
						else{
							$("#div_agencias").hide();
						}
				}

				function f_LoadFiltrosTitulo(){
					$("#lbl_anho").html('Año: <b>' + $("#filtro_anho").val() + '</b>');
					$("#lbl_mes").html('Mes: <b>' + $("#filtro_mes option:selected").text() + '</b>');
					$("#lbl_interaccion").html('Interacción: <b>' + $("#filtro_interacciones option:selected").text() + '</b>');

					if ($("#filtro_region").val() != null){
						if ($("#filtro_region").val().length > 0){
							$("#lbl_region").html(' | Región: <b>' + $("#filtro_region option:selected").text() + '</b>');
						}
						else{
							$("#lbl_region").html('');
						}
					}

					if ($("#filtro_tipocliente").val() != null){
						if ($("#filtro_tipocliente").val().length > 0){
							$("#lbl_tipocliente").html(' | Tipo Cliente: <b>' + $("#filtro_tipocliente option:selected").text() + '</b>');
						}
						else{
							$("#lbl_tipocliente").html('');
						}
					}

					if ($("#filtro_agencia").val() != null){
						if ($("#filtro_agencia").val().length > 0){
							$("#lbl_agencia").html(' | Agencia: <b>' + $("#filtro_agencia option:selected").text() + '</b>');
						}
						else{
							$("#lbl_agencia").html('');
						}
					}
				}

				function f_LoadEvolutivoAgencias(){
					f_LoadFiltrosTitulo();

					// Obteniendo filtros
						var filtro_anho = $("#filtro_anho").val();
						var filtro_mes = $("#filtro_mes").val();
						var filtro_interacciones = $("#filtro_interacciones").val();
						var filtro_region = $("#filtro_region").val();
						var filtro_tipocliente = $("#filtro_tipocliente").val();
						var filtro_agencia = $("#filtro_agencia").val();

					// Validando datos
						if (filtro_region == null){
							filtro_region = '';
						}

						if (filtro_tipocliente == null){
							filtro_tipocliente = '';
						}

						if (filtro_agencia == null){
							filtro_agencia = '';
						}

					// Cargando tabla
						$("#tbl_NPS").html();

						$.post( "apis/backend.php", { accion: "get_EvolutivoAgencias", filtro_anho: filtro_anho, filtro_mes: filtro_mes, filtro_interacciones: filtro_interacciones, filtro_region: filtro_region, filtro_tipocliente: filtro_tipocliente, filtro_agencia: filtro_agencia },
							function( data ) {
								if(data.estado == 1){
									$("#tbl_NPS").html(data.html);
								}

							}, "json");

				}
		</script>
	</body>
</html>