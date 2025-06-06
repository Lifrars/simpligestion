<?php


session_start();
include "assets/config.php";
include "auth/logincheck.php";

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
    <title>Gestor Documental</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
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
            <div class="card-body ">
                <div style="width: 100%;" class="mt-4">
                    <div class="table-responsive">
                        <H3>Votar</H3>
                        <table
                            class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0"
                            id="tablaVotar" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="">Título</th>
                                    <th class="">Descripcion</th>
                                    <th class="">Ganador</th>
                                    <th class="">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="mt-4" id="paginacion"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content p-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Agregar voto</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <!-- ID OCULTO -->
                        <input type="hidden" id="idVotacion" style="display: none;">
                        <div class="form-group text-center">
                            <label for="titulo" class="text-center">Título: <span id="titulo"></span></label>
                        </div>
                        <div class="form-group text-center">
                            <label for="descripcion" class="text-center">Descripción: <span
                                    id="descripcion"></span></label>
                        </div>
                        <div class="form-group">
                            <label for="opcion">Opción:</label>
                            <select class="form-control" id="sel_perfil"></select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="guardarVoto" class="btn btn-primary"
                                onclick="GuardarVoto()">Votar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/votar.js"></script>
</body>

</html>