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
    echo "Acceso denegado. Solo los administradores pueden acceder a esta página.";
    exit();
}

// Añadir un libro
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $categoria = $_POST['categoria'];

    // Verificar que no haya campos vacíos
    if (!empty($titulo) && !empty($autor) && !empty($categoria)) {
        $stmt = $conn->prepare("INSERT INTO libros (titulo, autor, id_categoria) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titulo, $autor, $categoria);
        
        if ($stmt->execute()) {
            $message = "Libro añadido correctamente.";
        } else {
            $message = "Error al añadir el libro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Todos los campos son obligatorios.";
    }
}

// Obtener lista de usuarios
$sql = "SELECT id_usuario, nombre, email FROM usuarios";
$result = $conn->query($sql);

// Obtener lista de categorías
$sql_categorias = "SELECT id_categoria, nombre FROM categorias";
$result_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Administrador</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de tener un CSS atractivo -->
</head>
<body>
    <!---->
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?> (Administrador)</h1>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Usuarios Registrados:</h2>
    <ul>
        <?php while ($usuario = $result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($usuario['nombre']) . " - " . htmlspecialchars($usuario['email']); ?></li>
        <?php endwhile; ?>
    </ul>

    <h2>Añadir un nuevo libro:</h2>
    <form method="post">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
        
        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" required>
        
        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria" required>
            <?php while ($categoria = $result_categorias->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit">Añadir Libro</button>
    </form>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>

<?php
$conn->close();
?>
