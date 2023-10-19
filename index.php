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
                <form style="float: right;"> <!--Cerca de llibres-->
                <label>Cerca</label>
                <input class="enFocus" type="text">
                <input class="boto-cerca" type="image" src="./media/sistema/buscar.png" alt="Enviar" width="20" height="20">
            </form>
            </div><br>
            <article class="libros"> <!--dinamico-->
                <h3 class="atrib">Titulo</h3>
                <img class="atrib" src="./media/sistema/pruebas.jpg" width="100" height="100" alt="libro">
                <br>
                <span class="atrib">estrellas</span>
                <p class="atrib">descripion breve</p>
            </article>
        </section>
    </main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html>