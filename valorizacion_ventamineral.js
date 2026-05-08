/**
 * valorizacion_ventamineral.js
 * Módulo: Valorización de Venta de Mineral
 *
 * Tablas principales:
 *   valorizacion_venta            → cabecera
 *   valorizacion_venta_detalle    → líneas de detalle
 *   distribucion_detalle          → lotes disponibles por planta
 *   condiciones_comerciales_planta → parámetros comerciales
 */

'use strict';

// ══════════════════════════════════════════════════════════════
//  ESTADO GLOBAL
// ══════════════════════════════════════════════════════════════
let idValorizacion_Selected    = 0;
let panelExpandido             = false;
let _evidenciasExistentes     = [];   // Para guardar las rutas de archivos ya subidos
let _lotesEnModal              = [];   // Filas acumuladas antes de grabar

// ══════════════════════════════════════════════════════════════
//  INICIALIZACIÓN
// ══════════════════════════════════════════════════════════════
function f_Init() {
  f_GetMenuPrincipal();
  $("#nv_titulo").html('| Valorización de Venta');
  f_LoadPlantas();
}

// ══════════════════════════════════════════════════════════════
//  CARGA DE PLANTAS (filtro y modal)
// ══════════════════════════════════════════════════════════════
function f_LoadPlantas() {
  $.post(url_api, { accion: 'get_plantas' }, function(data) {
    if (data.estado !== 1) return;

    let optFiltro = '<option value="">[Todas las plantas]</option>';
    let optModal  = '<option value="">[Seleccione una planta]</option>';

    $.each(data.data.plantas, function(i, p) {
      const opt = `<option value="${p.id_planta}"
                     data-ruc="${p.ruc}"
                     data-desc="${p.descripcion}">${p.descripcion}</option>`;
      optFiltro += opt;
      optModal  += opt;
    });

    $('#cmb_planta_filtro').html(optFiltro);
    $('#cmb_planta_modal').html(optModal);

    f_LoadValorizaciones();
  }, 'json').fail(function() {
    alert('Error al cargar plantas.');
  });
}

// ══════════════════════════════════════════════════════════════
//  LISTA DE VALORIZACIONES
// ══════════════════════════════════════════════════════════════
function f_LoadValorizaciones() {
  const idPlanta = $('#cmb_planta_filtro').val();

  $('#tbl_valorizaciones').html('');
  f_LoadingList(1);

  $.post(url_api,
    { accion: 'get_ListaValorizacionesVenta', id_planta: idPlanta },
    function(data) {
      f_LoadingList(0);

      if (data.estado !== 1 || !data.registros.length) {
        $('#tbl_valorizaciones').html(
          '<tr><td colspan="6" class="text-center text-muted py-3">' +
          '<i class="bi bi-inbox me-2"></i>Sin valorizaciones registradas.</td></tr>'
        );
        return;
      }

      let html = '';
      $.each(data.registros, function(i, row) {
        const n = i + 1;

        let badgeEstado = '';
        if (row.estado === 'A')      badgeEstado = `<span class="badge-estado badge-activo">Activo</span>`;
        else if (row.estado === 'I') badgeEstado = `<span class="badge-estado badge-inactivo">Inactivo</span>`;
        else                          badgeEstado = `<span class="badge-estado badge-anulado">Anulado</span>`;

        const esAnulado = (row.estado === 'X');

        html += `
          <tr class="vv-row" id="tr_vv_${n}"
              data-id="${row.id}"
              data-planta="${row.descripcion_planta}"
              data-correlativo="${row.numero_correlativo}"
              data-codigo-val="${row.codigo_valorizacion_venta || ''}"
              onclick="f_SelectValorizacion(this);">
            <td>${n}</td>
            <td>
              <span class="corr-chip">
                <i class="bi bi-hash"></i>${row.numero_correlativo}
              </span>
            </td>
            <td class="fw-bold text-primary">${row.codigo_valorizacion_venta || '—'}</td>
            <td style="text-align:left;">${row.descripcion_planta}</td>
            <td>
              <small>${row.usuario_registro}</small><br>
              <small class="text-muted">${row.created_at}</small>
            </td>
            <td>${badgeEstado}</td>
            <td>
              <div class="action-col">
                ${!esAnulado ? `
                  <a href="javascript:void(0)"
                     onclick="event.stopPropagation(); f_AdminValorizacion('E', ${n}, ${row.id});"
                     style="color:#0277bd;">
                    <i class="bi bi-pencil-square"></i> Editar
                  </a>
                  <a href="javascript:void(0)"
                     onclick="event.stopPropagation(); f_VerTrazabilidad(${row.id});"
                     style="color:#7b1fa2;">
                    <i class="bi bi-clock-history"></i> Cambios
                  </a>` : `
                  <span class="text-muted" style="font-size:11px;">Anulada</span>`}
                <a href="javascript:void(0)"
                   onclick="event.stopPropagation(); f_AbrirModalArchivosVV(${row.id}, '${row.numero_correlativo}');"
                   style="color:#2e7d32;">
                  <i class="bi bi-folder2-open"></i> Ver archivos
                </a>
              </div>
            </td> 
          </tr>`;
      });

      $('#tbl_valorizaciones').html(html);

      // Seleccionar primera fila automáticamente
      const firstRow = document.querySelector('#tbl_valorizaciones tr');
      if (firstRow) f_SelectValorizacion(firstRow);
    }, 'json').fail(function() {
      f_LoadingList(0);
      alert('Error al cargar valorizaciones.');
    });
}

// ══════════════════════════════════════════════════════════════
//  SELECCIONAR VALORIZACIÓN → cargar detalle
// ══════════════════════════════════════════════════════════════
function f_SelectValorizacion(tr) {
  $('#tbl_valorizaciones tr').removeClass('row-selected');
  $(tr).addClass('row-selected');

  idValorizacion_Selected = $(tr).data('id');
  const correlativo = $(tr).data('correlativo');
  const planta      = $(tr).data('planta');

  const codigo_val = $(tr).data('codigo-val');

  $('#lbl_titulo_detalle').html(`<b>N° ${correlativo}</b> ${codigo_val ? `[${codigo_val}]` : ''} — ${planta}`);
  f_LoadDetalleValorizacion(idValorizacion_Selected);
}

