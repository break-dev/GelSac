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

    <title><?php echo $nom_app; ?> | Administración de Proveedores Mineros</title>

    <script type="text/javascript">

    </script>
  </head>

  <body class="bg-light" onload="f_Init();" style="zoom: 80%;">
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

        <!-- Modal (Filtros Lateral) -->
        <div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-right" style="margin-top: 0px !important; margin-left: 0px !important;">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">Filtro de Opciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div  class="modal-body" style=" padding: 0px !important;">
               
                <div class="row" style="padding-left: 20px;margin-top: 10px;margin-bottom: 10px;font-size: 13px;padding-right: 20px;">
                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                    <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                      <div class="row" style="padding-left: 10px; padding-right: 10px;">
                        <h6 style="font-size: 14px;">Por Tipo Cliente</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                        <select id="filtro_tipocliente" class="form-select" style="text-align: left; font-size: 14px;" onchange="f_LoadResultados();">
                          <option selected value="">Elija una opción...</option>

                          <?php

                          $q_tipocliente = "SELECT Id,
                          descripcion
                          FROM tbconfig_tipocliente
                          WHERE estado = 'A'";

                          if ($res_tipocliente = mysqli_query($enlace, $q_tipocliente)){
                            if (mysqli_num_rows($res_tipocliente) > 0) {
                              while($row_tipocliente = mysqli_fetch_array($res_tipocliente)){
                                ?>

                                <option value="<?php echo $row_tipocliente["Id"]; ?>"><?php echo $row_tipocliente["descripcion"]; ?></option>

                                <?php
                              }
                            }
                          }

                          ?>

                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                    <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                      <div class="row" style="padding-left: 10px; padding-right: 10px;">
                        <h6 style="font-size: 14px;">Por Documento / Razón Social</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                        <select id="filtro_listatipo" class="form-select" style="text-align: left; font-size: 14px; width: 50%; margin-right: 5px;" onchange="f_CleanTxtTipo();">
                          <option selected value="">Elija una opción...</option>
                          <option value="1">Documento</option>
                          <option value="2">Razón Social</option>
                        </select>

                        <input id="filtro_tipo" type="text" class="form-control" style="font-size: 14px;" onblur="f_LoadResultados();">
                      </div>
                    </div>
                  </div>

                  <!-- <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 2px;">
                    <div style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding: 10px;">
                      <div class="row" style="padding-left: 10px; padding-right: 10px;">
                        <h6 style="font-size: 14px;">Por Cód. Cliente</h6>
                      </div>

                      <div class="row" style="margin-top: 1px; padding-left: 20px; padding-right: 20px;">
                        <hr style="border-color: #D9D9D9;"/>
                      </div>

                      <div class="d-flex" style="margin-top: -5px; padding-left: 10px; padding-right: 10px;">
                        <input id="filtro_codcliente" type="text" class="form-control" style="font-size: 14px; text-transform: uppercase;" onblur="f_LoadResultados();">
                      </div>
                    </div>
                  </div> -->
                </div>


              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px; padding-left: 35px;">
          <div class="d-flex row">
            <div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff; margin-bottom: 5px;">
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

            <div class="row" style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; background-color: #ffffff;">
              <div class="row" style="padding: 20px;">
                <div class="col-md-10 col-sm-10 col-xs-10">
                  <div class="d-flex">
                    <h5>Resumen de Proveedores Mineros</h5>

                    <div id="wt_resumen" class="" style="font-size: 12px; text-align: center; display: none; padding-top: 5px;">
                      <img src="<?php echo $img_waiting ?>" style="width: 20px;">
                      <label style="font-style: italic;"> Cargando datos...</label>
                    </div>
                  </div>
                </div>

                <div class="col-md-2 col-sm-2 col-xs-2" style="margin-top: -5px;">
                  <button class="btn btn-primary" type="button" onclick="f_AdminClientes('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
                    <b> + Nuevo Proveedor Minero</b>
                  </button>
                </div>

                
              </div>

              <div style="padding-left: 20px; padding-right: 20px; margin-top: -20px;">
                <hr style="border-color: #D9D9D9;"/>
              </div>

              <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
                <table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr style="font-size: 14px;">
                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                        N°
                      </th>

                      <!-- <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                        Cód. Cliente
                      </th> -->

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Tipo Cliente
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Tipo Documento
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Documento
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
                        Razón Social
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Teléfonos
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Correo
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 250px;">
                        Dirección
                      </th>

                      <th colspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Representante Legal
                      </th>

                      <th colspan="5" rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Información Bancaria
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                        Estado
                      </th>

                      <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 100px; border-top-right-radius: 15px;">
                        Acción
                      </th>
                    </tr>

                    <tr style="font-size: 14px;">
                      <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                        DNI
                      </th>

                      <th style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 300px;">
                        Nombres
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
    <div class="modal fade" id="modal_addcliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addclienteLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-6" id="modal_addclienteLabel">Nuevo Cliente</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Tipo Cliente:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cliente_tipocliente" class="form-select" style="text-align: left;" onchange="f_GetListaTipoDocumento(0)">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_tipocliente = "SELECT Id,
                  descripcion
                  FROM tbconfig_tipocliente
                  WHERE estado = 'A'";

                  if ($res_tipocliente = mysqli_query($enlace, $q_tipocliente)){
                    if (mysqli_num_rows($res_tipocliente) > 0) {
                      while($row_tipocliente = mysqli_fetch_array($res_tipocliente)){
                        ?>

                        <option value="<?php echo $row_tipocliente["Id"]; ?>"><?php echo $row_tipocliente["descripcion"]; ?></option>

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
                Tipo Documento:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cliente_tipodocumento" class="form-select" style="text-align: left;">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_tipodocumento = "SELECT Id,
                  descripcion
                  FROM tbconfig_tipodocumento
                  WHERE estado = 'A'";

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
                Documento:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_documento" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente();">
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

            <!-- <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Cód. Cliente:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_codcliente" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;">
              </div>
            </div> -->

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

            <div class="row" style="padding: 5px;">
              <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #816951; color: #ffffff;">
                Representante Legal
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                DNI:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_representantedni" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" onkeyup="f_GetInfoCliente(1)">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Nombres: <img id="wt_representantelegal" src="<?php echo $img_waiting ?>" style="width: 35px; display: none;">
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_representantenombres" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;">
              </div>
            </div>

            
            <div class="row" style="padding: 5px;">
              <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px; border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; text-align: center; background-color: #816951; color: #ffffff;">
                Información Bancaria
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Banco:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cliente_bancaria_id_banco" class="form-select" style="text-align: left;">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_banco = "SELECT Id,
                                        descripcion
                                        FROM tb_bancos
                                        WHERE estado = 'A'";

                  if ($res_banco = mysqli_query($enlace, $q_banco)){
                    if (mysqli_num_rows($res_banco) > 0) {
                      while($row_banco = mysqli_fetch_array($res_banco)){
                        ?>

                        <option value="<?php echo $row_banco["Id"]; ?>"><?php echo $row_banco["descripcion"]; ?></option>

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
                Número de cuenta:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_bancaria_num_cuenta" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" >
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                CCI:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_bancaria_cci" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" >
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Medio de pago:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cliente_bancaria_id_mediopago" class="form-select" style="text-align: left;">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_medio = "SELECT Id,
                                        descripcion
                                        FROM tbconfig_mediospago
                                        WHERE estado = 'A'";

                  if ($res_medio = mysqli_query($enlace, $q_medio)){
                    if (mysqli_num_rows($res_medio) > 0) {
                      while($row_medio = mysqli_fetch_array($res_medio)){
                        ?>

                        <option value="<?php echo $row_medio["Id"]; ?>"><?php echo $row_medio["descripcion"]; ?></option>

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
                Moneda:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cliente_bancaria_id_moneda" class="form-select" style="text-align: left;">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_moneda = "SELECT Id,
                                        descripcion
                                        FROM tbconfig_monedas
                                        WHERE estado = 'A'";

                  if ($res_moneda = mysqli_query($enlace, $q_moneda)){
                    if (mysqli_num_rows($res_moneda) > 0) {
                      while($row_moneda = mysqli_fetch_array($res_moneda)){
                        ?>

                        <option value="<?php echo $row_moneda["Id"]; ?>"><?php echo $row_moneda["descripcion"]; ?></option>

                        <?php
                      }
                    }
                  }

                  ?>

                </select>
              </div>
            </div>

          </div>

          <input id="hd_idcliente" type="hidden">
          <input id="hd_modograbar" type="hidden">

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="f_GrabarCliente();">Grabar</button>
          </div>
        </div>
      </div>
    </div>

    <!--Ventana Modal cliente concesión-->
    <div class="modal fade" id="modal_addclienteconcesion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addclienteconcesionLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-6" id="modal_addclienteconcesionLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; width: 100%;">
              <button class="btn btn-primary" type="button" onclick="f_AdminConcesion('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
                <b> + Nueva Concesión</b>
              </button>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="font-size: 12px;">
                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                      N°
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                      Concesión
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Cod. Único
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Procedencia
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  border-top-right-radius: 15px;">
                      Accion
                    </th>
                  </tr>
                </thead>

                <tbody id="tbl_detalle_cliente_concesion">

                </tbody>
              </table>
            </div>
          </div>
          <input id="hd_clienteconcesion_documento" type="hidden">

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!--Ventana Modal cliente banco-->
    <div class="modal fade" id="modal_addclientebanco" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addclientebancoLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-6" id="modal_addclientebancoLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; width: 100%;">
              <button class="btn btn-primary" type="button" onclick="f_AdminBanco('x');" style="color: #ffffff; width: 100%; font-size: 14px;">
                <b> + Nueva Cuenta Bancaria</b>
              </button>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 20px; margin-top: -15px; overflow-x: scroll; width: 100%;">
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr style="font-size: 12px;">
                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; border-top-left-radius: 15px;">
                      N°
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle; min-width: 80px;">
                      Banco
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Cuenta
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      CCI
                    </th>
                    
                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      Moneda
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;">
                      ¿Es Detracción?
                    </th>

                    <th rowspan="2" style="text-align: center; border: solid; border-width: 1px; background-color: #816951; border-color: #ffffff; color: #ffffff; vertical-align: middle;  border-top-right-radius: 15px;">
                      Accion
                    </th>
                  </tr>
                </thead>

                <tbody id="tbl_detalle_cliente_banco">

                </tbody>
              </table>
            </div>
          </div>
          <input id="hd_clientebanco_documento" type="hidden">
          <input id="hd_clientebanco_id_cliente" type="hidden">

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!--Ventana Modal Concesión-->
    <div class="modal fade" id="modal_addconcesion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addconcesionLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-6" id="modal_addconcesionLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Concesión:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="concesion_descripcion" type="email" class="form-control col-md-12 col-xs-12">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Cod. Único:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="concesion_codigo_unico" type="email" class="form-control col-md-12 col-xs-12">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Departamento:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="concesion_procedencia_departamento" class="form-select" style="text-align: left;" onchange="f_LoadListaProvincia()">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_departamento = "SELECT Id,
                  codigo,
                  descripcion
                  FROM tbconfig_ubigeodepartamentos
                  WHERE estado = 'A'";

                  if ($res_departamento = mysqli_query($enlace, $q_departamento)){
                    if (mysqli_num_rows($res_departamento) > 0) {
                      while($row_departamento = mysqli_fetch_array($res_departamento)){
                        ?>

                        <option value="<?php echo $row_departamento["codigo"]; ?>"><?php echo $row_departamento["descripcion"]; ?></option>

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
                Provincia:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="concesion_procedencia_provincia" class="form-select" style="text-align: left;"  onchange="f_LoadListaDistrito()">
                </select>
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Distrito:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="concesion_procedencia_distrito" class="form-select" style="text-align: left;" >
                </select>
              </div>
            </div>
            
          </div>

          <input id="hd_idclienteconcesion" type="hidden">
          <input id="hd_modograbarconcesion" type="hidden">

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="f_GrabarConcesion();">Grabar</button>
          </div>
        </div>
      </div>
    </div>

      <!--Ventana Modal Cuentas Bancarias-->
    <div class="modal fade" id="modal_addbanco" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addbancoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-6" id="modal_addbancoLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Banco:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cliente_banco_id_banco" class="form-select" style="text-align: left;">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_banco = "SELECT Id,
                                        descripcion
                                        FROM tb_bancos
                                        WHERE estado = 'A'";

                  if ($res_banco = mysqli_query($enlace, $q_banco)){
                    if (mysqli_num_rows($res_banco) > 0) {
                      while($row_banco = mysqli_fetch_array($res_banco)){
                        ?>

                        <option value="<?php echo $row_banco["Id"]; ?>"><?php echo $row_banco["descripcion"]; ?></option>

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
                Número de cuenta:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_banco_num_cuenta" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" >
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                CCI:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="cliente_banco_cci" type="text" class="form-control col-md-12 col-xs-12" style="text-align: center;" >
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Moneda:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <select id="cliente_banco_id_moneda" class="form-select" style="text-align: left;">
                  <option selected value="">Elija una opción...</option>

                  <?php

                  $q_moneda = "SELECT Id,
                                        descripcion
                                        FROM tbconfig_monedas
                                        WHERE estado = 'A'";

                  if ($res_moneda = mysqli_query($enlace, $q_moneda)){
                    if (mysqli_num_rows($res_moneda) > 0) {
                      while($row_moneda = mysqli_fetch_array($res_moneda)){
                        ?>

                        <option value="<?php echo $row_moneda["Id"]; ?>"><?php echo $row_moneda["descripcion"]; ?></option>

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
                Cuenta Detracción:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8" style="padding-top: 6px;">
                <input type="checkbox" id="cliente_banco_is_detraccion">
              </div>
            </div>

          
          </div>

          <input id="hd_idclientebanco" type="hidden">
          <input id="hd_modograbarbanco" type="hidden">

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="f_GrabarBanco();">Grabar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_addcredito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_addcreditoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modal_addcreditoLabel">Nuevo Cliente</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Vigencia:
              </div>

              <div class="col-md-4 col-sm-4 col-xs-4">
                <input id="cred_fechainicio" type="date" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; width: 95%; font-size: 14px;" value="<?php echo $g_date; ?>">
              </div>

              <div class="col-md-4 col-sm-4 col-xs-4">
                <input id="cred_fechafin" type="date" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; width: 95%; font-size: 14px;" value="<?php echo $g_date; ?>">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Observación:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <textarea id="cred_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
              </div>
            </div>
          </div>

          <input id="hd_idclientecredito" type="hidden">
          <input id="hd_idcredito" type="hidden">
          <input id="hd_modograbarcredito" type="hidden">

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="f_GrabarCredito();">Grabar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_adddescuento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal_adddescuentoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modal_adddescuentoLabel">Nuevo Cliente</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Vigencia:
              </div>

              <div class="col-md-4 col-sm-4 col-xs-4">
                <input id="dscto_fechainicio" type="date" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; width: 95%; font-size: 14px;" value="<?php echo $g_date; ?>">
              </div>

              <div class="col-md-4 col-sm-4 col-xs-4">
                <input id="dscto_fechafin" type="date" class="form-control obj_cab col-md-12 col-xs-12" style="text-align: center; width: 95%; font-size: 14px;" value="<?php echo $g_date; ?>">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Descuento(%):
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <input id="dscto_porc" type="number" class="form-control col-md-12 col-xs-12" style="text-align: center;">
              </div>
            </div>

            <div class="row" style="padding: 5px;">
              <div class="col-md-4 col-sm-4 col-xs-4" style="padding: 5px;">
                Observación:
              </div>

              <div class="col-md-8 col-sm-8 col-xs-8">
                <textarea id="dscto_observacion" type="text" class="form-control col-md-12 col-xs-12" rows="2" style="text-transform: uppercase;"></textarea>
              </div>
            </div>
          </div>

          <input id="hd_idclientedescuento" type="hidden">
          <input id="hd_iddescuento" type="hidden">
          <input id="hd_modograbardescuento" type="hidden">

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="f_GrabarDescuento();">Grabar</button>
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
        $("#nv_titulo").html('| Administración de Proveedores Mineros');

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
        var cod_tipocliente = $("#filtro_tipocliente").val();
        var cod_tipo = $("#filtro_listatipo").val();
        var txt_tipo = $("#filtro_tipo").val().trim();
        // var cod_cliente = f_CleanInjection($("#filtro_codcliente").val().trim());

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

        f_LoadingResumen(1);

        $.post( "apis/backend.php", { accion: "get_listaclientes", cod_condicion: 1, cod_tipocliente: cod_tipocliente, cod_tipo: cod_tipo, txt_tipo: txt_tipo, cod_cliente: '' }, 
          function( data ) {
            if(data.estado == 1){
              $.each( data.res, function( key, val ) {
                _html += '<tr style="cursor: pointer; font-size: 14px;">';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
                _html += '  </td>';

                // _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                // _html += '      ' + val.cod_cliente;
                // _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.TIPO_CLIENTE;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.TIPO_DOCUMENTO;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
                _html += '      ' + val.documento;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.razon_social;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.TELEFONOS;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.correo;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.direccion;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.representantelegal_dni;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.representantelegal_nombres;
                _html += '  </td>';
                
                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.banco_nombre;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.num_cuenta;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.cci;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.mediopago_nombre;
                _html += '  </td>';

                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle;">';
                _html += '      ' + val.moneda_nombre;
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
                _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left; min-width: 120px;">';

                _html += '      <a class="success" href="javascript: f_AdminClientes(' + d + ', ' + val.Id + ', ' + val.cod_tipocliente + ', ' + val.cod_tipodocumento + ", '" + val.documento + "', '" + val.razon_social + "', '" + val.telefono1 + "', '" + val.telefono2 + "', '" + val.correo + "', '" + val.direccion + "', '" + val.cod_cliente + "', '"+ val.representantelegal_dni + "', '" + val.representantelegal_nombres+ "', " + val.id_banco  + ", '" + val.num_cuenta+ "', '" + val.cci + "', " + val.id_mediopago + ", " + val.id_moneda + ')"><i class="bi bi-pencil-square"></i>';
                _html += '          <font style="color: #337ab7;"> Editar</font>';
                _html += '      </a>';

                _html += '<br>';

                _html += '      <a class="success" href="javascript: f_CambiarEstado(' + "'" + href_estado.substring(0, 1) + "', " + val.Id + ')"><i class="' + href_icon + '"></i>';
                _html += '          <font style="color: ' + href_color + ';"> ' + href_estado + '</font>';
                _html += '      </a>';

                _html += '<br>';

                _html += '      <a class="success" href="javascript: f_EliminarCliente(' + val.Id + ')"><i class="bi bi-file-x"></i>';
                _html += '          <font style="color: #F20505;"> Eliminar</font>';
                _html += '      </a>';

                _html += '<br>';
                _html += '<br>';

                _html += '      <a class="success" href="javascript: f_AdminClientesConcesion(' + d + ', ' + val.Id + ", '" + val.documento + "', '" + val.razon_social + "'" + ')"><i class="bi bi-plus-circle"></i>';
                _html += '          <font style="color: green;"> Concesión</font>';
                _html += '      </a>';
                _html += '<br>';
                _html += '      <a class="success" href="javascript: f_AdminClientesBanco(' + d + ', ' + val.Id + ", '" + val.documento + "', '" + val.razon_social + "'" + ')"><i class="bi bi-plus-circle"></i>';
                _html += '          <font">Ctas. Banco</font>';
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

  f_LoadingResumen(0);

  }, "json");
  };


  function f_LoadClienteConcesionResultados(_documento_cliente){
    var _html = '';
    var d = 1;

    $("#tbl_detalle_cliente_concesion").html('');

    $.post( "apis/backend.php", { accion: "get_listaclientesconcesion", documento_cliente: _documento_cliente }, 
      function( data ) {
        if(data.estado == 1){
          $.each( data.res, function( key, val ) {
            _html += '<tr style="cursor: pointer; font-size: 14px;">';


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
            

            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right">' + d;
            _html += '  </td>';

            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
            _html += '      ' + val.descripcion;
            _html += '  </td>';

            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
            _html += '      ' + val.codigo_unico;
            _html += '  </td>';

            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">';
            _html += '      ' + val.procedencia;
            _html += '  </td>';

                // Agregando acciones
            var modificar = 'M';
            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';
            _html += '      <a class="success" href="javascript: f_AdminConcesion('+ "'" + modificar + "', " + val.Id + ", '" + val.proveedorminero_documento+"','"+ val.descripcion+"','"+ val.codigo_unico+"','"+ val.cod_procedencia_departamento+"','"+ val.cod_procedencia_provincia+"','"+ val.cod_procedencia_distrito+"'"+ ')"><i class="bi bi-pencil-square"></i>';
            _html += '          <font style="color: #337ab7;"> Editar</font>';
            _html += '      </a>';
            _html += '<br>';

            _html += '      <a class="success" href="javascript: f_EliminarClienteConcesion(' + val.Id + ", '" + val.proveedorminero_documento+"'"+ ')"><i class="bi bi-file-x"></i>';
            _html += '          <font style="color: #F20505;"> Eliminar</font>';
            _html += '      </a>';
            _html += '<br>';

            _html += '  </td>';

            _html += '</tr>';

            d += 1;
          });
        }
        else{
                // alert("No se encontraron resultados.");
        }

        $("#tbl_detalle_cliente_concesion").html(_html);

        f_LoadingResumen(0);

      }, "json");
  };

  function f_AdminClientes(_item, _id_cliente, _cod_tipocliente, _cod_tipodocumento, _documento, _razon_social, _telefono1, _telefono2, _correo, _direccion, _cod_cliente, _representante_dni, _representante_nombres, _id_banco, _num_cuenta,_cci, _id_mediopago, _id_moneda){
          // Definiendo título de ventana e Inicilizando controles de tipo texto
    if (_item != 'x'){
      tipo = "E";
      titulo = 'Editar Cliente:<br>"<b>'+_documento + ' - ' + _razon_social.substring(0, 30) + '...</b>"';
    }
    else{
      tipo = "N";
      titulo = "Nuevo Cliente";
    }

          // Colocando el título a la pantalla
    $("#modal_addclienteLabel").html(titulo);

          // Identificando el tipo de grabación
    $("#hd_modograbar").val(tipo);

          // Cargando datos
    f_OpenModal('modal_addcliente');

    if (tipo != 'N'){
      $("#hd_idcliente").val(_id_cliente);
      $("#cliente_tipocliente").val(_cod_tipocliente);
      $("#cliente_tipodocumento").val(_cod_tipodocumento);
      $("#cliente_documento").val(f_CleanInjection(_documento));
      $("#cliente_razonsocial").val(f_CleanInjection(_razon_social));
      $("#cliente_codcliente").val(f_CleanInjection(_cod_cliente));
      $("#cliente_telefono1").val(_telefono1);
      $("#cliente_telefono2").val(_telefono2);
      $("#cliente_correo").val(f_CleanInjection(_correo));
      $("#cliente_direccion").val(f_CleanInjection(_direccion));
      $("#cliente_representantedni").val(f_CleanInjection(_representante_dni));
      $("#cliente_representantenombres").val(f_CleanInjection(_representante_nombres));
      $("#cliente_bancaria_id_banco").val(f_CleanInjection(_id_banco));
      $("#cliente_bancaria_num_cuenta").val(f_CleanInjection(_num_cuenta));
      $("#cliente_bancaria_cci").val(f_CleanInjection(_cci));
      $("#cliente_bancaria_id_mediopago").val(f_CleanInjection(_id_mediopago));
      $("#cliente_bancaria_id_moneda").val(f_CleanInjection(_id_moneda));
    }
    else{
      $("#hd_idcliente").val(0);
      $("#cliente_tipocliente").val('');
      $("#cliente_tipodocumento").val('');
      $("#cliente_documento").val('');
      $("#cliente_razonsocial").val('');
      $("#cliente_codcliente").val('');
      $("#cliente_telefono1").val('');
      $("#cliente_telefono2").val('');
      $("#cliente_correo").val('');
      $("#cliente_direccion").val('');
      $("#cliente_representantedni").val('');
      $("#cliente_representantenombres").val('');
      $("#cliente_bancaria_id_banco").val('');
      $("#cliente_bancaria_num_cuenta").val('');
      $("#cliente_bancaria_cci").val('');
      $("#cliente_bancaria_id_mediopago").val('');
      $("#cliente_bancaria_id_moneda").val('');
    }
  }

  function f_AdminConcesion(_item,_id_cliente_concesion,_documento_cliente, _descripcion_cliente, _codigo_unico, _cod_procedencia_departamento, _cod_procedencia_provincia, _cod_procedencia_distrito){
      // Definiendo título de ventana e Inicilizando controles de tipo texto
    if (_item != 'x'){
      tipo = "E";
      titulo = 'Editar Concesión';
    }
    else{
      tipo = "N";
      titulo = "Nueva Concesión";
    }

          // Colocando el título a la pantalla
    $("#modal_addconcesionLabel").html(titulo);

          // Identificando el tipo de grabación
    $("#hd_modograbarconcesion").val(tipo);


          // Cargando datos
    f_OpenModal('modal_addconcesion');

    if (tipo != 'N'){
      $("#hd_idclienteconcesion").val(_id_cliente_concesion);

      $("#concesion_descripcion").val(f_CleanInjection(_descripcion_cliente));
      $("#concesion_codigo_unico").val(f_CleanInjection(_codigo_unico));
      $("#concesion_procedencia_departamento").val(f_CleanInjection(_cod_procedencia_departamento));

      f_LoadModificaProvincia(_cod_procedencia_departamento,_cod_procedencia_provincia);
      f_LoadModificaDistrito(_cod_procedencia_departamento,_cod_procedencia_provincia,_cod_procedencia_distrito);
      
    }
    else{
     $("#concesion_descripcion").val('');
     $("#concesion_codigo_unico").val('');
     $("#concesion_procedencia_departamento").val('');
     $("#concesion_procedencia_provincia").html('');
     $("#concesion_procedencia_distrito").html('');
     
   }
  }

  
  function f_AdminBanco(_modo, _id_cliente_banco, _id_banco, _nro_cuenta, _cci, _id_moneda, _is_detraccion){
    var titulo = (_modo == 'M') ? 'Editar Cuenta Bancaria' : 'Nueva Cuenta Bancaria';
    var tipo = (_modo == 'M') ? 'E' : 'N';

    // Título del modal
    $("#modal_addbancoLabel").html(titulo);

    // Modo grabación
    $("#hd_modograbarbanco").val(tipo);

    // Mostrar modal
    f_OpenModal('modal_addbanco');

    if (tipo == 'E') {
      // Valores para edición
      $("#hd_idclientebanco").val(_id_cliente_banco);
      $("#cliente_banco_id_banco").val(_id_banco);
      $("#cliente_banco_num_cuenta").val(_nro_cuenta);
      $("#cliente_banco_cci").val(_cci);
      $("#cliente_banco_id_moneda").val(_id_moneda);
      $("#cliente_banco_is_detraccion").prop("checked", _is_detraccion == "1");
    } else {
      // Limpia campos para nuevo
      $("#hd_idclientebanco").val('');
      $("#cliente_banco_id_banco").val('');
      $("#cliente_banco_num_cuenta").val('');
      $("#cliente_banco_cci").val('');
      $("#cliente_banco_id_moneda").val('');
      $("#cliente_banco_is_detraccion").prop("checked", false);
    }
  }



  function f_AdminClientesConcesion(_item, _id_cliente, _documento, _razon_social){

    $("#hd_clienteconcesion_documento").val(_documento);
          // Definiendo título de ventana e Inicilizando controles de tipo texto
    titulo = 'Cliente:<br>"<b>'+_documento + ' - ' + _razon_social.substring(0, 30) + '...</b>"';
    
          // Colocando el título a la pantalla
    $("#modal_addclienteconcesionLabel").html(titulo);

          // Cargando datos
    f_OpenModal('modal_addclienteconcesion');
    f_LoadClienteConcesionResultados(_documento)

    
  }
  
  function f_AdminClientesBanco(_item, _id_cliente, _documento, _razon_social){

    $("#hd_clientebanco_documento").val(_documento);
    $("#hd_clientebanco_id_cliente").val(_id_cliente);
          // Definiendo título de ventana e Inicilizando controles de tipo texto
    titulo = 'Cliente:<br>"<b>'+_documento + ' - ' + _razon_social.substring(0, 30) + '...</b>"';
    
          // Colocando el título a la pantalla
    $("#modal_addclientebancoLabel").html(titulo);

          // Cargando datos
    f_OpenModal('modal_addclientebanco');
    f_LoadClienteBancoResultados(_id_cliente)

    
  }

  function f_AdminCredito(_id_cliente, _id_credito, _fecha_inicio, _fecha_fin, _observacion){
          // Definiendo título de ventana e Inicilizando controles de tipo texto
    if (_id_credito != 'x'){
      tipo = "E";
      titulo = "Editar Crédito";
    }
    else{
      tipo = "N";
      titulo = "Nuevo Crédito";
    }

          // Colocando el título a la pantalla
    $("#modal_addcreditoLabel").html(titulo);

          // Identificando el tipo de grabación
    $("#hd_modograbarcredito").val(tipo);

          // Cargando datos
    f_OpenModal('modal_addcredito');

    $("#hd_idclientecredito").val(_id_cliente);

    if (tipo != 'N'){
      $("#hd_idcredito").val(_id_credito);
      $("#cred_fechainicio").val(_fecha_inicio);
      $("#cred_fechafin").val(_fecha_fin);
      $("#cred_observacion").val(_observacion);
    }
    else{
      $("#hd_idcredito").val(0);
      $("#cred_fechainicio").val('<?php echo $g_date; ?>');
      $("#cred_fechafin").val('<?php echo $g_date; ?>');
      $("#cred_observacion").val('');
    }
  }

  function f_AdminDescuentos(_id_cliente, _id_descuento, _fecha_inicio, _fecha_fin, _dscto, _observacion){
          // Definiendo título de ventana e Inicilizando controles de tipo texto
    if (_id_descuento != 'x'){
      tipo = "E";
      titulo = "Editar Descuento";
    }
    else{
      tipo = "N";
      titulo = "Nuevo Descuento";
    }

          // Colocando el título a la pantalla
    $("#modal_adddescuentoLabel").html(titulo);

          // Identificando el tipo de grabación
    $("#hd_modograbardescuento").val(tipo);

          // Cargando datos
    f_OpenModal('modal_adddescuento');

    $("#hd_idclientedescuento").val(_id_cliente);

    if (tipo != 'N'){
      $("#hd_iddescuento").val(_id_descuento);
      $("#dscto_fechainicio").val(_fecha_inicio);
      $("#dscto_fechafin").val(_fecha_fin);
      $("#dscto_porc").val(_dscto);
      $("#dscto_observacion").val(_observacion);
    }
    else{
      $("#hd_iddescuento").val(0);
      $("#dscto_fechainicio").val('<?php echo $g_date; ?>');
      $("#dscto_fechafin").val('<?php echo $g_date; ?>');
      $("#dscto_porc").val('');
      $("#dscto_observacion").val('');
    }
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

    function f_GetInfoCliente(_is_representantelegal){
      if (_is_representantelegal != 1){
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
                  var success = arr_response[0].split(':')[1].trim();

                  if (success == 'false'){
                    var razon_social = 'No se encontraron resultados.';
                    var direccion = '';
                  }
                  else{
                    var razon_social = arr_response[1].split(':')[1].trim();
                    var direccion = arr_response[7].split(':')[1].trim();
                    direccion = ((direccion == 'null') ? '---' : direccion);
                  }

                  $("#cliente_razonsocial").val(razon_social);
                  $("#cliente_direccion").val(direccion);
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
      else{
        var documento = $("#cliente_representantedni").val();
        var arr_response = '';

            // Limpiando objetos
        $("#cliente_representantenombres").val('');
        $("#wt_representantelegal").hide();

            // Obteniendo información
        if (documento.length == 8){
          $("#wt_representantelegal").show();

          $.post( "apis/backend.php", { accion: "get_infocliente", is_ruc: is_ruc, documento: documento },
            function( data ) {
              if (data.estado == 1){
                arr_response = data.res.replace(/"/g, '').replace(/{/g, '').replace(/}/g, '').split(',');

                $("#cliente_representantenombres").val(arr_response[0].split(':')[1].trim());
              }
              else{
                $("#cliente_representantenombres").val('NO ENCONTRADO');
              }

              $("#wt_representantelegal").hide();

            }, "json");
        }
      }
    }

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
        // Graba información temporal (onblur).
    function f_GrabarCliente(){
            // Recupera variables
      var id_cliente = $("#hd_idcliente").val();
      var modo_grabar = $("#hd_modograbar").val();

      var cod_tipocliente = f_CleanInjection($("#cliente_tipocliente").val());
      var cod_tipodocumento = f_CleanInjection($("#cliente_tipodocumento").val());
      var documento = f_CleanInjection($("#cliente_documento").val());
      var razon_social = f_CleanInjection($("#cliente_razonsocial").val());
      // var cod_cliente = f_CleanInjection($("#cliente_codcliente").val());
      var telefono1 = f_CleanInjection($("#cliente_telefono1").val());
      var telefono2 = f_CleanInjection($("#cliente_telefono2").val());
      var correo = f_CleanInjection($("#cliente_correo").val());
      var direccion = f_CleanInjection($("#cliente_direccion").val());
      var representante_dni = f_CleanInjection($("#cliente_representantedni").val());
      var representante_nombres = f_CleanInjection($("#cliente_representantenombres").val());
      var bancaria_id_banco = f_CleanInjection($("#cliente_bancaria_id_banco").val());
      var bancaria_cci = f_CleanInjection($("#cliente_bancaria_cci").val());
      var bancaria_num_cuenta = f_CleanInjection($("#cliente_bancaria_num_cuenta").val());
      var bancaria_id_mediopago = f_CleanInjection($("#cliente_bancaria_id_mediopago").val());
      var bancaria_id_moneda = f_CleanInjection($("#cliente_bancaria_id_moneda").val());

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
        alert("Debe ingresar el Documento.");

        return;
      }
      if (documento.length == 0){
        alert("Debe ingresar el Documento.");

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

      // if (cod_cliente == null){
      //   alert("Debe ingresar el Código de Cliente.");

      //   return;
      // }
      // if (cod_cliente.length == 0){
      //   alert("Debe ingresar el Código de Cliente.");

      //   return;
      // }

      if (correo.trim().length > 0){
        if (!f_CheckEMail('cliente_correo')){
          alert("El correo ingresado no tiene el formato correcto.");

          return;
        }
      }

      if (direccion == null){
        alert("Debe ingresar la Dirección.");

        return;
      }
      if (direccion.length == 0){
        alert("Debe ingresar la Dirección.");

        return;
      }

            // Grabando Datos
      $.post( "apis/backend.php", { accion: "grabar_cliente", modo_grabar: modo_grabar, id_cliente: id_cliente, cod_condicion: 1, cod_tipocliente: cod_tipocliente, cod_tipodocumento: cod_tipodocumento, documento: documento, razon_social: razon_social, telefono1: telefono1, telefono2: telefono2, correo: correo, direccion: direccion, cod_cliente: '', representante_dni: representante_dni, representante_nombres: representante_nombres,bancaria_id_banco: bancaria_id_banco,bancaria_num_cuenta: bancaria_num_cuenta,bancaria_cci: bancaria_cci,bancaria_id_mediopago: bancaria_id_mediopago,bancaria_id_moneda: bancaria_id_moneda },
        function( data ) {
          if (data.estado == 2){
            alert("El documento ingresado ya fue registrado anteriormente.\nPor favor verificar.");

            return;
          }
          else{
            if(data.estado == 1){
              f_LoadResultados();

              f_cerrarModal('modal_addcliente');
            }
            else{
              alert("Ocurrió un error al momento de grabar el Cliente");
            }
          }

        }, "json");
    }

        // Cambiar estado de registros
    function f_CambiarEstado(_Estado, _id_cliente){
      var estado = ((_Estado == 'I') ? 'Inactivar' : 'Activar');

            // Validando datos
      if (_Estado != 'A' && _Estado != 'I'){
        alert("Ocurrió un error al momento de cambiar el estado");

        return;
      }

      if(confirm("¿Está seguro de " + estado + " el Cliente seleccionado?")){
        $.post( "apis/backend.php", { accion: "update_EstadoCliente", id_cliente: _id_cliente, estado: _Estado }, 
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
    function f_EliminarCliente(_id_cliente){
      if(confirm("¿Está seguro de eliminar el Cliente seleccionado?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
        $.post( "apis/backend.php", { accion: "eliminar_Cliente", id_cliente: _id_cliente },
          function( data ) {
            if(data.estado == 1){
              f_LoadResultados();
            }
            else{
              alert("Ocurrió un error al momento de eliminar el Cliente.");
            }
          }, "json");
      }
    };

        // Eliminar registros
    function f_EliminarClienteConcesion(_id_cliente_concesion,documento_cliente){
      if(confirm("¿Está seguro de eliminar la Concesión seleccionada?\n\nSi continua perderá la información permanentemente. ¿Desea continuar?")){
        $.post( "apis/backend.php", { accion: "eliminar_ClienteConcesion", id_cliente_concesion: _id_cliente_concesion },
          function( data ) {
            if(data.estado == 1){
              f_LoadClienteConcesionResultados(documento_cliente);
            }
            else{
              alert("Ocurrió un error al momento de eliminar la Concesión.");
            }
          }, "json");
      }
    };

    function f_EliminarClienteBanco(_id_cliente_banco, _id_cliente){
      if (confirm("¿Está seguro de eliminar la cuenta bancaria seleccionada?\n\nSi continúa, perderá la información permanentemente. ¿Desea continuar?")) {
        $.post("apis/backend.php", {
          accion: "eliminar_ClienteBanco",
          id_cliente_banco: _id_cliente_banco
        }, function(data){
          if (data.estado == 1) {
            f_LoadClienteBancoResultados(_id_cliente); // recarga tabla
          } else {
            alert("Ocurrió un error al momento de eliminar la cuenta bancaria.");
          }
        }, "json");
      }
    }


      // Cargar lista de provincias
    function f_LoadListaProvincia(){

      var _html = '<option selected value="">Elija una opción...</option>';

      var _cod_departamento = $("#concesion_procedencia_departamento").val();
      
      $("#concesion_procedencia_provincia").html('');
      $("#concesion_procedencia_distrito").html('');

      $.post( "apis/backend.php", { accion: "get_UbigeoProvincias", cod_departamento: _cod_departamento }, 
        function( data ) {
          if(data.estado == 1){
            $.each( data.registros, function( key, val ) {
              _html += '<option value="' + val.codigo + '">' + val.descripcion + '</option>';
            });
          }
          else{
            // alert("No se encontraron resultados.");
          }

          $("#concesion_procedencia_provincia").html(_html);

        }, "json");
    }

      // Cargar lista de provincias cuando se modifica
    function f_LoadModificaProvincia(_cod_procedencia_departamento,_cod_procedencia_provincia){

      var _html = '<option selected value="">Elija una opción...</option>';

      $("#concesion_procedencia_provincia").html('');

      $.post( "apis/backend.php", { accion: "get_UbigeoProvincias", cod_departamento: _cod_procedencia_departamento }, 
        function( data ) {
          if(data.estado == 1){
            $.each( data.registros, function( key, val ) {

              var seleccionar_item = "";  
              if(val.codigo == _cod_procedencia_provincia){
                seleccionar_item = "selected";
              }
              _html += '<option value="' + val.codigo + '" '+seleccionar_item+'>' + val.descripcion + '</option>';
            });
          }
          else{
            // alert("No se encontraron resultados.");
          }

          $("#concesion_procedencia_provincia").html(_html);

        }, "json");
    }

      // Cargar lista de distritos
    function f_LoadListaDistrito(){

      var _html = '<option selected value="">Elija una opción...</option>';

      var _cod_departamento = $("#concesion_procedencia_departamento").val();
      var _cod_provincia = $("#concesion_procedencia_provincia").val();
      
      $("#concesion_procedencia_distrito").html('');

      $.post( "apis/backend.php", { accion: "get_UbigeoDistritos", cod_departamento: _cod_departamento, cod_provincia: _cod_provincia }, 
        function( data ) {
          if(data.estado == 1){
            $.each( data.registros, function( key, val ) {
              _html += '<option value="' + val.codigo + '">' + val.descripcion + '</option>';
            });
          }
          else{
            // alert("No se encontraron resultados.");
          }

          $("#concesion_procedencia_distrito").html(_html);

        }, "json");
    }

      // Cargar lista de distritos cuando se modifica
    function f_LoadModificaDistrito(_cod_procedencia_departamento,_cod_procedencia_provincia, _cod_procedencia_distrito){

      var _html = '<option selected value="">Elija una opción...</option>';

      $("#concesion_procedencia_distrito").html('');

      $.post( "apis/backend.php", { accion: "get_UbigeoDistritos", cod_departamento: _cod_procedencia_departamento, cod_provincia: _cod_procedencia_provincia }, 
        function( data ) {
          if(data.estado == 1){
            $.each( data.registros, function( key, val ) {

              var seleccionar_item = "";  
              if(val.codigo == _cod_procedencia_distrito){
                seleccionar_item = "selected";
              }

              _html += '<option value="' + val.codigo + '" '+seleccionar_item+'>' + val.descripcion + '</option>';
            });
          }
          else{
            // alert("No se encontraron resultados.");
          }

          $("#concesion_procedencia_distrito").html(_html);

        }, "json");
    }


    function f_GrabarConcesion(){
      var _modo = $("#hd_modograbarconcesion").val();

      var _id_cliente_concesion = $("#hd_idclienteconcesion").val();
      var _documento_cliente = $("#hd_clienteconcesion_documento").val();
      var _concesion_descripcion = $("#concesion_descripcion").val();
      var _concesion_codigo_unico = $("#concesion_codigo_unico").val();
      var _concesion_procedencia_departamento = $("#concesion_procedencia_departamento option:selected").text();
      var _concesion_procedencia_cod_departamento = $("#concesion_procedencia_departamento").val();
      var _concesion_procedencia_provincia = $("#concesion_procedencia_provincia option:selected").text();
      var _concesion_procedencia_cod_provincia = $("#concesion_procedencia_provincia").val();
      var _concesion_procedencia_distrito = $("#concesion_procedencia_distrito option:selected").text();
      var _concesion_procedencia_cod_distrito = $("#concesion_procedencia_distrito").val();

      var _html = '';

        // Validando datos
      if (_concesion_descripcion == null || _concesion_descripcion.length == 0){
        alert("Debe ingresar la Concesión.");

        return;
      }
      
      if (_concesion_descripcion.indexOf('"') >= 0){
        alert("No se puede utilizar COMILLAS en la Concesión." + "\r\n" + "Por favor, corregir.");

        return;
      }

      if (_concesion_descripcion.indexOf("'") >= 0){
        alert("No se puede utilizar COMILLAS SIMPLES en la Concesión." + "\r\n" + "Por favor, corregir.");

        return;
      }


      if (_concesion_codigo_unico == null || _concesion_codigo_unico.length == 0){
        alert("Debe ingresar el Código Único.");

        return;
      }
      
      if (_concesion_codigo_unico.indexOf('"') >= 0){
        alert("No se puede utilizar COMILLAS en el Código Único." + "\r\n" + "Por favor, corregir.");

        return;
      }

      if (_concesion_codigo_unico.indexOf("'") >= 0){
        alert("No se puede utilizar COMILLAS SIMPLES en el Código Único." + "\r\n" + "Por favor, corregir.");

        return;
      }

      if (_concesion_procedencia_cod_departamento == null || _concesion_procedencia_cod_departamento.length == 0){
        alert("Debe seleccionar el Departamento.");

        return;
      }

      if (_concesion_procedencia_cod_provincia == null || _concesion_procedencia_cod_provincia.length == 0){
        alert("Debe seleccionar la Provincia.");

        return;
      }

      if (_concesion_procedencia_cod_distrito == null || _concesion_procedencia_cod_distrito.length == 0){
        alert("Debe seleccionar el Distrito.");

        return;
      }
      

        // Grabando Datos
      $.post( "apis/backend.php", { accion: "grabar_ClienteConcesion",id_cliente_concesion:_id_cliente_concesion, documento_cliente: _documento_cliente, modo_grabar: _modo, concesion_descripcion: _concesion_descripcion,concesion_codigo_unico: _concesion_codigo_unico, 
        concesion_procedencia_departamento: _concesion_procedencia_departamento,
        concesion_procedencia_cod_departamento: _concesion_procedencia_cod_departamento, 
        concesion_procedencia_provincia:_concesion_procedencia_provincia, 
        concesion_procedencia_cod_provincia:_concesion_procedencia_cod_provincia, 
        concesion_procedencia_distrito:_concesion_procedencia_distrito,
        concesion_procedencia_cod_distrito:_concesion_procedencia_cod_distrito 
      },
      function( data ) {
       
        if(data.estado == 1){
          f_cerrarModal('modal_addconcesion');
          f_LoadClienteConcesionResultados($("#hd_clienteconcesion_documento").val());
        }
        else{
          alert("Ocurrió un error al momento de grabar la Concesión");
        }
      }, "json");
      
    };

    function f_GrabarBanco(){
      var _modo = $("#hd_modograbarbanco").val();
      var _id_cliente_banco = $("#hd_idclientebanco").val();
      var _id_cliente = $("#hd_clientebanco_id_cliente").val();
      var _id_banco = $("#cliente_banco_id_banco").val();
      var _nro_cuenta = $("#cliente_banco_num_cuenta").val();
      var _cci = $("#cliente_banco_cci").val();
      var _id_moneda = $("#cliente_banco_id_moneda").val();
      var _is_detraccion = $("#cliente_banco_is_detraccion").is(":checked") ? 1 : 0;

      // Validaciones básicas
      if (_id_banco == "") {
        alert("Debe seleccionar el banco.");
        return;
      }
      if (_nro_cuenta.trim() == "") {
        alert("Debe ingresar el número de cuenta.");
        return;
      }
      if (_id_moneda == "") {
        alert("Debe seleccionar la moneda.");
        return;
      }

      // Envío al backend
      $.post("apis/backend.php", {
        accion: "grabar_ClienteBanco",
        modo_grabar: _modo,
        id_cliente_banco: _id_cliente_banco,
        id_cliente: _id_cliente,
        id_banco: _id_banco,
        nro_cuenta: _nro_cuenta,
        cci: _cci,
        id_moneda: _id_moneda,
        is_detraccion: _is_detraccion
      }, function(data){
        if (data.estado == 1){
          f_cerrarModal("modal_addbanco");
          f_LoadClienteBancoResultados(_id_cliente); 
        } else {
          alert("Ocurrió un error al grabar la cuenta bancaria.");
        }
      }, "json");
    }

    function f_LoadClienteBancoResultados(_id_cliente){
      var _html = '';
      var d = 1;

      $("#tbl_detalle_cliente_banco").html('');

      $.post("apis/backend.php", { accion: "get_listaclientesbancos", id_cliente: _id_cliente }, function(data){
        if(data.estado == 1){
          $.each(data.res, function(key, val){
            var bk_color, estado, href_estado, href_color, href_icon;

            if (val.estado == 'I'){
              bk_color = '#E6A50D';
              estado = 'Inactivo';
              href_estado = 'Activar';
              href_color = '#44803F';
              href_icon = 'bi bi-node-plus';
            } else {
              bk_color = '#44803F';
              estado = 'Activo';
              href_estado = 'Inactivar';
              href_color = '#E6A50D';
              href_icon = 'bi bi-node-minus';
            }

            _html += '<tr style="cursor: pointer; font-size: 14px;">';

            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: right;">' + d + '</td>';
            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">' + val.banco + '</td>';
            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">' + val.nro_cuenta + '</td>';
            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">' + val.cci + '</td>';
            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">' + val.moneda + '</td>';
            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: center;">' + (val.is_detraccion == "1" ? 'Sí' : 'No') + '</td>';

            // Acciones
            _html += '  <td style="border: solid; border-width: 1px; border-color: #D9D9D9; vertical-align: middle; text-align: left;">';
            _html += '    <a href="javascript: f_AdminBanco('
                    + "'M', " + val.Id + ", " 
                    + val.id_banco + ", '" 
                    + f_CleanInjection(val.nro_cuenta) + "', '" 
                    + f_CleanInjection(val.cci) + "', " 
                    + val.id_moneda + ", " 
                    + val.is_detraccion 
                    + ');" class="success"><i class="bi bi-pencil-square"></i><font style="color: #337ab7;"> Editar</font></a><br>';
            _html += '    <a href="javascript: f_EliminarClienteBanco(' + val.Id + ', ' + _id_cliente + ');" class="success"><i class="bi bi-file-x"></i><font style="color: #F20505;"> Eliminar</font></a>';
            _html += '  </td>';

            _html += '</tr>';

            d++;
          });
        }

        $("#tbl_detalle_cliente_banco").html(_html);
        f_LoadingResumen(0);
      }, "json");
    }



  </script>

  <!-- Funciones de Menús -->
  <script type="text/javascript">
    function f_SetDimension(){
      if (screen.width < 500){
        $("#offcanvasExample").css('width', '60%');
      }
    }
  </script>
  </body>
</html>