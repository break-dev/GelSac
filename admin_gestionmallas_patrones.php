
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
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo $favicon; ?>" type="image/png"/>
  <link href="libs/bootstrap-5.2.3/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link href="libs/select2/dist/css/select2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="global/estilos.css">
  <script type="text/javascript">
    let idpatron_Selected = 0;
    let codpatron_Selected = '';
  </script>
  <title>The Nebula Lims | LQ - Gestión de Mallas Patrón</title>
</head>

<body class="bg-light" onload="f_Init();" style="zoom: 80%;">
  <div class="container-fluid">
    <div class="row">
      <?php echo $navbar_maintop; ?>
      <div id="div_menu1" class="col-md-1 col-sm-1 col-xs-1" style="border: solid 1px #E6E9ED; border-radius: 7px; text-align: center; height: 114vh; background-color: #DEDEDE;"></div>
      <div class="col-md-11 col-sm-11 col-xs-11" style="border: solid 1px #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
        <div class="d-flex row">
          <div class="row" style="border: solid 1px #E6E9ED; border-radius: 7px; background-color: #ffffff;">
            <div class="d-flex" style="padding: 20px;">
              <div class="">
                <h5>Gestión de Mallas Patrón</h5>
              </div>
            </div>
            <div style="padding-left: 20px; padding-right: 20px; margin-top: -30px;">
              <hr style="border-color: #D9D9D9;"/>
            </div>
            <div class="d-flex" style="padding: 20px; margin-top: -25px; overflow-x: scroll; width: 100%;">
              <div id="div_patrones" class="col-md-6 col-sm-6 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
                <div style="border: solid 1px #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="d-flex">
                          <h6>Lista de Patrones</h6>
                          <div id="wt_patrones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando...</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 text-end">
                        <button class="btn btn-info btn-sm" style="color: white; width: 100%; margin-top: -7px;" onclick="f_AdminPatron('x');">
                          <i class="bi bi-plus-circle"></i> Nuevo Patrón
                        </button>
                      </div>
                    </div>
                  </div>
                  <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                    <hr style="border-color: #D9D9D9;"/>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -20px; overflow-x: scroll; width: 100%;">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr style="font-size: 12px;">
                          <th class="th-lims th-top-left">#</th>
                          <th class="th-lims">Cód. Malla</th>
                          <th class="th-lims">Estándar</th>
                          <th class="th-lims">Núm. Malla</th>
                          <th class="th-lims">Lote</th>
                          <th class="th-lims">Estado</th>
                          <th class="th-lims th-top-right">Acción</th>
                        </tr>
                      </thead>
                      <tbody id="tb_lista_patrones"></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div id="div_calibraciones" class="col-md-6 col-sm-6 col-xs-12" style="padding: 5px; padding-bottom: 5px;">
                <div style="border: solid 1px #E6E9ED; border-radius: 7px; background-color: #ffffff; padding: 0px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
                    <div class="row" style="padding-top: 10px; padding-left : 20px; padding-right: 20px;">
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="d-flex">
                          <h6>Calibraciones registradas para:</h6>
                          <h6 id="lbl_titulocalibraciones" style="margin-left: 5px; color: #337ab7; font-weight: bold;"></h6>
                          <div id="wt_calibraciones" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                            <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                            <label style="font-style: italic;"> Cargando...</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-4 col-xs-12 text-end">
                        <button class="btn btn-info btn-sm" style="color: white; width: 100%; margin-top: -7px;" onclick="f_AdminCalibracion('x');">
                          <i class="bi bi-plus-circle"></i> Nueva Calibración
                        </button>
                      </div>
                    </div>
                  </div>
                  <div style="padding-left: 20px; padding-right: 20px; margin-top: -15px;">
                    <hr style="border-color: #D9D9D9;"/>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -20px; overflow-x: scroll; width: 100%;">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr style="font-size: 12px;">
                          <th class="th-lims th-top-left">#</th>
                          <th class="th-lims">Certificado</th>
                          <th class="th-lims">Desde</th>
                          <th class="th-lims">Hasta</th>
                          <th class="th-lims">Resultado %</th>
                          <th class="th-lims">Estado</th>
                          <th class="th-lims th-top-right">Acción</th>
                        </tr>
                      </thead>
                      <tbody id="tb_lista_calibraciones"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #DEDEDE; width: 20%;">
        <div class="offcanvas-header" style="background-color: #ffffff;">
          <h5 id="sb1_titulo" class="offcanvas-title" id="offcanvasExampleLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div id="div_submenu1" class="offcanvas-body" style="color: #212529;"></div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="libs/bootstrap-5.2.3/js/bootstrap.bundle.min.js"></script>
  <script src="libs/select2/dist/js/select2.full.min.js"></script>
  <?php include('global/auxiliares_js.php'); ?>
  <!-- Resto de funciones en archivos JS específicos -->
</body>
</html>
