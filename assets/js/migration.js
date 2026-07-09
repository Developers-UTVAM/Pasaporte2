    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-sql-btn');
        if (btn) {
            const filename = btn.getAttribute('data-file');
            const modalTitle = document.getElementById('sqlModalTitle');
            const modalContent = document.getElementById('sqlModalContent');
            
            if (modalTitle) modalTitle.textContent = filename;
            if (modalContent) modalContent.innerHTML = '<div class="d-flex flex-column align-items-center justify-content-center py-4"><div class="lottie-dynamic-spinner" data-size="50" style="width: 50px; height: 50px;"></div><span class="mt-2 text-muted">Cargando SQL...</span></div>';
            const modalEl = document.getElementById('sqlModal');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
            fetch(`migrations.php?action=view_sql&file=${encodeURIComponent(filename)}`)
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error(`Error en el servidor: HTTP ${response.status}`);
                    }
                })
                .then(data => {
                    if (modalContent) modalContent.textContent = data;
                })
                .catch(err => {
                    if (modalContent) modalContent.textContent = "Error al cargar el archivo. Es posible que ya no exista en el servidor.";
                    console.error(err);
                });
        }
    });
    
