<?php
class Asignacion{
    public $id;
    public $legajo;
    public $turno;

    public function __construct($id, $legajo, $turno)
    {
        $this->id = $id;
        $this->legajo = $legajo;
        $this->turno = $turno;
    }

    public static function AgregarAsignacion($legajo, $turno, $idMateria, $listaAsignaciones){
        
        $manejadorProfesores = new Archivo("./Archivos/profesores.xxx");
        $listaProfesores = $manejadorProfesores->LeerArchivo("r");
        $manejadorlistaMaterias = new Archivo("./Archivos/materias.xxx");
        $listaMaterias = $manejadorlistaMaterias->LeerArchivo("r");
        if((Profesor::esUnico($legajo,$listaProfesores)) || !Materia::MateriaExistente($idMateria,$listaMaterias)){
            return -1;
        }
        foreach ($listaAsignaciones as $key => $asignacion) {
            if($asignacion->legajo==$legajo){
                if($asignacion->turno==$turno && $asignacion->id==$idMateria){
                    return -2;
                }
            }
        }
        return 1;
    }

    public static function MostrarAsignacion($listaAsignaciones){        
        $manejadorMat = new Archivo("./Archivos/materias.xxx");
        $listaMaterias = $manejadorMat->LeerArchivo("r");
        $listaRetornarTerminada = array();
        $manejadorProf = new Archivo("./Archivos/profesores.xxx");
        $listaProfesores = $manejadorProf->LeerArchivo("r");
        
        foreach ($listaAsignaciones as $key => $asignacion) {
            $listaRetorno = new stdClass;
            foreach ($listaMaterias as $key => $materia) {     
                if($materia->idMateria == $asignacion->id)     {
                    $listaRetorno->Materia = $materia->nombre;
                }                      
            }
            foreach ($listaProfesores as $key => $profesor) {
                if($profesor->legajo==$asignacion->legajo){
                    $listaRetorno->Profesor = $profesor->nombre;
                }
            }
            $listaRetorno->Turno = $asignacion->turno;
            array_push($listaRetornarTerminada, $listaRetorno);
        }
        return $listaRetornarTerminada;
    }
}