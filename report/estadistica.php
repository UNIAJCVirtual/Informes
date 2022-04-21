<?php
include("../class/model.php");


//Ordenar array con el método sort

function ordenar($a, $b)
{
    $a = preg_replace('/[^0-9]/','',$a->{'grupo'});
    $a = substr($a,0,1);
    $b = preg_replace('/[^0-9]/','',$b->{'grupo'});
    $b = substr($b,0,1);
   if ($a < $b){
	return -1;
   }      
   else if ($a > $b){
	return 1;
   }
   return 0;
}
//----------------------------------------------

function groupFilter1($str)
{
	$temp = $str[strlen($str) - 1];
	//echo $str."<br>";
	// se empieza del antepenultimo para tener la letra A B C o cualquiera
	for ($i = (strlen($str) - 2); $i >= 0; $i--) {
		if (is_numeric($str[$i])) {
			$temp = $str[$i] . "" . $temp;
		} else {
			if ($i > 0) {
				if ($str[$i] == "I" and $str[$i - 1] == "I") {
					$temp = $str[$i - 1] . "" . $str[$i] . "" . $temp;
					return $temp;
				} else if ($str[$i] == "I" and !($str[$i - 1] == "I")) {$temp = $str[$i] . "" . $temp;
					return $temp;
				} else if ($str[$i] == "F" and !($str[$i - 1] == "F")) {$temp = $str[$i] . "" . $temp;
					return $temp;
				} else if ($str[$i] == "S" and $i > -1) {if ($str[$i - 1] == "B") {$temp = $str[$i - 1] . $str[$i] . "" . $temp;
					return $temp;
					}
				} else if ($str[$i] == "S" and $str[$i - 1] == "I") {$temp = $str[$i - 1] . "" . $str[$i] . "" . $temp;
					return $temp;
				} else if (($str[$i] == "S" and $str[$i - 1] == "S") or ($str[$i] == "S")) {$temp = $str[$i] . "" . $temp;
					return $temp;
				} else {
					return $temp;
				}
			} else if ($i == 0) {$temp = $str[$i] . "" . $temp;
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
	$result = $str[strlen($str) - 1];
	for ($i = (strlen($str) - 2); $i >= 0; $i--) {
		if ($end and $i > $limit) {$result = $str[$i] . "" . $str[$i];
		} else {
			if (is_numeric($str[$i])) {$result = $str[$i] . "" . $result;
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
function statistics($program)
{
	include("../database/reportRequest.php");

	$vector_curso = [];
	$categoriesResult = Categories(implode(",", $program));

	if ($categoriesResult) {
		foreach ($categoriesResult as $val) {

			$result = NameCategory($val['id']);
			$semester = $result["name"];
			$programa = Program($result['parent']);
			$result = StatisticsInformation($val['id']);

			while ($column = $result->fetch_assoc()) {

                $curso = new estadistica();
				$email = trim(strtolower($column['email']));
				$resultMatricula = Enrolled($column['course_id'], $val['id']);
				$resultMatriculados = $resultMatricula->fetch_assoc();
				$grupo = explode("*", $column['course_fullname']);

			    $curso->setSemestre($semester);
			    $curso->setCodigo("ejemplo");
			    $curso->setGrupo($grupo[count($grupo) - 1]);
			    $curso->setNombreCurso($column['course_fullname']);
                $curso->setNombreProfesor(ucwords(strtolower($column['firstname'])) . " " . ucwords(strtolower($column['lastname'])));
                $curso->setCantidad($resultMatriculados['matriculados']);
                $vector_curso[] = $curso;
			}
            echo "
            <table class='table	'>
		        <tr class='td1'>
		            <th colspan='6'>ESTADISTICA DE LOS CURSOS EN AULAS VIRTUALES MOODLE ".$programa."</th>
		        </tr>
                <tr class='td1'>
		            <th>Semestre</th>
		            <th>Grupo</th>
		            <th>Código Asignatura</th>
		            <th>Nombre Asignatura</th>
		            <th>Profesor a Cargo</th>
		            <th>N. Estudiantes</th>
		        </tr>
                ";

                usort($vector_curso,'ordenar');

            foreach($vector_curso as $curse){

                echo"
                <tr class='tr5'>
				<td>".$curse->getSemestre()."</td>
				<td>".$curse->getGrupo()."</td>
				<td>".$curse->getCodigo()."</td>
				<td>".$curse->getNombreCurso()."</td>
				<td>".$curse->getNombreProfesor()."</td>
				<td>".$curse->getCantidad()."</td>";
		    }
	    }
    }
}
?>