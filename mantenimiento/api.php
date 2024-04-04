<?php
    # © Joan Aneas
    
   /*___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (ASCII art)*/

    ##################
    #--- Includes ---#
    ###########################################################################
    /**/require_once "clases_api.php";                                        #
    /**/require_once "mant.php"; # Importa las constantes de mantenimiento.   #
    /**/require_once "func_api.php";                                          #
    ###########################################################################

    # Versión del core.
    const VERSION =  'v1.3.5'; # hotfix - rutas absolutas.
    # Conexión a la base de datos, constantes de db.php.

    // Instancias de las APIs
    $apiGlobales = new API_Globales(VERSION, NOM_BIBLIOTECA, TITOL_WEB, FAVICON, H1_WEB, $GLOBALS['paths']['root']);
    $apiCarroussel = new API_Carroussel("../media/sistema/carroussel/");
    $apiUsuarios = new API_Usuarios(null, null, null);


    $peticion = $_POST["pttn"] ?? null;

    switch($peticion){
        case 'checkDB':
            if (($conn = peticionSQL())->connect_error) {
                echo json_encode([
                    "api" => null,
                    "response" => "error",
                    "message" => "Fallo en la conexion: " . $conn->connect_error
                ]);
            }
            else {
                echo json_encode([
                    "api" => null,
                    "response" => "ok",
                    "message" => "Conexión a la base de datos exitosa"
                ]);
            }
            break;

        case 'getRol':
            echo $apiUsuarios->getRol();
            break;

        case 'getGlobals':
            echo $apiGlobales->obtenerDatos();
            break;

        case 'cercaLlibresLite': # Solo busca por nombre y estado, para el buscador.
            $conn = peticionSQL();
            $llibre = $_POST["llibre"];
            cercaLlibresLite($conn, $llibre);
            break;
        
        case 'cercaLlibresFull': # Busca por todos los campos, para libro.php.
            $conn = peticionSQL();
            $llibre = $_POST["llibre"];
            cercaLlibresFull($conn, $llibre);
            break;

        case 'reservarLibro':
            $conn = peticionSQL();
            $titulo = $_POST["titulo"];
            $fechaInicio = $_POST["fechaInicio"];
            $fechaFin = $_POST["fechaFin"];
            reservarLibro($conn, $titulo, $fechaInicio, $fechaFin);
            break;

        case 'genPasswd':
            $password = '1234';
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $isValid = password_verify($password, $hashedPassword);
            if ($isValid) {
                echo json_encode('La contraseña es válida! (hashed: '.$hashedPassword.', passwd: '.$password.')');
            } else {
                echo json_encode('La contraseña no es válida! (hashed: '.$hashedPassword.', passwd: '.$password.')');
            }
            break;

        case 'authUsuario':
            $email = $_POST["email"];
            $password = $_POST["password"];
            $resp = $apiUsuarios->autenticarUsuario($email, $password);
            echo $resp; # No hace falta json_encode, ya que el método lo hace.
            break;

        case 'headerAuthUsuario':
            $resp = $apiUsuarios->headerAuthUsuario();
            echo $resp; # No hace falta json_encode, ya que el método lo hace.
            break; 
        
        case 'logout':
            session_start();
            session_destroy();
            echo json_encode([
                "api" => null,
                "response" => "ok",
                "message" => "Usuario desautenticado"
            ]);
            break;
        
        case 'getFotos':
            $resp = $apiCarroussel->obtenerFotos();
            echo $resp;
            break;
        default:
            echo json_encode("[ERROR (API)] No se ha encontrado la petición.");
            break;
    }
