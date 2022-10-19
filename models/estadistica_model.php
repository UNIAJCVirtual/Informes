<?php
class estadistica{

    // Variables que no son evaluadas en el informe y son unicamente a forma de consulta.

    private $programa;
    private $semestre;
    public $nombreCurso;
    private $nombreProfesor;
    public $grupo;
    private $codigo;
    private $cantidad;

    // Funciones get y set de la variable programa

    public function getPrograma(){
        return $this->programa;
    }
    
    public function setPrograma($program){
        $this->programa = $program;
    }

    // Funciones get y set de la variable semestre

    public function getSemestre(){
        return $this->semestre;
    }
    
    public function setSemestre($semet){
        $this->semestre = $semet;
    }

    // Funciones get y set de la variable nombreCurso

    public function getNombreCurso(){
        return $this->nombreCurso;
    }
    
    public function setNombreCurso($nameCourse){
        $this->nombreCurso = $nameCourse;
    }

    // Funciones get y set de la variable nombreProfesor

    public function getNombreProfesor(){
        return $this->nombreProfesor;
    }
    
    public function setNombreProfesor($nameTeacher){
        $this->nombreProfesor = $nameTeacher;
    }

    // Funciones get y set de la variable grupo

    public function getGrupo(){
        return $this->grupo;
    }

    public function setGrupo($grup){
        $this->grupo = $grup;
    }

     // Funciones get y set de la variable codigo

     public function getCodigo(){
        return $this->codigo;
    }

    public function setCodigo($code){
        $this->codigo = $code;
    }

    // Funciones get y set de la variable cantidad

    public function getCantidad(){
        return $this->cantidad;
    }

    public function setCantidad($cant){
        $this->cantidad = $cant;
    }
}

?>