document.addEventListener('DOMContentLoaded', () => {
    const openPopup = document.getElementById('openPopup');
    const popupBackground = document.getElementById('popupBackground');
    const loginButton = document.getElementById('loginButton');
    const registerButton = document.getElementById('registerButton');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

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
});