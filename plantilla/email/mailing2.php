<?php

header('Content-Type: text/html; charset=UTF-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Incluir los archivos de PHPMailer necesarios
include "lib/phpEmail/src/Exception.php";
include "lib/phpEmail/src/PHPMailer.php";
include "lib/phpEmail/src/SMTP.php";

// Variables post recibidas
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : 'Descripción no proporcionada';
$idUser = isset($_POST['idUser']) ? trim($_POST['idUser']) : 'ID de usuario no proporcionado';
$ticketId = isset($_POST['ticketId']) ? trim($_POST['ticketId']) : 'ID de ticket no proporcionado';

// Dirección de correo del celador
$correoCelador = 'juan_valencia23212@elpoli.edu.co';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0; // Deshabilitar el debugging de SMTP
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'bh8960.banahosting.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'notificaciones@metaversomsp.com';
    $mail->Password = '^zx$z=w9qd+A';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('notificaciones@metaversomsp.com', 'SimpliGestion');
    $mail->addAddress($correoCelador);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Notificación de Nuevo Ticket - SimpliGestion';

    // Preparar el cuerpo del correo
    $mail->Body = "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Nuevo Ticket - SimpliGestion</title>
        <style>
            body {font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0;}
            .container {max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;}
            .header {text-align: center; margin-bottom: 20px;}
            .footer {text-align: center; margin-top: 20px;}
            .btn {display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;}
            .ticket-info {background-color: #f1f1f1; padding: 10px; border-radius: 5px; margin-bottom: 20px;}
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Nuevo Ticket - SimpliGestion</h1>
            </div>
            <p>Hola,</p>
            <p>Se ha generado un nuevo ticket con la siguiente información:</p>
            <div class='ticket-info'>
                <p><strong>ID del Ticket:</strong> {$ticketId}</p>
                <p><strong>ID del Usuario:</strong> {$idUser}</p>
                <p><strong>Descripción del Problema:</strong> {$descripcion}</p>
            </div>
            <p>Por favor, atienda este problema lo antes posible.</p>
            <div class='footer'>
                <p>🖥 SimpliGestion - Gestión Simplificada</p>
            </div>
        </div>
    </body>
    </html>";

    $mail->send();
    echo json_encode('Correo enviado con éxito.');
} catch (Exception $error) {
    echo "Error en el envío del correo electrónico: " . $error->getMessage();
    die(); // Detiene la ejecución del script si hay un error
}
