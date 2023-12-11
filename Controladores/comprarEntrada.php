<?php

include_once '../Dao/DaoOperaciones.php';

try {

    $operacionesBDD = new DaoOperaciones();

    $idConcierto = $_REQUEST['id'];
    $concierto = $operacionesBDD->obtenerConcierto($idConcierto);
    //print_r($concierto);
    
    //Pasamos el concierto por sesión lo he intentado por json_encode y pasarselo por parámetro pero la imagen hace que sea muy largo y si no,no me lo traga :(
    //Cada conciertoa que le demos comprar se sustituirá pero nos da igual ya que solo lo necesitamos para pasarlo a la vista cada vez que cliquemos
    if(session_start() == PHP_SESSION_NONE){
          $_SESSION['concierto'] = $concierto;
    }
    
  
    
    header("Location: ../Vistas/concierto.php");
      
} catch (Exception $ex) {
    
}
