<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="nav">
        <p>holaa</p>
        <div class="foto">
            <img src="" alt="">
        </div>
        <div class="buscador">

        </div>
        <div class="usuario">
            <button id="openPopup">Iniciar Sesion</button>
        </div>
    </div>

    <img src="" alt="">

    <div class="marcasInicio">
        <div class="marcaInicio">
            <img src="" alt="">
            <p>Nike</p>
        </div>

        <div class="marcaInicio">
            <img src="" alt="">
            <p>Adidas</p>
        </div>

        <div class="marcaInicio">
            <img src="" alt="">
            <p>Jordan</p>
        </div>

        <div class="marcaInicio">
            <img src="" alt="">
            <p>Yeezy</p>
        </div>

        <div class="marcaInicio">
            <img src="" alt="">
            <p>New Balance</p>
        </div>

        <a href=""><button>VER TODAS</button></a>
    </div>

    <div class="background" id="popupBackground">
        <div class="popup">
            <h2>Iniciar SesiÃ³n</h2>
            <form action="/login" method="POST">
                @csrf
                <input type="text" name="username" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="ContraseÃ±a" required>
                <button type="submit">Acceder</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>

    <footer>
        <div>

        </div>

        <div>
            
        </div>

        <div>
            
        </div>
    </footer>
</body>
</html>
