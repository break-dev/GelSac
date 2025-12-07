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

    <title><?php echo $nom_app; ?> | Condiciones Comerciales</title>

    <script type="text/javascript">
      let idproveedorminero_Selected = 0;
      let itemproveedorminero_Selected = 0;
      let documentoproveedorminero_Selected = 0;

    </script>
  </head>

  <body class="bg-light" onload="f_Init();" style="zoom: 80%;">
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

        <div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
          <div class="d-flex row">
            <div class="row" style="padding: 0px;">

              <div id="div_patrones" class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
                <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="d-flex">
                          <h6>Lista de Proveedores</h6>

                          <div id="wt_proveedores" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                    <hr style="border-color: #D9D9D9;"/>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;height: 750px !important">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr style="font-size: 14px;">
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 30px; border-top-left-radius: 15px;">
                            N°
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            RUC
                          </th> 
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                            Razón Social
                          </th>
                          
                        </tr>
                      </thead>

                      <tbody id="tbl_proveedores">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div id="div_condiciones_comerciales" class="col-md-8 col-sm-8 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
                <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                  <div class="col-md-10 col-sm-10 col-xs-10" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="d-flex">
                          <h6>Condiciones Comerciales de:</h6>
                          <h6 id="lbl_titulocondicionescomerciales" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h6>

                          <div id="wt_condiciones_comerciales" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-10 col-sm-10 col-xs-10" style="margin-top: -5px;"></div>
                    <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
                      <button class="btn btn-primary" type="button" onclick="f_AdminCondicionComercial('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
                        <b> + Nueva C.C.</b>
                      </button>
                    </div>
                  </div>
                
                  <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                    <hr style="border-color: #D9D9D9;"/>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -22px; overflow-x: scroll; width: 100%;">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr style="font-size: 14px;">
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 30px; border-top-left-radius: 15px;">
                            N°
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Ley Auoz Inicio
                          </th> 
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Ley Auoz Fin
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Maquila
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Recuperacion
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Consumo
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; ">
                            Riesgo Comercial
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; ">
                            Estado
                          </th>
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                            Acción
                          </th>
                        </tr>
                      </thead>

                      <tbody id="tbl_condiciones_comerciales">
                        
                      </tbody>
                    </table>
                  </div>
                  </div>
                </div>
              </div>

              <div class="col-md-2"></div>
          </div>
        </div>

        <!-- Ventanas modales -->
        <div class="modal fade" id="modal_addcondicioncomercial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addcondicioncomercialLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-6" id="modal_addcondicioncomercialLabel">Nueva Condición Comercial</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row" style="padding: 5px;">
                  <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                    Ley Auoz Inicio:
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-8">
                    <input id="condicion_comercial_ley_auoz_inicio" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                  </div>
                </div>

                <div class="row" style="padding: 5px;">
                  <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                    Ley Auoz Fin: 
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-8">
                    <input id="condicion_comercial_ley_auoz_fin" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                  </div>
                </div>

                <div class="row" style="padding: 5px;">
                  <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                    Maquila: 
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-8">
                    <input id="condicion_comercial_maquila" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                  </div>
                </div>
                
                <div class="row" style="padding: 5px;">
                  <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                    Recuperación: 
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-8">
                    <input id="condicion_comercial_recuperacion" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                  </div>
                </div>
                
                <div class="row" style="padding: 5px;">
                  <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                   Consumo: 
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-8">
                    <input id="condicion_comercial_consumo" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                  </div>
                </div>
                
                <div class="row" style="padding: 5px;">
                  <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                    Riesgo Comercial: 
                  </div>

                  <div class="col-md-8 col-sm-8 col-xs-8">
                    <input id="condicion_comercial_riesgo_comercial" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                  </div>
                </div>
                
              </div>

              <input id="hd_id_condicion_comercial" type="hidden">
              <input id="hd_modograbar" type="hidden">

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="f_GrabarCondicionComercial();">Grabar</button>
              </div>
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
        $("#nv_titulo").html('| Condiciones Comerciales');

        f_LoadProveedores();
      }
    </script>

     <!-- Funciones Principales -->
    <script type="text/javascript">
      function f_LoadProveedores(){
        var _html = '';
        var d = 1;

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        $("#tbl_proveedores").html('');

        f_LoadingProveedores(1);

        $.post( "apis/backend.php", { accion: "get_ListaProveedoresMineros" }, 
          function( data ) {
            console.log(data);
            if(data.estado == 1){
               $("#tbl_proveedores").html(data.html);
            }

            f_LoadingProveedores(0);

            f_LoadItemCondicionComercial(1, data.id_proveedor, data.documento_proveedor);

          }, "json");
      };

      function f_LoadItemCondicionComercial(_item, _id_proveedorminero, _documento_proveedorminero){
       
        var _html = '';
        var d = 1;

        // Validando datos
          if (_id_proveedorminero == 0){
            return;
          }

        // Cargando Lista 
          $("#tbl_condiciones_comerciales").html('');

          f_LoadingCondicionesComerciales(1);

          $.post( "apis/backend.php", { accion: "get_ListaCondicionesComerciales", id_proveedorminero: _id_proveedorminero }, 
            function( data ) {
              if(data.estado == 1){
                $.each( data.registros, function( key, val ) {

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

                  _html += '<tr style="cursor: pointer; font-size: 14px;" >';

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
                  _html += '   ' + d;
                  _html += '  </td>';

                  _html += '  <td id="td_item_CC_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                  _html += '   ' + val.ley_auoz_inicio;
                  _html += '  </td>';

                  _html += '  <td id="td_item_CC_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                  _html += '   ' + val.ley_auoz_fin;
                  _html += '  </td>';

                  _html += '  <td id="td_item_CC_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '   ' + val.maquila;
                  _html += '  </td>';

                  _html += '  <td id="td_item_CC_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '   ' + val.recuperacion;
                  _html += '  </td>';

                  _html += '  <td id="td_item_CC_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '   ' + val.consumo;
                  _html += '  </td>';

                  _html += '  <td id="td_item_CC_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                  _html += '   ' + val.riesgo_comercial;
                  _html += '  </td>';

                  _html += '  <td id="td_item_CC_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color:' + ((val.estado == 'I') ? '#E6A50D' : '#44803F') + '; color: #ffffff;">';
                  _html += '      ' + ((val.estado == 'A') ? 'Activo' : 'Inactivo');
                  _html += '  </td>';

                  // Agregando acciones
                    _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';

                    _html += '      <a class="success" href="javascript: f_AdminCondicionComercial(' + d + ', ' + val.Id + ", " + val.ley_auoz_inicio + ","+val.ley_auoz_fin+ ", "+val.maquila +","+val.recuperacion+","+val.consumo+","+val.riesgo_comercial+ ')"><i class="bi bi-pencil-square"></i>';
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

                  d ++;
                });

                $("#tbl_condiciones_comerciales").html(_html);
              }

              itemproveedorminero_Selected = _item;
              idproveedorminero_Selected = _id_proveedorminero;
              documentoproveedorminero_Selected = _documento_proveedorminero;

              f_ColorSelectedProveedor(_item);

              f_LoadingCondicionesComerciales(0);

            }, "json");
      }

      function f_AdminCondicionComercial(_item, _id_condicion_comercial, _ley_auoz_inicio,_ley_auoz_fin,_maquila,_recuperacion,_consumo,_riesgo_comercial){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Condición Comercial: "<b>' + documentoproveedorminero_Selected + '</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Nueva Condición Comercial";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_addcondicioncomercialLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addcondicioncomercial');

	        if (tipo != 'N'){
            $("#hd_id_condicion_comercial").val(_id_condicion_comercial);
		        $("#condicion_comercial_ley_auoz_inicio").val(f_CleanInjection(_ley_auoz_inicio));
		        $("#condicion_comercial_ley_auoz_fin").val(f_CleanInjection(_ley_auoz_fin));
		        $("#condicion_comercial_maquila").val(f_CleanInjection(_maquila));
		        $("#condicion_comercial_recuperacion").val(f_CleanInjection(_recuperacion));
		        $("#condicion_comercial_consumo").val(f_CleanInjection(_consumo));
		        $("#condicion_comercial_riesgo_comercial").val(f_CleanInjection(_riesgo_comercial));
			    }
			    else{
			    	$("#hd_id_condicion_comercial").val(0);
		        $("#condicion_comercial_ley_auoz_inicio").val('');
		        $("#condicion_comercial_ley_auoz_fin").val('');
		        $("#condicion_comercial_maquila").val('');
		        $("#condicion_comercial_recuperacion").val('');
		        $("#condicion_comercial_consumo").val('');
		        $("#condicion_comercial_riesgo_comercial").val('');
		   		}
    	}

      function f_GrabarCondicionComercial(){
        // Recupera variables
          var id_condicion_comercial = $("#hd_id_condicion_comercial").val();
          var modo_grabar = $("#hd_modograbar").val();

          var ley_auoz_inicio = f_CleanInjection($("#condicion_comercial_ley_auoz_inicio").val());
          var ley_auoz_fin = f_CleanInjection($("#condicion_comercial_ley_auoz_fin").val());
          var maquila = f_CleanInjection($("#condicion_comercial_maquila").val());
          var recuperacion = f_CleanInjection($("#condicion_comercial_recuperacion").val());
          var consumo = f_CleanInjection($("#condicion_comercial_consumo").val());
          var riesgo_comercial = f_CleanInjection($("#condicion_comercial_riesgo_comercial").val());

        // Validando datos
          if (ley_auoz_inicio == null){
            alert("Debe ingresar la Ley Auoz de Inicio.");

            return;
          }
          if (ley_auoz_inicio.length == 0){
            alert("Debe ingresar la Ley Auoz de Inicio.");

            return;
          }

          if (ley_auoz_fin == null){
            alert("Debe ingresar la Ley Auoz de Fin.");

            return;
          }
          if (ley_auoz_fin.length == 0){
            alert("Debe ingresar la Ley Auoz de Fin.");

            return;
          }
        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_CondicionComercial", modo_grabar: modo_grabar, id_condicion_comercial: id_condicion_comercial, 
                                        documento_proveedor_minero: documentoproveedorminero_Selected, 
                                        ley_auoz_inicio: ley_auoz_inicio, ley_auoz_fin: ley_auoz_fin,
                                        maquila: maquila, recuperacion: recuperacion,   
                                        consumo: consumo, riesgo_comercial: riesgo_comercial
                                      },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemCondicionComercial(itemproveedorminero_Selected,idproveedorminero_Selected,documentoproveedorminero_Selected);

                f_cerrarModal('modal_addcondicioncomercial');
              }
              else{
                alert("Ocurrió un error al momento de grabar la Condición Comercial");
              }

            }, "json");
      }

      function f_CambiarEstado(_Estado, _id_registro){
        var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

        // Validando datos
          if (_Estado != 'A' && _Estado != 'I'){
            alert("Ocurrió un error al momento de cambiar el estado");

            return;
          }

        if(confirm("¿Está seguro de " + estado + " la condición comercial seleccionada?")){
          $.post( "apis/backend.php", { accion: "update_EstadoCondicionComercial", id_registro: _id_registro, estado: _Estado }, 
            function( data ) {
              if(data.estado == 1){
                f_LoadItemCondicionComercial(itemproveedorminero_Selected,idproveedorminero_Selected,documentoproveedorminero_Selected);
              }
              else{
                alert("Ocurrió un error al momento de cambiar el estado");
              }

            }, "json");
        }
      };

      function f_EliminarRegistro(_id_registro){
        if(confirm("¿Está seguro de eliminar la condición comercial seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_CondicionComercial", id_registro: _id_registro },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemCondicionComercial(itemproveedorminero_Selected,idproveedorminero_Selected,documentoproveedorminero_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar la Planta.");
              }
            }, "json");
        }
      };
    </script>

    <!-- Funciones Secundarias -->
    <script type="text/javascript">

      function f_LoadingProveedores(_is_show){
        if (_is_show == 1){
          $("#wt_proveedores").show();
        }
        else{
          $("#wt_proveedores").hide();
        }
      }

      function f_LoadingCondicionesComerciales(_is_show){
        if (_is_show == 1){
          $("#wt_condiciones_comerciales").show();
        }
        else{
          $("#wt_condiciones_comerciales").hide();
        }
      }

      function f_ColorSelectedProveedor(_item){
        // Recorre los Tr de la tabla y los limpia
          $(".tr_item_P").css('background-color', '');

        // Seteando item seleccionado
          $("#tr_item_P_" + _item).css('background-color', '#FFF587');

          $("#lbl_titulocondicionescomerciales").html($("#td_itemproveedor_3_" + _item).html());
      }

    </script>

    <!-- Funciones de Menús -->
    <script type="text/javascript">
      function f_SetDimension(){
        if (screen.width < 400){
          
        }
      }
    </script>

  </body>
</html>