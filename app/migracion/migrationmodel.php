<?php
include_once __DIR__ . '/../../helpers/db.php';

class MigrationModel extends Model {
    private $migrationsPath;

    public function __construct() {
        parent::__construct('migraciones');
        $this->migrationsPath = __DIR__ . '/../../migraciones_db/';
    }

    public function getAllAppliedMigrations() {
        return $this->selectAll("1 ORDER BY id DESC");
    }

    public function getPendingMigrations() {
        $rows = $this->selectAll();
        $applied = array_column($rows, 'archivo');

        if (!is_dir($this->migrationsPath)) {
            return [];
        }

        $files = scandir($this->migrationsPath);
        $pending = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                if (!in_array($file, $applied)) {
                    $pending[] = $file;
                }
            }
        }

        sort($pending);
        return $pending;
    }

    public function executeMigration($filename) {
        $filePath = $this->migrationsPath . $filename;
        
        if (!file_exists($filePath)) {
            throw new Exception("El archivo $filename no existe.");
        }

        $sql = file_get_contents($filePath);
        $dbInstance = Table::getConnection();
        $ref = new ReflectionClass($dbInstance);
        $prop = $ref->getProperty('conn');
        $prop->setAccessible(true);
        /** @var mysqli $mysqli */
        $mysqli = $prop->getValue($dbInstance);

        try {
            $mysqli->begin_transaction();
            
            if (trim($sql) !== '') {
                if ($mysqli->multi_query($sql)) {
                    do {
                        if ($res = $mysqli->store_result()) {
                            $res->free();
                        }
                    } while ($mysqli->more_results() && $mysqli->next_result());
                }
                if ($mysqli->error) {
                    throw new Exception($mysqli->error, $mysqli->errno);
                }
            }
            $this->recordMigration($filename);

            $mysqli->commit();
            return true;
        } catch (Exception $e) {
            $mysqli->rollback();
            
            $errorCode = $e->getCode();
            if (in_array($errorCode, [1050, 1060, 1061, 1062])) {
                $this->markAsSynced($filename);
                return true; 
            }
            throw new Exception("Error en $filename: " . $e->getMessage());
        }
    }
    private function getMigrationMetadata($filename) {
        $type = 'DDL';
        $nombre = $filename;
        $descripcion = 'Ejecución automática por sistema de migraciones';

        //nombre del archivo: mig_NUM_TIPO_NOMBRE.sql
        if (preg_match('/mig_\d+_([a-zA-Z]+)_(.*)\.sql$/i', $filename, $matches)) {
            $type = strtoupper($matches[1]);
            $rawName = str_replace(['_', '-'], ' ', $matches[2]);
            $nombre = ucfirst(trim($rawName));
        }

        $filePath = $this->migrationsPath . $filename;
        if (file_exists($filePath)) {
            $header = file_get_contents($filePath, false, null, 0, 1024);
            if (preg_match('/--\s*NOMBRE:\s*(.*)/i', $header, $m)) {
                $nombre = trim($m[1]);
            }
            if (preg_match('/--\s*DESCRIPCION:\s*(.*)/i', $header, $m)) {
                $descripcion = trim($m[1]);
            }
        }
        if (!in_array($type, ['DDL', 'DML'])) {
            $type = 'DDL';
        }

        return compact('type', 'nombre', 'descripcion');
    }

    private function recordMigration($filename) {
        $meta = $this->getMigrationMetadata($filename);

        $this->tipo = $meta['type'];
        $this->nombre = $meta['nombre'];
        $this->descripcion = $meta['descripcion'];
        $this->archivo = $filename;
        $this->fecha_aplicacion = date('Y-m-d H:i:s');
        
        $this->save();
    }

    private function markAsSynced($filename) {
        try {
            $meta = $this->getMigrationMetadata($filename);
            $meta['descripcion'] .= " (Sincronizado automáticamente por existencia previa)";
            if (!$this->select("archivo = ?", [$filename])) {
                $this->tipo = $meta['type'];
                $this->nombre = $meta['nombre'];
                $this->descripcion = $meta['descripcion'];
                $this->archivo = $filename;
                $this->fecha_aplicacion = date('Y-m-d H:i:s');
                $this->save();
            }
        } catch (Exception $e) {
        }
    }

    public function getMigrationSql($filename) {
        $filePath = $this->migrationsPath . $filename;
        if (!file_exists($filePath)) {
            http_response_code(404);
            return "-- Error 404: El archivo $filename no fue encontrado en el sistema.";
        }

        $content = @file_get_contents($filePath);
        if ($content === false) {
            http_response_code(500);
            return "-- Error 500: No se pudo leer el archivo $filename.";
        }

        http_response_code(200);
        return $content;
    }
}
?>