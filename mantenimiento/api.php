<?php
    # © Joan Aneas
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
    $apiGlobales = new API_Globales("v1.0.7 (alpha)", "vedruna vall", "Biblio Digital", "./ruta", "Biblio Digital");
    
    // Obtener y mostrar los datos en formato JSON
    echo $apiGlobales->obtenerDatos();

    class API_Libro{
        public $nombreLibro;
        public $descLibro;
        public $valoracionLibro;
        public $fechOcupado = array(); # Debe de ser un array.
        public $categoriasLibro = array(); # Debe de ser un array.
        public $topicosLibro = array(); # Debe de ser un array.

        // Constructor para asignar los datos.
        // Uso la nomenclatura de kernighan para las var. del constructor.
        public function __construct($_nombreLibro, $_descLibro, $_valoracionLibro) {
            $this->nombreLibro = $_nombreLibro;
            $this->descLibro = $_descLibro;
            $this->valoracionLibro = $_valoracionLibro;
        }

        public function asignarArrays($_fechOcupado, $_categoriasLibro, $_topicosLibro){
            $this->fechOcupado = $_fechOcupado;
            $this->categoriasLibro = $_categoriasLibro;
            $this->topicosLibro = $_categoriasLibro;
        }


    }
?>