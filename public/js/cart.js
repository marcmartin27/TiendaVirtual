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
    let hasBeenSynced = localStorage.getItem('cartSynced') === 'true';
    
    console.log('Estado inicial:', {
        'justLoggedInElement exists': !!justLoggedInElement,
        'justLoggedInElement value': justLoggedInElement ? justLoggedInElement.value : null,
        'hasBeenSynced': hasBeenSynced,
        'userId': userId
    });
    
    if (justLoggedInElement && justLoggedInElement.value === 'true' && !hasBeenSynced && userId) {
        console.log('Usuario recién logueado, sincronizando carrito...');
        syncCartWithDatabase();
        hasBeenSynced = true;
        localStorage.setItem('cartSynced', 'true');
        
        // Limpiar el flag
        justLoggedInElement.value = 'false';
        
        // Enviar petición al servidor para limpiar la sesión
        fetch('/clear-login-flag')
            .then(response => {
                if (response.ok) {
                    console.log('Flag de inicio de sesión limpiado correctamente');
                } else {
                    throw new Error('Error al limpiar el flag de inicio de sesión');
                }
            })
            .catch(err => console.error('Error al limpiar flag:', err));
    } else if (userId) {
        // Solo cargar desde base de datos si el usuario está logueado
        loadCartFromDatabase();
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
            
            let customizationBadge = '';
            if (item.isCustomized) {
                customizationBadge = '<span class="customized-badge">Personalizado</span>';
            }
            
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                <div class="cart-item-details">
                    <p class="cart-item-name">${item.name} ${customizationBadge}</p>
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
        
        // Verificar si hay una imagen personalizada para este producto
        let customizedImage = localStorage.getItem(`customized_${id}`);
        
        // Si existe una imagen personalizada, usarla en lugar de la original
        const finalImage = customizedImage || image;
        
        const existingItem = cartItems.find(item => item.id === id && item.size === size);
        if (existingItem) {
            existingItem.quantity += quantity;
            
            // Actualizar la imagen si hay una personalización
            if (customizedImage) {
                existingItem.image = customizedImage;
                existingItem.isCustomized = true;
            }
        } else {
            cartItems.push({ 
                id, 
                name, 
                price: parseFloat(price), 
                quantity, 
                size, 
                image: finalImage,
                isCustomized: !!customizedImage
            });
        }
        
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        renderCartItems();
    
        // Si el usuario está logueado, guardar en la base de datos
        console.log('addToCart - userId:', userId);
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

    // Modificar la función saveCartToDatabase
    async function saveCartToDatabase(cartItems) {
        try {
            console.log('saveCartToDatabase - Enviando estos productos:', cartItems);
            
            // Verificar solo userID, permitiendo que cartItems esté vacío
            if (!userId) {
                console.error('saveCartToDatabase - Usuario no identificado');
                return;
            }
            
            const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
            let csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';
            
            if (!csrfToken) {
                console.error('saveCartToDatabase - No se encontró CSRF token');
                return;
            }
            
            // Asegurarse de que cartItems sea un array (aunque esté vacío)
            const itemsToSave = Array.isArray(cartItems) ? cartItems : [];
            
            const response = await fetch('/save-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ userId, cartItems: itemsToSave })
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Error HTTP: ${response.status}, ${errorText}`);
            }
            
            const result = await response.json();
            console.log('saveCartToDatabase - Respuesta del servidor:', result);
            return result;
        } catch (error) {
            console.error('Error al guardar el carrito:', error);
            throw error;
        }
    }

    // Modifica tu función syncCartWithDatabase
    async function syncCartWithDatabase() {
        if (userId) {
            try {
                console.log('Sincronizando carrito para usuario:', userId);
                
                // Paso 1: Obtener los productos actuales de la base de datos
                const response = await fetch(`/get-cart/${userId}`);
                const data = await response.json();
                const dbCartItems = data.cartItems || [];
                console.log('Productos iniciales en base de datos:', dbCartItems);

                // Paso 2: Verificar si hay productos locales
                const localCartItems = getCartItems();
                console.log('Productos en localStorage:', localCartItems);
                
                // Debug de las condiciones
                console.log('Condiciones para sincronizar:', {
                    localCartItemsLength: localCartItems.length,
                    justLoggedInElement: document.getElementById('just-logged-in')?.value
                });

                // Si el usuario acaba de iniciar sesión con productos locales
                if (localCartItems.length > 0) {
                    console.log('Usuario con productos locales, sincronizando...');
                    
                    // NO eliminar localStorage aquí - lo haremos después de la sincronización exitosa
                    
                    // Crear un mapa para productos únicos (key: id-size)
                    const uniqueItemsMap = {};
                    
                    // Primero procesar productos de base de datos
                    dbCartItems.forEach(item => {
                        const itemId = String(item.id);
                        const key = `${itemId}-${item.size}`;
                        uniqueItemsMap[key] = {...item, id: itemId, price: parseFloat(item.price)};
                    });
                    
                    // Luego procesar productos locales
                    localCartItems.forEach(item => {
                        const itemId = String(item.id);
                        const key = `${itemId}-${item.size}`;
                        
                        if (uniqueItemsMap[key]) {
                            uniqueItemsMap[key].quantity += item.quantity;
                        } else {
                            uniqueItemsMap[key] = {...item, id: itemId, price: parseFloat(item.price)};
                        }
                    });
                    
                    // Convertir el objeto a un array
                    const mergedItems = Object.values(uniqueItemsMap);
                    console.log('Productos a guardar en DB:', mergedItems);
                    
                    // IMPORTANTE: Guardar en base de datos ANTES de borrar localStorage
                    try {
                        await saveCartToDatabase(mergedItems);
                        console.log('Carrito guardado en base de datos correctamente');
                        
                        // AHORA es seguro actualizar localStorage
                        localStorage.setItem('cartItems', JSON.stringify(mergedItems));
                        console.log('Carrito local actualizado con productos combinados');
                    } catch (error) {
                        console.error('Error al guardar en la base de datos:', error);
                        // No borramos localStorage si hubo un error
                    }
                } else {
                    console.log('No hay productos locales que sincronizar');
                    // Cargar desde la base de datos
                    loadCartFromDatabase();
                }
                
                // Renderizar los productos del carrito
                renderCartItems();
                
            } catch (error) {
                console.error('Error en la sincronización del carrito:', error);
            }
        }
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
                // Reemplazar completamente el localStorage
                localStorage.setItem('cartItems', JSON.stringify(normalizedDbItems));
                
                // Renderizar los productos
                renderCartItems();
            } catch (error) {
                console.error('Error al cargar el carrito desde la base de datos:', error);
            }
        }
    }

    async function verificarStock() {
        const cartItems = getCartItems();
        let stockValid = true;
        let errorMessage = '';
        
        try {
            for (const item of cartItems) {
                // Obtener stock actual del servidor
                const response = await fetch(`/check-stock/${item.id}/${item.size}`);
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error('Error al verificar stock');
                }
                
                const stockDisponible = data.stock || 0;
                
                // Verificar si hay suficiente stock
                if (item.quantity > stockDisponible) {
                    stockValid = false;
                    errorMessage = `No hay suficiente stock para "${item.name}" (talla ${item.size}). Stock disponible: ${stockDisponible}`;
                    break;
                }
            }
            
            return { valid: stockValid, message: errorMessage };
        } catch (error) {
            console.error('Error al verificar stock:', error);
            return { valid: false, message: 'Error al verificar disponibilidad' };
        }
    }

    async function verificarStock() {
        const cartItems = getCartItems();
        let stockAdjusted = false;
        let adjustmentMessage = '';
        let adjustments = [];
        
        try {
            for (const item of cartItems) {
                // Obtener stock actual del servidor
                const response = await fetch(`/check-stock/${item.id}/${item.size}`);
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error('Error al verificar stock');
                }
                
                const stockDisponible = data.stock || 0;
                
                // Verificar si hay suficiente stock
                if (item.quantity > stockDisponible) {
                    // En lugar de fallar, ajustar al máximo disponible
                    const oldQuantity = item.quantity;
                    item.quantity = stockDisponible;
                    stockAdjusted = true;
                    
                    // Guardar información del ajuste
                    adjustments.push({
                        name: item.name,
                        size: item.size,
                        oldQuantity: oldQuantity,
                        newQuantity: stockDisponible
                    });
                }
            }
            
            // Si se ajustó algún producto, actualizar el carrito
            if (stockAdjusted) {
                // Crear mensaje de ajuste para el usuario
                adjustmentMessage = "Se han ajustado algunas cantidades debido a limitaciones de stock:\n";
                adjustments.forEach(adj => {
                    adjustmentMessage += `- "${adj.name}" (talla ${adj.size}): cambio de ${adj.oldQuantity} a ${adj.newQuantity} unidades\n`;
                });
                
                // Guardar carrito actualizado
                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                
                // Si el usuario está logueado, actualizar también en base de datos
                if (userId) {
                    await saveCartToDatabase(cartItems);
                }
                
                // Volver a renderizar los items del carrito
                renderCartItems();
            }
            
            return { 
                valid: true, 
                adjusted: stockAdjusted, 
                message: adjustmentMessage 
            };
        } catch (error) {
            console.error('Error al verificar stock:', error);
            return { 
                valid: false, 
                adjusted: false, 
                message: 'Error al verificar disponibilidad' 
            };
        }
    }

    // Manejar el checkout
    if (checkoutButton) {
        checkoutButton.addEventListener('click', async function() {
            // Si hay productos en el carrito
            const cartItems = getCartItems();
            
            if (cartItems.length === 0) {
                alert('Tu carrito está vacío');
                return;
            }
            
            // Verificar si el usuario está autenticado
            if (!userId) {
                // Cerrar el popup del carrito
                cartPopupBackground.classList.add('hidden');
                
                // Mostrar el popup de inicio de sesión
                const popupBackground = document.getElementById('popupBackground');
                const loginButton = document.getElementById('loginButton');
                const registerButton = document.getElementById('registerButton');
                const loginForm = document.getElementById('loginForm');
                const registerForm = document.getElementById('registerForm');
                
                // Mostrar el popup de login
                if (popupBackground) {
                    popupBackground.style.display = 'flex';
                    
                    // Asegurarse de que el formulario de login está activo
                    if (loginButton && registerButton) {
                        loginButton.classList.add('active');
                        registerButton.classList.remove('active');
                    }
                    
                    if (loginForm && registerForm) {
                        loginForm.classList.remove('hidden');
                        registerForm.classList.add('hidden');
                    }
                    
                    // Añadir mensaje especial para el usuario
                    const loginMessage = document.createElement('div');
                    loginMessage.className = 'login-message';
                    loginMessage.style.color = '#594C45';
                    loginMessage.style.marginBottom = '15px';
                    loginMessage.style.textAlign = 'center';
                    loginMessage.style.fontWeight = 'bold';
                    loginMessage.innerHTML = '¡Inicia sesión para finalizar tu compra!';
                    
                    // Insertar el mensaje al principio del formulario de login
                    const formContainer = loginForm.querySelector('form');
                    if (formContainer) {
                        formContainer.insertBefore(loginMessage, formContainer.firstChild);
                        
                        // Eliminar mensajes antiguos después de 5 segundos
                        setTimeout(() => {
                            const oldMessages = document.querySelectorAll('.login-message');
                            if (oldMessages.length > 1) {
                                oldMessages.forEach((msg, index) => {
                                    if (index < oldMessages.length - 1) {
                                        msg.remove();
                                    }
                                });
                            }
                        }, 5000);
                    }
                }
                
                return;
            }

            // Verificar y ajustar stock antes de continuar
            const stockCheck = await verificarStock();
            
            if (!stockCheck.valid) {
                alert(stockCheck.message);
                return;
            }
            
            // Si se ajustaron cantidades, mostrar mensaje al usuario
            if (stockCheck.adjusted) {
                alert(stockCheck.message);
            }
            
            // Si el stock es válido (después de los ajustes), redirigir a checkout
            window.location.href = '/checkout';

        });
    }

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
            // También limpiar el flag de sincronización
            localStorage.removeItem('cartSynced');
            console.log('Carrito y estado de sincronización limpiados al cerrar sesión');
        });
    }
});