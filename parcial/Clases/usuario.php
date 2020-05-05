<?php

class Usuario{
    
    public $email;
    public $clave;
    public $tipo;

    public function __construct($email, $clave, $tipo)
    {        
        $this->email = $email;
        $this->clave = $clave;
        $this->tipo = $tipo;
        //$this->id = rand(0,1000);
    }

}