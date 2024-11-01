<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos de Libros</title>
    <link rel="stylesheet" href="estilosLIZ.css">
    <script src="java.js" defer></script> 
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
                <li><a href="libros.php">Libros favoritos</a></li>
                
            </ul>
        </nav>
        <div class="search-bar">
             <form onsubmit="buscarLibros(event)">
                <input type="text" id="buscador" placeholder="Buscar libros">
                <button type="submit">Buscar</button>
            </form>
        </div>
    </header>

        <!-- Contenedor principal -->
     <div class="container">
        
            <!-- Barra lateral -->
                <aside class="sidebar">
                    <h2>Opciones</h2>
                    <a href="prestamos.php">Préstamos de Libros</a>
                    <a href="ajustes.php">Ajustes</a>
                </aside>
    
         <!-- Contenido principal -->
          <main class="content">
            <h1> Libros</h1>
            <div class="libros">
                <div class="libro">
                    <img src="imagenes/libro1.jpg" alt="Libro 1">
                    <h3>Matar a un ruiseñor</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro2.jpg" alt="Libro 2">
                    <h3>Harry Potter y la piedra filosofal</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro3.jpg" alt="Libro 3">
                    <h3>Orgullo y prejuicio</h3>
                </div>
                
                <div class="libro">
                    <img src="imagenes/libro4.jpg" alt="Libro 4">
                    <h3>El gran Gatsby</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro5.jpg" alt="Libro 5">
                    <h3>La isla de los perdidos</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro6.jpg" alt="Libro 6">
                    <h3>Don quijote de la mancha</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro7.jpg" alt="Libro 7">
                    <h3>El código Da Vinci </h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro8.jpg" alt="Libro 8">
                    <h3>El Principito</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro9.jpg" alt="Libro 9">
                    <h3>Matilda</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro10.jpg" alt="Libro 10">
                    <h3>Hamlet</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro11.jpg" alt="Libro 11">
                    <h3>La guerra de los dos mundos</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro12.jpg" alt="Libro 12">
                    <h3>Los tres mosqueteros</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro13.jpg" alt="Libro 13">
                    <h3>El diario de Anne Frank</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro14.jpg" alt="Libro 14">
                    <h3>Alicia en el pais de las maravillas</h3>
                </div>
                <div class="libro">
                    <img src="imagenes/libro15.jpg" alt="Libro 15">
                    <h3>Rayuela</h3>
                    
                </div>
                <div class="libro">
                    <img src="imagenes/libro14.jpg" alt="Libro 14">
                    <h3>Alicia en el pais de las maravillas</h3>
                </div>
            </div>
        </main>
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