<div id="promo-banner">
    <div class="marquee" id="marquee">
        <span>🔥 Participa en un juego para ganar un artículo 🔥</span>
        <span>🔥 Participa en un juego para ganar un artículo 🔥</span>
        <span>🔥 Participa en un juego para ganar un artículo 🔥</span>
        <span>🔥 Participa en un juego para ganar un artículo 🔥</span>
        <span>🔥 Participa en un juego para ganar un artículo 🔥</span>
    </div>
</div>

<div class="nav">
    <div class="foto">
        <a href="{{ url('/') }}"><img src="../../../images/logo.png" alt=""></a>
    </div>
    <div class="buscador">
        <p><a href="{{ route('viewall', ['sale' => '1']) }}">Rebajas</a></p>
        <input type="text" id="buscador" placeholder="Buscar...">
    </div>
    <div class="usuario">
    <?php if (Auth::check()): ?>
        <form action="<?= route('logout') ?>" method="POST">
            @csrf
            <button type="submit">Cerrar Sesión</button>
        </form>
        <!-- Añadir el campo oculto con el ID del usuario -->
        <input type="hidden" id="userId" value="{{ auth()->user()->id }}">
        <!-- Indicador de inicio de sesión reciente -->
        <input type="hidden" id="just-logged-in" value="{{ session('just_logged_in') ? 'true' : 'false' }}">
    <?php else: ?>
        <button id="openPopup">Iniciar Sesion</button>
    <?php endif; ?>
        <img src="../../../images/cesta.webp" alt="" id="cartButton">
    </div>
</div>

<div class="background" id="popupBackground">
    <div class="popup">
        <div class="left">
            <div class="toggle-buttons">
                <button id="loginButton" class="active">Iniciar Sesión</button>
                <button id="registerButton">Registrarse</button>
            </div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-small">
            <img src="{{ asset('images/jordan.webp') }}" alt="Logo Grande" class="logo-large">
        </div>
        <div class="right">
            <img src="{{ asset('images/logo_redondo.png') }}" alt="Logo" class="logo-form">
            <div id="loginForm" class="form-section">
                <h2>Iniciar Sesión</h2>
                <form action="<?= route('login') ?>" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Acceder</button>
                </form>
            </div>
            <div id="registerForm" class="form-section hidden">
                <h2>Registrarse</h2>
                <form action="<?= route('register') ?>" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Nombre completo" required>
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
                    <button type="submit">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="searchPopup" class="search-popup hidden">
    <div class="search-results">
        <ul id="searchResultsList"></ul>
    </div>
</div>

<div class="cart-popup-background hidden" id="cartPopupBackground">
    <div id="cartPopup" class="cart-popup">
        <div class="cart-header">
            <h2>Carrito de Compras</h2>
            <button id="closeCartButton">&times;</button>
        </div>
        <div id="cartItemsContainer" class="cart-items-container"></div>
        <button id="checkoutButton">Finalizar Compra</button>
    </div>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success">
        <?= session('success') ?>
    </div>
<?php endif; ?>

<?php if ($errors->any()): ?>
    <div class="alert alert-danger">
        <?= $errors->first() ?>
    </div>
<?php endif; ?>

<script src="{{ asset('js/login.js') }}"></script>
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/cart.js') }}"></script>
<script src="{{ asset('js/banner.js') }}"></script>