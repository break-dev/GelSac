<?php

	session_start();

	include('cnx/cnx.php');
	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

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
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

		<title><?php echo $nom_app; ?> | Operaciones - Resumen de Procesos</title>

		<script type="text/javascript">
			var is_mobile = 0;
			var color_selected = '';
		</script>

		<style>
			.table-container{
				max-width: 100%;
				overflow-x: scroll;
			}

			.sticky{
				position: sticky;
				left: 0;
				z-index: 1000;
			}

			.sticky-2{
				position: sticky;
				left: 35;
				z-index: 1000;
			}

			.sticky-3{
				position: sticky;
				left: 35;
				z-index: 1000;
			}

			.sticky-4{
				position: sticky;
				left: 140;
				z-index: 1000;
			}

			.sticky-5{
				position: sticky;
				left: 270;
				z-index: 1000;
			}

			.sticky-2h{
				position: sticky;
				left: 35;
				z-index: 1000;
			}

			.sticky-3h{
				position: sticky;
				left: 140;
				z-index: 1000;
			}

			.sticky-4h{
				position: sticky;
				left: 270;
				z-index: 1000;
			}
		</style>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<div class="row">
					<!-- Menús principales -->
					<div id="div_menu1" class="col-md-1 col-sm-1 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #DEDEDE;">
						
					</div>

					<div class="col-md-11 col-sm-11 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
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
												<input id="fecha_inicio" type="date" class="form-control" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>">

												<input id="fecha_fin" type="date" class="form-control" style="text-align: center; margin-left: 5px; font-size: 14px;" value="<?php echo $g_date; ?>">
											</div>
										</div>
									</div>

									<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Lote</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<input id="filtro_lote" type="text" class="form-control" style="font-size: 14px;">
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding-left: 30px; margin-top: 5px; margin-bottom: 10px; font-size: 13px;">
									<div class="col-md-10 col-sm-10 col-xs-12">
										<button class="btn btn-secondary" type="button" onclick="f_LoadResultados();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; background-color: #cfaa41; margin-bottom: 10px;">
				              <i class="bi bi-search"></i> <b>Ejecutar Búsqueda</b>
			            	</button>
			            </div>

			            <div class="col-md-2 col-sm-2 col-xs-12">
			            	<button class="btn btn-success" type="button" onclick="f_ExportToExcel();" style="width: 100%; color: #ffffff; font-size: 14px; margin-top: -8px; margin-bottom: 12px;">
				              <b>Exportar a Excel</b>
				            </button>
				          </div>
								</div>
							</div>

							<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
								<div class="row" style="padding: 20px;">
									<div class="col-md-7 col-sm-7 col-xs-12">
										<div class="d-flex">
											<h5>Operaciones - Resumen de Procesos</h5>

											<div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Cargando datos...</label>
											</div>

											<div id="wt_saving" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Grabando datos...</label>
											</div>
										</div>
									</div>
								</div>

								<div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
									<hr style="border-color: #D9D9D9;"/>
								</div>

								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%; margin-bottom: 100px;">
									<div class="table-container">
										<table class="table table-bordered table-hover">
						        	<thead>
						        		<tr style="font-size: 12px;">
						        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
						        				N°
						        			</th>

						        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Fecha Hora Ingreso Planta
						        			</th>

						        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 130px;">
						        				Cód. Lote
						        			</th>

						        			<th rowspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Peso Neto Lote (Tn)
						        			</th>

						        			<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 120px;">
						        				Descarga
						        			</th>

						        			<?php

						        			// Obteniendo lista de procesos
						        				$count_procesos = 0;
						        				$arr_procesos = '';

						        				$q_procesos = "SELECT Id,
						        															abv,
						        															descripcion,
						        															depende_de
						        												 FROM tb_procesos
						        												WHERE id_procesosarea = 2
						        													AND estado = 'A'
						        											 ORDER BY orden";

      											if ($res_procesos = mysqli_query($enlace, $q_procesos)){
															if (mysqli_num_rows($res_procesos) > 0) {
																$count_procesos = mysqli_num_rows($res_procesos);

																while($row_procesos = mysqli_fetch_array($res_procesos)){
																	$arr_procesos .= $row_procesos["Id"].';'.$row_procesos["abv"].';'.$row_procesos["descripcion"].'|';
																}
															}
														}

														$arr_procesos = substr($arr_procesos, 0, -1);
														$arr_procesos_x = $arr_procesos;

						        			?>

						        			<th colspan="<?php echo ($count_procesos * 3) ?>" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
						        				Información Procesos
						        			</th>
						        		</tr>

						        		<tr style="font-size: 12px;">
						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Peso Inicial
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px;">
						        				Peso Final
						        			</th>

						        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
						        				Tiempo Efectivo
						        			</th>

						        			<?php

						        			// Coloca las cabeceras de procesos
						        				$p = 0;
						        				$arr_procesos = explode('|', $arr_procesos);

						        				while ($p < count($arr_procesos)){
						        					?>

						        					<th colspan="3" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				<?php

								        				echo explode(';', $arr_procesos[$p])[2];

								        				?>
								        			</th>

						        					<?php

						        					$p ++;
						        				}

						        			?>

						        		</tr>

						        		<tr style="font-size: 12px;">
						        			<?php

							        		// Coloca las cabeceras de Inicio / Fin
						        				$p = 0;

						        				while ($p < count($arr_procesos)){
						        					?>

						        					<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 150px;">
						        						Equipo
								        			</th>

						        					<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
						        						Inicio
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 160px;">
						        						Fin
								        			</th>

						        					<?php

						        					$p ++;
						        				}

						        			?>
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
		<div class="modal fade" id="modal_SetColor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_SetColorLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_SetColor_content" class="modal-content" style="margin-top: 15%;">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5">Asigne un color: </h1>
		        <h1 class="modal-title fs-5" id="modal_SetColorLabel" style="margin-left: 5px; font-weight: bold;"></h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="d-flex justify-content-center" style="padding: 5px;">
							<div class="color-box" data-color="#99BFF2" style="background-color: #99BFF2; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer; margin-right: 5px;"></div>
							<div class="color-box" data-color="#6BFA7E" style="background-color: #6BFA7E; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer; margin-right: 5px;"></div>
							<div class="color-box" data-color="#FADD5F" style="background-color: #FADD5F; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer; margin-right: 5px;"></div>
							<div class="color-box" data-color="#FF7F82" style="background-color: #FF7F82; border: solid; border-width: 2px; border-color: #ffffff; border-radius: 7px; width: 80px; height: 40px; cursor: pointer;"></div>
						</div>
		      </div>

		      <input id="hd_setcolor_item" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarColor();">Confirmar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Select2 -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
					$("#nv_titulo").html('| Operaciones - Resumen de Procesos');

				// Carga Filtros

				// Carga el detalle de información
					f_LoadResultados();
			}

		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			function f_SetSelect2(){
			  $('.select_datos').select2({
			    theme: "bootstrap-5",
			    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			    placeholder: $( this ).data( 'placeholder' ),
			    allowClear: true
				}).on('select2:open', function() {
				  $(this).data('select2').$dropdown.find(':input.select2-search__field').focus();
				});

				$('.select2-container').css('z-index', 1);
			}
		</script>

		<!-- Seteando lógica de filtrado -->
		<script type="text/javascript">

		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
        var fecha_inicio = $("#fecha_inicio").val();
        var fecha_fin = $("#fecha_fin").val();
        var filtro_lote = $("#filtro_lote").val();

        f_LoadingResumen(1);

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_Operaciones_ProcesosResumen", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, cod_lote: filtro_lote, arr_procesos: '<?php echo $arr_procesos_x; ?>' }, 
          function( data ) {
            if(data.estado == 1){
              $("#tbl_detalle").html(data.html);
            }

            f_LoadingResumen(0);

          }, "json");
    	}

    	function f_ExportToExcel(){
        // Obteniendo filtros
	        var fecha_inicio = $("#fecha_inicio").val();
	        var fecha_fin = $("#fecha_fin").val();
	        var filtro_lote = $("#filtro_lote").val();

        window.location.href = "export_to_excel/operaciones_procesosplanta_resumen.php?fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin+"&filtro_lote="+filtro_lote;
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_LoadingResumen(_is_show){
				if (_is_show == 1){
					$("#wt_resumen").show();
				}
				else{
					$("#wt_resumen").hide();
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">

		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');

					$("#modal_addcliente_content, #modal_addconductor_content, #modal_addzonaorigen_content, #modal_addacompanante_content").css('margin-top', '10px');
				}
			}

		</script>

		<!-- Funcion Default -->
		<script type="text/javascript">
			
		</script>
	</body>
</html>