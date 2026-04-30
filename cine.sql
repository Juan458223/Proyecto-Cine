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
  INDEX `fk_cine_tarifa1_idx` (`tarifa_id_dia` ASC) VISIBLE,
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
  INDEX `fk_pelicula_genero1_idx` (`genero_id_genero` ASC) VISIBLE,
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
  INDEX `fk_sala_cine1_idx` (`cine_id_cine` ASC) VISIBLE,
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
  INDEX `fk_funcion_pelicula1_idx` (`pelicula_id_pelicula` ASC) VISIBLE,
  INDEX `fk_funcion_sala1_idx` (`sala_id_sala` ASC) VISIBLE,
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
  INDEX `fk_pelicula_has_protagonistas_protagonistas1_idx` (`protagonistas_id_protagonista` ASC) VISIBLE,
  INDEX `fk_pelicula_has_protagonistas_pelicula1_idx` (`pelicula_id_pelicula` ASC) VISIBLE,
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
  `identificacion` INT NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `correo` VARCHAR(255) NOT NULL,
  `permisos` TINYINT(1) NOT NULL DEFAULT 0,
  `pregunta1` VARCHAR(10) NULL,
  `pregunta 2` VARCHAR(10) NULL,
  `pregunta 3` VARCHAR(10) NULL,
  PRIMARY KEY (`identificacion`));


-- -----------------------------------------------------
-- Table `Cine`.`transaccion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Cine`.`transaccion` (
  `id_transaccion` INT NOT NULL,
  `fecha` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_identificacion` INT NOT NULL,
  `funcion_id_funcion` INT NOT NULL,
  PRIMARY KEY (`id_transaccion`),
  INDEX `fk_transaccion_usuario1_idx` (`usuario_identificacion` ASC) VISIBLE,
  INDEX `fk_transaccion_funcion1_idx` (`funcion_id_funcion` ASC) VISIBLE,
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
