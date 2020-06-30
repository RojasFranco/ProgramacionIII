<?php

namespace App\Models;

class TipoMascota extends \Illuminate\Database\Eloquent\Model{


    //  protected $table = 'nombreTabla'; SI EL NOMBRE DE LA TABLA NO ES Ejemplo + s
    protected $table = 'tipo_mascota';
    //  protected $primaryKey = 'idName';   SI EL ID NO SE LLAMA 'id'
    public $timestamps = false;
}