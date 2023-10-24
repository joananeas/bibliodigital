<?php
error_reporting(0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servidor = $_POST['server'];
    $usuari = $_POST['usuari'];
    $passwd = $_POST['passwd'];
    $nom = $_POST['nom'];
}

// ... Código de conexión a la base de datos ...
$conn = new mysqli($servidor, $usuari, $passwd, $nom);
// Luego, guarda las variables de error en cookies
if ($conn->connect_error) {
    $numError = 400; // Definir el número de error según tus necesidades
    $textError = "No s'ha pogut connectar a la Base de Dades" . $conn->connect_error;
    echo "hola";
} else {
    // Conectado
}

// Cerrar la conexión cuando hayas terminado de usarla
$conn->close();

?>
