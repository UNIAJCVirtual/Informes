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

	$vector_curso = [];
	$vector_semestre = [];
	$vector_grupo = [];
	$vector_codigo = [];
	$vector_idCurso = [];
	$vector_estudiantes = [];
	$vector_profesor = [];
	$vector_programa = [];

	$semesters = Semesters(implode(",", $program));

	if ($semesters) {

		foreach ($semesters as $semester) {
			$programName = ProgramsName(implode(",", $program));
			$semesterName = $semester["name"];
			$coursesInformation = CoursesInformation($semester["id"]);
			
			while ($courseInfo = $coursesInformation->fetch_assoc()) {
				$course = new estadistica();
				$teachersNames="";

				//la variable requerida en la función Enrolled es el rol que vamos a buscar 3 Profesor y 5 Estudiante
				$teachers = Usersquantity($courseInfo['course_id'], 3);
				$students = Usersquantity($courseInfo['course_id'], 5);
				
				while($teacher = $teachers->fetch_assoc()){
					$teachersNames .= ($teachers->num_rows== 1 ) ? 
					ucwords(mb_strtolower($teacher['firstname'],'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'],'UTF-8')) : 
					ucwords(mb_strtolower($teacher['firstname'],'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'],'UTF-8'))." - ";
				}
				while($student = $students->fetch_assoc()){
					$vector_estudiantes [] = $student['user_id'];
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
				$vector_programa []= $course->getPrograma();
				$vector_semestre[] = $course->getSemestre();
				$vector_grupo[] = $course->getGrupo();
				$vector_idCurso[] = $course->getIdCurso();
				$vector_codigo[] = $course->getCodigo();
				$vector_profesor[] = $course->getNombreProfesor();
				$vector_curso[] = $course;
			}
		}
	}
	echo("
		<div class='title-estadist'>
			<p>ESTADISTICA DE LOS CURSOS EN</p>
			<h2>AULAS VIRTUALES MOODLE</h2>
		</div>
		<table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
			<thead class='td2'>
				<th class='td2'>Fecha</th>
				<th class='td2'>Semestre</th>
				<th class='td2'>Grupo</th>
				<th class='td2'>ID Curso</th>
				<th class='td2'>Código Asignatura</th>
				<th class='td2'>Nombre Asignatura</th>
				<th class='td2'>N. Estudiantes</th>
				<th class='td2'>Profesor a Cargo</th>
				<th class='td2'>Programa</th>
			</thead>
			<tbody>
		");
		$cantidadSemestres  = count(elementosUnicos($vector_semestre));
		$cantidadGrupos = count(elementosUnicos($vector_grupo));
		$cantidadCodigos = count(elementosUnicos($vector_codigo));
		$cantidadProfesores = count(elementosUnicos($vector_profesor));
		$cantidadEstudiantes = count(elementosUnicos($vector_estudiantes)) - $cantidadProfesores;
		$cantidadProgramas = count(elementosUnicos($vector_programa));
		$cantidadCursos = count(elementosUnicos($vector_idCurso));
		$cantidadRepetidos = count($vector_idCurso) - $cantidadCursos;
		date_default_timezone_set("America/Bogota");
		$fecha = date("Y-m-d H:i:s");

	foreach ($vector_curso as $curse) {
		echo ("
		<tr class='tr5'>
		<td class='tr5'>" . $fecha . "</td>
		<td class='tr5'>" . $curse->getSemestre() . "</td>
		<td class='tr5'>" . $curse->getGrupo() . "</td>
		<td class='tr5'>" . $curse->getIdCurso() . "</td>
		<td class='tr5'>" . $curse->getCodigo() . "</td>
		<td class='tr5'>" . $curse->getNombreCurso() . "</td>
		<td class='tr5'>" . $curse->getEstudiantes() . "</td>
		<td class='tr5'>" . $curse->getNombreProfesor() . "</td>
		<td class='tr5'>" . $curse->getPrograma() . "</td>");
	}
	echo "
		<tr class='td2'>
		<td class='td2'>Total</td>
		<td class='td2'>" . $cantidadSemestres . "</td>
		<td class='td2'>" . $cantidadGrupos . "</td>
		<td class='td2'>" . $cantidadRepetidos . "</td>
		<td class='td2'>" . $cantidadCodigos . "</td>
		<td class='td2'>" . $cantidadCursos . "</td>
		<td class='td2'>" . $cantidadEstudiantes . "</td>
		<td class='td2'>" . $cantidadProfesores . "</td>
		<td class='td2'>" . $cantidadProgramas . "</td>
		</tr>
	</tbody>
	</table>
	";
	echo ("
	<div class='container-items-porcent'>
        <div class='item-porcent td2'><span>Programas	|</span><h5>" . $cantidadProgramas . "</h5></div>
        <div class='item-porcent td2'><span>Grupos |</span>		<h5>" . $cantidadGrupos . "</h5></div>
        <div class='item-porcent td2'><span>Cursos  |</span>     <h5>" . $cantidadCursos . "</h5></div>
        <div class='item-porcent td2'><span>Cursos repetidos |</span>     <h5>" . $cantidadRepetidos . "</h5></div>
        <div class='item-porcent td2'><span>Estudiantes   |</span>		<h5>" . $cantidadEstudiantes . "</h5></div>
        <div class='item-porcent td2'><span>Profesores	|</span><h5>" . $cantidadProfesores . "</h5></div>
   	</div>
	");
}
