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
        <div style="display: block;" id="formInstalacionNormal" class="login">
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
                <input class="submit" id="submit" type="button" value="Enviar🛠️">
            </form>
        </div>
        <div style="display: none;" id="formInstalacionLoading" class="login">
            <h1>Instal·lant </h1>
            <p id="statusInstalacion"></p>
        </div>
        <a href="https://github.com/joananeas/bibliodigital"><span class="copyright">&copy; Biblio Digital</span></a>
    </main> 
    <script src="main.js"></script>
</body>
</html>