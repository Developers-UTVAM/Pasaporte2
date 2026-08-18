<?php
include_once __DIR__ . '/../../helpers/db.php';

class Materia extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('materia', config: $config);
    }

    public function __tostring(): string
    {
        return $this->nombre ?? "Materia";
    }

    public function getActivas(): array
    {
        return $this->selectAll('activa = 1');
    }

    public function getByCarrera(int $carreraId): array
    {
        return $this->selectAll('carrera_id = ? AND activa = 1', [$carreraId]);
    }

    public function getByCuatrimestre(int $cuatrimestre): array
    {
        return $this->selectAll('cuatrimestre = ? AND activa = 1', [$cuatrimestre]);
    }
}
