<?php

class Usuario{
    
    public $nombre;
    public $dni;
    public $clave;
    public $tipo;
    public $obraSocial;
    public $id;

    public function __construct($nombre, $dni, $clave, $tipo, $obraSocial)
    {
        $this->nombre = $nombre;
        $this->dni = $dni;
        $this->clave = $clave;
        $this->tipo = $tipo;
        $this->obraSocial = $obraSocial;
        $this->id = rand(0,1000);
    }

}