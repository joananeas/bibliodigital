
// document.getElementById("buscador").addEventListener("focusout", function() {
//     document.getElementById("buscadorLlibres").style.display = "none";
// });
document.getElementById("inputCercaLlibres").addEventListener("focus", function() {
    document.getElementById("buscadorLlibres").style.display = "block";
});

const viewQR = () => {
    let video = document.getElementById('videoElement');
    let canvas = document.getElementById('canvasElement');

    let menuActivo = false;
    let form = document.getElementById('popupQR');
    if (menuActivo) {
        menuImg.style.transform = "rotate(0deg)";
        form.style.display = "none";
        video.style.display = "none";
        canvas.style.display = "none";
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
        menuActivo = false;
    } else {
        menuImg.style.transform = "rotate(90deg)";
        form.style.display = "flex";
        video.style.display = "block";
        canvas.style.display = "none";
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


document.getElementById('qrCerca').addEventListener("click", function(event) {
    event.preventDefault();
    const video = document.getElementById('videoElement');
    viewQR();

    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
    .then(function(stream) {
        video.srcObject = stream;
        popupQR.style.display = 'block';
        video.play().catch(error => console.error("Error al iniciar la reproducción del video", error));
        scanQRCode();
    }).catch(function(error) {
        console.error("Cannot access camera", error);
    });
});

const scanQRCode = () => {
    const canvasElement = document.getElementById('canvasElement');
    const canvas = canvasElement.getContext('2d');
    const video = document.getElementById('videoElement');

    function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
            var code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });
            if (code) {
                console.log("Found QR code", code.data);
                video.srcObject.getTracks().forEach(track => track.stop());
                video.style.display = 'none';
                alert("Código QR detectado: " + code.data);
                return;
            }
        }
        requestAnimationFrame(tick);
    }
    tick();
}

document.getElementById("inputCercaLlibres").addEventListener("input", function() {
    let formData = new FormData();
    formData.append('pttn', 'cercaLlibresLite');
    formData.append('llibre', this.value);
    if (this.value !== "") {
        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // console.log("[RESPONSE: Cerca] ", data);
            if(data.response === "OK") {
                let response = data.llibres;
                let desplegable = document.getElementById("buscadorLlibres");
                desplegable.innerHTML = "";
                for(let i = 0; i < response.length; i++) {
                    let libro = response[i];

                    let estadoLibro = document.createElement("span");
                    let tituloLibro = document.createElement("li");

                    estadoLibro.className = "estadoLlibro";
                    if (libro.estadoActual === "Disponible") estadoLibro.style.color = "green", estadoLibro.innerHTML = "Disponible";
                    else estadoLibro.style.color = "red", estadoLibro.innerHTML = "No disponible";

                    tituloLibro.className = "llibre";
                    tituloLibro.appendChild(estadoLibro);
                    tituloLibro.appendChild(document.createTextNode(libro.nom));

                    // Agregar botón al final del elemento tituloLibro
                    let a = document.createElement("a");
                    a.href = `./libro.php?libro=${libro.nom}`;
                    a.innerHTML = "Clic aquí per a més informació";
                    a.style.marginLeft = "25px";
                    a.style.color = "grey";
                    tituloLibro.appendChild(a);

                    let boton = document.createElement("button");
                    boton.innerHTML = "Reservar";
                    boton.className = "botonUniversal";
                    boton.style.margin = "0px";
                    boton.style.float = "right";
                    tituloLibro.appendChild(boton);

                    desplegable.appendChild(tituloLibro);
                    boton.addEventListener("click", function() {
                        console.log("Reservar: ", libro.nom);
                        window.location.href = `./reservas.php?libro=${libro.nom}`;
                    });

                    if (i === response.length - 1) {
                        tituloLibro.style.borderBottom = "none";
                    }
                }
            }
        })
            .catch(error => {
                console.log("[ERROR (API_Request)] ", error);
            });
    }
});

const cBack = document.getElementById("c-anterior");
const cNext = document.getElementById("c-siguiente"); 
const cPhoto = document.getElementById("c-foto");
let tmpData;

const getFotos = () => {
    let formData = new FormData();
    formData.append('pttn', 'getFotos');
    fetch('./mantenimiento/api.php', {
        method: "POST",
        body: formData
    }).then(response => response.json())
    .then(data => {
        console.log("[RESPONSE: Cerca] ", data);
        tmpData = data.num_libros
        const puntosCarroussel = document.getElementById("puntos-carroussel");
        for(let i = 0; i < data.num_libros; i++){
            let li = document.createElement("li");
            li.id = "c-dot-" + i;
            if (i === 0) li.className = "activo";
            puntosCarroussel.appendChild(li);
        }
        console.log(data) 
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
};

document.addEventListener("DOMContentLoaded", getFotos());

const updateActiveDot = (newActiveIndex) => {
    const dots = document.querySelectorAll("#puntos-carroussel li");
    dots.forEach(dot => dot.classList.remove("activo"));
    const newActiveDot = document.getElementById("c-dot-" + newActiveIndex);
    if (newActiveDot) newActiveDot.classList.add("activo");
};

const r = () =>{
    let src = cPhoto.src;
    let partes = src.split('-');
    let srcNumericoConExtension = partes.slice(1).join('-');
    let partesPuntos = srcNumericoConExtension.split('.');
    let srcNumericoSinExtension = partesPuntos.slice(0, -1).join('.');
    srcNumericoSinExtension = parseInt(srcNumericoSinExtension);
    if ((srcNumericoSinExtension < tmpData+1) && (srcNumericoSinExtension > 1)) srcNumericoSinExtension--;
    else srcNumericoSinExtension = tmpData;
    
    console.log("num: ", tmpData, " actual: ", srcNumericoSinExtension);
    let newActiveIndex = srcNumericoSinExtension - 1;
    updateActiveDot(newActiveIndex);
    //console.log("nombre", partes,"Ext", partesPuntos[1], "Num: ", srcNumericoSinExtension);
    console.log("SRC: ", partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1]);
    cPhoto.src = partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1];
}

cBack.addEventListener("click", function() {
    r()
});

const l = () => {
    const src = cPhoto.src;
    const partes = src.split('-');
    const srcNumericoConExtension = partes.slice(1).join('-');
    const partesPuntos = srcNumericoConExtension.split('.');
    let srcNumericoSinExtension = partesPuntos.slice(0, -1).join('.');
    srcNumericoSinExtension = parseInt(srcNumericoSinExtension);
    console.log("num: ", tmpData, " actual: ", srcNumericoSinExtension);
    if (tmpData > srcNumericoSinExtension) srcNumericoSinExtension++;
    else srcNumericoSinExtension = 1;

    let newActiveIndex = srcNumericoSinExtension - 1;
    updateActiveDot(newActiveIndex);
    //console.log("nombre", partes,"Ext", partesPuntos[1], "Num: ", srcNumericoSinExtension);
    console.log("SRC: ", partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1]);
    cPhoto.src = partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1];
}

cNext.addEventListener("click", function() {
    l()
});


// Controla el swipe para moviles

let touchstartX = 0
let touchendX = 0
    
function checkDirection() {
  if (touchendX < touchstartX) l()
  if (touchendX > touchstartX) r()
}

cPhoto.addEventListener('touchstart', e => {
  touchstartX = e.changedTouches[0].screenX
})

cPhoto.addEventListener('touchend', e => {
  touchendX = e.changedTouches[0].screenX
  checkDirection()
})