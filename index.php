<?php
include_once __DIR__ . "/init.php";

startAPI();

if(checkVar("accion", "login")) {
    $username = getvar("username");
    $password = getvar("password");
    if($username && $password) {
        $usr = new Usuario();
        if(!$usr->authenticate($username, $password)) {
            $err = "Error al accesar al sistema: usuario o contraseña no válidos";
        }
    }
}
?><!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
</head>
<body class="d-flex flex-column min-vh-100 <?php echo !(isset($_SESSION['current_user']) && $_SESSION['current_user']) ? 'login-page' : ''; ?>">
    <?php include 'templates/header.php'; ?>

    <main class="container flex-grow-1 d-flex flex-column">

        <?php if(!(isset($_SESSION["current_user"]) && $_SESSION["current_user"])): ?>
            <div class="text-center mt-4 mb-4 animate-on-load">
                <h1 class="shiny-title big-text mb-2" style="font-weight: 900; line-height: 1.1; letter-spacing: -1px;">Semana de TICs 2026</h1>
                <p class="fs-5 mt-2 text-light opacity-75">Inicia sesión para acceder a tu pasaporte digital</p>
            </div>

            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center pb-5 position-relative" style="z-index: 1;">
                <div class="glow-orb glow-orb-1"></div>
                <div class="glow-orb glow-orb-2"></div>

                <div class="card w-100 login-card animate-on-load delay-1">
                    <form id="main-form" method="post" autocomplete="off">
                        <div class="text-center mb-5">
                            <h2 class="fw-black mb-1" style="color:#fff; font-size:1.75rem; letter-spacing:-0.04em;">Iniciar Sesión</h2>
                            <p class="small m-0" style="color:rgba(255,255,255,0.45); letter-spacing:0.01em;">Ingresa tus datos para acceder al portal</p>
                        </div>

                        <?php if(isset($err) && $err):?>
                            <div class="alert alert-dismissible fade show shadow-sm" role="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: var(--color-red-400); border-radius: 16px;">
                                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $err; ?>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php include "app/usuario/form_login.php"; ?>

                        <button type="submit" class="btn-minimal-pill w-100"><i class="fa-solid fa-right-to-bracket me-2"></i> Entrar</button>

                        <a href="<?php echo PAGES_URL; ?>registro.php" class="btn-minimal-pill w-100 mt-3"><i class="fa-solid fa-user-plus me-2"></i> Crear Cuenta</a>

                        <div class="text-center mt-4">
                            <a href="<?php echo PAGES_URL; ?>recuperar-contrasena.php" class="link-plain text-decoration-none small text-white-50" style="font-size: 0.85rem;"><i class="fa-solid fa-key me-1"></i> ¿Olvidaste tu contraseña? Recupérala aquí</a>
                        </div>
                    </form>
                </div>

<div class="w-100 mt-5 mb-5 animate-on-load delay-2" style="max-width: 800px;">

    <h4 class="text-center mb-4" style="color: var(--text-color); font-weight: var(--font-weight-light); opacity: 0.8;">
        Descubre nuestras últimas actividades
    </h4>

    <!-- <div class="card p-3 mb-4" style="border-radius: 24px;">
        <script src="https://cdn.lightwidget.com/widgets/lightwidget.js"></script>
        <iframe src="//lightwidget.com/widgets/ec85b02092e35b879334c0f3b5a05c69.html"
                scrolling="no"
                allowtransparency="true"
                class="lightwidget-widget"
                style="width:100%; border:0; overflow:hidden;">
        </iframe>
    </div> -->

    <div class="text-center mt-4">
        <a href="https://www.instagram.com/cybervibe_2026/" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow">
            <i class="fab fa-instagram me-2"></i> Síguenos
        </a>
    </div>

