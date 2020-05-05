<?php

class Venta{

    public $email;
    public $tipo;
    public $sabor;
    public $fecha;

    public function __construct($email, $tipo, $sabor)
    {
        $this->email = $email;
        $this->tipo = $tipo;
        $this->sabor = $sabor;
        $this->fecha = getdate();
    }

    public static function BuscarVentasUsuario($usuario, $listaVentas){
        $listaRetornar=array();
        foreach ($listaVentas as $key => $venta) {
            if($usuario->email==$venta->email){
                array_push($listaRetornar, $venta);
            }
        }
        return $listaRetornar;
    }
}