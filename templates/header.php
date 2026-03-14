<script type="text/javascript">
    <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
        let current_user = `<?php echo $_SESSION["current_user"] ?>`;
    <?php else: ?>
        let current_user = `Anonymous`;
    <?php endif; ?>
    console.log("Current User:", current_user)
</script>

<header id="main-header">
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
            <img src="assets/img/utvam_logo_favicon.png" alt="" width="30" height="24" class="d-inline-block align-text-top">
            UTVAM Pasaporte
            </a>
            <div class="d-flex align-items-center">
                <span class="me-3">TICS 2026</span>
                <?php if(isset($_SESSION["current_user"]) && $_SESSION["current_user"]->is_authenticated()): ?>
                    <a href="usuarios.php?accion=logout" title="Cerrar Sesión">
                        <img src="assets/img/Logout.png" alt="Salir" width="24" height="24">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
