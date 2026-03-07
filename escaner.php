<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: app/auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'templates/head.php'; ?>
    <!-- Librería para el escáner QR -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container">
        <h1 class="text-center my-4">Escáner de Pasaporte</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        Lector de QR
                    </div>
                    <div class="card-body p-0">
                        <!-- Contenedor de la cámara -->
                        <div id="reader" style="width: 100%; min-height: 300px;"></div>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-1">Resultado:</p>
                        <h4 id="resultado" class="fw-bold text-success">Iniciando cámara...</h4>
                        <button id="btn-reiniciar" class="btn btn-primary mt-2" style="display:none;" onclick="reiniciarEscaner()">Escanear Siguiente</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>

    <script>
        let html5QrCode;

        function onScanSuccess(decodedText, decodedResult) {
            // 1. Mostrar el resultado
            document.getElementById('resultado').innerText = decodedText;
            
            // 2. Pausar el escáner para evitar lecturas múltiples del mismo código
            if (html5QrCode) {
                html5QrCode.pause();
            }

            // 3. Mostrar botón para continuar
            document.getElementById('btn-reiniciar').style.display = 'inline-block';

            // 4. Feedback (Alerta temporal)
            alert("¡Código detectado!\n" + decodedText);
        }

        function reiniciarEscaner() {
            document.getElementById('resultado').innerText = "Escaneando...";
            document.getElementById('btn-reiniciar').style.display = 'none';
            html5QrCode.resume();
        }

        // Configuración e inicio automático
        document.addEventListener('DOMContentLoaded', () => {
            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            // Iniciar cámara trasera automáticamente
            html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .catch(err => {
                console.error("Error al iniciar:", err);
                document.getElementById('resultado').innerText = "Error: Permiso de cámara denegado o no encontrada.";
            });
        });
    </script>
</body>
</html>