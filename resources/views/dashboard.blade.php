<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <div class="user-info">
            <span>Bienvenido, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Cerrar Sesión</button>
            </form>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="#" data-section="dashboard">DASHBOARD</a></li>
                <li><a href="#" data-section="products">PRODUCTOS</a></li>
                <li><a href="#" data-section="categories">CATEGORIAS</a></li>
                <li><a href="#" data-section="users">USUARIOS</a></li>
                <li><a href="#" data-section="orders">PEDIDOS</a></li>
            </ul>
        </aside>
        <main class="main-content">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="dashboard">
                <div class="card">
                    <h3>Total Usuarios</h3>
                    <p>{{ $totalUsers }}</p>
                </div>
                <div class="card">
                    <h3>Total Productos</h3>
                    <p>{{ $totalProducts }}</p>
                </div>
                <div class="card">
                    <h3>Total Categorias</h3>
                    <p>{{ $totalCategories }}</p>
                </div>
                <div class="card">
                    <h3>Total Pedidos</h3>
                    <p>{{ $totalOrders }}</p>
                </div>
            </div>
            <div id="products" class="hidden">
                <button id="addProductButton">Añadir Producto</button>
                <div id="addProductForm" class="hidden">
                    <h2>Añadir Nuevo Producto</h2>
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
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
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
                </div>
                <input type="text" id="productSearch" placeholder="Buscar productos...">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Destacado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->featured ? 'Sí' : 'No' }}</td>
                                <td>
                                    <button class="addStockButton" data-product-id="{{ $product->id }}">Añadir Stock</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="categories" class="hidden">
                <button id="addCategoryButton">Añadir Categoría</button>
                <div id="addCategoryForm" class="hidden">
                    <h2>Añadir Nueva Categoría</h2>
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
                </div>
                <input type="text" id="categorySearch" placeholder="Buscar categorías...">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->code }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <button class="editCategoryButton" data-category-id="{{ $category->id }}">Editar</button>
                                    <button class="deleteCategoryButton" data-category-id="{{ $category->id }}">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal para editar categoría -->
            <div id="editCategoryModal" class="hidden">
                <h2>Editar Categoría</h2>
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="editCode">Código:</label>
                        <input type="text" id="editCode" name="code" required>
                    </div>
                    <div>
                        <label for="editName">Nombre:</label>
                        <input type="text" id="editName" name="name" required>
                    </div>
                    <button type="submit">Actualizar Categoría</button>
                </form>
            </div>
            <div id="users" class="hidden">
                <!-- Contenido de usuarios -->
            </div>
            <div id="orders" class="hidden">
                <!-- Contenido de pedidos -->
            </div>
        </main>
    </div>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>