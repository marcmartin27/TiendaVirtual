document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad de pestañas
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Desactivar todas las pestañas y contenido
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Activar la pestaña seleccionada
            this.classList.add('active');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
    
    // Funcionalidad para expandir/colapsar pedidos
    const orderHeaders = document.querySelectorAll('.order-header');
    
    orderHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const orderCard = this.closest('.order-card');
            orderCard.classList.toggle('expanded');
            
            // Animar el ícono de toggle
            const toggleIcon = this.querySelector('.order-toggle svg');
            if (orderCard.classList.contains('expanded')) {
                toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                toggleIcon.style.transform = 'rotate(0)';
            }
        });
    });

    document.querySelectorAll('.order-card').forEach(card => {
        const orderHeader = card.querySelector('.order-header');
        const discountRow = card.querySelector('.discount-row');
        
        if (discountRow) {
            orderHeader.classList.add('has-discount');
        }
    });
});
