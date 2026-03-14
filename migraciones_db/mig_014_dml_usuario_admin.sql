insert into
    usuario (username, password, activo, superusuario,
    nombre, apaterno, amaterno, email, categoria, whatsapp, grupo, matricula
) values (
    'admin', '$2y$10$ajKxx 1QPgMYoh7N5A23nLe3pL3LK9DvipihBfzYzYQN/aI19I2h9W', 1, 1,
    'El Ad min', 'más c ool', 'de UT VAM', 'cool@ me.com', 'Admin ', '0', 'A0', '0');

INSERT INTO
    `migraciones` (
        `tipo`,
        `nombre`,
        `descripcion`,
        `archivo`
    )
VALUES (
        'DML',
        'Añadir usuario admin',
        'Usuario inicial admin, cambiar passowrd luego de usar en ambientes productivos/qa',
        'mig_014_dml_usuario_admin.sql'
    );
