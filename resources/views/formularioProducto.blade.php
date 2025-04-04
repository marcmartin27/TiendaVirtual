<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    <h1>Añadir Nuevo Producto</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="code">Código:</label>
            <input type="text" id="code" name="code" required>
        </div>
        <div>
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div>
            <label for="category_id">Categoría:</label>
            <select id="category_id" name="category_id" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->id ?>"><?= $category->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="price">Precio:</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <div>
            <label for="featured">Destacado:</label>
            <input type="checkbox" id="featured" name="featured">
        </div>
        <div>
            <label for="images">Imágenes:</label>
            <input type="file" id="images" name="images[]" multiple required>
        </div>
        <button type="submit">Añadir Producto</button>
    </form>
</body>
</html>