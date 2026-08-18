<?php
include_once __DIR__ . '/../../helpers/db.php';

class Carrera extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('carrera', config: $config);
    }

    public function __tostring(): string
    {
        return $this->nombre ?? "Carrera";
    }

    public function getActivas(): array
    {
        return $this->selectAll('activa = 1');
    }
}
