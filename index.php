<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <main>
        <section class="carroussel">
            <button class="anterior">Anterior</button>
            <img class="carroussel-img" src="media/prueba.jpg">
            <button class="siguiente">Siguiente</button>
            <ul class="carroussel-puntos">
                <li class="activo"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </section>
        
        <section class="frame">
            <div class="header-section">
                <span style="float: left;">Cerca per:</span>
                <a href="#"><i>Clic per a veure tots.</i></a>
            </div>
            <div class="categorias">
                <button>Categoría 1</button>
                <button>Categoría 2</button>
                <button>Categoría 3</button>
            </div>

            <div class="topicos">
                <button>Tópico 1</button>
                <button>Tópico 2</button>
                <button>Tópico 3</button>
                <button>Tópico con un nombre largo</button>
                <button>Otro Tópico</button>
            </div>
        </section>

        <section class="frame">
            <div class="header-section">
                <span s="float: left;">Enquestes</span>
            </div>
            <ul>
                <li>Opció 1</li>
                <li>Opció 1</li>
                <li>Opció 1</li>
                <li>Opció 1</li>
                <li>Opció 1</li>
            </ul>
        </section>
    </main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
<!-- <script src="mantenimiento/scripts/index.js"></script> -->
</body>
</html>