<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Mexico_City');

include_once __DIR__ . '/../../helpers/db.php';
include_once __DIR__ . '/../evento/model.php';
include_once __DIR__ . '/../usuario/model.php';

class Asistencia
{
    private Table $tbl;
    private array $config;
    private Evento $evt;
    private Usuario $usr;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->tbl = new Table('asistencia', $config);
        $this->evt = new Evento();
        $this->usr = new Usuario();
    }

    public function verificarRegistro(int $evento_id, int $usuario_id): bool
    {
        $tblRegistro = new Table('registro', $this->config);
        $row = $tblRegistro->select(
            "evento_id = ? AND usuario_id = ?",
            [$evento_id, $usuario_id]
        );
        return $row !== null;
    }

    public function existeAsistencia(int $evento_id, int $usuario_id): bool
    {
        $row = $this->tbl->select(
            "evento_id = ? AND usuario_id = ?",
            [$evento_id, $usuario_id]
        );
        return $row !== null;
    }

    public function marcarAsistencia(int $evento_id, int $usuario_id, int $admin_id): bool
    {
        if ($this->existeAsistencia($evento_id, $usuario_id)) {
            return false;
        }

        $this->tbl->insert([
            'evento_id'      => $evento_id,
            'usuario_id'     => $usuario_id,
            'registrado_por' => $admin_id,
            'fecha_entrada'  => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function autoRegistrarYAsistir(int $evento_id, int $usuario_id, int $admin_id): bool
    {
        $tblRegistro = new Table('registro', $this->config);

        if (!$this->verificarRegistro($evento_id, $usuario_id)) {
            $tblRegistro->insert([
                'evento_id' => $evento_id,
                'usuario_id' => $usuario_id
            ]);
        }

        return $this->marcarAsistencia($evento_id, $usuario_id, $admin_id);
    }

    public function eliminarAsistencia(int $evento_id, int $usuario_id): int
    {
        return $this->tbl->delete(
            "evento_id = ? AND usuario_id = ?",
            [$evento_id, $usuario_id]
        );
    }

    public function listar(int $evento_id = 0, string $grupo = '', string $busqueda = ''): array
    {
        $sql = "SELECT a.evento_id, a.usuario_id, a.fecha_entrada, a.registrado_por,
                       e.nombre AS evento_nombre,
                       u.username, u.nombre, u.apaterno, u.amaterno, u.grupo, u.matricula,
                       admin.nombre AS admin_nombre
                FROM asistencia a
                INNER JOIN evento e ON e.id = a.evento_id
                INNER JOIN usuario u ON u.id = a.usuario_id
                LEFT JOIN usuario admin ON admin.id = a.registrado_por
                WHERE 1=1";

        $params = [];

        if ($evento_id > 0) {
            $sql .= " AND a.evento_id = ?";
            $params[] = $evento_id;
        }
        if (!empty($grupo)) {
            $sql .= " AND u.grupo = ?";
            $params[] = $grupo;
        }
        if (!empty($busqueda)) {
            $sql .= " AND (u.nombre LIKE ? OR u.apaterno LIKE ? OR u.matricula LIKE ?)";
            $like = '%' . $busqueda . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY a.fecha_entrada DESC";
        return $this->tbl->query($sql, $params);
    }

    public function getTodayAttendanceCount(): int
    {
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(usuario_id) as total FROM asistencia WHERE DATE(fecha_entrada) = ?";
        $result = $this->tbl->query($sql, [$today]);
        return (int)($result[0]['total'] ?? 0);
    }

    public function getLatestAttendance(): ?array
    {
        $sql = "SELECT u.nombre, u.apaterno, a.fecha_entrada
                FROM asistencia a
                INNER JOIN usuario u ON u.id = a.usuario_id
                ORDER BY a.fecha_entrada DESC
                LIMIT 1";
        $result = $this->tbl->query($sql);
        return $result[0] ?? null;
    }

    // --- HELPERS PARA SELECTORES ---

    public function getTodosEventos(): array
    {
        return $this->evt->selectAll();
    }

    public function getGrupos(): array
    {
        $sql = "SELECT DISTINCT grupo FROM usuario WHERE grupo IS NOT NULL AND grupo != '' ORDER BY grupo ASC";
        return $this->tbl->query($sql);
    }

    public function getCategorias(): array
    {
        $sql = "SELECT DISTINCT categoria FROM usuario WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria ASC";
        return $this->tbl->query($sql);
    }
}
