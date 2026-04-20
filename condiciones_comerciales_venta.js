/**
 * condiciones_comerciales_venta.js
 * Lógica de la vista Condiciones Comerciales Venta
 * Tablas: tbconfig_plantas  |  condiciones_comerciales_planta
 */

// ── Estado global ──────────────────────────────────────────────
let idPlanta_Selected        = 0;
let itemPlanta_Selected      = 0;
let descripcionPlanta_Selected = '';

// ── Inicialización ─────────────────────────────────────────────
function f_Init() {
  f_GetMenuPrincipal();
  $("#nv_titulo").html('| Condiciones Comerciales Venta');
  f_LoadPlantas();
}

// ══════════════════════════════════════════════════════════════
//  CARGA DE PLANTAS
// ══════════════════════════════════════════════════════════════
function f_LoadPlantas() {
  $("#tbl_plantas").html('');
  f_LoadingPlantas(1);

  $.post(
    "apis/backend.php",
    { accion: "get_plantas" },
    function (data) {
      f_LoadingPlantas(0);

      if (data.estado !== 1 || !data.data.plantas.length) return;

      var html = '';
      var plantas = data.data.plantas;

      $.each(plantas, function (idx, planta) {
        var n = idx + 1;
        html +=
          '<tr class="tr_item_P" id="tr_item_P_' + n + '" ' +
          '    onclick="f_LoadCondicionesComerciales(' + n + ', ' + planta.id_planta + ', \'' + f_EscapeStr(planta.descripcion) + '\')">' +
          '  <td style="width:36px;">' + n + '</td>' +
          '  <td>' + planta.ruc + '</td>' +
          '  <td id="td_planta_desc_' + n + '" style="text-align:left;">' + planta.descripcion + '</td>' +
          '</tr>';
      });

      $("#tbl_plantas").html(html);

      // Seleccionar la primera planta automáticamente
      var primera = plantas[0];
      f_LoadCondicionesComerciales(1, primera.id_planta, primera.descripcion);
    },
    "json"
  );
}

// ══════════════════════════════════════════════════════════════
//  CARGA DE CONDICIONES COMERCIALES
// ══════════════════════════════════════════════════════════════
function f_LoadCondicionesComerciales(_item, _id_planta, _descripcion_planta) {
  if (!_id_planta) return;

  $("#tbl_condiciones_comerciales").html('');
  f_LoadingCondicionesComerciales(1);

  $.post(
    "apis/backend.php",
    { accion: "get_ListaCondicionesComercialesVenta", id_planta: _id_planta },
    function (data) {
      f_LoadingCondicionesComerciales(0);

      // Guardar selección actual
      itemPlanta_Selected        = _item;
      idPlanta_Selected          = _id_planta;
      descripcionPlanta_Selected = _descripcion_planta;

      f_ColorSelectedPlanta(_item);

      if (data.estado !== 1 || !data.registros.length) {
        $("#tbl_condiciones_comerciales").html(
          '<tr><td colspan="9" class="text-center text-muted py-3">' +
          '<i class="bi bi-inbox me-2"></i>Sin condiciones comerciales registradas.</td></tr>'
        );
        return;
      }

      var html = '';
      $.each(data.registros, function (idx, cc) {
        var n       = idx + 1;
        var esActivo = (cc.estado === 'A');

        var badgeEstado  = esActivo
          ? '<span class="badge-activo">Activo</span>'
          : '<span class="badge-inactivo">Inactivo</span>';

        var accionEstado = esActivo ? 'Inactivar' : 'Activar';
        var colorEstado  = esActivo ? '#E6A50D'   : '#44803F';
        var iconEstado   = esActivo ? 'bi bi-node-minus' : 'bi bi-node-plus';
        var nuevoEstado  = esActivo ? 'I' : 'A';

        html +=
          '<tr style="font-size:13px;">' +
          '  <td>' + n + '</td>' +
          '  <td><b>' + cc.ley_auoz_inicio + '</b></td>' +
          '  <td><b>' + cc.ley_auoz_fin    + '</b></td>' +
          '  <td>' + cc.maquila           + '</td>' +
          '  <td>' + cc.recuperacion      + '</td>' +
          '  <td>' + cc.consumo           + '</td>' +
          '  <td>' + cc.riesgo_comercial  + '</td>' +
          '  <td>' + badgeEstado          + '</td>' +
          '  <td>' +
          '    <div class="action-links">' +
          '      <a href="javascript:void(0)" onclick="f_AdminCondicionComercial(' + n + ',' + cc.id + ',' +
                    cc.ley_auoz_inicio + ',' + cc.ley_auoz_fin + ',' +
                    cc.maquila + ',' + cc.recuperacion + ',' + cc.consumo + ',' + cc.riesgo_comercial +
                 ')">' +
          '        <i class="bi bi-pencil-square" style="color:#337ab7;"></i>' +
          '        <span style="color:#337ab7;">Editar</span>' +
          '      </a>' +
          '      <a href="javascript:void(0)" onclick="f_CambiarEstado(\'' + nuevoEstado + '\',' + cc.id + ')">' +
          '        <i class="' + iconEstado + '" style="color:' + colorEstado + ';"></i>' +
          '        <span style="color:' + colorEstado + ';">' + accionEstado + '</span>' +
          '      </a>' +
          '      <a href="javascript:void(0)" onclick="f_EliminarRegistro(' + cc.id + ')">' +
          '        <i class="bi bi-file-x" style="color:#E63946;"></i>' +
          '        <span style="color:#E63946;">Eliminar</span>' +
          '      </a>' +
          '    </div>' +
          '  </td>' +
          '</tr>';
      });

      $("#tbl_condiciones_comerciales").html(html);
    },
    "json"
  );
}

