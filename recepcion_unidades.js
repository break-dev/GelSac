function f_Init() {
  // Genera menús
  f_GetMenuPrincipal();

  // Titulo de Pantalla
  $("#nv_titulo").html("| Recepción de Unidades");

  // Carga Filtros
  f_LoadFiltroClientes();

  // Cargando listas generales
  f_LoadListaTransportistas(0);
  f_LoadListaConductores();
  f_LoadListaTipoCarga();
  f_LoadListaZonaOrigen();

  // Setea el campo de Placa 2 (Carreta)
  f_TieneCarreta();

  // Carga el detalle de información
  f_LoadResultados();
}

function f_LoadListaTransportistas(_id_cliente) {
  var _html = '<option selected value="">Seleccione una opción...</option>';

  $("#registro_transportista").html("");

  $.post(
    "apis/backend.php",
    { accion: "get_listaclientes", cod_condicion: 2 },
    function (data) {
      if (data.estado == 1) {
        $.each(data.res, function (key, val) {
          _html +=
            '<option value="' +
            val.Id +
            '" ' +
            (_id_cliente > 0 ? (_id_cliente == val.Id ? "selected" : "") : "") +
            ">" +
            val.razon_social.toUpperCase() +
            "</option>";
        });
      } else {
        // alert("No se encontraron resultados.");
      }

      $("#registro_transportista").html(_html);
    },
    "json",
  );
}

function f_LoadListaConductores(_id_conductor) {
  var _html = '<option selected value="">Seleccione una opción...</option>';

  $("#registro_conductor").html("");

  $.post(
    "apis/backend.php",
    { accion: "get_ListaConductores" },
    function (data) {
      if (data.estado == 1) {
        $.each(data.registros, function (key, val) {
          _html +=
            '<option value="' +
            val.Id +
            '" ' +
            (_id_conductor > 0
              ? _id_conductor == val.Id
                ? "selected"
                : ""
              : "") +
            ">" +
            val.nombres.toUpperCase() +
            "</option>";
        });
      } else {
        // alert("No se encontraron resultados.");
      }

      $("#registro_conductor").html(_html);
    },
    "json",
  );
}

function f_ShowTipoCarga() {
  var id_condicion = $("#registro_condicion").val();

  $("#div_tipocarga").show();

  if (id_condicion == 2) {
    $("#div_tipocarga").hide();
  }
}

function f_LoadListaTipoCarga() {
  var _html = '<option selected value="">Seleccione una opción...</option>';

  var id_condicion = $("#registro_condicion").val();

  $("#registro_tipocarga").html("");

  $.post(
    "apis/backend.php",
    { accion: "get_ListaTipoCarga", id_condicion: id_condicion },
    function (data) {
      if (data.estado == 1) {
        $.each(data.registros, function (key, val) {
          _html +=
            '<option value="' + val.Id + '">' + val.descripcion + "</option>";
        });
      } else {
        // alert("No se encontraron resultados.");
      }

      $("#registro_tipocarga").html(_html);
    },
    "json",
  );

  // Seteando la Zona de Origen
  $("#registro_zonaorigen").val("");
  $("#registro_zonaorigen").trigger("change");
}

function f_LoadListaZonaOrigen(_id_zonaorigen) {
  var _html = "<option></option>";

  $("#registro_zonaorigen").html("");

  $.post(
    "apis/backend.php",
    { accion: "get_ListaZonaOrigen" },
    function (data) {
      if (data.estado == 1) {
        $.each(data.registros, function (key, val) {
          _html +=
            '<option value="' +
            val.Id +
            '" ' +
            (_id_zonaorigen > 0
              ? _id_zonaorigen == val.Id
                ? "selected"
                : ""
              : "") +
            ">" +
            val.descripcion.toUpperCase() +
            "</option>";
        });
      } else {
        // alert("No se encontraron resultados.");
      }

      $("#registro_zonaorigen").html(_html);
    },
    "json",
  );
}

function f_GetListaTipoDocumento(_is_juridico) {
  var _html = '<option selected value="">Elija una opción...</option>';

  if (_is_juridico == 0) {
    if ($("#cliente_tipocliente").val() == 2) {
      _is_juridico = 1;
    }
  }

  $.post(
    "apis/backend.php",
    { accion: "get_listatipodocumento" },
    function (data) {
      if (data.estado == 1) {
        $.each(data.res, function (key, val) {
          _html +=
            '<option value="' +
            val.Id +
            '" ' +
            (_is_juridico == 1
              ? val.Id == 2
                ? "selected"
                : ""
              : val.Id == 1
                ? "selected"
                : "") +
            ">" +
            val.descripcion +
            "</option>";
        });

        $("#cliente_tipodocumento").html(_html);
      } else {
        $("#cliente_tipodocumento").html("");
      }
    },
    "json",
  );
}

function f_LoadResultados() {
  var _html = "";

  var fecha_inicio = $("#fecha_inicio").val();
  var fecha_fin = $("#fecha_fin").val();
  var filtro_condicioningreso = $("#filtro_condicioningreso").val();
  var filtro_transportista = $("#filtro_transportista").val();
  var filtro_placa = $("#filtro_placa").val();

  f_LoadingResumen(1);

  $("#tbl_detalle").html("");

  $.post(
    "apis/backend.php",
    {
      accion: "get_ListaIngresoUnidades",
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
      filtro_condicioningreso: filtro_condicioningreso,
      filtro_transportista: filtro_transportista,
      filtro_placa: filtro_placa,
    },
    function (data) {
      if (data.estado == 1) {
        $("#tbl_detalle").html(data.html);
      }

      f_LoadingResumen(0);
    },
    "json",
  );
}

function f_AdminRecepcion() {
  f_OpenModal("modal_addrecepcion");

  $("#hd_idregistro").val(0);
  $("#hd_modograbar").val("N");

  $("#registro_condicion").val("");
  $("#registro_condicion").trigger("change");

  $("#registro_placa1").val("");
  $("#registro_placa2").val("");

  $("#registro_transportista").val("");
  $("#registro_transportista").trigger("change");

  $("#registro_tipovehiculo").val("");
  $("#registro_tipovehiculo").trigger("change");

  $("#registro_conductor").val("");
  $("#registro_conductor").trigger("change");

  $("#registro_tipocarga").val("");
  $("#registro_tipocarga").trigger("change");

  $("#registro_zonaorigen").val("");
  $("#registro_zonaorigen").trigger("change");

  $("#registro_observacion").val("");
  $("#chk_vehiculoparticular").prop("checked", false);

  $("#tbl_acompanantes").html("");
  $("#tbl_imagenes").html("");

  $("#div_recepcion1").css("display", "block");
  $("#div_recepcion2").css("display", "none");
  $("#div_recepcion3").css("display", "none");

  $("#btn_Regresar_2").hide();
  $("#btn_Regresar_3").hide();

  $("#btn_Next_1").show();
  $("#btn_Next_2").hide();
  // $("#btn_ConfirmarAcompanantes").hide();

  f_LoadingGrabarIngreso(0);

  f_ToggleCamposDespachoMineral();
}

function f_AddTransportista() {
  // Definiendo título de ventana e Inicilizando controles de tipo texto
  var tipo = "N";
  var titulo = "Nuevo Transportista";

  // Colocando el título a la pantalla
  $("#modal_addclienteLabel").html(titulo);

  // Identificando el tipo de grabación
  $("#hd_modograbar").val(tipo);

  // Cargando datos
  f_OpenModal("modal_addcliente");

  $("#hd_idcliente").val(0);
  $("#cliente_condicion").val(2);
  $("#cliente_tipocliente").val("");
  $("#cliente_tipodocumento").val("");
  $("#cliente_documento").val("");
  $("#cliente_razonsocial").val("");
  $("#cliente_telefono1").val("");
  $("#cliente_telefono2").val("");
  $("#cliente_correo").val("");
  $("#cliente_direccion").val("");
}

