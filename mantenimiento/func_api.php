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
    $sql = "SELECT dib_cataleg.TITOL AS nom, dib_exemplars.ESTAT as estadoActual, dib_cataleg.NUMERO as id FROM `dib_cataleg` INNER JOIN `dib_exemplars` ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR WHERE `TITOL` LIKE '%$llibre%'";
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