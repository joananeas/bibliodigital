
let f = document.getElementById("formInstalacionNormal");

const success = () => {
    f.style.border = "2px solid green";
}

const error = () => {
    f.style.border = "2px solid red";
}

console.log("hola");
const comprobarConn = () => {
    let form = {
        "host": document.getElementById("server").value,
        "user": document.getElementById("usuari").value,
        "passwd": document.getElementById("passwd").value,
        "db": document.getElementById("nom").value
    };
        
    // if (form[0] == "" || form[1] == "" || form[2] == "" || form[3] == "") {
    //     error();
    //     return;
    // }

    fetch('./db-conn.php', {
        method: 'POST',
        body: JSON.stringify(form),
    }).then(response => response.json()) // Primero, analiza la respuesta en JSON
      .then(data => { // Luego, maneja los datos
        console.log(data); // Esto debería mostrar {"status":"ok","message":"Connexi\u00f3 a la base de dades correcta"}
        if(data.status == "ok") {
            success();
        } else {
            error();
        }
    });
}

document.getElementById("submit").addEventListener("click", function(e) {
    e.preventDefault();
    comprobarConn();
});
