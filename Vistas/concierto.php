<?php
session_start();
require 'menu.html';

//Obtenemos el concierto que nos ha pasado el controlador
$concierto = $_SESSION['concierto'];

// echo "latitud: " . $concierto->latitud;
// echo "<br>";
// echo "longitud: " . $concierto->longitud;

//die();

if (isset($_POST['ver_carrito'])) {
    print_r($_SESSION['carritoCompra']);
}

?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Concierto</title>
    <link rel="stylesheet" href=" ../static/css/estiloConcierto.css" />
    <script>
        function iniciarMapa() {
            // Coordenadas para el marcador obtenidas desde PHP
            var latitud = <?= $concierto->latitud ?>;
            var longitud = <?= $concierto->longitud ?>;

            // Configuramos el mapa
            let mapa = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: latitud,
                    lng: longitud
                },
                zoom: 12
            });

            // Creamos el marcador
            let marcador = new google.maps.Marker({
                position: {
                    lat: latitud,
                    lng: longitud
                },
                map: mapa,
                title: 'Marcador'
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQVMdewnhQqaQksj33cdtbPs4Ut92kens&callback=iniciarMapa" async defer></script>

</head>

<body>

    <div class = "container">
        <div class="cabecera">

            <div class="coverImagen">
                <img class="imagenAmplia" src="data:image/jpeg;base64,<?= $concierto->imagen ?>" alt="Imagen del concierto">
            </div>

            <div class="contenidoDerecha">
                <div class = "precio"><p>Precio por Unidad: <?= $concierto->precio?>€</p></div>
                <button id="btnPago" class="btnPagar">Proceder con el Pago</button>
            </div>
        </div>

        <div class="artistaContenedor">
            <h2>Descripción de <?= $concierto->nombreArtistico ?></h2>
            <div class="biografia">
                <?= $concierto->biografia ?>
            </div>

            <div class="actuacion">
                <p class = "inforConcierto">Lugar de la actuación de <?= $concierto->nombreArtistico ?> : <?= $concierto->lugar ?>.</p>
                <p class = "inforConcierto">Genero: <?= $concierto->genero  ?>.</p>
                <p class = "inforConcierto">Fecha de la actuación <?php
                $fecha = $concierto->fecha;
                $fechaFormateada = date("d/m/Y", strtotime($fecha));
                 echo $fechaFormateada ?>.</p>
            </div>

            <!-- Div donde va ir la api de google maps -->
            <div id="map" class="mapa"></div>

            <button id="<?= $concierto->id ?>" class="botonCarrito">Añade la entrada al carrito</button>

        </div>
    </div>
</body>

</body>

</html>