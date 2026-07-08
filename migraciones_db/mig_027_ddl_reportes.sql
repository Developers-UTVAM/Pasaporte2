create or replace view reporte_eventos as
select
    e.id,
    e.nombre,
    e.lugar,
    DATE_FORMAT(
        e.fecha_hora,
        '%d/%m/%Y %H:%i'
    ) as fecha_hora,
    e.responsable_interno as responsable,
    count(r.usuario_id) as asistentes_registrados,
    count(a.usuario_id) as usuarios_asistidos
from
    evento e
    left join registro r on r.evento_id = e.id
    left join asistencia a on a.evento_id = r.evento_id
    and a.usuario_id = r.usuario_id
group by
    e.id,
    e.nombre,
    e.lugar,
    e.fecha_hora,
    e.responsable_interno;

create or replace view reporte_usuarios as
select
    u.id,
    u.matricula,
    u.nombre,
    u.apaterno,
    u.amaterno,
    u.grupo,
    count(r.evento_id) as eventos_registrados,
    count(a.evento_id) as eventos_asistidos
from
    usuario u
    left join registro r on r.usuario_id = u.id
    left join asistencia a on a.usuario_id = r.usuario_id
    and a.evento_id = r.evento_id
group by
    u.id,
    u.matricula,
    u.nombre,
    u.apaterno,
    u.amaterno,
    u.grupo;

create or replace view reporte_eventos_detalle as
select
    e.id as e_id,
    e.nombre,
    e.lugar,
    DATE_FORMAT(
        e.fecha_hora,
        '%d/%m/%Y %H:%i'
    ) as fecha_hora,
    e.responsable_interno as responsable,
    DATE_FORMAT(
        r.fecha_registro,
        '%d/%m/%Y %H:%i'
    ) as fecha_registro,
    r.equipo,
    r.usuario_id as u_id,
    DATE_FORMAT(
        a.fecha_entrada,
        '%d/%m/%Y %H:%i'
    ) as fecha_entrada,
    trim(
        concat(
            u.nombre,
            ' ',
            u.apaterno,
            ' ',
            u.amaterno
        )
    ) as registrado_por
from
    evento e
    left join registro r on r.evento_id = e.id
    left join asistencia a on a.evento_id = r.evento_id
    and a.usuario_id = r.usuario_id
    left join usuario u on a.registrado_por = u.id;

create or replace view reporte_evento_usuario as
select
    e_id,
    e.nombre,
    e.lugar,
    e.fecha_hora,
    e.responsable,
    u.id as u_id,
    u.matricula,
    trim(
        concat(
            u.nombre,
            ' ',
            u.apaterno,
            ' ',
            u.amaterno
        )
    ) as usuario,
    u.grupo,
    e.fecha_registro,
    e.fecha_entrada as fecha_asistencia,
    e.registrado_por
from
    reporte_eventos_detalle e
    left join usuario u on e.u_id = u.id
union
select
    e_id,
    e.nombre,
    e.lugar,
    e.fecha_hora,
    e.responsable,
    u.id as u_id,
    u.matricula,
    trim(
        concat(
            u.nombre,
            ' ',
            u.apaterno,
            ' ',
            u.amaterno
        )
    ) as usuario,
    u.grupo,
    e.fecha_registro,
    e.fecha_entrada as fecha_asistencia,
    e.registrado_por
from
    reporte_eventos_detalle e
    right join usuario u on e.u_id = u.id;

INSERT IGNORE INTO `permiso` (`tipo`, `codename`, `nombre`)
VALUES ('reporte', 'usuario', 'Reporte de Usuarios');

INSERT IGNORE INTO `permiso` (`tipo`, `codename`, `nombre`)
VALUES ('reporte', 'evento', 'Reporte de Eventos');
INSERT IGNORE INTO `permiso` (`tipo`, `codename`, `nombre`)
VALUES ('reporte', 'evento-usuario', 'Reporte Evento - Usuario');
