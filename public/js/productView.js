let selectedSize = null;
let selectedSizeStock = 0;
let quantity = 1;
let currentImageIndex = 0;

// Función para verificar cantidad en carrito
function getCartQuantityForSize(productId, size) {
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    const existingItem = cartItems.find(item => 
        String(item.id) === String(productId) && item.size === size
    );
    
    return existingItem ? existingItem.quantity : 0;
}

// Función para seleccionar talla
function selectSize(size) {
    selectedSize = size;
    document.getElementById('selected-size').innerText = size;
    
    // Obtener elemento de talla y stock
    const sizeElement = document.querySelector(`.size[data-size="${size}"]`);
    const stock = parseInt(sizeElement.getAttribute('data-stock'));
    
    // Comprobar unidades ya en carrito
    const productId = document.getElementById('add-to-cart').getAttribute('data-product-id');
    const cartQuantity = getCartQuantityForSize(productId, size);
    
    // Calcular stock disponible real
    selectedSizeStock = Math.max(0, stock - cartQuantity);
    
    // Actualizar info de stock
    const stockInfoElement = document.getElementById('stock-info');
    
    if (stock <= 0) {
        stockInfoElement.innerText = 'Agotado';
        stockInfoElement.className = 'stock-info out-of-stock';
        disableQuantityControls(true);
    } else {
        if (cartQuantity > 0) {
            if (selectedSizeStock <= 0) {
                stockInfoElement.innerText = `Agotado (${cartQuantity} ya en tu carrito)`;
                stockInfoElement.className = 'stock-info out-of-stock';
                disableQuantityControls(true);
            } else if (selectedSizeStock < 5) {
                stockInfoElement.innerText = `¡Quedan solo ${selectedSizeStock} unidades! (${cartQuantity} ya en tu carrito)`;
                stockInfoElement.className = 'stock-info low-stock';
                disableQuantityControls(false);
            } else {
                stockInfoElement.innerText = `${selectedSizeStock} unidades disponibles (${cartQuantity} ya en tu carrito)`;
                stockInfoElement.className = 'stock-info in-stock';
                disableQuantityControls(false);
            }
        } else {
            if (stock < 5) {
                stockInfoElement.innerText = `¡Quedan solo ${stock} unidades!`;
                stockInfoElement.className = 'stock-info low-stock';
            } else {
                stockInfoElement.innerText = `${stock} unidades disponibles`;
                stockInfoElement.className = 'stock-info in-stock';
            }
            disableQuantityControls(false);
        }
    }
    
    // Actualizar cantidad máxima seleccionable
    document.getElementById('quantity').max = selectedSizeStock;
    
    // Reiniciar cantidad a 1
    quantity = 1;
    document.getElementById('quantity').value = 1;
    
    // Actualizar estado de botones de cantidad
    updateQuantityButtonsState();
    
    // Actualizar clases de selección
    const sizeElements = document.querySelectorAll('.size');
    sizeElements.forEach(element => {
        if (element.getAttribute('data-size') == size) {
            element.classList.add('selected');
        } else {
            element.classList.remove('selected');
        }
    });
    
    // Habilitar/deshabilitar botón de Añadir al Carrito
    toggleAddToCartButton();
}

// Función para habilitar/deshabilitar controles de cantidad
function disableQuantityControls(disabled) {
    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
    const quantityInput = document.getElementById('quantity');
    
    decreaseBtn.disabled = disabled;
    increaseBtn.disabled = disabled || quantity >= selectedSizeStock;
    quantityInput.disabled = disabled;
}

// Función para actualizar estado de botones de cantidad
function updateQuantityButtonsState() {
    document.getElementById('decrease').disabled = quantity <= 1;
    document.getElementById('increase').disabled = quantity >= selectedSizeStock;
}

// Función para actualizar cantidad
function updateQuantity(change) {
    const newQuantity = quantity + change;
    
    if (newQuantity >= 1 && newQuantity <= selectedSizeStock) {
        quantity = newQuantity;
        document.getElementById('quantity').value = quantity;
        updateQuantityButtonsState();
    }
}

// Función para habilitar/deshabilitar botón de añadir al carrito
function toggleAddToCartButton() {
    const addToCartBtn = document.getElementById('add-to-cart');
    const buyNowBtn = document.getElementById('buy-now');
    
    if (!selectedSize || selectedSizeStock <= 0) {
        addToCartBtn.disabled = true;
        addToCartBtn.classList.add('disabled');
        
        if (buyNowBtn) {
            buyNowBtn.disabled = true;
            buyNowBtn.classList.add('disabled');
        }
    } else {
        addToCartBtn.disabled = false;
        addToCartBtn.classList.remove('disabled');
        
        if (buyNowBtn) {
            buyNowBtn.disabled = false;
            buyNowBtn.classList.remove('disabled');
        }
    }
}

// Resto de funciones de imagen sin cambios
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
    modal.style.display = 'flex';
    modalImg.src = currentImage.src;
    modalImg.style.transform = 'scale(1)';
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar visualización de imágenes
    const images = document.querySelectorAll('.product-image');
    if (images.length > 0) {
        // Mostrar solo la primera imagen inicialmente
        for (let i = 0; i < images.length; i++) {
            if (i === 0) {
                images[i].style.display = 'block';
            } else {
                images[i].style.display = 'none';
            }
        }
        
        // Añadir evento click a todas las imágenes
        images.forEach(image => {
            image.addEventListener('click', openModal);
        });
    }

    // Inicializar selección de tallas
    const sizeElements = document.querySelectorAll('.size');
    sizeElements.forEach(element => {
        element.addEventListener('click', function () {
            if (!element.classList.contains('out-of-stock')) {
                selectSize(element.getAttribute('data-size'));
            }
        });
    });

    // Inicializar zoom de imagen en modal
    const modalImg = document.getElementById('modalImage');
    if (modalImg) {
        modalImg.addEventListener('click', function () {
            if (modalImg.style.transform === 'scale(2)') {
                modalImg.style.transform = 'scale(1)';
            } else {
                modalImg.style.transform = 'scale(2)';
            }
        });
    }

    // Inicializar botones de cantidad
    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
    
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            updateQuantity(-1);
        });
    }
    
    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            updateQuantity(1);
        });
    }
});