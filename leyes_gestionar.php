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


		<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Gestión de Leyes</title>

		<style>
			
		</style>

	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<!-- Modal (Menú Lateral) -->
				<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
					<div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important; ">
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


				<!-- Modal (Filtros Lateral) -->
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
												<h6 style="font-size: 14px;">Creación Lote</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="row" >
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
												</div>
												<br><br>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Estado:</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_estado" class="form-select obj_cab" style="text-align: left; font-size: 14px;">
													<option value="99">Todos...</option>
													<option value="1">Cerrados</option>
													<option value="0" selected>Pendientes</option>
												</select>
											</div>
										</div>
									</div>

									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Lotes:</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												
												<select id="filtro_lote" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
													<?php

													$q_lotes = "SELECT ccod_Lote
																				FROM catalogolotes
																			ORDER BY ccod_Lote DESC";

													if ($res_lotes = mysqli_query($enlace, $q_lotes)){
														if (mysqli_num_rows($res_lotes) > 0) {
															while($row_lotes = mysqli_fetch_array($res_lotes)){
																?>

																<option value="<?php echo $row_lotes["ccod_Lote"]; ?>"><?php echo $row_lotes["ccod_Lote"]; ?></option>

																<?php
															}
														}
													}

													?>
												</select>

											</div>
										</div>
									</div>

									<div class="row" style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
												<i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
											</button>
										</div>
										<br><br>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
												<b>Exportar a Excel</b>
											</button>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row " style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
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
									<h5>Gestión de Leyes</h5>
									<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
										<img src="<?php echo $img_waiting ?>" style="width: 20px;">
										<label style="font-style: italic;"> Cargando datos...</label>
									</div>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-primary" type="button" onclick="f_AdminRegistroLote();" style="color: #ffffff; width: 100%; font-size: 14px;">
										<b> + Nuevo registro</b>
									</button>
								</div>

							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
								<table class="table table-bordered table-striped table-hover" id="tabla_resumen">
									<thead>
										<tr style="font-size: 12px;" id="tbl_cabecera_leyes_grupos"></tr>
										<tr style="font-size: 12px;" id="tbl_cabecera_leyes_analitos"></tr>
										<tr style="font-size: 12px;" id="tbl_cabecera_leyes_ley_promedio"></tr>

										<input id="hd_arr_id_grupo_analito" type="hidden">
										<input id="hd_arr_id_grupo_analitoabv" type="hidden">
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
		<div class="modal fade" id="modal_verleyeslog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5">Historial</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row" style="padding: 5px;">
							<table class="table table-bordered table-hover">
								<thead>
									<tr style="font-size: 14px;">
										<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
											Valor
										</th>
										<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
											Fecha Registro
										</th>
										<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
											Usuario
										</th>
									</tr>
								</thead>
								<tbody id="tbl_verleyeslog"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		
		<!-- Ventanas modales Registro de lotes-->
		<div class="modal fade" id="modal_addregistrolote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addconductorLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="modal_addregistroloteLabel">Nuevo Registro</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: right;">
								Lote:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<select id="lista_lotes" class="form-select" data-placeholder="Elija una opción...">
									
								</select>
							</div>
						</div>

					</div>

					<div class="modal-footer">
						<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

						<button type="button" class="btn btn-secondary wt_resumen_button" data-bs-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary wt_resumen_button" onclick="f_GrabarRegistroLote();">Grabar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modal_ConfirmarValorizacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_ConfirmarValorizacionLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" id="modalheader_ConfirmarValorizacionLabel" style="color: #ffffff;">
						<h1 class="modal-title fs-6" id="modal_ConfirmarValorizacionLabel"></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row" style="padding: 5px;">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<textarea id="confirmarvalorizacion_comentario" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
							</div>
						</div>
					</div>

					<input id="hd_confirmarvalorizacionLote" type="hidden">
					<input id="hd_confirmarvalorizacionIsValorizar" type="hidden">

					<div class="modal-footer">
						<div id="wt_ConfirmarValorizacion" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

						<button type="button" class="btn btn-secondary wt_ConfirmarValorizacion_Button" data-bs-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary wt_ConfirmarValorizacion_Button" onclick="f_ConfirmarValorizacion();">Confirmar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Select2 -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<!-- ECharts -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
				$('#filtro_lote').select2({
					theme: "bootstrap-5",
					width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : '100%',
					placeholder: $( this ).data( 'placeholder' ),
					allowClear: true,
					minimumResultsForSearch: -1
				});
		</script>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| Gestión de Leyes');

				// Cargando listas generales
					f_LoadListaLotes();

				// Carga el detalle de información
					f_LoadCabeceraLeyesGrupo();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadListaLotes(){
				$("#lista_lotes").html('');

				$.post( "apis/backend.php", { accion: "get_GestionLeyes_ListaLotes" }, 
					function( data ) {
						if(data.estado == 1){
							$("#lista_lotes").html(data.html);
						}

					}, "json");
			}

			function f_LoadCabeceraLeyesGrupo(){
				$('#btn_exportar_excel').attr('disabled', false);

				var _html = '';

				var id_origendato = $("#hd_id_origendato").val();
				var filtro_tipocliente = "";

				$("#tbl_cabecera_leyes_grupos").html('');
				$("#tbl_cabecera_leyes_analitos").html('');
				$("#tbl_cabecera_leyes_ley_promedio").html('');

				$.post( "apis/backend.php", { accion: "get_ListaCabeceraLeyesGrupos" }, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_cabecera_leyes_grupos").html(data.html);
							f_LoadCabeceraLeyesAnalitos(data.arr_id_leyes_grupo);
						}
				}, "json");
			};

			function f_LoadCabeceraLeyesAnalitos(arr_id_leyes_grupo){
				var _html = '';

				$("#tbl_cabecera_leyes_analitos").html('');
				$("#tbl_cabecera_leyes_ley_promedio").html('');

				$.post( "apis/backend.php", { accion: "get_ListaCabeceraLeyesAnalitos", arr_id_leyes_grupo: arr_id_leyes_grupo}, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_cabecera_leyes_analitos").html(data.html);
							$("#hd_arr_id_grupo_analito").val(data.arr_grupo_analito);
							$("#hd_arr_id_grupo_analitoabv").val(data.arr_grupo_analitoabv);
							f_LoadCabeceraLeyesLeyPromedio(data.arr_id_analito);
						}
					}, "json");
			};

			function f_LoadCabeceraLeyesLeyPromedio(arr_id_analito){
				var _html = '';

				$("#tbl_cabecera_leyes_ley_promedio").html('');

				$.post( "apis/backend.php", { accion: "get_ListaCabeceraLeyesLeyPromedio", arr_id_analito: arr_id_analito}, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_cabecera_leyes_ley_promedio").html(data.html);
							f_LoadResultados();
						}
					}, "json");
			};

			function f_LoadResultados(){
				var _html = '';
				var d = 1;
				var is_show = 0;

				var fecha_inicio = $("#fecha_inicio").val();
				var fecha_fin = $("#fecha_fin").val();
				var estado = $("#filtro_estado").val();
				var filtro_lote = $("#filtro_lote").val();

				f_LoadingResumen(1);

				$("#tbl_detalle").html('');

				$.post( "apis/backend.php", { accion: "get_ListaDetalleLeyesLotes", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, estado: estado, filtro_lote: filtro_lote }, 
					function( data ) {
						if(data.estado == 1){

							var arr_grupo_analito = $("#hd_arr_id_grupo_analitoabv").val();
							var arr_grupo_analito = arr_grupo_analito.split("$");

							$.each( data.res, function( key, val ) {
								_html += '<tr style="cursor: pointer; font-size: 14px;" id="tr_ley_'+val.ccod_Lote+'">';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
								_html += '      ' + val.ccod_Lote;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
								_html += '      ' + val.dFechaCreacion + '<br>' + val.id_UsuarioCreacion;
								_html += '  </td>';

								var arr_grupo_analito_param = '';
								var grupos_tipo_insertados = {}; // Set de grupos ya insertados

								if(arr_grupo_analito.length > 0){
									for (var i = 0; i < arr_grupo_analito.length; i += 1) {
										var grupo_analito = arr_grupo_analito[i].split('|');

										var id_grupo = grupo_analito[0];
										var id_grupo_tiene_tipo = grupo_analito[1];
										var id_analito = grupo_analito[2];

										arr_grupo_analito_param += id_analito + "$";

										// Solo insertar el td_ley_tipo si no se ha insertado antes para este grupo
										if(id_grupo_tiene_tipo == 1 && !grupos_tipo_insertados[id_grupo]){
											_html += '  <td  style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" id="td_ley_tipo_'+val.ccod_Lote+'_'+id_grupo+'_'+id_analito+'">';
											_html += '  </td>';
											grupos_tipo_insertados[id_grupo] = true;
										}

										_html += '  <td  style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" id="td_ley_valor_'+val.ccod_Lote+'_'+id_grupo+'_'+id_analito+'">';
										_html += '  </td>';

										// Ocultando las columnas de promedio de Humedad y Recuperación
											is_show = 1;

											if (id_grupo == 4 || id_grupo == 5){
												is_show = 0;
											}

										_html += '  <td  style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;" id="td_ley_promedio_'+val.ccod_Lote+'_'+id_grupo+'_'+id_analito+'" ' + ((is_show == 0) ? 'hidden' : '') + '>';
										_html += '  </td>';
									}
								}

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center" id="td_loteestado_'+val.ccod_Lote+'">';
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center" id="td_ley_cierre_'+val.ccod_Lote+'">';
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center" >';
								_html += '   <label id="fecharegistro_ley_cierre_'+val.ccod_Lote+'"/>'
								_html += '  </td>';
								
								// _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center" >';
								// _html += '   <label id="usuarioregistro_ley_cierre_'+val.ccod_Lote+'"/>'
								// _html += '  </td>';

								f_LoadGestionLeyes(val.ccod_Lote,arr_grupo_analito);
								f_MostrarLeyesCierre(val.ccod_Lote);

								_html += '</tr>';

								d += 1;
							});

						}

						$("#tbl_detalle").html(_html);

						f_LoadingResumen(0);

					}, "json");
			};

			function f_LoadGestionLeyes(cod_lote,array_id_grupo_analitoabv){
				$.post("apis/backend.php", { 
						accion: "get_ListaDetalleLeyesLotes_Analisis", 
						cod_lote: cod_lote, 
						array_id_grupo_analitoabv: array_id_grupo_analitoabv
					}, 
					function(data) {
						if(data.estado == 1){

							// 🔑 Contadores por cada grupo/elemento para controlar múltiples filas
							var contadorInputs = {};

							$.each(data.res, function(key, val) {
								var d = val.Id;
								var keyGrupoElemento = cod_lote + "_" + val.id_grupo + "_" + val.id_elemento;

								if (!contadorInputs[keyGrupoElemento]) {
									contadorInputs[keyGrupoElemento] = 0;
								}
								contadorInputs[keyGrupoElemento]++;

								// Celda donde irá el valor
								var td = $('#td_ley_valor_'+cod_lote+'_'+val.id_grupo+'_'+val.id_elemento);
								if (td.find("table").length === 0) {
									td.append('<table style="width: 100%;"></table>');
								}

								// Celda del promedio
								var td_prom = $('#td_ley_promedio_'+cod_lote+'_'+val.id_grupo+'_'+val.id_elemento);

								// ✅ Caso especial: %HUMEDAD (h2o) y RECUPERACIÓN (recup)
								if (val.id_elemento === 'h2o' || val.id_elemento === 'recup') {
									// Solo habilitamos el primer input
									var disabledAttr = (contadorInputs[keyGrupoElemento] > 1) ? "disabled" : "";
									var is_show = (contadorInputs[keyGrupoElemento] > 1) ? "0" : "1";

									// Checkbox oculto solo en la primera fila
									var hiddenCheckbox = "";

									if (contadorInputs[keyGrupoElemento] === 1) {
										hiddenCheckbox = `
											<input type="checkbox" 
														id="chk_ley_valor_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}" 
														name="chk_ley_valor_${cod_lote}_${val.id_grupo}" 
														value="${val.valor}" 
														checked 
														style="display:none;" />`;
									}

									td.find("table").append(`
										<tr>
											<td>
												<div style="display: ${is_show == 1 ? 'flex' : 'none'}; align-items: center; gap: 8px;">
													${hiddenCheckbox}
													<input 
														type="text" 
														class="form-control" 
														style="text-align: center; width: 75px !important" 
														value="${val.valor}" 
														${disabledAttr}
														id="input_ley_valor_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}" 
														name="input_ley_valor_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}"
														onfocus="this.setAttribute('data-prev-value', this.value)" 
														onblur="if (this.value !== this.getAttribute('data-prev-value')) { 
																f_AnalisisLeyesValor({checked:true},'${d}','${cod_lote}','${val.id_grupo}','${val.id_elemento}');
																f_AnalisisLeyesValorLog(this,'${d}','${cod_lote}','${val.id_grupo}','${val.id_elemento}'); 
														}"
													/>
													<i class="bi bi-list-task" 
														id="btn_ley_valor_historial_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}" 
														onclick="f_AdminAnalisisLeyesValorLog('${d}','${cod_lote}','${val.id_grupo}','${val.id_elemento}')" 
														style="display: none"></i>
												</div>
											</td>
										</tr>`);

									f_CalcularPromedioLeyes(cod_lote, val.id_grupo, val.id_elemento);
								} 
								else {
									// ✅ Comportamiento normal para las demás columnas
									var td_tipo = $('#td_ley_tipo_'+cod_lote+'_'+val.id_grupo+'_'+val.id_elemento);
									if (td_tipo.find("table").length === 0) {
										td_tipo.append('<table style="width: 100%;"></table>');
									}

									td.find("table").append(`
										<tr><td>
											<div style="display: flex; align-items: center; gap: 8px;">
												<input type="checkbox" 
													id="chk_ley_valor_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}" 
													name="chk_ley_valor_${cod_lote}_${val.id_grupo}" 
													value="${val.valor}"  
													onchange="f_AnalisisLeyesValor(this,'${d}','${cod_lote}','${val.id_grupo}','${val.id_elemento}')" />
												<input 
													type="text" 
													class="form-control" 
													style="text-align: center; width: 75px !important" 
													value="${val.valor}" 
													id="input_ley_valor_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}" 
													name="input_ley_valor_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}"
													onfocus="this.setAttribute('data-prev-value', this.value)" 
													onblur="if (this.value !== this.getAttribute('data-prev-value')) { 
															f_AnalisisLeyesValorLog(this,'${d}','${cod_lote}','${val.id_grupo}','${val.id_elemento}'); 
													}"
												/>
												<i class="bi bi-list-task" 
													id="btn_ley_valor_historial_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}" 
													onclick="f_AdminAnalisisLeyesValorLog('${d}','${cod_lote}','${val.id_grupo}','${val.id_elemento}')" 
													style="display: none"></i>
											</div>
										</td></tr>`);

									td_tipo.find("table").append(`
										<tr><td>
											<select 
												class="form-select ley-select"
												style="width: 75px !important"
												id="input_ley_valor_tipo_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}" 
												name="input_ley_valor_tipo_${d}_${cod_lote}_${val.id_grupo}_${val.id_elemento}"
												onchange="f_AnalisisLeyesValorTipoLog(this, '${d}', '${cod_lote}', '${val.id_grupo}', '${val.id_elemento}');">
												<option value="R">(R)</option>
												<option value="P">(P)</option>
											</select>
										</td></tr>`);

									// Promedio normal
									f_CalcularPromedioLeyes(cod_lote,val.id_grupo,val.id_elemento);
								}

								// Acciones (mostrar historial, checkbox seleccionado, etc.)
								f_LoadGestionLeyesAcciones(d,cod_lote,val.id_grupo,val.id_elemento);
							});
						}
						
						f_MostrarLeyesCierre(cod_lote);

						f_LoadingResumen(0);
					}, "json");
			}


			function f_AnalisisLeyesValor(checkbox, posicion, cod_lote, id_grupo, abv_elemento) {
				var valor_input = $('#input_ley_valor_' + posicion + '_' + cod_lote + '_' + id_grupo + '_' + abv_elemento).val();

				if (!valor_input || valor_input.trim() === '') {
					alert('No se puede seleccionar un valor vacío.');
					if (checkbox && checkbox.checked !== undefined) {
						$(checkbox).prop('checked', false);
					}
					return;
				}

				var valor = valor_input;

				//Aquí la lógica: si es h2o o recup → siempre enviar is_select = 1
				var is_select = (abv_elemento === 'h2o' || abv_elemento === 'recup')
					? 1
					: (checkbox && checkbox.checked ? 1 : 0);

				$.post("apis/backend.php", {
						accion: "grabar_analisisleyes_valor",
						posicion: posicion,
						cod_lote: cod_lote,
						id_grupo: id_grupo,
						abv_elemento: abv_elemento,
						valor: valor,
						is_select: is_select
					},
					function(data) {
						if (data.estado == 1) {
							f_CalcularPromedioLeyes(cod_lote, id_grupo, abv_elemento);
							verificarCheckboxes(cod_lote, id_grupo);
						}
					},
					"json"
				);
			}



			function f_AnalisisLeyesValorLog(input,posicion,cod_lote,id_grupo,abv_elemento) {
					
				var valor = input.value;

				$.post( "apis/backend.php", { accion: "grabar_analisisleyes_valor_log", posicion:posicion, cod_lote: cod_lote, id_grupo: id_grupo, abv_elemento: abv_elemento,  valor: valor},
				function( data ) {
					if(data.estado == 1){

					//Activar el botón de historial de cambios
						$('#btn_ley_valor_historial_'+posicion+'_'+cod_lote+'_'+id_grupo+'_'+abv_elemento).show();
						f_CalcularPromedioLeyes(cod_lote,id_grupo,abv_elemento);
						
					}
				}, "json");
			 
			}

			function f_AnalisisLeyesValorTipoLog(input,posicion,cod_lote,id_grupo,abv_elemento) {
				var valor = input.value;

				$.post( "apis/backend.php", { accion: "grabar_analisisleyes_valor_tipo_log", posicion:posicion, cod_lote: cod_lote, id_grupo: id_grupo, abv_elemento: abv_elemento,  valor: valor},
				function( data ) {

					if(data.estado != 1){
						alert("Ocurrió un error al momento de grabar los datos.");
					}
				}, "json");
			 
			}

			function f_AdminAnalisisLeyesValorLog(posicion,cod_lote,id_grupo,abv_elemento){

				$("#tbl_verleyeslog").html('');
				var _html = '';

				// Abriendo modal
					f_OpenModal('modal_verleyeslog');

				$.post( "apis/backend.php", { accion: "listar_analisisleyes_valor_log", posicion:posicion, cod_lote: cod_lote, id_grupo: id_grupo, abv_elemento: abv_elemento}, 
					function( data ) {
						if(data.estado == 1){

							$.each( data.res, function( key, val ) {
								
								_html += '<tr style="cursor: pointer; font-size: 14px;">';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center">';
								_html += '      ' + val.valor;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center">';
								_html += '      ' + val.fechahora_registro;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center">';
								_html += '      ' + val.usuario_registro;
								_html += '  </td>';


								_html += '</tr>';

							});

						}

					$("#tbl_verleyeslog").html(_html);

				}, "json");
			}

			function f_LoadGestionLeyesAcciones(posicion,cod_lote,id_grupo,abv_elemento) {
				$.post( "apis/backend.php", { accion: "listar_analisisleyes_acciones", posicion:posicion, cod_lote: cod_lote, id_grupo: id_grupo, abv_elemento: abv_elemento},
					function( data ) {
						if(data.estado == 1){
							var val_cod_lote = "";
							var val_id_grupo = "";

							$.each( data.res, function( key, val ) {
								if(val.total_log > 0){
									$('#btn_ley_valor_historial_'+posicion+'_'+cod_lote+'_'+id_grupo+'_'+abv_elemento).show();
								}

								if(val.is_select > 0){
									$('#chk_ley_valor_'+posicion+'_'+cod_lote+'_'+id_grupo+'_'+abv_elemento).prop('checked', true);
									val_cod_lote = cod_lote;
									val_id_grupo = id_grupo;
								}

								if(val.valor >= 0){
									$('#input_ley_valor_'+posicion+'_'+cod_lote+'_'+id_grupo+'_'+abv_elemento).val(val.valor);
								}

									$('#input_ley_valor_tipo_' + posicion+'_' + cod_lote+'_' + id_grupo+'_'+abv_elemento).val(val.valor_tipo);


							});

							verificarCheckboxes(val_cod_lote,val_id_grupo);
						}

					}, "json");
			}
			
			function f_CalcularPromedioLeyes(cod_lote,id_grupo,abv_elemento) {
				$.post("apis/backend.php", { 
						accion: "calcular_analisisleyes_valor_promedio", 
						cod_lote: cod_lote, 
						id_grupo: id_grupo, 
						abv_elemento: abv_elemento
					},
					function(datap) {
						var promedio = "0.00"; // valor por defecto

						if (datap.estado == 1 && datap.res.length > 0) {
							promedio = parseFloat(datap.res[0].promedio).toFixed(2);
						}

						var $celda = $('#td_ley_promedio_'+cod_lote+'_'+id_grupo+'_'+abv_elemento);

						// limpiar contenido previo
						$celda.empty();

						// generar input hidden SIEMPRE
						var hiddenInput = '<input hidden name="input_ley_promedio_'+cod_lote+'_'+id_grupo+'" ' +
															'data-abvelemento="'+abv_elemento+'" value="'+promedio+'">';

						if (abv_elemento === 'h2o' || abv_elemento === 'recup') {
							// Mostrar el promedio pero oculto visualmente + input hidden
							$celda.append('<span style="visibility:hidden">'+promedio+'</span>');
							$celda.append(hiddenInput);
						} else {
							// Mostrar promedio visible + input hidden
							$celda.append('<b><h6>'+promedio+'</h6></b>');
							$celda.append(hiddenInput);
						}
					}, "json"
				);
			}

			function f_MostrarLeyesCierre(_cod_lote) {
				$('#fecharegistro_ley_cierre_' + _cod_lote).html('');

				$.post( "apis/backend.php", { accion: "mostrar_analisisleyes_cierre", cod_lote: _cod_lote},
					function( data ) {
						if(data.estado == 1){
							if (data.res[0]['gestionleyes_cerrado'] == 1){
								// Setea columna de Estados
									bg_color = '';
									html_estado = '<label style="color: #ffffff; font-size: 14px;">';

									if (data.res[0]['gestionleyes_cerrado_isvalorizar'] == 1){
										html_estado += '	Con Valor Comercial';
										bg_color = '#ffc107';
									}
									else{
										html_estado += '	Sin Valor Comercial';
										bg_color = '#6c757d';
									}

									html_estado += '</label>';

									$("#td_loteestado_" + _cod_lote).html(html_estado);
									$("#td_loteestado_" + _cod_lote).css('background-color', bg_color);
									$("#td_loteestado_" + _cod_lote).attr("title", data.res[0]['gestionleyes_cerrado_comentario']);

								// Setea columnas de Cierre
									var _html_reabrir = '';

									if (data.res[0]['is_valorizado'] == 0){
										_html_reabrir = ' <label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_ReabrirCierre(\''+_cod_lote+'\')"><u> Reabrir </u></label>'
									}

									$("#td_ley_cierre_" + _cod_lote).html(_html_reabrir);
									$('#fecharegistro_ley_cierre_' + _cod_lote).html(data.res[0]['gestionleyes_cerrado_fechahoraregistro'] + '<br>' + data.res[0]['gestionleyes_cerrado_usuarioregistro']);

									$("#tr_ley_" + _cod_lote + " input, #tr_ley_" + _cod_lote + " select, #tr_ley_" + _cod_lote + " textarea").prop("disabled", true);
							}
							else{
								// Setea columna de Estados
									var html_estado = '<label style="color: #ffffff; font-size: 14px;">';
									html_estado += '	Pendiente';
									html_estado += '</label>';

									$("#td_loteestado_" + _cod_lote).html(html_estado);
									$("#td_loteestado_" + _cod_lote).css('background-color', '#dc3545');

								// Setea columnas de Cierre
									var _html_check = '   <div class="d-flex justify-content-center">';
									_html_check += '    <button class="btn btn-primary" type="button" onclick="f_ConfirmarCierre(1, ' + "'" + _cod_lote + "'" + ');" style="font-size: 13px;">';
									_html_check += '      Con Valor Comercial';
									_html_check += '    </button>';

									_html_check += '    <button class="btn btn-secondary" type="button" onclick="f_ConfirmarCierre(0, ' + "'" + _cod_lote + "'" + ');" style="font-size: 13px; margin-left: 5px;">';
									_html_check += '      Sin Valor Comercial';
									_html_check += '    </button>';
									_html_check += '  </div>';

									$("#td_ley_cierre_" + _cod_lote).html(_html_check);
									$('#fecharegistro_ley_cierre_' + _cod_lote).html('');
							}

							// Validando si está valorizado
								if (data.res[0]['is_valorizado'] == 1){
									var _html = $("#td_loteestado_" + _cod_lote).html();

									_html += `<br>
														<label style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px; background-color: #198754; color: #ffffff;">
															Valorizado
														</label>
														`;

									$("#td_loteestado_" + _cod_lote).html(_html);
								}
						}

					}, "json");
			}

			function verificarCheckboxes(codLote, idGrupo) {
				var checkboxes = document.querySelectorAll(`input[name='chk_ley_valor_${codLote}_${idGrupo}']`);
				var chk_cierre = document.getElementById(`chk_ley_cierre_${codLote}`);

				// Verificar si alguno está marcado
				var algunoMarcado = Array.from(checkboxes).some(checkbox => checkbox.checked);

				if (chk_cierre) {
					chk_cierre.style.display = algunoMarcado ? "" : "none";
				} 

			}

			function descomponerIDLoteGrupoElemento(id) {
				const regex = /^td_ley_promedio_([^_]+)_([^_]+)_([^_]+)$/;
				const match = id.match(regex);

				if (match) {
						return {
								codlote: match[1],
								codgrupo: match[2],
								abvelemento: match[3]
						};
				} else {
						return null;
				}
			}

			function f_AdminRegistroLote(){
				// Cargando lista de lotes
					f_LoadListaLotes();

				// Cargando datos
					f_OpenModal('modal_addregistrolote');
			}

			function f_GrabarRegistroLote(){
					var cod_lote = f_CleanInjection($("#lista_lotes").val());

				// Validando datos
					if (cod_lote == null){
						alert("Debe seleccionar el Lote.");

						return;
					}
					if (cod_lote.length == 0){
						alert("Debe seleccionar el Lote.");

						return;
					}
					
				// Grabando Datos
					$.post( "apis/backend.php", { accion: "grabar_CierreResultados_Leyes_valor",  cod_lote: cod_lote },
						function( data ) {
							if (data.estado == 2){
								alert("El documento ingresado ya fue registrado anteriormente.\n\nPor favor verificar");

								return;
							}
							else{
								if(data.estado == 1){
									f_LoadResultados();

									f_cerrarModal('modal_addregistrolote');
								}
								else{
									alert("Ocurrió un error al momento de grabar el registro");
								}
							}

						}, "json");
			}

			function f_ExportToExcel(){
				// Obteniendo filtros
					var fecha_inicio = $("#fecha_inicio").val();
					var fecha_fin = $("#fecha_fin").val();
					var filtro_estado = $("#filtro_estado").val();
					var filtro_lote = $("#filtro_lote").val();

				window.location.href = "export_to_excel/leyes_gestionar.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_estado="+filtro_estado+"&filtro_lote="+filtro_lote;
			}
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');
				}
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadingResumen(_is_show){
				if (_is_show == 1){
					$("#wt_resumen").show();
				}
				else{
					$("#wt_resumen").hide();
				}
			}

			function f_ConfirmarCierre(_is_valorizar, _cod_lote){
				// Validando ingreso de datos (Hidden)
					const v = _validarHiddenCierre(_cod_lote);

					if (!v.ok){
						alert(v.msg);   // muestra mensaje con lista detallada

						if ($(checkbox).is(':checkbox')) {
							$(checkbox).prop('checked', false);
						}

						return; // Detiene proceso de grabación
					}

					$("#hd_confirmarvalorizacionLote").val(_cod_lote);
					$("#hd_confirmarvalorizacionIsValorizar").val(_is_valorizar);

				// Setear Título de ventana
					var bg_titulo = '';
					var html_titulo = '';

					if (_is_valorizar == 1){
						bg_titulo = '#0d6efd';
						html_titulo = 'Confirmar Valorización: <b>' + _cod_lote + '</b>';
					}
					else{
						bg_titulo = '#dc3545';
						html_titulo = 'Sin Valor Comercial: <b>' + _cod_lote + '</b>';
					}

					$("#modalheader_ConfirmarValorizacionLabel").css('background-color', bg_titulo);
					$("#modal_ConfirmarValorizacionLabel").html(html_titulo);

				// Limpiando comentario
					$("#confirmarvalorizacion_comentario").val('');

				// Abrir pantalla de confirmación
					f_OpenModal('modal_ConfirmarValorizacion');
			}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			// ---------- Helpers ----------
			function _toNum(v){
				if (v == null) return NaN;
				v = (v + '').trim().replace(/\s/g, '');
				return parseFloat(v.replace(',', '.'));
			}

			// Busca el input dentro de la fila del lote, por name exacto y data-abvelemento.
			// OJO: NO usamos [type="hidden"] porque tus inputs son <input hidden ...>
			function _findHiddenEnFila(cod_lote, grupo, abv){
				// restringimos a la fila del lote para evitar cruces con otros lotes
				const $fila = $('#tr_ley_' + cod_lote);
				return $fila.find(
					'input[name="input_ley_promedio_' + cod_lote + '_' + grupo + '"][data-abvelemento="' + abv + '"]'
				).eq(0);
			}

			// Valida los 4 valores clave antes de cerrar
			function _validarHiddenCierre(cod_lote){
				const objetivos = [
					{grupo: 3, abv: 'newau', etiqueta: 'Au (newau)'},
					// {grupo: 3, abv: 'newag', etiqueta: 'Ag (newag)'},
					{grupo: 4, abv: 'h2o',   etiqueta: 'H2O (h2o)'},
					{grupo: 5, abv: 'recup', etiqueta: 'Recuperación (recup)'}
				];

				const faltantes = [];

				objetivos.forEach(o => {
					const $inp = _findHiddenEnFila(cod_lote, o.grupo, o.abv);

					if ($inp.length === 0){
						faltantes.push(o.etiqueta + ' (no encontrado)');
						return;
					}
					const n = _toNum($inp.val());
					if (!isFinite(n) || n === 0){
						faltantes.push(o.etiqueta);
					}
				});

				if (faltantes.length){
					return {
						ok: false,
						msg:
							'No se puede cerrar el lote ' + cod_lote + '. Los siguientes datos deben tener un valor promedio mayor a 0.00:\n\n- ' +
							faltantes.join('\n- ')
					};
				}
				return { ok: true };
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_ConfirmarValorizacion(){
				var _cod_lote = $("#hd_confirmarvalorizacionLote").val();
				var _is_valorizar = $("#hd_confirmarvalorizacionIsValorizar").val();

				// Obteniendo comentario
					var comentario = $("#confirmarvalorizacion_comentario").val().trim().toUpperCase();

				// Validando datos
					if (_is_valorizar == 0 && comentario.length == 0){
						alert("Debe ingresar el motivo.");

						return;
					}

				// Grabando cabecera
					$.post( "apis/backend.php", { accion: "grabar_analisisleyes_cierre", is_cierre: 1, is_valorizar: _is_valorizar, cod_lote: _cod_lote, comentario }, 
						function( data ) {
							if(data.estado == 1){
								// Grabando detalle
									var resultados = [];

									var fila = $('#tr_ley_' + _cod_lote).closest('tr'); 

									fila.find('td[id^="td_ley_promedio_"]').each(function () {
										const id = $(this).attr('id');
										const texto = $(this).text().trim();
										const valor = parseFloat(texto);

										// if (!isNaN(valor) && valor > 0) {
										if (!isNaN(valor)) {
												const info = descomponerIDLoteGrupoElemento(id);
												if (info) {
														resultados.push({
																codlote: info.codlote,
																codgrupo: info.codgrupo,
																abvelemento: info.abvelemento,
																valor: valor
														});
												}
										}
									});

									if (resultados.length > 0) {
										// Grabando datos
											var d = 1;

											resultados.forEach(r => {
												var cod_lote = r.codlote;
												var id_grupo = r.codgrupo;
												var abv_elemento = r.abvelemento;
												var valor = r.valor;

												var bg_color = '';
												var html_estado = '';

												$.post( "apis/backend.php", { accion: "grabar_analisisleyes_cierredetalle", cod_lote: cod_lote, id_grupo: id_grupo, abv_elemento: abv_elemento, valor: valor, is_cierre: 1, comentario }, 
													function( data ) {
														if(data.estado == 1){
															if (d == 1){
																// Setea columna de Estados
																	bg_color = '';
																	html_estado = '<label style="color: #ffffff; font-size: 14px;">';

																	if (_is_valorizar == 1){
																		html_estado += '	Con Valor Comercial';
																		bg_color = '#ffc107';
																	}
																	else{
																		html_estado += '	Sin Valor Comercial';
																		bg_color = '#6c757d';
																	}

																	html_estado += '</label>';

																	$("#td_loteestado_" + cod_lote).html(html_estado);
																	$("#td_loteestado_" + cod_lote).css('background-color', bg_color);
																	$("#td_loteestado_" + cod_lote).attr("title", comentario);

																// Setea columnas de Cierre
																	_html_reabrir = ' <label style="font-style: italic; color: #F23030; cursor: pointer;" onclick="f_ReabrirCierre(\''+cod_lote+'\')">';
																	_html_reabrir += '  <u> Reabrir </u>';
																	_html_reabrir += '</label>'

																	$("#td_ley_cierre_" + cod_lote).html(_html_reabrir);
																	$('#fecharegistro_ley_cierre_' + cod_lote).html(data.fechahora_registro + '<br>' + data.usuario_registro);

																	$("#tr_ley_" + cod_lote + " input, #tr_ley_" + cod_lote + " select, #tr_ley_" + cod_lote + " textarea").prop("disabled", true);
															}

															d ++;
														}

													}, "json");
											});

										// Cerrando Modal
											f_cerrarModal('modal_ConfirmarValorizacion');
									}
									else{
										if ($("#chk_ley_cierre_" + cod_lote).prop('checked')) {
											alert("No tiene resultados para reportar.");

											$("#chk_ley_cierre_" + cod_lote).prop('checked', false);
										}
									}
							}
						});
			}

			function f_ReabrirCierre(_cod_lote) {
				// Grabando cabecera
					$.post( "apis/backend.php", { accion: "grabar_analisisleyes_cierre", is_cierre: 0, is_valorizar: 0, cod_lote: _cod_lote, comentario: '' }, 
						function( data ) {
							if(data.estado == 1){
								// Grabando detalle
									var resultados = [];

									var fila = $('#tr_ley_' + _cod_lote).closest('tr'); 

									fila.find('td[id^="td_ley_promedio_"]').each(function () {
										const id = $(this).attr('id');
										const texto = $(this).text().trim();
										const valor = parseFloat(texto);

										if (!isNaN(valor)) {
											const info = descomponerIDLoteGrupoElemento(id);
											if (info) {
												resultados.push({
														codlote: info.codlote,
														codgrupo: info.codgrupo,
														abvelemento: info.abvelemento,
														valor: valor
												});
											}
										}
									});

									if (resultados.length > 0) {
										resultados.forEach(r => {
											var cod_lote = r.codlote;
											var id_grupo = r.codgrupo;
											var abv_elemento = r.abvelemento;
											var valor = r.valor;

											$.post( "apis/backend.php", { accion: "grabar_analisisleyes_cierredetalle", cod_lote: cod_lote, id_grupo: id_grupo, abv_elemento: abv_elemento, valor: valor, is_cierre: 0 }, 
											function( data ) {
												if(data.estado == 1){
													// Setea columna de Estados
														var html_estado = '<label style="color: #ffffff; font-size: 14px;">';
														html_estado += '	Pendiente';
														html_estado += '</label>';

														$("#td_loteestado_" + _cod_lote).html(html_estado);
														$("#td_loteestado_" + _cod_lote).css('background-color', '#dc3545');
														$("#td_loteestado_" + _cod_lote).attr("title", '');

													// Setea columnas de Cierre
														var _html_check = '	<div class="d-flex justify-content-center">';
														_html_check += '    <button class="btn btn-primary" type="button" onclick="f_ConfirmarCierre(1, ' + "'" + cod_lote + "'" + ');" style="font-size: 13px;">';
														_html_check += '      Con Valor Comercial';
														_html_check += '    </button>';

														_html_check += '    <button class="btn btn-secondary" type="button" onclick="f_ConfirmarCierre(0, ' + "'" + cod_lote + "'" + ');" style="font-size: 13px; margin-left: 5px;">';
														_html_check += '      Sin Valor Comercial';
														_html_check += '    </button>';
														_html_check += '  </div>';

														$("#td_ley_cierre_" + cod_lote).html(_html_check);
														$('#fecharegistro_ley_cierre_' + cod_lote).html('');

														$("#tr_ley_" + cod_lote + " input, #tr_ley_" + cod_lote + " select, #tr_ley_" + cod_lote + " textarea").prop("disabled", false);
												}

											}, "json");
										});
									}
									else{
										if ($("#chk_ley_cierre_" + _cod_lote).prop('checked')) {
											alert("No tiene resultados para reportar.");

											$("#chk_ley_cierre_" + _cod_lote).prop('checked', false);
										}
									}
							}
						});
			}
		</script>


	</body>
</html>