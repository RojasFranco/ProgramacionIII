<?php
require './Clases/pais.php';
//require_once './Interfaces/interfaces.php'; 

class PaisPorCapital extends Pais implements IMostrar{

    public $paises;
    public function __construct($restCountries){
        
        parent::__construct($restCountries);
        
        
        $this->paises = $this->paisesTotales;
    }

    public function MostrarPaises($capital){
        $paisesMostrar = $this->paises->fields(["name"])->byCapitalCity($capital);
        echo json_encode($paisesMostrar);
    }

}