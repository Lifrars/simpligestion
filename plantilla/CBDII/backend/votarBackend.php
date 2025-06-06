<?php
session_start();
include "../assets/config.php";
$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");
$table = isset($_POST['id_vota']) ? $_POST['id_vota'] : (!empty($_GET['id_vota']) ? $_GET['id_vota'] : "");
$iduser = $_SESSION['pi']['act_id'];

// //Leer los datos
// if ($indicador == "0") {

//     $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

//     try {
//         // Consultar datos del producto con INNER JOIN en categorias
//         $query = "SELECT COUNT(e.id_opcion) as total from elecciones e WHERE e.id_opcion=:id"; //dos puntos pdo le pasa por debajo
//         $qry = $datappi->prepare($query);
//         $qry->bindParam(':id', $id, PDO::PARAM_INT);
//         $qry->execute();
//         $productData = $qry->fetchAll(PDO::FETCH_OBJ);
//     } catch (PDOException $e) {
//         echo 'Error al obtener datos del producto: ' . $e->getMessage();
//     }

//     header('Content-Type: application/json');
//     // echo json_encode($productData);
//     echo json_encode(["rta" => $productData]);
// }

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
        $query2 = "SELECT v.titulo, o.descripcion ,COUNT(*) AS Total
    FROM elecciones e    
    INNER JOIN opciones o      
    ON e.id_opcion = o.id  
    INNER JOIN votaciones v   
    ON v.id = o.id_votacion  
    where v.id = $table
    GROUP BY v.id, v.titulo, o.descripcion, e.id_opcion";
        $qry2 = $datappi->prepare($query2);
        $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
        $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
        $qry2->execute();
        $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);

        $response = array('rta2' => $rta2);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(["rta" => $e->getMessage()]);
        exit;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

if ($indicador == "3") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query = "SELECT  v.titulo , v.descripcion 
        FROM votaciones v
        INNER JOIN opciones o WHERE o.id_votacion = :id
        AND o.id_votacion = v.id 
        GROUP BY v.id, v.titulo, o.descripcion
        ORDER BY v.id DESC;";
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
if ($indicador == "4") {

    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query = "SELECT  o.descripcion , o.id  as id_opcion
        FROM votaciones v
        INNER JOIN opciones o WHERE o.id_votacion = :id
        AND o.id_votacion = v.id 
        GROUP BY v.id, v.titulo, o.descripcion
        ORDER BY v.id DESC;";
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

if ($indicador == "5") {
    $id_opcion = isset($_POST['id_opcion']) ? $_POST['id_opcion'] : null;
    $id_votacion = isset($_POST['id_votacion']) ? $_POST['id_votacion'] : null;


    $query0 = "SELECT COUNT(e.id) as count from elecciones e
	inner join opciones o on e.id_opcion =o.id  
	WHERE  e.id_usuario=:iduser and o.id_votacion=:id_votacion";
    $qry = $datappi->prepare($query0);
    $qry->bindParam(':id_votacion', $id_votacion, PDO::PARAM_INT);
    $qry->bindParam(':iduser', $iduser, PDO::PARAM_INT);
    $qry->execute();
    $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];
    if ($count > 0) {
        $rtacount = "nopermitido";
    } else {
        if ($id_opcion !== null) {
            $query = "INSERT INTO elecciones (id_usuario, id_opcion) VALUES (:iduser, :id_opcion)";
            $stmt = $datappi->prepare($query);
            $stmt->bindParam(':id_opcion', $id_opcion, PDO::PARAM_INT);
            $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $rta = "ok";
            } else {
                $rta = "error al ejecutar la consulta";
            }
        } else {
            $rta = "error: no se recibió el parámetro id_opcion";
        }
    }
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta, 'rtacount' => $rtacount));
}