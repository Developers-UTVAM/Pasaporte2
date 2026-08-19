<?php
if(!function_exists("currentUserCan")) {
    include_once __DIR__ . "/../init.php";
}
?><script type="text/javascript">
    <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
        let current_user = <?php echo json_encode((string)$_SESSION["current_user"]); ?>;
    <?php else: ?>
        let current_user = `Anonymous`;
    <?php endif; ?>
    console.log("Current User:", current_user);
</script>

<!-- Precargador Global (Lottie Spinner Overlay) -->
<div id="global-preloader">
    <div class="preloader-content text-center">
        <div id="lottie-spinner" style="width: 150px; height: 150px; margin: 0 auto;"></div>
        <p class="preloader-text mt-3 fw-bold text-light" style="font-size: 1.1rem; letter-spacing: 0.5px;">Cargando...</p>
    </div>
</div>
<script>
    window.lottieSpinnerData = <?php echo file_get_contents(__DIR__ . '/../assets/spinner/spinner.json'); ?>;
    window.brandLogoData = <?php echo file_get_contents(__DIR__ . '/../assets/logo/logo.json'); ?>;

    function renderLottieBrandLogos() {
        if (typeof lottie === 'undefined') return;

        if (window.lottieSpinnerData && document.getElementById('lottie-spinner') && !document.getElementById('lottie-spinner').hasChildNodes()) {
            window.globalPreloaderAnim = lottie.loadAnimation({
                container: document.getElementById('lottie-spinner'),
                renderer: 'svg',
                loop: true,
                autoplay: true,
                animationData: window.lottieSpinnerData
            });
        }

        if (window.brandLogoData) {
            document.querySelectorAll('#brand-lottie-logo, #badge-lottie-logo').forEach(function(container) {
                if (container && !container.querySelector('svg')) {
                    lottie.loadAnimation({
                        container: container,
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        animationData: window.brandLogoData
                    });
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderLottieBrandLogos);
    } else {
        renderLottieBrandLogos();
    }
</script>

<!-- Agregamos header-glass y animate-header -->
<header id="main-header" class="sticky-top header-glass animate-header">
    <nav class="navbar navbar-expand-lg navbar-dark py-2">
        <div class="container-fluid">

            <!-- Marca / Logo con cápsula de cristal brillante -->
            <a class="navbar-brand d-flex align-items-center brand-pill text-decoration-none" href="<?php echo ROOT_URL; ?>index.php">
                <div id="brand-lottie-logo" style="width: 36px; height: 36px;" class="me-2 d-inline-flex align-items-center justify-content-center"></div>
                <span class="shiny-title fw-black tracking-wider brand-title-text fs-5" style="letter-spacing: 0.5px;">PASS<span style="color: var(--primary);">-ID</span></span>
            </a>

            <!-- Botones de Acción (Móvil y Derecha) -->
            <div class="d-flex align-items-center order-lg-last">
                <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
                    <?php if(currentUserCan("otro.restaturar_contraseña")): ?>
                    <a href="<?php echo PAGES_URL; ?>olvide_mi_contrasena.php" class="me-2 me-sm-3 text-white text-decoration-none icon-action-header" title="Cambiar Contraseña">
                        <i class="fa-solid fa-key fs-5"></i>
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo PAGES_URL; ?>logout.php" title="Cerrar Sesión" class="text-decoration-none icon-action-header me-2">
                        <i class="fa-solid fa-right-from-bracket fs-5"></i>
                    </a>
                    <button id="menu-toggler" class="navbar-toggler ms-2 border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#nav-principal" aria-controls="nav-principal" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars text-primary fs-3"></i>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Menú Principal -->
            <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
            <div id="nav-principal" class="collapse navbar-collapse me-lg-4 mt-3 mt-lg-0">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fs-6 fw-bold">

                    <?php if (currentUserCan("otro.update_perfil")): ?>
                    <li class="nav-item text-center px-2">
                        <a href="<?php echo PAGES_URL; ?>mi_perfil.php" class="nav-link nav-link-custom">
                            <i class="fa-solid fa-user-gear mb-1 d-block d-lg-inline"></i> Mi Perfil
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (currentUserCan("asistencia.add_asistencia")): ?>
                        <li class="nav-item text-center px-2">
                            <a href="<?php echo PAGES_URL; ?>asistencia.php" class="nav-link nav-link-custom">
                                <i class="fa-solid fa-check mb-1 d-block d-lg-inline"></i> Asistencia
                        </a></li>
                    <?php endif; ?>

                    <?php if (currentUserCan("otro.autorregistrarse")): ?>
                    <li class="nav-item text-center px-2">
                        <a href="<?php echo PAGES_URL; ?>autoregistro.php" class="nav-link nav-link-custom">
                            <i class="fa-solid fa-user-plus mb-1 d-block d-lg-inline"></i> Eventos
                    </a></li>
                    <?php endif; ?>

                    <?php if (currentUserCan("otro.registrar_en_evento_rapido")): ?>
                        <li class="nav-item text-center px-2">
                            <a href="<?php echo PAGES_URL; ?>registrorapidoevento.php" class="nav-link nav-link-custom">
                                <i class="fa-solid fa-bolt mb-1 d-block d-lg-inline"></i> Registro Rápido
                        </a></li>
                    <?php endif; ?>

                    <?php if(currentUserCan(["otro.registrar_en_evento", "evento.*"])): ?>
                    <li class="nav-item text-center px-2">
                        <div class="dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button">
                                <i class="fa-solid fa-calendar-day mb-1 d-block d-lg-inline"></i> Eventos
                            </a>
                            <!-- Reemplazamos dropdown-menu-dark por nuestra clase de cristal -->
                            <ul class="dropdown-menu dropdown-menu-glass dropdown-menu-end border-0">

                            <?php if (currentUserCan("otro.registrar_en_evento")): ?>
                                <li><a href="<?php echo PAGES_URL; ?>registroevento.php" class="dropdown-item">
                                    <i class="fa-solid fa-user-plus me-2 text-primary"></i> Administrar Registros
                                </a></li>
                            <?php endif; ?>

                            <?php if (currentUserCan("evento.*")): ?>
                                <li><a href="<?php echo PAGES_URL; ?>eventos.php" class="dropdown-item">
                                    <i class="fa-regular fa-calendar-days me-2 text-primary"></i> Eventos
                                </a></li>
                            <?php endif; ?>
                            <li>
                                <a href="<?php echo PAGES_URL; ?>mis_eventos.php" class="dropdown-item">
                                    <i class="fa-solid fa-calendar-check me-2 text-primary"></i> Mis Eventos
                                </a>
                            </li>

                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item text-center px-2">
                        <div class="dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button">
                                <i class="fa-solid fa-graduation-cap mb-1 d-block d-lg-inline"></i> Académico
                            </a>
                            <ul class="dropdown-menu dropdown-menu-glass dropdown-menu-end border-0">

                                <?php if (currentUserCan("horario.view_horario")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>horarios.php" class="dropdown-item">
                                        <i class="fa-regular fa-clock me-2 text-primary"></i> Horarios
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("materia.view_materia")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>materias.php" class="dropdown-item">
                                        <i class="fa-solid fa-book me-2 text-primary"></i> Materias
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("carrera.view_carrera")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>carreras.php" class="dropdown-item">
                                        <i class="fa-solid fa-graduation-cap me-2 text-primary"></i> Carreras
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("aula.view_aula")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>aulas.php" class="dropdown-item">
                                        <i class="fa-solid fa-door-open me-2 text-primary"></i> Aulas
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("horario.manage_disponibilidad")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>disponibilidad.php" class="dropdown-item">
                                        <i class="fa-solid fa-user-clock me-2 text-primary"></i> Disponibilidad
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("usuario.*")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>inscripciones.php" class="dropdown-item">
                                        <i class="fa-solid fa-user-graduate me-2 text-primary"></i> Inscripciones
                                    </a></li>
                                <?php endif; ?>

                                <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                <li><a href="<?php echo PAGES_URL; ?>carga_academica.php" class="dropdown-item">
                                    <i class="fa-solid fa-calendar-week me-2 text-info"></i> Mi Carga Académica
                                </a></li>
                                <li><a href="<?php echo PAGES_URL; ?>escanear_qr.php" class="dropdown-item">
                                    <i class="fa-solid fa-qrcode me-2 text-warning"></i> Pase de Lista (QR)
                                </a></li>
                                <li><a href="<?php echo PAGES_URL; ?>mi_asistencia.php" class="dropdown-item">
                                    <i class="fa-solid fa-chart-line me-2 text-success"></i> Mi Asistencia
                                </a></li>

                            </ul>
                        </div>
                    </li>

                    <?php if(currentUserCan(["migracion.run_migracion", "usuario.*", "perfil.*", "permiso.*", "reporte.*"])): ?>
                    <li class="nav-item text-center px-2">
                        <div class="dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button">
                                <i class="fa-solid fa-screwdriver-wrench mb-1 d-block d-lg-inline"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-glass dropdown-menu-end border-0">

                                <?php if (currentUserCan("migracion.run_migracion")): ?>
                                    <li><a href="<?php echo ROOT_URL; ?>migraciones_db/migrations.php" class="dropdown-item">
                                        <i class="fa-solid fa-database me-2 text-secondary"></i> Migraciones
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("reporte.usuario")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>reporte.php?type=usuario" class="dropdown-item">
                                        <i class="fa-solid fa-users me-2 text-secondary"></i> Reporte Usuarios
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("reporte.evento")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>reporte.php?type=evento" class="dropdown-item">
                                        <i class="fa-solid fa-calendar-day me-2 text-secondary"></i> Reporte Eventos
                                    </a></li>
                                    <li><a href="<?php echo PAGES_URL; ?>reporte.php?type=evento-usuario" class="dropdown-item">
                                        <i class="fa-regular fa-chart-bar me-2 text-secondary"></i> Reporte Evt-Usuario
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("usuario.*")): ?>
                                    <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                    <li><a href="<?php echo PAGES_URL; ?>usuarios.php" class="dropdown-item">
                                        <i class="fa-solid fa-users-gear me-2 text-primary"></i> Usuarios
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("perfil.*")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>perfiles.php" class="dropdown-item">
                                        <i class="fa-solid fa-id-badge me-2 text-primary"></i> Perfiles
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("permiso.*")): ?>
                                    <li><a href="<?php echo PAGES_URL; ?>permisos.php" class="dropdown-item">
                                        <i class="fa-solid fa-unlock-keyhole me-2 text-primary"></i> Permisos
                                    </a></li>
                                <?php endif; ?>

                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>

        </div>
    </nav>
</header>
