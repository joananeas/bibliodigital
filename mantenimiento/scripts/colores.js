const getColores = () => {
    let formData = new FormData();
    formData.append('pttn', 'getColores'); 

    return fetch(urlForFetch, {
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
        document.documentElement.style.setProperty('--color-principal', data.colorPrincipal);
        document.documentElement.style.setProperty('--color-secundario', data.colorSecundario);
        document.documentElement.style.setProperty('--color-terciario', data.colorTerciario);
        return data; // Devuelve los datos para su uso posterior
    });
}