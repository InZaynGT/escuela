<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Flash messages desde sesión PHP ────────────────────────────────────
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: @json(session('success')),
        timer: 2800,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: false,
    });
    @elseif(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: @json(session('error')),
        confirmButtonColor: '#37474f',
    });
    @elseif(session('info'))
    Swal.fire({
        icon: 'info',
        title: 'Información',
        text: @json(session('info')),
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
    });
    @elseif(session('warning'))
    Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: @json(session('warning')),
        confirmButtonColor: '#37474f',
    });
    @endif

    // ── Confirmación global para formularios con data-confirm ───────────────
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var self = this;
            Swal.fire({
                title: '¿Confirmar acción?',
                text: self.dataset.confirm || '¿Estás seguro de continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#37474f',
                cancelButtonColor: '#90a4ae',
                reverseButtons: true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    self.submit();
                }
            });
        });
    });

});
</script>
