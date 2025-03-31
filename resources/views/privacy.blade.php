<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Política de Privacidad - Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    @include('header')
    
    <div class="legal-wrapper">
        <div class="legal-header">
            <h1>Política de Privacidad</h1>
            <div class="legal-meta">
                <span class="legal-date">Última actualización: 31 de marzo de 2025</span>
                <span class="legal-version">Versión 1.0</span>
            </div>
        </div>
        
        <nav class="legal-nav">
            <ul>
                <li><a href="/privacy" class="active">Política de Privacidad</a></li>
                <li><a href="/terms">Términos y Condiciones</a></li>
                <li><a href="/cookies">Política de Cookies</a></li>
                <li><a href="/legal">Aviso Legal</a></li>
            </ul>
        </nav>
        
        <div class="legal-content">
            <div class="toc">
                <h3>Contenido</h3>
                <ul>
                    <li><a href="#info-recopilamos">1. Información que recopilamos</a></li>
                    <li><a href="#uso-informacion">2. Uso de la información</a></li>
                    <li><a href="#compartir-informacion">3. Compartir información</a></li>
                    <li><a href="#seguridad-informacion">4. Seguridad de la información</a></li>
                    <li><a href="#derechos">5. Sus derechos</a></li>
                    <li><a href="#cambios">6. Cambios en esta política</a></li>
                    <li><a href="#contacto">7. Contacto</a></li>
                </ul>
            </div>

            <p>En Sneaks, valoramos su privacidad y nos comprometemos a proteger su información personal. Esta política describe cómo recopilamos, usamos y compartimos su información cuando visita nuestro sitio web.</p>
            
            <h2 id="info-recopilamos">1. Información que recopilamos</h2>
            <p>Recopilamos información que usted nos proporciona directamente, como su nombre, dirección de correo electrónico y otra información de contacto cuando se registra en nuestro sitio o realiza una compra.</p>
            
            <div class="highlighted-section">
                <h3>Datos personales que podemos recopilar:</h3>
                <ul>
                    <li>Nombre y apellidos</li>
                    <li>Dirección de correo electrónico</li>
                    <li>Dirección postal</li>
                    <li>Número de teléfono</li>
                    <li>Información de pago (procesada de forma segura)</li>
                </ul>
            </div>
            
            <h2 id="uso-informacion">2. Uso de la información</h2>
            <p>Utilizamos su información para procesar pedidos, comunicarnos con usted sobre su cuenta y enviarle información sobre nuestros productos y servicios.</p>
            
            <h2 id="compartir-informacion">3. Compartir información</h2>
            <p>No compartimos su información personal con terceros, excepto cuando sea necesario para procesar su pedido o cumplir con la ley.</p>
            
            <h2 id="seguridad-informacion">4. Seguridad de la información</h2>
            <p>Implementamos medidas de seguridad para proteger su información personal. Sin embargo, ningún método de transmisión por Internet o método de almacenamiento electrónico es 100% seguro.</p>
            
            <div class="legal-notice-box">
                <h4>Importante</h4>
                <p>Aunque nos esforzamos por proteger su información, no podemos garantizar la seguridad absoluta de los datos transmitidos a nuestro sitio web.</p>
            </div>
            
            <h2 id="derechos">5. Sus derechos</h2>
            <p>Usted tiene derecho a acceder, corregir o eliminar su información personal. Para ejercer estos derechos, contáctenos a través de la información proporcionada en nuestro sitio.</p>
            
            <h2 id="cambios">6. Cambios en esta política</h2>
            <p>Podemos actualizar esta política de privacidad de vez en cuando. Le notificaremos sobre cambios significativos a través de nuestro sitio web.</p>
            
            <h2 id="contacto">7. Contacto</h2>
            <p>Si tiene preguntas sobre esta política de privacidad, contáctenos en <a href="mailto:info@sneaks.com">info@sneaks.com</a>.</p>
            
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