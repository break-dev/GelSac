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
        <div class="filter-card">
          <h5 class="mb-3"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h5>

          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Proveedor</label>
              <input type="text" class="form-control" id="filter_proveedor" placeholder="Ej: E001-42">
            </div>

            <div class="col-md-3">
              <label class="form-label">N° Factura</label>
              <input type="text" class="form-control" id="filter_factura" placeholder="Ej: E001-42">
            </div>

            <div class="col-md-2">
              <label class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="filter-fecha-desde">
            </div>

            <div class="col-md-2">
              <label class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="filter-fecha-hasta">
            </div>

            <div class="col-md-2">
              <label class="form-label">Estado</label>
              <select class="form-select" id="filter-estado">
                <option value="">Todos</option>
                <option value="pendiente">Comprobante Pendiente</option>
                <option value="aprobado">Aprobado</option>
                <option value="anulado">Anulado</option>
              </select>
            </div>

            <div class="col-md-12 mt-3">
              <div class="d-flex justify-content-between">
                <div>
                  <button class="btn btn-secondary btn-sm" id="btn-limpiar-filtros">
                    <i class="bi bi-eraser"></i> Limpiar Filtros
                  </button>
                </div>
                <div>
                  <button class="btn btn-primary btn-sm me-2" id="btn-aplicar-filtros">
                    <i class="bi bi-search"></i> Aplicar Filtros
                  </button>
                  <button class="btn btn-success btn-sm" id="btn-exportar-excel">
                    <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                  </button>
                </div>
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
                    <th class="header-bg-success" colspan="3">Acción de anticipo</th>
                    <th class="header-bg-success" colspan="5">Factura de venta</th>
                    <th class="header-bg-success">DSCT DETRACC</th>
                    <th class="header-bg-success">SALDO DEUDA</th>
                    <th class="header-bg-primary" rowspan="2">ESTADO</th>
                    <th class="header-bg-primary" rowspan="2">N° VALORIZACIÓN</th>
                  </tr>
                  <tr>
                    <!-- Acción de anticipo subheaders -->
                    <th class="header-bg-primary">Aplicado al 100%</th>
                    <th class="header-bg-primary">Aplicado parcialmente</th>
                    <th class="header-bg-primary">LOTE</th>

                    <!-- Factura de venta subheaders -->
                    <th class="header-bg-primary">N° Factura Amortiza</th>
                    <th class="header-bg-primary">Fecha</th>
                    <th class="header-bg-primary">Importe Factura USD $</th>
                    <th class="header-bg-primary">Importe Amortiza Adelanto USD $</th>
                    <th class="header-bg-primary">Saldo Factura Amortiza</th>

                    <!-- DSCT DETRACC subheader -->
                    <th class="header-bg-primary">Saldo Neto Factura Amortiza</th>

                    <!-- SALDO DEUDA subheader -->
                    <th class="header-bg-primary">Importe USD $</th>
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
      let transaccionesData = [];
      const backendUrl = '<?php echo $backendUrl; ?>';
      const detallesModal = new bootstrap.Modal(document.getElementById('modalDetalles'));
      let selectedTransaccion = null;

      // Función para llamar al backend
      function f_callBackend(accion, data) {
        return $.post(backendUrl, {
          accion: accion,
          ...data
        }, 'json');
      }

      // Formato de moneda
      function formatCurrency(amount, showSymbol = true) {
        if (amount === null || amount === undefined) return '---';
        const num = parseFloat(amount);
        if (isNaN(num)) return '---';

        const formatted = num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return showSymbol ? `$ ${formatted}` : formatted;
      }

      // Formato de fecha
      function formatDate(dateString) {
        if (!dateString) return '---';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        return date.toLocaleDateString('es-ES');
      }

      // Renderizar tabla
      function renderTable(data) {
        const tbody = $('#tbl-transacciones');

        if (data.length === 0) {
          tbody.html(`
            <tr>
              <td colspan="12" class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 48px;"></i>
                <p class="mt-2">No se encontraron transacciones</p>
              </td>
            </tr>
          `);
          return;
        }

        let html = '';

        data.forEach((item, index) => {
          // Detectar si es una fila de cabecera/grupo (basado en accion_anticipo teniendo E001 y otros campos vacios/nulos si aplica)
          // La lógica original usaba highlight-yellow para item.accion_anticipo.includes('E001')
          // En el excel, hay filas que son solo el titulo del grupo "E001-41 | 15/07/2025 | $ 300,000.00"

          let isHeaderRow = false;
          // Asumimos que si no tiene porcentaje_aplicado y tiene accion_anticipo con E001, es header
          if (!item.porcentaje_aplicado && item.accion_anticipo && item.accion_anticipo.includes('E001')) {
            isHeaderRow = true;
          }

          if (isHeaderRow) {
            html += `
              <tr class="table-secondary fw-bold">
                <td colspan="12" class="text-center text-uppercase" style="background-color: #e2e3e5;">
                  ${item.accion_anticipo}
                  ${item.fecha ? ' | ' + formatDate(item.fecha) : ''}
                  ${item.importe_factura ? ' | ' + formatCurrency(item.importe_factura) : ''}
                </td>
              </tr>
             `;
          } else {
            // Determinar clases de fila
            let rowClass = 'btn-view-details'; // Hacemos toda la fila clickable
            let style = 'cursor: pointer;';

            // Colores especificos del excel
            // Aplicado 100% -> No parece tener color especial salvo el texto
            // Filas amarillas para items especificos
            if (item.porcentaje_aplicado === '100%') {
              // rowClass += ' highlight-green'; // Ajustar segun necesidad, excel no muestra verde claro en filas normales
            }

            // El excel muestra celdas amarillas en las columnas de importe amortiza y saldo si hay valores?
            // Replicamos lógica visual basica.

            const saldoDeudaClass = parseFloat(item.saldo_deuda) > 0 ? 'negative' :
              parseFloat(item.saldo_deuda) < 0 ? 'positive' : '';

            // Determinar badge de estado
            let estadoBadge = '';
            // Ajuste para coincidir con diseño excel (texto simple o badge)
            if (item.estado === 'Comprobante Pendiente') {
              estadoBadge = '<span class="text-danger fw-bold" style="font-size: 11px;">Comprobante<br>Pendiente</span>';
            } else if (item.estado === 'Aprobado') {
              estadoBadge = '<span class="text-success fw-bold">Aprobado</span>';
            } else if (item.estado === 'Anulado') {
              estadoBadge = '<span class="text-muted">Anulado</span>';
            } else {
              estadoBadge = item.estado;
            }

            html += `
              <tr class="${rowClass}" data-index="${index}" style="${style}">
                <!-- Aplicado al 100% -->
                <td class="text-center fw-bold">${item.porcentaje_aplicado || ''}</td>
                
                <!-- Aplicado parcialmente -->
                <td class="currency-cell">${item.monto_aplicado ? formatCurrency(item.monto_aplicado) : ''}</td>
                
                <!-- LOTE -->
                <td class="text-center font-monospace small">${item.lote || ''}</td>
                
                <!-- N° Factura Amortiza -->
                <td class="text-center fw-bold">${item.factura_amortiza || ''}</td>
                
                <!-- Fecha -->
                <td class="text-center">${formatDate(item.fecha)}</td>
                
                <!-- Importe Factura USD -->
                <td class="currency-cell">${formatCurrency(item.importe_factura)}</td>
                
                <!-- Importe Amortiza Adelanto USD -->
                <td class="currency-cell bg-warning bg-opacity-25">${formatCurrency(item.importe_amortiza)}</td>
                
                <!-- Saldo Factura Amortiza -->
                <td class="currency-cell">${formatCurrency(item.saldo_factura_amortiza)}</td>
                
                <!-- Saldo Neto Factura Amortiza (DSCT DETRACC col) -->
                <td class="currency-cell">${formatCurrency(item.saldo_neto_factura_amortiza)}</td>
                
                <!-- Importe USD $ (SALDO DEUDA col) -->
                <td class="currency-cell ${saldoDeudaClass}">${formatCurrency(item.saldo_deuda)}</td>
                
                <!-- Estado -->
                <td class="text-center align-middle">${estadoBadge}</td>
                
                <!-- N° Valorización -->
                <td class="text-center fw-bold text-primary">${item.numero_valorizacion || ''}</td>
              </tr>
            `;
          }
        });

        tbody.html(html);
      }

      // Actualizar resumen
      function updateSummary(data) {
        const totalFacturas = data.length;
        const montoTotal = data.reduce((sum, item) => sum + parseFloat(item.importe_factura || 0), 0);
        const anticiposAplicados = data.filter(item => item.porcentaje_aplicado && item.porcentaje_aplicado !== '0%').length;
        const saldoPendiente = data.reduce((sum, item) => sum + Math.max(0, parseFloat(item.saldo_deuda || 0)), 0);

        $('#total-facturas').text(totalFacturas);
        $('#monto-total').text(formatCurrency(montoTotal));
        $('#anticipos-aplicados').text(anticiposAplicados);
        $('#saldo-pendiente').text(formatCurrency(saldoPendiente));
      }

      // Cargar datos iniciales
      function loadTransacciones() {
        const filters = {
          factura: $('#filter-factura').val(),
          factura_amortiza: $('#filter-factura-amortiza').val(),
          fecha_desde: $('#filter-fecha-desde').val(),
          fecha_hasta: $('#filter-fecha-hasta').val(),
          estado: $('#filter-estado').val()
        };

        $('#tbl-transacciones').html(`
          <tr>
            <td colspan="13" class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
              <p class="mt-2 text-muted">Cargando transacciones...</p>
            </td>
          </tr>
        `);

        f_callBackend('getTransaccionesAnticipos', filters)
          .done(function(r) {
            if (r.estado === 1 && r.data) {
              transaccionesData = r.data;

              updateSummary(transaccionesData);
              renderTable(transaccionesData);

              // Ocultar paginacion si existe
              $('#pagination-container').hide();
              $('#info-paginacion').text(`Mostrando ${transaccionesData.length} registros`);

            } else {
              alert("Error al cargar transacciones: " + (r.msg || "Error desconocido"));
            }
          })
          .fail(function() {
            alert("Error de conexión al cargar transacciones");
          });
      }

      $('#btn-aplicar-filtros').on('click', function() {
        loadTransacciones();
      });

      $('#btn-limpiar-filtros').on('click', function() {
        $('#filter-factura').val('');
        $('#filter-factura-amortiza').val('');
        $('#filter-fecha-desde').val('');
        $('#filter-fecha-hasta').val('');
        $('#filter-estado').val('');
        loadTransacciones();
      });

      $('#btn-exportar-excel').on('click', function() {
        // Aquí iría la lógica para exportar a Excel
        alert('Funcionalidad de exportación a Excel en desarrollo...');
      });

      $(document).on('click', '.btn-view-details', function() {
        const index = $(this).data('index');
        // El index ahora corresponde directamente a transaccionesData ya que no hay paginación
        selectedTransaccion = transaccionesData[index];

        if (selectedTransaccion) {
          // Llenar datos del modal
          $('#detalle-factura-venta').text(selectedTransaccion.accion_anticipo || '---');
          $('#detalle-factura-amortiza').text(selectedTransaccion.factura_amortiza || '---');
          $('#detalle-fecha').text(formatDate(selectedTransaccion.fecha));
          $('#detalle-valorizacion').text(selectedTransaccion.numero_valorizacion || '---');
          $('#detalle-importe-factura').text(formatCurrency(selectedTransaccion.importe_factura));
          $('#detalle-importe-amortiza').text(formatCurrency(selectedTransaccion.importe_amortiza));
          $('#detalle-saldo-deuda').text(formatCurrency(selectedTransaccion.saldo_deuda));
          $('#detalle-estado').html(
            selectedTransaccion.estado === 'Comprobante Pendiente' ?
            '<span class="status-badge status-pendiente">PENDIENTE</span>' :
            selectedTransaccion.estado === 'Aprobado' ?
            '<span class="status-badge status-aprobado">APROBADO</span>' :
            '<span class="status-badge status-anulado">ANULADO</span>'
          );

          // Aquí cargaríamos los lotes desde el backend
          // Por ahora mostramos un mensaje
          $('#tbl-lotes-detalle tbody').html(`
            <tr>
              <td colspan="5" class="text-center">Cargando lotes...</td>
            </tr>
          `);

          detallesModal.show();

          // Simular carga de lotes
          setTimeout(() => {
            $('#tbl-lotes-detalle tbody').html(`
              <tr>
                <td>GEL-25-1609</td>
                <td>Mineral de oro</td>
                <td>1,250.50</td>
                <td>15.8</td>
                <td>$ 45,250.00</td>
              </tr>
              <tr>
                <td>GEL-25-1810</td>
                <td>Mineral de plata</td>
                <td>2,150.00</td>
                <td>8.5</td>
                <td>$ 32,150.00</td>
              </tr>
            `);
          }, 500);
        }
      });

      $('#btn-imprimir-comprobante').on('click', function() {
        if (selectedTransaccion) {
          window.open(`imprimir_comprobante.php?id=${selectedTransaccion.id}`, '_blank');
        }
      });

      // Inicialización
      function f_Init() {
        f_GetMenuPrincipal();
        $("#nv_titulo").html('| Transacciones Anticipos');
        loadTransacciones();
      }

      f_Init();
    });
  </script>

</body>

</html>