
let currentUser;

const fetchCurrentUserID = async () => {
    currentUser = await getID();
    //console.log("a", currentUser);
}

fetchCurrentUserID();

const getXats = async () => {
    const formData = new FormData();
    formData.append('pttn', 'getChats');
    const response = await fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });
    const xats = await response.json();
    const xatListContainer = document.getElementById('xatListContainer');
    xatListContainer.innerHTML = '';
    for (let xat of xats.message) {
        const xatElement = document.createElement('div');

        const xatDivImg = document.createElement('aside');
        const xatDivText = document.createElement('article');

        const xatTitle = document.createElement('p');
        const xatElementImg = document.createElement('img');

        xatElement.addEventListener('click', () => {
            window.location.href = `./xats.php?id=${xat.id_xat}`;
        });

        xatTitle.textContent = xat.nom_xat;
        xatElementImg.src = `./media/icons/qr-code.png`;

        xatDivImg.appendChild(xatElementImg);
        xatDivText.appendChild(xatTitle);

        xatElement.appendChild(xatDivImg);
        xatElement.appendChild(xatDivText);
        xatListContainer.appendChild(xatElement);
    }

    //log(xats);
}
getXats();

const getMessages = async () => {
    const formData = new FormData();
    formData.append('pttn', 'getMessages');
    formData.append('id_xat', new URLSearchParams(window.location.search).get('id'));

    const response = await fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });

    const data = await response.json();
    const messageListContainer = document.getElementById('xatViewContainer');
    messageListContainer.innerHTML = '';

    if (data.response === 'ERROR') {
        const messageElement = document.createElement('div');
        const messageText = document.createElement('p');
        messageText.textContent = "Sense missatges.";
        messageElement.classList.add('xatViewMessage');
        messageElement.appendChild(messageText);
        messageListContainer.appendChild(messageElement);
        return;
    }

    for (let message of data.message) {
        const messageContainer = document.createElement('div');
        const messageElement = document.createElement('div');
        const messageText = document.createElement('p');
        messageText.textContent = message.missatge;

        if (currentUser === message.usuari_id) {
            messageElement.classList.add('xatViewMessage-right');
            messageElement.classList.add('xatViewMessage');
        } else {
            messageElement.classList.add('xatViewMessage-left');
            messageElement.classList.add('xatViewMessage');
        }

        messageContainer.classList.add('xatViewMessageContainer');
        messageElement.appendChild(messageText);
        messageContainer.appendChild(messageElement);
        messageListContainer.appendChild(messageContainer);
    }
};

getMessages();

const sendMessage = async (event) => {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
    const xatMessage = document.getElementById('xatMessage');
    if (xatMessage.value === '') {
        return;
    }

    const formData = new FormData();
    formData.append('pttn', 'sendMessage');
    formData.append('id_xat', new URLSearchParams(window.location.search).get('id'));
    formData.append('missatge', xatMessage.value);

    const response = await fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });

    const messages = await response.json();
    xatMessage.value = '';
    getMessages();
}

setInterval(async () => getXats(), 5000); // Asegúrate de que la función getChats esté definida en otra parte del código
setInterval(async () => getMessages(), 1000);

document.getElementById('xatForm').addEventListener('submit', sendMessage);