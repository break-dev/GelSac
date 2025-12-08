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

  <title><?php echo $nom_app; ?> | Anticipos a proveedores</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  <link rel="stylesheet" href="<?php echo $url_lims; ?>/global/styles.css">

  <style>
    /* Estilos de cabecera para las tablas */
    .header-bg-proveedor {
      background-color: #2980b9 !important;
      /* Azul claro */
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    .header-bg-anticipo {
      background-color: #e67e22 !important;
      /* Naranja */
      color: #fff;
      font-weight: 600;
      vertical-align: middle;
    }

    /* Estilo para las filas seleccionables de proveedor */
    .proveedor-row:hover {
      background-color: #e0f7fa;
      cursor: pointer;
    }

    .proveedor-row.selected {
      background-color: #b3e5fc;
      /* Azul más claro para seleccionado */
      font-weight: bold;
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

      <div class="col-md-12 col-sm-12 col-xs-12" style="padding-top: 10px; padding-left: 15px; padding-right: 15px;">
        <div class="d-flex row">

          <div class="row bg-white shadow-sm p-3 mb-3 rounded">
            <h5><i class="bi bi-bank"></i> Resumen General de Anticipos</h5>
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
              <button class="btn btn-success btn-sm ms-auto" id="btn_open_new_anticipo_modal">
                <i class="bi bi-plus-circle me-1"></i> Registrar Nuevo Anticipo
              </button>
            </div>
          </div>

          <div class="col-md-5">
            <div class="bg-white shadow-sm p-3 rounded">
              <h5 class="d-inline-block"><i class="bi bi-people"></i> Proveedores con Anticipos</h5>
              <hr style="border-color: #D9D9D9; margin-top: 5px; margin-bottom: 10px;" />

              <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr style="font-size: 13px;">
                      <th class="header-bg-proveedor text-center" rowspan="2">Razón Social</th>
                      <th class="header-bg-proveedor text-center" rowspan="2">Documento</th>
                      <th class="header-bg-anticipo text-center" colspan="3">Anticipos</th>
                      <th class="header-bg-proveedor text-center" rowspan="2">Acciones</th>
                    </tr>
                    <tr style="font-size: 12px;">
                      <th class="header-bg-anticipo text-center">Total</th>
                      <th class="header-bg-anticipo text-center">Con Saldo</th>
                      <th class="header-bg-anticipo text-center">Sin Saldo</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_proveedores_con_anticipos" style="font-size: 13px;">
                    <tr>
                      <td colspan="6" class="text-center">Cargando proveedores...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-md-7">
            <div class="bg-white shadow-sm p-3 rounded">
              <h5 class="d-inline-block"><i class="bi bi-wallet"></i> Anticipos del Proveedor: <span id="proveedor_seleccionado_rs" class="text-primary">---</span></h5>
              <hr style="border-color: #D9D9D9; margin-top: 5px; margin-bottom: 10px;" />

              <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr style="font-size: 13px;">
                      <th class="header-bg-anticipo text-center">Factura</th>
                      <th class="header-bg-anticipo text-center">Saldo Inicial</th>
                      <th class="header-bg-anticipo text-center">Saldo Actual</th>
                      <th class="header-bg-anticipo text-center">Transacciones</th>
                      <th class="header-bg-anticipo text-center">Fecha Registro</th>
                      <th class="header-bg-anticipo text-center">Estado</th>
                      <th class="header-bg-anticipo text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_anticipos_proveedor" style="font-size: 13px;">
                    <tr>
                      <td colspan="7" class="text-center" id="msg_anticipos_proveedor">Seleccione un proveedor en el panel izquierdo.</td>
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

  <div class="modal fade" id="modal_nuevo_anticipo" tabindex="-1" aria-labelledby="modal_nuevo_anticipo_Label" aria-hidden="true">
    <div class="modal-dialog" style="position: fixed; top: 30%; left: 50%; transform: translate(-50%, -55%);">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modal_nuevo_anticipo_Label"><i class="bi bi-cash-coin"></i> Registrar Nuevo Anticipo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="reg_proveedor" class="form-label">Proveedor (Minero)</label>
            <select id="reg_proveedor" class="form-select" data-bs-theme="bootstrap-5"></select>
          </div>

          <div class="row mb-3">
            <label for="factura" class="form-label">Factura</label>
            <div class="input-group" id="factura-group">
              <input type="text" class="form-control text-uppercase col-3" id="reg_serie_factura" placeholder="FA01">
              <span class="input-group-text">-</span>
              <input type="text" class="form-control" id="reg_numero_factura" placeholder="00001234">
            </div>
          </div>

          <div class="mb-3">
            <label for="reg_saldo_inicial" class="form-label">Saldo Inicial (USD)</label>
            <input type="number" step="0.01" class="form-control" id="reg_saldo_inicial" placeholder="Ej. 10000.00">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
          <button type="button" class="btn btn-success" id="btn_guardar_anticipo"><i class="bi bi-save"></i> Grabar Anticipo</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_ver_transacciones" tabindex="-1" aria-labelledby="modal_ver_transacciones_Label" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="position: fixed; top: 30%; left: 50%; transform: translate(-50%, -55%);">
      <div class="modal-content">
        <div class="modal-header header-bg-anticipo text-white">
          <h5 class="modal-title" id="modal_ver_transacciones_Label"><i class="bi bi-clipboard-data"></i> Transacciones del Anticipo: <span id="modal_anticipo_factura"></span></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">Saldo Inicial: <strong id="modal_transaccion_saldo_inicial" class="text-primary">---</strong> | Saldo Actual: <strong id="modal_transaccion_saldo_actual" class="text-success">---</strong></p>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr style="font-size: 13px;">
                  <th class="header-bg-proveedor text-center">Nro</th>
                  <th class="header-bg-proveedor text-center">Código Valorización</th>
                  <th class="header-bg-proveedor text-center">Procedencia</th>
                  <th class="header-bg-proveedor text-center">Concesión</th>
                  <th class="header-bg-anticipo text-center">Saldo Anterior (USD)</th>
                  <th class="header-bg-anticipo text-center" style="font-weight: 600; font-size: 15px;">Monto Retirado (USD)</th>
                  <th class="header-bg-anticipo text-center">Saldo Restante (USD)</th>
                  <th class="header-bg-proveedor text-center">Fecha Registro</th>
                </tr>
              </thead>
              <tbody id="tbl_transacciones" style="font-size: 13px;">
                <tr>
                  <td colspan="8" class="text-center">No hay transacciones registradas para este anticipo.</td>
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

  <?php include('global/auxiliares_js.php'); ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>


  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {

      // -------------------------
      // Variables Globales
      // -------------------------

      let allData = {};
      let cantidad_anticipos = 0;
      let anticipos_con_saldo = 0;
      let anticipos_sin_saldo = 0;
      let proveedores_aptos = [];
      let proveedores_con_anticipos = [];

      let proveedor_seleccionado = null;
      let anticipo_seleccionado = null;

      const backendUrl = '<?php echo $backendUrl; ?>'; // Endpoint de PHP
      const nuevoAnticipoModal = new bootstrap.Modal(document.getElementById("modal_nuevo_anticipo"));
      const transaccionesModal = new bootstrap.Modal(document.getElementById("modal_ver_transacciones"));


      // -------------------------
      // Funciones Auxiliares de Datos
      // -------------------------

      function f_callBackend(accion, data) {
        return $.post(backendUrl, {
          accion: accion,
          ...data
        }, 'json');
      }

      function formatCurrency(amount) {
        // Formato simple de moneda (asumo USD por la tabla)
        return 'USD ' + parseFloat(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }

      function findProveedorById(id) {
        return proveedores_con_anticipos.find(p => String(p.id_proveedor) === String(id));
      }

      function findAnticipoById(proveedor, id) {
        if (!proveedor || !proveedor.anticipos) return null;
        return proveedor.anticipos.find(a => String(a.id_anticipo) === String(id));
      }

      // -------------------------
      // Lógica de Renderizado
      // -------------------------

      function renderGeneralSummary() {
        $("#total_anticipos").text(cantidad_anticipos);
        $("#anticipos_con_saldo").text(anticipos_con_saldo);
        $("#anticipos_sin_saldo").text(anticipos_sin_saldo);
      }

      function renderProveedores() {
        let html = '';
        if (proveedores_con_anticipos.length === 0) {
          html = '<tr><td colspan="6" class="text-center">No hay proveedores con anticipos registrados.</td></tr>';
        }

        // Ordenar por razón social para mejor usabilidad
        proveedores_con_anticipos.sort((a, b) => a.razon_social.localeCompare(b.razon_social));

        proveedores_con_anticipos.forEach(p => {
          html += `
            <tr class="proveedor-row" data-id-proveedor="${p.id_proveedor}" id="prov_row_${p.id_proveedor}">
              <td>${p.razon_social}</td>
              <td>${p.documento}</td>
              <td class="text-center">${p.cantidad_anticipos}</td>
              <td class="text-center text-success">${p.anticipos_con_saldo}</td>
              <td class="text-center text-danger">${p.anticipos_sin_saldo}</td>
              <td class="text-center">
                <button class="btn btn-sm btn-primary btn-view-anticipos" data-id-proveedor="${p.id_proveedor}" title="Ver Anticipos">
                  <i class="bi bi-eye"></i>
                </button>
              </td>
            </tr>`;
        });

        $("#tbl_proveedores_con_anticipos").html(html);
      }

      function renderAnticipos(proveedor) {
        // Limpiar la selección anterior en la UI
        $(".proveedor-row").removeClass('selected');

        if (!proveedor) {
          $("#proveedor_seleccionado_rs").text('---');
          $("#tbl_anticipos_proveedor").html(`<tr><td colspan="7" class="text-center" id="msg_anticipos_proveedor">Seleccione un proveedor en el panel izquierdo.</td></tr>`);
          return;
        }

        // Resaltar la fila seleccionada
        $(`#prov_row_${proveedor.id_proveedor}`).addClass('selected');

        // Mostrar el nombre del proveedor
        $("#proveedor_seleccionado_rs").text(proveedor.razon_social);

        let html = '';

        if (proveedor.anticipos.length === 0) {
          html = '<tr><td colspan="7" class="text-center">Este proveedor no tiene anticipos.</td></tr>';
        }

        // Ordenar anticipos por fecha de registro (más reciente primero)
        const anticiposOrdenados = [...proveedor.anticipos].sort((a, b) => {
          const dateA = a.id_anticipo;
          const dateB = b.id_anticipo;
          return dateB - dateA;
        });


        anticiposOrdenados.forEach(a => {
          // Asumo 'A' es CON SALDO y 'B' es SIN SALDO
          const estadoText = a.estado === 'A' ? 'Con saldo' : 'Sin saldo';
          const estadoClass = a.estado === 'A' ? 'text-success' : 'text-danger';

          html += `
            <tr>
              <td>${a.serie_factura}-${a.numero_factura}</td>
              <td class="text-end">${formatCurrency(a.saldo_inicial)}</td>
              <td class="text-end">${formatCurrency(a.saldo_actual)}</td>
              <td class="text-center">${a.cantidad_transacciones}</td>
              <td class="text-center">${a.fecha_registro}</td>
              <td class="text-center ${estadoClass}"><strong>${estadoText}</strong></td>
              <td class="text-center">
                <button class="btn btn-sm btn-info text-white btn-view-transacciones"
                        data-id-anticipo="${a.id_anticipo}"
                        data-id-proveedor="${proveedor.id_proveedor}"
                        title="Ver Transacciones">
                  <i class="bi bi-receipt"></i>
                </button>
              </td>
            </tr>`;
        });

        $("#tbl_anticipos_proveedor").html(html);
      }

      function renderTransaccionesModal(anticipo) {
        $("#modal_anticipo_factura").text(`${anticipo.serie_factura}-${anticipo.numero_factura}`);
        $("#modal_transaccion_saldo_inicial").text(formatCurrency(anticipo.saldo_inicial));
        $("#modal_transaccion_saldo_actual").text(formatCurrency(anticipo.saldo_actual));

        let html = '';
        if (anticipo.transacciones.length === 0) {
          html = '<tr><td colspan="8" class="text-center">No hay transacciones registradas para este anticipo.</td></tr>';
        }

        // Transacciones deben venir ordenadas por el backend (por ID de transacción ASC)
        anticipo.transacciones.forEach((t, index) => {
          html += `
            <tr>
              <td class="text-center">${index + 1}</td>
              <td>${t.codigo_unico}</td>
              <td>${t.procedencia}</td>
              <td>${t.concesion}</td>
              <td class="text-end">${formatCurrency(t.saldo_actual)}</td>
              <td class="text-end text-danger"><strong>${formatCurrency(t.monto_retirado)}</strong></td>
              <td class="text-end">${formatCurrency(t.saldo_restante)}</td>
              <td>${t.fecha_registro}</td>
            </tr>`;
        });

        $("#tbl_transacciones").html(html);
        transaccionesModal.show();
      }

      function loadSelect2Providers() {
        const data = proveedores_aptos.map(p => ({
          id: p.id_proveedor,
          text: `${p.razon_social} (${p.documento})`
        }));

        $("#reg_proveedor").select2({
          dropdownParent: $('#modal_nuevo_anticipo'),
          theme: "bootstrap-5", // Usar el tema de Bootstrap 5
          placeholder: 'Buscar o seleccionar un proveedor...',
          data: data,
          allowClear: true
        });
      }


      // -------------------------
      // Lógica de Eventos
      // -------------------------

      // 1. Carga Inicial de Datos
      function loadAllData() {
        f_callBackend('getAnticiposAndProviders', {})
          .done(function(r) {
            if (r.estado === 1 && r.data) {
              allData = r.data;
              cantidad_anticipos = parseInt(allData.cantidad_anticipos || 0);
              anticipos_con_saldo = parseInt(allData.anticipos_con_saldo || 0);
              anticipos_sin_saldo = parseInt(allData.anticipos_sin_saldo || 0);
              proveedores_aptos = allData.proveedores || [];
              proveedores_con_anticipos = allData.proveedores_con_anticipos || [];

              renderGeneralSummary();
              renderProveedores();
              loadSelect2Providers();

              // Si había un proveedor seleccionado previamente (mantener el estado después de una recarga)
              if (proveedor_seleccionado) {
                const updatedProv = findProveedorById(proveedor_seleccionado.id_proveedor);
                if (updatedProv) {
                  proveedor_seleccionado = updatedProv;
                  renderAnticipos(proveedor_seleccionado);
                }
              }

            } else {
              alert("Error al cargar datos iniciales: " + (r.msg || "Respuesta incompleta del servidor."));
            }
          })
          .fail(function() {
            alert("Error de conexión al cargar datos de anticipos.");
          });
      }

      // 2. Evento: Seleccionar Proveedor (para ver sus anticipos)
      // Delegamos el evento al cuerpo de la tabla
      $("#tbl_proveedores_con_anticipos").on("click", ".proveedor-row", function() {
        const id = $(this).data('id-proveedor');
        proveedor_seleccionado = findProveedorById(id);
        anticipo_seleccionado = null; // Limpiar selección de anticipo
        renderAnticipos(proveedor_seleccionado);
      });

      // 3. Evento: Abrir Modal de Transacciones
      // Delegamos el evento al cuerpo de la tabla de anticipos
      $("#tbl_anticipos_proveedor").on("click", ".btn-view-transacciones", function() {
        const idAnticipo = $(this).data('id-anticipo');

        // El proveedor debe estar seleccionado ya
        if (proveedor_seleccionado) {
          anticipo_seleccionado = findAnticipoById(proveedor_seleccionado, idAnticipo);
        }

        if (anticipo_seleccionado) {
          renderTransaccionesModal(anticipo_seleccionado);
        } else {
          alert('Error: Anticipo no encontrado o proveedor no seleccionado.');
        }
      });


      // 4. Evento: Abrir Modal de Nuevo Anticipo
      $("#btn_open_new_anticipo_modal").on("click", function() {
        // Limpiar modal
        $("#reg_proveedor").val('').trigger('change');
        $("#reg_serie_factura").val('');
        $("#reg_numero_factura").val('');
        $("#reg_saldo_inicial").val('');
        nuevoAnticipoModal.show();
      });


      // 5. Evento: Guardar Nuevo Anticipo
      $("#btn_guardar_anticipo").on("click", function() {
        const id_proveedor = $("#reg_proveedor").val();
        const serie_factura = $("#reg_serie_factura").val().trim().toUpperCase();
        const numero_factura = $("#reg_numero_factura").val().trim();
        const saldo_inicial = parseFloat($("#reg_saldo_inicial").val());

        if (!id_proveedor) {
          alert("Debe seleccionar un proveedor.");
          return;
        }
        if (serie_factura.length === 0 || numero_factura.length === 0) {
          alert("Debe ingresar la Serie y Número de Factura.");
          return;
        }
        if (isNaN(saldo_inicial) || saldo_inicial <= 0) {
          alert("Debe ingresar un Saldo Inicial válido.");
          return;
        }

        // Deshabilitar botón
        const $btn = $(this);
        $btn.prop('disabled', true).text('Grabando...');

        f_callBackend('registerNewAnticipo', {
            id_proveedor: id_proveedor,
            serie_factura: serie_factura,
            numero_factura: numero_factura,
            saldo_inicial: saldo_inicial
          })
          .done(function(r) {
            if (r.estado === 1) {
              alert("Anticipo registrado con éxito.");

              // Ocultar modal y re-habilitar botón
              nuevoAnticipoModal.hide();

              // --- 1. Simular la inserción dinámica en las variables globales ---
              const new_data = r.new_data; // Objeto del nuevo anticipo

              // Actualizar contadores globales
              cantidad_anticipos++;
              anticipos_con_saldo++;
              renderGeneralSummary();

              // 2. Insertar/Actualizar la lista de proveedores con anticipos
              let targetProv = findProveedorById(id_proveedor);

              if (targetProv) {
                // Caso A: El proveedor ya existía con anticipos
                targetProv.anticipos.push(new_data);
                targetProv.cantidad_anticipos++;
                targetProv.anticipos_con_saldo++;
              } else {
                // Caso B: Es el primer anticipo del proveedor
                const provInfo = proveedores_aptos.find(p => String(p.id_proveedor) === String(id_proveedor));
                if (provInfo) {
                  targetProv = {
                    id_proveedor: provInfo.id_proveedor,
                    documento: provInfo.documento,
                    razon_social: provInfo.razon_social,
                    cantidad_anticipos: 1,
                    anticipos_con_saldo: 1,
                    anticipos_sin_saldo: 0,
                    anticipos: [new_data]
                  };
                  proveedores_con_anticipos.push(targetProv);
                }
              }

              // 3. Seleccionar el proveedor recién actualizado y renderizar
              proveedor_seleccionado = targetProv;
              renderProveedores(); // Actualiza la tabla izquierda
              renderAnticipos(proveedor_seleccionado); // Muestra el nuevo anticipo a la derecha

            } else {
              alert("Error al registrar anticipo: " + (r.msg || "Error desconocido."));
            }
          })
          .fail(function() {
            alert("Error de conexión al intentar grabar el anticipo.");
          })
          .always(function() {
            $btn.prop('disabled', false).text('Grabar Anticipo');
          });
      });


      // -------------------------
      // Start
      // -------------------------

      function f_Init() {
        // Genera menús
        f_GetMenuPrincipal();

        // Titulo de Pantalla
        $("#nv_titulo").html('| Anticipos a proveedores');

        // Carga todos los datos al iniciar
        loadAllData();
      }

      f_Init();
    });
  </script>

</body>

</html>