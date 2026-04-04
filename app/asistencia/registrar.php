<?php
if (!isset($eventos)) {
    $eventos = $object->getTodosEventos();
}
?>

<!-- Botones para alternar la vista -->
<div class="text-center mb-4">
    <div class="btn-group shadow-sm" role="group" aria-label="Modo de Registro">
        <input type="radio" class="btn-check" name="modo_registro" id="btn_modo_qr" autocomplete="off" checked onchange="toggleAsistenciaMode('qr')">
        <label class="btn btn-outline-primary px-4 py-2" for="btn_modo_qr"><i class="fa-solid fa-qrcode"></i> Lector QR</label>

        <input type="radio" class="btn-check" name="modo_registro" id="btn_modo_manual" autocomplete="off" onchange="toggleAsistenciaMode('manual')">
        <label class="btn btn-outline-primary px-4 py-2" for="btn_modo_manual"><i class="fa-solid fa-keyboard"></i> Ingreso Manual</label>
    </div>
</div>

<div class="row justify-content-center">
    <!-- Vista para Registro Manual  -->
    <div class="col-md-10 col-lg-8" id="vista-manual" style="display: none;">
        <div class="card mb-3 custom-border shadow-sm">
            <div class="card-body p-md-4">
                <h3 class="mb-4 text-center"><i class="fa-solid fa-keyboard"></i> Registro Manual</h3>
                <form autocomplete="off" method="post" action="asistencia.php">
                    <input type="hidden" name="accion" value="marcar" />
                    <div class="form-floating mb-3">
                        <select name="evento_id" id="evento_id_manual" class="form-select" required>
                            <option value="">Seleccione un evento...</option>
                            <?php foreach($eventos as $e): ?>
                                <option value="<?= htmlspecialchars($e['id']) ?>">
                                    <?= htmlspecialchars($e['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="evento_id_manual">Evento Activo</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="text" name="usuario_id" id="usuario_id" class="form-control" required placeholder="Ingresar matrícula o ID">
                        <label for="usuario_id">ID o Matrícula del Usuario</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 fs-5 shadow">
                        <i class="fa-solid fa-check-to-slot"></i> Registrar Asistencia
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Vista para Lector QR -->
    <div class="col-md-10 col-lg-8" id="vista-qr">
        <div class="card shadow-sm custom-border">
            <div class="card-body text-center p-md-4">
                <h3 class="mb-4"><i class="fa-solid fa-qrcode"></i> Lector de Gafetes</h3>
                <div class="form-floating mb-4">
                    <select id="evento_id_qr" class="form-select" required>
                        <option value="">Seleccione un evento primero...</option>
                        <?php foreach($eventos as $e): ?>
                            <option value="<?= htmlspecialchars($e['id']) ?>">
                                <?= htmlspecialchars($e['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="evento_id_qr">Selecciona el evento para escanear</label>
                </div>

                <button class="btn btn-primary btn-lg mb-3 shadow" id="btn-iniciar-qr" onclick="iniciarEscaneoMulti()">
                    <i class="fa-solid fa-camera"></i> Iniciar Escáner
                </button>
                <button class="btn btn-outline-danger btn-lg mb-3 shadow d-none" id="btn-detener-qr" onclick="detenerEscaneoMulti()">
                    <i class="fa-solid fa-stop"></i> Detener Cámara
                </button>

                <div id="qr-reader-container" class="mx-auto mt-3 bg-dark" style="width: 100%; max-width: 450px; display: none; border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--tw-shadow);">
                    <div id="qr-reader" style="width: 100%;"></div>
                </div>

                <div id="qr-status" class="mt-4 fs-5 mx-auto" style="max-width: 500px; min-height: 60px;"></div>

                <script>
                let html5QrcodeScanner = null;
                let isScanning = false;
                let lastScannedCode = "";
                let lastScannedTime = 0;

                function iniciarEscaneoMulti() {
                    const eventoId = document.getElementById('evento_id_qr').value;
                    if (!eventoId) {
                        alert('Por favor, selecciona un evento primero.');
                        return;
                    }

                    document.getElementById('btn-iniciar-qr').classList.add('d-none');
                    document.getElementById('btn-detener-qr').classList.remove('d-none');
                    document.getElementById('qr-reader-container').style.display = 'block';
                    document.getElementById('qr-status').innerHTML = '<span class="text-info"><i class="fa-solid fa-spinner fa-spin"></i> Inicializando cámara...</span>';

                    if (!html5QrcodeScanner) {
                        html5QrcodeScanner = new Html5Qrcode("qr-reader");
                    }

                    html5QrcodeScanner.start(
                        { facingMode: "environment" },
                        {
                            fps: 10,
                            qrbox: function(viewfinderWidth, viewFinderHeight) {
                                let minEdgeSize = Math.min(viewfinderWidth, viewFinderHeight);
                                let qrboxSize = Math.floor(minEdgeSize * 0.8);
                                return {
                                    width: qrboxSize,
                                    height: qrboxSize
                                };
                            }
                        },
                        (decodedText) => {
                            const now = Date.now();
                            if (decodedText === lastScannedCode && (now - lastScannedTime) < 4000) {
                                return;
                            }
                            lastScannedCode = decodedText;
                            lastScannedTime = now;
                            const audio = document.getElementById('audio-qr');
                            if (audio) { audio.currentTime = 0; audio.play().catch(() => {}); }
                            document.getElementById('qr-status').innerHTML = `<span class="text-info fw-bold"><i class="fa-solid fa-spinner fa-spin"></i> QR detectado. Procesando...</span>`;
                            setTimeout(() => {
                                procesarCodigoMulti(decodedText, eventoId);
                            }, 600);
                        },
                        (errorMessage) => {}
                    ).then(() => {
                        isScanning = true;
                        document.getElementById('qr-status').innerHTML = '<span class="text-success"><i class="fa-solid fa-camera"></i> Cámara activa. Escaneando múltiples...</span>';
                    }).catch((err) => {
                        console.error(err);
                        document.getElementById('qr-status').innerHTML = '<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Error al iniciar la cámara.</span>';
                    });
                }

                function detenerEscaneoMulti() {
                    if (html5QrcodeScanner && isScanning) {
                        html5QrcodeScanner.stop().then(() => {
                            isScanning = false;
                            document.getElementById('btn-iniciar-qr').classList.remove('d-none');
                            document.getElementById('btn-detener-qr').classList.add('d-none');
                            document.getElementById('qr-reader-container').style.display = 'none';
                            document.getElementById('qr-status').innerHTML = '';
                        });
                    }
                }

                function procesarCodigoMulti(codigo, eventoId) {
                    document.getElementById('qr-status').innerHTML = `<span class="text-warning"><i class="fa-solid fa-spinner fa-spin"></i> Procesando: ${codigo}...</span>`;

                    fetch('asistencia.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ 'accion': 'procesar_qr', 'evento_id': eventoId, 'matricula': codigo })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById('qr-status').innerHTML = `<div class="alert alert-success py-2 my-0"><i class="fa-solid fa-check-circle"></i> ${data.message}</div>`;
                        } else if (data.status === 'not_registered') {
                            if(confirm(data.message)) {
                                fetch('asistencia.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: new URLSearchParams({ 'accion': 'confirmar_auto', 'evento_id': eventoId, 'usuario_id': data.usuario_id })
                                }).then(r => r.json()).then(res => {
                                    if (res.status === 'success') document.getElementById('qr-status').innerHTML = `<div class="alert alert-success py-2 my-0"><i class="fa-solid fa-check-circle"></i> ${res.message}</div>`;
                                    else document.getElementById('qr-status').innerHTML = `<div class="alert alert-danger py-2 my-0"><i class="fa-solid fa-triangle-exclamation"></i> ${res.message}</div>`;
                                });
                            } else {
                                document.getElementById('qr-status').innerHTML = `<div class="alert alert-warning py-2 my-0">Registro cancelado.</div>`;
                            }
                        } else {
                            document.getElementById('qr-status').innerHTML = `<div class="alert alert-danger py-2 my-0"><i class="fa-solid fa-triangle-exclamation"></i> ${data.message}</div>`;
                        }
                        setTimeout(() => {
                            if (isScanning) {
                                document.getElementById('qr-status').innerHTML = '<span class="text-success"><i class="fa-solid fa-camera"></i> Cámara activa. Siguiente...</span>';
                            }
                        }, 3000);
                    }).catch(() => {
                        document.getElementById('qr-status').innerHTML = `<div class="alert alert-danger py-2 my-0"><i class="fa-solid fa-circle-xmark"></i> Error de conexión al servidor.</div>`;
                    });
                }
                </script>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAsistenciaMode(modo) {
    if (modo === 'qr') {
        document.getElementById('vista-manual').style.display = 'none';
        document.getElementById('vista-qr').style.display = 'block';
    } else {
        document.getElementById('vista-qr').style.display = 'none';
        document.getElementById('vista-manual').style.display = 'block';
        if (typeof detenerEscaneoMulti === 'function') detenerEscaneoMulti();
    }
}
</script>
