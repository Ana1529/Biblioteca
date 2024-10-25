document.addEventListener('DOMContentLoaded', function() {
    const categoriasBtn = document.getElementById('categorias');
    const submenuCategorias = document.getElementById('submenu-categorias');

    // Mostrar/ocultar el menú al hacer clic en "Categorías"
    categoriasBtn.addEventListener('click', function(e) {
        e.preventDefault();
        submenuCategorias.classList.toggle('active');
    });

    // Cerrar el submenú si se hace clic en cualquier otro lugar de la página
    document.addEventListener('click', function(e) {
        if (!categoriasBtn.contains(e.target) && !submenuCategorias.contains(e.target)) {
            submenuCategorias.classList.remove('active');
        }
    });
});
