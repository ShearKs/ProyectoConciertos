<?php


// Eliminamos todas las variables de sesión
$_SESSION = array();

// Nos cargamos la sesión
if (ini_get("session.use_cookies")) {

    //Obtenemos los parámetros de la cookie de la sesión actual
    $params = session_get_cookie_params();
    //Se elimina la cookie de sesion
    setcookie(
        session_name(),
        '',
        //Lo hacemos con tiempo negativo para que la cookie(la sesion tambien es una cookie pero privada) expire
        time() - 42000,
        //Ruta para la cookie
        $params["path"],
        //Dominio para la cookie
        $params["domain"],
        //La cookie solo se envía a tráves de conexiones seguras
        $params["secure"],
        //Accesible solo a tráves del protocolo HTTP
        $params["httponly"]
    );
}

//Una vez hemos elimando todas las sesiones y con esto nuestro carrito de la compra nos vamos a la vista despedida
header('Location: ../Vistas/vistaDespedida.php');













