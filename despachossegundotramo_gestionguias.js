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
    f_LoadDataUnificada();
    // ocultar detalle
    $("#panel_detalle_agrupacion").slideUp(100);
  };

  // ========================
  // AGRUPACIONES PENDIENTES
  // ========================
  function f_LoadDataUnificada() {
    var fechaDesde = dmyToYmd($("#filtro_fecha_desde").val());
    var fechaHasta = dmyToYmd($("#filtro_fecha_hasta").val());
    var placa = $("#filtro_placa").val();
    var estadoFiltro = $("#filtro_estado").val();

    $("#wt_listado span").show();
    $("#tbl_listado_unificado").html(
      '<tr><td colspan="10" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando informacion...</td></tr>',
    );

    // Cargamos ambos: pendientes y generadas
    $.when(
      f_callBackend("get_distribuciones_pendientes_guia_2t", {
        fecha_inicio: fechaDesde,
        fecha_fin: fechaHasta,
        filtro_placa: placa,
      }),
      f_callBackend("get_guias_segundo_tramo", {
        fecha_inicio: fechaDesde,
        fecha_fin: fechaHasta,
        filtro_placa: placa,
      }),
    )
      .done(function (resPend, resGuias) {
        $("#wt_listado span").hide();

        var pendientes = resPend[0].estado === 1 ? resPend[0].data : [];
        var generadas = resGuias[0].estado === 1 ? resGuias[0].data : [];

        f_RenderizarListadoUnificado(pendientes, generadas, estadoFiltro);
      })
      .fail(function () {
        $("#wt_listado span").hide();
        $("#tbl_listado_unificado").html(
          '<tr><td colspan="10" class="text-center text-danger p-3">Error de conexión al cargar datos.</td></tr>',
        );
      });
  }

  function f_RenderizarListadoUnificado(pendientes, generadas, estadoFiltro) {
    var html = "";
    var totalPendientes = 0;
    var totalGeneradas = 0;

    // 1. Procesar Pendientes (Agrupar)
    var multiAgrupaciones = {}; // BaseKey -> Array of Groups
    for (var i = 0; i < pendientes.length; i++) {
      var d = pendientes[i];
      var baseKey =
        d.id_empresa_transporte +
        "|" +
        d.id_unidad +
        "|" +
        d.fecha_estimada +
        "|" +
        d.id_conductor;

      var s_placa = (d.segunda_placa || "").trim().toUpperCase();
      if (s_placa === "-") s_placa = "";

      if (!multiAgrupaciones[baseKey]) multiAgrupaciones[baseKey] = [];

      // Buscar grupo compatible: misma placa, o una de las dos vacía
      var grupo = multiAgrupaciones[baseKey].find(function (g) {
        return (
          g._placa_busqueda === "" ||
          s_placa === "" ||
          g._placa_busqueda === s_placa
        );
      });

      if (!grupo) {
        grupo = {
          tipo: "PENDIENTE",
          _placa_busqueda: s_placa,
          fecha_egreso: d.fecha_estimada,
          placa: d.placa1,
          transportista_rs: d.transportista_rs || "-",
          transportista_ruc: d.transportista_ruc || "-",
          conductor_nombre: d.conductor_nombre || "-",
          marca: d.marca || "",
          codigo_mtc: d.codigo_mtc || "",
          total_lotes: 0,
          peso_total_neto: 0,
          distribuciones: [],
          correlativos_despacho: [],
        };
        multiAgrupaciones[baseKey].push(grupo);
      } else {
        // Actualizar placa de búsqueda si el grupo estaba vacío y este tiene dato
        if (grupo._placa_busqueda === "" && s_placa !== "") {
          grupo._placa_busqueda = s_placa;
        }
      }

      grupo.distribuciones.push(d);
      grupo.total_lotes += parseInt(d.total_lotes) || 0;
      grupo.peso_total_neto += parseFloat(d.peso_total_neto) || 0;
      if (
        d.correlativo_despacho &&
        grupo.correlativos_despacho.indexOf(d.correlativo_despacho) === -1
      ) {
        grupo.correlativos_despacho.push(d.correlativo_despacho);
      }
    }

    // Aplanar para el listado final y mantener compatibilidad con agrupacionesMap
    agrupacionesMap = {};
    var listado = [];
    var totalPendientes = 0;
    var gIdx = 0;

    Object.keys(multiAgrupaciones).forEach(function (bk) {
      multiAgrupaciones[bk].forEach(function (g) {
        var flatKey = bk + "|G" + gIdx++;
        agrupacionesMap[flatKey] = g;
        if (estadoFiltro === "TODOS" || estadoFiltro === "POR_ASIGNAR") {
          listado.push(g);
          totalPendientes++;
        }
      });
    });

    if (estadoFiltro !== "TODOS" && estadoFiltro !== "POR_ASIGNAR") {
      totalPendientes = Object.keys(agrupacionesMap).length;
    }

    // 2. Procesar Generadas
    if (estadoFiltro === "TODOS" || estadoFiltro === "ASIGNADAS") {
      generadas.forEach(function (g) {
        listado.push({
          tipo: "GENERADA",
          id_guia: g.id_guia,
          fecha_egreso: g.fecha_inicio_traslado, // Ojo, usamos inicio traslado como referencia
          nro_guia:
            g.guia_remitente_serie && g.guia_remitente_numero
              ? g.guia_remitente_serie + "-" + g.guia_remitente_numero
              : "SIN GUIA",
          placa: g.placas || "-",
          transportista_rs: g.empresa_transporte || "-",
          total_lotes: g.total_lotes || 0,
          peso_total_neto: g.peso_total_neto || 0,
          conductor_nombre: g.conductor_nombre || "-",
          estado_guia: g.estado_guia,
          raw: g, // data completa para edicion
        });
        totalGeneradas++;
      });
    } else {
      totalGeneradas = generadas.length;
    }

    // Ordenar listado por fecha egreso desc
    listado.sort(function (a, b) {
      return b.fecha_egreso.localeCompare(a.fecha_egreso);
    });

    $("#badge_pendientes").text(totalPendientes);
    $("#badge_guias").text(totalGeneradas);

    if (listado.length === 0) {
      $("#tbl_listado_unificado").html(
        '<tr><td colspan="10" class="text-center text-muted p-4">No se encontro informacion.</td></tr>',
      );
      return;
    }

    var html = "";
    listado.forEach(function (item, idx) {
      var isPendiente = item.tipo === "PENDIENTE";
      var rowClass = isPendiente ? "table-warning" : "table-success";
      if (item.estado_guia === "0") rowClass = "table-danger"; // Anulada

      var rowId = isPendiente
        ? "tr_unif_pend_" + idx
        : "tr_unif_guia_" + item.id_guia;
      var detailRowId = isPendiente
        ? "tr_unif_pend_detail_" + idx
        : "tr_unif_guia_detail_" + item.id_guia;

      // Guardar referencia para click handler
      if (isPendiente) {
        // La key es la misma usada para agrupacionesMap
        item._rowIdx = idx;
      }

      var clickHandler = isPendiente
        ? "window.f_VerDetalleUnificado('" + idx + "', 'pend');"
        : "window.f_VerDetalleUnificado('" + item.id_guia + "', 'guia');";

      html +=
        '<tr id="' +
        rowId +
        '" class="' +
        rowClass +
        ' align-middle" style="cursor:pointer;" onclick="' +
        clickHandler +
        '">';
      html += '<td class="text-center fw-bold">' + (idx + 1) + "</td>";
      html +=
        '<td class="text-center">' + formatDateDMY(item.fecha_egreso) + "</td>";

      if (isPendiente) {
        html +=
          '<td class="text-center"><span class="badge bg-secondary">PENDIENTE</span></td>';
      } else {
        html += '<td class="text-center"><b>' + item.nro_guia + "</b></td>";
      }

      html +=
        '<td class="text-center"><span class="badge bg-dark">' +
        item.placa +
        "</span></td>";
      html += "<td>";
      html +=
        '<div class="fw-bold text-truncate" style="max-width: 250px;">' +
        item.transportista_rs +
        "</div>";
      if (item.transportista_ruc)
        html +=
          '<div class="text-muted small">' + item.transportista_ruc + "</div>";
      html += "</td>";

      html +=
        '<td class="text-center"><span class="badge bg-info text-dark">' +
        item.total_lotes +
        "</span></td>";
      html +=
        '<td class="text-end font-monospace fw-bold">' +
        formatNumber(item.peso_total_neto) +
        "</td>";
      html +=
        '<td class="text-center small">' + item.conductor_nombre + "</td>";

      // Estado
      if (isPendiente) {
        html +=
          '<td class="text-center"><span class="badge bg-primary">POR REGISTRAR</span></td>';
      } else {
        var badgeEstado =
          item.estado_guia === "1"
            ? '<span class="badge bg-success">ACTIVA</span>'
            : '<span class="badge bg-danger">ANULADA</span>';
        html += '<td class="text-center">' + badgeEstado + "</td>";
      }

      // Acciones
      html += '<td class="text-center">';
      if (isPendiente) {
        // Guardamos en cache por índice
        if (!window._agrupacionesCache) window._agrupacionesCache = {};
        window._agrupacionesCache[idx] = item;

        html +=
          '<button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); window.f_AbrirModalGuiaUnificado(' +
          idx +
          ');">';
        html += '<i class="bi bi-file-earmark-plus"></i> Generar</button>';
      } else {
        html += '<div class="btn-group btn-group-sm">';
        html +=
          '<button class="btn btn-warning" onclick="event.stopPropagation(); window.f_EditarGuiaUnificada(' +
          item.id_guia +
          ')"><i class="bi bi-pencil"></i></button>';
        html +=
          '<button class="btn btn-danger" onclick="event.stopPropagation(); window.f_AnularGuia(' +
          item.id_guia +
          ')"><i class="bi bi-trash"></i></button>';
        html +=
          '<button class="btn btn-dark" onclick="event.stopPropagation(); window.f_ImprimirGuia(' +
          item.id_guia +
          ')"><i class="bi bi-printer"></i></button>';
        html += "</div>";
      }
      html += "</td>";
      html += "</tr>";

      // Fila de detalle inline (oculta por defecto)
      html +=
        '<tr id="' +
        detailRowId +
        '" style="display:none; background-color: #f0f4fa;">';
      html += '<td colspan="10" class="p-3">';
      if (isPendiente) {
        html += '<div class="card shadow-sm border-primary">';
        html +=
          '<div class="card-header bg-primary bg-opacity-10 py-1"><i class="bi bi-box-seam text-primary me-2"></i><strong>Lotes del Despacho</strong></div>';
        html += '<div class="card-body p-2">';
        html +=
          '<table class="table table-bordered table-sm tabla-lotes-guia mb-0 bg-white"><thead><tr>';
        html += '<th class="text-center" style="width:30px;">N°</th>';
        html += '<th class="text-center">Tipo</th>';
        html += "<th>Código Mineral</th>";
        html += "<th>Despacho</th>";
        html += '<th class="text-center">Presentación</th>';
        html += '<th class="text-end">P. Distribución (Kg)</th>';
        html += '<th class="text-end">P. Bruto (Kg)</th>';
        html += '<th class="text-end">Tara (Kg)</th>';
        html += '<th class="text-end fw-bold">P. Neto (Kg)</th>';
        html += "</tr></thead>";
        var tbodyId = "tbl_unif_detail_pend_" + idx;
        html +=
          '<tbody id="' +
          tbodyId +
          '"><tr><td colspan="9" class="text-center text-muted">Cargando...</td></tr></tbody>';
        html += "</table></div></div>";
      } else {
        // Para GENERADAS: contenedor simple, se llena al hacer click
        html +=
          '<div id="ctn_unif_guia_' +
          item.id_guia +
          '"><div class="text-center text-muted p-3"><span class="spinner-border spinner-border-sm"></span> Cargando...</div></div>';
      }
      html += "</td></tr>";
    });

    // Guardar listado para acceso en f_VerDetalleUnificado
    window._listadoUnificadoCache = listado;

    $("#tbl_listado_unificado").html(html);
  }

  window.f_VerDetalleUnificado = function (id, tipo) {
    var detailRowId =
      tipo === "pend"
        ? "tr_unif_pend_detail_" + id
        : "tr_unif_guia_detail_" + id;
    var mainRowId =
      tipo === "pend" ? "tr_unif_pend_" + id : "tr_unif_guia_" + id;
    var tbodyId = "tbl_unif_detail_pend_" + id; // solo para PENDIENTE

    var detailRow = $("#" + detailRowId);
    var mainRow = $("#" + mainRowId);

    if (detailRow.is(":visible")) {
      detailRow.hide();
      return;
    }

    // Ocultar otros detalles abiertos
    $("[id^='tr_unif_pend_detail_'], [id^='tr_unif_guia_detail_']").hide();
    $("[id^='tr_unif_pend_'], [id^='tr_unif_guia_']").removeClass(
      "table-active",
    );
    mainRow.addClass("table-active");
    detailRow.fadeIn(150);

    // PENDIENTE: cargar lotes via AJAX
    if (tipo === "pend") {
      var listado = window._listadoUnificadoCache || [];
      var pendItem = null;
      for (var i = 0; i < listado.length; i++) {
        if (
          listado[i].tipo === "PENDIENTE" &&
          listado[i]._rowIdx === parseInt(id)
        ) {
          pendItem = listado[i];
          break;
        }
      }
      if (!pendItem) return;
      var idsArr = pendItem.distribuciones.map(function (d) {
        return d.id_distribucion;
      });
      $("#" + tbodyId).html(
        '<tr><td colspan="9" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando lotes...</td></tr>',
      );
      f_callBackend("get_lotes_distribucion_grupo_2t", {
        ids_distribuciones: JSON.stringify(idsArr),
      }).done(function (resp) {
        if (resp.estado === 1 && resp.data.length > 0) {
          f_RenderizarLotesTabla(resp.data, "#" + tbodyId);
        } else {
          $("#" + tbodyId).html(
            '<tr><td colspan="9" class="text-center text-muted p-3">Sin lotes encontrados.</td></tr>',
          );
        }
      });
    }
    // GENERADA: cargar detalle completo de guía via AJAX
    else {
      var ctnId = "ctn_unif_guia_" + id;
      f_callBackend("get_guia_segundo_tramo_detalle", { id_guia: id })
        .done(function (resp) {
          if (resp.estado === 1 && resp.data) {
            f_RenderizarDetalleGuiaCompleto(resp.data, "#" + ctnId);
          } else {
            $("#" + ctnId).html(
              '<div class="text-center text-muted p-3">Sin información disponible.</div>',
            );
          }
        })
        .fail(function () {
          $("#" + ctnId).html(
            '<div class="text-center text-danger p-3">Error de conexión.</div>',
          );
        });
    }
  };

  // ========================
  // RENDERIZAR DETALLE COMPLETO DE GUÍA GENERADA
  // ========================
  function f_RenderizarDetalleGuiaCompleto(g, ctnSelector) {
    var nroGuia =
      (g.guia_remitente_serie || "?") + "-" + (g.guia_remitente_numero || "?");
    var nroGRT =
      g.sin_guia_transportista == 1
        ? "Sin GRT"
        : (g.guia_transportista_serie || "?") +
          "-" +
          (g.guia_transportista_numero || "?");

    var h = '<div class="card shadow-sm border-success">';
    h +=
      '<div class="card-header bg-success bg-opacity-10 py-1 d-flex justify-content-between align-items-center">';
    h +=
      '<span><i class="bi bi-file-earmark-text text-success me-2"></i><strong>Guía ' +
      nroGuia +
      "</strong></span>";
    h +=
      '<span class="badge bg-success">' +
      formatNumber(g.peso_total_neto) +
      " Kg neto</span>";
    h += "</div>";
    h += '<div class="card-body p-2">';

    // Cabecera info en 2 filas
    h += '<div class="row g-2 mb-2" style="font-size:15px !important;">';
    h +=
      '<div class="col-md-3"><span class="text-muted">Guía Remitente:</span> <strong>' +
      nroGuia +
      "</strong></div>";
    h +=
      '<div class="col-md-3"><span class="text-muted">Guía Transportista:</span> <strong>' +
      nroGRT +
      "</strong></div>";
    h +=
      '<div class="col-md-3"><span class="text-muted">Motivo:</span> ' +
      (g.motivo_traslado || "-") +
      "</div>";
    h +=
      '<div class="col-md-3"><span class="text-muted">Placa:</span> <span class="badge bg-dark">' +
      (g.placa || "-") +
      "</span></div>";
    h +=
      '<div class="col-md-4"><span class="text-muted">Transportista:</span> ' +
      (g.empresa_transporte || "-") +
      "</div>";
    h +=
      '<div class="col-md-4"><span class="text-muted">Conductor:</span> ' +
      (g.conductor_nombre || "-") +
      "</div>";
    h +=
      '<div class="col-md-4"><span class="text-muted">Planta Destino:</span> ' +
      (g.planta_destino || "-") +
      "</div>";
    h +=
      '<div class="col-md-4"><span class="text-muted">Inicio Traslado:</span> ' +
      formatDateDMY(g.fecha_inicio_traslado || "") +
      "</div>";
    h +=
      '<div class="col-md-4"><span class="text-muted">Emisión:</span> ' +
      (g.fecha_hora_emision || "-") +
      "</div>";
    h +=
      '<div class="col-md-4"><span class="text-muted">Llegada Planta:</span> ' +
      (g.fecha_hora_planta || "-") +
      "</div>";
    h += "</div>";

    // Tabla de lotes (sin columna Despacho)
    h +=
      '<table class="table table-bordered table-sm tabla-lotes-guia mb-0 bg-white"><thead><tr>';
    h += '<th class="text-center" style="width:30px;">N°</th>';
    h += '<th class="text-center">Tipo</th>';
    h += "<th>Código Mineral</th>";
    h += "<th>Ticket Balanza</th>";
    h += '<th class="text-center">Presentación</th>';
    h += '<th class="text-end">P. Distribución (Kg)</th>';
    h += '<th class="text-end">P. Bruto (Kg)</th>';
    h += '<th class="text-end">Tara (Kg)</th>';
    h += '<th class="text-end fw-bold">P. Neto (Kg)</th>';
    h += "</tr></thead><tbody>";

    var sumNeto = 0,
      sumBruto = 0,
      sumTara = 0,
      sumTomado = 0;
    var lotes = g.lotes || [];
    for (var i = 0; i < lotes.length; i++) {
      var l = lotes[i];
      var tipoBadge =
        l.is_blending == 1
          ? '<span class="badge badge-blending">Blending</span>'
          : '<span class="badge badge-lote">Lote</span>';
      var presentacion =
        l.tipo_carga == 2
          ? "Big Bag (" + (l.cantidad_bigbags || 0) + ")"
          : l.tipo_carga == 1
            ? "Granel"
            : "?";
      var neto = parseFloat(l.peso_neto) || 0;
      var bruto = parseFloat(l.peso_bruto) || 0;
      var tara = parseFloat(l.peso_tara) || 0;
      var tomado = parseFloat(l.peso_tomado) || 0;
      sumNeto += neto;
      sumBruto += bruto;
      sumTara += tara;
      sumTomado += tomado;

      h += "<tr>";
      h += '<td class="text-center">' + (i + 1) + "</td>";
      h += '<td class="text-center">' + tipoBadge + "</td>";
      h += '<td class="fw-bold">' + (l.codigo_mineral || "-") + "</td>";
      h +=
        '<td><small class="text-muted">' +
        (l.ticket_balanza || "-") +
        "</small></td>";
      h += '<td class="text-center">' + presentacion + "</td>";
      h +=
        '<td class="text-end font-monospace">' + formatNumber(tomado) + "</td>";
      h +=
        '<td class="text-end font-monospace">' + formatNumber(bruto) + "</td>";
      h +=
        '<td class="text-end font-monospace">' + formatNumber(tara) + "</td>";
      h +=
        '<td class="text-end font-monospace fw-bold text-success">' +
        formatNumber(neto) +
        "</td>";
      h += "</tr>";
    }

    // Totales
    h += '<tr class="table-light fw-bold">';
    h += '<td colspan="5" class="text-end text-uppercase small">Totales:</td>';
    h +=
      '<td class="text-end font-monospace">' +
      formatNumber(sumTomado) +
      "</td>";
    h +=
      '<td class="text-end font-monospace">' + formatNumber(sumBruto) + "</td>";
    h +=
      '<td class="text-end font-monospace">' + formatNumber(sumTara) + "</td>";
    h +=
      '<td class="text-end font-monospace text-success">' +
      formatNumber(sumNeto) +
      "</td>";
    h += "</tr>";

    h += "</tbody></table></div></div>";
    $(ctnSelector).html(h);
  }

  // ========================
  // VER DETALLE AGRUPACIÓN (función legacy, mantenida por compatibilidad)
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
        presentacion = "Granel";
      } else {
        presentacion = "?";
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
  window.f_AbrirModalGuiaUnificado = function (idx) {
    var item = window._agrupacionesCache[idx];
    if (!item) return alert("Error al recuperar datos de la agrupacion.");

    // Seteamos modo Nuevo
    $("#hd_modo_guia").val("N");
    $("#hd_id_guia").val("0");
    $("#modal_generar_guiaLabel").html(
      '<i class="bi bi-file-earmark-plus me-2"></i>Generar Guía de Remisión',
    );

    // IDs distribuciones
    var idsArr = item.distribuciones.map(function (d) {
      return d.id_distribucion;
    });
    $("#hd_ids_distribuciones").val(JSON.stringify(idsArr));

    // Planta Destino (tomamos la de la primera distribucion)
    $("#guia_planta_destino").val(
      item.distribuciones[0].nombre_sucursal_llegada || "JONGOS",
    );

    // Limpiar campos
    $(
      "#guia_rem_serie, #guia_rem_numero, #guia_transp_serie, #guia_transp_numero",
    ).val("");
    $("#chk_sin_grt").prop("checked", false).trigger("change");
    $("#guia_motivo_traslado").val("VENTA SUJETA A CONFIRMACIÓN");

    // Tolva (ahora opcional)
    $("#guia_marca_tolva, #guia_empresa_tolva").val("").trigger("change");
    $("#guia_serie_tolva, #guia_numero_tolva, #guia_mtc_tolva").val("");

    var dt = item;
    if (
      dt.tipo === "PENDIENTE" &&
      dt.distribuciones &&
      dt.distribuciones.length > 0
    ) {
      dt.marca = dt.distribuciones[0].marca || dt.marca;
      dt.codigo_mtc = dt.distribuciones[0].codigo_mtc || dt.codigo_mtc;
      dt.placa = dt.distribuciones[0].placa1 || dt.placa;
    }

    // Unidad info (Read-only)
    console.log("Datos de Unidad (Generar):", {
      transportista: dt.transportista_rs,
      marca: dt.marca,
      placa: dt.placa || dt.placa1,
      mtc: dt.codigo_mtc,
    });

    $("#guia_unit_transportista").val(dt.transportista_rs || "");
    $("#guia_unit_marca").val(dt.marca || "");
    $("#guia_unit_placa").val(dt.placa || dt.placa1 || "");
    $("#guia_unit_mtc").val(dt.codigo_mtc || "");

    // Resumen
    $("#div_resumen_modal").html(
      '<span class="badge bg-dark"><i class="bi bi-truck me-1"></i> ' +
        item.placa +
        "</span>" +
        '<span class="badge bg-info text-dark">' +
        item.total_lotes +
        " Lotes</span>" +
        '<span class="badge bg-success">Neto: ' +
        formatNumber(item.peso_total_neto) +
        " Kg</span>",
    );

    // Cargar lotes en tabla modal
    $("#tbl_modal_lotes").html(
      '<tr><td colspan="9" class="text-center p-2"><span class="spinner-border spinner-border-sm"></span></td></tr>',
    );

    f_callBackend("get_lotes_distribucion_grupo_2t", {
      ids_distribuciones: JSON.stringify(idsArr),
    }).done(function (resp) {
      if (resp.estado === 1 && resp.data.length > 0) {
        f_RenderizarLotesTabla(resp.data, "#tbl_modal_lotes");

        // Auto-completar datos de tolva buscando el primer registro que tenga placa real
        var regConTolva = resp.data.find(function (r) {
          var p2 = (r.serie_segunda_placa || "").trim();
          return p2 !== "" && p2 !== "-";
        });

        if (regConTolva) {
          if (regConTolva.id_empresa_transporte_tolva) {
            $("#guia_empresa_tolva")
              .val(regConTolva.id_empresa_transporte_tolva)
              .trigger("change");
          }
          if (regConTolva.serie_segunda_placa) {
            $("#guia_serie_tolva").val(regConTolva.serie_segunda_placa);
          }
          if (regConTolva.numero_segunda_placa) {
            $("#guia_numero_tolva").val(regConTolva.numero_segunda_placa);
          }
          // Si el backend devuelve MTC de la tolva, podrías ponerlo aquí también
          if (regConTolva.numero_mtc_tolva) {
            $("#guia_mtc_tolva").val(regConTolva.numero_mtc_tolva);
          }
        } else {
          // Sin segunda placa, limpiar por si acaso
          $("#guia_serie_tolva, #guia_numero_tolva").val("");
        }
      }
    });

    var modal = new bootstrap.Modal(
      document.getElementById("modal_generar_guia"),
    );
    modal.show();
  };

  // ========================
  // EDITAR GUÍA
  // ========================
  window.f_EditarGuiaUnificada = function (idGuia) {
    // Buscamos la data en el cache o recargamos (por ahora tomamos de generadas si esta ahi)
    // Pero lo mas seguro es llamar al backend por la data completa si no esta accesible
    f_callBackend("get_guia_segundo_tramo_detalle", { id_guia: idGuia }).done(
      function (resp) {
        if (resp.estado === 1) {
          var guiaData = resp.data;
          // Preparar UI del modal de EDICIÓN
          $("#hd_modo_guia_e").val("E");
          $("#hd_id_guia_e").val(idGuia);
          $("#modal_editar_guiaLabel").html(
            '<i class="bi bi-pencil-square me-2"></i>Editar Guía de Remisión',
          );

          // Seteamos fechas/horas
          if (guiaData.fecha_inicio_traslado)
            $("#guia_e_fecha_inicio_traslado").val(
              dmyToYmd(guiaData.fecha_inicio_traslado),
            );

          if (guiaData.fecha_hora_emision) {
            var p = guiaData.fecha_hora_emision.split(" ");
            $("#guia_e_fecha_emision").val(dmyToYmd(p[0]));
            if (p[1]) $("#guia_e_hora_emision").val(p[1]);
          }

          if (guiaData.fecha_hora_planta) {
            var pp = guiaData.fecha_hora_planta.split(" ");
            $("#guia_e_fecha_planta").val(dmyToYmd(pp[0]));
            if (pp[1]) $("#guia_e_hora_planta").val(pp[1]);
          }
          // ... (resto de seteos similares a f_EditarGuia original)
          $("#guia_e_planta_origen").val(guiaData.planta_origen || "1");
          $("#guia_e_planta_destino").val(
            guiaData.nombre_sucursal_llegada || "JONGOS",
          );
          $("#guia_e_rem_serie").val(guiaData.guia_remitente_serie || "");
          $("#guia_e_rem_numero").val(guiaData.guia_remitente_numero || "");
          $("#guia_e_motivo_traslado").val(guiaData.motivo_traslado || "");

          // Tolva
          $("#guia_e_marca_tolva")
            .val(guiaData.id_marca_tolva || "")
            .trigger("change");
          $("#guia_e_empresa_tolva")
            .val(guiaData.id_empresa_transporte_tolva || "")
            .trigger("change");
          $("#guia_e_serie_tolva").val(guiaData.serie_tolva || "");
          $("#guia_e_numero_tolva").val(guiaData.numero_tolva || "");
          $("#guia_e_mtc_tolva").val(guiaData.numero_mtc_tolva || "");

          // Unidad info (Read-only)
          $("#guia_e_unit_transportista").val(
            guiaData.empresa_transporte || "",
          );
          $("#guia_e_unit_marca").val(guiaData.marca || "");
          $("#guia_e_unit_placa").val(guiaData.placa || "");
          $("#guia_e_unit_mtc").val(guiaData.codigo_mtc || "");

          var sgrt = parseInt(guiaData.sin_guia_transportista);
          $("#chk_sin_grt_e")
            .prop("checked", sgrt === 1)
            .trigger("change");
          if (sgrt === 0) {
            $("#guia_e_transp_serie").val(
              guiaData.guia_transportista_serie || "",
            );
            $("#guia_e_transp_numero").val(
              guiaData.guia_transportista_numero || "",
            );
          }

          // Lotes
          var arrIds = guiaData.lotes.map(function (x) {
            return x.id_distribucion;
          });
          $("#hd_ids_distribuciones_e").val(JSON.stringify(arrIds));
          f_RenderizarLotesTabla(guiaData.lotes, "#tbl_modal_lotes_e");

          $("#div_resumen_modal_e").html(
            '<span class="badge bg-dark">' +
              (guiaData.placas || "-") +
              "</span>" +
              '<span class="badge bg-success">Neto: ' +
              formatNumber(guiaData.peso_total_neto) +
              " Kg</span>",
          );

          var modal = new bootstrap.Modal(
            document.getElementById("modal_editar_guia"),
          );
          modal.show();
        }
      },
    );
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
    var remSerie = $.trim($("#guia_rem_serie").val());
    var remNumero = $.trim($("#guia_rem_numero").val());
    var transpSerie = $.trim($("#guia_transp_serie").val());
    var transpNumero = $.trim($("#guia_transp_numero").val());
    var sinGRT = $("#chk_sin_grt").prop("checked") ? 1 : 0;
    var motivoTraslado = $("#guia_motivo_traslado").val();

    if (!fechaInicioTraslado) return alert("Fecha inicio traslado requerida.");
    if (!fechaEmision || !horaEmision)
      return alert("Fecha y hora emision requerida.");
    if (!plantaOrigen) return alert("Seleccione planta origen.");
    if (!remSerie || !remNumero) return alert("Guia remitente incompleta.");
    if (!motivoTraslado) return alert("Seleccione motivo traslado.");

    if (sinGRT === 0 && (!transpSerie || !transpNumero)) {
      return alert("Guia transportista incompleta o marque 'Sin Guia'.");
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
      html += '<th class="text-end">P. Distribución (Kg)</th>';
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
