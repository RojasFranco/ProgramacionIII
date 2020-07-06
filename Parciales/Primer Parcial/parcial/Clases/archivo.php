<?php

class ArchivoJson{
    public $ubicacionArchivo;

    public function __construct($ubicacionArchivo)
    {
        $this->ubicacionArchivo=$ubicacionArchivo;
    }

    public function LeerArchivo($modoApertura){
        $tamañoArchivo = filesize($this->ubicacionArchivo);
        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        if($tamañoArchivo>0){
            $listaRetornar = fread($archivo, $tamañoArchivo);
            $listaRetornar = json_decode($listaRetornar);
        }
        else{
            $listaRetornar = array();
        }
        fclose($archivo);
        return $listaRetornar;        
    }

    public function EscribirArchivo($modoApertura, $arrayAEscribir){                

        $listaLeida = $this->LeerArchivo("r");
        array_push($listaLeida, $arrayAEscribir);

        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        $retorno = fwrite($archivo, json_encode($listaLeida));
        fclose($archivo);

        return $retorno;

    }

    public function ValidarUsuario($mail, $contraseña){        
        $listaUsuarios = $this->LeerArchivo("r");
        $retorno = new stdClass;
        $retorno->valido = false;        
        foreach ($listaUsuarios as $key => $usuario) {
            if($usuario->email==$mail && $usuario->clave==$contraseña){
                $retorno->valido=true;
                $retorno->contenido = $usuario;
                return $retorno;
            }
        }
        return $retorno;
    }

    public function ActualizarPizzas($modoApertura, $tipo, $sabor){
        $listaPizzas = $this->LeerArchivo("r");
        foreach ($listaPizzas as $key => $pizzaActual) {
            if($pizzaActual->tipo==$tipo && $pizzaActual->sabor==$sabor){
                $pizzaActual->stock = $pizzaActual->stock-1;
            }
        }
        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        fwrite($archivo, json_encode($listaPizzas));
        fclose($archivo);


    }
}