// ══════════════════════════════════════════════════════════════
//  DETALLE DE VALORIZACIÓN
// ══════════════════════════════════════════════════════════════
function f_LoadDetalleValorizacion(idValorizacion) {
  $('#tbl_detalle_valorizacion').html('');
  f_LoadingDetalle(1);

  $.post(url_api,
    { accion: 'get_DetalleValorizacionVenta', id_valorizacion: idValorizacion },
    function(data) {
      f_LoadingDetalle(0);

      if (data.estado !== 1 || !data.registros.length) {
        $('#tbl_detalle_valorizacion').html(
          '<tr><td colspan="16" class="text-center text-muted py-3">' +
          '<i class="bi bi-inbox me-2"></i>Sin líneas de detalle.</td></tr>'
        );
        return;
      }

      let html = '';
      let totalGeneral = 0;

      $.each(data.registros, function(i, v) {
        const pillElem = (v.elemento_quimico == 1)
          ? `<span class="pill-oro">Au</span>`
          : `<span class="pill-plata">Ag</span>`;

        totalGeneral += parseFloat(v.precio_total) || 0;

        html += `
          <tr style="font-size:12px;">
            <td>${pillElem}</td>
            <td>${v.codigo_interno || '—'}</td>
            <td>${v.codigo_cliente || '—'}</td>
            <td>${v.guia_transportista || '—'}</td>
            <td class="text-end">${f_RedondearDecimales(v.peso_seco, 3)}</td>
            <td class="text-end">${f_RedondearDecimales(v.ley_oro, 3)}</td>
            <td class="text-end">${f_RedondearDecimales(v.ley_plata, 3)}</td>
            <td class="text-end">${f_RedondearDecimales(v.recuperacion, 2)}</td>
            <td class="text-end">${f_RedondearDecimales(v.inter, 2)}</td>
            <td class="text-end">${f_RedondearDecimales(v.des_inter, 2)}</td>
            <td class="text-end">${f_RedondearDecimales(v.maquila, 2)}</td>
            <td class="text-end">${f_RedondearDecimales(v.consumo, 2)}</td>
            <td class="text-end">${f_RedondearDecimales(v.factor, 4)}</td>
            <td class="text-end">${f_RedondearDecimales(v.precio_por_tonelada, 2)}</td>
            <td class="text-end" style="font-weight:700;">${f_RedondearDecimales(v.precio_total, 2)}</td>
            <td>
              <a href="javascript:void(0)"
                 onclick="f_EliminarDetalleValorizacion(${v.id});"
                 style="color:#c62828;" title="Eliminar">
                <i class="bi bi-trash3-fill"></i>
              </a>
            </td>
          </tr>`;
      });

      // Fila de total
      html += `
        <tr class="total-row">
          <td colspan="14" class="text-end">TOTAL:</td>
          <td class="text-end">${f_RedondearDecimales(totalGeneral, 2)}</td>
          <td></td>
        </tr>`;

      $('#tbl_detalle_valorizacion').html(html);
    }, 'json').fail(function() {
      f_LoadingDetalle(0);
      alert('Error al cargar detalle.');
    });
}

