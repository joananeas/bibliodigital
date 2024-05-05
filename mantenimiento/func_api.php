<?php
# © Joan Aneas
    
   /*___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (ASCII art)*/

    
function peticionSQL(){
    require_once "db.php";
    
    $key = base64_decode(DB_SERVER_KEY);
    $iv = base64_decode(DB_SERVER_IV);
    $host = openssl_decrypt(DB_HOST, 'aes-256-cbc', $key, 0, $iv);
    $user = openssl_decrypt(DB_USER, 'aes-256-cbc', $key, 0, $iv);
    $password = openssl_decrypt(DB_PASSWORD, 'aes-256-cbc', $key, 0, $iv);
    $db = openssl_decrypt(DB_NAME, 'aes-256-cbc', $key, 0, $iv);
    $conn = new mysqli($host, $user, $password, $db);

    return $conn;
}

function cercaLlibresLite($conn, $llibre){
    $sql = "SELECT dib_cataleg.TITOL AS nom, dib_exemplars.ESTAT as estadoActual, dib_cataleg.NUMERO as id 
    FROM `dib_cataleg` INNER JOIN `dib_exemplars` ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR 
    WHERE `TITOL` LIKE '%$llibre%' OR AUTOR LIKE '%$llibre%'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $rows = array();
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $i++;
                # Si datos están sucios
                if (str_contains($row['estadoActual'], "Disponible")) $row['estadoActual'] = "Disponible";
                else $row['estadoActual'] = "Prestat";
                $rows[] = $row;
                if ($i > 5) break;
            }
            echo json_encode(['response' => 'OK', 'llibres' => $rows]);
        } else {
            echo json_encode(['response' => 'ERROR']);
        }
}

function cercaLlibresAll($conn, $libroId){
    $conn->set_charset("utf8mb4");
    $sql = "SELECT * FROM `dib_cataleg` WHERE `NUMERO` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta']);
        return;
    }
    
    // Enlazar el parámetro
    mysqli_stmt_bind_param($stmt, 'i', $libroId);
    
    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Comprobar los resultados
    if ($result && mysqli_num_rows($result) > 0) {
        $detalleLibro = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'detallesLibro' => $detalleLibro]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontraron detalles para el libro']);
    }
    
    // Cerrar el statement
    mysqli_stmt_close($stmt);
}


function cercaLlibresFull($conn, $llibre){
    $sql = "SELECT dib_cataleg.TITOL AS nom,
    dib_exemplars.ESTAT as estadoActual,
    dib_cataleg.URL as 'url',
    dib_cataleg.RESUM as 'resum',
    dib_cataleg.AUTOR as 'autor' FROM `dib_cataleg` INNER JOIN `dib_exemplars` ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR WHERE `NUMERO` = '$llibre'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode(['response' => 'OK', 'llibres' => $rows]);
    } else {
        echo json_encode(['response' => 'ERROR']);
    }
}


function reservarLibro($conn, $titulo, $fechaInicio, $fechaFin){
    // Primero, verifica si el libro existe en la base de datos
    $sql = "SELECT * FROM llibres WHERE nom = '$titulo'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // Si el libro existe, inserta la reserva en la base de datos
        $sql = "INSERT INTO `reserves` (`reserva`, `nomLlibre`, `dataInici`, `dataFi`) VALUES (NULL, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $titulo, $fechaInicio, $fechaFin);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['response' => 'OK']);
        } else {
            echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo insertar la reserva en la base de datos']);
        }
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'El libro no existe']);
    }
}

function getReserves($conn, $id){
    $sql = "SELECT * FROM dib_reserves WHERE exemplar_id = '$id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return json_encode(['response' => 'OK', 'reserves' => $rows]);
    } else {
        return json_encode(['response' => 'no-data']);
    }
}

function reservar($conn, $exemplar_id, $usuari_id, $data_inici, $estat = 'pendent', $prolongada = false, $motiu_prolongacio = '') {
    $data_fi = date('Y-m-d', strtotime($data_inici . ' + 7 days'));

    $sql = "INSERT INTO `dib_reserves` (`reserva`, `exemplar_id`, `usuari_id`, `data_inici`, `data_fi`, `estat`, `prolongada`, `motiu_prolongacio`) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    $prolongada = $prolongada ? 1 : 0;

    mysqli_stmt_bind_param($stmt, "iisssis", $exemplar_id, $usuari_id, $data_inici, $data_fi, $estat, $prolongada, $motiu_prolongacio);

    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo insertar la reserva en la base de datos: ' . mysqli_error($conn)]);
    }
}


function modificarLlibre($conn, $id, $cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat){
    $sql = "UPDATE `dib_cataleg` SET `ID_CATÀLEG` = ?, `ID_BIBLIOTECA` = ?, `TITOL` = ?, `ISBN` = ?, `CDU` = ?, `FORMAT` = ?, `AUTOR` = ?,
            `EDITORIAL` = ?, `LLOC` = ?, `COL·LECCIÓ` = ?, `PAÍS` = ?, `DATA` = ?, `LLENGUA` = ?, 
            `MATERIA` = ?, `DESCRIPTOR` = ?, `NIVELL` = ?, `RESUM` = ?, `URL` = ?, `ADREÇA` = ?, 
            `DIMENSIÓ` = ?, `VOLÚM` = ?, `PÀGINES` = ?, `PROC` = ?, `CARC` = ?, `CAMP_LLIURE` = ?, 
            `NPRES` = ?, `REC` = ?, `ESTAT` = ? WHERE `NUMERO` = ?";
            
    $conn->set_charset("utf8mb4");
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
    }
    
    mysqli_stmt_bind_param($stmt, 'iissssssssssssssssssissssisssi', 
        $cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio,
        $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url,
        $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure,
        $npres, $rec, $estat, $id);

    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        $error = mysqli_stmt_error($stmt);
        return json_encode(['response' => 'ERROR', 'message' => 'Error al ejecutar la consulta: ' . $error]);
    }
}

function crearLlibre($conn, $cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat) {
    // Asegurar que todos los valores no proporcionados son convertidos a NULL o a un valor por defecto adecuado
    $params = [$cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat];
    $types = ''; // Inicializar la cadena de tipos
    foreach ($params as &$param) {
        if ($param === '') {
            $param = NULL; // Convertir cadenas vacías a NULL para campos que pueden aceptarlo
        }
        $types .= is_int($param) ? 'i' : 's'; // Añadir especificador de tipo basado en el tipo de dato
    }
    $types .= 'i'; // Añadir un 'i' para el último parámetro que es el ID y es un entero

    $sql = "INSERT INTO `dib_cataleg` (`ID_CATÀLEG`, `ID_BIBLIOTECA`, `NUMERO`, `ISBN`, `CDU`, `FORMAT`, `TITOL`, `AUTOR`, `EDITORIAL`, `LLOC`, `COL·LECCIÓ`, `PAÍS`, `DATA`, `LLENGUA`, `MATERIA`, `DESCRIPTOR`, `NIVELL`, `RESUM`, `URL`, `ADREÇA`, `DIMENSIÓ`, `VOLÚM`, `PÀGINES`, `PROC`, `CARC`, `CAMP_LLIURE`, `NPRES`, `REC`, `ESTAT`) VALUES (?, ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
        return;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params); // Usar desempaquetado de argumentos para pasar variables

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo json_encode(['response' => 'OK']);
    } else {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al ejecutar la consulta: ' . $error]);
    }
}