getColores().then(data => {
    document.getElementById("colorPrincipal").value = data.colorPrincipal;
    document.getElementById("colorSecundario").value = data.colorSecundario;
    document.getElementById("colorTerciario").value = data.colorTerciario;
}).catch(error => {
    console.error('Error al obtener colores:', error);
});

document.getElementById('formColores').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData();
    formData.append('colorPrincipal', document.getElementById("colorPrincipal").value);
    formData.append('colorSecundario', document.getElementById("colorSecundario").value);
    formData.append('colorTerciario', document.getElementById("colorTerciario").value);
    formData.append('pttn', 'setColores'); 

    fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Respuesta de la red no fue ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error al hacer la petición:', error);
    });
});

getBanner().then(data => {
    console.log(data);
    if (data.bannerState === "1") {
        document.getElementById("toggleSwitch").checked = true;
        console.log(true);
    } else {
        document.getElementById("toggleSwitch").checked = false;
        console.log(false);
    }
    document.getElementById("banner").value = data.bannerText;
}).catch(error => {
    console.error('Error al obtener colores:', error);
});


document.getElementById('formBanner').addEventListener('submit', function(e) {
    e.preventDefault();
    let c = (document.getElementById("toggleSwitch").checked == true ? "1" : "0");
    let formData = new FormData();
    console.log("checked:", c);
    formData.append('bannerState', c);
    formData.append('bannerText', document.getElementById("banner").value);
    formData.append('pttn', 'setBanner');

    fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Respuesta de la red no fue ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error al hacer la petición:', error);
    });
});

document.addEventListener('DOMContentLoaded', getColores());
document.addEventListener('DOMContentLoaded', getBanner());