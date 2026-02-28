-- Creacion de tabla de migraciones para llevar control de las migraciones aplicadas a la base de datos.

SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;

SET
    @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS,
    FOREIGN_KEY_CHECKS = 0;

SET
    @OLD_SQL_MODE = @@SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- INICIO DE LA MIGRACION

CREATE TABLE IF NOT EXISTS `perfil` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `perfil_tiene_permiso` (
    `perfil_id` BIGINT NOT NULL,
    `permiso_id` BIGINT NOT NULL,
    PRIMARY KEY (`perfil_id`, `permiso_id`),
    INDEX `fk_perfil_tiene_permiso_permiso1_idx` (`permiso_id` ASC) VISIBLE,
    INDEX `fk_perfil_tiene_permiso_perfil_idx` (`perfil_id` ASC) VISIBLE,
    CONSTRAINT `fk_perfil_tiene_permiso_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_perfil_tiene_permiso_permiso1` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
        'Create perfil tbl',
        'Creacion de las tablas iniciales para perfiles',
        'mig_003_ddl_perfil.sql'
    );

-- FIN DE LA MIGRACION

SET SQL_MODE = @OLD_SQL_MODE;

SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;

SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
