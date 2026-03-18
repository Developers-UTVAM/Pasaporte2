<?php
include_once __DIR__ . '/../../helpers/db.php';

class ModeloRecuperacion {
    private $db;

    public function __construct() {
        $this->db = new Table('password_reset');
    }

    public function buscar_usuario_por_identificador($identificador) {
        $usuario_tb = new Table('usuario');
        $resultado = $usuario_tb->select("(username = ? OR email = ?) AND activo = 1", [$identificador, $identificador]);
        if ($resultado) {
            return $resultado['id'];
        }
        return null;
    }

    public function guardar_token($usuario_id, $token, $expira_en) {
        return $this->db->insert(['usuario_id' => $usuario_id, 'token' => $token, 'expira_en' => $expira_en]);
    }

    public function validar_token($token) {
        $resultado = $this->db->select("token = ? AND expira_en > NOW()", [$token]);
        if ($resultado) {
            return $resultado['usuario_id'];
        }
        return null;
    }

    public function actualizar_password($usuario_id, $nuevo_password) {
        $usuario_tb = new Table('usuario');
        $hash = password_hash($nuevo_password, PASSWORD_DEFAULT);
        return $usuario_tb->update(['password' => $hash], 'id = ?', [$usuario_id]);
    }

    public function eliminar_token($token) {
        return $this->db->delete("token = ?", [$token]);
    }
}
