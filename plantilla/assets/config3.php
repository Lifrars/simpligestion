<?php
$host = "34.44.141.62";
$port = "1521";
$service_name = "xepdb1";
$username = "SYS";
$password = "12345";

$connection_string = "(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = $host)(PORT = $port))
    (CONNECT_DATA = (SERVICE_NAME = $service_name))
)";

// Conexión como SYSDBA
$conn = oci_connect($username, $password, $connection_string, null, OCI_SYSDBA);

if (!$conn) {
    $e = oci_error();
    echo "❌ Error: " . htmlentities($e['message'], ENT_QUOTES);
} else {
    // echo "✅ Conexión exitosa como SYSDBA a Oracle";
}
?>
