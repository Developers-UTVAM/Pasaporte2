<?php
if(!function_exists("currentUserCan")) {
    include_once __DIR__ . "/../init.php";
}
?><script type="text/javascript">
    <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
        let current_user = `<?php echo $_SESSION["current_user"] ?>`;
    <?php else: ?>
        let current_user = `Anonymous`;
    <?php endif; ?>
    console.log("Current User:", current_user)
</script>

<header id="main-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">

            <a class="navbar-brand" href="index.php">
            <img src="assets/img/utvam_logo_favicon.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
            UTVAM Pasaporte
            </a>
            <div class="d-flex align-items-center order-lg-last">
                <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
                <button id="menu-toggler" class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#nav-principal" aria-controls="nav-principal" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <?php endif; ?>
                <span class="me-3 colores-gay">TICS 2026</span>
                <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
                    <?php if(currentUserCan("otro.restaturar_contraseña")): ?>
                    <a href="olvide_mi_contrasena.php" class="me-3 text-white" title="Cambiar Contraseña">
                        <i class="fa-solid fa-key fs-5"></i>
                    </a>
                    <?php endif; ?>
                    <a href="logout.php" title="Cerrar Sesión">
                        <img src="assets/img/Logout.png" alt="Salir" width="24" height="24">
                    </a>
                <?php endif; ?>
            </div>

            <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
            <div id="nav-principal" class="collapse navbar-collapse me-4">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fs-5">

                    <?php if (currentUserCan("otro.update_perfil")): ?>
                    <li class="nav-item text-center">
                        <a href="mi_perfil.php" class="nav-link">
                            <i class="fa-solid fa-user-gear"></i>
                            Mi Perfil
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (currentUserCan("asistencia.add_asistencia")): ?>
                        <li class="nav-item text-center"><a href="asistencia.php" class="nav-link">
                            <i class="fa-solid fa-check"></i>
                            Asistencia
                        </a></li>
                    <?php endif; ?>

                    <?php if (currentUserCan("otro.autorregistrarse")): ?>
                    <li class="nav-item text-center"><a href="autoregistro.php" class="nav-link">
                        <i class="fa-solid fa-user-plus"></i>
                        Registrarme en Eventos
                    </a></li>
                    <?php endif; ?>

                    <?php if (currentUserCan("otro.registrar_en_evento_rapido")): ?>
                        <li class="nav-item text-center"><a href="registrorapidoevento.php" class="nav-link">
                            <i class="fa-solid fa-user-plus"></i>
                            Registro Rápido a Eventos
                        </a></li>
                    <?php endif; ?>

                    <?php if(currentUserCan(["otro.registrar_en_evento", "evento.*"])): ?>
                    <li class="nav-item text-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button">
                                <i class="fa-solid fa-calendar-day"></i>
                                Eventos
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">

                            <?php if (currentUserCan("otro.registrar_en_evento")): ?>
                                <li><a href="registroevento.php" class="dropdown-item text-center">
                                    <i class="fa-solid fa-user-plus"></i>
                                    Administrar Registros a Eventos
                                </a></li>
                            <?php endif; ?>

                            <?php if (currentUserCan("evento.*")): ?>
                                <li><a href="eventos.php" class="dropdown-item text-center">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    Eventos
                                </a></li>
                            <?php endif; ?>
                            <li>
                                <a href="mis_eventos.php" class="dropdown-item text-center">
                                    <i class="fa-solid fa-calendar-check"></i>
                                    Mis Eventos
                                </a>
                            </li>

                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>

                    <?php if(currentUserCan(["migracion.run_migracion", "usuario.*", "perfil.*", "permiso.*"])): ?>
                    <li class="nav-item text-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" href="#" role="button">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                                Administración
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">

                                <?php if (currentUserCan("migracion.run_migracion")): ?>
                                    <li><a href="migrations.php" class="dropdown-item text-center">
                                        <i class="fa-solid fa-database"></i>
                                        Migraciones
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("usuario.*")): ?>
                                    <li><a href="usuarios.php" class="dropdown-item text-center">
                                        <i class="fa-solid fa-users"></i>
                                        Usuarios
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("perfil.*")): ?>
                                    <li><a href="perfiles.php" class="dropdown-item text-center">
                                        <span class="fa-stack" style="font-size: 0.7em;">
                                            <i class="fa-brands fa-superpowers fa-stack-2x"></i>
                                            <i class="fa-solid fa-users fa-stack-1x"></i>
                                        </span>
                                        Perfiles
                                    </a></li>
                                <?php endif; ?>

                                <?php if (currentUserCan("permiso.*")): ?>
                                    <li><a href="permisos.php" class="dropdown-item text-center">
                                        <i class="fa-brands fa-superpowers"></i>
                                        Permisos
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
