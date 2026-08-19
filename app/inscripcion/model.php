<?php

include_once __DIR__ . '/../../helpers/db.php';
include_once __DIR__ . '/../materia/model.php';
include_once __DIR__ . '/../usuario/model.php';

class Inscripcion extends Model
{
    private Materia $materia;
    private Usuario $usuario;

    public function __construct(array $config = [])
    {
        parent::__construct('inscripcion', $config);
        $this->materia = new Materia($config);
        $this->usuario = new Usuario($config);
    }

    public function getMaterias(): array
    {
        return $this->materia->selectAll('activa = 1 ORDER BY nombre');
    }

    public function getAlumnos(): array
    {
        return $this->usuario->selectAll('activo = 1 ORDER BY apaterno, amaterno, nombre, username');
    }

    public function getAlumnosPorMateria(int $materiaId, string $grupo = '', string $periodo = '', bool $soloActivas = true): array
    {
        $sql = "SELECT i.*, u.username, u.nombre, u.apaterno, u.amaterno, u.grupo AS grupo_alumno,
                       m.clave AS materia_clave, m.nombre AS materia_nombre
                FROM inscripcion i
                INNER JOIN usuario u ON u.id = i.usuario_id
                INNER JOIN materia m ON m.id = i.materia_id
                WHERE i.materia_id = ?";
        $params = [$materiaId];

        if ($grupo !== '') {
            $sql .= ' AND i.grupo = ?';
            $params[] = $grupo;
        }
        if ($periodo !== '') {
            $sql .= ' AND i.periodo = ?';
            $params[] = $periodo;
        }
        if ($soloActivas) {
            $sql .= ' AND i.activa = 1';
        }

        $sql .= ' ORDER BY u.apaterno, u.amaterno, u.nombre, u.username';
        return $this->query($sql, $params);
    }

    public function getMateriasAlumno(int $usuarioId, string $periodo = '', bool $soloActivas = true): array
    {
        $sql = "SELECT i.*, m.clave AS materia_clave, m.nombre AS materia_nombre
                FROM inscripcion i
                INNER JOIN materia m ON m.id = i.materia_id
                WHERE i.usuario_id = ?";
        $params = [$usuarioId];

        if ($periodo !== '') {
            $sql .= ' AND i.periodo = ?';
            $params[] = $periodo;
        }
        if ($soloActivas) {
            $sql .= ' AND i.activa = 1';
        }

        return $this->query($sql . ' ORDER BY i.periodo DESC, m.nombre', $params);
    }

    public function listar(string $grupo = '', string $periodo = '', int $materiaId = 0, bool $soloActivas = true): array
    {
        $sql = "SELECT i.*, u.username, u.nombre, u.apaterno, u.amaterno, u.grupo AS grupo_alumno,
                       m.clave AS materia_clave, m.nombre AS materia_nombre
                FROM inscripcion i
                INNER JOIN usuario u ON u.id = i.usuario_id
                INNER JOIN materia m ON m.id = i.materia_id
                WHERE 1 = 1";
        $params = [];

        if ($materiaId > 0) {
            $sql .= ' AND i.materia_id = ?';
            $params[] = $materiaId;
        }
        if ($grupo !== '') {
            $sql .= ' AND i.grupo = ?';
            $params[] = $grupo;
        }
        if ($periodo !== '') {
            $sql .= ' AND i.periodo = ?';
            $params[] = $periodo;
        }
        if ($soloActivas) {
            $sql .= ' AND i.activa = 1';
        }

        return $this->query($sql . ' ORDER BY i.periodo DESC, m.nombre, u.apaterno, u.amaterno', $params);
    }

    public function inscribir(int $usuarioId, int $materiaId, string $grupo, string $periodo): bool
    {
        if ($usuarioId <= 0 || $materiaId <= 0 || $grupo === '' || $periodo === '') {
            return false;
        }

        $existing = $this->select(
            'usuario_id = ? AND materia_id = ? AND grupo = ? AND periodo = ?',
            [$usuarioId, $materiaId, $grupo, $periodo]
        );
        if ($existing !== null) {
            if ((int)$existing['activa'] === 1) {
                return false;
            }
            $this->update(['activa' => 1], 'id = ?', [$existing['id']]);
            return true;
        }

        $this->insert([
            'usuario_id' => $usuarioId,
            'materia_id' => $materiaId,
            'grupo' => $grupo,
            'periodo' => $periodo,
            'activa' => 1,
        ]);
        return true;
    }

    public function inscribirMasivo(array $usuarioIds, int $materiaId, string $grupo, string $periodo): array
    {
        $nuevos = 0;
        $duplicados = 0;
        foreach (array_unique(array_map('intval', $usuarioIds)) as $usuarioId) {
            if ($this->inscribir($usuarioId, $materiaId, $grupo, $periodo)) {
                $nuevos++;
            } else {
                $duplicados++;
            }
        }
        return ['nuevos' => $nuevos, 'duplicados' => $duplicados];
    }

    public function darDeBaja(int $inscripcionId): bool
    {
        return $this->update(['activa' => 0], 'id = ?', [$inscripcionId]) > 0;
    }
}