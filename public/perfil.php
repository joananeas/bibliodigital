<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require_once "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

    <!-- Nav DINAMICO -->
    <?php require_once "dynamo/nav-dinamico.php"; ?>
    <main>
        <section class="frame">
            <div class="header-libros">
                <h2 class="fuenteH1">El teu Perfil</h2>
            </div>
            <br>
            <div class="profile-container">
                <aside>
                    <div class="imgContainer">
                        <img id="pfpPerfilSettings" src="./media/sistema/usuaris/default.jpg" alt="Imatge de perfil">
                    </div>
                    <div class="buttonContainer">
                        <button type="button" class="botonUniversal" id="cambiarImgBtn">Canviar Imatge</button>
                    </div>
                </aside>
                <article class="profileFormContainer">
                    <form id="profile-form">
                        <div class="form-group">
                            <label for="username">Nom d'usuari</label>
                            <input type="text" id="username" name="username"
                                placeholder="El teu nom d'usuari. Recorda que els noms ofensius queden prohibits.">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="email">Correu electrònic</label>
                            <input type="email" id="email" name="email" placeholder="usuari@domini.es">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="password">Contrasenya</label>
                            <input type="password" id="password" name="password"
                                placeholder="Deixa aquest camp buit si no vols canviar la contrasenya.">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="description">Descripció</label>
                            <textarea id="description" name="description" cols="30" rows="10" maxlength="200"
                                placeholder="La teva descripció, màxim 200 caràcters." class="fuenteTexto"></textarea>
                        </div>
                        <br>
                        <p id="toast" style="margin:0px;"></p>
                        <div class="form-group">
                            <button type="submit" class="botonUniversal">Guardar Canvis</button>
                        </div>
                    </form>
                </article>
            </div>
        </section>
    </main>

    <!-- Footer DINAMICO -->
    <?php require_once "dynamo/footer-dinamico.php"; ?>
</body>

</html>