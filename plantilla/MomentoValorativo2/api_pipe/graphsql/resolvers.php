<?php
require 'cnxpdo.php';

function consultarMascotasResolver()
{
    global $conexionBD;
    $query = "SELECT m.id_mascota, m.nombre, m.raza, m.edad, m.peso, 
                     CONCAT(c.nombres, ' ', c.apellidos) AS cliente, 
                     med.nombre AS medicamento
              FROM mascotas m 
              JOIN clientes c ON m.id_cliente = c.id_cliente
              LEFT JOIN medicamentos med ON m.id_medicamento = med.id_medicamento";
    $stmt = $conexionBD->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarMascotaResolver($root, $args)
{
    global $conexionBD;
    $query = "INSERT INTO mascotas (nombre, raza, edad, peso, id_cliente, id_medicamento) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexionBD->prepare($query);
    $stmt->execute([$args['nombre'], $args['raza'], $args['edad'], $args['peso'], $args['cliente'], $args['medicamento']]);
    return "Mascota agregada correctamente";
}

function eliminarMascotaResolver($root, $args)
{
    global $conexionBD;
    $query = "DELETE FROM mascotas WHERE id_mascota = ?";
    $stmt = $conexionBD->prepare($query);
    $stmt->execute([$args['id_mascota']]);
    return "Mascota eliminada correctamente";
}

function obtenerClientesResolver()
{
    global $conexionBD;
    $query = "SELECT id_cliente, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM clientes";
    $stmt = $conexionBD->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerMedicamentosResolver()
{
    global $conexionBD;
    $query = "SELECT id_medicamento, nombre, descripcion, dosis FROM medicamentos";
    $stmt = $conexionBD->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
