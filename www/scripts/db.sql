--
-- Database : `Test-majhordhom`
--
DROP DATABASE IF EXISTS `Test-majhordhom`;
CREATE DATABASE IF NOT EXISTS `Test-majhordhom`;
USE `Test-majhordhom`;

DROP TABLE IF EXISTS `form`;

--
-- FORM TABLE
--

CREATE TABLE IF NOT EXISTS `form`(
    `id_form` INT AUTO_INCREMENT,
    `lastname` VARCHAR(100) NOT NULL,
    `firstname` VARCHAR(100) NOT NULL,
    `mail` VARCHAR(100) NOT NULL,
    `phonenumber` VARCHAR(20) NOT NULL,
    `content` TEXT,
    `visit_requested` BOOLEAN DEFAULT 0,
    `callback_requested` BOOLEAN DEFAULT 0,
    `more_pictures_requested` BOOLEAN DEFAULT 0,
    `availability_date` TEXT NOT NULL,
    PRIMARY KEY (`id_form`),
    UNIQUE KEY `mail` (`mail`),
    UNIQUE KEY `phonenumber` (`phonenumber`)
);