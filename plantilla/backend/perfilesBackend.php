<?php
session_start();
include "../assets/config.php";
include "../assets/config2.php";


$indicador = isset($_POST['ind']) ? $_POST['ind'] : (!empty($_GET['ind']) ? $_GET['ind'] : "");


if ($indicador == '1') {
    $tipofiltro = $_POST['tipofiltro'];
    $filtro = $_POST['filtro'];

    $inicio = $_POST['inicio'];
    $nroreg = $_POST['nroreg'];

    if ($tipofiltro == "") {
        $query1 = "SELECT *
            FROM perfiles
            ORDER BY id_perfil DESC";
        $result_task1 = mysqli_query($con, $query1);
        $count = array('count' => mysqli_num_rows($result_task1));

        $query = "SELECT *
            FROM perfiles
            ORDER BY id_perfil DESC
            limit $inicio,$nroreg";
    }

    if ($tipofiltro == "nombre") {
        $query1 = "SELECT *
            FROM perfiles
            WHERE nombre_perfil like '%$filtro%'
            ORDER BY id_perfil DESC";
        $result_task1 = mysqli_query($con, $query1);
        $count = array('count' => mysqli_num_rows($result_task1));

        $query = "SELECT *
            FROM perfiles
            WHERE nombre_perfil like '%$filtro%'
            ORDER BY id_perfil DESC 
            limit $inicio,$nroreg";
    }

    if ($tipofiltro == "id") {
        $query1 = "SELECT *
            FROM perfiles
            WHERE id_perfil = '$filtro'";
        $result_task1 = mysqli_query($con, $query1);
        $count = array('count' => mysqli_num_rows($result_task1));

        $query = "SELECT *
            FROM perfiles
            WHERE id_perfil = '$filtro'";
    }

    $result_task = mysqli_query($con, $query);
    // Serializamos consulta
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode(array($arrayData, $count));
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '2') {
    $idperfil = $_POST['idperfil'];
    $id_vista = $_POST["id_vista"];
    $tipo = $_POST["tipo"];

    $inicio = $_POST['inicio'];
    $nroreg = $_POST['nroreg'];

    if ($tipo != null || $tipo != "") {
        $query1 = "SELECT pe.*, pe.id as idpermiso, m.*, IFNULL(mv.id,'') as idvista, IFNULL(mv.nombrevista,'') as nombrevista, IFNULL(mv.file,'') as file from permisos pe
                inner join perfiles per on pe.idperfil=per.id_perfil
                inner join modulos m on pe.idmodulo=m.id
                left join modulosvistas mv on pe.idvista=mv.id
                Where per.id_perfil =  '$idperfil'
                AND pe.idvista = '$id_vista'
                AND pe.tipoelemento = '$tipo'
                ORDER BY pe.id DESC";
        $result_task1 = mysqli_query($con, $query1);
        $count = array('count' => mysqli_num_rows($result_task1));

        $query = "SELECT pe.*, pe.id as idpermiso, m.*, IFNULL(mv.id,'') as idvista, IFNULL(mv.nombrevista,'') as nombrevista, IFNULL(mv.file,'') as file from permisos pe
                inner join perfiles per 
                on pe.idperfil=per.id_perfil
                inner join modulos m 
                on pe.idmodulo=m.id
                left join modulosvistas mv
                on pe.idvista=mv.id
                Where per.id_perfil = '$idperfil'
                AND pe.idvista = '$id_vista'
                AND pe.tipoelemento = '$tipo'
                ORDER BY pe.id DESC
                limit $inicio,$nroreg";
    } else if ($id_vista != null || $id_vista != "") {
        $query1 = "SELECT pe.*, pe.id as idpermiso, m.*, IFNULL(mv.id,'') as idvista, IFNULL(mv.nombrevista,'') as nombrevista, IFNULL(mv.file,'') as file from permisos pe
            inner join perfiles per 
            on pe.idperfil=per.id_perfil
            inner join modulos m 
            on pe.idmodulo=m.id
            left join modulosvistas mv
            on pe.idvista=mv.id
            Where per.id_perfil =  '$idperfil'
            AND pe.idvista = '$id_vista'
            ORDER BY pe.id DESC";
        $result_task1 = mysqli_query($con, $query1);
        $count = array('count' => mysqli_num_rows($result_task1));

        $query = "SELECT pe.*, pe.id as idpermiso, m.*, IFNULL(mv.id,'') as idvista, IFNULL(mv.nombrevista,'') as nombrevista, IFNULL(mv.file,'') as file from permisos pe
            inner join perfiles per 
            on pe.idperfil=per.id_perfil
            inner join modulos m 
            on pe.idmodulo=m.id
            left join modulosvistas mv
            on pe.idvista=mv.id
            Where per.id_perfil = '$idperfil'
            AND pe.idvista = '$id_vista'
            ORDER BY pe.id DESC
            limit $inicio,$nroreg";
    } else {
        $query1 = "SELECT pe.*, pe.id as idpermiso, m.*, IFNULL(mv.id,'') as idvista, IFNULL(mv.nombrevista,'') as nombrevista, IFNULL(mv.file,'') as file from permisos pe
        inner join perfiles per 
        on pe.idperfil=per.id_perfil
        inner join modulos m 
        on pe.idmodulo=m.id
        left join modulosvistas mv
        on pe.idvista=mv.id
        Where per.id_perfil =  '$idperfil'
        ORDER BY pe.id DESC";
        $result_task1 = mysqli_query($con, $query1);
        $count = array('count' => mysqli_num_rows($result_task1));

        $query = "SELECT pe.*, pe.id as idpermiso, m.*, IFNULL(mv.id,'') as idvista, IFNULL(mv.nombrevista,'') as nombrevista, IFNULL(mv.file,'') as file from permisos pe
        inner join perfiles per 
        on pe.idperfil=per.id_perfil
        inner join modulos m 
        on pe.idmodulo=m.id
        left join modulosvistas mv
        on pe.idvista=mv.id
        Where per.id_perfil = '$idperfil'
        ORDER BY pe.id DESC
        limit $inicio,$nroreg";
    }


    $result_task = mysqli_query($con, $query);
    // Serializamos consulta
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode(array($arrayData, $count));
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '2.1') {
    $idperfil = $_POST['idperfil'];
    $modulo = $_POST['modulo'];
    $vista = $_POST['vista'];

    $query = "SELECT pe.*, pe.id as idpermiso, m.*, IFNULL(mv.id,'') as idvista, IFNULL(mv.nombrevista,'') as nombrevista, IFNULL(mv.file,'') as file 
        from permisos pe
        inner join perfiles per 
        on pe.idperfil=per.id_perfil
        inner join modulos m 
        on pe.idmodulo=m.id
        left join modulosvistas mv
        on pe.idvista=mv.id
        WHERE per.id_perfil = '$idperfil'
        AND pe.idmodulo='$modulo'
        AND pe.idvista='$vista'
        ORDER BY pe.id DESC";

    $result_task = mysqli_query($con, $query);
    // Serializamos consulta
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($arrayData);
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '3') {
    $inp_nombreperfil = $_POST['inp_nombreperfil'];
    $txt_observa = $_POST['txt_observa'];
    $estado = $_POST['estado'];

    $nombrePerfil = mysqli_query($con, "SELECT * FROM perfiles WHERE nombre_perfil = '$inp_nombreperfil'");

    if (mysqli_num_rows($nombrePerfil) >= 1) {
        echo json_encode(array('respuesta' => 'NO'));
    } else if (mysqli_num_rows($nombrePerfil) == 0) {
        $query = "INSERT INTO perfiles(nombre_perfil, descripcion, status)  
            values('$inp_nombreperfil', '$txt_observa', '$estado')";
        $result_task = mysqli_query($con, $query);


        $query1 = mysqli_query($con, "SELECT id_perfil, nombre_perfil FROM `perfiles` ORDER by id_perfil DESC LIMIT 1");
        $result1 = mysqli_fetch_assoc($query1);

        $id_perfil = $result1["id_perfil"];
        $nombre_perfil = $result1["nombre_perfil"];
        $id_user = $_SESSION['pe']['act_id'];


        $query2 = "INSERT INTO `bhhurjmu_ppidata`.`log_perfiles` (`id`, `id_perfil`, `nombre_perfil`, `cambio`, `evento`, `id_user`) VALUES (NULL, '$id_perfil', '$nombre_perfil', NULL, 'INSERT', '$id_user')";

        $result_task2 = mysqli_query($con, $query2);

        if (mysqli_affected_rows($con) >= 1) {
            echo json_encode(array('respuesta' => 'SI'));
        } else {
            echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
        }
    }
}

