<?php 
    include 'mantenimiento/mant.php'; 
    # Variables de dinámicos.
    $estilos = ["componentes.css", "paginas/login.css"];
    $espPagina = ["<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1 '>"];
?>

<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Staatliches&display=swap');
        
        /* Aquí puedes agregar estilos específicos para centrar el contenido */
    </style>

    <main>
        <span id="loginConfirmacion">Login Correcte.</span>
        <div class="login">
        <h1>Inicia Sessió</h1>
            <form>
                <label>Usuari</label><br>
                <input id="email" type="text" placeholder="nom_usuari"><br>
                <label>Contrasenya&nbsp;</label><br>
                <input id="password" type="password" placeholder="c0ntrasenya"><br>
                <input id="login" class="submit" type="submit" value="Login">
            </form>
        </div>
        <a href="https://github.com/joananeas/bibliodigital"><span class="copyright">&copy; Biblio Digital</span></a>
    </main>
    <?php require "dynamo/footer-dinamico.php"; ?>
</body>

