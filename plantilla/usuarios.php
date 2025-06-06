<?php


session_start();
include "auth/logincheck.php";
include "assets/config.php";
// include "auth/logincheck.php";

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
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <div class="main-wrapper">
        <div class="container">
            <div class="card rounded mt-5">

                <div class="d-flex justify-content-between align-items-center mb-3 m-3">
                    <h4 class="m-0">Gestionar Usuario</h4>
                    <button type="button" class="btn btn-primary !ml-3" id="addUsuario" data-toggle="modal"
                        data-target="#largeModal">
                        Agregar usuario
                    </button>
                </div>

                <div class="mb-3">
                    <input type="text" id="buscador" class="form-control w-25 ml-3 " placeholder="Buscar usuarios..."
                        style="height: 35px; font-size: 12px;">
                </div>
                <div style="width: 100%;" class="mt-2">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-hover table-striped table-checkable table-highlight-head m-0"
                            id="tablaUsuarios" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="">DOCUMENTO</th>
                                    <th class="">NOMBRE</th>
                                    <th class="">TELEFONO</th>
                                    <th class="">CORREO</th>
                                    <th class="">PERFIL</th>
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
    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content p-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Gestionar Usuario</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- ID OCULTO -->
                                <input type="hidden" id="idUsuario" style="display: none;">
                                <div class="form-group">
                                    <label for="documento">Documento: <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="documento"
                                        placeholder="Ingrese el documento" required>
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono: <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="telefono"
                                        placeholder="Ingrese el teléfono" required>
                                </div>
                                <div class="form-group">
                                    <label for="recidencia">Recidencia: <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_residencia" required>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" placeholder="Ingrese el nombre"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="correo">Correo: <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="correo" placeholder="Ingrese el correo"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="perfil">Tipo de perfil: <span class="text-danger">*</span></label>
                                    <select class="form-control" id="sel_perfil" required>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="guardarUsuario" class="btn btn-primary"
                        onclick="Save_usuario()">Guardar</button>
                    <button type="button" id="actualizarUsuario" class="btn btn-primary"
                        onclick="Update_usuario()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Mostrar Datos del Usuario -->
    <div class="modal fade" id="modalMostrarUsuario" tabindex="-1" role="dialog"
        aria-labelledby="modalMostrarUsuarioLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMostrarUsuarioLabel">Datos del Usuario</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="documento">Documento:</label>
                                <div id="showDocumento"></div>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono:</label>
                                <div id="showTelefono"></div>
                            </div>
                            <div class="form-group">
                                <label for="residencia">Recidencia:</label>
                                <div id="showResidencia"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <div id="showNombre"></div>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo:</label>
                                <div id="showCorreo"></div>
                            </div>
                            <div class="form-group">
                                <label for="perfil">Tipo de perfil:</label>
                                <div id="showPerfil"></div>
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
    <script src="js/usuarios.js"></script>
</body>


</html>