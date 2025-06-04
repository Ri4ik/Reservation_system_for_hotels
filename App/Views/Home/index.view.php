<main>
<div class="container-fluid">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper(".mySwiper", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                loop: true,
                // slidesPerView: 3,
                spaceBetween: 50,
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 150,
                    modifier: 2.5,
                    slideShadows: false,
                },
                keyboard: {
                    enabled: true,
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1.2,
                    },
                    480: {
                        slidesPerView: 1.5,
                    },
                    768: {
                        slidesPerView: 2.3,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 50,
                    }
                }
            });
        });
    </script>

    <div class="row">
        <div class="col mt-5 text-center">
            <h2>Vitajte v Hotel Forest Paradise</h2>
            <p class="lead">
                Luxus uprostred prírody – oddýchnite si v objatí lesa.
            </p>

            <p>
                Hotel Forest Paradise je tichý lesný rezort, ktorý kombinuje pohodlie moderného ubytovania
                s pokojom nedotknutej prírody. Či už túžite po romantickom víkende, rodinnom pobyte
                alebo len úniku z mesta, ste na správnom mieste.
            </p>
        </div>
    </div>

    <div class="row mt-5 justify-content-center">
        <div class="col-md-8 text-center">
            <h2>Galéria hotela</h2>
            <div class="swiper mySwiper mt-4">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="public/images/rooms.jpg" alt="Lesné chatky">
                        <p>Chatky uprostred lesa</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="public/images/rooms2.jpg" alt="Lesné chatky">
                        <p>Chatky uprostred lesa</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="public/images/wellness.jpg" alt="Wellness">
                        <p>Wellness & relax</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="public/images/wellness2.jpg" alt="Wellness">
                        <p>Wellness & relax</p>
                    </div>
                    <div class="swiper-slide">
                        <img src="public/images/tur.jpg" alt="Trasa">
                        <p>Turistické trasy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </main>
