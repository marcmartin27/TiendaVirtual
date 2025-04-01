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
    
    // Área de dibujo (se definirá cuando se cargue la imagen)
    let drawingArea = {
        x: 0,
        y: 0,
        width: 0,
        height: 0
    };

    let customizedImageData = null;
    const productId = document.querySelector('#add-to-cart').getAttribute('data-product-id');
    
    // Añadir botón de guardar en el modal
    const saveButton = document.createElement('button');
    saveButton.id = 'saveCustomization';
    saveButton.textContent = 'Guardar personalización';
    saveButton.classList.add('save-button');
    clearCanvasButton.parentNode.appendChild(saveButton);
    
    // Abrir el modal de personalización
    customizeButton.addEventListener('click', function() {
        customizeModal.style.display = 'flex';
        
        // Obtener la primera imagen del producto
        const productImgSrc = document.querySelector('.product-image').src;
        loadImageToCanvas(productImgSrc);
        
        // Si ya hay una personalización guardada, cargarla después de que la imagen se haya cargado
        productImage.onload = function() {
            const savedCustomization = localStorage.getItem(`customized_${productId}`);
            if (savedCustomization) {
                const img = new Image();
                img.onload = function() {
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                };
                img.src = savedCustomization;
            }
        };
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
            
            const imgWidth = productImage.width * scale;
            const imgHeight = productImage.height * scale;
            const x = (canvas.width / 2) - (imgWidth / 2);
            const y = (canvas.height / 2) - (imgHeight / 2);
            
            // Limpiar el canvas y dibujar la imagen
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(
                productImage, 
                0, 0, 
                productImage.width, 
                productImage.height,
                x, y, 
                imgWidth, 
                imgHeight
            );
            
            // Definir área de dibujo (frente de la camiseta)
            // Estos valores se deben ajustar según la imagen específica de la camiseta
            drawingArea = {
                x: x + imgWidth * 0.27, // 30% desde la izquierda de la imagen
                y: y + imgHeight * 0.2, // 20% desde arriba
                width: imgWidth * 0.4,  // Ancho 40% de la imagen
                height: imgHeight * 0.5  // Alto 50% de la imagen
            };
            

        };
    }
    
    // Cerrar el modal de personalización
    window.closeCustomizeModal = function() {
        customizeModal.style.display = 'none';
    };
    
    // Función para verificar si un punto está dentro del área de dibujo
    function isInsideDrawingArea(x, y) {
        return (
            x >= drawingArea.x &&
            x <= drawingArea.x + drawingArea.width &&
            y >= drawingArea.y &&
            y <= drawingArea.y + drawingArea.height
        );
    }
    
    // Eventos para dibujar en el canvas
    canvas.addEventListener('mousedown', function(e) {
        const x = e.offsetX;
        const y = e.offsetY;
        
        // Solo comenzar a dibujar si está dentro del área permitida
        if (isInsideDrawingArea(x, y)) {
            isDrawing = true;
            lastX = x;
            lastY = y;
        }
    });
    
    canvas.addEventListener('mousemove', function(e) {
        if (!isDrawing) return;
        
        const x = e.offsetX;
        const y = e.offsetY;
        
        // Solo dibujar si está dentro del área permitida
        if (isInsideDrawingArea(x, y)) {
            ctx.strokeStyle = colorPicker.value;
            ctx.lineWidth = brushSize.value;
            ctx.lineJoin = 'round';
            ctx.lineCap = 'round';
            
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.stroke();
            
            lastX = x;
            lastY = y;
        } else {
            // Detener el dibujo si se sale del área permitida
            isDrawing = false;
        }
    });
    
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Eventos táctiles para dispositivos móviles
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', stopDrawing);
    
    function stopDrawing() {
        isDrawing = false;
    }
    
    function handleTouch(e) {
        e.preventDefault();
        
        const touch = e.touches[0];
        const rect = canvas.getBoundingClientRect();
        const x = touch.clientX - rect.left;
        const y = touch.clientY - rect.top;
        
        if (e.type === 'touchstart') {
            // Solo comenzar a dibujar si está dentro del área permitida
            if (isInsideDrawingArea(x, y)) {
                isDrawing = true;
                lastX = x;
                lastY = y;
            }
        } 
        else if (e.type === 'touchmove' && isDrawing) {
            // Solo dibujar si está dentro del área permitida
            if (isInsideDrawingArea(x, y)) {
                ctx.strokeStyle = colorPicker.value;
                ctx.lineWidth = brushSize.value;
                ctx.lineJoin = 'round';
                ctx.lineCap = 'round';
                
                ctx.beginPath();
                ctx.moveTo(lastX, lastY);
                ctx.lineTo(x, y);
                ctx.stroke();
                
                lastX = x;
                lastY = y;
            } else {
                // Detener el dibujo si se sale del área permitida
                isDrawing = false;
            }
        }
    }
    
    // Limpiar el canvas (mantener la imagen y el área de dibujo)
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