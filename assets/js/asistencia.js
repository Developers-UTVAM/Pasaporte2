let html5QrcodeScanner = null;
let isContinuous = true;
let alertTimeout = null;
let isProcessing = false;

function reproducirSonido() {
    const audio = document.getElementById('audio-qr');
    if (audio) {
        audio.currentTime = 0;
        audio.play().catch(e => console.log("Ocurrió un error con el sonido de escáner:", e));
    }
}

function mostrarAlerta(mensaje, tipo = 'warning') {
    const container = document.getElementById('modal-alert-container');
    if (container) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show shadow-sm text-start mb-2`;
        alertDiv.style.cssText = 'background: var(--glass-bg); border: 1px solid var(--glass-border); color: var(--text-color); border-radius: 16px; font-weight: normal; transition: opacity 0.3s ease;';

        let icon = 'fa-circle-info';
        if (tipo === 'danger') icon = 'fa-triangle-exclamation';
        if (tipo === 'success') icon = 'fa-circle-check';
        if (tipo === 'warning') icon = 'fa-circle-exclamation';

        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fa-solid ${icon} fs-2 me-3 text-${tipo}"></i>
                <div style="font-size: 1.05rem;">${mensaje}</div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="top: 50%; transform: translateY(-50%);"></button>
        `;

        container.prepend(alertDiv);

        while (container.children.length > 3) {
            container.lastElementChild.remove();
        }

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.classList.remove('show');
                setTimeout(() => {
                    if (alertDiv.parentNode) alertDiv.remove();
                }, 150);
            }
        }, 5000);
    }
}

function actualizarKPIs(kpi) {
    if (!kpi) return;
    const kpiHoy = document.getElementById('kpi-asistencias-hoy');
    const kpiUltimo = document.getElementById('kpi-ultimo-registro-container');

    if (kpiHoy) kpiHoy.innerText = kpi.hoy;

    if (kpiUltimo) {
        if (kpi.ultimo_nombre === 'Ninguno') {
            kpiUltimo.innerHTML = `<h3 class="kpi-value" style="font-size: 1.2rem; margin-top: 5px;">Ninguno</h3>`;
        } else {
            kpiUltimo.innerHTML = `
                <h3 class="kpi-value" style="font-size: 1.1rem; margin-top: 2px; line-height: 1.2;">${kpi.ultimo_nombre}</h3>
                <small class="text-muted-custom" style="font-size: 0.75rem;">${kpi.ultimo_hora}</small>
            `;
        }
    }
}

function syncEventoManual() {
    const globalSelect = document.getElementById('evento_id_global').value;
    if (!globalSelect) {
        mostrarAlerta('Por favor, selecciona un evento en la parte superior antes de registrar.', 'warning');
        return false;
    }
    document.getElementById('evento_id_manual_hidden').value = globalSelect;
    return true;
}

function iniciarEscaneo() {
    const evento_id = document.getElementById('evento_id_global').value;
    if (!evento_id) {
        mostrarAlerta("Selecciona un evento primero para iniciar la cámara.", 'warning');
        return;
    }

    document.getElementById('btn-iniciar-qr').classList.add('d-none');
    document.getElementById('btn-detener-qr').classList.remove('d-none');
    document.getElementById('qr-reader-container').style.display = 'block';

    const modoContinuoSwitch = document.getElementById('modoContinuo');
    isContinuous = modoContinuoSwitch ? modoContinuoSwitch.checked : false;

    if (isContinuous) {
        document.getElementById('lista-escaneados').style.display = 'block';
    } else {
        document.getElementById('lista-escaneados').style.display = 'none';
    }

    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
    }

    html5QrcodeScanner.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: function(viewfinderWidth, viewFinderHeight) {
                let minEdgePercentage = 0.8;
                let minEdgeSize = Math.min(viewfinderWidth, viewFinderHeight);
                let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                return {
                    width: qrboxSize,
                    height: qrboxSize
                };
            },
            formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ]
        },
        onScanSuccess,
        onScanFailure
    ).catch(err => {
        console.error("Error al iniciar escáner:", err);
        mostrarAlerta("No se pudo iniciar la cámara. Verifica los permisos de tu navegador.", 'danger');
    });
}

