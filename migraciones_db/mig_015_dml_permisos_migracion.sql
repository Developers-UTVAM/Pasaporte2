-- INICIO DE LA MIGRACION


INSERT IGNORE INTO `permiso` (`tipo`, `codename`, `nombre`) VALUES
('migracion', 'view_migracion', 'Ver menú de migraciones'),
('migracion', 'run_migracion', 'Ejecutar sincronización de migraciones');

INSERT INTO
    `migraciones` (
        `tipo`,
        `nombre`,
        `descripcion`,
        `archivo`
    )
VALUES (
        'DML',
        'Permisos de Migracion',
        'Agrega los permisos del sistema para el modulo de migraciones',
        'mig_016_dml_permisos_migracion.sql'
    );

-- FIN DE LA MIGRACION