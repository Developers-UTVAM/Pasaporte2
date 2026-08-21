-- NOMBRE: Datos de prueba para el modulo academico
-- DESCRIPCION: Cuentas de prueba, catalogos (carreras, aulas, materias), horarios, disponibilidad, inscripciones y asistencias de prueba.
-- Motor: MySQL / MariaDB. El script es idempotente.

START TRANSACTION;

-- Perfiles usados por los modulos academicos.
INSERT INTO perfil (nombre)
SELECT 'admin' WHERE NOT EXISTS (SELECT 1 FROM perfil WHERE nombre = 'admin');
INSERT INTO perfil (nombre)
SELECT 'profesor' WHERE NOT EXISTS (SELECT 1 FROM perfil WHERE nombre = 'profesor');
INSERT INTO perfil (nombre)
SELECT 'basico' WHERE NOT EXISTS (SELECT 1 FROM perfil WHERE nombre = 'basico');
INSERT INTO perfil (nombre)
SELECT 'alumno' WHERE NOT EXISTS (SELECT 1 FROM perfil WHERE nombre = 'alumno');

-- Permisos academicos, por si la migracion de permisos aun no fue aplicada.
INSERT INTO permiso (tipo, codename, nombre)
SELECT datos.tipo, datos.codename, datos.nombre
FROM (
    SELECT 'carrera' tipo, 'add_carrera' codename, 'Agregar carrera' nombre
    UNION ALL SELECT 'carrera', 'change_carrera', 'Cambiar carrera'
    UNION ALL SELECT 'carrera', 'delete_carrera', 'Eliminar carrera'
    UNION ALL SELECT 'carrera', 'view_carrera', 'Ver carrera'
    UNION ALL SELECT 'aula', 'add_aula', 'Agregar aula'
    UNION ALL SELECT 'aula', 'change_aula', 'Cambiar aula'
    UNION ALL SELECT 'aula', 'delete_aula', 'Eliminar aula'
    UNION ALL SELECT 'aula', 'view_aula', 'Ver aula'
    UNION ALL SELECT 'materia', 'add_materia', 'Agregar materia'
    UNION ALL SELECT 'materia', 'change_materia', 'Cambiar materia'
    UNION ALL SELECT 'materia', 'delete_materia', 'Eliminar materia'
    UNION ALL SELECT 'materia', 'view_materia', 'Ver materia'
    UNION ALL SELECT 'horario', 'add_horario', 'Agregar horario'
    UNION ALL SELECT 'horario', 'change_horario', 'Cambiar horario'
    UNION ALL SELECT 'horario', 'delete_horario', 'Eliminar horario'
    UNION ALL SELECT 'horario', 'view_horario', 'Ver horario'
    UNION ALL SELECT 'horario', 'manage_disponibilidad', 'Gestionar disponibilidad de horarios'
    UNION ALL SELECT 'horario', 'view_carga_academica', 'Ver mi carga academica'
    UNION ALL SELECT 'asistencia_clase', 'pasar_lista', 'Pasar lista de asistencia (QR)'
    UNION ALL SELECT 'asistencia_clase', 'ver_mi_asistencia', 'Ver mi propia asistencia'
    UNION ALL SELECT 'asistencia_clase', 'ver_historial', 'Ver historial de asistencia de mis grupos'
    UNION ALL SELECT 'inscripcion', 'add_inscripcion', 'Agregar inscripcion'
    UNION ALL SELECT 'inscripcion', 'change_inscripcion', 'Cambiar inscripcion'
    UNION ALL SELECT 'inscripcion', 'delete_inscripcion', 'Eliminar inscripcion'
    UNION ALL SELECT 'inscripcion', 'view_inscripcion', 'Ver inscripcion'
) datos
WHERE NOT EXISTS (
    SELECT 1 FROM permiso p
    WHERE p.tipo = datos.tipo AND p.codename = datos.codename
);

-- Hash de password_hash('qwerty', PASSWORD_DEFAULT).
INSERT INTO usuario
    (username, password, activo, superusuario, nombre, apaterno, amaterno,
     email, categoria, whatsapp, grupo, matricula)
