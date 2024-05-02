<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="componentes.css">
    <link rel="stylesheet" type="text/css" href="instalacion.css">
    <title id="tituloTab">Biblio Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Staatliches&display=swap" rel="stylesheet"> 
</head>
<body>
    <main>
        <div style="display: none;" id="formAntesDeEmpezar" class="login">
            <h1>Instal·lació</h1>
            <form method="POST" id="mainForm" action="">
                <label id="formAntesDeEmpezar-label"></label><br>
                <input id="formAntesDeEmpezar-tornar" class="submit" type="button" value="↩️ Tornar">
                <input id="formAntesDeEmpezar-continuar" class="submit" type="button" value="Continuar🛠️">
            </form>
        </div>

        <div style="display: none;" id="formInstalacionNormal" class="login">
            <h1>Instal·lació</h1>
            <h4>(BBDD)</h4>
            <form method="POST" id="mainForm" action="">
                <label>Servidor</label><br>
                <input type="text" id="server" placeholder="localhost"><br>
                <label>Nom BBDD</label><br>
                <input type="text" id="nom" placeholder="bibliodigitalDB"><br>
                <label>Usuari</label><br>
                <input type="text" id="usuari" placeholder="nom_usuari"><br>
                <label>Contrasenya</label><br>
                <input type="password" id="passwd" placeholder="c0ntrasenya"><br>
                <!-- Evita que el formulario se envíe automáticamente y maneja el evento onclick con JavaScript -->
                <input class="submit" id="formInstalacionNormal-submit" type="button" value="Enviar🛠️">
            </form>
        </div>
        
        <div style="display: none;" id="formInstalacionLoading" class="login">
            <h1>Instal·lació</h1>
            <!-- <button class="submit" id="formInstalacionLoading-start">Instal·lam!</button> -->
            <p id="formInstalacionLoading-text"></p>
        </div>

        <div style="display: none;" id="formInstalacionAdmin" class="login">
            <h1>Instal·lació</h1>
            <h4>(Admin)</h4>
            <form method="POST" id="adminForm" action="">
                <p>Crea un compte d'usuari</p>
                <label>Usuari</label><br>
                <input type="text" id="admin" placeholder="admin"><br>
                <label>Contrasenya</label><br>
                <input type="password" id="adminPass" placeholder="admin"><br>
                <input class="submit" id="submitAdmin" type="button" value="Enviar🛠️">
            </form>
        </div>

        <div style="display: none;" id="formInstalacionFinal" class="login">
            <h1>Instal·lació</h1>
            <h4>(Config. site)</h4>
            <form method="POST" id="finalForm" action="">
                <p>Configura el teu lloc web</p>
                
                <label for="nomBiblioteca">Nom de la biblioteca</label><br>
                <input type="text" id="nomBiblioteca" placeholder="Nom de la biblioteca"><br>
                
                <label for="titolWeb">Títol del lloc web</label><br>
                <input type="text" id="titolWeb" placeholder="Títol del lloc web"><br>
                
                <label for="h1Web">Títol de la pàgina</label><br>
                <input type="text" id="h1Web" placeholder="Títol de la pàgina"><br>
                
                <label for="favicon">Favicon</label><br>
                <input type="text" id="favicon" placeholder="Ruta al favicon"><br>
                
                <label for="colorPrincipal">Color principal</label><br>
                <input type="text" id="colorPrincipal" placeholder="#4A68A0"><br>
                
                <label for="colorSecundario">Color secundari</label><br>
                <input type="text" id="colorSecundario" placeholder="#F5F5F5"><br>
                
                <label for="colorTerciario">Color terciari</label><br>
                <input type="text" id="colorTerciario" placeholder="#333333"><br>
                
                <input class="submit" id="submitFinal" type="button" value="Enviar🛠️">
            </form>
        </div>

        <div style="display: none;" id="formInstalacionUpload" class="login">
            <h1>Instal·lació</h1>
            <h4>(Upload)</h4>
            <form method="POST" id="uploadForm" enctype="multipart/form-data">
                <p>Aquí pots pujar el catàleg.xlsx i el exemplars.xls d'Epèrgam</p>
                <p>Ho podràs fer més tard a <code>/admin</code> si no disposes dels arxius.</p>
                <p><strong>NO MODIFIQUIS EL NOM DELS ARXIUS</strong></p>
                <label for="upload">Pujar arxiu(s)</label>
                <p><strong><span id="errorUpload"></span></strong></p>
                <input type="file" id="upload" name="uploads[]" multiple><br>
                <input class="submit" id="skipUpload" type="button" value="Ometre⏭">
                <input class="submit" id="submitUpload" type="button" value="Enviar🛠️">
            </form>
        </div>


        

        <a href="https://github.com/joananeas/bibliodigital"><span class="copyright">&copy; Biblio Digital</span></a>
    </main> 
    <script src="main.js"></script>
</body>
</html>