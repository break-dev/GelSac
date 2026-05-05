document.addEventListener("DOMContentLoaded", function () {

  // ──────────────────────────────────────────────
  // Variables globales
  // ──────────────────────────────────────────────
  let allData = {};
  let cantidad_anticipos = 0;
  let anticipos_con_saldo = 0;
  let anticipos_sin_saldo = 0;
  let plantas_aptas = [];          // Para el Select2 del modal
  let plantas_con_anticipos = [];         // Array con toda la data

  let planta_seleccionada = null;
  let anticipo_seleccionado = null;
  let _evidencias_contexto = { tipo: 'A', id: 0 }; // 'A' para anticipo_planta




  // ──────────────────────────────────────────────
  // Helpers
  // ──────────────────────────────────────────────

  function callBackend(accion, data, formData) {
    if (formData) {
      formData.append("accion", accion);
      return $.ajax({ url: BACKEND_URL, type: "POST", data: formData, processData: false, contentType: false, dataType: "json" });
    }
    return $.post(BACKEND_URL, { accion, ...data }, "json");
  }

  function formatCurrency(amount) {
    return "USD " + parseFloat(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function findPlantaById(id) {
    return plantas_con_anticipos.find(p => String(p.id_planta) === String(id));
  }

  function findAnticipoById(planta, id) {
    if (!planta || !planta.anticipos) return null;
    return planta.anticipos.find(a => String(a.id_anticipo) === String(id));
  }

  function estadoLabel(estado) {
    if (estado === "A") return { text: "Con saldo", cls: "text-success" };
    if (estado === "B") return { text: "Sin saldo", cls: "text-danger" };
    return { text: "Anulado", cls: "text-muted" };
  }

  function transEstadoLabel(estado) {
    if (estado === "A") return '<span class="badge bg-success">Confirmada</span>';
    if (estado === "B") return '<span class="badge bg-warning text-dark">Por confirmar</span>';
    return '<span class="badge bg-secondary">Cancelada</span>';
  }


  // ──────────────────────────────────────────────
  // Renderizado
  // ──────────────────────────────────────────────

  function renderSummary() {
    $("#total_anticipos").text(cantidad_anticipos);
    $("#anticipos_con_saldo").text(anticipos_con_saldo);
    $("#anticipos_sin_saldo").text(anticipos_sin_saldo);
  }

  function renderPlantas() {
    let html = "";
    if (plantas_con_anticipos.length === 0) {
      html = '<tr><td colspan="6" class="text-center">No hay plantas con anticipos registrados.</td></tr>';
    }

    plantas_con_anticipos.sort((a, b) => a.descripcion.localeCompare(b.descripcion));

    plantas_con_anticipos.forEach(p => {
      html += `
        <tr class="planta-row" data-id-planta="${p.id_planta}" id="planta_row_${p.id_planta}">
          <td>${p.descripcion}</td>
          <td>${p.ruc || "---"}</td>
          <td class="text-center">${p.cantidad_anticipos}</td>
          <td class="text-center text-success">${p.anticipos_con_saldo}</td>
          <td class="text-center text-danger">${p.anticipos_sin_saldo}</td>
          <td class="text-center">
            <button class="btn btn-sm btn-primary btn-view-anticipos" data-id-planta="${p.id_planta}" title="Ver Anticipos">
              <i class="bi bi-eye"></i>
            </button>
          </td>
        </tr>`;
    });

    $("#tbl_plantas_con_anticipos").html(html);
  }

  function renderAnticipos(planta) {
    $(".planta-row").removeClass("selected");

    if (!planta) {
      $("#planta_seleccionada_desc").text("---");
      $("#tbl_anticipos_planta").html('<tr><td colspan="8" class="text-center">Seleccione una planta en el panel izquierdo.</td></tr>');
      $("#span_saldo_planta").addClass("d-none");
      return;
    }

    $(`#planta_row_${planta.id_planta}`).addClass("selected");
    $("#planta_seleccionada_desc").text(planta.descripcion);

    // Calcular saldo total disponible (solo anticipos con estado A)
    const saldoTotal = planta.anticipos
      .filter(a => a.estado === "A")
      .reduce((acc, a) => acc + parseFloat(a.saldo_actual || 0), 0);

    $("#label_planta_saldo").text(planta.descripcion);
    $("#total_saldo_planta").text(formatCurrency(saldoTotal));
    $("#span_saldo_planta").removeClass("d-none");

    let html = "";
    if (planta.anticipos.length === 0) {
      html = '<tr><td colspan="8" class="text-center">Esta planta no tiene anticipos.</td></tr>';
    }

    const anticiposOrdenados = [...planta.anticipos].sort((a, b) => b.id_anticipo - a.id_anticipo);

    anticiposOrdenados.forEach(a => {
      const { text, cls } = estadoLabel(a.estado);

      // Evidencias
      let evidencias = [];
      try { evidencias = JSON.parse(a.evidencias) || []; } catch (e) { evidencias = []; }
      const badgeEvidencias = evidencias.length > 0
        ? `<span class="badge bg-info text-dark badge-evidencia btn-ver-evidencias"
               data-id-anticipo="${a.id_anticipo}"
               data-id-planta="${planta.id_planta}"
               title="Ver ${evidencias.length} archivo(s)">
               <i class="bi bi-paperclip"></i> ${evidencias.length}
           </span>`
        : '<span class="text-muted" style="font-size:12px;">Sin archivos</span>';

      // Botón anular (solo si no está anulado)
      let btnAnular = "";
      if (a.estado !== "X") {
        btnAnular = `
          <button class="btn btn-sm btn-danger btn-anular-anticipo ms-1"
                  data-id-anticipo="${a.id_anticipo}"
                  data-id-planta="${planta.id_planta}"
                  data-factura="${a.serie_factura}-${a.numero_factura}"
                  title="Anular Anticipo">
            <i class="bi bi-trash"></i>
          </button>`;
      }

      html += `
        <tr>
          <td>${a.serie_factura}-${a.numero_factura}</td>
          <td class="text-end">${formatCurrency(a.saldo_inicial)}</td>
          <td class="text-end">${formatCurrency(a.saldo_actual)}</td>
          <td class="text-center">${a.transacciones ? a.transacciones.length : 0}</td>
          <td class="text-center">${badgeEvidencias}</td>
          <td class="text-center">${a.fecha_registro}</td>
          <td class="text-center ${cls}"><strong>${text}</strong></td>
          <td class="text-center">
            
              <button class="btn btn-sm btn-info text-white btn-view-transacciones"
                      data-id-anticipo="${a.id_anticipo}"
                      data-id-planta="${planta.id_planta}"
                      title="Ver Transacciones">
                <i class="bi bi-receipt"></i>
              </button>
              <button class="btn btn-sm btn-outline-info btn-manage-evidencias"
                      data-id-anticipo="${a.id_anticipo}"
                      title="Gestionar Evidencias">
                <i class="bi bi-files"></i>
              </button>
              ${btnAnular}
            
          </td>
        </tr>`;
    });

    $("#tbl_anticipos_planta").html(html);
  }

  function renderTransaccionesModal(anticipo) {
    $("#modal_anticipo_factura").text(`${anticipo.serie_factura}-${anticipo.numero_factura}`);
    $("#modal_saldo_inicial").text(formatCurrency(anticipo.saldo_inicial));
    $("#modal_saldo_actual").text(formatCurrency(anticipo.saldo_actual));

    let html = "";
    if (!anticipo.transacciones || anticipo.transacciones.length === 0) {
      html = '<tr><td colspan="7" class="text-center">No hay transacciones registradas.</td></tr>';
    } else {
      anticipo.transacciones.forEach((t, idx) => {
        html += `
          <tr>
            <td class="text-center">${t.factura_serie ? t.factura_serie + '-' + t.factura_numero : "---"}</td>
            <td class="text-end">${formatCurrency(t.saldo_actual)}</td>
            <td class="text-end text-danger"><strong>${formatCurrency(t.monto_retirado)}</strong></td>
            <td class="text-end">${formatCurrency(t.saldo_restante)}</td>
            <td class="text-center">${transEstadoLabel(t.estado)}</td>
            <td>${t.fecha_registro}</td>
          </tr>`;
      });
    }

    $("#tbl_transacciones").html(html);
    $("#modal_ver_transacciones").modal("show");
  }

  // ──────────────────────────────────────────────
  // GestiÃ³n de Evidencias (Files)
  // ──────────────────────────────────────────────

  window.f_AbrirModalEvidencias = function(id) {
    _evidencias_contexto = { tipo: 'A', id: id };
    $('#ev_nuevo_archivo').val('');
    f_LoadEvidencias();
    $("#modal_evidencias").modal("show");
  }

  window.f_LoadEvidencias = function() {
    $('#div_evidencias_list').html('<div class="text-center p-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>');
    $.post(BACKEND_URL, { 
        accion: 'fv_ListarEvidencias', 
        tipo: _evidencias_contexto.tipo, 
        id: _evidencias_contexto.id 
    }, function (data) {
        if (data.estado !== 1 || !data.evidencias.length) {
            $('#div_evidencias_list').html('<div class="text-center p-3 text-muted" style="font-size:12px;">Sin archivos adjuntos.</div>');
            return;
        }
        var html = data.evidencias.map(function (ev, i) {
            return '<div class="list-group-item d-flex justify-content-between align-items-center py-2">' +
                '<div class="text-truncate" style="max-width: 80%; font-size:12px;">' +
                '<i class="bi bi-file-earmark-arrow-down me-2"></i>' +
                '<a href="' + ev.path + '" target="_blank" class="text-decoration-none">' + (ev.filename || 'Archivo ' + (i+1)) + '</a>' +
                '</div>' +
                '<button class="btn btn-sm btn-outline-danger border-0" onclick="f_EliminarEvidencia(' + i + ');">' +
                '<i class="bi bi-trash"></i>' +
                '</button>' +
                '</div>';
        }).join('');
        $('#div_evidencias_list').html(html);
    }, 'json');
  }

  window.f_SubirEvidencia = function() {
    var fileInput = $('#ev_nuevo_archivo')[0];
    if (!fileInput || fileInput.files.length === 0) {
        alert('Seleccione un archivo.');
        return;
    }

    var formData = new FormData();
    formData.append('accion', 'fv_SubirEvidencia');
    formData.append('tipo', _evidencias_contexto.tipo);
    formData.append('id', _evidencias_contexto.id);
    formData.append('archivo', fileInput.files[0]);

    $('#div_loading').addClass('show');
    $.ajax({
        url: BACKEND_URL, type: 'POST', data: formData,
        contentType: false, processData: false, dataType: 'json',
        success: function (data) {
            $('#div_loading').removeClass('show');
            if (data.estado === 1) {
                $('#ev_nuevo_archivo').val('');
                f_LoadEvidencias();
                // Opcional: recargar la tabla para actualizar el contador de archivos
                loadAllData();
            } else {
                alert('Error: ' + data.msg);
            }
        },
        error: function () {
            $('#div_loading').removeClass('show');
            alert('Error de conexion.');
        }
    });
  }

  window.f_EliminarEvidencia = function(index) {
    if (!confirm('¿Desea eliminar este archivo?')) return;

    $('#div_loading').addClass('show');
    $.post(BACKEND_URL, { 
        accion: 'fv_EliminarEvidencia', 
        tipo: _evidencias_contexto.tipo, 
        id: _evidencias_contexto.id,
        index: index
    }, function (data) {
        $('#div_loading').removeClass('show');
        if (data.estado === 1) {
            f_LoadEvidencias();
            loadAllData();
        } else {
            alert('Error: ' + data.msg);
        }
    }, 'json');
  }

  function loadSelect2Plantas() {
    const data = plantas_aptas.map(p => ({ id: p.id_planta, text: `${p.descripcion} (${p.ruc || "S/RUC"})` }));
    $("#reg_planta").select2({
      dropdownParent: $("#modal_nuevo_anticipo"),
      theme: "bootstrap-5",
      placeholder: "Buscar o seleccionar una planta...",
      data: data,
      allowClear: true
    });
  }


  // ──────────────────────────────────────────────
  // Carga inicial
  // ──────────────────────────────────────────────

  function loadAllData() {
    callBackend("getAnticiposPlantasData", {})
      .done(function (r) {
        if (r.estado === 1 && r.data) {
          allData = r.data;
          cantidad_anticipos = parseInt(allData.cantidad_anticipos || 0);
          anticipos_con_saldo = parseInt(allData.anticipos_con_saldo || 0);
          anticipos_sin_saldo = parseInt(allData.anticipos_sin_saldo || 0);
          plantas_aptas = allData.plantas || [];
          plantas_con_anticipos = allData.plantas_con_anticipos || [];

          renderSummary();
          renderPlantas();
          loadSelect2Plantas();

          // Mantener selección si ya había una
          if (planta_seleccionada) {
            const updated = findPlantaById(planta_seleccionada.id_planta);
            if (updated) {
              planta_seleccionada = updated;
              renderAnticipos(planta_seleccionada);
            }
          }
        } else {
          alert("Error al cargar datos: " + (r.msg || "Respuesta incompleta."));
        }
      })
      .fail(function () {
        alert("Error de conexión al cargar datos de anticipos.");
      });
  }


  // ──────────────────────────────────────────────
  // Eventos
  // ──────────────────────────────────────────────

  // Seleccionar planta (click en fila o en botón)
  $("#tbl_plantas_con_anticipos").on("click", ".planta-row, .btn-view-anticipos", function () {
    const id = $(this).data("id-planta");
    planta_seleccionada = findPlantaById(id);
    anticipo_seleccionado = null;
    renderAnticipos(planta_seleccionada);
  });

  // Ver Transacciones
  $("#tbl_anticipos_planta").on("click", ".btn-view-transacciones", function () {
    const idAnticipo = $(this).data("id-anticipo");
    const idPlanta = $(this).data("id-planta");
    const planta = planta_seleccionada || findPlantaById(idPlanta);
    if (planta) {
      anticipo_seleccionado = findAnticipoById(planta, idAnticipo);
      if (anticipo_seleccionado) {
        renderTransaccionesModal(anticipo_seleccionado);
      }
    }
  });

  // Ver Evidencias (Click en badge o botón files)
  $("#tbl_anticipos_planta").on("click", ".btn-ver-evidencias, .btn-manage-evidencias", function () {
    const idAnticipo = $(this).data("id-anticipo");
    f_AbrirModalEvidencias(idAnticipo);
  });

  // Helper: limpia todos los estados de validación del modal
  function clearModalValidation() {
    ["#reg_serie_factura", "#reg_numero_factura", "#reg_saldo_inicial"].forEach(sel => {
      $(sel).removeClass("is-invalid is-valid");
    });
    $("#reg_planta").next(".select2-container").find(".select2-selection").css("border-color", "");
    $("#err_planta").text("");
    $(".invalid-feedback").text("");
  }

  // Helper: valida un campo y devuelve true si es válido
  function validateField(selector, check, msg) {
    const $el = $(selector);
    if (!check) {
      $el.addClass("is-invalid").removeClass("is-valid");
      $el.next(".invalid-feedback").text(msg);
      return false;
    }
    $el.addClass("is-valid").removeClass("is-invalid");
    return true;
  }

  // Abrir modal nuevo anticipo
  $("#btn_open_new_anticipo_modal").on("click", function () {
    $("#reg_planta").val("").trigger("change");
    $("#reg_serie_factura").val("");
    $("#reg_numero_factura").val("");
    $("#reg_saldo_inicial").val("");
    $("#reg_evidencias").val("");
    clearModalValidation();
    $("#modal_nuevo_anticipo").modal("show");
  });

  // Guardar nuevo anticipo
  $("#btn_guardar_anticipo").on("click", function () {
    const id_planta = $("#reg_planta").val();
    const serie = $("#reg_serie_factura").val().trim().toUpperCase();
    const numero = $("#reg_numero_factura").val().trim();
    const saldo_inicial = parseFloat($("#reg_saldo_inicial").val());

    // Validación visual
    clearModalValidation();
    let valid = true;

    // Planta: colorear el borde del Select2
    if (!id_planta) {
      $("#reg_planta").next(".select2-container").find(".select2-selection").css("border-color", "#dc3545");
      $("#err_planta").text("Debe seleccionar una planta.");
      valid = false;
    } else {
      $("#reg_planta").next(".select2-container").find(".select2-selection").css("border-color", "#198754");
    }

    valid = validateField("#reg_serie_factura", serie.length > 0, "Ingrese la serie (ej. FA01).") && valid;
    valid = validateField("#reg_numero_factura", numero.length > 0, "Ingrese el número de factura.") && valid;
    valid = validateField("#reg_saldo_inicial", !isNaN(saldo_inicial) && saldo_inicial > 0,
      "Ingrese un saldo mayor a 0.") && valid;

    if (!valid) return;

    const $btn = $(this).prop("disabled", true).text("Grabando...");

    // Construir FormData para soportar archivos
    const fd = new FormData();
    fd.append("id_planta", id_planta);
    fd.append("serie_factura", serie);
    fd.append("numero_factura", numero);
    fd.append("saldo_inicial", saldo_inicial);

    const files = document.getElementById("reg_evidencias").files;
    for (let i = 0; i < files.length; i++) {
      fd.append("archivos[]", files[i]);
    }

    callBackend("registrarAnticipoPlanta", {}, fd)
      .done(function (r) {
        if (r.estado === 1) {
          $("#modal_nuevo_anticipo").modal("hide");
          const new_data = r.new_data;

          // Actualizar contadores
          cantidad_anticipos++;
          anticipos_con_saldo++;
          renderSummary();

          // Insertar en estructura local
          let targetPlanta = findPlantaById(id_planta);
          if (targetPlanta) {
            targetPlanta.anticipos.push(new_data);
            targetPlanta.cantidad_anticipos++;
            targetPlanta.anticipos_con_saldo++;
          } else {
            const plantaInfo = plantas_aptas.find(p => String(p.id_planta) === String(id_planta));
            if (plantaInfo) {
              targetPlanta = {
                id_planta: plantaInfo.id_planta,
                ruc: plantaInfo.ruc,
                descripcion: plantaInfo.descripcion,
                cantidad_anticipos: 1,
                anticipos_con_saldo: 1,
                anticipos_sin_saldo: 0,
                anticipos: [new_data],
              };
              plantas_con_anticipos.push(targetPlanta);
            }
          }

          planta_seleccionada = targetPlanta;
          renderPlantas();
          renderAnticipos(planta_seleccionada);
        } else {
          alert("Error: " + (r.msg || "Error desconocido."));
        }
      })
      .fail(function () { alert("Error de conexión."); })
      .always(function () { $btn.prop("disabled", false).html('<i class="bi bi-save"></i> Grabar Anticipo'); });
  });

  // Anular anticipo
  $("#tbl_anticipos_planta").on("click", ".btn-anular-anticipo", function () {
    const idAnticipo = $(this).data("id-anticipo");
    const idPlanta = $(this).data("id-planta");
    const factura = $(this).data("factura");

    if (!confirm(`¿Anular el anticipo ${factura}?\n\nLas transacciones pendientes serán canceladas.`)) return;

    const planta = planta_seleccionada || findPlantaById(idPlanta);
    if (!planta) { alert("Error: planta no encontrada."); return; }

    const anticipo = findAnticipoById(planta, idAnticipo);
    if (!anticipo) { alert("Error: anticipo no encontrado."); return; }

    callBackend("anularAnticipoPlanta", { id_anticipo: idAnticipo })
      .done(function (r) {
        if (r.estado === 1) {
          // Marcar como anulado en local (estado X)
          anticipo.estado = "X";

          // Ajustar contadores
          if (anticipo.estado === "A") {
            planta.anticipos_con_saldo = Math.max(0, planta.anticipos_con_saldo - 1);
            anticipos_con_saldo = Math.max(0, anticipos_con_saldo - 1);
          } else {
            planta.anticipos_sin_saldo = Math.max(0, planta.anticipos_sin_saldo - 1);
            anticipos_sin_saldo = Math.max(0, anticipos_sin_saldo - 1);
          }
          cantidad_anticipos = Math.max(0, cantidad_anticipos - 1);
          planta.cantidad_anticipos = Math.max(0, planta.cantidad_anticipos - 1);

          renderSummary();
          renderPlantas();

          planta_seleccionada = planta;
          renderAnticipos(planta_seleccionada);
        } else {
          alert("Error: " + (r.msg || "No se pudo anular."));
        }
      })
      .fail(function () { alert("Error de conexión."); });
  });


  // ──────────────────────────────────────────────
  // Init
  // ──────────────────────────────────────────────

  function f_Init() {
    f_GetMenuPrincipal();
    $("#nv_titulo").html("| Anticipos a Plantas");
    loadAllData();
  }

  f_Init();
});
