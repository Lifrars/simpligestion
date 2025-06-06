<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");

// Mostrar todos los errores
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Leer datos de una torre específica
if ($indicador == "1") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        $query = "SELECT id_torre, nombre_torre, numero_pisos_torre FROM torres WHERE id_torre = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $torreData = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo json_encode(['rta' => 'error', 'message' => $e->getMessage()]);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['rta' => $torreData]);
}

// Listar torres con paginación
if ($indicador == "2") {
    try {
        $nuevoinicio = isset($_POST['nuevoinicio']) ? intval($_POST['nuevoinicio']) : 0;
        $nroreg = isset($_POST['nroreg']) ? intval($_POST['nroreg']) : 0;

        // Contador total (opcional)
        $query = "SELECT COUNT(*) AS count FROM residencias";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta nueva que une torres, pisos y residencias
        $query2 = "
            SELECT 
                t.nombre_torre AS torre,
                p.id_piso AS piso,
                r.id_residencia,
                r.numero_residencia AS residencia,
                COALESCE(r.documento_usuario_propietario_residencia, 'Sin Propietario') AS propietario
            FROM residencias r
            JOIN pisos p ON r.id_piso_residencia = p.id_piso AND r.id_torre_residencia = p.id_torre_piso
            JOIN torres t ON r.id_torre_residencia = t.id_torre
            ORDER BY t.nombre_torre, p.id_piso, r.numero_residencia
            LIMIT :nuevoinicio, :nroreg
        ";

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

// Insertar nueva torre
if ($indicador == "3") {
    $nombre_torre = isset($_POST['nombre_torre']) ? $_POST['nombre_torre'] : "";
    $numero_pisos_torre = isset($_POST['numero_pisos_torre']) ? $_POST['numero_pisos_torre'] : "";

    $query = "INSERT INTO torres (nombre_torre, numero_pisos_torre) VALUES (:nombre_torre, :numero_pisos_torre)";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':nombre_torre', $nombre_torre);
    $qry->bindParam(':numero_pisos_torre', $numero_pisos_torre);

    if ($qry->execute()) {
        echo json_encode(['rta' => 'ok']);
    } else {
        echo json_encode(['rta' => 'error']);
    }
}

// Actualizar torre
if ($indicador == "4") {
    $id_torre = isset($_POST['id']) ? $_POST['id'] : "";
    $nombre_torre = isset($_POST['nombre_torre']) ? $_POST['nombre_torre'] : "";
    $numero_pisos_torre = isset($_POST['numero_pisos_torre']) ? $_POST['numero_pisos_torre'] : "";

    $query = "UPDATE torres SET nombre_torre = :nombre_torre, numero_pisos_torre = :numero_pisos_torre WHERE id_torre = :id_torre";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':nombre_torre', $nombre_torre);
    $qry->bindParam(':numero_pisos_torre', $numero_pisos_torre);
    $qry->bindParam(':id_torre', $id_torre, PDO::PARAM_INT);

    if ($qry->execute()) {
        echo json_encode(['rta' => 'ok']);
    } else {
        echo json_encode(['rta' => 'error']);
    }
}

// Mostrar todos los datos de una residencia, propietario + residentes
if ($indicador == "6") {
    $id_residencia = $_POST['id'] ?? "";

    // Datos principales de la residencia y torre
    $query = "
        SELECT 
            r.numero_residencia,
            r.id_piso_residencia,
            t.nombre_torre
        FROM residencias r
        LEFT JOIN torres t ON r.id_torre_residencia = t.id_torre
        WHERE r.id_residencia = :id_residencia
    ";

    $qry = $datappi->prepare($query);
    $qry->bindParam(':id_residencia', $id_residencia, PDO::PARAM_INT);
    $qry->execute();
    $residencia = $qry->fetch(PDO::FETCH_OBJ);

    // Obtener propietario
    $queryProp = "
        SELECT documento, nombre_completo 
        FROM usuarios 
        WHERE id_residencia = :id_residencia AND idPerfil = 4 
        LIMIT 1
    ";
    $qryProp = $datappi->prepare($queryProp);
    $qryProp->bindParam(':id_residencia', $id_residencia);
    $qryProp->execute();
    $propietario = $qryProp->fetch(PDO::FETCH_OBJ);
    if (!$propietario) {
        $propietario = (object)[
            'documento' => null,
            'nombre_completo' => 'Sin Propietario'
        ];
    }

    // Obtener residentes (todos, sin filtrar)
    $queryRes = "
        SELECT nombre_completo 
        FROM usuarios 
        WHERE id_residencia = :id_residencia AND idPerfil != 4
        ORDER BY nombre_completo ASC
    ";
    $qryRes = $datappi->prepare($queryRes);
    $qryRes->bindParam(':id_residencia', $id_residencia);
    $qryRes->execute();
    $residentes = $qryRes->fetchAll(PDO::FETCH_OBJ);

    echo json_encode([
        'rta' => [
            'numero_residencia' => $residencia->numero_residencia ?? null,
            'id_piso_residencia' => $residencia->id_piso_residencia ?? null,
            'nombre_torre' => $residencia->nombre_torre ?? null,
            'propietario_documento' => $propietario->documento,
            'propietario_nombre' => $propietario->nombre_completo,
            'residentes' => $residentes
        ]
    ]);
}

// Validar si el nombre de la torre ya existe en la base de datos
if ($indicador == "8") {
    $nombreTorre = isset($_GET['nombreTorre']) ? $_GET['nombreTorre'] : "";

    $query = "SELECT COUNT(*) AS total FROM torres WHERE nombre_torre = :nombreTorre";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':nombreTorre', $nombreTorre);
    $qry->execute();
    $resultado = $qry->fetch(PDO::FETCH_ASSOC);

    $existe = ($resultado["total"] > 0);

    header('Content-Type: application/json');
    echo json_encode(["existe" => $existe]);
}
