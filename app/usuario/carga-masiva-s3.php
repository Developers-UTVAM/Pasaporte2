<?php
$data = [];
$tblperfil = new Table("usuario_tiene_perfil");
$usuarios = getvar("data");
foreach($usuarios as $usr) {
    $usr = explode("||", $usr);
    $row = [];
    try {
        $row["username"] = $usr[0];
        $row["nombre"] = $usr[3] . " " . $usr[4] . " " . $usr[5];
        $usuario = new Usuario();
        $usuario->username = $usr[0];
        $usuario->password = $usr[1];
        $usuario->activo = 1;
        $usuario->superusuario = 0;
        $usuario->nombre = $usr[3];
        $usuario->apaterno = $usr[4];
        $usuario->amaterno = $usr[5];
        $usuario->email = $usr[8];
        $usuario->categoria = $usr[7];
        $usuario->whatsapp = $usr[9];
        $usuario->grupo = $usr[6];
        $usuario->matricula = $usr[2];
        $usuario->save();
        if($usuario->pk) {
            foreach(explode("**", $usr[10]) as $perf_id) {
                $tblperfil->insert(["usuario_id" => $usuario->pk, "perfil_id" => $perf_id]);
            }
            $row["resultado"] = "Usuario agregado correctamente.";
        } else {
            $row["resultado"] = "Error al guardar usuario.";
        }
    } catch(Exception $e) {
        $row["resultado"] = $e->getMessage();
    }
    $data[] = $row;
}
?>
<h2>Carga masiva de usuarios <small class="text-muted">(3 de 3)</small></h2>

<div class="card mb-3"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Resultado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["username"] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row["nombre"] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row["resultado"] ?? ''); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
