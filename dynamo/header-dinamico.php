<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <title id="tituloTab"></title><!--OK--><!--Debe de ser dinámico-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Staatliches&display=swap" rel="stylesheet"> 
    <link id="favicon" rel="icon" type="image/png">
    <style>
        /* EVITA FUOC */
        @import url('https://fonts.googleapis.com/css2?family=Inter&family=Staatliches&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            font-display: swap;
        }
    </style>
    <?php 
    session_start();
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if((!isset($_SESSION['email']) && (strpos($url, 'login') === false) && (strpos($url, 'error') === false))) { 
            header("Location: ./login.php"); 
        }

        if (strpos($url, 'admin') !== false) {
            echo '<script src="../mantenimiento/scripts/colores.js"></script>';
        } else {
            echo '<script src="mantenimiento/scripts/colores.js"></script>';
        }
    ?>
</head>

<!--
    ___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (c) Joan Aneas

-->