<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");

//Leer los datos
if ($indicador == "1") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del área
        $query = "SELECT * FROM Areas_Comunes WHERE area_id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo 'Error al obtener datos del área: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $productData));
}

//Mostrar los datos del área (listar)
if ($indicador == "2") {
    try {
        $nuevoinicio = isset($_POST['nuevoinicio']) ? intval($_POST['nuevoinicio']) : 0;
        $nroreg = isset($_POST['nroreg']) ? intval($_POST['nroreg']) : 0;

        // Conteo de áreas
        $query = "SELECT COUNT(Areas_Comunes.area_id) AS count FROM Areas_Comunes";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta de áreas
        $query2 = "SELECT * FROM Areas_Comunes ORDER BY Areas_Comunes.area_id DESC LIMIT :nuevoinicio, :nroreg";
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

// Actualizar áreas comunes
if ($indicador == "4") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : (!empty($_GET['nombre']) ? $_GET['nombre'] : "");
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
    $capacidad = isset($_POST['capacidad']) ? $_POST['capacidad'] : (!empty($_GET['capacidad']) ? $_GET['capacidad'] : "");
    $hora_inicio = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : (!empty($_GET['hora_inicio']) ? $_GET['hora_inicio'] : "");
    $hora_fin = isset($_POST['hora_fin']) ? $_POST['hora_fin'] : (!empty($_GET['hora_fin']) ? $_GET['hora_fin'] : "");
    $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : (!empty($_GET['ubicacion']) ? $_GET['ubicacion'] : "");
    $dias_disponibles = isset($_POST['dias_disponibles']) ? $_POST['dias_disponibles'] : (!empty($_GET['dias_disponibles']) ? $_GET['dias_disponibles'] : "");

    try {
        $query = "UPDATE Areas_Comunes SET 
                    nombre = :nombre, 
                    descripcion = :descripcion, 
                    capacidad = :capacidad, 
                    hora_inicio = :hora_inicio, 
                    hora_fin = :hora_fin, 
                    ubicacion = :ubicacion,
                    dias_disponibles = :dias_disponibles
                  WHERE area_id = :id";

        $qry = $datappi->prepare($query);
        $qry->bindParam(':nombre', $nombre);
        $qry->bindParam(':descripcion', $descripcion);
        $qry->bindParam(':capacidad', $capacidad);
        $qry->bindParam(':hora_inicio', $hora_inicio);
        $qry->bindParam(':hora_fin', $hora_fin);
        $qry->bindParam(':ubicacion', $ubicacion);
        $qry->bindParam(':dias_disponibles', $dias_disponibles);
        $qry->bindParam(':id', $id);

        if ($qry->execute()) {
            $rta = "ok";
        } else {
            $rta = "error";
        }
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en actualización: " . $e->getMessage());
    }
    
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Insertar áreas comunes
if ($indicador == "5") {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : (!empty($_GET['nombre']) ? $_GET['nombre'] : "");
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
    $capacidad = isset($_POST['capacidad']) ? $_POST['capacidad'] : (!empty($_GET['capacidad']) ? $_GET['capacidad'] : "");
    $hora_inicio = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : (!empty($_GET['hora_inicio']) ? $_GET['hora_inicio'] : "");
    $hora_fin = isset($_POST['hora_fin']) ? $_POST['hora_fin'] : (!empty($_GET['hora_fin']) ? $_GET['hora_fin'] : "");
    $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : (!empty($_GET['ubicacion']) ? $_GET['ubicacion'] : "");
    $dias_disponibles = isset($_POST['dias_disponibles']) ? $_POST['dias_disponibles'] : (!empty($_GET['dias_disponibles']) ? $_GET['dias_disponibles'] : "");

    try {
        // Insertar en la base de datos
        $query = "INSERT INTO Areas_Comunes (nombre, descripcion, capacidad, hora_inicio, hora_fin, ubicacion, dias_disponibles, estado) 
                  VALUES (:nombre, :descripcion, :capacidad, :hora_inicio, :hora_fin, :ubicacion, :dias_disponibles, 0)";

        $qry = $datappi->prepare($query);
        $qry->bindParam(':nombre', $nombre);
        $qry->bindParam(':descripcion', $descripcion);
        $qry->bindParam(':capacidad', $capacidad);
        $qry->bindParam(':hora_inicio', $hora_inicio);
        $qry->bindParam(':hora_fin', $hora_fin);
        $qry->bindParam(':ubicacion', $ubicacion);
        $qry->bindParam(':dias_disponibles', $dias_disponibles);

        if ($qry->execute()) {
            $rta = "ok";
            $id = $datappi->lastInsertId();
        } else {
            $rta = "error";
            $id = null;
        }
    } catch (PDOException $e) {
        $rta = "error";
        $id = null;
        error_log("Error en inserción: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta, 'id' => $id));
}

//Mostrar datos del área (detalle)
if ($indicador == "6") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        $query = "SELECT * FROM Areas_Comunes WHERE Areas_Comunes.area_id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id);

        if ($qry->execute()) {
            $rta = $qry->fetch(PDO::FETCH_OBJ);
        } else {
            $rta = "error";
        }
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en consulta detalle: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Desactivar área
if ($indicador == "7") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        $query = "UPDATE Areas_Comunes SET estado = 1 WHERE area_id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id);

        if ($qry->execute()) {
            $rta = "ok";
        } else {
            $rta = "error";
        }
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en desactivación: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Activar área
if ($indicador == "8") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        $query = "UPDATE Areas_Comunes SET estado = 0 WHERE area_id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id);

        if ($qry->execute()) {
            $rta = "ok";
        } else {
            $rta = "error";
        }
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en activación: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}
// Consultar todas las áreas activas
if ($indicador == "9") {
    try {
        $query = "SELECT * FROM Areas_Comunes WHERE estado = 0 ORDER BY area_id DESC";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en consulta de áreas activas: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Consultar todas las áreas inactivas
if ($indicador == "10") {
    try {
        $query = "SELECT * FROM Areas_Comunes WHERE estado = 1 ORDER BY area_id DESC";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en consulta de áreas inactivas: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Obtener total de áreas comunes
if ($indicador == "11") {
    try {
        $query = "SELECT COUNT(*) as total FROM Areas_Comunes";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $rta = $qry->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en conteo total de áreas: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Obtener capacidad total de todas las áreas activas
if ($indicador == "12") {
    try {
        $query = "SELECT SUM(capacidad) as capacidad_total FROM Areas_Comunes WHERE estado = 0";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $rta = $qry->fetch(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error al obtener capacidad total: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Listar solo ID y Nombre de áreas activas
if ($indicador == "13") {
    try {
        $query = "SELECT area_id, nombre FROM Areas_Comunes WHERE estado = 0 ORDER BY nombre ASC";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en listado de nombres de áreas: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

?>