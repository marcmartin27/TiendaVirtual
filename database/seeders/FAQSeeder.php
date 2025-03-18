<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    public function run()
    {
        FAQ::create(['question' => '¿Cuál es tu horario?', 'answer' => 'Estamos abiertos de lunes a viernes de 9 AM a 6 PM.']);
        FAQ::create(['question' => '¿Dónde están ubicados?', 'answer' => 'Nuestra tienda está en la Calle Principal #123, Ciudad.']);
        FAQ::create(['question' => '¿Aceptan tarjetas de crédito?', 'answer' => 'Sí, aceptamos todas las tarjetas de crédito y débito.']);
    }
}