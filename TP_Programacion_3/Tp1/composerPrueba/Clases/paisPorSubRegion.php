<?php

class PaisPorRegion{


    public static function MostrarPaises($restCountries, $subRegion){
        $paisesMostrar=$restCountries->fields(["name"])->byRegionalBloc($subRegion);
        echo json_encode($paisesMostrar);
    }



}

