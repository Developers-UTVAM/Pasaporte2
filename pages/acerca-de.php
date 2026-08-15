<?php include_once __DIR__ . "/../init.php"; ?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <!-- Suponiendo que el CSS nuevo está en styles.css -->
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">

        <div class="text-center my-4">
            <h1 class="fw-bold mb-2">
                <i class="fa-solid fa-passport me-2" style="color: var(--primary);"></i>
                PASS-ID TICs
            </h1>
            <h4 style="color: var(--text-color); opacity: 0.8; font-weight: var(--font-weight-light);">
            PASS-ID TICs: Tu Ruta al Conocimiento
            </h4>
        </div>

        <div class="row mb-4">
            <div class="col text-center">
                <button onclick="toggleTheme()" id="theme-toggle-1" type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fa-solid fa-palette me-1"></i>
                    UI_THEME_SELECT
                </button>
            </div>
        </div>

        <div class="card p-4 p-md-5 shadow-lg glass-panel mx-auto mb-5" style="max-width: 900px; border-radius: 24px;">

            <h2 class="mb-3" style="color: var(--primary);"><i class="fa-solid fa-circle-question me-2 opacity-75"></i>¿De qué se trata?</h2>
            <p class="fs-5 mb-4" style="color: var(--text-color); opacity: 0.85; line-height: 1.7;">
                En el marco de la <strong style="color: var(--secondary);">Semana de TICs</strong> de la <strong>Universidad Tecnológica de la Zona Metropolitana del Valle de México</strong>,
                presentamos el <strong>PASS-ID TICs</strong>, una innovadora solución digital diseñada por el equipo <a href="<?php echo PAGES_URL; ?>creditos.php" class="text-decoration-none fw-bold" style="color: var(--primary);"><em>The Tech Pantheon</em></a>.
            </p>

            <h2 class="mb-3 mt-2" style="color: var(--primary);"><i class="fa-solid fa-gears me-2 opacity-75"></i>¿Cómo funciona?</h2>
            <p class="fs-5 mb-4" style="color: var(--text-color); opacity: 0.85; line-height: 1.7;">
                El <strong>PASS-ID TICs</strong> es una plataforma de seguimiento dinámico que acompaña a cada estudiante en su recorrido por el
                evento. Olvida los registros tradicionales; con esta herramienta, los alumnos podrán validar su asistencia y
                participación en conferencias, talleres y actividades especiales de manera ágil y moderna.
            </p>

            <h2 class="mb-3 mt-2" style="color: var(--primary);"><i class="fa-solid fa-bullseye me-2 opacity-75"></i>Nuestra Misión</h2>
            <p class="fs-5 mb-3" style="color: var(--text-color); opacity: 0.85; line-height: 1.7;">
                Cada participación cuenta. A través de este "PASS-ID digital", los estudiantes pueden:
            </p>

            <ul class="list-unstyled fs-5 mb-4 ms-2 ms-md-4" style="color: var(--text-color); opacity: 0.9;">
                <li class="mb-2"><i class="fa-solid fa-check-circle text-success me-2"></i> <strong>Registrar su asistencia</strong> a las diversas actividades del calendario.</li>
                <li class="mb-2"><i class="fa-solid fa-check-circle text-success me-2"></i> <strong>Visualizar su progreso</strong> en tiempo real durante la semana.</li>
                <li class="mb-2"><i class="fa-solid fa-check-circle text-success me-2"></i> <strong>Acreditar su participación</strong> académica de forma transparente y eficiente.</li>
            </ul>

            <p class="fs-5 mb-4" style="color: var(--text-color); opacity: 0.85; line-height: 1.7;">
                Desarrollado bajo la sinergia de nuestros equipos (<strong>Tech Titans</strong>, <strong>Defect Destroyers</strong> y <strong>Mission Accelerators</strong>), este
                proyecto busca integrar la tecnología con la vida universitaria, fomentando el compromiso estudiantil y facilitando
                la gestión administrativa del evento.
            </p>

            <hr style="border-color: var(--glass-border); margin: 2rem 0;">

            <h2 class="text-center mb-0 mt-3" style="line-height: 1.3;"><span class="colores-gay fw-black">¡Prepara tu perfil y comienza tu viaje por la Semana de TICs!</span></h2>

        </div>

        <p class="text-center mb-2" style="color: var(--text-color); opacity: 0.6; font-size: 0.9rem;">PASS-ID TICs. Copyright &copy; <?php echo date("Y"); ?>. Todos los derechos reservados.</p>

        <div class="row mb-5">
            <div class="col text-center">
                <button onclick="toggleTheme()" id="theme-toggle-2" type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fa-solid fa-palette me-1"></i>
                    UI_THEME_SELECT
                </button>
            </div>
        </div>

        <!-- SECCIÓN 3: Banner Intermedio (Animación de entrada lenta) -->
        <div class="text-center mb-5 animate-on-load delay-3">
            <h2 class="mb-0">
                <!-- Usamos tu clase big-text combinada con el brillo -->
                <span class="shiny-title big-text fw-black opacity-90">¡Prepara tu perfil y comienza tu viaje!</span>
            </h2>
        </div>

        <!-- SECCIÓN 4: Aviso de Privacidad (Entrada final) -->
        <div class="animate-on-load delay-4 mb-5">
            <div class="card glass-panel p-4 p-md-5 border-0 shadow-lg">
                <h1 class="mb-5 text-primary fw-bold" style="font-size: var(--text-3xl);">Aviso de Privacidad - PASS-ID TICs 2026</h1>

                <!-- Reestructurado para mejor lectura y estilo -->
                <div class="row row-cols-1 row-cols-lg-2 g-4">
                    <div class="col card bg-transparent border-0 p-3">
                        <h2 class="h4 text-secondary fw-bold">1. Responsable</h2>
                        <p class="text-light opacity-75 small">Proyecto PASS-ID TICs 2026 (carrera de TICs & Prof. Rubén Ramírez). Domicilio digital: <a href="http://utvam.imagilex.com.mx/pasaporte">utvam.imagilex.com.mx</a></p>
                    </div>
                    <div class="col card bg-transparent border-0 p-3">
                        <h2 class="h4 text-secondary fw-bold">2. Datos Recabados</h2>
                        <p class="text-light opacity-75 small">Matrícula, Nombre, Carrera, Grupo, Correo, Teléfono, Registros de asistencia e IP (seguridad).</p>
                    </div>
                    <div class="col card bg-transparent border-0 p-3">
                        <h2 class="h4 text-secondary fw-bold">3. Finalidad</h2>
                        <p class="text-light opacity-75 small">Gestión de acceso, control de asistencia, reportes de acreditación para UTVAM y estadística anonimizada.</p>
                    </div>
                    <div class="col card bg-transparent border-0 p-3">
                        <h2 class="h4 text-secondary fw-bold">4. Derechos ARCO</h2>
                        <p class="text-light opacity-75 small">Solicita rectificación o eliminación enviando correo a: <strong class="text-info">r.ramirez@utvam.edu.mx</strong>.</p>
                    </div>
                </div>

                <hr class="border-secondary opacity-25 my-4">
                <p class="text-muted small mb-0 text-center">Consulta la política completa en el portal oficial.</p>
            </div>
        </div>

        <!-- Footer interno -->
        <div class="text-center mt-auto pb-4 animate-on-load delay-4 opacity-50">
             <p class="text-muted small">PASS-ID TICs. Copyright &copy; 2026. Todos los derechos reservados.</p>
        </div>

    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
