(function () {

    
    function actualizarEstadoMaestro(selectorChks, chkTodos) {
        if (!chkTodos) return;
        var chks = document.querySelectorAll(selectorChks);
        var sel  = document.querySelectorAll(selectorChks + ':checked').length;
        chkTodos.checked       = sel > 0 && sel === chks.length;
        chkTodos.indeterminate = sel > 0 && sel < chks.length;
    }

    
    var chkTodosCrear = document.getElementById('chk-todos');
    var btnSelAll     = document.getElementById('btn-sel-todos');
    var btnDesel      = document.getElementById('btn-desel-todos');

    if (chkTodosCrear) {

        chkTodosCrear.addEventListener('change', function () {
            document.querySelectorAll('.chk-usuario')
                .forEach(function (c) { c.checked = chkTodosCrear.checked; });
        });

        if (btnSelAll) {
            btnSelAll.addEventListener('click', function () {
                document.querySelectorAll('.chk-usuario')
                    .forEach(function (c) { c.checked = true; });
                chkTodosCrear.checked       = true;
                chkTodosCrear.indeterminate = false;
            });
        }

        if (btnDesel) {
            btnDesel.addEventListener('click', function () {
                document.querySelectorAll('.chk-usuario')
                    .forEach(function (c) { c.checked = false; });
                chkTodosCrear.checked       = false;
                chkTodosCrear.indeterminate = false;
            });
        }

        document.querySelectorAll('.fila-disponible').forEach(function (fila) {
            fila.style.cursor = 'pointer';
            fila.addEventListener('click', function (e) {
                if (e.target.type === 'checkbox') {
                    actualizarEstadoMaestro('.chk-usuario', chkTodosCrear);
                    return;
                }
                var chk = fila.querySelector('.chk-usuario');
                if (chk) {
                    chk.checked = !chk.checked;
                    actualizarEstadoMaestro('.chk-usuario', chkTodosCrear);
                }
            });
        });

        document.querySelectorAll('.chk-usuario').forEach(function (chk) {
            chk.addEventListener('change', function () {
                actualizarEstadoMaestro('.chk-usuario', chkTodosCrear);
            });
        });
    }

   
    var chkTodosEditar = document.getElementById('chk-todos');
    var panelMover     = document.getElementById('panel-mover');
    var panelElim      = document.getElementById('panel-eliminar');

    document.querySelectorAll('input[name="tipo_accion"]').forEach(function (r) {
        r.addEventListener('change', function () {
            if (panelMover) panelMover.classList.toggle('d-none', this.value === 'eliminar');
            if (panelElim)  panelElim.classList.toggle('d-none',  this.value !== 'eliminar');
        });
    });

    if (chkTodosEditar && document.querySelector('.chk-registro')) {

        chkTodosEditar.addEventListener('change', function () {
            document.querySelectorAll('.chk-registro')
                .forEach(function (c) { c.checked = chkTodosEditar.checked; });
            actualizarEstadoMaestro('.chk-registro', chkTodosEditar);
        });

        var btnSelAllEditar = document.getElementById('btn-sel-todos');
        var btnDeselEditar  = document.getElementById('btn-desel-todos');

        if (btnSelAllEditar) {
            btnSelAllEditar.addEventListener('click', function () {
                document.querySelectorAll('.chk-registro')
                    .forEach(function (c) { c.checked = true; });
                chkTodosEditar.checked = true;
                actualizarEstadoMaestro('.chk-registro', chkTodosEditar);
            });
        }

        if (btnDeselEditar) {
            btnDeselEditar.addEventListener('click', function () {
                document.querySelectorAll('.chk-registro')
                    .forEach(function (c) { c.checked = false; });
                chkTodosEditar.checked = false;
                actualizarEstadoMaestro('.chk-registro', chkTodosEditar);
            });
        }

        document.querySelectorAll('.fila-registro').forEach(function (fila) {
            fila.addEventListener('click', function (e) {
                if (e.target.type === 'checkbox') {
                    actualizarEstadoMaestro('.chk-registro', chkTodosEditar);
                    return;
                }
                var chk = fila.querySelector('.chk-registro');
                if (chk) {
                    chk.checked = !chk.checked;
                    actualizarEstadoMaestro('.chk-registro', chkTodosEditar);
                }
            });
        });

        document.querySelectorAll('.chk-registro').forEach(function (chk) {
            chk.addEventListener('change', function () {
                actualizarEstadoMaestro('.chk-registro', chkTodosEditar);
            });
        });
    }

    
    document.addEventListener('DOMContentLoaded', function () {
        var tabla = document.getElementById('data-list');
        if (tabla && typeof $.fn !== 'undefined' && $.fn.DataTable) {
            if ($.fn.DataTable.isDataTable('#data-list')) {
                $('#data-list').DataTable().destroy();
            }
            $('#data-list').DataTable({
                responsive: true,
                order: [[6, 'desc']],
                columnDefs: [{ orderable: false, targets: -1 }]
            });
        }
    });

})();
