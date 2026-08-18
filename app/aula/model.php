<?php
include_once __DIR__ . '/../../helpers/db.php';

class Aula extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('aula', config: $config);
    }

    public function __tostring(): string
    {
        return $this->codigo ?? "Aula";
    }

    public function getActivas(): array
    {
        return $this->selectAll('activa = 1');
    }

    public function getByTipo(string $tipo): array
    {
        return $this->selectAll('tipo = ? AND activa = 1', [$tipo]);
    }
}
