/*
Fet per Joan Aneas.
    ⣿⣿⣿⣿⣿⣿⡿⠛⠋⠉⠄⠄⠄⠄⠄⠄⠄⠄⠄⠈⠉⠛⠿⣿⣿⣿⣿⣿⣿⣿
    ⣿⣿⣿⡿⠋⠁⠄⠄⢠⣴⣶⣿⣿⣶⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠈⠿⣿⣿⣿⣿
    ⣿⣿⡟⠁⠄⠄⠄⠄⣿⣿⣿⣿⣿⣿⣇⡀⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⢹⣿⣿⣿
    ⣿⣿⣧⠄⠄⠄⠄⢰⣿⣿⣿⣿⣿⣿⣿⡆⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⣸⣿⣿⣿
    ⣿⣿⣿⣧⡀⠄⠄⢸⣿⣿⣿⣿⣿⣿⣿⣷⣆⠄⠄⠄⠄⠄⠄⠄⠄⣰⣿⣿⣿⣿
    ⣿⣿⣿⣿⡿⣦⣀⣾⣿⣟⣉⠉⠙⢛⡏⠁⠄⠄⠄⠄⠄⠄⠄⠄⠚⢿⣿⣿⣿⣿
    ⣿⣿⣿⣿⣯⣗⣻⣿⣯⣥⣦⠄⣀⣾⡇⠄⠄⠂⠄⠄⠄⠄⠄⠄⠄⣼⣿⣿⣿⣿
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡇⠄⠄⠂⠄⠄⠄⠄⠄⠄⠄⣿⣿⣿⣿⣿
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣟⣻⠋⠄⠄⠄⠄⠄⠄⠄⢀⠄⣸⣿⣿⣿⣿⣿
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡁⡀⠄⠄⠄⠄⠄⠄⢸⣾⣿⣿⣿⣿⣿⣿
    ⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣥⣾⣷⠶⠆⠄⠄⠄⢀⠄⠄⠄⠸⣿⣿⣿⣿⣿⣿⣿
    ⣿⣿⣿⣿⣿⣿⣿⢿⣿⣿⣿⣿⣿⣶⣄⡀⠄⠄⠄⠄⠄⢀⠄⠸⣿⣿⣿⣿⣿⣿
    ⣿⣿⣿⣿⣿⣿⠟⠚⣿⣿⡻⠿⠿⠛⠙⠁⠄⠄⠄⠄⠠⠂⠄⠄⠘⠿⣿⣿⣿⣿
    ⠿⠛⠉⠁⠁⠄⠄⠄⣻⣿⣿⣧⣠⣀⠄⠄⠄⠄⡀⠂⠄⠄⠄⠄⠄⠄⠈⠉⠿⢿
    ⠄⠄⠄⠄⠄⠄⠄⠄⠄⠘⠿⣿⡿⠃⢀⡠⠄⠃⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄

    Zhong Xina, 1999.
*/

let f = document.getElementById("formInstalacionNormal");
let s = document.getElementById("statusInstalacion");
let t = document.getElementById("formInstalacionLoading-text");


const textoDeCarga = (texto, time) => {
    setTimeout(() => {}, time);
    t.textContent = texto;
}

const animacionFinal = () => {
    document.getElementById("formInstalacionLoading").style.display = "block";
    document.getElementById("formInstalacionUpload").style.display = "none";

    textoDeCarga("La truita de patates amb ceba o sense? - Tornant a l'inici.", 500);
    setTimeout(() => {
        window.location.href = "../index.php";
    }, 2000);
}

