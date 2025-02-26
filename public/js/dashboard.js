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
});