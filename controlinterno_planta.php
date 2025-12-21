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

	<!--Date Range Picker-->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

	<title><?php echo $nom_app; ?> | Cumplimiento - Planta</title>

	<script type="text/javascript">
		var is_mobile = 0;
	</script>
</head>

<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%" id="body-inner">

<section>
	<div class="container-fluid" id="content">
		<div class="row">
			<!-- Llamando a Navbar -->
			<?php echo $navbar_maintop; ?>

			<div class="row">
				<!-- Menús principales -->
				<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #DEDEDE;">

				</div>

				<div class="col-md-11 col-sm-11 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
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
											<h6 style="font-size: 14px;">Por Planta</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<span class="span_select2" style="position:relative; width: 100% !important;">
												<select id="filtro_cliente" class="form-select" style="text-align: left; font-size: 14px; width: 100%;" >
													<option selected value="">Elija una opción...</option>

													<?php
													$q_plantas = "SELECT Id,
																				 nombre_comercial
																				FROM tbconfig_plantas
																				WHERE 
																					  estado = 'A'
																			  ";

													if ($res_plantas = mysqli_query($enlace, $q_plantas)){
														if (mysqli_num_rows($res_plantas) > 0) {
															while($row_plantas = mysqli_fetch_array($res_plantas)){
																?>
																<option value="<?php echo $row_plantas["Id"]; ?>"><?php echo $row_plantas["nombre_comercial"]; ?></option>
																<?php
															}
														}
													}
													?>
												</select>
											</span>
										</div>
									</div>
								</div>

								<div class="row" style="padding-left: 30px; margin-top: 25px; margin-bottom: 10px; font-size: 13px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadCabeceraProcesos();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
											<i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
										</button>
									</div>

									<div class="col-md-2 col-sm-2 col-xs-12">
										<button id="btn_exportar_excel" class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;" disabled>
											<b>Exportar a Excel</b>
										</button>
									</div>
								</div>
							</div>

							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="row" style="padding: 20px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<div class="d-flex">
											<h5>Resumen de Cumplimiento</h5>

											<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Cargando datos...</label>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">

									<input id="hd_id_origendato" type="hidden" value="4">

									<table class="table table-bordered table-hover" id="tbl_resultados">
										<thead>
											<tr style="font-size: 12px;" id="tbl_cabecera_procesos"></tr>
											<tr style="font-size: 12px;" id="tbl_cabecera_detalles"></tr>
											<tr style="font-size: 12px;" id="tbl_cabecera_tipo_datos"></tr>

											<input id="hd_arr_id_proceso_detalle" type="hidden">
											<input id="hd_arr_id_proceso_detalle_tipo_dato" type="hidden">
										</thead>

										<tbody id="tbl_detalle">

										</tbody>

										<tbody id="tbl_detalle_excel" > 

										</tbody>
									</table>
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

		<!-- Ventanas modales Control interno -->
		<div class="modal fade " id="modal_addcontrolinterno" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addcontrolinternoLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="modal_addcontrolinternoLabel">Control Interno</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; width: 100%;">
							<p>Proceso: 
								<label id="lbl_proceso_descripcion"></label>
							</p>
							<p>Detalle: 
								<label id="lbl_detalle_descripcion"></label>
							</p>
						</div>


						<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
							<table class="table table-bordered table-hover">
								<thead>
									<tr style="font-size: 12px;" id="tbl_cabecera_gestiondocumentaria_tipo_datos"></tr>
								</thead>
								<tbody id="tbl_gestiondocumentaria_detalle"> 
								</tbody>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Ventanas modales Reporte Gestión Documentaria -->
		<div class="modal fade modal-dialog-scrollable " id="modal_reporteGestionDocumentaria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_reporteGestionDocumentariaLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="modal_reporteGestionDocumentariaLabel">
							Reporte de Cumplimiento:
							<span id="lbl_cliente_documento" style="margin-left: 5px; color: #337ab7;"></span>
							<span id="lbl_cliente_razon_social" style="margin-left: 5px; color: #337ab7;"></span>

						</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div id="modal-body-print-view" style="background-color: white !important;"></div>
					<div class="modal-body modal-body-print" style="background-color: white !important;">
						<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
							<div id="div_reporte"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary print" ><i class="bi bi-printer-fill"></i> Imprimir</button>
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Ventanas modales Gestión documentaria-->
		<div class="modal fade" id="modal_addgestiondocumentaria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addgestiondocumentariaLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="modal_addgestiondocumentariaLabel"></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div id="div_tipodato"></div>
					</div>

					<input id="hd_idregistro" type="hidden">
					<input id="hd_modograbar" type="hidden">
					<input id="hd_array_tipo_dato" type="hidden">

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" onclick="f_GrabarRegistro();">Grabar</button>
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

		<!--Date Range Picker-->
		<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
			// Genera menús
				f_GetMenuPrincipal();

			// Titulo de Pantalla
				$("#nv_titulo").html('| Cumplimiento - Planta');

			// Load Cabeceras Tabla
				f_LoadCabeceraProcesos();
			}

		</script>


		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			var parentElement = $(".span_select2");
			$('#filtro_cliente').select2({
		    theme: "bootstrap-5",
		    placeholder: $( this ).data( 'placeholder' ),
	     	//dropdownParent: $('#body-inner'),
     	  dropdownParent: parentElement,
		    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		    allowClear: false
			});
		</script>

		<!-- Funciones de registros -->
		<script type="text/javascript">
			// Lista de Unidades
			function f_GrabarRegistro(){

				var _modo = $("#hd_modograbar").val();
				var _arr_tipo_dato = $("#hd_array_tipo_dato").val();

				var res_arr_tipo_dato = [];

				var _html = '';

				if(_arr_tipo_dato != ""){
					res_arr_tipo_dato = _arr_tipo_dato.split(",");

					for (var i=0; i<res_arr_tipo_dato.length; i++)
					{
						res_arr_tipo_dato[i] = parseInt(res_arr_tipo_dato[i], 10);
					}
				}

				if(res_arr_tipo_dato.length>0){
					for (var i=0; i<res_arr_tipo_dato.length; i++)
					{
						if ($("#controlinterno_tipodato_"+res_arr_tipo_dato[i]).val() == null  || $("#controlinterno_tipodato_"+res_arr_tipo_dato[i]).val() == 0){
							alert("Debe ingresar dato(s).");
							return;
						}    
					}
				}

          // Grabando Datos
				$.post( "apis/backend.php", { accion: "grabar_controlinterno_gestiondocumentaria", modo_grabar: _modo, id_cliente: _id_cliente, id_proceso: _id_proceso,  id_detalle: _id_detalle},
					function( data ) {

						if(data.estado == 1){

							if(res_arr_tipo_dato.length>0){
								for (var i=0; i<res_arr_tipo_dato.length; i++)
								{

				  		    //Insertar el tipo de Input (Tipo de dato)
									var input_value = "";
									var formData = new FormData();
									var input_type =  $("#controlinterno_tipodato_"+res_arr_tipo_dato[i]).attr('type'); 
									if(input_type == 'file'){
										input_value = $('#controlinterno_tipodato_'+res_arr_tipo_dato[i])[0].files[0];
									}else if(input_type == 'checkbox'){
										input_value = $("#controlinterno_tipodato_"+res_arr_tipo_dato[i]).is(":checked") ? 1 : 0;
									}else{
										input_value = $("#controlinterno_tipodato_"+res_arr_tipo_dato[i]).val();
									}

									formData.append('input_type', input_type);
									formData.append('input_value', input_value);
									formData.append('id_tipo_dato', res_arr_tipo_dato[i]);
									formData.append('id_detalle', data.id_controlinterno_gestiondocumentaria_detalle);
									formData.append('id_cliente', _id_cliente);
									formData.append('accion', 'grabar_controlinterno_gestiondocumentaria_tipodato');

									$.ajax({
										url: 'apis/backend.php',
										type: 'POST',
										data: formData,
										contentType: false,
										processData: false,
										success: function(response) {
											if (response.estado == 0) {
												alert("Ocurrió un error al momento de ingresar dato(s).");
											}
											else{
												f_LoadClienteControlInternoResultados();
											}
										}
									});


								}
							}

							f_cerrarModal('modal_addgestiondocumentaria');
						}
						else{
							alert("Ocurrió un error al momento de grabar el Registro");
						}
					}, "json");

			};
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



			function f_LoadCabeceraProcesos(){

				$('#btn_exportar_excel').attr('disabled', false);

				var _html = '';

				var id_origendato = $("#hd_id_origendato").val();
				var filtro_tipocliente = "";

				$("#tbl_cabecera_procesos").html('');
				$("#tbl_cabecera_detalles").html('');
				$("#tbl_cabecera_tipo_datos").html('');
				$("#tbl_detalle").html('');
				$("#tbl_detalle_excel").html('');


				$.post( "apis/backend.php", { accion: "get_ListaCabeceraControlInternoProcesos", id_origendato:id_origendato, filtro_tipocliente: filtro_tipocliente}, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_cabecera_procesos").html(data.html);


							f_LoadCabeceraDetalles(data.arr_id_proceso);

						}


					}, "json");
			};


			function f_LoadCabeceraDetalles(arr_id_proceso){
				var _html = '';

				$("#tbl_cabecera_detalles").html('');

				$.post( "apis/backend.php", { accion: "get_ListaCabeceraControlInternoDetalles", arr_id_proceso: arr_id_proceso}, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_cabecera_detalles").html(data.html);
							$("#hd_arr_id_proceso_detalle").val(data.arr_proceso_detalle);

							f_LoadCabeceraTipoDatos(data.arr_id_detalle);
						}


					}, "json");
			};


			function f_LoadCabeceraTipoDatos(arr_id_detalle){
				var _html = '';

				$("#tbl_cabecera_tipo_datos").html('');

				$.post( "apis/backend.php", { accion: "get_ListaCabeceraControlInternoTipoDatos", arr_id_detalle: arr_id_detalle}, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_cabecera_tipo_datos").html(data.html);
							$("#hd_arr_id_proceso_detalle_tipo_dato").val(data.arr_proceso_detalle_tipo_dato);

							f_LoadResultados();
							f_LoadResultadosExcel();
						}


					}, "json");
			};


			function f_LoadCabeceraGestionDocumentariaTipoDatos(_id_cliente,_id_detalle){
				var _html = '';

				$("#tbl_cabecera_gestiondocumentaria_tipo_datos").html('');

				$.post( "apis/backend.php", { accion: "get_ListaCabeceraGestionDocumentariaTipoDatos", id_detalle: _id_detalle}, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_cabecera_gestiondocumentaria_tipo_datos").html(data.html);

							f_LoadGestionDocumentariaResultados(data.array_cabecera_tipo_dato,data.file_es_primero,_id_cliente,_id_detalle);
						}
					}, "json");
			};
			
			
			function f_LoadResultados(){


				var _html = '';

				// Obteniendo filtros
				var filtro_tipocliente = "";
				var filtro_cliente = $("#filtro_cliente").val();
				var id_origendato = $("#hd_id_origendato").val();

				var is_excel=0;

				var d = 1;

				f_LoadingResumen(1);

				$("#tbl_detalle").html('');

				$.post( "apis/backend.php", { accion: "get_ListaDetalleControlInternoPlanta", filtro_tipocliente: filtro_tipocliente, filtro_cliente: filtro_cliente, id_origendato: id_origendato }, 
					function( data ) {
						if(data.estado == 1){

							var arr_proceso_detalle = $("#hd_arr_id_proceso_detalle").val();
							var arr_proceso_detalle_tipo_dato = $("#hd_arr_id_proceso_detalle_tipo_dato").val();

							var arr_proceso_detalle = arr_proceso_detalle.split("$");

							var arr_proceso_detalle_tipo_dato = arr_proceso_detalle_tipo_dato.split("$");


							$.each( data.res, function( key, val ) {
								_html += '<tr style="cursor: pointer; font-size: 14px;">';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center">';

								_html += ' <button class="btn btn-success " type="button"  style="font-size: 14px; padding: 5px; width: 30px; margin-bottom: 5px" onclick="f_ReporteGestionDocumentaria('+val.Id+", '"+val.documento+"','"+val.razon_social+"'"+')">'
								_html += '   <i class="bi bi-file-earmark-text"></i>  '
								_html += ' </button>'

								_html += ' <button class="btn btn-warning " type="button" title="Reporte Vertical" style="font-size: 14px; padding: 5px; width: 30px; margin-bottom: 5px" onclick="f_ReporteGestionDocumentariaVertical('+val.Id+", '"+val.documento+"','"+val.razon_social+"'"+')">'
								_html += '   <i class="bi bi-body-text"></i>  '
								_html += ' </button>'

 								_html += ' <button class="btn btn-danger " type="button"  style="font-size: 14px; padding: 5px; width: 30px;" onclick="f_DescargarZIPGestionDocumentaria('+val.Id+", '"+val.documento+"'"+')">'
								_html += '   <i class="bi bi-arrow-bar-down"></i>  '
								_html += ' </button>'

								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
								_html += '  </td>';

								_html += '  <td id="total_cliente_'+val.Id+'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 75px">';
								_html += '<label id="lbl_inicio_total_cliente_'+val.Id+'"></label>';
								_html += ' de ';
								_html += '<label id="lbl_fin_total_cliente_'+val.Id+'"></label>';
								_html += '  </td>';

								_html += '  <td id="porcentaje_cliente_'+val.Id+'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 75px; padding-top: 25px">';
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
								_html += '      ' + val.documento;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
								_html += '      ' + val.razon_social;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
								_html += '      ' + val.direccion;
								_html += '  </td>';


								var arr_proceso_detalle_tipo_dato_param = '';
								if(arr_proceso_detalle_tipo_dato.length>0){
									for (var i = 0; i < arr_proceso_detalle_tipo_dato.length; i+=1) {
										var tipo_dato_detalle = arr_proceso_detalle_tipo_dato[i].split('|');
										var tipo_dato_detalle_mas = arr_proceso_detalle_tipo_dato[i+1] ? arr_proceso_detalle_tipo_dato[i+1].split('|') : "";

										var id_detalle = tipo_dato_detalle[1];
										var id_tipo_cliente = tipo_dato_detalle[2];

										var indice_cadena = arr_proceso_detalle_tipo_dato[i].indexOf("|");
										var id_tipo_dato = arr_proceso_detalle_tipo_dato[i].substring(0, indice_cadena);

										arr_proceso_detalle_tipo_dato_param += id_tipo_dato+"$";

										var bloquear_celda = "";

										if(id_tipo_dato==99999999){
											var ocultar_td = "";
											_html += '  <td  style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background:'+ocultar_td+'" id="td_tipo_dato_'+id_tipo_dato+'_'+val.Id+'">';
										}else{
											_html += '  <td  style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background:'+bloquear_celda+'" id="td_tipo_dato_'+id_tipo_dato+'_'+val.Id+'">';
										}


										if(id_tipo_dato==99999999){ 	

											var ocultar_boton = "";

											_html += ' <button class="btn btn-success " type="button"  style="font-size: 14px; padding: 5px; width: 30px;" onclick="f_AdminControlInterno('+tipo_dato_detalle[1]+','+val.Id+')" '+ocultar_boton+'>'
											_html += '   <i class="bi bi-file-earmark-text"></i> '
											_html += ' </button>'

										}

										_html += '  </td>';

									}
								}

								arr_proceso_detalle_tipo_dato_param = arr_proceso_detalle_tipo_dato_param.slice(0, -1);

								f_LoadGestionDocumentaria(val.Id,val.cod_tipocliente,arr_proceso_detalle_tipo_dato_param,is_excel);

								_html += '</tr>';

								d += 1;
							});
						}

						$("#tbl_detalle").html(_html);

						f_LoadingResumen(0);

					}, "json");
			};


			function f_LoadGestionDocumentariaResultados(_array_cabecera_tipo_dato,_file_es_primero,_id_cliente,_id_detalle){

				var _html = '';

				$("#tbl_gestiondocumentaria_detalle").html('');

				$.post( "apis/backend.php", { accion: "get_ListaDetalleGestionDocumentaria",id_cliente:_id_cliente, id_detalle:_id_detalle, file_es_primero:_file_es_primero,array_cabecera_tipo_dato:_array_cabecera_tipo_dato}, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_gestiondocumentaria_detalle").html(data.html);
						}
					}, "json");
			};



			function f_LoadResultadosExcel(){


				var _html = '';

				// Obteniendo filtros
				var filtro_tipocliente = "";
				var filtro_cliente = $("#filtro_cliente").val();
				var id_origendato = $("#hd_id_origendato").val();

				var is_excel=1;

				var d = 1;

				f_LoadingResumen(1);

				$("#tbl_detalle_excel").html('');

				$.post( "apis/backend.php", { accion: "get_ListaDetalleControlInternoPlanta", filtro_tipocliente: filtro_tipocliente, filtro_cliente: filtro_cliente, id_origendato: id_origendato }, 
					function( data ) {
						if(data.estado == 1){

							var arr_proceso_detalle = $("#hd_arr_id_proceso_detalle").val();
							var arr_proceso_detalle_tipo_dato = $("#hd_arr_id_proceso_detalle_tipo_dato").val();

							var arr_proceso_detalle = arr_proceso_detalle.split("$");

							var arr_proceso_detalle_tipo_dato = arr_proceso_detalle_tipo_dato.split("$");


							$.each( data.res, function( key, val ) {
								_html += '<tr style="cursor: pointer; font-size: 14px;">';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">';
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
								_html += '  </td>';

								_html += '  <td id="total_cliente_excel_'+val.Id+'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 75px">';
								_html += '<label id="lbl_inicio_total_cliente_excel_'+val.Id+'"></label>';
								_html += ' de ';
								_html += '<label id="lbl_fin_total_cliente_excel_'+val.Id+'"></label>';
								_html += '  </td>';

								_html += '  <td id="porcentaje_cliente_excel_'+val.Id+'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; min-width: 75px; padding-top: 25px">';
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
								_html += '      ' + val.documento;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
								_html += '      ' + val.razon_social;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
								_html += '      ' + val.direccion;
								_html += '  </td>';
								
								var arr_proceso_detalle_tipo_dato_param = '';
								if(arr_proceso_detalle_tipo_dato.length>0){
									for (var i = 0; i < arr_proceso_detalle_tipo_dato.length; i+=1) {
										var tipo_dato_detalle = arr_proceso_detalle_tipo_dato[i].split('|');
										var tipo_dato_detalle_mas = arr_proceso_detalle_tipo_dato[i+1] ? arr_proceso_detalle_tipo_dato[i+1].split('|') : "";

										var id_detalle = tipo_dato_detalle[1];
										var id_tipo_cliente = tipo_dato_detalle[2];

										var indice_cadena = arr_proceso_detalle_tipo_dato[i].indexOf("|");
										var id_tipo_dato = arr_proceso_detalle_tipo_dato[i].substring(0, indice_cadena);

										arr_proceso_detalle_tipo_dato_param += id_tipo_dato+"$";

										var bloquear_celda = "";

										if(id_tipo_dato==99999999){
											var ocultar_td = "";
											_html += '  <td  style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background:'+ocultar_td+'" id="td_tipo_dato_excel_'+id_tipo_dato+'_'+val.Id+'">';
										}else{
											_html += '  <td class="td_tipo_dato_excel_'+val.Id+'_'+id_detalle+'" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background:'+bloquear_celda+'" id="td_tipo_dato_excel_'+id_tipo_dato+'_'+val.Id+'">';
										}


										if(id_tipo_dato==99999999){ 	

											var ocultar_boton = "hidden";

											_html += ' <button class="btn btn-success " type="button"  style="font-size: 14px; padding: 5px; width: 30px;" onclick="f_AdminControlInterno('+tipo_dato_detalle[1]+','+val.Id+')" '+ocultar_boton+'>'
											_html += '   <i class="bi bi-file-earmark-text"></i> '
											_html += ' </button>'

										}

										_html += '  </td>';

									}
								}

								arr_proceso_detalle_tipo_dato_param = arr_proceso_detalle_tipo_dato_param.slice(0, -1);

								f_LoadGestionDocumentaria(val.Id,val.cod_tipocliente,arr_proceso_detalle_tipo_dato_param,is_excel);

								_html += '</tr>';

								d += 1;
							});
						}

						$("#tbl_detalle_excel").html(_html);

						f_LoadingResumen(0);

				

					}, "json");
			};

			function f_AdminControlInterno(_id_detalle,_id_cliente){
				f_OpenModal('modal_addcontrolinterno');

				$("#lbl_proceso_descripcion").html('');
				$("#lbl_detalle_descripcion").html('');
				$("#tbl_controlinterno_detalle").html('');
				$("#tbl_gestiondocumentaria_detalle").html('');



				$.post( "apis/backend.php", { accion: "get_ProcesoDetalle", id_detalle :_id_detalle }, 
					function( data ) {
						if(data.estado == 1){
							$("#lbl_proceso_descripcion").html(data.descripcion_proceso);
							$("#lbl_detalle_descripcion").html(data.descripcion_detalle);

							f_LoadCabeceraGestionDocumentariaTipoDatos(_id_cliente,_id_detalle);
							//f_LoadClienteControlInternoResultados(data.id_proceso,data.id_detalle,_id_cliente);

						}
					});

			}

			function f_LoadClienteControlInternoResultados(_id_proceso,_id_detalle,_id_cliente){
				var _html = '';
				var d = 1;

				$("#tbl_controlinterno_detalle").html('');

				$.post( "apis/backend.php", { accion: "get_ListaControlInternoGestionDocumentaria", id_cliente: _id_cliente, id_proceso: _id_proceso, id_detalle: _id_detalle }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.res, function( key, val ) {
								var valor = "";
								_html += '<tr style="cursor: pointer; font-size: 14px;">';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
								_html += '  </td>';

								if(val.input_type=='checkbox'){
									valor = val.input_value=='1' ? 'Si' : 'No';
								}else if(val.input_type=='file'){
									valor = "<a href='files/control_interno/"+val.input_value+"' download>Descargar</a>"
								}else{
									valor = val.input_value;
								}
								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
								_html += '      ' + valor;
								_html += '  </td>';

								_html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
								_html += '      ' + val.fechahora_registro;
								_html += '  </td>';

								_html += '</tr>';

								d += 1;
							});
						}
						else{
									// alert("No se encontraron resultados.");
						}

						$("#tbl_controlinterno_detalle").html(_html);

						f_LoadingResumen(0);

					}, "json");
			};




			function f_ExportToExcel(){

				$("#tbl_detalle").html("");

				// // Captura la tabla HTML

        var tabla_html = $('#tbl_resultados').prop('outerHTML');

        // Crea un formulario dinámico
        var form = $('<form method="post" action="export_to_excel/controlinterno.php"></form>');
        var input = $('<input type="hidden" name="tabla_html_param">').val(tabla_html);

        // Añade el input al formulario y lo envía
        form.append(input);
        $('body').append(form);
        form.submit();
			
				f_LoadResultados();
				f_LoadResultadosExcel();
			}

		// Eliminar registros
			function f_EliminarGestionDocumentaria(_id_controlinterno_gestiondocumentaria,_id_tipo_dato,_id_cliente, _id_detalle,_is_principal){
				if(confirm("¿Está seguro de eliminar el archivo seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
					$.post( "apis/backend.php", { accion: "eliminar_GestionDocumentaria", id_controlinterno_gestiondocumentaria: _id_controlinterno_gestiondocumentaria },
						function( data ) {
							if(data.estado == 1){

								$(".otro_tipo_" + _id_cliente + '_' + _id_detalle).prop('disabled', true);
								$(".otro_tipo_" + _id_cliente + '_' + _id_detalle).val('');
								$(".otro_tipo_font_" + _id_cliente + '_' + _id_detalle).html('');

								$(".td_tipo_dato_excel_" + _id_cliente + '_' + _id_detalle).html('');

								f_TotalLoadGestionDocumentaria(_id_cliente);

								$('#td_tipo_dato_excel_'+_id_tipo_dato+'_'+_id_cliente).html("");

								$('#td_tipo_dato_'+_id_tipo_dato+'_'+_id_cliente).html(
									'<i class="btn btn-primary bi bi-upload" style="font-weight: bold; font-size: 18px; cursor: pointer;padding: 5px;" onclick="f_AddFileGestionDocumentaria('+_id_tipo_dato+", "+_id_cliente+", "+_is_principal+')"></i>'+
									'<input type="file" class="form-control" id="input_id_tipo_dato_'+_id_tipo_dato+'_'+_id_cliente+'" hidden>'
									);

								$(".otro_tipo_" + _id_cliente + '_' + _id_detalle).prop('disabled', true);
							}
							else{
								alert("Ocurrió un error al momento de eliminar el archivo");
							}
						}, "json");
				}
			};

			function f_LoadGestionDocumentaria(id_cliente,id_tipo_cliente,array_id_tipo_dato,is_excel){
				var _html = '';

				$.post( "apis/backend.php", { accion: "get_ListaControlInternoGestionDocumentariaDetalle", id_cliente: id_cliente, array_id_tipo_dato: array_id_tipo_dato}, 
					function( data ) {
						if(data.estado == 1){
							var array_grupo_id_detalle = "";

							var id_detalle_x = 0;
							var is_inicio = 0;
							var tiene_archivo = 0;

							var contar_si = 0;
							var contar_total = 0;
							var porcentaje_cliente = 0;

							var contar_si_excel = 0;
							var contar_total_excel = 0;
							var porcentaje_cliente_excel = 0;

							$.each( data.res, function( key, val ) {

								if(is_excel == 0){
									is_inicio = 0;

									if(id_detalle_x != val.id_detalle){
										tiene_archivo = 0;
										is_inicio = 1;
									}

									if(val.input_value != ''){
										contar_si ++;
									}

									if(val.input_value == '0'){
										contar_si --;
									}

									var tiene_valor = val.input_value != '' ? 'si' : 'no';
									array_grupo_id_detalle += id_cliente+"|"+val.id_detalle+"|"+val.Id+"|"+tiene_valor+"$";

									var oculto_tipo_cliente = "";

									var desactivar = '';

									if (val.TIENE_ARCHIVO > 0){
										desactivar = is_inicio == 0 && tiene_archivo == 0 ? 'disabled' : '';
									}

									if(val.input_type=='checkbox'){
										var is_checked = val.input_value == 1 ? 'checked ' : '';
										$('#td_tipo_dato_'+val.Id+'_'+id_cliente).html(
											'<input type="'+val.input_type+'" class="form-check-input otro_tipo_' + id_cliente + '_' + val.id_detalle +'" role="switch" '+is_checked+' id="input_id_tipo_dato_'+val.Id+'_'+id_cliente+'" onclick="f_AgregarGestionDocumentaria('+val.Id+','+id_cliente+', '+val.id_gestiondocumentaria_tipo_dato+')" value="check" '+oculto_tipo_cliente+" "+desactivar+'/>'
											);
									}else if(val.input_type=='file'){

										var icono_imagen = "bi bi-upload";
										var btn_eliminar_file = "";

										if(val.id_gestiondocumentaria_tipo_dato != null){
											tiene_archivo = 1;

											icono_imagen = 'bi bi-card-image';
											btn_eliminar_file = '<br><label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarGestionDocumentaria('+val.id_gestiondocumentaria_tipo_dato+","+val.Id+","+id_cliente+', '+val.id_detalle+", "+val.is_principal+')" '+oculto_tipo_cliente +'>X</label>';

										}

										$('#td_tipo_dato_'+val.Id+'_'+id_cliente).html(
											'<button class="btn btn-primary '+icono_imagen+' ' + ((is_inicio == 0) ? 'otro_tipo_' + id_cliente + '_' + val.id_detalle : '') + '" style="font-weight: bold; font-size: 14px; cursor: pointer;padding: 5px;" onclick="f_AddFileGestionDocumentaria('+val.Id+","+id_cliente+", "+val.is_principal+')" '+oculto_tipo_cliente+' ' + desactivar + '></button>'+
											'<input type="'+val.input_type+'" class="form-control" value="'+val.input_value+'" id="input_id_tipo_dato_'+val.Id+'_'+id_cliente +'" hidden>'+
											btn_eliminar_file
											);
									}else if(val.input_type=='text'){
										$('#td_tipo_dato_'+val.Id+'_'+id_cliente).html(
											'<input type="'+val.input_type+'" class="form-control otro_tipo_' + id_cliente + '_' + val.id_detalle +'" value="'+val.input_value+'" id="input_id_tipo_dato_'+val.Id+'_'+id_cliente+'" onblur="f_AgregarGestionDocumentaria('+val.Id+','+id_cliente+', '+val.id_gestiondocumentaria_tipo_dato+')" style="width:150px; font-size: 13px" '+oculto_tipo_cliente+" "+desactivar+'>'
											);
									}else if(val.input_type=='date'){
										$('#td_tipo_dato_'+val.Id+'_'+id_cliente).html(
											'<input type="'+val.input_type+'" class="form-control otro_tipo_' + id_cliente + '_' + val.id_detalle +'" value="'+val.input_value+'" id="input_id_tipo_dato_'+val.Id+'_'+id_cliente+'" onblur="f_AgregarGestionDocumentaria('+val.Id+','+id_cliente+', '+val.id_gestiondocumentaria_tipo_dato+')" style="width:120px; font-size: 13px" '+oculto_tipo_cliente+" "+desactivar + '>'
											);

									}else if(val.input_type=='daterange'){
										$('#td_tipo_dato_'+val.Id+'_'+id_cliente).html(
											'<center><input type="text" name="'+val.input_type+'" class="form-control otro_tipo_' + id_cliente + '_' + val.id_detalle +'" id="input_id_tipo_dato_'+val.Id+'_'+id_cliente+'" onblur="" style="width:180px; font-size: 13px" '+oculto_tipo_cliente+" "+desactivar + '></center>'
											);

										 $('#input_id_tipo_dato_'+val.Id+'_'+id_cliente).daterangepicker({
										      autoUpdateInput: false,
										      locale: {
										          cancelLabel: 'Limpiar'
										      }
										  });

										 	if(val.input_value != ''){

										 		//Formatear Fecha de Vigencia FIN
										 		var res_vigencia = val.input_value.split(" - ");
										 		var fecha_fin_vigencia = res_vigencia[1];
												var fecha_fin_vigencia_format = fecha_fin_vigencia.replace(/\//g, "-");
												var partes_fecha_fin = fecha_fin_vigencia_format.split("-");
												var fecha_fin_format = `${partes_fecha_fin[2]}-${partes_fecha_fin[1]}-${partes_fecha_fin[0]}`;

												$('#input_id_tipo_dato_'+val.Id+'_'+id_cliente).val(val.input_value);

												var html_label_vigencia = "";

												//Restar Fechas para obtener los días de vencimiento
												var fecha_actual =  moment().format('YYYY-MM-DD');

												var fecha1 = new Date(fecha_fin_format);
												var fecha2 = new Date(fecha_actual);

												var diferencia_dias = (fecha1 - fecha2) / (1000 * 60 * 60 * 24);

												if(diferencia_dias >= 0){
													html_label_vigencia = "<font class='bg bg-warning vencimiento  otro_tipo_font_"+id_cliente+"_"+val.id_detalle+"' ><i class='bi bi-exclamation-circle-fill'></i> Vence en "+ diferencia_dias +" día(s)</font>";
												}else{
													html_label_vigencia = "<font class='bg bg-danger vencimiento  otro_tipo_font_"+id_cliente+"_"+val.id_detalle+"' style='color: white' ><i class='bi bi-exclamation-circle-fill'></i> Venció en "+ ((diferencia_dias)*-1) +" día(s)</font>";
												}

												$('#td_tipo_dato_'+val.Id+'_'+id_cliente).append(html_label_vigencia);

											}else{
												$('#input_id_tipo_dato_'+val.Id+'_'+id_cliente).val('');
											}

										  $('#input_id_tipo_dato_'+val.Id+'_'+id_cliente).on('apply.daterangepicker', function(ev, picker) {
										      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));

										      f_AgregarGestionDocumentaria(val.Id,id_cliente, val.id_gestiondocumentaria_tipo_dato);

									        var fecha_picker = picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY');
										      
										      //Formatear Fecha de Vigencia FIN
											 		var res_vigencia = fecha_picker.split(" - ");
											 		var fecha_fin_vigencia = res_vigencia[1];
													var fecha_fin_vigencia_format = fecha_fin_vigencia.replace(/\//g, "-");
													var partes_fecha_fin = fecha_fin_vigencia_format.split("-");
													var fecha_fin_format = `${partes_fecha_fin[2]}-${partes_fecha_fin[1]}-${partes_fecha_fin[0]}`;

													var html_label_vigencia = "";

													//Restar Fechas para obtener los días de vencimiento
													var fecha_actual =  moment().format('YYYY-MM-DD');

													var fecha1 = new Date(fecha_fin_format);
													var fecha2 = new Date(fecha_actual);

													var diferencia_dias = (fecha1 - fecha2) / (1000 * 60 * 60 * 24);

													if(diferencia_dias >= 0){
														html_label_vigencia = "<font class='bg bg-warning vencimiento  otro_tipo_font_"+id_cliente+"_"+val.id_detalle+"' ><i class='bi bi-exclamation-circle-fill'></i> Vence en "+ diferencia_dias +" día(s)</font>";
													}else{
														html_label_vigencia = "<font class='bg bg-danger vencimiento  otro_tipo_font_"+id_cliente+"_"+val.id_detalle+"' style='color: white' ><i class='bi bi-exclamation-circle-fill'></i> Venció en "+ ((diferencia_dias)*-1) +" día(s)</font>";
													}


													$('#td_tipo_dato_'+val.Id+'_'+id_cliente).find('font').remove();
													$('#td_tipo_dato_'+val.Id+'_'+id_cliente).append(html_label_vigencia);

										  });

										  $('#input_id_tipo_dato_'+val.Id+'_'+id_cliente).on('cancel.daterangepicker', function(ev, picker) {
										      $(this).val('');
										      f_AgregarGestionDocumentaria(val.Id,id_cliente, val.id_gestiondocumentaria_tipo_dato);
										      $('#td_tipo_dato_'+val.Id+'_'+id_cliente).find('font').remove();
										  });

									}else{
										$('#td_tipo_dato_'+val.Id+'_'+id_cliente).html(
											'<input type="'+val.input_type+'" class="form-control otro_tipo_' + id_cliente + '_' + val.id_detalle +'" value="'+val.input_value+'" id="input_id_tipo_dato_'+val.Id+'_'+id_cliente+'" onblur="f_AgregarGestionDocumentaria('+val.Id+','+id_cliente+', '+val.id_gestiondocumentaria_tipo_dato+')" style="font-size: 13px" '+oculto_tipo_cliente+" "+desactivar+'>'
											);
									}

									id_detalle_x = val.id_detalle;

									contar_total ++;

								}else{
									$('#td_tipo_dato_excel_'+val.Id+'_'+id_cliente).html('');

									if(val.input_value != ''){
										contar_si_excel ++;
									}

									if(val.input_value == '0'){
										contar_si_excel --;
									}


									if(val.input_type=='checkbox'){

										var value_checkbox = "";

										if(val.input_value != ''){
												if(val.input_value == 1){
														value_checkbox = 'Si';
												}else{
														value_checkbox = 'No';
												}
										}

										$('#td_tipo_dato_excel_'+val.Id+'_'+id_cliente).html(
											value_checkbox
										);
									}else if(val.input_type=='file'){

										var value_file = "";
										if(val.input_value != ""){
											value_file = '<a target="_blank" class="success" href="http://localhost/aum/files/control_interno/'+val.input_value+'" >Descargar</a>';
										}
										$('#td_tipo_dato_excel_'+val.Id+'_'+id_cliente).html(
											value_file
										);				
									}else{
										$('#td_tipo_dato_excel_'+val.Id+'_'+id_cliente).html(
											val.input_value
										);
									}
								}

						   	$('#btn_exportar_excel').attr('disabled', false);
								
								contar_total_excel ++;

							});

							if(is_excel == 0){
								var array_nuevo_grupo_id_detalle = array_grupo_id_detalle.slice(0, -1);

								var resultado_array_id_detalle = array_nuevo_grupo_id_detalle.split("$");

								for (var i=0; i<resultado_array_id_detalle.length; i++)
								{

									var tiene_valor = resultado_array_id_detalle[i].indexOf('si');

									var resultado_array_id_detalle_valor = resultado_array_id_detalle[i].split("|");

								}

								$('#lbl_inicio_total_cliente_'+id_cliente).html(contar_si);
								$('#lbl_fin_total_cliente_'+id_cliente).html(contar_total);

								porcentaje_cliente = (contar_si * 100) / contar_total;

								$('#porcentaje_cliente_'+id_cliente).html(
									'<div class="progress">'+
									'<div class="progress-bar" role="progressbar" style="width: '+porcentaje_cliente+'%;" aria-valuenow="'+porcentaje_cliente+'" aria-valuemin="0" aria-valuemax="100"></div>'+
									'</div>'+
									'<label style="font-size: 13px">'+porcentaje_cliente.toFixed(0)+'%</label>'
								);
							}else{

								$('#lbl_inicio_total_cliente_excel_'+id_cliente).html(contar_si_excel);
								$('#lbl_fin_total_cliente_excel_'+id_cliente).html(contar_total_excel);

								porcentaje_cliente_excel = (contar_si_excel * 100) / contar_total_excel;

								$('#porcentaje_cliente_excel_'+id_cliente).html(
									'<div class="progress">'+
									'<div class="progress-bar" role="progressbar" style="width: '+porcentaje_cliente_excel+'%;" aria-valuenow="'+porcentaje_cliente_excel+'" aria-valuemin="0" aria-valuemax="100"></div>'+
									'</div>'+
									'<label style="font-size: 13px">'+porcentaje_cliente_excel.toFixed(0)+'%</label>'
								);
							}

						}
						else{
					// alert("No se encontraron resultados.");
						}

						f_LoadingResumen(0);

					}, "json");

				

			};

			function f_AddFileGestionDocumentaria(_id_tipo_dato, _id_cliente,_is_principal){
				$('#input_id_tipo_dato_'+_id_tipo_dato+'_'+_id_cliente).trigger('click'); 

				var id_origendato = $("#hd_id_origendato").val();

				document.getElementById('input_id_tipo_dato_'+_id_tipo_dato+'_'+_id_cliente).addEventListener('change', function(e) {
					if (e.target.files[0]) {

			    //Insertar

						var formData = new FormData();

						input_value = $('#input_id_tipo_dato_'+_id_tipo_dato+'_'+_id_cliente)[0].files[0];
						input_type =  $("#input_id_tipo_dato_"+_id_tipo_dato+'_'+_id_cliente).attr('type'); 

						formData.append('input_type', input_type);
						formData.append('input_value', input_value);
						formData.append('id_tipo_dato', _id_tipo_dato);
						formData.append('id_cliente', _id_cliente);
						formData.append('is_principal', _is_principal);
						formData.append('id_origendato', id_origendato);
						formData.append('accion', 'grabar_GestionDocumentariaFile');
						$.ajax({
							url: 'apis/backend.php',
							type: 'POST',
							data: formData,
							contentType: false,
							processData: false,
							success: function(response) {
								if (response.estado == 0) {
									alert("Ocurrió un error al momento de ingresar el archivo.");
								}
								else{
									$(".otro_tipo_" + _id_cliente + '_' + response.id_detalle).prop('disabled', false);
									$(".otro_tipo_" + _id_cliente + '_' + response.id_detalle).val('');
									$(".otro_tipo_font_" + _id_cliente + '_' + response.id_detalle).html('');

									$(".td_tipo_dato_excel_" + _id_cliente + '_' + response.id_detalle).html('');

									f_TotalLoadGestionDocumentaria(_id_cliente);

									var value_file = "";
									var btn_eliminar_file = "";

									if(response.input_value != ""){
										btn_eliminar_file = '<br><label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarGestionDocumentaria('+response.id_controlinterno_gestiondocumentaria+","+_id_tipo_dato+","+_id_cliente+', '+response.id_detalle+', '+response.is_principal+')">X</label>';

										$('#td_tipo_dato_'+_id_tipo_dato+'_'+_id_cliente).html(
											'<i class="bi bi-card-image btn btn-primary" style="font-weight: bold; font-size: 14px; cursor: pointer;padding: 5px;" onclick="f_AddFileGestionDocumentaria('+_id_tipo_dato+","+_id_cliente+", "+response.is_principal+')"></i>'+
											'<input type="file" class="form-control" id="input_id_tipo_dato_'+_id_tipo_dato+'_'+_id_cliente+'" hidden>'+	
											btn_eliminar_file
										);

										value_file = '<a target="_blank" class="success" href="http://localhost/aum/files/control_interno/'+response.input_value+'" >Descargar</a>';
									}
									$('#td_tipo_dato_excel_'+_id_tipo_dato+'_'+_id_cliente).html(
										value_file
									);

								}
							}
						});

					}
				});




			}



		// Actualizar registros input
			function f_AgregarGestionDocumentaria(id_tipo_dato,id_cliente, _id_gestiondocumentaria_tipo_dato){

				var input_type =  $("#input_id_tipo_dato_"+id_tipo_dato+'_'+id_cliente).attr('type'); 
				var id_origendato = $("#hd_id_origendato").val();

				var valor = "";
				var valor_excel = "";

				if(input_type=='checkbox'){
					valor = $("#input_id_tipo_dato_"+id_tipo_dato+'_'+id_cliente).is(":checked") ? 1 : 0;
					valor_excel = $("#input_id_tipo_dato_"+id_tipo_dato+'_'+id_cliente).is(":checked") ? 'Si' : 'No';
				}else{
					valor = $('#input_id_tipo_dato_'+id_tipo_dato+'_'+id_cliente).val();
					valor_excel = $('#input_id_tipo_dato_'+id_tipo_dato+'_'+id_cliente).val();
				}

				$.post( "apis/backend.php", { accion: "agregar_GestionDocumentaria", id_tipo_dato: id_tipo_dato, id_cliente:id_cliente, valor: valor, id_origendato: id_origendato, id_gestiondocumentaria_tipo_dato: _id_gestiondocumentaria_tipo_dato },
					function( data ) {
						if(data.estado == 1){

							f_TotalLoadGestionDocumentaria(id_cliente);

							$('#td_tipo_dato_excel_'+id_tipo_dato+'_'+id_cliente).html(valor_excel);

						}
						else{
							alert("Ocurrió un error al momento de actualizar la Gestión documentaria.");
						}
					}, "json");
			}

			//Descargar ZIP Gestión Documentaria
			function f_DescargarZIPGestionDocumentaria(_id_cliente, _documento_cliente){

				var id_origendato = $("#hd_id_origendato").val();

				$.post( "apis/backend.php", { accion: "get_ZipGestionDocumentaria", id_cliente :_id_cliente, documento_cliente : _documento_cliente, id_origendato : id_origendato }, 
					function( data ) {
						if(data.estado == 1){
							var nombre_archivo_zip = _documento_cliente+'.zip';
							var link = document.createElement('a')
						  link.setAttribute('download', _documento_cliente)
						  link.setAttribute('href', 'http://localhost/aum/files/control_interno/zip/'+nombre_archivo_zip)
						  link.click();
						
							$.post( "apis/backend.php", { accion: "delete_ZipGestionDocumentaria", nombre_archivo_zip: nombre_archivo_zip }, 
							function( data ) {
								if(data.estado == 1){
									alert('Se descargó el archivo correctamente.');
								}
							});

						}else{
							alert("No se encontraron archivos en el ZIP");
						}
					});

			}

			//Reporte Modal
			function f_ReporteGestionDocumentaria(_id_cliente, _documento_cliente, _razon_social_cliente){
				
				var _html = '';
				var _html_d = '';


				$("#lbl_cliente_razon_social").html("");
				$("#lbl_cliente_documento").html("");

				f_OpenModal('modal_reporteGestionDocumentaria');

				$("#lbl_cliente_razon_social").html(_razon_social_cliente);
				$("#lbl_cliente_documento").html(_documento_cliente+" - ");


				var id_origendato = $("#hd_id_origendato").val();

				$("#div_reporte").html('');

				var formDataProceso = new FormData();
				formDataProceso.append('accion', 'get_ListaControlInternoProcesos');
				formDataProceso.append('id_origendato', id_origendato);

				$.ajax({
					url: 'apis/backend.php',
					type: 'POST',
					data: formDataProceso,
					contentType: false,
					processData: false,
				 	async: false, 
					success: function(data) {
						if (data.estado == 1) {
							$.each( data.res, function( key, val) {

								var id_proceso = val.Id;

								_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px;">'

								_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px;text-align: center; background: #000; color: white">'
								_html += '<h6>'+val.descripcion+'</h6>'
								_html += '</div>'

								// var formDataDetalle = new FormData();
								// formDataDetalle.append('accion', 'get_ListaControlInternoDetalles');
								// formDataDetalle.append('id_proceso', val.Id);

								// $.ajax({
								// 	url: 'apis/backend.php',
								// 	type: 'POST',
								// 	data: formDataDetalle,
								// 	contentType: false,
								// 	processData: false,
								//  	async: false, 
								// 	success: function(data) {
								// 		if (data.estado == 1) {
								// 			$.each( data.res_d, function( key, val2) {
								// 				_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">'

								// 				_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px;text-align: center; background: #616366; color: white">'
								// 				_html += '<h6>'+val2.descripcion+'</h6>';
								// 					_html += '</div>'

								// 				var formDataTipoDato = new FormData();
								// 				formDataTipoDato.append('accion', 'get_ListaControlInternoTipoDatos');
								// 				formDataTipoDato.append('id_detalle', val2.Id);

								// 				$.ajax({
								// 					url: 'apis/backend.php',
								// 					type: 'POST',
								// 					data: formDataTipoDato,
								// 					contentType: false,
								// 					processData: false,
								// 				 	async: false, 
								// 					success: function(data) {
								// 						if (data.estado == 1) {
								// 							$.each( data.res_t, function( key, val3) {
								// 								_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">'

								// 								_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px;text-align: center; background: #acacad; color: white">'
								// 								_html += '<h6>'+val3.etiqueta+'</h6>';
								// 								_html += '</div>';
								// 								_html += '</div>';
								// 							});
								// 						}
								// 					}
								// 				});

								// 				_html += '</div>';
								// 			});
								// 		}
								// 	}
								// });

								//Mostrar el detalle
								var formDataDetalle = new FormData();
								formDataDetalle.append('accion', 'get_ListaCabeceraGestionDocumentariaDetalles');
								formDataDetalle.append('id_proceso', id_proceso);

								$.ajax({
									url: 'apis/backend.php',
									type: 'POST',
									data: formDataDetalle,
									contentType: false,
									processData: false,
								 	async: false, 
									success: function(data2) {
										if (data2.estado == 1) {
											$.each( data2.res, function( key, val) {

											var id_detalle = val.Id;


											_html += '<br>';
											_html += '<table class="table table-bordered table-hover">';
											_html += '		<thead>';

											_html += '			<th colspan="'+val.total_tipo_dato+'"  style="text-align: center; border: solid; border-width: 1px; background-color: #6e6d6d; border-color: #ffffff; color: #ffffff; vertical-align: middle; ">';
											_html += val.descripcion;
											_html += '			</th>';

											//Mostrar el Tipo de Dato

											var formDataTipo = new FormData();
											formDataTipo.append('accion', 'get_ListaCabeceraGestionDocumentariaTipoValor');
											formDataTipo.append('id_detalle', id_detalle);
											formDataTipo.append('id_cliente', _id_cliente);

											var array_valor_tipo_dato = "";

											$.ajax({
												url: 'apis/backend.php',
												type: 'POST',
												data: formDataTipo,
												contentType: false,
												processData: false,
											 	async: false, 
												success: function(data3) {
													if (data3.estado == 1) {
														_html += '<tr>';
														$.each( data3.res, function( key, val) {


														_html += '	<th style="text-align: center; border: solid; border-width: 1px; background-color: lightgray; border-color: #ffffff; color: #000; vertical-align: middle; ">';
														_html += val.etiqueta;

														array_valor_tipo_dato += val.input_value +"$";

														_html += '	</th>';

														
														});
														_html += '</tr>';
													}
												}
											});

											_html += '		</thead>';

									    array_valor_tipo_dato_x = array_valor_tipo_dato.substring(0, array_valor_tipo_dato.length-1);

											_html += '		<tbody>';

											if(array_valor_tipo_dato_x != ""){
												res_arr_tipo_dato = array_valor_tipo_dato_x.split("$");

												_html += '<tr style="font-size: 14px;">';
													for (var i=0; i<res_arr_tipo_dato.length; i++)
													{
														_html += '<td>';
														_html += res_arr_tipo_dato[i];
														_html += '</td>';
													}
												_html += '</tr>';

											}

											_html += '		</tbody>';


											_html += '	</table>';

											});
										}
									}
								});

								_html += '</div>';
								_html += '<br>';

							});



						}
					}
				});

				$("#div_reporte").html(_html);
								
			}

			function f_ReporteGestionDocumentariaVertical(_id_cliente, _documento_cliente, _razon_social_cliente){
				
				var _html = '';
				var _html_d = '';


				$("#lbl_cliente_razon_social").html("");
				$("#lbl_cliente_documento").html("");

				f_OpenModal('modal_reporteGestionDocumentaria');

				$("#lbl_cliente_razon_social").html(_razon_social_cliente);
				$("#lbl_cliente_documento").html(_documento_cliente+" - ");


				var id_origendato = $("#hd_id_origendato").val();

				$("#div_reporte").html('');

				var formDataProceso = new FormData();
				formDataProceso.append('accion', 'get_ListaControlInternoProcesos');
				formDataProceso.append('id_origendato', id_origendato);


				_html += '<h5>'+_documento_cliente+' - '+_razon_social_cliente+'</h5>';
				_html += '<hr>';

				$.ajax({
					url: 'apis/backend.php',
					type: 'POST',
					data: formDataProceso,
					contentType: false,
					processData: false,
				 	async: false, 
					success: function(data) {
						if (data.estado == 1) {
							$.each( data.res, function( key, val) {

								var id_proceso = val.Id;

								_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px;">';

								_html += '<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 5px;text-align: center; background: #000; color: white">';
								_html += '<h6>'+val.descripcion+'</h6>';
								_html += '</div>';

							

								//Mostrar el detalle
								var formDataDetalle = new FormData();
								formDataDetalle.append('accion', 'get_ListaCabeceraGestionDocumentariaDetalles');
								formDataDetalle.append('id_proceso', id_proceso);

								$.ajax({
									url: 'apis/backend.php',
									type: 'POST',
									data: formDataDetalle,
									contentType: false,
									processData: false,
								 	async: false, 
									success: function(data2) {
										if (data2.estado == 1) {
											$.each( data2.res, function( key, val) {

											var id_detalle = val.Id;

											_html += '<br>';

											_html += '<div  style="border-radius: 7px; text-align: center; border: solid; border-width: 1px; background-color: #6e6d6d; border-color: #ffffff; color: #ffffff; vertical-align: middle; ">';
											_html += '<h6>'+val.descripcion+'</h6>';
											_html += '</div>';

											//Mostrar el Tipo de Dato

											var formDataTipo = new FormData();
											formDataTipo.append('accion', 'get_ListaCabeceraGestionDocumentariaTipoValor');
											formDataTipo.append('id_detalle', id_detalle);
											formDataTipo.append('id_cliente', _id_cliente);

											var array_valor_tipo_dato = "";

											$.ajax({
												url: 'apis/backend.php',
												type: 'POST',
												data: formDataTipo,
												contentType: false,
												processData: false,
											 	async: false, 
												success: function(data3) {
													if (data3.estado == 1) {
														$.each( data3.res, function( key, val) {

														var codigo_tipo_dato = val.cod_tipo_dato;

														if(val.cod_tipo_dato == 4){
															valor_tipo_dato = val.input_value == '1' ? 'Si' : 'No';
														}else{
															valor_tipo_dato = val.input_value;
														}

														_html += '<br>';

														_html += '<div  style="border-radius: 7px; text-align: center; border: solid; border-width: 1px; border-color: lightgray; color: #000; vertical-align: middle; ">';
														_html += '<h6>'+val.etiqueta+'</h6>';
														_html += '<h6>'+valor_tipo_dato+'</h6>';
														_html += '</div>';
														
														});
													}
												}
											});


											});
										}
									}
								});

								_html += '</div>';
								_html += '<br>';

							});



						}
					}
				});

				$("#div_reporte").html(_html);
								
			}

			function f_TotalLoadGestionDocumentaria(_id_cliente){
				$.post( "apis/backend.php", { accion: "get_TotalControlInternoGestionDocumentariaDetalle", id_cliente: _id_cliente}, 
					function( data ) {
						if(data.estado == 1){
				
							$('#lbl_inicio_total_cliente_'+_id_cliente).html(data.total_gestion_documentaria_cliente);

							var porcentaje_cliente = 0;
							var contar_si = data.total_gestion_documentaria_cliente;
							var contar_total = $('#lbl_fin_total_cliente_'+_id_cliente).text();

							//Calcular el porcentaje de avance
								porcentaje_cliente = (contar_si * 100) / parseInt(contar_total);

								$('#porcentaje_cliente_'+_id_cliente).html(
									'<div class="progress">'+
									'<div class="progress-bar" role="progressbar" style="width: '+porcentaje_cliente+'%;" aria-valuenow="'+porcentaje_cliente+'" aria-valuemin="0" aria-valuemax="100"></div>'+
									'</div>'+
									'<label style="font-size: 13px">'+porcentaje_cliente.toFixed(0)+'%</label>'
								);


							//Para mostrar en la tabla EXCEL
								$('#lbl_inicio_total_cliente_excel_'+_id_cliente).html(data.total_gestion_documentaria_cliente);

								var porcentaje_excel_cliente = 0;
								var contar_excel_si = data.total_gestion_documentaria_cliente;
								var contar_excel_total = $('#lbl_fin_total_cliente_'+_id_cliente).text();

								//Calcular el porcentaje de avance
									porcentaje_excel_cliente = (contar_excel_si * 100) / parseInt(contar_excel_total);

									$('#porcentaje_cliente_excel_'+_id_cliente).html(
										'<div class="progress">'+
										'<div class="progress-bar" role="progressbar" style="width: '+porcentaje_excel_cliente+'%;" aria-valuenow="'+porcentaje_excel_cliente+'" aria-valuemin="0" aria-valuemax="100"></div>'+
										'</div>'+
										'<label style="font-size: 13px">'+porcentaje_excel_cliente.toFixed(0)+'%</label>'
									);

						}
						else{
							// alert("No se encontraron resultados.");
						}

					f_LoadingResumen(0);

				}, "json");

				

			};

		

		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');
				}
			}

			$(document).on("click", ".print", function () {
		    $(".modal-backdrop").css('background','transparent');

			  const section = $("section");
			  const modalBody = $(".modal-body-print").detach();
			  const content = $("#content").detach();

			  section.append(modalBody);
			  window.print();
			  section.empty();
			  section.append(content);
			  $("#modal-body-print-view").append(modalBody);
		    $("#modal-body-print-view").css('overflow-y','scroll');
		    $("#modal-body-print-view").css('height','100vh');
       	$(".modal-backdrop").css('background','');
			});


		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">

		</script>
	</div>

</section>
</body>
</html>