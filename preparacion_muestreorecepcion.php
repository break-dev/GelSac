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

		<title><?php echo $nom_app; ?> | Preparación Mecánica - Recepción de Muestras</title>

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
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadResultados();">

											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadResultados();">
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 5px; padding-right: 5px;">
											<h6 style="font-size: 14px;">N° Lote</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 5px; padding-right: 5px;">
											<input id="filtro_CI" type="text" class="form-control" style="font-size: 14px;" onkeyup="f_SearchCI();">
										</div>
									</div>
								</div>

								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Tipo Muestra</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<select id="filtro_tipomuestra" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
												<option selected value="">Elija una opción...</option>

												<?php

												$q_tipo = "SELECT Id,
                            							UPPER(descripcion) AS descripcion
					                           FROM tbconfig_tipomuestraspreparacion
					                          WHERE estado = 'A'";

								        if ($res_tipo = mysqli_query($enlace, $q_tipo)){
								          if (mysqli_num_rows($res_tipo) > 0) {
								            while($row_tipo = mysqli_fetch_array($res_tipo)){
								              ?>

								              <option value="<?php echo $row_tipo["Id"]; ?>"><?php echo $row_tipo["descripcion"]; ?></option>

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

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="col-md-8 col-sm-8 col-xs-12" style="padding: 0px;">
								<div class="d-flex" style="padding: 20px;">
									<div class="">
										<h5>Resumen de Muestras</h5>
									</div>

									<div class="" style="margin-top: -7px;">
										<div class="d-flex flex-row-reverse" style="padding: 10px; font-size: 14px;">
											<div class="form-check" style="margin-left: 10px;">
											  <input class="form-check-input" type="radio" name="rd_recepcion" id="rd_sinrecepcion" onchange="f_LoadResultados();">
											  <label class="form-check-label" for="rd_sinrecepcion">
											    Sin Recepción
											  </label>
											</div>

											<div class="form-check" style="margin-left: 10px;">
											  <input class="form-check-input" type="radio" name="rd_recepcion" id="rd_conrecepcion" onchange="f_LoadResultados();">
											  <label class="form-check-label" for="rd_conrecepcion">
											    Con Recepción
											  </label>
											</div>

											<div class="form-check" style="margin-left: 20px;">
											  <input class="form-check-input" type="radio" name="rd_recepcion" id="rd_todos" onchange="f_LoadResultados();" checked>
											  <label class="form-check-label" for="rd_todos">
											    Todas
											  </label>
											</div>
										</div>
									</div>

									<div class="" style="margin-top: -5px; margin-left: 10px;">
										<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px;">
				              <b>Exportar a Excel</b>
				            </button>
									</div>

									<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
										<img src="<?php echo $img_waiting ?>" style="width: 20px;">
										<label style="font-style: italic;"> Cargando datos...</label>
									</div>
								</div>
							</div>

							<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px;">
								<div class="d-flex" style="padding: 20px;">
									<div class="d-flex bg-danger" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 100%; padding: 5px; margin-top: -15px; vertical-align: middle;">
										<label style="color: #ffffff; margin-top: 7px; margin-left: 10px;">Recepción: </label>

										<input id="barcode_lectura" type="text" class="form-control" style="margin-left: 15px; font-size: 16px; font-weight: bold; text-align: center; text-transform: uppercase;">

										<img src="<?php echo $barcode_laser ?>" style="width: 55px; margin-left: -60px; margin-right: 10px;">
									</div>
								</div>
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
								<table class="table table-bordered table-hover">
				        	<thead>
				        		<tr style="font-size: 14px;">
				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
				        				N°
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
				        				Fecha Hora Ingreso Planta
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
				        				Cód. Lote
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 140px;" hidden>
				        				Placa
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;" hidden>
				        				Placa 2
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
				        				Tipo Muestra
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
				        				Peso Neto Lote (Tn)
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
				        				Peso Muestra (Kg)
				        			</th>

				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px; background-color: #FF5F5D;">
				        				Información de Recepción Laboratorio
				        			</th>
				        		</tr>

				        		<tr style="font-size: 14px;">
				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px; background-color: #FF5F5D;">
				        				Fecha y Hora
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; background-color: #FF5F5D;">
				        				Usuario
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
		<div class="modal fade modal-dialog-scrollable" id="modal_addrecepcion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addrecepcionLabel" aria-hidden="true">
		  <div class="modal-dialog" style="margin-top: 15%;">
		    <div class="modal-content">
		      <div class="modal-header">
		      	<div class="d-flex">
			        <h1 class="modal-title fs-5">Recepción Muestra: </h1>
			        <h1 class="modal-title fs-5" id="modal_addrecepcionLabel" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h1>
			      </div>
		      </div>

		      <div class="modal-body">
		      	<div class="row" style="padding: 5px; margin-left: 5px;">
							<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 25px;">
								<div class="col-md-5 col-sm-5 col-xs-12" style="padding: 5px; margin-top: 3px;">
									Tipo Muestra:
								</div>

								<div class="col-md-7 col-sm-7 col-xs-12">
									<select id="addrecepcion_tipomuestra" class="form-select" data-placeholder="Elija una opción...">

									</select>
								</div>
							</div>
						</div>

		      	<div class="row" style="padding: 5px; margin-left: 5px;">
							<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 25px;">
								<div class="col-md-5 col-sm-5 col-xs-12" style="padding: 5px; margin-top: 3px;">
									Peso Muestra (Kg):
								</div>

								<div class="col-md-3 col-sm-3 col-xs-12">
									<input id="txt_getpeso" type="number" class="form-control" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #F2AA52; height: 45px;">
								</div>
							</div>

							<div id="div_SinConexion" class="row" style="text-align: right; display: none;">
                <label class="control-label" style="color: #d9534f; font-size: 12px;">
                  Esperando peso...
                </label>
              </div>
						</div>
		      </div>

		      <input id="getpeso_idlote" type="hidden">

		      <div class="modal-footer">
		        <div id="div_addrecepcion_buttons" class="d-flex">
			        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			        <button class="btn btn-warning" type="button" style="font-size: 14px; margin-left: 5px;" onclick="f_ConfirmarRecepcion();">Confirmar</button>
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
					$("#nv_titulo").html('| Preparación Mecánica - Recepción de Muestras');

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
        	// var filtro_estadomuestra = $("#filtro_estadomuestra").val();
        	// var filtro_envasemuestra = $("#filtro_envasemuestra").val();
        	// var filtro_tiposmuestra = $("#filtro_tiposmuestra").val();
        	// var filtro_ensayosmuestra = $("#filtro_ensayosmuestra").val();
        	var filtro_CI = $("#filtro_CI").val();
        	var filtro_opt1 = (($("#rd_sinrecepcion").prop('checked')) ? 1 : 0);
        	var filtro_opt2 = (($("#rd_conrecepcion").prop('checked')) ? 1 : 0);
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

				// Cargando detalles
	        $("#tbl_detalle").html('');

        	f_LoadingDetalle(1);

        	$.post( "apis/backend.php", { accion: "get_Preparacion_RecepcionMuestras", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_CI: filtro_CI, filtro_opt1: filtro_opt1, filtro_opt2: filtro_opt2, filtro_opt3: filtro_opt3 }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_detalle").html(data.html);

	            	// Agrega el Focus
									document.getElementById("barcode_lectura").focus();
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

            	f_LoadingDetalle(0);

	          }, "json");
    	};

    	$("#barcode_lectura").keyup(function (e) {
		    if (e.keyCode === 13) {
		    	// Setea Código Interno
            var _barcode = $("#barcode_lectura").val().trim().replace(/'/g, '-');
						_barcode = _barcode.substring(0, 8).toUpperCase();
						$("#barcode_lectura").val(_barcode);

		    	// Seteando el título de la venta
		    		$("#modal_addrecepcionLabel").html($(this).val().trim().substring(0, 8).toUpperCase());

		    	// Seteando objetos
		    		$("#txt_getpeso").val('');

		    	// Validando si tiene recepción previa
          	$.post( "apis/backend.php", { accion: "validar_Preparacion_RecepcionMuestras", CI: _barcode },
              function( data ) {
              	if (data.estado == 1){
              		// Carga lista de Tipos de Muestra
              			var _html = '';
              			var t = 1;

              			$("#addrecepcion_tipomuestra").html('');

              			$.each( data.res, function( key, val ) {
              				_html += '<option ' + ((t == 1) ? 'selected' : '') + ' value="' + val.id_tipomuestrapreparacion + '">' + val.TIPO_MUESTRA + '</option>';
              				_html += '</option>';

              				t ++;
              			});

              			$("#addrecepcion_tipomuestra").html(_html);

            			// Cerra modal
	    							f_OpenModal('modal_addrecepcion');
	    					}
              	else{
              		if(data.estado == 2 || data.estado == 3){
	              		if (data.estado == 2){
	              			alert("La muestra ingresada no se encuentra en la B.D.");
	              		}
	              		else{
	              			alert("La muestra ya fue recepcionada para ambos Tipos de Muestra.");
	              		}
	              	}
	              	else{
	                	alert("Ocurrió un error al momento de registrar la Recepción de la muestra leída.");
	                }
              	}

              }, "json");
		    }
		  });

    	function f_ExportToExcel(){
        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();
        	var filtro_estadomuestra = $("#filtro_estadomuestra").val();
        	var filtro_envasemuestra = $("#filtro_envasemuestra").val();
        	var filtro_tiposmuestra = $("#filtro_tiposmuestra").val();
        	var filtro_ensayosmuestra = $("#filtro_ensayosmuestra").val();
        	var filtro_CI = $("#filtro_CI").val();
        	var filtro_opt1 = (($("#rd_sinrecepcion").prop('checked')) ? 1 : 0);
        	var filtro_opt2 = (($("#rd_conrecepcion").prop('checked')) ? 1 : 0);
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

        window.location.href = "export_to_excel/lq_recepcionmuestras.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_estadomuestra="+filtro_estadomuestra+"&filtro_envasemuestra="+filtro_envasemuestra+"&filtro_tiposmuestra="+filtro_tiposmuestra+"&filtro_ensayosmuestra="+filtro_ensayosmuestra+"&filtro_CI="+filtro_CI+"&filtro_opt1="+filtro_opt1+"&filtro_opt2="+filtro_opt2+"&filtro_opt3="+filtro_opt3;
    	};
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			// Buscar Código Internos
				function f_SearchCI(){
					var _find = $("#filtro_CI").val().trim();

					if (_find.length >= 3 || _find.length == 0){
						f_LoadResultados();
					}
				}

			// Vuelve a colocar el Focus en el campo de búsqueda de Códigos de Barra
				function f_SelectBarcode(){
					document.getElementById("barcode_lectura").focus();
				}

			$("#modal_addrecepcion").on('shown.bs.modal', function(){
      	$("#txt_getpeso").focus();
    	});

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
				function f_ConfirmarRecepcion(){
					// Recupera variables
						var cod_lote = $("#modal_addrecepcionLabel").html();
						var cod_tipomuestra = $("#addrecepcion_tipomuestra").val();
            var peso = $("#txt_getpeso").val();

          // Validando datos
            if (cod_tipomuestra == null){
              alert("Debe seleccionar el Tipo de Muestra.");

              return;
            }
            if (cod_tipomuestra.length == 0){
              alert("Debe seleccionar el Tipo de Muestra.");

              return;
            }

            if (peso == null){
              alert("Debe ingresar el Peso.");

              return;
            }
            if (peso.length == 0){
              alert("Debe ingresar el Peso.");

              return;
            }
            if (peso < 0){
              alert("El Peso ingresado no es correcto.");

              return;
            }

          // Actualiza Recepción
						$.post( "apis/backend.php", { accion: "update_Preparacion_RecepcionMuestras", cod_lote: cod_lote, cod_tipomuestra: cod_tipomuestra, peso_muestra: peso },
              function( data ) {
                if(data.estado == 1){
                	// Cambiando el color de la muestra
                		$(".td_recepcionado_" + cod_tipomuestra + '_' + cod_lote).css('background-color', '#93D94E');
                		$("#tdrecepcion_1_" + cod_tipomuestra + '_' + cod_lote).html(data.recepcion_fecha);
                		$("#tdrecepcion_2_" + cod_tipomuestra + '_' + cod_lote).html(data.recepcion_usuario);

                		$("#tdrecepcion_1_" + cod_tipomuestra + '_' + cod_lote).css('color', '');

                		$("#barcode_lectura").val('');

              		// Seteando el valor del peso de la muestra
                		$("#td_pesomuestra_" + cod_tipomuestra + '_' + cod_lote).html(peso);

                	// Cerrano modal
										f_cerrarModal('modal_addrecepcion');
                }
                else{
                	if(data.estado == 2 || data.estado == 3){
                		if (data.estado == 2){
                			alert("La muestra ingresada no se encuentra en la B.D.");
                		}
                		else{
                			alert("La muestra ya fue recepcionada anteriormente.");
                		}
                	}
                }

              }, "json");
				}

			// Cambiar estado de registros
        function f_CambiarEstado(_Estado, _id_usuario){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " el Usuario seleccionado?")){
            $.post( "apis/backend.php", { accion: "update_EstadoUsuario", id_usuario: _id_usuario, estado: _Estado }, 
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de cambiar el estado");
                }

              }, "json");
          }
        };

      // Eliminar registros
        function f_EliminarUsuario(_id_usuario){
          if(confirm("¿Está seguro de eliminar el Usuario seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_Usuario", id_usuario: _id_usuario },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar el Usuario.");
                }
              }, "json");
          }
        };
		</script>


		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');
					$("#div_resumen").css('width', '100%');
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