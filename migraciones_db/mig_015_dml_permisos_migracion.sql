-- INICIO DE LA MIGRACION

INSERT IGNORE INTO `permiso` (`tipo`, `codename`, `nombre`) VALUES
('migracion', 'view_migracion', 'Ver menú de migraciones'),
('migracion', 'run_migracion', 'Ejecutar sincronización de migraciones');


-- FIN DE LA MIGRACION