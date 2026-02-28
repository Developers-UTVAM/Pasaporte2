-- Creacion de tabla de migraciones para llevar control de las migraciones aplicadas a la base de datos.

SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET
    @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS,
    FOREIGN_KEY_CHECKS = 0;
SET
    @OLD_SQL_MODE = @@SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- INICIO DE LA MIGRACION

CREATE TABLE IF NOT EXISTS `migraciones` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `tipo` ENUM('DDL', 'DML') NOT NULL,
    `nombre` VARCHAR(100) NOT NULL,
    `descripcion` TEXT ,
    `archivo` VARCHAR(50) NOT NULL,
    `fecha_aplicacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `migraciones` (`tipo`, `nombre`, `descripcion`, `archivo`) VALUES
('DDL', 'Create migracion tbl', 'Creacion de la tabla inicial para migraciones', 'mig_001_ddl_migracion.sql');

-- FIN DE LA MIGRACION

SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
