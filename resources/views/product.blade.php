<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
</head>
<body>
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    <p>Precio: {{ $product->price }}</p>
    <p>Categoría: {{ $product->category->name }}</p>
    <p>Destacado: {{ $product->featured ? 'Sí' : 'No' }}</p>

    <h2>Tallas</h2>
    <ul>
        @foreach ($product->sizes as $size)
            <li>Talla: {{ $size->size }}, Stock: {{ $size->stock }}</li>
        @endforeach
    </ul>

    <h2>Imágenes</h2>
    <ul>
        @foreach ($product->images as $image)
            <li><img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $product->name }}"></li>
        @endforeach
    </ul>
</body>
</html>