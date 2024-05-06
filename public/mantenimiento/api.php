<?php
    # © Joan Aneas
    
   /*___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (ASCII art)*/

    ##################
    #--- Includes ---#
    ###########################################################################
    /**/require_once(__DIR__ ."/clases_api.php");                              #
    /**/require_once(__DIR__ ."/func_api.php");                                #
    ###########################################################################

    # Versión del core.
    const VERSION =  'v1.5.8'; # - 🐛 minor fixes

    // Instancias de las APIs
    $root = realpath(dirname(__FILE__));
    $rootPath = str_replace('mantenimiento', "", $root); # Hay que bajar un directorio (estamos en mantenimiento/)
    $mediaPath = $rootPath . '/media/';
    $adminPath = $rootPath . '/admin/';
    $stylesPath = $rootPath . '/styles/';
    $jsPath = $rootPath . '/mantenimineto/scripts/';
    $apiPath = $rootPath . '/mantenimineto/';
    $dynaPath = $rootPath . '/dynamo/';
    
    $GLOBALS['paths'] = [
        'root' => $rootPath,
        'media' => $mediaPath,
        'admin' => $adminPath,
        'styles' => $stylesPath,
        'js' => $jsPath,
        'api' => $apiPath,
        'dyna' => $dynaPath
    ];

    $apiGlobales = new API_Globales(VERSION, $GLOBALS['paths']['root']);
    $apiBanner = new API_Banner();
    $apiCarroussel = new API_Carroussel("../media/sistema/carroussel/");
    $apiUsuarios = new API_Usuarios(null, null, null);


    $peticion = $_POST["pttn"] ?? null;
    $peticion = $peticion ?? $_GET["pttn"] ?? null;

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
        
        case 'server-ip':
            echo json_encode([
                "api" => null,
                "response" => "ok",
                "ip" => $_SERVER['REQUEST_URI']
            ]);
            break;

        case 'getID':
            echo $apiUsuarios->getID();
            break;

        case 'getRol':
            echo $apiUsuarios->getRol();
            break;

        case 'getAllUsers':
            echo $apiUsuarios->getAllUsers();
            break;
        
        case 'createUser':
            $email = $_POST["email"];
            $password = $_POST["password"];
            $rol = $_POST["rol"];
            echo $apiUsuarios->createUser($email, $password, $rol);
            break;

        case 'getGlobals':
            echo $apiGlobales->obtenerDatos();
            break;

        case 'getColores':
            echo $apiGlobales->getColores();
            break;

        case 'setColores':
            $colorPrincipal = $_POST["colorPrincipal"];
            $colorSecundario = $_POST["colorSecundario"];
            $colorTerciario = $_POST["colorTerciario"];
            echo $apiGlobales->setColores($colorPrincipal, $colorSecundario, $colorTerciario);
            break;

        case 'getBanner':
            echo $apiBanner->getBanner();
            break;
        
        case 'setBanner':
            $bannerState = $_POST["bannerState"];
            $bannerText = $_POST["bannerText"];
            echo $apiBanner->setBanner($bannerState, $bannerText);
            break;
            
        case 'cercaLlibresLite': # Solo busca por nombre y estado, para el buscador.
            $conn = peticionSQL();
            $llibre = $_POST["llibre"];
            cercaLlibresLite($conn, $llibre);
            break;
        
        case 'cercaLlibresFull': # Busca por todos los campos (para usuario), para libro.php.
            $conn = peticionSQL();
            $llibre = $_POST["llibre"];
            cercaLlibresFull($conn, $llibre);
            break;
        
        case 'cercaLlibresAll': # Busca por todos los campos (para bibliotecario), para libro.php.
            $conn = peticionSQL();
            $llibre = $_POST["llibre"];
            cercaLlibresAll($conn, $llibre);
            break;

        case 'reservarLibro':
            $conn = peticionSQL();
            $titulo = $_POST["titulo"];
            $fechaInicio = $_POST["fechaInicio"];
            $fechaFin = $_POST["fechaFin"];
            reservarLibro($conn, $titulo, $fechaInicio, $fechaFin);
            break;

        case 'modificarLlibre':
            $conn = peticionSQL();
            $conn -> set_charset("utf8mb4"); 
            $id = $_POST["id"];

            $exemplars = $_POST["exemplars"];

            $cataleg = $_POST["cataleg"];
            $biblioteca = $_POST["biblioteca"];
            $titol = $_POST["titol"];
            $isbn = $_POST["isbn"];
            $cdu = $_POST["cdu"];
            $format = $_POST["format"];
            $autor = $_POST["autor"];
            $editorial = $_POST["editorial"];
            $lloc = $_POST["lloc"];
            $colleccio = $_POST["colleccio"];
            $pais = $_POST["pais"];
            $data = $_POST["data"];
            $llengua = $_POST["llengua"];
            $materia = $_POST["materia"];
            $descriptor = $_POST["descriptor"];
            $nivell = $_POST["nivell"];
            $resum = $_POST["resum"];
            $url = $_POST["url"];
            $adreca = $_POST["adreca"];
            $dimensio = $_POST["dimensio"];
            $volum = $_POST["volum"];
            $pagines = $_POST["pagines"];
            $proc = $_POST["proc"];
            $carc = $_POST["carc"];
            $camp_lliure = $_POST["camp_lliure"];
            $npres = $_POST["npres"];
            $rec = $_POST["rec"];
            $estat = $_POST["estat"];

            echo modificarLlibre($id, $cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat);
            break;

        case 'getLastLlibre':
            $conn = peticionSQL();
            echo getLastLlibre();
            break;

        case 'crearLlibre':
            $cataleg = $_POST["cataleg"];
            $biblioteca = $_POST["biblioteca"];
            $id = $_POST["identificador"];
            $titol = $_POST["titol"];
            $isbn = $_POST["isbn"];
            $cdu = $_POST["cdu"];
            $format = $_POST["format"];
            $autor = $_POST["autor"];
            $editorial = $_POST["editorial"];
            $lloc = $_POST["lloc"];
            $colleccio = $_POST["colleccio"];
            $pais = $_POST["pais"];
            $data = $_POST["data"];
            $llengua = $_POST["llengua"];
            $materia = $_POST["materia"];
            $descriptor = $_POST["descriptor"];
            $nivell = $_POST["nivell"];
            $resum = $_POST["resum"];
            $url = $_POST["url"];
            $adreca = $_POST["adreca"];
            $dimensio = $_POST["dimensio"];
            $volum = $_POST["volum"];
            $pagines = $_POST["pagines"];
            $proc = $_POST["proc"];
            $carc = $_POST["carc"];
            $camp_lliure = $_POST["camp_lliure"];
            $npres = $_POST["npres"];
            $rec = $_POST["rec"];
            $estat = $_POST["estat"];
        
            echo crearLlibre($cataleg, $biblioteca, $id, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat);
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
                $_COOKIE['rol'] = null;
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

        case 'getReserves':
            $conn = peticionSQL();
            $id = $_POST["id"];
            getReserves($conn, $id);
            #echo $resp;
            break;
        
        case 'getReservesFromUser':
            $usuari = $apiUsuarios->getID();
            $resp = $apiUsuarios->getReservas($usuari);
            echo $resp;
            break;
        
        case 'reservar':
            $conn = peticionSQL();
            $id = $_POST['id'];
            $fechaInicio = $_POST['fecha'];
            $usuari = $apiUsuarios->getID();

            $resp = reservar($conn, $id, $usuari, $fechaInicio);
            echo $resp;
            break;

        case 'getNotifications':
            $user_id = $apiUsuarios->getID();
            
            $resp = $apiUsuarios->getNotifications($user_id);
            echo $resp;
            break;

        default:
            echo json_encode("[ERROR (API)] No se ha encontrado la petición.");
            header('Location: ../error.php?error=404');
            break;
    }
