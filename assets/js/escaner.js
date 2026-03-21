let html5QrcodeScanner = new Html5QrcodeScanner(
    "qr-reader", { fps: 10, qrbox: 250 }
);

function onScanSuccess(decodedText, decodedResult) {
    
    html5QrcodeScanner.clear();
    document.getElementById('qr-reader').style.display = "none";
    document.getElementById('btn-toggle-camera').innerHTML = '<i class="fa-solid fa-camera"></i> Escanear otro código';
    
    const resultDiv = document.getElementById('qr-reader-results');
    
    
    let tipoDato = "ID de Sistema";
    let valorRecuperado = decodedText;

    if (decodedText.startsWith("mat:")) {
        tipoDato = "Matrícula";
        valorRecuperado = decodedText.split(":")[1];
    } else if (decodedText.includes(":")) {
        
        let partes = decodedText.split(":");
        tipoDato = partes[0];
        valorRecuperado = partes[1];
    }

    resultDiv.innerHTML = `
        <div class="alert alert-success shadow-sm">
            <h4 class="alert-heading mb-3"><i class="fa-solid fa-circle-check"></i> Lectura Exitosa</h4>
            <div class="p-2 bg-light border rounded mb-2">
                <span class="text-muted" style="font-size: 0.85em;">Dato Crudo Escaneado:</span><br>
                <code class="text-dark">${decodedText}</code>
            </div>
            <p class="mb-1"><strong>Tipo detectado:</strong> ${tipoDato}</p>
            <p class="mb-0 fs-5 text-primary"><strong>Valor recuperado:</strong> ${valorRecuperado}</p>
        </div>
    `;

    console.log(`[TEST QR] ${tipoDato} recuperado:`, valorRecuperado);
}

function toggleLector() {
    const readerDiv = document.getElementById('qr-reader');
    const resultDiv = document.getElementById('qr-reader-results');
    const btn = document.getElementById('btn-toggle-camera');
    
    if (readerDiv.style.display === "none" || readerDiv.style.display === "") {
        
        readerDiv.style.display = "block";
        resultDiv.innerHTML = ""; 
        btn.innerHTML = '<i class="fa-solid fa-camera-rotate"></i> Apagar Cámara';
        html5QrcodeScanner.render(onScanSuccess);
    } else {
        readerDiv.style.display = "none";
        btn.innerHTML = '<i class="fa-solid fa-camera"></i> Activar Cámara';
        html5QrcodeScanner.clear();
    }
}