<?php


session_start();
include "auth/logincheck.php";
include "assets/config.php";
// include "auth/logincheck.php";

$moduloid = "2";
$modulo = "Administracion";
$vistaid = "3";
$vista = "CambiarContra";

$iduser = $_SESSION['pi']['act_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Usuarios</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://ppi.miclickderecho.com/plantilla/assets/css/styles.css">

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <style>
        body {
            background-color: #0b111e;
            color: white;
            margin: 0;
            padding: 0;
            font-family: 'Quicksand', sans-serif;
        }

        .page-content {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .card {
            background-color: #0f172a;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }


        .field-wrapper {
            position: relative;
        }

        .field-wrapper svg.feather-lock {
            position: absolute;
            top: 12px;
            left: 10px;
            color: #9ca3af;
        }

        .field-wrapper svg.feather-eye {
            position: absolute;
            top: 12px;
            right: 10px;
            cursor: pointer;
            color: #9ca3af;
        }

        .form-control {
            padding-left: 40px;
            background-color: #1e2a44;
            color: white;
            border: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }
    </style>

</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <input type="hidden" id="idUsuario" value="<?php echo $iduser; ?>">
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="card rounded shadow">
            <h4 class="mb-4">Cambiar Contraseña</h4>

            <!-- Campo de contraseña -->
            <div class="field-wrapper mb-3 position-relative">
                <input id="password" name="password" type="password" class="form-control"
                    placeholder="Contraseña nueva">
                <svg id="toggle-password" class="feather feather-eye position-absolute"
                    style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>

            <!-- Campo de confirmar contraseña -->
            <div class="field-wrapper mb-3 position-relative">
                <input id="confirm-password" name="confirm-password" type="password" class="form-control"
                    placeholder="Confirmar contraseña">
                <svg id="toggle-confirm-password" class="feather feather-eye position-absolute"
                    style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>

            <div class="text-center">
                <button class="btn btn-primary" onclick="validatePasswords()">Guardar</button>
                <div id="error-message" class="mt-2 text-danger"></div>
            </div>
        </div>
    </div>

    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="plugins/highlight/highlight.pack.js"></script>
    <script src="js/usuarios.js"></script>
    <script>
        document.getElementById("toggle-password").addEventListener("click", function () {
            const passwordField = document.getElementById("password");
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
        });

        document.getElementById("toggle-confirm-password").addEventListener("click", function () {
            const confirmPasswordField = document.getElementById("confirm-password");
            const type = confirmPasswordField.getAttribute("type") === "password" ? "text" : "password";
            confirmPasswordField.setAttribute("type", type);
        });

        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm-password').value;
            const error = document.getElementById('error-message');

            // Validación de fortaleza de contraseña
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            if (!passwordRegex.test(password)) {
                error.textContent = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
                return;
            }

            if (password !== confirm) {
                error.textContent = "Las contraseñas no coinciden.";
                return;
            }

            error.textContent = "";

            let idUsuario = document.getElementById('idUsuario').value;
            let nuevaContrasena = password;

            let ladata = new FormData();
            ladata.append('idUsuario', idUsuario);
            ladata.append('nuevaContrasena', nuevaContrasena);
            ladata.append('ind', '10'); // nuevo indicador

            fetch('backend/usuariosBackend.php', {
                method: 'POST',
                body: ladata,
            })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Error en la solicitud');
                    }
                })
                .then(data => {
                    if (data.rta === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Contraseña actualizada',
                            text: 'Tu contraseña ha sido cambiada exitosamente.',
                        }).then(() => {
                            window.history.back();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo actualizar la contraseña.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un problema al conectarse con el servidor.',
                    });
                });
        }
    </script>
</body>


</html>