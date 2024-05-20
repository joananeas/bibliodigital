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
    
    # Encontrar esto fue un dolor de cabeza...
    $conn->set_charset("utf8mb4");
    return $conn;
}

function cercaLlibresLite($conn, $llibre){   
    $sql = ""; # Innit before if check

    # Esto comprueba si se está buscando una categoría o un libro
    $llibre = strtolower($llibre);
    $llibre = mysqli_real_escape_string($conn, $llibre);

    if (str_contains($llibre, "c:")) {
        $llibre = str_replace("c:", "", $llibre); # Previene usar "c:" en la consulta (se buguea)
        $sql = "SELECT DISTINCT dib_cataleg.TITOL AS nom, 
                dib_cataleg.NUMERO AS id,
                (CASE 
                    WHEN EXISTS (SELECT 1 FROM dib_exemplars de WHERE de.IDENTIFICADOR = dib_cataleg.NUMERO AND de.ESTAT = 'Disponible') 
                    THEN 'Disponible'
                    ELSE 'Prestat' 
                END) AS estadoActual
                FROM dib_cataleg
                INNER JOIN dib_exemplars ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR 
                WHERE dib_cataleg.MATERIA LIKE '%$llibre%'";
    } else {
        $sql = "SELECT DISTINCT dib_cataleg.TITOL AS nom, 
                dib_cataleg.NUMERO AS id,
                (CASE 
                    WHEN EXISTS (SELECT 1 FROM dib_exemplars de WHERE de.IDENTIFICADOR = dib_cataleg.NUMERO AND de.ESTAT = 'Disponible') 
                    THEN 'Disponible'
                    ELSE 'Prestat' 
                END) AS estadoActual
                FROM dib_cataleg
                INNER JOIN dib_exemplars ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR 
                WHERE dib_cataleg.TITOL LIKE '%$llibre%' 
                OR dib_cataleg.AUTOR LIKE '%$llibre%'";
    }

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $rows = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $i++;
            # Si datos están sucios
            if  (str_contains($row['estadoActual'], "Disponible")) {
                $row['estadoActual'] = "Disponible";
            } else {
                $row['estadoActual'] = "Prestat";
            }
            $rows[] = $row;
            
            if ($i > 5) { break; }
        }
        echo json_encode(['response' => 'OK', 'llibres' => $rows]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontraron libros']);
    }
}

function cercaExemplars($conn, $llibre){
    $sql = "SELECT COUNT(dib_exemplars.IDENTIFICADOR) AS num_exemplars
            FROM `dib_exemplars`
            WHERE dib_exemplars.IDENTIFICADOR = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result)['num_exemplars'];
}

function cercaLlibresAll($conn, $libroId){
    $conn->set_charset("utf8mb4");
    $sql = "SELECT DISTINCT
                dib_cataleg.*,
                COUNT(dib_exemplars.IDENTIFICADOR) AS num_exemplars
            FROM `dib_cataleg`
            LEFT JOIN `dib_exemplars` ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR
            WHERE dib_cataleg.`NUMERO` = ?
            GROUP BY dib_cataleg.NUMERO";

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
    $sql = "SELECT DISTINCT dib_cataleg.TITOL AS nom,
                dib_exemplars.ESTAT as estadoActual,
                dib_cataleg.URL as 'url',
                dib_cataleg.MATERIA as 'categoria',
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

function getStars($conn, $llibre) {
    $sql = "SELECT AVG(puntuacio) as estrelles FROM dib_estrelles WHERE exemplar_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'estrelles' => round($row['estrelles'])]);
    } else {
        echo json_encode(['response' => 'no-data']);
    }
}

