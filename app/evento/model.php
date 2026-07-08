<?php
include_once __DIR__ . '/../../helpers/db.php';

class Evento extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('evento', config: $config);
    }

    public function __tostring(): string
    {
        return $this->nombre ?? "Evento";
    }
    public function getEventosDisponibles(): array
    {
        date_default_timezone_set('America/Mexico_City');
        $fecha_hora_actual = date('Y-m-d H:i:s');

        $data = $this->selectAll('fecha_hora >= ?', [$fecha_hora_actual]);
        uasort($data, function ($a, $b) {
            $cmp = strcmp($a['fecha_hora'], $b['fecha_hora']);
            if($cmp !== 0) return $cmp;
            return strcmp($a['nombre'], $b['nombre']);
        });
        return $data;
    }

    public function getEventosExpirados(): array
    {
        $data = $this->selectAll('fecha_hora < NOW()', []);
        uasort($data, function ($a, $b) {
            $cmp = strcmp($a['fecha_hora'], $b['fecha_hora']);
            if($cmp !== 0) return $cmp;
            return strcmp($a['nombre'], $b['nombre']);
        });
        return $data;
    }

   public function getEventosPorRol($es_admin): array
    {
        return $this->getEventosDisponibles();
    }
}