if ($indicador == '3.1') {
    // busco ultimo id insertado 
    $query = "SELECT MAX(id_perfil) as id_perfil FROM perfiles";
    $result_task = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($arrayData);
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '3.2') {
    // busco ultimo id insertado 
    $idCopiar = $_POST["idperfil"];

    $query = "SELECT id_perfil FROM perfiles ORDER BY id_perfil DESC LIMIT 1";
    $result_task = mysqli_query($con, $query);
    $rowPerfil = mysqli_fetch_array($result_task);
    $idNuevo = $rowPerfil["id_perfil"];

    $query2 = "SELECT * FROM permisos WHERE idperfil = '$idCopiar'";
    $result_task2 = mysqli_query($con, $query2);
    // $rowPermisos = mysqli_fetch_array($result_task2);
    $prueba = array();

    while ($row = mysqli_fetch_array($result_task2)) {
        // $prueba[] = array($row);
        $idmodulo = $row["idmodulo"];
        $idvista = $row["idvista"];
        $elemento = $row["elemento"];
        $permiso = $row["permiso"];
        $tipoelemento = $row["tipoelemento"];

        $query3 = "INSERT INTO permisos (id, idperfil, idmodulo, idvista, elemento, permiso, tipoelemento) VALUES (NULL, '$idNuevo', '$idmodulo', '$idvista', '$elemento', '$permiso', '$tipoelemento')";
        mysqli_query($con, $query3);
    }
    echo json_encode(array('respuesta' => 'SI'));
}

