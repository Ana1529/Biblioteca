<?php
// Conexión a la base de datos
$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$nombre_bd = 'biblioteca';

$conn = new mysqli($host, $usuario, $contraseña, $nombre_bd);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para generar una contraseña aleatoria
function generarContrasena($longitud = 8) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle(str_repeat($caracteres, $longitud)), 0, $longitud);
}

// Procesar el formulario de registro si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $correo = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $contrasena = generarContrasena();
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $id_roles = $_POST['id_roles'] ?? null;

    // Guardar el usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, contra, telefono, id_roles) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $apellido, $correo, $contrasena_hash, $telefono, $id_roles);

    if ($stmt->execute()) {
        // Enviar un correo electrónico al usuario con la contraseña
        $asunto = "Bienvenido a Mi Biblioteca";
        $mensaje = "Hola $nombre,\n\nTu cuenta ha sido creada. Tu contraseña temporal es: $contrasena\n\nPor favor, inicia sesión y cámbiala inmediatamente.\n\nSaludos.";
        $headers = "From: no-reply@biblioteca.com";

        mail($correo, $asunto, $mensaje, $headers);
        echo "<div class='success-message'>Usuario registrado exitosamente. Se ha enviado un correo con la contraseña temporal.</div>";
    } else {
        echo "<div class='error-message'>Error al registrar el usuario: " . $stmt->error . "</div>";
    }
}

// Procesar el formulario de eliminación si se envía
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id_usuario = $_POST['id_usuario'];

    if (!empty($id_usuario)) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id_usuario' => $id_usuario]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID de usuario inválido.']);
        exit;
    }
}

// Consultar usuarios
$result = $conn->query("SELECT id_usuario, nombre, apellido, email, telefono FROM usuarios");
$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Usuarios</title>
    <link rel="stylesheet" href="css/registros_admin.css">
    <script>
        function eliminarUsuario(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                fetch('registro.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'accion=eliminar&id_usuario=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuario eliminado exitosamente.');
                        // Eliminar la fila de la tabla
                        document.getElementById('usuario-' + id).remove();
                    } else {
                        alert('Error al eliminar el usuario: ' + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestión de Usuarios</h1>
            <div class="user-notification">
                <span>Administrador</span>
                <img src="imagenes/logoazul.jpg" alt="Usuario" class="icon" width="30" height="30">
            </div>
        </header>
        <center>
            <div class="form-container">
                <form id="form-registro" action="registro.php" method="post">
                    <h2>Registrar Usuario</h2>
                    <input type="hidden" name="accion" value="registrar">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" required>

                    <label for="email">Correo:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" required>

                    <label for="id_roles">Rol:</label>
                    <select id="id_roles" name="id_roles" required>
                        <option value="">Seleccionar rol</option>
                        <option value="1">Administrador</option>
                        <option value="2">Usuario</option>
                    </select>

                    <input type="submit" value="Registrar">
                </form>
                <button type="button" onclick="window.location.href='administrador.php'">Volver</button>
                <h2>Usuarios Registrados</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr id="usuario-<?php echo $usuario['id_usuario']; ?>">
                            <td><?php echo $usuario['id_usuario']; ?></td>
                            <td><?php echo $usuario['nombre']; ?></td>
                            <td><?php echo $usuario['apellido']; ?></td>
                            <td><?php echo $usuario['email']; ?></td>
                            <td><?php echo $usuario['telefono']; ?></td>
                            <td>
                                <button onclick="eliminarUsuario(<?php echo $usuario['id_usuario']; ?>)">Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            
        </center>
    </div>
</body>
</html>

<?php
$conn->close();
?>
