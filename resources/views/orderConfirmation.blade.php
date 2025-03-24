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

<div class="confirmation-container">
    <div class="confirmation-content">
        <div class="confirmation-header">
            <h1>¡Gracias por tu compra!</h1>
            <p class="confirmation-message">Tu pedido ha sido procesado correctamente.</p>
        </div>
        
        <div class="confirmation-details">
            <div class="confirmation-section">
                <h2>Detalles del Pedido</h2>
                <p><strong>Número de pedido:</strong> #{{ $order->id }}</p>
                <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Total:</strong> {{ number_format($order->total, 2) }} €</p>
                <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Método de pago:</strong> PayPal</p>
            </div>
            
            <div class="confirmation-section">
                <h2>Dirección de Envío</h2>
                <p>{{ $order->first_name }} {{ $order->last_name }}</p>
                <p>{{ $order->address }}</p>
                @if($order->apartment)
                    <p>{{ $order->apartment }}</p>
                @endif
                <p>{{ $order->postal_code }}, {{ $order->city }}</p>
                <p>{{ $order->province }}, {{ $order->country }}</p>
                <p>{{ $order->phone }}</p>
            </div>
        </div>
        
        <div class="order-items">
            <h2>Productos</h2>
            <div class="order-items-list">
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-details">
                            <h3>{{ $item->product_name }}</h3>
                            <p><strong>Talla:</strong> {{ $item->size }}</p>
                            <p><strong>Cantidad:</strong> {{ $item->quantity }}</p>
                            <p><strong>Precio:</strong> {{ number_format($item->price, 2) }} €</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="confirmation-actions">
            <a href="/" class="primary-button">Volver a la tienda</a>
            <a href="/profile" class="secondary-button">Ver mis pedidos</a>
        </div>
    </div>
</div>

@include('footer')

</body>

