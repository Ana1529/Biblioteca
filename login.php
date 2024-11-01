<?php    
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'biblioteca');
    
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener datos del formulario
    $email = $_POST['email'];
    $password = $_POST['contra']; // La contraseña enviada por el formulario
    
    // Preparar la consulta SQL para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        // Verificar si la contraseña está encriptada con password_hash o usando SHA-256
        if (password_verify($password, $usuario['contra']) || hash('sha256', $password) === $usuario['contra']) {
            $_SESSION['usuario'] = $usuario; // Almacenar datos del usuario en sesión
            
            // Redirigir según el rol del usuario
            switch ($usuario['id_roles']) { 
                case 1: // Administrador
                    header("Location: administrador.php");
                    exit();
                case 3: // Cliente
                    header("Location: index.php");
                    exit();
                default:
                    $error = "Rol desconocido.";
            }
        } else {
            $error = "Email o contraseña incorrectos.";
        }
    } else {
        $error = "Email o contraseña incorrectos.";
    }
    
    // Cerrar la consulta y la conexión
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
            <form action="login.php" method="POST">
                <center><h1>Iniciar Sesión</h1></center>
                <div class="input-group">
                    <i class='bx bxs-envelope'></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class='bx bxs-lock-alt'></i>
                    <input type="password" name="contra" placeholder="Contraseña" required>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox"> Recordarme</label>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="buttons">
                    <button type="submit">Iniciar Sesión</button>
                    <button type="button" onclick="window.location.href='registro.php'">registrarse</button>
                    <?php if (isset($error)) : ?>
                        <p style="color:red;"><?php echo $error; ?></p>
                    <?php endif; ?>
                </div>
            </form>
            <div class="social-links">
                <a href="#">Follow</a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <script src="java/script.js"></script>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
