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
        return $this->dia_semana ?? "Disponibilidad";
    }

    public function getDisponibilidad(int $profesorId, string $periodo): array
    {
        $sql = "SELECT id, profesor_id, dia_semana, hora_inicio, hora_fin, periodo, tipo, notas
                FROM profesor_disponibilidad
                WHERE profesor_id = ? AND periodo = ?
                ORDER BY FIELD(dia_semana, 'lunes','martes','miercoles','jueves','viernes','sabado'), hora_inicio";
        return $this->query($sql, [$profesorId, $periodo]);
    }

    public function tieneConflicto(int $profesorId, string $dia, string $horaInicio, string $horaFin, string $periodo): bool
    {
        $sqlHorario = "SELECT COUNT(*) AS conflictos
                FROM horario h
                WHERE h.profesor_id = ?
                  AND h.dia_semana = ?
                  AND h.periodo = ?
                  AND h.activo = 1
                  AND h.hora_inicio < ?
                  AND h.hora_fin > ?";
        $resultHorario = $this->query($sqlHorario, [$profesorId, $dia, $periodo, $horaFin, $horaInicio]);
        if (intval($resultHorario[0]['conflictos'] ?? 0) > 0) {
            return true;
        }

        $sqlNoDisp = "SELECT COUNT(*) AS conflictos
                FROM profesor_disponibilidad pd
                WHERE pd.profesor_id = ?
                  AND pd.dia_semana = ?
                  AND pd.periodo = ?
                  AND pd.tipo = 'no_disponible'
                  AND pd.hora_inicio < ?
                  AND pd.hora_fin > ?";
        $resultNoDisp = $this->query($sqlNoDisp, [$profesorId, $dia, $periodo, $horaFin, $horaInicio]);
        return intval($resultNoDisp[0]['conflictos'] ?? 0) > 0;
    }

    public function getProfesoresDisponibles(string $dia, string $horaInicio, string $horaFin, string $periodo): array
    {
        $sql = "SELECT DISTINCT u.id, u.nombre, u.apaterno, u.amaterno, u.username, u.email
                FROM usuario u
                INNER JOIN usuario_tiene_perfil utp ON utp.usuario_id = u.id
                INNER JOIN perfil p ON p.id = utp.perfil_id
                WHERE p.nombre = 'profesor' AND u.activo = 1
                ORDER BY u.nombre, u.apaterno, u.amaterno";
        $profesores = $this->query($sql, []);

        $disponibles = [];
        foreach ($profesores as $profesor) {
            if (!$this->tieneConflicto((int)$profesor['id'], $dia, $horaInicio, $horaFin, $periodo)) {
                $disponibles[] = $profesor;
            }
        }
        return $disponibles;
    }
}
