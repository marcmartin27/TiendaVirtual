document.addEventListener('DOMContentLoaded', () => {
    const openPopup = document.getElementById('openPopup');
    const popupBackground = document.getElementById('popupBackground');

    openPopup.addEventListener('click', () => {
        popupBackground.style.display = 'flex';
    });

    popupBackground.addEventListener('click', (e) => {
        if (e.target === popupBackground) {
            popupBackground.style.display = 'none';
        }
    });
});
