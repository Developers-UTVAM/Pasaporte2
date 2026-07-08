<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <!-- Suponiendo que el CSS nuevo está en styles.css -->
</head>
<body class="d-flex flex-column vh-100">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column py-5">

        <!-- SECCIÓN 1: Título y Botón (Animación de entrada rápida) -->
        <div class="text-center mb-5 animate-on-load">
            <h1 class="mb-3 display-4 fw-bold">
                <!-- Usamos la nueva clase .shiny-title -->
                <span class="shiny-title">Pasaporte TICs: Tu Ruta al Conocimiento</span>
            </h1>
            <button onclick="toggleTheme()" id="theme-toggle-1" type="button" class="btn btn-action-gradient btn-lg rounded-pill px-5 shadow animate-on-load delay-1">
                <i class="fas fa-palette me-2"></i> Cambiar Experiencia
            </button>
        </div>

        <!-- SECCIÓN 2: Información Principal (Entrada con retraso medio) -->
        <div class="animate-on-load delay-2 mb-5">
            <!-- Aplicamos tu clase .glass-panel y añadimos el pulso de brillo -->
            <div class="card glass-panel p-4 p-md-5 border-0 shadow-lg" style="animation: glowPulse 5s infinite alternate;">

                <h2 class="mb-4 text-primary h1 fw-bold">¿De qué se trata?</h2>
                <p class="lead text-light opacity-75 mb-5">
                    En el marco de la <strong>Semana de TICs</strong> de la <strong>Universidad Tecnológica del Valle de México</strong>,
                    presentamos el <strong>Pasaporte TICs</strong>, una innovadora solución digital diseñada por el equipo <a href="creditos.php" class="fw-bold text-info"><em>The Tech Pantheon</em></a>.
                </p>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <h2 class="mb-3 text-primary">¿Cómo funciona?</h2>
                        <p class="text-light opacity-75">
                            El Pasaporte TICs es una plataforma de seguimiento dinámico que acompaña a cada estudiante. Olvida los registros tradicionales; con esta herramienta agilizamos y modernizamos tu participación.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h2 class="mb-3 text-primary">Nuestra Misión</h2>
                        <p class="text-light opacity-75 mb-0">Integrar la tecnología con la vida universitaria, fomentando el compromiso estudiantil y facilitando la gestión administrativa del evento.</p>
                    </div>
                </div>

                <div class="kpi-card glass-panel border-primary p-4">
                    <div class="kpi-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="kpi-info flex-grow-1">
                        <h4 class="kpi-title mb-3">Beneficios para el estudiante</h4>
                        <ul class="text-light opacity-75 mb-0 row row-cols-1 row-cols-lg-2 g-2">
                            <li><i class="fas fa-check-circle me-2 text-success"></i>Registrar asistencia ágilmente.</li>
                            <li><i class="fas fa-chart-line me-2 text-info"></i>Visualizar progreso en tiempo real.</li>
                            <li><i class="fas fa-certificate me-2 text-warning"></i>Acreditar participación académica.</li>
                            <li><i class="fas fa-user-friends me-2 text-primary"></i>Conectar con equipos de desarrollo.</li>
                        </ul>
                    </div>
                </div>
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
                <h1 class="mb-5 text-primary fw-bold" style="font-size: var(--text-3xl);">Aviso de Privacidad - Pasaporte TICs 2026</h1>

                <!-- Reestructurado para mejor lectura y estilo -->
                <div class="row row-cols-1 row-cols-lg-2 g-4">
                    <div class="col card bg-transparent border-0 p-3">
                        <h2 class="h4 text-secondary fw-bold">1. Responsable</h2>
                        <p class="text-light opacity-75 small">Proyecto Pasaporte TICs 2026 (carrera de TICs & Prof. Rubén Ramírez). Domicilio digital: <a href="http://utvam.imagilex.com.mx/pasaporte">utvam.imagilex.com.mx</a></p>
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
             <p class="text-muted small">Pasaporte TICs. Copyright &copy; 2026. Todos los derechos reservados.</p>
        </div>

    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
