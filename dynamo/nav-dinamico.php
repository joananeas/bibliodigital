
<div id="info-dinamica" class="news-bar">
    <div class="news-content">
        <span>Noticia importante 📰</span>
        <!-- Dinamico!!! -->
    </div>
</div>

<header id="h">
    <a id="menuToggler" href="#" class="menu-hamburguesa"><img id="menuTogglerImg" width="35px" height="35px" alt="icono menú hamburguesa (desplegable)"></a>
    <a href="./index.php" class="titulo"><h1 id="titulo"></h1></a>
    <div class="logout">
        <div class="dropdown">
            <button style="margin-top:0;" class="botonUniversal_alt">🔔&nbsp;(<span id="notification-number"></span>)</button>
            <div class="dropdown-content" id="notification-submenu">
                <!-- Dinamico!!! -->
            </div>
        </div>
        <span id="info-usuari"></span>
        <button id="logoutBoton" class="botonUniversal_alt" style="margin-top:0;">Logout</button>
    </div>
</header>
<nav class="frame" style="margin-left:0; margin-right:0; margin-top:20px; padding:0;">
    <li><a href="index.php">🏠 Inici</a></li>
    <li><a href="llibres.php">🔍 Cerca de llibres</a></li>
    <li><a href="prestecs.php">💡 Buffet lectura</a></li>
    <li><a href="perfil.php">📚 Gestió de reserves</a></li>
    <li><a href="perfil.php">🚩 Enquestes </a></li>
    <li><a href="perfil.php">⚙️ Gestió del compte</a></li>
    <li style="border: none;"><a href="perfil.php">📬 Bústia suggerim.</a>
</nav>

<script>
    let urlLogout = "";
    if (window.location.href.includes("admin")) urlLogout = "../mantenimiento/api.php";
    else urlLogout = "./mantenimiento/api.php";

    document.getElementById("logoutBoton").addEventListener("click", () => {
        console.log("[LOGOUT] Cerrando sesión...");
        let formData = new FormData();
        formData.append('pttn', 'logout');
        fetch(urlLogout, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.response === "ok") {
                console.log("[LOGOUT] Sesión cerrada.");
                if (window.location.href.includes("admin")) window.location.href = "../login.php";
                else window.location.href = "./login.php";
            } else {
                console.log("[LOGOUT] Error al cerrar sesión.");
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
    });
</script>

<script>
    let menuActivo = false;
    let menuImg = document.getElementById("menuTogglerImg");
    let menuToggler = document.getElementById("menuToggler");
    if (window.location.href.includes("admin")) {
        menuImg.src = "../media/icons/menuHamburguesa.png";
    }
    else {
        menuImg.src = "./media/icons/menuHamburguesa.png";
    }
    menuToggler.addEventListener("click", function() {
        if (menuActivo) {
            menuImg.style.transform = "rotate(0deg)";
            document.querySelector("nav").style.display = "none";
            document.querySelector("main").style.display = "block";
            document.querySelector("main").style.opacity = "1";
            menuActivo = false;
        } else {
            menuImg.style.transform = "rotate(90deg)";
            document.querySelector("nav").style.display = "block";
            document.querySelector("main").style.opacity = "0.2";
            document.querySelector("nav").style.opacity = "1";
            menuActivo = true;
        }
    });
</script>

