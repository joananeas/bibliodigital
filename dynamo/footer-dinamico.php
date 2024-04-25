<!-- El popup estará disponible en cada página --->
<!-- <section id="popup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <p>Some text in the Modal..</p>
    </div>
</section> -->

<footer id="footerMobile" class="centrado">
    <div>
        <a href="" aria-label="Inici"><img src="./media/icons/home.png" width="40" height="40"></a>
        <a href="" aria-label="Comunitat"><img src="./media/icons/heart.png" width="40" height="40"></a>
        <a href="" aria-label="Escanejar QR"><img src="./media/icons/qr-code-white.png" width="40" height="40"></a>
        <a href="" aria-label="Marcadors"><img src="./media/icons/markers.png" width="40" height="40"></a>
        <a href="" aria-label="Perfil Usuari"><img src="./media/icons/user.png" width="40" height="40"></a>
    </div>
</footer>


<footer id="footerDesktop" class="centrado">
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
