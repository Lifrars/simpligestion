<?php
$graphqlEndpoint = 'http://ppi.miclickderecho.com/graphql/index.php'; // Reemplaza con tu endpoint de GraphQL

// Función para ejecutar una consulta GraphQL
function executeGraphQL($query) {
    $data = json_encode(['query' => $query]);
    $ch = curl_init($GLOBALS['graphqlEndpoint']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        echo json_encode(json_decode($response), JSON_PRETTY_PRINT);
    }
    curl_close($ch);
}

// 1. Query para consultar todas las mascotas
$queryConsultarMascotas = <<<GQL
{
  consultarMascotas {
    id_mascota
    nombre
    raza
    edad
    peso
    cliente
    medicamento
  }
}
GQL;

// 2. Query para obtener clientes
$queryObtenerClientes = <<<GQL
{
  obtenerClientes {
    id_cliente
    nombre_completo
  }
}
GQL;

// 3. Query para obtener medicamentos
$queryObtenerMedicamentos = <<<GQL
{
  obtenerMedicamentos {
    id_medicamento
    nombre
    descripcion
    dosis
  }
}
GQL;

// 4. Mutation para agregar una mascota
$mutationAgregarMascota = <<<GQL
mutation {
  agregarMascota(
    nombre: "Luna",
    raza: "Golden Retriever",
    edad: 4,
    peso: 22.5,
    cliente: 1,
    medicamento: 2
  )
}
GQL;

// 5. Mutation para eliminar una mascota
$mutationEliminarMascota = <<<GQL
mutation {
  eliminarMascota(id_mascota: 1)
}
GQL;

// Ejecutamos las consultas
echo "=== Consultar Mascotas ===\n";
executeGraphQL($queryConsultarMascotas);

echo "\n\n=== Obtener Clientes ===\n";
executeGraphQL($queryObtenerClientes);

echo "\n\n=== Obtener Medicamentos ===\n";
executeGraphQL($queryObtenerMedicamentos);

echo "\n\n=== Agregar Mascota ===\n";
executeGraphQL($mutationAgregarMascota);

echo "\n\n=== Eliminar Mascota ===\n";
executeGraphQL($mutationEliminarMascota);
?>