function f_GetInfoCliente(_id_modulo) {
  var is_ruc = 0;
  var documento = "";

  if (_id_modulo == 1) {
    is_ruc = $("#cliente_tipodocumento").val() == 2 ? 1 : 0;
    documento = $("#cliente_documento").val();
  }

  if (_id_modulo == 2) {
    is_ruc = 0;
    documento = $("#conductor_dni").val();
  }

  if (_id_modulo == 3) {
    is_ruc = 0;
    documento = $("#acompanante_dni").val();
  }

  var arr_response = "";

  // Limpiando objetos
  if (_id_modulo == 1) {
    $("#cliente_razonsocial").val("");
    $("#cliente_direccion").val("");
    $("#wt_razonsocial2").hide();
  }

  if (_id_modulo == 2) {
    $("#conductor_nombres").val("");
    $("#wt_conductor").hide();
  }

  if (_id_modulo == 3) {
    $("#acompanante_nombres").val("");
    $("#wt_acompanante").hide();
  }

  // Obteniendo información
  if (documento.length == 8 || documento.length == 11) {
    if (_id_modulo == 1) {
      $("#wt_razonsocial2").show();
    }

    if (_id_modulo == 2) {
      $("#wt_conductor").show();
    }

    if (_id_modulo == 3) {
      $("#wt_acompanante").show();
    }

    $.post(
      "apis/backend.php",
      { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
      function (data) {
        if (data.estado == 1) {
          arr_response = data.res
            .replace(/"/g, "")
            .replace(/{/g, "")
            .replace(/}/g, "")
            .split(",");

          if (is_ruc == 1) {
            $("#cliente_razonsocial").val(arr_response[0].split(":")[1].trim());
            $("#cliente_direccion").val(arr_response[4].split(":")[1].trim());
          } else {
            if (_id_modulo == 1) {
              $("#cliente_razonsocial").val(
                arr_response[0].split(":")[1].trim(),
              );
              $("#cliente_direccion").val("");
            }

            if (_id_modulo == 2) {
              $("#conductor_nombres").val(arr_response[0].split(":")[1].trim());
              $("#conductor_licencia").val($("#conductor_dni").val().trim());
            }

            if (_id_modulo == 3) {
              $("#acompanante_nombres").val(
                arr_response[0].split(":")[1].trim(),
              );
            }
          }
        } else {
          if (_id_modulo == 1) {
            $("#cliente_razonsocial").val("NO ENCONTRADO");
            $("#cliente_direccion").val("");
          }

          if (_id_modulo == 2) {
            $("#conductor_nombres").val("NO ENCONTRADO");
          }

          if (_id_modulo == 3) {
            $("#acompanante_nombres").val("NO ENCONTRADO");
          }
        }

        if (_id_modulo == 1) {
          $("#wt_razonsocial2").hide();
        }

        if (_id_modulo == 2) {
          $("#wt_conductor").hide();
        }

        if (_id_modulo == 3) {
          $("#wt_acompanante").hide();
        }
      },
      "json",
    );
  }
}

function f_AddConductor() {
  // Definiendo título de ventana e Inicilizando controles de tipo texto
  var tipo = "N";
  var titulo = "Nuevo Conductor";

  // Colocando el título a la pantalla
  $("#modal_addconductorLabel").html(titulo);

  // Identificando el tipo de grabación

  // Cargando datos
  f_OpenModal("modal_addconductor");

  $("#conductor_tipodocumento").val("");
  $("#conductor_dni").val("");
  $("#conductor_licencia").val("");
  $("#conductor_nombres").val("");
}

function f_AddZonaOrigen() {
  // Definiendo título de ventana e Inicilizando controles de tipo texto
  var tipo = "N";
  var titulo = "Nueva Zona de Origen";

  // Colocando el título a la pantalla
  $("#modal_addzonaorigenLabel").html(titulo);

  // Identificando el tipo de grabación

  // Cargando datos
  f_OpenModal("modal_addzonaorigen");

  $("#zona_origen").val("");
}

function f_GrabarRecepcion_Next(_id_div) {
  if (_id_div == 1) {
    // Recupera variables
    var registro_condicion = $("#registro_condicion").val();
    var registro_placa =
      f_CleanInjection($("#registro_placa1").val()) +
      "-" +
      f_CleanInjection($("#registro_placa2").val());
    var registro_transportista = $("#registro_transportista").val();
    var registro_tipovehiculo = $("#registro_tipovehiculo").val().split("|")[0];
    var tiene_carreta = $("#registro_tipovehiculo").val().split("|")[1];
    var registro_placa2 =
      f_CleanInjection($("#registro_placa1_2").val()) +
      "-" +
      f_CleanInjection($("#registro_placa2_2").val());
    var registro_conductor = $("#registro_conductor").val();
    var registro_tipocarga = $("#registro_tipocarga").val();
    var registro_zonaorigen = $("#registro_zonaorigen").val();
    var registro_observacion = f_CleanInjection(
      $("#registro_observacion").val(),
    );

    var id_placadespacho = $("#registro_placasdespacho").val();

    // Validando datos
    if (registro_condicion == null) {
      alert("Debe seleccionar la Condición de Ingreso.");

      return;
    }
    if (registro_condicion.length == 0) {
      alert("Debe seleccionar la Condición de Ingreso.");

      return;
    }

    if (registro_condicion == 2) {
      if (id_placadespacho == null) {
        alert("Debe seleccionar una Placa de la lista.");

        return;
      }
      if (id_placadespacho.length == 0) {
        alert("Debe seleccionar una Placa de la lista.");

        return;
      }

      if (id_placadespacho == 9.9) {
        if ($("#registro_placa1").val() == null) {
          alert("La Placa 1 ingresada no es válida.");

          return;
        }
        if ($("#registro_placa1").val().length == 0) {
          alert("La Placa 1 ingresada no es válida.");

          return;
        }
        if ($("#registro_placa2").val() == null) {
          alert("La Placa 1 ingresada no es válida.");

          return;
        }
        if ($("#registro_placa2").val().length == 0) {
          alert("La Placa 1 ingresada no es válida.");

          return;
        }
      }
    }

    if ($("#registro_placa1").val() == null) {
      alert("La Placa 1 ingresada no es válida.");

      return;
    }
    if ($("#registro_placa1").val().length == 0) {
      alert("La Placa 1 ingresada no es válida.");

      return;
    }
    if ($("#registro_placa2").val() == null) {
      alert("La Placa 1 ingresada no es válida.");

      return;
    }
    if ($("#registro_placa2").val().length == 0) {
      alert("La Placa 1 ingresada no es válida.");

      return;
    }

    if (registro_transportista == null) {
      alert("Debe seleccionar el Transportista.");

      return;
    }
    if (registro_transportista.length == 0) {
      alert("Debe seleccionar el Transportista.");

      return;
    }

    if (registro_tipovehiculo == null) {
      alert("Debe seleccionar el Tipo de Vehículo.");

      return;
    }
    if (registro_tipovehiculo.length == 0) {
      alert("Debe seleccionar el Tipo de Vehículo.");

      return;
    }

    if (tiene_carreta == 1) {
      if ($("#registro_placa1_2").val() == null) {
        alert("La Placa 2 ingresada no es válida.");

        return;
      }
      if ($("#registro_placa1_2").val().length == 0) {
        alert("La Placa 2 ingresada no es válida.");

        return;
      }
      if ($("#registro_placa2_2").val() == null) {
        alert("La Placa 2 ingresada no es válida.");

        return;
      }
      if ($("#registro_placa2_2").val().length == 0) {
        alert("La Placa 2 ingresada no es válida.");

        return;
      }
    } else {
      registro_placa2 = "";
    }

    if (registro_conductor == null) {
      alert("Debe seleccionar el Conductor.");

      return;
    }
    if (registro_conductor.length == 0) {
      alert("Debe seleccionar el Conductor.");

      return;
    }

    if (registro_condicion != 2) {
      if (registro_tipocarga == null) {
        alert("Debe seleccionar el Tipo de Carga.");

        return;
      }
      if (registro_tipocarga.length == 0) {
        alert("Debe seleccionar el Tipo de Carga.");

        return;
      }
    }

    // Obtiene total de acompañantes
    var table = document.getElementById("tbl_acompanantes");
    var a = table.rows.length;

    // Setea tabla de Acompañantes
    if (a == 0) {
      var _html = $("#tbl_acompanantes").html();

      _html +=
        '<td colspan="6" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      _html +=
        '  <button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddAcompanante();">';
      _html += "    <b>+ Agregar Acompañante</b>";
      _html += "  </button>";
      _html += "</td>";

      document.getElementById("tbl_acompanantes").insertRow(-1).innerHTML =
        _html;
    }

    // Continuar al siguiente grupo de datos
    $("#div_recepcion1").hide(500);
    $("#div_recepcion2").show(500);

    $("#btn_Regresar_2").show();
    $("#btn_Regresar_3").hide();

    $("#btn_Next_1").hide();
    $("#btn_Next_2").show();
    // $("#btn_ConfirmarAcompanantes").hide();
    // $("#btn_Next_2").hide();
    // $("#btn_ConfirmarAcompanantes").show();
  }

  if (_id_div == 2) {
    // Continuar al siguiente grupo de datos
    $("#div_recepcion2").hide(500);
    $("#div_recepcion3").show(500);

    $("#btn_Regresar_2").hide();
    $("#btn_Regresar_3").show();

    $("#btn_Next_2").hide();
    // $("#btn_ConfirmarAcompanantes").show();

    // Obtiene total de Imágenes
    var table = document.getElementById("tbl_imagenes");
    var a = table.rows.length;

    // Obteniendo Id temporal autogenerado
    var _time = new Date();
    _time =
      _time.getHours().toString().padStart(2, "0") +
      ":" +
      _time.getMinutes().toString().padStart(2, "0");

    var tmp_Id = "tmp_imagenes-<?php echo $g_date ?>-" + _time;

    // Setea tabla de Imágenes adicionales
    if (a == 0) {
      var _html = $("#tbl_imagenes").html();

      // Verifica si hay Placa 2
      var is_placa2 = 0;
      var is_placa2_x = 0;

      if ($("#div_placa2").css("display") == "flex") {
        is_placa2 = 1;
        is_placa2_x = is_placa2;
      }

      // Cargando las 3 imágenes por defecto
      var i = 1;
      var descripcion = "";

      while (i <= 4) {
        // Definiendo descripción
        if (i == 1) {
          descripcion = "BREVETE";
        }

        if (i == 2) {
          descripcion = "PLACA 1";
        }

        if (i == 3) {
          descripcion = "TOLVA";
        }

        if (i == 4) {
          descripcion = "TARJETA CIRCULACIÓN";
        }

        // Seteando html
        _html += "<tr>";
        _html +=
          '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
        // _html += '   <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;">X</label>';
        _html += "  </td>";

        _html +=
          '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
        _html +=
          "    " +
          (i == 4 && is_placa2 == 1 ? 5 : i == 3 && is_placa2 == 1 ? 4 : i);
        _html +=
          '    <input id="tmp_imagenes_id_' +
          (i == 4 && is_placa2 == 1 ? 5 : i == 3 && is_placa2 == 1 ? 4 : i) +
          '" type="hidden" value="' +
          tmp_Id +
          "_" +
          (i == 4 && is_placa2 == 1 ? 5 : i == 3 && is_placa2 == 1 ? 4 : i) +
          '">';
        _html += "  </td>";

        _html +=
          '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px; font-weight: bold;">';
        _html += "    " + descripcion;
        _html += "  </td>";

        _html +=
          '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
        _html +=
          '    <img class="imagen" src="" alt="" style="width: 80px; display: none;" id="img_imagenes_' +
          (i == 4 && is_placa2 == 1 ? 5 : i == 3 && is_placa2 == 1 ? 4 : i) +
          '" onclick="f_ShowImagenes(this.src, 1, ' +
          "'" +
          descripcion +
          "'" +
          ');">';
        _html += "  </td>";

        _html +=
          '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
        _html +=
          '    <img src="<?php echo $img_camara ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImagenes(' +
          (i == 4 && is_placa2 == 1 ? 5 : i == 3 && is_placa2 == 1 ? 4 : i) +
          ');">';
        _html += "  </td>";
        _html += "</tr>";

        // Si tiene Placa 2
        if (i == 2 && is_placa2_x == 1) {
          _html += "<tr>";
          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
          // _html += '   <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;">X</label>';
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
          _html += "    " + (i + 1);
          _html +=
            '    <input id="tmp_imagenes_id_' +
            (i + 1) +
            '" type="hidden" value="' +
            tmp_Id +
            "_" +
            (i + 1) +
            '">';
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px; font-weight: bold;">';
          _html += "    PLACA 2";
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
          _html +=
            '    <img class="imagen" src="" alt="" style="width: 80px; display: none;" id="img_imagenes_' +
            (i + 1) +
            '" onclick="f_ShowImagenes(this.src, 1, ' +
            "'" +
            "PLACA 2" +
            "'" +
            ');">';
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
          _html +=
            '    <img src="<?php echo $img_camara ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImagenes(' +
            (i + 1) +
            ');">';
          _html += "  </td>";
          _html += "</tr>";

          is_placa2_x = 0;
        }

        i++;
      }

      // Agregando fila para imágenes adicionales
      _html += "<tr>";
      _html +=
        '  <td colspan="5" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
      _html +=
        '    <button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px;" onclick="f_AddImagenAdicional();">';
      _html += "      <b>+ Agregar Imagen</b>";
      _html += "    </button>";
      _html += "  </td>";
      _html += "</tr>";

      // Agregando html
      $("#tbl_imagenes").html(_html);
    } else {
      // Verifica por si la placa 2 está activa
      if ($("#div_placa2").css("display") == "none") {
        if ($("#tbl_imagenes tr:eq(2) td:eq(2)").html().trim() == "PLACA 2") {
          $("#tbl_imagenes tr:eq(2)").remove();
        }
      } else {
        if ($("#tbl_imagenes tr:eq(2) td:eq(2)").html().trim() != "PLACA 2") {
          var _html = "<tr>";
          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
          // _html += '   <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;">X</label>';
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
          _html += "    3";
          _html +=
            '    <input id="tmp_imagenes_id_3" type="hidden" value="' +
            tmp_Id +
            "_3" +
            '">';
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px; font-weight: bold;">';
          _html += "    PLACA 2";
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
          _html +=
            '    <img class="imagen" src="" alt="" style="width: 80px; display: none;" id="img_imagenes_3" onclick="f_ShowImagenes(this.src, 1, ' +
            "'PLACA 2'" +
            ');">';
          _html += "  </td>";

          _html +=
            '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
          _html +=
            '    <img src="<?php echo $img_camara ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImagenes(3);">';
          _html += "  </td>";
          _html += "</tr>";

          $("#tbl_imagenes tr:eq(1)").after(_html);
        }
      }

      // Obtiene total de Imágenes
      var table = document.getElementById("tbl_imagenes");
      var _rows = table.rows.length - 1;

      // Reinicia los contadores
      var x = 1;

      $("#tbl_imagenes tr").each(function () {
        if (x <= _rows) {
          $(this).find("td").eq(1).html(x);
        }

        x++;
      });
    }
  }
}

function f_RegresarRecepcion(_id_div) {
  if (_id_div == 2) {
    $("#div_recepcion1").show(500);
    $("#div_recepcion2").hide(500);

    $("#btn_Regresar_2").hide();
    $("#btn_Regresar_3").hide();

    $("#btn_Next_1").show();
    $("#btn_Next_2").hide();
    // $("#btn_ConfirmarAcompanantes").hide();
  }

  if (_id_div == 3) {
    $("#div_recepcion2").show(500);
    $("#div_recepcion3").hide(500);

    $("#btn_Regresar_2").show();
    $("#btn_Regresar_3").hide();

    $("#btn_Next_2").show();
    // $("#btn_ConfirmarAcompanantes").hide();
  }
}

function f_AddAcompanante() {
  // Cargando datos
  f_OpenModal("modal_addacompanante");

  $("#acompanante_dni").val("");
  $("#acompanante_nombres").val("");
}

function f_AddImagenAdicional() {
  // Cargando datos
  f_OpenModal("modal_addimagenadicional");

  $("#imagenadicional_descripcion").val("");
}

function f_GrabarAcompanante() {
  // Recupera variables
  var acompanante_dni = f_CleanInjection($("#acompanante_dni").val().trim());
  var acompanante_nombres = f_CleanInjection($("#acompanante_nombres").val());

  // Validando datos
  if (acompanante_dni == null) {
    alert("Debe ingresar el N° de DNI del Acompañante.");

    return;
  }
  if (acompanante_dni.length == 0) {
    alert("Debe ingresar el N° de DNI del Acompañante.");

    return;
  }

  if (acompanante_nombres == null) {
    alert("Debe ingresar los Nombres y Apellidos del Acompañante.");

    return;
  }
  if (acompanante_nombres.length == 0) {
    alert("Debe ingresar los Nombres y Apellidos del Acompañante.");

    return;
  }

  // Eliminando la ultima fila (Botón de agregar acompañantes)
  var table = document.getElementById("tbl_acompanantes");
  var a = table.rows.length;

  table.deleteRow(a - 1);

  // Obteniendo Id temporal autogenerado
  var _time = new Date();
  _time =
    _time.getHours().toString().padStart(2, "0") +
    ":" +
    _time.getMinutes().toString().padStart(2, "0");

  var tmp_Id = "tmp-<?php echo $g_date ?>-" + _time;

  // Agregar nuevo acompañante
  var _html = "";

  _html +=
    '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html += "  " + a;
  _html +=
    '  <input id="tmp_id_' +
    a +
    '" type="hidden" value="' +
    tmp_Id +
    "_" +
    a +
    '">';
  _html += "</td>";

  _html +=
    '<td class="del_tr" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html +=
    '  <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;">X</label>';
  _html += "</td>";

  _html +=
    '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html += "  " + acompanante_dni;
  _html += "</td>";

  _html +=
    '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html += "  " + acompanante_nombres.toUpperCase();
  _html += "</td>";

  _html +=
    '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html +=
    '  <img class="imagen" src="" alt="" style="width: 80px; display: none;cursor: pointer" id="img_acompanante_' +
    a +
    '" onclick="f_ShowDocumentoAcompanante(this.src, ' +
    "'" +
    acompanante_nombres.toUpperCase() +
    "', 1" +
    ');">';
  _html += "</td>";

  _html +=
    '<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html +=
    '  <img src="<?php echo $img_camara ?>" style="width: 30px; cursor: pointer;" onclick="f_AddAcompanante_Imagen(' +
    a +
    ');">';
  _html += "</td>";

  document.getElementById("tbl_acompanantes").insertRow(-1).innerHTML = _html;

  // Agregar fila para Nuevo Acompañante
  _html =
    '<td colspan="6" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
  _html +=
    '  <button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px; margin-top: -5px;" onclick="f_AddAcompanante();">';
  _html += "    <b>+ Agregar Acompañante</b>";
  _html += "  </button>";
  _html += "</td>";

  document.getElementById("tbl_acompanantes").insertRow(-1).innerHTML = _html;

  // Cerrando Modal
  f_cerrarModal("modal_addacompanante");
}

function f_GrabarImagenAdicional() {
  // Recupera variables
  var imagenadicional_descripcion = f_CleanInjection(
    $("#imagenadicional_descripcion").val().trim(),
  );

  // Validando datos
  if (imagenadicional_descripcion == null) {
    alert("Debe ingresar la descripción de la imagen.");

    return;
  }
  if (imagenadicional_descripcion.length == 0) {
    alert("Debe ingresar la descripción de la imagen.");

    return;
  }

  // Eliminando la ultima fila (Botón de agregar acompañantes)
  var table = document.getElementById("tbl_imagenes");
  var i = table.rows.length;

  table.deleteRow(i - 1);

  // Obteniendo Id temporal autogenerado
  var _time = new Date();
  _time =
    _time.getHours().toString().padStart(2, "0") +
    ":" +
    _time.getMinutes().toString().padStart(2, "0");

  var tmp_Id = "tmp-<?php echo $g_date ?>-" + _time;

  // Agregar nuevo acompañante
  var _html = "";

  _html +=
    '  <td class="del_tr2" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
  _html +=
    '    <label style="border: solid; border-width: 1px; border-color: #D9D9D9; border-radius: 7px; padding-left: 6px; padding-right: 6px; padding-bottom: 1px; background-color: #FF5F5D; color: #ffffff; font-weight: bold; cursor: pointer;">X</label>';
  _html += "  </td>";

  _html +=
    '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px;">';
  _html += "    " + i;
  _html +=
    '    <input id="tmp_imagenes_id_' +
    i +
    '" type="hidden" value="' +
    tmp_Id +
    "_" +
    i +
    '">';
  _html += "  </td>";

  _html +=
    '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px; width: 30px; font-weight: bold;">';
  _html += "    " + imagenadicional_descripcion.toUpperCase();
  _html += "  </td>";

  _html +=
    '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html +=
    '    <img class="imagen" src="" alt="" style="width: 80px; display: none;" id="img_imagenes_' +
    i +
    '" onclick="f_ShowImagenes(this.src, 1, ' +
    "'" +
    imagenadicional_descripcion +
    "'" +
    ');">';
  _html += "  </td>";

  _html +=
    '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-size: 12px;">';
  _html +=
    '    <img src="<?php echo $img_camara ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImagenes(' +
    i +
    ');">';
  _html += "  </td>";

  document.getElementById("tbl_imagenes").insertRow(-1).innerHTML = _html;

  // Agregar fila para Nuevo Acompañante
  _html =
    ' <td colspan="5" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
  _html +=
    '    <button class="btn btn-primary" type="button" style="color: #ffffff; font-size: 14px;" onclick="f_AddImagenAdicional();">';
  _html += "      <b>+ Agregar Imagen</b>";
  _html += "    </button>";
  _html += "  </td>";

  document.getElementById("tbl_imagenes").insertRow(-1).innerHTML = _html;

  // Cerrando Modal
  f_cerrarModal("modal_addimagenadicional");
}

$(document).on("click", ".del_tr", function (event) {
  event.preventDefault();

  $(this).closest("tr").remove();

  // Obtiene total de Acompañantes
  var table = document.getElementById("tbl_acompanantes");
  var _rows = table.rows.length - 1;

  // Reinicia los contadores
  var x = 1;

  $("#tbl_acompanantes tr").each(function () {
    if (x <= _rows) {
      $(this).find("td").eq(0).html(x);
    }

    x++;
  });
});

$(document).on("click", ".del_tr2", function (event) {
  event.preventDefault();

  $(this).closest("tr").remove();

  // Obtiene total de Imágenes
  var table = document.getElementById("tbl_imagenes");
  var _rows = table.rows.length - 1;

  // Reinicia los contadores
  var x = 1;

  $("#tbl_imagenes tr").each(function () {
    if (x <= _rows) {
      $(this).find("td").eq(1).html(x);
    }

    x++;
  });
});

function f_AddAcompanante_Imagen(_id_row) {
  var input = document.createElement("input");
  input.type = "file";
  input.accept = "image/*";
  input.onchange = function (event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      var imagen = document.getElementById("img_acompanante_" + _id_row);
      imagen.src = e.target.result;
    };
    reader.readAsDataURL(file);
  };
  input.click();

  $("#img_acompanante_" + _id_row).show();
}

function f_AddImagenes(_id_row) {
  var input = document.createElement("input");
  input.type = "file";
  input.accept = "image/*";
  input.onchange = function (event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      var imagen = document.getElementById("img_imagenes_" + _id_row);
      imagen.src = e.target.result;
    };
    reader.readAsDataURL(file);
  };
  input.click();

  $("#img_imagenes_" + _id_row).show();
}

