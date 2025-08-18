--
-- Database : `Test-tremplin`
--
DROP DATABASE IF EXISTS `Test-tremplin`;
CREATE DATABASE IF NOT EXISTS `Test-tremplin`;
USE `Test-tremplin`;

--
-- REQUEST TABLE
--

CREATE TABLE IF NOT EXISTS `request` (
    `id_request` INT AUTO_INCREMENT,
    `message` TEXT,
    `visit_request` BOOLEAN,
    `callback` BOOLEAN,
    `more_pictures` BOOLEAN,
    PRIMARY KEY (`id_request`),
);

--
-- USER TABLE
--

CREATE TABLE IF NOT EXISTS `user` (
    `id_user` INT AUTO_INCREMENT,
    `lastname` VARCHAR(100),
    `firstname` VARCHAR(100),
    `email` VARCHAR(100),
    `phonenumber` VARCHAR(20) NOT NULL,
    `id_visit` INT,
    `id_message` INT,
    PRIMARY KEY (`id_user`),
    FOREIGN KEY (`id_visit`) REFERENCES visit(`id_visit`) ON DELETE CASCADE,
    FOREIGN KEY (`id_message`) REFERENCES request(`id_message`) ON DELETE CASCADE
);

--
-- VISIT TABLE
--

CREATE TABLE IF NOT EXISTS `visit` (
    `id_visit` INT AUTO_INCREMENT,
    `availability` DATE,
    `hour` TIME,
    `minute` TIME,
    PRIMARY KEY (`id_visit`),
);