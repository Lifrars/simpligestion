<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include "assets/config2.php"; // asegúrate de que $con esté definido como conexión OCI

$ultima_sesion = $_SESSION['pi']["act_ultima_sesion"] ?? '';

if ($ultima_sesion !== '') {
    $documento = $_SESSION['pi']["act_documento"] ?? '';

    // Verificamos si sigue activa la sesión desde base de datos
    $query = "SELECT SESSION_ACTIVA_USUARIO FROM US_PPI.USUARIOS WHERE DOCUMENTO_USUARIO = :documento";
    $stid = oci_parse($con, $query);
    oci_bind_by_name($stid, ':documento', $documento);
    oci_execute($stid);

    $idactivo_estado = null;
    $activo_reciente = false;

    if ($row = oci_fetch_assoc($stid)) {
        $idactivo_estado = $row['SESSION_ACTIVA_USUARIO'];
        $activo_reciente = true;
    }
    oci_free_statement($stid);

    if ($idactivo_estado === "0") {
        $activo_reciente = false;
        header("location:https://ppi.miclickderecho.com/plantilla/auth/logout.php");
        die();
    }

    // Verificamos el tiempo de sesión
    $datetime1 = date_create($ultima_sesion);
    $datetime2 = date_create(date('Y-m-d H:i:s'));

    $interval = date_diff($datetime1, $datetime2);

    if ($interval->format('%i') >= 32 || $interval->format('%h') > 0 || $interval->format('%a') > 0) {
        $activo_reciente = false;
        session_destroy();
        echo "<script>alert('Su sesión ha expirado, por favor vuelva a iniciar sesión');</script>";
        header("location:https://ppi.miclickderecho.com/plantilla/auth/logout.php");
        die();
    } else {
        $activo_reciente = true;
        $_SESSION['pi']["act_ultima_sesion"] = date('Y-m-d H:i:s');

        // También puedes actualizar la base de datos si lo deseas:
        /*
        $update = "UPDATE US_PPI.USUARIOS SET ULTIMA_SESION_USUARIO = TO_DATE(:fecha, 'YYYY-MM-DD HH24:MI:SS') WHERE DOCUMENTO_USUARIO = :documento";
        $stmt_update = oci_parse($con, $update);
        $fecha_actual = date('Y-m-d H:i:s');
        oci_bind_by_name($stmt_update, ':fecha', $fecha_actual);
        oci_bind_by_name($stmt_update, ':documento', $documento);
        oci_execute($stmt_update);
        oci_free_statement($stmt_update);
        */
    }
}

if ($ultima_sesion === '') {
    $activo_reciente = false;
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
    header("location:auth/login.php");
    die();
}
?>
