<?php

include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");

if ($indicador == "9") {
    error_log("Indicador 9 activado");
    $correo = $_POST['correo'] ?? $_GET['correo'] ?? "";
    
    if (empty($correo)) {
        echo json_encode(['rta' => 'correo_requerido']);
        exit;
    }

    // Verificar que el correo exista
    $verificaCorreo = "SELECT nombre_completo FROM usuarios WHERE correo = :correo";
    $stmt = $datappi->prepare($verificaCorreo);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(['rta' => 'correo_no_encontrado']);
        exit;
    }

    // Generar código temporal
    $randomCode = mt_rand(100000, 999999);
    $hashedCode = md5($randomCode);

    // Actualizar código temporal
    $updateCodigo = "UPDATE usuarios SET codigoTemporal = :codigoTemporal, contrasena = :contrasena WHERE correo = :correo";
    $upd = $datappi->prepare($updateCodigo);
    $upd->bindParam(':codigoTemporal', $hashedCode);
    $upd->bindParam(':contrasena', $hashedCode);
    $upd->bindParam(':correo', $correo);

    if ($upd->execute()) {
        // Enviar correo
        $url = 'https://ppi.miclickderecho.com/plantilla/email/mailing.php';
        $data = http_build_query([
            'respuesta' => $usuario['nombre_completo'],
            'correos' => $correo,
            'codigoTemporal' => $randomCode
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($ch);
        curl_close($ch);

        echo json_encode([
            'rta' => 'ok',
            'respuestaCorreo' => $respuesta
        ]);
    } else {
        echo json_encode(['rta' => 'error_actualizando']);
    }
}
