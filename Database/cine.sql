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
-- Table `cine`.`tipos_tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`tipos_tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB;

INSERT INTO `tipos_tokens` (`id`, `nombre`) VALUES (1, 'register_user'), (2, 'validate_user'), (3, 'reset_password');

INSERT INTO `estados` (`id`, `nombre`) VALUES (1, 'Activado'), (2, 'Pendiente'), (3, 'Desactivado');

-- -----------------------------------------------------
-- Table `cine`.`permisos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`permisos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB;

INSERT INTO `permisos` (`id`, `nombre`) VALUES (1, 'Administrador'), (2, 'Usuario');

-- -----------------------------------------------------
-- Table `cine`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `correo` VARCHAR(255) NOT NULL,
  `estado_id` INT NOT NULL,
  `permisos_id` INT NOT NULL DEFAULT 2,
  `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `correo_UNIQUE` (`correo`),
  INDEX `fk_usuarios_estados_idx` (`estado_id`),
  INDEX `fk_usuarios_permisos_idx` (`permisos_id`),
  CONSTRAINT `fk_usuarios_estados`
    FOREIGN KEY (`estado_id`)
    REFERENCES `cine`.`estados` (`id`),
  CONSTRAINT `fk_usuarios_permisos`
    FOREIGN KEY (`permisos_id`)
    REFERENCES `cine`.`permisos` (`id`)
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
-- Table `cine`.`protagonista`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`protagonista` (
  `id_actor` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_actor`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`pelicula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`pelicula` (
  `id_pelicula` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NOT NULL,
  `director` VARCHAR(45) NOT NULL,
  `clasificacion` VARCHAR(45) NOT NULL,
  `url_image` VARCHAR(255) NULL DEFAULT NULL,
  `genero_id_genero` INT NOT NULL,
  PRIMARY KEY (`id_pelicula`),
  CONSTRAINT `fk_pelicula_genero1` FOREIGN KEY (`genero_id_genero`) REFERENCES `cine`.`genero` (`id_genero`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `cine`.`pelicula_has_protagonistas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cine`.`pelicula_has_protagonistas` (
  `pelicula_id_pelicula` INT NOT NULL,
  `protagonistas_id_protagonista` INT NOT NULL,
  PRIMARY KEY (`pelicula_id_pelicula`, `protagonistas_id_protagonista`),
  CONSTRAINT `fk_pelicula_has_protagonistas_pelicula1`
    FOREIGN KEY (`pelicula_id_pelicula`)
    REFERENCES `cine`.`pelicula` (`id_pelicula`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_pelicula_has_protagonistas_protagonista1`
    FOREIGN KEY (`protagonistas_id_protagonista`)
    REFERENCES `cine`.`protagonista` (`id_actor`)
    ON DELETE CASCADE)
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
  PRIMARY KEY (`id_funcion`),
  CONSTRAINT `fk_funcion_pelicula` FOREIGN KEY (`pelicula_id_pelicula`) REFERENCES `cine`.`pelicula` (`id_pelicula`),
  CONSTRAINT `fk_funcion_sala` FOREIGN KEY (`sala_id_sala`) REFERENCES `cine`.`sala` (`id_sala`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- DATOS INICIALES Y PRUEBAS
-- -----------------------------------------------------
INSERT INTO `tarifas_tipos_dia` (`id`, `nombre_dia`) VALUES (1, 'Normal'), (2, 'Espectador'), (3, 'Festivo'), (4, 'Víspera');
INSERT INTO `tarifas_categorias_publico` (`id`, `nombre_categoria`) VALUES (1, 'General'), (2, 'Estudiante'), (3, 'Jubilado'), (4, 'Niño');

INSERT INTO `cine` (`id_cine`, `nombre`, `calle`, `numero`, `telefono`) VALUES
(1, 'CinePlanet Norte', 'Av. Siempre Viva', '123', '5550101'),
(2, 'CineCity Centro', 'Calle Falsa', '456', '5550202'),
(3, 'Luxor Cinema', 'Avenida del Sol', '789', '5550303'),
(4, 'Star Cine', 'Camino Real', '101', '5550404');

INSERT INTO `genero` (`id_genero`, `nombre_genero`) VALUES 
(1, 'Acción'), (2, 'Drama'), (3, 'Comedia'), (4, 'Ciencia Ficción'), (5, 'Terror'), (6, 'Aventura');

INSERT INTO `pelicula` (`id_pelicula`, `titulo`, `director`, `clasificacion`, `url_image`, `genero_id_genero`) VALUES
(1, 'Inception', 'Christopher Nolan', '13', 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkcsI9b2i2N069a3.jpg', 4),
(2, 'The Dark Knight', 'Christopher Nolan', '13', 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haTaboPH.jpg', 1),
(3, 'Interstellar', 'Christopher Nolan', '13', 'https://image.tmdb.org/t/p/w500/rAiYTfPXWdZXQn2V976RkHlS00q.jpg', 4),
(4, 'Joker', 'Todd Phillips', '18', 'https://image.tmdb.org/t/p/w500/udDclJoHjmabe8EkF7W4Vez85V2.jpg', 2),
(5, 'The Conjuring', 'James Wan', '18', 'https://image.tmdb.org/t/p/w500/wou2pG6D8cR7Hk3L1Z4K5m1f9a0.jpg', 5),
(6, 'Gladiator', 'Ridley Scott', '13', 'https://image.tmdb.org/t/p/w500/ty8xC3v12dYfJg2L2uGv842rX2n.jpg', 1),
(7, 'Pulp Fiction', 'Quentin Tarantino', '18', 'https://image.tmdb.org/t/p/w500/d5iilQnNnLh2tHk9wS7ZJ9k8.jpg', 2),
(8, 'The Matrix', 'Wachowski', '13', 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9Gkd4EbXUKj7d.jpg', 4),
(9, 'Avengers: Endgame', 'Russo Brothers', '13', 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg', 1),
(10, 'Parasite', 'Bong Joon-ho', '18', 'https://image.tmdb.org/t/p/w500/7Ii3yqX9oYf3W2Sg47t8lF1f4.jpg', 2);

INSERT INTO `protagonista` (`id_actor`, `nombre`) VALUES 
(1, 'Leonardo DiCaprio'), (2, 'Christian Bale'), (3, 'Heath Ledger'), (4, 'Matthew McConaughey'), 
(5, 'Joaquin Phoenix'), (6, 'Vera Farmiga'), (7, 'Russell Crowe'), (8, 'John Travolta'), (9, 'Keanu Reeves'), (10, 'Robert Downey Jr.');

INSERT INTO `pelicula_has_protagonistas` (`pelicula_id_pelicula`, `protagonistas_id_protagonista`) VALUES 
(1, 1), (2, 2), (2, 3), (3, 4), (4, 5), (5, 6), (6, 7), (7, 8), (8, 9), (9, 10);

INSERT INTO `sala` (`capacidad`, `cine_id_cine`) VALUES (150, 1), (120, 1), (90, 2), (110, 3), (200, 4);

INSERT INTO `funcion` (`fecha_hora`, `pelicula_id_pelicula`, `sala_id_sala`) VALUES
('2026-05-26 16:00:00', 3, 1), ('2026-05-26 18:00:00', 3, 1), ('2026-05-26 20:00:00', 3, 1),
('2026-05-26 22:00:00', 3, 1), ('2026-05-27 16:00:00', 3, 1), ('2026-05-27 18:00:00', 3, 1),
('2026-05-26 17:00:00', 4, 2), ('2026-05-26 19:30:00', 4, 2), ('2026-05-26 22:00:00', 4, 2),
('2026-05-27 17:00:00', 5, 3), ('2026-05-27 20:00:00', 5, 3), ('2026-05-27 22:30:00', 5, 3);

INSERT INTO `usuarios` (`nombre`, `password`, `correo`, `estado_id`, `permisos_id`) VALUES ('Admin', '$2y$10$tZk5nZ5O8.w1zP3rD1Z5l.t3v2n5l1U/r1yX7M6x9Y3S', '2305juanda@gmail.com', 1, 1);

DELIMITER $$
CREATE PROCEDURE `sp_validar_token`(IN p_correo VARCHAR(255), IN p_token_valor VARCHAR(255), IN p_tipo_nombre VARCHAR(45), OUT p_resultado VARCHAR(20))
BEGIN
  DECLARE v_fecha_c DATETIME;
  DECLARE v_u_id INT;
  DECLARE v_tipo_id INT;
  SELECT id INTO v_tipo_id FROM tipos_tokens WHERE nombre = p_tipo_nombre;
  SELECT t.fecha_c, t.usuario_id INTO v_fecha_c, v_u_id FROM tokens t INNER JOIN usuarios u ON u.id = t.usuario_id WHERE u.correo = p_correo AND t.token_valor = p_token_valor AND t.tipo_id = v_tipo_id LIMIT 1;
  IF v_fecha_c IS NULL THEN SET p_resultado = 'token no valido';
  ELSEIF NOW() > DATE_ADD(v_fecha_c, INTERVAL 2 MINUTE) THEN SET p_resultado = 'token expirado';
  ELSE SET p_resultado = 'token valido';
    IF p_tipo_nombre = 'register_user' THEN UPDATE usuarios SET estado_id = 1 WHERE id = v_u_id; END IF;
  END IF;
END$$
DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
