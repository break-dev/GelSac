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

		<title><?php echo $nom_app; ?> | Administración de Puntos de Destino</title>

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

				<!-- Modal (Menú Lateral) -->
	      <div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
	        <div class="modal-dialog modal-right" style="margin-top: 0px !important; margin-left: 0px !important;">
	          <div class="modal-content">
	            <div class="modal-header">
	              <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
	              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>
	            <div  class="modal-body" style="padding: 0px !important;">
	            
	            	<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Planta</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_planta" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
													<option selected value="">Elija una opción...</option>

													<?php

													$q_plantas = "SELECT Id,
		                            							 UPPER(descripcion) AS descripcion
							                            FROM tbconfig_plantas
							                           WHERE estado = 'A'";

									        if ($res_plantas = mysqli_query($enlace, $q_plantas)){
									          if (mysqli_num_rows($res_plantas) > 0) {
									            while($row_plantas = mysqli_fetch_array($res_plantas)){
									              ?>

									              <option value="<?php echo $row_plantas["Id"]; ?>"><?php echo $row_plantas["descripcion"]; ?></option>

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
												<h6 style="font-size: 14px;">Por Modalidad de Envío</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_modalidadenvio" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
													<option selected value="">Elija una opción...</option>

													<?php

													$q_modalidadenvio = "SELECT Id,
		                            											UPPER(descripcion) AS descripcion
											                           FROM tbconfig_modalidadenvio
											                          WHERE estado = 'A'";

									        if ($res_modalidadenvio = mysqli_query($enlace, $q_modalidadenvio)){
									          if (mysqli_num_rows($res_modalidadenvio) > 0) {
									            while($row_modalidadenvio = mysqli_fetch_array($res_modalidadenvio)){
									              ?>

									              <option value="<?php echo $row_modalidadenvio["Id"]; ?>"><?php echo $row_modalidadenvio["descripcion"]; ?></option>

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

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-10 col-sm-10 col-xs-10">
									<div class="d-flex">
										<h5>Resumen de Puntos de Destino</h5>

										<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
											<img src="<?php echo $img_waiting ?>" style="width: 20px;">
											<label style="font-style: italic;"> Cargando datos...</label>
										</div>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-primary" type="button" onclick="f_AdminPuntoDestino('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
			              <b> + Nuevo Punto de Destino</b>
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

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 220px;">
				        				Planta
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
				        				Modalidad Envío
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
				        				Dirección
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
		<div class="modal fade" id="modal_addpuntodestino" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addpuntodestinoLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-6" id="modal_addpuntodestinoLabel">Nuevo Cliente</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Planta:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="puntodestino_planta" class="form-select" style="text-align: left;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_plantas = "SELECT Id,
	                            				 UPPER(descripcion) AS descripcion
			                            FROM tbconfig_plantas
			                           WHERE estado = 'A'";

					        if ($res_plantas = mysqli_query($enlace, $q_plantas)){
					          if (mysqli_num_rows($res_plantas) > 0) {
					            while($row_plantas = mysqli_fetch_array($res_plantas)){
					              ?>

					              <option value="<?php echo $row_plantas["Id"]; ?>"><?php echo $row_plantas["descripcion"]; ?></option>

					              <?php
					            }
					          }
					        }

									?>

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Modalidad Envío:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="puntodestino_modalidadenvio" class="form-select" style="text-align: left;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_modalidadenvio = "SELECT Id,
	                            								UPPER(descripcion) AS descripcion
							                           FROM tbconfig_modalidadenvio
							                          WHERE estado = 'A'";

					        if ($res_modalidadenvio = mysqli_query($enlace, $q_modalidadenvio)){
					          if (mysqli_num_rows($res_modalidadenvio) > 0) {
					            while($row_modalidadenvio = mysqli_fetch_array($res_modalidadenvio)){
					              ?>

					              <option value="<?php echo $row_modalidadenvio["Id"]; ?>"><?php echo $row_modalidadenvio["descripcion"]; ?></option>

					              <?php
					            }
					          }
					        }

									?>

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Dirección:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="puntodestino_direccion" type="text" class="form-control col-md-12 col-xs-12" rows="6"></textarea>
							</div>
						</div>
		      </div>

		      <input id="hd_idpuntodestino" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarPuntoDestino();">Grabar</button>
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
					$("#nv_titulo").html('| Administración de Puntos de Destino');

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

        var cod_planta = $("#filtro_planta").val();
        var cod_modalidadenvio = $("#filtro_modalidadenvio").val();

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

        $.post( "apis/backend.php", { accion: "get_ListaPuntosDestino", cod_planta: cod_planta, cod_modalidadenvio: cod_modalidadenvio }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.DES_PLANTA;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.DES_MODALIDADENVIO;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.direccion;
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

                  _html += '      <a class="success" href="javascript: f_AdminPuntoDestino(' + d + ', ' + val.Id + ', ' + val.id_planta	+ ', ' + val.id_modalidadenvio + ", '" + val.direccion + "'" + ')"><i class="bi bi-pencil-square"></i>';
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

    	function f_AdminPuntoDestino(_item, _id_puntodestino, _cod_planta, _cod_modalidadenvio, _direccion){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Punto Destino';
	        }
	        else{
            tipo = "N";
            titulo = "Nuevo Punto Destino";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_addpuntodestinoLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addpuntodestino');

	        if (tipo != 'N'){
            $("#hd_idpuntodestino").val(_id_puntodestino);
            $("#puntodestino_planta").val(_cod_planta);
		        $("#puntodestino_modalidadenvio").val(_cod_modalidadenvio);
		        $("#puntodestino_direccion").val(f_CleanInjection(_direccion));
			    }
			    else{
			    	$("#hd_idpuntodestino").val(0);
		        $("#puntodestino_planta").val('');
		        $("#puntodestino_modalidadenvio").val('');
		        $("#puntodestino_direccion").val('');
		   		}
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_CleanTxtTipo(){
				var cod_tipo = $("#filtro_listatipo").val();

				if (cod_tipo == null){
          $("#filtro_tipo").val('');

          return;
        }

        if (cod_tipo.length == 0){
          $("#filtro_tipo").val('');

          return;
        }
			}

			function f_GetListaTipoDocumento(_is_juridico){
				var _html = '<option selected value="">Elija una opción...</option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				if (_is_juridico == 0){
					if ($("#puntodestino_planta").val() == 2){
						_is_juridico = 1;
					}
				}

				$.post( "apis/backend.php", { accion: "get_listatipodocumento" }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.res, function( key, val ) {
								_html += '<option value="' + val.Id + '" ' + ((_is_juridico == 1) ? ((val.Id == 2) ? 'selected' : '') : ((val.Id == 1) ? 'selected' : '')) + '>' + val.descripcion + '</option>';
								_html += '<option value="x" style="font-size: 6px;" disabled></option>';
							});

							$("#puntodestino_modalidadenvio").html(_html);
						}
						else{
							$("#puntodestino_modalidadenvio").html('');
						}

					}, "json");
			}

			function f_GetInfoCliente(_is_representantelegal){
				if (_is_representantelegal != 1){
					var is_ruc = (($("#puntodestino_modalidadenvio").val() == 2) ? 1 : 0);
					var documento = $("#cliente_documento").val();
					var arr_response = '';

					// Limpiando objetos
						$("#cliente_razonsocial").val('');
	        	$("#puntodestino_direccion").val('');
						$("#wt_razonsocial2").hide();

					// Obteniendo información
						if (documento.length == 8 || documento.length == 11){
							$("#wt_razonsocial2").show();

							$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
		            function( data ) {
		            	if (data.estado == 1){
		            		arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

		            		if (is_ruc == 1){
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	$("#puntodestino_direccion").val(arr_response[4].split(':')[1].trim());
			            	}
			            	else{
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	$("#puntodestino_direccion").val('');
			            	}
		            	}
		            	else{
		            		$("#cliente_razonsocial").val('NO ENCONTRADO');
		              	$("#puntodestino_direccion").val('');
		            	}

		            	$("#wt_razonsocial2").hide();

		            }, "json");
						}
				}
				else{
					var documento = $("#cliente_representantedni").val();
					var arr_response = '';

					// Limpiando objetos
						$("#cliente_representantenombres").val('');
						$("#wt_representantelegal").hide();

					// Obteniendo información
						if (documento.length == 8){
							$("#wt_representantelegal").show();

							$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
		            function( data ) {
		            	if (data.estado == 1){
		            		arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

			            	$("#cliente_representantenombres").val(arr_response[0].split(':')[1].trim());
		            	}
		            	else{
		            		$("#cliente_representantenombres").val('NO ENCONTRADO');
		            	}

		            	$("#wt_representantelegal").hide();

		            }, "json");
						}
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
				function f_GrabarPuntoDestino(){
					// Recupera variables
						var id_puntodestino = $("#hd_idpuntodestino").val();
						var modo_grabar = $("#hd_modograbar").val();

            var cod_planta = f_CleanInjection($("#puntodestino_planta").val());
            var cod_modalidadenvio = f_CleanInjection($("#puntodestino_modalidadenvio").val());
            var direccion = f_CleanInjection($("#puntodestino_direccion").val());

          // Validando datos
            if (cod_planta == null){
              alert("Debe seleccionar la Planta.");

              return;
            }
            if (cod_planta.length == 0){
              alert("Debe seleccionar la Planta.");

              return;
            }

            if (cod_modalidadenvio == null){
              alert("Debe seleccionar la Modalidad de Envío.");

              return;
            }
            if (cod_modalidadenvio.length == 0){
              alert("Debe seleccionar la Modalidad de Envío.");

              return;
            }

            if (direccion == null){
                alert("Debe ingresar la Dirección.");

                return;
            }
            if (direccion.length == 0){
                alert("Debe ingresar la Dirección.");

                return;
            }

          // Grabando Datos
            $.post( "apis/backend.php", { accion: "grabar_PuntoDestino", modo_grabar: modo_grabar, id_puntodestino: id_puntodestino, cod_planta: cod_planta, cod_modalidadenvio: cod_modalidadenvio, direccion: direccion },
              function( data ) {
                if (data.estado == 2){
                  alert("Ya se tiene una dirección configurada para la Planta y Modalidad de Envío seleccionadas.\nPor favor verificar.");

                  return;
                }
                else{
                  if(data.estado == 1){
                  	f_LoadResultados();

                  	f_cerrarModal('modal_addpuntodestino');
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar el Punto de Destino");
                  }
                }

              }, "json");
				}

			// Cambiar estado de registros
        function f_CambiarEstado(_Estado, _id_puntodestino){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " el Punto de Destino seleccionado?")){
            $.post( "apis/backend.php", { accion: "update_EstadoPuntoDestino", id_puntodestino: _id_puntodestino, estado: _Estado }, 
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
        function f_EliminarRegistro(_id_puntodestino){
          if(confirm("¿Está seguro de eliminar el Punto de Destino seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_PuntoDestino", id_puntodestino: _id_puntodestino },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar el Cliente.");
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
	</body>
</html>