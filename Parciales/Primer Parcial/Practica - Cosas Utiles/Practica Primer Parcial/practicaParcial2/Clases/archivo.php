<?php
class Archivo{
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

    public function ValidarCuenta($email, $contraseña){        
        $listaUsuarios = $this->LeerArchivo("r");
        $retorno = new stdClass;
        $retorno->valido = false;        
        foreach ($listaUsuarios as $key => $usuario) {
            if($usuario->email==$email && $usuario->clave==$contraseña){
                $retorno->valido=true;
                $retorno->contenido = $usuario;
                return $retorno;
            }
        }
        return $retorno;
    }

    public function ModificarArchivoElemento($modoApertura, $claveBuscada, $nuevoValor){
        $listaActual = $this->LeerArchivo("r");
        foreach ($listaActual as $key => $producto) {
            if($producto->claveBuscada == $claveBuscada){
                $producto->stock = $nuevoValor;
                break;
            }
        }
        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        fwrite($archivo, json_encode($listaActual));
        fclose($archivo);
    }


    public function LeerArchivoSerializado($modoApertura){
        $tamañoArchivo = filesize($this->ubicacionArchivo);

        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        if($tamañoArchivo>0){
            $listaRetornar = fread($archivo, $tamañoArchivo);
        }
        else{
            $listaRetornar = "";
        }
        fclose($archivo);
        return $listaRetornar;        
    }

    
    public function EscribirArchivoSerializado($modoApertura, $elementoAEscribir){                

        $strLeido = $this->LeerArchivoSerializado("r");
        $archivo = fopen($this->ubicacionArchivo, $modoApertura);
        $escribir = serialize($elementoAEscribir);
        $retorno = fwrite($archivo, $strLeido . $escribir.PHP_EOL);
        fclose($archivo);

        return $retorno;

    }

    public function MostrarArchivoDeserealizado(){
        
        $archivo = fopen($this->ubicacionArchivo, "r");
        $listaRetornar = array();
        while(!feof($archivo)){
            $serealizado=fgets($archivo);
            if($serealizado!=""){
                $deserealizado = unserialize($serealizado);
                array_push($listaRetornar, $deserealizado);
            }            
        }                
        return $listaRetornar;
    }

    public static function guardarImagen($fileKey,$ubicacionImagenes){
        
        //$ubicacionImagenes = "../Imagenes";//"./Imagenes/";
        $nombreOriginal = $fileKey["name"];        
        $ubicacionInicial = $fileKey["tmp_name"];

        $explode = explode(".", $nombreOriginal);        
        $nombreUnico=$explode[0]."-".time().".".$explode[count($explode)-1];

        $ubicacionDestino = $ubicacionImagenes.$nombreUnico;
        move_uploaded_file($ubicacionInicial, $ubicacionDestino);        
    }
}