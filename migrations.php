
<?php
include_once "app/usuario/model.php";
session_start();

include_once 'helpers/vars.php';

if (!isset($_SESSION["current_user"]) || !$_SESSION["current_user"]->can("migracion.*")) {
    header("Location: index.php");
    exit();
}

require_once 'app/migracion/migrationcontroller.php';
$controller = new MigrationController();

$accion = getvar('accion') ?? getvar('action');

if ($accion === 'view_sql' && getvar('file')) {
    echo $controller->getSql(getvar('file'));
    exit;
}

$data = $controller->index();
extract($data);
?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="flex-grow-1">
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="m-0"><i class="fa-solid fa-database"></i> Control de Migraciones</h2>
                <a href="migrations.php" class="btn btn-primary">
                    <i class="fa-solid fa-rotate"></i> Sincronizar
                </a>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading"><i class="fa-solid fa-triangle-exclamation"></i> Error Crítico</h4>
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= $err ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="mb-0">El proceso se detuvo. Revise la base de datos.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($messages)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading"><i class="fa-solid fa-check-circle"></i> Base de Datos Actualizada</h4>
                    <ul>
                        <?php foreach ($messages as $msg): ?>
                            <li><?= $msg ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($messages) && empty($errors)): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-info"></i> No se detectaron migraciones nuevas pendientes. Su sistema está al día.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($accion === 'listar' || $accion === null || $accion === ''): ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0">Historial de Ejecución</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="data-list" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Archivo</th>
                            <th>Nombre / Acción</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                            <th>Fecha Aplicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td class="font-monospace text-primary"><?= htmlspecialchars($row['archivo']) ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nombre']) ?></td>
                                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['tipo']) ?></span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary view-sql-btn" data-file="<?= htmlspecialchars($row['archivo']) ?>" title="Ver SQL"><i class="fa-solid fa-code"></i></button>
                                </td>
                                <td><?= htmlspecialchars($row['fecha_aplicacion']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sqlModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Contenido SQL: <span id="sqlModalTitle" class="fw-bold fs-6 font-monospace"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light">
        <pre><code id="sqlModalContent" class="language-sql" style="white-space: pre-wrap;"></code></pre>
      </div>
    </div>
  </div>
</div>

<script src="assets/js/migration.js"></script>

    <?php endif; ?>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>