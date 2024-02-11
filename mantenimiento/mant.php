<?php
    # © Joan Aneas
    require_once "db.php";
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $peticion = $_GET["pttn"] ?? null;
?>