<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require_once "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

    <!-- Nav DINAMICO -->
    <?php require_once "dynamo/nav-dinamico.php"; ?>
    <main>
        <section id="buscador" class="frame" style="padding: 0;">
            <input type="text" id="inputCercaLlibres" placeholder="Cerca el llibre per: nom, autor, temàtica,...">
            <a id="qrCerca"><img src="./media/icons/qr-code.png" alt="Cerca Per QR" class="cercaLlibresPerQr" width="30"
                    height="30"></a>
            <div class="buscadorLlibres" id="buscadorLlibres"></div>
        </section>
        <section class="carroussel">
            <button id="c-anterior" class="c-boton anterior">&lt;</button>
            <img id="c-foto" class="carroussel-img" src="media/sistema/carroussel/prueba-1.jpg">
            <button id="c-siguiente" class="c-boton siguiente">&gt;</button>
            <ul id="puntos-carroussel" class="carroussel-puntos">
                <!-- Aquí se generan de forma automática-->
            </ul>
        </section>

        <section class="frame categoriesFrame">
            <div class="header-section">
                <span style="float: left;" class="fuenteH1">Vols provar alguna cosa nova?</span>
                <a href="#" id="veurePopUpCategories"><i>Clic per a veure tots.</i></a>
            </div>
            <article>
                <div class="categorias" id="categoriesContainer">
                </div>
            </article>
        </section>



        <section class="frame">
            <div class="header-section">
                <span style="float: left;" class="fuenteH1">Lectòmetre 🔥</span>
            </div>
            <article>
                <h3 class="fuenteH1">Pròximament.</h3>
            </article>
            <article style="display:none;">
                <div class="lectometre" id="lectometreContainerPodium">
                    <img src="media/icons/user.jpg" alt="1r" width="100" height="100">
                    <p class="fuenteH1">Primer 🥇</p>
                    <p class="fuenteTexto">Joan, 33 llibres</p>
                </div>
                <div class="lectometre Ranking" id="lectometreContainerRanking">
                    <!-- Top 5 lectors -->
                    <p class="fuenteH1">Top 5</p>
                    <div class="lectometreRankingUser">
                        <img src="media/icons/user.jpg" alt="1r" width="50" height="50">
                        <p class="fuenteTexto">Joan, 33 llibres</p>

                    </div>
                    <div class="lectometreRankingUser">
                        <img src="media/icons/user.jpg" alt="1r" width="50" height="50">
                        <p class="fuenteTexto">Joan, 33 llibres</p>
                    </div>
                    <div class="lectometreRankingUser">
                        <img src="media/icons/user.jpg" alt="1r" width="50" height="50">
                        <p class="fuenteTexto">Joan, 33 llibres</p>

                    </div>
                    <div class="lectometreRankingUser">
                        <img src="media/icons/user.jpg" alt="1r" width="50" height="50">
                        <p class="fuenteTexto">Joan, 33 llibres</p>
                    </div>
                    <div class="lectometreRankingUser">
                        <img src="media/icons/user.jpg" alt="1r" width="50" height="50">
                        <p class="fuenteTexto">Joan, 33 llibres</p>
                    </div>
                </div>
            </article>
        </section>
        <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>

    </main>
    <!-- Para la api de la cámara -->
    <div class="popup" style="display:none;" id="popupQR">
        <div class="popup-content">
            <section class="frame">
                <a id="closeQR" class="close">&times;</a>
                <p class="fuenteH1" style="text-align:center;">Escaneja un QR vàlid</p>
                <div class="qr">
                    <video id="videoElement" playsInline autoplay style="display:none;"></video>
                    <canvas id="canvasElement" style="display:none;"></canvas>
                </div>
            </section>
        </div>
    </div>

    <div class="popup" style="display:none;" id="popupCategories">
        <div class="popup-content">
            <section class="frame">
                <a id="closeCategories" class="close">&times;</a>
                <p class="fuenteH1" style="text-align:center;">Selecciona una categoria per a començar a buscar.</p>
                <p class="fuenteTexto" style="text-align:center;">També pots buscar-la aquí: <input
                        id="cercadorCategories" type="text" placeholder="Cercar categoria"></p>
                <div id="categoriesListPopup" class="categoriesListPopup">
                    <!-- Aquí se generan de forma automática-->
                </div>
            </section>
        </div>
    </div>
    <!-- Footer DINAMICO -->
    <?php require_once "dynamo/footer-dinamico.php"; ?>
</body>

</html>