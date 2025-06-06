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
$respuesta = isset($_POST['respuesta']) ? trim($_POST['respuesta']) : 'Nombre por defecto';
$correos = isset($_POST['correos']) ? trim($_POST['correos']) : 'correo@default.com';
$codigoTemporal = isset($_POST['codigoTemporal']) ? trim($_POST['codigoTemporal']) : mt_rand(10000, 99999);  // Generar un código si no se recibe

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
    $mail->addAddress($correos);
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Confirmación Simpligestion';

    // Preparar el cuerpo del correo
    $mail->Body = "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Código Temporal - Simpligestion</title>
        <style>
            body {font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0;}
            .container {max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px; background-color: #f9f9f9;}
            .header {text-align: center; margin-bottom: 20px;}
            .footer {text-align: center; margin-top: 20px;}
            .btn {display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;}
            .code {background-color: #007bff; color: #ffffff; font-size: 18px; padding: 10px; text-align: center; border-radius: 5px; margin-bottom: 20px;}
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Código Temporal - Simpligestion</h1>
            </div>
            <p>Hola, {$respuesta}, aquí está tu código temporal para acceder a Simpligestion:</p>
            <div class='code'>{$codigoTemporal}</div>
            <p>Por favor, no compartas este código con nadie. Es válido por un tiempo limitado.</p>
            <div class='footer'>
                <p>🖥 Simpligestion - Gestión Simplificada</p>
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
echo json_encode('Correo enviado con éxito.');
