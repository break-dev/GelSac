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
              <button class="btn btn-primary" id="btn_open_new_despacho_modal">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Despacho
              </button>
            </div>
          </div>

          <!-- Columna Izquierda: Lista de Despachos -->
          <div class="col-md-5">
            <div class="bg-white shadow-sm p-3 rounded h-100">
              <h5 class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-task"></i> Historial de Despachos</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="loadAllDespachos()" title="Recargar"><i
                    class="bi bi-arrow-clockwise"></i></button>
              </h5>
              <hr class="my-2" />

              <div class="table-responsive" style="height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-hover table-sm">
                  <thead class="sticky-top">
                    <tr style="font-size: 13px;">
                      <th class="header-bg-primary text-center">Código</th>
                      <th class="header-bg-primary text-center">Planta Destino</th>
                      <th class="header-bg-primary text-center">Fecha</th>
                      <th class="header-bg-primary text-center">Items</th>
                      <th class="header-bg-primary text-center">Estado</th>
                      <th class="header-bg-primary text-center" width="50">Ver</th>
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
          <div class="col-md-7">
            <div class="d-flex flex-column h-100">

              <!-- Panel Superior: Detalle de Minerales -->
              <div class="bg-white shadow-sm p-3 rounded mb-3 flex-grow-1">
                <h5 class="d-flex justify-content-between align-items-center">
                  <span><i class="bi bi-box-seam"></i> Detalle del Despacho: <span id="lbl_despacho_seleccionado"
                      class="text-primary fw-bold">---</span></span>
                </h5>
                <div id="info_provider_plant" class="mb-2 text-muted small fst-italic">Seleccione un despacho para ver
                  detalles.</div>
                <hr class="my-2" />

                <div class="table-responsive" style="max-height: 40vh; overflow-y: auto;">
                  <table class="table table-bordered table-striped table-sm">
                    <thead class="sticky-top">
                      <tr style="font-size: 13px;">
                        <th class="header-bg-secondary text-center">Tipo</th>
                        <th class="header-bg-secondary text-center">Código Mineral</th>
                        <th class="header-bg-secondary text-center">Peso Original</th>
                        <th class="header-bg-secondary text-center">Peso Despachado</th>
                      </tr>
                    </thead>
                    <tbody id="tbl_detalle_despacho" style="font-size: 13px;">
                      <tr>
                        <td colspan="4" class="text-center text-muted p-3">---</td>
                      </tr>
                    </tbody>
                    <tfoot id="tfoot_detalle_despacho" style="display:none;">
                      <tr class="fw-bold table-light">
                        <td colspan="3" class="text-end">Total Despachado:</td>
                        <td class="text-end" id="lbl_total_peso_despacho">0.00</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <!-- Panel Inferior: Distribuciones (Placeholder) -->
              <div class="bg-white shadow-sm p-3 rounded flex-grow-1 footer-panel">
                <h5 class="text-muted"><i class="bi bi-diagram-3"></i> Distribuciones (Próximamente)</h5>
                <hr class="my-2" />
                <div class="text-center p-4 text-muted bg-light border border-dashed rounded">
                  <i class="bi bi-cone-striped fs-1 d-block mb-2"></i>
                  Esta sección permitirá gestionar las distribuciones asociadas al despacho seleccionado.
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
                <div class="col-md-5">
                  <label class="form-label fw-bold small text-uppercase text-muted">2. Proveedor</label>
                  <select id="reg_proveedor" class="form-select" data-bs-theme="bootstrap-5" disabled></select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button class="btn btn-secondary w-100" id="btn_buscar_minerales" disabled>
                    <i class="bi bi-search"></i> Listar Minerales
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Paso 2: Selección de Minerales -->
          <h6 class="border-bottom pb-2 mb-2">3. Seleccione Minerales (Lotes o Blendings) y Peso a Despachar</h6>
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
                  <td colspan="5" class="text-center text-muted py-4">Configure la Planta y el Proveedor para cargar
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

  <?php include('global/auxiliares_js.php'); ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {

      // VARIABLES GLOBALES
      const backendUrl = '<?php echo $backendUrl; ?>';
      const modalNuevoDespacho = new bootstrap.Modal(document.getElementById("modal_nuevo_despacho"));

      let allDespachos = [];
      let mineralesDisponibles = [];

      // FUNCIONES AUXILIARES
      function f_callBackend(accion, data) {
        return $.post(backendUrl, { accion: accion, ...data }, 'json');
      }

      function formatNumber(num) {
        return parseFloat(num).toFixed(2);
      }

      // --------------------------------------------------------------------------------
      // VIEW LOGIC: LISTADO PRINCIPAL
      // --------------------------------------------------------------------------------

      window.loadAllDespachos = function () {
        $("#tbl_despachos").html('<tr><td colspan="6" class="text-center p-3">Actualizando...</td></tr>');

        f_callBackend('get_lista_despacho_cabecera', {})
          .done(function (r) {
            if (r.estado === 1) {
              allDespachos = r.data.despachos;
              renderDespachos();
            } else {
              console.error("Error al cargar despachos");
            }
          })
          .fail(function () {
            $("#tbl_despachos").html('<tr><td colspan="6" class="text-center text-danger p-3">Error de conexión.</td></tr>');
          });
      };

      function renderDespachos() {
        let html = '';
        if (allDespachos.length === 0) {
          html = '<tr><td colspan="6" class="text-center text-muted p-3">No hay despachos registrados.</td></tr>';
        } else {
          // Group by Provider for visual clarity if needed, or straight list
          // Let's verify if we need grouping. The user asked for "buen estilado".
          // Grouping by Date or Provider is nice. Let's stick to simple list ordered by ID desc as per query for now but distinct rows.

          allDespachos.forEach(d => {
            let totalItems = parseInt(d.blending_usados) + parseInt(d.lotes_usados);
            let estadoBadge = '<span class="badge bg-success">Activo</span>';

            html += `
                  <tr class="clickable-row" onclick="selectDespacho(${d.id_despacho}, this)" data-id="${d.id_despacho}">
                      <td class="text-center fw-bold">${d.correlativo}</td>
                      <td>
                        <div class="fw-bold small">${d.descripcion_planta}</div>
                        <div class="text-muted" style="font-size: 0.75em;">${d.ruc_planta}</div>
                      </td>
                      <td class="text-center small">${d.fecha_registro}</td>
                      <td class="text-center">
                        <span class="badge bg-secondary rounded-pill">${totalItems} Items</span>
                      </td>
                      <td class="text-center">${estadoBadge}</td>
                      <td class="text-center">
                        <button class="btn btn-sm btn-link text-primary"><i class="bi bi-eye-fill"></i></button>
                      </td>
                  </tr>
                  `;
          });
        }

        $("#tbl_despachos").html(html);
        $("#total_despachos").text(allDespachos.length);
      }

      window.selectDespacho = function (id, rowElem) {
        // Highlight Row
        if (rowElem) {
          $(".clickable-row").removeClass("selected");
          $(rowElem).addClass("selected");
        }

        let d = allDespachos.find(x => x.id_despacho == id);
        if (!d) return;

        // Header Info
        $("#lbl_despacho_seleccionado").text(d.correlativo);
        $("#info_provider_plant").html(`
            <strong>Planta:</strong> ${d.descripcion_planta} &nbsp;|&nbsp; 
            <strong>Proveedor:</strong> ${d.razon_social} (${d.documento_proveedor})
          `);

        // Load Details
        $("#tbl_detalle_despacho").html('<tr><td colspan="4" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando detalle...</td></tr>');

        f_callBackend('get_despacho_detalle_by_despacho', { id_despacho: id })
          .done(function (r) {
            if (r.estado === 1) {
              renderDetalleDespacho(r.data.detalle_blending); // reusing name from backend response structure
            }
          });
      };

      function renderDetalleDespacho(detalles) {
        let html = '';
        let totalPeso = 0;

        if (!detalles || detalles.length === 0) {
          html = '<tr><td colspan="4" class="text-center text-muted">Sin detalles.</td></tr>';
        } else {
          detalles.forEach(item => {
            let isBlending = (item.is_blending == 1);
            let badge = isBlending
              ? '<span class="badge-mineral-type badge-blending">Blending</span>'
              : '<span class="badge-mineral-type badge-lote">Lote</span>';

            html += `
                  <tr>
                      <td class="text-center">${badge}</td>
                      <td class="fw-bold text-dark">${item.codigo}</td>
                      <td class="text-end text-muted">${formatNumber(item.peso_actual_log)}</td>
                      <td class="text-end fw-bold text-primary">${formatNumber(item.peso_tomado)}</td>
                  </tr>
                  `;
            totalPeso += parseFloat(item.peso_tomado);
          });
        }

        $("#tbl_detalle_despacho").html(html);
        $("#lbl_total_peso_despacho").text(formatNumber(totalPeso));
        $("#tfoot_detalle_despacho").show();
      }


      // --------------------------------------------------------------------------------
      // MODAL LOGIC: NUEVO DESPACHO
      // --------------------------------------------------------------------------------

      // Paso 0: Abrir Modal -> Cargar Plantas
      $("#btn_open_new_despacho_modal").click(function () {
        // Reset UI
        $("#reg_planta").empty().append('<option value="">Cargando...</option>');
        $("#reg_proveedor").empty().prop('disabled', true);
        $("#btn_buscar_minerales").prop('disabled', true);
        $("#tbl_minerales_disponibles").html('<tr><td colspan="5" class="text-center text-muted py-4">Seleccione planta y proveedor.</td></tr>');
        $("#lbl_total_modal").text("0.00");
        $("#btn_crear_despacho").prop('disabled', true);

        // Get Plantas
        f_callBackend('get_plantas_to_despachos', {})
          .done(function (r) {
            if (r.estado === 1) {
              let opts = r.data.plantas.map(p => ({ id: p.id_planta, text: `${p.descripcion} (RUC: ${p.ruc})` }));

              $("#reg_planta").empty().select2({
                dropdownParent: $('#modal_nuevo_despacho'),
                theme: "bootstrap-5",
                placeholder: "Seleccione Planta Destino",
                data: opts,
                width: '100%'
              }).val('').trigger('change');
            }
          });

        modalNuevoDespacho.show();
      });

      // Paso 1: Configurar Change de Planta -> Cargar Proveedores
      $("#reg_planta").on('change', function () {
        let idPlanta = $(this).val();

        $("#reg_proveedor").empty().prop('disabled', true);
        $("#btn_buscar_minerales").prop('disabled', true);

        if (idPlanta) {
          f_callBackend('get_proveedores_by_planta', { id_planta: idPlanta })
            .done(function (r) {
              if (r.estado === 1) {
                let opts = r.data.proveedores.map(p => ({ id: p.id_proveedor, text: `${p.razon_social} (${p.documento})` }));
                $("#reg_proveedor").prop('disabled', false).select2({
                  dropdownParent: $('#modal_nuevo_despacho'),
                  theme: "bootstrap-5",
                  placeholder: "Seleccione Proveedor",
                  data: opts,
                  width: '100%'
                }).val('').trigger('change');
              }
            });
        }
      });

      // Paso 2: Configurar Change de Proveedor -> Activar Botón Buscar
      $("#reg_proveedor").on('change', function () {
        let val = $(this).val();
        $("#btn_buscar_minerales").prop('disabled', !val);
        // Limpiar tabla si cambia proveedor
        $("#tbl_minerales_disponibles").html('<tr><td colspan="5" class="text-center text-muted py-4">Haga clic en Listar Minerales.</td></tr>');
      });

      // Paso 3: Click Buscar Minerales
      $("#btn_buscar_minerales").click(function () {
        let idProv = $("#reg_proveedor").val();
        if (!idProv) return;

        let $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        f_callBackend('get_minerales_to_despacho_by_proveedor', { id_proveedor: idProv })
          .done(function (r) {
            $btn.prop('disabled', false).html('<i class="bi bi-search"></i> Listar Minerales');

            if (r.estado === 1) {
              mineralesDisponibles = r.data.minerales;
              renderMineralesTable();
            } else {
              alert("Error al obtener minerales.");
            }
          });
      });

      function renderMineralesTable() {
        let html = '';
        if (mineralesDisponibles.length === 0) {
          html = '<tr><td colspan="5" class="text-center">El proveedor no tiene minerales (Lotes con saldo o Blendings activos) disponibles.</td></tr>';
        } else {
          mineralesDisponibles.forEach((m, index) => {
            let isBlending = (m.is_blending == 1); // Ensure boolean check works with response
            let badge = isBlending
              ? '<span class="badge-mineral-type badge-blending">Blending</span>'
              : '<span class="badge-mineral-type badge-lote">Lote</span>';

            // Use unique ID for row/checkbox
            let uniqueId = `min_${isBlending ? 'B' : 'L'}_${m.id_mineral}`;

            html += `
                  <tr class="${isBlending ? 'table-info' : ''}">
                      <td class="text-center">
                          <input type="checkbox" class="form-check-input chk-min" 
                            id="${uniqueId}"
                            data-idx="${index}">
                      </td>
                      <td>
                        <label class="form-check-label w-100 cursor-pointer" for="${uniqueId}">
                            ${m.codigo}
                        </label>
                      </td>
                      <td>${badge}</td>
                      <td class="text-end font-monospace">${formatNumber(m.peso_actual)}</td>
                      <td>
                          <input type="number" 
                            class="form-control form-control-sm text-end input-peso-despacho" 
                            placeholder="0.00"
                            step="0.01"
                            disabled
                            data-max="${m.peso_actual}"
                            data-idx="${index}">
                      </td>
                  </tr>
                  `;
          });
        }
        $("#tbl_minerales_disponibles").html(html);
        updateTotalModal();
      }

      // Paso 4: Interacción en Tabla (Checkbox / Input)
      $(document).on('change', '.chk-min', function () {
        let idx = $(this).data('idx');
        let checked = $(this).is(':checked');

        let $input = $(`.input-peso-despacho[data-idx='${idx}']`);
        $input.prop('disabled', !checked);

        if (checked) {
          // Auto-fill max weight for convenience? Maybe not, better safe 0 or empty.
          $input.focus();
        } else {
          $input.val('');
        }
        updateTotalModal();
      });

      $(document).on('input', '.input-peso-despacho', function () {
        let max = parseFloat($(this).data('max'));
        let val = parseFloat($(this).val());

        if (val < 0) $(this).val(0);
        if (val > max) {
          alert("No puede despachar más del peso actual: " + max);
          $(this).val(max);
        }
        updateTotalModal();
      });

      function updateTotalModal() {
        let total = 0;
        $(".input-peso-despacho:enabled").each(function () {
          let v = parseFloat($(this).val());
          if (!isNaN(v)) total += v;
        });
        $("#lbl_total_modal").text(formatNumber(total));
        $("#btn_crear_despacho").prop('disabled', total <= 0);
      }

      // Paso 5: CREAR DESPACHO
      $("#btn_crear_despacho").click(function () {
        let idPlanta = $("#reg_planta").val();
        let idProveedor = $("#reg_proveedor").val();

        let payload = {
          id_planta: idPlanta,
          id_proveedor: idProveedor,
          minerales: []
        };

        $(".input-peso-despacho:enabled").each(function () {
          let idx = $(this).data('idx');
          let val = parseFloat($(this).val());

          if (val > 0) {
            let mineralData = mineralesDisponibles[idx];
            payload.minerales.push({
              id_mineral: mineralData.id_mineral,
              is_blending: mineralData.is_blending, // Ensure this passes true/false or 1/0 correctly
              peso_tomado: val
            });
          }
        });

        if (payload.minerales.length === 0) return;

        if (!confirm("¿Confirmar Creación de Despacho?")) return;

        let $btn = $(this);
        $btn.prop('disabled', true).text("Creando...");

        f_callBackend('crear_despacho', payload)
          .done(function (r) {
            if (r.estado === 1) {
              alert("Despacho Creado Correctamente: " + r.data.correlativo);
              modalNuevoDespacho.hide();
              loadAllDespachos();
            } else {
              alert("Error: " + r.mensaje);
            }
          })
          .always(function () {
            $btn.prop('disabled', false).html('<i class="bi bi-check-lg"></i> Crear Despacho');
          });
      });


      // INIT
      function init() {
        f_GetMenuPrincipal();
        $("#nv_titulo").html('| Despachos');
        loadAllDespachos();
      }

      init();
    });
  </script>
</body>

</html>