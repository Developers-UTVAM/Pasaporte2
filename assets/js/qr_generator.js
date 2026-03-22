document.addEventListener('DOMContentLoaded', () => {
    const qrContainer = document.getElementById("qrcode");

    if (qrContainer) {
        let qrContent = null;
        const matricula = qrContainer.getAttribute("data-matricula");
        const id = qrContainer.getAttribute("data-id");
        if (matricula && matricula.trim() !== "" && matricula !== "0") {
            qrContent = `mat:${matricula}`;
        } else if (id && id.trim() !== "") {
            qrContent = `id:${id}`;
        }

        if (qrContent) {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
            script.onload = () => {
                qrContainer.innerHTML = "";
                new QRCode(qrContainer, {
                    text: qrContent,
                    width: 200,
                    height: 200,
                    colorLight : "#ffffff",
                    colorDark : "#000000",
                    correctLevel : QRCode.CorrectLevel.H
                });
            };
            document.head.appendChild(script);
        }
    }
});
