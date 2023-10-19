<!--© Joan Aneas-->
<?php include 'mantenimiento/mant.php'; ?>

<!-- Header DINAMICO -->
<?php 
    require "dynamo/header-dinamico.php"; 
    # Variables de dinámicos.
    $estilos = ["principal.css", "componentes.css"];
?>

<body>
<!-- Nav DINAMICO -->
<?php require "dynamo/nav-dinamico.php"; ?>
    <main>
        <div class="centrado">
            <h1>Gestió del compte d'usuari</h1>
        </div>
        <br>
        <br>
        <br>
        <form>
            <!-- DINAMICO -->
            <?php $usuari = "hola"; $descripcio = "hasoijas"; $lenContra = strlen($contrasenya = "1234"); ?>
            
            <label>Usuari</label>
            <input type="text" placeholder=<?php echo "'".$usuari."'"; ?>><br>
            <label>Contrasenya</label>
            <input type="password" placeholder=<?php echo "'".$lenContra."'"; ?>><br>
            <label>Descripció</label>
            <input type="text" placeholder=<?php echo "'".$descripcio."'"; ?>><br>
            <label>Foto de perfil (pfp)</label>
            <input type="file"><br>
            <input type="submit">
        </form>
    </main>

    <!-- Footer DINAMICO -->
    <?php require "dynamo/footer-dinamico.php"; ?>
</body>
</html>