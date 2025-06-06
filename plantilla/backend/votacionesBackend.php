<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");


//Leer los datos
if ($indicador == "0") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query = "SELECT * FROM votaciones WHERE id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo 'Error al obtener datos de la votación: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $productData));
}


//Mostrar los datos del las votaciones
if ($indicador == "1") {
    try {
        $nuevoinicio = isset($_POST['nuevoinicio']) ? intval($_POST['nuevoinicio']) : 0;
        $nroreg = isset($_POST['nroreg']) ? intval($_POST['nroreg']) : 0;

        // Conteo de productos para la sucursal específica
        $query = "SELECT COUNT(v.id) AS count FROM votaciones v ;";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta de productos para la sucursal específica
        $query2 = "WITH VotosPorTitulo AS (
            SELECT v.id, v.titulo, o.descripcion, v.descripcion AS descripcion2, e.id_opcion, v.estado,v.fecha_cierre_votacion ,
              COALESCE(COUNT(id_opcion), 0) AS Total,
              ROW_NUMBER() OVER(PARTITION BY v.titulo ORDER BY COALESCE(COUNT(id_opcion), 0) DESC) AS rn
            FROM opciones o
            LEFT JOIN votaciones v ON v.id = o.id_votacion
            LEFT JOIN elecciones e ON e.id_opcion = o.id
            GROUP BY v.id, v.titulo, o.descripcion, e.id_opcion
          )
          SELECT *
            FROM (
                SELECT id, titulo, descripcion2, descripcion, id_opcion, Total, estado, fecha_cierre_votacion
                FROM VotosPorTitulo
                WHERE rn = 1
                ) AS Resultados
            ORDER BY id
            DESC LIMIT :nuevoinicio, :nroreg";
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
if ($indicador == "2") {
    try {
        $nuevoinicio = isset($_POST['nuevoinicio']) ? intval($_POST['nuevoinicio']) : 0;
        $nroreg = isset($_POST['nroreg']) ? intval($_POST['nroreg']) : 0;

        // Conteo de productos para la sucursal específica
        $query = "SELECT COUNT(v.id) AS count FROM votaciones v ;";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta de productos para la sucursal específica
        $query2 = "WITH VotosPorTitulo AS (
            SELECT v.id,v.titulo, o.descripcion, v.descripcion AS descripcion2 , e.id_opcion,COUNT(*) AS Total, 
            ROW_NUMBER() OVER(PARTITION BY v.titulo ORDER BY COUNT(*) DESC) AS rn
            FROM elecciones e    
            INNER JOIN opciones o      
            ON e.id_opcion = o.id  
            INNER JOIN votaciones v   
            ON v.id = o.id_votacion  
            GROUP BY v.id, v.titulo, o.descripcion, e.id_opcion
        )
        SELECT id, titulo, descripcion2,  descripcion, id_opcion, Total
        FROM VotosPorTitulo
        WHERE rn = 1";
        $qry2 = $datappi->prepare($query2);
        // $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
        // $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
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
if ($indicador == "3") {
    try {
        $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : (!empty($_GET['titulo']) ? $_GET['titulo'] : "");
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
        $fecha_cierre = isset($_POST['fecha_cierre']) ? $_POST['fecha_cierre'] : (!empty($_GET['fecha_cierre']) ? $_GET['fecha_cierre'] : "");
        $opciones = isset($_POST['opciones']) ? $_POST['opciones'] : (!empty($_GET['opciones']) ? $_GET['opciones'] : "");

        // Modificado: Cambio "fecha_cierre" por "fecha_cierre_votacion"
        $query = 'INSERT INTO votaciones (titulo, descripcion, fecha_cierre_votacion, estado) 
                  VALUES (:titulo, :descripcion, :fecha_cierre, 1)';
        $stmt = $datappi->prepare($query);
        $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindValue(':fecha_cierre', $fecha_cierre, PDO::PARAM_STR); // Aquí ya está bien nombrado

        if ($stmt->execute()) {
            $rta = "ok";
            $id_votacion = $datappi->lastInsertId();

            $query = 'INSERT INTO opciones (id_votacion, descripcion) VALUES (:id_votacion, :descripcion)';
            $stmt = $datappi->prepare($query);

            if (!is_array($opciones)) {
                $opciones = explode(',', $opciones);
            }

            foreach ($opciones as $opcion) {
                $stmt->bindValue(':id_votacion', $id_votacion, PDO::PARAM_INT);
                $stmt->bindValue(':descripcion', $opcion, PDO::PARAM_STR);
                $stmt->execute();
            }

            header('Content-Type: application/json');
            echo json_encode(array('rta' => $rta, 'code' => $id_votacion));
        } else {
            $rta = "error";
            header('Content-Type: application/json');
            echo json_encode(array('rta' => $rta));
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(["rta" => "SQL Error: " . $e->getMessage()]);
        exit;
    }
}


if ($indicador == "4") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : (!empty($_GET['titulo']) ? $_GET['titulo'] : "");
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
    $opciones = isset($_POST['opciones']) ? $_POST['opciones'] : (!empty($_GET['opciones']) ? $_GET['opciones'] : "");


    try {
        // Comenzar una transacción para garantizar que todas las operaciones se completen o se reviertan
        $datappi->beginTransaction();

        // Actualizar el título y la descripción de la votación
        $query = "UPDATE votaciones SET titulo = :titulo, descripcion = :descripcion WHERE id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':titulo', $titulo);
        $qry->bindParam(':descripcion', $descripcion);
        $qry->bindParam(':id', $id);
        $qry->execute();
        // Verifica si $opciones no está vacío antes de procesarlo
        if (!empty($opciones)) {
            // Divide la cadena $opciones en un array utilizando '|'
            $opciones_array = explode(',', $opciones);

            // Ahora puedes iterar sobre $opciones_array
            foreach ($opciones_array as $opcion) {
                // Divide cada elemento de $opciones_array en ID y valor
                $opcion_data = explode('|', $opcion);
                $opcion_id = $opcion_data[0];
                $opcion_desc = $opcion_data[1];
                // Si el ID de la opción es -1, se crea una nueva opción, de lo contrario, se actualiza la opción existente
                if ($opcion_id == -1) {
                    $insertQuery = "INSERT INTO opciones (id_votacion, descripcion) VALUES (:id_votacion, :descripcion)";
                    $insertStmt = $datappi->prepare($insertQuery);
                    $insertStmt->bindParam(':id_votacion', $id);
                    $insertStmt->bindParam(':descripcion', $opcion_desc);
                    $insertStmt->execute();
                } else {
                    $updateQuery = "UPDATE opciones SET descripcion = :descripcion WHERE id = :id";
                    $updateStmt = $datappi->prepare($updateQuery);
                    $updateStmt->bindParam(':descripcion', $opcion_desc);
                    $updateStmt->bindParam(':id', $opcion_id);
                    $updateStmt->execute();
                }
            }
        }

        // Confirmar la transacción si todas las operaciones han sido exitosas
        $datappi->commit();

        $rta = "ok";
    } catch (PDOException $e) {
        // Si ocurre algún error, revertir la transacción y devolver un mensaje de error
        $datappi->rollBack();
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta, 'status' => 'ok', 'opciones1' => $opciones, 'opciones2' => $opciones2, 'opcion_id' => $opcion_id, 'opcion_desc' => $opcion_desc));
}

if ($indicador == "41") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : (!empty($_GET['titulo']) ? $_GET['titulo'] : "");
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : (!empty($_GET['descripcion']) ? $_GET['descripcion'] : "");
    $query = "UPDATE votaciones SET titulo = :titulo, descripcion = :descripcion WHERE id = :id";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':titulo', $titulo);
    $qry->bindParam(':descripcion', $descripcion);

    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = "ok";
    } else {
        $rta = "error";
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta, 'status' => 'ok'));
}

