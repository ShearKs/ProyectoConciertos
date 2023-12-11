<?php

session_start();

$sesionCarrito = ($_SESSION['carritoCompra']) ? $_SESSION['carritoCompra'] : null;


// Devolver la información como json
header('Content-Type: application/json');
echo json_encode(['sesionCarrito' => $sesionCarrito]);