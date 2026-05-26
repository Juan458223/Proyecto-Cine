-- MySQL Workbench Forward Engineering
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

DROP SCHEMA IF EXISTS `cine` ;
CREATE SCHEMA IF NOT EXISTS `cine` DEFAULT CHARACTER SET utf8mb4 ;
USE `cine` ;

-- -----------------------------------------------------
-- Table `cine`.`estados`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`estados` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `correo` VARCHAR(255) NOT NULL,
  `estado_id` INT NOT NULL,
  `permisos` INT(1) NOT NULL default 0,
  `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `correo_UNIQUE` (`correo`),
  INDEX `fk_usuarios_estados_idx` (`estado_id`),
  CONSTRAINT `fk_usuarios_estados`
    FOREIGN KEY (`estado_id`)
    REFERENCES `cine`.`estados` (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`tipos_tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`tipos_tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`tokens` (
  `idtoken` INT(11) NOT NULL AUTO_INCREMENT,
  `token_valor` VARCHAR(255) NOT NULL,
  `usuario_id` INT(11) NOT NULL,
  `tipo_id` INT NOT NULL,
  `fecha_c` DATETIME NOT NULL,
  PRIMARY KEY (`idtoken`),
  CONSTRAINT `fk_token_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `cine`.`usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tokens_tipos_tokens1` FOREIGN KEY (`tipo_id`) REFERENCES `cine`.`tipos_tokens` (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`cine`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`cine` (
  `id_cine` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `calle` VARCHAR(100) NOT NULL,
  `numero` VARCHAR(10) NOT NULL,
  `telefono` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id_cine`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`genero`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`genero` (
  `id_genero` INT NOT NULL AUTO_INCREMENT,
  `nombre_genero` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_genero`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`pelicula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`pelicula` (
  `id_pelicula` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NOT NULL,
  `director` VARCHAR(45) NOT NULL,
  `clasificacion` VARCHAR(45) NOT NULL,
  `url_image` VARCHAR(200) NULL DEFAULT NULL,
  `genero_id_genero` INT NOT NULL,
  PRIMARY KEY (`id_pelicula`),
  CONSTRAINT `fk_pelicula_genero1` FOREIGN KEY (`genero_id_genero`) REFERENCES `cine`.`genero` (`id_genero`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`sala`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`sala` (
  `id_sala` INT NOT NULL AUTO_INCREMENT,
  `numero_sala` INT NOT NULL,
  `capacidad` INT NOT NULL,
  `cine_id_cine` INT NOT NULL,
  PRIMARY KEY (`id_sala`),
  CONSTRAINT `fk_sala_cine1` FOREIGN KEY (`cine_id_cine`) REFERENCES `cine`.`cine` (`id_cine`))
ENGINE = InnoDB;

-- Trigger numeración automática
DELIMITER $$
CREATE TRIGGER `tg_numero_sala_bi` BEFORE INSERT ON `sala`
FOR EACH ROW
BEGIN
    SET NEW.numero_sala = (SELECT IFNULL(MAX(numero_sala), 0) + 1 FROM sala WHERE cine_id_cine = NEW.cine_id_cine);
END$$
DELIMITER ;

-- -----------------------------------------------------
-- TABLAS DE TARIFAS
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`tarifas_tipos_dia` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_dia` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `cine`.`tarifas_categorias_publico` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_categoria` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `cine`.`tarifa` (
  `id_tarifa` INT NOT NULL AUTO_INCREMENT,
  `cine_id_cine` INT NOT NULL,
  `tipo_dia_id` INT NOT NULL,
  `categoria_publico_id` INT NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_tarifa`),
  CONSTRAINT `fk_tarifa_cine` FOREIGN KEY (`cine_id_cine`) REFERENCES `cine`.`cine` (`id_cine`),
  CONSTRAINT `fk_tarifa_dia` FOREIGN KEY (`tipo_dia_id`) REFERENCES `cine`.`tarifas_tipos_dia` (`id`),
  CONSTRAINT `fk_tarifa_publico` FOREIGN KEY (`categoria_publico_id`) REFERENCES `cine`.`tarifas_categorias_publico` (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`funcion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`funcion` (
  `id_funcion` INT NOT NULL AUTO_INCREMENT,
  `fecha_hora` DATETIME NOT NULL,
  `pelicula_id_pelicula` INT NOT NULL,
  `sala_id_sala` INT NOT NULL,
  `boletas_vendidas` INT NOT NULL default 0,
  `tipo_dia_id` INT NOT NULL,
  PRIMARY KEY (`id_funcion`),
  CONSTRAINT `fk_funcion_pelicula` FOREIGN KEY (`pelicula_id_pelicula`) REFERENCES `cine`.`pelicula` (`id_pelicula`),
  CONSTRAINT `fk_funcion_sala` FOREIGN KEY (`sala_id_sala`) REFERENCES `cine`.`sala` (`id_sala`),
  CONSTRAINT `fk_funcion_tipo_dia` FOREIGN KEY (`tipo_dia_id`) REFERENCES `cine`.`tarifas_tipos_dia` (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- DATOS INICIALES Y PRUEBAS
-- -----------------------------------------------------
INSERT INTO `tarifas_tipos_dia` (`id`, `nombre_dia`) VALUES (1, 'Normal'), (2, 'Espectador'), (3, 'Festivo'), (4, 'Víspera');
INSERT INTO `tarifas_categorias_publico` (`id`, `nombre_categoria`) VALUES (1, 'General'), (2, 'Estudiante'), (3, 'Jubilado'), (4, 'Niño');

INSERT INTO `cine` (`id_cine`, `nombre`, `calle`, `numero`, `telefono`) VALUES
(1, 'ABC El Saler', 'Centro Comercial El Saler', 'S/N', '3950592'),
(2, 'Acteon', 'G.v. Marques del Turia', '26', '3954084'),
(3, 'Artis', 'Russafa', '20', '3940178');

-- Tarifas extendidas (Ejemplo Cine 1)
INSERT INTO `tarifa` (`cine_id_cine`, `tipo_dia_id`, `categoria_publico_id`, `precio`) VALUES
(1, 1, 1, 550.00), (1, 1, 2, 450.00), (1, 1, 3, 400.00), (1, 1, 4, 350.00),
(1, 2, 1, 350.00), (1, 2, 2, 350.00), (1, 2, 3, 300.00), (1, 2, 4, 300.00),
(1, 3, 1, 650.00), (1, 3, 2, 600.00), (1, 3, 3, 550.00), (1, 3, 4, 500.00),
(1, 4, 1, 600.00), (1, 4, 2, 500.00), (1, 4, 3, 450.00), (1, 4, 4, 400.00);

-- Géneros y Películas (Todo público corregido)
INSERT INTO `genero` (`id_genero`, `nombre_genero`) VALUES (1, 'Dibujos'), (2, 'Comedia'), (3, 'Drama'), (4, 'Acción');
INSERT INTO `pelicula` (`id_pelicula`, `titulo`, `director`, `clasificacion`, `url_image`, `genero_id_genero`) VALUES
(1, 'Pocahontas', 'Mike Gabriel', 'Todo público', 'https://m.media-amazon.com/images/I/515MY8H3FDL._AC_UF1000,1000_QL80_.jpg', 1),
(2, 'Two much', 'Fernando Trueba', 'Todo público', 'https://pics.filmaffinity.com/Two_Much-750844534-large.jpg', 2),
(3, 'Los puentes de Madison', 'Clint Eastwood', '+13', 'https://wmagazin.com/wp-content/uploads/2025/11/cine-club-literario-lospuentesdemadison-cartel-WMagazin-scaled-e1763019346105.jpg', 3),
(4, 'The Dark Knight', 'Christopher Nolan', '+13', 'https://m.media-amazon.com/images/S/pv-target-images/5732ef430839ef69ef339397686566838a1658b549302636254707f15456f966.jpg', 4),
(5, 'Inception', 'Christopher Nolan', '+13', 'https://m.media-amazon.com/images/I/912AErFSBHL._AC_UF1000,1000_QL80_.jpg', 4),
(6, 'The Conjuring', 'James Wan', '+18', 'https://m.media-amazon.com/images/S/pv-target-images/44477d9c614529ec97b2649a5b3a3d5f57a3e75e92556a3e6f987f17666f7f32.jpg', 5);

-- Salas
INSERT INTO `sala` (`capacidad`, `cine_id_cine`) VALUES (150, 1), (120, 1), (100, 1), (90, 2), (80, 2), (110, 3);

-- Funciones (Múltiples para paginación)
INSERT INTO `funcion` (`fecha_hora`, `pelicula_id_pelicula`, `sala_id_sala`, `tipo_dia_id`) VALUES
('2026-05-25 16:00:00', 1, 1, 1), ('2026-05-25 18:00:00', 1, 1, 1), ('2026-05-25 20:00:00', 1, 1, 1),
('2026-05-25 16:30:00', 2, 2, 1), ('2026-05-25 19:00:00', 2, 2, 1), ('2026-05-25 21:30:00', 2, 2, 1),
('2026-05-26 17:00:00', 3, 3, 1), ('2026-05-26 19:30:00', 3, 3, 1), ('2026-05-26 22:00:00', 3, 3, 1),
('2026-05-27 15:00:00', 4, 4, 2), ('2026-05-27 18:00:00', 4, 4, 2), ('2026-05-27 21:00:00', 4, 4, 2),
('2026-05-28 16:00:00', 5, 5, 1), ('2026-05-28 19:00:00', 5, 5, 1), ('2026-05-28 22:00:00', 5, 5, 1);

-- Usuarios
INSERT INTO `estados` (`id`, `nombre`) VALUES (1, 'activo'), (2, 'pendiente'), (3, 'bloqueado');
INSERT INTO `usuarios` (`nombre`, `password`, `correo`, `estado_id`, `permisos`) VALUES ('Admin', '$2y$10$7v/f6M3w3qI.G5p1v5H/u.3mJ5uVfXz/xS7vQZ.pYv8yX7M6x9Y3S', '2305juanda@gmail.com', 1, 1);
