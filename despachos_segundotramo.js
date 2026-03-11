document.addEventListener("DOMContentLoaded", function () {
  // -------------------------
  // Variables Globales
  // -------------------------
  const backendUrl = "apis/backend.php";

  // -------------------------
  // -------------------------
  // Variables Globales
  // -------------------------
  let find_peso = 0;
  let auto_pesaje_interval = null;
  let current_peso_balanza = 0;

  // -------------------------
  // Funciones Auxiliares
  // -------------------------
  function f_callBackend(accion, data) {
    return $.post(
      backendUrl,
      {
        accion: accion,
        ...data,
      },
      "json",
    );
  }

  function formatNumber(num, decimals = 2) {
    if (num === null || num === undefined || isNaN(num) || num === "")
      return "";
    return new Intl.NumberFormat("en-US", {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals,
    })
      .format(num)
      .replace(/,/g, ""); // Remove commas for input fields
  }

  // -------------------------
  // Funciones Principales
  // -------------------------

  window.f_LoadDistribuciones = function () {
    const fecha_inicio = $("#filtro_fecha_inicio").val();
    const fecha_fin = $("#filtro_fecha_fin").val();
    const filtro_placa = $("#filtro_placa").val();

    $("#wt_loadingunidades").show();
    $("#div_unidades_planta").html(
      '<div class="text-center p-3 text-muted"><div class="spinner-border spinner-border-sm" role="status"></div> Cargando...</div>',
    );

    f_callBackend("get_distribuciones_to_despacho", {
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
      filtro_placa: filtro_placa,
    })
      .done(function (response) {
        $("#wt_loadingunidades").hide();
        if (response.estado === 1) {
          let html = "";
          if (response.data && response.data.length > 0) {
            response.data.forEach(function (row) {
              let estado_pesaje = "";
              let border_color = "";
              let bg_header = "";
              let text_status = "";

              if (row.estado === "A") {
                estado_pesaje = "Esperando Llegada";
                border_color = "border-secondary";
                bg_header = "bg-secondary bg-opacity-10";
                text_status = "text-secondary";
              } else if (row.estado === "B" || row.estado === "C") {
                if (row.estado_peso === "A") {
                  estado_pesaje = "Pesaje Completo";
                  border_color = "border-success";
                  bg_header = "bg-success bg-opacity-10";
                  text_status = "text-success fw-bold";
                } else if (row.estado_peso === "B") {
                  estado_pesaje = "Pesaje Incompleto";
                  border_color = "border-warning border-opacity-75";
                  bg_header = "bg-warning bg-opacity-10";
                  text_status = "text-warning text-dark fw-bold";
                } else {
                  estado_pesaje = "Sin pesar";
                  border_color = "border-danger";
                  bg_header = "bg-danger bg-opacity-10";
                  text_status = "text-danger fw-bold";
                }
              }

              const obj_json = btoa(
                unescape(encodeURIComponent(JSON.stringify(row))),
              );
              const placas_display = `<b>${row.placa1}</b>${row.placa2 ? " / " + row.placa2 : ""}`;
              const fecha_display = row.fecha_hora_llegada
                ? row.fecha_hora_llegada
                : row.fecha_estimada;

              // Preserve selection if this is the active opened panel
              const active_id = $("#pesaje_id_distribucion").val();
              let active_class =
                active_id == row.id_distribucion
                  ? "border-primary border-2 shadow bg-primary bg-opacity-10"
                  : border_color;

              html += `
                        <div class='card mb-2 shadow-sm ${active_class} pointer' style='cursor: pointer;' onclick='f_AbrirPesaje(${row.id_distribucion})' id='tr_dist_${row.id_distribucion}' data-obj='${obj_json}'>
                            <div class='card-header py-1 ${bg_header} d-flex justify-content-between align-items-center' style='border-bottom: 1px solid rgba(0,0,0,0.05);'>
                                <span style="font-size:15px !important;" class='fw-bold text-dark'>${placas_display}</span>
                                <span style="font-size:16px !important;" class='${text_status}'><i class='bi bi-circle-fill me-1' style='font-size: 8px;'></i>${estado_pesaje}</span>
                            </div>
                            <div class='card-body p-2'>
                                <div class='d-flex justify-content-between mb-1'>
                                    <span style="font-size:15px !important;" class='text-muted'>Despacho:</span>
                                    <span class='fw-bold' style="font-size:15px !important;">${row.correlativo}</span>
                                </div>
                                <div class='d-flex justify-content-between mb-1'>
                                    <span style="font-size:15px !important;" class='text-muted'>Transp:</span>
                                    <span class='text-truncate text-end' style='max-width: 60%; font-size:15px !important;' title='${row.transportista_rs || "-"}'>${row.transportista_rs || "-"}</span>
                                </div>
                                <div class='d-flex justify-content-between'>
                                    <span style="font-size:15px !important;" class='text-muted'><i class='bi bi-clock me-1'></i> Llegada:</span>
                                    <span style="font-size:15px !important;">${fecha_display}</span>
                                </div>
                            </div>
                        </div>`;
            });
          } else {
            html = `<div class='alert alert-secondary text-center small p-3'>No hay unidades pendientes de pesaje.</div>`;
          }
          $("#div_unidades_planta").html(html);
        } else {
          $("#div_unidades_planta").html(
            `<div class='alert alert-danger text-center small p-3'>${response.mensaje || "Error al consultar la BD."}</div>`,
          );
        }
      })
      .fail(function () {
        $("#wt_loadingunidades").hide();
        $("#div_unidades_planta").html(
          '<div class="alert alert-danger text-center small p-2">Error al conectar con el servidor.</div>',
        );
      });
  };

  window.f_AbrirPesaje = function (id_distribucion) {
    // Encontrar los datos del registro ocultos en la fila (data-obj)
    const element = $(`#tr_dist_${id_distribucion}`);
    if (element.length === 0) return;

    const obj_b64 = element.attr("data-obj");
    if (!obj_b64) return;

    // Resaltar tarjeta seleccionada
    $("#div_unidades_planta .card").removeClass(
      "border-primary border-2 shadow bg-primary bg-opacity-10",
    );
    element.addClass("border-primary border-2 shadow bg-primary bg-opacity-10");

    const data = JSON.parse(decodeURIComponent(escape(atob(obj_b64))));

    // Mostrar Panel y Ocultar Empty State
    $("#div_empty_state").hide();
    $("#div_panel_pesaje").fadeIn();

    // Limpiar inputs
    $("#txt_peso_tara").val("");
    $("#txt_peso_bruto").val("");
    $("#txt_peso_neto").val("0.00");
    $("#txt_cantidad_bigbags").val("");

    // Llenar datos fijos
    $("#pesaje_id_distribucion").val(data.id_distribucion);
    $("#lbl_placas").html(
      data.placa1 + (data.placa2 ? " / " + data.placa2 : ""),
    );
    $("#lbl_transportista").html(data.transportista_rs || "-");
    $("#lbl_correlativo").html(data.correlativo);
    $("#lbl_peso_esperado").html(formatNumber(data.peso_esperado));

    // Obtener detalles desde la API en base a la distribucion elegida
    f_callBackend("get_detalle_distribucion_to_despacho", {
      id_distribucion: data.id_distribucion,
    })
      .done(function (response) {
        let html_detalles = "";
        if (
          response.estado === 1 &&
          response.data &&
          response.data.length > 0
        ) {
          const detalles = response.data;
          detalles.forEach((det, index) => {
            const tara = det.peso_tara || "";
            const bruto = det.peso_bruto || "";
            const neto = det.peso_neto || "0.00";
            const bigbags = det.cantidad_bigbags || "";
            const tipo_carga = det.tipo_carga;

            const showBigBags = tipo_carga == 2 ? "" : "display: none;";

            let bg_card = "bg-white";
            let border_class = "border-0";
            let header_bg = "bg-dark";
            let text_accent = "text-warning";

            if (parseFloat(neto) > 0) {
              bg_card = "bg-success bg-opacity-10 opacity-75";
              border_class = "border border-success border-2";
              header_bg = "bg-primary";
              text_accent = "text-white";
            }

            html_detalles += `
                    <div class="card shadow mb-4 ${border_class} ${bg_card}" id="card_detalle_${det.id_distribucion_detalle}" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header border-0 ${header_bg} text-white py-2 d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="bi bi-box-seam ${text_accent} me-2"></i>${det.is_blending == 1 ? "Blending" : "Lote"}: <span class="${text_accent}">${det.codigo_mineral || "-"}</span> | Parte: ${det.numero_parte || "Total"}</span>
                            <div class="d-flex gap-2">
                                <span class="badge bg-secondary text-white border border-secondary d-flex align-items-center gap-1 shadow-sm" title="Ticket de Balanza" style="font-size: 0.85rem;">
                                    <i class="bi bi-receipt text-light" style="font-size: 1rem;"></i> ${det.ticket_balanza || "Pendiente"}
                                </span>
                                <span class="badge bg-info text-dark border border-info d-flex align-items-center gap-1 shadow-sm" title="Peso Teórico Húmedo" style="font-size: 0.85rem;">
                                    <i class="bi bi-droplet-fill text-primary" style="font-size: 1rem;"></i> ${formatNumber(det.peso_tomado)} Kg
                                </span>
                                <span class="badge bg-warning text-dark border border-warning d-flex align-items-center gap-1 shadow-sm" title="Peso Teórico Seco" style="font-size: 0.85rem;">
                                    <i class="bi bi-brightness-high-fill text-danger" style="font-size: 1rem;"></i> ${formatNumber(det.peso_seco)} Kg
                                </span>
                            </div>
                        </div>
                        <div class="card-body bg-light py-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-truck text-secondary"></i> Tara (Kg)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" ${tara > 0 ? "readonly" : ""} class="form-control text-center fw-bold shadow-sm border-dark" id="txt_tara_${det.id_distribucion_detalle}" value="${tara}">
                                        <button class="btn ${tara > 0 ? "btn-success" : "btn-outline-secondary"}" type="button" id="btn_conf_tara_${det.id_distribucion_detalle}" onclick="f_ConfirmarTara(${det.id_distribucion_detalle})" title="Confirmar/Desconfirmar Tara">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-muted mb-1"><i class="bi bi-truck-front-fill text-secondary"></i> Bruto (Kg)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" ${bruto > 0 ? "readonly" : tara > 0 ? "" : "disabled"} class="form-control text-center fw-bold shadow-sm border-dark" id="txt_bruto_${det.id_distribucion_detalle}" value="${bruto}">
                                        <button class="btn ${bruto > 0 ? "btn-success" : "btn-outline-secondary"}" type="button" id="btn_conf_bruto_${det.id_distribucion_detalle}" onclick="f_ConfirmarBruto(${det.id_distribucion_detalle})" title="Confirmar/Desconfirmar Bruto" ${bruto > 0 || tara > 0 ? "" : "disabled"}>
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2" style="${showBigBags}">
                                    <label class="form-label small fw-bold text-primary mb-1"><i class="bi bi-bag-fill"></i> Big Bags</label>
                                    <input type="number" ${neto > 0 ? "disabled" : ""} class="form-control form-control-sm text-center fw-bold shadow-sm border-primary text-primary bg-info bg-opacity-10" id="txt_bbs_${det.id_distribucion_detalle}" value="${bigbags}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-success mb-1"><i class="bi bi-box"></i> Neto (Kg)</label>
                                    <input type="text" class="form-control form-control-sm text-center fw-bold bg-white text-dark shadow-sm border-success" id="txt_neto_${det.id_distribucion_detalle}" data-seco="${det.peso_seco}" data-tomado="${det.peso_tomado}" value="${formatNumber(neto)}" readonly>
                                </div>
                                <div class="col-md-2 text-end ${neto > 0 ? "d-none" : ""}" id="container_btn_save_${det.id_distribucion_detalle}">
                                    <button style="margin-top: 22px !important" class="btn btn-primary w-100 fw-bold shadow-sm py-1 btn-sm" id="btn_save_${det.id_distribucion_detalle}" onclick="f_GuardarPesajeLote(${det.id_distribucion_detalle}, ${tipo_carga})" ${bruto > 0 ? "" : "disabled"}>
                                        <i class="bi bi-save me-1"></i> Guardar
                                    </button>
                                </div>
                                <div class="col-12 mt-2 pt-2 border-top text-end" id="msg_val_${det.id_distribucion_detalle}" style="font-size: 0.85rem; min-height: 24px;">
                                </div>
                            </div>
                        </div>
                    </div>`;
          });
        } else {
          html_detalles = `<div class='alert alert-info text-center small py-2'>No hay lotes asociados a este despacho o falló la consulta.</div>`;
        }
        $("#div_detalles_lotes").html(html_detalles);

        // Re-evaluar los pesos para activar alertas si el lote ya tiene neto guardado
        if (response.data && response.data.length > 0) {
          setTimeout(() => {
            response.data.forEach((det) => {
              if (
                parseFloat(det.peso_neto) > 0 ||
                parseFloat(det.peso_tara) > 0 ||
                parseFloat(det.peso_bruto) > 0
              ) {
                f_CalcularNetoLote(det.id_distribucion_detalle);
              }
            });
          }, 100);
        }
      })
      .fail(function () {
        $("#div_detalles_lotes").html(
          `<div class='alert alert-danger text-center small py-2'>Error al obtener detalles.</div>`,
        );
      });

    // Reset Balanza Interval for the active panel
    if (auto_pesaje_interval) {
      clearTimeout(auto_pesaje_interval);
      auto_pesaje_interval = null;
    }

    find_peso = 1;
    $("#chk_auto_pesaje").prop("checked", true);
    f_getPeso(1);
  };

  window.f_CapturarPesoLote = function (tipo, id_detalle) {
    if (!$("#chk_auto_pesaje").prop("checked")) {
      alert(
        "El pesaje automático está desactivado. Escriba el peso manualmente.",
      );
      return;
    }

    if (current_peso_balanza <= 0) {
      alert("La balanza no registra un peso válido (> 0).");
      return;
    }

    if (tipo === "tara") {
      $(`#txt_tara_${id_detalle}`).val(current_peso_balanza);
    } else if (tipo === "bruto") {
      $(`#txt_bruto_${id_detalle}`).val(current_peso_balanza);
    }
  };

  window.f_ConfirmarTara = function (id_detalle) {
    if ($(`#btn_conf_tara_${id_detalle}`).is(":disabled")) return;

    const isConfirmed = $(`#txt_tara_${id_detalle}`).is("[readonly]");

    if (isConfirmed) {
      // Intentando desconfirmar Tara (Visual solamente en este punto para permitir corrección rápida antes de bruto)
      const isBrutoConfirmed = $(`#txt_bruto_${id_detalle}`).is("[readonly]");
      if (isBrutoConfirmed) {
        alert(
          "No puede desconfirmar Tara si el peso Bruto ya está confirmado. Desconfirme el Bruto primero.",
        );
        return;
      }

      $(`#txt_tara_${id_detalle}`).removeAttr("readonly").removeAttr("disabled");
      $(`#btn_conf_tara_${id_detalle}`).removeClass("btn-success").addClass("btn-outline-secondary");
      $(`#txt_bruto_${id_detalle}`).prop("disabled", true).val("");
      $(`#btn_conf_bruto_${id_detalle}`).prop("disabled", true);
    } else {
      // Confirmando Tara -> Backend
      const tara = parseFloat($(`#txt_tara_${id_detalle}`).val()) || 0;
      if (tara <= 0) {
        alert("Ingrese una Tara válida mayor a 0.");
        return;
      }

      const btn = $(`#btn_conf_tara_${id_detalle}`);
      btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span>');

      f_callBackend("update_tara_distribucion", {
        id_distribucion_detalle: id_detalle,
        peso_tara: tara
      }).done(function(resp) {
        btn.prop("disabled", false).html('<i class="bi bi-check2"></i>');
        if(resp.estado === 1) {
          $(`#txt_tara_${id_detalle}`).attr("readonly", "readonly");
          btn.removeClass("btn-outline-secondary").addClass("btn-success");
          
          $(`#txt_bruto_${id_detalle}`).removeAttr("disabled").removeAttr("readonly").focus();
          $(`#btn_conf_bruto_${id_detalle}`).removeAttr("disabled");

          if (resp.ticket_balanza) {
            $(`#card_detalle_${id_detalle} .badge[title="Ticket de Balanza"]`).html(
              `<i class="bi bi-receipt text-light" style="font-size: 1rem;"></i> ${resp.ticket_balanza}`
            );
            f_ImprimirTicketBalanza(id_detalle);
          }
        } else {
          alert(resp.mensaje);
        }
      }).fail(() => {
        btn.prop("disabled", false).html('<i class="bi bi-check2"></i>');
        alert("Error de conexión.");
      });
    }
  };

  window.f_ConfirmarBruto = function (id_detalle) {
    if ($(`#btn_conf_bruto_${id_detalle}`).is(":disabled")) return;

    const isConfirmed = $(`#txt_bruto_${id_detalle}`).is("[readonly]");

    if (isConfirmed) {
      $(`#txt_bruto_${id_detalle}`).removeAttr("readonly").removeAttr("disabled");
      $(`#btn_conf_bruto_${id_detalle}`).removeClass("btn-success").addClass("btn-outline-secondary");
      $(`#btn_save_${id_detalle}`).prop("disabled", true);
      $(`#container_btn_save_${id_detalle}`).removeClass("d-none");
    } else {
      const tara = parseFloat($(`#txt_tara_${id_detalle}`).val()) || 0;
      const bruto = parseFloat($(`#txt_bruto_${id_detalle}`).val()) || 0;

      if (bruto <= 0 || bruto <= tara) {
        alert("Verifique el peso bruto ingresado."); return;
      }

      const neto = bruto - tara;
      const btn = $(`#btn_conf_bruto_${id_detalle}`);
      btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span>');

      f_callBackend("update_bruto_distribucion", {
        id_distribucion_detalle: id_detalle,
        peso_bruto: bruto,
        peso_neto: neto
      }).done(function(resp) {
        btn.prop("disabled", false).html('<i class="bi bi-check2"></i>');
        if(resp.estado === 1) {
          $(`#txt_bruto_${id_detalle}`).attr("readonly", "readonly");
          btn.removeClass("btn-outline-secondary").addClass("btn-success");
          f_CalcularNetoLote(id_detalle);
          $(`#btn_save_${id_detalle}`).prop("disabled", false);
          f_ImprimirTicketBalanza(id_detalle);
        } else {
          alert(resp.mensaje);
        }
      }).fail(() => {
        btn.prop("disabled", false).html('<i class="bi bi-check2"></i>');
        alert("Error de conexión.");
      });
    }
  };

  window.f_ImprimirTicketBalanza = function (id_detalle) {
    window.open(`print_ticketbalanza_dist.php?id=${id_detalle}`, "_blank");
  };

  window.f_CalcularNetoLote = function (id_detalle) {
    const tara = parseFloat($(`#txt_tara_${id_detalle}`).val()) || 0;
    const bruto = parseFloat($(`#txt_bruto_${id_detalle}`).val()) || 0;

    if (tara > 0 && bruto > 0) {
      const neto = bruto - tara;
      $(`#txt_neto_${id_detalle}`).val(formatNumber(neto));
      $(`#txt_neto_${id_detalle}`).removeClass(
        "text-danger text-decoration-line-through",
      );

      // Logica de alertas
      const peso_seco =
        parseFloat($(`#txt_neto_${id_detalle}`).attr("data-seco")) || 0;
      const peso_tomado =
        parseFloat($(`#txt_neto_${id_detalle}`).attr("data-tomado")) || 0;
      let warning_msg = "";

      if (neto < peso_tomado) {
        const perdida = peso_tomado - neto;
        const tolerancia = peso_seco * 0.01;
        if (perdida > tolerancia) {
          warning_msg = `<span class="text-danger fw-bold rounded bg-danger bg-opacity-10 px-2 py-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Pérdida supera el 1% del P.Seco (Merma: ${formatNumber(perdida)} Kg)</span>`;
        } else if (perdida > 0) {
          warning_msg = `<span class="text-warning text-dark fw-bold rounded bg-warning bg-opacity-10 px-2 py-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Pérdida aceptable (Merma: ${formatNumber(perdida)} Kg)</span>`;
        }
      } else if (neto > peso_tomado) {
        warning_msg = `<span class="text-info text-dark fw-bold rounded bg-info bg-opacity-10 px-2 py-1"><i class="bi bi-info-circle-fill me-1"></i> Ganancia de peso (Exceso: ${formatNumber(neto - peso_tomado)} Kg)</span>`;
      } else {
        warning_msg = `<span class="text-success fw-bold rounded bg-success bg-opacity-10 px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Peso exacto</span>`;
      }
      $(`#msg_val_${id_detalle}`).html(warning_msg);
    } else {
      $(`#txt_neto_${id_detalle}`).val("0.00");
      $(`#msg_val_${id_detalle}`).html("");
    }
  };

  window.f_GuardarPesajeLote = function (id_detalle, tipo_carga) {
    const tara = parseFloat($(`#txt_tara_${id_detalle}`).val()) || 0;
    const bruto = parseFloat($(`#txt_bruto_${id_detalle}`).val()) || 0;

    if (tara <= 0 || bruto <= 0) {
      alert("Debe confirmar Tara y Bruto antes de finalizar.");
      return;
    }

    const btn = $(`#btn_save_${id_detalle}`);
    const originalText = btn.html();
    btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span>');

    // Si es Big Bags, primero actualizamos la cantidad
    const updateBbs = (tipo_carga == 2) 
      ? f_callBackend("update_bigbags_distribucion", { id_distribucion_detalle: id_detalle, cantidad_bigbags: $(`#txt_bbs_${id_detalle}`).val() })
      : $.Deferred().resolve({estado: 1});

    updateBbs.done(function(respBbs) {
      if(respBbs.estado === 1) {
        // Confirmación final del lote
        f_callBackend("confirmar_pesos_distribucion", { id_distribucion_detalle: id_detalle })
          .done(function (response) {
            btn.html(originalText).prop("disabled", false);
            if (response.estado === 1) {
              f_ImprimirTicketBalanza(id_detalle);
              
              $(`#card_detalle_${id_detalle}`).addClass("bg-success bg-opacity-10 opacity-75");
              $(`#txt_bbs_${id_detalle}`).prop("disabled", true);
              $(`#container_btn_save_${id_detalle}`).addClass("d-none");

              f_LoadDistribucionesSilently();
              f_CheckCompletadoDistribucion();
            } else {
              alert(response.mensaje);
            }
          }).fail(() => {
            btn.html(originalText).prop("disabled", false);
            alert("Error al confirmar pesos.");
          });
      } else {
        btn.html(originalText).prop("disabled", false);
        alert(respBbs.mensaje);
      }
    }).fail(() => {
      btn.html(originalText).prop("disabled", false);
      alert("Error al actualizar Big Bags.");
    });
  };

  window.f_CheckCompletadoDistribucion = function () {
    // If there are no save buttons currently visible and enabled, the distribution is fully weighed
    let remaining = 0;
    $('[id^="container_btn_save_"]').each(function () {
      if (!$(this).hasClass("d-none")) {
        remaining++;
      }
    });

    if (remaining === 0) {
      // Wait a moment for visual feedback then close the right panel
      setTimeout(() => {
        $("#div_panel_pesaje").fadeOut(400, function () {
          $("#div_empty_state").show();
          f_LoadDistribuciones(); // Full reload to clear active states and refresh filters
        });
      }, 1000);
    }
  };

  // A helper to reload the left panel without showing loaders and keeping right panel open
  window.f_LoadDistribucionesSilently = function () {
    const fecha_inicio = $("#filtro_fecha_inicio").val();
    const fecha_fin = $("#filtro_fecha_fin").val();
    const filtro_placa = $("#filtro_placa").val();

    f_callBackend("get_distribuciones_to_despacho", {
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
      filtro_placa: filtro_placa,
    }).done(function (response) {
      if (response.estado === 1) {
        let html = "";
        if (response.data && response.data.length > 0) {
          response.data.forEach(function (row) {
            let estado_pesaje = "";
            let border_color = "";
            let bg_header = "";
            let text_status = "";

            if (row.estado === "A") {
              estado_pesaje = "Esperando Llegada";
              border_color = "border-secondary";
              bg_header = "bg-secondary bg-opacity-10";
              text_status = "text-secondary";
            } else if (row.estado === "B" || row.estado === "C") {
              if (row.estado_peso === "A") {
                estado_pesaje = "Pesaje Completo";
                border_color = "border-success";
                bg_header = "bg-success bg-opacity-10";
                text_status = "text-success fw-bold";
              } else if (row.estado_peso === "B") {
                estado_pesaje = "Pesaje Incompleto";
                border_color = "border-warning border-opacity-75";
                bg_header = "bg-warning bg-opacity-10";
                text_status = "text-warning text-dark fw-bold";
              } else {
                estado_pesaje = "Sin pesar";
                border_color = "border-danger";
                bg_header = "bg-danger bg-opacity-10";
                text_status = "text-danger fw-bold";
              }
            }

            const obj_json = btoa(
              unescape(encodeURIComponent(JSON.stringify(row))),
            );
            const placas_display = `<b>${row.placa1}</b>${row.placa2 ? " / " + row.placa2 : ""}`;
            const fecha_display = row.fecha_hora_llegada
              ? row.fecha_hora_llegada
              : row.fecha_estimada;

            // Preserve selection if this is the active opened panel
            const active_id = $("#pesaje_id_distribucion").val();
            let active_class =
              active_id == row.id_distribucion
                ? "border-primary border-2 shadow bg-primary bg-opacity-10"
                : border_color;

            html += `
                        <div class='card mb-2 shadow-sm ${active_class} pointer' style='cursor: pointer;' onclick='f_AbrirPesaje(${row.id_distribucion})' id='tr_dist_${row.id_distribucion}' data-obj='${obj_json}'>
                            <div class='card-header py-1 ${bg_header} d-flex justify-content-between align-items-center' style='border-bottom: 1px solid rgba(0,0,0,0.05);'>
                                <span style="font-size:15px !important;" class='fw-bold text-dark'>${placas_display}</span>
                                <span style="font-size:14px !important;" class='${text_status}'><i class='bi bi-circle-fill me-1' style='font-size: 8px;'></i>${estado_pesaje}</span>
                            </div>
                            <div class='card-body p-2'>
                                <div class='d-flex justify-content-between mb-1'>
                                    <span style="font-size:15px !important;" class='text-muted'>Despacho:</span>
                                    <span class='fw-bold' style="font-size:15px !important;">${row.correlativo}</span>
                                </div>
                                <div class='d-flex justify-content-between mb-1'>
                                    <span style="font-size:15px !important;" class='text-muted'>Transp:</span>
                                    <span class='text-truncate text-end' style='max-width: 60%; font-size:15px !important;' title='${row.transportista_rs || "-"}'>${row.transportista_rs || "-"}</span>
                                </div>
                                <div class='d-flex justify-content-between'>
                                    <span style="font-size:15px !important;" class='text-muted'><i class='bi bi-clock me-1'></i> Llegada:</span>
                                    <span style="font-size:15px !important;">${fecha_display}</span>
                                </div>
                            </div>
                        </div>`;
          });
        } else {
          html = `<div class='alert alert-secondary text-center small p-3'>No hay unidades pendientes de pesaje.</div>`;
        }
        $("#div_unidades_planta").html(html);
      }
    });
  };

  // -------------------------
  // Función de la Balanza
  // -------------------------
  window.f_getPeso = function (_on) {
    if (!$("#chk_auto_pesaje").prop("checked")) {
      current_peso_balanza = 0;
      // $('#lbl_peso_actual').html('0.00'); // Optional: reset display if manual
      return;
    }

    if (_on == 1) {
      find_peso = 1;
    }

    if (find_peso == 1) {
      $.get(
        "apis/interfaces.php",
        { accion: "get_Peso", cod_balanza: 1 },
        function (data) {
          if (data.estado == 1) {
            if (data.peso == -1) {
              $("#div_SinConexion_balanza").show();
              current_peso_balanza = 0;
              $("#lbl_peso_actual").html("0.00");
            } else {
              $("#div_SinConexion_balanza").hide();
              current_peso_balanza = parseFloat(data.peso);
              $("#lbl_peso_actual").html(formatNumber(current_peso_balanza));
            }
          } else {
            $("#div_SinConexion_balanza").show();
            current_peso_balanza = 0;
            $("#lbl_peso_actual").html("0.00");
          }

          auto_pesaje_interval = setTimeout(function () {
            f_getPeso(0);
          }, 500);
        },
        "json",
      ).fail(function () {
        $("#div_SinConexion_balanza").show();
        current_peso_balanza = 0;
        $("#lbl_peso_actual").html("0.00");

        auto_pesaje_interval = setTimeout(function () {
          f_getPeso(0);
        }, 500);
      });
    }
  };

  // Start
  function f_Init() {
    f_GetMenuPrincipal();
    $("#nv_titulo").html("| Balanza (Segundo Tramo)");

    // Load data on start (today by default or let user click button)
    const today = new Date().toISOString().split("T")[0];
    $("#filtro_fecha_inicio").val(today);
    $("#filtro_fecha_fin").val(today);

    f_LoadDistribuciones();
  }

  f_Init();
});
