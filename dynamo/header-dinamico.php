<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>

<?php 
    session_start();
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if((!isset($_SESSION['email']) && (strpos($url, 'login') === false) && (strpos($url, 'error') === false))) { 
        header("Location: ./login.php"); 
    }
?>