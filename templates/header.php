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
            <div>TICS 2026</div>
        </div>
    </nav>
</header>

<nav class="nav nav-pills nav-justified">
  <a class="nav-link" href="eventos.php">Eventos</a>
  <a class="nav-link" href="usuarios.php">Usuarios</a>
  <a class="nav-link" href="perfiles.php">Perfiles</a>
  <a class="nav-link" href="permisos.php">Permisos</a>
</nav>
