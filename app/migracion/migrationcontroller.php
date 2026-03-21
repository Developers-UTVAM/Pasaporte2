<?php
require_once __DIR__ . '/migrationmodel.php';

class MigrationController {
    private $model;

    public function __construct() {
        $this->model = new MigrationModel();
    }

    public function index() {
        $messages = [];
        $errors = [];
        $pending = $this->model->getPendingMigrations();

        if (!empty($pending)) {
            foreach ($pending as $file) {
                try {
                    $migracion = new MigrationModel();
                    $migracion->executeMigration($file);
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
        return $this->model->getMigrationSql(basename($filename));
    }
}
?>