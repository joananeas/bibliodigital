<?php
    # © Joan Aneas
    header('Content-Type: application/json');
    require_once "func_install.php";
    
    if(isset($_POST['peticion'])) {
        $peticion = $_POST['peticion'];
    } else if(isset($_GET['peticion'])) {
        $peticion = $_GET['peticion'];
    }

    switch ($peticion){
        case 'comprobarConn':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";

            if ($db_server == null || $db_user == null || $db_name == null) {
                echo json_encode(["status" => "error", "message" => "Faltan datos."]);
            }

            $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
            if ($conn->connect_error) {
                echo json_encode(["status" => "error", "message" => "conex-bad" . $conn->connect_error]);
            } else {
                echo json_encode(["status" => "ok", "message" => "conex-ok"]);
            }

            break;

        case 'comprobarArchivos':
            # Comprueba si existen los archivos necesarios para la instalación.
            if (file_exists("../mantenimiento/mant.php") && file_exists("../mantenimiento/db.php")) {
                echo json_encode(["status" => "ok", "message" => "existen-2"]);
            } else if (file_exists("../mantenimiento/mant.php")) {
                echo json_encode(["status" => "ok", "message" => "existe-mant"]);
            } else if (file_exists("../mantenimiento/db.php")) {
                echo json_encode(["status" => "ok", "message" => "existe-db"]);
            } else {
                echo json_encode(["status" => "error", "message" => "no-existen"]);
            }
            break;

        case 'instalacion-db':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";

            $msg = installation($db_server, $db_user, $db_name, $db_pass);
            echo $msg;
            break;

        case 'instalacion-tablas':
            echo crearTablasDB();
            break;
        
        case 'creacion-admin':
            $admin = $_POST['admin'] ?? null;
            $adminPass = $_POST['adminPass'] ?? null;

            echo crearAdmin($admin, $adminPass);
            break;
        
        case 'config':
            $nomBiblioteca = $_POST['nomBiblioteca'] ?? null;
            $titolWeb = $_POST['titolWeb'] ?? null;
            $h1Web = $_POST['h1Web'] ?? null;
            $colorPrincipal = $_POST['colorPrincipal'] ?? null;
            $colorSecundario = $_POST['colorSecundario'] ?? null;
            $colorTerciario = $_POST['colorTerciario'] ?? null;
        
            // Llamar a la función config con todos los parámetros
            echo config($nomBiblioteca, $titolWeb, $h1Web, $colorPrincipal, $colorSecundario, $colorTerciario);
            break;
        
        case 'subir-xls':
            echo subirXls($_FILES['uploads']);
            break;

        case 'xls-to-csv':
            echo xlsToCsv();
            break;
        
        case 'csv-to-db':
            echo csvToDB();
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Peticion no reconocida: $peticion"]);
            break;
  
    }  
exit;
