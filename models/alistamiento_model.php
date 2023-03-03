<?php

class alistamiento{

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
    public $unidades=[];

    // Variables del modulo "Libro de calificaciones"
    
    private $af01Actividades;
    private $af01Ponderaciones;
    // private $af01Disponibilidad;

    private $af02Actividades;
    private $af02Ponderaciones;
    // private $af02Disponibilidad;

    private $af03Actividades;
    private $af03Ponderaciones;
    // private $af03Disponibilidad;

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

    public function setForoConsulta($form){
        $this->foroConsulta = $form;
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

    // Funciones get y set de la variable af01Disponibilidad
/*
    public function getAF01Disponibilidad(){
        return $this->af01Disponibilidad;
    }

    public function setAF01Disponibilidad($AF01D){
        $this->af01Disponibilidad = $AF01D;
    }
    */
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

    // Funciones get y set de la variable af02Disponibilidad
/*
    public function getAF02Disponibilidad(){
        return $this->af02Disponibilidad;
    }

    public function setAF02Disponibilidad($AF02D){
        $this->af02Disponibilidad = $AF02D;
    }
*/
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

    // Funciones get y set de la variable af03Disponibilidad
/*
    public function getAF03Disponibilidad(){
        return $this->af03Disponibilidad;
    }

    public function setAF03Disponibilidad($AF03D){
        $this->af03Disponibilidad = $AF03D;
    }
*/
    // Funciones get y set de la variable porcentaje

    public function getPorcentaje(){
        return $this->porcentaje;
    }

    public function setPorcentaje($percentage){
        $this->porcentaje = $percentage;
    }

}
