document.addEventListener('DOMContentLoaded', () => {
    // Selección de secciones y navegación
    const links = document.querySelectorAll('.sidebar ul li a');
    const sections = document.querySelectorAll('.main-content > div#dashboard, .main-content > div#products, .main-content > div#categories, .main-content > div#users, .main-content > div#orders');
    
    // ===== Navegación entre secciones =====
    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const sectionId = link.getAttribute('data-section');

            sections.forEach(section => {
                if (section.id === sectionId) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });
        });
    });

    // ===== Funciones para manejar modales =====
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevenir scroll en el fondo
        }
    }
    
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restaurar scroll
        }
    }
    
    // Cerrar modal al hacer clic en el botón de cerrar o fuera del modal
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        // Cerrar con el botón X
        const closeBtn = modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Cerrar al hacer clic fuera del modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // ===== PRODUCTOS =====
    // Botón añadir producto
    const addProductButton = document.getElementById('addProductButton');
    if (addProductButton) {
        addProductButton.addEventListener('click', () => {
            openModal('addProductModal');
        });
    }
    
    // Búsqueda de productos
    const productSearch = document.getElementById('productSearch');
    if (productSearch) {
        productSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const productRows = document.querySelectorAll('#products tbody tr');
            
            productRows.forEach(row => {
                const productName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (productName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Botones editar producto
    document.querySelectorAll('.editProductButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const productId = e.target.getAttribute('data-product-id');
            try {
                const response = await fetch(`/products/${productId}/edit`);
                const product = await response.json();
                
                // Actualizar campos del formulario
                document.getElementById('product_editCode').value = product.code;
                document.getElementById('product_editName').value = product.name;
                document.getElementById('product_editDescription').value = product.description || '';
                document.getElementById('product_editCategoryId').value = product.category_id;
                document.getElementById('product_editPrice').value = product.price;
                document.getElementById('product_editFeatured').checked = product.featured;
                document.getElementById('editProductForm').action = `/products/${productId}`;
                
                // Cargar y mostrar las imágenes actuales
                const imagesContainer = document.getElementById('current-images-container');
                imagesContainer.innerHTML = '';
                
                if (product.images && product.images.length > 0) {
                    product.images.forEach(image => {
                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'image-container';
                        
                        const img = document.createElement('img');
                        img.src = `/images/${image.image_url}`;
                        img.alt = product.name;
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'remove-image';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.dataset.imageId = image.id;
                        removeBtn.addEventListener('click', function(event) {
                            event.preventDefault();
                            if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
                                deleteProductImage(image.id, imageContainer);
                            }
                        });
                        
                        imageContainer.appendChild(img);
                        imageContainer.appendChild(removeBtn);
                        imagesContainer.appendChild(imageContainer);
                    });
                } else {
                    imagesContainer.innerHTML = '<p>No hay imágenes disponibles para este producto.</p>';
                }
                
                openModal('editProductModal');
            } catch (error) {
                console.error('Error al cargar datos del producto:', error);
            }
        });
    });

    // Función para eliminar una imagen de producto
    async function deleteProductImage(imageId, containerElement) {
        try {
            const response = await fetch(`/product-images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // Eliminar el contenedor de la imagen del DOM
                containerElement.remove();
                alert(result.message || 'Imagen eliminada correctamente');
            } else {
                alert(result.error || 'Error al eliminar la imagen');
            }
        } catch (error) {
            console.error('Error al eliminar la imagen:', error);
            alert('Error al procesar la solicitud');
        }
    }
    
    // Botones eliminar producto
    document.querySelectorAll('.deleteProductButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const productId = e.target.getAttribute('data-product-id');
            
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                try {
                    await fetch(`/products/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    // Recargar la página después de la eliminación
                    location.reload();
                } catch (error) {
                    console.error('Error al eliminar producto:', error);
                }
            }
        });
    });
    
    // ===== CATEGORÍAS =====
    // Botón añadir categoría
    const addCategoryButton = document.getElementById('addCategoryButton');
    if (addCategoryButton) {
        addCategoryButton.addEventListener('click', () => {
            openModal('addCategoryModal');
        });
    }
    
    // Búsqueda de categorías
    const categorySearch = document.getElementById('categorySearch');
    if (categorySearch) {
        categorySearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const categoryRows = document.querySelectorAll('#categories tbody tr');
            
            categoryRows.forEach(row => {
                const categoryName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (categoryName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Botones editar categoría
    document.querySelectorAll('.editCategoryButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const categoryId = e.target.getAttribute('data-category-id');
            try {
                const response = await fetch(`/categories/${categoryId}/edit`);
                const category = await response.json();
                
                // Actualizar estos IDs
                document.getElementById('category_editCode').value = category.code;
                document.getElementById('category_editName').value = category.name;
                document.getElementById('editCategoryForm').action = `/categories/${categoryId}`;
                
                openModal('editCategoryModal');
            } catch (error) {
                console.error('Error al cargar datos de la categoría:', error);
            }
        });
    });
    
    // Botones eliminar categoría
    document.querySelectorAll('.deleteCategoryButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const categoryId = e.target.getAttribute('data-category-id');
            
            if (confirm('¿Estás seguro de que deseas eliminar esta categoría?')) {
                try {
                    await fetch(`/categories/${categoryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    // Recargar la página después de la eliminación
                    location.reload();
                } catch (error) {
                    console.error('Error al eliminar categoría:', error);
                }
            }
        });
    });
    
    // ===== USUARIOS =====
    // Botón añadir usuario
    const addUserButton = document.getElementById('addUserButton');
    if (addUserButton) {
        addUserButton.addEventListener('click', () => {
            openModal('addUserModal');
        });
    }
    
    // Búsqueda de usuarios
    const userSearch = document.getElementById('userSearch');
    if (userSearch) {
        userSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const userRows = document.querySelectorAll('#users tbody tr');
            
            userRows.forEach(row => {
                const userName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (userName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Botones editar usuario
    document.querySelectorAll('.editUserButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const userId = e.target.getAttribute('data-user-id');
            try {
                const response = await fetch(`/users/${userId}/edit`);
                const user = await response.json();
                
                // Actualizar estos IDs
                document.getElementById('user_editName').value = user.name;
                document.getElementById('user_editEmail').value = user.email;
                document.getElementById('editUserForm').action = `/users/${userId}`;
                
                openModal('editUserModal');
            } catch (error) {
                console.error('Error al cargar datos del usuario:', error);
            }
        });
    });
    
    // Botones eliminar usuario
    document.querySelectorAll('.deleteUserButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const userId = e.target.getAttribute('data-user-id');
            
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                try {
                    await fetch(`/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    // Recargar la página después de la eliminación
                    location.reload();
                } catch (error) {
                    console.error('Error al eliminar usuario:', error);
                }
            }
        });
    });
    
    // ===== PEDIDOS =====
    // Botón añadir pedido
    const addOrderButton = document.getElementById('addOrderButton');
    if (addOrderButton) {
        addOrderButton.addEventListener('click', () => {
            openModal('addOrderModal');
        });
    }
    
    // Búsqueda de pedidos
    const orderSearch = document.getElementById('orderSearch');
    if (orderSearch) {
        orderSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const orderRows = document.querySelectorAll('#orders tbody tr');
            
            orderRows.forEach(row => {
                const orderUserName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (orderUserName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Botones editar pedido
    document.querySelectorAll('.editOrderButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const orderId = e.target.getAttribute('data-order-id');
            try {
                const response = await fetch(`/orders/${orderId}/edit`);
                const order = await response.json();
                
                // Actualizar estos IDs
                document.getElementById('order_editUserId').value = order.user_id;
                document.getElementById('order_editTotal').value = order.total;
                document.getElementById('order_editStatus').value = order.status;
                document.getElementById('editOrderForm').action = `/orders/${orderId}`;
                
                openModal('editOrderModal');
            } catch (error) {
                console.error('Error al cargar datos del pedido:', error);
            }
        });
    });
    
    // Botones eliminar pedido
    document.querySelectorAll('.deleteOrderButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const orderId = e.target.getAttribute('data-order-id');
            
            if (confirm('¿Estás seguro de que deseas eliminar este pedido?')) {
                try {
                    await fetch(`/orders/${orderId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    // Recargar la página después de la eliminación
                    location.reload();
                } catch (error) {
                    console.error('Error al eliminar pedido:', error);
                }
            }
        });
    });

    // Botones ver detalles del pedido
    document.querySelectorAll('.viewOrderDetailsButton').forEach(button => {
        button.addEventListener('click', async (e) => {
            const orderId = e.target.getAttribute('data-order-id');
            try {
                const response = await fetch(`/orders/${orderId}/details`);
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                
                const orderDetails = await response.json();
                console.log("Datos del pedido completo:", orderDetails); // Depuración
                
                // Actualizar la información del pedido en el modal
                document.getElementById('orderIdDisplay').textContent = orderDetails.id;
                document.getElementById('orderCustomerName').textContent = orderDetails.user ? orderDetails.user.name : 'Cliente no disponible';
                document.getElementById('orderDate').textContent = new Date(orderDetails.created_at).toLocaleString();
                document.getElementById('orderStatus').textContent = orderDetails.status;
                document.getElementById('orderTotal').textContent = orderDetails.total;
                
                // Mostrar información de envío
                // Los datos de envío están directamente en el objeto orderDetails, no en shipping_address
                document.getElementById('shippingName').textContent = 
                    `${orderDetails.first_name || ''} ${orderDetails.last_name || ''}`.trim() || 'No disponible';
                document.getElementById('shippingAddress').textContent = orderDetails.address || 'No disponible';
                document.getElementById('shippingApartment').textContent = orderDetails.apartment || 'No disponible';
                document.getElementById('shippingPostalCode').textContent = orderDetails.postal_code || 'No disponible';
                document.getElementById('shippingCity').textContent = orderDetails.city || 'No disponible';
                document.getElementById('shippingProvince').textContent = orderDetails.province || 'No disponible';
                document.getElementById('shippingCountry').textContent = orderDetails.country || 'No disponible';
                document.getElementById('shippingPhone').textContent = orderDetails.phone || 'No disponible';
                
                // Limpiar la tabla de productos
                const productsList = document.getElementById('orderProductsList');
                productsList.innerHTML = '';
                
                // Añadir los productos a la tabla
                if (orderDetails.order_items && orderDetails.order_items.length > 0) {
                    orderDetails.order_items.forEach(item => {
                        const tr = document.createElement('tr');
                        
                        // Imagen del producto
                        const imgTd = document.createElement('td');
                        const img = document.createElement('img');
                        img.src = item.product && item.product.images && item.product.images.length > 0 
                            ? `/images/${item.product.images[0].image_url}` 
                            : '/images/default-product.png';
                        img.alt = item.product ? item.product.name : 'Producto';
                        imgTd.appendChild(img);
                        tr.appendChild(imgTd);
                        
                        // Nombre del producto
                        const nameTd = document.createElement('td');
                        nameTd.textContent = item.product ? item.product.name : 'Producto no disponible';
                        tr.appendChild(nameTd);
                        
                        // Cantidad
                        const quantityTd = document.createElement('td');
                        quantityTd.textContent = item.quantity;
                        tr.appendChild(quantityTd);
                        
                        // Talla
                        const sizeTd = document.createElement('td');
                        sizeTd.textContent = item.size || 'N/A';
                        tr.appendChild(sizeTd);
                        
                        // Precio unitario
                        const priceTd = document.createElement('td');
                        priceTd.textContent = `${item.price}€`;
                        tr.appendChild(priceTd);
                        
                        // Subtotal
                        const subtotalTd = document.createElement('td');
                        subtotalTd.textContent = `${(item.price * item.quantity).toFixed(2)}€`;
                        tr.appendChild(subtotalTd);
                        
                        productsList.appendChild(tr);
                    });
                } else {
                    // Si no hay productos, mostrar mensaje
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.colSpan = 6;
                    td.textContent = 'No hay productos en este pedido';
                    td.style.textAlign = 'center';
                    tr.appendChild(td);
                    productsList.appendChild(tr);
                }
                
                // Mostrar el modal
                openModal('orderDetailsModal');
                
            } catch (error) {
                console.error('Error al cargar detalles del pedido:', error);
                alert('Error al cargar los detalles del pedido');
            }
        });
    });
    
    // Inicializar gráficos si estamos en la sección de dashboard
    if (document.getElementById('productsChart') && document.getElementById('ordersChart')) {
        initCharts();
    }
});

// Función para inicializar gráficos
function initCharts() {
    // Obtener datos del backend
    const productsByMonthElement = document.getElementById('productsByMonth');
    const ordersByMonthElement = document.getElementById('ordersByMonth');
    
    if (!productsByMonthElement || !ordersByMonthElement) return;
    
    const productsByMonth = JSON.parse(productsByMonthElement.value);
    const ordersByMonth = JSON.parse(ordersByMonthElement.value);
    const months = JSON.parse(document.getElementById('months').value);
    
    // Crear array de etiquetas de meses
    const monthLabels = Object.values(months);
    
    // Crear array de valores
    const productValues = Object.values(productsByMonth);
    const orderValues = Object.values(ordersByMonth);
    
    // Dibujar gráfico de productos
    drawBarChart('productsChart', monthLabels, productValues, 
        'Productos añadidos por mes', 'rgba(54, 162, 235, 0.5)', 'rgb(54, 162, 235)');
    
    // Dibujar gráfico de pedidos
    drawBarChart('ordersChart', monthLabels, orderValues, 
        'Pedidos realizados por mes', 'rgba(255, 99, 132, 0.5)', 'rgb(255, 99, 132)');
}

// Función para dibujar un gráfico de barras
function drawBarChart(canvasId, labels, data, title, fillColor, strokeColor) {
    const canvas = document.getElementById(canvasId);
    if (!canvas || !canvas.getContext) return;
    
    const ctx = canvas.getContext('2d');
    
    // Establecer dimensiones del canvas
    canvas.width = canvas.parentElement.clientWidth * 0.95;
    canvas.height = 280;
    
    // Márgenes y dimensiones
    const margin = {top: 40, right: 20, bottom: 60, left: 60};
    const width = canvas.width - margin.left - margin.right;
    const height = canvas.height - margin.top - margin.bottom;
    
    // Limpiar el canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Encontrar el valor máximo para escalar el gráfico
    const maxValue = Math.max(...data) || 10; // Valor mínimo de 10 si todos son 0
    const yScaleFactor = height / (maxValue * 1.1); // Añadir 10% de espacio
    
    // Dibujar el eje Y
    ctx.beginPath();
    ctx.moveTo(margin.left, margin.top);
    ctx.lineTo(margin.left, margin.top + height);
    ctx.strokeStyle = '#333';
    ctx.stroke();
    
    // Dibujar etiquetas del eje Y
    const yLabelCount = 5;
    ctx.font = '12px Arial';
    ctx.textAlign = 'right';
    ctx.textBaseline = 'middle';
    
    for (let i = 0; i <= yLabelCount; i++) {
        const yValue = (maxValue * i) / yLabelCount;
        const yPos = margin.top + height - (yValue * yScaleFactor);
        
        // Dibujar línea de cuadrícula
        ctx.beginPath();
        ctx.moveTo(margin.left - 5, yPos);
        ctx.lineTo(margin.left + width, yPos);
        ctx.strokeStyle = '#e0e0e0';
        ctx.stroke();
        
        // Dibujar etiqueta
        ctx.fillStyle = '#333';
        ctx.fillText(Math.round(yValue), margin.left - 10, yPos);
    }
    
    // Título del eje Y
    ctx.save();
    ctx.translate(20, margin.top + height / 2);
    ctx.rotate(-Math.PI / 2);
    ctx.textAlign = 'center';
    ctx.font = '14px Arial';
    ctx.fillText('Cantidad', 0, 0);
    ctx.restore();
    
    // Dibujar el eje X
    ctx.beginPath();
    ctx.moveTo(margin.left, margin.top + height);
    ctx.lineTo(margin.left + width, margin.top + height);
    ctx.strokeStyle = '#333';
    ctx.stroke();
    
    // Dibujar las barras y etiquetas del eje X
    const barWidth = width / labels.length * 0.7;
    const barSpacing = width / labels.length;
    
    ctx.font = '12px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'top';
    
    for (let i = 0; i < labels.length; i++) {
        const value = data[i] || 0;
        const barHeight = value * yScaleFactor;
        const x = margin.left + (i * barSpacing) + (barSpacing - barWidth) / 2;
        const y = margin.top + height - barHeight;
        
        // Dibujar la barra
        ctx.fillStyle = fillColor;
        ctx.strokeStyle = strokeColor;
        ctx.beginPath();
        ctx.rect(x, y, barWidth, barHeight);
        ctx.fill();
        ctx.stroke();
        
        // Dibujar valor encima de la barra
        if (value > 0) {
            ctx.fillStyle = '#333';
            ctx.fillText(value, x + barWidth / 2, y - 15);
        }
        
        ctx.fillStyle = '#333';
        ctx.fillText(labels[i], x + barWidth / 2, margin.top + height + 25);
    }
        
    // Título del eje X
    ctx.fillStyle = '#333';
    ctx.textAlign = 'center';
    ctx.font = '14px Arial';
    ctx.fillText('Mes', margin.left + width / 2, canvas.height - 20);
    
    ctx.strokeStyle = '#ccc';
    ctx.strokeRect(0, 0, canvas.width, canvas.height);
}

// CODIGO DE POP UP ACTUALIZAR STOCK

document.addEventListener('DOMContentLoaded', function() {
    const stockModal = document.getElementById('stockModal');
    const manageStockButton = document.getElementById('manageStockButton');
    const stockForm = document.getElementById('stockForm');

    // Manejar el botón de gestionar stock
    if (manageStockButton) {
        manageStockButton.addEventListener('click', function() {
            const productId = document.querySelector('#editProductForm').getAttribute('action').split('/').pop();
            document.getElementById('stock_product_id').value = productId;
            
            // Cargar el stock actual
            fetch(`/products/${productId}/stock`)
                .then(response => response.json())
                .then(sizes => {
                    // Rellenar los inputs con los valores actuales
                    for (let size = 36; size <= 47; size++) {
                        const input = document.getElementById(`size_${size}`);
                        input.value = sizes[size] || 0;
                    }
                    stockModal.classList.add('active'); // Cambiado de remove('hidden') a add('active')
                })
                .catch(error => console.error('Error:', error));
        });
    }

    // Manejar el formulario de stock
    if (stockForm) {
        stockForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const productId = document.getElementById('stock_product_id').value;
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                if (key.startsWith('sizes[')) {
                    const size = key.match(/\[(\d+)\]/)[1];
                    if (!data.sizes) data.sizes = {};
                    data.sizes[size] = value;
                } else {
                    data[key] = value;
                }
            });

            fetch(`/products/${productId}/stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                alert('Stock actualizado correctamente');
                stockModal.classList.remove('active'); // Cambiado de add('hidden') a remove('active')
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el stock');
            });
        });
    }

    // Cerrar modal de stock
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal-overlay').classList.remove('active'); // Cambiado de add('hidden') a remove('active')
        });
    });

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('active');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {

    // Variables para el stock inicial
    const addStockButton = document.getElementById('addStockButton');
    const addStockModal = document.getElementById('addStockModal');
    const saveInitialStockButton = document.getElementById('saveInitialStock');
    let initialStockData = {};

    // Manejar el botón de añadir stock inicial
    if (addStockButton) {
        addStockButton.addEventListener('click', function() {
            addStockModal.classList.add('active');
        });
    }

    // Guardar stock inicial
    if (saveInitialStockButton) {
        saveInitialStockButton.addEventListener('click', function() {
            // Recoger los valores de stock inicial
            for (let size = 36; size <= 47; size++) {
                const input = document.getElementById(`initial_size_${size}`);
                initialStockData[size] = input.value;
            }
            
            // Cerrar el modal
            addStockModal.classList.remove('active');
            alert('Stock inicial guardado');
        });
    }

    // Modificar el evento submit del formulario de añadir producto
    const addProductForm = document.querySelector('form[action*="products"]');
if (addProductForm) {
    addProductForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Añadir el stock inicial al FormData
        formData.append('initial_stock', JSON.stringify(initialStockData));

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (response.ok) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.error || 'Error al guardar el producto');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al guardar el producto');
        }
    });
}

    // Cerrar modal de stock inicial
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal-overlay').classList.remove('active');
        });
    });

    // ...existing code...
});