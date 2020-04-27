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
            if($usuario->email==$mail && $usuario->clave!=$contraseña){
                $retorno->contenido = "Clave incorrecta";
                return $retorno;
            }
            else if($usuario->email==$mail && $usuario->clave==$contraseña){
                $retorno->valido=true;
                $retorno->contenido = $usuario;
                return $retorno;
            }
        }
        $retorno->contenido = "Mail invalido";
        return $retorno;
    }


    public function ListaUsuariosPorTipo($tipo){
        $listaUsuarios = $this->LeerArchivo("r");
        $retorno = array();
        if($tipo=="admin"){
            $retorno = $listaUsuarios;
        }
        else if($tipo=="user"){
            foreach ($listaUsuarios as $key => $usuario) {
                if($usuario->tipo == "user"){
                    unset($usuario->clave);
                    array_push($retorno, $usuario);
                }
                
            }            
        }
        return $retorno;
    }

}