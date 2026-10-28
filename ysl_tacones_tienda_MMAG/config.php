<?php
// datos de conexion local para MySQL en XAMPP.
// direccion del servidor de base de datos.
$host = "localhost";
// nombre de la base de datos de la tienda.
$dbname = "tienda_tacones";
// usuario de MySQL.
$user = "root";
// contrasena de MySQL, vacia en la configuracion local usada.
$password = "";

// intenta crear la conexion con la base de datos.
try {
    // guarda la conexion PDO en una variable para usarla en otros archivos.
    $pdo = new PDO(
        // configura el servidor, la base de datos y la codificacion.
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        // envia el usuario configurado.
        $user,
        // envia la contrasena configurada.
        $password,
        // define como PDO manejara consultas y resultados.
        [
            // hace que PDO lance una excepcion si ocurre un error.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // devuelve los resultados como arreglos asociativos.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // muestra una indicacion si no se pudo conectar.
    die("No se pudo conectar con la base de datos. Revisa la configuración de MySQL y que hayas importado tienda.sql.");
}
?>
