
<?php
	
	
	function showCountSeccions ($category,$program,$semester){
		include("../database/reportRequest.php");
		$categoriesResult = Categories(implode(",",$program));
		echo "<table border='1' cellspacing='0' cellpadding='0'>
			<tr class='tr'>
				<td>PROGRAMA</td>
				<td>SEMESTRE</td>
				<td>CURSO</td>
				<td>NOMBRE CURSO</td>
				<td>numero de unidades</td>
		  	</tr>";
		foreach($categoriesResult as $val){
			$result = NameCategory($val['id']);
			$semester = $result["name"];
			$program = Program($result['parent']);
			$color_rows=1;
			while ($columna = $result->fetch_assoc()){
				 // var_dump($columna);
				if ($color_rows == 0) {
					$class_row = "td1";
					$color_rows = 1;
				}else{
					$class_row = "td2";
					$color_rows = 0;
				}
				$resultContenido = seccion($columna['course_id']); 
				$fila= "
					<tr class='".$class_row."'>
						<td>$program</td>
						<td>$semester</td>
						<td>" . $columna['course_id'] . "</td>
						<td>" . $columna['course_fullname'] . "</td>";
				$contador=8;
				$totalValidaciones=0;
				$cumple=0;
				while ($resultInformacion = $resultContenido->fetch_assoc()){
					$countSeccion=$resultInformacion['conteo'];
						$fila.= "<td>".$countSeccion."</td></tr>";			
		
				}
			
			}
		}
		echo "</table>";
	}
?>