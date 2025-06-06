<?php
session_start();
include "auth/logincheck.php";
include "assets/config.php";
// include "auth/logincheck.php";

$idPerfil = $_SESSION['pi']['act_idPerfil'];
$id = $_SESSION['pi']['act_id'];
$nombre = $_SESSION['pi']['act_nomre_completo'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Usuarios</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>
    <!-- CSS de Alertify.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.css">
    <!-- Tema predeterminado de Alertify.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/themes/default.min.css" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">


    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://ppi.miclickderecho.com/plantilla/assets/css/styles.css">

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">

    <style>
/* Estilo general en modo oscuro */
/* Estilo general */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #f1f5f9;
}

/* Contenedor del calendario */
#calendar {
    max-width: 1100px;
    margin: 40px auto;
    background: #1e293b;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
}

/* Barra de herramientas */
.fc-toolbar {
    background: #334155;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 15px;
}

.fc-center h2 {
    font-size: 1.5rem;
    color: #f8fafc;
}

/* Botones */
.fc-button {
    background: #4f46e5 !important;
    border: none !important;
    color: white !important;
    font-weight: 600;
    border-radius: 8px !important;
    padding: 6px 14px !important;
    transition: background 0.3s ease;
}

.fc-button:hover {
    background: #6366f1 !important;
}

/* Días */
.fc-day-header {
    background: #0f172a;
    color: #94a3b8;
    font-weight: 600;
    padding: 8px 0;
}

.fc-day {
    background: #0f172a !important;
    border: 1px solid #334155 !important;
    transition: background 0.2s;
}

.fc-day:hover {
    background: #1e293b !important;
}

/* Eventos */
.fc-event {
    background: #0ea5e9 !important;
    border: none !important;
    color: white !important;
    border-radius: 6px;
    font-size: 14px;
    padding: 4px 6px;
}

.fc-event:hover {
    background: #38bdf8 !important;
    color: #0f172a !important;
    font-weight: bold;
}

/* Modal */
.modal-content {
    background: #1e293b;
    color: #f1f5f9;
    border-radius: 14px;
    border: 1px solid #334155;
}

.modal-header,
.modal-footer {
    border-color: #334155;
}

/* Botones de modal */
.btn-primary {
    background-color: #4f46e5;
    border: none;
    font-weight: 600;
}

.btn-primary:hover {
    background-color: #6366f1;
}

.btn-secondary {
    background-color: #64748b;
    border: none;
}

.btn-secondary:hover {
    background-color: #94a3b8;
}

/* Input y selects */
.form-control {
    background-color: #0f172a;
    color: #f8fafc;
    border: 1px solid #334155;
    border-radius: 8px;
}

.form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3);
    background-color: #1e293b;
}

/* Botón Agregar evento */
.add-user-btn {
    margin: 20px 0;
    background-color: #10b981;
    border: none;
    font-weight: 600;
    transition: background 0.3s ease;
}

.add-user-btn:hover {
    background-color: #22c55e;
}
#infoArea {
    font-size: 15px;
    line-height: 1.6;
    border-left: 5px solid #22c55e;
}

#infoArea strong {
    color: #a5f3fc;
}


    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="main-wrapper">
        <div class="container">
            <div class="card rounded mt-5">
                <div style="margin-bottom: 20px;">
                    <button type="button" class="btn btn-primary add-user-btn" id="addUsuario" data-toggle="modal"
                        data-target="#largeModal">Agregar evento</button>
                </div>
                <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel"
                    aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content p-5">
                            <div class="modal-header">
                                <h5 class="modal-title" id="largeModalLabel">Agregar reserva</h5>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- ID OCULTO -->
                                            <input type="hidden" id="idArea" style="display: none;">
                                             <input type="hidden" id="idArea" style="display: none;">
                                            <div class="form-group">
                                                <label for="nombre">Fecha Reserva: <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="fechareserva" required/>
                                            </div>
                                            <div class="form-group">
                                                <label for="horaInicio">Hora inicio:<span class="text-danger">*</span></label>
                                                <select id="horaInicio" class="form-control" name="hora" required>
                                                    <option value="">Selecciona una hora</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="horaFin">Hora fin:<span class="text-danger">*</span></label>
                                                <select id="horaFin" class="form-control" name="hora" required>
                                                    <option value="">Selecciona una hora</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="comentario">Comentario:<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="comentario"
                                                    placeholder="Ingrese la capacidad" required>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="cancelar" class="btn btn-secondary"
                                    data-dismiss="modal">Cancelar</button>
                                <button type="button" id="guardarArea" class="btn btn-primary"
                                    onclick="Save_Calendario()">Guardar</button>
                    
                            </div>
                        </div>
                    </div>
                </div>
                <h1>Calendario de Eventos</h1>
                <div id="infoArea" class="mt-4 p-4 rounded shadow" style="background-color:#334155; display: none;">
    <h5 class="text-white mb-3">Información del Área</h5>
    <ul class="list-unstyled text-white">
        <li><strong>Nombre:</strong> <span id="areaNombre"></span></li>
        <li><strong>Ubicación:</strong> <span id="areaUbicacion"></span></li>
        <li><strong>Capacidad:</strong> <span id="areaCapacidad"></span></li>
        <li><strong>Horario:</strong> <span id="areaHorario"></span> (<span id="areaHoraInicio"></span> a <span id="areaHoraFin"></span>)</li>
        <li><strong>Días disponibles:</strong> <span id="areaDias"></span></li>
        <li><strong>Descripción:</strong> <span id="areaDescripcion"></span></li>
    </ul>
</div>

                <div id="calendar" class="container"></div>
                <!-- Modal de Bootstrap -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Detalles del Evento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p id="eventDescription"></p>
                                <p id="eventArea"></p>
                                <p id="eventStart"></p>
                                <p id="eventEnd"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <!-- Agrega aquí tus botones para editar o eliminar el evento -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="js/bootstrap.min.js"></script> <!-- Asegúrate de cargar Bootstrap después de jQuery -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
    <script>
        var id = <?php echo json_encode($_GET['id']); ?>;
        var idS = <?php echo json_encode($id); ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/calendario.js"></script>

</body>

</html>

