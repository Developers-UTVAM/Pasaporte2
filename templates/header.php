<?php
if(!function_exists("currentUserCan")) {
    include_once __DIR__ . "/../init.php";
}
?><script type="text/javascript">
    <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
        let current_user = `<?php echo $_SESSION["current_user"]; ?>`;
    <?php else: ?>
        let current_user = `Anonymous`;
    <?php endif; ?>
    console.log("Current User:", current_user);
</script>

<!-- Agregamos header-glass y animate-header -->
<header id="main-header" class="sticky-top header-glass animate-header">
    <nav class="navbar navbar-expand-lg navbar-dark py-2">
        <div class="container-fluid">

            <!-- Marca / Logo con toque brillante -->
            <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
                <img src="assets/img/utvam_logo_favicon.png" alt="UTVAM" width="42" height="42" class="me-2" style="filter: drop-shadow(0 0 8px color-mix(in oklab, var(--primary) 60%, transparent)); object-fit: contain;">
                <span class="shiny-title d-none d-sm-inline">UTVAM Pasaporte</span>
                <span class="shiny-title d-inline d-sm-none fs-5">UTVAM</span>
            </a>

            <!-- Botones de Acción (Móvil y Derecha) -->
            <div class="d-flex align-items-center order-lg-last">
                <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
                    <?php if(currentUserCan("otro.restaturar_contraseña")): ?>
                    <a href="olvide_mi_contrasena.php" class="me-2 me-sm-3 text-white text-decoration-none icon-action-header" title="Cambiar Contraseña">
                        <i class="fa-solid fa-key fs-5"></i>
                    </a>
                    <?php endif; ?>
                    <a href="logout.php" title="Cerrar Sesión" class="text-decoration-none icon-action-header me-2">
                        <img src="assets/img/Logout.png" alt="Salir" width="26" height="26">
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
                        <a href="mi_perfil.php" class="nav-link nav-link-custom">
                            <i class="fa-solid fa-user-gear mb-1 d-block d-lg-inline"></i> Mi Perfil
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (currentUserCan("asistencia.add_asistencia")): ?>
                        <li class="nav-item text-center px-2">
                            <a href="asistencia.php" class="nav-link nav-link-custom">
                                <i class="fa-solid fa-check mb-1 d-block d-lg-inline"></i> Asistencia
                        </a></li>
                    <?php endif; ?>

                    <?php if (currentUserCan("otro.autorregistrarse")): ?>
                    <li class="nav-item text-center px-2">
                        <a href="autoregistro.php" class="nav-link nav-link-custom">
                            <i class="fa-solid fa-user-plus mb-1 d-block d-lg-inline"></i> Eventos
                    </a></li>
                    <?php endif; ?>

                    <?php if (currentUserCan("otro.registrar_en_evento_rapido")): ?>
                        <li class="nav-item text-center px-2">
                            <a href="registrorapidoevento.php" class="nav-link nav-link-custom">
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
                                <li><a href="registroevento.php" class="dropdown-item">
                                    <i class="fa-solid fa-user-plus me-2 text-primary"></i> Administrar Registros
                                </a></li>
                            <?php endif; ?>

                            <?php if (currentUserCan("evento.*")): ?>
                                <li><a href="eventos.php" class="dropdown-item">
                                    <i class="fa-regular fa-calendar-days me-2 text-primary"></i> Eventos
                                </a></li>
                            <?php endif; ?>
                            <li>
                                <a href="mis_eventos.php" class="dropdown-item">
                                    <i class="fa-solid fa-calendar-check me-2 text-primary"></i> Mis Eventos
                                </a>
                            </li>

                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if(currentUserCan(["migracion.run_migracion", "usuario.*", "perfil.*", "permiso.*", "reporte.*"])): ?>
                    <li class="nav-item text-center px-2">
                        <div class="dropdown">
                            <a class="nav-link nav-link-custom dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button">
                                <i class="fa-solid fa-screwdriver-wrench mb-1 d-block d-lg-inline"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-glass dropdown-menu-end border-0">

                                <?php if (currentUserCan("migracion.run_migracion")): ?>
                                    <li><a href="migrations.php" class="dropdown-item">
                                        <i class="fa-solid fa-database me-2 text-secondary"></i> Migraciones
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("reporte.usuario")): ?>
                                    <li><a href="reporte.php?type=usuario" class="dropdown-item">
                                        <i class="fa-solid fa-users me-2 text-secondary"></i> Reporte Usuarios
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("reporte.evento")): ?>
                                    <li><a href="reporte.php?type=evento" class="dropdown-item">
                                        <i class="fa-solid fa-calendar-day me-2 text-secondary"></i> Reporte Eventos
                                    </a></li>
                                    <li><a href="reporte.php?type=evento-usuario" class="dropdown-item">
                                        <i class="fa-regular fa-chart-bar me-2 text-secondary"></i> Reporte Evt-Usuario
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("usuario.*")): ?>
                                    <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                    <li><a href="usuarios.php" class="dropdown-item">
                                        <i class="fa-solid fa-users-gear me-2 text-primary"></i> Usuarios
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("perfil.*")): ?>
                                    <li><a href="perfiles.php" class="dropdown-item">
                                        <i class="fa-solid fa-id-badge me-2 text-primary"></i> Perfiles
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("permiso.*")): ?>
                                    <li><a href="permisos.php" class="dropdown-item">
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
