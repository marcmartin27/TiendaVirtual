document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.sidebar ul li a');
    const sections = document.querySelectorAll('.main-content > div');
    const addProductButton = document.getElementById('addProductButton');
    const addProductForm = document.getElementById('addProductForm');
    const productSearch = document.getElementById('productSearch');
    const productRows = document.querySelectorAll('tbody tr');
    const addUserButton = document.getElementById('addUserButton');
    const addUserForm = document.getElementById('addUserForm');
    const editUserButtons = document.querySelectorAll('.editUserButton');
    const deleteUserButtons = document.querySelectorAll('.deleteUserButton');
    const editUserModal = document.getElementById('editUserModal');
    const editUserForm = document.getElementById('editUserForm');
    const userSearch = document.getElementById('userSearch');
    const userRows = document.querySelectorAll('tbody tr');
    const addOrderButton = document.getElementById('addOrderButton');
    const addOrderForm = document.getElementById('addOrderForm');
    const editOrderButtons = document.querySelectorAll('.editOrderButton');
    const deleteOrderButtons = document.querySelectorAll('.deleteOrderButton');
    const editOrderModal = document.getElementById('editOrderModal');
    const editOrderForm = document.getElementById('editOrderForm');
    const orderSearch = document.getElementById('orderSearch');
    const orderRows = document.querySelectorAll('tbody tr');

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

    addProductButton.addEventListener('click', () => {
        addProductForm.classList.toggle('hidden');
    });

    productSearch.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        productRows.forEach(row => {
            const productName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            if (productName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    addUserButton.addEventListener('click', () => {
        addUserForm.classList.toggle('hidden');
    });

    editUserButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const userId = e.target.getAttribute('data-user-id');
            const response = await fetch(`/users/${userId}/edit`);
            const user = await response.json();

            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            editUserForm.action = `/users/${userId}`;

            editUserModal.classList.remove('hidden');
        });
    });

    deleteUserButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const userId = e.target.getAttribute('data-user-id');
            
            // SweetAlert confirmation
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, bórralo!'
            });
    
            if (result.isConfirmed) {
                await fetch(`/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
    
                // Recargar la página después de la eliminación
                location.reload();
            }
        });
    });

    userSearch.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        userRows.forEach(row => {
            const userName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            if (userName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    addOrderButton.addEventListener('click', () => {
        addOrderForm.classList.toggle('hidden');
    });

    editOrderButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const orderId = e.target.getAttribute('data-order-id');
            const response = await fetch(`/orders/${orderId}/edit`);
            const order = await response.json();

            document.getElementById('editUserId').value = order.user_id;
            document.getElementById('editTotal').value = order.total;
            document.getElementById('editStatus').value = order.status;
            editOrderForm.action = `/orders/${orderId}`;

            editOrderModal.classList.remove('hidden');
        });
    });

    deleteOrderButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const orderId = e.target.getAttribute('data-order-id');
            
            // SweetAlert confirmation
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, bórralo!'
            });
    
            if (result.isConfirmed) {
                await fetch(`/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
    
                // Recargar la página después de la eliminación
                location.reload();
            }
        });
    });

    orderSearch.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        orderRows.forEach(row => {
            const orderUserName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            if (orderUserName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    const addCategoryButton = document.getElementById('addCategoryButton');
    const addCategoryForm = document.getElementById('addCategoryForm');
    const editCategoryButtons = document.querySelectorAll('.editCategoryButton');
    const deleteCategoryButtons = document.querySelectorAll('.deleteCategoryButton');
    const editCategoryModal = document.getElementById('editCategoryModal');
    const editCategoryForm = document.getElementById('editCategoryForm');
    const editProductButtons = document.querySelectorAll('.editProductButton');
    const deleteProductButtons = document.querySelectorAll('.deleteProductButton');
    const editProductModal = document.getElementById('editProductModal');
    const editProductForm = document.getElementById('editProductForm');
    const categorySearch = document.getElementById('categorySearch');
    const categoryRows = document.querySelectorAll('tbody tr');

    addCategoryButton.addEventListener('click', () => {
        addCategoryForm.classList.toggle('hidden');
    });

    editCategoryButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const categoryId = e.target.getAttribute('data-category-id');
            const response = await fetch(`/categories/${categoryId}/edit`);
            const category = await response.json();

            document.getElementById('editCode').value = category.code;
            document.getElementById('editName').value = category.name;
            editCategoryForm.action = `/categories/${categoryId}`;

            editCategoryModal.classList.remove('hidden');
        });
    });

    deleteCategoryButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const categoryId = e.target.getAttribute('data-category-id');
            
            // SweetAlert confirmation
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, bórralo!'
            });
    
            if (result.isConfirmed) {
                await fetch(`/categories/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
    
                // Recargar la página después de la eliminación
                location.reload();
            }
        });
    });

    editProductButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const productId = e.target.getAttribute('data-product-id');
            const response = await fetch(`/products/${productId}/edit`);
            const product = await response.json();

            document.getElementById('editCode').value = product.code;
            document.getElementById('editName').value = product.name;
            document.getElementById('editDescription').value = product.description;
            document.getElementById('editCategoryId').value = product.category_id;
            document.getElementById('editPrice').value = product.price;
            document.getElementById('editFeatured').checked = product.featured;
            editProductForm.action = `/products/${productId}`;

            editProductModal.classList.remove('hidden');
        });
    });

    deleteProductButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const productId = e.target.getAttribute('data-product-id');
            
            // SweetAlert confirmation
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, bórralo!'
            });
    
            if (result.isConfirmed) {
                await fetch(`/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
    
                // Recargar la página después de la eliminación
                location.reload();
            }
        });
    });

    categorySearch.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        categoryRows.forEach(row => {
            const categoryName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            if (categoryName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

        // Inicializar gráficos si estamos en la sección de dashboard
        if (document.getElementById('productsChart') && document.getElementById('ordersChart')) {
            initCharts();
        }
        
// Reemplaza la función initCharts actual con esta versión
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
    ctx.font = '12px Roboto';
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
    ctx.font = '14px Roboto';
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
    
    ctx.font = '12px Roboto';
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
        
        // ELIMINAR ESTA LÍNEA QUE ESTÁ CAUSANDO LA DUPLICACIÓN
        // ctx.fillStyle = '#333';
        // ctx.fillText(labels[i], x + barWidth / 2, margin.top + height + 10);
        
        // Si los nombres de los meses son largos, rotarlos
        ctx.fillStyle = '#333';
        // Reemplazar este bloque:
        // Reemplazar este bloque condicional:
        if (labels[i].length > 4) {
            ctx.save();
            ctx.translate(x + barWidth / 2, margin.top + height + 25);
            ctx.rotate(Math.PI / 8);
            ctx.textAlign = 'right';
            ctx.restore();
        }

        // Por este código simple que mostrará todos los meses en horizontal:
        ctx.fillStyle = '#333';
        ctx.fillText(labels[i], x + barWidth / 2, margin.top + height + 25);

        // Por este código que mostrará todos los meses rectos:
    }
        
    // Título del eje X - ajustar la posición vertical
    ctx.fillStyle = '#333';
    ctx.textAlign = 'center';
    ctx.font = '14px Roboto';
    ctx.fillText('Mes', margin.left + width / 2, canvas.height - 20); // Cambiar de -10 a -20
    
    // Añadir un borde al canvas para mayor claridad
    ctx.strokeStyle = '#ccc';
    ctx.strokeRect(0, 0, canvas.width, canvas.height);
}
});