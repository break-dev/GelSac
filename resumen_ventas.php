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

		<title><?php echo $nom_app; ?> | Resumen de Ventas</title>

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
											<h6 style="font-size: 14px;">Por Fechas</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadClientes();">

											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadClientes();">
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Cliente</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_clientes" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
												
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Sucursal</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_sucursal" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_sucursales = "SELECT Id,
                                								des_sucursal
								                           FROM tb_sucursal
								                          WHERE estado_sucursal = 'A'";

								        if ($res_sucursales = mysqli_query($enlace, $q_sucursales)){
								          if (mysqli_num_rows($res_sucursales) > 0) {
								            while($row_sucursales = mysqli_fetch_array($res_sucursales)){
								              ?>

								              <option value="<?php echo $row_sucursales["Id"]; ?>"><?php echo $row_sucursales["des_sucursal"]; ?></option>

								              <?php
								            }
								          }
								        }

												?>

											</select>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Nombre Muestra</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="filtro_nommuestra" type="text" class="form-control" style="font-size: 14px;">
										</div>
									</div>
								</div>

								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Documentos</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_listatipodocumento" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
												<option selected value="">Elija una opción...</option>
												<option value="1">N° Recibo</option>
												<option value="2">N° Comprobante</option>
											</select>

											<input id="filtro_documento" type="text" class="form-control" style="font-size: 14px;">
										</div>
									</div>
								</div>
							</div>

							<div class="row" style="padding-left: 30px; margin-top: 5px; margin-bottom: 10px; font-size: 13px;">
								<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px;">
		              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
		            </button>
							</div>
						</div>

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-3 col-sm-3 col-xs-3">
									<div class="d-flex">
										<h5>Resumen de Ventas</h5>

										<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
											<img src="<?php echo $img_waiting ?>" style="width: 20px;">
											<label style="font-style: italic;"> Cargando datos...</label>
										</div>
									</div>
								</div>

								<div class="col-md-5 col-sm-5 col-xs-5" style="margin-top: -10px;">
									<div class="d-flex flex-row-reverse" style="padding: 10px;">
										<div class="form-check" style="margin-left: 10px;">
										  <input class="form-check-input" type="radio" name="rd_instruccion" id="rd_sininstruccion" onchange="f_LoadResultados();">
										  <label class="form-check-label" for="rd_sininstruccion">
										    Sin Instrucción
										  </label>
										</div>

										<div class="form-check" style="margin-left: 10px;">
										  <input class="form-check-input" type="radio" name="rd_instruccion" id="rd_coninstruccion" onchange="f_LoadResultados();">
										  <label class="form-check-label" for="rd_coninstruccion">
										    Con Instrucción
										  </label>
										</div>

										<div class="form-check" style="margin-left: 40px;">
										  <input class="form-check-input" type="radio" name="rd_instruccion" id="rd_todos" onchange="f_LoadResultados();" checked>
										  <label class="form-check-label" for="rd_todos">
										    Todos
										  </label>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px;">
			              <b>Exportar a Excel</b>
			            </button>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-success" type="button" onclick="f_ExportToExcel_Macro();" style="width: 100%; color: #ffffff; font-size: 14px;">
			              <b>Exportar para Macro</b>
			            </button>
								</div>
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
								<div class="d-flex" style="margin-top: -10px; font-size: 14px;">
									<label>
										Total de Muestras: <spam id="total_muestras" style="color: #0897B4; font-weight: bold; margin-left: 5px;"></spam>
									</label>

									<label style="margin-left: 10px;">
										| &nbsp; Muestras Sin Recepción: <spam id="total_sinrecepcion" style="color: #dc3545; font-weight: bold; margin-left: 5px;"></spam>
									</label>

									<label style="margin-left: 10px;">
										| &nbsp; Muestras Con Recepción: <spam id="total_conrecepcion" style="color: #32B86C; font-weight: bold; margin-left: 5px;"></spam>
									</label>
								</div>

								<table class="table table-bordered table-hover">
				        	<thead>
				        		<tr style="font-size: 14px;">
				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
				        				N°
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
				        				Sucursal
				        			</th>

				        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Cliente
				        			</th>

				        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Documentos
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Estado
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
				        				Fecha Hora Recepción
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
				        				Fecha Hora Instrucción
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
				        				Fecha Hora Entrega
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
				        				Fecha Hora Reporte
				        			</th>

				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
				        				Entrega Informe
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
				        				Medio Pago
				        			</th>

				        			<th colspan="11" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Información de Muestras
				        			</th>

				        			<th colspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
				        				Informe de Ensayos
				        			</th>
				        		</tr>

				        		<tr style="font-size: 14px;">
				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Documento
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
				        				Razón Social
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Teléfonos
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
				        				N° Recibo
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
				        				N° Comprobante
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
				        				Fecha Emisión
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
				        				Fecha y Hora
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
				        				Usuario
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; font-weight: bold; min-width: 120px;">
				        				C.I.
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
				        				Nombre
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
				        				Análisis
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Peso (Kg)
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Estado
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Envase
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Tipo
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Ensayo
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Exceso
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Total
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
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
					$("#nv_titulo").html('| Resumen de Ventas');

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

        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_cliente = $("#filtro_clientes").val();
        	var filtro_sucursal = $("#filtro_sucursal").val();
        	var filtro_nommuestra = $("#filtro_nommuestra").val();
        	var filtro_listatipodocumento = $("#filtro_listatipodocumento").val();
        	var filtro_documento = $("#filtro_documento").val();
        	var filtro_opt1 = (($("#rd_sininstruccion").prop('checked')) ? 1 : 0);
        	var filtro_opt2 = (($("#rd_coninstruccion").prop('checked')) ? 1 : 0);
        	var filtro_opt3 = (($("#rd_todos").prop('checked')) ? 1 : 0);

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

        // Inicializando los totales
        	$("#total_muestras").html('0');
					$("#total_sinrecepcion").html('0');
					$("#total_conrecepcion").html('0');

        $("#tbl_detalle").html('');

        f_LoadingDetalle(1);

        $.post( "apis/backend.php", { accion: "get_ResumenVentas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_cliente: filtro_cliente, filtro_sucursal: filtro_sucursal, filtro_nommuestra: filtro_nommuestra, filtro_listatipodocumento: filtro_listatipodocumento, filtro_documento: filtro_documento, filtro_opt1: filtro_opt1, filtro_opt2: filtro_opt2, filtro_opt3: filtro_opt3 }, 
          function( data ) {
            if(data.estado == 1){
            	$("#tbl_detalle").html(data.html);

	            f_TotalMuestras();
            }
            else{
              // alert("No se encontraron resultados.");
            }

            f_LoadingDetalle(0);

          }, "json");
    	};

    	function f_TotalMuestras(){
    		// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_estadomuestra = '';
        	var filtro_envasemuestra = '';
        	var filtro_tiposmuestra = '';
        	var filtro_ensayosmuestra = '';
        	var filtro_CI = '';
        	var filtro_opt1 = '';
        	var filtro_opt2 = '';
        	var filtro_opt3 = '';

        // Inicializando los totales
        	$("#total_muestras").html('0');
					$("#total_sinrecepcion").html('0');
					$("#total_conrecepcion").html('0');

				// Obteniendo totales
	    		$.post( "apis/backend.php", { accion: "get_LQRecepcionMuestras_TotalMuestras", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_estadomuestra: filtro_estadomuestra, filtro_envasemuestra: filtro_envasemuestra, filtro_tiposmuestra: filtro_tiposmuestra, filtro_ensayosmuestra: filtro_ensayosmuestra, filtro_CI: filtro_CI, filtro_opt1: filtro_opt1, filtro_opt2: filtro_opt2, filtro_opt3: filtro_opt3 }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#total_muestras").html(parseFloat(data.total_sinrecepcion) + parseFloat(data.total_conrecepcion));
								$("#total_sinrecepcion").html(data.total_sinrecepcion);
								$("#total_conrecepcion").html(data.total_conrecepcion);
	            }
	            else{
	              alert("No se encontraron resultados.");
	            }

	          }, "json");
    	}

    	function f_ExportToExcel(){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_cliente = $("#filtro_clientes").val();
        	var filtro_sucursal = $("#filtro_sucursal").val();
        	var filtro_nommuestra = $("#filtro_nommuestra").val();
        	var filtro_listatipodocumento = $("#filtro_listatipodocumento").val();
        	var filtro_documento = $("#filtro_documento").val();
        	var filtro_opt1 = (($("#rd_sininstruccion").prop('checked')) ? 1 : 0);
        	var filtro_opt2 = (($("#rd_coninstruccion").prop('checked')) ? 1 : 0);
        	var filtro_opt3 = (($("#rd_todos").prop('checked')) ? 1 : 0);

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

        window.location.href = "export_to_excel/resumen_venta.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_cliente="+filtro_cliente+"&filtro_sucursal="+filtro_sucursal+"&filtro_nommuestra="+filtro_nommuestra+"&filtro_listatipodocumento="+filtro_listatipodocumento+"&filtro_documento="+filtro_documento+"&filtro_opt1="+filtro_opt1+"&filtro_opt2="+filtro_opt2+"&filtro_opt3="+filtro_opt3;
    	};

    	function f_ExportToExcel_Macro(){
        // Obteniendo filtros
        	var filtro_origen = $("#filtro_origen").val();
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_sucursal = $("#filtro_sucursal").val();
        	var filtro_nommuestra = $("#filtro_nommuestra").val();
        	var filtro_listatipodocumento = $("#filtro_listatipodocumento").val();
        	var filtro_documento = $("#filtro_documento").val();
        	var filtro_opt1 = (($("#rd_sininstruccion").prop('checked')) ? 1 : 0);
        	var filtro_opt2 = (($("#rd_coninstruccion").prop('checked')) ? 1 : 0);
        	var filtro_opt3 = (($("#rd_todos").prop('checked')) ? 1 : 0);

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

        window.location.href = "export_to_excel/resumen_venta_macro.php?filtro_origen="+filtro_origen+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_sucursal="+filtro_sucursal+"&filtro_nommuestra="+filtro_nommuestra+"&filtro_listatipodocumento="+filtro_listatipodocumento+"&filtro_documento="+filtro_documento+"&filtro_opt1="+filtro_opt1+"&filtro_opt2="+filtro_opt2+"&filtro_opt3="+filtro_opt3;
    	};

      function f_PrintDocumentosCliente(_id_md5){
      	var url = 'print_informeensayos.php?x=' + _id_md5;

      	window.open(url, '_blank');
      }

      function f_SendWsp(_item, _id_md5){
          var url = '<?php echo $url_lims; ?>' + 'print_informeensayos.php?x=' + _id_md5;

          var nom_cliente = $("#td_nomcliente_" + _item).html().trim();
          var cel_cliente = $("#td_celcliente_" + _item).html().replace(/ /g, '');
          var analisis = $("#td_analisis_" + _item).html().trim();
          var nom_muestra = $("#td_nommuestra_" + _item).html().trim();
          var num_informeensayo = $("#td_codinterno_" + _item).html().trim();

          // Obtiene el primer número del Cliente
              cel_cliente = cel_cliente.substring(0, 9);

          // Arma el mensaje
              var msg = 'https://api.whatsapp.com/send?phone=51' + cel_cliente +'&text=Estimado(a) Cliente *' + nom_cliente + "*, los resultados de su muestra: *" + nom_muestra.trim() + "* para los análisis: *" + analisis.replace(/ /g, '').trim() + "* se encuentran listos con número de Informe de Ensayo: *" + num_informeensayo + "*. Puede revisarlos entrando al siguiente link:%0A%0A" + url + "%0A%0ASaludos cordiales%0A*CC Laboratorio*";

          window.open(msg, '_blank');
          window.open(url, '_blank');
      }

    	function f_LoadClientes(){
    		// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();

        // Cargando clientes
        	$("#filtro_clientes").html('');

        	$.post( "apis/backend.php", { accion: "get_ClientesxFechas", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
          function( data ) {
            if(data.estado == 1){
            	$("#filtro_clientes").html(data.html);
            }
            else{
              // alert("No se encontraron resultados.");
            }

          }, "json");
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			// Lista de Roles
				function f_GetListaRoles(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listaroles" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.DESCRIPCION + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#usu_rol").html(_html);
							}
							else{
								$("#usu_rol").html('');
							}

						}, "json");
				}

			// Lista de Sucursales
				function f_GetListaSucursales(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listasucursales" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.DESCRIPCION + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#usu_sucursal").html(_html);
							}
							else{
								$("#usu_sucursal").html('');
							}

						}, "json");
				}

			function f_LoadingDetalle(_is_show){
				if (_is_show == 1){
					$("#wt_detalle").show();
				}
				else{
					$("#wt_detalle").hide();
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Graba información temporal (onblur).
				function f_ConfirmarEntregaInforme(_id_detalle){
					if (!confirm("¿Está seguro de confirmar la Entrega del Informe?")){
						return;
					}

					// Actualizando Fecha y Hora
						$.post( "apis/backend.php", { accion: "update_EntregaInforme", id_detalle: _id_detalle },
	            function( data ) {
	              if(data.estado == 1){
	              	$("#td_entregainforme_1_" + _id_detalle).html(data.fechahora_registro);
	              	$("#td_entregainforme_2_" + _id_detalle).html(data.usuario_registro);
	              }
	              else{
	                alert("Ocurrió un error al momento confirmar la entrega del informe.");
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