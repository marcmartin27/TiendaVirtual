let selectedSize = 38;
let quantity = 1;
let currentImageIndex = 0;

function selectSize(size) {
    selectedSize = size;
    document.getElementById('selectedSize').innerText = size;
}

function updateQuantity(change) {
    if (quantity + change > 0) {
        quantity += change;
        document.getElementById('quantity').innerText = quantity;
    }
}

function changeImage(direction) {
    const images = document.querySelectorAll('.product-image');
    images[currentImageIndex].style.display = 'none';
    currentImageIndex = (currentImageIndex + direction + images.length) % images.length;
    images[currentImageIndex].style.display = 'block';
}

function changeImageInModal(direction) {
    changeImage(direction);
    const modalImg = document.getElementById('modalImage');
    const currentImage = document.querySelector('.product-image[style*="block"]');
    modalImg.src = currentImage.src;
}

function openModal() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const images = document.querySelectorAll('.product-image');
    const currentImage = images[currentImageIndex];
    modal.style.display = 'flex'; // Cambiar a 'flex' para centrar la imagen
    modalImg.src = currentImage.src;
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    const images = document.querySelectorAll('.product-image');
    images.forEach(image => {
        image.addEventListener('click', openModal);
    });

    const modalImg = document.getElementById('modalImage');
    modalImg.addEventListener('click', function () {
        if (modalImg.style.transform === 'scale(2)') {
            modalImg.style.transform = 'scale(1)';
        } else {
            modalImg.style.transform = 'scale(2)';
        }
    });
});