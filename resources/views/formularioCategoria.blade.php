<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Categoría</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    <h1>Añadir Nueva Categoría</h1>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div>
            <label for="code">Código:</label>
            <input type="text" id="code" name="code" required>
        </div>
        <div>
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <button type="submit">Añadir Categoría</button>
    </form>
</body>
</html>