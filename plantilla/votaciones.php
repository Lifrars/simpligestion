<?php


session_start();
include "auth/logincheck.php";
include "assets/config.php";
// include "auth/logincheck.php";

$moduloid = "1";
$modulo = "Votaciones";
$vistaid = "2";
$vista = "Votaciones";

$idPerfil = $_SESSION['pi']['act_idPerfil'];
$id = $_SESSION['pi']['act_id'];
$nombre = $_SESSION['pi']['act_nomre_completo'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Usuarios</title>
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

    <?php

    include 'menu.php';

    ?>


    <div class="container">
        <div class="card rounded mt-5">
            <div class="card rounded">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="mb-0">Votaciones</h4>
                </div>
                <div style="margin-bottom: 20px;"> <!-- Ajusta el valor de margin-bottom según necesites -->
                    <button type="button" class="btn btn-primary add-user-btn" id="addvotacion">Agregar
                        votacion</button>
                </div>

                <div class="mb-3">
                    <input type="text" id="buscador" class="form-control w-25 ml-3" placeholder="Buscar votación..."
                        style="height: 35px; font-size: 12px;">
                </div>

            </div>


            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0"
                    id="tablaVotaciones" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="" style="display: none;">Id</th>
                            <th class="">Título</th>
                            <th class="">Descripcion</th>
                            <th class="">Estado</th>
                            <th class="">Ganador</th>
                            <th class="">Cierre</th>
                            <th class="">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="mt-4" id="paginacion"></div>
            </div>

            <!-- Large Modal -->
            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
                aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content p-5">

                        <div class="modal-header">
                            <h5 class="modal-title" id="largeModalLabel">Editar Votacion</h5>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- ID OCULTO -->
                                        <input type="hidden" id="idVotacion" style="display: none;">
                                        <div class="form-group">
                                            <label for="documento">Título:</label>
                                            <input type="text" class="form-control" id="titulo"
                                                placeholder="Ingrese el título">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telefono">Descripción:</label>
                                            <input type="text" class="form-control" id="descripcion"
                                                placeholder="Ingrese la descripción">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-6">
                                    <h2>Opciones</h2>
                                </div>
                                <div style="margin-bottom: 20px;" class="col-md-6">
                                    <button type="button" class="btn btn-primary add-user-btn" id="addOpcion"
                                        onclick="agregarOpcion()">Agregar Opcion</button>
                                </div>
                            </div>

                            <div class="table-responsive mt-4">

                                <table
                                    class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0"
                                    id="tablaOpciones" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="" style="display: none;">Id</th>
                                            <th class="">Descripcion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4" id="paginacion"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="actualizarVotacion" class="btn btn-primary"
                                onclick="Update_Votacion()">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Large Modal -->
            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content p-5">
                        <div class="modal-header">
                            <h5 class="modal-title" id="largeModalLabel">Large Modal Example</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>This is a large modal. You can put any content you want here.</p>
                            <p>For example, you can add forms, images, videos, or any other HTML content.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

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


    <script src="js/votaciones.js"></script>
</body>


</html>