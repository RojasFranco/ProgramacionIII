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

    public function ValidarUsuario($nombre, $contraseña){        
        $listaUsuarios = $this->LeerArchivo("r");
        $retorno = new stdClass;
        $retorno->valido = false;        
        foreach ($listaUsuarios as $key => $usuario) {
            if($usuario->nombre==$nombre && $usuario->clave==$contraseña){
                $retorno->valido=true;
                $retorno->contenido = $usuario;
                return $retorno;
            }
        }
        return $retorno;
    }

    public function ValidarProducto($id){        
        $listaProductos = $this->LeerArchivo("r");
        $retorno = new stdClass;
        $retorno->valido = false;        
        foreach ($listaProductos as $key => $producto) {
            if($producto->id==$id){
                $retorno->valido=true;
                $retorno->contenido = $producto;
                return $retorno;
            }
        }
        return $retorno;
    }


    public function LeerArchivoSerializado($modoApertura){
        $tamañoArchivo = filesize($this->ubicacionArchivo);
        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        if($tamañoArchivo>0){
            $listaRetornar = fread($archivo, $tamañoArchivo);
            //$listaRetornar = json_decode($listaRetornar);
        }
        else{
            $listaRetornar = "";
        }
        fclose($archivo);
        return $listaRetornar;        
    }
    public function EscribirArchivoSerializado($modoApertura, $stringEscribir){                

        $strLeido = $this->LeerArchivo("r");
        

        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        $retorno = fwrite($archivo, $strLeido . $stringEscribir);
        fclose($archivo);

        return $retorno;

    }
}