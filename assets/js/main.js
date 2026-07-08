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
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
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