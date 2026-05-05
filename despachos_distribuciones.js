document.addEventListener("DOMContentLoaded", function () {
  // VARIABLES GLOBALES
  // VARIABLES GLOBALES
  // const backendUrl is defined in the parent PHP file
  const modalNuevoDespacho = new window.bootstrap.Modal(
    document.getElementById("modal_nuevo_despacho"),
  );
  const modalNuevaDistribucion = new window.bootstrap.Modal(
    document.getElementById("modal_nueva_distribucion"),
  );

  let allDespachos = [];
  let mineralesDisponibles = [];
  let itemsDespachoDistribucion = [];
  let selectedDespachoId = 0;

  // Initialize Flatpickr
  const flatpickrConfig = {
    locale: "es",
    dateFormat: "d/m/Y",
    allowInput: true,
  };
  flatpickr("#filter_fecha_desde", flatpickrConfig);
  flatpickr("#filter_fecha_hasta", flatpickrConfig);
  flatpickr("#dist_fecha", flatpickrConfig);

  // FUNCIONES AUXILIARES
  function f_callBackend(accion, data) {
    return $.post(backendUrl, { accion: accion, ...data }, "json");
  }

  function formatNumber(num) {
    return parseFloat(num).toLocaleString("es-PE", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  }

  function formatDateToDMY(dateStr) {
    if (!dateStr) return "";
    // Asumiendo formato YYYY-MM-DD o YYYY-MM-DD HH:mm:ss
    let parts = dateStr.split(" ")[0].split("-");
    if (parts.length === 3) {
      return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
  }

  function dmyToYmd(dateStr) {
    if (!dateStr) return "";
    let parts = dateStr.split("/");
    if (parts.length === 3) {
      return `${parts[2]}-${parts[1]}-${parts[0]}`;
    }
    return dateStr;
  }

  // --------------------------------------------------------------------------------
  // VIEW LOGIC: LISTADO PRINCIPAL
  // --------------------------------------------------------------------------------

  window.loadAllDespachos = function () {
    $("#tbl_despachos").html(
      '<tr><td colspan="6" class="text-center p-3">Actualizando...</td></tr>',
    );

    f_callBackend("get_lista_despacho_cabecera", {})
      .done(function (r) {
        if (r.estado === 1) {
          allDespachos = r.data.despachos;
          populateFilters();
          applyFilters();
          if (selectedDespachoId) {
            selectDespacho(selectedDespachoId);
          }
        } else {
          console.error("Error al cargar despachos");
        }
      })
      .fail(function () {
        $("#tbl_despachos").html(
          '<tr><td colspan="6" class="text-center text-danger p-3">Error de conexión.</td></tr>',
        );
        $("#total_despachos").text("0");
      });
  };

  // --------------------------------------------------------------------------------
  // FILTER LOGIC
  // --------------------------------------------------------------------------------
  function populateFilters() {
    let uniquePlantas = {};
    let uniqueProveedores = {};

    allDespachos.forEach((d) => {
      if (!uniquePlantas[d.id_planta]) {
        uniquePlantas[d.id_planta] = {
          id: d.id_planta,
          text: d.descripcion_planta,
        };
      }
      if (!uniqueProveedores[d.id_proveedor]) {
        uniqueProveedores[d.id_proveedor] = {
          id: d.id_proveedor,
          text: d.razon_social,
        };
      }
    });

    // Convert to Arrays & Sort
    let arrPlantas = Object.values(uniquePlantas).sort((a, b) =>
      a.text.localeCompare(b.text),
    );
    let arrProvs = Object.values(uniqueProveedores).sort((a, b) =>
      a.text.localeCompare(b.text),
    );

    // Add 'Todos'
    arrPlantas.unshift({ id: "", text: "Todos" });
    arrProvs.unshift({ id: "", text: "Todos" });

    let $selPlanta = $("#filter_planta");
    let $selProv = $("#filter_proveedor");

    let curPlanta = $selPlanta.val();
    let curProv = $selProv.val();

    $selPlanta.empty().select2({ theme: "bootstrap-5", data: arrPlantas });
    $selProv.empty().select2({ theme: "bootstrap-5", data: arrProvs });

    if (curPlanta) $selPlanta.val(curPlanta).trigger("change.select2");
    if (curProv) $selProv.val(curProv).trigger("change.select2");
  }

  function applyFilters() {
    let fPlanta = $("#filter_planta").val();
    let fProv = $("#filter_proveedor").val();
    // let fEst = $("#filter_estado").val();
    let fDesde = dmyToYmd($("#filter_fecha_desde").val());
    let fHasta = dmyToYmd($("#filter_fecha_hasta").val());

    let filtered = allDespachos.filter((d) => {
      // Planta
      if (fPlanta && d.id_planta != fPlanta) return false;
      // Proveedor
      if (fProv && d.id_proveedor != fProv) return false;

      // Fechas
      let fecha = d.fecha_registro.substring(0, 10);
      if (fDesde && fecha < fDesde) return false;
      if (fHasta && fecha > fHasta) return false;

      return true;
    });

    renderDespachos(filtered);
  }

  $("#btn_aplicar_filtros").click(applyFilters);

  $("#btn_limpiar_filtros").click(function () {
    $("#filter_planta").val("").trigger("change");
    $("#filter_proveedor").val("").trigger("change");
    // $("#filter_estado").val('');
    $("#filter_fecha_desde").val("");
    $("#filter_fecha_hasta").val("");
    applyFilters();
  });

  // Export Handlers
  $("#btn_export_pdf").click(function () {
    let pl = $("#filter_planta").val() || "";
    let pv = $("#filter_proveedor").val() || "";
    let d1 = dmyToYmd($("#filter_fecha_desde").val()) || "";
    let d2 = dmyToYmd($("#filter_fecha_hasta").val()) || "";

    let url = `print_despachos_distribuciones.php?planta=${pl}&proveedor=${pv}&desde=${d1}&hasta=${d2}`;
    window.open(url, "_blank");
  });

  $("#btn_export_excel").click(function () {
    let pl = $("#filter_planta").val() || "";
    let pv = $("#filter_proveedor").val() || "";
    let d1 = dmyToYmd($("#filter_fecha_desde").val()) || "";
    let d2 = dmyToYmd($("#filter_fecha_hasta").val()) || "";

    // Excel - versio 1
    let url = `export_to_excel/export_despachos_distribuciones_data_cruda.php?planta=${pl}&proveedor=${pv}&desde=${d1}&hasta=${d2}`;
    window.open(url, "_blank");
  });

  function renderDespachos(dataList = allDespachos) {
    let html = "";
    if (dataList.length === 0) {
      html =
        '<tr><td colspan="6" class="text-center text-muted p-3">No hay despachos registrados o coincidentes.</td></tr>';
    } else {
      // Sort by Plant then by ID Descending
      dataList.sort((a, b) => {
        if (a.descripcion_planta < b.descripcion_planta) return -1;
        if (a.descripcion_planta > b.descripcion_planta) return 1;
        return b.id_despacho - a.id_despacho;
      });

      let lastPlantId = null;

      dataList.forEach((d) => {
        let totalItems = parseInt(d.blending_usados) + parseInt(d.lotes_usados);
        let estadoBadge = "";
        if (d.estado == "I" || d.estado == "0") {
          estadoBadge =
            '<span class="badge bg-danger" style="font-size:12px !important;">Anulado</span>';
        } else {
          estadoBadge =
            '<span class="badge bg-success" style="font-size:12px !important;">Activo</span>';
        }

        // Group Header
        if (d.id_planta !== lastPlantId) {
          html += `
                    <tr class="table-primary">
                        <td colspan="6" class="fw-bold text-uppercase" style="background-color: #e9ecef;">
                            <i class="bi bi-building me-2"></i>${d.descripcion_planta} 
                            <span class="text-muted fw-normal">(${d.ruc_planta})</span>
                        </td>
                    </tr>
                `;
          lastPlantId = d.id_planta;
        }

        html += `
                  <tr class="clickable-row ${d.id_despacho == selectedDespachoId ? "selected" : ""}" onclick="selectDespacho(${d.id_despacho}, this)" data-id="${d.id_despacho}">
                      <td class="text-center fw-bold">${d.correlativo}</td>
                      <td>
                        <div class="fw-bold">${d.razon_social}</div>
                        <div class="text-muted" style="font-size: 1em;">${d.documento_proveedor}</div>
                      </td>
                      <td class="text-center">${formatDateToDMY(d.fecha_registro)}</td>
                      <td class="text-center">
                        <span class="badge bg-secondary rounded-pill" style="font-size:12px !important;">${totalItems} Items</span>
                      </td>
                       <td class="text-center">${estadoBadge}</td>
                       <td class="text-center">
                         <button class="btn btn-sm btn-link text-primary"><i class="bi bi-eye-fill"></i></button>

                         ${parseInt(d.distribuciones_cerradas) > 0 ||
            parseInt(d.distribuciones_aprobadas) > 0
            ? `<button class="btn btn-sm btn-link text-secondary" title="No se puede anular, tiene distribuciones cerradas o aprobadas" disabled><i class="bi bi-trash-fill"></i></button>`
            : `<button class="btn btn-sm btn-link text-danger" onclick="anularDespacho(${d.id_despacho}, event)" title="Anular Despacho"><i class="bi bi-trash-fill"></i></button>`
          }
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

    let d = allDespachos.find((x) => x.id_despacho == id);
    if (!d) return;

    // Header Info
    $("#lbl_despacho_seleccionado").text(d.correlativo);
    $("#info_provider_plant").html(`
            <strong>Planta:</strong> ${d.descripcion_planta} &nbsp;|&nbsp; 
            <strong>Proveedor:</strong> ${d.razon_social} (${d.documento_proveedor})
          `);

    // Load Details
    $("#tbl_detalle_despacho").html(
      '<tr><td colspan="4" class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Cargando detalle...</td></tr>',
    );

    f_callBackend("get_despacho_detalle_by_despacho", { id_despacho: id }).done(
      function (r) {
        if (r.estado === 1) {
          renderDetalleDespacho(r.data.detalle_blending);
          selectedDespachoId = id;
          $("#btn_open_new_distribucion").prop("disabled", false);
          loadDistribuciones(id); // Cargar distribuciones
        }
      },
    );
  };

  function loadDistribuciones(idDespacho) {
    $("#tbl_distribuciones").html(
      '<tr><td colspan="4" class="text-center p-3">Cargando distribuciones...</td></tr>',
    );

    f_callBackend("get_distribuciones_by_despacho", {
      id_despacho: idDespacho,
    }).done(function (r) {
      let html = "";
      if (r.estado === 1 && r.data && r.data.distribuciones) {
        let dists = r.data.distribuciones;
        if (dists.length === 0) {
          html =
            '<tr><td colspan="6" class="text-center text-muted p-2">Sin distribuciones registradas.</td></tr>';
        } else {
          let contador = 1;
          dists.forEach((d) => {
            let meta = encodeURIComponent(JSON.stringify(d));

            let badgeEstado = "";
            let badgePeso = "";

            if (d.estado === "A") {
              badgeEstado =
                '<span class="badge bg-secondary" style="font-size: 11px;">Registrado</span>';
            } else if (d.estado === "B") {
              badgeEstado =
                '<span class="badge bg-primary" style="font-size: 11px;">En planta</span>';
            } else if (d.estado === "C") {
              badgeEstado =
                '<span class="badge bg-success" style="font-size: 11px;">Salió de planta</span>';
            } else if (d.estado === "D") {
              badgeEstado =
                '<span class="badge bg-info text-dark" style="font-size: 11px;">Llegó a destino</span>';
            } else if (d.estado === "E") {
              badgeEstado =
                '<span class="badge bg-dark" style="font-size: 11px;">Finalizado</span>';
            }

            if (d.estado_peso === "A") {
              badgePeso =
                '<span class="badge bg-success" style="font-size: 11px;">Pesado Completo</span>';
            } else if (d.estado_peso === "B") {
              badgePeso =
                '<span class="badge bg-warning text-dark" style="font-size: 11px;">Pesado Incompleto</span>';
            }
            let cellCierre = "";
            let btnActionCierre = "";

            // si la distribucion fue aprobada
            if (d.estado_cierre === "1") {
              cellCierre = `
                <div class="small fw-bold text-success"><i class="bi bi-lock-fill"></i> Cerrado</div>
                <div class="small text-muted" title="Cierre" style="font-size: 11px;">
                  <i class="bi bi-person"></i> ${d.usuario_cierre}<br>
                  <i class="bi bi-clock"></i> ${d.fecha_hora_cierre}
                </div>
              `;

              if (d.estado === "A") {
                // Only allow re-open if status is 'A' Activo
                btnActionCierre = `<button class="btn btn-sm btn-link text-secondary" onclick="abrirDistribucion(${d.id_distribucion}, event)" title="Reabrir Distribución"><i class="bi bi-unlock-fill"></i></button>`;
              }
            }
            // si no ha sido aprobada aun
            else {
              cellCierre = `-`;

              btnActionCierre = `<button class="btn btn-sm btn-link text-warning" onclick="cerrarDistribucion(${d.id_distribucion}, event)" title="Cerrar Distribución"><i class="bi bi-lock-fill"></i></button>`;
            }

            let deleteButton = "";
            // Si acaba de registrarse la distribucion pero no esta cerrada/aprobada
            if (
              (d.estado === "A" || d.estado === "0") &&
              d.estado_cierre !== "1"
            ) {
              deleteButton = `<button class="btn btn-sm btn-link text-danger" onclick="anularDistribucion(${d.id_distribucion}, event)" title="Anular Distribución"><i class="bi bi-trash-fill"></i></button>`;
            }

            let btnLlegada = "";
            if (d.estado === "C") {
              // salio de planta, por lo que puede indicar cuando llego a la planta de destino
              btnLlegada = `<button class="btn btn-sm btn-link text-success" onclick="abrirModalLlegadaDestino(${d.id_distribucion}, event)" title="Registrar Llegada a Destino"><i class="bi bi-geo-alt-fill"></i></button>`;
            }

            html += `
                    <tr>
                      <td class="text-center fw-bold">${contador}</td>
                      <td> 
                        <div class="fw-bold text-truncate" style="font-size:14px !important;">${d.tipo_vehiculo} | ${d.placa} ${d.serie_segunda_placa ? "(" + d.serie_segunda_placa + "-" + d.numero_segunda_placa + ")" : ""} | Cap. ${d.capacidad}</div>
                        <div class="text-muted" title="${d.nombre_transportista}">${d.nombre_transportista}</div>
                      </td>
                      <td class="text-center">
                        <div class="mb-1"><strong>Est:</strong> ${formatDateToDMY(d.fecha_estimada)}</div>
                        <div class="small text-muted" title="Registro">
                           <i class="bi bi-person"></i> ${d.usuario_registro || "Sistema"}<br>
                           <i class="bi bi-calendar-check text-success"></i> ${d.fecha_registro}
                        </div>
                      </td>
                      <td class="text-end fw-bold text-success align-middle">${formatNumber(d.peso_acumulado)}</td>
                      <td class="text-center align-middle">
                        <div>${badgePeso}</div>
                        <div class="mb-1 mt-1">${badgeEstado}</div>
                      </td>
                      <td class="text-center align-middle">
                        ${cellCierre}
                      </td>
                      <td class="text-center align-middle">
                          <button class="btn btn-sm btn-link text-primary" onclick="viewDistribucion(${d.id_distribucion}, '${meta}')" title="Ver Detalles">
                            <i class="bi bi-eye-fill"></i>
                          </button>
                          <button class="btn btn-sm btn-link text-info" onclick="viewTrazabilidad(${d.id_distribucion})" title="Ver Trazabilidad">
                            <i class="bi bi-clock-history"></i>
                          </button>
                          ${btnLlegada}
                          ${btnActionCierre}
                          ${deleteButton}
                      </td>
                    </tr>`;
            contador++;
          });
        }
      } else {
        html =
          '<tr><td colspan="6" class="text-center text-muted p-2">No se encontraron datos.</td></tr>';
      }
      $("#tbl_distribuciones").html(html);
    });
  }

  window.viewDistribucion = function (id, metaStr) {
    const modalView = new window.bootstrap.Modal(
      document.getElementById("modal_detalle_distribucion"),
    );
    let meta = JSON.parse(decodeURIComponent(metaStr));

    // Set Header Info
    let badgeEstado = "";
    let badgePeso = "";

    if (meta.estado === "A") {
      badgeEstado =
        '<span class="badge bg-secondary" style="font-size: 11px;">Registrado</span>';
    } else if (meta.estado === "B") {
      badgeEstado =
        '<span class="badge bg-primary" style="font-size: 11px;">En planta</span>';
    } else if (meta.estado === "C") {
      badgeEstado =
        '<span class="badge bg-success" style="font-size: 11px;">Salió de planta</span>';
    } else if (meta.estado === "D") {
      badgeEstado =
        '<span class="badge bg-info text-dark" style="font-size: 11px;">Llegó a destino</span>';
    } else if (meta.estado === "E") {
      badgeEstado =
        '<span class="badge bg-dark" style="font-size: 11px;">Finalizado</span>';
    }

    if (meta.estado_peso === "A") {
      badgePeso =
        '<span class="badge bg-success" style="font-size: 11px;">Pesado Completo</span>';
    } else if (meta.estado_peso === "B") {
      badgePeso =
        '<span class="badge bg-warning text-dark" style="font-size: 11px;">Pesado Incompleto</span>';
    }

    let badgeCierre = "";
    if (meta.estado_cierre === "1") {
      badgeCierre = `<br><span class="badge bg-success" style="font-size: 11px;" title="Cerrado por ${meta.usuario_cierre} el ${meta.fecha_hora_cierre}"><i class="bi bi-lock-fill"></i> Cerrado</span>`;
    }

    $("#view_dist_transportista").html(
      `<strong>${meta.documento_transportista}</strong> - ${meta.nombre_transportista}`,
    );
    $("#view_dist_estados").html(
      `${badgeEstado} <br> ${badgePeso || '<span class="badge bg-light text-dark border">Sin pesaje</span>'}${badgeCierre}`,
    );
    $("#view_dist_placa").html(
      `<strong>Unidad:</strong> ${meta.placa} | <strong>Vehículo:</strong> ${meta.tipo_vehiculo} | <strong>Segunda placa:</strong> ${meta.serie_segunda_placa ? meta.serie_segunda_placa + "-" + meta.numero_segunda_placa : "Ninguna"}`,
    );

    $("#view_dist_fecha").text(formatDateToDMY(meta.fecha_estimada));
    $("#view_dist_llegada").text(
      meta.fecha_hora_llegada ? meta.fecha_hora_llegada : "No registrado",
    );
    $("#view_dist_salida").text(
      meta.fecha_hora_salida ? meta.fecha_hora_salida : "No registrado",
    );
    $("#view_dist_total").text(formatNumber(meta.peso_acumulado));

    if (meta.estado === "D") {
      $("#thead_view_dist_items").html(`
        <tr>
          <th>Cód. Destino</th>
          <th class="text-end">Peso Destino</th>
          <th class="text-end">Ley Au</th>
          <th class="text-end">Ley Ag</th>
          <th class="text-end">Ley H2O</th>
          <th>Cód. Origen</th>
          <th class="text-center">Ticket</th>
          <th class="text-center">T. Carga</th>
          <th class="text-end">P. Neto O.</th>
          <th class="text-end" title="Número de partición/Lote origen">Nro.</th>
        </tr>
      `);
      $("#btn_guardar_detalles_destino")
        .removeClass("d-none")
        .data("id_dist", id);
      $("#btn_finalizar_distribucion")
        .removeClass("d-none")
        .data("id_dist", id);
    } else {
      $("#thead_view_dist_items").html(`
        <tr>
          <th>Cód. Destino</th>
          <th class="text-end">Peso Destino</th>
          <th class="text-end">Ley Au</th>
          <th class="text-end">Ley Ag</th>
          <th class="text-end">Ley H2O</th>
          <th>Cód. Origen</th>
          <th class="text-center">Ticket</th>
          <th class="text-center">T. Carga</th>
          <th class="text-end">P. Neto O.</th>
          <th class="text-end" title="Número de partición/Lote origen">Nro.</th>
        </tr>
      `);
      $("#btn_guardar_detalles_destino").addClass("d-none");
      $("#btn_finalizar_distribucion").addClass("d-none");
    }

    if (meta.estado === "E") {
      $("#btn_guardar_detalles_destino").addClass("d-none");
      $("#btn_finalizar_distribucion").addClass("d-none");
    }

    $("#tbl_view_dist_items").html(
      `<tr><td colspan="${meta.estado === "D" || meta.estado === "E" ? 9 : 9}" class="text-center">Cargando detalles...</td></tr>`,
    );

    modalView.show();

    // Fetch Details
    f_callBackend("get_detalle_distribucion_by_distribucion", {
      id_distribucion: id,
    }).done(function (r) {
      if (r.estado === 1) {
        let html = "";
        let items = r.data.detalles || [];
        let isFinalized = meta.estado === "E";

        if (items.length === 0) {
          html = `<tr><td colspan="${meta.estado === "D" || meta.estado === "E" ? 9 : 9}" class="text-center">Sin items.</td></tr>`;
        } else {
          items.forEach((i) => {
            let isBlending = i.is_blending == 1;
            let badge = isBlending
              ? '<span class="badge-mineral-type badge-blending">Blending</span>'
              : '<span class="badge-mineral-type badge-lote">Lote</span>';

            let txtCarga =
              i.tipo_carga == 2
                ? `Big Bags (${i.cantidad_bigbags || 0})`
                : `Granel`;

            let strTicket =
              i.ticket_balanza ||
              '<span class="text-muted fst-italic">Pdte.</span>';

            if (meta.estado === "D" || meta.estado === "E") {
              html += `
                <tr data-id-detalle="${i.id}">
                    <td><input type="text" class="form-control form-control-sm i-codigo" value="${i.codigo_en_planta_destino || ""}" placeholder="Cód. Destino" ${isFinalized ? 'disabled' : ''}></td>
                    <td><input type="number" step="0.001" class="form-control form-control-sm text-end i-peso" value="${i.peso_en_planta_destino || ""}" placeholder="0.00" ${isFinalized ? 'disabled' : ''}></td>
                    <td><input type="number" step="0.001" class="form-control form-control-sm text-end i-au" value="${i.ley_oro_en_planta_destino || ""}" placeholder="0.00" ${isFinalized ? 'disabled' : ''}></td>
                    <td><input type="number" step="0.001" class="form-control form-control-sm text-end i-ag" value="${i.ley_plata_en_planta_destino || ""}" placeholder="0.00" ${isFinalized ? 'disabled' : ''}></td>
                    <td><input type="number" step="0.001" class="form-control form-control-sm text-end i-h2o" value="${i.ley_humedad_en_planta_destino || ""}" placeholder="0.00" ${isFinalized ? 'disabled' : ''}></td>
                    <td class="align-middle text-muted" style="font-size: 0.9em;">${i.codigo}</td>
                    <td class="text-center align-middle" style="font-size: 0.85em;">${strTicket}</td>
                    <td class="text-center align-middle text-muted" style="font-size: 0.9em;">${txtCarga}</td>
                    <td class="text-end font-monospace align-middle text-success fw-bold">${formatNumber(i.peso_neto || 0)}</td>
                    <td class="text-end font-monospace align-middle text-muted" style="font-size: 0.9em;">${i.numero_parte != null ? i.numero_parte : "Total"}</td>
                </tr>
              `;
            } else {
              html += `
                  <tr>
                      <td>${i.codigo}</td>
                      <td class="text-center" style="font-size: 0.85em;">${strTicket}</td>
                      <td class="text-center">${badge}</td>
                      <td class="text-center">${txtCarga}</td>
                      <td class="text-end font-monospace">${formatNumber(i.peso_tara || 0)}</td>
                      <td class="text-end font-monospace">${formatNumber(i.peso_bruto || 0)}</td>
                      <td class="text-end font-monospace fw-bold text-success">${formatNumber(i.peso_neto || 0)}</td>
                      <td class="text-end font-monospace">${formatNumber(i.peso_tomado || 0)}</td>
                      <td class="text-end font-monospace">${i.numero_parte != null ? i.numero_parte : "Dis. Total"}</td>
                  </tr>
                `;
            }
          });
        }
        $("#tbl_view_dist_items").html(html);
      } else {
        $("#tbl_view_dist_items").html(
          `<tr><td colspan="${meta.estado === "D" ? 9 : 9}" class="text-center text-danger">Error al cargar.</td></tr>`,
        );
      }
    });
  };

  function renderDetalleDespacho(detalles) {
    let html = "";
    let totalPeso = 0;

    if (!detalles || detalles.length === 0) {
      html =
        '<tr><td colspan="4" class="text-center text-muted">Sin detalles.</td></tr>';
    } else {
      detalles.forEach((item) => {
        let isBlending = item.is_blending == 1;
        let badge = isBlending
          ? '<span class="badge-mineral-type badge-blending" style="font-size:12px !important;">Blending</span>'
          : '<span class="badge-mineral-type badge-lote" style="font-size:12px !important;">Lote</span>';

        html += `
                  <tr>
                      <td class="text-center">${badge}</td>
                      <td class="fw-bold text-dark">${item.codigo}</td>
                      <td class="text-end text-muted">${formatNumber(item.peso_actual_log)}</td>
                      <td class="text-end fw-bold text-primary">${formatNumber(item.peso_tomado)}</td>
                      <td class="text-end fw-bold text-primary">${formatNumber(item.peso_distribuido)}</td>
                  </tr>
                  `;
        totalPeso += parseFloat(item.peso_tomado);
      });
    }

    $("#tbl_detalle_despacho").html(html);
    $("#lbl_total_peso_despacho").text(formatNumber(totalPeso));

    // Calcular total distribuido
    let totalDist = 0;
    if (detalles && detalles.length > 0) {
      detalles.forEach((item) => {
        totalDist += parseFloat(item.peso_distribuido || 0);
      });
    }
    $("#lbl_total_peso_distribuido").text(formatNumber(totalDist));

    $("#tfoot_detalle_despacho").show();
  }

  // --------------------------------------------------------------------------------
  // LOGIC: TRAZABILIDAD TIMELINE
  // --------------------------------------------------------------------------------

  window.viewTrazabilidad = function (id) {
    const modalTrazabilidad = new window.bootstrap.Modal(
      document.getElementById("modal_trazabilidad_distribucion")
    );
    let html = '<li><div class="timeline-item"><div class="timeline-body text-center">Cargando trazabilidad...</div></div></li>';
    $("#trazabilidad_timeline").html(html);
    modalTrazabilidad.show();

    f_callBackend("get_trazabilidad_by_distribucion", { id_distribucion: id }).done(function (r) {
      if (r.estado === 1) {
        let logs = r.data || [];
        if (logs.length === 0) {
          $("#trazabilidad_timeline").html('<li><div class="timeline-item"><div class="timeline-body text-center text-muted">No hay registros de trazabilidad para esta unidad.</div></div></li>');
        } else {

          let estadoColors = {
            'Registrado': { badge: 'bg-primary', border: '#0d6efd', icon: 'bi-journal-check' },
            'Distribución Cerrada': { badge: 'bg-success', border: '#198754', icon: 'bi-lock-fill' },
            'Distribución Abierta': { badge: 'bg-warning', border: '#ffc107', icon: 'bi-unlock-fill' },
            'Llegó a Destino': { badge: 'bg-info', border: '#0dcaf0', icon: 'bi-geo-alt-fill' },
            'Pesaje Completo': { badge: 'bg-success', border: '#198754', icon: 'bi-check-circle-fill' },
            'Pesaje Modificado': { badge: 'bg-warning', border: '#ffc107', icon: 'bi-exclamation-circle-fill' },
            'Pesaje Confirmado': { badge: 'bg-success', border: '#198754', icon: 'bi-check-circle-fill' },
            'Guía Creada': { badge: 'bg-primary', border: '#0d6efd', icon: 'bi-file-earmark-text' },
            'Guía Modificada': { badge: 'bg-info', border: '#0dcaf0', icon: 'bi-pencil-square' },
            'Guía Anulada': { badge: 'bg-danger', border: '#dc3545', icon: 'bi-file-earmark-x' },
            'Finalizado': { badge: 'bg-dark', border: '#212529', icon: 'bi-flag-fill' }
          };

          html = "";
          logs.forEach((log) => {
            let colorConfig = estadoColors[log.estado] || { badge: 'bg-primary', border: '#0d6efd', icon: 'bi-info-circle-fill' };

            html += `
              <li>
                <div class="timeline-badge ${colorConfig.badge}"></div>
                <div class="timeline-item" style="border-left-color: ${colorConfig.border};">
                  <h3 class="timeline-header">
                    <span><i class="${colorConfig.icon} me-2" style="color: ${colorConfig.border};"></i>${log.estado}</span>
                    <span class="time"><i class="bi bi-calendar-event me-1"></i> ${log.created_at}</span>
                  </h3>
                  <div class="timeline-body">
                    ${log.descripcion}
                    <div class="mt-2 text-end"><span class="badge bg-light text-dark border pt-1 pb-1 px-2"><i class="bi bi-person-fill text-secondary"></i> ${log.empleado}</span></div>
                  </div>
                </div>
              </li>
             `;
          });
          $("#trazabilidad_timeline").html(html);
        }
      } else {
        $("#trazabilidad_timeline").html('<li><div class="timeline-item"><div class="timeline-body text-center text-danger">Error obteniendo trazabilidad.</div></div></li>');
      }
    }).fail(function () {
      $("#trazabilidad_timeline").html('<li><div class="timeline-item"><div class="timeline-body text-center text-danger">Error de conexión.</div></div></li>');
    });
  };


  // --------------------------------------------------------------------------------
  // MODAL LOGIC: NUEVO DESPACHO
  // --------------------------------------------------------------------------------

  // Paso 0: Abrir Modal -> Cargar Plantas
  $("#btn_open_new_despacho_modal").click(function () {
    f_openDespachoModal();
  });

  function f_openDespachoModal() {
    // UI Reset
    $("#modal_nuevo_despacho .modal-title").html(
      '<i class="bi bi-send-plus"></i> Crear Nuevo Despacho',
    );
    $("#btn_crear_despacho").text("Crear Despacho");

    $("#reg_planta")
      .empty()
      .append('<option value="">Cargando...</option>')
      .prop("disabled", false);
    $("#reg_proveedor").empty().prop("disabled", true);

    $("#btn_buscar_minerales").prop("disabled", true);
    $("#tbl_minerales_disponibles").html(
      '<tr><td colspan="5" class="text-center text-muted py-4">Seleccione planta y proveedor.</td></tr>',
    );
    $("#lbl_total_modal").text("0.00");
    $("#btn_crear_despacho").prop("disabled", true);

    // Get Plantas
    f_callBackend("get_plantas_to_despachos", {}).done(function (r) {
      if (r.estado === 1) {
        let opts = r.data.plantas.map((p) => ({
          id: p.id_planta,
          text: `${p.descripcion} (RUC: ${p.ruc})`,
        }));

        $("#reg_planta")
          .empty()
          .select2({
            dropdownParent: $("#modal_nuevo_despacho"),
            theme: "bootstrap-5",
            placeholder: "Seleccione Planta Destino",
            data: opts,
            width: "100%",
          })
          .val("")
          .trigger("change");
      }
    });

    modalNuevoDespacho.show();
  }

  // Paso 1: Configurar Change de Planta -> Cargar Proveedores
  $("#reg_planta").on("change", function () {
    let idPlanta = $(this).val();

    $("#reg_proveedor").empty().prop("disabled", true);
    $("#btn_buscar_minerales").prop("disabled", true);

    if (idPlanta) {
      f_callBackend("get_proveedores_by_planta", { id_planta: idPlanta }).done(
        function (r) {
          if (r.estado === 1) {
            let sortedProveedores = r.data.proveedores.sort((a, b) =>
              a.razon_social.localeCompare(b.razon_social),
            );
            let opts = sortedProveedores.map((p) => ({
              id: p.id_proveedor,
              text: `${p.razon_social} (${p.documento})`,
            }));
            $("#reg_proveedor")
              .prop("disabled", false)
              .select2({
                dropdownParent: $("#modal_nuevo_despacho"),
                theme: "bootstrap-5",
                placeholder: "Seleccione Proveedor",
                data: opts,
                width: "100%",
              })
              .val("")
              .trigger("change");
          }
        },
      );
    }
  });

  // Paso 2: Configurar Change de Proveedor -> Activar Botón Buscar
  $("#reg_proveedor").on("change", function () {
    let val = $(this).val();
    $("#btn_buscar_minerales").prop("disabled", !val);
    // Limpiar tabla si cambia proveedor
    $("#tbl_minerales_disponibles").html(
      '<tr><td colspan="5" class="text-center text-muted py-4">Haga clic en Listar Lotes/Blendings.</td></tr>',
    );
  });

  // Paso 3: Click Buscar Minerales
  $("#btn_buscar_minerales").click(function () {
    let idProv = $("#reg_proveedor").val();
    if (!idProv) return;

    let $btn = $(this);
    $btn
      .prop("disabled", true)
      .html('<span class="spinner-border spinner-border-sm"></span>');

    f_callBackend("get_minerales_to_despacho_by_proveedor", {
      id_proveedor: idProv,
      id_planta: $("#reg_planta").val(),
    }).done(function (r) {
      $btn
        .prop("disabled", false)
        .html('<i class="bi bi-search"></i> Listar Lotes/Blendings');

      if (r.estado === 1) {
        mineralesDisponibles = r.data.minerales;
        renderMineralesTable();
      } else {
        alert("Error al obtener minerales.");
      }
    });
  });

  function renderMineralesTable() {
    let html = "";
    if (mineralesDisponibles.length === 0) {
      html =
        '<tr><td colspan="5" class="text-center">El proveedor no tiene Lotes o Blendings disponibles.</td></tr>';
    } else {
      mineralesDisponibles.forEach((m, index) => {
        let isBlending = m.is_blending == 1; // Ensure boolean check works with response
        let badge = isBlending
          ? '<span class="badge-mineral-type badge-blending">Blending</span>'
          : '<span class="badge-mineral-type badge-lote">Lote</span>';

        // Validation for Blendings
        let isDisabled = false;
        let warningIcon = "";
        let eyeBtn = "";

        if (isBlending) {
          eyeBtn = `<button type="button" class="btn btn-sm btn-link text-info p-0 ms-2" title="Ver Detalle de Blending Sugerido" onclick="window.showBlendingDetailsSugerido(${m.id_mineral}, '${m.codigo}', event)">
                      <i class="bi bi-eye-fill"></i>
                    </button>`;
        }

        if (isBlending && parseInt(m.all_proveedores_asociados) === 0) {
          isDisabled = true;
          warningIcon = ` <i class="bi bi-question-circle-fill text-danger ms-2 cursor-pointer" 
                                  onclick="window.showBlendingIssues(${index}, event)" 
                                  title="Ver detalles de proveedores no asociados"></i>`;
        }

        // Use unique ID for row/checkbox
        let uniqueId = `min_${isBlending ? "B" : "L"}_${m.id_mineral}`;

        html += `
                  <tr class="${isBlending ? "table-info" : ""} ${isDisabled ? "table-secondary opacity-75" : ""}">
                      <td class="text-center">
                          <input type="checkbox" class="form-check-input chk-min" 
                            id="${uniqueId}"
                            data-idx="${index}"
                            ${isDisabled ? "disabled" : ""}>
                      </td>
                      <td>
                        <label class="form-check-label w-100 ${isDisabled ? "" : "cursor-pointer"}" for="${uniqueId}">
                            ${m.codigo} ${warningIcon} ${eyeBtn}
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
  $(document).on("change", ".chk-min", function () {
    let idx = $(this).data("idx");
    let checked = $(this).is(":checked");

    let $input = $(`.input-peso-despacho[data-idx='${idx}']`);
    $input.prop("disabled", !checked);

    if (checked) {
      $input.focus();
      let pesoTotal = mineralesDisponibles[idx].peso_actual;
      $input.val(pesoTotal);
    } else {
      $input.val("");
    }
    updateTotalModal();
  });

  $(document).on("input", ".input-peso-despacho", function () {
    let max = parseFloat($(this).data("max"));
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
    $("#btn_crear_despacho").prop("disabled", total <= 0);
  }

  // Paso 5: CREAR / EDITAR DESPACHO (Unified)
  $("#btn_crear_despacho").click(function () {
    let lista = [];

    $(".chk-min:checked").each(function () {
      let idx = $(this).data("idx");
      let val = parseFloat($(`.input-peso-despacho[data-idx='${idx}']`).val());
      let min = mineralesDisponibles[idx];

      if (val > 0) {
        lista.push({
          id_mineral: min.id_mineral,
          is_blending: min.is_blending,
          peso_tomado: val,
        });
      }
    });

    if (lista.length === 0 && !confirm("¿Guardar sin minerales? (Vacío)"))
      return;

    let action = "crear_despacho";
    let payload = {
      id_planta: $("#reg_planta").val(),
      id_proveedor: $("#reg_proveedor").val(),
      // estado: $("#reg_estado").val(),
      minerales: lista,
    };

    if (!confirm("¿Confirmar operación?")) return;

    let $btn = $(this);
    $btn.prop("disabled", true).text("Procesando...");

    f_callBackend(action, payload)
      .done(function (r) {
        if (r.estado === 1) {
          alert(r.mensaje || "Operación exitosa.");
          modalNuevoDespacho.hide();
          if (r.data && r.data.id_despacho) {
            selectedDespachoId = r.data.id_despacho;
          }
          loadAllDespachos();
        } else {
          alert("Error: " + r.mensaje);
        }
      })
      .always(function () {
        $btn.prop("disabled", false).html("Crear Despacho");
      });
  });

  window.anularDespacho = function (id, e) {
    if (e) e.stopPropagation();
    if (!confirm("¿ANULAR Despacho? Se revertirá todo el stock.")) return;
    f_callBackend("anular_despacho", { id_despacho: id }).done(function (r) {
      if (r.estado === 1) {
        alert("Anulado.");
        loadAllDespachos();
        // If we are looking at this dispatch, clear view
        if (id == selectedDespachoId) {
          selectedDespachoId = 0;
          $("#lbl_despacho_seleccionado").text("---");
          $("#info_provider_plant").text(
            "Seleccione un despacho para ver detalles.",
          );
          $("#tbl_detalle_despacho").html(
            '<tr><td colspan="5" class="text-center text-muted p-3">---</td></tr>',
          );
          $("#tfoot_detalle_despacho").hide();
          $("#tbl_distribuciones").html(
            '<tr><td colspan="6" class="text-center text-muted p-3">Seleccione un despacho para ver sus distribuciones.</td></tr>',
          );
          $("#btn_open_new_distribucion").prop("disabled", true);
        }
      } else alert("Error: " + r.mensaje);
    });
  };

  window.anularDistribucion = function (id, e) {
    if (e) e.stopPropagation();
    if (!confirm("¿ANULAR Distribución? Se devolverá peso al Despacho."))
      return;
    f_callBackend("anular_distribucion", { id_distribucion: id }).done(
      function (r) {
        if (r.estado === 1) {
          alert("Anulado.");
          if (selectedDespachoId) {
            selectDespacho(selectedDespachoId); // Reloads everything
          }
          if (typeof loadAllDespachos === "function") {
            loadAllDespachos();
          }
        } else alert("Error: " + r.mensaje);
      },
    );
  };

  // --------------------------------------------------------------------------------
  // MODAL LOGIC: NUEVA DISTRIBUCION
  // --------------------------------------------------------------------------------

  $("#btn_open_new_distribucion").click(function () {
    if (!selectedDespachoId) return;

    // Limpiar UI
    $("#dist_transportista, #dist_tipo_vehiculo")
      .empty()
      .append('<option value="">Cargando...</option>');
    $("#dist_unidad").empty().prop("disabled", true);
    $("#dist_serie_segunda_placa").val("");
    $("#dist_numero_segunda_placa").val("");
    $("#dist_fecha").val(new Date().toISOString().split("T")[0]); // Default today
    $("#tbl_items_distribucion").html(
      '<tr><td colspan="7" class="text-center p-3">Cargando items...</td></tr>',
    );
    $("#lbl_total_dist_modal").text("0.00");
    $("#btn_guardar_distribucion").prop("disabled", true);

    modalNuevaDistribucion.show();

    // Load Initial Data
    $.when(
      f_callBackend("get_lista_transportistas", {}),
      f_callBackend("get_tipos_vehiculo", {}),
      f_callBackend("get_minerales_of_despacho_to_distribucion", {
        id_despacho: selectedDespachoId,
      }),
    ).done(function (rTr, rTv, rMin) {
      let r1 = rTr[0];
      let r2 = rTv[0];
      let r3 = rMin[0];

      if (r1.estado === 1) {
        let opts = r1.data.transportistas.map((t) => ({
          id: t.id_transportista,
          text: t.razon_social + " (" + t.documento + ")",
        }));
        $("#dist_transportista, #dist_empresa_tolva")
          .empty()
          .select2({
            dropdownParent: $("#modal_nueva_distribucion"),
            theme: "bootstrap-5",
            placeholder: "Seleccione Empresa",
            data: opts,
            width: "100%",
          })
          .val("")
          .trigger("change");
      }

      if (r2.estado === 1) {
        let types = r2.data.tipos_vehiculo || [];
        $("#dist_tipo_vehiculo")
          .empty()
          .select2({
            dropdownParent: $("#modal_nueva_distribucion"),
            theme: "bootstrap-5",
            placeholder: "Seleccione Tipo",
            data: types.map((t) => ({
              id: t.id_tipo_vehiculo,
              text: t.descripcion,
            })),
            width: "100%",
          })
          .val("")
          .trigger("change");
      }

      if (r3.estado === 1) {
        itemsDespachoDistribucion = r3.data.minerales || [];
        renderItemsDistribucion();
      }
    });
  });

  // Cascading Dropdowns
  function loadUnidades() {
    let idTr = $("#dist_transportista").val();
    let idTv = $("#dist_tipo_vehiculo").val();

    $("#dist_unidad").empty().prop("disabled", true);

    if (idTr) {
      // Cargar Empresa Tolva igual al transportista (campo oculto)
      $("#dist_empresa_tolva").val(idTr).trigger("change");

      // Cargar Unidades (Placa 1)
      if (idTv) {
        f_callBackend("get_unidades_transporte_to_distribucion", {
          id_transportista: idTr,
          id_tipo_vehiculo: idTv,
        }).done(function (r) {
          if (r.estado === 1) {
            let units = r.data.unidades || [];
            if (units.length > 0) {
              let opts = units.map((u) => ({
                id: u.id_unidad,
                text: `${u.placa} (Cap: ${u.capacidad})`,
                capacidad: parseFloat(u.capacidad) || 0,
              }));
              $("#dist_unidad")
                .prop("disabled", false)
                .select2({
                  dropdownParent: $("#modal_nueva_distribucion"),
                  theme: "bootstrap-5",
                  placeholder: "Seleccione Unidad",
                  data: opts,
                  width: "100%",
                });
            }
          }
        });
      }
    }
  }

  function loadCarretas() {
    let idTr = $("#dist_transportista").val();
    $("#dist_tolva").empty().prop("disabled", true);

    if (idTr) {
      // Cargar Carretas (Placa 2 - Tolva)
      f_callBackend("get_unidades_transporte_to_distribucion", {
        id_transportista: idTr,
        id_tipo_vehiculo: 7, // 7 = Carreta
      }).done(function (r) {
        if (r.estado === 1) {
          let units = r.data.unidades || [];
          let opts = units.map((u) => ({
            id: u.id_unidad,
            text: u.placa,
          }));
          $("#dist_tolva")
            .prop("disabled", false)
            .select2({
              dropdownParent: $("#modal_nueva_distribucion"),
              theme: "bootstrap-5",
              placeholder: "Seleccione Tolva",
              data: opts,
              width: "100%",
            })
            .val("")
            .trigger("change");
        }
      });
    }
  }

  $("#dist_transportista").on("change", function () {
    loadUnidades();
    loadCarretas();
  });

  $("#dist_tipo_vehiculo").on("change", function () {
    loadUnidades();
  });

  // --------------------------------------------------------------------------------
  // MODAL LOGIC: NUEVA
  // --------------------------------------------------------------------------------

  $("#btn_open_new_distribucion").click(function () {
    f_openDistribucionModal();
  });

  function f_openDistribucionModal() {
    if (!selectedDespachoId) return;

    // UI Reset
    $("#modal_nueva_distribucion .modal-title").html(
      '<i class="bi bi-truck"></i> Nueva Distribución',
    );
    $("#btn_guardar_distribucion").text("Guardar Distribución");

    $("#dist_transportista, #dist_tipo_vehiculo")
      .empty()
      .append('<option value="">Cargando...</option>');
    $("#dist_unidad").empty().prop("disabled", true);
    $("#dist_segunda_placa").val("");
    // Default today DD/MM/YYYY
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, "0");
    let mm = String(today.getMonth() + 1).padStart(2, "0");
    let yyyy = today.getFullYear();
    $("#dist_fecha").val(`${dd}/${mm}/${yyyy}`);

    $("#tbl_items_distribucion").html(
      '<tr><td colspan="7" class="text-center p-3">Cargando items...</td></tr>',
    );
    $("#lbl_total_dist_modal").text("0.00");
    $("#btn_guardar_distribucion").prop("disabled", true);

    modalNuevaDistribucion.show();

    // Load Data Chain
    $.when(
      f_callBackend("get_lista_transportistas", {}),
      f_callBackend("get_tipos_vehiculo", {}),
      f_callBackend("get_minerales_of_despacho_to_distribucion", {
        id_despacho: selectedDespachoId,
      }),
    ).done(function (rTr, rTv, rMin) {
      let r1 = rTr[0];
      let r2 = rTv[0];
      let r3 = rMin[0];

      if (r1.estado === 1) {
        let opts = r1.data.transportistas.map((t) => ({
          id: t.id_transportista,
          text: t.razon_social + " (" + t.documento + ")",
        }));
        $("#dist_transportista, #dist_empresa_tolva")
          .empty()
          .select2({
            dropdownParent: $("#modal_nueva_distribucion"),
            theme: "bootstrap-5",
            placeholder: "Seleccione Empresa",
            data: opts,
          })
          .val("")
          .trigger("change");
      }

      if (r2.estado === 1) {
        let types = r2.data.tipos_vehiculo || [];
        $("#dist_tipo_vehiculo")
          .empty()
          .select2({
            dropdownParent: $("#modal_nueva_distribucion"),
            theme: "bootstrap-5",
            placeholder: "Seleccione Tipo",
            data: types.map((t) => ({
              id: t.id_tipo_vehiculo,
              text: t.descripcion,
            })),
          })
          .val("")
          .trigger("change");
      }

      if (r3.estado === 1) {
        itemsDespachoDistribucion = r3.data.minerales || [];
        renderItemsDistribucion();
      }
    });
  }

  function renderItemsDistribucion() {
    let html = "";
    if (itemsDespachoDistribucion.length === 0) {
      html =
        '<tr><td colspan="5" class="text-center">No hay saldo disponible en este despacho.</td></tr>';
    } else {
      itemsDespachoDistribucion.forEach((m, idx) => {
        let isBlending = m.is_blending == 1;
        let badge = isBlending
          ? '<span class="badge-mineral-type badge-blending">Blending</span>'
          : '<span class="badge-mineral-type badge-lote">Lote</span>';
        let restante = parseFloat(m.peso_actual);
        let uid = `dist_item_${m.id_despacho_detalle}`;

        html += `
                    <tr>
                        <td class="text-center" width="40">
                            <input type="checkbox" class="form-check-input chk-dist-item" id="${uid}" data-idx="${idx}">
                        </td>
                        <td><label class="cursor-pointer w-100" for="${uid}">${m.codigo}</label></td>
                        <td class="text-center">${badge}</td>
                        <td class="text-end text-muted font-monospace">${formatNumber(restante)}</td>
                        <td class="text-center">
                            <select class="form-select form-select-sm select-dist-tipo" data-idx="${idx}" disabled>
                                <option value="1" selected>Granel</option>
                                <option value="2">Bigbag</option>
                            </select>
                        </td>
                        <td width="120">
                            <input type="number" class="form-control form-control-sm text-end input-cant-bigbags" 
                                data-idx="${idx}" disabled placeholder="0" step="1">
                        </td>
                        <td class="text-end" width="180">
                            <input type="number" class="form-control form-control-sm text-end input-dist-peso" 
                                data-idx="${idx}" data-max="${restante}" disabled placeholder="0.00" step="0.01">
                        </td>
                    </tr>
                   `;
      });
    }
    $("#tbl_items_distribucion").html(html);
  }

  // Checkbox / Input Logic
  $(document).on("change", ".chk-dist-item", function () {
    let idx = $(this).data("idx");
    let chk = $(this).is(":checked");
    let $inp = $(`.input-dist-peso[data-idx='${idx}']`);
    let $tipo_carga = $(`.select-dist-tipo[data-idx='${idx}']`);
    let $q_bigbags = $(`.input-cant-bigbags[data-idx='${idx}']`);

    $inp.prop("disabled", !chk);
    $tipo_carga.prop("disabled", !chk);

    // Reset BigBags input disabled state logic
    if (!chk) {
      $q_bigbags.prop("disabled", true).val("");
      $inp.val("");
      $tipo_carga.val("1"); // Default to Granel
    } else {
      $inp.focus();
      let pesoRestante = itemsDespachoDistribucion[idx].peso_actual;
      $inp.val(pesoRestante);

      // Check current type logic
      let currentType = $tipo_carga.val();
      $q_bigbags.prop("disabled", currentType != "2");
    }

    updateTotalDist();
  });

  $(document).on("change", ".select-dist-tipo", function () {
    let idx = $(this).data("idx");
    let val = $(this).val();
    let $q_bigbags = $(`.input-cant-bigbags[data-idx='${idx}']`);

    if (val == "2") {
      $q_bigbags.prop("disabled", false).focus();
    } else {
      $q_bigbags.prop("disabled", true).val("");
    }
  });

  $(document).on("input", ".input-dist-peso", function () {
    let max = parseFloat($(this).data("max"));
    let val = parseFloat($(this).val());
    if (val < 0) $(this).val(0);
    if (val > max) {
      alert("Excede el peso restante: " + max);
      $(this).val(max);
    }
    updateTotalDist();
  });

  function updateTotalDist() {
    let total = 0;
    $(".input-dist-peso:enabled").each(function () {
      let v = parseFloat($(this).val());
      if (!isNaN(v)) total += v;
    });
    $("#lbl_total_dist_modal").text(formatNumber(total));
    $("#btn_guardar_distribucion").prop("disabled", total <= 0);
  }

  // Guardar Distribucion
  $("#btn_guardar_distribucion").click(function () {
    let idUnidad = $("#dist_unidad").val();
    let id_empresa_transporte = $("#dist_transportista").val();
    let fecha = dmyToYmd($("#dist_fecha").val());

    if (!idUnidad) {
      alert("Seleccione una unidad.");
      return;
    }

    if (!id_empresa_transporte) {
      alert("Seleccione una empresa de transporte.");
      return;
    }

    let $btn = $(this);
    $btn.prop("disabled", true);

    // Verificar uso de unidad
    f_callBackend("verificar_uso_unidad_en_distribuciones", {
      id_unidad: idUnidad,
      fecha_estimada: fecha,
    })
      .done(function (rVer) {
        $btn.prop("disabled", false);

        // Check simplified response
        if (rVer.estado === 1 && rVer.data.en_uso) {
          let correlativos = rVer.data.despachos_correlativos
            ? rVer.data.despachos_correlativos.join(", ")
            : "";
          let msg =
            "Para el día " +
            formatDateToDMY(fecha) +
            ", la unidad seleccionada estará en uso en los siguientes despachos: " +
            correlativos +
            ".\n¿Desea continuar?";

          if (!confirm(msg)) {
            return;
          }
        }

        let serie2 = "";
        let numero2 = "";
        let idTolva = $("#dist_tolva").val();
        if (idTolva) {
          let plateStr = $("#dist_tolva option:selected").text();
          if (plateStr && plateStr.indexOf("-") !== -1) {
            let parts = plateStr.split("-");
            serie2 = parts[0].trim();
            numero2 = parts[1].trim();
          } else {
            serie2 = plateStr.trim();
          }
        }

        let payload = {
          id_despacho: selectedDespachoId,
          id_unidad: idUnidad,
          id_empresa_transporte: id_empresa_transporte,
          id_tolva: idTolva || null,
          id_empresa_transporte_tolva: $("#dist_empresa_tolva").val() || null,
          serie_segunda_placa: serie2,
          numero_segunda_placa: numero2,
          fecha_estimada: fecha,
          detalle: [],
        };

        let totalPesoDistribucion = 0;
        let errorValidation = null;

        $(".input-dist-peso:enabled").each(function () {
          let val = parseFloat($(this).val());
          let idx = $(this).data("idx");
          let tipo = $(`.select-dist-tipo[data-idx='${idx}']`).val();
          let cantBB =
            parseInt($(`.input-cant-bigbags[data-idx='${idx}']`).val()) || 0;

          if (val > 0) {
            // Validacion Big Bags
            if (tipo == "2" && cantBB <= 0) {
              errorValidation =
                "Debe ingresar la cantidad de Bigbags para el item " +
                (idx + 1);
              return false;
            }

            totalPesoDistribucion += val;
            payload.detalle.push({
              id_despacho_detalle:
                itemsDespachoDistribucion[idx].id_despacho_detalle,
              peso_tomado: val,
              tipo_carga: tipo,
              cantidad_bigbags: cantBB,
            });
          }
        });

        if (errorValidation) {
          alert(errorValidation);
          $btn.prop("disabled", false);
          return;
        }

        if (payload.detalle.length === 0 && !confirm("¿Guardar sin items?"))
          return;

        // Validar Capacidad
        let selectedData = $("#dist_unidad").select2("data");
        let capacidadUnidad =
          selectedData && selectedData[0] ? selectedData[0].capacidad || 0 : 0;

        if (totalPesoDistribucion > capacidadUnidad) {
          if (
            !confirm(
              "La capacidad del vehículo (" +
              formatNumber(capacidadUnidad) +
              ") es inferior al peso total (" +
              formatNumber(totalPesoDistribucion) +
              ").\n¿Desea continuar?",
            )
          ) {
            return;
          }
        } else {
          if (!confirm("¿Confirmar Distribución?")) return;
        }

        let action = "crear_distribucion";

        $btn.prop("disabled", true);

        f_callBackend(action, payload)
          .done(function (r) {
            if (r.estado === 1) {
              alert("Guardado correctamente.");
              modalNuevaDistribucion.hide();
              loadDistribuciones(selectedDespachoId);
              selectDespacho(selectedDespachoId);
            } else {
              alert("Error: " + r.mensaje);
            }
          })
          .always(() => $btn.prop("disabled", false));
      })
      .fail(function () {
        $btn.prop("disabled", false);
        alert("Error al verificar disponibilidad de unidad.");
      });
  });

  // INIT
  function init() {
    f_GetMenuPrincipal();
    $("#nv_titulo").html("| Despachos");
    loadAllDespachos();
  }

  init();

  // --- Lógica Carreta Rápida ---
  $("#btn_add_carreta_rapida").click(function () {
    let idTr = $("#dist_transportista").val();
    let textTr = $("#dist_transportista option:selected").text();
    if (!idTr) {
      alert("Primero seleccione un transportista.");
      return;
    }
    $("#reg_carreta_id_transportista").val(idTr);
    $("#reg_carreta_transportista_nombre").val(textTr);
    $("#reg_carreta_serie").val("");
    $("#reg_carreta_numero").val("");

    new bootstrap.Modal(
      document.getElementById("modal_registrar_carreta_rapida"),
    ).show();
  });

  $("#btn_confirmar_registro_carreta").click(function () {
    let idTr = $("#reg_carreta_id_transportista").val();
    let serie = $("#reg_carreta_serie").val().trim();
    let numero = $("#reg_carreta_numero").val().trim();

    if (!serie || !numero) {
      alert("Ingrese serie y número de la placa.");
      return;
    }

    let placa = (serie + "-" + numero).toUpperCase();

    let $btn = $(this);
    $btn.prop("disabled", true).text("Registrando...");

    f_callBackend("registrar_carreta_rapida", {
      id_transportista: idTr,
      placa: placa,
    })
      .done(function (r) {
        if (r.estado === 1) {
          alert("Carreta registrada.");
          bootstrap.Modal.getInstance(
            document.getElementById("modal_registrar_carreta_rapida"),
          ).hide();

          // Al recargar unidades, queremos que ésta quede seleccionada
          f_callBackend("get_unidades_transporte_to_distribucion", {
            id_transportista: idTr,
            id_tipo_vehiculo: 7,
          }).done(function (r2) {
            if (r2.estado === 1) {
              let units = r2.data.unidades || [];
              let opts = units.map((u) => ({ id: u.id_unidad, text: u.placa }));
              $("#dist_tolva")
                .empty()
                .prop("disabled", false)
                .select2({
                  dropdownParent: $("#modal_nueva_distribucion"),
                  theme: "bootstrap-5",
                  data: opts,
                  width: "100%",
                })
                .val(r.data.id_transporte)
                .trigger("change");
            }
          });
        } else {
          alert(r.mensaje);
        }
      })
      .always(() => {
        $btn.prop("disabled", false).text("Registrar Carreta");
      });
  });

  // --------------------------------------------------------------------------------
  // MODAL BLENDING ISSUES
  // --------------------------------------------------------------------------------
  window.showBlendingIssues = function (index, e) {
    if (e) e.stopPropagation();
    let m = mineralesDisponibles[index];
    if (!m || !m.detalles_blending) return;

    let modal = new window.bootstrap.Modal(
      document.getElementById("modal_blending_issues"),
    );

    let html = "";
    // Group by Provider
    let groups = {};

    m.detalles_blending.forEach((d) => {
      if (!groups[d.id_proveedor]) {
        groups[d.id_proveedor] = {
          items: [],
          id: d.id_proveedor,
          razon_social: d.razon_social || "Desconocido",
          documento: d.documento || "---",
        };
      }
      groups[d.id_proveedor].items.push(d);
    });

    // Loop groups
    for (let pid in groups) {
      let g = groups[pid];
      // Check if this provider is associated (check first item)
      let isAssociated = g.items[0].asociado_planta == 1;
      let colorClass = isAssociated ? "text-success" : "text-danger";
      let icon = isAssociated
        ? '<i class="bi bi-check-circle-fill"></i>'
        : '<i class="bi bi-x-circle-fill"></i>';
      let title = isAssociated
        ? "Proveedor asociado a Planta"
        : "Proveedor NO asociado a Planta";

      html += `
                <div class="card mb-2 border-${isAssociated ? "success" : "danger"}">
                    <div class="card-header ${isAssociated ? "bg-success text-white" : "bg-danger text-white"} py-1">
                        <strong>${g.razon_social}</strong> (${g.documento}) ${isAssociated ? '<span class="float-end badge bg-light text-dark">Apto</span>' : '<span class="float-end badge bg-light text-danger">No Apto</span>'}
                    </div>
                    <ul class="list-group list-group-flush">
            `;

      g.items.forEach((item) => {
        html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                            Lote: ${item.codigo_lote}
                            <span>${icon}</span>
                         </li>`;
      });

      html += `</ul></div>`;
    }

    $("#body_blending_issues").html(html);
    modal.show();
  };
  window.showBlendingDetailsSugerido = function (id_blending, codigo, e) {
    if (e) e.stopPropagation();
    let modalBl = new window.bootstrap.Modal(
      document.getElementById("modal_detalle_blending_sugerido"),
    );
    $("#lbl_blending_codigo_sugerido").text(codigo);
    $("#tbl_blending_detalle_sugerido").html(
      '<tr><td colspan="6" class="text-center">Cargando detalles...</td></tr>',
    );
    modalBl.show();

    f_callBackend("get_blending_detalle_by_blending", {
      id_blending: id_blending,
    }).done(function (r) {
      if (r.estado === 1) {
        let html = "";
        let detalle = r.data.detalle_blending || [];
        if (detalle.length === 0) {
          html =
            '<tr><td colspan="6" class="text-center">No hay detalles.</td></tr>';
        } else {
          detalle.forEach((d) => {
            let pesoHumedo = parseFloat(d.peso_humedo) || 0;
            let h2o = parseFloat(d.porcentaje_humedad) || 0;
            let pesoSeco = parseFloat(d.peso_seco) || 0;
            let au = parseFloat(d.ley_oro) || 0;
            let ag = parseFloat(d.ley_plata) || 0;
            html += `
                  <tr>
                    <td>${d.codigo_gel}</td>
                    <td class="text-end fw-bold text-primary">${formatNumber(pesoHumedo)}</td>
                    <td class="text-end text-muted">${formatNumber(h2o, 3)}</td>
                    <td class="text-end fw-bold text-success">${formatNumber(pesoSeco)}</td>
                    <td class="text-end">${formatNumber(au)}</td>
                    <td class="text-end">${formatNumber(ag)}</td>
                  </tr>
                `;
          });
        }
        $("#tbl_blending_detalle_sugerido").html(html);
      } else {
        $("#tbl_blending_detalle_sugerido").html(
          '<tr><td colspan="6" class="text-center text-danger">Error al cargar.</td></tr>',
        );
      }
    });
  };

  window.cerrarDistribucion = function (id_distribucion, e) {
    if (e) e.stopPropagation();
    if (
      confirm(
        "¿Está seguro que desea cerrar esta distribución? Una vez cerrada, se considerará validada.",
      )
    ) {
      f_callBackend("cerrar_distribucion", {
        id_distribucion: id_distribucion,
      }).done(function (r) {
        if (r.estado === 1) {
          alert(r.mensaje);
          if (typeof selectedDespachoId !== "undefined" && selectedDespachoId) {
            loadDistribuciones(selectedDespachoId);
          }
          if (typeof loadAllDespachos === "function") {
            loadAllDespachos();
          }
        } else {
          alert(r.mensaje);
        }
      });
    }
  };

  window.abrirDistribucion = function (id_distribucion, e) {
    if (e) e.stopPropagation();
    if (
      confirm(
        "¿Está seguro que desea reabrir esta distribución? Volverá a estar pendiente y saldrá de la lista de vigilancia.",
      )
    ) {
      f_callBackend("abrir_distribucion", {
        id_distribucion: id_distribucion,
      }).done(function (r) {
        if (r.estado === 1) {
          alert(r.mensaje);
          if (typeof selectedDespachoId !== "undefined" && selectedDespachoId) {
            loadDistribuciones(selectedDespachoId);
          }
          if (typeof loadAllDespachos === "function") {
            loadAllDespachos();
          }
        } else {
          alert(r.mensaje);
        }
      });
    }
  };

  window.abrirModalLlegadaDestino = function (id_distribucion, e) {
    if (e) e.stopPropagation();
    $("#hdn_id_distribucion_llegada").val(id_distribucion);

    // Set to current local datetime by default for convenience
    let now = new Date();
    let pad = (n) => (n < 10 ? "0" + n : n);
    let defaultDateTime =
      now.getFullYear() +
      "-" +
      pad(now.getMonth() + 1) +
      "-" +
      pad(now.getDate()) +
      "T" +
      pad(now.getHours()) +
      ":" +
      pad(now.getMinutes());

    $("#dt_llegada_destino").val(defaultDateTime);

    let modal = new window.bootstrap.Modal(
      document.getElementById("modal_llegada_destino"),
    );
    modal.show();
  };

  $("#btn_confirmar_llegada_destino").click(function () {
    let id_distribucion = $("#hdn_id_distribucion_llegada").val();
    let fecha_hora = $("#dt_llegada_destino").val();

    if (!fecha_hora) {
      alert("Por favor ingrese la fecha y hora de llegada.");
      return;
    }

    $(this)
      .prop("disabled", true)
      .html(
        '<span class="spinner-border spinner-border-sm"></span> Guardando...',
      );

    f_callBackend("marcar_llegada_planta_destino", {
      id_distribucion: id_distribucion,
      fecha_hora_llegada: fecha_hora,
    })
      .done(function (r) {
        $("#btn_confirmar_llegada_destino")
          .prop("disabled", false)
          .html('<i class="bi bi-check-lg"></i> Confirmar Llegada');
        if (r.estado === 1) {
          alert(r.mensaje);
          bootstrap.Modal.getInstance(
            document.getElementById("modal_llegada_destino"),
          ).hide();
          if (typeof selectedDespachoId !== "undefined" && selectedDespachoId) {
            loadDistribuciones(selectedDespachoId);
          }
        } else {
          alert(r.mensaje);
        }
      })
      .fail(function () {
        $("#btn_confirmar_llegada_destino")
          .prop("disabled", false)
          .html('<i class="bi bi-check-lg"></i> Confirmar Llegada');
        alert("Error de conexión");
      });
  });

  $("#btn_finalizar_distribucion").click(function () {
    let btn = $(this);
    let idDist = btn.data("id_dist");

    if (!confirm("¿Está seguro que desea finalizar esta distribución? Una vez finalizada no podrá editar los datos.")) return;

    btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Finalizando...');

    f_callBackend("finalizar_distribucion", {
      id_distribucion: idDist,
    })
      .done(function (r) {
        btn.prop("disabled", false).html('<i class="bi bi-check-circle-fill"></i> Finalizar Distribución');
        if (r.estado === 1) {
          alert(r.mensaje);
          bootstrap.Modal.getInstance(document.getElementById("modal_detalle_distribucion")).hide();
          if (typeof selectedDespachoId !== "undefined" && selectedDespachoId) {
            loadDistribuciones(selectedDespachoId);
          }
        } else {
          alert(r.mensaje || "Error al finalizar");
        }
      })
      .fail(function () {
        btn.prop("disabled", false).html('<i class="bi bi-check-circle-fill"></i> Finalizar Distribución');
        alert("Error de conexión");
      });
  });

  $("#btn_guardar_detalles_destino").click(function () {
    let btn = $(this);
    let idDist = btn.data("id_dist");
    let detalles = [];

    $("#tbl_view_dist_items tr[data-id-detalle]").each(function () {
      let tr = $(this);
      let idDetalle = tr.data("id-detalle");
      let cod = tr.find(".i-codigo").val() || "";
      let peso = parseFloat(tr.find(".i-peso").val()) || 0;
      let au = parseFloat(tr.find(".i-au").val()) || 0;
      let ag = parseFloat(tr.find(".i-ag").val()) || 0;
      let h2o = parseFloat(tr.find(".i-h2o").val()) || 0;

      detalles.push({
        id: idDetalle,
        codigo: cod,
        peso: peso,
        ley_oro: au,
        ley_plata: ag,
        ley_humedad: h2o,
      });
    });

    if (detalles.length === 0) return;

    btn
      .prop("disabled", true)
      .html(
        '<span class="spinner-border spinner-border-sm"></span> Guardando...',
      );

    f_callBackend("guardar_detalles_destino", {
      detalles: JSON.stringify(detalles),
    })
      .done(function (r) {
        btn
          .prop("disabled", false)
          .html('<i class="bi bi-save"></i> Guardar Trazabilidad Destino');
        if (r.estado === 1) {
          alert(r.mensaje);
        } else {
          alert(r.mensaje || "Error al guardar");
        }
      })
      .fail(function () {
        btn
          .prop("disabled", false)
          .html('<i class="bi bi-save"></i> Guardar Trazabilidad Destino');
        alert("Error de conexión");
      });
  });
});
