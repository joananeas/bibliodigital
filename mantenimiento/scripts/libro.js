const viewQR = () => {
    let menuActivo = false;
    let form = document.getElementById('popupQR');
    if (menuActivo) {
        menuImg.style.transform = "rotate(0deg)";
        form.style.display = "none";
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
        menuActivo = false;
    } else {
        menuImg.style.transform = "rotate(90deg)";
        form.style.display = "flex";
        document.querySelector("main").style.opacity = "0.2";
        form.style.opacity = "1";
        menuActivo = true;
    }
    
    document.getElementById('close').addEventListener('click', () => {
        document.getElementById('popupQR').style.display = 'none';
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
    });
};

const getLibro = () => {
    document.getElementById('min-qr').addEventListener('click', viewQR);
    let urlString = window.location.href;
    let paramString = urlString.split('?')[1];
    let queryString = new URLSearchParams(paramString);
    let libro = queryString.get('libro');
    console.log("Libro is:" + libro);
    return libro;
};

const loadQR = () => {
    let formData = new FormData();
    formData.append('pttn', 'server-ip');
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.response === "ok") {
            console.log(data);
            let qr = new QRCode(document.getElementById('qrcode'), {
                text: window.location.href,
                width: 128,
                height: 128,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}

let l = getLibro(); // No se por que, no funcionaba una funcion DOMContentLoaded, asi que lo he puesto aqui.

if (!l) {
    window.location.href = "./index.php";
}

else {
    loadQR();
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
            console.log(data);
            data.llibres.forEach(libro => {
                document.getElementById('tituloLibro').innerHTML = libro.nom;
                document.getElementById('estrellas').innerHTML = libro.estrellas;
                document.getElementById('libroImagen').src = "https://aplicacions.ensenyament.gencat.cat" + libro.url;
                document.getElementById('resumLibro').innerHTML = libro.resum;
                document.getElementById('autorLibro').innerHTML = libro.autor;
            });
        }
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}

const toast = document.getElementById('toast');
const toastFrame = document.getElementById('toast-frame');

document.getElementById('share-copy').addEventListener('click', () => {
    navigator.clipboard.writeText(window.location.href).then(() => {
        toast.innerHTML = 'URL ';
        toastFrame.style.display = 'block';
        setTimeout(() => {
            toastFrame.style.display = 'none';
        }, 2000);
    }).catch(err => {
        toast.innerHTML = 'Error al copiar la URL al portapapers.';
    });
});