<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'biblioteca');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

session_start(); 
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['usuario']['id_roles'] != 1) {
    die("Acceso denegado. Solo los administradores pueden acceder a esta página.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <!-- Barra lateral  -->
        <aside class="sidebar">
            <nav>
                <a href="Pagina_principal.php"><center><h1>Mi Biblioteca</h1></center></a>
                
                <ul>
                    <li><a href="gestionar_prestamos.php">Préstamos</a></li>
                    <li><a href="libros_por_categoria.php">Libros</a></li>
                    <li><a href="usuarios_registrados.php">Personas Registradas</a></li>
                </ul>
            </nav>
            <br>
            <br>
            <br>
            <br><br><br><br>
            <img src="imagenes/logoazul.jpg" alt="Usuario" style="width: 200px; height: 200px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); object-fit: cover;">
        </aside>
        <div class="main-content">
            <!-- Encabezado -->
            <header>
                <div class="user-notification">
                    <span>Administrador</span>
                    <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
                </div>
            </header>

            <!-- Panel de Administración -->
            <div class="main-section"> 
                <h1>Dashboard</h1>
                <br>
                <br>
                <div class="grid-container">
                    <a href="registros_admin.php">
                        <div class="card">Gestion de Usuarios</div>
                    </a>
                    <a href="gestion_libros.php">
                        <div class="card">Gestion de Libros</div>
                    </a>
                    
                    <a href="categorias.php">
                        <div class="card">Categorias</div>
                    </a>
                    <a href="gestionar_prestamos.php">
                        <div class="card">Gestion de Prestamos</div>
                    </a>
                    <a href="devoluciones.php">
                        <div class="card">Devoluciones</div>
                    </a>
                    
                    <a href="añadir_libro.php">
                        <div class="card">Añadir Libro</div>
                    </a>
                    <a href="borrar_libro.php">
                        <div class="card">Borrar Libros</div>
                    </a>
                </div>
                <br><br>
                <div class="prestamos">
                    <h1>Pedidos de Préstamos</h1>
                    
                    <?php
                    // Consultar los pedidos de préstamos
                    $query_pedidos = "SELECT p.id_pedido, u.id_usuario, u.nombre AS nombre_usuario, 
                                      b.titulo AS titulo_libro, b.anio_publicacion, b.autor, 
                                      p.fecha_pedido
                                      FROM pedidos p
                                      JOIN usuarios u ON p.id_usuario = u.id_usuario
                                      JOIN libros b ON p.id_libro = b.id_libro"; 

                    $resultado_pedidos = $conn->query($query_pedidos);

                    if ($resultado_pedidos === FALSE) {
                        die("Error en la consulta: " . $conn->error);
                    }
                    ?>

                    <table>
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>ID Usuario</th>
                                <th>Nombre Usuario</th>
                                <th>Título del Libro</th>
                                <th>Año de Publicación</th>
                                <th>Autor</th>
                                <th>Fecha de Solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($pedido = $resultado_pedidos->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $pedido['id_pedido']; ?></td>
                                    <td><?php echo htmlspecialchars($pedido['id_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['titulo_libro']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['anio_publicacion']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['autor']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