function detenerEscaneo() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
            document.getElementById('btn-iniciar-qr').classList.remove('d-none');
            document.getElementById('btn-detener-qr').classList.add('d-none');
            document.getElementById('qr-reader-container').style.display = 'none';
        }).catch(err => console.log("El escáner ya estaba detenido."));
    }
}

function onScanSuccess(decodedText, decodedResult) {

    if (isProcessing) {
        return;
    }
    isProcessing = true;

    console.log("QR Detectado crudo:", decodedText);

    reproducirSonido();
    mostrarAlerta("QR detectado. Procesando asistencia...", 'info');
    const statusDiv = document.getElementById('qr-status');
    if(statusDiv) statusDiv.innerHTML = '<span class="text-info fw-bold"><i class="fa-solid fa-spinner fa-spin"></i> Código detectado, enviando...</span>';

    procesarAsistencia(decodedText);
}

function onScanFailure(error) {
}

function procesarAsistencia(matricula) {
    const evento_id = document.getElementById('evento_id_global').value;
    const statusDiv = document.getElementById('qr-status');

    statusDiv.innerHTML = '<span class="text-info"><i class="fa-solid fa-spinner fa-spin"></i> Validando asistencia...</span>';

    const formData = new FormData();
    formData.append('accion', 'procesar_qr');
    formData.append('evento_id', evento_id);
    formData.append('matricula', matricula);

    fetch('app/asistencia/controlador_asistencia.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            if(statusDiv) statusDiv.innerHTML = `<span class="text-success fw-bold"><i class="fa-solid fa-check-circle"></i> Asistencia confirmada</span>`;

            let msgAlerta = data.message;
            let msgLista = data.message;
            if (data.nombre && data.matricula) {
                msgAlerta = `<strong>Alumno:</strong> ${data.nombre}<br><strong>Matrícula:</strong> <span class="badge bg-secondary">${data.matricula}</span><br><span class="text-success fw-bold mt-1 d-block">Asistencia tomada correctamente</span>`;
                msgLista = `${data.nombre} (${data.matricula})`;
            }

            mostrarAlerta(msgAlerta, 'success');
            agregarALista(msgLista, 'success');
            actualizarKPIs(data.kpi);
            if (!isContinuous) detenerEscaneo();
        } else if (data.status === 'not_registered') {
            detenerEscaneo();

            if(statusDiv) statusDiv.innerHTML = `
                <div class="p-3 rounded mb-3 text-center fade-in shadow-sm" style="background: rgba(255,193,7,0.1); border: 1px solid rgba(255,193,7,0.3);">
                    <p class="text-warning fw-bold mb-3"><i class="fa-solid fa-circle-question"></i> ${data.message}</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-success px-4 shadow-sm" style="border-radius: 12px; font-weight: bold;" onclick="confirmarAutoRegistro(${evento_id}, ${data.usuario_id}, \`${data.nombre}\`)"><i class="fa-solid fa-check"></i> Sí, Registrar</button>
                        <button class="btn btn-outline-light px-4" style="border-radius: 12px; border-color: rgba(255,255,255,0.2);" onclick="document.getElementById('qr-status').innerHTML = '<span class=\\'text-warning fw-bold\\'><i class=\\'fa-solid fa-xmark\\'></i> Cancelado.</span>'; if(isContinuous) iniciarEscaneo();"><i class="fa-solid fa-xmark"></i> Cancelar</button>
                    </div>
                </div>
            `;
        } else {
            if(statusDiv) statusDiv.innerHTML = `<span class="text-danger fw-bold"><i class="fa-solid fa-circle-xmark"></i> Error al registrar</span>`;
            mostrarAlerta(`<strong>Atención:</strong><br>${data.message}`, 'danger');
            agregarALista(`Error: ${data.message}`, 'danger');
            if (!isContinuous) detenerEscaneo();
        }
    })
    .catch(error => {
        if(statusDiv) statusDiv.innerHTML = `<span class="text-danger fw-bold"><i class="fa-solid fa-bug"></i> Error de conexión con el servidor.</span>`;
        mostrarAlerta("Error de conexión con el servidor.", 'danger');
    })
    .finally(() => {

        setTimeout(() => {
            isProcessing = false;
            const statusDiv = document.getElementById('qr-status');
            if (isContinuous && statusDiv && !statusDiv.querySelector('button')) {
                statusDiv.innerHTML = '<span class="text-success"><i class="fa-solid fa-camera"></i> Cámara activa. Siguiente...</span>';
            }
        }, 2000);
    });
}

