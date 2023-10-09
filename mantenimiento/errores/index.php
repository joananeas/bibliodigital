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
    <main>
        <?php
            if (isset($_GET['numError']) && isset($_GET['textError'])) {
                $numError = $_GET['numError'];
                $textError = $_GET['textError'];
                // Ahora puedes mostrar el número de error y el texto de error.
            } else {
                echo "No se proporcionaron datos de error.";
            }
        ?>
        <div class="centrado">
            <h1>[ERROR] <?php echo $numError;?></h1>
            <h3>Per a més informació contacteu amb l'administrador.</h3>
            <p>Error: <?php echo $textError;?></p>
        </div>
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