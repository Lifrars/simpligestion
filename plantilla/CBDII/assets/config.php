<?php
//Datos de conexion a la base de datos/
//define('DB_HOST', '10.31.66.6');
// !servidor
define('DB_HOST', 'localhost');
define('DB_USER', 'bhhurjmu_ppiuserbd');
define('DB_PASS', 'GX2tKgrZl5;k');
define('DB_NAME', 'bhhurjmu_ppidata'); //Nombre de la base de datos
// !bases de datos
define('DB_NAME2', 'bhhurjmu_ppidata'); //Nombre de la base de datos de imagenes

// !configuracion de dsn para las diferentes bases de datos
$DSNcredimas = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
// !creacion de instancias
// si tenemos un error en credimas bases de datos
try {
	$datappi = new PDO($DSNcredimas, DB_USER, DB_PASS);

} catch (PDOException $error) {
	echo "<p style= 'font-family: system-ui;'>Error en la conexion de bases de datos de pos <strong style='color : red;'> " . $error->getMessage() . " </strong> Revisa la configuración</p>";
	echo "<br>";
}
try{
	$datappi2 = new PDO($DSNcredimas, DB_USER, DB_PASS);

} catch (PDOException $error) {
	echo "<p style= 'font-family: system-ui;'>Error en la conexion de bases de datos de pos <strong style='color : red;'> " . $error->getMessage() . " </strong> Revisa la configuración</p>";
	echo "<br>";
}