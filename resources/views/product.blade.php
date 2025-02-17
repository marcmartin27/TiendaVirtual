<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <title>Sneaks</title>
</head>
<body>
    @include('header')
    <div class="container">
        <div class="productContainer">
            <div class="productImage">
                <img src="{{ asset('images/' . $product->imagen) }}" alt="{{ $product->name }}">
            </div>

            <div class="productInfo">
                <h1>{{ $product->name }}</h1>
                <p class="price">{{ number_format($product->price, 2) }}€</p>
                
                <div class="sizes">
                    <p><strong>Talla:</strong> <span id="selectedSize">Selecciona una talla</span></p>
                    <div class="size-options">
                        @if ($product->sizes->count() > 0)
                            @foreach($product->sizes as $size)
                                <button class="size-btn" onclick="selectSize({{ $size->numero }})">{{ $size->numero }}</button>
                            @endforeach
                        @else
                            <p>No hay tallas disponibles</p>
                        @endif
                    </div>
                </div>
                
                <p class="delivery-info">El plazo promedio de entrega es de 2 a 14 días</p>
                
                <div class="quantity">
                    <p><strong>Cantidad:</strong></p>
                    <button onclick="updateQuantity(-1)">-</button>
                    <span id="quantity">1</span>
                    <button onclick="updateQuantity(1)">+</button>
                </div>
                
                <div class="buttons">
                    <button class="add-to-cart">Añadir al carrito</button>
                    <button class="buy-now">Comprar ahora</button>
                </div>
            </div>
        </div>
    </div>

    @include('footer')

    <script src="{{ asset('js/productView.js') }}"></script>
</body>
</html>
