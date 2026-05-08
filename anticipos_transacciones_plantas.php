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

  <title><?php echo $nom_app; ?> | Transacciones de Anticipos Planta</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <link rel="stylesheet" href="<?php echo $url_lims; ?>/global/styles.css">

  <style>
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

    .header-bg-primary   { background-color: var(--color-primary)   !important; color: white; font-weight: 600; vertical-align: middle; }
    .header-bg-secondary { background-color: var(--color-secondary) !important; color: white; font-weight: 600; vertical-align: middle; }
    .header-bg-success   { background-color: var(--color-success)   !important; color: white; font-weight: 600; vertical-align: middle; }
    .header-bg-warning   { background-color: var(--color-warning)   !important; color: white; font-weight: 600; vertical-align: middle; }
    .header-bg-danger    { background-color: var(--color-danger)    !important; color: white; font-weight: 600; vertical-align: middle; }
    .header-bg-info      { background-color: var(--color-info)      !important; color: white; font-weight: 600; vertical-align: middle; }

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
    .table-custom tbody tr:hover { background-color: rgba(52,152,219,0.05); }
    .table-custom tbody tr:last-child td { border-bottom: none; }

    .filter-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      border: 1px solid var(--color-border);
    }

    .currency-cell {
      text-align: right;
      font-family: 'Courier New', monospace;
      font-weight: 500;
    }
  </style>
</head>

<body class="bg-light" style="zoom: 80%;">
  <div class="container-fluid">
    <div class="row">
      <?php echo $navbar_maintop; ?>

      <!-- Modal Menú -->
      <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-left" style="margin-top:0px!important;margin-left:0px!important;">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background:#25476a;color:white;border-top:solid #EFB810 3px;padding:0px!important;">
              <ul class="list-unstyled">
                <div id="div_menu1"></div>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenido Principal -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:10px;padding-left:15px;padding-right:15px;">

        <!-- Filtros -->
        <div class="filter-card pt-3 pb-3">
          <h6 class="mb-2 text-secondary"><i class="bi bi-funnel"></i> Filtros de Búsqueda</h6>
          <div class="row g-2">

            <div class="col-md-3">
              <label class="form-label small mb-1">Planta</label>
              <select class="form-select form-select-sm" id="filter_planta" style="width:100%;"></select>
            </div>

            <div class="col-md-2">
              <label class="form-label small mb-1">Factura Anticipo</label>
              <input type="text" class="form-control form-control-sm" id="filter_factura_anticipo" placeholder="Ej: F001-123">
            </div>

            <div class="col-md-2">
              <label class="form-label small mb-1">Factura Venta</label>
              <input type="text" class="form-control form-control-sm" id="filter_factura_venta" placeholder="Ej: E001-42">
            </div>

            <div class="col-md-3">
              <label class="form-label small mb-1">Rango Fechas (Factura Venta)</label>
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
                <option value="E">En espera</option>
                <option value="P">En proceso de pago</option>
                <option value="A">Pagado - Mixto</option>
                <option value="B">Pagado - Banco</option>
                <option value="C">Pagado - Anticipos</option>
                <option value="X">Anulada</option>
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

        <!-- Tabla -->
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
                    <th class="header-bg-primary text-center" rowspan="2">VALORIZACIONES</th>
                  </tr>
                  <tr>
                    <th class="header-bg-primary text-center small">Factura</th>
                    <th class="header-bg-primary text-center small">Fecha</th>
                    <th class="header-bg-primary text-center small">Importe</th>

                    <th class="header-bg-success text-center small">Aplicado 100%</th>
                    <th class="header-bg-success text-center small">Aplicado parcialmente</th>
                    <th class="header-bg-success text-center small">Lote</th>

                    <th class="header-bg-secondary text-center small">N° Factura Venta</th>
                    <th class="header-bg-secondary text-center small">Fecha</th>
                    <th class="header-bg-secondary text-center small">Importe Factura $</th>
                    <th class="header-bg-secondary text-center small">Importe Amortiza Adelanto $</th>

                    <th class="header-bg-warning text-center small">Saldo Factura Amortiza</th>
                    <th class="header-bg-info text-center small">Saldo Neto Factura</th>
                    <th class="header-bg-danger text-center small">Importe $</th>
                  </tr>
                </thead>
                <tbody id="tbl-transacciones">
                  <tr>
                    <td colspan="15" class="text-center py-5">
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

      </div><!-- /col -->
    </div><!-- /row -->
  </div><!-- /container -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <?php include('global/auxiliares_js.php'); ?>


  <script src="anticipos_transacciones_plantas.js?v=<?php echo time(); ?>"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      initAnticiposPlanta('<?php echo $backendUrl; ?>');
    });
  </script>

</body>
</html>