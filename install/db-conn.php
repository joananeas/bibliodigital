<?php
    try {
        $db_server = $_POST['server'];
        $db_user = $_POST['usuari'];  // Cambiado de $_POST['user']
        $db_name = $_POST['nom'];
        $db_pass = isset($_POST['passwd']) ? $_POST['passwd'] : '';  // Corregido el nombre de la variable

        $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
        if ($conn) { echo json_encode(["status" => "success", "message" => "[OK]"]);} 
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }

    $conn -> close();
?>
