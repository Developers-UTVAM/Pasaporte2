-- Creacion de tabla de migraciones para llevar control de las migraciones aplicadas a la base de datos.

SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;

SET
    @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS,
    FOREIGN_KEY_CHECKS = 0;

SET
    @OLD_SQL_MODE = @@SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- INICIO DE LA MIGRACION

CREATE TABLE IF NOT EXISTS `permiso` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `tipo` VARCHAR(100) NOT NULL,
    `codename` VARCHAR(100) NOT NULL,
    `nombre` VARCHAR(100) NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `codename_UNIQUE` (`codename` ASC) VISIBLE
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
        'Create permiso tbl',
        'Creacion de la tabla inicial para permisos',
        'mig_002_ddl_permiso.sql'
    );

-- FIN DE LA MIGRACION

SET SQL_MODE = @OLD_SQL_MODE;

SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;

SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
