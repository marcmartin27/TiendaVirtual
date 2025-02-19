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