function getReserves($conn, $id){
    $sql = "SELECT `reserva`, dib_cataleg.TITOL AS llibre, `usuari_id`, `data_inici`, `data_fi`, dib_reserves.`estat`, `prolongada`, `motiu_prolongacio` 
            FROM dib_reserves 
            JOIN dib_exemplars ON dib_reserves.exemplar_id = dib_exemplars.IDENTIFICADOR 
            JOIN dib_cataleg ON dib_exemplars.IDENTIFICADOR = dib_cataleg.NUMERO WHERE exemplar_id = ?";
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

function reservar($conn, $exemplar_id, $usuari_id, $data_inici, $estat = 1, $prolongada = false, $motiu_prolongacio = ''){
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

function modificarLlibre($id, $exemplars, $OGexemplars, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat) {
    $conn = peticionSQL();
    $conn->set_charset("utf8mb4");

    $sql = "UPDATE `dib_cataleg` SET `ISBN`=?, `CDU`=?, `FORMAT`=?, `TITOL`=?, `AUTOR`=?, `EDITORIAL`=?, `LLOC`=?, `COLLECCIO`=?, `PAIS`=?, `DATA`=?, `LLENGUA`=?, `MATERIA`=?, `DESCRIPTOR`=?, `NIVELL`=?, `RESUM`=?, `URL`=?, `ADRECA`=?, `DIMENSIO`=?, `VOLUM`=?, `PAGINES`=?, `PROC`=?, `CARC`=?, `CAMP_LLIURE`=?, `NPRES`=?, `REC`=?, `ESTAT`=? WHERE `NUMERO` = ?";

    $stmt = mysqli_prepare($conn, $sql);

    $params = [
        $isbn, $cdu, $format, $titol, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat, $id
    ];

    for ($i = 0; $i < count($params); $i++) {
        if ($params[$i] == '') $params[$i] = NULL;
    }

    if (!$stmt) {
        return json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
    }

    mysqli_stmt_bind_param($stmt, 'sssssssssisssssssssisssissi', ...$params);

    if (mysqli_stmt_execute($stmt)) {
        if ($exemplars > $OGexemplars) {
            $diff = $exemplars - $OGexemplars;
            for ($i = 0; $i < $diff; $i++) {
                $sqlInsert = "INSERT INTO `dib_exemplars` (`IDENTIFICADOR`, `NUMERO_EXEMPLAR`, `SIGNATURA_EXEMPLAR`, `SITUACIO`, `ESTAT`) VALUES (?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $sqlInsert);

                if (!$stmtInsert) {
                    return json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
                }

                $numero_exemplar = $OGexemplars + $i + 1;
                $signatura_exemplar = $cdu . " " . substr($autor, 0, 3);
                $situacio = 'Préstec';
                $estat_exemplar = 'Disponible';

                mysqli_stmt_bind_param($stmtInsert, 'iisss', $id, $numero_exemplar, $signatura_exemplar, $situacio, $estat_exemplar);

                if (!mysqli_stmt_execute($stmtInsert)) {
                    $error = mysqli_stmt_error($stmtInsert);
                    return json_encode(['response' => 'ERROR', 'message' => 'Error al insertar la consulta: ' . $error]);
                }
            }
        } elseif ($exemplars < $OGexemplars) {
            $diff = $OGexemplars - $exemplars;
            $sqlDelete = "DELETE FROM `dib_exemplars` WHERE `IDENTIFICADOR` = ? ORDER BY `NUMERO_EXEMPLAR` DESC LIMIT ?";
            $stmtDelete = mysqli_prepare($conn, $sqlDelete);

            if (!$stmtDelete) {
                return json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
            }

            mysqli_stmt_bind_param($stmtDelete, 'ii', $id, $diff);

            if (!mysqli_stmt_execute($stmtDelete)) {
                $error = mysqli_stmt_error($stmtDelete);
                return json_encode(['response' => 'ERROR', 'message' => 'Error al eliminar la consulta: ' . $error]);
            }
        }

        return json_encode(['response' => 'OK']);
    } else {
        $error = mysqli_stmt_error($stmt);
        return json_encode(['response' => 'ERROR', 'message' => 'Error al ejecutar la consulta: ' . $error]);
    }
}

function getLastLlibre(){
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

function crearLlibre($id, $exemplars, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat) {
    $conn = peticionSQL();
    $conn->set_charset("utf8mb4");

    $sql = "INSERT INTO `dib_cataleg` (`ID_CATALEG`, `ID_BIBLIOTECA`, `NUMERO`, `ISBN`, `CDU`, 
            `FORMAT`, `TITOL`, `AUTOR`, `EDITORIAL`, `LLOC`, `COLLECCIO`, `PAIS`, `DATA`, `LLENGUA`, 
            `MATERIA`, `DESCRIPTOR`, `NIVELL`, `RESUM`, `URL`, `ADRECA`, `DIMENSIO`, `VOLUM`, `PAGINES`, 
            `PROC`, `CARC`, `CAMP_LLIURE`, `NPRES`, `REC`, `ESTAT`) 
            VALUES (NULL, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
        return;
    }

    mysqli_stmt_bind_param(
        $stmt,
        'isssssssssisssssssssisssiss',
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

function valorarLlibre($idLlibre, $idUsuari, $estrelles, $comentari){
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

function prestarExemplar($id_reserva) {
    $conn = peticionSQL();
    $sql_reserva = "SELECT exemplar_id, usuari_id FROM dib_reserves WHERE reserva = ?";
    $stmt_reserva = mysqli_prepare($conn, $sql_reserva);
    mysqli_stmt_bind_param($stmt_reserva, "s", $id_reserva);
    mysqli_stmt_execute($stmt_reserva);
    mysqli_stmt_bind_result($stmt_reserva, $exemplar_id, $usuari_id);
    mysqli_stmt_fetch($stmt_reserva);
    mysqli_stmt_close($stmt_reserva);
    #echo $exemplar_id; DEBUGGING

    if ($exemplar_id && $usuari_id) {
        $sql_exemplar = "SELECT NUMERO_EXEMPLAR 
                        FROM dib_exemplars 
                        JOIN dib_cataleg ON dib_exemplars.IDENTIFICADOR = dib_cataleg.NUMERO
                        WHERE dib_exemplars.IDENTIFICADOR = ? AND dib_exemplars.ESTAT = 'Disponible' 
                        ORDER BY dib_exemplars.NUMERO_EXEMPLAR DESC 
                        LIMIT 1";
        $stmt_exemplar = mysqli_prepare($conn, $sql_exemplar);
        mysqli_stmt_bind_param($stmt_exemplar, "i", $exemplar_id);
        mysqli_stmt_execute($stmt_exemplar);
        mysqli_stmt_bind_result($stmt_exemplar, $exemplar_num);
        mysqli_stmt_fetch($stmt_exemplar);
        mysqli_stmt_close($stmt_exemplar);

        if ($exemplar_num) {
            $data_inici = date("Y-m-d");
            $data_devolucio = date("Y-m-d", strtotime("+1 month"));

            $sql_prestec = "INSERT INTO dib_prestecs (id_prestec, exemplar_id, exemplar_num, usuari_id, data_inici, data_devolucio, data_real_tornada, estat, comentaris) 
                            VALUES (NULL, ?, ?, ?, ?, ?, NULL, 1, 'Pendent del bibliotecari.')";
            $stmt_prestec = mysqli_prepare($conn, $sql_prestec);
            mysqli_stmt_bind_param($stmt_prestec, "iiiss", $exemplar_id, $exemplar_num, $usuari_id, $data_inici, $data_devolucio);
            if (mysqli_stmt_execute($stmt_prestec)) {
                $sql_update_exemplar = "UPDATE dib_exemplars SET ESTAT = 'Prestat' WHERE IDENTIFICADOR = ? AND NUMERO_EXEMPLAR = ?";
                $stmt_update_exemplar = mysqli_prepare($conn, $sql_update_exemplar);
                mysqli_stmt_bind_param($stmt_update_exemplar, "ii", $exemplar_id, $exemplar_num);
                mysqli_stmt_execute($stmt_update_exemplar);
                mysqli_stmt_close($stmt_update_exemplar);

                echo json_encode(['response' => 'OK']);
            } else {
                echo json_encode(['response' => 'ERROR', 'message' => mysqli_error($conn)]);
            }
            mysqli_stmt_close($stmt_prestec);
        } else {
            echo json_encode(['response' => 'ERROR', 'message' => 'No hay ejemplares disponibles']);
        }
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'Reserva no encontrada o datos incompletos']);
    }

    $conn->close();
}

function viewAllPrestecs() {
    $conn = peticionSQL();

    $sql = "SELECT DISTINCT dib_prestecs.id_prestec, dib_cataleg.TITOL AS llibre, dib_usuaris.email AS usuari, dib_prestecs.data_inici, dib_prestecs.data_devolucio, dib_prestecs.data_real_tornada, dib_prestecs.estat, dib_prestecs.comentaris 
            FROM dib_prestecs 
            JOIN dib_exemplars ON dib_prestecs.exemplar_id = dib_exemplars.IDENTIFICADOR 
            JOIN dib_cataleg ON dib_exemplars.IDENTIFICADOR = dib_cataleg.NUMERO 
            JOIN dib_usuaris ON dib_prestecs.usuari_id = dib_usuaris.usuari
            ORDER BY dib_prestecs.estat = 'pendent'; ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $prestecs = array();
        while($row = $result->fetch_assoc()) {
            $prestecs[] = $row;
        }
        echo json_encode(array('response' => 'OK', 'message' => $prestecs));
    } else {
        echo json_encode(array('response' => 'ERROR', 'message' => 'No records found'));
    }

    $conn->close();
}

function viewAllReserves(){
    $conn = peticionSQL();

    $sql = "SELECT DISTINCT dib_reserves.reserva, dib_cataleg.TITOL AS llibre, dib_usuaris.email AS usuari, dib_reserves.data_inici, dib_reserves.data_fi, dib_reserves.estat, dib_reserves.prolongada, dib_reserves.motiu_prolongacio 
            FROM dib_reserves 
            JOIN dib_exemplars ON dib_reserves.exemplar_id = dib_exemplars.IDENTIFICADOR 
            JOIN dib_cataleg ON dib_exemplars.IDENTIFICADOR = dib_cataleg.NUMERO 
            JOIN dib_usuaris ON dib_reserves.usuari_id = dib_usuaris.usuari";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $reserves = array();
        while($row = $result->fetch_assoc()) {
            $reserves[] = $row;
        }
        echo json_encode(array('response' => 'OK', 'message' => $reserves));
    } else {
        echo json_encode(array('response' => 'ERROR', 'message' => 'No records found'));
    }

    $conn->close();
}

function getAutor($id_llibre){
    $conn = peticionSQL();
    $sql = "SELECT AUTOR FROM dib_cataleg WHERE NUMERO = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'autor' => $row['AUTOR']]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontraron detalles para el libro']);
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}

function getCDU($id_llibre){
    $conn = peticionSQL();
    $sql = "SELECT CDU FROM dib_cataleg WHERE NUMERO = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'cdu' => $row['CDU']]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontraron detalles para el libro']);
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}

function autoritzarPrestec($id_prestec) {
    $conn = peticionSQL();

    // Obtener los valores de exemplar_id y usuari_id del préstamo
    $sqlSelect = "SELECT exemplar_id, usuari_id FROM dib_prestecs WHERE id_prestec = ?";
    $stmtSelect = mysqli_prepare($conn, $sqlSelect);
    mysqli_stmt_bind_param($stmtSelect, "i", $id_prestec);
    mysqli_stmt_execute($stmtSelect);
    mysqli_stmt_bind_result($stmtSelect, $exemplar_id, $usuari_id);
    mysqli_stmt_fetch($stmtSelect);
    mysqli_stmt_close($stmtSelect);

    // Verificar si se obtuvieron los valores de exemplar_id y usuari_id
    if (empty($exemplar_id) || empty($usuari_id)) {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo obtener los datos del préstamo']);
    }

    // Iniciar la transacción
    mysqli_begin_transaction($conn);

    // Actualizar el estado del préstamo
    $sqlUpdatePrestec = "UPDATE dib_prestecs SET estat = 2, comentaris = 'Préstec confirmat' WHERE id_prestec = ?";
    $stmtUpdatePrestec = mysqli_prepare($conn, $sqlUpdatePrestec);
    mysqli_stmt_bind_param($stmtUpdatePrestec, "i", $id_prestec);

    if (!mysqli_stmt_execute($stmtUpdatePrestec)) {
        mysqli_rollback($conn);
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo autorizar el préstamo']);
    }

    mysqli_stmt_close($stmtUpdatePrestec);

    // Actualizar el estado de la reserva correspondiente
    $sqlUpdateReserva = "UPDATE dib_reserves SET estat = 3 WHERE exemplar_id = ? AND usuari_id = ?";
    $stmtUpdateReserva = mysqli_prepare($conn, $sqlUpdateReserva);
    mysqli_stmt_bind_param($stmtUpdateReserva, "ii", $exemplar_id, $usuari_id);

    if (!mysqli_stmt_execute($stmtUpdateReserva)) {
        mysqli_rollback($conn);
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo actualizar la reserva']);
    }

    mysqli_stmt_close($stmtUpdateReserva);

    // Confirmar la transacción
    mysqli_commit($conn);

    // Cerrar la conexión
    mysqli_close($conn);

    return json_encode(['response' => 'OK']);
}

function denegarPrestec($id_prestec) {
    $conn = peticionSQL();

    // Obtener los valores de exemplar_id y usuari_id del préstamo
    $sqlSelect = "SELECT exemplar_id, usuari_id FROM dib_prestecs WHERE id_prestec = ?";
    $stmtSelect = mysqli_prepare($conn, $sqlSelect);
    mysqli_stmt_bind_param($stmtSelect, "i", $id_prestec);
    mysqli_stmt_execute($stmtSelect);
    mysqli_stmt_bind_result($stmtSelect, $exemplar_id, $usuari_id);
    mysqli_stmt_fetch($stmtSelect);
    mysqli_stmt_close($stmtSelect);

    // Verificar si se obtuvieron los valores de exemplar_id y usuari_id
    if (empty($exemplar_id) || empty($usuari_id)) {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo obtener los datos del préstamo']);
    }

    // Iniciar la transacción
    mysqli_begin_transaction($conn);

    // Actualizar el estado del préstamo
    $sqlUpdatePrestec = "UPDATE dib_prestecs SET estat = 4, comentaris = 'Préstec Cancel·lat' WHERE id_prestec = ?";
    $stmtUpdatePrestec = mysqli_prepare($conn, $sqlUpdatePrestec);
    mysqli_stmt_bind_param($stmtUpdatePrestec, "i", $id_prestec);

    if (!mysqli_stmt_execute($stmtUpdatePrestec)) {
        mysqli_rollback($conn);
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo denegar el préstamo']);
    }

    mysqli_stmt_close($stmtUpdatePrestec);

    // Actualizar el estado de la reserva correspondiente
    $sqlUpdateReserva = "UPDATE dib_reserves SET estat = 4 WHERE exemplar_id = ? AND usuari_id = ?";
    $stmtUpdateReserva = mysqli_prepare($conn, $sqlUpdateReserva);
    mysqli_stmt_bind_param($stmtUpdateReserva, "ii", $exemplar_id, $usuari_id);

    if (!mysqli_stmt_execute($stmtUpdateReserva)) {
        mysqli_rollback($conn);
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo actualizar la reserva']);
    }

    mysqli_stmt_close($stmtUpdateReserva);

    // Confirmar la transacción
    mysqli_commit($conn);

    // Cerrar la conexión
    mysqli_close($conn);

    return json_encode(['response' => 'OK']);
}

function retornarPrestec($id_prestec){
    $conn = peticionSQL();
    $sql = "UPDATE dib_prestecs SET estat = 3, data_real_tornada = curdate(), comentaris = 'Préstec finalitzat, tornat per usuari.' WHERE id_prestec = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_prestec);
    if (mysqli_stmt_execute($stmt)) {
        $sql_get_exemplar = "SELECT exemplar_id FROM dib_prestecs WHERE id_prestec = ?";
        $stmt_get_exemplar = mysqli_prepare($conn, $sql_get_exemplar);
        mysqli_stmt_bind_param($stmt_get_exemplar, "i", $id_prestec);
        mysqli_stmt_execute($stmt_get_exemplar);
        mysqli_stmt_bind_result($stmt_get_exemplar, $exemplar_id);
        mysqli_stmt_fetch($stmt_get_exemplar);
        mysqli_stmt_close($stmt_get_exemplar);

        $sql_update_exemplar = "UPDATE dib_exemplars SET ESTAT = 'Disponible' WHERE IDENTIFICADOR = ? AND NUMERO_EXEMPLAR = (SELECT exemplar_num FROM dib_prestecs WHERE id_prestec = ?)";
        $stmt_update_exemplar = mysqli_prepare($conn, $sql_update_exemplar);
        mysqli_stmt_bind_param($stmt_update_exemplar, "ii", $exemplar_id, $id_prestec);
        mysqli_stmt_execute($stmt_update_exemplar);
        mysqli_stmt_close($stmt_update_exemplar);

        return json_encode(['response' => 'OK']);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo retornar el préstamo']);
    }
}

function eliminarPrestec($id_prestec){
    $conn = peticionSQL();
    $sql = "DELETE FROM dib_prestecs WHERE id_prestec = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_prestec);
    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo eliminar el préstamo']);
    }
}

