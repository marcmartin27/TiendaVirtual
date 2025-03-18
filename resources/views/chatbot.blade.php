<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <style>
        #chatbox { border: 1px solid #ccc; padding: 10px; width: 300px; height: 400px; overflow-y: auto; }
        .message { margin: 5px 0; }
        .user { text-align: right; color: blue; }
        .bot { text-align: left; color: green; }
    </style>
</head>
<body>

    <div id="chatbox">
        <p><strong>Bot:</strong> Hola, ¿cómo puedo ayudarte?</p>
    </div>

    <select id="message">
        <option value="" disabled selected>Selecciona una pregunta...</option>
        @foreach($questions as $question)
            <option value="{{ $question }}">{{ $question }}</option>
        @endforeach
    </select>
    <button onclick="sendMessage()">Enviar</button>

    <script>
        function sendMessage() {
            let select = document.getElementById("message");
            let message = select.value;
            if (!message) return;

            let chatbox = document.getElementById("chatbox");
            chatbox.innerHTML += `<p class="message user"><strong>Tú:</strong> ${message}</p>`;

            fetch('/chatbot/send', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                chatbox.innerHTML += `<p class="message bot"><strong>Bot:</strong> ${data.response}</p>`;
                chatbox.scrollTop = chatbox.scrollHeight;
            });

            select.selectedIndex = 0; // Reiniciar selección
        }
    </script>

</body>
</html>
