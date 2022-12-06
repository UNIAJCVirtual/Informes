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

	$vector_curso = [];
	$vector_semestre = [];
	$vector_grupo = [];
	$vector_codigo = [];
	$vector_estudiantes = [];
	$vector_programa = [];
	$vector_profesor = [];
	$vector_idCurso = [];
	$cantidadEstudiantes=0;
	
	$categoriesResult = Categories(implode(",", $program));

	if ($categoriesResult) {

		foreach ($categoriesResult as $val) {
			$result = NameCategory($val['id']);
			$semester = $result["name"];
			$programa = Program($result['parent']);
			$result = StatisticsInformation($val['id']);
			$estudiantes = StatisticsInformation2($val['id']);
			
			while ($totalestudiantes = $estudiantes->fetch_assoc()) {
				$vector_estudiantes[]=$totalestudiantes['user_id'];
			}

			while ($column = $result->fetch_assoc()) {

				
				$curso = new estadistica();
				//la variable requerida en la función Enrolled es el rol que vamos a buscar 3 Profesor y 5 Estudiante
				$profesorMatricula= Enrolled($column['course_id'], 3);
				$profesoresMatriculados = $profesorMatricula->fetch_assoc();

				$estudianteMatricula= Enrolled($column['course_id'], 5);
				$estudiantesMatriculados = $estudianteMatricula->fetch_assoc();
				//---------

				$curso->setPrograma($programa);
				$curso->setIdCurso($column['course_id']);
				$codigo = explode("Conv_", $column['course_fullname']);
				$curso->setCodigo($codigo[count($codigo) - 1]);
				$curso->setSemestre($semester);
				$curso->setGrupo(substr($column['course_fullname'],7,6));
				$curso->setNombreCurso($codigo[0]);
				$curso->setNombreProfesor(ucwords(mb_strtolower($column['firstname'],'UTF-8')) . " " . ucwords(mb_strtolower($column['lastname'],'UTF-8')));
				$curso->setEstudiantes($estudiantesMatriculados['matriculados']-$profesoresMatriculados['matriculados']);
				$cantidadEstudiantes += $curso->getEstudiantes();
				$vector_programa []= $curso->getPrograma();
				$vector_semestre []= $curso->getSemestre();
				$vector_grupo[] = $curso->getGrupo();
				$vector_codigo[] = $curso->getCodigo();
				$vector_idCurso[] = $curso->getIdCurso();
				$vector_profesor[] = $curso->getNombreProfesor();
				$vector_curso[] = $curso;
			}
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
		$cantidadSemestres  = count(elementosUnicos($vector_semestre));
		$cantidadGrupos = count(elementosUnicos($vector_grupo));
		$cantidadCodigos = count(elementosUnicos($vector_codigo));
		$cantidadProfesores = count(elementosUnicos($vector_profesor));
		$cantidadProgramas = count(elementosUnicos($vector_programa));
		$cantidadCursos = count(elementosUnicos($vector_idCurso));
		$cantidadRepetidos = count($vector_idCurso) - $cantidadCursos;

	date_default_timezone_set("America/Bogota");
	$fecha = date("Y-m-d H:i:s");

	foreach ($vector_curso as $curse) {
		echo ("
		<tr class='tr5'>
		<td class='tr5'>".$fecha."</td>
		<td class='tr5'>" . $curse->getPrograma() . "</td>
		<td class='tr5'>" . $curse->getSemestre() . "</td>
		<td class='tr5'>" . $curse->getIdCurso() . "</td>
		<td class='tr5'>" . $curse->getGrupo() . "</td>
		<td class='tr5'>" . $curse->getCodigo() . "</td>
		<td class='tr5'>" . $curse->getNombreCurso() . "</td>
		<td class='tr5'>" . $curse->getEstudiantes() . "</td>
		<td class='tr5'>" . $curse->getNombreProfesor() . "</td>");
	}
	echo("
			<tr class='td2'>
			<td class='td2'>Total</td>
			<td class='td2'>" . $cantidadProgramas . "</td>
			<td class='td2'>" . $cantidadSemestres . "</td>
			<td class='td2'>" . $cantidadRepetidos . "</td>
			<td class='td2'>" . $cantidadGrupos . "</td>
			<td class='td2'>" . $cantidadCodigos . "</td>
			<td class='td2'>" . $cantidadCursos . "</td>
			<td class='td2'>" . $cantidadEstudiantes . "</td>
			<td class='td2'>" . $cantidadProfesores . "</td>
			</tr>
		</tbody>
		</table>
		");
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
