<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajustes - Biblioteca Digital</title>
    <link rel="stylesheet" href="estilosLIZ.css"> <!-- Enlace al archivo CSS -->
    <script src="java.js" defer></script> <!-- Enlace al archivo JavaScript -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <!-- Encabezado -->
    <header>
    <div class="logo">
            <img src="imagenes/logoazul.jpg" alt=""></a>
            <a href="#"></a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn" onclick="toggleDropdown()">Categorías</a>
                    <div class="dropdown-content" id="dropdownContent">
                        <a href="categoria.php?cat=ficcion">Ficción</a>
                        <a href="categoria.php?cat=infantil">Infantil</a>
                        <a href="categoria.php?cat=juvenil">Juvenil</a>
                        <a href="categoria.php?cat=romance">Romance</a>
                        <a href="categoria.php?cat=suspenso">Suspenso</a>
                        <a href="categoria.php?cat=fantasia">Fantasía</a>
                        <a href="categoria.php?cat=historia">Historia</a>
                    </div>
                </li>
                <li><a href="libros.php">Libros</a></li>
            </ul>
        </nav>
        <h1>Ajustes</h1>
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
