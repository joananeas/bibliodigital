const viewQR = (formulari, close) => {
    let menuActivo = false;
    let form = document.getElementById(formulari);
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
    
    document.getElementById(close).addEventListener('click', () => {
        document.getElementById(formulari).style.display = 'none';
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
    });
};

const getLibro = () => {
    document.getElementById('min-qr').addEventListener('click', () => viewQR('popupQR', 'closeQR'));
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

document.getElementById('reservar').addEventListener('click', function() {
    viewQR('popupReserva', 'closeReserva');
    const monthNames = ["Gener", "Febrer", "Març", "Abril", "Maig", "Juny", 
        "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre"];
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    const monthYear = document.getElementById('month-year');
    const calendarBody = document.getElementById('calendar-body');
    const prevMonth = document.getElementById('prev-month');
    const nextMonth = document.getElementById('next-month');

    prevMonth.onclick = () => changeMonth(-1);
    nextMonth.onclick = () => changeMonth(1);

    function changeMonth(step) {
        currentMonth += step;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        fillCalendar();
    }

    const getReserves = () => {
        let formData = new FormData();
        formData.append('pttn', 'getReserves');
        formData.append('id', l);
        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.response === "OK") {
                return data.reserves;
            }
            else if (data.response === "ERROR") {
                return null;
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
    }

    function padToTwoDigits(value) {
        return value.toString().padStart(2, '0');
    }

    const highlightNextDays = (day, month, year) => {
        let holder = document.getElementById('reserva-holder');
        holder.innerHTML = day + " de " + monthNames[month] + " de " + year;
        month = padToTwoDigits(month);

        document.getElementById('reservar-libro').addEventListener('click', () => {
            let formData = new FormData();
            formData.append('pttn', 'reservar');
            formData.append('id', l);
            let formattedDate = `${year}-${month}-${day}`;
            formData.append('fecha', formattedDate);
            
            fetch("./mantenimiento/api.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.response === "OK") {
                    console.log(data);
                    holder.innerHTML = "Reserva completada!";
                }
            })
            .catch(error => {
                console.log("[ERROR (API_Request)] ", error);
            });
        });
    }


    function fillCalendar() {
        const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const currentDay = new Date().getDate();

        let reserves = getReserves();
        console.log("Reserves: " + reserves);
        monthYear.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        calendarBody.innerHTML = '';

        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDayOfMonth.getDay()) {
                    row.appendChild(document.createElement('td'));
                } else if (date > daysInMonth) {
                    break;
                } else {
                    const cell = document.createElement('td');

                    if (reserves == null || reserves == undefined) {
                        cell.style.backgroundColor = "#53DD6C";
                    }

                    if (date < currentDay && currentMonth === new Date().getMonth() && currentYear === new Date().getFullYear()){
                        cell.style.backgroundColor = "#9C0D38";
                    }

                    cell.textContent = date++;
                    cell.addEventListener('click', () => {
                        console.log(cell.textContent);
                        cell.style.backgroundColor = "yellow";
                        highlightNextDays(cell.textContent, new Date().getMonth(), new Date().getFullYear());
                    });
                    row.appendChild(cell);
                }
            }
            calendarBody.appendChild(row);
            if (date > daysInMonth) {
                break;
            }
        }
    }

    fillCalendar();
});
