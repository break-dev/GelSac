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

  <title><?php echo $nom_app; ?> | Despachos y Distribuciones</title>

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
    .header-bg-primary {
      background-color: #2c3e50 !important;
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-secondary {
      background-color: #7f8c8d !important;
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    /* Estilo para las filas seleccionables */
    .clickable-row:hover {
      background-color: #e0f7fa;
      cursor: pointer;
    }

    .clickable-row.selected {
      background-color: #b3e5fc;
      font-weight: bold;
      border-left: 4px solid #0d6efd;
    }

    .badge-mineral-type {
      font-size: 0.8em;
      padding: 0.3em 0.6em;
      border-radius: 4px;
    }

    .badge-lote {
      background-color: #17a2b8;
      color: white;
    }

    .badge-blending {
      background-color: #6f42c1;
      color: white;
    }
  </style>
</head>

<body class="bg-light" style="zoom: 85%;">
  <div class="container-fluid">
    <div class="row">
      <?php echo $navbar_maintop; ?>

      <!-- Modal Menú (Heredado de plantilla) -->
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

      <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px; padding-left: 20px; padding-right: 20px;">
        <div class="row">

          <!-- Header Section -->
          <div class="col-12 bg-white shadow-sm p-3 mb-3 rounded d-flex justify-content-between align-items-center">
            <div>
              <h4 class="mb-0"><i class="bi bi-truck-flatbed"></i> Gestión de Despachos y Distribuciones</h4>
              <small class="text-muted">Administre los envíos de mineral a plantas y sus distribuciones
                posteriores.</small>
            </div>
            <div>
              <span class="me-3 fs-5">
                Total Despachos: <strong id="total_despachos" class="text-primary">0</strong>
              </span>
              <button class="btn btn-danger me-2" id="btn_export_pdf">
                <i class="bi bi-file-earmark-pdf"></i> Exportar a PDF
              </button>
              <button class="btn btn-success me-2" id="btn_export_excel">
                <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
              </button>
              <button class="btn btn-primary" id="btn_open_new_despacho_modal">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Despacho
              </button>
            </div>
          </div>

          <!-- Columna Izquierda: Lista de Despachos -->
          <div class="col-md-6">
            <div class="bg-white shadow-sm p-3 rounded h-100">
              <h5 class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-task"></i> Historial de Despachos</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="loadAllDespachos()" title="Recargar"><i
                    class="bi bi-arrow-clockwise"></i></button>
              </h5>
              <hr class="my-2" />

              <!-- Filters Section -->
              <div class="row g-2 mb-3 bg-light p-2 rounded border">
                <div class="col-6">
                  <label class="form-label small mb-0 fw-bold">Planta</label>
                  <select id="filter_planta" class="form-select form-select-sm w-100"></select>
                </div>
                <div class="col-6">
                  <label class="form-label small mb-0 fw-bold">Proveedor</label>
                  <select id="filter_proveedor" class="form-select form-select-sm w-100"></select>
                </div>
                <!-- <div class="col-6">
                  <label class="form-label small mb-0 fw-bold">Estado</label>
                  <select id="filter_estado" class="form-select form-select-sm">
                    <option value="Activo">Activo</option>
                    <option value="Anulado">Anulado</option>
                  </select>
                </div> -->
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

              <div class="table-responsive" style="height: 55vh; overflow-y: auto;">
                <table class="table table-bordered table-hover table-sm">
                  <thead class="sticky-top">
                    <tr style="font-size: 13px;">
                      <th class="header-bg-primary text-center">Código</th>
                      <th class="header-bg-primary text-center">Proveedor</th>
                      <th class="header-bg-primary text-center">Fecha</th>
                      <th class="header-bg-primary text-center">Items</th>
                      <th class="header-bg-primary text-center">Estado</th>
                      <th class="header-bg-primary text-center" width="50">Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_despachos" style="font-size: 13px;">
                    <tr>
                      <td colspan="6" class="text-center p-3">Cargando datos...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Columna Derecha: Detalle del Despacho -->
          <div class="col-md-6">
            <div class="d-flex flex-column h-100">

              <!-- Panel Superior: Detalle de Minerales -->
              <div class="bg-white shadow-sm p-3 rounded mb-3 flex-grow-1">
                <h5 class="d-flex justify-content-between align-items-center">
                  <span><i class="bi bi-box-seam"></i> Detalle del Despacho: <span id="lbl_despacho_seleccionado"
                      class="text-primary fw-bold">---</span></span>
                </h5>
                <div id="info_provider_plant" class="mb-2 text-muted small fst-italic">Seleccione un despacho
                  para ver
                  detalles.</div>
                <hr class="my-2" />

                <div class="table-responsive" style="max-height: 40vh; overflow-y: auto;">
                  <table class="table table-bordered table-striped table-sm">
                    <thead class="sticky-top">
                      <tr style="font-size: 13px;">
                        <th class="header-bg-secondary text-center">Tipo</th>
                        <th class="header-bg-secondary text-center">Código Mineral</th>
                        <th class="header-bg-secondary text-center">Peso Lote/Bleding</th>
                        <th class="header-bg-secondary text-center">Peso en Despacho</th>
                        <th class="header-bg-secondary text-center">Peso en Distribucion</th>
                      </tr>
                    </thead>
                    <tbody id="tbl_detalle_despacho" style="font-size: 13px;">
                      <tr>
                        <td colspan="5" class="text-center text-muted p-3">---</td>
                      </tr>
                    </tbody>
                    <tfoot id="tfoot_detalle_despacho" style="display:none;">
                      <tr class="fw-bold table-light">
                        <td colspan="3" class="text-end">Totales:</td>
                        <td class="text-end" id="lbl_total_peso_despacho">0.00</td>
                        <td class="text-end" id="lbl_total_peso_distribuido">0.00</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <!-- Panel Inferior: Distribuciones -->
              <div class="bg-white shadow-sm p-3 rounded flex-grow-1 footer-panel d-flex flex-column">
                <h5 class="d-flex justify-content-between align-items-center">
                  <span><i class="bi bi-diagram-3"></i> Distribuciones</span>
                  <button class="btn btn-sm btn-primary" id="btn_open_new_distribucion" disabled>
                    <i class="bi bi-plus-lg"></i> Añadir Distribución
                  </button>
                </h5>
                <hr class="my-2" />
                <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                  <table class="table table-bordered table-sm table-hover align-middle">
                    <thead class="bg-light sticky-top">
                      <tr style="font-size: 13px;">
                        <th class="text-center">Nro. Unidad</th>
                        <th class="text-center">Info. Transporte</th>
                        <th class="text-center">Fecha Llegada Est.</th>
                        <th class="text-end">Peso Total</th>
                        <th class="text-center" width="70">Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="tbl_distribuciones" style="font-size: 13px;">
                      <tr>
                        <td colspan="5" class="text-center text-muted p-3">Seleccione un despacho para ver sus
                          distribuciones.</td>
                      </tr>
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

  <!-- Modal Nuevo Despacho -->
  <div class="modal fade" id="modal_nuevo_despacho" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="bi bi-send-plus"></i> Crear Nuevo Despacho</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Paso 1: Configuración Inicial -->
          <div class="card mb-3 border-0 bg-light">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-5">
                  <label class="form-label fw-bold small text-uppercase text-muted">1. Planta Destino</label>
                  <select id="reg_planta" class="form-select" data-bs-theme="bootstrap-5"></select>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-bold small text-uppercase text-muted">2. Proveedor</label>
                  <select id="reg_proveedor" class="form-select" data-bs-theme="bootstrap-5" disabled></select>
                </div>
                <div class="col-md-3 d-flex align-items-end justify-content-end">
                  <button class="btn btn-primary" id="btn_buscar_minerales" disabled>
                    <i class="bi bi-search"></i> Listar Lotes/Blendings
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Paso 2: Selección de Minerales -->
          <h6 class="border-bottom pb-2 mb-2">3. Seleccione los Lotes o Blendings e ingrese su peso a Despachar
          </h6>
          <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-sm table-hover align-middle">
              <thead class="table-secondary sticky-top">
                <tr>
                  <th class="text-center" width="40"><i class="bi bi-check2-square"></i></th>
                  <th>Código</th>
                  <th>Tipo</th>
                  <th class="text-end">Peso Actual</th>
                  <th class="text-center" width="180">Peso a Despachar</th>
                </tr>
              </thead>
              <tbody id="tbl_minerales_disponibles">
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">Configure la Planta y el Proveedor para
                    cargar
                    minerales disponibles.</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
        <div class="modal-footer bg-light d-flex justify-content-between">
          <div class="fw-bold fs-5">
            Total a Despachar: <span id="lbl_total_modal" class="text-primary">0.00</span>
          </div>
          <div>
            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary px-4" id="btn_crear_despacho" disabled>
              <i class="bi bi-check-lg"></i> Crear Despacho
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Nueva Distribucion -->
  <div class="modal fade" id="modal_nueva_distribucion" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="bi bi-truck"></i> Nueva Distribución de Carga</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold text-muted">1. Transportista</label>
              <select id="dist_transportista" class="form-select" data-bs-theme="bootstrap-5"></select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold text-muted">2. Tipo de Vehículo</label>
              <select id="dist_tipo_vehiculo" class="form-select" data-bs-theme="bootstrap-5"></select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold text-muted">3. Unidad (Placa)</label>
              <select id="dist_unidad" class="form-select" data-bs-theme="bootstrap-5" capacidad="0" disabled></select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold text-muted">Datos Adicionales</label>
              <input type="text" class="form-control" id="dist_segunda_placa" placeholder="Segunda Placa / Carreta">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold text-muted">Fecha Estimada Llegada</label>
              <input type="text" class="form-control bg-white" id="dist_fecha" placeholder="dd/mm/yyyy" readonly>
            </div>
          </div>

          <h6 class="border-bottom pb-2 mb-2 text-success">Seleccione items del despacho a distribuir</h6>
          <div class="table-responsive">
            <table class="table table-sm table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th width="40" class="text-center"><i class="bi bi-check2-square"></i></th>
                  <th>Código Mineral</th>
                  <th class="text-center">Tipo</th>
                  <th class="text-end">Peso Restante</th>
                  <th class="text-end" width="180">A Distribuir</th>
                </tr>
              </thead>
              <tbody id="tbl_items_distribucion"></tbody>
            </table>
          </div>

        </div>
        <div class="modal-footer bg-light d-flex justify-content-between">
          <div class="fw-bold fs-5">
            Total Asignado: <span id="lbl_total_dist_modal" class="text-success">0.00</span>
          </div>
          <div>
            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="btn_guardar_distribucion" disabled>
              <i class="bi bi-save"></i> Guardar Distribución
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- Modal Ver Detalle Distribucion -->
  <div class="modal fade" id="modal_detalle_distribucion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="bi bi-eye"></i> Detalle de Distribución</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-6">
              <strong>Transportista:</strong> <br> <span id="view_dist_transportista" class="text-muted">...</span>
            </div>
            <div class="col-6">
              <strong>Unidad / Placa:</strong> <br> <span id="view_dist_placa" class="text-muted">...</span>
            </div>
            <div class="col-6 mt-2">
              <strong>Fecha Estimada:</strong> <br> <span id="view_dist_fecha" class="text-muted">...</span>
            </div>
            <div class="col-6 mt-2">
              <strong>Peso Total:</strong> <br> <span id="view_dist_total" class="fw-bold text-success">...</span>
            </div>
          </div>

          <h6 class="border-bottom pb-2">Mineral Distribuido:</h6>
          <table class="table table-sm table-bordered table-striped">
            <thead class="table-light">
              <tr>
                <th>Código</th>
                <th class="text-center">Tipo</th>
                <th class="text-end">Peso</th>
                <th class="text-end">Nro. Partición</th>
              </tr>
            </thead>
            <tbody id="tbl_view_dist_items"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <select id="voiceList" style="display:none;"></select>
  <?php include('global/auxiliares_js.php'); ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {

      // VARIABLES GLOBALES
      const backendUrl = '<?php echo $backendUrl; ?>';
      const modalNuevoDespacho = new window.bootstrap.Modal(document.getElementById("modal_nuevo_despacho"));
      const modalNuevaDistribucion = new window.bootstrap.Modal(document.getElementById("modal_nueva_distribucion"));

      let allDespachos = [];
      let mineralesDisponibles = [];
      let itemsDespachoDistribucion = [];
      let selectedDespachoId = 0;

      // Initialize Flatpickr
      const flatpickrConfig = {
        locale: "es",
        dateFormat: "d/m/Y",
        allowInput: true,
      };
      flatpickr("#filter_fecha_desde", flatpickrConfig);
      flatpickr("#filter_fecha_hasta", flatpickrConfig);
      flatpickr("#dist_fecha", flatpickrConfig);



      // FUNCIONES AUXILIARES
      function f_callBackend(accion, data) {
        return $.post(backendUrl, { accion: accion, ...data }, 'json');
      }

      function formatNumber(num) {
        return parseFloat(num).toLocaleString('es-PE', {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      }

      function formatDateToDMY(dateStr) {
        if (!dateStr) return "";
        // Asumiendo formato YYYY-MM-DD o YYYY-MM-DD HH:mm:ss
        let parts = dateStr.split(' ')[0].split('-');
        if (parts.length === 3) {
          return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
        return dateStr;
      }

      function dmyToYmd(dateStr) {
        if (!dateStr) return "";
        let parts = dateStr.split('/');
        if (parts.length === 3) {
          return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        return dateStr;
      }

      // --------------------------------------------------------------------------------
      // VIEW LOGIC: LISTADO PRINCIPAL
      // --------------------------------------------------------------------------------

      window.loadAllDespachos = function () {
        $("#tbl_despachos").html('<tr><td colspan="6" class="text-center p-3">Actualizando...</td></tr>');

        f_callBackend('get_lista_despacho_cabecera', {})
          .done(function (r) {
            if (r.estado === 1) {
              allDespachos = r.data.despachos;
              populateFilters();
              applyFilters();
            } else {
              console.error("Error al cargar despachos");
            }
          })
          .fail(function () {
            $("#tbl_despachos").html('<tr><td colspan="6" class="text-center text-danger p-3">Error de conexión.</td></tr>');
            $("#total_despachos").text("0");
          });
      };

      // --------------------------------------------------------------------------------
      // FILTER LOGIC
      // --------------------------------------------------------------------------------
      function populateFilters() {
        let uniquePlantas = {};
        let uniqueProveedores = {};

        allDespachos.forEach(d => {
          if (!uniquePlantas[d.id_planta]) {
            uniquePlantas[d.id_planta] = { id: d.id_planta, text: d.descripcion_planta };
          }
          if (!uniqueProveedores[d.id_proveedor]) {
            uniqueProveedores[d.id_proveedor] = { id: d.id_proveedor, text: d.razon_social };
          }
        });

        // Convert to Arrays & Sort
        let arrPlantas = Object.values(uniquePlantas).sort((a, b) => a.text.localeCompare(b.text));
        let arrProvs = Object.values(uniqueProveedores).sort((a, b) => a.text.localeCompare(b.text));

        // Add 'Todos'
        arrPlantas.unshift({ id: '', text: 'Todos' });
        arrProvs.unshift({ id: '', text: 'Todos' });

        let $selPlanta = $("#filter_planta");
        let $selProv = $("#filter_proveedor");

        let curPlanta = $selPlanta.val();
        let curProv = $selProv.val();

        $selPlanta.empty().select2({ theme: 'bootstrap-5', data: arrPlantas });
        $selProv.empty().select2({ theme: 'bootstrap-5', data: arrProvs });

        if (curPlanta) $selPlanta.val(curPlanta).trigger('change.select2');
        if (curProv) $selProv.val(curProv).trigger('change.select2');
      }

      function applyFilters() {
        let fPlanta = $("#filter_planta").val();
        let fProv = $("#filter_proveedor").val();
        // let fEst = $("#filter_estado").val();
        let fDesde = dmyToYmd($("#filter_fecha_desde").val());
        let fHasta = dmyToYmd($("#filter_fecha_hasta").val());

        let filtered = allDespachos.filter(d => {
          // Planta
          if (fPlanta && d.id_planta != fPlanta) return false;
          // Proveedor
          if (fProv && d.id_proveedor != fProv) return false;

          // Fechas
          let fecha = d.fecha_registro.substring(0, 10);
          if (fDesde && fecha < fDesde) return false;
          if (fHasta && fecha > fHasta) return false;

          return true;
        });

        renderDespachos(filtered);
      }

      $("#btn_aplicar_filtros").click(applyFilters);

      $("#btn_limpiar_filtros").click(function () {
        $("#filter_planta").val('').trigger('change');
        $("#filter_proveedor").val('').trigger('change');
        // $("#filter_estado").val('');
        $("#filter_fecha_desde").val('');
        $("#filter_fecha_hasta").val('');
        applyFilters();
      });

      // Export Handlers
      $("#btn_export_pdf").click(function () {
        let pl = $("#filter_planta").val() || "";
        let pv = $("#filter_proveedor").val() || "";
        let d1 = dmyToYmd($("#filter_fecha_desde").val()) || "";
        let d2 = dmyToYmd($("#filter_fecha_hasta").val()) || "";

        let url = `print_despachos_distribuciones.php?planta=${pl}&proveedor=${pv}&desde=${d1}&hasta=${d2}`;
        window.open(url, '_blank');
      });

      $("#btn_export_excel").click(function () {
        let pl = $("#filter_planta").val() || "";
        let pv = $("#filter_proveedor").val() || "";
        let d1 = dmyToYmd($("#filter_fecha_desde").val()) || "";
        let d2 = dmyToYmd($("#filter_fecha_hasta").val()) || "";

        // Excel - versio 1
        let url = `export_to_excel/export_despachos_distribuciones_data_cruda.php?planta=${pl}&proveedor=${pv}&desde=${d1}&hasta=${d2}`;
        window.open(url, '_blank');
      });


      function renderDespachos(dataList = allDespachos) {
        let html = '';
        if (dataList.length === 0) {
          html = '<tr><td colspan="6" class="text-center text-muted p-3">No hay despachos registrados o coincidentes.</td></tr>';
        } else {
          // Sort by Plant then by ID Descending
          dataList.sort((a, b) => {
            if (a.descripcion_planta < b.descripcion_planta) return -1;
            if (a.descripcion_planta > b.descripcion_planta) return 1;
            return b.id_despacho - a.id_despacho;
          });

          let lastPlantId = null;

          dataList.forEach(d => {
            let totalItems = parseInt(d.blending_usados) + parseInt(d.lotes_usados);
            let estadoBadge = (d.estado == 'I' || d.estado == '0')
              ? '<span class="badge bg-danger" style="font-size:12px !important;">Anulado</span>'
              : '<span class="badge bg-success" style="font-size:12px !important;">Activo</span>';

            // Group Header
            if (d.id_planta !== lastPlantId) {
              html += `
                    <tr class="table-primary">
                        <td colspan="6" class="fw-bold text-uppercase" style="background-color: #e9ecef;">
                            <i class="bi bi-building me-2"></i>${d.descripcion_planta} 
                            <span class="text-muted fw-normal">(${d.ruc_planta})</span>
                        </td>
                    </tr>
                `;
              lastPlantId = d.id_planta;
            }

            html += `
                  <tr class="clickable-row ${(d.id_despacho == selectedDespachoId) ? 'selected' : ''}" onclick="selectDespacho(${d.id_despacho}, this)" data-id="${d.id_despacho}">
                      <td class="text-center fw-bold">${d.correlativo}</td>
                      <td>
                        <div class="fw-bold">${d.razon_social}</div>
                        <div class="text-muted" style="font-size: 1em;">${d.documento_proveedor}</div>
                      </td>
                      <td class="text-center">${formatDateToDMY(d.fecha_registro)}</td>
                      <td class="text-center">
                        <span class="badge bg-secondary rounded-pill" style="font-size:12px !important;">${totalItems} Items</span>
                      </td>
                      <td class="text-center">${estadoBadge}</td>
                      <td class="text-center">
                        <button class="btn btn-sm btn-link text-primary"><i class="bi bi-eye-fill"></i></button>

                        <button class="btn btn-sm btn-link text-danger" onclick="anularDespacho(${d.id_despacho}, event)"><i class="bi bi-trash-fill"></i></button>
                      </td>
                  </tr>
                  `;
          });
        }

        $("#tbl_despachos").html(html);
        $("#total_despachos").text(allDespachos.length);
      }

      window.selectDespacho = function (id, rowElem) {
        // Highlight Row
        if (rowElem) {
          $(".clickable-row").removeClass("selected");
          $(rowElem).addClass("selected");
        }

        let d = allDespachos.find(x => x.id_despacho == id);
        if (!d) return;

        // Header Info
        $("#lbl_despacho_seleccionado").text(d.correlativo);
        $("#info_provider_plant").html(`
            <strong>Planta:</strong> ${d.descripcion_planta} &nbsp;|&nbsp; 
            <strong>Proveedor:</strong> ${d.razon_social} (${d.documento_proveedor})
          `);

        // Load Details
        $("#tbl_detalle_despacho").html('<tr><td colspan="4" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando detalle...</td></tr>');

        f_callBackend('get_despacho_detalle_by_despacho', { id_despacho: id })
          .done(function (r) {
            if (r.estado === 1) {
              renderDetalleDespacho(r.data.detalle_blending);
              selectedDespachoId = id;
              $("#btn_open_new_distribucion").prop('disabled', false);
              loadDistribuciones(id); // Cargar distribuciones
            }
          });
      };

      function loadDistribuciones(idDespacho) {
        $("#tbl_distribuciones").html('<tr><td colspan="4" class="text-center p-3">Cargando distribuciones...</td></tr>');

        f_callBackend('get_distribuciones_by_despacho', { id_despacho: idDespacho })
          .done(function (r) {
            let html = '';
            if (r.estado === 1 && r.data && r.data.distribuciones) {
              let dists = r.data.distribuciones;
              if (dists.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted p-2">Sin distribuciones registradas.</td></tr>';
              } else {
                let contador = 1;
                dists.forEach(d => {
                  let meta = encodeURIComponent(JSON.stringify(d));

                  html += `
                    <tr>
                      <td class="text-center fw-bold">${contador}</td>
                      <td> 
                        <div class="fw-bold text-truncate" style="font-size:14px !important;">${d.tipo_vehiculo} | ${d.placa} ${d.segunda_placa ? '(' + d.segunda_placa + ')' : ''} | Cap. ${d.capacidad}</div>
                        <div class="text-muted" title="${d.nombre_transportista}">${d.nombre_transportista}</div>
                      </td>
                      <td class="text-center">${formatDateToDMY(d.fecha_estimada)}</td>
                      <td class="text-end fw-bold text-success">${formatNumber(d.peso_acumulado)}</td>
                      <td class="text-center">
                          <button class="btn btn-sm btn-link text-primary" onclick="viewDistribucion(${d.id_distribucion}, '${meta}')">
                            <i class="bi bi-eye-fill"></i>
                          </button>

                          <button class="btn btn-sm btn-link text-danger" onclick="anularDistribucion(${d.id_distribucion}, event)">
                            <i class="bi bi-trash-fill"></i>
                          </button>
                      </td>
                    </tr>`;
                  contador++;
                });
              }
            } else {
              html = '<tr><td colspan="4" class="text-center text-muted p-2">No se encontraron datos.</td></tr>';
            }
            $("#tbl_distribuciones").html(html);
          });
      }

      window.viewDistribucion = function (id, metaStr) {
        const modalView = new window.bootstrap.Modal(document.getElementById("modal_detalle_distribucion"));
        let meta = JSON.parse(decodeURIComponent(metaStr));

        // Set Header Info
        $("#view_dist_transportista").text(meta.nombre_transportista);
        $("#view_dist_placa").text(`${meta.tipo_vehiculo} - ${meta.placa} ${meta.segunda_placa ? '/ ' + meta.segunda_placa : ''}`);
        $("#view_dist_fecha").text(formatDateToDMY(meta.fecha_estimada));
        $("#view_dist_total").text(formatNumber(meta.peso_acumulado));

        $("#tbl_view_dist_items").html('<tr><td colspan="3" class="text-center">Cargando detalles...</td></tr>');

        modalView.show();

        // Fetch Details
        f_callBackend('get_detalle_distribucion_by_distribucion', { id_distribucion: id })
          .done(function (r) {
            if (r.estado === 1) {
              let html = '';
              let items = r.data.detalles || [];
              if (items.length === 0) {
                html = '<tr><td colspan="3" class="text-center">Sin items.</td></tr>';
              } else {
                items.forEach(i => {
                  let isBlending = (i.is_blending == 1);
                  let badge = isBlending
                    ? '<span class="badge-mineral-type badge-blending">Blending</span>'
                    : '<span class="badge-mineral-type badge-lote">Lote</span>';

                  html += `
                            <tr>
                                <td>${i.codigo}</td>
                                <td class="text-center">${badge}</td>
                                <td class="text-end font-monospace">${formatNumber(i.peso_tomado)}</td>
                                <td class="text-end font-monospace">${i.numero_parte != null ? i.numero_parte : 'Dis. Total'}</td>
                            </tr>
                          `;
                });
              }
              $("#tbl_view_dist_items").html(html);
            } else {
              $("#tbl_view_dist_items").html('<tr><td colspan="3" class="text-center text-danger">Error al cargar.</td></tr>');
            }
          });
      };

      function renderDetalleDespacho(detalles) {
        let html = '';
        let totalPeso = 0;

        if (!detalles || detalles.length === 0) {
          html = '<tr><td colspan="4" class="text-center text-muted">Sin detalles.</td></tr>';
        } else {
          detalles.forEach(item => {
            let isBlending = (item.is_blending == 1);
            let badge = isBlending
              ? '<span class="badge-mineral-type badge-blending" style="font-size:12px !important;">Blending</span>'
              : '<span class="badge-mineral-type badge-lote" style="font-size:12px !important;">Lote</span>';

            html += `
                  <tr>
                      <td class="text-center">${badge}</td>
                      <td class="fw-bold text-dark">${item.codigo}</td>
                      <td class="text-end text-muted">${formatNumber(item.peso_actual_log)}</td>
                      <td class="text-end fw-bold text-primary">${formatNumber(item.peso_tomado)}</td>
                      <td class="text-end fw-bold text-primary">${formatNumber(item.peso_distribuido)}</td>
                  </tr>
                  `;
            totalPeso += parseFloat(item.peso_tomado);
          });
        }

        $("#tbl_detalle_despacho").html(html);
        $("#lbl_total_peso_despacho").text(formatNumber(totalPeso));

        // Calcular total distribuido
        let totalDist = 0;
        if (detalles && detalles.length > 0) {
          detalles.forEach(item => {
            totalDist += parseFloat(item.peso_distribuido || 0);
          });
        }
        $("#lbl_total_peso_distribuido").text(formatNumber(totalDist));

        $("#tfoot_detalle_despacho").show();
      }


      // --------------------------------------------------------------------------------
      // MODAL LOGIC: NUEVO DESPACHO
      // --------------------------------------------------------------------------------

      // Paso 0: Abrir Modal -> Cargar Plantas
      $("#btn_open_new_despacho_modal").click(function () {
        f_openDespachoModal();
      });

      function f_openDespachoModal() {
        // UI Reset
        $("#modal_nuevo_despacho .modal-title").html('<i class="bi bi-send-plus"></i> Crear Nuevo Despacho');
        $("#btn_crear_despacho").text("Crear Despacho");

        $("#reg_planta").empty().append('<option value="">Cargando...</option>').prop('disabled', false);
        $("#reg_proveedor").empty().prop('disabled', true);

        $("#btn_buscar_minerales").prop('disabled', true);
        $("#tbl_minerales_disponibles").html('<tr><td colspan="5" class="text-center text-muted py-4">Seleccione planta y proveedor.</td></tr>');
        $("#lbl_total_modal").text("0.00");
        $("#btn_crear_despacho").prop('disabled', true);

        // Get Plantas
        f_callBackend('get_plantas_to_despachos', {})
          .done(function (r) {
            if (r.estado === 1) {
              let opts = r.data.plantas.map(p => ({ id: p.id_planta, text: `${p.descripcion} (RUC: ${p.ruc})` }));

              $("#reg_planta").empty().select2({
                dropdownParent: $('#modal_nuevo_despacho'),
                theme: "bootstrap-5",
                placeholder: "Seleccione Planta Destino",
                data: opts,
                width: '100%'
              }).val('').trigger('change');
            }
          });

        modalNuevoDespacho.show();
      }

      // Paso 1: Configurar Change de Planta -> Cargar Proveedores
      $("#reg_planta").on('change', function () {
        let idPlanta = $(this).val();

        $("#reg_proveedor").empty().prop('disabled', true);
        $("#btn_buscar_minerales").prop('disabled', true);

        if (idPlanta) {
          f_callBackend('get_proveedores_by_planta', { id_planta: idPlanta })
            .done(function (r) {
              if (r.estado === 1) {
                let opts = r.data.proveedores.map(p => ({ id: p.id_proveedor, text: `${p.razon_social} (${p.documento})` }));
                $("#reg_proveedor").prop('disabled', false).select2({
                  dropdownParent: $('#modal_nuevo_despacho'),
                  theme: "bootstrap-5",
                  placeholder: "Seleccione Proveedor",
                  data: opts,
                  width: '100%'
                }).val('').trigger('change');
              }
            });
        }
      });

      // Paso 2: Configurar Change de Proveedor -> Activar Botón Buscar
      $("#reg_proveedor").on('change', function () {
        let val = $(this).val();
        $("#btn_buscar_minerales").prop('disabled', !val);
        // Limpiar tabla si cambia proveedor
        $("#tbl_minerales_disponibles").html('<tr><td colspan="5" class="text-center text-muted py-4">Haga clic en Listar Lotes/Blendings.</td></tr>');
      });

      // Paso 3: Click Buscar Minerales
      $("#btn_buscar_minerales").click(function () {
        let idProv = $("#reg_proveedor").val();
        if (!idProv) return;

        let $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        f_callBackend('get_minerales_to_despacho_by_proveedor', { id_proveedor: idProv })
          .done(function (r) {
            $btn.prop('disabled', false).html('<i class="bi bi-search"></i> Listar Lotes/Blendings');

            if (r.estado === 1) {
              mineralesDisponibles = r.data.minerales;
              renderMineralesTable();
            } else {
              alert("Error al obtener minerales.");
            }
          });
      });

      function renderMineralesTable() {
        let html = '';
        if (mineralesDisponibles.length === 0) {
          html = '<tr><td colspan="5" class="text-center">El proveedor no tiene Lotes o Blendings disponibles.</td></tr>';
        } else {
          mineralesDisponibles.forEach((m, index) => {
            let isBlending = (m.is_blending == 1); // Ensure boolean check works with response
            let badge = isBlending
              ? '<span class="badge-mineral-type badge-blending">Blending</span>'
              : '<span class="badge-mineral-type badge-lote">Lote</span>';

            // Use unique ID for row/checkbox
            let uniqueId = `min_${isBlending ? 'B' : 'L'}_${m.id_mineral}`;

            html += `
                  <tr class="${isBlending ? 'table-info' : ''}">
                      <td class="text-center">
                          <input type="checkbox" class="form-check-input chk-min" 
                            id="${uniqueId}"
                            data-idx="${index}">
                      </td>
                      <td>
                        <label class="form-check-label w-100 cursor-pointer" for="${uniqueId}">
                            ${m.codigo}
                        </label>
                      </td>
                      <td>${badge}</td>
                      <td class="text-end font-monospace">${formatNumber(m.peso_actual)}</td>
                      <td>
                          <input type="number" 
                            class="form-control form-control-sm text-end input-peso-despacho" 
                            placeholder="0.00"
                            step="0.01"
                            disabled
                            data-max="${m.peso_actual}"
                            data-idx="${index}">
                      </td>
                  </tr>
                  `;
          });
        }
        $("#tbl_minerales_disponibles").html(html);
        updateTotalModal();
      }

      // Paso 4: Interacción en Tabla (Checkbox / Input)
      $(document).on('change', '.chk-min', function () {
        let idx = $(this).data('idx');
        let checked = $(this).is(':checked');

        let $input = $(`.input-peso-despacho[data-idx='${idx}']`);
        $input.prop('disabled', !checked);

        if (checked) {
          $input.focus();
          let pesoTotal = mineralesDisponibles[idx].peso_actual;
          $input.val(pesoTotal);
        } else {
          $input.val('');
        }
        updateTotalModal();
      });

      $(document).on('input', '.input-peso-despacho', function () {
        let max = parseFloat($(this).data('max'));
        let val = parseFloat($(this).val());

        if (val < 0) $(this).val(0);
        if (val > max) {
          alert("No puede despachar más del peso actual: " + max);
          $(this).val(max);
        }
        updateTotalModal();
      });

      function updateTotalModal() {
        let total = 0;
        $(".input-peso-despacho:enabled").each(function () {
          let v = parseFloat($(this).val());
          if (!isNaN(v)) total += v;
        });
        $("#lbl_total_modal").text(formatNumber(total));
        $("#btn_crear_despacho").prop('disabled', total <= 0);
      }

      // Paso 5: CREAR / EDITAR DESPACHO (Unified)
      $("#btn_crear_despacho").click(function () {
        let lista = [];

        $(".chk-min:checked").each(function () {
          let idx = $(this).data('idx');
          let val = parseFloat($(`.input-peso-despacho[data-idx='${idx}']`).val());
          let min = mineralesDisponibles[idx];

          if (val > 0) {
            lista.push({
              id_mineral: min.id_mineral,
              is_blending: min.is_blending,
              peso_tomado: val
            });
          }
        });

        if (lista.length === 0 && !confirm("¿Guardar sin minerales? (Vacío)")) return;

        let action = "crear_despacho";
        let payload = {
          id_planta: $("#reg_planta").val(),
          id_proveedor: $("#reg_proveedor").val(),
          // estado: $("#reg_estado").val(),
          minerales: lista
        };

        if (!confirm("¿Confirmar operación?")) return;

        let $btn = $(this);
        $btn.prop('disabled', true).text("Procesando...");

        f_callBackend(action, payload)
          .done(function (r) {
            if (r.estado === 1) {
              alert(r.mensaje || "Operación exitosa.");
              modalNuevoDespacho.hide();
              loadAllDespachos();
            } else {
              alert("Error: " + r.mensaje);
            }
          })
          .always(function () {
            $btn.prop('disabled', false).html('Crear Despacho');
          });
      });

      window.anularDespacho = function (id, e) {
        if (e) e.stopPropagation();
        if (!confirm("¿ANULAR Despacho? Se revertirá todo el stock.")) return;
        f_callBackend('anular_despacho', { id_despacho: id }).done(function (r) {
          if (r.estado === 1) {
            alert("Anulado.");
            loadAllDespachos();
            // If we are looking at this dispatch, clear view
            if (id == selectedDespachoId) {
              selectedDespachoId = 0;
              $("#lbl_despacho_seleccionado").text("---");
              $("#info_provider_plant").text("Seleccione un despacho para ver detalles.");
              $("#tbl_detalle_despacho").html('<tr><td colspan="5" class="text-center text-muted p-3">---</td></tr>');
              $("#tfoot_detalle_despacho").hide();
              $("#tbl_distribuciones").html('<tr><td colspan="5" class="text-center text-muted p-3">Seleccione un despacho para ver sus distribuciones.</td></tr>');
              $("#btn_open_new_distribucion").prop('disabled', true);
            }
          }
          else alert("Error: " + r.mensaje);
        });
      };

      window.anularDistribucion = function (id, e) {
        if (e) e.stopPropagation();
        if (!confirm("¿ANULAR Distribución? Se devolverá peso al Despacho.")) return;
        f_callBackend('anular_distribucion', { id_distribucion: id }).done(function (r) {
          if (r.estado === 1) {
            alert("Anulado.");
            if (selectedDespachoId) {
              selectDespacho(selectedDespachoId); // Reloads everything
            } else {
              loadAllDespachos(); // Fallback
            }
          }
          else alert("Error: " + r.mensaje);
        });
      };


      // --------------------------------------------------------------------------------
      // MODAL LOGIC: NUEVA DISTRIBUCION
      // --------------------------------------------------------------------------------

      $("#btn_open_new_distribucion").click(function () {
        if (!selectedDespachoId) return;

        // Limpiar UI
        $("#dist_transportista, #dist_tipo_vehiculo").empty().append('<option value="">Cargando...</option>');
        $("#dist_unidad").empty().prop('disabled', true);
        $("#dist_segunda_placa").val('');
        $("#dist_fecha").val(new Date().toISOString().split('T')[0]); // Default today
        $("#tbl_items_distribucion").html('<tr><td colspan="5" class="text-center p-3">Cargando items...</td></tr>');
        $("#lbl_total_dist_modal").text("0.00");
        $("#btn_guardar_distribucion").prop('disabled', true);

        modalNuevaDistribucion.show();

        // Load Initial Data
        $.when(
          f_callBackend('get_lista_transportistas', {}),
          f_callBackend('get_tipos_vehiculo', {}),
          f_callBackend('get_minerales_of_despacho_to_distribucion', { id_despacho: selectedDespachoId })
        ).done(function (rTr, rTv, rMin) {
          let r1 = rTr[0];
          let r2 = rTv[0];
          let r3 = rMin[0];

          if (r1.estado === 1) {
            let opts = r1.data.transportistas.map(t => ({ id: t.id_transportista, text: t.razon_social + ' (' + t.documento + ')' }));
            $("#dist_transportista").empty().select2({
              dropdownParent: $('#modal_nueva_distribucion'),
              theme: "bootstrap-5",
              placeholder: "Seleccione Transportista",
              data: opts,
              width: '100%'
            }).val('').trigger('change');
          }

          if (r2.estado === 1) {
            let types = r2.data.tipos_vehiculo || [];
            $("#dist_tipo_vehiculo").empty().select2({
              dropdownParent: $('#modal_nueva_distribucion'),
              theme: "bootstrap-5",
              placeholder: "Seleccione Tipo",
              data: types.map(t => ({ id: t.id_tipo_vehiculo, text: t.descripcion })),
              width: '100%'
            }).val('').trigger('change');
          }

          if (r3.estado === 1) {
            itemsDespachoDistribucion = r3.data.minerales || [];
            renderItemsDistribucion();
          }
        });
      });

      // Cascading Dropdowns
      function loadUnidades() {
        let idTr = $("#dist_transportista").val();
        let idTv = $("#dist_tipo_vehiculo").val();

        $("#dist_unidad").empty().prop('disabled', true);

        if (idTr && idTv) {
          f_callBackend('get_unidades_transporte_to_distribucion', { id_transportista: idTr, id_tipo_vehiculo: idTv })
            .done(function (r) {
              if (r.estado === 1) {
                let units = r.data.unidades || [];
                if (units.length > 0) {
                  let opts = units.map(u => ({
                    id: u.id_unidad,
                    text: `${u.placa} (Cap: ${u.capacidad})`,
                    capacidad: parseFloat(u.capacidad) || 0
                  }));
                  $("#dist_unidad").prop('disabled', false).select2({
                    dropdownParent: $('#modal_nueva_distribucion'),
                    theme: "bootstrap-5",
                    placeholder: "Seleccione Unidad",
                    data: opts,
                    width: '100%'
                  });
                } else {
                  // No units
                }
              }
            });
        }
      }

      $("#dist_transportista, #dist_tipo_vehiculo").on("change", function () {
        loadUnidades();
      });

      // --------------------------------------------------------------------------------
      // MODAL LOGIC: NUEVA
      // --------------------------------------------------------------------------------

      $("#btn_open_new_distribucion").click(function () {
        f_openDistribucionModal();
      });

      function f_openDistribucionModal() {
        if (!selectedDespachoId) return;

        // UI Reset
        $("#modal_nueva_distribucion .modal-title").html('<i class="bi bi-truck"></i> Nueva Distribución');
        $("#btn_guardar_distribucion").text("Guardar Distribución");

        $("#dist_transportista, #dist_tipo_vehiculo").empty().append('<option value="">Cargando...</option>');
        $("#dist_unidad").empty().prop('disabled', true);
        $("#dist_segunda_placa").val('');
        // Default today DD/MM/YYYY
        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let yyyy = today.getFullYear();
        $("#dist_fecha").val(`${dd}/${mm}/${yyyy}`);

        $("#tbl_items_distribucion").html('<tr><td colspan="5" class="text-center p-3">Cargando items...</td></tr>');
        $("#lbl_total_dist_modal").text("0.00");
        $("#btn_guardar_distribucion").prop('disabled', true);

        modalNuevaDistribucion.show();

        // Load Data Chain
        $.when(
          f_callBackend('get_lista_transportistas', {}),
          f_callBackend('get_tipos_vehiculo', {}),
          f_callBackend('get_minerales_of_despacho_to_distribucion', { id_despacho: selectedDespachoId })
        ).done(function (rTr, rTv, rMin) {
          let r1 = rTr[0];
          let r2 = rTv[0];
          let r3 = rMin[0];

          if (r1.estado === 1) {
            let opts = r1.data.transportistas.map(t => ({ id: t.id_transportista, text: t.razon_social + ' (' + t.documento + ')' }));
            $("#dist_transportista").empty().select2({
              dropdownParent: $('#modal_nueva_distribucion'),
              theme: "bootstrap-5",
              placeholder: "Seleccione Transportista",
              data: opts
            }).val('').trigger('change');
          }

          if (r2.estado === 1) {
            let types = r2.data.tipos_vehiculo || [];
            $("#dist_tipo_vehiculo").empty().select2({
              dropdownParent: $('#modal_nueva_distribucion'),
              theme: "bootstrap-5",
              placeholder: "Seleccione Tipo",
              data: types.map(t => ({ id: t.id_tipo_vehiculo, text: t.descripcion }))
            }).val('').trigger('change');
          }

          if (r3.estado === 1) {
            itemsDespachoDistribucion = r3.data.minerales || [];
            renderItemsDistribucion();
          }
        });
      }

      function renderItemsDistribucion() {
        let html = '';
        if (itemsDespachoDistribucion.length === 0) {
          html = '<tr><td colspan="5" class="text-center">No hay saldo disponible en este despacho.</td></tr>';
        } else {
          itemsDespachoDistribucion.forEach((m, idx) => {
            let isBlending = (m.is_blending == 1);
            let badge = isBlending
              ? '<span class="badge-mineral-type badge-blending">Blending</span>'
              : '<span class="badge-mineral-type badge-lote">Lote</span>';
            let restante = parseFloat(m.peso_actual);
            let uid = `dist_item_${m.id_despacho_detalle}`;

            html += `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input chk-dist-item" id="${uid}" data-idx="${idx}">
                        </td>
                        <td><label class="cursor-pointer w-100" for="${uid}">${m.codigo}</label></td>
                        <td class="text-center">${badge}</td>
                        <td class="text-end text-muted font-monospace">${formatNumber(restante)}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm text-end input-dist-peso" 
                                data-idx="${idx}" data-max="${restante}" disabled placeholder="0.00" step="0.01">
                        </td>
                    </tr>
                   `;
          });
        }
        $("#tbl_items_distribucion").html(html);
      }

      // Checkbox / Input Logic
      $(document).on('change', '.chk-dist-item', function () {
        let idx = $(this).data('idx');
        let chk = $(this).is(':checked');
        let $inp = $(`.input-dist-peso[data-idx='${idx}']`);

        $inp.prop('disabled', !chk);
        if (chk) {
          $inp.focus();
          let pesoRestante = itemsDespachoDistribucion[idx].peso_actual;
          $inp.val(pesoRestante);
        } else {
          $inp.val('');
        }

        updateTotalDist();
      });

      $(document).on('input', '.input-dist-peso', function () {
        let max = parseFloat($(this).data('max'));
        let val = parseFloat($(this).val());
        if (val < 0) $(this).val(0);
        if (val > max) {
          alert("Excede el peso restante: " + max);
          $(this).val(max);
        }
        updateTotalDist();
      });

      function updateTotalDist() {
        let total = 0;
        $(".input-dist-peso:enabled").each(function () {
          let v = parseFloat($(this).val());
          if (!isNaN(v)) total += v;
        });
        $("#lbl_total_dist_modal").text(formatNumber(total));
        $("#btn_guardar_distribucion").prop('disabled', total <= 0);
      }

      // Guardar Distribucion
      $("#btn_guardar_distribucion").click(function () {
        let idUnidad = $("#dist_unidad").val();
        let fecha = dmyToYmd($("#dist_fecha").val());

        if (!idUnidad) {
          alert("Seleccione una unidad.");
          return;
        }

        let $btn = $(this);
        $btn.prop('disabled', true);

        // Verificar uso de unidad
        f_callBackend("verificar_uso_unidad_en_distribuciones", {
          id_unidad: idUnidad,
          fecha_estimada: fecha
        }).done(function (rVer) {
          $btn.prop('disabled', false);

          // Check simplified response
          if (rVer.estado === 1 && rVer.data.en_uso) {
            let correlativos = rVer.data.despachos_correlativos ? rVer.data.despachos_correlativos.join(", ") : "";
            let msg = "Para el día " + formatDateToDMY(fecha) + ", la unidad seleccionada estará en uso en los siguientes despachos: " + correlativos + ".\n¿Desea continuar?";

            if (!confirm(msg)) {
              return;
            }
          }

          let payload = {
            id_despacho: selectedDespachoId,
            id_unidad: idUnidad,
            segunda_placa: $("#dist_segunda_placa").val(),
            fecha_estimada: fecha,
            // estado: $("#dist_estado").val(), // Logic removed
            detalle: []
          };

          let totalPesoDistribucion = 0;

          $(".input-dist-peso:enabled").each(function () {
            let val = parseFloat($(this).val());
            let idx = $(this).data('idx');
            if (val > 0) {
              totalPesoDistribucion += val;
              payload.detalle.push({
                id_despacho_detalle: itemsDespachoDistribucion[idx].id_despacho_detalle,
                peso_tomado: val
              });
            }
          });

          if (payload.detalle.length === 0 && !confirm("¿Guardar sin items?")) return;

          // Validar Capacidad
          let selectedData = $("#dist_unidad").select2('data');
          let capacidadUnidad = (selectedData && selectedData[0]) ? (selectedData[0].capacidad || 0) : 0;

          if (totalPesoDistribucion > capacidadUnidad) {
            if (!confirm("La capacidad del vehículo (" + formatNumber(capacidadUnidad) + ") es inferior al peso total (" + formatNumber(totalPesoDistribucion) + ").\n¿Desea continuar?")) {
              return;
            }
          }
          else {
            if (!confirm("¿Confirmar Distribución?")) return;
          }

          let action = "crear_distribucion";

          $btn.prop('disabled', true);

          f_callBackend(action, payload).done(function (r) {
            if (r.estado === 1) {
              alert("Guardado correctamente.");
              modalNuevaDistribucion.hide();
              loadDistribuciones(selectedDespachoId);
              selectDespacho(selectedDespachoId);
            } else {
              alert("Error: " + r.mensaje);
            }
          }).always(() => $btn.prop('disabled', false));

        }).fail(function () {
          $btn.prop('disabled', false);
          alert("Error al verificar disponibilidad de unidad.");
        });
      });

      // INIT
      function init() {
        f_GetMenuPrincipal();
        $("#nv_titulo").html('| Despachos');
        loadAllDespachos();
      }

      init();
    });
  </script>
</body>

</html>