<?php


$path_info = $_SERVER['PATH_INFO'];
$metodo = $_SERVER['REQUEST_METHOD'];
$archivo;
$ubicacionMascotas="./archivosFolder/mascotas.txt";
$ubicacionPersonas="./archivosFolder/personas.txt";
$ubicacionMarcas = "./archivosFolder/marcas.txt";

switch($path_info){
    case "/Mascota":
        if($metodo=="GET"){
            $archivo = fopen($ubicacionMascotas, "r");
            while(!feof($archivo)){
                echo fgets($archivo);
            }             
            
        }
        else if($metodo=="POST"){
            $archivo = fopen($ubicacionMascotas, "a");
            fwrite($archivo, json_encode($_POST));            
        }
        fclose($archivo);
        break;
    case "/Persona":
        if($metodo=="GET"){
            $archivo=fopen($ubicacionPersonas, "r+");
            while(!feof($archivo)){
                echo fgets($archivo);
            }

        }
        else if($metodo=="POST"){
            $archivo = fopen($ubicacionPersonas, "a");
            fwrite($archivo, json_encode($_POST));
        }
        fclose($archivo);
        break;
    case "/Marca":
        if($metodo=="GET"){
            $archivo = fopen($ubicacionMarcas, "r");
            while(!feof($archivo)){
                echo fgets($archivo);
            }
        }
        else if($metodo=="POST"){
            $archivo = fopen($ubicacionMarcas,"a");
            fwrite($archivo, json_encode($_POST));
        }
        fclose($archivo);
        break;
    default:
        break;

}

