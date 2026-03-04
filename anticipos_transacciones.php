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

  <title><?php echo $nom_app; ?> | Transacciones de Anticipos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <link rel="stylesheet" href="<?php echo $url_lims; ?>/global/styles.css">

  <style>
    /* Colores basados en el Excel */
    :root {
      --color-primary: #2c3e50;
      --color-secondary: #3498db;
      --color-success: #27ae60;
      --color-warning: #f39c12;
      --color-danger: #e74c3c;
      --color-info: #17a2b8;
      --color-light-gray: #f8f9fa;
      --color-border: #dee2e6;
    }

    .header-bg-primary {
      background-color: var(--color-primary) !important;
      color: white;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-secondary {
      background-color: var(--color-secondary) !important;
      color: white;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-success {
      background-color: var(--color-success) !important;
      color: white;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-warning {
      background-color: var(--color-warning) !important;
      color: white;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-danger {
      background-color: var(--color-danger) !important;
      color: white;
      font-weight: 600;
      vertical-align: middle;
    }

    .table-custom {
      font-size: 13px;
      border-collapse: separate;
      border-spacing: 0;
      border: 1px solid var(--color-border);
      border-radius: 8px;
      overflow: hidden;
    }

    .table-custom th {
      border-bottom: 2px solid var(--color-border);
      padding: 10px 8px;
      text-align: center;
      vertical-align: middle;
    }

    .table-custom td {
      padding: 8px;
      vertical-align: middle;
      border-bottom: 1px solid var(--color-border);
    }

    .table-custom tbody tr:hover {
      background-color: rgba(52, 152, 219, 0.05);
    }

    .table-custom tbody tr:last-child td {
      border-bottom: none;
    }

    .highlight-yellow {
      background-color: #fffacd !important;
      font-weight: bold;
    }

    .highlight-green {
      background-color: #d4edda !important;
    }

    .highlight-blue {
      background-color: #d1ecf1 !important;
    }

    .summary-card {
      background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .summary-item {
      padding: 10px 15px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 5px;
      margin-bottom: 5px;
    }

    .filter-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      border: 1px solid var(--color-border);
    }

    .status-badge {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
    }

    .status-pendiente {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .status-aprobado {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .status-anulado {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .currency-cell {
      text-align: right;
      font-family: 'Courier New', monospace;
      font-weight: 500;
    }

    .positive {
      color: var(--color-success);
      font-weight: bold;
    }

    .negative {
      color: var(--color-danger);
      font-weight: bold;
    }

    .action-cell {
      text-align: center;
      min-width: 120px;
    }

    .btn-custom-sm {
      padding: 3px 8px;
      font-size: 12px;
    }

    .modal-xl-custom {
      max-width: 95%;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .table-responsive {
        font-size: 12px;
      }

      .table-custom th,
      .table-custom td {
        padding: 6px 4px;
      }
    }
  </style>
</head>

<body class="bg-light" style="zoom: 80%;">
  <div class="container-fluid">
    <div class="row">
      <?php echo $navbar_maintop; ?>

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

      <!-- Contenido Principal -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 10px; padding-left: 15px; padding-right: 15px;">

        <!-- Filtros -->
        <div class="filter-card pt-3 pb-3"> <!-- Reduced padding -->
          <h6 class="mb-2 text-secondary"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h6>

          <div class="row g-2"> <!-- G-2 for tighter spacing -->
            <div class="col-md-3">
              <label class="form-label small mb-1">Proveedor</label>
              <select class="form-select form-select-sm" id="filter_proveedor" style="width: 100%;"></select>
            </div>

            <div class="col-md-2">
              <label class="form-label small mb-1">Factura Anticipo</label>
              <input type="text" class="form-control form-control-sm" id="filter_factura_anticipo" placeholder="Ej: F001-123">
            </div>

            <div class="col-md-2">
              <label class="form-label small mb-1">Factura Comprobante</label>
              <input type="text" class="form-control form-control-sm" id="filter_factura_comprobante" placeholder="Ej: E001-42">
            </div>

            <div class="col-md-3">
              <label class="form-label small mb-1">Rango Fechas (Comprobante)</label>
              <div class="input-group input-group-sm">
                <input type="date" class="form-control" id="filter-fecha-desde" title="Desde">
                <span class="input-group-text bg-white">-</span>
                <input type="date" class="form-control" id="filter-fecha-hasta" title="Hasta">
              </div>
            </div>

            <div class="col-md-2">
              <label class="form-label small mb-1">Estado</label>
              <select class="form-select form-select-sm" id="filter-estado">
                <option value="">Todos</option>
                <option value="pendiente_comprobante">Comprobante pendiente</option>
                <option value="proceso_pago">Proceso de pago</option>
                <option value="pagado_mixto">Pagado - Mixto</option>
                <option value="pagado_anticipos">Pagado - Anticipos</option>
                <option value="pagado_banco">Pagado - Banco</option>
              </select>
            </div>

            <div class="col-md-12 mt-2">
              <div class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm me-2" id="btn-aplicar-filtros">
                  <i class="bi bi-search"></i> Buscar
                </button>
                <button class="btn btn-secondary btn-sm me-2" id="btn-limpiar-filtros">
                  <i class="bi bi-eraser"></i> Limpiar
                </button>
                <button class="btn btn-success btn-sm" id="btn-exportar-excel">
                  <i class="bi bi-file-earmark-excel"></i> Excel
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de Transacciones -->
        <div class="card shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-custom table-hover mb-0">
                <thead>
                  <tr>
                    <th class="header-bg-primary text-center" colspan="3">Factura por anticipo</th>
                    <th class="header-bg-success text-center" colspan="3">Acción de anticipo</th>
                    <th class="header-bg-secondary text-center" colspan="4">Factura de venta</th>
                    <th class="header-bg-warning text-center">Saldo</th>
                    <th class="header-bg-info text-center">DSCT DETRACC</th>
                    <th class="header-bg-danger text-center">SALDO DEUDA</th>
                    <th class="header-bg-primary text-center" rowspan="2">ESTADO</th>
                    <th class="header-bg-primary text-center" rowspan="2">N° VALORIZACIÓN</th>
                  </tr>
                  <tr>
                    <!-- Factura por anticipo subcols -->
                    <th class="header-bg-primary text-center small">Factura</th>
                    <th class="header-bg-primary text-center small">Fecha</th>
                    <th class="header-bg-primary text-center small">Importe</th>

                    <!-- Acción de anticipo subcols -->
                    <th class="header-bg-success text-center small">Aplicado 100%</th>
                    <th class="header-bg-success text-center small">Aplicado parcialmente</th>
                    <th class="header-bg-success text-center small">Lote</th>

                    <!-- Factura de venta subcols -->
                    <th class="header-bg-secondary text-center small">N° Factura Amortiza</th>
                    <th class="header-bg-secondary text-center small">Fecha</th>
                    <th class="header-bg-secondary text-center small">Importe Factura $</th>
                    <th class="header-bg-secondary text-center small">Importe Amortiza Adelanto $</th>

                    <!-- Saldo subcol -->
                    <th class="header-bg-warning text-center small">Saldo Factura Amortiza</th>

                    <!-- DSCT DETRACC subcol -->
                    <th class="header-bg-info text-center small">Saldo Neto Factura </th>

                    <!-- SALDO DEUDA subcol -->
                    <th class="header-bg-danger text-center small">Importe $</th>
                  </tr>
                </thead>
                <tbody id="tbl-transacciones">
                  <!-- Los datos se cargarán dinámicamente -->
                  <tr>
                    <td colspan="12" class="text-center py-5">
                      <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                      </div>
                      <p class="mt-2 text-muted">Cargando transacciones...</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal para Detalles -->
  <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl-custom">
      <div class="modal-content">
        <div class="modal-header header-bg-primary text-white">
          <h5 class="modal-title" id="modalDetallesLabel">
            <i class="bi bi-info-circle"></i> Detalles de Transacción
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <h6>Información Principal</h6>
              <table class="table table-sm table-borderless">
                <tr>
                  <td width="40%"><strong>Factura Venta:</strong></td>
                  <td id="detalle-factura-venta">---</td>
                </tr>
                <tr>
                  <td><strong>Factura Amortiza:</strong></td>
                  <td id="detalle-factura-amortiza">---</td>
                </tr>
                <tr>
                  <td><strong>Fecha:</strong></td>
                  <td id="detalle-fecha">---</td>
                </tr>
                <tr>
                  <td><strong>N° Valorización:</strong></td>
                  <td id="detalle-valorizacion">---</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h6>Montos</h6>
              <table class="table table-sm table-borderless">
                <tr>
                  <td width="50%"><strong>Importe Factura:</strong></td>
                  <td class="currency-cell" id="detalle-importe-factura">---</td>
                </tr>
                <tr>
                  <td><strong>Importe Amortiza:</strong></td>
                  <td class="currency-cell" id="detalle-importe-amortiza">---</td>
                </tr>
                <tr>
                  <td><strong>Saldo Deuda:</strong></td>
                  <td class="currency-cell" id="detalle-saldo-deuda">---</td>
                </tr>
                <tr>
                  <td><strong>Estado:</strong></td>
                  <td id="detalle-estado">---</td>
                </tr>
              </table>
            </div>
          </div>

          <h6>Lotes Asociados</h6>
          <div class="table-responsive">
            <table class="table table-sm table-bordered" id="tbl-lotes-detalle">
              <thead class="table-light">
                <tr>
                  <th>Código Lote</th>
                  <th>Descripción</th>
                  <th>Peso (kg)</th>
                  <th>Ley (g/T)</th>
                  <th>Valor (USD)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="5" class="text-center">No hay lotes registrados</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="btn-imprimir-comprobante">
            <i class="bi bi-printer"></i> Imprimir Comprobante
          </button>
        </div>
      </div>
    </div>
  </div>

  <?php include('global/auxiliares_js.php'); ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
      // Variables globales
      let selectedProveedor = null;
      const backendUrl = '<?php echo $backendUrl; ?>';
      const detallesModal = new bootstrap.Modal(document.getElementById('modalDetalles'));

      // Inicializar Select2
      $('#filter_proveedor').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccione un proveedor...',
        allowClear: true
      });

      // Función para llamar al backend
      function f_callBackend(accion, data) {
        return $.post(backendUrl, {
          accion: accion,
          ...data
        }, 'json');
      }

      function formatearMoneda(valor) {
        if (!valor || isNaN(valor)) return '0.00';
        return parseFloat(valor).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      }


      // Formato de fecha (DD/MM/YYYY)
      function formatDate(dateString) {
        if (!dateString) return '';
        // Asumiendo YYYY-MM-DD
        const parts = dateString.split('-');
        if (parts.length < 3) return dateString; // Si ya viene formateada o es invalida
        // Retornar solo fecha si viene con hora
        const dayPart = parts[2].split(' ')[0];
        return `${dayPart}/${parts[1]}/${parts[0]}`;
      }

      // Variables globales
      let currentData = [];

      // Cargar Proveedores
      function loadProviders() {
        f_callBackend('getAnticiposAndProviders', {})
          .done(function(r) {
            if (r.estado === 1 && r.data && r.data.proveedores) {
              const providers = r.data.proveedores.map(p => ({
                id: p.id_proveedor,
                text: `${p.razon_social} (${p.documento})`
              }));

              $('#filter_proveedor').empty();
              $('#filter_proveedor').select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccione un proveedor...',
                allowClear: true,
                data: providers
              });

              $('#filter_proveedor').val(null).trigger('change');
            }
          })
          .fail(function() {
            alert("Error al cargar lista de proveedores.");
          });
      }

      // Renderizar tabla jerárquica
      function renderTable(data) {
        const tbody = $('#tbl-transacciones');
        tbody.empty();

        if (!data || data.length === 0) {
          tbody.html(`
            <tr>
              <td colspan="15" class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 32px;"></i>
                <p class="mt-2 mb-0">No se encontraron registros.</p>
              </td>
            </tr>
          `);
          return;
        }

        let html = '';

        data.forEach((group) => {
          const ant = group.anticipo_info;

          // Si el grupo no tiene transacciones visibles (por filtrado), no mostrar encabezado
          if (!group.transacciones || group.transacciones.length === 0) return;

          html += `
            <tr class="table-secondary fw-bold" style="background-color: #e9ecef;">
              <!-- Info Anticipo -->
              <td class="text-center text-primary">${ant.factura}</td>
              <td class="text-center">${formatDate(ant.fecha)}</td>
              <td class="text-end text-primary">${formatearMoneda(ant.importe_inicial)}</td>
              
              <!-- Resto de columnas vacías para el header del grupo -->
              <td colspan="12" style="background-color: #f8f9fa;"></td>
            </tr>
          `;

          // 2. Filas de Transacciones
          group.transacciones.forEach((tr, index) => {
            // Determinar estado badge
            let estadoBadge = '';
            if (tr.estado_comprobante === 'Pagado - Mixto') {
              estadoBadge = '<span class="badge bg-success">Pagado - Mixto</span>';
            } else if (tr.estado_comprobante === 'Pagado - Anticipos') {
              estadoBadge = '<span class="badge bg-success">Pagado - Anticipos</span>';
            } else if (tr.estado_comprobante === 'Pagado - Banco') {
              estadoBadge = '<span class="badge bg-success">Pagado - Banco</span>';
            } else if (tr.estado_comprobante === 'Proceso de pago') {
              estadoBadge = '<span class="badge bg-warning text-dark">Proceso de pago</span>';
            } else if (tr.estado_comprobante === 'Comprobante pendiente') {
              estadoBadge = '<span class="badge bg-danger">Comprobante pendiente</span>';
            } else {
              estadoBadge = `<span class="badge bg-secondary">${tr.estado_comprobante}</span>`;
            }

            html += `
              <tr>
                <!-- Col 1-3: Anticipo Info (Vacío para transacciones) -->
                <td></td>
                <td></td>
                <td></td>
                
                <!-- Acción Anticipo -->
                <td class="text-center">${tr.porcentaje_aplicado}</td> <!-- Aplicado % -->
                <td class="text-end">${formatearMoneda(tr.monto_aplicado)}</td> <!-- Aplicado Parc -->
                <td class="text-center small font-monospace">${tr.lotes || ''}</td> <!-- Lote -->
                
                <!-- Factura Venta -->
                <td class="text-center fw-bold">${tr.factura_amortiza_serie}</td>
                <td class="text-center">${formatDate(tr.fecha_factura)}</td>
                <td class="text-end">${formatearMoneda(tr.importe_factura_usd)}</td>
                <td class="text-end bg-warning fw-bold">${formatearMoneda(tr.importe_amortiza_adelanto_usd)}</td>
                
                <!-- Vacíos solicitados -->
                <td class="text-end">${tr.saldo_factura_amortiza ? formatearMoneda(tr.saldo_factura_amortiza) : '-'}</td>
                <td class="text-end">${tr.saldo_neto_factura_amortiza ? formatearMoneda(tr.saldo_neto_factura_amortiza) : '-'}</td>
                
                <!-- Saldo Deuda (Saldo Restante del Anticipo) -->
                <td class="text-end fw-bold text-primary">${formatearMoneda(tr.saldo_deuda_usd)}</td>
                
                <!-- Estado y Val -->
                <td class="text-center">${estadoBadge}</td>
                <td class="text-center">${tr.nro_valorizacion || ''}</td>
              </tr>
             `;
          });
        });

        // Si después de filtrar filas individuales no queda nada en el HTML (casos raros)
        if (html === '') {
          tbody.html(`
            <tr>
              <td colspan="15" class="text-center py-5 text-muted">
                <p class="mt-2 mb-0">No hay coincidencias con los filtros.</p>
              </td>
            </tr>
          `);
        } else {
          tbody.html(html);
        }
      }

      function filterAndRender() {
        const fAnticipo = $('#filter_factura_anticipo').val().toLowerCase().trim();
        const fComprobante = $('#filter_factura_comprobante').val().toLowerCase().trim();
        const fEstado = $('#filter-estado').val();

        if (currentData.length === 0) {
          renderTable([]);
          return;
        }

        // Filtrado profundo: Clonar estructura para no mutar original
        // Estrategia: Iterar grupos, filtrar transacciones dentro, si quedan transacciones mantener grupo.
        // Ademas, filtro de Anticipo Factura aplica al Header del grupo.

        const filteredData = [];

        currentData.forEach(group => {
          const ant = group.anticipo_info;
          // Filtro 1: Factura Anticipo (match en el padre)
          const matchAnticipo = ant.factura.toLowerCase().includes(fAnticipo);

          // Si el anticipo no matchea y se escribio algo, descartamos todo el grupo?
          // UX: Si busco anticipo "F001", quiero ver sus trs. Si busco comprobante "E001", quiero ver trs E001 dentro de cualquier anticipo.
          // Si estricto: (MatchAnt OR EmptyAnt) AND (Trans Has MatchComp)

          // Simplificacion: Si buscó anticipo, debe matchear.
          if (fAnticipo && !matchAnticipo) return; // Skip group

          // Filtro 2 y 3: Transacciones
          const matchingTransacciones = group.transacciones.filter(tr => {
            // Filtro Factura Comprobante
            const matchComprobante = !fComprobante || tr.factura_amortiza_serie.toLowerCase().includes(fComprobante);

            // Filtro Estado
            let matchEstado = true;
            if (fEstado) {
              // Map backend status to filter values
              let statusKey = '';
              if (tr.estado_comprobante === 'Pagado - Mixto') {
                statusKey = 'pagado_mixto';
              } else if (tr.estado_comprobante === 'Pagado - Anticipos') {
                statusKey = 'pagado_anticipos';
              } else if (tr.estado_comprobante === 'Pagado - Banco') {
                statusKey = 'pagado_banco';
              } else if (tr.estado_comprobante === 'Proceso de pago') {
                statusKey = 'proceso_pago';
              } else if (tr.estado_comprobante === 'Comprobante pendiente') {
                statusKey = 'pendiente_comprobante';
              }

              if (statusKey !== fEstado) matchEstado = false;
            }

            return matchComprobante && matchEstado;
          });

          if (matchingTransacciones.length > 0) {
            // Clonar grupo y asignar trs filtradas
            filteredData.push({
              anticipo_info: ant,
              transacciones: matchingTransacciones
            });
          }
        });

        renderTable(filteredData);
      }

      // Cargar datos
      function loadReporte() {
        const idProveedor = $('#filter_proveedor').val();
        // Fechas se envian al backend
        const fechaDesde = $('#filter-fecha-desde').val();
        const fechaHasta = $('#filter-fecha-hasta').val();

        if (!idProveedor) {
          $('#tbl-transacciones').html(`
             <tr>
               <td colspan="15" class="text-center py-5 text-muted">
                 <p class="mt-2">Seleccione un proveedor para ver el reporte.</p>
               </td>
             </tr>
           `);
          return;
        }

        $('#tbl-transacciones').html(`
           <tr>
             <td colspan="15" class="text-center py-5">
               <div class="spinner-border text-primary" role="status">
                 <span class="visually-hidden">Cargando...</span>
               </div>
               <p class="mt-2 text-muted">Generando reporte...</p>
             </td>
           </tr>
         `);

        f_callBackend('getReporteAnticiposTransacciones', {
            id_proveedor: idProveedor,
            fecha_desde: fechaDesde,
            fecha_hasta: fechaHasta
          })
          .done(function(r) {
            if (r.estado === 1) {
              currentData = r.data || [];
              filterAndRender(); // Filtra (vacio) y Rende
            } else {
              alert("Error al cargar reporte: " + (r.msg || "Desconocido"));
              $('#tbl-transacciones').empty();
            }
          })
          .fail(function() {
            alert("Error de conexión.");
            $('#tbl-transacciones').empty();
          });
      }

      // Eventos
      $('#btn-aplicar-filtros').on('click', function() {
        loadReporte();
      });

      $('#btn-exportar-excel').on('click', function() {
        const idProveedor = $('#filter_proveedor').val();
        if (!idProveedor) {
          alert('Seleccione un proveedor para exportar.');
          return;
        }

        const fechaDesde = $('#filter-fecha-desde').val();
        const fechaHasta = $('#filter-fecha-hasta').val();

        // Create form
        const form = $('<form>', {
          action: backendUrl,
          method: 'POST',
          target: '_blank'
        });

        $('<input>').attr({
          type: 'hidden',
          name: 'accion',
          value: 'exportExcelResumenTransacciones'
        }).appendTo(form);
        $('<input>').attr({
          type: 'hidden',
          name: 'id_proveedor',
          value: idProveedor
        }).appendTo(form);
        $('<input>').attr({
          type: 'hidden',
          name: 'fecha_desde',
          value: fechaDesde
        }).appendTo(form);
        $('<input>').attr({
          type: 'hidden',
          name: 'fecha_hasta',
          value: fechaHasta
        }).appendTo(form);

        form.appendTo('body').submit().remove();
        $post
      });

      // Filtros cliente-side inmediatos
      $('#filter_factura_anticipo, #filter_factura_comprobante, #filter-estado').on('keyup change', function() {
        filterAndRender();
      });

      $('#btn-limpiar-filtros').on('click', function() {
        $('#filter_proveedor').val(null).trigger('change');
        $('#filter-fecha-desde').val('');
        $('#filter-fecha-hasta').val('');
        $('#filter_factura_anticipo').val('');
        $('#filter_factura_comprobante').val('');
        $('#filter-estado').val('');

        currentData = []; // Clear data
        loadReporte();
      });

      // Inicialización
      function f_Init() {
        f_GetMenuPrincipal();
        $("#nv_titulo").html('| Transacciones Anticipos - Reporte');
        loadProviders();
        // Renderizar tabla vacía inicial
        loadReporte();
      }

      f_Init();
    });
  </script>

</body>

</html>