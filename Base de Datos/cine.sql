-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema Cine
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Cine
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Cine` DEFAULT CHARACTER SET utf8mb3 ;
USE `Cine` ;

-- -----------------------------------------------------
-- Table `Cine`.`tarifa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`tarifa` (
  `id_dia` VARCHAR(16) NOT NULL,
  `precio` DECIMAL(10,2) NULL DEFAULT NULL,
  PRIMARY KEY (`id_dia`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`cine`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`cine` (
  `id_cine` INT NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `direccion` VARCHAR(45) NOT NULL,
  `telefono` VARCHAR(45) NULL DEFAULT NULL,
  `tarifa_id_dia` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`id_cine`),
  INDEX `fk_cine_tarifa1_idx` (`tarifa_id_dia` ASC) ,
  CONSTRAINT `fk_cine_tarifa1`
    FOREIGN KEY (`tarifa_id_dia`)
    REFERENCES `Cine`.`tarifa` (`id_dia`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`genero`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`genero` (
  `id_genero` INT NOT NULL,
  `nombre_genero` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_genero`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`pelicula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`pelicula` (
  `id_pelicula` INT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `director` VARCHAR(45) NOT NULL,
  `clasificacion` INT NOT NULL,
  `url_image` VARCHAR(200) NULL DEFAULT NULL,
  `genero_id_genero` INT NOT NULL,
  PRIMARY KEY (`id_pelicula`),
  INDEX `fk_pelicula_genero1_idx` (`genero_id_genero` ASC) ,
  CONSTRAINT `fk_pelicula_genero1`
    FOREIGN KEY (`genero_id_genero`)
    REFERENCES `Cine`.`genero` (`id_genero`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`sala`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`sala` (
  `id_sala` INT NOT NULL,
  `capacidad` INT NOT NULL,
  `cine_id_cine` INT NOT NULL,
  PRIMARY KEY (`id_sala`),
  INDEX `fk_sala_cine1_idx` (`cine_id_cine` ASC) ,
  CONSTRAINT `fk_sala_cine1`
    FOREIGN KEY (`cine_id_cine`)
    REFERENCES `Cine`.`cine` (`id_cine`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`funcion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`funcion` (
  `id_funcion` INT NOT NULL AUTO_INCREMENT,
  `hora` DATETIME NOT NULL,
  `pelicula_id_pelicula` INT NOT NULL,
  `sala_id_sala` INT NOT NULL,
  `boletas_vendidas` INT NOT NULL,
  PRIMARY KEY (`id_funcion`),
  INDEX `fk_funcion_pelicula1_idx` (`pelicula_id_pelicula` ASC) ,
  INDEX `fk_funcion_sala1_idx` (`sala_id_sala` ASC) ,
  CONSTRAINT `fk_funcion_pelicula1`
    FOREIGN KEY (`pelicula_id_pelicula`)
    REFERENCES `Cine`.`pelicula` (`id_pelicula`),
  CONSTRAINT `fk_funcion_sala1`
    FOREIGN KEY (`sala_id_sala`)
    REFERENCES `Cine`.`sala` (`id_sala`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`protagonistas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`protagonistas` (
  `id_actor` INT NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `sexo` TINYINT(1) NOT NULL,
  `url_image` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`id_actor`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`pelicula_has_protagonistas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`pelicula_has_protagonistas` (
  `pelicula_id_pelicula` INT NOT NULL,
  `protagonistas_id_protagonista` INT NOT NULL,
  PRIMARY KEY (`pelicula_id_pelicula`, `protagonistas_id_protagonista`),
  INDEX `fk_pelicula_has_protagonistas_protagonistas1_idx` (`protagonistas_id_protagonista` ASC) ,
  INDEX `fk_pelicula_has_protagonistas_pelicula1_idx` (`pelicula_id_pelicula` ASC) ,
  CONSTRAINT `fk_pelicula_has_protagonistas_pelicula1`
    FOREIGN KEY (`pelicula_id_pelicula`)
    REFERENCES `Cine`.`pelicula` (`id_pelicula`),
  CONSTRAINT `fk_pelicula_has_protagonistas_protagonistas1`
    FOREIGN KEY (`protagonistas_id_protagonista`)
    REFERENCES `Cine`.`protagonistas` (`id_actor`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `Cine`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`usuario` (
  `identificacion` BIGINT NOT NULL auto_increment,
  `nombre` VARCHAR(45) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `correo` VARCHAR(255) NOT NULL,
  `permisos` TINYINT(1) NOT NULL DEFAULT 0,
  `pregunta1` VARCHAR(10) NULL,
  `pregunta2` VARCHAR(10) NULL,
  `pregunta3` VARCHAR(10) NULL,
  PRIMARY KEY (`identificacion`));


-- -----------------------------------------------------
-- Table `Cine`.`transaccion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`transaccion` (
  `id_transaccion` INT NOT NULL,
  `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_identificacion` BIGINT NOT NULL,
  `funcion_id_funcion` INT NOT NULL,
  PRIMARY KEY (`id_transaccion`),
  INDEX `fk_transaccion_usuario1_idx` (`usuario_identificacion` ASC) ,
  INDEX `fk_transaccion_funcion1_idx` (`funcion_id_funcion` ASC) ,
  CONSTRAINT `fk_transaccion_usuario1`
    FOREIGN KEY (`usuario_identificacion`)
    REFERENCES `Cine`.`usuario` (`identificacion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_transaccion_funcion1`
    FOREIGN KEY (`funcion_id_funcion`)
    REFERENCES `Cine`.`funcion` (`id_funcion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;





-- -----------------------------------------------------
-- Inserción de Datos
-- -----------------------------------------------------

USE `Cine`;

-- 1. Insertar Tarifas
-- Basado en los precios de la imagen (Normal 550, Día espectador 350, Festivos 650)
INSERT INTO `tarifa` (`id_dia`, `precio`) VALUES
('Normal', 550.00),
('Espectador', 350.00),
('Festivo', 650.00);

-- 2. Insertar Cines
INSERT INTO `cine` (`id_cine`, `nombre`, `direccion`, `telefono`, `tarifa_id_dia`) VALUES
(1, 'ABC EL SALER', 'Centro Comercial El Saler', '3950592', 'Normal'),
(2, 'ACTEON', 'G.v. Marqués del Turia, 26', '3954084', 'Normal'),
(3, 'ARTIS', 'Russafa, 20', '3940178', 'Normal'),
(4, 'AULA 7', 'G. Sanmartín, 15', '3940415', 'Normal'),
(5, 'CINES NUEVO CENTRO', 'Avd. Pío XII, 2', '3485477', 'Normal');

-- 3. Insertar Géneros
INSERT INTO `genero` (`id_genero`, `nombre_genero`) VALUES
(1, 'Dibujos'),
(2, 'Comedia'),
(3, 'Drama');

-- 4. Insertar Películas
INSERT INTO `pelicula` (`id_pelicula`, `titulo`, `director`, `clasificacion`, `url_image`, `genero_id_genero`) VALUES
(1, 'Pocahontas', 'Mike Gabriel', 0, NULL, 1),
(2, 'Two much', 'Fernando Trueba', 0, NULL, 2),
(3, 'Los puentes de Madison', 'Clint Eastwood', 13, NULL, 3),
(4, 'Smoke', 'Wayne Wang', 0, NULL, 3),
(5, 'Un paseo por las nubes', 'Alfonso Arau', 13, NULL, 3),
(6, 'Carrington', 'Christopher Hampton', 13, NULL, 3),
(7, 'Nueve meses', 'Chris Columbus', 0, NULL, 2),
(8, '¡Vaya Santa Claus!', 'John Pasquin', 0, NULL, 2);

-- 5. Insertar Salas (Inventadas para relacionar las funciones a cada cine)
INSERT INTO `sala` (`id_sala`, `capacidad`, `cine_id_cine`) VALUES
(1, 150, 1), (2, 120, 1), (3, 100, 1), -- Salas de ABC EL SALER
(4, 90, 2),  (5, 80, 2),               -- Salas de ACTEON
(6, 110, 3),                           -- Sala de ARTIS
(7, 100, 4),                           -- Sala de AULA 7
(8, 200, 5), (9, 150, 5), (10, 180, 5);-- Salas de NUEVO CENTRO

-- 6. Insertar Protagonistas (Actores extraídos del recorte)
INSERT INTO `protagonistas` (`id_actor`, `nombre`, `fecha_nacimiento`, `sexo`, `url_image`) VALUES
(1, 'Antonio Banderas', '1960-08-10', 1, NULL),
(2, 'Melanie Griffith', '1957-08-09', 0, NULL),
(3, 'Daryl Hannah', '1960-12-03', 0, NULL),
(4, 'Clint Eastwood', '1930-05-31', 1, NULL),
(5, 'Meryl Streep', '1949-06-22', 0, NULL),
(6, 'William Hurt', '1950-03-20', 1, NULL),
(7, 'Harvey Keitel', '1939-05-13', 1, NULL),
(8, 'Keanu Reeves', '1964-09-02', 1, NULL),
(9, 'Aitana Sanchez Gijon', '1968-11-05', 0, NULL),
(10, 'Emma Thompson', '1959-04-15', 0, NULL),
(11, 'Jonathan Pryce', '1947-06-01', 1, NULL),
(12, 'Hugh Grant', '1960-09-09', 1, NULL),
(13, 'Julianne Moore', '1960-12-03', 0, NULL),
(14, 'Tim Allen', '1953-06-13', 1, NULL),
(15, 'Judge Reinhold', '1957-05-21', 1, NULL),
(16, 'Irene Bedard', '1967-07-22', 0, NULL), -- Voz de Pocahontas
(17, 'Mel Gibson', '1956-01-03', 1, NULL);

-- 7. Relacionar Películas con Protagonistas (Tabla Intermedia)
INSERT INTO `pelicula_has_protagonistas` (`pelicula_id_pelicula`, `protagonistas_id_protagonista`) VALUES
(1, 16),(1, 17),
(2, 1), (2, 2), (2, 3),   -- Two much
(3, 4), (3, 5),           -- Los puentes de Madison
(4, 6), (4, 7),           -- Smoke
(5, 8), (5, 9),           -- Un paseo por las nubes
(6, 10), (6, 11),         -- Carrington
(7, 12), (7, 13),         -- Nueve meses
(8, 14), (8, 15);         -- ¡Vaya Santa Claus!
-- (Nota: Pocahontas al ser dibujos no listaba actores en la cartelera, por lo que no se añade)

-- 8. Insertar Funciones (Horarios extraídos de la imagen convertidos a formato 24h)
INSERT INTO `funcion` (`hora`, `pelicula_id_pelicula`, `sala_id_sala`, `boletas_vendidas`) VALUES
-- ABC EL SALER
('1995-12-25 16:30:00', 1, 1, 0), ('1995-12-25 18:25:00', 1, 1, 0), ('1995-12-25 20:20:00', 1, 1, 0), ('1995-12-25 22:45:00', 1, 1, 0), -- Pocahontas
('1995-12-25 17:00:00', 2, 2, 0), ('1995-12-25 19:40:00', 2, 2, 0), ('1995-12-25 22:50:00', 2, 2, 0),                                 -- Two much
('1995-12-25 16:45:00', 3, 3, 0), ('1995-12-25 19:35:00', 3, 3, 0), ('1995-12-25 22:35:00', 3, 3, 0),                                 -- Los puentes de Madison

-- ACTEON
('1995-12-25 17:15:00', 1, 4, 0), ('1995-12-25 19:45:00', 1, 4, 0),                                 -- Pocahontas
('1995-12-25 22:45:00', 4, 5, 0),                                                                   -- Smoke

-- ARTIS
('1995-12-25 16:45:00', 5, 6, 0), ('1995-12-25 19:20:00', 5, 6, 0), ('1995-12-25 22:45:00', 5, 6, 0), -- Un paseo por las nubes

-- AULA 7
('1995-12-25 16:45:00', 6, 7, 0), ('1995-12-25 19:20:00', 6, 7, 0), ('1995-12-25 22:45:00', 6, 7, 0), -- Carrington

-- CINES NUEVO CENTRO
('1995-12-25 16:30:00', 7, 8, 0), ('1995-12-25 18:30:00', 7, 8, 0), ('1995-12-25 20:30:00', 7, 8, 0), ('1995-12-25 22:40:00', 7, 8, 0), -- Nueve meses
('1995-12-25 12:00:00', 1, 9, 0), ('1995-12-25 16:20:00', 1, 9, 0), ('1995-12-25 18:10:00', 1, 9, 0), ('1995-12-25 19:45:00', 1, 9, 0), ('1995-12-25 22:40:00', 1, 9, 0), -- Pocahontas
('1995-12-25 12:00:00', 8, 10, 0),('1995-12-25 16:30:00', 8, 10, 0),('1995-12-25 18:30:00', 8, 10, 0),('1995-12-25 20:30:00', 8, 10, 0),('1995-12-25 22:40:00', 8, 10, 0); -- ¡Vaya Santa Claus!

INSERT INTO `Cine`.`usuario` (`identificacion`, `nombre`, `password`, `correo`, `permisos`, `pregunta1`, `pregunta2`, `pregunta3`) VALUES 

(20241578221, 'Admin Cine', '12345', 'admin@cine.com', 1, 'Negro', 'Firulais', 'Bogota');