<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

    @extends('layouts.app')

    @include('header')

    <img src="{{ asset('images/banner1.png') }}" alt="Banner promocional" id="bannerPromocional">

    <div class="marcasInicio">
        <div class="marcaInicio">
            <a href="{{ route('viewall', ['category' => 'Nike']) }}">
                <div class="img-container">
                    <img src="../../../images/nike.webp" alt="nike">
                </div>
                <p>Nike</p>
            </a>
        </div>

        <div class="marcaInicio">
            <a href="{{ route('viewall', ['category' => 'Adidas']) }}">
                <div class="img-container">
                    <img src="../../../images/Adidas-CampusBadBunny-Brown-1.webp" alt="adidas">
                </div>
                <p>Adidas</p>
            </a>
        </div>

        <div class="marcaInicio">
            <a href="{{ route('viewall', ['category' => 'Jordan']) }}">
                <div class="img-container">
                    <img src="../../../images/jordan.webp" alt="jordan">
                </div>
                <p>Jordan</p>
            </a>
        </div>

        <div class="marcaInicio">
            <a href="{{ route('viewall', ['category' => 'Yeezy']) }}">
                <div class="img-container">
                    <img src="../../../images/yeezy.webp" alt="yeezy">
                </div>
                <p>Yeezy</p>
            </a>
        </div>

        <div class="marcaInicio">
            <a href="{{ route('viewall', ['category' => 'New Balance']) }}">
                <div class="img-container">
                    <img src="../../../images/newbalance.webp" alt="newbalance">
                </div>
                <p>New Balance</p>
            </a>
        </div>

    </div>

    <div class="ver-todas-container">
        <a href="{{ url('/viewall') }}"><button>VER TODAS</button></a>
    </div>

    <h1>🔥 ¡PRODUCTOS DESTACADOS! 🔥</h1>

    @if($featuredProducts->isNotEmpty())
        <div class="featured-container">
            <div class="featured-wrapper">
                @foreach($featuredProducts as $product)
                    <a href="{{ route('product.show', ['id' => $product->id]) }}" class="featured-card">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('images/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}">
                        @endif
                        <h3>{{ $product->name }}</h3>
                    </a>
                @endforeach
                @foreach($featuredProducts->take(5) as $product)
                    <a href="{{ route('product.show', ['id' => $product->id]) }}" class="featured-card clone">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset('images/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}">
                        @endif
                        <h3>{{ $product->name }}</h3>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <p>No hay productos destacados en este momento.</p>
    @endif

    <!-- Añadir justo después del div "featured-container" en welcome.blade.php -->
    <section class="customization-section">
        <div class="customization-container">
            <div class="customization-content">
                <div class="customization-text">
                    <h2>CREA TU ESTILO ÚNICO</h2>
                    <h3>Personaliza tu propia camiseta</h3>
                    <p>Diseña algo tan único como tú. Elige colores, añade texto y crea una prenda exclusiva que refleje tu personalidad.</p>
                    <a href="{{ route('product.show', ['id' => 75]) }}" class="customization-btn">PERSONALIZAR AHORA</a>
                </div>
                <div class="customization-image">
                    <img src="{{ asset('images/white-t-shirt-free-png.webp') }}" alt="Camiseta personalizable">
                    <div class="customization-badge">
                        <span>¡NUEVO!</span>
                    </div>
                </div>
            </div>
        </div>
    </section>



    @include('footer')

    <!-- Añadir justo antes del cierre del body en welcome.blade.php -->
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>


