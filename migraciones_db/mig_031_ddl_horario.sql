-- ============================================================
-- Migración 031 – DDL: Crear tabla `horario`
-- Commit 4: [DB] Crear tabla horario
-- Fecha: 2026-08-17
-- Descripción:
--   Almacena los bloques de clase (materia + profesor + aula +
--   grupo + día/hora) para cada periodo académico.
-- ============================================================

CREATE TABLE IF NOT EXISTS horario (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    materia_id BIGINT NOT NULL,
    profesor_id BIGINT NOT NULL,              -- FK a usuario.id (usuario con perfil profesor)
    aula_id BIGINT NULL,
    grupo VARCHAR(10) NOT NULL DEFAULT 'A',   -- Ej: "A", "B", "101"
    dia_semana ENUM('lunes','martes','miercoles','jueves','viernes','sabado') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    periodo VARCHAR(30) NOT NULL,             -- Ej: "2026-1", "2026-2", "Verano 2026"
    activo TINYINT NOT NULL DEFAULT 1,
    CONSTRAINT fk_horario_materia FOREIGN KEY (materia_id)
        REFERENCES materia(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_horario_profesor FOREIGN KEY (profesor_id)
        REFERENCES usuario(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_horario_aula FOREIGN KEY (aula_id)
        REFERENCES aula(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT uq_horario_slot UNIQUE (aula_id, dia_semana, hora_inicio, periodo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
