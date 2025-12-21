<?php

if (!isset($_SESSION["nom_usuario"]) || !isset($_SESSION["des_sucursal"]) || !isset($_SESSION["modo_auditoria"])) {
	return;
}

// Navbar principal
$navbar_maintop = '<nav class="navbar" style="background-color: #ba9842; box-shadow: 0px 0px 10px #BDBFAE; padding: 2px;">
											  <div class="container-fluid" style="background-color: #25476a;">
											    <div class="col-md-2">
											    	<img src="' . $img_logo . '" style="width: 120px; padding: 5px;">
													<a role="button" data-bs-toggle="modal" data-bs-target="#menuModal"><i class="bi bi-list" style="color: white; font-size: 30px"></i></a>
											    </div>
											    
											    <div style="text-align: center; color: #ffffff; font-size: 14px; font-weight: bold;">
										    		' . $nom_app . ' <label id="nv_titulo" style="color: #FFDB17;"></label>
										    	</div>

											    <ul class="nav justify-content-end">
											    	<li class="nav-item dropdown">
										          <a class="nav-link dropdown-toggle"  role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #ffffff; font-size: 14px;">
										            ' . $_SESSION["des_sucursal"] . ' - ' . $_SESSION['nom_usuario'] . '
										          </a>
										          <ul class="dropdown-menu">';

if ($_SESSION["modo_auditoria"] == 1) {
	if ($_SESSION["modo_auditoria_ison"] == 0) {
		$navbar_maintop .= '<li><a class="dropdown-item" style="cursor: pointer; font-weight: bold; font-size: 14px;" onclick="f_ModoAuditoria(1);">
																					          		<div class="d-flex align-items-center">
																					          			<div class="p-2" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #D9D9D9;">
																					          				<img src="images/auditoria_on.png" style="width: 30px;">
																				          				</div>

																				          				<div class="p-2 grow">
																					          				<label>Modo Auditoría</label>
																				          				</div>
																				          			</div>
																					          	</a></li>

																					          	<li><hr class="dropdown-divider"></li>';
	} else {
		$navbar_maintop .= '<li><a class="dropdown-item" style="cursor: pointer; font-weight: bold; font-size: 14px;" onclick="f_ModoAuditoria(0);">
																					          		<div class="d-flex align-items-center">
																					          			<div class="p-2" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #D9D9D9;">
																					          				<img src="images/auditoria_off.png" style="width: 30px;">
																				          				</div>

																				          				<div class="p-2 grow">
																					          				<label>Desactivar Modo Auditoría</label>
																				          				</div>
																				          			</div>
																					          	</a></li>

																					          	<li><hr class="dropdown-divider"></li>';
	}
}

$navbar_maintop .= '				<li><a class="dropdown-item" style="cursor: pointer; font-weight: bold; font-size: 14px;" onclick="f_CerrarSesion();">
										          		<div class="d-flex align-items-center">
											            	<div class="p-2" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #D9D9D9;">
										          				<img src="images/exit.png" style="width: 30px; padding: 2px;">
									          				</div>

									          				<div class="p-2 grow">
										          				<label>Cerrar Sesión</label>
									          				</div>
								          				</div>
							          				</a></li>
										          </ul>
										        </li>
											    </ul>
											  </div>
											</nav>

											<div id="tst_container" class="toast-container position-fixed top-0 end-0 p-3">
												
											</div>

											<div id="tst_visitas" class="toast-container position-fixed top-0 end-0 p-3">
												
											</div>';

$modal_clientescredito = '<div class="modal fade" id="modal_clientescredito_sendemail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_clientescredito_sendemailLabel" aria-hidden="true" style="display: none;">
															  <div class="modal-dialog">
															    <div class="modal-content">
															      <div class="modal-header">
															        <h1 class="modal-title fs-5" id="modal_clientescredito_sendemailLabel">Enviar Recordatorio</h1>
															        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
															      </div>
															      <div class="modal-body">
															        <div class="row" style="padding: 5px;">
																				<div class="col-md-2 col-sm-2 col-xs-2" style="padding: 5px;">
																					Cliente:
																				</div>

																				<div class="col-md-10 col-sm-10 col-xs-10">
																					<textarea id="clientescredito_cliente" type="text" class="form-control col-md-12 col-xs-12" rows="2" disabled></textarea>
																				</div>
																			</div>

																			<div class="row" style="padding: 5px;">
																				<div class="col-md-2 col-sm-2 col-xs-2" style="padding: 5px;">
																					Correo:
																				</div>

																				<div class="col-md-10 col-sm-10 col-xs-10">
																					<input id="clientescredito_correo" type="email" class="form-control col-md-12 col-xs-12">
																				</div>
																			</div>

																			<div class="row" style="padding: 5px;">
																				<div class="col-md-2 col-sm-2 col-xs-2" style="padding: 5px;">
																					Texto:
																				</div>

																				<div class="col-md-10 col-sm-10 col-xs-10">
																					<textarea id="clientescredito_texto" type="text" class="form-control col-md-12 col-xs-12" rows="10"></textarea>
																				</div>
																			</div>
															      </div>

															      <input id="hd_idcliente" type="hidden">
															      <input id="hd_modograbar" type="hidden">

															      <div class="modal-footer">
															        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
															        <button type="button" class="btn btn-primary" onclick="f_GrabarCliente();">Enviar correo</button>
															      </div>
															    </div>
															  </div>
															</div>';

echo $modal_clientescredito;
