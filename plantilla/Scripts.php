<?php
// session_start();

include "assets/config.php";
// include "../assets/config2.php";



$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");

//Leer los datos
if ($indicador == "1") {
    
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    try {
        // Consultar datos del producto con INNER JOIN en categorias
        $query = "SELECT COUNT(e.id_opcion) as total from elecciones e WHERE e.id_opcion=:id";//dos puntos pdo le pasa por debajo
        $qry = $datappi->prepare($query);
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
        echo 'Error al obtener datos del producto: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(array('' => $productData));
}

//Mostrar los datos del cliente
if ($indicador == "2") {
    
    try {
        // Conteo de productos para la sucursal específica
        $query = "SELECT e.id_opcion, COUNT(*) AS Total FROM elecciones e  GROUP BY e.id_opcion ORDER BY Total DESC";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);

    } catch (PDOException $e) {
        echo 'Error al obtener datos del producto: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(array('' => $productData));
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

if ($indicador == "votacion2") {
    
    try {
        // Conteo de productos para la sucursal específica
        $query = "SELECT o.descripcion,e.id_opcion, COUNT(*) AS Total FROM elecciones e 
            inner join opciones o 
            inner join votaciones v 
            where e.id_opcion = o.id 
            AND v.id = o.id_votacion 
            AND o.id_votacion = 1
            GROUP BY e.id_opcion ORDER BY Total DESC";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);

    } catch (PDOException $e) {
        echo 'Error al obtener datos del producto: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(array('' => $productData));
}
if ($indicador == "votacion3") {
    
    try {
        // Conteo de productos para la sucursal específica
        $query = "WITH VotosPorTitulo AS (
            SELECT v.id,v.titulo, o.descripcion,e.id_opcion,COUNT(*) AS Total, 
            ROW_NUMBER() OVER(PARTITION BY v.titulo ORDER BY COUNT(*) DESC) AS rn
            FROM elecciones e    
            INNER JOIN opciones o      
            ON e.id_opcion = o.id  
            INNER JOIN votaciones v   
            ON v.id = o.id_votacion  
            GROUP BY v.id, v.titulo, o.descripcion, e.id_opcion
        )
        SELECT id, titulo, descripcion, id_opcion, Total
        FROM VotosPorTitulo
        WHERE rn = 1";
        $qry = $datappi->prepare($query);
        $qry->execute();
        $productData = $qry->fetchAll(PDO::FETCH_OBJ);

    } catch (PDOException $e) {
        echo 'Error al obtener datos del producto: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode(array('' => $productData));
}

//Insertar Producto
if ($indicador == "3") {
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    try {
        $nom_producto = isset($_POST['nom_producto']) ? $_POST['nom_producto'] : "";
        $idCategoria = isset($_POST['categoria']) ? $_POST['categoria'] : "";
        $tipo_categoria = isset($_POST['tipoCategoria']) ? $_POST['tipoCategoria'] : "";
        $sku = isset($_POST['sku']) ? $_POST['sku'] : "";
        $idMarca = isset($_POST['marca']) ? $_POST['marca'] : "";
        $unidades = isset($_POST['unidades']) ? $_POST['unidades'] : "";
        $costo = isset($_POST['costo']) ? $_POST['costo'] : "";
        $costo_compra = isset($_POST['costo_compra']) ? $_POST['costo_compra'] : "";
        $creacion = $_SESSION['pos']['id'];
        $proveedor = isset($_POST['proveedor']) ? $_POST['proveedor'] : "";
        $stock_max = isset($_POST['stock_max']) ? $_POST['stock_max'] : "";
        $stock_min = isset($_POST['stock_min']) ? $_POST['stock_min'] : "";
        $stock_maneja = isset($_POST['stock_maneja']) ? $_POST['stock_maneja'] : "";
        $stock_sugerido = isset($_POST['stock_sugerido']) ? $_POST['stock_sugerido'] : "";
        $estado = isset($_POST['estado']) ? $_POST['estado'] : "";
        $caducidad = isset($_POST['caducidad']) ? $_POST['caducidad'] : "";
        $manejoInventario = isset($_POST['maneja_inventario']) ? $_POST['maneja_inventario'] : "";
        $cambiaFoto = isset($_POST['cambiaFoto']) ? $_POST['cambiaFoto'] : (!empty($_GET['cambiaFoto']) ? $_GET['cambiaFoto'] : "");

        $imagen_producto = "https://pos.metaversomsp.com/files/imgs/descarga.png";

        $query = "INSERT INTO productos (nom_producto, idCategoria, sku, idMarca, unidades, costo_compra, costo, creacion, idProveedor, stock_max, stock_min, stock_maneja, stock_sugerido, estado, imagen_producto, receta, caducidad, maneja_inventario) 
            VALUES (:nom_producto, :idCategoria, :sku, :idMarca, :unidades, :costo_compra, :costo, :creacion, :idProveedor, :stock_max, :stock_min, :stock_maneja, :stock_sugerido, :estado, :imagen_producto, :receta, :caducidad, :maneja_inventario)";

        $qry = $datapos->prepare($query);
        $qry->bindParam(':nom_producto', $nom_producto);
        $qry->bindParam(':idCategoria', $idCategoria);
        $qry->bindParam(':sku', $sku);
        $qry->bindParam(':idMarca', $idMarca);
        $qry->bindParam(':unidades', $unidades);
        $qry->bindParam(':costo_compra', $costo_compra);
        $qry->bindParam(':costo', $costo);
        $qry->bindParam(':creacion', $creacion);
        $qry->bindParam(':idProveedor', $proveedor);
        $qry->bindParam(':stock_max', $stock_max);
        $qry->bindParam(':stock_min', $stock_min);
        $qry->bindParam(':stock_maneja', $stock_maneja);
        $qry->bindParam(':stock_sugerido', $stock_sugerido);
        $qry->bindParam(':estado', $estado);
        $qry->bindParam(':imagen_producto', $imagen_producto);
        $qry->bindParam(':receta', $tipo_categoria);
        $qry->bindParam(':caducidad', $caducidad);
        $qry->bindParam(':maneja_inventario', $manejoInventario);

        if ($qry->execute()) {
            $rta = "ok";

            // Obtener el último ID insertado en la tabla productos
            $queryId = "SELECT id FROM productos ORDER BY id DESC LIMIT 1;";
            $qryId = $datapos->prepare($queryId);
            $qryId->execute();
            $rtaId = $qryId->fetch(PDO::FETCH_OBJ);

            // Obteniendo el ID de la respuesta
            $idProducto = $rtaId->id;

            $sucursales = explode(",", $_POST['sucursal_ids']); // asumimos que las sucursales están llegando como un array
            foreach ($sucursales as $idSucursal) {
                try {
                    $querySucursal = "INSERT INTO productos_sucursales (idProducto, idSucursal) VALUES (:idProducto, :idSucursal)";
                    $qrySucursal = $datapos->prepare($querySucursal);
                    $qrySucursal->bindParam(':idProducto', $idProducto);
                    $qrySucursal->bindParam(':idSucursal', $idSucursal);

                    $qrySucursal->execute();
                } catch (PDOException $e) {
                    echo json_encode(array('success' => false, 'message' => 'Error al insertar en la tabla productos_sucursales: ' . $e->getMessage()));
                    exit;
                }
            }

            // Código para manejar la imagen predeterminada
            if ($cambiaFoto == "si") {
                // Obtener el archivo enviado desde JavaScript
                $archivo = $_FILES['archivoPersonita']['tmp_name'];
                $formatoImagen = isset($_POST['formatoImagen']) ? $_POST['formatoImagen'] : (!empty($_GET['formatoImagen']) ? $_GET['formatoImagen'] : "");


                if (file_exists($archivo)) {
                    $tamanioArchivo = filesize($archivo);
                    // echo "Tamaño del archivo: " . $tamanioArchivo . " bytes";
                } else {
                    echo "El archivo no existe o no se pudo encontrar.";
                }

                $carpeta = "/www/pos.metaversomsp.com/files/Productos/" . "P-" . $idProducto;
                //============================================= 
                $ftp_server = "216.246.113.247";
                $ftp_user_name = "personitas";
                $ftp_user_pass = "Credimas2023+-*";
                $ch = curl_init();
                $fp = fopen($archivo, "r");

                curl_setopt($ch, CURLOPT_URL, "ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . $carpeta . "/" . $idProducto . "_predeterminada" . "." . $formatoImagen);
                // URL para descargar fichero

                curl_setopt($ch, CURLOPT_FTP_CREATE_MISSING_DIRS, CURLFTP_CREATE_DIR_RETRY);
                curl_setopt($ch, CURLOPT_UPLOAD, 1);
                curl_setopt($ch, CURLOPT_INFILE, $fp);
                curl_setopt($ch, CURLOPT_INFILESIZE, filesize($archivo));
                curl_exec($ch);
                $error_no = curl_errno($ch);
                curl_close($ch);

                if ($error_no == 0) {
                    $respConFtp = "ok";
                } else {
                    $respConFtp = "error" . $error_no;
                }

                // Construyendo la URL FTP con la estructura deseada
                $ftp_url = "https://pos.metaversomsp.com/files/Productos/P-" . $idProducto . "/" . $idProducto . "_predeterminada." . $formatoImagen;

                // Preparando la consulta para actualizar la imagen en la base de datos
                $updateProductQuery = "UPDATE productos SET imagen_producto = :imagen_producto WHERE id = :id";
                $updateProductStatement = $datapos->prepare($updateProductQuery);
                $updateProductStatement->bindParam(':imagen_producto', $ftp_url);
                $updateProductStatement->bindParam(':id', $idProducto);

                // Ejecutando la consulta
                if (!$updateProductStatement->execute()) {
                    echo json_encode('Error en la consulta de actualización de la imagen');
                    exit;
                }
            }
        }
    } catch (PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()));
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'message' => 'Error general: ' . $e->getMessage()));
    }
    echo json_encode(array('success' => true, 'message' => 'Producto, imágenes y sucursales añadidos exitosamente.'));
}

