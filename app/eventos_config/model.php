<?php
include_once __DIR__ . '/../../helpers/db.php';

class PasaporteConfig extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('eventos_config', config: $config);
    }

    public function __toString(): string
    {
        return $this->formatearTipo($this->tipo ?? '');
    }

    public function getAll(): array
    {
        $data = parent::getAll();

        uasort($data, fn($a, $b) =>
            strcmp($a['tipo'] ?? '', $b['tipo'] ?? '')
        );

        return $data;
    }

    private function formatearTipo(string $tipo): string
    {
        return ucfirst(str_replace('_', ' ', $tipo));
    }
}
