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

		<title><?php echo $nom_app; ?> | LQ - Carga de Resultados</title>

		<script type="text/javascript">
			let itemimportacion_Selected = 0;
      let idimportacion_Selected = 0;
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
											<h6 style="font-size: 14px;">Por Fecha de Importación</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadImportaciones();">

											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadImportaciones();">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="padding: 0px;">
							<div id="div_importacion" class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-7 col-sm-7 col-xs-12">
												<div class="d-flex">
													<h6>Historial de Importaciones</h6>

													<div id="wt_importacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>
											</div>

											<div class="col-md-5 col-sm-5 col-xs-12">
												<button class="btn btn-info" type="button" onclick="f_AdminImportacion('N');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
						              <b>+ Nueva Importación</b>
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
						        			<th rowspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				Item
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Fecha Hora Importación
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
						        				Observación
						        			</th>

						        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Acción
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 30px;">
						        				Descargar
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 30px;">
						        				Ver
						        			</th>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_importaciones">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_detalle" class="col-md-8 col-sm-8 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<div id="div_ShowListaDetalle" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px;" onclick="f_HideListaDetalle(1);">
                          <i class="bi bi-arrow-left-square" style="font-size: 18px;"></i>
                        </div>

                        <div id="div_HideListaDetalle" class="col-md-4 col-sm-4 col-xs-4" style="background-color: #FF5F5D; color: #ffffff; border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; vertical-align: middle; height: 27px; cursor: pointer; width: 30px; margin-left: 5px; margin-right: 5px; padding: 4px; display: none;" onclick="f_HideListaDetalle(0);">
                          <i class="bi bi-arrow-right-square" style="font-size: 18px;"></i>
                        </div>

                        <div class="col-md-9 col-sm-9 col-xs-12">
													<h6>Registros de la Importación seleccionada</h6>

													<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando datos...</label>
													</div>
												</div>

												<div class="col-md-3 col-sm-3 col-xs-12" style="text-align: center;">
													<label style="font-size: 14px;">Registros importados</label>
													<label id="num_registros" style="margin-left: 10px; font-weight: bold; font-size: 14px;">0</label>
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

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Código L.Q.
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
						        				Cliente
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        				Código Cliente
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Ensayo Inicio
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Ensayo Fin
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #00A1F2; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				H2O
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #8FBF6D; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Cu
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #8FBF6D; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				CuOx
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F2C230; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Au (g/tm)
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F2C230; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Au (oz/tc)
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #CCEDE9; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Ag (g/tm)
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #CCEDE9; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Ag (oz/tc)
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F05E5E; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				As
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #BFBFBF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Pb
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #BFBFBF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				PbOx
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F2AA52; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Zn
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F2AA52; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				ZnOx
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #A07DBF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Sb
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #8AFEFF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Bi
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #40A54A; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Cd
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #23798C; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 50px;">
						        				S
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #F279C8; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 50px;">
						        				Fe
						        			</th>

						        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
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
		<div class="modal fade modal-dialog-scrollable" id="modal_adminimportaciones" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminimportacionesLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_adminimportacionesLabel">
		        	Nueva Importación
		        </h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px;">
						  <input class="form-control" id="file_upload" type="file">
						</div>

						<div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
								Observación:
							</div>

							<div class="col-md-9 col-sm-9 col-xs-9">
								<textarea id="observacion" type="text" class="form-control obj_cab col-md-12 col-xs-12" style="margin-top: 5px; text-transform: uppercase;" rows="3"></textarea>
							</div>
						</div>
		      </div>

		      <div class="modal-footer">
		      	<div id="wt_adminmimportaciones" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button id="btn_cerrarimportacion" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button id="btn_grabarimportacion" type="button" class="btn btn-primary" onclick="f_ConfirmarImportacion();">Importar</button>
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
					$("#nv_titulo").html('| LQ - Carga de Resultados');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadImportaciones();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadImportaciones(){
        var _html = '';
        var d = 1;

        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();

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

				// Cargando Lista de Racks
	        $("#tbl_importaciones").html('');
	        $("#tbl_detalle").html('');

	        f_LoadingImportaciones(1);

	        $.post( "apis/backend.php", { accion: "get_CierreResultados_ListaImportacion", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_importaciones").html(data.html);

	            	itemimportacion_Selected = 1;
								idimportacion_Selected = data.id_importacion;
	            }
	            else{
	              // alert("No se encontraron resultados.");
	            }

	            f_LoadingImportaciones(0);

	          }, "json");

    	};

      function f_AdminImportacion(){
        // Registrando el modo

        // Colocando Títulos

        // Seteando datos
          $("#file_upload").val('');
					$("#observacion").val('');

        // Abre modal
        	f_OpenModal('modal_adminimportaciones');
      };

      function f_LoadItemImportacion(_item, _id_importacion){
        var _html = '';

        // Pinta selección
          f_ColorSelected(_item);

        // Cargando datos
          f_LoadingDetalle(1);

          $("#tbl_detalle").html(_html);

          $.post( "apis/backend.php", { accion: "get_CierreResultados_DetalleImportacion", id_importacion: _id_importacion }, 
            function( data ) {
              if(data.estado == 1){
                // Actualiza la tabla de Muestras
                  $("#tbl_detalle").html(data.html);

                // Actualiza el número de registros
                  $("#num_registros").html(data.r);
              }

              f_LoadingDetalle(0);

            }, "json");

        itemimportacion_Selected = _item;
        idimportacion_Selected = _id_importacion;
      };

      function f_ColorSelected(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_importaciones tr").each(function () {
          $("#tr_item_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_item_" + _item).css('background-color', '#FFF587');
      };

      function f_DownloadFile(_file_name){
        window.location.href = 'files/' + _file_name;
      };
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingImportaciones(_is_show){
				if (_is_show == 1){
					$("#wt_importacion").show();
				}
				else{
					$("#wt_importacion").hide();
				}
			}

			function f_LoadingConfirmarImportacion(_is_show){
				if (_is_show == 1){
					$("#wt_adminmimportaciones").show();

					$("#btn_cerrarimportacion").prop('disabled', true);
					$("#btn_cerrarimportacion").css('background-color', '#C2C0A6');
					$("#btn_grabarimportacion").prop('disabled', true);
					$("#btn_grabarimportacion").css('background-color', '#C2C0A6');
				}
				else{
					$("#wt_adminmimportaciones").hide();

					$("#btn_cerrarimportacion").prop('disabled', false);
					$("#btn_cerrarimportacion").css('background-color', '');
					$("#btn_grabarimportacion").prop('disabled', false);
					$("#btn_grabarimportacion").css('background-color', '');
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

      function f_HideListaDetalle(_x){
        if (_x == 1){
          $("#div_importacion").hide();
          $("#div_detalle").width('100%');

          f_CerrarDiv('C', 'div_ShowListaDetalle');
          f_CerrarDiv('A', 'div_HideListaDetalle');
          }
        else{
          $("#div_importacion").show();
          $("#div_detalle").width('');

          f_CerrarDiv('A', 'div_ShowListaDetalle');
          f_CerrarDiv('C', 'div_HideListaDetalle');
        }
      };
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_ConfirmarImportacion(){
				var _file = $("#file_upload").val();
				var _observacion = $("#observacion").val().replace(/'/g, '').replace(/"/g, '').trim().toUpperCase();

				// Validando datos
					if (_file == null){
	          alert("Debe seleccionar un Archivo.");

	          return;
	        }
	        if (_file.length == 0){
	          alert("Debe seleccionar un Archivo.");

	          return;
	        }

	      // Guardando datos
	        var _html = '';

	        f_LoadingConfirmarImportacion(1);

          $.post( "apis/backend.php", { accion: "import_CierreResultados_ResultadosLQ", nombre_archivo: $("#file_upload")[0].files[0]["name"], observacion: _observacion },
              function( data ) {
                if(data.estado == 1){
                	var _id_importacion = data.id_importacion;
                	var _fechahoraregistro = data.g_fecha;

                  // Obtiene el total de Importaciones
                    var item_importacion = 1;

                    $("#tbl_importaciones tr").each(function () {
                      item_importacion += 1;
                    });

                  // Obtiene los registros actuales de Racks
                    _html = $("#tbl_importaciones").html();

                  // Agregando el nuevo Rack
                    _html += '<tr id="tr_item_' + item_importacion + '" style="cursor: pointer; font-size: 13px;">';

                    _html += '  <td id="td_item_1_' + item_importacion + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
                    _html += '    ' + item_importacion;
                    _html += '  </td>';

                    _html += '  <td id="td_item_2_' + item_importacion + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
                    _html += '    ' + _fechahoraregistro;
                    _html += '  </td>';

                    _html += '  <td id="td_item_3_' + item_importacion + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                    _html += '    ' + _observacion;
                    _html += '  </td>';

                    _html += '  <td id="td_item_4_' + item_importacion + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
                    _html += '  	<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_DownloadFile(' + "'file_" + _id_importacion + ".xls'" + ');">';
										_html += '    	<i class="bi bi-arrow-down-circle"></i>';
										_html += '    </label>';
                    _html += '  </td>';

										_html += '  <td id="td_item_5_' + item_importacion + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 14px; width: 30px;">';
										_html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #4C8B44; color: #ffffff; cursor: pointer;" onclick="f_LoadItemImportacion(' + item_importacion + ', ' + _id_importacion + ');">';
										_html += '      	<i class="bi bi-box-arrow-in-up-right"></i>';
										_html += '      </label>';
										_html += '  </td>';

                    _html += '</tr>';

                  $("#tbl_importaciones").html(_html);

                	// Actualiza variables
                		itemimportacion_Selected = item_importacion;
                		idimportacion_Selected = _id_importacion;

	                // Cargando Archivo
                    var formData = new FormData();
                    var files = $('#file_upload')[0].files[0];

                    formData.append('file', files);
                    formData.append('id_importacion', _id_importacion);
                    formData.append('accion', 'import_CierreResultados_ExcelToBD');

                    $.ajax({
                      url: 'apis/backend.php',
                      type: 'POST',
                      data: formData,
                      contentType: false,
                      processData: false,
                      success: function(response) {
                        if (response == 0) {
                          alert('Ocurrió un error al momento de grabar el archivo.');
                        }
                        else{
                          // f_LoadResultados();
                        }
                      }
                    });
                }
                else{
                  alert("Ocurrió un error al momento de confirmar la importación.");

                  f_LoadingConfirmarImportacion(0);

                  return;
                }

	              f_LoadingConfirmarImportacion(0);

	              f_cerrarModal("modal_adminimportaciones");

              }, "json");
			}
			// ------------------------------------------------------------------
			function f_GrabarRack(){
        var _id_rack = $("#id_rack").val();
        var _item_rack = $("#item_rack").val();
        var _modo = $("#modo_grabarrack").val();

        var _fecha_rack = $("#fecha_rack").val();
        var _hora_rack = $("#hora_rack").val();
        var _registro_rack = _fecha_rack + ' ' + _hora_rack;
        var _nombre_rack = $("#nombre_rack").val().trim().toUpperCase();
				var _estufa_rack = $("#estufa_rack").val().trim().toUpperCase();
				var _estufa_rack_des = $("#estufa_rack option:selected").text().trim().toUpperCase();
        var _horas_secado = $("#horassecado_rack").val();
        var _observacion = $("#observacion_rack").val().trim().toUpperCase();
        var _html = '';

        // Validando datos
          if (_nombre_rack == null){
            alert("Debe ingresar el nombre del Rack.");

            return;
          }
          if (_nombre_rack.length == 0){
            alert("Debe ingresar el nombre del Rack.");

            return;
          }

          if (_estufa_rack == null){
            alert("Debe seleccionar la Estufa.");

            return;
          }
          if (_estufa_rack.length == 0){
            alert("Debe seleccionar la Estufa.");

            return;
          }

          if (_horas_secado == null){
            alert("Debe ingresar las Horas de Secado.");

            return;
          }
          if (_horas_secado.length == 0){
            alert("Debe ingresar las Horas de Secado.");

            return;
          }
          if (_horas_secado < 3){
            alert("La Hora de Secado no puede ser menor a 3.");

            return;
          }
          if (_horas_secado > 8){
            alert("La Hora de Secado no puede ser mayor a 8.");

            return;
          }

        // Grabando datos
          $.post( "apis/backend.php", { accion: "grabar_AnalisisHumedad_Racks", modo: _modo, id_rack: _id_rack, registro_rack: _registro_rack, nombre_rack: _nombre_rack, estufa_rack: _estufa_rack, horas_secado: _horas_secado, observacion: _observacion },
            function( data ) {
              if(data.estado == 1){
                // Registra el nuevo Rack
                  if (_modo == 'N'){
                    _id_rack = data.id_rack;

                    // Obtiene el total de Racks
                      var item_rack = 1;

                      $("#tbl_importaciones tr").each(function () {
                        item_rack += 1;
                      });

                    // Obtiene los registros actuales de Racks
                      _html = $("#tbl_importaciones").html();

                    // Agregando el nuevo Rack
                      _html += '<tr id="tr_item_' + item_rack + '" style="cursor: pointer; font-size: 13px;" onclick="f_LoadItemHumedad(' + item_rack + ', ' + _id_rack + ', 0, 0)">';

                      _html += '  <td id="td_item_1_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '    ' + item_rack;
                      _html += '  </td>';

                      _html += '  <td id="td_item_2_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 14px;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_AdminImportacion(' + "'M', " + item_rack + ', ' + _id_rack + ", '" + _fecha_rack + "', '" + _hora_rack + "', '" + _nombre_rack + "', " + _estufa_rack + ', ' + _horas_secado + ", '" + _observacion + "'" + ');">';
				              _html += '      	<i class="bi bi-pencil-square"></i>';
				              _html += '      </label>';
				              _html += '  </td>';

				              _html += '  <td id="td_item_3_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; cursor: pointer;">';
				              _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarRack(' + _id_rack + ');">X</label>';
				              _html += '  </td>';

                      _html += '  <td id="td_1_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                      _html += '  		<label id="lbl_td_1_' + item_rack + '" style="font-weight: bold;">';
				              _html += '      	' + _nombre_rack;
				              _html += '  		</label><br>';
				              _html += '  		<label>';
				              _html += '  			<i>' + _registro_rack + '</i>';
				              _html += '  		</label><br>';
				              _html += '  		<label>';
				              _html += '  			<i>' + '<?php echo $_SESSION["usu_usuario"]; ?>' + '</i>';
				              _html += '  		</label>';
                      _html += '  </td>';

                      _html += '  <td id="td_2_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '    ' + _estufa_rack_des;
                      _html += '  </td>';

                      _html += '  <td id="td_3_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '      ' + _horas_secado;
                      _html += '  </td>';

                      _html += '  <td id="td_4_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                      _html += '      ' + _observacion;
                      _html += '  </td>';

                      _html += '  <td id="td_5_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; color: #FF5F5D;">';
                      _html += '      Pendiente';
                      _html += '  </td>';

                      _html += '  <td id="td_6_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; color: #FF5F5D;">';
                      _html += '      Pendiente';
                      _html += '  </td>';

                      _html += '  <td id="td_7_' + item_rack + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; color: #FF5F5D;">';
                      _html += '      Pendiente';
                      _html += '  </td>';

                      _html += '</tr>';

                    $("#tbl_importaciones").html(_html);

                    f_ColorSelected(item_rack);

                  	// Actualiza variables
                  		itemimportacion_Selected = item_rack;
                  		idimportacion_Selected = _id_rack;
                  		rack_tieneiniciosecado_selected = 0;
                  		rack_tienefinsecado_selected = 0;
                  		rack_horassecado_selected = _horas_secado;
                  }

                // Actualiza el Rack seleccionado
                  if (_modo == 'M'){
                  	var _html_x = '';

                  	// td_item_2
                  		_html_x = '			<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-top: 3px; padding-bottom: 3px; padding-left: 4px; padding-right: 4px; background-color: #F29F05; color: #ffffff; cursor: pointer;" onclick="f_AdminImportacion(' + "'M', " + item_rack + ', ' + _id_rack + ", '" + _fecha_rack + "', '" + _hora_rack + "', '" + _nombre_rack + "', " + _estufa_rack + ', ' + _horas_secado + ", '" + _observacion + "'" + ');">';
				              _html_x += '      	<i class="bi bi-pencil-square"></i>';
				              _html_x += '			</label>';

				              $("#td_item_2_" + _item_rack).html(_html_x);

			              // td_1
				              _html_x = '  		<label style="font-weight: bold;">';
				              _html_x += '      	' + _nombre_rack;
				              _html_x += '  		</label><br>';
				              _html_x += '  		<label>';
				              _html_x += '  			<i>' + _registro_rack + '</i>';
				              _html_x += '  		</label><br>';
				              _html_x += '  		<label>';
				              _html_x += '  			<i>' + '<?php echo $_SESSION["usu_usuario"]; ?>' + '</i>';
				              _html_x += '  		</label>';

                    	$("#td_1_" + _item_rack).html(_html_x);

                    $("#td_2_" + _item_rack).html(_estufa_rack_des);
                    $("#td_3_" + _item_rack).html(_horas_secado);
                    $("#td_4_" + _item_rack).html(_observacion);
                  }

                f_LoadItemHumedad(itemimportacion_Selected, idimportacion_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
              }
              else{
                alert("Ocurrió un error al momento gusrdar el Rack.");
              }

              f_cerrarModal("modal_adminimportaciones");

            }, "json");
      };

      function f_EliminarRack(_id_rack){
        if(confirm("¿Está seguro de eliminar el Rack seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_AnalisisHumedad_Racks", modo: 'E', id_rack: _id_rack },
            function( data ) {
              if(data.estado == 1){
                f_LoadRacks();
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Modelo.");
              }

            }, "json");
        }
      };

      function f_EliminarMuestra(_item, _id_cabecera){
      	if (_item != 0){
      		var _muestra = $("#td_analisismuestra_3_" + _item).html().trim();

      		if(!confirm("¿Está seguro de eliminar la muestra seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
      			return;
      		}
      	}

        $.post( "apis/backend.php", { accion: "eliminar_AnalisisHumedad_MuestraRack", id_cabecera: _id_cabecera },
          function( data ) {
            if(data.estado == 1){
              f_LoadItemHumedad(itemimportacion_Selected, idimportacion_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
            }
            else{
              alert("Ocurrió un error al momento de eliminar el Modelo.");
            }

          }, "json");
      };

      function f_GuardarPeso(){
      	var _is_buscarmuestra = $("#getpeso_isbuscarmuestra").val();
				var _orden_peso = $("#getpeso_ordenpeso").val();
				var _orden_item = $("#getpeso_ordenitem").val();
				var _item = $("#getpeso_item").val();
				var _id_detalle = $("#getpeso_iddetalle").val();
				var _is_update = $("#getpeso_update").val();
				var _peso = $("#txt_getpeso").val();
				var _cod_interno = $("#addmuestra_barcode").val().trim().substring(0, $("#addmuestra_barcode").val().trim().length - 1);

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
	            	if (_is_update == 1){
	            		f_LoadItemHumedad(itemimportacion_Selected, idimportacion_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);

	            		f_cerrarModal("modal_getpeso");

	            		return;
	            	}

	            	if (_orden_peso == 1){
	            		$("#modal_getpesoLabel").html('Peso Húmedo: ');

					        // Setea objetos
					          $("#txt_getpeso").val('');

				          if (_is_buscarmuestra == 1){
				          	$("#td_buscarmuestra_getpeso_1_" + _orden_item).html(_peso);
				          	$("#td_buscarmuestra_getpeso_2_" + _orden_item).html('<button class="btn btn-info" type="button" onclick="f_GetPeso_Show(' + _is_buscarmuestra + ', 2, ' + _orden_item + ", '" + _item + "'" + ', ' + _id_detalle + ", '" + _cod_interno + "'" + ');" style="width: 100%; color: #ffffff; font-size: 14px;">Pesar</button>');

				          	$("#th_addmuestra_1").val('');
										$("#th_addmuestra_2").val('');

				          	// Agrega el Focus
											document.getElementById("txt_getpeso").focus();
				          }
				          else{
				          	$("#td_analisismuestra_getpeso_1_" + _orden_item).html(_peso);
				          	$("#td_analisismuestra_getpeso_2_" + _orden_item).html('<button class="btn btn-info" type="button" onclick="f_GetPeso_Show(' + _is_buscarmuestra + ', 2, ' + _orden_item + ", '" + _item + "'" + ', ' + _id_detalle + ", '" + _cod_interno + "'" + ');" style="width: 100%; color: #ffffff; font-size: 14px;">Pesar</button>');

				          	f_LoadItemHumedad(itemimportacion_Selected, idimportacion_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
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

						          $("#th_addmuestra_1").val('');
											$("#th_addmuestra_2").val('');
						      }
						      else{
						      	f_LoadItemHumedad(itemimportacion_Selected, idimportacion_Selected, rack_tieneiniciosecado_selected, rack_tienefinsecado_selected, rack_horassecado_selected);
						      }

						      f_cerrarModal("modal_getpeso");

					        f_BreakAutomatico();

					        // Agrega el Focus en el primer buscador
										document.getElementById("th_addmuestra_1").focus();
	            	}

	            	if (_orden_peso == 3){
	            		// Setea objetos
						        $("#td_buscarmuestra_getpeso_3_" + _orden_item).html(_peso);

						      // Actualizar el Click del Rack seleccionado
				        		$("#tr_item_" + itemimportacion_Selected).attr("onclick", 'f_LoadItemHumedad(' + itemimportacion_Selected + ", " + idimportacion_Selected + ", 1, 1, " + rack_horassecado_selected + ');');

						      f_LoadItemHumedad(itemimportacion_Selected, idimportacion_Selected, 1, 1, rack_horassecado_selected);

						      f_cerrarModal("modal_getpeso");

					        f_BreakAutomatico();

					        // Agrega el Focus en el tercer buscador
										document.getElementById("th_buscarmuestra_3").focus();
	            	}
	            }
	            else{
	              alert("Ocurrió un error al momento de eliminar el Modelo.");
	            }

	          }, "json");
      }

      function f_ConfirmarSecado(){
      	var _is_iniciosecado = $("#racksecado_isiniciosecado").val();
      	var _is_finsecado = 0;
      	var inicio_secado = '';
      	var fin_programado = '';
      	var fin_real = '';

      	inicio_secado = $("#secado_fechainicio").val() + ' ' + $("#secado_horainicio").val();
      	fin_programado = $("#secado_fechafin_programado").val() + ' ' + $("#secado_horafin_programado").val();

      	if (_is_iniciosecado == 0){
      		fin_real = $("#secado_fechafin_real").val() + ' ' + $("#secado_horafin_real").val();

      		// Validando fechas
      			if (fin_real <= inicio_secado){
      				alert("La Fecha y Hora Real no puede ser menor o igual al Inicio de Secado.");

      				return;
      			}

      			if (fin_real <= fin_programado){
      				if (!confirm("La Fecha y Hora Real es menor o igual al Fin Programado.\n\n¿Está seguro de continuar?")){
      					return;
      				}
      			}
      	}

        // Grabando datos
        	$.post( "apis/backend.php", { accion: "confirmar_AnalisisHumedad_Secado", is_iniciosecado: _is_iniciosecado, id_rack: idimportacion_Selected, inicio_secado: inicio_secado, fin_programado: fin_programado, fin_real: fin_real },
	          function( data ) {
	            if(data.estado == 1){
	            	// Actualizando los campos de Secado del Rack
	            		if (_is_iniciosecado == 1){
		            		$("#td_5_" + itemimportacion_Selected).html(inicio_secado);
		            		$("#td_6_" + itemimportacion_Selected).html(fin_programado);
		            	}
		            	else{
		            		$("#td_7_" + itemimportacion_Selected).html(fin_real);
		            	}

	            	// Define inicio o fin de secado
	            		if (_is_iniciosecado == 0){
	            			_is_finsecado = 1;
	            		}

	            	// Cargando nuevamente los datos de análisis
	            		f_LoadItemHumedad(itemimportacion_Selected, idimportacion_Selected, _is_iniciosecado, _is_finsecado, rack_horassecado_selected);

	            	// Cambiando el evento click del Rack seleccionado
	            		$("#tr_item_" + itemimportacion_Selected).attr("onclick", 'f_LoadItemHumedad(' + itemimportacion_Selected + ", " + idimportacion_Selected + ", " + _is_iniciosecado + ", " + _is_finsecado + ", " + rack_horassecado_selected + ');');

	            	f_cerrarModal('modal_racksecado');
	            }
	            else{
	              alert("Ocurrió un error al momento de confirmar el Secado.");
	            }

	          }, "json");
      }

      function f_CerrarRack(){
      	if (!confirm("¿Está seguro de cerrar el Rack seleccionado?")){
      		return;
      	}

      	// Guardando datos
      		$.post( "apis/backend.php", { accion: "cerrar_AnalisisHumedad", id_rack: idimportacion_Selected },
          function( data ) {
            if(data.estado == 1){
            	f_SetButtons(0);

            	// Seteando el cierre en el Rack
            		var html = $("#td_1_" + itemimportacion_Selected).html();

            		html += '<br><label style="color: #FF5F5D; font-weight: bold;">CERRADO</label>';

            		$("#td_1_" + itemimportacion_Selected).html(html);

            		$("#td_israckcerrado_" + itemimportacion_Selected).val(1);
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