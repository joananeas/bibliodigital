<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require_once "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

    <!-- Nav DINAMICO -->
    <?php require_once "dynamo/nav-dinamico.php"; ?>
    <main>
        <section class="frame" style="margin-top:20px;">
            <h1 class="fuenteTitulo">Xats 💬</h1>
            <p>Selecciona un xat per començar a xatejar. </p>
            <p>Els comentaris ofensius no s'accepten, poden comportar una
                expulsió temporal. Sigues respectuós amb la resta d'usuaris i recorda que aquest xat és educacional.</p>
        </section>

        <div class="xatContainer">
            <section class="frame xatList" style="min-height: 500px;">
                Els teus xats es veuran aquí.
                <div class="xatListContainer" id="xatListContainer">
                    <a href="xat.php?xat=1" class="fuenteTexto">Xat amb Joan</a>
                </div>
            </section>
            <section class="frame xatView">
                <div class="xatViewHeader">
                    <h3 class="fuenteTitulo" id="titolXat"></h3>
                </div>
                <article class="xatMain">
                    <div class="xatViewContainer" id="xatViewContainer">
                        <!-- Aquí es mostraran els missatges -->
                    </div>
                </article>

                <form id="xatForm" class="xatSend">
                    <input type="text" name="xatMessage" id="xatMessage" class="fuenteTexto"
                        placeholder="Escriu un missatge..." maxlength="400" style="overflow-x:scroll;">
                    <button id="sendMessageBtn" type="submit" class="botonUniversal"
                        style="margin-top:0;">Enviar</button>
                </form>
            </section>
        </div>
    </main>

    <!-- Footer DINAMICO -->
    <?php require_once "dynamo/footer-dinamico.php"; ?>
</body>

</html>