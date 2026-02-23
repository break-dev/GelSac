document.addEventListener("DOMContentLoaded", function () {
  // Variables globales
  var allDistribuciones = [];
  var agrupacionesMap = {}; // Key -> Object

  // Precargar listados
  function f_LoadConcesiones(ids_despachos, callback) {
    f_callBackend("get_Guia2T_concesiones", {
      ids_despachos: ids_despachos,
    }).done(function (resp) {
      if (resp.estado === 1) {
        var html = '<option value="">Seleccione...</option>';
        for (var i = 0; i < resp.data.length; i++) {
          var idFormated = resp.data[i].Id || resp.data[i].id;
          html +=
            '<option value="' +
            idFormated +
            '">' +
            resp.data[i].descripcion +
            "</option>";
        }
        $("#guia_concesion").html(html);
        $("#guia_e_concesion").html(html);
        if (callback) callback();
      }
    });
  }

  function f_LoadMarcasTolva() {
    f_callBackend("get_Guia2T_marcas_tolva", {}).done(function (resp) {
      if (resp.estado === 1) {
        var html = '<option value="">Seleccione...</option>';
        for (var i = 0; i < resp.data.length; i++) {
          var idFormated = resp.data[i].Id || resp.data[i].id;
          html +=
            '<option value="' +
            idFormated +
            '">' +
            resp.data[i].descripcion +
            "</option>";
        }
        $("#guia_marca_tolva").html(html);
        $("#guia_e_marca_tolva").html(html);
      }
    });
  }

  function f_LoadEmpresasTransporte() {
    f_callBackend("get_Guia2T_empresas_transporte", {}).done(function (resp) {
      if (resp.estado === 1) {
        var html = '<option value="">Seleccione...</option>';
        for (var i = 0; i < resp.data.length; i++) {
          var idFormated = resp.data[i].Id || resp.data[i].id;
          var label =
            resp.data[i].documento + " - " + resp.data[i].razon_social;
          html += '<option value="' + idFormated + '">' + label + "</option>";
        }
        $("#guia_empresa_tolva").html(html);
        $("#guia_e_empresa_tolva").html(html);
      }
    });
  }

  // ========================
  // Funciones Auxiliares
  // ========================
  function f_callBackend(accion, data) {
    return $.post(backendUrl, { accion: accion, ...data }, "json");
  }

  function formatNumber(num, decimals) {
    decimals = decimals !== undefined ? decimals : 2;
    if (num === null || num === undefined || isNaN(num)) return "0.00";
    return new Intl.NumberFormat("en-US", {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals,
    }).format(num);
  }

  function formatDateDMY(dateStr) {
    if (!dateStr) return "";
    var parts = dateStr.split("-");
    if (parts.length === 3) return parts[2] + "/" + parts[1] + "/" + parts[0];
    return dateStr;
  }

  function dmyToYmd(dateStr) {
    if (!dateStr) return "";
    var parts = dateStr.split("/");
    if (parts.length === 3) return parts[2] + "-" + parts[1] + "-" + parts[0];
    return dateStr;
  }

  // ========================
  // Cargar todo
  // ========================
  window.f_LoadAll = function () {
    f_LoadAgrupaciones();
    f_LoadGuiasGeneradas();
    // ocultar detalle
    $("#panel_detalle_agrupacion").slideUp(100);
    $("#panel_detalle_guia").slideUp(100);
  };

  // ========================
  // AGRUPACIONES PENDIENTES
  // ========================
  function f_LoadAgrupaciones() {
    var fechaDesde = dmyToYmd($("#filtro_fecha_desde").val());
    var fechaHasta = dmyToYmd($("#filtro_fecha_hasta").val());
    var placa = $("#filtro_placa").val();

    $("#wt_agrupaciones").show();
    $("#tbl_agrupaciones").html(
      '<tr><td colspan="10" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando...</td></tr>',
    );

    f_callBackend("get_distribuciones_pendientes_guia_2t", {
      fecha_inicio: fechaDesde,
      fecha_fin: fechaHasta,
      filtro_placa: placa,
    })
      .done(function (response) {
        $("#wt_agrupaciones").hide();

        if (response.estado === 1) {
          allDistribuciones = response.data;
          f_AgruparYRenderizar();
        } else {
          $("#tbl_agrupaciones").html(
            '<tr><td colspan="10" class="text-center text-danger p-3">Error al cargar datos.</td></tr>',
          );
        }
      })
      .fail(function () {
        $("#wt_agrupaciones").hide();
        $("#tbl_agrupaciones").html(
          '<tr><td colspan="10" class="text-center text-danger p-3">Error de conexión.</td></tr>',
        );
      });
  }

  function f_AgruparYRenderizar() {
    // Agrupar por: fecha_estimada + id_unidad + id_empresa_transporte
    agrupacionesMap = {};

    for (var i = 0; i < allDistribuciones.length; i++) {
      var d = allDistribuciones[i];
      var key =
        d.fecha_estimada + "|" + d.id_unidad + "|" + d.id_empresa_transporte;

      if (!agrupacionesMap[key]) {
        agrupacionesMap[key] = {
          fecha_estimada: d.fecha_estimada,
          id_unidad: d.id_unidad,
          id_empresa_transporte: d.id_empresa_transporte,
          placa: d.placa1,
          transportista_rs: d.transportista_rs || "-",
          transportista_ruc: d.transportista_ruc || "-",
          conductor_nombre: d.conductor_nombre || "-",
          distribuciones: [],
          total_lotes: 0,
          peso_total_neto: 0,
          peso_total_bruto: 0,
          peso_total_tara: 0,
          peso_total_tomado: 0,
          correlativos: [],
        };
      }

      agrupacionesMap[key].distribuciones.push(d);
      agrupacionesMap[key].total_lotes += parseInt(d.total_lotes) || 0;
      agrupacionesMap[key].peso_total_neto +=
        parseFloat(d.peso_total_neto) || 0;
      agrupacionesMap[key].peso_total_bruto +=
        parseFloat(d.peso_total_bruto) || 0;
      agrupacionesMap[key].peso_total_tara +=
        parseFloat(d.peso_total_tara) || 0;
      agrupacionesMap[key].peso_total_tomado +=
        parseFloat(d.peso_total_tomado) || 0;

      if (
        d.correlativo_despacho &&
        agrupacionesMap[key].correlativos.indexOf(d.correlativo_despacho) === -1
      ) {
        agrupacionesMap[key].correlativos.push(d.correlativo_despacho);
      }
    }

    // Renderizar
    var keys = Object.keys(agrupacionesMap);
    $("#badge_pendientes").text(keys.length);

    if (keys.length === 0) {
      $("#tbl_agrupaciones").html(
        '<tr><td colspan="10" class="text-center text-muted p-4">' +
          '<i class="bi bi-check-circle" style="font-size: 24px;"></i><br>' +
          "No hay agrupaciones pendientes de guía para los filtros aplicados." +
          "</td></tr>",
      );
      return;
    }

    var html = "";
    for (var idx = 0; idx < keys.length; idx++) {
      var k = keys[idx];
      var g = agrupacionesMap[k];
      var keyEncoded = btoa(unescape(encodeURIComponent(k)));

      html +=
        '<tr class="guia-row-table" style="cursor:pointer;" onclick="window.f_VerDetalleAgrupacion(\'' +
        keyEncoded +
        "')\">";
      html += '<td class="text-center fw-bold">' + (idx + 1) + "</td>";
      html +=
        '<td class="text-center">' + formatDateDMY(g.fecha_estimada) + "</td>";
      html +=
        '<td class="text-center"><span class="badge bg-dark">' +
        g.placa +
        "</span></td>";
      html += "<td>";
      html +=
        '<div class="fw-bold text-truncate" style="max-width: 250px;" title="' +
        g.transportista_rs +
        '">' +
        g.transportista_rs +
        "</div>";
      html += '<div class="text-muted small">' + g.transportista_ruc + "</div>";
      html += "</td>";
      html +=
        '<td class="text-center"><span class="badge bg-secondary">' +
        g.correlativos.join(", ") +
        "</span></td>";
      html +=
        '<td class="text-center"><span class="badge bg-info text-dark">' +
        g.total_lotes +
        "</span></td>";
      html +=
        '<td class="text-end font-monospace fw-bold text-success">' +
        formatNumber(g.peso_total_neto) +
        "</td>";
      html +=
        '<td class="text-center small text-truncate" style="max-width: 120px;">' +
        g.conductor_nombre +
        "</td>";
      html +=
        '<td class="text-center"><span class="badge bg-warning text-dark">Pendiente</span></td>';
      html += '<td class="text-center">';
      html +=
        '<button class="btn btn-sm btn-success" onclick="event.stopPropagation(); window.f_AbrirModalGuia(\'' +
        keyEncoded +
        '\');" title="Generar Guía">';
      html += '<i class="bi bi-plus-circle me-1"></i>Guía';
      html += "</button>";
      html += "</td>";
      html += "</tr>";
    }

    $("#tbl_agrupaciones").html(html);
  }

  // ========================
  // VER DETALLE AGRUPACIÓN
  // ========================
  window.f_VerDetalleAgrupacion = function (keyEncoded) {
    var key = decodeURIComponent(escape(atob(keyEncoded)));
    var grupo = agrupacionesMap[key];
    if (!grupo) return;

    grupoSeleccionadoKey = key;

    // Marcar fila seleccionada
    $("#tbl_agrupaciones tr").removeClass("table-active");

    // Título
    $("#lbl_grupo_titulo").text(
      grupo.placa +
        " — " +
        formatDateDMY(grupo.fecha_estimada) +
        " — " +
        grupo.transportista_rs,
    );

    // Info cards
    $("#div_info_grupo").html(
      '<div class="col-auto"><div class="stat-card bg-primary bg-opacity-10 text-primary"><div class="value">' +
        grupo.total_lotes +
        '</div><div class="label">Lotes</div></div></div>' +
        '<div class="col-auto"><div class="stat-card bg-success bg-opacity-10 text-success"><div class="value">' +
        formatNumber(grupo.peso_total_neto) +
        '</div><div class="label">Peso Neto (Kg)</div></div></div>' +
        '<div class="col-auto"><div class="stat-card bg-secondary bg-opacity-10 text-secondary"><div class="value">' +
        formatNumber(grupo.peso_total_bruto) +
        '</div><div class="label">Peso Bruto (Kg)</div></div></div>' +
        '<div class="col-auto"><div class="stat-card bg-secondary bg-opacity-10 text-secondary"><div class="value">' +
        formatNumber(grupo.peso_total_tara) +
        '</div><div class="label">Tara (Kg)</div></div></div>',
    );

    // Cargar lotes detallados
    var idsArr = grupo.distribuciones.map(function (d) {
      return d.id_distribucion;
    });
    $("#tbl_detalle_lotes_grupo").html(
      '<tr><td colspan="9" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando lotes...</td></tr>',
    );
    $("#panel_detalle_agrupacion").slideDown(200);

    f_callBackend("get_lotes_distribucion_grupo_2t", {
      ids_distribuciones: JSON.stringify(idsArr),
    }).done(function (resp) {
      if (resp.estado === 1 && resp.data.length > 0) {
        f_RenderizarLotesTabla(resp.data, "#tbl_detalle_lotes_grupo");
      } else {
        $("#tbl_detalle_lotes_grupo").html(
          '<tr><td colspan="9" class="text-center text-muted p-3">Sin lotes encontrados.</td></tr>',
        );
      }
    });
  };

  window.cerrarDetalleAgrupacion = function () {
    $("#panel_detalle_agrupacion").slideUp(200);
    grupoSeleccionadoKey = null;
  };

  // ========================
  // RENDERIZAR LOTES EN TABLA
  // ========================
  function f_RenderizarLotesTabla(lotes, target) {
    var html = "";
    var sumNeto = 0,
      sumBruto = 0,
      sumTara = 0,
      sumTomado = 0;

    for (var i = 0; i < lotes.length; i++) {
      var l = lotes[i];
      var tipoBadge =
        l.is_blending == 1
          ? '<span class="badge badge-blending">Blending</span>'
          : '<span class="badge badge-lote">Lote</span>';
      var presentacion = "-";
      if (l.tipo_carga == 2) {
        presentacion = "Big Bag (" + (l.cantidad_bigbags || 0) + ")";
      } else if (l.tipo_carga == 1) {
        presentacion = "Sacos";
      } else {
        presentacion = "Granel";
      }

      var neto = parseFloat(l.peso_neto) || 0;
      var bruto = parseFloat(l.peso_bruto) || 0;
      var tara = parseFloat(l.peso_tara) || 0;
      var tomado = parseFloat(l.peso_tomado) || 0;

      sumNeto += neto;
      sumBruto += bruto;
      sumTara += tara;
      sumTomado += tomado;

      html += "<tr>";
      html += '<td class="text-center">' + (i + 1) + "</td>";
      html += '<td class="text-center">' + tipoBadge + "</td>";
      html += '<td class="fw-bold">' + (l.codigo_mineral || "-") + "</td>";
      html += "<td>" + (l.correlativo_despacho || "-") + "</td>";
      html += '<td class="text-center">' + presentacion + "</td>";
      html +=
        '<td class="text-end font-monospace">' + formatNumber(tomado) + "</td>";
      html +=
        '<td class="text-end font-monospace">' + formatNumber(bruto) + "</td>";
      html +=
        '<td class="text-end font-monospace">' + formatNumber(tara) + "</td>";
      html +=
        '<td class="text-end font-monospace fw-bold text-success">' +
        formatNumber(neto) +
        "</td>";
      html += "</tr>";
    }

    // Footer totales
    html += '<tr class="table-light fw-bold">';
    html +=
      '<td colspan="5" class="text-end text-uppercase small">Totales:</td>';
    html +=
      '<td class="text-end font-monospace">' +
      formatNumber(sumTomado) +
      "</td>";
    html +=
      '<td class="text-end font-monospace">' + formatNumber(sumBruto) + "</td>";
    html +=
      '<td class="text-end font-monospace">' + formatNumber(sumTara) + "</td>";
    html +=
      '<td class="text-end font-monospace text-success">' +
      formatNumber(sumNeto) +
      "</td>";
    html += "</tr>";

    $(target).html(html);
  }

  // ========================
  // ABRIR MODAL GENERAR GUÍA
  // ========================
  window.f_AbrirModalGuia = function (keyEncoded) {
    var key = decodeURIComponent(escape(atob(keyEncoded)));
    var grupo = agrupacionesMap[key];
    if (!grupo) {
      alert("No se encontró la agrupación seleccionada.");
      return;
    }

    // Reset modal
    $("#hd_modo_guia").val("N");
    $("#hd_id_guia").val("0");
    $("#modal_generar_guiaLabel").html(
      '<i class="bi bi-file-earmark-plus me-2"></i>Generar Guía de Remisión',
    );

    // IDs distribuciones
    var idsArr = grupo.distribuciones.map(function (d) {
      return d.id_distribucion;
    });
    $("#hd_ids_distribuciones").val(JSON.stringify(idsArr));

    // Cargar concesiones de estos despachos particulares
    var despachosSet = new Set();
    grupo.distribuciones.forEach(function (d) {
      if (d.id_despacho) despachosSet.add(d.id_despacho);
    });
    var idsDespachosStr = Array.from(despachosSet).join(",");
    f_LoadConcesiones(idsDespachosStr);

    // Reset campos
    $("#guia_rem_serie, #guia_rem_numero").val("");
    $("#guia_transp_serie, #guia_transp_numero")
      .val("")
      .prop("disabled", false);
    $("#chk_sin_grt").prop("checked", false);
    $("#guia_planta_origen").val("");
    $("#guia_concesion").val("").trigger("change");
    $("#guia_motivo_traslado").val("");
    $("#guia_marca_tolva").val("");
    $("#guia_empresa_tolva").val("").trigger("change");
    $("#guia_serie_tolva, #guia_numero_tolva, #guia_mtc_tolva").val("");

    // Resumen
    $("#div_resumen_modal").html(
      '<span class="badge bg-dark"><i class="bi bi-truck me-1"></i>' +
        grupo.placa +
        "</span>" +
        '<span class="badge bg-info text-dark">' +
        grupo.total_lotes +
        " Lotes</span>" +
        '<span class="badge bg-success">Peso Neto: ' +
        formatNumber(grupo.peso_total_neto) +
        " Kg</span>" +
        '<span class="text-muted small">' +
        grupo.transportista_rs +
        "</span>",
    );

    // Cargar lotes en la tabla del modal
    $("#tbl_modal_lotes").html(
      '<tr><td colspan="9" class="text-center p-2"><span class="spinner-border spinner-border-sm"></span></td></tr>',
    );

    f_callBackend("get_lotes_distribucion_grupo_2t", {
      ids_distribuciones: JSON.stringify(idsArr),
    }).done(function (resp) {
      if (resp.estado === 1 && resp.data.length > 0) {
        f_RenderizarLotesTabla(resp.data, "#tbl_modal_lotes");
      } else {
        $("#tbl_modal_lotes").html(
          '<tr><td colspan="9" class="text-center text-muted p-2">Sin lotes.</td></tr>',
        );
      }
    });

    // Mostrar modal
    var modal = new bootstrap.Modal(
      document.getElementById("modal_generar_guia"),
    );
    modal.show();
  };

  // ========================
  // EDITAR GUÍA
  // ========================
  window.f_EditarGuia = function (idGuia, guiaData) {
    if (!guiaData) return;

    // Preparar UI del modal de EDICIÓN
    $("#hd_modo_guia_e").val("E");
    $("#hd_id_guia_e").val(idGuia);
    $("#modal_editar_guiaLabel").html(
      '<i class="bi bi-pencil-square me-2"></i>Editar Guía de Remisión #' +
        idGuia,
    );

    // Seteamos las fechas y horas
    if (guiaData.fecha_inicio_traslado) {
      var parts = guiaData.fecha_inicio_traslado.split("/");
      if (parts.length === 3) {
        $("#guia_e_fecha_inicio_traslado").val(
          parts[2] + "-" + parts[1] + "-" + parts[0],
        );
      }
    }
    if (guiaData.fecha_hora_emision) {
      var partsEmi = guiaData.fecha_hora_emision.split(" ");
      if (partsEmi.length === 2) {
        var dp = partsEmi[0].split("/");
        if (dp.length === 3) {
          $("#guia_e_fecha_emision").val(dp[2] + "-" + dp[1] + "-" + dp[0]);
        }
        if (partsEmi[1]) {
          $("#guia_e_hora_emision").val(partsEmi[1]);
        }
      }
    }
    if (guiaData.fecha_hora_planta) {
      var partsPlanta = guiaData.fecha_hora_planta.split(" ");
      if (partsPlanta.length === 2) {
        var dp2 = partsPlanta[0].split("/");
        if (dp2.length === 3) {
          $("#guia_e_fecha_planta").val(dp2[2] + "-" + dp2[1] + "-" + dp2[0]);
        }
        if (partsPlanta[1]) {
          $("#guia_e_hora_planta").val(partsPlanta[1]);
        }
      }
    }

    // Cargar concesiones de este despacho en modo edición
    var despachosSet = new Set();
    if (guiaData.lotes && guiaData.lotes.length > 0) {
      guiaData.lotes.forEach(function (l) {
        if (l.id_despacho) despachosSet.add(l.id_despacho);
      });
    }
    var idsDespachosStr = Array.from(despachosSet).join(",");

    // Función personalizada para cargar y luego setear el valor en el Edit modal
    f_LoadConcesiones(idsDespachosStr, function () {
      $("#guia_e_concesion")
        .val(guiaData.id_concesion || "")
        .trigger("change");
    });

    $("#guia_e_planta_origen").val(guiaData.planta_origen || "");
    $("#guia_e_rem_serie").val(guiaData.guia_remitente_serie || "");
    $("#guia_e_rem_numero").val(guiaData.guia_remitente_numero || "");
    $("#guia_e_motivo_traslado").val(guiaData.motivo_traslado || "");
    $("#guia_e_marca_tolva")
      .val(guiaData.id_marca_tolva || "")
      .trigger("change");
    $("#guia_e_empresa_tolva")
      .val(guiaData.id_empresa_transporte_tolva || "")
      .trigger("change");
    $("#guia_e_serie_tolva").val(guiaData.serie_tolva || "");
    $("#guia_e_numero_tolva").val(guiaData.numero_tolva || "");
    $("#guia_e_mtc_tolva").val(guiaData.numero_mtc_tolva || "");

    var sgrt = parseInt(guiaData.sin_guia_transportista);
    if (sgrt === 1) {
      $("#chk_sin_grt_e").prop("checked", true);
      $(".guia_grt_field_e").prop("disabled", true).val("");
    } else {
      $("#chk_sin_grt_e").prop("checked", false);
      $(".guia_grt_field_e").prop("disabled", false);
      $("#guia_e_transp_serie").val(guiaData.guia_transportista_serie || "");
      $("#guia_e_transp_numero").val(guiaData.guia_transportista_numero || "");
    }

    // IDs de las distribuciones actuales
    var arrIds = [];
    if (guiaData.lotes) {
      arrIds = guiaData.lotes.map(function (x) {
        return x.id_distribucion;
      });
      f_RenderizarLotesTabla(guiaData.lotes, "#tbl_modal_lotes_e");
    } else {
      $("#tbl_modal_lotes_e").html(
        '<tr><td colspan="9" class="text-center text-muted p-2">Sin lotes.</td></tr>',
      );
    }
    $("#hd_ids_distribuciones_e").val(JSON.stringify(arrIds));

    // Resumen para el modal de edición
    $("#div_resumen_modal_e").html(
      '<span class="badge bg-dark"><i class="bi bi-truck me-1"></i>' +
        (guiaData.placas || "-") +
        "</span>" +
        '<span class="badge bg-info text-dark">' +
        (guiaData.total_lotes || 0) +
        " Lotes</span>" +
        '<span class="badge bg-success">Peso Neto: ' +
        formatNumber(guiaData.peso_total_neto) +
        " Kg</span>" +
        '<span class="text-muted small">' +
        (guiaData.empresa_transporte || "-") +
        "</span>",
    );

    // Mostrar modal de edición
    var modal = new bootstrap.Modal(
      document.getElementById("modal_editar_guia"),
    );
    modal.show();
  };

  // ========================
  // EMITIR GUÍA (VALIDAR + GRABAR)
  // ========================
  window.f_EmitirGuia = function () {
    // Validaciones
    var fechaInicioTraslado = $("#guia_fecha_inicio_traslado").val();
    var fechaEmision = $("#guia_fecha_emision").val();
    var horaEmision = $("#guia_hora_emision").val();
    var fechaPlanta = $("#guia_fecha_planta").val();
    var horaPlanta = $("#guia_hora_planta").val();
    var plantaOrigen = $("#guia_planta_origen").val();
    var concesion = $("#guia_concesion").val();
    var remSerie = $.trim($("#guia_rem_serie").val());
    var remNumero = $.trim($("#guia_rem_numero").val());
    var transpSerie = $.trim($("#guia_transp_serie").val());
    var transpNumero = $.trim($("#guia_transp_numero").val());
    var sinGRT = $("#chk_sin_grt").prop("checked") ? 1 : 0;
    var motivoTraslado = $("#guia_motivo_traslado").val();

    if (!fechaInicioTraslado) {
      alert("Debe seleccionar la Fecha de Inicio de Traslado.");
      return;
    }
    if (!fechaEmision || !horaEmision) {
      alert("Debe completar la Fecha y Hora de Emisión.");
      return;
    }
    if (!fechaPlanta || !horaPlanta) {
      alert("Debe completar la Fecha y Hora en Planta.");
      return;
    }
    if (!plantaOrigen) {
      alert("Debe seleccionar la Planta de Origen.");
      return;
    }
    if (!remSerie) {
      alert("Debe ingresar la Serie de Guía Remitente.");
      return;
    }
    if (!remNumero) {
      alert("Debe ingresar el Número de Guía Remitente.");
      return;
    }
    if (!motivoTraslado) {
      alert("Debe seleccionar el Motivo de Traslado.");
      return;
    }

    if (sinGRT === 0) {
      if (!transpSerie) {
        alert(
          'Debe ingresar la Serie de Guía Transportista, o marque "Sin Guía Transportista".',
        );
        return;
      }
      if (!transpNumero) {
        alert(
          'Debe ingresar el Número de Guía Transportista, o marque "Sin Guía Transportista".',
        );
        return;
      }
    } else {
      transpSerie = "";
      transpNumero = "";
    }

    var idsDistribuciones = $("#hd_ids_distribuciones").val();
    if (!idsDistribuciones || idsDistribuciones === "[]") {
      alert("No hay distribuciones asociadas a esta guía.");
      return;
    }

    // Confirmar
    var modo = $("#hd_modo_guia").val();
    var msgConfirm =
      modo === "E"
        ? "¿Desea actualizar esta guía?"
        : "¿Desea emitir esta nueva guía?";
    if (!confirm(msgConfirm)) return;

    // Construir payload
    var payload = {
      modo: modo,
      id_guia: $("#hd_id_guia").val(),
      ids_distribuciones: idsDistribuciones,
      fecha_inicio_traslado: fechaInicioTraslado,
      fecha_hora_emision: fechaEmision + " " + horaEmision + ":00",
      fecha_hora_planta: fechaPlanta + " " + horaPlanta + ":00",
      planta_origen: plantaOrigen,
      id_concesion: concesion || 0,
      guia_remitente_serie: remSerie,
      guia_remitente_numero: remNumero,
      guia_transportista_serie: transpSerie,
      guia_transportista_numero: transpNumero,
      sin_guia_transportista: sinGRT,
      motivo_traslado: motivoTraslado,
      id_marca_tolva: $("#guia_marca_tolva").val() || 0,
      id_empresa_transporte_tolva: $("#guia_empresa_tolva").val() || 0,
      serie_tolva: $.trim($("#guia_serie_tolva").val()),
      numero_tolva: $.trim($("#guia_numero_tolva").val()),
      numero_mtc_tolva: $.trim($("#guia_mtc_tolva").val()),
    };

    // Grabar
    $("#wt_grabando_guia").show();
    $("#btn_emitir_guia").prop("disabled", true);

    var endpointAjax = "grabar_guia_segundo_tramo";

    f_callBackend(endpointAjax, payload)
      .done(function (resp) {
        $("#wt_grabando_guia").hide();
        $("#btn_emitir_guia").prop("disabled", false);

        if (resp.estado === 1) {
          alert(resp.mensaje || "Operación exitosa.");
          var modal = bootstrap.Modal.getInstance(
            document.getElementById("modal_generar_guia"),
          );
          if (modal) modal.hide();
          window.f_LoadAll();
        } else {
          alert("Error: " + (resp.mensaje || "Ocurrió un problema."));
        }
      })
      .fail(function () {
        $("#wt_grabando_guia").hide();
        $("#btn_emitir_guia").prop("disabled", false);
        alert("Error de conexión al grabar guía.");
      });
  };

  // ========================
  // GUARDAR EDICIÓN DE GUÍA
  // ========================
  window.f_GuardarEdicionGuia = function () {
    // Validaciones
    var fechaInicioTraslado = $("#guia_e_fecha_inicio_traslado").val();
    var fechaEmision = $("#guia_e_fecha_emision").val();
    var horaEmision = $("#guia_e_hora_emision").val();
    var fechaPlanta = $("#guia_e_fecha_planta").val();
    var horaPlanta = $("#guia_e_hora_planta").val();
    var plantaOrigen = $("#guia_e_planta_origen").val();
    var concesion = $("#guia_e_concesion").val();
    var remSerie = $.trim($("#guia_e_rem_serie").val());
    var remNumero = $.trim($("#guia_e_rem_numero").val());
    var transpSerie = $.trim($("#guia_e_transp_serie").val());
    var transpNumero = $.trim($("#guia_e_transp_numero").val());
    var sinGRT = $("#chk_sin_grt_e").prop("checked") ? 1 : 0;
    var motivoTraslado = $("#guia_e_motivo_traslado").val();

    if (!fechaInicioTraslado)
      return alert("Debe seleccionar la Fecha de Inicio de Traslado.");
    if (!fechaEmision || !horaEmision)
      return alert("Debe completar la Fecha y Hora de Emisión.");
    if (!fechaPlanta || !horaPlanta)
      return alert("Debe completar la Fecha y Hora en Planta.");
    if (!plantaOrigen) return alert("Debe seleccionar la Planta de Origen.");
    if (!remSerie) return alert("Debe ingresar la Serie de Guía Remitente.");
    if (!remNumero) return alert("Debe ingresar el Número de Guía Remitente.");
    if (!motivoTraslado)
      return alert("Debe seleccionar el Motivo de Traslado.");

    if (sinGRT === 0) {
      if (!transpSerie)
        return alert(
          'Debe ingresar la Serie de Guía Transportista, o marque "Sin Guía Transportista".',
        );
      if (!transpNumero)
        return alert(
          'Debe ingresar el Número de Guía Transportista, o marque "Sin Guía Transportista".',
        );
    } else {
      transpSerie = "";
      transpNumero = "";
    }

    var idsDistribuciones = $("#hd_ids_distribuciones_e").val();
    if (!idsDistribuciones || idsDistribuciones === "[]") {
      return alert("No hay distribuciones asociadas a esta guía.");
    }

    if (!confirm("¿Desea guardar los cambios de esta guía?")) return;

    // Construir payload
    var payload = {
      id_guia: $("#hd_id_guia_e").val(),
      ids_distribuciones: idsDistribuciones,
      fecha_inicio_traslado: fechaInicioTraslado,
      fecha_hora_emision: fechaEmision + " " + horaEmision + ":00",
      fecha_hora_planta: fechaPlanta + " " + horaPlanta + ":00",
      planta_origen: plantaOrigen,
      id_concesion: concesion || 0,
      guia_remitente_serie: remSerie,
      guia_remitente_numero: remNumero,
      guia_transportista_serie: transpSerie,
      guia_transportista_numero: transpNumero,
      sin_guia_transportista: sinGRT,
      motivo_traslado: motivoTraslado,
      id_marca_tolva: $("#guia_e_marca_tolva").val() || 0,
      id_empresa_transporte_tolva: $("#guia_e_empresa_tolva").val() || 0,
      serie_tolva: $.trim($("#guia_e_serie_tolva").val()),
      numero_tolva: $.trim($("#guia_e_numero_tolva").val()),
      numero_mtc_tolva: $.trim($("#guia_e_mtc_tolva").val()),
    };

    $("#wt_grabando_guia_e").show();
    $("#btn_guardar_edicion_guia").prop("disabled", true);

    f_callBackend("editar_guia_segundo_tramo", payload)
      .done(function (resp) {
        $("#wt_grabando_guia_e").hide();
        $("#btn_guardar_edicion_guia").prop("disabled", false);

        if (resp.estado === 1) {
          alert(resp.mensaje || "Guía actualizada correctamente.");
          var modal = bootstrap.Modal.getInstance(
            document.getElementById("modal_editar_guia"),
          );
          if (modal) modal.hide();
          window.f_LoadAll();
        } else {
          alert("Error: " + (resp.mensaje || "Ocurrió un problema."));
        }
      })
      .fail(function () {
        $("#wt_grabando_guia_e").hide();
        $("#btn_guardar_edicion_guia").prop("disabled", false);
        alert("Error de conexión al actualizar guía.");
      });
  };

  // ========================
  // GUÍAS GENERADAS
  // ========================
  function f_LoadGuiasGeneradas() {
    var fechaDesde = dmyToYmd($("#filtro_fecha_desde").val());
    var fechaHasta = dmyToYmd($("#filtro_fecha_hasta").val());

    $("#wt_guias").show();
    $("#tbl_guias_generadas").html(
      '<tr><td colspan="11" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando...</td></tr>',
    );

    f_callBackend("get_guias_generadas_2t", {
      fecha_inicio: fechaDesde,
      fecha_fin: fechaHasta,
    })
      .done(function (resp) {
        $("#wt_guias").hide();

        if (resp.estado === 1) {
          f_RenderizarGuias(resp.data);
        } else {
          $("#tbl_guias_generadas").html(
            '<tr><td colspan="11" class="text-center text-danger p-3">Error al cargar guías.</td></tr>',
          );
        }
      })
      .fail(function () {
        $("#wt_guias").hide();
        $("#tbl_guias_generadas").html(
          '<tr><td colspan="11" class="text-center text-danger p-3">Error de conexión.</td></tr>',
        );
      });
  }

  function f_RenderizarGuias(guias) {
    var activas = guias.filter(function (g) {
      return g.estado == 1;
    });
    $("#badge_guias").text(activas.length);

    if (guias.length === 0) {
      $("#tbl_guias_generadas").html(
        '<tr><td colspan="11" class="text-center text-muted p-4">' +
          '<i class="bi bi-journal-x" style="font-size: 24px;"></i><br>' +
          "No hay guías generadas para los filtros aplicados." +
          "</td></tr>",
      );
      return;
    }

    var html = "";
    for (var i = 0; i < guias.length; i++) {
      var g = guias[i];
      var guiaDataB64 = btoa(unescape(encodeURIComponent(JSON.stringify(g))));
      var estadoBadge =
        g.estado == 1
          ? '<span class="badge badge-guia-activa">Activa</span>'
          : '<span class="badge badge-guia-anulada">Anulada</span>';

      var guiaRemitente =
        (g.guia_remitente_serie || "???") +
        "-" +
        (g.guia_remitente_numero || "???");
      var guiaTransp = "-";
      if (g.sin_guia_transportista != 1) {
        guiaTransp =
          (g.guia_transportista_serie || "???") +
          "-" +
          (g.guia_transportista_numero || "???");
      }

      var trId = "tr_guia_" + g.id;
      var detailTrId = "tr_detail_" + g.id;

      html +=
        '<tr id="' +
        trId +
        '" class="guia-row-table" style="cursor: pointer;" onclick="window.f_VerLotesGuia(' +
        g.id +
        ')">';
      html += '<td class="text-center fw-bold">' + (i + 1) + "</td>";
      html +=
        '<td class="text-center"><span class="fw-bold text-primary">' +
        guiaRemitente +
        "</span></td>";
      html += '<td class="text-center">' + guiaTransp + "</td>";
      html +=
        '<td class="text-center">' + (g.planta_origen_nombre || "-") + "</td>";
      html +=
        '<td class="text-center"><span class="badge bg-dark">' +
        (g.placas || "-") +
        "</span></td>";
      html +=
        '<td class="text-truncate" style="max-width: 200px;" title="' +
        (g.empresa_transporte || "-") +
        '"><small>' +
        (g.empresa_transporte || "-") +
        "</small></td>";
      html +=
        '<td class="text-center">' + (g.fecha_hora_emision || "-") + "</td>";
      html +=
        '<td class="text-center"><span class="badge bg-info text-dark">' +
        (g.total_lotes || 0) +
        "</span></td>";
      html +=
        '<td class="text-end font-monospace fw-bold text-success">' +
        formatNumber(g.peso_total_neto) +
        "</td>";
      html += '<td class="text-center">' + estadoBadge + "</td>";
      html += '<td class="text-center">';

      if (g.estado == 1) {
        html +=
          '<button class="btn btn-sm btn-outline-primary me-1" onclick="event.stopPropagation(); window.f_EditarGuia(' +
          g.id +
          ", JSON.parse(decodeURIComponent(escape(atob('" +
          guiaDataB64 +
          '\')))));" title="Editar"><i class="bi bi-pencil"></i></button>';
        html +=
          '<button class="btn btn-sm btn-outline-secondary me-1" onclick="event.stopPropagation(); window.f_ImprimirGuia(' +
          g.id +
          ');" title="Imprimir PDF"><i class="bi bi-printer"></i></button>';
        html +=
          '<button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); window.f_AnularGuia(' +
          g.id +
          ');" title="Anular"><i class="bi bi-trash"></i></button>';
      }

      html += "</td>";
      html += "</tr>";

      // Fila de detalles (oculta por defecto)
      html +=
        '<tr id="' +
        detailTrId +
        '" style="display: none; background-color: #f8f9fa;">';
      html += '<td colspan="11" class="p-3">';
      html += '<div class="card shadow-sm border-warning">';
      html +=
        '<div class="card-header bg-warning bg-opacity-10 py-1"><i class="bi bi-list-check text-dark me-2"></i><strong>Lotes de la Guía ' +
        guiaRemitente +
        "</strong></div>";
      html += '<div class="card-body p-2">';
      html +=
        '<table class="table table-bordered table-sm tabla-lotes-guia mb-0 bg-white">';
      html += "<thead><tr>";
      html += '<th class="text-center" style="width: 30px;">N°</th>';
      html += '<th class="text-center">Tipo</th>';
      html += "<th>Código Mineral</th>";
      html += "<th>Despacho</th>";
      html += '<th class="text-center">Presentación</th>';
      html += '<th class="text-end">P. Tomado (Kg)</th>';
      html += '<th class="text-end">P. Bruto (Kg)</th>';
      html += '<th class="text-end">Tara (Kg)</th>';
      html += '<th class="text-end fw-bold">P. Neto (Kg)</th>';
      html += "</tr></thead>";
      html += '<tbody id="tbl_lotes_inline_' + g.id + '">';
      html +=
        '<tr><td colspan="9" class="text-center text-muted">Cargando...</td></tr>';
      html += "</tbody></table></div></div></td></tr>";

      // Guardar lotes en memoria para renderizarlos luego
      setTimeout(
        (function (id, lotes) {
          return function () {
            if (lotes && lotes.length > 0) {
              f_RenderizarLotesTabla(lotes, "#tbl_lotes_inline_" + id);
            } else {
              $("#tbl_lotes_inline_" + id).html(
                '<tr><td colspan="9" class="text-center text-muted p-2">Sin lotes disponibles.</td></tr>',
              );
            }
          };
        })(g.id, g.lotes),
        50,
      );
    }

    $("#tbl_guias_generadas").html(html);
  }

  // ========================
  // VER LOTES DE GUÍA GENERADA
  // ========================
  window.f_VerLotesGuia = function (idGuia) {
    var detailRow = $("#tr_detail_" + idGuia);
    var mainRow = $("#tr_guia_" + idGuia);

    if (detailRow.is(":visible")) {
      detailRow.hide();
      mainRow.removeClass("table-active");
    } else {
      // Ocultar otras si se desea, o permitir múltiple
      $(".guia-row-table").removeClass("table-active");
      mainRow.addClass("table-active");

      $("[id^=tr_detail_]").hide(); // Ocultar todas las de detalles
      detailRow.fadeIn(200);
    }
  };

  // ========================
  // IMPRIMIR GUÍA (PDF)
  // ========================
  window.f_ImprimirGuia = function (idGuia) {
    window.open("print_segundotramo_guia_gestion.php?id=" + idGuia, "_blank");
  };

  // ========================
  // ANULAR GUÍA
  // ========================
  window.f_AnularGuia = function (idGuia) {
    if (
      !confirm(
        "¿Está seguro de ANULAR esta guía? Las distribuciones quedarán libres para asignar a otra guía.",
      )
    )
      return;

    f_callBackend("anular_guia_segundo_tramo", { id_guia: idGuia })
      .done(function (resp) {
        if (resp.estado === 1) {
          alert(resp.mensaje || "Guía anulada correctamente.");
          window.f_LoadAll();
        } else {
          alert(resp.mensaje || "Error al anular la guía.");
        }
      })
      .fail(function () {
        alert("Error de conexión.");
      });
  };

  // Init
  function f_Init() {
    console.log("INICIALIZANDO COMPONENTES...");
    f_GetMenuPrincipal();
    $("#nv_titulo").html("| Gestionar Guías — Segundo Tramo");

    // Flatpickr
    flatpickr("#filtro_fecha_desde", {
      locale: "es",
      dateFormat: "d/m/Y",
      allowInput: true,
    });
    flatpickr("#filtro_fecha_hasta", {
      locale: "es",
      dateFormat: "d/m/Y",
      allowInput: true,
    });
    flatpickr("#guia_fecha_inicio_traslado", {
      locale: "es",
      dateFormat: "Y-m-d",
      allowInput: true,
    });
    flatpickr("#guia_fecha_emision", {
      locale: "es",
      dateFormat: "Y-m-d",
      allowInput: true,
    });
    flatpickr("#guia_fecha_planta", {
      locale: "es",
      dateFormat: "Y-m-d",
      allowInput: true,
    });
    flatpickr("#guia_e_fecha_inicio_traslado", {
      locale: "es",
      dateFormat: "Y-m-d",
      allowInput: true,
    });
    flatpickr("#guia_e_fecha_emision", {
      locale: "es",
      dateFormat: "Y-m-d",
      allowInput: true,
    });
    flatpickr("#guia_e_fecha_planta", {
      locale: "es",
      dateFormat: "Y-m-d",
      allowInput: true,
    });

    // Select2 en modal
    $("#guia_concesion").select2({
      dropdownParent: $("#modal_generar_guia"),
      theme: "bootstrap-5",
      placeholder: "Seleccione concesión...",
      width: "100%",
    });
    $("#guia_empresa_tolva").select2({
      dropdownParent: $("#modal_generar_guia"),
      theme: "bootstrap-5",
      placeholder: "Seleccione empresa...",
      width: "100%",
    });
    $("#guia_marca_tolva").select2({
      dropdownParent: $("#modal_generar_guia"),
      theme: "bootstrap-5",
      placeholder: "Seleccione empresa...",
      width: "100%",
    });
    $("#guia_e_concesion").select2({
      dropdownParent: $("#modal_editar_guia"),
      theme: "bootstrap-5",
      placeholder: "Seleccione concesión...",
      width: "100%",
    });
    $("#guia_e_empresa_tolva").select2({
      dropdownParent: $("#modal_editar_guia"),
      theme: "bootstrap-5",
      placeholder: "Seleccione empresa...",
      width: "100%",
    });

    // Event listeners
    $("#chk_sin_grt").on("change", function () {
      if ($(this).is(":checked")) {
        $(".guia_grt_field").prop("disabled", true).val("");
      } else {
        $(".guia_grt_field").prop("disabled", false);
      }
    });

    $("#chk_sin_grt_e").on("change", function () {
      if ($(this).is(":checked")) {
        $(".guia_grt_field_e").prop("disabled", true).val("");
      } else {
        $(".guia_grt_field_e").prop("disabled", false);
      }
    });
    $("#btn_buscar").on("click", window.f_LoadAll);
    $("#btn_limpiar").on("click", function () {
      $("#filtro_fecha_desde").val("");
      $("#filtro_fecha_hasta").val("");
      $("#filtro_placa").val("");
      window.f_LoadAll();
    });

    // Precargar Dropdowns globales
    f_LoadMarcasTolva();
    f_LoadEmpresasTransporte();

    // Primera carga
    window.f_LoadAll();
  }

  f_Init();
});
