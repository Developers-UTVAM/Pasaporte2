-- ============================================================
-- Migracion 035 - DDL: Crear tabla `inscripcion`
-- Commit 19: [DB] Crear tabla inscripcion
-- Descripcion:
--   Registra los alumnos inscritos en una materia, grupo y periodo.
--   El constraint unico evita inscripciones duplicadas.
-- ============================================================

CREATE TABLE IF NOT EXISTS inscripcion (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT NOT NULL,               -- FK a usuario.id (alumno)
    materia_id BIGINT NOT NULL,               -- FK a materia.id
    grupo VARCHAR(10) NOT NULL DEFAULT 'A',
    periodo VARCHAR(30) NOT NULL,             -- Ej: "2026-1"
    fecha_inscripcion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    activa TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT fk_inscripcion_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_inscripcion_materia FOREIGN KEY (materia_id)
        REFERENCES materia(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT uq_inscripcion UNIQUE (usuario_id, materia_id, grupo, periodo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