SELECT datos.username, datos.password, 1, datos.superusuario, datos.nombre,
       datos.apaterno, datos.amaterno, datos.email, datos.categoria,
       datos.whatsapp, datos.grupo, datos.matricula
FROM (
    SELECT 'admin.academico' username, '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2' password,
           1 superusuario, 'Administrador' nombre, 'Academico' apaterno, NULL amaterno,
           'admin.academico@demo.local' email, 'admin' categoria, '5550000001' whatsapp, 'ADMIN' grupo, '233110100' matricula
    UNION ALL SELECT 'profesor.garcia', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Laura', 'Garcia', 'Mendez', 'laura.garcia@demo.local', 'profesor', '5550000002', 'DOC', '233110101'
    UNION ALL SELECT 'profesor.lopez', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Miguel', 'Lopez', 'Santos', 'miguel.lopez@demo.local', 'profesor', '5550000003', 'DOC', '233110102'
    UNION ALL SELECT 'alumno.ana', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Ana', 'Martinez', 'Ruiz', 'ana.martinez@demo.local', 'alumno', '5550000004', 'A', '233110103'
    UNION ALL SELECT 'alumno.bruno', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Bruno', 'Hernandez', 'Diaz', 'bruno.hernandez@demo.local', 'alumno', '5550000005', 'A', '233110104'
    UNION ALL SELECT 'alumno.carla', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Carla', 'Sanchez', 'Vega', 'carla.sanchez@demo.local', 'alumno', '5550000006', 'A', '233110105'
    UNION ALL SELECT 'alumno.diego', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Diego', 'Torres', 'Nava', 'diego.torres@demo.local', 'alumno', '5550000007', 'A', '233110106'
    UNION ALL SELECT 'alumno.elena', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Elena', 'Flores', 'Cruz', 'elena.flores@demo.local', 'alumno', '5550000008', 'B', '233110107'
    UNION ALL SELECT 'alumno.felipe', '$2y$12$IqAxEJfBpG6f7tymhvWTA.NiOwd.U.qMF08WfmXPO4wnfJnSDv/F2', 0, 'Felipe', 'Ramirez', 'Luna', 'felipe.ramirez@demo.local', 'alumno', '5550000009', 'B', '233110108'
) datos
WHERE NOT EXISTS (
    SELECT 1 FROM usuario u
    WHERE u.username = datos.username OR u.email = datos.email OR u.matricula = datos.matricula
);

-- Perfiles de las cuentas de prueba.
INSERT IGNORE INTO usuario_tiene_perfil (usuario_id, perfil_id)
SELECT u.id, p.id FROM usuario u JOIN perfil p
WHERE (u.username = 'admin.academico' AND p.nombre = 'admin')
   OR (u.username IN ('profesor.garcia', 'profesor.lopez') AND p.nombre = 'profesor')
   OR (u.username LIKE 'alumno.%' AND p.nombre IN ('basico', 'alumno'));

-- Permisos para los perfiles academicos.
INSERT IGNORE INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT p.id, pe.id
FROM perfil p CROSS JOIN permiso pe
WHERE p.nombre = 'admin'
  AND pe.codename IN (
      'add_carrera', 'change_carrera', 'delete_carrera', 'view_carrera',
      'add_aula', 'change_aula', 'delete_aula', 'view_aula',
      'add_materia', 'change_materia', 'delete_materia', 'view_materia',
      'add_horario', 'change_horario', 'delete_horario', 'view_horario',
      'manage_disponibilidad', 'view_carga_academica', 'pasar_lista',
      'ver_mi_asistencia', 'ver_historial', 'add_inscripcion',
      'change_inscripcion', 'delete_inscripcion', 'view_inscripcion'
  );
INSERT IGNORE INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT p.id, pe.id FROM perfil p CROSS JOIN permiso pe
WHERE p.nombre = 'profesor'
  AND pe.codename IN ('view_carga_academica', 'pasar_lista', 'ver_historial', 'manage_disponibilidad');
