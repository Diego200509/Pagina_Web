    // Código para manejar el scroll y ocultar/mostrar el header
    let lastScrollTop = 0;
    const navbar = document.querySelector('navbar');

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop) {
            // Scroll hacia abajo
            header.style.top = "-100px"; // Esconde el header
        } else {
            // Scroll hacia arriba
            header.style.top = "0"; // Muestra el header
        }
        lastScrollTop = scrollTop;
    });

    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }

    document.querySelector('.next').addEventListener('click', nextSlide);
    document.querySelector('.prev').addEventListener('click', prevSlide);

    // Cambia el slide cada 5 segundos
    setInterval(nextSlide, 5000);
