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

    function autoScroll() {
        if (cards.length <= visibleCards) return; // No hace scroll si hay 5 o menos
        
        index++;
        if (index > cards.length - visibleCards) {
            index = 0; // Reinicia el scroll cuando llega al final
        }

        const offset = -index * (100 / visibleCards);
        wrapper.style.transform = `translateX(${offset}%)`;
    }

    setInterval(autoScroll, 4000); // Cambia cada 4 segundos
});

