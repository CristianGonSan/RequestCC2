document.addEventListener('livewire:init', () => {
    Livewire.directive('swal-confirm', ({ el, directive, component, cleanup }) => {
        if (el.__swalConfirmAttached) return;
        el.__swalConfirmAttached = true;

        let content = directive.expression;

        let onClick = async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();

            const result = await Swal.fire({
                title: content,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                el.removeEventListener('click', onClick, { capture: true });
                el.click();
                el.addEventListener('click', onClick, { capture: true });
            }
        };

        el.addEventListener('click', onClick, { capture: true });

        cleanup(() => {
            el.removeEventListener('click', onClick, { capture: true });
            el.__swalConfirmAttached = false;
        });
    });

    Livewire.directive('swal-delete', ({ el, directive, component, cleanup }) => {
        if (el.__swalDeleteAttached) return;
        el.__swalDeleteAttached = true;

        let content = directive.expression || '¿Estás seguro de que deseas eliminar este registro?';

        let onClick = async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();

            const result = await Swal.fire({
                title: content,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33'
            });

            if (result.isConfirmed) {
                el.removeEventListener('click', onClick, { capture: true });
                el.click();
                el.addEventListener('click', onClick, { capture: true });
            }
        };

        el.addEventListener('click', onClick, { capture: true });

        cleanup(() => {
            el.removeEventListener('click', onClick, { capture: true });
            el.__swalDeleteAttached = false;
        });
    });

    Livewire.on('SwalFire', (data) => {
        Swal.fire(data.config);
    });
});


