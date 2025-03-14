document.addEventListener('DOMContentLoaded', function() {
    // Función para ocultar las alertas después de un tiempo
    function hideAlerts() {
        const alerts = document.querySelectorAll('.alert');
        
        if (alerts.length > 0) {
            // Para cada alerta encontrada
            alerts.forEach(function(alert) {
                // Establecer un temporizador para ocultarla después de 2 segundos
                setTimeout(function() {
                    // Agregar una transición suave
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    
                    // Eliminar completamente después de que termine la transición
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 2000); // 2000ms = 2 segundos
            });
        }
    }

    // Ejecutar la función cuando se carga la página
    hideAlerts();
});