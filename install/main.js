
let f = document.getElementById("formInstalacionNormal");

const success = () => {
    f.style.border = "2px solid green";
}

const error = () => {
    f.style.border = "2px solid red";
}

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
    }).then(response => {
        if(response.status === "ok") {
            console.log(response);
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
