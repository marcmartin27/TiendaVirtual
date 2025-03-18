document.addEventListener('DOMContentLoaded', function() {
    const chatbotButton = document.getElementById('chatbotButton');
    const chatbotPopup = document.getElementById('chatbotPopup');
    const closeChatbotButton = document.querySelector('.close-chatbot');

    chatbotButton.addEventListener('click', function() {
        chatbotPopup.classList.toggle('hidden');
    });

    closeChatbotButton.addEventListener('click', function() {
        chatbotPopup.classList.add('hidden');
    });
});

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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        chatbox.innerHTML += `<p class="message bot"><strong>Bot:</strong> ${data.response}</p>`;
        chatbox.scrollTop = chatbox.scrollHeight;
    });

    select.selectedIndex = 0;
}