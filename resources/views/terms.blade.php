<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Términos y Condiciones - Sneaks</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    @include('header')
    
    <div class="legal-wrapper">
        <div class="legal-header">
            <h1>Términos y Condiciones</h1>
            <div class="legal-meta">
                <span class="legal-date">Última actualización: 31 de marzo de 2025</span>
                <span class="legal-version">Versión 1.0</span>
            </div>
        </div>
        
        <nav class="legal-nav">
            <ul>
                <li><a href="/privacy">Política de Privacidad</a></li>
                <li><a href="/terms" class="active">Términos y Condiciones</a></li>
                <li><a href="/cookies">Política de Cookies</a></li>
                <li><a href="/legal">Aviso Legal</a></li>
            </ul>
        </nav>
        
        <div class="legal-content">
            <div class="toc">
                <h3>Contenido</h3>
                <ul>
                    <li><a href="#aceptacion">1. Aceptación de los términos</a></li>
                    <li><a href="#modificaciones">2. Modificaciones a los términos</a></li>
                    <li><a href="#uso">3. Uso del sitio</a></li>
                    <li><a href="#propiedad">4. Propiedad intelectual</a></li>
                    <li><a href="#limitacion">5. Limitación de responsabilidad</a></li>
                    <li><a href="#ley">6. Ley aplicable</a></li>
                    <li><a href="#contacto">7. Contacto</a></li>
                </ul>
            </div>

            <p>Estos términos y condiciones rigen el uso de nuestro sitio web y la compra de productos a través de él. Al acceder a este sitio, usted acepta cumplir con estos términos.</p>
            
            <h2 id="aceptacion">1. Aceptación de los términos</h2>
            <p>Al utilizar este sitio, usted acepta estos términos y condiciones en su totalidad. Si no está de acuerdo con estos términos, no debe utilizar este sitio.</p>
            
            <h2 id="modificaciones">2. Modificaciones a los términos</h2>
            <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Cualquier cambio será efectivo inmediatamente después de su publicación en el sitio.</p>
            
            <h2 id="uso">3. Uso del sitio</h2>
            <p>Usted se compromete a utilizar el sitio solo para fines legales y de acuerdo con todas las leyes aplicables.</p>
            
            <div class="highlighted-section">
                <h3>Usos prohibidos:</h3>
                <ul>
                    <li>Utilizar el sitio de manera que cause o pueda causar daño al sitio o deteriorar la disponibilidad del mismo</li>
                    <li>Utilizar el sitio para cualquier fin fraudulento o en conexión con un delito</li>
                    <li>Utilizar el sitio para enviar, usar o reutilizar cualquier material que sea ilegal, ofensivo o que viole la privacidad de otra persona</li>
                    <li>Acceder sin autorización, interferir, dañar o interrumpir cualquier parte de nuestro sitio, el servidor en el que se almacena o cualquier servidor, computadora o base de datos conectada a nuestro sitio</li>
                </ul>
            </div>
            
            <h2 id="propiedad">4. Propiedad intelectual</h2>
            <p>Todo el contenido de este sitio, incluyendo texto, gráficos, logotipos, imágenes y software, es propiedad de nuestra empresa o de nuestros proveedores de contenido y está protegido por las leyes de propiedad intelectual.</p>
            
            <h2 id="limitacion">5. Limitación de responsabilidad</h2>
            <p>No seremos responsables de ningún daño directo, indirecto, incidental o consecuente que resulte del uso o la imposibilidad de uso de este sitio.</p>
            
            <div class="legal-notice-box">
                <h4>Importante</h4>
                <p>Nada en estos términos excluye o limita nuestra responsabilidad por muerte o lesiones personales derivadas de nuestra negligencia, fraude o cualquier otra responsabilidad que no pueda ser excluida o limitada por la ley española.</p>
            </div>
            
            <h2 id="ley">6. Ley aplicable</h2>
            <p>Estos términos se regirán e interpretarán de acuerdo con las leyes del país donde se encuentra nuestra empresa.</p>
            
            <h2 id="contacto">7. Contacto</h2>
            <p>Si tiene alguna pregunta sobre estos términos, por favor contáctenos a través de nuestro correo electrónico: <a href="mailto:info@sneaks.com">info@sneaks.com</a>.</p>
            
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