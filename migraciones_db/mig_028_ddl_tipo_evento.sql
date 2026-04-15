SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- =========================
-- INICIO DE LA MIGRACION
-- =========================

ALTER TABLE `evento`
ADD COLUMN IF NOT EXISTS `tipo` VARCHAR(50) NULL DEFAULT NULL AFTER `nombre`;

UPDATE `evento`
SET `tipo` = CASE
    WHEN LOWER(nombre) LIKE '%conferencia%' OR LOWER(nombre) LIKE '%conveferencia%' OR LOWER(nombre) LIKE '%conferecia%' THEN 'conferencia'
    WHEN LOWER(nombre) LIKE '%taller%' THEN 'taller'
    WHEN LOWER(nombre) LIKE '%deportiv%' OR LOWER(nombre) LIKE '%deporte%' THEN 'deportiva'
    WHEN LOWER(nombre) LIKE '%cultural%' THEN 'cultural'
    WHEN LOWER(nombre) LIKE '%juego%' THEN 'juegos'
    WHEN LOWER(nombre) LIKE '%concurso%' THEN 'concurso'
    ELSE 'otro'
END
WHERE `tipo` IS NULL OR `tipo` = '';

INSERT INTO `migraciones` (`tipo`, `nombre`, `descripcion`, `archivo`)
VALUES (
    'DDL',
    'Add tipo to evento',
    'Agrega columna tipo a evento y clasifica registros existentes',
    'mig_028_ddl_tipo_evento.sql'
);

-- =========================
-- FIN DE LA MIGRACION
-- =========================

SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;