function f_ShowDocumentoAcompanante(_id_img, _nombres, _is_local) {
  // Colocando el título a la pantalla
  $("#modal_showdocumentoacompananteLabel").html(_nombres);

  // Limpiando objeto img
  $("#img_documentoacompanante").attr("src", "");

  // Obtiene el SRC si lo tuviera
  if (_is_local == 1) {
    // Cargando Imagen
    var modalImg = document.getElementById("img_documentoacompanante");
    modalImg.src = _id_img;
  } else {
    var _src = "";

    f_LoadingDocumentoAcompanante(1);

    $.post(
      "apis/backend.php",
      { accion: "get_ControlIngreso_AcompanantesSRC", id_img: _id_img },
      function (data) {
        if (data.estado == 1) {
          _src = data.src;
          _src_url = data.src_url;
        }

        // Cargando Imagen
        var modalImg = document.getElementById("img_documentoacompanante");
        modalImg.src = "files/recepcion/" + _src_url;

        f_LoadingDocumentoAcompanante(0);
      },
    );
  }

  // Abre modal
  f_OpenModal("modal_showdocumentoacompanante");
}

function f_ShowImagenes(_id_img, _is_local, _item) {
  // Colocando el título a la pantalla
  $("#modal_showimagenesLabel").html("Imagen: " + _item);

  // Limpiando objeto img
  $("#img_imagenes").attr("src", "");

  // Cargando datos
  if (_is_local == 1) {
    var modalImg = document.getElementById("img_imagenes");
    modalImg.src = _id_img;
  } else {
    var _src = "";

    f_LoadingImagenes(1);

    $.post(
      "apis/backend.php",
      { accion: "get_ControlIngreso_ImagenesSRC", id_img: _id_img },
      function (data) {
        if (data.estado == 1) {
          _src = data.src;
        }

        // Cargando Imagen
        var modalImg = document.getElementById("img_imagenes");
        modalImg.src = _src;

        f_LoadingImagenes(0);
      },
    );
  }

  // Abre modal
  f_OpenModal("modal_showimagenes");
}

