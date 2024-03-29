<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
<main>
    <section class="frame">
        <div class="header-libros">
        <span>Vista del llibre: <span id="nombreLibro" class="fuenteH1"></span></span>
        </div>   
        <div class="container">
            <aside class="aside-image">
                <img src="./media/sistema/pruebas.jpg" class="book-image">
            </aside>
            <aside class="aside-text">
                <p>Títol: <span id="tituloLibro" class="fuenteH1"></span></p>
            </aside>    
            <article>
                <h3 class="fuenteH1"></h3>
                <span>Estrellas: </span><span id="estrellas" class="fuenteH1"></span>
            </article>
        </div>
    </section>
</main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html> 