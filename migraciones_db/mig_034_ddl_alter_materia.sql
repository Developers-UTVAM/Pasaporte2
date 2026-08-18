
ALTER TABLE materia
    CHANGE COLUMN creditos asistencias INT UNSIGNED NOT NULL DEFAULT 0,
    CHANGE COLUMN cuatrimestre periodo VARCHAR(30) NULL;
