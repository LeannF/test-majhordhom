--
-- Database : `Test-majhordhom`
--
DROP DATABASE IF EXISTS `Test-majhordhom`;
CREATE DATABASE IF NOT EXISTS `Test-majhordhom`;
USE `Test-majhordhom`;

-- DROP TABLE IN ORDER

DROP TABLE IF EXISTS `form`;

--
-- FORM TABLE
--

CREATE TABLE IF NOT EXISTS `form`(
    `id_form` INT AUTO_INCREMENT,
    `lastname` VARCHAR(100) NOT NULL,
    `firstname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phonenumber` VARCHAR(20) NOT NULL,
    `content` TEXT,
    `visit_requested` BOOLEAN,
    `callback_requested` BOOLEAN,
    `more_pictures_requested` BOOLEAN,
    `availability_date` DATE,
    `availability_hour` TIME,
    `availability_minute` TIME,
    PRIMARY KEY (`id_form`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `phonenumber` (`phonenumber`)   
);
