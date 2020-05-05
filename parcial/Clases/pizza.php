<?php

class Pizza{

    public $tipo;
    public $precio;
    public $stock;
    public $sabor;

    public function __construct($tipo, $sabor, $precio, $stock)
    {
        $this->tipo = $tipo;
        $this->sabor = $sabor;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    public static function guardarImagen($fileKey){
        
        $ubicacionImagenes = "./Imagenes/";
        $nombreOriginal = $fileKey["name"];        
        $ubicacionInicial = $fileKey["tmp_name"];

        $explode = explode(".", $nombreOriginal);        
        $nombreUnico=$explode[0]."-".time().".".$explode[count($explode)-1];

        $ubicacionDestino = $ubicacionImagenes.$nombreUnico;
        move_uploaded_file($ubicacionInicial, $ubicacionDestino);        
    }

    public static function pizzaEstaIncluida($tipo, $sabor, $listaPizzas){
        foreach ($listaPizzas as $key => $pizzaActual) {
            if($pizzaActual->tipo==$tipo && $pizzaActual->sabor==$sabor){
                return true;
            }
        }
        return false;
    }

    public static function BuscarPizza($tipo, $sabor, $listaPizzas){
        $retorno = new stdClass;
        $retorno->estaPizza=false;
        foreach ($listaPizzas as $key => $pizzaActual) {
            if($pizzaActual->tipo==$tipo && $pizzaActual->sabor==$sabor){
                $retorno->contenido=$pizzaActual;
                $retorno->estaPizza=true;
                return $retorno;
            }
        }
        return $retorno;

    }

    public static function CargarVenta($ubicacionArchivo, $pizza, $email){
        $manejadorVentas = new ArchivoJson($ubicacionArchivo);
        $ventaNueva = new Venta($email,$pizza->tipo, $pizza->sabor, $pizza->precio);
        $manejadorVentas->EscribirArchivo("w", $ventaNueva);
    }

    public static function VenderPizza($ubicacionArchivoPizzas, $tipo, $sabor, $ubicacionVenta, $email){
        $manejadorArchivoPizza = new ArchivoJson($ubicacionArchivoPizzas);
        $listaPizzas = $manejadorArchivoPizza->LeerArchivo("r");

        $retornoPizza = Pizza::BuscarPizza($tipo, $sabor, $listaPizzas);
        if($retornoPizza->estaPizza){
            $pizzaVender = $retornoPizza->contenido;
            if($pizzaVender->stock>=1){
                $manejadorArchivoPizza->ActualizarPizzas("w", $tipo, $sabor);
                Pizza::CargarVenta($ubicacionVenta, $pizzaVender, $email);                                
                $retorno = new RespuestaJson("ok", $pizzaVender->precio);
                echo json_encode($retorno);
            }
            else{
                echo "no hay stock";
            }
        }
        else{
            echo "no está esta pizza";
        }
    }

}