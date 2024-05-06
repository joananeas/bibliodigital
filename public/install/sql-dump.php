<?php
    $dump = 
    '
    -- phpMyAdmin SQL Dump
    -- version 5.2.1
    -- https://www.phpmyadmin.net/
    --
    -- Servidor: 127.0.0.1
    -- Tiempo de generación: 06-11-2023 a las 21:38:24
    -- Versión del servidor: 10.4.28-MariaDB
    -- Versión de PHP: 8.2.4
    
    SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
    START TRANSACTION;
    SET time_zone = "+00:00";
    
    
    /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
    /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
    /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
    /*!40101 SET NAMES utf8mb4 */;
    
    --
    -- Base de datos: "bibliodigital"
    --
    
    -- --------------------------------------------------------
    
    --
    -- Estructura de tabla para la tabla "biblioteca"
    --
    
    CREATE TABLE "biblioteca" (
      "usuari" varchar(50) NOT NULL,
      "contrasenya" varchar(50) NOT NULL,
      "banTempIP" varchar(15) NOT NULL,
      "banPerm" tinyint(1) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
    -- --------------------------------------------------------
    
    --
    -- Estructura de tabla para la tabla "llibres"
    --
    
    CREATE TABLE "llibres" (
      "idLlibre" int(11) NOT NULL,
      "nomLlibre" varchar(50) NOT NULL,
      "unitatsDisp" int(11) NOT NULL,
      "topics" varchar(100) NOT NULL,
      "descripcio" varchar(500) NOT NULL,
      "qrLlibre" int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
    --
    -- Volcado de datos para la tabla "llibres"
    --
    
    INSERT INTO "llibres" ("idLlibre", "nomLlibre", "unitatsDisp", "topics", "descripcio", "qrLlibre") VALUES
    (1, "PruebaLibro", 1, "misteri", "aquest llibre és molt interessant y me estoy alargandooo sjdashdhausaushdausuidaehuidahuihauidashuifehuirfshuihuiaesfuhauisfaesdhudfshufshuidsdfshuidfhusdfhudfashudfashu", 0),
    (2, "Libro 2", 1, "misteri", "prueba prueba prueba...", 0);
    
    -- --------------------------------------------------------
    
    --
    -- Estructura de tabla para la tabla "prestecs"
    --
    
    CREATE TABLE "prestecs" (
      "usuariPrestec" varchar(50) NOT NULL,
      "llibrePrestec" varchar(50) NOT NULL,
      "dataIniciPrestec" date NOT NULL,
      "dataFinalPrestec" date NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    
    --
    -- Índices para tablas volcadas
    --
    
    --
    -- Índices de la tabla "llibres"
    --
    ALTER TABLE "llibres"
      ADD PRIMARY KEY ("idLlibre");
    
    --
    -- AUTO_INCREMENT de las tablas volcadas
    --
    
    --
    -- AUTO_INCREMENT de la tabla "llibres"
    --
    ALTER TABLE "llibres"
      MODIFY "idLlibre" int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
    COMMIT;
    
    /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
    /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
    /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
    
    '
    ;
?>