// ══════════════════════════════════════════════════════════════
//  MODAL: ABRIR AGREGAR / EDITAR
// ══════════════════════════════════════════════════════════════
function f_AdminCondicionComercial(_item, _id_cc, _ley_inicio, _ley_fin, _maquila, _recuperacion, _consumo, _riesgo) {
  var esNuevo = (_item === 'x');
  var tipo    = esNuevo ? 'N' : 'E';
  var titulo  = esNuevo
    ? 'Nueva Condición Comercial'
    : 'Editar Condición Comercial: <b>' + descripcionPlanta_Selected + '</b>';

  $("#modal_addcondicioncomercialLabel").html(titulo);
  $("#hd_modograbar").val(tipo);

  if (esNuevo) {
    $("#hd_id_condicion_comercial").val(0);
    $("#condicion_comercial_ley_auoz_inicio").val('');
    $("#condicion_comercial_ley_auoz_fin").val('');
    $("#condicion_comercial_maquila").val('');
    $("#condicion_comercial_recuperacion").val('');
    $("#condicion_comercial_consumo").val('');
    $("#condicion_comercial_riesgo_comercial").val('');
  } else {
    $("#hd_id_condicion_comercial").val(_id_cc);
    $("#condicion_comercial_ley_auoz_inicio").val(f_CleanInjection(_ley_inicio));
    $("#condicion_comercial_ley_auoz_fin").val(f_CleanInjection(_ley_fin));
    $("#condicion_comercial_maquila").val(f_CleanInjection(_maquila));
    $("#condicion_comercial_recuperacion").val(f_CleanInjection(_recuperacion));
    $("#condicion_comercial_consumo").val(f_CleanInjection(_consumo));
    $("#condicion_comercial_riesgo_comercial").val(f_CleanInjection(_riesgo));
  }

  f_OpenModal('modal_addcondicioncomercial');
}

