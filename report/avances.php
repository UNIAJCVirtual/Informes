<?php

include("../class/model.php");

//Ordenar array con el método sort

function ordenar($ar1, $ar2)
{
   if ($ar1->{'porcentaje'} > $ar2->{'porcentaje'}){
	return -1;
   }      
   else if ($ar1->{'porcentaje'}<$ar2->{'porcentaje'}){
	return 1;
   }
   return 0;
}
//----------------------------------------------

//Imprimir los items con la evaluación en una tabla

function headerDetail($items,$evaluacion)
{
	$result = "<table class='tables'>
				<tr  class='tr5'>";

	if (count($items) > 0) {

		for($i = 0; $i < count($items); ++$i) {
			$result .= "<td class='tr5'>".$items[$i]."</td>";
		}
		$result .= "</tr> <tr class='tr5'>";
		for($i = 0; $i < count($evaluacion); ++$i) {
			$result .= "<td class='tr5'>".$evaluacion[$i]."</td>";
		}

	} else {
		$result .= "<td class='tr5'>Sin actividades</td>";
	}
	$result.= "</tr></table>";

	return $result;
}
//----------------------------------------------

function advanceReport($category, $program, $semester, $type_report)
{
	include("../database/reportRequest.php");
    $vector_curso = [];
	$teachesResult = Teachers(implode(",", $program));
	$verde=0;
	$amarillo=0;
	$rojoClaro=0;
	$rojoOscuro=0;

	if ($teachesResult->num_rows > 0) {

		foreach ($teachesResult as $value) {
			
			$cumple = 0;
			$noCumple = 0;
            $curso = new avance();
			$semester = NameCategory($value['cat']);
			$program = Program($semester["parent"]);
			$coursesResult = Courses($value['courseid'], $value['userid'], $type_report);
			
			

            foreach ($coursesResult as $valueC) {

                $curso->setIdUser($value['userid']);
			    $curso->setNombreProfesor(ucwords(strtolower($value['mdl_user_firstname'])) . " " . ucwords(strtolower($value['mdl_user_lastname'])));
			    $curso->setCorreo($value["mdl_user_email"]);
			    $curso->setPrograma($program);
			    $curso->setSemestre($semester["name"]);
			    $curso->setNombreCurso($valueC['mdl_course_fullname']);
				$recordItem = ItemCourse($value['courseid'], strtoupper($type_report));
				$cantidad = $recordItem->num_rows;

				if ($cantidad > 0) {

					foreach ($recordItem as $recordItems) {
						if ($recordItems["itemmodule"] == "forum") {

							$curso->items []= "Nota: ".$recordItems["name"];

							//calificaciones del foro
							$score = ScoreItem($recordItems["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->evaluacion []= $score;

							//retroalimentación del foro
							$curso->items []= "Retroalimentación: ".$recordItems["name"];
							$resultFeedback = FeedbackForum1($valueC['id'], $recordItems["iteminstance"]);

							if ($resultFeedback->num_rows > 0) {
								$feed1 = $resultFeedback->fetch_assoc();
								$resultFeedback = FeedbackForum2($feed1['id'], $value["userid"]);
								($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
								$curso->evaluacion []=$resultFeedback;
							} else {
								$curso->evaluacion []="NO CUMPLE";
								$noCumple++;
							}

						}elseif ($recordItems["itemmodule"] == "assign") {

							//calificaciones de la tarea

							$curso->items []= "Nota: ".$recordItems["name"];					

							$score = ScoreItem($recordItems["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->evaluacion []= $score;

							//retroalimentación de la tarea

							$curso->items []= "Retroalimentación: ".$recordItems["name"];

							$resultFeedback = FeedbackActivity($recordItems["iteminstance"]);
							($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->evaluacion []=$resultFeedback;

							
						}elseif ($recordItems["itemmodule"] == "quiz") {

							//calificaciones del quiz
							$curso->items []= "Nota: ".$recordItems["name"];	
							$curso->evaluacion []= "CUMPLE";
							
							//retroalimentaciones del quiz
							$curso->items []= "Retroalimentación: ".$recordItems["name"];
							$curso->evaluacion []= "NO APLICA";
							$cumple++;
							
						}							
					}
				}
					$total = (($cumple + $noCumple) == 0) ? -1 : ($cumple + $noCumple);
					$per = ($total == -1) ? -1 : round(((100 / $total) * $cumple), 2);
					$curso->setPorcentaje($per);
			}
			$vector_curso[] = $curso;
		}
		usort($vector_curso,'ordenar');
		echo "
		<table class='tables'>
			<tr class='td1'>
			<th class='td1'>ID user</th>
			<th class='td1'>Nombre</th>
			<th class='td1'>Correo</th>
			<th class='td1'>Curso</th>
			<th class='td1'>Programa</th>
			<th class='td1'>Semestre</th>
			<th class='td1'>Detalle</th>
			<th class='td1'>Porcentaje</th>
		  	</tr>";

		foreach($vector_curso as $curse){

			if($curse->getPorcentaje() >= 80 && $curse->getPorcentaje() <= 100){
				
				//Porcentaje verde
				$verde++;
				print("<tr class='tr1'>
				<td class='tr1'>".$curse->getIdUser()."</td>
				<td class='tr1'>".$curse->getNombreProfesor()."</td>
				<td class='tr1'>".$curse->getCorreo()."</td>				
				<td class='tr1'>".$curse->getNombreCurso()."</td>
				<td class='tr1'>".$curse->getPrograma()."</td>
				<td class='tr1'>".$curse->getSemestre()."</td>
				<td class='tr1'>".headerDetail($curse->items,$curse->evaluacion)."</td>
				<td class='tr1'>".$curse->getPorcentaje()."%"."</td>
				</tr>");			
		
			} elseif ($curse->getPorcentaje() >= 51 && $curse->getPorcentaje() <= 79){
				//Porcentaje amarillo
				
				$amarillo++;
				print("<tr class='tr2'>
				<td class='tr2'>".$curse->getIdUser()."</td>
				<td class='tr2'>".$curse->getNombreProfesor()."</td>
				<td class='tr2'>".$curse->getCorreo()."</td>				
				<td class='tr2'>".$curse->getNombreCurso()."</td>
				<td class='tr2'>".$curse->getPrograma()."</td>
				<td class='tr2'>".$curse->getSemestre()."</td>
				<td class='tr2'>".headerDetail($curse->items,$curse->evaluacion)."</td>
				<td class='tr2'>".$curse->getPorcentaje()."%"."</td>
				</tr>");			
	
			} elseif ($curse->getPorcentaje() >= 0 && $curse->getPorcentaje() <= 50 ){
			//Porcentaje rojo claro
			
				$rojoClaro++;
				print("<tr class='tr3' >
				<td class='tr3'>".$curse->getIdUser()."</td>
				<td class='tr3'>".$curse->getNombreProfesor()."</td>
				<td class='tr3'>".$curse->getCorreo()."</td>				
				<td class='tr3'>".$curse->getNombreCurso()."</td>
				<td class='tr3'>".$curse->getPrograma()."</td>
				<td class='tr3'>".$curse->getSemestre()."</td>
				<td class='tr3'>".headerDetail($curse->items,$curse->evaluacion)."</td>
				<td class='tr3'>".$curse->getPorcentaje()."%"."</td>
				</tr>");			
		

			} elseif ($curse->getPorcentaje()== -1){
				//Porcentaje rojo oscuro
				$rojoOscuro++;
				print("<tr class='tr4' >
				<td class='tr4'>".$curse->getIdUser()."</td>
				<td class='tr4'>".$curse->getNombreProfesor()."</td>
				<td class='tr4'>".$curse->getCorreo()."</td>				
				<td class='tr4'>".$curse->getNombreCurso()."</td>
				<td class='tr4'>".$curse->getPrograma()."</td>
				<td class='tr4'>".$curse->getSemestre()."</td>
				<td class='tr4'>".headerDetail($curse->items,$curse->evaluacion)."</td>
				<td class='tr4'>Sin actividades</td>
				</tr>");
			}
		}
		echo "</table>";

		$sum=$verde+$amarillo+$rojoClaro+$rojoOscuro;
	
		echo"
		</table>
		<br>
	
		<table class='tables'>
	
			<tr class='td1'>
			<td class='td1' colspan='2'>Porcentajes</th>
			<td class='td1' colspan='2'>Cantidad de cursos	</th>
			</tr>
	
			<tr class='tr1'>
			<td class='tr1' colspan='2' > 100% - 80% </td>
			<td class='tr1' colspan='2'>".$verde."</td>
			</tr>
			<tr class='tr2'>
			<td class='tr2' colspan='2'> 79% - 51% </td>
			<td class='tr2' colspan='2'>".$amarillo."</td>
			</tr>
			<tr class='tr3'>
			<td class='tr3' colspan='2'> 50% - 0% </td>
			<td class='tr3' colspan='2'>".$rojoClaro."</td>
			</tr>
			<tr class='tr4'>
			<td class='tr4' colspan='2'> Sin actividades </td>
			<td class='tr4' colspan='2'>".$rojoOscuro."</td>
			</tr>
			<tr class='td1'>
			<td class='td1' colspan='2'> Total de cursos </td>
			<td class='td1' colspan='2'>".$sum."</td>
			</tr>
		</table>
		";
	}else{
		echo("<b> En estos momentos el programa: ".Program(implode(",", $program))."  no cuenta con cursos.</b><br>");
	}
}