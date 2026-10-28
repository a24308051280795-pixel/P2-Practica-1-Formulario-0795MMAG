<?php
// inicia o continua la sesion.
session_start();
// carga la conexion con la base de datos.
require "config.php";
// guarda aqui los mensajes de error.
$error = "";

// si ya hay una sesion, no hace falta crear otra cuenta.
if (isset($_SESSION["usuario_id"])) {
    // lleva al usuario a la tienda.
    header("Location: index.php");
    // detiene la ejecucion despues de redirigir.
    exit;
}

// procesa los datos cuando se envia el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // obtiene el nombre y elimina espacios sobrantes.
    $nombre = trim($_POST["nombre"] ?? "");
    // obtiene el correo y elimina espacios sobrantes.
    $correo = trim($_POST["correo"] ?? "");
    // obtiene la contrasena escrita.
    $password = $_POST["password"] ?? "";

    // revisa que el nombre y el correo sean validos y que la contrasena tenga 8 caracteres.
    if ($nombre === "" || !filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        // avisa que hay que completar los datos correctamente.
        $error = "Completa los datos. La contraseña debe tener al menos 8 caracteres.";
    } else {
        // intenta guardar la nueva cuenta.
        try {
            // prepara la insercion con espacios para los datos recibidos.
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
            // guarda el nombre, el correo y la contrasena protegida con un hash.
            $stmt->execute([$nombre, $correo, password_hash($password, PASSWORD_DEFAULT)]);
            // cambia el identificador de sesion por seguridad.
            session_regenerate_id(true);
            // guarda el ID de la cuenta recien creada en la sesion.
            $_SESSION["usuario_id"] = $pdo->lastInsertId();
            // guarda el nombre para mostrarlo en la tienda.
            $_SESSION["nombre"] = $nombre;
            // envia al usuario a la pagina principal.
            header("Location: index.php");
            // detiene el archivo despues de redirigir.
            exit;
        } catch (PDOException $e) {
            // muestra un mensaje si no se pudo guardar, por ejemplo, por correo duplicado.
            $error = "Ese correo ya está registrado. Intenta iniciar sesión.";
        }
    }
}
?>
<!-- aqui empieza el documento HTML. -->
<!DOCTYPE html>
<!-- indica que el contenido esta en espanol. -->
<html lang="es">
<head>
    <!-- configura los caracteres para que se vean correctamente. -->
    <meta charset="UTF-8">
    <!-- ajusta la pagina al ancho de la pantalla. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- nombre que aparece en la pestana del navegador. -->
    <title>Crear cuenta | YSL Collection</title>
    <!-- carga los estilos de la tienda. -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<!-- encabezado con el logo y los enlaces. -->
<header class="encabezado">
    <!-- el logo lleva a la pagina principal. -->
    <a class="logo" href="index.php">YSL <span>COLLECTION</span></a>
    <!-- permite volver a la tienda o iniciar sesion. -->
    <nav><a href="index.php">Volver a la tienda</a><a href="login.php">Iniciar sesión</a></nav>
</header>
<!-- area principal de registro. -->
<main class="formulario-contenedor">
    <!-- envia los datos para crear una cuenta en esta misma pagina. -->
    <form class="formulario" method="post">
        <!-- texto decorativo del formulario. -->
        <p class="mini-titulo">BIENVENIDA</p>
        <!-- titulo del formulario. -->
        <h1>Crear una cuenta</h1>
        <!-- explica para que sirve la cuenta. -->
        <p>Regístrate para realizar un pedido.</p>
        <!-- si hubo un error, lo muestra escapando el texto. -->
        <?php if ($error): ?><p class="mensaje-error"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p><?php endif; ?>
        <!-- etiqueta del campo para el nombre. -->
        <label for="nombre">Nombre completo</label>
        <!-- campo obligatorio para el nombre, con limite de caracteres. -->
        <input id="nombre" type="text" name="nombre" required maxlength="100" autocomplete="name">
        <!-- etiqueta del campo para el correo. -->
        <label for="correo">Correo electrónico</label>
        <!-- campo obligatorio para el correo, con limite de caracteres. -->
        <input id="correo" type="email" name="correo" required maxlength="150" autocomplete="email">
        <!-- etiqueta que indica la longitud minima de contrasena. -->
        <label for="password">Contraseña (mínimo 8 caracteres)</label>
        <!-- campo obligatorio que oculta la contrasena al escribir. -->
        <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
        <!-- envia los datos para registrar la cuenta. -->
        <button class="boton" type="submit">Registrarme</button>
        <!-- enlace para iniciar sesion si ya tiene cuenta. -->
        <p class="nota">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </form>
</main>
<!-- cierra el contenido visible de la pagina. -->
</body>
<!-- termina el documento HTML. -->
</html>
