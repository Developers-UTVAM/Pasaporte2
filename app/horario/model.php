<?php
include_once __DIR__ . '/../../helpers/db.php';

class Horario extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('horario', config: $config);
    }

    public function __tostring(): string
    {
        return $this->grupo ?? "Horario";
    }

    private function selectEnriquecidoBase(): string
    {
        return "SELECT h.id, h.materia_id, h.profesor_id, h.aula_id, h.grupo,
                       h.dia_semana, h.hora_inicio, h.hora_fin, h.periodo, h.activo,
                       m.nombre AS materia_nombre, m.clave AS materia_clave,
                       m.carrera_id, m.periodo AS materia_periodo,
                       TRIM(CONCAT(u.nombre, ' ', u.apaterno, ' ', IFNULL(u.amaterno, ''))) AS profesor_nombre,
                       a.codigo AS aula_codigo, a.edificio AS aula_edificio
                FROM horario h
                INNER JOIN materia m ON m.id = h.materia_id
                INNER JOIN usuario u ON u.id = h.profesor_id
                LEFT JOIN aula a ON a.id = h.aula_id";
    }

    public function getTodosEnriquecidos(): array
    {
        $sql = $this->selectEnriquecidoBase() . " ORDER BY h.periodo DESC, h.dia_semana, h.hora_inicio";
        return $this->query($sql);
    }

    public function getHorariosPorPeriodo(string $periodo): array
    {
        $sql = $this->selectEnriquecidoBase() . "
                WHERE h.periodo = ? AND h.activo = 1
                ORDER BY h.dia_semana, h.hora_inicio";
        return $this->query($sql, [$periodo]);
    }

    public function getHorariosPorProfesor(int $profesorId, string $periodo): array
    {
        $sql = $this->selectEnriquecidoBase() . "
                WHERE h.profesor_id = ? AND h.periodo = ? AND h.activo = 1
                ORDER BY h.dia_semana, h.hora_inicio";
        return $this->query($sql, [$profesorId, $periodo]);
    }

    public function getHorariosPorCarrera(int $carreraId, string $periodo): array
    {
        $sql = $this->selectEnriquecidoBase() . "
                WHERE m.carrera_id = ? AND h.periodo = ? AND h.activo = 1
                ORDER BY h.dia_semana, h.hora_inicio";
        return $this->query($sql, [$carreraId, $periodo]);
    }

    public function getHorariosPorAula(int $aulaId, string $periodo): array
    {
        $sql = $this->selectEnriquecidoBase() . "
                WHERE h.aula_id = ? AND h.periodo = ? AND h.activo = 1
                ORDER BY h.dia_semana, h.hora_inicio";
        return $this->query($sql, [$aulaId, $periodo]);
    }

    public function verificarDisponibilidad(
        int $aulaId,
        string $dia,
        string $horaInicio,
        string $horaFin,
        string $periodo,
        ?int $excludeId = null
    ): bool {
        $sql = "SELECT COUNT(*) AS conflictos
                FROM horario h
                WHERE h.aula_id = ?
                  AND h.dia_semana = ?
                  AND h.periodo = ?
                  AND h.activo = 1
                  AND h.hora_inicio < ?
                  AND h.hora_fin > ?";
        $params = [$aulaId, $dia, $periodo, $horaFin, $horaInicio];

        if ($excludeId !== null) {
            $sql .= " AND h.id != ?";
            $params[] = $excludeId;
        }

        $result = $this->query($sql, $params);
        $conflictos = $result[0]['conflictos'] ?? 0;

        return intval($conflictos) === 0;
    }

    public function getCargaProfesor(int $profesorId, string $periodo): float
    {
        $sql = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(h.hora_fin, h.hora_inicio))) AS segundos_totales
                FROM horario h
                WHERE h.profesor_id = ? AND h.periodo = ? AND h.activo = 1";

        $result = $this->query($sql, [$profesorId, $periodo]);
        $segundos = $result[0]['segundos_totales'] ?? 0;

        return round(intval($segundos) / 3600, 2);
    }

    public function getHorariosPorAlumno(int $alumnoId, string $periodo): array
    {
        $sql = "SELECT h.id, h.materia_id, h.profesor_id, h.aula_id, h.grupo,
                       h.dia_semana, h.hora_inicio, h.hora_fin, h.periodo, h.activo,
                       m.nombre AS materia_nombre, m.clave AS materia_clave,
                       TRIM(CONCAT(u.nombre, ' ', u.apaterno, ' ', IFNULL(u.amaterno, ''))) AS profesor_nombre,
                       a.codigo AS aula_codigo, a.edificio AS aula_edificio
                FROM inscripcion i
                INNER JOIN horario h ON (h.materia_id = i.materia_id AND h.grupo = i.grupo AND h.periodo = i.periodo)
                INNER JOIN materia m ON m.id = h.materia_id
                INNER JOIN usuario u ON u.id = h.profesor_id
                LEFT JOIN aula a ON a.id = h.aula_id
                WHERE i.usuario_id = ? AND i.periodo = ? AND i.activa = 1 AND h.activo = 1
                ORDER BY h.dia_semana, h.hora_inicio";
        return $this->query($sql, [$alumnoId, $periodo]);
    }
}
