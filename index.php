<!--© Joan Aneas-->
<?php 
    include 'mantenimiento/mant.php'; 
    # Variables de dinámicos.
    $estilos = ["componentes.css", "paginas/index.css"];
    $espPagina = ["<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1 '>"];
?>

<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <main>
        <section class="frame">
            <div class="header-libros">
                <span>Llistat llibres <span class="fuenteH1" style="font-size:18px; text-decoration:underline;"><?php echo $nomBiblioteca; ?></span></span>
            <form style="float: right;" method="post" action=""> <!--Cerca de llibres-->
                <label>Cerca</label>
                <input name="inputCerca" class="enFocus" type="text" value="<?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {                
                        // Obtener el valor del campo de entrada de texto
                        $inputCerca = $_POST["inputCerca"];
                        #$inputCerca = $conexion->real_escape_string($inputCerca);
                        $sql = "SELECT * FROM llibres
                        WHERE nomLlibre LIKE '%".$inputCerca."%';";

                        // Ejecutar la consulta
                        $result = $conexion->query($sql);
                        
                        // Verificar si hay resultados
                        if ($result->num_rows > 0) {
                            // Recorrer los resultados
                            while ($row = $result->fetch_assoc()) {
                                echo $row["nomLlibre"];
                            }
                        } else {
                            echo "No hi han llibres disponibles.";
                        }
                    }
                ?>">
                
                <button class="boto-cerca" type="submit"><img src="./media/sistema/buscar.png" alt="Enviar" width="20" height="20"></button>                
            </form>
            </div><br>
            <!--dinamico-->
            <?php
                $sql = "SELECT llibres.idLlibre, llibres.nomLlibre, llibres.descripcio  FROM llibres";

                // Ejecutar la consulta
                $result = $conexion->query($sql);
                
                // Verificar si hay resultados
                if ($result->num_rows > 0) {
                    // Recorrer los resultados
                    while ($row = $result->fetch_assoc()) {
                        // Uso el print para las etiquetas html y echo para el php (meramente human-friendly)
                        $idLlibre = $row["idLlibre"];
                        $descripcioCurta = strlen($row['descripcio']) > 70 ? (substr($row['descripcio'], 0, 70) . "...") : $row['descripcio'];
                        print("<article class='libros'>");
                        # Título
                        print("<h3 class='atrib'>");
                        echo  $row["nomLlibre"];
                        print("</h3>");
                        # Imagen (src dinámico)
                        print("<img class='atrib' src='./media/sistema/pruebas.jpg' width='100' height='100' alt='libro'>");
                        # Salto de línea (br)
                        print("<br>");
                        #Valoración
                        print("<span class='atrib'>");
                        echo "estrellas";
                        print("</span>");
                        # Descripción
                        print("<p class='atrib descrip'>");
                        echo $descripcioCurta;
                        print("</p>");
                        # Saber más...
                        print("<button class='botonUniversal botonLibros'><a href='libro.php?id=".$idLlibre."'>Llegir més</a></button>");
                        print("</article>");
                        # Fin Libro
                    }
                } else {
                    echo "No hi han llibres disponibles.";
                }
                
                ######## ¡¡¡¡¡¡¡DEBUGGING!!!!!!!#######
                //        (quitar en produccion)
                $sql = "SELECT llibres.nomLlibre, llibres.idLlibre FROM llibres WHERE idLlibre = 1";
                $resultado = $conexion->query($sql);

                if ($resultado && $fila = $resultado->fetch_assoc()) {
                    echo "<p>DEBUG: " . $fila['nomLlibre'] . " id: ". $fila['idLlibre'];
                } else {
                    echo "<p>No se encontraron registros para el ID 1.</p>";
                }
                ######## ¡¡¡¡¡¡¡DEBUGGING!!!!!!!#######
            ?>

                <!-- <h3 class="atrib">Titulo</h3>
                <img class="atrib" src="./media/sistema/pruebas.jpg" width="100" height="100" alt="libro">
                <br>
                <span class="atrib">estrellas</span>
                <p class="atrib">descripion breve</p> -->
            
        </section>
    </main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html>