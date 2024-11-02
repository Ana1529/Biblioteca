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

// Función para manejar errores
function handleError($stmt) {
    return "Error: " . $stmt->error;
}

$conn->close();
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
                <center><h1>Mi Biblioteca</h1></center>
                <ul>
                    <li><a href="#">Préstamos</a></li>
                    <li><a href="#">Libros</a></li>
                    <li><a href="#">Personas</a></li>
                </ul>
            </nav>
        </aside>
        <div class="main-content">
            <!-- Encabezado -->
            <header>
                
                <div class="user-notification">
                    <span>Administrador</span>
                    <img src="IMG/usuario.png" alt="Usuario" class="icon" width="30" height="30">
                    
                </div>
            </header>

            <!-- Panel de Administración -->
            <div class="main-section"> 
                <h1>Dashboard</h1>
                <br>
                <br>
                <div class="grid-container">
                    <a href="usuarios_registrados.php">
                        <div class="card">usuarios</div>
                    </a>
                    <div class="card" onclick="showSection('libros')">Libros</div>
                    <div class="card" onclick="showSection('autor')">Autor</div>
                    <div class="card" onclick="showSection('editorial')">Editorial</div>
                    <a href="prestamos.php">
                        <div class="card">Prestamos</div>
                    </a>
                    <div class="card" onclick="showSection('devoluciones')">Devoluciones</div>
                    <div class="card" onclick="showSection('multas')">Multas</div>
                    <a href="añadir_libro.php">
                        <div class="card">Añadir Libro</div>
                    </a>
                    <a href="borrar_libro.php">
                        <div class="card">Borrar Libros</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
