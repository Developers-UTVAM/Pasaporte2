let tblDataList = null;
let datatblDataList = null;

const applyTheme = (themeName) => {
    document.documentElement.setAttribute('data-theme', themeName);
    localStorage.setItem('user-theme', themeName);
    if (document.getElementById('theme-toggle-1')) {
        document.getElementById('theme-toggle-1').innerText = `DEPLOY: ${themeLbs[themeName]}`;
    }
    if (document.getElementById('theme-toggle-2')) {
        document.getElementById('theme-toggle-2').innerText = `DEPLOY: ${themeLbs[themeName]}`;
    }
}

const toggleTheme = () => {
    let currentTheme = localStorage.getItem('user-theme') || 'default';
    let currentIndex = themes.indexOf(currentTheme);
    let nextIndex = (currentIndex + 1) % themes.length;
    let nextTheme = themes[nextIndex];
    applyTheme(nextTheme);
};

const themes = [ "pasaporte", "energia", "cyber-ruby", "deep-ocean",
    "mint-dark", "aqua-glass", "deep-glass", "utvam" ];
const themeLbs = { "pasaporte": "Pasaporte TI", "energia": "Energía TI",
    "cyber-ruby": "Cyber Ruby TI", "deep-ocean": "Deep Ocean TI",
    "mint-dark": "Mint Dark TI", "aqua-glass": "Aqua Glass TI",
    "deep-glass": "Deep Glass TI", "utvam": "UTVAM"};
const savedTheme = localStorage.getItem('user-theme');

if (savedTheme) {
    applyTheme(savedTheme);
}

document.addEventListener('DOMContentLoaded', () => {
    $.extend($.fn.dataTable.defaults, {
        searching: true,
        ordering: [],
        pageLength: 50,
        lengthMenu: [10, 25, 50, 75, 100, -1],
        language: {
            "decimal": "",
            "emptyTable": "No hay filas que mostrar",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "(filtrado de _MAX_ entradas totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ entradas",
            "loadingRecords": '<div class="d-flex align-items-center justify-content-center py-2"><div class="lottie-dynamic-spinner" data-size="40" style="width: 40px; height: 40px; margin-right: 8px;"></div> Cargando...</div>',
            "processing": '<div class="d-flex align-items-center justify-content-center py-2"><div class="lottie-dynamic-spinner" data-size="40" style="width: 40px; height: 40px; margin-right: 8px;"></div> Procesando...</div>',
            "search": "Buscar:",
            "zeroRecords": "No se encontraron registros coincidentes",
            "paginate": {
                "first": "<i class=\"fa-solid fa-angles-left\"></i>",
                "last": "<i class=\"fa-solid fa-angles-right\"></i>",
                "next": "<i class=\"fa-solid fa-angle-right\"></i>",
                "previous": "<i class=\"fa-solid fa-angle-left\"></i>"
            },
            "aria": {
                "orderable": "Ordenar de forma Ascendente",
                "orderableReverse": "Ordenar de forma Descendente"
            },
            "lengthLabels": {
                '-1': 'Mostrar todo',
            }
        }
    });

    tblDataList = $('table#data-list');
    datatblDataList = tblDataList.DataTable({"responsive": true});
});
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});
 
document.addEventListener('DOMContentLoaded', function() {
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
}); 

// === CONTROL DE CICLO DE VIDA DEL PRELOADER Y DYNAMIC SPINNERS ===

// Ocultar preloader global al terminar de cargar la página
window.addEventListener('load', function() {
    const preloader = document.getElementById('global-preloader');
    if (preloader) {
        preloader.classList.add('hide');
    }
});

// Reactivar preloader en envíos de formularios estándar
document.addEventListener('submit', function(e) {
    const form = e.target;
    // Evitar si es una acción cancelada o interna de DataTables
    if (form && !form.classList.contains('dt-form') && !e.defaultPrevented) {
        // No mostrar precargador si la validación del navegador falla
        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
            return;
        }
        const preloader = document.getElementById('global-preloader');
        if (preloader) {
            const textEl = preloader.querySelector('.preloader-text');
            if (textEl) {
                textEl.textContent = 'Procesando...';
            }
            preloader.classList.remove('hide');
            // Forzar reproducción del spinner de Lottie para asegurar que se anime al re-mostrar
            if (window.globalPreloaderAnim) {
                window.globalPreloaderAnim.goToAndPlay(0, true);
            }
        }
    }
});

// MutationObserver para inicializar automáticamente cualquier spinner Lottie dinámico
document.addEventListener('DOMContentLoaded', function() {
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            document.querySelectorAll('.lottie-dynamic-spinner').forEach(function(el) {
                if (!el.hasAttribute('data-lottie-loaded')) {
                    el.setAttribute('data-lottie-loaded', 'true');
                    const size = el.getAttribute('data-size') || '30';
                    el.style.width = size + 'px';
                    el.style.height = size + 'px';
                    if (typeof lottie !== 'undefined' && window.lottieSpinnerData) {
                        lottie.loadAnimation({
                            container: el,
                            renderer: 'svg',
                            loop: true,
                            autoplay: true,
                            animationData: window.lottieSpinnerData
                        });
                    }
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }
});