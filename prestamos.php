<?php
$host = 'localhost';
$usuario = 'root'; // Usuario de XAMPP (comúnmente 'root')
$contraseña = ''; // Contraseña, suele estar vacía en XAMPP
$nombre_base_datos = 'biblioteca'; // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Variable para almacenar mensajes de estado
$mensaje = "";
$mostrarEncuesta = false; // Inicializa en false

// Registrar préstamo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_prestamo'])) {
    $nombre_usuario = $_POST['userName'];
    $titulo_libro = $_POST['bookTitle'];
    
    $sql = "INSERT INTO prestamos (nombre_usuario, titulo_libro, estado_libro) VALUES ('$nombre_usuario', '$titulo_libro', 'Prestado')";
    
    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Préstamo registrado correctamente";
        // No se muestra la encuesta aquí
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}

// Devolver libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['devolver_libro'])) {
    $id = $_POST['loanId'];
    
    $sql = "UPDATE prestamos SET estado_libro = 'Devuelto' WHERE id = $id";
    
    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Libro devuelto correctamente";
        $mostrarEncuesta = true; // Muestra el modal de encuesta solo al devolver
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Préstamos de Libros</title>
    <link rel="stylesheet" href="prestamos.css">
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
                <li><a href="libros.php">Libros favoritos</a></li>
            </ul>
        </nav>
    </header>

    <h1>Registro de Préstamos de Libros</h1>

    <!-- Mostrar el mensaje de estado aquí -->
    <?php if ($mensaje != ""): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
    
    <form method="post">
        <label for="userName">Nombre del Usuario:</label>
        <input type="text" id="userName" name="userName" required>
        
        <label for="bookTitle">Título del Libro:</label>
        <input type="text" id="bookTitle" name="bookTitle" required>
        
        <button type="submit" name="registrar_prestamo">Registrar Préstamo</button>
    </form>
    
    <h2>Préstamos Activos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Libro</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        
        <?php
        $sql = "SELECT * FROM prestamos WHERE estado_libro = 'Prestado'";
        $resultado = $conexion->query($sql);
        
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila['id'] . "</td>";
                echo "<td>" . $fila['nombre_usuario'] . "</td>";
                echo "<td>" . $fila['titulo_libro'] . "</td>";
                echo "<td>" . $fila['estado_libro'] . "</td>";
                echo "<td>
                    <form method='post'>
                        <input type='hidden' name='loanId' value='" . $fila['id'] . "'>
                        <button type='submit' name='devolver_libro'>Devolver</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay préstamos activos</td></tr>";
        }
        ?>
    </table>

    <!-- Modal de Encuesta -->
    <div id="modalEncuesta" class="modal" <?php if ($mostrarEncuesta) echo 'style="display:block;"'; else echo 'style="display:none;"'; ?>>
        <div class="modal-content">
            <span class="close" onclick="cerrarEncuesta()">&times;</span>
            <h2>Encuesta de Satisfacción</h2>
            <form id="encuestaForm">
                <label>¿Cómo calificarías el servicio?</label>
                <select name="calificacion_servicio" required>
                    <option value="excelente">Excelente</option>
                    <option value="bueno">Bueno</option>
                    <option value="regular">Regular</option>
                    <option value="malo">Malo</option>
                </select>
                <label>¿Cómo calificarías el libro?</label>
                <select name="calificacion_libro" required>
                    <option value="excelente">Excelente</option>
                    <option value="bueno">Bueno</option>
                    <option value="regular">Regular</option>
                    <option value="malo">Malo</option>
                </select>
                <label>¿En qué condiciones encontraste el libro?</label>
                <select name="condiciones_libro" required>
                    <option value="excelente">Excelente</option>
                    <option value="regular">Regular</option>
                    <option value="malo">Malo</option>
                </select>
                <label>¿Comentarios adicionales?</label>
                <textarea name="comentarios" rows="4" placeholder="Escribe tus comentarios aquí..."></textarea>
                <button type="submit">Enviar Encuesta</button>
            </form>
        </div>
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

    <script>
        // Función para cerrar el modal
        function cerrarEncuesta() {
            document.getElementById("modalEncuesta").style.display = "none";
        }
    </script>

</body>
</html>

<?php
// Cerrar conexión
$conexion->close();
?>
