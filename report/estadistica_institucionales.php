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

function cursoInstitucional($tipo,$nombreCurso)
{
	$nombreCurso= strtolower($nombreCurso);
	if($tipo===0){
		return true;
	}elseif($tipo===1){
		$condicion1 = 'empresarial';
		$condicion2 = 'liderazgo';
		if(strpos($nombreCurso, $condicion1) !== false || strpos($nombreCurso, $condicion2) !== false){
			return true;
		}else{
			return false;
		}
	}elseif($tipo===2){
		$condicion1 = 'empresarial';
		$condicion2 = 'liderazgo';
		if(strpos($nombreCurso, $condicion1) !== false || strpos($nombreCurso, $condicion2) !== false){
			return true;
		}else{
			return false;
		}
	}elseif($tipo===3){
			$condicion1 = 'constitucion';
			$condicion2 = 'ciudadania';
			if(strpos($nombreCurso, $condicion1) !== false || strpos($nombreCurso, $condicion2) !== false){
				return true;
			}else{
				return false;
			}
		}elseif($tipo===4){
				$condicion1 = 'catedra';
				if(strpos($nombreCurso, $condicion1) !== false){
					return true;
				}else{
					return false;
				}
}
}

function statistics($program)
{
	include("../services/reportRequest.php");

	$vector_curso = [];
	$vector_semestre = [];
	$vector_grupo = [];
	$vector_codigo = [];
	$cantidadCursos=0;
	$typeCurse=0;
	$vector_estudiantes = [];
	$vector_profesor = [];
	$vector_programa = [];
	
	$categoriesResult = Categories(implode(",", $program));

	if ($categoriesResult) {

		foreach ($categoriesResult as $val) {
			$result = NameCategory($val['id']);
			$semester = $result["name"];
			$programa = Program($result['parent']);
			$result = StatisticsInformation($val['id']);
			$estudiantes = StatisticsInformation2($val['id']);
			
			while ($totalestudiantes = $estudiantes->fetch_assoc()) {
				$boolean = cursoInstitucional($typeCurse,$totalestudiantes['course_fullname']);
				($boolean)?$vector_estudiantes[]=$totalestudiantes['user_id']:'';
			}

			while ($column = $result->fetch_assoc()) {

				$boolean = cursoInstitucional($typeCurse,$column['course_fullname']);

				if($boolean){
					$curso = new estadistica();
					//la variable requerida en la función Enrolled es el rol que vamos a buscar 3 Profesor y 5 Estudiante
					$profesorMatricula= Enrolled($column['course_id'], 3);
					$profesoresMatriculados = $profesorMatricula->fetch_assoc();

					$estudianteMatricula= Enrolled($column['course_id'], 5);
					$estudiantesMatriculados = $estudianteMatricula->fetch_assoc();
					//---------

					$grupo = explode("*", $column['course_fullname']);
					$codigo = codigo($column['course_shortname']);				
					$curso->setSemestre($semester);
					$curso->setPrograma($programa);
					$curso->setCodigo($codigo);
					$curso->setGrupo($grupo[count($grupo) - 1]);
					$curso->setNombreCurso($column['course_fullname'] );
					$curso->setNombreProfesor(ucwords(strtolower($column['firstname'])) . " " . ucwords(strtolower($column['lastname'])));
					$curso->setEstudiantes($estudiantesMatriculados['matriculados']-$profesoresMatriculados['matriculados']);

					$vector_programa []= $curso->getPrograma();
					$vector_semestre []= $curso->getSemestre();
					$vector_grupo[] = $curso->getGrupo();
					$vector_codigo[] = $curso->getCodigo();
					$vector_profesor[] = $curso->getNombreProfesor();
					$vector_curso[] = $curso;
				}{
					
				}

			}
		}
	}
	echo "
	<table class='table'>
		<tr class='td2'>
			<th class='td2'>Fecha</th>
			<th class='td2'>Semestre</th>
			<th class='td2'>Grupo</th>
			<th class='td2'>Código Asignatura</th>
			<th class='td2'>Nombre Asignatura</th>
			<th class='td2'>N. Estudiantes</th>
			<th class='td2'>Profesor a Cargo</th>
			<th class='td2'>Programa</th>
		</tr>
		";
	$cantidadSemestres  = count(elementosUnicos($vector_semestre));
	$cantidadGrupos = count(elementosUnicos($vector_grupo));
	$cantidadCodigos = count(elementosUnicos($vector_codigo));
	$cantidadProfesores = count(elementosUnicos($vector_profesor));
	$cantidadEstudiantes = count(elementosUnicos($vector_estudiantes))-$cantidadProfesores;
	$cantidadProgramas = count(elementosUnicos($vector_programa));

	$fecha = date("Y-m-d H:i:s");

	foreach ($vector_curso as $curse) {
		$cantidadCursos++;
		echo ("
		<tr class='tr5'>
		<td class='tr5'>".$fecha."</td>
		<td class='tr5'>" . $curse->getSemestre() . "</td>
		<td class='tr5'>" . $curse->getGrupo() . "</td>
		<td class='tr5'>" . $curse->getCodigo() . "</td>
		<td class='tr5'>" . $curse->getNombreCurso() . "</td>
		<td class='tr5'>" . $curse->getEstudiantes() . "</td>
		<td class='tr5'>" . $curse->getNombreProfesor() . "</td>
		<td class='tr5'>" . $curse->getPrograma() . "</td>
		");
	}
	echo "
		<tr class='td2'>
		<td class='td2'>Total</td>
		<td class='td2'>" . $cantidadSemestres . "</td>
		<td class='td2'>" . $cantidadGrupos . "</td>
		<td class='td2'>" . $cantidadCodigos . "</td>
		<td class='td2'>" . $cantidadCursos . "</td>
		<td class='td2'>" . $cantidadEstudiantes . "</td>
		<td class='td2'>" . $cantidadProfesores . "</td>
		<td class='td2'>" . $cantidadProgramas . "</td>
		</tr>
	</table>
	";
}
