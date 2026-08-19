-- ============================================================
-- Migracion 036 - DDL: Crear tabla `asistencia_clase`
-- Commit 21/22: Pase de lista por escaneo de codigo QR en clases
-- Descripcion:
--   Almacena el registro de asistencias de alumnos por clase (horario) y fecha.
--   El constraint unico previene registros duplicados en el mismo dia.
-- ============================================================

CREATE TABLE IF NOT EXISTS asistencia_clase (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    horario_id BIGINT NOT NULL,
    usuario_id BIGINT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('presente', 'retardo', 'falta', 'justificado') NOT NULL DEFAULT 'presente',
    metodo ENUM('qr', 'manual') NOT NULL DEFAULT 'qr',
    CONSTRAINT fk_asistclase_horario FOREIGN KEY (horario_id)
        REFERENCES horario(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_asistclase_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT uq_asistclase_sesion UNIQUE (horario_id, usuario_id, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
