<?php
include_once __DIR__ . '/../../helpers/db.php';

class ProfesorDisponibilidad extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('profesor_disponibilidad', config: $config);
    }

    public function __tostring(): string
    {
        return $this->dia_semana ? ucfirst($this->dia_semana) . " (" . $this->hora_inicio . " - " . $this->hora_fin . ")" : "Disponibilidad";
    }

    private function selectEnriquecidoBase(): string
    {
        return "SELECT pd.*,
                       TRIM(CONCAT(u.nombre, ' ', u.apaterno, ' ', IFNULL(u.amaterno, ''))) AS profesor_nombre,
                       u.email AS profesor_email
                FROM profesor_disponibilidad pd
                INNER JOIN usuario u ON u.id = pd.profesor_id";
    }

    public function getTodasEnriquecidas(): array
    {
        $sql = $this->selectEnriquecidoBase() . " ORDER BY pd.periodo DESC, pd.dia_semana, pd.hora_inicio";
        return $this->query($sql);
    }

    public function getDisponibilidadProfesor(int $profesorId, ?string $periodo = null): array
    {
        $sql = $this->selectEnriquecidoBase() . " WHERE pd.profesor_id = ?";
        $params = [$profesorId];

        if ($periodo !== null && $periodo !== '') {
            $sql .= " AND pd.periodo = ?";
            $params[] = $periodo;
        }

        $sql .= " ORDER BY pd.dia_semana, pd.hora_inicio";
        return $this->query($sql, $params);
    }

    public function tieneConflicto(
        int $profesorId,
        string $dia,
        string $horaInicio,
        string $horaFin,
        string $periodo,
        ?int $excludeId = null
    ): bool {
        $sql = "SELECT COUNT(*) AS conflictos
                FROM profesor_disponibilidad pd
                WHERE pd.profesor_id = ?
                  AND pd.dia_semana = ?
                  AND pd.periodo = ?
                  AND pd.hora_inicio < ?
                  AND pd.hora_fin > ?";
        $params = [$profesorId, $dia, $periodo, $horaFin, $horaInicio];

        if ($excludeId !== null) {
            $sql .= " AND pd.id != ?";
            $params[] = $excludeId;
        }

        $result = $this->query($sql, $params);
        return intval($result[0]['conflictos'] ?? 0) > 0;
    }
}
