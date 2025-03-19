document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const startGameBtn = document.getElementById('startGameBtn');
    const gameArea = document.getElementById('gameArea');
    const basket = document.getElementById('basket');
    const scoreElement = document.getElementById('score');
    const timerElement = document.getElementById('timer');
    const gameOverModal = document.getElementById('gameOverModal');
    const gameResultTitle = document.getElementById('gameResultTitle');
    const gameResultMessage = document.getElementById('gameResultMessage');
    const finalScoreElement = document.getElementById('finalScore');
    const couponContainer = document.getElementById('couponContainer');
    const couponCodeElement = document.getElementById('couponCode');
    const playAgainBtn = document.getElementById('playAgainBtn');
    const returnToShopBtn = document.getElementById('returnToShopBtn');

    // Variables del juego
    let score = 0;
    let timer = 20;
    let gameInterval;
    let shoeInterval;
    let gameActive = false;
    let shoesImages = [
        'nike.webp', 
        'jordan.webp', 
        'Adidas-CampusBadBunny-Brown-1.webp',
        'yeezy.webp',
        'NewBalance-2002RProtectionPackPink.webp',
        // Añadir más imágenes de zapatillas según disponibilidad
    ];
    
    // Variables para drag and drop
    let isDragging = false;
    let dragOffsetX = 0;

    // Inicializar posición del basket
    let basketPositionX = gameArea.offsetWidth / 2 - basket.offsetWidth / 2;
    basket.style.left = basketPositionX + 'px';

    // Evento para empezar el juego
    startGameBtn.addEventListener('click', startGame);
    
    // EVENTOS DRAG AND DROP
    // 1. Mouse Down - Comenzar arrastre
    basket.addEventListener('mousedown', startDrag);
    
    // 2. Mouse Move - Mover si está arrastrando
    document.addEventListener('mousemove', drag);
    
    // 3. Mouse Up - Terminar arrastre
    document.addEventListener('mouseup', endDrag);
    
    // Eventos para dispositivos táctiles
    basket.addEventListener('touchstart', startDragTouch);
    document.addEventListener('touchmove', dragTouch);
    document.addEventListener('touchend', endDrag);
    
    // Función para iniciar arrastre con mouse
    function startDrag(e) {
        if (!gameActive) return;
        
        e.preventDefault();
        isDragging = true;
        
        // Calcular el desplazamiento del clic dentro del elemento
        const rect = basket.getBoundingClientRect();
        dragOffsetX = e.clientX - rect.left;
        
        // Cambiar cursor y añadir clase visual
        basket.style.cursor = 'grabbing';
        basket.classList.add('dragging');
    }
    
    // Función para iniciar arrastre con touch
    function startDragTouch(e) {
        if (!gameActive) return;
        
        e.preventDefault();
        isDragging = true;
        
        // Calcular el desplazamiento del toque dentro del elemento
        const rect = basket.getBoundingClientRect();
        dragOffsetX = e.touches[0].clientX - rect.left;
        
        basket.classList.add('dragging');
    }
    
    // Función para arrastre con mouse
    function drag(e) {
        if (!isDragging || !gameActive) return;
        
        const gameRect = gameArea.getBoundingClientRect();
        basketPositionX = e.clientX - gameRect.left - dragOffsetX;
        
        // Limitar el movimiento dentro del área de juego
        if (basketPositionX < 0) basketPositionX = 0;
        if (basketPositionX > gameArea.offsetWidth - basket.offsetWidth) {
            basketPositionX = gameArea.offsetWidth - basket.offsetWidth;
        }
        
        basket.style.left = basketPositionX + 'px';
    }
    
    // Función para arrastre con touch
    function dragTouch(e) {
        if (!isDragging || !gameActive) return;
        
        e.preventDefault();
        const gameRect = gameArea.getBoundingClientRect();
        basketPositionX = e.touches[0].clientX - gameRect.left - dragOffsetX;
        
        // Limitar el movimiento dentro del área de juego
        if (basketPositionX < 0) basketPositionX = 0;
        if (basketPositionX > gameArea.offsetWidth - basket.offsetWidth) {
            basketPositionX = gameArea.offsetWidth - basket.offsetWidth;
        }
        
        basket.style.left = basketPositionX + 'px';
    }
    
    // Función para terminar arrastre
    function endDrag() {
        isDragging = false;
        basket.style.cursor = 'grab';
        basket.classList.remove('dragging');
    }

    // Comenzar el juego
    function startGame() {
        // Reiniciar variables
        score = 0;
        timer = 20;
        gameActive = true;
        scoreElement.textContent = score;
        timerElement.textContent = timer;
        
        // Ocultar botón de inicio
        startGameBtn.style.display = 'none';
        
        // Cambiar el cursor del basket para indicar que es arrastrable
        basket.style.cursor = 'grab';
        
        // Iniciar generación de zapatillas
        shoeInterval = setInterval(createShoe, 1000);
        
        // Iniciar temporizador
        gameInterval = setInterval(updateGame, 1000);
    }

    // Actualizar estado del juego
    function updateGame() {
        timer--;
        timerElement.textContent = timer;
        
        if (timer <= 0) {
            endGame();
        }
    }

    // Crear zapatilla que cae
    function createShoe() {
        if (!gameActive) return;
        
        const shoe = document.createElement('div');
        shoe.className = 'shoe';
        
        // Posición aleatoria en el eje X
        const posX = Math.random() * (gameArea.offsetWidth - 60);
        shoe.style.left = posX + 'px';
        shoe.style.top = '-60px'; // Comienza fuera de la pantalla
        
        // Seleccionar imagen aleatoria de zapatillas
        const randomImage = shoesImages[Math.floor(Math.random() * shoesImages.length)];
        shoe.style.backgroundImage = `url('/images/${randomImage}')`;
        
        gameArea.appendChild(shoe);
        
        // Animación de caída
        let posY = -60;
        let speed = 2 + Math.random() * 3; // Velocidad aleatoria
        
        const fallInterval = setInterval(() => {
            posY += speed;
            shoe.style.top = posY + 'px';
            
            // Comprobar colisión con la cesta
            if (posY > gameArea.offsetHeight - basket.offsetHeight - 30 && 
                posY < gameArea.offsetHeight - 30 && 
                posX > basketPositionX - 30 && 
                posX < basketPositionX + basket.offsetWidth - 30) {
                
                // Colisión detectada
                gameArea.removeChild(shoe);
                clearInterval(fallInterval);
                score += 10;
                scoreElement.textContent = score;
                
                // Efecto visual de puntuación
                const scorePopup = document.createElement('div');
                scorePopup.textContent = '+10';
                scorePopup.style.position = 'absolute';
                scorePopup.style.left = `${posX + 30}px`;
                scorePopup.style.top = `${posY}px`;
                scorePopup.style.color = '#594C45';
                scorePopup.style.fontWeight = 'bold';
                scorePopup.style.fontSize = '20px';
                gameArea.appendChild(scorePopup);
                
                setTimeout(() => {
                    gameArea.removeChild(scorePopup);
                }, 500);
            } 
            // Si la zapatilla llega al fondo sin ser atrapada
            else if (posY > gameArea.offsetHeight) {
                gameArea.removeChild(shoe);
                clearInterval(fallInterval);
            }
        }, 20);
    }

    // Resto del código sin cambios...
    
    // Terminar el juego
    function endGame() {
        gameActive = false;
        isDragging = false;
        basket.style.cursor = 'default';
        clearInterval(gameInterval);
        clearInterval(shoeInterval);
        
        // Eliminar todas las zapatillas
        document.querySelectorAll('.shoe').forEach(shoe => shoe.remove());
        
        // Actualizar mensaje final
        finalScoreElement.textContent = score;
        
        // Comprobar si el jugador ha ganado
        if (score >= 100) {
            gameResultTitle.textContent = '¡Felicidades!';
            gameResultMessage.textContent = `¡Has conseguido ${score} puntos y has ganado un cupón de descuento!`;
            generateCoupon();
        } else {
            gameResultTitle.textContent = '¡Juego terminado!';
            gameResultMessage.textContent = `Has conseguido ${score} puntos. Necesitas 100 puntos para ganar el descuento.`;
            couponContainer.classList.add('hidden');
        }
        
        // Mostrar el modal de fin de juego
        gameOverModal.style.display = 'block';
    }

    // Generar cupón al ganar
    function generateCoupon() {
        // Solicitud AJAX para generar un cupón
        fetch('/generate-coupon', {  // Cambiado de /api/generate-coupon a /generate-coupon para usar la ruta web
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ score: score })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                couponCodeElement.textContent = data.couponCode;
                couponContainer.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error generating coupon:', error);
        });
    }

    // Evento para jugar de nuevo
    playAgainBtn.addEventListener('click', function() {
        gameOverModal.style.display = 'none';
        startGameBtn.style.display = 'block';
    });

    // Evento para volver a la tienda
    returnToShopBtn.addEventListener('click', function() {
        window.location.href = '/';
    });
});