<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    @include('header')

    @extends('layouts.app')

    <div class="game-container">
    <div class="game-header">
        <h1>¡Atrapa las Zapatillas!</h1>
        <p class="game-instructions">Arrastra la cesta para atrapar las zapatillas que caen. ¡Consigue 100 puntos para ganar un 10% de descuento!</p>
        <div class="game-stats">
            <div class="score">Puntos: <span id="score">0</span></div>
            <div class="timer">Tiempo: <span id="timer">20</span>s</div>
        </div>
        <button id="startGameBtn" class="start-game-btn">¡Comenzar!</button>
    </div>
    
    <div class="game-area" id="gameArea">
        <!-- Elementos decorativos -->
        <div class="cloud" style="left: 10%"></div>
        <div class="cloud" style="left: 40%"></div>
        <div class="cloud" style="left: 70%"></div>
        
        <div id="basket" class="basket">
            <img src="{{ asset('images/cesta.webp') }}" alt="Cesta">
        </div>
    </div>
    
    <div id="gameOverModal" class="game-over-modal">
        <div class="modal-content">
            <h2 id="gameResultTitle">¡Juego terminado!</h2>
            <p id="gameResultMessage">Tu puntuación: <span id="finalScore">0</span></p>
            <div id="couponContainer" class="coupon-container hidden">
                <p>¡Felicidades! Has ganado un cupón de descuento:</p>
                <div class="coupon-code" id="couponCode"></div>
                <p class="coupon-info">Utiliza este código en tu próxima compra para obtener un 10% de descuento. Válido por 30 días.</p>
            </div>
            <button id="playAgainBtn" class="play-again-btn">Jugar de nuevo</button>
            <button id="returnToShopBtn" class="return-to-shop-btn">Volver a la tienda</button>
        </div>
    </div>
</div>

@include('footer')
<script src="{{ asset('js/game.js') }}"></script>



</body>
</html>