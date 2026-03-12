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
}