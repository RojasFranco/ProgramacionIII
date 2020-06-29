<?php

interface IApi{
    
    public function TraerUno($request, $response, $args);

    public function TraerTodos($request, $response, $args);
    
    public function InsertarUno($request, $response);

    public function ActualizarUno($request, $response);

    public function BorrarUno($request, $response);
}