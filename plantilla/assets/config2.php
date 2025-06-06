<?php
// Definir constantes para Oracle
if (!defined('DB_HOST')) define('DB_HOST', '34.44.141.62');
if (!defined('DB_PORT')) define('DB_PORT', '1521');
if (!defined('DB_SERVICE')) define('DB_SERVICE', 'xepdb1');
if (!defined('DB_USER')) define('DB_USER', 'SYS');
if (!defined('DB_PASS')) define('DB_PASS', '12345');
if (!defined('DB_SCHEMA')) define('DB_SCHEMA', 'US_PPI');

// Cadena de conexión (DSN)
$dsn = "(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = " . DB_HOST . ")(PORT = " . DB_PORT . "))
    (CONNECT_DATA = (SERVICE_NAME = " . DB_SERVICE . "))
)";

// Conectar como SYSDBA
$con = @oci_connect(DB_USER, DB_PASS, $dsn, null, OCI_SYSDBA);

// Validar conexión
if (!$con) {
    $e = oci_error();
    die("<h2 style='text-align:center;color:red;'>Error de conexión a Oracle: " . htmlentities($e['message'], ENT_QUOTES) . "</h2>");
} else {
    // echo "<h2 style='text-align:center;color:green;'>Conexión exitosa a Oracle</h2>";
}
?>
