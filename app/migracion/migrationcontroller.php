<?php
require_once __DIR__ . '/migrationmodel.php';

class MigrationController {
    private $model;
    private $pdo;

    public function __construct() {
        $configFile = __DIR__ . '/../../configs.php';
        
        if (file_exists($configFile)) {
            require $configFile;
        } else {
            die("Error Crítico: No se encontró el archivo configs.php en la raíz.");
        }
        $dsn = "mysql:host={$db['servidor']};dbname={$db['basededatos']};charset=utf8mb4";
        
        try {
            $this->pdo = new PDO($dsn, $db['usuario'], $db['contrasena']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("<h1>Error de Conexión</h1><p>" . $e->getMessage() . "</p>");
        }

        $this->model = new MigrationModel($this->pdo);
    }

    public function index() {
        $messages = [];
        $errors = [];
        $pending = $this->model->getPendingMigrations();

        if (!empty($pending)) {
            foreach ($pending as $file) {
                try {
                    $this->model->executeMigration($file);
                    $messages[] = "Migración ejecutada: <strong>$file</strong>";
                } catch (Exception $e) {
                    $errors[] = "FALLO en <strong>$file</strong>: " . $e->getMessage();
                    break; 
                }
            }
        }

        $history = $this->model->getAllAppliedMigrations();

        return compact('history', 'messages', 'errors');
    }

    public function getSql($filename) {
        // basename evita que intenten leer archivos fuera de la carpeta (path traversal)
        return $this->model->getMigrationSql(basename($filename));
    }
}
?>