function crearChat($nom){
    $conn = peticionSQL();
    $sql = "INSERT INTO dib_xats (id_xat, nom_xat) VALUES (NULL, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $nom);
    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo crear el chat']);
    }
}

function getChats(){
    $conn = peticionSQL();
    $sql = "SELECT * FROM dib_xats";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $chats = array();
        while($row = $result->fetch_assoc()) {
            $chats[] = $row;
        }
        echo json_encode(array('response' => 'OK', 'message' => $chats));
    } else {
        echo json_encode(array('response' => 'ERROR', 'message' => 'No records found'));
    }
    $conn->close();
}

function getMessages($id_chat){
    $conn = peticionSQL();
    $sql = "SELECT 
                dm.id_missatge, 
                dm.xat_id, 
                dm.usuari_id, 
                dm.data_enviament, 
                dm.missatge, 
                du.pfp AS pfp,
                du.email AS email,
                du.nickname AS nickname
            FROM 
                dib_missatges dm
            INNER JOIN 
                dib_usuaris du 
            ON 
                dm.usuari_id = du.usuari
            WHERE 
                dm.xat_id = ?
            ORDER BY 
                dm.data_enviament ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_chat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $missatges = array();
        while($row = $result->fetch_assoc()) {
            $missatges[] = $row;
        }
        echo json_encode(array('response' => 'OK', 'message' => $missatges));
    } else {
        echo json_encode(array('response' => 'ERROR', 'message' => 'No records found'));
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}

