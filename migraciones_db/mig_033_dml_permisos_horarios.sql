INSERT INTO permiso (tipo, codename, nombre)
SELECT nuevos.tipo, nuevos.codename, nuevos.nombre
FROM (
    
    SELECT 'carrera' AS tipo, 'add_carrera'    AS codename, 'Agregar carrera'   AS nombre
    UNION ALL SELECT 'carrera', 'change_carrera', 'Cambiar carrera'
    UNION ALL SELECT 'carrera', 'delete_carrera', 'Eliminar carrera'
    UNION ALL SELECT 'carrera', 'view_carrera',   'Ver carrera'

  
    UNION ALL SELECT 'aula', 'add_aula',    'Agregar aula'
    UNION ALL SELECT 'aula', 'change_aula', 'Cambiar aula'
    UNION ALL SELECT 'aula', 'delete_aula', 'Eliminar aula'
    UNION ALL SELECT 'aula', 'view_aula',   'Ver aula'


    UNION ALL SELECT 'materia', 'add_materia',    'Agregar materia'
    UNION ALL SELECT 'materia', 'change_materia', 'Cambiar materia'
    UNION ALL SELECT 'materia', 'delete_materia', 'Eliminar materia'
    UNION ALL SELECT 'materia', 'view_materia',   'Ver materia'

    
    UNION ALL SELECT 'horario', 'add_horario',    'Agregar horario'
    UNION ALL SELECT 'horario', 'change_horario', 'Cambiar horario'
    UNION ALL SELECT 'horario', 'delete_horario', 'Eliminar horario'
    UNION ALL SELECT 'horario', 'view_horario',   'Ver horario'

   
    UNION ALL SELECT 'horario', 'manage_disponibilidad', 'Gestionar disponibilidad de horarios'
) AS nuevos
WHERE NOT EXISTS (
    SELECT 1
    FROM permiso p
    WHERE p.codename = nuevos.codename
      AND p.tipo = nuevos.tipo
);

INSERT INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT pf.id, pe.id
FROM perfil pf
JOIN permiso pe
    ON (pe.tipo = 'carrera' AND pe.codename IN ('add_carrera', 'change_carrera', 'delete_carrera', 'view_carrera'))
    OR (pe.tipo = 'aula'    AND pe.codename IN ('add_aula', 'change_aula', 'delete_aula', 'view_aula'))
    OR (pe.tipo = 'materia' AND pe.codename IN ('add_materia', 'change_materia', 'delete_materia', 'view_materia'))
    OR (pe.tipo = 'horario' AND pe.codename IN ('add_horario', 'change_horario', 'delete_horario', 'view_horario', 'manage_disponibilidad'))
WHERE pf.nombre = 'admin'
  AND NOT EXISTS (
    SELECT 1
    FROM perfil_tiene_permiso ptp
    WHERE ptp.perfil_id = pf.id
      AND ptp.permiso_id = pe.id
  );
