<?php

session_start();

include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');

if (!isset($_SESSION["Id"])) {
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
  <link rel="icon" href="<?php echo $favicon; ?>" type="image/png" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

  <!-- Íconos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

  <title><?php echo $nom_app; ?> | Gestión de Tipo de Cambio de Monedas</title>

  <script type="text/javascript">
    let loaded_img_TC_1 = '';
    let loaded_img_TC_2 = '';
    let img_selected_TC_1 = '0';
    let img_selected_TC_2 = '0';

    let loaded_img_TP_1 = '';
    let loaded_img_TP_2 = '';
    let img_selected_TP_1 = '0';
    let img_selected_TP_2 = '0';
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
                      <h6 style="font-size: 14px;">Por Fechas</h6>
                    </div>

                    <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                      <hr style="border-color: #D9D9D9;"/>
                    </div>


                    <div class="row" >
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltros();">
                      </div>
                      <br><br>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltros();">

                      </div>
                    </div>

                    <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                      

                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
                    <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: px;">
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
              <hr style="border-color: #D9D9D9;" />
            </div>
          
          </div>

          <div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
            <div class="row" style="padding: 20px;">
              <div class="col-md-10 col-sm-10 col-xs-10">
                <h5>Historial de Tipos de Cambio</h5>
              </div>

              <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
                <button class="btn btn-primary" type="button" onclick="f_AdminTipoCambio('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
                  <b> + Nuevo Tipo Cambio</b>
                </button>
              </div>


            </div>

            <div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
              <hr style="border-color: #D9D9D9;" />
            </div>

            <div id="div_resumen" class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll;">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="font-size: 14px;">
                    <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                      N°
                    </th>

                    <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Fecha
                    </th>

                    <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Moneda Base
                    </th>

                    <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      TC Compra
                    </th>

                    <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      TC Venta
                    </th>

                    <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Registro
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
  <div class="modal fade" id="modal_admintipocambio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_admintipocambioLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header py-2">
          <h5 class="modal-title" id="modal_admintipocambioLabel">Nuevo Tipo de Cambio</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-2">
            <div class="col-md-4">Fecha:</div>
            <div class="col-md-8"><input id="txt_fecha" type="date" class="form-control form-control-sm text-center"></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">Moneda Base:</div>
            <div class="col-md-8">
              <select id="cbo_moneda" class="form-select form-select-sm">
                <?php

                  $q_monedas = "SELECT Id,
                                       CONCAT('(', abv, ') ', descripcion) AS DESCRIPCION
                                  FROM tbconfig_monedas
                                 WHERE estado = 'A'
                                ORDER BY is_default_valorizaciones DESC";

                  if ($res_monedas = mysqli_query($enlace, $q_monedas)){
                    if (mysqli_num_rows($res_monedas) > 0) {
                      while($row_monedas = mysqli_fetch_array($res_monedas)){
                        ?>

                        <option value="<?php echo $row_monedas["Id"] ?>" style="font-size: 14px;"><?php echo $row_monedas["DESCRIPCION"] ?></option>

                        <?php
                      }
                    }
                  }

                ?>
              </select>
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4">TC Compra:</div>
            <div class="col-md-8"><input id="txt_tccompra" type="number" step="0.0001" class="form-control form-control-sm text-center"></div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4">TC Venta:</div>
            <div class="col-md-8"><input id="txt_tcventa" type="number" step="0.0001" class="form-control form-control-sm text-center"></div>
          </div>

          <input id="id_tipocambio" type="hidden">
          <input id="modo_grabar" type="hidden">
        </div>
        <div class="modal-footer">
          <div id="wt_admintipocambio" class="text-center mt-2" style="font-size: 12px; display: none;">
            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
            <label style="font-style: italic;"> Grabando datos...</label>
          </div>
          <button type="button" class="btn btn-secondary wt_admintipocambio_button" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary wt_admintipocambio_button" onclick="f_GrabarTipoCambio();">Grabar</button>
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

  <!-- Funciones de Inicio -->
  <script type="text/javascript">
    function f_Init() {
      // Genera menús
        f_GetMenuPrincipal();

      // Titulo de Pantalla
        $("#nv_titulo").html('| Gestión de Tipo de Cambio de Monedas');

      // Cargando listas generales
        

      // Carga el detalle de información
        f_LoadResultados();
    }
  </script>

  <!-- Funciones Principales -->
  <script type="text/javascript">
    function f_LoadResultados(){
      var fecha_inicio = $("#fecha_inicio").val();
      var fecha_fin = $("#fecha_fin").val();

      $.post('apis/backend.php', { accion: 'get_config_tiposcambio', fecha_inicio, fecha_fin }, function(response){
        if(response.estado == 1){
          $('#tbl_detalle').html(response.html);
        }
        else {
          $('#tbl_detalle').html('');
        }
      }, 'json');
    }

    function f_AdminTipoCambio(id, fecha = '', compra = 0, venta = 0, id_moneda_base = ''){
      if(id == 'x'){
        $('#modal_admintipocambioLabel').html('Nuevo Tipo de Cambio');
        $('#modo_grabar').val('N');
        $('#id_tipocambio').val('');
        $('#txt_fecha').val('<?php echo $g_date ?>');
        $('#txt_tccompra').val('');
        $('#txt_tcventa').val('');
        $('#cbo_moneda').val('2').trigger('change');
      }
      else {
        $('#modal_admintipocambioLabel').html('Editar Tipo de Cambio');
        $('#modo_grabar').val('E');
        $('#id_tipocambio').val(id);
        $('#txt_fecha').val(fecha);
        $('#txt_tccompra').val(parseFloat(compra).toFixed(4));
        $('#txt_tcventa').val(parseFloat(venta).toFixed(4));
        $('#cbo_moneda').val(id_moneda_base).trigger('change');
      }
      f_OpenModal('modal_admintipocambio');
    }
  </script>

  <!-- Funciones Secundarias -->
  <script type="text/javascript">
  </script>

  <!-- Funciones de Grabación -->
  <script type="text/javascript">
    function f_GrabarTipoCambio(){
      let modo = $('#modo_grabar').val();
      let id = $('#id_tipocambio').val();
      let fecha = $('#txt_fecha').val();
      let compra = parseFloat($('#txt_tccompra').val());
      let venta = parseFloat($('#txt_tcventa').val());
      let id_moneda_base = $('#cbo_moneda').val();

      // Validando datos
        if (!fecha){
          alert("Debe seleccionar la Fecha.");

          return;
        }

        if (!id_moneda_base){
          alert("Debe seleccionar la Moneda base.");

          return;
        }

        if (!compra){
          alert("Debe ingresar el Tipo de Cambio para Compra.");

          return;
        }

        if (!venta){
          alert("Debe ingresar el Tipo de Cambio para Venta.");

          return;
        }

      f_LoadingRegistro('admintipocambio', true);

      $.post('apis/backend.php', {
        accion: 'grabar_config_tipocambio',
        modo: modo,
        id: id,
        fecha: fecha,
        compra: compra,
        venta: venta,
        id_moneda_base: id_moneda_base,
      }, function(response){
        f_LoadingRegistro('admintipocambio', false);
        if(response.estado == 1){
          $('#modal_admintipocambio').modal('hide');
          f_LoadResultados();
        } else if(response.estado == 2){
          alert('El Tipo de Cambio para esta fecha ya se encuentra ingresado.');
        }else{
          alert('Error al grabar la información.');
        }
      }, 'json');
    }

    function f_CambiarEstadoTipoCambio(id, modo){
      if(confirm('¿Estás seguro de cambiar el estado de este tipo de cambio?')){
        $.post('apis/backend.php', { accion: 'cambiar_estado_config_tipocambio', id: id, modo: modo }, function(response){
          if(response.estado == 1){
            f_LoadResultados();
          }
        }, 'json');
      }
    }

    function f_EliminarTipoCambio(id){
      if(confirm('¿Estás seguro de eliminar este tipo de cambio?')){
        $.post('apis/backend.php', { accion: 'eliminar_config_tipocambio', id: id }, function(response){
          if(response.estado == 1){
            f_LoadResultados();
          }
        }, 'json');
      }
    }
  </script>
</body>

</html>