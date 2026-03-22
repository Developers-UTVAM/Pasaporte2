<script type="text/javascript">
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
                    <a href="usuarios.php?accion=logout" title="Cerrar Sesión">
                        <img src="assets/img/Logout.png" alt="Salir" width="24" height="24">
                    </a>
                <?php endif; ?>
            </div>

            <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
            <div id="nav-principal" class="collapse navbar-collapse me-4">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fs-5">

                    <?php if ($_SESSION["current_user"]->can("otro.registrar_en_evento_rapido")): ?>
                    <li class="nav-item text-center"><a href="registrorapidoevento.php" class="nav-link">
                        <i class="fa-solid fa-user-plus"></i>
                        Registro Rápido a Eventos
                    <?php endif; ?>

                    <?php if ($_SESSION["current_user"]->can("otro.registrar_en_evento")): ?>
                    <li class="nav-item text-center"><a href="registroevento.php" class="nav-link">
                        <i class="fa-solid fa-user-plus"></i>
                        Administrar Registros a Eventos
                    </a></li>
                    <?php endif; ?>

                    <li class="nav-item text-center"><a href="lector_qr.php" class="nav-link">
                        <i class="fa-solid fa-qrcode"></i>
                        Lector QR
                    </a></li>

                    <?php if ($_SESSION["current_user"]->can("evento.*")): ?>
                    <li class="nav-item text-center"><a href="eventos.php" class="nav-link">
                        <i class="fa-regular fa-calendar-days"></i>
                        Eventos
                    </a></li>
                    <?php endif; ?>

                    <?php if ($_SESSION["current_user"]->can("migracion.run_migracion")): ?>
                        <li class="nav-item text-center"><a href="migrations.php" class="nav-link">
                            <i class="fa-solid fa-database"></i>
                            Migraciones
                        </a></li>
                    <?php endif; ?>

                    <?php if ($_SESSION["current_user"]->can("usuario.*")): ?>
                    <li class="nav-item text-center"><a href="usuarios.php" class="nav-link">
                        <i class="fa-solid fa-users"></i>
                        Usuarios
                    </a></li>
                    <?php endif; ?>

                    <?php if($_SESSION["current_user"]->can("perfil.*")): ?>
                    <li class="nav-item text-center"><a href="perfiles.php" class="nav-link">
                        <span class="fa-stack" style="font-size: 0.7em;">
                            <i class="fa-brands fa-superpowers fa-stack-2x"></i>
                            <i class="fa-solid fa-users fa-stack-1x"></i>
                        </span>
                        Perfiles
                    </a></li>
                    <?php endif; ?>

                    <?php if ($_SESSION["current_user"]->can("permiso.*")): ?>
                    <li class="nav-item text-center"><a href="permisos.php" class="nav-link">
                        <i class="fa-brands fa-superpowers"></i>
                        Permisos
                    </a></li>
                    <?php endif; ?>

                </ul>
            </div>
            <?php endif; ?>

        </div>
    </nav>
</header>
