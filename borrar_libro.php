<?php

// Definir las variables de conexión
$host = 'localhost'; // Cambia esto si es necesario
$usuario = 'root';   // Cambia esto según tus configuraciones
$contraseña = '';    // Cambia esto si tienes una contraseña
$nombre_bd = 'biblioteca'; // Cambia esto según el nombre de tu base de datos

// Conexión a la base de datos
$conn = new mysqli($host, $usuario, $contraseña, $nombre_bd);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_libro = $_POST['nombre_libro'] ?? null;
    $isbn = $_POST['isbn'] ?? null;

    if ($nombre_libro && $isbn) {
        // Primero, buscar el ID del libro usando el nombre y el ISBN
        $query = $conn->prepare("SELECT id_libro FROM libros WHERE titulo = ? AND isbn = ?");
        $query->bind_param("ss", $nombre_libro, $isbn);
        $query->execute();
        $query->bind_result($id_libro);
        $query->fetch();
        $query->close();

        if ($id_libro) {
            // Eliminar los registros de préstamos relacionados
            $delete_prestamos = $conn->prepare("DELETE FROM prestamos WHERE id_libro = ?");
            $delete_prestamos->bind_param("i", $id_libro);
            $delete_prestamos->execute();
            $delete_prestamos->close();

            // Ahora eliminar el libro
            $stmt = $conn->prepare("DELETE FROM libros WHERE id_libro = ?");
            $stmt->bind_param("i", $id_libro);

            if ($stmt->execute()) {
                echo "Libro borrado exitosamente.";
            } else {
                echo "Error al borrar el libro: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "No se encontró el libro con el nombre y ISBN proporcionados.";
        }
    } else {
        echo "Error: Se requiere el nombre del libro y el ISBN.";
    }
}

$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Libro</title>
    <link rel="stylesheet" href="CSS/borrar_libros.css">
</head>
<body>

    <form action="borrar_libro.php" method="post">
        <label for="nombre_libro">Nombre del Libro a Borrar:</label>
        <input type="text" id="nombre_libro" name="nombre_libro" required>

        <label for="isbn">ISBN del Libro:</label>
        <input type="text" id="isbn" name="isbn" required>

        
        <div class="button-group">
            <input type="submit" value="Eliminar">
            <button type="button" class="btn-volver" onclick="window.location.href='administrador.php'">Volver</button>
        </div>

    </form>

</body>
</html>