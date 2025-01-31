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

    <img src="{{ asset('images/banner1.png') }}" alt="Banner promocional" id="bannerPromocional">

    <div class="marcasInicio">
        <div class="marcaInicio">
            <div class="img-container">
                <img src="../../../images/nike.webp" alt="nike">
            </div>
            <p>Nike</p>
        </div>

        <div class="marcaInicio">
            <div class="img-container">
                <img src="../../../images/adidas.webp" alt="adidas">
            </div>
            <p>Adidas</p>
        </div>

        <div class="marcaInicio">
            <div class="img-container">
                <img src="../../../images/jordan.webp" alt="jordan">
            </div>
            <p>Jordan</p>
        </div>

        <div class="marcaInicio">
            <div class="img-container">
                <img src="../../../images/yeezy.webp" alt="yeezy">
            </div>
            <p>Yeezy</p>
        </div>

        <div class="marcaInicio">
            <div class="img-container">
                <img src="../../../images/newbalance.webp" alt="newbalance">
            </div>
            <p>New Balance</p>
        </div>

        <a href="{{ url('/viewall') }}"><button>VER TODAS</button></a>
    </div>

    <script src="{{ asset('js/banner.js') }}"></script>

    @include('footer')
</body>
</html>
