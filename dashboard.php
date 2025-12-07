<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	// if(!isset($_SESSION["Id"])){
  //   header('Location: index.php');
  // }

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
		<title><?php echo $nom_app; ?> | Dashboard</title>

		<!-- Bootstrap -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"/>

		<style type="text/css">

		</style>
	</head>

	<body class="bg-dark" onload="f_Init();" style="zoom: 70%;">
		<!-- Llamando a Navbar -->
		<?php echo $navbar_maintop; ?>

		<div class="container-fluid">
			<div class="row" style="padding: 5px;">
				<div class="col-md-3 col-sm-3 col-xs-6" style="padding: 2px; margin-bottom: -10px;">
					<div style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; padding: 10px;">
						<div class="d-flex justify-content-center" style="padding-left: 10px; padding-right: 10px;">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="d-flex">
									<div class="col-md-4 col-sm-4 col-xs-4" style="color: #ffffff; padding-top: 10px; padding-right: 5px;">
										Desde:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadResultados(); f_LoadCharts();">
									</div>
								</div>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="d-flex">
									<div class="col-md-4 col-sm-4 col-xs-4" style="color: #ffffff; padding-top: 10px; padding-left: 10px; padding-right: 5px;">
										Hasta:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadResultados(); f_LoadCharts();">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row" style="padding: 5px;">
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
					<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; height: 380px;">
						<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px; color: #ffffff;">
							<div class="d-flex">
								<h5>Total de Muestras</h5>
								<div id="wt_loading" class="" style="font-size: 12px; text-align: center; padding-top: 5px; display: none;">
									<img src="images/waiting.gif" style="width: 20px;">
									<label style="font-style: italic;"> Cargando datos...</label>
								</div>
							</div>
						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
							<hr style="border-color: #D9D9D9;"/>
						</div>

						<div class="d-flex" style="padding-left: 20px; margin-top: -15px; margin-bottom: 5px; font-size: 13px; text-align: center;">
							<div class="col-md-5 col-sm-5 col-xs-5" style="padding: 5px;">
								<label id="lbl_TotalMuestras" style="padding-top: 85px; padding-left: 5px; margin-top: -15px; font-size: 40px; background-image: url('<?php echo $dash_circle; ?>'); background-size: cover; background-size: 270px; background-repeat: no-repeat; background-position: center; width: 250px; height: 250px; vertical-align: middle; color: #ffffff;">

	            	</label>
	            </div>

	            <div class="col-md-7 col-sm-7 col-xs-7" style="padding: 10px;">
	            	<div class="row" style="margin-left: -10px;">
		            	<div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 15px; width: 100%;">
			            	<label style="font-size: 16px; color: #ffffff;">
			            		Parque Industrial
			            	</label>

			            	<table style="width: 100%">
			            		<tr>
			            			<td id="td_totalmuestras1" style="color: #ffffff; font-size: 16px; width: 80px; text-align: center;">

			            			</td>

			            			<td style="text-align: center;">
			            				<div id="prg_totalmuestras1" class="progress" style="height: 30px; background-color: #74726C;">

													</div>
			            			</td>
			            		</tr>
			            	</table>
									</div>

									<div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 15px; width: 100%;">
			            	<label style="font-size: 16px; color: #ffffff;">
			            		Av.América
			            	</label>

			            	<table style="width: 100%">
			            		<tr>
			            			<td id="td_totalmuestras2" style="color: #ffffff; font-size: 16px; width: 80px; text-align: center;">

			            			</td>

			            			<td style="text-align: center;">
			            				<div id="prg_totalmuestras2" class="progress" style="height: 30px; background-color: #74726C;">

	          							</div>
			            			</td>
			            		</tr>
			            	</table>
		            	</div>
		            </div>
            	</div>
						</div>

						<div class="d-flex justify-content-center" style="padding: 15px; margin-top: -30px;">
							<div style="padding-left: 5px; padding-right: 5px; width: 100%">
								<div style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; width: 100%; padding-left: 5px; padding-right: 5px; background-color: #1B0126;">
									<div class="row col-md-12 col-sm-12 col-xs-12 justify-content-center" style="color: #ffffff;">
										N° Ventas
									</div>

									<div id="num_ventas" class="justify-content-center" style="color: #ffffff; text-align: center; font-weight: bold;">

									</div>
								</div>
							</div>

							<div style="padding-left: 5px; padding-right: 5px; width: 100%">
								<div style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; width: 100%; padding-left: 5px; padding-right: 5px; background-color: #102D51;">
									<div class="row col-md-12 col-sm-12 col-xs-12 justify-content-center" style="color: #ffffff;">
										Parque Industrial
									</div>

									<div id="num_ventas1" class="justify-content-center" style="color: #ffffff; text-align: center; font-weight: bold;">

									</div>
								</div>
							</div>

							<div style="padding-left: 5px; padding-right: 5px; width: 100%">
								<div style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; width: 100%; padding-left: 5px; padding-right: 5px; background-color: #102D51;">
									<div class="row justify-content-center" style="color: #ffffff;">
										Av. América
									</div>

									<div id="num_ventas2" class="justify-content-center" style="color: #ffffff; text-align: center; font-weight: bold;">

									</div>
								</div>
							</div>
          	</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
					<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; height: 380px;">
						<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px; color: #ffffff;">
							<h5>Total de Ventas (S/)</h5>
						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
							<hr style="border-color: #D9D9D9;"/>
						</div>

						<div class="d-flex" style="padding-left: 20px; margin-top: -5px; margin-bottom: 5px; font-size: 13px; text-align: center;">
							<div class="col-md-6 col-sm-6 col-xs-6" style="width: 50%;">
								<label id="lbl_totalventas" style="padding-top: 115px; padding-left: 5px; margin-top: -15px; font-size: 25px; background-image: url('<?php echo $dash_circle; ?>'); background-size: cover; background-size: 320px; background-repeat: no-repeat; background-position: center; width: 250px; height: 270px; vertical-align: middle; color: #ffffff;">

	            	</label>
	            </div>

	            <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 10px; margin-top: 20px; width: 50%;">
	            	<div class="row" style="margin-left: -10px;">
		            	<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 15px; width: 100%;">
			            	<label style="font-size: 16px; color: #ffffff;">
			            		Parque Industrial
			            	</label>

			            	<table style="width: 100%">
			            		<tr>
			            			<td id="td_totalventas1" style="color: #ffffff; font-size: 16px; width: 80px; text-align: right;">

			            			</td>

			            			<td style="text-align: center;">
			            				<div id="prg_totalventas1" class="progress" style="height: 30px; background-color: #74726C;">

													</div>
			            			</td>
			            		</tr>
			            	</table>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 15px; width: 100%;">
			            	<label style="font-size: 16px; color: #ffffff;">
			            		Av.América
			            	</label>

			            	<table style="width: 100%">
			            		<tr>
			            			<td id="td_totalventas2" style="color: #ffffff; font-size: 16px; width: 80px; text-align: right;">

			            			</td>

			            			<td style="text-align: center;">
			            				<div id="prg_totalventas2" class="progress" style="height: 30px; background-color: #74726C;">

	          							</div>
			            			</td>
			            		</tr>
			            	</table>
		            	</div>
		            </div>
            	</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row" style="padding: 5px; margin-top: -10px;">

				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
					<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; height: 380px;">
						<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px; color: #ffffff;">
							<div class="col-md-7 col-sm-7 col-xs-12" style="padding: 5px; margin-left: 10px;">
								<h5>Promedio Diario de Muestras</h5>
							</div>

							<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px; margin-top: -10px;">
								<div class="d-flex">
									<select id="filtro_anho" class="form-select" style="text-align: left; font-size: 14px; width: 70%; margin-left: 15px;margin-right: 15px;" onchange="f_LoadFiltroMeses();">
										<?php
											$a = 1;

											$q_anhos = "SELECT DISTINCT YEAR(fechahora_recepcion) AS ANHO
																	  FROM recepcion_ensayos_cabecera
																	 WHERE fechahora_recepcion IS NOT NULL
																	ORDER BY 1 DESC";

							        if ($res_anhos = mysqli_query($enlace, $q_anhos)){
							          if (mysqli_num_rows($res_anhos) > 0) {
							            while($row_anhos = mysqli_fetch_array($res_anhos)){
							              ?>

							              <option value="<?php echo $row_anhos["ANHO"]; ?>" <?php echo (($a == 1) ? 'selected' : '') ?>><?php echo $row_anhos["ANHO"]; ?></option>

							              <?php

							              $a ++;
							            }
							          }
							        }

											?>
									</select>

									<select id="filtro_mes" class="form-select" style="text-align: left; font-size: 14px; margin-left: 15px;margin-right: 15px;" onchange="f_LoadPromedioMuestrasDiario();">

									</select>
								</div>
							</div>
						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
							<hr style="border-color: #D9D9D9;"/>
						</div>

						<div class="d-flex" style="padding-left: 20px; margin-top: -20px; margin-bottom: 5px; font-size: 13px; text-align: center;">
							<div class="col-md-6 col-sm-6 col-xs-6" style="width: 50%;">
								<label id="lbl_totalpromedio" style="padding-top: 95px; padding-left: 5px; margin-top: -15px; font-size: 40px; background-image: url('<?php echo $dash_circle; ?>'); background-size: cover; background-size: 320px; background-repeat: no-repeat; background-position: center; width: 250px; height: 270px; vertical-align: middle; color: #ffffff;">

	            	</label>
	            </div>

	            <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 10px; margin-top: 20px; width: 50%;">
	            	<div class="row" style="margin-left: -10px;">
		            	<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 15px; width: 100%;">
			            	<label style="font-size: 16px; color: #ffffff;">
			            		Parque Industrial
			            	</label>

			            	<table style="width: 100%">
			            		<tr>
			            			<td id="td_totalpromedio1" style="color: #ffffff; font-size: 16px; width: 80px; text-align: right;">

			            			</td>

			            			<td style="text-align: center;">
			            				<div id="prg_totalpromedio1" class="progress" style="height: 30px; background-color: #74726C;">

													</div>
			            			</td>
			            		</tr>
			            	</table>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 15px; width: 100%;">
			            	<label style="font-size: 16px; color: #ffffff;">
			            		Av.América
			            	</label>

			            	<table style="width: 100%">
			            		<tr>
			            			<td id="td_totalpromedio2" style="color: #ffffff; font-size: 16px; width: 80px; text-align: right;">

			            			</td>

			            			<td style="text-align: center;">
			            				<div id="prg_totalpromedio2" class="progress" style="height: 30px; background-color: #74726C;">

	          							</div>
			            			</td>
			            		</tr>
			            	</table>
		            	</div>
		            </div>
            	</div>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
					<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; height: 380px;">
						<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px; color: #ffffff;">
							<h5>Comparativo - Promedio Diario de Muestras</h5>
						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
							<hr style="border-color: #D9D9D9;"/>
						</div>

						<div id="chrt_PromedioMuestrasDiario" class="d-flex justify-content-center" style="margin-top: -25px; width: 100%; height: 350px; text-align: center;">

						</div>
					</div>
				</div>
			</div>

			<div class="row" style="padding: 5px; margin-top: -10px;">
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
					<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; height: 380px;">
						<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px; color: #ffffff;">
							<h5>N° de Ventas por Medios de Pago</h5>
						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
							<hr style="border-color: #D9D9D9;"/>
						</div>

						<div id="chrt_CountVentasMediosPago" class="d-flex justify-content-center" style="margin-top: -40px; width: 100%; height: 350px; text-align: center;">

						</div>
					</div>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px;">
					<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; height: 380px;">
						<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px; color: #ffffff;">
							<h5>Total de Ventas (S/) por Medios de Pago</h5>
						</div>

						<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
							<hr style="border-color: #D9D9D9;"/>
						</div>

						<div id="chrt_TotalVentasMediosPago" class="d-flex justify-content-center" style="margin-top: -40px; width: 100%; height: 350px; text-align: center;">

						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

		<!-- Bootstrap -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

		<!-- Apache eCharts -->
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.1/echarts.min.js"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.1/dist/echarts.min.js"></script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_Init(){
				// Carga el detalle de información
					f_LoadResultados();

				// Carga filtro de meses
					f_LoadFiltroMeses();

				// Inicializa los gráficos
					f_LoadCharts();
			}

			function f_LoadResultados(){
				// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	// var filtro_sucursal = $("#filtro_sucursal").val();
        	// var filtro_mediospago = $("#filtro_mediospago").val();

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

        // Cargando datos
        	f_Loading(1);

	        $.post( "apis/backend.php", { accion: "dash_TotalMuestras", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
	          function( data ) {
	            if(data.estado == 1){
	            	// Pinta el total de muestras
	            		$("#lbl_TotalMuestras").html(data.total_muestras1 + data.total_muestras2);

	            	// Pinta los totales por oficina
	            		$("#td_totalmuestras1").html(parseFloat((data.total_muestras1 / (data.total_muestras1 + data.total_muestras2)) * 100).toFixed(0) + ' %');

	            		$("#prg_totalmuestras1").html('<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; font-size: 14px; font-weight: bold;">' + data.total_muestras1 + '</div>');

	            		$("#td_totalmuestras2").html(parseFloat((data.total_muestras2 / (data.total_muestras1 + data.total_muestras2)) * 100).toFixed(0) + ' %');

	            		$("#prg_totalmuestras2").html('<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; font-size: 14px; color: #212529; font-weight: bold;">' + data.total_muestras2 + '</div>');

	            	// Pinta el N° de muestras
	            		var num_venta1 = parseFloat(data.num_ventas1);
	            		var num_venta2 = parseFloat(data.num_ventas2);

	            		$("#num_ventas").html(parseFloat(parseFloat(num_venta1) + parseFloat(num_venta2)));
	            		$("#num_ventas1").html(parseFloat(num_venta1));
	            		$("#num_ventas2").html(parseFloat(num_venta2));

	          		// Pinta el total de Ventas
	            		var total_venta1 = data.total_ventas1;
	            		var total_venta2 = data.total_ventas2;
	            		var total_venta = data.total_ventas;

	            		$("#lbl_totalventas").html(total_venta);

	            	// Pinta los totales por oficina
	            		$("#td_totalventas1").html(parseFloat((total_venta1.replace(/,/g, '') / total_venta.replace(/,/g, '')) * 100).toFixed(0) + ' %');

	            		$("#prg_totalventas1").html('<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; font-size: 16px; font-weight: bold;">' + total_venta1 + '</div>');

	            		$("#td_totalventas2").html(parseFloat((total_venta2.replace(/,/g, '') / total_venta.replace(/,/g, '')) * 100).toFixed(0) + ' %');

	            		$("#prg_totalventas2").html('<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; font-size: 16px; color: #212529; font-weight: bold;">' + total_venta2 + '</div>');
	            }
	            else{
	              alert("No se encontraron resultados.");
	            }

	            f_Loading(0);

	          }, "json");
			}

			function f_LoadFiltroMeses(){
				var cod_anho = $("#filtro_anho").val();

				$("#filtro_mes").html('');

				$.post( "apis/backend.php", { accion: "dash_LoadFiltroMeses", cod_anho: cod_anho }, 
          function( data ) {
            if(data.estado == 1){
            	$("#filtro_mes").html(data.html);

            	f_LoadPromedioMuestrasDiario();
            }
          	else{
              alert("No se encontraron resultados.");
            }

          }, "json");
			}

			function f_LoadCharts(){
				f_LoadChart_CountVentasMediosPago();
				f_LoadChart_TotalVentasMediosPago();
				f_LoadChart_PromedioMuestrasDiario();
			}

			function f_LoadChart_CountVentasMediosPago(){
				// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	// var filtro_sucursal = $("#filtro_sucursal").val();
        	// var filtro_mediospago = $("#filtro_mediospago").val();

				// Cargar Chart
          $("#chrt_CountVentasMediosPago").load("charts/dashboard_countventasmediospago.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin);
			}

			function f_LoadChart_TotalVentasMediosPago(){
				// Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	// var filtro_sucursal = $("#filtro_sucursal").val();
        	// var filtro_mediospago = $("#filtro_mediospago").val();

				// Cargar Chart
          $("#chrt_TotalVentasMediosPago").load("charts/dashboard_totalventasmediospago.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin);
			}

			function f_LoadPromedioMuestrasDiario(){
				var cod_anho = $("#filtro_anho").val();
				var cod_mes = $("#filtro_mes").val();

				$.post( "apis/backend.php", { accion: "dash_PromedioMuestrasDiario", cod_anho: cod_anho, cod_mes: cod_mes }, 
          function( data ) {
            if(data.estado == 1){
            	// Pinta el total de muestras
            		$("#lbl_totalpromedio").html(data.total_muestras1 + data.total_muestras2);

            	// Pinta los totales por oficina
            		$("#td_totalpromedio1").html(parseFloat((data.total_muestras1 / (data.total_muestras1 + data.total_muestras2)) * 100).toFixed(0) + ' %');

            		$("#prg_totalpromedio1").html('<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; font-size: 14px; font-weight: bold;">' + data.total_muestras1 + '</div>');

            		$("#td_totalpromedio2").html(parseFloat((data.total_muestras2 / (data.total_muestras1 + data.total_muestras2)) * 100).toFixed(0) + ' %');

            		$("#prg_totalpromedio2").html('<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; font-size: 14px; color: #212529; font-weight: bold;">' + data.total_muestras2 + '</div>');
            }
          	else{
              alert("No se encontraron resultados.");
            }

          }, "json");
			}

			function f_LoadChart_PromedioMuestrasDiario(){
				// Obteniendo filtros

				// Cargar Chart
          $("#chrt_PromedioMuestrasDiario").load("charts/dashboard_promediomuestrasdiario.php");
			}

			function f_Loading(_is_show){
				if (_is_show == 1){
					$("#wt_loading").show();
				}
				else{
					$("#wt_loading").hide();
				}
			}
		</script>
	</body>
</html>