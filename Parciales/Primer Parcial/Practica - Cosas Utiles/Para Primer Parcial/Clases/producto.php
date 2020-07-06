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


    public static function guardarImagen($fileKey){//, $ubicacionImagenes){
        
        $ubicacionImagenes = "./Imagenes/";
        $nombreOriginal = $fileKey["name"];        
        $ubicacionInicial = $fileKey["tmp_name"];

        $explode = explode(".", $nombreOriginal);        
        $nombreUnico=$explode[0]."-".time().".".$explode[count($explode)-1];

        $ubicacionDestino = $ubicacionImagenes.$nombreUnico;
        move_uploaded_file($ubicacionInicial, $ubicacionDestino);        
    }
}