<?php

session_start();

foreach($_SESSION['carritoCompra']  as $indice => $carrito){

    unset($_SESSION['carritoCompra'][$indice]);

}

echo json_encode(['success' => true]);
