<?php
// Midleware
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'cnxpdo.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

//SE VALIDA PORQUE EL SERVER DEJO DE INFORMAR ESTA VARIABLE
if (isset($_SERVER['PATH_INFO'])) {
    $request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
} else {
    $request = [];
}

switch ($method) {
    case 'GET':
        if (isset($_GET["consultarMascotas"])) {
            consultarMascotas();
        } elseif (isset($_GET["obtenerClientes"])) {
            obtenerClientes();
        } elseif (isset($_GET["obtenerClientess"])) {
            obtenerClientess();
        } elseif (isset($_GET["obtenerMedicamentos"])) {
            obtenerMedicamentos();
        } elseif (isset($_GET["obtenerMedicamentoss"])) {
            obtenerMedicamentoss();
        } else {
            echo json_encode(["success" => 0, "message" => "Consulta no encontrada"]);
        }
        break;
    case 'POST':
        if (isset($_GET["agregarMascota"])) {
            agregarMascota($input); // Pasamos la data recibida como parámetro
        } elseif (isset($_GET["agregarCliente"])) {
            agregarCliente($input); // Nueva función para agregar cliente
        } elseif (isset($_GET["agregarMedicamento"])) {
            agregarMedicamento($input); // Llama a la función de agregar medicamento
        } else {
            echo json_encode(["success" => 0, "message" => "Operación POST no encontrada"]);
        }
        break;

    case 'PUT':
        // Validar que es una solicitud para actualizar una mascota
        if (isset($_GET["actualizarMascota"])) {
            $id_mascota = $_GET["id"];
            $input = json_decode(file_get_contents('php://input'), true);

            actualizarMascota($id_mascota, $input);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id_mascota'])) {
            eliminarMascota($_GET['id_mascota']);
        } elseif (isset($_GET['id_cliente'])) {
            eliminarCliente($_GET['id_cliente']); // Nueva función para eliminar cliente
        } elseif (isset($_GET['id_medicamento'])) {
            eliminarMedicamento($_GET['id_medicamento']); // Llama a la función para eliminar medicamento
        } else {
            echo json_encode(["success" => 0, "message" => "Falta el ID de la mascota"]);
        }
        break;

    default:
        echo json_encode(["success" => 0, "message" => "Método no permitido"]);
        break;
}

