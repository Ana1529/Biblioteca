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

// Manejo de registro de nuevos usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_usuario'])) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $rol = $_POST['rol'];

    if (!empty($nombre) && !empty($email) && !empty($rol)) {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, telefono, id_roles) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $rol);
        
        if ($stmt->execute()) {
            echo "Usuario registrado correctamente.";
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Manejo de eliminación de usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id_usuario = $_POST['id_usuario'];
    
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    
    if ($stmt->execute()) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar el usuario: " . $stmt->error;
    }

    $stmt->close();
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

// Registrar nuevo préstamo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nuevo_prestamo'])) {
    $id_usuario = $_POST['id_usuario'];
    $id_libro = $_POST['id_libro'];
    $fecha_prestamo = date('Y-m-d');
    $sql = "INSERT INTO prestamos (id_usuario, id_libro, fecha_prestamo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $id_usuario, $id_libro, $fecha_prestamo);
    
    if ($stmt->execute()) {
        echo "Nuevo préstamo registrado exitosamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Devolver libro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['devolver_libro'])) {
    $id_prestamo = $_POST['id_prestamo'];
    $sql = "UPDATE prestamos SET devuelto = TRUE, fecha_devolucion = CURDATE() WHERE id_prestamo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_prestamo);
    
    if ($stmt->execute()) {
        echo "Libro devuelto exitosamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Listar préstamos
$sql_prestamos = "SELECT p.id_prestamo, u.nombre, l.titulo, p.fecha_prestamo, p.fecha_devolucion, p.devuelto 
                  FROM prestamos p 
                  JOIN usuarios u ON p.id_usuario = u.id_usuario 
                  JOIN libros l ON p.id_libro = l.id_libro";
$result_prestamos = $conn->query($sql_prestamos);

// Manejo de devoluciones de libros
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['devolver'])) {
    $id_prestamo = $_POST['id_prestamo'];
    $fecha_devolucion = date('Y-m-d'); // Fecha actual

    $stmt = $conn->prepare("UPDATE prestamos SET devuelto = TRUE, fecha_devolucion = ? WHERE id_prestamo = ?");
    $stmt->bind_param("si", $fecha_devolucion, $id_prestamo);
    
    if ($stmt->execute()) {
        echo "Devolución registrada correctamente.";
    } else {
        echo "Error al registrar la devolución: " . $stmt->error;
    }

    $stmt->close();
}

// Manejo de pago de multas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pagar'])) {
    $id_multa = $_POST['id_multa'];

    $stmt = $conn->prepare("UPDATE multas SET estado = TRUE WHERE id_multa = ?");
    $stmt->bind_param("i", $id_multa);
    
    if ($stmt->execute()) {
        echo "Pago registrado correctamente.";
    } else {
        echo "Error al registrar el pago: " . $stmt->error;
    }

    $stmt->close();
}

// Manejo de la solicitud AJAX
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['title'], $data['author'], $data['year'], $data['genre'])) {
    $titulo = trim($data['title']);
    $autor = trim($data['author']);
    $anio_publicacion = $data['year'];
    $genero = trim($data['genre']);

    $stmt = $conn->prepare("INSERT INTO libros (titulo, autor, anio_publicacion, id_categoria) VALUES (?, ?, ?, ?)");
    // Asegúrate de tener la lógica para el id_categoria si lo necesitas
    $id_categoria = 1; // Cambia esto según tu lógica de categorías

    $stmt->bind_param("ssii", $titulo, $autor, $anio_publicacion, $id_categoria);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Libro registrado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el libro: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos necesarios.']);
}

$conn->close();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="stylesheet" href="CSS/añadir_libro.css">
    <link rel="stylesheet" href="CSS/prestamos.css">
    <link rel="stylesheet" href="CSS/devoluciones.css">
    <link rel="stylesheet" href="CSS/multas.css">

