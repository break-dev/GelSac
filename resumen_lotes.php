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
  <title><?php echo $nom_app; ?> | Resumen de Lotes</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

  <style>
    :root {
      --primary:    #25476a;
      --primary-lt: #eef3f8;
      --accent:     #EFB810;
      --brown:      #816951;
      --success:    #2e7d32;
      --danger:     #c62828;
      --warning:    #e65100;
      --border:     #dde3ec;
      --bg:         #f0f2f5;
      --card:       #ffffff;
      --text:       #1a2535;
      --muted:      #6b7a8d;
      --radius:     10px;
      --shadow:     0 2px 16px rgba(37,71,106,.09);
    }

    body { background: var(--bg); color: var(--text); font-family: 'Segoe UI', system-ui, sans-serif; font-size: 13px; }

    /* ── Toolbar de filtros ── */
    .filter-bar {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 12px 18px;
      display: flex;
      align-items: flex-end;
      gap: 14px;
      flex-wrap: wrap;
    }
    .filter-group { display: flex; flex-direction: column; gap: 4px; min-width: 200px; }
    .filter-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
    .filter-control {
      border: 1px solid var(--border); border-radius: 7px;
      padding: 6px 10px; font-size: 13px; color: var(--text);
      background: var(--card); outline: none;
      transition: border-color .18s;
    }
    .filter-control:focus { border-color: var(--primary); box-shadow: 0 0 0 .18rem rgba(37,71,106,.13); }

    /* ── Loading ── */
    .loading-bar {
      display: none; align-items: center; gap: 8px;
      font-size: 12px; color: var(--muted); font-style: italic;
    }
    .loading-bar img { width: 20px; }

    /* ── Header de sección ── */
    .section-header {
      background: linear-gradient(90deg, var(--primary) 0%, #2d5a84 100%);
      color: #fff;
      padding: 11px 18px;
      border-radius: var(--radius) var(--radius) 0 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 0;
    }
    .section-header .title { font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 7px; }
    .section-header .title i { color: var(--accent); font-size: 15px; }
    .stat-chip {
      background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.25);
      border-radius: 20px;
      padding: 3px 12px;
      font-size: 11px;
      font-weight: 700;
      color: #fff;
    }

    /* ── Grid de lote-cards ── */
    .lotes-grid {
      display: flex;
      flex-direction: column;
      gap: 16px;
      padding: 16px;
    }

    /* ── Lote Card ── */
    .lote-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: transform .15s, box-shadow .15s;
    }
    .lote-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(37,71,106,.15);
    }

    /* Cabecera de la card */
    .lc-head {
      background: linear-gradient(135deg, #4a5568 0%, #37474f 100%);
      color: #fff;
      padding: 10px 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
    }
    .lc-head-left { display: flex; align-items: center; gap: 8px; }
    .lc-num {
      background: rgba(255,255,255,.18);
      border-radius: 50%;
      width: 24px; height: 24px;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; flex-shrink: 0;
    }
    .lc-lote   { font-size: 16px; font-weight: 700; letter-spacing: .3px; }
    .lc-interno { font-size: 12px; opacity: .75; }
    .lc-head-right { text-align: right; }
    .lc-proveedor { font-size: 13px; opacity: .85; }
    .lc-doc       { font-size: 11px; opacity: .6; }

    /* Secciones */
    .lc-body {
      display: flex;
      flex-wrap: wrap;
    }
    .lc-section {
      border-top: 1px solid var(--border);
      border-right: 1px solid var(--border);
      padding: 12px 14px;
      flex: 1;
      min-width: 220px;
    }
    .lc-section:last-child {
      border-right: none;
    }
    .lc-section-title {
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .lcs-compra   { background: rgba(56,142,60,.05); }
    .lcs-compra   .lc-section-title { color: #2e7d32; }
    .lcs-despacho { background: rgba(25,118,210,.05); }
    .lcs-despacho .lc-section-title { color: #1565c0; }
    .lcs-dist     { background: rgba(123,31,162,.05); }
    .lcs-dist     .lc-section-title { color: #6a1f6e; }
    .lcs-venta    { background: rgba(216,67,21,.05); }
    .lcs-venta    .lc-section-title { color: #bf360c; }

    /* Filas de dato */
    .lc-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      gap: 6px;
      padding: 4px 0;
      font-size: 13px;
    }
    .lc-row + .lc-row { border-top: 1px dashed #eaeef4; }
    .lc-label { color: var(--muted); white-space: nowrap; flex-shrink: 0; }
    .lc-val   { font-weight: 600; text-align: right; word-break: break-word; }

    /* Chips */
    .chip {
      display: inline-block; padding: 2px 9px; border-radius: 20px;
      font-size: 10.5px; font-weight: 700; letter-spacing: .3px;
    }
    .chip-oro   { background: #fff8e1; color: #e65100; border: 1px solid #ffcc02; }
    .chip-plata { background: #e8eaf6; color: #283593; border: 1px solid #9fa8da; }

    /* Valor monetario */
    .money     { font-weight: 700; color: var(--success); font-variant-numeric: tabular-nums; }
    .money-neg { font-weight: 700; color: var(--danger); }

    /* Sin resultados */
    .empty-state {
      padding: 48px 20px;
      text-align: center;
      color: var(--muted);
      grid-column: 1 / -1;
    }
    .empty-state i { font-size: 36px; display: block; margin-bottom: 10px; opacity: .4; }
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

  <!-- ── CONTENIDO ── -->
  <div class="col-12" style="padding:12px 16px 24px;">

    <!-- Filtros -->
    <div class="filter-bar mb-3">

      <div class="filter-group">
        <span class="filter-label"><i class="bi bi-person-badge me-1"></i>Proveedor</span>
        <select id="cmb_proveedor" class="filter-control" style="min-width:240px;">
          <option value="">[Todos los proveedores]</option>
        </select>
      </div>

      <div class="filter-group">
        <span class="filter-label"><i class="bi bi-building me-1"></i>Planta Destino</span>
        <select id="cmb_planta" class="filter-control" style="min-width:200px;">
          <option value="">[Todas las plantas]</option>
        </select>
      </div>

      <div style="display:flex; align-items:flex-end; gap:8px; flex-wrap:wrap;">
        <div id="wt_resumen" class="loading-bar">
          <img src="<?php echo $img_waiting?>"> <span>Cargando...</span>
        </div>
      </div>

    </div>

    <!-- Resultado -->
    <div class="section-header">
      <div class="title">
        <i class="bi bi-layers"></i> Resumen de Lotes
      </div>
      <div style="display:flex; gap:8px; align-items:center;">
        <span class="stat-chip" id="stat_total">0 registros</span>
      </div>
    </div>

    <div id="lotes-grid" class="lotes-grid">
      <div class="empty-state">
        <i class="bi bi-hourglass-split"></i>
        Cargando...
      </div>
    </div>

  </div><!-- /col -->

</div><!-- /row -->
</div><!-- /container -->

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

<?php include('global/auxiliares_js.php'); ?>

<script src="resumen_lotes.js"></script>
</body>
</html>