<?php

include_once 'Conexion.php';
include_once '../Modelos/Concierto.php';

/**
 * Description of DaoOperaciones
 *
 * @author Sergio
 */
class DaoOperaciones
{

    private $conexion;

    //Se puede incluir la clase por parámetro PHP 7.0 y  posteriores...
    public function __construct()
    {
        $conexion = new Conexion();
        $this->conexion = $conexion->getConexion();
    }

    public function obtenerConciertos()
    {

        $sql = "SELECT ar.nombreArtistico,con.id,lu.nombre as lugar,lu.provincia,TO_BASE64(con.imagen) as imagen from concierto con , artista ar ,lugar lu
                    WHERE con.idArtista = ar.id AND lu.id = con.idLugar order by con.id;";
        //Resultado producido de la consulta
        $resultado = $this->conexion->query($sql);

        //array asociativo donde vamos a guardar todos los conciertos
        $conciertos = array();

        //Si obtemos algún resultado de esa consulta
        if ($resultado->num_rows > 0) {
            //Mientras haya datos el while sigue corriendo
            while ($fila = $resultado->fetch_object()) {
                array_push($conciertos, $fila);
            }
        }

        //Liberamos memoria
        $resultado->free();

        return $conciertos;
    }

    public function obtenerConcierto($id)
    {

        $sql = "SELECT ar.nombreArtistico,ar.biografia,con.id,con.genero,lu.nombre as lugar,con.fecha,con.precio,lu.provincia,TO_BASE64(con.imagen) as imagen,lu.latitud,lu.longitud FROM concierto con ,artista ar, lugar lu
                    WHERE  con.idArtista = ar.id AND lu.id = con.idLugar  AND con.id = ? ;";

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->bind_param("i", $id);

        $estado = $sentencia->execute();
        $resultado = $sentencia->get_result();

        $empleado = new ArrayObject();

        if ($estado != null && $resultado->num_rows == 1) {

            $empleado = $resultado->fetch_object();
        }

        $resultado->free();

        return $empleado;
    }

    //Para que no cargue la imagen , va a tener datos sencillos que van a ser los que cargue en el carrito
    public function obtenerConciertoSencillo($id)
    {

        $sql = "SELECT con.codigo,ar.nombreArtistico,lu.nombre as lugar,lu.provincia,con.precio,lu.latitud,lu.longitud  FROM concierto con ,artista ar,lugar lu
                    WHERE  con.idArtista = ar.id  AND lu.id = con.idLugar AND con.id = ? ";

        $sentencia = $this->conexion->prepare($sql);
        $sentencia->bind_param("i", $id);

        $estado = $sentencia->execute();
        $resultado = $sentencia->get_result();

        $empleado = array();

        if ($estado != null && $resultado->num_rows == 1) {

            $empleado = $resultado->fetch_assoc();
        }

        $resultado->free();

        return $empleado;
    }
}
