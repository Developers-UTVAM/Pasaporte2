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
                qrContainer.style.backgroundColor = "#ffffff";
                qrContainer.style.padding = "15px";
                qrContainer.style.display = "inline-block";
                qrContainer.style.borderRadius = "12px";
                qrContainer.style.boxShadow = "0 8px 16px rgba(0, 0, 0, 0.1)";
                qrContainer.style.border = "1px solid rgba(0, 0, 0, 0.05)";

                new QRCode(qrContainer, {
                    text: qrContent,
                    width: 200,
                    height: 200,
                    colorLight : "#ffffff",
                    colorDark : "#1f2937",
                    correctLevel : QRCode.CorrectLevel.M
                });
            };
            document.head.appendChild(script);
        }
    }
});
