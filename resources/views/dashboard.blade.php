@php
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
@extends('layouts.app')

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
            <div class="card-container">
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


                <!-- Gráfico de productos añadidos por mes -->
                <div class="chart-container">
                    <h3>Productos añadidos por mes</h3>
                    <canvas id="productsChart"></canvas>
                </div>
                
                <!-- Gráfico de pedidos realizados por mes -->
                <div class="chart-container">
                    <h3>Pedidos realizados por mes</h3>
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
            <input type="hidden" id="productsByMonth" value="{{ json_encode($productsByMonth) }}">
            <input type="hidden" id="ordersByMonth" value="{{ json_encode($ordersByMonth) }}">
            <input type="hidden" id="months" value="{{ json_encode($months) }}">
            
            <div id="products" class="hidden">
                <div class="action-container">
                    <button id="addProductButton">Añadir Producto</button>
                    <input type="text" id="productSearch" placeholder="Buscar productos...">
                </div>
                <div id="addProductModal" class="modal-overlay">
                    <div class="modal-container">
                        <button class="modal-close">&times;</button>
                        <h2>Añadir Nuevo Producto</h2>
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="code">Código:</label>
                                <textarea id="product_description" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" id="product_name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción:</label>
                                <textarea id="product_editDescription" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Categoría:</label>
                                <select id="product_category_id" name="category_id" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="price">Precio:</label>
                                <input type="number" id="product_price" name="price" step="0.01" required>                                
                            </div>
                            <div class="form-group">
                                <label for="featured">Destacado:</label>
                                <input type="hidden" name="featured" value="0">
                                <input type="checkbox" id="product_featured" name="featured" value="1">
                            </div>
                            <div class="form-group">
                                <label for="images">Imágenes:</label>
                                <input type="file" id="product_images" name="images[]" multiple required>
                            </div>
                            <button type="submit">Añadir Producto</button>
                        </form>
                    </div>
                </div>
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
                                <td>{{ Str::words($product->description, 5, '...') }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->featured ? 'Sí' : 'No' }}</td>
                                <td>
                                <img src="{{ asset('images/editar_icon.png') }}" alt="Editar Producto" class="editProductButton action-icon" data-product-id="{{ $product->id }}">
                                <img src="{{ asset('images/eliminar_icon.png') }}" alt="Eliminar Producto" class="deleteProductButton action-icon" data-product-id="{{ $product->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="categories" class="hidden">
                <div class="action-container">
                    <button id="addCategoryButton">Añadir Categoría</button>
                    <input type="text" id="categorySearch" placeholder="Buscar categorías...">
                </div>
                <div id="addCategoryModal" class="modal-overlay">
                    <div class="modal-container">
                        <button class="modal-close">&times;</button>
                        <h2>Añadir Nueva Categoría</h2>
                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="code">Código:</label>
                                <input type="text" id="category_code" name="code" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" id="category_name" name="name" required> 
                            </div>
                            <button type="submit">Añadir Categoría</button>
                        </form>
                    </div>
                </div>
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
                                <img src="{{ asset('images/editar_icon.png') }}" alt="Editar Categoría" class="editCategoryButton action-icon" data-category-id="{{ $category->id }}">
                                <img src="{{ asset('images/eliminar_icon.png') }}" alt="Eliminar Categoría" class="deleteCategoryButton action-icon" data-category-id="{{ $category->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

                       <!-- Modal para editar categoría -->
                       <div id="editCategoryModal" class="modal-overlay">
                            <div class="modal-container">
                                <button class="modal-close">&times;</button>
                                <h2>Editar Categoría</h2>
                                <form id="editCategoryForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="editCode">Código:</label>
                                        <input type="text" id="category_editCode" name="code" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editName">Nombre:</label>
                                        <input type="text" id="category_editName" name="name" required>
                                    </div>
                                    <button type="submit">Actualizar Categoría</button>
                                </form>
                            </div>
                        </div>

            <!-- Modal para editar producto -->
            <div id="editProductModal" class="modal-overlay">
                <div class="modal-container">
                    <button class="modal-close">&times;</button>
                    <h2>Editar Producto</h2>
                    <form id="editProductForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="editCode">Código:</label>
                            <input type="text" id="product_editCode" name="code" required>
                        </div>
                        <div class="form-group">
                            <label for="editName">Nombre:</label>
                            <input type="text" id="product_editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Descripción:</label>
                            <textarea id="product_editDescription" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editCategoryId">Categoría:</label>
                            <select id="product_editCategoryId" name="category_id" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editPrice">Precio:</label>
                            <input type="number" id="product_editPrice" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="editFeatured">Destacado:</label>
                            <input type="hidden" name="featured" value="0">
                            <input type="checkbox" id="product_editFeatured" name="featured" value="1">
                        </div>
                        <div class="form-group">
                            <label for="editImages">Imágenes:</label>
                            <input type="file" id="product_editImages" name="images[]" multiple>
                        </div>
                        <div class="form-group">
                            <button type="button" id="manageStockButton" class="secondary-button">Gestionar Stock</button>
                        </div>
                        <button type="submit">Actualizar Producto</button>
                    </form>
                </div>
            </div>

            <!-- Sección de usuarios -->
            <div id="users" class="hidden">
                <div class="action-container">
                    <button id="addUserButton">Añadir Usuario</button>
                    <input type="text" id="userSearch" placeholder="Buscar usuarios...">
                </div>
                <!-- Modal de añadir usuario -->
                <div id="addUserModal" class="modal-overlay">
                    <div class="modal-container">
                        <button class="modal-close">&times;</button>
                        <h2>Añadir Nuevo Usuario</h2>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" id="user_name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="user_email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña:</label>
                                <input type="password" id="user_password" name="password" required>
                            </div>
                            <button type="submit">Añadir Usuario</button>
                        </form>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                <img src="{{ asset('images/editar_icon.png') }}" alt="Editar Usuario" class="editUserButton action-icon" data-user-id="{{ $user->id }}">
                                <img src="{{ asset('images/eliminar_icon.png') }}" alt="Eliminar Usuario" class="deleteUserButton action-icon" data-user-id="{{ $user->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal para editar usuario -->
            <div id="editUserModal" class="modal-overlay">
                <div class="modal-container">
                    <button class="modal-close">&times;</button>
                    <h2>Editar Usuario</h2>
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="editName">Nombre:</label>
                            <input type="text" id="user_editName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email:</label>
                            <input type="email" id="user_editEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Contraseña:</label>
                            <input type="password" id="user_editPassword" name="password">
                            <small>Dejar en blanco para mantener la contraseña actual</small>
                        </div>
                        <button type="submit">Actualizar Usuario</button>
                    </form>
                </div>
            </div>


            <div id="orders" class="hidden">
                <div class="action-container">
                    <button id="addOrderButton">Añadir Pedido</button>
                    <input type="text" id="orderSearch" placeholder="Buscar pedidos...">
                </div>
                <!-- Modal de añadir pedido -->
                <div id="addOrderModal" class="modal-overlay">
                    <div class="modal-container">
                        <button class="modal-close">&times;</button>
                        <h2>Añadir Nuevo Pedido</h2>
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="user_id">Usuario:</label>
                                <select id="order_user_id" name="user_id" required>
                                    <option value="">Seleccione un usuario</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="total">Total:</label>
                                <input type="number" id="order_total" name="total" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Estado:</label>
                                <input type="text" id="order_status" name="status" required>
                            </div>
                            <button type="submit">Añadir Pedido</button>
                        </form>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user ? $order->user->name : 'Usuario no disponible' }}</td>                                <td>{{ $order->total }}</td>
                                <td>{{ $order->status }}</td>
                                <td>
                                <img src="{{ asset('images/editar_icon.png') }}" alt="Editar Pedido" class="editOrderButton action-icon" data-order-id="{{ $order->id }}">
                                <img src="{{ asset('images/eliminar_icon.png') }}" alt="Eliminar Pedido" class="deleteOrderButton action-icon" data-order-id="{{ $order->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal para editar pedido -->
            <div id="editOrderModal" class="modal-overlay">
                <div class="modal-container">
                    <button class="modal-close">&times;</button>
                    <h2>Editar Pedido</h2>
                    <form id="editOrderForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="editUserId">Usuario:</label>
                            <select id="order_editUserId" name="user_id" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editTotal">Total:</label>
                            <input type="number" id="order_editTotal" name="total" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="editStatus">Estado:</label>
                            <input type="text" id="order_editStatus" name="status" required>
                        </div>
                        <button type="submit">Actualizar Pedido</button>
                    </form>
                </div>
            </div>
        </main>
    </div>



    <!-- Pop up gestion del stock -->
    <div id="stockModal" class="modal-overlay">
        <div class="modal-container">
            <button class="modal-close">&times;</button>
            <h2>Gestionar Stock</h2>
            <form id="stockForm" method="POST">
                @csrf
                <input type="hidden" id="stock_product_id" name="product_id">
                <div class="sizes-grid">
                    @for ($size = 36; $size <= 47; $size++)
                        <div class="size-input">
                            <label for="size_{{ $size }}">Talla {{ $size }}:</label>
                            <input type="number" 
                                id="size_{{ $size }}" 
                                name="sizes[{{ $size }}]" 
                                min="0" 
                                class="stock-input">
                        </div>
                    @endfor
                </div>
                <button type="submit">Guardar Stock</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>