INSERT IGNORE INTO perfil_tiene_permiso (perfil_id, permiso_id)
SELECT p.id, pe.id FROM perfil p CROSS JOIN permiso pe
WHERE p.nombre = 'alumno' AND pe.codename = 'ver_mi_asistencia';

-- Catalogos academicos.
INSERT INTO carrera (clave, nombre, activa)
SELECT 'ISC', 'Ingenieria en Sistemas Computacionales', 1
WHERE NOT EXISTS (SELECT 1 FROM carrera WHERE clave = 'ISC');
INSERT INTO carrera (clave, nombre, activa)
SELECT 'ADM', 'Licenciatura en Administracion', 1
WHERE NOT EXISTS (SELECT 1 FROM carrera WHERE clave = 'ADM');

INSERT INTO aula (codigo, edificio, capacidad, tipo, activa)
SELECT 'LAB-101', 'Edificio A', 30, 'laboratorio', 1
WHERE NOT EXISTS (SELECT 1 FROM aula WHERE codigo = 'LAB-101');
INSERT INTO aula (codigo, edificio, capacidad, tipo, activa)
SELECT 'A-202', 'Edificio A', 40, 'aula', 1
WHERE NOT EXISTS (SELECT 1 FROM aula WHERE codigo = 'A-202');
INSERT INTO aula (codigo, edificio, capacidad, tipo, activa)
SELECT 'AUD-01', 'Edificio B', 120, 'auditorio', 1
WHERE NOT EXISTS (SELECT 1 FROM aula WHERE codigo = 'AUD-01');

INSERT INTO materia (clave, nombre, asistencias, horas_semana, periodo, carrera_id, activa)
SELECT 'ISC101', 'Fundamentos de Programacion', 30, 5, '2026-2', c.id, 1
FROM carrera c WHERE c.clave = 'ISC'
  AND NOT EXISTS (SELECT 1 FROM materia WHERE clave = 'ISC101');
INSERT INTO materia (clave, nombre, asistencias, horas_semana, periodo, carrera_id, activa)
SELECT 'ISC102', 'Bases de Datos', 30, 5, '2026-2', c.id, 1
FROM carrera c WHERE c.clave = 'ISC'
  AND NOT EXISTS (SELECT 1 FROM materia WHERE clave = 'ISC102');
INSERT INTO materia (clave, nombre, asistencias, horas_semana, periodo, carrera_id, activa)
SELECT 'ISC103', 'Estructuras de Datos', 30, 4, '2026-2', c.id, 1
FROM carrera c WHERE c.clave = 'ISC'
  AND NOT EXISTS (SELECT 1 FROM materia WHERE clave = 'ISC103');
INSERT INTO materia (clave, nombre, asistencias, horas_semana, periodo, carrera_id, activa)
SELECT 'ADM101', 'Administracion General', 30, 4, '2026-2', c.id, 1
FROM carrera c WHERE c.clave = 'ADM'
  AND NOT EXISTS (SELECT 1 FROM materia WHERE clave = 'ADM101');

