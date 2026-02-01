<?php

	session_start();

	include('global/variables.php');
	include('global/auxiliares.php');
  include('cnx/cnx.php');

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

		<link rel="stylesheet" href="<?php echo $url_lims?>/global/styles.css">

		<title><?php echo $nom_app; ?> | Bienvenido</title>
	
	</head>

	<body class="login fondo-login-background" onload="f_SetDimension();">

		<div class="main" style="min-height: 100vh; position: relative;">
			<div class="_login" style="padding-top: 7%; padding-left: 50px; padding-right: 50px; position: relative; z-index: 2; opacity: 0.8;">
				<div class="row justify-content-center">
					<div class="card col-md-6 col-lg-4 py-3" style="margin-top: 30px; border: solid; border-width: 1px; border-color: #ba9842; border-radius: 7px; background-color: #fff;">
	            <div class="card-body" style="text-align: center; font-size: 14px;">
	            	<div class="d-flex justify-content-center" style="width: 100%; border: solid; border-width: 1px; background-color: #fff; border-color: transparent; border-radius: 7px;">
                	<img src="<?php echo $img_logo; ?>" style="width: 200px;">
                </div>

                <div class="form-group row" style="text-align: left;">
                  <label for="user" class="col-12 col-form-label" style="font-weight: bold; color: #000; font-size: 12px;">Usuario</label>

                  <div class="col-12">
                    <input id="user" type="user" class="form-control" style="font-size: 14px;" name="user" value="" required="" autofocus="">
                  </div>
                </div>

                <div class="form-group row" style="text-align: left;">
                  <label for="password" class="col-12 col-form-label" style="font-weight: bold; color: #000; font-size: 12px;">Contraseña</label>

                  <div class="col-12">
                    <input id="password" type="password" class="form-control" style="font-size: 14px;" name="password" required="" autocomplete="current-password" value="">
                  </div>
                </div>

                <div class="form-group row" style="text-align: left;">
                  <label for="id_sucursal" class="col-12 col-form-label" style="font-weight: bold; color: #000; font-size: 12px;">Sucursal</label>

                  <div class="col-12">
                    <select id="id_sucursal" class="form-select" style="font-size: 14px;">
                      <option selected value="">Elija una opción...</option>

                      <?php

                      $q_sucursales = "SELECT Id,
                                              cod_sucursal,
                                              des_sucursal
                                         FROM tb_sucursal
                                        WHERE estado_sucursal = 'A'";

                      if ($res_sucursales = mysqli_query($enlace, $q_sucursales)) {
                        if (mysqli_num_rows($res_sucursales) > 0) {
                          while ($row_sucursales = mysqli_fetch_array($res_sucursales)) {
                            ?>

                            <option data-id_sucursal="<?php echo $row_sucursales["cod_sucursal"] ?>" value="<?php echo $row_sucursales["Id"] ?>"><?php echo $row_sucursales["des_sucursal"] ?></option>

                            <?php
                          }
                        }
                      }

                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group row mb-0 text-center" style="margin-top: 20px;">
                  <div class="col-md-12">
                    <button id="btn_LogIn" class="btn btn-primary btn-block" style="font-size: 14px;">
                      Iniciar sesión
                    </button>

                    <div class="spinner-border text-primary" role="status" style="display: none;">
										  <!-- <span class="visually-hidden">Loading...</span> -->
										</div>
                  </div>

                  <div class="col-md-12" style="margin-top: 5px;">
                  	<label style="cursor: pointer; color: #000; font-size: 14px;" onclick="f_OpenUpdateContrasena();">
                  		<u>¿Olvidó su contraseña?</u>
                  	</label>
                  </div>
                </div>
	            </div>
		        </div>
				</div>

				<div class="row justify-content-center">
					<div class="col-md-6 col-lg-4 py-3" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; height: 15px; background-color: #785a15; text-align: center;">
						<div style="margin-top: -8px;">
							<label style="font-size: 10px; color: #ffffff;"> <?php echo $nom_empresa.' - '.get_nombre_mes($g_mes).' '.$g_anho?></label>
						</div>
					</div>

					<div style="display: none;">
						<iframe src="https://sqsale.app/guiaremisionc" frameborder="0" width="100%" height="1200"></iframe>
					</div>
				</div>

				<div class="row justify-content-center" hidden>
					<select id="voiceList"></select>
				</div>
			</div>

		</div>

		<!-- Ventanas modales -->
		<div class="modal fade" id="modal_changepassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_changepasswordLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div id="modal_changepassword_content" class="modal-content">
		      <div class="modal-header" style="background-color: #f8da62;">
		        <h1 class="modal-title fs-6" id="modal_changepasswordLabel">Cambie su contraseña</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <div class="row" style="padding: 5px; font-size: 14px;">
							<div class="col-md-5 col-sm-5 col-xs-5" style="padding: 5px;">
								Contraseña Actual
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<input id="clave_old" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; font-size: 14px;">
							</div>
						</div>

						<div class="row" style="padding: 5px; font-size: 14px;">
							<div class="col-md-5 col-sm-5 col-xs-5" style="padding: 5px;">
								Nueva Contraseña
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<input id="clave_new" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; font-size: 14px;">
							</div>
						</div>

						<div class="row" style="padding: 5px; font-size: 14px;">
							<div class="col-md-5 col-sm-5 col-xs-5" style="padding: 5px;">
								Repetir Nueva Contraseña
							</div>

							<div class="col-md-7 col-sm-7 col-xs-7">
								<input id="clave_new2" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center; font-size: 14px;">
							</div>
						</div>
		      </div>

		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px;">Cerrar</button>
		        <button type="button" class="btn btn-primary" style="font-size: 14px;" onclick="f_UpdateContrasena();">Confirmar</button>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- Referenciando a JQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

		<!-- Referenciando auxiliares -->
		<?php include('global/auxiliares_js.php'); ?>

		<script type="text/javascript">
			function f_SetDimension(){
				if (screen.width < 500){
					$("._login").css('padding-top', '30%');
					$("._login").css('padding-left', '60px');
					$("._login").css('padding-right', '60px');
				}
			}

			$("#btn_LogIn").on( 'click', function() {
					f_LogIn();
			});

			function f_LogIn(){
        var user = f_CleanInjection($("#user").val());
        var password = f_CleanInjection($("#password").val());
        var id_sucursal = f_CleanInjection($("#id_sucursal").val());
        var cod_sucursal = f_CleanInjection($("#id_sucursal option:selected").data("id_sucursal"));
        var des_sucursal = f_CleanInjection($("#id_sucursal option:selected").text());

        // Valida ingreso de datos
          if (user == null){
            alert("Debe ingresar el Usuario.");

            return;
          }
          if (user.length == 0){
            alert("Debe ingresar el Usuario.");

            return;
          }

          if (password == null){
            alert("Debe ingresar la Clave.");

            return;
          }
          if (password.length == 0){
            alert("Debe ingresar la Clave.");

            return;
          }

          if (id_sucursal == null){
            alert("Debe seleccionar la Sucursal.");

            return;
          }
          if (id_sucursal.length == 0){
            alert("Debe seleccionar la Sucursal.");

            return;
          }

          $(".spinner-border").show();
          $("#btn_LogIn").hide();

          $.post( "apis/backend.php", { accion: "Log_In", user: user, password: password, id_sucursal, cod_sucursal, des_sucursal }, 
            function( data ) {
              if(data.estado == 1){
              	speak(data.sexo, "'" + data.nom_usuario + "'");

              	setTimeout('f_OpenInicio()', 2500);
              	// window.open('inicio.php', '_self');
              }
              else{
                alert("El Usuario y/o Clave ingresados no son correctos.\n\nPor favor, verificar.");

                $(".spinner-border").hide();
          			$("#btn_LogIn").show();
              }

            }, "json");
      }

      function f_OpenUpdateContrasena(){
      	// Validando datos
      		var _user = $("#user").val().trim();

      		if (_user.length == 0){
      			alert("Primero debe ingresar su nombre de usuario.");

      			return;
      		}
      		else{
      			// Verificando que sea un usuario válido
		          $.post( "apis/backend.php", { accion: "Validar_UsuarioExistente", usu_usuario: _user },
		            function( data ) {
		              if(data.estado == 1){
	                  if (data.existe == 0){
	                    alert("El usuario ingresado no es válido. Por favor, verificar.");

	                    return;
	                  }
	                  else{
	                    // Limpiando objetos
									    	$("#clave_old").val('');
												$("#clave_new").val('');
												$("#clave_new2").val('');

									    // Cargando pantalla
								        f_OpenModal('modal_changepassword');
	                  }
		              }
		          }, "json");
      		}
    	}

    	$("#modal_changepassword").on('shown.bs.modal', function(){
      	$("#clave_old").focus();
    	});

    	function f_UpdateContrasena(){
    		var _user = $("#user").val().trim();
        var clave_old = $("#clave_old").val();
        var clave_new1 = $("#clave_new").val();
        var clave_new2 = $("#clave_new2").val();

        // Validando datos
          if (clave_old == null){
            alert("Debe ingresar la clave actual.");

            return;
          }
          if (clave_old.length == 0){
            alert("Debe ingresar la clave actual.");

            return;
          }

          if (clave_new1 == null){
            alert("Debe ingresar la nueva clave.");

            return;
          }
          if (clave_new1.length == 0){
            alert("Debe ingresar la nueva clave.");

            return;
          }

          if (clave_new2 == null){
            alert("Debe reingresar la nueva clave.");

            return;
          }
          if (clave_new2.length == 0){
            alert("Debe reingresar la nueva clave.");

            return;
          }

        // Comprobando ingreso de claves
          if (clave_old == clave_new1){
            alert("La nueva clave debe ser diferente a la actual. Por favor, verificar.");

            return;
          }

          if (clave_new1 != clave_new2){
            alert("La clave reingresada no coincide con la nueva clave. Por favor, verificar.");

            return;
          }

          if (clave_new1.length < 4){
            alert("La nueva clave debe tener al menos 4 caracteres. Por favor, verificar.");

            return;
          }

        // Grabar información
          $.post( "apis/backend.php", { accion: "Validar_ClaveOld", usu_usuario: _user, clave_old: clave_old },
            function( data ) {
              if(data.estado == 1){
                if (data.is_ok == 0){
                  alert("La clave actual ingresada no es correcta. Por favor, asegúrese de ingresarla correctamente.");

                  return;
                }
                else{
                  $.post( "apis/backend.php", { accion: "grabar_CambioClave", usu_usuario: _user, clave_new: clave_new1 }, 
                      function( data2 ) {
                        if(data2.estado == 1){
                          alert("La clave fue cambiada satisfactoriamente");

                          f_cerrarModal('modal_changepassword');
                        }
                        else{
                          alert("Ocurrió un error al momento de grabar los datos.");
                        }
                    }, "json");
                }
              }
          }, "json");
    	}

      function f_OpenInicio(){
      	window.open('inicio.php', '_self');
      }

			function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);

        if (evt.keyCode == 13){
          f_LogIn();
        }
      }

      document.onkeypress = stopRKey;
		</script>
	</body>
</html>