function sendMessage($id_chat, $id_usuari, $missatge){
    $conn = peticionSQL();
    $sql = "INSERT INTO `dib_missatges`(`id_missatge`, `xat_id`, `usuari_id`, `data_enviament`, `missatge`) 
            VALUES (NULL, ?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        return json_encode(['response' => 'ERROR', 'message' => 'Error en la preparación de la consulta: ' . mysqli_error($conn)]);
    }
    
    mysqli_stmt_bind_param($stmt, "iis", $id_chat, $id_usuari, $missatge);
    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo enviar el mensaje: ' . mysqli_stmt_error($stmt)]);
    }
}

function getCategories(){
    $conn = peticionSQL();
    $sql = "SELECT DISTINCT `MATERIA` FROM dib_cataleg";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $categories = array();
        while($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        echo json_encode(array('response' => 'OK', 'message' => $categories));
    } else {
        echo json_encode(array('response' => 'ERROR', 'message' => 'No records found'));
    }
    $conn->close();
}

# Función para obtener la disponibilidad de un libro antes de reservar (rellenar calendario)
function getDisponibilitatLlibrePerReserves($id_llibre) {
    $conn = peticionSQL();
    $sql = "SELECT dr.estat AS estat_reserva
            FROM dib_reserves dr
            WHERE dr.exemplar_id = ?
            AND dr.data_fi >= CURDATE()
            AND (dr.estat = 1 OR dr.estat = 2)"; // 1 = Pendent, 2 = Confirmada

    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt === false) {
        echo json_encode(array('response' => 'E', 'message' => 'Prepare failed: ' . mysqli_error($conn)));
        return;
    }

    mysqli_stmt_bind_param($stmt, "i", $id_llibre);

    if (mysqli_stmt_execute($stmt)) {
        $reserves = array();
        $result = mysqli_stmt_get_result($stmt); // Initialize the $result variable

        if ($result) {
            while($row = $result->fetch_assoc()) {
                $reserves[] = $row;
            }
            echo json_encode(array('response' => 'OK', 'message' => $reserves));
        } else {
            echo json_encode(array('response' => 'E', 'message' => 'Error fetching result: ' . mysqli_error($conn)));
        }
    } else {
        echo json_encode(array('response' => 'E', 'message' => 'Execute failed: ' . mysqli_stmt_error($stmt)));
    }

    mysqli_stmt_close($stmt);
    $conn->close();
}

