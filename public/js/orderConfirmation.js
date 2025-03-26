document.addEventListener('DOMContentLoaded', function() {
    // Animar la entrada de los elementos
    const elements = document.querySelectorAll('.confirmation-header, .order-summary, .confirmation-section, .order-item, .confirmation-actions');
    
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});