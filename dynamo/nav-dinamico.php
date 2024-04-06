
<div id="info-dinamica" class="news-bar">
    <div class="news-content">
        <span>Noticia importante 📰</span>
        <!-- Dinamico!!! -->
    </div>
</div>

<header id="h">
    <a id="menuToggler" href="#" class="menu-hamburguesa"><img id="menuTogglerImg" width="35px" height="35px" alt="icono menú hamburguesa (desplegable)"></a>
    <a href="./index.php" class="titulo"><h1 id="titulo"></h1></a>
    <a class="menu-links" href="libro.php">Cercar llibres</a>
    <a class="menu-links" href="#">Pjt Carlos</a>
    <div class="logout">
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
    document.getElementById("logoutBoton").addEventListener("click", () => {
        console.log("[LOGOUT] Cerrando sesión...");
        let formData = new FormData();
        formData.append('pttn', 'logout');
        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.response === "ok") {
                console.log("[LOGOUT] Sesión cerrada.");
                window.location.href = "./login.php";
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
    let menuImg = document.getElementById("menuTogglerImg");
    let menuToggler = document.getElementById("menuToggler");
    let menuActivo = false;
    if (window.location.href.includes("admin")) {
        menuImg.src = "../media/icons/menuHamburguesa.png";
    }
    else {
        menuImg.src = "./media/icons/menuHamburguesa.png";
    }
    menuToggler.addEventListener("click", function() {
        if (menuActivo) {
            console.log("Cerrando menú...");
            menuImg.style.transform = "rotate(0deg)";
            document.querySelector("nav").style.display = "none";
            document.querySelector("main").style.display = "block";
            document.querySelector("main").style.opacity = "1";
            menuActivo = false;
        } else {
            console.log("Abriendo menú...");
            menuImg.style.transform = "rotate(90deg)";
            document.querySelector("nav").style.display = "block";
            document.querySelector("main").style.opacity = "0.2";
            document.querySelector("nav").style.opacity = "1";
            menuActivo = true;
        }
    });
</script>

