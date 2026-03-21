-- INICIO DE LA MIGRACION

-- Se insertan los perfiles 'basico' y 'alumno' si no existen,
-- para evitar duplicados en caso de ejecutar la migración varias veces.
INSERT INTO perfil (nombre)
SELECT 'basico'
WHERE NOT EXISTS (SELECT 1 FROM perfil WHERE nombre = 'basico');

INSERT INTO perfil (nombre)
SELECT 'alumno'
WHERE NOT EXISTS (SELECT 1 FROM perfil WHERE nombre = 'alumno');

-- FIN DE LA MIGRACION