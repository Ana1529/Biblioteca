<?php
// Inicializar mensajes
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos
    $host = 'localhost';
    $usuario = 'root'; // Cambia esto según tus configuraciones
    $contraseña = '';
    $nombre_bd = 'biblioteca';

    $conn = new mysqli($host, $usuario, $contraseña, $nombre_bd);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recoger datos del formulario
    $titulo = $_POST['titulo'] ?? null;
    $autor = $_POST['autor'] ?? null;
    $editorial = $_POST['editorial'] ?? null;
    $anio_publicacion = $_POST['anio_publicacion'] ?? null;
    $isbn = $_POST['isbn'] ?? null;
    $id_categoria = $_POST['id_categoria'] ?? null;
    $imagen = $_FILES['imagen'] ?? null;

    // Verificar que la categoría existe
    $categoria_query = $conn->prepare("SELECT COUNT(*) FROM categorias WHERE id_categoria = ?");
    $categoria_query->bind_param("i", $id_categoria);
    $categoria_query->execute();
    $categoria_query->bind_result($count);
    $categoria_query->fetch();
    $categoria_query->close();

    if ($count === 0) {
        $message = "Error: La categoría seleccionada no existe.";
        $messageType = "error"; // Tipo de mensaje
    } else {
        // Comprobar si el ISBN ya existe
        $isbn_query = $conn->prepare("SELECT COUNT(*) FROM libros WHERE isbn = ?");
        $isbn_query->bind_param("s", $isbn);
        $isbn_query->execute();
        $isbn_query->bind_result($isbn_count);
        $isbn_query->fetch();
        $isbn_query->close();

        if ($isbn_count > 0) {
            $message = "Error: El ISBN ya está registrado.";
            $messageType = "error"; // Tipo de mensaje
        } else {
            // Manejo de la imagen
            if ($imagen['error'] === UPLOAD_ERR_OK) {
                $nombre_imagen = basename($imagen['name']);
                $ruta_imagen = 'uploads/' . $nombre_imagen; // Ruta de la imagen

                // Mover la imagen a la carpeta deseada
                if (move_uploaded_file($imagen['tmp_name'], $ruta_imagen)) {
                    // Preparar y ejecutar la consulta para insertar el libro
                    $stmt = $conn->prepare("INSERT INTO libros (titulo, autor, editorial, anio_publicacion, isbn, id_categoria, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssiss", $titulo, $autor, $editorial, $anio_publicacion, $isbn, $id_categoria, $ruta_imagen);

                    if ($stmt->execute()) {
                        $message = "Libro añadido exitosamente.";
                        $messageType = "success"; // Tipo de mensaje
                    } else {
                        $message = "Error al añadir el libro: " . $stmt->error;
                        $messageType = "error"; // Tipo de mensaje
                    }

                    $stmt->close();
                } else {
                    $message = "Error al mover la imagen.";
                    $messageType = "error"; // Tipo de mensaje
                }
            } else {
                $message = "Error en la subida de la imagen.";
                $messageType = "error"; // Tipo de mensaje
            }
        }
    }

    // Cerrar la conexión al final
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Libro</title>
    <link rel="stylesheet" href="CSS/libros_añadidos.css">
</head>
<body>
    <div class="container">

        <header>
            <h1>Añadir Libro</h1>
            <div class="user-notification">
                <span>Administrador</span>
                <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
            </div>
        </header>

        <!-- Mostrar mensaje -->
        <div class="message <?= $messageType; ?>" style="display: <?= !empty($message) ? 'block' : 'none'; ?>;">
            <?= $message; ?>
        </div>

        <center>
        <form action="añadir_libro.php" method="post" enctype="multipart/form-data">
            <center>
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" required>

            <label for="editorial">Editorial:</label>
            <input type="text" id="editorial" name="editorial">

            <label for="anio_publicacion">Año de Publicación:</label>
            <input type="number" id="anio_publicacion" name="anio_publicacion" required>

            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required>

            <label for="id_categoria">Categoría:</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">Selecciona una categoría</option>
                <option value="1">Ficción</option>
                <option value="2">No Ficción</option>
                <option value="3">Ciencia</option>
                <option value="4">Literatura Infantil</option>
                <option value="5">Juvenil</option>
                <option value="6">Fantasia</option>
                <option value="7">Misterio</option>
                <option value="8">Romance</option>
                <option value="9">Autoayuda</option>
                <option value="11">Clásico</option>
                <option value="12">Ciencia Ficción</option>
                <option value="13">Suspenso</option>
                <option value="14">Novela</option>
                <option value="15">Historia</option>
            </select>
            </center>
            <label for="imagen">Imagen del Libro:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>
 
            <div class="button-group">
                <input type="submit" value="Añadir Libro">
                <button type="button" onclick="window.location.href='administrador.php'">Volver</button>
            </div>

        </form>
        </center>
    </div>
</body>
</html>
