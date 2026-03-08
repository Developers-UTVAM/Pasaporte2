document.addEventListener('DOMContentLoaded', () => {
    const qrContainer = document.getElementById("qrcode");

    if (qrContainer && qrContainer.dataset.text) {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
        script.onload = () => {
            new QRCode(qrContainer, {
                text: qrContainer.dataset.text,
                width: 200,
                height: 200,
                colorLight : "#ffffff",
                colorDark : "#000000",
                correctLevel : QRCode.CorrectLevel.H
            });
        };
        document.head.appendChild(script);
    }
});