function puntuar($user_id, $id_llibre, $puntuacio){
    $conn = peticionSQL();
    
    // Primero, verifica si el usuario ya ha puntuado
    $sql = "SELECT * FROM `dib_estrelles` WHERE `usuari_id` = ? AND `exemplar_id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $id_llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $message = '';
    if (mysqli_num_rows($result) > 0) {
        // Si el usuario ya ha puntuado, actualiza la puntuación existente
        $sql = "UPDATE `dib_estrelles` SET `puntuacio` = ?, `data_puntuacio` = curdate() WHERE `usuari_id` = ? AND `exemplar_id` = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $puntuacio, $user_id, $id_llibre);
        $message = "updated";
    } else {
        // Si el usuario no ha puntuado, inserta una nueva puntuación
        $sql = "INSERT INTO `dib_estrelles`(`id_estrella`, `usuari_id`, `puntuacio`, `exemplar_id`, `data_puntuacio`) VALUES (NULL, ?, ?, ?, curdate())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $puntuacio, $id_llibre);
        $message = "inserted";
    }

    // Ejecuta la consulta preparada
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['response' => 'OK', 'message' => $message]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo insertar o actualizar la puntuación en la base de datos']);
    }
}

function sendComment($user_id, $id_llibre, $comentari){
    $conn = peticionSQL();

    // Comprobar si ya existe un comentario del usuario para el libro
    $checkSql = "SELECT COUNT(*) AS count FROM dib_comentaris WHERE usuari_id = ? AND exemplar_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $user_id, $id_llibre);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_bind_result($checkStmt, $count);
    mysqli_stmt_fetch($checkStmt);
    mysqli_stmt_close($checkStmt);

    if ($count > 0) {
        // Actualizar el comentario existente
        $updateSql = "UPDATE dib_comentaris SET comentari = ?, data_comentari = curdate() WHERE usuari_id = ? AND exemplar_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "sii", $comentari, $user_id, $id_llibre);
        if (mysqli_stmt_execute($updateStmt)) {
            echo json_encode(['response' => 'OK']);
        } else {
            echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo actualizar el comentario en la base de datos']);
        }
        mysqli_stmt_close($updateStmt);
    } else {
        // Insertar un nuevo comentario
        $insertSql = "INSERT INTO dib_comentaris (id_comentari, usuari_id, exemplar_id, data_comentari, comentari) 
                      VALUES (NULL, ?, ?, curdate(), ?)";
        $insertStmt = mysqli_prepare($conn, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "iis", $user_id, $id_llibre, $comentari);
        if (mysqli_stmt_execute($insertStmt)) {
            echo json_encode(['response' => 'OK']);
        } else {
            echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo insertar el comentario en la base de datos']);
        }
        mysqli_stmt_close($insertStmt);
    }
}

