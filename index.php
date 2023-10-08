<!--© Joan Aneas-->
<?php include 'mantenimiento/mant.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./estilos/index.css">
    <link rel="stylesheet" type="text/css" href="./estilos/principal.css">
    <title><?php echo $titolWeb; ?></title><!--OK--><!--Debe de ser dinámico-->
</head>
<body>
    <header>
        <button class="menu-hamburguesa"><!--Hamburguesa-->a</button>
        <span class="titulo"><h1><?php echo $h1Web; ?></h1></span><!--OK--><!--Debe de ser dinámico-->
        <div class="cuenta-logout">
            <a class="logout">Logout</a>
            <a><img src="./media/icons/login.png" width="30" height="30" alt="cerrar sesión / tancar sessió"></a>
        </div>
    </header>
    <main>
        <div class="centrado">
            <span class="izquierda">Llistat llibres <?php echo $nomBiblioteca; ?></span><!--OK--><!--dinámico-->
            <form class="derecha"> <!--Cerca de llibres-->
                <label>Cerca</label>
                <input class="" type="text">
                <input class="boto-cerca" type="image" src="./media/sistema/buscar.png" alt="Enviar" width="20" height="20">
            </form>
        </div>
        <br>
        <section class="centrado contenedor-libros">
            <article class="libros"> <!--dinamico-->
                <h3 class="atrib">Titulo</h3>
                <img class="atrib" src="./media/sistema/pruebas.jpg" width="100" height="100" alt="libro">
                <br>
                <span class="atrib">estrellas</span>
                <p class="atrib">descripion breve</p>
            </article>
        </section>
    </main>
    <footer class="centrado">
        <span class="izquierda"><?php echo $version; ?></span><!--OK--><!--dinamico-->
        <div class="central">
            <a>privacitat</a>
            <a>etc</a>
        </div>
        <span class="derecha">&copy; <a href="https://github.com/joananeas">Joan Aneas</a></span>
    </footer>
</body>
</html>