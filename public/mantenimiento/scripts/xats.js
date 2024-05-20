
let currentUser;

const fetchCurrentUserID = async () => {
    currentUser = await getID();
    //console.log("a", currentUser);
}

fetchCurrentUserID();

const getXatName = async (id_xat) => {
    const formData = new FormData();
    formData.append('pttn', 'getXatName');
    formData.append('id_xat', id_xat);

    const response = await fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });

    const data = await response.json();
    return data.nom_xat;
}

const urlParams = new URLSearchParams(window.location.search);
const chatId = urlParams.get('id');
// Redirige si 'id' no está presente o está vacío

const titolXat = document.getElementById('titolXat');
if (!chatId) {
    console.log('No se ha especificado un chat.');
    titolXat.textContent = 'Xat no especificat';
} else {
    const titolXat = document.getElementById('titolXat');
    getXatName(chatId).then((nom) => {
        titolXat.textContent = nom;
    });
}

const getImgXat = async (id_xat) => {
    const formData = new FormData();
    formData.append('pttn', 'getImgXat');
    formData.append('id_xat', id_xat);

    const response = await fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });

    const data = await response.json();
    return data.img_xat;
}

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

        getImgXat(xat.id_xat).then((img) => {
            xatElementImg.src = `./media/sistema/xats/${img}`;
        });

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
        const userName = document.createElement('p');
        const messageText = document.createElement('p');
        const userImg = document.createElement('img');

        userImg.src = `../media/sistema/usuaris/${message.pfp}`;
        userImg.width = 30;
        userImg.height = 30;
        userImg.alt = "User Image";
        userImg.classList.add('userImage');

        userName.textContent = message.nickname || message.email; // Assuming `message.nickname` contains the user's nickname
        userName.classList.add('userName');

        messageText.textContent = message.missatge;

        if (currentUser === message.usuari_id) {
            messageElement.classList.add('xatViewMessage-right');
            messageElement.classList.add('xatViewMessage');
            messageElement.appendChild(userName);
            messageElement.appendChild(messageText);
            messageContainer.appendChild(messageElement);
            messageContainer.appendChild(userImg);
        } else {
            messageElement.classList.add('xatViewMessage-left');
            messageElement.classList.add('xatViewMessage');
            messageElement.appendChild(userName);
            messageElement.appendChild(messageText);

            messageContainer.appendChild(userImg);
            messageContainer.appendChild(messageElement);
        }

        messageContainer.classList.add('xatViewMessageContainer');
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
    console.log(messages);
    xatMessage.value = '';
    getMessages();
}

setInterval(async () => getXats(), 30000); // Asegúrate de que la función getChats esté definida en otra parte del código
setInterval(async () => getMessages(), 1000);

document.getElementById('xatForm').addEventListener('submit', sendMessage);