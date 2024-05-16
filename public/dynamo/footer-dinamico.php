<!-- El popup estará disponible en cada página --->
<!-- <section id="popup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <p>Some text in the Modal..</p>
    </div>
</section> -->

<footer id="footerMobile" class="centrado">
    <div>
        <a id="footer-m-home" href="" aria-label="Inici"><img id="footer-img-home" width="40" height="40"></a>
        <a id="footer-m-community" href="" aria-label="Comunitat"><img id="footer-img-community" width="40"
                height="40"></a>
        <a id="footer-m-qr" href="" aria-label="Escanejar QR"><img id="footer-img-qr" width="40" height="40"></a>
        <a id="footer-m-markers" href="" aria-label="Marcadors"><img id="footer-img-markers" width="40" height="40"></a>
        <a id="footer-m-profile" href="" aria-label="Perfil Usuari"><img id="footer-img-user" width="40"
                height="40"></a>
    </div>
</footer>


<footer id="footerDesktop" class="centrado">
    <span class="izquierda" id="version"></span>
    <!--OK-->
    <!--dinamico-->
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