-- ============================================================
-- Migración 030 – DDL: Crear tabla `materia`
-- Commit 3: [DB] Crear tabla materia
-- Fecha: 2026-08-17
-- Descripción:
--   Catálogo de materias/asignaturas vinculadas a una carrera.
--   Cada materia tiene clave única, créditos, horas semanales
--   y cuatrimestre sugerido.
-- ============================================================

CREATE TABLE IF NOT EXISTS materia (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(30) NOT NULL UNIQUE,         -- Ej: "TC1018", "MA2001"
    nombre VARCHAR(200) NOT NULL,              -- Ej: "Estructura de Datos"
    creditos INT UNSIGNED NOT NULL DEFAULT 0,
    horas_semana INT UNSIGNED NOT NULL DEFAULT 0,
    cuatrimestre INT UNSIGNED NULL,             -- Cuatrimestre sugerido (1-12+)
    carrera_id BIGINT NULL,
    activa TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT fk_materia_carrera FOREIGN KEY (carrera_id)
        REFERENCES carrera(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
