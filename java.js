// Función para mostrar/ocultar el menú de categorías
function toggleDropdown() {
    document.getElementById("dropdownContent").classList.toggle("show");
}

// Cerrar el menú desplegable si se hace clic fuera de él
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
// Buscador
function buscarLibros(event) {
    event.preventDefault(); // Evita el envío del formulario y recarga de la página
    const filtro = document.getElementById("buscador").value.toLowerCase();
    const libros = document.querySelectorAll(".libros .libro");

    libros.forEach(function(libro) {
        const titulo = libro.querySelector("h3").textContent.toLowerCase();
        libro.style.display = titulo.includes(filtro) ? "block" : "none";
    });
}
//pagina de ajustes

// Escucha el evento de envío en el formulario de editar usuario
document.getElementById("edit-user-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita el envío real del formulario
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;

    // Simulación de guardado de datos (puedes reemplazarlo con una llamada a un servidor)
    alert(`Cambios guardados:\nNombre de usuario: ${username}\nCorreo electrónico: ${email}`);
});

// Manejo de notificaciones
document.getElementById("email-notifications").addEventListener("change", function() {
    if (this.checked) {
        alert("Notificaciones por correo activadas.");
    } else {
        alert("Notificaciones por correo desactivadas.");
    }
});

document.getElementById("sms-notifications").addEventListener("change", function() {
    if (this.checked) {
        alert("Notificaciones por SMS activadas.");
    } else {
        alert("Notificaciones por SMS desactivadas.");
    }
});
//pagina de prestamos
document.addEventListener("DOMContentLoaded", () => {
    const returnQuestionnaire = document.getElementById("returnQuestionnaire");
    const loanRequestForm = document.getElementById("loanRequestForm");

    document.querySelectorAll(".return-btn").forEach(button => {
        button.addEventListener("click", () => {
            const loanId = button.dataset.loanId;
            document.getElementById("loanId").value = loanId;
            returnQuestionnaire.style.display = "block";
        });
    });
});
