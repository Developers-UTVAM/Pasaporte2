DELETE ptp FROM perfil_tiene_permiso ptp
INNER JOIN permiso p ON p.id = ptp.permiso_id
WHERE p.codename IN ('escanear_qr', 'view_mi_asistencia');

DELETE FROM permiso WHERE codename IN ('escanear_qr', 'view_mi_asistencia');

INSERT INTO permiso (tipo, codename, nombre)
SELECT nuevos.tipo, nuevos.codename, nuevos.nombre
FROM (
    SELECT 'asistencia_clase' AS tipo, 'pasar_lista'        AS codename, 'Pasar lista de asistencia (QR)' AS nombre
    UNION ALL SELECT 'asistencia_clase', 'ver_mi_asistencia', 'Ver mi propia asistencia'
    UNION ALL SELECT 'asistencia_clase', 'ver_historial',     'Ver historial de asistencia de mis grupos'
) AS nuevos
WHERE NOT EXISTS (SELECT 1 FROM permiso p WHERE p.codename = nuevos.codename);

INSERT INTO permiso (tipo, codename, nombre)
SELECT nuevos.tipo, nuevos.codename, nuevos.nombre
FROM (
    SELECT 'inscripcion' AS tipo, 'add_inscripcion'    AS codename, 'Agregar inscripcion' AS nombre
    UNION ALL SELECT 'inscripcion', 'change_inscripcion', 'Cambiar inscripcion'
    UNION ALL SELECT 'inscripcion', 'delete_inscripcion', 'Eliminar inscripcion'
    UNION ALL SELECT 'inscripcion', 'view_inscripcion',   'Ver inscripcion'
) AS nuevos
WHERE NOT EXISTS (SELECT 1 FROM permiso p WHERE p.codename = nuevos.codename);

INSERT INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT pf.id, pe.id
FROM perfil pf
JOIN permiso pe
    ON (pe.tipo = 'asistencia_clase' AND pe.codename IN ('pasar_lista', 'ver_mi_asistencia', 'ver_historial'))
    OR (pe.tipo = 'inscripcion'      AND pe.codename IN ('add_inscripcion', 'change_inscripcion', 'delete_inscripcion', 'view_inscripcion'))
WHERE pf.nombre = 'admin'
  AND NOT EXISTS (
    SELECT 1 FROM perfil_tiene_permiso ptp
    WHERE ptp.perfil_id = pf.id AND ptp.permiso_id = pe.id
  );

INSERT INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT pf.id, pe.id
FROM perfil pf
JOIN permiso pe
    ON (pe.tipo = 'asistencia_clase' AND pe.codename IN ('pasar_lista', 'ver_historial'))
WHERE pf.nombre = 'profesor'
  AND NOT EXISTS (
    SELECT 1 FROM perfil_tiene_permiso ptp
    WHERE ptp.perfil_id = pf.id AND ptp.permiso_id = pe.id
  );

INSERT INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT pf.id, pe.id
FROM perfil pf
JOIN permiso pe
    ON (pe.tipo = 'asistencia_clase' AND pe.codename = 'ver_mi_asistencia')
WHERE pf.nombre = 'alumno'
  AND NOT EXISTS (
    SELECT 1 FROM perfil_tiene_permiso ptp
    WHERE ptp.perfil_id = pf.id AND ptp.permiso_id = pe.id
  );
