
    document.addEventListener("DOMContentLoaded", function() {
        const adminAccordion = document.getElementById("adminAccordion");
        const body = document.body;
        const originalBackground = "linear-gradient(160deg, #ffffff, #1C9FFF)";

        adminAccordion.addEventListener("click", (event) => {
            if (event.target.matches(".accordion-button")) {
                const button = event.target;

                // Cambia el fondo según la sección seleccionada
                if (button.innerText.includes("Crear Admin")) {
                    body.style.background = "linear-gradient(160deg, #00BFFF, #87CEFA)";

                } else if (button.innerText.includes("Cambiar Colores")) {
                    body.style.background = "linear-gradient(160deg, #FF66CC, #FF1493)";
                } else if (button.innerText.includes("Cambiar Imágenes Inicio")) {
                    body.style.background = "linear-gradient(355deg, #FF66CC,rgb(255, 255, 255))";
                }

                // Si todas las secciones están contraídas, vuelve al fondo original
                const allCollapsed = [...document.querySelectorAll(".accordion-button")].every((btn) =>
                    btn.classList.contains("collapsed")
                );

                if (allCollapsed) {
                    body.style.background = originalBackground;
                }
            }
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const message = urlParams.get('message');

        if (message) {
            const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            const messageIcon = document.getElementById('messageIcon');
            const messageModalLabel = document.getElementById('messageModalLabel');
            const messageText = document.getElementById('messageText');

            if (success === '1') {
                messageIcon.className = 'fas fa-check-circle fa-3x text-success';
                messageModalLabel.textContent = '¡Éxito!';
            } else {
                messageIcon.className = 'fas fa-times-circle fa-3x text-danger';
                messageModalLabel.textContent = '¡Error!';
            }

            messageText.textContent = message;
            messageModal.show();
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const colorInput = document.getElementById("colorNavbar");
        const hexInput = document.getElementById("hexColorNavbar");
        const defaultColor = "#00bfff";

        // Sincronizar el campo de texto hexadecimal con el selector de color
        colorInput.addEventListener("input", function() {
            hexInput.value = colorInput.value;
        });

        // Sincronizar el selector de color con el campo de texto hexadecimal
        hexInput.addEventListener("input", function() {
            const value = hexInput.value;
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                colorInput.value = value;
            }
        });

        // Manejar el evento del formulario
        const formNavbar = document.getElementById("formNavbar");
        formNavbar.addEventListener("submit", function(event) {
            const submitter = event.submitter;

            if (submitter.name === "resetNavbar" && submitter.value === "1") {
                // Restablecer el color al valor por defecto
                colorInput.value = defaultColor;
                hexInput.value = defaultColor;

                // Guardar en localStorage que se ha restablecido
                localStorage.setItem("navbarColorUpdated", "reset");
            } else {
                // Guardar que el color ha sido actualizado
                localStorage.setItem("navbarColorUpdated", "true");
            }

            // Limpiar el estado en el localStorage después de 1 segundo
            setTimeout(() => {
                localStorage.removeItem("navbarColorUpdated");
            }, 1000);
        });
    });


    document.addEventListener("DOMContentLoaded", function () {
// Selección de elementos
const gradientStartInput = document.getElementById("gradientStartLogin");
const hexGradientStartInput = document.getElementById("hexGradientStartLogin");
const gradientEndInput = document.getElementById("gradientEndLogin");
const hexGradientEndInput = document.getElementById("hexGradientEndLogin");
const formLogin = document.getElementById("formLogin");

// Valores por defecto
const defaultStartColor = "#FF007B";
const defaultEndColor = "#1C9FFF";

// Sincronizar el input de texto hexadecimal con el selector de color (Color Inicial)
gradientStartInput.addEventListener("input", function () {
    hexGradientStartInput.value = gradientStartInput.value;
});

// Sincronizar el selector de color con el input de texto hexadecimal (Color Inicial)
hexGradientStartInput.addEventListener("input", function () {
    const value = hexGradientStartInput.value;
    if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
        gradientStartInput.value = value;
    }
});

// Sincronizar el input de texto hexadecimal con el selector de color (Color Final)
gradientEndInput.addEventListener("input", function () {
    hexGradientEndInput.value = gradientEndInput.value;
});

// Sincronizar el selector de color con el input de texto hexadecimal (Color Final)
hexGradientEndInput.addEventListener("input", function () {
    const value = hexGradientEndInput.value;
    if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
        gradientEndInput.value = value;
    }
});

// Manejar el evento de envío del formulario
if (formLogin) {
    formLogin.addEventListener("submit", function (event) {
        const submitter = event.submitter;

        if (submitter.name === "reset" && submitter.value === "1") {
            // Restablecer valores por defecto
            gradientStartInput.value = defaultStartColor;
            hexGradientStartInput.value = defaultStartColor;
            gradientEndInput.value = defaultEndColor;
            hexGradientEndInput.value = defaultEndColor;

            // Guardar en localStorage que se restableció
            localStorage.setItem("loginColorUpdated", "reset");
        } else {
            // Guardar en localStorage que se actualizó
            localStorage.setItem("loginColorUpdated", "true");
        }

        // Limpiar el estado en localStorage después de 1 segundo
        setTimeout(() => {
            localStorage.removeItem("loginColorUpdated");
        }, 1000);
    });
}
});