function getComments($id_llibre){
    $conn = peticionSQL();
    $sql = "SELECT dc.comentari, dc.data_comentari, du.email AS usuari
            FROM dib_comentaris dc
            JOIN dib_usuaris du ON dc.usuari_id = du.usuari
            WHERE dc.exemplar_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $comentaris = array();
        while($row = $result->fetch_assoc()) {
            $comentaris[] = $row;
        }
        echo json_encode(array('response' => 'OK', 'message' => $comentaris));
    } else {
        echo json_encode(array('response' => 'ERROR', 'message' => 'No records found'));
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}

function uploadImgPFP($img, $user_id){
    $conn = peticionSQL();

    // Obtener el nombre de la imagen anterior
    $sql_get_old_img = "SELECT pfp FROM dib_usuaris WHERE usuari = ?";
    $stmt_get_old_img = mysqli_prepare($conn, $sql_get_old_img);
    mysqli_stmt_bind_param($stmt_get_old_img, "i", $user_id);
    mysqli_stmt_execute($stmt_get_old_img);
    mysqli_stmt_bind_result($stmt_get_old_img, $old_img);
    mysqli_stmt_fetch($stmt_get_old_img);
    mysqli_stmt_close($stmt_get_old_img);

    // Ruta de destino
    $target_dir = "../media/sistema/usuaris/";
    $imageFileType = strtolower(pathinfo($img["name"], PATHINFO_EXTENSION));
    $new_img_name = pathinfo($img["name"], PATHINFO_FILENAME) . "_$user_id." . $imageFileType;
    $target_file = $target_dir . $new_img_name;

    // Comprobar si el archivo es una imagen real o falsa
    $check = getimagesize($img["tmp_name"]);
    if($check === false) {
        echo json_encode(['response' => 'ERROR', 'message' => 'El archivo no es una imagen.']);
        return;
    }

    // Verificar los tipos de archivo permitidos
    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Solo se permiten archivos JPG, JPEG y PNG.']);
        return;
    }

    // Verificar errores de subida
    if ($img['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al subir el archivo.']);
        return;
    }

    // Subir la nueva imagen
    if (move_uploaded_file($img["tmp_name"], $target_file)) {
        // Si la imagen anterior no es "default.jpg", eliminarla
        if ($old_img !== "default.jpg" && $old_img !== "default.png") {
            $old_img_path = $target_dir . $old_img;
            if (file_exists($old_img_path)) {
                unlink($old_img_path);
            }
        }

        // Actualizar el nombre de la imagen en la base de datos
        $sql_update_img = "UPDATE dib_usuaris SET pfp = ? WHERE usuari = ?";
        $stmt_update_img = mysqli_prepare($conn, $sql_update_img);
        mysqli_stmt_bind_param($stmt_update_img, "si", $new_img_name, $user_id);
        if (mysqli_stmt_execute($stmt_update_img)) {
            echo json_encode(['response' => 'OK']);
        } else {
            echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo actualizar el nombre de la imagen en la base de datos']);
        }
        mysqli_stmt_close($stmt_update_img);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo subir la imagen']);
    }

    mysqli_close($conn);
}