function f_ShowImagenesCarousel(_id_controlingreso) {
  // Colocando el título a la pantalla
  $("#modal_showimagenescarouselLabel").html("Imágenes Adicionales");

  $("#div_imagenes_adicionales").html("");

  f_LoadingImagenes(1);

  // Cargando datos
  $.post(
    "apis/backend.php",
    {
      accion: "get_ControlIngreso_ImagenesURL",
      id_controlingreso: _id_controlingreso,
    },
    function (data) {
      if (data.estado == 1) {
        $("#div_imagenes_adicionales").html(data.html);
      }
      f_LoadingImagenes(0);
    },
  );

  // Abre modal
  f_OpenModal("modal_showimagenescarousel");
}

function f_RegistroSalida(_id_registro, _id_distribucion) {
  $("#hd_idregistrosalida").val(_id_registro);
  $("#hd_iddistribucion_salida").val(_id_distribucion || 0);

  $("#salida_estado").val("");
  $("#salida_observacion").val("");

  // Cargando datos de acompañantes
  f_LoadingSalidaAcompanantes(1);

  $("#tbl_acompanantes_salida").html("");

  $.post(
    "apis/backend.php",
    { accion: "get_ListaAcompanantes", id_registro: _id_registro },
    function (data) {
      if (data.estado == 1) {
        $("#tbl_acompanantes_salida").html(data.html);
      }

      f_LoadingSalidaAcompanantes(0);
    },
    "json",
  );

  f_OpenModal("modal_registrosalida");
}

