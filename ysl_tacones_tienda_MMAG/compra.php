<?php
// activa la sesion para consultar los datos de la persona que inicio sesion.
session_start();
// carga la conexion con la base de datos.
require "config.php";

// revisa que la persona haya iniciado sesion.
if (!isset($_SESSION["usuario_id"])) {
    // si no inicio sesion, la manda a la pagina de acceso.
    header("Location: login.php");
    // detiene el archivo despues de redirigir.
    exit;
}

// obtiene el ID del producto desde la direccion y valida que sea un numero entero.
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
// prepara una consulta para buscar el producto por su ID.
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
// ejecuta la consulta usando el ID recibido.
$stmt->execute([$id]);
// guarda los datos del producto encontrado.
$producto = $stmt->fetch();

// comprueba si el producto no existe.
if (!$producto) {
    // si no existe, vuelve al catalogo.
    header("Location: index.php");
    // detiene el resto del archivo.
    exit;
}

// guarda aqui el mensaje que se mostrara si hay un error.
$error = "";
// revisa si el formulario se envio usando el metodo POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // quita espacios al inicio y al final de la talla recibida.
    $talla = trim($_POST["talla"] ?? "");
    // recibe la cantidad y verifica que sea un numero entero.
    $cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT);
    // quita espacios al inicio y al final de la direccion.
    $direccion = trim($_POST["direccion"] ?? "");
    // quita espacios al inicio y al final del telefono.
    $telefono = trim($_POST["telefono"] ?? "");

    // verifica que la talla sea valida, que la cantidad este entre 1 y 5
    // y que la direccion y el telefono no esten vacios.
    if (!in_array($talla, ["22", "23", "24", "25", "26", "27"], true) ||
        !$cantidad || $cantidad < 1 || $cantidad > 5 ||
        $direccion === "" || $telefono === "") {
        // muestra un aviso cuando algun dato no cumple las condiciones.
        $error = "Revisa los datos del formulario.";
    } else {
        // prepara la consulta para guardar el pedido en la base de datos.
        $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, producto_id, talla, cantidad, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?)");
        // guarda el pedido con los datos del usuario y del formulario.
        $stmt->execute([$_SESSION["usuario_id"], $producto["id"], $talla, $cantidad, $direccion, $telefono]);
        // regresa al catalogo con un aviso de pedido exitoso.
        header("Location: index.php?pedido=ok");
        // detiene el archivo despues de guardar y redirigir.
        exit;
    }
}
?>
<!-- indica que desde aqui comienza el contenido de la pagina. -->
<!DOCTYPE html>
<!-- define el idioma principal de la pagina como espanol. -->
<html lang="es">
<head>
    <!-- permite mostrar correctamente caracteres especiales. -->
    <meta charset="UTF-8">
    <!-- ajusta el ancho de la pagina al tamano de la pantalla. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- texto que aparece en la pestana del navegador. -->
    <title>Realizar pedido | YSL Collection</title>
    <!-- carga los estilos de la tienda. -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<!-- encabezado con el nombre de la tienda y sus enlaces. -->
<header class="encabezado">
    <!-- el logo lleva de vuelta a la pagina principal. -->
    <a class="logo" href="index.php">YSL <span>COLLECTION</span></a>
    <!-- enlaces para volver a la tienda o cerrar sesion. -->
    <nav><a href="index.php">Volver a la tienda</a><a href="logout.php">Cerrar sesión</a></nav>
</header>
<!-- muestra el nombre de la persona que inicio sesion. -->
<div class="bienvenida">
    <!-- escapa el nombre para mostrarlo de forma segura. -->
    <span>¡Bienvenido, <?= htmlspecialchars($_SESSION["nombre"], ENT_QUOTES, "UTF-8") ?>!</span>
    <!-- permite cerrar la sesion desde esta pagina. -->
    <a href="logout.php" class="cerrar-sesion">Cerrar sesión</a>
</div>
<!-- contenedor principal del formulario de compra. -->
<main class="formulario-contenedor">
    <!-- el formulario envia los datos a esta misma pagina. -->
    <form class="formulario" method="post">
        <!-- texto pequeno que introduce la seleccion del producto. -->
        <p class="mini-titulo">TU ELECCIÓN</p>
        <!-- titulo principal del formulario. -->
        <h1>Realizar pedido</h1>
        <!-- resumen del producto que se esta comprando. -->
        <div class="producto-resumen">
            <!-- muestra la imagen del producto. -->
            <img src="<?= htmlspecialchars($producto["imagen"], ENT_QUOTES, "UTF-8") ?>" alt="">
            <!-- agrupa el nombre y el precio del producto. -->
            <div>
                <!-- muestra el nombre del producto de forma segura. -->
                <strong><?= htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") ?></strong>
                <!-- muestra el precio con dos decimales y la moneda. -->
                <p>$<?= number_format((float)$producto["precio"], 2) ?> MXN</p>
            </div>
        </div>
        <!-- muestra el error si los datos enviados no son validos. -->
        <?php if ($error): ?><p class="mensaje-error"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p><?php endif; ?>
        <!-- texto que identifica el campo de talla. -->
        <label for="talla">Talla</label>
        <!-- lista para elegir una talla; el campo es obligatorio. -->
        <select id="talla" name="talla" required>
            <!-- opcion inicial que pide seleccionar una talla. -->
            <option value="">Selecciona tu talla</option>
            <!-- crea una opcion por cada talla disponible. -->
            <?php foreach (["22", "23", "24", "25", "26", "27"] as $t): ?>
                <!-- muestra la talla como opcion en el menu. -->
                <option value="<?= $t ?>"><?= $t ?> MX</option>
            <?php endforeach; ?>
        </select>
        <!-- texto del campo para indicar cuantos pares se quieren. -->
        <label for="cantidad">Cantidad (máximo 5)</label>
        <!-- permite escribir una cantidad entre 1 y 5; inicia en 1. -->
        <input id="cantidad" type="number" name="cantidad" min="1" max="5" value="1" required>
        <!-- texto que identifica el campo de direccion. -->
        <label for="direccion">Dirección de envío</label>
        <!-- area para escribir la direccion; es obligatoria. -->
        <textarea id="direccion" name="direccion" rows="3" required></textarea>
        <!-- texto que identifica el campo de telefono. -->
        <label for="telefono">Teléfono de contacto</label>
        <!-- campo para el telefono, con un maximo de 20 caracteres. -->
        <input id="telefono" type="tel" name="telefono" required maxlength="20">
        <!-- aclara que el formulario no guarda datos bancarios. -->
        <p class="nota">Demostración escolar: no se solicitan ni guardan datos de tarjetas bancarias.</p>
        <!-- boton que envia el formulario para confirmar el pedido. -->
        <button class="boton" type="submit">Confirmar pedido</button>
    </form>
</main>
</body>
</html>
