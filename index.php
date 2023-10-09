<!--© Joan Aneas-->
<?php include 'mantenimiento/mant.php'; ?>

<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body>

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <main>
        <div class="centrado">
            <span class="izquierda">Llistat llibres <?php echo $nomBiblioteca; ?></span><!--OK--><!--dinámico-->
            <form class="derecha"> <!--Cerca de llibres-->
                <label>Cerca</label>
                <input class="" type="text">
                <input class="boto-cerca" type="image" src="./media/sistema/buscar.png" alt="Enviar" width="20" height="20">
            </form>
        </div>
        <br>
        <section class="centrado contenedor-libros">
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