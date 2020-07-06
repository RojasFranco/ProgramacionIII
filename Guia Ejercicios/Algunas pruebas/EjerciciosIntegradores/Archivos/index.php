<?php
require_once './Clases/mascota.php';
require_once './Clases/persona.php';


$metodo = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];

$UbicacionArchivoPersonas = "./archivos/personas.txt";
$UbicacionArchivoMascotas = "./archivos/mascotas.txt";

$respuesta = new stdClass;
$respuesta->success = "ok";

switch($path_info){
    case "/personas":
        if($metodo=="GET"){
            $archivo = fopen($UbicacionArchivoPersonas, "r");
            $listaPersonas = fread($archivo, filesize($UbicacionArchivoPersonas));
            
            $respuesta->data = json_decode($listaPersonas, true);                        
            
            echo json_encode($respuesta->data);
            //print_r($listaPersonas);            
            
            fclose($archivo);
        }
        else if($metodo=="POST"){
            $archivo = fopen($UbicacionArchivoPersonas, "r");

            if(filesize($UbicacionArchivoPersonas)>0){
                $listaPersonas = fread($archivo, filesize($UbicacionArchivoPersonas));            
                $listaPersonas = json_decode($listaPersonas);            
            }   
            else{
                $listaPersonas=array();
            }         

            $personaAgregar = new Persona($_POST["nombre"], $_POST["apellido"]);
            //$arrayAgregar = (array)$personaAgregar;
            array_push($listaPersonas, $personaAgregar);
            //print_r($listaPersonas);
            
            fclose($archivo);
            
            
            $archivo = fopen($UbicacionArchivoPersonas, "w");
            $respuesta->data = fwrite($archivo, json_encode($listaPersonas));            
            fclose($archivo);            
            echo json_encode($respuesta);
        }
        break;
    case "/mascotas":
        if($metodo=="GET"){
            $archivo = fopen($UbicacionArchivoMascotas, 'r');
            if(filesize($UbicacionArchivoMascotas)>0){
                $listaMascotas = fread($archivo, filesize($UbicacionArchivoMascotas));
                $respuesta->data = json_decode($listaMascotas);
            }            
            else{
                $respuesta->data=array();
            }
            echo json_encode($respuesta);
            fclose($archivo);
        }
        else if($metodo=="POST"){
            $archivo = fopen($UbicacionArchivoMascotas, 'r');
            if(filesize($UbicacionArchivoMascotas)>0){
                $listaMascotas = fread($archivo, filesize($UbicacionArchivoMascotas));
                $listaMascotas = json_decode($listaMascotas);
            }
            else{
                $listaMascotas = array();
            }
            fclose($archivo);
            
            if(isset($_POST['nombre'], $_POST['raza'])){
                $mascotaAgregar = new Mascota($_POST['nombre'], $_POST['raza']);
                array_push($listaMascotas, $mascotaAgregar);
                $archivo = fopen($UbicacionArchivoMascotas, "w");
                
                $respuesta->data = fwrite($archivo, json_encode($listaMascotas));
                fclose($archivo);
            }            
            else{
                $respuesta->data=0;
            }
            echo json_encode($respuesta);
            

        }
        break;
    default:
        echo "Ingrese path info valido";
}

