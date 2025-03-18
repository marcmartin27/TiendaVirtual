<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FAQ;

class ChatbotController extends Controller
{

    public function index()
    {
        $questions = FAQ::pluck('question'); // Obtener solo las preguntas
        return view('chatbot', compact('questions'));
    }

    public function chat(Request $request)
    {
        $userQuestion = $request->input('message');

        // Buscar una coincidencia exacta en la base de datos
        $faq = FAQ::where('question', $userQuestion)->first();

        if ($faq) {
            return response()->json(['response' => $faq->answer]);
        } else {
            return response()->json(['response' => 'Lo siento, no tengo una respuesta para eso.']);
        }
    }
}

