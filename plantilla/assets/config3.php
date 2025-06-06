<?php
// Datos de conexión Oracle
$host = "34.44.141.62";
$port = "1521";
$service_name = "xepdb1";  // o SID si aplica
$username = "SYSTEM";
$password = "12345";

// Conexión usando PDO_OCI
$tns = "(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = $host)(PORT = $port))
    (CONNECT_DATA = (SERVICE_NAME = $service_name))
)";

try {
    $conn = new PDO("oci:dbname=" . $tns, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión a Oracle exitosa";
} catch (PDOException $e) {
    echo "Error al conectar a Oracle: " . $e->getMessage();
}
?>
