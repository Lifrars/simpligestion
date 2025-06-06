<?php
session_start();
include "auth/logincheck.php";
include "assets/config.php";

$moduloid = "2";
$modulo = "Administracion";
$vistaid = "3";
$vista = "Residencias";
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
            <div class="card rounded mt-5">
                <div style="margin-bottom: 20px;">
                    <button type="button" class="btn btn-primary" id="addResidencia" data-toggle="modal"
                        data-target="#modalResidencia">Agregar Residencia</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="tablaResidencias" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Dirección Residencia</th>
                                    <th>Documento Propietario</th>
                                    <th>Nombre Torre</th>
                                    <th>Acciones</th>
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

        <!-- Modal para Agregar/Editar Residencia -->
        <div class="modal fade" id="modalResidencia" tabindex="-1" role="dialog" aria-labelledby="modalResidenciaLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content p-5">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalResidenciaLabel">Gestionar Residencia</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formResidencia">
                            <input type="hidden" id="idResidencia"> <!-- Campo oculto para ID -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccionResidencia">Dirección Residencia:</label>
                                        <input type="text" class="form-control" id="direccionResidencia" placeholder="Ingrese la dirección de la residencia" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="documentoPropietario">Documento Propietario:</label>
                                        <input type="text" class="form-control" id="documentoPropietario" placeholder="Ingrese el documento del propietario" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="idTorre">Seleccionar Torre:</label>
                                        <select class="form-control" id="idTorre" required>
                                            <option value="">Seleccione una Torre</option>
                                            <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" onclick="guardarResidencia()" id="guardarResidencia" class="btn btn-primary">Guardar</button>
                        <button type="button" onclick="actualizarResidencia()" id="actualizarResidencia" class="btn btn-primary" style="display: none;">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Mostrar Datos de la Residencia -->
        <div class="modal fade" id="modalMostrarResidencia" tabindex="-1" role="dialog"
            aria-labelledby="modalMostrarResidenciaLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMostrarResidenciaLabel">Detalles de la Residencia</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dirección Residencia:</label>
                                    <div id="showDireccionResidencia"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Documento Propietario:</label>
                                    <div id="showDocumentoPropietario"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre Torre:</label>
                                    <div id="showNombreTorre"></div>
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
        </script>
        <script src="js/residencias.js"></script>
</body>

</html>