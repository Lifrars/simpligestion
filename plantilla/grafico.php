<?php
session_start();
include "auth/logincheck.php";
include "assets/config.php";
// include "auth/logincheck.php";


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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php

    include 'menu.php';
    if(isset($_GET['dato'])) {
        // Obtiene el valor del parámetro 'dato'
        $dato = $_GET['dato'];
    
        // Imprime el valor del parámetro 'dato' en un script JavaScript
        echo '<script>';
        echo 'var dato = "' . $dato . '";'; // Asigna el valor del parámetro a una variable JavaScript
        echo 'console.log("El dato obtenido es: " + dato);'; // Muestra el valor en la consola del navegador
        echo '</script>';
    }
    ?>
    <div id="chart" style="display: flex; justify-content: space-between; margin: 20px;"></div>
    <div id="bar" style="display: flex; justify-content: space-between; margin: 20px;"></div>

<script src="js/grafico.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.1/dist/apexcharts.min.js"></script>
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->


</body>
</html>