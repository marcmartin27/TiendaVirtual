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
                <div class="image-slider">
                    @foreach($product->images as $image)
                        <img src="{{ asset('images/' . $image->image_url) }}" alt="{{ $product->name }}" class="product-image">
                    @endforeach
                    <button class="prev" onclick="changeImage(-1)">&#10094;</button>
                    <button class="next" onclick="changeImage(1)">&#10095;</button>
                </div>
            @else
                <img src="{{ asset('images/default-product.png') }}" alt="Imagen no disponible">
            @endif
        </div>
        <div class="right-column">
            <h2>{{ $product->name }}</h2>
            @if($product->sale)
                <p class="price">
                    <span class="original-price">{{ $product->price }} €</span>
                    <span class="sale-price">{{ $product->new_price }} €</span>
                </p>
            @else
                <p class="price">{{ $product->price }} €</p>
            @endif
            <!-- Modificar la sección de tallas -->
            <div class="sizes">
                @foreach ($product->sizes as $size)
                    <div class="size {{ $size->stock <= 0 ? 'out-of-stock' : '' }}" 
                        data-size="{{ $size->size }}" 
                        data-stock="{{ $size->stock }}">
                        {{ $size->size }}
                    </div>
                @endforeach
            </div>
            <p class="delivery-time">⚠ El plazo promedio de entrega es de 2 a 14 días.</p>
            <div class="selected-size">
                <p>Talla: <span id="selected-size">Ninguna</span></p>
                <p id="stock-info" class="stock-info">Selecciona una talla para ver disponibilidad</p>
                <label for="quantity">Cantidad:</label>
                <div class="quantity-container">
                    <button type="button" id="decrease" disabled>-</button>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" max="1" disabled>
                    <button type="button" id="increase" disabled>+</button>
                </div>
            </div>
            <div class="actions">
                <button id="add-to-cart" 
                        data-product-id="{{ $product->id }}" 
                        data-product-name="{{ $product->name }}" 
                        data-product-price="{{ $product->price }}" 
                        data-product-new-price="{{ $product->new_price }}" 
                        data-product-image="{{ asset('images/' . $product->images->first()->image_url) }}">
                    Añadir al Carrito
                </button>
                <button id="buy-now">Comprar ahora</button>
                @if($product->id == 75)
                <button id="customize-product">Modificar producto</button>
                @endif
            </div>
        </div>
    </div>

    <div class="product-description">
        <h3>Descripción</h3>
        <p>{{ $product->description }}</p>
    </div>

    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        <button class="prev" onclick="changeImageInModal(-1)">&#10094;</button>
        <button class="next" onclick="changeImageInModal(1)">&#10095;</button>
    </div>

    <div id="customizeModal" class="modal customize-modal">
        <div class="customize-content">
            <span class="close" onclick="closeCustomizeModal()">&times;</span>
            <h3>Personaliza tu producto</h3>
            <div class="canvas-container">
                <canvas id="productCanvas"></canvas>
            </div>
            <div class="drawing-tools">
                <div class="color-picker">
                    <label>Color:</label>
                    <input type="color" id="drawColor" value="#000000">
                </div>
                <div class="brush-size">
                    <label>Tamaño:</label>
                    <input type="range" id="brushSize" min="1" max="20" value="5">
                </div>
                <button id="clearCanvas">Borrar todo</button>
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    <div class="related-products">
        <h2>🔥 ¡PRODUCTOS RELACIONADOS! 🔥</h2>
        <div class="related-products-container">
            @foreach($relatedProducts as $relatedProduct)
                <div class="related-product">
                    <a href="{{ route('products.show', $relatedProduct->id) }}">
                        <img src="{{ asset('images/' . $relatedProduct->images->first()->image_url) }}" alt="{{ $relatedProduct->name }}">
                        <p>{{ $relatedProduct->name }}</p>
                        <p>{{ $relatedProduct->price }} €</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <script src="{{ asset('js/productView.js') }}"></script>
    <script src="{{ asset('js/customizer.js') }}"></script>
    @include('footer')
</body>
</html>