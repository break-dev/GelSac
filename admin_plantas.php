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

		<title><?php echo $nom_app; ?> | Administración de Plantas</title>

		<script type="text/javascript">

		</script>
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

	      <!-- Modal (Filtro Lateral) -->
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
												<h6 style="font-size: 14px;">Por RUC</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_ruc" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase;" onblur="f_LoadResultados();">
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Descripción</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_descripcion" type="text" class="form-control" style="font-size: 14px;" onblur="f_LoadResultados();">
											</div>
										</div>
									</div>
								</div>

	            </div>
	          </div>
	        </div>
	      </div>


				<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px; ">
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

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-10 col-sm-10 col-xs-10">
									<div class="d-flex">
										<h5>Resumen de Plantas</h5>

										<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
											<img src="<?php echo $img_waiting ?>" style="width: 20px;">
											<label style="font-style: italic;"> Cargando datos...</label>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-primary" type="button" onclick="f_AdminPlantas('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
			              <b> + Nueva Planta</b>
			            </button>
								</div>

								
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
								<table class="table table-bordered table-striped table-hover">
				        	<thead>
				        		<tr style="font-size: 14px;">
				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
				        				N°
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				RUC
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Descripción
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Estado
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
				        				Acción
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

		</div>

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_addplanta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addplantaLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-6" id="modal_addplantaLabel">Nueva Planta</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								RUC:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="planta_ruc" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente()">
							</div>
						</div>

		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Descripción: <img id="wt_razonsocial" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="planta_descripcion" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
							</div>
						</div>
		      </div>

		      <input id="hd_idplanta" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarPlanta();">Grabar</button>
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
					$("#nv_titulo").html('| Administración de Plantas');

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

        var filtro_descripcion = f_CleanInjection($("#filtro_descripcion").val());
        var filtro_ruc = f_CleanInjection($("#filtro_ruc").val());

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        var arr_creditos = '';
        var arr_descuentos = '';
        var c = 0;

        $("#tbl_detalle").html('');

        f_LoadingResumen(1);

        $.post( "apis/backend.php", { accion: "get_listaplantas", filtro_descripcion: filtro_descripcion, filtro_ruc: filtro_ruc }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.ruc;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.descripcion;
                _html += '  </td>';

                // Setea el Estado del registro
                  if (val.estado == 'I'){
                    bk_color = '#E6A50D';
                    estado = 'Inactivo';
                    href_estado = 'Activar';
                    href_color = '#44803F';
                    href_icon = 'bi bi-node-plus';
                  }
                  else{
                    bk_color = '#44803F';
                    estado = 'Activo';
                    href_estado = 'Inactivar';
                    href_color = '#E6A50D';
                    href_icon = 'bi bi-node-minus';
                  }

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color:' + ((val.estado == 'I') ? '#E6A50D' : '#44803F') + '; color: #ffffff;">';
                  _html += '      ' + ((val.estado == 'A') ? 'Activo' : 'Inactivo');
                  _html += '  </td>';

                // Agregando acciones
                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';

                  _html += '      <a class="success" href="javascript: f_AdminPlantas(' + d + ', ' + val.Id + ", '" + val.descripcion	+ "', '" + val.ruc + "'" + ')"><i class="bi bi-pencil-square"></i>';
                  _html += '          <font style="color: #337ab7;"> Editar</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_CambiarEstado(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                  _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_EliminarRegistro(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                  _html += '          <font style="color: #F20505;"> Eliminar</font>';
                  _html += '      </a>';

                  _html += '  </td>';

                _html += '</tr>';

                d += 1;
              });
            }
            else{
              // alert("No se encontraron resultados.");
            }

            $("#tbl_detalle").html(_html);

            f_LoadingResumen(0);

          }, "json");
    	};

    	function f_AdminPlantas(_item, _id_planta, _descripcion, _ruc){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Planta: "<b>' + _descripcion + '</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Nueva Planta";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_addplantaLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addplanta');

	        if (tipo != 'N'){
            $("#hd_idplanta").val(_id_planta);
            $("#planta_descripcion").val(f_CleanInjection(_descripcion));
		        $("#planta_ruc").val(f_CleanInjection(_ruc));
			    }
			    else{
			    	$("#hd_idplanta").val(0);
		        $("#planta_descripcion").val('');
		        $("#planta_ruc").val('');
		   		}
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_GetInfoCliente(){
				var documento = $("#planta_ruc").val();
				var arr_response = '';

				// Limpiando objetos
					$("#planta_descripcion").val('');
					$("#wt_razonsocial").hide();

				// Obteniendo información
					if (documento.length == 8 || documento.length == 11){
						$("#wt_razonsocial").show();

						if (documento.length == 8){
							is_ruc = 0;
						}
						else{
							is_ruc = 1;
						}

						$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
	            function( data ) {
	            	if (data.estado == 1){
	            		arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

	            		if (is_ruc == 1){
	                  var success = arr_response[0].split(':')[1].trim();

	                  if (success == 'false'){
	                    var razon_social = 'No se encontraron resultados.';
	                    var direccion = '';
	                  }
	                  else{
	                    var razon_social = arr_response[1].split(':')[1].trim();
	                    var direccion = arr_response[7].split(':')[1].trim();
	                    direccion = ((direccion == 'null') ? '---' : direccion);
	                  }

	                  $("#planta_descripcion").val(razon_social);
		            	}
		            	else{
		            		$("#planta_descripcion").val(arr_response[0].split(':')[1].trim());
		            	}
	            	}
	            	else{
	            		$("#planta_descripcion").val('NO ENCONTRADO');
	            	}

	            	$("#wt_razonsocial").hide();

	            }, "json");
					}
			}

			function f_LoadingResumen(_is_show){
				if (_is_show == 1){
					$("#wt_resumen").show();
				}
				else{
					$("#wt_resumen").hide();
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Graba información temporal (onblur).
				function f_GrabarPlanta(){
					// Recupera variables
						var id_planta = $("#hd_idplanta").val();
						var modo_grabar = $("#hd_modograbar").val();

            var planta_ruc = f_CleanInjection($("#planta_ruc").val());
            var planta_descripcion = f_CleanInjection($("#planta_descripcion").val());

          // Validando datos
            if (planta_ruc == null){
              alert("Debe ingresar el RUC de la planta.");

              return;
            }
            if (planta_ruc.length == 0){
              alert("Debe ingresar el RUC de la planta.");

              return;
            }

            if (planta_descripcion == null){
              alert("Debe ingresar la descripción de la planta.");

              return;
            }
            if (planta_descripcion.length == 0){
              alert("Debe ingresar la descripción de la planta.");

              return;
            }

          // Grabando Datos
            $.post( "apis/backend.php", { accion: "grabar_Planta", modo_grabar: modo_grabar, id_planta: id_planta, planta_ruc: planta_ruc, planta_descripcion: planta_descripcion },
              function( data ) {
                if (data.estado == 2){
                  alert("El RUC ingresado ya fue registrado anteriormente.\nPor favor verificar.");

                  return;
                }
                else{
                  if(data.estado == 1){
                  	f_LoadResultados();

                  	f_cerrarModal('modal_addplanta');
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar la Planta");
                  }
                }

              }, "json");
				}

			// Cambiar estado de registros
        function f_CambiarEstado(_Estado, _id_registro){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " la Planta seleccionada?")){
            $.post( "apis/backend.php", { accion: "update_EstadoPlanta", id_registro: _id_registro, estado: _Estado }, 
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
        function f_EliminarRegistro(_id_registro){
          if(confirm("¿Está seguro de eliminar la Planta seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_Planta", id_registro: _id_registro },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar la Planta.");
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