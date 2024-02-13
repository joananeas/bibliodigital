const getLibro = () => {
    let urlString = window.location.href;
    let paramString = urlString.split('?')[1];
    let queryString = new URLSearchParams(paramString);
    let libro = queryString.get('libro');
    console.log("Libro is:" + libro);
    return libro;
};

let l = getLibro(); // No se por que, no funcionaba una funcion DOMContentLoaded, asi que lo he puesto aqui.

if (!l) {
    window.location.href = "./index.php";
}

else {
    let formData = new FormData();
    formData.append('pttn', 'cercaLlibresFull');
    formData.append('llibre', l);
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.response === "OK") {
            console.log(data.llibres.nom);
            console.log(data.llibres.estadoActual);
        }
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}