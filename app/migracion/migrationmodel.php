<?php
include_once __DIR__ . '/../../helpers/db.php';

class MigrationModel extends Model {
    private $migrationsPath;

    public function __construct() {
        parent::__construct('migraciones');
        $this->migrationsPath = __DIR__ . '/../../migraciones_db/';
    }

    private function getRawConnection() {
        if (file_exists(__DIR__ . '/../../configs.php')) {
            include __DIR__ . '/../../configs.php';
        } else {
            global $db;
        }
        $conn = new mysqli($db['servidor'], $db['usuario'], $db['contrasena'], $db['basededatos']);
        if ($conn->connect_error) {
            throw new Exception("Error al conectar para migración: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
        return $conn;
    }

    public function getAllAppliedMigrations() {
        return $this->query("SELECT * FROM migraciones ORDER BY id DESC");
    }

    public function getPendingMigrations() {
        $rows = $this->query("SELECT archivo FROM migraciones");
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
   
        $mysqli = $this->getRawConnection();
        try {
            $mysqli->begin_transaction();
            
            // Ejecutar el SQL del archivo (DDL/DML) si no está vacío
            if (trim($sql) !== '') {
                if ($mysqli->multi_query($sql)) {
                    do {
                        if ($result = $mysqli->store_result()) {
                            $result->free();
                        }
                        if (!$mysqli->more_results()) {
                            break;
                        }
                    } while ($mysqli->next_result());
                }
                
                if ($mysqli->errno) {
                    throw new Exception($mysqli->error, $mysqli->errno);
                }
            }
            // Registrar la migración automáticamente en la base de datos
            $this->recordMigration($filename, $mysqli);

            $mysqli->commit();
            $mysqli->close();
            return true;
        } catch (Exception $e) {
            $mysqli->rollback();
            $mysqli->close();
            
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
            $type = strtoupper($matches[1]); // DDL o DML
            // Convertir 'usuario_upd' a 'Usuario upd'
            $rawName = str_replace(['_', '-'], ' ', $matches[2]);
            $nombre = ucfirst(trim($rawName));
        }

        // Opcional: Buscar etiquetas de comentarios en el archivo para sobrescribir
        $filePath = $this->migrationsPath . $filename;
        if (file_exists($filePath)) {
            // Leemos solo el inicio del archivo para buscar cabeceras
            $header = file_get_contents($filePath, false, null, 0, 1024);
            if (preg_match('/--\s*NOMBRE:\s*(.*)/i', $header, $m)) {
                $nombre = trim($m[1]);
            }
            if (preg_match('/--\s*DESCRIPCION:\s*(.*)/i', $header, $m)) {
                $descripcion = trim($m[1]);
            }
        }

        // Validar tipo permitido
        if (!in_array($type, ['DDL', 'DML'])) {
            $type = 'DDL';
        }

        return compact('type', 'nombre', 'descripcion');
    }

    private function recordMigration($filename, $mysqli) {
        $meta = $this->getMigrationMetadata($filename);
        
        $stmt = $mysqli->prepare("INSERT INTO migraciones (tipo, nombre, descripcion, archivo, fecha_aplicacion) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param('ssss', $meta['type'], $meta['nombre'], $meta['descripcion'], $filename);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error preparando registro de migración: " . $mysqli->error);
        }
    }

    private function markAsSynced($filename) {
        $mysqli = $this->getRawConnection();
        
        try {
            $meta = $this->getMigrationMetadata($filename);
            $meta['descripcion'] .= " (Sincronizado automáticamente por existencia previa)";

            $stmt = $mysqli->prepare("INSERT IGNORE INTO migraciones (tipo, nombre, descripcion, archivo, fecha_aplicacion) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param('ssss', $meta['type'], $meta['nombre'], $meta['descripcion'], $filename);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            // Si falla la sincronización, no interrumpimos el flujo principal
        }
        $mysqli->close();
    }

    public function getMigrationSql($filename) {
        $filePath = $this->migrationsPath . $filename;
        if (!file_exists($filePath)) {
            return "-- El archivo $filename no fue encontrado en el sistema.";
        }
        return file_get_contents($filePath);
    }
}
?>