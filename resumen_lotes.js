/**
 * resumen_lotes.js
 * Vista: Resumen de Lotes (compra → despacho → distribución → venta)
 * Soporta dos tipos de card: lote y blending
 */

'use strict';

// ══════════════════════════════════════════════════════════════
//  ESTADO GLOBAL
// ══════════════════════════════════════════════════════════════
let g_data_resumen = { lotes: [], blends: [] };

// ══════════════════════════════════════════════════════════════
//  INICIALIZACIÓN
// ══════════════════════════════════════════════════════════════
function f_Init() {
  f_GetMenuPrincipal();
  $("#nv_titulo").html('| Resumen de Lotes');
  f_LoadFiltros();

  // Filtros frontend — se aplican en tiempo real
  $('#cmb_proveedor, #cmb_planta, #txt_buscar_codigo').on('input change', function () {
    f_AplicarFiltros();
  });

  // Carga inicial desde el backend (sin filtros de fecha)
  f_LoadResumen();
}

// ══════════════════════════════════════════════════════════════
//  CARGAR COMBOS (proveedores + plantas)
// ══════════════════════════════════════════════════════════════
function f_LoadFiltros() {
  $.post(url_api, { accion: 'get_ProveedoresMineros' }, function (data) {
    let html = '<option value="">[Todos los proveedores]</option>';
    if (data.estado === 1) {
      $.each(data.registros, function (i, p) {
        html += `<option value="${p.Id}">${p.documento} — ${p.razon_social}</option>`;
      });
    }
    $('#cmb_proveedor').html(html);
  }, 'json');

  $.post(url_api, { accion: 'get_plantas' }, function (data) {
    let html = '<option value="">[Todas las plantas]</option>';
    if (data.estado === 1) {
      $.each(data.data.plantas, function (i, p) {
        html += `<option value="${p.id_planta}">${p.descripcion}</option>`;
      });
    }
    $('#cmb_planta').html(html);
  }, 'json');
}

// ══════════════════════════════════════════════════════════════
//  CARGAR RESUMEN (llamada al backend, sin filtros)
// ══════════════════════════════════════════════════════════════
function f_LoadResumen() {
  $('#lotes-grid').html(
    '<div class="empty-state"><i class="bi bi-hourglass-split"></i>Consultando datos...</div>'
  );
  f_LoadingResumen(1);

  $.post(url_api, { accion: 'get_ResumenLotes' }, function (data) {
    f_LoadingResumen(0);

    if (data.estado !== 1) {
      f_RenderVacio();
      return;
    }

    g_data_resumen.lotes = data.lotes || [];
    g_data_resumen.blends = data.blends || [];

    f_AplicarFiltros();
  }, 'json');
}

// ══════════════════════════════════════════════════════════════
//  FILTROS FRONTEND
// ══════════════════════════════════════════════════════════════
function f_AplicarFiltros() {
  const idProveedor = $('#cmb_proveedor').val();
  const idPlanta = $('#cmb_planta').val();
  const busqueda = ($('#txt_buscar_codigo').val() || '').trim().toLowerCase();

  // ── Filtrar lotes ──
  const lotesFiltrados = g_data_resumen.lotes.filter(function (r) {
    if (idProveedor && String(r.id_proveedor || '') !== idProveedor) return false;
    if (idPlanta && String(r.id_planta || '') !== idPlanta) return false;

    if (busqueda) {
      const campos = [
        r.lote_codigo_interno, r.lote_codigo,
        r.codigo_valorizacion_venta, r.codigo_en_planta_destino,
        r.planta_destino, r.proveedor
      ].map(v => (v || '').toLowerCase());
      if (!campos.some(c => c.includes(busqueda))) return false;
    }
    return true;
  });

  // ── Filtrar blends ──
  const blendsFiltrados = g_data_resumen.blends.filter(function (r) {
    // Si hay filtro de proveedor, el blending debe tener al menos un lote de ese proveedor
    if (idProveedor) {
      const tieneProveedor = (r.lotes_tomados || []).some(l => String(l.id_proveedor || '') === idProveedor);
      if (!tieneProveedor) return false;
    }
    if (idPlanta && String(r.id_planta || '') !== idPlanta) return false;

    if (busqueda) {
      const campos = [
        r.codigo_blending, r.codigo_valorizacion_venta, r.codigo_en_planta_destino,
        r.planta_destino
      ].map(v => (v || '').toLowerCase());
      const enLotes = (r.lotes_tomados || []).some(l =>
        (l.codigo_lote || '').toLowerCase().includes(busqueda) ||
        (l.proveedor || '').toLowerCase().includes(busqueda)
      );
      if (!campos.some(c => c.includes(busqueda)) && !enLotes) return false;
    }
    return true;
  });

  const total = lotesFiltrados.length + blendsFiltrados.length;
  $('#stat_total').text(total + ' registro' + (total !== 1 ? 's' : ''));

  if (total === 0) {
    f_RenderVacio();
    return;
  }

  f_RenderCards(lotesFiltrados, blendsFiltrados);
}

