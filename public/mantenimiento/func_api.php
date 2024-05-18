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
            if  (str_contains($row['estadoActual'], "Disponible")) {
                $row['estadoActual'] = "Disponible";
            }
            else {
                $row['estadoActual'] = "Prestat";
            }
            $rows[] = $row;
            
            if ($i > 5) { break; }
        }
        echo json_encode(['response' => 'OK', 'llibres' => $rows]);
    } else {
        echo json_encode(['response' => 'ERROR']);
    }
}

function cercaExemplars($conn, $llibre)
{
    $sql = "SELECT COUNT(dib_exemplars.IDENTIFICADOR) AS num_exemplars
            FROM `dib_exemplars`
            WHERE dib_exemplars.IDENTIFICADOR = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $llibre);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result)['num_exemplars'];
}

function cercaLlibresAll($conn, $libroId)
{
    $conn->set_charset("utf8mb4");
    $sql = "SELECT DISTINCT
                dib_cataleg.*,
                COUNT(dib_exemplars.IDENTIFICADOR) AS num_exemplars
            FROM `dib_cataleg`
            LEFT JOIN `dib_exemplars` ON dib_cataleg.NUMERO = dib_exemplars.IDENTIFICADOR
            WHERE dib_cataleg.`NUMERO` = ?
            GROUP BY dib_cataleg.NUMERO;";

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


function modificarLlibre($id, $exemplars, $OGexemplars, $cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat)
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
        $cataleg, $biblioteca, $isbn, $cdu, $format, $titol, $autor, $editorial,
        $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell,
        $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc,
        $camp_lliure, $npres, $rec, $estat, $id
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
        ...$params
    );

    if (mysqli_stmt_execute($stmt)) {
        if ($exemplars > $OGexemplars) {
            $diff = $exemplars - $OGexemplars;
            for ($i = 0; $i < $diff; $i++) {
                $sqlInsert = "INSERT INTO `dib_exemplars` (`NUMERO_EXEMPLAR`, `SIGNATURA_EXEMPLAR`, `SITUACIO`, `ESTAT`) VALUES (?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $sqlInsert);

                if (!$stmtInsert) {
                    return json_encode(['response' => 'ERROR', 'message' => 'Error al preparar la consulta: ' . mysqli_error($conn)]);
                }

                $numero_exemplar = $id;
                $signatura_exemplar = $cdu . " " . str_split($autor, 3)[0];
                $situacio = 'Préstec';
                $estat = 'Disponible';

                mysqli_stmt_bind_param($stmtInsert, 'isss', $numero_exemplar, $signatura_exemplar, $situacio, $estat);

                if (!mysqli_stmt_execute($stmtInsert)) {
                    $error = mysqli_stmt_error($stmtInsert);
                    return json_encode(['response' => 'ERROR', 'message' => 'Error al insertar la consulta: ' . $error]);
                }
            }
        } elseif ($exemplars < $OGexemplars) {
            $diff = $OGexemplars - $exemplars;
            $sqlDelete = "DELETE FROM `dib_exemplars` WHERE `IDENTIFICADOR` = ? ORDER BY `NUMERO_EXEMPLAR` DESC LIMIT ?; ";
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
                            VALUES (NULL, ?, ?, ?, ?, ?, NULL, 'Pendent', 'Pendent del bibliotecari.')";
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

function autoritzarPrestec($id_prestec){
    $conn = peticionSQL();
    $sql = "UPDATE dib_prestecs SET estat = 'Autoritzat' WHERE id_prestec = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_prestec);
    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo autorizar el préstamo']);
    }
}

function denegarPrestec($id_prestec){
    $conn = peticionSQL();
    $sql = "UPDATE dib_prestecs SET estat = 'Denegat' WHERE id_prestec = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_prestec);
    if (mysqli_stmt_execute($stmt)) {
        return json_encode(['response' => 'OK']);
    } else {
        return json_encode(['response' => 'ERROR', 'message' => 'No se pudo denegar el préstamo']);
    }
}

function retornarPrestec($id_prestec){
    $conn = peticionSQL();
    $sql = "UPDATE dib_prestecs SET estat = 'Tornat' WHERE id_prestec = ?";
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

        $sql_update_exemplar = "UPDATE dib_exemplars SET ESTAT = 'Disponible' WHERE IDENTIFICADOR = ?";
        $stmt_update_exemplar = mysqli_prepare($conn, $sql_update_exemplar);
        mysqli_stmt_bind_param($stmt_update_exemplar, "i", $exemplar_id);
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
    $sql = "SELECT * FROM dib_missatges WHERE xat_id = ? ORDER BY `data_enviament` ASC";
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