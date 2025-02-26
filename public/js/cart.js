document.addEventListener('DOMContentLoaded', function () {
    const cartButton = document.getElementById('cartButton');
    const cartPopupBackground = document.getElementById('cartPopupBackground');
    const cartPopup = document.getElementById('cartPopup');
    const closeCartButton = document.getElementById('closeCartButton');
    const addToCartButtons = document.querySelectorAll('#add-to-cart');
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const checkoutButton = document.getElementById('checkoutButton');
    const selectedSizeElement = document.getElementById('selected-size');

    // Crear elemento para el total del carrito
    const totalPriceElement = document.createElement('p');
    totalPriceElement.id = 'totalPrice';
    totalPriceElement.style.fontWeight = 'bold';
    totalPriceElement.style.textAlign = 'right';
    checkoutButton.insertAdjacentElement('beforebegin', totalPriceElement);

    // Mostrar el carrito
    cartButton.addEventListener('click', function () {
        cartPopupBackground.classList.remove('hidden');
        renderCartItems();
    });

    // Cerrar el carrito
    closeCartButton.addEventListener('click', function () {
        cartPopupBackground.classList.add('hidden');
    });

    // Cerrar el carrito al hacer clic fuera de él
    cartPopupBackground.addEventListener('click', function (event) {
        if (event.target === cartPopupBackground) {
            cartPopupBackground.classList.add('hidden');
        }
    });

    // Añadir productos al carrito
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productPrice = parseFloat(button.getAttribute('data-product-price'));
            const productImage = button.getAttribute('data-product-image');
            const productQuantity = parseInt(document.getElementById('quantity').value);
            const selectedSize = selectedSizeElement.textContent;

            if (selectedSize === 'Ninguna') {
                alert('Por favor, seleccione una talla antes de añadir el producto al carrito.');
                return;
            }

            addToCart(productId, productName, productPrice, productQuantity, selectedSize, productImage);
            cartPopupBackground.classList.remove('hidden');
            renderCartItems();
        });
    });

    // Renderizar los productos del carrito
    function renderCartItems() {
        const cartItems = getCartItems();
        cartItemsContainer.innerHTML = '';
        cartItems.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                <div class="cart-item-details">
                    <p class="cart-item-name">${item.name}</p>
                    <p class="cart-item-info">Talla: ${item.size} | Precio: ${item.price.toFixed(2)} € | Cantidad: ${item.quantity}</p>
                </div>
                <button class="remove-from-cart" data-product-id="${item.id}">Eliminar</button>
            `;
            cartItemsContainer.appendChild(cartItem);
        });

        // Añadir eventos a los botones de eliminar
        const removeFromCartButtons = document.querySelectorAll('.remove-from-cart');
        removeFromCartButtons.forEach(button => {
            button.addEventListener('click', function () {
                const productId = button.getAttribute('data-product-id');
                removeFromCart(productId);
                renderCartItems();
            });
        });

        updateTotalPrice();
    }

    // Calcular y actualizar el precio total
    function updateTotalPrice() {
        const cartItems = getCartItems();
        const totalPrice = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        totalPriceElement.textContent = `Total: ${totalPrice.toFixed(2)} €`;
    }

    // Añadir producto al carrito
    function addToCart(id, name, price, quantity, size, image) {
        const cartItems = getCartItems();
        const existingItem = cartItems.find(item => item.id === id && item.size === size);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cartItems.push({ id, name, price, quantity, size, image });
        }
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        renderCartItems();
    }

    // Obtener productos del carrito
    function getCartItems() {
        return JSON.parse(localStorage.getItem('cartItems')) || [];
    }

    // Eliminar producto del carrito
    function removeFromCart(id) {
        let cartItems = getCartItems();
        cartItems = cartItems.filter(item => item.id !== id);
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        renderCartItems();
    }

    // Manejar el checkout
    checkoutButton.addEventListener('click', function () {
        const cartItems = getCartItems();
        if (cartItems.length === 0) {
            alert('El carrito está vacío.');
            return;
        }
        // Aquí puedes añadir la lógica para manejar el checkout
    });

    // Seleccionar talla
    const sizeElements = document.querySelectorAll('.size');
    sizeElements.forEach(sizeElement => {
        sizeElement.addEventListener('click', function () {
            sizeElements.forEach(el => el.classList.remove('selected'));
            sizeElement.classList.add('selected');
            selectedSizeElement.textContent = sizeElement.getAttribute('data-size');
        });
    });
});
