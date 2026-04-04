<?php
include_once __DIR__ . "/init.php";

startAPI("reporte.*");

$data = [];
$title = "Reporte";
$usr_aux = new Usuario();
if(checkVar("type", "usuario") && currentUserCan("reporte.usuario")) {
    $data = $usr_aux->query("SELECT * FROM reporte_usuarios");
    $headers = $data ? array_keys($data[0]) : [];
    unset($headers[0]);
    $headers[5] = "Eventos Registrados";
    $headers[6] = "Eventos Asistidos";
    $title = "Reporte de Usuarios";
    foreach ($data as $idx => $row) {
        $data[$idx]['matricula'] = '<a href="usuarios.php?accion=mostrar&pk=' . $row['id'] . '#data-list">' . htmlspecialchars($row['matricula']) . '</a>';
        unset($data[$idx]['id']);
    }
} elseif (checkVar("type", "evento") && currentUserCan("reporte.evento")) {
    $data = $usr_aux->query("SELECT * FROM reporte_eventos");
    $headers = $data ? array_keys($data[0]) : [];
    unset($headers[0]);
    $headers[3] = "Fecha";
    $headers[5] = "Registros";
    $headers[6] = "Asistencias";
    $title = "Reporte de Eventos";
    foreach ($data as $idx => $row) {
        $data[$idx]['nombre'] = '<a href="eventos.php?accion=mostrar&pk=' . $row['id'] . '#data-list">' . htmlspecialchars($row['nombre']) . '</a>';
        unset($data[$idx]['id']);
    }
} elseif (checkVar("type", "evento-usuario") && currentUserCan("reporte.evento-usuario")) {
    $data = $usr_aux->query("SELECT * FROM reporte_evento_usuario");
    $headers = $data ? array_keys($data[0]) : [];
    unset($headers[5], $headers[4], $headers[2], $headers[0]);
    $headers[1] = "Evento";
    $headers[3] = "Fecha";
    $headers[9] = "Registro";
    $headers[10] = "Asistencia";
    $headers[11] = "Registrado por";
    $title = "Reporte Eventos - Usuario";
    foreach ($data as $idx => $row) {
        if ($row['e_id'] !== null) {
            $data[$idx]['nombre'] = '<a href="eventos.php?accion=mostrar&pk=' . $row['e_id'] . '#data-list">' . htmlspecialchars($row['nombre']) . '</a>';
        }
        if ($row['u_id'] !== null) {
            $data[$idx]['matricula'] = '<a href="usuarios.php?accion=mostrar&pk=' . $row['u_id'] . '#data-list">' . htmlspecialchars($row['matricula']) . '</a>';
        }
        unset($data[$idx]['e_id'], $data[$idx]['u_id'], $data[$idx]['lugar'], $data[$idx]['responsable']);
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(["error" => "Tipo de reporte no especificado o no autorizado."]);
    exit;
}
?><!DOCTYPE html>
<html lang="es-MX">

<head>
    <?php include 'templates/head.php'; ?>
</head>

<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1><?= $title; ?></h1>

        <table id="data-list" class="table table-hover table-sm mb-0">
        <thead>
            <tr>
                <?php foreach ($headers as $header): ?>
                    <th><?= $header; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <td><?= $cell; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php include 'templates/footer.php'; ?>
</body>

</html>
