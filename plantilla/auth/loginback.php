<?php
session_start();

include "../assets/config2.php";

$mail = $_POST["mail"];
$pass = md5($_POST["pass"]);

//consulto para validar usuario y contraseña
$mail_escape = mysqli_real_escape_string($con, $mail);
$query = "SELECT * from usuarios where correo='$mail_escape' AND estado='1';";

$result_task = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result_task)) {
    $arrayData[] = $row;
    if ($row['session_activa'] == '1') {

        $datetime1 = date_create($row['ultima_sesion']);
        $datetime2 = date_create(date('Y-m-d H:i:s'));

        $interval = date_diff($datetime1, $datetime2);

        if (
            $interval->format('%a') <= 0 &&
            $interval->format('%h') <= 0 &&
            $interval->format('%i') <= 30
        ) {
            echo json_encode(array('respuesta' => 'NO', 'err' => 'SESION', 'time' => $row['ultima_sesion'], 'i' => $interval->format('%i'), 'h' => $interval->format('%h'), 'a' => $interval->format('%a')));
            die();
        } else {
            //cerrar sesion aqui

            $con->query("UPDATE usuarios SET session_activa='0', ultima_sesion='1900-01-01 00:00:00' WHERE id=" . $row['id'] . ";");
            unset($_SESSION['pi']['session_username']);
            unset($_SESSION['pi']['act_id']);
            unset($_SESSION['pi']['act_documento']);
            unset($_SESSION['pi']['act_nomre_completo']);
            unset($_SESSION['pi']['act_telefono']);
            unset($_SESSION['pi']['act_correo']);
            unset($_SESSION['pi']['act_contrasena']);
            unset($_SESSION['pi']['act_estado']);
            unset($_SESSION['pi']['act_ultima_sesion']);
            unset($_SESSION['pi']['act_session_activa']);
            unset($_SESSION['pi']['act_idPerfil']);
            unset($_SESSION['pi']['act_codigoTemporal']);
            unset($_SESSION['pi']['act_localhost']);
            unset($_SESSION['pi']['act_site_url']);
            unset($_SESSION['pi']['act_site']);
        }
    }


    if ($pass == $row['contrasena']) {
        session_start();
        $_SESSION['pi']['act_id'] = $row['id'];
        $_SESSION['pi']['act_documento'] = $row['documento'];
        $_SESSION['pi']['act_nomre_completo'] = $row['nombre_completo'];
        $_SESSION['pi']['act_telefono'] = $row['telefono'];
        $_SESSION['pi']['act_correo'] = $row['correo'];
        $_SESSION['pi']['act_contrasena'] = $row['contrasena'];
        $_SESSION['pi']['act_estado'] = $row['estado'];
        $_SESSION['pi']['act_ultima_sesion'] = $row['ultima_sesion'];
        $_SESSION['pi']['act_session_activa'] = $row['session_activa'];
        $_SESSION['pi']['act_idPerfil'] = $row['idPerfil'];
        $_SESSION['pi']['act_codigoTemporal'] = $row['codigoTemporal'];
        $_SESSION['pi']['act_localhost'] = "75.102.22.98";
        $_SESSION['pi']['act_site_url'] = "https://ppi.miclickderecho.com/plantilla";
        $_SESSION['pi']['act_site'] = "SimpliGestion";


        $_SESSION['pi']['act_ultima_sesion'] = date('Y-m-d H:i:s');


        $eldate = date('Y-m-d H:i:s');
        $elid = $row['id'];
        $queryup = "UPDATE usuarios 
            SET session_activa = 1, ultima_sesion = '$eldate' WHERE id='$elid'";
        $result_taskup = mysqli_query($con, $queryup);
    } else {
        echo json_encode(array('respuesta' => 'NO', 'err' => 'PASS'));
        die();
    }
}

if (mysqli_affected_rows($con) >= 1) {
    echo json_encode(array('respuesta' => 'SI', 'datos' => $arrayData));
} else {
    echo json_encode(array('respuesta' => 'NO', 'err' => 'NOUSER'));
}
