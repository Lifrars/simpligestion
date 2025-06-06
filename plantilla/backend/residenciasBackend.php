<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");

// Mostrar todos los errores
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Leer datos de una residencia específica con INNER JOIN para traer el nombre de la torre
if ($indicador == "1") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        $query = "SELECT r.id_residencia, r.direccion_residencia, r.documento_usuario_propietario, t.nombre_torre, r.id_torre
                  FROM residencias r
                  INNER JOIN torres t ON r.id_torre = t.id_torre
                  WHERE r.id_residencia = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $residenciaData = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo json_encode(['rta' => 'error', 'message' => $e->getMessage()]);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['rta' => $residenciaData]);
}

// Listar residencias con paginación y el nombre de la torre
if ($indicador == "2") {
    try {
        $nuevoinicio = isset($_POST['nuevoinicio']) ? intval($_POST['nuevoinicio']) : 0;
        $nroreg = isset($_POST['nroreg']) ? intval($_POST['nroreg']) : 0;

        // Consulta para obtener el total de residencias
        $query = "SELECT COUNT(r.id_residencia) AS count 
                  FROM residencias r
                  INNER JOIN torres t ON r.id_torre = t.id_torre";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta para obtener las residencias con el nombre de la torre
        $query2 = "SELECT r.id_residencia, r.direccion_residencia, r.documento_usuario_propietario, t.nombre_torre 
                   FROM residencias r
                   INNER JOIN torres t ON r.id_torre = t.id_torre
                   ORDER BY r.id_residencia DESC
                   LIMIT :nuevoinicio, :nroreg";
        $qry2 = $datappi->prepare($query2);
        $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
        $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
        $qry2->execute();
        $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);

        echo json_encode(['rta' => $count, 'rta2' => $rta2]);
    } catch (Exception $e) {
        echo json_encode(['rta' => 'error', 'message' => $e->getMessage()]);
    }
}

// Insertar nueva residencia
if ($indicador == "3") {
    $direccion_residencia = isset($_POST['direccion_residencia']) ? $_POST['direccion_residencia'] : "";
    $documento_usuario_propietario = isset($_POST['documento_usuario_propietario']) ? $_POST['documento_usuario_propietario'] : "";
    $id_torre = isset($_POST['nombre_torre']) ? $_POST['nombre_torre'] : "";

    $query = "INSERT INTO residencias (direccion_residencia, documento_usuario_propietario, id_torre) VALUES (:direccion_residencia, :documento_usuario_propietario, :id_torre)";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':direccion_residencia', $direccion_residencia);
    $qry->bindParam(':documento_usuario_propietario', $documento_usuario_propietario);
    $qry->bindParam(':id_torre', $id_torre, PDO::PARAM_INT);

    if ($qry->execute()) {
        echo json_encode(['rta' => 'ok']);
    } else {
        echo json_encode(['rta' => 'error']);
    }
}

// Actualizar residencia
if ($indicador == "4") {
    $id_residencia = isset($_POST['id']) ? $_POST['id'] : "";
    $direccion_residencia = isset($_POST['direccion_residencia']) ? $_POST['direccion_residencia'] : "";
    $documento_usuario_propietario = isset($_POST['documento_usuario_propietario']) ? $_POST['documento_usuario_propietario'] : "";
    $id_torre = isset($_POST['id_torre']) ? $_POST['id_torre'] : "";

    $query = "UPDATE residencias SET direccion_residencia = :direccion_residencia, documento_usuario_propietario = :documento_usuario_propietario, id_torre = :id_torre WHERE id_residencia = :id_residencia";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':direccion_residencia', $direccion_residencia);
    $qry->bindParam(':documento_usuario_propietario', $documento_usuario_propietario);
    $qry->bindParam(':id_torre', $id_torre, PDO::PARAM_INT);
    $qry->bindParam(':id_residencia', $id_residencia, PDO::PARAM_INT);

    if ($qry->execute()) {
        echo json_encode(['rta' => 'ok']);
    } else {
        echo json_encode(['rta' => 'error']);
    }
}

// Mostrar datos de una residencia específica con INNER JOIN para traer el nombre de la torre
if ($indicador == "6") {
    $id_residencia = isset($_POST['id']) ? $_POST['id'] : "";

    $query = "SELECT r.id_residencia, r.direccion_residencia, r.documento_usuario_propietario, t.nombre_torre, r.id_torre
              FROM residencias r
              INNER JOIN torres t ON r.id_torre = t.id_torre
              WHERE r.id_residencia = :id_residencia";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':id_residencia', $id_residencia, PDO::PARAM_INT);

    if ($qry->execute()) {
        $rta = $qry->fetch(PDO::FETCH_OBJ);
        echo json_encode(['rta' => $rta]);
    } else {
        echo json_encode(['rta' => 'error']);
    }
}

// Eliminar residencia
if ($indicador == "7") {
    $id_residencia = isset($_POST['id']) ? $_POST['id'] : "";

    try {
        $query = "DELETE FROM residencias WHERE id_residencia = :id_residencia";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id_residencia', $id_residencia, PDO::PARAM_INT);

        if ($qry->execute()) {
            echo json_encode(['rta' => 'ok']);
        } else {
            echo json_encode(['rta' => 'error']);
        }
    } catch (PDOException $e) {
        echo json_encode(['rta' => 'error', 'message' => $e->getMessage()]);
    }
}

// Listar torres disponibles para el selector
if ($indicador == "8") {
    try {
        $query2 = "SELECT id_torre, nombre_torre FROM torres ORDER BY id_torre DESC";
        $qry2 = $datappi->prepare($query2);
        $qry2->execute();
        $torres = $qry2->fetchAll(PDO::FETCH_OBJ);

        echo json_encode(['rta2' => $torres]);
    } catch (Exception $e) {
        echo json_encode(['rta' => 'error', 'message' => $e->getMessage()]);
    }
}
