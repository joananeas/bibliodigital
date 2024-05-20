<?php
echo "Hello World!";

function peticionSQL()
{
    require_once "./mantenimiento/db.php";

    $key = base64_decode(DB_SERVER_KEY);
    $iv = base64_decode(DB_SERVER_IV);
    $host = openssl_decrypt(DB_HOST, 'aes-256-cbc', $key, 0, $iv);
    $user = openssl_decrypt(DB_USER, 'aes-256-cbc', $key, 0, $iv);
    $password = openssl_decrypt(DB_PASSWORD, 'aes-256-cbc', $key, 0, $iv);
    $db = openssl_decrypt(DB_NAME, 'aes-256-cbc', $key, 0, $iv);
    $conn = new mysqli($host, $user, $password, $db);

    return $conn;
}

function getChats() {
    $conn = peticionSQL();
    $conn->set_charset("utf8");
    $sql = "SELECT * FROM `dib_xats`";
    $result = $conn->query($sql);
    var_dump($result->fetch_all());
    $conn->close();
}

getChats();