<!--© Joan Aneas-->
<?php 
    include 'mantenimiento/mant.php'; 
    # Variables de dinámicos.
    $h1Web = "Compte d'usuari";
    $estilos = ["componentes.css", "paginas/cuenta.css"];
    $espPagina = ["<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1 '>"];
?>

<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <main>
        <section class="frame">
            <div class="header-libros">
                <span>Gestiona el teu compte d'usuari per a la biblioteca <span class="fuenteH1" style="font-size:18px; text-decoration:underline;"><?php echo $nomBiblioteca; ?></span></span>
            </div>
            <br>
            <div>
                <form>
                    <label>Nom d'usuari</label>
                    <input type="text" value="usuari"> <br>
                    <label>Correu electrònic</label>
                    <input type="text" value="usuari@domini.es"> <br>
                    <label>Contrasenya</label>
                    <input type="password" value="contrasenya"><br>
                    <label>Descripció</label><br>
                    <textarea name="" id="" cols="30" rows="10"></textarea>

                </form>
            </div>
        </section>
    </main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html>