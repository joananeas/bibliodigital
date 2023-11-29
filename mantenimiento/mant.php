<?php
    # © Joan Aneas
    require_once "db.php";
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $peticion = $_GET["pttn"] ?? null;
    function conn_db($conn){
        $conn == true ? $msg = "Conexión exitosa" : $msg = "Error: No se pudo conectar a MySQL.";
        echo json_encode($msg);
    }

    function auth_users($conn, $user, $pass){
     // Iniciar una nueva sesión o reanudar una existente
        session_start();
        // Guardar datos en la sesión
        $_SESSION['username'] = 'JohnDoe';

        // Obtener datos de la sesión
        if (isset($_SESSION['username'])) {
            echo 'Bienvenido ' . $_SESSION['username'];
        } else {
            echo 'Por favor inicia sesión.';
        }

        // Eliminar datos de la sesión
        unset($_SESSION['username']);

        // Destruir la sesión
        session_destroy();
    }

    switch($peticion){
        case 0:
            conn_db($conn);
            echo json_encode("[pttn::0]");
            break;
        case 1:
            auth_users($conn, $user, $pass);
            echo json_encode("[pttn::1]");
            break;
        default:
            echo json_encode("Error: No se ha encontrado la petición.");
    }
?>