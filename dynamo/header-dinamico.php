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
                if (strpos($url, 'admin') !== false) header("Location: ../login.php"); 
                else header("Location: ./login.php"); 
                exit();
            }

            if (strpos($url, 'admin') !== false) {
                echo '<script src="../mantenimiento/scripts/colores.js"></script>';
            } else {
                echo '<script src="mantenimiento/scripts/colores.js"></script>';
            }
            
            if (phpversion() < 8) {
                if (strpos($url, 'admin') !== false) header("Location: ../error.php?error=0008"); 
                else header("Location: ./error.php?error=0008");
            }
            
            const ROL_LVL = [
                'admin' => 4,
                'bibliotecari' => 3,
                'user' => 2,
                'guest' => 1,
                null => 0
            ];
            
            const ALLOWED_ROLES = [
                'admin',
                'bibliotecari',
                'user',
                'guest',
                null
            ];

            const PAGES_ROL_LVL = [
                'admin' => 4,
                'mantenimiento' => 4,
                'cuenta' => 2,
                'reservas' => 2,
                'index' => 1,
                'error' => 1,
                'libro' => 1,
                'login' => 0 || null,
            ];
            
            # echo $_COOKIE['rol'];
            # echo ROL_LVL[$_COOKIE['rol']];
            error_reporting(0);

            foreach (PAGES_ROL_LVL as $page => $level) {
                if (strpos($url, $page) !== false && ROL_LVL[$_COOKIE['rol']] < $level){
                    if (strpos($url, 'admin') !== false) header("Location: ../error.php?error=403"); 
                    else header("Location: ./error.php?error=403");
                    exit();
                }
            }
            
    ?>
</head>

<!--
    ___ ___ ___ _    ___ ___  
    |   \_ _| _ ) |  |_ _/ _ \ 
    | |) | || _ \ |__ | | (_) |
    |___/___|___/____|___\___/  (c) Joan Aneas

-->