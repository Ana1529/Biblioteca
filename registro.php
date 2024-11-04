<?php
session_start();
include 'conexion.php'; // Asegúrate de tener un archivo de conexión correcto

$error = ''; // Variable para almacenar mensajes de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitización de entradas
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $email = $conn->real_escape_string($_POST['email']);
    $contra = password_hash($_POST['contra'], PASSWORD_BCRYPT); // Encriptar la contraseña

    // Cliente es el rol por defecto con id_roles = 3
    $rol = 3; // Cliente

    // Verificación de si el correo ya existe
    $checkEmail = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el correo ya existe, muestra un mensaje de error
        $error = "Este correo ya está registrado.";
    } else {
        // Insertar nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, apellido, telefono, email, contra, id_roles) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $apellido, $telefono, $email, $contra, $rol);

        if ($stmt->execute()) {
            // Guardar el nombre y el rol en la sesión para el perfil de cliente
            $_SESSION['usuario'] = ['nombre' => $nombre, 'email' => $email, 'id_roles' => $rol];
            
            // Redirigir al perfil de cliente después del registro
            header("Location: index.php");
            exit();
        } else {
            $error = "Error al registrar el usuario: " . $conn->error;
        }
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div class="welcome-section">
        <img src="img/7.jpeg" alt="Bienvenido" class="welcome-image">
        </div>
        <div class="login-section">
            <center><h1>Registro</h1></center>
            <div class="right-section">
                <form action="registro.php" method="POST">
                    <div class="form-container">
                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" name="nombre" placeholder="Nombre" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" name="apellido" placeholder="Apellido" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-phone'></i>
                            <input type="tel" name="telefono" placeholder="Teléfono" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-envelope'></i>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="contra" placeholder="Contraseña" required>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">Acepto los términos y condiciones</label>
                        </div>
                        <?php if ($error): ?>
                            <p style="color:red;"><?php echo $error; ?></p>
                        <?php endif; ?>
                        <div class="buttons">
                            <button type="button" onclick="window.location.href='Pagina_principal.php'">Registrar</button>
                            <button type="button" onclick="window.location.href='login.php'">Iniciar sesión</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="java/script.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
