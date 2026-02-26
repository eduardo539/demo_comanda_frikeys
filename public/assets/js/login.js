document.addEventListener('DOMContentLoaded', function() {
    
    // Funcionalidad para mostrar/ocultar contraseña en el Login
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#passwordInput');
    const eyeIcon = document.querySelector('#eyeIcon');

    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function () {
            // Alternar el tipo de input (password <-> text)
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Alternar el ícono (ojo abierto <-> ojo cerrado)
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    }

});