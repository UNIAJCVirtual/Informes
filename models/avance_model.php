<?php

class avance{

    // Variables que no son evaluadas en el informe y son unicamente a forma de consulta.
    
    private $idUser;
    private $programa;
    private $semestre;
    public $nombreCurso;
    private $nombreProfesor;
    private $correo;
    public $items=[];
    public $evaluacion=[];
    public $porcentaje;

     // Funciones get y set de la variable idUser

     public function getIdUser(){
        return $this->idUser;
    }

    public function setIdUser($id){
        $this->idUser = $id;
    }

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

    // Funciones get y set de la variable correo

    public function getCorreo(){
        return $this->correo;
    }

    public function setCorreo($email){
        $this->correo = $email;
    }
    // Funciones get y set de la variable porcentaje

    public function getPorcentaje(){
        return $this->porcentaje;
    }

    public function setPorcentaje($percentage){
        $this->porcentaje = $percentage;
    }
}


?>