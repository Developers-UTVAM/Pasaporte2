-- Creacion de tabla de migraciones para llevar control de las migraciones aplicadas a la base de datos.

SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;

SET
    @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS,
    FOREIGN_KEY_CHECKS = 0;

SET
    @OLD_SQL_MODE = @@SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- INICIO DE LA MIGRACION

CREATE TABLE IF NOT EXISTS `usuario` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(50) NOT NULL,
    `activo` TINYINT NOT NULL DEFAULT 1,
    `superusuario` TINYINT NOT NULL DEFAULT 0,
    `nombre` VARCHAR(50) NULL,
    `apaterno` VARCHAR(50) NULL,
    `amaterno` VARCHAR(50) NULL,
    `email` VARCHAR(50) NOT NULL,
    `categoria` VARCHAR(50) NULL,
    `whatsapp` VARCHAR(50) NOT NULL,
    `grupo` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE,
    UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `usuario_tiene_perfil` (
    `usuario_id` BIGINT NOT NULL,
    `perfil_id` BIGINT NOT NULL,
    PRIMARY KEY (`usuario_id`, `perfil_id`),
    INDEX `fk_usuario_tiene_perfil_perfil1_idx` (`perfil_id` ASC) VISIBLE,
    INDEX `fk_usuario_tiene_perfil_usuario1_idx` (`usuario_id` ASC) VISIBLE,
    CONSTRAINT `fk_usuario_tiene_perfil_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_usuario_tiene_perfil_perfil1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `usuario_tiene_permiso` (
    `usuario_id` BIGINT NOT NULL,
    `permiso_id` BIGINT NOT NULL,
    PRIMARY KEY (`usuario_id`, `permiso_id`),
    INDEX `fk_usuario_tiene_permiso_permiso1_idx` (`permiso_id` ASC) VISIBLE,
    INDEX `fk_usuario_tiene_permiso_usuario1_idx` (`usuario_id` ASC) VISIBLE,
    CONSTRAINT `fk_usuario_tiene_permiso_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_usuario_tiene_permiso_permiso1` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

INSERT INTO
    `migraciones` (
        `tipo`,
        `nombre`,
        `descripcion`,
        `archivo`
    )
VALUES (
        'DDL',
        'Create usuario tbl',
        'Creacion de las tablas iniciales para usuarios',
        'mig_004_ddl_usuario.sql'
    );

-- FIN DE LA MIGRACION

SET SQL_MODE = @OLD_SQL_MODE;

SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;

SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
