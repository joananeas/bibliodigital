<?php
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