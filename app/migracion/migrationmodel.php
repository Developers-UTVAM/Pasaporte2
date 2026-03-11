<?php

class MigrationModel {
    private $db;
    private $migrationsPath;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->migrationsPath = __DIR__ . '/../../migraciones_db/';
    }

    public function getAllAppliedMigrations() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM migraciones ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getPendingMigrations() {
        $applied = [];
        try {
            $stmt = $this->db->prepare("SELECT archivo FROM migraciones");
            $stmt->execute();
            $applied = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $applied = [];
        }

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

        try {
            $this->db->beginTransaction();
            $this->db->exec($sql);
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            
            $errorCode = $e->errorInfo[1] ?? 0;
            if (in_array($errorCode, [1050, 1060, 1061, 1062])) {
                $this->markAsSynced($filename);
                return true; 
            }

            throw new Exception("Error en $filename: " . $e->getMessage());
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error en $filename: " . $e->getMessage());
        }
    }

    private function markAsSynced($filename) {
        $filePath = $this->migrationsPath . $filename;
        if (!file_exists($filePath)) {
            // Fallback por seguridad, aunque executeMigration ya debería haberlo detectado.
            $stmt = $this->db->prepare("INSERT IGNORE INTO migraciones (tipo, nombre, descripcion, archivo, fecha_aplicacion) VALUES ('Error', 'Archivo no encontrado', 'Se intentó sincronizar un archivo de migración que no existe.', ?, NOW())");
            $stmt->execute([$filename]);
            return;
        }

        $sql = file_get_contents($filePath);
        $pattern = "/INSERT\s+INTO\s+`?migraciones`?.*?VALUES\s*\(\s*'([^']*)'\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*\)/is";

        if (preg_match($pattern, $sql, $matches)) {
            $tipo = $matches[1];
            $nombre = $matches[2];
            $descripcion = $matches[3] . " (Sincronizado automáticamente por existencia previa)";
            $archivo = $matches[4];

            $stmt = $this->db->prepare("INSERT IGNORE INTO migraciones (tipo, nombre, descripcion, archivo, fecha_aplicacion) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$tipo, $nombre, $descripcion, $archivo]);
        } else {
            $stmt = $this->db->prepare("INSERT IGNORE INTO migraciones (tipo, nombre, descripcion, archivo, fecha_aplicacion) VALUES ('Mmto', 'Sincronizacion Automatica', 'Marcado como completado al detectar existencia previa (no se pudo parsear el SQL original).', ?, NOW())");
            $stmt->execute([$filename]);
        }
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