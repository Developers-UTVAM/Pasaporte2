<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        
        return $this->selectAll('fecha_hora >= ?', [$fecha_hora_actual]);
    }
    
    public function getEventosExpirados(): array
    {
        return $this->selectAll('fecha_hora < NOW()', []);
    }

   public function getEventosPorRol($es_admin): array
    {
        if ($es_admin) {
            return $this->getEventosDisponibles(); 
        } else {
            return $this->getEventosDisponibles(); 
        }
    }
} 