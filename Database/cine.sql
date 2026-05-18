  -- MySQL Workbench Forward Engineering

  SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
  SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
  SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


  DROP SCHEMA IF EXISTS `cine` ;

  -- -----------------------------------------------------
  -- Schema cine
  -- -----------------------------------------------------
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
  -- Table `cine`.`cine`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`cine` (
    `id_cine` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(45) NOT NULL,
    `direccion` VARCHAR(45) NOT NULL,
    `telefono` VARCHAR(45) NULL DEFAULT NULL,
    PRIMARY KEY (`id_cine`))
  ENGINE = InnoDB
  AUTO_INCREMENT = 6
  DEFAULT CHARACTER SET = utf8mb4;


  -- -----------------------------------------------------
  -- Table `cine`.`genero`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`genero` (
    `id_genero` INT NOT NULL AUTO_INCREMENT,
    `nombre_genero` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id_genero`))
  ENGINE = InnoDB
  AUTO_INCREMENT = 4
  DEFAULT CHARACTER SET = utf8mb4;


  -- -----------------------------------------------------
  -- Table `cine`.`pelicula`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`pelicula` (
    `id_pelicula` INT NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(45) NOT NULL,
    `director` VARCHAR(45) NOT NULL,
    `clasificacion` INT NOT NULL,
    `url_image` VARCHAR(200) NULL DEFAULT NULL,
    `genero_id_genero` INT NOT NULL,
    PRIMARY KEY (`id_pelicula`),
    CONSTRAINT `fk_pelicula_genero1`
      FOREIGN KEY (`genero_id_genero`)
      REFERENCES `cine`.`genero` (`id_genero`))
  ENGINE = InnoDB
  AUTO_INCREMENT = 9
  DEFAULT CHARACTER SET = utf8mb4;


  -- -----------------------------------------------------
  -- Table `cine`.`sala`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`sala` (
    `id_sala` INT NOT NULL AUTO_INCREMENT,
    `capacidad` INT NOT NULL,
    `cine_id_cine` INT NOT NULL,
    PRIMARY KEY (`id_sala`),
    CONSTRAINT `fk_sala_cine1`
      FOREIGN KEY (`cine_id_cine`)
      REFERENCES `cine`.`cine` (`id_cine`))
  ENGINE = InnoDB
  AUTO_INCREMENT = 11
  DEFAULT CHARACTER SET = utf8mb4;


  -- -----------------------------------------------------
  -- Table `cine`.`tarifa`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`tarifa` (
    `id_dia` VARCHAR(16) NOT NULL,
    `precio` DECIMAL(10,2) NULL DEFAULT NULL,
    PRIMARY KEY (`id_dia`))
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8mb4;


  -- -----------------------------------------------------
  -- Table `cine`.`funcion`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`funcion` (
    `id_funcion` INT NOT NULL AUTO_INCREMENT,
    `fecha_hora` DATETIME NOT NULL,
    `pelicula_id_pelicula` INT NOT NULL,
    `sala_id_sala` INT NOT NULL,
    `boletas_vendidas` INT NOT NULL,
    `tarifa_id_dia` VARCHAR(16) NOT NULL,
    PRIMARY KEY (`id_funcion`),
    CONSTRAINT `fk_funcion_pelicula1`
      FOREIGN KEY (`pelicula_id_pelicula`)
      REFERENCES `cine`.`pelicula` (`id_pelicula`),
    CONSTRAINT `fk_funcion_sala1`
      FOREIGN KEY (`sala_id_sala`)
      REFERENCES `cine`.`sala` (`id_sala`),
    CONSTRAINT `fk_funcion_tarifa1`
      FOREIGN KEY (`tarifa_id_dia`)
      REFERENCES `cine`.`tarifa` (`id_dia`)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION)
  ENGINE = InnoDB
  AUTO_INCREMENT = 34
  DEFAULT CHARACTER SET = utf8mb4;


  -- -----------------------------------------------------
  -- Table `cine`.`protagonistas`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`protagonistas` (
    `id_actor` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id_actor`))
  ENGINE = InnoDB
  AUTO_INCREMENT = 18
  DEFAULT CHARACTER SET = utf8mb4;


  -- -----------------------------------------------------
  -- Table `cine`.`pelicula_has_protagonistas`
  -- -----------------------------------------------------
  CREATE TABLE IF NOT EXISTS `cine`.`pelicula_has_protagonistas` (
    `pelicula_id_pelicula` INT NOT NULL,
    `protagonistas_id_protagonista` INT NOT NULL,
    PRIMARY KEY (`pelicula_id_pelicula`, `protagonistas_id_protagonista`),
    CONSTRAINT `fk_pelicula_has_protagonistas_pelicula1`
      FOREIGN KEY (`pelicula_id_pelicula`)
      REFERENCES `cine`.`pelicula` (`id_pelicula`),
    CONSTRAINT `fk_pelicula_has_protagonistas_protagonistas1`
      FOREIGN KEY (`protagonistas_id_protagonista`)
      REFERENCES `cine`.`protagonistas` (`id_actor`))
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8mb4;

  -- 1. Insertar Tarifas
  -- Basado en los precios de la imagen (Normal 550, Día espectador 350, Festivos 650)
  INSERT INTO `tarifa` (`id_dia`, `precio`) VALUES
  ('Normal', 550.00),
  ('Espectador', 350.00),
  ('Festivo', 650.00);

  -- 2. Insertar Cines
  INSERT INTO `cine` (`id_cine`, `nombre`, `direccion`, `telefono`) VALUES
  (1, 'ABC EL SALER', 'Centro Comercial El Saler', '3950592'),
  (2, 'ACTEON', 'G.v. Marques del Turia, 26', '3954084'),
  (3, 'ARTIS', 'Russafa, 20', '3940178'),
  (4, 'AULA 7', 'G. Sanmartin, 15', '3940415'),
  (5, 'CINES NUEVO CENTRO', 'Avd. Pio XII, 2', '3485477');

  -- 3. Insertar Géneros
  INSERT INTO `genero` (`id_genero`, `nombre_genero`) VALUES
  (1, 'Dibujos'),
  (2, 'Comedia'),
  (3, 'Drama');

  -- 4. Insertar Películas
  INSERT INTO `pelicula` (`id_pelicula`, `titulo`, `director`, `clasificacion`, `url_image`, `genero_id_genero`) VALUES
  (1, 'Pocahontas', 'Mike Gabriel', 0, 'https://m.media-amazon.com/images/I/515MY8H3FDL._AC_UF1000,1000_QL80_.jpg', 1),
  (2, 'Two much', 'Fernando Trueba', 0, 'https://pics.filmaffinity.com/Two_Much-750844534-large.jpg', 2),
  (3, 'Los puentes de Madison', 'Clint Eastwood', 13, 'https://wmagazin.com/wp-content/uploads/2025/11/cine-club-literario-lospuentesdemadison-cartel-WMagazin-scaled-e1763019346105.jpg', 3),
  (4, 'Smoke', 'Wayne Wang', 0, 'https://m.media-amazon.com/images/M/MV5BMzgwMGQ1NjAtMWZmYi00NDRhLWFkN2EtN2FjNmFlYWRhNzliXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg', 3),
  (5, 'Un paseo por las nubes', 'Alfonso Arau', 13, 'https://m.media-amazon.com/images/M/MV5BZTI3OTdkMzYtOTM5Yy00Njk4LWI1ODktZDlmNjA4YzQ1YTNlXkEyXkFqcGc@._V1_.jpg', 3),
  (6, 'Carrington', 'Christopher Hampton', 13, 'https://play-lh.googleusercontent.com/xB9jlapwtdH3tY7ywO03lwmkyVq2cCJtqU-11bawkKQ4y6qblpt-mDNjIieWPufAZsUZ=w240-h480-rw', 3),
  (7, 'Nueve meses', 'Chris Columbus', 0, 'https://play-lh.googleusercontent.com/proxy/8zHzaYz73OHhhMKEW1RlkFWk7goumCOZY_KedW5yA7tRF4DtBO0eqkYQglXLxjLmUe8XcZcVAdkWPw7eKeYAOziXkoYIbe4i8Z6CjrjcbV0LyXNVqjNhiklE7-0ZavQ2ho6IrSif8kBLGEMheBYQ2JTMjFmhXiMtyahWew', 2),
  (8, 'Vaya Santa Claus', 'John Pasquin', 0, 'https://es.web.img3.acsta.net/medias/nmedia/18/82/96/85/20428062.jpg', 2);

  -- 5. Insertar Salas (Inventadas para relacionar las funciones a cada cine)
  INSERT INTO `sala` (`id_sala`, `capacidad`, `cine_id_cine`) VALUES
  (1, 150, 1), (2, 120, 1), (3, 100, 1), -- Salas de ABC EL SALER
  (4, 90, 2),  (5, 80, 2),               -- Salas de ACTEON
  (6, 110, 3),                           -- Sala de ARTIS
  (7, 100, 4),                           -- Sala de AULA 7
  (8, 200, 5), (9, 150, 5), (10, 180, 5);-- Salas de NUEVO CENTRO

  -- 6. Insertar Protagonistas (Actores extraídos del recorte)
  INSERT INTO `protagonistas` (`id_actor`, `nombre`) VALUES
  (1, 'Antonio Banderas'),
  (2, 'Melanie Griffith'),
  (3, 'Daryl Hannah'),
  (4, 'Clint Eastwood'),
  (5, 'Meryl Streep'),
  (6, 'William Hurt'),
  (7, 'Harvey Keitel'),
  (8, 'Keanu Reeves'),
  (9, 'Aitana Sanchez Gijon'),
  (10, 'Emma Thompson'),
  (11, 'Jonathan Pryce'),
  (12, 'Hugh Grant'),
  (13, 'Julianne Moore'),
  (14, 'Tim Allen'),
  (15, 'Judge Reinhold'),
  (16, 'Irene Bedard'), -- Voz de Pocahontas
  (17, 'Mel Gibson');

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
  INSERT INTO `funcion` (`fecha_hora`, `pelicula_id_pelicula`, `sala_id_sala`, `boletas_vendidas`, `tarifa_id_dia`) VALUES
  -- ABC EL SALER
  ('1995-12-25 16:30:00', 1, 1, 0, 'Normal'), ('1995-12-25 18:25:00', 1, 1, 0, 'Normal'), ('1995-12-25 20:20:00', 1, 1, 0, 'Normal'), ('1995-12-25 22:45:00', 1, 1, 0, 'Normal'), -- Pocahontas
  ('1995-12-25 17:00:00', 2, 2, 0, 'Normal'), ('1995-12-25 19:40:00', 2, 2, 0, 'Normal'), ('1995-12-25 22:50:00', 2, 2, 0, 'Normal'),                                 -- Two much
  ('1995-12-25 16:45:00', 3, 3, 0, 'Normal'), ('1995-12-25 19:35:00', 3, 3, 0, 'Normal'), ('1995-12-25 22:35:00', 3, 3, 0, 'Normal'),                                 -- Los puentes de Madison

  -- ACTEON
  ('1995-12-25 17:15:00', 1, 4, 0, 'Espectador'), ('1995-12-25 19:45:00', 1, 4, 0, 'Espectador'),                                 -- Pocahontas
  ('1995-12-25 22:45:00', 4, 5, 0, 'Espectador'),                                                                   -- Smoke

  -- ARTIS
  ('1995-12-25 16:45:00', 5, 6, 0, 'Festivo'), ('1995-12-25 19:20:00', 5, 6, 0, 'Festivo'), ('1995-12-25 22:45:00', 5, 6, 0, 'Festivo'), -- Un paseo por las nubes

  -- AULA 7
  ('1995-12-25 16:45:00', 6, 7, 0, 'Normal'), ('1995-12-25 19:20:00', 6, 7, 0, 'Normal'), ('1995-12-25 22:45:00', 6, 7, 0, 'Normal'), -- Carrington

  -- CINES NUEVO CENTRO
  ('1995-12-25 16:30:00', 7, 8, 0, 'Espectador'), ('1995-12-25 18:30:00', 7, 8, 0, 'Normal'), ('1995-12-25 20:30:00', 7, 8, 0, 'Espectador'), ('1995-12-25 22:40:00', 7, 8, 0, 'Normal'), -- Nueve meses
  ('1995-12-25 12:00:00', 1, 9, 0, 'Festivo'), ('1995-12-25 16:20:00', 1, 9, 0, 'Espectador'), ('1995-12-25 18:10:00', 1, 9, 0, 'Normal'), ('1995-12-25 19:45:00', 1, 9, 0, 'Normal'), ('1995-12-25 22:40:00', 1, 9, 0, 'Espectador'), -- Pocahontas
  ('1995-12-25 12:00:00', 8, 10, 0, 'Espectador'),('1995-12-25 16:30:00', 8, 10, 0, 'Festivo'),('1995-12-25 18:30:00', 8, 10, 0, 'Espectador'),('1995-12-25 20:30:00', 8, 10, 0, 'Festivo'),('1995-12-25 22:40:00', 8, 10, 0, 'Festivo'); -- ¡Vaya Santa Claus!

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
  INSERT INTO `cine`.`usuarios` (`nombre`, `password`, `correo`, `estado_id`, `permisos`) 
  VALUES ('Admin', '$2y$10$7v/f6M3w3qI.G5p1v5H/u.3mJ5uVfXz/xS7vQZ.pYv8yX7M6x9Y3S', '2305juanda@gmail.com', 1, 1);

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
