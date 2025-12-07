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
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

		<!-- JSColor -->
		<script src="libs/jscolor/jscolor.js"></script>

		<title><?php echo $nom_app; ?> | Registro de Procesos</title>

		<script type="text/javascript">
			let itemlote_Selected = 0;
      let codlote_Selected = 0;

      let itemproceso_Selected = 0;
      let codproceso_Selected = 0;
		</script>

		<style>

		</style>
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
						<div class="row" style="padding: 0px;">
							<div id="div_plantas" class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
								<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="d-flex">
												<h5>Lista de Lotes</h5>

												<div id="wt_lotes" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 40px; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
						        				Lote
						        			</th>

						        			<?php

						        			// Obteniendo lista de procesos
						        				$count_procesos = 0;
						        				$arr_procesos = '';

						        				$q_procesos = "SELECT Id,
						        															abv,
						        															descripcion,
						        															depende_de
						        												 FROM tb_procesos
						        												WHERE id_procesosarea = 2
						        													AND estado = 'A'
						        											 ORDER BY orden";

      											if ($res_procesos = mysqli_query($enlace, $q_procesos)){
															if (mysqli_num_rows($res_procesos) > 0) {
																$count_procesos = mysqli_num_rows($res_procesos);

																while($row_procesos = mysqli_fetch_array($res_procesos)){
																	$arr_procesos .= $row_procesos["Id"].';'.$row_procesos["abv"].';'.$row_procesos["descripcion"].'|';
																}
															}
														}

														$arr_procesos = substr($arr_procesos, 0, -1);
														$arr_procesos_x = $arr_procesos;

						        			?>

						        			<th colspan="<?php echo $count_procesos ?>" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Avance
						        			</th>
						        		</tr>

						        		<tr style="font-size: 14px;">
						        			<?php

						        			// Coloca las cabeceras de procesos
						        				$p = 0;
						        				$arr_procesos = explode('|', $arr_procesos);

						        				while ($p < count($arr_procesos)){
						        					?>

						        					<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 50px;">
								        				<?php

								        				echo explode(';', $arr_procesos[$p])[1];

								        				?>
								        			</th>

						        					<?php

						        					$p ++;
						        				}

						        			?>
						        		</tr>
						        	</thead>

						        	<tbody id="tbl_lotes">
						        		
						        	</tbody>
						        </table>
									</div>
								</div>
							</div>

							<div id="div_detalle" class="col-md-3 col-sm-3 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="d-flex">
													<h5>Procesos para: </h5>
													<h5 id="lbl_titulolote" style="margin-left: 5px; color: #337ab7;"></h5>

													<div id="wt_procesos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando...</label>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
										<div id="div_procesos" class="d-flex flex-column mb-3">

										</div>
									</div>
								</div>
							</div>

							<div id="div_detalle" class="col-md-5 col-sm-5 col-xs-12" style="padding: 0px; padding-left: 5px;">
								<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
										<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="d-flex">
													<h5>Registro para Lote: </h5>
													<h5 id="lbl_titulolote2" style="margin-left: 5px; color: #337ab7;"></h5>

													<h5 style="margin-left: 5px;"> | </h5>
													<h5 id="lbl_tituloproceso" style="margin-left: 5px; color: #337ab7;"></h5>

													<div id="wt_procesos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
														<img src="<?php echo $img_waiting ?>" style="width: 20px;">
														<label style="font-style: italic;"> Cargando...</label>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
										<hr style="border-color: #D9D9D9;"/>
									</div>

									<div class="row" style="padding: 20px; margin-top: -15px; width: 100%;">
										<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px; text-align: right;">
											Equipo:
										</div>

										<div class="col-md-8 col-sm-8 col-xs-12">
											<select id="lista_equipos" class="form-select" data-placeholder="Elija una opción...">

											</select>
										</div>

										<div style="padding-left: 20px; padding-right: 20px; margin-top: 5px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>
									</div>

									<div class="row" style="padding: 10px; margin-top: -15px; width: 100%; margin-bottom: 10px;">
										<div style="padding: 0px;">
											<div class="d-flex justify-content-evenly">
												<button id="btn_inicio" class="btn btn-success" type="button" onclick="f_RegistroFechaHora(0);" style="color: #ffffff; font-size: 14px; width: 120px; height: 120px; border-radius: 50%; box-shadow: 0 0 10px #000000; font-size: 18px; display: none;">
						              <b> INICIO</b>
						            </button>

						            <div id="div_InfoInicio" style="padding: 0px; display: none;">
							            <div class="d-flex flex-column mb-3 align-items-center justify-content-center" style="background-color: #84B026; width: 250px; height: 120px; box-shadow: 0 0 10px #747E7E; padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
							            	<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; width: 100%; margin-top: -5px;">
							            		<label style="font-size: 18px; font-weight: bold; background-color: #C4FF6E;">
							            			INICIO
							            		</label>
							            	</div>

							            	<div class="p-2">
							            		<label id="lbl_fechahoraregistro_inicio" style="font-size: 18px; font-weight: bold;">
							            			2023-12-16 16:27
							            		</label>
							            	</div>

							            	<div class="p-2">
							            		<i>
								            		<label id="lbl_usuarioregistro_inicio" style="font-size: 18px; font-weight: bold; margin-top: -20px; color: #ffffff;">
								            			mburga
								            		</label>
								            	</i>
							            	</div>
							            </div>
							          </div>

						            <button id="btn_fin" class="btn btn-danger" type="button" onclick="f_RegistroFechaHora(1);" style="color: #ffffff; font-size: 14px; width: 120px; height: 120px; border-radius: 50%; box-shadow: 0 0 10px #000000; font-size: 18px; display: none;">
						              <b> FIN</b>
						            </button>

						            <div id="div_InfoFin" style="padding: 0px; display: none;">
							            <div class="d-flex flex-column mb-3 align-items-center justify-content-center" style="background-color: #FF5F5D; width: 250px; height: 120px; box-shadow: 0 0 10px #747E7E; padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
							            	<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; width: 100%; margin-top: -5px;">
							            		<label style="font-size: 18px; font-weight: bold; background-color: #FFB8B2;">
							            			FIN
							            		</label>
							            	</div>

							            	<div class="p-2">
							            		<label id="lbl_fechahoraregistro_fin" style="font-size: 18px; font-weight: bold;">
							            			2023-12-16 16:27
							            		</label>
							            	</div>

							            	<div class="p-2">
							            		<i>
								            		<label id="lbl_usuarioregistro_fin" style="font-size: 18px; font-weight: bold; margin-top: -20px; color: #ffffff;">
								            			mburga
								            		</label>
								            	</i>
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
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<!-- ECharts -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

		<!-- JSColor -->
		<script>
			// Here we can adjust defaults for all color pickers on page:
			jscolor.presets.default = {
			    position: 'bottom',
			    palette: [
			        '#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26',
			        '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
			        '#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d',
			        '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
			    ],
			    //paletteCols: 12,
			    hideOnPaletteClick: true,
			};
		</script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| Registro de Procesos');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadLotes();
			}
		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			$('#filtro_proveedorminero, #filtro_modalidadenvio').select2({
		    theme: "bootstrap-5",
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		    placeholder: $( this ).data( 'placeholder' ),
		    allowClear: true,
		    dropdownParent: $('#modal_adminprogramaciones')
			});

			$('#tipo_unidad, #distribucion_unidad, #distribucion_unidad2').select2({
		    theme: "bootstrap-5",
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		    placeholder: $( this ).data( 'placeholder' ),
		    allowClear: true,
		    dropdownParent: $('#modal_admindistribuciones')
			});

			$('#filtro_proveedorminero, #filtro_modalidadenvio, #tipo_unidad, #distribucion_unidad, #distribucion_unidad2').next('.select2-container').find('.select2-selection__rendered').css('font-size', '14px');
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadLotes(){
        var _html = '';
        var d = 1;

        // Obteniendo filtros

        // Validando datos

				// Cargando Lista de Racks
	        $("#tbl_lotes").html('');

	        f_LoadingLotes(1);

	        $.post( "apis/backend.php", { accion: "get_ProcesosPlanta_ListaLotes", arr_procesos: '<?php echo $arr_procesos_x; ?>' }, 
	          function( data ) {
	            if(data.estado == 1){
	            	$("#tbl_lotes").html(data.html);

	            	itemlote_Selected = 1;
								codlote_Selected = data.cod_lote;

								f_LoadItemLote(itemlote_Selected, codlote_Selected);
	            }

	            f_LoadingLotes(0);

	          }, "json");
    	}

      function f_LoadItemLote(_item, _cod_lote){
        var _html = '';

        // Pinta selección
          f_ColorSelected_Lote(_item);

        // Seteando título
        	$("#lbl_titulolote").html(_cod_lote);

        // Cargando datos
          f_LoadingProcesos(1);

          $("#tbl_programacion").html(_html);
          $("#tbl_distribucion").html(_html);

          $.post( "apis/backend.php", { accion: "get_ProcesosPlanta_ListaProcesosxLote", cod_lote: _cod_lote, arr_procesos: '<?php echo $arr_procesos_x; ?>' }, 
            function( data ) {
              if(data.estado == 1){
                $("#div_procesos").html(data.html);
              }

              // Llama al panel de Registro de Procesos por Lote
              	f_RegistroProceso(data.id_procesoactivo, data.des_procesoactivo, data.fechahora_inicio_procesoactivo, data.fechahora_fin_procesoactivo, data.usuario_inicio_procesoactivo, data.usuario_fin_procesoactivo, data.id_equipo_procesoactivo);

              f_LoadingProcesos(0);

            }, "json");

        itemlote_Selected = _item;
        codlote_Selected = _cod_lote;
      }

      function f_ColorSelected_Lote(_item){
        var i = 1;

        // Recorre los Tr de la tabla y los limpia
        $("#tbl_lotes tr").each(function () {
          $("#tr_lote_" + i).css('background-color', '');

          i += 1;
        });

        // Seteando item seleccionado
          $("#tr_lote_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulolote").html($("#td_codlote_" + _item).html().trim());
      }

      function f_RegistroProceso(_id_proceso, _des_proceso, _fechahora_inicio, _fechahora_fin, _usuario_inicio, _usuario_fin, _id_equipo){
      	// Seteando Título
      		$("#lbl_titulolote2").html($("#td_codlote_" + itemlote_Selected).html().trim());
      		$("#lbl_tituloproceso").html(_des_proceso);

      	// Carga lista de equipos
      		f_LoadListaEquipos(_id_proceso, _id_equipo);

      	// Seteando objetos
      		$("#lista_equipos").prop('disabled', false);

      		$("#btn_inicio").hide();
					$("#btn_fin").hide();

					$("#div_InfoInicio").hide();
					$("#div_InfoFin").hide();

      		if (_fechahora_inicio == undefined || _fechahora_inicio.length == 0){
      			$("#btn_inicio").show();
      		}
      		else{
      			$("#lista_equipos").prop('disabled', true);

      			if (_fechahora_fin == undefined || _fechahora_fin.length == 0){
	      			$("#div_InfoInicio").show();
	      			$("#btn_fin").show();

	      			// Seteando objetos de registro
	      				$("#lbl_fechahoraregistro_inicio").html(_fechahora_inicio);
	      				$("#lbl_usuarioregistro_inicio").html(_usuario_inicio);
	      		}
	      		else{
	      			$("#div_InfoInicio").show();
	      			$("#div_InfoFin").show();

	      			// Seteando objetos de registro
	      				$("#lbl_fechahoraregistro_inicio").html(_fechahora_inicio);
	      				$("#lbl_usuarioregistro_inicio").html(_usuario_inicio);

	      				$("#lbl_fechahoraregistro_fin").html(_fechahora_fin);
	      				$("#lbl_usuarioregistro_fin").html(_usuario_fin);
	      		}
      		}

				codproceso_Selected = _id_proceso;
      }

      function f_LoadListaEquipos(_id_proceso, _id_equipo){
      	var _html = '';

      	$.post( "apis/backend.php", { accion: "get_ProcesosPlanta_ListaEquiposxProceso", id_proceso: _id_proceso }, 
          function( data ) {
            if(data.estado == 1){
            	$.each( data.res, function( key, val ) {
                _html += '<option value="' + val.Id + '" ' + ((_id_equipo > 0) ? ((_id_equipo == val.Id) ? 'selected' : '') : '') + '>' + val.descripcion.toUpperCase() + '</option>';
              });

              $("#lista_equipos").html(_html);
            }

          }, "json");
      }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingLotes(_is_show){
				if (_is_show == 1){
					$("#wt_lotes").show();
				}
				else{
					$("#wt_lotes").hide();
				}
			}

			function f_LoadingProcesos(_is_show){
				if (_is_show == 1){
					$("#wt_procesos").show();
				}
				else{
					$("#wt_procesos").hide();
				}
			}

			// function f_LoadingLotes(_is_show){
			// 	if (_is_show == 1){
			// 		$("#wt_loadinglotes").show();
			// 	}
			// 	else{
			// 		$("#wt_loadinglotes").hide();
			// 	}
			// }

			// function f_LoadingLotes_Distribucion(_is_show){
			// 	if (_is_show == 1){
			// 		$("#wt_loadinglotesdistribuciones").show();
			// 	}
			// 	else{
			// 		$("#wt_loadinglotesdistribuciones").hide();
			// 	}
			// }

			// function f_LoadingDistribucion(_is_show){
			// 	if (_is_show == 1){
			// 		$("#wt_distribucion").show();
			// 	}
			// 	else{
			// 		$("#wt_distribucion").hide();
			// 	}
			// }

      // function f_HideListaProgramaciones(_x){
      //   if (_x == 1){
      //     $("#div_plantas").hide();
      //     $("#div_detalle").width('100%');

      //     f_CerrarDiv('C', 'div_ShowListaProgramaciones');
      //     f_CerrarDiv('A', 'div_HideListaProgramaciones');
      //     }
      //   else{
      //     $("#div_plantas").show();
      //     $("#div_detalle").width('');

      //     f_CerrarDiv('A', 'div_ShowListaProgramaciones');
      //     f_CerrarDiv('C', 'div_HideListaProgramaciones');
      //   }
      // }

	    // function f_SelectChkLotes(){
	    // 	var is_checked = false;

	    // 	// Obteniendo valor del checkbox
		  //   	if ($("#th_Chk").prop('checked')){
		  //   		is_checked = true;
		  //   	}

		  //   // Recorre solo las filas visibles
		  //   	var d = 1;

		  //   	$("#tbl_FiltroLotes tr").filter(function() {
		  //   		$("#chk_lote_" + d).prop('checked', is_checked);

		  //   		d ++;
		  //   	});

		  //   // Cuenta los seleccionados
		  //   	f_CountSelected();
	    // }

	    // function f_CountSelected(){
	    // 	var d = 1;
	    // 	var _count = 0;
	    // 	var _total_tmh = 0;
	    // 	var _total_tms = 0;

	    // 	$("#tbl_FiltroLotes tr").filter(function() {
	    // 		if ($("#chk_lote_" + d).prop('checked')){
	    // 			_total_tmh += parseFloat($(this).find("td:eq(5)").text());
	    // 			_total_tms += ((isNaN($(this).find("td:eq(6)").text().trim())) ? 0 : parseFloat($(this).find("td:eq(6)").text()));

	    // 			_count ++;
	    // 		}

	    // 		d ++;
	    // 	});

	    // 	// Setea el conteo de seleccionados
	    // 		$("#lbl_countlotes").html(_count);

	    // 	// Setea el total de Netos
	    // 		$("#lbl_totaltmh").html(f_RedondearDecimales(_total_tmh, 3));
	    // 		$("#lbl_totaltms").html(f_RedondearDecimales(_total_tms, 3));
	    // }

	    // function f_SelectChkLotes_Distribucion(){
	    // 	var is_checked = false;

	    // 	// Obteniendo valor del checkbox
		  //   	if ($("#th_Chk_Distribucion").prop('checked')){
		  //   		is_checked = true;
		  //   	}

		  //   // Recorre solo las filas visibles
		  //   	var d = 1;

		  //   	$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
		  //   		$("#chk_lotedistribucion_" + d).prop('checked', is_checked);

		  //   		d ++;
		  //   	});

		  //   // Cuenta los seleccionados
		  //   	f_CountSelected_Distribucion();
	    // }

	    // function f_CountSelected_Distribucion(){
	    // 	var d = 1;
	    // 	var _count = 0;
	    // 	var _total_distribuido = 0;

	    // 	$("#tbl_FiltroLotes_Distribucion tr").filter(function() {
	    // 		if ($("#chk_lotedistribucion_" + d).prop('checked')){
	    // 			_total_distribuido += (($("#tmh_distribuido_" + d).val().length == 0) ? 0 : parseFloat($("#tmh_distribuido_" + d).val()));

	    // 			_count ++;
	    // 		}

	    // 		d ++;
	    // 	});

	    // 	// Setea el conteo de seleccionados
	    // 		$("#lbl_countlotes_Distribucion").html(_count);

	    // 	// Setea el total de Netos
	    // 		$("#lbl_totaltmh_Distribuido").html(f_RedondearDecimales(_total_distribuido, 3));
	    // }
	    
	    // function f_LoadingGrabarProgramacion(_is_show){
			// 	if (_is_show == 1){
			// 		$("#wt_grabarprogramacion").show();

			// 		$(".wt_grabarprogramacion_button").prop('disabled', true);
			// 	}
			// 	else{
			// 		$("#wt_grabarprogramacion").hide();

			// 		$(".wt_grabarprogramacion_button").prop('disabled', false);
			// 	}
			// }
	    
	    // function f_LoadingGrabarProgramacion_Distribucion(_is_show){
			// 	if (_is_show == 1){
			// 		$("#wt_grabardistribucion").show();

			// 		$(".wt_grabardistribucion_button").prop('disabled', true);
			// 	}
			// 	else{
			// 		$("#wt_grabardistribucion").hide();

			// 		$(".wt_grabardistribucion_button").prop('disabled', false);
			// 	}
			// }

			// function f_SetCapacidad(){
			// 	var tipo_vehiculo = $("#tipo_unidad").val().split('|')[0];
			// 	var tiene_carreta = (($("#tipo_unidad").val().length == 0) ? 0 : $("#tipo_unidad").val().split('|')[1]);
			// 	var capacidad_unidad = '';

			// 	// Oculta Placa 2
			// 		$("#div_placa2").hide();

			// 	// Determinando si el tipo de unidad tiene carreta
			// 		if (tiene_carreta == 1){
			// 			$("#div_placa2").show();
			// 		}

			// 	// Obteniendo la Capacidad
			// 		if (tiene_carreta == 0){
			// 			if ($("#distribucion_unidad").val() != null){
			// 				if ($("#distribucion_unidad").val().length > 0){
			// 					var capacidad_unidad = $("#distribucion_unidad").val().split('|')[1];

			// 					if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0){
			// 						capacidad_unidad = 'Sin Asignar...';
			// 					}
			// 					else{
			// 						capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
			// 					}
			// 				}
			// 			}
			// 		}
			// 		else{
			// 			if ($("#distribucion_unidad2").val() != null){
			// 				if ($("#distribucion_unidad2").val().length > 0){
			// 					var capacidad_unidad = $("#distribucion_unidad2").val().split('|')[1];

			// 					if (capacidad_unidad.trim().length == 0 || capacidad_unidad == 0){
			// 						capacidad_unidad = 'Sin Asignar...';
			// 					}
			// 					else{
			// 						capacidad_unidad = f_RedondearDecimales((capacidad_unidad / 1000), 3);
			// 					}
			// 				}
			// 			}
			// 		}

			// 	$("#unidad_capacidad").val(capacidad_unidad);
			// }
	    
	    // function f_LoadingGrabar_CierrePrograma(_is_show){
			// 	if (_is_show == 1){
			// 		$("#wt_grabarcierre").show();

			// 		$(".wt_grabarcierre_button").prop('disabled', true);
			// 	}
			// 	else{
			// 		$("#wt_grabarcierre").hide();

			// 		$(".wt_grabarcierre_button").prop('disabled', false);
			// 	}
			// }
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_RegistroFechaHora(_is_fin){
				// Obteniendo datos
					var cod_equipo = $("#lista_equipos").val();

				// Validando datos
					if (_is_fin == 0){
						if (!confirm("¿Está seguro de Iniciar el Proceso")){
							return
						}

						$("#btn_inicio").hide();
					}
					else{
						if (!confirm("¿Está seguro de Finalizar el Proceso")){
							return
						}

						$("#btn_fin").hide();
					}

					if (cod_equipo == null){
            alert("Debe seleccionar el Equipo.");

            return;
          }
          if (cod_equipo.length == 0){
            alert("Debe seleccionar el Equipo.");

            return;
          }

        // Grabando Datos
					var cod_equipo = $("#lista_equipos").val();

          $.post( "apis/backend.php", { accion: "grabar_ProcesosPlanta_Registro", id_proceso: codproceso_Selected, cod_lote: codlote_Selected, cod_equipo: cod_equipo, is_fin: _is_fin },
            function( data ) {
              if(data.estado == 1){
              	// Setea el Color del proceso
              		if (_is_fin == 1){
              			$("#div_Proc_" + itemlote_Selected + "_" + codproceso_Selected).css('background-color', data.color);
              		}

              	// Recarga los procesos
              		f_LoadItemLote(itemlote_Selected, codlote_Selected);
              }
              else{
                alert("Ocurrió un error al momento de grabar los datos.");
              }

            }, "json");

				// Seteando los objetos
					if (_is_fin == 0){

					}
					else{

					}
			}
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 400){
					
				}
			}
		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>