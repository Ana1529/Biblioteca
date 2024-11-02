drop database biblioteca;
create database biblioteca;
use biblioteca;

create table roles (
id_roles int primary key,
describcion varchar (255) 

);

CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(26) NOT NULL,
    apellido varchar (20),
    email VARCHAR(50),
    contra varchar (65),
    telefono VARCHAR(20),
    id_roles INT,
    foreign key (id_roles) references roles(id_roles)
    on update cascade on delete set null
);

CREATE TABLE categorias (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE libros (
    id_libro INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    editorial VARCHAR(100),
    anio_publicacion YEAR,
    isbn VARCHAR(20) UNIQUE,
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

CREATE TABLE prestamos (
    id_prestamo INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_libro INT,
    titulo varchar(45), 
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion DATE,
    devuelto BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_libro) REFERENCES libros(id_libro)
);

CREATE TABLE devoluciones (
    id_devolucion INT PRIMARY KEY AUTO_INCREMENT,
    id_prestamo INT,
    fecha_devolucion DATE NOT NULL,
    estado ENUM('devuelto', 'deteriorado', 'perdido') NOT NULL,
    FOREIGN KEY (id_prestamo) REFERENCES prestamos(id_prestamo)
);

CREATE TABLE perdidas (
    id_perdida INT PRIMARY KEY AUTO_INCREMENT,
    id_prestamo INT,
    fecha_perdida DATE NOT NULL,
    monto_compensacion DECIMAL(5, 2) NOT NULL,
    estado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_prestamo) REFERENCES prestamos(id_prestamo)
);
CREATE TABLE multas (
    id_multa INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_prestamo INT NOT NULL,
    monto DECIMAL(5, 2) NOT NULL,
    fecha_multa DATE NOT NULL,
    estado BOOLEAN DEFAULT FALSE, -- FALSE para no pagada, TRUE para pagada
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_prestamo) REFERENCES prestamos(id_prestamo)
);
CREATE TABLE comentarios (
    id_comentario INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    id_libro INT NOT NULL,
    comentario TEXT NOT NULL,
    puntuacion INT CHECK (puntuacion >= 1 AND puntuacion <= 5),
    fecha_comentario DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_libro) REFERENCES libros(id_libro)
);
ALTER TABLE usuarios MODIFY COLUMN contra VARCHAR(64);


insert into roles values(1, 'Administrador'),(2, 'Socio'),(3, 'Cliente');
INSERT INTO usuarios (nombre, apellido, email, contra, telefono, id_roles) 
VALUES ('Ana Romina Liz', 'liz', 'anarominaliz123@gmail.com', SHA2('amistad12318', 256), '123456789', 2),
('Ana Romina Liz', 'liz', 'Administrador@gmail.com', SHA2('admin123', 256), '123456789', 1),
('Ana Romina Liz', 'liz', 'clientes@gmail.com', SHA2('cliente1', 256), '123456789', 3);
SELECT * FROM usuarios WHERE email = 'clientes@gmail.com';

select * from usuarios where email = "anatokio29@gmail.com";

select * from usuarios;
select * from roles;