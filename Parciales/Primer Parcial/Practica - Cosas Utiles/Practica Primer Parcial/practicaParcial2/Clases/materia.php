<?php
class Materia{
    public $nombre;
    public $cuatrimestre;
    public $idMateria;

    public function __construct($nombre, $cuatrimestre)
    {
        $this->nombre = $nombre;
        $this->cuatrimestre = $cuatrimestre;
        $this->idMateria=rand(0,50);
    }

    public static function MateriaExistente($idMateria, $listaMaterias){
        foreach ($listaMaterias as $key => $materia) {
            if($materia->idMateria == $idMateria){
                return true;
            }
        }
        return false;
    }
}