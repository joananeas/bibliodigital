<footer class="centrado">
    <span class="izquierda"><?php echo $version; ?></span><!--OK--><!--dinamico-->
    <div class="central">
        <?php 
            if($fueraDeRango === true) {
                echo '<a href="'.$ruta.'login.php">Login</a>
                <a href="'.$ruta.'cuenta.php">Cuenta</a>
                <a href="'.$ruta.'index.php">Home</a>
                <a href="'.$ruta.'install/">Install</a>';
            }
            else{
                echo '<a href="login.php">Login</a>
                <a href="cuenta.php">Cuenta</a>
                <a href="index.php">Home</a>
                <a href="./install/">Install</a>';
            }
            
        ?>
    </div>
    <span class="derecha">&copy; <a href="https://github.com/joananeas">Joan Aneas</a></span>
</footer> 
<?php
    if (isset($scripts)) {
        foreach ($scripts as $value) {
            echo $value . "</script>";
        }
    }
?>
<!-- 
    $estilos = [""];
    $titolWeb = ;
-->
