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

    <title><?php echo $nom_app; ?> | Configuración de Analitos y Grupos</title>

    <script type="text/javascript">

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
              <div id="div_analitos" class="col-md-6 col-sm-6 col-xs-12" style="padding: 0px; padding-bottom: 5px;">
                <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="d-flex">
                          <h5>Lista de Analitos </h5>

                          <div id="wt_ensayos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="d-flex justify-content-end">
                          <button type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AdminEnsayos('x');">+ Nuevo Analito</button>
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
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 30px; border-top-left-radius: 15px;">
                            N°
                          </th>

                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Descripción
                          </th>

                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Símbolo
                          </th>

                          <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Estado
                          </th>

                          <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
                            Acción
                          </th>
                        </tr>
                      </thead>

                      <tbody id="tbl_ensayos">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div id="div_grupos" class="col-md-6 col-sm-6 col-xs-12" style="padding: 0px; padding-left: 5px;">
                <div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="d-flex">
                          <h5>Lista de Grupos </h5>

                          <div id="wt_clasificaciones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="d-flex justify-content-end">
                          <button type="button" class="btn btn-primary" style="font-size: 14px; margin-top: -6px;" onclick="f_AdminClasificaciones('x');">+ Nuevo Grupo</button>
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
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 30px; border-top-left-radius: 15px;">
                            N°
                          </th>

                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Descripción
                          </th>

                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            ¿Tiene Tipo?
                          </th>

                          <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Estado
                          </th>

                          <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
                            Acción
                          </th>
                        </tr>
                      </thead>

                      <tbody id="tbl_clasificaciones">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Ventanas modales -->
    <div class="modal fade" id="modal_addensayo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addensayoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modal_addensayoLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
                Descripción:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-12">
                <input id="ensayo_descripcion" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
                Símbolo:
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <input id="ensayo_abv" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;">
              </div>
            </div>
          </div>

          <input id="hd_idensayo" type="hidden">
          <input id="hd_modograbarensayo" type="hidden">

          <div class="modal-footer">
            <div id="wt_grabarensayo" style="display: none; font-size: 12px; text-align: center;">
              <img src="<?php echo $img_waiting ?>" style="width: 20px;">
              <label style="font-style: italic;"> Grabando datos...</label>
            </div>

            <button type="button" class="btn btn-secondary wt_grabarensayo_button" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary wt_grabarensayo_button" onclick="f_GrabarEnsayo();">Grabar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_addclasificacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addclasificacionLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modal_addclasificacionLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
                Descripción:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-12">
                <input id="clasificacion_descripcion" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; text-transform: uppercase;">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
                ¿Tiene tipo?:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-12">
                <input id="tiene_tipo" type="checkbox" class="form-check-input" style="margin-top: 8px;">
              </div>
            </div>

          </div>

          <input id="hd_idclasificacion" type="hidden">
          <input id="hd_modograbarclasificacion" type="hidden">

          <div class="modal-footer">
            <div id="wt_grabarclasificacion" style="display: none; font-size: 12px; text-align: center;">
              <img src="<?php echo $img_waiting ?>" style="width: 20px;">
              <label style="font-style: italic;"> Grabando datos...</label>
            </div>

            <button type="button" class="btn btn-secondary wt_grabarclasificacion_button" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary wt_grabarclasificacion_button" onclick="f_GrabarClasificacion();">Grabar</button>
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

    <script>
      $(document).ready(function() {
        $('#ensayo_abv').on('input', function() {
          // Permite solo letras, números y guiones bajos
          $(this).val($(this).val().replace(/[^a-zA-Z0-9_]/g, ''));
        });
      });
    </script>

    <!-- Funciones de Inicio -->
    <script type="text/javascript">
      function f_Init(){
      // Genera menús
        f_GetMenuPrincipal();

      // Titulo de Pantalla
        $("#nv_titulo").html('| Configuración de Analitos y Grupos');

      // Cargando listas generales

      // Carga el detalle de información
        f_LoadEnsayos();
        f_LoadClasificaciones();
      }
    </script>

    <!-- Funciones Principales -->
    <script type="text/javascript">
      function f_LoadEnsayos(){
        var _html = '';
        var d = 1;

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        $("#tbl_ensayos").html('');

        f_LoadingEnsayos(1);

        $.post( "apis/backend.php", { accion: "get_ListaElementosQuimicos", is_gestionleyes: 1 }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.registros, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.descripcion;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold;">';
                _html += '      ' + val.abv;
                _html += '  </td>';

                // Setea el Estado del registro
                  if (val.estado == 'I'){
                    bk_color = '#E6A50D';
                    estado = 'Inactivo';
                    href_estado = 'Activar';
                    href_color = '#92D050';
                    href_icon = 'bi bi-node-plus';
                  }
                  else{
                    bk_color = '#92D050';
                    estado = 'Activo';
                    href_estado = 'Inactivar';
                    href_color = '#E6A50D';
                    href_icon = 'bi bi-node-minus';
                  }

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color:' + ((val.estado == 'I') ? '#E6A50D' : '#92D050') + '; color: #ffffff;">';
                  _html += '      ' + ((val.estado == 'A') ? 'Activo' : 'Inactivo');
                  _html += '  </td>';

                // Agregando acciones
                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';

                  _html += '      <a class="success" href="javascript: f_AdminEnsayos(' + d + ', ' + val.Id + ", '" + val.descripcion + "', '" + val.abv + "'" + ')"><i class="bi bi-pencil-square"></i>';
                  _html += '          <font style="color: #337ab7;"> Editar</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_CambiarEstadoEnsayo(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                  _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_EliminarEnsayo(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                  _html += '          <font style="color: #F20505;"> Eliminar</font>';
                  _html += '      </a>';

                  _html += '  </td>';

                _html += '</tr>';

                d += 1;
              });
            }

            $("#tbl_ensayos").html(_html);

            f_LoadingEnsayos(0);

          }, "json");
      };

      function f_LoadClasificaciones(){
        var _html = '';
        var d = 1;

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        $("#tbl_clasificaciones").html('');

        f_LoadingEnsayos(1);

        $.post( "apis/backend.php", { accion: "get_ListaLeyesGrupos_All" }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.descripcion;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.tiene_tipo == 1 ? 'Si' : 'No';
                _html += '  </td>';

                // Setea el Estado del registro
                  if (val.estado == 'I'){
                    bk_color = '#E6A50D';
                    estado = 'Inactivo';
                    href_estado = 'Activar';
                    href_color = '#92D050';
                    href_icon = 'bi bi-node-plus';
                  }
                  else{
                    bk_color = '#92D050';
                    estado = 'Activo';
                    href_estado = 'Inactivar';
                    href_color = '#E6A50D';
                    href_icon = 'bi bi-node-minus';
                  }

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color:' + ((val.estado == 'I') ? '#E6A50D' : '#92D050') + '; color: #ffffff;">';
                  _html += '      ' + ((val.estado == 'A') ? 'Activo' : 'Inactivo');
                  _html += '  </td>';

                // Agregando acciones
                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';

                  _html += '      <a class="success" href="javascript: f_AdminClasificaciones(' + d + ', ' + val.Id + ", '" + val.descripcion + "', '" + val.ABV + "'," +val.tiene_tipo+ ')"><i class="bi bi-pencil-square"></i>';
                  _html += '          <font style="color: #337ab7;"> Editar</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_CambiarEstadoClasificacion(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                  _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_EliminarClasificacion(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                  _html += '          <font style="color: #F20505;"> Eliminar</font>';
                  _html += '      </a>';

                  _html += '  </td>';

                _html += '</tr>';

                d += 1;
              });
            }

            $("#tbl_clasificaciones").html(_html);

            f_LoadingEnsayos(0);

          }, "json");
      };

      function f_AdminEnsayos(_item, _id_ensayo, _descripcion, _abv){
        // Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Ensayo:<br>"<b>' + _descripcion + '</b>"';
          }
          else{
            tipo = "N";
            titulo = "Nuevo Ensayo";
          }

        // Colocando el título a la pantalla
          $("#modal_addensayoLabel").html(titulo);

        // Identificando el tipo de grabación
          $("#hd_modograbarensayo").val(tipo);

          if (tipo != 'N'){
            $("#hd_idensayo").val(_id_ensayo);
            $("#ensayo_descripcion").val(_descripcion);
            $("#ensayo_abv").val(_abv);
          }
          else{
            $("#hd_idensayo").val(0);
            $("#ensayo_descripcion").val(_descripcion);
            $("#ensayo_abv").val(_abv);
          }

        // Abriendo modal
          f_OpenModal('modal_addensayo');
      }

      function f_AdminClasificaciones(_item, _id_clasificacion, _descripcion, _abv, _tiene_tipo){
        // Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Grupo:<br>"<b>' + _descripcion + '</b>"';
          }
          else{
            tipo = "N";
            titulo = "Nuevo Grupo";
          }

        // Colocando el título a la pantalla
          $("#modal_addclasificacionLabel").html(titulo);

        // Identificando el tipo de grabación
          $("#hd_modograbarclasificacion").val(tipo);

          if (tipo != 'N'){
            $("#hd_idclasificacion").val(_id_clasificacion);
            $("#clasificacion_descripcion").val(_descripcion);

            if(_tiene_tipo == 1){
              $("#tiene_tipo").prop("checked", true);
            }else{
              $("#tiene_tipo").prop("checked", false);
            }

          }
          else{
            $("#hd_idclasificacion").val(0);
            $("#clasificacion_descripcion").val(_descripcion);
            $("#tiene_tipo").prop("checked", false);
          }

        // Abriendo modal
          f_OpenModal('modal_addclasificacion');
      }
    </script>

    <!-- Funciones Secundarias -->
    <script type="text/javascript">
      function f_LoadingEnsayos(_is_show){
        if (_is_show == 1){
          $("#wt_ensayos").show();
        }
        else{
          $("#wt_ensayos").hide();
        }
      }
      
      function f_LoadingGrabarEnsayos(_is_show){
        if (_is_show == 1){
          $("#wt_grabarensayo").show();

          $(".wt_grabarensayo_button").prop('disabled', true);
        }
        else{
          $("#wt_grabarensayo").hide();

          $(".wt_grabarensayo_button").prop('disabled', false);
        }
      }
    </script>


    <!-- Funciones de Grabación -->
    <script type="text/javascript">
      function f_GrabarEnsayo(){
        // Recupera variables
          var id_ensayo = $("#hd_idensayo").val();
          var modo_grabar = $("#hd_modograbarensayo").val();

          var ensayo_descripcion = f_CleanInjection($("#ensayo_descripcion").val());
          var ensayo_abv = f_CleanInjection($("#ensayo_abv").val());

        // Validando datos
          if (ensayo_descripcion == null){
            alert("Debe ingresar la descripción del Ensayo.");

            return;
          }
          if (ensayo_descripcion.length == 0){
            alert("Debe ingresar la Descripción del Ensayo.");

            return;
          }

          if (ensayo_abv == null){
            alert("Debe ingresar el Símbolo del Ensayo.");

            return;
          }
          if (ensayo_abv.length == 0){
            alert("Debe ingresar el Símbolo del Ensayo.");

            return;
          }

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_Ensayo", modo_grabar: modo_grabar, id_ensayo: id_ensayo, ensayo_descripcion: ensayo_descripcion, ensayo_abv: ensayo_abv, is_gestionleyes: 1 },
            function( data ) {
              if (data.estado == 2 || data.estado == 3){
                if (data.estado == 2){
                  alert("La descripción del Ensayo ingresada ya fue registrada anteriormente, por favor verificar");
                }
                else{
                  alert("El Símbolo del Ensayo ingresado ya fue registrado anteriormente, por favor verificar");
                }

                return;
              }
              else{
                if(data.estado == 1){
                  f_LoadEnsayos();

                  f_cerrarModal('modal_addensayo');
                }
                else{
                  alert("Ocurrió un error al momento de grabar el Ensayo.");
                }
              }

            }, "json");
      }

      function f_CambiarEstadoEnsayo(_estado, _id_ensayo){
        var estado = ((_estado == 'I') ? 'Inactivar' : 'Activar');

        // Validando datos
          if (_estado != 'A' && _estado != 'I'){
            alert("Ocurrió un error al momento de cambiar el Estado.");

            return;
          }

        if(confirm("¿Está seguro de " + estado + " el Ensayo seleccionado?")){
          $.post( "apis/backend.php", { accion: "update_EstadoEnsayo", id_ensayo: _id_ensayo, estado: _estado }, 
            function( data ) {
              if(data.estado == 1){
                f_LoadEnsayos();
              }
              else{
                alert("Ocurrió un error al momento de cambiar el Estado.");
              }

            }, "json");
        }
      };

      function f_EliminarEnsayo(_id_ensayo){
        if(confirm("¿Está seguro de eliminar el Ensayo seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_Ensayo", id_ensayo: _id_ensayo },
            function( data ) {
              if(data.estado == 1){
                f_LoadEnsayos();
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Ensayo.");
              }
            }, "json");
        }
      };

      function f_GrabarClasificacion(){
        // Recupera variables
          var id_clasificacion = $("#hd_idclasificacion").val();
          var modo_grabar = $("#hd_modograbarclasificacion").val();

          var clasificacion_descripcion = f_CleanInjection($("#clasificacion_descripcion").val());
          
          var tiene_tipo = $("#tiene_tipo").is(':checked') ? 1 : 0;

        // Validando datos
          if (clasificacion_descripcion == null){
            alert("Debe ingresar la descripción de el Grupo.");

            return;
          }
          if (clasificacion_descripcion.length == 0){
            alert("Debe ingresar la Descripción de el Grupo.");

            return;
          }

        // Grabando Datos
          $.post( "apis/backend.php", { accion: "grabar_LeyesGrupo", modo_grabar: modo_grabar, id_clasificacion: id_clasificacion, clasificacion_descripcion: clasificacion_descripcion, tiene_tipo: tiene_tipo},
            function( data ) {
              if (data.estado == 2 || data.estado == 3){
                if (data.estado == 2){
                  alert("La descripción de el Grupo ingresada ya fue registrada anteriormente, por favor verificar");
                }
                else{
                  alert("La Abreviatura de el Grupo ingresada ya fue registrada anteriormente, por favor verificar");
                }

                return;
              }
              else{
                if(data.estado == 1){
                  f_LoadClasificaciones();

                  f_cerrarModal('modal_addclasificacion');
                }
                else{
                  alert("Ocurrió un error al momento de grabar el Ensayo.");
                }
              }

            }, "json");
      }

      function f_CambiarEstadoClasificacion(_estado, _id_clasificacion){
        var estado = ((_estado == 'I') ? 'Inactivar' : 'Activar');

        // Validando datos
          if (_estado != 'A' && _estado != 'I'){
            alert("Ocurrió un error al momento de cambiar el Estado.");

            return;
          }

        if(confirm("¿Está seguro de " + estado + " el Grupo seleccionado?")){
          $.post( "apis/backend.php", { accion: "update_EstadoLeyesGrupos", id_clasificacion: _id_clasificacion, estado: _estado }, 
            function( data ) {
              if(data.estado == 1){
                f_LoadClasificaciones();
              }
              else{
                alert("Ocurrió un error al momento de cambiar el Estado.");
              }

            }, "json");
        }
      };

      function f_EliminarClasificacion(_id_clasificacion){
        if(confirm("¿Está seguro de eliminar el Grupo seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "eliminar_LeyesGrupos", id_clasificacion: _id_clasificacion },
            function( data ) {
              if(data.estado == 1){
                f_LoadClasificaciones();
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Grupo.");
              }
            }, "json");
        }
      };
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