<?php
// inicia la sesion para poder cerrarla.
session_start();
// borra los datos guardados en la sesion actual.
$_SESSION = [];

// comprueba si PHP usa una cookie para identificar la sesion.
if (ini_get("session.use_cookies")) {
    // obtiene la configuracion actual de la cookie.
    $parametros = session_get_cookie_params();
    // expira la cookie para quitarla del navegador.
    setcookie(
        // usa el nombre de la cookie de sesion actual.
        session_name(),
        // deja el valor vacio.
        "",
        // establece una fecha pasada para eliminar la cookie.
        time() - 42000,
        // mantiene la ruta configurada para la cookie.
        $parametros["path"],
        // mantiene el dominio configurado para la cookie.
        $parametros["domain"],
        // conserva la configuracion de conexion segura.
        $parametros["secure"],
        // conserva la configuracion que limita el acceso desde JavaScript.
        $parametros["httponly"]
    );
}

// destruye la sesion en el servidor.
session_destroy();
// regresa a la pagina principal.
header("Location: index.php");
// detiene el archivo despues de redirigir.
exit;
?>
