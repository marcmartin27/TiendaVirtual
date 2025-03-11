@if(Auth::check())
    <input type="hidden" id="userId" value="{{ auth()->user()->id }}">
    <input type="hidden" id="just-logged-in" value="{{ session('just_logged_in') ? 'true' : 'false' }}">
@endif
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Tienda')</title>
    
    <!-- CSS de Bootstrap (opcional) -->
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Vite (si usas Laravel con Vite) -->
</head>
<body>
    <!-- Scripts de Bootstrap (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