function f_TieneCarreta() {
  $("#registro_placa1_2").val("");
  $("#registro_placa2_2").val("");

  var valTipoVehiculo = $("#registro_tipovehiculo").val();
  if (!valTipoVehiculo || valTipoVehiculo.trim().length == 0) {
    $("#div_placa2").hide();
  } else {
    var tiene_carreta = $("#registro_tipovehiculo").val().split("|")[1];

    if (tiene_carreta == 0) {
      $("#div_placa2").hide();
    } else {
      $("#div_placa2").show();
    }
  }
}

function f_ShowInformacion(_id_registro) {
  f_OpenModal("modal_showinfo");

  f_LoadingShowInfo(1);

  // Limpiando objetos
  $("#info_ingreso").val("");
  $("#info_condicion").val("");
  $("#info_placa1").val("");
  $("#info_transportista_documento").val("");
  $("#info_transportista").val("");
  $("#info_tipovehiculo").val("");
  $("#info_placa2").val("");
  $("#info_conductor").val("");
  $("#info_tipocarga").val("");
  $("#info_zonaorigen").val("");
  $("#info_zonaorigen").val("");
  $("#info_observacion").val("");
  $("#chk_tienevehiculoparticular").prop("checked", false);

  $("#tbl_infosalidas").html("");
  $("#tbl_infoacompanantes").html("");
  $("#tbl_infoimagenes").html("");

  // Cargando datos
  $.post(
    "apis/backend.php",
    { accion: "get_ListaIngresoUnidades_Info", id_registro: _id_registro },
    function (data) {
      if (data.estado == 1) {
        $.each(data.res, function (key, val) {
          // Título de Ventana
          $("#modal_showinfoLabel").html(val.placa);
          // Llenando los datos principales
          $("#info_ingreso").val(
            val.dFechaIngreso + " " + val.dhoraingresoPlanta,
          );
          $("#info_condicion").val(val.CLIENTE_CONDICION);
          $("#info_placa1").val(val.placa);
          $("#info_transportista_documento").val(val.documento);
          $("#info_transportista").val(val.TRANSPORTISTA);
          $("#info_tipovehiculo").val(val.TIPO_VEHICULO);

          if (val.tiene_carreta == 1) {
            $("#div_placa2_info").show();

            $("#info_placa2").val(val.placa2);
          } else {
            $("#div_placa2_info").hide();
          }

          $("#info_conductor").val(val.CONDUCTOR);
          $("#info_tipocarga").val(val.TIPO_CARGA);

          // if (val.id_tipoingresounidad == 1){
          //  $("#div_zonaorigen_info").show();

          //  $("#info_zonaorigen").val(val.ZONA_ORIGEN);
          // }
          // else{
          //  $("#div_zonaorigen_info").hide();
          // }

          $("#info_observacion").val(val.cNotas);
          $("#chk_tienevehiculoparticular").prop(
            "checked",
            val.tiene_vehiculoparticular == 1 ? true : false,
          );
        });

        // Llenando la Salida
        $("#tbl_infosalidas").html(data.html_salida);

        // Llenando Acompañantes
        $("#tbl_infoacompanantes").html(data.html_acompanantes);

        // Llenando Imágenes
        $("#tbl_infoimagenes").html(data.html_imagenes);
      }

      f_LoadingShowInfo(0);
    },
    "json",
  );
}

function f_ExportToExcel() {
  // Obteniendo filtros
  var fecha_inicio = $("#fecha_inicio").val();
  var fecha_fin = $("#fecha_fin").val();
  var filtro_condicioningreso = $("#filtro_condicioningreso").val();
  var filtro_transportista = $("#filtro_transportista").val();
  var filtro_placa = $("#filtro_placa").val();

  window.location.href =
    "export_to_excel/recepcion_unidades.php?fecha_inicio=" +
    fecha_inicio +
    "&fecha_fin=" +
    fecha_fin +
    "&filtro_condicioningreso=" +
    filtro_condicioningreso +
    "&filtro_transportista=" +
    filtro_transportista +
    "&filtro_placa=" +
    filtro_placa;
}

function f_LoadFiltroClientes() {
  // Obteniendo filtros
  var fecha_inicio = $("#fecha_inicio").val();
  var fecha_fin = $("#fecha_fin").val();

  // Cargando clientes
  $("#filtro_transportista").html("");

  $.post(
    "apis/backend.php",
    {
      accion: "get_ClientesIngresoUnidadesxFechas",
      fecha_inicio: fecha_inicio,
      fecha_fin: fecha_fin,
    },
    function (data) {
      if (data.estado == 1) {
        $("#filtro_transportista").html(data.html);
      }
    },
    "json",
  );
}

function f_ShowListaPlacasDespacho(_id_placa) {
  // Valida la Condición
  var id_condicion = $("#registro_condicion").val();

  if (id_condicion != 2) {
    $("#div_FechasDespacho").hide();
    $("#div_PlacasDespacho").hide();
    $("#div_PlacaIngreso").show();

    return;
  } else {
    $("#div_FechasDespacho").show();
    $("#div_PlacasDespacho").show();
    $("#div_PlacaIngreso").hide();
  }

  var fecha_despacho = $("#registro_fechadespacho").val();

  // Cargando lista de Placas para Despacho
  var _html = "<option></option>";

  $("#registro_placasdespacho").html("");

  $.post(
    "apis/backend.php",
    { accion: "get_Placas_Distribucion", fecha_estimada: fecha_despacho },
    function (data) {
      if (data.estado == 1) {
        $.each(data.registros, function (key, val) {
          const selected =
            _id_placa > 0 && _id_placa == val.id_distribucion ? "selected" : "";
          const placa1 = val.placa1 ? val.placa1.toUpperCase() : "";
          const placa2 = val.placa2 ? ` / ${val.placa2.toUpperCase()}` : "";
          const correlativo = val.correlativo
            ? ` - Despacho: ${val.correlativo}`
            : "";

          _html += `<option value="${val.id_distribucion}" 
            data-placa1="${val.placa1}" 
            data-placa2="${val.placa2}" 
            data-id_transportista="${val.id_empresa_transporte}" 
            data-id_tipovehiculo="${val.id_tipovehiculo}" 
            ${selected}>${placa1}</option>`;
        });
      }

      $("#registro_placasdespacho").html(_html);
    },
    "json",
  );
}

