/* asistencia_clase.js — Lógica independiente de escaneo QR para clases regulares */
let html5QrcodeScannerClase = null;
let isProcessingClase = false;

function reproducirSonidoClase() {
    const audio = document.getElementById('audio-qr');
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(e => console.log("Error sonido:", e));
    }
}

function mostrarAlertaClase(mensaje, tipo = 'warning') {
    const container = document.getElementById('alert-container');
    if (!container) return;

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show shadow-sm mb-2`;
    alertDiv.innerHTML = `
        <i class="fa-solid ${tipo === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'} me-2"></i>
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    container.prepend(alertDiv);

    setTimeout(() => {
        if (alertDiv.parentNode) alertDiv.remove();
    }, 4000);
}

function registrarLecturaEnTabla(alumno, hora) {
    const body = document.getElementById('tabla-asistencias-body');
    const sinReg = document.getElementById('row-sin-registros');
    if (sinReg) sinReg.remove();

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><strong>${alumno}</strong></td>
        <td>${hora}</td>
        <td><span class="badge bg-success">Presente</span></td>
        <td><span class="badge bg-info text-dark">QR</span></td>
    `;
    body.prepend(tr);

    const badge = document.getElementById('badge-contador');
    if (badge) {
        let count = body.children.length;
        badge.innerText = `${count} Registrados`;
    }
}

function onScanSuccessClase(decodedText) {
    if (isProcessingClase) return;
    isProcessingClase = true;

    const horarioSelect = document.querySelector('select[name="horario_id"]');
    const horarioId = horarioSelect ? horarioSelect.value : '';
    if (!horarioId) {
        mostrarAlertaClase('Selecciona una clase válida primero.', 'warning');
        isProcessingClase = false;
        return;
    }

    reproducirSonidoClase();

    $.ajax({
        url: 'escanear_qr.php',
        method: 'POST',
        data: {
            accion: 'marcar_qr',
            horario_id: horarioId,
            identificador: decodedText,
            fecha: new Date().toISOString().split('T')[0]
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                mostrarAlertaClase(res.message, 'success');
                registrarLecturaEnTabla(res.usuario, res.hora);
            } else {
                mostrarAlertaClase(res.message, 'warning');
            }
        },
        error: function () {
            mostrarAlertaClase('Error de comunicación con el servidor.', 'danger');
        },
        complete: function () {
            setTimeout(() => {
                isProcessingClase = false;
            }, 1500);
        }
    });
}

function iniciarEscaneoClase() {
    const container = document.getElementById('qr-reader-container');
    if (container) container.style.display = 'block';

    if (!html5QrcodeScannerClase) {
        html5QrcodeScannerClase = new Html5Qrcode("qr-reader");
    }

    html5QrcodeScannerClase.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccessClase
    ).catch(err => {
        mostrarAlertaClase("Error al iniciar cámara: " + err, "danger");
    });
}

function detenerEscaneoClase() {
    if (html5QrcodeScannerClase) {
        html5QrcodeScannerClase.stop().then(() => {
            document.getElementById('qr-reader-container').style.display = 'none';
        }).catch(err => console.log(err));
    }
}

$(document).ready(function () {
    if ($.fn.select2) {
        $('.select2').select2({ width: '100%' });
    }
});
