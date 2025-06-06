<?php
include "../assets/config.php";

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");

// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Leer los datos
if ($indicador == "1") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query = "SELECT * FROM usuarios WHERE id = :id";
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

        // Conteo de usuarios
        $query = "SELECT COUNT(usuarios.id) AS count FROM usuarios;";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Traer usuarios junto con el nombre del perfil
        $query2 = "
            SELECT 
                u.*, 
                p.nombre_perfil 
            FROM usuarios u
            LEFT JOIN perfiles p ON u.idPerfil = p.id_perfil
            ORDER BY u.id DESC
            LIMIT :nuevoinicio, :nroreg
        ";
        $qry2 = $datappi->prepare($query2);
        $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
        $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
        $qry2->execute();
        $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);

        $response = ['rta' => $count, 'rta2' => $rta2];
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(["rta" => $e->getMessage()]);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

// Insertar usuario con validación de propietario
if ($indicador == "3") {
    $documento = $_POST['documento'] ?? $_GET['documento'] ?? "";
    $telefono = $_POST['telefono'] ?? $_GET['telefono'] ?? "";
    $nombre = $_POST['nombre'] ?? $_GET['nombre'] ?? "";
    $correo = $_POST['correo'] ?? $_GET['correo'] ?? "";
    $perfil = $_POST['perfil'] ?? $_GET['perfil'] ?? "";
    $id_residencia = $_POST['id_residencia'] ?? $_GET['id_residencia'] ?? "";
    $estado = 1;

    // Validación: si perfil es 4, verificar que no exista otro propietario en esa residencia
    if ($perfil == 4 && !empty($id_residencia)) {
        $verifica = "
            SELECT COUNT(*) as total 
            FROM usuarios 
            WHERE id_residencia = :id_residencia AND idPerfil = 4
        ";
        $check = $datappi->prepare($verifica);
        $check->bindParam(':id_residencia', $id_residencia);
        $check->execute();
        $yaExiste = $check->fetch(PDO::FETCH_ASSOC)['total'];

        if ($yaExiste > 0) {
            echo json_encode(['rta' => 'residencia_ocupada']);
            exit;
        }
    }

    // Generar código temporal
    $randomCode = mt_rand(100000, 999999);
    $hashedCode = md5($randomCode);

    $query = "INSERT INTO usuarios (documento, nombre_completo, telefono, correo, id_residencia, contrasena, estado, idPerfil, codigoTemporal) 
              VALUES (:documento, :nombre, :telefono, :correo, :id_residencia, :contrasena, :estado, :idPerfil, :codigoTemporal)";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':documento', $documento);
    $qry->bindParam(':nombre', $nombre);
    $qry->bindParam(':telefono', $telefono);
    $qry->bindParam(':correo', $correo);
    $qry->bindParam(':id_residencia', $id_residencia);
    $qry->bindParam(':contrasena', $hashedCode);
    $qry->bindParam(':estado', $estado);
    $qry->bindParam(':idPerfil', $perfil);
    $qry->bindParam(":codigoTemporal", $hashedCode);

    if ($qry->execute()) {
        // Enviar correo
        $url = 'https://ppi.miclickderecho.com/plantilla/email/mailing.php';
        $data = http_build_query([
            'respuesta' => $nombre,
            'correos' => $correo,
            'codigoTemporal' => $randomCode
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($ch);
        curl_close($ch);

        echo json_encode([
            'rta' => 'ok',
            'codigoTemporal' => $randomCode,
            'respuestaCorreo' => $respuesta
        ]);
    } else {
        echo json_encode(['rta' => 'error']);
    }
}

// Actualizar usuarios con validación de propietario en residencia
if ($indicador == "4") {
    $id = $_POST['id'] ?? $_GET['id'] ?? "";
    $documento = $_POST['documento'] ?? $_GET['documento'] ?? "";
    $telefono = $_POST['telefono'] ?? $_GET['telefono'] ?? "";
    $nombre = $_POST['nombre'] ?? $_GET['nombre'] ?? "";
    $correo = $_POST['correo'] ?? $_GET['correo'] ?? "";
    $perfil = $_POST['perfil'] ?? $_GET['perfil'] ?? "";
    $id_residencia = $_POST['id_residencia'] ?? $_GET['id_residencia'] ?? "";

    // Validación: ¿hay otro usuario con esa residencia y perfil 4?
    $verifica = "
        SELECT COUNT(*) as total 
        FROM usuarios 
        WHERE id_residencia = :id_residencia AND idPerfil = 4 AND id != :id
    ";
    $check = $datappi->prepare($verifica);
    $check->bindParam(':id_residencia', $id_residencia);
    $check->bindParam(':id', $id);
    $check->execute();
    $yaExiste = $check->fetch(PDO::FETCH_ASSOC)['total'];

    if ($yaExiste > 0 && $perfil == 4) {
        // Ya hay un propietario en esa residencia
        header('Content-Type: application/json');
        echo json_encode(['rta' => 'residencia_ocupada', 'status' => 'error']);
        exit;
    }

    // Si pasa la validación, hacer el UPDATE
    $query = "
        UPDATE usuarios 
        SET 
            documento = :documento, 
            nombre_completo = :nombre, 
            telefono = :telefono, 
            correo = :correo, 
            id_residencia = :id_residencia, 
            idPerfil = :idPerfil
        WHERE id = :id
    ";
    $qry = $datappi->prepare($query);
    $qry->bindParam(':documento', $documento);
    $qry->bindParam(':nombre', $nombre);
    $qry->bindParam(':telefono', $telefono);
    $qry->bindParam(':correo', $correo);
    $qry->bindParam(':id_residencia', $id_residencia);
    $qry->bindParam(':idPerfil', $perfil);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = "ok";
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(['rta' => $rta, 'status' => 'ok']);
}

//Traer perfiles del sistema 
if ($indicador == "5") {
    $query = "SELECT * FROM perfiles";
    $qry = $datappi->prepare($query);
    if ($qry->execute()) {
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Mostrar datos del usuario con residencia formateada
if ($indicador == "6") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    $query = "
        SELECT 
            u.*, 
            p.nombre_perfil AS nombrePerfil,
            COALESCE(
                CONCAT(
                    UPPER(RIGHT(TRIM(t.nombre_torre), 1)), 
                    r.numero_residencia
                ),
                'Sin Residencia'
            ) AS nombre_residencia_formateada
        FROM usuarios u
        INNER JOIN perfiles p ON u.idPerfil = p.id_perfil
        LEFT JOIN residencias r ON u.id_residencia = r.id_residencia
        LEFT JOIN torres t ON r.id_torre_residencia = t.id_torre
        WHERE u.id = :id
    ";

    $qry = $datappi->prepare($query);
    $qry->bindParam(':id', $id);

    if ($qry->execute()) {
        $rta = $qry->fetch(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(['rta' => $rta]);
}

//Eliminar usuarios
if ($indicador == "7") {
    // Verificar el valor de $id
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    // Depurar el ID recibido
    if (empty($id)) {
        echo json_encode(['rta' => 'error', 'message' => 'ID no recibido']);
        exit;
    }

    try {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);

        if ($qry->execute()) {
            $rta = "ok";
        } else {
            // Obtener error de PDO
            $errorInfo = $qry->errorInfo();
            $rta = "error";
            echo json_encode(['rta' => $rta, 'error' => $errorInfo]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['rta' => 'error', 'exception' => $e->getMessage()]);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['rta' => $rta]);
}

// Traer residencias formateadas para el selector
if ($indicador == "8") {
    $query = "
        SELECT 
            r.id_residencia, 
            r.numero_residencia, 
            t.nombre_torre
        FROM residencias r
        JOIN torres t ON r.id_torre_residencia = t.id_torre
        ORDER BY t.nombre_torre, r.numero_residencia
    ";

    $qry = $datappi->prepare($query);
    if ($qry->execute()) {
        $result = $qry->fetchAll(PDO::FETCH_ASSOC);

        // Formatear etiqueta personalizada
        $rta = array_map(function ($item) {
            $ultimaLetra = strtoupper(substr(trim($item['nombre_torre']), -1)); // Última letra torre
            return [
                "id_residencia" => $item["id_residencia"],
                "nombre_residencia" => $ultimaLetra . $item["numero_residencia"]
            ];
        }, $result);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(['rta' => $rta]);
}

if ($indicador == "9") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    

    try {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);

        if ($qry->execute()) {
            $rta = "ok";
        } else {
            // Obtener error de PDO
            $errorInfo = $qry->errorInfo();
            $rta = "error";
            echo json_encode(['rta' => $rta, 'error' => $errorInfo]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['rta' => 'error', 'exception' => $e->getMessage()]);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['rta' => $rta]);
}

if ($indicador == "10") {
    $idUsuario = $_POST['idUsuario'] ?? null;
    $nuevaContrasena = $_POST['nuevaContrasena'] ?? null;

    if (empty($idUsuario) || empty($nuevaContrasena)) {
        echo json_encode(['rta' => 'datos_incompletos']);
        exit;
    }
    $hashedPassword = md5($nuevaContrasena); 

    $updatePassword = "UPDATE usuarios SET contrasena = :contrasena WHERE usuarios.id = :idusuario";
    $stmt = $datappi->prepare($updatePassword);
    $stmt->bindParam(':contrasena', $hashedPassword);
    $stmt->bindParam(':idusuario', $idUsuario);

    if ($stmt->execute()) {
        echo json_encode(['rta' => 'ok']);
    } else {
        echo json_encode(['rta' => 'error_actualizando']);
    }
}

if ($indicador == "11") {
    $query = "SELECT 
    usuarios.documento,
    usuarios.nombre_completo,
    usuarios.telefono,
    usuarios.correo,
    e.nombre 
FROM 
    usuarios
JOIN 
    estados e ON e.id = usuarios.estado
WHERE 
    usuarios.estado = 1
ORDER BY 
    usuarios.nombre_completo
LIMIT 100;";
    $qry = $datappi->prepare($query);
    if ($qry->execute()) {
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

if ($indicador == "12") {
    $query = "SELECT * FROM residencias;";
    $qry = $datappi->prepare($query);
    if ($qry->execute()) {
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

if ($indicador == "13") {
    $query = "SELECT 
    q.descripcion,
    q.estado,
    q.fecha_creacion,
    u.documento,
    u.correo,
    u.telefono
FROM 
    quejas q
JOIN 
    usuarios u ON u.id = q.idUser;
;";
    $qry = $datappi->prepare($query);
    if ($qry->execute()) {
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}