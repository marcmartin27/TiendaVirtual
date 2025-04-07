class ProductSearcher {
    constructor() {
      // Propiedades DOM
      this.searchInput = null;
      this.searchPopup = null;
      this.searchResultsList = null;
      
      // Bind de métodos para mantener el contexto
      this.handleInput = this.handleInput.bind(this);
      this.handleDocumentClick = this.handleDocumentClick.bind(this);
    }
    
    // Inicializar el buscador
    init() {
      // Obtener referencias DOM
      this.searchInput = document.getElementById('buscador');
      this.searchPopup = document.getElementById('searchPopup');
      this.searchResultsList = document.getElementById('searchResultsList');
      
      // Validar que existen los elementos necesarios
      if (!this.searchInput || !this.searchPopup || !this.searchResultsList) {
        console.error('Error: No se encontraron los elementos necesarios para el buscador');
        return;
      }
      
      // Agregar event listeners
      this.searchInput.addEventListener('input', this.handleInput);
      document.addEventListener('click', this.handleDocumentClick);
    }
    
    // Manejar el evento de input
    handleInput() {
      const query = this.searchInput.value;
      
      if (query.length > 2) {
        this.searchProducts(query);
      } else {
        this.hideResults();
      }
    }
    
    // Realizar la búsqueda
    searchProducts(query) {
      fetch(`/search?query=${query}`)
        .then(response => response.json())
        .then(products => {
          this.displayResults(products, query);
        })
        .catch(error => {
          console.error('Error en la búsqueda:', error);
        });
    }
    
    // Mostrar resultados
    displayResults(products, query) {
      this.searchResultsList.innerHTML = '';
      
      if (products.length === 0) {
        this.displayNoResults(query);
      } else {
        this.displayProductList(products);
      }
      
      this.searchPopup.classList.remove('hidden');
    }
    
    // Mostrar mensaje de no resultados
    displayNoResults(query) {
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
      this.searchResultsList.appendChild(noResultsItem);
    }
    
    // Mostrar lista de productos
    displayProductList(products) {
      products.forEach(product => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = `/product/${product.id}`;
        a.innerHTML = `<span>${product.name}</span><span>${product.price}€</span>`;
        const img = document.createElement('img');
        img.src = `/images/${product.images[0].image_url}`;
        li.appendChild(img);
        li.appendChild(a);
        this.searchResultsList.appendChild(li);
      });
    }
    
    // Ocultar resultados
    hideResults() {
      this.searchPopup.classList.add('hidden');
    }
    
    // Manejar click en documento
    handleDocumentClick(event) {
      if (!this.searchPopup.contains(event.target) && event.target !== this.searchInput) {
        this.hideResults();
      }
    }
  }
  
  // Inicializar el buscador cuando el DOM esté listo
  document.addEventListener('DOMContentLoaded', function() {
    const productSearcher = new ProductSearcher();
    productSearcher.init();
  });