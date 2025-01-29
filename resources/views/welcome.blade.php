<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    @include('header')

    <img src="../../../images/banner.png" alt="Banner promocional" id="bannerPromocional">

    <div class="marcasInicio">
        <div class="marcaInicio">
            <img src="../../../images/nike.webp" alt="nike">
            <p>Nike</p>
        </div>

        <div class="marcaInicio">
            <img src="../../../images/adidas.webp" alt="adidas">
            <p>Adidas</p>
        </div>

        <div class="marcaInicio">
            <img src="../../../images/jordan.webp" alt="jordan">
            <p>Jordan</p>
        </div>

        <div class="marcaInicio">
            <img src="../../../images/yeezy.webp" alt="yeezy">
            <p>Yeezy</p>
        </div>

        <div class="marcaInicio">
            <img src="../../../images/newbalance.webp" alt="newbalance">
            <p>New Balance</p>
        </div>

        <a href="{{ url('/viewall') }}"><button>VER TODAS</button></a>
    </div>


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
