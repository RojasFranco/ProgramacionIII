<?php
/*
Crear una API rest con las siguientes rutas:
1- POST signin: recibe email, clave, nombre, apellido, telefono y tipo (user, admin) y lo guarda en un archivo.
2- POST login: recibe email y clave y chequea que existan, si es así retorna un JWT de lo contrario informa el error (si el email o la clave están equivocados) .
A PARTIR DE AQUI TODAS LAS RUTAS SON AUTENTICADAS.
3- GET detalle: Muestra todos los datos del usuario actual.
4- GET lista: Si el usuario es admin muestra todos los usuarios, si es user solo los del tipo user.
*/


require_once __DIR__ . '/composer/vendor/autoload.php';
include_once './clases/usuario.php';
include_once './clases/respuestaJson.php';
include_once './clases/archivosJson.php';
include_once './clases/tokenJwt.php';

$metodo = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];
$ubicacionArchivo = "./archivos/archivo.txt";

switch ($path_info) {
    case '/signin':
        if($metodo=="POST"){
            if(isset($_POST["nombre"], $_POST["apellido"], $_POST["clave"],
            $_POST["email"], $_POST["telefono"], $_POST["tipo"])){
                $nombre = $_POST["nombre"];
                $apellido = $_POST["apellido"];
                $email = $_POST["email"];
                $clave = $_POST["clave"];
                $telefono = $_POST["telefono"];
                $tipo = $_POST["tipo"];
                $usuarioAgregar = new Usuario($nombre, $apellido,$email,$clave, $telefono,$tipo);
                
                $manejadorArchivo = new ArchivoJson($ubicacionArchivo);
                $retornoData = $manejadorArchivo->EscribirArchivo("w", $usuarioAgregar);
                $respuesta= new RespuestaJson("ok", $retornoData);
                echo json_encode($respuesta);
            }
            else{
                echo "Llene los campos necesarios: nombre-apellido-email-clave-telefono-tipo";            
            }
        }
        else{
            echo "Seleccione metodo valido";
        }
        break;
    case '/login':
        if($metodo=="POST"){

            if(isset($_POST["email"], $_POST["clave"])){
                $mail = $_POST["email"];
                $clave = $_POST["clave"];                
                $manejadorArchivo = new ArchivoJson($ubicacionArchivo);
                $respuestaValidarUsuario = $manejadorArchivo->ValidarUsuario($mail, $clave);
                if($respuestaValidarUsuario->valido)
                {                    
                    $datosUsuario = $respuestaValidarUsuario->contenido;
                    unset($datosUsuario->clave); // PARA NO GUARDAR CLAVE EN PAYLOAD
                    $tokenJwt = new TokenJwt();
                    echo $tokenJwt->SolicitarToken($datosUsuario);                                        
                }
                else
                {
                    echo $respuestaValidarUsuario->contenido;
                }
            }
            else{
                echo "Llene los campos necesarios: mail y clave";
            }
        }
        else{
            echo "Seleccione metodo valido";
        }
        break;
    case '/detalle': 
        if($metodo=="GET"){      
            $headers = getallheaders();            
            if(isset($headers["token"])){    
                $token = $headers["token"];
                if(!empty($token))
                {
                    $manejadorToken = new TokenJwt();
                    try{
                        $datosRecibidos=$manejadorToken->MostrarDatos($token);
                        $respuesta = new RespuestaJson("ok", $datosRecibidos);
                        echo json_encode($respuesta);
                    }                    
                    catch(Exception $error){
                        echo "error en mostrar datos con token enviado";
                    }
                }                
                else{
                    echo "el token esta vacio";
                }                
            }
            else{
                echo "No ingreso datos, ingrese su token";
            }
        }
        else{
            echo "Metodo invalido";
        }
        break;
    case '/lista':
        if($metodo=="GET"){
            $headers = getallheaders();
            if(isset($headers["token"])){
                $token = $headers["token"];
                if(!empty($token))
                {
                    $manejadorToken = new TokenJwt();
                    try{
                        $usuarioIngresado=$manejadorToken->MostrarDatos($token);
                        $manejadorArchivo = new ArchivoJson($ubicacionArchivo);
                        $listaUsuarios = $manejadorArchivo->LeerArchivo("r");
                        $retornoLista = $manejadorArchivo->ListaUsuariosPorTipo($usuarioIngresado->tipo);
                        $respuesta = new RespuestaJson("ok", $retornoLista);
                        echo json_encode($respuesta);
                    }                    
                    catch(Exception $error){
                        //echo "error en mostrar datos con token enviado";                        
                        echo $error->getMessage();
                    }
                }                
                else{
                    echo "el token esta vacio";
                }       
            }
        }        
        else{
            echo "Metodo invalido";
        }
        break;
    default:
        echo "path_info invalido";
        break;
}