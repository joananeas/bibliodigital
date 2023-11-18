<?php
    # © Joan Aneas
    #################################
    # Variables globales ############
        $version = "v1.0.7 (alpha)";
        $nomBiblioteca = "vedruna vall";
        $titolWeb = "Biblio Digital";
        $favicon = "./ruta";
        $h1Web = "Biblio Digital";
        $numError = "E-0003";
        $textError = "Error de connexió amb la base de dades.";
        $fueraDeRango = false;
        
    # Los introduzco en un array para poder hacer el json_encode

        $datos = array(
            "version" => $version,
            "nomBiblioteca" => $nomBiblioteca,
            "titolWeb" => $titolWeb,
            "favicon" => $favicon,
            "h1Web" => $h1Web,
            "numError" => $numError,
            "textError" => $textError,
            "fueraDeRango" => $fueraDeRango
        );
    
    echo json_encode($datos); # Mandamos los datos.
    #################################
?>