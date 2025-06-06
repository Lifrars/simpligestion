<?php
session_start();
include "auth/logincheck.php";
include "assets/config.php";

$moduloid = "2";
$modulo = "Administracion";
$vistaid = "3";
$vista = "Torres";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Residencias</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://ppi.miclickderecho.com/plantilla/assets/css/styles.css">

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="main-wrapper">
        <div class="container">
            <div class="card rounded mt-2">

                <div class="card-body">
                    <div class="py-2">
                        <h4 class="mb-0">Ver residencias</h3>
                    </div>
                    <div class="mb-3">
                        <input type="text" id="buscador" class="form-control w-25 " placeholder="Buscar torre..." style="height: 35px; font-size: 12px;">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="tablaTorres" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Nombre Torre</th>
                                    <th>Número de Piso</th>
                                    <th>Número de Residencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán dinámicamente con JavaScript -->
                            </tbody>
                        </table>
                        <div class="mt-4" id="paginacion"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Agregar/Editar Torre -->
        <div class="modal fade" id="modalTorre" tabindex="-1" role="dialog" aria-labelledby="modalTorreLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content p-5">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTorreLabel">Gestionar Torre</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formTorre">
                            <input type="hidden" id="idTorre">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombreTorre">Nombre Torre:</label>
                                        <input type="text" class="form-control" id="nombreTorre" placeholder="Ingrese el nombre de la torre" required oninput="convertirAMayusculas()" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="numPisos">Número de Pisos:</label>
                                        <input type="number" class="form-control" id="numPisos" placeholder="Ingrese el número de pisos" required oninput="limitarNumero()">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" onclick="guardarTorre()" id="guardarTorre" class="btn btn-primary">Guardar</button>
                        <button type="button" onclick="actualizarTorre()" id="actualizarTorre" class="btn btn-primary" style="display: none;">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Mostrar Datos de la Residencia -->
        <div class="modal fade" id="modalMostrarResidencia" tabindex="-1" role="dialog"
            aria-labelledby="modalMostrarResidenciaLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content p-4">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMostrarResidenciaLabel">Detalles de la Residencia</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Torre:</label>
                                    <div id="showTorre"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Piso:</label>
                                    <div id="showPiso"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de Residencia:</label>
                                    <div id="showNumeroResidencia"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Propietario:</label>
                                    <div id="showPropietario"></div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label>Residentes Asociados:</label>
                                    <ul id="listaResidentes" class="list-group list-group-flush">
                                        <!-- Aquí se cargarán los residentes -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="plugins/highlight/highlight.pack.js"></script>
        <script src="perfilar.js"></script>
        <script>
            var idperfil = '<?php echo ($_SESSION['pi']['act_idPerfil']); ?>';
            var moduloid = '<?php echo ($moduloid); ?>';
            var vistaid = '<?php echo ($vistaid); ?>';
            perfilar(idperfil, moduloid, vistaid);
        </script>
        <script src="js/torres.js"></script>

        <!-- Ajuste de estilo para eliminar espacios extra -->
        <style>
            .card-header {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }

            .card-header h5 {
                margin: 0 !important;
                font-size: 1.1rem;
            }

            .card.mt-2 {
                margin-top: 1rem !important;
            }
        </style>
    </div>
</body>

</html>