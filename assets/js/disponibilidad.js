/* disponibilidad.js — Interacción dinámica para la gestión de disponibilidad de profesores */
$(document).ready(function () {
    if ($.fn.select2) {
        $('#profesor_id').select2({
            width: '100%',
            placeholder: '-- Seleccionar Profesor --',
            allowClear: true
        });
    }

    // Validación básica de rango de horas al enviar el formulario
    $('#main-form').on('submit', function (e) {
        let inicio = $('#hora_inicio').val();
        let fin = $('#hora_fin').val();

        if (inicio && fin && inicio >= fin) {
            e.preventDefault();
            alert('La hora de inicio debe ser menor que la hora de fin.');
            $('#hora_inicio').focus();
        }
    });
});
