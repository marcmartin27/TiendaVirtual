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
                <img src="../../../images/Adidas-CampusBadBunny-Brown-1.webp" alt="adidas">
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

    <h1>🔥 ¡PRODUCTOS DESTACADOS! 🔥</h1>

    @if($featuredProducts->isNotEmpty())
        <div class="featured-container">
            <div class="featured-scroll">
                @foreach($featuredProducts as $product)
                    <div class="featured-card">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('images/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}">
                        @endif
                        <h3>{{ $product->name }}</h3>
                        <p>Precio: ${{ $product->price }}</p>
                        <button>Añadir al carrito</button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <script src="{{ asset('js/banner.js') }}"></script>

    @include('footer')
</body>
</html>
