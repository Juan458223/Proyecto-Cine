SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema cine
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `cine` ;
CREATE SCHEMA IF NOT EXISTS `cine` DEFAULT CHARACTER SET utf8 ;
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
  PRIMARY KEY (`id`),
  UNIQUE INDEX `correo_UNIQUE` (`correo`),
  INDEX `fk_usuarios_estados_idx` (`estado_id`),
  CONSTRAINT `fk_usuarios_estados`
    FOREIGN KEY (`estado_id`)
    REFERENCES `cine`.`estados` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
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
  INDEX `fk_token_usuario1_idx` (`usuario_id`),
  INDEX `fk_tokens_tipos_tokens1_idx` (`tipo_id`),
  CONSTRAINT `fk_token_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `cine`.`usuarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tokens_tipos_tokens1`
    FOREIGN KEY (`tipo_id`)
    REFERENCES `cine`.`tipos_tokens` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Data for table `cine`.`estados`
-- -----------------------------------------------------
INSERT INTO `cine`.`estados` (`id`, `nombre`) VALUES (1, 'activo');
INSERT INTO `cine`.`estados` (`id`, `nombre`) VALUES (2, 'pendiente');
INSERT INTO `cine`.`estados` (`id`, `nombre`) VALUES (3, 'bloqueado');

-- -----------------------------------------------------
-- Data for table `cine`.`tipos_tokens`
-- -----------------------------------------------------
INSERT INTO `cine`.`tipos_tokens` (`id`, `nombre`) VALUES (1, 'reset_password');
INSERT INTO `cine`.`tipos_tokens` (`id`, `nombre`) VALUES (2, 'validate_user');
INSERT INTO `cine`.`tipos_tokens` (`id`, `nombre`) VALUES (3, 'register_user');

-- -----------------------------------------------------
-- Data for table `cine`.`usuarios`
-- -----------------------------------------------------
-- Admin: 123456789
INSERT INTO `cine`.`usuarios` (`nombre`, `password`, `correo`, `estado_id`) 
VALUES ('Admin', '$2y$10$7v/f6M3w3qI.G5p1v5H/u.3mJ5uVfXz/xS7vQZ.pYv8yX7M6x9Y3S', '2305juanda@gmail.com', 1);

-- Juan: 230506
INSERT INTO `cine`.`usuarios` (`nombre`, `password`, `correo`, `estado_id`) 
VALUES ('Juan', '$2y$10$f1p9j.v8H8v7H7v7H7v7He7v7H7v7H7v7H7v7H7v7H7v7H7v7H7v7', 'juansitoperon5t@gmail.com', 1);

-- -----------------------------------------------------
-- procedure sp_validar_token
-- -----------------------------------------------------
DELIMITER $$
USE `cine`$$
DROP PROCEDURE IF EXISTS `sp_validar_token`$$
CREATE PROCEDURE `sp_validar_token`(
  IN  p_correo      VARCHAR(255),
  IN  p_token_valor VARCHAR(255),
  IN  p_tipo_nombre VARCHAR(45),
  OUT p_resultado   VARCHAR(20)
)
BEGIN
  DECLARE v_fecha_c DATETIME;
  DECLARE v_u_id INT;
  DECLARE v_tipo_id INT;

  -- Obtener el ID del tipo de token
  SELECT id INTO v_tipo_id FROM tipos_tokens WHERE nombre = p_tipo_nombre;

  -- Buscar el token y el ID de usuario
  SELECT t.fecha_c, t.usuario_id INTO v_fecha_c, v_u_id
  FROM tokens t
  INNER JOIN usuarios u ON u.id = t.usuario_id
  WHERE u.correo = p_correo 
    AND t.token_valor = p_token_valor 
    AND t.tipo_id = v_tipo_id
  LIMIT 1;

  IF v_fecha_c IS NULL THEN
    SET p_resultado = 'token no valido';
  ELSEIF NOW() > DATE_ADD(v_fecha_c, INTERVAL 2 MINUTE) THEN
    SET p_resultado = 'token expirado';
  ELSE
    SET p_resultado = 'token valido';
    -- Activar usuario automáticamente si es token de registro
    IF p_tipo_nombre = 'register_user' THEN
      UPDATE usuarios SET estado_id = 1 WHERE id = v_u_id;
    END IF;
  END IF;
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
