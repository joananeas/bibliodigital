SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- !!! PERILL !!!
DROP TABLE IF EXISTS `dib_config`;
DROP TABLE IF EXISTS `dib_reserves`;
DROP TABLE IF EXISTS `dib_usuaris`;
DROP TABLE IF EXISTS `dib_cataleg`;
DROP TABLE IF EXISTS `dib_exemplars`;
-- Es borren les taules i totes les seves dades abans de continuar.

CREATE TABLE `dib_reserves` (
  `reserva` int(5) NOT NULL,
  `nomLlibre` varchar(255) NOT NULL,
  `dataInici` date NOT NULL,
  `dataFi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `dib_reserves` (`reserva`, `nomLlibre`, `dataInici`, `dataFi`) VALUES
(1, 'prueba', '2024-02-01', '2024-02-15'),
(2, 'prueba', '2024-02-01', '2024-02-15'),
(3, 'El libro de la selva', '2024-02-01', '2024-02-01'),
(4, 'Viaje al centro de la Tierra', '2024-02-01', '2024-02-01');

CREATE TABLE `dib_usuaris` (
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `dib_usuaris` (`email`, `passwd`, `rol`) VALUES
('joananeas', '1234', ''),
('admin', '1234', 'admin'),
('admin', '1234', 'admin'),
('usuari', '1234', 'user');

-- Inici Heredades d'epèrgam
CREATE TABLE dib_cataleg (
    ID_CATÀLEG INT,
    ID_BIBLIOTECA INT,
    NUMERO INT,
    ISBN VARCHAR(20),
    CDU VARCHAR(10),
    FORMAT VARCHAR(50),
    TITOL VARCHAR(255),
    AUTOR VARCHAR(100),
    EDITORIAL VARCHAR(100),
    LLOC VARCHAR(100),
    COL·LECCIÓ VARCHAR(100),
    PAÍS VARCHAR(50),
    DATA DATE,
    LLENGUA VARCHAR(50),
    MATERIA VARCHAR(100),
    DESCRIPTOR VARCHAR(100),
    NIVELL VARCHAR(50),
    RESUM TEXT,
    URL VARCHAR(255),
    ADREÇA VARCHAR(255),
    DIMENSIÓ VARCHAR(50),
    VOLÚM VARCHAR(50),
    PÀGINES INT,
    PROC VARCHAR(50),
    CARC VARCHAR(50),
    CAMP_LLIURE VARCHAR(100),
    NPRES INT,
    REC VARCHAR(50),
    ESTAT VARCHAR(50)
);

CREATE TABLE dib_exemplars (
    IDENTIFICADOR INT,
    NUMERO_EXEMPLAR INT,
    SIGNATURA_EXEMPLAR VARCHAR(255),
    SITUACIO VARCHAR(50),
    ESTAT VARCHAR(50)
);
-- Fi heredades d'epèrgam

-- ⚙ CONFIG ⚙ --

CREATE TABLE dib_config (
    INSTALLED BOOLEAN,
    NOM_BIBLIOTECA VARCHAR(255),
    TITOL_WEB VARCHAR(255),
    H1_WEB VARCHAR(255),
    FAVICON VARCHAR(255),
    COLOR_PRINCIPAL CHAR(7),
    COLOR_SECUNDARIO CHAR(7),
    COLOR_TERCIARIO CHAR(7),
    BANNER_STATE BOOLEAN,
    BANNER_TEXT TEXT
);

ALTER TABLE `dib_reserves`
  ADD PRIMARY KEY (`reserva`);

ALTER TABLE `dib_reserves`
  MODIFY `reserva` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

COMMIT;
