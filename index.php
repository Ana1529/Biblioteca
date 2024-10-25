<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Virtual</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Logo Biblioteca">
        </div>
        <input type="text" class="search-bar" placeholder="Buscar libros...">
        <div class="user-info">
            <button class="login-btn">Acceder</button>
        </div>
    </header>

    <nav class="sidebar">
        <ul>
            <li>Principal</li>
            <li>Categorias</li>
            <li>Recomendados</li>
            <li>Favoritos</li>
            <li>Historial</li>
        </ul>
    </nav>

    <main class="content">
        <div class="book-grid">
            <div class="book-card">
                <img src="img/7.jpeg" alt="Book Thumbnail">
                <h3>El Principito</h3>
                <p>Antoine de Saint-Exupéry</p>
                <p>1.2K lecturas · hace 2 días</p>
            </div>
            <div class="book-card">
                <img src="img/7.jpeg" alt="Book Thumbnail">
                <h3>Cien Años de Soledad</h3>
                <p>Gabriel García Márquez</p>
                <p>3.5K lecturas · hace 1 semana</p>
            </div>
            <div class="book-card">
                <img src="img/7.jpeg"  alt="Book Thumbnail">
                <h3>Sapiens: De animales a dioses</h3>
                <p>Yuval Noah Harari</p>
                <p>2K lecturas · hace 3 días</p>
            </div>
        </div>
    </main>
        <!-- Pie de página mejorado -->
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
            <p class="footer-bottom">&copy; 2024 Mi Página. Todos los derechos reservados.</p>
        </footer>
    </div>

</body>
</html>
