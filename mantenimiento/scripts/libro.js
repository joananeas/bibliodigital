fetch("./mantenimiento/api.php", {
    method: "POST",
    body: formData
})
.then(response => response.json())
.then(data => {
    // console.log("[RESPONSE: Cerca] ", data);
    if(data.response === "OK") {
        let llibres = data.llibres;
        let llibresDiv = document.getElementById("buscadorLlibres");
        llibresDiv.innerHTML = "";
        for(let i = 0; i < llibres.length; i++) {
            let llibre = llibres[i];
            let llibreLi = document.createElement("li");
            llibreLi.className = "llibre";
            llibreLi.textContent = llibre.nom; // Cambia 'titol' a 'nom'
            llibresDiv.appendChild(llibreLi);
            if (i === llibres.length - 1) {
                llibreLi.style.borderBottom = "none";
            }
        }
    }
})
.catch(error => {
    console.log("[ERROR (API_Request)] ", error);
});