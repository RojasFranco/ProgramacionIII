<?php

class Venta{
    public $id;
    public $cantidad;
    public $usuario;


    public function __construct($id, $cantidad, $usuario)
    {
        $this->id = $id;
        $this->cantidad = $cantidad;
        $this->usuario = $usuario;
    }
}