if ($indicador == '4') {
    $idperfil = $_POST['idperfil'];
    $query = "SELECT * FROM perfiles  where id_perfil='$idperfil'";
    // Ejecutamos query
    $result_task = mysqli_query($con, $query);
    // Serializamos consulta
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($arrayData);
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '5') {

    $query = "SELECT * FROM modulos ";
    // Ejecutamos query
    $result_task = mysqli_query($con, $query);
    // Serializamos consulta
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($arrayData);
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '6') {
    $modulo = $_POST["modulo"];
    $query = "SELECT * FROM modulosvistas where idmodulo='$modulo'";
    // Ejecutamos query
    $result_task = mysqli_query($con, $query);
    // Serializamos consulta
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }

    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($arrayData);
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '7') {
    $idperfil = $_POST['idperfil'];
    $idmodulo = $_POST['idmodulo'];
    $idvista = $_POST['idvista'];
    $elemento = $_POST['elemento'];
    $permiso = $_POST['permiso'];
    $tipoelemento = $_POST['tipoelemento'];


    $query = "INSERT INTO permisos (idperfil, idmodulo, idvista, elemento, permiso, tipoelemento)  
		values('$idperfil', '$idmodulo', '$idvista', '$elemento', '$permiso', '$tipoelemento')";
    $result_task = mysqli_query($con, $query);

    $queryid = mysqli_query($con, "SELECT id FROM permisos WHERE elemento = '$elemento' ORDER BY id DESC LIMIT 1");

    $resultid = mysqli_fetch_assoc($queryid);
    $idpermiso = $resultid["id"];
    $id_user = $_SESSION['pe']['act_id'];
    $fecha = date('Y-m-d H:i:s');

    $query2 = "INSERT INTO log_permisos 
    (idpermiso, idperfil, idmodulo, idvista, elemento, permiso, tipoelemento, evento, id_user, fecha)  
    VALUES 
    ('$idpermiso', '$idperfil', '$idmodulo', '$idvista', '$elemento', '$permiso', '$tipoelemento', 'INSERT', '$id_user', '$fecha')";

    $result_task2 = mysqli_query($con, $query2);
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode(array('respuesta' => 'SI'));
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '8') {
    $id = $_POST['id'];

    $query2 = mysqli_query($con, "SELECT * FROM permisos WHERE id = '$id'");
    $result_task2 = mysqli_fetch_assoc($query2);

    $query = "DELETE from permisos where id='$id'";
    // Ejecutamos query
    $result_task = mysqli_query($con, $query);
    // Serializamos consulta

    $idpermiso = $result_task2["id"];
    $idperfil = $result_task2["idperfil"];
    $idmodulo = $result_task2["idmodulo"];
    $idvista = $result_task2["idvista"];
    $elemento = $result_task2["elemento"];
    $permiso = $result_task2["permiso"];
    $tipoelemento = $result_task2["tipoelemento"];
    $id_user = $_SESSION['pe']['act_id'];
    $query3 = "INSERT INTO  log_permisos( idpermiso, idperfil, idmodulo, idvista, elemento, permiso, tipoelemento, evento, id_user)  
    values( '$idpermiso', '$idperfil', '$idmodulo', '$idvista', '$elemento', '$permiso', '$tipoelemento',
    'DELETE', '$id_user')";

    $result_task3 = mysqli_query($con, $query3);

    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode(array('SI'));
    } else {
        echo json_encode(array('NO'));
    }
}