function f_ShowPlacaNoExiste() {
  var placa_despacho = $("#registro_placasdespacho").val();
  var placa_despacho_des = $("#registro_placasdespacho option:selected").text();

  $("#div_PlacaIngreso").hide();
  $("#lbl_PlacaIngreso").html("Placa 1:");

  $("#registro_placa1").val("");
  $("#registro_placa2").val("");
  $("#registro_placa1_2").val("");
  $("#registro_placa2_2").val("");

  if (placa_despacho == 9.9) {
    $("#div_PlacaIngreso").show();

    $("#lbl_PlacaIngreso").html("");
  } else if (placa_despacho != null && placa_despacho != "") {
    // Setea la Placa internamente en el campo de Placa de Ingreso
    var opt = $("#registro_placasdespacho option:selected");

    var placa1_parts = (opt.data("placa1") || "").split("-");
    $("#registro_placa1").val(placa1_parts[0] || "");
    $("#registro_placa2").val(placa1_parts[1] || "");

    var placa2_parts = (opt.data("placa2") || "").split("-");
    $("#registro_placa1_2").val(placa2_parts[0] || "");
    $("#registro_placa2_2").val(placa2_parts[1] || "");

    if (opt.data("id_transportista")) {
      $("#registro_transportista")
        .val(opt.data("id_transportista"))
        .trigger("change");
    }
    if (opt.data("id_tipovehiculo")) {
      var idTipoVehiculo = opt.data("id_tipovehiculo");
      // Buscar la opción cuyo valor empiece con "idTipoVehiculo|"
      $("#registro_tipovehiculo option").each(function () {
        if (
          $(this)
            .val()
            .startsWith(idTipoVehiculo + "|")
        ) {
          $("#registro_tipovehiculo").val($(this).val()).trigger("change");
          return false; // Break loop
        }
      });
    }

    // Lock the fields since they are auto-populated from distribution
    $(
      "#registro_placa1, #registro_placa2, #registro_placa1_2, #registro_placa2_2",
    ).prop("readonly", true);
    $("#registro_transportista, #registro_tipovehiculo").prop("disabled", true);
  } else {
    // Unlock fields if no distribution is selected
    $(
      "#registro_placa1, #registro_placa2, #registro_placa1_2, #registro_placa2_2",
    ).prop("readonly", false);
    $("#registro_transportista, #registro_tipovehiculo").prop(
      "disabled",
      false,
    );
  }

  f_ToggleCamposDespachoMineral();
}

function f_KeyUpPlaca() {
  var placa1 = $("#registro_placa1").val().trim();
  var placa2 = $("#registro_placa2").val().trim();

  if (placa1.length == 3) {
    document.getElementById("registro_placa2").focus();
  }

  // Obtiene los datos de Placa
  if (placa1.length == 3 && placa2.length == 3) {
    $.post(
      "apis/backend.php",
      { accion: "get_InfoUnidad", placa: placa1 + "-" + placa2 },
      function (data) {
        if (data.estado == 1) {
          $("#registro_transportista").val(data.id_transportista);
          $("#registro_tipovehiculo").val(data.id_tipovehiculo);
          $("#registro_conductor").val(data.id_conductor);
        } else {
          $("#registro_transportista").val("");
          $("#registro_tipovehiculo").val("");
          $("#registro_conductor").val("");
        }

        $("#registro_transportista").trigger("change");
        $("#registro_tipovehiculo").trigger("change");
        $("#registro_conductor").trigger("change");
      },
      "json",
    );
  }
}

function f_KeyUpPlaca2() {
  var placa2 = $("#registro_placa1_2").val();

  if (placa2.trim().length == 3) {
    document.getElementById("registro_placa2_2").focus();
  }
}

$("#modal_addrecepcion").on("shown.bs.modal", function () {
  $("#registro_placa1").focus();
});

$("#modal_addcliente").on("shown.bs.modal", function () {
  $("#cliente_tipocliente").focus();
});

$("#modal_addconductor").on("shown.bs.modal", function () {
  $("#conductor_dni").focus();
});

$("#modal_addzonaorigen").on("shown.bs.modal", function () {
  $("#zona_origen").focus();
});

function f_LoadingGrabarIngreso(_is_show) {
  if (_is_show == 1) {
    $("#wt_grabarregistro").show();

    $(".wt_grabarregistro_button").prop("disabled", true);
  } else {
    $("#wt_grabarregistro").hide();

    $(".wt_grabarregistro_button").prop("disabled", false);
  }
}

function f_LoadingRegistroSalida(_is_show) {
  if (_is_show == 1) {
    $("#wt_grabarsalida").show();

    $(".wt_grabarsalida_button").prop("disabled", true);
  } else {
    $("#wt_grabarsalida").hide();

    $(".wt_grabarsalida_button").prop("disabled", false);
  }
}

function f_LoadingSalidaAcompanantes(_is_show) {
  if (_is_show == 1) {
    $("#wt_loadingacompanantes").show();
  } else {
    $("#wt_loadingacompanantes").hide();
  }
}

function f_LoadingResumen(_is_show) {
  if (_is_show == 1) {
    $("#wt_resumen").show();
  } else {
    $("#wt_resumen").hide();
  }
}

function f_LoadingDocumentoAcompanante(_is_show) {
  if (_is_show == 1) {
    $("#wt_documentoacompanante").show();
  } else {
    $("#wt_documentoacompanante").hide();
  }
}

function f_LoadingImagenes(_is_show) {
  if (_is_show == 1) {
    $("#wt_imagenes").show();
  } else {
    $("#wt_imagenes").hide();
  }
}

function f_LoadingShowInfo(_is_show) {
  if (_is_show == 1) {
    $("#wt_info").show();
  } else {
    $("#wt_info").hide();
  }
}

