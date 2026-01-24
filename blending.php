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
                <div class="col-12">
                  <label class="form-label small mb-0 fw-bold">Proveedor</label>
                  <select id="filter_proveedor" class="form-select form-select-sm w-100"></select>
                </div>
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
                <div class="col-6">
                  <label class="form-label small mb-0 fw-bold">Desde</label>
                  <input type="date" id="filter_fecha_desde" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                  <label class="form-label small mb-0 fw-bold">Hasta</label>
                  <div class="input-group input-group-sm">
                    <input type="date" id="filter_fecha_hasta" class="form-control form-control-sm">
                    <button class="btn btn-outline-secondary" type="button" id="btn_limpiar_filtros"
                      title="Limpiar Filtros"><i class="bi bi-x-lg"></i></button>
                  </div>
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
                      <!-- <th class="header-bg-detalle text-center">ID Lote</th> -->
                      <!-- <th class="header-bg-detalle text-center">Código Lote</th> -->
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
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modal_nuevo_blending_Label"><i class="bi bi-box-seam"></i> Crear Nuevo Blending
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-8">
              <label for="reg_proveedor" class="form-label">Proveedor (con lotes disponibles)</label>
              <select id="reg_proveedor" class="form-select" data-bs-theme="bootstrap-5"></select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button class="btn btn-primary w-100" id="btn_cargar_lotes" disabled>
                <i class="bi bi-search"></i> Buscar Lotes
              </button>
            </div>
          </div>

          <hr>

          <h6 class="mb-2">Seleccione Lotes y Cantidades:</h6>
          <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-sm table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="text-center" width="50">Sel.</th>
                  <!-- <th>Lote</th> -->
                  <th>Código Gel</th>
                  <th class="text-end">TMH (Peso Húmedo)</th>
                  <th class="text-end">H2O</th>
                  <th class="text-end">TMS (Peso Seco)</th>
                </tr>
              </thead>
              <tbody id="tbl_lotes_disponibles">
                <tr>
                  <td colspan="5" class="text-center text-muted">Seleccione un proveedor para ver sus lotes.</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-secondary fw-bold">
                  <td colspan="2" class="text-end">TOTALES:</td>
                  <td class="text-end text-primary" id="lbl_total_tmh">0.00</td>
                  <td class="text-end text-primary" id="lbl_avg_h2o">0.000</td>
                  <td class="text-end text-primary" id="lbl_recalc_tms">0.00</td>
                </tr>
              </tfoot>
            </table>
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

  <?php include('global/auxiliares_js.php'); ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>


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

      // -------------------------
      // Lógica de Renderizado
      // -------------------------

      function renderBlendings(dataList = allBlendings) {
        let html = '';
        if (dataList.length === 0) {
          html = '<tr><td colspan="7" class="text-center">No se encontraron blendings registrados.</td></tr>';
        } else {
          // Ordenar por Proveedor y luego por ID descendente
          dataList.sort((a, b) => {
            if (a.razon_social < b.razon_social) return -1;
            if (a.razon_social > b.razon_social) return 1;
            return b.id_blending - a.id_blending;
          });

          let lastProviderId = null;

          dataList.forEach(b => {
            let estadoClass = b.estado === 'Activo' ? 'text-success' : 'text-muted';

            // Verificar cambio de proveedor para insertar cabecera
            if (b.id_proveedor !== lastProviderId) {
              html += `
                     <tr class="table-primary">
                        <td colspan="7" class="fw-bold text-uppercase"><i class="bi bi-person-badge-fill me-2"></i>${b.razon_social} <span class="text-muted fw-normal small">(${b.documento})</span></td>
                     </tr>
                     `;
              lastProviderId = b.id_proveedor;
            }

            html += `
                 <tr class="blending-row" data-id="${b.id_blending}" onclick="selectBlending(${b.id_blending})">
                    <td class="fw-bold text-center ps-4"><i class="bi bi-caret-right-fill text-muted" style="font-size: 0.8em;"></i> ${b.correlativo}</td>
                    <td class="text-end">${formatNumber(b.peso_inicial)}</td>
                    <td class="text-end">${formatNumber(b.peso_actual)}</td>
                    <td class="text-center ${estadoClass}">${b.estado}</td>
                    <td class="text-center">${b.fecha_registro}</td>
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
          detalle.forEach(d => {
            let pesoHumedo = parseFloat(d.peso_humedo) || 0;
            let h2o = parseFloat(d.porcentaje_humedad) || 0;
            let pesoSeco = parseFloat(d.peso_seco) || 0;

            totalTMH += pesoHumedo;
            totalTMS += pesoSeco;
            sumH2O += h2o;

            html += `
                  <tr>
                    <!-- <td>${d.codigo_gel}</td> -->
                    <td>${d.codigo_gel}</td>
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
        if (lotesDisponibles.length === 0) {
          html = '<tr><td colspan="5" class="text-center">No hay lotes con saldo para este proveedor.</td></tr>';
        } else {
          lotesDisponibles.forEach(l => {
            html += `
                  <tr class="lote-disponible-row" data-id="${l.id_lote}">
                    <td class="text-center">
                        <input type="checkbox" class="chk-lote form-check-input" 
                               data-id="${l.id_lote}" 
                               data-tmh="${l.peso_humedo}" 
                               data-h2o="${l.porcentaje_humedad}" 
                               data-tms="${l.peso_seco}">
                    </td>
                    <!-- <td>${l.codigo_lote}</td> -->
                    <td>${l.codigo_gel}</td>
                    <td class="text-end fw-bold">${formatNumber(l.peso_humedo)}</td>
                    <td class="text-end text-muted">${formatNumber(l.porcentaje_humedad, 3)}</td>
                    <td class="text-end text-success fw-bold">${formatNumber(l.peso_seco)}</td>
                  </tr>
                  `;
          });
        }
        $("#tbl_lotes_disponibles").html(html);
        updateTotalModal();
      }

      function updateTotalModal() {
        let selectedItems = [];
        $(".chk-lote:checked").each(function () {
          selectedItems.push({
            tmh: parseFloat($(this).data('tmh')) || 0,
            h2o: parseFloat($(this).data('h2o')) || 0,
            tms: parseFloat($(this).data('tms')) || 0
          });
        });

        let totalTMH = 0;
        let sumH2O = 0;
        let count = selectedItems.length;

        selectedItems.forEach(item => {
          totalTMH += item.tmh;
          sumH2O += item.h2o;
        });

        let avgH2O = count > 0 ? (sumH2O / count) : 0;
        // Formula: TMS = TMH / (1 + (H2O % / 100))
        // Pero el valor de h2o ya viene como 0.052 (que es 5.2%)? 
        // El usuario dice "da valores algo asi: 0.052". 
        // Entonces la formula sería: Recalc TMS = Sum(TMH) / (1 + Avg(H2O))
        // Si el usuario dijo: Peso humedo/(1 + (ley de humedad)/100)
        // Y el backend da 0.052 para 5.2%, entonces 0.052 * 100 = 5.2
        let recalcTMS = 0;
        if (count > 0) {
          // Si h2o es 0.052, entonces ley_humedad es 5.2
          let ley_humedad_avg = avgH2O;
          recalcTMS = totalTMH / (1 + (ley_humedad_avg / 100));
        }

        $("#lbl_total_tmh").text(formatNumber(totalTMH, 2));
        $("#lbl_avg_h2o").text(formatNumber(avgH2O, 3));
        $("#lbl_recalc_tms").text(formatNumber(recalcTMS, 2));

        // Enable/Disable create button
        $("#btn_crear_blending").prop('disabled', count <= 1);
      }

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

        // Init Select2 Proveedor
        $("#filter_proveedor").empty().select2({
          theme: "bootstrap-5",
          data: arrProvs
        }).val('').trigger('change.select2');

        // Init Select2 Correlativo
        $("#filter_correlativo").empty().select2({
          theme: "bootstrap-5",
          data: arrCorrels
        }).val('').trigger('change.select2');
      }

      // 1.2 Aplicar Filtros
      function applyFilters() {
        let provId = $("#filter_proveedor").val();
        let correlativo = $("#filter_correlativo").val();
        let estado = $("#filter_estado").val();
        let fDesde = $("#filter_fecha_desde").val();
        let fHasta = $("#filter_fecha_hasta").val();

        let filtered = allBlendings.filter(b => {
          // Proveedor
          if (provId && b.id_proveedor != provId) return false;
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

      // Event Listeners for Filters
      $("#filter_proveedor, #filter_correlativo, #filter_estado").on("change", function () {
        applyFilters();
      });
      $("#filter_fecha_desde, #filter_fecha_hasta").on("input change", function () {
        applyFilters();
      });
      $("#btn_limpiar_filtros").on("click", function () {
        $("#filter_proveedor").val(null).trigger('change');
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
        $("#reg_proveedor").empty();
        $("#tbl_lotes_disponibles").html('<tr><td colspan="6" class="text-center text-muted">Seleccione un proveedor y busque lotes.</td></tr>');
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
                placeholder: 'Seleccione proveedor',
                data: data,
                width: '100%'
              });
              $("#btn_cargar_lotes").prop('disabled', false);
            }
          });

        nuevoBlendingModal.show();
      });

      // 3. Buscar Lotes
      $("#btn_cargar_lotes").on("click", function () {
        let id_prov = $("#reg_proveedor").val();
        if (!id_prov) return;

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        f_callBackend("get_lotes_to_blending_by_proveedor", { id_proveedor: id_prov })
          .done(function (r) {
            $("#btn_cargar_lotes").prop('disabled', false).html('<i class="bi bi-search"></i> Buscar Lotes');
            if (r.estado === 1) {
              lotesDisponibles = r.data.lotes;
              renderLotesDisponibles();
            }
          });
      });

      // 4. Interacción en tabla lotes (Checkbox y Inputs)
      $(document).on("change", ".chk-lote", function () {
        updateTotalModal();
      });

      // 5. Crear Blending Enviar
      $("#btn_crear_blending").on("click", function () {
        let id_proveedor = $("#reg_proveedor").val();
        let lotesSeleccionados = [];

        if (!id_proveedor) {
          alert("Debe seleccionar un proveedor.");
          return;
        }

        $(".chk-lote:checked").each(function () {
          let tmh = parseFloat($(this).data("tmh"));
          lotesSeleccionados.push({
            id_lote: $(this).data("id"),
            peso_tomado: tmh
          });
        });

        if (lotesSeleccionados.length === 0) {
          alert("Debe seleccionar al menos un lote con peso mayor a 0.");
          return;
        }

        if (!confirm("¿Está seguro de crear este Blending? Se descontará el stock de los lotes seleccionados.")) return;

        // Enviar
        let $btn = $(this);
        $btn.prop('disabled', true).text("Procesando...");
        console.log('lotesSeleccionados', lotesSeleccionados);
        console.log('id_proveedor', id_proveedor);

        f_callBackend("crear_blending", {
          lotes: lotesSeleccionados,
          id_proveedor: id_proveedor
        })
          .done(function (r) {
            if (r.estado === 1) {
              alert(r.mensaje);
              nuevoBlendingModal.hide();
              loadAllData(); // Recargar lista
            } else {
              alert("Error: " + r.mensaje);
            }
          })
          .fail(function () { alert("Error de conexión"); })
          .always(function () { $btn.prop('disabled', false).text("Crear Blending"); });
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