<?php
# © Joan Aneas

/*___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (ASCII art)*/


function peticionSQL()
{
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

function cercaLlibresLite($conn, $llibre)
{
    $sql = "SELECT DISTINCT dib_cataleg.TITOL 
            AS nom, dib_exemplars.ESTAT 
            as estadoActual, dib_cataleg.NUMERO 
            as id 
            FROM `dib_cataleg` INNER JOIN `dib_exemplars` 
            ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR 
            WHERE `TITOL` LIKE '%$llibre%' 
            OR AUTOR LIKE '%$llibre%'";

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

function cercaLlibresAll($conn, $libroId)
{
    $conn->set_charset("utf8mb4");
    $sql = "SELECT DISTINCT
                dib_cataleg.*,
                COUNT(dib_exemplars.IDENTIFICADOR) AS num_exemplars
            FROM 
                `dib_cataleg`
            LEFT JOIN 
                `dib_exemplars` ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR
            WHERE 
                dib_cataleg.`NUMERO` = ?
            GROUP BY 
                dib_cataleg.NUMERO;";

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


function cercaLlibresFull($conn, $llibre)
{
    $sql = "SELECT DISTINCT dib_cataleg.TITOL AS nom,
                dib_exemplars.ESTAT as estadoActual,
                dib_cataleg.URL as 'url',
                dib_cataleg.NIVELL as 'nivell',
                dib_cataleg.RESUM as 'resum',
                dib_cataleg.AUTOR as 'autor' 
            FROM `dib_cataleg` 
            INNER JOIN `dib_exemplars` ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR 
            WHERE `NUMERO` = '$llibre';";

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

function getStars($conn, $llibre)
{
    $sql = "SELECT AVG(puntuacio) as estrelles FROM dib_estrelles WHERE exemplar_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'estrelles' => $row['estrelles']]);
    } else {
        echo json_encode(['response' => 'no-data']);
    }
}

function reservarLibro($conn, $titulo, $fechaInicio, $fechaFin)
{
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

function getReserves($conn, $id)
{
    $sql = "SELECT * FROM dib_reserves WHERE exemplar_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        echo json_encode(['response' => 'OK', 'reserves' => $rows]);
    } else {
        echo json_encode(['response' => 'no-data']);
    }
}

function reservar($conn, $exemplar_id, $usuari_id, $data_inici, $estat = 'pendent', $prolongada = false, $motiu_prolongacio = '')
{
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


function modificarLlibre($id, $cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat)
{
    $conn = peticionSQL();
    $conn->set_charset("utf8mb4");
    $sql = "UPDATE `dib_cataleg` SET `ID_CATÀLEG`=?, `ID_BIBLIOTECA`=?, `ISBN`=?, 
            `CDU`=?, `FORMAT`=?, `TITOL`=?, `AUTOR`=?, `EDITORIAL`=?, `LLOC`=?,
            `COL·LECCIÓ`=?, `PAÍS`=?, `DATA`=?, `LLENGUA`=?, `MATERIA`=?, `DESCRIPTOR`=?, 
            `NIVELL`=?, `RESUM`=?, `URL`=?, `ADREÇA`=?, `DIMENSIÓ`=?, `VOLÚM`=?, 
            `PÀGINES`=?, `PROC`=?, `CARC`=?, `CAMP_LLIURE`=?, `NPRES`=?, `REC`=?, 
            `ESTAT`=? WHERE `NUMERO` = ?";

    $stmt = mysqli_prepare($conn, $sql);

    $params = [
        $cataleg, $biblioteca, $titol, $isbn, $cdu,
        $format, $autor, $editorial, $lloc, $colleccio,
        $pais, $data, $llengua, $materia, $descriptor,
        $nivell, $resum, $url, $adreca, $dimensio, $volum,
        $pagines, $proc, $carc, $camp_lliure, $npres, $rec,
        $estat, $id
    ];

    for ($i = 0; $i < count($params); $i++) {
        if ($params[$i] == '') $params[$i] = NULL;
    }

    if (!$stmt) {
        return json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
    }

    mysqli_stmt_bind_param(
        $stmt,
        'iisssssssssisssssssssisssissi', # 30 parámetros
        $cataleg,
        $biblioteca,
        $isbn,
        $cdu,
        $format,
        $titol,
        $autor,
        $editorial,
        $lloc,
        $colleccio,
        $pais,
        $data,
        $llengua,
        $materia,
        $descriptor,
        $nivell,
        $resum,
        $url,
        $adreca,
        $dimensio,
        $volum,
        $pagines,
        $proc,
        $carc,
        $camp_lliure,
        $npres,
        $rec,
        $estat,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        $error = mysqli_stmt_error($stmt);
        return json_encode(['response' => 'ERROR', 'message' => 'Error al ejecutar la consulta: ' . $error]);
    }
}

function getLastLlibre()
{
    $conn = peticionSQL();
    $sql = "SELECT MAX(NUMERO) as last FROM dib_cataleg";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $row['last'] = $row['last'] + 1;
        return json_encode(['response' => 'OK', 'last' => $row['last']]);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => $conn->error]);
    }
}

function crearLlibre(
    $cataleg,
    $biblioteca,
    $id,
    $exemplars,
    $titol,
    $isbn,
    $cdu,
    $format,
    $autor,
    $editorial,
    $lloc,
    $colleccio,
    $pais,
    $data,
    $llengua,
    $materia,
    $descriptor,
    $nivell,
    $resum,
    $url,
    $adreca,
    $dimensio,
    $volum,
    $pagines,
    $proc,
    $carc,
    $camp_lliure,
    $npres,
    $rec,
    $estat
) {
    $conn = peticionSQL();
    $conn->set_charset("utf8mb4");

    $sql = "INSERT INTO `dib_cataleg` (`ID_CATÀLEG`, `ID_BIBLIOTECA`, `NUMERO`, `ISBN`, `CDU`, 
            `FORMAT`, `TITOL`, `AUTOR`, `EDITORIAL`, `LLOC`, `COL·LECCIÓ`, `PAÍS`, `DATA`, `LLENGUA`, 
            `MATERIA`, `DESCRIPTOR`, `NIVELL`, `RESUM`, `URL`, `ADREÇA`, `DIMENSIÓ`, `VOLÚM`, `PÀGINES`, 
            `PROC`, `CARC`, `CAMP_LLIURE`, `NPRES`, `REC`, `ESTAT`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
        return;
    }

    mysqli_stmt_bind_param(
        $stmt,
        'iiisssssssssisssssssssisssiss',
        $cataleg,
        $biblioteca,
        $id,
        $isbn,
        $cdu,
        $format,
        $titol,
        $autor,
        $editorial,
        $lloc,
        $colleccio,
        $pais,
        $data,
        $llengua,
        $materia,
        $descriptor,
        $nivell,
        $resum,
        $url,
        $adreca,
        $dimensio,
        $volum,
        $pagines,
        $proc,
        $carc,
        $camp_lliure,
        $npres,
        $rec,
        $estat
    );

    if (mysqli_stmt_execute($stmt)) {
        # Signatura = CDU + Autor (JN Pan: Juvenil Novela Panicello, Victor)
        # Només 3 primeres lletres.
        $signatura = $cdu . " " . str_split($autor, 3)[0];
        $exemplars = (int) $exemplars;
        for ($i = 0; $i < $exemplars; $i++) {
            $j = $i + 1;
            $sql = "INSERT INTO `dib_exemplars`(`IDENTIFICADOR`, `NUMERO_EXEMPLAR`, `SIGNATURA_EXEMPLAR`, `SITUACIO`, `ESTAT`) 
                    VALUES (?, ?, ?, 'Préstec', 'Disponible')";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'iis', $id, $j, $signatura);
            mysqli_stmt_execute($stmt);
        }
        echo json_encode(['response' => 'OK', "message" => "llibre creat"]);
    } else {
        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al ejecutar la consulta: ' . $error]);
    }
}


function valorarLlibre($idLlibre, $idUsuari, $estrelles, $comentari)
{
    $conn = peticionSQL();
    $sql = "INSERT INTO `dib_valoracions`(`id_comentari`, `usuari_id`, `exemplar_id`, `data_comentari`, `comentari`) 
    VALUES (NULL, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $idLlibre, $idUsuari, $estrelles);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['response' => 'OK']);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo insertar la valoración en la base de datos']);
    }
}
