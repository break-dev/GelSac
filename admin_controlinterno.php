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


  <title><?php echo $nom_app; ?> | Configurador para Gestión de Cumplimiento</title>

  <script type="text/javascript">
    let itemproceso_Selected = 0;
    let idproceso_Selected = 0;
    let itemprocesodescripcion_Selected = 0;
    let itemprocesoorigendato_Selected = 0; 

    let itemdetalle_Selected = 0;
    let iddetalle_Selected = 0;
    let itemdetalledescripcion_Selected = 0;


    let array_roles = [];
    let array_roles_update = [];

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

      <!--Filtros-->
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
              <div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
                <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                  <div class="row" style="padding-left: 10px; padding-right: 10px;">
                    <h6 style="font-size: 14px;">Por Área</h6>
                  </div>

                  <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                    <hr style="border-color: #D9D9D9;"/>
                  </div>

                  <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                    <select id="filtro_area" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadProcesos();">
                      <option selected value="">Elija una opción...</option>

                      <?php

                      $q_areas = "SELECT Id,
                      descripcion
                      FROM tbconfig_areas
                      WHERE estado = 'A'";

                      if ($res_areas = mysqli_query($enlace, $q_areas)){
                        if (mysqli_num_rows($res_areas) > 0) {
                          while($row_areas = mysqli_fetch_array($res_areas)){
                            ?>
                            <option value="<?php echo $row_areas["Id"]; ?>"><?php echo $row_areas["descripcion"]; ?></option>
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

          <!--Tabla Procesos-->
          <div class="row" style="padding: 0px;">
            <div id="div_procesos" class="col-md-4 col-sm-4 col-xs-4" style="padding: 0px; padding-bottom: 5px;">
              <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                  <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <div class="d-flex">
                        <h5>Lista de Procesos:</h5>

                        <div id="wt_proceso" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                          <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                          <label style="font-style: italic;"> Cargando datos...</label>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <button class="btn btn-primary" type="button" onclick="f_AdminProceso('N');" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;">
                        <b>+ Proceso</b>
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
                        <th colspan="4" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                          Item
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                          Área
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                          Origen
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                          Descripción
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  border-top-right-radius: 15px;">
                          Estado
                        </th>
                      </tr>
                    </thead>

                    <tbody id="tbl_procesos">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!--Tabla Detalles-->
            <div id="div_procesos_detalles" class="col-md-4 col-sm-4 col-xs-4" style="padding: 0px; padding-left: 5px;">
              <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                  <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <div class="d-flex">
                        <h5>Detalle: </h5>
                        <h5 id="lbl_tituloprocesos" style="margin-left: 5px; color: #337ab7;"></h5>
                        <input id="input_value_proceso_id" type="hidden">

                        <div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                          <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                          <label style="font-style: italic;"> Cargando datos...</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12" >
                      <button id="btn_add_procesos_detalle" type="button" class="btn btn-primary"  style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;"  onclick="f_AdminDetalle('N');"><b>+ Detalles</b></button>
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
                        <th colspan="4" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                          Item
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          Tipo de cliente
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          Descripción
                        </th> 

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          Primer Tramo
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          Segundo Tramo
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  border-top-right-radius: 15px;">
                          Estado
                        </th>
                      </tr>
                    </thead>

                    <tbody id="tbl_detalles">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!--Tabla Tipo de Dato-->
            <div id="div_procesos_detalles_tipos_datos" class="col-md-4 col-sm-4 col-xs-4" style="padding: 0px; padding-left: 5px;">
              <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                  <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <div class="d-flex">
                        <h5>Tipo de Dato: </h5>
                        <h5 id="lbl_tituloprocesosdetalles" style="margin-left: 5px; color: #337ab7;"></h5>
                        <input id="input_value_proceso_detalle_id" type="hidden">

                        <div id="wt_tipo_dato" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                          <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                          <label style="font-style: italic;"> Cargando datos...</label>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12" >
                      <button id="btn_add_procesos_detalle_tipo_dato" type="button" class="btn btn-primary"  style="width: 100%;color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px;"  onclick="f_AdminTipoDato('N');"><b>+ Tipo de Dato</b></button>
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
                        <th colspan="4" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                          Item
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          Tipo de dato
                        </th>


                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          Etiqueta
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          ¿Alerta?
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                          Correos
                        </th>

                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  border-top-right-radius: 15px;">
                          Estado
                        </th>
                      </tr>
                    </thead>

                    <tbody id="tbl_tipo_dato">

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

  
  <!-- Ventanas modales Procesos -->
  <div class="modal fade modal-dialog-scrollable" id="modal_adminprocesos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminprocesosLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modal_adminprocesosLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Origen dato:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="proceso_origendato" class="form-select">
                <option value="">Elija una opción...</option>

                <?php

                $q_origendatos = "SELECT Id,
                descripcion
                FROM tb_controlinterno_origendato
                WHERE estado = 'A'
                ORDER BY descripcion";

                if ($res_origendatos = mysqli_query($enlace, $q_origendatos)){
                  if (mysqli_num_rows($res_origendatos) > 0) {
                    while($row_origendatos = mysqli_fetch_array($res_origendatos)){
                      ?>

                      <option value="<?php echo $row_origendatos["Id"] ?>"><?php echo $row_origendatos["descripcion"] ?></option>

                      <?php
                    }
                  }
                }

                ?>
              </select>
            </div>
          </div>

          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Área:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="proceso_area" class="form-select">
                <option value="">Elija una opción...</option>

                <?php

                $q_areas = "SELECT Id,
                descripcion
                FROM tbconfig_areas
                WHERE estado = 'A'
                ORDER BY descripcion";

                if ($res_areas = mysqli_query($enlace, $q_areas)){
                  if (mysqli_num_rows($res_areas) > 0) {
                    while($row_areas = mysqli_fetch_array($res_areas)){
                      ?>

                      <option value="<?php echo $row_areas["Id"] ?>"><?php echo $row_areas["descripcion"] ?></option>

                      <?php
                    }
                  }
                }

                ?>
              </select>
            </div>
          </div>

        

          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Descripción:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="proceso_descripcion" type="text" class="form-control col-md-12 col-xs-12">
            </div>
          </div>
        </div>

        <input id="modo_grabarproceso" type="hidden">
        <input id="id_proceso" type="hidden">
        <input id="item_proceso" type="hidden">

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarProceso();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Ventanas modales Detalles -->
  <div class="modal fade modal-dialog-scrollable" id="modal_adminprocesosdetalles" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminprocesosdetallesLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modal_adminprocesosdetallesLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;" id="lbl_tipo_cliente">
              Tipo de Cliente:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="proceso_detalle_tipocliente" class="form-select">
                <option value="">Elija una opción...</option>

                <?php

                $q_tipocliente = "SELECT Id,
                descripcion
                FROM tbconfig_tipocliente
                WHERE estado = 'A'
                ORDER BY descripcion";

                if ($res_tiposclientes = mysqli_query($enlace, $q_tipocliente)){
                  if (mysqli_num_rows($res_tiposclientes) > 0) {
                    while($row_tiposclientes = mysqli_fetch_array($res_tiposclientes)){
                      ?>

                      <option value="<?php echo $row_tiposclientes["Id"] ?>"><?php echo $row_tiposclientes["descripcion"] ?></option>

                      <?php
                    }
                  }
                }

                ?>
              </select>
            </div>
          </div>


          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Descripción:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="proceso_detalle_descripcion" type="text" class="form-control col-md-12 col-xs-12">
            </div>
          </div>

          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;" id="lbl_verificacion">
              Verificación:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="checkbox_is_primer_tramo">
                <label class="form-check-label" for="checkbox_is_primer_tramo">Primer Tramo</label>
              </div>  

              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="checkbox_is_segundo_tramo">
                <label class="form-check-label" for="checkbox_is_segundo_tramo">Segundo Tramo</label>
              </div>
            

            </div>
          </div>

          <hr>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <button style="width: 100%" id="btn_add_procesos_detalle" type="button" class="btn btn-primary btn-sm btn-block"   onclick="f_AdminDetalleRol();"><b>+ Acceso Rol</b></button>
          </div>
          <br>

          <!--Tabla Acceso a roles-->
          <div class="col-md-12 col-sm-12 col-xs-12" style=" margin-top: -15px; overflow-x: scroll; width: 100%;">
            <table class="table table-bordered table-hover">
              <thead>
                <tr style="font-size: 14px;">
                  <th colspan="2" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                    Item
                  </th>

                  <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  border-top-right-radius: 15px;">
                    Rol
                  </th>
                </tr>
              </thead>

              <tbody id="tbl_detalles_roles">

              </tbody>
            </table>
          </div>  

        </div>

        <input id="modo_grabardetalle" type="hidden">
        <input id="id_proceso_detalle" type="hidden">
        <input id="item_proceso_detalle" type="hidden">

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarDetalle();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Ventanas modales Acceso a Rol -->
  <div class="modal fade modal-dialog-scrollable" id="modal_adminprocesosdetallesroles" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminprocesosdetallesrolesLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modal_adminprocesosdetallesrolesLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Rol:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="proceso_detalle_rol" class="form-select">
                <option value="">Elija una opción...</option>

                <?php

                $q_rol = "SELECT Id,
                nom_rol as descripcion
                FROM tb_rol
                WHERE estado = 'A'
                ORDER BY descripcion";

                if ($res_roles = mysqli_query($enlace, $q_rol)){
                  if (mysqli_num_rows($res_roles) > 0) {
                    while($row_roles = mysqli_fetch_array($res_roles)){
                      ?>

                      <option value="<?php echo $row_roles["Id"] ?>"><?php echo $row_roles["descripcion"] ?></option>

                      <?php
                    }
                  }
                }

                ?>
              </select>
            </div>
          </div>
        </div>

        <input id="modo_grabardetallerol" type="hidden">
        <input id="id_proceso_detalle_rol" type="hidden">
        <input id="item_proceso_detalle_rol" type="hidden">

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarDetalleRol();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Ventanas modales Tipo de Dato -->
  <div class="modal fade modal-dialog-scrollable" id="modal_adminprocesosdetallestiposdatos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminprocesosdetallestiposdatosLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modal_adminprocesosdetallestiposdatosLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Tipo de Dato:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="proceso_detalle_tipo_dato_tipodato" class="form-select">
                <option value="">Elija una opción...</option>

                <?php

                $q_tipodato = "SELECT Id,
                descripcion,
                tiene_alerta
                FROM tbconfig_tipodato
                WHERE estado = 'A'
                ORDER BY descripcion";

                if ($res_tiposdatos = mysqli_query($enlace, $q_tipodato)){
                  if (mysqli_num_rows($res_tiposdatos) > 0) {
                    while($row_tiposdatos = mysqli_fetch_array($res_tiposdatos)){
                      ?>

                      <option data-tiene-alerta="<?php echo $row_tiposdatos["tiene_alerta"] ?>"  value="<?php echo $row_tiposdatos["Id"] ?>"><?php echo $row_tiposdatos["descripcion"] ?></option>

                      <?php
                    }
                  }
                }

                ?>
              </select>
            </div>
          </div>

          <div id="div_mostrar_alerta" style="display: none;">
            <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
                Correos alerta:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <textarea id="proceso_detalle_tipo_dato_correo" rows="4"  class="form-control col-md-12 col-xs-12"></textarea>
                <span style="color: red">Use (,) para separar los datos</span>
              </div>
            </div>

          </div>

          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Etiqueta:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="proceso_detalle_tipo_dato_etiqueta" type="text" class="form-control col-md-12 col-xs-12">
            </div>
          </div>

          <div class="row" style="margin-top: -5px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 5px; text-align: left;">
              Link de referencia:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="proceso_detalle_tipo_dato_link_referencia" type="text" class="form-control col-md-12 col-xs-12">
            </div>
          </div>

        </div>

        <input id="modo_grabartipodato" type="hidden">
        <input id="id_proceso_detalle_tipo_dato" type="hidden">
        <input id="item_proceso_detalle_tipo_dato" type="hidden">

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarTipoDato();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Referenciando a JQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

  <!-- ECharts -->
  <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

  <!-- Referenciando auxiliares -->
  <?php include('global/auxiliares_js.php'); ?>

  <!-- Funciones de Inicio -->
  <script type="text/javascript">
    $( document ).ready(function() {
      array_roles = []; 
      array_roles_update = [];

      $("#proceso_detalle_tipo_dato_tipodato").change(function(){
        var element = $("option:selected", this);
        var tag_tiene_alerta = element.attr("data-tiene-alerta");
        if(tag_tiene_alerta == 1){
          $("#div_mostrar_alerta").show();
          $("#proceso_detalle_tipo_dato_correo").val('');
        }else{
          $("#div_mostrar_alerta").hide();
          $("#proceso_detalle_tipo_dato_correo").val('');
        }

      });

    });

    function f_Init(){
        // Genera menús
      f_GetMenuPrincipal();

        // Titulo de Pantalla
      $("#nv_titulo").html('| Configurador para Gestión de Cumplimiento');

        // Carga el detalle de información
      f_LoadProcesos();

      f_SetButtons(0);

    }
  </script>

  <!-- Funciones Principales -->
  <script type="text/javascript">
    function f_LoadProcesos(){
      var _html = '';
      var d = 1;
          // Obteniendo filtros
      var filtro_area = $("#filtro_area").val();

      $("#tbl_procesos").html(_html);


      f_LoadingProcesos(1);

      $.post( "apis/backend.php", { accion: "get_ControlInterno_Procesos", filtro_area: filtro_area}, 
        function( data ) {
          if(data.estado == 1){
            $("#tbl_procesos").html(data.html);
          }
          else{
                // alert("No se encontraron resultados.");
          }

          $("#lbl_tituloprocesos").html('');

          f_LoadingProcesos(0);

          $("#tbl_detalles").html('');
          $("#tbl_tipo_dato").html('');

          f_SetButtons(0);


        }, "json");


    };

    function f_AdminProceso(_modo, _item, _id_proceso,_id_area, _id_tipo,_descripcion,_id_origendato){
        // Registrando el modo
      $("#modo_grabarproceso").val(_modo);
      $("#id_proceso").val(_id_proceso);
      $("#item_proceso").val(_item);

        // Colocando Títulos
      if (_modo == 'N'){
        $("#modal_adminprocesosLabel").html('Nuevo Proceso');
      }
      else{
        $("#modal_adminprocesosLabel").html('Editar Proceso');
      }

        // Cargando datos
      if (_modo != 'N'){
        $("#proceso_area").val(_id_area);
        $("#proceso_origendato").val(_id_tipo);
        $("#proceso_descripcion").val(_descripcion);
      }
      else{
        $("#proceso_area").val('');
        $("#proceso_origendato").val('');
        $("#proceso_descripcion").val('');
      }

        // Abre modal
      f_OpenModal('modal_adminprocesos');
    };


    function f_AdminDetalle(_modo, _item, _id_proceso_detalle,_id_tipo_cliente, _descripcion,_is_primer_tramo, _is_segundo_tramo, _array_roles_){
      array_roles=[];
      array_roles_update=[];

        // Registrando el modo
      $("#modo_grabardetalle").val(_modo);
      $("#id_proceso_detalle").val(_id_proceso_detalle);
      $("#item_proceso").val(_item);

        // Colocando Títulos
      if (_modo == 'N'){
        $("#modal_adminprocesosdetallesLabel").html('Nuevo Detalle');
      }
      else{
        $("#modal_adminprocesosdetallesLabel").html('Editar Detalle');
      }

        // Cargando datos
      if (_modo != 'N'){
        $("#proceso_detalle_tipocliente").val(_id_tipo_cliente);
        $("#proceso_detalle_descripcion").val(_descripcion);

        _is_primer_tramo == 1 ? $("#checkbox_is_primer_tramo").prop('checked',true) : $("#checkbox_is_primer_tramo").prop('checked',false);

        _is_segundo_tramo == 1 ? $("#checkbox_is_segundo_tramo").prop('checked',true) : $("#checkbox_is_segundo_tramo").prop('checked',false);

        //Modificar Detalle
        // Seteando array de Roles
        var row = '';
        var r = 0;
        var _arr_roles = _array_roles_.split('|');
        var id_rol = 0;
        var des_rol = '';
        var _html = '';

        if(_array_roles_.length>0){
          while (r < _arr_roles.length){
            id_rol = _arr_roles[r].split('$')[0];
            des_rol = _arr_roles[r].split('$')[1];

                  // _html += '<tr id="proceso_detalle_rol_id_'+id_rol+'">';
                  // _html += ' <td>';
                  // _html += '   ' + (r + 1);
                  // _html += ' </td>';

                  // _html += ' <td>';
                  // _html += '      <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarDetalle('+id_rol+');">X</label>';
                  // _html += ' </td>';

                  // _html += ' <td>';
                  // _html += '   ' + des_rol;
                  // _html += ' </td>';
                  // _html += '</tr>';

            array_roles.push({'id_rol':id_rol, 'descripcion_rol':des_rol});
            array_roles_update.push({'id_rol':id_rol, 'descripcion_rol':des_rol});

            r ++;
          }

              // $("#tbl_detalles_roles").html(_html);

        }


        var table = document.getElementById("tbl_detalles_roles");

        $("#tbl_detalles_roles").html('');

        for (var i = 0; i < array_roles.length; i++) {
          row = table.insertRow();
          row.id = 'proceso_detalle_rol_id_'+array_roles[i].id_rol;
          cell_0 = row.insertCell(0);
          cell_0.innerHTML = (i+1);

          cell_1 = row.insertCell(1);
          cell_1.innerHTML = '<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarDetalleRol(\''+array_roles[i].id_rol+'\')">X</label>';
          cell_2 = row.insertCell(2);
          cell_2.innerHTML = array_roles[i].descripcion_rol;
        }


      }
      else{
        $("#proceso_detalle_tipocliente").val('');
        $("#proceso_detalle_descripcion").val('');
        $("#tbl_detalles_roles").html('');
      }

        // Abre modal
      f_OpenModal('modal_adminprocesosdetalles');
    };

    function f_AdminDetalleRol(){
        // Colocando Títulos
      $("#modal_adminprocesosdetallesrolesLabel").html('Agregar Acceso Rol');

      $("#proceso_detalle_rol").val('');

        // Abre modal
      f_OpenModal('modal_adminprocesosdetallesroles');
    };


    function f_AdminTipoDato(_modo, _item, _id_proceso_detalle,_id_tipo_dato, _correo, _etiqueta, _link_referencia){
        // Registrando el modo
      $("#modo_grabartipodato").val(_modo);
      $("#id_proceso_detalle_tipo_dato").val(_id_proceso_detalle);
      $("#item_proceso_detalle").val(_item);

        // Colocando Títulos
      if (_modo == 'N'){
        $("#modal_adminprocesosdetallestiposdatosLabel").html('Nuevo Tipo de Dato');
      }
      else{
        $("#modal_adminprocesosdetallestiposdatosLabel").html('Editar Tipo de Dato');
      }

        // Cargando datos
      if (_modo != 'N'){
        $("#proceso_detalle_tipo_dato_tipodato").val(_id_tipo_dato);
        $("#proceso_detalle_tipo_dato_correo").val(_correo);
        $("#proceso_detalle_tipo_dato_etiqueta").val(_etiqueta);
        $("#proceso_detalle_tipo_dato_link_referencia").val(_link_referencia);
        if(_correo.length>0){
          $("#div_mostrar_alerta").show();
        }else{
          $("#div_mostrar_alerta").hide();
          $("#proceso_detalle_tipo_dato_correo").val('');
        }
      }
      else{
        $("#proceso_detalle_tipo_dato_tipodato").val('');
        $("#proceso_detalle_tipo_dato_correo").val('');
        $("#proceso_detalle_tipo_dato_etiqueta").val('');
        $("#proceso_detalle_tipo_dato_link_referencia").val('');
        $("#div_mostrar_alerta").hide();
      }

        // Abre modal
      f_OpenModal('modal_adminprocesosdetallestiposdatos');
    };

    function f_ColorSelectedProceso(_item){
      var i = 1;

        // Recorre los Tr de la tabla y los limpia
      $("#tbl_procesos tr").each(function () {
        $("#tr_proceso_item_" + i).css('background-color', '');

        i += 1;
      });

        // Seteando item seleccionado
      $("#tr_proceso_item_" + _item).css('background-color', '#FFF587');

    };


    function f_ColorSelectedDetalle(_item){
      var i = 1;

        // Recorre los Tr de la tabla y los limpia
      $("#tbl_detalles tr").each(function () {
        $("#tr_detalle_item_" + i).css('background-color', '');

        i += 1;
      });

        // Seteando item seleccionado
      $("#tr_detalle_item_" + _item).css('background-color', '#FFF587');

    };

    function f_ColorSelectedTipoDato(_item){
      var i = 1;

        // Recorre los Tr de la tabla y los limpia
      $("#tbl_procesos tr").each(function () {
        $("#tr_item_" + i).css('background-color', '');

        i += 1;
      });

        // Seteando item seleccionado
      $("#tr_item_" + _item).css('background-color', '#FFF587');

    };

    function f_LoadProcesosDetalles(_item, _id_proceso, _proceso_descripcion, _proceso_origendato){
      var _html = '';
      itemproceso_Selected = _item;
      idproceso_Selected = _id_proceso;
      itemprocesodescripcion_Selected = _proceso_descripcion;
      itemprocesoorigendato_Selected = _proceso_origendato;

      $("#lbl_tituloprocesos").html(_proceso_descripcion);
      $("#input_value_proceso_id").val(_id_proceso);

      $("#lbl_tituloprocesosdetalles").html('');
      $("#input_value_proceso_detalle_id").val('');

      $("#lbl_tituloprocesos").html(_proceso_descripcion);
      $("#input_value_proceso_id").val(_id_proceso);

      $("#tbl_detalles").html('');
      $("#tbl_tipo_dato").html('');

      //Desactivar el combo de Tipo de cliente
      if(itemprocesoorigendato_Selected == 3 || itemprocesoorigendato_Selected == 4){ // 3: Lote 4: Planta
        $('#proceso_detalle_tipocliente').prop('disabled', true);
        $('#lbl_tipo_cliente').css('color', '#d3d3d3');
      }else{
        $('#proceso_detalle_tipocliente').prop('disabled', false);
        $('#lbl_tipo_cliente').css('color', '#000');
      }

      if(itemprocesoorigendato_Selected == 3){
        $('#checkbox_is_primer_tramo').prop('disabled', false);
        $('#checkbox_is_segundo_tramo').prop('disabled', false);
        $('#lbl_verificacion').css('color', '#000');
      }else{
        $('#checkbox_is_primer_tramo').prop('disabled', true);
        $('#checkbox_is_segundo_tramo').prop('disabled', true);
        $('#lbl_verificacion').css('color', '#d3d3d3');
      }


        // Pintar el proceso
        f_ColorSelectedProceso(_item);

        // Inhabilita los botones
        f_SetButtons(1);

        // Cargando datos
        f_LoadingDetalle(1);

        $("#tbl_detalles").html(_html);

        $.post( "apis/backend.php", { accion: "get_ControlInterno_Detalles", id_proceso: _id_proceso }, 
          function( data ) {
            if(data.estado == 1){
              // Actualiza la tabla de Muestras
              $("#tbl_detalles").html(data.html);

              // Seteando botones
              f_SetButtons(1);


              $("#tbl_tipo_dato").html('');
              $("#lbl_tituloprocesosdetalles").val('');
            }
            else{
              f_SetButtons(1);
            }

            f_LoadingDetalle(0);

          }, "json");

      };

      function f_LoadProcesosDetallesTiposDatos(_item, _id_proceso_detalle, _proceso_detalle_descripcion){
        var _html = '';

        itemdetalle_Selected = _item;
        iddetalle_Selected = _id_proceso_detalle;
        itemdetalledescripcion_Selected= _proceso_detalle_descripcion;

        $("#lbl_tituloprocesosdetalles").html(_proceso_detalle_descripcion);
        $("#input_value_proceso_detalle_id").val(_id_proceso_detalle);

        $("#tbl_tipo_dato").html('');

        // Pintar el detalle
        f_ColorSelectedDetalle(_item);

        // Inhabilita los botones
        f_SetButtons(0);

        // Cargando datos
        f_LoadingTipoDato(1);

        $("#tbl_tipo_dato").html(_html);

        $.post( "apis/backend.php", { accion: "get_ControlInterno_TiposDatos", id_proceso_detalle: _id_proceso_detalle }, 
          function( data ) {
            if(data.estado == 1){
                // Actualiza la tabla de Muestras
              $("#tbl_tipo_dato").html(data.html);

                  // Seteando botones
              f_SetButtons(2);

            }
            else{
              f_SetButtons(2);
            }

            f_LoadingTipoDato(0);

          }, "json");

      };

      function f_LoadingProcesos(_is_show){
        if (_is_show == 1){
          $("#wt_proceso").show();
        }
        else{
          $("#wt_proceso").hide();
        }
      }
    </script>

    <!-- Funciones Secundarias -->
    <script type="text/javascript">
      function f_SetButtons(_x){
        $("#btn_add_procesos_detalle").prop('disabled', true);
        $("#btn_add_procesos_detalle").css('background-color', '#BBBBBB');
        $("#btn_add_procesos_detalle").css('color', '#ffffff');
        $("#btn_add_procesos_detalle").removeClass('btn-primary');
        $("#btn_add_procesos_detalle").addClass('btn-secondary');

        $("#btn_add_procesos_detalle_tipo_dato").prop('disabled', true);
        $("#btn_add_procesos_detalle_tipo_dato").css('background-color', '#BBBBBB');
        $("#btn_add_procesos_detalle_tipo_dato").css('color', '#ffffff');
        $("#btn_add_procesos_detalle_tipo_dato").removeClass('btn-primary');
        $("#btn_add_procesos_detalle_tipo_dato").addClass('btn-secondary');

        if (_x == 1){
          $("#btn_add_procesos_detalle").prop('disabled', false);
          $("#btn_add_procesos_detalle").css('background-color', '');
          $("#btn_add_procesos_detalle").css('color', '');
          $("#btn_add_procesos_detalle").removeClass('btn-secondary');
          $("#btn_add_procesos_detalle").addClass('btn-primary');
        }

        if (_x == 2){
          $("#btn_add_procesos_detalle").prop('disabled', false);
          $("#btn_add_procesos_detalle").css('background-color', '');
          $("#btn_add_procesos_detalle").css('color', '');
          $("#btn_add_procesos_detalle").removeClass('btn-secondary');
          $("#btn_add_procesos_detalle").addClass('btn-primary');

          $("#btn_add_procesos_detalle_tipo_dato").prop('disabled', false);
          $("#btn_add_procesos_detalle_tipo_dato").css('background-color', '');
          $("#btn_add_procesos_detalle_tipo_dato").css('color', '');
          $("#btn_add_procesos_detalle_tipo_dato").removeClass('btn-secondary');
          $("#btn_add_procesos_detalle_tipo_dato").addClass('btn-primary');
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

      function f_LoadingTipoDato(_is_show){
        if (_is_show == 1){
          $("#wt_tipo_dato").show();
        }
        else{
          $("#wt_tipo_dato").hide();
        }
      }

    </script>

    <!-- Funciones de Grabación -->
    <script type="text/javascript">
      function f_GrabarProceso(){
        var _id_proceso = $("#id_proceso").val();
        var _item_proceso = $("#item_proceso").val();
        var _modo = $("#modo_grabarproceso").val();

        var _proceso_area = $("#proceso_area").val();
        var _proceso_origendato = $("#proceso_origendato").val();
        var _proceso_descripcion = $("#proceso_descripcion").val();

        var _html = '';

        if (_proceso_origendato == null || _proceso_origendato.length == 0){
          alert("Debe ingresar el Origen de dato.");

          return;
        }

        if (_proceso_area == null || _proceso_area.length == 0){
          alert("Debe seleccionar el Área.");

          return;
        }

        if (_proceso_descripcion == null || _proceso_descripcion.length == 0){
          alert("Debe ingresar la Descripción.");

          return;
        }

        if (_proceso_descripcion.indexOf('"') >= 0){
          alert("No se puede utilizar COMILLAS en la Descripción." + "\r\n" + "Por favor, corregir.");

          return;
        }

        if (_proceso_descripcion.indexOf("'") >= 0){
          alert("No se puede utilizar COMILLAS SIMPLES en la Descripción." + "\r\n" + "Por favor, corregir.");

          return;
        }

          // Grabando Datos
        $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso", id_proceso: _id_proceso, modo_grabar: _modo, proceso_area: _proceso_area,proceso_origendato: _proceso_origendato, proceso_descripcion: _proceso_descripcion},
          function( data ) {

            if(data.estado == 1){
              f_LoadProcesos();

              f_cerrarModal('modal_adminprocesos');
            }
            else{
              alert("Ocurrió un error al momento de grabar el Proceso");
            }
          }, "json");

      };

      function f_GrabarDetalle(){
        var _id_proceso = $("#input_value_proceso_id").val();
        var _id_proceso_detalle = $("#id_proceso_detalle").val();
        var _item_proceso_detalle = $("#item_proceso_detalle").val();

        var _is_primer_tramo = $("#checkbox_is_primer_tramo").is(':checked') ? 1 : 0;
        var _is_segundo_tramo = $("#checkbox_is_segundo_tramo").is(':checked') ? 1 : 0;

        var _modo = $("#modo_grabardetalle").val();

        var _proceso_detalle_tipocliente = $("#proceso_detalle_tipocliente").val();
        var _proceso_detalle_descripcion = $("#proceso_detalle_descripcion").val();

        var _proceso_detalle_rol = array_roles;

          //Roles a eliminar lógicamente
        var _proceso_detalle_rol_update = array_roles_update;


        var _html = '';

          // Validando datos
        if (_proceso_detalle_descripcion == null || _proceso_detalle_descripcion.length == 0){
          alert("Debe ingresar la Descripción.");

          return;
        }

        if (_proceso_detalle_descripcion.indexOf('"') >= 0){
          alert("No se puede utilizar COMILLAS en la Descripción." + "\r\n" + "Por favor, corregir.");

          return;
        }

        if (_proceso_detalle_descripcion.indexOf("'") >= 0){
          alert("No se puede utilizar COMILLAS SIMPLES en la Descripción." + "\r\n" + "Por favor, corregir.");

          return;
        }

          // Grabando Datos
        $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle", id_proceso_detalle: _id_proceso_detalle, modo_grabar: _modo, id_proceso: _id_proceso, proceso_detalle_tipocliente: _proceso_detalle_tipocliente, proceso_detalle_descripcion: _proceso_detalle_descripcion, proceso_detalle_rol: _proceso_detalle_rol, proceso_detalle_rol_update:_proceso_detalle_rol_update,
          is_primer_tramo: _is_primer_tramo, is_segundo_tramo: _is_segundo_tramo
         },
          function( data ) {

            if(data.estado == 1){
              f_LoadProcesosDetalles(itemproceso_Selected, idproceso_Selected, itemprocesodescripcion_Selected,itemprocesoorigendato_Selected);

              f_cerrarModal('modal_adminprocesosdetalles');
            }
            else{
              alert("Ocurrió un error al momento de grabar el Detalle");
            }
          }, "json");

      };

      function f_EliminarDetalleRol(_id_rol){
        var index_eliminar = array_roles.findIndex(item => item.id_rol == _id_rol);
        console.log(index_eliminar);
        array_roles.splice(index_eliminar, 1);

        var table = document.getElementById("tbl_detalles_roles");

        //Cargar los datos en la tabla detalle rol
        $("#tbl_detalles_roles").html('');

        for (var i = 0; i < array_roles.length; i++) {
          row = table.insertRow();
          row.id = 'proceso_detalle_rol_id_'+array_roles[i].id_rol;
          cell_0 = row.insertCell(0);
          cell_0.innerHTML = (i+1);

          cell_1 = row.insertCell(1);
          cell_1.innerHTML = '<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarDetalleRol(\''+array_roles[i].id_rol+'\')">X</label>';
          cell_2 = row.insertCell(2);
          cell_2.innerHTML = array_roles[i].descripcion_rol;
        }

      };

      function f_GrabarDetalleRol(){
        var _item_proceso_detalle = $("#item_proceso_detalle").val();

        var _proceso_detalle_rol_id = $("#proceso_detalle_rol").val();
        var _proceso_detalle_rol_nombre = $("#proceso_detalle_rol option:selected").text();


        var _html = '';

        // Validando datos
        if (_proceso_detalle_rol_id == null || _proceso_detalle_rol_id.length == 0){
          alert("Debe ingresar el Rol.");

          return;
        }


        var seleccion_id_rol=[]
        seleccion_id_rol.push(_proceso_detalle_rol_id);

        //Validar si el rol seleccionado existe
        var filtro_array_roles = array_roles.filter( i => seleccion_id_rol.includes( i.id_rol ) );

        if(filtro_array_roles.length>0){
          alert("Ya existe el rol seleccionado.");
          return;
        }else{
          array_roles.push({'id_rol':_proceso_detalle_rol_id, 'descripcion_rol':_proceso_detalle_rol_nombre});
        }

        var table = document.getElementById("tbl_detalles_roles");

        //Cargar los datos en la tabla detalle rol
        $("#tbl_detalles_roles").html('');

        for (var i = 0; i < array_roles.length; i++) {
          row = table.insertRow();
          row.id = 'proceso_detalle_rol_id_'+array_roles[i].id_rol;
          cell_0 = row.insertCell(0);
          cell_0.innerHTML = (i+1);

          cell_1 = row.insertCell(1);
          cell_1.innerHTML = '<label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px;  padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;" onclick="f_EliminarDetalleRol(\''+array_roles[i].id_rol+'\')">X</label>';

          cell_2 = row.insertCell(2);
          cell_2.innerHTML = array_roles[i].descripcion_rol;
        }

        f_cerrarModal('modal_adminprocesosdetallesroles');

      };

      function f_GrabarTipoDato(){
        var _id_proceso_detalle = $("#input_value_proceso_detalle_id").val();
        var _id_proceso_detalle_tipo_dato = $("#id_proceso_detalle_tipo_dato").val();
        var _item_proceso_detalle_tipo_dato = $("#item_proceso_detalle_tipo_dato").val();

        var _modo = $("#modo_grabartipodato").val();

        var _proceso_detalle_tipo_dato_tipodato = $("#proceso_detalle_tipo_dato_tipodato").val();
        var _proceso_detalle_tipo_dato_correo = $("#proceso_detalle_tipo_dato_correo").val();
        var _proceso_detalle_tipo_dato_etiqueta = $("#proceso_detalle_tipo_dato_etiqueta").val();

        var _proceso_detalle_tipo_dato_link_referencia = $("#proceso_detalle_tipo_dato_link_referencia").val();

        var _html = '';

          // Validando datos
        if (_proceso_detalle_tipo_dato_etiqueta== null || _proceso_detalle_tipo_dato_etiqueta.length == 0){
          alert("Debe seleccionar la Etiqueta.");

          return;
        }


        if (_proceso_detalle_tipo_dato_tipodato == null || _proceso_detalle_tipo_dato_tipodato.length == 0){
          alert("Debe seleccionar el Tipo de Dato.");

          return;
        }
        var es_correo_alerta = 0;


        var tiene_alerta = $('#proceso_detalle_tipo_dato_tipodato').find('option:selected').attr('data-tiene-alerta');


        if(tiene_alerta == 1){
          if (_proceso_detalle_tipo_dato_correo.length > 0){
            if (!f_CheckEMail('proceso_detalle_tipo_dato_correo')){
              alert("El correo ingresado no tiene el formato correcto.");
              return;
            }
            es_correo_alerta=1;
          }else{
            alert("Debe ingresar el Correo.");
            return;
          }
        }


          // Grabando Datos
        $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle_tipo_dato", id_proceso_detalle_tipo_dato: _id_proceso_detalle_tipo_dato, modo_grabar: _modo, id_proceso_detalle: _id_proceso_detalle, proceso_detalle_tipo_dato_tipodato: _proceso_detalle_tipo_dato_tipodato, proceso_detalle_tipo_dato_es_alerta: es_correo_alerta, proceso_detalle_tipo_dato_correo: _proceso_detalle_tipo_dato_correo, proceso_detalle_tipo_dato_etiqueta: _proceso_detalle_tipo_dato_etiqueta, proceso_detalle_tipo_dato_link_referencia: _proceso_detalle_tipo_dato_link_referencia},
          function( data ) {

            if(data.estado == 1){
              f_LoadProcesosDetallesTiposDatos(itemdetalle_Selected, iddetalle_Selected, itemdetalledescripcion_Selected);

              f_cerrarModal('modal_adminprocesosdetallestiposdatos');
            }
            else{
              alert("Ocurrió un error al momento de grabar el Tipo de Dato");
            }
          }, "json");

      };

      function f_EliminarProceso(_id_proceso){
        if(confirm("¿Está seguro de eliminar el Proceso seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso", modo_grabar: 'E', id_proceso: _id_proceso },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesos();
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Proceso.");
              }

            }, "json");
        }
      };

      function f_EliminarDetalle(_id_proceso_detalle){
        if(confirm("¿Está seguro de eliminar el Detalle seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle", modo_grabar: 'E', id_proceso_detalle: _id_proceso_detalle },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesosDetalles(itemproceso_Selected, idproceso_Selected, itemprocesodescripcion_Selected,itemprocesoorigendato_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Detalle.");
              }

            }, "json");
        }
      };

      function f_EliminarTipoDato(_id_proceso_detalle_tipo_dato){
        if(confirm("¿Está seguro de eliminar el Tipo de Dato seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle_tipo_dato", modo_grabar: 'E', id_proceso_detalle_tipo_dato: _id_proceso_detalle_tipo_dato },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesosDetallesTiposDatos(itemdetalle_Selected, iddetalle_Selected, itemdetalledescripcion_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Detalle.");
              }

            }, "json");
        }
      };

      function f_InactivarProceso(_id_proceso){
        if(confirm("¿Está seguro de inactivar el Proceso seleccionado?\n\n ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso", modo_grabar: 'I', id_proceso: _id_proceso },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesos();
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Proceso.");
              }

            }, "json");
        }
      };

      function f_ActivarProceso(_id_proceso){
        if(confirm("¿Está seguro de activar el Proceso seleccionado?\n\n ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso", modo_grabar: 'A', id_proceso: _id_proceso },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesos();
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Proceso.");
              }

            }, "json");
        }
      };

      function f_InactivarDetalle(_id_proceso_detalle){
        if(confirm("¿Está seguro de inactivar el Detalle seleccionado?\n\n ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle", modo_grabar: 'I', id_proceso_detalle: _id_proceso_detalle },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesosDetalles(itemproceso_Selected, idproceso_Selected, itemprocesodescripcion_Selected,itemprocesoorigendato_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Detalle.");
              }

            }, "json");
        }
      };

      function f_ActivarDetalle(_id_proceso_detalle){
        if(confirm("¿Está seguro de activar el Detalle seleccionado?\n\n ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle", modo_grabar: 'A', id_proceso_detalle: _id_proceso_detalle },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesosDetalles(itemproceso_Selected, idproceso_Selected, itemprocesodescripcion_Selected,itemprocesoorigendato_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Detalle.");
              }

            }, "json");
        }
      };

      function f_InactivarTipoDato(_id_proceso_detalle_tipo_dato){
        if(confirm("¿Está seguro de inactivar el Detalle seleccionado?\n\n ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle_tipo_dato", modo_grabar: 'I', id_proceso_detalle_tipo_dato: _id_proceso_detalle_tipo_dato },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesosDetallesTiposDatos(itemdetalle_Selected, iddetalle_Selected, itemdetalledescripcion_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Detalle.");
              }

            }, "json");
        }
      };


      function f_ActivarTipoDato(_id_proceso_detalle_tipo_dato){
        if(confirm("¿Está seguro de activar el Tipo de Dato seleccionado?\n\n ¿Desea continuar?")){
          $.post( "apis/backend.php", { accion: "grabar_controlinterno_proceso_detalle_tipo_dato", modo_grabar: 'A', id_proceso_detalle_tipo_dato: _id_proceso_detalle_tipo_dato },
            function( data ) {
              if(data.estado == 1){
                f_LoadProcesosDetallesTiposDatos(itemdetalle_Selected, iddetalle_Selected, itemdetalledescripcion_Selected);
              }
              else{
                alert("Ocurrió un error al momento de eliminar el Detalle.");
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