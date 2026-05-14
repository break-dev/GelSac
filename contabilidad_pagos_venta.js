'use strict';
console.log('contabilidad_pagos_venta.js CARGADO');

function f_SetDimension() { /* Global layout adjust - usually in auxiliares_js.php */ }

var url_api = 'apis/backend.php';

// Estado global
var _factura_actual = null;   // factura abierta en modal detalle
var _lotes_modal = [];     // lotes valorizados cargados en modal
var _anticipos_modal = [];     // anticipos disponibles de la planta
var _evidencias_contexto = { tipo: '', id: 0 }; // 'F' para factura, 'P' para pago

// ---------------------------------------------------------------------------------------------------------------------------------------
//  INIT: carga facturas
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_LoadFacturas() {
    var anio = $('#filtro_anio').val();
    var mes = $('#filtro_mes').val();

    $('#div_factura_grid').html(
        '<div class="empty-state w-100"><i class="bi bi-hourglass-split"></i>Cargando...</div>'
    );
    $('#wt_resumen').show();

    $.post(url_api, { 
        accion: 'fv_ListaFacturasVenta',
        anio: anio,
        mes: mes
    }, function (data) {
        $('#wt_resumen').hide();
        if (data.estado !== 1 || !data.registros.length) {
            $('#div_factura_grid').html(
                '<div class="empty-state w-100"><i class="bi bi-inbox"></i>Sin comprobantes registrados para este periodo.</div>'
            );
            return;
        }
        $('#div_factura_grid').html(data.registros.map(function (r) { return f_BuildCard(r); }).join(''));
    }, 'json').fail(function () {
        $('#wt_resumen').hide();
        $('#div_factura_grid').html('<div class="empty-state w-100 text-danger"><i class="bi bi-exclamation-circle"></i>Error al cargar.</div>');
    });
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Card HTML
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_BuildCard(r) {
    var estadoMap = {
        E: ['est-E', 'En Espera'],
        P: ['est-P', 'En Proceso'],
        A: ['est-A', 'Pagado Mixto'],
        B: ['est-B', 'Pagado Transf.'],
        C: ['est-C', 'Pagado Anticipos'],
        X: ['est-X', 'Anulada']
    };
    var _estInfo = estadoMap[r.estado] ? estadoMap[r.estado] : ['est-E', r.estado];
    var estCls = _estInfo[0];
    var estLbl = _estInfo[1];

    var totalUsd = parseFloat(r.total_dolares) || 0;
    var totalSoles = parseFloat(r.total_soles) || 0;
    var tc = parseFloat(r.tipo_cambio_venta) || 0;

    var mNeto = parseFloat(r.monto_neto) || 0;
    var mDetr = parseFloat(r.monto_detraccion) || 0;
    var mAnt = parseFloat(r.avance_pago_anticipo) || 0;

    var aNeto = parseFloat(r.avance_pago_neto) || 0;
    var aDetr = parseFloat(r.avance_pago_detraccion) || 0;

    // Cálculos de Pagado
    var pagadoTotal = mAnt + aNeto + aDetr;
    var saldoTotal = Math.max(0, totalUsd - pagadoTotal);
    var pctTotal = totalUsd > 0 ? Math.min((pagadoTotal / totalUsd) * 100, 100).toFixed(0) : 0;

    var saldoNeto = Math.max(0, mNeto - aNeto);
    var pctNeto = mNeto > 0 ? Math.min((aNeto / mNeto) * 100, 100).toFixed(0) : 0;

    var mDetrSoles = mDetr * tc;
    var aDetrSoles = aDetr * tc;
    var saldoDetrSoles = Math.max(0, mDetrSoles - aDetrSoles);
    var pctDetr = mDetr > 0 ? Math.min((aDetr / mDetr) * 100, 100).toFixed(0) : 0;

    var esAnulada = r.estado === 'X';

    // Lotes HTML
    var htmlLotes = '';
    (r.lotes || []).forEach(function (l) {
        var pill = l.elemento_quimico == 1 ? '<div class="pill-au">Au</div>' : '<div class="pill-ag">Ag</div>';
        htmlLotes +=
            '<div class="lote-item">' +
            '<div class="lote-info-main">' +
            pill +
            '<div class="lote-codes">' +
            '<span class="lote-client">' + (l.codigo_cliente || 'S/C') + '</span>' +
            '<span class="lote-internal">' + (l.codigo_interno || '') + ' <small class="text-muted">(P:' + (l.numero_parte || '') + ')</small></span>' +
            '</div>' +
            '</div>' +
            '<div class="lote-price">$ ' + f_Fmt(l.precio_total, 2) + '</div>' +
            '</div>';
    });

    // Guardar en string para las funciones
    var strJson = btoa(JSON.stringify(r));

    return '<div class="fv-card" id="fvcard_' + r.id + '">' +
        '<div class="fv-card-header">' +
        '<div>' +
        '<div class="badge-serie">' + r.serie + '-' + r.numero + '</div>' +
        '<div class="fv-fecha"><i class="bi bi-calendar-event me-1"></i>' + r.fecha_emision + '</div>' +
        '</div>' +
        '<div class="text-center">' +
        '<div class="fv-planta text-white mb-0"><i class="bi bi-building me-1"></i>' + r.descripcion_planta + '</div>' +
        '</div>' +
        '<div class="text-end d-flex align-items-center gap-2">' +
        '<button class="btn btn-outline-light btn-sm py-0 px-1 border-0" onclick="f_AbrirModalEvidencias(\'F\', ' + r.id + ');" title="Gestionar Evidencias">' +
        '<i class="bi bi-files fs-6"></i>' +
        '</button>' +
        '<span class="estado-badge ' + estCls + '">' + estLbl + '</span>' +
        '</div>' +
        '</div>' +
        '<div class="fv-card-body">' +
        '<div class="fv-summary-grid mb-3">' +
        '<!-- CARD TOTAL -->' +
        '<div class="fv-summary-card total">' +
        '<div class="card-title">' +
        'Total Comprobante' +
        '<span class="badge-status ' + (saldoTotal <= 0.01 ? 'badge-pagado' : 'badge-pendiente') + '">' +
        (saldoTotal <= 0.01 ? 'Pagado' : 'Pendiente') +
        '</span>' +
        '</div>' +
        '<div class="card-amount">$ ' + f_Fmt(totalUsd, 2) + '</div>' +
        '<div class="card-sub-info">' +
        'Equiv: <b>S/ ' + f_Fmt(totalSoles, 2) + '</b> <br>' +
        'IGV: S/ ' + f_Fmt(r.monto_igv, 2) + ' | TC: ' + tc.toFixed(4) +
        '</div>' +
        '<div class="prog-info">' +
        '<span>Pendiente: $ ' + f_Fmt(saldoTotal, 2) + '</span>' +
        '<span>' + pctTotal + '%</span>' +
        '</div>' +
        '<div class="progress"><div class="progress-bar bg-primary" style="width:' + pctTotal + '%"></div></div>' +
        '</div>' +

        '<!-- CARD NETO -->' +
        '<div class="fv-summary-card neto">' +
        '<div class="card-title">' +
        'Saldo Neto ($USD)' +
        '<span class="badge-status ' + (saldoNeto <= 0.01 ? 'badge-pagado' : 'badge-pendiente') + '">' +
        (saldoNeto <= 0.01 ? 'Pagado' : 'Pendiente') +
        '</span>' +
        '</div>' +
        '<div class="card-amount">$ ' + f_Fmt(mNeto, 2) + '</div>' +
        '<div class="card-sub-info">' +
        'Equiv: <b>S/ ' + f_Fmt(mNeto * tc, 2) + '</b> <br>' +
        'TC: ' + tc.toFixed(4) +
        '</div>' +

        '<div class="prog-info">' +
        '<span>Pendiente: $ ' + f_Fmt(saldoNeto, 2) + '</span>' +
        '<span>' + pctNeto + '%</span>' +
        '</div>' +
        '<div class="progress"><div class="progress-bar bg-primary" style="width:' + pctNeto + '%"></div></div>' +
        '</div>' +

        '<!-- CARD DETRACCION -->' +
        '<div class="fv-summary-card detraccion">' +
        '<div class="card-title">' +
        'Detracción (S/)' +
        '<span class="badge-status ' + (saldoDetrSoles <= 0.01 ? 'badge-pagado' : 'badge-pendiente') + '">' +
        (saldoDetrSoles <= 0.01 ? 'Pagado' : 'Pendiente') +
        '</span>' +
        '</div>' +
        '<div class="card-amount">S/ ' + f_Fmt(mDetrSoles, 2) + '</div>' +
        '<div class="card-sub-info">' +
        'Equiv: <b>$ ' + f_Fmt(mDetr, 2) + '</b> <br>' +
        'TC: ' + tc.toFixed(4) + ' | ' + (r.porcentaje_detraccion || 12) + '%' +
        '</div>' +
        '<div class="prog-info">' +
        '<span>Pendiente: S/ ' + f_Fmt(saldoDetrSoles, 2) + '</span>' +
        '<span>' + pctDetr + '%</span>' +
        '</div>' +
        '<div class="progress"><div class="progress-bar bg-success" style="width:' + pctDetr + '%"></div></div>' +
        '</div>' +
        '</div>' +

        '<!-- Lotes Desplegables -->' +
        '<div class="lotes-collapse-header mb-2" onclick="$(\'#collapseLotes_' + r.id + '\').collapse(\'toggle\');">' +
        '<span><i class="bi bi-layers me-2"></i>Lotes Valorizados (' + (r.lotes ? r.lotes.length : 0) + ')</span>' +
        '<i class="bi bi-chevron-down"></i>' +
        '</div>' +
        '<div class="collapse border rounded mb-3" id="collapseLotes_' + r.id + '">' +
        '<div class="lotes-grid">' +
        (htmlLotes || '<div class="p-3 text-center text-muted small w-100">Sin lotes asociados.</div>') +
        '</div>' +
        '</div>' +

        '<div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">' +
        '<div class="text-muted small">' +
        '<i class="bi bi-person me-1"></i>Registrado por: <b>' + r.usuario_registro + '</b>' +
        '</div>' +
        '<div class="d-flex gap-2">' +
        (!esAnulada ?
            '<button class="btn btn-danger btn-sm" onclick="f_AnularFacturaDirecto(\'' + strJson + '\');">' +
            '<i class="bi bi-x-circle me-1"></i> Anular' +
            '</button>' +
            '<button class="btn btn-success btn-sm px-4" onclick="f_IrAPagosDirecto(\'' + strJson + '\');">' +
            '<i class="bi bi-cash-coin me-1"></i> Ver / Registrar Pagos' +
            '</button>' :
            '<span class="badge bg-secondary">Comprobante anulado</span>') +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
}

function f_IrAPagosDirecto(strJson) {
    var r = JSON.parse(atob(strJson));
    _factura_actual = r;
    $('#lbl_pago_factura').text(r.serie + '-' + r.numero);
    f_LoadPagos();
    f_OpenModal('modal_lista_pagos');
}

function f_AnularFacturaDirecto(strJson) {
    var r = JSON.parse(atob(strJson));
    if (!confirm('Anular el comprobante ' + r.serie + '-' + r.numero + '?')) return;

    $('#div_loading').addClass('show');
    $.post(url_api, { accion: 'fv_AnularFacturaVenta', id_factura: r.id }, function (data) {
        $('#div_loading').removeClass('show');
        if (data.estado === 1) {
            f_LoadFacturas();
        } else {
            alert('Error: ' + (data.msg || 'No se pudo anular.'));
        }
    }, 'json').fail(function () {
        $('#div_loading').removeClass('show');
        alert('Error de conexion.');
    });
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  MODAL: Nueva Factura
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_AbrirModalNuevaFactura() {
    _lotes_modal = [];
    _anticipos_modal = [];

    $('#nf_planta').val('');
    // $('#nf_fecha').val(new Date().toISOString().split('T')[0]); // Se comenta para usar la fecha del servidor (Peru)
    $('#nf_serie, #nf_numero').val('');
    $('#nf_detraccion').val(10);
    $('#nf_tipocambio').val('');
    $('#nf_tc_info').html('');
    $('#nf_usa_anticipos').prop('checked', false);
    $('#div_anticipos').hide();
    $('#tbl_lotes_valorizacion').html('<tr><td colspan="7" class="text-center text-muted py-3">Seleccione una planta.</td></tr>');
    $('#tbl_anticipos_planta').html('<tr><td colspan="6" class="text-center text-muted py-2">Sin anticipos.</td></tr>');
    f_RecalcResumen();
    f_GetTipoCambioVenta();
    f_OpenModal('modal_nueva_factura');
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Tipo de Cambio para venta
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_GetTipoCambioVenta() {
    var fecha = $('#nf_fecha').val();
    $('#nf_tc_info').text('Consultando...');

    // Reset global state
    gb_tipocambio_id = 0;
    gb_tipocambio_compra = '';
    gb_tipocambio_venta = '';
    var _modo = 'N';

    $.post(url_api, { accion: 'fv_GetTipoCambio', fecha: fecha }, function (data) {
        if (data.estado === 1) {
            gb_tipocambio_id = data.id_tipo_cambio;
            gb_tipocambio_compra = data.tc_compra;
            gb_tipocambio_venta = data.tc_venta;
            _modo = 'E';
            $('#nf_tipocambio').val(data.tc_venta);
            $('#nf_tc_info').html('<span class="text-success">TC Compra: ' + data.tc_compra + '</span>');
        } else {
            $('#nf_tipocambio').val('');
            $('#nf_tc_info').html('<span class="text-danger">Sin TC hoy</span>');
        }
        $('#hd_idtipocambio').val(gb_tipocambio_id);
        $('#hd_tipocambio_modograbar').val(_modo);
    }, 'json').fail(function () { $('#nf_tc_info').text('Error'); });
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Cambia planta -> carga lotes + anticipos
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_OnPlantaChange() {
    var id_planta = $('#nf_planta').val();
    _lotes_modal = [];
    f_RecalcResumen();

    if (!id_planta) {
        $('#tbl_lotes_valorizacion').html('<tr><td colspan="7" class="text-center text-muted py-3">Seleccione una planta.</td></tr>');
        return;
    }

    // Cargar lotes valorizados en venta con Au
    $('#nf_lotes_loading').show();
    $('#tbl_lotes_valorizacion').html('');

    $.post(url_api, { accion: 'fv_GetLotesValorizadosVenta', id_planta: id_planta }, function (data) {
        $('#nf_lotes_loading').hide();
        if (data.estado !== 1 || !data.registros.length) {
            $('#tbl_lotes_valorizacion').html('<tr><td colspan="7" class="text-center text-muted py-3">Sin lotes disponibles.</td></tr>');
            return;
        }

        // ------------------------------------------------------------------------------------------------------------------------------------------------------------------
        // FIX: guardar los registros en _lotes_modal para que
        // f_RecalcResumen y f_GrabarFactura puedan leerlos
        // ------------------------------------------------------------------------------------------------------------------------------------------------------------------
        _lotes_modal = data.registros;

        var html = '';
        data.registros.forEach(function (v) {
            var elem = v.elemento_quimico == 1
                ? '<span class="pill-au">Au</span>'
                : '<span class="pill-ag">Ag</span>';
            html += '<tr id="lotrow_' + v.id + '" ' +
                'data-id="' + v.id + '" ' +
                'data-precio="' + v.precio_total + '" ' +
                'data-cod-cliente="' + (v.codigo_cliente || '') + '" ' +
                'data-cod-interno="' + (v.codigo_interno || '') + '" ' +
                'data-particion="' + (v.numero_parte || '') + '" ' +
                'data-elem="' + v.elemento_quimico + '" ' +
                'data-id-valorizacion="' + v.id_valorizacion_venta + '" ' +
                'style="cursor:pointer;" onclick="f_ToggleLote(this);">' +
                '<td><input type="checkbox" class="form-check-input chk-lote" data-id="' + v.id + '" value="' + v.id + '"></td>' +
                '<td style="font-size:11px;color:#6c757d;">' + (v.codigo_valorizacion_venta || '') + '</td>' +
                '<td>' + (v.codigo_cliente || '') + '</td>' +
                '<td>' + (v.codigo_interno || '') + '</td>' +
                '<td>' + (v.numero_parte || '') + '</td>' +
                '<td>' + elem + '</td>' +
                '<td class="text-end fw-bold">$ ' + f_Fmt(v.precio_total, 2) + '</td>' +
                '</tr>';
        });
        $('#tbl_lotes_valorizacion').html(html);
    }, 'json').fail(function () {
        $('#nf_lotes_loading').hide();
        $('#tbl_lotes_valorizacion').html('<tr><td colspan="7" class="text-danger text-center py-2">Error al cargar lotes.</td></tr>');
    });

    // Si anticipos estaba activo, recargar
    if ($('#nf_usa_anticipos').is(':checked')) {
        f_CargarAnticiposPlanta(id_planta);
    }
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Toggle fila lote
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_ToggleLote(tr) {
    var chk = $(tr).find('.chk-lote');
    var nuevoEstado = !chk.prop('checked');
    chk.prop('checked', nuevoEstado);
    $(tr).toggleClass('table-primary', nuevoEstado);
    f_RecalcResumen();
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Toggle seccion anticipos
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_ToggleAnticipos() {
    var usa = $('#nf_usa_anticipos').is(':checked');
    $('#div_anticipos').toggle(usa);
    if (usa) {
        var id_planta = $('#nf_planta').val();
        if (id_planta) f_CargarAnticiposPlanta(id_planta);
    }
    f_RecalcResumen();
}

function f_CargarAnticiposPlanta(id_planta) {
    $('#nf_anticipos_loading').show();
    $('#tbl_anticipos_planta').html('');
    $.post(url_api, { accion: 'fv_GetAnticiposPlanta', id_planta: id_planta }, function (data) {
        $('#nf_anticipos_loading').hide();
        if (data.estado !== 1 || !data.anticipos.length) {
            $('#tbl_anticipos_planta').html('<tr><td colspan="6" class="text-center text-muted py-2">Sin anticipos con saldo.</td></tr>');
            return;
        }
        _anticipos_modal = data.anticipos;
        $('#tbl_anticipos_planta').html(f_BuildAnticiposTable(data.anticipos));
    }, 'json').fail(function () {
        $('#nf_anticipos_loading').hide();
    });
}

function f_BuildAnticiposTable(anticipos) {
    if (!anticipos.length) {
        return '<tr><td colspan="6" class="text-center text-muted py-2">Sin anticipos disponibles.</td></tr>';
    }
    return anticipos.map(function (a) {
        return '<tr class="anticipo-row" id="ant_row_' + a.id_anticipo + '">' +
            '<td class="text-center">' +
            '<input type="checkbox" class="chk-anticipo form-check-input" value="' + a.id_anticipo + '" ' +
            'data-saldo="' + a.saldo_actual + '" onchange="f_CalcAnticiposSeleccionados();">' +
            '</td>' +
            '<td>' + a.factura + '</td>' +
            '<td class="text-end">$ ' + f_Fmt(a.saldo_inicial, 2) + '</td>' +
            '<td class="text-end fw-bold text-success">$ ' + f_Fmt(a.saldo_actual, 2) + '</td>' +
            '<td class="text-center">' +
            '<input type="number" class="form-control form-control-sm text-end nf-ant-monto" ' +
            'style="width:100px; display:inline-block;" step="0.01" min="0" max="' + a.saldo_actual + '" ' +
            'placeholder="0.00" oninput="f_CalcAnticiposSeleccionados();" disabled>' +
            '</td>' +
            '<td class="text-center">' + a.fecha_registro + '</td>' +
            '</tr>';
    }).join('');
}

function f_CalcAnticiposSeleccionados() {
    var totalFactura = 0;
    // Calcular total de la factura (suma de lotes seleccionados)
    $('.chk-lote:checked').each(function () {
        var id = $(this).val();
        for (var i = 0; i < _lotes_modal.length; i++) {
            if (_lotes_modal[i].id == id) {
                totalFactura += parseFloat(_lotes_modal[i].precio_total) || 0;
                break;
            }
        }
    });

    var totalUsado = 0;
    $('#tbl_anticipos_planta tr').each(function () {
        var chk = $(this).find('.chk-anticipo');
        var input = $(this).find('.nf-ant-monto');
        var saldoMax = parseFloat(chk.data('saldo')) || 0;

        if (chk.is(':checked')) {
            $(this).addClass('table-primary');
            input.prop('disabled', false);

            var monto = parseFloat(input.val()) || 0;

            // Si está marcado pero el monto es 0, sugerimos el saldo máximo O el restante de la factura
            if (monto === 0 && !input.data('manual')) {
                var restante = Math.max(0, totalFactura - totalUsado);
                monto = Math.min(saldoMax, restante);
                input.val(monto.toFixed(2));
            }

            if (monto > saldoMax) {
                monto = saldoMax;
                input.val(monto.toFixed(2));
            }
            totalUsado += monto;
        } else {
            $(this).removeClass('table-primary');
            input.prop('disabled', true);
            input.val('');
            input.data('manual', false);
        }
    });
    $('#nf_total_anticipos_lbl').text('$ ' + f_Fmt(totalUsado, 2));
    $('#nf_res_anticipos').text('$ ' + f_Fmt(totalUsado, 2));
}

// Marcar como manual si el usuario escribe
$(document).on('input', '.nf-ant-monto', function () {
    $(this).data('manual', true);
});

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Recalcular resumen financiero
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_RecalcResumen() {
    var totalUsd = 0;

    // FIX: leer precio desde _lotes_modal usando el value del checkbox
    $('.chk-lote:checked').each(function () {
        var id = $(this).val();
        for (var i = 0; i < _lotes_modal.length; i++) {
            if (_lotes_modal[i].id == id) {
                totalUsd += parseFloat(_lotes_modal[i].precio_total) || 0;
                break;
            }
        }
    });

    var tc = parseFloat($('#nf_tipocambio').val()) || 0;
    var pdet = parseFloat($('#nf_detraccion').val()) || 0;
    var soles = totalUsd * tc;
    var detUsd = totalUsd * (pdet / 100);

    $('#nf_total_usd_lbl').text('Total: $ ' + f_Fmt(totalUsd, 2));
    $('#nf_res_usd').text('$ ' + f_Fmt(totalUsd, 2));
    $('#nf_res_soles').text('S/ ' + f_Fmt(soles, 2));
    $('#nf_res_detraccion').text('$ ' + f_Fmt(detUsd, 2));
    f_CalcAnticiposSeleccionados();
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Tipo de Cambio Quick Register
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_AdminTipoCambio() {
    $('#modal_admintipocambioLabel').html('Registrar Tipo de Cambio');

    $('#tipocambio_fecha').val($('#nf_fecha').val());
    $('#tipocambio_compra').val(gb_tipocambio_compra);
    $('#tipocambio_venta').val($('#nf_tipocambio').val());
    $('#tipocambio_moneda').val(2); // Dolares default

    f_OpenModal('modal_admintipocambio');
}

function f_GrabarTipoCambio() {
    var fecha = $('#tipocambio_fecha').val();
    var moneda = $('#tipocambio_moneda').val();
    var compra = $('#tipocambio_compra').val();
    var venta = $('#tipocambio_venta').val();
    var id = $('#hd_idtipocambio').val();
    var modo = $('#hd_tipocambio_modograbar').val();

    if (!compra || !venta) {
        alert('Ingrese TC Compra y Venta');
        return;
    }

    $('.wt_admintipocambio_button').prop('disabled', true);
    $('#wt_admintipocambio').show();

    $.post(url_api, {
        accion: 'grabar_config_tipocambio',
        modo: modo,
        id: id,
        fecha: fecha,
        compra: compra,
        venta: venta,
        id_moneda_base: moneda,
    }, function (data) {
        $('#wt_admintipocambio').hide();
        $('.wt_admintipocambio_button').prop('disabled', false);

        if (data.estado == 1 || data.estado == 2) {
            if (data.estado == 2) alert('El Tipo de Cambio para esta fecha ya existía, se ha actualizado.');
            f_cerrarModal('modal_admintipocambio');
            f_GetTipoCambioVenta();
        } else {
            alert(data.msg || 'Error al grabar TC');
        }
    }, 'json').fail(function () {
        $('#wt_admintipocambio').hide();
        $('.wt_admintipocambio_button').prop('disabled', false);
        alert('Error de conexión');
    });
}
window.f_AdminTipoCambio = f_AdminTipoCambio;
window.f_GrabarTipoCambio = f_GrabarTipoCambio;

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Grabar Factura
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_GrabarFactura() {
    var id_planta = $('#nf_planta').val();
    var fecha = $('#nf_fecha').val();
    var serie = $('#nf_serie').val().trim().toUpperCase();
    var numero = $('#nf_numero').val().trim().toUpperCase();
    var pdet = $('#nf_detraccion').val();
    var tc = $('#nf_tipocambio').val();
    var id_tc = gb_tipocambio_id || '';

    if (!id_planta) { alert('Seleccione una planta.'); return; }
    if (!fecha) { alert('Ingrese la fecha de emision.'); return; }
    if (!serie) { alert('Ingrese la serie del comprobante.'); return; }
    if (!numero) { alert('Ingrese el numero del comprobante.'); return; }
    if (!tc || parseFloat(tc) <= 0) { alert('Ingrese el tipo de cambio.'); return; }

    // Lotes seleccionados
    var lotes = [];
    $('.chk-lote:checked').each(function () {
        var id = $(this).val();
        for (var i = 0; i < _lotes_modal.length; i++) {
            if (_lotes_modal[i].id == id) {
                lotes.push({
                    id_valorizacion_venta_detalle: _lotes_modal[i].id,
                    precio_total: _lotes_modal[i].precio_total
                });
                break;
            }
        }
    });

    if (!lotes.length) { alert('Seleccione al menos un lote valorizado.'); return; }

    // Anticipos seleccionados
    var anticipos = [];
    $('#tbl_anticipos_planta tr').each(function () {
        var chk = $(this).find('.chk-anticipo');
        var input = $(this).find('.nf-ant-monto');
        if (chk.is(':checked')) {
            var monto = parseFloat(input.val()) || 0;
            if (monto > 0) {
                anticipos.push({
                    id_anticipo_planta: chk.val(),
                    monto_uso: monto
                });
            }
        }
    });

    var usa_anticipos = $('#nf_usa_anticipos').is(':checked') ? 1 : 0;

    var formData = new FormData();
    formData.append('accion', 'fv_GrabarFacturaVenta');
    formData.append('id_planta', id_planta);
    formData.append('id_tipo_cambio', id_tc);
    formData.append('fecha_emision', fecha);
    formData.append('serie', serie);
    formData.append('numero', numero);
    formData.append('porcentaje_detraccion', pdet);
    formData.append('tc_venta', tc);
    formData.append('lotes', JSON.stringify(lotes));
    formData.append('anticipos', JSON.stringify(anticipos));
    formData.append('usa_anticipos', usa_anticipos);

    var files = $('#nf_evidencias')[0] ? $('#nf_evidencias')[0].files : [];
    for (var i = 0; i < files.length; i++) {
        formData.append('archivos[]', files[i]);
    }

    $('#wt_grabar_factura').show();
    $('#modal_nueva_factura .modal-footer .btn').prop('disabled', true);

    $.ajax({
        url: url_api, type: 'POST', data: formData,
        contentType: false, processData: false, dataType: 'json',
        success: function (data) {
            $('#wt_grabar_factura').hide();
            $('#modal_nueva_factura .modal-footer .btn').prop('disabled', false);
            if (data.estado === 1) {
                f_cerrarModal('modal_nueva_factura');
                f_LoadFacturas();
            } else {
                alert('Error: ' + (data.msg || 'Error desconocido'));
            }
        },
        error: function () {
            $('#wt_grabar_factura').hide();
            $('#modal_nueva_factura .modal-footer .btn').prop('disabled', false);
            alert('Error de conexion.');
        }
    });
}



function f_LoadPagos() {
    if (!_factura_actual) return;
    var id_factura = _factura_actual.id;

    $('#div_loading').addClass('show');
    $.post(url_api, { accion: 'fv_GetListaPagos', id_factura: id_factura }, function (data) {
        $('#div_loading').removeClass('show');
        if (data.estado !== 1 || !data.pagos.length) {
            $('#div_pagos_grid').html('<div class="empty-state w-100 text-center text-muted"><i class="bi bi-inbox fs-1"></i><p>Sin pagos registrados.</p></div>');
            f_UpdatePagosSummary(0);
            return;
        }

        var html = '';
        var totalPagado = 0;
        data.pagos.forEach(function (p, i) {
            var m_pagado = parseFloat(p.monto_pagado) || 0;
            var c_dolares = parseFloat(p.cambio_dolares) || 1;
            var es_det = p.es_para_detraccion == 1;

            if (es_det) {
                totalPagado += m_pagado / c_dolares;
            } else {
                totalPagado += m_pagado;
            }

            var ev = p.evidencias ? JSON.parse(p.evidencias) : [];
            var htmlEv = '<button class="btn btn-sm btn-outline-info ms-2" onclick="f_AbrirModalEvidencias(\'P\', ' + p.id + ');" title="Gestionar Evidencias"><i class="bi bi-files"></i></button>';

            html += '<div class="fv-card mb-3" style="border-left: 4px solid ' + (es_det ? '#198754' : '#0d6efd') + ';">' +
                    '<div class="fv-card-header py-2" style="background:#f8f9fa; color:#333; border-bottom:1px solid #e9ecef;">' +
                        '<div>' +
                            '<span class="badge bg-' + (es_det ? 'success' : 'primary') + ' me-2">' + (es_det ? 'Detracción' : 'Neto') + '</span>' +
                            '<b>#' + (i + 1) + '</b> - ' + (p.medio_pago || 'Pago') +
                            (p.nro_operacion ? ' <small class="text-muted ms-2">(Op: ' + p.nro_operacion + ')</small>' : '') +
                        '</div>' +
                        '<div class="text-end" style="font-size:11px;">' +
                            '<span class="text-muted me-3"><i class="bi bi-clock me-1"></i>Reg: ' + p.created_at + ' (' + (p.usuario_registro || '') + ')</span>' +
                            '<button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="f_EliminarPago(' + p.id + ');" title="Eliminar"><i class="bi bi-trash"></i></button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="fv-card-body py-2">' +
                        '<div class="row align-items-center">' +
                            '<div class="col-md-3">' +
                                '<div class="text-muted" style="font-size:10px;">Fecha Pago</div>' +
                                '<div class="fw-bold">' + (p.fecha_hora_pago || '') + '</div>' +
                            '</div>' +
                            '<div class="col-md-5">' +
                                '<div class="d-flex align-items-center gap-2">' +
                                    '<div style="flex:1;">' +
                                        '<div class="text-muted" style="font-size:10px;">Origen (Planta)</div>' +
                                        '<div class="fw-bold text-truncate" style="font-size:11px;">' + (p.banco_planta || 'N/A') + '</div>' +
                                        '<div class="text-muted" style="font-size:11px;">' + (p.cuenta_planta || 'N/A') + '</div>' +
                                    '</div>' +
                                    '<i class="bi bi-arrow-right text-muted"></i>' +
                                    '<div style="flex:1;">' +
                                        '<div class="text-muted" style="font-size:10px;">Destino (GEL)</div>' +
                                        '<div class="fw-bold text-truncate" style="font-size:11px;">' + (p.banco_empresa || 'N/A') + '</div>' +
                                        '<div class="text-muted" style="font-size:11px;">' + (p.cuenta_empresa || 'N/A') + '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-4 text-end">' +
                                '<div class="fw-bold ' + (es_det ? 'text-success' : 'text-primary') + '" style="font-size:18px;">' +
                                    (es_det ? 'S/ ' : '$ ') + f_Fmt(m_pagado, 2) +
                                '</div>' +
                                '<div class="text-muted" style="font-size:11px;">' +
                                    'TC: ' + f_Fmt(c_dolares, 4) +
                                    (es_det ? ' (~$ ' + f_Fmt(m_pagado / c_dolares, 2) + ')' : '') +
                                '</div>' +
                                '<div class="mt-1">' + htmlEv + '</div>' +
                            '</div>' +
                        '</div>' +
                        (p.observacion ? '<div class="mt-2 text-muted" style="font-size:11px; font-style:italic;">Obs: ' + p.observacion + '</div>' : '') +
                    '</div>' +
                '</div>';
        });
        $('#div_pagos_grid').html(html);
        f_UpdatePagosSummary(totalPagado);
    }, 'json').fail(function () {
        $('#div_loading').removeClass('show');
    });
}

function f_UpdatePagosSummary(pagado) {
    var total = parseFloat(_factura_actual.total_dolares) || 0;
    var anticipo = parseFloat(_factura_actual.avance_pago_anticipo) || 0;
    var total_pagado = pagado + anticipo;
    var saldo = total - total_pagado;

    $('#lp_total_usd').text('$ ' + f_Fmt(total, 2));
    $('#lp_pagado_usd').text('$ ' + f_Fmt(total_pagado, 2));
    $('#lp_saldo_usd').text('$ ' + f_Fmt(saldo, 2));
    $('#lp_foot_total').text('$ ' + f_Fmt(total_pagado, 2));

    $('#btn_nuevo_pago').prop('disabled', saldo <= 0.01);
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Modal Registro Pago
// ---------------------------------------------------------------------------------------------------------------------------------------
var _cuentas_planta = [];
var _cuentas_empresa = [];

function f_AbrirModalRegistroPago() {
    if (!_factura_actual) return;

    $('#rp_fecha').val(new Date().toISOString().split('T')[0]);
    $('#rp_is_detraccion').prop('checked', false);
    $('#rp_monto').val('');
    $('#rp_observacion').val('');
    $('#rp_tipocambio').val(_factura_actual.tipo_cambio_venta || 1);
    $('#rp_monto_usd').val('0.00');
    $('#rp_nro_operacion').val('');
    $('#rp_por_pagar').html('<span class="text-muted" style="font-size:12px;">Calculando...</span>');

    // Saldo
    var total_dolares = parseFloat(_factura_actual.total_dolares) || 0;
    var total_pagado = parseFloat($('#lp_foot_total').text().replace(/[^0-9.-]+/g, '')) || 0;

    // Limpiar el input de archivo de forma segura
    var rpEv = $('#rp_evidencia');
    rpEv.val('');

    f_CargarCuentas();
    f_CargarMediosPago();
    f_OpenModal('modal_registro_pago');
}

function f_CargarMediosPago() {
    $.post(url_api, { accion: 'fv_GetMediosPago' }, function (data) {
        if (data.estado === 1) {
            var html = data.registros.map(function (m) {
                return '<option value="' + m.Id + '">' + m.descripcion + '</option>';
            }).join('');
            $('#rp_medio_pago').html(html);
        }
    }, 'json');
}

function f_OnIsDetraccionChange() {
    f_CargarCuentas();
}

function f_CargarCuentas() {
    if (!_factura_actual) return;
    var id_planta = _factura_actual.id_planta;

    var reqPlanta = $.post(url_api, { accion: 'get_listaplantasbancos', id_planta: id_planta });
    var reqEmpresa = $.post(url_api, { accion: 'fv_GetCuentasEmpresa' });

    $('#rp_banco_planta').html('<option value="">Cargando...</option>');
    $('#rp_banco_empresa').html('<option value="">Cargando...</option>');

    $.when(reqPlanta, reqEmpresa).done(function (data1, data2) {
        var resPlanta = typeof data1[0] === 'string' ? JSON.parse(data1[0]) : data1[0];
        var resEmpresa = typeof data2[0] === 'string' ? JSON.parse(data2[0]) : data2[0];

        _cuentas_planta = resPlanta.estado === 1 ? resPlanta.res : [];
        _cuentas_empresa = resEmpresa.estado === 1 ? resEmpresa.cuentas : [];

        f_OnIsDetraccionChange();
    });
}

function f_OnIsDetraccionChange() {
    var isDet = $('#rp_is_detraccion').is(':checked') ? 1 : 0;

    // Planta Banks
    var bPlanta = {};
    _cuentas_planta.filter(function(c) { return c.is_detraccion == isDet; }).forEach(function(c) {
        bPlanta[c.id_banco] = c.banco;
    });
    var htmlBP = '<option value="">Elija una opción...</option>';
    for (var k in bPlanta) htmlBP += '<option value="' + k + '">' + bPlanta[k] + '</option>';
    $('#rp_banco_planta').html(htmlBP);
    $('#rp_cuenta_planta').html('<option value="">Seleccione cuenta</option>');

    // Empresa Banks
    var bEmpresa = {};
    _cuentas_empresa.filter(function(c) { return c.is_detraccion == isDet; }).forEach(function(c) {
        bEmpresa[c.banco] = c.nombre_banco || c.banco; // cod_banco mapped to c.banco, use description if available
    });
    var htmlBE = '<option value="">Elija una opción...</option>';
    for (var k in bEmpresa) htmlBE += '<option value="' + k + '">' + bEmpresa[k] + '</option>';
    $('#rp_banco_empresa').html(htmlBE);
    $('#rp_cuenta_empresa').html('<option value="">Seleccione cuenta</option>');

    f_SugerirMontoPago();
}

function f_OnChangeBancoPlanta() {
    var isDet = $('#rp_is_detraccion').is(':checked') ? 1 : 0;
    var bId = $('#rp_banco_planta').val();
    var html = '<option value="">Seleccione cuenta</option>';
    if (bId) {
        _cuentas_planta.filter(function(c) { return c.is_detraccion == isDet && c.id_banco == bId; }).forEach(function(c) {
            var label = c.nro_cuenta;
            if(c.cci) label += ' | CCI: ' + c.cci;
            html += '<option value="' + c.Id + '" data-moneda="' + c.moneda + '" data-id_banco="' + c.id_banco + '">' +
                    '(' + c.moneda + ') ' + label +
                    '</option>';
        });
    }
    $('#rp_cuenta_planta').html(html);
    f_SugerirMontoPago();
}

function f_OnChangeBancoEmpresa() {
    var isDet = $('#rp_is_detraccion').is(':checked') ? 1 : 0;
    var bId = $('#rp_banco_empresa').val();
    var html = '<option value="">Seleccione cuenta</option>';
    if (bId) {
        _cuentas_empresa.filter(function(c) { return c.is_detraccion == isDet && c.banco == bId; }).forEach(function(c) {
            html += '<option value="' + c.id + '" data-moneda="' + c.moneda + '" data-cod_banco="' + c.banco + '">' +
                    '(' + c.moneda + ') ' + c.num_cuenta +
                    '</option>';
        });
    }
    $('#rp_cuenta_empresa').html(html);
    f_SugerirMontoPago();
}

function f_SugerirMontoPago() {
    if (!_factura_actual) return;
    var isDetraccion = $('#rp_is_detraccion').is(':checked');
    var plantaOpt = $('#rp_cuenta_planta').find('option:selected');
    var empresaOpt = $('#rp_cuenta_empresa').find('option:selected');

    var total_dolares = parseFloat(_factura_actual.total_dolares) || 0;
    var monto_detraccion = parseFloat(_factura_actual.monto_detraccion) || 0;
    var avance_pago_detraccion = parseFloat(_factura_actual.avance_pago_detraccion) || 0;
    var monto_neto = parseFloat(_factura_actual.monto_neto) || 0;
    var avance_pago_neto = parseFloat(_factura_actual.avance_pago_neto) || 0;
    var tc = parseFloat(_factura_actual.tipo_cambio_venta) || 1;

    var saldoDetUsd = Math.max(0, monto_detraccion - avance_pago_detraccion);
    var saldoNetoUsd = Math.max(0, monto_neto - avance_pago_neto);

    // Update rp_por_pagar with dual currency
    if (isDetraccion) {
        var saldoDetSoles = saldoDetUsd * tc;
        $('#rp_por_pagar').html('$ ' + f_Fmt(saldoDetUsd, 2) + ' <small class="text-muted ms-1" style="font-size:11px;">(S/ ' + f_Fmt(saldoDetSoles, 2) + ')</small>');
    } else {
        var saldoNetoSoles = saldoNetoUsd * tc;
        $('#rp_por_pagar').html('$ ' + f_Fmt(saldoNetoUsd, 2) + ' <small class="text-muted ms-1" style="font-size:11px;">(S/ ' + f_Fmt(saldoNetoSoles, 2) + ')</small>');
    }

    var isBnPlanta = plantaOpt.data('id_banco') == 3;
    var isBnEmpresa = empresaOpt.data('cod_banco') == 'BN';

    if (isDetraccion && isBnPlanta && isBnEmpresa) {
        var detSolesTotal = monto_detraccion * tc;
        var detSolesAvance = avance_pago_detraccion * tc;
        var saldoDetraccion = Math.max(0, detSolesTotal - detSolesAvance);
        
        $('#rp_moneda_pago_simbolo').text('S/');
        $('#rp_monto').val(saldoDetraccion.toFixed(2));
    } else {
        var saldoNeto = Math.max(0, monto_neto - avance_pago_neto);
        
        $('#rp_moneda_pago_simbolo').text('$');
        $('#rp_monto').val(saldoNeto.toFixed(2));
    }
    f_RecalcConversionPago();
}

function f_RecalcConversionPago() {
    var monto = parseFloat($('#rp_monto').val()) || 0;
    var tc = parseFloat($('#rp_tipocambio').val()) || 1;
    var isDetraccion = $('#rp_is_detraccion').is(':checked');
    var isSoles = $('#rp_moneda_pago_simbolo').text() === 'S/';

    if (isSoles) {
        var usd = tc > 0 ? (monto / tc).toFixed(2) : '0.00';
        $('#rp_monto_usd').val(usd);
    } else {
        $('#rp_monto_usd').val(monto.toFixed(2));
    }
}

function f_GrabarPago() {
    var id_factura = _factura_actual.id;
    var fecha = $('#rp_fecha').val();
    var medio = $('#rp_medio_pago').val();
    var isDet = $('#rp_is_detraccion').is(':checked') ? 1 : 0;
    var ctPlanta = $('#rp_cuenta_planta').val();
    var ctEmpresa = $('#rp_cuenta_empresa').val();
    var nroOp = $('#rp_nro_operacion').val();
    var monto = $('#rp_monto').val();
    var tc = $('#rp_tipocambio').val();
    var obs = $('#rp_observacion').val();

    if (!fecha || !monto || !ctPlanta || !ctEmpresa) {
        alert('Por favor complete los campos obligatorios.');
        return;
    }

    var formData = new FormData();
    formData.append('accion', 'fv_GrabarPagoFactura');
    formData.append('id_factura_venta', id_factura);
    formData.append('fecha', fecha);
    formData.append('id_medio_pago', medio);
    formData.append('is_detraccion', isDet);
    formData.append('id_cuenta_planta', ctPlanta);
    formData.append('id_cuenta_empresa', ctEmpresa);
    formData.append('num_operacion', nroOp);
    formData.append('monto_pagado', monto);
    formData.append('cambio_dolares', tc);
    formData.append('observacion', obs);

    var fileInput = $('#rp_evidencia')[0];
    if (fileInput && fileInput.files.length > 0) {
        formData.append('archivo', fileInput.files[0]);
    }

    $('#wt_grabar_pago').show();
    $('#modal_registro_pago .modal-footer .btn').prop('disabled', true);

    $.ajax({
        url: url_api, type: 'POST', data: formData,
        contentType: false, processData: false, dataType: 'json',
        success: function (data) {
            $('#wt_grabar_pago').hide();
            $('#modal_registro_pago .modal-footer .btn').prop('disabled', false);
            if (data.estado === 1) {
                f_cerrarModal('modal_registro_pago');
                f_LoadPagos();
                f_LoadFacturas();
            } else {
                alert('Error: ' + data.msg);
            }
        },
        error: function () {
            $('#wt_grabar_pago').hide();
            $('#modal_registro_pago .modal-footer .btn').prop('disabled', false);
            alert('Error de conexion.');
        }
    });
}

function f_EliminarPago(id_pago) {
    if (!confirm('Esta seguro de eliminar este pago?')) return;

    $('#div_loading').addClass('show');
    $.post(url_api, { accion: 'fv_EliminarPagoFactura', id_pago: id_pago }, function (data) {
        $('#div_loading').removeClass('show');
        if (data.estado === 1) {
            f_LoadPagos();
            f_LoadFacturas();
        } else {
            alert('Error: ' + data.msg);
        }
    }, 'json');
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  Helpers
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_Fmt(val, dec) {
    return parseFloat(val || 0).toLocaleString('en-US', {
        minimumFractionDigits: dec,
        maximumFractionDigits: dec
    });
}

// Escuchar cambios en TC y % detraccion para recalcular resumen
$(document).on('input', '#nf_tipocambio, #nf_detraccion', f_RecalcResumen);

// ---------------------------------------------------------------------------------------------------------------------------------------
//  GestiÃ³n de Evidencias
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_AbrirModalEvidencias(tipo, id) {
    _evidencias_contexto = { tipo: tipo, id: id };
    $('#ev_nuevo_archivo').val('');
    f_LoadEvidencias();
    f_OpenModal('modal_evidencias');
}

function f_LoadEvidencias() {
    $('#div_evidencias_list').html('<div class="text-center p-3"><img src="images/waiting.gif" width="20"></div>');
    $.post(url_api, { 
        accion: 'fv_ListarEvidencias', 
        tipo: _evidencias_contexto.tipo, 
        id: _evidencias_contexto.id 
    }, function (data) {
        if (data.estado !== 1 || !data.evidencias.length) {
            $('#div_evidencias_list').html('<div class="text-center p-3 text-muted">Sin archivos adjuntos.</div>');
            return;
        }
        var html = data.evidencias.map(function (ev, i) {
            return '<div class="list-group-item d-flex justify-content-between align-items-center py-2">' +
                '<div class="text-truncate" style="max-width: 80%;">' +
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

function f_SubirEvidencia() {
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
        url: url_api, type: 'POST', data: formData,
        contentType: false, processData: false, dataType: 'json',
        success: function (data) {
            $('#div_loading').removeClass('show');
            if (data.estado === 1) {
                $('#ev_nuevo_archivo').val('');
                f_LoadEvidencias();
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

function f_EliminarEvidencia(index) {
    if (!confirm('¿Desea eliminar este archivo?')) return;

    $('#div_loading').addClass('show');
    $.post(url_api, { 
        accion: 'fv_EliminarEvidencia', 
        tipo: _evidencias_contexto.tipo, 
        id: _evidencias_contexto.id,
        index: index
    }, function (data) {
        $('#div_loading').removeClass('show');
        if (data.estado === 1) {
            f_LoadEvidencias();
        } else {
            alert('Error: ' + data.msg);
        }
    }, 'json');
}

// ---------------------------------------------------------------------------------------------------------------------------------------
//  INIT
// ---------------------------------------------------------------------------------------------------------------------------------------
function f_Init() {
    if (typeof f_GetMenuPrincipal === 'function') f_GetMenuPrincipal();
    $('#nv_titulo').html('| Comprobantes Venta Mineral');

    // Inicializar filtros con mes actual
    var d = new Date();
    var mes = ("0" + (d.getMonth() + 1)).slice(-2);
    $('#filtro_mes').val(mes);

    f_LoadFacturas();
    f_GetTipoCambioVenta();
}
