<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
<main>
    <section class="frame">
        <div class="header-libros">
        <span>Llibre a reservar: <span id="nombreLibro" class="fuenteH1"></span></span>
        </div>   
        <div class="container">
            <aside class="aside-text">
                <p>Títol: <span class="fuenteH1"></span></p>
                <label>Data inici:</label>
                <input type="date" id="fechaReserva" class="fuenteTexto"> <br>
                <label>Data fi:</label>
                <input type="date" id="fechaReserva" class="fuenteTexto"> <br>
                <button id="reservar" class="botonUniversal">Reservar</button>
            </aside>    
        </div>
    </section>
</main>

<!-- Footer DINAMICO -->
<?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html> 