</div>

        <?php else: ?>
            <?php
            loadModels(["registroevento", "evento"]);
            // Instanciar registro para obtener estadísticas de eventos
            $registro = new Registro();
            $usuario_id = $_SESSION["current_user"]->id;
            $mis_eventos = $registro->listarPorUsuario($usuario_id);
            
            $total_registrados = count($mis_eventos);
            $total_asistidos = 0;
            $siguiente_evento = null;
            $ahora = new DateTime();
            
            foreach ($mis_eventos as $ev) {
                if (isset($ev['asistencia']) && $ev['asistencia'] == 1) {
                    $total_asistidos++;
                }
                
                // Buscar el siguiente evento (el evento futuro más próximo)
                $fecha_evt = new DateTime($ev['fecha_hora']);
                if ($fecha_evt > $ahora) {
                    if ($siguiente_evento === null || $fecha_evt < new DateTime($siguiente_evento['fecha_hora'])) {
                        $siguiente_evento = $ev;
                    }
                }
            }
            
            $porcentaje_asistencia = $total_registrados > 0 ? round(($total_asistidos / $total_registrados) * 100) : 0;
            ?>

            <div class="bento-grid mt-4 mb-5 w-100">
                <!-- 1. Módulo QR (Pasaporte Digital) -->
                <div class="glass-panel bento-card bento-qr bento-delay-1 p-0 overflow-hidden d-flex flex-column align-items-stretch">
                    <!-- Cabecera de la credencial -->
                    <div class="qr-badge-header d-flex justify-content-between align-items-center px-4 py-3" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--glass-border);">
                        <span class="small fw-black text-uppercase tracking-wider text-light opacity-75" style="font-size: 11px; letter-spacing: 1px;"><i class="fa-solid fa-passport me-1 text-primary"></i> PASAPORTE DIGITAL</span>
                        <img src="assets/img/utvam_logo_favicon.png" alt="UTVAM" width="22" height="22" style="object-fit: contain;">
                    </div>
                    <!-- Cuerpo central con el QR -->
                    <div class="qr-badge-body d-flex flex-column align-items-center justify-content-center px-4 py-4 flex-grow-1 position-relative">
                        <div class="qr-container-wrapper p-3 rounded-4 position-relative" style="background: #fff; box-shadow: 0 0 35px rgba(0, 245, 255, 0.25);">
                            <?php
                                $mat = @$_SESSION["current_user"]->matricula;
                                $uid = @$_SESSION["current_user"]->id;
                                $fallback = @$_SESSION["current_user"]->getQrData();
                            ?>
                            <div id="qrcode" class="d-flex justify-content-center position-relative" data-matricula="<?php echo htmlspecialchars((string)$mat); ?>" data-id="<?php echo htmlspecialchars((string)$uid); ?>" data-fallback="<?php echo htmlspecialchars((string)$fallback); ?>"></div>
                        </div>
                        <h4 id="qr-label" class="m-0 font-monospace text-primary fw-bold mt-4 fs-6" style="letter-spacing: 2px; text-shadow: 0 0 10px rgba(37,99,235,0.5);"></h4>
                    </div>
                    <!-- Pie de la credencial -->
                    <div class="qr-badge-footer px-4 py-3 text-center" style="background: rgba(255,255,255,0.01); border-top: 1px solid var(--glass-border);">
                        <div class="fw-bold text-white mb-1 text-truncate" style="font-size: 0.95rem;"><?php echo htmlspecialchars((string)($_SESSION["current_user"] ?? 'Usuario')); ?></div>
                        <div class="text-muted small" style="font-size: 10px; letter-spacing: 1.5px;"><i class="fa-solid fa-id-card-clip me-1 text-primary"></i> MATRÍCULA: <?php echo htmlspecialchars((string)$mat); ?></div>
                    </div>
                </div>

                <!-- 2. Módulo Bienvenida y Estadísticas -->
                <div class="glass-panel bento-card bento-welcome bento-delay-2 p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h2 class="mb-2"><span class="shiny-title fw-bold">¡Hola, <?php echo htmlspecialchars((string)($_SESSION["current_user"] ?? '')); ?>!</span></h2>
                        <p class="text-light opacity-75 fs-6 mb-4">Aquí tienes el resumen de tu participación en la Semana de TICs 2026.</p>
                    </div>

                    <div class="row g-3 text-center mb-2">
                        <div class="col-6">
                            <div class="stat-box p-3 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04);">
                                <i class="fa-regular fa-calendar-check text-primary fs-3 mb-2"></i>
                                <div class="fs-4 fw-black text-white"><?php echo $total_registrados; ?></div>
                                <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">Registrados</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-3 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04);">
                                <i class="fa-solid fa-user-check text-success fs-3 mb-2"></i>
                                <div class="fs-4 fw-black text-white"><?php echo $total_asistidos; ?></div>
                                <div class="text-muted small text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">Asistencias</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Progreso de Asistencia</span>
                            <span><?php echo $total_asistidos; ?> de <?php echo $total_registrados; ?> eventos</span>
                        </div>
                        <div class="progress progress-glass" style="height: 8px; border-radius: 4px; background: rgba(255,255,255,0.05);">
                            <div class="progress-bar progress-bar-neon" role="progressbar" style="width: <?php echo $porcentaje_asistencia; ?>%; border-radius: 4px;" aria-valuenow="<?php echo $porcentaje_asistencia; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- 3. Módulo Próximo Evento -->
                <div class="glass-panel bento-card bento-next bento-delay-3 p-4 d-flex flex-column justify-content-between">
                    <div class="row align-items-center w-100 g-3 m-0">
                        <div class="col-md-8 p-0">
                            <div class="text-muted small text-uppercase mb-2" style="letter-spacing: 1px;"><i class="fa-solid fa-hourglass-half text-secondary me-1"></i> Siguiente Actividad</div>
                            <?php if ($siguiente_evento): ?>
                                <h4 class="text-white fw-bold mb-2 text-truncate-2" style="line-height: 1.3; font-size: 1.15rem;">
                                    <?php echo htmlspecialchars($siguiente_evento['nombre']); ?>
                                </h4>
                                <div class="d-flex flex-row gap-3 mt-2 small text-light opacity-75">
                                    <div><i class="fa-regular fa-clock text-primary me-2"></i> <?php echo htmlspecialchars((new DateTime($siguiente_evento['fecha_hora']))->format('d/m/Y H:i')); ?></div>
                                    <div><i class="fa-solid fa-location-dot text-primary me-2"></i> <?php echo htmlspecialchars($siguiente_evento['lugar'] ?? 'No especificado'); ?></div>
                                </div>
                            <?php else: ?>
                                <h5 class="text-white fw-bold mb-2">Sin eventos próximos</h5>
                                <p class="small text-muted mb-0">No tienes actividades agendadas. ¡Explora el catálogo y regístrate a una!</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 p-0 text-md-end text-center d-flex flex-column gap-2 justify-content-center align-items-md-end align-items-center">
                            <a href="<?php echo PAGES_URL; ?>autoregistro.php" class="btn btn-sm btn-outline-secondary rounded-pill px-4" style="max-width: 200px;"><i class="fa-solid fa-calendar-plus me-1"></i> Ver Catálogo</a>
                            <a href="https://www.instagram.com/cybervibe_2026/" target="_blank" class="text-decoration-none small mt-1" style="color: var(--secondary); text-shadow: 0 0 8px color-mix(in oklab, var(--secondary) 30%, transparent);">
                                <i class="fab fa-instagram me-1"></i> @cybervibe_2026
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <script src="assets/js/qr_generator.js"></script>
        <?php endif; ?>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
