<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require "../dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

<!-- Nav DINAMICO -->
<?php require "../dynamo/nav-dinamico.php"; ?>
    <main>
        <br>
        <!-- nav de admin -->
        <section class="frame nav-admin">
            <ul>
                <li><a id="admin-config" href="#" class="nav-link" data-target="admin-config-panel">Configuració</a></li>
                <li><a id="admin-users" href="#" class="nav-link" data-target="admin-users-panel">Usuaris</a></li>
                <li><a id="admin-styles" href="#" class="nav-link" data-target="admin-styles-panel">Estètica</a></li>
                <li><a id="admin-stats" href="#" class="nav-link" data-target="admin-stats-panel">Estadístiques</a></li>
            </ul>
        </section>
        <br>
        <div id="admin-config-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">Configuración</p>
            </section>
        </div>
        <div id="admin-users-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">Usuarios <button class="botonUniversal" style="margin-top:0px; float:right;" onclick="formCreateUser()">Crear Usuari</button></p>
                <div class="tableContainer">
                    <table id="userList" class="tablaUsuarios">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </table>
                </div>
            </section>
        </div>
        <div id="admin-styles-panel" class="admin-panel">
            <section class="frame">
                <form id="formBanner">
                    <span class="fuenteH1">Banner</span>
                    <label class="switch">
                        <input type="checkbox" id="toggleSwitch">
                        <span class="slider round"></span>
                    </label>
                    <br><br>
                    <label for="banner">Banner:</label>
                    <input type="text" id="banner" name="banner"">
                    <br>
                    <button type="submit" class="botonUniversal">Cambiar banner</button>
                </form>
            </section>
            <br>
            <section class="frame">
                <p class="fuenteH1">Cambiar colores</p>
                <form id="formColores">
                    <label for="colorPrincipal">Color Principal:</label>
                    <input type="text" id="colorPrincipal" name="colorPrincipal" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC" title="Ingrese un código de color hexadecimal válido">
                    <br>
                    <label for="colorSecundario">Color Secundario:</label>
                    <input type="text" id="colorSecundario" name="colorSecundario" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC" title="Ingrese un código de color hexadecimal válido">
                    <br>
                    <label for="colorTerciario">Color Terciario:</label>
                    <input type="text" id="colorTerciario" name="colorTerciario" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC" title="Ingrese un código de color hexadecimal válido">
                    <br>
                    <button type="submit" class="botonUniversal">Cambiar colores</button>
                </form>
            </section>
        </div>
        <div id="admin-stats-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">Estadísticas</p>
                <p>Usuarios registrados: <span id="usuariosRegistrados">0</span></p>
                <p>Usuarios activos: <span id="usuariosActivos">0</span></p>
                <p>Usuarios inactivos: <span id="usuariosInactivos">0</span></p>
                <p>Usuarios baneados: <span id="usuariosBaneados">0</span></p>
                <p>Usuarios eliminados: <span id="usuariosEliminados">0</span></p>
                <p>Usuarios totales: <span id="usuariosTotales">0</span></p>
            </section>
        </div>
    </main>

    <!-- Los "popups" tienen que estar fuera de main -->
    <div class="popup" style="display:none;" id="formCreateUser">
        <section class="frame popup-content" >
            <a id="close">&times;</a>
            <p class="fuenteH1">Crear Usuario</p>
            <form id="formUser">
                <label for="nombre">Correo:</label>
                <input type="text" id="email" name="nombre" required>
                <br>
                <label for="correo">Password:</label>
                <input type="text" id="passwd" name="correo" required>
                <br>
                <label for="password">Rol:</label>
                <select id="rol" name="rol">
                    <option value="usuario">Usuario</option>
                    <option value="moderador">Moderador</option>
                    <option value="bibliotecario">Bibliotecario</option>
                    <option value="admin">Admin</option>
                </select>
                <br>
                <button id="submitFormCreateUser" type="submit" class="botonUniversal">Crear Usuario</button>
            </form>
        </section>
    </div>
<!-- Footer DINAMICO -->
<?php require "../dynamo/footer-dinamico.php"; ?>
</body>
</html>