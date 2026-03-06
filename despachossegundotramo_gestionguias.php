<?php
// Inicia la sesión
session_start();

// Inclusión de archivos de configuración y utilidades
include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

  <title><?php echo $nom_app; ?> | Gestionar Guías (2do Tramo)</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <link rel="stylesheet" href="<?php echo $url_lims; ?>/global/styles.css">

  <style>
    body {
      font-size: 1.15rem;
      /* Escala el texto base de Bootstrap para compensar el zoom 80% */
    }

    .header-primary {
      background-color: #1a3a5c !important;
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
      font-size: 14px;
    }

    .header-gold {
      background-color: #816951 !important;
      border-color: #ffffff !important;
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
      font-size: 14px;
    }

    .card-agrupacion {
      border-left: 4px solid #1a3a5c;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .card-agrupacion:hover {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
      transform: translateY(-1px);
    }

    .card-agrupacion.selected {
      border-left-color: #0d6efd;
      background-color: #f0f6ff;
    }

    .badge-lote {
      background-color: #17a2b8;
      color: white;
      font-size: 0.75em;
      padding: 0.3em 0.6em;
    }

    .badge-blending {
      background-color: #6f42c1;
      color: white;
      font-size: 0.75em;
      padding: 0.3em 0.6em;
    }

    .badge-guia-activa {
      background-color: #198754;
    }

    .badge-guia-anulada {
      background-color: #dc3545;
    }

    .stat-card {
      border-radius: 10px;
      padding: 12px 16px;
      text-align: center;
    }

    .stat-card .value {
      font-size: 1.5rem;
      font-weight: 700;
    }

    .stat-card .label {
      font-size: 0.75rem;
      text-transform: uppercase;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .tabla-lotes-guia th {
      background-color: #f8f9fa;
      font-size: 13px;
      text-transform: uppercase;
      font-weight: 700;
      color: #6c757d;
    }

    .tabla-lotes-guia td {
      font-size: 15px;
      vertical-align: middle;
    }

    .panel-section {
      background: #fff;
      border: 1px solid #e6e9ed;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 12px;
    }

    .section-title {
      font-size: 16px;
      font-weight: 700;
      color: #1a3a5c;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .section-title i {
      font-size: 18px;
    }

    .detalle-lotes-container {
      max-height: 45vh;
      overflow-y: auto;
    }

    .guia-row-table {
      font-size: 15px;
    }

    .guia-row-table td {
      vertical-align: middle;
    }

    .empty-state {
      padding: 40px 20px;
      text-align: center;
      color: #adb5bd;
    }

    .empty-state i {
      font-size: 48px;
      margin-bottom: 10px;
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

      <!-- Contenido Principal -->
      <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 10px; padding-left: 15px; padding-right: 15px;">

        <!-- Header -->
        <div class="panel-section d-flex justify-content-between align-items-center mb-2">
          <div>
            <h5 class="mb-0 fw-bold text-dark">
              <i class="bi bi-file-earmark-text me-2" style="color: #816951;"></i>Gestionar Guías — Segundo Tramo
            </h5>
            <small class="text-muted">Generación de guías de remisión para despachos del segundo tramo</small>
          </div>
          <div class="d-flex gap-2 align-items-center">
            <div id="wt_loading" style="display: none;">
              <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
              <span class="text-muted small fst-italic">Cargando...</span>
            </div>
          </div>
        </div>

        <!-- Filtros -->
        <div class="panel-section mb-2">
          <div class="row g-2 align-items-end">
            <div class="col-auto">
              <label class="form-label small fw-bold mb-0">Egresos Desde</label>
              <input type="text" id="filtro_fecha_desde" class="form-control form-control-sm bg-white"
                placeholder="dd/mm/yyyy" style="width: 130px;" readonly>
            </div>
            <div class="col-auto">
              <label class="form-label small fw-bold mb-0">Hasta</label>
              <input type="text" id="filtro_fecha_hasta" class="form-control form-control-sm bg-white"
                placeholder="dd/mm/yyyy" style="width: 130px;" readonly>
            </div>
            <div class="col-auto">
              <label class="form-label small fw-bold mb-0">Estado</label>
              <select id="filtro_estado" class="form-select form-select-sm" style="width: 150px;">
                <option value="TODOS">Ver Todos</option>
                <option value="POR_ASIGNAR" selected>Por Asignar (Pendiente)</option>
                <option value="ASIGNADAS">Asignadas (Generadas)</option>
              </select>
            </div>
            <div class="col-auto">
              <label class="form-label small fw-bold mb-0">Placa</label>
              <input type="text" id="filtro_placa" class="form-control form-control-sm" placeholder="Buscar placa..."
                style="width: 140px; text-transform: uppercase;">
            </div>
            <div class="col-auto">
              <button class="btn btn-primary btn-sm" id="btn_buscar">
                <i class="bi bi-search me-1"></i> Buscar
              </button>
              <button class="btn btn-outline-secondary btn-sm" id="btn_limpiar" title="Limpiar filtros">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Panel Unificado: Agrupaciones y Guías -->
        <div class="panel-section" style="min-height: 400px;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="section-title mb-0">
              <i class="bi bi-table text-primary"></i>
              Listado de Egresos y Guías Segundo Tramo
            </div>
            <div class="d-flex gap-3 align-items-center">
              <span class="badge bg-primary rounded-pill" style="font-size: 11px;">Pendientes: <span id="badge_pendientes">0</span></span>
              <span class="badge bg-success rounded-pill" style="font-size: 11px;">Generadas: <span id="badge_guias">0</span></span>
              <div id="wt_listado">
                <span class="spinner-border spinner-border-sm text-secondary" style="display:none;"></span>
              </div>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
            <table class="table table-bordered table-hover table-sm mb-0">
              <thead class="sticky-top">
                <tr>
                  <th class="header-primary text-center" style="min-width: 35px;">N°</th>
                  <th class="header-primary text-center" style="min-width: 110px;">Fecha Egreso</th>
                  <th class="header-primary text-center" style="min-width: 110px;">Guía(s)</th>
                  <th class="header-primary text-center" style="min-width: 100px;">Placa(s)</th>
                  <th class="header-primary" style="min-width: 200px;">Empresa Transporte</th>
                  <th class="header-primary text-center" style="min-width: 60px;">Lotes</th>
                  <th class="header-primary text-center" style="min-width: 110px;">Peso Neto (Kg)</th>
                  <th class="header-primary text-center" style="min-width: 100px;">Conductor</th>
                  <th class="header-primary text-center" style="min-width: 100px;">Estado</th>
                  <th class="header-primary text-center" style="min-width: 150px;">Acciones</th>
                </tr>
              </thead>
              <tbody id="tbl_listado_unificado">
                <tr>
                  <td colspan="10" class="text-center text-muted p-4">
                    <i class="bi bi-search" style="font-size: 24px;"></i><br>
                    Use los filtros para buscar información.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Panel Inferior: Detalle de Lotes de la Agrupación seleccionada -->
        <div class="panel-section" id="panel_detalle_agrupacion" style="display: none;">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="section-title mb-0">
              <i class="bi bi-box-seam text-success"></i>
              Detalle de Lotes — <span id="lbl_grupo_titulo" class="text-primary">-</span>
            </div>
            <button class="btn btn-sm btn-outline-secondary" onclick="cerrarDetalleAgrupacion()">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>

          <!-- Info cards resumidas -->
          <div class="row g-2 mb-3" id="div_info_grupo">
          </div>

          <div class="detalle-lotes-container">
            <table class="table table-bordered table-sm tabla-lotes-guia mb-0">
              <thead class="sticky-top">
                <tr>
                  <th class="text-center" style="width: 30px;">N°</th>
                  <th class="text-center">Tipo</th>
                  <th>Código Mineral</th>
                  <th>Despacho</th>
                  <th class="text-center">Presentación</th>
                  <th class="text-end">Peso (Kg)</th>
                  <th class="text-end">P. Bruto (Kg)</th>
                  <th class="text-end">Tara (Kg)</th>
                  <th class="text-end fw-bold">P. Neto (Kg)</th>
                </tr>
              </thead>
              <tbody id="tbl_detalle_lotes_grupo">
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ======================= MODAL: GENERAR GUÍA ======================= -->
  <div class="modal fade" id="modal_generar_guia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_generar_guiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header" style="background: #1a3a5c; color: #fff;">
          <h5 class="modal-title" id="modal_generar_guiaLabel">
            <i class="bi bi-file-earmark-plus me-2"></i>Generar Guía de Remisión
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">

          <input type="hidden" id="hd_modo_guia" value="N">
          <input type="hidden" id="hd_id_guia" value="0">
          <input type="hidden" id="hd_ids_distribuciones" value="[]">

          <!-- Sección 1: Información de Fechas -->
          <div class="row g-3 mb-3">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-calendar3 me-1"></i> Información de Fechas
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Fecha Inicio Traslado</label>
              <input id="guia_fecha_inicio_traslado" type="date" class="form-control form-control-sm"
                value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Fecha Emisión</label>
              <div class="input-group input-group-sm">
                <input id="guia_fecha_emision" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                <input id="guia_hora_emision" type="time" class="form-control"
                  value="<?php echo date('H:i'); ?>">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Fecha/Hora en Planta</label>
              <div class="input-group input-group-sm">
                <input id="guia_fecha_planta" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                <input id="guia_hora_planta" type="time" class="form-control"
                  value="<?php echo date('H:i'); ?>">
              </div>
            </div>
          </div>

          <!-- Sección 2: Planta y Guías -->
          <div class="row g-3 mb-3">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-building me-1"></i> Planta y Guías
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Planta Origen</label>
              <select id="guia_planta_origen" class="form-select form-select-sm">
                <option value="">Seleccione...</option>
                <option value="1" selected>Huanchaco</option>
                <option value="2">Laredo</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Planta Destino</label>
              <input type="text" id="guia_planta_destino" class="form-control form-control-sm bg-light" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Guía Remitente</label>
              <div class="input-group input-group-sm">
                <input id="guia_rem_serie" type="text" class="form-control" placeholder="Serie"
                  style="text-transform: uppercase;">
                <span class="input-group-text">-</span>
                <input id="guia_rem_numero" type="text" class="form-control" placeholder="Número"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Guía Transportista</label>
              <div class="input-group input-group-sm">
                <input id="guia_transp_serie" type="text" class="form-control guia_grt_field" placeholder="Serie"
                  style="text-transform: uppercase;">
                <span class="input-group-text">-</span>
                <input id="guia_transp_numero" type="text" class="form-control guia_grt_field" placeholder="Número"
                  style="text-transform: uppercase;">
              </div>
              <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" id="chk_sin_grt">
                <label class="form-check-label small" for="chk_sin_grt">Sin Guía Transportista</label>
              </div>
            </div>
          </div>

          <!-- Sección 3: Motivo de Traslado -->
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label small fw-bold">Motivo de Traslado</label>
              <select id="guia_motivo_traslado" class="form-select form-select-sm">
                <option value="">Seleccione...</option>
                <option value="VENTA SUJETA A CONFIRMACIÓN">VENTA SUJETA A CONFIRMACIÓN</option>
                <option value="SERVICIO DE CHANCADO">SERVICIO DE CHANCADO</option>
                <option value="TRASLADO ENTRE ESTABLECIMIENTOS">TRASLADO ENTRE ESTABLECIMIENTOS</option>
              </select>
            </div>
          </div>

          <!-- Sección 1-B: Información de la Unidad (Lectura) -->
          <div class="row g-3 mt-3 mb-3 bg-light p-2 rounded border mx-0">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-1">
                <i class="bi bi-truck-flatbed me-1"></i> Información de la Unidad
              </h6>
            </div>
            <div class="col-md-5">
              <label class="form-label small fw-bold mb-0">Empresa Transporte</label>
              <input type="text" id="guia_unit_transportista" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold mb-0">Marca</label>
              <input type="text" id="guia_unit_marca" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold mb-0">Placa</label>
              <input type="text" id="guia_unit_placa" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold mb-0">MTC</label>
              <input type="text" id="guia_unit_mtc" class="form-control form-control-sm" readonly>
            </div>
          </div>

          <!-- Sección 4: Información de Tolva -->
          <div class="row g-3 mb-3">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-truck me-1"></i> Información de Tolva
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Marca</label>
              <select id="guia_marca_tolva" class="form-select form-select-sm">
                <option value="">Seleccione...</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Empresa Transp.</label>
              <select id="guia_empresa_tolva" class="form-select form-select-sm"
                data-placeholder="Elija una opción...">
                <option value="">Seleccione...</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Placa</label>
              <div class="input-group input-group-sm">
                <input id="guia_serie_tolva" type="text" class="form-control" placeholder="Serie" style="text-transform: uppercase;">
                <span class="input-group-text">-</span>
                <input id="guia_numero_tolva" type="text" class="form-control" placeholder="Número" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold">N° MTC</label>
              <input id="guia_mtc_tolva" type="text" class="form-control form-control-sm"
                style="text-transform: uppercase;">
            </div>
          </div>

          <!-- Sección 5: Tabla de Lotes (Read-only) -->
          <div class="row">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-box-seam me-1"></i> Lotes Asociados
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-12">
              <div class="mb-2 d-flex gap-3 align-items-center" id="div_resumen_modal">
              </div>
              <div style="max-height: 250px; overflow-y: auto;">
                <table class="table table-bordered table-sm tabla-lotes-guia mb-0">
                  <thead class="sticky-top">
                    <tr>
                      <th class="text-center" style="width: 30px;">N°</th>
                      <th class="text-center">Tipo</th>
                      <th>Código</th>
                      <th>Despacho</th>
                      <th class="text-center">Presentación</th>
                      <th class="text-end">Tomado (Kg)</th>
                      <th class="text-end">Bruto (Kg)</th>
                      <th class="text-end">Tara (Kg)</th>
                      <th class="text-end fw-bold">Neto (Kg)</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_modal_lotes">
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <div id="wt_grabando_guia" style="display: none;">
            <span class="spinner-border spinner-border-sm text-primary"></span>
            <span class="text-muted small fst-italic">Procesando...</span>
          </div>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cerrar
          </button>
          <button type="button" class="btn btn-primary btn-sm" id="btn_emitir_guia" onclick="f_EmitirGuia();">
            <i class="bi bi-check2-circle me-1"></i>Emitir Guía
          </button>
        </div>
      </div>
    </div>
  </div>


  <!-- ======================= MODAL: EDITAR GUÍA ======================= -->
  <div class="modal fade" id="modal_editar_guia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modal_editar_guiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header" style="background: #1a3a5c; color: #fff;">
          <h5 class="modal-title" id="modal_editar_guiaLabel">
            <i class="bi bi-pencil-square me-2"></i>Editar Guía de Remisión
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">

          <input type="hidden" id="hd_modo_guia_e" value="E">
          <input type="hidden" id="hd_id_guia_e" value="0">
          <input type="hidden" id="hd_ids_distribuciones_e" value="[]">

          <!-- Sección 1: Información de Fechas -->
          <div class="row g-3 mb-3">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-calendar3 me-1"></i> Información de Fechas
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Fecha Inicio Traslado</label>
              <input id="guia_e_fecha_inicio_traslado" type="date" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Fecha Emisión</label>
              <div class="input-group input-group-sm">
                <input id="guia_e_fecha_emision" type="date" class="form-control">
                <input id="guia_e_hora_emision" type="time" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Fecha/Hora en Planta</label>
              <div class="input-group input-group-sm">
                <input id="guia_e_fecha_planta" type="date" class="form-control">
                <input id="guia_e_hora_planta" type="time" class="form-control">
              </div>
            </div>
          </div>

          <!-- Sección 2: Planta y Guías -->
          <div class="row g-3 mb-3">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-building me-1"></i> Planta y Guías
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Planta Origen</label>
              <select id="guia_e_planta_origen" class="form-select form-select-sm">
                <option value="">Seleccione...</option>
                <option value="1">Huanchaco</option>
                <option value="2">Laredo</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Planta Destino</label>
              <input type="text" id="guia_e_planta_destino" class="form-control form-control-sm bg-light" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Guía Remitente</label>
              <div class="input-group input-group-sm">
                <input id="guia_e_rem_serie" type="text" class="form-control" placeholder="Serie"
                  style="text-transform: uppercase;">
                <span class="input-group-text">-</span>
                <input id="guia_e_rem_numero" type="text" class="form-control" placeholder="Número"
                  style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Guía Transportista</label>
              <div class="input-group input-group-sm">
                <input id="guia_e_transp_serie" type="text" class="form-control guia_grt_field_e" placeholder="Serie"
                  style="text-transform: uppercase;">
                <span class="input-group-text">-</span>
                <input id="guia_e_transp_numero" type="text" class="form-control guia_grt_field_e" placeholder="Número"
                  style="text-transform: uppercase;">
              </div>
              <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" id="chk_sin_grt_e">
                <label class="form-check-label small" for="chk_sin_grt_e">Sin Guía Transportista</label>
              </div>
            </div>
          </div>

          <!-- Sección 3: Motivo de Traslado -->
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label small fw-bold">Motivo de Traslado</label>
              <select id="guia_e_motivo_traslado" class="form-select form-select-sm">
                <option value="">Seleccione...</option>
                <option value="VENTA SUJETA A CONFIRMACIÓN">VENTA SUJETA A CONFIRMACIÓN</option>
                <option value="SERVICIO DE CHANCADO">SERVICIO DE CHANCADO</option>
                <option value="TRASLADO ENTRE ESTABLECIMIENTOS">TRASLADO ENTRE ESTABLECIMIENTOS</option>
              </select>
            </div>
          </div>

          <!-- Sección 1-B: Información de la Unidad (Lectura) -->
          <div class="row g-3 mt-3 mb-3 bg-light p-2 rounded border mx-0">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-1">
                <i class="bi bi-truck-flatbed me-1"></i> Información de la Unidad
              </h6>
            </div>
            <div class="col-md-5">
              <label class="form-label small fw-bold mb-0">Empresa Transporte</label>
              <input type="text" id="guia_e_unit_transportista" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold mb-0">Marca</label>
              <input type="text" id="guia_e_unit_marca" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold mb-0">Placa</label>
              <input type="text" id="guia_e_unit_placa" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold mb-0">MTC</label>
              <input type="text" id="guia_e_unit_mtc" class="form-control form-control-sm" readonly>
            </div>
          </div>

          <!-- Sección 4: Información de Tolva -->
          <div class="row g-3 mb-3">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-truck me-1"></i> Información de Tolva
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Marca Tolva</label>
              <select id="guia_e_marca_tolva" class="form-select form-select-sm">
                <option value="">Seleccione...</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold">Empresa Transp. Tolva</label>
              <select id="guia_e_empresa_tolva" class="form-select form-select-sm"
                data-placeholder="Elija una opción...">
                <option value="">Seleccione...</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold">Placa Tracto / Carreta</label>
              <div class="input-group input-group-sm">
                <input id="guia_e_serie_tolva" type="text" class="form-control" placeholder="Tracto" style="text-transform: uppercase;">
                <span class="input-group-text">-</span>
                <input id="guia_e_numero_tolva" type="text" class="form-control" placeholder="Carreta" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold">N° MTC Tolva</label>
              <input id="guia_e_mtc_tolva" type="text" class="form-control form-control-sm"
                style="text-transform: uppercase;">
            </div>
          </div>

          <!-- Sección 5: Tabla de Lotes (Read-only) -->
          <div class="row">
            <div class="col-12">
              <h6 class="fw-bold text-uppercase text-muted small mb-2">
                <i class="bi bi-box-seam me-1"></i> Lotes Asociados
              </h6>
              <hr class="mt-0 mb-2" style="border-color: #ddd;">
            </div>
            <div class="col-12">
              <div class="mb-2 d-flex gap-3 align-items-center" id="div_resumen_modal_e">
              </div>
              <div style="max-height: 250px; overflow-y: auto;">
                <table class="table table-bordered table-sm tabla-lotes-guia mb-0">
                  <thead class="sticky-top">
                    <tr>
                      <th class="text-center" style="width: 30px;">N°</th>
                      <th class="text-center">Tipo</th>
                      <th>Código</th>
                      <th>Despacho</th>
                      <th class="text-center">Presentación</th>
                      <th class="text-end">Peso (Kg)</th>
                      <th class="text-end">Bruto (Kg)</th>
                      <th class="text-end">Tara (Kg)</th>
                      <th class="text-end fw-bold">Neto (Kg)</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_modal_lotes_e">
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <div id="wt_grabando_guia_e" style="display: none;">
            <span class="spinner-border spinner-border-sm text-primary"></span>
            <span class="text-muted small fst-italic">Procesando...</span>
          </div>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cerrar
          </button>
          <button type="button" class="btn btn-primary btn-sm" id="btn_guardar_edicion_guia" onclick="f_GuardarEdicionGuia();">
            <i class="bi bi-check2-circle me-1"></i>Guardar Cambios
          </button>
        </div>
      </div>
    </div>
  </div>


  <!-- Elementos ocultos necesarios para auxiliares_js.php -->
  <select id="voiceList" style="display:none;"></select>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>

  <?php include('global/auxiliares_js.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <script>
    const backendUrl = '<?php echo $backendUrl; ?>';
  </script>
  <script src="despachossegundotramo_gestionguias.js"></script>
</body>

</html>