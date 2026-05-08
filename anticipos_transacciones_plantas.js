/**
 * anticipos_transacciones_planta.js
 * Módulo: Transacciones de Anticipos Planta
 */

function initAnticiposPlanta(backendUrl) {

  // ─── Estado global ────────────────────────────────────────────────────────
  let currentData = [];

  // ─── Helpers ─────────────────────────────────────────────────────────────

  function f_callBackend(accion, data) {
    return $.post(backendUrl, { accion: accion, ...data }, 'json');
  }

  function formatearMoneda(valor) {
    if (valor === null || valor === undefined || isNaN(valor)) return '0.00';
    return parseFloat(valor)
      .toFixed(2)
      .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  function formatDate(dateString) {
    if (!dateString || dateString === '-') return '-';
    const parts = dateString.split('-');
    if (parts.length < 3) return dateString;
    const day = parts[2].split(' ')[0];
    return `${day}/${parts[1]}/${parts[0]}`;
  }

  /** Devuelve badge HTML usando el estado raw y el label ya resuelto por el backend */
  function estadoBadge(estado, label) {
    const clsMap = {
      'A': 'bg-success',
      'B': 'bg-success',
      'C': 'bg-success',
      'P': 'bg-warning text-dark',
      'E': 'bg-secondary',
      'X': 'bg-danger',
    };
    const cls = clsMap[estado] || 'bg-secondary';
    return `<span class="badge ${cls}">${label || estado}</span>`;
  }

  /** Reúne los filtros de cliente en un objeto */
  function getClientFilters() {
    return {
      anticipo:   $('#filter_factura_anticipo').val().toLowerCase().trim(),
      venta:      $('#filter_factura_venta').val().toLowerCase().trim(),
      estado:     $('#filter-estado').val(),
      fechaDesde: $('#filter-fecha-desde').val(),
      fechaHasta: $('#filter-fecha-hasta').val(),
    };
  }

  // ─── Carga de Plantas ──────────────────────────────────────────────────────

  function loadPlantas() {
    f_callBackend('get_plantas', {})
      .done(function (r) {
        if (r.estado === 1 && r.data && r.data.plantas) {
          const opts = r.data.plantas.map(p => ({ id: p.id_planta, text: p.descripcion }));
          $('#filter_planta').empty().select2({
            theme: 'bootstrap-5',
            placeholder: 'Seleccione una planta...',
            allowClear: true,
            data: opts,
          });
          $('#filter_planta').val(null).trigger('change');
        }
      })
      .fail(function () {
        alert('Error al cargar lista de plantas.');
      });
  }

  // ─── Renderizado ───────────────────────────────────────────────────────────

  function renderTable(data) {
    const tbody = $('#tbl-transacciones');
    tbody.empty();

    if (!data || data.length === 0) {
      tbody.html(`
        <tr>
          <td colspan="15" class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size:32px;"></i>
            <p class="mt-2 mb-0">No se encontraron registros.</p>
          </td>
        </tr>`);
      return;
    }

    let html = '';

    data.forEach(function (group) {
      const ant = group.anticipo_info;

      if (!group.transacciones || group.transacciones.length === 0) return;

      // ── Fila cabecera del anticipo ──
      html += `
        <tr class="table-secondary fw-bold" style="background-color:#e9ecef;">
          <td class="text-center text-primary">${ant.factura}</td>
          <td class="text-center">${formatDate(ant.fecha)}</td>
          <td class="text-end text-primary">$ ${formatearMoneda(ant.importe_inicial)}</td>
          <td colspan="12" style="background-color:#f8f9fa;"></td>
        </tr>`;

      // ── Filas de transacciones ──
      group.transacciones.forEach(function (tr) {
        html += `
          <tr>
            <td></td><td></td><td></td>

            <td class="text-center">${tr.porcentaje_aplicado}</td>
            <td class="text-end">$ ${formatearMoneda(tr.monto_retirado)}</td>
            <td class="text-center small font-monospace">${tr.lotes || '-'}</td>

            <td class="text-center fw-bold">${tr.factura_venta}</td>
            <td class="text-center">${formatDate(tr.fecha_emision)}</td>
            <td class="text-end">$ ${formatearMoneda(tr.total_dolares)}</td>
            <td class="text-end bg-warning fw-bold">$ ${formatearMoneda(tr.monto_retirado)}</td>

            <td class="text-end">$ ${formatearMoneda(tr.saldo_factura_amortiza)}</td>
            <td class="text-end">$ ${formatearMoneda(tr.saldo_neto_factura)}</td>
            <td class="text-end fw-bold text-primary">$ ${formatearMoneda(tr.saldo_restante)}</td>

            <td class="text-center">${estadoBadge(tr.estado, tr.estado_label)}</td>
            <td class="text-center small">${tr.valorizaciones || '-'}</td>
          </tr>`;
      });
    });

    if (html === '') {
      tbody.html(`
        <tr>
          <td colspan="15" class="text-center py-5 text-muted">
            <p class="mb-0">No hay coincidencias con los filtros.</p>
          </td>
        </tr>`);
    } else {
      tbody.html(html);
    }
  }

  // ─── Filtrado cliente-side ────────────────────────────────────────────────

  function filterAndRender() {
    const f = getClientFilters();

    if (currentData.length === 0) {
      renderTable([]);
      return;
    }

    const filtered = [];

    currentData.forEach(function (group) {
      const ant = group.anticipo_info;

      // Filtro por factura anticipo (sobre el padre)
      if (f.anticipo && !ant.factura.toLowerCase().includes(f.anticipo)) return;

      // Filtrar transacciones
      const matchingTrs = group.transacciones.filter(function (tr) {

        // Factura venta
        if (f.venta && !tr.factura_venta.toLowerCase().includes(f.venta)) return false;

        // Estado
        if (f.estado && tr.estado !== f.estado) return false;

        // Rango de fechas (sobre fecha_emision de la factura de venta)
        if (f.fechaDesde || f.fechaHasta) {
          const fecha = tr.fecha_emision; // YYYY-MM-DD
          if (!fecha || fecha === '-') return false;
          if (f.fechaDesde && fecha < f.fechaDesde) return false;
          if (f.fechaHasta && fecha > f.fechaHasta) return false;
        }

        return true;
      });

      if (matchingTrs.length > 0) {
        filtered.push({ anticipo_info: ant, transacciones: matchingTrs });
      }
    });

    renderTable(filtered);
  }

  // ─── Carga de Reporte ─────────────────────────────────────────────────────

  function loadReporte() {
    const idPlanta    = $('#filter_planta').val();
    const fechaDesde  = $('#filter-fecha-desde').val();
    const fechaHasta  = $('#filter-fecha-hasta').val();

    if (!idPlanta) {
      $('#tbl-transacciones').html(`
        <tr>
          <td colspan="15" class="text-center py-5 text-muted">
            <p class="mt-2">Seleccione una planta para ver el reporte.</p>
          </td>
        </tr>`);
      return;
    }

    $('#tbl-transacciones').html(`
      <tr>
        <td colspan="15" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
          <p class="mt-2 text-muted">Generando reporte...</p>
        </td>
      </tr>`);

    f_callBackend('getReporteAnticiposPlantaTransacciones', {
      id_planta:    idPlanta,
      fecha_desde:  fechaDesde,
      fecha_hasta:  fechaHasta,
    })
      .done(function (r) {
        if (r.estado === 1) {
          currentData = r.data || [];
          filterAndRender();
        } else {
          alert('Error al cargar reporte: ' + (r.msg || 'Desconocido'));
          currentData = [];
          renderTable([]);
        }
      })
      .fail(function () {
        alert('Error de conexión.');
        currentData = [];
        renderTable([]);
      });
  }

  // ─── Exportar Excel ───────────────────────────────────────────────────────

  $('#btn-exportar-excel').on('click', function () {
    const idPlanta = $('#filter_planta').val();
    if (!idPlanta) {
      alert('Seleccione una planta para exportar.');
      return;
    }

    // Se pasan TODOS los filtros activos al backend para que el Excel
    // refleje exactamente lo que se ve en pantalla.
    const f = getClientFilters();

    const form = $('<form>', {
      action: backendUrl,
      method: 'POST',
      target: '_blank',
    });

    const fields = {
      accion:              'exportExcelAnticiposPlantaTransacciones',
      id_planta:           idPlanta,
      fecha_desde:         $('#filter-fecha-desde').val(),
      fecha_hasta:         $('#filter-fecha-hasta').val(),
      filter_anticipo:     f.anticipo,
      filter_venta:        f.venta,
      filter_estado:       f.estado,
    };

    Object.entries(fields).forEach(([name, value]) => {
      $('<input>').attr({ type: 'hidden', name, value }).appendTo(form);
    });

    form.appendTo('body').submit().remove();
  });

  // ─── Eventos ──────────────────────────────────────────────────────────────

  $('#btn-aplicar-filtros').on('click', function () {
    loadReporte();
  });

  // Filtros texto/select aplican client-side inmediatamente
  $('#filter_factura_anticipo, #filter_factura_venta, #filter-estado').on('keyup change', function () {
    filterAndRender();
  });

  // Fechas también disparan refetch al backend (son parámetros del query)
  $('#filter-fecha-desde, #filter-fecha-hasta').on('change', function () {
    loadReporte();
  });

  $('#btn-limpiar-filtros').on('click', function () {
    $('#filter_planta').val(null).trigger('change');
    $('#filter-fecha-desde, #filter-fecha-hasta').val('');
    $('#filter_factura_anticipo, #filter_factura_venta').val('');
    $('#filter-estado').val('');
    currentData = [];
    loadReporte();
  });

  // ─── Init ─────────────────────────────────────────────────────────────────

  // Inicializar Select2 vacío primero, luego cargar plantas
  $('#filter_planta').select2({
    theme: 'bootstrap-5',
    placeholder: 'Seleccione una planta...',
    allowClear: true,
  });

  f_GetMenuPrincipal();
  $('#nv_titulo').html('| Transacciones Anticipos Planta - Reporte');

  loadPlantas();
  loadReporte(); // Renderiza estado vacío inicial
}