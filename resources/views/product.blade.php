<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
@include('header')
    <div class="product-container">
        <div class="left-column">
            @if($product->images->isNotEmpty())
                <img src="{{ asset('images/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}">
            @else
                <img src="{{ asset('images/default-product.png') }}" alt="Imagen no disponible">
            @endif
            <p>{{ $product->description }}</p>
        </div>
        <div class="right-column">
            <h2>{{ $product->name }}</h2>
            <p class="price">{{ $product->price }} €</p>
            <div class="sizes">
                @foreach ($product->sizes as $size)
                    <div class="size" data-size="{{ $size->size }}">{{ $size->size }}</div>
                @endforeach
            </div>
            <p class="delivery-time">El plazo promedio de entrega es de 2 a 14 días.</p>
            <div class="selected-size">
                <p>Talla: <span id="selected-size">Ninguna</span></p>
                <label for="quantity">Cantidad:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1">
            </div>
            <div class="actions">
                <button id="add-to-cart">Añadir al carrito</button>
                <button id="buy-now">Comprar ahora</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/product.js') }}"></script>
    @include('footer')
</body>
</html>