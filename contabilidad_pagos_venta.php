<?php
session_start();
include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');

if (!isset($_SESSION["Id"])) {
    header('Location: index.php');
}

// Consultar plantas al inicio para que estén disponibles en toda la página
$q_plantas = "SELECT id, descripcion FROM tbconfig_plantas WHERE estado='A' ORDER BY descripcion";
$r_plantas = mysqli_query($enlace, $q_plantas);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/png">
    <title><?php echo $nom_app; ?> | Comprobantes Venta Mineral</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <!-- Global Styles -->
    <link rel="stylesheet" href="<?php echo $url_lims ?>/global/styles.css">

    <script>
        var gb_tipocambio_id = '';
        var gb_tipocambio_compra = '';
        var gb_tipocambio_venta = '';
    </script>

    <style>
        /* ── Layout ── */
        body {
            background: #f0f2f5;
            zoom: 85%;
        }

        /* ── Cards de factura ── */
        .fv-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 10px 0;
        }

        .fv-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
            overflow: hidden;
            transition: box-shadow .2s, transform .15s;
            border: 1.5px solid #e9ecef;
        }

        .fv-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, .14);
            transform: translateY(-2px);
        }

        .fv-card-header {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f0f2f5;
            background: linear-gradient(135deg, #1a3a5c 0%, #25476a 100%);
            color: #fff;
        }

        .fv-card-header .badge-serie {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .fv-card-header .fv-fecha {
            font-size: 11px;
            opacity: .82;
        }

        .fv-card-body {
            padding: 20px;
            font-size: 13px;
        }

        .fv-card-body .fv-planta {
            font-weight: 600;
            color: #1a3a5c;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .fv-montos {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }

        .fv-monto-box {
            flex: 1;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 7px 10px;
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .fv-monto-box .monto-label {
            font-size: 10px;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .fv-monto-box .monto-val {
            font-size: 14px;
            font-weight: 700;
            color: #1a3a5c;
        }

        .fv-monto-box.soles .monto-val {
            color: #198754;
        }

        .fv-progress-wrap {
            margin: 8px 0 4px;
        }

        .fv-progress-wrap .prog-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .progress {
            height: 7px;
            border-radius: 4px;
        }

        /* Estado badges */
        .estado-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .3px;
        }

        .est-E {
            background: #fff3cd;
            color: #856404;
        }

        .est-P {
            background: #cfe2ff;
            color: #084298;
        }

        .est-A {
            background: #d1e7dd;
            color: #0a3622;
        }

        .est-B {
            background: #d1e7dd;
            color: #0a3622;
        }

        .est-C {
            background: #d4edda;
            color: #155724;
        }

        .est-X {
            background: #f8d7da;
            color: #721c24;
        }

        /* Tipo pago badges */
        .tipo-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        .tipo-1 {
            background: #e2e3e5;
            color: #383d41;
        }

        .tipo-2 {
            background: #d4edda;
            color: #155724;
        }

        .tipo-3 {
            background: #fff3cd;
            color: #856404;
        }

        .fv-card-footer {
            padding: 10px 16px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .fv-card-footer .btn {
            font-size: 12px;
            padding: 4px 12px;
        }

        /* ── Toolbar ── */
        .toolbar-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .07);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar-card h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1a3a5c;
        }

        /* ── Modal fields ── */
        .form-label-sm {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 3px;
            font-weight: 500;
        }

        .field-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #e9ecef;
        }

        /* Anticipos table in modal */
        .tbl-anticipos {
            font-size: 12px;
        }

        .tbl-anticipos thead th {
            background: #1a3a5c;
            color: #fff;
            font-weight: 500;
            padding: 6px 10px;
            font-size: 11px;
            border-color: #25476a;
        }

        .tbl-anticipos tbody td {
            padding: 6px 10px;
            vertical-align: middle;
        }

        .anticipo-row.selected {
            background: #cfe2ff;
        }

        /* Lotes summary chips */
        .lote-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #e9ecef;
            border-radius: 6px;
            padding: 3px 8px;
            font-size: 11px;
            margin: 2px;
        }

        .lote-chip .pill-au {
            background: #f59e0b;
            color: #fff;
            border-radius: 4px;
            padding: 1px 5px;
            font-size: 10px;
        }

        .lote-chip .pill-ag {
            background: #6c757d;
            color: #fff;
            border-radius: 4px;
            padding: 1px 5px;
            font-size: 10px;
        }

        /* Loading overlay */
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

        /* Detail card in resumen modal */
        .info-row {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .info-row .info-label {
            color: #6c757d;
            min-width: 110px;
            font-size: 12px;
        }

        .info-row .info-val {
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #adb5bd;
        }

        .empty-state i {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
        }

        /* ── Summary Cards (Modal Detalle) ── */
        .fv-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .fv-summary-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            border: 1px solid #e9ecef;
            position: relative;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            display: flex;
            flex-direction: column;
        }

        .fv-summary-card.total {
            background-color: #e9ecef;
        }

        .fv-summary-card.neto {
            background-color: #e3f2fd;
        }

        .fv-summary-card.detraccion {
            background-color: #e8f5e9;
        }

        .fv-summary-card .card-title {
            font-size: 14px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fv-summary-card .card-amount {
            font-size: 28px;
            font-weight: 800;
            color: #212529;
            margin-bottom: 5px;
        }

        .fv-summary-card .card-subtitle,
        .fv-summary-card .card-sub-info {
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 15px;
            min-height: 34px; /* Asegura alineacion */
        }

        .fv-summary-card .prog-info {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 4px;
            margin-top: auto;
            padding-top: 10px;
        }

        .fv-summary-card .progress {
            height: 6px;
            background-color: rgba(0, 0, 0, .05);
        }

        .fv-summary-card .badge-status {
            font-size: 10px;
            border-radius: 10px;
            background-color: #f1f5f9;
        }

        /* Colores por tipo */
        .fv-summary-card.total {
            border-left: 4px solid #3b82f6;
        }

        .fv-summary-card.neto {
            border-left: 4px solid #6366f1;
        }

        .fv-summary-card.detraccion {
            border-left: 4px solid #10b981;
        }

        .badge-status {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
        }

        .badge-pendiente {
            background: #fee2e2;
            color: #ef4444;
        }

        .badge-pagado {
            background: #dcfce7;
            color: #10b981;
        }

        /* Estilos de Lotes */
        .lotes-collapse-header {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }

        .lotes-collapse-header:hover {
            background: #f1f5f9;
        }

        .lotes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 10px;
            padding: 12px;
        }

        .lote-item {
            background: #fff;
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .lote-item:hover {
            border-color: #1a3a5c;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .lote-info-main {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pill-au,
        .pill-ag {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 11px;
        }

        .pill-au {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .pill-ag {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .lote-codes {
            display: flex;
            flex-direction: column;
        }

        .lote-client {
            font-weight: 700;
            color: #1e293b;
        }

        .lote-internal {
            font-size: 10px;
            color: #64748b;
        }

        .lote-price {
            text-align: right;
            font-weight: 800;
            color: #0f172a;
            font-size: 13px;
        }
    </style>
</head>

<body onload="f_SetDimension(); f_Init();">
    <div class="container-fluid">
        <div class="row">
            <!-- Navbar -->
            <?php echo $navbar_maintop; ?>

            <!-- Menú lateral -->
            <div class="modal fade" id="menuModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-left" style="margin:0;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Menú de Opciones</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body"
                            style="background:#25476a;color:#fff;border-top:3px solid #EFB810;padding:0;">
                            <ul class="list-unstyled">
                                <div id="div_menu1"></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Contenido principal ── -->
            <div class="col-12" style="padding:10px 20px;">

                <!-- Toolbar -->
                <div class="toolbar-card">
                    <h5><i class="bi bi-receipt-cutoff me-2" style="color:#EFB810;"></i>Comprobantes de Venta Mineral
                    </h5>
                    <div class="ms-auto d-flex gap-2 align-items-center flex-wrap">
                        <div id="wt_resumen" style="display:none; font-size:12px; color:#6c757d;">
                            <img src="<?php echo $img_waiting ?>" style="width:18px;"> Cargando...
                        </div>
                        <div class="d-flex align-items-center gap-2 me-3">
                            <select id="filtro_anio" class="form-select form-select-sm" style="width: 100px;" onchange="f_LoadFacturas();">
                                <?php
                                $anio_actual = date("Y");
                                for ($i = $anio_actual; $i >= 2024; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                            <select id="filtro_mes" class="form-select form-select-sm" style="width: 130px;" onchange="f_LoadFacturas();">
                                <option value="">[Todos los meses]</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="f_AbrirModalNuevaFactura();"
                            id="btn_nueva_factura">
                            <i class="bi bi-plus-circle me-1"></i> Nueva Factura
                        </button>
                    </div>
                </div>

                <!-- Grid de cards -->
                <div id="div_factura_grid" class="fv-grid">
                    <div class="empty-state w-100">
                        <i class="bi bi-hourglass-split"></i>
                        Cargando comprobantes...
                    </div>
                </div>
            </div>

        </div><!-- /row -->
    </div>

    <!-- ════════════════════════════════════════════
     MODAL: Nueva Factura / Comprobante
════════════════════════════════════════════ -->
    <div class="modal fade" id="modal_nueva_factura" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(135deg,#1a3a5c,#25476a);color:#fff;">
                    <h5 class="modal-title"><i class="bi bi-receipt me-2"></i>Registrar Comprobante de Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <!-- Fila 1: Planta + Fecha + Serie/Número -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label-sm">Planta *</label>
                            <select id="nf_planta" class="form-select form-select-sm" onchange="f_OnPlantaChange();">
                                <option value="">[Seleccione planta]</option>
                                <?php
                                if ($r_plantas) {
                                    mysqli_data_seek($r_plantas, 0);
                                    while ($p = mysqli_fetch_assoc($r_plantas)) {
                                        echo '<option value="' . $p['id'] . '" data-desc="' . htmlspecialchars($p['descripcion']) . '">' . $p['descripcion'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-sm">Fecha Emisión *</label>
                            <input id="nf_fecha" type="date" class="form-control form-control-sm"
                                value="<?php echo $g_date; ?>" onchange="f_GetTipoCambioVenta();">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-sm">Serie *</label>
                            <input id="nf_serie" type="text" class="form-control form-control-sm" placeholder="F001"
                                style="text-transform:uppercase;">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-sm">Número *</label>
                            <input id="nf_numero" type="text" class="form-control form-control-sm"
                                placeholder="00001234" style="text-transform:uppercase;">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-sm">% Detracción *</label>
                            <input id="nf_detraccion" type="number" class="form-control form-control-sm" value="10"
                                min="0" max="100">
                        </div>
                    </div>

                    <!-- Fila 2: Tipo de Cambio -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="field-box">
                                <div class="form-label-sm mb-1">Tipo de Cambio (TC Venta) *</div>
                                <div class="d-flex align-items-center gap-2">
                                    <input id="nf_tipocambio" type="number" step="0.0001"
                                        class="form-control form-control-sm" placeholder="3.8000"
                                        style="box-shadow:0 0 6px #1a3a5c44; max-width:130px;">

                                    <img src="<?php echo $img_tipocambio ?>" style="width: 35px; cursor: pointer;"
                                        title="Configurar Tipo de Cambio" onclick="f_AdminTipoCambio();">

                                    <div id="nf_tc_info" class="text-muted" style="font-size:11px;"></div>
                                    <a href="https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias" target="_blank"
                                        style="font-size:11px; color:#0d6efd; white-space:nowrap;">
                                        <i class="bi bi-search me-1"></i>Sunat
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="field-box">
                                <div class="form-label-sm mb-1">Evidencias (adjuntos)</div>
                                <input id="nf_evidencias" type="file" class="form-control form-control-sm" multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <!-- Sección: Valorizaciones / Lotes -->
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <div class="field-box">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="form-label-sm mb-0">
                                        <i class="bi bi-layers me-1"></i>Valorizaciones de la Planta (lotes Au
                                        valorizados en venta)
                                    </div>
                                    <div id="nf_total_usd_lbl" class="fw-bold text-primary" style="font-size:14px;">
                                        Total: $ 0.00</div>
                                </div>
                                <div id="nf_lotes_loading" class="text-muted" style="font-size:12px; display:none;">
                                    <img src="<?php echo $img_waiting ?>" style="width:16px;"> Cargando lotes...
                                </div>
                                <div class="table-responsive" style="max-height:240px; overflow-y:auto;">
                                    <table class="table table-hover table-sm mb-0" style="font-size:12px;">
                                        <thead>
                                            <tr>
                                                <th style="background:#1a3a5c;color:#fff;font-weight:500;">Sel.</th>
                                                <th style="background:#1a3a5c;color:#fff;font-weight:500;">Valorización
                                                </th>
                                                <th style="background:#1a3a5c;color:#fff;font-weight:500;">Cod. Cliente
                                                </th>
                                                <th style="background:#1a3a5c;color:#fff;font-weight:500;">Cod. Interno
                                                </th>
                                                <th style="background:#1a3a5c;color:#fff;font-weight:500;">Partición
                                                </th>
                                                <th style="background:#1a3a5c;color:#fff;font-weight:500;">Elem.</th>
                                                <th
                                                    style="background:#1a3a5c;color:#fff;font-weight:500;text-align:right;">
                                                    Precio Total ($)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl_lotes_valorizacion">
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-3">Seleccione una
                                                    planta para cargar los lotes.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Anticipos -->
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <div class="field-box">
                                <div class="form-label-sm mb-1">
                                    <i class="bi bi-currency-dollar me-1"></i>¿Usar Anticipos de la Planta?
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="nf_usa_anticipos"
                                        onchange="f_ToggleAnticipos();">
                                    <label class="form-check-label" for="nf_usa_anticipos" style="font-size:12px;">Sí,
                                        seleccionar anticipos</label>
                                </div>
                                <div id="div_anticipos" style="display:none;">
                                    <div id="nf_anticipos_loading" class="text-muted"
                                        style="font-size:12px; display:none;">
                                        <img src="<?php echo $img_waiting ?>" style="width:16px;"> Cargando anticipos...
                                    </div>
                                    <div class="table-responsive" style="max-height:200px; overflow-y:auto;">
                                        <table class="table table-hover table-sm mb-0 tbl-anticipos">
                                            <thead>
                                                <tr>
                                                    <th>Sel.</th>
                                                    <th>Factura Anticipo</th>
                                                    <th class="text-end">Saldo Inicial ($)</th>
                                                    <th class="text-end">Saldo Disponible ($)</th>
                                                    <th class="text-center">Monto a usar ($)</th>
                                                    <th>Fecha</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl_anticipos_planta">
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-2">Sin anticipos
                                                        disponibles.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end mt-2">
                                        <span class="text-muted" style="font-size:12px;">Total anticipos
                                            seleccionados:</span>
                                        <span id="nf_total_anticipos_lbl" class="fw-bold ms-2"
                                            style="font-size:13px; color:#0d6efd;">$ 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen financiero -->
                    <div class="row g-2" id="div_resumen_financiero">
                        <div class="col-md-3">
                            <div class="fv-monto-box">
                                <div class="monto-label">Total USD (lotes)</div>
                                <div class="monto-val" id="nf_res_usd">$ 0.00</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-monto-box soles">
                                <div class="monto-label">Total Soles (TC × USD)</div>
                                <div class="monto-val" id="nf_res_soles">S/ 0.00</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-monto-box">
                                <div class="monto-label">Detracción (%)</div>
                                <div class="monto-val" id="nf_res_detraccion">$ 0.00</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-monto-box">
                                <div class="monto-label">Anticipos Seleccionados</div>
                                <div class="monto-val" id="nf_res_anticipos">$ 0.00</div>
                            </div>
                        </div>
                    </div>

                </div><!-- /modal-body -->

                <div class="modal-footer" style="gap:10px;">
                    <div id="wt_grabar_factura" style="display:none; font-size:12px; color:#6c757d;">
                        <img src="<?php echo $img_waiting ?>" style="width:18px;"> Grabando...
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="f_GrabarFactura();">
                        <i class="bi bi-save me-1"></i>Registrar Comprobante
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════
     MODAL: Lista de Pagos
════════════════════════════════════════════ -->
    <div class="modal fade" id="modal_lista_pagos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#198754; color:#fff;">
                    <h5 class="modal-title"><i class="bi bi-cash-coin me-2"></i>Historial de Pagos - <span
                            id="lbl_pago_factura"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="fv-monto-box">
                                <div class="monto-label">Total Comprobante</div>
                                <div class="monto-val" id="lp_total_usd">$ 0.00</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-monto-box">
                                <div class="monto-label">Total Pagado</div>
                                <div class="monto-val text-success" id="lp_pagado_usd">$ 0.00</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-monto-box">
                                <div class="monto-label">Saldo Pendiente</div>
                                <div class="monto-val text-danger" id="lp_saldo_usd">$ 0.00</div>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <button class="btn btn-success w-100" onclick="f_AbrirModalRegistroPago();"
                                id="btn_nuevo_pago">
                                <i class="bi bi-plus-circle me-1"></i> Registrar Pago
                            </button>
                        </div>
                    </div>

                    <div id="div_pagos_grid" class="fv-grid" style="max-height: 400px; overflow-y: auto; padding: 10px;">
                        <!-- Dinámico -->
                    </div>
                    <div class="d-flex justify-content-end mt-3 border-top pt-2">
                        <span class="fw-bold me-2">TOTAL PAGADO ($):</span>
                        <span class="fw-bold text-primary" style="font-size:16px;" id="lp_foot_total">$ 0.00</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════
     MODAL: Registrar Nuevo Pago
════════════════════════════════════════════ -->
    <div class="modal fade" id="modal_registro_pago" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #198754; color: #fff;">
                    <h5 class="modal-title">Nuevo Pago</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <!-- Caja Banco Cliente -->
                        <div class="border rounded p-3" style="flex: 1; background: #fff;">
                            <div class="mb-2">
                                <label class="form-label text-muted" style="font-size: 13px;">Banco Cliente:</label>
                                <select id="rp_banco_planta" class="form-select form-select-sm" onchange="f_OnChangeBancoPlanta();">
                                    <option value="">Elija una opción...</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-muted" style="font-size: 13px;">Cuenta Cliente:</label>
                                <select id="rp_cuenta_planta" class="form-select form-select-sm" onchange="f_SugerirMontoPago();">
                                    <option value="">Seleccione cuenta</option>
                                </select>
                            </div>
                        </div>

                        <!-- Flecha centro -->
                        <div class="mx-3 d-flex align-items-center justify-content-center">
                            <div style="background: #198754; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>

                        <!-- Caja Banco GEL -->
                        <div class="border rounded p-3" style="flex: 1; background: #fff;">
                            <div class="mb-2">
                                <label class="form-label text-muted" style="font-size: 13px;">Banco GEL:</label>
                                <select id="rp_banco_empresa" class="form-select form-select-sm" onchange="f_OnChangeBancoEmpresa();">
                                    <option value="">Elija una opción...</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-muted" style="font-size: 13px;">Cuenta GEL:</label>
                                <select id="rp_cuenta_empresa" class="form-select form-select-sm" onchange="f_SugerirMontoPago();">
                                    <option value="">Seleccione cuenta</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <!-- Fila Detalles -->
                    <div class="row align-items-center mb-3">
                        <div class="col-md-2 text-muted" style="font-size: 14px;">Fecha Pago:</div>
                        <div class="col-md-4">
                            <input type="date" id="rp_fecha" class="form-control form-control-sm" value="<?php echo $g_date; ?>">
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <div class="form-check form-switch ms-3">
                                <input class="form-check-input" type="checkbox" id="rp_is_detraccion" onchange="f_OnIsDetraccionChange();">
                                <label class="form-check-label text-success fw-bold" for="rp_is_detraccion" style="font-size:13px;">Pago de Detracción</label>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-2 text-muted" style="font-size: 14px;">Por pagar:</div>
                        <div class="col-md-4">
                            <div class="form-control form-control-sm text-center fw-bold" style="background: #e9ecef; color: #198754;" id="rp_por_pagar">$ 0.00</div>
                        </div>
                        <div class="col-md-2 text-end text-muted" style="font-size: 14px;">A pagar :</div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text fw-bold bg-white" id="rp_moneda_pago_simbolo" style="border-right: none;">$</span>
                                <input type="number" id="rp_monto" class="form-control fw-bold text-center" step="0.01" style="border-left: none; font-size: 15px;" oninput="f_RecalcConversionPago();">
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-2 text-muted" style="font-size: 14px;">Medio Pago:</div>
                        <div class="col-md-4">
                            <select id="rp_medio_pago" class="form-select form-select-sm">
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-end text-muted" style="font-size: 14px;">Nro. Operación:</div>
                        <div class="col-md-4">
                            <input type="text" id="rp_nro_operacion" class="form-control form-control-sm" placeholder="Ej: 123456">
                        </div>
                    </div>

                    <div class="row align-items-center mb-4">
                        <div class="col-md-2 text-muted" style="font-size: 14px;">Evidencia:</div>
                        <div class="col-md-4">
                            <input type="file" id="rp_evidencia" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-2 text-muted" style="font-size: 14px;">Observación:</div>
                        <div class="col-md-10">
                            <textarea id="rp_observacion" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                    
                    <div class="row mt-2" style="display:none;">
                        <div class="col-md-12">
                            <input type="number" id="rp_tipocambio" class="form-control" oninput="f_RecalcConversionPago();">
                            <input type="text" id="rp_monto_usd" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="modal-footer p-3" style="background: #fff; border-top: 1px solid #dee2e6;">
                    <div id="wt_grabar_pago" style="display:none; font-size:12px; color:#6c757d; margin-right: auto;">
                        <img src="<?php echo $img_waiting ?>" style="width:18px;"> Grabando...
                    </div>
                    <button type="button" class="btn btn-secondary" style="background: #6c757d; font-size: 14px; padding: 6px 20px;" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" style="background: #0d6efd; border: none; font-size: 14px; padding: 6px 20px;" onclick="f_GrabarPago();">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_admintipocambio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modal_admintipocambioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: solid; border-width: 1px; border-color: #E6E9ED;">
                <div class="modal-header py-2" style="background:linear-gradient(135deg,#1a3a5c,#25476a);color:#fff;">
                    <h5 class="modal-title" id="modal_admintipocambioLabel">Nuevo Tipo de Cambio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">Fecha:</div>
                        <div class="col-md-6">
                            <input id="tipocambio_fecha" type="date" class="form-control form-control-sm text-center"
                                disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">Moneda Base:</div>
                        <div class="col-md-6">
                            <select id="tipocambio_moneda" class="form-select form-select-sm" disabled>
                                <?php
                                $q_monedas = "SELECT Id, CONCAT('(', abv, ') ', descripcion) AS DESCRIPCION FROM tbconfig_monedas WHERE estado = 'A' ORDER BY is_default_valorizaciones DESC";
                                if ($res_monedas = mysqli_query($enlace, $q_monedas)) {
                                    while ($row_monedas = mysqli_fetch_array($res_monedas)) {
                                        echo '<option value="' . $row_monedas["Id"] . '">' . $row_monedas["DESCRIPCION"] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">TC Compra:</div>
                        <div class="col-md-3"><input id="tipocambio_compra" type="number" step="0.0001"
                                class="form-control form-control-sm text-center"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-1"></div>
                        <div class="col-md-4">TC Venta:</div>
                        <div class="col-md-3"><input id="tipocambio_venta" type="number" step="0.0001"
                                class="form-control form-control-sm text-center"></div>
                    </div>

                    <input id="hd_idtipocambio" type="hidden">
                    <input id="hd_tipocambio_modograbar" type="hidden">
                </div>
                <div class="modal-footer">
                    <div id="wt_admintipocambio" class="text-center mt-2" style="font-size: 12px; display: none;">
                        <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                        <label style="font-style: italic;"> Grabando datos...</label>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm wt_admintipocambio_button"
                        data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-sm wt_admintipocambio_button"
                        onclick="f_GrabarTipoCambio();">Grabar Tipo Cambio</button>
                </div>
            </div>
        </div>
    </div>

    <div id="div_loading">
        <div class="spinner-border text-light" role="status"></div>
    </div>

    <!-- ── Scripts ── -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <?php include('global/auxiliares_js.php'); ?>

    <script>
        function f_Init() {
            f_GetMenuPrincipal();
            $("#nv_titulo").html('| Comprobantes Venta Mineral');
            f_LoadFacturas();
            f_GetTipoCambioVenta();
        }
    </script>
    <!-- ════════════════════════════════════════════
     MODAL: Gestión de Evidencias
════════════════════════════════════════════ -->
    <div class="modal fade" id="modal_evidencias" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:#25476a; color:#fff;">
                    <h5 class="modal-title"><i class="bi bi-files me-2"></i>Evidencias / Adjuntos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-sm">Subir nuevo archivo</label>
                        <div class="input-group input-group-sm">
                            <input type="file" id="ev_nuevo_archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <button class="btn btn-primary" type="button" onclick="f_SubirEvidencia();">
                                <i class="bi bi-upload"></i>
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div id="div_evidencias_list" class="list-group list-group-flush">
                        <!-- Dinámico -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="contabilidad_pagos_venta.js?v=<?php echo time(); ?>"></script>
</body>

</html>