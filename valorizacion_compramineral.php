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

  <link rel="stylesheet" href="<?php echo $url_lims ?>/global/styles.css">

  <style type="text/css">
    .modal-xxl {
      max-width: 98% !important;
    }

    #div_valorizacion_detalle {
      transition: width 0.3s ease;
    }
  </style>

  <!-- Estilos para agrupaciones por N° Valorización -->
  <style>
    tr.valo-row {
      transition: background-color .15s ease-in-out;
    }

    tr.valo-row:hover {
      filter: brightness(0.98);
    }

    /* mantiene tu estilo de estado */
    td.estado-pill {
      color: #fff;
      font-weight: bold;
      vertical-align: middle;
    }

    .badge-version {
      font-weight: 600;
      border: 1px solid currentColor;
      padding: .35em .55em;
      border-radius: 999px;
      letter-spacing: .2px;
    }

    .corr-wrapper {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      color: #0d6efd;
      font-weight: 700;
    }

    .corr-wrapper u {
      text-underline-offset: 2px;
    }

    /* separador cuando inicia grupo */
    tr.group-start td {
      border-top: 2px solid var(--group-border, #bbb);
    }
  </style>

  <title><?php echo $nom_app; ?> | Valorización de Compra</title>

  <script type="text/javascript">
    let id_valorizacion_Selected = 0;
    let item_valorizacion_Selected = 0;
    const url_api = "apis/backend.php";
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
            <div class="modal-body" style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0px !important;">
              <ul class="list-unstyled">
                <div id="div_menu1"></div>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal (Menú Lateral) -->
      <div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-right" style="margin-top: 0px !important; margin-left: 0px !important;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="menuModalLabel">Filtro de Opciones</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 0px !important;">

              <div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
                <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                  <div class="row" style="padding-left: 10px; padding-right: 10px;">
                    <h6 style="font-size: 14px;">Fecha Ingreso a Balanza
                      <button class="btn btn-info text-end" type="button" onclick="f_LoadResultados();" style="font-size: 14px; background-color: #ffffff; padding: 5px; margin-left: 5px; margin-top: -5px;">
                        <img src="<?php echo $img_refresh ?>" style="width: 25px;">
                      </button>
                    </h6>
                  </div>

                  <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                    <hr style="border-color: #D9D9D9;" />
                  </div>

                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">
                    </div>
                    <br><br>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <input id="fecha_fin" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_LoadFiltroClientes();">
                    </div>

                  </div>
                </div>
              </div>

              <div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                  <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                    <div class="row" style="padding-left: 10px; padding-right: 10px;">
                      <h6 style="font-size: 14px;">Por Lotes:</h6>
                    </div>
                    <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                      <hr style="border-color: #D9D9D9;" />
                    </div>
                    <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                      <select id="filtro_lote" class="form-control" multiple data-placeholder="Elija una o más opciones..." style="font-size: 14px; border: solid; border-width: 1px; border-color: #BFBFBF; border-radius: 7px; max-height: 40px;">
                        <?php

                        $q_lotes = "SELECT ccod_Lote
                                            FROM catalogolotes
                                          WHERE YEAR(dFechaIngreso) >= 2024
                                          ORDER BY ccod_Lote DESC";

                        if ($res_lotes = mysqli_query($enlace, $q_lotes)) {
                          if (mysqli_num_rows($res_lotes) > 0) {
                            while ($row_lotes = mysqli_fetch_array($res_lotes)) {
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

      <div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
        <div class="d-flex row">
          <div class="row" style="padding: 0px;">

            <div id="div_valorizacion_lista" class="col-md-6 col-sm-6 col-xs-12" style="padding: 5px;">
              <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                  <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <div class="d-flex">
                        <h6>Valorizaciones</h6>
                        <div id="wt_valorizaciones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                          <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                          <label style="font-style: italic;"> Cargando datos...</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 text-end">
                      <button class="btn btn-primary btn-sm" style="margin-top: -7px;" onclick="f_AdminValorizacion('x');">
                        <i class="bi bi-plus-circle"></i> Nueva Valorización
                      </button>
                    </div>
                  </div>
                </div>

                <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                  <hr style="border-color: #D9D9D9;" />
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -22px; overflow-x: scroll; width: 100%;">
                  <table class="table table-bordered table-hover" style="border-top-left-radius: 15px; border-top-right-radius: 15px; overflow: hidden;">
                    <thead>
                      <tr style="font-size: 12px;">
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; width: 30px; border-top-left-radius: 15px;">#</th>
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">N°<br>Valorización</th>
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">N°<br>Oficio</th>
                        <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Información Proveedor</th>
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 50px;">Versión</th>
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">Elaborado<br>por</th>
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">Aprobado<br>por</th>
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Estado</th>
                        <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">Acción</th>
                      </tr>

                      <tr style="font-size: 12px;">
                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">RUC</th>
                        <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Proveedor</th>
                      </tr>
                    </thead>

                    <tbody id="tbl_valorizaciones">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div id="div_valorizacion_detalle" class="col-md-6 col-sm-6 col-xs-12" style="padding: 5px;">
              <div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                <div class="col-md-10 col-sm-10 col-xs-10" style="padding: 0px;">
                  <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="d-flex">
                        <button id="btn_toggle_panel" class="btn btn-sm btn-outline-secondary me-2" style="margin-top: -5px; margin-bottom: 3px;" onclick="f_TogglePanel();">
                          <i class="bi bi-arrows-angle-expand"></i>
                        </button>
                        <h6>Detalle de Valorización:</h6>
                        <h6 id="lbl_titulovalorizacion" class="ms-2 text-primary"></h6>
                        <div id="wt_detallevalorizacion" style="font-size: 12px; display: none; padding-left: 10px;">
                          <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                          <label style="font-style: italic;"> Cargando detalle...</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                  <hr style="border-color: #D9D9D9;" />
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -22px; overflow-x: scroll; width: 100%;">
                  <table class="table table-bordered table-hover" style="border-top-left-radius: 15px; border-top-right-radius: 15px; overflow: hidden;">
                    <thead>
                      <tr style="font-size: 12px;">
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Elemento</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">Lote</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">GEL</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">G.R.R.</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">G.R.T.</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 90px;">Fecha<br>Ingreso</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TMH</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">% H2O</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TMS</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Ley<br>(oz/tc)</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">REC<br>(%)</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">INTER<br>($/oz)</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">DES. INTER<br>($/oz)</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">MAQUILA<br>($/oz)</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">REACT<br>($/oz)</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; display: none;">FLETE</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">FACTOR</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">PRECIO * TN</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; display: none;">INCENTIVO</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; display: none;">PRECIO * TN<br>(Final)</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TOTAL</th>
                        <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Acción</th>
                      </tr>
                    </thead>
                    <tbody id="tbl_valorizacion_detalle"></tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-2"></div>
          </div>
        </div>
      </div>

      <!-- MODAL: NUEVA/EDITAR VALORIZACIÓN -->
      <div class="modal fade" id="modal_valorizacion" tabindex="-1" aria-labelledby="modal_valorizacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">

            <!-- ENCABEZADO -->
            <div class="modal-header">
              <h6 class="modal-title" id="modal_valorizacionLabel">Nueva Valorización</h6>
              <button type="button" class="btn-close text-primary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- CUERPO -->
            <div class="modal-body" style="padding-bottom: 0px;">
              <form id="form_valorizacion">
                <!-- Fila combinada: Proveedor -->
                <div class="row mb-3">
                  <div class="d-flex">
                    <div class="col-md-1">
                      <label class="form-label fw-bold">Proveedor:</label>
                    </div>

                    <div class="col-md-11">
                      <select id="cmb_proveedor" class="form-select" style="width: 100%; font-size: 14px; margin-top: -5px;" onchange="f_CargarConcesionesPorProveedor(); f_LoadListaCuentasBancarias(); f_LoadListaCuentaDetraccion();">
                        <option value="">[Seleccione proveedor]</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Grupo: Información Concesión -->
                <div class="d-flex">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <div style="background-color: #816951; color: #ffffff; font-weight: bold; padding: 5px; border-radius: 6px; text-align: center; font-size: 14px;">
                          Información Concesión
                        </div>
                      </div>
                    </div>

                    <div class="row mb-2 mt-2" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin: 0px; padding: 10px; margin-top: 2px !important;">
                      <div class="col-md-4">
                        <label class="form-label" style="font-size: 14px;">Concesión:</label>
                        <select id="cmb_concesion" class="form-select" style="width: 100%; font-size: 14px;" onchange="f_MostrarInfoConcesionSeleccionada();">
                          <option value="">[Seleccione concesión]</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label" style="font-size: 14px;">Código Único:</label>
                        <input type="text" id="txt_codigo_unico" class="form-control form-control-sm" style="height: 35px; background-color: #e9ecef; font-weight: bold; text-align: center;" readonly>
                      </div>
                      <div class="col-md-5">
                        <label class="form-label" style="font-size: 14px;">Procedencia:</label>
                        <input type="text" id="txt_procedencia" class="form-control form-control-sm" style="height: 35px; background-color: #e9ecef; font-weight: bold; text-align: center;" readonly>
                      </div>
                    </div>
                  </div>

                  <!-- Reemplazar la sección de "Información del Pago" (~línea 178) con este código: -->
                  <div class="col-md-6 col-sm-6 col-xs-12" style="margin-left: 5px;">
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <div style="background-color: #816951; color: #ffffff; font-weight: bold; padding: 5px; border-radius: 6px; text-align: center; font-size: 14px;">
                          Información Pago
                        </div>
                      </div>
                    </div>

                    <div class="row">

                      <div class="col-md-6">
                        <div id="div_informacion_bancaria" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin: 10px 0; padding: 10px;">
                          <h6 style="font-size: 14px; color: #816951; font-weight: bold; margin-bottom: 15px;">
                            <i class="bi bi-bank"></i> Información Bancaria
                          </h6>

                          <div class="col-md-8">
                            <label class="form-label" style="font-size: 14px;">Cuenta Bancaria:</label>
                            <select id="valorizacion_cuentaproveedor" class="form-select" style="width: 100%; font-size: 14px;">
                            </select>
                          </div>

                          <div class="mt-1 col-md-8">
                            <label class="form-label" style="font-size: 14px;">Cuenta Detracción:</label>
                            <select id="valorizacion_cuentadetraccionproveedor" class="form-select" style="width: 100%; font-size: 14px;">
                            </select>
                          </div>

                          <div id="div_monto_transferencia" class="row mb-3" style="display: none;">
                            <div class="col-md-12" style="margin-top: 12px;">
                              <label class="form-label" style="font-size: 14px;">Monto a Transferir:</label>
                              <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" id="txt_monto_transferencia" class="form-control" step="0.01" min="0" placeholder="0.00" readonly>
                              </div>
                              <small class="text-muted">Se completará automáticamente después de seleccionar anticipos</small>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div id="div_gestion_anticipos" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin: 10px 0; padding: 10px;">
                          <h6 style="font-size: 14px; color: #816951; font-weight: bold; margin-bottom: 15px;">
                            <i class="bi bi-wallet2"></i> Gestión de Anticipos
                          </h6>

                          <div class="col-md-12">
                            <label class="form-label" style="font-size: 14px;">Anticipos seleccionados:</label>
                            <div id="txt_anticipos_seleccionados" class="form-control" style="height: auto; min-height: 35px; background-color: #e9ecef; font-size: 13px; padding: 5px;" readonly>
                              Ningún anticipo seleccionado
                            </div>

                            <div class="mt-4 d-flex justify-content-between" style="margin-bottom: 12px;">
                              <button type="button" class="btn btn-sm btn-outline-primary" onclick="f_AbrirModalSeleccionAnticipos();" style="font-size: 13px;">
                                <i class="bi bi-wallet2"></i> Seleccionar anticipos
                              </button>

                              <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="f_LimpiarSeleccionAnticipos();" style="font-size: 13px;">
                                  <i class="bi bi-trash"></i> Limpiar
                                </button>
                              </div>
                            </div>

                            <div id="div_saldo_restante" class="alert alert-warning mt-2" style="font-size: 12px; display: none; margin-bottom: 4px;">
                              <i class="bi bi-exclamation-triangle me-1"></i>
                              <strong>Saldo restante:</strong> <span id="lbl_saldo_restante">$ 0.00</span> - Este monto se cubrirá con transferencia bancaria.
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                </div>

                <!-- Grupo: Detalle Valorización -->
                <div class="row mt-4">
                  <div class="col-md-10">
                    <div style="background-color: #816951; color: #ffffff; font-weight: bold; padding: 5px; border-radius: 6px; text-align: center; font-size: 14px;">
                      Detalle Valorización
                    </div>
                  </div>

                  <div class="col-md-2" style="padding: 0px;">
                    <button type="button" class="btn btn-info btn-sm" style="width: 93%; color: #ffffff;" onclick="f_AdminLotes('x');">
                      <i class="bi bi-plus-circle"></i> Nuevo Lote
                    </button>
                  </div>
                </div>

                <!-- Botón + tabla -->
                <div class="row">
                  <div class="col-md-12" style="width: 100%; overflow-x: scroll;">
                    <table class="table table-bordered table-hover" id="tbl_lotes_valorizacion" style="border-top-left-radius: 15px; border-top-right-radius: 15px; overflow: hidden;">
                      <thead>
                        <tr style="font-size: 12px;">
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Elemento</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Lote</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">GEL</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">G.R.R.</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">G.R.T.</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Fecha<br>Ingreso</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TMH</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">% H2O</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TMS</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Ley<br>(oz/tc)</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">REC<br>(%)</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">INTER<br>($/oz)</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">DES. INTER<br>($/oz)</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">MAQUILA<br>($/oz)</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">REACT<br>($/oz)</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">FACTOR</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">PRECIO * TN</th>
                          <!-- <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">INCENTIVO</th>
                            <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">PRECIO * TN<br>(Final)</th> -->
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">TOTAL</th>
                          <th style="text-align: center; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">Acción</th>
                        </tr>
                      </thead>
                      <tbody id="tbody_lotes_valorizacion">
                        <!-- Contenido dinámico -->
                      </tbody>
                    </table>
                  </div>
                </div>

              </form>
            </div>

            <input type="hidden" id="hd_idvalorizacion">
            <input type="hidden" id="hd_modograbar_valorizacion">

            <!-- FOOTER -->
            <div class="modal-footer">
              <div id="wt_grabarvalorizacion" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                <label style="font-style: italic;"> Grabando datos...</label>
              </div>

              <button type="button" class="btn btn-secondary wt_grabarvalorizacion_button" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary wt_grabarvalorizacion_button" onclick="f_GrabarValorizacion();">Grabar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL: NUEVO LOTE -->
      <div class="modal fade" id="modal_nuevolote" tabindex="-1" aria-labelledby="modal_nuevoloteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content" style="border: solid; border-width: 1px; border-color: #816951;">

            <!-- ENCABEZADO -->
            <div class="modal-header">
              <h5 class="modal-title" id="modal_nuevoloteLabel">Agregando Lote</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- CUERPO -->
            <div class="modal-body" style="padding-bottom: 0px;">
              <form id="form_nuevolote">
                <div class="row mb-2">
                  <!-- Lote -->
                  <div class="col-md-1"><label class="form-label">Lote:</label></div>

                  <!-- Combo de Lote (Nuevo) -->
                  <div class="col-md-5" id="div_lote_combo" style="padding: 0px;">
                    <select id="cmb_lotes" class="form-select form-select-sm select2" style="width: 100%;">
                      <option value="">[Seleccione]</option>
                    </select>
                  </div>

                  <!-- Texto de Lote (Editar) -->
                  <div class="col-md-5" id="div_lote_static" style="display: none; margin-top: -7px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
                    <label id="lbl_lote_static" class="form-control-plaintext" style="font-size: 14px;"></label>
                  </div>

                  <!-- Elemento -->
                  <div class="col-md-2" style="text-align: right;"><label class="form-label">Elemento:</label></div>

                  <!-- Combo de Elemento (Nuevo) -->
                  <div class="col-md-4" id="div_elemento_combo">
                    <select id="cmb_elemento" class="form-select form-select-sm select2" style="width: 100%;">
                      <option value="">Seleccione un Lote...</option>
                    </select>
                  </div>

                  <!-- Texto de Elemento (Editar) -->
                  <div class="col-md-4" id="div_elemento_static" style="display: none; margin-top: -7px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
                    <label id="lbl_elemento_static" class="form-control-plaintext" style="font-size: 14px;"></label>
                  </div>
                </div>

                <div class="row mb-2">
                  <!-- Mostrar solo en edición -->
                  <input type="text" id="txt_lote_visible" class="form-control form-control-sm" style="display: none; text-align: center; background-color: #e9ecef; font-weight: bold;" readonly>
                  <input type="text" id="txt_elemento_visible" class="form-control form-control-sm" style="display: none; text-align: center; background-color: #e9ecef; font-weight: bold;" readonly>
                </div>

                <div id="div_sinvalorcomercial" class="row mb-12">
                  <div class="col-md-1"><label class="form-label"></label></div>

                  <!-- Combo de Lote (Nuevo) -->
                  <div class="col-md-5" style="margin-top: -15px; background-color: #dc3545; text-align: center; font-size: 13px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
                    <label style="color: #ffffff;">
                      Sin Valor Comercial
                    </label>
                  </div>
                </div>

                <div class="row mb-12">
                  <div class="col-md-6">
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <div style="background-color: #816951; color: #ffffff; font-weight: bold; padding: 5px; border-radius: 6px; text-align: center; font-size: 14px; margin-bottom: 5px;">
                          Información Lote
                        </div>
                      </div>
                    </div>

                    <!-- Código GEL -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">Código GEL:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_codigo_gel" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- G.R.R. -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">G.R.R.:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_grr" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- G.R.T. -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">G.R.T.:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_grt" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- Fecha Ingreso -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">Fecha Ingreso:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_fecha_ingreso" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- TMH -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">TMH:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_tmh" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- % H2O -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">% H2O:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_h2o" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- TMS -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">TMS:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_tms" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- Ley -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">Ley (oz/tc):</label></div>
                      <div class="col-md-7"><input type="text" id="txt_ley" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <div style="background-color: #816951; color: #ffffff; font-weight: bold; padding: 5px; border-radius: 6px; text-align: center; font-size: 14px; margin-bottom: 5px;">
                          Información Valorización
                        </div>
                      </div>
                    </div>

                    <!-- REC % -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">REC %:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_recuperacion" style="text-align: center;" class="form-control form-control-sm" onkeyup="f_CalcularTotalesValorizacion();"></div>
                    </div>

                    <!-- INTER -->
                    <div class="row mb-2">
                      <div class="col-md-5">
                        <label class="form-label" style="font-size: 14px; margin-top: 5px;">INTER ($/oz):</label>
                      </div>
                      <div class="col-md-7 d-flex">
                        <!-- <button type="button" class="btn btn-outline-secondary btn-sm me-1"
                                  title="Obtener precio internacional (London Fix - PM)"
                                  onclick="f_GetPrecioInternacionalAu('txt_inter', '<?php echo $g_date ?>')">
                            <i class="bi bi-cloud-download"></i>
                          </button> -->
                        <input type="text" id="txt_inter" style="text-align: center;" class="form-control form-control-sm" onkeyup="f_CalcularTotalesValorizacion();">
                      </div>
                    </div>

                    <!-- DESCUENTO INTER -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">DES. INTER ($/oz):</label></div>
                      <div class="col-md-7"><input type="text" id="txt_desc_inter" style="text-align: center;" class="form-control form-control-sm" onkeyup="f_CalcularTotalesValorizacion();"></div>
                    </div>

                    <!-- MAQUILA -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">MAQUILA ($/tc):</label></div>
                      <div class="col-md-7"><input type="text" id="txt_maquila" style="text-align: center;" class="form-control form-control-sm" onkeyup="f_CalcularTotalesValorizacion();"></div>
                    </div>

                    <!-- REACT -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">REACT ($/tc):</label></div>
                      <div class="col-md-7"><input type="text" id="txt_reactivo" style="text-align: center;" class="form-control form-control-sm" onkeyup="f_CalcularTotalesValorizacion();"></div>
                    </div>

                    <!-- FACTOR -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">FACTOR:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_factor" style="text-align: center;" class="form-control form-control-sm" style="text-align: center;" onkeyup="f_CalcularTotalesValorizacion();"></div>
                    </div>

                    <!-- PRECIO * TN -->
                    <div class="row mb-2" style="display: none;">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">PRECIO * TN:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_precio_tn" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- INCENTIVO -->
                    <div class="row mb-2" style="display: none;">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">INCENTIVO:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_incentivo" style="text-align: center;" class="form-control form-control-sm" onkeyup="f_CalcularTotalesValorizacion();"></div>
                    </div>

                    <!-- PRECIO TN FINAL -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">PRECIO * TN (Final):</label></div>
                      <div class="col-md-7"><input type="text" id="txt_precio_tn_final" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>

                    <!-- TOTAL -->
                    <div class="row mb-2">
                      <div class="col-md-5"><label class="form-label" style="font-size: 14px; margin-top: 5px;">TOTAL:</label></div>
                      <div class="col-md-7"><input type="text" id="txt_total" class="form-control form-control-sm" readonly style="text-align: center; background-color: #e9ecef; font-weight: bold;"></div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <input type="hidden" id="hd_modograbar_lote">
            <input type="hidden" id="hd_id_lote">
            <input type="hidden" id="hd_id_elemento">
            <input type="hidden" id="hd_cc_proveedorruc"> <!-- Para Proveedor de Condiciones Comerciales -->
            <input type="hidden" id="hd_fila_edicion">

            <!-- FOOTER -->
            <div class="modal-footer">
              <div id="wt_grabarlote" style="font-size: 12px; padding-top: 10px; display: none;">
                <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                <label style="font-style: italic;"> Grabando lote...</label>
              </div>

              <button type="button" class="btn btn-secondary wt_grabarlote_button" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary wt_grabarlote_button" onclick="f_GrabarLoteValorizacion();">Agregar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modal_AddCuentaBancaria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_AddCuentaBancaria" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="border: solid; border-width: 1px; border-color: #816951;">
            <div class="modal-header">
              <h1 class="modal-title fs-6" id="modal_AddCuentaBancaria"> Nueva Cuenta Bancaria</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

              <div class="row" style="padding: 5px;">
                <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                  Banco:
                </div>

                <div class="col-md-8 col-sm-8 col-xs-8">
                  <select id="cliente_banco_id_banco" class="form-select" style="text-align: left;">
                    <option selected value="">Elija una opción...</option>

                    <?php

                    $q_banco = "SELECT Id,
                                         descripcion
                                    FROM tb_bancos
                                   WHERE estado = 'A'";

                    if ($res_banco = mysqli_query($enlace, $q_banco)) {
                      if (mysqli_num_rows($res_banco) > 0) {
                        while ($row_banco = mysqli_fetch_array($res_banco)) {
                    ?>

                          <option value="<?php echo $row_banco["Id"]; ?>"><?php echo $row_banco["descripcion"]; ?></option>

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
                  Moneda:
                </div>

                <div class="col-md-8 col-sm-8 col-xs-8">
                  <select id="cliente_banco_id_moneda" class="form-select" style="text-align: left;">
                    <option selected value="">Elija una opción...</option>

                    <?php

                    $q_moneda = "SELECT Id,
                                          descripcion
                                     FROM tbconfig_monedas
                                    WHERE estado = 'A'";

                    if ($res_moneda = mysqli_query($enlace, $q_moneda)) {
                      if (mysqli_num_rows($res_moneda) > 0) {
                        while ($row_moneda = mysqli_fetch_array($res_moneda)) {
                    ?>

                          <option value="<?php echo $row_moneda["Id"]; ?>"><?php echo $row_moneda["descripcion"]; ?></option>

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
                  Número de cuenta:
                </div>

                <div class="col-md-8 col-sm-8 col-xs-8">
                  <input id="cliente_banco_num_cuenta" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                </div>
              </div>

              <div id="div_cuentacci" class="row" style="padding: 5px; display: none;">
                <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                  CCI:
                </div>

                <div class="col-md-8 col-sm-8 col-xs-8">
                  <input id="cliente_banco_cci" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;">
                </div>
              </div>
            </div>

            <input id="hd_iscuentadetraccion" type="hidden">

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary" onclick="f_GrabarCuentaBancaria();">Grabar</button>
            </div>
          </div>
        </div>
      </div>


      <!-- MODAL: SELECCIÓN DE ANTICIPOS -->
      <div class="modal fade" id="modal_seleccion_anticipos" tabindex="-1" aria-labelledby="modal_seleccion_anticiposLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" style="border: solid; border-width: 1px; border-color: #816951;">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_seleccion_anticiposLabel">
                <i class="bi bi-wallet2 me-2"></i>Seleccionar Anticipos
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="alert alert-info" style="font-size: 13px;">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Instrucciones:</strong> Seleccione los anticipos en orden, comenzando por el más antiguo.
                  </div>
                </div>
                <div class="col-md-6 text-end">
                  <div class="badge bg-primary fs-6">
                    Monto a cubrir: <span id="lbl_monto_a_cubrir" class="fw-bold">0.00</span>
                  </div>
                  <div class="badge bg-success fs-6 mt-1">
                    Total seleccionado: <span id="lbl_total_seleccionado" class="fw-bold">0.00</span>
                  </div>
                  <div class="badge bg-warning fs-6 mt-1">
                    Restante: <span id="lbl_monto_restante" class="fw-bold" style="color: #25476a !important;">0.00</span>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" style="font-size: 13px;">
                      <thead class="table-dark">
                        <tr>
                          <th width="50" class="text-center">#</th>
                          <th width="100" class="text-center">Seleccionar</th>
                          <th>Factura</th>
                          <th width="120" class="text-center">Saldo Inicial</th>
                          <th width="120" class="text-center">Saldo Actual</th>
                          <th width="120" class="text-center">Fecha Registro</th>
                          <th width="150" class="text-center">Monto a Usar</th>
                        </tr>
                      </thead>
                      <tbody id="tbl_anticipos_disponibles">
                        <tr>
                          <td colspan="7" class="text-center">Cargando anticipos...</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" onclick="f_ConfirmarSeleccionAnticipos();">
                <i class="bi bi-check-circle me-1"></i>Confirmar selección
              </button>
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
      $("#nv_titulo").html('| Valorización de Compra');

      // Cargando listas generales
      f_LoadProveedoresValorizacion();
      // f_LoadElementosValorizacion();

      // Cargando datos
      f_LoadValorizaciones();
    }
  </script>

  <!-- Seteando objetos Select2 -->
  <script type="text/javascript">
    // // Listas para edición
    //   $('#cmb_proveedor').select2({
    //     theme: "bootstrap-5",
    //     width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    //     placeholder: $( this ).data( 'placeholder' ),
    //     allowClear: true,
    //     dropdownParent: $('#modal_valorizacion')
    //   });

    // $('.select2-search__field').css('font-size', '14px');
    // $('.select2-selection__rendered').css('font-size', '14px');
  </script>

  <!-- Funciones Principales -->
  <script type="text/javascript">
    // Variables globales para anticipos
    let anticiposDisponibles = [];
    let anticiposSeleccionados = [];
    let montoTotalValorizacion = 0;

    // Función para actualizar automáticamente el tipo de pago y controles relacionados
    function f_ActualizarTipoPago() {
      const totalValorizacion = getMontoTotalValorizacionNumerico();
      const totalAnticipos = f_CalcularTotalSeleccionadoNumerico();
      const tieneLotes = $('#tbody_lotes_valorizacion tr').not('#tr_TotalValorizacion').length > 0;

      // Deshabilitar botón de anticipos si no hay lotes
      const btnAnticipos = $('button[onclick*="f_AbrirModalSeleccionAnticipos"]');
      if (!tieneLotes || totalValorizacion === 0) {
        btnAnticipos.prop('disabled', true);
        btnAnticipos.attr('title', 'Agregue lotes primero');
        btnAnticipos.addClass('disabled');
      } else {
        btnAnticipos.prop('disabled', false);
        btnAnticipos.attr('title', '');
        btnAnticipos.removeClass('disabled');
      }

      // Determinar tipo de pago automáticamente
      let tipoPago = 'transferencia';
      let textoTipoPago = 'Transferencia Bancaria';
      let colorIndicador = 'info';
      let iconoTipo = 'bank';
      let habilitarBanco = true;

      if (totalAnticipos == 0) {
        // Solo transferencia bancaria
        tipoPago = 'transferencia';
        textoTipoPago = 'Transferencia Bancaria';
        colorIndicador = 'info';
        iconoTipo = 'bank';
        habilitarBanco = true;
      } else if (totalAnticipos >= totalValorizacion && totalAnticipos > 0) {
        // Solo anticipos (cubren el total o más)
        tipoPago = 'anticipo';
        textoTipoPago = 'Pago con Anticipo';
        colorIndicador = 'success';
        iconoTipo = 'wallet2';
        habilitarBanco = false; // Deshabilitar porque anticipos cubren todo
      } else if (totalAnticipos > 0 && totalAnticipos < totalValorizacion) {
        // Pago mixto (anticipos parciales)
        tipoPago = 'mixto';
        textoTipoPago = 'Pago Mixto (Anticipo + Transferencia)';
        colorIndicador = 'warning';
        iconoTipo = 'cash-coin';
        habilitarBanco = true; // Habilitar para cubrir el saldo
      }

      // Actualizar indicador visual
      const indicador = $('#indicador_tipo_pago');
      indicador.removeClass('alert-info alert-success alert-warning alert-primary')
        .addClass('alert-' + colorIndicador);

      $('#txt_tipo_pago').html(`<i class="bi bi-${iconoTipo} me-2"></i>${textoTipoPago}`);

      // AMBAS SECCIONES SIEMPRE VISIBLES - No ocultar ninguna
      $('#div_informacion_bancaria').show();
      $('#div_gestion_anticipos').show();

      // Habilitar/Deshabilitar cuentas bancarias
      if (habilitarBanco) {
        console.log('Habilitando cuentas bancarias');
        $('#valorizacion_cuentaproveedor').prop('disabled', false);
        $('#valorizacion_cuentadetraccionproveedor').prop('disabled', false);
      } else {
        console.log('Deshabilitando cuentas bancarias');
        $("#valorizacion_cuentaproveedor").val('[Seleccione una Cuenta Bancaria]');
        $("#valorizacion_cuentadetraccionproveedor").val('[Seleccione una Cuenta de Detracción]');
        $('#valorizacion_cuentaproveedor').prop('disabled', true);
        $('#valorizacion_cuentadetraccionproveedor').prop('disabled', true);
      }

      // Actualizar información de saldo restante y monto de transferencia
      if (tipoPago === 'mixto') {
        const saldoRestante = totalValorizacion - totalAnticipos;
        $('#lbl_saldo_restante').text('$ ' + f_RedondearDecimales(saldoRestante, 2));
        $('#txt_monto_transferencia').val(f_RedondearDecimales(saldoRestante, 2).replace(',', ''));
        $('#div_saldo_restante').show();
        $('#div_monto_transferencia').show();
      } else {
        $('#div_saldo_restante').hide();
        $('#div_monto_transferencia').hide();
      }

      // Retornar tipo de pago para uso en otras funciones
      return tipoPago;
    }

    // Mantener para compatibilidad con código existente
    function f_ToggleTipoPago() {
      f_ActualizarTipoPago();
    }

    function f_ActualizarSaldoRestante() {
      const montoTotal = getMontoTotalValorizacionNumerico();
      const totalAnticipos = f_CalcularTotalSeleccionado();
      const saldoRestante = montoTotal - totalAnticipos;

      // Actualizar campos
      $('#lbl_saldo_restante').text(f_RedondearDecimales(saldoRestante, 2));
      $('#txt_monto_transferencia').val(f_RedondearDecimales(saldoRestante, 2).replace(',', ''));

      // Mostrar/ocultar según corresponda
      if (saldoRestante > 0) {
        $('#div_saldo_restante').show();
      } else {
        $('#div_saldo_restante').hide();
      }
    }

    function f_ValidarEstadoBotones() {
      const tipoPago = $('input[name="tipo_pago"]:checked').val();
      const idProveedor = $('#cmb_proveedor').val();
      const idConcesion = $('#cmb_concesion').val();

      // Siempre requeridos
      if (!idProveedor || !idConcesion) {
        return false;
      }

      // Validaciones específicas por tipo de pago
      if (tipoPago === 'transferencia' || tipoPago === 'mixto') {
        const idCuentaBancaria = $('#valorizacion_cuentaproveedor').val();
        const idCuentaDetraccion = $('#valorizacion_cuentadetraccionproveedor').val();

        if (!idCuentaBancaria || idCuentaBancaria === '') {
          return false;
        }

        if (!idCuentaDetraccion || idCuentaDetraccion === '') {
          return false;
        }
      }

      return true;
    }

    // Función para abrir el modal de selección de anticipos
    function f_AbrirModalSeleccionAnticipos() {
      // Validar que haya un proveedor seleccionado
      const idProveedor = $('#cmb_proveedor').val();
      if (!idProveedor) {
        alert('Debe seleccionar un proveedor primero.');
        return;
      }

      // Obtener el monto total de la valorización
      montoTotalValorizacion = getMontoTotalValorizacion();

      if (montoTotalValorizacion <= 0) {
        alert('Debe agregar al menos un lote con valor mayor a cero.');
        return;
      }

      // Determinar tipo de pago
      const tipoPago = $('input[name="tipo_pago"]:checked').val();

      // Para pago mixto, permitir selección parcial
      if (tipoPago === 'mixto') {
        // El monto máximo que se puede cubrir con anticipos es el total
        $('#lbl_monto_a_cubrir').text(montoTotalValorizacion);
        $('#lbl_info_tipo').text('(Pago Mixto - Puede seleccionar parcialmente)');
      } else {
        // Para solo anticipos, debe cubrir el total
        $('#lbl_monto_a_cubrir').text(montoTotalValorizacion);
        $('#lbl_info_tipo').text('(Debe cubrir el total)');
      }

      // Limpiar selección previa si el monto ha cambiado significativamente
      const totalSeleccionadoPrev = f_CalcularTotalSeleccionado();
      if (Math.abs(totalSeleccionadoPrev - montoTotalValorizacion) > 100) {
        // Si hay una diferencia grande, recalcular automáticamente
        anticiposSeleccionados = [];
      }

      f_ActualizarTotalesModal();

      // Cargar anticipos disponibles
      f_CargarAnticiposDisponibles(idProveedor, montoTotalValorizacion);

      // Abrir el modal
      f_OpenModal('modal_seleccion_anticipos');
    }

    // Función para cargar anticipos disponibles
    function f_CargarAnticiposDisponibles(idProveedor, montoValorizacion) {
      $('#tbl_anticipos_disponibles').html('<tr><td colspan="7" class="text-center">Cargando anticipos...</td></tr>');

      $.post(url_api, {
        accion: 'getAnticiposByProveedor',
        id_proveedor: idProveedor,
        monto_valorizacion: montoValorizacion,
        es_para_seleccion: true
      }, function(data) {
        console.log('Datos de anticipos recibidos:', data); // Para debug

        if (data.estado == 1) {
          anticiposDisponibles = data.data || [];
          console.log('Anticipos disponibles:', anticiposDisponibles); // Para debug

          // Inicializar sin selección automática
          anticiposSeleccionados = [];

          // Renderizar tabla
          f_RenderizarTablaAnticipos();

          // Habilitar solo el primer checkbox inicialmente
          f_ActualizarHabilitacionCheckboxes();
        } else {
          $('#tbl_anticipos_disponibles').html(`<tr><td colspan="7" class="text-center text-danger">${data.msg || 'Error al cargar anticipos'}</td></tr>`);
        }
      }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
        $('#tbl_anticipos_disponibles').html('<tr><td colspan="7" class="text-center text-danger">Error de conexión al cargar anticipos</td></tr>');
      });
    }

    // Función para renderizar la tabla de anticipos
    function f_RenderizarTablaAnticipos() {
      let html = '';

      if (anticiposDisponibles.length === 0) {
        html = '<tr><td colspan="7" class="text-center">No hay anticipos disponibles para este proveedor.</td></tr>';
      } else {
        anticiposDisponibles.forEach((anticipo, index) => {
          // Usar propiedades seguras con valores por defecto
          const idAnticipo = anticipo.id_anticipo || anticipo.Id || 0;
          const factura = anticipo.factura || anticipo.num_factura || 'Sin factura';
          const saldoInicial = parseFloat(anticipo.saldo_inicial || anticipo.monto_inicial || 0);
          const saldoActual = parseFloat(anticipo.saldo_actual || anticipo.saldo || 0);
          const fechaRegistro = anticipo.fecha_registro || anticipo.fecha || '';

          // Determinar si está seleccionado
          const isSeleccionado = anticiposSeleccionados.some(a =>
            a.id_anticipo == idAnticipo || a.Id == idAnticipo
          );

          // Obtener monto seleccionado
          let montoSeleccionado = 0;
          if (isSeleccionado) {
            const anticipoSeleccionado = anticiposSeleccionados.find(a =>
              a.id_anticipo == idAnticipo || a.Id == idAnticipo
            );
            montoSeleccionado = anticipoSeleccionado ?
              parseFloat(anticipoSeleccionado.monto_a_usar || anticipoSeleccionado.monto || 0) : 0;
          }

          // Determinar si este checkbox debe estar habilitado
          // Inicialmente habilitamos todos, f_ActualizarHabilitacionCheckboxes restringirá si es necesario
          const estaHabilitado = true;

          // El input de monto está habilitado solo si el checkbox está seleccionado
          const inputHabilitado = isSeleccionado;

          html += `
                <tr id="tr_anticipo_${idAnticipo}" class="${isSeleccionado ? 'table-success' : ''}">
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">
                        <input type="checkbox" 
                               id="chk_anticipo_${idAnticipo}" 
                               ${isSeleccionado ? 'checked' : ''}
                               ${estaHabilitado ? '' : 'disabled'}
                               onchange="f_ToggleAnticipo(${idAnticipo}, ${saldoActual});">
                    </td>
                    <td>${factura}</td>
                    <td class="text-end">$ ${f_RedondearDecimales(saldoInicial, 2)}</td>
                    <td class="text-end">$ ${f_RedondearDecimales(saldoActual, 2)}</td>
                    <td class="text-center">${fechaRegistro.split(' ')[0] || fechaRegistro}</td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   id="txt_monto_${idAnticipo}" 
                                   class="form-control form-control-sm text-end" 
                                   value="${f_RedondearDecimales(montoSeleccionado, 2)}"
                                   min="0" 
                                   max="${saldoActual}"
                                   step="0.01"
                                   ${inputHabilitado ? '' : 'disabled'}
                                   onchange="f_ActualizarMontoAnticipo(${idAnticipo}, this.value);">
                        </div>
                    </td>
                </tr>
            `;
        });
      }

      $('#tbl_anticipos_disponibles').html(html);
      f_ActualizarTotalesModal();
      // Ejecutar validación de checkboxes después de renderizar
      f_ActualizarHabilitacionCheckboxes();
    }

    // Nueva función para actualizar el monto de un anticipo manualmente
    function f_ActualizarMontoAnticipo(idAnticipo, monto) {
      let montoNum = parseFloat(monto) || 0;

      // Buscar el anticipo en la lista de seleccionados
      const index = anticiposSeleccionados.findIndex(a =>
        a.id_anticipo == idAnticipo || a.Id == idAnticipo
      );

      if (index != -1) {
        const anticipo = anticiposSeleccionados[index];
        const montoTotalValorizacion = getMontoTotalValorizacionNumerico();

        // Calcular sumatoria de LOS DEMÁS anticipos seleccionados
        let otrosMontos = 0;
        anticiposSeleccionados.forEach((a, i) => {
          if (i !== index) {
            otrosMontos += parseFloat(a.monto_a_usar || 0);
          }
        });

        // El monto máximo que este anticipo puede tomar es:
        // 1. Su propio saldo
        // 2. Lo que falta para completar la valorización (Total - Otros)

        let remanenteGlobal = montoTotalValorizacion - otrosMontos;
        if (remanenteGlobal < 0) remanenteGlobal = 0;

        const maximoPermitido = Math.min(anticipo.saldo_actual, remanenteGlobal);

        // Validamos con un pequeño margen
        if (montoNum > maximoPermitido + 0.01) {
          if (maximoPermitido == anticipo.saldo_actual) {
            alert(`El monto no puede superar el saldo disponible ($${f_RedondearDecimales(anticipo.saldo_actual, 2)})`);
          } else {
            alert(`La suma de anticipos no puede superar el monto total de la valorización.`);
          }
          montoNum = maximoPermitido;
        }

        // Si es menor a 0
        if (montoNum < 0) montoNum = 0;

        // Actualizar el valor en el input y en el objeto
        $(`#txt_monto_${idAnticipo}`).val(montoNum.toFixed(2));

        anticipo.monto_a_usar = montoNum;
        anticipo.is_manual = true;

        f_ReasignarMontosAutomaticamente();
      }
    }

    // f_EstaCheckboxHabilitado ha sido eliminada


    function getMontoTotalValorizacionNumerico() {
      let total = 0;
      $('#tbody_lotes_valorizacion tr').each(function(i) {
        if ($(this).attr('id') === 'tr_TotalValorizacion') return;
        const text = $('#total_' + i).text();
        total += parseFloat(text.replace(/,/g, '')) || 0;
      });
      return total;
    }

    function f_AsignarMontosAutomaticamente() {
      const montoTotal = getMontoTotalValorizacionNumerico();
      let montoRestante = montoTotal;

      anticiposSeleccionados = [];

      anticiposDisponibles.forEach((anticipo, index) => {
        if (montoRestante <= 0) return;

        const montoDisponible = parseFloat(anticipo.saldo_actual);
        const montoAUsar = Math.min(montoDisponible, montoRestante);

        if (montoAUsar > 0) {
          anticiposSeleccionados.push({
            id_anticipo: anticipo.id_anticipo || anticipo.Id,
            factura: anticipo.factura,
            saldo_actual: anticipo.saldo_actual,
            monto_a_usar: montoAUsar,
            is_manual: false
          });

          montoRestante -= montoAUsar;
        }
      });

      // Actualizar toda la tabla para reflejar los cambios
      f_RenderizarTablaAnticipos();
    }

    // Reemplazar f_CalcularTotalSeleccionado() por:
    function f_CalcularTotalSeleccionadoNumerico() {
      return anticiposSeleccionados.reduce((total, anticipo) =>
        total + (parseFloat(anticipo.monto_a_usar) || 0), 0);
    }

    // Función para seleccionar/deseleccionar un anticipo
    // Función para seleccionar/deseleccionar un anticipo
    function f_ToggleAnticipo(idAnticipo, saldoActual) {
      const checkbox = $(`#chk_anticipo_${idAnticipo}`);
      const inputMonto = $(`#txt_monto_${idAnticipo}`);

      if (checkbox.is(':checked')) {
        // Añadir a seleccionados
        // Se añade con monto 0 o calculado, y luego Reasignar se encarga de darle valor
        anticiposSeleccionados.push({
          id_anticipo: idAnticipo,
          factura: anticiposDisponibles.find(a => a.id_anticipo == idAnticipo)?.factura || '',
          saldo_actual: saldoActual,
          monto_a_usar: 0,
          is_manual: false
        });

        inputMonto.prop('disabled', false);
      } else {
        // Remover
        anticiposSeleccionados = anticiposSeleccionados.filter(a => a.id_anticipo != idAnticipo);
        inputMonto.val('0.00');
        inputMonto.prop('disabled', true);
      }

      // Recalcular montos (esto asignará valor al nuevo check si hay espacio)
      f_ReasignarMontosAutomaticamente();
    }

    function f_ReasignarMontosAutomaticamente() {
      const montoTotal = getMontoTotalValorizacionNumerico();
      let montoAsignado = 0;

      // Separamos manuales y automáticos
      // Queremos mantener el orden de la lista original para asignación automática (FIFO)
      // Pero primero respetamos los manuales.

      // 1. Sumar manuales
      anticiposSeleccionados.forEach(anticipo => {
        if (anticipo.is_manual) {
          montoAsignado += parseFloat(anticipo.monto_a_usar || 0);
        }
      });

      // 2. Asignar automáticos
      // Ordenamos por indice en disponible para mantener orden visual/cronológico
      const seleccionadosAuto = anticiposSeleccionados.filter(a => !a.is_manual).sort((a, b) => {
        const idxA = anticiposDisponibles.findIndex(x => x.id_anticipo == a.id_anticipo);
        const idxB = anticiposDisponibles.findIndex(x => x.id_anticipo == b.id_anticipo);
        return idxA - idxB;
      });

      seleccionadosAuto.forEach(anticipo => {
        let disponibleAnticipo = parseFloat(anticipo.saldo_actual);
        let faltaPorCubrir = montoTotal - montoAsignado;
        if (faltaPorCubrir < 0) faltaPorCubrir = 0;

        let aUsar = Math.min(disponibleAnticipo, faltaPorCubrir);
        anticipo.monto_a_usar = aUsar;
        montoAsignado += aUsar;
      });

      // 3. Actualizar inputs en la UI
      anticiposSeleccionados.forEach(anticipo => {
        $(`#txt_monto_${anticipo.id_anticipo}`).val(parseFloat(anticipo.monto_a_usar).toFixed(2));
      });

      f_ActualizarTotalesModal();
      f_ActualizarHabilitacionCheckboxes();
    }

    // Nueva función para actualizar habilitación de checkboxes
    function f_ActualizarHabilitacionCheckboxes() {
      // Usar f_CalcularTotalSeleccionadoNumerico si existe y es consistente
      const totalSeleccionado = f_CalcularTotalSeleccionadoNumerico();
      const montoTotal = getMontoTotalValorizacionNumerico();

      // Si ya cubrimos el total (con un pequeño margen de error), deshabilitamos los NO seleccionados
      const estaCompleto = totalSeleccionado >= (montoTotal - 0.01);

      anticiposDisponibles.forEach((anticipo) => {
        // Compatibilidad de ID
        const id = anticipo.id_anticipo || anticipo.Id;
        const checkbox = $(`#chk_anticipo_${id}`);

        if (checkbox.is(':checked')) {
          // Seleccionados siempre habilitados
          checkbox.prop('disabled', false);
        } else {
          // No seleccionados: deshabilitados si ya está completo
          checkbox.prop('disabled', estaCompleto);
        }
      });
    }


    // Función para calcular el total seleccionado
    function f_CalcularTotalSeleccionado() {
      return anticiposSeleccionados.reduce((total, anticipo) => total + parseFloat(anticipo.monto_a_usar || 0), 0);
    }

    // Función para actualizar los totales en el modal
    function f_ActualizarTotalesModal() {
      const totalSeleccionado = f_CalcularTotalSeleccionado();
      let floatMontoTotal = parseFloat(getMontoTotalValorizacion().replace(",", ""));
      const restante = floatMontoTotal - totalSeleccionado;

      $('#lbl_total_seleccionado').text(f_RedondearDecimales(totalSeleccionado, 2));
      $('#lbl_monto_restante').text(f_RedondearDecimales(restante, 2));

      // Cambiar color según el restante
      const lblRestante = $('#lbl_monto_restante');
      if (restante <= 0) {
        lblRestante.removeClass('text-warning').addClass('text-success');
      } else {
        lblRestante.removeClass('text-success').addClass('text-warning');
      }
    }

    // Función para confirmar la selección de anticipos
    function f_ConfirmarSeleccionAnticipos() {
      const totalSeleccionado = f_CalcularTotalSeleccionado();
      const montoTotal = getMontoTotalValorizacionNumerico();

      // Validación básica
      if (totalSeleccionado <= 0) {
        alert('Debe seleccionar al menos un anticipo.');
        return;
      }

      if (totalSeleccionado > montoTotal) {
        alert('El total seleccionado supera el monto total de la valorización.');
        return;
      }

      // Actualizar el campo de anticipos seleccionados
      let html = '';
      if (anticiposSeleccionados.length > 0) {
        anticiposSeleccionados.forEach(anticipo => {
          if (anticipo.monto_a_usar > anticipo.saldo_actual) {
            alert(`El monto no puede superar el saldo disponible del anticipo ${anticipo.factura}`);
            return;
          }
          if (anticipo.monto_a_usar > montoTotal) {
            alert(`El monto no puede superar el monto total de la valorización`);
            return;
          }
          if (anticipo.monto_a_usar > 0) {
            html += `<div class="mb-1">
                        <span class="badge bg-primary me-2">${anticipo.factura}</span>
                        <span>Monto: $ ${f_RedondearDecimales(anticipo.monto_a_usar, 2)}</span>
                    </div>`;
          }
        });
        html += `<div class="mt-2 fw-bold">Total anticipos: $ ${f_RedondearDecimales(totalSeleccionado, 2)}</div>`;

        // Si hay saldo restante, informar
        const saldoRestante = montoTotal - totalSeleccionado;
        if (saldoRestante > 0) {
          html += `<div class="mt-1 fw-bold text-warning">Saldo restante: $ ${f_RedondearDecimales(saldoRestante, 2)}</div>`;
          html += `<small class="text-muted">Se cubrirá con transferencia bancaria</small>`;
        }
      } else {
        html = 'Ningún anticipo seleccionado';
      }

      $('#txt_anticipos_seleccionados').html(html);

      // Actualizar tipo de pago automáticamente
      f_ActualizarTipoPago();

      // Cerrar el modal
      f_cerrarModal('modal_seleccion_anticipos');
    }

    // Función para limpiar la selección de anticipos
    function f_LimpiarSeleccionAnticipos() {
      anticiposSeleccionados = [];
      $('#txt_anticipos_seleccionados').html('Ningún anticipo seleccionado');
      f_ActualizarTotalesModal();
      f_ActualizarTipoPago(); // Actualizar tipo de pago automáticamente
    }

    function f_LoadValorizaciones() {
      $('#tbl_valorizaciones').html('');
      $('#wt_valorizaciones').show();

      $.post(url_api, {
        accion: 'get_ValorizacionCompra_ListaValorizaciones'
      }, function(data) {
        $('#wt_valorizaciones').hide();

        if (data.estado == 1) {
          // 1) Conteo por correlativo (para saber si hay múltiples versiones)
          const counts = {};
          // 2) Mapa de grupos aprobados: correlativo -> boolean (si alguna está aprobada)
          const gruposAprobados = {};

          (data.registros || []).forEach(r => {
            const key = String(r.correlativo || '');
            counts[key] = (counts[key] || 0) + 1;

            // Si alguna valorización del grupo está aprobada, marcamos el grupo como aprobado
            if (r.is_aprobado == 1) {
              gruposAprobados[key] = true;
            } else if (gruposAprobados[key] === undefined) {
              gruposAprobados[key] = false;
            }
          });

          // 3) Paleta por grupo
          const PALETTE = [{
              border: '#2563eb',
              bg: '#e8f0ff',
              ink: '#1e3a8a'
            }, // azul
            {
              border: '#059669',
              bg: '#e6faf3',
              ink: '#065f46'
            }, // verde
            {
              border: '#d97706',
              bg: '#fff4e5',
              ink: '#92400e'
            }, // ámbar
            {
              border: '#6d28d9',
              bg: '#f2e8ff',
              ink: '#4c1d95'
            }, // violeta
            {
              border: '#db2777',
              bg: '#ffe8f2',
              ink: '#9d174d'
            }, // rosa
            {
              border: '#0ea5e9',
              bg: '#e6f7fd',
              ink: '#075985'
            }, // celeste
          ];

          let _html = '';
          let i = 1,
            prevCorrelativo = null,
            groupIndex = -1;

          console.log(data.registros);
          $.each(data.registros, function(x, row) {
            // Estado (tu misma lógica)
            let estado_txt = '',
              estado_color = '';
            if (row.estado == 'A') {
              estado_txt = 'Activo';
              estado_color = '#2E8B57';
            } else if (row.estado == 'I') {
              estado_txt = 'Inactivo';
              estado_color = '#e0a800';
            } else if (row.estado == 'R') {
              estado_txt = 'Eliminado por anticipo';
              estado_color = '#dc3545';
            } else {
              estado_txt = 'Eliminado';
              estado_color = '#dc3545';
            }

            // Detectar cambio de grupo
            const corrKey = String(row.correlativo || '');
            let isGroupStart = false;
            if (corrKey !== prevCorrelativo) {
              groupIndex++;
              prevCorrelativo = corrKey;
              isGroupStart = true;
            }
            const pal = PALETTE[groupIndex % PALETTE.length];

            // Estilos por grupo
            const groupStyles = `
                    --group-border:${pal.border};
                    --group-bg:${pal.bg};
                    --group-ink:${pal.ink};
                    border-left:3px solid var(--group-border);
                    background-color:var(--group-bg);
                `;

            // Indicadores
            const totalVersions = counts[corrKey] || 1;
            const hasMany = totalVersions > 1;
            const versionsIcon = hasMany ? `<i class="bi bi-layers" title="Tiene ${totalVersions} versiones"></i>` : '';
            const versionBadge = `<span class="badge-version" style="color:${pal.border}; background:#fff;">v${row.version}</span>`;

            // Verificar si el grupo tiene alguna valorización aprobada
            const grupoTieneAprobado = gruposAprobados[corrKey] || false;

            // --- HTML de la Fila (Inicio) ---
            _html += `
                <tr class="valo-row ${isGroupStart ? 'group-start' : ''}"
                    data-id="${row.Id}" data-codigo="${row.correlativo}"
                    data-ruc="${row.ruc}" data-proveedor="${row.proveedor}"
                    style="font-size:12px; cursor:pointer; ${groupStyles}"
                    onclick="f_SelectValorizacion(this);">
                    <td id="tdvalorizaciones_1_${i}" style="text-align:center; vertical-align:middle;">${i}</td>

                    <td id="tdvalorizaciones_2_${i}" style="text-align:center; vertical-align:middle;"
                        onclick="f_PrintValorizacion('${row.ID_MD5}'); event.stopPropagation();">
                        <span class="corr-wrapper" style="color:${pal.ink}">
                            ${versionsIcon}
                            <u>${row.correlativo}</u>
                        </span>
                    </td>

                    <td id="tdvalorizaciones_3_${i}" style="text-align:center; vertical-align:middle;">${row.nro_oficio || '---'}</td>
                    <td id="tdvalorizaciones_4_${i}" style="text-align:center; vertical-align:middle;">${row.ruc}</td>
                    <td id="tdvalorizaciones_5_${i}" style="text-align:center; vertical-align:middle;">${row.proveedor}</td>

                    <td style="text-align:center; vertical-align:middle;">${versionBadge}</td>

                    <td id="tdvalorizaciones_6_${i}" style="text-align:center; vertical-align:middle;">
                        <b>${row.usuario_registro}</b><br>${row.fechahora_registro}
                    </td>

                    <td id="tdvalorizaciones_7_${i}" style="text-align:center; vertical-align:middle;">
                `;

            // Lógica de Aprobación
            if (row.IS_VALORIZACIONAPROBADA == 0 && row.estado == 'A') {
              // Solo mostrar botón "Aprobar" si el grupo NO tiene ya una aprobada
              if (!grupoTieneAprobado) {
                _html += `<button class="btn btn-primary btn-sm" style="font-size: 13px;" onclick="event.stopPropagation(); f_AprobarValorizacion(${row.Id}, ${row.correlativo}, ${row.version});">
                                Aprobar
                            </button>`;
              } else {
                _html += `<span class="text-muted" style="font-size: 12px;">No disponible</span>`;
              }
            } else {
              if (row.is_aprobado == 1) {
                _html += `<b>${row.is_aprobado_usuarioregistro}</b><br>${row.is_aprobado_fechahoraregistro}`;
              }
            }

            // Cierre de TD 7
            _html += `
                                    </td>

                            <td id="tdvalorizaciones_8_${i}" class="text-center estado-pill" style="background-color:${estado_color}; vertical-align:middle;">
                                ${estado_txt}
                            </td>

                            <td class="text-center" style="vertical-align:middle;">
                        `;

            const is_aprobado = row.is_aprobado == 1;
            const tiene_comprobante = row.tiene_comprobante == 1;

            if (is_aprobado && !tiene_comprobante) {
              // Si está aprobada y no tiene comprobante, mostrar solo botón "Reabrir"
              _html += `
                        <button class="btn btn-sm btn-warning" title="Reabrir Valorización" style="margin:2px;"
                            onclick="event.stopPropagation(); f_ReabrirValorizacion(${row.Id});">
                            <i class="bi bi-arrow-counterclockwise"></i> Reabrir
                        </button>
                    `;
            } else if (is_aprobado && tiene_comprobante) {
              // Si está aprobada y tiene comprobante, mostrar solo botón "Reabrir"
              _html += `
                        <div class="d-flex justify-content-center align-items-center">
                                <span class="text-muted" style="font-size: 11px;">
                                    Ya cuenta con comprobante
                                </span>
                        </div>
                    `;
            }
            // si esta eliminado por anticipos, solo permitir eliminar
            else if (row.estado == "R") {
              _html += `
                        <div class="d-flex justify-content-center align-items-center">
                            <button class="btn btn-sm btn-danger" title="Eliminar" style="margin:2px;"
                                onclick="event.stopPropagation(); f_EliminarValorizacion(${row.Id});">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        `;
            } else {
              // Si NO está aprobada individualmente...
              // Verificar si el grupo tiene alguna aprobada
              if (grupoTieneAprobado) {
                // Si el grupo tiene alguna aprobada, ocultar todos los botones
                _html += `
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="text-muted" style="font-size: 11px;">
                                    Hay una copia aprobada
                                </span>
                            </div>
                        `;
              } else {
                // Si el grupo NO tiene aprobaciones, mostrar los 4 botones normales
                _html += `
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex flex-column mb-1">
                                    <button class="btn btn-sm btn-primary" title="Editar" style="margin:2px;"
                                        onclick="event.stopPropagation(); f_AdminValorizacion('E', ${i}, ${row.Id}, '${row.num_oficio || ''}', ${row.id_proveedor || ''}, ${row.id_concesion || ''}, ${row.id_cuentabancaria}, ${row.id_cuentadetraccion});">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button class="btn btn-sm btn-secondary" title="Nueva versión" style="margin:2px;"
                                        onclick="event.stopPropagation(); f_NuevaVersion(${row.Id}, ${row.correlativo});">
                                        <i class="bi bi-clouds"></i>
                                    </button>
                                </div>

                                <div class="d-flex flex-column mb-1">
                                    ${row.estado == 'A'
                                        ? `<button class="btn btn-sm btn-warning" title="Inactivar" style="margin:2px;"
                                                onclick="event.stopPropagation(); f_CambiarEstadoValorizacion(${row.Id}, 'I');">
                                                <i class="bi bi-x-circle"></i>
                                            </button>`
                                        : `<button class="btn btn-sm btn-success" title="Activar" style="margin:2px;"
                                                onclick="event.stopPropagation(); f_CambiarEstadoValorizacion(${row.Id}, 'A');">
                                                <i class="bi bi-check-circle"></i>
                                            </button>`
                                    }
                                    <button class="btn btn-sm btn-danger" title="Eliminar" style="margin:2px;"
                                        onclick="event.stopPropagation(); f_EliminarValorizacion(${row.Id});">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
              }
            }

            // Cierre de TD 9 y de la Fila
            _html += `
                    </td>
                </tr>
                `;
            i++;
          });

          $('#tbl_valorizaciones').html(_html);

          const firstRow = document.querySelector('#tbl_valorizaciones tr');
          if (firstRow) {
            f_SelectValorizacion(firstRow);
          }
        } else {
          $('#tbl_valorizaciones').html('<tr><td colspan="10" style="text-align:center;">No se encontraron registros.</td></tr>');
        }
      }, 'json');
    }

    function f_ReabrirValorizacion(id_valorizacion) {
      if (!confirm("¿Está seguro de reabrir esta valorización?\n\nSi usaste anticipos, se revertirán las transacciones.")) {
        return;
      }

      $.post(url_api, {
        accion: 'reabrir_Valorizacion',
        id_valorizacion: id_valorizacion
      }, function(data) {
        if (data.estado == 1) {
          alert('Valorización reabierta correctamente.');
          f_LoadValorizaciones();
        } else {
          alert('Error al reabrir la valorización: ' + (data.msg || 'Error desconocido'));
        }
      }, 'json');
    }

    function f_SelectValorizacion(tr) {
      // Resaltar visualmente la fila seleccionada
      $('#tbl_valorizaciones tr').css('background-color', ''); // limpia todos
      $(tr).css('background-color', '#FFF587'); // pinta seleccionado

      // Obtener ID
      let id_valorizacion = $(tr).data('id');
      let correlativo = $(tr).data('codigo');
      let ruc = $(tr).data('ruc');
      let proveedor = $(tr).data('proveedor');

      // Mostrar título y cargar detalle
      $('#lbl_titulovalorizacion').html('N° ' + correlativo + ' | ' + ruc + ' - ' + proveedor);
      id_valorizacion_Selected = id_valorizacion;
      f_LoadDetalleValorizacion(id_valorizacion);
    }

    function f_LoadDetalleValorizacion(id_valorizacion) {
      $('#tbl_valorizacion_detalle').html('');
      $('#wt_detallevalorizacion').show();

      $.post(url_api, {
        accion: 'get_ValorizacionCompra_Detalle',
        id_valorizacion: id_valorizacion
      }, function(data) {
        $('#wt_detallevalorizacion').hide();

        if (data.estado == 1) {
          let _html = '';
          let d = 0;

          $.each(data.registros, function(i, v) {
            _html += `
                <tr style="font-size: 13px;">
                  <td style="text-align: center; vertical-align: middle; font-weight: bold;">${v.elemento}</td>
                  <td id="lote_resumen_${d}" style="text-align: center; vertical-align: middle;">${v.cod_lote}</td>
                  <td id="gel_resumen_${d}" style="text-align: center; vertical-align: middle;">${v.cod_gel}</td>
                  <td style="text-align: center; vertical-align: middle;">${v.guiaremision_remitente}</td>
                  <td style="text-align: center; vertical-align: middle;">${v.guiaremision_transportista}</td>
                  <td style="text-align: center; vertical-align: middle;">${v.fecha_ingreso}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.pesto_tmh, 3)}</td>
                  <td style="text-align: right; vertical-align: middle;">${((v.porc_h20 == null) ? '' : f_RedondearDecimales(v.porc_h20, 2))}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.peso_tms, 3)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.ley_oztc, 3)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.porc_rec, 0)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.precio_inter, 2)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.precio_inter_desc, 2)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.maquila, 2)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.precio_reac, 2)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.factor, 4)}</td>
                  <td style="text-align: right; vertical-align: middle;">${f_RedondearDecimales(v.subtotal, 2)}</td>
                  <td style="text-align: right; vertical-align: middle; display: none;">${f_RedondearDecimales(v.incentivo, 2)}</td>
                  <td style="text-align: right; vertical-align: middle; display: none;">${f_RedondearDecimales(v.subtotal_final, 2)}</td>
                  <td id="total_resumen_${d}" style="text-align: right; vertical-align: middle; font-weight: bold;">${f_RedondearDecimales(v.total, 2)}</td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-danger" onclick="f_EliminarRegistro(${v.Id})">
                      <i class="bi bi-trash3-fill"></i>
                    </button>
                  </td>
                </tr>
              `;

            d++;
          });

          $('#tbl_valorizacion_detalle').html(_html);

          // Setear Total de Valorización (Resumen)
          f_GetTotalValorizacion_Resumen()
        } else {
          $('#tbl_valorizacion_detalle').html('<tr><td colspan="20" style="text-align:center;">No hay detalle disponible.</td></tr>');
        }
      }, 'json');
    }

    async function f_AdminValorizacion(_item, _pos = 0, _id_valorizacion = '', _num_oficio = '', _id_proveedor = '', _id_concesion = '', _id_cuentabancaria = '', _id_cuentadetraccion = '') {
      anticiposSeleccionados = [];
      anticiposDisponibles = [];
      montoTotalValorizacion = 0;
      // limpiamos los anticipos previamente seleccionados
      f_LimpiarSeleccionAnticipos();
      let titulo = "";
      let tipo = "";

      if (_item != 'x') {
        tipo = "E";
        titulo = 'Editar Valorización: <b class="text-primary">N° ' + $("#tdvalorizaciones_2_" + _pos).html() + ' | ' + $("#tdvalorizaciones_4_" + _pos).html() + ' - ' + $("#tdvalorizaciones_5_" + _pos).html() + '</b>';
      } else {
        tipo = "N";
        titulo = "Nueva Valorización";
      }

      // Seteando variables hidden
      $("#hd_idvalorizacion").val(_id_valorizacion);
      $("#hd_modograbar_valorizacion").val(tipo);

      // Setea Título
      $("#modal_valorizacionLabel").html(titulo);

      // Setea objetos
      $("#tbody_lotes_valorizacion").html('');
      // $("#valorizacion_cuentaproveedor").html('');
      // $("#valorizacion_cuentadetraccionproveedor").html('');
      $("#cmb_proveedor").prop('disabled', false);
      $("#cmb_concesion").prop('disabled', false);
      let monto_banco = 0;
      let es_mixto = false;
      if (tipo != "N") {
        $("#hd_id_valorizacion").val(_id_valorizacion);
        $("#txt_nro_oficio").val(f_CleanInjection(_num_oficio));
        // $("#cmb_proveedor").val(_id_proveedor).trigger('change');
        $("#cmb_proveedor").val(_id_proveedor);

        $("#cmb_proveedor").prop('disabled', true);
        $("#cmb_concesion").prop('disabled', false);

        // Esperar carga de concesiones antes de asignar valor
        await f_CargarConcesionesPorProveedor(_id_concesion);
        $("#cmb_concesion").val(_id_concesion);

        f_MostrarInfoConcesionSeleccionada();


        // Recarga detalle de lotes
        await f_CargarDetalleValorizacion_Editar(_id_valorizacion);

        // Cargar anticipos si la valorización usa anticipos
        $.post(url_api, {
          accion: 'getAnticiposByValorizacion',
          id_valorizacion: _id_valorizacion
        }, function(data) {
          if (data.estado == 1 && data.data.length > 0) {
            // Marcar que usa anticipos
            $('#chk_usar_anticipo').prop('checked', true);

            // Cargar anticipos seleccionados
            anticiposSeleccionados = data.data.map(trans => ({
              id_anticipo: trans.id_proveedor_anticipo,
              factura: trans.factura,
              saldo_actual: trans.saldo_actual,
              monto_a_usar: trans.monto_retirado
            }));

            // Mostrar en el campo de anticipos seleccionados
            let html = '';
            anticiposSeleccionados.forEach(anticipo => {
              html += `<div class="mb-1">
                    <span class="badge bg-primary me-2">${anticipo.factura}</span>
                    <span>Monto: $ ${f_RedondearDecimales(anticipo.monto_a_usar, 2)}</span>
                </div>`;
            });
            const total = anticiposSeleccionados.reduce((sum, a) => sum + parseFloat(a.monto_a_usar || 0), 0);
            html += `<div class="mt-2 fw-bold">Total: $ ${f_RedondearDecimales(total, 2)}</div>`;
            $('#txt_anticipos_seleccionados').html(html);
          }

          $.post(url_api, {
            accion: 'getTipoPagoValorizacion',
            id_valorizacion: _id_valorizacion
          }, async function(data) {
            if (data.estado == 1) {
              // Si la valorizacion solo ha usado anticipos, vaciar y deshabilitar 
              // las cuentas de banco
              if (data.tipo_pago == 'anticipo') {
                $("#valorizacion_cuentaproveedor").val('');
                $("#valorizacion_cuentadetraccionproveedor").val('');
                $("#valorizacion_cuentaproveedor").prop('disabled', true);
                $("#valorizacion_cuentadetraccionproveedor").prop('disabled', true);
              } else if (data.tipo_pago == 'mixto') {
                es_mixto = true;
              }

              $.post(url_api, {
                accion: 'get_montos_valorizacion',
                id_valorizacion: _id_valorizacion
              }, async function(data) {
                if (data.estado == 1) {
                  if (es_mixto) {
                    console.log("data: ", data);
                    monto_banco = data.monto_banco;
                    console.log("monto_banco: ", monto_banco);
                  }
                  // Carga listas de Cuentas
                  await f_LoadListaCuentasBancarias(_id_cuentabancaria);
                  await f_LoadListaCuentaDetraccion(_id_cuentadetraccion);

                  // Setear Total de Valorización
                  f_GetTotalValorizacion();
                  f_ActualizarTipoPago();
                  // Abre modal
                  if (es_mixto && monto_banco > 0) {
                    $('#txt_monto_transferencia').val(f_RedondearDecimales(monto_banco, 2).replace(',', ''));
                    $('#txt_monto_transferencia').prop('disabled', true);
                    $('#txt_monto_transferencia').show();
                  }
                  f_OpenModal('modal_valorizacion');

                  return;
                }
              }, 'json');
            }
          }, 'json');

        }, 'json');

      } else {
        $('#chk_usar_anticipo').prop('checked', false);
        $("#hd_id_valorizacion").val(0);
        $("#txt_nro_oficio").val('');
        $("#cmb_proveedor").val('').trigger('change');

        $("#txt_concesion").val('');
        $("#txt_codigo_unico").val('');
        $("#txt_procedencia").val('');
      }

      // Setear Total de Valorización
      f_GetTotalValorizacion();
      f_ActualizarTipoPago();
      // Abre modal
      if (es_mixto && monto_banco > 0) {
        $('#txt_monto_transferencia').val(f_RedondearDecimales(monto_banco, 2).replace(',', ''));
        $('#txt_monto_transferencia').prop('disabled', true);
        $('#txt_monto_transferencia').show();
      }
      f_OpenModal('modal_valorizacion');
    }

    function f_LoadProveedoresValorizacion() {
      let _html = '<option value="">[Seleccione proveedor]</option>';

      $.post(url_api, {
        accion: "get_ValorizacionCompra_ListaProveedores"
      }, function(data) {
        if (data.estado == 1) {
          $.each(data.registros, function(i, v) {
            _html += `<option value="${v.Id}">${v.documento} - ${v.razon_social}</option>`;
          });
          $("#cmb_proveedor").html(_html);
        }
      }, "json");
    }

    async function f_CargarConcesionesPorProveedor(_id_concesion) {
      return new Promise((resolve) => {
        let id_proveedor = $("#cmb_proveedor").val();
        $("#cmb_concesion").html('<option value="">[Seleccione concesión]</option>');
        $("#txt_codigo_unico").val('');
        $("#txt_procedencia").val('');

        if (id_proveedor == '') return resolve(); // ← Resuelve si no hay proveedor

        $.post(url_api, {
          accion: "get_ValorizacionCompra_ListaConcesiones",
          id_proveedor: id_proveedor
        }, function(data) {
          if (data.estado == 1) {
            let _html = '<option value="">[Seleccione concesión]</option>';
            $.each(data.registros, function(i, v) {
              _html += '<option ' + ((_id_concesion == v.Id) ? 'selected' : '') + ' value="' + v.Id + '" data-codigo="' + v.codigo_unico + '" data-procedencia="' + v.procedencia + '">' + v.descripcion + '</option>';
            });
            $("#cmb_concesion").html(_html);
          }
          resolve(); // ← Confirma que terminó la carga
        }, "json");
      });
    }

    function f_MostrarInfoConcesionSeleccionada() {
      let sel = $("#cmb_concesion option:selected");

      $("#txt_codigo_unico").val(sel.data("codigo") || '');
      $("#txt_procedencia").val(sel.data("procedencia") || '');

      f_CargarLotesDisponibles();
    }

    function f_AdminLotes(_item, index_fila = '', _id_lote = '', _id_elemento = '', _txt_lote = '', _txt_elemento = '', _inter = '', _desc_inter = '', _maquila = '', _reactivo = '', _recuperacion = '', _incentivo = '', _proveedor_ruc = '', _ley = '') {
      let titulo = '';
      let tipo = '';

      if (_item != 'x') {
        tipo = 'E';
        // titulo = 'Editar Lote: <b>' + _txt_elemento + ' - ' + _txt_lote + '</b>';
        titulo = 'Editar Lote:';

        // Si hay anticipos seleccionados y se está editando, pedir confirmación
        if (anticiposSeleccionados.length > 0) {
          if (!confirm('Al editar este lote se reiniciará la selección de anticipos.\n\n¿Desea continuar?')) {
            return;
          }
          f_LimpiarSeleccionAnticipos();
        }
      } else {
        tipo = 'N';
        titulo = 'Agregar Lote a Valorización';
      }

      // Validar proveedor/concesión
      const id_proveedor = $("#cmb_proveedor").val();
      const id_concesion = $("#cmb_concesion").val();
      const id_cuentabancaria = $("#valorizacion_cuentaproveedor").val();
      const id_cuentadetraccion = $("#valorizacion_cuentadetraccionproveedor").val();

      // En la función f_AdminLotes, reemplazar la sección de validación:
      if (tipo == 'N') {
        if (id_proveedor.length == 0) {
          alert("Antes de agregar un Lote debe seleccionar el Proveedor.");
          return;
        }

        if (id_concesion.length == 0) {
          alert("Antes de agregar un Lote debe seleccionar la Concesión.");
          return;
        }

        // Validar según tipo de pago
        const tipoPago = $('input[name="tipo_pago"]:checked').val();

        if (tipoPago === 'transferencia' || tipoPago === 'mixto') {
          const id_cuentabancaria = $("#valorizacion_cuentaproveedor").val();
          const id_cuentadetraccion = $("#valorizacion_cuentadetraccionproveedor").val();

          if (id_cuentabancaria.length == 0 || id_cuentabancaria == '') {
            alert("Antes de agregar un Lote debe seleccionar la Cuenta Bancaria del Proveedor.");
            return;
          }

          if (id_cuentadetraccion.length == 0 || id_cuentadetraccion == '') {
            alert("Antes de agregar un Lote debe seleccionar la Cuenta de Detracción del Proveedor.");
            return;
          }
        }

        // Para tipo mixto, verificar que haya anticipos seleccionados
        if (tipoPago === 'mixto' && anticiposSeleccionados.length === 0) {
          if (!confirm("No ha seleccionado anticipos para el pago mixto. ¿Desea continuar solo con transferencia?")) {
            return;
          }
          // Cambiar a solo transferencia
          $('#tipo_pago_transferencia').prop('checked', true).trigger('change');
        }
      }

      // Seteando objetos hidden
      $("#hd_id_lote").val(_id_lote);
      $("#hd_id_elemento").val(_id_elemento);
      $("#hd_cc_proveedorruc").val(_proveedor_ruc);

      // Título y modo
      $('#modal_nuevoloteLabel').html(titulo);
      $('#hd_modograbar_lote').val(tipo);
      $('#hd_fila_edicion').val(index_fila);

      // Limpiar campos
      $('#txt_codigo_gel').val('');
      $('#txt_grr').val('');
      $('#txt_grt').val('');
      $('#txt_fecha_ingreso').val('');
      $('#txt_tmh').val('');
      $('#txt_h2o').val('');
      $('#txt_tms').val('');
      $('#txt_ley').val('');
      $('#txt_recuperacion').val('');
      $('#txt_inter').val('');
      $('#txt_desc_inter').val('');
      $('#txt_maquila').val('');
      $('#txt_reactivo').val('');
      $('#txt_factor').val('');
      $('#txt_precio_tn').val('');
      $('#txt_incentivo').val('');
      $('#txt_precio_tn_final').val('');
      $('#txt_total').val('');

      if (tipo == 'E') {
        // Mostrar inputs estáticos, ocultar combos
        $('#div_lote_static').show();
        $('#div_elemento_static').show();
        $('#div_lote_combo').hide();
        $('#div_elemento_combo').hide();

        // Mostrar valores como texto en los labels
        $('#lbl_lote_static').html(`<strong>${_txt_lote}</strong>`);
        $('#lbl_elemento_static').html(`<strong>${_txt_elemento}</strong>`);

        // Asignar valores
        $('#txt_inter').val(_inter.replace(/,/g, ''));
        $('#txt_desc_inter').val(_desc_inter.replace(/,/g, ''));
        $('#txt_maquila').val(_maquila.replace(/,/g, ''));
        $('#txt_reactivo').val(_reactivo.replace(/,/g, ''));
        $('#txt_recuperacion').val(_recuperacion.replace(/,/g, ''));
        $('#txt_incentivo').val(_incentivo.replace(/,/g, ''));

        f_CargarDatosLotePorId(index_fila, _id_lote);

        // Cargando Condiciones Comrciales
        // f_ObtenerCondicionesComerciales();

        // Seteando objetos hidden
        $("#hd_id_lote").val(_id_lote);
        $("#hd_id_elemento").val(_id_elemento);
      } else {
        // Nuevo
        $('#hd_fila_edicion').val('');

        $('#cmb_lotes').prop('disabled', false);
        $('#cmb_elemento').prop('disabled', false);

        $('#div_lote_static').hide();
        $('#div_elemento_static').hide();
        $('#div_lote_combo').show();
        $('#div_elemento_combo').show();

        f_CargarLotesDisponibles();
      }

      // Mostrar modal
      f_OpenModal('modal_nuevolote');
    }

    function f_LoadElementosValorizacion() {
      var id_valorizacion = $("#hd_idvalorizacion").val();
      var cod_lote = $("#cmb_lotes option:selected").text().trim();

      $.post(url_api, {
        accion: "get_ValorizacionCompra_Elementos",
        id_valorizacion,
        cod_lote
      }, function(data) {
        if (data.estado == 1) {
          let html = '<option value="">[Seleccione]</option>';
          $.each(data.registros, function(i, e) {
            html += `<option value="${e.Id}">${e.abv}</option>`;
          });
          $('#cmb_elemento').html(html);
        }
      }, 'json');
    }

    function f_CargarLotesDisponibles() {
      let id_proveedor = $("#cmb_proveedor").val();
      let id_concesion = $("#cmb_concesion").val();

      if (id_proveedor == "" || id_concesion == "") return;

      // Limpiando Elementos
      $('#cmb_lotes').val('').trigger('change');

      // Cargando Lotes
      $.post(url_api, {
        accion: "get_ValorizacionCompra_LotesDisponibles",
        id_proveedor: id_proveedor,
        id_concesion: id_concesion
      }, function(data) {
        if (data.estado == 1) {
          let _html = '<option value="">[Seleccione]</option>';

          $.each(data.registros, function(i, v) {
            // si el lote ya lo eligio, lo saltamos
            let lotesSeleccionados = getLotesSeleccionados();
            if (lotesSeleccionados.some(lote => lote.cod_lote == v.lote_cod_lote)) {
              return;
            }
            _html += `<option value="${v.Id}"
                                data-idcodlote="${v.ID_CODLOTE}"
                                data-codgel="${v.CODIGO_GEL}"
                                data-grr="${v.GUIA_REMITENTE}"
                                data-grt="${v.GUIA_TRANSPORTISTA}"
                                data-fecha="${v.lote_pesoinicial_fechahoraregistro}"
                                data-tmh="${v.TMH}"
                                data-h2o="${v.h2o}"
                                data-tms="${v.tms}"
                                data-ley="${v.ley_au_oz}"
                                data-leyag="${v.ley_ag_oz}"
                                data-factor="${v.factor}"
                                data-sinvc="${v.IS_SINVALORCOMERCIAL}">
                            ${v.lote_cod_lote}
                          </option>`;
          });

          $('#cmb_lotes').html(_html);
        }

      }, "json");
    }

    $('#cmb_lotes').on('change', function() {
      const sel = $('#cmb_lotes option:selected');

      let id_codlote = parseFloat(sel.data('idcodlote')) || 0;
      let tmh = parseFloat(sel.data('tmh')) || '';
      let h2o = parseFloat(sel.data('h2o')) || '';
      let tms = ((h2o.length == 0) ? tmh : ((100 - h2o) / 100) * tmh);
      let ley = parseFloat(sel.data('ley')) || '';
      let factor = ((tmh.length == 0) ? '' : (tmh <= 1) ? 1 : 1.1023);

      $('#txt_codigo_gel').val(sel.data('codgel') || '');
      $('#txt_grr').val(sel.data('grr') || '');
      $('#txt_grt').val(sel.data('grt') || '');
      $('#txt_fecha_ingreso').val(sel.data('fecha') || '');
      $('#txt_tmh').val(((tmh.length == 0) ? '' : tmh.toFixed(3)));
      $('#txt_h2o').val(((h2o.length == 0) ? '' : h2o.toFixed(2)));
      $('#txt_tms').val(((tms.length == 0) ? '' : tms.toFixed(3)));
      // $('#txt_ley').val(((ley.length == 0) ? '' : ley.toFixed(5)));
      $('#txt_factor').val(factor);

      // Nueva llamada para filtrar elementos permitidos
      f_CargarElementosPorLote(id_codlote);

      // Setea Sin Valor Comercial
      $("#div_sinvalorcomercial").hide();

      $("#txt_inter").val('');
      // $("#txt_inter").prop('disabled', false);

      if (sel.data('sinvc') == 1) {
        $("#div_sinvalorcomercial").show();

        //   $("#txt_inter").val('0.00');
        //   $("#txt_inter").prop('disabled', true);
      }
    });

    $("#cmb_elemento").on('change', function() {
      f_ObtenerCondicionesComerciales();
    });

    function f_ObtenerCondicionesComerciales() {
      let ley_au_oz = '';
      let documento = '';

      // Limpia objetos
      $("#txt_recuperacion").val('');
      $("#txt_desc_inter").val('');
      $('#txt_maquila').val('');
      $('#txt_reactivo').val('');

      // Validando datos
      if ($("#cmb_elemento").val() == '') {
        return;
      }

      if ($("#hd_modograbar_lote").val() == 'N') {
        const sel = $('#cmb_lotes option:selected');

        // Obtiene Ley según el elemento seleccionado
        if ($("#cmb_elemento").val() == 33) { // Si selecciona Au
          ley_oz = parseFloat(sel.data('ley'), 2);
        }

        if ($("#cmb_elemento").val() == 34) { // Si selecciona Ag
          ley_oz = parseFloat(sel.data('leyag'), 2);
        }

        $("#txt_ley").val(f_RedondearDecimales(ley_oz, 2));

        documento = $('#cmb_proveedor option:selected').text().split(' - ')[0];
      } else {
        documento = $('#hd_cc_proveedorruc').val();
      }

      // Obtiene Condiciones Comerciales
      if ($("#cmb_elemento").val() == 33) {
        if ($("#hd_modograbar_lote").val() == 'N') {
          $.post(url_api, {
              accion: "get_ValorizacionCompra_CondicionesComerciales",
              documento: documento,
              ley: ley_oz
            },
            function(data) {
              if (data.estado == 1) {
                if ($("#hd_modograbar_lote").val() == 'N') {
                  $("#txt_recuperacion").val(data.recuperacion);
                  $("#txt_desc_inter").val(60);
                  $('#txt_maquila').val(data.maquila);
                  $('#txt_reactivo').val(data.consumo);
                }

                $('#txt_recuperacion').prop('disabled', false);
                $('#txt_maquila').prop('disabled', false);
                $('#txt_reactivo').prop('disabled', false);
              } else {
                $('#txt_recuperacion').val('C.C. No Definida');
                $('#txt_maquila').val('C.C. No Definida');
                $('#txt_reactivo').val('C.C. No Definida');

                $('#txt_recuperacion').prop('disabled', true);
                $('#txt_maquila').prop('disabled', true);
                $('#txt_reactivo').prop('disabled', true);
              }

              // Recalcular valores
              f_CalcularTotalesValorizacion();

            }, "json");
        }
      } else {
        $("#txt_recuperacion").val(40);
        $("#txt_desc_inter").val('');
        $('#txt_maquila').val('');
        $('#txt_reactivo').val('');
      }
    }

    function f_CalcularTotalesValorizacion() {
      // Obtener valores desde los inputs
      const inter = parseFloat($("#txt_inter").val()) || 0;
      const desc_inter = parseFloat($("#txt_desc_inter").val()) || 0;
      const ley = parseFloat($("#txt_ley").val()) || 0;
      const rec = parseFloat($("#txt_recuperacion").val()) || 0;
      const maquila = parseFloat($("#txt_maquila").val()) || 0;
      const react = parseFloat($("#txt_reactivo").val()) || 0;
      const factor = parseFloat($("#txt_factor").val()) || 0;
      const incentivo = parseFloat($("#txt_incentivo").val()) || 0;
      const tms = parseFloat($("#txt_tms").val()) || 0;

      // Calcular PRECIO * TN
      let precio_tn = ((inter - desc_inter) * ley * (rec / 100)) - maquila - react;
      precio_tn = precio_tn * factor;
      precio_tn = Math.round(precio_tn * 100) / 100;
      if (precio_tn < 0) precio_tn = 0;

      // Calcular PRECIO * TN (Final)
      let precio_tn_final = precio_tn + incentivo;
      if (precio_tn_final < 0) precio_tn_final = 0;

      // Calcular TOTAL
      let total = precio_tn_final * tms;
      // total = Math.round(total * 100) / 100;

      // Definir Precio y Total
      const sel = $('#cmb_lotes option:selected');

      if (sel.data('sinvc') == 1) {
        $("#txt_precio_tn").val(f_RedondearDecimales(0, 2));
        $("#txt_precio_tn_final").val(f_RedondearDecimales(0, 2));
        $("#txt_total").val(f_RedondearDecimales(0, 2));
      } else {
        $("#txt_precio_tn").val(f_RedondearDecimales(precio_tn, 2));
        $("#txt_precio_tn_final").val(f_RedondearDecimales(precio_tn_final, 2));
        $("#txt_total").val(f_RedondearDecimales(total, 2));
      }
    }

    function f_CargarElementosPorLote(id_lote, callback = null) {
      if (id_lote == '') {
        $('#cmb_elemento').html('<option value="">[Seleccione]</option>');
        return;
      }

      let usados = [];
      $('#tbody_lotes_valorizacion tr').each(function() {
        let elemento = $(this).data('elemento');
        let lote = $(this).data('lote');

        if (lote == id_lote) {
          usados.push(elemento.toString());
        }
      });

      // if (usados.includes('33') && usados.includes('34')) {
      //   alert('Este lote ya fue valorizado para Au y Ag.');
      //   $('#cmb_elemento').html('<option value="">[Seleccione]</option>');
      //   return;
      // }

      var id_valorizacion = $("#hd_idvalorizacion").val();
      var cod_lote = $("#cmb_lotes option:selected").text();

      $.post(url_api, {
        accion: "get_ValorizacionCompra_Elementos",
        id_valorizacion,
        cod_lote
      }, function(data) {
        if (data.estado == 1) {
          let _html = '<option value="">[Seleccione]</option>';
          data.registros.forEach(function(el) {
            if (!usados.includes(el.Id.toString())) {
              _html += `<option value="${el.Id}">${el.abv}</option>`;
            }
          });
          $('#cmb_elemento').html(_html);

          if (callback) callback(); // 👈 ejecutar callback al terminar
        }
      }, "json");
    }

    function f_CargarDatosLotePorId(_item, id_lote) {
      if ($("#hd_modograbar_lote").val() == 'N') {
        const sel = $('#cmb_lotes option[value="' + id_lote + '"]');

        let tmh = parseFloat(sel.data('tmh')) || '';
        let h2o = parseFloat(sel.data('h2o')) || '';
        let tms = (h2o === '' ? tmh : ((100 - h2o) / 100) * tmh);
        let ley = parseFloat(sel.data('ley')) || '';
        let factor = (tmh === '' ? '' : (tmh <= 1 ? 1 : 1.1023));

        $('#txt_codigo_gel').val(sel.data('codgel') || '');
        $('#txt_grr').val(sel.data('grr') || '');
        $('#txt_grt').val(sel.data('grt') || '');
        $('#txt_fecha_ingreso').val(sel.data('fecha') || '');
        $('#txt_tmh').val((tmh === '' ? '' : tmh.toFixed(3)));
        $('#txt_h2o').val((h2o === '' ? '' : h2o.toFixed(2)));
        $('#txt_tms').val((tms === '' ? '' : tms.toFixed(3)));
        $('#txt_ley').val((ley === '' ? '' : ley.toFixed(3)));
        $('#txt_factor').val(factor);
      } else {
        $('#txt_codigo_gel').val($("#gel_" + _item).html());
        $('#txt_grr').val($("#grr_" + _item).html());
        $('#txt_grt').val($("#grt_" + _item).html());
        $('#txt_fecha_ingreso').val($("#ingreso_" + _item).html());
        $('#txt_tmh').val(parseFloat($("#tmh_" + _item).html()).toFixed(3));
        $('#txt_h2o').val((($("#h2o_" + _item).html().length == 0) ? '' : parseFloat($("#h2o_" + _item).html()).toFixed(2)));
        $('#txt_tms').val(parseFloat($("#tms_" + _item).html()).toFixed(3));
        $('#txt_ley').val((($("#ley_" + _item).html().length == 0) ? '<label style="color: #F23030;">Pendiente</label>' : parseFloat($("#ley_" + _item).html()).toFixed(3)));
        $('#txt_factor').val(parseFloat($("#factor_" + _item).html()).toFixed(4));
      }

      // f_ObtenerCondicionesComerciales();

      // Recalcular Totales
      f_CalcularTotalesValorizacion();
    }

    function f_PrintValorizacion(_idmd5_valorizacion) {
      var url = 'print_valorizacion_compramineral.php?x=' + _idmd5_valorizacion;

      window.open(url, '_blank', "");
    }

    async function f_CargarDetalleValorizacion_Editar(id_valorizacion) {
      $('#tbody_lotes_valorizacion').html('');

      const data = await $.post(url_api, {
        accion: 'get_ValorizacionCompra_Detalle',
        id_valorizacion
      });

      if (data.estado === 1) {
        let _html = '';

        data.registros.forEach((v, i) => {
          const total_str = f_RedondearDecimales(v.total, 2);
          const btn_editar = `f_AdminLotes('E', ${i}, '${v.ID_LOTE}', '${v.id_elemento}', '${v.cod_lote}', '${v.elemento_original}', '${f_RedondearDecimales(v.precio_inter, 2)}', '${f_RedondearDecimales(v.precio_inter_desc, 2)}', '${f_RedondearDecimales(v.maquila, 2)}', '${f_RedondearDecimales(v.precio_reac, 2)}', '${f_RedondearDecimales(v.porc_rec, 0)}', '${v.incentivo || ''}', '${v.PROVEEDOR_RUC}', ${f_RedondearDecimales(v.ley_oztc, 3)})`;

          _html += `
              <tr data-elemento="${v.id_elemento}" data-lote="${v.ID_LOTE}" style="font-size: 14px;">
                <td id="elemento_${i}" style="text-align:center; vertical-align: middle;">${v.elemento_original}</td>
                <td id="lote_${i}" style="text-align:center; vertical-align: middle; font-weight:bold;">${v.cod_lote}</td>
                <td id="gel_${i}" style="text-align:center; vertical-align: middle;">${v.cod_gel}</td>
                <td id="grr_${i}" style="text-align:center; vertical-align: middle;">${v.guiaremision_remitente}</td>
                <td id="grt_${i}" style="text-align:center; vertical-align: middle;">${v.guiaremision_transportista}</td>
                <td id="ingreso_${i}" style="text-align:center; vertical-align: middle;">${v.fecha_ingreso}</td>
                <td id="tmh_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.pesto_tmh, 3)}</td>
                <td id="h2o_${i}" style="text-align:right; vertical-align: middle;">${v.porc_h20 != null ? f_RedondearDecimales(v.porc_h20, 2) : ''}</td>
                <td id="tms_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.peso_tms, 3)}</td>
                <td id="ley_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.ley_oztc, 3)}</td>
                <td id="rec_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.porc_rec, 0)}</td>
                <td id="inter_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.precio_inter, 2)}</td>
                <td id="descint_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.precio_inter_desc, 2)}</td>
                <td id="maquila_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.maquila, 2)}</td>
                <td id="react_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.precio_reac, 2)}</td>
                <td id="factor_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.factor, 4)}</td>
                <td id="ptn_${i}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(v.subtotal, 2)}</td>
                <td id="incent_${i}" style="text-align:right; vertical-align: middle; display: none;">${v.incentivo ? f_RedondearDecimales(v.incentivo, 2) : ''}</td>
                <td id="ptnf_${i}" style="text-align:right; vertical-align: middle; display: none;">${f_RedondearDecimales(v.subtotal_final, 2)}</td>
                <td id="total_${i}" style="text-align:right; vertical-align: middle; font-weight:bold;">${total_str}</td>
                <td class="text-center">
                  <button id="btn_edit_${i}" type="button" class="btn btn-sm btn-warning me-1" onclick="${btn_editar}">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button class="btn btn-sm btn-danger" onclick="f_ConfirmarEliminarLote(this);"><i class="bi bi-trash3-fill"></i></button>
                </td>
              </tr>`;
        });

        $('#tbody_lotes_valorizacion').html(_html);
      }
    }

    function f_ConfirmarEliminarLote(btn) {
      if (!confirm("¿Está seguro de eliminar este lote?\n\n⚠️ ADVERTENCIA: Esto reiniciará la selección de anticipos.")) {
        return;
      }

      // Eliminar la fila
      $(btn).closest('tr').remove();

      // Reiniciar selección de anticipos
      f_ReiniciarAnticipos();

      // Renumerar filas
      f_RenumerarFilas();
    }

    // Nueva función para reiniciar anticipos
    function f_ReiniciarAnticipos() {
      const tipoPago = $('input[name="tipo_pago"]:checked').val();

      if (tipoPago === 'anticipo' || tipoPago === 'mixto') {
        if (anticiposSeleccionados.length > 0) {
          alert("La selección de anticipos ha sido reiniciada debido a cambios en los lotes.");

          // Limpiar selección
          f_LimpiarSeleccionAnticipos();

          // Si es modo mixto, también limpiar monto de transferencia
          if (tipoPago === 'mixto') {
            $('#txt_monto_transferencia').val('');
            $('#div_saldo_restante').hide();
          }
        }
      }
    }


    async function f_LoadListaCuentasBancarias(_id_registro) {
      let _html = '<option value="" selected>[Seleccione una Cuenta Bancaria]</option>';
      const $select = $("#valorizacion_cuentaproveedor");
      const id_proveedor = $("#cmb_proveedor").val();

      $select.prop('disabled', true).html(_html);

      const id_moneda = 2;

      try {
        const response = await fetch(url_api, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            accion: "get_ValorizacionCompra_ListaCuentasBancariasProveedor",
            id_moneda: id_moneda,
            id_proveedor: id_proveedor,
            is_detraccion: 0
          })
        });

        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        if (data.estado === 1) {
          data.registros.forEach((v) => {
            _html += `<option value="${v.Id}">${v.BANCO} (${v.MONEDA}) | N° Cuenta: ${v.nro_cuenta} | CCI: ${v.cci || "---"}</option>`;
          });
        }

      } catch (error) {
        console.error("Error al cargar cuentas bancarias:", error);
      }
      _html += `<option disabled>-----------------------------------------------------------------------</option>`;
      _html += `<option value="x">+ Agregar Nueva Cuenta Bancaria...</option>`;

      // Habilitando el select si hay un proveedor seleccionado
      if (id_proveedor.length > 0) {
        $select.prop('disabled', false);
      } else {
        // Si no hay proveedor, se mantiene deshabilitado.
        $select.prop('disabled', true);
      }

      // Seteando lista
      $select.html(_html);

      // Si viene de un registro
      if (_id_registro !== undefined) {
        $select.val(_id_registro);
      }
    }

    $('#valorizacion_cuentaproveedor').on('change', function() {
      var id_cuenta = $(this).val();

      if (id_cuenta == 'x') {
        // Seteando objetos
        $("#div_cuentacci").show();
        $("#hd_iscuentadetraccion").val(0);

        $("#cliente_banco_id_banco").prop('disabled', false);
        $("#cliente_banco_id_moneda").prop('disabled', true);

        // Setea Banco y Moneda
        $("#cliente_banco_id_banco").val('');
        $("#cliente_banco_id_moneda").val(2);
        $("#cliente_banco_num_cuenta").val('');
        $("#cliente_banco_cci").val('');

        f_OpenModal('modal_AddCuentaBancaria');
      }
    });

    async function f_LoadListaCuentaDetraccion(_id_registro) {
      let _html = '<option value="" selected>[Seleccione una Cuenta de Detracción]</option>';
      const $select = $("#valorizacion_cuentadetraccionproveedor");
      const id_proveedor = $("#cmb_proveedor").val();
      $select.prop('disabled', true).html(_html);

      // Parámetros de la API
      const id_moneda = 1; // Moneda: Soles (generalmente para detracciones)

      if (!id_proveedor) {
        return; // Salir si no hay proveedor seleccionado, manteniendo el select deshabilitado
      }

      try {
        const response = await fetch(url_api, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            accion: "get_ValorizacionCompra_ListaCuentasBancariasProveedor",
            id_moneda,
            id_proveedor,
            is_detraccion: 1 // Clave para obtener solo cuentas de detracción
          })
        });

        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        // Procesando la respuesta
        if (data.estado === 1) {
          data.registros.forEach((v) => {
            _html += `<option value="${v.Id}">${v.nro_cuenta}</option>`;
          });
        }

      } catch (error) {
        console.error("Error al cargar cuentas de detracción:", error);
      }

      // Agregando opción para nueva cuenta
      _html += `<option disabled>-----------------------------------------------------------------------</option>`;
      _html += `<option value="x">+ Agregar Nueva Cuenta de Detracción...</option>`;

      // Seteando lista y habilitando
      $select.html(_html).prop('disabled', false);

      // Si se pasa un ID, seleccionar el valor
      if (_id_registro !== undefined) {
        $select.val(_id_registro);
      }
    }

    $('#valorizacion_cuentadetraccionproveedor').on('change', function() {
      var id_cuenta = $(this).val();

      if (id_cuenta == 'x') {
        // Seteando objetos
        $("#div_cuentacci").hide();
        $("#hd_iscuentadetraccion").val(1);

        $("#cliente_banco_id_banco").prop('disabled', true);
        $("#cliente_banco_id_moneda").prop('disabled', true);

        // Setea Banco y Moneda
        $("#cliente_banco_id_banco").val(3);
        $("#cliente_banco_id_moneda").val(1);
        $("#cliente_banco_num_cuenta").val('');
        $("#cliente_banco_cci").val('');

        f_OpenModal('modal_AddCuentaBancaria');
      }
    });

    $('#modal_AddCuentaBancaria').on('hidden.bs.modal', function(e) {
      if ($("#hd_iscuentadetraccion").val() == 0) {
        if ($("#valorizacion_cuentaproveedor").val() != 'x') {
          return;
        }

        $("#valorizacion_cuentaproveedor").val('');
      } else {
        if ($("#valorizacion_cuentadetraccionproveedor").val() != 'x') {
          return;
        }

        $("#valorizacion_cuentadetraccionproveedor").val('');
      }
    });

    function f_GetTotalValorizacion() {
      let v = 0;
      let total_valorizacion = 0;
      let total = '';
      let lote = '';
      let gel = '';
      let valor = '';
      let valores = [];

      // Eliminando previamente la fila de totales
      $("#tr_TotalValorizacion").remove();

      // Agregando fila de totales
      let totalFilas = $("#tbody_lotes_valorizacion tr").length;

      while (v < totalFilas) {
        // Obteniendo cadena con Lotes / Códigos GEL
        // obtengo los textos de cada td
        lote = $("#lote_" + v).html().trim();
        gel = $("#gel_" + v).html().trim();

        // si gel está vacío uso lote, de lo contrario gel
        valor = (gel !== "") ? gel : lote;

        // si el valor existe y no está repetido, lo agrego al array
        if (valor !== "" && !valores.includes(valor)) {
          valores.push(valor);
        }

        // Obteniendo el Total de la Valorización
        total = $("#total_" + v).html().replace(/,/g, '');

        total_valorizacion += parseFloat(total, 2);

        v++;
      }

      valores.join(" / ");

      let _html = ` <tr id="tr_TotalValorizacion" style="font-size: 14px; background-color: #816951; color: #ffffff;">
                          <td colspan="17" style="text-align:right; vertical-align: middle; font-weight:bold;">${valores.length == 0 ? 'TOTAL: ' : valores}</td>
                          <td style="text-align:right; vertical-align: middle; font-weight:bold;">${f_RedondearDecimales(total_valorizacion, 2)}</td>
                          <td></td>
                        </tr>
                      `;

      $('#tbody_lotes_valorizacion').append(_html);

      // Actualizar tipo de pago cuando cambia el total
      f_ActualizarTipoPago();
    }

    function f_GetTotalValorizacion_Resumen() {
      let v = 0;
      let total_valorizacion = 0;
      let total = '';
      let lote = '';
      let gel = '';
      let valor = '';
      let valores = [];

      // Eliminando previamente la fila de totales
      $("#tr_TotalValorizacion_Resumen").remove();

      // Agregando fila de totales
      let totalFilas = $("#tbl_valorizacion_detalle tr").length;

      while (v < totalFilas) {
        // Obteniendo cadena con Lotes / Códigos GEL
        // obtengo los textos de cada td
        lote = $("#lote_resumen_" + v).html().trim();
        gel = $("#gel_resumen_" + v).html().trim();

        // si gel está vacío uso lote, de lo contrario gel
        valor = (gel !== "") ? gel : lote;

        // si el valor existe y no está repetido, lo agrego al array
        if (valor !== "" && !valores.includes(valor)) {
          valores.push(valor);
        }

        // Obteniendo el Total de la Valorización
        total = $("#total_resumen_" + v).html().replace(/,/g, '');

        total_valorizacion += parseFloat(total, 2);

        v++;
      }

      valores.join(" / ");

      let _html = ` <tr id="tr_TotalValorizacion_Resumen" style="font-size: 14px; background-color: #816951; color: #ffffff;">
                          <td colspan="17" style="text-align:right; vertical-align: middle; font-weight:bold;">${valores.length == 0 ? 'TOTAL: ' : valores}</td>
                          <td style="text-align:right; vertical-align: middle; font-weight:bold;">${f_RedondearDecimales(total_valorizacion, 2)}</td>
                          <td></td>
                        </tr>
                      `;

      $('#tbl_valorizacion_detalle').append(_html);
    }
  </script>

  <!-- Funciones Secundarias -->
  <script type="text/javascript">
    let panelExpandido = false;

    function getMontoTotalValorizacion() {
      let totalFilas = $("#tbody_lotes_valorizacion tr").length;
      let total = '';
      let total_valorizacion = 0;
      let v = 0;

      // console.log("TotalFilas: ", totalFilas);
      while (v < totalFilas) {
        // Obteniendo el Total de la Valorización
        const valorcito = $("#total_" + v).html();
        // console.log("Valorcito: ", valorcito);
        // console.log("V: ", v);
        if (valorcito == undefined) {
          break;
        }
        total = $("#total_" + v).html().replace(/,/g, '');

        total_valorizacion += parseFloat(total, 2);

        v++;
      }

      return f_RedondearDecimales(total_valorizacion, 2);
    }


    function f_TogglePanel() {
      if (!panelExpandido) {
        $("#div_valorizacion_lista").hide();
        $("#div_valorizacion_detalle").removeClass("col-md-6").addClass("col-md-12");
        $("#btn_toggle_panel i").removeClass("bi-arrows-angle-expand").addClass("bi-arrows-angle-contract");
        panelExpandido = true;
      } else {
        $("#div_valorizacion_detalle").removeClass("col-md-12").addClass("col-md-6");
        $("#btn_toggle_panel i").removeClass("bi-arrows-angle-contract").addClass("bi-arrows-angle-expand");

        // Espera 300ms para que la animación termine antes de mostrar el panel izquierdo
        setTimeout(function() {
          $("#div_valorizacion_lista").show();
        }, 300);

        panelExpandido = false;
      }
    }

    function f_RenumerarFilas() {
      $('#tbody_lotes_valorizacion tr').each(function(i) {
        // Actualiza el ID de cada TD
        $(this).find('td').each(function(index, td) {
          let id_actual = $(td).attr('id');
          if (id_actual) {
            let base = id_actual.split('_')[0]; // ejemplo: "lote_3" → "lote"
            $(td).attr('id', base + '_' + i);
          }
        });

        // Actualiza el botón de edición
        let btn_editar = $(this).find('button.btn-warning');
        if (btn_editar.length) {
          let onclick_str = btn_editar.attr('onclick');

          // Reemplaza la posición anterior por la nueva
          let nuevo_onclick = onclick_str.replace(/f_AdminLotes\('E',\s*\d+/, `f_AdminLotes('E', ${i}`);
          btn_editar.attr('onclick', nuevo_onclick);
        }
      });

      // Setear Total de Valorización
      f_GetTotalValorizacion();
    }

    function f_LoadingGrabarValorizacion(_is_show) {
      $("#wt_grabarvalorizacion").hide();

      $(".wt_grabarvalorizacion_button").prop('disabled', false);

      if (_is_show == 1) {
        $("#wt_grabarvalorizacion").show();

        $(".wt_grabarvalorizacion_button").prop('disabled', true);
      }
    }
  </script>

  <!-- Funciones de Grabación -->
  <script type="text/javascript">
    function f_GrabarLoteValorizacion() {
      let modo = $('#hd_modograbar_lote').val();
      let idx = $('#hd_fila_edicion').val(); // posición de fila

      let elemento = '';
      let lote = '';
      let proveedor_ruc = $("#cmb_proveedor option:selected").text().split(' - ')[0];

      // Solo si es NUEVO, tomamos de los combos
      if (modo !== 'E') {
        elemento = $('#cmb_elemento option:selected').text();
        lote = $('#cmb_lotes option:selected').text().trim();

        id_elemento = $('#cmb_elemento').val();
        // id_lote     = $('#cmb_lotes').val();
        id_lote = $('#cmb_lotes option:selected').attr('data-idcodlote');
      } else {
        // Si es EDICIÓN, usamos los label visibles
        elemento = $('#lbl_elemento_static').text().trim();
        lote = $('#lbl_lote_static').text().trim();

        id_elemento = $("#hd_id_lote").val();
        id_lote = $("#hd_id_elemento").val();
      }

      let total = parseFloat($('#txt_total').val()) || 0;

      // Validación
      if (id_lote == '') {
        alert('Debe seleccionar un Lote.');
        return;
      }

      if (id_elemento == '') {
        alert('Debe seleccionar un Elemento.');
        return;
      }

      // Solo validar duplicado si es nuevo
      if (modo !== 'E') {
        let duplicado = false;
        $('#tbody_lotes_valorizacion tr').each(function() {
          let val_elemento = $(this).attr('data-elemento');
          let val_lote = $(this).attr('data-lote');
          if (val_elemento == id_elemento && val_lote == id_lote) {
            duplicado = true;
            return false;
          }
        });
        if (duplicado) {
          alert('Este lote ya fue agregado con el mismo elemento.');
          return;
        }
      }

      // Leer campos
      let gel = $('#txt_codigo_gel').val();
      let grr = $('#txt_grr').val();
      let grt = $('#txt_grt').val();
      let ingreso = $('#txt_fecha_ingreso').val().split(' ')[0];
      let tmh = $('#txt_tmh').val();
      let h2o = $('#txt_h2o').val();
      let tms = $('#txt_tms').val();
      let ley = $('#txt_ley').val();
      let rec = $('#txt_recuperacion').val();
      let inter = $('#txt_inter').val();
      let descint = $('#txt_desc_inter').val();
      let maquila = $('#txt_maquila').val();
      let react = $('#txt_reactivo').val();
      let factor = $('#txt_factor').val();
      let ptn = $('#txt_precio_tn').val().replace(/,/g, '');
      let incent = $('#txt_incentivo').val();
      let ptnf = $('#txt_precio_tn_final').val().replace(/,/g, '');
      let total_ = $('#txt_total').val().replace(/,/g, '');

      // Seteando Maquila y React.
      maquila = ((maquila.trim().length == 0) ? 0.00 : maquila);
      react = ((react.trim().length == 0) ? 0.00 : react);

      if (modo === 'E' && idx !== '') {
        // Solo reemplazar valores por ID
        $(`#elemento_${idx}`).html(elemento);
        $(`#lote_${idx}`).html(lote);
        $(`#gel_${idx}`).html(gel);
        $(`#grr_${idx}`).html(grr);
        $(`#grt_${idx}`).html(grt);
        $(`#ingreso_${idx}`).html(ingreso);
        $(`#tmh_${idx}`).html(f_RedondearDecimales(tmh, 3));
        $(`#h2o_${idx}`).html(h2o ? f_RedondearDecimales(h2o, 2) : '');
        $(`#tms_${idx}`).html(f_RedondearDecimales(tms, 3));
        $(`#ley_${idx}`).html(f_RedondearDecimales(ley, 3));
        $(`#rec_${idx}`).html(f_RedondearDecimales(rec, 0));
        $(`#inter_${idx}`).html(f_RedondearDecimales(inter, 2));
        $(`#descint_${idx}`).html(f_RedondearDecimales(descint, 2));
        $(`#maquila_${idx}`).html(f_RedondearDecimales(maquila, 2));
        $(`#react_${idx}`).html(f_RedondearDecimales(react, 2));
        $(`#factor_${idx}`).html(f_RedondearDecimales(factor, 4));
        $(`#ptn_${idx}`).html(f_RedondearDecimales(ptn, 2));
        $(`#incent_${idx}`).html(incent ? f_RedondearDecimales(incent, 2) : '');
        $(`#ptnf_${idx}`).html(f_RedondearDecimales(ptnf, 2));
        $(`#total_${idx}`).html(`${f_RedondearDecimales(total_, 2)}`);

        $(`#btn_edit_${idx}`).attr("onclick", `f_AdminLotes('E', ${idx}, '${id_lote}', '${id_elemento}', '${lote}', '${elemento}', '${inter}', '${descint}', '${maquila}', '${react}', '${rec}', '${incent}', '${proveedor_ruc}', ${ley})`);

      } else {
        // Eliminando fila de totales
        $("#tr_TotalValorizacion").remove();

        // Nuevo registro: obtener índice
        let idx_new = $('#tbody_lotes_valorizacion tr').length

        let fila = `
              <tr data-elemento="${id_elemento}" data-lote="${id_lote}" style="font-size: 14px;">
                <td id="elemento_${idx_new}" style="text-align:center; vertical-align: middle;">${elemento}</td>
                <td id="lote_${idx_new}" style="text-align:center; vertical-align: middle; font-weight:bold;">${lote}</td>
                <td id="gel_${idx_new}" style="text-align:center; vertical-align: middle;">${gel}</td>
                <td id="grr_${idx_new}" style="text-align:center; vertical-align: middle;">${grr}</td>
                <td id="grt_${idx_new}" style="text-align:center; vertical-align: middle;">${grt}</td>
                <td id="ingreso_${idx_new}" style="text-align:center; vertical-align: middle;">${ingreso}</td>
                <td id="tmh_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(tmh, 3)}</td>
                <td id="h2o_${idx_new}" style="text-align:right; vertical-align: middle;">${h2o ? f_RedondearDecimales(h2o, 2) : ''}</td>
                <td id="tms_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(tms, 3)}</td>
                <td id="ley_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(ley, 3)}</td>
                <td id="rec_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(rec, 0)}</td>
                <td id="inter_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(inter, 2)}</td>
                <td id="descint_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(descint, 2)}</td>
                <td id="maquila_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(maquila, 2)}</td>
                <td id="react_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(react, 2)}</td>
                <td id="factor_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(factor, 4)}</td>
                <td id="ptn_${idx_new}" style="text-align:right; vertical-align: middle;">${f_RedondearDecimales(ptn, 2)}</td>
                <td id="incent_${idx_new}" style="text-align:right; vertical-align: middle; display: none;">${incent ? f_RedondearDecimales(incent, 2) : ''}</td>
                <td id="ptnf_${idx_new}" style="text-align:right; vertical-align: middle; display: none;">${f_RedondearDecimales(ptnf, 2)}</td>
                <td id="total_${idx_new}" style="text-align:right; vertical-align: middle; font-weight:bold;">${f_RedondearDecimales(total_, 2)}</td>
                <td class="text-center">
                  <button id="btn_edit_${idx_new}" class="btn btn-sm btn-warning me-1" type="button"
                    onclick="f_AdminLotes('E', ${idx_new}, '${id_lote}', '${id_elemento}', '${lote}', '${elemento}', '${inter}', '${descint}', '${maquila}', '${react}', '${rec}', '${incent}', '${proveedor_ruc}', ${ley})">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button class="btn btn-sm btn-danger" onclick="$(this).closest('tr').remove(); f_RenumerarFilas();"><i class="bi bi-trash3-fill"></i></button>
                </td>
              </tr>
            `;

        $('#tbody_lotes_valorizacion').append(fila);
      }

      // Setear Total de Valorización
      f_GetTotalValorizacion();

      // Cerrar modal
      $('#modal_nuevolote').modal('hide');

      // Deshabilitar combos generales
      $('#cmb_proveedor').prop('disabled', true);
      $('#cmb_concesion').prop('disabled', true);
    }

    function f_EditarLoteValorizacion(btn) {
      if (!confirm("¿Está seguro de editar este lote?\n\nADVERTENCIA: Esto reiniciará la selección de anticipos.")) {
        return;
      }
      // Obtener la fila
      let tr = $(btn).closest('tr');
      let index_fila = tr.index();
      let proveedor_ruc = $("#cmb_proveedor option:selected").text().split(' - ')[0];

      // Extraer valores desde las celdas
      let elemento = tr.find('td').eq(0).text().trim();
      let lote = tr.find('td').eq(1).text().trim();
      let gel = tr.find('td').eq(2).text().trim();
      let grr = tr.find('td').eq(3).text().trim();
      let grt = tr.find('td').eq(4).text().trim();
      let fecha_ingreso = tr.find('td').eq(5).text().trim();
      let tmh = tr.find('td').eq(6).text().trim();
      let h2o = tr.find('td').eq(7).text().trim();
      let tms = tr.find('td').eq(8).text().trim();
      let ley = tr.find('td').eq(9).text().trim();
      let rec = tr.find('td').eq(10).text().trim();
      let inter = tr.find('td').eq(11).text().replace(/,/g, '');
      let desc_inter = tr.find('td').eq(12).text().replace(/,/g, '');
      let maquila = tr.find('td').eq(13).text().replace(/,/g, '');
      let reactivo = tr.find('td').eq(14).text().replace(/,/g, '');
      let factor = tr.find('td').eq(15).text().replace(/,/g, '');
      let precio_tn = tr.find('td').eq(16).text().trim();
      let incentivo = tr.find('td').eq(17).text().replace(/,/g, '');
      let precio_tn_final = tr.find('td').eq(18).text().trim();
      let total = tr.find('td').eq(19).text().trim();

      // Obtener valores ocultos de los atributos
      let id_lote = tr.attr('data-lote');
      let id_elemento = tr.attr('data-elemento');

      let lote_txt = tr.find('td').eq(1).text().trim();
      let elemento_txt = tr.find('td').eq(0).text().trim();

      // Llamar a f_AdminLotes pasando los valores necesarios
      f_AdminLotes('E', index_fila, id_lote, id_elemento, lote_txt, elemento_txt, inter, desc_inter, maquila, reactivo, rec, incentivo, proveedor_ruc, ley);
    }

    function getLotesSeleccionados() {
      let detalle = [];

      $('#tbody_lotes_valorizacion tr').each(function(idx, tr) {
        if ($(tr).attr("id") === "tr_TotalValorizacion") {
          return;
        }

        let fila = $(tr);
        let id_elemento = fila.attr('data-elemento');
        let id_lote = fila.attr('data-lote');

        detalle.push({
          id_elemento,
          cod_lote: $(`#lote_${idx}`).text().trim(),
          cod_gel: $(`#gel_${idx}`).text().trim(),
          grr: $(`#grr_${idx}`).text().trim(),
          grt: $(`#grt_${idx}`).text().trim(),
          fecha_ingreso: $(`#ingreso_${idx}`).text().trim(),
          tmh: $(`#tmh_${idx}`).text().trim().replace(/,/g, ''),
          h2o: $(`#h2o_${idx}`).text().trim().replace(/,/g, ''),
          tms: $(`#tms_${idx}`).text().trim().replace(/,/g, ''),
          ley: $(`#ley_${idx}`).text().trim().replace(/,/g, ''),
          rec: $(`#rec_${idx}`).text().trim().replace(/,/g, ''),
          inter: $(`#inter_${idx}`).text().trim().replace(/,/g, ''),
          descint: $(`#descint_${idx}`).text().trim().replace(/,/g, ''),
          maquila: $(`#maquila_${idx}`).text().trim().replace(/,/g, ''),
          react: $(`#react_${idx}`).text().trim().replace(/,/g, ''),
          factor: $(`#factor_${idx}`).text().trim().replace(/,/g, ''),
          ptn: $(`#ptn_${idx}`).text().trim().replace(/,/g, ''),
          incent: $(`#incent_${idx}`).text().trim().replace(/,/g, ''),
          ptnf: $(`#ptnf_${idx}`).text().trim().replace(/,/g, ''),
          total: $(`#total_${idx}`).text().replace(/[^0-9.-]/g, '').replace(/,/g, '')
        });
      });

      return detalle;
    }

    function f_GrabarValorizacion() {
      let id_valorizacion = $("#hd_idvalorizacion").val();
      let modo_grabar = $('#hd_modograbar_valorizacion').val();
      let id_proveedor = $('#cmb_proveedor').val();
      let id_concesion = $('#cmb_concesion').val();
      let txt_concesion = $('#cmb_concesion option:selected').text();
      let txt_codigo_unico = $('#txt_codigo_unico').val();
      let txt_procedencia = $('#txt_procedencia').val();
      let id_cuentabancaria = $('#valorizacion_cuentaproveedor').val();
      let id_cuentadetraccion = $('#valorizacion_cuentadetraccionproveedor').val();
      let info_cuentabancaria = $('#valorizacion_cuentaproveedor option:selected').text();
      let info_cuentadetraccion = $('#valorizacion_cuentadetraccionproveedor option:selected').text();
      let monto_total_valorizacion = getMontoTotalValorizacionNumerico();

      // ===== DETECCIÓN AUTOMÁTICA DEL TIPO DE PAGO =====
      // Basado en datos reales, no en radio buttons
      const tiene_anticipos = anticiposSeleccionados && anticiposSeleccionados.length > 0;
      const tiene_cuenta_bancaria = id_cuentabancaria && id_cuentabancaria.length > 0;

      let total_anticipos = 0;
      if (tiene_anticipos) {
        total_anticipos = anticiposSeleccionados.reduce((sum, a) => sum + parseFloat(a.monto_a_usar || 0), 0);
      }

      // Determinar tipo de pago automáticamente
      let tipo_pago_detectado = '';
      let usa_anticipo = false;
      let es_pago_mixto = false;
      let monto_transferencia = 0;

      if (tiene_anticipos && total_anticipos >= monto_total_valorizacion) {
        // Solo anticipos (cubren el total o más)
        tipo_pago_detectado = 'anticipo';
        usa_anticipo = true;
        es_pago_mixto = false;
        monto_transferencia = 0;
      } else if (tiene_anticipos && total_anticipos > 0 && tiene_cuenta_bancaria) {
        // Pago mixto (anticipos parciales + transferencia)
        tipo_pago_detectado = 'mixto';
        usa_anticipo = true;
        es_pago_mixto = true;
        monto_transferencia = monto_total_valorizacion - total_anticipos;
      } else if (tiene_cuenta_bancaria) {
        // Solo transferencia bancaria
        tipo_pago_detectado = 'transferencia';
        usa_anticipo = false;
        es_pago_mixto = false;
        monto_transferencia = monto_total_valorizacion;
      } else {
        // Sin método de pago válido
        tipo_pago_detectado = 'ninguno';
      }

      // Validaciones según tipo de pago detectado
      if (tipo_pago_detectado === 'ninguno') {
        alert('Debe seleccionar al menos un método de pago: anticipos y/o cuenta bancaria.');
        return;
      }

      if (tipo_pago_detectado === 'anticipo') {
        // Solo anticipos - deben cubrir el total
        if (Math.abs(total_anticipos - monto_total_valorizacion) > 0.01) {
          const diferencia = monto_total_valorizacion - total_anticipos;
          alert(`Los anticipos seleccionados (${f_RedondearDecimales(total_anticipos, 2)}) no cubren el monto total (${f_RedondearDecimales(monto_total_valorizacion, 2)}).\n\nFalta: ${f_RedondearDecimales(diferencia, 2)}\n\nDebe agregar más anticipos o seleccionar una cuenta bancaria para pago mixto.`);
          return;
        }
      }

      if (tipo_pago_detectado === 'transferencia' || tipo_pago_detectado === 'mixto') {
        // Validar cuentas bancarias
        if (!id_cuentabancaria || id_cuentabancaria.length == 0) {
          alert('Debe seleccionar la Cuenta Bancaria del Proveedor.');
          return;
        }

        if (!id_cuentadetraccion || id_cuentadetraccion.length == 0) {
          alert('Debe seleccionar la Cuenta de Detracción del Proveedor.');
          return;
        }
      }

      if (tipo_pago_detectado === 'mixto') {
        // Validar que la suma sea correcta
        const suma_total = total_anticipos + monto_transferencia;
        if (Math.abs(suma_total - monto_total_valorizacion) > 0.01) {
          alert(`Error en cálculo de pago mixto:\n\nAnticipos: ${f_RedondearDecimales(total_anticipos, 2)}\nTransferencia: ${f_RedondearDecimales(monto_transferencia, 2)}\nTotal: ${f_RedondearDecimales(suma_total, 2)}\n\nDebe ser: ${f_RedondearDecimales(monto_total_valorizacion, 2)}`);
          return;
        }
      }

      // Validaciones comunes
      if (id_proveedor == null || id_proveedor.length == 0) {
        alert('Debe seleccionar el Proveedor.');
        return;
      }

      if (id_concesion == null || id_concesion.length == 0) {
        alert('Debe seleccionar la Concesión.');
        return;
      }

      if ($('#tbody_lotes_valorizacion tr').length - 1 == 0) {
        alert('Debe agregar al menos un lote.');
        return;
      }

      if (monto_total_valorizacion <= 0) {
        alert('El monto total de la valorización debe ser mayor a cero.');
        return;
      }

      // Obteniendo detalle
      f_LoadingGrabarValorizacion(1);

      let detalle = [];

      $('#tbody_lotes_valorizacion tr').each(function(idx, tr) {
        if ($(tr).attr("id") === "tr_TotalValorizacion") {
          return;
        }

        let fila = $(tr);
        let id_elemento = fila.attr('data-elemento');
        let id_lote = fila.attr('data-lote');

        detalle.push({
          id_elemento,
          cod_lote: $(`#lote_${idx}`).text().trim(),
          cod_gel: $(`#gel_${idx}`).text().trim(),
          grr: $(`#grr_${idx}`).text().trim(),
          grt: $(`#grt_${idx}`).text().trim(),
          fecha_ingreso: $(`#ingreso_${idx}`).text().trim(),
          tmh: $(`#tmh_${idx}`).text().trim().replace(/,/g, ''),
          h2o: $(`#h2o_${idx}`).text().trim().replace(/,/g, ''),
          tms: $(`#tms_${idx}`).text().trim().replace(/,/g, ''),
          ley: $(`#ley_${idx}`).text().trim().replace(/,/g, ''),
          rec: $(`#rec_${idx}`).text().trim().replace(/,/g, ''),
          inter: $(`#inter_${idx}`).text().trim().replace(/,/g, ''),
          descint: $(`#descint_${idx}`).text().trim().replace(/,/g, ''),
          maquila: $(`#maquila_${idx}`).text().trim().replace(/,/g, ''),
          react: $(`#react_${idx}`).text().trim().replace(/,/g, ''),
          factor: $(`#factor_${idx}`).text().trim().replace(/,/g, ''),
          ptn: $(`#ptn_${idx}`).text().trim().replace(/,/g, ''),
          incent: $(`#incent_${idx}`).text().trim().replace(/,/g, ''),
          ptnf: $(`#ptnf_${idx}`).text().trim().replace(/,/g, ''),
          total: $(`#total_${idx}`).text().replace(/[^0-9.-]/g, '').replace(/,/g, '')
        });
      });

      // Preparar datos para enviar al backend
      const datosEnvio = {
        accion: 'grabar_ValorizacionCompra',
        id_valorizacion,
        id_proveedor,
        id_concesion,
        concesion: txt_concesion,
        codigo_unico: txt_codigo_unico,
        procedencia: txt_procedencia,
        id_cuentabancaria: id_cuentabancaria,
        info_cuentabancaria: info_cuentabancaria,
        id_cuentadetraccion: id_cuentadetraccion,
        info_cuentadetraccion: info_cuentadetraccion,
        modo_grabar: modo_grabar,
        arr_detalle: JSON.stringify(detalle),
        usa_anticipo: usa_anticipo,
        es_pago_mixto: es_pago_mixto,
        monto_total_valorizacion: monto_total_valorizacion,
        monto_transferencia: monto_transferencia,
        // Siempre enviar anticipos (aunque sea array vacío)
        anticipos_seleccionados: JSON.stringify(anticiposSeleccionados || [])
      };

      // ===== LOGGING PARA DEBUG =====
      // console.log('╔═══════════════════════════════════════════════════════════════');
      // console.log('║ DATOS DE ENVÍO - grabar_ValorizacionCompra');
      // console.log('╠═══════════════════════════════════════════════════════════════');
      // console.log('║ Tipo de Pago DETECTADO:', tipo_pago_detectado);
      // console.log('║ usa_anticipo:', usa_anticipo);
      // console.log('║ es_pago_mixto:', es_pago_mixto);
      // console.log('║ Monto Total Valorización:', monto_total_valorizacion);
      // console.log('║ Monto Transferencia:', monto_transferencia);
      // console.log('╠═══════════════════════════════════════════════════════════════');
      // console.log('║ ANTICIPOS SELECCIONADOS:');
      // console.log('║ Cantidad:', anticiposSeleccionados ? anticiposSeleccionados.length : 0);
      if (anticiposSeleccionados && anticiposSeleccionados.length > 0) {
        anticiposSeleccionados.forEach((ant, idx) => {
          console.log(`║ [${idx + 1}] ID: ${ant.id_anticipo}, Factura: ${ant.factura}, Monto a usar: ${ant.monto_a_usar}`);
        });
        const totalAnticipos = anticiposSeleccionados.reduce((sum, a) => sum + parseFloat(a.monto_a_usar || 0), 0);
        console.log('║ Total de Anticipos:', totalAnticipos);
      } else {
        console.log('║ Ningún anticipo seleccionado');
      }
      // console.log('║ Cuenta Bancaria ID:', id_cuentabancaria);
      // console.log('║ Cuenta Detracción ID:', id_cuentadetraccion);
      // console.log('║ Datos completos enviados:', datosEnvio);
      // ===== FIN LOGGING =====

      $.post(url_api, datosEnvio, function(data) {
        if (data.estado == 1) {
          // Limpiar selección de anticipos
          anticiposSeleccionados = [];
          $('#tipo_pago_transferencia').prop('checked', true).trigger('change');

          // Limpiar campos
          $('#txt_anticipos_seleccionados').html('Ningún anticipo seleccionado');
          $('#txt_monto_transferencia').val('');
          $('#div_saldo_restante').hide();

          // Cerrar modal si es nueva valorización
          if (modo_grabar == 'N') {
            f_LoadValorizaciones();
            f_cerrarModal('modal_valorizacion');
          } else {
            // Para edición, recargar la valorización seleccionada
            const rows = [...document.querySelectorAll('#tbl_valorizaciones tr')];
            const yellow = rows.find(tr => {
              const c = getComputedStyle(tr).backgroundColor;
              return c === 'rgb(255, 245, 135)' || c === 'rgb(255, 244, 229)';
            });
            if (yellow) f_SelectValorizacion(yellow);
            else if (rows[0]) f_SelectValorizacion(rows[0]);

            f_cerrarModal('modal_valorizacion');
          }
        } else {
          alert('Error al grabar: ' + (data.msg || 'Error desconocido'));
        }

        f_LoadingGrabarValorizacion(0);

      }, 'json');
    }

    function f_EliminarRegistro(_id_registro) {
      if (confirm("¿Está seguro de Eliminar el registro seleccionado?")) {
        $.post(url_api, {
            accion: "eliminar_ValorizacionDetalle",
            id_registro: _id_registro
          },
          function(data) {
            if (data.estado == 1) {
              f_LoadDetalleValorizacion(id_valorizacion_Selected);
            } else {
              alert("Ocurrió un error al momento de eliminar el registro de Valorización.");
            }

          }, "json");
      }
    }

    function f_CambiarEstadoValorizacion(_id, _modo) {
      let _accion = (_modo == 'I') ? 'Inactivar' : 'Activar';

      if (confirm("¿Está seguro de " + _accion + " la Valorización seleccionada?")) {
        $.post(url_api, {
          accion: "eliminar_Valorizacion",
          modo: _modo,
          id_registro: _id
        }, function(data) {
          if (data.estado == 1) {
            f_LoadValorizaciones();
          } else {
            alert("Ocurrió un error al momento de cambiar el estado.");
          }
        }, "json");
      }
    }

    function f_EliminarValorizacion(_id) {
      if (confirm("¿Está seguro de Eliminar la Valorización seleccionada?\n\nEsta acción no se puede deshacer.")) {
        $.post(url_api, {
          accion: "eliminar_Valorizacion",
          modo: "X",
          id_registro: _id
        }, function(data) {
          console.log("Data: ", data);
          if (data.estado == 1) {
            f_LoadValorizaciones();
          } else {
            if (data.msg) {
              alert(data.msg);
              return;
            }
            alert("Ocurrió un error al momento de eliminar el Estándar.");
          }
        }, "json");
      }
    }

    function f_GrabarCuentaBancaria() {
      var _id_cliente = $("#cmb_proveedor").val();
      var _id_banco = $("#cliente_banco_id_banco").val();
      var _id_moneda = $("#cliente_banco_id_moneda").val();
      var _nro_cuenta = $("#cliente_banco_num_cuenta").val();
      var _cci = $("#cliente_banco_cci").val();
      var _is_detraccion = $("#hd_iscuentadetraccion").val();

      // Validaciones básicas
      if (_id_banco == "") {
        alert("Debe seleccionar el banco.");
        return;
      }

      if (_id_moneda == "") {
        alert("Debe seleccionar la moneda.");
        return;
      }

      if (_nro_cuenta.trim() == "") {
        alert("Debe ingresar el número de cuenta.");
        return;
      }

      // Envío al backend
      $.post(url_api, {
          accion: "grabar_ClienteBanco",
          modo_grabar: 'N',
          id_cliente: _id_cliente,
          id_banco: _id_banco,
          id_moneda: _id_moneda,
          nro_cuenta: _nro_cuenta,
          cci: _cci,
          is_detraccion: _is_detraccion
        },
        function(data) {
          if (data.estado == 1) {
            if ($("#hd_iscuentadetraccion").val() == 0) {
              f_LoadListaCuentasBancarias(data.id_registro);
            } else {
              f_LoadListaCuentaDetraccion(data.id_registro);
            }

            f_cerrarModal('modal_AddCuentaBancaria');
          } else {
            if (data.estado == 2) {
              alert("La cuenta ingresada para este cliente ya fue ingresada anteriormente.\nPor favor verificar.");

              return;
            } else {
              alert("Ocurrió un error al grabar la cuenta bancaria.");
            }
          }
        }, "json");
    }

    function f_NuevaVersion(_id_registro, _num_valorizacion) {
      // Validando datos
      if (!confirm("¿Está seguro de Copiar la valorización N° " + _num_valorizacion + "?")) {
        return;
      }

      // Creando copia
      $.post(url_api, {
          accion: "grabar_ValorizacionCompra_NuevaVersion",
          id_registro: _id_registro,
          num_valorizacion: _num_valorizacion
        },
        function(data) {
          if (data.estado == 1) {
            f_LoadValorizaciones();
          } else {
            alert("Ocurrió un error al generar la copia.");
          }
        }, "json");
    }

    function f_AprobarValorizacion(_id_registro, _num_valorizacion, _version) {
      // Validando datos
      if (!confirm("¿Está seguro de Aprobar la valorización N° " + _num_valorizacion + " - Versión " + _version + " ?")) {
        return;
      }

      // Aprobando Valorización
      $.post(url_api, {
          accion: "grabar_ValorizacionCompra_Aprobacion",
          id_registro: _id_registro,
          num_valorizacion: _num_valorizacion,
          version: _version,
          is_aprobado: 1
        },
        function(data) {
          if (data.estado == 1) {
            f_LoadValorizaciones();
          } else {
            if (data.msg) {
              alert(data.msg);
              return;
            }
            alert("Ocurrió un error al generar la copia.");
          }
        }, "json");
    }
  </script>
</body>

</html>