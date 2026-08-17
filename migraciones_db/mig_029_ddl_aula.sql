CREATE TABLE IF NOT EXISTS aula (
    id        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    codigo    VARCHAR(30)     NOT NULL,
    edificio  VARCHAR(100)    NULL,
    capacidad INT UNSIGNED    NULL,
    tipo      ENUM('aula', 'laboratorio', 'auditorio', 'taller', 'otro')
                              NOT NULL DEFAULT 'aula',
    activa    TINYINT(1)      NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY uk_aula_codigo (codigo)
);
