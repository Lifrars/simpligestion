<?php
session_start();
include "../assets/config2.php"; // aquí defines $con con oci_connect()

$mail = $_POST["mail"];
$pass = md5($_POST["pass"]);

// Escape simple manual en Oracle (sin inyección básica)
$mail_escape = strtoupper(trim($mail)); // Oracle suele almacenar correos en mayúsculas


// 🔍 Query SQL con normalización de correo
$query = "
SELECT DOCUMENTO_USUARIO, 
       NOMBRE_USUARIO || ' ' || APELLIDO_USUARIO || ' ' || APELLIDO2_USUARIO AS NOMBRE_COMPLETO,
       TELEFONO_USUARIO, CORREO_USUARIO, CONTRASENA_USUARIO, ESTADO_USUARIO,
       ULTIMA_SESION_USUARIO, SESSION_ACTIVA_USUARIO, ID_PERFIL_USUARIO, 
       CODIGO_TEMPORAL_USUARIO, ID_RESIDENCIA_USUARIO
FROM US_PPI.USUARIOS 
WHERE LOWER(TRIM(CORREO_USUARIO)) = :mail AND ESTADO_USUARIO = '1'
";

$stid = oci_parse($con, $query);
oci_bind_by_name($stid, ':mail', $mail);
oci_execute($stid);

$arrayData = [];
$found = false;

while ($row = oci_fetch_assoc($stid)) {
    $found = true;
    $arrayData[] = $row;

    if ($row['SESSION_ACTIVA_USUARIO'] == '1') {
        $datetime1 = date_create($row['ULTIMA_SESION_USUARIO']);
        $datetime2 = date_create(date('Y-m-d H:i:s'));
        $interval = date_diff($datetime1, $datetime2);

        if (
            $interval->format('%a') <= 0 &&
            $interval->format('%h') <= 0 &&
            $interval->format('%i') <= 30
        ) {
            echo json_encode(array('respuesta' => 'NO', 'err' => 'SESION', 'time' => $row['ULTIMA_SESION_USUARIO'], 'i' => $interval->format('%i'), 'h' => $interval->format('%h'), 'a' => $interval->format('%a')));
            die();
        } else {
            // Cerrar sesión
            $sql_update = "UPDATE US_PPI.USUARIOS SET SESSION_ACTIVA_USUARIO='0', ULTIMA_SESION_USUARIO=TO_DATE('1900-01-01 00:00:00', 'YYYY-MM-DD HH24:MI:SS') WHERE DOCUMENTO_USUARIO = :id";
            $stid_update = oci_parse($con, $sql_update);
            oci_bind_by_name($stid_update, ':id', $row['DOCUMENTO_USUARIO']);
            oci_execute($stid_update);
            oci_free_statement($stid_update);

            // Limpiar sesión
            unset($_SESSION['pi']);
        }
    }

    if ($pass == $row['CONTRASENA_USUARIO']) {
        $_SESSION['pi']['act_documento'] = $row['DOCUMENTO_USUARIO'];
        $_SESSION['pi']['act_nomre_completo'] = $row['NOMBRE_COMPLETO'];
        $_SESSION['pi']['act_telefono'] = $row['TELEFONO_USUARIO'];
        $_SESSION['pi']['act_correo'] = $row['CORREO_USUARIO'];
        $_SESSION['pi']['act_contrasena'] = $row['CONTRASENA_USUARIO'];
        $_SESSION['pi']['act_estado'] = $row['ESTADO_USUARIO'];
        $_SESSION['pi']['act_ultima_sesion'] = $row['ULTIMA_SESION_USUARIO'];
        $_SESSION['pi']['act_session_activa'] = $row['SESSION_ACTIVA_USUARIO'];
        $_SESSION['pi']['act_idPerfil'] = $row['ID_PERFIL_USUARIO'];
        $_SESSION['pi']['act_codigoTemporal'] = $row['CODIGO_TEMPORAL_USUARIO'];
        $_SESSION['pi']['act_localhost'] = "75.102.22.98";
        $_SESSION['pi']['act_site_url'] = "https://ppi.miclickderecho.com/plantilla";
        $_SESSION['pi']['act_site'] = "SimpliGestion";
        $_SESSION['pi']['act_ultima_sesion'] = date('Y-m-d H:i:s');

        // actualizar sesión en Oracle
        $eldate = date(format: 'Y-m-d H:i:s');
        $eldoc = $row['DOCUMENTO_USUARIO'];
        $update_query = "UPDATE US_PPI.USUARIOS SET SESSION_ACTIVA_USUARIO='1', ULTIMA_SESION_USUARIO = TO_DATE(:fecha, 'YYYY-MM-DD HH24:MI:SS') WHERE DOCUMENTO_USUARIO = :id";
        $stid_update = oci_parse($con, $update_query);
        oci_bind_by_name($stid_update, ':fecha', $eldate);
        oci_bind_by_name($stid_update, ':id', $eldoc);
        oci_execute($stid_update);
        oci_free_statement($stid_update);
    } else {
        echo json_encode(array('respuesta' => 'NO', 'err' => 'PASS'));
        die();
    }
}
oci_free_statement($stid);

// No se usa mysqli_affected_rows en Oracle, se valida por bandera
if ($found) {
    echo json_encode(array('respuesta' => 'SI', 'datos' => $arrayData));
} else {
    echo json_encode(array('respuesta' => 'NO', 'err' => 'NOUSER'));
}
?>
