<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
@include('header')
@extends('layouts.app')

<!-- Efecto confeti -->
<div class="confetti-container">
    @for ($i = 1; $i <= 15; $i++)
        <div class="confetti"></div>
    @endfor
</div>

<div class="confirmation-container">
    <div class="confirmation-content">
        <div class="confirmation-header">
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48" height="48">
                    <circle cx="12" cy="12" r="11" fill="white" fill-opacity="0.2"/>
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" fill="white"/>
                </svg>
            </div>
            <h1>¡Gracias por tu compra!</h1>
            <p class="confirmation-message">Tu pedido ha sido procesado correctamente y está en preparación.</p>
        </div>
        
        <div class="order-summary">
            <div class="summary-box">
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#594C45" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                    </svg>
                </div>
                <div class="summary-value">
                    <div class="summary-label">Número de pedido</div>
                    <div class="summary-data">#{{ $order->id }}</div>
                </div>
            </div>
            
            <div class="summary-box">
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#594C45" d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V9h14v10zM5 7V5h14v2H5zm2 4h10v2H7zm0 4h7v2H7z"/>
                    </svg>
                </div>
                <div class="summary-value">
                    <div class="summary-label">Fecha del pedido</div>
                    <div class="summary-data">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            
            <div class="summary-box">
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#594C45" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                    </svg>
                </div>
                <div class="summary-value">
                    <div class="summary-label">Estado</div>
                    <div class="summary-data status-badge">{{ ucfirst($order->status) }}</div>
                </div>
            </div>
            
            <div class="summary-box">
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="#594C45" d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                    </svg>
                </div>
                <div class="summary-value">
                    <div class="summary-label">Método de pago</div>
                    <div class="summary-data">PayPal</div>
                </div>
            </div>
        </div>
        
        <div class="confirmation-details">
            <div class="confirmation-section">
                <h2>Resumen del pedido</h2>
                <div class="price-summary">
                    <div class="price-row">
                        <span>Subtotal</span>
                        <span>{{ number_format($order->total, 2) }} €</span>
                    </div>
                    <div class="price-row">
                        <span>Envío</span>
                        <span>Gratis</span>
                    </div>
                    <div class="price-row total">
                        <span>Total</span>
                        <span>{{ number_format($order->total, 2) }} €</span>
                    </div>
                </div>
            </div>
            
            <div class="confirmation-section">
                <h2>Dirección de envío</h2>
                <div class="address-card">
                    <div class="address-name">{{ $order->first_name }} {{ $order->last_name }}</div>
                    <div class="address-line">{{ $order->address }}</div>
                    @if($order->apartment)
                        <div class="address-line">{{ $order->apartment }}</div>
                    @endif
                    <div class="address-line">{{ $order->postal_code }}, {{ $order->city }}</div>
                    <div class="address-line">{{ $order->province }}, {{ $order->country }}</div>
                    <div class="address-phone">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                            <path fill="#594C45" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        {{ $order->phone }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="order-items">
            <h2>Productos comprados</h2>
            <div class="order-items-list">
                @foreach($order->items as $item)
                    <div class="order-item">
                    <div class="item-image">
                        @php
                            $product = App\Models\Product::find($item->product_id);
                            $imageUrl = null;
                            
                            if ($product && $product->images->count() > 0) {
                                $image = $product->images->first();
                                
                                // Usar la misma lógica que el CartController
                                if (isset($image->image_url)) {
                                    if (strpos($image->image_url, 'http') === 0) {
                                        $imageUrl = $image->image_url;
                                    } else {
                                        $imageUrl = asset('images/' . $image->image_url);
                                    }
                                }
                            }
                        @endphp
                        
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}">
                        @else
                            <!-- Imagen de placeholder -->
                            <div class="no-image">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="#594C45" d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                        <div class="item-details">
                            <h3>{{ $item->product_name }}</h3>
                            <div class="item-specs">
                                <span class="item-size">Talla: {{ $item->size }}</span>
                                <span class="item-quantity">Cantidad: {{ $item->quantity }}</span>
                            </div>
                            <div class="item-price">{{ number_format($item->price, 2) }} €</div>
                        </div>
                        <div class="item-total">
                            <div class="item-total-label">Total:</div>
                            <div class="item-total-price">{{ number_format($item->total_price, 2) }} €</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="confirmation-message-box">
            <div class="message-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="#594C45" d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-9 11h-2v-2h2v2zm0-4h-2V7h2v4z"/>
                </svg>
            </div>
            <div class="message-content">
                <h3>Información de envío</h3>
                <p>El plazo promedio de entrega es de 2 a 4 días.</p>
            </div>
        </div>
        
        <div class="confirmation-actions">
            <a href="/" class="primary-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                    <path fill="currentColor" d="M11 9l1.42 1.42L8.83 14H18V16H8.83l3.59 3.58L11 21l-6-6 6-6z"/>
                </svg>
                Volver a la tienda
            </a>
            <a href="/profile" class="secondary-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                    <path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Ver mis pedidos
            </a>
            <a href="{{ route('invoice.generate', $order->id) }}" class="invoice-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                    <path fill="currentColor" d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                </svg>
                Descargar factura
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animar la entrada de los elementos
        const elements = document.querySelectorAll('.confirmation-header, .order-summary, .confirmation-section, .order-item, .confirmation-actions');
        
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    });
</script>

@include('footer')

</body>