// ══════════════════════════════════════════════════════════════
//  GRABAR CONDICIÓN COMERCIAL
// ══════════════════════════════════════════════════════════════
function f_GrabarCondicionComercial() {
  var id_cc        = $("#hd_id_condicion_comercial").val();
  var modo_grabar  = $("#hd_modograbar").val();

  var ley_inicio   = f_CleanInjection($("#condicion_comercial_ley_auoz_inicio").val());
  var ley_fin      = f_CleanInjection($("#condicion_comercial_ley_auoz_fin").val());
  var maquila      = f_CleanInjection($("#condicion_comercial_maquila").val());
  var recuperacion = f_CleanInjection($("#condicion_comercial_recuperacion").val());
  var consumo      = f_CleanInjection($("#condicion_comercial_consumo").val());
  var riesgo       = f_CleanInjection($("#condicion_comercial_riesgo_comercial").val());

  // Validaciones
  if (!ley_inicio || ley_inicio.length === 0) {
    alert("Debe ingresar la Ley Auoz de Inicio.");
    return;
  }
  if (!ley_fin || ley_fin.length === 0) {
    alert("Debe ingresar la Ley Auoz de Fin.");
    return;
  }
  if (idPlanta_Selected === 0) {
    alert("Debe seleccionar una planta primero.");
    return;
  }

  $.post(
    "apis/backend.php",
    {
      accion:               "grabar_CondicionComercialVenta",
      modo_grabar:          modo_grabar,
      id_condicion_comercial: id_cc,
      id_planta:            idPlanta_Selected,
      ley_auoz_inicio:      ley_inicio,
      ley_auoz_fin:         ley_fin,
      maquila:              maquila,
      recuperacion:         recuperacion,
      consumo:              consumo,
      riesgo_comercial:     riesgo
    },
    function (data) {
      if (data.estado === 1) {
        f_cerrarModal('modal_addcondicioncomercial');
        f_LoadCondicionesComerciales(itemPlanta_Selected, idPlanta_Selected, descripcionPlanta_Selected);
      } else {
        alert("Ocurrió un error al grabar la Condición Comercial.");
      }
    },
    "json"
  );
}

// ══════════════════════════════════════════════════════════════
//  CAMBIAR ESTADO (A / I)
// ══════════════════════════════════════════════════════════════
function f_CambiarEstado(_nuevoEstado, _id_registro) {
  if (_nuevoEstado !== 'A' && _nuevoEstado !== 'I') {
    alert("Ocurrió un error al intentar cambiar el estado.");
    return;
  }

  var accion = (_nuevoEstado === 'I') ? 'Inactivar' : 'Activar';

  if (!confirm("¿Está seguro de " + accion + " la condición comercial seleccionada?")) return;

  $.post(
    "apis/backend.php",
    { accion: "update_EstadoCondicionComercialVenta", id_registro: _id_registro, estado: _nuevoEstado },
    function (data) {
      if (data.estado === 1) {
        f_LoadCondicionesComerciales(itemPlanta_Selected, idPlanta_Selected, descripcionPlanta_Selected);
      } else {
        alert("Ocurrió un error al cambiar el estado.");
      }
    },
    "json"
  );
}

// ══════════════════════════════════════════════════════════════
//  ELIMINAR REGISTRO (estado = 'X')
// ══════════════════════════════════════════════════════════════
function f_EliminarRegistro(_id_registro) {
  if (!confirm("¿Está seguro de eliminar la condición comercial seleccionada?\n\nEsta acción es permanente. ¿Desea continuar?")) return;

  $.post(
    "apis/backend.php",
    { accion: "eliminar_CondicionComercialVenta", id_registro: _id_registro },
    function (data) {
      if (data.estado === 1) {
        f_LoadCondicionesComerciales(itemPlanta_Selected, idPlanta_Selected, descripcionPlanta_Selected);
      } else {
        alert("Ocurrió un error al eliminar el registro.");
      }
    },
    "json"
  );
}

// ══════════════════════════════════════════════════════════════
//  HELPERS UI
// ══════════════════════════════════════════════════════════════
function f_LoadingPlantas(_show) {
  _show ? $("#wt_plantas").css('display', 'flex') : $("#wt_plantas").hide();
}

function f_LoadingCondicionesComerciales(_show) {
  _show ? $("#wt_condiciones_comerciales").css('display', 'flex') : $("#wt_condiciones_comerciales").hide();
}

function f_ColorSelectedPlanta(_item) {
  $(".tr_item_P").removeClass('selected-row');
  $("#tr_item_P_" + _item).addClass('selected-row');
  $("#lbl_tituloplanta").html($("#td_planta_desc_" + _item).html());
}

/** Escapa comillas simples para uso seguro en atributos HTML inline */
function f_EscapeStr(str) {
  if (!str) return '';
  return str.replace(/'/g, "\\'");
}