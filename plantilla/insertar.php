<?php
session_start();
include "auth/logincheck.php";
include "assets/config.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestor Documental</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link rel="stylesheet" href="assets/css/plugins.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #0a0a17;
        /* Color oscuro, ajusta según tu diseño */
    }

    .container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
    }

    .delete-option {
        cursor: pointer;
        color: red;
        margin-left: 10px;
    }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h1 class="title has-text-centered">Crear Votación</h1>
        <form id="votacion-form">
            <div class="field">
                <label class="label">Título: *</label>
                <div class="control">
                    <input class="input" type="text" id="titulo" name="titulo" required>
                </div>
            </div>
            <div class="field">
                <label class="label">Descripción: *</label>
                <div class="control">
                    <textarea class="textarea" id="descripcion" name="descripcion" required></textarea>
                </div>
            </div>
            <div class="field">
                <label class="label">Fecha de cierre: *</label>
                <div class="control">
                    <input type="date" id="fecha_cierre" name="fecha_cierre" min="" required onkeydown="return false"
                        onpaste="return false">

                </div>
            </div>

            <div id="contenedor-opciones">
                <div class="field option-field">
                    <label class="label">Opción 1: *</label>
                    <div class="control has-icons-right">
                        <input class="input option-input" type="text" name="opcion_1" required>
                    </div>
                </div>
                <div class="field option-field">
                    <label class="label">Opción 2: *</label>
                    <div class="control has-icons-right">
                        <input class="input option-input" type="text" name="opcion_2" required>
                    </div>
                </div>
            </div>
            <div class="field is-grouped is-grouped-centered">
                <div class="control">
                    <button type="button" class="button is-info" onclick="agregarOpcion()">Agregar Opción</button>
                </div>
                <div class="control">
                    <button type="submit" class="button is-primary">Crear Votación</button>
                </div>
            </div>
        </form>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let hoy = new Date();

        // Ajusta la zona horaria para evitar desfases
        hoy.setHours(0, 0, 0, 0);

        // Formatea la fecha correctamente en YYYY-MM-DD
        let fechaFormateada = hoy.toISOString().split("T")[0];

        // Asigna la fecha mínima al input
        document.getElementById("fecha_cierre").setAttribute("min", fechaFormateada);
    });
    </script>
    </script>
    <script src="js/inserta_vota.js"></script>
</body>

</html>