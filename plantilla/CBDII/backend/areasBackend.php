<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");


//Leer los datos
if ($indicador == "1") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query = "SELECT * FROM Areas_Comunes WHERE area_id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo 'Error al obtener datos del producto: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $productData));
}

//Mostrar los datos del usuario
if ($indicador == "2") {
    try {
        $nuevoinicio = isset($_POST['nuevoinicio']) ? intval($_POST['nuevoinicio']) : 0;
        $nroreg = isset($_POST['nroreg']) ? intval($_POST['nroreg']) : 0;

        // Conteo de productos para la sucursal específica
        $query = "SELECT COUNT(Areas_Comunes.area_id) AS count FROM Areas_Comunes ;";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta de productos para la sucursal específica
        $query2 = "SELECT * FROM Areas_Comunes  ORDER BY Areas_Comunes.area_id DESC LIMIT :nuevoinicio, :nroreg";
        $qry2 = $datappi->prepare($query2);
        $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
        $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
        $qry2->execute();
        $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);

        $response = array('rta' => $count, 'rta2' => $rta2);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(["rta" => $e->getMessage()]);
        exit;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

//Insertar usuarios
if ($indicador == "3") {
    //$pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
    $documento = isset($_POST['documento']) ? $_POST['documento'] : (!empty($_GET['documento']) ? $_GET['documento'] : "");
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : (!empty($_GET['telefono']) ? $_GET['telefono'] : "");
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : (!empty($_GET['nombre']) ? $_GET['nombre'] : "");
    $correo = isset($_POST['correo']) ? $_POST['correo'] : (!empty($_GET['correo']) ? $_GET['correo'] : "");
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : (!empty($_GET['perfil']) ? $_GET['perfil'] : "");
    $estado = 1;

    // Generar un número aleatorio de 6 dígitos
    $randomCode = mt_rand(100000, 999999);

    // Convertir el código temporal a MD5
    $hashedCode = md5($randomCode);

    $query = "INSERT INTO usuarios (documento, nombre_completo, telefono, correo, contrasena, estado, idPerfil, codigoTemporal) VALUES (:documento, :nombre, :telefono, :correo, :contrasena, :estado, :idPerfil, :codigoTemporal)";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':documento', $documento);
    $qry->bindParam(':nombre', $nombre);
    $qry->bindParam(':telefono', $telefono);
    $qry->bindParam(':correo', $correo);
    $qry->bindParam(':contrasena', $hashedCode); // Insertar el código temporal cifrado
    $qry->bindParam(':estado', $estado);
    $qry->bindParam(':idPerfil', $perfil);
    $qry->bindParam(":codigoTemporal", $hashedCode); // Insertar el código temporal cifrado

    if ($qry->execute()) {
        $rta = "ok";

        // Preparar los datos para enviar por correo, incluyendo el código temporal sin cifrar
        $url = 'https://ppi.miclickderecho.com/plantilla/email/mailing.php';
        $data = http_build_query(array('respuesta' => $nombre, 'correos' => $correo, 'codigoTemporal' => $randomCode)); // Usar el código temporal sin cifrar

        // Inicializar cURL
        $ch = curl_init();

        // Configurar opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud POST
        $respuesta = curl_exec($ch);

        // Cerrar cURL
        curl_close($ch);

        // Agregar la respuesta de la solicitud de correo y el código temporal sin cifrar al JSON
        echo json_encode(array('rta' => $rta, 'codigoTemporal' => $randomCode, 'respuestaCorreo' => $respuesta));
    } else {
        $rta = "error";
        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    }
}

// Actualizar áreas comunes
if ($indicador == "4") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : (!empty($_GET['nombre']) ? $_GET['nombre'] : "");
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
    $capacidad = isset($_POST['capacidad']) ? $_POST['capacidad'] : (!empty($_GET['capacidad']) ? $_GET['capacidad'] : "");
    $horario = isset($_POST['horario']) ? $_POST['horario'] : (!empty($_GET['horario']) ? $_GET['horario'] : "");
    $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : (!empty($_GET['ubicacion']) ? $_GET['ubicacion'] : "");

    $query = "UPDATE Areas_Comunes SET nombre = :nombre, descripcion = :descripcion, capacidad = :capacidad, horario = :horario, ubicacion = :ubicacion WHERE area_id = :id";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':nombre', $nombre);
    $qry->bindParam(':descripcion', $descripcion);
    $qry->bindParam(':capacidad', $capacidad);
    $qry->bindParam(':horario', $horario);
    $qry->bindParam(':ubicacion', $ubicacion);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = "ok";
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta, 'status' => 'ok'));
}

// Insertar áreas comunes
if ($indicador == "5") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : (!empty($_GET['nombre']) ? $_GET['nombre'] : "");
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
    $capacidad = isset($_POST['capacidad']) ? $_POST['capacidad'] : (!empty($_GET['capacidad']) ? $_GET['capacidad'] : "");
    $horario = isset($_POST['horario']) ? $_POST['horario'] : (!empty($_GET['horario']) ? $_GET['horario'] : "");
    $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : (!empty($_GET['ubicacion']) ? $_GET['ubicacion'] : "");

    $query = "INSERT INTO Areas_Comunes (nombre, descripcion, capacidad, horario, ubicacion,estado) VALUES (:nombre, :descripcion, :capacidad, :horario, :ubicacion ,0)";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':nombre', $nombre);
    $qry->bindParam(':descripcion', $descripcion);
    $qry->bindParam(':capacidad', $capacidad);
    $qry->bindParam(':horario', $horario);
    $qry->bindParam(':ubicacion', $ubicacion);

    if ($qry->execute()) {
        $rta = "ok";
        // Obtener el ID de la nueva fila insertada
        $id = $datappi->lastInsertId();
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta, 'id' => $id));

}



//Mostrar datos del usuario
if ($indicador == "6") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "SELECT * FROM Areas_Comunes
    WHERE Areas_Comunes.area_id = :id";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = $qry->fetch(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Eliminar usuarios
if ($indicador == "7") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "UPDATE Areas_Comunes SET estado = 1 WHERE area_id = :id";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = "ok";
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}
//Activar area
if ($indicador == "8") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "UPDATE Areas_Comunes SET estado = 0 WHERE area_id = :id";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = "ok";
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}
