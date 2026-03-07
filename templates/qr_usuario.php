<?php
// Verificar si hay sesión activa para mostrar el QR
if (isset($_SESSION['id'])) {
    $idUsuario = $_SESSION['id'];
    // Usamos el operador de fusión null (??) por si la matrícula no está definida
    $matriculaUsuario = $_SESSION['matricula'] ?? '';

    // Lógica de qr: Si hay matrícula usa 'mat:', si no usa 'id:'
    if (!empty($matriculaUsuario)) {
        $contenidoQR = "mat:" . $matriculaUsuario;
        $textoLegible = "Matrícula: " . $matriculaUsuario;
    } else {
        $contenidoQR = "id:" . $idUsuario;
        $textoLegible = "ID Usuario: " . $idUsuario;
    }
?>

<div class="card text-center shadow-sm mb-4" style="max-width: 300px; margin: 0 auto;">
    <div class="card-header bg-dark text-white">
        <h5 class="card-title mb-0">Mi Pasaporte</h5>
    </div>
    <div class="card-body">
        <!-- Aquí se dibujará el QR -->
        <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
        
        <p class="fw-bold mb-0"><?php echo htmlspecialchars($textoLegible); ?></p>
    </div>
</div>

<!-- Librería QRCode.js (CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // Generar el gráfico QR
    new QRCode(document.getElementById("qrcode"), {
        text: "<?php echo $contenidoQR; ?>",
        width: 180,
        height: 180,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
</script>

<?php
} // Fin del if session
?>
