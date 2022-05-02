<?php
include("../class/model.php");


//Ordenar array con el método sort

function ordenar($a, $b)
{
    /*$a = preg_replace('/[^0-9]/','',$a->{'grupo'});
    $a = substr($a,0,1);
    $b = preg_replace('/[^0-9]/','',$b->{'grupo'});
    $b = substr($b,0,1);*/

   if ($a->{'grupo'} < $b->{'grupo'} ){
	return -1;
   }      
   else if ($a->{'grupo'} > $b->{'grupo'} ){
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

function codigo($codigo){
	
	if(strstr($codigo, 'FD')){
		return $codigo=strstr($codigo, 'FD');
	} else if (strstr($codigo, 'CB')){
		return $codigo=strstr($codigo, 'CB');
	} else if (strstr($codigo, 'FI')){
		return $codigo=strstr($codigo, 'FI');
	}

}


function statistics($program)
{
	include("../database/reportRequest.php");

	$vector_curso = [];
    $vector_grupo = [];
    $vector_profesor = [];
	$totalEstudiantes=[];
	$categoriesResult = Categories(implode(",", $program));

	if ($categoriesResult) {
        
		foreach ($categoriesResult as $val) {
            $sum=0;
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
				$codigo = codigo($column['course_shortname']);

			    $curso->setSemestre($semester);
			    $curso->setCodigo($codigo);
			    $curso->setGrupo($grupo[count($grupo) - 1]);
			    $curso->setNombreCurso($column['course_fullname']);
                $curso->setNombreProfesor(ucwords(strtolower($column['firstname'])) . " " . ucwords(strtolower($column['lastname'])));
                $curso->setCantidad($resultMatriculados['matriculados']);

                $vector_grupo[] = $curso->getGrupo();
                $vector_profesor[] = $curso->getNombreProfesor();
                $vector_curso[] = $curso;
			}
			
			$numGrup = count($vector_grupo);
            echo "
            <table class='table' id='".$numGrup."'>
		        <tr class='td2'>
		            <th colspan='7'>ESTADISTICA DE LOS CURSOS EN AULAS VIRTUALES MOODLE ".$programa."</th>
		        </tr>
                <tr class='td2'>
		            <th class='td2'>Semestre</th>
		            <th class='td2'>Grupo</th>
		            <th class='td2'>Código Asignatura</th>
		            <th class='td2'>Nombre Asignatura</th>
		            <th class='td2'>Profesor a Cargo</th>
		            <th class='td2' colspan='2'>N. Estudiantes</th>
		        </tr>
                ";

                usort($vector_curso,'ordenar');
				$profesor = array_unique($vector_profesor);
				$numeroProfesores = count($profesor);

				$grupo = array_unique($vector_grupo);
				$numeroGrupos = count($grupo);

				$i=0;
			while($numeroGrupos > 0){
				$moda[]=0;
				$sumEstudiantes=0;

				foreach($vector_curso as $curse){     
				if($grupo[$i] === $curse->getGrupo()){
					$moda[]=$curse->getCantidad();
					array_count_values($moda);
					$sumEstudiantes=$moda[1];
				}
				}
				$totalEstudiantes[]=$sumEstudiantes;

				$i++;
				$numeroGrupos--;
			}			
            foreach($vector_curso as $curse){
                $sum++;
                echo"
                <tr class='tr5'>
				<td class='tr5'>".$curse->getSemestre()."</td>
				<td class='tr5' id='group' >".$curse->getGrupo()."</td>
				<td class='tr5'>".$curse->getCodigo()."</td>
				<td class='tr5'>".$curse->getNombreCurso()."</td>
				<td class='tr5'>".$curse->getNombreProfesor()."</td>
				<td class='tr5'colspan='2' id='".$curse->getGrupo()."'>".$curse->getCantidad()."</td>";               
		    }
            echo"
		<tr class='td2'>
		<td class='td2' colspan='2'>Total de cursos</td>
		<td class='td2'>".$sum."</td>
		<td class='td2'>Total de profesores</td>
		<td class='td2'>".$numeroProfesores."</td>
		<td class='td2'>Total estudiantes</td>
		<td class='td2'>".array_sum($totalEstudiantes)."</td>
		</tr>
	</table>
	";
	    }
    }
}
?>