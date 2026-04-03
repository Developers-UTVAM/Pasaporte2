<?php
$data = [];
$general_issues = [];
if (isset($_FILES["archivo-carga"]) && $_FILES["archivo-carga"]["error"] == 0) {
    if (($file = fopen($_FILES["archivo-carga"]["tmp_name"], "r")) !== false) {
        $row = fgetcsv($file);
        while (($row = fgetcsv($file)) !== false) {
            $issues = [];
            $resultado = true;
            try {
                $evento = new Evento();
                $evento->nombre = $row[0];
                $evento->fecha_hora = DateTime::createFromFormat(
                    'd/m/Y H:i', $row[1])->format('Y-m-d H:i:s');
                $evento->lugar = $row[2];
                $evento->responsable_interno = $row[3];
                $evento->responsable_externo = $row[4];
                $evento->save();
            } catch(Exception $e) {
                $issues[] = $e->getMessage();
                $resultado = false;
            }
            $data[] = [
                "issues" => implode("</li><li>", $issues),
                "evento" => $row[0],
                "lugar" => $row[2],
                "responsable_interno" => $row[3],
                "resultado" => $resultado
            ];
        }
    } else {
        $general_issues[] = "Error al acargar archivo";
    }
}
?>
<h2>Carga masiva de eventos <small class="text-muted">(2 de 2)</small></h2>

<?php foreach ($general_issues as $issue): ?>
<div class="alert alert-danger" role="alert">
    <strong>Error: </strong>
    <?php echo $issue; ?>
</div>
<?php endforeach; ?>

<div class="card mb-3"><div class="card-body"><table id="data-list" class="table table-hover table-sm">
    <thead>
        <tr>
            <th>Evento</th>
            <th>Lugar</th>
            <th>Responsable Interno</th>
            <th>Resultado</th>
            <th>Problemas</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data as $row): ?>
            <tr>
                <td><?php echo $row["evento"]; ?></td>
                <td><?php echo $row["lugar"]; ?></td>
                <td><?php echo $row["responsable_interno"]; ?></td>
                <td class="text-<?php echo $row["resultado"] ? "success" : "danger" ; ?> text-center">
                    <?php
                    echo $row["resultado"] ?
                        '<i class="fa-solid fa-circle-check"></i> Carga correcta'
                        : '<i class="fa-solid fa-circle-xmark"></i> Error al cargar';
                    ?>
                </td>
                <td>
                    <?php echo $row["issues"] ? "<ul><li>" . $row["issues"] . "</li></ul>" : "" ; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table></div></div>