function f_GrabarRecepcion_Confirmar() {
  // Recupera variables
  var registro_condicion = $("#registro_condicion").val();

  var registro_placa =
    f_CleanInjection($("#registro_placa1").val().trim()) +
    "-" +
    f_CleanInjection($("#registro_placa2").val().trim());
  registro_placa = registro_placa.toUpperCase();

  var registro_transportista = $("#registro_transportista").val();
  var registro_tipovehiculo = $("#registro_tipovehiculo").val().split("|")[0];
  var tiene_carreta = $("#registro_tipovehiculo").val().split("|")[1];

  var registro_placa2 =
    f_CleanInjection($("#registro_placa1_2").val().trim()) +
    "-" +
    f_CleanInjection($("#registro_placa2_2").val().trim());
  registro_placa2 = registro_placa2.toUpperCase();

  var registro_conductor = $("#registro_conductor").val();
  var registro_tipocarga = $("#registro_tipocarga").val();
  var registro_zonaorigen = $("#registro_zonaorigen").val();
  var registro_observacion = f_CleanInjection(
    $("#registro_observacion").val().trim().toUpperCase(),
  );
  var vehiculo_particular = $("#chk_vehiculoparticular").prop("checked")
    ? 1
    : 0;

  var id_placadespacho = $("#registro_placasdespacho").val();

  // Validando datos
  if (registro_condicion == null) {
    alert("Debe seleccionar la Condición de Ingreso.");

    return;
  }
  if (registro_condicion.length == 0) {
    alert("Debe seleccionar la Condición de Ingreso.");

    return;
  }

  if (registro_condicion == 2) {
    if (id_placadespacho == null) {
      alert("Debe seleccionar una Placa de la lista.");

      return;
    }
    if (id_placadespacho.length == 0) {
      alert("Debe seleccionar una Placa de la lista.");

      return;
    }

    if (id_placadespacho == 9.9) {
      if ($("#registro_placa1").val() == null) {
        alert("La Placa 1 ingresada no es válida.");

        return;
      }
      if ($("#registro_placa1").val().length == 0) {
        alert("La Placa 1 ingresada no es válida.");

        return;
      }
      if ($("#registro_placa2").val() == null) {
        alert("La Placa 1 ingresada no es válida.");

        return;
      }
      if ($("#registro_placa2").val().length == 0) {
        alert("La Placa 1 ingresada no es válida.");

        return;
      }
    }
  }

  if ($("#registro_placa1").val() == null) {
    alert("La Placa 1 ingresada no es válida.");

    return;
  }
  if ($("#registro_placa1").val().length == 0) {
    alert("La Placa 1 ingresada no es válida.");

    return;
  }
  if ($("#registro_placa2").val() == null) {
    alert("La Placa 1 ingresada no es válida.");

    return;
  }
  if ($("#registro_placa2").val().length == 0) {
    alert("La Placa 1 ingresada no es válida.");

    return;
  }

  // if (registro_transportista == null){
  //   alert("Debe seleccionar el Transportista.");

  //   return;
  // }
  // if (registro_transportista.length == 0){
  //   alert("Debe seleccionar el Transportista.");

  //   return;
  // }

  // if (registro_tipovehiculo == null){
  //   alert("Debe seleccionar el Tipo de Vehículo.");

  //   return;
  // }
  // if (registro_tipovehiculo.length == 0){
  //   alert("Debe seleccionar el Tipo de Vehículo.");

  //   return;
  // }

  // if (tiene_carreta == 1){
  //   if ($("#registro_placa1_2").val() == null){
  //     alert("La Placa 2 ingresada no es válida.");

  //     return;
  //   }
  //   if ($("#registro_placa1_2").val().length == 0){
  //     alert("La Placa 2 ingresada no es válida.");

  //     return;
  //   }
  //   if ($("#registro_placa2_2").val() == null){
  //     alert("La Placa 2 ingresada no es válida.");

  //     return;
  //   }
  //   if ($("#registro_placa2_2").val().length == 0){
  //     alert("La Placa 2 ingresada no es válida.");

  //     return;
  //   }
  // }
  // else{
  //   registro_placa2 = '';
  // }

  if (registro_conductor == null) {
    alert("Debe seleccionar el Conductor.");

    return;
  }
  if (registro_conductor.length == 0) {
    alert("Debe seleccionar el Conductor.");

    return;
  }

  // if (registro_condicion != 2){
  //   if (registro_tipocarga == null){
  //     alert("Debe seleccionar el Tipo de Carga.");

  //     return;
  //   }
  //   if (registro_tipocarga.length == 0){
  //     alert("Debe seleccionar el Tipo de Carga.");

  //     return;
  //   }
  // }

  f_LoadingGrabarIngreso(1);

  // Obtiene total de acompañantes
  var table = document.getElementById("tbl_acompanantes");
  var _rows_acompanantes = table.rows.length - 1;

  // Recorre la tabla de Acompañanates y obtiene los datos
  var a = 1;
  var arr_acompanantes = [];
  var arr_acompanantes_datos = [];

  $("#tbl_acompanantes tr").each(function () {
    if (a <= _rows_acompanantes) {
      var _acompanante = {
        cod_auto: a,
        dni: $(this).find("td").eq(2).html(),
        nombres: $(this).find("td").eq(3).html(),
        imagen: $(this).find(".imagen").attr("src"),
      };

      var _acompanante_datos = {
        cod_auto: a,
        dni: $(this).find("td").eq(2).html(),
        nombres: $(this).find("td").eq(3).html(),
        tiene_imagen: $(this).find(".imagen").attr("src").length > 0 ? 1 : 0,
      };

      arr_acompanantes.push(_acompanante);
      arr_acompanantes_datos.push(_acompanante_datos);
    }

    a++;
  });

  // Obtiene total de Imágenes adicionales
  var table = document.getElementById("tbl_imagenes");
  var _rows_imagenes = table.rows.length - 1;

  // Recorre la tabla de Acompañanates y obtiene los datos
  var a = 1;
  var arr_imagenes = [];
  var arr_imagenes_datos = [];

  $("#tbl_imagenes tr").each(function () {
    if (a <= _rows_imagenes) {
      // Verifica que se hayan registrado todas las imágenes
      if ($(this).find(".imagen").attr("src").length == 0) {
        alert(
          "Hay imágenes que no han sido cargadas.\n\nPor favor, verificar.",
        );

        f_LoadingGrabarIngreso(0);

        return;
      }

      var _imagen = {
        cod_auto: a,
        imagen: $(this).find(".imagen").attr("src"),
      };

      var _imagen_datos = {
        cod_auto: a,
        descripcion: $(this).find("td").eq(2).html().trim(),
      };

      arr_imagenes.push(_imagen);
      arr_imagenes_datos.push(_imagen_datos);
    }

    a++;
  });

  // Actualizar conductor y observacion en distribucion si la condicion es 2
  if (registro_condicion == 2 && id_placadespacho) {
    $.post("apis/backend.php", {
      accion: "update_conductor_and_observacion_distribucion",
      id_distribucion: id_placadespacho,
      id_conductor: registro_conductor,
      observacion: registro_observacion,
    });
  }

  // Grabando Datos
  $.post(
    "apis/backend.php",
    {
      accion: "grabar_recepcionunidades",
      registro_condicion: registro_condicion,
      registro_placa: registro_placa,
      registro_transportista: registro_transportista,
      registro_tipovehiculo: registro_tipovehiculo,
      tiene_carreta: tiene_carreta,
      registro_placa2: registro_placa2,
      registro_conductor: registro_conductor,
      registro_tipocarga: registro_tipocarga,
      registro_zonaorigen: registro_zonaorigen,
      registro_observacion: registro_observacion,
      tiene_vehiculoparticular: vehiculo_particular,
      id_placadespacho: id_placadespacho,
      id_distribucion: id_placadespacho,
      arr_acompanantes_datos: JSON.stringify(arr_acompanantes_datos),
      arr_imagenes_datos: JSON.stringify(arr_imagenes_datos),
      arr_acompanantes: JSON.stringify(arr_acompanantes),
      arr_imagenes: JSON.stringify(arr_imagenes),
    },
    function (data) {
      if (data.estado == 1) {
        f_LoadResultados();

        var id_registro = data.id_registro;
      } else {
        alert("Ocurrió un error al momento de grabar los datos de ingreso.");

        f_LoadingGrabarIngreso(0);

        return;
      }

      f_LoadingGrabarIngreso(0);

      f_cerrarModal("modal_addrecepcion");
    },
    "json",
  );
}

function f_GrabarCliente() {
  // Recupera variables
  var id_cliente = $("#hd_idcliente").val();
  var modo_grabar = $("#hd_modograbar").val();

  var cod_condicion = 2;
  var cod_tipocliente = f_CleanInjection($("#cliente_tipocliente").val());
  var cod_tipodocumento = f_CleanInjection($("#cliente_tipodocumento").val());
  var documento = f_CleanInjection($("#cliente_documento").val().trim());
  var razon_social = f_CleanInjection($("#cliente_razonsocial").val().trim());
  var telefono1 = f_CleanInjection($("#cliente_telefono1").val().trim());
  var telefono2 = f_CleanInjection($("#cliente_telefono2").val().trim());
  var correo = f_CleanInjection($("#cliente_correo").val().trim());
  var direccion = f_CleanInjection($("#cliente_direccion").val().trim());

  // Validando datos
  if (cod_tipocliente == null) {
    alert("Debe seleccionar el Tipo de Cliente.");

    return;
  }
  if (cod_tipocliente.length == 0) {
    alert("Debe seleccionar el Tipo de Cliente.");

    return;
  }

  if (cod_tipodocumento == null) {
    alert("Debe seleccionar el Tipo de Documento.");

    return;
  }
  if (cod_tipodocumento.length == 0) {
    alert("Debe seleccionar el Tipo de Documento.");

    return;
  }

  if (documento == null) {
    alert("Debe ingresar el Documento.");

    return;
  }
  if (documento.length == 0) {
    alert("Debe ingresar el Documento.");

    return;
  }

  if (razon_social == null) {
    alert("Debe ingresar la Razón Social.");

    return;
  }
  if (razon_social.length == 0) {
    alert("Debe ingresar la Razón Social.");

    return;
  }

  if (correo.trim().length > 0) {
    if (!f_CheckEMail("cliente_correo")) {
      alert("El correo ingresado no tiene el formato correcto.");

      return;
    }
  }

  if (direccion == null) {
    alert("Debe ingresar la Dirección.");

    return;
  }
  if (direccion.length == 0) {
    alert("Debe ingresar la Dirección.");

    return;
  }

  // Grabando Datos
  $.post(
    "apis/backend.php",
    {
      accion: "grabar_cliente",
      modo_grabar: modo_grabar,
      id_cliente: id_cliente,
      cod_condicion: cod_condicion,
      cod_tipocliente: cod_tipocliente,
      cod_tipodocumento: cod_tipodocumento,
      documento: documento,
      razon_social: razon_social,
      telefono1: telefono1,
      telefono2: telefono2,
      correo: correo,
      direccion: direccion,
    },
    function (data) {
      if (data.estado == 2) {
        alert(
          "El documento ingresado ya fue registrado anteriormente.\n\nPor favor verificar",
        );

        return;
      } else {
        if (data.estado == 1) {
          f_LoadListaTransportistas(data.id_cliente);

          f_cerrarModal("modal_addcliente");
        } else {
          alert("Ocurrió un error al momento de grabar el Cliente.");
        }
      }
    },
    "json",
  );
}

