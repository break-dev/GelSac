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
		<link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">

  	<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Administración de Conductores</title>

		<script type="text/javascript">
			let loaded_img_TC_1 = '';
			let loaded_img_TC_2 = '';
			let img_selected_TC_1 = '0';
			let img_selected_TC_2 = '0';

			let loaded_img_TP_1 = '';
			let loaded_img_TP_2 = '';
			let img_selected_TP_1 = '0';
			let img_selected_TP_2 = '0';
		</script>
	</head>

	<body class="bg-light" onload="f_SetDimension(); f_Init();" style="zoom: 80%;">
		<div class="container-fluid">
			<div class="row">
				<!-- Llamando a Navbar -->
				<?php echo $navbar_maintop; ?>

				<!-- Modal (Menú Lateral) -->
	      <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
	        <div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important;">
	          <div class="modal-content">
	            <div class="modal-header">
	              <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
	              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>
	            <div  class="modal-body" style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0px !important;">
	              <ul class="list-unstyled">
	                <div id="div_menu1"></div>
	              </ul>
	            </div>
	          </div>
	        </div>
	      </div>

	      <!-- Modal (Filtro Lateral) -->
	      <div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
	        <div class="modal-dialog modal-right" style="margin-top: 0px !important; margin-left: 0px !important;">
	          <div class="modal-content">
	            <div class="modal-header">
	              <h5 class="modal-title" id="menuModalLabel">Filtro de Opciones</h5>
	              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>
	            <div  class="modal-body" style="padding: 0px !important;">
	              
								<div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
									<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
										<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
											<div class="row" style="padding-left: 10px; padding-right: 10px;">
												<h6 style="font-size: 14px;">Por Documento / Nombres</h6>
											</div>

											<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
												<hr style="border-color: #D9D9D9;"/>
											</div>

											<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
												<select id="filtro_listatipo" class="form-select" style="text-align: left; font-size: 14px; width: 50%;" onchange="f_CleanTxtTipo(); f_LoadResultados();">
													<option selected value="">Elija una opción...</option>
													<option value="1">Documento</option>
													<option value="2">Nombres</option>
												</select>

												<input id="filtro_tipo" type="text" class="form-control" style="font-size: 14px; margin-left: 5px;" onblur="f_LoadResultados();">
											</div>
										</div>
									</div>
								</div>

	            </div>
	          </div>
	        </div>
	      </div>

				<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px; width: 100%;">
							<div class="row text-end" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
								<h5>
									Filtros
									<a role="button" data-bs-toggle="modal" data-bs-target="#filtroModal">
										<i class="bi bi-funnel" style="color: #000; font-size: 30px"></i>
									</a>
								</h5>
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							
						</div>

						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; width: 100%;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-10 col-sm-10 col-xs-10">
									<h5>Resumen de Conductores</h5>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-primary" type="button" onclick="f_AdminConductores('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
			              <b> + Nuevo Conductor</b>
			            </button>
								</div>

								
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div id="div_resumen" class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll;">
								<table class="table table-bordered table-striped table-hover">
				        	<thead>
				        		<tr style="font-size: 14px;">
				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
				        				N°
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Tipo Documento
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				N° Documento
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				N° Licencia
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
				        				Nombres
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 350px;">
				        				Dirección
				        			</th>

				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				DNI
				        			</th>

				        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Licencia
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Estado
				        			</th>

				        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
				        				Acción
				        			</th>
				        		</tr>

				        		<tr style="font-size: 14px;">
				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Anverso
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Reverso
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Anverso
				        			</th>

				        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
				        				Reverso
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

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_addconductor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addconductorLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addconductorLabel">Nuevo Cliente</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Tipo Documento:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="cliente_tipodocumento" class="form-select" style="text-align: left;">
									<option selected value="">Elija una opción...</option>

									<?php

									$q_tipodocumento = "SELECT Id,
                            								 descripcion
						                            FROM tbconfig_tipodocumento
						                           WHERE estado = 'A'
						                          	 AND is_conductor = 1";

					        if ($res_tipodocumento = mysqli_query($enlace, $q_tipodocumento)){
					          if (mysqli_num_rows($res_tipodocumento) > 0) {
					            while($row_tipodocumento = mysqli_fetch_array($res_tipodocumento)){
					              ?>

					              <option value="<?php echo $row_tipodocumento["Id"]; ?>"><?php echo $row_tipodocumento["descripcion"]; ?></option>

					              <?php
					            }
					          }
					        }

									?>

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								N° Documento:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="cliente_documento" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente();">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								N° Licencia:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="cliente_licencia" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Nombres: <img id="wt_razonsocial2" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="cliente_razonsocial" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Dirección:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="cliente_direccion" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #816951; color: #ffffff;">
								DNI
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Anverso:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<table style="width: 100%;">
									<tr>
										<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
											<img id="img_TC_1" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
										</td>

										<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
											<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(1);">
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Reverso:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<table style="width: 100%;">
									<tr>
										<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
											<img id="img_TC_2" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
										</td>

										<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
											<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(2);">
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #816951; color: #ffffff;">
								Licencia
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Anverso:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<table style="width: 100%;">
									<tr>
										<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
											<img id="img_TP_1" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
										</td>

										<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
											<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(3);">
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Reverso:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<table style="width: 100%;">
									<tr>
										<td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">
											<img id="img_TP_2" class="imagen" src="" style="width: 100px; cursor: pointer; display: none;" onclick="f_ShowImg(this.src);">
										</td>

										<td style="border: solid; border-width: 1px; border-color: #ffffff; vertical-align: middle; text-align: center; font-size: 12px; width: 50px; background-color: #BFBFBF; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
											<img src="<?php echo $img_camara; ?>" style="width: 30px; cursor: pointer;" onclick="f_AddImg(4);">
										</td>
									</tr>
								</table>
							</div>
						</div>
		      </div>

		      <input id="hd_idconductor" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer">
		      	<div id="wt_grabarconductor" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary wt_grabarconductor_button" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary wt_grabarconductor_button" onclick="f_GrabarConductor();">Grabar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_showimg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_showimgLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_showimg_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-5" id="modal_showimgLabel">Título</h1>

		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<img id="img_modal" alt="">
						</div>
					</div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>

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
					$("#nv_titulo").html('| Administración de Conductores');

				// Cargando listas generales

				// Carga el detalle de información
					f_LoadResultados();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
        var _html = '';
        var d = 1;

        // var cod_condicion = $("#filtro_condicion").val();
        var cod_tipo = $("#filtro_listatipo").val();
        var txt_tipo = $("#filtro_tipo").val().trim();

        if (txt_tipo.length > 0){
      		if (cod_tipo == null){
	          alert("Debe indicar si la búsqueda es por Documento o Razón Social.");

	          return;
	        }
	        if (cod_tipo.length == 0){
	          alert("Debe indicar si la búsqueda es por Documento o Razón Social.");

	          return;
	        }
        }

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        var arr_creditos = '';
        var arr_descuentos = '';
        var c = 0;

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_listaconductores_All", cod_tipo: cod_tipo, txt_tipo: txt_tipo }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.TIPO_DOCUMENTO;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.dni_licencia;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.licencia_conducir;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.nombres;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.DOMICILIO;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

	                if (val.img_dni_1.trim().length > 0){
	                	_html += '		<img src="' + '<?php echo $img_view; ?>' +'" style="width: 40px; cursor: pointer;" onclick="f_ShowImg(' + "'images/conductores/" + val.img_dni_1 + "'" + ');">';
	                }

                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

	                if (val.img_dni_2.trim().length > 0){
	                	_html += '		<img src="' + '<?php echo $img_view; ?>' +'" style="width: 40px; cursor: pointer;" onclick="f_ShowImg(' + "'images/conductores/" + val.img_dni_2 + "'" + ');">';
	                }

                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

	                if (val.img_licencia_1.trim().length > 0){
	                	_html += '		<img src="' + '<?php echo $img_view; ?>' +'" style="width: 40px; cursor: pointer;" onclick="f_ShowImg(' + "'images/conductores/" + val.img_licencia_1 + "'" + ');">';
	                }

                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';

	                if (val.img_licencia_2.trim().length > 0){
	                	_html += '		<img src="' + '<?php echo $img_view; ?>' +'" style="width: 40px; cursor: pointer;" onclick="f_ShowImg(' + "'images/conductores/" + val.img_licencia_2 + "'" + ');">';
	                }

                _html += '  </td>';

                // Setea el Estado del registro
                  if (val.estado == 'I'){
                    bk_color = '#E6A50D';
                    estado = 'Inactivo';
                    href_estado = 'Activar';
                    href_color = '#44803F';
                    href_icon = 'bi bi-node-plus';
                  }
                  else{
                    bk_color = '#44803F';
                    estado = 'Activo';
                    href_estado = 'Inactivar';
                    href_color = '#E6A50D';
                    href_icon = 'bi bi-node-minus';
                  }

                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; background-color:' + ((val.estado == 'I') ? '#E6A50D' : '#44803F') + '; color: #ffffff;">';
                  _html += '      ' + ((val.estado == 'A') ? 'Activo' : 'Inactivo');
                  _html += '  </td>';

                // Agregando acciones
                  _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';

                  _html += '      <a class="success" href="javascript: f_AdminConductores(' + d + ', ' + val.Id + ', ' + val.id_tipodocumento + ", '" + val.dni_licencia + "', '" + val.licencia_conducir	+ "', '" + val.nombres + "', '" + val.DOMICILIO + "', '" + val.img_dni_1 + "', '" + val.img_dni_2 + "', '" + val.img_licencia_1 + "', '" + val.img_licencia_2 + "'" + ')"><i class="bi bi-pencil-square"></i>';
                  _html += '          <font style="color: #337ab7;"> Editar</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_CambiarEstado(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                  _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_EliminarConductor(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                  _html += '          <font style="color: #F20505;"> Eliminar</font>';
                  _html += '      </a>';

                  _html += '  </td>';

                _html += '</tr>';

                d += 1;
              });
            }
            else{
              // alert("No se encontraron resultados.");
            }

            $("#tbl_detalle").html(_html);

          }, "json");
    	};

    	function f_AdminConductores(_item, _id_conductor, _id_tipodocumento, _dni_licencia, _licencia_conducir, _nombres, _domicilio, img_TC_1, img_TC_2, img_TP_1, img_TP_2){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Conductor:<br>"<b>' + _dni_licencia + ' - ' + _nombres + '</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Nuevo Conductor";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_addconductorLabel").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addconductor');

	        if (tipo != 'N'){
            $("#hd_idconductor").val(_id_conductor);
            $("#cliente_tipodocumento").val(_id_tipodocumento);
            $("#cliente_documento").val(_dni_licencia);
            $("#cliente_licencia").val(_licencia_conducir);
		        $("#cliente_razonsocial").val(f_CleanInjection(_nombres));
		        $("#cliente_direccion").val(f_CleanInjection(_domicilio));

		        if (img_TC_1.trim().length > 0){
		        	$("#img_TC_1").attr('src', 'images/conductores/' + img_TC_1);

		        	$("#img_TC_1").show();

		        	loaded_img = 'images/conductores/' + img_TC_1;
		        }
		        else{
		        	$("#img_TC_1").attr('src', '');

		        	$("#img_TC_1").hide();
		        }

		        if (img_TC_2.trim().length > 0){
		        	$("#img_TC_2").attr('src', 'images/conductores/' + img_TC_2);

		        	$("#img_TC_2").show();

		        	loaded_img = 'images/conductores/' + img_TC_2;
		        }
		        else{
		        	$("#img_TC_2").attr('src', '');

		        	$("#img_TC_2").hide();
		        }

		        if (img_TP_1.trim().length > 0){
		        	$("#img_TP_1").attr('src', 'images/conductores/' + img_TP_1);

		        	$("#img_TP_1").show();

		        	loaded_img = 'images/conductores/' + img_TP_1;
		        }
		        else{
		        	$("#img_TP_1").attr('src', '');

		        	$("#img_TP_1").hide();
		        }

		        if (img_TP_2.trim().length > 0){
		        	$("#img_TP_2").attr('src', 'images/conductores/' + img_TP_2);

		        	$("#img_TP_2").show();

		        	loaded_img = 'images/conductores/' + img_TP_2;
		        }
		        else{
		        	$("#img_TP_2").attr('src', '');

		        	$("#img_TP_2").hide();
		        }
			    }
			    else{
			    	$("#hd_idconductor").val(0);
			    	$("#cliente_tipodocumento").val('');
		        $("#cliente_documento").val('');
            $("#cliente_licencia").val('');
		        $("#cliente_razonsocial").val('');
		        $("#cliente_direccion").val('');
		        $("#img_TC_1").attr('src', '');
		        $("#img_TC_1").hide();
		        $("#img_TC_2").attr('src', '');
						$("#img_TC_2").hide();
		        $("#img_TP_1").attr('src', '');
		        $("#img_TP_1").hide();
		        $("#img_TP_2").attr('src', '');
						$("#img_TP_2").hide();

						loaded_img_TC_1 = '';
						loaded_img_TC_2 = '';
						img_selected_TC_1 = '0';
						img_selected_TC_2 = '0';

						loaded_img_TP_1 = '';
						loaded_img_TP_2 = '';
						img_selected_TP_1 = '0';
						img_selected_TP_2 = '0';
		   		}
    	}

	    function f_AddImg(_item){
			  var input = document.createElement('input');
			  input.type = 'file';
			  input.accept = 'image/*';
			  input.onchange = function(event) {
			    var file = event.target.files[0];
			    var timer;

			    var checkFileLoaded = function() {
			      if (file) {
			        var reader = new FileReader();
			        reader.onload = function(e) {
			        	if (_item == 1){
			        		var imagen = document.getElementById('img_TC_1');
			        	}

			        	if (_item == 2){
			        		var imagen = document.getElementById('img_TC_2');
			        	}

			        	if (_item == 3){
			        		var imagen = document.getElementById('img_TP_1');
			        	}

			        	if (_item == 4){
			        		var imagen = document.getElementById('img_TP_2');
			        	}

			          imagen.src = e.target.result;
			        };

			        reader.readAsDataURL(file);

			        if (_item == 1){
			        	loaded_img_TC_1 = file;

			        	$("#img_TC_1").show();

			        	img_selected_TC_1 = 1;
			        }

			        if (_item == 2){
			        	loaded_img_TC_2 = file;

			        	$("#img_TC_2").show();

			        	img_selected_TC_2 = 1;
			        }

			        if (_item == 3){
			        	loaded_img_TP_1 = file;

			        	$("#img_TP_1").show();

			        	img_selected_TP_1 = 1;
			        }

			        if (_item == 4){
			        	loaded_img_TP_2 = file;

			        	$("#img_TP_2").show();

			        	img_selected_TP_2 = 1;
			        }
			      }
			      else{
			      	alert('No se seleccionó ningún archivo.');
			      }
			    };

			    timer = setTimeout(checkFileLoaded, 1000); // Espera 1 segundo antes de verificar

			    input.addEventListener('click', function() {
			      clearTimeout(timer);
			    });
			  };
			  input.click();
			}

	    function f_ShowImg(_id_img){
	      // Limpiando objeto img
	        $("#img_modal").attr('src', '');

	      // Obtiene el SRC si lo tuviera
	        var modalImg = document.getElementById('img_modal');
			    modalImg.src = _id_img;

	      // Abre modal
	      	f_OpenModal('modal_showimg');
	    }
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			function f_CleanTxtTipo(){
				var cod_tipo = $("#filtro_listatipo").val();

				if (cod_tipo == null){
          $("#filtro_tipo").val('');

          return;
        }

        if (cod_tipo.length == 0){
          $("#filtro_tipo").val('');

          return;
        }
			}

			function f_GetListaTipoDocumento(_is_juridico){
				var _html = '<option selected value="">Elija una opción...</option>';
				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				if (_is_juridico == 0){
					if ($("#cliente_tipocliente").val() == 2){
						_is_juridico = 1;
					}
				}

				$.post( "apis/backend.php", { accion: "get_listatipodocumento" }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.res, function( key, val ) {
								_html += '<option value="' + val.Id + '" ' + ((_is_juridico == 1) ? ((val.Id == 2) ? 'selected' : '') : ((val.Id == 1) ? 'selected' : '')) + '>' + val.descripcion + '</option>';
								_html += '<option value="x" style="font-size: 6px;" disabled></option>';
							});

							$("#cliente_tipodocumento").html(_html);
						}
						else{
							$("#cliente_tipodocumento").html('');
						}

					}, "json");
			}

			function f_GetInfoCliente(){
				var is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
					var documento = $("#cliente_documento").val();
					var arr_response = '';

					// Limpiando objetos
						$("#cliente_razonsocial").val('');
          	// $("#cliente_direccion").val('');
						$("#wt_razonsocial2").hide();

					// Obteniendo información
						if (documento.length == 8 || documento.length == 11){
							$("#wt_razonsocial2").show();

							$.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
		            function( data ) {
		            	if (data.estado == 1){
		            		arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

		            		if (is_ruc == 1){
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	// $("#cliente_direccion").val(arr_response[4].split(':')[1].trim());
			            	}
			            	else{
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	// $("#cliente_direccion").val('');
			            	}
		            	}
		            	else{
		            		$("#cliente_razonsocial").val('NO ENCONTRADO');
		              	// $("#cliente_direccion").val('');
		            	}

		            	$("#wt_razonsocial2").hide();

		            }, "json");
						}
			}

			function f_LoadingGrabarConductor(_is_show){
				if (_is_show == 1){
					$("#wt_grabarconductor").show();

					$(".wt_grabarconductor_button").prop('disabled', true);
				}
				else{
					$("#wt_grabarconductor").hide();

					$(".wt_grabarconductor_button").prop('disabled', false);
				}
			}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Graba información temporal (onblur).
				function f_GrabarConductor(){
					// Recupera variables
						var id_conductor = $("#hd_idconductor").val();
						var modo_grabar = $("#hd_modograbar").val();

            var tipo_documento = f_CleanInjection($("#cliente_tipodocumento").val());
            var documento = f_CleanInjection($("#cliente_documento").val());
            var licencia = f_CleanInjection($("#cliente_licencia").val());
            var razon_social = f_CleanInjection($("#cliente_razonsocial").val());
            var domicilio = f_CleanInjection($("#cliente_direccion").val());

          // Validando datos
            if (tipo_documento == null){
              alert("Debe seleccionar el Tipo de Documento.");

              return;
            }
            if (tipo_documento.length == 0){
              alert("Debe seleccionar el Tipo de Documento.");

              return;
            }

            if (documento == null){
              alert("Debe ingresar el Documento.");

              return;
            }
            if (documento.length == 0){
              alert("Debe ingresar el Documento.");

              return;
            }

            if (licencia == null){
              alert("Debe ingresar la Licencia.");

              return;
            }
            if (licencia.length == 0){
              alert("Debe ingresar la Licencia.");

              return;
            }

            if (razon_social == null){
              alert("Debe ingresar la Razón Social.");

              return;
            }
            if (razon_social.length == 0){
              alert("Debe ingresar la Razón Social.");

              return;
            }

		      // Seteando parámetros de Imagen
		        var formData = new FormData();
	    			formData.append('img_TC_1', loaded_img_TC_1);
	    			formData.append('img_TC_2', loaded_img_TC_2);
	    			formData.append('img_TP_1', loaded_img_TP_1);
	    			formData.append('img_TP_2', loaded_img_TP_2);

	  			// Seteando los demás parámetros
	    			formData.append('accion', 'grabar_conductor');
			      formData.append('modo_grabar', modo_grabar);
			      formData.append('id_conductor', id_conductor);
			      formData.append('id_tipodocumento', tipo_documento);
			      formData.append('dni_licencia', documento);
			      formData.append('licencia_conducir', licencia);
			      formData.append('conductor_nombres', razon_social);
			      formData.append('domicilio', domicilio);
			      formData.append('img_selected_TC_1', img_selected_TC_1);
			      formData.append('img_selected_TC_2', img_selected_TC_2);
			      formData.append('img_selected_TP_1', img_selected_TP_1);
			      formData.append('img_selected_TP_2', img_selected_TP_2);

	        // Grabando Datos
			      f_LoadingGrabarConductor(1);

			      $.ajax({
			        url: 'apis/backend.php',
			        type: 'POST',
			        data: formData,
			        contentType: false,
			        processData: false,
			        success: function( data ) {
			          if (data.estado == 2){
	                alert("El documento ingresado ya fue registrado anteriormente.\n\nPor favor verificar");
	              }
	              else{
	                if(data.estado == 1){
	                	f_LoadResultados();

	                	f_cerrarModal('modal_addconductor');
	                }
	                else{
	                  alert("Ocurrió un error al momento de grabar el Conductor");
	                }
	              }

	              f_LoadingGrabarConductor(0);
			        }
			      });

	          // // Grabando Datos
	          //   $.post( "apis/backend.php", { accion: "grabar_conductor", modo_grabar: modo_grabar, id_conductor: id_conductor, dni_licencia: documento, conductor_nombres: razon_social },
	          //     function( data ) {
	          //       if (data.estado == 2){
	          //         alert("El documento ingresado ya fue registrado anteriormente.\n\nPor favor verificar");

	          //         return;
	          //       }
	          //       else{
	          //         if(data.estado == 1){
	          //         	f_LoadResultados();

	          //         	f_cerrarModal('modal_addconductor');
	          //         }
	          //         else{
	          //           alert("Ocurrió un error al momento de grabar el Conductor");
	          //         }
	          //       }

	          //     }, "json");
				}

			// Cambiar estado de registros
        function f_CambiarEstado(_Estado, _id_registro){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " el Conductor seleccionado?")){
            $.post( "apis/backend.php", { accion: "update_EstadoConductor", id_registro: _id_registro, estado: _Estado }, 
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de cambiar el estado del Conductor.");
                }

              }, "json");
          }
        };

      // Eliminar registros
        function f_EliminarConductor(_id_registro){
          if(confirm("¿Está seguro de eliminar el Conductor seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_Conductor", id_registro: _id_registro },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar el Conductor.");
                }
              }, "json");
          }
        };
		</script>

		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');
					$("#div_resumen").css('width', '100%');
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

		<script type="text/javascript">
			// Funciones Principales
				function f_LoadAnhos(){
					// Carga filtros de Periodo
						$.post( "apis/backend.php", { accion: "get_Anhos" }, 
							function( data ) {
								if(data.estado == 1){
									$("#filtro_anho").html(data.html);

									f_LoadMeses();
								}
								else{
									$("#filtro_anho").val('');
									$("#filtro_mes").val('');
								}

							}, "json");
				}

				function f_LoadMeses(){
					var _anho = $("#filtro_anho").val();

					// Carga filtros de Periodo
						$.post( "apis/backend.php", { accion: "get_Meses", anho: _anho}, 
							function( data ) {
								if(data.estado == 1){
									$("#filtro_mes").html(data.html);

									f_LoadDashboard();
								}
								else{
									$("#filtro_mes").val('');
								}

							}, "json");
				}

				function f_LoadDashboard(){
					$("#lbl_anho").html('Año: <b>' + $("#filtro_anho").val() + '</b>');
					$("#lbl_mes").html('Mes: <b>' + $("#filtro_mes option:selected").text() + '</b>');

					// Obteniendo filtros
						var filtro_anho = $("#filtro_anho").val();
						var filtro_mes = $("#filtro_mes").val();

					// Cargando el Chart Principal
						$("#chart_main").load("charts/chart_mainnps.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes);

					// Cargando Interacciones
						$.post( "apis/backend.php", { accion: "get_Interacciones", filtro_anho: filtro_anho, filtro_mes: filtro_mes }, 
							function( data ) {
								if(data.estado == 1){
									$("#int_1").html(data.totalitems_nps.split('|')[0]);
									$("#int_2").html(data.totalitems_nps.split('|')[1]);
									$("#int_3").html(data.totalitems_nps.split('|')[2]);
									$("#int_4").html(data.totalitems_nps.split('|')[3]);
									$("#int_5").html(data.totalitems_nps.split('|')[4]);
								}
								else{
									$("#int_1").val('');
									$("#int_2").val('');
									$("#int_3").val('');
									$("#int_4").val('');
									$("#int_5").val('');
								}

							}, "json");

						// Cargando Pies
							// Operaciones Ventanilla
								$("#chart_int1").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 1);

							// Asesores de Negocio
								$("#chart_int2").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 2);

							// Call Center
								$("#chart_int3").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 3);

							// Agentes Corresponsales
								$("#chart_int4").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 4);

							// App Móvil
								$("#chart_int5").load("charts/chart_interacciones.php?filtro_anho=" + filtro_anho + "&filtro_mes=" + filtro_mes + "&interaccion=" + 5);
				}
		</script>
	</body>
</html>