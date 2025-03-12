<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
@extends('layouts.app')
@include('header')
<div class="checkout-container">
    <h1>Finalizar Compra</h1>
    
    <div class="checkout-content">
        <!-- Formulario de Compra (Lado izquierdo) -->
        <div class="checkout-form">
            <form action="{{ route('order.process') }}" method="POST">
                @csrf
                
                <div class="form-section">
                    <h2>Contacto</h2>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" required>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Entrega</h2>
                    <div class="form-group">
                        <label for="country">País</label>
                        <select id="country" name="country" required>
                            <option value="ES">España</option>
                            <option value="FR">Francia</option>
                            <option value="IT">Italia</option>
                            <option value="DE">Alemania</option>
                            <option value="PT">Portugal</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="first_name">Nombre</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group half">
                            <label for="last_name">Apellidos</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apartment">Casa/Apartamento (opcional)</label>
                        <input type="text" id="apartment" name="apartment">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="postal_code">Código Postal</label>
                            <input type="text" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="form-group half">
                            <label for="city">Ciudad</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="province">Provincia</label>
                        <input type="text" id="province" name="province" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Pago</h2>
                    <p>Métodos de pago disponibles próximamente</p>
                </div>
                
                <div class="checkout-actions">
                    <a href="/" class="back-link">Volver a la tienda</a>
                    <button type="submit" class="checkout-button">Completar Compra</button>
                </div>
            </form>
        </div>
        
        <!-- Resumen del Carrito (Lado derecho) -->
        <div class="cart-summary">
            <h2>Tu Pedido</h2>
            <div class="summary-items" id="summaryItems">
                <!-- Aquí se cargarán los items del carrito con JavaScript -->
            </div>
            
            <div class="summary-totals">
                <div class="subtotal">
                    <span>Subtotal</span>
                    <span id="subtotal">0.00 €</span>
                </div>
                <div class="shipping">
                    <span>Envío</span>
                    <span>Gratuito</span>
                </div>
                <div class="total">
                    <span>Total</span>
                    <span id="total">0.00 €</span>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')
<!-- Hidden input to store cart data -->
<input type="hidden" id="cartData" value="">

<!-- Scripts -->
<script src="{{ asset('js/orderFinish.js') }}"></script>
</body>

