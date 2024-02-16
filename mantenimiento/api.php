<?php
    # © Joan Aneas
    define("VERSION", "v1.2.4"); # Creación archivo db.php (instalación) aun no implementada.

    function peticionSQL(){
        require_once "db.php";
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $conn;
    }

    class API_Globales {
        private $version;
        private $nomBiblioteca;
        private $titolWeb;
        private $favicon;
        private $h1Web;
    
        public function __construct($version, $nomBiblioteca, $titolWeb, $favicon, $h1Web) {
            $this->version = $version;
            $this->nomBiblioteca = $nomBiblioteca;
            $this->titolWeb = $titolWeb;
            $this->favicon = $favicon;
            $this->h1Web = $h1Web;
        }
    
        public function obtenerDatos() {
            $datos = array(
                "version" => $this->version,
                "nomBiblioteca" => $this->nomBiblioteca,
                "titolWeb" => $this->titolWeb,
                "favicon" => $this->favicon,
                "h1Web" => $this->h1Web
            );
            return json_encode($datos);
        }
    }
    
    // Crear una instancia de la clase API_Globales
    $apiGlobales = new API_Globales(VERSION, "vedruna vall", "Biblio Digital", "./ruta", "Biblio Digital");
    
    // Obtener y mostrar los datos en formato JSON

    class API_Usuarios{
        private $email;
        private $password;
        private $rol; # Admin, Bibliotecario, Moderador y Usuario

        public function __construct($email, $password, $rol){
            $this->email = $email;
            $this->password = $password;
            $this->rol = $rol;
        }

        public function obtenerDatos(){
            $datos = array(
                "email" => $this->email,
                "password" => $this->password,
                "rol" => $this->rol,
            );
            return json_encode($datos);
        }
        public function autenticarUsuario($email, $password){
            //$password = md5($password);
            $conn = peticionSQL();
            $sql = "SELECT * FROM usuaris WHERE email = '$email' AND passwd = '$password'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            if(!$row){
                return json_encode([
                    "api" => null,
                    "response" => "error"
                ]);
            }  

            session_start();
            $_SESSION['email'] = $row['email'];
            $_SESSION['rol'] = $row['rol'];

            return json_encode([
                "api" => $row,
                "response" => "ok"
            ]);
        }

        public function getRol(){
            session_start();
            if (!isset($_SESSION['rol'])) {
                return json_encode([
                    "api" => null,
                    "response" => "error",
                    "message" => "Usuario no autenticado"
                ]);
            }

            return json_encode([
                "api" => $_SESSION['rol'],
                "response" => "ok",
                "message" => "Usuario autenticado"
            ]);
        }

        public function headerAuthUsuario(){
            session_start();
            if (!isset($_SESSION['email'])) {
                return json_encode([
                    "api" => null,
                    "response" => "error",
                    "message" => "Usuario no autenticado"
                ]);
            }
            return json_encode([
                "api" => $_SESSION['email'],
                "response" => "ok",
                "message" => "Usuario autenticado"
            ]);
        }
    }

    $apiUsuarios = new API_Usuarios(null, null, null);
    $peticion = $_POST["pttn"] ?? null;

    switch($peticion){
        case 'getRol':
            echo $apiUsuarios->getRol();
            break;

        case 'getGlobals':
            echo $apiGlobales->obtenerDatos();
            break;

        case 'cercaLlibresLite': # Solo busca por nombre y estado, para el buscador.
            $conn = peticionSQL();
            $llibre = $_POST["llibre"];
            $sql = "SELECT nom, estadoActual FROM llibres WHERE nom LIKE '%$llibre%'";
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
            break;
        
        case 'cercaLlibresFull': # Busca por todos los campos, para libro.php.
            $conn = peticionSQL();
            $llibre = $_POST["llibre"];
            $sql = "SELECT * FROM llibres WHERE nom = '$llibre'";
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
            break;

        case 'reservarLibro':
            $conn = peticionSQL();
            $titulo = $_POST["titulo"];
            $fechaInicio = $_POST["fechaInicio"];
            $fechaFin = $_POST["fechaFin"];
        
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
            
        default:
            echo json_encode("[ERROR (API)] No se ha encontrado la petición.");
            break;
    }
