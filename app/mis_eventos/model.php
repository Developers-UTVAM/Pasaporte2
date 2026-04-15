<?php
include_once __DIR__ . '/../../helpers/db.php';

class MiPasaporte
{
    private Table $tblRegistro;
    private Table $tblAsistencia;
    private Table $tblConfig;

    public function __construct()
    {
        $this->tblRegistro   = new Table('registro');
        $this->tblAsistencia = new Table('asistencia');
        $this->tblConfig     = new Table('eventos_config');
    }

    public function getConfig(): array
    {
        $rows   = $this->tblConfig->selectAll();
        $config = [];
        foreach ($rows as $row) {
            $config[strtolower($row['tipo'])] = [
                'icono'     => $row['icono'],
                'requerido' => (int) $row['requerido'],
            ];
        }
        return $config;
    }

    public function getRegistros(int $usuario_id): array
{
    $sql = "SELECT 
                e.id, 
                e.nombre, 
                LOWER(e.tipo) AS tipo, 
                e.fecha_hora, 
                e.lugar, 
                e.responsable_interno,
                e.responsable_externo,
                r.fecha_registro
            FROM registro r
            INNER JOIN evento e ON e.id = r.evento_id
            WHERE r.usuario_id = ?
            ORDER BY e.fecha_hora ASC";

    return $this->tblRegistro->query($sql, [$usuario_id]);
}

    public function getAsistencias(int $usuario_id): array
{
    $sql = "SELECT 
                e.id, 
                e.nombre, 
                LOWER(e.tipo) AS tipo, 
                e.fecha_hora, 
                e.responsable_interno,
                e.responsable_externo,
                a.fecha_entrada
            FROM asistencia a
            INNER JOIN evento e ON e.id = a.evento_id
            WHERE a.usuario_id = ?
            ORDER BY a.fecha_entrada ASC";

    return $this->tblAsistencia->query($sql, [$usuario_id]);
}

    public function getResumen(int $usuario_id): array
    {
        $config      = $this->getConfig();
        $registros   = $this->getRegistros($usuario_id);
        $asistencias = $this->getAsistencias($usuario_id);

        $resumen = [];
        foreach ($config as $tipo => $cfg) {
            $regs = array_filter($registros,   fn($r) => $r['tipo'] === $tipo);
            $asis = array_filter($asistencias, fn($a) => $a['tipo'] === $tipo);
            $resumen[$tipo] = [
                'icono'      => $cfg['icono'],
                'requerido'  => $cfg['requerido'],
                'registrado' => count($regs),
                'asistencia' => count($asis),
                'completo'   => count($asis) >= $cfg['requerido'],
            ];
        }

        return $resumen;
    }
}
