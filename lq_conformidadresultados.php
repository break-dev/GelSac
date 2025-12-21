<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

  $is_vistatouch = $_SESSION["vista_touch"];

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="<?php echo $favicon; ?>" type="image/png"/>

		<!-- Bootstrap -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

		<!-- Íconos -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

		<!-- Select2 -->
		<link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

		<title><?php echo $nom_app; ?> | LQ - Conformidad de Resultados</title>

		<script type="text/javascript">

		</script>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<!-- Menús principales -->
				<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-1" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; height: 114vh; background-color: #DEDEDE;">
					
				</div>

				<div class="col-md-11 col-sm-11 col-xs-11" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
							<div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
								<h5>Filtros</h5>
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="row" style="padding-left: 30px; margin-top: -5px; margin-bottom: 10px; font-size: 13px;">
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 14px;">Por Fechas</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
											<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadResultados();">

											<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>" onblur="f_LoadResultados();">
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" style="padding: 0px;">
							<div class="" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
									<div class="d-flex" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
										<div class="col-md-9 col-sm-9 col-xs-12" style="padding: 0px;">
											<div class="d-flex">
												<h5>Detalle de Resultados</h5>

												<div id="wt_detalle" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
													<img src="<?php echo $img_waiting ?>" style="width: 20px;">
													<label style="font-style: italic;"> Cargando datos...</label>
												</div>
											</div>
										</div>

										<div class="col-md-3 col-sm-3 col-xs-12" style="text-align: right;">
											<div class="d-flex">
												<button class="btn btn-danger" type="button" onclick="f_CierreResultados();" style="width: 60%; color: #ffffff; font-size: 14px; margin-top: -5px; margin-bottom: 4px; margin-left: 10px;">
						              <b>Cierre de Resultados</b>
						            </button>

						            <div id="wt_cierre" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 7px; width: 200px;">
													<label style="font-style: italic; margin-left: 5px;"> Cargando datos...</label>
													<img src="<?php echo $img_waiting ?>" style="width: 20px; margin-top: -5px;">
												</div>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
									<table class="table table-bordered table-hover">
					        	<thead>
					        		<tr style="font-size: 14px;">
					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
					        				N°
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Sucursal
					        			</th>

					        			<th colspan="4" style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Cierre
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
					        				N° Informe Ensayos
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 110px;">
					        				Código L.Q.
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
					        				Cliente
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
					        				Código Cliente
					        			</th>

					        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
					        				Fecha Ensayo
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 180px;">
					        				Análisis
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #00A1F2; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				H2O
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #8FBF6D; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Cu
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #8FBF6D; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				CuOx
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #F2C230; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Au (g/tm)
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #F2C230; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Au (oz/tc)
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #CCEDE9; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Ag (g/tm)
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #CCEDE9; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Ag (oz/tc)
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #F05E5E; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				As
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #BFBFBF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Pb
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #BFBFBF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				PbOx
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #F2AA52; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Zn
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #F2AA52; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				ZnOx
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #A07DBF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Sb
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #8AFEFF; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Bi
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #40A54A; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Cd
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #23798C; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				S
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #F279C8; border-color: #ffffff; color: #404040; vertical-align: middle; min-width: 100px;">
					        				Fe
					        			</th>

					        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px; border-top-right-radius: 15px;">
					        				Observación
					        			</th>
					        		</tr>

					        		<tr style="font-size: 14px;">
					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Sel.
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Fecha Hora
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Usuario
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #FF5F5D; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
					        				Informe de Ensayo
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Inicio
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Fin
					        			</th>
					        		</tr>
					        	</thead>

					        	<tbody id="tbl_detalle">
					        		
					        	</tbody>
					        </table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Menú flotante -->
			<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%;">
			  <div class="offcanvas-header" style="background-color: #ffffff;">
			    <h5 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h5>
			    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			  </div>

			  <div id="div_submenu1" class="offcanvas-body" style="color: #212529;">

			  </div>
			</div>
		</div>

		<!-- Ventanas modales -->

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Select2 -->
		<script src="libs/select2/dist/js/select2.full.min.js"></script>

		<!-- ECharts -->
		<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<!-- Funciones de Inicio -->
		<script type="text/javascript">
			function f_Init(){
				// Genera menús
					f_GetMenuPrincipal();

				// Titulo de Pantalla
					$("#nv_titulo").html('| LQ - Conformidad de Resultados');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadResultados();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
      function f_LoadResultados(){
        var _html = '';

        // Obteniendo filtros
        	var fecha_inicio = $("#fecha_inicio").val();
        	var fecha_fin = $("#fecha_fin").val();

        // Validando datos
      		// if (fecha_inicio == null){
          //   alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }
	        // if (fecha_inicio.length == 0){
          //   alert('La fecha "Desde" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }

	        // if (fecha_fin == null){
          //   alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }
	        // if (fecha_fin.length == 0){
          //   alert('La fecha "Hasta" ingresada no es correcta.\nPor favor, verificar.');

          //   return;
	        // }

	        // if (fecha_fin < fecha_inicio){
          //   alert('La fecha "Desde" no puede ser mayor a la fecha "Hasta".\nPor favor, verificar.');

          //   return;
        	// }

        // Cargando datos
          $("#tbl_detalle").html(_html);

          f_LoadingDetalle(1);

          $.post( "apis/backend.php", { accion: "get_CierreResultados_ListaAnalisisResultados", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, 
            function( data ) {
              if(data.estado == 1){
                // Actualiza la tabla de Muestras
                  $("#tbl_detalle").html(data.html);

                // Verifica los checks de muestras
                  f_VerificaResultadosCompletos(0);
              }

              f_LoadingDetalle(0);

            }, "json");
      };

      function f_VerificaResultadosCompletos(_item){
      	var d = 1;
      	var a = 1;
      	var arr_analisis = '';
      	var resultado = '';
      	var pos = '';
      	var nom_obj = '';
      	var tiene_resultados = 1;
      	var _html = '';

      	if (_item == 0){
      		$("#tbl_detalle tr").each(function () {
      			if ($("#td_cierre_2_" + d).html().trim().length == 0){
		          arr_analisis = $("#td_countanalisis_3_" + d).html().trim();

		          if (arr_analisis.length > 0){
		          	arr_analisis = arr_analisis.split('|');

		          	// Validando que los análisis tengan resultados
			          	a = 0
			          	resultado = '';
			          	tiene_resultados = 1;
			          	_html = '';

			          	while (a < arr_analisis.length){
			          		pos = arr_analisis[a];

			          		// Setea el nombre de los objetos
			          			if (pos == 4 || pos == 5){
			          				if (pos == 4){
			          					nom_obj = 'au_g_' + d;
			          				}
			          				else{
			          					nom_obj = 'ag_g_' + d;
			          				}
			          			}
			          			else{
			          				nom_obj = 'resultado_' + pos + '_' + d;
			          			}

			          		// Valida que tenga resultados
			          			if ($("#" + nom_obj).val().trim().length == 0){
			          				tiene_resultados = 0;
			          			}

			          		a ++;
			          	}

			          // Valida que se haya ingresado el N° de Informe de Ensayos
			          	if ($("#num_IE_" + d).val().trim().length == 0){
		          				tiene_resultados = 0;
		          			}

			          // Si tiene todos los resultados
		          		_html = '<input id="rowchk_' + d + '" class="form-check-input" style="transform: scale(1.5);" type="checkbox" ';

		        			if (tiene_resultados == 1){
		        				_html += 'checked';
		        			}
		        			else{
		        				_html += 'disabled';
		        			}

		        			_html += '>';

		        			$("#td_cierre_1_" + d).html(_html);
		          }
          	}

	          d ++
	        });
      	}
      	else{
      		arr_analisis = $("#td_countanalisis_3_" + _item).html().trim();

          if (arr_analisis.length > 0){
          	arr_analisis = arr_analisis.split('|');

          	// Validando que los análisis tengan resultados
	          	a = 0
	          	resultado = '';
	          	tiene_resultados = 1;
	          	_html = '';

	          	while (a < arr_analisis.length){
	          		pos = arr_analisis[a];

	          		// Setea el nombre de los objetos
	          			if (pos == 4 || pos == 5){
	          				if (pos == 4){
	          					nom_obj = 'au_g_' + _item;
	          				}
	          				else{
	          					nom_obj = 'ag_g_' + _item;
	          				}
	          			}
	          			else{
	          				nom_obj = 'resultado_' + pos + '_' + _item;
	          			}

	          		// Valida que tenga resultados
	          			if ($("#" + nom_obj).val().trim().length == 0){
	          				tiene_resultados = 0;
	          			}

	          		a ++;
	          	}

	          // Valida que se haya ingresado el N° de Informe de Ensayos
	          	if ($("#num_IE_" + _item).val().trim().length == 0){
        				tiene_resultados = 0;
        			}

	          // Si tiene todos los resultados
	          	_html = '<input id="rowchk_' + _item + '" class="form-check-input" style="transform: scale(1.5);" type="checkbox" ';

        			if (tiene_resultados == 1){
        				_html += 'checked';
        			}
        			else{
        				_html += 'disabled';
        			}

        			_html += '>';

        			$("#td_cierre_1_" + _item).html(_html);
          }
      	}
      }

      function f_PrintDocumentosCliente(_id_md5){
      	var url = 'print_informeensayos.php?x=' + _id_md5;

      	window.open(url, '_blank');
      }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingDetalle(_is_show){
				if (_is_show == 1){
					$("#wt_detalle").show();
				}
				else{
					$("#wt_detalle").hide();
				}
			}

			function f_LoadingCierre(_is_show){
				if (_is_show == 1){
					$("#wt_cierre").show();
				}
				else{
					$("#wt_cierre").hide();
				}
			}

			function f_CalcularOnzas(_is_au, _item){
				var factor = 34.285;
				var _gramos = '';

				if(_is_au == 1){
					_gramos = $("#au_g_" + _item).val();

					$("#td_auonza_" + _item).html(parseFloat(_gramos / factor).toFixed(3));
				}
				else{
					_gramos = $("#ag_g_" + _item).val();

					$("#td_agonza_" + _item).html(parseFloat(_gramos / factor).toFixed(3));
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			function f_GrabarResultados(_item, _pos_objeto){
				var _id_detalle = $("#CI_" + _item).val();
				var _resultado = '';

				// Obteniendo y seteando resultados
					if (_pos_objeto == 4 || _pos_objeto == 5 || _pos_objeto == 16 || _pos_objeto == 17 || _pos_objeto == 18 || _pos_objeto == 19){
						if (_pos_objeto == 4){
							_resultado = $("#au_g_" + _item).val();
						}

						if (_pos_objeto == 5){
							_resultado = $("#ag_g_" + _item).val();
						}

						if (_pos_objeto == 16){
							_resultado = $("#cab_observacion_" + _item).val();
						}

						if (_pos_objeto == 17){
							_resultado = $("#num_IE_" + _item).val();
						}

						if (_pos_objeto == 18){
							_resultado = $("#fechaensayo_inicio_" + _item).val();
						}

						if (_pos_objeto == 19){
							_resultado = $("#fechaensayo_fin_" + _item).val();
						}
					}
					else{
						_resultado = $("#resultado_" + _pos_objeto + '_' + _item).val();
					}

				// Grabando resultados
					$.post( "apis/backend.php", { accion: "grabar_CierreResultados_AnalisisResultados", pos_objeto: _pos_objeto, id_detalle: _id_detalle, resultado: _resultado },
	            function( data ) {
	              if(data.estado == 1){
	              	f_VerificaResultadosCompletos(_item);
	              }
	              else{
	              	alert("Ocurrió un error al momento de grabar el Resultado");
	              }

	            }, "json");
			}

			function f_CierreResultados(){
		  	var c = 1;
		  	var html = '';
		  	var id_cabecera = 0;
		  	var tiene_checks = 0;
		  	var arr_selected = [];
		  	var _items = '';

		  	// Recorriendo los checks
			  	while (c <= 1000){
			  		// Determina su el objeto existe
			  			html = $("#rowchk_" + c);

			  			if (html.html() != undefined){
			  				// Validando los promedios que tienen Check
			  					if (html.prop('checked')){
			  						id_cabecera = $("#rowchk_cierre_" + c).val();

			  						// Guarda el Id del check
			  							arr_selected.push(id_cabecera);

			  							_items += c + '|';

			  						tiene_checks = 1;
			  					}
			  			}

			  		c ++;
			  	}

			  	_items = _items.substring(0, _items.length - 1);

			  // Validando si no se ha seleccionado ningún check
			  	if (tiene_checks == 0){
			  		alert("No ha seleccionado ningún check de Cierre.");

			  		return;
			  	}

  			// Guardando cierres
			  	var _html = '';
			  	var a = 0;
			  	var arr_cierre = '';
			  	var pos = 0;

	  			f_LoadingCierre(1);


	  			$.post( "apis/backend.php", { accion: "cierre_CierreResultados_AnalisisResultados", arr_idmuestras: arr_selected },
	          function( data ) {
	            if(data.estado == 1){
	            	// Actualizando columnas de Cierre
	            		arr_cierre = _items.split('|');

	            		while (a < arr_cierre.length){
	            			pos = arr_cierre[a];

	            			// Campo 1
		            			_html = '<u style="color: #FF5F5D; cursor: pointer;" onclick="f_Reabrir(' + pos + ', ' + arr_selected[a] + ')">Reabrir</u>';

		            			$("#td_cierre_1_" + pos).html(_html);

	            			// Campo 2
		            			_html = data.fechahora_registro;

		            			$("#td_cierre_2_" + pos).html(_html);

	            			// Campo 3
		            			_html = data.usuario_registro;

		            			$("#td_cierre_3_" + pos).html(_html);

	            			// Campo 4
		            			_html = '<img src="' + '<?php echo $img_IE; ?>' + '" class="rounded" style="width: 30px; cursor: pointer;" onclick="f_PrintDocumentosCliente(' + "'" + data.id_x.split('|')[a] + "'" + ')">';

		            			$("#td_cierre_4_" + pos).html(_html);

	            			a ++;
	            		}
	            }
	            else{
	              alert("Ocurrió un error al momento de realizar el Cierre de Resultados.");
	            }

	            f_LoadingCierre(0);

	          }, "json");
		  }

		  function f_Reabrir(_item_cierre, _id_detalle){
		  	// Validando cierre
		  		if (!confirm("¿Está seguro de Reabrir el cierre seleccionado?")){
		  			return;
		  		}

		  	// Reabrir Cierre
		  		$.post( "apis/backend.php", { accion: "reabrir_CierreResultados_AnalisisResultados", id_detalle: _id_detalle },
	          function( data ) {
	            if(data.estado == 1){
								// Habilita los objetos de Cierre
									var _html = '<input id="rowchk_' + _item_cierre + '" class="form-check-input" style="transform: scale(1.5);" type="checkbox" checked>';

									$("#td_cierre_1_" + _item_cierre).html(_html);
									$("#td_cierre_2_" + _item_cierre).html('');
									$("#td_cierre_3_" + _item_cierre).html('');
									$("#td_cierre_4_" + _item_cierre).html('');
	            }
	            else{
	              alert("Ocurrió un error al momento de reabrir el registro seleccionado.");
	            }

	          }, "json");

		  }
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 400){
					
				}
			}

			$(document).ready(function() {
	  		$("#filtro_anho, #filtro_mes").select2();

	  		$("#select2-filtro_anho-container").css('background-color', '#0d2b68');
	  		$("#select2-filtro_anho-container").css('color', '#ffffff');

	  		$("#select2-filtro_mes-container").css('background-color', '#0d2b68');
	  		$("#select2-filtro_mes-container").css('color', '#ffffff');
	  	});

		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>