if ($indicador == '9') {
    $moduloid = $_POST["moduloid"];
    $vistaid = $_POST["vistaid"];
    $idperfil = $_POST["idperfil"];


    $query = "SELECT usuarios.idperfil, permisos.*  from usuarios  inner JOIN permisos 
        on usuarios.idperfil = permisos.idperfil
        where usuarios.idperfil = '$idperfil' AND permisos.idmodulo = '$moduloid' AND permisos.idvista = '$vistaid'
        OR permisos.idvista = '0'  GROUP BY usuarios.idperfil, permisos.elemento";
    // Ejecutamos query

    $result_task = mysqli_query($con, $query);

    // Serializamos consulta
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }

    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($arrayData);
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == '10') {
    $idperfil = $_POST['idperfil'];
    $query4 = "SELECT id FROM usuarios WHERE idperfil = '$idperfil'";
    $result_task4 = mysqli_query($con, $query4);
    if (mysqli_num_rows($result_task4) > 0) {
        echo json_encode(array('respuesta' => 'NO'));
        return;
    }

    $query2 = mysqli_query($con, "SELECT id_perfil, nombre_perfil FROM `perfiles` WHERE id_perfil='$idperfil'");
    $result2 = mysqli_fetch_assoc($query2);

    $query = "DELETE from perfiles where id_perfil='$idperfil'";
    $result_task = mysqli_query($con, $query);
    $query1 = "DELETE from permisos where idperfil='$idperfil'";
    $result_task1 = mysqli_query($con, $query);


    $id_perfil = $result2["id_perfil"];
    $nombre_perfil = $result2["nombre_perfil"];
    $id_user = $_SESSION['pe']['act_id'];

    $query3 = "INSERT INTO `bhhurjmu_ppidata`.`log_perfiles` (`id`, `id_perfil`, `nombre_perfil`, `cambio`, `evento`, `id_user`) VALUES (NULL, '$id_perfil', '$nombre_perfil', NULL, 'DELETE', '$id_user')";
    $result_task3 = mysqli_query($con, $query3);
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode(array('respuesta' => 'SI'));
    } else {
        echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
    }
}
if ($indicador == '11') {
    //guarda el cargo
    $idperfil = $_POST["idperfil"];
    $perfil = $_POST["perfil"];
    $descripcion = $_POST["descripcion"];

    $nombrePerfil = mysqli_query($con, "SELECT * FROM perfiles WHERE nombre_perfil = '$perfil'");
    $array = mysqli_fetch_array($nombrePerfil);
    if ($array["nombre_perfil"] == $perfil && $array["descripcion"] != $descripcion) {
        $query = "UPDATE perfiles SET descripcion='$descripcion' WHERE id_perfil='$idperfil'";
        $result_task = mysqli_query($con, $query);

        if (mysqli_affected_rows($con) >= 1) {
            echo json_encode(array('respuesta' => 'SI'));
        } else {
            echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
        }
    } else if (mysqli_num_rows($nombrePerfil) >= 1) {
        echo json_encode(array('respuesta' => 'NO'));
    } else if (mysqli_num_rows($nombrePerfil) == 0) {
        $nombreQuery = mysqli_query($con, "SELECT nombre_perfil FROM perfiles WHERE id_perfil = '$idperfil'");
        $resultNombre = mysqli_fetch_assoc($nombreQuery);
        $nombre = $resultNombre["nombre_perfil"];

        $query = "UPDATE perfiles SET nombre_perfil='$perfil', descripcion='$descripcion' WHERE id_perfil='$idperfil'";
        $result_task = mysqli_query($con, $query);

        $query2 = mysqli_query($con, "SELECT id_perfil, nombre_perfil FROM `perfiles` WHERE id_perfil='$idperfil'");
        $result2 = mysqli_fetch_assoc($query2);
        $id_perfil = $result2["id_perfil"];
        $nombre_perfil = $result2["nombre_perfil"];
        $id_user = $_SESSION['pe']['act_id'];

        $query3 = "INSERT INTO `bhhurjmu_ppidata`.`log_perfiles` (`id`, `id_perfil`, `nombre_perfil`, `cambio`, `evento`, `id_user`) VALUES (NULL, '$id_perfil', '$nombre_perfil', '$nombre', 'UPDATE', '$id_user')";

        $result_task3 = mysqli_query($con, $query3);
        if (mysqli_affected_rows($con) >= 1) {
            echo json_encode(array('respuesta' => 'SI'));
        } else {
            echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
        }
    }
}
if ($indicador == '12') {
    $query = "SELECT * FROM perfiles";
    $result_task = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result_task)) {
        $arrayData[] = $row;
    }
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($arrayData);
    } else {
        echo json_encode(array('respuesta' => 'NO'));
    }
}

