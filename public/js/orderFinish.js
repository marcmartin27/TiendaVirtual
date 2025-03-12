document.addEventListener('DOMContentLoaded', function() {
    // Obtener los items del carrito desde localStorage
    function getCartItems() {
        const cartItems = localStorage.getItem('cartItems');
        return cartItems ? JSON.parse(cartItems) : [];
    }
    
    // Renderizar los items del carrito en el resumen
    function renderCartSummary() {
        const cartItems = getCartItems();
        const summaryItemsContainer = document.getElementById('summaryItems');
        
        if (!summaryItemsContainer) return;
        
        summaryItemsContainer.innerHTML = '';
        
        let subtotal = 0;
        
        if (cartItems.length === 0) {
            summaryItemsContainer.innerHTML = '<p>No hay productos en el carrito</p>';
            return;
        }
        
        cartItems.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            const summaryItem = document.createElement('div');
            summaryItem.classList.add('summary-item');
            
            summaryItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <div class="item-details">
                    <div class="item-name">${item.name}</div>
                    <div class="item-meta">Talla: ${item.size} × ${item.quantity}</div>
                </div>
                <div class="item-price">${itemTotal.toFixed(2)} €</div>
            `;
            
            summaryItemsContainer.appendChild(summaryItem);
        });
        
        // Actualizar subtotal y total
        document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' €';
        document.getElementById('total').textContent = subtotal.toFixed(2) + ' €';
        
        // Guardar datos del carrito para enviar con el formulario
        document.getElementById('cartData').value = JSON.stringify(cartItems);
    }
    
    // Si el usuario está logueado, intentar prellenar los campos con sus datos
    function prefillUserData() {
        const userId = document.getElementById('userId')?.value;
        
        if (userId) {
            fetch(`/user-address/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        
                        // Rellenar los campos si existen datos
                        if (user.first_name) document.getElementById('first_name').value = user.first_name;
                        if (user.last_name) document.getElementById('last_name').value = user.last_name;
                        if (user.address) document.getElementById('address').value = user.address;
                        if (user.apartment) document.getElementById('apartment').value = user.apartment;
                        if (user.postal_code) document.getElementById('postal_code').value = user.postal_code;
                        if (user.city) document.getElementById('city').value = user.city;
                        if (user.province) document.getElementById('province').value = user.province;
                        if (user.phone) document.getElementById('phone').value = user.phone;
                        if (user.country) document.getElementById('country').value = user.country;
                    }
                })
                .catch(err => console.error('Error al cargar datos del usuario:', err));
        }
    }
    
    // Inicializar la página
    renderCartSummary();
    prefillUserData();
});