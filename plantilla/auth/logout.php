<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../assets/config.php"; // Suponiendo que config.php define las constantes de la base de datos
session_start();

// Verificar si la sesión está iniciada y si existe la clave 'act_id' en $_SESSION['pi']
if(isset($_SESSION['pi']['act_id'])) {
    // Establecer conexión a la base de datos
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Verificar la conexión
    if ($con->connect_error) {
        die("Error de conexión a la base de datos: " . $con->connect_error);
    }

    // Preparar la consulta SQL para actualizar la tabla 'usuarios'
    $query = "UPDATE usuarios SET session_activa = 0, ultima_sesion = '1900-01-01 00:00:00' WHERE id = ?";
    
    // Preparar y ejecutar la declaración preparada
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $_SESSION['pi']['act_id']); // Vincular el parámetro
    $stmt->execute();

    // Verificar si ocurrió un error durante la ejecución de la consulta
    if ($stmt->error) {
        die("Error en la actualización de la base de datos: " . $stmt->error);
    }

    // Cerrar la declaración preparada y la conexión
    $stmt->close();
    $con->close();
}

// Limpiar y destruir la sesión
unset($_SESSION['pi']);
session_destroy();

// Redirigir a la página de inicio de sesión
header("Location: login.php");
exit(); // Asegura que no se ejecute más código después de redirigir
?>