// ══════════════════════════════════════════════════════════════
//  ABRIR MODAL VALORIZACIÓN (nueva / editar)
// ══════════════════════════════════════════════════════════════
function f_AdminValorizacion(_modo, _pos, _id) {
  _lotesEnModal = [];
  $('#tbody_lotes_modal').html('');

  if (_modo === 'x') {
    // Nueva
    $('#modal_valorizacionLabel').html('Nueva Valorización de Venta');
    $('#hd_modo_valorizacion').val('N');
    $('#hd_id_valorizacion').val(0);
    $('#cmb_planta_modal').val('').prop('disabled', false);
    $('#lbl_ruc_planta').text('—');
    $('#file_evidencias').val('');
    $('#div_evidencias_lista').html('');
    $('#txt_codigo_valorizacion').val('');
    _evidenciasExistentes = [];
  } else {
    // Editar
    $('#modal_valorizacionLabel').html(`Editar Valorización N° <b>${$(`#tr_vv_${_pos}`).data('correlativo')}</b>`);
    $('#hd_modo_valorizacion').val('E');
    $('#hd_id_valorizacion').val(_id);

    // Cargar datos existentes
    f_CargarDatosEdicion(_id);
  }

  f_GetTotalModal();
  f_OpenModal('modal_valorizacion');
}

function f_CargarDatosEdicion(idValorizacion) {
  $.post(url_api,
    { accion: 'get_DetalleValorizacionVenta', id_valorizacion: idValorizacion },
    function(data) {
      if (data.estado !== 1) return;

      // Setear planta en el combo del modal
      if (data.cabecera) {
        $('#cmb_planta_modal').val(data.cabecera.id_planta).prop('disabled', true);
        const sel = $('#cmb_planta_modal option:selected');
        $('#lbl_ruc_planta').text(sel.data('ruc') || '—');
        $('#txt_codigo_valorizacion').val(data.cabecera.codigo_valorizacion_venta || '');
        
        // Procesar evidencias (JSON array)
        $('#div_evidencias_lista').html('');
        _evidenciasExistentes = [];
        try {
          let evStr = data.cabecera.evidencias;
          if (evStr && evStr.trim().startsWith('[')) {
            _evidenciasExistentes = JSON.parse(evStr);
            let htmlEv = '';
            _evidenciasExistentes.forEach((ev, idx) => {
              htmlEv += `<div class="d-flex justify-content-between align-items-center mb-1">
                           <a href="${ev.path}" target="_blank" class="text-truncate" style="max-width:180px;">${ev.filename}</a>
                           <i class="bi bi-x-circle text-danger cursor-pointer" onclick="f_QuitarEvidencia(${idx});"></i>
                         </div>`;
            });
            $('#div_evidencias_lista').html(htmlEv);
          } else if (evStr && evStr.trim() !== '') {
            // Si hay texto pero NO es JSON (legacy), mostrarlo como nota simple (opcional)
            $('#div_evidencias_lista').html(`<div class="text-muted small">Nota previa: ${evStr}</div>`);
          }
        } catch(e) { console.error("Error parseando evidencias", e); }
      }

      // Cargar filas en la tabla del modal
      let html = '';
      $.each(data.registros, function(i, v) {
        html += f_BuildFilaModal(i, v, true);
        _lotesEnModal.push({ ...v, _idx: i });
      });

      $('#tbody_lotes_modal').html(html);
      f_GetTotalModal();
    }, 'json');
}

// ══════════════════════════════════════════════════════════════
//  CAMBIO DE PLANTA EN MODAL → cargar distribuciones
// ══════════════════════════════════════════════════════════════
function f_OnPlantaModalChange() {
  const idPlanta = $('#cmb_planta_modal').val();
  const sel      = $('#cmb_planta_modal option:selected');

  $('#lbl_ruc_planta').text(sel.data('ruc') || '—');

  // Limpiar lotes si se cambia la planta
  if ($('#tbody_lotes_modal tr').length > 0) {
    if (!confirm('Al cambiar la planta se eliminarán los lotes agregados. ¿Continuar?')) {
      return;
    }
    $('#tbody_lotes_modal').html('');
    _lotesEnModal = [];
    f_GetTotalModal();
  }
}

// ══════════════════════════════════════════════════════════════
//  MODAL LOTE: abrir
// ══════════════════════════════════════════════════════════════
function f_AdminLote(_modo, _idx) {
  const idPlanta = $('#cmb_planta_modal').val();
  let _datos = null;
  if (_modo === 'E') {
    _datos = _lotesEnModal[_idx];
  }

  if (!idPlanta) {
    alert('Debe seleccionar una Planta primero.');
    return;
  }

  f_LimpiarCamposLote();

  if (_modo === 'x') {
    // Nuevo
    $('#modal_loteLabel').html('Agregar Lote a Valorización');
    $('#hd_modo_lote').val('N');
    $('#hd_fila_edicion').val('');

    $('#div_lote_combo').show();
    $('#div_lote_static').hide();
    $('#div_elemento_combo').show();
    $('#div_elemento_static').hide();
    $('#cmb_distribucion').prop('disabled', false);
    $('#cmb_elemento').html('<option value="">Seleccione el lote...</option>').prop('disabled', true);

    // Cargar distribuciones disponibles
    f_CargarDistribuciones(idPlanta);

  } else {
    // Editar
    $('#modal_loteLabel').html('Editar Lote');
    $('#hd_modo_lote').val('E');
    $('#hd_fila_edicion').val(_idx);

    $('#div_lote_combo').hide();
    $('#div_lote_static').show();
    $('#div_elemento_combo').hide();
    $('#div_elemento_static').show();

    $('#lbl_lote_static').text(_datos.codigo_interno || '—');
    $('#lbl_elemento_static').text(_datos.elemento_quimico == 1 ? 'Oro (Au)' : 'Plata (Ag)');

    // Rellenar campos readonly del lote
    $('#f_codigo_interno').text(_datos.codigo_interno || '—');
    $('#f_codigo_cliente').text(_datos.codigo_cliente || '—');
    $('#f_guia_transportista').text(_datos.guia_transportista || '—');
    $('#f_peso_humedo').text(f_RedondearDecimales(_datos.peso_humedo, 3));
    $('#f_humedad').text(f_RedondearDecimales(_datos.ley_humedad_en_planta_destino, 2));
    $('#f_peso_seco').text(f_RedondearDecimales(_datos.peso_seco, 3));
    $('#f_ley_oro').text(f_RedondearDecimales(_datos.ley_oro, 3));
    $('#f_ley_plata').text(f_RedondearDecimales(_datos.ley_plata, 3));

    const leyUsada = (_datos.elemento_quimico == 1) ? _datos.ley_oro : _datos.ley_plata;
    $('#f_ley_seleccionada').text(f_RedondearDecimales(leyUsada, 3));

    // Campos editables
    $('#txt_recuperacion').val(_datos.recuperacion);
    $('#txt_inter').val(_datos.inter);
    $('#txt_des_inter').val(_datos.des_inter);
    $('#txt_maquila').val(_datos.maquila);
    $('#txt_consumo').val(_datos.consumo);
    $('#txt_factor').val(_datos.factor);

    $('#hd_id_distribucion_detalle').val(_datos.id_distribucion_detalle);
    $('#hd_elemento_quimico').val(_datos.elemento_quimico);
    $('#hd_id_condicion_comercial').val(_datos.id_condicion_comercial || 0);

    f_CalcularTotales();
  }

  // Cambiar label del botón grabar
  $('#modal_lote .btn-vv-lote-save.btn-primary').html(
    _modo === 'x' ? '<i class="bi bi-plus-lg me-1"></i>Agregar' : '<i class="bi bi-save me-1"></i>Guardar'
  );

  f_OpenModal('modal_lote');
}

// ══════════════════════════════════════════════════════════════
//  CARGAR DISTRIBUCIONES DISPONIBLES PARA LA PLANTA
// ══════════════════════════════════════════════════════════════
function f_CargarDistribuciones(idPlanta) {
  $('#cmb_distribucion').html('<option value="">[Cargando lotes...]</option>').prop('disabled', true);

  $.post(url_api,
    { accion: 'get_LotesDisponiblesVenta', id_planta: idPlanta },
    function(data) {
      let html = '<option value="">[Seleccione el lote]</option>';

      if (data.estado === 1 && data.registros.length) {
        $.each(data.registros, function(i, v) {
          // Verificar si ya está en la tabla modal
          const yaAgregadoOro   = _lotesEnModal.some(l => l.id_distribucion_detalle == v.id_distribucion_detalle && l.elemento_quimico == 1);
          const yaAgregadoPlata = _lotesEnModal.some(l => l.id_distribucion_detalle == v.id_distribucion_detalle && l.elemento_quimico == 2);

          // Verificar si queda algún elemento habilitado por agregar
          let tienePendiente = false;
          if (v.habilitado_oro == 1 && !yaAgregadoOro) tienePendiente = true;
          if (v.habilitado_plata == 1 && !yaAgregadoPlata) tienePendiente = true;

          // Si no tiene nada pendiente, saltar este lote
          if (!tienePendiente) return;

          html += `<option value="${v.id_distribucion_detalle}"
                     data-codigo-interno="${ v.codigo_interno || ''}"
                     data-codigo-cliente="${v.codigo_cliente || ''}"
                     data-guia-transportista="${v.guia_transportista || ''}"
                     data-peso-humedo="${v.peso_humedo || 0}"
                     data-humedad="${v.ley_humedad_en_planta_destino || 0}"
                     data-peso-seco="${v.peso_seco || 0}"
                     data-ley-oro="${v.ley_oro_en_planta_destino || 0}"
                     data-ley-plata="${v.ley_plata_en_planta_destino || 0}"
                     data-habilitado-oro="${v.habilitado_oro}"
                     data-habilitado-plata="${v.habilitado_plata}">
                     ${v.codigo_cliente + " | " + v.codigo_interno + " | Partición: " + v.numero_parte}
                   </option>`;
        });
      } else {
        html = '<option value="">[Sin lotes disponibles]</option>';
      }

      $('#cmb_distribucion').html(html).prop('disabled', false);
    }, 'json');
}

// ══════════════════════════════════════════════════════════════
//  ON CHANGE: distribución seleccionada
// ══════════════════════════════════════════════════════════════
function f_OnDistribucionChange() {
  const sel = $('#cmb_distribucion option:selected');
  const id  = $('#cmb_distribucion').val();

  f_LimpiarCamposLote();

  if (!id) {
    $('#cmb_elemento').html('<option value="">Seleccione el lote...</option>').prop('disabled', true);
    return;
  }

  // Rellenar campos readonly
  $('#f_codigo_interno').text(sel.data('codigo-interno') || '—');
  $('#f_codigo_cliente').text(sel.data('codigo-cliente') || '—');
  $('#f_guia_transportista').text(sel.data('guia-transportista') || '—');
  $('#f_peso_humedo').text(f_RedondearDecimales(sel.data('peso-humedo'), 3));
  $('#f_humedad').text(f_RedondearDecimales(sel.data('humedad'), 2));
  $('#f_peso_seco').text(f_RedondearDecimales(sel.data('peso-seco'), 3));
  $('#f_ley_oro').text(f_RedondearDecimales(sel.data('ley-oro'), 3));
  $('#f_ley_plata').text(f_RedondearDecimales(sel.data('ley-plata'), 3));

  // Construir opciones de elemento disponibles
  const yaAgregadoOro   = _lotesEnModal.some(l => l.id_distribucion_detalle == id && l.elemento_quimico == 1);
  const yaAgregadoPlata = _lotesEnModal.some(l => l.id_distribucion_detalle == id && l.elemento_quimico == 2);

  let htmlElem = '<option value="">[Seleccione]</option>';
  if (sel.data('habilitado-oro') == 1 && !yaAgregadoOro)  {
    htmlElem += '<option value="1" data-ley="' + sel.data('ley-oro') + '">🟡 Oro (Au)</option>';
  }
  if (sel.data('habilitado-plata') == 1 && !yaAgregadoPlata) {
    htmlElem += '<option value="2" data-ley="' + sel.data('ley-plata') + '">⚪ Plata (Ag)</option>';
  }

  $('#cmb_elemento').html(htmlElem).prop('disabled', false);
  $('#hd_id_distribucion_detalle').val(id);
}

// ══════════════════════════════════════════════════════════════
//  ON CHANGE: elemento seleccionado → cargar condición comercial
// ══════════════════════════════════════════════════════════════
function f_OnElementoChange() {
  const elem = $('#cmb_elemento').val();
  const sel  = $('#cmb_elemento option:selected');
  const ley  = parseFloat(sel.data('ley')) || 0;
  const idPlanta = $('#cmb_planta_modal').val();

  if (!elem) return;

  $('#hd_elemento_quimico').val(elem);
  $('#f_ley_seleccionada').text(f_RedondearDecimales(ley, 3));

  // Solo cargar C.C. automáticamente para Oro
  if (elem == 1) {
    f_ObtenerCondicionesComerciales(idPlanta, ley);
  } else {
    // Plata: No hay condiciones automáticas definidas (solo Au), resetear y pedir datos
    $('#div_sin_cc').show();
    $('#txt_recuperacion').val('');
    $('#txt_des_inter').val('');
    $('#txt_maquila').val('');
    $('#txt_consumo').val('');
    $('#hd_id_condicion_comercial').val(0);
    f_CalcularTotales();
  }
}

// ══════════════════════════════════════════════════════════════
//  OBTENER CONDICIONES COMERCIALES POR PLANTA Y LEY
// ══════════════════════════════════════════════════════════════
function f_ObtenerCondicionesComerciales(idPlanta, ley) {
  $('#div_sin_cc').hide();

  $.post(url_api,
    { accion: 'get_CondicionesComerciales_Venta', id_planta: idPlanta, ley: ley },
    function(data) {
      if (data.estado === 1) {
        $('#txt_recuperacion').val(data.recuperacion || '');
        $('#txt_maquila').val(data.maquila || '');
        $('#txt_consumo').val(data.consumo || '');
        $('#txt_des_inter').val(data.des_inter || '');
        $('#hd_id_condicion_comercial').val(data.id_condicion_comercial || 0);
      } else {
        $('#div_sin_cc').show();
        $('#txt_recuperacion').val('');
        $('#txt_maquila').val('');
        $('#txt_consumo').val('');
        $('#hd_id_condicion_comercial').val(0);
      }
      f_CalcularTotales();
    }, 'json');
}

// ══════════════════════════════════════════════════════════════
//  CÁLCULO DE TOTALES DEL LOTE
// ══════════════════════════════════════════════════════════════
function f_CalcularTotales() {
  const inter   = parseFloat($('#txt_inter').val())        || 0;
  const desInter = parseFloat($('#txt_des_inter').val())   || 0;
  const rec      = parseFloat($('#txt_recuperacion').val())|| 0;
  const maquila  = parseFloat($('#txt_maquila').val())     || 0;
  const consumo  = parseFloat($('#txt_consumo').val())     || 0;
  const factor   = parseFloat($('#txt_factor').val())      || 0;
  const pesoSeco = parseFloat($('#f_peso_seco').text().replace(/,/g, '')) || 0;

  // Ley a usar según elemento
  const elemHidden = $('#hd_elemento_quimico').val();
  let ley = 0;
  if (elemHidden) {
    ley = parseFloat($('#f_ley_seleccionada').text().replace(/,/g, '')) || 0;
  } else {
    const selElem = $('#cmb_elemento option:selected');
    ley = parseFloat(selElem.data('ley')) || 0;
  }

  // Fórmula: precio/TN = ((inter - desInter) * ley * (rec/100) - maquila - consumo) * factor
  let ptn = ((inter - desInter) * ley * (rec / 100) - maquila - consumo) * factor;
  if (ptn < 0) ptn = 0;
  ptn = Math.round(ptn * 100) / 100;

  const total = ptn * pesoSeco;

  $('#f_precio_tn').text(f_RedondearDecimales(ptn, 2));
  $('#f_total').text(f_RedondearDecimales(total, 2));
}

// ══════════════════════════════════════════════════════════════
//  GRABAR LOTE EN LA TABLA DEL MODAL (no va a BD aún)
// ══════════════════════════════════════════════════════════════
function f_GrabarLote() {
  const modo = $('#hd_modo_lote').val();
  const idDist = $('#hd_id_distribucion_detalle').val();
  const elemQuimico = $('#hd_elemento_quimico').val() || $('#cmb_elemento').val();

  // Validaciones
  if (!idDist) { alert('Debe seleccionar un lote/distribución.'); return; }
  if (!elemQuimico) { alert('Debe seleccionar el elemento químico.'); return; }

  const recuperacion = parseFloat($('#txt_recuperacion').val()) || 0;
  const inter        = parseFloat($('#txt_inter').val()) || 0;
  const desInter     = parseFloat($('#txt_des_inter').val()) || 0;
  const maquila      = parseFloat($('#txt_maquila').val()) || 0;
  const consumo      = parseFloat($('#txt_consumo').val()) || 0;
  const factor       = parseFloat($('#txt_factor').val()) || 0;
  const ptn          = parseFloat($('#f_precio_tn').text().replace(/,/g, '')) || 0;
  const total        = parseFloat($('#f_total').text().replace(/,/g, ''))  || 0;

  const pesoSeco  = parseFloat($('#f_peso_seco').text().replace(/,/g, '')) || 0;
  const pesoHumedo = parseFloat($('#f_peso_humedo').text().replace(/,/g, '')) || 0;
  const humedad   = parseFloat($('#f_humedad').text().replace(/,/g, '')) || 0;
  const leyOro    = parseFloat($('#f_ley_oro').text().replace(/,/g, '')) || 0;
  const leyPlata  = parseFloat($('#f_ley_plata').text().replace(/,/g, '')) || 0;
  const leyUsada  = parseFloat($('#f_ley_seleccionada').text().replace(/,/g, '')) || 0;

  const codInterno = $('#f_codigo_interno').text();
  const codCliente = $('#f_codigo_cliente').text();
  const grt        = $('#f_guia_transportista').text();
  const idCC       = $('#hd_id_condicion_comercial').val() || 0;

  const elemText = (elemQuimico == 1) ? '<span class="pill-oro">Au</span>' : '<span class="pill-plata">Ag</span>';

  if (modo === 'N') {
    // Verificar duplicado
    const dup = _lotesEnModal.some(l => l.id_distribucion_detalle == idDist && l.elemento_quimico == elemQuimico);
    if (dup) { alert('Este lote ya fue agregado con el mismo elemento.'); return; }

    const idx = _lotesEnModal.length;

    const nuevaFila = {
      id_distribucion_detalle: idDist,
      id_condicion_comercial: idCC,
      elemento_quimico: elemQuimico,
      recuperacion, inter, des_inter: desInter, maquila, consumo, factor,
      precio_por_tonelada: ptn, precio_total: total,
      // para UI
      codigo_interno: codInterno, codigo_cliente: codCliente,
      guia_transportista: grt,
      peso_humedo: pesoHumedo, ley_humedad_en_planta_destino: humedad,
      peso_seco: pesoSeco, ley_oro: leyOro, ley_plata: leyPlata, ley_usada: leyUsada,
      _idx: idx
    };

    _lotesEnModal.push(nuevaFila);
    $('#tbody_lotes_modal').append(f_BuildFilaModal(idx, nuevaFila, false));
    f_CargarDistribuciones($('#cmb_planta_modal').val());

  } else {
    // Editar fila existente
    const idx = parseInt($('#hd_fila_edicion').val());
    _lotesEnModal[idx] = { ..._lotesEnModal[idx], recuperacion, inter, des_inter: desInter, maquila, consumo, factor, precio_por_tonelada: ptn, precio_total: total };

    // Actualizar celdas
    $(`#ml_rec_${idx}`).text(f_RedondearDecimales(recuperacion, 2));
    $(`#ml_inter_${idx}`).text(f_RedondearDecimales(inter, 2));
    $(`#ml_desinter_${idx}`).text(f_RedondearDecimales(desInter, 2));
    $(`#ml_maquila_${idx}`).text(f_RedondearDecimales(maquila, 2));
    $(`#ml_consumo_${idx}`).text(f_RedondearDecimales(consumo, 2));
    $(`#ml_factor_${idx}`).text(f_RedondearDecimales(factor, 4));
    $(`#ml_ptn_${idx}`).text(f_RedondearDecimales(ptn, 2));
    $(`#ml_total_${idx}`).text(f_RedondearDecimales(total, 2));
  }

  f_GetTotalModal();
  f_cerrarModal('modal_lote');
}

/** Construye el HTML de una fila de la tabla del modal */
function f_BuildFilaModal(idx, v, esEdicion) {
  const elemQuimico = v.elemento_quimico;
  const elemText    = (elemQuimico == 1) ? '<span class="pill-oro">Au</span>' : '<span class="pill-plata">Ag</span>';

  const idDist = v.id_distribucion_detalle;
  const idCC   = v.id_condicion_comercial || 0;

  return `
    <tr id="ml_tr_${idx}" style="font-size:12px;">
      <td>${elemText}</td>
      <td>${v.codigo_interno || '—'}</td>
      <td>${v.codigo_cliente || '—'}</td>
      <td>${v.guia_transportista || '—'}</td>
      <td class="text-end">${f_RedondearDecimales(v.peso_humedo || v.peso_en_planta_destino, 3)}</td>
      <td class="text-end">${f_RedondearDecimales(v.ley_humedad_en_planta_destino, 2)}</td>
      <td class="text-end">${f_RedondearDecimales(v.peso_seco, 3)}</td>
      <td class="text-end">${f_RedondearDecimales(v.ley_oro || v.ley_oro_en_planta_destino, 3)}</td>
      <td class="text-end">${f_RedondearDecimales(v.ley_plata || v.ley_plata_en_planta_destino, 3)}</td>
      <td id="ml_rec_${idx}" class="text-end">${f_RedondearDecimales(v.recuperacion, 2)}</td>
      <td id="ml_inter_${idx}" class="text-end">${f_RedondearDecimales(v.inter, 2)}</td>
      <td id="ml_desinter_${idx}" class="text-end">${f_RedondearDecimales(v.des_inter, 2)}</td>
      <td id="ml_maquila_${idx}" class="text-end">${f_RedondearDecimales(v.maquila, 2)}</td>
      <td id="ml_consumo_${idx}" class="text-end">${f_RedondearDecimales(v.consumo, 2)}</td>
      <td id="ml_factor_${idx}" class="text-end">${f_RedondearDecimales(v.factor, 4)}</td>
      <td id="ml_ptn_${idx}" class="text-end">${f_RedondearDecimales(v.precio_por_tonelada, 2)}</td>
      <td id="ml_total_${idx}" class="text-end" style="font-weight:700;">${f_RedondearDecimales(v.precio_total, 2)}</td>
      <td>
        <div class="action-col">
          <a href="javascript:void(0)"
             onclick="f_AdminLote('E', ${idx});"
             style="color:#0277bd;">
            <i class="bi bi-pencil-square"></i> Editar
          </a>
          <a href="javascript:void(0)"
             onclick="f_EliminarFilaModal(${idx});"
             style="color:#c62828;">
            <i class="bi bi-trash3-fill"></i> Quitar
          </a>
        </div>
      </td>
    </tr>`;
}

function f_EliminarFilaModal(idx) {
  if (!confirm('¿Desea quitar este lote del detalle?')) return;
  $(`#ml_tr_${idx}`).remove();
  _lotesEnModal = _lotesEnModal.filter(l => l._idx !== idx);
  f_CargarDistribuciones($('#cmb_planta_modal').val());
  f_GetTotalModal();
}

// ══════════════════════════════════════════════════════════════
//  TOTAL EN EL MODAL
// ══════════════════════════════════════════════════════════════
function f_GetTotalModal() {
  $('#tr_total_modal').remove();

  let total = 0;
  _lotesEnModal.forEach(l => { total += parseFloat(l.precio_total) || 0; });

  if (_lotesEnModal.length > 0) {
    const html = `
      <tr id="tr_total_modal" class="total-row">
        <td colspan="16" class="text-end">TOTAL:</td>
        <td class="text-end">${f_RedondearDecimales(total, 2)}</td>
        <td></td>
      </tr>`;
    $('#tbody_lotes_modal').append(html);
  }
}

// ══════════════════════════════════════════════════════════════
//  GRABAR VALORIZACIÓN → backend
// ══════════════════════════════════════════════════════════════
function f_GrabarValorizacion() {
  const modo     = $('#hd_modo_valorizacion').val();
  const idVal    = $('#hd_id_valorizacion').val();
  const idPlanta = $('#cmb_planta_modal').val();

  if (!idPlanta) { alert('Debe seleccionar una Planta.'); return; }

  const detalle = _lotesEnModal.filter(l => l._idx !== undefined);
  if (!detalle.length) { alert('Debe agregar al menos un lote.'); return; }

  const formData = new FormData();
  formData.append('accion', 'grabar_ValorizacionVenta');
  formData.append('modo_grabar', modo);
  
  if (modo === 'E') {
    const motivo = prompt("Ingrese el motivo de la modificación (Para registro de trazabilidad):");
    if (motivo === null) return; // canceló
    if (motivo.trim() === '') {
      alert("El motivo es obligatorio para registrar la modificación.");
      return;
    }
    formData.append('motivo', motivo);
  }

  formData.append('id_valorizacion', idVal);
  formData.append('id_planta', idPlanta);
  formData.append('codigo_valorizacion_venta', $('#txt_codigo_valorizacion').val());
  formData.append('detalle', JSON.stringify(detalle));
  formData.append('evidencias_anteriores', JSON.stringify(_evidenciasExistentes));

  // Agregar archivos
  const files = $('#file_evidencias')[0].files;
  for (let i = 0; i < files.length; i++) {
    formData.append('archivos[]', files[i]);
  }

  f_LoadingGrabar(1);

  $.ajax({
    url: url_api,
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    dataType: 'json',
    success: function(data) {
      f_LoadingGrabar(0);
      if (data.estado === 1) {
        f_cerrarModal('modal_valorizacion');
        f_LoadValorizaciones();
      } else {
        alert('Error al grabar: ' + (data.msg || 'Error desconocido'));
      }
    },
    error: function() {
      f_LoadingGrabar(0);
      alert('Error de conexión con el servidor.');
    }
  });
}

function f_QuitarEvidencia(idx) {
  if (!confirm('¿Desea quitar este archivo?')) return;
  _evidenciasExistentes.splice(idx, 1);
  
  let htmlEv = '';
  _evidenciasExistentes.forEach((ev, i) => {
    htmlEv += `<div class="d-flex justify-content-between align-items-center mb-1">
                 <a href="${ev.path}" target="_blank" class="text-truncate" style="max-width:180px;">${ev.filename}</a>
                 <i class="bi bi-x-circle text-danger cursor-pointer" onclick="f_QuitarEvidencia(${i});"></i>
               </div>`;
  });
  $('#div_evidencias_lista').html(htmlEv);
}

// ══════════════════════════════════════════════════════════════
//  VER TRAZABILIDAD (CAMBIOS)
// ══════════════════════════════════════════════════════════════
function f_VerTrazabilidad(id_valorizacion) {
  $('#modal_trazabilidad_cards').html('<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando...</div>');
  f_OpenModal('modal_trazabilidad');
  
  $.post(url_api, { accion: 'get_TrazabilidadValorizacionVenta', id_valorizacion: id_valorizacion }, function(data) {
    if(data.estado === 1) {
      if(!data.registros || data.registros.length === 0) {
        $('#modal_trazabilidad_cards').html('<div class="text-center text-muted py-4">No hay registros de cambios para esta valorización.</div>');
        return;
      }
      let html = '';
      data.registros.forEach(r => {
        let arrCambios = [];
        try { arrCambios = JSON.parse(r.cambios); } catch(e){}
        let htmlCambios = '';
        if(arrCambios && arrCambios.length > 0) {
          arrCambios.forEach(c => {
            htmlCambios += `<div style="margin-bottom:6px; background:#f8f9fa; padding:8px; border-radius:6px; border:1px solid #e9ecef;">
              <div style="font-weight:600; color:#495057; font-size:12px; margin-bottom:4px;">${c.campo}</div>
              <div style="display:flex; align-items:center; gap:8px; font-size:13px;">
                <span class="text-muted text-decoration-line-through">${c.version_anterior}</span>
                <i class="bi bi-arrow-right text-primary"></i>
                <span style="font-weight:700; color:#212529;">${c.version_resultante}</span>
                <span class="${c.cambio.toString().startsWith('+') ? 'text-success' : 'text-danger'}" style="font-weight:600; font-size:11px;">(${c.cambio})</span>
              </div>
            </div>`;
          });
        } else {
          htmlCambios = `<div class="text-muted small">Sin cambios detectados en campos numéricos</div>`;
        }
        
        const elemText = r.elemento_quimico == 1 ? '<span class="pill-oro">Au</span>' : '<span class="pill-plata">Ag</span>';
        
        html += `
          <div class="vv-card p-3 mb-2" style="border-left: 4px solid var(--primary);">
            <div class="d-flex justify-content-between align-items-start mb-2 pb-2" style="border-bottom:1px solid var(--border);">
              <div>
                <div style="font-size:12px; color:var(--muted);"><i class="bi bi-calendar-event me-1"></i>${r.fechahora_registro}</div>
                <div style="font-weight:700; color:var(--primary);"><i class="bi bi-person-fill me-1"></i>${r.usuario_registro}</div>
              </div>
              <div class="text-end">
                <div style="font-weight:700; font-size:14px; margin-bottom:2px;">${r.codigo_interno || 'N/A'}</div>
                <div style="font-size:11px; color:var(--muted); margin-bottom:4px;"><i class="bi bi-person-badge"></i> ${r.codigo_cliente || 'N/A'}</div>
                ${elemText}
              </div>
            </div>
            <div class="mb-3">
              <div style="font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; margin-bottom:2px;">Motivo:</div>
              <div style="background:#fff3cd; color:#856404; padding:6px 10px; border-radius:4px; font-size:12px; border:1px solid #ffeeba;">
                ${r.motivo}
              </div>
            </div>
            <div>
              <div style="font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; margin-bottom:6px;">Detalle de Cambios:</div>
              <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap:8px;">
                ${htmlCambios}
              </div>
            </div>
          </div>
        `;
      });
      $('#modal_trazabilidad_cards').html(html);
    } else {
      $('#modal_trazabilidad_cards').html(`<div class="text-center text-danger py-4"><i class="bi bi-exclamation-triangle"></i> ${data.msg || 'Error al cargar datos.'}</div>`);
    }
  }, 'json').fail(function() {
    $('#modal_trazabilidad_cards').html('<div class="text-center text-danger py-4"><i class="bi bi-exclamation-triangle"></i> Error de conexión.</div>');
  });
}

// ══════════════════════════════════════════════════════════════
//  ELIMINAR LÍNEA DE DETALLE (desde panel resumen)
// ══════════════════════════════════════════════════════════════
function f_EliminarDetalleValorizacion(idDetalle) {
  if (!confirm('¿Eliminar esta línea del detalle?')) return;
  const motivo = prompt('Ingrese el motivo de la eliminación para la trazabilidad:');
  if (motivo === null) return; // Si cancela, sale de la función
  if (motivo.trim() === '') {
    alert("El motivo es obligatorio para registrar la eliminación en trazabilidad.");
    return;
  }

  $.post(url_api,
    { accion: 'eliminar_DetalleValorizacionVenta', id_detalle: idDetalle, motivo: motivo },
    function(data) {
      if (data.estado === 1) f_LoadDetalleValorizacion(idValorizacion_Selected);
      else alert('Error al eliminar: ' + (data.msg || 'Error desconocido'));
    }, 'json').fail(function() {
      alert('Error de conexión al eliminar.');
    });
}

// ══════════════════════════════════════════════════════════════
//  TOGGLE PANEL
// ══════════════════════════════════════════════════════════════
function f_TogglePanel() {
  if (!panelExpandido) {
    $('#div_venta_lista').hide();
    $('#div_venta_detalle').removeClass('col-md-7').addClass('col-md-12');
    $('#btn_toggle_panel i').removeClass('bi-arrows-angle-expand').addClass('bi-arrows-angle-contract');
    panelExpandido = true;
  } else {
    $('#div_venta_detalle').removeClass('col-md-12').addClass('col-md-7');
    $('#btn_toggle_panel i').removeClass('bi-arrows-angle-contract').addClass('bi-arrows-angle-expand');
    setTimeout(() => $('#div_venta_lista').show(), 280);
    panelExpandido = false;
  }
}

// ══════════════════════════════════════════════════════════════
//  HELPERS UI
// ══════════════════════════════════════════════════════════════
function f_LoadingList(_show) {
  _show ? $('#wt_valorizaciones').css('display','flex') : $('#wt_valorizaciones').hide();
}

function f_LoadingDetalle(_show) {
  _show ? $('#wt_detalle').css('display','flex') : $('#wt_detalle').hide();
}

function f_LoadingGrabar(_show) {
  if (_show) {
    $('#wt_grabar_valorizacion').css('display','flex');
    $('.btn-vv-save').prop('disabled', true);
  } else {
    $('#wt_grabar_valorizacion').hide();
    $('.btn-vv-save').prop('disabled', false);
  }
}

function f_LimpiarCamposLote() {
  $('#f_codigo_interno').text('—');
  $('#f_codigo_cliente').text('—');
  $('#f_guia_transportista').text('—');
  $('#f_peso_humedo').text('—');
  $('#f_humedad').text('—');
  $('#f_peso_seco').text('—');
  $('#f_ley_oro').text('—');
  $('#f_ley_plata').text('—');
  $('#f_ley_seleccionada').text('—');
  $('#f_precio_tn').text('—');
  $('#f_total').text('—');
  $('#txt_recuperacion, #txt_inter, #txt_des_inter, #txt_maquila, #txt_consumo').val('');
  $('#txt_factor').val('1.1023');
  $('#hd_id_distribucion_detalle, #hd_elemento_quimico, #hd_id_condicion_comercial').val('');
  $('#div_sin_cc').hide();
}

// ══════════════════════════════════════════════════════════════
//  GESTIÓN DE ARCHIVOS / EVIDENCIAS (Modal)
// ══════════════════════════════════════════════════════════════
let _idVVArchivos = 0; // id_valorizacion activo en el modal

function f_AbrirModalArchivosVV(idVal, correlativo) {
  _idVVArchivos = idVal;
  $('#lbl_archivos_vv_titulo').text('N° ' + correlativo);
  $('#file_nuevo_vv').val('');
  f_CargarListaArchivosVV();
  f_OpenModal('modal_archivos_vv');
}

function f_CargarListaArchivosVV() {
  $('#div_lista_archivos_vv').html('<p class="text-muted text-center small">Cargando...</p>');

  $.post(url_api, { accion: 'fv_ListarEvidencias', tipo: 'V', id: _idVVArchivos }, function(data) {
    const evs = data.evidencias || [];

    if (!evs.length) {
      $('#div_lista_archivos_vv').html('<p class="text-muted text-center small"><i class="bi bi-inbox me-1"></i>Sin archivos adjuntos.</p>');
      return;
    }

    let html = '';
    evs.forEach((ev, idx) => {
      html += `<div class="d-flex justify-content-between align-items-center mb-2 p-2" style="background:#f8f9fa; border-radius:6px; border:1px solid #dee2e6;">
        <a href=" ${ev.path} " target="_blank" style="font-size:13px; text-decoration:none; color:#0277bd;">
          <i class="bi bi-file-earmark me-1"></i>${ev.filename}
        </a>
        <button class="btn btn-danger btn-sm py-0 px-2" onclick="f_EliminarArchivoVV(${idx});" title="Eliminar">
          <i class="bi bi-trash3-fill"></i>
        </button>
      </div>`;
    });
    $('#div_lista_archivos_vv').html(html);
  }, 'json').fail(function() {
    $('#div_lista_archivos_vv').html('<p class="text-danger text-center small">Error al cargar archivos.</p>');
  });
}

function f_SubirArchivoVV() {
  const file = $('#file_nuevo_vv')[0].files[0];
  if (!file) { alert('Seleccione un archivo.'); return; }

  const fd = new FormData();
  fd.append('accion', 'fv_SubirEvidencia');
  fd.append('tipo', 'V');
  fd.append('id', _idVVArchivos);
  fd.append('archivo', file);

  $('#wt_subir_vv').show();

  $.ajax({
    url: url_api, type: 'POST', data: fd,
    contentType: false, processData: false, dataType: 'json',
    success: function(data) {
      $('#wt_subir_vv').hide();
      if (data.estado === 1) {
        $('#file_nuevo_vv').val('');
        f_CargarListaArchivosVV();
      } else {
        alert('Error al subir: ' + (data.msg || 'Error desconocido'));
      }
    },
    error: function() {
      $('#wt_subir_vv').hide();
      alert('Error de conexión.');
    }
  });
}

function f_EliminarArchivoVV(idx) {
  if (!confirm('¿Eliminar este archivo?')) return;

  $.post(url_api, { accion: 'fv_EliminarEvidencia', tipo: 'V', id: _idVVArchivos, index: idx }, function(data) {
    if (data.estado === 1) {
      f_CargarListaArchivosVV();
    } else {
      alert('Error al eliminar: ' + (data.msg || 'Error desconocido'));
    }
  }, 'json').fail(function() {
    alert('Error de conexión.');
  });
}