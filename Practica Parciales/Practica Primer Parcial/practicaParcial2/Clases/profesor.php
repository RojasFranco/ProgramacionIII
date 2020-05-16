<?php
class Profesor{
    public $nombre;
    public $legajo;

    public function __construct($nombre, $legajo)
    {
        $this->nombre = $nombre;
        $this->legajo = $legajo;
    }

    public static function esUnico($legajo, $listaProfesores){
        foreach ($listaProfesores as $key => $profesor) {
            if($profesor->legajo==$legajo){
                return false;
            }
        }
        return true;
    }
}