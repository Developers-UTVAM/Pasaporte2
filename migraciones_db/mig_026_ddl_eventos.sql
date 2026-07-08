ALTER TABLE evento
DROP COLUMN costo_externo,
DROP COLUMN costo_interno,
ADD COLUMN permitir_autorregistro TINYINT UNSIGNED NOT NULL DEFAULT 1;

ALTER TABLE `evento`
CHANGE COLUMN `requiere_registro` `requiere_registro` TINYINT NULL DEFAULT '1',
CHANGE COLUMN `permitir_autorregistro` `permitir_autorregistro` TINYINT UNSIGNED NULL DEFAULT '1';

