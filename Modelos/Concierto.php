<?php

/**
 * Description of Concierto
 *
 * @author Sergio
 */
class Concierto {

    //tipado PHP 8.0
    public function __construct(
            private int $id,
            private string $codigo,
            private string $nombre,
            private string $provincia,
            private string $ciudad,
            private string $lugar,
            private string $tipo,
            private string $genero,
            private string $fecha
    ) {}

    public function getId() {
        return $this->id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getProvincia() {
        return $this->provincia;
    }

    public function getCiudad() {
        return $this->ciudad;
    }
    
    public function getLugar(){
        return $this->lugar;
    }

    public function getTipo() {
        return $this->tipo;
    }
    public function getGenero(){
        return $this->genero;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function __toString() {
        return "Id: " . $this->getId() . " cod: " . $this->getCodigo() . " Nombre: " . $this->getNombre() . " Provincia: " . $this->getProvincia() .
                " Ciudad: " . $this->getCiudad() . " Lugar: ".$this->lugar. " Tipo: " . $this->getTipo() ." Genero: " .$this->getGenero()." Fecha: " . $this->getFecha();
    }
}
