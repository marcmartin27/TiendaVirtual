document.addEventListener('DOMContentLoaded', function () {
    const cartButton = document.getElementById('cartButton');
    const cartPopupBackground = document.getElementById('cartPopupBackground');
    const cartPopup = document.getElementById('cartPopup');
    const closeCartButton = document.getElementById('closeCartButton');
    const addToCartButtons = document.querySelectorAll('#add-to-cart');
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const checkoutButton = document.getElementById('checkoutButton');
    const selectedSizeElement = document.getElementById('selected-size');
    const userIdElement = document.getElementById('userId');
    const userId = userIdElement ? userIdElement.value : null;


    // Verificar si el usuario acaba de iniciar sesión
    const justLoggedInElement = document.getElementById('just-logged-in');
    if (justLoggedInElement && justLoggedInElement.value === 'true') {
        console.log('Usuario recién logueado, sincronizando carrito...');
        syncCartWithDatabase();
        
        // Limpiar el flag para que no se vuelva a sincronizar en futuras cargas
        fetch('/clear-login-flag').catch(err => console.error('Error al limpiar flag:', err));
    }

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
            // Verificar si existe precio rebajado y usarlo en lugar del precio regular
            const newPrice = button.getAttribute('data-product-new-price');
            const regularPrice = parseFloat(button.getAttribute('data-product-price'));
            const productPrice = newPrice && newPrice !== "null" ? parseFloat(newPrice) : regularPrice;
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
                    <p class="cart-item-info">Talla: ${item.size} | Precio: ${parseFloat(item.price).toFixed(2)} €</p>
                    <div class="quantity-controls">
                        <button class="quantity-btn decrease-quantity" data-product-id="${item.id}" data-product-size="${item.size}">-</button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button class="quantity-btn increase-quantity" data-product-id="${item.id}" data-product-size="${item.size}">+</button>
                    </div>
                </div>
                <button class="remove-from-cart" data-product-id="${item.id}" data-product-size="${item.size}">Eliminar</button>
            `;
            cartItemsContainer.appendChild(cartItem);
        });
    
        // Añadir eventos a los botones de eliminar
        const removeFromCartButtons = document.querySelectorAll('.remove-from-cart');
        removeFromCartButtons.forEach(button => {
            button.addEventListener('click', function () {
                const productId = button.getAttribute('data-product-id');
                const productSize = button.getAttribute('data-product-size');
                removeFromCart(productId, productSize);
                renderCartItems();
            });
        });
    
        // Añadir eventos a los botones de aumentar cantidad
        const increaseButtons = document.querySelectorAll('.increase-quantity');
        increaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = button.getAttribute('data-product-id');
                const productSize = button.getAttribute('data-product-size');
                changeQuantity(productId, productSize, 1);
            });
        });
    
        // Añadir eventos a los botones de disminuir cantidad
        const decreaseButtons = document.querySelectorAll('.decrease-quantity');
        decreaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = button.getAttribute('data-product-id');
                const productSize = button.getAttribute('data-product-size');
                changeQuantity(productId, productSize, -1);
            });
        });
    
        updateTotalPrice();
    }

    // Función para cambiar la cantidad de un producto en el carrito
    function changeQuantity(id, size, change) {
        let cartItems = getCartItems();
        const idString = String(id);
        
        // Encontrar el producto específico por ID y talla
        const item = cartItems.find(item => String(item.id) === idString && item.size === size);
        
        if (item) {
            // Cambiar la cantidad (mínimo 1)
            item.quantity = Math.max(1, item.quantity + change);
            
            // Guardar en localStorage
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            
            // Si el usuario está logueado, actualizar la base de datos
            if (userId) {
                saveCartToDatabase(cartItems);
            }
            
            // Renderizar los cambios
            renderCartItems();
        }
    }

    // Calcular y actualizar el precio total
    function updateTotalPrice() {
        const cartItems = getCartItems();
        const totalPrice = cartItems.reduce((sum, item) => sum + (parseFloat(item.price) * item.quantity), 0);
        totalPriceElement.textContent = `Total: ${totalPrice.toFixed(2)} €`;
    }

    // Añadir producto al carrito
    function addToCart(id, name, price, quantity, size, image) {
        const cartItems = getCartItems();
        const existingItem = cartItems.find(item => item.id === id && item.size === size);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cartItems.push({ id, name, price: parseFloat(price), quantity, size, image });
        }
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        renderCartItems();

        // Si el usuario está logueado, guardar en la base de datos
        console.log('addToCart - userId:', userId); // Log para depuración
        if (userId) {
            console.log('addToCart - Guardando en la base de datos', cartItems);
            saveCartToDatabase(cartItems);
        }
    }

    // Obtener productos del carrito
    function getCartItems() {
        return JSON.parse(localStorage.getItem('cartItems')) || [];
    }

    // Eliminar producto del carrito
    function removeFromCart(id, size) {
        let cartItems = getCartItems();
        console.log('Intentando eliminar producto con ID:', id, 'y talla:', size);
        console.log('Items en el carrito antes de eliminar:', cartItems);
        
        // Convertir id a string para asegurar una comparación consistente
        const idString = String(id);
        
        // Filtrar por ID Y talla para eliminar solo el producto específico
        cartItems = cartItems.filter(item => !(String(item.id) === idString && item.size === size));
        
        console.log('Items en el carrito después de eliminar:', cartItems);
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        renderCartItems();

        // Si el usuario está logueado, actualizar la base de datos
        if (userId) {
            saveCartToDatabase(cartItems);
        }
    }

    // Guardar el carrito en la base de datos
    async function saveCartToDatabase(cartItems) {
        try {
            console.log('saveCartToDatabase - Iniciando con userId:', userId);
            
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            let csrfToken = '';
            
            if (csrfTokenElement) {
                csrfToken = csrfTokenElement.getAttribute('content');
                console.log('saveCartToDatabase - csrfToken encontrado');
            } else {
                console.log('saveCartToDatabase - csrfToken NO encontrado');
            }
            
            const headers = {
                'Content-Type': 'application/json'
            };
            
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch('/save-cart', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ userId, cartItems })
            });
            
            const result = await response.json();
            console.log('saveCartToDatabase - Respuesta del servidor:', result);
        } catch (error) {
            console.error('Error al guardar el carrito en la base de datos:', error);
        }
    }

    // Sincronizar el carrito local con la base de datos al iniciar sesión
    async function syncCartWithDatabase() {
        if (userId) {
            try {
                console.log('Sincronizando carrito para usuario:', userId);
                
                // Paso 1: Obtener los productos actuales de la base de datos
                const response = await fetch(`/get-cart/${userId}`);
                const data = await response.json();
                const dbCartItems = data.cartItems || [];
                console.log('Productos iniciales en base de datos:', dbCartItems);

                // Paso 2: Verificar si el usuario acaba de iniciar sesión y hay productos locales
                const localCartItems = getCartItems();
                const justLoggedIn = document.getElementById('just-logged-in')?.value === 'true';

                // Si el usuario acaba de iniciar sesión con productos locales
                if (localCartItems.length > 0 && justLoggedIn) {
                    console.log('Usuario recién logueado con productos locales, sincronizando...');

                    // LIMPIAR localStorage antes de empezar para evitar duplicaciones
                    localStorage.removeItem('cartItems');
                    
                    // Crear un mapa para productos únicos (key: id-size)
                    const uniqueItemsMap = {};
                    
                    // Primero procesar productos de base de datos
                    dbCartItems.forEach(item => {
                        // Convertir todo a string para consistencia
                        const itemId = String(item.id);
                        const key = `${itemId}-${item.size}`;
                        uniqueItemsMap[key] = {
                            ...item,
                            id: itemId,
                            price: parseFloat(item.price)
                        };
                    });
                    
                    // Luego procesar productos locales
                    localCartItems.forEach(item => {
                        // Convertir todo a string para consistencia
                        const itemId = String(item.id);
                        const key = `${itemId}-${item.size}`;
                        
                        if (uniqueItemsMap[key]) {
                            // Si ya existe, sumar cantidades
                            uniqueItemsMap[key].quantity += item.quantity;
                        } else {
                            // Si no existe, añadir
                            uniqueItemsMap[key] = {
                                ...item,
                                id: itemId,
                                price: parseFloat(item.price)
                            };
                        }
                    });
                    
                    // Convertir el objeto a un array
                    const mergedItems = Object.values(uniqueItemsMap);
                    console.log('Productos combinados sin duplicados:', mergedItems);
                    
                    // Guardar en base de datos
                    await saveCartToDatabase(mergedItems);
                    
                    // Actualizar localStorage con los productos combinados
                    localStorage.setItem('cartItems', JSON.stringify(mergedItems));
                } else {
                    // Si no acaba de iniciar sesión o no tiene productos locales,
                    // simplemente cargar desde la base de datos sin combinar
                    const normalizedDbItems = dbCartItems.map(item => ({
                        ...item,
                        id: String(item.id),
                        price: parseFloat(item.price)
                    }));
                    
                    // Reemplazar completamente el contenido del localStorage
                    localStorage.setItem('cartItems', JSON.stringify(normalizedDbItems));
                }
                
                // Renderizar los productos del carrito
                renderCartItems();
                
            } catch (error) {
                console.error('Error al sincronizar el carrito con la base de datos:', error);
            }
        }
    }

    // IMPORTANTE: Eliminar o modificar esta línea que causa la sincronización automática en cada carga
    // syncCartWithDatabase();

    // En lugar de eso, solo sincronizar cuando sea necesario:
    if (userId && document.getElementById('just-logged-in')?.value === 'true') {
        // Solo sincronizar si el usuario acaba de iniciar sesión
    } else if (userId) {
        // Si el usuario está logueado pero no acaba de iniciar sesión,
        // solo cargar los datos de la base de datos sin sincronizar
        loadCartFromDatabase();
    }

    // Función para cargar el carrito desde la base de datos (sin sincronizar con localStorage)
    async function loadCartFromDatabase() {
        if (userId) {
            try {
                const response = await fetch(`/get-cart/${userId}`);
                const data = await response.json();
                const dbCartItems = data.cartItems || [];
                
                // Normalizar los datos
                const normalizedDbItems = dbCartItems.map(item => ({
                    ...item,
                    id: String(item.id),
                    price: parseFloat(item.price)
                }));
                // Limpiar carrito al cerrar sesión
document.addEventListener('DOMContentLoaded', function() {
    const logoutForm = document.querySelector('form[action*="logout"]');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function() {
            // Limpiar localStorage antes de enviar la solicitud de cierre de sesión
            localStorage.removeItem('cartItems');
            console.log('Carrito limpiado al cerrar sesión');
        });
    }
});
                // Reemplazar completamente el localStorage
                localStorage.setItem('cartItems', JSON.stringify(normalizedDbItems));
                
                // Renderizar los productos
                renderCartItems();
            } catch (error) {
                console.error('Error al cargar el carrito desde la base de datos:', error);
            }
        }
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

// Limpiar carrito al cerrar sesión
document.addEventListener('DOMContentLoaded', function() {
    const logoutForm = document.querySelector('form[action*="logout"]');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function() {
            // Limpiar localStorage antes de enviar la solicitud de cierre de sesión
            localStorage.removeItem('cartItems');
            console.log('Carrito limpiado al cerrar sesión');
        });
    }
});