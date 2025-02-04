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
            <?php if (Auth::check()): ?>
                <form action="<?= route('logout') ?>" method="POST">
                    @csrf
                    <button type="submit">Cerrar Sesión</button>
                </form>
            <?php else: ?>
                <button id="openPopup">Iniciar Sesion</button>
            <?php endif; ?>
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
            <?php if (Auth::check()): ?>
                <h2>Bienvenido, <?= Auth::user()->name ?></h2>
                <form action="<?= route('logout') ?>" method="POST">
                    @csrf
                    <button type="submit">Cerrar Sesión</button>
                </form>
            <?php else: ?>
                <h2>Iniciar Sesión</h2>
                <form action="<?= route('login') ?>" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Correo electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Acceder</button>
                </form>
            <?php endif; ?>
        </div>
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