</head>
<body>

    <!-- Encabezado -->
    <header>
        <div class="logo">
            <img src="logo.png" alt="Logo Sistema" class="logo-img">
        </div>
        <div class="user-notification">
            <img src="IMG/usuario.png" alt="Usuario" class="icon" width="1%" height="1%">
            <img src="IMG/noti.png" alt="Notificaciones" class="icon" width="1%" height="1%">
        </div>
    </header>

    <!-- Contenedor Principal -->
    <div class="container">
        
        <!-- Barra lateral  -->
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="#">Préstamos</a></li>
                    <li><a href="#">Libros</a></li>
                    <li><a href="#">Administración</a></li>
                </ul>
            </nav>
        </aside>
        <br>
        <br>

        <!-- Panel de Administración -->
        <section class="main-section">
            <h1>Panel de Administración</h1>
            <div class="grid-container">
            <div class="card" onclick="showSection('usuarios')">Usuarios</div>
            <div class="card" onclick="showSection('libros')">Libros</div>
            <div class="card" onclick="showSection('autor')">Autor</div>
            <div class="card" onclick="showSection('editorial')">Editorial</div>
            <div class="card" onclick="showSection('prestamos')">Préstamos</div>
            <div class="card" onclick="showSection('devoluciones')">Devoluciones</div>
            <div class="card" onclick="showSection('multas')">Multas</div>
            <div class="card" id="add-book-btn" onclick="showSection('añadir_libro')">Añadir Libro</div>
                <!--<div class="card">Usuarios</div>
                <div class="card">Libros</div>
                <div class="card">Autor</div>
                <div class="card">Editorial</div>
                <div class="card">Préstamos</div>
                <div class="card" onclick="showSection('prestamos')">Préstamos</div>
                 Quitar el enlace y solo usar el div con id -->
                <!--<div class="card" id="add-book-btn">Añadir Libro</div>-->
            
        </section>

        <!-- seccion de USUARIOS -->
        <section class="usuarios" style="display: none;">
            <h1>Usuarios Registrados</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener usuarios
                    $sql = "SELECT u.id_usuario, u.nombre, u.apellido, u.email, u.telefono, r.nombre AS rol 
                            FROM usuarios u 
                            JOIN roles r ON u.id_roles = r.id_roles";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($usuario = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$usuario['id_usuario']}</td>
                                    <td>{$usuario['nombre']}</td>
                                    <td>{$usuario['apellido']}</td>
                                    <td>{$usuario['email']}</td>
                                    <td>{$usuario['telefono']}</td>
                                    <td>{$usuario['rol']}</td>
                                    <td>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='id_usuario' value='{$usuario['id_usuario']}'>
                                            <input type='submit' name='eliminar' value='Eliminar' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\");'>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay usuarios registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h2>Añadir Nuevo Usuario</h2>
            <form method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" required>
                
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" required>
                
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" required>
                
                <label for="rol">Rol:</label>
                <select name="rol" required>
                    <?php
                    // Obtener lista de roles para el select
                    $sql_roles = "SELECT id_roles, nombre FROM roles";
                    $result_roles = $conn->query($sql_roles);
                    while ($rol = $result_roles->fetch_assoc()) {
                        echo "<option value='{$rol['id_roles']}'>{$rol['nombre']}</option>";
                    }
                    ?>
                </select>
                
                <input type="submit" name="registrar_usuario" value="Registrar Usuario">
            </form>
        </section>

        
        <!-- seccion de PRESTAMOS -->
        
        <section class="prestamos" style="display: none;">
            <h1>Préstamos Actuales</h1>
            <table border="1">
                <tr>
                    <th>ID Préstamo</th>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                    <th>Devuelto</th>
                    <th>Acciones</th>
                </tr>
                <?php if ($result_prestamos->num_rows > 0) {
                    while ($row = $result_prestamos->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id_prestamo']; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                            <td><?php echo $row['fecha_prestamo']; ?></td>
                            <td><?php echo $row['fecha_devolucion'] ?: 'Pendiente'; ?></td>
                            <td><?php echo $row['devuelto'] ? 'Sí' : 'No'; ?></td>
                            <td>
                                <?php if (!$row['devuelto']) { ?>
                                    <form method="POST">
                                        <input type="hidden" name="id_prestamo" value="<?php echo $row['id_prestamo']; ?>">
                                        <input type="submit" name="devolver_libro" value="Devolver">
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='7'>No hay préstamos registrados.</td></tr>";
                } ?>
            </table>

            <h2>Registrar Nuevo Préstamo</h2>
            <form method="POST">
                <label for="id_usuario">ID Usuario:</label>
                <input type="number" name="id_usuario" required>
                <br>
                <label for="id_libro">ID Libro:</label>
                <input type="number" name="id_libro" required>
                <br>
                <input type="submit" name="nuevo_prestamo" value="Registrar Préstamo">
            </form>
        </section>

        <!--SECCION DE DEVOLUCIONES-->

        <section class="devoluciones" style="display: none;">
            <h1>Devoluciones de Libros</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID Préstamo</th>
                        <th>ID Usuario</th>
                        <th>Nombre Usuario</th>
                        <th>Título del Libro</th>
                        <th>Fecha de Préstamo</th>
                        <th>Fecha de Devolución</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener préstamos activos
                    $sql = "SELECT p.id_prestamo, p.id_usuario, u.nombre, l.titulo, p.fecha_prestamo, p.fecha_devolucion 
                            FROM prestamos p 
                            JOIN usuarios u ON p.id_usuario = u.id_usuario 
                            JOIN libros l ON p.id_libro = l.id_libro 
                            WHERE p.devuelto = FALSE";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($prestamo = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$prestamo['id_prestamo']}</td>
                                    <td>{$prestamo['id_usuario']}</td>
                                    <td>{$prestamo['nombre']}</td>
                                    <td>{$prestamo['titulo']}</td>
                                    <td>{$prestamo['fecha_prestamo']}</td>
                                    <td>{$prestamo['fecha_devolucion']}</td>
                                    <td>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='id_prestamo' value='{$prestamo['id_prestamo']}'>
                                            <input type='submit' name='devolver' value='Registrar Devolución' onclick='return confirm(\"¿Estás seguro de registrar la devolución?\");'>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No hay préstamos activos.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!--SECCION DE MULTAS-->
        <section class="multas" style="display: none;">
            <h1>Multas Registradas</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID Multa</th>
                        <th>ID Usuario</th>
                        <th>Nombre Usuario</th>
                        <th>ID Préstamo</th>
                        <th>Monto</th>
                        <th>Fecha de Multa</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para obtener multas
                    $sql = "SELECT m.id_multa, m.id_usuario, u.nombre, m.id_prestamo, m.monto, m.fecha_multa, m.estado 
                            FROM multas m 
                            JOIN usuarios u ON m.id_usuario = u.id_usuario";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($multa = $result->fetch_assoc()) {
                            $estado = $multa['estado'] ? 'Pagada' : 'No Pagada';
                            echo "<tr>
                                    <td>{$multa['id_multa']}</td>
                                    <td>{$multa['id_usuario']}</td>
                                    <td>{$multa['nombre']}</td>
                                    <td>{$multa['id_prestamo']}</td>
                                    <td>{$multa['monto']}</td>
                                    <td>{$multa['fecha_multa']}</td>
                                    <td>{$estado}</td>
                                    <td>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='id_multa' value='{$multa['id_multa']}'>
                                            <input type='submit' name='pagar' value='Registrar Pago' ".($multa['estado'] ? "disabled" : "")." onclick='return confirm(\"¿Estás seguro de registrar el pago?\");'>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No hay multas registradas.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>


        <!-- Sección Añadir Libro, oculta por defecto -->
        <section class="añadir_libro" id="add-book-section">
            <h2>Administrador de Libros</h2>
            <button id="add-new-book-btn">Añadir Nuevo Libro</button>
            <table border="1" id="books-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Año</th>
                        <th>Género</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="books-list">
                    <!-- Aquí aparecerán los libros -->
                </tbody>
            </table>

            <!-- Formulario Modal para Añadir o Editar un Libro -->
            <div class="modal" id="book-modal" style="display: none;">
                <div class="modal-content">
                    <h3 id="modal-title">Añadir Libro</h3>
                    <form id="book-form">
                        <input type="hidden" id="book-id">
                        <label for="book-title">Título:</label>
                        <input type="text" id="book-title" required>
                        <label for="book-author">Autor:</label>
                        <input type="text" id="book-author" required>
                        <label for="book-year">Año:</label>
                        <input type="number" id="book-year" required min="1900" max="2100">
                        <label for="book-genre">Género:</label>
                        <input type="text" id="book-genre" required>
                        <button type="submit" id="save-book-btn">Guardar</button>
                    </form>
                    <button id="close-modal-btn">Cerrar</button>
                </div>
            </div>
        </section>

        <script>
                document.getElementById('add-new-book-btn').addEventListener('click', function() {
                document.getElementById('book-modal').style.display = 'block';
                document.getElementById('modal-title').textContent = 'Añadir Libro';
                document.getElementById('book-form').reset();
                document.getElementById('book-id').value = '';
            });

            document.getElementById('close-modal-btn').addEventListener('click', function() {
                document.getElementById('book-modal').style.display = 'none';
            });

            document.getElementById('book-form').addEventListener('submit', function(event) {
                event.preventDefault();

                const id = document.getElementById('book-id').value;
                const title = document.getElementById('book-title').value;
                const author = document.getElementById('book-author').value;
                const year = document.getElementById('book-year').value;
                const genre = document.getElementById('book-genre').value;

                const data = {
                    id: id,
                    title: title,
                    author: author,
                    year: year,
                    genre: genre
                };

                fetch('añadir_libro.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    // Aquí podrías agregar el libro a la tabla sin recargar la página
                    // Lógica para añadir libro a la tabla
                    document.getElementById('book-modal').style.display = 'none';
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            });

        </script>
        <!--alterna entre diferentes secciones al hacer clic en los botones correspondientes-->
        
        <script>
            function showSection(section) {
                // Ocultar todas las secciones
                const sections = ['prestamos', 'usuarios', 'libros', 'autor', 'editorial', 'añadir_libro', 'devoluciones', 'multas'];
                sections.forEach(sec => {
                    const element = document.querySelector(`.${sec}`);
                    if (element) {
                        element.style.display = 'none';
                    }
                });

                // Mostrar la sección seleccionada
                const selectedSection = document.querySelector(`.${section}`);
                if (selectedSection) {
                    selectedSection.style.display = 'block';
                }
            }
            
            // Llamar a la función al cargar la página para ocultar todas las secciones inicialmente
            window.onload = function() {
                showSection(''); // Inicialmente, no se muestra ninguna sección
            };
        </script>

    </div>

</body>
</html>
