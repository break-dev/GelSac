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

  	<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Administración de Usuarios</title>

		<script type="text/javascript">

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


				<div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
					<div class="d-flex row">
						<div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
							<div class="row" style="padding: 20px;">
								<div class="col-md-10 col-sm-10 col-xs-10">
									<h5>Resumen de Usuarios</h5>
								</div>

								<div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
									<button class="btn btn-primary" type="button" onclick="f_AdminUsuario('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
			              <b> + Nuevo Usuario</b>
			            </button>
								</div>

								
							</div>

							<div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
								<hr style="border-color: #D9D9D9;"/>
							</div>

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
								<table>
									<table class="table table-bordered table-hover">
					        	<thead>
					        		<tr style="font-size: 14px;">
					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
					        				N°
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Rol
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Sucursal
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Usuario
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Empleado
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
					        				Estado
					        			</th>

					        			<th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-right-radius: 15px;">
					        				Acción
					        			</th>
					        		</tr>
					        	</thead>

					        	<tbody id="tbl_detalle">

					        	</tbody>
					        </table>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_addusuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addusuarioLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 id="modal_titulousuario" class="modal-title fs-5" id="modal_addusuarioLabel">Nuevo Usuario</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Rol:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="usu_rol" class="form-select" style="text-align: left;">

								</select>
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Sucursal:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<select id="usu_sucursal" class="form-select" style="text-align: left;">

								</select>
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 5px;">
								Empleado:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-12">
								<select id="usu_empleado" class="form-select" data-placeholder="Elija una opción..." data-titulo="Empleados" onclick="f_ShowListaModal(this);">

										<option selected value="">Elija una opción...</option>

										<?php

										$t = 1;

										$q_empleado = "SELECT Id,
	                        								nombres,
	                        								apellido_paterno
					                           FROM tb_empleados
					                          WHERE estado = 'A'
					                         ORDER BY nombres, apellido_paterno";

						        if ($res_empleado = mysqli_query($enlace, $q_empleado)){
						          if (mysqli_num_rows($res_empleado) > 0) {
						            while($row_empleado = mysqli_fetch_array($res_empleado)){
						              ?>

						              <option value="<?php echo $row_empleado["Id"]; ?>"><?php echo $row_empleado["nombres"].' '.$row_empleado["apellido_paterno"]; ?></option>


						              <?php

						              $t ++;
						            }
						          }
						        }

										?>

									</select>
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Usuario:
							</div>

							<div class="col-md-8 col-sm-8 col-xs-8">
								<input id="usu_usuario" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>
						</div>

						<div class="d-flex" style="padding: 5px;">
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
								Clave:
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="usu_clave1" type="password" class="form-control col-md-12 col-xs-12" style="text-align: center;">
							</div>

							<div class="col-md-4 col-sm-4 col-xs-4">
								<input id="usu_clave2" type="password" class="form-control col-md-12 col-xs-12" placeholder="Repetir clave" style="text-align: center;">
							</div>
						</div>
		      </div>

		      <input id="hd_idusuario" type="hidden">
		      <input id="hd_modograbar" type="hidden">

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
		        <button type="button" class="btn btn-primary" onclick="f_GrabarUsuario();">Grabar</button>
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
					$("#nv_titulo").html('| Administración de Usuarios');

				// Cargando listas generales
					f_GetListaRoles();
					f_GetListaSucursales();

				// Carga el detalle de información
					f_LoadResultados();
			}
		</script>

		<!-- Seteando objetos Select2 -->
		<script type="text/javascript">
			// Lista de Unidades
			  // $('#usu_empleado').select2({
			  //   theme: "bootstrap-5",
			  //   width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
			  //   placeholder: $( this ).data( 'placeholder' ),
			  //   allowClear: true,
			  //   dropdownParent: $('#modal_addusuario')
				// }).on('select2:open', function() {
				// 	$('body').css('zoom', '100%'); 
				// }).on('select2:close', function() {
				//     $('body').css('zoom', '80%'); // Vuelve a aplicar el zoom al cerrar el dropdown
				// });
		</script>

		<!-- Funciones Principales -->
		<script type="text/javascript">
			function f_LoadResultados(){
        var _html = '';
        var d = 1;

        var bk_color = '';
        var estado = '';
        var href_estado = '';
        var href_color = '';
        var href_icon = '';

        $("#tbl_detalle").html('');

        $.post( "apis/backend.php", { accion: "get_listausuarios" }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.nom_rol;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.des_sucursal;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.usu_usuario;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.NOMBRES;
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

                  _html += '      <a class="success" href="javascript: f_AdminUsuario(' + d + ', ' + val.Id + ', ' + val.cod_rol	+ ', ' + val.cod_sucursal + ", '" + val.usu_usuario + "', " + val.id_empleado + ')"><i class="bi bi-pencil-square"></i>';
                  _html += '          <font style="color: #337ab7;"> Editar</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_CambiarEstado(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                  _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                  _html += '      </a>';

                  _html += '<br>';

                  if (val.usu_usuario != '<?php echo $_SESSION["usu_usuario"] ?>'){
                      _html += '      <a class="success" href="javascript: f_EliminarUsuario(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                      _html += '          <font style="color: #F20505;"> Eliminar</font>';
                      _html += '      </a>';
                  }

                  _html += '<br>';

                  _html += '      <a class="success" href="javascript: f_ResetClave(' + val.Id + ", '" + val.usu_usuario + "'" + ')"><i class="bi bi-arrow-clockwise"></i>';
                  _html += '          <font style="color: #7D1D88;"> Resetear Clave</font>';
                  _html += '      </a>';

                _html += '</tr>';

                d += 1;
              });
            }
            else{
              alert("No se encontraron resultados.");
            }

            $("#tbl_detalle").html(_html);

          }, "json");
    	};

    	function f_AdminUsuario(_item, _id_usuario, _cod_rol, _cod_sucursal, _usu_usuario, _id_empleado){
    		// Definiendo título de ventana e Inicilizando controles de tipo texto
          if (_item != 'x'){
            tipo = "E";
            titulo = 'Editar Usuario: "<b>'+_usu_usuario+'</b>"';
	        }
	        else{
            tipo = "N";
            titulo = "Nuevo Usuario";
	        }

		    // Colocando el título a la pantalla
	        $("#modal_titulousuario").html(titulo);

		    // Identificando el tipo de grabación
	        $("#hd_modograbar").val(tipo);

		    // Cargando datos
	        f_OpenModal('modal_addusuario');

	        if (tipo != 'N'){
            $("#hd_idusuario").val(f_CleanInjection(_id_usuario));
            $("#usu_rol").val(_cod_rol);
		        $("#usu_sucursal").val(_cod_sucursal);
            $("#usu_usuario").val(f_CleanInjection(_usu_usuario));
            $("#usu_clave1").val('******');
            $("#usu_clave2").val('******');
		        $("#usu_empleado").val(_id_empleado);
		        $("#usu_empleado").trigger('change');

	          $("#usu_clave1").prop('disabled', true);
	          $("#usu_clave2").prop('disabled', true);
			    }
			    else{
			    	$("#hd_idusuario").val(0);
		        $("#usu_rol").val('');
		        $("#usu_sucursal").val('');
		        $("#usu_usuario").val('');
		        $("#usu_clave1").val('');
		        $("#usu_clave2").val('');
		        $("#usu_empleado").val('');

		        $("#usu_clave1").prop('disabled', false);
		        $("#usu_clave2").prop('disabled', false);
		   		}
    	}
		</script>

		<!-- Funciones Secundarias -->
		<script type="text/javascript">
			// Lista de Roles
				function f_GetListaRoles(){
					var _html = '<option selected value="">Elija una opción...</option>';

					$.post( "apis/backend.php", { accion: "get_listaroles" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.DESCRIPCION + '</option>';
								});

								$("#usu_rol").html(_html);
							}
							else{
								$("#usu_rol").html('');
							}

						}, "json");
				}

			// Lista de Sucursales
				function f_GetListaSucursales(){
					var _html = '<option selected value="">Elija una opción...</option>';

					$.post( "apis/backend.php", { accion: "get_listasucursales" }, 
						function( data ) {
							if(data.estado == 1){
								$.each( data.res, function( key, val ) {
									_html += '<option value="' + val.Id + '">' + val.DESCRIPCION + '</option>';
								});

								$("#usu_sucursal").html(_html);
							}
							else{
								$("#usu_sucursal").html('');
							}

						}, "json");
				}
		</script>

		<!-- Funciones de Grabación -->
		<script type="text/javascript">
			// Graba información temporal (onblur).
				function f_GrabarUsuario(){
					// Recupera variables
						var id_usuario = $("#hd_idusuario").val();
						var modo_grabar = $("#hd_modograbar").val();

            var usu_rol = f_CleanInjection($("#usu_rol").val());
            var usu_sucursal = f_CleanInjection($("#usu_sucursal").val());
            var usu_usuario = f_CleanInjection($("#usu_usuario").val());
            var usu_clave1 = f_CleanInjection($("#usu_clave1").val());
            var usu_clave2 = f_CleanInjection($("#usu_clave2").val());
            var usu_empleado = f_CleanInjection($("#usu_empleado").val());

          // Validando datos
              if (usu_rol == null){
                alert("Debe seleccionar el Rol.");

                return;
              }
              if (usu_rol.length == 0){
                alert("Debe seleccionar el Rol.");

                return;
              }

              if (usu_sucursal == null){
                alert("Debe seleccionar la Sucursal.");

                return;
              }
              if (usu_sucursal.length == 0){
                alert("Debe seleccionar la Sucursal.");

                return;
              }

              if (usu_empleado == null){
                alert("Debe seleccionar el Empleado.");

                return;
              }
              if (usu_empleado.length == 0){
                alert("Debe seleccionar el Empleado.");

                return;
              }

              if (usu_usuario == null){
                alert("Debe ingresar el Usuario.");

                return;
              }
              if (usu_usuario.length == 0){
                alert("Debe ingresar el Usuario.");

                return;
              }
              if (usu_usuario.indexOf('"') >= 0){
                alert("No se puede utilizar COMILLAS en el Usuario." + "\r\n" + "Por favor, corregir.");

                return;
              }
              if (usu_usuario.indexOf("'") >= 0){
                alert("No se puede utilizar COMILLAS SIMPLES en el Usuario." + "\r\n" + "Por favor, corregir.");

                return;
              }

              if (usu_clave1 == null){
                alert("Debe ingresar la Clave.");

                return;
              }
              if (usu_clave1.length == 0){
                alert("Debe ingresar la Clave.");

                return;
              }

              if (usu_clave2 == null){
                alert("Debe repetir la Clave.");

                return;
              }
              if (usu_clave2.length == 0){
                alert("Debe repetir la Clave.");

                return;
              }

              if (usu_clave1.length == 0 || usu_clave2.length == 0){
                alert("Debe ingresar la clave en ambos campos.")

                return;
              }

              if (usu_clave1 != usu_clave2){
                alert("Las Claves ingresadas no coinciden.\n\nPor favor, verificar.");

                return;
              }

          // Grabando Datos
            $.post( "apis/backend.php", { accion: "grabar_usuario", id_usuario: id_usuario, modo_grabar: modo_grabar, usu_rol: usu_rol, usu_sucursal: usu_sucursal, usu_usuario: usu_usuario, usu_clave: usu_clave1, usu_empleado: usu_empleado },
              function( data ) {
                if (data.estado == 2){
                  alert("El Usuario ingresado ya fue registrado anteriormente, por favor verificar");

                  return;
                }
                else{
                  if(data.estado == 1){
                  	f_LoadResultados();

                  	f_cerrarModal('modal_addusuario');
                  }
                  else{
                    alert("Ocurrió un error al momento de grabar el Usuario");
                  }
                }

              }, "json");
				}

			// Cambiar estado de registros
        function f_CambiarEstado(_Estado, _id_usuario){
          var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

          // Validando datos
            if (_Estado != 'A' && _Estado != 'I'){
              alert("Ocurrió un error al momento de cambiar el estado");

              return;
            }

          if(confirm("¿Está seguro de " + estado + " el Usuario seleccionado?")){
            $.post( "apis/backend.php", { accion: "update_EstadoUsuario", id_usuario: _id_usuario, estado: _Estado }, 
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de cambiar el estado");
                }

              }, "json");
          }
        };

      // Eliminar registros
        function f_EliminarUsuario(_id_usuario){
          if(confirm("¿Está seguro de eliminar el Usuario seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
            $.post( "apis/backend.php", { accion: "eliminar_Usuario", id_usuario: _id_usuario },
              function( data ) {
                if(data.estado == 1){
                  f_LoadResultados();
                }
                else{
                  alert("Ocurrió un error al momento de eliminar el Usuario.");
                }
              }, "json");
          }
        };

      // Resetar Clave
        function f_ResetClave(_id_usuario, _usu_usuario){
	        if (confirm('Está seguro de resetear la clave del usuario: "' + _usu_usuario + '"')){
	          $.post( "apis/backend.php", { accion: "reset_UsuarioPassword", id_usuario: _id_usuario }, 
	            function( data ) {
	              if(data.estado == 1){
	                alert('La clave fue reseteada satisfactoriamente.\nLa nueva clave es: 123456');
	              }
	              else{
	                alert("Ocurrió un error al momento de resetear la clave");
	              }
	          }, "json");
	        }
	      }
		</script>


		<!-- Funciones de Menús -->
		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("#offcanvasExample").css('width', '60%');
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