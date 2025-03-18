<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    public function run()
    {
        FAQ::create(['question' => '¿Qué marcas de zapatillas venden?', 'answer' => 'Trabajamos con marcas como Nike, Adidas, Puma, Reebok y más.']);  

        FAQ::create(['question' => '¿Tienen zapatillas para correr?', 'answer' => 'Sí, contamos con una amplia selección de zapatillas especializadas para running.']);  
        
        FAQ::create(['question' => '¿Cuáles son sus métodos de pago?', 'answer' => 'Aceptamos tarjetas de crédito, débito, transferencias bancarias y pagos en efectivo en tienda.']);  
        
        FAQ::create(['question' => '¿Ofrecen envíos a domicilio?', 'answer' => 'Sí, realizamos envíos a todo el país. El tiempo de entrega varía según la ubicación.']);  
        
        FAQ::create(['question' => '¿Cómo puedo saber mi talla?', 'answer' => 'Disponemos de una guía de tallas en cada producto para ayudarte a elegir la correcta.']);  
        
        FAQ::create(['question' => '¿Puedo cambiar o devolver un producto?', 'answer' => 'Sí, aceptamos cambios y devoluciones dentro de los primeros 30 días con el ticket de compra.']);  
        
        FAQ::create(['question' => '¿Tienen descuentos o promociones?', 'answer' => 'Sí, revisa nuestra sección de ofertas o suscríbete a nuestro newsletter para recibir descuentos exclusivos.']);  
        
        FAQ::create(['question' => '¿Las zapatillas son originales?', 'answer' => 'Sí, todos nuestros productos son 100% originales y cuentan con garantía de fábrica.']);  
        
        FAQ::create(['question' => '¿Puedo reservar un modelo antes de que llegue a la tienda?', 'answer' => 'Sí, ofrecemos preventa en algunos modelos exclusivos. Contáctanos para más información.']);  
        
        FAQ::create(['question' => '¿Cuál es el horario de atención?', 'answer' => 'Estamos abiertos de lunes a sábado de 10 AM a 8 PM y domingos de 11 AM a 6 PM.']);  
        
    }
}