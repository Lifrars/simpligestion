<?php
// Visualización de errores
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Datos de conexión Oracle como SYSDBA
if (!defined('DB_HOST')) define('DB_HOST', '34.44.141.62');
if (!defined('DB_PORT')) define('DB_PORT', '1521');
if (!defined('DB_SERVICE')) define('DB_SERVICE', 'xepdb1');
if (!defined('DB_USER')) define('DB_USER', 'SYS');
if (!defined('DB_PASS')) define('DB_PASS', '12345');
if (!defined('DB_SCHEMA')) define('DB_SCHEMA', 'US_PPI');

// Esquema (si trabajas sobre otro usuario)
if (!defined('DB_SCHEMA')) define('DB_SCHEMA', 'US_PPI');

// String de conexión TNS
$DSNoracle = "(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = " . DB_HOST . ")(PORT = " . DB_PORT . "))
    (CONNECT_DATA = (SERVICE_NAME = " . DB_SERVICE . "))
)";

// Conexión principal como SYSDBA
$datappi = @oci_connect(DB_USER, DB_PASS, $DSNoracle, null, OCI_SYSDBA);

if (!$datappi) {
    $e = oci_error();
    echo "<p style='font-family: system-ui;'>❌ Error en la conexión SYSDBA (datappi): <strong style='color:red'>" . htmlentities($e['message'], ENT_QUOTES) . "</strong></p><br>";
} else {
    // echo "<p style='font-family: system-ui;'>✅ Conexión Oracle SYSDBA (datappi) exitosa</p>";
}

// Segunda conexión (si se requiere otra instancia también como SYSDBA)
$datappi2 = @oci_connect(DB_USER, DB_PASS, $DSNoracle, null, OCI_SYSDBA);

if (!$datappi2) {
    $e = oci_error();
    echo "<p style='font-family: system-ui;'>❌ Error en la conexión SYSDBA (datappi2): <strong style='color:red'>" . htmlentities($e['message'], ENT_QUOTES) . "</strong></p><br>";
} else {
    // echo "<p style='font-family: system-ui;'>✅ Conexión Oracle SYSDBA (datappi2) exitosa</p>";
}
?>
