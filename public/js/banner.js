document.addEventListener("DOMContentLoaded", function () {
    let banners = [
        "/images/banner1.png",
        "/images/banner2.png"
    ];
    let index = 0;
    let bannerElement = document.getElementById("bannerPromocional");

    setInterval(() => {
        bannerElement.style.opacity = 0; // Desvanecer

        setTimeout(() => {
            index = (index + 1) % banners.length;
            bannerElement.src = banners[index];
            bannerElement.style.opacity = 1; // Aparecer
        }, 500); // Espera 0.5s antes de cambiar la imagen
    }, 5000); // Cambia cada 3 segundos
});

document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.querySelector(".featured-wrapper");
    const cards = document.querySelectorAll(".featured-card");
    const visibleCards = 5;
    let index = 0;
    const cardWidth = cards[0].offsetWidth + 15; // Ancho de una tarjeta + gap
    const totalCards = cards.length;
    
    function autoScroll() {
        index++;
        wrapper.style.transition = "transform 0.5s ease-in-out";
        wrapper.style.transform = `translateX(-${index * cardWidth}px)`;

        // Cuando llegamos al final, reiniciamos la posición sin transición
        if (index === totalCards - visibleCards) {
            setTimeout(() => {
                wrapper.style.transition = "none"; // Quitamos la animación
                wrapper.style.transform = `translateX(0px)`;
                index = 0;
            }, 3500); // Esperamos a que termine la animación antes de resetear
        }
    }

    setInterval(autoScroll, 3000); // Cambia cada 3 segundos
});

