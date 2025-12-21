<?php

	session_start();

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

		<title><?php echo $nom_app; ?> | Facturación Electrónica</title>

		<!-- Desactivar las flechas de Arriba y Abajo de los input (number) -->
    <style type="text/css">
      /*Chrome*/
      input[type="number"]::-webkit-outer-spin-button,
      input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      /*Firefox*/
      input[type="number"] {
      -moz-appearance: textfield;
      }
      input[type="number"]:hover,
      input[type="number"]:focus {
        -moz-appearance: number-input;
      }
      /*Other*/
      input[type=number]::-webkit-inner-spin-button,
      input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
    </style>

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

				<div class="col-md-11 col-sm-11 col-xs-11" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
					<div class="d-flex row">
						<iframe	src="facturador/?u=<?php echo $_SESSION["usu_usuario"]; ?>" width="100%" height="1200px;">
						</iframe>
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
					$("#nv_titulo").html('| Módulo de Facturación Electrónica');

				// Generar Id de Recepcion Preliminar
					f_GetIdRecepcion();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_GetIdRecepcion(){
				$.post( "apis/backend.php", { accion: "get_IdRecepcion" }, 
					function( data ) {
						if(data.estado == 1){
							id_recepcion = data.id_recepcion;
							id_md5 = data.id_md5;

							// Obtiene los datos pre grabados para el caso de una recepción temporal
								$.post( "apis/backend.php", { accion: "get_RecepcionTemporal", id_recepcion: id_recepcion }, 
									function( data ) {
										if(data.estado == 1){
											$.each( data.res, function( key, val ) {
												cab_codinternopreliminar_x = ((val.cod_interno_preliminar == null) ? '' : val.cod_interno_preliminar);
												cab_fecharecepcion_x = ((val.fechahora_recepcion != null) ? val.fechahora_recepcion.substring(0, 10) : '<?php echo $g_date; ?>');
												cab_horarecepcion_x = ((val.fechahora_recepcion != null) ? val.fechahora_recepcion.substring(16, 11) : '<?php echo substr($g_time, 0, 5); ?>');
												cab_fechaentrega_x = ((val.fechahora_entrega != null) ? val.fechahora_entrega.substring(0, 10) : '');
												cab_horaentrega_x = ((val.fechahora_entrega != null) ? val.fechahora_entrega.substring(16, 11) : '');
												cab_clientedocumento_x = ((val.cliente_documento == null) ? '' : val.cliente_documento);
												cab_clienterazonsocial_x = ((val.cliente_razonsocial == null) ? '' : val.cliente_razonsocial);
												cab_entregadopor_x = ((val.entregado_por == null) ? '' : val.entregado_por);
												cab_celularareportar_x = ((val.celular_areportar == null) ? '' : val.celular_areportar);
												cab_sucursal_x = ((val.cod_sucursal == null) ? '' : val.cod_sucursal);
												cab_moneda_x = ((val.cod_moneda == null) ? '' : val.cod_moneda);
												cab_observacion_x = ((val.observacion == null) ? '' : val.observacion);
												cab_iscabecerasaved = ((val.is_cabecerasaved == null) ? '' : val.is_cabecerasaved);

												// Actualizando campos input
													$("#cab_codigo").val(cab_codinternopreliminar_x);
													$("#cab_fecharecepcion").val(cab_fecharecepcion_x);
													$("#cab_horarecepcion").val(cab_horarecepcion_x);
													$("#cab_fechaentrega").val(cab_fechaentrega_x);
													$("#cab_horaentrega").val(cab_horaentrega_x);
													$("#cab_clientedocumento").val(cab_clientedocumento_x);
													$("#cab_clienterazonsocial").val(cab_clienterazonsocial_x);
													$("#cab_entregadopor").val(cab_entregadopor_x);
													$("#cab_celularareportar").val(cab_celularareportar_x);
													$("#cab_observacion").val(cab_observacion_x);

												// Si es Cabecera grabada deshabilitar los objetos
													if (cab_iscabecerasaved == 1){
														f_DisableObjCabecera(1);

														// Si se grabó la cabecera debe llamar al detalle
															f_getRecepcionDetalle();
													}

												// Actualiza en la tabla los datos (para cuando se actualiza la fecha y hora de entrega al momento de agregar muestras)
													f_GrabarCabeceraRecepcion_Temporal();

											});
										}
										else{
											alert("Ocurrió un error al momento de generar el Id de la recepción.");
										}

										// Asigan correlativo
											f_GetCodigoInterno();

										// Cargando listas para Cabecera
											f_GetListaSucursales();
											f_GetListaMonedas();
											f_GetListaTipoCliente();
											f_GetListaTipoDocumento(1);

										// Cargando listas para Detalles de Muestras
											f_GetListaEstadosMuestra();
											f_GetListaEnvasesMuestra();
											f_GetListaTipoMuestra();
											f_GetListaEnsayosMuestra();

										// Cargando Clasificaciones para Análisis
											f_GetListaAnalisisClasificaciones();

										// Cargando Medio de Pago para la Instrcción
											f_GetListaMediosDePago();

									}, "json");
						}
						else{
							alert("Ocurrió un error al momento de generar el Id de la recepción.");
						}

					}, "json");
			}

			function f_GetCodigoInterno(){
				$.post( "apis/backend.php", { accion: "get_CodigoInterno", is_ensayo: 1, is_preliminar: 1, id_recepcion: id_recepcion }, 
					function( data ) {
						if(data.estado == 1){
							$("#cab_codigo").val(data.correlativo);
						}
						else{
							alert("Ocurrió un error al momento de obtener el código de recepción.");
						}

					}, "json");
			}

			function f_BuscarClientes(){
				var documento = $("#cab_clientedocumento").val();

				$("#cab_clienterazonsocial").val('');
				$("#cab_celularareportar").val('');
				$("#wt_razonsocial1").hide();

				if (documento.length == 8 || documento.length == 11){
					$("#wt_razonsocial1").show();

					$.post( "apis/backend.php", { accion: "get_RecepcionCliente", documento: documento }, 
						function( data ) {
							if(data.estado == 1){
								$("#cab_clienterazonsocial").val(data.res);
								$("#cab_celularareportar").val(data.tel);

								cab_clientedocumento_x = documento;
							}
							else{
								$("#cab_clienterazonsocial").val('NO ENCONTRADO');
							}

							$("#wt_razonsocial1").hide();

						}, "json");
				}
			}

			function f_getRecepcionDetalle(){
				$("#tbl_detallemuestras").html('');
				$("#det_nummuestras").val(1);

				// Obtiene el detalle de Muestras de la recepción, si aun no tuviera detalle agrega por defecto la muestra 001.
					$.post( "apis/backend.php", { accion: "get_RecepcionDetalle", id_recepcion: id_recepcion }, 
						function( data ) {
							if(data.estado == 1){
								$("#tbl_detallemuestras").html(data.html);

								f_LoadAnalisisMuestra(1);
							}
							else{
								alert("Ocurrió un error al momento de obtener el detalle de la recepción.");
							}

						}, "json");
			}

			function f_LoadAnalisisMuestra(_item){
				item_selected = _item;
				idmuestra_selected = $("#id_detalle_x_" + _item).val();
				muestra_selected = $("#td_detalle_3_" + _item).html().trim();

				// Colocando título
					$("#detalle_titulo").html(muestra_selected);

				// Pinta selección
          f_ColorSelected(_item);

				// Obtiene el detalle de la muestra seleccionada
          $("#wt_detalle_infomuestra2").show();

					$.post( "apis/backend.php", { accion: "get_RecepcionDetalle_Temporal", id_detalle: idmuestra_selected }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									det_nombremuestra_x = ((val.nombre_muestra == null) ? '' : val.nombre_muestra);
									det_pesomuestra_x = ((val.peso_muestra == null) ? '' : val.peso_muestra);
									det_estadomuestra_x = ((val.cod_estadomuestra == null) ? '' : val.cod_estadomuestra);
									det_envasemuestra_x = ((val.cod_envasemuestra == null) ? '' : val.cod_envasemuestra);
									det_tipomuestra_x = ((val.cod_tipomuestra == null) ? '' : val.cod_tipomuestra);
									det_ensayomuestra_x = ((val.cod_ensayomuestra == null) ? '' : val.cod_ensayomuestra);
									det_excesomuestra_x = ((val.exceso == null) ? '' : val.exceso);
									det_observacion_x = ((val.observacion == null) ? '' : val.observacion);

									// Actualizando campos de registro
										$("#det_nombremuestra").val(det_nombremuestra_x);
										$("#det_pesomuestra").val(det_pesomuestra_x);
										$("#det_estadomuestra").val(det_estadomuestra_x);
										$("#det_envasemuestra").val(det_envasemuestra_x);
										$("#det_tipomuestra").val(det_tipomuestra_x);
										$("#det_ensayomuestra").val(((det_ensayomuestra_x.length == 0) ? 1 : det_ensayomuestra_x));
										$("#det_excesomuestra").val(det_excesomuestra_x);
										$("#det_observacion").val(det_observacion_x);

									// Obtiene detalle de análisis
										f_LoadDetalleAnalisis();

								});
							}
							else{
								alert("Ocurrió un error al momento de obtener la información de la muestra seleccionada.");
							}

							$("#wt_detalle_infomuestra2").hide();

						}, "json");
			}

			function f_GetListaAnalisisClasificaciones(){
				// Carga clasificaciones
					var _html = '';
					var c = 1;

					$.post( "apis/backend.php", { accion: "get_ListaAnalisisClasificaciones", cod_cliente: cab_clientedocumento_x, filtro_precios: filtro_precios_x }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<div id="div_clasificacion_' + c + '" class="flex-fill" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; padding: 8px; margin-right: 10px; background-color: ' + ((c == 1) ? '#FFF587' : '') + '; padding-left: 15px; padding-right: 15px; font-weight: bold; margin-bottom: 5px; font-size: 14px; cursor: pointer;" onclick="f_GetListaAnalisis(' + c + ')">';
									_html += '	' + val.descripcion;
									_html += '  <input id="id_analisisclasificacion_' + c + '" type="hidden" value="' + val.Id + '">';
									_html += '  <input id="des_analisisclasificacion_' + c + '" type="hidden" value="' + val.descripcion + '">';
									_html += '</div>';

									c ++;
								});
							}
							else{
								$("#div_analisis").html('');
							}

							$("#div_analisisclasificaciones").html(_html);

							f_GetListaAnalisis(1);

						}, "json");
			}

			function f_AddAnalisis(){
				// Coloca el título en la ventana
					$("#titulo_analisis").html(muestra_selected);

				// Coloca por defecto el filtro de Precios Generales
					filtro_precios_x = 1;

				// Selecciona el Fitro de Precios Generales por defecto
					f_SelectedPrecios(1);

				// Actualiza el html de análisis
					if ($("#td_x").html() == undefined){
						$("#tbl_detalleanalisis_add").html($("#tbl_detalleanalisis").html().replace(/id="tr_subtotal"/g, 'id="tr_subtotal_add"').replace(/"deltr_detalleanalisis"/g, '"deltr_detalleanalisis_add"').replace(/id="td_x"/g, 'id="td_x_add"'));
					}
					else{
						$("#tbl_detalleanalisis_add").html('');
					}

				f_GetListaAnalisis(1);

				// Setea la moneda en el campo de Total
				$("#lbl_monedaanalisis_add").html($("#cab_moneda option:selected").text());
			}

			function f_GetListaAnalisis(_item){
				f_ClasificacionSelected(_item);

				// Carga clasificaciones
					var _html = '';
					var a = 1;
					var id_clasificacion = $("#id_analisisclasificacion_" + _item).val();
					var des_clasificacion = $("#des_analisisclasificacion_" + _item).val().trim().toUpperCase();

					$.post( "apis/backend.php", { accion: "get_ListaAnalisis", cod_cliente: cab_clientedocumento_x, filtro_precios: filtro_precios_x, id_clasificacion: id_clasificacion }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<div id="div_analisis_' + a + '" class="flex-fill" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; padding: 8px; margin-right: 10px; background-color: #00a2aa; padding-left: 15px; padding-right: 15px; font-weight: bold; margin-bottom: 5px; font-size: 14px; cursor: pointer;" onclick="f_SetAnalisis(' + val.Id + ", '(" + val.abv + ") - " + val.descripcion + "', '" + des_clasificacion + "', '" + val.MONEDA + "', " + val.precio + ', ' + ((filtro_precios_x == 2) ? 1 : 0) + ', ' + ((filtro_precios_x == 3) ? 1 : 0) + ')">';
									_html += '	<input id="div_listaanalisis_' + a + '" type="hidden" value="' + val.Id + ((filtro_precios_x == 2) ? '1' : '0') + ((filtro_precios_x == 3) ? '1' : '0') + '">';
									_html += '	<label style="color: #ffffff; cursor: pointer; font-size: 15px;">' + val.abv + '</label><br><label style="font-size: 12px; margin-top: -5px; cursor: pointer; color: #12537e;">' + val.descripcion + '</label><br><label style="color: #FFDB17; margin-top: -5px; cursor: pointer;">' + val.MONEDA + ' ' + val.precio + '</label>';
									_html += '</div>';

									a ++;
								});
							}
							else{
								_html += '<label style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #F25050; color: #ffffff; width: 100%;">';
								_html += '	No se encontraron análisis configurados para la clasificación seleccionada.';
								_html += '</label>';
							}

							$("#div_analisis").html(_html);

							// Calcular Subtotal
								f_CalculaSubtotal(1);

							// Deshabilita Análisis seleccionados
								f_DisabledAnalisis(1);

						}, "json");
			}

			function f_SetAnalisis(_id_analisis, _analisis, _clasificacion, _moneda, _total, _is_paquete, _is_paquetecliente){
				// Obtener el número de filas actuales
					var _rows = $("#tbl_detalleanalisis_add tr").length;

				// Setea Fila
					var _tr = '<tr style="font-size: 14px;">';
					_tr += '  <td class="deltr_detalleanalisis_add" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 40px;">';
          _tr += '    <button class="btn btn-danger" type="button" style="color: #ffffff;">';
          _tr += '      x';
          _tr += '    </button>';
          _tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;">';
					_tr += '  	 ' + _rows;
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;">';
					_tr += '  	 ' + _clasificacion;
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;" hidden>' + _id_analisis + '</td>';
					_tr += '  <td style="vertical-align: middle;">';
					_tr += '  	 ' + _analisis;
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;">';
					_tr += '  	 ' + _total;
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;" hidden>' + _is_paquete + '</td>';
					_tr += '  <td style="vertical-align: middle;" hidden>' + _is_paquetecliente + '</td>';
					_tr += '</tr>';

					$("#tbl_detalleanalisis_add").append(_tr);

				// Calcular Subtotal
					f_CalculaSubtotal(1);

				// Deshabilita Análisis seleccionados
					f_DisabledAnalisis(1);
			}

			function f_LoadDetalleAnalisis(){
				var _html = '';
				var a = 1;

				// Coloca título de moneda
					$("#lbl_monedaanalisis").html($("#cab_moneda option:selected").text());

				// Cargando detalle de análisis
					$("#tbl_detalleanalisis").html('');

					$.post( "apis/backend.php", { accion: "get_DetalleAnalisis", id_detalle: idmuestra_selected }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									// Agrega filas
										_html = '<tr style="font-size: 14px;">';
										_html += '  <td class="deltr_detalleanalisis" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 40px;">';
					          _html += '    <button class="btn btn-danger" type="button" style="color: #ffffff;">';
					          _html += '      x';
					          _html += '    </button>';
					          _html += '  </td>';
										_html += '  <td style="vertical-align: middle;">';
										_html += '  	 ' + a;
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle;">';
										_html += '  	 ' + val.CLASIFICACION;
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle;" hidden>' + val.cod_analisis + '</td>';
										_html += '  <td style="vertical-align: middle;">';
										_html += '  	 ' + val.ANALISIS;
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle;">';
										_html += '  	 ' + val.total;
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle;" hidden>' + val.is_paquete + '</td>';
										_html += '  <td style="vertical-align: middle;" hidden>' + val.is_paquetecliente + '</td>';
										_html += '</tr>';

										$("#tbl_detalleanalisis").append(_html);

									// Calcular Subtotal
										f_CalculaSubtotal(0);

									a ++;
								});
							}
							else{
								_html = '<tr style="font-size: 14px; background-color: #F25050;">';
								_html += '  <td id="td_x" colspan="6" style="vertical-align: middle; color: #ffffff;">';
								_html += '  	 Aún no se han registrado análisis para la muestra seleccionada.';
								_html += '  </td>';
								_html += '</tr>';

								$("#tbl_detalleanalisis").html(_html);
							}

						}, "json");
			}

			function f_Replicarmuestras(){
				$("#titulo_replicar").html(muestra_selected);

				$("#chk_MuestrasReplicaSelectAll").prop('checked', true);

				// Recorre la lista de muestras para replicar
					var _tr = '';
					var r = 1;
					var x = 1;

					$("#tbl_muestrasreplica").html('');

					$('#tbl_detallemuestras tr').each(function () {
						if (idmuestra_selected != $("#id_detalle_x_" + r).val()){
							_tr = '<tr id="tr_itemreplica_' + x + '" style="background-color: #FFF587; font-size: 14px;" onclick="f_SelectedMuestraReplica(' + x + ');">';
							_tr += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; width: 40px;">';
							_tr += '  	' + x;
							_tr += '  	<input id="id_replica_x_' + x + '" type="hidden" value="' + $("#id_detalle_x_" + r).val() + '">';
							_tr += '  </td>';
							_tr += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; cursor: pointer;">';
							_tr += '  	 ' + $(this).find("td").eq(2).html();
							_tr += '  </td>';
							_tr += '</tr>';

							$("#tbl_muestrasreplica").append(_tr);

							x ++;
						}

						r ++;
	        });
			}

			function f_ImprimirRecibo(_is_prelimiar){
				var url = 'print_recibo_ensayos.php?x=' + id_md5 + '&p=' + _is_prelimiar;

				window.open(url,'_blank',"");
			}

			function f_GenerarInstruccion(_is_instruccion){
				if (_is_instruccion == 0){
					// Grabar Preventa
						f_LoadingGenerarInstruccion(1);

						$.post( "apis/backend.php", { accion: "grabar_PreVentaSinInstruccion", id_recepcion: id_recepcion, cod_sucursal: cab_sucursal_x }, 
							function( data ) {
								if(data.estado == 1){
									window.open('recepcion_ensayos.php', '_self');
								}
								else{
									alert("Ocurrió un error al momento de grabar la recepción.");
								}

								f_LoadingGenerarInstruccion(0);

							}, "json");
				}
				else{
					f_cerrarModal('modal_confirmarrecepcion');

					// Setea la moneda
						$("#ins_moneda_1").html($("#cab_moneda option:selected").text());
						$("#ins_moneda_2").html($("#cab_moneda option:selected").text());
						$("#ins_moneda_3").html($("#cab_moneda option:selected").text());

					// Obtiene el Total de la Venta
						$.post( "apis/backend.php", { accion: "get_TotalRecepcion", id_recepcion: id_recepcion }, 
						function( data ) {
							if(data.estado == 1){
								$("#ins_totalventa").html(parseFloat(data.res).toFixed(2));
							}
							else{
								alert("Ocurrió un error al momento de obtener el Total de la Venta.");
							}

						}, "json");

					f_OpenModal('modal_instruccion');
				}
			}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			// Lista de Sucursales
				function f_GetListaSucursales(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listasucursales", is_recepcion: 1 }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '" ' + ((cab_sucursal_x.length > 0) ? ((cab_sucursal_x == val.Id) ? 'selected' : '') : ((<?php echo $_SESSION["cod_sucursal"] ?> == val.Id) ? 'selected' : '')) + '>' + val.DESCRIPCION + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#cab_sucursal").html(_html);
							}
							else{
								$("#cab_sucursal").html('');
							}

						}, "json");
				}

			// Lista de Monedas
				function f_GetListaMonedas(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listamonedas" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '" ' + ((cab_moneda_x.length > 0) ? ((cab_moneda_x == val.Id) ? 'selected' : '') : ((val.is_default == 1) ? 'selected' : '')) + '>' + val.DESCRIPCION + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#cab_moneda").html(_html);
							}
							else{
								$("#cab_moneda").html('');
							}

						}, "json");
				}

			// Tipo de Cliente
				function f_GetListaTipoCliente(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listatipocliente" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '" ' + ((val.is_default == 1) ? 'selected' : '') + '>' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#cliente_tipocliente").html(_html);
							}
							else{
								$("#cliente_tipocliente").html('');
							}

						}, "json");
				}

			// Tipo de Documento del Cliente
				function f_GetListaTipoDocumento(_is_juridico){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					if (_is_juridico == 0){
						if ($("#cliente_tipocliente").val() == 2){
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

								$("#cliente_tipodocumento").html(_html);
							}
							else{
								$("#cliente_tipodocumento").html('');
							}

						}, "json");
				}

			// Obtener información del Cliente
				function f_GetInfoCliente(){
					var is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
					var documento = $("#cliente_documento").val();

					// Limpiando objetos
						$("#cliente_razonsocial").val('');
          	$("#cliente_direccion").val('');
						$("#wt_razonsocial2").hide();

					// Obteniendo información
						if (documento.length == 8 || documento.length == 11){
							$("#wt_razonsocial2").show();

							$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
		            function( data ) {
		            	if (is_ruc == 1){
		            		$("#cliente_razonsocial").val(data.denominacion.trim());
		              	$("#cliente_direccion").val(data.direccion.trim());
		            	}
		            	else{
		            		$("#cliente_razonsocial").val(data.nombre.trim());
		              	$("#cliente_direccion").val('');
		            	}

		            	$("#wt_razonsocial2").hide();

		            }, "json");
						}
				}

			// Lista de Clientes para búsqueda
				function f_GetListaBuscarClientes(){
					var _html = '';

					$.post( "apis/backend.php", { accion: "get_listabuscarclientes" }, 
						function( data ) {
							if(data.estado == 1){
								$("#tbl_buscarcliente").html(data.html);
							}
							else{
								$("#tbl_buscarcliente").html('');
							}

						}, "json");
				}

			// Seleccionar Cliente
				function f_SelectCliente(_item){
					$("#cab_clientedocumento").val($("#td_buscarcliente_item_2_" + _item).html().trim());
					$("#cab_clienterazonsocial").val($("#td_buscarcliente_item_3_" + _item).html().trim());

					f_cerrarModal("modal_buscarcliente");
				}

			// Loading de Grabación de Cabecera preliminar
				function f_LoadingGrabarCabeceraPreliminar(_is_show){
					if (_is_show == 1){
						$("#wt_cabecerarecepcion").show();

						$("#btn_grabarcabecera").prop('disabled', true);
						$("#btn_grabarcabecera").css('background-color', '#C2C0A6')
					}
					else{
						$("#wt_cabecerarecepcion").hide();

						$("#btn_grabarcabecera").prop('disabled', false);
						$("#btn_grabarcabecera").css('background-color', '')
					}
				}

			// Deshabilita los objetos de Cabecera
				function f_DisableObjCabecera(_is_disabled){
					if (_is_disabled == 1){
						$(".obj_cab").prop('disabled', true);
						$(".obj_cab_img").hide();

						$("#div_grabarcabecera_prev").attr("style", "display: none !important");

						$("#div_grabarcabecera").attr("style", "display: flex !important");
						$("#div_grabarcabecera").attr("style", "padding-bottom: 5px; !important");
						$("#div_grabarcabecera").attr("style", "padding: 15px; !important");

						$("#div_detalle_prev").hide();
						$("#div_detalle").show();
					}
					else{
						$(".obj_cab").prop('disabled', false);
						$(".obj_cab_img").show();

						$("#div_grabarcabecera_prev").attr("style", "display: flex !important");
						$("#div_grabarcabecera_prev").attr("style", "padding-bottom: 5px; !important");
						$("#div_grabarcabecera_prev").attr("style", "padding: 15px; !important");

						$("#div_grabarcabecera").attr("style", "display: none !important");

						$("#div_detalle_prev").show();
						$("#div_detalle").hide();
					}
				}

			// Detecta el enter al agregar más muestras
				$("#det_nummuestras").keyup(function (e) {
			    if (e.keyCode === 13) {
			       f_Addmuestras();
			    }
			  });

			// Detecta el enter al eliminar muestras
				$("#det_nummuestras_eliminar").keyup(function (e) {
			    if (e.keyCode === 13) {
			       f_Delmuestras();
			    }
			  });

			// Lista de Estados de Muestra
				function f_GetListaEstadosMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listaestadosmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_estadomuestra").html(_html);
							}
							else{
								$("#det_estadomuestra").html('');
							}

						}, "json");
				}

			// Lista de Envases de Muestra
				function f_GetListaEnvasesMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listaenvasesmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_envasemuestra").html(_html);
							}
							else{
								$("#det_envasemuestra").html('');
							}

						}, "json");
				}

			// Lista de Tipos de Muestra
				function f_GetListaTipoMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listatiposmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_tipomuestra").html(_html);
							}
							else{
								$("#det_tipomuestra").html('');
							}

						}, "json");
				}

			// Lista de Tipos de Análisis (Ensayos)
				function f_GetListaEnsayosMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listaensayosmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option ' + ((val.Id == 1) ? 'selected' : '') + ' value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_ensayomuestra").html(_html);
							}
							else{
								$("#det_ensayomuestra").html('');
							}

						}, "json");
				}

			// Loading de Grabación de Detalle preliminar
				function f_LoadingGrabarDetallePreliminar(_is_show){
					if (_is_show == 1){
						$("#wt_detalle_infomuestra").show();

						$("#btn_GrabarRecepcion").prop('disabled', true);
						$("#btn_GrabarRecepcion").css('background-color', '#C2C0A6');

						$("#btn_CancelarRecepcion").prop('disabled', true);
						$("#btn_CancelarRecepcion").css('background-color', '#C2C0A6');

						$("#btn_RegresarRecepcion").prop('disabled', true);
						$("#btn_RegresarRecepcion").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_detalle_infomuestra").hide();

						$("#btn_GrabarRecepcion").prop('disabled', false);
						$("#btn_GrabarRecepcion").css('background-color', '');

						$("#btn_CancelarRecepcion").prop('disabled', false);
						$("#btn_CancelarRecepcion").css('background-color', '');

						$("#btn_RegresarRecepcion").prop('disabled', false);
						$("#btn_RegresarRecepcion").css('background-color', '');
					}
				}

    	// Pinta la muestra seleccionada
	    	function f_ColorSelected(_item){
	        var i = 1;

	        // Recorre los Tr de la tabla y los limpia
	        $("#tbl_detallemuestras tr").each(function () {
						if ($("#tr_item_" + i).css('background-color') != 'rgb(242, 162, 162)'){
							$("#tr_item_" + i).css('background-color', '');
						}

	          i ++;
	        });

	        // Seteando item seleccionado
	        	if ($("#tr_item_" + _item).css('background-color') != 'rgb(242, 162, 162)'){
	          	$("#tr_item_" + _item).css('background-color', '#FFF587');
	          }
	    	}

    	// Pinta el análisis seleccionado
	    	function f_ClasificacionSelected(_item){
	        var i = 1;
	        var _obj = '';

	        // Recorre los Tr de la tabla y los limpia
		        while (i < 100){
		        	_obj = $("#div_clasificacion_" + i);

							if (_obj.html() == undefined){
								break;
							}

		          _obj.css('background-color', '');

		          i ++;
		        }

	        // Seteando item seleccionado
	          $("#div_clasificacion_" + _item).css('background-color', '#FFF587');
	    	}

	    // Quita las filas de la tabla de Análisis de la pantalla de agregar análisis
	    	$(document).on('click', '.deltr_detalleanalisis_add', function (event) {
          event.preventDefault();

          $(this).closest('tr').remove();

          // Obtiene el número de filas actual
          	var _rows = $("#tbl_detalleanalisis_add tr").length + 1;

          // Reconstruye los correlativos
          	if (_rows > 0){
          		var r = 1;

          		$('#tbl_detalleanalisis_add tr').each(function () {
		            $(this).find("td").eq(1).html(r);

		            r ++;
		          });
          	}

          // Recalcula Subtotales
          	f_CalculaSubtotal(1);

          // Deshabilita Análisis seleccionados
						f_DisabledAnalisis(1);
        });

      // Calculla Sub total de análisis
	    	function f_CalculaSubtotal(_is_add){
	    		var _tr_subtotal = '';
    			var _subtotal = 0;
    			var _table = 'tbl_detalleanalisis';

	    		if (_is_add == 1){
	    			_table += '_add';
	    		}

	    		// Eliminar Subtotal previo
    				$("#tr_subtotal" + ((_is_add == 1) ? '_add' : '')).remove();

    			// Agregando Subtotal
		    		$('#' + _table + ' tr').each(function () {
		          _subtotal += parseFloat($(this).find("td").eq(5).html());
		        });

		        _tr_subtotal = '<tr id="tr_subtotal' + ((_is_add == 1) ? '_add' : '') + '" style="background-color: #5b2a68; color: #ffffff; font-size: 14px;">';
						_tr_subtotal += '  <td colspan="4" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; width: 40px; color: #ffffff;">';
						_tr_subtotal += '  	 Total ' + $("#cab_moneda option:selected").text();
						_tr_subtotal += '  </td>';
						_tr_subtotal += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 40px; color: #ffffff;">';
						_tr_subtotal += '  	 ' + parseFloat(_subtotal).toFixed(2);
						_tr_subtotal += '  </td>';
						_tr_subtotal += '</tr>';

						$('#' + _table).append(_tr_subtotal);
				}

			// Desahabilita los análisis que ya fueron seleccionados
				function f_DisabledAnalisis(_is_add){
					// Arma Array de análisis seleccionados
						var idanalisis_selected = '';
						var _table = 'tbl_detalleanalisis';

		    		if (_is_add == 1){
		    			_table += '_add';
		    		}

						$('#' + _table + ' tr').each(function () {
		          idanalisis_selected += $(this).find("td").eq(3).html() + $(this).find("td").eq(6).html() + $(this).find("td").eq(7).html() + '|';
		        });

		        if (idanalisis_selected.length > 0){
		        	idanalisis_selected = idanalisis_selected.substring(0, idanalisis_selected.length - 1);
		        }

		      // Recorre análisis y los muestra
		        var _obj = '';
		        var i = 1;

		        while (i < 1000){
		        	_obj = $("#div_listaanalisis_" + i);

							if (_obj.html() == undefined){
								break;
							}

							$("#div_analisis_" + i).show();

		          i ++;
		        }

		      // Recorre Array y deshabilita análisis
		        var s = 0;
		        var arr_idanalisis = idanalisis_selected.split('|');

		        while (s < arr_idanalisis.length - 1){
		        	// Recorre los análisis y los oculta
		        		i = 1;

				        while (i < 1000){
				        	_obj = $("#div_listaanalisis_" + i);

									if (_obj.html() == undefined){
										break;
									}

				          if (_obj.val() == arr_idanalisis[s]){
				          	$("#div_analisis_" + i).hide();
				          }

				          i ++;
				        }

		        	s ++;
		        }

					// div_listaanalisis_
				}

			// Quita las filas de la tabla de Análisis de la recepción
	    	$(document).on('click', '.deltr_detalleanalisis', function (event) {
          event.preventDefault();

          $(this).closest('tr').remove();

          // Obtiene el número de filas actual
          	var _rows = $("#tbl_detalleanalisis tr").length + 1;

          // Reconstruye los correlativos
          	if (_rows > 0){
          		var r = 1;

          		$('#tbl_detalleanalisis tr').each(function () {
		            $(this).find("td").eq(1).html(r);

		            r ++;
		          });
          	}

          // Graba los análisis
          	var _selected = '';

						// Eliminar Subtotal previo
		  				$("#tr_subtotal").remove();

						// Validando que se haya ingresado al menos 1 análisis
			    		$('#tbl_detalleanalisis tr').each(function () {
			          _selected += $(this).find("td").eq(3).html() + ';' + $(this).find("td").eq(6).html() + ';' + $(this).find("td").eq(7).html() + ';' + parseFloat($(this).find("td").eq(5).html()) + '|';
			        });

			        if (_selected.length > 0){
			        	_selected = _selected.substring(0, _selected.length - 1);
			        }

			      // Grabando análisis
			        $("#wt_detalle_infomuestra").show();

			        $.post( "apis/backend.php", { accion: "grabar_MuestrasAnalisis", id_detalle: idmuestra_selected, analisis_selected: _selected, fecha_recepcion: cab_fecharecepcion_x, hora_recepcion: cab_horarecepcion_x },
								function( data ) {
									if(data.estado == 1){
										// Actualiza la tabla de Análisis
											// f_LoadDetalleAnalisis();
									}

									$("#wt_detalle_infomuestra").hide();

									// Vuelve a jalar los datos de la recepción para refresecar la hora de entrega y actualiza los datos de la cabecera
										f_GetIdRecepcion();

								}, "json");
        });

      // Selecciona las muestras a replicar
	    	function f_SelectedMuestraReplica(_item){
	    		if (_item != 0){
	    			if ($("#tr_itemreplica_" + _item).css('background-color') == 'rgb(255, 245, 135)'){
		    			$("#tr_itemreplica_" + _item).css('background-color', '');
		    		}
		    		else{
		    			$("#tr_itemreplica_" + _item).css('background-color', '#FFF587');
		    		}
	    		}
	    		else{
	    			var r = 1;

	    			$('#tbl_muestrasreplica tr').each(function () {
	    				if ($("#chk_MuestrasReplicaSelectAll").prop('checked')){
	    					$("#tr_itemreplica_" + r).css('background-color', '#FFF587');
	    				}
	    				else{
	    					$("#tr_itemreplica_" + r).css('background-color', '');
	    				}

	            r ++;
	          });
	    		}
	    	}

	    // Lista de Medios de Pago
	    	function f_GetListaMediosDePago(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listamediospago" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '|' + val.is_efectivo + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#ins_mediospago").html(_html);
							}
							else{
								$("#ins_mediospago").html('');
							}

						}, "json");
				}

			// Si se selecciona el Tipo de Pago efectivo se mostrarán los billetes
				function f_ShowEfectivo(){
					var medio_pago = $("#ins_mediospago").val().substring(0, $("#ins_mediospago").val().indexOf('|'));
          var is_efectivo = $("#ins_mediospago").val().substring($("#ins_mediospago").val().indexOf('|') + 1);

          if (is_efectivo == 1){
            $(".ins_divefectivo").show();
	        }
	        else{
            $(".ins_divefectivo").hide();
        	}
				}

			// Suma la selección de billetes y monedas
				function f_SumaBilletes(_billete){
	        $("#total_billete" + _billete).val(parseFloat($("#total_billete" + _billete).val()) + 1);

	        f_CalcularEfectivo();
	      };

	    // Calcular el total de efectivo seleccionado
      	function f_CalcularEfectivo(){
          var total_billete200 = parseFloat($("#total_billete200").val()) * 200;
          var total_billete100 = parseFloat($("#total_billete100").val()) * 100;
          var total_billete50 = parseFloat($("#total_billete50").val()) * 50;
          var total_billete20 = parseFloat($("#total_billete20").val()) * 20;
          var total_billete10 = parseFloat($("#total_billete10").val()) * 10;

          var total_billete5 = parseFloat($("#total_billete5").val()) * 5;
          var total_billete2 = parseFloat($("#total_billete2").val()) * 2;
          var total_billete1 = parseFloat($("#total_billete1").val()) * 1;
          var total_billete50cen = parseFloat($("#total_billete50cen").val()) * 0.5;
          var total_billete20cen = parseFloat($("#total_billete20cen").val()) * 0.2;
          var total_billete10cen = parseFloat($("#total_billete10cen").val()) * 0.1;

          var total_efectivo = parseFloat(total_billete200 +
	                                        total_billete100 +
	                                        total_billete50 +
	                                        total_billete20 +
	                                        total_billete10 +
	                                        total_billete5 +
	                                        total_billete2 +
	                                        total_billete1 +
	                                        total_billete50cen +
	                                        total_billete20cen +
	                                        total_billete10cen).toFixed(2);

          $("#ins_efectivo").html(total_efectivo);
          $("#ins_cambio").html(parseFloat(total_efectivo - parseFloat(document.getElementById("ins_totalventa").innerHTML).toFixed(2)).toFixed(2));
      	};

    	// Loading de Generar Instrucción
				function f_LoadingGenerarInstruccion(_is_show){
					if (_is_show == 1){
						$("#wt_generarinstruccion").show();

						$("#btn_generarinstruccion_grabar").prop('disabled', true);
						$("#btn_generarinstruccion_grabar").css('background-color', '#C2C0A6');

						$("#btn_generarinstruccion_cerrar").prop('disabled', true);
						$("#btn_generarinstruccion_cerrar").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_generarinstruccion").hide();

						$("#btn_generarinstruccion_grabar").prop('disabled', false);
						$("#btn_generarinstruccion_grabar").css('background-color', '');

						$("#btn_generarinstruccion_cerrar").prop('disabled', false);
						$("#btn_generarinstruccion_cerrar").css('background-color', '');
					}
				}

      // Loading de Confirmación de Instrucción
				function f_LoadingConfirmarInstruccion(_is_show){
					if (_is_show == 1){
						$("#wt_confirmarinstruccion").show();

						$("#btn_insgrabar").prop('disabled', true);
						$("#btn_insgrabar").css('background-color', '#C2C0A6');

						$("#btn_inscerrar").prop('disabled', true);
						$("#btn_inscerrar").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_confirmarinstruccion").hide();

						$("#btn_insgrabar").prop('disabled', false);
						$("#btn_insgrabar").css('background-color', '');

						$("#btn_inscerrar").prop('disabled', false);
						$("#btn_inscerrar").css('background-color', '');
					}
				}

			// Selección de filtros de precios
				function f_SelectedPrecios(_item){
					var s = 1;

					filtro_precios_x = _item;

					while (s <= 3){
						f_ReplaceClass("filtro_precios" + s, 'btn-primary', 'btn-outline-primary');

						s ++;
					}

					f_ReplaceClass("filtro_precios" + _item, 'btn-outline-primary', 'btn-primary');

					// Cargando la lista de Clasificaciones
						f_GetListaAnalisisClasificaciones();
				}

		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Graba información temporal (onblur).
				function f_GrabarCabeceraRecepcion_Temporal(){
					// Recupera variables
						var codigo_interno = $("#cab_codigo").val();
						var fecha_recepcion = $("#cab_fecharecepcion").val();
						var hora_recepcion = $("#cab_horarecepcion").val();
						var fecha_entrega = $("#cab_fechaentrega").val();
						var hora_entrega = $("#cab_horaentrega").val();
						var documento = $("#cab_clientedocumento").val();
						var razon_social = f_CleanInjection($("#cab_clienterazonsocial").val().trim().toUpperCase());
						var entregado_por = f_CleanInjection($("#cab_entregadopor").val().trim().toUpperCase());
						var celular_areportar = f_CleanInjection($("#cab_celularareportar").val().trim().toUpperCase());
						var cod_sucursal = $("#cab_sucursal").val();
						var cod_moneda = $("#cab_moneda").val();
						var observacion = f_CleanInjection($("#cab_observacion").val().trim().toUpperCase());

					// Grabando datos
						f_LoadingGrabarCabeceraPreliminar(1);

            $.post( "apis/backend.php", { accion: "grabar_CabeceraRecepcion_Temporal", id_recepcion: id_recepcion, codigo_interno: codigo_interno, fecha_recepcion: fecha_recepcion, hora_recepcion: hora_recepcion, fecha_entrega: fecha_entrega, hora_entrega: hora_entrega, documento: documento, razon_social: razon_social, entregado_por: entregado_por, celular_areportar: celular_areportar, cod_sucursal: cod_sucursal, cod_moneda: cod_moneda, observacion: observacion },
              function( data ) {
                if(data.estado == 0){
                  alert("Ocurrió un error al momento de grabar la Cabecera de la Recepción.");
                }

                f_LoadingGrabarCabeceraPreliminar(0);

              }, "json");
				}

			// Graba Cliente
				function f_GrabarCliente(){
					// Recupera variables
						var cod_tipocliente = $("#cliente_tipocliente").val();
						var cod_tipodocumento = $("#cliente_tipodocumento").val();
						var documento = $("#cliente_documento").val();
						var razon_social = $("#cliente_razonsocial").val();
						var telefono1 = $("#cliente_telefono1").val();
						var telefono2 = $("#cliente_telefono2").val();
						var correo = $("#cliente_correo").val();
						var direccion = $("#cliente_direccion").val();

					// Validando datos
						if (cod_tipocliente == null){
              alert("Debe seleccionar el Tipo de Cliente.");

              return;
            }
            if (cod_tipocliente.length == 0){
              alert("Debe seleccionar el Tipo de Cliente.");

              return;
            }

            if (cod_tipodocumento == null){
              alert("Debe seleccionar el Tipo de Documento.");

              return;
            }
            if (cod_tipodocumento.length == 0){
              alert("Debe seleccionar el Tipo de Documento.");

              return;
            }

            if (documento == null){
              alert("Debe ingresar el Número de Documento del Cliente.");

              return;
            }
            if (documento.length == 0){
              alert("Debe ingresar el Número de Documento del Cliente.");

              return;
            }

            if (cod_tipodocumento == 1){
            	if (documento.length != 8){
            		alert("Usted ha seleccionado DNI, la longitud del documento es incorrecta.\nDebería tener 8 dígitos.");

              	return;
            	}
            }

            if (cod_tipodocumento == 2){
            	if (documento.length != 11){
            		alert("Usted ha seleccionado RUC, la longitud del documento es incorrecta.\nDebería tener 11 dígitos.");

              	return;
            	}
            }

            if (razon_social == null){
              alert("Debe ingresar la Razón Social del Cliente.");

              return;
            }
            if (razon_social.length == 0){
              alert("Debe ingresar la Razón Social del Cliente.");

              return;
            }

            if (correo.trim().length > 0){
            	if (!f_CheckEMail('cliente_correo')){
            		alert("El correo ingresado no tiene el formato correcto.");

              	return;
            	}
            }

          // Grabando datos
            $.post( "apis/backend.php", { accion: "grabar_cliente", modo_grabar: 'N', id_cliente: 0, cod_tipocliente: cod_tipocliente, cod_tipodocumento: cod_tipodocumento, documento: documento, razon_social: razon_social, telefono1: telefono1, telefono2: telefono2, correo: correo, direccion: direccion },
              function( data ) {
                if (data.estado == 2){
                  alert("El documento ingresado ya fue registrado anteriormente, por favor verificar");

                  return;
                }
                else{
                  if(data.estado == 1){
                    $("#cab_clientedocumento").val(documento);
                    $("#cab_clienterazonsocial").val(razon_social);

                    f_cerrarModal("modal_addcliente");
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar el Cliente");
                  }
                }

              }, "json");
				}

			// Graba Cabecera
				function f_GrabarCabecera(){
					// Recupera variables
						var codigo_interno = $("#cab_codigo").val();
						var fecha_recepcion = $("#cab_fecharecepcion").val();
						var hora_recepcion = $("#cab_horarecepcion").val();
						var fecha_entrega = $("#cab_fechaentrega").val();
						var hora_entrega = $("#cab_horaentrega").val();
						var documento = $("#cab_clientedocumento").val();
						var razon_social = $("#cab_clienterazonsocial").val();
						var cod_sucursal = $("#cab_sucursal").val();
						var cod_moneda = $("#cab_moneda").val();
						var observacion = $("#cab_observacion").val();

					// Validando datos
						if (fecha_recepcion == null){
              alert("Debe ingresar la Fecha de Recepción.");

              return;
            }
            if (fecha_recepcion.length == 0){
              alert("Debe ingresar la Fecha de Recepción.");

              return;
            }

            if (hora_recepcion == null){
              alert("Debe ingresar la Hora de Recepción.");

              return;
            }
            if (hora_recepcion.length == 0){
              alert("Debe ingresar la Hora de Recepción.");

              return;
            }

            // if (fecha_entrega == null){
            //   alert("Debe ingresar la Fecha de Entrega.");

            //   return;
            // }
            // if (fecha_entrega.length == 0){
            //   alert("Debe ingresar la Fecha de Entrega.");

            //   return;
            // }

            // if (hora_entrega == null){
            //   alert("Debe ingresar la Hora de Entrega.");

            //   return;
            // }
            // if (hora_entrega.length == 0){
            //   alert("Debe ingresar la Hora de Entrega.");

            //   return;
            // }

            // if ((fecha_recepcion + ' ' + hora_recepcion) >= (fecha_entrega + ' ' + hora_entrega)){
          	// 	alert('La Fecha y Hora de Entrega no puede ser menor o igual a la de Recepción.\n\nPor favor, verificar.');

            //   return;
            // }

            if (documento == null){
              alert("Debe ingresar el documento del Cliente.");

              return;
            }
            if (documento.length == 0){
              alert("Debe ingresar el documento del Cliente.");

              return;
            }

            if (razon_social == null){
              alert("Debe ingresar un Cliente que exista en la base de datos.");

              return;
            }
            if (razon_social.length == 0){
              alert("Debe ingresar un Cliente que exista en la base de datos.");

              return;
            }

            if (cod_sucursal == null){
              alert("Debe seleccionar la Sucursal.");

              return;
            }
            if (cod_sucursal.length == 0){
              alert("Debe seleccionar la Sucursal.");

              return;
            }

            if (cod_moneda == null){
              alert("Debe seleccionar la Moneda.");

              return;
            }
            if (cod_moneda.length == 0){
              alert("Debe seleccionar la Moneda.");

              return;
            }

          // Grabando datos
						$("#wt_cabecerarecepcion").show();

		        $.post( "apis/backend.php", { accion: "grabar_CabeceraRecepcion", id_recepcion: id_recepcion },
		          function( data ) {
		            if(data.estado == 1){
		            	f_DisableObjCabecera(1);

		            	f_getRecepcionDetalle();
		            }
		            else{
		              alert("Ocurrió un error al momento de grabar la Cabecera de la Recepción.");
		            }

		            $("#wt_cabecerarecepcion").hide();

		          }, "json");
				}

			// Cancelar Recepción
				function f_CancelarRecepcion(){
					if (!confirm("¿Está seguro de Cancelar la Recepción?\n\nSi continua perderá toda la información.")){
						return;
					}

					// Cancelando Recepción
						$("#btn_GrabarRecepcion").prop('disabled', true);

		        $.post( "apis/backend.php", { accion: "cancelar_Recepcion", id_recepcion: id_recepcion },
		          function( data ) {
		            if(data.estado == 1){
		            	window.open('recepcion_ensayos.php', '_self');
		            }
		            else{
		              alert("Ocurrió un error al momento de Cancelar la Recepción.");
		            }

		            $("#btn_GrabarRecepcion").prop('disabled', false);

		          }, "json");
				}

			// Regresar Recepción (Back)
				function f_RegresarRecepcion(){
					if (!confirm("¿Está seguro de regresar a los datos de la Recepción?")){
						return;
					}

					// Regresando
						$("#btn_GrabarRecepcion").prop('disabled', true);
						$("#btn_CancelarRecepcion").prop('disabled', true);

		        $.post( "apis/backend.php", { accion: "regresar_Recepcion", id_recepcion: id_recepcion },
		          function( data ) {
		            if(data.estado == 1){
		            	f_DisableObjCabecera(0);
		            }
		            else{
		              alert("Ocurrió un error al momento de Cancelar la Recepción.");
		            }

		            $("#btn_GrabarRecepcion").prop('disabled', false);
								$("#btn_CancelarRecepcion").prop('disabled', false);

		          }, "json");
				}

			// Agregar muestras
				function f_Addmuestras(){
					var num_muestras = $("#det_nummuestras").val();

					// Validando número de muestras
						if (num_muestras == null){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras.length == 0){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras <= 0){
	            alert("El número de muestras ingresado no es válido.");

	            return;
	          }

	        // Obtener lista de muestras
	          $("#tbl_detallemuestras").html('');
	          $("#wt_detalle_muestras").show();

						$.post( "apis/backend.php", { accion: "add_RecepcionDetalleMuestras", num_muestras: num_muestras, id_recepcion: id_recepcion }, 
							function( data ) {
								if(data.estado == 1){
									f_getRecepcionDetalle();
								}

								$("#wt_detalle_muestras").hide();

							}, "json");
				}

			// Eliminar muestras
				function f_Delmuestras(){
					var num_muestras = $("#det_nummuestras_eliminar").val();

					// Validando número de muestras
						if (num_muestras == null){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras.length == 0){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras <= 0){
	            alert("El número de muestras ingresado no es válido.");

	            return;
	          }

	        if (!confirm("¿Está seguro de eliminar: " + num_muestras + " muestra(s).?\nSi continua perderá la información permanentemente.\n\n¿Desea continuar?")){
	        	return;
	        }

	        // Obtener lista de muestras
	          $("#tbl_detallemuestras").html('');
	          $("#wt_detalle_muestras").show();

						$.post( "apis/backend.php", { accion: "del_RecepcionDetalleMuestras", num_muestras: num_muestras, id_recepcion: id_recepcion },
							function( data ) {
								if(data.estado == 1){
									f_getRecepcionDetalle();

									$("#det_nummuestras_eliminar").val('');
								}

								$("#wt_detalle_muestras").hide();

							}, "json");
				}

			// Elimina las Muestras registradas
	    	$(document).on('click', '.deltr_detalle', function (event) {
          event.preventDefault();

          if (!confirm("¿Está seguro de eliminar la muestra seleccionada?")){
          	return;
          }

          // $(this).closest('tr').remove();

          // Eliminando Muestra
          	$.post( "apis/backend.php", { accion: "eliminar_MuestraRecepción", id_recepcion: id_recepcion, id_detalle: idmuestra_selected },
							function( data ) {
								if(data.estado == 1){
									f_getRecepcionDetalle();

									$("#det_nummuestras_eliminar").val('');
								}

								$("#wt_detalle_muestras").hide();

							}, "json");

          // Carga nuevamente el listado de muestras
          	// f_getRecepcionDetalle();
        });

			// Graba información temporal (onblur).
				function f_GrabarDetalleRecepcion_Temporal(){
					// Recupera variables
						var ci_muestra = $("#td_detalle_3_" + item_selected).html().trim();
						var nombre_muestra = f_CleanInjection($("#det_nombremuestra").val().trim().toUpperCase());
						var peso_muestra = $("#det_pesomuestra").val();
						var estado_muestra = $("#det_estadomuestra").val();
						var envase_muestra = $("#det_envasemuestra").val();
						var tipo_muestra = $("#det_tipomuestra").val();
						var ensayo_muestra = $("#det_ensayomuestra").val();
						var exceso_muestra = $("#det_excesomuestra").val();
						var observacion = f_CleanInjection($("#det_observacion").val().trim().toUpperCase());

					// Grabando datos
						f_LoadingGrabarDetallePreliminar(1);

            $.post( "apis/backend.php", { accion: "grabar_DetalleRecepcion_Temporal", id_detalle: idmuestra_selected, ci_muestra: ci_muestra, nombre_muestra: nombre_muestra, peso_muestra: peso_muestra, estado_muestra: estado_muestra, envase_muestra: envase_muestra, tipo_muestra: tipo_muestra, ensayo_muestra: ensayo_muestra, exceso_muestra: exceso_muestra, observacion: observacion },
              function( data ) {
                if(data.estado == 0){
                  alert("Ocurrió un error al momento de grabar el Detalle de la Recepción.");
                }

                f_LoadingGrabarDetallePreliminar(0);

              }, "json");
				}

			// Graba Análisis
				function f_ConfirmarAnalisis(){
					var _selected = '';

					// Eliminar Subtotal previo
	  				$("#tr_subtotal_add").remove();

					// Validando que se haya ingresado al menos 1 análisis
		    		$('#tbl_detalleanalisis_add tr').each(function () {
		          _selected += $(this).find("td").eq(3).html() + ';' + $(this).find("td").eq(6).html() + ';' + $(this).find("td").eq(7).html() + ';' + parseFloat($(this).find("td").eq(5).html()) + '|';
		        });

		        if (_selected.length == 0){
		        	alert("Debe ingresar al menos un análisis.");

		        	return;
		        }
		        else{
		        	_selected = _selected.substring(0, _selected.length - 1);
		        }

		      // Grabando análisis
		        $("#wt_detalleanalisis_add").show();

		        $.post( "apis/backend.php", { accion: "grabar_MuestrasAnalisis", id_detalle: idmuestra_selected, analisis_selected: _selected, fecha_recepcion: cab_fecharecepcion_x, hora_recepcion: cab_horarecepcion_x },
							function( data ) {
								if(data.estado == 1){
									// Actualiza el html de la tabla de análisis en la del detalle
										$("#tbl_detalleanalisis").html($("#tbl_detalleanalisis_add").html().replace(/id="tr_subtotal_add"/g, 'id="tr_subtotal"').replace(/"deltr_detalleanalisis_add"/g, '"deltr_detalleanalisis"').replace(/id="td_x_add"/g, 'id="td_x"'));

									// Calcular Subtotal
										f_CalculaSubtotal(0);

									// Vuelve a jalar los datos de la recepción para refresecar la hora de entrega y actualiza los datos de la cabecera
										f_GetIdRecepcion();
								}

								$("#wt_detalleanalisis_add").hide();

								f_cerrarModal("modal_addanalisis");

							}, "json");
				}

			// Grabar Recepción
				function f_GrabarRecepcion(){
					var _id_muestras = '';
					var m = 1;
					var arrmuestras_verify = '';
					var x = 1;

					// Arma array con cada muestra para detectar datos faltantes
						$('#tbl_detallemuestras tr').each(function () {
		          _id_muestras += $("#id_detalle_x_" + m).val() + ', ';

		          m ++;
		        });

		      // Realiza la verificación y/o graba la recepción
						_id_muestras = _id_muestras.substring(0, _id_muestras.length - 2);

						$.post( "apis/backend.php", { accion: "grabar_Recepcion", id_recepcion: id_recepcion, arr_idmuestras: _id_muestras }, 
							function( data ) {
								if(data.estado == 1){
									f_ImprimirRecibo(1);

									f_OpenModal('modal_confirmarrecepcion');

									return;
								}

								if(data.estado == 2 || data.estado == 3){
									if (data.estado == 2){
										alert("Se han detectado muestras con información incompleta.\nPor favor revise las muestras resaltadas en rojo.");
									}
									else{
										alert("Se han detectado muestras que no tienen análisis asignados.\nPor favor revise las muestras resaltadas en rojo.");
									}

									arrmuestras_verify = '|' + data.idmuestras_verify + '|';

									$('#tbl_detallemuestras tr').each(function () {
										$("#tr_item_" + x).css('background-color', '');

										if (arrmuestras_verify.includes('|' + $("#id_detalle_x_" + x).val() + '|')){
											$("#tr_item_" + x).css('background-color', '#F2A2A2');
										}

					          x ++;
					        });

					        return;
								}
								else{
									alert("Ocurrió un error al momento de grabar la recepción.");
								}

							}, "json");
				}

			// Replicar Muestras
				function f_EjecutarReplica(){
					var r = 1;
					var arr_idmuestras = '';

					// Obtiene un array de las muestras seleccionadas para replicar
						$('#tbl_muestrasreplica tr').each(function () {
	    				if ($("#tr_itemreplica_" + r).css('background-color') == 'rgb(255, 245, 135)'){
	    					arr_idmuestras += $("#id_replica_x_" + r).val() + '|';
	    				}

	            r ++;
	          });

	          if (arr_idmuestras.length == 0){
	          	alert("No ha seleccionado ninguna muestras para replicar.");

	          	return;
	          }
	          else{
	          	$.post( "apis/backend.php", { accion: "replicar_MuestraAnalisis", id_detalle: idmuestra_selected, arr_idmuestras: arr_idmuestras.substring(0, arr_idmuestras.length - 1) }, 
								function( data ) {
									if(data.estado == 1){
										f_cerrarModal("modal_replicarmuestra");
									}
								});
	          }
				}

			// Confirmar Instrucción
				function f_ConfirmarInstruccion(){
					// Validaciones para cuando se "Efectivo"
            var medio_pago = $("#ins_mediospago").val().substring(0, $("#ins_mediospago").val().indexOf('|'));
            var is_efectivo = $("#ins_mediospago").val().substring($("#ins_mediospago").val().indexOf('|') + 1);

            if (medio_pago == null){
                alert("Debe seleccionar el Medio de Pago.");

                return;
            }
            if (medio_pago.length == 0){
                alert("Debe seleccionar el Medio de Pago.");

                return;
            }

            if (is_efectivo == 1){
                var total_efectivo = parseFloat(document.getElementById("ins_totalventa").innerHTML).toFixed(2);
                var cambio = parseFloat(document.getElementById("ins_cambio").innerHTML).toFixed(2);

                if (medio_pago == 3){
                    if (parseFloat(cambio) < 0 || isNaN(cambio)){
                        alert('El efectivo ingresado aún no cubre el total de la venta.'+"\n\n"+'Por favor, verificar.');

                        return;
                    }
                }
                else{
                    if (total_efectivo == 0){
                        alert("No ha ingresado efectivo. \n\nPor favor, verificar.");

                        return;
                    }
                }
            }

          // Parámetros de pago
            var is_factura = (($("#chk_Comprobante").prop('checked')) ? 1 : 0);
            var total_venta = parseFloat(document.getElementById("ins_totalventa").innerHTML).toFixed(2);
            var efectivo_ingresado = parseFloat(document.getElementById("ins_efectivo").innerHTML).toFixed(2);

            var total_billete200 = parseFloat($("#total_billete200").val());
            var total_billete100 = parseFloat($("#total_billete100").val());
            var total_billete50 = parseFloat($("#total_billete50").val());
            var total_billete20 = parseFloat($("#total_billete20").val());
            var total_billete10 = parseFloat($("#total_billete10").val());

            var total_billete5 = parseFloat($("#total_billete5").val());
            var total_billete2 = parseFloat($("#total_billete2").val());
            var total_billete1 = parseFloat($("#total_billete1").val());
            var total_billete50cen = parseFloat($("#total_billete50cen").val());
            var total_billete20cen = parseFloat($("#total_billete20cen").val());
            var total_billete10cen = parseFloat($("#total_billete10cen").val());

          // Confirmar Instrucción
            if (!confirm("¿Está seguro de Confirmar la Instrucción?")){
            	return;
            }

            f_LoadingConfirmarInstruccion(1);

            $.post( "apis/backend.php", { accion: "confirmar_Instruccion", id_recepcion: id_recepcion, is_factura: is_factura, medio_pago: medio_pago, total_venta: total_venta, efectivo_ingresado: efectivo_ingresado, total_billete200: total_billete200, total_billete100: total_billete100, total_billete50: total_billete50, total_billete20: total_billete20, total_billete10: total_billete10, total_billete5: total_billete5, total_billete2: total_billete2, total_billete1: total_billete1, total_billete50cen: total_billete50cen, total_billete20cen: total_billete20cen, total_billete10cen: total_billete10cen }, 
							function( data ) {
								if(data.estado == 1){
									f_ImprimirRecibo(0);

									window.open('recepcion_ensayos.php', '_self');
								}
								else{
									alert("Ocurrió un error al momento de Confirmar la Instrucción.");
								}

								f_LoadingConfirmarInstruccion(0);

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