function getPFP($user_id){
    $conn = peticionSQL();
    $sql = "SELECT pfp FROM dib_usuaris WHERE usuari = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'pfp' => $row['pfp']]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontró la imagen de perfil']);
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}

function updateProfile($user_id, $username, $email, $password, $description) {
    $conn = peticionSQL();

    // Verificar si la contraseña debe actualizarse
    if (empty($password) || $password === null) {
        $sql = "UPDATE dib_usuaris SET nickname = ?, email = ?, descripcio = ? WHERE usuari = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $description, $user_id);
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE dib_usuaris SET nickname = ?, email = ?, passwd = ?, descripcio = ? WHERE usuari = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $hashed_password, $description, $user_id);
    }

    # Recargar la sesión
    # (Sinó se buguea porque se queda con el mail anterior, tampoco podemos hacer logout)
    $_SESSION['email'] = $email;

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['response' => 'OK']);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo actualizar el perfil en la base de datos']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function getProfileData($user_id) {
    $conn = peticionSQL();
    $sql = "SELECT nickname, email, descripcio FROM dib_usuaris WHERE usuari = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'data' => $row]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontraron detalles del perfil']);
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}

function uploadImgXat($img, $id_xat) {
    $conn = peticionSQL();

    // Obtener el nombre de la imagen anterior
    $sql_get_old_img = "SELECT img_xat FROM dib_xats WHERE id_xat = ?";
    $stmt_get_old_img = mysqli_prepare($conn, $sql_get_old_img);
    mysqli_stmt_bind_param($stmt_get_old_img, "i", $id_xat);
    mysqli_stmt_execute($stmt_get_old_img);
    mysqli_stmt_bind_result($stmt_get_old_img, $old_img);
    mysqli_stmt_fetch($stmt_get_old_img);
    mysqli_stmt_close($stmt_get_old_img);

    // Ruta de destino
    $target_dir = "../media/sistema/xats/";
    $imageFileType = strtolower(pathinfo($img["name"], PATHINFO_EXTENSION));
    $new_img_name = pathinfo($img["name"], PATHINFO_FILENAME) . "_$id_xat." . $imageFileType;
    $target_file = $target_dir . $new_img_name;

    // Comprobar si el archivo es una imagen real o falsa
    $check = getimagesize($img["tmp_name"]);
    if ($check === false) {
        echo json_encode(['response' => 'ERROR', 'message' => 'El archivo no es una imagen.']);
        return;
    }

    // Verificar los tipos de archivo permitidos
    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Solo se permiten archivos JPG, JPEG y PNG.']);
        return;
    }

    // Verificar errores de subida
    if ($img['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['response' => 'ERROR', 'message' => 'Error al subir el archivo.']);
        return;
    }

    // Subir la nueva imagen
    if (move_uploaded_file($img["tmp_name"], $target_file)) {
        // Si la imagen anterior no es "default.jpg" o "default.png", eliminarla
        if ($old_img !== "default.jpg" && $old_img !== "default.png") {
            $old_img_path = $target_dir . $old_img;
            if (file_exists($old_img_path)) {
                unlink($old_img_path);
            }
        }

        // Actualizar el nombre de la imagen en la base de datos
        $sql_update_img = "UPDATE dib_xats SET img_xat = ? WHERE id_xat = ?";
        $stmt_update_img = mysqli_prepare($conn, $sql_update_img);
        mysqli_stmt_bind_param($stmt_update_img, "si", $new_img_name, $id_xat);
        if (mysqli_stmt_execute($stmt_update_img)) {
            echo json_encode(['response' => 'OK']);
        } else {
            echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo actualizar el nombre de la imagen en la base de datos']);
        }
        mysqli_stmt_close($stmt_update_img);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se pudo subir la imagen']);
    }

    mysqli_close($conn);
}

function getImgXat($id_xat) {
    $conn = peticionSQL();
    $sql = "SELECT img_xat FROM dib_xats WHERE id_xat = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_xat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'img_xat' => $row['img_xat']]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontró la imagen del chat']);
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}

function getXatName($id_xat) {
    $conn = peticionSQL();
    $sql = "SELECT nom_xat FROM dib_xats WHERE id_xat = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_xat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['response' => 'OK', 'nom_xat' => $row['nom_xat']]);
    } else {
        echo json_encode(['response' => 'ERROR', 'message' => 'No se encontró el nombre del chat']);
    }
    mysqli_stmt_close($stmt);
    $conn->close();
}