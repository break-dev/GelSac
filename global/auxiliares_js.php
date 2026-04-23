<?php

// Loading modal
echo '	<div id="divmodal_Loading" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="divmodal_LoadingLabel" aria-hidden="true" style="margin-top: 300px; overflow-y: hidden;">
							<div class="modal-dialog" style="height: 100vh; opacity: 0.5; width: 100%;"><center>
								<img src="' . $url_images . '/loading.gif" width="200px" style="border: solid; border-width: 1px; border-color: #D9D9D9;  border-radius: 15px;"></img>
							</center></div>
						</div>';

include('modal_global.php');

?>

<script type="text/javascript">
	let g_ismovil = 0; // Para saber si se está ingresando des un móvil
	const modal_loading = new bootstrap.Modal(document.getElementById('divmodal_Loading'), {});

	// Seteando eventos iniciales para formularios
	$(document).ready(function () {

	});

	// Determinando si se está accediendo desde un móvil u otro dispositivo
	function f_DetectarDispositivo() {
		var esMovil = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
		var esTactil = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
		var esPequeño = window.matchMedia("(max-width: 768px)").matches;

		if (esMovil || (esTactil && esPequeño)) {
			g_ismovil = 1;
		} else if (esTactil) {
			// return "Tablet o Laptop táctil";
		} else {
			// return "PC o Laptop";
		}
	}

	// Para cuando se abra un modal
	$('.modal').on('shown.bs.modal', function () {
		$('.modal-backdrop').css('width', '100%');
		$('.modal-backdrop').css('height', '100%');
	});

	var modalMenuElement = document.querySelector('#menuModal');
	var modalFiltroElement = document.querySelector('#filtroModal');

	// Elimina los atributos
	if (modalMenuElement) {
		modalMenuElement.removeAttribute('data-bs-backdrop');
		modalMenuElement.removeAttribute('data-bs-keyboard');
	}

	if (modalFiltroElement) {
		modalFiltroElement.removeAttribute('data-bs-backdrop');
		modalFiltroElement.removeAttribute('data-bs-keyboard');
	}


	// Abriendo Waiting modals
	$(document).ajaxStart(function () {
		// modal_loading.show();
	});

	// Cerrando Waiting modals
	$(document).ajaxStop(function () {
		// modal_loading.hide();
	});

	function f_GetMenuPrincipal() {
		$.post("apis/backend.php", { accion: "get_menus_new" },
			function (data) {
				if (data.estado == 1) {
					$("#div_menu1").html(data.html);
				}
				else {
					$("#div_menu1").html('');
				}

			}, "json");
	}

	// function f_GetMenuPrincipalNew(){
	// 	$.post( "apis/backend.php", { accion: "get_menus_new" }, 
	// 		function( data ) {
	// 			if(data.estado == 1){
	// 				$("#div_menu1").html(data.html);
	// 			}
	// 			else{
	// 				$("#div_menu1").html('');
	// 			}

	// 		}, "json");
	// }

	function f_OpenMenu(_id_window) {
		window.open(_id_window, '_self');
	}

	function f_ShowSubMenu(_id_elemento) {
		if ($('#div_submenu_' + _id_elemento).is(':visible')) {
			$('#div_submenu_' + _id_elemento).attr("style", "display: none !important");

			$("#img_SubMenu_" + _id_elemento).removeClass("bi-arrow-bar-up").addClass("bi-arrow-bar-down");
		}
		else {
			$('#div_submenu_' + _id_elemento).show(350);

			$("#img_SubMenu_" + _id_elemento).removeClass("bi-arrow-bar-down").addClass("bi-arrow-bar-up");
		}
	}

	function f_ShowSubMenu2(_id_subelemento) {
		if ($('#div_submenu2_' + _id_subelemento).is(':visible')) {
			$('#div_submenu2_' + _id_subelemento).attr("style", "display: none !important");

			$("#img_SubMenu2_" + _id_subelemento).removeClass("bi-arrow-bar-up").addClass("bi-arrow-bar-down");
		}
		else {
			$('#div_submenu2_' + _id_subelemento).show(350);

			$("#img_SubMenu2_" + _id_subelemento).removeClass("bi-arrow-bar-down").addClass("bi-arrow-bar-up");
		}
	}

	function f_GetSubmenu1(_cod_seccion, _nom_seccion) {
		$.post("apis/backend.php", { accion: "get_submenu1", cod_seccion: _cod_seccion },
			function (data) {
				if (data.estado == 1) {
					$("#div_submenu1").html(data.html);

					$("#sb1_titulo").html(_nom_seccion);

					$(".offcanvas-backdrop").css('width', '100%');
					$(".offcanvas-backdrop").css('height', '100%');
				}
				else {
					$("#div_submenu1").html('');

					window.open('index.php', '_self');
				}

			}, "json");
	}

	function f_CheckEMail(_id_object) {
		var _estado = 1;

		if ($("#" + _id_object).val().indexOf('@', 0) == -1 || $("#" + _id_object).val().indexOf('.', 0) == -1) {
			_estado = 0;
		}

		return _estado;
	}

	function f_OpenModal(_id_modal) {
		$("#" + _id_modal).modal("show");
	};

	function f_cerrarModal(_id_modal) {
		$("#" + _id_modal).modal('hide');
	};

	function f_CerrarDiv(accion, _id_div) {
		var _div = document.getElementById(_id_div);

		if (accion == 'A') {
			_div.style.display = 'block'
		}
		else {
			_div.style.display = 'none';
		}
	};

	function f_CleanInjection(_val) {
		_val = ((_val == null) ? '' : _val);
		_val = _val.toString().trim().replace(/'/g, '');

		return _val;
	};

	function f_CerrarSesion() {
		window.open('cerrar_sesion.php', '_self');
	};

	function f_ReplaceClass(_Id, _oldClass, _newClass) {
		var _obj = $("#" + _Id);

		if (_obj.hasClass(_oldClass)) {
			_obj.removeClass(_oldClass);
		}
		_obj.addClass(_newClass);
	}

	function f_CheckClientesCredito() {
		$("#tst_container").html('');

		$.post("apis/backend.php", { accion: "check_clientescredito" },
			function (data) {
				if (data.estado == 1) {
					$("#tst_container").html(data.html);

					setTimeout('f_CheckClientesCredito()', 600000);

					return;
				}

			}, "json");
	}

	function f_AutorizarVisita(_id_visita, _is_autorizado) {
		$.post("apis/backend.php", { accion: "update_AutorizacionVisita", id_visita: _id_visita, is_autorizado: _is_autorizado },
			function (data) {
				if (data.estado == 1) {
					f_CheckVisitas();
				}
				else {
					alert("Ocurrió un error al momento de Grabar la autorización de la visita.");
				}

			}, "json");
	}

	function f_RedondearDecimales(_numero, _cantidadDecimales) {
		var partes = parseFloat(_numero).toFixed(_cantidadDecimales).toString().split('.');
		var entero = partes[0];
		var decimal = partes[1] || '';

		if (decimal.length < _cantidadDecimales) {
			decimal = decimal.padEnd(_cantidadDecimales, '0');
		}

		if (decimal.endsWith(".")) {
			decimal = decimal.slice(0, -1);
		}

		return `${parseFloat(`${entero}.${decimal}`).toLocaleString('es-PE', { minimumFractionDigits: _cantidadDecimales })}`
	}

	function f_CalcularDiferenciaHoras(_fecha_inicio, _fecha_fin) {
		// Convertir las fechas en objetos Date
		var inicio = new Date(_fecha_inicio);
		var fin = new Date(_fecha_fin);

		// Calcular la diferencia en milisegundos
		var diferencia = fin - inicio;

		// Calcular la diferencia en horas con un decimal
		var horas = diferencia / (1000 * 60 * 60);
		horas = horas.toFixed(1); // Redondear a 1 decimal

		return horas;
	}

	function f_ModoAuditoria(_on) {
		if (_on == 1) {
			if (!confirm("¿Está seguro de Iniciar el Modo Auditoría?")) {
				return;
			}
		}

		// Grabar la ejecución
		$.post("../apis/backend.php", { accion: "grabar_ModoAuditoria", is_on: _on },
			function (data) {
				if (data.estado == 1) {
					if (_on == 1) {
						window.open('resumen_balanza.php', '_self');
					}
					else {
						window.open('inicio.php', '_self');

						f_CheckModoAuditoria();
					}
				}

			}, "json");
	}

	function f_CheckVisitas() {
		$("#tst_visitas").html('');

		$.post("apis/backend.php", { accion: "check_visitas" },
			function (data) {
				if (data.estado == 1) {
					$("#tst_visitas").html(data.html);

					setTimeout('f_CheckVisitas()', 20000);

					return;
				}

			}, "json");
	}

	function f_CheckModoAuditoria() {
		$.post("apis/backend.php", { accion: "check_ModoAuditoria" },
			function (data) {
				if (data.estado == 1) {
					if (data.is_on == 1) {
						if (data.is_ejecucion == 0) {
							window.open('resumen_balanza.php', '_self');
						}
					}

					setTimeout('f_CheckModoAuditoria()', 5000);
				}

			}, "json");
	}

	function f_LoadingRegistro(_idbase, _is_show) {
		let _div_wait = "#wt_" + _idbase;
		let _clase_botones = ".wt_" + _idbase + "_button";

		if (_is_show) {
			$(_div_wait).show();
			$(_clase_botones).prop('disabled', true);
		}
		else {
			$(_div_wait).hide();
			$(_clase_botones).prop('disabled', false);
		}
	}

	// Convertir Base64 a Blob
	function base64ToBlob(base64) {
		const parts = base64.split(',');
		const mime = parts[0].match(/:(.*?);/)[1];
		const binary = atob(parts[1]);
		const array = [];
		for (let i = 0; i < binary.length; i++) {
			array.push(binary.charCodeAt(i));
		}
		return new Blob([new Uint8Array(array)], { type: mime });
	}

	// Convertir Blob a Base64
	function blobToBase64(blob) {
		return new Promise((resolve, reject) => {
			const reader = new FileReader();
			reader.onloadend = () => resolve(reader.result);
			reader.onerror = reject;
			reader.readAsDataURL(blob);
		});
	}

	// Comprimir una imagen Blob
	async function compressImage(blob) {
		const options = {
			maxSizeMB: 1, // Tamaño máximo de 1 MB
			maxWidthOrHeight: 800, // Dimensión máxima de 800px
			useWebWorker: true, // Optimizar el rendimiento
		};
		return await imageCompression(blob, options);
	}

	// Abre las listas modales para dispositivos móviles
	function f_ShowListaModal(_select) {
		if (_select.disabled) {
			return;
		}

		// Cierra la lista desplegable
		$(_select).blur();

		// Limpiando objetos
		$("#modalglobal_find").val('');

		// Setea título
		var modal_titulo = _select.getAttribute("data-titulo");

		$("#modalglobal_Titulo").html(modal_titulo);

		f_OpenModal('modal_global');

		// Identifica el Select
		var id_select = _select.id;

		// Crea html para la tabla
		var _val = '';
		var _text = '';
		var _html = '';

		for (let option of _select.options) {
			// if (option.value != 'x' && option.value.trim().length > 0){
			_html += '<tr style="font-size: 18px; color: #ffffff;" onclick="f_ListaModal_Select(' + "'" + id_select + "', '" + option.value + "'" + ');">';
			_html += '	<td hidden>';
			_html += '		' + option.value;
			_html += '	</td>';

			_html += '	<td style="text-align: center; width: 40px; vertical-align: middle; height: 50px;">';
			_html += '		<img src="<?php echo $img_select2 ?>" style="width: 20px;">';
			_html += '	</td>';

			_html += '	<td style="vertical-align: middle;">';
			_html += '		' + option.text;
			_html += '	</td>';
			_html += '</tr>';
			// }
		}

		$("#modalglobal_TablaOpciones").html(_html);
	}

	// Asignar el valor seleccionado de la lista modal global
	function f_ListaModal_Select(_id_select, _val) {
		$("#" + _id_select).val(_val);

		// Ejecutar el onchange del select
		var _select = document.getElementById(_id_select);
		_select.dispatchEvent(new Event("change")); // Disparar el evento onchange del select

		// Cerrando la ventana modal
		f_cerrarModal('modal_global');
	}

	// Busca coincidencias en la tabla de resultados del Modal Global
	function f_ModalGlobal_Finding() {
		var _find = $("#modalglobal_find").val().trim().toLowerCase(); // Obtiene el valor en minúsculas

		$("#modalglobal_TablaOpciones tr").each(function () {
			var descripcion = $(this).find("td:nth-child(3)").text().toLowerCase(); // Obtiene el texto del tercer td

			if (descripcion.includes(_find)) {
				$(this).show(); // Muestra la fila si coincide
			}
			else {
				$(this).hide(); // Oculta la fila si no coincide
			}
		});
	}

	// Coloca el cursor en el Input de búsqueda del Modal Global
	$("#modal_global").on('shown.bs.modal', function () {
		$("#modalglobal_find").focus();
	});

	// Cambiar el formato de Fecha PE
	function f_FormatFecha(_fechahora, _show_hora = 1) {
		let fecha;

		if (_fechahora.toLowerCase() === "hoy") {
			fecha = new Date();
		} else if (/^\d{4}-\d{2}-\d{2}$/.test(_fechahora)) { // Caso "YYYY-MM-DD"
			fecha = new Date(_fechahora + "T00:00:00");
		} else if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(_fechahora)) { // Caso "YYYY-MM-DD HH:MM:SS"
			fecha = new Date(_fechahora.replace(" ", "T"));
		} else {
			console.error("Formato de fecha no válido:", _fechahora);

			return null;
		}

		const dia = String(fecha.getDate()).padStart(2, '0');
		const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Meses van de 0 a 11
		const año = fecha.getFullYear();

		if (_show_hora) {
			const hora = String(fecha.getHours()).padStart(2, '0');
			const minutos = String(fecha.getMinutes()).padStart(2, '0');
			const segundos = String(fecha.getSeconds()).padStart(2, '0');
			return `${dia}/${mes}/${año} ${hora}:${minutos}:${segundos}`;
		} else {
			return `${dia}/${mes}/${año}`;
		}
	}

	// Obtener el Precio de Kitco
	function f_GetPrecioInternacionalAu(id_input, fecha = '') {
		if ($("#" + id_input).length == 0) return;

		$("#" + id_input).val("Cargando...");

		$.ajax({
			url: "../apis/backend.php",
			type: "POST",
			data: {
				accion: "get_ValorizacionCompra_PrecioInternacional",
				fecha: fecha
			},
			dataType: "json",
			success: function (data) {
				if (data.estado == 1) {
					$("#" + id_input).val(parseFloat(data.precio).toFixed(2));
				} else {
					$("#" + id_input).val("No disponible");
				}
			},
			error: function () {
				$("#" + id_input).val("Error");
			}
		});
	}

	// Addslashes (equivalente a PHP)
	function f_AddSlashes(_string) {
		return _string.replace(/\\/g, '\\\\')
			.replace(/'/g, "\\'")
			.replace(/"/g, '\\"')
			.replace(/\u0000/g, '\\0');
	}

	// Redonde a Entero
	function f_FormatEntero(valor) {
		if (isNaN(valor) || valor === null) return "0.00";
		return Math.round(parseFloat(valor)).toFixed(2);
	}

	// f_CheckClientesCredito();
	// f_CheckVisitas();
	// f_CheckModoAuditoria();
	f_DetectarDispositivo();
</script>

<!-- Para Exportar tablas html a Excel con ExcelJS -->
<script type="module">
	async function exportar_TablaAExcel(idTabla, nombreArchivo = "reporte.xlsx", nombreHoja = "Hoja1") {
		const { default: ExcelJS } = await import('https://cdn.jsdelivr.net/npm/exceljs@4.4.0/+esm');

		const tabla = document.getElementById(idTabla);
		if (!tabla) {
			alert("No se encontró la tabla con ID: " + idTabla);
			return;
		}

		const wb = new ExcelJS.Workbook();
		const ws = wb.addWorksheet(nombreHoja);
		const maxColsExcel = 16384;

		for (let i = 0; i < tabla.rows.length; i++) {
			const filaHTML = tabla.rows[i];
			let colIndex = 1;

			for (let j = 0; j < filaHTML.cells.length; j++) {
				const celdaHTML = filaHTML.cells[j];

				// Ignorar celdas internas con <table>
				if (celdaHTML.querySelector("table")) continue;

				const colspan = parseInt(celdaHTML.getAttribute("colspan") || "1");
				const rowspan = parseInt(celdaHTML.getAttribute("rowspan") || "1");

				while (ws.getCell(i + 1, colIndex).value !== undefined) {
					colIndex++;
				}

				if (colIndex > maxColsExcel) break;

				const cell = ws.getCell(i + 1, colIndex);
				cell.value = celdaHTML.innerText.trim();

				// Estilos base
				cell.alignment = { vertical: "middle", horizontal: "center", wrapText: true };
				cell.border = {
					top: { style: "thin" },
					left: { style: "thin" },
					bottom: { style: "thin" },
					right: { style: "thin" }
				};

				if (filaHTML.parentElement.tagName === "THEAD") {
					cell.font = { bold: true };
				}

				// Estilos visuales desde CSS
				const bgColor = window.getComputedStyle(celdaHTML).backgroundColor;
				const fontColor = window.getComputedStyle(celdaHTML).color;

				if (bgColor && bgColor !== "rgba(0, 0, 0, 0)" && bgColor !== "transparent") {
					cell.fill = {
						type: "pattern",
						pattern: "solid",
						fgColor: { argb: rgbToHex(bgColor).replace("#", "") }
					};
				}

				if (fontColor && fontColor !== "rgba(0, 0, 0, 0)" && fontColor !== "transparent") {
					cell.font = { ...(cell.font || {}), color: { argb: rgbToHex(fontColor).replace("#", "") } };
				}

				const endCol = colIndex + colspan - 1;
				const endRow = i + rowspan;

				if (endCol <= maxColsExcel) {
					if (colspan > 1 || rowspan > 1) {
						ws.mergeCells(i + 1, colIndex, endRow, endCol);
					}
				}

				colIndex += colspan;
			}
		}

		const buffer = await wb.xlsx.writeBuffer();
		const blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
		const link = document.createElement("a");
		link.href = URL.createObjectURL(blob);
		link.download = nombreArchivo;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}

	// Convertir RGB → HEX
	function rgbToHex(rgb) {
		const result = rgb.match(/\d+/g);
		if (!result) return "#FFFFFF";
		return (
			"#" +
			result
				.slice(0, 3)
				.map((x) => {
					const hex = parseInt(x).toString(16);
					return hex.length === 1 ? "0" + hex : hex;
				})
				.join("")
		);
	}

	// Exponer como función global
	window.exportar_TablaAExcel = exportar_TablaAExcel;
</script>

<script type="text/javascript">
	var voiceList = document.querySelector('#voiceList');
	var tts = window.speechSynthesis;
	var voices = [];

	GetVoices();

	if (speechSynthesis !== undefined) {
		speechSynthesis.onvoiceschanged = GetVoices;
	}

	function GetVoices() {
		voices = tts.getVoices();
		if (!voiceList) return;
		voiceList.innerHTML = '';
		voices.forEach((voice) => {
			var listItem = document.createElement('option');
			listItem.textContent = voice.name;
			listItem.setAttribute('data-lang', voice.lang);
			listItem.setAttribute('data-name', voice.name);
			voiceList.appendChild(listItem);
		});

		voiceList.selectedIndex = 0;
	}

	function f_StartToSpeech(_sexo, _nom_usuario) {
		var txtMsg = 'Bienvenid' + ((_sexo == 0) ? 'o' : 'a') + ' ' + _nom_usuario + ', este es el ERP Operaciones de GEL   ZAC.';
		var toSpeak = new SpeechSynthesisUtterance(txtMsg);
		var selectedVoiceName = voiceList.selectedOptions[0].getAttribute('data-name');
		var _ok = 0;

		voices.forEach((voice) => {
			if (voice.name === 'Google español de Estados Unidos') {
				toSpeak.voice = voice;

				_ok = 1;
			}
		});

		if (_ok == 0) {
			voices.forEach((voice) => {
				if (voice.name === selectedVoiceName) {
					toSpeak.voice = voice;
				}
			});
		}

		tts.speak(toSpeak);
	}

	let _speechSynth
	let _voices
	const _cache = {}

	/**
	 * retries until there have been voices loaded. No stopper flag included in this example. 
	 * Note that this function assumes, that there are voices installed on the host system.
	 */

	function loadVoicesWhenAvailable(onComplete = () => { }) {
		_speechSynth = window.speechSynthesis
		const voices = _speechSynth.getVoices()

		if (voices.length !== 0) {
			_voices = voices
			onComplete()
		} else {
			return setTimeout(function () { loadVoicesWhenAvailable(onComplete) }, 100)
		}
	}

	/**
	 * Returns the first found voice for a given language code.
	 */

	function getVoices(locale) {
		if (!_speechSynth) {
			throw new Error('Browser does not support speech synthesis')
		}
		if (_cache[locale]) return _cache[locale]

		_cache[locale] = _voices.filter(voice => voice.lang === locale)
		return _cache[locale]
	}

	/**
	 * Speak a certain text 
	 * @param locale the locale this voice requires
	 * @param text the text to speak
	 * @param onEnd callback if tts is finished
	 */

	function playByText(locale, text, onEnd) {
		const voices = getVoices(locale)

		// TODO load preference here, e.g. male / female etc.
		// TODO but for now we just use the first occurrence
		const utterance = new window.SpeechSynthesisUtterance()
		utterance.voice = voices[0]
		utterance.pitch = 1
		utterance.rate = 1
		utterance.voiceURI = 'Sara'
		utterance.volume = 1
		utterance.rate = 1.05
		utterance.pitch = 0.8
		utterance.text = text
		utterance.lang = locale

		if (onEnd) {
			utterance.onend = onEnd
		}

		_speechSynth.cancel() // cancel current speak, if any is running
		_speechSynth.speak(utterance)
	}

	// on document ready
	loadVoicesWhenAvailable(function () {
		console.log("loaded")
	})

	function speak(_sexo, _nom_usuario) {
		var txtMsg = 'Bienvenid' + ((_sexo == 1) ? 'o' : 'a') + ' ' + _nom_usuario + ', este es el ERP Operaciones de GEL   ZAC.';

		setTimeout(() => playByText("es-PE", txtMsg), 300)
	}

	function f_SendRecordatorio_ClientesCredito(_is_vencido, _cliente, _correo, _vencimiento) {
		// Seteando parámetros enviados
		$("#clientescredito_cliente").val(_cliente);
		$("#clientescredito_correo").val(_correo);

		var txt_correo = "Estimado cliente, buen día.\n\nLe recordamos que su crédito";

		if (_is_vencido == 1) {
			txt_correo += " venció el día " + _vencimiento.split('-')[2] + '/' + _vencimiento.split('-')[1] + '/' + _vencimiento.split('-')[0] + " y aún tiene facturas pendientes por pagar. Por tal motivo, solicitamos pueda cancelar la deuda ";
		}
		else {
			txt_correo += " vencerá el día " + _vencimiento.split('-')[2] + '/' + _vencimiento.split('-')[1] + '/' + _vencimiento.split('-')[0] + " y aún tiene facturas pendientes por pagar. Por tal motivo, le recordamos hacer el pago antes de la fecha indicada ";
		}

		txt_correo += "para continuar brindándole el servicio acostumbrado.\n\nQuedamos atentos a su pronta confirmación, muchas gracias.";

		$("#clientescredito_texto").val(txt_correo);

		// Abriendo ventana modal
		f_OpenModal('modal_clientescredito_sendemail');
	}

	function DownloadFileFromUrl(fileURL, fileName) {
		var link = document.createElement('a');
		link.href = fileURL;
		link.download = fileName;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}


</script>