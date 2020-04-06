<?php
//require './Clases/pais.php'; Ya en uso
class PaisDetallado extends Pais{

    public $paises;

    public function __construct($restCountries){
        parent::__construct($restCountries);
        
        $this->paises = $this->paisesTotales;
    }

    public function MostrarDetalles($paisBuscado){
        $paisMostrar = $this->paises->byName($paisBuscado, true);
        echo json_encode($paisMostrar);
    }
}