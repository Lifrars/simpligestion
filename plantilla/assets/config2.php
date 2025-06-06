<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'bhhurjmu_ppiuserbd');
define('DB_PASS', 'GX2tKgrZl5;k');
define('DB_NAME', 'bhhurjmu_ppidata');

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_set_charset($con, "utf8");

if (!$con) {
	die("<h2 style='text-align:center;color:red;'>Error de conexión: " . mysqli_connect_error() . "</h2>");
} else {
	// echo "<h2 style='text-align:center;color:green;'>Conexión exitosa</h2>";
}
