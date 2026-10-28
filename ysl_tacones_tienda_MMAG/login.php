<?php
// inicia o continua la sesion.
session_start();
// carga la conexion con la base de datos.
require "config.php";
// guarda aqui los mensajes de error.
$error = "";

// si ya hay una sesion, no hace falta volver a iniciar sesion.
if (isset($_SESSION["usuario_id"])) {
    // lleva al usuario a la tienda.
    header("Location: index.php");
    // detiene la ejecucion despues de redirigir.
    exit;
}

// procesa los datos cuando se envia el formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // obtiene el correo y elimina espacios sobrantes.
    $correo = trim($_POST["correo"] ?? "");
    // obtiene la contrasena escrita.
    $password = $_POST["password"] ?? "";

    // prepara la busqueda de la cuenta usando el correo.
    $stmt = $pdo->prepare("SELECT id, nombre, password FROM usuarios WHERE correo = ?");
    // ejecuta la busqueda con el correo recibido.
    $stmt->execute([$correo]);
    // guarda los datos de la cuenta encontrada.
    $usuario = $stmt->fetch();

    // comprueba que exista la cuenta y que la contrasena coincida con su hash.
    if ($usuario && password_verify($password, $usuario["password"])) {
        // cambia el identificador de sesion por seguridad.
        session_regenerate_id(true);
        // guarda el ID del usuario en la sesion.
        $_SESSION["usuario_id"] = $usuario["id"];
        // guarda el nombre para mostrarlo en la tienda.
        $_SESSION["nombre"] = $usuario["nombre"];
        // envia al usuario a la pagina principal.
        header("Location: index.php");
        // detiene el archivo despues de redirigir.
        exit;
    } else {
        // muestra un mensaje si el correo o la contrasena no coinciden.
        $error = "Correo o contraseña incorrectos.";
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
    <title>Iniciar sesión | YSL Collection</title>
    <!-- carga los estilos de la tienda. -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<!-- encabezado con el logo y los enlaces principales. -->
<header class="encabezado">
    <!-- el logo lleva a la pagina principal. -->
    <a class="logo" href="index.php">YSL <span>COLLECTION</span></a>
    <!-- permite volver a la tienda o crear una cuenta. -->
    <nav><a href="index.php">Volver a la tienda</a><a href="registro.php">Crear cuenta</a></nav>
</header>
<!-- area principal de inicio de sesion. -->
<main class="formulario-contenedor">
    <!-- envia los datos de acceso a esta misma pagina. -->
    <form class="formulario" method="post">
        <!-- texto decorativo del formulario. -->
        <p class="mini-titulo">QUÉ GUSTO VERTE</p>
        <!-- titulo del formulario. -->
        <h1>Iniciar sesión</h1>
        <!-- indicacion para la persona que inicia sesion. -->
        <p>Ingresa a tu cuenta para continuar.</p>
        <!-- si hubo un error, lo muestra escapando el texto. -->
        <?php if ($error): ?><p class="mensaje-error"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p><?php endif; ?>
        <!-- etiqueta del campo de correo. -->
        <label for="correo">Correo electrónico</label>
        <!-- campo obligatorio para escribir el correo. -->
        <input id="correo" type="email" name="correo" required autocomplete="email">
        <!-- etiqueta del campo de contrasena. -->
        <label for="password">Contraseña</label>
        <!-- campo obligatorio que oculta la contrasena al escribir. -->
        <input id="password" type="password" name="password" required autocomplete="current-password">
        <!-- envia el formulario para comprobar los datos. -->
        <button class="boton" type="submit">Entrar</button>
        <!-- enlace para registrarse si aun no tiene cuenta. -->
        <p class="nota">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </form>
</main>
<!-- cierra el contenido visible de la pagina. -->
</body>
<!-- termina el documento HTML. -->
</html>
