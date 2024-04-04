<?php
# © Joan Aneas
    
   /*___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (ASCII art)*/


#TODO: Añadir autoloader
#TODO: Crear clase para las sesiones
class API_Globales {
    private $version;
    private $nomBiblioteca;
    private $titolWeb;
    private $favicon;
    private $h1Web;
    private $rootPath;

    public function __construct($version, $nomBiblioteca, $titolWeb, $favicon, $h1Web, $rootPath) {
        $this->version = $version;
        $this->nomBiblioteca = $nomBiblioteca;
        $this->titolWeb = $titolWeb;
        $this->favicon = $favicon;
        $this->h1Web = $h1Web;
        $this->rootPath = $rootPath;
    }

    public function obtenerDatos() {
        $datos = array(
            "version" => $this->version,
            "nomBiblioteca" => $this->nomBiblioteca,
            "titolWeb" => $this->titolWeb,
            "favicon" => $this->favicon,
            "h1Web" => $this->h1Web,
            "rootPath" => $this->rootPath
        );
        return json_encode($datos);
    }
}

class API_Carroussel {
    private $foto;
    private $ancho;
    private $alto;
    private $url;

    public function __construct($url_fotos) {
        $this->url = $url_fotos;
    }

    public function obtenerDatos() {
        $datos = array(
            "foto" => $this->foto,
            "ancho" => $this->ancho,
            "alto" => $this->alto
        );
        return json_encode($datos);
    }

    public function obtenerFotos(){
        $i = 0;
        $flag = true;
        if(!dir($this->url)) return json_encode(["api" => "url doesn't exist."]);
        while($flag){
            $i++;
            if(!file_exists($this->url . 'prueba-' . $i . '.jpg')){
                $flag = false;
                $i--; # Compensa la vuelta extra
            }
            #echo "Fotos: ". $i;
        }
        return json_encode(["num_libros" => $i]);
    }
}

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

        #FIXME: Revisar si cookie es la mejor practica
        if (isset($row['rol']) && $row['rol'] != "") $cookie = $row['rol'];
        else $cookie = 'lector'; # Lo seteo a lector por seguridad, no puede interactuar con la página (anónimo??)

        setcookie('rol', $cookie, time() + (86400 * 30), "/");

        return json_encode([
            "api" => $row,
            "response" => "ok"
        ]);
    }

    public function getRol(){
        session_start();
        // if (!isset($_COOKIE['rol'])) {
        //     return json_encode([
        //         "api" => null,
        //         "response" => "error",
        //         "message" => "Usuario no autenticado"
        //     ]);
        // }
        return json_encode([
            "rol" => $_COOKIE['rol'],
            "username" => $_SESSION['email'],
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