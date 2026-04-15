-- INICIO DE LA MIGRACION
-- Crea tabla eventos_config y agrega permiso eventos.admin_eventos_config

SET @OLD_UNIQUE_CHECKS    = @@UNIQUE_CHECKS,    UNIQUE_CHECKS    = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE TABLE IF NOT EXISTS `eventos_config` (
    `id`        BIGINT NOT NULL AUTO_INCREMENT,
    `tipo`      VARCHAR(50)  NOT NULL,
    `icono`     VARCHAR(100) NOT NULL DEFAULT 'fa-calendar',
    `requerido` INT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_eventos_config_tipo` (`tipo`)
) ENGINE = InnoDB COMMENT = 'Configuración de tipos de actividad del pasaporte';

INSERT IGNORE INTO `eventos_config` (`tipo`, `icono`, `requerido`) VALUES
    ('conferencia', 'fa-chalkboard-user',    3),
    ('taller',      'fa-screwdriver-wrench', 1),
    ('deportiva',   'fa-volleyball',         1),
    ('cultural',    'fa-masks-theater',      2),
    ('juegos',      'fa-gamepad',            2),
    ('concurso',    'fa-trophy',             1);

INSERT IGNORE INTO `permiso` (`tipo`, `codename`, `nombre`)
VALUES ('eventos', 'admin_eventos_config', 'Administrar configuración de eventos');

INSERT IGNORE INTO `migraciones` (`tipo`, `nombre`, `descripcion`, `archivo`)
VALUES (
    'DDL',
    'Create eventos_config',
    'Crea tabla eventos_config con datos iniciales y permiso eventos.admin_eventos_config',
    'mig_030_ddl_eventos_config.sql'
);

-- FIN DE LA MIGRACION

SET SQL_MODE             = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS   = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS        = @OLD_UNIQUE_CHECKS;
