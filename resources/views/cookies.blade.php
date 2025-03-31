<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Política de Cookies - Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    @include('header')
    
    <div class="legal-wrapper">
        <div class="legal-header">
            <h1>Política de Cookies</h1>
            <div class="legal-meta">
                <span class="legal-date">Última actualización: 31 de marzo de 2025</span>
                <span class="legal-version">Versión 1.0</span>
            </div>
        </div>
        
        <nav class="legal-nav">
            <ul>
                <li><a href="/privacy">Política de Privacidad</a></li>
                <li><a href="/terms">Términos y Condiciones</a></li>
                <li><a href="/cookies" class="active">Política de Cookies</a></li>
                <li><a href="/legal">Aviso Legal</a></li>
            </ul>
        </nav>
        
        <div class="legal-content">
            <div class="toc">
                <h3>Contenido</h3>
                <ul>
                    <li><a href="#que-son">1. ¿Qué son las cookies?</a></li>
                    <li><a href="#tipos">2. Tipos de cookies que utilizamos</a></li>
                    <li><a href="#gestion">3. Cómo gestionar las cookies</a></li>
                    <li><a href="#tabla-cookies">4. Cookies utilizadas en esta web</a></li>
                    <li><a href="#cambios">5. Cambios en nuestra política de cookies</a></li>
                </ul>
            </div>

            <p>En Sneaks, utilizamos cookies para mejorar tu experiencia en nuestro sitio web. Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestro sitio.</p>
            
            <h2 id="que-son">1. ¿Qué son las cookies?</h2>
            <p>Las cookies son archivos que contienen información sobre tu navegación en nuestro sitio. Nos permiten recordar tus preferencias y mejorar la funcionalidad del sitio.</p>
            
            <div class="highlighted-section">
                <h3>Beneficios de las cookies:</h3>
                <ul>
                    <li>Mejoran la velocidad y seguridad de navegación</li>
                    <li>Permiten guardar tus preferencias</li>
                    <li>Ayudan a personalizar tu experiencia</li>
                    <li>Permiten ofrecer funciones específicas como el carrito de compra</li>
                </ul>
            </div>
            
            <h2 id="tipos">2. Tipos de cookies que utilizamos</h2>
            <ul>
                <li><strong>Cookies necesarias:</strong> Estas cookies son esenciales para el funcionamiento del sitio web y no se pueden desactivar.</li>
                <li><strong>Cookies de rendimiento:</strong> Estas cookies nos ayudan a entender cómo los visitantes interactúan con nuestro sitio, recopilando información sobre las áreas visitadas y el tiempo que pasan en el sitio.</li>
                <li><strong>Cookies de funcionalidad:</strong> Estas cookies permiten que el sitio recuerde las elecciones que realizas y proporcionan características mejoradas y personalizadas.</li>
                <li><strong>Cookies de publicidad:</strong> Estas cookies se utilizan para mostrarte anuncios relevantes según tus intereses.</li>
            </ul>
            
            <h2 id="gestion">3. Cómo gestionar las cookies</h2>
            <p>Puedes gestionar tus preferencias de cookies a través de la configuración de tu navegador. Sin embargo, ten en cuenta que desactivar las cookies puede afectar la funcionalidad del sitio.</p>
            
            <div class="legal-notice-box">
                <h4>Cómo configurar las cookies en los navegadores principales</h4>
                <p>Puedes permitir, bloquear o eliminar las cookies instaladas en tu equipo mediante la configuración de las opciones del navegador:</p>
                <ul>
                    <li><a href="https://support.google.com/chrome/answer/95647?hl=es" target="_blank">Google Chrome</a></li>
                    <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias" target="_blank">Mozilla Firefox</a></li>
                    <li><a href="https://support.microsoft.com/es-es/help/17442/windows-internet-explorer-delete-manage-cookies" target="_blank">Internet Explorer</a></li>
                    <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank">Safari</a></li>
                </ul>
            </div>
            
            <h2 id="tabla-cookies">4. Cookies utilizadas en esta web</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Propósito</th>
                        <th>Duración</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PHPSESSID</td>
                        <td>Necesaria</td>
                        <td>Gestiona la sesión de usuario</td>
                        <td>Sesión</td>
                    </tr>
                    <tr>
                        <td>_ga</td>
                        <td>Analítica</td>
                        <td>Distingue usuarios para Google Analytics</td>
                        <td>2 años</td>
                    </tr>
                    <tr>
                        <td>_gid</td>
                        <td>Analítica</td>
                        <td>Distingue usuarios para Google Analytics</td>
                        <td>24 horas</td>
                    </tr>
                    <tr>
                        <td>cart_items</td>
                        <td>Funcional</td>
                        <td>Guarda los productos en el carrito</td>
                        <td>30 días</td>
                    </tr>
                </tbody>
            </table>
            
            <h2 id="cambios">5. Cambios en nuestra política de cookies</h2>
            <p>Nos reservamos el derecho de actualizar esta política de cookies en cualquier momento. Te recomendamos que revises esta página periódicamente para estar informado sobre cualquier cambio.</p>
            
            <a href="/" class="legal-cta">Volver a la tienda</a>
        </div>
        
        <div class="legal-footer">
            <p>© 2025 Sneaks. Todos los derechos reservados.</p>
            <div class="contact-info">
                <p>Para cualquier consulta sobre temas legales: <a href="mailto:legal@sneaks.com">legal@sneaks.com</a></p>
            </div>
        </div>
    </div>
    
    @include('footer')
</body>
</html>