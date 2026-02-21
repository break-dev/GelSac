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
                  <th class="">Tipo de Carga</th>
                  <th class="" width="120">Cant. BigBags</th>
                  <th class="text-end" width="180">Peso a Distribuir</th>
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
            <div class="col-8">
              <strong>Transportista:</strong> <br> <span id="view_dist_transportista" class="text-muted">...</span>
            </div>
            <div class="col-4">
              <strong>Estado / Pesaje:</strong> <br> <span id="view_dist_estados" class="text-muted">...</span>
            </div>
            <div class="col-12 mt-2">
              <strong>Unidad / Placa:</strong> <br> <span id="view_dist_placa" class="text-muted">...</span>
            </div>
            <div class="col-4 mt-2">
              <strong>Fecha Estimada:</strong> <br> <span id="view_dist_fecha" class="text-muted">...</span>
            </div>
            <div class="col-4 mt-2">
              <strong>Llegada:</strong> <br> <span id="view_dist_llegada" class="text-muted">...</span>
            </div>
            <div class="col-4 mt-2">
              <strong>Salida:</strong> <br> <span id="view_dist_salida" class="text-muted">...</span>
            </div>
            <div class="col-12 mt-2">
              <strong>Peso Acumulado / Esperado:</strong> <br> <span id="view_dist_total"
                class="fw-bold text-success">...</span>
            </div>
          </div>

          <h6 class="border-bottom pb-2">Mineral Distribuido:</h6>
          <table class="table table-sm table-bordered table-striped">
            <thead class="table-light">
              <tr>
                <th>Código</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Tipo de Carga</th>
                <th class="text-end">P. Tara</th>
                <th class="text-end">P. Bruto</th>
                <th class="text-end">P. Neto</th>
                <th class="text-end">P. Tomado</th>
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <?php include('global/auxiliares_js.php'); ?>

  <script>
    const backendUrl = '<?php echo $backendUrl; ?>';
  </script>
  <script src="./despachos_distribuciones.js?v=<?php echo time(); ?>"></script>

  <!-- Modal Blending Issues -->
  <div class="modal fade" id="modal_blending_issues" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Información de Blending</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">
            Este blending contiene lotes de proveedores que <strong>no están asociados</strong> a la planta
            seleccionada.
          </p>
          <div id="body_blending_issues" style="max-height: 400px; overflow-y: auto;">
            <!-- Content -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>