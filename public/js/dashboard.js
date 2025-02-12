document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.sidebar ul li a');
    const sections = document.querySelectorAll('.main-content > div');
    const addProductButton = document.getElementById('addProductButton');
    const addProductForm = document.getElementById('addProductForm');
    const productSearch = document.getElementById('productSearch');
    const productRows = document.querySelectorAll('tbody tr');

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
});

document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('.sidebar ul li a');
    const sections = document.querySelectorAll('.main-content > div');
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
            const response = await fetch(`/categories/${categoryId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                location.reload();
            } else {
                console.error('Error al eliminar la categoría:', response.statusText);
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
            const response = await fetch(`/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                location.reload();
            } else {
                console.error('Error al eliminar la categoría:', response.statusText);
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