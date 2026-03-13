/**
 * Lector.js - Manejo de escaneo de QR para el sistema Pasaporte2
 * Utiliza la librería html5-qrcode para procesar video en tiempo real.
 */

// Función que se ejecuta cuando el QR se lee correctamente
function onScanSuccess(decodedText, decodedResult) {
    console.log(`Código detectado: ${decodedText}`);
    // NUEVO: Mostrar el dato crudo para testeo
    const rawDisplay = document.getElementById('raw-data');
    if (rawDisplay) rawDisplay.innerText = decodedText;
    // 1. Referencias a campos comunes en formularios de usuario
    const inputNombre = document.getElementById('nombre');
    const inputMatricula = document.getElementById('matricula'); // Asegúrate de que este ID exista en tu form

    // 2. Lógica de procesamiento de datos (según getQrData de PHP)
    if (decodedText.startsWith("mat:")) {
        // Si el QR es "mat:12345", extraemos solo el "12345"
        const matricula = decodedText.replace("mat:", "");
        if (inputMatricula) inputMatricula.value = matricula;
    } else if (decodedText.startsWith("id:")) {
        // Si es un ID, podrías usarlo para buscar al usuario o llenar un campo oculto
        const id = decodedText.replace("id:", "");
        console.log("ID de usuario detectado: " + id);
    } else {
        // Si es texto plano, lo ponemos en el nombre por defecto
        if (inputNombre) inputNombre.value = decodedText;
    }

    // 3. Feedback visual para el usuario
    const resultElement = document.getElementById('result');
    if (resultElement) {
        resultElement.innerHTML = `<div class="alert alert-success">Leído: ${decodedText}</div>`;
    }

    // 4. Detener la cámara para ahorrar recursos en Windows 11
    html5QrcodeScanner.clear().catch(error => {
        console.error("Error al cerrar la cámara:", error);
    });
}

// Función para manejar errores de enfoque o luz (se dispara muchas veces, mejor dejarla limpia)
function onScanFailure(error) {
    // console.warn(`Error de escaneo: ${error}`);
}

// Configuración e inicialización del escáner
let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", // El ID del <div> en el HTML
    { 
        fps: 15,           // Cuadros por segundo (más alto = más fluido)
        qrbox: { width: 250, height: 250 }, // Área visual de escaneo
        aspectRatio: 1.0   // Cámara cuadrada
    },
    /* verbose= */ false
);

// Renderiza el lector en el div "reader"
html5QrcodeScanner.render(onScanSuccess, onScanFailure);