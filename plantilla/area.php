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
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Usuarios</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    
    <!-- Fuentes y librerías externas -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <!-- Estilos y plugins -->
    <link href="assets/css/loader.css" rel="stylesheet" />
    <link href="assets/css/plugins.css" rel="stylesheet" />
    <link href="assets/plugins/fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" />
    <link href="assets/css/dashboard/dash_2.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://ppi.miclickderecho.com/plantilla/assets/css/styles.css">

    <script src="assets/js/loader.js"></script>
</head>
<body>

<?php include 'menu.php'; ?>

<div class="main-wrapper">
<div class="card-body">

                <div class="table-responsive mt-3">
                            <div class="container mt-5">
        <div class="card rounded">
    <div class="card-header d-flex justify-content-between ">
    <h4 class="mb-0">Gestión de Áreas</h4>
    <button type="button" class="btn btn-primary ml-2" id="addUsuario" data-toggle="modal" data-target="#largeModal">
        Agregar Área
    </button>
    
</div>

               <div class="mb-3">
                   <input type="text" id="buscador" class="form-control w-25 ml-3" placeholder="Buscar área..." style="height: 35px; font-size: 12px;">
                </div>
              
                    <table class="table table-bordered table-hover table-striped" id="tablaAreas" style="width:100%;">
                        <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>DESCRIPCIÓN</th>
                                <th>CAPACIDAD</th>
                                <th>HORARIO</th>
                                <th>DÍAS</th>
                                <th>UBICACIÓN</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="mt-4" id="paginacion"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar/Actualizar Área -->
    <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Gestionar Área</h5>
                </div>
                <div class="modal-body">
                    <form id="formArea">
                        <input type="hidden" id="idArea">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" required placeholder="Ingrese el nombre">
                                </div>
                                <div class="form-group">
                                    <label for="descripcion">Descripción: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="descripcion" required placeholder="Ingrese la descripción">
                                </div>
                                <div class="form-group">
                                    <label for="capacidad">Capacidad: <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="capacidad" required placeholder="Ingrese la capacidad">
                                </div>
                                <div class="form-group">
                                    <label for="ubicacion">Ubicación: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ubicacion" required placeholder="Ingrese la ubicación">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Días Disponibles: <span class="text-danger">*</span></label>
                                    <div id="diasDisponibles" class="d-flex flex-wrap gap-2">
                                        <?php
                                            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                            foreach ($dias as $dia) {
                                                echo "<div class='form-check form-check-inline'>
                                                        <input class='form-check-input' type='checkbox' id='dia$dia' value='$dia'>
                                                        <label class='form-check-label' for='dia$dia'>$dia</label>
                                                    </div>";
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="horaInicio">Hora inicio: <span class="text-danger">*</span></label>
                                    <select id="horaInicio" class="form-control" required>
                                        <option value="">Selecciona una hora</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="horaFin">Hora fin: <span class="text-danger">*</span></label>
                                    <select id="horaFin" class="form-control" required>
                                        <option value="">Selecciona una hora</option>
                                    </select>
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

    <!-- Modal Ver Detalles Área -->
    <div class="modal fade" id="modalMostrarArea" tabindex="-1" role="dialog" aria-labelledby="modalMostrarAreaLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMostrarAreaLabel">Datos del Área</h5>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6">
                        <div class="form-group"><strong>Nombre:</strong> <div id="showNombre"></div></div>
                        <div class="form-group"><strong>Descripción:</strong> <div id="showDescripcion"></div></div>
                        <div class="form-group"><strong>Capacidad:</strong> <div id="showCapacidad"></div></div>
                        <div class="form-group"><strong>Horario:</strong> <div id="showHorario"></div></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><strong>Ubicación:</strong> <div id="showUbicacion"></div></div>
                        <div class="form-group"><strong>Días:</strong> <div id="showDias"></div></div>
                        <div class="form-group"><strong>Estado:</strong> <div id="showEstado"></div></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/highlight/highlight.pack.js"></script>
<script src="perfilar.js"></script>
<script>
    const idperfil = '<?php echo $_SESSION['pi']['act_idPerfil']; ?>';
    const moduloid = '<?php echo $moduloid; ?>';
    const vistaid = '<?php echo $vistaid; ?>';
</script>
<script src="js/areas.js"></script>
</body>
</html>
