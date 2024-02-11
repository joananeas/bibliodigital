<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <nav class="frame" style="margin-left:0; margin-right:0; margin-top:20px; padding:0;">
        <li><a href="index.php">🏠 Inici</a></li>
        <li><a href="llibres.php">🔍 Cerca de llibres</a></li>
        <li><a href="prestecs.php">💡 Buffet lectura</a></li>
        <li><a href="perfil.php">📚 Gestió de reserves</a></li>
        <li><a href="perfil.php">🚩 Enquestes </a></li>
        <li><a href="perfil.php">⚙️ Gestió del compte</a></li>
        <li style="border: none;"><a href="perfil.php">📬 Bústia suggerim.</a>
    </nav>
    <main>
        <section class="frame" style="padding: 0;">
            <input type="text" id="inputCercaLlibres" placeholder="Cerca el llibre per: nom, autor, temàtica,...">    
            <!-- <button class="botonUniversal" id="btnCercaLlibres">Cerca</button> -->
            <div class="buscadorLlibres" id="buscadorLlibres"></div>
        </section>
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
</body>
</html>