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

  <title><?php echo $nom_app; ?> | Gestionar Guías</title>

  <script type="text/javascript">
    var is_mobile = 0;
    var idmodalidadenvio_selected = 3; // Comercializadora
    var iddestino_selected = 21; // 21: GRUPO EMPRESARIAL LUBRA S.A.C. tbconfig_destinatarios
    var puntodestino_selected = 'OTR.VALLE MOCHE LOTE. VD SEC. VALDIVIA ALTA (190-III)'; // Dirección de GELSAC
    var motivotraslado_selected = "VENTA SUJETA A CONFIRMACIÓN"
  </script>

  <style>
    .table-container {
      max-width: 100%;
      overflow-x: scroll;
    }

    .sticky {
      position: sticky;
      left: 0;
      z-index: 1000;
    }

    .sticky-2 {
      position: sticky;
      left: 35;
      z-index: 1000;
    }

    .sticky-3 {
      position: sticky;
      left: 35;
      z-index: 1000;
    }

    .sticky-4 {
      position: sticky;
      left: 140;
      z-index: 1000;
    }

    .sticky-5 {
      position: sticky;
      left: 270;
      z-index: 1000;
    }

    .sticky-2h {
      position: sticky;
      left: 35;
      z-index: 1000;
    }

    .sticky-3h {
      position: sticky;
      left: 140;
      z-index: 1000;
    }

    .sticky-4h {
      position: sticky;
      left: 270;
      z-index: 1000;
    }

    .resaltado-movido {
      background-color: #fff3cd !important;
      /* Amarillo suave */
      transition: background-color 1s ease;
    }
  </style>
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

      <!-- Modal (Menú Lateral) -->
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
                <div
                  style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                  <div class="row" style="padding-left: 10px; padding-right: 10px;">
                    <h6 style="font-size: 14px;">
                      Fecha Ingreso a Balanza
                    </h6>
                  </div>

                  <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                    <hr style="border-color: #D9D9D9;" />
                  </div>

                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <input id="fecha_inicio" type="date" class="form-control"
                        style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
                    </div>
                    <br><br>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <input id="fecha_fin" type="date" class="form-control"
                        style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
                    </div>

                  </div>
                </div>
              </div>

              <div class="row"
                style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                  <div
                    style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                      <h6 style="font-size: 14px;">Por Guía Remitente:</h6>
                    </div>
                    <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                      <hr style="border-color: #D9D9D9;" />
                    </div>
                    <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                      <select id="filtro_guiaremitente" class="form-control" multiple
                        data-placeholder="Elija una o más opciones..."
                        style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
                        <?php

                        $q_guiaremitente = "SELECT DISTINCT CONCAT(guiaremitente_serie,'-',guiaremitente_numero) AS guia_remitente
																						FROM despachos_primertramo_validaciondatos
																					ORDER BY 1 DESC";

                        if ($res_guiaremitente = mysqli_query($enlace, $q_guiaremitente)) {
                          if (mysqli_num_rows($res_guiaremitente) > 0) {
                            while ($row_guiaremitente = mysqli_fetch_array($res_guiaremitente)) {
                              ?>

                              <option value="<?php echo $row_guiaremitente["guia_remitente"]; ?>">
                                <?php echo $row_guiaremitente["guia_remitente"]; ?>
                              </option>

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

              <div class="row" style="padding-left: 10px;margin-top: 30px;font-size: 13px;padding-right: 10px;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-secondary" type="button" onclick="f_LoadResultados();"
                    style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
                    <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
                  </button>
                </div>
                <br><br>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <!-- <button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
                      <b>Exportar a Excel</b>
                    </button> -->
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

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

          <div class="row" style="padding: 0px;">
            <div id="div_detalle" class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
              <div class="row"
                style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-left: 0px; margin-right: 0px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                  <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <div class="d-flex">
                        <div class="d-flex flex-fill">
                          <h5>Resumen </h5>

                          <div id="wt_resumen" class=""
                            style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando datos...</label>
                          </div>

                          <div id="wt_saving" class=""
                            style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Grabando datos...</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-xs-12">
                      <div class="d-flex justify-content-end">
                        <button id="btn_AddDistribucion" type="button" class="btn btn-primary"
                          style="font-size: 14px; margin-top: -6px;" onclick="f_AddGuia();">+ Generar Guía</button>
                      </div>
                    </div>
                  </div>
                </div>



                <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                  <hr style="border-color: #D9D9D9;" />
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12"
                  style="padding-left: 20px; padding-right: 20px; margin-top: 5px; width: 100%;">
                  <div class="table-container"
                    style="margin-top: 5px; overflow-x: scroll; width: 100%; height: auto; margin-bottom: 20px;">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr style="font-size: 12px;">
                          <th colspan="2" rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; min-width: 35px;">
                            N°
                          </th>

                          <th colspan="2" rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  min-width: 35px;">
                            Guía Remitente
                          </th>

                          <th colspan="2" rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  min-width: 35px;">
                            Guía Transportista
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  min-width: 35px;">
                            Planta Destino
                          </th>


                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  min-width: 35px;">
                            Fecha Guía
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
                            Lote
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
                            Cod. GEL
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 105px;">
                            Ticket Balanza
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
                            Fecha Llegada<br>(Planta)
                          </th>

                          <th colspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            Proveedor Minero
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
                            Concesión
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            Placa 1
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            Placa 2
                          </th>

                          <th colspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Emp. de Transporte
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            Tipo Vehículo
                          </th>

                          <th colspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            Info. Conductor
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            Tipo Carga
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            Zona Origen
                          </th>


                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
                            Encargado Muestra
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
                            Producto
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 200px;">
                            Tipo Mineral
                          </th>

                          <th rowspan="2"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
                            Observación
                          </th>

                          <th colspan="5"
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Información de Pesos (Kg)
                          </th>
                        </tr>

                        <tr style="font-size: 12px;">

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
                            DNI/RUC
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
                            Razón Social
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
                            DNI/RUC
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
                            Razón Social
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
                            Licencia
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
                            Nombres
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                            Fecha Peso Inicial
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
                            Fecha Peso Final
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Bruto
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Tara
                          </th>

                          <th
                            style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                            Neto
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
    </div>

  </div>

  <!-- Ventanas modales -->
  <div class="modal fade modal-dialog-scrollable" id="modal_adminguias" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminguiasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modal_adminguiasLabel">Generar Guía</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Fecha Inicio Traslado:
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <input id="guia_fechas" type="date" class="form-control" style="text-align: center; font-size: 14px;"
                value="<?php echo $g_date; ?>">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Fecha Emisión:
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <input id="guia_fechaemision" type="date" class="form-control"
                style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">
            </div>

            <input id="guia_horaemision" type="hidden" value="<?php echo substr($g_time, 0, 5); ?>">
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Fecha en Planta:
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <input id="guia_balanza_fecharegistro" type="date" class="form-control"
                style="text-align: center; font-size: 14px;">
            </div>

            <input id="guia_balanza_horaregistro" type="hidden">
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Planta de Destino:
            </div>

            <div class="col-md-5 col-sm-5 col-xs-12" style="margin-left: -20px;">
              <select id="planta_destino" class="form-control" style="font-size: 14px;">
                <option value="">Seleccione una planta...</option>
                <option value="1">Huanchaco</option>
                <option value="2">Laredo</option>
              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Proveedor:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_proveedor" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_lista = "SELECT Id,
                                documento,
                                UPPER(razon_social) AS razon_social
                              FROM tb_clientes
                              WHERE estado = 'A'
                                AND cod_clientecondicion = 1
                              ORDER BY razon_social";

                if ($res_lista = mysqli_query($enlace, $q_lista)) {
                  if (mysqli_num_rows($res_lista) > 0) {
                    while ($row_lista = mysqli_fetch_array($res_lista)) {
                      ?>

                      <option value="<?php echo $row_lista["Id"] ?>">
                        <?php echo $row_lista["documento"] . ' - ' . $row_lista["razon_social"] ?>
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
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Concesión:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_concesion" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>
              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Guía Remitente:
            </div>

            <div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
              <input id="guia_remitenteserie" type="text" class="form-control"
                style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Serie">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <input id="guia_remitentenumero" type="text" class="form-control"
                style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Guía">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Guía Transportista:
            </div>

            <div class="col-md-2 col-sm-2 col-xs-12" style="margin-left: -20px;">
              <input id="guia_transportistaserie" type="text" class="form-control guia_GRT"
                style="text-align: center; font-size: 14px; text-transform: uppercase;" placeholder="N° Serie">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <input id="guia_transportistanumero" type="text" class="form-control guia_GRT"
                style="text-align: center; font-size: 14px; text-transform: uppercase;;" placeholder="N° Guía">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px; margin-top: 7px;">
              <div class="form-check">
                <input id="chk_SinGRT" class="form-check-input" type="checkbox" onchange="f_DisabledGRT();">
                <label class="form-check-label" for="chk_SinGRT">
                  Sin GRT
                </label>
              </div>
            </div>
          </div>
          <hr>
          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Placa:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_placa" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_lista_placa = "SELECT id_transporte,
                                cplaca,
                                id_Transportista,
                                codigo_mtc,
                                nCapacidad,
                                id_marca
                              FROM transporte
                              WHERE cEstado_Registro = 'A'";

                if ($res_lista_placa = mysqli_query($enlace, $q_lista_placa)) {
                  if (mysqli_num_rows($res_lista_placa) > 0) {
                    while ($row_lista_placa = mysqli_fetch_array($res_lista_placa)) {
                      ?>

                      <option value="<?php echo $row_lista_placa["id_transporte"] ?>"
                        data-codmtc="<?php echo $row_lista_placa["codigo_mtc"] ?>"
                        data-idempresatransporte="<?php echo $row_lista_placa["id_Transportista"] ?>"
                        data-capacidadunidad="<?php echo number_format($row_lista_placa["nCapacidad"] / 1000, 2) ?>"
                        data-idmarca="<?php echo $row_lista_placa["id_marca"] ?>"><?php echo $row_lista_placa["cplaca"] ?>
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
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Emp. Transporte:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_empresatransporte" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>
              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              N° Constancia MTC:
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <input id="guia_constanciamtc" type="text" class="form-control"
                style="text-align: center; font-size: 14px; text-transform: uppercase;">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Marca Unidad:
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <select id="guia_marcaunidad" class="form-select" data-placeholder="Elija una opción...">
                <option selected value="">Elija una opción...</option>
                <option value="x" style="font-size: 6px;" disabled></option>

                <?php

                // Obtiene lista
                $q_marcas = "SELECT Id,
                                          descripcion
                                    FROM tbconfig_unidadesmarca
                                    WHERE estado = 'A'
                                  ORDER BY descripcion";

                if ($res_marcas = mysqli_query($enlace, $q_marcas)) {
                  if (mysqli_num_rows($res_marcas) > 0) {
                    while ($row_marcas = mysqli_fetch_array($res_marcas)) {
                      ?>

                      <option value="<?php echo $row_marcas["Id"]; ?>"><?php echo $row_marcas["descripcion"]; ?></option>

                      <option value="x" style="font-size: 6px;" disabled></option>

                      <?php
                    }
                  }
                }

                ?>

              </select>
            </div>
          </div>
          <hr>
          <!-- Placa 2-->
          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Placa 2:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_placa2" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_lista_placa = "SELECT id_transporte,
                                cplaca,
                                id_Transportista,
                                codigo_mtc,
                                nCapacidad,
                                id_marca
                              FROM transporte
                              WHERE cEstado_Registro = 'A'";

                if ($res_lista_placa = mysqli_query($enlace, $q_lista_placa)) {
                  if (mysqli_num_rows($res_lista_placa) > 0) {
                    while ($row_lista_placa = mysqli_fetch_array($res_lista_placa)) {
                      ?>

                      <option value="<?php echo $row_lista_placa["id_transporte"] ?>"
                        data-codmtc="<?php echo $row_lista_placa["codigo_mtc"] ?>"
                        data-idempresatransporte="<?php echo $row_lista_placa["id_Transportista"] ?>"
                        data-capacidadunidad="<?php echo number_format($row_lista_placa["nCapacidad"] / 1000, 2) ?>"
                        data-idmarca="<?php echo $row_lista_placa["id_marca"] ?>"><?php echo $row_lista_placa["cplaca"] ?>
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
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Emp. Transporte:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_empresatransporte2" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>
              </select>
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              N° Constancia MTC:
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <input id="guia_constanciamtc2" type="text" class="form-control"
                style="text-align: center; font-size: 14px; text-transform: uppercase;">
            </div>
          </div>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Marca Unidad:
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-left: -20px;">
              <select id="guia_marcaunidad2" class="form-select" data-placeholder="Elija una opción...">
                <option selected value="">Elija una opción...</option>
                <option value="x" style="font-size: 6px;" disabled></option>

                <?php

                // Obtiene lista
                $q_marcas = "SELECT Id,
                                          descripcion
                                    FROM tbconfig_unidadesmarca
                                    WHERE estado = 'A'
                                  ORDER BY descripcion";

                if ($res_marcas = mysqli_query($enlace, $q_marcas)) {
                  if (mysqli_num_rows($res_marcas) > 0) {
                    while ($row_marcas = mysqli_fetch_array($res_marcas)) {
                      ?>

                      <option value="<?php echo $row_marcas["Id"]; ?>"><?php echo $row_marcas["descripcion"]; ?></option>

                      <option value="x" style="font-size: 6px;" disabled></option>

                      <?php
                    }
                  }
                }

                ?>

              </select>
            </div>
          </div>
          <hr>

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Conductor:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_conductor" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>

                <?php

                $q_lista = "SELECT Id,
                                      dni_licencia,
                                      UPPER(nombres) AS nombres
                                  FROM tbconfig_conductores
                                WHERE estado = 'A'
                                ORDER BY nombres";

                if ($res_lista = mysqli_query($enlace, $q_lista)) {
                  if (mysqli_num_rows($res_lista) > 0) {
                    while ($row_lista = mysqli_fetch_array($res_lista)) {
                      ?>

                      <option value="<?php echo $row_lista["Id"] ?>">
                        <?php echo $row_lista["dni_licencia"] . ' - ' . $row_lista["nombres"] ?>
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
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Motivo Traslado:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_motivotraslado" class="form-select" data-placeholder="Elija una opción..."
                style="font-size: 14px;">
                <option selected value="">Elija una opción...</option>
                <option value="VENTA SUJETA A CONFIRMACIÓN">VENTA SUJETA A CONFIRMACIÓN</option>
                <option value="SERVICIO DE CHANCADO">SERVICIO DE CHANCADO</option>
              </select>
            </div>
          </div>
          <hr>
          <div class="row" style="padding: 5px;">
            <div class="d-flex justify-content-end">
              <button id="btn_AddLote" type="button" class="btn btn-dark" style="font-size: 14px;"
                onclick="f_AddLote();">+ Agregar Lote</button>
            </div>
          </div>

          <div class="d-flex justify-content-center" style="padding: 5px; height: 400px; overflow-y: scroll;">
            <div class="col-md-12 col-sm-12 col-xs-12">

              <table class="table table-bordered table-hover">
                <thead>
                  <tr style="font-size: 12px;">
                    <th colspan="7"
                      style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                      Información Lotes
                    </th>
                  </tr>

                  <!-- Primera fila de cabecera -->
                  <tr style="font-size: 12px;">
                    <th colspan="2"
                      style="text-align: center; background-color: #816951; border: solid 1px #ffffff; color: #ffffff; vertical-align: middle;">
                      N°</th>
                    <th rowspan="2"
                      style="text-align: center; background-color: #816951; border: solid 1px #ffffff; color: #ffffff; vertical-align: middle;">
                      Lote</th>
                    <th rowspan="2"
                      style="text-align: center; background-color: #816951; border: solid 1px #ffffff; color: #ffffff; vertical-align: middle;">
                      P. Bruto</th>
                    <th rowspan="2"
                      style="text-align: center; background-color: #816951; border: solid 1px #ffffff; color: #ffffff; vertical-align: middle;">
                      Tara</th>
                    <th rowspan="2"
                      style="text-align: center; background-color: #816951; border: solid 1px #ffffff; color: #ffffff; vertical-align: middle;">
                      P. Neto</th>
                  </tr>

                </thead>

                <tbody id="tbl_guialistalotes">

                </tbody>
              </table>
            </div>

          </div>

          <hr style="color: #6c757d;">

          <div class="d-flex" style="padding: 5px; margin-top: -10px; font-size: 14px;">
            <label style="width: 150px; margin-top: 7px;">
              Capacidad Unidad (Tn):
            </label>

            <input id="guia_capacidadunidad" type="text" class="form-control"
              style="text-align: center; font-size: 14px; width: 80px;" disabled>

          </div>
        </div>

        <input id="id_programacion" type="hidden">
        <input id="item_programacion" type="hidden">
        <input id="modograbar_guia" type="hidden">
        <input id="hd_idtransportista" type="hidden">

        <div class="modal-footer" style="margin-top: -10px;">
          <div id="wt_grabarprogramacion" class=""
            style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
            <label style="font-style: italic;"> Grabando datos...</label>
          </div>

          <button type="button" class="btn btn-secondary wt_grabarprogramacion_button" data-bs-dismiss="modal"
            style="font-size: 14px;">Cerrar</button>
          <button type="button" class="btn btn-primary wt_grabarprogramacion_button" style="font-size: 14px;"
            onclick="f_EmitirGuia();">Emitir Guías</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal-dialog-scrollable" id="modal_adminlotes" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adminlotesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modal_adminlotesLabel">Seleccionar Lotes</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="row" style="padding: 5px;">
            <div class="col-md-1 col-sm-1 col-xs-12" style="padding: 5px;">
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12" style="padding: 5px;">
              Lotes:
            </div>

            <div class="col-md-8 col-sm-8 col-xs-12" style="margin-left: -20px;">
              <select id="guia_lote" class="form-select" multiple data-placeholder="Elija opciones..."
                style="font-size: 14px;">
              </select>
            </div>
          </div>

        </div>

        <div class="modal-footer" style="margin-top: -10px;">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
            style="font-size: 14px;">Cerrar</button>
          <button type="button" class="btn btn-warning" style="font-size: 14px;" onclick="f_AgregarLotes();">Confirmar
            Lotes</button>
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

  <!-- Funciones de Inicio -->
  <script type="text/javascript">
    function f_Init() {
      // Genera menús
      f_GetMenuPrincipal();

      // Titulo de Pantalla
      $("#nv_titulo").html('| Gestionar Guías');

      // Carga el detalle de información
      f_LoadResultados();
    }

  </script>

  <script type="text/javascript">

    const dropdownParent = $('#modal_adminguias');
    const dropdownParentLote = $('#modal_adminlotes');
    const dropdownParentMenu = $('#menuModal');

    $(document).ready(function () {
      $('#guia_proveedor, #guia_concesion, #guia_placa, #guia_empresatransporte, #guia_marcaunidad, #guia_placa2, #guia_empresatransporte2, #guia_marcaunidad2, #guia_conductor, #guia_motivotraslado').select2({
        theme: 'bootstrap-5',
        dropdownParent: dropdownParent,
        width: '100%',
        placeholder: "Elija una opción...",
        allowClear: true

      });

      $('#guia_lote').select2({
        theme: 'bootstrap-5',
        dropdownParent: dropdownParentLote,
        width: '100%',
        placeholder: "Buscar lotes...",
        allowClear: true

      });

      $('#filtro_guiaremitente').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: "Buscar lotes...",
        allowClear: true

      });

      $('#guia_proveedor').on('change', function () {
        var texto = $(this).find(":selected").text();
        var documento = texto.split(' - ')[0];
        f_CargarConcesionesProveedor(documento);
      });

      $('#guia_fechas').on('change', function () {
        var fecha = $(this).val();
        $('#guia_fechaemision').val(fecha);
        $('#guia_balanza_fecharegistro').val(fecha);
      });



      $('#guia_placa').on('change', function () {
        var texto = $(this).find(":selected").text();
        var documento = texto.split(' - ')[0];

        // Obtiene el ID de la empresa de transporte desde el atributo del <option>
        var id_empresa_transporte = $(this).find(":selected").data("idempresatransporte");

        // Obtiene el Código MTC de la empresa de transporte desde el atributo del <option>
        var codigo_mtc = $(this).find(":selected").data("codmtc");
        $('#guia_constanciamtc').val(codigo_mtc);

        // Obtiene el Código MTC de la empresa de transporte desde el atributo del <option>
        var capacidad_unidad = $(this).find(":selected").data("capacidadunidad");
        $('#guia_capacidadunidad').val(capacidad_unidad);

        // Obtiene la marca desde el atributo del <option>
        var id_marca = $(this).find(":selected").data("idmarca");
        $('#guia_marcaunidad').val(id_marca).trigger('change.select2');

        f_CargarEmpresaTransporteDesdePlaca(id_empresa_transporte, codigo_mtc, capacidad_unidad, id_marca);
      });



      $('#guia_placa2').on('change', function () {
        var posicion_placa = 2;
        var texto = $(this).find(":selected").text();
        var documento = texto.split(' - ')[0];

        // Obtiene el ID de la empresa de transporte desde el atributo del <option>
        var id_empresa_transporte = $(this).find(":selected").data("idempresatransporte");

        // Obtiene el Código MTC de la empresa de transporte desde el atributo del <option>
        var codigo_mtc = $(this).find(":selected").data("codmtc");
        $('#guia_constanciamtc' + posicion_placa).val(codigo_mtc);

        // Obtiene el Código MTC de la empresa de transporte desde el atributo del <option>
        var capacidad_unidad = $(this).find(":selected").data("capacidadunidad");
        $('#guia_capacidadunidad' + posicion_placa).val(capacidad_unidad);

        // Obtiene la marca desde el atributo del <option>
        var id_marca = $(this).find(":selected").data("idmarca");
        $('#guia_marcaunidad' + posicion_placa).val(id_marca).trigger('change.select2');

        f_CargarEmpresaTransporteDesdePlaca(id_empresa_transporte, codigo_mtc, capacidad_unidad, id_marca, posicion_placa);
      });

    });
  </script>

  <!-- Funciones Principales -->
  <script type="text/javascript">
    function f_LoadResultados() {
      var _html = '';

      var fecha_inicio = $("#fecha_inicio").val();
      var fecha_fin = $("#fecha_fin").val();
      var filtro_transportista = $("#filtro_transportista").val();
      var filtro_placa = $("#filtro_placa").val();
      var filtro_guiaremitente = $("#filtro_guiaremitente").val();

      f_LoadingResumen(1);

      $("#tbl_detalle").html('');

      $.post("apis/backend.php", { accion: "get_Guias_ListaResumenBalanza_Cierre", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, filtro_transportista: filtro_transportista, filtro_placa: filtro_placa, filtro_guiaremitente: filtro_guiaremitente },
        function (data) {
          if (data.estado == 1) {
            $("#tbl_detalle").html(data.html);
          }

          f_LoadingResumen(0);

        }, "json");
    };

    function f_AddGuia(_is_edit, fecha_inicio, _fechahora_iniciotraslado, _fechahora_inicioplanta, _guiaremitente_serie, _guiaremitente_numero, _guiatransportista_serie, _guiatransportista_numero, _id_proveedorminero, _id_proveedorminero_concesion, _placa, _id_empresatransporte, _unidad_codigo_mtc, _unidad_capacidad, _unidad_id_marca, _placa2, _id_empresatransporte2, _unidad_codigo_mtc2, _unidad_capacidad2, _unidad_id_marca2, _motivotraslado, _id_transportista, _planta_destino) {
      // Seteando variables hidden
      $("#modograbar_guia").val(((_is_edit == 1) ? 'E' : 'N'));

      $("#guia_fechas").val(fecha_inicio);

      if (_fechahora_iniciotraslado == undefined) {
        $("#guia_fechaemision").val(fecha_inicio);
        $("#guia_horaemision").val('<?php echo substr($g_time, 0, 5) ?>');
      }
      else {
        $("#guia_fechaemision").val(_fechahora_iniciotraslado.substring(0, 10));
        $("#guia_horaemision").val(_fechahora_iniciotraslado.substring(16, 11));
      }

      if (_fechahora_inicioplanta == undefined) {
        $("#guia_balanza_fecharegistro").val(fecha_inicio);
        $("#guia_balanza_horaregistro").val('<?php echo substr($g_time, 0, 5) ?>');
      }
      else {
        $("#guia_balanza_fecharegistro").val(_fechahora_inicioplanta.substring(0, 10));
        $("#guia_balanza_horaregistro").val(_fechahora_inicioplanta.substring(16, 11));
      }

      $("#guia_remitenteserie").val(((_is_edit == 1) ? _guiaremitente_serie : ''));
      $("#guia_remitentenumero").val(((_is_edit == 1) ? _guiaremitente_numero : ''));
      $("#guia_transportistaserie").val(((_is_edit == 1) ? _guiatransportista_serie : ''));
      $("#guia_transportistanumero").val(((_is_edit == 1) ? _guiatransportista_numero : ''));
      $("#chk_SinGRT").prop('checked', false);
      $("#guia_proveedor").val(((_is_edit == 1) ? _id_proveedorminero : '')).trigger('change.select2');

      // Obtenemos el texto actual del proveedor ya cargado
      const texto_proveedor = $('#guia_proveedor option:selected').text();
      const documento = texto_proveedor.split(' - ')[0];

      // Cargar concesiones con valor seleccionado
      f_CargarConcesionesProveedor(documento, _id_proveedorminero_concesion);

      $("#guia_placa").val(((_is_edit == 1) ? _placa : '')).trigger('change.select2');
      f_CargarEmpresaTransporteDesdePlaca(_id_empresatransporte, _unidad_codigo_mtc, _unidad_capacidad, _unidad_id_marca);

      $("#guia_placa2").val(((_is_edit == 1) ? _placa2 : '')).trigger('change.select2');
      f_CargarEmpresaTransporteDesdePlaca(_id_empresatransporte2, _unidad_codigo_mtc2, _unidad_capacidad2, _unidad_id_marca2, 2);

      $("#guia_conductor").val(((_is_edit == 1) ? _id_transportista : '')).trigger('change.select2');
      $("#guia_motivotraslado").val(((_is_edit == 1) ? _motivotraslado : '')).trigger('change.select2');
      $("#planta_destino").val(((_is_edit == 1) ? _planta_destino : '')).trigger('change.select2');
      $("#tbl_guialistalotes").html('');
      $("#guia_capacidadunidad").val(((_is_edit == 1) ? ((parseFloat(_unidad_capacidad) || 0) + (parseFloat(_unidad_capacidad2) || 0)) : ''));

      f_AgregarLotesDesdeGuiaRemitente(_id_proveedorminero, _guiaremitente_serie, _guiaremitente_numero, _is_edit);

      f_OpenModal('modal_adminguias');

    };

    function f_CargarConcesionesProveedor(documento, id_concesion_selected = null) {
      $('#guia_concesion').html('<option value="">Cargando concesiones...</option>');
      $('#guia_concesion').prop("disabled", true).trigger('change.select2');

      $.post('apis/backend.php', { accion: 'get_listaclientesconcesion', documento_cliente: documento }, function (data) {
        if (data.estado == 1) {
          let html = '';
          const concesiones = data.res;

          if (concesiones.length === 1) {
            const item = concesiones[0];
            html = '<option value="' + item.Id + '">' +
              item.descripcion + ' - ' + item.codigo_unico + ' ' + item.procedencia + '</option>';

            $('#guia_concesion').html(html).prop("disabled", false).val(item.Id).trigger('change.select2');
          } else {
            html = '<option value="">Elija una opción...</option>';
            concesiones.forEach(function (item) {
              html += '<option value="' + item.Id + '">' +
                item.descripcion + ' - ' + item.codigo_unico + ' (' + item.procedencia + ')</option>';
            });

            $('#guia_concesion').html(html).prop("disabled", false);

            //Selecciona automáticamente si viene desde edición
            if (id_concesion_selected) {
              $('#guia_concesion').val(id_concesion_selected).trigger('change.select2');
            } else {
              $('#guia_concesion').val('').trigger('change.select2');
            }
          }
        } else {
          $('#guia_concesion').html('<option value="">Sin concesiones disponibles</option>')
            .prop("disabled", true)
            .trigger('change.select2');
        }
      }, 'json');
    }

    function f_CargarEmpresaTransporteDesdePlaca(id_empresa_transporte, unidad_codigo_mtc, unidad_capacidad, unidad_id_marca, posicion_placa = '') {
      $('#guia_constanciamtc' + posicion_placa).val(unidad_codigo_mtc);
      $('#guia_capacidadunidad' + posicion_placa).val(unidad_capacidad);
      $('#guia_marcaunidad' + posicion_placa).val(unidad_id_marca).trigger('change.select2');

      $('#guia_empresatransporte' + posicion_placa).html('<option value="">Cargando empresas de transporte...</option>');
      $('#guia_empresatransporte' + posicion_placa).prop("disabled", true).trigger('change.select2');

      $.post('apis/backend.php', { accion: 'get_listaclientes_Short' }, function (data) {
        if (data.estado == 1) {
          let html = '<option value="">Elija una opción...</option>';
          data.res.forEach(function (item) {
            html += `<option value="${item.Id}">${item.razon_social}</option>`;
          });

          $('#guia_empresatransporte' + posicion_placa)
            .html(html)
            .prop("disabled", false);

          if (id_empresa_transporte) {
            $('#guia_empresatransporte' + posicion_placa)
              .val(id_empresa_transporte)
              .trigger('change.select2');
          } else {
            $('#guia_empresatransporte' + posicion_placa).trigger('change.select2');
          }

        } else {
          $('#guia_empresatransporte' + posicion_placa).html('<option value="">Sin empresas disponibles</option>')
            .prop("disabled", true)
            .trigger('change.select2');
        }
      }, 'json');
    }



    function f_AddLote() {
      $("#guia_lote").val('').trigger('change.select2');
      f_CargarLotesDisponibles();
      f_OpenModal('modal_adminlotes');
    };

    function f_CargarLotesDisponibles() {
      const $combo = $('#guia_lote');

      $combo.html('<option value="">Cargando lotes...</option>');

      $.post('apis/backend.php', { accion: 'get_Lotes_Disponibles' }, function (data) {
        if (data.estado == 1) {
          let html = '';

          data.res.forEach(item => {
            html += `
                <option value="${item.Id}"
                        data-pesobruto="${item.lote_peso_bruto}"
                        data-pesotara="${item.lote_peso_tara}"
                        data-pesoneto="${item.lote_peso_neto}">
                  ${item.lote_cod_lote} | ${item.balanza_placa} | ${(item.lote_peso_neto / 1000).toFixed(2)} t
                </option>`;
          });

          $combo.html(html).val(null).trigger('change.select2');
        } else {
          $combo.html('<option value="">No hay lotes disponibles</option>');
        }
      }, 'json');
    }


    function f_Guias_GenerarCodigoGel(_guia_remitente, _guia_fecha_emision, _planta_fechallegada) {
      $.post('apis/backend.php', { accion: 'cierre_Guias_GenerarCodigosGEL', guia_remitente: _guia_remitente, guia_fecha_emision: _guia_fecha_emision, planta_fechallegada: _planta_fechallegada }, function (data) {
        if (data.estado == 1) {
          f_LoadResultados();
        } else if (data.msg) {
          alert(data.msg);
        } else {
          alert("Ocurrió un error al generar códigos GEL.");
        }
      }, 'json');
    };

    function f_AgregarLotes() {
      const selected_options = $('#guia_lote option:selected');
      const tbody = $('#tbl_guialistalotes');

      let index = $('#tbl_guialistalotes tr.fila-lote').length;

      selected_options.each(function () {
        const $opt = $(this);
        const id_lote = $opt.val();

        if (tbody.find(`tr[data-id="${id_lote}"]`).length > 0) {
          return;
        }

        const cod_lote = $opt.text().split('|')[0].trim();
        const peso_bruto = parseFloat($opt.data('pesobruto')) || 0;
        const peso_tara = parseFloat($opt.data('pesotara')) || 0;
        const peso_neto = parseFloat($opt.data('pesoneto')) || 0;

        const fila = `
            <tr class="fila-lote" data-id="${id_lote}">
              <td class="col-numero" style="text-align: center;">${index + 1}</td>

              <!-- Columna de acciones -->
              <td style="text-align: center; width: 130px !important;">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-square me-1" style="width: 30px; height: 30px; padding: 0;" onclick="f_MoverLoteIndividual(this, 'up')" title="Subir">
                  <img src="images/up.png" style="width: 18px; height: 18px;" alt="Subir">
                </button>

                <button type="button" class="btn btn-outline-primary btn-sm rounded-square me-1" style="width: 30px; height: 30px; padding: 0;" onclick="f_MoverLoteIndividual(this, 'down')" title="Bajar">
                  <img src="images/down.png" style="width: 18px; height: 18px;" alt="Bajar">
                </button>

                <button type="button" class="btn btn-outline-danger btn-sm rounded-square" style="width: 30px; height: 30px; padding: 0;" onclick="f_EliminarLote(this)" title="Eliminar">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>


              <td style="text-align: center; width: 130px !important">${cod_lote}</td>
              <td>
                <input type="number" step="0.01" min="0" class="form-control text-end input-bruto" value="${(peso_bruto / 1000).toFixed(2)}" data-index="${index}">
              </td>
              <td>
                <input type="number" step="0.01" min="0" class="form-control text-end input-tara" value="${(peso_tara / 1000).toFixed(2)}" data-index="${index}">
              </td>
              <td>
                <input type="number" step="0.01" min="0" class="form-control text-end input-neto" value="${(peso_neto / 1000).toFixed(2)}" data-index="${index}" disabled>
              </td>
              
            </tr>
          `;

        tbody.append(fila);

        tbody.find('tr.fila-lote').removeClass('table-success');
        tbody.find(`tr[data-id="${id_lote}"]`).addClass('table-success');

        index++;
      });

      f_ActualizarNumeracionLotes();
      f_RecalcularDesde(0);
      $('#modal_adminlotes').modal('hide');
    }

    function f_MoverLoteIndividual(btn, direccion) {
      const fila = $(btn).closest('tr');
      if (direccion === 'up') {
        fila.prev().before(fila);
      } else if (direccion === 'down') {
        fila.next().after(fila);
      }

      f_ActualizarNumeracionLotes(); // ya lo tienes

      // Agregar este bloque:
      const index_modificado = fila.index(); // nuevo índice luego de mover
      f_RecalcularDesde(index_modificado);

      // Pintar visualmente la fila movida
      fila.addClass('resaltado-movido');
      setTimeout(() => {
        fila.removeClass('resaltado-movido');
      }, 800); // 0.8 segundos
    }

    function f_ActualizarNumeracionLotes() {
      $('#tbl_guialistalotes tr.fila-lote').each(function (index) {
        const num_cell = $(this).find('td').eq(0); // primera columna
        const botones = `
            ${index + 1}
          `;
        num_cell.html(botones);

        // Actualiza los data-index en los inputs de esta fila
        $(this).find('.input-bruto, .input-tara, .input-neto').attr('data-index', index);
      });
    }

    $(document).on('input', '.input-bruto', function () {
      const index = parseInt($(this).data('index')) || 0;
      f_RecalcularDesde(index);
    });

    $(document).on('input', '.input-tara', function () {
      const index = parseInt($(this).data('index')) || 0;
      f_RecalcularDesde(index);
    });

    function f_RecalcularDesde(index_modificado) {
      const filas = $('#tbl_guialistalotes tr.fila-lote');
      const fila = $(filas[index_modificado]);

      let bruto = parseFloat(fila.find('.input-bruto').val()) || 0;
      let tara = parseFloat(fila.find('.input-tara').val()) || 0;
      const neto = parseFloat(fila.find('.input-neto').val()) || 0;

      const input_modificado = document.activeElement.classList.contains("input-bruto") ? "bruto" : "tara";

      // Recalcular el campo complementario (respetando el neto fijo)
      if (input_modificado === "bruto") {
        tara = bruto - neto;
        fila.find('.input-tara').val(tara.toFixed(2));
      } else {
        bruto = tara + neto;
        fila.find('.input-bruto').val(bruto.toFixed(2));
      }

      // Hacia abajo: cada tara pasa como bruto del siguiente
      for (let i = index_modificado + 1; i < filas.length; i++) {
        const fila_anterior = $(filas[i - 1]);
        const fila_actual = $(filas[i]);

        const tara_anterior = parseFloat(fila_anterior.find('.input-tara').val()) || 0;
        fila_actual.find('.input-bruto').val(tara_anterior.toFixed(2));

        const neto_actual = parseFloat(fila_actual.find('.input-neto').val()) || 0;
        const tara_actual = tara_anterior - neto_actual;
        fila_actual.find('.input-tara').val(tara_actual.toFixed(2));
      }

      // Hacia arriba: cada bruto pasa como tara del anterior
      for (let i = index_modificado - 1; i >= 0; i--) {
        const fila_siguiente = $(filas[i + 1]);
        const fila_actual = $(filas[i]);

        const bruto_siguiente = parseFloat(fila_siguiente.find('.input-bruto').val()) || 0;
        fila_actual.find('.input-tara').val(bruto_siguiente.toFixed(2));

        const neto_actual = parseFloat(fila_actual.find('.input-neto').val()) || 0;
        const bruto_actual = bruto_siguiente + neto_actual;
        fila_actual.find('.input-bruto').val(bruto_actual.toFixed(2));
      }

      // Resaltar filas con valores negativos
      filas.each(function () {
        const fila = $(this);
        const bruto = parseFloat(fila.find('.input-bruto').val()) || 0;
        const tara = parseFloat(fila.find('.input-tara').val()) || 0;
        const neto = parseFloat(fila.find('.input-neto').val()) || 0;

        if (bruto < 0 || tara < 0 || neto < 0) {
          fila.addClass('table-danger');
        } else {
          fila.removeClass('table-danger');
        }
      });

    }

    function f_EliminarLote(btn, _id_distribucion = null) {
      if (!confirm("¿Está seguro de eliminar el lote seleccionado?\n\n")) {
        return;
      }

      const fila = $(btn).closest('tr');
      const index = fila.index();

      fila.remove(); // Elimina la fila
      f_ActualizarNumeracionLotes(); // Renumera

      const filas = $('#tbl_guialistalotes tr.fila-lote');
      if (filas.length > 0) {
        const index_a_recalcular = Math.max(0, index - 1);
        f_RecalcularDesde(index_a_recalcular);
      }

      if (_id_distribucion != null) {
        $.post("apis/backend.php", {
          accion: "eliminar_Guias_PrimerTramo_Lote",
          id: _id_distribucion
        },
          function (data) {
            if (data.estado == 1) {
              // f_LoadResultados();
            } else {
              alert("Ocurrió un error al momento de eliminar el lote.");
            }
          });
      }
    }

    function f_PrintTicketBakanza(_id_md5) {
      url = 'print_ticketbalanza.php?x=' + _id_md5;

      window.open(url, '_blank');
    }


  </script>

  <!-- Funciones Secundarias -->
  <script type="text/javascript">
    function f_LoadingResumen(_is_show) {
      if (_is_show == 1) {
        $("#wt_resumen").show();
      } else {
        $("#wt_resumen").hide();
      }
    }

    function f_SavingDatos(_is_show) {
      if (_is_show == 1) {
        $("#wt_saving").show();
      } else {
        $("#wt_saving").hide();
      }
    }

    function f_ImpirmirGuias(_tipo, _guiaserie_md5, _guianumero_md5, is_remitente, _id_remitente, _id_transportista, _guia_fecha) {
      if (_tipo == 1) {
        if (is_remitente == 1) {
          url = 'print_primertramo_guiar_v1.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
        } else {
          url = 'print_primertramo_guiat_v1.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
        }
      }
      else {
        if (is_remitente == 1) {
          url = 'print_primertramo_guiar_v1.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
        } else {
          url = 'print_primertramo_guiat_v1.php?a=' + _guiaserie_md5 + '&b=' + _guianumero_md5 + '&c=' + _id_remitente + '&d=' + _id_transportista + '&e=' + _guia_fecha;
        }
      }

      window.open(url, '_blank');
    }

    function f_EliminarGuia(_numguia_serie, _numguia_numero) {
      if (!confirm("¿Está seguro de eliminar la guía seleccionada?\n\n   - Guía Remitente: " + _numguia_serie + '-' + _numguia_numero + "\n\nSi continua se eliminará también la guía del transportista.")) {
        return;
      }

      // Grabando datos
      $.post("apis/backend.php", {
        accion: "eliminar_Guias_PrimerTramo_Guias",
        numguia_serie: _numguia_serie,
        numguia_numero: _numguia_numero
      },
        function (data) {
          if (data.estado == 1) {
            f_LoadResultados();
          } else {
            alert("Ocurrió un error al momento de eliminar la guía.");
          }
        });
    }

    function f_CargarConcesiones(id_proveedor, id_concesion_selected = null) {
      $.post("apis/backend.php", {
        accion: "get_ConcesionesPorProveedor",
        id_proveedor: id_proveedor
      }, function (data) {
        let $combo = $('#_id_proveedorminero_concesion');
        $combo.empty();

        if (data.length > 0) {
          $.each(data, function (i, item) {
            $combo.append(`<option value="${item.id}">${item.descripcion}</option>`);
          });

          // Si se pasa un ID seleccionado (modo editar), lo marcamos
          if (id_concesion_selected) {
            $combo.val(id_concesion_selected).trigger('change');
          }
        }
      }, "json");
    }

    function f_AgregarLotesDesdeGuiaRemitente(id_proveedor, serie, numero, _is_edit = null) {
      const tbody = $('#tbl_guialistalotes');
      let index = $('#tbl_guialistalotes tr.fila-lote').length;

      $.post('apis/backend.php', {
        accion: 'get_lotes_por_guiaremitente',
        id_proveedor: id_proveedor,
        serie: serie,
        numero: numero
      }, function (data) {
        if (data.estado == 1 && data.res.length > 0) {
          data.res.forEach(item => {
            const id_distribucion = item.id_distribucion;

            // Evitar duplicados
            if (tbody.find(`tr[data-id="${id_distribucion}"]`).length > 0) {
              return;
            }

            const cod_lote = item.lote_cod_lote;
            const peso_bruto = parseFloat(item.lote_peso_bruto) || 0;
            const peso_tara = parseFloat(item.lote_peso_tara) || 0;
            const peso_neto = parseFloat(item.lote_peso_neto) || 0;

            /*const botonEliminar = (_is_edit != 1) ? `
              <button type="button" class="btn btn-outline-danger btn-sm rounded-square" style="width: 30px; height: 30px; padding: 0;" onclick="f_EliminarLote(this)" title="Eliminar">
                <i class="bi bi-trash-fill"></i>
              </button>` : `
              <button type="button" class="btn btn-outline-danger btn-sm rounded-square" style="width: 30px; height: 30px; padding: 0;" onclick="f_EliminarLote(this)" title="Eliminar">
                <i class="bi bi-trash-fill"></i>
              </button>`;*/

            const botonEliminar = `
                <button type="button" class="btn btn-outline-danger btn-sm rounded-square" style="width: 30px; height: 30px; padding: 0;" onclick="f_EliminarLote(this,${id_distribucion} )" title="Eliminar">
                  <i class="bi bi-trash-fill"></i>
                </button>`;

            const fila = `
                <tr class="fila-lote" data-id="${id_distribucion}">
                  <td class="col-numero" style="text-align: center;">${index + 1}</td>

                  <!-- Columna de acciones -->
                  <td style="text-align: center; width: 130px !important;">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-square me-1" style="width: 30px; height: 30px; padding: 0;" onclick="f_MoverLoteIndividual(this, 'up')" title="Subir">
                      <img src="images/up.png" style="width: 18px; height: 18px;" alt="Bajar">
                    </button>

                    <button type="button" class="btn btn-outline-primary btn-sm rounded-square me-1" style="width: 30px; height: 30px; padding: 0;" onclick="f_MoverLoteIndividual(this, 'down')" title="Bajar">
                      <img src="images/down.png" style="width: 18px; height: 18px;" alt="Bajar">
                    </button>
                    
                     ${botonEliminar}
                  </td>

                  <td style="text-align: center; width: 130px !important">${cod_lote}</td>
                  <td>
                    <input type="number" step="0.01" min="0" class="form-control text-end input-bruto" value="${(peso_bruto / 1000).toFixed(2)}" data-index="${index}">
                  </td>
                  <td>
                    <input type="number" step="0.01" min="0" class="form-control text-end input-tara" value="${(peso_tara / 1000).toFixed(2)}" data-index="${index}">
                  </td>
                  <td>
                    <input type="number" step="0.01" min="0" class="form-control text-end input-neto" value="${(peso_neto / 1000).toFixed(2)}" data-index="${index}" disabled>
                  </td>
                </tr>
              `;

            tbody.append(fila);
            tbody.find('tr.fila-lote').removeClass('table-success');
            tbody.find(`tr[data-id="${id_distribucion}"]`).addClass('table-success');
            index++;
          });

          f_ActualizarNumeracionLotes();
          f_RecalcularDesde(0);

        }
      }, 'json');
    }



  </script>

  <!-- Funciones de Grabación -->
  <script type="text/javascript">

    function f_EmitirGuia() {
      var modograbar_guia = $("#modograbar_guia").val();
      var guia_fechas = $("#guia_fechas").val();
      var guia_fechaemision = $("#guia_fechaemision").val();
      var guia_horaemision = $("#guia_horaemision").val();
      var guia_balanza_fecharegistro = $("#guia_balanza_fecharegistro").val();
      var guia_balanza_horaregistro = $("#guia_balanza_horaregistro").val();
      var guia_remitenteserie = $("#guia_remitenteserie").val();
      var guia_remitentenumero = $("#guia_remitentenumero").val();
      var guia_transportistaserie = $("#guia_transportistaserie").val();
      var guia_transportistanumero = $("#guia_transportistanumero").val();
      var sin_GRT = (($("#chk_SinGRT").prop('checked')) ? 1 : 0);
      var guia_puntopartida = $("#guia_concesion option:selected").text();
      var guia_puntodestino = puntodestino_selected;
      var guia_proveedor = $("#guia_proveedor").val();
      var guia_concesion = $("#guia_concesion").val();
      var guia_destinatario = iddestino_selected;
      var guia_placa = $("#guia_placa").val();
      var guia_placa_numero = $("#guia_placa  option:selected").text();
      var guia_placa2 = $("#guia_placa2").val();
      var guia_placa_numero2 = $("#guia_placa2  option:selected").text();
      var guia_empresatransporte = $("#guia_empresatransporte").val();
      var guia_constanciamtc = $("#guia_constanciamtc").val();
      var guia_marcaunidad = $("#guia_marcaunidad").val();
      var guia_empresatransporte2 = $("#guia_empresatransporte2").val();
      var guia_constanciamtc2 = $("#guia_constanciamtc2").val();
      var guia_marcaunidad2 = $("#guia_marcaunidad2").val();
      var guia_conductor = $("#guia_conductor").val();
      var guia_motivotraslado = $("#guia_motivotraslado").val();
      var guia_capacidadunidad = $("#guia_capacidadunidad").val();

      // Validando datos
      if (guia_fechas == null) {
        alert("Debe registrar la Fecha de Inicio de Traslado.");

        return;
      }
      if (guia_fechas.length == 0) {
        alert("Debe registrar la Fecha de Inicio de Traslado.");

        return;
      }

      if (guia_fechaemision == null) {
        alert("Debe ingresar la Fecha de Emisión.");

        return;
      }

      if (guia_fechaemision.length == 0) {
        alert("Debe ingresar la Fecha de Emisión.");

        return;
      }

      if (guia_horaemision == null) {
        alert("Debe ingresar la Hora de Emisión.");

        return;
      }

      if (guia_horaemision.length == 0) {
        alert("Debe ingresar la Hora de Emisión.");

        return;
      }

      if (guia_balanza_fecharegistro == null) {
        alert("Debe ingresar la Fecha de Ingreso a Planta.");

        return;
      }

      if (guia_balanza_fecharegistro.length == 0) {
        alert("Debe ingresar la Fecha de Ingreso a Planta.");

        return;
      }

      if (guia_balanza_horaregistro == null) {
        alert("Debe ingresar la Hora de Ingreso a Planta.");

        return;
      }

      if (guia_balanza_horaregistro.length == 0) {
        alert("Debe ingresar la Hora de Ingreso a Planta.");

        return;
      }

      if (guia_proveedor == null) {
        alert("Debe ingresar el Proveedor.");

        return;
      }

      if (guia_proveedor.length == 0) {
        alert("Debe ingresar el Proveedor.");

        return;
      }

      if (guia_concesion == null) {
        alert("Debe ingresar la Concesión.");

        return;
      }

      if (guia_concesion.length == 0) {
        alert("Debe ingresar la Concesión.");

        return;
      }

      if (guia_remitenteserie == null) {
        alert("Debe registrar la Serie de la Guía del Remitente.");

        return;
      }

      if (guia_remitenteserie.length == 0) {
        alert("Debe registrar la Serie de la Guía del Remitente.");

        return;
      }

      if (guia_remitentenumero == null) {
        alert("Debe registrar el Número de Guía del Remitente.");

        return;
      }

      if (guia_remitentenumero.length == 0) {
        alert("Debe registrar el Número de Guía del Remitente.");

        return;
      }

      if (guia_puntopartida == null) {
        alert("El Punto de Partida no ha sido configurado correctamente, por favor verificar.");

        return;
      }

      if (guia_puntopartida.length == 0) {
        alert("El Punto de Partida no ha sido configurado correctamente, por favor verificar.");

        return;
      }

      if (guia_puntodestino == null) {
        alert("El Punto de Destino no ha sido configurado correctamente, por favor verificar.");

        return;
      }

      if (guia_puntodestino.length == 0) {
        alert("El Punto de Destino no ha sido configurado correctamente, por favor verificar.");

        return;
      }

      if (guia_destinatario == null) {
        alert("El Destinatario no ha sido configurado correctamente, por favor verificar.");

        return;
      }

      if (guia_destinatario.length == 0) {
        alert("El Destinatario no ha sido configurado correctamente, por favor verificar.");

        return;
      }

      if (guia_placa == null) {
        alert("Debe de registrar la Placa 1");

        return;
      }

      if (guia_placa.length == 0) {
        alert("Debe de registrar la Placa 1");

        return;
      }

      if (guia_empresatransporte == null) {
        alert("La Empresa de Transporte no ha sido configurada correctamente, por favor verificar.");

        return;
      }

      if (guia_empresatransporte.length == 0) {
        alert("La Empresa de Transporte no ha sido configurada correctamente, por favor verificar.");

        return;
      }

      if (guia_constanciamtc == null) {
        alert("Debe registrar el N° de Constancia MTC de la Placa 1.");

        return;
      }
      if (guia_constanciamtc.length == 0) {
        alert("Debe registrar el N° de Constancia MTC de la Placa 1.");

        return;
      }

      if (guia_marcaunidad == null) {
        alert("Debe registrar la Marca de la Placa 1.");

        return;
      }

      if (guia_marcaunidad.length == 0) {
        alert("Debe registrar la Marca de la Placa 1.");

        return;
      }

      if (guia_placa2 != null && guia_placa2.length > 0) {
        if (guia_empresatransporte2 == null) {
          alert("La Empresa de Transporte 2 no ha sido configurada correctamente, por favor verificar.");

          return;
        }

        if (guia_empresatransporte2.length == 0) {
          alert("La Empresa de Transporte 2 no ha sido configurada correctamente, por favor verificar.");

          return;
        }

        if (guia_constanciamtc2 == null) {
          alert("Debe registrar el N° de Constancia MTC de la Placa 2.");

          return;
        }
        if (guia_constanciamtc2.length == 0) {
          alert("Debe registrar el N° de Constancia MTC de la Placa 2.");

          return;
        }

        if (guia_marcaunidad == null) {
          alert("Debe registrar la Marca de la Placa 2.");

          return;
        }

        if (guia_marcaunidad.length == 0) {
          alert("Debe registrar la Marca de la Placa 2.");

          return;
        }
      }

      if (guia_conductor == null) {
        alert("Debe seleccionar el Conductor.");

        return;
      }
      if (guia_conductor.length == 0) {
        alert("Debe seleccionar el Conductor.");

        return;
      }

      if (guia_motivotraslado == null) {
        alert("El Motivo de Traslado no ha sido configurado correctamente, por favor verificar.");

        return;
      }
      if (guia_motivotraslado.length == 0) {
        alert("El Motivo de Traslado no ha sido configurado correctamente, por favor verificar.");

        return;
      }

      // Valida que exista al menos un lote
      if ($("#tbl_guialistalotes tr").length == 0) {
        alert("Debe agregar al menos un lote antes de emitir la guía.");
        return;
      }

      // Validar que todos los pesos netos sean > 0
      var is_valid = true;

      $("#tbl_guialistalotes tr.fila-lote").each(function (index) {
        var $row = $(this);

        var peso_bruto = parseFloat($row.find(".input-bruto").val()) || 0;
        var peso_tara = parseFloat($row.find(".input-tara").val()) || 0;
        var cod_lote = $row.find("td:nth-child(3)").text().trim(); // columna de código de lote

        if (peso_bruto <= 0 || peso_tara <= 0) {
          alert("Debe ingresar Peso Bruto y Peso Tara mayores a cero para el lote: " + cod_lote);
          is_valid = false;
          return false; // rompe el each
        }
      });

      if (!is_valid) {
        return; // Detiene la función f_EmitirGuia
      }


      // Valida si no se generará Guía de Transportista
      if (sin_GRT == 1) {
        if (!confirm("¿Está seguro de Emitir la Guía del Remitente sin Guía del Transportista?")) {
          return;
        }

        guia_transportistaserie = '';
        guia_transportistanumero = '';
      } else {
        if (guia_transportistaserie == null) {
          alert("Debe registrar la Serie de la Guía del Transportista.");

          return;
        }
        if (guia_transportistaserie.length == 0) {
          alert("Debe registrar la Serie de la Guía del Transportista.");

          return;
        }

        if (guia_transportistanumero == null) {
          alert("Debe registrar el Número de Guía del Transportista.");

          return;
        }
        if (guia_transportistanumero.length == 0) {
          alert("Debe registrar el Número de Guía del Transportista.");

          return;
        }
      }

      // Generar el array de detalle de lote
      var detalles_lotes = [];

      $("#tbl_guialistalotes tr.fila-lote").each(function (index) {
        var $row = $(this);
        var id_distribucion = $row.data("id");
        var peso_bruto = parseFloat($row.find(".input-bruto").val()) || 0;
        var peso_tara = parseFloat($row.find(".input-tara").val()) || 0;
        var peso_neto = parseFloat($row.find(".input-neto").val()) || 0;

        detalles_lotes.push({
          id_distribucion: id_distribucion,
          peso_bruto: peso_bruto,
          peso_tara: peso_tara,
          peso_neto: peso_neto
        });
      });

      var detalles_lotes_json = JSON.stringify(detalles_lotes);

      // Validar que la suma de peso neto no supere la capacidad de la unidad
      var capacidad_maxima = parseFloat(guia_capacidadunidad) || 0;
      var suma_neto = 0;

      for (var i = 0; i < detalles_lotes.length; i++) {
        suma_neto += detalles_lotes[i].peso_neto;
      }

      if (suma_neto > capacidad_maxima) {
        var continuar = confirm(
          "La suma de los pesos netos (" + suma_neto.toFixed(2) + " Tn) supera la Capacidad de Unidad permitida (" + capacidad_maxima.toFixed(2) + " Tn).\n¿Desea continuar de todas formas?"
        );

        if (!continuar) {
          return;
        }

      }

      // PLANTA DE DESTINO: HUANCHACO|LARDO
      const planta_destino = $("#planta_destino").val();

      if (planta_destino == "") {
        alert("Debe seleccionar la Planta de Destino.");
        return;
      }
      // Grabando datos
      $.post("apis/backend.php", {
        accion: "grabar_Guias_PrimerTramo_GestionGuias",
        planta_destino: planta_destino,
        modograbar_guia: modograbar_guia,
        guia_fechas: guia_fechas,
        guia_fechaemision: guia_fechaemision,
        guia_horaemision: guia_horaemision,
        guia_balanza_fecharegistro: guia_balanza_fecharegistro,
        guia_balanza_horaregistro: guia_balanza_horaregistro,
        guia_proveedor: guia_proveedor,
        guia_concesion: guia_concesion,
        guia_remitenteserie: guia_remitenteserie,
        guia_remitentenumero: guia_remitentenumero,
        guia_transportistaserie: guia_transportistaserie,
        guia_transportistanumero: guia_transportistanumero,
        guia_puntopartida: guia_puntopartida,
        guia_puntodestino: guia_puntodestino,
        guia_destinatario: guia_destinatario,
        guia_placa: guia_placa,
        guia_placa_numero: guia_placa_numero,
        guia_empresatransporte: guia_empresatransporte,
        guia_constanciamtc: guia_constanciamtc,
        guia_marcaunidad: guia_marcaunidad,
        guia_placa2: guia_placa2,
        guia_placa_numero2: guia_placa_numero2,
        guia_empresatransporte2: guia_empresatransporte2,
        guia_constanciamtc2: guia_constanciamtc2,
        guia_marcaunidad2: guia_marcaunidad2,
        guia_conductor: guia_conductor,
        guia_motivotraslado: guia_motivotraslado,
        guia_capacidadunidad: guia_capacidadunidad,
        detalles_lotes: detalles_lotes_json,
        isEdit: 1
      },
        function (data) {
          if (data.estado == 1) {
            f_LoadResultados();
          } else if (data.estado == -1) {
            alert("Ya existe una guía con la misma combinación de serie y número (Remitente / Transportista)");
            return;
          } else if (data.msg) {
            alert(data.msg);
            return;
          } else {
            alert("Ocurrió un error al momento de confirmar las guías.");
          }

          // Cierra modal
          f_cerrarModal('modal_adminguias');

        }, "json");
    }

    function f_DisabledGRT() {
      var is_selected = (($("#chk_SinGRT").prop('checked')) ? 1 : 0);

      $(".guia_GRT").val('');

      if (is_selected == 1) {
        $(".guia_GRT").prop('disabled', true);
      } else {
        $(".guia_GRT").prop('disabled', false);
      }
    }


  </script>

  <!-- Funciones de Menús -->
  <script type="text/javascript">
    function f_SetDimension() {
      if (screen.width < 500) {
        $("#offcanvasExample").css('width', '60%');

        $("#modal_addcliente_content, #modal_addconductor_content, #modal_addzonaorigen_content, #modal_addacompanante_content").css('margin-top', '10px');
      }
    }


  </script>

  <!-- Funcion Default -->
  <script type="text/javascript">

  </script>
</body>

</html>