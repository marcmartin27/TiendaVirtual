let selectedSize = 38;
let quantity = 1;

function selectSize(size) {
    selectedSize = size;
    document.getElementById('selectedSize').innerText = size;
}

function updateQuantity(change) {
    if (quantity + change > 0) {
        quantity += change;
        document.getElementById('quantity').innerText = quantity;
    }
}