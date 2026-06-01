<?php

if (!isset($_SESSION["nom_usuario"]) || !isset($_SESSION["des_sucursal"]) || !isset($_SESSION["modo_auditoria"])) {
  return;
}

// Navbar principal — diseño premium (versión más grande)
$navbar_maintop = '
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  .gelsac-navbar {
      background: linear-gradient(90deg, #1a3050 0%, #25476a 60%, #2d5580 100%);
      box-shadow: 0 2px 20px rgba(26,48,80,.4);
      height: 68px;
      display: flex;
      align-items: center;
      justify-content: space-between; /* Añade esta línea */
      padding: 0 24px;
      gap: 0;
      font-family: "Inter", sans-serif;
      position: relative; /* Importante para el posicionamiento absoluto del título */
      z-index: 1030;
  }
  .gelsac-navbar::after {
    content: "";
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;  /* Aumentado de 2px a 3px */
    background: linear-gradient(90deg, #ba9842, #FFDB17, #ba9842);
  }
.gelsac-nav-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
    position: relative;
    z-index: 2; /* Para que quede por encima */
    background: linear-gradient(90deg, #1a3050 0%, #25476a 60%, #2d5580 100%); /* Opcional: para tapar el texto detrás */
}
  .gelsac-nav-brand img { height: 42px; }  /* Aumentado de 36px a 42px */
  .gelsac-menu-btn {
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 10px;  /* Aumentado de 8px a 10px */
    width: 42px; height: 42px;  /* Aumentado de 36px a 42px */
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: 22px;  /* Aumentado de 20px a 22px */
    cursor: pointer;
    transition: background .2s;
    margin-left: 10px;  /* Aumentado de 8px a 10px */
    text-decoration: none;
  }
  .gelsac-menu-btn:hover { background: rgba(255,255,255,.18); color: #FFDB17; }
  .gelsac-nav-center {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      text-align: center;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      letter-spacing: .4px;
      pointer-events: none;
      white-space: nowrap;
  }
  .gelsac-nav-center span { color: #FFDB17; font-weight: 700; }
.gelsac-nav-right {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-shrink: 0;
    position: relative;
    z-index: 2; /* Para que quede por encima */
    
}
  /* Botón Centro de Ayuda */
  .gelsac-help-btn {
    display: flex;
    align-items: center;
    gap: 8px;  /* Aumentado de 7px a 8px */
    padding: 8px 20px;  /* Aumentado de 7px 16px a 8px 20px */
    background: linear-gradient(135deg, #ba9842, #d4b05a);
    border: none;
    border-radius: 24px;  /* Aumentado de 20px a 24px */
    color: #fff;
    font-size: 13px;  /* Aumentado de 12px a 13px */
    font-weight: 600;
    font-family: "Inter", sans-serif;
    text-decoration: none;
    cursor: pointer;
    transition: all .22s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 2px 8px rgba(186,152,66,.4);
    white-space: nowrap;
    letter-spacing: .3px;  /* Aumentado de .2px a .3px */
  }
  .gelsac-help-btn:hover {
    background: linear-gradient(135deg, #d4b05a, #FFDB17);
    color: #1a3050;
    box-shadow: 0 4px 14px rgba(186,152,66,.55);
    transform: translateY(-1px);
  }
  .gelsac-help-btn i { font-size: 15px; }  /* Aumentado de 14px a 15px */
  /* Dropdown usuario */
  .gelsac-user-toggle {
    display: flex;
    align-items: center;
    gap: 10px;  /* Aumentado de 8px a 10px */
    padding: 6px 16px 6px 6px;  /* Aumentado de 5px 14px 5px 5px a 6px 16px 6px 6px */
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 28px;  /* Aumentado de 20px a 28px */
    cursor: pointer;
    transition: background .2s;
    color: #fff;
    font-size: 14px;  /* Aumentado de 13px a 14px */
    font-family: "Inter", sans-serif;
    font-weight: 500;
    white-space: nowrap;
  }
  .gelsac-user-toggle:hover { background: rgba(255,255,255,.18); }
  .gelsac-user-avatar {
    width: 34px; height: 34px;  /* Aumentado de 28px a 34px */
    background: linear-gradient(135deg, #ba9842, #FFDB17);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;  /* Aumentado de 13px a 15px */
    font-weight: 700;
    color: #1a3050;
    flex-shrink: 0;
  }
  .gelsac-user-info { display: flex; flex-direction: column; line-height: 1.3; }  /* Aumentado de 1.2 a 1.3 */
  .gelsac-user-name { font-size: 13px; font-weight: 600; color: #fff; }  /* Aumentado de 12px a 13px */
  .gelsac-user-branch { font-size: 11px; color: rgba(255,255,255,.65); }  /* Aumentado de 10px a 11px */
  /* Dropdown menu */
  .gelsac-dropdown {
    position: relative;
  }
  .gelsac-dropdown-menu {
    position: absolute;
    top: calc(100% + 12px);  /* Aumentado de 10px a 12px */
    right: 0;
    background: #fff;
    border-radius: 14px;  /* Aumentado de 12px a 14px */
    box-shadow: 0 10px 35px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.1);
    border: 1px solid #e2e8f0;
    min-width: 240px;  /* Aumentado de 220px a 240px */
    padding: 10px;  /* Aumentado de 8px a 10px */
    display: none;
    z-index: 9999;
    animation: ddFadeIn .2s ease;
    font-family: "Inter", sans-serif;
  }
  @keyframes ddFadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .gelsac-dropdown.open .gelsac-dropdown-menu { display: block; }
  .gelsac-dd-item {
    display: flex;
    align-items: center;
    gap: 12px;  /* Aumentado de 10px a 12px */
    padding: 12px 14px;  /* Aumentado de 10px 12px a 12px 14px */
    border-radius: 10px;  /* Aumentado de 8px a 10px */
    cursor: pointer;
    font-size: 14px;  /* Aumentado de 13px a 14px */
    font-weight: 500;
    color: #334155;
    transition: background .18s;
    text-decoration: none;
    border: none;
    width: 100%;
    background: none;
    font-family: "Inter", sans-serif;
  }
  .gelsac-dd-item:hover { background: #f1f5f9; color: #25476a; }
  .gelsac-dd-item i {
    width: 36px; height: 36px;  /* Aumentado de 32px a 36px */
    background: #f1f5f9;
    border-radius: 10px;  /* Aumentado de 8px a 10px */
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;  /* Aumentado de 15px a 17px */
    color: #64748b;
    flex-shrink: 0;
  }
  .gelsac-dd-item:hover i { background: rgba(37,71,106,.1); color: #25476a; }
  .gelsac-dd-divider { border: none; border-top: 1px solid #e2e8f0; margin: 8px 0; }  /* Aumentado de 6px a 8px */
  .gelsac-dd-logout i { color: #dc2626 !important; }
  .gelsac-dd-logout:hover { background: #fef2f2 !important; color: #dc2626 !important; }
  .gelsac-dd-audit i { color: #7c3aed !important; }
  .gelsac-dd-audit:hover { background: #f5f3ff !important; color: #7c3aed !important; }
</style>

<nav class="gelsac-navbar">
  <!-- Brand + menu -->
  <div class="gelsac-nav-brand">
    <img src="' . $img_logo . '" alt="Logo">
    <a class="gelsac-menu-btn" role="button" data-bs-toggle="modal" data-bs-target="#menuModal" title="Menú principal">
      <i class="bi bi-list"></i>
    </a>
  </div>

  <!-- Título centrado -->
  <div class="gelsac-nav-center">
    ' . $nom_app . ' &nbsp;<span id="nv_titulo"></span>
  </div>

  <!-- Acciones derecha -->
  <div class="gelsac-nav-right">

    <!-- Botón Centro de Ayuda -->
    <a href="centro-ayuda.html" target="_blank" class="gelsac-help-btn" title="Abrir Centro de Ayuda">
      <i class="bi bi-headset"></i>
      <span>Centro de Ayuda</span>
    </a>

    <!-- Dropdown usuario -->
    <div class="gelsac-dropdown" id="gelsac-user-dropdown">
      <div class="gelsac-user-toggle" onclick="toggleNavDropdown()">
        <div class="gelsac-user-avatar">' . strtoupper(substr($_SESSION['nom_usuario'], 0, 1)) . '</div>
        <div class="gelsac-user-info">
          <span class="gelsac-user-name">' . $_SESSION['nom_usuario'] . '</span>
          <span class="gelsac-user-branch">' . $_SESSION['des_sucursal'] . '</span>
        </div>
        <i class="bi bi-chevron-down" style="font-size:12px; color:rgba(255,255,255,.6); margin-left:3px;"></i>
      </div>

      <div class="gelsac-dropdown-menu">';

if ($_SESSION["modo_auditoria"] == 1) {
  if ($_SESSION["modo_auditoria_ison"] == 0) {
    $navbar_maintop .= '
			<button class="gelsac-dd-item gelsac-dd-audit" onclick="f_ModoAuditoria(1);">
				<i class="bi bi-shield-lock-fill"></i> Activar Modo Auditoría
			</button>
			<hr class="gelsac-dd-divider">';
  } else {
    $navbar_maintop .= '
			<button class="gelsac-dd-item gelsac-dd-audit" onclick="f_ModoAuditoria(0);">
				<i class="bi bi-shield-slash-fill"></i> Desactivar Auditoría
			</button>
			<hr class="gelsac-dd-divider">';
  }
}

$navbar_maintop .= '
			<button class="gelsac-dd-item gelsac-dd-logout" onclick="f_CerrarSesion();">
				<i class="bi bi-box-arrow-right"></i> Cerrar Sesión
			</button>
		</div>
	  </div>
	</div>
</nav>

<script>
function toggleNavDropdown() {
	var dd = document.getElementById("gelsac-user-dropdown");
	dd.classList.toggle("open");
}
document.addEventListener("click", function(e) {
	var dd = document.getElementById("gelsac-user-dropdown");
	if (dd && !dd.contains(e.target)) dd.classList.remove("open");
});
</script>

<div id="tst_container" class="toast-container position-fixed top-0 end-0 p-3"></div>
<div id="tst_visitas" class="toast-container position-fixed top-0 end-0 p-3"></div>';

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