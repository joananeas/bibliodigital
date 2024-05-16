<!--© Joan Aneas-->
<!-- Header DINAMICO -->
<?php require_once "../dynamo/header-dinamico.php"; ?>

<body class="colorPrincipalBg fuenteTexto">

    <!-- Nav DINAMICO -->
    <?php require_once "../dynamo/nav-dinamico.php"; ?>
    <main>
        <br>
        <!-- nav de admin -->
        <section class="frame nav-admin">
            <ul>
                <li><a id="admin-config" href="#" class="nav-link" data-target="admin-config-panel">Configuració</a>
                </li>
                <li><a id="admin-books" href="#" class="nav-link" data-target="admin-books-panel">Llibres</a></li>
                <li><a id="admin-reserves" href="#" class="nav-link" data-target="admin-reserves-panel">Reserves</a>
                </li>
                <li><a id="admin-prestecs" href="#" class="nav-link" data-target="admin-prestecs-panel">Préstecs</a>
                </li>
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

        <!-- Panel de libros -->
        <div id="admin-books-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">Llibres <button class="botonUniversal" style="margin-top:0px; float:right;"
                        onclick="formCreateBook()">Crear un nou llibre 📗</button></p>
                <form id="formSearchBooks">
                    <label for="searchBooks">Buscar:</label>
                    <input type="text" id="campoBuscarLibroIndividual" name="searchBooks"
                        placeholder="Cerca per títol o autor">
                </form>
                <ul id="buscadorLlibres" style="list-style-type: none; padding: 0;">
                    <!-- Los resultados de la búsqueda se añadirán aquí -->
                </ul>
                <div class="vistaLibro" id="vistaLibro">
                    <form id="crearLlibre">
                        <label for="identificador">Identificador:</label>
                        <input style="width: fit-content;" class="primerInput" type="text" id="identificador"
                            name="identificador" readonly>

                        <label>Exemplars:</label>
                        <input type="number" id="exemplars" name="exemplars" class="primerInput">
                        <br>

                        <label for="titol">Catàleg:</label>
                        <input type="text" id="cataleg" name="cataleg" class="primerInput">

                        <label for="isbn">Biblioteca:</label>
                        <input type="text" id="biblioteca" name="biblioteca">
                        <br>

                        <label for="titol">Títol:</label>
                        <input type="text" id="titol" name="titol" class="primerInput">

                        <label for="isbn">ISBN:</label>
                        <input type="text" id="isbn" name="isbn">
                        <br>

                        <label for="cdu">CDU:</label>
                        <input type="text" id="cdu" name="cdu" class="primerInput">

                        <label for="format">Format:</label>
                        <input type="text" id="format" name="format">
                        <br>

                        <label for="autor">Autor:</label>
                        <input type="text" id="autor" name="autor" class="primerInput">

                        <label for="editorial">Editorial:</label>
                        <input type="text" id="editorial" name="editorial">
                        <br>

                        <label for="lloc">Lloc:</label>
                        <input type="text" id="lloc" name="lloc" class="primerInput">

                        <label for="colleccio">Col·lecció:</label>
                        <input type="text" id="colleccio" name="colleccio">
                        <br>

                        <label for="pais">País:</label>
                        <input type="text" id="pais" name="pais" class="primerInput">

                        <label for="data">Data:</label>
                        <input type="date" id="data" name="data">
                        <br>

                        <label for="llengua">Llengua:</label>
                        <input type="text" id="llengua" name="llengua" class="primerInput">

                        <label for="materia">Matèria:</label>
                        <input type="text" id="materia" name="materia">
                        <br>

                        <label for="descriptor">Descriptor:</label>
                        <input type="text" id="descriptor" name="descriptor" class="primerInput">

                        <label for="nivell">Nivell:</label>
                        <input type="text" id="nivell" name="nivell">
                        <br>

                        <label for="resum">Resum:</label>
                        <textarea id="resum" name="resum"></textarea>
                        <br>

                        <label for="url">URL:</label>
                        <input type="text" id="url" name="url" class="primerInput">

                        <label for="adreca">Adreça:</label>
                        <input type="text" id="adreca" name="adreca">
                        <br>

                        <label for="dimensio">Dimensió:</label>
                        <input type="text" id="dimensio" name="dimensio" class="primerInput">

                        <label for="volum">Volum:</label>
                        <input type="text" id="volum" name="volum">
                        <br>

                        <label for="pagines">Pàgines:</label>
                        <input type="number" id="pagines" name="pagines" class="primerInput">

                        <label for="proc">Proc:</label>
                        <input type="text" id="proc" name="proc">
                        <br>

                        <label for="carc">Carc:</label>
                        <input type="text" id="carc" name="carc" class="primerInput">

                        <label for="camp_lliure">Camp Lliure:</label>
                        <input type="text" id="camp_lliure" name="camp_lliure">
                        <br>

                        <label for="npres">NPres:</label>
                        <input type="number" id="npres" name="npres" class="primerInput">
                        <br>

                        <label for="rec">Rec:</label>
                        <input type="text" id="rec" name="rec" class="primerInput">

                        <label for="estat">Estat:</label>
                        <input type="text" id="estat" name="estat">
                        <br>
                        <button type="submit" class="botonUniversal" id="crearLlibreSubmit" style="display: none;">Crear
                            Llibre 💾</button>
                        <button type="submit" class="botonUniversal" id="modificarLlibreSubmit"
                            style="display: none;">Modificar 💾</button>
                    </form>
                </div>
            </section>
        </div>

        <!-- Panel de reservas -->
        <div id="admin-reserves-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">Reserves</p>
                <div class="tableContainer">
                    <table id="reservesList" class="tableBody">
                        <tr>
                            <th>ID</th>
                            <th>Llibre</th>
                            <th>Usuari</th>
                            <th>Data Inici</th>
                            <th>Data Fi</th>
                            <th>Estat</th>
                            <th>Prolongada</th>
                            <th>Motiu prolongació</th>
                            <th>Accions</th>
                        </tr>
                    </table>
                </div>
            </section>
        </div>

        <!-- Panel de préstamos -->
        <div id="admin-prestecs-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">
                    Préstecs
                </p>
                <div class="tableContainer">
                    <table id="prestecsList" class="tableBody">
                        <tr>
                            <th>ID</th>
                            <th>Llibre</th>
                            <th>Usuari</th>
                            <th>Data Inici</th>
                            <th>Data Fi</th>
                            <th>Data Devolució</th>
                            <th>Estat</th>
                            <th>Comentaris</th>
                            <th>Accions</th>
                        </tr>
                    </table>
                </div>
            </section>
        </div>

        <!-- Panel de usuarios -->
        <div id="admin-users-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">Usuarios <button class="botonUniversal" style="margin-top:0px; float:right;"
                        onclick="formCreateUser()">Crear Usuari</button></p>
                <div class="tableContainer">
                    <table id="userList" class="tableBody">
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

        <!-- Panel de estilos -->
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
                    <button type=" submit" class="botonUniversal">Cambiar banner</button>
                </form>
            </section>
            <br>
            <section class="frame">
                <p class="fuenteH1">Cambiar colores</p>
                <form id="formColores">
                    <label for="colorPrincipal">Color Principal:</label>
                    <input type="text" id="colorPrincipal" name="colorPrincipal"
                        pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC"
                        title="Ingrese un código de color hexadecimal válido">
                    <br>
                    <label for="colorSecundario">Color Secundario:</label>
                    <input type="text" id="colorSecundario" name="colorSecundario"
                        pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC"
                        title="Ingrese un código de color hexadecimal válido">
                    <br>
                    <label for="colorTerciario">Color Terciario:</label>
                    <input type="text" id="colorTerciario" name="colorTerciario"
                        pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" placeholder="#123ABC"
                        title="Ingrese un código de color hexadecimal válido">
                    <br>
                    <button type="submit" class="botonUniversal">Cambiar colores</button>
                </form>
            </section>
        </div>

        <!-- Panel de estadísticas -->
        <div id="admin-stats-panel" class="admin-panel">
            <section class="frame">
                <p class="fuenteH1">Estadístiques 👥</p>
                <p>Usuaris actius: <span id="usuariosActivos">0</span></p>
                <p>Usuaris inactius: <span id="usuariosInactivos">0</span></p>
                <p>Usuaris expulsats: <span id="usuariosBaneados">0</span></p>
                <p>Usuaris eliminats: <span id="usuariosEliminados">0</span></p>
                <p>Usuaris totals: <span id="usuariosTotales">0</span></p>
            </section>
            <section class="frame" style="margin-top:20px;">
                <p class="fuenteH1">Estadístiques 📚</p>
                <p>Total Llibres: <span id="librosTotal">0</span></p>
                <p>Total Exemplars: <span id="librosExemplars">0</span></p>
            </section>
        </div>
    </main>

    <!-- Los "popups" tienen que estar fuera de main -->
    <div class="popup" style="display:none;" id="formCreateUser">
        <section class="frame popup-content">
            <a id="close">&times;</a>
            <p class="fuenteH1">Crear Usuario</p>
            <form id="formUser">
                <label for="nombre">Correo:</label>
                <input type="text" id="email" name="nombre" require_onced>
                <br>
                <label for="correo">Password:</label>
                <input type="text" id="passwd" name="correo" require_onced>
                <br>
                <label for="password">Rol:</label>
                <select id="rol" name="rol">
                    <option value="user">Usuario</option>
                    <option value="moderador">Moderador</option>
                    <option value="bibliotecari">Bibliotecario</option>
                    <option value="admin">Admin</option>
                </select>
                <br>
                <button id="submitFormCreateUser" type="submit" class="botonUniversal">Crear Usuario</button>
            </form>
        </section>
    </div>
    <!-- Footer DINAMICO -->
    <?php require_once "../dynamo/footer-dinamico.php"; ?>
</body>

</html>