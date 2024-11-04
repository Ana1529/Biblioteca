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

// Consultar libros agrupados por categoría
$query = "SELECT c.nombre AS categoria, l.titulo, l.autor, l.editorial, l.anio_publicacion, l.imagen
          FROM libros l
          JOIN categorias c ON l.id_categoria = c.id_categoria
          ORDER BY c.nombre, l.titulo";
$resultado = $conexion->query($query);

$libros_por_categoria = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $libros_por_categoria[$fila['categoria']][] = $fila;
    }
} else {
    echo "Error al obtener los libros: " . $conexion->error;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros por Categoría</title>
    <link rel="stylesheet" href="CSS/libros_por_categoria.css">
</head>
<body>
    <div class="wrapper">

        <!-- Barra lateral  -->
        <aside class="sidebar">
            <nav>
                <center><h1>Mi Biblioteca</h1></center>
                <ul>
                    <li><a href="gestionar_prestamos.php">Préstamos</a></li>
                    <li><a href="libros_por_categoria.php">Libros</a></li>
                    <li><a href="usuarios_registrados.php">Personas Registradas</a></li>
                    <br>
                    <li>
                            <a href="administrador.php">
                                <img src="img/retorno.png" alt="Volver" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">
                                Volver
                            </a>
                    </li>
                </ul>
            </nav>
            <br>
            <br>
            <br>
            <br><br><br><br>
            <img src="imagenes/logoazul.jpg" alt="Usuario" style="width: 200px; height: 200px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); object-fit: cover;">
        </aside>

        <div class="container">
            <header>
            
                <h1>Libros por Categorias</h1>
                <div class="user-notification">
                    <span>Administrador</span>
                    <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
                </div>
            
            </header>
            <?php foreach ($libros_por_categoria as $categoria => $libros): ?>
                <h2><?php echo htmlspecialchars($categoria); ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Año de Publicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($libro['imagen'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($libro['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($libro['titulo']); ?>" width="100">
                                    <?php else: ?>
                                        <p>No disponible</p>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                                <td><?php echo htmlspecialchars($libro['editorial']); ?></td>
                                <td><?php echo htmlspecialchars($libro['anio_publicacion']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