const csvToDB = async () => {
    let formData = new FormData();
    formData.append("peticion", "csv-to-db");
    console.log("Pujant arxius...");

    try {
        const response = await fetch('instalacion.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.status !== "ok") {
            console.log("Error al importar datos.");
            return;
        }

        if (data.message == "csv-to-db-ok") {
            textoDeCarga("Dades importades correctament.", 500);
            animacionFinal();
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

const xlsToCsv = async () => {
    let formData = new FormData();
    formData.append("peticion", "xls-to-csv");
    console.log("Convirtiendo archivo...");
    try {
        const response = await fetch('instalacion.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.status !== "ok") return;
        if (data.message == "xls-to-csv-ok") {
            console.log("Arxiu convertit correctament.");
            textoDeCarga("Arxiu convertit correctament.", 500);
            csvToDB();
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

const subirXls = () => {
    let formData = new FormData();
    formData.append("peticion", "subir-xls");
    console.log("Subiendo archivo...");
    let files = document.getElementById('upload').files;

    for (let i = 0; i < files.length; i++) {
        formData.append('uploads[]', files[i]);
    }

    fetch('instalacion.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== "ok") {
            console.log("Error al pujar l'arxiu.");
            return;
        }

        e = document.getElementById("errorUpload").value;
        console.log(data.message);

        if (data.message == "subir-xls-ok") {
            textoDeCarga("Arxiu pujat correctament.", 500);
            console.log("Arxiu pujat correctament.");
            xlsToCsv();
        }
    }).catch(error => {
        console.error('Error:', error);
    });
};

const config = async () => {
    let formData = new FormData();    
    // Añadir datos de configuración del sitio web
    formData.append("nomBiblioteca", document.getElementById("nomBiblioteca").value);
    formData.append("titolWeb", document.getElementById("titolWeb").value);
    formData.append("h1Web", document.getElementById("h1Web").value);
    formData.append("favicon", document.getElementById("favicon").value);
    formData.append("favicon", document.getElementById("favicon").files[0]);
    formData.append("colorPrincipal", document.getElementById("colorPrincipal").value);
    formData.append("colorSecundario", document.getElementById("colorSecundario").value);
    formData.append("colorTerciario", document.getElementById("colorTerciario").value);
    
    // Indicar la petición de configuración
    formData.append("peticion", "config");

    document.getElementById("formInstalacionLoading").style.display = "none";
    document.getElementById("formInstalacionFinal").style.display = "block";
    textoDeCarga("Calculant el diàmetre Solar...", 500);
    try {
        const response = await fetch('instalacion.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.status !== "ok") return; 
        switch(data.message){
            case "config-ok":
                textoDeCarga("Error al crear l'arxiu de configuració.", 500);
                document.getElementById("formInstalacionFinal").style.display = "none";
                document.getElementById("formInstalacionUpload").style.display = "block";
                
                document.getElementById("submitUpload").addEventListener("click", function() {
                    subirXls();
                });

                document.getElementById("skipUpload").addEventListener("click", function(e) {
                    e.preventDefault();
                    animacionFinal();
                });
                break;
            case "error-config":
                textoDeCarga("Arxiu de configuració creat correctament.", 500);
                window.location.href = "../index.php";
                break;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

const creacionAdmin = async () => {
    let formData = new FormData();
    formData.append("admin", document.getElementById("admin").value);
    formData.append("adminPass", document.getElementById("adminPass").value);
    formData.append("peticion", "creacion-admin");

    document.getElementById("formInstalacionLoading").style.display = "none";
    document.getElementById("formInstalacionAdmin").style.display = "block";
    textoDeCarga("Llegint documentació...", 500);

    try {
        const response = await fetch('instalacion.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.status !== "ok") return; 
        
        switch(data.message){
            case "admin-no-creado":
                textoDeCarga("Error al crear l'administrador.", 500);
                f.style.border = "2px solid red";
                setTimeout(() => {
                    f.style.border = "none";
                }, 2500);
                break;
            case "admin-creado":
                textoDeCarga("Administrador creat correctament.", 500);
                document.getElementById("formInstalacionAdmin").style.display = "none";
                document.getElementById("formInstalacionFinal").style.display = "block";
                document.getElementById("submitFinal").addEventListener("click", function(e) {
                    e.preventDefault();
                    config();
                });
                break;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

const instalacionTablas = async () => {
    let formData = new FormData();
    formData.append("peticion", "instalacion-tablas");

    textoDeCarga("Cercant als índex...", 500);

    try {
        const response = await fetch('instalacion.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        switch(data.message){
            case "tablas-no-creadas":
                console.log("Error al crear las tablas.");
                textoDeCarga("Error: " + data.content, 0);
                break;
            case "tablas-creadas":
                document.getElementById("formInstalacionLoading").style.display = "none";
                document.getElementById("formInstalacionAdmin").style.display = "block";
                
                document.getElementById("submitAdmin").addEventListener("click", function(e) {
                    e.preventDefault();
                    creacionAdmin();
                });

                break;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

const instalacionDb = async () => {
    let formData = new FormData();
    formData.append("host", document.getElementById("server").value);
    formData.append("user", document.getElementById("usuari").value);
    formData.append("passwd", document.getElementById("passwd").value);
    formData.append("db", document.getElementById("nom").value);
    formData.append("peticion", "instalacion-db");
    
    textoDeCarga("Validant dades...", 500);

    try {
        const response = await fetch('instalacion.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.status !== "ok") return;
        switch(data.message){
            case "archivo-no-creado":
                textoDeCarga("Error al crear l'arxiu de configuració.", 500);
                break;
            case "archivo-creado":
                textoDeCarga("Arxiu de connexió a la BBDD creat correctament.", 500);
                instalacionTablas(); // Solo se ejecuta si el archivo fue creado correctamente
                break;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

const comprobarConn = () => {
    let formData = new FormData();
    formData.append("host", document.getElementById("server").value);
    formData.append("user", document.getElementById("usuari").value);
    formData.append("passwd", document.getElementById("passwd").value);
    formData.append("db", document.getElementById("nom").value);
    formData.append("peticion", "comprobarConn");

    fetch('instalacion.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()) // Primero, analiza la respuesta en JSON
    .then(data => { // Luego, maneja los datos
        console.log(data); // Esto debería mostrar {"status":"ok","message":"Connexi\u00f3 a la base de dades correcta"}
        if(data.status === "ok" && data.message === "conex-ok") {
            document.getElementById("formInstalacionNormal").style.display = "none";
            document.getElementById("formInstalacionLoading").style.display = "block";
            history.pushState(null, '', './index.php?fase=instalacion');
            instalacionDb();
        } else {
            f.style.border = "2px solid red";
            setTimeout(() => {
                f.style.border = "none";
            }, 2500);
        }
    }).catch(function() {
        f.style.border = "2px solid red";
        setTimeout(() => {
            f.style.border = "none";
        }, 2500);
    });
}

const peticionAntesDeEmpezar = () => {
    history.pushState(null, '', './index.php?fase=comprobar-archivos');
    fetch('instalacion.php?peticion=comprobarArchivos', function(){
        method: 'GET'
    }).then(response => response.json())
    .then(data => {
        let l = document.getElementById("formAntesDeEmpezar-label");
        if (data.status !== "ok") return; 
        switch(data.message){
            case "no-existen":
                document.getElementById("formAntesDeEmpezar").style.display = "none";
                document.getElementById("formInstalacionNormal").style.display = "block";
                break;
            case "existen-2":
                document.getElementById("formAntesDeEmpezar").style.display = "block";
                document.getElementById("formInstalacionNormal").style.display = "none";
                l.textContent = "Ja existeixen els arxius de configuració. Segur que vols continuar?";
                break;
            case "existe-mant":
                document.getElementById("formAntesDeEmpezar").style.display = "block";
                document.getElementById("formInstalacionNormal").style.display = "none";
                l.textContent = "Ja existeix l'arxiu de configuració (manteniment). Segur que vols continuar?";
                break;
            case "existe-db":
                document.getElementById("formAntesDeEmpezar").style.display = "block";
                document.getElementById("formInstalacionNormal").style.display = "none";
                l.textContent = "Ja existeix l'arxiu de configuració (connexió-bbdd). Segur que vols continuar?";
                break;
        }

    }).catch(function(){
        console.log("Error");
    });

    document.getElementById("formAntesDeEmpezar-tornar").addEventListener("click", function(){
        window.location.href = "../index.php"
    });
    document.getElementById("formAntesDeEmpezar-continuar").addEventListener("click", function(){
        document.getElementById("formAntesDeEmpezar").style.display = "none";
        document.getElementById("formInstalacionNormal").style.display = "block";
        history.pushState(null, '', './index.php?fase=datos-bbdd');
    });

    document.getElementById("formInstalacionNormal-submit").addEventListener("click", function(e) {
        e.preventDefault();
        comprobarConn();
    });
}

document.addEventListener("DOMContentLoaded", peticionAntesDeEmpezar());

const paginas = {
    "comprobar-archivos": "formAntesDeEmpezar",
    "datos-bbdd": "formInstalacionNormal",
    "instalacion": "formInstalacionLoading"
};

if (window.location.href.includes("fase")) {
    let url = new URL(window.location.href);
    let fase = url.searchParams.get("fase");
    let pagina = paginas[fase];
    let paginasArray = Object.values(paginas);
    paginasArray.forEach(p => {
        let elemento = document.getElementById(p);
        if(elemento) {
            elemento.style.display = (p === pagina) ? "block" : "none";
        }
    });
}

// Fases en url:
// 1: Comprobar archivos: install/index.php?fase=comprobar-archivos
// 2: Instalación: install/index.php?fase=datos-bbdd
// 3: Instalación: install/index.php?fase=instalacion
