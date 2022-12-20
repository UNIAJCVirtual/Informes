<?php
include("../models/estadistica_model.php");

//----------------------------------------------

function elementosUnicos($array)
{
    $arraySinDuplicados = [];
    foreach($array as $elemento) {
        if (!in_array($elemento, $arraySinDuplicados)) {
            $arraySinDuplicados[] = $elemento;
        }
    }
    return $arraySinDuplicados;
}

function statistics($program)
{
	include("../services/reportRequest.php");

	date_default_timezone_set("America/Bogota");
	$vector_course = [];
	$vector_semester = [];
	$vector_grup = [];
	$vector_course_code = [];
	$vector_course_id = [];
	$studentsSum = 0;
	$vector_teachers = [];
	$vector_program = [];
	
	$semesters = Semesters(implode(",", $program));

	if ($semesters) {

		foreach ($semesters as $semester) {
			$programName = ProgramsName($semester['parent']);
			$semesterName = $semester['name'];
			$coursesInformation = CoursesInformation($semester['id']);
			
			while ($courseInfo = $coursesInformation->fetch_assoc()) {
				$course = new estadistica();
				$teachersNames="";

				//la variable requerida en la función Enrolled es el rol que vamos a buscar 3 Profesor y 5 Estudiante
				$teachers = Usersquantity($courseInfo['course_id'], 3);
				$students = Usersquantity($courseInfo['course_id'], 5);
				
				while($teacher = $teachers->fetch_assoc()){
					$teachersNames .= ($teachers->num_rows== 1 ) ? 
					ucwords(mb_strtolower($teacher['firstname'],'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'],'UTF-8')) : 
					ucwords(mb_strtolower($teacher['firstname'],'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'],'UTF-8'))." <br> ";
					$vector_teachers [] = $teacher['user_id'];
				}
				$students->fetch_assoc();
				$studentsSum += $students->num_rows - $teachers->num_rows;
				
				$code = explode("Conv_", $courseInfo['course_name']);
				$course->setCodigo($code[count($code) - 1]);
				$course->setSemestre($semesterName);
				$course->setPrograma($programName);
				$course->setGrupo(substr($courseInfo['course_name'],7,6));
				$course->setNombreCurso($code[0]);
				$course->setIdCurso($courseInfo['course_id']);
				$course->setNombreProfesor($teachersNames);
				$course->setEstudiantes($students->num_rows - $teachers->num_rows);
				$vector_program []= $course->getPrograma();
				$vector_semester[] = $course->getSemestre();
				$vector_grup[] = $course->getGrupo();
				$vector_course_id[] = $course->getIdCurso();
				$vector_course_code[] = $course->getCodigo();
				$vector_course[] = $course;
			}
		}
	echo "
	<div class='title-estadist'>
		<p>ESTADISTICA DE LOS CURSOS DE INGLES</p>
		<h2>EN AULAS VIRTUALES MOODLE</h2>
	</div>
	<table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
		<thead class='td2'>
			<th class='td2'>Fecha</th>
			<th class='td2'>Periodo</th>
			<th class='td2'>Semestre</th>
			<th class='td2'>ID Curso</th>
			<th class='td2'>Grupo</th>
			<th class='td2'>Convocatoría</th>
			<th class='td2'>Nombre Asignatura</th>
			<th class='td2'>N. Estudiantes</th>
			<th class='td2'>Profesor a Cargo</th>
		</thead>
		<tbody>		
		";
		$numberSemesters  = count(elementosUnicos($vector_semester));
		$numberGroups = count(elementosUnicos($vector_grup));
		$numberCodes = count(elementosUnicos($vector_course_code));
		$numberTeachers = count(elementosUnicos($vector_teachers));
		$numberPrograms = count(elementosUnicos($vector_program));
		$numberCourses = count(elementosUnicos($vector_course_id));
		$numberRepeats = count($vector_course_id) - $numberCourses;

		foreach ($vector_course as $curse) {
			echo ("
			<tr class='tr5' nowrap>
			<td class='tr5' nowrap>" . date("Y-m-d H:i:s") . "</td>
			<td class='tr5' nowrap>" . $curse->getSemestre() . "</td>
			<td class='tr5' nowrap>" . $curse->getSemestre() . "</td>
			<td class='tr5' nowrap>" . $curse->getIdCurso() . "</td>
			<td class='tr5' nowrap>" . $curse->getGrupo() . "</td>
			<td class='tr5' nowrap>" . $curse->getCodigo() . "</td>
			<td class='tr5' >" . $curse->getNombreCurso() . "</td>
			<td class='tr5' nowrap>" . $curse->getEstudiantes() . "</td>
			<td class='tr5' nowrap>" . $curse->getNombreProfesor() . "</td>");
		}
		echo "
			<tr class='td2' nowrap>
			<td class='td2' nowrap>Total</td>
			<td class='td2' nowrap>" . $numberSemesters . "</td>
			<td class='td2' nowrap>" . $numberSemesters . "</td>
			<td class='td2' nowrap>" . $numberCourses . "</td>
			<td class='td2' nowrap>" . $numberGroups . "</td>
			<td class='td2' nowrap>" . $numberCodes . "</td>
			<td class='td2' nowrap>" . $numberCourses . "</td>
			<td class='td2' nowrap>" . $studentsSum . "</td>
			<td class='td2' nowrap>" . $numberTeachers . "</td>
			</tr>
		</tbody>
		</table>
		";
		echo ("
		<div class='container-items-porcent'>
			<div class='item-porcent td2'><span>Programas	|</span><h5>" . $numberPrograms . "</h5></div>
			<div class='item-porcent td2'><span>Grupos |</span>		<h5>" . $numberGroups . "</h5></div>
			<div class='item-porcent td2'><span>Cursos  |</span>     <h5>" . $numberCourses . "</h5></div>
			<div class='item-porcent td2'><span>Cursos repetidos |</span>     <h5>" . $numberRepeats . "</h5></div>
			<div class='item-porcent td2'><span>Estudiantes   |</span>		<h5>" . $studentsSum . "</h5></div>
			<div class='item-porcent td2'><span>Profesores	|</span><h5>" . $numberTeachers . "</h5></div>
		</div>
		");
	}else{
		echo("
		<div class='title-estadist'>
		<p>ESTADISTICA DE LOS CURSOS EN</p>
		<h2>AULAS VIRTUALES MOODLE</h2>
		</div>");
	}
}
