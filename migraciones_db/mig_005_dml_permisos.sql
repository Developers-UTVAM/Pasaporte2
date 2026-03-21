-- INICIO DE LA MIGRACION

insert into `permiso` (`tipo`, `codename`, `nombre`) values

('permiso', 'add_permiso', 'Agregar permiso'),
('permiso', 'change_permiso', 'Cambiar permiso'),
('permiso', 'delete_permiso', 'Eliminar permiso'),
('permiso', 'view_permiso', 'Ver permiso'),
('permiso', 'list_permiso', 'Listar permisos'),

('perfil', 'add_perfil', 'Agregar perfil'),
('perfil', 'change_perfil', 'Cambiar perfil'),
('perfil', 'delete_perfil', 'Eliminar perfil'),
('perfil', 'view_perfil', 'Ver perfil'),
('perfil', 'list_perfil', 'Listar perfiles'),

('usuario', 'add_usuario', 'Agregar usuario'),
('usuario', 'change_usuario', 'Cambiar usuario'),
('usuario', 'delete_usuario', 'Eliminar usuario'),
('usuario', 'view_usuario', 'Ver usuario'),
('usuario', 'list_usuario', 'Listar usuarios');

INSERT INTO
    `migraciones` (
        `tipo`,
        `nombre`,
        `descripcion`,
        `archivo`
    )
VALUES (
        'DDL',
        'Creacion de permisos',
        'Creacion de permisos para administrar permisos, perfiles y usuarios',
        'mig_005_dml_permisos.sql'
    );

-- FIN DE LA MIGRACION
