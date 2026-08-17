CREATE TABLE IF NOT EXISTS profesor_disponibilidad (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    profesor_id BIGINT UNSIGNED NOT NULL,
    dia_semana  ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado')
                                NOT NULL,
    hora_inicio TIME            NOT NULL,
    hora_fin    TIME            NOT NULL,
    periodo     VARCHAR(30)     NOT NULL,
    tipo        ENUM('disponible', 'no_disponible')
                                NOT NULL DEFAULT 'disponible',
    notas       VARCHAR(200)    NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_profesor_disp_bloque (profesor_id, dia_semana, hora_inicio, periodo),
    CONSTRAINT fk_profesor_disp_usuario
        FOREIGN KEY (profesor_id) REFERENCES usuario (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ;
