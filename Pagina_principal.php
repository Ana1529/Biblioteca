<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Conectar a la base de datos
$servidor = "localhost";
$usuario = "root"; 
$contrasena = ""; 
$nombre_bd = 'biblioteca';

$conexion = new mysqli($servidor, $usuario, $contrasena, $nombre_bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consultar libros
$query = "SELECT l.*, c.nombre AS categoria FROM libros l JOIN categorias c ON l.id_categoria = c.id_categoria";
$resultado = $conexion->query($query);

$libros = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $libros[] = $fila;
    }
} else {
    echo "Error al obtener los libros: " . $conexion->error;
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="css/pagina_principal.css">
    <script src="java.js" defer></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<!-- Encabezado -->
<header>
    <div class="logo">
        <img src="imagenes/logoazul.jpg" alt="Logo">
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
        <h1> Libros</h1><br>
        <br><div class="libros">
            <?php foreach ($libros as $libro): ?>
                <div class="libro">
                    <img src="<?php echo htmlspecialchars($libro['imagen']); ?>" alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($libro['titulo']); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<!-- Pie de página -->
<footer>
    <div class="footer-container">
        <div class="contact-info">
            <h3>Contáctanos</h3>
            <p><i class="fas fa-phone"></i> +123 456 7890</p>
            <p><i class="fas fa-envelope"></i>Bibliotecachicas@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Venezuela 1170, BS AS</p>
        </div>
        <div class="social-media">
            <h3>Síguenos</h3>
            <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
    </div> 
    <p class="footer-bottom">&copy; 2024 Biblioteca Chicas. Todos los derechos reservados.</p>
</footer>

</body>
</html>