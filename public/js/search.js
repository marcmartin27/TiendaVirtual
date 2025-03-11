document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('buscador');
    const searchPopup = document.getElementById('searchPopup');
    const searchResultsList = document.getElementById('searchResultsList');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value;

        if (query.length > 2) {
            fetch(`/search?query=${query}`)
                .then(response => response.json())
                .then(products => {
                    searchResultsList.innerHTML = '';

                    // Verificar si no hay productos encontrados
                    if (products.length === 0) {
                        // Crear un elemento para el mensaje "no encontrado"
                        const noResultsItem = document.createElement('li');
                        noResultsItem.classList.add('no-results');
                        noResultsItem.innerHTML = `
                            <div class="no-results-message">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    <line x1="8" y1="11" x2="14" y2="11"></line>
                                </svg>
                                <p>No se encontraron productos que coincidan con "<strong>${query}</strong>"</p>
                            </div>
                        `;
                        searchResultsList.appendChild(noResultsItem);
                    } else {
                        // Código existente para mostrar los productos
                        products.forEach(product => {
                            const li = document.createElement('li');
                            const a = document.createElement('a');
                            a.href = `/products/${product.id}`;
                            a.innerHTML = `<span>${product.name}</span><span>$${product.price}</span>`;
                            const img = document.createElement('img');
                            img.src = `/images/${product.images[0].image_url}`;
                            li.appendChild(img);
                            li.appendChild(a);
                            searchResultsList.appendChild(li);
                        });
                    }

                    searchPopup.classList.remove('hidden');
                });
        } else {
            searchPopup.classList.add('hidden');
        }
    });

    document.addEventListener('click', function (event) {
        if (!searchPopup.contains(event.target) && event.target !== searchInput) {
            searchPopup.classList.add('hidden');
        }
    });
});