// ══════════════════════════════════════════════════════════════
//  RENDERIZAR CARDS
// ══════════════════════════════════════════════════════════════
function f_RenderCards(lotes, blends) {
  let html = '';
  let n = 1;

  // ── Cards de Lote ──
  $.each(lotes, function (i, r) {
    const venta = parseFloat(r.precio_total) || 0;
    const ptn = parseFloat(r.precio_por_tonelada) || 0;

    const elemChip = (r.elemento_quimico == 2)
      ? '<span class="chip chip-plata">Ag</span>'
      : (r.elemento_quimico == 1 ? '<span class="chip chip-oro">Au</span>' : '—');

    html += `
      <div class="lote-card">

        <!-- Cabecera -->
        <div class="lc-head">
          <div class="lc-head-left">
            <div class="lc-num">${n}</div>
            <div>
            <div class="lc-interno">
              ${r.lote_codigo_interno || ''}
              ${(r.numero_parte) ?
        `<span class="ms-1" style="font-size:14px;font-weight:400;color:rgba(255,255,255,.65);">
                  (${r.codigo_en_planta_destino || '--'}  Part. #${r.numero_parte})
                </span>` : ''}
            </div>
              <div class="lc-tipo-row">
                <span class="lote-badge"><i class="bi bi-box-fill"></i> Lote</span>
                <span class="lc-lote ms-2">${r.lote_codigo || '—'}</span>
              </div>
            </div>
          </div>
          <div class="lc-head-right">
            <div class="lc-proveedor">${r.proveedor || '—'}</div>
            <div class="lc-doc">${r.proveedor_documento || ''}</div>
          </div>
        </div>

        <div class="lc-body">

          <!-- Compra -->
          <div class="lc-section lcs-compra">
            <div class="lc-section-title"><i class="bi bi-cash-coin"></i> Valorización Compra</div>
            <div class="lc-row"><span class="lc-label">Numero</span><span class="lc-val">${r.numero_correlativo_compra ?? '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Peso Neto</span><span class="lc-val">${f_Num(r.lote_peso_neto, 2)} TN</span></div>
            <div class="lc-row"><span class="lc-label">Ley Au</span><span class="lc-val">${f_Num(r.ley_oro_compra, 3)} oz/tc</span></div>
            <div class="lc-row"><span class="lc-label">Ley Ag</span><span class="lc-val">${f_Num(r.ley_plata_compra, 3)} oz/tc</span></div>
            <div class="lc-row"><span class="lc-label">Total Au</span><span class="lc-val money">$ ${f_Num(r.total_oro_compra, 2)}</span></div>
            <div class="lc-row"><span class="lc-label">Total Ag</span><span class="lc-val money">$ ${f_Num(r.total_plata_compra, 2)}</span></div>
          </div>

          <!-- Despacho -->
          <div class="lc-section lcs-despacho">
            <div class="lc-section-title"><i class="bi bi-truck"></i> Despacho</div>
            <div class="lc-row"><span class="lc-label">Planta Destino</span><span class="lc-val">${r.planta_destino || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Código Despacho</span><span class="lc-val">${r.despacho_codigo || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Peso Despachado</span><span class="lc-val">${f_Num(r.peso_a_despachar, 2)} TN</span></div>
          </div>

          <!-- Distribución -->
          <div class="lc-section lcs-dist">
            <div class="lc-section-title"><i class="bi bi-box-seam"></i> Distribución / Transporte</div>
            <div class="lc-row"><span class="lc-label">Transportista</span><span class="lc-val">${r.empresa_transporte_salida || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Placa</span><span class="lc-val">${r.placa_unidad_salida || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Guía Remitente</span><span class="lc-val">${r.guia_remitente_salida || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Guía Transportista</span><span class="lc-val">${r.guia_transportista_salida || '—'}</span></div>
          </div>

          <!-- Venta -->
          <div class="lc-section lcs-venta">
            <div class="lc-section-title"><i class="bi bi-graph-up-arrow"></i> Valorización Venta</div>
            <div class="lc-row"><span class="lc-label">N° Valor.</span><span class="lc-val">${r.numero_correlativo_venta ?? '—'}</span></div>
            <div class="lc-row">
              <span class="lc-label">Cód. Valorización</span>
              <span class="lc-val text-primary fw-bold">${r.codigo_valorizacion_venta ?? '—'}</span>
            </div>
            <div class="lc-row"><span class="lc-label">Elemento</span><span class="lc-val">${elemChip}</span></div>
            <div class="planta-edit-box">
              ${(r.id_distribucion_detalle && !r.codigo_valorizacion_venta) ? `
                <button class="btn btn-sm btn-outline-primary btn-edit-planta"
                        onclick="f_EditarDatosPlanta(${r.id_distribucion_detalle})"
                        title="Editar datos planta">
                  <i class="bi bi-pencil-fill"></i>
                </button>
              ` : ''}
              <div class="lc-row"><span class="lc-label">Cód. en Planta</span><span class="lc-val">${r.codigo_en_planta_destino || '—'}</span></div>
              <div class="lc-row"><span class="lc-label">Ley Au Planta</span><span class="lc-val">${f_Num(r.ley_oro_en_planta_destino, 3)} oz/tc</span></div>
              <div class="lc-row"><span class="lc-label">Ley Ag Planta</span><span class="lc-val">${f_Num(r.ley_plata_en_planta_destino, 3)} oz/tc</span></div>
              <div class="lc-row"><span class="lc-label">Ley H2O Planta</span><span class="lc-val">${f_Num(r.ley_humedad_en_planta_destino, 2)}</span></div>
            </div>
            <div class="lc-row"><span class="lc-label">Precio / TN</span><span class="lc-val money">$ ${f_Num(ptn, 2)}</span></div>
            <div class="lc-row"><span class="lc-label">Total Venta</span><span class="lc-val money">$ ${f_Num(venta, 2)}</span></div>
          </div>

        </div>
      </div>`;
    n++;
  });

  // ── Cards de Blending ──
  $.each(blends, function (i, r) {
    const venta = parseFloat(r.precio_total) || 0;
    const ptn = parseFloat(r.precio_por_tonelada) || 0;

    const elemChip = (r.elemento_quimico == 2)
      ? '<span class="chip chip-plata">Ag</span>'
      : (r.elemento_quimico == 1 ? '<span class="chip chip-oro">Au</span>' : '—');

    // Chips de lotes tomados agrupados por proveedor
    let lotesHtml = '';
    if (r.lotes_tomados && r.lotes_tomados.length > 0) {
      // Agrupar por proveedor
      const grupos = {};
      r.lotes_tomados.forEach(function (l) {
        const prov = l.proveedor || 'Sin proveedor';
        if (!grupos[prov]) grupos[prov] = [];
        grupos[prov].push(l);
      });

      lotesHtml = Object.entries(grupos).map(function ([prov, lotes]) {
        const chips = lotes.map(l =>
          `<span class="blend-lote-chip" title="Peso: ${f_Num(l.peso_tomado, 2)} kg">
            <i class="bi bi-box"></i> ${l.codigo_lote || '—'}
            <span class="blend-lote-peso">${f_Num(l.peso_tomado, 3)} TN</span>
           </span>`
        ).join('');
        return `<div class="blend-lote-grupo">
          <span class="blend-lote-prov"><i class="bi bi-person-fill"></i> ${prov}</span>
          <div class="blend-lote-chips">${chips}</div>
        </div>`;
      }).join('');
    } else {
      lotesHtml = '<span style="color:rgba(255,255,255,.4);font-size:12px;">Sin lotes registrados</span>';
    }

    html += `
      <div class="lote-card blend-card">

        <!-- Cabecera blending -->
        <div class="lc-head lc-head-blend">
          <div class="lc-head-left">
            <div class="lc-num">${n}</div>
            <div>
              <div class="lc-interno">
                <span class="blend-badge me-2"><i class="bi bi-layers-fill"></i> Blending</span>
                ${r.codigo_blending || '—'}
                ${r.numero_parte ? `<span class="ms-1" style="font-size:14px;font-weight:400;color:rgba(255,255,255,.9);"> -- Part. #${r.numero_parte}</span>` : ''}
              </div>
              
            </div>
          </div>
          <div class="lc-head-right">
            <div class="lc-proveedor">${lotesHtml}</div>
          </div>
        </div>

        <div class="lc-body">

          <!-- Leyes blending -->
          <div class="lc-section lcs-compra">
            <div class="lc-section-title"><i class="bi bi-bar-chart-fill"></i> Leyes Blending</div>
            <div class="lc-row"><span class="lc-label">Peso Neto</span><span class="lc-val">${f_Num(r.peso_neto / 1000, 2)} TN</span></div>
            <div class="lc-row"><span class="lc-label">Ley Au</span><span class="lc-val">${f_Num(r.ley_oro_blending, 3)} oz/tc</span></div>
            <div class="lc-row"><span class="lc-label">Ley Ag</span><span class="lc-val">${f_Num(r.ley_plata_blending, 3)} oz/tc</span></div>
            <div class="lc-row"><span class="lc-label">H2O Prom.</span><span class="lc-val">${f_Num(r.ley_humedad_blending, 2)} %</span></div>
          </div>

          <!-- Despacho -->
          <div class="lc-section lcs-despacho">
            <div class="lc-section-title"><i class="bi bi-truck"></i> Despacho</div>
            <div class="lc-row"><span class="lc-label">Planta Destino</span><span class="lc-val">${r.planta_destino || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Código Despacho</span><span class="lc-val">${r.despacho_codigo || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Peso Despachado</span><span class="lc-val">${f_Num(r.peso_a_despachar, 2)} TN</span></div>
          </div>

          <!-- Distribución -->
          <div class="lc-section lcs-dist">
            <div class="lc-section-title"><i class="bi bi-box-seam"></i> Distribución / Transporte</div>
            <div class="lc-row"><span class="lc-label">Transportista</span><span class="lc-val">${r.empresa_transporte_salida || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Placa</span><span class="lc-val">${r.placa_unidad_salida || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Guía Remitente</span><span class="lc-val">${r.guia_remitente_salida || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Guía Transportista</span><span class="lc-val">${r.guia_transportista_salida || '—'}</span></div>
          </div>

          <!-- Venta -->
          <div class="lc-section lcs-venta">
            <div class="lc-section-title"><i class="bi bi-graph-up-arrow"></i> Valorización Venta</div>
            <div class="lc-row"><span class="lc-label">N° Valor.</span><span class="lc-val">${r.numero_correlativo_venta ?? '—'}</span></div>
            <div class="lc-row">
              <span class="lc-label">Cód. Valorización</span>
              <span class="lc-val text-primary fw-bold">${r.codigo_valorizacion_venta ?? '—'}</span>
            </div>
            <div class="lc-row"><span class="lc-label">Elemento</span><span class="lc-val">${elemChip}</span></div>
            <div class="planta-edit-box">
            ${(r.id_distribucion_detalle && !r.codigo_valorizacion_venta) ? `
                <button class="btn btn-sm btn-outline-primary btn-edit-planta"
                        onclick="f_EditarDatosPlanta(${r.id_distribucion_detalle})"
                        title="Editar datos planta">
                  <i class="bi bi-pencil-fill"></i>
                </button>
              ` : ''}
              <div class="lc-row"><span class="lc-label">Cód. en Planta</span><span class="lc-val">${r.codigo_en_planta_destino || '—'}</span></div>
              <div class="lc-row"><span class="lc-label">Ley Au Planta</span><span class="lc-val">${f_Num(r.ley_oro_en_planta_destino, 3)} oz/tc</span></div>
              <div class="lc-row"><span class="lc-label">Ley Ag Planta</span><span class="lc-val">${f_Num(r.ley_plata_en_planta_destino, 3)} oz/tc</span></div>
              <div class="lc-row"><span class="lc-label">Ley H2O Planta</span><span class="lc-val">${f_Num(r.ley_humedad_en_planta_destino, 2)}</span></div>
            </div>
            <div class="lc-row"><span class="lc-label">Precio / TN</span><span class="lc-val money">$ ${f_Num(ptn, 2)}</span></div>
            <div class="lc-row"><span class="lc-label">Total Venta</span><span class="lc-val money">$ ${f_Num(venta, 2)}</span></div>
          </div>

        </div>
      </div>`;
    n++;
  });

  $('#lotes-grid').html(html);
}

function f_RenderVacio() {
  $('#stat_total').text('0 registros');
  $('#lotes-grid').html(
    '<div class="empty-state"><i class="bi bi-inbox"></i>Sin resultados para los filtros seleccionados.</div>'
  );
}

// ══════════════════════════════════════════════════════════════
//  HELPERS
// ══════════════════════════════════════════════════════════════
function f_LoadingResumen(_show) {
  _show ? $('#wt_resumen').css('display', 'flex') : $('#wt_resumen').hide();
}

/** Formatea número con decimales, retorna '—' si es nulo/cero/vacío */
function f_Num(val, dec) {
  const n = parseFloat(val);
  if (isNaN(n)) return '—';
  return f_RedondearDecimales(n, dec);
}

/** Abre modal para editar datos de planta */
function f_EditarDatosPlanta(id) {
  // Buscar en lotes
  let r = g_data_resumen.lotes.find(x => x.id_distribucion_detalle == id);
  // Si no está en lotes, buscar en blends
  if (!r) {
    r = g_data_resumen.blends.find(x => x.id_distribucion_detalle == id);
  }

  if (!r) return;

  $('#txt_edit_id_dist_det').val(id);
  $('#txt_edit_codigo_planta').val(r.codigo_en_planta_destino || '');
  $('#txt_edit_ley_au').val(r.ley_oro_en_planta_destino || '');
  $('#txt_edit_ley_ag').val(r.ley_plata_en_planta_destino || '');
  $('#txt_edit_ley_h2o').val(r.ley_humedad_en_planta_destino || '');

  $('#modalEditPlanta').modal('show');
}

/** Graba los cambios realizados en el modal de planta */
function f_GrabarDatosPlanta() {
  const id = $('#txt_edit_id_dist_det').val();
  const codigo = $('#txt_edit_codigo_planta').val();
  const au = $('#txt_edit_ley_au').val();
  const ag = $('#txt_edit_ley_ag').val();
  const h2o = $('#txt_edit_ley_h2o').val();

  f_LoadingResumen(1);
  $.post(url_api, {
    accion: 'update_DatosPlantaResumen',
    id_dist_det: id,
    codigo_planta: codigo,
    ley_au: au,
    ley_ag: ag,
    ley_h2o: h2o
  }, function (data) {
    f_LoadingResumen(0);
    if (data.estado === 1) {
      $('#modalEditPlanta').modal('hide');
      f_LoadResumen();
      f_Alert("success", data.msg);
    } else {
      f_Alert("error", data.msg);
    }
  }, 'json');
}