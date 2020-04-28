<?php

class Producto{


    public $producto;
    public $marca;
    public $precio;
    public $stock;
    public $id;

    public function __construct($producto, $marca, $precio, $stock)
    {
        $this->producto = $producto;
        $this->marca = $marca;
        $this->stock = $stock;
        $this->precio = $precio;
        $this->id = rand(0,1) . "-" . time();
    }

}