<?php
    # © Joan Aneas
    #################################
    # Variables globales ############
        $version = "v1.0.5 (alpha)";
        $nomBiblioteca = "vedruna vall";
        $titolWeb = "Biblio Digital";
        $favicon = "./ruta";
        $h1Web = "Biblio Digital";
        $numError; # Control de errores
        $textError;
        $fueraDeRango = false;

        // Ruta al archivo README.md
        $readmePath = './../README.md';

        // Contenido del README.md
        $readmeContent = file_get_contents($readmePath);

        // Reemplaza el marcador en el contenido del README.md con el valor de $version
        $readmeContent = str_replace('<!-- VERSION -->', $version, $readmeContent);

        // Guarda el contenido modificado en el archivo README.md
        file_put_contents($readmePath, $readmeContent);
    #################################

    require_once "db.php"; # Datos para la conexión a la BBDD
    try {
        $conexion = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
        if (!$conexion) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    
        # Resto de tu código
    } catch (Exception $e) {
        // Manejo de la excepción y mensaje de error personalizado
        echo "<p class='error'>Error: " . $e->getMessage(). "</p>";
    }
    #else echo "hola";
?>