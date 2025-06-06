<?php


session_start();
include "assets/config.php";
include "auth/logincheck.php";



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Gestor Documental</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://ppi.miclickderecho.com/plantilla/assets/css/styles.css">

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" href="inserta.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-5">
        <h1 class="title is-1 has-text-centered">Crear Votación</h1>
        <form>
            <div id="contenedor-opciones">
                <div class="field">
                    <label class="label" for="titulo">Título:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Escribe aquí..." id="titulo" name="titulo"
                            required>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="descripcion">Descripción:</label>
                    <div class="control">
                        <textarea class="textarea" placeholder="Descripción" id="descripcion"
                            style="height: 100px"></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="opcion_1">Opción 1:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Escribe aquí..." id="opcion_1" name="opcion_1"
                            required>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="opcion_2">Opción 2:</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Escribe aquí..." id="opcion_2" name="opcion_2"
                            required>
                    </div>
                </div>
            </div>

            <div class="field is-grouped is-grouped-centered">
                <div class="control">
                    <a href="votaciones.php" onclick="enviar_vota()">
                        <input type="button" class="button is-primary" value="Crear encuesta">
                    </a>
                </div>

                <div class="control">
                    <input type="button" class="button is-info" value="Agregar Opción" onclick="agregarOpcion()">
                </div>
            </div>
        </form>
    </div>

    <script src="js/inserta_vota.js"></script>
</body>

</html>