<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");


// Leer los datos
if ($indicador == "1") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del ticket
        $query = "SELECT * FROM quejas WHERE id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $ticketData = $qry->fetchAll(PDO::FETCH_OBJ);

        // Verificar si se encontraron datos
        if ($ticketData) {
            $response = array('rta' => $ticketData);
        } else {
            // Si no se encontraron datos, devolver un mensaje adecuado
            $response = array('rta' => null, 'message' => 'No se encontraron datos para el ID proporcionado');
        }
    } catch (PDOException $e) {
        // Manejo de errores
        $response = array('error' => 'Error al obtener datos del ticket: ' . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

// Mostrar los datos del usuario
if ($indicador == "2") {
    try {
        $nuevoinicio = isset($_POST['nuevoinicio']) ? intval($_POST['nuevoinicio']) : 0;
        $nroreg = isset($_POST['nroreg']) ? intval($_POST['nroreg']) : 0;
        $idPerfil = isset($_POST['idPerfil']) ? $_POST['idPerfil'] : (!empty($_GET['idPerfil']) ? $_GET['idPerfil'] : "");
        $idUsuario = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : (!empty($_GET['idUsuario']) ? $_GET['idUsuario'] : "");

        if ($idPerfil == "1") {
            // Conteo de todas las quejas
            $query = "SELECT COUNT(quejas.id) AS count FROM quejas;";
            $qry = $datappi->prepare($query);
            $qry->execute();
            $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

            // Consulta de todas las quejas con JOIN para obtener el nombre del usuario
            $query2 = "SELECT quejas.*, usuarios.nombre_completo 
                       FROM quejas 
                       INNER JOIN usuarios ON quejas.idUser = usuarios.id 
                       ORDER BY quejas.id DESC 
                       LIMIT :nuevoinicio, :nroreg";
            $qry2 = $datappi->prepare($query2);
            $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
            $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
            $qry2->execute();
            $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);

            $response = array('rta' => $count, 'rta2' => $rta2);
        } else {
            // Conteo de quejas para el usuario específico
            $query = "SELECT COUNT(quejas.id) AS count FROM quejas WHERE quejas.idUser = :idUsuario;";
            $qry = $datappi->prepare($query);
            $qry->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $qry->execute();
            $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

            // Consulta de quejas para el usuario específico con JOIN para obtener el nombre del usuario
            $query2 = "SELECT quejas.*, usuarios.nombre_completo 
                       FROM quejas 
                       INNER JOIN usuarios ON quejas.idUser = usuarios.id 
                       WHERE quejas.idUser = :idUsuario 
                       ORDER BY quejas.id DESC 
                       LIMIT :nuevoinicio, :nroreg";
            $qry2 = $datappi->prepare($query2);
            $qry2->bindParam(":idUsuario", $idUsuario, PDO::PARAM_INT);
            $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
            $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
            $qry2->execute();
            $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);

            $response = array('rta' => $count, 'rta2' => $rta2);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(["rta" => $e->getMessage()]);
        exit;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}


// Insertar tickets
if ($indicador == "3") {
    $idUser = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : (!empty($_GET['idUsuario']) ? $_GET['idUsuario'] : "");
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
    $fecha_creacion = isset($_POST['fecha_creacion']) ? $_POST['fecha_creacion'] : (!empty($_GET['fecha_creacion']) ? $_GET['fecha_creacion'] : "");
    $estado = isset($_POST['estado']) ? $_POST['estado'] : (!empty($_GET['estado']) ? $_GET['estado'] : "");

    $query = "INSERT INTO quejas (idUser, descripcion, fecha_creacion, estado) VALUES (:idUser, :descripcion, :fecha_creacion, :estado)";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':idUser', $idUser);
    $qry->bindParam(':descripcion', $descripcion);
    $qry->bindParam(':fecha_creacion', $fecha_creacion);
    $qry->bindParam(':estado', $estado);

    if ($qry->execute()) {
        $ticketId = $datappi->lastInsertId(); // Obtener el ID del último ticket insertado
        $rta = "ok";

        // Generar la URL con los parámetros necesarios
        $url = 'https://ppi.miclickderecho.com/plantilla/email/mailing2.php';
        $data = http_build_query(array('descripcion' => $descripcion, 'idUser' => $idUser, 'ticketId' => $ticketId));

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

        // Agregar la respuesta de la solicitud de correo al JSON
        echo json_encode(array('rta' => $rta, 'ticketId' => $ticketId, 'respuestaCorreo' => $respuesta));
    } else {
        $rta = "error";
        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    }
}

//Actualizar usuarios
if ($indicador == "4") {
    $id = isset($_POST['id']) ? $_POST['id'] : "";
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
    $fecha_creacion = isset($_POST['fecha_creacion']) ? $_POST['fecha_creacion'] : "";
    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Assuming 'usuarios' is the correct table name and fields match
    $query = "UPDATE quejas SET descripcion = :descripcion, fecha_creacion = :fecha_creacion, estado = :estado WHERE id = :id";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':descripcion', $descripcion);
    $qry->bindParam(':fecha_creacion', $fecha_creacion);
    $qry->bindParam(':estado', $estado);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = "ok";
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Mostrar datos del usuario
if ($indicador == "6") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "SELECT quejas.*, usuarios.nombre_completo ,usuarios.id AS idUsuario
                       FROM quejas 
                       INNER JOIN usuarios ON quejas.idUser = usuarios.id 
                       WHERE quejas.id = :idUsuario";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':idUsuario', $id);

    if ($qry->execute()) {
        $rta = $qry->fetch(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Eliminar quejas
if ($indicador == "7") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "DELETE FROM quejas WHERE id = :id";
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
