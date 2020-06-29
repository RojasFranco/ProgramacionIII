<?php

class AccesoBD{

    private static $objetoPDO;
    private $const = "mysql: host=localhost; dbname=pruebaPDO";
    //private static $accesoBD;

    private function __construct()
    {        
        try{
            AccesoBD::$objetoPDO = new PDO($this->const, "root", "");
        }        
        catch(PDOException $error){
            echo "ERROR: ".$error->getMessage();
        }

    }


    public static function ObtenerAcceso(){
        if(!isset(AccesoBD::$objetoPDO)){
            new AccesoBD();            
        }
        return AccesoBD::$objetoPDO;
    }


    public static function RetornarPrepare($consultaSql){
        $accesoBD = AccesoBD::ObtenerAcceso();
        return $accesoBD->prepare($consultaSql);
    }
}