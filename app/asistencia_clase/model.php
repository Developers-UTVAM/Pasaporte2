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
}
