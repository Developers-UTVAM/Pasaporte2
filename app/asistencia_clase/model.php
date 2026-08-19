<?php
include_once __DIR__ . '/../../helpers/db.php';

class AsistenciaClase extends Model
{
    public function __construct(array $config = [])
    {
        parent::__construct('asistencia_clase', config: $config);
    }

    public function __tostring(): string
    {
        return "AsistenciaClase #{$this->pk}";
    }

    public function marcarAsistenciaQR(int $horarioId, string $identificador, string $fecha): array
    {
        $identificador = trim($identificador);
        $usuario = null;
        $tblUsuario = new Table('usuario');

        if (str_starts_with($identificador, 'mat:')) {
            $matricula = substr($identificador, 4);
            $usuario = $tblUsuario->select('matricula = ?', [$matricula]);
        } elseif (str_starts_with($identificador, 'id:')) {
            $userId = intval(substr($identificador, 3));
            $usuario = $tblUsuario->select('id = ?', [$userId]);
        } else {
            $usuario = $tblUsuario->select('matricula = ? OR id = ?', [$identificador, $identificador]);
        }

        if (!$usuario) {
            return ['success' => false, 'message' => 'Alumno no encontrado con el QR proporcionado.'];
        }

        $usuarioId = intval($usuario['id']);

        $existente = $this->select('horario_id = ? AND usuario_id = ? AND fecha = ?', [$horarioId, $usuarioId, $fecha]);
        if ($existente) {
            $nombreAlumno = trim($usuario['nombre'] . ' ' . $usuario['apaterno'] . ' ' . ($usuario['amaterno'] ?? ''));
            return [
                'success' => false,
                'message' => "El alumno $nombreAlumno ya tiene asistencia registrada para hoy.",
                'usuario' => $nombreAlumno
            ];
        }

        $hora = date('H:i:s');
        $this->fromArray([
            'horario_id' => $horarioId,
            'usuario_id' => $usuarioId,
            'fecha' => $fecha,
            'hora' => $hora,
            'estado' => 'presente',
            'metodo' => 'qr'
        ]);
        $this->save();

        $nombreAlumno = trim($usuario['nombre'] . ' ' . $usuario['apaterno'] . ' ' . ($usuario['amaterno'] ?? ''));

        return [
            'success' => true,
            'message' => "Asistencia registrada correctamente para: $nombreAlumno",
            'usuario' => $nombreAlumno,
            'hora' => date('H:i', strtotime($hora))
        ];
    }

    public function getResumenAlumno(int $usuarioId, string $periodo): array
    {
        $sqlInscripciones = "SELECT i.materia_id, m.nombre AS materia_nombre, m.clave AS materia_clave,
                                   IFNULL(m.asistencias, 0) AS asistencias_requeridas, i.grupo
                            FROM inscripcion i
                            INNER JOIN materia m ON m.id = i.materia_id
                            WHERE i.usuario_id = ? AND i.periodo = ? AND i.activa = 1";
        $inscripciones = $this->query($sqlInscripciones, [$usuarioId, $periodo]);

        $resumen = [];

        foreach ($inscripciones as $row) {
            $materiaId = intval($row['materia_id']);
            $meta = intval($row['asistencias_requeridas']);

            if ($meta <= 0) {
                $meta = 30;
            }

            $sqlAsistencias = "SELECT COUNT(ac.id) AS logradas
                               FROM asistencia_clase ac
                               INNER JOIN horario h ON h.id = ac.horario_id
                               WHERE ac.usuario_id = ?
                                 AND h.materia_id = ?
                                 AND h.periodo = ?
                                 AND ac.estado IN ('presente', 'retardo')";
            $asistData = $this->query($sqlAsistencias, [$usuarioId, $materiaId, $periodo]);
            $logradas = intval($asistData[0]['logradas'] ?? 0);

            $porcentaje = min(100, round(($logradas / $meta) * 100, 1));

            if ($porcentaje >= 80) {
                $color = 'success';
                $badge = 'Cumpliendo';
            } elseif ($porcentaje >= 60) {
                $color = 'warning';
                $badge = 'En Riesgo';
            } else {
                $color = 'danger';
                $badge = 'Crítico';
            }

            $resumen[] = [
                'materia_id' => $materiaId,
                'materia_nombre' => $row['materia_nombre'],
                'materia_clave' => $row['materia_clave'],
                'grupo' => $row['grupo'],
                'asistencias_requeridas' => $meta,
                'asistencias_logradas' => $logradas,
                'porcentaje' => $porcentaje,
                'color' => $color,
                'badge' => $badge
            ];
        }

        return $resumen;
    }

    public function getResumenMateria(int $materiaId, string $periodo): array
    {
        $sql = "SELECT ac.usuario_id,
                       TRIM(CONCAT(u.nombre, ' ', u.apaterno, ' ', IFNULL(u.amaterno, ''))) AS alumno_nombre,
                       u.matricula,
                       COUNT(ac.id) AS logradas
                FROM asistencia_clase ac
                INNER JOIN horario h ON h.id = ac.horario_id
                INNER JOIN usuario u ON u.id = ac.usuario_id
                WHERE h.materia_id = ? AND h.periodo = ? AND ac.estado IN ('presente', 'retardo')
                GROUP BY ac.usuario_id, u.nombre, u.apaterno, u.amaterno, u.matricula
                ORDER BY alumno_nombre";
        return $this->query($sql, [$materiaId, $periodo]);
    }
}
