<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "../dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "../dynamo/nav-dinamico.php"; ?>
    <main>
        <section class="frame"><span class="fuenteH1">Benvingut/da a l'Administració de Diblio</span></section>
        <br>
        <section class="frame">
            <form id="formBanner">
                <span class="fuenteH1">Banner</span>
                <label class="switch">
                    <input type="checkbox" id="toggleSwitch">
                    <span class="slider round"></span>
                </label>
                <br><br>
                <label for="banner">Banner:</label>
                <input type="text" id="banner" name="banner"">
                <br>
                <button type="submit" class="botonUniversal">Cambiar banner</button>
            </form>
        </section>
        <br>
        <section class="frame">
            <p class="fuenteH1">Cambiar colores</p>
            <form id="formColores">
                <label for="colorPrincipal">Color Principal:</label>
                <input type="text" id="colorPrincipal" name="colorPrincipal" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC" title="Ingrese un código de color hexadecimal válido">
                <br>
                <label for="colorSecundario">Color Secundario:</label>
                <input type="text" id="colorSecundario" name="colorSecundario" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC" title="Ingrese un código de color hexadecimal válido">
                <br>
                <label for="colorTerciario">Color Terciario:</label>
                <input type="text" id="colorTerciario" name="colorTerciario" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC" title="Ingrese un código de color hexadecimal válido">
                <br>
                <button type="submit" class="botonUniversal">Cambiar colores</button>
            </form>
        </section>


    </main>

<!-- Footer DINAMICO -->
<?php require "../dynamo/footer-dinamico.php"; ?>
</body>
</html>