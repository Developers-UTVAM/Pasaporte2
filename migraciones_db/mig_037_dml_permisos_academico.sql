INSERT INTO permiso (tipo, codename, nombre)
SELECT nuevos.tipo, nuevos.codename, nuevos.nombre
FROM (
    SELECT 'horario' AS tipo, 'view_carga_academica' AS codename, 'Ver mi carga académica' AS nombre
    UNION ALL SELECT 'asistencia_clase', 'escanear_qr',        'Escanear QR (pase de lista)'
    UNION ALL SELECT 'asistencia_clase', 'view_mi_asistencia', 'Ver mi asistencia'
) AS nuevos
WHERE NOT EXISTS (
    SELECT 1 FROM permiso p
    WHERE p.codename = nuevos.codename AND p.tipo = nuevos.tipo
);

INSERT INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT pf.id, pe.id
FROM perfil pf
JOIN permiso pe
    ON (pe.tipo = 'horario'          AND pe.codename = 'view_carga_academica')
    OR (pe.tipo = 'asistencia_clase' AND pe.codename = 'escanear_qr')
WHERE pf.nombre = 'profesor'
  AND NOT EXISTS (
    SELECT 1 FROM perfil_tiene_permiso ptp
    WHERE ptp.perfil_id = pf.id AND ptp.permiso_id = pe.id
  );

INSERT INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT pf.id, pe.id
FROM perfil pf
JOIN permiso pe
    ON (pe.tipo = 'asistencia_clase' AND pe.codename = 'view_mi_asistencia')
WHERE pf.nombre = 'alumno'
  AND NOT EXISTS (
    SELECT 1 FROM perfil_tiene_permiso ptp
    WHERE ptp.perfil_id = pf.id AND ptp.permiso_id = pe.id
  );

INSERT INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT pf.id, pe.id
FROM perfil pf
JOIN permiso pe
    ON (pe.tipo = 'horario'          AND pe.codename = 'view_carga_academica')
    OR (pe.tipo = 'asistencia_clase' AND pe.codename IN ('escanear_qr', 'view_mi_asistencia'))
WHERE pf.nombre = 'admin'
  AND NOT EXISTS (
    SELECT 1 FROM perfil_tiene_permiso ptp
    WHERE ptp.perfil_id = pf.id AND ptp.permiso_id = pe.id
  );
