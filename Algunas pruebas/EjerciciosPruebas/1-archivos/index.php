<?php

$archivo = fopen("archivoNuevo.txt", "r+");

//fwrite($archivo, "jose".PHP_EOL." argenti");




/******* LEER*/
while(!feof($archivo)){
    echo fgets($archivo)."<br>";
}
 

fclose($archivo);


/******** Copiar, Eliminar

copy("archivoNuevo.txt", "acaCopio.txt");

unlink("acaCopio.txt");

*/