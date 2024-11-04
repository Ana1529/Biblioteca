<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Conectar a la base de datos
$servidor = "localhost";
$usuario = "root"; 
$contrasena = ""; // Asegúrate de que no haya espacios
$nombre_bd = 'biblioteca';

$conexion = new mysqli($servidor, $usuario, $contrasena, $nombre_bd);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Variables para mensajes
$mensaje = "";
$libros = [];

// Consultar libros existentes con sus categorías
$query = "SELECT l.*, c.nombre AS categoria
          FROM libros l
          JOIN categorias c ON l.id_categoria = c.id_categoria";
$resultado = $conexion->query($query);

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $libros[] = $fila;
    }
} else {
    echo "Error al obtener los libros: " . $conexion->error;
}

// Consultar categorías para el formulario
$query_categorias = "SELECT * FROM categorias";
$resultado_categorias = $conexion->query($query_categorias);

$categorias = [];
if ($resultado_categorias) {
    while ($fila = $resultado_categorias->fetch_assoc()) {
        $categorias[] = $fila;
    }
} else {
    echo "Error al obtener categorías: " . $conexion->error;
}

// Agregar un libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_libro'])) {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $editorial = $_POST['editorial'];
    $anio_publicacion = $_POST['anio_publicacion'];
    $isbn = $_POST['isbn'];
    $id_categoria = $_POST['id_categoria'];

    // Manejo de la imagen
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $ruta_imagen = 'uploads/' . basename($nombre_imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
        $imagen = $ruta_imagen; // Guarda la ruta de la imagen
    }

    $sql = "INSERT INTO libros (titulo, autor, editorial, anio_publicacion, isbn, imagen, id_categoria) 
            VALUES ('$titulo', '$autor', '$editorial', '$anio_publicacion', '$isbn', '$imagen', '$id_categoria')";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Libro agregado correctamente.";
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}

// Editar un libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_libro'])) {
    $id_libro = $_POST['id_libro'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $editorial = $_POST['editorial'];
    $anio_publicacion = $_POST['anio_publicacion'];
    $isbn = $_POST['isbn'];
    $id_categoria = $_POST['id_categoria'];

    // Manejo de la imagen
    $imagen = $_POST['imagen_actual'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $nombre_imagen = $_FILES['imagen']['name'];
        $ruta_imagen = 'uploads/' . basename($nombre_imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
        $imagen = $ruta_imagen; // Guarda la nueva ruta de la imagen
    }

    $sql = "UPDATE libros SET 
            titulo='$titulo', autor='$autor', editorial='$editorial', 
            anio_publicacion='$anio_publicacion', isbn='$isbn', imagen='$imagen', id_categoria='$id_categoria' 
            WHERE id_libro='$id_libro'";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Libro editado correctamente.";
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}

// Eliminar un libro
if (isset($_GET['eliminar'])) {
    $id_libro = $_GET['eliminar'];
    $sql = "DELETE FROM libros WHERE id_libro='$id_libro'";

    if ($conexion->query($sql) === TRUE) {
        $mensaje = "Libro eliminado correctamente.";
    } else {
        $mensaje = "Error: " . $conexion->error;
    }
}

// Manejo de la edición
$libro_editar = null;
if (isset($_GET['editar'])) {
    $id_libro = $_GET['editar'];
    $sql = "SELECT * FROM libros WHERE id_libro='$id_libro'";
    $resultado = $conexion->query($sql);
    $libro_editar = $resultado->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros</title>
    <link rel="stylesheet" href="CSS/gestion_libros.css"> 
</head>
<body>
    <header>
        
        <h1>Gestión de Libros</h1>
            <div class="user-notification">
                <span>Administrador</span>
                <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
            </div>
        
    </header>

    <!-- Mostrar el mensaje de estado aquí -->
    <?php if ($mensaje != ""): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
        <br>
        <br>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_libro" value="<?php echo $libro_editar['id_libro'] ?? ''; ?>">
        
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo $libro_editar['titulo'] ?? ''; ?>" required>

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" value="<?php echo $libro_editar['autor'] ?? ''; ?>" required>

        <label for="editorial">Editorial:</label>
        <input type="text" id="editorial" name="editorial" value="<?php echo $libro_editar['editorial'] ?? ''; ?>">

        <label for="anio_publicacion">Año de Publicación:</label>
        <input type="number" id="anio_publicacion" name="anio_publicacion" value="<?php echo $libro_editar['anio_publicacion'] ?? ''; ?>" required>

        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" value="<?php echo $libro_editar['isbn'] ?? ''; ?>" required>

        <label for="id_categoria">Categoría:</label>
        <select id="id_categoria" name="id_categoria" required>
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>"
                    <?php echo (isset($libro_editar) && $libro_editar['id_categoria'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        <input type="hidden" name="imagen_actual" value="<?php echo $libro_editar['imagen'] ?? ''; ?>">

        <div class="button-group">
            <button type="submit" name="<?php echo isset($libro_editar) ? 'editar_libro' : 'agregar_libro'; ?>">
                <?php echo isset($libro_editar) ? 'Actualizar Libro' : 'Agregar Libro'; ?>
            </button>
            <button type="button" onclick="window.location.href='administrador.php'">Volver</button>
        </div>
        
    </form>

    <h2>Lista de Libros</h2>
    <table>
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Editorial</th>
            <th>Año de Publicación</th>
            <th>ISBN</th>
            <th>Categoría</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($libros as $libro): ?>
        <tr>
            <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
            <td><?php echo htmlspecialchars($libro['autor']); ?></td>
            <td><?php echo htmlspecialchars($libro['editorial']); ?></td>
            <td><?php echo htmlspecialchars($libro['anio_publicacion']); ?></td>
            <td><?php echo htmlspecialchars($libro['isbn']); ?></td>
            <td><?php echo htmlspecialchars($libro['categoria']); ?></td>
            <td>
                <?php if ($libro['imagen']): ?>
                    <img src="<?php echo htmlspecialchars($libro['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($libro['titulo']); ?>" style="width: 100px; height: auto;">
                <?php else: ?>
                    No disponible
                <?php endif; ?>
            </td>
            <td>
                <a href="?editar=<?php echo $libro['id_libro']; ?>">Editar</a>
                <a href="?eliminar=<?php echo $libro['id_libro']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
