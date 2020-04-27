<?php

class Usuario{

    public $nombre;
    public $apellido;
    public $email;
    public $clave;
    public $telefono;
    public $tipo;

    public function __construct($nombre, $apellido, $email, $clave, $telefono, $tipo)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->clave = $clave;
        $this->telefono = $telefono;
        $this->tipo = $tipo;
    }

}