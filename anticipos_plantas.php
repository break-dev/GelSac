<?php
session_start();
include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');

if (!isset($_SESSION["Id"])) {
  header('Location: index.php');
  exit;
}

$backendUrl = 'apis/backend.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo $favicon; ?>" type="image/png" />

  <title><?php echo $nom_app; ?> | Anticipos a Plantas</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <link rel="stylesheet" href="<?php echo $url_lims; ?>/global/styles.css">

  <style>
    .header-bg-planta {
      background-color: #1a6b3c !important;
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-anticipo {
      background-color: #e67e22 !important;
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    .planta-row:hover {
      background-color: #d4edda;
      cursor: pointer;
    }

    .planta-row.selected {
      background-color: #a8d5b9;
      font-weight: bold;
    }

    /* Badge de evidencias */
    .badge-evidencia {
      font-size: 11px;
      cursor: pointer;
    }

    #div_loading {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .35);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    #div_loading.show {
      display: flex;
    }

    #div_loading .spinner-border {
      width: 3rem;
      height: 3rem;
      border-width: .35em;
    }
  </style>
</head>

<body class="bg-light" style="zoom: 80%;">
  <div class="container-fluid">
    <div class="row">
      <?php echo $navbar_maintop; ?>

      <!-- Modal Menú -->
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

          <!-- Resumen General -->
          <div class="row bg-white shadow-sm p-3 mb-3 rounded">
            <h5><i class="bi bi-bank"></i> Resumen General de Anticipos a Plantas</h5>
            <hr style="border-color: #D9D9D9; margin-top: 2px;" />
            <div class="d-flex mb-2" style="font-size: 16px;">
              <span class="me-4">
                Total Anticipos: <strong id="total_anticipos" class="text-primary">0</strong>
              </span>
              <span class="me-4 border-start ps-3">
                Con Saldo: <strong id="anticipos_con_saldo" class="text-success">0</strong>
              </span>
              <span class="me-4 border-start ps-3">
                Sin Saldo: <strong id="anticipos_sin_saldo" class="text-danger">0</strong>
              </span>
              <span class="me-4 border-start ps-3 d-none" id="span_saldo_planta">
                Saldo disponible (<span id="label_planta_saldo" class="text-success fw-semibold"></span>):
                <strong id="total_saldo_planta" class="text-success">USD 0.00</strong>
              </span>
              <button class="btn btn-success btn-sm ms-auto" id="btn_open_new_anticipo_modal">
                <i class="bi bi-plus-circle me-1"></i> Registrar Nuevo Anticipo
              </button>
            </div>
          </div>

          <!-- Tabla de Plantas -->
          <div class="col-md-5">
            <div class="bg-white shadow-sm p-3 rounded">
              <h5><i class="bi bi-building"></i> Plantas con Anticipos</h5>
              <hr style="border-color: #D9D9D9; margin-top: 5px; margin-bottom: 10px;" />
              <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr style="font-size: 13px;">
                      <th class="header-bg-planta text-center" rowspan="2">Planta</th>
                      <th class="header-bg-planta text-center" rowspan="2">RUC</th>
                      <th class="header-bg-anticipo text-center" colspan="3">Anticipos</th>
                      <th class="header-bg-planta text-center" rowspan="2">Acciones</th>
                    </tr>
                    <tr style="font-size: 12px;">
                      <th class="header-bg-anticipo text-center">Total</th>
                      <th class="header-bg-anticipo text-center">Con Saldo</th>
                      <th class="header-bg-anticipo text-center">Sin Saldo</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_plantas_con_anticipos" style="font-size: 13px;">
                    <tr>
                      <td colspan="6" class="text-center">Cargando plantas...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Tabla de Anticipos de la Planta seleccionada -->
          <div class="col-md-7">
            <div class="bg-white shadow-sm p-3 rounded">
              <h5><i class="bi bi-wallet"></i> Anticipos de la Planta: <span id="planta_seleccionada_desc"
                  class="text-success">---</span></h5>
              <hr style="border-color: #D9D9D9; margin-top: 5px; margin-bottom: 10px;" />
              <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr style="font-size: 13px;">
                      <th class="header-bg-anticipo text-center">Factura</th>
                      <th class="header-bg-anticipo text-center">Saldo Inicial</th>
                      <th class="header-bg-anticipo text-center">Saldo Actual</th>
                      <th class="header-bg-anticipo text-center">Transacciones</th>
                      <th class="header-bg-anticipo text-center">Evidencias</th>
                      <th class="header-bg-anticipo text-center">Fecha</th>
                      <th class="header-bg-anticipo text-center">Estado</th>
                      <th class="header-bg-anticipo text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_anticipos_planta" style="font-size: 13px;">
                    <tr>
                      <td colspan="8" class="text-center">Seleccione una planta en el panel izquierdo.</td>
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

  <!-- Modal: Nuevo Anticipo -->
  <div class="modal fade" id="modal_nuevo_anticipo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" style="position: fixed; top: 30%; left: 50%; transform: translate(-50%, -55%);">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="bi bi-cash-coin"></i> Registrar Nuevo Anticipo a Planta</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="reg_planta" class="form-label">Planta <span class="text-danger">*</span></label>
            <select id="reg_planta" class="form-select"></select>
            <div class="invalid-feedback d-block" id="err_planta"></div>
          </div>
          <div class="mb-1">
            <label class="form-label">Factura <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="text" class="form-control text-uppercase" id="reg_serie_factura" placeholder="FA01"
                maxlength="10" style="max-width:110px;">
              <span class="input-group-text">-</span>
              <input type="text" class="form-control" id="reg_numero_factura" placeholder="00001234" maxlength="20">
            </div>
            <div class="d-flex gap-3 mt-1">
              <div class="invalid-feedback d-block" style="min-width:110px;"></div>
              <div class="invalid-feedback d-block"></div>
            </div>
          </div>
          <div class="mb-3">
            <label for="reg_saldo_inicial" class="form-label">Saldo Inicial (USD) <span
                class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">USD</span>
              <input type="number" step="0.01" min="0.01" class="form-control" id="reg_saldo_inicial"
                placeholder="10000.00">
            </div>
            <div class="invalid-feedback d-block"></div>
          </div>
          <div class="mb-3">
            <label for="reg_evidencias" class="form-label">Evidencias <small class="text-muted">(archivos,
                opcional)</small></label>
            <input type="file" class="form-control" id="reg_evidencias" name="archivos[]" multiple>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i>
            Cancelar</button>
          <button type="button" class="btn btn-success" id="btn_guardar_anticipo"><i class="bi bi-save"></i> Grabar
            Anticipo</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Ver Transacciones -->
  <div class="modal fade" id="modal_ver_transacciones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="position: fixed; top: 30%; left: 50%; transform: translate(-50%, -55%);">
      <div class="modal-content">
        <div class="modal-header header-bg-anticipo text-white">
          <h5 class="modal-title"><i class="bi bi-clipboard-data"></i> Transacciones del Anticipo: <span
              id="modal_anticipo_factura"></span></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">
            Saldo Inicial: <strong id="modal_saldo_inicial" class="text-primary">---</strong>
            &nbsp;|&nbsp;
            Saldo Actual: <strong id="modal_saldo_actual" class="text-success">---</strong>
          </p>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="font-size: 13px;">
                  <th class="header-bg-planta text-center">Factura Venta</th>
                  <th class="header-bg-anticipo text-center">Saldo Anterior (USD)</th>
                  <th class="header-bg-anticipo text-center" style="font-size:15px;">Monto Retirado (USD)</th>
                  <th class="header-bg-anticipo text-center">Saldo Restante (USD)</th>
                  <th class="header-bg-planta text-center">Estado</th>
                  <th class="header-bg-planta text-center">Fecha</th>
                </tr>
              </thead>
              <tbody id="tbl_transacciones" style="font-size: 13px;">
                <tr>
                  <td colspan="7" class="text-center">Sin transacciones.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: GestiÃ³n de Evidencias -->
  <div class="modal fade" id="modal_evidencias" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="background:#25476a; color:#fff;">
          <h5 class="modal-title"><i class="bi bi-files me-2"></i>Evidencias / Adjuntos</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label-sm" style="font-size: 12px; color: #6c757d;">Subir nuevo archivo</label>
            <div class="input-group input-group-sm">
              <input type="file" id="ev_nuevo_archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
              <button class="btn btn-primary" type="button" onclick="f_SubirEvidencia();">
                <i class="bi bi-upload"></i>
              </button>
            </div>
          </div>
          <hr>
          <div id="div_evidencias_list" class="list-group list-group-flush">
            <!-- DinÃ¡mico -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div id="div_loading">
    <div class="spinner-border text-light" role="status"></div>
  </div>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <?php include('global/auxiliares_js.php'); ?>

  <script>
    const BACKEND_URL = '<?php echo $backendUrl; ?>';
  </script>
  <script src="anticipos_plantas.js?v=2"></script>

</body>

</html>