-- BASE DE DATOS: estructura y datos iniciales de la tienda de tacones.
CREATE DATABASE IF NOT EXISTS tienda_tacones CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tienda_tacones;

-- Tabla para cuentas de usuarios. El correo no se puede repetir.
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de catálogo: nombre, descripción, precio y ruta de imagen.
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255) NOT NULL
);

-- Tabla de pedidos. Las claves foráneas relacionan usuario y producto.
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    talla VARCHAR(5) NOT NULL,
    cantidad INT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Productos de ejemplo para llenar el catálogo al instalar el proyecto.
INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES
('Tacón negro clásico', 'Un diseño elegante para cualquier ocasión.', 2899.00, 'img/tacon-negro.jpg'),
('Tacón nude', 'Un tono discreto y fácil de combinar.', 3199.00, 'img/tacon-nude.jpg'),
('Tacón de noche', 'Un estilo sofisticado para ocasiones especiales.', 3499.00, 'img/tacon-noche.jpg');
