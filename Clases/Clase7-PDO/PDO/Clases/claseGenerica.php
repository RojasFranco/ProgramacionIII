<?php


class Generico{

    public $id;
    public $nombre;
    public $otroDato;

    public function __construct($id, $nombre, $otroDato)
    {
        $this->id=$id;
        $this->nombre=$nombre;
        $this->otroDato=$otroDato;
    }


    public function TraerTodosDatos(){
        
    }

}