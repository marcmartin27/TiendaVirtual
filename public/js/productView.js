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

// Modificar la función openModal
function openModal() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const images = document.querySelectorAll('.product-image');
    const currentImage = images[currentImageIndex];
    
    // Ocultar las flechas del slider principal
    const mainSliderPrev = document.querySelector('.image-slider .prev');
    const mainSliderNext = document.querySelector('.image-slider .next');
    if (mainSliderPrev) mainSliderPrev.style.display = 'none';
    if (mainSliderNext) mainSliderNext.style.display = 'none';
    
    // Mostrar el modal
    modal.style.display = 'flex';
    modalImg.src = currentImage.src;
    modalImg.style.transform = 'scale(1)';
}

// Modificar la función closeModal
function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Mostrar nuevamente las flechas del slider principal
    const mainSliderPrev = document.querySelector('.image-slider .prev');
    const mainSliderNext = document.querySelector('.image-slider .next');
    if (mainSliderPrev) mainSliderPrev.style.display = 'block';
    if (mainSliderNext) mainSliderNext.style.display = 'block';
}

function buyNow() {
    // Verificar si se ha seleccionado una talla
    if (!selectedSize || selectedSize === 'Ninguna' || selectedSizeStock <= 0) {
        alert('Por favor, selecciona una talla disponible antes de continuar');
        return;
    }
    
    // Obtener los datos del producto
    const addToCartBtn = document.getElementById('add-to-cart');
    const productId = addToCartBtn.getAttribute('data-product-id');
    const productName = addToCartBtn.getAttribute('data-product-name');
    const productPrice = addToCartBtn.getAttribute('data-product-price');
    const productNewPrice = addToCartBtn.getAttribute('data-product-new-price');
    const productImage = addToCartBtn.getAttribute('data-product-image');
    const currentQuantity = parseInt(document.getElementById('quantity').value);
    
    // Determinar el precio correcto (regular o rebajado)
    const finalPrice = (productNewPrice && productNewPrice !== "null" && productNewPrice !== "0") 
                     ? parseFloat(productNewPrice) 
                     : parseFloat(productPrice);
    
    // Verificar que la cantidad sea válida
    if (isNaN(currentQuantity) || currentQuantity < 1 || currentQuantity > selectedSizeStock) {
        alert('La cantidad seleccionada no es válida');
        return;
    }
    
    // Obtener el token CSRF
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (!metaToken) {
        console.error('No se encontró el token CSRF');
        alert('Error de configuración: Token CSRF no encontrado');
        return;
    }
    
    const csrfToken = metaToken.getAttribute('content');
    const userIdElement = document.getElementById('userId');
    const userId = userIdElement ? userIdElement.value : null;
    
    // Mostrar indicador de carga
    const buyNowBtn = document.getElementById('buy-now');
    const originalText = buyNowBtn.textContent;
    buyNowBtn.textContent = 'Procesando...';
    buyNowBtn.disabled = true;

    // Primero añadir este producto al localStorage para que esté disponible en todas partes
    const cartItem = {
        id: productId,
        name: productName,
        price: finalPrice,
        quantity: currentQuantity,
        size: selectedSize,
        image: productImage,
        isCustomized: false
    };
    
    // Guardar en localStorage
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    
    // Verificar si el producto ya existe con la misma talla
    const existingItemIndex = cartItems.findIndex(item => 
        String(item.id) === String(productId) && item.size === selectedSize
    );
    
    if (existingItemIndex !== -1) {
        // Actualizar cantidad si ya existe
        cartItems[existingItemIndex].quantity = currentQuantity;
    } else {
        // Agregar nuevo item
        cartItems.push(cartItem);
    }
    
    // Guardar el carrito actualizado
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
    
    // Ahora enviar los datos al servidor en el formato que espera
    fetch('/save-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            userId: userId,
            cartItems: cartItems
        })
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                throw new Error('Por favor inicia sesión para añadir productos al carrito');
            } else {
                throw new Error('Error en el servidor: ' + response.status);
            }
        }
        return response.json();
    })
    .then(data => {
        // Redirigir directamente al checkout
        window.location.href = '/checkout';
    })
    // Reemplaza la sección de manejo de errores con este código:
    .catch(error => {
        console.error('Error:', error);
        
        if (error.message.includes('inicia sesión')) {
            // En lugar de redirigir, activa el modal de login
            const loginModal = document.getElementById('login-modal');
            if (loginModal) {
                // Si existe un modal con ID 'login-modal'
                loginModal.classList.add('show');
                loginModal.style.display = 'block';
            } else {
                // Si no hay un modal específico, busca un botón de login y haz clic en él
                const loginBtn = document.querySelector('.login-btn') || 
                                document.querySelector('[data-toggle="modal"][data-target="#loginModal"]') ||
                                document.getElementById('login-button');
                
                if (loginBtn) {
                    loginBtn.click(); // Simular clic en el botón de login
                } else {
                    // Si todo lo demás falla, muestra una alerta
                    alert('Por favor inicia sesión para continuar con la compra.');
                }
            }
        } else {
            alert(error.message || 'Ha ocurrido un error al procesar la solicitud. Por favor, inténtalo de nuevo.');
        }
        
        // Restaurar el botón
        buyNowBtn.textContent = originalText;
        buyNowBtn.disabled = false;
    });
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

    // Inicializar botón de compra rápida
    const buyNowBtn = document.getElementById('buy-now');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', buyNow);
    }

    // Manejar cambios en el input de cantidad
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            
            // Validar el valor introducido
            if (isNaN(value) || value < 1) {
                value = 1;
            } else if (value > selectedSizeStock) {
                value = selectedSizeStock;
            }
            
            // Actualizar la cantidad y la UI
            quantity = value;
            this.value = value;
            updateQuantityButtonsState();
        });
    }
});