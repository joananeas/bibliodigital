<?php
    # © Joan Aneas
    define("VERSION", "v1.1.0");
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
        // private $id;
        // private $nombre;
        // private $apellido;
        private $email;
        private $password;
        // private $rol;
        // private $estado;
        // private $fechaRegistro;
        // private $fechaActualizacion;

        public function __construct($email, $password){
            // $this->id = $id;
            // $this->nombre = $nombre;
            // $this->apellido = $apellido;
            $this->email = $email;
            $this->password = $password;
            // $this->rol = $rol;
            // $this->estado = $estado;
            // $this->fechaRegistro = $fechaRegistro;
            // $this->fechaActualizacion = $fechaActualizacion;
        }

        public function obtenerDatos(){
            $datos = array(
                // "id" => $this->id,
                // "nombre" => $this->nombre,
                // "apellido" => $this->apellido,
                "email" => $this->email,
                "password" => $this->password,
                // "rol" => $this->rol,
                // "estado" => $this->estado,
                // "fechaRegistro" => $this->fechaRegistro,
                // "fechaActualizacion" => $this->fechaActualizacion
            );
            return json_encode($datos);
        }
        public function autenticarUsuario($email, $password){
            //$password = md5($password);
            $conn = mysqli_connect("localhost", "root", "", "bibliodigital");
            $sql = "SELECT * FROM usuaris WHERE email = '$email' AND passwd = '$password'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            if($row){
                return json_encode($row);
            }else{
                return json_encode("Error: No se ha encontrado el usuario.");
            }  

        }
    }

    $apiUsuarios = new API_Usuarios(null, null);
    $peticion = $_POST["pttn"] ?? null;

    switch($peticion){
        case 'getGlobals':
            echo $apiGlobales->obtenerDatos();
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
            echo $resp;
            break;
        default:
            echo json_encode("[ERROR (API)] No se ha encontrado la petición.");
            break;
    }
?>