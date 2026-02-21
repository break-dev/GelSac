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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

  <!-- Íconos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <link rel="stylesheet" href="<?php echo $url_lims ?>/global/styles.css">

  <title><?php echo $nom_app; ?> | Recepción de Unidades</title>

  <script type="text/javascript">
    var is_mobile = 0;
  </script>
</head>

<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
  <div class="container-fluid">
    <div class="row">
      <!-- Llamando a Navbar -->
      <?php echo $navbar_maintop; ?>

      <!-- Modal (Menú Lateral) -->
      <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"
              style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0px !important;">
              <ul class="list-unstyled">
                <div id="div_menu1"></div>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal (Filtro Lateral) -->
      <div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-right" style="margin-top: 0px !important; margin-left: 0px !important;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="menuModalLabel">Filtro de Opciones</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 0px !important;">

              <div class="row"
                style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                  <div
                    style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                      <h6 style="font-size: 14px;">Por Fechas</h6>
                    </div>

                    <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                      <hr style="border-color: #D9D9D9;" />
                    </div>

                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input id="fecha_inicio" type="date" class="form-control"
                          style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>"
                          onchange="f_LoadFiltroClientes();">
                      </div>
                      <br><br>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input id="fecha_fin" type="date" class="form-control"
                          style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>"
                          onchange="f_LoadFiltroClientes();">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                  <div
                    style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                      <h6 style="font-size: 14px;">Condición Ingreso</h6>
                    </div>

                    <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                      <hr style="border-color: #D9D9D9;" />
                    </div>

                    <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                      <div class="flex-fill">
                        <select id="filtro_condicioningreso" class="form-select" data-placeholder="Elija una opción..."
                          data-titulo="Condición Ingeso" onclick="f_ShowListaModal(this);" style="font-size: 14px;">
                          <option selected value="">Elija una opción...</option>

                          <?php

                          $t = 1;

                          $q_tipoingreso = "SELECT Id,
                                                     descripcion
                                                FROM tbconfig_tipoingresounidades
                                               WHERE estado = 'A'
                                              ORDER BY is_predeterminado DESC";

                          if ($res_tipoingreso = mysqli_query($enlace, $q_tipoingreso)) {
                            if (mysqli_num_rows($res_tipoingreso) > 0) {
                              while ($row_tipoingreso = mysqli_fetch_array($res_tipoingreso)) {
                                ?>

                                <option value="<?php echo $row_tipoingreso["Id"]; ?>">
                                  <?php echo $row_tipoingreso["descripcion"]; ?>
                                </option>

                                <?php

                                $t++;
                              }
                            }
                          }

                          ?>

                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                  <div
                    style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                      <h6 style="font-size: 14px;">Transportista</h6>
                    </div>

                    <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                      <hr style="border-color: #D9D9D9;" />
                    </div>

                    <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                      <div class="flex-fill">
                        <select id="filtro_transportista" class="form-select" data-placeholder="Elija una opción..."
                          style="text-align: left; font-size: 14px;" data-titulo="Empresas de Transporte"
                          onclick="f_ShowListaModal(this);">

                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                  <div
                    style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                      <h6 style="font-size: 14px;">Placa</h6>
                    </div>

                    <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                      <hr style="border-color: #D9D9D9;" />
                    </div>

                    <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                      <input id="filtro_placa" type="text" class="form-control" style="font-size: 14px;">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-secondary" type="button" onclick="f_LoadResultados();"
                    style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
                    <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
                  </button>
                </div>
                <br><br>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-success" type="button" onclick="f_ExportToExcel();"
                    style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
                    <b>Exportar a Excel</b>
                  </button>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>

      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12"
          style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
          <div class="d-flex row">
            <div class="row"
              style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
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

            <div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
              <div class="row" style="padding: 20px;">
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <div class="d-flex">
                    <h5>Resumen de Unidades</h5>

                    <div id="wt_resumen" class=""
                      style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                      <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                      <label style="font-style: italic;"> Cargando datos...</label>
                    </div>
                  </div>
                </div>

                <div class="col-md-2 col-sm-2 col-xs-12" style="margin-top: -5px;">
                  <button class="btn btn-primary" type="button" onclick="f_AdminRecepcion('x');"
                    style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -5px;">
                    <b>+ Nueva Recepción</b>
                  </button>
                </div>
              </div>

              <div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
                <hr style="border-color: #D9D9D9;" />
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12"
                style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr style="font-size: 12px;">
                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                        N°
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                        Fecha Hora Ingreso
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                        Condición
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                        N° Placa 1
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                        N° Placa 2 (Remolque)
                      </th>

                      <th colspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
                        Emp. de Transporte
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                        Tipo Vehículo
                      </th>

                      <th colspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Info. Conductor
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
                        Tipo Carga
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;"
                        hidden>
                        Zona Origen
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
                        Observación
                      </th>

                      <th rowspan="2"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;"
                        hidden>
                        Ingresó con Vehículo Particular
                      </th>

                      <th colspan="3"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
                        Información de Salida de Unidad
                      </th>
                    </tr>

                    <tr style="font-size: 12px;">
                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        DNI / RUC
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
                        Razón Social
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Licencia
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
                        Nombres
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                        Fecha Hora
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">
                        Estado Unidad
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
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

  <!-- Ventanas modales -->
  <div class="modal fade" id="modal_addrecepcion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_addrecepcionLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modal_addrecepcionLabel">Nueva Recepción de Unidad</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="div_recepcion1">
            <div class="row"
              style="padding: 5px; background-color: #f0efe8; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Condición:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="registro_condicion" class="form-select" data-placeholder="Elija una opción..."
                  onchange="f_LoadListaTipoCarga(); f_ShowTipoCarga(); f_ShowListaPlacasDespacho();"
                  data-titulo="Condiciones" onclick="f_ShowListaModal(this);">
                  <option selected value="">Seleccione una opción...</option>

                  <?php

                  $t = 1;

                  $q_tipocarga = "SELECT Id,
                                           descripcion
                                      FROM tbconfig_tipoingresounidades
                                     WHERE estado = 'A'
                                    ORDER BY is_predeterminado DESC";

                  if ($res_tipocarga = mysqli_query($enlace, $q_tipocarga)) {
                    if (mysqli_num_rows($res_tipocarga) > 0) {
                      while ($row_tipocarga = mysqli_fetch_array($res_tipocarga)) {
                        ?>

                        <option value="<?php echo $row_tipocarga["Id"]; ?>"><?php echo $row_tipocarga["descripcion"]; ?>
                        </option>

                        <?php

                        $t++;
                      }
                    }
                  }

                  ?>

                </select>
              </div>
            </div>

            <div id="div_FechasDespacho" class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Fecha Despacho:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="registro_fechadespacho" type="date" class="form-control"
                  style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>"
                  onchange="f_ShowListaPlacasDespacho();">
              </div>
            </div>

            <div id="div_PlacasDespacho" class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Placa 1:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="registro_placasdespacho" class="form-select" data-placeholder="Elija una opción..."
                  onchange="f_ShowPlacaNoExiste();">

                </select>
              </div>
            </div>

            <div id="div_PlacaIngreso" class="row" style="padding: 5px;">
              <div id="lbl_PlacaIngreso" class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Placa 1:
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="d-flex">
                  <div class="col-md-5 col-sm-5 col-xs-5">
                    <input id="registro_placa1" type="text" class="form-control"
                      style="text-align: center; text-transform: uppercase;" placeholder="ABC"
                      onkeyup="f_KeyUpPlaca();">
                  </div>

                  <div class="col-md-1 col-sm-1 col-xs-1">
                    <label style="font-weight: bold; margin-left: 5px; margin-top: 5px;">-</label>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-6">
                    <input id="registro_placa2" type="text" class="form-control"
                      style="text-align: center; margin-left: 2px;" placeholder="111" onkeyup="f_KeyUpPlaca();">
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Transportista:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="registro_transportista" class="form-select" onclick="f_ShowListaModal(this);">

                </select>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Tipo Vehículo:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="registro_tipovehiculo" class="form-select" data-placeholder="Elija una opción..."
                  onchange="f_TieneCarreta();" data-titulo="Empresas de Transporte" onclick="f_ShowListaModal(this);">
                  <option selected value="">Seleccione una opción...</option>

                  <?php

                  $t = 1;

                  $q_tipovehiculo = "SELECT Id,
                                              UPPER(descripcion) AS descripcion,
                                              tiene_carreta
                                         FROM tbconfig_tipovehiculo
                                        WHERE estado = 'A'
                                       ORDER BY descripcion";

                  if ($res_tipovehiculo = mysqli_query($enlace, $q_tipovehiculo)) {
                    if (mysqli_num_rows($res_tipovehiculo) > 0) {
                      while ($row_tipovehiculo = mysqli_fetch_array($res_tipovehiculo)) {
                        ?>

                        <option value="<?php echo $row_tipovehiculo["Id"] . '|' . $row_tipovehiculo["tiene_carreta"]; ?>">
                          <?php echo $row_tipovehiculo["descripcion"]; ?>
                        </option>

                        <?php

                        $t++;
                      }
                    }
                  }

                  ?>

                </select>
              </div>
            </div>

            <div id="div_placa2" class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Placa 2:
              </div>

              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="d-flex">
                  <div class="col-md-5 col-sm-5 col-xs-5">
                    <input id="registro_placa1_2" type="text" class="form-control"
                      style="text-align: center; text-transform: uppercase;" placeholder="ABC"
                      onkeyup="f_KeyUpPlaca2();">
                  </div>

                  <div class="col-md-1 col-sm-1 col-xs-1">
                    <label style="font-weight: bold; margin-left: 5px; margin-top: 5px;">-</label>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-6">
                    <input id="registro_placa2_2" type="text" class="form-control"
                      style="text-align: center; margin-left: 2px;" placeholder="111">
                  </div>

                  <div class="col-md-5 col-sm-5 col-xs-5">
                    <label style="margin-left: 5px; margin-top: 10px; font-size: 14px;"><i>(Remolque)</i></label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Conductor:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="d-flex">
                  <div class="flex-fill" style="max-width: 90%">
                    <select id="registro_conductor" class="form-select" data-placeholder="Elija una opción..."
                      data-titulo="Empresas de Transporte" onclick="f_ShowListaModal(this);">

                    </select>
                  </div>

                  <div class="col-md-2 col-sm-2 col-xs-2">
                    <button type="button" class="btn" onclick="f_AddConductor();"
                      style="padding: 0px; margin-left: 10px;">
                      <img src="<?php echo $btn_add; ?>" style="width: 35px;">
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div id="div_tipocarga" class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Tipo Carga:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <select id="registro_tipocarga" class="form-select" data-placeholder="Elija una opción..."
                  data-titulo="Empresas de Transporte" onclick="f_ShowListaModal(this);">

                </select>
              </div>
            </div>

            <div id="div_zonaorigen" class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Zona Origen:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="d-flex">
                  <div class="flex-fill" style="max-width: 90%">
                    <select id="registro_zonaorigen" class="form-select" data-placeholder="Elija una opción...">

                    </select>
                  </div>

                  <div class="col-md-2 col-sm-2 col-xs-2">
                    <button type="button" class="btn" onclick="f_AddZonaOrigen();"
                      style="padding: 0px; margin-left: 10px;">
                      <img src="<?php echo $btn_add; ?>" style="width: 35px;">
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Observación:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <textarea id="registro_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2"
                  style="text-transform: uppercase;"></textarea>
              </div>
            </div>
          </div>

          <div id="div_recepcion2" style="display: none;">
            <div class="row" style="padding: 5px;">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr style="font-size: 12px;">
                    <th colspan="6"
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                      Registro de Acompañantes
                    </th>
                  </tr>

                  <tr style="font-size: 12px;">
                    <th colspan="2"
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      N°
                    </th>

                    <th
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      DNI
                    </th>

                    <th
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Nombres
                    </th>

                    <th colspan="2"
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Foto Documento
                    </th>
                  </tr>
                </thead>

                <tbody id="tbl_acompanantes">

                </tbody>
              </table>
            </div>
          </div>

          <div id="div_recepcion3" style="display: none;">
            <div class="row"
              style="padding: 5px; background-color: #f0efe8; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;"
              hidden>
              <div class="form-check">
                <input id="chk_vehiculoparticular" class="form-check-input" type="checkbox">
                <label class="form-check-label" for="chk_vehiculoparticular">
                  Ingresa con Vehículo Particular
                </label>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr style="font-size: 12px;">
                    <th colspan="5"
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                      Registro de Imágenes
                    </th>
                  </tr>

                  <tr style="font-size: 12px;">
                    <th colspan="2"
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      N°
                    </th>

                    <th
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 200px;">
                      Descripción
                    </th>

                    <th colspan="2"
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Imagen
                    </th>
                  </tr>
                </thead>

                <tbody id="tbl_imagenes">

                </tbody>
              </table>
            </div>
          </div>
        </div>

        <input id="hd_idregistro" type="hidden">
        <input id="hd_modograbar" type="hidden">

        <div class="modal-footer">
          <div id="wt_grabarregistro" class=""
            style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
            <label style="font-style: italic;"> Grabando datos...</label>
          </div>

          <button type="button" class="btn btn-secondary wt_grabarregistro_button" data-bs-dismiss="modal"
            style="font-size: 14px;">Cerrar</button>
          <button id="btn_ConfirmarAcompanantes" type="button" class="btn btn-success wt_grabarregistro_button"
            style="font-size: 14px;" onclick="f_GrabarRecepcion_Confirmar();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_addcliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_addclienteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div id="modal_addcliente_content" class="modal-content" style="margin-top: 250px;">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_addclienteLabel">Nuevo Cliente</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Tipo Cliente:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="cliente_tipocliente" class="form-select" style="text-align: left;"
                onchange="f_GetListaTipoDocumento(0)">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_tipocliente = "SELECT Id,
                                           descripcion
                                      FROM tbconfig_tipocliente
                                     WHERE estado = 'A'";

                if ($res_tipocliente = mysqli_query($enlace, $q_tipocliente)) {
                  if (mysqli_num_rows($res_tipocliente) > 0) {
                    while ($row_tipocliente = mysqli_fetch_array($res_tipocliente)) {
                      ?>

                      <option value="<?php echo $row_tipocliente["Id"]; ?>"><?php echo $row_tipocliente["descripcion"]; ?>
                      </option>

                      <?php
                    }
                  }
                }

                ?>

              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Tipo Documento:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="cliente_tipodocumento" class="form-select" style="text-align: left;">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_tipodocumento = "SELECT Id,
                                             descripcion
                                        FROM tbconfig_tipodocumento
                                       WHERE estado = 'A'";

                if ($res_tipodocumento = mysqli_query($enlace, $q_tipodocumento)) {
                  if (mysqli_num_rows($res_tipodocumento) > 0) {
                    while ($row_tipodocumento = mysqli_fetch_array($res_tipodocumento)) {
                      ?>

                      <option value="<?php echo $row_tipodocumento["Id"]; ?>"><?php echo $row_tipodocumento["descripcion"]; ?>
                      </option>

                      <?php
                    }
                  }
                }

                ?>

              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Documento:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="cliente_documento" type="number" class="form-control col-md-12 col-xs-12"
                style="text-align: center;" onkeyup="f_GetInfoCliente(1);">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Razón Social: <img id="wt_razonsocial2" src="<?php echo $img_waiting ?>"
                style="width: 35px; display: none;">
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <textarea id="cliente_razonsocial" type="text" class="form-control col-md-12 col-xs-12" rows="2"
                style="text-transform: uppercase;"></textarea>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Teléfonos:
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
              <input id="cliente_telefono1" type="number" class="form-control col-md-12 col-xs-12"
                style="text-align: center;">
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4">
              <input id="cliente_telefono2" type="number" class="form-control col-md-12 col-xs-12"
                style="text-align: center;">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Correo:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="cliente_correo" type="email" class="form-control col-md-12 col-xs-12">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Dirección:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <textarea id="cliente_direccion" type="text" class="form-control col-md-12 col-xs-12" rows="2"
                style="text-transform: uppercase;"></textarea>
            </div>
          </div>
        </div>

        <input id="hd_idcliente" type="hidden">
        <input id="hd_modograbar" type="hidden">

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarCliente();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_addconductor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_addconductorLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div id="modal_addconductor_content" class="modal-content" style="margin-top: 346px;">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_addconductorLabel">Nuevo Conductor</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Tipo Documento:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="conductor_tipodocumento" class="form-select" style="text-align: left;">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_tipodocumento = "SELECT Id,
                                             descripcion
                                        FROM tbconfig_tipodocumento
                                       WHERE estado = 'A'
                                         AND is_conductor = 1";

                if ($res_tipodocumento = mysqli_query($enlace, $q_tipodocumento)) {
                  if (mysqli_num_rows($res_tipodocumento) > 0) {
                    while ($row_tipodocumento = mysqli_fetch_array($res_tipodocumento)) {
                      ?>

                      <option value="<?php echo $row_tipodocumento["Id"]; ?>"><?php echo $row_tipodocumento["descripcion"]; ?>
                      </option>

                      <?php
                    }
                  }
                }

                ?>

              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              N° Documento:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="conductor_dni" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;"
                onkeyup="f_GetInfoCliente(2);">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              N° Licencia:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="conductor_licencia" type="text" class="form-control col-md-12 col-xs-12"
                style="text-align: center;">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Nombres: <img id="wt_conductor" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="conductor_nombres" type="text" class="form-control col-md-12 col-xs-12"
                style="text-align: center; text-transform: uppercase;">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarConductor();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_addzonaorigen" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_addzonaorigenLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div id="modal_addzonaorigen_content" class="modal-content" style="margin-top: 394px;">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_addzonaorigenLabel">Nueva Zona de Origen</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Zona Origen:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="zona_origen" type="text" class="form-control col-md-12 col-xs-12"
                style="text-align: center; text-transform: uppercase;">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarZonaOrigen();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_addacompanante" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_addacompananteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div id="modal_addacompanante_content" class="modal-content" style="margin-top: 225px;">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_addacompananteLabel">Nuevo Acompañante</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              DNI:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="acompanante_dni" type="text" class="form-control col-md-12 col-xs-12"
                style="text-align: center;" onkeyup="f_GetInfoCliente(3);">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Nombres: <img id="wt_acompanante" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="acompanante_nombres" type="text" class="form-control col-md-12 col-xs-12"
                style="text-align: center; text-transform: uppercase;">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarAcompanante();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_addimagenadicional" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_addimagenadicionalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div id="modal_addimagenadicional_content" class="modal-content" style="margin-top: 225px;">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_addimagenadicionalLabel">Nueva Imagen</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Descripción:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <input id="imagenadicional_descripcion" type="text" class="form-control col-md-12 col-xs-12"
                style="text-align: center; text-transform: uppercase;">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="f_GrabarImagenAdicional();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_showinfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_showinfoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5">Información de: </h1>
          <h1 class="modal-title fs-5" id="modal_showinfoLabel" style="margin-left: 10px;"></h1>

          <div id="wt_info" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
            <label style="font-style: italic;"> Cargando imagen...</label>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div>
            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Ingreso Planta:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="info_ingreso" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Condición:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="info_condicion" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Placa 1:
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <input id="info_placa1" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Transportista:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="info_transportista_documento" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled> <br>

                <textarea id="info_transportista" type="text" class="form-control col-md-12 col-xs-12" rows="2"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold; margin-top: -20px;"
                  disabled></textarea>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Tipo Vehículo:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="info_tipovehiculo" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div id="div_placa2_info" class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Placa 2:
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <input id="info_placa2" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Conductor:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="info_conductor" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Tipo Carga:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="info_tipocarga" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div id="div_zonaorigen_info" class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Zona Origen:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="info_zonaorigen" type="text" class="form-control"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
                Observación:
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <textarea id="info_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2"
                  style="font-size: 14px; text-transform: uppercase; font-weight: bold;" disabled></textarea>
              </div>
            </div>

            <div class="row" style="padding: 5px; display: none;">
              <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">

              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <input id="chk_tienevehiculoparticular" class="form-check-input obj_cab" type="checkbox" disabled>
                <label class="form-check-label" for="chk_tienevehiculoparticular">
                  Ingresó con Vehículo Particular
                </label>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr style="font-size: 12px;">
                      <th colspan="3"
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                        Información de Salida de Unidad
                      </th>
                    </tr>

                    <tr style="font-size: 12px;">
                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Fecha Hora
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Estado Unidad
                      </th>

                      <th
                        style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Observación
                      </th>
                    </tr>
                  </thead>

                  <tbody id="tbl_infosalidas">

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <input id="hd_idregistro" type="hidden">
        <input id="hd_modograbar" type="hidden">

        <div class="modal-footer">
          <div id="wt_grabarregistro" class=""
            style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
            <label style="font-style: italic;"> Grabando datos...</label>
          </div>

          <button type="button" class="btn btn-secondary wt_grabarregistro_button" data-bs-dismiss="modal"
            style="font-size: 14px;">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_registrosalida" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_registrosalidaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div id="modal_registrosalida_content" class="modal-content" style="margin-top: 100px;">
        <div class="modal-header" style="background-color: #dc3545;">
          <h1 class="modal-title fs-5" style="color: #ffffff;">Registro de Salida: </h1>
          <h1 class="modal-title fs-5" id="modal_registrosalidaLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Estado Unidad:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <select id="salida_estado" class="form-select" style="text-align: left;">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_estadossalida = "SELECT Id,
                                             descripcion
                                        FROM tbconfig_estadosalidaunidades
                                       WHERE estado = 'A'";

                if ($res_estadossalida = mysqli_query($enlace, $q_estadossalida)) {
                  if (mysqli_num_rows($res_estadossalida) > 0) {
                    while ($row_estadossalida = mysqli_fetch_array($res_estadossalida)) {
                      ?>

                      <option value="<?php echo $row_estadossalida["Id"]; ?>"><?php echo $row_estadossalida["descripcion"]; ?>
                      </option>

                      <?php
                    }
                  }
                }

                ?>

              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
              Observación:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-8">
              <textarea id="salida_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2"
                style="text-transform: uppercase;"></textarea>
            </div>
          </div>
        </div>

        <input id="hd_idregistrosalida" type="hidden">
        <input id="hd_iddistribucion_salida" type="hidden">

        <div class="modal-footer">
          <div id="wt_grabarsalida" class=""
            style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
            <label style="font-style: italic;"> Grabando datos...</label>
          </div>

          <button type="button" class="btn btn-secondary wt_grabarsalida_button" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary wt_grabarsalida_button"
            onclick="f_RegistroSalida_Confirmar();">Grabar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_showdocumentoacompanante" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="modal_showdocumentoacompananteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div id="modal_showdocumentoacompanante_content" class="modal-content">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_showdocumentoacompananteLabel"></h1>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <img id="img_documentoacompanante" alt="">

            <div id="wt_documentoacompanante" class=""
              style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
              <img src="<?php echo $img_waiting ?>" style="width: 20px;">
              <label style="font-style: italic;"> Cargando imagen...</label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_showimagenes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_showimagenesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div id="modal_showimagenes_content" class="modal-content">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_showimagenesLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <img id="img_imagenes" alt="">

            <div id="wt_imagenes" class=""
              style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
              <img src="<?php echo $img_waiting ?>" style="width: 20px;">
              <label style="font-style: italic;"> Cargando imagen...</label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!--Modal de Imágenes Carousel-->
  <div class="modal fade" id="modal_showimagenescarousel" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="modal_showimagenescarouselLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div id="modal_showimagenes_content" class="modal-content">
        <div class="modal-header" style="background-color: #f8da62;">
          <h1 class="modal-title fs-5" id="modal_showimagenescarouselLabel"></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">

            <div id="div_imagenes_adicionales"> </div>

            <div id="wt_imagenes" class=""
              style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
              <img src="<?php echo $img_waiting ?>" style="width: 20px;">
              <label style="font-style: italic;"> Cargando imagen...</label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Referenciando a JQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>

  <!-- Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- ECharts -->
  <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

  <!-- Referenciando auxiliares -->
  <?php include('global/auxiliares_js.php'); ?>
  <script src="./recepcion_unidades.js?v=<?= time() ?>"></script>


</body>

</html>