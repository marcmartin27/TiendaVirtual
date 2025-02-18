document.addEventListener('DOMContentLoaded', () => {
    const sizes = document.querySelectorAll('.size');
    const selectedSizeElement = document.getElementById('selected-size');

    sizes.forEach(size => {
        size.addEventListener('click', () => {
            sizes.forEach(s => s.classList.remove('selected'));
            size.classList.add('selected');
            selectedSizeElement.textContent = size.dataset.size;
        });
    });
});