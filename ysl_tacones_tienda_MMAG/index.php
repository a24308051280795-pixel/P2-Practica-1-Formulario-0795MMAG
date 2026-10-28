<?php
// inicia o continua la sesion.
session_start();
// carga la conexion con la base de datos.
require "config.php";
// busca todos los productos y los ordena por su ID.
$productos = $pdo->query("SELECT * FROM productos ORDER BY id")->fetchAll();
?>
<!-- aqui empieza el documento HTML de la tienda. -->
<!DOCTYPE html>
<!-- indica que el contenido esta en espanol. -->
<html lang="es">
<head>
    <!-- configura los caracteres para que se vean correctamente. -->
    <meta charset="UTF-8">
    <!-- ajusta la pagina al ancho de la pantalla. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- nombre que aparece en la pestana del navegador. -->
    <title>YSL Collection | Tienda de tacones</title>
    <!-- carga los estilos de la tienda. -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<!-- encabezado con el logo y el menu. -->
<header class="encabezado">
    <!-- el logo lleva a la pagina principal. -->
    <a class="logo" href="index.php">YSL <span>COLLECTION</span></a>
    <!-- agrupa los enlaces de navegacion. -->
    <nav>
        <!-- enlace a la pagina de inicio. -->
        <a href="index.php">Inicio</a>
        <!-- enlace directo al catalogo. -->
        <a href="#productos">Tacones</a>
        <!-- muestra los enlaces segun el estado de la sesion. -->
        <?php if (isset($_SESSION["usuario_id"])): ?>
            <!-- opcion para salir cuando hay una sesion iniciada. -->
            <a href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <!-- opcion para acceder si todavia no inicio sesion. -->
            <a href="login.php">Iniciar sesión</a>
            <!-- opcion para crear una cuenta nueva. -->
            <a href="registro.php">Crear cuenta</a>
        <?php endif; ?>
    </nav>
</header>

<!-- muestra un saludo si hay una sesion con nombre de usuario. -->
<?php if (isset($_SESSION["usuario_id"], $_SESSION["nombre"])): ?>
    <!-- contenedor del saludo y el enlace para salir. -->
    <div class="bienvenida">
        <!-- muestra el nombre escapado para evitar interpretar codigo HTML. -->
        <span>¡Bienvenido, <?= htmlspecialchars($_SESSION["nombre"], ENT_QUOTES, "UTF-8") ?>!</span>
        <!-- enlace para cerrar la sesion. -->
        <a href="logout.php" class="cerrar-sesion">Cerrar sesión</a>
    </div>
<?php endif; ?>

<!-- portada principal de la tienda. -->
<section class="portada">
    <!-- agrupa los textos y el boton de la portada. -->
    <div class="portada-texto">
        <!-- frase pequena que acompana el titulo. -->
        <p class="mini-titulo">ELEGANCIA EN CADA PASO</p>
        <!-- titulo principal de la portada. -->
        <h1>Un clásico<br>que nunca pasa de moda.</h1>
        <!-- texto breve para presentar la coleccion. -->
        <p>Descubre nuestra selección de tacones para ocasiones especiales.</p>
        <!-- boton que lleva al catalogo. -->
        <a class="boton" href="#productos">Ver colección</a>
    </div>
</section>

<!-- contenido principal con los productos de la tienda. -->
<main class="contenedor" id="productos">
    <!-- presentacion del catalogo. -->
    <div class="titulo-seccion">
        <!-- texto pequeno sobre la seleccion. -->
        <p class="mini-titulo">NUESTRA SELECCIÓN</p>
        <!-- titulo de la seccion de productos. -->
        <h2>Tacones destacados</h2>
        <!-- descripcion breve de los disenos. -->
        <p>Diseños elegantes para completar tu estilo.</p>
    </div>

    <!-- muestra un aviso si se acaba de completar un pedido. -->
    <?php if (isset($_GET["pedido"]) && $_GET["pedido"] === "ok"): ?>
        <p class="mensaje-exito">¡Tu pedido se registró correctamente!</p>
    <?php endif; ?>

    <!-- seccion donde aparecen las tarjetas de productos. -->
    <section class="productos">
        <!-- repite la tarjeta por cada producto recibido de la base de datos. -->
        <?php foreach ($productos as $producto): ?>
        <!-- tarjeta individual del producto actual. -->
        <article class="tarjeta">
            <!-- imagen y nombre alternativo, ambos escapados para mostrarlos con seguridad. -->
            <img src="<?= htmlspecialchars($producto["imagen"], ENT_QUOTES, "UTF-8") ?>"
                alt="<?= htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") ?>">
            <!-- agrupa el nombre, la descripcion, el precio y el boton. -->
            <div class="tarjeta-info">
                <!-- muestra el nombre del producto. -->
                <h3><?= htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") ?></h3>
                <!-- muestra la descripcion del producto. -->
                <p><?= htmlspecialchars($producto["descripcion"], ENT_QUOTES, "UTF-8") ?></p>
                <!-- muestra el precio con dos decimales y la moneda. -->
                <strong>$<?= number_format((float)$producto["precio"], 2) ?> MXN</strong>
                <!-- enlace para iniciar la compra de este producto. -->
                <a class="boton boton-chico" href="compra.php?id=<?= (int)$producto["id"] ?>">Comprar</a>
            </div>
        </article>
        <?php endforeach; ?>
    </section>
</main>

<!-- pie de pagina con los datos del proyecto. -->
<footer>
    <!-- nombre y tipo de tienda. -->
    <p>0795.Tienda de tacones YSL</p>
    <!-- nombre y grupo de la estudiante. -->
    <p>MIA MARINA ARZAPALA GUERRERO 5°J</p>
</footer>
<!-- cierra el contenido visible de la pagina. -->
</body>
<!-- termina el documento HTML. -->
</html>
