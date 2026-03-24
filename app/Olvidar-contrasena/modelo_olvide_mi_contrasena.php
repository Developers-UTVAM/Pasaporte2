<?php
include_once __DIR__ . '/../../helpers/db.php';

class ModeloOlvideMiContrasena {
    private Table $db;

    public function __construct() {
        $this->db = new Table('usuario');
    }

    public function actualizar_password(int $usuario_id, string $nuevo_password): bool {
        $hash = password_hash($nuevo_password, PASSWORD_DEFAULT);
        $afectados = $this->db->update(['password' => $hash], 'id = ?', [$usuario_id]);
        return $afectados > 0;
    }
}
