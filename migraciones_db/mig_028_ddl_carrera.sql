CREATE TABLE IF NOT EXISTS carrera (
    id      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    clave   VARCHAR(20)     NOT NULL,
    nombre  VARCHAR(200)    NOT NULL,
    activa  TINYINT(1)      NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY uk_carrera_clave (clave)
) ;
