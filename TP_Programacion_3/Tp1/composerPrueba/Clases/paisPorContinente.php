<?php
require './Interfaces/interfaces.php';

class PaisesPorContinente implements IMostrar{

    public $paises;

    public function __construct($restCountries){
        $this->paises = $restCountries;
    }
    
    public function MostrarPaises($continente){        
        $paisesPedidos = $this->paises->fields(["name"])->byRegion($continente);
        echo json_encode($paisesPedidos);        
    }    
    






}