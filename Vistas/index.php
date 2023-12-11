<?php
session_start();
include_once '../Dao/DaoOperaciones.php';
require 'menu.html';

$dao = new DaoOperaciones();


if (!isset($_SESSION['carritoCompra'])) {
    $_SESSION['carritoCompra'] = array();
} else {


    //Para depurar descomentar...
    // echo (gettype($_SESSION['carritoCompra']));
    //print_r("Entradas Compradas: " . count($_SESSION['carritoCompra']));
    
    //Descomentar para saber lo que tenemos en sesión
    // echo '<pre>';
    // print_r($_SESSION['carritoCompra']);
    // echo '</pre>';

}

$conciertos = $dao->obtenerConciertos();
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Bienvenido a nuestra página de conciertos</title>
    <link rel="stylesheet" href=" ../static/css/estilo.css" />
</head>

<body>
  
    <div class="principal">
        <?php foreach ($conciertos as $concierto) : ?>
            <div class="concierto">

                <div class="imagen">
                    <img class="imagenConcierto" src="data:image/jpeg;base64,<?= $concierto->imagen ?>" alt="Imagen del concierto">
                </div>

                <div class="contenido">
                    <div class="texto">
                        <h2><?= $concierto->nombreArtistico ?></h2>
                        <p><?= $concierto->lugar ?></p>
                        <p><?= $concierto->provincia ?></p>
                    </div>
                    <div>
                        <a href="../Controladores/comprarEntrada.php?id=<?= $concierto->id ?>" class="btnCompraLink">Comprar entrada</a>
                    </div>

                </div>
            </div>
        <?php endforeach ?>

    </div>
</body>

</html>