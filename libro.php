<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
<main>
    <section class="frame vistaLibro">
        <div class="header-libros">
            <h3 class="fuenteH1">
                <span id="tituloLibro"></span>&nbsp;📖
            </h3>
        </div>   
        <div class="container">
            <aside class="aside-image">
                <img id="libroImagen" src="./media/sistema/pruebas.jpg" class="book-image">
            </aside>
            <aside class="aside-text">
                <p><span class="fuenteH1">Autor:</span>&nbsp;<span id="autorLibro"></span></p>
                <p><span class="fuenteH1">Categoria:</span>&nbsp;</p>
                <p><span class="fuenteH1">💬Descripció:</span>&nbsp;<span id="resumLibro"></span></p>
                <p><span class="fuenteH1">⭐Estrelles:</span>&nbsp;<span id="estrellas"></span></p>
            </aside>    
        </div>
    </section>
</main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html> 