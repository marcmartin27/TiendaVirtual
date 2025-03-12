document.addEventListener('DOMContentLoaded', function () {
    // Referencias a elementos del DOM
    const customizeButton = document.getElementById('customize-product');
    const customizeModal = document.getElementById('customizeModal');
    const canvas = document.getElementById('productCanvas');
    const ctx = canvas.getContext('2d');
    const colorPicker = document.getElementById('drawColor');
    const brushSize = document.getElementById('brushSize');
    const clearCanvasButton = document.getElementById('clearCanvas');
    
    // Variables para dibujo
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    
    // Imagen del producto
    let productImage = new Image();


    let customizedImageData = null;
    const productId = document.querySelector('#add-to-cart').getAttribute('data-product-id');
    
    // Añadir botón de guardar en el modal
    const saveButton = document.createElement('button');
    saveButton.id = 'saveCustomization';
    saveButton.textContent = 'Guardar personalización';
    saveButton.classList.add('save-button');
    clearCanvasButton.parentNode.appendChild(saveButton);
    
    // Abrir el modal de personalización (modificación del código existente)
    customizeButton.addEventListener('click', function() {
        customizeModal.style.display = 'flex';
        
        // Obtener la primera imagen del producto
        const productImgSrc = document.querySelector('.product-image').src;
        loadImageToCanvas(productImgSrc);
        
        // Si ya hay una personalización guardada, cargarla
        const savedCustomization = localStorage.getItem(`customized_${productId}`);
        if (savedCustomization) {
            const img = new Image();
            img.onload = function() {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            };
            img.src = savedCustomization;
        }
    });
    
    // Guardar la personalización
    saveButton.addEventListener('click', function() {
        // Convertir el canvas a imagen base64
        customizedImageData = canvas.toDataURL('image/png');
        
        // Guardar en localStorage para este producto específico
        localStorage.setItem(`customized_${productId}`, customizedImageData);
        
        // Cerrar el modal
        customizeModal.style.display = 'none';
        
        // Mostrar notificación al usuario
        const notification = document.createElement('div');
        notification.classList.add('customization-notification');
        notification.textContent = 'Personalización guardada correctamente';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });


    
    // Cargar la imagen en el canvas
    function loadImageToCanvas(src) {
        productImage.src = src;
        productImage.onload = function() {
            // Ajustar el tamaño del canvas para que coincida con el contenedor
            const container = canvas.parentElement;
            canvas.width = container.clientWidth;
            canvas.height = container.clientHeight;
            
            // Mantener la relación de aspecto de la imagen
            const scale = Math.min(
                canvas.width / productImage.width,
                canvas.height / productImage.height
            );
            
            const x = (canvas.width / 2) - (productImage.width / 2) * scale;
            const y = (canvas.height / 2) - (productImage.height / 2) * scale;
            
            // Limpiar el canvas y dibujar la imagen
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(
                productImage, 
                0, 0, 
                productImage.width, 
                productImage.height,
                x, y, 
                productImage.width * scale, 
                productImage.height * scale
            );
        };
    }
    
    // Cerrar el modal de personalización
    window.closeCustomizeModal = function() {
        customizeModal.style.display = 'none';
    };
    
    // Eventos para dibujar en el canvas
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Eventos táctiles para dispositivos móviles
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', stopDrawing);
    
    function startDrawing(e) {
        isDrawing = true;
        [lastX, lastY] = [e.offsetX, e.offsetY];
    }
    
    function draw(e) {
        if (!isDrawing) return;
        
        ctx.strokeStyle = colorPicker.value;
        ctx.lineWidth = brushSize.value;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        
        [lastX, lastY] = [e.offsetX, e.offsetY];
    }
    
    function stopDrawing() {
        isDrawing = false;
    }
    
    function handleTouch(e) {
        e.preventDefault();
        
        if (e.type === 'touchstart') {
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;
            
            startDrawing({ offsetX: x, offsetY: y });
        } 
        else if (e.type === 'touchmove') {
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;
            
            draw({ offsetX: x, offsetY: y });
        }
    }
    
    // Limpiar el canvas (mantener solo la imagen)
    clearCanvasButton.addEventListener('click', function() {
        const productImgSrc = document.querySelector('.product-image').src;
        loadImageToCanvas(productImgSrc);
    });
    
    // Ajustar canvas cuando se redimensiona la ventana
    window.addEventListener('resize', function() {
        if (customizeModal.style.display === 'flex') {
            const productImgSrc = document.querySelector('.product-image').src;
            loadImageToCanvas(productImgSrc);
        }
    });
});