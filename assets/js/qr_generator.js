document.addEventListener('DOMContentLoaded', () => {
    const qrContainer = document.getElementById("qrcode");
    const qrLabel = document.getElementById("qr-label");
    const inputColorDark = document.getElementById("qrColorDark");
    const inputColorLight = document.getElementById("qrColorLight");

    if (qrContainer) {
        const matricula = qrContainer.dataset.matricula;
        const id = qrContainer.dataset.id;
        const fallback = qrContainer.dataset.fallback;
        let valor = (matricula && matricula.trim() !== '' && matricula.trim() !== '0') ? matricula : id;
        let prefijo = (matricula && matricula.trim() !== '' && matricula.trim() !== '0') ? "Matrícula: " : "ID: ";
        if (!valor || valor.trim() === '') {
            valor = fallback;
            prefijo = (valor && valor.length > 5) ? "Matrícula: " : "ID: ";
        }

        if (!valor) return;
        if (qrLabel) {
            qrLabel.textContent = prefijo + valor;
        }
        const qrText = "math " + valor;

        const generateQR = () => {
            qrContainer.innerHTML = '';
            new QRCode(qrContainer, {
                text: qrText,
                width: 200,
                height: 200,
                colorLight : "#ffffff",
                colorDark : "#000000",
                correctLevel : QRCode.CorrectLevel.H
            });
        };

        if (typeof QRCode !== 'undefined') {
            generateQR();
        } else {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
            script.onload = generateQR;
            script.onerror = () => console.error("Error al cargar qrcode.js");
            document.head.appendChild(script);
        }
    }
});