if ($indicador == "13") {
    $idperfil = $_POST["idperfil"];
    $estado = $_POST["estado"];
    $query = "UPDATE perfiles SET status='$estado' WHERE id_perfil='$idperfil'";
    $result_task = mysqli_query($con, $query);

    $query2 = mysqli_query($con, "SELECT id_perfil, nombre_perfil FROM `perfiles` WHERE id_perfil='$idperfil'");
    $result2 = mysqli_fetch_assoc($query2);
    $id_perfil = $result2["id_perfil"];
    $nombre_perfil = $result2["nombre_perfil"];
    $id_user = $_SESSION['pe']['act_id'];
    $estado2 = "ESTADO = " . $estado;

    $query3 = "INSERT INTO `bhhurjmu_ppidata`.`log_perfiles` (`id`, `id_perfil`, `nombre_perfil`, `cambio`, `evento`, `id_user`) VALUES (NULL, '$id_perfil', '$nombre_perfil', NULL, '$estado2', '$id_user')";
    $result_task3 = mysqli_query($con, $query3);
    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode(array('respuesta' => 'SI'));
    } else {
        echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
    }
}

if ($indicador == "14") {
    $id_modulo = $_POST["id_modulo"];

    $task = mysqli_query($con, "SELECT * FROM modulosvistas WHERE idmodulo = '$id_modulo'");
    $result = mysqli_fetch_all($task);

    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($result);
    } else {
        echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
    }
}

if ($indicador == "15") {
    $id_vista = $_POST["id_vista"];

    $task = mysqli_query($con, "SELECT * FROM permisos WHERE idvista = '$id_vista'");
    $result = mysqli_fetch_all($task);

    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($result);
    } else {
        echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
    }
}

if ($indicador == "16") {
    $id_vista = $_POST["id_vista"];
    $id_perfil = $_POST["id_perfil"];

    $task = mysqli_query($con, "SELECT DISTINCT(tipoelemento) as tipo FROM permisos WHERE idvista = '$id_vista' AND idperfil = '$id_perfil'");
    $result = mysqli_fetch_all($task);

    if (mysqli_affected_rows($con) >= 1) {
        echo json_encode($result);
    } else {
        echo json_encode(array('respuesta' => 'NO', 'error' => mysqli_error($con)));
    }
}

if ($indicador == "17") {
    $query2 = "SELECT * FROM modulos";
    $task = mysqli_query($con, $query2);

    if ($task && mysqli_num_rows($task) > 0) {
        $rta = mysqli_fetch_all($task, MYSQLI_ASSOC);
        header('Content-Type: application/json');
        echo json_encode(array('rta2' => $rta));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            'error' => true,
            'message' => 'Error al consultar los módulos',
            'details' => mysqli_error($con)
        ));
    }
}
