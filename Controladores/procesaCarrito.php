<?php

session_start();
include_once '../Dao/DaoOperaciones.php';

// Verifica si el valor 'añadirAlCarrito' está definido en la solicitud POST
if (isset($_POST['añadirAlCarrito'])) {
    $idConcierto = $_POST['añadirAlCarrito'];
    $dao = new DaoOperaciones();
    //Obtiene el concierto
    $concierto = $dao->obtenerConciertoSencillo($idConcierto);

    //Le añadimos al concierto que no llega que es un array asociativo un nuevo valor con entrada
    $concierto['entrada'] = 1;

    $conciertoExistente = false;

    foreach ($_SESSION['carritoCompra'] as $indice => $entrada) {

        if ($concierto['codigo'] === $entrada['codigo']) {

            //Ya encontramos una entrada con ese mismo código en vez de crear otra entrada nueva le sumamos una entrada su número de entradas
            //Le sumamos una nueva entrada al ya existir
            $_SESSION['carritoCompra'][$indice]['entrada']++;

            $conciertoExistente = true;

            //Terminamos de recorrer el array de sesiones
            break;
        }
    }

    //Si el concierto no existe se lo añadimos al array
    if (!$conciertoExistente) {
        $_SESSION['carritoCompra'][] = $concierto;
    }
}

// Obtener el contenido actual del carrito y devolverlo como JSON
$sesionCarrito = ($_SESSION['carritoCompra']) ? $_SESSION['carritoCompra'] : null;

// Devolver la información como JSON
header('Content-Type: application/json');
echo json_encode(['sesionCarrito' => $sesionCarrito]);
