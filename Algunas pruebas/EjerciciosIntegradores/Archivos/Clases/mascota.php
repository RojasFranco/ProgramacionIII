<?php

class Mascota{
    public $nombre;
    public $raza;

    public function __construct($nombre, $raza)
    {
        $this->nombre=$nombre;
        $this->raza = $raza;
    }
}