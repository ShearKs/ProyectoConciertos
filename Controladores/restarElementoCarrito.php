<?php

session_start();

include_once '../Dao/DaoOperaciones.php';
if (isset($_POST['eventoRestarCarrito'])) {
    $codigoConcierto = $_POST['eventoRestarCarrito'];
    $carrito = $_SESSION['carritoCompra'];


    function restarCarrito($carrito,$codigoConcierto){

        $carritoActualizado = array();

        //Recorremos nuestro array session
        foreach ($carrito as $indice  => $concierto) {
     
            if ($concierto['codigo'] ===  $codigoConcierto) {

                //Si tiene más de una entrada le quitamos una entrada
                if ($concierto['entrada'] > 1) {

                    $concierto['entrada'] -= 1;
                    //Actualizamos el carrito con una entrada restada
                    $carritoActualizado[] = $concierto;
                } 
            }else{

                //Añadiremos los demás elementos al array tal como estaban
                $carritoActualizado[] = $concierto;


            }
        }
        //Retornamos una copia del carrito que teniamos pero restandole una entrada al concierto
        return $carritoActualizado;
    }

    /* He tenido que crear está función que me hago un nuevo array en vez dez de directamente al array de sesión
        eliminarle entradas porque me daba errores si eliminaba completamente alguno entre medias es decir tenia problemas haciendolo de esa maneras por la posiciones del array
    
    */

    //Actualizamos el carrito con la entrada restada
    $_SESSION['carritoCompra'] = restarCarrito($carrito,$codigoConcierto);

    //Le damos una respuesta al cliente que la resta de elemento se ha hecho correctamente
    echo json_encode((['succes' => true, 'message' => "Elemento restado correctamente"]));


}

//Si no hay parámetros o no se cumple alguna condición devolvemos un mensaje de error
echo json_encode(['success' => false, 'message' => 'Error en la solicitud']);
