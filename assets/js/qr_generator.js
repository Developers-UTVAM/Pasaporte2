document.addEventListener('DOMContentLoaded', () => {
    // Qr busca por ID
    const qrContainer = document.getElementById("qrcode");

    if (qrContainer && qrContainer.dataset.text) {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
        script.onload = () => {
            new QRCode(qrContainer, {
                text: qrContainer.dataset.text,
                width: 200,
                height: 200,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        };
        document.head.appendChild(script);
    }
});
