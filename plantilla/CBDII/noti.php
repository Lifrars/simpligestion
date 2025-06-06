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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Aceptar/Rechazar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="notification">
        <h2>Notificación</h2>
        <p>Tienes una nueva solicitud. ¿Deseas aceptarla o rechazarla?</p>
        <div class="buttons">
            <button class="accept">Aceptar</button>
            <button class="reject">Rechazar</button>
        </div>
    </div>

    <link rel="stylesheet" href="https://ppi.miclickderecho.com/plantilla/assets/css/stylenoti.css">
</body>
</html>
