
let html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", 
    { 
        fps: 10, 
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0 
    }
);


function onScanSuccess(decodedText, decodedResult) {
 
    html5QrcodeScanner.clear();
    document.getElementById('qr-reader').style.display = "none";

    const btn = document.getElementById('btn-toggle-camera');
    btn.innerHTML = '<i class="fa-solid fa-camera"></i> Escanear otro código';
    btn.classList.replace('btn-danger', 'btn-primary');
    
    const resultDiv = document.getElementById('qr-reader-results');
    

    let tipoDato = "Desconocido";
    let valorRecuperado = "Sin valor";
    let alertClass = "alert-info";


    if (decodedText.includes(":")) {
        const partes = decodedText.split(":");
        const prefijo = partes[0].trim().toLowerCase();
        valorRecuperado = partes[1].trim();

        if (prefijo === "mat") {
            tipoDato = "Matrícula Estudiantil";
            alertClass = "alert-success";
        } else {
            tipoDato = `ID de ${partes[0].toUpperCase()}`;
            alertClass = "alert-success";
        }
    } else {
        tipoDato = "Código Genérico / Texto Plano";
        valorRecuperado = decodedText;
        alertClass = "alert-warning";
    }

    resultDiv.innerHTML = `
        <div class="alert ${alertClass} shadow-sm border-2 animate__animated animate__fadeIn">
            <h4 class="alert-heading mb-3">
                <i class="fa-solid ${alertClass === 'alert-success' ? 'fa-circle-check' : 'fa-triangle-exclamation'}"></i> 
                Lectura Finalizada
            </h4>
            <div class="p-3 bg-white border rounded mb-3">
                <span class="text-muted d-block small mb-1">Dato Crudo (Raw):</span>
                <code class="text-dark fw-bold">${decodedText}</code>
            </div>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted d-block">Categoría:</small>
                    <span class="badge bg-secondary">${tipoDato}</span>
                </div>
                <div class="col-6 text-end">
                    <small class="text-muted d-block">Valor Extraído:</small>
                    <span class="fs-5 fw-bold text-primary">${valorRecuperado}</span>
                </div>
            </div>
        </div>
    `;

    console.log(`[TEST-LOG] Tipo: ${tipoDato} | Valor: ${valorRecuperado}`);
}


function toggleLector() {
    const readerDiv = document.getElementById('qr-reader');
    const resultDiv = document.getElementById('qr-reader-results');
    const btn = document.getElementById('btn-toggle-camera');
    
    if (readerDiv.style.display === "none" || readerDiv.style.display === "") {
        readerDiv.style.display = "block";
        resultDiv.innerHTML = "";
        
        btn.innerHTML = '<i class="fa-solid fa-stop"></i> Detener Cámara';
        btn.classList.replace('btn-primary', 'btn-danger');
        html5QrcodeScanner.render(onScanSuccess, (errorMessage) => {
            console.debug("Buscando QR...");
        });
    } else {

        readerDiv.style.display = "none";
        btn.innerHTML = '<i class="fa-solid fa-camera"></i> Activar Cámara';
        btn.classList.replace('btn-danger', 'btn-primary');
        html5QrcodeScanner.clear();
    }
}
