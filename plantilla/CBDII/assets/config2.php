<?php
	/*Datos de conexion a la base de datos*/
	//define('DB_HOST', '10.31.66.4');
	define('DB_HOST', 'localhost');
	define('DB_USER', 'bhhurjmu_ppiuserbd');
	define('DB_PASS', 'GX2tKgrZl5;k');
	define('DB_NAME', 'bhhurjmu_ppidata'); //Nombre de la base de datos
	$con=@mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	mysqli_set_charset($con, "utf8"); //formato de datos utf8
	if(!$con){
		echo "<h2 style='text-align:center'>Imposible conectarse a la base de datos! </h2>";
    }else{
		// echo "<h2 style='text-align:center'>Conexión exitosa!!</h2>";
	}
?>