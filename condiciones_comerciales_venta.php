<?php

  session_start();

  include('cnx/cnx.php');
  include('global/variables.php');
  include('global/auxiliares.php');

  if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/png"/>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <!-- Select2 -->
    <link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

    <!-- Estilos globales -->
    <link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

    <!-- Estilos propios de esta vista -->
    <style>
      :root {
        --primary:    #25476a;
        --accent:     #EFB810;
        --brown:      #816951;
        --success:    #44803F;
        --danger:     #E63946;
        --warning:    #E6A50D;
        --light-bg:   #F4F6F9;
        --card-bg:    #ffffff;
        --border:     #E0E4EA;
        --text-main:  #2d3748;
        --text-muted: #718096;
        --radius:     10px;
        --shadow:     0 2px 12px rgba(37,71,106,.08);
      }

      body {
        background: var(--light-bg);
        color: var(--text-main);
        font-family: 'Segoe UI', system-ui, sans-serif;
        font-size: 14px;
      }

      /* ── Tarjetas ── */
      .cc-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
      }

      .cc-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px 10px;
        border-bottom: 2px solid var(--border);
        background: linear-gradient(135deg, #fff 60%, #f8f9fc 100%);
      }

      .cc-card-header h6 {
        margin: 0;
        font-weight: 700;
        font-size: 14px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 7px;
      }

      .cc-card-header h6 i {
        color: var(--accent);
        font-size: 16px;
      }

      .cc-card-body {
        padding: 16px;
        overflow-x: auto;
      }

      /* ── Tablas ── */
      .cc-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
      }

      .cc-table thead th {
        background: var(--brown);
        color: #fff;
        text-align: center;
        padding: 9px 10px;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid rgba(255,255,255,.15);
      }

      .cc-table thead th:first-child { border-radius: 8px 0 0 0; }
      .cc-table thead th:last-child  { border-radius: 0 8px 0 0; }

      .cc-table tbody tr {
        transition: background .15s;
        cursor: pointer;
      }

      .cc-table tbody tr:hover { background: #EEF4FF; }

      .cc-table tbody td {
        padding: 8px 10px;
        border: 1px solid var(--border);
        vertical-align: middle;
        text-align: center;
      }

      .cc-table tbody tr.selected-row { background: #FFF587 !important; }

      /* ── Badges de estado ── */
      .badge-activo   { background: var(--success); color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
      .badge-inactivo { background: var(--warning);  color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }

      /* ── Acciones inline ── */
      .action-links { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
      .action-links a { font-size: 12px; text-decoration: none; display: flex; align-items: center; gap: 4px; }
      .action-links a:hover { text-decoration: underline; }

      /* ── Spinner de carga ── */
      .loading-badge {
        display: none;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: var(--text-muted);
        font-style: italic;
      }

      .loading-badge img { width: 18px; }

      /* ── Botón Nueva CC ── */
      .btn-nueva-cc {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background .2s, transform .1s;
      }

      .btn-nueva-cc:hover { background: #1b3654; color: #fff; transform: translateY(-1px); }

      /* ── Título proveedor seleccionado ── */
      #lbl_tituloplanta {
        color: var(--primary);
        font-weight: 700;
        margin-left: 6px;
      }

      /* ── Modal ── */
      .modal-header {
        background: var(--primary);
        color: #fff;
        border-radius: var(--radius) var(--radius) 0 0;
      }

      .modal-header .btn-close { filter: invert(1); }

      .modal-title { font-size: 15px; font-weight: 700; }

      .form-label-cc {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 4px;
      }

      .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 .2rem rgba(37,71,106,.2);
      }

      /* ── Scrollable list ── */
      .list-scroll {
        max-height: 680px;
        overflow-y: auto;
      }

      /* ── Separador ── */
      .cc-divider {
        border: 0;
        border-top: 1px solid var(--border);
        margin: 8px 0 14px;
      }
    </style>

    <title><?php echo $nom_app; ?> | Condiciones Comerciales Venta</title>
  </head>

  <body onload="f_Init();" style="zoom: 80%;">
    <div class="container-fluid">
      <div class="row">

        <!-- Navbar -->
        <?php echo $navbar_maintop; ?>

        <!-- Modal Menú Lateral -->
        <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-left" style="margin: 0;">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0;">
                <ul class="list-unstyled">
                  <div id="div_menu1"></div>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-12 mt-3">
          <div class="row g-3">

            <!-- ── Columna: Lista de Plantas ── -->
            <div class="col-md-4 col-sm-12">
              <div class="cc-card h-100">
                <div class="cc-card-header">
                  <h6><i class="bi bi-building"></i> Lista de Plantas</h6>
                  <div id="wt_plantas" class="loading-badge">
                    <img src="<?php echo $img_waiting ?>"> <span>Cargando...</span>
                  </div>
                </div>
                <div class="cc-card-body list-scroll p-2">
                  <table class="cc-table">
                    <thead>
                      <tr>
                        <th style="width:36px;">N°</th>
                        <th>RUC</th>
                        <th>Descripción</th>
                      </tr>
                    </thead>
                    <tbody id="tbl_plantas"></tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- ── Columna: Condiciones Comerciales ── -->
            <div class="col-md-8 col-sm-12">
              <div class="cc-card">
                <div class="cc-card-header">
                  <h6>
                    <i class="bi bi-file-earmark-text"></i>
                    Condiciones Comerciales de:
                    <span id="lbl_tituloplanta"></span>
                  </h6>
                  <div style="display:flex; align-items:center; gap:12px;">
                    <div id="wt_condiciones_comerciales" class="loading-badge">
                      <img src="<?php echo $img_waiting ?>"> <span>Cargando...</span>
                    </div>
                    <button class="btn-nueva-cc" type="button" onclick="f_AdminCondicionComercial('x');">
                      <i class="bi bi-plus-lg"></i> Nueva C.C.
                    </button>
                  </div>
                </div>

                <div class="cc-card-body">
                  <table class="cc-table">
                    <thead>
                      <tr>
                        <th style="width:36px;">N°</th>
                        <th>Ley Auoz Inicio</th>
                        <th>Ley Auoz Fin</th>
                        <th>Maquila</th>
                        <th>Recuperación</th>
                        <th>Consumo</th>
                        <th>Riesgo Comercial</th>
                        <th>Estado</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody id="tbl_condiciones_comerciales"></tbody>
                  </table>
                </div>

              </div>
            </div>

          </div><!-- /row -->
        </div><!-- /col-12 -->

        <!-- ── Modal: Agregar / Editar Condición Comercial ── -->
        <div class="modal fade" id="modal_addcondicioncomercial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addcondicioncomercialLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: var(--radius); overflow:hidden;">
              <div class="modal-header">
                <h5 class="modal-title" id="modal_addcondicioncomercialLabel">Nueva Condición Comercial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body p-4">
                <div class="row g-3">

                  <div class="col-md-6">
                    <label class="form-label-cc">Ley Auoz Inicio <span class="text-danger">*</span></label>
                    <input id="condicion_comercial_ley_auoz_inicio" type="number" class="form-control text-center" placeholder="0.00">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label-cc">Ley Auoz Fin <span class="text-danger">*</span></label>
                    <input id="condicion_comercial_ley_auoz_fin" type="number" class="form-control text-center" placeholder="0.00">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label-cc">Maquila</label>
                    <input id="condicion_comercial_maquila" type="number" class="form-control text-center" placeholder="0.00">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label-cc">Recuperación</label>
                    <input id="condicion_comercial_recuperacion" type="number" class="form-control text-center" placeholder="0.00">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label-cc">Consumo</label>
                    <input id="condicion_comercial_consumo" type="number" class="form-control text-center" placeholder="0.00">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label-cc">Riesgo Comercial</label>
                    <input id="condicion_comercial_riesgo_comercial" type="number" class="form-control text-center" placeholder="0.00">
                  </div>

                </div>
              </div>

              <input id="hd_id_condicion_comercial" type="hidden">
              <input id="hd_modograbar" type="hidden">

              <div class="modal-footer" style="background:#f8f9fc;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                  <i class="bi bi-x-circle me-1"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="f_GrabarCondicionComercial();">
                  <i class="bi bi-save me-1"></i>Grabar
                </button>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /row principal -->
    </div><!-- /container -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="libs/select2/dist/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

    <!-- Auxiliares globales -->
    <?php include('global/auxiliares_js.php'); ?>

    <!-- Lógica de la vista -->
    <script src="condiciones_comerciales_venta.js"></script>
  </body>
</html>