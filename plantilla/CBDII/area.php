<?php
session_start();
include "assets/config.php";
include "auth/logincheck.php";

$moduloid = "2";
$modulo = "Administracion";
$vistaid = "3";
$vista = "Usuarios";
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
    <?php include 'menu.php'; ?>
    <div class="main-wrapper">
        <div class="container">
            <div class="card rounded mt-5">
                <div style="margin-bottom: 20px;">
                    <button type="button" class="btn btn-primary add-user-btn" id="addUsuario" data-toggle="modal" data-target="#largeModal">Agregar area</button>
                </div>
                <div class="card-body">
                    <div style="width: 100%;" class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0" id="tablaAreas" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="">NOMBRE</th>
                                        <th class="">DESCRIPCION</th>
                                        <th class="">CAPACIDAD</th>
                                        <th class="">HORARIO</th>
                                        <th class="">UBICACION</th>
                                        <th class="">ACCIONES</th>
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

        <!-- Large Modal -->
        <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content p-5">
                    <div class="modal-header">
                        <h5 class="modal-title" id="largeModalLabel">Agregar area</h5>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- ID OCULTO -->
                                    <input type="hidden" id="idArea" style="display: none;">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" placeholder="Ingrese el nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion">Descripcion:</label>
                                        <input type="text" class="form-control" id="descripcion" placeholder="Ingrese la descripcion" required >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="capacidad">Capacidad:</label>
                                        <input type="text" class="form-control" id="capacidad" placeholder="Ingrese la capacidad" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="horario">Horario:</label>
                                        <input type="text" class="form-control" id="horario" placeholder="Ingrese el horario" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="ubicacion">Ubicacion:</label>
                                        <input type="text" class="form-control" id="ubicacion" placeholder="Ingrese la ubicacion" required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="guardarArea" class="btn btn-primary" onclick="Save_usuario()">Guardar</button>
                        <button type="button" id="actualizarArea" class="btn btn-primary" onclick="Update_usuario()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Mostrar Datos del Usuario -->
        <div class="modal fade" id="modalMostrarArea" tabindex="-1" role="dialog" aria-labelledby="modalMostrarAreaLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMostrarAreaLabel">Datos del Area</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre:</label>
                                    <div id="showNombre"></div>
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripcion:</label>
                                    <div id="showDescripcion"></div>
                                </div>
                                <div class="form-group">
                                    <label for="capacidad">Capacidad:</label>
                                    <div id="showCapacidad"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="horario">Horario:</label>
                                    <div id="showHorario"></div>
                                </div>
                                <div class="form-group">
                                    <label for="ubicacion">Ubicacion:</label>
                                    <div id="showUbicacion"></div>
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
        <script src="js/areas.js"></script>
</body>

</html>
