<?php
    # © Joan Aneas
    #################################
    # Variables globales ############
        $version = "v1.0.6 (alpha)";
        $nomBiblioteca = "vedruna vall";
        $titolWeb = "Biblio Digital";
        $favicon = "./ruta";
        $h1Web = "Biblio Digital";
        $numError = "E-0003"; # Control de errores
        $textError = "Error de connexió amb la base de dades.";
        $fueraDeRango = false;
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