<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes - Biblioteca Digital</title>
    <link rel="stylesheet" href="css/ajustes_estilos.css"> <!-- Enlace al archivo CSS -->
    <script src="java.js" defer></script> <!-- Enlace al archivo JavaScript -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <!-- Encabezado -->
    <header>
    <div class="logo">
        <img src="imagenes/logoazul.jpg" alt="Logo">
    </div>
    
    <!-- Contenedor general de la barra de navegación -->
    <nav>
            <ul class="nav-links">
                <li><a href="Pagina_principal.php">Inicio</a></li>
                <li><a href="">Libros favoritos</a></li>
                
            </ul>
        </nav>
        <!-- Buscador centrado -->
        <div class="search-bar">
            <form onsubmit="buscarLibros(event)">
                <input type="text" id="buscador" placeholder="Buscar libros">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <!-- Iconos de notificación y usuario a la derecha -->
        <div class="icons">
            <i class="fas fa-bell"></i>
            <i class="fas fa-user-circle"></i>
        </div>
    </div>
    <!-- Barra de categorías -->
       
    <div class="category-bar">
            <button class="category">Todos</button>
            <button class="category">Ficción</button>
            <button class="category">Infantil</button>
            <button class="category">Juvenil</button>
            <button class="category">Romance</button>
            <button class="category">Suspenso</button>
            <button class="category">Fantasía</button>
            <button class="category">Historia</button>
            <!-- Puedes agregar más categorías aquí -->
        </div>
</header>
   <!-- Contenido de ajustes -->
<div class="container">
    <!-- Sección para editar usuario -->
    <section class="config-section">
        <h2>Editar Usuario</h2>
        <form id="edit-user-form">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" value="UsuarioActual">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" value="correo@ejemplo.com">
            <button type="submit">Guardar cambios</button>
        </form>
    </section>

    <!-- Sección de notificaciones -->
    <section class="config-section">
        <h2>Notificaciones</h2>
        <p>Selecciona tus preferencias de notificación.</p>
        <label>
            <input type="checkbox" id="email-notifications">
            Notificaciones por correo electrónico
        </label>
        <label>
            <input type="checkbox" id="sms-notifications">
            Notificaciones por SMS
        </label>
    </section>
</div>


    <!-- Pie de página -->
    <footer>
    <div class="footer-container">
        <div class="contact-info">
            <h3>Contáctanos</h3>
            <p><i class="fas fa-phone"></i> +123 456 7890</p>
            <p><i class="fas fa-envelope"></i> contacto@mipagina.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Calle Falsa 123, Ciudad</p>
        </div>
        <div class="social-media">
            <h3>Síguenos</h3>
            <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
    </div> 
    <p class="footer-bottom">&copy; 2024 Biblioteca Digital. Todos los derechos reservados.</p>
</footer>


</body>
</html>
