<?php
// Opcional: Incluir el header de tu sistema para mantener el diseño
include_once __DIR__ . '/../../templates/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-primary shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vista Temporal de Testeo QR</h5>
                    <span class="badge bg-light text-primary">Modo Desarrollador</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Usa esta vista para verificar la decodificación de prefijos (mat: o id:).</p>
                    
                    <div id="reader" class="bg-light" style="border-radius: 15px; overflow: hidden;"></div>
                    
                    <hr>
                    
                    <div class="alert alert-secondary mt-3">
                        <h6> Datos Crutos:</h6>
                        <div id="raw-data" class="fw-bold text-dark text-break">Esperando escaneo...</div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <label>Input Destino (Simulado):</label>
                            <input type="text" id="nombre" class="form-control" placeholder="Aquí caerá el texto plano">
                        </div>
                        <div class="col">
                            <label>Matrícula (Simulado):</label>
                            <input type="text" id="matricula" class="form-control" placeholder="Aquí caerá si tiene mat:">
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <button class="btn btn-outline-danger btn-sm" onclick="location.reload()">
                             Reiniciar Cámara / Limpiar Test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script src="../../assets/js/Lector.js"></script>

<?php include_once __DIR__ . '/../../templates/footer.php'; ?>
