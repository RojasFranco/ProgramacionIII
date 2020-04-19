<?php

// use function GuzzleHttp\json_decode;

// require_once "./src/pais.php";
// require_once "./src/archivo.php";
// $pais = Pais::TraerPaisPorNombre("Argentina");
// $pais = Pais::TraerPaisesPorContinente("Americas");
// $pais = Pais::TraerPaisesPorSubRegion("ASEAN");
// $pais = Pais::TraerPaisPorCapital("Buenos Aires");
// $pais = Pais::TraerPaisesPorLenguaje("ES");

// Pais::MostrarPaises($pais);
// Pais::MostrarPaisesJson($pais);
$direccion = "datos.json";
$opciones_get = "Utilice una de las siguientes opciones:\r\n/continente?nombre= para traer todos los paises del continente deseado\r\n/subregion?nombre= para traer los paises de la sub-region deseada\r\n/lenguaje?idioma= para traer los paises que utilizan el idioma deseado\r\n/pais?nombre= para buscar el pais deseado\r\n/capital?nombre= para traer el pais con la capital deseada";
$request_method = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];
$response = new STDClass;
$response->status = 'success';

if ($request_method == 'GET') {
    if (empty($_SERVER['PATH_INFO'])) {
        echo $opciones_get;
    } else {
        $path_info = $_SERVER["PATH_INFO"];

        switch ($path_info) {
            case "/personas":
                $archivo = fopen('datos.json','r');
                
                // Leo archivo
                $listaPersonas = fread($archivo, filesize('datos.json'));

                fclose($archivo);
                // echo $listaPersonas;
                // Convierto string a array
                $listaPersonas = json_decode($listaPersonas);


                $retorno = $listaPersonas; // fwrite($archivo, json_encode($persona));

                $response->data = $retorno;

                echo json_encode($response);

                break;
        }
    }
} else if ($request_method == 'POST') {
    // $path_info = $_SERVER["PATH_INFO"];

        switch ($path_info) {
            case "/personas":
                $datos = $_POST['nombre'] . '@' . $_POST['apellido'];
                // Instancio Persona
                $persona = new stdClass;

                $persona->nombre = $_POST['nombre'];
                $persona->apellido = $_POST['apellido'];


                $archivo = fopen('datos.json','r');
                
                // Leo archivo
                $listaPersonas = fread($archivo, filesize('datos.json'));

                // echo $listaPersonas;
                // Convierto string a array
                $listaPersonas = json_decode($listaPersonas);

                // Insertamos persona
                array_push($listaPersonas, $persona);

                $retorno = $listaPersonas; // fwrite($archivo, json_encode($persona));

                fclose($archivo);

                $archivo = fopen('datos.json','w');

                $retorno = fwrite($archivo, json_encode($listaPersonas));

                fclose($archivo);


                // escribo archivo
                $response->data = $retorno;

                echo json_encode($response);
                break;
        }

}
