<?php
include_once "app/usuario/model.php";
session_start();

date_default_timezone_set('America/Mexico_City');
include_once "helpers/vars.php";

?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">

        <h1 class="my-3">
            Pasaporte TICs: Tu Ruta al Conocimiento
        </h1>

        <h2>¿De qué se trata?</h2>

        <p>
            En el marco de la <strong>Semana de TICs</strong> de la <strong>Universidad Tecnológica de la Zona Metropolitana del Valle de México</strong>,
            presentamos el <strong>Pasaporte TICs</strong>, una innovadora solución digital diseñada por el equipo <a href="creditos.php" ><em>The Tech Pantheon</em></a>.
        </p>

        <h2>¿Cómo funciona?</h2>

        <p>
            El <strong>Pasaporte TICs</strong> es una plataforma de seguimiento dinámico que acompaña a cada estudiante en su recorrido por el
            evento. Olvida los registros tradicionales; con esta herramienta, los alumnos podrán validar su asistencia y
            participación en conferencias, talleres y actividades especiales de manera ágil y moderna.
        </p>

        <h2>Nuestra Misión</h2>

        <p>
            Cada participación cuenta. A través de este "pasaporte digital", los estudiantes pueden:
        </p>

        <ul>
            <li><strong>Registrar su asistencia</strong> a las diversas actividades del calendario.</li>
            <li><strong>Visualizar su progreso</strong> en tiempo real durante la semana.</li>
            <li><strong>Acreditar su participación</strong> académica de forma transparente y eficiente.</li>
        </ul>

        <p>
            Desarrollado bajo la sinergia de nuestros equipos (<strong>Tech Titans</strong>, <strong>Defect Destroyers</strong> y <strong>Mission Accelerators</strong>), este
            proyecto busca integrar la tecnología con la vida universitaria, fomentando el compromiso estudiantil y facilitando
            la gestión administrativa del evento.
        </p>

        <h1 class="mb-4"><span class="colores-gay big-text">¡Prepara tu perfil y comienza tu viaje por la Semana de TICs!</span></h1>

        <h4 class="text-center mb-4" style="color: var(--text-color); font-weight: var(--font-weight-light); opacity: 0.8;">
            Descubre nuestras últimas actividades
        </h4>

        <div class="card p-3 mb-4" style="border-radius: 24px;">
            <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script>
            <iframe src="//lightwidget.com/widgets/ec85b02092e35b879334c0f3b5a05c69.html"
                    scrolling="no"
                    allowtransparency="true"
                    class="lightwidget-widget"
                    style="width:100%; border:0; overflow:hidden;">
            </iframe>
        </div>

        <div class="text-center mt-4">
            <a href="https://www.instagram.com/cybervibe_2026/" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">
                <i class="fab fa-instagram me-2"></i> Síguenos
            </a>
        </div>

        <div style="min-height: 100px;"></div>

    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
