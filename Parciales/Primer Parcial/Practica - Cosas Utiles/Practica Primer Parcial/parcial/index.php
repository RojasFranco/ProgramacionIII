<?php

require_once __DIR__ . '/composer/vendor/autoload.php';
require_once './Clases/usuario.php';
require_once './Clases/archivo.php';
require_once './Clases/respuestaJson.php';

require_once './Clases/tokenJwt.php';
require_once './Clases/producto.php';
require_once './Clases/venta.php';

$metodo = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];

$ubicacionArchivoUsuarios = "./Archivos/archivoUsuarios.txt";
$ubicacionArchivoProductos = "./Archivos/archivoProducto.json";
//$ubicacionImagenes = "./Imagenes";

$ubicacionVentas = "./Archivos/ventas.xxx";

switch ($path_info) {
    case '/usuario':
        if($metodo == "POST"){
            if(isset($_POST["nombre"],$_POST["dni"],$_POST["tipo"],$_POST["obra_social"],$_POST["clave"])){
                $nombre = $_POST["nombre"];
                $dni = $_POST["dni"];
                $tipo = $_POST["tipo"];
                $obraSocial = $_POST["obra_social"];
                $clave = $_POST["clave"];

                $usuario = new Usuario($nombre, $dni, $clave, $tipo, $obraSocial);
                $caracteresEscritos = Usuario::GuardarUsuario($ubicacionArchivoUsuarios, $usuario);
                $retorno = new RespuestaJson("ok",$caracteresEscritos);
                echo json_encode($retorno);
            }
        }
        else{
            echo "metodo invalido";
        }
        # code...
        break;
    case '/login':
        if($metodo=="POST"){
            if(isset($_POST["nombre"], $_POST["clave"])){
                $nombre = $_POST["nombre"];
                $clave = $_POST["clave"];

                $manejadorArchivo = new ArchivoJson($ubicacionArchivoUsuarios);
                $retornoBusqueda = $manejadorArchivo->ValidarUsuario($nombre, $clave);
                if($retornoBusqueda->valido){
                    $usuarioGuardar = $retornoBusqueda->contenido;
                    unset($usuarioGuardar->clave);                    
                    echo TokenJwt::SolicitarToken($usuarioGuardar);
                }
                else{
                    echo "usuario invalido";
                }

            }
        }
        break;
    case '/stock':
        if($metodo=="POST"){
            $headers = getallheaders();            
            if(isset($_POST["producto"], $_POST["marca"], $_POST["precio"],$_POST["stock"],$headers["token"], $_FILES["foto"])){
                $token = $headers["token"];
                $producto = $_POST["producto"];
                $marca = $_POST["marca"];
                $precio = $_POST["precio"];
                $stock = $_POST["stock"];                
                $fileKey = $_FILES["foto"];
                
                $manejadorToken = new TokenJwt();
                try
                {
                    $usuario = TokenJwt::MostrarDatos($token);
                    if($usuario->tipo=="admin"){
                        $productoGuardar = new Producto($producto, $marca, $precio, $stock);
                        $manejadorArchivo = new ArchivoJson($ubicacionArchivoProductos);
                        $cantCaracteres = $manejadorArchivo->EscribirArchivo("w", $productoGuardar);
                        Producto::guardarImagen($fileKey);//, $ubicacionImagenes);
                        $retorno = new RespuestaJson("ok", $cantCaracteres);
                        echo json_encode($retorno);

                    }
                    else{
                        echo "no tiene permiso";
                    }
                }
                catch(Exception $err){
                    echo $err->getMessage();
                }
            }
            else
            {
                echo "ingrese los datos";
            }
        }
        else if($metodo == "GET"){
            $headers = getallheaders();
            if(isset($headers["token"])){
                $token = $headers["token"];
                $manejadorToken = new TokenJwt();
                try
                {
                   $usuario = $manejadorToken->MostrarDatos($token);
                   if($usuario->tipo=="admin"){                    
                        $manejadorArchivo = new ArchivoJson($ubicacionArchivoProductos);
                        $listaProductos = $manejadorArchivo->LeerArchivo("r");
                        $retorno = new RespuestaJson("ok", $listaProductos);
                        echo json_encode($retorno);
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
                echo "ingrese token";
            }
            $manejadorToken = new TokenJwt();
        }
        break;

        case '/ventas':
            if($metodo == "POST"){
                $headers = getallheaders();
                if(isset($_POST["id_producto"], $_POST["cantidad"], $_POST["ususario"])){
                    $idProducto = $_POST["id_producto"];
                    $cantidad = $_POST["cantidad"];
                    $ususario = $_POST["ususario"];
                    
                    $token = $headers["token"];
                    $manejadorToken = new TokenJwt();
                    try
                    {
                    $usuario = $manejadorToken->MostrarDatos($token);
                    if($usuario->tipo=="user"){                    
                        $manejadorArchivo = new ArchivoJson($ubicacionArchivoProductos);
                        //$manejadorArchivo->Val
                        $productoVender = $manejadorArchivo->ValidarProducto($idProducto);
                        if($productoVender->valido){
                            $producto = $productoVender->contenido;
                            if($producto->stock>0 && $producto->stock>=$cantidad){
                                $precioTotal = ($producto->precio)*($cantidad);

                                $ventaprod = new Venta($idProducto, $cantidad, $ususario);                                
                                $manejadorArchivoVentas = new ArchivoJson($ubicacionVentas);
                                $manejadorArchivoVentas->EscribirArchivoSerializado("w", $ventaprod);                                
                                $retorno = new RespuestaJson("ok", $precioTotal);
                                $manejadorArchivo->ModificarArchivoProducto("w",$idProducto,$producto->stock-$cantidad);
                                echo json_encode($retorno);
                            }
                            else{
                                echo "la cantidad es mayor al stock disponible";
                            }
                        }
                        else{
                            echo "no existe id de producto";
                        }

                        //echo json_encode($retorno);
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
                    echo "llene los campos";
                }
            }
            else if($metodo=="GET"){
                $headers = getallheaders();
                if(isset($headers["token"])){
                    $token = $headers["token"];
                    $manejadorToken = new TokenJwt();
                    try{
                        $usuario = $manejadorToken->MostrarDatos($token);
                        $manejadorArchivo = new ArchivoJson($ubicacionVentas);
                        if($usuario->tipo=="admin"){                            
                            $listaProductos = $manejadorArchivo->MostrarArchivoDeserealizado();
                            echo json_encode($listaProductos);
                        }
                        else{
                            echo "no tiene permisos";
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