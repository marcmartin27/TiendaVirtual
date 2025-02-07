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

    <h1>🔥 ¡EXPLORA NUESTRO CATALOGO! 🔥</h1>

    <div class="container">
        @foreach($products as $product)
            <div class="sneaker-card">
                @if($product->images->isNotEmpty())
                    @php
                        $firstImage = $product->images->first();
                        $secondImage = $product->images->skip(1)->first(); // Obtiene la segunda imagen si existe
                    @endphp

                    <div class="image-container">
                        <img class="default-image" 
                            src="{{ asset('images/' . $firstImage->image_url) }}" 
                            alt="{{ $product->name }}">
                        
                        @if($secondImage)
                            <img class="hover-image" 
                                src="{{ asset('images/' . $secondImage->image_url) }}" 
                                alt="{{ $product->name }}">
                        @endif
                    </div>
                @endif

                <h3>{{ $product->name }}</h3>
                <p>Precio: ${{ $product->price }}</p>
                <button>Añadir al carrito</button>
            </div>
        @endforeach
    </div>


    @include('footer')
</body>
</html>