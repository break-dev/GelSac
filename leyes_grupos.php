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

    <title><?php echo $nom_app; ?> | Gestión de Grupos</title>

    <script type="text/javascript">
      let itemgrupo_Selected = 0;
      let idgrupo_Selected = 0;

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
              <div class="col-md-2"></div>

              <div id="div_patrones" class="col-md-5 col-sm-5 col-xs-12" style="padding: 5px;">
                <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="d-flex">
                          <h6>Lista de Grupos</h6>

                          <div id="wt_grupos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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

                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr style="font-size: 14px;">
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 30px; border-top-left-radius: 15px;">
                            N°
                          </th>

                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                            Descripción
                          </th>
                        </tr>
                      </thead>

                      <tbody id="tbl_clasificaciones">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div id="div_elementos" class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
                <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="d-flex">
                          <h6>Analitos de:</h6>
                          <h6 id="lbl_tituloelementos" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h6>

                          <div id="wt_elementos" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
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

                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -22px; overflow-x: scroll; width: 100%;">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr style="font-size: 14px;">
                          <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                            Item
                          </th>

                          <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px; width: 80%;">
                            Analitos
                          </th>
                        </tr>

                        <tr style="font-size: 14px;">
                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Sel.<br>
                            <input id="th_Chk" class="form-check-input" type="checkbox" style="margin-top: 5px; transform: scale(1.2);" onchange="f_SelectAll();">
                          </th>

                          <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            N°
                          </th>
                        </tr>
                      </thead>

                      <tbody id="tbl_elementos">
                        
                      </tbody>
                    </table>
                  </div>
                  </div>
                </div>
              </div>

              <div class="col-md-2"></div>
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
        $("#nv_titulo").html('| Gestión de Grupos');

        f_LoadClasificaciones();
      }
    </script>

     <!-- Funciones Principales -->
    <script type="text/javascript">

      function f_LoadClasificaciones(){
        var _html = '';
        var d = 1;

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        $("#tbl_clasificaciones").html('');

        f_LoadingGrupos(1);

        $.post( "apis/backend.php", { accion: "get_ListaLeyes_Grupos" }, 
          function( data ) {
            if(data.estado == 1){
               $("#tbl_clasificaciones").html(data.html);
            }

            f_LoadingGrupos(0);

            f_LoadItemGrupoAnalito(1, data.id_grupo);

          }, "json");
      };

      function f_LoadItemGrupoAnalito(_item, _id_grupo){
        var _html = '';
        var d = 1;
        var id_elemento = 0;

        // Validando datos
          if (_id_grupo == 0){
            return;
          }

        // Cargando Lista de Racks
          $("#tbl_elementos").html('');

          f_LoadingAnalitos(1);

          $.post( "apis/backend.php", { accion: "get_ListaLeyes_Grupos_Analitos", id_grupo: _id_grupo }, 
            function( data ) {
              if(data.estado == 1){
                $.each( data.registros, function( key, val ) {
                  if (d == 1){
                    id_elemento = val.Id;
                  }

                  _html += '<tr style="cursor: pointer; font-size: 14px;" >';

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
                  _html += '    <input id="th_Chk_' + d + '" class="form-check-input chk_analito" type="checkbox" style="margin-top: 5px; transform: scale(1.2);" onchange="f_SelectChkAnalitos(' + d + ', ' + val.Id + ');" ' + ((val.is_checked == 1) ? 'checked' : '') + '>';
                  _html += '  </td>';

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px;">';
                  _html += '   ' + d;
                  _html += '  </td>';

                  _html += '  <td id="td_item_E_' + d + '" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 30px; font-weight: bold;">';
                  _html += '   ' + val.abv;
                  _html += '  </td>';

                  _html += '</tr>';

                  d ++;
                });

                $("#tbl_elementos").html(_html);
              }

              itemgrupo_Selected = _item;
              idgrupo_Selected = _id_grupo;

              f_ColorSelectedGrupos(_item);

              f_LoadingAnalitos(0);

            }, "json");
      }

      function f_SelectChkAnalitos(_item, _id_analito){
        var is_selected = (($("#th_Chk_" + _item).prop('checked')) ? 1 : 0);

        if (is_selected == 1){
          $.post( "apis/backend.php", { accion: "grabar_Leyes_Grupos_Analitos", id_grupo: idgrupo_Selected, id_analito: _id_analito },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemGrupoAnalito(itemgrupo_Selected, idgrupo_Selected);
              }
              else{
                alert("Ocurrió un error al momento de grabar el elemento.");
              }

            }, "json");
        }
        else{
          $.post( "apis/backend.php", { accion: "eliminar_Leyes_Grupos_Analitos", id_grupo: idgrupo_Selected, id_analito: _id_analito },
            function( data ) {
              if(data.estado == 1){
                f_LoadItemGrupoAnalito(itemgrupo_Selected, idgrupo_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el elemento.");
              }

            }, "json");
        }
      }

    </script>

    <!-- Funciones Secundarias -->
    <script type="text/javascript">

      function f_LoadingGrupos(_is_show){
        if (_is_show == 1){
          $("#wt_grupos").show();
        }
        else{
          $("#wt_grupos").hide();
        }
      }

      function f_LoadingAnalitos(_is_show){
        if (_is_show == 1){
          $("#wt_elementos").show();
        }
        else{
          $("#wt_elementos").hide();
        }
      }

      function f_ColorSelectedGrupos(_item){
        // Recorre los Tr de la tabla y los limpia
          $(".tr_item_G").css('background-color', '');

        // Seteando item seleccionado
          $("#tr_item_G_" + _item).css('background-color', '#FFF587');

          $("#lbl_tituloelementos").html($("#td_itempgrupo_2_" + itemgrupo_Selected).html().trim());
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