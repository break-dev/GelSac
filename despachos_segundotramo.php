<?php
// Inicia la sesión
session_start();

// Inclusión de archivos de configuración y utilidades
include('cnx/cnx.php');
include('global/variables.php');
include('global/auxiliares.php');

// Redirección si el usuario no está autenticado
if (!isset($_SESSION["Id"])) {
    header('Location: index.php');
    exit;
}

// endpoint de backend
$backendUrl = 'apis/backend.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/png" />

    <title><?php echo $nom_app; ?> | Balanza (Segundo Tramo)</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <link rel="stylesheet" href="<?php echo $url_lims; ?>/global/styles.css">
</head>

<body class="bg-light" style="zoom: 80%;">
    <div class="container-fluid">
        <div class="row">
            <?php echo $navbar_maintop; ?>

            <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true"
                data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-left" style="margin-top: 0px !important; margin-left: 0px !important;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="menuModalLabel">Menú de Opciones</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body"
                            style="background: #25476a; color: white; border-top: solid #EFB810 3px; padding: 0px !important;">
                            <ul class="list-unstyled">
                                <div id="div_menu1"></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal Header -->
            <div class="col-md-12 col-sm-12 col-xs-12"
                style="border: solid; border-width: 1px; border-color: #E6E9ED; border-radius: 7px; padding-top: 10px;">
                <div class="d-flex">

                    <!-- Columna Izquierda: Unidades en Planta -->
                    <div class="col-md-3 col-sm-4 col-xs-12 shadow-sm bg-white d-flex flex-column"
                        style="border: solid 1px #E6E9ED; border-radius: 7px; margin-right: 5px; padding: 0; min-height: calc(100vh / 0.8 - 120px);">
                        <div
                            style="padding: 10px; border-top-left-radius: 7px; border-top-right-radius: 7px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #ffffff; text-align: center;">
                            <div class="d-flex justify-content-center align-items-center">
                                <h5 style="font-size: 14px; font-weight: bold; margin: 0;">
                                    <i class="bi bi-truck me-2"></i> Unidades en Planta
                                </h5>

                                <div id="wt_loadingunidades"
                                    style="font-size: 12px; text-align: center; display: none; margin-left: 10px;">
                                    <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros (Small) -->
                        <div class="p-2" style="background-color: #f8f9fa; border-bottom: 1px solid #ddd;">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text">Fecha</span>
                                <input type="date" class="form-control" id="filtro_fecha_inicio"
                                    onchange="f_LoadDistribuciones()">
                            </div>
                            <div class="input-group input-group-sm mb-0">
                                <span class="input-group-text">Placa</span>
                                <input type="text" class="form-control" id="filtro_placa" placeholder="..."
                                    onkeyup="if(event.keyCode==13) f_LoadDistribuciones()">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="f_LoadDistribuciones()"><i class="bi bi-search"></i></button>
                            </div>
                        </div>

                        <!-- Listado de Tarjetas -->
                        <div id="div_unidades_planta" class="flex-grow-1"
                            style="padding: 5px; font-size: 13px; max-height: calc(100vh / 0.8 - 250px); overflow-y: auto;">
                            <!-- Aqui van las Cards generadas por JS -->
                        </div>
                    </div>

                    <!-- Columna Derecha: Panel de Pesaje -->
                    <div class="col-md-9 col-sm-8 col-xs-12 shadow-sm bg-white d-flex flex-column"
                        style="border: solid 1px #E6E9ED; border-radius: 7px; background: #fbfbfb; padding: 0; min-height: calc(100vh / 0.8 - 120px);">
                        <div class="position-relative shadow-sm"
                            style="padding: 13px; border-top-left-radius: 7px; border-top-right-radius: 7px; background: linear-gradient(135deg, #cfaa41, #e3c45b);">
                            <div class="text-center">
                                <h5
                                    style="font-size: 16px; font-weight: bold; margin: 0; color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                                    <i class="bi bi-ui-checks-grid me-2"></i> Captura de Pesos
                                </h5>
                            </div>
                        </div>

                        <!-- Panel Interactivo de Pesaje -->
                        <div id="div_panel_pesaje" style="padding: 15px; display: none;">
                            <input type="hidden" id="pesaje_id_distribucion">

                            <!-- Header de Info -->
                            <div class="row mb-4 p-3 bg-white shadow-sm border rounded align-items-center"
                                style="border-radius: 10px !important;">
                                <div class="col-md-3 border-end">
                                    <div class="text-muted small fw-bold text-uppercase"><i
                                            class="bi bi-hash text-primary"></i> Despacho</div>
                                    <div class="fw-bold fs-4 text-primary" id="lbl_correlativo"></div>
                                </div>
                                <div class="col-md-3 border-end">
                                    <div class="text-muted small fw-bold text-uppercase"><i
                                            class="bi bi-building text-info"></i> Transportista</div>
                                    <div class="fw-bold text-dark text-truncate" id="lbl_transportista"></div>
                                </div>
                                <div class="col-md-3 border-end">
                                    <div class="text-muted small fw-bold text-uppercase"><i
                                            class="bi bi-truck-front-fill text-secondary"></i> Unidad / Placas</div>
                                    <div class="fw-bold fs-5 text-dark" id="lbl_placas"></div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="text-muted small fw-bold text-uppercase"><i
                                            class="bi bi-basket3 text-success"></i> Peso a Distribuir</div>
                                    <h3 class="mb-0 fw-bold text-success"><span id="lbl_peso_esperado"></span> <small
                                            class="text-muted fs-6">Kg</small></h3>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Lector Balanza -->
                                <!-- <div class="col-md-12 mb-3">
                                    <div class="card border-dark shadow-sm">
                                        <div
                                            class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2">
                                            <span>Lectura de Balanza</span>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input bg-success border-success"
                                                    type="checkbox" id="chk_auto_pesaje" checked>
                                                <label class="form-check-label text-white small"
                                                    for="chk_auto_pesaje">Auto</label>
                                            </div>
                                        </div>
                                        <div class="card-body bg-light text-center py-2">
                                            <div id="div_SinConexion_balanza" style="display: none; color: red;"
                                                class="mb-2 small">
                                                <i class="bi bi-x-circle-fill"></i> Sin conexión a balanza
                                            </div>
                                            <h1 class="display-3 text-success fw-bold m-0" id="lbl_peso_actual"
                                                style="font-family: monospace;">0.00</h1>
                                            <p class="text-muted m-0">Kg</p>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Detalle Lotes -->
                                <div class="col-md-12 flex-grow-1 d-flex flex-column">
                                    <h6 class="fw-bold mb-2 text-primary">Lotes/Blendings por pesar</h6>
                                    <div id="div_detalles_lotes" class="flex-grow-1"
                                        style="max-height: calc(100vh / 0.8 - 350px); overflow-y: auto; padding-right: 5px;">
                                        <!-- Generado dinámicamente -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Indicador Vacio -->
                        <div id="div_empty_state" class="text-center text-muted" style="padding: 100px 0;">
                            <i class="bi bi-truck display-1 text-secondary opacity-25" style="font-size: 6rem;"></i>
                            <h4 class="mt-4 fw-bold text-secondary">Seleccione una unidad de la lista</h4>
                            <p class="text-muted fs-6">Haga clic en una de las tarjetas de la izquierda para comenzar el
                                pesaje.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Cargar jQuery antes de auxiliares para evitar errores ReferenceError -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>

    <?php include('global/auxiliares_js.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.3.3/dist/echarts.min.js"></script>
    <script src="despachos_segundotramo.js"></script>

</body>

</html>