<?php

require_once __DIR__ . '/composer/vendor/autoload.php';
require_once './Clases/usuario.php';
require_once './Clases/archivo.php';
require_once './Clases/respuestaJson.php';
require_once './Clases/tokenJwt.php';
require_once './Clases/pizza.php';
require_once './Clases/venta.php';

$metodo = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];

$ubicacionUsuarios = "./Archivos/users.txt";
$ubicacionPizzas = "./Archivos/pizzas.txt";
$ubicacionVentas = "./Archivos/ventas.txt";

switch ($path_info) {
    case '/usuario':
        if($metodo=="POST"){
            if(isset($_POST["email"], $_POST["clave"], $_POST["tipo"])){
                $email = $_POST["email"];
                $clave = $_POST["clave"];
                $tipo = $_POST["tipo"];
                $usuarioNuevo = new Usuario($email, $clave, $tipo);                
                $manejadorArchivo = new ArchivoJson($ubicacionUsuarios);
                $cantCaracteres=$manejadorArchivo->EscribirArchivo("w", $usuarioNuevo);                
                $retorno = new RespuestaJson("ok", $cantCaracteres);                

                echo json_encode($retorno);
            }
            else{
                echo "llene los campos";
            }
        }
        else{
            echo "metodo invalido";
        }
        break;
    case '/login':
        if($metodo=="POST"){
            if(isset($_POST["email"], $_POST["clave"])){
                $email = $_POST["email"];
                $clave = $_POST["clave"];                

                $manejadorArchivo = new ArchivoJson($ubicacionUsuarios);
                $retornoBusqueda = $manejadorArchivo->ValidarUsuario($email, $clave);
                if($retornoBusqueda->valido){
                    $usuarioGuardar = $retornoBusqueda->contenido;
                    unset($usuarioGuardar->clave);
                    $manejadorToken = new TokenJwt();
                    echo $manejadorToken->SolicitarToken($usuarioGuardar);                    
                }
                else{
                    echo "usuario invalido";
                }
            }
            else{
                echo "llene los campos";
            }
        }
        break;
    case '/pizzas':
        $headers = getallheaders();
        if($metodo=="POST"){
            if(isset($_POST["tipo"], $_POST["precio"], $_POST["stock"], $_POST["sabor"], $_FILES["foto"], $headers["token"])){

                $token =  $headers["token"];
                $precio = $_POST["precio"];
                $stock = $_POST["stock"];
                $sabor = $_POST["sabor"];
                $tipo = $_POST["tipo"];
                $fileKey = $_FILES["foto"];
                if( ($tipo=="molde" || $tipo=="piedra") && ($sabor=="jamon"|| $sabor=="napo" || $sabor=="muzza")  ){

                    $manejadorToken = new TokenJwt();
                    try{
                        $usuario = $manejadorToken->MostrarDatos($token);
                        if($usuario->tipo=="encargado"){
                            $pizzaGuardar = new Pizza($tipo, $sabor, $precio, $stock);
                            $manejadorArchivo = new ArchivoJson($ubicacionPizzas);
                            $listaPizzas = $manejadorArchivo->LeerArchivo("r");
                            $estaIncluida = Pizza::pizzaEstaIncluida($pizzaGuardar->tipo, $pizzaGuardar->sabor, $listaPizzas);
                            if(!$estaIncluida){
                                $caracteres = $manejadorArchivo->EscribirArchivo("w", $pizzaGuardar);
                                Pizza::guardarImagen($fileKey);
                                $retorno = new RespuestaJson("ok", $caracteres);
                                echo json_encode($retorno);
                            }
                            else{
                                echo "Es combinacion ya esta incluida";
                            }                            
    
                        }
                        else{
                            echo "no tiene permiso";
                        }
                    }
                    catch(Exception $err){
                        echo $err->getMessage();
                    }
                }
                else{
                    echo "tipo o sabor erroneo";
                }
            }
        }
        else if($metodo=="GET"){
            $headers = getallheaders();
            if(isset($headers["token"])){
                $token = $headers["token"];
                try{
                    $manejadorToken = new TokenJwt();
                    $manejadorArchivo = new ArchivoJson($ubicacionPizzas);
                    $usuario = $manejadorToken->MostrarDatos($token);     
                    $listaPizzas = $manejadorArchivo->LeerArchivo("r");
                    if($usuario->tipo=="encargado"){
                        echo json_encode($listaPizzas);
                    }
                    else{
                        foreach ($listaPizzas as $key => $pizza) {
                            unset($pizza->stock);
                        }
                        echo json_encode($listaPizzas);
                    }
                }
                catch(Exception $err){
                    echo $err->getMessage();
                }
            }
            else{
                echo "ingrese token";
            }            
        }

        break;
    case '/ventas':
        $headers = getallheaders();
        if($metodo=="POST"){
            if(isset($_POST["tipo"], $_POST["sabor"], $headers["token"])){
                $token = $headers["token"];
                $tipo=$_POST["tipo"];
                $sabor=$_POST["sabor"];

                try{
                    $manejadorToken = new TokenJwt();
                    $usuario = $manejadorToken->MostrarDatos($token);
                    if($usuario->tipo=="cliente"){
                        Pizza::VenderPizza($ubicacionPizzas, $tipo, $sabor, $ubicacionVentas, $usuario->email);
                    }
                    else{
                        echo "no tiene permiso";
                    }
                }
                catch(Exception $err){
                    echo $err->getMessage();
                }                 
            }
            else{
                echo "Ingrese campos";
            }
        }
        else if($metodo=="GET"){
            $headers = getallheaders();
            if(isset($headers["token"])){
                $token=$headers["token"];
                try{
                    $manejadorToken = new TokenJwt();
                    $usuario = $manejadorToken->MostrarDatos($token);
                    $manejadorArchivo = new ArchivoJson($ubicacionVentas);
                    $listaVentas = $manejadorArchivo->LeerArchivo("r");
                    if($usuario->tipo=="encargado"){
                        
                        echo json_encode($listaVentas);
                    }
                    else{
                        $retorno = Venta::BuscarVentasUsuario($usuario, $listaVentas);
                        echo json_encode($retorno);
                    }
                }
                catch(Exception $err){
                    echo $err->getMessage();
                }  
            }
            else{
                echo "ingrese token";
            }

        }

        break;

    default:
        # code...
        break;
}