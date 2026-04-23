/**
 * resumen_lotes.js
 * Vista: Resumen de Lotes (compra → despacho → distribución → venta)
 */

'use strict';


// ══════════════════════════════════════════════════════════════
//  INICIALIZACIÓN
// ══════════════════════════════════════════════════════════════
function f_Init() {
  f_GetMenuPrincipal();
  $("#nv_titulo").html('| Resumen de Lotes');
  f_LoadFiltros();

  // Búsqueda automática al cambiar filtros
  $('#cmb_proveedor, #cmb_planta').on('change', function() {
    f_LoadResumen();
  });

  // Carga inicial
  f_LoadResumen();
}

// ══════════════════════════════════════════════════════════════
//  CARGAR FILTROS (proveedores + plantas en paralelo)
// ══════════════════════════════════════════════════════════════
function f_LoadFiltros() {
  // Proveedores
  $.post(url_api, { accion: 'get_ProveedoresMineros' }, function(data) {
    let html = '<option value="">[Todos los proveedores]</option>';
    if (data.estado === 1) {
      $.each(data.registros, function(i, p) {
        html += `<option value="${p.Id}">${p.documento} — ${p.razon_social}</option>`;
      });
    }
    $('#cmb_proveedor').html(html);
  }, 'json');

  // Plantas
  $.post(url_api, { accion: 'get_plantas' }, function(data) {
    let html = '<option value="">[Todas las plantas]</option>';
    if (data.estado === 1) {
      $.each(data.data.plantas, function(i, p) {
        html += `<option value="${p.id_planta}">${p.descripcion}</option>`;
      });
    }
    $('#cmb_planta').html(html);
  }, 'json');
}

// ══════════════════════════════════════════════════════════════
//  CARGAR RESUMEN
// ══════════════════════════════════════════════════════════════
function f_LoadResumen() {
  const idProveedor = $('#cmb_proveedor').val();
  const idPlanta    = $('#cmb_planta').val();

  $('#lotes-grid').html(
    '<div class="empty-state"><i class="bi bi-hourglass-split"></i>Consultando datos...</div>'
  );
  f_LoadingResumen(1);

  $.post(url_api, {
    accion:       'get_ResumenLotes',
    id_proveedor: idProveedor,
    id_planta:    idPlanta
  }, function(data) {
    f_LoadingResumen(0);

    if (data.estado !== 1 || !data.registros || !data.registros.length) {
      $('#stat_total').text('0 registros');
      $('#lotes-grid').html(
        '<div class="empty-state"><i class="bi bi-inbox"></i>Sin resultados para los filtros seleccionados.</div>'
      );
      return;
    }

    $('#stat_total').text(data.registros.length + ' registro' + (data.registros.length !== 1 ? 's' : ''));
    f_RenderCards(data.registros);

  }, 'json');
}

// ══════════════════════════════════════════════════════════════
//  RENDERIZAR CARDS
// ══════════════════════════════════════════════════════════════
function f_RenderCards(registros) {
  let html = '';
  let n = 1;

  $.each(registros, function(i, r) {
    const venta     = parseFloat(r.precio_total)        || 0;
    const ptn       = parseFloat(r.precio_por_tonelada) || 0;

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
              <div class="lc-lote">${r.lote_codigo || '—'}</div>
              <div class="lc-interno">${r.lote_codigo_interno || ''}</div>
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
            <div class="lc-row">
              <span class="lc-label">Numero</span>
              <span class="lc-val">${r.numero_correlativo_compra ?? "—"}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Peso Neto</span>
              <span class="lc-val">${f_Num(r.lote_peso_neto, 3)} Kg</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Ley Au</span>
              <span class="lc-val">${f_Num(r.ley_oro_compra, 3)} oz/tc</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Ley Ag</span>
              <span class="lc-val">${f_Num(r.ley_plata_compra, 3)} oz/tc</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Total Au</span>
              <span class="lc-val money">$ ${f_Num(r.total_oro_compra, 2)}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Total Ag</span>
              <span class="lc-val money">$ ${f_Num(r.total_plata_compra, 2)}</span>
            </div>
          </div>

          <!-- Despacho -->
          <div class="lc-section lcs-despacho">
            <div class="lc-section-title"><i class="bi bi-truck"></i> Despacho</div>
            <div class="lc-row">
              <span class="lc-label">Planta Destino</span>
              <span class="lc-val">${r.planta_destino || '—'}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Código Despacho</span>
              <span class="lc-val">${r.despacho_codigo || '—'}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Peso Despachado</span>
              <span class="lc-val">${f_Num(r.peso_a_despachar, 3)} Kg</span>
            </div>
          </div>

          <!-- Distribución -->
          <div class="lc-section lcs-dist">
            <div class="lc-section-title"><i class="bi bi-box-seam"></i> Distribución / Transporte</div>
            <div class="lc-row">
              <span class="lc-label">Transportista</span>
              <span class="lc-val">${r.empresa_transporte_salida || '—'}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Placa</span>
              <span class="lc-val">${r.placa_unidad_salida || '—'}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Guía Remitente</span>
              <span class="lc-val">${r.guia_remitente_salida || '—'}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Guía Transportista</span>
              <span class="lc-val">${r.guia_transportista_salida || '—'}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Cód. en Planta</span>
              <span class="lc-val">${r.codigo_en_planta_destino || '—'}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Ley Au Planta</span>
              <span class="lc-val">${f_Num(r.ley_oro_en_planta_destino, 3)} oz/tc</span>
            </div>
             <div class="lc-row">
              <span class="lc-label">Ley Ag Planta</span>
              <span class="lc-val">${f_Num(r.ley_plata_en_planta_destino, 3)} oz/tc</span>
            </div>
          </div>

          <!-- Venta -->
          <div class="lc-section lcs-venta">
            <div class="lc-section-title"><i class="bi bi-graph-up-arrow"></i> Valorización Venta</div>
            <div class="lc-row">
              <span class="lc-label">Numero</span>
              <span class="lc-val">${r.numero_correlativo_venta ?? "—"}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Elemento</span>
              <span class="lc-val">${elemChip}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Precio / TN</span>
              <span class="lc-val money">$ ${f_Num(ptn, 2)}</span>
            </div>
            <div class="lc-row">
              <span class="lc-label">Total Venta</span>
              <span class="lc-val money">$ ${f_Num(venta, 2)}</span>
            </div>
          </div>

        </div>
      </div>`;
    n++;
  });

  $('#lotes-grid').html(html);
}



// ══════════════════════════════════════════════════════════════
//  HELPERS
// ══════════════════════════════════════════════════════════════
function f_LoadingResumen(_show) {
  _show ? $('#wt_resumen').css('display','flex') : $('#wt_resumen').hide();
}

/** Formatea número con decimales, retorna '—' si es nulo/cero/vacío */
function f_Num(val, dec) {
  const n = parseFloat(val);
  if (isNaN(n)) return '—';
  return f_RedondearDecimales(n, dec);
}