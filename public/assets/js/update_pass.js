document.addEventListener('input', function (e) {
    // Solo actuamos si el cambio viene de los campos de NUEVA contraseña
    if (e.target.id === 'pass_nueva' || e.target.id === 'pass_confirmar') {
        
        const passNueva = document.getElementById('pass_nueva');
        const passConfirmar = document.getElementById('pass_confirmar');
        const btn = document.getElementById('btnGuardarPass');
        const errorPass = document.getElementById('msg-error-pass');
        const errorConfirm = document.getElementById('msg-error-confirm');

        // Valores actuales
        const pass = passNueva.value;
        const confirm = passConfirmar.value;

        // RegEx: Mínimo 8 caracteres, al menos una letra y un número
        const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        const esValida = regex.test(pass);

        // 1. Validar Formato de la Nueva Contraseña
        if (pass.length > 0) {
            errorPass.innerHTML = esValida 
                ? '<span class="text-success small">✓ Formato seguro</span>' 
                : '<span class="text-danger small">⚠ Mín. 8 caracteres (letras y números)</span>';
        } else {
            errorPass.innerHTML = "";
        }

        // 2. Validar Coincidencia
        if (confirm.length > 0) {
            if (pass === confirm) {
                errorConfirm.innerHTML = '<span class="text-success small">✓ Las contraseñas coinciden</span>';
            } else {
                errorConfirm.innerHTML = '<span class="text-danger small">⚠ No coinciden</span>';
            }
        } else {
            errorConfirm.innerHTML = "";
        }

        // 3. Habilitar/Deshabilitar Botón
        if (esValida && pass === confirm && confirm !== "") {
            btn.disabled = false;
            btn.classList.remove('disabled', 'opacity-50');
        } else {
            btn.disabled = true;
            btn.classList.add('disabled', 'opacity-50');
        }
    }
});

// Limpieza al cerrar el modal
document.addEventListener('hidden.bs.modal', function (event) {
    if (event.target.id === 'modalNewPass') {
        const form = document.getElementById('formNewPass');
        if(form) form.reset();
        document.getElementById('msg-error-pass').innerHTML = "";
        document.getElementById('msg-error-confirm').innerHTML = "";
        const btn = document.getElementById('btnGuardarPass');
        btn.disabled = true;
        btn.classList.add('disabled', 'opacity-50');
    }
});