function confirmarAutoRegistro(evento_id, usuario_id, nombre) {
    const formData = new FormData();
    formData.append('accion', 'confirmar_auto');
    formData.append('evento_id', evento_id);
    formData.append('usuario_id', usuario_id);

    fetch('app/asistencia/controlador_asistencia.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById('qr-status');
        if (data.status === 'success') {
            if(statusDiv) statusDiv.innerHTML = `<span class="text-success fw-bold"><i class="fa-solid fa-check-circle"></i> Auto-registro confirmado</span>`;

            let msgAlerta = data.message;
            let msgLista = `Auto-registro: ${nombre}`;
            if (data.nombre && data.matricula) {
                msgAlerta = `<strong>Alumno:</strong> ${data.nombre}<br><strong>Matrícula:</strong> <span class="badge bg-secondary">${data.matricula}</span><br><span class="text-success fw-bold mt-1 d-block">¡Auto-registro y asistencia exitosos!</span>`;
                msgLista = `Auto-registro: ${data.nombre} (${data.matricula})`;
            } else {
                msgAlerta = `<strong>Auto-registro correcto:</strong><br>${nombre}`;
            }

            mostrarAlerta(msgAlerta, 'success');
            agregarALista(msgLista, 'success');
            actualizarKPIs(data.kpi);
            if (isContinuous) iniciarEscaneo(); else detenerEscaneo();
        } else {
            if(statusDiv) statusDiv.innerHTML = `<span class="text-danger fw-bold"><i class="fa-solid fa-circle-xmark"></i> Error al registrar</span>`;
            mostrarAlerta(`<strong>Error auto-registro:</strong><br>${data.message}`, 'danger');
            agregarALista(`Error auto-registro: ${nombre}`, 'danger');
            if (isContinuous) iniciarEscaneo();
        }
    });
}

function agregarALista(mensaje, tipo) {
    if (!isContinuous) return;
    const ul = document.getElementById('ul-escaneados');
    if (!ul) return;

    const li = document.createElement('li');
    li.className = `list-group-item d-flex justify-content-between align-items-center py-3 fade-in`;
    li.style.background = 'var(--glass-bg)';
    li.style.borderColor = 'var(--glass-border)';
    li.style.color = 'var(--text-color)';

    const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});

    let colorText = tipo === 'success' ? 'var(--color-green-400)' : (tipo === 'danger' ? 'var(--color-red-400)' : 'var(--color-blue-400)');
    li.innerHTML = `<span class="fw-bold" style="color: ${colorText};">${mensaje}</span> <span class="badge border" style="background: rgba(255,255,255,0.1); border-color: var(--glass-border) !important;">${time}</span>`;

    ul.prepend(li);
    if (ul.children.length > 8) {
        ul.removeChild(ul.lastChild);
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('modalRegistroAsistencia');
    if(modalElement) {
        modalElement.addEventListener('hidden.bs.modal', event => {
            detenerEscaneo();
            document.getElementById('ul-escaneados').innerHTML = '';
            document.getElementById('qr-status').innerHTML = '';
        });
    }
    const formManual = document.getElementById('form-manual');
    if (formManual) {
        formManual.addEventListener('submit', (e) => {
            e.preventDefault();
            if (!syncEventoManual()) return;
            const matriculaInput = document.getElementById('usuario_id_manual');
            procesarAsistencia(matriculaInput.value);
            matriculaInput.value = '';
        });
    }
});
