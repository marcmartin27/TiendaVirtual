<!-- resources/views/invoices/template.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.4;
        }
        .invoice-header {
            padding: 20px 0;
            border-bottom: 1px solid #594C45;
            margin-bottom: 20px;
            position: relative;
        }
        .logo {
            max-width: 150px;
            max-height: 80px;
        }
        .invoice-title {
            font-size: 28px;
            color: #594C45;
            margin-top: 20px;
        }
        .company-info {
            text-align: right;
            position: absolute;
            top: 20px;
            right: 0;
        }
        .client-info, .order-info {
            margin-bottom: 20px;
        }
        h3 {
            color: #594C45;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f5f3f2;
            color: #594C45;
            font-weight: bold;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 40%;
            margin-left: auto;
        }
        .totals td {
            border: none;
            padding: 5px 10px;
        }
        .totals .total-row {
            font-weight: bold;
            border-top: 1px solid #594C45;
            font-size: 18px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <img src="{{ public_path('images/logo.png') }}" alt="Sneaks Logo" class="logo">
        <div class="company-info">
            <div><strong>Sneaks</strong></div>
            <div>Calle san juan de la barca, 22</div>
            <div>08915, Badalona</div>
            <div>España</div>
        </div>
    </div>
    
    <div class="invoice-title">
        Pedido #{{ $order->id }}
    </div>
    
    <div class="client-info">
        <h3>Datos del Cliente</h3>
        <p>
            <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
            {{ $order->address }}<br>
            @if($order->apartment) {{ $order->apartment }}<br> @endif
            {{ $order->postal_code }}, {{ $order->city }}<br>
            {{ $order->province }}, {{ $order->country }}<br>
            <strong>Teléfono:</strong> {{ $order->phone }}<br>
            <strong>Email:</strong> {{ $user->email }}
        </p>
    </div>
    
    <div class="order-info">
        <h3>Detalles del Pedido</h3>
        <p>
            <strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
            <strong>Estado:</strong> {{ ucfirst($order->status) }}<br>
        </p>
    </div>
    
    <h3>Productos</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name ?? $item->product->name }}</td>
                    <td>{{ $item->size }}</td>
                    <td class="text-right">{{ number_format($item->price, 2) }} €</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">{{ number_format($order->total + $order->discount_amount, 2) }} €</td>
        </tr>
        @if($order->discount_amount > 0)
        <tr>
            <td>Descuento @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
            <td class="text-right">-{{ number_format($order->discount_amount, 2) }} €</td>
        </tr>
        @endif
        <tr>
            <td>IVA (21%)</td>
            <td class="text-right">Incluido</td>
        </tr>
        <tr>
            <td>Gastos de envío</td>
            <td class="text-right">Gratis</td>
        </tr>
        <tr class="total-row">
            <td>Total</td>
            <td class="text-right">{{ number_format($order->total, 2) }} €</td>
        </tr>
    </table>
    
    <div class="footer">
        Gracias por tu compra. Si tienes alguna duda sobre esta factura, 
        ponte en contacto con nosotros en info@sneaks.com o llámanos al +34 666 666 666.
    </div>
</body>
</html>