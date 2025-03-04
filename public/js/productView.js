let selectedSize = null;
let quantity = 1;
let currentImageIndex = 0;

function selectSize(size) {
    selectedSize = size;
    document.getElementById('selected-size').innerText = size;
    const sizeElements = document.querySelectorAll('.size');
    sizeElements.forEach(element => {
        if (element.getAttribute('data-size') == size) {
            element.classList.add('selected');
        } else {
            element.classList.remove('selected');
        }
    });
}

function updateQuantity(change) {
    if (quantity + change > 0) {
        quantity += change;
        document.getElementById('quantity').value = quantity;
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
    modalImg.style.transform = 'scale(1)'; // Asegurarse de que el zoom no esté activado al abrir el modal
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

    const sizeElements = document.querySelectorAll('.size');
    sizeElements.forEach(element => {
        element.addEventListener('click', function () {
            selectSize(element.getAttribute('data-size'));
        });
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

document.getElementById("decrease").addEventListener("click", function () {
    let quantity = document.getElementById("quantity");
    if (quantity.value > 1) {
        quantity.value = parseInt(quantity.value) - 1;
    }
});

document.getElementById("increase").addEventListener("click", function () {
    let quantity = document.getElementById("quantity");
    quantity.value = parseInt(quantity.value) + 1;
});
