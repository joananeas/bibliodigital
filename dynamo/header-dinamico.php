<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php  
    $espPagina = array();

    if (isset($estilos)) {
        foreach ($estilos as $value) {
            echo "<link rel='stylesheet' type='text/css' href='./estilos/$value'>
            ";
        }
    }
    // Si se encuentran fuera del directorio raíz
    if (isset($estilosFueraRango)) {
        foreach ($estilosFueraRango as $value) {
            echo "<link rel='stylesheet' type='text/css' href='$value'>
            ";
        }
    }
?>
    <title><?php echo $titolWeb; ?></title><!--OK--><!--Debe de ser dinámico-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Staatliches&display=swap" rel="stylesheet"> 
</head>
<!-- 
    $estilos = [""];
    $titolWeb = ;
-->