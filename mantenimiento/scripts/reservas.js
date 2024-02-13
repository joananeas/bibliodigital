const getLibro = () => {
    let urlString = window.location.href;
    let paramString = urlString.split('?')[1];
    let queryString = new URLSearchParams(paramString);
    let libro = queryString.get('libro');
    console.log("Libro is:" + libro);
    return libro;
};

document.getElementById('reservar').addEventListener('click', function() {
    let titulo = getLibro();
    let fechaInicio = document.getElementById('fechaReserva').value;
    let fechaFin = document.getElementById('fechaReserva').value;

    let formData = new FormData();
    formData.append('pttn', 'reservarLibro');
    formData.append('titulo', titulo);
    formData.append('fechaInicio', fechaInicio);
    formData.append('fechaFin', fechaFin);

    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});