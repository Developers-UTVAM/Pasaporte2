<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../../helpers/db.php';
include_once __DIR__ . '/../evento/model.php';
include_once __DIR__ . '/../usuario/model.php';

class Registro
{
    private Table $tbl;
    private array $config;
    private Evento $evt;
	private Usuario $usr;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->tbl = new Table('registro', $config);
        $this->evt = new Evento();
        $this->usr = new Usuario();
    }

    public function getTodosEventos(): array
    {
        return $this->evt->selectAll();
    }

    public function getGrupos(): array
	{
		$usuarios = $this->usr->selectAll();
		$usuarios = array_filter($usuarios, function ($u) { return $u['activo'] == 1; });
		$grupos   = array_column($usuarios, 'grupo');
		$grupos   = array_filter($grupos, function ($g) { return !empty($g); });
		$grupos   = array_unique($grupos);
		sort($grupos);
 
		return array_map(function ($g) { return ['grupo' => $g]; }, $grupos);
	}
 
	public function getCategorias(): array
	{
		$usuarios   = $this->usr->selectAll();
		$usuarios   = array_filter($usuarios,   function ($u) { return $u['activo'] == 1; });
		$categorias = array_column($usuarios, 'categoria');
		$categorias = array_filter($categorias, function ($c) { return !empty($c); });
		$categorias = array_unique($categorias);
		sort($categorias);
 
		return array_map(function ($c) { return ['categoria' => $c]; }, $categorias);
	}
 
	public function buscarUsuarios(string $busqueda = '', string $grupo = '', string $categoria = ''): array
	{
		$usuarios = $this->usr->selectAll();
		$usuarios = array_filter($usuarios, function ($u) use ($busqueda, $grupo, $categoria) {
			if ($u['activo'] != 1) return false;
			if (!empty($grupo)     && ($u['grupo']     ?? '') !== $grupo)     return false;
			if (!empty($categoria) && ($u['categoria'] ?? '') !== $categoria) return false;
			if (!empty($busqueda)) {
				$b        = mb_strtolower($busqueda);
				$haystack = mb_strtolower(implode(' ', [
					$u['username'] ?? '',
					$u['nombre']   ?? '',
					$u['apaterno'] ?? '',
					$u['amaterno'] ?? '',
				]));
				if (strpos($haystack, $b) === false) return false;
			}
			return true;
		});
 
		return array_values($usuarios);
	}

    public function existeRegistro(int $evento_id, int $usuario_id): bool
    {
        $row = $this->tbl->select(
            "evento_id = ? AND usuario_id = ?",
            [$evento_id, $usuario_id]
        );
        return $row !== null;
    }

    public function crear(int $evento_id, int $usuario_id): bool
    {
        if ($this->existeRegistro($evento_id, $usuario_id)) {
            return false;
        }
        $this->tbl->insert([
            'evento_id'  => $evento_id,
            'usuario_id' => $usuario_id,
        ]);
        return true;
    }

    public function crearMasivo(int $evento_id, array $usuario_ids): array
    {
        $nuevos = 0;
        $duplicados = 0;
        foreach ($usuario_ids as $uid) {
            $uid = intval($uid);
            if ($uid <= 0) continue;
            if ($this->crear($evento_id, $uid)) {
                $nuevos++;
            } else {
                $duplicados++;
            }
        }
        return ['nuevos' => $nuevos, 'duplicados' => $duplicados];
    }

    public function eliminar(int $evento_id, int $usuario_id): int
    {
        return $this->tbl->delete(
            "evento_id = ? AND usuario_id = ?",
            [$evento_id, $usuario_id]
        );
    }

    public function listar(int $evento_id = 0, string $grupo = '', string $categoria = '', string $busqueda = ''): array
    {
        $sql = "SELECT r.evento_id, r.usuario_id, r.fecha_registro,
                       e.nombre AS evento_nombre, e.fecha_hora AS evento_fecha,
                       u.username, u.nombre, u.apaterno, u.amaterno, u.grupo, u.categoria
                FROM registro r
                INNER JOIN evento e ON e.id = r.evento_id
                INNER JOIN usuario u ON u.id = r.usuario_id
                WHERE 1=1";
        $params = [];

        if ($evento_id > 0) {
            $sql .= " AND r.evento_id = ?";
            $params[] = $evento_id;
        }
        if (!empty($grupo)) {
            $sql .= " AND u.grupo = ?";
            $params[] = $grupo;
        }
        if (!empty($categoria)) {
            $sql .= " AND u.categoria = ?";
            $params[] = $categoria;
        }
        if (!empty($busqueda)) {
            $sql .= " AND (u.username LIKE ? OR u.nombre LIKE ? OR u.apaterno LIKE ?)";
            $like = '%' . $busqueda . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY r.fecha_registro DESC";
        return $this->tbl->query($sql, $params);
    }
}
