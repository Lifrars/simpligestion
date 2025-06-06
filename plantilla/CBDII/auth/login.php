<?php
//session_destroy();
session_start();
//validamos si tiene sesion en el servidor
$ultima_sesion = $_SESSION['act']["act_sesion_date"];
if ($ultima_sesion != '') {
	$datetime1 = date_create($ultima_sesion);
	$datetime2 = date_create(date('Y-m-d H:i:s'));
	$interval = date_diff($datetime1, $datetime2);
	if ($interval->format('%i') >= 35 || $interval->format('%h') >= 0 || $interval->format('%a') >= 0) {
		$activo_reciente = false;
		//echo("old sesion". $ultima_sesion . '||' . date('Y-m-d H:i:s') . '//' . $interval->format('%i') .'--'. $interval->format('%h') ."--". $interval->format('%a'));
		//session_destroy();
		unset($_SESSION['act']['session_username']);
		unset($_SESSION['act']['act_id']);
		unset($_SESSION['act']['act_documento']);
		unset($_SESSION['act']['act_nomre_completo']);
		unset($_SESSION['act']['act_telefono']);
		unset($_SESSION['act']['act_correo']);
		unset($_SESSION['act']['act_contrasena']);
		unset($_SESSION['act']['act_estado']);
		unset($_SESSION['act']['act_ultima_sesion']);
		unset($_SESSION['act']['act_session_activa']);
		unset($_SESSION['act']['act_idPerfil']);
		unset($_SESSION['act']['act_codigoTemporal']);
		unset($_SESSION['act']['act_localhost']);
		unset($_SESSION['act']['act_site_url']);
		unset($_SESSION['act']['act_site']);
	} else {
		$activo_reciente = true;
		//echo("activo" . $ultima_sesion . '||' . date('Y-m-d H:i:s') . '//' . $interval->format('%i') .'--'. $interval->format('%h') ."--". $interval->format('%a'));
	}
} else {
	$activo_reciente = false;
	//session_destroy();
	unset($_SESSION['act']['session_username']);
	unset($_SESSION['act']['act_id']);
	unset($_SESSION['act']['act_documento']);
	unset($_SESSION['act']['act_nomre_completo']);
	unset($_SESSION['act']['act_telefono']);
	unset($_SESSION['act']['act_correo']);
	unset($_SESSION['act']['act_contrasena']);
	unset($_SESSION['act']['act_estado']);
	unset($_SESSION['act']['act_ultima_sesion']);
	unset($_SESSION['act']['act_session_activa']);
	unset($_SESSION['act']['act_idPerfil']);
	unset($_SESSION['act']['act_codigoTemporal']);
	unset($_SESSION['act']['act_localhost']);
	unset($_SESSION['act']['act_site_url']);
	unset($_SESSION['act']['act_site']);
	//echo("no sesion");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
	<title>CRM SimpliGestion</title>
	<link rel="icon" type="image/x-icon" href="../img/logo1.jpg" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />

	<script src="../plugins/sweetalerts/promise-polyfill.js"></script>
	<link href="../plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
	<link href="../plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->

	<link rel="stylesheet" type="text/css" href="../assets/css/forms/theme-checkbox-radio.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/forms/switches.css">

	<!-- Esto es PWA -->


	<meta name="description" content="A Solution for Beauty - CRM">
	<meta name="theme-color" content="#8D23FF">

	<!-- mobile catacteristica -->
	<meta name="MobileOptimized" content="width">
	<meta name="HandheldFriendly" content="true">

	<!-- ios instalacion -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="#8D23FF">
	<meta name="apple-mobile-web-app-title" content="A Solution for Beauty - CRM">

	<!-- windows aspecto -->
	<meta name="msapplication-TileImage" content="assets/icons/Assets.xcassets/16.png">
	<meta name="msapplication-TileColor" content="#8D23FF">

	<!-- ios aspecto -->
	<link rel="apple-touch-icon" href="assets/icons/playstore.png">
	<link rel="apple-touch-startup-image" href="assets/icons/playstore.png">

	<!-- standard aspecto -->
	<link rel="shortcut icon" href="assets/icons/playstore.png" type="image/x-icon">
	<link rel="mask-icon" href="assets/icons/playstore.png" color="#8D23FF">

	<!-- iconos soportados generar iconos con esta webb app https://www.appicon.co/    -->
	<link rel="icon" type="image/png" href="assets/icons/playstore.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/icons/Assets.xcassets/AppIcon.appiconset/16.png">
	<link rel="icon" type="image/png" sizes="20x20" href="assets/icons/Assets.xcassets/AppIcon.appiconset/20.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/icons/Assets.xcassets/AppIcon.appiconset/32.png">
	<link rel="icon" type="image/png" sizes="48x48" href="assets/icons/Assets.xcassets/AppIcon.appiconset/48.png">
	<link rel="icon" type="image/png" sizes="64x64" href="assets/icons/Assets.xcassets/AppIcon.appiconset/64.png">
	<link rel="icon" type="image/png" sizes="70x70" href="assets/icons/Assets.xcassets/AppIcon.appiconset/70.png">
	<link rel="icon" type="image/png" sizes="72x72" href="assets/icons/Assets.xcassets/AppIcon.appiconset/72.png">
	<link rel="icon" type="image/png" sizes="76x76" href="assets/icons/Assets.xcassets/AppIcon.appiconset/76.png">
	<link rel="icon" type="image/png" sizes="80x80" href="assets/icons/Assets.xcassets/AppIcon.appiconset/80.png">
	<link rel="icon" type="image/png" sizes="92x92" href="assets/icons/Assets.xcassets/AppIcon.appiconset/92.png">
	<link rel="icon" type="image/png" sizes="100x100" href="assets/icons/Assets.xcassets/AppIcon.appiconset/100.png">
	<link rel="icon" type="image/png" sizes="120x120" href="assets/icons/Assets.xcassets/AppIcon.appiconset/120.png">
	<link rel="icon" type="image/png" sizes="128x128" href="assets/icons/Assets.xcassets/AppIcon.appiconset/128.png">
	<link rel="icon" type="image/png" sizes="172x172" href="assets/icons/Assets.xcassets/AppIcon.appiconset/172.png">
	<link rel="icon" type="image/png" sizes="180x180" href="assets/icons/Assets.xcassets/AppIcon.appiconset/180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="assets/icons/Assets.xcassets/AppIcon.appiconset/192.png">
	<link rel="icon" type="image/png" sizes="196x196" href="assets/icons/Assets.xcassets/AppIcon.appiconset/196.png">
	<link rel="icon" type="image/png" sizes="216x216" href="assets/icons/Assets.xcassets/AppIcon.appiconset/216.png">
	<link rel="icon" type="image/png" sizes="256x256" href="assets/icons/Assets.xcassets/AppIcon.appiconset/256.png">
	<link rel="icon" type="image/png" sizes="512x512" href="assets/icons/Assets.xcassets/AppIcon.appiconset/512.png">
	<link rel="icon" type="image/png" sizes="1024x1024" href="assets/icons/Assets.xcassets/AppIcon.appiconset/1024.png">
</head>

<body class="form">


	<div class="form-container outer">
		<div class="form-form">
			<div class="form-form-wrap">
				<div class="form-container">
					<div class="form-content">
						<img alt="logo" src="../img/logo1.jpg" width="200px">


						<form class="text-left" id="loginform">
							<p class="center">Register the access data...</p>
							<div class="form">
								<div id="username-field" class="field-wrapper input">
									<label for="username">User</label>
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
										<circle cx="12" cy="7" r="4"></circle>
									</svg>
									<input id="username" name="username" type="text" class="form-control" placeholder="Username">
								</div>
								<div id="password-field" class="field-wrapper input mb-2">
									<div class="d-flex justify-content-between">
										<label for="password">Password</label>
										<a href="#" id="forgotlink" class="forgot-pass-link">No Access?</a>
									</div>
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
										<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
										<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
									</svg>
									<input id="password" name="password" type="password" class="form-control" placeholder="Password">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye">
										<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
										<circle cx="12" cy="12" r="3"></circle>
									</svg>
								</div>
								<div class="d-sm-flex justify-content-between">
									<div class="field-wrapper">
										<button id="btnLogin" type="button" class="btn btn-primary" value="">Enter</button>
									</div>
								</div>
							</div>
						</form>

						<form class="text-left" id="loginforgot" style="display:none">
							<div class="form">
								<div id="username-field" class="field-wrapper input">
									<label for="emailu">User</label>
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
										<circle cx="12" cy="7" r="4"></circle>
									</svg>
									<input id="emailu" name="emailu" type="text" class="form-control" placeholder="email@dominio.com">
								</div>

								<div class="d-sm-flex justify-content-between">
									<div class="field-wrapper">
										<button id="btnForgot" class="btn btn-primary" value="">Recover Access</button>
									</div>
								</div>
							</div>
						</form>

						<div class="text-center"><img id="imgok" src="../imgs/gloading.gif" style="display:none;" width="150px"></div>

						<h2 id="lblloading" style="display:none;color:black; text-align:center">Requesting Access...</h2>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
	<script src="../assets/js/libs/jquery-3.1.1.min.js"></script>
	<script src="../bootstrap/js/popper.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>


	<script src="../plugins/sweetalerts/sweetalert2.min.js"></script>


	<!-- END GLOBAL MANDATORY SCRIPTS -->
	<script src="../assets/js/authentication/form-2.js"></script>
	<script>
		$("#btnLogin").click(function() {
			var sesionid = '<?php echo (session_id()) ?>';
			$("#loginform").hide();
			$("#imgok").attr("src", "../img/carga.gif")
			$("#imgok").show();
			$("#lblloading").show();

			if ($("#user").val() == "" || $("#password").val() == "") {
				Swal.fire({
					position: 'top-end',
					type: 'info',
					title: 'Despacio!!!',
					text: "Debes escribir los datos para solicitar acceso",
					showConfirmButton: false,
					timer: 2500
				})
				$("#loginform").show();
				$("#lblloading").hide();
				$("#imgok").hide();
				system.exit(0);
			}

			setTimeout(() => {
				var testing = $("#testing").val();
				$.ajax({
						url: "loginback.php",
						type: "POST",
						dataType: "json",
						data: ({
							mail: $("#username").val(),
							pass: $("#password").val()
						}),
						beforeSend: function() {
							console.log("enviando datos para leer usertbl");
						}
					})
					.done(function(respuesta) {
						console.log("done:" + respuesta.respuesta + respuesta);
						if (respuesta.respuesta == 'SI') {
							$("#imgok").show();
							$('#trcc').hide();
							$('#loader').hide();
							$("#lblloading").text("Welcome...");
							// $("#imgok").attr("src","../imgs/ok.png")
							// $("#imgok").show();
							$("#lblloading").show();

							setTimeout(() => {
								window.location.replace("../index.php");
							}, 2000);
						}

						if (respuesta.respuesta == 'NO') {


							$('#trcc').hide();
							$('#loader').hide();
							$("#loginform").show();
							$("#lblloading").hide();
							if (respuesta.err == 'PASS') {
								Swal.fire({
									position: 'top-end',
									type: 'info',
									title: 'Despacio!!!',
									text: "No se pudo validar la contraseña, revise los datos e intente de nuevo",
									showConfirmButton: false,
									timer: 3000
								})
							}
							if (respuesta.err == 'NOUSER') {
								Swal.fire({
									position: 'top-end',
									type: 'warning',
									title: 'Acceso Denegado!!!',
									text: "El usuario escrito no existe, revise los datos",
									showConfirmButton: false,
									timer: 3000
								})
							}
							if (respuesta.err == 'SESION') {
								Swal.fire({
									position: 'top-end',
									type: 'warning',
									title: 'Acceso Denegado!!!',
									text: "El usuario ya tiene una sesión abierta recientemente, espere por el cierre automático de la sesión ó solicite a un administrador el cierre manual.",
									showConfirmButton: false,
									timer: 5000
								})
							}


							$("#imgok").hide();
							$("#lblloading").hide();

						}

					})
					.fail(function(resp) {
						console.log("failllll:" + resp.responseText);

					})
					.always(function(resp) {
						console.log("alwayssssssss:" + resp.responseText);
					})
			}, 2500);
		})



		$("#password").on('keypress', function(e) {
			if (e.which == 13) {
				$("#btnLogin").click()
			}
		});



		$("#forgotlink").click(function() {
			$("#loginform").hide();

			$("#loginforgot").show();

		})

		$("#btnForgot").click(function() {

			if ($("#emailu").val() == '') {
				Swal.fire({
					position: 'top-end',
					type: 'info',
					title: 'Escribe tu Email.',
					showConfirmButton: false,
					timer: 4000
				})
				system.exit(0);
			}

			$.ajax({
					url: "emailforgot.php",
					type: "POST",
					dataType: "json",
					data: ({
						emailu: $("#emailu").val()
					}),
					beforeSend: function() {
						$("#emailu").attr('disabled', true)
						$("#btnForgot").attr('disabled', true);
						$("#btnForgot").text('Un momento ...');
					}
				})
				.done(function(respuesta) {

					if (respuesta.respuesta == "ok") {
						Swal.fire({
							position: 'top-end',
							type: 'info',
							title: respuesta.nombre + ' Bien Hecho!',
							text: "Revisa tu Email, ya te enviamos el nuevo código de acceso.",
							showConfirmButton: false,
							timer: 7000
						})

						$("#emailu").attr('disabled', false);
						$("#emailu").val('');

						$("#password").val('');
						$("#btnForgot").text('Recuperar Acceso');
						$("#loginform").show();

						$("#loginforgot").hide();

						$("#btnForgot").attr('disabled', false);
					}
				})
				.fail(function(resp) {

				})
				.always(function(resp) {
					if (resp.respuesta == "no") {
						Swal.fire({
							position: 'top-end',
							type: 'info',
							title: ' Oppppps!',
							text: "Revisa el email que ingresaste.",
							showConfirmButton: false,
							timer: 7000
						})
					}
					$("#emailu").attr('disabled', false);
					$("#emailu").val('');

					$("#password").val('');
					$("#btnForgot").text('Recuperar Acceso');
					$("#loginform").show();

					$("#loginforgot").hide();

					$("#btnForgot").attr('disabled', false);

				})


		})
	</script>
</body>

</html>