// Función para consultar mascotas (ya existente)
function consultarMascotas()
{
    global $conexionBD;

    try {
        // Consulta SQL que une las tablas mascotas, clientes y medicamentos
        $query = "
            SELECT 
                m.id_mascota, 
                m.nombre AS nombre_mascota, 
                m.raza, 
                m.edad, 
                m.peso, 
                c.nombres AS nombre_cliente, 
                c.apellidos AS apellido_cliente, 
                med.nombre AS nombre_medicamento, 
                med.dosis 
            FROM 
                mascotas m
            JOIN 
                clientes c ON m.id_cliente = c.id_cliente
            LEFT JOIN 
                medicamentos med ON m.id_medicamento = med.id_medicamento
            ORDER BY 
                m.id_mascota ASC;
        ";

        $stmt = $conexionBD->prepare($query);
        $stmt->execute();

        // Obtener todos los resultados
        $mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar si hay resultados
        if ($mascotas) {
            echo json_encode(["success" => 1, "mascotas" => $mascotas]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontraron mascotas"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}

// Función para agregar una mascota (nueva)
function agregarMascota($data)
{
    global $conexionBD;

    if (!isset($data['nombre'], $data['raza'], $data['edad'], $data['peso'], $data['cliente'])) {
        echo json_encode(["success" => 0, "message" => "Datos incompletos"]);
        return;
    }

    try {
        // Asignamos los valores del array $data
        $nombre = $data['nombre'];
        $raza = $data['raza'];
        $edad = $data['edad'];
        $peso = $data['peso'];
        $cliente = $data['cliente']; // Suponemos que cliente es el ID del cliente
        $medicamento = isset($data['medicamento']) ? $data['medicamento'] : null;

        // Consulta SQL para insertar una nueva mascota
        $query = "INSERT INTO mascotas (nombre, raza, edad, peso, id_cliente, id_medicamento) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conexionBD->prepare($query);
        $stmt->execute([$nombre, $raza, $edad, $peso, $cliente, $medicamento]);

        echo json_encode(["success" => 1, "message" => "Mascota agregada correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => "Error al agregar la mascota: " . $e->getMessage()]);
    }
}

function obtenerClientes()
{
    global $conexionBD;

    try {
        $query = "SELECT id_cliente, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM clientes";
        $stmt = $conexionBD->prepare($query);
        $stmt->execute();

        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($clientes) {
            echo json_encode(["success" => 1, "clientes" => $clientes]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontraron clientes"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}


function obtenerMedicamentos()
{
    global $conexionBD;

    try {
        $query = "SELECT id_medicamento, nombre FROM medicamentos";
        $stmt = $conexionBD->prepare($query);
        $stmt->execute();

        $medicamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($medicamentos) {
            echo json_encode(["success" => 1, "medicamentos" => $medicamentos]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontraron medicamentos"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}

function eliminarMascota($id_mascota)
{
    global $conexionBD;

    try {
        // Consulta para eliminar la mascota por ID
        $query = "DELETE FROM mascotas WHERE id_mascota = :id_mascota";
        $stmt = $conexionBD->prepare($query);
        $stmt->bindParam(':id_mascota', $id_mascota, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            echo json_encode(["success" => 1, "message" => "Mascota eliminada correctamente"]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontró la mascota con ese ID"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}

function actualizarMascota($id_mascota, $input)
{
    global $conexionBD;

    try {
        $query = "UPDATE mascotas SET nombre = :nombre, raza = :raza, edad = :edad, peso = :peso, id_cliente = :cliente, id_medicamento = :medicamento WHERE id_mascota = :id_mascota";
        $stmt = $conexionBD->prepare($query);
        $stmt->bindParam(':id_mascota', $id_mascota);
        $stmt->bindParam(':nombre', $input['nombre']);
        $stmt->bindParam(':raza', $input['raza']);
        $stmt->bindParam(':edad', $input['edad']);
        $stmt->bindParam(':peso', $input['peso']);
        $stmt->bindParam(':cliente', $input['cliente']);
        $stmt->bindParam(':medicamento', $input['medicamento']);
        $stmt->execute();

        echo json_encode(["success" => 1, "message" => "Mascota actualizada correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}

function obtenerClientess()
{
    global $conexionBD;

    try {
        $query = "SELECT id_cliente, cedula, nombres, apellidos, direccion, telefono FROM clientes";
        $stmt = $conexionBD->prepare($query);
        $stmt->execute();

        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($clientes) {
            echo json_encode(["success" => 1, "clientes" => $clientes]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontraron clientes"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}

function agregarCliente($data)
{
    global $conexionBD;

    if (!isset($data['cedula'], $data['nombres'], $data['apellidos'], $data['direccion'], $data['telefono'])) {
        echo json_encode(["success" => 0, "message" => "Datos incompletos"]);
        return;
    }

    try {
        $cedula = $data['cedula'];
        $nombres = $data['nombres'];
        $apellidos = $data['apellidos'];
        $direccion = $data['direccion'];
        $telefono = $data['telefono'];

        // Consulta para agregar un nuevo cliente
        $query = "INSERT INTO clientes (cedula, nombres, apellidos, direccion, telefono) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexionBD->prepare($query);
        $stmt->execute([$cedula, $nombres, $apellidos, $direccion, $telefono]);

        echo json_encode(["success" => 1, "message" => "Cliente agregado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => "Error al agregar el cliente: " . $e->getMessage()]);
    }
}

function eliminarCliente($id_cliente)
{
    global $conexionBD;

    try {
        // Consulta para eliminar el cliente por ID
        $query = "DELETE FROM clientes WHERE id_cliente = :id_cliente";
        $stmt = $conexionBD->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            echo json_encode(["success" => 1, "message" => "Cliente eliminado correctamente"]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontró el cliente con ese ID"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}

function obtenerMedicamentoss()
{
    global $conexionBD;

    try {
        $query = "SELECT id_medicamento, nombre, descripcion, dosis FROM medicamentos";
        $stmt = $conexionBD->prepare($query);
        $stmt->execute();

        $medicamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($medicamentos) {
            echo json_encode(["success" => 1, "medicamentos" => $medicamentos]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontraron medicamentos"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}

function agregarMedicamento($data)
{
    global $conexionBD;

    // Validamos que todos los campos requeridos estén presentes
    if (!isset($data['nombre'], $data['descripcion'], $data['dosis'])) {
        echo json_encode(["success" => 0, "message" => "Datos incompletos"]);
        return;
    }

    try {
        // Asignamos los valores del array $data
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'];
        $dosis = $data['dosis'];

        // Consulta SQL para insertar un nuevo medicamento
        $query = "INSERT INTO medicamentos (nombre, descripcion, dosis) VALUES (?, ?, ?)";

        // Preparamos y ejecutamos la consulta con los valores proporcionados
        $stmt = $conexionBD->prepare($query);
        $stmt->execute([$nombre, $descripcion, $dosis]);

        // Si la inserción fue exitosa, devolvemos un mensaje de éxito
        echo json_encode(["success" => 1, "message" => "Medicamento agregado correctamente"]);
    } catch (PDOException $e) {
        // En caso de error, lo reportamos
        echo json_encode(["success" => 0, "message" => "Error al agregar el medicamento: " . $e->getMessage()]);
    }
}

function eliminarMedicamento($id_medicamento)
{
    global $conexionBD;

    try {
        // Consulta SQL para eliminar el medicamento por ID
        $query = "DELETE FROM medicamentos WHERE id_medicamento = :id_medicamento";
        $stmt = $conexionBD->prepare($query);
        $stmt->bindParam(':id_medicamento', $id_medicamento, PDO::PARAM_INT);
        $stmt->execute();

        // Verificamos si se eliminó algún registro
        if ($stmt->rowCount()) {
            echo json_encode(["success" => 1, "message" => "Medicamento eliminado correctamente"]);
        } else {
            echo json_encode(["success" => 0, "message" => "No se encontró el medicamento con ese ID"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => 0, "message" => $e->getMessage()]);
    }
}
