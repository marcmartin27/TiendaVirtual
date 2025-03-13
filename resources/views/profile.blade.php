<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    @include('header')

    <div class="profile-container">
        <h2>Modifica tu perfil</h2>
        <form action="{{ route('updateProfile') }}" method="POST" class="profile-form">
            @csrf
            <div class="form-group">
                <label for="first_name">Nombre</label>
                <input type="text" id="first_name" name="first_name" placeholder="Nombre" value="{{ auth()->user()->first_name }}" required>
            </div>
            <div class="form-group">
                <label for="last_name">Apellido</label>
                <input type="text" id="last_name" name="last_name" placeholder="Apellido" value="{{ auth()->user()->last_name }}" required>
            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <input type="text" id="address" name="address" placeholder="Dirección" value="{{ auth()->user()->address }}" required>
            </div>
            <div class="form-group">
                <label for="apartment">Apartamento</label>
                <input type="text" id="apartment" name="apartment" placeholder="Apartamento" value="{{ auth()->user()->apartment }}">
            </div>
            <div class="form-group">
                <label for="postal_code">Código Postal</label>
                <input type="text" id="postal_code" name="postal_code" placeholder="Código Postal" value="{{ auth()->user()->postal_code }}" required>
            </div>
            <div class="form-group">
                <label for="city">Ciudad</label>
                <input type="text" id="city" name="city" placeholder="Ciudad" value="{{ auth()->user()->city }}" required>
            </div>
            <div class="form-group">
                <label for="province">Provincia</label>
                <input type="text" id="province" name="province" placeholder="Provincia" value="{{ auth()->user()->province }}" required>
            </div>
            <div class="form-group">
                <label for="country">País</label>
                <input type="text" id="country" name="country" placeholder="País" value="{{ auth()->user()->country }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Teléfono</label>
                <input type="text" id="phone" name="phone" placeholder="Teléfono" value="{{ auth()->user()->phone }}" required>
            </div>
            <button type="submit">Actualizar Perfil</button>
        </form>
    </div>

    @include('footer')
</body>