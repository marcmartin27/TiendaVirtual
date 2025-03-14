document.addEventListener('DOMContentLoaded', () => {
    const openPopup = document.getElementById('openPopup');
    const popupBackground = document.getElementById('popupBackground');
    const loginButton = document.getElementById('loginButton');
    const registerButton = document.getElementById('registerButton');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const profileIcon = document.getElementById('profileIcon');
    const profilePopup = document.getElementById('profilePopup');

    openPopup.addEventListener('click', () => {
        popupBackground.style.display = 'flex';
        loginButton.classList.add('active');
        registerButton.classList.remove('active');
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    });

    popupBackground.addEventListener('click', (e) => {
        if (e.target === popupBackground) {
            popupBackground.style.display = 'none';
        }
    });

    loginButton.addEventListener('click', () => {
        loginButton.classList.add('active');
        registerButton.classList.remove('active');
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    });

    registerButton.addEventListener('click', () => {
        registerButton.classList.add('active');
        loginButton.classList.remove('active');
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
    });

    profileIcon.addEventListener('click', () => {
        profilePopup.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!profilePopup.contains(e.target) && e.target !== profileIcon) {
            profilePopup.classList.add('hidden');
        }
    });

    // Ocultar mensajes de alerta después de 1 segundo
    setTimeout(function() {
        var successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.display = 'none';
        }
        var errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            errorAlert.style.display = 'none';
        }
    }, 1000); // 1000 milisegundos = 1 segundo
});