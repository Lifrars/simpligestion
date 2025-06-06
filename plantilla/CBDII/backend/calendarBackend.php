<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");


//Leer los datos
if ($indicador == "1") {

    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query = "SELECT * FROM Areas_Comunes WHERE estado= 0";
        $qry = $datappi->prepare($query);

        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo 'Error al obtener datos de las areas: ' . $e->getMessage();
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
        $query = "SELECT COUNT(usuarios.id) AS count FROM usuarios;";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta de productos para la sucursal específica
        $query2 = "SELECT * FROM usuarios ORDER BY usuarios.id DESC LIMIT :nuevoinicio, :nroreg";
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
    $idArea = isset($_POST['idArea']) ? $_POST['idArea'] : "";
    $idUser = isset($_POST['idUser']) ? $_POST['idUser'] : "";
    $fechareserva = isset($_POST['fechareserva']) ? $_POST['fechareserva'] : "";
    $horaInicio = isset($_POST['horaInicio']) ? $_POST['horaInicio'] : "";
    $horaFin = isset($_POST['horaFin']) ? $_POST['horaFin'] : "";
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : "";

    // Luego puedes usar estos valores como necesites.


    $query = "INSERT INTO reservas (usuario_id, area_id, fecha_reserva, hora_inicio, hora_fin, comentario, estado_id)
    VALUES (:idUser, :idArea, :fechareserva, :horaInicio, :horaFin, :comentario, 2);";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':idUser', $idUser);
    $qry->bindParam(':idArea', $idArea);
    $qry->bindParam(':fechareserva', $fechareserva);
    $qry->bindParam(':horaInicio', $horaInicio);
    $qry->bindParam(':horaFin', $horaFin); // Insertar el código temporal cifrado
    $qry->bindParam(':comentario', $comentario);
    // Insertar el código temporal cifrado

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

//Actualizar usuarios
if ($indicador == "4") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    $documento = isset($_POST['documento']) ? $_POST['documento'] : (!empty($_GET['documento']) ? $_GET['documento'] : "");
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : (!empty($_GET['telefono']) ? $_GET['telefono'] : "");
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : (!empty($_GET['nombre']) ? $_GET['nombre'] : "");
    $correo = isset($_POST['correo']) ? $_POST['correo'] : (!empty($_GET['correo']) ? $_GET['correo'] : "");
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : (!empty($_GET['perfil']) ? $_GET['perfil'] : "");

    $query = "UPDATE usuarios SET documento = :documento, nombre_completo = :nombre, telefono = :telefono, correo = :correo, idPerfil = :idPerfil WHERE id = :id";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':documento', $documento);
    $qry->bindParam(':nombre', $nombre);
    $qry->bindParam(':telefono', $telefono);
    $qry->bindParam(':correo', $correo);
    $qry->bindParam(':idPerfil', $perfil);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = "ok";
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta, 'status' => 'ok'));
}

//Traer perfiles del sistema 
if ($indicador == "5") {
    $idArea = isset($_POST['idArea']) ? $_POST['idArea'] : "";
    $idUser = isset($_POST['idUser']) ? $_POST['idUser'] : "";

    $query = "SELECT u.telefono  ,r.fecha_reserva ,r.hora_inicio ,r.hora_fin ,r.comentario,e.descripcion  from reservas r  
                INNER JOIN usuarios u ON r.usuario_id = u.id
                INNER JOIN estados e ON r.estado_id = e.id
                INNER JOIN Areas_Comunes ac ON ac.area_id  = r.area_id
                WHERE ac.area_id = :idArea AND u.id= :idUser;";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':idArea', $idArea);
    $qry->bindParam(':idUser', $idUser);

    if ($qry->execute()) {
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Mostrar datos del usuario
if ($indicador == "6") {
    $idArea = isset($_POST['idArea']) ? $_POST['idArea'] : "";
    $idUser = isset($_POST['idUser']) ? $_POST['idUser'] : "";
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : "";

    // Consulta SQL con los nombres de parámetros correctos
    $query = "SELECT 
        CASE 
            WHEN YEARWEEK(:fecha_reserva, 1) = YEARWEEK(r.fecha_reserva, 1) THEN 2
            ELSE 1
        END AS num
    FROM reservas r
    WHERE r.usuario_id = :id_User 
      AND r.area_id = :id_Area;";

    $qry = $datappi->prepare($query);
    $qry->bindParam(':id_Area', $idArea);
    $qry->bindParam(':id_User', $idUser);
    $qry->bindParam(':fecha_reserva', $fecha);

    if ($qry->execute()) {
        $rta = $qry->fetch(PDO::FETCH_OBJ);
        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    } else {
        $errorInfo = $qry->errorInfo();
        $errorMessage = "Error al ejecutar la consulta: " . $errorInfo[2];
        header('Content-Type: application/json');
        echo json_encode(array('error' => $errorMessage));
    }
}


//Eliminar usuarios
if ($indicador == "7") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "DELETE FROM usuarios WHERE id = :id";
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
