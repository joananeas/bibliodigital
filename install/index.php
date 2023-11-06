<?php 
    include './../mantenimiento/mant.php'; 
    # Variables de dinámicos.
    $estilosFueraRango = ["./../estilos/componentes.css", "./../estilos/paginas/instalacion.css"];
    $espPagina = ["<meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1 '>"];
    $scripts = ['<script src="https://code.jquery.com/jquery-3.6.0.min.js">'];
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
            <form method="POST" id="mainForm" action="">
                <label>Servidor</label><br>
                <input type="text" name="server" placeholder="localhost"><br>
                <label>Nom BBDD</label><br>
                <input type="text" name="nom" placeholder="bibliodigitalDB"><br>
                <label>Usuari</label><br>
                <input type="text" name="usuari" placeholder="nom_usuari"><br>
                <label>Contrasenya</label><br>
                <input type="password" name="passwd" placeholder="c0ntrasenya"><br>
                <input class="submit" id="submit" type="submit" value="Enviar🛠️" onclick="enviarFormulario(event);">
            </form>
        </div>
        <a href="https://github.com/joananeas/bibliodigital"><span class="copyright">&copy; Biblio Digital</span></a>
    </main>
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
    if ($conn->connect_error) $respConn = false;
    else $respConn = true;

    $respConnToJSON = array("connexio" => $respConn);
    echo json_encode($respConnToJSON);
    // Cerrar la conexión cuando hayas terminado de usarla
    $conn->close();
    ?>
    <script>
        function mostrarAnimacion() {
            var formulario = $('#mainForm');

            formulario.animate({ borderColor: 'green' }, 1000, function() {
                // La animación se completa después de 1000 milisegundos (1 segundo)
            });
        }

        // Realizar una solicitud AJAX para obtener la respuesta JSON
        function enviarFormulario(event) {
            event.preventDefault(); // Prevenir la acción predeterminada del formulario

            // Realizar una solicitud AJAX para obtener la respuesta JSON
            $.ajax({
                url: 'db-conn.php',
                type: 'POST', // Cambia el método a POST para enviar los datos del formulario
                data: $('#mainForm').serialize(), // Envía los datos del formulario
                dataType: 'json',
                success: function(response) {
                    if (response.connection) {
                        mostrarAnimacion('green');
                        console.log('funciona');
                    }

                },
                error: function() {
                    console.log('Error en la solicitud AJAX');
                }
            });
        }

    </script>
    <?php 
        $fueraDeRango = true;
        $ruta = "./../";
        require "./../dynamo/footer-dinamico.php"; 
    ?>
</body>