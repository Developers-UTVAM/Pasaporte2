alter Table evento ADD responsable_interno VARCHAR(200) NULL;
alter Table evento ADD responsable_externo VARCHAR(200) NULL;

INSERT INTO `migraciones` (`tipo`, `nombre`, `descripcion`, `archivo`)
VALUES ('DDL', 'Actualizar tabla evento', 'Agregar columnas de responsable interno y externo a la tabla evento', 'mig_012_ddl_actualizacion_tabla_evento.sql');

-- FIN DE LA MIGRACION