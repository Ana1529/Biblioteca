let isLogin = true; // Estado inicial (login visible)

function toggleForm() {
    const formWrapper = document.getElementById('form-wrapper');
    if (isLogin) {
        formWrapper.style.transform = 'translateX(-100%)'; // Mueve el registro a la vista
    } else {
        formWrapper.style.transform = 'translateX(0)'; // Mueve el login a la vista
    }
    isLogin = !isLogin; // Cambia el estado
}

function login() {
    // Lógica de inicio de sesión
    alert('Iniciando sesión...');
}

function register() {
    // Lógica de registro
    alert('Registrándose...');
}
// script.js

document.querySelectorAll('.toggle-btn').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelector('.container').classList.toggle('active');
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const categoriasBtn = document.getElementById('categorias');
    const submenuCategorias = document.getElementById('submenu-categorias');

    categoriasBtn.addEventListener('click', function(e) {
        e.preventDefault();
        submenuCategorias.style.display = submenuCategorias.style.display === 'block' ? 'none' : 'block';
    });

    // Para cerrar el submenú si se hace clic en cualquier otro lugar de la página
    document.addEventListener('click', function(e) {
        if (!categoriasBtn.contains(e.target) && !submenuCategorias.contains(e.target)) {
            submenuCategorias.style.display = 'none';
        }
    });
});
