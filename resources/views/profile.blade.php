<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaks - Mi Perfil</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
    @include('header')

    <div class="profile-container">
        <div class="profile-tabs">
            <div class="tab active" data-tab="profile">Perfil</div>
            <div class="tab" data-tab="orders">Mis Pedidos</div>
        </div>

        <div class="tab-content profile-tab active" id="profile-tab">
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

        <div class="tab-content orders-tab" id="orders-tab">
            <h2>Mis Pedidos</h2>
            
            @if(isset($orders) && $orders->count() > 0)
                <div class="orders-list">
                    @foreach($orders as $order)
                        <div class="order-card">
                            <div class="order-header" data-order-id="{{ $order->id }}">
                                <div class="order-info">
                                    <div class="order-number">
                                        <span class="label">Pedido #</span>
                                        <span class="value">{{ $order->id }}</span>
                                    </div>
                                    <div class="order-date">
                                        <span class="label">Fecha:</span>
                                        <span class="value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                <div class="order-status-price">
                                    <div class="order-status status-{{ strtolower($order->status) }}">
                                        {{ ucfirst($order->status) }}
                                    </div>
                                    <div class="order-price">
                                        <span class="label">Total:</span>
                                        <span class="value">{{ number_format($order->total, 2) }} €</span>
                                    </div>
                                </div>
                                <div class="order-invoice">
                                    <a href="{{ route('invoice.generate', $order->id) }}" class="invoice-btn" title="Descargar factura">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                            <path fill="#594C45" d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                                        </svg>
                                        Factura
                                    </a>
                                </div>
                                <div class="order-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="currentColor" d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="order-details">
                                <div class="order-address">
                                    <h3>Dirección de envío</h3>
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
                                
                                <div class="order-items-container">
                                    <h3>Productos</h3>
                                    <div class="order-items-list">
                                        @foreach($order->items as $item)
                                            <div class="order-item">
                                            <div class="item-image">
                                                @php
                                                    $product = App\Models\Product::find($item->product_id);
                                                    $imageUrl = null;
                                                    
                                                    if ($product && $product->images->count() > 0) {
                                                        $image = $product->images->first();
                                                        
                                                        // Usar la misma lógica que en orderConfirmation.blade.php
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
                                                    <h4>{{ $item->product_name }}</h4>
                                                    <div class="item-specs">
                                                        <span class="item-size">Talla: {{ $item->size }}</span>
                                                        <span class="item-quantity">Cantidad: {{ $item->quantity }}</span>
                                                    </div>
                                                    <div class="item-price">{{ number_format($item->price, 2) }} €</div>
                                                </div>
                                                <div class="item-total">
                                                    <div class="item-total-label">Total:</div>
                                                    <div class="item-total-price">{{ number_format($item->price * $item->quantity, 2) }} €</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="order-summary">
                                @php
                                // Calcular el descuento basado en el código del cupón SIEMPRE que haya un cupón
                                $calculatedDiscountAmount = $order->discount_amount;
                                $originalTotal = $order->total;

                                if (!empty($order->coupon_code)) {
                                    // Si es un cupón SNEAKS10, suponemos 10% de descuento
                                    if (strpos($order->coupon_code, 'SNEAKS10') === 0) {
                                        // El precio actual es después del descuento, calculamos el original
                                        $originalTotal = $order->total / 0.9; // 100% - 10% = 90% = 0.9
                                        $calculatedDiscountAmount = $originalTotal - $order->total;
                                    }
                                }
                                @endphp
                                    
                                    <div class="summary-row">
                                        <span>Subtotal</span>
                                        <span>{{ number_format($originalTotal, 2) }} €</span>
                                    </div>
                                    
                                    @if(!empty($order->coupon_code))
                                    <div class="summary-row discount-row">
                                        <span>Descuento ({{ $order->coupon_code }})</span>
                                        <span>-{{ number_format($calculatedDiscountAmount, 2) }} €</span>
                                    </div>
                                    @endif
                                    
                                    <div class="summary-row">
                                        <span>Envío</span>
                                        <span>Gratis</span>
                                    </div>
                                    
                                    <div class="summary-row total">
                                        <span>Total</span>
                                        <span>{{ number_format($order->total, 2) }} €</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-orders">
                    <div class="no-orders-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64">
                            <path fill="#e0e0e0" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                        </svg>
                    </div>
                    <h3>Aún no tienes pedidos</h3>
                    <p>Explora nuestra tienda y encuentra productos que te encanten</p>
                    <a href="/" class="primary-button">Ir a comprar</a>
                </div>
            @endif
        </div>
    </div>

    @include('footer')

    <script src="{{ asset('js/profileOrders.js') }}"></script>
</body>
</html>