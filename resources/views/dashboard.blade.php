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
                                <img src="{{ asset('images/editar_icon.png') }}" alt="Editar Categoría" class="editCategoryButton action-icon" data-category-id="{{ $category->id }}">
                                <img src="{{ asset('images/eliminar_icon.png') }}" alt="Eliminar Categoría" class="deleteCategoryButton action-icon" data-category-id="{{ $category->id }}">
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

            <!-- Modal para editar producto -->
            <div id="editProductModal" class="hidden">
                <h2>Editar Producto</h2>
                <form id="editProductForm" method="POST" enctype="multipart/form-data">
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
                    <div>
                        <label for="editDescription">Descripción:</label>
                        <textarea id="editDescription" name="description" required></textarea>
                    </div>
                    <div>
                        <label for="editCategoryId">Categoría:</label>
                        <select id="editCategoryId" name="category_id" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="editPrice">Precio:</label>
                        <input type="number" id="editPrice" name="price" step="0.01" required>
                    </div>
                    <div>
                        <label for="editFeatured">Destacado:</label>
                        <input type="checkbox" id="editFeatured" name="featured">
                    </div>
                    <div>
                        <label for="editImages">Imágenes:</label>
                        <input type="file" id="editImages" name="images[]" multiple>
                    </div>
                    <button type="submit">Actualizar Producto</button>
                </form>
            </div>

            <!-- Sección de usuarios -->
            <div id="users" class="hidden">
                <button id="addUserButton">Añadir Usuario</button>
                <div id="addUserForm" class="hidden">
                    <h2>Añadir Nuevo Usuario</h2>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="name">Nombre:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div>
                            <label for="password">Contraseña:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit">Añadir Usuario</button>
                    </form>
                </div>
                <input type="text" id="userSearch" placeholder="Buscar usuarios...">
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
                       <div id="editUserModal" class="hidden">
                <h2>Editar Usuario</h2>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="editName">Nombre:</label>
                        <input type="text" id="editName" name="name" required>
                    </div>
                    <div>
                        <label for="editEmail">Email:</label>
                        <input type="email" id="editEmail" name="email" required>
                    </div>
                    <div>
                        <label for="editPassword">Contraseña:</label>
                        <input type="password" id="editPassword" name="password">
                    </div>
                    <button type="submit">Actualizar Usuario</button>
                </form>
            </div>
            <div id="orders" class="hidden">
                <button id="addOrderButton">Añadir Pedido</button>
                <div id="addOrderForm" class="hidden">
                    <h2>Añadir Nuevo Pedido</h2>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="user_id">Usuario:</label>
                            <select id="user_id" name="user_id" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="total">Total:</label>
                            <input type="number" id="total" name="total" step="0.01" required>
                        </div>
                        <div>
                            <label for="status">Estado:</label>
                            <input type="text" id="status" name="status" required>
                        </div>
                        <button type="submit">Añadir Pedido</button>
                    </form>
                </div>
                <input type="text" id="orderSearch" placeholder="Buscar pedidos...">
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
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->total }}</td>
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
            <div id="editOrderModal" class="hidden">
                <h2>Editar Pedido</h2>
                <form id="editOrderForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="editUserId">Usuario:</label>
                        <select id="editUserId" name="user_id" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="editTotal">Total:</label>
                        <input type="number" id="editTotal" name="total" step="0.01" required>
                    </div>
                    <div>
                        <label for="editStatus">Estado:</label>
                        <input type="text" id="editStatus" name="status" required>
                    </div>
                    <button type="submit">Actualizar Pedido</button>
                </form>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>