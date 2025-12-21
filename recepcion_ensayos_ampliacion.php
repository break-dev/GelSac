<?php

	session_start();

	include('global/variables.php');
	include('global/auxiliares.php');

	if(!isset($_SESSION["Id"])){
    header('Location: index.php');
  }

  $is_vistatouch = $_SESSION["vista_touch"];

  // Recuperando parámetros
  	$id_recepcion_x = $_GET["x"]; // Id de Recepción de Origen
  	$id_recepcion_r = $_GET["r"]; // Id de Recepción de la Ampliación
  	$id_md5 = md5($id_recepcion_r);
  	$arr_muestras = $_GET["d"];

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

		<title><?php echo $nom_app; ?> | Ampliación de Análisis</title>

		<!-- Desactivar las flechas de Arriba y Abajo de los input (number) -->
    <style type="text/css">
      /*Chrome*/
      input[type="number"]::-webkit-outer-spin-button,
      input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      /*Firefox*/
      input[type="number"] {
      -moz-appearance: textfield;
      }
      input[type="number"]:hover,
      input[type="number"]:focus {
        -moz-appearance: number-input;
      }
      /*Other*/
      input[type=number]::-webkit-inner-spin-button,
      input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
    </style>

		<script type="text/javascript">
			let id_recepcion_origen = <?php echo $id_recepcion_x; ?>;
			let id_recepcion = <?php echo $id_recepcion_r; ?>;
			let id_md5 = '<?php echo $id_md5; ?>';
			let arr_muestras = '<?php echo $arr_muestras; ?>';
			let cab_codinternopreliminar_x = '';
			let cab_fecharecepcion_x = '';
			let cab_horarecepcion_x = '';
			let cab_fechaentrega_x = '';
			let cab_horaentrega_x = '';
			let cab_clientedocumento_x = '';
			let cab_clienterazonsocial_x = '';
			let cab_entregadopor_x = '';
			let cab_celularareportar_x = '';
			let cab_sucursal_x = '';
			let cab_moneda_x = '';
			let cab_observacion_x = '';
			let cab_tienerecojo_x = 0;
			let cab_iscabecerasaved = 0;
			let cab_recibo_codigo_x = 0;
			let cli_tienecredito = 0;
			let cli_tienedscto = 0;
			let cod_tipocliente_x = 0;

			let idmuestra_selected = 0;
			let item_selected = 0;
			let muestra_selected = '';

			let det_nombremuestra_x = '';
			let det_pesomuestra_x = '';
			let det_estadomuestra_x = '';
			let det_envasemuestra_x = '';
			let det_tipomuestra_x = '';
			let det_ensayomuestra_x = '';
			let det_excesomuestra_x = '';
			let det_observacion_x = '';

			let filtro_precios_x = 1;

			let find_peso = 0; // Para obtener el Peso en línea
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

				<div class="col-md-11 col-sm-11 col-xs-11" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;">
					<div class="d-flex row">
						<div class="col-md-4 col-sm-4 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row bg-danger" style="font-size: 18px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; color: #ffffff; text-align: center; margin-bottom: 10px;">
								<label style="text-align: center;">Datos Principales</label>
							</div>

							<div id="div_cabecera_prev" class="row justify-content-center flex-wrap" style="text-align: center; background-color: #736E66; height: 650px; margin-top: -10px; padding-top: 280px;">
								<div class="row justify-content-center" style="text-align: center;">
									<button class="btn btn-info" type="button" onclick="f_GetIdRecepcion();" style="width: 50%; color: #ffffff; height: 40px; font-size: 14px;">
			              <b> + Nueva Recepción</b>
			            </button>
			          </div>

			          <div class="row justify-content-center" style="text-align: center; margin-top: -150px;">
									<button class="btn btn-danger" type="button" onclick="f_OpenAmpliacion();" style="width: 50%; color: #ffffff; height: 40px; font-size: 14px;">
			              <b> Ampliación</b>
			            </button>
			          </div>
							</div>

							<div id="div_cabecera" class="row" style="text-align: center; margin-top: 10px; display: none;">
								<div id="wt_cabecerarecepcion" class="" style="font-size: 12px; text-align: center; display: none;">
									<img src="<?php echo $img_waiting ?>" style="width: 20px;">
									<label style="font-style: italic;"> Grabando datos...</label>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Código:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<input id="cab_codigo" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; font-weight: bold;" disabled>
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Recepción:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<div class="row">
											<div class="col-md-7 col-sm-7 col-xs-7">
												<input id="cab_fecharecepcion" type="date" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; width: 95%;" value="<?php echo $g_date; ?>" onblur="f_GrabarCabeceraRecepcion_Temporal();" disabled>
											</div>

											<div class="col-md-5 col-sm-5 col-xs-5">
												<input id="cab_horarecepcion" type="time" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center;" value="<?php echo substr($g_time, 0, 5); ?>" onblur="f_GrabarCabeceraRecepcion_Temporal();" disabled>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Entrega:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<div class="row">
											<div class="col-md-7 col-sm-7 col-xs-7">
												<input id="cab_fechaentrega" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center; width: 95%;" onblur="f_GrabarCabeceraRecepcion_Temporal();" disabled>
											</div>

											<div class="col-md-5 col-sm-5 col-xs-5">
												<input id="cab_horaentrega" type="time" class="form-control col-md-12 col-xs-12" style="text-align: center;" onblur="f_GrabarCabeceraRecepcion_Temporal();" disabled>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Cliente: <img id="wt_razonsocial1" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
									</div>

									<div class="col-md-5 col-sm-5 col-xs-5">
										<input id="cab_clientedocumento" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_BuscarClientes();" onblur="f_GrabarCabeceraRecepcion_Temporal();">
									</div>

									<div class="col-md-1 col-sm-1 col-xs-1" style="margin-top: 2px; margin-right: 10px;">
										<img src="<?php echo $url_images.'search.png' ?>" class="" style="width: 35px; cursor: pointer;" onclick="f_GetListaBuscarClientes();" data-bs-toggle="modal" data-bs-target="#modal_buscarcliente">
									</div>

									<div class="col-md-1 col-sm-1 col-xs-1" style="margin-top: 2px;">
										<img src="<?php echo $url_images.'add_new.png' ?>" class="" style="width: 35px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modal_addcliente">
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 0px;">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<input id="cab_clienterazonsocial" type="text" class="form-control col-md-12 col-xs-12" onblur="f_GrabarCabeceraRecepcion_Temporal();" disabled>
									</div>

									<div id="div_creditocliente" class="col-md-12 col-sm-12 col-xs-12" style="display: none;">
										<div class="row col-md-12 col-sm-12 col-xs-12" style="font-size: 18px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #736E66; color: #ffffff; text-align: center; margin-left: 0px;">
											<label style="text-align: center; font-size: 14px;">Cliente con Crédito</label>
										</div>

										<div class="row col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #CCEA8D; margin-left: 0px;">
											<div class="row" style="font-size: 14px;">
												<div class="col-md-3 col-sm-3 col-xs-3" style="text-align: left;">
													Vigencia:
												</div>

												<div class="col-md-8 col-sm-8 col-xs-8" style="text-align: left;">
													<label id="cli_creditovigencia" style="font-weight: bold;">
														Del: 
													</label>
												</div>
											</div>

											<div class="row" style="font-size: 14px;">
												<div class="col-md-3 col-sm-3 col-xs-3" style="text-align: left;">
													Observación:
												</div>

												<div class="col-md-8 col-sm-8 col-xs-8" style="text-align: left;">
													<label id="cli_creditoobservacion" style="font-weight: bold;">
														
													</label>
												</div>
											</div>
										</div>
									</div>

									<div id="div_dsctocliente" class="col-md-12 col-sm-12 col-xs-12" style="display: none;">
										<div class="row col-md-12 col-sm-12 col-xs-12" style="font-size: 18px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #736E66; color: #ffffff; text-align: center; margin-left: 0px;">
											<label style="text-align: center; font-size: 14px;">Cliente con Descuento</label>
										</div>

										<div class="row col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #FFEC5C; margin-left: 0px;">
											<div class="row" style="font-size: 14px;">
												<div class="col-md-3 col-sm-3 col-xs-3" style="text-align: left;">
													Vigencia:
												</div>

												<div class="col-md-8 col-sm-8 col-xs-8" style="text-align: left;">
													<label id="cli_dsctovigencia" style="font-weight: bold;">
														Del: 
													</label>
												</div>
											</div>

											<div class="row" style="font-size: 14px;">
												<div class="col-md-3 col-sm-3 col-xs-3" style="text-align: left;">
													Dscto (%):
												</div>

												<div class="col-md-8 col-sm-8 col-xs-8" style="text-align: left;">
													<label id="cli_dsctoporc" style="font-weight: bold;">
														
													</label>
												</div>
											</div>

											<div class="row" style="font-size: 14px;">
												<div class="col-md-3 col-sm-3 col-xs-3" style="text-align: left;">
													Observación:
												</div>

												<div class="col-md-8 col-sm-8 col-xs-8" style="text-align: left;">
													<label id="cli_dsctoobservacion" style="font-weight: bold;">
														
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Entregado por:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<input id="cab_entregadopor" type="text" class="form-control obj_cab col-md-12 col-xs-12" style="text-transform: uppercase;" onblur="f_GrabarCabeceraRecepcion_Temporal();">
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Celular a reportar:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<input id="cab_celularareportar" type="text" class="form-control obj_cab col-md-12 col-xs-12" style="text-transform: uppercase;" onblur="f_GrabarCabeceraRecepcion_Temporal(); f_ActualizarTelefonoCliente();">
									</div>
								</div>

								<div class="row" style="padding: 5px;" hidden>
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Sucursal:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<select id="cab_sucursal" class="form-select obj_cab" style="text-align: left;" onchange="f_GrabarCabeceraRecepcion_Temporal();" required>

										</select>
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Moneda:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<select id="cab_moneda" class="form-select obj_cab" style="text-align: left;" onchange="f_GrabarCabeceraRecepcion_Temporal();" required>

											</select>
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										Observación:
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<textarea id="cab_observacion" type="text" class="form-control obj_cab col-md-12 col-xs-12" style="text-transform: uppercase;" rows="3" onblur="f_GrabarCabeceraRecepcion_Temporal();"></textarea>
									</div>
								</div>

								<div class="row" style="padding: 5px; margin-left: 5px; padding-bottom: 35px;">
									<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; text-align: left;">
										
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8" style="text-align: left;">
										<div class="form-check">
										  <input id="chk_recojo" class="form-check-input obj_cab" type="checkbox" value="" onchange="f_GrabarCabeceraRecepcion_Temporal();">
										  <label class="form-check-label" for="chk_recojo">
										    Incluir Recojo
										  </label>
										</div>
									</div>
								</div>

								<hr/>

								<div id="div_grabarcabecera_prev" class="row" style="padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 20px; text-align: center;">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<button id="btn_CancelarRecepcion" class="btn btn-danger" type="button" onclick="f_CancelarRecepcion();" style="width: 100%; color: #ffffff;">
				              <b>Cancelar</b>
				            </button>
				          </div>

				          <div class="col-md-9 col-sm-9 col-xs-9">
										<button class="btn btn-info" type="button" onclick="f_GrabarCabecera();" style="width: 100%; color: #ffffff;">
				              <b>Continuar</b>
				            </button>
				          </div>
			          </div>

			          <div id="div_grabarcabecera" class="row" style="padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 20px; text-align: center; display: none !important;">
			          	<div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 5px;">
				            <button id="btn_GrabarRecepcion" class="btn btn-primary" type="button" onclick="f_GrabarRecepcion();" style="width: 100%; color: #ffffff;">
				              <b>Confirmar Ampliación</b>
				            </button>
				          </div>

			          	<div class="col-md-12 col-sm-12 col-xs-12">
										<button id="btn_CancelarRecepcion" class="btn btn-danger" type="button" onclick="f_CancelarRecepcion();" style="width: 100%; color: #ffffff;">
				              <b>Cancelar</b>
				            </button>
				          </div>
			          </div>
							</div>
						</div>

						<div class="col-md-8 col-sm-8 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #736E66;">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 bg-danger" style="font-size: 18px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; color: #ffffff; text-align: center;">
									<label style="text-align: center;">Detalle de Recepción</label>
								</div>

								<div id="div_detalle_prev" class="row" style="text-align: center;">
									<label style="color: #ffffff; padding-top: 280px;">
										Registre los datos principales de la recepción antes de ingresar el detalle de muestras.
									</label>
								</div>

								<div id="div_detalle" class="row" style="text-align: center; margin-top: 10px; display: none;">
									<div class="col-md-4 col-sm-4 col-xs-4">
										<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #ffffff; width: 105%; margin-bottom: 10px;">
											<div id="wt_detalle_muestras" class="" style="font-size: 12px; text-align: center; display: none; margin-top: -5px;">
												<img src="<?php echo $img_waiting ?>" style="width: 20px;">
												<label style="font-style: italic;"> Grabando datos...</label>
											</div>

											<div class="d-flex" style="overflow-y: scroll; padding-left: 5px; margin-top: 10px;">
												<table class="table table-bordered table-hover">
								        	<thead>
								        		<tr style="font-size: 14px;">
								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
								        				N°
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
								        				Cód. Interno
								        			</th>
								        		</tr>
								        	</thead>

								        	<tbody id="tbl_detallemuestras">

								        	</tbody>
								        </table>
											</div>
										</div>
									</div>

									<div class="col-md-8 col-sm-8 col-xs-8">
										<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #ffffff; width: 104%;">
											<div class="col-md-12 col-sm-12 col-xs-12" style="font-size: 18px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #21303a; color: #ffffff; text-align: center;">
												<label style="text-align: center;">Información de la Muestra: </label>
												<label id="detalle_titulo" style="color: #FFDB17;"></label>
											</div>

											<div style="height: 20px;">
												<div id="wt_detalle_infomuestra" class="" style="font-size: 12px; text-align: center; display: none;">
													<img src="images/waiting.gif" style="width: 20px;">
													<label style="font-style: italic;"> Grabando datos...</label>
												</div>

												<div id="wt_detalle_infomuestra2" class="" style="font-size: 12px; text-align: center; display: none;">
													<img src="images/waiting.gif" style="width: 20px;">
													<label style="font-style: italic;"> Caargando datos...</label>
												</div>
											</div>

											<div class="row" style="padding: 5px;">
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
															Nombre:
														</div>

														<div class="col-md-8 col-sm-8 col-xs-8">
															<input id="det_nombremuestra" type="text" class="form-control obj_det col-md-12 col-xs-12" style="text-transform: uppercase;" onblur="f_GrabarDetalleRecepcion_Temporal();">
														</div>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
															Peso (Kg):
														</div>

														<div class="col-md-8 col-sm-8 col-xs-8">
															<input id="det_pesomuestra" type="number" class="form-control obj_det col-md-12 col-xs-12" style="text-align: right;" onclick="f_getPeso(1);" onblur="f_GrabarDetalleRecepcion_Temporal(); f_getPesoOff();" onkeyup="f_CalcularExceso();">

															<div id="div_SinConexion" class="col-md-12 col-sm-12 col-xs-12" style="text-align: right; display: none;">
					                      <label class="control-label" style="color: #d9534f; font-size: 12px;">
					                        Se perdió la conexión con la balanza
					                      </label>
					                    </div>
														</div>
													</div>
												</div>
											</div>

											<div class="row" style="padding: 5px;">
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
															Estado:
														</div>

														<div class="col-md-8 col-sm-8 col-xs-8">
															<select id="det_estadomuestra" class="form-select obj_det" onchange="f_GrabarDetalleRecepcion_Temporal();">

															</select>
														</div>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
															Envase:
														</div>

														<div class="col-md-8 col-sm-8 col-xs-8">
															<select id="det_envasemuestra" class="form-select obj_det" onchange="f_GrabarDetalleRecepcion_Temporal();">

															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="row" style="padding: 5px;">
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
															Tipo:
														</div>

														<div class="col-md-8 col-sm-8 col-xs-8">
															<select id="det_tipomuestra" class="form-select obj_det" onchange="f_GrabarDetalleRecepcion_Temporal();">

															</select>
														</div>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
															Ensayo:
														</div>

														<div class="col-md-8 col-sm-8 col-xs-8">
															<select id="det_ensayomuestra" class="form-select obj_det" onchange="f_GrabarDetalleRecepcion_Temporal();">

															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="row" style="padding: 5px;">
												<div class="col-md-6 col-sm-6 col-xs-6" style="text-align: left;">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px; margin-left: 10px; margin-top: 15px;">
															Observación:
														</div>
													</div>
												</div>

												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="row">
														<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
															Exceso:
														</div>

														<div class="col-md-8 col-sm-8 col-xs-8">
															<input id="det_excesomuestra" type="number" class="form-control obj_det col-md-12 col-xs-12" style="text-align: right;" onblur="f_GrabarDetalleRecepcion_Temporal();">
														</div>
													</div>
												</div>
											</div>

											<div class="row" style="padding: 5px; margin-top: -11px;">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<textarea id="det_observacion" type="text" class="form-control obj_det col-md-12 col-xs-12" style="text-transform: uppercase;" rows="2" onblur="f_GrabarDetalleRecepcion_Temporal();"></textarea>
												</div>
											</div>

											<div class="col-md-12 col-sm-12 col-xs-12" style="font-size: 18px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #21303a; color: #ffffff; text-align: center; margin-top: 10px;">
												<label style="text-align: center;">Análisis Asignados</label>
											</div>

											<div class="row" style="padding: 5px;">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<button id="btn_DetalleAddAnalisis" class="btn btn-primary" type="button" onclick="f_AddAnalisis();" style="width: 100%; color: #ffffff; font-size: 14px;"  data-bs-toggle="modal" data-bs-target="#modal_addanalisis">
							              <b> + Análisis</b>
							            </button>
												</div>

												<!-- <div class="col-md-3 col-sm-3 col-xs-6">
													<button id="btn_DetalleReplicarAnalisis" class="btn btn-warning" type="button" onclick="f_Replicarmuestras();" style="width: 100%; color: #ffffff; font-size: 14px;" data-bs-toggle="modal" data-bs-target="#modal_replicarmuestra">
							              <b> Replicar</b>
							            </button>
												</div> -->
											</div>

											<div class="d-flex" style="overflow-y: scroll; padding-left: 5px;">
												<table class="table table-bordered table-hover">
								        	<thead>
								        		<tr style="font-size: 14px;">
								        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
								        				N°
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Clasificación
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
								        				Id Análisis
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
								        				Análisis
								        			</th>

								        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px; width: 130px;">
								        				Total <label id="lbl_monedaanalisis"></label>
								        			</th>
								        		</tr>
								        	</thead>

								        	<tbody id="tbl_detalleanalisis">

								        	</tbody>
								        </table>
											</div>
										</div>
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
		<div class="modal fade" id="modal_addcliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addclienteLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addclienteLabel">Nuevo Cliente</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Tipo Cliente:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="cliente_tipocliente" class="form-select" style="text-align: left;" onchange="f_GetListaTipoDocumento(0)">

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Tipo Documento:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="cliente_tipodocumento" class="form-select" style="text-align: left;">

								</select>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Documento:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="cliente_documento" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente()";>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Razón Social: <img id="wt_razonsocial2" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<textarea id="cliente_razonsocial" type="text" class="form-control col-md-12 col-xs-12" rows="2"></textarea>
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Teléfonos:
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="cliente_telefono1" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="cliente_telefono2" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="row" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Correo:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="cliente_correo" type="email" class="form-control col-md-12 col-xs-12">
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
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarCliente();">Grabar y continuar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_buscarcliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_buscarclienteLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_buscarclienteLabel">Buscar Cliente</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <table class="table table-bordered table-hover">
		        	<thead>
		        		<tr style="font-size: 14px;">
		        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
		        				N°
		        			</th>

		        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
		        				Documento
		        			</th>

		        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
		        				Razón Social
		        			</th>
		        		</tr>

		        		<tr style="font-size: 14px;">
		        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
		        				<input id="filtro_clientedocumento" type="text" class="form-control col-md-12 col-xs-12" onkeyup="f_BuscarCliente_Filtro(1)" style="font-size: 14px;">
		        			</th>

		        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
		        				<input id="filtro_clienterazonsocial" type="text" class="form-control col-md-12 col-xs-12" onkeyup="f_BuscarCliente_Filtro(2)" style="font-size: 14px; text-transform: uppercase;">
		        			</th>
		        		</tr>
		        	</thead>

		        	<tbody id="tbl_buscarcliente">

		        	</tbody>
		        </table>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_addanalisis" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addanalisisLabel" aria-hidden="true">
		  <div class="modal-dialog modal-xl">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_addanalisisLabel">Seleccione los Análisis que desea asignar a la muestra: </h1>
		        <h1 class="modal-title fs-5" id="titulo_analisis" style="color: #FFDB17; margin-left: 5px; font-weight: bold;"></h1>

		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="d-flex justify-content-center flex-wrap" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center;">
		      		<button id="filtro_precios1" type="button" class="btn btn-primary" onclick="f_SelectedPrecios(1)">Precios Generales</button>
		      		<button id="filtro_precios2" type="button" class="btn btn-outline-primary" style="margin-left: 10px;" onclick="f_SelectedPrecios(2)">Paquetes Generales</button>
		      		<button id="filtro_precios3" type="button" class="btn btn-outline-primary" style="margin-left: 10px;" onclick="f_SelectedPrecios(3)">Paquetes Cliente</button>
						</div>

		        <div id="div_analisisclasificaciones" class="d-flex justify-content-center flex-wrap" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px;">

						</div>

						<div id="div_analisis" class="d-flex justify-content-center flex-wrap" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px; background-color: #A5B0FF;">

						</div>

						<div class="d-flex" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px;">
							<table class="table table-bordered table-hover">
			        	<thead>
			        		<tr style="font-size: 14px;">
			        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px; width: 40px;">
			        				N°
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Clasificación
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;" hidden>
			        				Id Análisis
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
			        				Análisis
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #5b2a68; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px; width: 130px;">
			        				Total <label id="lbl_monedaanalisis_add"></label>
			        			</th>
			        		</tr>
			        	</thead>

			        	<tbody id="tbl_detalleanalisis_add">

			        	</tbody>
			        </table>
						</div>
		      </div>

		      <div class="modal-footer">
		      	<div id="wt_detalleanalisis_add" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="<?php echo $img_waiting ?>" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_ConfirmarAnalisis();">Confirmar y continuar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_replicarmuestra" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_replicarmuestraLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_replicarmuestraLabel">Replicar Análisis de: </h1>
		        <h1 class="modal-title fs-5" id="titulo_replicar" style="color: #FFDB17; margin-left: 5px; font-weight: bold;"></h1>

		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="form-check form-switch" style="margin-left: 30px;">
						  <input class="form-check-input" type="checkbox" role="switch" id="chk_MuestrasReplicaSelectAll" onchange="f_SelectedMuestraReplica(0);" checked>
						  <label class="form-check-label" for="chk_MuestrasReplicaSelectAll">Seleccionar Todo</label>
						</div>

		      	<div class="d-flex" style="overflow-y: scroll; padding-left: 25px; padding-right: 25px;">
			        <table class="table table-bordered table-hover">
			        	<thead>
			        		<tr style="font-size: 14px;">
			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
			        				N°
			        			</th>

			        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
			        				Muestra
			        			</th>
			        		</tr>
			        	</thead>

			        	<tbody id="tbl_muestrasreplica">

			        	</tbody>
			        </table>
			      </div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_EjecutarReplica();">Replicar y continuar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_confirmarrecepcion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_confirmarrecepcionLabel" aria-hidden="true">
		  <div class="modal-dialog" style="margin-top: 10%;">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_confirmarrecepcionLabel">Confirmar Recepción</h1>

		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="d-flex" justify-content-center style="padding: 20px;">
		        	<button id="btn_generarinstruccion_printconfirmacion" type="button" class="btn btn-warning" onclick="f_ImprimirRecibo(1);">Imprimir Confirmación</button>
		        	<button id="btn_generarinstruccion_cerrar" type="button" class="btn btn-danger" style="margin-left: 5px;" onclick="f_GenerarInstruccion(0);">Por Pagar</button>
		        	<button id="btn_generarinstruccion_grabar" type="button" class="btn btn-primary" style="margin-left: 5px;" onclick="f_GenerarInstruccion(1);">Métodos de Pago</button>
		        </div>
		      </div>

		      <div class="modal-footer">
		      	<div id="wt_confirmarinstruccion_clientecredito" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="images/waiting.gif" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		      	<button id="btn_inscerrar_clientecredito" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		      	<button id="btn_insgrabar_clientecredito" type="button" class="btn btn-primary" style="display: none;" onclick="f_ConfirmarInstruccion();">Confirmar Instrucción</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="modal_instruccion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_instruccionLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_instruccionLabel">Confirmar Instrucción</h1>
		      </div>
		      <div class="modal-body">
						<div class="d-flex justify-content-center">
							<div class="d-flex">
								<div class="col-md-4 col-sm-4 col-xs-4">
									<label style="margin-right: 30px; font-weight: bold;">Boleta</label>
								</div>

								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="form-check form-switch">
									  <input class="form-check-input" type="checkbox" role="switch" id="chk_Comprobante" style="width: 80px;" checked>
									</div>
								</div>

								<div class="col-md-4 col-sm-4 col-xs-4">
									<label style="margin-left: 30px; font-weight: bold;">Factura</label>
								</div>
							</div>
						</div>

						<div class="row" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px; background-color: #FFF587;">
							<div class="col-md-4 col-sm-4 col-xs-6" style="padding: 5px; text-align: right;">
								Medio de Pago:
							</div>

							<div class="col-md-7 col-sm-7 col-xs-6">
								<select id="ins_mediospago" class="form-select" style="text-align: left;" onchange="f_ShowEfectivo();">

								</select>
							</div>
						</div>

						<div class="row" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; margin-top: 10px;">
							<div class="d-flex justify-content-center">
								<div style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px; width: 240px; font-weight: bold;">
									Total Venta <label id="ins_moneda_1"></label>
									<hr style="margin-top: 5px; margin-bottom: 10px;">

									<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #5b2a68;">
										<label id="ins_totalventa" style="color: #ffffff; font-size: 22px;">

										</label>
									</div>

								</div>

								<div class="ins_divefectivo" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px; width: 240px; font-weight: bold; display: none;">
									Efectivo Ingresado <label id="ins_moneda_2"></label>
									<hr style="margin-top: 5px; margin-bottom: 10px;">

									<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ba9842;">
										<label id="ins_efectivo" style="color: #ffffff; font-size: 22px;">

										</label>
									</div>

								</div>

								<div class="ins_divefectivo" style="padding: 10px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; margin-right: 5px; width: 240px; font-weight: bold; display: none;">
									Cambio <label id="ins_moneda_3"></label>
									<hr style="margin-top: 5px; margin-bottom: 10px;">

									<div style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #dc3545;">
										<label id="ins_cambio" style="color: #ffffff; font-size: 22px;">

										</label>
									</div>

								</div>
							</div>
						</div>

						<div class="ins_divefectivo" style="display: none;">
              <div class="row" style="padding-left: 30px; padding-right: 30px;">
                <div class="col-md-6 col-sm-6 col-xs-12" style="text-align: center;">
                	<div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(200)">
	                    <img src="images/dinero/billete200.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete200" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(100)">
	                    <img src="images/dinero/billete100.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete100" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(50)">
	                    <img src="images/dinero/billete50.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete50" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(20)">
	                    <img src="images/dinero/billete20.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px;">
	                    <input type="number" id="total_billete20" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_billete col-md-8 col-sm-8 col-xs-8" style="cursor: pointer; margin-top: 10px;" onclick="f_SumaBilletes(10)">
	                    <img src="images/dinero/billete10.png" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; width: 150px;"/>
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 30px; font-size: 20px">
	                    <input type="number" id="total_billete10" style="text-align: center; width: 60px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes(5)">
	                    <img src="images/dinero/billete5.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete5" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes(2)">
	                    <img src="images/dinero/billete2.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete2" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes(1)">
	                    <img src="images/dinero/billete1.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete1" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes('50cen')">
	                    <img src="images/dinero/billete50cen.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete50cen" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes('20cen')">
	                    <img src="images/dinero/billete20cen.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete20cen" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>

	                <div class="d-flex justify-content-center">
	                  <div class="_hov_moneda col-md-4 col-sm-4 col-xs-4" style="cursor: pointer;" onclick="f_SumaBilletes('10cen')">
	                    <img src="images/dinero/billete10cen.png" width="62px" style="margin-top: 5px" />
	                  </div>
	                  <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: 27px; font-size: 20px">
	                    <input type="number" id="total_billete10cen" style="text-align: center; width: 50px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px;" value="0" min="0" onchange="f_CalcularEfectivo()"/>
	                  </div>
	                </div>
                </div>
              </div>
            </div>
		      </div>

		      <div class="modal-footer">
		      	<div id="wt_confirmarinstruccion" class="" style="font-size: 12px; text-align: center; display: none;">
							<img src="images/waiting.gif" style="width: 20px;">
							<label style="font-style: italic;"> Grabando datos...</label>
						</div>

		        <button id="btn_inscerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button id="btn_insgrabar" type="button" class="btn btn-primary" onclick="f_ConfirmarInstruccion();">Confirmar Instrucción</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade modal-dialog-scrollable" id="modal_ampliacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_ampliacionLabel" aria-hidden="true">
		  <div class="modal-dialog modal-xl">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modal_ampliacionLabel">Realizar Ampliación</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		      	<div class="row" style="margin-top: -10px; margin-bottom: 5px; padding: 5px; margin-left: 5px;">
		      		<div class="row col-md-3 col-sm-12 col-xs-12">
								<div class="col-md-4 col-sm-3 col-xs-2" style="padding: 5px; text-align: left;">
									Recepción:
								</div>

								<div class="col-md-8 col-sm-6 col-xs-10">
									<input id="amp_fecha" type="date" class="form-control col-md-12 col-xs-12" style="text-align: center; font-size: 14px;" value="<?php echo $g_date; ?>" onchange="f_GetListaClientesAmpliacion();">
								</div>
							</div>

							<div class="row col-md-7 col-sm-12 col-xs-12">
								<div class="col-md-1 col-sm-3 col-xs-2" style="padding: 5px; text-align: left;">
									Cliente:
								</div>

								<div class="col-md-11 col-sm-9 col-xs-10">
									<select id="amp_cliente" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_GetListaVentasAmpliacion();" required>

									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="row" style="padding-left: 10px; padding-right: 10px;">
											<h6 style="font-size: 13px;">Ventas</h6>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
							        <table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 13px;">
							        			<th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
							        				N°
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				N° Requerimiento
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Hora Recepción
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				N° Muestras
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Total Venta
							        			</th>
							        		</tr>
							        	</thead>

							        	<tbody id="tbl_ventasampliacion">

							        	</tbody>
							        </table>
							      </div>
							    </div>
							  </div>
				      </div>

				      <div class="col-md-6 col-sm-6 col-xs-6">
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
									<div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
										<div class="d-flex" style="padding-left: 10px; padding-right: 10px;">
											<div style="font-size: 14px;">Muestras del Requerimiento: </div>
											<div id="titulo_requerimientoampliacion" style="font-size: 14px; font-weight: bold; padding-left: 10px;"></div>
										</div>

										<div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
											<hr style="border-color: #D9D9D9;"/>
										</div>

										<div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
							        <table class="table table-bordered table-hover">
							        	<thead>
							        		<tr style="font-size: 13px;">
							        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
							        				Item
							        			</th>

							        			<th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
							        				Información de Muestras
							        			</th>
							        		</tr>

							        		<tr style="font-size: 13px;">
							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				<input id="chk_MuestrasAmpliacionSelectAll" class="form-check-input" type="checkbox" onchange="f_SelectedMuestrasAmpliacion(0)">
							        				Sel.
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				N°
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Nombre
							        			</th>

							        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #21303a; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
							        				Análisis
							        			</th>
							        		</tr>
							        	</thead>

							        	<tbody id="tbl_muestrasampliacion">

							        	</tbody>
							        </table>
							      </div>
							    </div>
							  </div>
				      </div>
				    </div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_IniciarAmpliacion();">Iniciar Ampliación</button>
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

				// // Titulo de Pantalla
				// 	$("#nv_titulo").html('| Recepción de Ensayos de Laboratorio');

				// Seteando la pantalla
					$("#div_cabecera_prev").hide();
					$("#div_cabecera").show();

					$(".obj_det").prop('disabled', true);

				// Obtiene los datos de la Recepción
					f_GetIdRecepcion();
			}
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_GetIdRecepcion(){
				// Si no se ha generado aun la Ampliación
					if (id_recepcion == 0){
						$.post( "apis/backend.php", { accion: "get_IdRecepcion_Ampliacion", id_recepcion_origen: id_recepcion_origen, arr_muestras: arr_muestras }, 
							function( data ) {
								if(data.estado == 1){
									id_recepcion = data.id_recepcion;
									id_md5 = data.id_md5;

									// Vuelve a carga la página para actualizar los parámetros
										window.open('recepcion_ensayos_ampliacion.php?x=' + id_recepcion_origen + '&r=' + id_recepcion + '&d=' + arr_muestras, '_self');

										return;
								}
								else{
									alert("Ocurrió un error al momento de generar el Id de la recepción.");
								}

							}, "json");
					}
					else{
						f_GetRecepcionTemporal();
					}
			}	

			function f_GetRecepcionTemporal(){
				$.post( "apis/backend.php", { accion: "get_RecepcionTemporal", id_recepcion: id_recepcion }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.res, function( key, val ) {
								cab_codinternopreliminar_x = ((val.cod_interno_preliminar == null) ? '' : val.cod_interno_preliminar);
								cab_fecharecepcion_x = ((val.fechahora_recepcion != null) ? val.fechahora_recepcion.substring(0, 10) : '<?php echo $g_date; ?>');
								cab_horarecepcion_x = ((val.fechahora_recepcion != null) ? val.fechahora_recepcion.substring(16, 11) : '<?php echo substr($g_time, 0, 5); ?>');
								cab_fechaentrega_x = ((val.fechahora_entrega != null) ? val.fechahora_entrega.substring(0, 10) : '');
								cab_horaentrega_x = ((val.fechahora_entrega != null) ? val.fechahora_entrega.substring(16, 11) : '');
								cab_clientedocumento_x = ((val.cliente_documento == null) ? '' : val.cliente_documento);
								cab_clienterazonsocial_x = ((val.cliente_razonsocial == null) ? '' : val.cliente_razonsocial);
								cab_entregadopor_x = ((val.entregado_por == null) ? '' : val.entregado_por);
								cab_celularareportar_x = ((val.celular_areportar == null) ? '' : val.celular_areportar);
								cab_sucursal_x = ((val.cod_sucursal == null) ? '' : val.cod_sucursal);
								cab_moneda_x = ((val.cod_moneda == null) ? '' : val.cod_moneda);
								cab_observacion_x = ((val.observacion == null) ? '' : val.observacion);
								cab_tienerecojo_x = val.tiene_recojo;
								cab_iscabecerasaved = ((val.is_cabecerasaved == null) ? '' : val.is_cabecerasaved);
								cab_recibo_codigo_x = ((val.recibo_codigo == null) ? '' : val.recibo_codigo);

								// Actualizando campos input
									// $("#cab_codigo").val(cab_codinternopreliminar_x);
									$("#cab_codigo").val(cab_recibo_codigo_x);
									$("#cab_fecharecepcion").val(cab_fecharecepcion_x);
									$("#cab_horarecepcion").val(cab_horarecepcion_x);
									$("#cab_fechaentrega").val(cab_fechaentrega_x);
									$("#cab_horaentrega").val(cab_horaentrega_x);
									$("#cab_clientedocumento").val(cab_clientedocumento_x);
									$("#cab_clienterazonsocial").val(cab_clienterazonsocial_x);
									$("#cab_entregadopor").val(cab_entregadopor_x);
									$("#cab_celularareportar").val(cab_celularareportar_x);
									$("#cab_observacion").val(cab_observacion_x);
									$("#chk_recojo").prop('checked', ((cab_tienerecojo_x == 1) ? true : false));

									f_BuscarClientes();

								// Titulo de Pantalla
									$("#nv_titulo").html('| Ampliación del requerimiento: <b>' + cab_recibo_codigo_x.substring(0, 14) + '</b>');

								// Debecargar toda la recepción y dejarla lista para el registro de análisis
									f_DisableObjCabecera(1);
									f_getRecepcionDetalle();

								// Actualiza en la tabla los datos (para cuando se actualiza la fecha y hora de entrega al momento de agregar muestras)
									f_GrabarCabeceraRecepcion_Temporal();

							});
						}
						else{
							alert("Ocurrió un error al momento de generar el Id de la recepción.");
						}

						// Asigan correlativo
							f_GetCodigoInterno();

						// Cargando listas para Cabecera
							f_GetListaSucursales();
							f_GetListaMonedas();
							f_GetListaTipoCliente();
							f_GetListaTipoDocumento(1);

						// Cargando listas para Detalles de Muestras
							f_GetListaEstadosMuestra();
							f_GetListaEnvasesMuestra();
							f_GetListaTipoMuestra();
							f_GetListaEnsayosMuestra();

						// Cargando Clasificaciones para Análisis
							f_GetListaAnalisisClasificaciones();

						// Cargando Medio de Pago para la Instrucción
							f_GetListaMediosDePago();

						// Actualiza en la tabla los datos (para cuando se actualiza la fecha y hora de entrega al momento de agregar muestras)
							f_GrabarCabeceraRecepcion_Temporal();

					}, "json");
			}

			function f_GetCodigoInterno(){
				$.post( "apis/backend.php", { accion: "get_CodigoInterno", is_ensayo: 1, is_preliminar: 1, id_recepcion: id_recepcion }, 
					function( data ) {
						if(data.estado == 1){
							// $("#cab_codigo").val(data.correlativo);
						}
						else{
							alert("Ocurrió un error al momento de obtener el código de recepción.");
						}

					}, "json");
			}

			function f_BuscarClientes(){
				var documento = $("#cab_clientedocumento").val();

				$("#cab_clienterazonsocial").val('');
				$("#cab_celularareportar").val('');
				$("#wt_razonsocial1").hide();
				$("#div_creditocliente").hide();
				$("#div_dsctocliente").hide();

				cli_tienecredito = 0;
				cli_tienedscto = 0;

				if (documento.length == 8 || documento.length == 11){
					$("#wt_razonsocial1").show();

					$.post( "apis/backend.php", { accion: "get_RecepcionCliente", documento: documento }, 
						function( data ) {
							if(data.estado == 1){
								$("#cab_clienterazonsocial").val(data.res);
								$("#cab_celularareportar").val(data.tel);

								if (data.tiene_credito == 1){
									cli_tienecredito = 1;

									$("#div_creditocliente").show();

									$("#cli_creditovigencia").html(data.cred_vigencia);
									$("#cli_creditoobservacion").html(data.cred_observacion);
								}

								if (data.tiene_dscto == 1){
									cli_tienedscto = 1;

									$("#div_dsctocliente").show();

									$("#cli_dsctovigencia").html(data.dscto_vigencia);
									$("#cli_dsctoporc").html(data.dscto_porc);
									$("#cli_dsctoobservacion").html(data.dscto_observacion);
								}

								cab_clientedocumento_x = documento;
								cod_tipocliente_x = data.cod_tipocliente;
							}
							else{
								$("#cab_clienterazonsocial").val('NO ENCONTRADO');
							}

							$("#wt_razonsocial1").hide();

						}, "json");
				}
			}

			function f_getRecepcionDetalle(){
				$("#tbl_detallemuestras").html('');
				$("#det_nummuestras").val(1);

				// Obtiene el detalle de Muestras de la recepción, si aun no tuviera detalle agrega por defecto la muestra 001.
					$.post( "apis/backend.php", { accion: "get_RecepcionDetalle", id_recepcion: id_recepcion, is_ampliacion: 1 }, 
						function( data ) {
							if(data.estado == 1){
								$("#tbl_detallemuestras").html(data.html);

								f_LoadAnalisisMuestra(1);
							}
							else{
								alert("Ocurrió un error al momento de obtener el detalle de la recepción.");
							}

						}, "json");
			}

			function f_LoadAnalisisMuestra(_item){
				item_selected = _item;
				idmuestra_selected = $("#id_detalle_x_" + _item).val();
				muestra_selected = $("#td_detalle_3_" + _item).html().trim();

				// Colocando título
					$("#detalle_titulo").html(muestra_selected);

				// Pinta selección
          f_ColorSelected(_item);

				// Obtiene el detalle de la muestra seleccionada
          $("#wt_detalle_infomuestra2").show();

					$.post( "apis/backend.php", { accion: "get_RecepcionDetalle_Temporal", id_detalle: idmuestra_selected }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									det_nombremuestra_x = ((val.nombre_muestra == null) ? '' : val.nombre_muestra);
									det_pesomuestra_x = ((val.peso_muestra == null) ? '' : val.peso_muestra);
									det_estadomuestra_x = ((val.cod_estadomuestra == null) ? '' : val.cod_estadomuestra);
									det_envasemuestra_x = ((val.cod_envasemuestra == null) ? '' : val.cod_envasemuestra);
									det_tipomuestra_x = ((val.cod_tipomuestra == null) ? '' : val.cod_tipomuestra);
									det_ensayomuestra_x = ((val.cod_ensayomuestra == null) ? '' : val.cod_ensayomuestra);
									det_excesomuestra_x = ((val.exceso == null) ? '' : val.exceso);
									det_observacion_x = ((val.observacion == null) ? '' : val.observacion);

									// Actualizando campos de registro
										$("#det_nombremuestra").val(det_nombremuestra_x);
										$("#det_pesomuestra").val(det_pesomuestra_x);
										$("#det_estadomuestra").val(det_estadomuestra_x);
										$("#det_envasemuestra").val(det_envasemuestra_x);
										$("#det_tipomuestra").val(det_tipomuestra_x);
										$("#det_ensayomuestra").val(((det_ensayomuestra_x.length == 0) ? 1 : det_ensayomuestra_x));
										$("#det_excesomuestra").val(det_excesomuestra_x);
										$("#det_observacion").val(det_observacion_x);

									// Obtiene detalle de análisis
										f_LoadDetalleAnalisis();

								});
							}
							else{
								alert("Ocurrió un error al momento de obtener la información de la muestra seleccionada.");
							}

							$("#wt_detalle_infomuestra2").hide();

						}, "json");
			}

			function f_GetListaAnalisisClasificaciones(){
				// Carga clasificaciones
					var _html = '';
					var c = 1;

					$.post( "apis/backend.php", { accion: "get_ListaAnalisisClasificaciones", cod_cliente: cab_clientedocumento_x, filtro_precios: filtro_precios_x }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<div id="div_clasificacion_' + c + '" class="flex-fill" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; padding: 8px; margin-right: 10px; background-color: ' + ((c == 1) ? '#FFF587' : '') + '; padding-left: 15px; padding-right: 15px; font-weight: bold; margin-bottom: 5px; font-size: 14px; cursor: pointer;" onclick="f_GetListaAnalisis(' + c + ')">';
									_html += '	' + val.descripcion;
									_html += '  <input id="id_analisisclasificacion_' + c + '" type="hidden" value="' + val.Id + '">';
									_html += '  <input id="des_analisisclasificacion_' + c + '" type="hidden" value="' + val.descripcion + '">';
									_html += '</div>';

									c ++;
								});
							}
							else{
								$("#div_analisis").html('');
							}

							$("#div_analisisclasificaciones").html(_html);

							f_GetListaAnalisis(1);

						}, "json");
			}

			function f_AddAnalisis(){
				// Coloca el título en la ventana
					$("#titulo_analisis").html(muestra_selected);

				// Coloca por defecto el filtro de Precios Generales
					filtro_precios_x = 1;

				// Selecciona el Fitro de Precios Generales por defecto
					f_SelectedPrecios(1);

				// Actualiza el html de análisis
					if ($("#td_x").html() == undefined){
						$("#tbl_detalleanalisis_add").html($("#tbl_detalleanalisis").html().replace(/id="tr_subtotal"/g, 'id="tr_subtotal_add"').replace(/"deltr_detalleanalisis"/g, '"deltr_detalleanalisis_add"').replace(/id="td_x"/g, 'id="td_x_add"'));
					}
					else{
						$("#tbl_detalleanalisis_add").html('');
					}

				f_GetListaAnalisis(1);

				// Setea la moneda en el campo de Total
					$("#lbl_monedaanalisis_add").html($("#cab_moneda option:selected").text());

				// Actualiza en la tabla los datos (para cuando se actualiza la fecha y hora de entrega al momento de agregar muestras)
					f_GrabarCabeceraRecepcion_Temporal();
			}

			function f_GetListaAnalisis(_item){
				f_ClasificacionSelected(_item);

				// Carga clasificaciones
					var _html = '';
					var a = 1;
					var id_clasificacion = $("#id_analisisclasificacion_" + _item).val();
					var des_clasificacion = $("#des_analisisclasificacion_" + _item).val().trim().toUpperCase();

					$.post( "apis/backend.php", { accion: "get_ListaAnalisis", cod_cliente: cab_clientedocumento_x, filtro_precios: filtro_precios_x, id_clasificacion: id_clasificacion }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<div id="div_analisis_' + a + '" class="flex-fill" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; padding: 8px; margin-right: 10px; background-color: #00a2aa; padding-left: 15px; padding-right: 15px; font-weight: bold; margin-bottom: 5px; font-size: 14px; cursor: pointer;" onclick="f_SetAnalisis(' + val.Id + ", '(" + val.abv + ") - " + val.descripcion + "', '" + des_clasificacion + "', '" + val.MONEDA + "', " + val.precio + ', ' + ((filtro_precios_x == 2) ? 1 : 0) + ', ' + ((filtro_precios_x == 3) ? 1 : 0) + ')">';
									_html += '	<input id="div_listaanalisis_' + a + '" type="hidden" value="' + val.Id + ((filtro_precios_x == 2) ? '1' : '0') + ((filtro_precios_x == 3) ? '1' : '0') + '">';
									_html += '	<label style="color: #ffffff; cursor: pointer; font-size: 16px;">' + val.abv + '</label><br><label style="font-size: 15px; margin-top: -5px; cursor: pointer; color: #12537e;">' + val.descripcion + '</label><br><label style="color: #FFDB17; margin-top: -5px; cursor: pointer;">' + val.MONEDA + ' ' + val.precio + '</label>';
									_html += '</div>';

									a ++;
								});
							}
							else{
								_html += '<label style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #F25050; color: #ffffff; width: 100%;">';
								_html += '	No se encontraron análisis configurados para la clasificación seleccionada.';
								_html += '</label>';
							}

							$("#div_analisis").html(_html);

							// Calcular Subtotal
								f_CalculaSubtotal(1);

							// Deshabilita Análisis seleccionados
								f_DisabledAnalisis(1);

						}, "json");
			}

			function f_SetAnalisis(_id_analisis, _analisis, _clasificacion, _moneda, _total, _is_paquete, _is_paquetecliente){
				// Obtener el número de filas actuales
					var _rows = $("#tbl_detalleanalisis_add tr").length;

				// Setea Fila
					var _tr = '<tr style="font-size: 14px;">';
					_tr += '  <td class="deltr_detalleanalisis_add" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 40px;">';
          _tr += '    <button class="btn btn-danger" type="button" style="color: #ffffff;">';
          _tr += '      x';
          _tr += '    </button>';
          _tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;">';
					_tr += '  	 ' + _rows;
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;">';
					_tr += '  	 ' + _clasificacion;
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;" hidden>' + _id_analisis + '</td>';
					_tr += '  <td style="vertical-align: middle;">';
					_tr += '  	 ' + _analisis;
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle; text-align: right">';
					_tr += '  	 ' + parseFloat(_total).toFixed(2);
					_tr += '  </td>';
					_tr += '  <td style="vertical-align: middle;" hidden>' + _is_paquete + '</td>';
					_tr += '  <td style="vertical-align: middle;" hidden>' + _is_paquetecliente + '</td>';
					_tr += '</tr>';

					$("#tbl_detalleanalisis_add").append(_tr);

				// Calcular Subtotal
					f_CalculaSubtotal(1);

				// Deshabilita Análisis seleccionados
					f_DisabledAnalisis(1);
			}

			function f_LoadDetalleAnalisis(){
				var _html = '';
				var a = 1;

				// Coloca título de moneda
					$("#lbl_monedaanalisis").html($("#cab_moneda option:selected").text());

				// Cargando detalle de análisis
					$("#tbl_detalleanalisis").html('');

					$.post( "apis/backend.php", { accion: "get_DetalleAnalisis", id_detalle: idmuestra_selected }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									// Agrega filas
										_html = '<tr style="font-size: 14px;">';
										_html += '  <td class="deltr_detalleanalisis" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; width: 40px;">';
					          _html += '    <button class="btn btn-danger" type="button" style="color: #ffffff;">';
					          _html += '      x';
					          _html += '    </button>';
					          _html += '  </td>';
										_html += '  <td style="vertical-align: middle;">';
										_html += '  	 ' + a;
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle;">';
										_html += '  	 ' + val.CLASIFICACION;
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle;" hidden>' + val.cod_analisis + '</td>';
										_html += '  <td style="vertical-align: middle;">';
										_html += '  	 ' + val.ANALISIS;
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle; text-align: right;">';
										_html += '  	 ' + parseFloat(val.total).toFixed(2);
										_html += '  </td>';
										_html += '  <td style="vertical-align: middle;" hidden>' + val.is_paquete + '</td>';
										_html += '  <td style="vertical-align: middle;" hidden>' + val.is_paquetecliente + '</td>';
										_html += '</tr>';

										$("#tbl_detalleanalisis").append(_html);

									// Calcular Subtotal
										f_CalculaSubtotal(0);

									a ++;
								});
							}
							else{
								_html = '<tr style="font-size: 14px; background-color: #F25050;">';
								_html += '  <td id="td_x" colspan="6" style="vertical-align: middle; color: #ffffff; text-align: center;">';
								_html += '  	 Aún no se han registrado análisis para la muestra seleccionada.';
								_html += '  </td>';
								_html += '</tr>';

								$("#tbl_detalleanalisis").html(_html);
							}

						}, "json");
			}

			function f_Replicarmuestras(){
				$("#titulo_replicar").html(muestra_selected);

				$("#chk_MuestrasReplicaSelectAll").prop('checked', true);

				// Recorre la lista de muestras para replicar
					var _tr = '';
					var r = 1;
					var x = 1;

					$("#tbl_muestrasreplica").html('');

					$('#tbl_detallemuestras tr').each(function () {
						if (idmuestra_selected != $("#id_detalle_x_" + r).val()){
							_tr = '<tr id="tr_itemreplica_' + x + '" style="background-color: #FFF587; font-size: 14px;" onclick="f_SelectedMuestraReplica(' + x + ');">';
							_tr += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; width: 40px;">';
							_tr += '  	' + x;
							_tr += '  	<input id="id_replica_x_' + x + '" type="hidden" value="' + $("#id_detalle_x_" + r).val() + '">';
							_tr += '  </td>';
							_tr += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center; font-weight: bold; cursor: pointer;">';
							_tr += '  	 ' + $(this).find("td").eq(2).html();
							_tr += '  </td>';
							_tr += '</tr>';

							$("#tbl_muestrasreplica").append(_tr);

							x ++;
						}

						r ++;
	        });
			}

			function f_ImprimirRecibo(_is_prelimiar){
				var url = 'print_recibo_ensayos.php?x=' + id_md5 + '&p=' + _is_prelimiar;

				window.open(url,'_blank',"");
			}

			function f_GenerarInstruccion(_is_instruccion){
				if (_is_instruccion == 0){
					// Grabar Preventa (POR PAGAR)
						f_LoadingGenerarInstruccion(1);

						$.post( "apis/backend.php", { accion: "grabar_PreVentaSinInstruccion", id_recepcion: id_recepcion, cod_sucursal: cab_sucursal_x }, 
							function( data ) {
								if(data.estado == 1){
									window.open('recepcion_ensayos.php', '_self');
								}
								else{
									alert("Ocurrió un error al momento de grabar la recepción.");
								}

								f_LoadingGenerarInstruccion(0);

							}, "json");
				}
				else{
					f_cerrarModal('modal_confirmarrecepcion');

					// Setea la moneda
						$("#ins_moneda_1").html($("#cab_moneda option:selected").text());
						$("#ins_moneda_2").html($("#cab_moneda option:selected").text());
						$("#ins_moneda_3").html($("#cab_moneda option:selected").text());

					// Setea el selector del comprobante
						$("#chk_Comprobante").prop('disabled', false);
						$("#chk_Comprobante").prop('checked', true);

						if (cod_tipocliente_x == 1){
							$("#chk_Comprobante").prop('disabled', true);
							$("#chk_Comprobante").prop('checked', false);
						}

					// Obtiene el Total de la Venta
						$.post( "apis/backend.php", { accion: "get_TotalRecepcion", id_recepcion: id_recepcion }, 
						function( data ) {
							if(data.estado == 1){
								$("#ins_totalventa").html(parseFloat(data.res).toFixed(2));
							}
							else{
								alert("Ocurrió un error al momento de obtener el Total de la Venta.");
							}

						}, "json");

					f_OpenModal('modal_instruccion');
				}
			}

			function f_getPeso(_on){
				if (_on == 1){
					$.get( "apis/interfaces.php", { accion: "get_Peso", cod_balanza: 1 }, 
	          function( data ) {
	            if(data.estado == 1){
	              if (data.peso == -1){
	                $("#div_SinConexion").show();

	                return;
	              }
	              else{
	                $("#div_SinConexion").hide();

	                $("#det_pesomuestra").val(data.peso);

	                f_CalcularExceso();
	              }
	            }
	            else{
	              $("#div_SinConexion").show();
	            }

	            setTimeout('f_getPeso(1)', 1000);

	          }, "json");
				}
			}

			function f_getPesoOff(){
				f_getPeso(0);
			}

			function f_BuscarCliente_Filtro(tipo_busqueda){
        var htmlContenido = '';
        var str_busqueda = '';

        if (tipo_busqueda == 1){
        	$("#filtro_clienterazonsocial").val('');

          if ($("#filtro_clientedocumento").val().length >= 3){
            str_busqueda = $("#filtro_clientedocumento").val();
          }
          else{
            return;
          }
        }
        else{
        	$("#filtro_clientedocumento").val('');

          if ($("#filtro_clienterazonsocial").val().length >= 3){
            str_busqueda = $("#filtro_clienterazonsocial").val();
          }
          else{
            return;
          }
        }

        // Recorriendo registros
        	var c = 1;

	        $("#tbl_buscarcliente tr").each(function () {
	        	$("#td_buscarcliente_item_" + c).show();

	          if (!$(this).find("td").eq(tipo_busqueda).html().trim().toLowerCase().includes(str_busqueda.toLowerCase())){
	          	$("#td_buscarcliente_item_" + c).hide();
	          }

	          c += 1;
	        });
    	};

    	function f_CalcularExceso(){
    		var _peso = $("#det_pesomuestra").val();
    		_peso = parseFloat(_peso);

    		if (isNaN(_peso)){
    			$("#det_excesomuestra").val('');

    			return;
    		}

    		// Limpia campo
    			$("#det_excesomuestra").val('');

    		// Validando intervalos
    			if (_peso < 5){
    				return;
    			}

    			var _dif = 0;
    			var _arr_peso = [];

	    		if (_peso >= 5 && _peso <= 15){
	    			$("#det_excesomuestra").val(10);
	    		}
	    		else{
	    			_dif = parseFloat($("#det_pesomuestra").val()) - 15;

	    			_arr_peso = _dif.toString().split('.');

	    			$("#det_excesomuestra").val(10 + parseFloat(_arr_peso[0]));

	    		}

    	}

  		function f_OpenAmpliacion(){
  			$("#amp_fecha").val('<?php echo $g_date; ?>');

  			// Cargando lista de Clientes según la fecha seleccionada
  				f_GetListaClientesAmpliacion();

  			f_OpenModal('modal_ampliacion');
  		}

  		function f_GetListaClientesAmpliacion(){
  			var _fecha_recepcion = $("#amp_fecha").val();
				var _html = '<option selected value="">Elija una opción...</option>';

				_html += '<option value="x" style="font-size: 6px;" disabled></option>';

				$("#amp_cliente").html('');

				$.post( "apis/backend.php", { accion: "get_ListaClientesAmpliacion", fecha_recepcion: _fecha_recepcion }, 
					function( data ) {
						if(data.estado == 1){
							$.each( data.res, function( key, val ) {
								_html += '<option value="' + val.cliente_documento + '">' + val.cliente_documento.trim() + ' - ' + val.cliente_razonsocial.trim() + '</option>';
								_html += '<option value="x" style="font-size: 6px;" disabled></option>';
							});

							$("#amp_cliente").html(_html);
						}

					}, "json");
			}

  		function f_GetListaVentasAmpliacion(){
  			var _fecha_recepcion = $("#amp_fecha").val();
  			var _documento = $("#amp_cliente").val();

				$("#tbl_ventasampliacion").html('');

				$.post( "apis/backend.php", { accion: "get_ListaVentasAmpliacion", fecha_recepcion: _fecha_recepcion, documento: _documento }, 
					function( data ) {
						if(data.estado == 1){
							$("#tbl_ventasampliacion").html(data.html);

							f_GetListaMuestrasAmpliacion(1, data.first_Id);
						}

					}, "json");
			}

  		function f_GetListaMuestrasAmpliacion(_item, _id_venta){
  			// Coloca título
  				$("#titulo_requerimientoampliacion").html($("#td_MuestraAmpliacion_" + _id_venta).html().trim());

  			// Setea el Color del item seleccionado
  				var v = 1;

  				$('#tbl_ventasampliacion tr').each(function () {
  					$("#tr_VentaAmpliacion_" + v).css('background-color', '');

            v ++;
          });

          $("#tr_VentaAmpliacion_" + _item).css('background-color', '#FFF587');

  			// Carga grilla de Muestras
  				$("#chk_MuestrasAmpliacionSelectAll").prop('checked', false);
					$("#tbl_muestrasampliacion").html('');

					$.post( "apis/backend.php", { accion: "get_ListaMuestrasAmpliacion", id_recepcion: _id_venta }, 
						function( data ) {
							if(data.estado == 1){
								$("#tbl_muestrasampliacion").html(data.html);
							}

						}, "json");
			}

			function f_SelectedMuestrasAmpliacion(_item){
    		if (_item != 0){
    			if ($("#tr_itemmuestrasampliacion_" + _item).css('background-color') == 'rgb(255, 245, 135)'){
	    			$("#tr_itemmuestrasampliacion_" + _item).css('background-color', '');

	    			$("#chk_itemmuestrasampliacion_" + _item).prop('checked', false);
	    		}
	    		else{
	    			$("#tr_itemmuestrasampliacion_" + _item).css('background-color', '#FFF587');

	    			$("#chk_itemmuestrasampliacion_" + _item).prop('checked', true);
	    		}
    		}
    		else{
    			var r = 1;

    			$('#tbl_muestrasampliacion tr').each(function () {
    				if ($("#chk_MuestrasAmpliacionSelectAll").prop('checked')){
    					$("#tr_itemmuestrasampliacion_" + r).css('background-color', '#FFF587');

    					$("#chk_itemmuestrasampliacion_" + r).prop('checked', true);
    				}
    				else{
    					$("#tr_itemmuestrasampliacion_" + r).css('background-color', '');

    					$("#chk_itemmuestrasampliacion_" + r).prop('checked', false);
    				}

            r ++;
          });
    		}
			}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			// Lista de Sucursales
				function f_GetListaSucursales(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listasucursales", is_recepcion: 1 }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '" ' + ((cab_sucursal_x.length > 0) ? ((cab_sucursal_x == val.Id) ? 'selected' : '') : ((<?php echo $_SESSION["cod_sucursal"] ?> == val.Id) ? 'selected' : '')) + '>' + val.DESCRIPCION + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#cab_sucursal").html(_html);
							}
							else{
								$("#cab_sucursal").html('');
							}

						}, "json");
				}

			// Lista de Monedas
				function f_GetListaMonedas(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listamonedas" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '" ' + ((cab_moneda_x.length > 0) ? ((cab_moneda_x == val.Id) ? 'selected' : '') : ((val.is_default == 1) ? 'selected' : '')) + '>' + val.DESCRIPCION + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#cab_moneda").html(_html);
							}
							else{
								$("#cab_moneda").html('');
							}

						}, "json");
				}

			// Tipo de Cliente
				function f_GetListaTipoCliente(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listatipocliente" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '" ' + ((val.is_default == 1) ? 'selected' : '') + '>' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#cliente_tipocliente").html(_html);
							}
							else{
								$("#cliente_tipocliente").html('');
							}

						}, "json");
				}

			// Tipo de Documento del Cliente
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

			// Obtener información del Cliente
				function f_GetInfoCliente(){
					var is_ruc = (($("#cliente_tipodocumento").val() == 2) ? 1 : 0);
					var documento = $("#cliente_documento").val();
					var arr_response = '';

					// Limpiando objetos
						$("#cliente_razonsocial").val('');
          	$("#cliente_direccion").val('');
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
			              	$("#cliente_direccion").val(arr_response[4].split(':')[1].trim());
			            	}
			            	else{
			            		$("#cliente_razonsocial").val(arr_response[0].split(':')[1].trim());
			              	$("#cliente_direccion").val('');
			            	}
		            	}
		            	else{
		            		$("#cliente_razonsocial").val('NO ENCONTRADO');
		              	$("#cliente_direccion").val('');
		            	}

		            	$("#wt_razonsocial2").hide();

		            }, "json");
						}
				}

			// Lista de Clientes para búsqueda
				function f_GetListaBuscarClientes(){
					var _html = '';

					$("#filtro_clientedocumento").val('');
					$("#filtro_clienterazonsocial").val('');

					$.post( "apis/backend.php", { accion: "get_listabuscarclientes" }, 
						function( data ) {
							if(data.estado == 1){
								$("#tbl_buscarcliente").html(data.html);
							}
							else{
								$("#tbl_buscarcliente").html('');
							}

						}, "json");
				}

			// Seleccionar Cliente
				function f_SelectCliente(_item){
					$("#cab_clientedocumento").val($("#td_buscarcliente_item_2_" + _item).html().trim());
					$("#cab_clienterazonsocial").val($("#td_buscarcliente_item_3_" + _item).html().trim());

					f_BuscarClientes();
					f_GrabarCabeceraRecepcion_Temporal();

					f_cerrarModal("modal_buscarcliente");
				}

			// Loading de Grabación de Cabecera preliminar
				function f_LoadingGrabarCabeceraPreliminar(_is_show){
					if (_is_show == 1){
						$("#wt_cabecerarecepcion").show();

						$("#btn_grabarcabecera").prop('disabled', true);
						$("#btn_grabarcabecera").css('background-color', '#C2C0A6')
					}
					else{
						$("#wt_cabecerarecepcion").hide();

						$("#btn_grabarcabecera").prop('disabled', false);
						$("#btn_grabarcabecera").css('background-color', '')
					}
				}

			// Deshabilita los objetos de Cabecera
				function f_DisableObjCabecera(_is_disabled){
					if (_is_disabled == 1){
						$(".obj_cab").prop('disabled', true);
						$(".obj_cab_img").hide();

						$("#div_grabarcabecera_prev").attr("style", "display: none !important");

						$("#div_grabarcabecera").attr("style", "display: flex !important");
						$("#div_grabarcabecera").attr("style", "padding-bottom: 5px; !important");
						$("#div_grabarcabecera").attr("style", "padding: 15px; !important");

						$("#div_detalle_prev").hide();
						$("#div_detalle").show();
					}
					else{
						$(".obj_cab").prop('disabled', false);
						$(".obj_cab_img").show();

						$("#div_grabarcabecera_prev").attr("style", "display: flex !important");
						$("#div_grabarcabecera_prev").attr("style", "padding-bottom: 5px; !important");
						$("#div_grabarcabecera_prev").attr("style", "padding: 15px; !important");

						$("#div_grabarcabecera").attr("style", "display: none !important");

						$("#div_detalle_prev").show();
						$("#div_detalle").hide();
					}
				}

			// Detecta el enter al agregar más muestras
				$("#det_nummuestras").keyup(function (e) {
			    if (e.keyCode === 13) {
			       f_Addmuestras();
			    }
			  });

			// Detecta el enter al eliminar muestras
				$("#det_nummuestras_eliminar").keyup(function (e) {
			    if (e.keyCode === 13) {
			       f_Delmuestras();
			    }
			  });

			// Lista de Estados de Muestra
				function f_GetListaEstadosMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listaestadosmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_estadomuestra").html(_html);
							}
							else{
								$("#det_estadomuestra").html('');
							}

						}, "json");
				}

			// Lista de Envases de Muestra
				function f_GetListaEnvasesMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listaenvasesmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_envasemuestra").html(_html);
							}
							else{
								$("#det_envasemuestra").html('');
							}

						}, "json");
				}

			// Lista de Tipos de Muestra
				function f_GetListaTipoMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listatiposmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_tipomuestra").html(_html);
							}
							else{
								$("#det_tipomuestra").html('');
							}

						}, "json");
				}

			// Lista de Tipos de Análisis (Ensayos)
				function f_GetListaEnsayosMuestra(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listaensayosmuestra" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option ' + ((val.Id == 1) ? 'selected' : '') + ' value="' + val.Id + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#det_ensayomuestra").html(_html);
							}
							else{
								$("#det_ensayomuestra").html('');
							}

						}, "json");
				}

			// Loading de Grabación de Detalle preliminar
				function f_LoadingGrabarDetallePreliminar(_is_show){
					if (_is_show == 1){
						$("#wt_detalle_infomuestra").show();

						$("#btn_GrabarRecepcion").prop('disabled', true);
						$("#btn_GrabarRecepcion").css('background-color', '#C2C0A6');

						$("#btn_CancelarRecepcion").prop('disabled', true);
						$("#btn_CancelarRecepcion").css('background-color', '#C2C0A6');

						$("#btn_RegresarRecepcion").prop('disabled', true);
						$("#btn_RegresarRecepcion").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_detalle_infomuestra").hide();

						$("#btn_GrabarRecepcion").prop('disabled', false);
						$("#btn_GrabarRecepcion").css('background-color', '');

						$("#btn_CancelarRecepcion").prop('disabled', false);
						$("#btn_CancelarRecepcion").css('background-color', '');

						$("#btn_RegresarRecepcion").prop('disabled', false);
						$("#btn_RegresarRecepcion").css('background-color', '');
					}
				}

    	// Pinta la muestra seleccionada
	    	function f_ColorSelected(_item){
	        var i = 1;

	        // Recorre los Tr de la tabla y los limpia
	        $("#tbl_detallemuestras tr").each(function () {
						if ($("#tr_item_" + i).css('background-color') != 'rgb(242, 162, 162)'){
							$("#tr_item_" + i).css('background-color', '');
						}

	          i ++;
	        });

	        // Seteando item seleccionado
	        	if ($("#tr_item_" + _item).css('background-color') != 'rgb(242, 162, 162)'){
	          	$("#tr_item_" + _item).css('background-color', '#FFF587');
	          }
	    	}

    	// Pinta el análisis seleccionado
	    	function f_ClasificacionSelected(_item){
	        var i = 1;
	        var _obj = '';

	        // Recorre los Tr de la tabla y los limpia
		        while (i < 100){
		        	_obj = $("#div_clasificacion_" + i);

							if (_obj.html() == undefined){
								break;
							}

		          _obj.css('background-color', '');

		          i ++;
		        }

	        // Seteando item seleccionado
	          $("#div_clasificacion_" + _item).css('background-color', '#FFF587');
	    	}

	    // Quita las filas de la tabla de Análisis de la pantalla de agregar análisis
	    	$(document).on('click', '.deltr_detalleanalisis_add', function (event) {
          event.preventDefault();

          $(this).closest('tr').remove();

          // Obtiene el número de filas actual
          	var _rows = $("#tbl_detalleanalisis_add tr").length + 1;

          // Reconstruye los correlativos
          	if (_rows > 0){
          		var r = 1;

          		$('#tbl_detalleanalisis_add tr').each(function () {
		            $(this).find("td").eq(1).html(r);

		            r ++;
		          });
          	}

          // Recalcula Subtotales
          	f_CalculaSubtotal(1);

          // Deshabilita Análisis seleccionados
						f_DisabledAnalisis(1);
        });

      // Calculla Sub total de análisis
	    	function f_CalculaSubtotal(_is_add){
	    		var _tr_subtotal = '';
    			var _subtotal = 0;
    			var _table = 'tbl_detalleanalisis';

	    		if (_is_add == 1){
	    			_table += '_add';
	    		}

	    		// Eliminar Subtotal previo
    				$("#tr_subtotal" + ((_is_add == 1) ? '_add' : '')).remove();

    			// Agregando Subtotal
		    		$('#' + _table + ' tr').each(function () {
		          _subtotal += parseFloat($(this).find("td").eq(5).html());
		        });

		        _tr_subtotal = '<tr id="tr_subtotal' + ((_is_add == 1) ? '_add' : '') + '" style="background-color: #5b2a68; color: #ffffff; font-size: 14px;">';
						_tr_subtotal += '  <td colspan="4" style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; width: 40px; color: #ffffff;">';
						_tr_subtotal += '  	 Total ' + $("#cab_moneda option:selected").text();
						_tr_subtotal += '  </td>';
						_tr_subtotal += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right; width: 40px; color: #ffffff;">';
						_tr_subtotal += '  	 ' + parseFloat(_subtotal).toFixed(2);
						_tr_subtotal += '  </td>';
						_tr_subtotal += '</tr>';

						$('#' + _table).append(_tr_subtotal);
				}

			// Desahabilita los análisis que ya fueron seleccionados
				function f_DisabledAnalisis(_is_add){
					// Arma Array de análisis seleccionados
						var idanalisis_selected = '';
						var _table = 'tbl_detalleanalisis';

		    		if (_is_add == 1){
		    			_table += '_add';
		    		}

						$('#' + _table + ' tr').each(function () {
		          idanalisis_selected += $(this).find("td").eq(3).html() + $(this).find("td").eq(6).html() + $(this).find("td").eq(7).html() + '|';
		        });

		        if (idanalisis_selected.length > 0){
		        	idanalisis_selected = idanalisis_selected.substring(0, idanalisis_selected.length - 1);
		        }

		      // Recorre análisis y los muestra
		        var _obj = '';
		        var i = 1;

		        while (i < 1000){
		        	_obj = $("#div_listaanalisis_" + i);

							if (_obj.html() == undefined){
								break;
							}

							$("#div_analisis_" + i).show();

		          i ++;
		        }

		      // Recorre Array y deshabilita análisis
		        var s = 0;
		        var arr_idanalisis = idanalisis_selected.split('|');

		        while (s < arr_idanalisis.length - 1){
		        	// Recorre los análisis y los oculta
		        		i = 1;

				        while (i < 1000){
				        	_obj = $("#div_listaanalisis_" + i);

									if (_obj.html() == undefined){
										break;
									}

				          if (_obj.val() == arr_idanalisis[s]){
				          	$("#div_analisis_" + i).hide();
				          }

				          i ++;
				        }

		        	s ++;
		        }

					// div_listaanalisis_
				}

			// Quita las filas de la tabla de Análisis de la recepción
	    	$(document).on('click', '.deltr_detalleanalisis', function (event) {
          event.preventDefault();

          $(this).closest('tr').remove();

          // Obtiene el número de filas actual
          	var _rows = $("#tbl_detalleanalisis tr").length + 1;

          // Reconstruye los correlativos
          	if (_rows > 0){
          		var r = 1;

          		$('#tbl_detalleanalisis tr').each(function () {
		            $(this).find("td").eq(1).html(r);

		            r ++;
		          });
          	}

          // Graba los análisis
          	var _selected = '';

						// Eliminar Subtotal previo
		  				$("#tr_subtotal").remove();

						// Validando que se haya ingresado al menos 1 análisis
			    		$('#tbl_detalleanalisis tr').each(function () {
			          _selected += $(this).find("td").eq(3).html() + ';' + $(this).find("td").eq(6).html() + ';' + $(this).find("td").eq(7).html() + ';' + parseFloat($(this).find("td").eq(5).html()) + '|';
			        });

			        if (_selected.length > 0){
			        	_selected = _selected.substring(0, _selected.length - 1);
			        }

			      // Grabando análisis
			        $("#wt_detalle_infomuestra").show();

			        $.post( "apis/backend.php", { accion: "grabar_MuestrasAnalisis", id_detalle: idmuestra_selected, analisis_selected: _selected, fecha_recepcion: cab_fecharecepcion_x, hora_recepcion: cab_horarecepcion_x },
								function( data ) {
									if(data.estado == 1){
										// Actualiza la tabla de Análisis
											// f_LoadDetalleAnalisis();
									}

									$("#wt_detalle_infomuestra").hide();

									// Vuelve a jalar los datos de la recepción para refresecar la hora de entrega y actualiza los datos de la cabecera
										f_GetIdRecepcion();

								}, "json");
        });

      // Selecciona las muestras a replicar
	    	function f_SelectedMuestraReplica(_item){
	    		if (_item != 0){
	    			if ($("#tr_itemreplica_" + _item).css('background-color') == 'rgb(255, 245, 135)'){
		    			$("#tr_itemreplica_" + _item).css('background-color', '');
		    		}
		    		else{
		    			$("#tr_itemreplica_" + _item).css('background-color', '#FFF587');
		    		}
	    		}
	    		else{
	    			var r = 1;

	    			$('#tbl_muestrasreplica tr').each(function () {
	    				if ($("#chk_MuestrasReplicaSelectAll").prop('checked')){
	    					$("#tr_itemreplica_" + r).css('background-color', '#FFF587');
	    				}
	    				else{
	    					$("#tr_itemreplica_" + r).css('background-color', '');
	    				}

	            r ++;
	          });
	    		}
	    	}

	    // Lista de Medios de Pago
	    	function f_GetListaMediosDePago(){
					var _html = '<option selected value="">Elija una opción...</option>';
					_html += '<option value="x" style="font-size: 6px;" disabled></option>';

					$.post( "apis/backend.php", { accion: "get_listamediospago" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '|' + val.is_efectivo + '">' + val.descripcion + '</option>';
									_html += '<option value="x" style="font-size: 6px;" disabled></option>';
								});

								$("#ins_mediospago").html(_html);
							}
							else{
								$("#ins_mediospago").html('');
							}

						}, "json");
				}

			// Si se selecciona el Tipo de Pago efectivo se mostrarán los billetes
				function f_ShowEfectivo(){
					var medio_pago = $("#ins_mediospago").val().substring(0, $("#ins_mediospago").val().indexOf('|'));
          var is_efectivo = $("#ins_mediospago").val().substring($("#ins_mediospago").val().indexOf('|') + 1);

          if (is_efectivo == 1){
            $(".ins_divefectivo").show();
	        }
	        else{
            $(".ins_divefectivo").hide();
        	}
				}

			// Suma la selección de billetes y monedas
				function f_SumaBilletes(_billete){
	        $("#total_billete" + _billete).val(parseFloat($("#total_billete" + _billete).val()) + 1);

	        f_CalcularEfectivo();
	      };

	    // Calcular el total de efectivo seleccionado
      	function f_CalcularEfectivo(){
          var total_billete200 = parseFloat($("#total_billete200").val()) * 200;
          var total_billete100 = parseFloat($("#total_billete100").val()) * 100;
          var total_billete50 = parseFloat($("#total_billete50").val()) * 50;
          var total_billete20 = parseFloat($("#total_billete20").val()) * 20;
          var total_billete10 = parseFloat($("#total_billete10").val()) * 10;

          var total_billete5 = parseFloat($("#total_billete5").val()) * 5;
          var total_billete2 = parseFloat($("#total_billete2").val()) * 2;
          var total_billete1 = parseFloat($("#total_billete1").val()) * 1;
          var total_billete50cen = parseFloat($("#total_billete50cen").val()) * 0.5;
          var total_billete20cen = parseFloat($("#total_billete20cen").val()) * 0.2;
          var total_billete10cen = parseFloat($("#total_billete10cen").val()) * 0.1;

          var total_efectivo = parseFloat(total_billete200 +
	                                        total_billete100 +
	                                        total_billete50 +
	                                        total_billete20 +
	                                        total_billete10 +
	                                        total_billete5 +
	                                        total_billete2 +
	                                        total_billete1 +
	                                        total_billete50cen +
	                                        total_billete20cen +
	                                        total_billete10cen).toFixed(2);

          $("#ins_efectivo").html(total_efectivo);
          $("#ins_cambio").html(parseFloat(total_efectivo - parseFloat(document.getElementById("ins_totalventa").innerHTML).toFixed(2)).toFixed(2));
      	};

    	// Loading de Generar Instrucción
				function f_LoadingGenerarInstruccion(_is_show){
					if (_is_show == 1){
						$("#wt_generarinstruccion").show();

						$("#btn_generarinstruccion_grabar").prop('disabled', true);
						$("#btn_generarinstruccion_grabar").css('background-color', '#C2C0A6');

						$("#btn_generarinstruccion_cerrar").prop('disabled', true);
						$("#btn_generarinstruccion_cerrar").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_generarinstruccion").hide();

						$("#btn_generarinstruccion_grabar").prop('disabled', false);
						$("#btn_generarinstruccion_grabar").css('background-color', '');

						$("#btn_generarinstruccion_cerrar").prop('disabled', false);
						$("#btn_generarinstruccion_cerrar").css('background-color', '');
					}
				}

      // Loading de Confirmación de Instrucción
				function f_LoadingConfirmarInstruccion(_is_show){
					if (_is_show == 1){
						$("#wt_confirmarinstruccion").show();

						$("#btn_insgrabar").prop('disabled', true);
						$("#btn_insgrabar").css('background-color', '#C2C0A6');

						$("#btn_inscerrar").prop('disabled', true);
						$("#btn_inscerrar").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_confirmarinstruccion").hide();

						$("#btn_insgrabar").prop('disabled', false);
						$("#btn_insgrabar").css('background-color', '');

						$("#btn_inscerrar").prop('disabled', false);
						$("#btn_inscerrar").css('background-color', '');
					}
				}

				// Loading de Confirmación de Instrucción
				function f_LoadingConfirmarInstruccion_ClienteCredito(_is_show){
					if (_is_show == 1){
						$("#wt_confirmarinstruccion_clientecredito").show();

						$("#btn_insgrabar_clientecredito").prop('disabled', true);
						$("#btn_insgrabar_clientecredito").css('background-color', '#C2C0A6');

						$("#btn_inscerrar_clientecredito").prop('disabled', true);
						$("#btn_inscerrar_clientecredito").css('background-color', '#C2C0A6');
					}
					else{
						$("#wt_confirmarinstruccion_clientecredito").hide();

						$("#btn_insgrabar_clientecredito").prop('disabled', false);
						$("#btn_insgrabar_clientecredito").css('background-color', '');

						$("#btn_inscerrar_clientecredito").prop('disabled', false);
						$("#btn_inscerrar_clientecredito").css('background-color', '');
					}
				}

			// Selección de filtros de precios
				function f_SelectedPrecios(_item){
					var s = 1;

					filtro_precios_x = _item;

					while (s <= 3){
						f_ReplaceClass("filtro_precios" + s, 'btn-primary', 'btn-outline-primary');

						s ++;
					}

					f_ReplaceClass("filtro_precios" + _item, 'btn-outline-primary', 'btn-primary');

					// Cargando la lista de Clasificaciones
						f_GetListaAnalisisClasificaciones();
				}

			// Poner Focus al momento de abrir la búsqueda de clientes
				$("#modal_buscarcliente").on('shown.bs.modal', function(){
					$(this).find('#filtro_clienterazonsocial').focus();
				});

		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Graba información temporal (onblur).
				function f_GrabarCabeceraRecepcion_Temporal(){
					// Recupera variables
						var codigo_interno = $("#cab_codigo").val();
						var fecha_recepcion = $("#cab_fecharecepcion").val();
						var hora_recepcion = $("#cab_horarecepcion").val();
						var fecha_entrega = $("#cab_fechaentrega").val();
						var hora_entrega = $("#cab_horaentrega").val();
						var documento = $("#cab_clientedocumento").val();
						var razon_social = f_CleanInjection($("#cab_clienterazonsocial").val().trim().toUpperCase());
						var entregado_por = f_CleanInjection($("#cab_entregadopor").val().trim().toUpperCase());
						var celular_areportar = f_CleanInjection($("#cab_celularareportar").val().trim().toUpperCase());
						var cod_sucursal = $("#cab_sucursal").val();
						var cod_moneda = $("#cab_moneda").val();
						var observacion = f_CleanInjection($("#cab_observacion").val().trim().toUpperCase());
						var tiene_recojo = (($("#chk_recojo").prop('checked')) ? 1 : 0);
						var dscto_porc = $("#cli_dsctoporc").html().trim();

					// Grabando datos
						f_LoadingGrabarCabeceraPreliminar(1);

            $.post( "apis/backend.php", { accion: "grabar_CabeceraRecepcion_Temporal", id_recepcion: id_recepcion, codigo_interno: codigo_interno, fecha_recepcion: fecha_recepcion, hora_recepcion: hora_recepcion, fecha_entrega: fecha_entrega, hora_entrega: hora_entrega, documento: documento, razon_social: razon_social, entregado_por: entregado_por, celular_areportar: celular_areportar, cod_sucursal: cod_sucursal, cod_moneda: cod_moneda, observacion: observacion, tiene_recojo: tiene_recojo, cli_tienedscto: cli_tienedscto, dscto_porc: dscto_porc },
              function( data ) {
                if(data.estado == 0){
                  alert("Ocurrió un error al momento de grabar la Cabecera de la Recepción.");
                }

                f_LoadingGrabarCabeceraPreliminar(0);

              }, "json");
				}

			// Graba Cliente
				function f_GrabarCliente(){
					// Recupera variables
						var cod_tipocliente = $("#cliente_tipocliente").val();
						var cod_tipodocumento = $("#cliente_tipodocumento").val();
						var documento = $("#cliente_documento").val();
						var razon_social = $("#cliente_razonsocial").val();
						var telefono1 = $("#cliente_telefono1").val();
						var telefono2 = $("#cliente_telefono2").val();
						var correo = $("#cliente_correo").val();
						var direccion = $("#cliente_direccion").val();

					// Validando datos
						if (cod_tipocliente == null){
              alert("Debe seleccionar el Tipo de Cliente.");

              return;
            }
            if (cod_tipocliente.length == 0){
              alert("Debe seleccionar el Tipo de Cliente.");

              return;
            }

            if (cod_tipodocumento == null){
              alert("Debe seleccionar el Tipo de Documento.");

              return;
            }
            if (cod_tipodocumento.length == 0){
              alert("Debe seleccionar el Tipo de Documento.");

              return;
            }

            if (documento == null){
              alert("Debe ingresar el Número de Documento del Cliente.");

              return;
            }
            if (documento.length == 0){
              alert("Debe ingresar el Número de Documento del Cliente.");

              return;
            }

            if (cod_tipodocumento == 1){
            	if (documento.length != 8){
            		alert("Usted ha seleccionado DNI, la longitud del documento es incorrecta.\nDebería tener 8 dígitos.");

              	return;
            	}
            }

            if (cod_tipodocumento == 2){
            	if (documento.length != 11){
            		alert("Usted ha seleccionado RUC, la longitud del documento es incorrecta.\nDebería tener 11 dígitos.");

              	return;
            	}
            }

            if (razon_social == null){
              alert("Debe ingresar la Razón Social del Cliente.");

              return;
            }
            if (razon_social.length == 0){
              alert("Debe ingresar la Razón Social del Cliente.");

              return;
            }

            if (correo.trim().length > 0){
            	if (!f_CheckEMail('cliente_correo')){
            		alert("El correo ingresado no tiene el formato correcto.");

              	return;
            	}
            }

          // Grabando datos
            $.post( "apis/backend.php", { accion: "grabar_cliente", modo_grabar: 'N', id_cliente: 0, cod_tipocliente: cod_tipocliente, cod_tipodocumento: cod_tipodocumento, documento: documento, razon_social: razon_social, telefono1: telefono1, telefono2: telefono2, correo: correo, direccion: direccion },
              function( data ) {
                if (data.estado == 2){
                  alert("El documento ingresado ya fue registrado anteriormente, por favor verificar");

                  return;
                }
                else{
                  if(data.estado == 1){
                    $("#cab_clientedocumento").val(documento);
                    $("#cab_clienterazonsocial").val(razon_social);

                    f_cerrarModal("modal_addcliente");
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar el Cliente");
                  }
                }

              }, "json");
				}

			// Graba Cabecera
				function f_GrabarCabecera(){
					// Recupera variables
						var codigo_interno = $("#cab_codigo").val();
						var fecha_recepcion = $("#cab_fecharecepcion").val();
						var hora_recepcion = $("#cab_horarecepcion").val();
						var fecha_entrega = $("#cab_fechaentrega").val();
						var hora_entrega = $("#cab_horaentrega").val();
						var documento = $("#cab_clientedocumento").val();
						var razon_social = $("#cab_clienterazonsocial").val();
						var cod_sucursal = $("#cab_sucursal").val();
						var cod_moneda = $("#cab_moneda").val();
						var observacion = $("#cab_observacion").val();

					// Validando datos
						if (fecha_recepcion == null){
              alert("Debe ingresar la Fecha de Recepción.");

              return;
            }
            if (fecha_recepcion.length == 0){
              alert("Debe ingresar la Fecha de Recepción.");

              return;
            }

            if (hora_recepcion == null){
              alert("Debe ingresar la Hora de Recepción.");

              return;
            }
            if (hora_recepcion.length == 0){
              alert("Debe ingresar la Hora de Recepción.");

              return;
            }

            // if (fecha_entrega == null){
            //   alert("Debe ingresar la Fecha de Entrega.");

            //   return;
            // }
            // if (fecha_entrega.length == 0){
            //   alert("Debe ingresar la Fecha de Entrega.");

            //   return;
            // }

            // if (hora_entrega == null){
            //   alert("Debe ingresar la Hora de Entrega.");

            //   return;
            // }
            // if (hora_entrega.length == 0){
            //   alert("Debe ingresar la Hora de Entrega.");

            //   return;
            // }

            // if ((fecha_recepcion + ' ' + hora_recepcion) >= (fecha_entrega + ' ' + hora_entrega)){
          	// 	alert('La Fecha y Hora de Entrega no puede ser menor o igual a la de Recepción.\n\nPor favor, verificar.');

            //   return;
            // }

            if (documento == null){
              alert("Debe ingresar el documento del Cliente.");

              return;
            }
            if (documento.length == 0){
              alert("Debe ingresar el documento del Cliente.");

              return;
            }

            if (razon_social == null){
              alert("Debe ingresar un Cliente que exista en la base de datos.");

              return;
            }
            if (razon_social.length == 0){
              alert("Debe ingresar un Cliente que exista en la base de datos.");

              return;
            }
            if (razon_social == 'NO ENCONTRADO'){
            	alert("El Cliente ingresado no es válido.");

              return;
            }

            if (cod_sucursal == null){
              alert("Debe seleccionar la Sucursal.");

              return;
            }
            if (cod_sucursal.length == 0){
              alert("Debe seleccionar la Sucursal.");

              return;
            }

            if (cod_moneda == null){
              alert("Debe seleccionar la Moneda.");

              return;
            }
            if (cod_moneda.length == 0){
              alert("Debe seleccionar la Moneda.");

              return;
            }

          // Grabando datos
						$("#wt_cabecerarecepcion").show();

		        $.post( "apis/backend.php", { accion: "grabar_CabeceraRecepcion", id_recepcion: id_recepcion },
		          function( data ) {
		            if(data.estado == 1){
		            	f_DisableObjCabecera(1);

		            	f_getRecepcionDetalle();
		            }
		            else{
		              alert("Ocurrió un error al momento de grabar la Cabecera de la Recepción.");
		            }

		            $("#wt_cabecerarecepcion").hide();

		          }, "json");
				}

			// Cancelar Recepción
				function f_CancelarRecepcion(){
					if (!confirm("¿Está seguro de Cancelar la Recepción?\n\nSi continua perderá toda la información.")){
						return;
					}

					// Cancelando Recepción
						$("#btn_GrabarRecepcion").prop('disabled', true);

		        $.post( "apis/backend.php", { accion: "cancelar_Recepcion", id_recepcion: id_recepcion },
		          function( data ) {
		            if(data.estado == 1){
		            	window.open('recepcion_ensayos.php', '_self');
		            }
		            else{
		              alert("Ocurrió un error al momento de Cancelar la Recepción.");
		            }

		            $("#btn_GrabarRecepcion").prop('disabled', false);

		          }, "json");
				}

			// Regresar Recepción (Back)
				function f_RegresarRecepcion(){
					if (!confirm("¿Está seguro de regresar a los datos de la Recepción?")){
						return;
					}

					// Regresando
						$("#btn_GrabarRecepcion").prop('disabled', true);
						$("#btn_CancelarRecepcion").prop('disabled', true);

		        $.post( "apis/backend.php", { accion: "regresar_Recepcion", id_recepcion: id_recepcion },
		          function( data ) {
		            if(data.estado == 1){
		            	f_DisableObjCabecera(0);
		            }
		            else{
		              alert("Ocurrió un error al momento de Cancelar la Recepción.");
		            }

		            $("#btn_GrabarRecepcion").prop('disabled', false);
								$("#btn_CancelarRecepcion").prop('disabled', false);

		          }, "json");
				}

			// Agregar muestras
				function f_Addmuestras(){
					var num_muestras = $("#det_nummuestras").val();

					// Validando número de muestras
						if (num_muestras == null){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras.length == 0){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras <= 0){
	            alert("El número de muestras ingresado no es válido.");

	            return;
	          }

	        // Obtener lista de muestras
	          $("#tbl_detallemuestras").html('');
	          $("#wt_detalle_muestras").show();

						$.post( "apis/backend.php", { accion: "add_RecepcionDetalleMuestras", num_muestras: num_muestras, id_recepcion: id_recepcion }, 
							function( data ) {
								if(data.estado == 1){
									f_getRecepcionDetalle();
								}

								$("#wt_detalle_muestras").hide();

							}, "json");
				}

			// Eliminar muestras
				function f_Delmuestras(){
					var num_muestras = $("#det_nummuestras_eliminar").val();

					// Validando número de muestras
						if (num_muestras == null){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras.length == 0){
	            alert("Debe ingresar un número de muestras válido.");

	            return;
	          }
	          if (num_muestras <= 0){
	            alert("El número de muestras ingresado no es válido.");

	            return;
	          }

	        if (!confirm("¿Está seguro de eliminar: " + num_muestras + " muestra(s).?\nSi continua perderá la información permanentemente.\n\n¿Desea continuar?")){
	        	return;
	        }

	        // Obtener lista de muestras
	          $("#tbl_detallemuestras").html('');
	          $("#wt_detalle_muestras").show();

						$.post( "apis/backend.php", { accion: "del_RecepcionDetalleMuestras", num_muestras: num_muestras, id_recepcion: id_recepcion },
							function( data ) {
								if(data.estado == 1){
									f_getRecepcionDetalle();

									$("#det_nummuestras_eliminar").val('');
								}

								$("#wt_detalle_muestras").hide();

							}, "json");
				}

			// Elimina las Muestras registradas
	    	$(document).on('click', '.deltr_detalle', function (event) {
          event.preventDefault();

          if (!confirm("¿Está seguro de eliminar la muestra seleccionada?")){
          	return;
          }

          // $(this).closest('tr').remove();

          // Eliminando Muestra
          	$.post( "apis/backend.php", { accion: "eliminar_MuestraRecepción", id_recepcion: id_recepcion, id_detalle: idmuestra_selected },
							function( data ) {
								if(data.estado == 1){
									f_getRecepcionDetalle();

									$("#det_nummuestras_eliminar").val('');
								}

								$("#wt_detalle_muestras").hide();

							}, "json");

          // Carga nuevamente el listado de muestras
          	// f_getRecepcionDetalle();
        });

			// Graba información temporal (onblur).
				function f_GrabarDetalleRecepcion_Temporal(){
					// Recupera variables
						var ci_muestra = $("#td_detalle_3_" + item_selected).html().trim();
						var nombre_muestra = f_CleanInjection($("#det_nombremuestra").val().trim().toUpperCase());
						var peso_muestra = $("#det_pesomuestra").val();
						var estado_muestra = $("#det_estadomuestra").val();
						var envase_muestra = $("#det_envasemuestra").val();
						var tipo_muestra = $("#det_tipomuestra").val();
						var ensayo_muestra = $("#det_ensayomuestra").val();
						var exceso_muestra = $("#det_excesomuestra").val();
						var observacion = f_CleanInjection($("#det_observacion").val().trim().toUpperCase());

					// Grabando datos
						f_LoadingGrabarDetallePreliminar(1);

            $.post( "apis/backend.php", { accion: "grabar_DetalleRecepcion_Temporal", id_detalle: idmuestra_selected, ci_muestra: ci_muestra, nombre_muestra: nombre_muestra, peso_muestra: peso_muestra, estado_muestra: estado_muestra, envase_muestra: envase_muestra, tipo_muestra: tipo_muestra, ensayo_muestra: ensayo_muestra, exceso_muestra: exceso_muestra, observacion: observacion },
              function( data ) {
                if(data.estado == 0){
                  alert("Ocurrió un error al momento de grabar el Detalle de la Recepción.");
                }

                f_LoadingGrabarDetallePreliminar(0);

              }, "json");
				}

			// Graba Análisis
				function f_ConfirmarAnalisis(){
					var _selected = '';

					// Eliminar Subtotal previo
	  				$("#tr_subtotal_add").remove();

					// Validando que se haya ingresado al menos 1 análisis
		    		$('#tbl_detalleanalisis_add tr').each(function () {
		          _selected += $(this).find("td").eq(3).html() + ';' + $(this).find("td").eq(6).html() + ';' + $(this).find("td").eq(7).html() + ';' + parseFloat($(this).find("td").eq(5).html()) + '|';
		        });

		        if (_selected.length == 0){
		        	alert("Debe ingresar al menos un análisis.");

		        	return;
		        }
		        else{
		        	_selected = _selected.substring(0, _selected.length - 1);
		        }

		      // Grabando análisis
		        $("#wt_detalleanalisis_add").show();

		        $.post( "apis/backend.php", { accion: "grabar_MuestrasAnalisis", id_detalle: idmuestra_selected, analisis_selected: _selected, fecha_recepcion: cab_fecharecepcion_x, hora_recepcion: cab_horarecepcion_x },
							function( data ) {
								if(data.estado == 1){
									// Actualiza el html de la tabla de análisis en la del detalle
										$("#tbl_detalleanalisis").html($("#tbl_detalleanalisis_add").html().replace(/id="tr_subtotal_add"/g, 'id="tr_subtotal"').replace(/"deltr_detalleanalisis_add"/g, '"deltr_detalleanalisis"').replace(/id="td_x_add"/g, 'id="td_x"'));

									// Calcular Subtotal
										f_CalculaSubtotal(0);

									// Vuelve a jalar los datos de la recepción para refresecar la hora de entrega y actualiza los datos de la cabecera
										f_GetIdRecepcion();
								}

								$("#wt_detalleanalisis_add").hide();

								f_cerrarModal("modal_addanalisis");

							}, "json");
				}

			// Grabar Recepción
				function f_GrabarRecepcion(){
					var _id_muestras = '';
					var m = 1;
					var arrmuestras_verify = '';
					var x = 1;

					// Arma array con cada muestra para detectar datos faltantes
						$('#tbl_detallemuestras tr').each(function () {
		          _id_muestras += $("#id_detalle_x_" + m).val() + ', ';

		          m ++;
		        });

	        // Actualiza en la tabla los datos (para cuando se actualiza la fecha y hora de entrega al momento de agregar muestras)
						f_GrabarCabeceraRecepcion_Temporal();

		      // Realiza la verificación y/o graba la recepción
						_id_muestras = _id_muestras.substring(0, _id_muestras.length - 2);

						$.post( "apis/backend.php", { accion: "grabar_Recepcion", id_recepcion: id_recepcion, arr_idmuestras: _id_muestras }, 
							function( data ) {
								if(data.estado == 1){
									f_ImprimirRecibo(1);

									// Si es Cliente con Crédito
										$("#btn_generarinstruccion_cerrar").show();
										$("#btn_generarinstruccion_grabar").show();
										$("#btn_insgrabar_clientecredito").hide();
										$("#btn_generarinstruccion_printconfirmacion").css('margin-left', '');

										if (cli_tienecredito == 1){
											$("#btn_generarinstruccion_cerrar").hide();
											$("#btn_generarinstruccion_grabar").hide();
											$("#btn_insgrabar_clientecredito").show();

											$("#btn_generarinstruccion_printconfirmacion").css('margin-left', '130px');
										}

									f_OpenModal('modal_confirmarrecepcion');

									return;
								}

								if(data.estado == 2 || data.estado == 3){
									if (data.estado == 2){
										alert("Se han detectado muestras con información incompleta.\nPor favor revise las muestras resaltadas en rojo.");
									}
									else{
										alert("Se han detectado muestras que no tienen análisis asignados.\nPor favor revise las muestras resaltadas en rojo.");
									}

									arrmuestras_verify = '|' + data.idmuestras_verify + '|';

									$('#tbl_detallemuestras tr').each(function () {
										$("#tr_item_" + x).css('background-color', '');

										if (arrmuestras_verify.includes('|' + $("#id_detalle_x_" + x).val() + '|')){
											$("#tr_item_" + x).css('background-color', '#F2A2A2');
										}

					          x ++;
					        });

					        return;
								}
								else{
									alert("Ocurrió un error al momento de grabar la recepción.");
								}

							}, "json");
				}

			// Replicar Muestras
				function f_EjecutarReplica(){
					var r = 1;
					var arr_idmuestras = '';

					// Obtiene un array de las muestras seleccionadas para replicar
						$('#tbl_muestrasreplica tr').each(function () {
	    				if ($("#tr_itemreplica_" + r).css('background-color') == 'rgb(255, 245, 135)'){
	    					arr_idmuestras += $("#id_replica_x_" + r).val() + '|';
	    				}

	            r ++;
	          });

	          if (arr_idmuestras.length == 0){
	          	alert("No ha seleccionado ninguna muestras para replicar.");

	          	return;
	          }
	          else{
	          	$.post( "apis/backend.php", { accion: "replicar_MuestraAnalisis", id_detalle: idmuestra_selected, arr_idmuestras: arr_idmuestras.substring(0, arr_idmuestras.length - 1) }, 
								function( data ) {
									if(data.estado == 1){
										f_cerrarModal("modal_replicarmuestra");
									}
								});
	          }
				}

			// Confirmar Instrucción
				function f_ConfirmarInstruccion(){
					var medio_pago = '';
					var is_efectivo = '';
					var total_efectivo = '';
					var cambio = '';
					var is_factura = '';
					var total_venta = '';
					var efectivo_ingresado = '';
					var total_billete200 = '';
					var total_billete100 = '';
					var total_billete50 = '';
					var total_billete20 = '';
					var total_billete10 = '';
					var total_billete5 = '';
					var total_billete2 = '';
					var total_billete1 = '';
					var total_billete50cen = '';
					var total_billete20cen = '';
					var total_billete10cen = '';

					if (cli_tienecredito == 0){
						// Validaciones para cuando sea "Efectivo"
	            medio_pago = $("#ins_mediospago").val().substring(0, $("#ins_mediospago").val().indexOf('|'));
	            is_efectivo = $("#ins_mediospago").val().substring($("#ins_mediospago").val().indexOf('|') + 1);

	            if (medio_pago == null){
	                alert("Debe seleccionar el Medio de Pago.");

	                return;
	            }
	            if (medio_pago.length == 0){
	                alert("Debe seleccionar el Medio de Pago.");

	                return;
	            }

	            if (is_efectivo == 1){
                total_efectivo = parseFloat(document.getElementById("ins_totalventa").innerHTML).toFixed(2);
                cambio = parseFloat(document.getElementById("ins_cambio").innerHTML).toFixed(2);

                if (medio_pago == 3){
                  if (parseFloat(cambio) < 0 || isNaN(cambio)){
                    alert('El efectivo ingresado aún no cubre el total de la venta.'+"\n\n"+'Por favor, verificar.');

                    return;
                  }
                }
                else{
                  if (total_efectivo == 0){
                    alert("No ha ingresado efectivo. \n\nPor favor, verificar.");

                    return;
                  }
                }
	            }

	          // Parámetros de pago
	            is_factura = (($("#chk_Comprobante").prop('checked')) ? 1 : 0);
	            total_venta = parseFloat(document.getElementById("ins_totalventa").innerHTML).toFixed(2);
	            efectivo_ingresado = parseFloat(document.getElementById("ins_efectivo").innerHTML).toFixed(2);

	            total_billete200 = parseFloat($("#total_billete200").val());
	            total_billete100 = parseFloat($("#total_billete100").val());
	            total_billete50 = parseFloat($("#total_billete50").val());
	            total_billete20 = parseFloat($("#total_billete20").val());
	            total_billete10 = parseFloat($("#total_billete10").val());

	            total_billete5 = parseFloat($("#total_billete5").val());
	            total_billete2 = parseFloat($("#total_billete2").val());
	            total_billete1 = parseFloat($("#total_billete1").val());
	            total_billete50cen = parseFloat($("#total_billete50cen").val());
	            total_billete20cen = parseFloat($("#total_billete20cen").val());
	            total_billete10cen = parseFloat($("#total_billete10cen").val());
          }

          var dscto_porc = 'NULL';

          if (cli_tienedscto == 1){
          	dscto_porc = $("#cli_dsctoporc").html().trim();
          }

          // Confirmar Instrucción
            if (!confirm("¿Está seguro de Confirmar la Instrucción?")){
            	return;
            }

            if (cli_tienecredito == 0){
            	f_LoadingConfirmarInstruccion(1);
            }
            else{
            	f_LoadingConfirmarInstruccion_ClienteCredito(1);
            }

            $.post( "apis/backend.php", { accion: "confirmar_Instruccion", id_recepcion: id_recepcion, is_factura: is_factura, medio_pago: medio_pago, total_venta: total_venta, efectivo_ingresado: efectivo_ingresado, total_billete200: total_billete200, total_billete100: total_billete100, total_billete50: total_billete50, total_billete20: total_billete20, total_billete10: total_billete10, total_billete5: total_billete5, total_billete2: total_billete2, total_billete1: total_billete1, total_billete50cen: total_billete50cen, total_billete20cen: total_billete20cen, total_billete10cen: total_billete10cen, cliente_tienecredito: cli_tienecredito, cli_tienedscto: cli_tienedscto, dscto_porc: dscto_porc }, 
							function( data ) {
								if(data.estado == 1){
									f_ImprimirRecibo(0);

									if (cli_tienecredito == 0){
										var url = 'https://apicooper.wsystem.world/invoices/pdf/20602385371-' + ((is_factura == 1) ? '01-' : '03-') + data.num_comprobante + '.pdf';

										window.open(url,'_blank',"");
									}

									window.open('recepcion_ensayos.php', '_self');

									return;
								}

								if(data.estado == 3){
									alert("Ocurrió un error al momento de generar el comprobante. Por favor, diríjase al módulo de Facturación para realizarlo manualmente.");
								}
								else{
									alert("Ocurrió un error al momento de Confirmar la Instrucción. Código de errror: " + data.estado);
								}

								window.open('recepcion_ensayos.php', '_self');

								if (cli_tienecredito == 0){
									f_LoadingConfirmarInstruccion(0);
								}
								else{
									f_LoadingConfirmarInstruccion_ClienteCredito(0);
								}

							}, "json");
				}

			// Actualizar Teléfono del Cliente
				function f_ActualizarTelefonoCliente(){
					var documento_cliente = $("#cab_clientedocumento").val().trim()
					var telefono = $("#cab_celularareportar").val().trim();

					if (telefono.length > 0 && documento_cliente.length > 0){
						$.post( "apis/backend.php", { accion: "update_TelefonoCliente", documento_cliente: documento_cliente, telefono: telefono },
							function( data ) {
								if(data.estado == 1){
									f_getRecepcionDetalle();

									$("#det_nummuestras_eliminar").val('');
								}

								$("#wt_detalle_muestras").hide();

							}, "json");
					}
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