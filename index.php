<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <main>
        <section id="buscador" class="frame" style="padding: 0;">
            <input type="text" id="inputCercaLlibres" placeholder="Cerca el llibre per: nom, autor, temàtica,...">    
            <a id="qrCerca"><img src="./media/icons/qr-code.png" alt="Cerca Per QR" class="cercaLlibresPerQr" width="30" height="30"></a>
            
            <!-- Para la api de la cámara -->
            <video id="videoElement" playsInline autoplay style="display:none;"></video>
            <canvas id="canvasElement" style="display:none;"></canvas>
            
            <div class="buscadorLlibres" id="buscadorLlibres"></div>
        </section>
        <section class="carroussel">
            <button id="c-anterior" class="c-boton anterior"><</button>
            <img id="c-foto" class="carroussel-img" src="media/sistema/carroussel/prueba-1.jpg">
            <button id="c-siguiente" class="c-boton siguiente">></button>
            <ul id="puntos-carroussel" class="carroussel-puntos">
                <!-- Aquí se generan de forma automática-->
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
        <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
    </main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html>