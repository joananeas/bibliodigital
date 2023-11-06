<!--© Joan Aneas-->
<?php 
    include 'mantenimiento/mant.php'; 
    # Variables de dinámicos.
    $estilos = ["componentes.css", "paginas/index.css", "paginas/libro.css"];
    $espPagina = ["<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1 '>"];
?>

<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <?php
        if (isset($_GET['id'])) {
            $idLlibre = $_GET['id'];
        }

        $sql = "SELECT llibres.nomLlibre, llibres.descripcio FROM llibres WHERE idLlibre =".$idLlibre;
                $resultado = $conexion->query($sql);

        if ($resultado && $fila = $resultado->fetch_assoc()) {
           $nomLlibre = $fila["nomLlibre"];
           $descripcioLlarga = $fila["descripcio"];
        } else {
            echo "<p>No s'ha trobat informació per aquest llibre :(</p>";
        }
    ?>
    <main>
        <section class="frame">
            <div class="header-libros">
            <span>Vista del llibre: <span class="fuenteH1" style="font-size:18px; text-decoration:underline;"><?php echo $nomLlibre; ?></span></span>
            </div>   
            <div style="width: 100%;">
            <aside>
                <img src="<?php echo $imgSrc = "./media/sistema/pruebas.jpg"; ?>" width="400px" height="400">
            </aside>
            <article>
                <h3 class="fuenteH1"><?php echo $nomLlibre; ?></h3>
                <span>Estrellas</span>
                <p><?php echo $descripcioLlarga; ?></p>
            </article>
            </div>
        </section>
    </main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html>