-- Horarios, uno por materia/grupo. Las aulas no se solapan.
INSERT INTO horario (materia_id, profesor_id, aula_id, grupo, dia_semana, hora_inicio, hora_fin, periodo, activo)
SELECT m.id, u.id, a.id, 'A', 'lunes', '08:00:00', '10:00:00', '2026-2', 1
FROM materia m JOIN usuario u ON u.username = 'profesor.garcia' JOIN aula a ON a.codigo = 'LAB-101'
WHERE m.clave = 'ISC101' AND NOT EXISTS (SELECT 1 FROM horario h WHERE h.materia_id = m.id AND h.grupo = 'A' AND h.periodo = '2026-2');
INSERT INTO horario (materia_id, profesor_id, aula_id, grupo, dia_semana, hora_inicio, hora_fin, periodo, activo)
SELECT m.id, u.id, a.id, 'A', 'martes', '08:00:00', '10:00:00', '2026-2', 1
FROM materia m JOIN usuario u ON u.username = 'profesor.garcia' JOIN aula a ON a.codigo = 'A-202'
WHERE m.clave = 'ISC102' AND NOT EXISTS (SELECT 1 FROM horario h WHERE h.materia_id = m.id AND h.grupo = 'A' AND h.periodo = '2026-2');
INSERT INTO horario (materia_id, profesor_id, aula_id, grupo, dia_semana, hora_inicio, hora_fin, periodo, activo)
SELECT m.id, u.id, a.id, 'A', 'miercoles', '10:00:00', '12:00:00', '2026-2', 1
FROM materia m JOIN usuario u ON u.username = 'profesor.lopez' JOIN aula a ON a.codigo = 'LAB-101'
WHERE m.clave = 'ISC103' AND NOT EXISTS (SELECT 1 FROM horario h WHERE h.materia_id = m.id AND h.grupo = 'A' AND h.periodo = '2026-2');
INSERT INTO horario (materia_id, profesor_id, aula_id, grupo, dia_semana, hora_inicio, hora_fin, periodo, activo)
SELECT m.id, u.id, a.id, 'B', 'jueves', '10:00:00', '12:00:00', '2026-2', 1
FROM materia m JOIN usuario u ON u.username = 'profesor.lopez' JOIN aula a ON a.codigo = 'A-202'
WHERE m.clave = 'ADM101' AND NOT EXISTS (SELECT 1 FROM horario h WHERE h.materia_id = m.id AND h.grupo = 'B' AND h.periodo = '2026-2');

-- Disponibilidad de los profesores.
INSERT IGNORE INTO profesor_disponibilidad (profesor_id, dia_semana, hora_inicio, hora_fin, periodo, tipo, notas)
SELECT u.id, d.dia, d.inicio, d.fin, '2026-2', 'disponible', 'Bloque disponible para asignacion'
FROM usuario u
CROSS JOIN (
    SELECT 'lunes' dia, '08:00:00' inicio, '12:00:00' fin
    UNION ALL SELECT 'martes', '08:00:00', '12:00:00'
    UNION ALL SELECT 'miercoles', '08:00:00', '12:00:00'
    UNION ALL SELECT 'jueves', '08:00:00', '14:00:00'
    UNION ALL SELECT 'viernes', '08:00:00', '12:00:00'
) d
WHERE u.username IN ('profesor.garcia', 'profesor.lopez');

-- Inscripciones de alumnos en sus grupos.
INSERT IGNORE INTO inscripcion (usuario_id, materia_id, grupo, periodo, activa)
SELECT u.id, m.id, 'A', '2026-2', 1
FROM usuario u CROSS JOIN materia m
WHERE u.username IN ('alumno.ana', 'alumno.bruno', 'alumno.carla')
  AND m.clave IN ('ISC101', 'ISC102', 'ISC103');
INSERT IGNORE INTO inscripcion (usuario_id, materia_id, grupo, periodo, activa)
SELECT u.id, m.id, 'B', '2026-2', 1
FROM usuario u CROSS JOIN materia m
WHERE u.username IN ('alumno.diego', 'alumno.elena', 'alumno.felipe')
  AND m.clave = 'ADM101';

-- Asistencias de ejemplo para probar reportes y el lector QR.
INSERT IGNORE INTO asistencia_clase (horario_id, usuario_id, fecha, hora, estado, metodo)
SELECT h.id, u.id, '2026-08-17', '08:05:00', 'presente', 'manual'
FROM horario h JOIN materia m ON m.id = h.materia_id
JOIN usuario u ON u.username IN ('alumno.ana', 'alumno.bruno', 'alumno.carla')
WHERE m.clave = 'ISC101' AND h.grupo = 'A' AND h.periodo = '2026-2';
INSERT IGNORE INTO asistencia_clase (horario_id, usuario_id, fecha, hora, estado, metodo)
SELECT h.id, u.id, '2026-08-18', '08:14:00', 'retardo', 'manual'
FROM horario h JOIN materia m ON m.id = h.materia_id
JOIN usuario u ON u.username = 'alumno.ana'
WHERE m.clave = 'ISC102' AND h.grupo = 'A' AND h.periodo = '2026-2';

COMMIT;
