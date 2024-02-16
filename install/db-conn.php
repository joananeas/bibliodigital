<?php
    # © Joan Aneas
    $db_server = $_POST['host'] ?? "localhost";
    $db_user = $_POST['user'] ?? "root";  
    $db_name = $_POST['db'] ?? "bibliodigital";
    $db_pass = $_POST['passwd'] ?? "";

    $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
    
    function installation($db_server, $db_user, $db_name, $db_pass){
        
    }

    if ($conn->connect_error) {
        echo json_encode(array
        ('status' => 'error',
         'message' => 'Error de connexió a la base de dades: ' . $conn->connect_error,
         'data' => array('host' => $db_server, 'user' => $db_user, 'db' => $db_name, 'passwd' => $db_pass)));
    }
    else {
        echo json_encode(array('status' => 'ok', 'message' => 'Connexió a la base de dades correcta'));
    }

    $conn -> close();
