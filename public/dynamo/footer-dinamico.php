<!-- El popup estará disponible en cada página --->
<div class="popup" style="display:none;" id="popupUploadMedia">
    <div class="popup-content">
        <section class="frame">
            <a id="closeUploadMedia" class="close">&times;</a>
            <p class="fuenteH1" style="text-align:center;" id="uploadMediaTitle"></p>
            <p class="fuenteTexto" style="text-align:center;" id="uploadMediaSubtitle"></p>
            <div id="uploadMediaContent">
                <!-- Aquí se generan de forma automática-->
            </div>
            <div style="display:flex; align-items:center; justify-content:center;">
                <button id="uploadMediaButton" class="botonUniversal">Pujar</button>
            </div>
        </section>
    </div>
</div>

<!-- Para la api de la cámara -->
<div class="popup" style="display:none;" id="popupQR">
    <div class="popup-content">
        <section class="frame">
            <a id="closeQR" class="close">&times;</a>
            <p class="fuenteH1" style="text-align:center;">Escaneja un QR vàlid</p>
            <div class="qr">
                <video id="videoElement" playsInline autoplay style="display:none;"></video>
                <canvas id="canvasElement" style="display:none;"></canvas>
            </div>
        </section>
    </div>
</div>

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