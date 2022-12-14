<?php
include("../models/estadistica_model.php");

//----------------------------------------------
function elementosUnicos($array)
{
	$arraySinDuplicados = [];
	foreach ($array as $elemento) {
		if (!in_array($elemento, $arraySinDuplicados)) {
			$arraySinDuplicados[] = $elemento;
		}
	}
	return $arraySinDuplicados;
}
//----------------------------------------------
function groupFilter1($str)
{
	$temp = $str[strlen($str) - 1];
	// se empieza del antepenultimo para tener la letra A B C o cualquiera
	for ($i = (strlen($str) - 2); $i >= 0; $i--) {
		if (is_numeric($str[$i])) {
			$temp = $str[$i] . "" . $temp;
		} else {
			if ($i > 0) {
				if ($str[$i] == "I" and $str[$i - 1] == "I") {
					$temp = $str[$i - 1] . "" . $str[$i] . "" . $temp;
					return $temp;
				} else if ($str[$i] == "I" and !($str[$i - 1] == "I")) {
					$temp = $str[$i] . "" . $temp;
					return $temp;
				} else if ($str[$i] == "F" and !($str[$i - 1] == "F")) {
					$temp = $str[$i] . "" . $temp;
					return $temp;
				} else if ($str[$i] == "S" and $i > -1) {
					if ($str[$i - 1] == "B") {
						$temp = $str[$i - 1] . $str[$i] . "" . $temp;
						return $temp;
					}
				} else if ($str[$i] == "S" and $str[$i - 1] == "I") {
					$temp = $str[$i - 1] . "" . $str[$i] . "" . $temp;
					return $temp;
				} else if (($str[$i] == "S" and $str[$i - 1] == "S") or ($str[$i] == "S")) {
					$temp = $str[$i] . "" . $temp;
					return $temp;
				} else {
					return $temp;
				}
			} else if ($i == 0) {
				$temp = $str[$i] . "" . $temp;
				return $temp;
			}
		}
	}
}
function groupFilter($str)
{
	$level = ["X", "I", "V"];
	$temp = "";
	$flag = true;
	$end = false;
	$limit = 0;
	$result = $str[strlen($str) - 1];
	for ($i = (strlen($str) - 2); $i >= 0; $i--) {
		if ($end and $i > $limit) {
			$result = $str[$i] . "" . $str[$i];
		} else {
			if (is_numeric($str[$i])) {
				$result = $str[$i] . "" . $result;
			} else {
				if ($flag) {
					$temp = $result;
					$flag = false;
				}
				if (strlen($str) - strlen($temp) >= 5) {
				} else {
					if (in_array($str[0], $level) and in_array($str[1], $level)) {
						$end = true;
						$limit = 2;
					} else if (in_array($str[0], $level)) {
						$end = true;
						$limit = 1;
					}
				}
			}
		}
	}
	return $result;
}
function codigo($codigo)
{

	if (strstr($codigo, 'FD')) {
		return $codigo = strstr($codigo, 'FD');
	} else if (strstr($codigo, 'CB')) {
		return $codigo = strstr($codigo, 'CB');
	} else if (strstr($codigo, 'FI')) {
		return $codigo = strstr($codigo, 'FI');
	} else if (strstr($codigo, 'CE')) {
		return $codigo = strstr($codigo, 'CE');
	} else if (strstr($codigo, 'CS')) {
		return $codigo = strstr($codigo, 'CS');
	}
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
	$vector_students = [];
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
				while($student = $students->fetch_assoc()){
					$vector_students [] = $student['user_id'];
				}

				$grup = explode("*", $courseInfo['course_name']);
				$course->setSemestre($semesterName);
				$course->setPrograma($programName);
				$course->setCodigo($courseInfo['course_code']);
				$course->setGrupo($grup[count($grup) - 1]);
				$course->setNombreCurso($courseInfo['course_name']);
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
		echo("
			<div class='title-estadist'>
				<p>ESTADISTICA DE LOS CURSOS EN</p>
				<h2>AULAS VIRTUALES MOODLE</h2>
			</div>
			<table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
				<thead class='td2' nowrap>
					<th class='td2' nowrap>Fecha</th>
					<th class='td2' nowrap>Semestre</th>
					<th class='td2' nowrap>Grupo</th>
					<th class='td2' nowrap>ID Curso</th>
					<th class='td2' nowrap>Código Asignatura</th>
					<th class='td2' nowrap>Nombre Asignatura</th>
					<th class='td2' nowrap>N. Estudiantes</th>
					<th class='td2' nowrap>Profesor a Cargo</th>
					<th class='td2' nowrap>Programa</th>
				</thead>
				<tbody>
			");
		$numberSemesters  = count(elementosUnicos($vector_semester));
		$numberGroups = count(elementosUnicos($vector_grup));
		$numberCodes = count(elementosUnicos($vector_course_code));
		$numberTeachers = count(elementosUnicos($vector_teachers));
		$numberStudents = count(elementosUnicos($vector_students)) - $numberTeachers;
		$numberPrograms = count(elementosUnicos($vector_program));
		$numberCourses = count(elementosUnicos($vector_course_id));
		$numberRepeats = count($vector_course_id) - $numberCourses;

		foreach ($vector_course as $curse) {
			echo ("
			<tr class='tr5' nowrap>
			<td class='tr5' nowrap>" . date("Y-m-d H:i:s") . "</td>
			<td class='tr5' nowrap>" . $curse->getSemestre() . "</td>
			<td class='tr5' nowrap>" . $curse->getGrupo() . "</td>
			<td class='tr5' nowrap>" . $curse->getIdCurso() . "</td>
			<td class='tr5' nowrap>" . $curse->getCodigo() . "</td>
			<td class='tr5' >" . $curse->getNombreCurso() . "</td>
			<td class='tr5' nowrap>" . $curse->getEstudiantes() . "</td>
			<td class='tr5' nowrap>" . $curse->getNombreProfesor() . "</td>
			<td class='tr5' nowrap>" . $curse->getPrograma() . "</td>");
		}
		echo "
			<tr class='td2' nowrap>
			<td class='td2' nowrap>Total</td>
			<td class='td2' nowrap>" . $numberSemesters . "</td>
			<td class='td2' nowrap>" . $numberGroups . "</td>
			<td class='td2' nowrap>" . $numberRepeats . "</td>
			<td class='td2' nowrap>" . $numberCodes . "</td>
			<td class='td2' nowrap>" . $numberCourses . "</td>
			<td class='td2' nowrap>" . $numberStudents . "</td>
			<td class='td2' nowrap>" . $numberTeachers . "</td>
			<td class='td2' nowrap>" . $numberPrograms . "</td>
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
			<div class='item-porcent td2'><span>Estudiantes   |</span>		<h5>" . $numberStudents . "</h5></div>
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
