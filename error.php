<?php
include './../mantenimiento/mant.php'; 
# Variables de dinámicos.
$estilos = ["componentes.css", "paginas/error.css"];
$espPagina = ["<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1 '>"];
error_reporting(0);

// Comprueba si existen las cookies de error
if (isset($_COOKIE['numError']) && isset($_COOKIE['textError'])) {
    $numError = $_COOKIE['numError']; 
    $textError = $_COOKIE['textError'];
    // Limpia las cookies para evitar mostrar los errores nuevamente en futuras cargas de la página
    setcookie('numError', '', time() - 3600, '/');
    setcookie('textError', '', time() - 3600, '/');
}
?>

<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Staatliches&display=swap');
        
        /* Aquí puedes agregar estilos específicos para centrar el contenido */
    </style>

    <main>
        <h1>🛠️Error <?php echo $numError; ?>🛠️</h1>
        <p><?php echo $textError;?><a href="./index.php">Torna a l'inici...</a></p>
    </main>
    <?php require "dynamo/footer-dinamico.php"; ?>
</body>

