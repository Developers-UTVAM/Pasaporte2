-- INICIO DE LA MIGRACION

ALTER TABLE usuario MODIFY COLUMN `password` VARCHAR(255);

INSERT INTO
    `migraciones` (
        `tipo`,
        `nombre`,
        `descripcion`,
        `archivo`
    )
VALUES (
        'DDL',
        'Alter password de usuario',
        'Actualizacion al campo para almacenar contraseñas encriptadas',
        'mig_013_ddl_usuario_upd.sql'
    );

-- FIN DE LA MIGRACION