function f_GrabarConductor() {
  // Recupera variables
  var id_tipodocumento = f_CleanInjection(
    $("#conductor_tipodocumento").val().trim(),
  );
  var dni_licencia = f_CleanInjection($("#conductor_dni").val().trim());
  var licencia = f_CleanInjection($("#conductor_licencia").val().trim());
  var conductor_nombres = f_CleanInjection($("#conductor_nombres").val());

  // Validando datos
  if (id_tipodocumento == null) {
    alert("Debe seleccionar el Tipo de Documento.");

    return;
  }
  if (id_tipodocumento.length == 0) {
    alert("Debe seleccionar el Tipo de Documento.");

    return;
  }

  if (dni_licencia == null) {
    alert("Debe ingresar el DNI o N° de Licencia.");

    return;
  }
  if (dni_licencia.length == 0) {
    alert("Debe ingresar el DNI o N° de Licencia.");

    return;
  }

  if (licencia == null) {
    alert("Debe ingresar la Licencia.");

    return;
  }
  if (licencia.length == 0) {
    alert("Debe ingresar la Licencia.");

    return;
  }

  if (conductor_nombres == null) {
    alert("Debe ingresar los Nombres y Apellidos del Conductor.");

    return;
  }
  if (conductor_nombres.length == 0) {
    alert("Debe ingresar los Nombres y Apellidos del Conductor.");

    return;
  }

  // Grabando Datos
  $.post(
    "apis/backend.php",
    {
      accion: "grabar_conductor",
      modo_grabar: "N",
      id_tipodocumento: id_tipodocumento,
      id_conductor: 0,
      dni_licencia: dni_licencia,
      licencia_conducir: licencia,
      conductor_nombres: conductor_nombres,
    },
    function (data) {
      if (data.estado == 2) {
        alert(
          "El DNI o N° de Licencia ya fue registrado anteriormente.\n\nPor favor verificar",
        );

        return;
      } else {
        if (data.estado == 1) {
          f_LoadListaConductores(data.id_conductor);

          f_cerrarModal("modal_addconductor");
        } else {
          alert("Ocurrió un error al momento de grabar el Conductor.");
        }
      }
    },
    "json",
  );
}

function f_GrabarZonaOrigen() {
  // Recupera variables
  var zona_origen = f_CleanInjection($("#zona_origen").val().trim());

  // Validando datos
  if (zona_origen == null) {
    alert("Debe ingresar la Zona de Origen.");

    return;
  }
  if (zona_origen.length == 0) {
    alert("Debe ingresar la Zona de Origen.");

    return;
  }

  // Grabando Datos
  $.post(
    "apis/backend.php",
    {
      accion: "grabar_zonaorigen",
      modo_grabar: "N",
      id_zonaorigen: 0,
      zona_origen: zona_origen,
    },
    function (data) {
      if (data.estado == 2) {
        alert(
          "La Zona de Origen ingresada ya fue registrada anteriormente.\n\nPor favor verificar.",
        );

        return;
      } else {
        if (data.estado == 1) {
          f_LoadListaZonaOrigen(data.id_zonaorigen);

          f_cerrarModal("modal_addzonaorigen");
        } else {
          alert("Ocurrió un error al momento de grabar la Zona de Origen.");
        }
      }
    },
    "json",
  );
}

function f_RegistroSalida_Confirmar() {
  // Recupera variables
  var id_registro = $("#hd_idregistrosalida").val();
  var id_distribucion = $("#hd_iddistribucion_salida").val();
  var salida_estado = $("#salida_estado").val();
  var des_salidaestado = $("#salida_estado option:selected").text();
  var salida_observacion = f_CleanInjection(
    $("#salida_observacion").val().trim(),
  );

  // Validando datos
  if (salida_estado == null) {
    alert("Debe seleccionar el Estado de la Unidad.");

    return;
  }
  if (salida_estado.length == 0) {
    alert("Debe seleccionar el Estado de la Unidad.");

    return;
  }

  // Obtiene la lista de Acompañantes seleccionados
  var a = 1;
  var arr_acompanantes = "";

  $("#tbl_acompanantes_salida tr").each(function () {
    if ($("#chk_acompanante_" + a).prop("checked")) {
      arr_acompanantes += $("#id_acompanante_" + a).val() + "|";
    }

    a++;
  });

  if (arr_acompanantes.length > 0) {
    arr_acompanantes = arr_acompanantes.substring(
      0,
      arr_acompanantes.length - 1,
    );
  }

  // Grabando Datos
  f_LoadingRegistroSalida(1);

  if (id_distribucion > 0) {
    $.post("apis/backend.php", {
      accion: "update_fecha_salida_observacion_distribucion",
      id_distribucion: id_distribucion,
      fecha_hora_salida: "CURRENT_TIMESTAMP",
      observacion_salida: salida_observacion,
    });
  }

  $.post(
    "apis/backend.php",
    {
      accion: "grabar_salidaunidades",
      id_registro: id_registro,
      salida_estado: salida_estado,
      salida_observacion: salida_observacion,
      arr_acompanantes: arr_acompanantes,
    },
    function (data) {
      if (data.estado == 1) {
        $("#td_salida_1_" + id_registro).html(
          data.fechahora_registro + "</br><i>" + data.usuario_registro + "</i>",
        );
        $("#td_salida_2_" + id_registro).html(des_salidaestado);
        $("#td_salida_3_" + id_registro).html(salida_observacion.toUpperCase());

        f_LoadResultados();
      }

      f_LoadingRegistroSalida(0);

      f_cerrarModal("modal_registrosalida");
    },
    "json",
  );
}

function f_RegistroSalida_Acompanantes(_id_acompanante, _nombres) {
  if (!confirm("¿Está seguro de registrar la salida de:\n\n" + _nombres)) {
    return;
  }

  // Grabando salida
  $.post(
    "apis/backend.php",
    { accion: "grabar_salidaacompanante", id_acompanante: _id_acompanante },
    function (data) {
      if (data.estado == 1) {
        $("#td_salidaacompanante_" + _id_acompanante).html(
          data.fechahora_salida + "<br><i>" + data.usuario_registro + "</i>",
        );
      }
    },
    "json",
  );
}

function f_SetDimension() {
  if (screen.width < 500) {
    $("#offcanvasExample").css("width", "60%");

    $(
      "#modal_addcliente_content, #modal_addconductor_content, #modal_addzonaorigen_content, #modal_addacompanante_content",
    ).css("margin-top", "10px");
  }
}

function f_ToggleCamposDespachoMineral() {
  var condicion = $("#registro_condicion").val();
  var placa_despacho = $("#registro_placasdespacho").val();

  var isModoDespachoMineral =
    condicion == 2 &&
    placa_despacho != null &&
    placa_despacho != "" &&
    placa_despacho != 9.9;

  $("#registro_fechadespacho").prop("disabled", isModoDespachoMineral);
  $("#registro_placa1").prop("disabled", isModoDespachoMineral);
  $("#registro_placa2").prop("disabled", isModoDespachoMineral);
  $("#registro_transportista").prop("disabled", isModoDespachoMineral);
  $("#registro_tipovehiculo").prop("disabled", isModoDespachoMineral);
  $("#registro_placa1_2").prop("disabled", isModoDespachoMineral);
  $("#registro_placa2_2").prop("disabled", isModoDespachoMineral);
  $("#registro_tipocarga").prop("disabled", isModoDespachoMineral);
  $("#registro_zonaorigen").prop("disabled", isModoDespachoMineral);
  $("#div_zonaorigen button").prop("disabled", isModoDespachoMineral);
}
