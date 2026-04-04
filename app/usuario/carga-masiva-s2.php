<?php
$data = [];
$usr = new Usuario();
$perfil = new Perfil();
$general_issues = [];
if(isset($_FILES["archivo-carga"]) && $_FILES["archivo-carga"]["error"] == 0){
    if(($file = fopen($_FILES["archivo-carga"]["tmp_name"], "r")) !== false) {
        $row = fgetcsv($file);
        $pbasico = $perfil->select("nombre = ?", ["basico"]);
        $pbasico = $pbasico["id"];
        while(($row = fgetcsv($file)) !== false) {
            $issues = [];
            $skip = false;
            if(!$row[0]) {
                $issues[] = "No se a indicado nombre de usuario";
                $skip = true;
            } else if($usr->select("username = ?", [$row[0]]) !== null) {
                $issues[] = "Ya existe el nombre de usuario";
                $skip = true;
            }
            if(!$row[8]){
                $issues[] = "No se a indicado el email del usuario";
                $skip = true;
            } else if ($usr->select("email = ?", [$row[8]]) !== null) {
                $issues[] = "Ya se ha añadido antes el email del usuario";
                $skip = true;
            }
            if($usr->select("matricula = ?", [$row[0]]) !== null) {
                $issues[] = "Ya se ha asociado la matrícula del usuario";
                $skip = true;
            }
            if(isset($row[10]) && $row[10]) {
                $perfiles = [$pbasico];
                foreach(explode(",", $row[10]) as $perfil_name) {
                    $perfil_name = trim($perfil_name);
                    $perf = $perfil->select("nombre = ?", [$perfil_name]);
                    if($perf) { $perfiles[] = $perf["id"]; }
                    else { $issues[] = "No se encontro el perfil \"$perfil_name\""; }
                }
                $row[10] = implode("**", $perfiles);
            }
            $data[] = [
                "username" => $row[0],
                "nombre" => trim($row[3] . " " . $row[4] . " " . $row[5]),
                "issues" => $issues,
                "skip" => $skip,
                "row" => implode("||", $row)
                ];
        }
    } else {
        $general_issues[] = "Error al acargar archivo";
    }
}
?>
<h2>Carga masiva de usuarios <small class="text-muted">(2 de 3)</small></h2>

<?php foreach($general_issues as $issue): ?>
<div class="alert alert-danger" role="alert">
    <strong>Error: </strong>
    <?php echo $issue; ?>
</div>
<?php endforeach;?>

<form autocomplete="off" method="post" id="main-form">
<div class="card mb-3"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Errores</th>
            <th>Se cargara</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data as $row): ?>
            <tr>
                <td><?php echo $row["username"]; ?></td>
                <td><?php echo $row["nombre"]; ?></td>
                <td>
                    <ul>
                        <?php foreach($row["issues"] as $issue): ?>
                            <li><?php echo $issue; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td class="text-center">
                    <?php if($row["skip"]): ?>
                        <span class="text-danger">
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    <?php else: ?>
                        <span class="text-success">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
<input type="hidden" name="accion" value="add-many-step-3" />
<?php foreach($data as $row): ?>
    <?php if(!$row["skip"]): ?>
        <input type="hidden" name="data[]" value="<?php echo $row["row"]; ?>" />
    <?php endif; ?>
<?php endforeach; ?>
<button type="submit" class="btn btn-outline-primary">
    <i class="fa-regular fa-floppy-disk"></i>
    Guardar
</button>
<a href="usuarios.php" class="btn btn-outline-secondary">
    <i class="fa-regular fa-circle-xmark"></i>
    Cancelar
</a>
</form>
