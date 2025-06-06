<?php
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;

// Definimos los tipos (Mascota, Cliente y Medicamento)
$mascotaType = new ObjectType([
    'name' => 'Mascota',
    'fields' => [
        'id_mascota' => Type::int(),
        'nombre' => Type::string(),
        'raza' => Type::string(),
        'edad' => Type::int(),
        'peso' => Type::float(),
        'cliente' => Type::string(),
        'medicamento' => Type::string()
    ]
]);

$clienteType = new ObjectType([
    'name' => 'Cliente',
    'fields' => [
        'id_cliente' => Type::int(),
        'nombre_completo' => Type::string(),
    ]
]);

$medicamentoType = new ObjectType([
    'name' => 'Medicamento',
    'fields' => [
        'id_medicamento' => Type::int(),
        'nombre' => Type::string(),
        'descripcion' => Type::string(),
        'dosis' => Type::string(),
    ]
]);

// Definimos el esquema principal con Query y Mutation
$schema = new Schema([
    'query' => new ObjectType([
        'name' => 'Query',
        'fields' => [
            'consultarMascotas' => [
                'type' => Type::listOf($mascotaType),
                'resolve' => 'consultarMascotasResolver'
            ],
            'obtenerClientes' => [
                'type' => Type::listOf($clienteType),
                'resolve' => 'obtenerClientesResolver'
            ],
            'obtenerMedicamentos' => [
                'type' => Type::listOf($medicamentoType),
                'resolve' => 'obtenerMedicamentosResolver'
            ],
        ]
    ]),
    'mutation' => new ObjectType([
        'name' => 'Mutation',
        'fields' => [
            'agregarMascota' => [
                'type' => Type::string(),
                'args' => [
                    'nombre' => Type::nonNull(Type::string()),
                    'raza' => Type::nonNull(Type::string()),
                    'edad' => Type::int(),
                    'peso' => Type::float(),
                    'cliente' => Type::nonNull(Type::int()),
                    'medicamento' => Type::int()
                ],
                'resolve' => 'agregarMascotaResolver'
            ],
            'eliminarMascota' => [
                'type' => Type::string(),
                'args' => [
                    'id_mascota' => Type::nonNull(Type::int()),
                ],
                'resolve' => 'eliminarMascotaResolver'
            ],
        ]
    ])
]);
