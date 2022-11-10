<?php

include("../models/avance_model.php");

function quitar_tildes($cadena)
{
	$cade = utf8_decode($cadena);
	$no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú","?");
	$permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U","ñ");
	$texto = str_replace($no_permitidas, $permitidas, $cade);
	return $texto;
}
function headerItems($cantItems)
{
	$result = "";
	if ($cantItems > 0) {
		for($i = 1; $i < $cantItems+1; ++$i) {
			$result .= "<th nowrap>Nombre de la actividad ".$i."</th>";
			$result .= "<th nowrap>Calificaciones ".$i."</th>";
			$result .= "<th nowrap>Retroalimentaciones ".$i."</th>";
		}
	} else {
		$result .= "<th nowrap>No hay actividades por mostrar</th>";
	}

	return $result;
}

function items($items,$cantItems)
{
	$result = "";
	$cantItems*=3;
	if (count($items) > 0) {
		for($i = 0; $i < $cantItems; ++$i) {
			$result .= count($items) > $i ? "<td nowrap>".$items[$i]."</td>" : "<td></td>";
		}
	} else {
		$result .= "<td nowrap>Sin actividades</td>";
		for($i = 0; $i < $cantItems-1; ++$i) {
			$result .= "<td></td>";
		}
	}
	return $result;
}

function advanceReport($program, $semestre, $type_report)
{
	include("../services/reportRequest.php");
    $vector_curso = [];
	$teachesResult = Teachers(implode(",", $program));

	if ($teachesResult->num_rows > 0) {

		foreach ($teachesResult as $value) {
			
			$cumple = 0;
			$noCumple = 0;
            $curso = new avance();
			$semestre = NameCategory($value['cat']);
			$program = Program($semestre["parent"]);
			$coursesResult = Courses($value['courseid'], $value['userid'], $type_report);
			$cantidadItems = 0;
			

            foreach ($coursesResult as $valueC) {

                $curso->setIdUser($value['userid']);
			    $curso->setNombreProfesor(quitar_tildes(ucwords(strtolower($value['mdl_user_firstname'])) . " " . ucwords(strtolower($value['mdl_user_lastname']))));
			    $curso->setCorreo($value["mdl_user_email"]);
			    $curso->setPrograma($program);
			    $curso->setSemestre($semestre["name"]);
			    $curso->setNombreCurso($valueC['mdl_course_fullname']);
				$recordItem = ItemCourse($value['courseid'], strtoupper($type_report));
				$cantidad = $recordItem->num_rows;
				$cantidadItems = ($cantidadItems < $cantidad ) ? $cantidad : $cantidadItems;
				if ($cantidad > 0) {

					foreach ($recordItem as $recordItems) {
						if ($recordItems["itemmodule"] == "forum") {

							$curso->items []= $recordItems["name"];

							//calificaciones del foro
							$score = ScoreItem($recordItems["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->items []= $score;

							//retroalimentación del foro
							$resultFeedback = FeedbackForum1($valueC['id'], $recordItems["iteminstance"]);

							if ($resultFeedback->num_rows > 0) {
								$feed1 = $resultFeedback->fetch_assoc();
								$resultFeedback = FeedbackForum2($feed1['id'], $value["userid"]);
								($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
								$curso->items []=$resultFeedback;
							} else {
								$curso->items []="NO CUMPLE";
								$noCumple++;
							}

						}elseif ($recordItems["itemmodule"] == "assign") {

							$curso->items []= $recordItems["name"];	

                            //calificaciones de la tarea
							$score = ScoreItem($recordItems["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->items []= $score;
							//retroalimentación de la tarea
							$resultFeedback = FeedbackActivity($recordItems["iteminstance"]);
							($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->items []=$resultFeedback;

							
						}elseif ($recordItems["itemmodule"] == "quiz") {

							$curso->items []= $recordItems["name"];
							//calificaciones del quiz	
							$curso->items []= "CUMPLE";
							//retroalimentaciones del quiz
							$curso->items []= "NO APLICA";
							$cumple++;
						}							
					}
				}
					$total = (($cumple + $noCumple) == 0) ? -1 : ($cumple + $noCumple);
					$per = ($total == -1) ? -1 : round(((100 / $total) * $cumple));
					$curso->setPorcentaje($per);
			}
			$vector_curso[] = $curso;
		}
		echo "
		<table id='table' class='display'>
			<thead>
				<tr>
					<th nowrap>ID user</th>
					<th nowrap>Nombre</th>
					<th nowrap>Correo</th>
					<th nowrap>Curso</th>
					<th nowrap>Programa</th>
					<th nowrap>Semestre</th>"
					.headerItems($cantidadItems)."
					<th nowrap>Porcentaje</th>
		  		</tr>
			</thead>
			<tbody id='tbody'>";

		foreach($vector_curso as $curse){

			print("
				<tr id='item'>
					<td nowrap>".$curse->getIdUser()."</td>
					<td nowrap>".$curse->getNombreProfesor()."</td>
					<td nowrap>".$curse->getCorreo()."</td>				
					<td nowrap>".$curse->getNombreCurso()."</td>
					<td nowrap>".$curse->getPrograma()."</td>
					<td nowrap>".$curse->getSemestre()."</td>"
					.items($curse->items,$cantidadItems)."
					<td nowrap>".$curse->getPorcentaje()."</td>
				</tr>");
		}
		echo("
		</tbody>
		</table>");
	}else{
		echo("<b> En estos momentos el programa: ".Program(implode(",", $program))."  no cuenta con cursos.</b><br>");
	}
	
}