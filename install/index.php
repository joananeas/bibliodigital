<?php 
    include './../mantenimiento/mant.php'; 
    # Variables de dinámicos.
    $estilosFueraRango = ["./../estilos/componentes.css", "./../estilos/paginas/instalacion.css"];
    $espPagina = ["<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1 '>"];
?>

<!-- Header DINAMICO -->
<?php require "./../dynamo/header-dinamico.php"; ?>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Staatliches&display=swap');
        
        /* Aquí puedes agregar estilos específicos para centrar el contenido */
    </style>

    <main>
        <div class="login">
        <h1>Instal·lació</h1>
        <h4>(BBDD)</h4>
            <form method="POST" action="db-conn.php">
                <label>Servidor</label><br>
                <input type="text" name="server" placeholder="localhost"><br>
                <label>Nom BBDD</label><br>
                <input type="text" name="nom" placeholder="bibliodigitalDB"><br>
                <label>Usuari</label><br>
                <input type="text" name="usuari" placeholder="nom_usuari"><br>
                <label>Contrasenya</label><br>
                <input type="password" name="passwd" placeholder="c0ntrasenya"><br>
                <input class="submit" type="submit" value="Enviar🛠️">
            </form>
        </div>
        <a href="https://github.com/joananeas/bibliodigital"><span class="copyright">&copy; Biblio Digital</span></a>
    </main>
    <?php 
        $fueraDeRango = true;
        $ruta = "./../";
        require "./../dynamo/footer-dinamico.php"; 
    ?>
</body>

