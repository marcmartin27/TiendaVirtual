<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aviso Legal - Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    @include('header')
    
    <div class="legal-wrapper">
        <div class="legal-header">
            <h1>Aviso Legal</h1>
            <div class="legal-meta">
                <span class="legal-date">Última actualización: 31 de marzo de 2025</span>
                <span class="legal-version">Versión 1.0</span>
            </div>
        </div>
        
        <nav class="legal-nav">
            <ul>
                <li><a href="/privacy">Política de Privacidad</a></li>
                <li><a href="/terms">Términos y Condiciones</a></li>
                <li><a href="/cookies">Política de Cookies</a></li>
                <li><a href="/legal" class="active">Aviso Legal</a></li>
            </ul>
        </nav>
        
        <div class="legal-content">
            <div class="toc">
                <h3>Contenido</h3>
                <ul>
                    <li><a href="#info-general">1. Información General</a></li>
                    <li><a href="#propiedad">2. Propiedad Intelectual</a></li>
                    <li><a href="#responsabilidad">3. Responsabilidad</a></li>
                    <li><a href="#legislacion">4. Legislación Aplicable</a></li>
                    <li><a href="#datos-empresa">5. Datos de la Empresa</a></li>
                </ul>
            </div>

            <p>Este aviso legal regula el uso del sitio web de Sneaks. Al acceder y utilizar este sitio, aceptas cumplir con los términos y condiciones establecidos en este aviso.</p>
            
            <h2 id="info-general">1. Información General</h2>
            <p>El sitio web es operado por Sneaks, S.L., con domicilio en Calle San Juan de la Barca 22, 08915 Badalona, Barcelona. Puedes contactarnos a través del correo electrónico: <a href="mailto:info@sneaks.com">info@sneaks.com</a>.</p>
            
            <div class="highlighted-section">
                <h3>Identificación fiscal:</h3>
                <ul>
                    <li><strong>CIF/NIF:</strong> B-12345678</li>
                    <li><strong>Inscripción:</strong> Registro Mercantil de Barcelona, Tomo 12345, Folio 67, Hoja B-123456</li>
                    <li><strong>Dominio web:</strong> www.sneaks.com</li>
                </ul>
            </div>
            
            <h2 id="propiedad">2. Propiedad Intelectual</h2>
            <p>Todos los contenidos del sitio web, incluyendo textos, imágenes, gráficos, logotipos, y software, son propiedad de Sneaks o de terceros que han autorizado su uso. Queda prohibida la reproducción total o parcial sin el consentimiento expreso de Sneaks.</p>
            
            <h2 id="responsabilidad">3. Responsabilidad</h2>
            <p>Sneaks no se hace responsable de los daños y perjuicios que puedan derivarse del uso indebido del sitio web. Nos reservamos el derecho de modificar el contenido del sitio en cualquier momento y sin previo aviso.</p>
            
            <div class="legal-notice-box">
                <h4>Limitación de responsabilidad</h4>
                <p>Sneaks ha adoptado todas las medidas técnicas necesarias para proporcionar un servicio estable y seguro. Sin embargo, no podemos garantizar la disponibilidad ininterrumpida del sitio web ni la ausencia de virus u otros elementos nocivos.</p>
            </div>
            
            <h2 id="legislacion">4. Legislación Aplicable</h2>
            <p>Este aviso legal se rige por la legislación española. Cualquier controversia que surja en relación con el uso del sitio web se someterá a la jurisdicción de los tribunales de Barcelona.</p>
            
            <h2 id="datos-empresa">5. Datos de la Empresa</h2>
            <p>Sneaks S.L.</p>
            <p>Calle San Juan de la Barca 22</p>
            <p>08915 - Badalona</p>
            <p>Barcelona, España</p>
            <p>Teléfono: +34 932 000 000</p>
            <p>Email: <a href="mailto:info@sneaks.com">info@sneaks.com</a></p>
            
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