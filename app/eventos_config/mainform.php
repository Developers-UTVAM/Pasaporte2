<input type="hidden" name="pk" value="<?= htmlspecialchars($object->pk ?? '') ?>" />

<div class="form-floating mb-3">
    <input type="text" required class="form-control" id="tipo" name="tipo"
           placeholder="Clave interna"
           value="<?= htmlspecialchars($object->tipo ?? '') ?>" />
    <label for="tipo">Tipo — clave interna (ej: <code>conferencia</code>)</label>
</div>


<div class="mb-3">
    <label for="icono" class="form-label">Ícono</label>
    <?php
    $iconos = [
        'fa-calendar'           => 'Calendario',
        'fa-chalkboard-user'    => 'Conferencia',
        'fa-screwdriver-wrench' => 'Taller',
        'fa-volleyball'         => 'Deporte',
        'fa-masks-theater'      => 'Cultural',
        'fa-gamepad'            => 'Juegos',
        'fa-trophy'             => 'Concurso',
        'fa-music'              => 'Música',
        'fa-book'               => 'Lectura',
        'fa-flask'              => 'Ciencia',
        'fa-code'               => 'Programación',
        'fa-briefcase'          => 'Laboral',
        'fa-graduation-cap'     => 'Académico',
        'fa-heart'              => 'Salud',
        'fa-star'               => 'Destacado',
        'fa-users'              => 'Grupal',
        'fa-microphone'         => 'Ponencia',
        'fa-palette'            => 'Arte',
        'fa-film'               => 'Cine',
        'fa-camera'             => 'Fotografía',
        'fa-dumbbell'           => 'Gym',
        'fa-futbol'             => 'Fútbol',
        'fa-chess'              => 'Ajedrez',
        'fa-robot'              => 'Robótica',
        'fa-leaf'               => 'Ecología',
        'fa-globe'              => 'Global',
        'fa-handshake'          => 'Acuerdo',
        'fa-lightbulb'          => 'Innovación',
        'fa-comments'           => 'Debate',
        'fa-person-running'     => 'Atletismo',
        'fa-award'              => 'Premio',
        'fa-flag'               => 'Evento especial',
    ];
    $actual = $object->icono ?? 'fa-calendar';
    ?>
    <select name="icono" id="icono" class="form-select">
        <option value="">— Selecciona un ícono —</option>
        <?php foreach ($iconos as $clase => $nombre): ?>
            <option value="<?= $clase ?>" <?= ($actual === $clase) ? 'selected' : '' ?>>
                <?= htmlspecialchars($nombre) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-floating mb-3">
    <input type="number" required min="1" class="form-control" id="requerido" name="requerido"
           value="<?= htmlspecialchars($object->requerido ?? 1) ?>" />
    <label for="requerido">Actividades requeridas para completar</label>
</div>
