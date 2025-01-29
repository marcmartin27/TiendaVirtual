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