if ($indicador == "5") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "UPDATE votaciones SET estado = 0 WHERE id = :id";
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

if ($indicador == "61") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query1 = "SELECT * FROM votaciones WHERE id = :id";
        $qry1 = $datappi->prepare($query1);
        $qry1->bindParam(':id', $id, PDO::PARAM_INT);
        $qry1->execute();
        $productData = $qry1->fetchAll(PDO::FETCH_OBJ);

        // Conteo de productos para la sucursal específica
        $query2 = "SELECT COUNT(*) from opciones WHERE id_votacion =:id";
        $qry2 = $datappi->prepare($query2);
        $qry2->bindParam(':id', $id, PDO::PARAM_INT);
        $qry2->execute();
        $count = $qry2->fetch(PDO::FETCH_ASSOC)["COUNT(*)"];

        // Consulta de productos para la sucursal específica
        $query3 = "SELECT * FROM opciones WHERE id_votacion = :id";
        $qry3 = $datappi->prepare($query3);
        $qry3->bindParam(':id', $id, PDO::PARAM_INT);
        $qry3->execute();
        $rta2 = $qry3->fetchAll(PDO::FETCH_OBJ);

        $response = array(
            'rta' => $productData,
            'count' => $count,
            'rta2' => $rta2
        );
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(["rta" => $e->getMessage()]);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode($response);

}
// Reporte de votaciones con opciones y cantidad de votos por opción
if ($indicador == "10") {
    try {
        $query = "SELECT 
    v.id AS id_votacion,
    v.titulo,
    v.descripcion AS descripcion_votacion,
    o.id AS id_opcion,
    o.descripcion AS descripcion_opcion,
    COUNT(e.id) AS total_votos
FROM 
    votaciones v
JOIN opciones o ON v.id = o.id_votacion
LEFT JOIN elecciones e ON o.id = e.id_opcion
GROUP BY 
    v.id, o.id
ORDER BY 
    v.id, total_votos DESC;
";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en consulta de Reporte de votaciones con opciones y cantidad de votos por opción: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}
//Reporte de votaciones por usuario (quién votó qué)
if ($indicador == "11") {
    try {
        $query = "SELECT 
    u.id AS id_usuario,
    u.nombre_completo,
    v.titulo AS votacion,
    o.descripcion AS opcion_elegida,
    e.id AS id_voto
FROM 
    elecciones e
JOIN usuarios u ON e.id_usuario = u.id
JOIN opciones o ON e.id_opcion = o.id
JOIN votaciones v ON o.id_votacion = v.id
ORDER BY 
    u.nombre_completo, v.titulo;";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        $rta = "error";
        error_log("Error en consulta de Reporte de votaciones por usuario (quién votó qué): " . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// if ($indicador == "61") {

//     $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
//     try {
//         // Conteo de productos para la sucursal específica
//         $query = "SELECT COUNT(*) from opciones WHERE id_votacion =:id";
//         $qry = $datappi->prepare($query);
//         $qry->bindParam(':id', $id, PDO::PARAM_INT);
//         $qry->execute();
//         $count = $qry->fetch(PDO::FETCH_ASSOC)["COUNT(*)"];

//         // Consulta de productos para la sucursal específica
//         $query2 = "SELECT * FROM opciones WHERE id_votacion = :id"; // Aquí estaba el error, debe ser ':id' y no '$id'
//         $qry2 = $datappi->prepare($query2);
//         $qry2->bindParam(':id', $id); // Aquí también debe enlazarse con $qry2
//         $qry2->execute();
//         $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);
//         $response = array('rta' => $count, 'rta2' => $rta2);
//     } catch (Exception $e) {
//         header('Content-Type: application/json');
//         echo json_encode(["rta" => $e->getMessage()]);
//         exit;
//     }
//     header('Content-Type: application/json');
//     echo json_encode($response);
// }   

?>