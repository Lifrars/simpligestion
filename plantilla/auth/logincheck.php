<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
include "assets/config2.php";
$ultima_sesion = $_SESSION['pi']["act_ultima_sesion"];

if ($ultima_sesion != '') {
	$idactivo = $_SESSION['pi']["act_id"] ?? '';

	$query = "SELECT session_activa FROM usuarios WHERE id = '$idactivo'";

	$result_task = mysqli_query($con, $query);


	while ($row = mysqli_fetch_assoc($result_task)) {
		$idactivo_estado = $row['session_activa'];
		$activo_reciente = true;
	}
	if ($idactivo_estado == "0") {
		$activo_reciente = false;
		header("location:https://ppi.miclickderecho.com/plantilla/auth/logout.php");
		die();
	}



	$datetime1 = date_create($ultima_sesion);

	$datetime2 = date_create(date('Y-m-d H:i:s', time()));


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
		//$con->query('UPDATE usuarios SET ultima_sesion = "'.date('Y-m-d H:i:s').'" WHERE id='.$_SESSION['pi']['act_id']);
	}
}

if ($ultima_sesion == '') {
	$activo_reciente = false;
	//session_destroy();
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
	header("location:auth/login.php");
	die();
}
