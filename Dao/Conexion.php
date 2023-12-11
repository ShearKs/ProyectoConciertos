<?php

/**
 * Description of Conexion
 *
 * @author Sergio
 */
class Conexion {

    //private int 
    private $host;
    private $usuario;
    private $contrasena;
    private $nombreBdd;
    private $conexion;

    public function __construct() {

        $this->host = "localhost";
        $this->usuario = "root";
        $this->contrasena = "";
        $this->nombreBdd = "bdd_conciertos_sfe";

        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->nombreBdd);

        //Para evitar problemas en la tildes en la base de datos...
        $this->conexion->set_charset("utf8");

        //Si tenemos algún problema al conectar en la base de datos
        if ($this->conexion->connect_errno) {
            echo "Ha habido un problema al conectar con la base de datos ,Error".$this->conexion->connect_error;
            die();
        }
    }
    
    
    //Devolvemos la conexión
    public function getConexion(){
        return $this->conexion;
    }
    
    //Método para cerrar la conexión
    public function cerrarConexion(){
        $this->conexion->close();
    }
    

}
