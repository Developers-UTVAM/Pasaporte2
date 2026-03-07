ALTER TABLE `usuario` MODIFY COLUMN `password` VARCHAR(255) NOT NULL;

INSERT INTO `migraciones` (`tipo`, `nombre`, `descripcion`, `archivo`)
VALUES ('DDL', 'Modificar columna password', 'Modificar columna password de la tabla usuario', 'mig_013_dll_alterar_contrasena_usuario.sql');