// Editar Producto
if ($indicador == "4") {
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    try {
        // Recuperar datos del formulario
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $nom_producto = isset($_POST['nom_producto']) ? $_POST['nom_producto'] : "";
        $idCategoria = isset($_POST['categoria']) ? $_POST['categoria'] : "";
        $sku = isset($_POST['sku']) ? $_POST['sku'] : "";
        $idMarca = isset($_POST['marca']) ? $_POST['marca'] : "";
        $unidades = isset($_POST['unidades']) ? $_POST['unidades'] : "";
        $costo_compra = isset($_POST['costo_compra']) ? $_POST['costo_compra'] : "";
        $costo = isset($_POST['costo']) ? $_POST['costo'] : "";
        $creacion = $_SESSION['pos']['id'];
        $proveedor = isset($_POST['proveedor']) ? $_POST['proveedor'] : "";
        $stock_max = isset($_POST['stock_max']) ? $_POST['stock_max'] : "";
        $stock_min = isset($_POST['stock_min']) ? $_POST['stock_min'] : "";
        $stock_maneja = isset($_POST['stock_maneja']) ? $_POST['stock_maneja'] : "";
        $stock_sugerido = isset($_POST['stock_sugerido']) ? $_POST['stock_sugerido'] : "";
        $estado = isset($_POST['estado']) ? $_POST['estado'] : "";
        $caducidad = isset($_POST['caducidad']) ? $_POST['caducidad'] : "";
        $maneja_inventario = isset($_POST['maneja_inventario']) ? $_POST['maneja_inventario'] : "";



        // Verificar si se cambió la foto
        $cambiaFoto = isset($_POST['cambiaFoto']) ? $_POST['cambiaFoto'] : (!empty($_GET['cambiaFoto']) ? $_GET['cambiaFoto'] : "");

        // Si se cambió la foto, manejarla
        if ($cambiaFoto == "si" && isset($_FILES['archivoPersonita']['tmp_name']) && file_exists($_FILES['archivoPersonita']['tmp_name'])) {
            $formatoImagen = isset($_POST['formatoImagen']) ? $_POST['formatoImagen'] : (!empty($_GET['formatoImagen']) ? $_GET['formatoImagen'] : "");

            // Generar un número aleatorio de 4 dígitos
            $randomNumber = rand(1000, 9999);

            // Construir el nuevo nombre del archivo con el número aleatorio
            $nuevoNombreArchivo = $id . "predeterminada" . $randomNumber . "." . $formatoImagen;

            if (isset($_FILES['archivoPersonita']['tmp_name'])) {
                $archivo = $_FILES['archivoPersonita']['tmp_name'];
                // Continúa con la lógica para manejar el archivo subido
            }
            $carpeta = "/www/pos.metaversomsp.com/files/Productos/" . "P-" . $id;
            $ftp_server = "216.246.113.247";
            $ftp_user_name = "personitas";
            $ftp_user_pass = "Credimas2023+-*";


            // Subir la imagen al servidor FTP
            $ch = curl_init();
            $archivo = $_FILES['archivoPersonita']['tmp_name'];
            $fp = fopen($archivo, "r");

            // Verifica si el archivo se pudo abrir correctamente
            if ($fp !== false) {
                curl_setopt($ch, CURLOPT_URL, "ftp://" . $ftp_user_name . ":" . $ftp_user_pass . "@" . $ftp_server . $carpeta . "/" . $nuevoNombreArchivo);
                curl_setopt($ch, CURLOPT_FTP_CREATE_MISSING_DIRS, CURLFTP_CREATE_DIR_RETRY);
                curl_setopt($ch, CURLOPT_UPLOAD, 1);
                curl_setopt($ch, CURLOPT_INFILE, $fp); // Asegúrate de que esta línea esté antes de fclose($fp)
                curl_setopt($ch, CURLOPT_INFILESIZE, filesize($archivo));
                curl_exec($ch);
                $error_no = curl_errno($ch);

                fclose($fp); // Mueve fclose($fp) aquí, después de las operaciones de cURL

                if ($error_no == 0) {
                    $respConFtp = "ok";
                } else {
                    $respConFtp = "error" . $error_no;
                    echo json_encode($respConFtp);
                    die();
                }
            } else {
                // Manejar el error de apertura de archivo aquí
                echo json_encode(['success' => false, 'message' => 'Error al abrir el archivo para subir.']);
                die();
            }
            curl_close($ch);

            if ($error_no == 0) {
                $respConFtp = "ok";
            } else {
                $respConFtp = "error" . $error_no;
                echo json_encode($respConFtp);
                die();
            }

            // URL de la imagen en el servidor FTP
            $ftp_url = "https://pos.metaversomsp.com/files/Productos/P-" . $id . "/" . $nuevoNombreArchivo;


            // Actualizar la base de datos con la URL de la nueva imagen
            $queryFoto = "UPDATE productos SET imagen_producto = :imagen_producto WHERE id = :id";
            $exec = $datapos->prepare($queryFoto);
            $exec->bindParam(':imagen_producto', $ftp_url);
            $exec->bindParam(':id', $id); // ¡Cuidado! Deberías asegurarte de que esta variable esté definida en algún lugar.
            $result = $exec->execute();
            if (!$result) {
                $error = $exec->errorInfo();
                echo json_encode(['success' => false, 'message' => 'Error en la consulta de la foto: ' . $error[2]]);
                die();
            }
        }

        // Actualizar los demás datos del producto en la base de datos
        $query = "UPDATE productos SET nom_producto = :nom_producto, idCategoria = :idCategoria, sku = :sku, idMarca = :idMarca, unidades = :unidades, costo_compra = :costo_compra, costo = :costo, creacion = :creacion, idProveedor = :idProveedor, stock_max = :stock_max, stock_min = :stock_min, stock_maneja = :stock_maneja, stock_sugerido = :stock_sugerido, estado = :estado, caducidad = :caducidad, maneja_inventario =:maneja_inventario  WHERE id = :id";

        $qry = $datapos->prepare($query);
        $qry->bindParam(':id', $id);
        $qry->bindParam(':nom_producto', $nom_producto);
        $qry->bindParam(':idCategoria', $idCategoria);
        $qry->bindParam(':sku', $sku);
        $qry->bindParam(':idMarca', $idMarca);
        $qry->bindParam(':unidades', $unidades);
        $qry->bindParam(':costo_compra', $costo_compra);
        $qry->bindParam(':costo', $costo);
        $qry->bindParam(':creacion', $creacion);
        $qry->bindParam(':idProveedor', $proveedor);
        $qry->bindParam(':stock_max', $stock_max);
        $qry->bindParam(':stock_min', $stock_min);
        $qry->bindParam(':stock_maneja', $stock_maneja);
        $qry->bindParam(':stock_sugerido', $stock_sugerido);
        $qry->bindParam(':estado', $estado);
        $qry->bindParam(':caducidad', $caducidad);
        $qry->bindParam(':maneja_inventario', $maneja_inventario);
        $qry->execute();
        $actualizacionExitosa = $qry->rowCount() > 0;

        $checkboxesCambiados = json_decode(isset($_POST['checkboxesCambiados']) ? $_POST['checkboxesCambiados'] : "[]");
        $checkboxesNuevosSeleccionados = json_decode(isset($_POST['checkboxesNuevosSeleccionados']) ? $_POST['checkboxesNuevosSeleccionados'] : "[]");

        try {
            // Eliminar registros de productos_sucursales para los checkboxes desmarcados
            foreach ($checkboxesCambiados as $idSucursal) {
                $deleteQuery = "DELETE FROM productos_sucursales WHERE idProducto = :idProducto AND idSucursal = :idSucursal";
                $deleteStmt = $datapos->prepare($deleteQuery);
                $deleteStmt->bindParam(':idProducto', $id);
                $deleteStmt->bindParam(':idSucursal', $idSucursal);
                $deleteStmt->execute();
                if ($deleteStmt->rowCount() > 0) {
                    $actualizacionExitosa = true;
                }
            }

            // Insertar registros nuevos para los checkboxes marcados
            foreach ($checkboxesNuevosSeleccionados as $idSucursal) {
                $insertQuery = "INSERT INTO productos_sucursales (idProducto, idSucursal) VALUES (:idProducto, :idSucursal)";
                $insertStmt = $datapos->prepare($insertQuery);
                $insertStmt->bindParam(':idProducto', $id);
                $insertStmt->bindParam(':idSucursal', $idSucursal);
                $insertStmt->execute();
                if ($insertStmt->rowCount() > 0) {
                    $actualizacionExitosa = true;
                }
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar los checkboxes: ' . $e->getMessage()]);
            exit;
        }

        // Manejar la respuesta
        if (!$actualizacionExitosa) {
            echo json_encode(['success' => true, 'message' => 'No se detectaron cambios en el producto.']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Producto actualizado con éxito.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
    }
}


//Eliminar imagen especifica
if ($indicador == "5") {
    // Recibir el ID de la imagen
    $imageId = $_POST['imageId'];

    // Crear la consulta SQL
    $query = "DELETE FROM img_producto WHERE id = ?";

    // Preparar y ejecutar la consulta
    $qry = $datapos->prepare($query);
    $qry->execute([$imageId]); // Se pasa un array con un solo ID para ejecutar la consulta

    $response = ['success' => true];
    if ($qry->rowCount() === 0) {
        $response['success'] = false;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

//Leer los datos detallados del producto
if ($indicador == "6") {
    $id = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");

    // Consultar datos del producto junto con el nombre completo del usuario que lo creó
    $query = "SELECT productos.*, CONCAT(usuarios.nombre, ' ', usuarios.apellido) as nombre 
              FROM productos 
              INNER JOIN usuarios ON productos.creacion = usuarios.id 
              WHERE productos.id = :id";
    $qry = $datapos->prepare($query);
    $qry->bindParam(':id', $id, PDO::PARAM_INT);
    $qry->execute();
    $productData = $qry->fetchAll(PDO::FETCH_OBJ);

    // Combinar la respuesta en un solo objeto JSON
    $response = [
        'product' => $productData
    ];

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $response));
}

//Rellenar selector de categorias
if ($indicador == "7") {
    $query = "SELECT * FROM categorias";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Rellenar selector de categorias
if ($indicador == "8") {
    $query = "SELECT * FROM marcas ORDER BY nom_marca ASC";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Rellenar selector de categorias
if ($indicador == "9") {
    $query = "SELECT * FROM usuarios";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Filtrar productos
if ($indicador == "10") {
    try {
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
        $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : "";
        $sku = isset($_POST['sku']) ? trim($_POST['sku']) : "";
        $nuevoinicio = isset($_POST['nuevoinicio']) ? $_POST['nuevoinicio'] : "";
        $nroreg = isset($_POST['nroreg']) ? $_POST['nroreg'] : "";
        $idSucursal = $_SESSION['pos']['idSucursal'];

        $nuevoinicio = (int) $nuevoinicio;
        $nroreg = (int) $nroreg;

        // Consulta para obtener el conteo total
        $query = "SELECT COUNT(pr.id) AS count FROM productos pr 
        INNER JOIN categorias ct ON ct.id = pr.idCategoria
        INNER JOIN productos_sucursales ps ON pr.id = ps.idProducto ";

        // Agregar filtros a la consulta
        $filters = [];
        if ($nombre != "") {
            $filters[] = "pr.nom_producto LIKE '%$nombre%'";
        }
        if ($categoria != "") {
            $filters[] = "pr.idCategoria = $categoria";
        }
        if ($sku != "") {
            $filters[] = "pr.sku = '$sku'";
        }
        if (count($filters) > 0) {
            $query .= "WHERE " . implode(" AND ", $filters) . " AND ps.idSucursal = :idSucursal ";
        }

        $qry = $datapos->prepare($query);
        $qry->bindParam(":idSucursal", $idSucursal, PDO::PARAM_INT);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        // Consulta para obtener productos paginados
        $query2 = "SELECT pr.*, ct.nom_categoria FROM productos pr 
        INNER JOIN categorias ct ON ct.id = pr.idCategoria
        INNER JOIN productos_sucursales ps ON pr.id = ps.idProducto";
        // Asegúrate de que la consulta incluya la condición de idSucursal para la segunda consulta también
        if (count($filters) > 0) {
            $query2 .= " WHERE " . implode(" AND ", $filters) . " AND ps.idSucursal = :idSucursal";
        } else {
            $query2 .= " WHERE ps.idSucursal = :idSucursal";
        }
        $query2 .= " ORDER BY pr.id DESC LIMIT :nuevoinicio, :nroreg";

        $qry2 = $datapos->prepare($query2);
        $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
        $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
        $qry2->bindParam(":idSucursal", $idSucursal, PDO::PARAM_INT); // Asegúrate de bindear este parámetro
        $qry2->execute();
        $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);

        $response = array('rta' => $count, 'rta2' => $rta2);
        echo json_encode($response);
    } catch (PDOException $e) {
        // Atrapar y manejar la excepción
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array('error' => $e->getMessage()));
    }
    header('Content-Type: application/json');
}


//Rellenar selector para buscar por categorias
if ($indicador == "11") {
    $query = "SELECT * FROM categorias";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Rellenar selector de Proveedores
if ($indicador == "12") {
    $query = "SELECT * FROM proveedores";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Llenar los checkbox de las sucursales
if ($indicador == "13") {
    $query = "SELECT * FROM sucursales";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}
//Llenar sucursales del producto
if ($indicador == "13.1") {
    $idProducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : (!empty($_GET['idproducto']) ? $_GET['idproducto'] : "");

    try {
        // Modificación aquí: Agregar INNER JOIN para unir con la tabla sucursales
        $query = "SELECT ps.idSucursal, b.id 
                  FROM productos_sucursales ps 
                  INNER JOIN bodegas b ON ps.idSucursal = b.idSucursal 
                  WHERE ps.idProducto = :idProducto";

        $qry = $datapos->prepare($query);
        $qry->bindParam(':idProducto', $idProducto);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);

        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error en la consulta: ' . $e->getMessage()));
    }
}

//Validar Sku
if ($indicador == "14") {
    $sku = trim(isset($_POST['sku']) ? $_POST['sku'] : (!empty($_GET['sku']) ? $_GET['sku'] : ""));
    $idproducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : (!empty($_GET['idproducto']) ? $_GET['idproducto'] : "");

    try {
        $query = "SELECT COUNT(*) as existe FROM productos WHERE sku = :sku AND id != :idproducto";
        $qry = $datapos->prepare($query);
        $qry->bindParam(':sku', $sku);
        $qry->bindParam(':idproducto', $idproducto);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);

        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error al validar SKU: ' . $e->getMessage()));
    }
}

//Traer las variables de sesion y retornarlas
if ($indicador == "15") {
    $idSucursal = $_SESSION['pos']['idSucursal'];
    $idUsuario = $_SESSION['pos']['id'];
    $nombreUsuario = $_SESSION['pos']['nombre'];
    $apellidoUsuario = $_SESSION['pos']['apellido'];
    $nombreSucursal = $_SESSION['pos']['nombreSucursal'];
    $rta = array('idSucursal' => $idSucursal, 'idUsuario' => $idUsuario, 'nombreUsuario' => $nombreUsuario, 'apellidoUsuario' => $apellidoUsuario, 'nombreSucursal' => $nombreSucursal);
    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Buscar Saldo de producto
if ($indicador == "16") {
    $idproducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : (!empty($_GET['idproducto']) ? $_GET['idproducto'] : "");
    $idBodega = isset($_POST['idBodega']) ? $_POST['idBodega'] : (!empty($_GET['idBodega']) ? $_GET['idBodega'] : "");

    try {
        $query = "SELECT SUM(entra)-SUM(sale) AS saldo FROM bodega_" . $idBodega . " WHERE idproducto = :idproducto;";
        $qry = $datapos->prepare($query);
        $qry->bindParam(':idproducto', $idproducto);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);

        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error al validar el saldo del producto: ' . $e->getMessage()));
    }
}

//Llenar tarerse las bodegas de los checkbox de la sucursal
if ($indicador == "17") {
    $idSucursal = isset($_POST['idSucursal']) ? $_POST['idSucursal'] : (!empty($_GET['idSucursal']) ? $_GET['idSucursal'] : "");

    try {
        // Consulta para obtener los id de bodegas basados en idSucursal
        $query = "SELECT id FROM bodegas WHERE idSucursal = :idSucursal";

        $qry = $datapos->prepare($query);
        $qry->bindParam(':idSucursal', $idSucursal);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);

        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error en la consulta: ' . $e->getMessage()));
    }
}

//Buscar productos de las bodegas
if ($indicador == "17.1") {
    $idproducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : (!empty($_GET['idproducto']) ? $_GET['idproducto'] : "");
    $idBodega = isset($_POST['idBodega']) ? $_POST['idBodega'] : (!empty($_GET['idBodega']) ? $_GET['idBodega'] : "");

    try {
        $query = "SELECT SUM(entra)-SUM(sale) AS saldo FROM bodega_" . $idBodega . " WHERE idproducto = :idproducto;";
        $qry = $datapos->prepare($query);
        $qry->bindParam(':idproducto', $idproducto);
        $qry->execute();
        $rta = $qry->fetchAll(PDO::FETCH_OBJ);

        header('Content-Type: application/json');
        echo json_encode(array('rta' => $rta));
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error al validar el saldo del producto: ' . $e->getMessage()));
    }
}

//Eliminar Producto de Bodega y Tablas Relacionadas
if ($indicador == "18") {
    $idProducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : "";
    $idBodega = isset($_POST['idBodega']) ? $_POST['idBodega'] : "";

    // Verificar si hay un producto en la bodega
    $queryVerificar = "SELECT COUNT(*) AS cantidad FROM bodega_" . $idBodega . " WHERE idproducto = :idproducto;";
    $qryVerificar = $datapos->prepare($queryVerificar);
    $qryVerificar->bindParam(':idproducto', $idProducto);

    try {
        $qryVerificar->execute();
        $resultadoVerificar = $qryVerificar->fetch(PDO::FETCH_ASSOC);
        if ($resultadoVerificar['cantidad'] > 0) {
            // Eliminar el producto de la bodega
            $queryBodega = "DELETE FROM bodega_" . $idBodega . " WHERE idproducto = :idproducto;";
            $qryBodega = $datapos->prepare($queryBodega);
            $qryBodega->bindParam(':idproducto', $idProducto);
            $qryBodega->execute();
        }
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al verificar o eliminar el producto de la bodega: ' . $e->getMessage()));
        return;
    }

    // Eliminar el producto de productos_sucursales
    try {
        $querySucursal = "DELETE FROM productos_sucursales WHERE idProducto = :idProducto;";
        $qrySucursal = $datapos->prepare($querySucursal);
        $qrySucursal->bindParam(':idProducto', $idProducto);
        $qrySucursal->execute();
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al eliminar el producto de productos_sucursales: ' . $e->getMessage()));
        return;
    }

    // Eliminar el producto de la tabla productos
    try {
        $queryProducto = "DELETE FROM productos WHERE id = :idProducto;";
        $qryProducto = $datapos->prepare($queryProducto);
        $qryProducto->bindParam(':idProducto', $idProducto);
        $qryProducto->execute();
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Error al eliminar el producto de productos: ' . $e->getMessage()));
        return;
    }

    echo json_encode(array('success' => true, 'message' => 'Producto eliminado correctamente.'));
}

// Actualizar receta de producto a si
if ($indicador == "19") {
    $idProducto = isset($_POST['idProducto']) ? $_POST['idProducto'] : "";
    $receta = isset($_POST['receta']) ? $_POST['receta'] : "";

    try {
        // Preparar la consulta SQL
        $query = "UPDATE productos SET receta = :receta WHERE id = :idProducto;";
        $qry = $datapos->prepare($query);

        // Vincular los parámetros a la consulta
        $qry->bindParam(':idProducto', $idProducto);
        $qry->bindParam(':receta', $receta);

        // Ejecutar la consulta
        $qry->execute();

        // Enviar respuesta exitosa
        echo json_encode(array('success' => true, 'message' => 'Receta actualizada correctamente.'));
    } catch (PDOException $e) {
        // Capturar y manejar la excepción
        echo json_encode(array('error' => 'Error al actualizar la receta: ' . $e->getMessage()));
    }
}

//Rellenar selector para buscar los productos que tengan como tipo de categoria insumos o procesados
if ($indicador == "20") {
    $idSucursal = $_SESSION['pos']['idSucursal'];
    $query = "SELECT productos.*, unidades.nombre_unidad, categorias.tipo_categoria FROM productos 
              INNER JOIN categorias ON productos.idCategoria = categorias.id 
              INNER JOIN productos_sucursales ON productos.id = productos_sucursales.idProducto 
              INNER JOIN unidades ON productos.unidades = unidades.id
              WHERE categorias.tipo_categoria IN ('insumos', 'procesados','Productos') AND productos_sucursales.idSucursal = :idSucursal";
    $qry = $datapos->prepare($query);
    $qry->bindParam(':idSucursal', $idSucursal);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

// Insertar Ingredientes
if ($indicador == "21") {
    try {
        $idproducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : "";
        $idIngrediente = isset($_POST['idIngrediente']) ? $_POST['idIngrediente'] : "";
        $nombreIngrediente = isset($_POST['nombreIngrediente']) ? $_POST['nombreIngrediente'] : "";
        $costo = isset($_POST['costo']) ? $_POST['costo'] : "";
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : "";
        $unidad = isset($_POST['unidad']) ? $_POST['unidad'] : "";
        $responsable = $_SESSION['pos']['id'];
        $nuevoinicio = isset($_POST['nuevoinicio']) ? $_POST['nuevoinicio'] : "";
        $nroreg = isset($_POST['nroreg']) ? $_POST['nroreg'] : "";

        $query = "INSERT INTO productos_ingredientes (idproducto, idIngrediente, nombre_ingrediente, costo, cantidad, unidad) 
                VALUES (:idproducto, :idIngrediente, :nombreIngrediente, :costo, :cantidad, :unidad)";

        $qry = $datapos->prepare($query);
        $qry->bindParam(':idproducto', $idproducto);
        $qry->bindParam(':idIngrediente', $idIngrediente);
        $qry->bindParam(':nombreIngrediente', $nombreIngrediente);
        $qry->bindParam(':costo', $costo);
        $qry->bindParam(':cantidad', $cantidad);
        $qry->bindParam(':unidad', $unidad);

        if ($qry->execute()) {
            $last_id = $datapos->lastInsertId();

            // Segunda consulta para obtener todos los detalles del movimiento
            $query2 = "SELECT * FROM compras_detalle WHERE idcompra = :idcompra LIMIT :nuevoinicio, :nroreg";
            $qry2 = $datapos->prepare($query2);
            $qry2->bindParam(':idcompra', $idcompra);
            $qry2->bindParam(':nuevoinicio', $nuevoinicio, PDO::PARAM_INT);
            $qry2->bindParam(':nroreg', $nroreg, PDO::PARAM_INT);
            $qry2->execute();
            $details = $qry2->fetchAll(PDO::FETCH_ASSOC);

            // Tercera consulta para contar todos los registros
            $query3 = "SELECT COUNT(*) as count FROM compras_detalle WHERE idcompra = :idcompra";
            $qry3 = $datapos->prepare($query3);
            $qry3->bindParam(':idcompra', $idcompra);
            $qry3->execute();
            $count = $qry3->fetch(PDO::FETCH_ASSOC)['count'];

            $rta = array('status' => 'ok', 'last_id' => $last_id, 'details' => $details, 'rta' => $count);
        } else {
            $rta = array('status' => 'error');
        }
    } catch (PDOException $e) {
        $rta = array('status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage());
    } catch (Exception $e) {
        $rta = array('status' => 'error', 'message' => 'Error general: ' . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($rta);
}

//Pintar tabla maestra de productos
if ($indicador == "22") {
    $idproducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : "";
    $nuevoinicio = isset($_POST['nuevoinicio']) ? $_POST['nuevoinicio'] : "";
    $nroreg = isset($_POST['nroreg']) ? $_POST['nroreg'] : "";

    // Segunda consulta para obtener todos los detalles del movimiento
    $query2 = "SELECT * FROM productos_ingredientes 
               WHERE idproducto = :idproducto 
               ORDER BY idproducto DESC 
               LIMIT :nuevoinicio, :nroreg";

    $qry2 = $datapos->prepare($query2);
    $qry2->bindParam(':idproducto', $idproducto);
    $qry2->bindParam(':nuevoinicio', $nuevoinicio, PDO::PARAM_INT);
    $qry2->bindParam(':nroreg', $nroreg, PDO::PARAM_INT);
    $qry2->execute();
    $details = $qry2->fetchAll(PDO::FETCH_ASSOC);

    // Tercera consulta para contar todos los registros
    $query3 = "SELECT COUNT(*) as count 
               FROM productos_ingredientes 
               WHERE idproducto = :idproducto"; // Añadiendo la condición idSucursal

    $qry3 = $datapos->prepare($query3);
    $qry3->bindParam(':idproducto', $idproducto);
    $qry3->execute();
    $count = $qry3->fetch(PDO::FETCH_ASSOC)['count'];

    $rta = array('details' => $details, 'rta' => $count);

    header('Content-Type: application/json');
    echo json_encode($rta);
}

//Rellenar selector de unidades
if ($indicador == "23") {
    $query = "SELECT * FROM unidades";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Eliminar detalle del ingrediente
if ($indicador == "24") {
    $id = isset($_POST['id']) ? $_POST['id'] : "";

    try {
        $query = "DELETE FROM productos_ingredientes WHERE id = :id";
        $qry = $datapos->prepare($query);
        $qry->bindParam(':id', $id);

        if ($qry->execute()) {
            $rta = array('status' => 'ok');
        } else {
            $rta = array('status' => 'error');
        }
    } catch (PDOException $e) {
        $rta = array('status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage());
    } catch (Exception $e) {
        $rta = array('status' => 'error', 'message' => 'Error general: ' . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($rta);
}

//Rellenar selector de origen
if ($indicador == "25") {
    $idsucursal = $_SESSION['pos']['idSucursal'];
    $query = "SELECT * FROM bodegas WHERE idSucursal = $idsucursal";
    $qry = $datapos->prepare($query);
    $qry->execute();
    $rta = $qry->fetchAll(PDO::FETCH_OBJ);

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}

//Mostrar los datos de las bodegas
if ($indicador == "26") {
    try {
        $bodega = isset($_POST['bodega']) ? $_POST['bodega'] : "";
        $nuevoinicio = isset($_POST['nuevoinicio']) ? $_POST['nuevoinicio'] : "";
        $nroreg = isset($_POST['nroreg']) ? $_POST['nroreg'] : "";
        $idproducto = isset($_POST['idproducto']) ? $_POST['idproducto'] : "";

        $nombreTabla = "bodega_" . $bodega;

        $query = "SELECT COUNT(*) AS count FROM $nombreTabla WHERE idproducto = :idproducto;";
        $qry = $datapos->prepare($query);
        $qry->bindParam(':idproducto', $idproducto);
        $qry->execute();
        $count = $qry->fetch(PDO::FETCH_ASSOC)['count'];

        $query2 = "SELECT b.*,
           (SELECT sum(b2.entra) - sum(b2.sale) 
            FROM $nombreTabla b2 
            WHERE b2.idproducto = b.idproducto) AS saldo 
           FROM $nombreTabla b 
           WHERE b.idproducto = :idproducto 
           ORDER BY b.fecha DESC 
           LIMIT :nuevoinicio, :nroreg";

        $qry2 = $datapos->prepare($query2);
        $qry2->bindParam(':idproducto', $idproducto);
        $qry2->bindParam(":nuevoinicio", $nuevoinicio, PDO::PARAM_INT);
        $qry2->bindParam(":nroreg", $nroreg, PDO::PARAM_INT);
        $qry2->execute();
        $rta2 = $qry2->fetchAll(PDO::FETCH_OBJ);



        // Consultas para obtener el saldo, vendido y comprado de la bodega en específico
        $totalSaldo = 0;
        $totalVendido = 0;
        $totalComprado = 0;

        // Consulta para saldo
        $querySaldo = "SELECT IFNULL(sum(b2.entra) - sum(b2.sale), 0) AS saldo 
                           FROM $nombreTabla b2 
                           WHERE b2.idproducto = :idproducto";
        $qrySaldo = $datapos->prepare($querySaldo);
        $qrySaldo->bindParam(':idproducto', $idproducto);
        $qrySaldo->execute();
        $saldo = $qrySaldo->fetch(PDO::FETCH_OBJ);
        $totalSaldo += $saldo->saldo;

        // Consulta para vendido
        $queryVendido = "SELECT IFNULL(sum(b2.sale), 0) AS sale 
        FROM $nombreTabla b2 
        WHERE b2.idproducto = :idproducto";
        $qryVendido = $datapos->prepare($queryVendido);
        $qryVendido->bindParam(':idproducto', $idproducto);
        $qryVendido->execute();
        $vendido = $qryVendido->fetch(PDO::FETCH_OBJ);
        $totalVendido += $vendido->sale;

        // Consulta para comprado
        $queryComprado = "SELECT IFNULL(sum(b2.entra), 0) AS entra 
         FROM $nombreTabla b2 
         WHERE b2.idproducto = :idproducto";
        $qryComprado = $datapos->prepare($queryComprado);
        $qryComprado->bindParam(':idproducto', $idproducto);
        $qryComprado->execute();
        $comprado = $qryComprado->fetch(PDO::FETCH_OBJ);
        $totalComprado += $comprado->entra;

        $response = array('rta' => $count, 'rta2' => $rta2, 'saldo' => $totalSaldo, 'vendido' => $totalVendido, 'comprado' => $totalComprado);
    } catch (PDOException $e) {
        $response = array('error' => $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

if ($indicador == "27") {
    $idproducto = isset($_POST['id']) ? $_POST['id'] : (!empty($_GET['id']) ? $_GET['id'] : "");
    $bodegas = isset($_POST['bodegasValues']) ? json_decode($_POST['bodegasValues']) : [];

    try {
        $totalSaldo = 0;
        $totalVendido = 0;
        $totalComprado = 0;

        foreach ($bodegas as $bodega) {
            $nombreTabla = "bodega_" . $bodega;

            // Consulta para saldo
            $querySaldo = "SELECT IFNULL(sum(b2.entra) - sum(b2.sale), 0) AS saldo 
                           FROM $nombreTabla b2 
                           WHERE b2.idproducto = :idproducto";
            $qrySaldo = $datapos->prepare($querySaldo);
            $qrySaldo->bindParam(':idproducto', $idproducto);
            $qrySaldo->execute();
            $saldo = $qrySaldo->fetch(PDO::FETCH_OBJ);
            $totalSaldo += $saldo->saldo;

            // Consulta para vendido
            $queryVendido = "SELECT IFNULL(sum(b2.sale), 0) AS sale 
                             FROM $nombreTabla b2 
                             WHERE b2.idproducto = :idproducto";
            $qryVendido = $datapos->prepare($queryVendido);
            $qryVendido->bindParam(':idproducto', $idproducto);
            $qryVendido->execute();
            $vendido = $qryVendido->fetch(PDO::FETCH_OBJ);
            $totalVendido += $vendido->sale;

            // Consulta para comprado
            $queryComprado = "SELECT IFNULL(sum(b2.entra), 0) AS entra 
                              FROM $nombreTabla b2 
                              WHERE b2.idproducto = :idproducto";
            $qryComprado = $datapos->prepare($queryComprado);
            $qryComprado->bindParam(':idproducto', $idproducto);
            $qryComprado->execute();
            $comprado = $qryComprado->fetch(PDO::FETCH_OBJ);
            $totalComprado += $comprado->entra;
        }

        $response = array(
            'saldo' => $totalSaldo,
            'vendido' => $totalVendido,
            'comprado' => $totalComprado
        );
    } catch (PDOException $e) {
        $response = array('error' => $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

if ($indicador == "28") {
    $idSucursal = $_SESSION['pos']['idSucursal'];

    $query = "SELECT vp.codigoProducto, vp.producto, SUM(vp.cantidad) AS cantidad, SUM(vp.valorTotal) AS ValorTotal
    FROM ventas_productos vp 
    INNER JOIN productos p ON vp.codigoProducto = p.id
    INNER JOIN ventas v ON vp.idVenta = v.id WHERE v.idSucursal = :idSucursal
    GROUP BY vp.producto
    ORDER BY ValorTotal DESC LIMIT 1";

    $qry = $datapos->prepare($query);

    $qry->bindParam(':idSucursal', $idSucursal);

    if ($qry->execute()) {
        $rta = $qry->fetch(PDO::FETCH_ASSOC);
    } else {
        $rta = "error";
    }

    header('Content-Type: application/json');
    echo json_encode(array('rta' => $rta));
}