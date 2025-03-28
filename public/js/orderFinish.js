// Variables para gestionar descuentos
let discountApplied = false;
let discountAmount = 0;  // Variable global
let discountCode = '';

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar la página
    renderCartSummary();
    prefillUserData();
    initCouponSystem();
    
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
    
    // Inicializar el sistema de cupones
    function initCouponSystem() {
        const applyButton = document.getElementById('apply-coupon');
        const couponInput = document.getElementById('coupon_code');
        
        if (applyButton) {
            applyButton.addEventListener('click', function() {
                const code = couponInput.value.trim();
                
                if (!code) {
                    showCouponMessage('Por favor ingresa un código de descuento', false);
                    return;
                }
                
                applyCouponCode(code);
            });
        }
        
        // También permitir aplicar el cupón al presionar Enter
        if (couponInput) {
            couponInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (applyButton) applyButton.click();
                }
            });
        }
    }
});

// Función para aplicar código de descuento
function applyCouponCode(code) {
    const couponMessage = document.getElementById('coupon-message');
    const discountRow = document.getElementById('discount-row');
    const discountAmountElement = document.getElementById('discount-amount');  // Cambiado aquí
    const discountValue = document.getElementById('discount_value');
    const appliedCoupon = document.getElementById('applied_coupon');
    const applyButton = document.getElementById('apply-coupon');
    
    // Mostrar estado de carga
    const originalText = applyButton.textContent;
    applyButton.textContent = 'Validando...';
    applyButton.disabled = true;
    
    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Hacer una petición al servidor para validar el cupón
    fetch('/validate-coupon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            code: code
        })
    })
    .then(response => response.json())
    .then(data => {
        // Restaurar el botón
        applyButton.textContent = originalText;
        
        if (data.valid) {
            // Cupón válido
            let discount = 0;
            const subtotalValue = parseFloat(document.getElementById('subtotal').innerText.replace('€', '').trim());
            
            if (data.type === 'percentage') {
                discount = (subtotalValue * data.discount) / 100;
                showCouponMessage(`¡Cupón aplicado! Descuento del ${data.discount}% (${discount.toFixed(2)} €)`, true);
            } else {
                discount = Math.min(data.discount, subtotalValue);
                showCouponMessage(`¡Cupón aplicado! Descuento de ${discount.toFixed(2)} €`, true);
            }
            
            // Redondear a dos decimales
            discount = Math.round(discount * 100) / 100;
            
            // Actualizar UI
            discountRow.style.display = 'flex';
            discountAmountElement.innerText = `-${discount.toFixed(2)} €`;  // Cambiado aquí
            discountValue.value = discount;
            appliedCoupon.value = code;
            
            // Actualizar el total
            updateTotal(subtotalValue - discount);
            
            // Deshabilitar el campo y el botón
            document.getElementById('coupon_code').disabled = true;
            document.getElementById('apply-coupon').disabled = true;
            
            // Guardar estado de descuento
            discountApplied = true;
            discountAmount = discount;  // Ahora funciona correctamente
            discountCode = code;
            
        } else {
            // Cupón inválido
            applyButton.disabled = false;
            discountRow.style.display = 'none';
            discountValue.value = 0;
            appliedCoupon.value = '';
            
            // Recalcular el total sin descuento
            const subtotalValue = parseFloat(document.getElementById('subtotal').innerText.replace('€', '').trim());
            updateTotal(subtotalValue);
            
            showCouponMessage(data.message || 'Código de descuento inválido', false);
            
            // Resetear estado de descuento
            discountApplied = false;
            discountAmount = 0;
            discountCode = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        applyButton.textContent = originalText;
        applyButton.disabled = false;
        showCouponMessage('Error al validar el cupón. Intenta más tarde.', false);
    });
}

// Función para mostrar mensaje sobre el código de descuento
function showCouponMessage(message, isValid) {
    const couponMessage = document.getElementById('coupon-message');
    if (!couponMessage) return;
    
    couponMessage.innerText = message;
    couponMessage.className = 'coupon-message';
    
    if (isValid) {
        couponMessage.classList.add('coupon-valid');
    } else {
        couponMessage.classList.add('coupon-invalid');
    }
}

// Función para actualizar el total con descuento
function updateTotal(newTotal) {
    const totalElement = document.getElementById('total');
    if (totalElement) {
        totalElement.innerText = newTotal.toFixed(2) + ' €';
    }
}

// Verificar que los valores de descuento se envían correctamente
document.querySelector('form').addEventListener('submit', function(e) {
    // Imprimir en consola para depuración
    console.log('Enviando formulario con descuento:', document.getElementById('discount_value').value);
    console.log('Cupón aplicado:', document.getElementById('applied_coupon').value);
    
    // También puedes actualizar los valores justo antes de enviar
    if (discountApplied && discountAmount > 0) {
        document.getElementById('discount_value').value = discountAmount;
        document.getElementById('applied_coupon').value = discountCode;
    }
});