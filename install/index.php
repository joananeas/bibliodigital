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
            <button class="submit" id="formInstalacionLoading-start">Instal·lam!</button>
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
                <label>Nom del lloc</label><br>
                <input type="text" id="nomLloc" placeholder="Biblio Digital"><br>
                <label>Descripció</label><br>
                <input type="text" id="descLloc" placeholder="Biblioteca digital"><br>
                <label>Tema<span style="background-color:#4A68A0; border-radius:4px; color: white; padding-left: 3px; padding-right:3px; margin-left: 10px">soon</span></label><br>
                <input class="submit" id="submitFinal" type="button" value="Enviar🛠️">
            </form>
        </div>
        

        <a href="https://github.com/joananeas/bibliodigital"><span class="copyright">&copy; Biblio Digital</span></a>
    </main> 
    <script src="main.js"></script>
</body>
</html>