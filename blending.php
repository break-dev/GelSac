<?php
// Inicia la sesión
session_start();

// Inclusión de archivos de configuración y utilidades
include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');

// Redirección si el usuario no está autenticado
if (!isset($_SESSION["Id"])) {
  header('Location: index.php');
  exit;
}

// endpoint de backend
$backendUrl = 'apis/backend.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo $favicon; ?>" type="image/png" />

  <title><?php echo $nom_app; ?> | Blending</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <link rel="stylesheet" href="<?php echo $url_lims; ?>/global/styles.css">

  <style>
    /* Estilos de cabecera para las tablas */
    .header-bg-blending {
      background-color: #2c3e50 !important;
      /* Dark Blue */
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-detalle {
      background-color: #8e44ad !important;
      /* Purple */
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    /* Estilo para las filas seleccionables de blending */
    .blending-row:hover {
      background-color: #e0f7fa;
      cursor: pointer;
    }

    .blending-row.selected {
      background-color: #b3e5fc;
      font-weight: bold;
    }

    /* Ajuste para que el modal sea más ancho */
    @media (min-width: 1200px) {
      .modal-xl-custom {
        max-width: 65% !important;
      }
    }
  </style>
</head>

<body class="bg-light" style="zoom: 80%;">
  <div class="container-fluid">
    <div class="row">
      <?php echo $navbar_maintop; ?>

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
    </div>
  </div>

  <!-- Hidden element to prevent JS Error in auxiliaries_js.php -->
  <select id="voiceList" hidden></select>

  <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 10px; padding-left: 15px; padding-right: 15px;">
    <div class="d-flex row">

      <div class="row bg-white shadow-sm p-3 mb-3 rounded">
        <h5><i class="bi bi-layers-half"></i> Registro y Control de Blending</h5>
        <hr style="border-color: #D9D9D9; margin-top: 2px;" />
        <div class="d-flex mb-2" style="font-size: 16px;">
          <span class="me-4">
            Total Blendings: <strong id="total_blendings" class="text-primary">0</strong>
          </span>
          <button class="btn btn-success btn-sm ms-auto" id="btn_open_new_blending_modal">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Blending
          </button>
        </div>
      </div>

      <!-- Columna Izquierda: Lista de Blendings -->
      <div class="col-md-5">
        <div class="bg-white shadow-sm p-3 rounded">
          <h5 class="d-inline-block"><i class="bi bi-list-columns-reverse"></i> Historial de Blendings</h5>
          <hr style="border-color: #D9D9D9; margin-top: 5px; margin-bottom: 10px;" />

          <!-- Filters Section -->
          <div class="row g-2 mb-3 bg-light p-2 rounded border">
            <div class="col-6">
              <label class="form-label small mb-0 fw-bold">Correlativo</label>
              <select id="filter_correlativo" class="form-select form-select-sm w-100"></select>
            </div>
            <div class="col-6">
              <label class="form-label small mb-0 fw-bold">Estado</label>
              <select id="filter_estado" class="form-select form-select-sm">
                <option value="">Todos</option>
                <option value="Con peso">Con peso</option>
                <option value="Agotado">Agotado</option>
              </select>
            </div>
            <div class="col-3">
              <label class="form-label small mb-0 fw-bold">Desde</label>
              <input type="text" id="filter_fecha_desde" class="form-control form-control-sm bg-white"
                placeholder="dd/mm/yyyy" readonly>
            </div>
            <div class="col-3">
              <label class="form-label small mb-0 fw-bold">Hasta</label>
              <input type="text" id="filter_fecha_hasta" class="form-control form-control-sm bg-white"
                placeholder="dd/mm/yyyy" readonly>
            </div>
            <div class="col-6 text-end mt-4">
              <button class="btn btn-primary btn-sm me-1" type="button" id="btn_aplicar_filtros">
                <i class="bi bi-funnel-fill"></i> Aplicar Filtros
              </button>
              <button class="btn btn-outline-secondary btn-sm" type="button" id="btn_limpiar_filtros"
                title="Limpiar Filtros"><i class="bi bi-x-lg"></i> Limpiar filtros</button>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-bordered table-hover table-striped">
              <thead>
                <tr style="font-size: 13px;">
                  <th class="header-bg-blending text-center">Código</th>
                  <th class="header-bg-blending text-center">Peso Inicial</th>
                  <th class="header-bg-blending text-center">Peso Actual</th>
                  <th class="header-bg-blending text-center">Estado</th>
                  <th class="header-bg-blending text-center">Fecha</th>
                  <th class="header-bg-blending text-center"># Lotes</th>
                  <th class="header-bg-blending text-center">Acción</th>
                </tr>
              </thead>
              <tbody id="tbl_blendings" style="font-size: 13px;">
                <tr>
                  <td colspan="7" class="text-center">Cargando...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Columna Derecha: Detalle del Blending -->
      <div class="col-md-7">
        <div class="bg-white shadow-sm p-3 rounded">
          <h5 class="d-inline-block"><i class="bi bi-eye"></i> Detalle de Blending: <span
              id="blending_seleccionado_codigo" class="text-primary">---</span></h5>
          <hr style="border-color: #D9D9D9; margin-top: 5px; margin-bottom: 10px;" />

          <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-bordered table-hover table-striped">
              <thead>
                <tr style="font-size: 13px;">
                  <th class="header-bg-detalle text-center">Código Gel</th>
                  <th class="header-bg-detalle text-center">TMH (Peso Húmedo)</th>
                  <th class="header-bg-detalle text-center">H2O</th>
                  <th class="header-bg-detalle text-center">TMS (Peso Seco)</th>
                </tr>
              </thead>
              <tbody id="tbl_detalle_blending" style="font-size: 13px;">
                <tr>
                  <td colspan="4" class="text-center" id="msg_detalle_blending">Seleccione un blending en el panel
                    izquierdo.</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-secondary fw-bold" style="font-size: 13px;">
                  <td class="text-end">TOTALES:</td>
                  <td class="text-end text-primary" id="lbl_total_tmh_blending">0.00</td>
                  <td class="text-end text-primary" id="lbl_avg_h2o_blending">0.000</td>
                  <td class="text-end text-primary" id="lbl_recalc_tms_blending">0.00</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
  </div>
  </div>

  <!-- Modal Nuevo Blending -->
  <div class="modal fade" id="modal_nuevo_blending" tabindex="-1" aria-labelledby="modal_nuevo_blending_Label"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-xl-custom modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modal_nuevo_blending_Label"><i class="bi bi-box-seam"></i> Crear Nuevo Blending
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-8">
              <label for="reg_proveedor" class="form-label">Filtrar por Proveedor (Opcional)</label>
              <select id="reg_proveedor" class="form-select" data-bs-theme="bootstrap-5"></select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button class="btn btn-primary w-100" id="btn_cargar_lotes" disabled>
                <i class="bi bi-search"></i> Buscar Lotes
              </button>
            </div>
          </div>

          <hr>

          <h6 class="mb-2">Lotes Disponibles:</h6>
          <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-sm table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="text-center">Proveedor</th>
                  <th class="text-center">Código Gel</th>
                  <th class="text-center">TMH (Peso Húmedo)</th>
                  <th class="text-center">H2O (Humedad)</th>
                  <th class="text-center">TMS (Peso Seco)</th>
                  <th class="text-center" width="50"></th>
                </tr>
              </thead>
              <tbody id="tbl_lotes_disponibles">
                <tr>
                  <td colspan="6" class="text-center text-muted">Seleccione Buscar para ver lotes.</td>
                </tr>
              </tbody>
            </table>
          </div>

          <hr>

          <h6 class="mb-2">Lotes Seleccionados:</h6>
          <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-sm table-bordered">
              <thead class="table-success">
                <tr>
                  <th class="text-center">Proveedor</th>
                  <th class="text-center">Código Gel</th>
                  <th class="text-center">TMH (Disponible)</th>
                  <th class="text-center">H2O (Humedad)</th>
                  <th class="text-center">TMS (Disponible)</th>
                  <th class="text-center" width="120">Peso a Tomar</th>
                  <th class="text-center" width="50"></th>
                </tr>
              </thead>
              <tbody id="tbl_lotes_seleccionados">
                <tr>
                  <td colspan="7" class="text-center text-muted">No hay lotes seleccionados.</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Total Summary Section -->
          <div class="mt-4">
            <h6 class="text-secondary fw-bold text-uppercase small border-bottom pb-1 mb-3">Valores Estimados</h6>
            <div class="d-flex justify-content-center align-items-center gap-4 text-center">
              <!-- TMH -->
              <div>
                <div class="text-primary fw-bold" style="font-size: 1.5rem;" id="card_total_tmh">0.00</div>
                <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                  Peso Humedo</div>
              </div>
              <!-- Divider -->
              <div class="border-end border-2" style="height: 40px; opacity: 0.2;"></div>
              <!-- H2O -->
              <div>
                <div class="text-info fw-bold" style="font-size: 1.5rem;" id="card_avg_h2o">0.000</div>
                <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                  Humedad %</div>
              </div>
              <!-- Divider -->
              <div class="border-end border-2" style="height: 40px; opacity: 0.2;"></div>
              <!-- TMS -->
              <div>
                <div class="text-success fw-bold" style="font-size: 1.5rem;" id="card_recalc_tms">0.00</div>
                <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                  Peso Seco</div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>
            Cancelar</button>
          <button type="button" class="btn btn-success" id="btn_crear_blending" disabled><i class="bi bi-save"></i>
            Crear Blending</button>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- Moved auxiliaries include to after scripts -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <?php include('global/auxiliares_js.php'); ?>


  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {

      // -------------------------
      // Variables Globales
      // -------------------------
      const backendUrl = '<?php echo $backendUrl; ?>';
      const nuevoBlendingModal = new bootstrap.Modal(document.getElementById("modal_nuevo_blending"));

      let allBlendings = [];
      let blendingSeleccionado = null;
      let lotesDisponibles = [];
      let lotesSeleccionados = []; // Array to persist selected lots across provider changes

      // Filter Data
      let filterData = {
        proveedores: [],
        correlativos: []
      };

      // -------------------------
      // Funciones Auxiliares
      // -------------------------
      function f_callBackend(accion, data) {
        return $.post(backendUrl, {
          accion: accion,
          ...data
        }, 'json');
      }

      function formatNumber(num, decimals = 2) {
        if (num === null || num === undefined || isNaN(num)) return '0.00';
        return new Intl.NumberFormat('en-US', {
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals
        }).format(num);
      }

      function dmyToYmd(dateStr) {
        if (!dateStr) return "";
        let parts = dateStr.split('/');
        if (parts.length === 3) {
          return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        return dateStr;
      }

      // Initialize Flatpickr
      const flatpickrConfig = {
        locale: "es",
        dateFormat: "d/m/Y",
        allowInput: true,
      };
      flatpickr("#filter_fecha_desde", flatpickrConfig);
      flatpickr("#filter_fecha_hasta", flatpickrConfig);

      // -------------------------
      // Lógica de Renderizado
      // -------------------------

      function renderBlendings(dataList = allBlendings) {
        let html = '';
        if (dataList.length === 0) {
          html = '<tr><td colspan="7" class="text-center">No se encontraron blendings registrados.</td></tr>';
        } else {
          // Sorting by Date/ID Descending
          dataList.sort((a, b) => {
            return b.id_blending - a.id_blending;
          });

          // let lastProviderId = null;

          dataList.forEach(b => {
            let estadoClass = b.estado === 'Activo' ? 'text-success' : 'text-muted';

            // Removed Provider Grouping Row
            /*
            if (b.id_proveedor !== lastProviderId) {
               html += ...
               lastProviderId = b.id_proveedor;
            }
            */

            html += `
                 <tr class="blending-row" data-id="${b.id_blending}" onclick="selectBlending(${b.id_blending})">
                    <td class="fw-bold text-center ps-4"><i class="bi bi-caret-right-fill text-muted" style="font-size: 0.8em;"></i> ${b.correlativo}</td>
                    <td class="text-end">${formatNumber(b.peso_inicial)}</td>
                    <td class="text-end">${formatNumber(b.peso_actual)}</td>
                    <td class="text-center ${estadoClass}">${b.estado}</td>
                    <td class="text-center">${f_FormatFecha(b.fecha_registro, 1)}</td>
                    <td class="text-center">${b.cantidad_lotes}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary" onclick="selectBlending(${b.id_blending}, event)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="anularBlending(${b.id_blending}, event)" title="Anular Blending">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                 </tr>
                 `;
          });
        }
        $("#tbl_blendings").html(html);
        $("#total_blendings").text(dataList.length);
      }

      function renderDetalle(detalle) {
        let html = '';
        let totalTMH = 0;
        let totalTMS = 0;
        let sumH2O = 0;
        let count = detalle.length;

        if (detalle.length === 0) {
          html = '<tr><td colspan="5" class="text-center">No hay detalles para mostrar.</td></tr>';
          $("#lbl_total_tmh_blending").text('0.00');
          $("#lbl_avg_h2o_blending").text('0.000');
          $("#lbl_recalc_tms_blending").text('0.00');
        } else {
          let lastProvider = null;

          detalle.forEach(d => {
            let pesoHumedo = parseFloat(d.peso_humedo) || 0;
            let h2o = parseFloat(d.porcentaje_humedad) || 0;
            let pesoSeco = parseFloat(d.peso_seco) || 0;

            totalTMH += pesoHumedo;
            totalTMS += pesoSeco;
            sumH2O += h2o;

            if (d.nombre_proveedor !== lastProvider) {
              html += `
                     <tr class="table-dev">
                        <td colspan="4" class="fw-bold text-uppercase" style="background-color:#EFEFEF;">
                            <i class="bi bi-person-fill"></i> ${d.nombre_proveedor}
                        </td>
                     </tr>
                `;
              lastProvider = d.nombre_proveedor;
            }

            html += `
                  <tr>
                    <!-- <td>${d.codigo_gel}</td> -->
                    <td class="ps-4">${d.codigo_gel}</td>
                    <td class="text-end fw-bold text-primary">${formatNumber(pesoHumedo)}</td>
                    <td class="text-end text-muted">${formatNumber(h2o, 3)}</td>
                    <td class="text-end fw-bold text-success">${formatNumber(pesoSeco)}</td>
                  </tr>
                  `;
          });

          // Calculate Totals for Footer
          let avgH2O = count > 0 ? (sumH2O / count) : 0;
          let recalcTMS = 0;
          if (count > 0) {
            recalcTMS = totalTMH / (1 + (avgH2O / 100));
          }

          $("#lbl_total_tmh_blending").text(formatNumber(totalTMH));
          $("#lbl_avg_h2o_blending").text(formatNumber(avgH2O, 3));
          $("#lbl_recalc_tms_blending").text(formatNumber(recalcTMS));
        }
        $("#tbl_detalle_blending").html(html);
      }

      // Expuesta globalmente para usar en el HTML onclick
      window.selectBlending = function (id, event) {
        if (event) event.stopPropagation();

        // UI update
        $(".blending-row").removeClass("selected");
        $(`.blending-row[data-id='${id}']`).addClass("selected");

        let blending = allBlendings.find(b => b.id_blending == id);
        if (blending) {
          $("#blending_seleccionado_codigo").text(blending.correlativo);
          $("#msg_detalle_blending").parent().html('<tr><td colspan="5" class="text-center">Cargando detalle...</td></tr>');

          f_callBackend('get_blending_detalle_by_blending', { id_blending: id })
            .done(function (r) {
              if (r.estado === 1) {
                renderDetalle(r.data.detalle_blending);
              } else {
                alert("Error al cargar detalle.");
              }
            });
        }
      };

      window.anularBlending = function (id, event) {
        if (event) event.stopPropagation();
        if (!confirm("¿Está seguro de ANULAR este blending? Esta acción revertirá el stock a los lotes originales.")) return;

        f_callBackend('anular_blending', { id_blending: id })
          .done(function (r) {
            if (r.estado === 1) {
              alert(r.mensaje);
              loadAllData();
              // Limpiar detalle si era el seleccionado
              if ($("#blending_seleccionado_codigo").text().includes("BL-")) {
                $("#blending_seleccionado_codigo").text("---");
                $("#tbl_detalle_blending").html('<tr><td colspan="5" class="text-center">Seleccione un blending...</td></tr>');
              }
            } else {
              alert("Error: " + r.mensaje);
            }
          });
      };

      function renderLotesDisponibles() {
        let html = '';
        // Filter out already selected lots - use String comparison to ensure proper matching
        let selectedIds = lotesSeleccionados.map(l => String(l.id_lote));
        let availableLots = lotesDisponibles.filter(l => !selectedIds.includes(String(l.id_lote)));

        if (availableLots.length === 0) {
          html = '<tr><td colspan="6" class="text-center">No hay lotes disponibles.</td></tr>';
        } else {
          availableLots.forEach(l => {
            let maxPeso = parseFloat(l.peso_humedo) || 0;

            html += `
                      <tr class="lote-disponible-row" data-id="${l.id_lote}">
                        <td class="text-center small align-middle">${l.proveedor_nombre || '-'}</td>
                        <td class="text-center align-middle fw-bold">${l.codigo_gel}</td>
                        <td class="text-center align-middle">${formatNumber(maxPeso)}</td>
                        <td class="text-center text-muted align-middle">${formatNumber(l.porcentaje_humedad, 3)}</td>
                        <td class="text-center text-success fw-bold align-middle">${formatNumber(l.peso_seco)}</td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-success btn-add-lote" 
                                    data-id="${l.id_lote}" 
                                    data-proveedor="${l.proveedor_nombre || '-'}" 
                                    data-codigo-gel="${l.codigo_gel}" 
                                    data-max="${maxPeso}"
                                    data-h2o="${l.porcentaje_humedad}"
                                    data-tms="${l.peso_seco}"
                                    title="Agregar lote">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </td>
                      </tr>
                      `;
          });
        }
        $("#tbl_lotes_disponibles").html(html);
      }

      function renderLotesSeleccionados() {
        let html = '';
        if (lotesSeleccionados.length === 0) {
          html = '<tr><td colspan="7" class="text-center text-muted">No hay lotes seleccionados.</td></tr>';
        } else {
          lotesSeleccionados.forEach(l => {
            let maxPeso = parseFloat(l.peso_disponible) || 0;
            let pesoActual = parseFloat(l.peso_tomado) || maxPeso;

            html += `
                      <tr class="lote-seleccionado-row" data-id="${l.id_lote}">
                        <td class="text-center small align-middle">${l.proveedor}</td>
                        <td class="text-center align-middle fw-bold">${l.codigo_gel}</td>
                        <td class="text-center align-middle">${formatNumber(maxPeso)}</td>
                        <td class="text-center text-muted align-middle">${formatNumber(l.h2o, 3)}</td>
                        <td class="text-center text-success fw-bold align-middle">${formatNumber(l.tms)}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm text-end input-peso-seleccionado" 
                                   step="0.01" min="0" max="${maxPeso}"
                                   data-id="${l.id_lote}"
                                   value="${pesoActual}">
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-danger btn-remove-lote" 
                                    data-id="${l.id_lote}"
                                    title="Quitar lote">
                                <i class="bi bi-dash-lg"></i>
                            </button>
                        </td>
                      </tr>
                      `;
          });
        }
        $("#tbl_lotes_seleccionados").html(html);
        updateTotalModal();
      }

      // --- ADD AND REMOVE LOT BUTTONS ---

      $(document).on("click", ".btn-add-lote", function () {
        let id = $(this).data('id');
        let proveedor = $(this).data('proveedor');
        let codigoGel = $(this).data('codigo-gel');
        let max = parseFloat($(this).data('max'));
        let h2o = parseFloat($(this).data('h2o'));
        let tms = parseFloat($(this).data('tms'));

        // Check if lot is already selected (prevent duplicates)
        let yaSeleccionado = lotesSeleccionados.some(l => String(l.id_lote) === String(id));
        if (yaSeleccionado) {
          alert("Este lote ya ha sido seleccionado.");
          return;
        }

        // Add to selected array
        lotesSeleccionados.push({
          id_lote: id,
          proveedor: proveedor,
          codigo_gel: codigoGel,
          peso_disponible: max,
          h2o: h2o,
          tms: tms,
          peso_tomado: max // Auto-fill with full weight
        });

        // Re-render both tables
        renderLotesDisponibles();
        renderLotesSeleccionados();
      });

      $(document).on("click", ".btn-remove-lote", function () {
        let id = $(this).data('id');

        // Remove from selected array - use String comparison
        lotesSeleccionados = lotesSeleccionados.filter(l => String(l.id_lote) !== String(id));

        // Re-render both tables
        renderLotesDisponibles();
        renderLotesSeleccionados();
      });

      $(document).on("input change", ".input-peso-seleccionado", function () {
        let val = parseFloat($(this).val());
        let max = parseFloat($(this).attr('max'));
        let id = $(this).data('id');

        // Validate Max
        if (val > max) {
          $(this).val(max);
          val = max;
        }
        if (val < 0) {
          $(this).val(0);
          val = 0;
        }

        // Update peso_tomado in array - use String comparison
        let lote = lotesSeleccionados.find(l => String(l.id_lote) === String(id));
        if (lote) {
          lote.peso_tomado = val;
        }

        updateTotalModal();
      });

      function updateTotalModal() {
        let totalTMH = 0;
        let sumH2O = 0;
        let count = 0;

        lotesSeleccionados.forEach(l => {
          let peso = parseFloat(l.peso_tomado) || 0;
          let h2o = parseFloat(l.h2o) || 0;

          if (peso > 0) {
            totalTMH += peso;
            sumH2O += h2o;
            count++;
          }
        });

        let avgH2O = count > 0 ? (sumH2O / count) : 0;
        let recalcTMS = 0;
        if (count > 0) {
          recalcTMS = totalTMH / (1 + (avgH2O / 100));
        }

        $("#card_total_tmh").text(formatNumber(totalTMH, 2));
        $("#card_avg_h2o").text(formatNumber(avgH2O, 3));
        $("#card_recalc_tms").text(formatNumber(recalcTMS, 2));

        // Enable/Disable create button
        $("#btn_crear_blending").prop('disabled', count < 1);
      }

      // Remove old updateTotalModal if it exists below or above
      /*
      function updateTotalModal() {
      ...
      */

      // -------------------------
      // Eventos
      // -------------------------

      // 1.1 Popular Filtros
      function populateFilters() {
        let uniqueProveedores = {};
        let uniqueCorrelativos = [];

        allBlendings.forEach(b => {
          if (!uniqueProveedores[b.id_proveedor]) {
            uniqueProveedores[b.id_proveedor] = {
              id: b.id_proveedor,
              text: `${b.razon_social} - ${b.documento}`
            };
          }
          uniqueCorrelativos.push({
            id: b.correlativo,
            text: b.correlativo
          });
        });

        // Sort arrays
        let arrProvs = Object.values(uniqueProveedores).sort((a, b) => a.text.localeCompare(b.text));
        let arrCorrels = uniqueCorrelativos.sort((a, b) => b.text.localeCompare(a.text)); // Descending

        // Add 'Todos' option
        arrProvs.unshift({ id: '', text: 'Todos' });
        arrCorrels.unshift({ id: '', text: 'Todos' });

        // Init Select2
        $("#filter_correlativo").empty().select2({
          theme: "bootstrap-5",
          data: arrCorrels
        }).val('').trigger('change.select2');

        $("#filter_estado").select2({
          theme: "bootstrap-5",
        });
      }

      // 1.2 Aplicar Filtros
      function applyFilters() {
        let correlativo = $("#filter_correlativo").val();
        let estado = $("#filter_estado").val();
        let fDesde = dmyToYmd($("#filter_fecha_desde").val());
        let fHasta = dmyToYmd($("#filter_fecha_hasta").val());

        let filtered = allBlendings.filter(b => {
          // Correlativo
          if (correlativo && b.correlativo != correlativo) return false;
          // Estado
          if (estado && b.estado != estado) return false;

          // Fechas
          let fechaReg = b.fecha_registro.substring(0, 10); // YYYY-MM-DD
          if (fDesde && fechaReg < fDesde) return false;
          if (fHasta && fechaReg > fHasta) return false;

          return true;
        });

        renderBlendings(filtered);
      }

      // Botón Aplicar Filtros
      $("#btn_aplicar_filtros").on("click", function () {
        applyFilters();
      });

      // Botón Limpiar Filtros
      $("#btn_limpiar_filtros").on("click", function () {
        $("#filter_correlativo").val(null).trigger('change');
        $("#filter_estado").val('');
        $("#filter_fecha_desde").val('');
        $("#filter_fecha_hasta").val('');
        applyFilters();
      });

      // 1. Cargar datos iniciales
      function loadAllData() {
        f_callBackend('get_lista_blending_cabecera', {})
          .done(function (r) {
            if (r.estado === 1) {
              allBlendings = r.data.blendings;
              populateFilters();
              renderBlendings(allBlendings);
            } else {
              console.error("Error cargando blendings");
            }
          });
      }

      // 2. Abrir Modal Nuevo
      $("#btn_open_new_blending_modal").on("click", function () {
        // Reset Modal
        lotesSeleccionados = []; // Clear selected lots
        lotesDisponibles = []; // Clear available lots
        $("#reg_proveedor").empty();
        $("#tbl_lotes_disponibles").html('<tr><td colspan="6" class="text-center text-muted">Seleccione Buscar para ver lotes.</td></tr>');
        $("#tbl_lotes_seleccionados").html('<tr><td colspan="7" class="text-center text-muted">No hay lotes seleccionados.</td></tr>');
        $("#lbl_total_tomado").text("0.00");
        $("#btn_cargar_lotes").prop('disabled', true);
        $("#btn_crear_blending").prop('disabled', true);

        // Cargar proveedores
        f_callBackend("get_proveedores_to_blending", {})
          .done(function (r) {
            if (r.estado === 1) {
              let data = r.data.proveedores.map(p => ({
                id: p.id_proveedor,
                text: `${p.razon_social} (${p.documento})`
              }));
              $("#reg_proveedor").select2({
                dropdownParent: $('#modal_nuevo_blending'),
                theme: "bootstrap-5",
                placeholder: 'Todos (Opcional)',
                data: data,
                width: '100%',
                allowClear: true
              });
              $("#reg_proveedor").val('').trigger('change'); // Default to All
              $("#btn_cargar_lotes").prop('disabled', false);
            }
          });

        nuevoBlendingModal.show();
      });

      // 3. Buscar Lotes
      $("#btn_cargar_lotes").on("click", function () {
        let id_prov = $("#reg_proveedor").val() || 0; // Send 0 if empty

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        f_callBackend("get_lotes_to_blending_by_proveedor", { id_proveedor: id_prov })
          .done(function (r) {
            $("#btn_cargar_lotes").prop('disabled', false).html('<i class="bi bi-search"></i> Buscar Lotes');
            if (r.estado === 1) {
              lotesDisponibles = r.data.lotes;
              renderLotesDisponibles(); // Will filter out already selected lots
              // Don't reset lotesSeleccionados - preserve selected lots across provider changes
            }
          });
      });

      // 5. Crear Blending Enviar
      $("#btn_crear_blending").on("click", function () {
        let lotesParaEnviar = [];

        lotesSeleccionados.forEach(l => {
          let peso = parseFloat(l.peso_tomado) || 0;
          if (peso > 0) {
            lotesParaEnviar.push({
              id_lote: l.id_lote,
              peso_tomado: peso
            });
          }
        });

        if (lotesParaEnviar.length === 0) {
          alert("Debe seleccionar al menos un lote con peso mayor a 0.");
          return;
        }

        if (!confirm("¿Está seguro de crear este Blending? Se descontará el stock de los lotes seleccionados.")) return;

        // Enviar
        let $btn = $(this);
        $btn.prop('disabled', true).text("Procesando...");

        f_callBackend("crear_blending", {
          lotes: lotesParaEnviar,
        })
          .done(function (r) {
            if (r.estado === 1) {
              alert(r.mensaje);
              $("#modal_nuevo_blending").modal("hide");
              loadAllData();
            } else {
              alert("Error: " + r.mensaje);
            }
          })
          .fail(function () {
            alert("Error de conexión");
          })
          .always(function () {
            $("#btn_crear_blending").prop("disabled", false);
          });
      });

      // 6. Reset Modal on Close (Fix persistence)
      document.getElementById('modal_nuevo_blending').addEventListener('hidden.bs.modal', function () {
        // Reset Filters
        $("#reg_proveedor").val(null).trigger('change');
        // Reset Data
        lotesDisponibles = [];
        lotesSeleccionados = [];
        // Reset Tables
        renderLotesDisponibles();
        renderLotesSeleccionados();
        // Reset Totals
        $("#lbl_total_tomado").text('0.00');
        $("#card_total_tmh").text('0.00');
        $("#card_avg_h2o").text('0.000');
        $("#card_recalc_tms").text('0.00');
        // Disable button
        $("#btn_crear_blending").prop('disabled', true);
      });

      // Start
      function f_Init() {
        f_GetMenuPrincipal();
        $("#nv_titulo").html('| Registro de Blending');
        loadAllData();
      }

      f_Init();
    });
  </script>

</body>

</html>