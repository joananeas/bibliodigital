<!-- El popup estará disponible en cada página --->
<section class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <p>Some text in the Modal..</p>
    </div>
</section>

<footer class="centrado">
    <span class="izquierda" id="version"></span><!--OK--><!--dinamico-->
    <div class="central">
        <span id="escuela-footer"></span>
    </div>
    <span class="derecha"><a href="https://github.com/sponsors/joananeas">Dóna ❤️</a></span>
    <?php 
        $url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (strpos($url, "admin") !== false || strpos($url, "pages") !== false){
            echo '<script src="../mantenimiento/scripts/main.js"></script>';
        } else {
            echo '<script src="mantenimiento/scripts/main.js"></script>';
        }
    ?>
</footer> 
