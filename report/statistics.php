
<?php
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
	$categoriesResult = Categories(implode(",", $program));
	echo "
			<table border='1' cellspacing='1' cellpadding='0'>
			<thead>
				<tr class='tr'>
					<th>Programa</th>
					<th>Semestre</th>
					<th>GRUPO</th>
					<th>Nombre CURSO</th>
					<th>Matriculados</th>
					<th>NOMBRE</th>
			  	</tr>
			</thead>";
	if ($categoriesResult) {
		foreach ($categoriesResult as $val) {
			$result = NameCategory($val['id']);
			$semester = $result["name"];
			$programa = Program($result['parent']);
			$result = StatisticsInformation($val['id']);
			$color_rows = 1;
			while ($column = $result->fetch_assoc()) {
				$email = trim(strtolower($column['email']));
				$resultMatricula = Enrolled($column['course_id'], $val['id']);
				$resultMatriculados = $resultMatricula->fetch_assoc();
				$grupo = explode("*", $column['course_fullname']);
				if ($color_rows == 0) {
					$class_row = "td1";
					$color_rows = 1;
				} else {
					$class_row = "td2";
					$color_rows = 0;
				}
				$fila = "
						<tr class='" . $class_row . "'>
							<td>" . $programa . "</td>
							<td>" . $semester . "</td>
							<td>" . $grupo[count($grupo) - 1] . "</td>
							<td>" . $column['course_fullname'] . "</td>
							<td>" . $resultMatriculados['matriculados'] . "</td>
							<td>" . ucwords(strtolower($column['firstname'])) . " " . ucwords(strtolower($column['lastname'])) . "</td>";
				echo $fila . "</tr>";
			}
		}
	}
	echo "</table>";
}
?>