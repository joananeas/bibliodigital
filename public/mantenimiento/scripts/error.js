document.addEventListener('DOMContentLoaded', () => {
    const getError = () => {
        let urlString = window.location.href;
        let paramString = urlString.split('?')[1];
        let queryString = new URLSearchParams(paramString);
        let e = queryString.get('error');
        console.log("Error is:" + e);
        return e;
    };

    const assignError = (error) => {
        let errorElement = document.getElementById('error');
        if (errorElement) {
            errorElement.innerHTML = error ? error : "Error desconocido";
        } else {
            console.log("Elemento de error no encontrado");
        }
    }

    let e = getError();
    assignError(e);
});