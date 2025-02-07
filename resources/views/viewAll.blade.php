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
                @foreach($product->images as $image)
                    <img src="{{ asset('images/' . $image->image_url) }}" alt="{{ $product->name }}">
                @endforeach
                <h3>{{ $product->name }}</h3>
                <p>Precio: ${{ $product->price }}</p>
                <button>Añadir al carrito</button>
            </div>
        @endforeach
    </div>

    @include('footer')
</body>
</html>