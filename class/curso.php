<?php

class curso {


    // Variables que no son evaluadas en el informe y son unicamente a forma de consulta.

    private $idUser;
    private $nombre;
    private $correo;
    private $programa;
    private $semestre;
    private $idCurso;
    private $nombreCurso;

    // Variables que serán evaluadas en el informe de Alistamiento
    
    // Variables del modulo "Presentación del profesor"

    private $nombreProfesor;
    private $correoProfesor;
    private $horarioAtencion;
    private $fotografia;

    // Variables del modulo "Foro de consulta y fechas de las unidades"

    private $foroConsulta;
    private $fechasUnidad1="NO APLICA";
    private $fechasUnidad2="NO APLICA";
    private $fechasUnidad3="NO APLICA";
    private $fechasUnidad4="NO APLICA";
    private $fechasUnidad5="NO APLICA";
    private $fechasUnidad6="NO APLICA";
    private $fechasUnidad7="NO APLICA";
    private $fechasUnidad8="NO APLICA";

    // Variables del modulo "Libro de calificaciones"

    private $af01Actividades;
    private $af01Ponderaciones;

    private $af02Actividades;
    private $af02Ponderaciones;

    private $af03Actividades;
    private $af03Ponderaciones;

    // Variable donde se guardan los resultados del informe de cada curso va del 0% al 100%

    public $porcentaje;

    // Métodos para consultar y editar las variables antes mencionadas

    // Funciones get y set de la variable idUser

    public function getIdUser(){
        return $this->idUser;
    }

    public function setIdUser($id){
        $this->idUser = $id;
    }

    // Funciones get y set de la variable nombre

    public function getNombre(){
        return $this->nombre;
    }
    
    public function setNombre($name){
        $this->nombre = $name;
    }

    // Funciones get y set de la variable correo

    public function getCorreo(){
        return $this->correo;
    }
    
    public function setCorreo($email){
        $this->correo = $email;
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

    // Funciones get y set de la variable idCurso

    public function getIdCurso(){
        return $this->idCurso;
    }
    
    public function setIdCurso($idCourse){
        $this->idCurso = $idCourse;
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

    // Funciones get y set de la variable correoProfesor

    public function getCorreoProfesor(){
        return $this->correoProfesor;
    }

    public function setCorreoProfesor($emailTeacher){
        $this->correoProfesor = $emailTeacher;
    }

    // Funciones get y set de la variable horarioAtencion

    public function getHorarioAtencion(){
        return $this->horarioAtencion;
    }

    public function setHorarioAtencion($attentionHours){
        $this->horarioAtencion = $attentionHours;
    }

    // Funciones get y set de la variable fotografia

    public function getFotografia(){
        return $this->fotografia;
    }

    public function setFotografia($photo){
        $this->fotografia = $photo;
    }

    // Funciones get y set de la variable foroConsulta

    public function getForoConsulta(){
        return $this->foroConsulta;
    }

    public function setForoConsulta($consultForum){
        $this->foroConsulta = $consultForum;
    }

     // Funciones get y set de la variable fechasUnidad1

     public function getFechasUnidad1(){
        return $this->fechasUnidad1;
    }

    public function setFechasUnidad1($dateUnid1){
        $this->fechasUnidad1 = $dateUnid1;
    }

     // Funciones get y set de la variable fechasUnidad2

     public function getFechasUnidad2(){
        return $this->fechasUnidad2;
    }

    public function setFechasUnidad2($dateUnid2){
        $this->fechasUnidad2 = $dateUnid2;
    }

     // Funciones get y set de la variable fechasUnidad3

     public function getFechasUnidad3(){
        return $this->fechasUnidad3;
    }

    public function setFechasUnidad3($dateUnid3){
        $this->fechasUnidad3 = $dateUnid3;
    }

     // Funciones get y set de la variable fechasUnidad4

     public function getFechasUnidad4(){
        return $this->fechasUnidad4;
    }

    public function setFechasUnidad4($dateUnid4){
        $this->fechasUnidad4 = $dateUnid4;
    }

     // Funciones get y set de la variable fechasUnidad5

     public function getFechasUnidad5(){
        return $this->fechasUnidad5;
    }

    public function setFechasUnidad5($dateUnid5){
        $this->fechasUnidad5 = $dateUnid5;
    }

     // Funciones get y set de la variable fechasUnidad6

     public function getFechasUnidad6(){
        return $this->fechasUnidad6;
    }

    public function setFechasUnidad6($dateUnid6){
        $this->fechasUnidad6 = $dateUnid6;
    }

     // Funciones get y set de la variable fechasUnidad7

     public function getFechasUnidad7(){
        return $this->fechasUnidad7;
    }

    public function setFechasUnidad7($dateUnid7){
        $this->fechasUnidad7 = $dateUnid7;
    }

     // Funciones get y set de la variable fechasUnidad8

     public function getFechasUnidad8(){
        return $this->fechasUnidad8;
    }

    public function setFechasUnidad8($dateUnid8){
        $this->fechasUnidad8 = $dateUnid8;
    }

     // Funciones get y set de la variable af01Actividades

     public function getAF01Actividades(){
        return $this->af01Actividades;
    }

    public function setAF01Actividades($AF01A){
        $this->af01Actividades = $AF01A;
    }

    // Funciones get y set de la variable af01Ponderaciones

    public function getAF01Ponderaciones(){
        return $this->af01Ponderaciones;
    }

    public function setAF01Ponderaciones($AF01P){
        $this->af01Ponderaciones = $AF01P;
    }

     // Funciones get y set de la variable af02Actividades

     public function getAF02Actividades(){
        return $this->af02Actividades;
    }

    public function setAF02Actividades($AF02A){
        $this->af02Actividades = $AF02A;
    }

    // Funciones get y set de la variable af02Ponderaciones

    public function getAF02Ponderaciones(){
        return $this->af02Ponderaciones;
    }

    public function setAF02Ponderaciones($AF02P){
        $this->af02Ponderaciones = $AF02P;
    }

     // Funciones get y set de la variable af03Actividades

     public function getAF03Actividades(){
        return $this->af03Actividades;
    }

    public function setAF03Actividades($AF03A){
        $this->af03Actividades = $AF03A;
    }

    // Funciones get y set de la variable af03Ponderaciones

    public function getAF03Ponderaciones(){
        return $this->af03Ponderaciones;
    }

    public function setAF03Ponderaciones($AF03P){
        $this->af03Ponderaciones = $AF03P;
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