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
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo $favicon; ?>" type="image/png"/>
  <title><?php echo $nom_app; ?> | Valorización de Venta</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
  <!-- Estilos globales -->
  <link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

  <style>
    :root {
      --primary:     #25476a;
      --primary-lt:  #eef3f8;
      --accent:      #EFB810;
      --brown:       #816951;
      --brown-lt:    #f5f0eb;
      --success:     #2e7d32;
      --danger:      #c62828;
      --warning:     #e65100;
      --info:        #0277bd;
      --border:      #dde3ec;
      --bg:          #f0f2f5;
      --card:        #ffffff;
      --text:        #1a2535;
      --muted:       #6b7a8d;
      --radius:      10px;
      --shadow:      0 2px 16px rgba(37,71,106,.09);
      --shadow-lg:   0 6px 32px rgba(37,71,106,.14);
      --transition:  .2s cubic-bezier(.4,0,.2,1);
    }

    * { box-sizing: border-box; }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Segoe UI', system-ui, sans-serif;
      font-size: 13px;
    }

    /* ── Layout ── */
    .main-wrapper { padding: 12px 16px 24px; }

    /* ── Cards ── */
    .vv-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .vv-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 11px 18px;
      background: linear-gradient(90deg, var(--primary) 0%, #2d5a84 100%);
      color: #fff;
    }

    .vv-card-header .title {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 700;
      font-size: 13px;
      letter-spacing: .2px;
    }

    .vv-card-header .title i { font-size: 15px; color: var(--accent); }

    .vv-card-header .subtitle {
      font-size: 12px;
      color: rgba(255,255,255,.75);
      margin-left: 4px;
    }

    .vv-card-body { padding: 14px; overflow-x: auto; }

    /* ── Section title (divider band) ── */
    .section-band {
      background: linear-gradient(90deg, var(--brown) 0%, #9c7d60 100%);
      color: #fff;
      font-weight: 700;
      font-size: 12px;
      letter-spacing: .5px;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: 7px;
    }

    /* ── Tables ── */
    .vv-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 12px;
    }

    .vv-table thead th {
      background: var(--brown);
      color: #fff;
      text-align: center;
      padding: 8px 8px;
      font-weight: 600;
      white-space: nowrap;
      border: 1px solid rgba(255,255,255,.18);
      position: sticky;
      top: 0;
      z-index: 2;
    }

    .vv-table thead th:first-child { border-radius: 7px 0 0 0; }
    .vv-table thead th:last-child  { border-radius: 0 7px 0 0; }

    .vv-table tbody tr { transition: background var(--transition); cursor: pointer; }
    .vv-table tbody tr:hover { background: #eef3f8; }
    .vv-table tbody tr.row-selected { background: #fffde7 !important; outline: 2px solid var(--accent); outline-offset: -1px; }

    .vv-table tbody td {
      padding: 7px 8px;
      border: 1px solid var(--border);
      vertical-align: middle;
      text-align: center;
    }

    /* ── Badges ── */
    .badge-estado {
      display: inline-block;
      padding: 3px 11px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .3px;
    }

    .badge-activo   { background: #e8f5e9; color: var(--success); border: 1px solid #a5d6a7; }
    .badge-inactivo { background: #fff3e0; color: var(--warning);  border: 1px solid #ffcc80; }
    .badge-anulado  { background: #ffebee; color: var(--danger);   border: 1px solid #ef9a9a; }

    /* ── Buttons ── */
    .btn-vv-primary {
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 7px;
      padding: 6px 14px;
      font-size: 12px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: background var(--transition), transform var(--transition);
      cursor: pointer;
    }
    .btn-vv-primary:hover { background: #1b3654; color: #fff; transform: translateY(-1px); }

    .btn-vv-accent {
      background: var(--accent);
      color: #1a2535;
      border: none;
      border-radius: 7px;
      padding: 6px 14px;
      font-size: 12px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: filter var(--transition);
      cursor: pointer;
    }
    .btn-vv-accent:hover { filter: brightness(.92); }

    /* ── Action links ── */
    .action-col { display: flex; flex-direction: column; gap: 3px; white-space: nowrap; }
    .action-col a { font-size: 11px; text-decoration: none; display: flex; align-items: center; gap: 3px; }
    .action-col a:hover { text-decoration: underline; }

    /* ── Loading badge ── */
    .loading-badge {
      display: none;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: rgba(255,255,255,.8);
      font-style: italic;
    }
    .loading-badge img { width: 18px; }

    /* ── Modal overrides ── */
    .modal-header-vv {
      background: linear-gradient(90deg, var(--primary) 0%, #2d5a84 100%);
      color: #fff;
      border-radius: 10px 10px 0 0;
    }
    .modal-header-vv .btn-close { filter: invert(1); }
    .modal-header-vv .modal-title { font-size: 14px; font-weight: 700; }

    .modal-xxl { max-width: 96% !important; }

    /* ── Form labels ── */
    .form-label-vv {
      font-size: 12px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 3px;
    }

    .form-control-vv {
      font-size: 13px;
      border-radius: 6px;
      border: 1px solid var(--border);
      padding: 5px 10px;
    }
    .form-control-vv:focus { border-color: var(--primary); box-shadow: 0 0 0 .2rem rgba(37,71,106,.15); outline: none; }

    /* ── Readonly info field ── */
    .field-readonly {
      background: #f4f6f9;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 5px 10px;
      font-size: 13px;
      font-weight: 600;
      color: var(--primary);
      text-align: center;
    }

    /* ── Info panel ── */
    .info-panel {
      background: var(--primary-lt);
      border: 1px solid #c4d4e4;
      border-radius: 8px;
      padding: 10px 14px;
    }

    /* ── Total row ── */
    .total-row td {
      background: linear-gradient(90deg, var(--brown) 0%, #9c7d60 100%) !important;
      color: #fff !important;
      font-weight: 700 !important;
    }

    /* ── Toggle panel button ── */
    #btn_toggle_panel {
      background: rgba(255,255,255,.12);
      border: 1px solid rgba(255,255,255,.25);
      color: #fff;
      border-radius: 6px;
      padding: 3px 8px;
      font-size: 12px;
      cursor: pointer;
      transition: background var(--transition);
    }
    #btn_toggle_panel:hover { background: rgba(255,255,255,.22); }

    /* ── Scroll areas ── */
    .list-scroll { max-height: 72vh; overflow-y: auto; }

    /* ── Separador visual ── */
    .vv-divider { border: 0; border-top: 1px solid var(--border); margin: 10px 0; }

    /* ── Elemento pill ── */
    .pill-oro   { background: #fff8e1; color: #e65100; border: 1px solid #ffcc02; border-radius: 20px; padding: 2px 10px; font-weight: 700; font-size: 11px; }
    .pill-plata { background: #e8eaf6; color: #283593; border: 1px solid #9fa8da; border-radius: 20px; padding: 2px 10px; font-weight: 700; font-size: 11px; }

    /* ── Disponibilidad en tabla de lotes ── */
    .habilitado   { color: var(--success); font-size: 15px; }
    .deshabilitado { color: #bdbdbd; font-size: 15px; }

    /* ── Sin valor comercial ── */
    .sin-vc-badge {
      background: var(--danger);
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 5px;
    }

    /* ── Correlativo chip ── */
    .corr-chip {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: var(--primary-lt);
      color: var(--primary);
      font-weight: 700;
      font-size: 12px;
      padding: 2px 10px;
      border-radius: 20px;
      border: 1px solid #c4d4e4;
    }

    /* ── Panel transition ── */
    #div_venta_lista, #div_venta_detalle { transition: all .3s ease; }
  </style>

  <script>
    const url_api = "apis/backend.php";
  </script>
</head>

<body onload="f_Init();" style="zoom:80%;">
<div class="container-fluid">
<div class="row">

  <!-- Navbar -->
  <?php echo $navbar_maintop; ?>

  <!-- Modal Menú Lateral -->
  <div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-left" style="margin:0;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Menú de Opciones</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="background:#25476a; color:#fff; border-top:3px solid #EFB810; padding:0;">
          <ul class="list-unstyled"><div id="div_menu1"></div></ul>
        </div>
      </div>
    </div>
  </div>

  <!-- ══════════════════════════════════════════════
       CONTENIDO PRINCIPAL
  ══════════════════════════════════════════════ -->
  <div class="col-12 main-wrapper">
    <div class="row g-3">

      <!-- ── PANEL IZQUIERDO: Lista de Valorizaciones ── -->
      <div id="div_venta_lista" class="col-md-5 col-sm-12">
        <div class="vv-card h-100">
          <div class="vv-card-header">
            <div class="title">
              <i class="bi bi-receipt"></i>
              Valorizaciones de Venta
              <span id="lbl_planta_filtro" class="subtitle"></span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
              <div id="wt_valorizaciones" class="loading-badge">
                <img src="<?php echo $img_waiting?>"> <span>Cargando...</span>
              </div>
              <button class="btn-vv-accent" onclick="f_AdminValorizacion('x');">
                <i class="bi bi-plus-lg"></i> Nueva
              </button>
            </div>
          </div>

          <!-- Selector de planta -->
          <div style="padding:10px 14px 0; display:flex; align-items:center; gap:8px;">
            <label class="form-label-vv mb-0" style="white-space:nowrap;"><i class="bi bi-building me-1"></i>Planta:</label>
            <select id="cmb_planta_filtro" class="form-control form-control-vv" style="max-width:320px;" onchange="f_LoadValorizaciones();">
              <option value="">[Todas las plantas]</option>
            </select>
          </div>

          <div class="vv-card-body list-scroll pt-2">
            <table class="vv-table">
              <thead>
                <tr>
                  <th style="width:32px;">#</th>
                  <th>N° Valor.</th>
                  <th>Cód. Valorización</th>
                  <th>Planta</th>
                  <th>Registrado</th>
                  <th>Estado</th>
                  <th >Acción</th>
                </tr>
              </thead>
              <tbody id="tbl_valorizaciones"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ── PANEL DERECHO: Detalle ── -->
      <div id="div_venta_detalle" class="col-md-7 col-sm-12">
        <div class="vv-card">
          <div class="vv-card-header">
            <div class="title">
              <button id="btn_toggle_panel" onclick="f_TogglePanel();" title="Expandir/contraer">
                <i class="bi bi-arrows-angle-expand"></i>
              </button>
              <i class="bi bi-list-columns-reverse"></i>
              Detalle:
              <span id="lbl_titulo_detalle" class="subtitle"></span>
            </div>
            <div id="wt_detalle" class="loading-badge">
              <img src="<?php echo $img_waiting?>"> <span>Cargando...</span>
            </div>
          </div>

          <div class="vv-card-body" style="overflow-x:auto;">
            <table class="vv-table" style="min-width:900px;">
              <thead>
                <tr>
                  <th>Elemento</th>
                  <th>Cód. Interno</th>
                  <th>Cód. Cliente</th>
                  <th>G.R.T.</th>
                  <th>Peso Seco (TM)</th>
                  <th>Ley Oro</th>
                  <th>Ley Plata</th>
                  <th>Rec. %</th>
                  <th>Inter ($/oz)</th>
                  <th>Des. Inter</th>
                  <th>Maquila</th>
                  <th>Consumo</th>
                  <th>Factor</th>
                  <th>P/TN</th>
                  <th>Total</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody id="tbl_detalle_valorizacion"></tbody>
            </table>
          </div>
        </div>
      </div>

    </div><!-- /row -->
  </div><!-- /main-wrapper -->

  <!-- ══════════════════════════════════════════════
       MODAL: NUEVA / EDITAR VALORIZACIÓN
  ══════════════════════════════════════════════ -->
  <div class="modal fade" id="modal_valorizacion" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xxl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content" style="border-radius:10px; overflow:hidden;">

        <div class="modal-header modal-header-vv">
          <h5 class="modal-title" id="modal_valorizacionLabel">Nueva Valorización de Venta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="padding:18px 20px 10px;">

          <!-- Fila superior: Planta -->
          <div class="row g-3 mb-3">
            <div class="col-md-3">
              <label class="form-label-vv">Planta Destino <span class="text-danger">*</span></label>
              <select id="cmb_planta_modal" class="form-control form-control-vv"
                onchange="f_OnPlantaModalChange();">
                <option value="">[Seleccione una planta]</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label-vv">Cód. Valorización</label>
              <input type="text" id="txt_codigo_valorizacion" class="form-control form-control-vv" placeholder="VNT-XXXX" maxlength="20">
            </div>
            <div class="col-md-4">
              <label class="form-label-vv">RUC Planta</label>
              <div class="field-readonly" id="lbl_ruc_planta">—</div>
            </div>
            <div class="col-md-3">
              <label class="form-label-vv">Evidencias / Documentos</label>
              <input id="file_evidencias" type="file" class="form-control form-control-vv" multiple>
              <div id="div_evidencias_lista" class="mt-2" style="font-size:11px; max-height:60px; overflow-y:auto;"></div>
            </div>
          </div>

          <!-- Sección detalle -->
          <div class="row align-items-center mb-2">
            <div class="col-md-10">
              <div class="section-band">
                <i class="bi bi-table"></i> Detalle de Lotes a Valorizar
              </div>
            </div>
            <div class="col-md-2 text-end">
              <button type="button" class="btn-vv-accent w-100" onclick="f_AdminLote('x');">
                <i class="bi bi-plus-circle"></i> Agregar Lote
              </button>
            </div>
          </div>

          <div style="overflow-x:auto;">
            <table class="vv-table" style="min-width:1100px;" id="tbl_lotes_modal">
              <thead>
                <tr>
                  <th>Elemento</th>
                  <th>Cód. Interno</th>
                  <th>Cód. Cliente</th>
                  <th>G.R.T.</th>
                  <th>Peso Húmedo</th>
                  <th>Humedad %</th>
                  <th>Peso Seco</th>
                  <th>Ley Oro</th>
                  <th>Ley Plata</th>
                  <th>Rec. %</th>
                  <th>Inter ($/oz)</th>
                  <th>Des. Inter</th>
                  <th>Maquila</th>
                  <th>Consumo</th>
                  <th>Factor</th>
                  <th>P/TN</th>
                  <th>Total</th>
                  <th >Acción</th>
                </tr>
              </thead>
              <tbody id="tbody_lotes_modal"></tbody>
            </table>
          </div>

        </div><!-- /modal-body -->

        <input type="hidden" id="hd_id_valorizacion">
        <input type="hidden" id="hd_modo_valorizacion">

        <div class="modal-footer" style="background:#f4f6f9;">
          <div id="wt_grabar_valorizacion" class="loading-badge" style="color:var(--muted);">
            <img src="<?php echo $img_waiting?>"> <span style="font-style:italic;">Guardando...</span>
          </div>
          <button type="button" class="btn btn-secondary btn-vv-save" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i>Cerrar
          </button>
          <button type="button" class="btn btn-primary btn-vv-save" onclick="f_GrabarValorizacion();">
            <i class="bi bi-save me-1"></i>Grabar
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- ══════════════════════════════════════════════
       MODAL: AGREGAR / EDITAR LOTE
  ══════════════════════════════════════════════ -->
  <div class="modal fade" id="modal_lote" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content" style="border-radius:10px; overflow:hidden;">

        <div class="modal-header modal-header-vv">
          <h5 class="modal-title" id="modal_loteLabel">Agregar Lote</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="padding:16px 20px;">

          <!-- Selector de lote y elemento -->
          <div class="row g-3 mb-3">
            <div class="col-md-5">
              <label class="form-label-vv">Distribución / Lote <span class="text-danger">*</span></label>
              <!-- NUEVO: combo -->
              <div id="div_lote_combo">
                <select id="cmb_distribucion" class="form-control form-control-vv"
                  onchange="f_OnDistribucionChange();">
                  <option value="">[Seleccione la planta primero]</option>
                </select>
              </div>
              <!-- EDICIÓN: static -->
              <div id="div_lote_static" style="display:none;">
                <div class="field-readonly" id="lbl_lote_static">—</div>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label-vv">Elemento Químico <span class="text-danger">*</span></label>
              <div id="div_elemento_combo">
                <select id="cmb_elemento" class="form-control form-control-vv"
                  onchange="f_OnElementoChange();">
                  <option value="">Seleccione el lote...</option>
                </select>
              </div>
              <div id="div_elemento_static" style="display:none;">
                <div class="field-readonly" id="lbl_elemento_static">—</div>
              </div>
            </div>
            <div class="col-md-4">
              <div id="div_sin_cc" style="display:none;" class="mt-4">
                <span class="sin-vc-badge"><i class="bi bi-exclamation-triangle me-1"></i>Sin Condición Comercial</span>
              </div>
            </div>
          </div>

          <!-- Dos columnas: Info lote | Info valorización -->
          <div class="row g-3">

            <!-- Info del lote (readonly) -->
            <div class="col-md-6">
              <div class="section-band mb-2"><i class="bi bi-box-seam"></i> Información del Lote</div>
              <div class="info-panel">
                <div class="row g-2">
                  <div class="col-6">
                    <label class="form-label-vv">Cód. Interno</label>
                    <div class="field-readonly" id="f_codigo_interno">—</div>
                  </div>
                  <div class="col-6">
                    <label class="form-label-vv">Cód. Cliente</label>
                    <div class="field-readonly" id="f_codigo_cliente">—</div>
                  </div>
                  <div class="col-6">
                    <label class="form-label-vv">Guía Transportista</label>
                    <div class="field-readonly" id="f_guia_transportista">—</div>
                  </div>
                  <div class="col-6">
                    <label class="form-label-vv">Peso Húmedo (TN)</label>
                    <div class="field-readonly" id="f_peso_humedo">—</div>
                  </div>
                  <div class="col-4">
                    <label class="form-label-vv">Humedad %</label>
                    <div class="field-readonly" id="f_humedad">—</div>
                  </div>
                  <div class="col-4">
                    <label class="form-label-vv">Peso Seco (TN)</label>
                    <div class="field-readonly" id="f_peso_seco">—</div>
                  </div>
                  <div class="col-4">
                    <label class="form-label-vv">Ley Oro (oz/tc)</label>
                    <div class="field-readonly" id="f_ley_oro">—</div>
                  </div>
                  <div class="col-4">
                    <label class="form-label-vv">Ley Plata (oz/tc)</label>
                    <div class="field-readonly" id="f_ley_plata">—</div>
                  </div>
                  <div class="col-4">
                    <label class="form-label-vv">Ley Seleccionada</label>
                    <div class="field-readonly" id="f_ley_seleccionada">—</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Info de valorización (editable) -->
            <div class="col-md-6">
              <div class="section-band mb-2"><i class="bi bi-calculator"></i> Parámetros de Valorización</div>
              <div class="row g-2">

                <div class="col-6">
                  <label class="form-label-vv">Recuperación % <span class="text-danger">*</span></label>
                  <input type="number" id="txt_recuperacion" class="form-control form-control-vv text-center"
                    placeholder="0.00" onkeyup="f_CalcularTotales();" step="0.01">
                </div>
                <div class="col-6">
                  <label class="form-label-vv">INTER ($/oz) <span class="text-danger">*</span></label>
                  <input type="number" id="txt_inter" class="form-control form-control-vv text-center"
                    placeholder="0.00" onkeyup="f_CalcularTotales();" step="0.01">
                </div>
                <div class="col-6">
                  <label class="form-label-vv">Des. INTER ($/oz)</label>
                  <input type="number" id="txt_des_inter" class="form-control form-control-vv text-center"
                    placeholder="0.00" onkeyup="f_CalcularTotales();" step="0.01">
                </div>
                <div class="col-6">
                  <label class="form-label-vv">Maquila ($/oz)</label>
                  <input type="number" id="txt_maquila" class="form-control form-control-vv text-center"
                    placeholder="0.00" onkeyup="f_CalcularTotales();" step="0.01">
                </div>
                <div class="col-6">
                  <label class="form-label-vv">Consumo ($/oz)</label>
                  <input type="number" id="txt_consumo" class="form-control form-control-vv text-center"
                    placeholder="0.00" onkeyup="f_CalcularTotales();" step="0.01">
                </div>
                <div class="col-6">
                  <label class="form-label-vv">Factor</label>
                  <input type="number" id="txt_factor" class="form-control form-control-vv text-center"
                    placeholder="1.00" onkeyup="f_CalcularTotales();" step="0.0001">
                </div>

                <div class="col-12"><hr class="vv-divider"></div>

                <div class="col-6">
                  <label class="form-label-vv">Precio / Tonelada</label>
                  <div class="field-readonly" id="f_precio_tn">—</div>
                </div>
                <div class="col-6">
                  <label class="form-label-vv">Total ($)</label>
                  <div class="field-readonly" id="f_total" style="color:var(--success); font-size:14px;">—</div>
                </div>

              </div>
            </div>

          </div><!-- /row -->
        </div><!-- /modal-body -->

        <input type="hidden" id="hd_id_distribucion_detalle">
        <input type="hidden" id="hd_elemento_quimico">
        <input type="hidden" id="hd_id_condicion_comercial">
        <input type="hidden" id="hd_modo_lote">
        <input type="hidden" id="hd_fila_edicion">

        <div class="modal-footer" style="background:#f4f6f9;">
          <div id="wt_grabar_lote" class="loading-badge" style="color:var(--muted);">
            <img src="<?php echo $img_waiting?>"> <span style="font-style:italic;">Guardando lote...</span>
          </div>
          <button type="button" class="btn btn-secondary btn-vv-lote-save" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary btn-vv-lote-save" onclick="f_GrabarLote();">
            <i class="bi bi-check-lg me-1"></i>Agregar
          </button>
        </div>

      </div>
    </div>
  </div>

  <!-- ══════════════════════════════════════════════
       MODAL: VER / SUBIR / ELIMINAR ARCHIVOS
  ══════════════════════════════════════════════ -->
  <div class="modal fade" id="modal_archivos_vv" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content" style="border-radius:10px; overflow:hidden;">

        <div class="modal-header modal-header-vv">
          <h5 class="modal-title">
            <i class="bi bi-folder2-open me-2"></i>
            Archivos — <span id="lbl_archivos_vv_titulo"></span>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="padding:16px 20px;">

          <!-- Subir nuevo archivo -->
          <div class="d-flex align-items-center gap-2 mb-3">
            <input type="file" id="file_nuevo_vv" class="form-control form-control-sm" style="max-width:360px;">
            <button class="btn btn-primary btn-sm" onclick="f_SubirArchivoVV();">
              <i class="bi bi-upload me-1"></i>Subir
            </button>
            <div id="wt_subir_vv" style="display:none;">
              <span class="spinner-border spinner-border-sm text-primary"></span>
            </div>
          </div>

          <hr style="margin:10px 0 14px;">

          <!-- Lista de archivos -->
          <div id="div_lista_archivos_vv">
            <p class="text-muted text-center small">Cargando...</p>
          </div>

        </div>

        <div class="modal-footer" style="background:#f4f6f9;">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
        </div>

      </div>
    </div>
  </div>

  <!-- MODAL: TRAZABILIDAD (CAMBIOS) -->

  <div class="modal fade" id="modal_trazabilidad" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content" style="border-radius:10px; overflow:hidden;">
        <div class="modal-header modal-header-vv">
          <h5 class="modal-title"><i class="bi bi-clock-history me-2"></i>Trazabilidad de Cambios</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="padding:16px 20px;">
          <div id="modal_trazabilidad_cards" style="display:flex; flex-direction:column; gap:12px;"></div>
        </div>
        <div class="modal-footer" style="background:#f4f6f9;">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</div><!-- /row -->
</div><!-- /container-fluid -->

<!-- ── Scripts ── -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

<!-- Auxiliares globales -->
<?php include('global/auxiliares_js.php'); ?>

<!-- Lógica de la vista -->
<script src="valorizacion_ventamineral.js"></script>
</body>
</html>