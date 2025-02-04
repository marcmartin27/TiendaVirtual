<div class="nav">
        <div class="foto">
        <a href="{{ url('/') }}"><img src="../../../images/logo.png" alt=""></a>
        </div>
        <div class="buscador">
            <p><a href=""></a>Rebajas</p>
            <input type="text" id="buscador" placeholder="Buscar...">
            <img src="../../../images/cesta.webp" alt="">
        </div>
        <div class="usuario">
            <button id="openPopup">Iniciar Sesion</button>
        </div>
</div>

<div class="background" id="popupBackground">
    <div class="popup">
        <div class="left">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-small">
        <img src="{{ asset('images/jordan.webp') }}" alt="Logo Grande" class="logo-large">
    </div>
    <div class="right">
        <img src="{{ asset('images/logo_redondo.png') }}" alt="Logo" class="logo-form">
        <h2>Iniciar Sesion</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Acceder</button>
        </form>
    </div>
    </div>
    </div>

<script src="{{ asset('js/login.js') }}"></script>
