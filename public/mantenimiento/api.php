<?php
# © Joan Aneas

/*___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (ASCII art)*/

##################
#--- Includes ---#
###########################################################################
/**/ require_once(__DIR__ . "/clases_api.php");                           #
/**/ require_once(__DIR__ . "/func_api.php");                             #
###########################################################################

# Versión del core.
const VERSION =  'v1.7.5'; # - ✨ Categories Search & more.

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
$apiStats = new API_Stats();

header('Content-Type: application/json');

$peticion = $_POST["pttn"] ?? null;
$peticion = $peticion ?? $_GET["pttn"] ?? null;

switch ($peticion) {
    case 'checkDB':
        if (($conn = peticionSQL())->connect_error) {
            echo json_encode([
                "api" => null,
                "response" => "error",
                "message" => "Fallo en la conexion: " . $conn->connect_error
            ]);
        } else {
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
        echo json_encode($apiUsuarios->getID());
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

    case 'getStars':
        $conn = peticionSQL();
        $llibre = $_POST["llibre"];
        getStars($conn, $llibre);
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
        $conn->set_charset("utf8mb4");
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

        $OGexemplars = cercaExemplars($conn, $id);

        echo modificarLlibre($id, $exemplars, $OGexemplars, $cataleg, $biblioteca, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat);
        break;

    case 'getLastLlibre':
        $conn = peticionSQL();
        echo getLastLlibre();
        break;

    case 'crearLlibre':
        $cataleg = $_POST["cataleg"];
        $biblioteca = $_POST["biblioteca"];
        $id = $_POST["identificador"];
        $exemplars = $_POST["exemplars"];
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

        echo crearLlibre($cataleg, $biblioteca, $id, $exemplars, $titol, $isbn, $cdu, $format, $autor, $editorial, $lloc, $colleccio, $pais, $data, $llengua, $materia, $descriptor, $nivell, $resum, $url, $adreca, $dimensio, $volum, $pagines, $proc, $carc, $camp_lliure, $npres, $rec, $estat);
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
        $resp = $apiUsuarios->getReservesFromUser($usuari);
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

    case 'clearNotifications':
        $user_id = $apiUsuarios->getID();
        $resp = $apiUsuarios->clearNotifications($user_id);
        echo $resp;
        break;

    case 'getUserStats':
        echo $apiStats->getUserStats();
        break;

    case 'getBookStats':
        echo $apiStats->getBookStats();
        break;

    case 'prestarExemplar':
        $conn = peticionSQL();
        $id = $_POST['id_reserva'];

        $resp = prestarExemplar($id);
        echo $resp;
        break;
        
    case 'viewAllPrestecs':
        $resp = viewAllPrestecs();
        echo $resp;
        break;
    
    case 'viewAllReserves':
        $resp = viewAllReserves();
        echo $resp;
        break;
        
    case 'getUserCreationDate':
        echo $apiUsuarios->getUserCreationDate();
        break;
        
    case 'getFavicon':
        echo $apiGlobales->getFavicon();
        break;

    case 'getCDU':
        $id_llibre = $_GET['id'];
        $resp = getCDU($id_llibre);
        echo $resp;
        break;
    
    case 'getAutor':
        $id_llibre = $_GET['id'];
        $resp = getAutor($id_llibre);
        echo $resp;
        break;
        
    case 'autoritzarPrestec':
        $id_prestec = $_POST['id_prestec'];
        $resp = autoritzarPrestec($id_prestec);
        echo $resp;
        break;    
    
    case 'denegarPrestec':
        $id_prestec = $_POST['id_prestec'];
        $resp = denegarPrestec($id_prestec);
        echo $resp;
        break;
    
    case 'marcarRetornat':
        $id_prestec = $_POST['id_prestec'];
        $resp = retornarPrestec($id_prestec);
        echo $resp;
        break;
    
    case 'eliminarPrestec':
        $id_prestec = $_POST['id_prestec'];
        $resp = eliminarPrestec($id_prestec);
        echo $resp;
        break;

    case 'crearChat':
        $nom_xat = $_POST['nom_xat'];
        $resp = crearChat($nom_xat);
        echo $resp;
        break;
    
    case 'getChats':
        $resp = getChats();
        echo $resp;
        break;

    case 'getMessages':
        $id_xat = $_POST['id_xat'];
        $resp = getMessages($id_xat);
        echo $resp;
        break;
    
    case 'sendMessage':
        if (isset($_POST['id_xat']) && isset($_POST['missatge'])) {
            $id_xat = $_POST['id_xat'];
            $missatge = $_POST['missatge'];
            $id_usuari = $apiUsuarios->getID();
            
            $resp = sendMessage($id_xat, $id_usuari, $missatge);
            echo $resp;
        } else {
            echo json_encode(['response' => 'ERROR', 'message' => 'Faltan parámetros necesarios']);
        }
        break;
        
    
    case 'getCategories':
        $resp = getCategories();
        echo $resp;
        break;
        
    default:
        echo json_encode("[ERROR (API)] No se ha encontrado la petición.");
        header('Location: ../error.php?error=404');
        break;
}